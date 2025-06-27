<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\JenisKost;
use App\Models\Payment;
use App\Models\Penyewa;
use App\Models\User;
use App\Services\FonnteService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PenyewaController extends Controller
{

    protected $fonnte;
    public function __construct(FonnteService $fonnte)
    {
        $this->fonnte = $fonnte;
        $this->middleware('permission:penyewa-list|penyewa-create|penyewa-edit|penyewa-delete', ['only' => ['index', 'show']]);
        $this->middleware('permission:penyewa-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:penyewa-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:penyewa-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Penyewa::with('bookings.property', 'bookings.room', 'payments')->latest()->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group" role="group" style="overflow: hidden;">';

                    // Tombol Lihat (biru muda)
                    $btn .= '<a href="' . route('penyewas.show', $row->id) . '" 
        class="btn btn-sm text-white" 
        style="background-color:#60a5fa; border-radius: 0.375rem 0 0 0.375rem;" 
        title="Lihat">
        <i class="fas fa-eye"></i>
    </a>';

                    // Tombol Edit (biru)
                    if (auth()->user()->can('penyewa-edit')) {
                        $btn .= '<a href="' . route('penyewas.edit', $row->id) . '" 
            class="btn btn-sm text-white" 
            style="background-color:#3b82f6; border-radius: 0; margin-left:-1px;" 
            title="Edit">
            <i class="fas fa-edit"></i>
        </a>';
                    }

                    // Tombol Tagih (hijau)
                    if (auth()->user()->can('penyewa-tagih')) {
                        $btn .= '<a href="' . route('tagih.penyewa', $row->id) . '" 
            class="btn btn-sm text-white" 
            style="background-color:#10b981; border-radius: 0; margin-left:-1px;" 
            title="Tagih">
            <i class="fab fa-whatsapp"></i>
        </a>';
                    }

                    // Tombol Hapus (merah)
                    if (auth()->user()->can('penyewa-delete')) {
                        $btn .= '
        <form action="' . route('penyewas.destroy', $row->id) . '" method="POST" class="d-inline" 
              onsubmit="return confirm(\'Yakin ingin menghapus penyewa ini?\');" style="margin-left:-1px;">
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

                ->addColumn('pengguna', function ($row) {
                    return $row->nama;
                })
                ->addColumn('property', function ($row) {
                    return $row->bookings->first()?->property?->nama ?? '-';
                })
                ->addColumn('kamar', function ($row) {
                    return $row->bookings->first()?->room?->room_name ?? '-';
                })
                ->addColumn('no_hp', function ($row) {
                    return $row->nohp;
                })
                ->addColumn('sisa_pembayaran', function ($row) {
                    // Menjumlah semua sisa_pembayaran dari relasi payments
                    $sisa = $row->payments->sum('sisa_pembayaran');
                    return 'Rp ' . number_format($sisa, 0, ',', '.');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('penyewas.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jenisKosts = JenisKost::all();
        return view('penyewas.create', compact('jenisKosts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:penyewas,email',
            'nohp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nama', 'email', 'nohp', 'alamat', 'jenis_kelamin']);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $path = $file->store('penyewas', 'public'); // simpan di folder penyewas
            $data['foto'] = $path;
        }

        Penyewa::create($data);

        return redirect()->route('penyewas.index')
            ->with('success', 'Penyewa berhasil ditambahkan.');
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // dd($id);
        $penyewa = Penyewa::findOrFail($id);
        // dd($penyewa);
        return view('penyewas.show', compact('penyewa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penyewa $penyewa)
    {
        // $penyewas = Penyewa::all(); 
        return view('penyewas.edit', compact('penyewa'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penyewa $penyewa)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:penyewas,email,' . $penyewa->id,
            'nohp' => 'required|string|max:15',
            'alamat' => 'required|string',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',  // validasi foto
        ]);

        // Ambil data kecuali foto dulu
        $data = $request->only(['nama', 'email', 'nohp', 'alamat', 'jenis_kelamin']);

        // Jika ada file foto baru, simpan dan update path foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada (opsional)
            if ($penyewa->foto) {
                \Storage::disk('public')->delete($penyewa->foto);
            }

            $file = $request->file('foto');
            $path = $file->store('penyewas', 'public'); // simpan ke storage/app/public/penyewas
            $data['foto'] = $path;
        }

        $penyewa->update($data);

        return redirect()->route('penyewas.index')
            ->with('success', 'Penyewa berhasil diperbarui.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penyewa $penyewa)
    {
        $penyewa->delete(); // SoftDeletes: tidak langsung menghapus dari database
        $user = User::where('id','=',$penyewa->user_id)->first();
        $user->delete();
        return redirect()->route('penyewas.index')
            ->with('success', 'Penyewa berhasil dihapus.');
    }

    /**
     * Restore a deleted penyewa.
     */
    public function restore($id)
    {
        Penyewa::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('penyewas.index')
            ->with('success', 'Penyewa berhasil dikembalikan.');
    }

    /**
     * Permanently delete the specified penyewa from database.
     */
    public function forceDelete($id)
    {
        Penyewa::onlyTrashed()->findOrFail($id)->forceDelete();

        return redirect()->route('penyewas.index')
            ->with('success', 'Penyewa dihapus secara permanen.');
    }

    public function tagih($id)
    {
        $penyewa = Penyewa::findOrFail($id);

        // Ambil semua booking milik penyewa (termasuk properti)
        $bookings = Booking::with('property')->where('penyewa_id', $penyewa->id)->get();

        if ($bookings->isEmpty()) {
            return redirect()->back()->with('error', 'Penyewa belum memiliki booking.');
        }

        // Ambil semua payment terkait booking penyewa
        $bookingIds = $bookings->pluck('id');
        $payments = Payment::whereIn('booking_id', $bookingIds)->get();

        if ($payments->isEmpty()) {
            return redirect()->back()->with('error', 'Penyewa belum memiliki tagihan pembayaran.');
        }

        // Hitung total sisa tagihan
        $totalSisaTagihan = $payments->sum('sisa_pembayaran');
        $formattedSisaTagihan = number_format($totalSisaTagihan, 0, ',', '.');

        // Ambil properti pertama (opsional, bisa juga tampilkan semua)
        $properti = $bookings->first()?->property?->nama ?? '(Nama properti tidak tersedia)';

        // Persiapkan isi pesan
        $message = "📢 *Tagihan Pembayaran Kost*

Halo *{$penyewa->nama}*,

Kami menginformasikan bahwa Anda masih memiliki sisa tagihan pembayaran kost:

🏠 *Properti*: {$properti}
💰 *Total Sisa Tagihan*: Rp {$formattedSisaTagihan}

Mohon segera melakukan pelunasan agar layanan Anda tetap aktif.

Jika sudah membayar, abaikan pesan ini. Untuk info lebih lanjut, silakan hubungi admin.

Terima kasih 🙏";

        // Validasi nomor HP
        if (!$penyewa->nohp || !preg_match('/^(\+62|62|08)[0-9]{8,13}$/', $penyewa->nohp)) {
            return redirect()->back()->with('error', 'Nomor HP penyewa tidak valid atau kosong.');
        }

        // Format nomor untuk Fonnte (ubah 08 menjadi 628)
        $nohp = preg_replace('/^08/', '628', $penyewa->nohp);

        // Kirim pesan via Fonnte
        $this->fonnte->sendMessage($nohp, $message);

        return redirect()->back()->with('success', 'Pesan tagihan berhasil dikirim ke penyewa.');
    }


}
