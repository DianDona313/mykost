<?php

namespace App\Http\Controllers;

use App\Models\Peraturans;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PeraturanController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:peraturan-list|peraturan-create|peraturan-edit|peraturan-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:peraturan-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:peraturan-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:peraturan-delete', ['only' => ['destroy']]);
    }


    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Peraturans::query();

            // Filter data berdasarkan user yang login (kecuali admin)
            if (!auth()->user()->hasRole('admin')) {
                $query->where('created_by', auth()->id());
            }

            $data = $query->latest()->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group" role="group" style="overflow: hidden;">';

                    $canEdit = auth()->user()->can('peraturan-edit') &&
                        (auth()->user()->hasRole('admin') || $row->created_by == auth()->id());

                    $canDelete = auth()->user()->can('peraturan-delete') &&
                        (auth()->user()->hasRole('admin') || $row->created_by == auth()->id());

                    // Tombol Edit (biru)
                    if ($canEdit) {
                        $btn .= '<a href="' . route('peraturans.edit', $row->id) . '" 
            class="btn btn-sm text-white" 
            style="background-color:#3b82f6; border-radius: 0.375rem 0 0 0.375rem;" 
            title="Edit">
            <i class="fas fa-edit"></i>
        </a>';
                    }

                    // Tombol Hapus (merah)
                    if ($canDelete) {
                        $btn .= '
        <form action="' . route('peraturans.destroy', $row->id) . '" method="POST" class="d-inline" 
              onsubmit="return confirm(\'Yakin ingin menghapus peraturan ini?\');" style="margin-left:-1px;">
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

                ->addColumn('creator', function ($row) {
                    return $row->creator->name ?? 'System'; // Asumsi ada relasi 'creator'
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('peraturans.index');
    }

    public function create()
    {
        return view('peraturans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string', 
            'created_by' => 'nullable|integer',
        ]);

        Peraturans::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi ?? '-',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('peraturans.index')
            ->with('success', 'Peraturan berhasil ditambahkan.');
    }

    public function show(Peraturans $peraturan)
    {
        return view('peraturans.show', compact('peraturan'));
    }

    public function edit(Peraturans $peraturan)
    {
        return view('peraturans.edit', compact('peraturan'));
    }

    public function update(Request $request, Peraturans $peraturan)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            // 'updated_by' => 'nullable|integer',
        ]);

        $peraturan->update([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
            'updated_by' => $request->updated_by,
        ]);

        return redirect()->route('peraturans.index')
            ->with('success', 'Peraturan berhasil diperbarui.');
    }

    public function destroy(Peraturans $peraturan)
    {
        $peraturan->delete();

        return redirect()->route('peraturans.index')
            ->with('success', 'Peraturan berhasil dihapus.');
    }
}
