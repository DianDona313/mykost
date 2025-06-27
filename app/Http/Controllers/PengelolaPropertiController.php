<?php

namespace App\Http\Controllers;

use App\Models\Pengelola_properties;
use Illuminate\Http\Request;

class PengelolaPropertiController extends Controller
{
    public function index()
    {
        $pengelolaProperties = Pengelola_properties::latest()->paginate(10);
        return view('pengelola_properties.index', compact('pengelolaProperties'));
    }

    public function create()
    {
        return view('pengelola_properties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pengelola_id' => 'required|exists:pengelolas,id',
            'properti_id' => 'required|exists:properties,id',
            // 'created_by' => 'required|integer',
        ]);

        Pengelola_properties::create($request->all());

        return redirect()->route('pengelola_properties.index')
            ->with('success', 'Pengelola Properti berhasil ditambahkan.');
    }

    public function show(Pengelola_properties $pengelolaProperty)
    {
        return view('pengelola_properties.show', compact('pengelolaProperty'));
    }

    public function edit(Pengelola_properties $pengelolaProperty)
    {
        return view('pengelola_properties.edit', compact('pengelolaProperty'));
    }

    public function update(Request $request, Pengelola_properties $pengelolaProperty)
    {
        $request->validate([
            'pengelola_id' => 'required|exists:pengelolas,id',
            'properti_id' => 'required|exists:properties,id',
            // 'updated_by' => 'required|integer',
        ]);

        $pengelolaProperty->update($request->all());

        return redirect()->route('pengelola_properties.index')
            ->with('success', 'Pengelola Properti berhasil diperbarui.');
    }

    public function destroy(Pengelola_properties $pengelolaProperty)
    {
        $pengelolaProperty->delete();
        return redirect()->route('pengelola_properties.index')
            ->with('success', 'Pengelola Properti berhasil dihapus.');
    }
}
