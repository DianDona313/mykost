<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Metode_Pembayaran;
use App\Models\Payment;
use App\Models\Penyewa;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PaymentController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:payment-list|payment-create|payment-edit|payment-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:payment-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:payment-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:payment-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $payments = Payment::with(['booking.property', 'booking.room', 'penyewa', 'metode_pembayaran'])
                ->select('payments.*');

            // Filter berdasarkan role user
            if (Auth::user()->hasRole('Pengelola')) {
                $payments = $payments->whereHas('booking.property', function ($query) {
                    $query->whereHas('pengelola', function ($subQuery) {
                        $subQuery->where('pengelolas.user_id', Auth::user()->id);
                    });
                });
            } elseif (Auth::user()->hasRole('Penyewa')) {
                $payments = $payments->whereHas('booking', function ($query) {
                    $query->where('penyewa_id', Auth::user()->id);
                });
            }

            return DataTables::of($payments)
                ->addIndexColumn()
                
                ->addColumn('properti', fn($row) => $row->booking->property->nama ?? 'Tidak Diketahui')
                ->addColumn('metode_pembayaran', fn($row) => $row->metode_pembayaran->nama_bank ?? 'Tidak Diketahui')
                ->addColumn('penyewa', fn($row) => $row->penyewa->nama ?? 'Tidak Diketahui')
                ->addColumn('jumlah', fn($row) => 'Rp ' . number_format($row->jumlah, 0, ',', '.'))
                ->addColumn('sisa', fn($row) => 'Rp ' . number_format($row->sisa_pembayaran, 0, ',', '.'))
                ->addColumn('metode', fn($row) => ucfirst($row->payment_method))
                ->addColumn('telah_dibayar', fn($row) => $row->telah_dibayar)
                ->addColumn('status', fn($row) => match ($row->payment_status) {
                    'paid' => '<span class="badge bg-success">Sukses</span>',
                    'review' => '<span class="badge bg-warning">Review</span>',
                    'lunas' => '<span class="badge bg-success">Lunas</span>',
                    default => '<span class="badge bg-danger">Gagal</span>',
                })
                ->addColumn('bukti', fn($row) => '<img src="' . asset('storage/' . $row->foto) . '" width="50">')
                ->addColumn('action', function ($row) {
                    $actions = '';
                    $sisa = intval($row->jumlah) - intval($row->sisa_pembayaran);

                    if (auth()->user()->can('payment-detail')) {
                        $actions .= '<a href="' . route('payments.show', $row->id) . '" class="btn btn-info btn-sm me-1">
                        <i class="fas fa-eye"></i> Detail
                    </a>';
                    }

                    if (auth()->user()->can('payment-edit')) {
                        $actions .= '<a href="' . route('payments.edit', $row->id) . '" class="btn btn-warning btn-sm me-1">
                        <i class="fas fa-edit"></i> 
                    </a>';
                    }

                    if ($sisa != 0 && auth()->user()->can('payment-pay')) {
                        $actions .= '<button type="button" class="btn btn-primary btn-sm me-1" 
                        data-bs-toggle="modal" 
                        data-bs-target="#paymentModal" 
                        data-payment-id="' . $row->id . '"
                        data-sisa="' . number_format($sisa, 0, ',', '.') . '"
                        data-property-name="' . ($row->booking->property->nama ?? 'N/A') . '"
                        data-room-name="' . ($row->booking->room->room_name ?? 'N/A') . '"
                        data-penyewa-name="' . ($row->penyewa->nama ?? 'N/A') . '">
                        <i class="fas fa-credit-card"></i> Bayar
                    </button>';
                    }

                    if (auth()->user()->can('payment-approve')) {
                        $actions .= '<form action="' . route('payments.approve', $row->id) . '" method="POST" class="d-inline">
                        ' . csrf_field() . '
                        ' . method_field('PATCH') . '
                        <button type="submit" class="btn btn-success btn-sm me-1"
                            onclick="return confirm(\'Apakah Anda yakin ingin menyetujui pembayaran ini?\')">
                            <i class="fas fa-check"></i> Approve
                        </button>
                    </form>';
                    }

                    if (auth()->user()->can('payment-delete')) {
                        $actions .= '<form action="' . route('payments.destroy', $row->id) . '" method="POST" class="d-inline">
                        ' . csrf_field() . '
                        ' . method_field('DELETE') . '
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm(\'Apakah Anda yakin ingin menghapus data ini?\')">
                            <i class="fas fa-trash"></i> 
                        </button>
                    </form>';
                    }

                    return $actions;
                })
                ->rawColumns(['status', 'bukti', 'action'])
                ->make(true);
        }
        return view('payments.index');
    }

    public function create()
    {
        $paymentMethods = Metode_Pembayaran::all();
        $bookings = Booking::all();
        $penyewas = Penyewa::all();
        return view('payments.create', compact('bookings', 'penyewas', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required',
            'user_id' => 'required',
            'jumlah' => 'required|numeric|min:0',
            'sisa_pembayaran' => 'required|numeric|min:0',
            'payment_method' => 'required|string',
            'payment_status' => 'required|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Tambahkan field foto jika ada
        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')->store('foto_pembayaran', 'public');
        }

        Payment::create($validated);

        return redirect()->route('payments.index')
            ->with('success', 'Pembayaran berhasil ditambahkan.');
    }


    public function show(Payment $payment)
    {
        return view('payments.show', compact('payment'));
    }

    public function edit(Payment $payment)
    {
        return view('payments.edit', compact('payment'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'booking_id' => 'required',
            'user_id' => 'required',
            'jumlah' => 'required|integer',
            'sisa_pembayaran' => 'required',
            'payment_method' => 'required',
            'payment_status' => 'required',
            'foto' => 'nullable|string',
            // 'updated_by' => 'required|integer',
        ]);

        $payment->update($request->all());

        return redirect()->route('payments.index')
            ->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payments.index')
            ->with('success', 'Pembayaran berhasil dihapus.');
    }

    public function getBookingDetail($id)
    {
        $booking = Booking::with(['penyewa', 'room'])->findOrFail($id);

        return response()->json([
            'penyewa' => $booking->penyewa ? $booking->penyewa->only(['id', 'nama']) : null,
            'harga_kamar' => $booking->room ? $booking->room->harga : null,
        ]);
    }

    public function getDetails(Request $request)
    {
        $methodId = $request->query('method'); // Ambil ID dari query ?method=...

        $metode = Metode_Pembayaran::find($methodId); // Cari berdasarkan ID

        if (!$metode) {
            return response()->json(['error' => 'Metode pembayaran tidak ditemukan'], 404);
        }

        return response()->json([
            'nama_bank' => $metode->nama_bank,
            'nomor_rekening' => $metode->no_rek,
            'atas_nama' => $metode->atas_nama
        ]);
    }

    public function invoice($id)
    {
        $payment = Payment::with(['booking.property', 'penyewa', 'metode_pembayaran'])->findOrFail($id);

        $pdf = Pdf::loadView('payments.invoice', compact('payment'));

        return $pdf->download('invoice_pembayaran_' . $payment->id . '.pdf');
    }

    public function approve(Payment $payment)
    {
        try {
            // Cek apakah payment sudah di-approve sebelumnya
            if ($payment->payment_status === 'paid') {
                return redirect()->back()->with('warning', 'Pembayaran sudah disetujui sebelumnya.');
            }

            // Mulai database transaction untuk memastikan konsistensi data
            DB::beginTransaction();

            // Update status payment menjadi approved
            $payment->update([
                'payment_status' => 'paid',
                'sisa_pembayaran' => max(0, intval($payment->sisa_pembayaran) - intval($payment->telah_dibayar))
            ]);

            // Ambil booking terlebih dahulu untuk mendapatkan property_id
            $booking = Booking::findOrFail($payment->booking_id);
            // Update status booking
            $booking->update([
                'status' => 'approved' // Lebih konsisten dengan naming convention
            ]);

            // Update room availability berdasarkan property_id dari booking
            Room::where('id', $booking->room_id)
                ->update(['is_available' => false]); // Gunakan boolean untuk clarity

            // Commit transaction jika semua berhasil
            DB::commit();

            // Log activity untuk audit trail
            \Log::info('Payment approved successfully', [
                'payment_id' => $payment->id,
                'booking_id' => $payment->booking_id,
                'user_id' => auth()->id()
            ]);

            return redirect()->back()->with('success', 'Pembayaran berhasil disetujui.');
        } catch (\Exception $e) {
            // Rollback transaction jika terjadi error
            DB::rollback();

            \Log::error('Error approving payment', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyetujui pembayaran: ' . $e->getMessage());
        }
    }

    public function processPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:1',
            'payment_method' => 'required|exists:metode_pembayarans,id',
            'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'keterangan' => 'nullable|string|max:500'
        ]);
        $fotoPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');

            // Generate unique filename
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Store file to storage/app/public/bukti_pembayaran
            $fotoPath = $file->storeAs('bukti_pembayaran', $fileName, 'public');
        }
        $data_payment = [
            'booking_id' => $payment->booking_id,
            'user_id' => $payment->user_id,
            'jumlah' => $payment->jumlah,
            'telah_dibayar' => $request->jumlah_bayar,
            'sisa_pembayaran' => $payment->sisa_pembayaran,
            'payment_method' => $request->payment_method,
            'payment_status' => 'review',
            'foto' => $fotoPath,
            'created_at' => now(),
            'updated_at' => now()
        ];
        try {
            $payment = Payment::create($data_payment);

            return redirect()->back()->with('success', 'Pembayaran berhasil diajukan dan menunggu persetujuan.');
        } catch (\Exception $e) {
            \Log::error('Error processing payment: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memproses pembayaran.');
        }
    }

    public function getPaymentMethods(Payment $payment)
    {
        $property = $payment->booking->property;
        $paymentMethods = $property->metode_pembayaran;

        return response()->json($paymentMethods);
    }
}
