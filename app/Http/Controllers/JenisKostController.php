<?php

namespace App\Http\Controllers;

use App\Models\JenisKost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class JenisKostController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:jeniskost-list|jeniskost-create|jeniskost-edit|jeniskost-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:jeniskost-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:jeniskost-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:jeniskost-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = JenisKost::latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group" role="group" style="overflow: hidden;">';

                    // Tombol Show
                    $btn .= '<a href="' . route('jeniskosts.show', $row->id) . '" 
                class="btn btn-sm text-white" 
                style="background-color:#60a5fa; border-radius: 0.375rem 0 0 0.375rem;" 
                title="Lihat Detail">
                <i class="fas fa-eye"></i>
             </a>';

                    // Tombol Edit
                    if (Auth::user()->can('jeniskost-edit')) {
                        $btn .= '<a href="' . route('jeniskosts.edit', $row->id) . '" 
                    class="btn btn-sm text-white" 
                    style="background-color:#3b82f6; border-radius: 0; margin-left:-1px;" 
                    title="Edit">
                    <i class="fas fa-edit"></i>
                 </a>';
                    }

                    // Tombol Delete
                    if (Auth::user()->can('jeniskost-delete')) {
                        $btn .= '<form action="' . route('jeniskosts.destroy', $row->id) . '" method="POST" class="d-inline" 
                        onsubmit="return confirm(\'Apakah Anda yakin ingin menghapusnya?\');" style="margin-left:-1px;">
                        ' . csrf_field() . method_field("DELETE") . '
                        <button type="submit" class="btn btn-sm text-white" style="background-color:#ef4444; border-radius: 0 0.375rem 0.375rem 0;" 
                    title="Hapus"><i class="fas fa-trash"></i></button></form>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('jeniskosts.index');
    }

    public function create()
    {
        return view('jeniskosts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            // 'deskripsi' => 'nullable|string',
        ]);

        JenisKost::create([
            'nama' => $request->nama,
            'deskripsi' => $request->deskripsi,
        ]);

        return redirect()->route('jeniskosts.index')
            ->with('success', 'Jenis Kost berhasil ditambahkan.');
    }

    public function show(JenisKost $jeniskost)
    {
        return view('jeniskosts.show', compact('jeniskost'));
    }

    public function edit(JenisKost $jeniskost)
    {
        return view('jeniskosts.edit', compact('jeniskost'));
    }

    public function update(Request $request, JenisKost $jeniskost)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            // 'deskripsi' => 'nullable|string',
            // 'updated_by' => 'required|integer',
        ]);

        $jeniskost->update($request->all());

        return redirect()->route('jeniskosts.index')
            ->with('success', 'Jenis Kost berhasil diperbarui.');
    }

    public function destroy(JenisKost $jeniskost)
    {
        $jeniskost->delete();
        return redirect()->route('jeniskosts.index')
            ->with('success', 'Jenis Kost berhasil dihapus.');
    }
}
