<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Daftar - Sistem Manajemen Peran</title>
    @laravelPWA

    <link rel="icon" href="{{ asset('/templates/landing/img/logomykostt.png') }}" type="image/png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <style>
        /* [CSS tidak diubah karena tidak berisi teks yang perlu diterjemahkan] */
        body {
            background: linear-gradient(135deg, #398423 0%, #F09F38 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px 0;
        }

        .register-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        .register-header {
            background: linear-gradient(135deg, #398423 0%, #F09F38 100%);
            color: white;
            border-radius: 20px 20px 0 0;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 16px;
            font-size: 16px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #398423;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            outline: none;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }

        .btn-register {
            background: linear-gradient(135deg, #398423 0%, #F09F38 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .form-text {
            font-size: 14px;
            color: #6c757d;
            margin-top: 5px;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        @media (max-width: 768px) {
            .container {
                padding: 10px;
            }

            .card-body {
                padding: 2rem !important;
            }

            .register-header {
                padding: 1.5rem !important;
            }
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem !important;
            }

            .register-header {
                padding: 1rem !important;
            }

            .register-header h3 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-10 col-md-8 col-lg-6">
                <div class="card register-card border-0">
                    <div class="card-header register-header text-center py-4">
                        <h3 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>
                            Daftar
                        </h3>
                        <p class="mb-0 mt-2">Buat akun baru Anda</p>
                    </div>
                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Ups!</strong> Ada beberapa masalah dengan input Anda:
                                <ul class="mb-0 mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-2"></i>Nama Lengkap
                                </label>
                                <input type="text" name="name" class="form-control"
                                    placeholder="Masukkan nama lengkap Anda" required id="name"
                                    value="{{ old('name') }}">
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </label>
                                <input type="email" name="email" class="form-control"
                                    placeholder="Masukkan email Anda" required id="email"
                                    value="{{ old('email') }}">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Kata Sandi
                                </label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Masukkan kata sandi" required id="password">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>Kata sandi minimal 8 karakter.
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Konfirmasi Kata Sandi
                                </label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Ulangi kata sandi Anda" required id="password_confirmation">
                            </div>

                            <div class="mb-3">
                                <label for="no_hp" class="form-label">Nomor HP</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp"
                                    placeholder="Contoh: 6281234567890">

                                <div class="form-text text-secondary">
                                    <i class="fas fa-exclamation-circle me-1"></i> Gunakan awalan <strong>62</strong>
                                    untuk nomor HP. Contoh: 6281234567890
                                </div>
                            </div>


                            <div class="mb-3">
                                <label for="alamat" class="form-label">
                                    <i class="fas fa-home me-2"></i>Alamat
                                </label>
                                <textarea name="alamat" class="form-control" placeholder="Masukkan alamat Anda" required rows="3" id="alamat">{{ old('alamat') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">
                                    <i class="fas fa-venus-mars me-2"></i>Jenis Kelamin
                                </label>
                                <select name="jenis_kelamin" class="form-select" required id="jenis_kelamin">
                                    <option value="">Pilih jenis kelamin</option>
                                    <option value="Laki-laki"
                                        {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="Perempuan"
                                        {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="foto" class="form-label">
                                    <i class="fas fa-image me-2"></i>Foto Profil <span class="text-danger">*</span>
                                </label>
                                <input type="file" name="foto" class="form-control" accept="image/*"
                                    id="foto" >
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i>Format yang diterima: JPG, PNG, GIF. Ukuran
                                    maks: 2MB.
                                </div>
                            </div>

                            <div class="d-grid mb-4">
                                <button type="submit" class="btn btn-register text-white">Buat Akun</button>
                            </div>
                        </form>

                        <div class="text-center">
                            <p class="mb-0">
                                Sudah punya akun?
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold"
                                    style="color: #398423;">
                                    Masuk di sini
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
