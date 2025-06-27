<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Metode_Pembayaran;
use App\Models\Penyewa;
use App\Models\Properti;
use App\Models\Properties;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BookingController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:booking-list|booking-create|booking-edit|booking-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:booking-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:booking-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:booking-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $user = auth()->user();
            $userId = $user->id;

            // Default: semua data
            $query = Booking::latest();

            // Cek role
            if ($user->hasRole('Penyewa')) {
                // Temukan penyewa berdasarkan user_id
                $penyewa = \App\Models\Penyewa::where('user_id', $userId)->first();

                if ($penyewa) {
                    $query->where('penyewa_id', $penyewa->id);
                } else {
                    // Jika penyewa tidak ditemukan, kembalikan query kosong
                    $query->whereRaw('1 = 0');
                }
            }

            // Eksekusi query
            $data = $query->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('properti', function ($row) {
                    return $row->property->nama ?? 'Tidak Diketahui';
                })
                ->addColumn('penyewa', function ($row) {
                    return $row->penyewa->nama ?? 'Tidak Diketahui';
                })
                ->addColumn('kamar', function ($row) {
                    return $row->room->room_name ?? 'Tidak Diketahui';
                })
                ->addColumn('periode', function ($row) {
                    return $row->start_date . ' - ' . $row->end_date;
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == 'approved') {
                        return '<span class="badge bg-success">Terkonfirmasi</span>';
                    } elseif ($row->status == 'pending') {
                        return '<span class="badge bg-warning">Menunggu</span>';
                    } else {
                        return '<span class="badge bg-danger">Dibatalkan</span>';
                    }
                })
                ->addColumn('aksi', function ($row) {
                    $btn = '<div class="btn-group" role="group" style="overflow: hidden;">';

                    // Tombol Edit (pojok kiri jika tombol delete tidak ada)
                    if (auth()->user()->can('booking-edit')) {
                        $editBorderRadius = auth()->user()->can('booking-delete')
                            ? 'border-radius: 0.375rem 0 0 0.375rem;'
                            : 'border-radius: 0.375rem;';

                        $btn .= '<a href="' . route('bookings.edit', $row->id) . '" 
                    class="btn btn-sm text-white" 
                    style="background-color:#4c6ef5; ' . $editBorderRadius . '" 
                    title="Edit">
                    <i class="fas fa-edit"></i>
                 </a>';
                    }

                    // Tombol Delete
                    if (auth()->user()->can('booking-delete')) {
                        $deleteBorderRadius = auth()->user()->can('booking-edit')
                            ? 'border-radius: 0 0.375rem 0.375rem 0;'
                            : 'border-radius: 0.375rem;';

                        $btn .= '
        <form action="' . route('bookings.destroy', $row->id) . '" method="POST" class="d-inline" 
              onsubmit="return confirm(\'Yakin ingin menghapus booking ini?\');" style="margin-left:-1px;">
            ' . csrf_field() . method_field('DELETE') . '
            <button type="submit" class="btn btn-sm text-white" 
                    style="background-color:#fa5252; ' . $deleteBorderRadius . '" 
                    title="Hapus">
                <i class="fas fa-trash"></i>
            </button>
        </form>';
                    }

                    $btn .= '</div>';
                    return $btn;
                })




                ->rawColumns(['status', 'aksi'])
                ->make(true);
        }

        return view('bookings.index');

    }

    /**
     * Menampilkan form untuk membuat pemesanan baru.
     */
    public function create()
    {
        $properties = Properties::all();
        $rooms = Room::get()
            ->unique('room_name')
            ->values();
        // dd($rooms);
        $penyewas = Penyewa::all();

        return view('bookings.create', compact('properties', 'rooms', 'penyewas'));
    }

    /**
     * Menyimpan data pemesanan baru ke database.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'property_id' => 'required',
            'penyewa_id' => 'required',
            'room_id' => 'required',
        ]);

        // dd($request->all());

        $data = $request->all();
        $penyewa = Penyewa::where('email', '=', Auth::user()->email)->first();
        // dd($penyewa);
        $data['status'] = 'pending';
        $data['penyewa_id'] = $penyewa->id;
        $data['durasisewa'] = $request->duration;
        $data['created_by'] = Auth::id();
        $data['updated_by'] = Auth::id();

        $data2 = Booking::create($data);
        return redirect()->route('bookings.index')
            ->with('success', 'Pemesanan berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pemesanan tertentu.
     */
    public function show($id)
    {
        $booking = Booking::with(['property', 'penyewa', 'room'])->findOrFail($id);

        // Menentukan status booking
        $status = [
            'confirmed' => ['class' => 'bg-success', 'icon' => 'fas fa-check-circle', 'text' => 'Terkonfirmasi'],
            'pending' => ['class' => 'bg-warning', 'icon' => 'fas fa-clock', 'text' => 'Menunggu'],
            'cancelled' => ['class' => 'bg-danger', 'icon' => 'fas fa-times-circle', 'text' => 'Dibatalkan'],
            'completed' => ['class' => 'bg-primary', 'icon' => 'fas fa-check-double', 'text' => 'Selesai'],
        ];

        $statusInfo = $status[$booking->status] ?? ['class' => 'bg-secondary', 'icon' => 'fas fa-info-circle', 'text' => ucfirst($booking->status)];

        return view('bookings.show', compact('booking', 'statusInfo'));
    }


    /**
     * Menampilkan form edit pemesanan.
     */
    public function edit(Booking $booking)
    {
        $properties = Properties::all();
        $rooms = Room::get()
            ->unique('room_name')
            ->values();
        // dd($rooms);
        $penyewas = Penyewa::all();

        return view('bookings.edit', compact('booking', 'properties', 'rooms', 'penyewas'));
    }

    /**
     * Memperbarui data pemesanan.
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'property_id' => 'required',
            'penyewa_id' => 'required',
            'room_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'durasisewa' => 'required',
            'status' => 'required',
        ]);

        $data = $request->all();
        $data['updated_by'] = Auth::id();

        $booking->update($data);

        return redirect()->route('bookings.index')
            ->with('success', 'Pemesanan berhasil diperbarui.');
    }

    /**
     * Menghapus (soft delete) pemesanan.
     */
    public function destroy(Booking $booking)
    {
        $booking->update(['deleted_by' => Auth::id()]);
        $booking->delete();

        return redirect()->route('bookings.index')
            ->with('success', 'Pemesanan berhasil dihapus.');
    }

    /**
     * Mengembalikan data yang telah dihapus (restore).
     */
    public function restore($id)
    {
        $booking = Booking::onlyTrashed()->findOrFail($id);
        $booking->restore();

        return redirect()->route('bookings.index')
            ->with('success', 'Pemesanan berhasil dikembalikan.');
    }

    /**
     * Menghapus data secara permanen.
     */
    public function forceDelete($id)
    {
        $booking = Booking::onlyTrashed()->findOrFail($id);
        $booking->forceDelete();

        return redirect()->route('bookings.index')
            ->with('success', 'Pemesanan berhasil dihapus permanen.');
    }
    public function kamar_by_id_kost($id)
    {
        // Ambil kamar berdasarkan ID properti
        $kamar = Room::where('properti_id', $id)->get(['id', 'room_name', 'harga']);
        return response()->json($kamar);
    }
    public function historybooking()
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cari penyewa berdasarkan email user yang login
        $penyewa = Penyewa::where('email', $user->email)->first();

        if (!$penyewa) {
            return redirect()->back()->with('error', 'Data penyewa tidak ditemukan.');
        }

        // Ambil booking berdasarkan penyewa_id
        $bookings = Booking::where('penyewa_id', $penyewa->id)
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($bookings);

        $metode_pembayaran = Metode_Pembayaran::all();
        return view('historybooking', compact('bookings', 'metode_pembayaran'));
    }
    // BookingController.php
    public function getPaymentDetails(Request $request)
    {
        $method = $request->get('method');

        // Return payment details based on method
        $paymentDetails = [
            'transfer' => [
                'nama_bank' => 'Bank BCA',
                'nomor_rekening' => '1234567890',
                'atas_nama' => 'PT. Booking System'
            ],
            // Add other payment methods...
        ];

        return response()->json($paymentDetails[$method] ?? []);
    }

    public function processPayment(Request $request, Booking $booking)
    {
        // Validate and process payment
        $request->validate([
            'payment_method' => 'required|string',
            'payment_proof' => 'nullable|file|max:5120', // 5MB max
            'notes' => 'nullable|string'
        ]);

        // Process payment logic here...

        return response()->json(['success' => true, 'message' => 'Pembayaran berhasil disubmit']);
    }
}
