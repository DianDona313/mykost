@extends('layouts.admin.app')

@section('content')
<div class="card shadow-lg border-0 rounded-lg overflow-hidden">
    <div class="card-body p-4">
        <div class="row g-4">
            <!-- FOTO DAN IDENTITAS KAMAR -->
            <div class="col-md-4">
                @if ($room->foto)
                    <div class="position-relative mb-3">
                        <img src="{{ asset('storage/' . $room->foto) }}"
                             class="img-fluid rounded-3 w-100 shadow-sm"
                             style="object-fit: cover; height: 400px;"
                             alt="Foto Kamar">
                        <div class="position-absolute top-0 start-0 m-2">
                            <span class="badge fs-6 px-3 py-2 rounded-pill" style="background-color: #398423; color: white;">
                                <i class="fas fa-door-open me-1"></i>Kamar
                            </span>
                        </div>
                    </div>
                    <div class="text-center">
                        <h4 class="fw-bold mb-0" style="color: #398423;">{{ $room->room_name }}</h4>
                        <div class="border-bottom mx-auto mt-2" style="width: 50px; height: 2px; background-color: #57A438;"></div>
                    </div>
                @endif
            </div>

            <!-- DETAIL -->
            <div class="col-md-8">
                <div class="row g-3">

                    <!-- Informasi Dasar -->
                    <div class="col-12">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-3" style="color: #398423;">
                                    <i class="fas fa-info-circle me-2"></i>Informasi Dasar
                                </h5>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-home me-2 mt-1" style="color: #F09F38;"></i>
                                            <div>
                                                <small class="text-muted d-block">Properti</small>
                                                <span class="fw-medium">{{ $room->properti->nama ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-money-bill-wave me-2 mt-1" style="color: #57A438;"></i>
                                            <div>
                                                <small class="text-muted d-block">Harga</small>
                                                <span class="fw-medium">Rp{{ number_format($room->harga, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-door-open me-2 mt-1" style="color: #398423;"></i>
                                            <div>
                                                <small class="text-muted d-block">Ketersediaan</small>
                                                <span class="fw-medium">
                                                    <span class="badge bg-{{ $room->is_available ? 'success' : 'danger' }}">
                                                        {{ $room->is_available ? 'Tersedia' : 'Tidak Tersedia' }}
                                                    </span>
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="d-flex align-items-start">
                                            <i class="fas fa-align-left me-2 mt-1" style="color: #F6BE68;"></i>
                                            <div>
                                                <small class="text-muted d-block">Deskripsi</small>
                                                <span class="fw-medium">{{ $room->room_deskription ?? '-' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fasilitas -->
                    <div class="col-12">
                        <div class="card bg-light" style="border: 2px solid #57A438;">
                            <div class="card-body p-3">
                                <h5 class="card-title mb-3" style="color: #57A438;">
                                    <i class="fas fa-tools me-2"></i>Fasilitas Kamar
                                </h5>
                                @if ($room->fasilitas->count() > 0)
                                    <div class="row g-2">
                                        @foreach ($room->fasilitas as $item)
                                            <div class="col-md-6">
                                                <div class="d-flex align-items-center p-2 bg-white rounded shadow-sm">
                                                    <div class="rounded-circle p-1 me-2" style="background-color: rgba(87, 164, 56, 0.1);">
                                                        <i class="fas fa-check-circle" style="color: #57A438;"></i>
                                                    </div>
                                                    <span class="text-dark">{{ $item->nama }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <i class="fas fa-info-circle text-muted fs-1 mb-2"></i>
                                        <p class="text-muted mb-0">Tidak ada fasilitas</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TOMBOL AKSI -->
        <div class="row mt-4 pt-3 border-top">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2 justify-content-center justify-content-md-start">
                    <a href="{{ route('rooms.index') }}" class="btn btn-outline-secondary btn-lg px-4" style="border-color: #398423; color: #398423;">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>

                    @can('room-edit')
                        <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-lg px-4" style="background-color: #F6BE68; border-color: #F6BE68; color: #333;">
                            <i class="fas fa-edit me-2"></i>Edit Kamar
                        </a>
                    @endcan

                    @can('room-delete')
                        <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="d-inline"
                            onsubmit="return confirm('Yakin ingin menghapus kamar {{ $room->room_name }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-lg px-4" style="background-color: #F09F38; border-color: #F09F38; color: white;">
                                <i class="fas fa-trash me-2"></i>Hapus Kamar
                            </button>
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    transition: all 0.3s ease;
}
.card:hover {
    transform: translateY(-2px);
}
.btn {
    transition: all 0.3s ease;
}
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
.btn[style*="#398423"]:hover {
    background-color: #2d6b1c !important;
    border-color: #2d6b1c !important;
}
.btn[style*="#F6BE68"]:hover {
    background-color: #f4a832 !important;
    border-color: #f4a832 !important;
}
.btn[style*="#F09F38"]:hover {
    background-color: #ec8f22 !important;
    border-color: #ec8f22 !important;
}
</style>
@endsection
