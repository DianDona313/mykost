<?php

namespace App\Http\Controllers;

use App\Models\HistoryPengeluaran;
use App\Models\Kategori_Pengeluaran;
use App\Models\Properties;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class HistoryPengeluaranController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:history_pengeluarans-list|history_pengeluarans-create|history_pengeluarans-edit|history_pengeluarans-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:history_pengeluarans-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:history_pengeluarans-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:history_pengeluarans-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = HistoryPengeluaran::with('property')->latest()->get(); // Mengambil data history pengeluaran dengan relasi property

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $edit = '';
                    $delete = '';

                    if (auth()->user()->can('history_pengeluarans-edit')) {
                        $edit = '<a href="' . route('history_pengeluarans.edit', $row->id) . '" class="btn btn-warning btn-sm">Edit</a>';
                    }

                    if (auth()->user()->can('history_pengeluarans-delete')) {
                        $delete = '
                        <form action="' . route('history_pengeluarans.destroy', $row->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Yakin ingin menghapus?\');">
                            ' . csrf_field() . method_field('DELETE') . '
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>';
                    }

                    return $edit . ' ' . $delete;
                })
                ->addColumn('property', function ($row) {
                    return $row->property->nama ?? 'Tidak Diketahui'; // Menampilkan nama properti
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }

        return view('history_pengeluarans.index');
    }

    public function create()
    {
        $properties = Properties::all();
        $kategori_pengeluarans = Kategori_Pengeluaran::all();
        return view('history_pengeluarans.create', compact('properties', 'kategori_pengeluarans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluarans,id',
            'nama_pengeluaran' => 'required|string|max:255',
            'jumlah_pengeluaran' => 'required|integer',
            'tanggal_pengeluaran' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'penanggung_jawab' => 'required|integer',
            // 'deskripsi' => 'nullable|string',
        ]);

        $data = $request->all();
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('history_pengeluaran', 'public');
        }

        $data['created_by'] = 1;
        HistoryPengeluaran::create($data);

        return redirect()->route('history_pengeluarans.index')
            ->with('success', 'Pengeluaran berhasil ditambahkan.');
    }

    public function show(HistoryPengeluaran $historyPengeluaran)
    {
        return view('history_pengeluarans.show', compact('historyPengeluaran'));
    }

    public function edit(HistoryPengeluaran $historyPengeluaran)
    {
        $properties = Properties::all();
        $kategori_pengeluarans = Kategori_Pengeluaran::all();
        return view('history_pengeluarans.edit', compact('historyPengeluaran', 'properties', 'kategori_pengeluarans'));
    }

    public function update(Request $request, HistoryPengeluaran $historyPengeluaran)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluarans,id',
            'nama_pengeluaran' => 'required|string|max:255',
            'jumlah_pengeluaran' => 'required|integer',
            'tanggal_pengeluaran' => 'required|date',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'penanggung_jawab' => 'required|integer',
            'deskripsi' => 'nullable|string',
        ]);

        $data = $request->all();

        if ($request->hasFile('foto')) {
            if ($historyPengeluaran->foto) {
                Storage::disk('public')->delete($historyPengeluaran->foto);
            }
            $data['foto'] = $request->file('foto')->store('history_pengeluaran', 'public');
        }

        $data['updated_by'] = 1;
        $historyPengeluaran->update($data);

        return redirect()->route('history_pengeluarans.index')
            ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(HistoryPengeluaran $historyPengeluaran)
    {
        if ($historyPengeluaran->foto) {
            Storage::disk('public')->delete($historyPengeluaran->foto);
        }

        $historyPengeluaran->update(['deleted_by' => 1]);
        $historyPengeluaran->delete();

        return redirect()->route('history_pengeluarans.index')
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }

    public function restore($id)
    {
        $historyPengeluaran = HistoryPengeluaran::onlyTrashed()->findOrFail($id);
        $historyPengeluaran->restore();

        return redirect()->route('history_pengeluarans.index')
            ->with('success', 'Pengeluaran berhasil dikembalikan.');
    }

    public function forceDelete($id)
    {
        $historyPengeluaran = HistoryPengeluaran::onlyTrashed()->findOrFail($id);

        if ($historyPengeluaran->foto) {
            Storage::disk('public')->delete($historyPengeluaran->foto);
        }

        $historyPengeluaran->forceDelete();

        return redirect()->route('history_pengeluarans.index')
            ->with('success', 'Pengeluaran dihapus permanen.');
    }
}
