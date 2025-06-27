@extends('layouts.admin.app')

@section('content')
<div class="container">
    <h2 class="mb-4" style="color: #FDE5AF;">Daftar Pengelola</h2>

    @can('pengelola-create')
        <a href="{{ route('pengelolas.create') }}" class="btn btn-custom-orange mb-3">
            <i class="fas fa-plus me-1"></i> Tambah Pengelola
        </a>
    @endcan

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-center" id="pengelola-table">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Nama</th>
                            <th width="15%">No. Telepon</th>
                            <th width="30%">Alamat</th>
                            <th width="15%">Foto</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Style untuk kolom alamat agar word wrap dan lebar tetap */
    #pengelola-table td:nth-child(4) {
        max-width: 250px;
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
        vertical-align: top;
        text-align: left; /* Alamat lebih baik align left */
        padding: 8px;
        line-height: 1.4;
    }
    
    /* Style untuk kolom nama juga agar tidak terlalu lebar */
    #pengelola-table td:nth-child(2) {
        max-width: 200px;
        word-wrap: break-word;
        word-break: break-word;
        white-space: normal;
        vertical-align: top;
    }
    
    /* Semua kolom td menggunakan vertical-align top */
    #pengelola-table td {
        vertical-align: top;
    }
    
    /* Header tetap center */
    #pengelola-table th {
        vertical-align: middle;
        text-align: center;
    }
    
    /* Kolom yang tidak perlu word wrap */
    #pengelola-table td:nth-child(1), /* No */
    #pengelola-table td:nth-child(3), /* No. Telepon */
    #pengelola-table td:nth-child(5), /* Foto */
    #pengelola-table td:nth-child(6)  /* Aksi */ {
        white-space: nowrap;
        text-align: center;
    }
    
    /* Style khusus untuk responsive */
    @media (max-width: 768px) {
        #pengelola-table td:nth-child(4) {
            max-width: 150px;
        }
        #pengelola-table td:nth-child(2) {
            max-width: 120px;
        }
    }
</style>
@endpush

@push('scripts')
<script>
$(function () {
    $('#pengelola-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('pengelolas.index') }}",
        columns: [
            { 
                data: 'DT_RowIndex', 
                name: 'DT_RowIndex', 
                orderable: false, 
                searchable: false,
                className: 'text-center'
            },
            { 
                data: 'nama', 
                name: 'nama',
                className: 'text-center'
            },
            { 
                data: 'no_telp_pengelola', 
                name: 'no_telp_pengelola',
                className: 'text-center'
            },
            { 
                data: 'alamat', 
                name: 'alamat',
                className: 'alamat-column' // Class khusus untuk alamat
            },
            { 
                data: 'foto', 
                name: 'foto', 
                orderable: false, 
                searchable: false,
                className: 'text-center'
            },
            { 
                data: 'action', 
                name: 'action', 
                orderable: false, 
                searchable: false,
                className: 'text-center'
            },
        ],
        // Konfigurasi kolom width
        columnDefs: [
            { width: "5%", targets: 0 },   // No
            { width: "25%", targets: 1 },  // Nama
            { width: "15%", targets: 2 },  // No. Telepon
            { width: "30%", targets: 3 },  // Alamat
            { width: "15%", targets: 4 },  // Foto
            { width: "10%", targets: 5 }   // Aksi
        ],
        autoWidth: false, // Nonaktifkan auto width
        language: {
            url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
        },
        order: [[1, 'asc']] // Default order by nama
    });
});
</script>
@endpush