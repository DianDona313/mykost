<?php

namespace App\Http\Controllers;

use App\Models\Fasilitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class FasilitasController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:fasilitas-list|fasilitas-create|fasilitas-edit|fasilitas-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:fasilitas-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:fasilitas-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:fasilitas-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            if (auth()->user()->hasRole('Admin')) {
                $data = Fasilitas::latest()->get(); // Admin melihat semua
            } else {
                $data = Fasilitas::where('created_by', auth()->id())->latest()->get(); // Pengguna hanya lihat miliknya
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $btn = '<div class="btn-group" role="group" style="overflow: hidden;">';

                    // Tombol Edit (biru)
                    if (auth()->user()->can('fasilitas-edit')) {
                        $btn .= '<a href="' . route('fasilitas.edit', $row->id) . '" 
            class="btn btn-sm text-white" 
            style="background-color:#3b82f6; border-radius: 0.375rem 0 0 0.375rem;" 
            title="Edit">
            <i class="fas fa-edit"></i>
        </a>';
                    }

                    // Tombol Hapus (merah)
                    if (auth()->user()->can('fasilitas-delete')) {
                        $btn .= '
        <form action="' . route('fasilitas.destroy', $row->id) . '" method="POST" class="d-inline" 
              onsubmit="return confirm(\'Yakin ingin menghapus fasilitas ini?\');" style="margin-left:-1px;">
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

                ->rawColumns(['aksi'])
                ->make(true);
        }

        return view('fasilitas.index');
    }


    public function create()
    {
        return view('fasilitas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            // 'created_by' => 'required|integer',
        ]);

        Fasilitas::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi ?? '-',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function show(Fasilitas $fasilita)
    {
        return view('fasilitas.show', compact('fasilita'));
    }

    public function edit(Fasilitas $fasilita)
    {
        return view('fasilitas.edit', compact('fasilita'));
    }

    public function update(Request $request, Fasilitas $fasilita)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            // 'updated_by' => 'required|integer',
        ]);

        $fasilita->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'updated_by' => $request->updated_by,
        ]);

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    public function destroy(Fasilitas $fasilita)
    {
        $fasilita->delete();

        return redirect()->route('fasilitas.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }
}
