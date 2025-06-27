<?php

namespace App\Http\Controllers;

use App\Models\Kategori_Pengeluaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class KategoriPengeluaranController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:kategori_pengeluarans-list|kategori_pengeluarans-create|kategori_pengeluarans-edit|kategori_pengeluarans-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:kategori_pengeluarans-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:kategori_pengeluarans-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:kategori_pengeluarans-delete', ['only' => ['destroy']]);

    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Kategori_Pengeluaran::query();

            // Jika user bukan Admin, filter berdasarkan created_by
            if (!auth()->user()->hasRole('admin')) {
                $data->where('created_by', auth()->id());
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group" role="group" style="overflow: hidden;">';

                    // Tombol Edit (biru)
                    if (auth()->user()->can('kategori_pengeluarans-edit')) {
                        $btn .= '<a href="' . route('kategori_pengeluarans.edit', $row->id) . '" 
                        class="btn btn-sm text-white" 
                        style="background-color:#3b82f6; border-radius: 0.375rem 0 0 0.375rem;" 
                        title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>';
                    }

                    // Tombol Hapus (merah)
                    if (auth()->user()->can('kategori_pengeluarans-delete')) {
                        $btn .= '
                    <form action="' . route('kategori_pengeluarans.destroy', $row->id) . '" method="POST" class="d-inline" 
                        onsubmit="return confirm(\'Yakin ingin menghapus kategori ini?\')" 
                        style="margin-left:-1px;">
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
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('kategori_pengeluarans.index');
    }

    public function create()
    {
        return view('kategori_pengeluarans.create');
    }

    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        // Simpan data ke database, pakai ID user yang sedang login
        Kategori_Pengeluaran::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'status' => 'aktif',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('kategori_pengeluarans.index')
            ->with('success', 'Kategori Pengeluaran berhasil ditambahkan.');
    }

    public function show($id)
    {
        // Cari kategori berdasarkan ID
        $kategoriPengeluaran = Kategori_Pengeluaran::findOrFail($id);

        return view('kategori_pengeluarans.show', compact('kategoriPengeluaran'));
    }

    public function edit($id)
    {
        $kategoriPengeluaran = Kategori_Pengeluaran::findOrFail($id);
        $kategoripengeluaran = Kategori_Pengeluaran::all();
        return view('kategori_pengeluarans.edit', compact('kategoriPengeluaran'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            // 'updated_by' => 'required|integer',
        ]);

        // Ambil data kategori pengeluaran berdasarkan ID
        $kategoriPengeluaran = Kategori_Pengeluaran::findOrFail($id);

        // Update data kategori
        $kategoriPengeluaran->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'updated_by' => $request->updated_by,
        ]);

        return redirect()->route('kategori_pengeluarans.index')
            ->with('success', 'Kategori Pengeluaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategoriPengeluaran = Kategori_Pengeluaran::findOrFail($id);
        $kategoriPengeluaran->delete();

        return redirect()->route('kategori_pengeluarans.index')
            ->with('success', 'Kategori Pengeluaran berhasil dihapus.');
    }
}
