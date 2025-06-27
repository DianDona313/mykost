<?php

namespace App\Exports;

use App\Models\PengeluaranKost;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PengeluaranKostExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Ambil data dari database
     */
    public function collection()
    {
        // return PengeluaranKost::with(['property', 'kategoriPengeluaran', 'creator'])
        //     ->select('pengeluaran_kosts.*')
        //     ->get();

        $user = Auth::user();

        $query = PengeluaranKost::with(['property', 'kategoriPengeluaran', 'creator'])
            ->select('pengeluaran_kosts.*');

        // Filter berdasarkan created_by jika bukan Admin
        if (!$user->hasRole('Admin')) {
            $query->where('created_by', $user->id);
        }

        return $query->get();
    }

    /**
     * Mapping setiap baris data yang akan diekspor ke Excel
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->property->nama ?? '-',
            $row->kategoriPengeluaran->nama ?? '-',
            $row->keperluan,
            number_format($row->jumlah, 2, ',', '.'),
            Carbon::parse($row->tanggal_pengeluaran)->format('d/m/Y'),
            $row->keterangan ?? '-',
            $row->status,
            $row->creator->name ?? '-',
        ];
    }

    /**
     * Judul kolom di file Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Properti',
            'Kategori Pengeluaran',
            'Keperluan',
            'Jumlah',
            'Tanggal',
            'Keterangan',
            'Status',
            'Dibuat Oleh',
        ];
    }
}
