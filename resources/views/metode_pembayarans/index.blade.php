@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Daftar Metode Pembayaran</h2>
        @can('metode_pembayaran-create')
            <a href="{{ route('metode_pembayarans.create') }}" class="btn btn-custom-orange mb-3">
                <i class="fas fa-plus me-1"></i> Tambah Metode Pembayaran
            </a>
        @endcan

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Kost</th>
                            <th>Nama Bank</th>
                            <th>No Rekening</th>
                            <th>Atas Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($metode_pembayarans as $metode)
                            <tr class="text-center">
                                <td class="text-center">{{ ++$i }}</td>
                                <td>{{ $metode->properties->first()->nama ?? '-' }}</td>

                                <td>{{ $metode->nama_bank }}</td>
                                <td>{{ $metode->no_rek }}</td>
                                <td>{{ $metode->atas_nama }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('metode_pembayarans.show', $metode->id) }}"
                                            class="btn btn-sm text-white"
                                            style="background-color: #60a5fa; border-radius: 0.5rem 0 0 0.5rem;">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        @can('metode_pembayaran-edit')
                                            <a href="{{ route('metode_pembayarans.edit', $metode->id) }}"
                                                class="btn btn-sm text-white"
                                                style="background-color: #3b82f6; border-radius: 0;">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endcan

                                        @can('metode_pembayaran-delete')
                                            <form action="{{ route('metode_pembayarans.destroy', $metode->id) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?');"
                                                style="margin-left:-1px;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm text-white"
                                                    style="background-color: #ef4444; border-radius: 0 0.5rem 0.5rem 0;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>




                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
