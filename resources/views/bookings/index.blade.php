@extends('layouts.admin.app')
@section('content')
    <div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Daftar Booking</h2>

        @can('booking-create')
            <a href="{{ route('bookings.create') }}" class="btn btn-custom-orange mb-3">
                <i class="fas fa-plus me-1"></i> Tambah Booking
            </a>
        @endcan

        {{-- Tampilkan pesan sukses jika ada --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @can('booking-list')
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-hover" id="bookingsTable">
                        <thead class="table-light text-center">
                            <tr>
                                <th>No</th>
                                <th>Properti</th>
                                <th>Penyewa</th>
                                <th>Kamar</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data akan diisi melalui AJAX oleh DataTables -->
                        </tbody>
                    </table>
                </div>
            </div>
        @endcan
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#bookingsTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('bookings.index') }}",
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'text-center' },
                    { data: 'properti', name: 'properti' },
                    { data: 'penyewa', name: 'penyewa' },
                    { data: 'kamar', name: 'kamar' },
                    { data: 'periode', name: 'periode', className: 'text-center' },
                    { data: 'status', name: 'status', className: 'text-center' },
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false, className: 'text-center' }
                ],
                responsive: true,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/id.json" // Bahasa Indonesia (jika tersedia)
                }
            });
        });
    </script>
@endpush
