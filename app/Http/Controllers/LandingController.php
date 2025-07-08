<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Metode_Pembayaran;
use App\Models\Payment;
use App\Models\Penyewa;
use App\Models\Properties;
use App\Models\Room;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LandingController extends Controller
{
    protected $fonnte;

    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
    }
    public function index()
    {
        $properties = Properties::all();
        $rooms = Room::where('is_available', '=', '1')->with(['properti.metode_pembayaran', 'fasilitas'])->limit(8)->get();
        return view('guest_or_user.index', compact('properties', 'rooms'));
    }

    public function all_rooms()
    {
        $properties = Properties::all();
        $rooms = Room::where('is_available', '=', '1')->with(['properti.metode_pembayaran', 'fasilitas'])->get();
        return view('guest_or_user.all_rooms', compact('rooms'));
    }
    public function all_kost()
    {
        // $rooms = Room::where('is_available', '=', '1')->with(['properti.metode_pembayaran', 'fasilitas'])->get();
        $properties = Properties::with('jeniskost')->get();
        return view('guest_or_user.all_kost', compact('properties'));
    }

    public function booking_proses(Request $request)
    {
        // Validasi input
        $request->validate([
            'metode_pembayaran_id' => 'required',
            'total_harga' => 'required',
            'jumlah_yang_bayar' => 'required',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'jumlah' => 'required|integer|min:1',
            'room_id' => 'required',
            'properti_id' => 'required',
        ]);

        try {
            // Mulai database transaction
            DB::beginTransaction();

            $data = $request->all();
            $penyewa = Penyewa::where('user_id', '=', Auth::user()->id)->first();

            // Cek apakah penyewa ada
            if (!$penyewa) {
                DB::rollback();
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data penyewa tidak ditemukan. Silakan lengkapi profil Anda terlebih dahulu.'
                    ]);
                }
                return redirect()->back()->with('error', 'Data penyewa tidak ditemukan');
            }

            // Cek ketersediaan kamar pada tanggal yang dipilih
            $existingBooking = Booking::where('room_id', $data['room_id'])
                ->where(function ($query) use ($data) {
                    $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                        ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])
                        ->orWhere(function ($q) use ($data) {
                            $q->where('start_date', '<=', $data['start_date'])
                                ->where('end_date', '>=', $data['end_date']);
                        });
                })
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->exists();

            if ($existingBooking) {
                DB::rollback();
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Kamar tidak tersedia pada tanggal yang dipilih. Silakan pilih tanggal lain.'
                    ]);
                }
                return redirect()->back()->with('error', 'Kamar tidak tersedia pada tanggal yang dipilih');
            }

            // Handle file upload
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');

                // Generate unique filename
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                // Store file to storage/app/public/bukti_pembayaran
                $fotoPath = $file->storeAs('bukti_pembayaran', $fileName, 'public');
            }

            // Data untuk booking
            $data_booking = [
                'property_id' => $data['properti_id'],
                'penyewa_id' => $penyewa->id,
                'room_id' => $data['room_id'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'durasisewa' => $data['jumlah'],
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Buat booking
            $booking = Booking::create($data_booking);
            $properti = Properties::with(['jenisKost', 'metode_pembayaran'])->find($data['properti_id']);
            $pengelolas = $properti->pengelolas;
            $metodePembayaran = Metode_Pembayaran::where('id','=',$data['metode_pembayaran_id'])->first();

            foreach ($pengelolas as $pengelola) {
                $jenisKost = $properti->jenisKost ? $properti->jenisKost->nama : '-';
                $metodePembayaranList = $properti->metode_pembayaran->pluck('nama')->implode(', ');

                $message = "📢 *Pemberitahuan Booking Baru Kost*

Halo *{$pengelola->nama}*,

Ada penyewa baru yang melakukan booking dengan detail sebagai berikut:

🏠 *Properti*: {$properti->nama}
🏷️ *Jenis Kost*: {$jenisKost}
💳 *Metode Pembayaran*: {$metodePembayaran->nama_bank}

🧑 *Nama Penyewa*: {$penyewa->nama}
📧 *Email Penyewa*: {$penyewa->email}
📞 *No HP Penyewa*: {$penyewa->nohp}
🗓️ *Durasi Sewa*: {$data['jumlah']} bulan
📌 *Status Booking*: Pending

Silakan cek panel admin untuk informasi lebih lanjut.

Terima kasih 🙏";

                $this->fonnte->sendMessage($pengelola->no_telp_pengelola, $message);
            }

            // Data untuk payment
            $data_payment = [
                'booking_id' => $booking->id,
                'user_id' => $penyewa->id,
                'jumlah' => $data['total_harga'],
                'sisa_pembayaran' => $data['total_harga'],
                'payment_method' => $data['metode_pembayaran_id'],
                'payment_status' => 'review',
                'foto' => $fotoPath,
                'telah_dibayar' => $data['jumlah_yang_bayar'],
                'created_at' => now(),
                'updated_at' => now()
            ];

            // Buat payment
            $payment = Payment::create($data_payment);

            // Commit transaction
            DB::commit();

            // Response untuk AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Booking berhasil dibuat! Pembayaran Anda sedang dalam proses review.',
                    'data' => [
                        'bookingId' => '#BK' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
                        'totalPayment' => 'Rp ' . number_format($data['total_harga'], 0, ',', '.'),
                        'bookingDate' => $booking->created_at->format('d/m/Y H:i'),
                        'status' => 'pending'
                    ]
                ]);
            }

            return redirect()->back()->with('success', 'Booking berhasil dibuat! Pembayaran Anda sedang dalam proses review.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::info($e->getMessage());
            DB::rollback();

            // Hapus file yang sudah diupload jika ada error
            if (isset($fotoPath) && $fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data yang Anda masukkan tidak valid.',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Rollback transaction jika ada error
            DB::rollback();

            // Hapus file yang sudah diupload jika ada error
            if (isset($fotoPath) && $fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            // Log error untuk debugging
            \Log::error('Booking Process Error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'data' => $request->except(['foto']),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan sistem. Silakan coba lagi atau hubungi customer service.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan sistem. Silakan coba lagi.');
        }
    }
}
