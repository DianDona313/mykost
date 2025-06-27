@extends('layouts.admin.app')
@section('content')
    <div class="container">
        <h2 class="mb-4">Detail Booking</h2>

        @can('booking-list')
            {{-- Informasi Utama Booking --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">
                        Properti: {{ $booking->property->nama ?? 'Tidak Diketahui' }}
                    </h5>
                    <p class="card-text">
                        Penyewa: {{ $booking->penyewa->nama ?? 'Tidak Diketahui' }} <br>
                        Kamar: {{ $booking->room->room_name ?? 'Tidak Diketahui' }} <br>
                        Periode: {{ $booking->start_date ?? $booking->start_date ?? 'N/A' }} - {{ $booking->end_date ?? $booking->end_date ?? 'N/A' }} <br>
                        Status:
                        <span class="badge {{ $statusInfo['class'] ?? 'bg-secondary' }}">
                            <i class="{{ $statusInfo['icon'] ?? 'fas fa-info-circle' }} me-1"></i>
                            {{ $statusInfo['text'] ?? 'Tidak Diketahui' }}
                        </span>
                    </p>

                    {{-- Tombol Aksi --}}
                    @can('booking-edit')
                        <a href="{{ route('bookings.edit', $booking->id) }}" class="btn btn-warning me-2">Edit</a>
                    @endcan
                    <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>

            {{-- Informasi Detail Booking --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-calendar-check me-2"></i>Informasi Booking
                            </h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Check In:</strong></td>
                                    <td>
                                        @if ($booking->start_date)
                                            {{ \Carbon\Carbon::parse($booking->start_date)->format('d F Y') }}<br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($booking->start_date)->format('l') }}
                                            </small>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Check Out:</strong></td>
                                    <td>
                                        @if ($booking->end_date)
                                            {{ \Carbon\Carbon::parse($booking->end_date)->format('d F Y') }}<br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($booking->end_date)->format('l') }}
                                            </small>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Durasi:</strong></td>
                                    <td>
                                        @if ($booking->start_date && $booking->end_date)
                                            @php
                                                $durasi = \Carbon\Carbon::parse($booking->start_date)->diffInDays(
                                                    \Carbon\Carbon::parse($booking->end_date)
                                                ) + 1;
                                            @endphp
                                            <span class="badge bg-secondary">{{ $durasi }} hari</span>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $statusInfo['class'] ?? 'bg-secondary' }}">
                                            <i class="{{ $statusInfo['icon'] ?? 'fas fa-info-circle' }} me-1"></i>
                                            {{ $statusInfo['text'] ?? 'Tidak Diketahui' }}
                                        </span>
                                    </td>
                                </tr>

                                <tr>
                                    <td><strong>Total Harga:</strong></td>
                                    <td>
                                        <h5 class="text-success mb-0">
                                            Rp {{ number_format($booking->harga ?? 0, 0, ',', '.') }}
                                        </h5>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- Kolom kedua untuk informasi tambahan --}}
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-info-circle me-2"></i>Informasi Tambahan
                            </h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm table-borderless">
                                <tr>
                                    <td><strong>Dibuat:</strong></td>
                                    <td>
                                        @if (isset($booking->created_at))
                                            {{ \Carbon\Carbon::parse($booking->created_at)->format('d F Y, H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Diupdate:</strong></td>
                                    <td>
                                        @if (isset($booking->updated_at))
                                            {{ \Carbon\Carbon::parse($booking->updated_at)->format('d F Y, H:i') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Catatan:</strong></td>
                                    <td>
                                        {{ $booking->catatan ?? 'Tidak ada catatan' }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Informasi Kontak Penyewa --}}
            @if(isset($booking->penyewa))
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0">
                                <i class="fas fa-user me-2"></i>Informasi Penyewa
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>Nama:</strong></td>
                                            <td>{{ $booking->penyewa->nama ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $booking->penyewa->email ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <td><strong>Telepon:</strong></td>
                                            <td>{{ $booking->penyewa->telepon ?? $booking->penyewa->phone ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Alamat:</strong></td>
                                            <td>{{ $booking->penyewa->alamat ?? $booking->penyewa->address ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @else
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Anda tidak memiliki akses untuk melihat detail booking ini.
            </div>
        @endcan
    </div>
@endsection

@push('styles')
<style>
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .badge {
        font-size: 0.875em;
    }
    
    .table td {
        padding: 0.5rem 0.75rem;
        vertical-align: middle;
    }
    
    .card-header h6 {
        font-weight: 600;
    }
</style>
@endpush