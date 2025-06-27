<?php

namespace App\Http\Controllers;

use App\Models\Properties;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OccupancyReportController extends Controller
{
    // public function index(Request $request)
    // {
    //     $user = Auth::user();

    //     // Mulai query dasar
    //     $query = Properties::with('rooms');

    //     // Filter untuk non-admin
    //     if (!$user->hasRole('admin')) {
    //         $query->where('created_by', $user->id);

    //         // Jika user bukan admin dan request punya id_properti, tambahkan filter
    //         if ($request->has('id_properti')) {
    //             $query->where('id', $request->id_properti);
    //         }
    //     } else {
    //         // Jika admin dan request punya id_properti, filter juga
    //         if ($request->has('id_properti')) {
    //             $query->where('id', $request->id_properti);
    //         }
    //         // Kalau tidak, biarkan ambil semua properti
    //     }

    //     $properties = $query->get();

    //     // Mapping data okupansi
    //     $data = $properties->map(function ($property) {
    //         $totalRooms = $property->rooms->count();
    //         $occupiedRooms = $property->rooms->where('is_available', 0)->count();
    //         $vacantRooms = $property->rooms->where('is_available', 1)->count();
    //         $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;

    //         return [
    //             'nama_properti' => $property->nama,
    //             'total_kamar' => $totalRooms,
    //             'kamar_terisi' => $occupiedRooms,
    //             'kamar_kosong' => $vacantRooms,
    //             'tingkat_okupansi' => $occupancyRate . '%',
    //         ];
    //     });

    //     return view('laporan.okupansi', compact('data'));
    // }



    public function index(Request $request)
    {
        $user = Auth::user();

        // Mulai query dasar
        $query = Properties::with('rooms');

        // Filter untuk non-admin
        if (!$user->hasRole('Admin')) {
            $query->where('created_by', $user->id);

            // Jika user bukan admin dan request punya id_properti, tambahkan filter
            if ($request->has('id_properti')) {
                $query->where('id', $request->id_properti);
            }
        } else {
            // Jika admin dan request punya id_properti, filter berdasarkan id_properti
            if ($request->has('id_properti')) {
                $query->where('id', $request->id_properti);
            }
            // Admin akan mengambil semua properti, tidak ada filter tambahan
        }

        $properties = $query->get();

        // Mapping data okupansi
        $data = $properties->map(function ($property) {
            $totalRooms = $property->rooms->count();
            $occupiedRooms = $property->rooms->where('is_available', 0)->count();
            $vacantRooms = $property->rooms->where('is_available', 1)->count();
            $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 2) : 0;

            return [
                'nama_properti' => $property->nama,
                'total_kamar' => $totalRooms,
                'kamar_terisi' => $occupiedRooms,
                'kamar_kosong' => $vacantRooms,
                'tingkat_okupansi' => $occupancyRate . '%',
            ];
        });

        return view('laporan.okupansi', compact('data'));
    }


    public function getOkupansiData(Request $request)
    {
        $user = Auth::user();
        $query = Properties::with('rooms');

        // Jika user bukan admin, tampilkan hanya properti miliknya
        if (!$user->hasRole('Admin')) {
            $query->where('created_by', $user->id);
        }

        // Filter berdasarkan id_properti jika ada
        if ($request->has('id_properti')) {
            $query->where('id', $request->id_properti);
        }

        $properties = $query->get();

        $data = $properties->map(function ($property) {
            $totalRooms = $property->rooms->count();
            $occupiedRooms = $property->rooms->where('is_available', 0)->count();
            $vacantRooms = $totalRooms - $occupiedRooms;
            $occupancyRate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms) * 100 : 0;

            return [
                'nama_properti' => $property->nama,
                'total_kamar' => $totalRooms,
                'kamar_terisi' => $occupiedRooms,
                'kamar_kosong' => $vacantRooms,
                'tingkat_okupansi' => number_format($occupancyRate, 2) . '%',
            ];
        });

        return $data;
    }


    public function exportPdf(Request $request)
    {
        $data = $this->getOkupansiData($request); // Kirim request untuk dapatkan data sesuai filter

        // Jika kamu kirim chart sebagai base64 string
        $barChart = $request->input('bar_chart');
        $pieChart = $request->input('pie_chart');

        $pdf = Pdf::loadView('laporan.okupansi_pdf', [
            'data' => $data,
            'barChart' => $barChart,
            'pieChart' => $pieChart,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('laporan_okupansi.pdf');
    }
}
