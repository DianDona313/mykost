@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Daftar History Pesan</h2>

        @can('history_pesans-create')
            <a href="{{ route('history_pesans.create') }}" class="btn btn-custom-orange mb-3">
                <i class="fas fa-plus me-1"></i> Tambah History Pesan
            </a>
        @endcan

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered text-center" id="history-pesan-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pesan</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
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
            $('#history-pesan-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('history_pesans.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'pesan',
                        name: 'pesan'
                    },
                    {
                        data: 'tanggal_mulai',
                        name: 'tanggal_mulai'
                    },
                    {
                        data: 'tanggal_selesai',
                        name: 'tanggal_selesai'
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
