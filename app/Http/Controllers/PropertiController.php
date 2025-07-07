<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Properties;
use App\Models\Peraturans;
use App\Models\User;
use App\Models\JenisKost;
use App\Models\Metode_Pembayaran;
use App\Models\Pengelola;
use App\Models\Pengelola_properties;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

use function PHPSTORM_META\map;

class PropertiController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:properti-list|properti-create|properti-edit|properti-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:properti-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:properti-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:properti-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();

            // Cek role user
            if ($user->hasRole('Pengelola')) {
                // Ambil ID pengelola berdasarkan user login
                $pengelola = Pengelola::where('user_id', $user->id)->first();

                // Jika pengelola ditemukan
                if ($pengelola) {
                    // Ambil properti yang dimiliki oleh pengelola tersebut (dari relasi many-to-many)
                    $data = $pengelola->properties()->with(['pengelola', 'users'])->get();
                } else {
                    $data = collect(); // kosongkan data jika tidak ditemukan
                }
            } else {
                // Jika admin/superadmin, tampilkan semua properti
                $data = Properties::with(['pengelola', 'users'])->get();
            }

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('created_by', function ($row) {
                    return $row->users->name ?? '-';
                })
                ->addColumn('pengelola', function ($row) {
                    return $row->pengelola->count() ? $row->pengelola->pluck('nama')->join(', ') : '-';
                })
                ->addColumn('foto', function ($row) {
                    if ($row->foto) {
                        return '<img src="' . asset('storage/properti_foto/' . $row->foto) . '" width="50" class="object-cover"/>';
                    }
                    return '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">';
                    $btn .= '<a href="' . route('properties.show', $row->id) . '" class="btn btn-sm btn-info mr-1"><i class="fas fa-eye"></i></a>';
                    $btn .= '<a href="' . route('properties.edit', $row->id) . '" class="btn btn-sm btn-primary mr-1"><i class="fas fa-edit"></i></a>';
                    $btn .= '<button onclick="deleteProperty(' . $row->id . ')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })

                ->rawColumns(['foto', 'action'])
                ->toJson();
        }

        return view('properties.index');
    }


    public function edit($id)
    {
        $property = Properties::with(['peraturans', 'jeniskost', 'pengelola'])->findOrFail($id);
        $peraturans = Peraturans::where('created_by', Auth::id())->get();
        $jeniskosts = JenisKost::all();

        // ambil ID peraturan yang sudah terhubung ke properti
        $selectedPeraturans = $property->peraturans->pluck('id')->toArray();

        return view('properties.edit', compact('property', 'peraturans', 'jeniskosts', 'selectedPeraturans'));
    }


    public function create()
    {
        $peraturans = Peraturans::where('created_by', Auth::id())->get();
        $jenisKosts = JenisKost::all();
        return view('properties.create', compact('peraturans', 'jenisKosts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'jeniskost_id' => 'required|exists:jeniskosts,id',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'peraturan_id' => 'required|array',
            'peraturan_id.*' => 'exists:peraturans,id',
            'metode_pembayaran' => 'required|array|min:1',
            'metode_pembayaran.*.nama_bank' => 'required|string|max:255',
            'metode_pembayaran.*.no_rek' => 'required|string|max:255',
            'metode_pembayaran.*.atas_nama' => 'required|string|max:255',
        ]);

        $fotoPath = $request->file('foto')->store('properti_foto', 'public');
        $created_by = Auth::user()->id;

        // Simpan properti
        $property = Properties::create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'jeniskost_id' => $request->jeniskost_id,
            'foto' => basename($fotoPath),
            'deskripsi' => $request->deskripsi,
            'created_by' => $created_by
        ]);

        // Sync peraturans
        $property->peraturans()->sync($request->peraturan_id);

        // Simpan metode pembayaran dan buat relasi
        $metodePembayaranIds = [];
        foreach ($validated['metode_pembayaran'] as $metodePembayaran) {
            $metode = Metode_Pembayaran::create([
                'nama_bank' => $metodePembayaran['nama_bank'],
                'no_rek' => $metodePembayaran['no_rek'],
                'atas_nama' => $metodePembayaran['atas_nama'],
                'created_by' => $created_by,
            ]);
            $metodePembayaranIds[] = $metode->id;
        }

        // Attach metode pembayaran ke properti
        $property->metode_pembayaran()->attach($metodePembayaranIds);

        // Simpan relasi pengelola
        $pengelola = Pengelola::where('user_id', '=', Auth::user()->id)->first();

        // dd($pengelola);
        Pengelola_properties::create([
            'pengelola_id' => $pengelola->id,
            'properti_id' => $property->id,
            'created_by' => $created_by
        ]);

        return redirect()->route('properties.index')->with('success', 'Properti berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kota' => 'required|string|max:100',
            'jeniskost_id' => 'required|exists:jeniskosts,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'peraturan_id' => 'required|array',
            'peraturan_id.*' => 'exists:peraturans,id',
            'metode_pembayaran' => 'required|array|min:1',
            'metode_pembayaran.*.nama_bank' => 'required|string|max:255',
            'metode_pembayaran.*.no_rek' => 'required|string|max:255',
            'metode_pembayaran.*.atas_nama' => 'required|string|max:255',
        ]);

        $property = Properties::findOrFail($id);
        $updated_by = Auth::user()->id;

        // Data untuk update properti
        $updateData = [
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'kota' => $request->kota,
            'jeniskost_id' => $request->jeniskost_id,
            'deskripsi' => $request->deskripsi,
            'updated_by' => $updated_by,
        ];

        // Handle foto jika ada upload baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($property->foto && Storage::disk('public')->exists('properti_foto/' . $property->foto)) {
                Storage::disk('public')->delete('properti_foto/' . $property->foto);
            }

            $fotoPath = $request->file('foto')->store('properti_foto', 'public');
            $updateData['foto'] = basename($fotoPath);
        }

        // Update properti
        $property->update($updateData);

        // Sync peraturans
        $property->peraturans()->sync($request->peraturan_id);

        // Handle metode pembayaran
        // Hapus relasi metode pembayaran lama
        $oldMetodePembayaranIds = $property->metode_pembayaran()->pluck('metode_pembayarans.id')->toArray();
        $property->metode_pembayaran()->detach();

        // Hapus metode pembayaran lama yang sudah tidak digunakan properti lain
        foreach ($oldMetodePembayaranIds as $oldId) {
            $metode = Metode_Pembayaran::find($oldId);
            if ($metode) {
                // Cek apakah metode pembayaran ini masih digunakan properti lain
                $isUsedByOtherProperty = DB::table('properti_has_metode_pembayaran')
                    ->where('id_metode_pembayaran', $oldId)
                    ->exists();

                if (!$isUsedByOtherProperty) {
                    $metode->update(['deleted_by' => $updated_by]);
                    $metode->delete(); // soft delete
                }
            }
        }

        // Simpan metode pembayaran baru
        $metodePembayaranIds = [];
        foreach ($validated['metode_pembayaran'] as $metodePembayaran) {
            $metode = Metode_Pembayaran::create([
                'nama_bank' => $metodePembayaran['nama_bank'],
                'no_rek' => $metodePembayaran['no_rek'],
                'atas_nama' => $metodePembayaran['atas_nama'],
                'created_by' => $updated_by,
            ]);
            $metodePembayaranIds[] = $metode->id;
        }

        // Attach metode pembayaran baru ke properti
        $property->metode_pembayaran()->attach($metodePembayaranIds);

        return redirect()->route('properties.index')->with('success', 'Properti berhasil diperbarui.');
    }
    public function show($id)
    {
        $property = Properties::with('pengelolas')->findOrFail($id);

        return view('properties.show', compact('property'));
    }


    public function destroy($id)
    {
        $property = Properties::with(['pengelola', 'peraturans', 'metode_pembayaran'])->findOrFail($id);
        $deleted_by = Auth::user()->id;

        // Hapus relasi pengelola jika menggunakan pivot
        if ($property->pengelola()->exists()) {
            // Update deleted_by di tabel pivot jika ada kolom deleted_by
            // atau langsung detach jika tidak ada
            $property->pengelola()->detach();
        }

        // Hapus relasi peraturans jika menggunakan pivot
        if ($property->peraturans()->exists()) {
            $property->peraturans()->detach();
        }

        // Handle metode pembayaran
        if ($property->metode_pembayaran()->exists()) {
            $metodePembayaranIds = $property->metode_pembayaran()->pluck('metode_pembayarans.id')->toArray();

            // Detach dari properti
            $property->metode_pembayaran()->detach();

            // Soft delete metode pembayaran yang sudah tidak digunakan properti lain
            foreach ($metodePembayaranIds as $metodeId) {
                $metode = Metode_Pembayaran::find($metodeId);
                if ($metode) {
                    // Cek apakah metode pembayaran ini masih digunakan properti lain
                    $isUsedByOtherProperty = DB::table('properti_has_metode_pembayaran')
                        ->where('id_metode_pembayaran', $metodeId)
                        ->exists();

                    if (!$isUsedByOtherProperty) {
                        $metode->update(['deleted_by' => $deleted_by]);
                        $metode->delete(); // soft delete
                    }
                }
            }
        }

        // Hapus file foto jika ada
        if ($property->foto && Storage::disk('public')->exists('properti_foto/' . $property->foto)) {
            Storage::disk('public')->delete('properti_foto/' . $property->foto);
        }

        // Update deleted_by sebelum soft delete
        $property->update(['deleted_by' => $deleted_by]);

        // Soft delete properti
        $property->delete();

        return redirect()->route('properties.index')->with('success', 'Properti berhasil dihapus');
    }
    public function detailKost($id_properti)
    {
        $properties = Properties::with([
            'jeniskost',
            'rooms.fasilitas',
            'peraturans',
            'metode_pembayaran'
        ])->findOrFail($id_properti);

        $allFasilitas = $properties->rooms->flatMap(function ($room) {
            return $room->fasilitas;
        })->unique('id')->values();

        $availableRooms = $properties->rooms->where('is_available', 1);

        return view('guest_or_user.detailkost', [
            'properties' => $properties,
            'allFasilitas' => $allFasilitas,
            'availableRooms' => $availableRooms,
            'rooms' => $properties->rooms, // <— ini solusi agar tidak undefined
        ]);
    }


}
