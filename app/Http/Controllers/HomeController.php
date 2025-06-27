<?php

namespace App\Http\Controllers;

use App\Models\HistoryPengeluaran;
use App\Models\Payment;
use App\Models\Penyewa;
use App\Models\Properties;
use App\Models\Room;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $rooms = Room::all();
        $penyewaCount = Penyewa::count();
        $properties = Properties::all();
        $totalPengeluaran = HistoryPengeluaran::whereMonth('tanggal_pengeluaran', now()->month)
            ->whereYear('tanggal_pengeluaran', now()->year)
            ->sum('jumlah_pengeluaran');
        $totalPembayaran = Payment::sum('jumlah');

        // $totalPembayaran = Payment::sum('harga');
        return view('home', compact('properties', 'rooms', 'penyewaCount', 'totalPengeluaran','totalPembayaran'));
    }
}
