@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Daftar History Pengeluaran</h2>
        @can('history_pengeluarans-create')
            <a href="{{ route('history_pengeluarans.create') }}" class="btn btn-custom-orange mb-3">
                <i class="fas fa-plus me-1"></i> Tambah Pengeluaran
            </a>
        @endcan

        {{-- Tampilkan pesan sukses jika ada --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-striped" id="historyTable">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nama Pengeluaran</th>
                            <th>Jumlah</th>
                            <th>Tanggal</th>
                            <th>Properti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data akan dimuat menggunakan AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#historyTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('history_pengeluarans.index') }}", // URL untuk AJAX request
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex' }, // Kolom index
                    { data: 'nama_pengeluaran', name: 'nama_pengeluaran' },
                    { data: 'jumlah_pengeluaran', name: 'jumlah_pengeluaran', render: function(data) {
                        return 'Rp ' + data.toLocaleString(); // Format jumlah uang
                    }},
                    { data: 'tanggal_pengeluaran', name: 'tanggal_pengeluaran' },
                    { data: 'property', name: 'property' }, // Kolom properti
                    { data: 'aksi', name: 'aksi', orderable: false, searchable: false }
                ]
            });
        });
    </script>
    @endpush
@endsection
