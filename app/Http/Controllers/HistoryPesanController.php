<?php

namespace App\Http\Controllers;

use App\Models\HistoryPesan;
use App\Models\Penyewa;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class HistoryPesanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:history_pesans-list|history_pesans-create|history_pesans-edit|history_pesans-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:history_pesans-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:history_pesans-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:history_pesans-delete', ['only' => ['destroy']]);
    }
    public function index(Request $request)
{
    if ($request->ajax()) {
        $data = HistoryPesan::query();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $buttons = '<a href="' . route('history_pesans.show', $row->id) . '" class="btn btn-info btn-sm">Detail</a> ';

                if (auth()->user()->can('history_pesans-edit')) {
                    $buttons .= '<a href="' . route('history_pesans.edit', $row->id) . '" class="btn btn-warning btn-sm">Edit</a> ';
                }

                if (auth()->user()->can('history_pesans-delete')) {
                    $buttons .= '<form action="' . route('history_pesans.destroy', $row->id) . '" method="POST" class="d-inline" onsubmit="return confirm(\'Yakin ingin menghapus?\')">'
                        . csrf_field() . method_field('DELETE')
                        . '<button type="submit" class="btn btn-danger btn-sm">Hapus</button></form>';
                }

                return $buttons;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    return view('history_pesans.index');
}

    public function create()
    {
        $penyewas = Penyewa::all();
        return view('history_pesans.create', compact('penyewas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'penyewa_id' => 'required',
            'pesan' => 'required',
            'deskripsi' => 'nullable',
            'tanggal_mulai' => 'required',
            'tanggal_selesai' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('history_pesan', 'public');
        }

        HistoryPesan::create([
            'penyewa_id' => $request->penyewa_id,
            'pesan' => $request->pesan,
            'deskripsi' => $request->deskripsi,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'foto' => $fotoPath ?? null,
            'created_by' => $request->created_by,
        ]);

        return redirect()->route('history_pesans.index')
            ->with('success', 'Pesan berhasil ditambahkan.');
    }

    public function show(HistoryPesan $historyPesan)
    {
        return view('history_pesans.show', compact('historyPesan'));
    }

    public function edit(HistoryPesan $historyPesan)
    {
        return view('history_pesans.edit', compact('historyPesan'));
    }

    public function update(Request $request, HistoryPesan $historyPesan)
    {
        $request->validate([
            'penyewa_id' => 'required|exists:penyewas,id',
            'pesan' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            // 'updated_by' => 'required|integer',
        ]);

        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('history_pesan', 'public');
            $historyPesan->foto = $fotoPath;
        }

        $historyPesan->update($request->except('foto'));

        return redirect()->route('history_pesans.index')
            ->with('success', 'Pesan berhasil diperbarui.');
    }

    public function destroy(HistoryPesan $historyPesan)
    {
        $historyPesan->delete();
        return redirect()->route('history_pesans.index')
            ->with('success', 'Pesan berhasil dihapus.');
    }
}
