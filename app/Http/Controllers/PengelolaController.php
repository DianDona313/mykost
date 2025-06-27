<?php

namespace App\Http\Controllers;

use App\Models\Pengelola;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class PengelolaController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:pengelola-list|pengelola-create|pengelola-edit|pengelola-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:pengelola-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:pengelola-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:pengelola-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Pengelola::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('foto', function ($row) {
                    return '<img src="' . asset('storage/' . $row->foto) . '" width="50">';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group" role="group" style="overflow: hidden;">';

                    // Tombol Detail (biru muda)
                    $btn .= '<a href="' . route('pengelolas.show', $row->id) . '" 
        class="btn btn-sm text-white" 
        style="background-color:#60a5fa; border-radius: 0.375rem 0 0 0.375rem;" 
        title="Detail">
        <i class="fas fa-eye"></i>
    </a>';

                    // Tombol Edit (biru)
                    if (auth()->user()->can('pengelola-edit')) {
                        $btn .= '<a href="' . route('pengelolas.edit', $row->id) . '" 
            class="btn btn-sm text-white" 
            style="background-color:#3b82f6; border-radius: 0; margin-left:-1px;" 
            title="Edit">
            <i class="fas fa-edit"></i>
        </a>';
                    }

                    // Tombol Hapus (merah)
                    if (auth()->user()->can('pengelola-delete')) {
                        $btn .= '
        <form action="' . route('pengelolas.destroy', $row->id) . '" method="POST" class="d-inline" 
              onsubmit="return confirm(\'Hapus pengelola ini?\');" style="margin-left:-1px;">
            ' . csrf_field() . method_field('DELETE') . '
            <button type="submit" class="btn btn-sm text-white" 
                    style="background-color:#ef4444; border-radius: 0 0.375rem 0.375rem 0;" 
                    title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        </form>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })

                ->rawColumns(['foto', 'action'])
                ->make(true);
        }

        return view('pengelolas.index');
    }

    /**
     * Menampilkan form untuk membuat pengelola baru.
     */
    public function create()
    {
        return view('pengelolas.create');
    }

    /**
     * Menyimpan data pengelola baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_telp_pengelola' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'nullable|string',
            'email' => 'required|email|unique:users,email' // Tambahkan unique validation
        ]);

        try {
            DB::beginTransaction();

            $data = $request->all();

            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('pengelola_fotos', 'public');
            }

            $data['created_by'] = Auth::id();
            $data['updated_by'] = Auth::id();

            // Buat user baru
            $user = User::create([
                'name' => $data['nama'], // Perbaiki: gunakan 'nama' bukan 'name'
                'email' => $data['email'],
                'email_verified_at' => now(),
                'foto' => $data['foto'],
                'password' => Hash::make('12341234') // Pertimbangkan untuk generate password random
            ]);

            // Assign role "Pengelola" ke user
            $user->assignRole('Pengelola');

            // Atau jika menggunakan role ID:
            // $user->roles()->attach(Role::where('name', 'Pengelola')->first()->id);

            // Tambahkan user_id ke data pengelola
            $data['user_id'] = $user->id;

            // Buat record pengelola
            $pengelola = Pengelola::create($data);

            DB::commit();

            return redirect()->route('pengelolas.index')
                ->with('success', 'Pengelola berhasil ditambahkan dengan akun login.');
        } catch (\Exception $e) {
            DB::rollback();

            // Hapus foto jika ada error
            if (isset($data['foto']) && Storage::disk('public')->exists($data['foto'])) {
                Storage::disk('public')->delete($data['foto']);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail pengelola tertentu.
     */
    public function show(Pengelola $pengelola)
    {
        return view('pengelolas.show', compact('pengelola'));
    }

    /**
     * Menampilkan form edit pengelola.
     */
    public function edit(Pengelola $pengelola)
    {
        return view('pengelolas.edit', compact('pengelola'));
    }

    /**
     * Memperbarui data pengelola.
     */
    public function update(Request $request, Pengelola $pengelola)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_telp_pengelola' => 'required|string|max:15',
            'alamat' => 'required|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('pengelola_fotos', 'public');
        }

        $data['updated_by'] = Auth::id();
        $pengelola->update($data);

        return redirect()->route('pengelolas.index')
            ->with('success', 'Pengelola berhasil diperbarui.');
    }

    /**
     * Menghapus (soft delete) pengelola.
     */
    public function destroy(Pengelola $pengelola)
    {
        $pengelola->update(['deleted_by' => Auth::id()]);
        $pengelola->delete();

        return redirect()->route('pengelolas.index')
            ->with('success', 'Pengelola berhasil dihapus.');
    }

    /**
     * Mengembalikan data yang telah dihapus (restore).
     */
    public function restore($id)
    {
        $pengelola = Pengelola::onlyTrashed()->findOrFail($id);
        $pengelola->restore();

        return redirect()->route('pengelolas.index')
            ->with('success', 'Pengelola berhasil dikembalikan.');
    }

    /**
     * Menghapus data secara permanen.
     */
    public function forceDelete($id)
    {
        $pengelola = Pengelola::onlyTrashed()->findOrFail($id);
        $pengelola->forceDelete();

        return redirect()->route('pengelolas.index')
            ->with('success', 'Pengelola berhasil dihapus permanen.');
    }
}
