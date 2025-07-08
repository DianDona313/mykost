@extends('guest_or_user.layouts.main_all_rooms_list')
@section('content')
    <style>
        .property-img img {
            opacity: 1 !important;
        }
    </style>
    
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if (session('success'))
        <meta name="success-message" content="{{ session('success') }}">
    @endif
    @if (session('error'))
        <meta name="error-message" content="{{ session('error') }}">
    @endif

    <div class="container-fluid rooms py-5">
        <!-- Page Header -->
        <div class="container-fluid page-header py-5">
            <h1 class="text-center text-white display-6">Kost</h1>
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a href="{{ route('index') }}" style="color: #F6BE68;">Home /</a></li>
                <li class="breadcrumb-item"><a href="{{ route('all_rooms') }}" style="color: #F6BE68;">Pages</a></li>
                <li class="breadcrumb-item active text-white">Kost</li>
            </ol>
        </div>

        <!-- Properties Cards Section -->
        <div class="container py-5">
            <div class="row">
                <div class="col-12">
                    <div class="row g-4">
                        @foreach ($properties as $property)
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                                <div class="card h-100 border-0 shadow-sm rounded-3 property-card"
                                    style="overflow: hidden; cursor: pointer; border-left: 4px solid #F09F38 !important;"
                                    onclick="window.location.href='{{ route('guest_or_user.detailkost', $property->id) }}'">

                                    <!-- Property Image -->
                                    <div class="property-img position-relative" style="height: 220px; overflow: hidden;">
                                        @if ($property->foto && file_exists(public_path('storage/properti_foto/' . $property->foto)))
                                            <img src="{{ asset('storage/properti_foto/' . $property->foto) }}"
                                                class="img-fluid w-100 h-100"
                                                style="object-fit: cover; transition: transform 0.3s ease; "
                                                alt="{{ $property->nama }}"
                                                onerror="this.src='{{ asset('images/no-image-placeholder.jpg') }}'; this.onerror=null;">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center"
                                                style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                                                <div class="text-center">
                                                    <i class="fas fa-image fa-3x mb-2"
                                                        style="color: #F09F38; opacity: 0.5;"></i>
                                                    <p class="text-muted mb-0" style="font-size: 0.8rem;">Foto tidak
                                                        tersedia</p>
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Premium Badge -->
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge px-2 py-1"
                                                style="background-color: #398423; color: white; font-size: 0.7rem; border-radius: 15px;">
                                                {{ $property->jeniskost->nama ?? 'Jenis Kost Tidak Diketahui' }}
                                            </span>
                                        </div>

                                        <!-- Overlay hover effect -->
                                        <div class="overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center"
                                            style="background: rgba(240, 159, 56, 0.8); opacity: 0; transition: opacity 0.3s ease;">
                                            <span class="text-white fw-bold">
                                                <i class="fas fa-eye me-2"></i>Lihat Detail
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="card-body d-flex flex-column p-3"
                                        style="background-color: #ffffff; min-height: 180px; position: relative;">

                                        <!-- Yellow Accent Line -->
                                        <div class="accent-line"
                                            style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #F09F38 0%, #F6BE68 100%);">
                                        </div>

                                        <!-- Property Name -->
                                        <h5 class="card-title fw-bold mb-2 text-truncate"
                                            style="color: #2c3e50; font-size: 1.05rem; margin-top: 8px;">
                                            {{ $property->nama }}
                                        </h5>

                                        <!-- Property Address -->
                                        <p class="card-text text-muted mb-3 flex-grow-1"
                                            style="font-size: 0.85rem; line-height: 1.4; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                            <i class="fas fa-map-marker-alt me-1" style="color: #F09F38;"></i>
                                            {{ $property->alamat }}
                                        </p>


                                        <!-- Detail -->
                                        <div class="mt-auto">
                                            <div class="action-section">
                                                <button class="btn w-100 rounded-pill py-2 fw-semibold"
                                                    style="background: linear-gradient(135deg, #F09F38 0%, #F6BE68 100%); 
                                                           border: none; color: white; font-size: 0.85rem; 
                                                           transition: all 0.3s ease; box-shadow: 0 2px 8px rgba(240, 159, 56, 0.3);">
                                                    <i class="fas fa-eye me-2"></i>Lihat Detail
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- No Properties Message -->
                    @if ($properties->isEmpty())
                        <div class="row justify-content-center mt-5">
                            <div class="col-md-6 text-center">
                                <div class="empty-state p-5"
                                    style="background-color: #f8f9fa; border-radius: 15px; border: 2px dashed #F09F38;">
                                    <i class="fas fa-home fa-4x mb-3" style="color: #F09F38;"></i>
                                    <h5 class="text-muted mb-3">Belum ada properti yang tersedia</h5>
                                    <p class="text-muted">Silakan cek kembali nanti atau hubungi kami untuk informasi lebih
                                        lanjut.</p>
                                    <button class="btn mt-3"
                                        style="background-color: #F09F38; color: white; border: none; border-radius: 25px; padding: 10px 30px;">
                                        <i class="fas fa-phone me-2"></i>Hubungi Kami
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .property-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 15px !important;
        }

        .property-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(240, 159, 56, 0.15) !important;
        }

        .property-card:hover .overlay {
            opacity: 1 !important;
        }

        .property-card:hover .property-img img {
            transform: scale(1.08);
        }

        .property-card:hover .btn {
            background: linear-gradient(135deg, #e8941f 0%, #F09F38 100%) !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(240, 159, 56, 0.4) !important;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: "";
        }

        /* Enhanced yellow accents */
        .property-card .fas.fa-map-marker-alt,
        .property-card .fas.fa-check {
            color: #F09F38 !important;
        }

        /* Features list styling */
        .features-list {
            transition: all 0.3s ease;
        }

        .features-list:hover {
            box-shadow: 0 2px 8px rgba(240, 159, 56, 0.1);
            transform: translateY(-1px);
        }

        /* Custom badge styling */
        .badge {
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(240, 159, 56, 0.3);
        }

        /* Responsive Grid - 4 columns on large screens */
        @media (min-width: 1200px) {
            .col-lg-3 {
                flex: 0 0 25%;
                max-width: 25%;
            }
        }

        /* Responsive adjustments */
        @media (max-width: 1199px) {
            .col-md-4 {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }
        }

        @media (max-width: 991px) {
            .col-md-4 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 767px) {
            .property-card {
                margin-bottom: 1rem;
            }

            .property-img {
                height: 200px !important;
            }

            .card-body {
                min-height: 160px !important;
                padding: 1rem !important;
            }

            .card-title {
                font-size: 1rem !important;
            }

            .col-sm-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        @media (max-width: 575px) {
            .property-img {
                height: 180px !important;
            }

            .card-body {
                padding: 0.8rem !important;
            }
        }

        /* Loading animation */
        .btn-loading {
            position: relative;
            pointer-events: none;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            margin: auto;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Enhanced scrollbar */
        .container-fluid::-webkit-scrollbar {
            width: 10px;
        }

        .container-fluid::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 5px;
        }

        .container-fluid::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #F09F38, #F6BE68);
            border-radius: 5px;
        }

        .container-fluid::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #e8941f, #F09F38);
        }

        /* Grid alignment */
        .row.g-4 {
            margin: 0 -12px;
        }

        .row.g-4>* {
            padding: 0 12px;
            margin-bottom: 24px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const propertyCards = document.querySelectorAll('.property-card');

            propertyCards.forEach(card => {
                card.addEventListener('click', function() {
                    // Add loading state
                    const button = this.querySelector('.btn');
                    if (button) {
                        button.classList.add('btn-loading');
                        button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memuat...';
                    }
                });

                // Add hover sound effect (optional)
                card.addEventListener('mouseenter', function() {
                    this.style.borderLeft = '6px solid #F09F38';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.borderLeft = '4px solid #F09F38';
                });
            });

            // Lazy loading for images
            const images = document.querySelectorAll('.property-img img');
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.style.opacity = '0';
                        img.onload = () => {
                            img.style.transition = 'opacity 0.3s ease';
                            img.style.opacity = '1';
                        };
                        observer.unobserve(img);
                    }
                });
            });

            images.forEach(img => imageObserver.observe(img));
        });
    </script>
@endsection
