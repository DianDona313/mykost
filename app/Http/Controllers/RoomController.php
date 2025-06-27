<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use App\Models\Pengelola;
use App\Models\Properties;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Builder\Property;
use Yajra\DataTables\DataTables;

class RoomController extends Controller
{



    public function __construct()
    {
        $this->middleware('permission:room-list|room-create|room-edit|room-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:room-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:room-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:room-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->user();

            if ($user->hasRole('Pengelola')) {
                // Ambil pengelola berdasarkan user login
                $pengelola = Pengelola::where('user_id', $user->id)->first();

                if ($pengelola) {
                    // Ambil semua property id yang dikelola
                    $propertyIds = DB::table('properties')
                        ->join('pengelola_properties', 'properties.id', '=', 'pengelola_properties.properti_id')
                        ->where('pengelola_properties.pengelola_id', $pengelola->id)
                        ->whereNull('properties.deleted_at')
                        ->select('properties.id')  // <-- ini yang harus jelas
                        ->pluck('id');

                    // Query rooms yang termasuk di property pengelola
                    $subQuery = Room::selectRaw('MIN(id) as id')
                        ->whereIn('properti_id', $propertyIds) // filter berdasarkan properti pengelola
                        ->groupBy('room_name');

                    $data = Room::with('properti')->whereIn('id', $subQuery)->latest()->get();
                } else {
                    $data = collect(); // kosong jika pengelola tidak ditemukan
                }
            } else {
                // Untuk user selain pengelola tampilkan semua data
                $subQuery = Room::selectRaw('MIN(id) as id')
                    ->groupBy('room_name');

                $data = Room::with('properti')->whereIn('id', $subQuery)->latest()->get();
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('foto', function ($row) {
                    return $row->foto ?? '-';
                })
                ->addColumn('properti', function ($row) {
                    return $row->properti->nama ?? '-';
                })
                ->addColumn('is_available', function ($row) {
                    if ($row->is_available == 1) {
                        return '<span class="badge bg-success">Tersedia</span>';
                    } else {
                        return '<span class="badge bg-danger">Tidak Tersedia</span>';
                    }
                })

                ->addColumn('harga', function ($row) {
                    $harga = is_numeric($row->harga) ? $row->harga : 0;
                    return 'Rp ' . number_format($harga, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group" role="group" style="overflow: hidden;">';

                    // Tombol Detail (bisa pakai biru muda)
                    $btn .= '<a href="' . url('/rooms/' . urlencode($row->id)) . '" 
                class="btn btn-sm text-white" 
                style="background-color:#60a5fa; border-radius: 0.375rem 0 0 0.375rem;" 
                title="Detail">
                <i class="fas fa-eye"></i>
             </a>';

                    // Tombol Edit (biru)
                    if (auth()->user()->can('room-edit')) {
                        $btn .= '<a href="' . route('rooms.edit', $row->id) . '" 
                    class="btn btn-sm text-white" 
                    style="background-color:#3b82f6; border-radius: 0; margin-left:-1px;" 
                    title="Edit">
                    <i class="fas fa-edit"></i>
                 </a>';
                    }

                    // Tombol Hapus (merah)
                    if (auth()->user()->can('room-delete')) {
                        $btn .= '
        <form action="' . route('rooms.destroy', $row->id) . '" method="POST" class="d-inline" 
              onsubmit="return confirm(\'Yakin ingin menghapus kamar ini?\');" style="margin-left:-1px;">
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


                ->rawColumns(['action', 'is_available', 'foto'])
                ->make(true);
        }

        return view('rooms.index');
    }

    /**
     * Menampilkan form untuk membuat kamar baru.
     */

    public function create()
    {
        $fasilitas = Fasilitas::where('created_by', Auth::id())->get();
        $propertis = Properties::where('created_by', Auth::id())->get();

        return view('rooms.create', compact('fasilitas', 'propertis'));
    }


    /**
     * Menyimpan data kamar baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'properti_id' => 'required|exists:properties,id',
            'room_name' => 'required|string|max:255',
            'room_deskription' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'is_available' => 'required|in:Ya,Tidak',
            'fasilitas' => 'required|array',
            'fasilitas.*' => 'exists:fasilitas,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['properti_id', 'room_name', 'room_deskription', 'harga', 'is_available']);
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();
        $data['is_available'] = $request->is_available === 'Ya';

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('rooms', 'public');
        }

        $room = Room::create($data);
        $room->fasilitas()->attach($request->fasilitas);

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
    }


    /**
     * Menampilkan detail kamar tertentu.
     */
    public function show(Room $room)
    {
        $roomsWithSameName = Room::where('room_name', $room->room_name)->get();
        return view('rooms.show', compact('room', 'roomsWithSameName'));
    }

    /**
     * Menampilkan form edit kamar.
     */
    public function edit(Room $room)
    {
        $fasilitas = Fasilitas::where('created_by', Auth::id())->get();
        $propertis = Properties::where('created_by', Auth::id())->get();
        return view('rooms.edit', compact('room', 'fasilitas', 'propertis'));
    }
    /**
     * Memperbarui data kamar.
     */
    public function update(Request $request, Room $room)
    {
        $request->validate([
            'properti_id' => 'required|exists:properties,id',
            'room_name' => 'required|string|max:255',
            'room_deskription' => 'nullable|string',
            'harga' => 'required|integer|min:0',
            'is_available' => 'required|in:Ya,Tidak',
            'fasilitas' => 'required|array',
            'fasilitas.*' => 'exists:fasilitas,id',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['properti_id', 'room_name', 'room_deskription', 'harga']);
        $data['is_available'] = $request->is_available === 'Ya';
        $data['updated_by'] = Auth::id();

        if ($request->hasFile('foto')) {
            if ($room->foto && \Storage::disk('public')->exists($room->foto)) {
                \Storage::disk('public')->delete($room->foto);
            }
            $data['foto'] = $request->file('foto')->store('rooms', 'public');
        }

        $room->update($data);
        $room->fasilitas()->sync($request->fasilitas);

        return redirect()->route('rooms.index')->with('success', 'Kamar berhasil diperbarui.');
    }



    /**
     * Menghapus (soft delete) kamar.
     */
    public function destroy(Room $room)
    {
        $room->update(['deleted_by' => Auth::id()]);
        $room->delete();

        return redirect()->route('rooms.index')
            ->with('success', 'Kamar berhasil dihapus.');
    }

    /**
     * Mengembalikan data yang telah dihapus (restore).
     */
    public function restore($id)
    {
        $room = Room::onlyTrashed()->findOrFail($id);
        $room->restore();

        return redirect()->route('rooms.index')
            ->with('success', 'Kamar berhasil dikembalikan.');
    }

    /**
     * Menghapus data secara permanen.
     */
    public function forceDelete($id)
    {
        $room = Room::onlyTrashed()->findOrFail($id);
        $room->forceDelete();

        return redirect()->route('rooms.index')
            ->with('success', 'Kamar berhasil dihapus permanen.');
    }
    public function daftarKamar()
    {
        $rooms = Room::with('properti')->get();
        return view('daftarkamar', compact('rooms'));
    }
}
