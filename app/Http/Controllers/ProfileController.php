<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Pengelola;
use App\Models\Penyewa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     */
    public function index()
    {
        $user = Auth::user();
        $profileData = null;

        
        // Cek role user
        if ($user->hasRole('Admin')) {
            // Jika admin, ambil data dari table users
            $profileData = $user;
        } elseif ($user->hasRole('Pengelola')) {
            // Jika pengelola, ambil data dari table pengelola
            // dd(Pengelola::all());
            // dd( Pengelola::where('user_id', '=',$user->id)->get());

            $profileData = Pengelola::where('user_id', '=',$user->id)->first();
            
            // Jika data pengelola tidak ditemukan, redirect dengan error
            if (!$profileData) {
                return redirect()->back()->with('error', 'Data pengelola tidak ditemukan.');
            }
        } elseif ($user->hasRole('Penyewa')) {
            // Jika penyewa, ambil data dari table penyewa
            $profileData = Penyewa::where('user_id', $user->id)->first();
            
            // Jika data penyewa tidak ditemukan, redirect dengan error
            if (!$profileData) {
                return redirect()->back()->with('error', 'Data penyewa tidak ditemukan.');
            }
        } else {
            // Role lain, gunakan data user default
            $profileData = $user;
        }


        
        return view('profile.index', compact('user', 'profileData'));
    }
    
    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $user = Auth::user();
        $profileData = null;
        
        if ($user->hasRole('Admin')) {
            $profileData = $user;
        } elseif ($user->hasRole('Pengelola')) {
            $profileData = Pengelola::where('user_id', $user->id)->first();
            
            if (!$profileData) {
                return redirect()->back()->with('error', 'Data pengelola tidak ditemukan.');
            }
        } elseif ($user->hasRole('Penyewa')) {
            $profileData = Penyewa::where('user_id', $user->id)->first();
            
            if (!$profileData) {
                return redirect()->back()->with('error', 'Data penyewa tidak ditemukan.');
            }
        } else {
            $profileData = $user;
        }
        
        return view('profile.edit', compact('user', 'profileData'));
    }
    
    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        try {
            DB::beginTransaction();
            
            if ($user->hasRole('Admin')) {
                // Update data admin
                $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email,' . $user->id,
                    'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                ]);
                
                $data = $request->only(['name', 'email']);
                
                // Handle foto upload untuk admin
                if ($request->hasFile('foto')) {
                    // Hapus foto lama jika ada
                    if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                        Storage::disk('public')->delete($user->foto);
                    }
                    $data['foto'] = $request->file('foto')->store('admin_fotos', 'public');
                }
                
                $user->update($data);
                
            } elseif ($user->hasRole('Pengelola')) {
                // Update data pengelola
                $request->validate([
                    'nama' => 'required|string|max:255',
                    'no_telp_pengelola' => 'required|string|max:15',
                    'alamat' => 'required|string|max:255',
                    'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                    'deskripsi' => 'nullable|string',
                    'email' => 'required|email|unique:users,email,' . $user->id,
                ]);
                
                $pengelola = Pengelola::where('user_id', $user->id)->first();
                
                if (!$pengelola) {
                    throw new \Exception('Data pengelola tidak ditemukan.');
                }
                
                // Update data pengelola
                $pengelolaData = $request->only(['nama', 'no_telp_pengelola', 'alamat', 'deskripsi']);
                
                // Handle foto upload untuk pengelola
                if ($request->hasFile('foto')) {
                    // Hapus foto lama jika ada
                    if ($pengelola->foto && Storage::disk('public')->exists($pengelola->foto)) {
                        Storage::disk('public')->delete($pengelola->foto);
                    }
                    $pengelolaData['foto'] = $request->file('foto')->store('pengelola_fotos', 'public');
                }
                
                $pengelolaData['updated_by'] = Auth::id();
                $pengelola->update($pengelolaData);
                
                // Update email di table users
                $user->update([
                    'email' => $request->email,
                    'name' => $request->nama // Sinkronkan nama juga
                ]);
                
            } elseif ($user->hasRole('Penyewa')) {
                // Update data penyewa
                $request->validate([
                    'nama' => 'required|string|max:255',
                    'nohp' => 'required|string|max:15',
                    'alamat' => 'required|string|max:255',
                    'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
                    'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                    'email' => 'required|email|unique:users,email,' . $user->id,
                ]);
                
                $penyewa = Penyewa::where('user_id', $user->id)->first();
                
                if (!$penyewa) {
                    throw new \Exception('Data penyewa tidak ditemukan.');
                }
                
                // Update data penyewa
                $penyewaData = $request->only(['nama', 'nohp', 'alamat', 'jenis_kelamin']);
                
                // Handle foto upload untuk penyewa
                if ($request->hasFile('foto')) {
                    // Hapus foto lama jika ada
                    if ($penyewa->foto && Storage::disk('public')->exists($penyewa->foto)) {
                        Storage::disk('public')->delete($penyewa->foto);
                    }
                    $penyewaData['foto'] = $request->file('foto')->store('penyewa_fotos', 'public');
                }
                
                $penyewaData['updated_by'] = Auth::id();
                $penyewa->update($penyewaData);
                
                // Update email di table users
                $user->update([
                    'email' => $request->email,
                    'name' => $request->nama // Sinkronkan nama juga
                ]);
            }
            
            DB::commit();
            return redirect()->route('profile.index')->with('success', 'Profile berhasil diperbarui.');
            
        } catch (\Exception $e) {
            DB::rollback();
            
            // Hapus foto yang baru diupload jika ada error
            if (isset($penyewaData['foto']) && Storage::disk('public')->exists($penyewaData['foto'])) {
                Storage::disk('public')->delete($penyewaData['foto']);
            }
            if (isset($pengelolaData['foto']) && Storage::disk('public')->exists($pengelolaData['foto'])) {
                Storage::disk('public')->delete($pengelolaData['foto']);
            }
            if (isset($data['foto']) && Storage::disk('public')->exists($data['foto'])) {
                Storage::disk('public')->delete($data['foto']);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for changing password.
     */
    public function changePasswordForm()
    {
        return view('profile.change-password');
    }
    
    /**
     * Update the user's password.
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);
        
        $user = Auth::user();
        
        // Cek password saat ini
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->with('error', 'Password saat ini tidak sesuai.');
        }
        
        // Update password
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah.');
    }
}