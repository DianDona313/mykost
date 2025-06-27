@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Daftar Kamar</h2>

        @can('room-create')
            <a href="{{ route('rooms.create') }}" class="btn btn-custom-orange mb-3">
                <i class="fas fa-plus me-1"></i> Tambah Kamar Kost
            </a>
        @endcan

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-striped text-center" id="rooms-table">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Foto</th>
                            <th>Nama Kost</th>
                            <th>Nama Kamar</th>
                            <th>Harga</th>
                            <th>Ketersediaan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#rooms-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('rooms.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'foto',
                        name: 'foto',
                        render: function(data, type, full, meta) {
                            if (!data) return '-';
                            return `<img src="/storage/${data}" width="60" class="img-thumbnail"/>`;
                        },
                        orderable: false,
                        searchable: false
                    },
{
                        data: 'properti',
                        name: 'properti'
                    },
                    {
                        data: 'room_name',
                        name: 'room_name'
                    },
                    
                    {
                        data: 'harga',
                        name: 'harga',
                        // render: function(data) {
                        //     return 'Rp ' + parseInt(data).toLocaleString('id-ID');
                        // }
                    },
                    {
                        data: 'is_available',
                        name: 'is_available',
                       
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
    </script>
@endpush
