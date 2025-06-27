<?php

namespace App\Http\Controllers;

use App\Models\Metode_Pembayaran;
use App\Models\Properties;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MetodePembayaranController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:metode_pembayaran-list|metode_pembayaran-create|metode_pembayaran-edit|metode_pembayaran-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:metode_pembayaran-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:metode_pembayaran-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:metode_pembayaran-delete', ['only' => ['destroy']]);

    }

    public function index()
    {
        $metode_pembayarans = Metode_Pembayaran::all();
        return view('metode_pembayarans.index', compact('metode_pembayarans'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $properties = Properties::all();
        return view('metode_pembayarans.create', compact('properties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'nama_bank' => 'required|string|max:255',
            'no_rek' => 'required|string|max:255',
            'atas_nama' => 'required|string|max:255',
        ]);

        Metode_Pembayaran::create([
            'property_id' => $request->property_id,
            'nama_bank' => $request->nama_bank,
            'no_rek' => $request->no_rek,
            'atas_nama' => $request->atas_nama,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('metode_pembayarans.index')->with('success', 'Metode Pembayaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Metode_Pembayaran $metode_pembayaran)
    {
        return view('metode_pembayarans.show', compact('metode_pembayaran'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Metode_Pembayaran $metode_pembayaran)
    {
        return view('metode_pembayarans.edit', compact('metode_pembayaran'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Metode_Pembayaran $metode_pembayaran)
    {
        $request->validate([
            'property_id' => 'required|integer',
            'nama_bank' => 'required|string|max:255',
            'no_rek' => 'required|string|max:255',
            'atas_nama' => 'required|string|max:255',
        ]);

        $metode_pembayaran->update([
            'property_id' => $request->property_id,
            'nama_bank' => $request->nama_bank,
            'no_rek' => $request->no_rek,
            'atas_nama' => $request->atas_nama,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('metode_pembayarans.index')->with('success', 'Metode Pembayaran berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage (Soft Delete).
     */
    public function destroy(Metode_Pembayaran $metode_pembayaran)
    {
        $metode_pembayaran->update([
            'deleted_by' => Auth::id(),
        ]);

        $metode_pembayaran->delete();

        return redirect()->route('metode_pembayarans.index')->with('success', 'Metode Pembayaran berhasil dihapus.');
    }
}
