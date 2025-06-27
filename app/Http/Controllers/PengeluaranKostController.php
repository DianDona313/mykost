<?php

namespace App\Http\Controllers;

use App\Exports\PengeluaranKostExport;
use App\Models\Kategori_Pengeluaran;
use App\Models\PengeluaranKost;
use App\Models\Properties;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
// use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PengeluaranKostController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = Auth::user();

            $data = PengeluaranKost::with(['property', 'kategoriPengeluaran', 'creator'])
                ->select('pengeluaran_kosts.*');

            // Jika bukan admin, filter berdasarkan created_by
            if (!$user->hasRole('Admin')) {
                $data->where('created_by', $user->id);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('property_nama', function ($row) {
                    return $row->property->nama ?? '-';
                })
                ->addColumn('kategori_nama', function ($row) {
                    return $row->kategoriPengeluaran->nama ?? '-';
                })
                ->addColumn('jumlah_format', function ($row) {
                    return $row->jumlah_format;
                })
                ->addColumn('status_badge', function ($row) {
                    $status = strtolower($row->status);
                    $color = match ($status) {
                        'pending' => 'text-warning',
                        'approved' => 'text-success',
                        'rejected' => 'text-danger',
                        default => 'text-secondary',
                    };

                    return '<span class="' . $color . '">' . ucfirst($row->status) . '</span>';
                })
                ->addColumn('created_by_name', function ($row) {
                    return $row->creator->name ?? '-';
                })
                ->addColumn('tanggal_format', function ($row) {
                    return optional($row->tanggal_pengeluaran)->format('d/m/Y');
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group" role="group">';
                    $btn .= '<button type="button" class="btn btn-sm btn-info" onclick="showDetail(' . $row->id . ')" title="Detail"><i class="fas fa-eye"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-warning" onclick="editData(' . $row->id . ')" title="Edit"><i class="fas fa-edit"></i></button>';
                    $btn .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteData(' . $row->id . ')" title="Hapus"><i class="fas fa-trash"></i></button>';
                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['status_badge', 'action'])
                ->make(true);
        }

        return view('pengeluaran-kost.index');
    }



    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        // Ambil ID dan role user yang sedang login
        $user = auth()->user();

        // Jika role bukan Admin, tampilkan properti dan kategori pengeluaran berdasarkan created_by
        if ($user->role !== 'Admin') {
            // Ambil properti dan kategori pengeluaran berdasarkan user yang login (created_by = user_id)
            $properties = Properties::where('created_by', $user->id)
                ->select('id', 'nama')
                ->get();

            $kategoriPengeluarans = Kategori_Pengeluaran::where('status', 'aktif')
                ->where('created_by', $user->id)
                ->select('id', 'nama')
                ->get();
        } else {
            // Jika role Admin, ambil semua properti dan kategori pengeluaran aktif
            $properties = Properties::select('id', 'nama')->get();
            $kategoriPengeluarans = Kategori_Pengeluaran::where('status', 'aktif')
                ->select('id', 'nama')
                ->get();
        }

        return view('pengeluaran-kost.create', compact('properties', 'kategoriPengeluarans'));
    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'properti_id' => 'required|exists:properties,id',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluarans,id',
            'keperluan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_pengeluaran' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_pengeluaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();

        // Handle file upload
        if ($request->hasFile('bukti_pengeluaran')) {
            $file = $request->file('bukti_pengeluaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['bukti_pengeluaran'] = $file->storeAs('pengeluaran-kost', $filename, 'public');
        }

        PengeluaranKost::create($data);

        return redirect()->route('pengeluaran-kost.index')
            ->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PengeluaranKost $pengeluaranKost)
    {
        $pengeluaranKost->load(['property', 'kategoriPengeluaran', 'creator', 'updater']);

        return response()->json([
            'data' => $pengeluaranKost,
            'property_nama' => $pengeluaranKost->property->nama ?? '-',
            'kategori_nama' => $pengeluaranKost->kategoriPengeluaran->nama ?? '-',
            'created_by_name' => $pengeluaranKost->creator->name ?? '-',
            'updated_by_name' => $pengeluaranKost->updater->name ?? '-',
            'jumlah_format' => $pengeluaranKost->jumlah_format,
            'tanggal_format' => $pengeluaranKost->tanggal_pengeluaran->format('d/m/Y'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PengeluaranKost $pengeluaranKost)
    {
        $properties = Properties::select('id', 'nama')->get();
        $kategoriPengeluarans = Kategori_Pengeluaran::where('status', 'aktif')
            ->select('id', 'nama')->get();

        return view('pengeluaran-kost.edit', compact('pengeluaranKost', 'properties', 'kategoriPengeluarans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PengeluaranKost $pengeluaranKost)
    {
        $request->validate([
            'properti_id' => 'required|exists:properties,id',
            'kategori_pengeluaran_id' => 'required|exists:kategori_pengeluarans,id',
            'keperluan' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'tanggal_pengeluaran' => 'required|date',
            'keterangan' => 'nullable|string',
            'bukti_pengeluaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $data = $request->all();
        $data['updated_by'] = Auth::id();

        // Handle file upload
        if ($request->hasFile('bukti_pengeluaran')) {
            // Delete old file if exists
            if ($pengeluaranKost->bukti_pengeluaran) {
                Storage::disk('public')->delete($pengeluaranKost->bukti_pengeluaran);
            }

            $file = $request->file('bukti_pengeluaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $data['bukti_pengeluaran'] = $file->storeAs('pengeluaran-kost', $filename, 'public');
        }

        $pengeluaranKost->update($data);

        return redirect()->route('pengeluaran-kost.index')
            ->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PengeluaranKost $pengeluaranKost)
    {
        try {
            // Delete file if exists
            if ($pengeluaranKost->bukti_pengeluaran) {
                Storage::disk('public')->delete($pengeluaranKost->bukti_pengeluaran);
            }

            $pengeluaranKost->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data pengeluaran berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get data for AJAX select options
     */
    public function getSelectData(Request $request)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'properties':
                $data = Properties::select('id', 'nama as text')->get();
                break;
            case 'kategori':
                $data = Kategori_Pengeluaran::where('status', 'aktif')
                    ->select('id', 'nama as text')->get();
                break;
            default:
                $data = [];
        }

        return response()->json($data);
    }

    /**
     * Bulk delete
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        if (!$ids || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data yang dipilih.'
            ]);
        }

        try {
            $pengeluarans = PengeluaranKost::whereIn('id', $ids)->get();

            foreach ($pengeluarans as $pengeluaran) {
                // Delete file if exists
                if ($pengeluaran->bukti_pengeluaran) {
                    Storage::disk('public')->delete($pengeluaran->bukti_pengeluaran);
                }
                $pengeluaran->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus sebanyak ' . count($ids) . ' item.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Export data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $user = auth()->user();  // Mendapatkan data pengguna yang sedang login
        $query = PengeluaranKost::with(['property', 'kategoriPengeluaran', 'creator']);

        // Jika pengguna adalah Admin, filter berdasarkan created_by
        if (!$user->role == 'Admin') {
            $query->where('created_by', $user->id);
        }

        try {
            if ($format === 'excel') {
                $pengeluarans = $query->get();  // Menjalankan query setelah filter

                return Excel::download(new PengeluaranKostExport($pengeluarans), 'pengeluaran_kost_' . date('YmdHis') . '.xlsx');
            } elseif ($format === 'pdf') {
                $pengeluarans = $query->get();  // Menjalankan query setelah filter

                $pdf = Pdf::loadView('pengeluaran-kost.pdf', compact('pengeluarans'))
                    ->setPaper('a4', 'landscape');

                return $pdf->download('pengeluaran_kost_' . date('YmdHis') . '.pdf');
            } else {
                return redirect()->back()->with('error', 'Format export tidak didukung.');
            }
        } catch (\Exception $e) {
            Log::error('Export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mengekspor data: ' . $e->getMessage());
        }
    }

    public function exportPdf()
    {
        $user = auth()->user();  // Mendapatkan data pengguna yang sedang login

        $query = PengeluaranKost::with(['property', 'kategoriPengeluaran', 'creator']);

        // Jika pengguna adalah Admin, filter berdasarkan created_by
        if (!$user->role == 'Admin') {
            $query->where('created_by', $user->id);
        }

        $pengeluarans = $query->get();  // Menjalankan query setelah filter

        $pdf = Pdf::loadView('pengeluaran-kost.pdg', compact('pengeluarans'));
        return $pdf->download('pengeluaran_kost.pdf');
    }

}
