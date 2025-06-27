@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
            <div class="card-body p-4">
                <div class="row g-4">
                    <!-- Judul -->
                    <div class="col-md-12 text-center mb-3">
                        <h4 class="fw-bold" style="color: #398423;">
                            <i class="fas fa-credit-card me-2"></i>Detail Metode Pembayaran
                        </h4>
                        <div class="mx-auto mb-4" style="width: 60px; height: 3px; background-color: #57A438;"></div>
                    </div>

                    <!-- Informasi -->
                    <div class="col-md-6">
                        <div class="p-3 rounded shadow-sm" style="background-color: #FDE5AF;">
                            <span class="text-dark fw-bold d-block mb-1">Nama Kost</span>
                            <span class="fs-6">{{ $metode_pembayaran->properties->first()->nama ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 rounded shadow-sm" style="background-color: #FFF3CD;">
                            <span class="text-dark fw-bold d-block mb-1">Nama Bank</span>
                            <span class="fs-6">{{ $metode_pembayaran->nama_bank }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 rounded shadow-sm" style="background-color: #E2F7E1;">
                            <span class="text-dark fw-bold d-block mb-1">Nomor Rekening</span>
                            <span class="fs-6">{{ $metode_pembayaran->no_rek }}</span>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="p-3 rounded shadow-sm" style="background-color: #D1F2EB;">
                            <span class="text-dark fw-bold d-block mb-1">Atas Nama</span>
                            <span class="fs-6">{{ $metode_pembayaran->atas_nama }}</span>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-5 pt-4 border-top d-flex justify-content-center gap-2 flex-wrap">
                    <a href="{{ route('metode_pembayarans.index') }}" class="btn btn-outline-success btn-lg px-4">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>

                    @can('metode_pembayaran-edit')
                        <a href="{{ route('metode_pembayarans.edit', $metode_pembayaran->id) }}" class="btn btn-lg px-4"
                            style="background-color: #F6BE68; border-color: #F6BE68; color: #333;">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <style>
        .btn {
            transition: 0.3s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .shadow-sm {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }
    </style>
@endsection
