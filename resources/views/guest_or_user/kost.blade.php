<div class="container-fluid service py-5" style="background: linear-gradient(135deg, #398423 0%, #57A438 100%);">
    <div class="text-center mb-5">
        <h1 class="display-4 text-white fw-bold mb-3">Pilihan Kost Terbaik</h1>
        <p class="text-white-50 fs-5">Temukan hunian nyaman sesuai kebutuhan Anda</p>
    </div>

    <div class="container">
        {{-- <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false"> --}}
        <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="1500">

            <div class="carousel-inner">
                @foreach ($properties->chunk(3) as $chunkIndex => $propertyChunk)
                    <div class="carousel-item {{ $chunkIndex == 0 ? 'active' : '' }}">
                        <div class="row g-4 justify-content-center">
                            @foreach ($propertyChunk as $property)
                                <div class="col-lg-4 col-md-6">
                                    <div class="kost-card h-100">
                                        <div class="card border-0 shadow-lg h-100 overflow-hidden"
                                            style="border-radius: 20px;">

                                            <!-- Gambar -->
                                            <div class="position-relative overflow-hidden">
                                                <img src="{{ asset('storage/properti_foto/' . $property->foto) }}"
                                                    class="card-img-top" style="height: 280px; object-fit: cover;"
                                                    alt="{{ $property->nama }}">
                                                <!-- Badge status -->
                                                <div class="position-absolute top-0 end-0 m-3">
                                                    <span class="badge px-3 py-2 rounded-pill"
                                                        style="background-color: #F09F38;">
                                                        <i class="fas fa-check-circle me-1"></i>Tersedia
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Isi Card -->
                                            <div class="card-body d-flex flex-column p-4">
                                                <div class="mb-3">
                                                    <h5 class="card-title fw-bold text-dark mb-2"
                                                        style="font-size: 1.2rem;">
                                                        {{ $property->nama }}
                                                    </h5>
                                                    <p class="card-text text-muted mb-3">
                                                        <i class="fas fa-map-marker-alt me-2"
                                                            style="color: #398423;"></i>
                                                        {{ $property->alamat }}
                                                    </p>

                                                    <!-- Tombol Lihat Detail di SINI -->
                                                </div>
                                                <a href="{{ route('guest_or_user.detailkost', $property->id) }}"
                                                    class="btn text-white py-2 px-3 mt-1 rounded-pill fw-bold text-uppercase"
                                                    style="background: linear-gradient(45deg, #398423, #57A438); font-size: 0.9rem; border: none;">
                                                    <i class="fas fa-eye me-2"></i>Lihat Detail
                                                </a>

                                                <!-- Fasilitas -->
                                                {{-- <div class="row text-center mt-2">
                                                    <div class="col-4">
                                                        <i class="fas fa-bed fs-5" style="color: #398423;"></i>
                                                        <p class="small text-muted mt-1">Kamar</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <i class="fas fa-wifi fs-5" style="color: #398423;"></i>
                                                        <p class="small text-muted mt-1">WiFi</p>
                                                    </div>
                                                    <div class="col-4">
                                                        <i class="fas fa-car fs-5" style="color: #398423;"></i>
                                                        <p class="small text-muted mt-1">Parkir</p>
                                                    </div>
                                                </div> --}}
                                            </div> <!-- end card-body -->

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Carousel Controls -->
            <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel"
                data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>
    <div class="text-center mt-5">
        <a href="{{ route('all_kost') }}" class="btn text-white py-3 px-5 rounded-pill fw-bold text-uppercase"
            style="background: linear-gradient(45deg, #F09F38); font-size: 1rem;">
            <i class="fas fa-home me-1"></i>Lihat Semua Kost
        </a>
    </div>

</div>

<style>
    .kost-card:hover .card {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .kost-card:hover .card-img-top {
        transform: scale(1.03);
        transition: transform 0.3s ease;
    }

    .kost-card:hover .btn {
        background: linear-gradient(45deg, #F09F38, #398423) !important;
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(89, 144, 46, 0.4);
    }

    @media (max-width: 768px) {
        .display-4 {
            font-size: 2rem !important;
        }

        .carousel-control-prev,
        .carousel-control-next {
            display: none;
        }
    }
</style>
