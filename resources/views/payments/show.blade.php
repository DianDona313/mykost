@extends('layouts.admin.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h6>Detail Pembayaran</h6>
                    <div>
                        <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-sm me-2">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <a href="{{ route('payments.invoice', $payment->id) }}" class="btn btn-primary btn-sm" target="_blank">
                            <i class="fas fa-download me-1"></i> Download Invoice
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Informasi Pembayaran</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%">ID Pembayaran</th>
                                            <td>{{ $payment->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal Pembayaran</th>
                                            <td>{{ $payment->created_at->format('d F Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if($payment->payment_status == 'paid')
                                                    <span class="badge bg-success">Lunas</span>
                                                @elseif($payment->payment_status == 'review')
                                                    <span class="badge bg-warning text-dark">Review</span>
                                                @else
                                                    <span class="badge bg-danger">Gagal</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Metode Pembayaran</th>
                                            <td>{{ ucfirst($payment->metode_pembayaran->nama_bank) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Jumlah Dibayar</th>
                                            <td class="fw-bold">Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Sisa Pembayaran</th>
                                            <td>Rp {{ number_format($payment->sisa_pembayaran, 0, ',', '.') }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Informasi Penyewa</h6>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%">Nama Penyewa</th>
                                            <td>{{ $payment->penyewa->nama ?? 'Tidak Diketahui' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Properti</th>
                                            <td>{{ $payment->booking->property->nama }}</td>
                                        </tr>
                                        <tr>
                                            <th>Durasi Sewa</th>
                                            <td>
                                                {{ $payment->booking->start_date }} - 
                                                {{ $payment->booking->end_date }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Lama Sewa</th>
                                            <td>{{ number_format($payment->booking->durasisewa, 0, ',', '.') }} Bulan</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">Bukti Pembayaran</h6>
                                </div>
                                <div class="card-body text-center">
                                    <img src="{{ asset('storage/' . $payment->foto) }}" class="img-fluid rounded" style="max-height: 400px;" alt="Bukti Pembayaran">
                                    <div class="mt-3">
                                        <a href="{{ asset('storage/' . $payment->foto) }}" download="bukti_pembayaran_{{ $payment->id }}.jpg" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-download me-1"></i> Download Bukti
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection