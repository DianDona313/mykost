<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Register - Role Management System</title>
    @laravelPWA
    
    <link rel="icon" href="{{ asset('/templates/landing/img/logomykostt.png') }}" type="image/png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <style>
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

        /* Responsive adjustments */
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
                            Register
                        </h3>
                        <p class="mb-0 mt-2">Create your account</p>
                    </div>
                    <div class="card-body p-4">
                        <!-- Alert Messages -->
                        @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Whoops!</strong> There were some problems with your input.
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            <i class="fas fa-user me-2"></i>Full Name
                                        </label>
                                        <input type="text" name="name" class="form-control"
                                            placeholder="Enter your full name" required id="name"
                                            value="{{ old('name') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-2"></i>Email Address
                                        </label>
                                        <input type="email" name="email" class="form-control"
                                            placeholder="Enter your email" required id="email"
                                            value="{{ old('email') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            <i class="fas fa-lock me-2"></i>Password
                                        </label>
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Enter your password" required id="password">
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>Password must be at least 8
                                            characters long.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">
                                            <i class="fas fa-lock me-2"></i>Confirm Password
                                        </label>
                                        <input type="password" name="password_confirmation" class="form-control"
                                            placeholder="Confirm your password" required id="password_confirmation">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="no_hp" class="form-label">
                                            <i class="fas fa-phone me-2"></i>Phone Number
                                        </label>
                                        <input type="text" name="no_hp" class="form-control"
                                            placeholder="Enter your phone number" required id="no_hp"
                                            value="{{ old('no_hp') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="alamat" class="form-label">
                                            <i class="fas fa-home me-2"></i>Address
                                        </label>
                                        <textarea name="alamat" class="form-control" placeholder="Enter your address"
                                            required rows="3" id="alamat">{{ old('alamat') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-3">
                                        <label for="jenis_kelamin" class="form-label">
                                            <i class="fas fa-venus-mars me-2"></i>Gender
                                        </label>
                                        <select name="jenis_kelamin" class="form-select" required id="jenis_kelamin">
                                            <option value="">Select your gender</option>
                                            <option value="Laki-laki" {{ old('jenis_kelamin')=='Laki-laki' ? 'selected'
                                                : '' }}>Laki-laki</option>
                                            <option value="Perempuan" {{ old('jenis_kelamin')=='Perempuan' ? 'selected'
                                                : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label for="foto" class="form-label">
                                            <i class="fas fa-image me-2"></i>Profile Photo (optional)
                                        </label>
                                        <input type="file" name="foto" class="form-control" accept="image/*" id="foto">
                                        <div class="form-text">
                                            <i class="fas fa-info-circle me-1"></i>Accepted formats: JPG, PNG, GIF. Max
                                            size: 2MB.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-grid mb-4">
                                        <button type="submit" class="btn btn-register text-white">Create
                                            Account</button>
                                    </div>
                                </div>
                            </div>

                        </form>

                        <div class="text-center">
                            <p class="mb-0">
                                Already have an account?
                                <a href="{{ route('login') }}" class="text-decoration-none fw-bold"
                                    style="color: #398423;">
                                    Sign in here
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