<div class="container-fluid rooms py-5">
    <div class="container py-5">
        <div class="text-center">
            <div class="row g-4 align-items-center mb-4">
                <div class="col-lg-6 text-start">
                    <h1 class="mb-0">Kamar List</h1>
                </div>
                <div class="col-lg-6 text-end">
                    <a href="{{ route('all_rooms') }}" class="btn btn-primary rounded-pill px-4 py-2">Lihat Lebih</a>
                </div>
            </div>

            <div class="row g-4">
                @foreach ($rooms as $room)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="card h-100 border-secondary" style="max-height: 400px;">
                        <div class="room-img" style="height: 150px; overflow: hidden;">
                            <img src="{{ asset('storage/' . $room->foto) }}" class="img-fluid w-100 h-100"
                                style="object-fit: cover;" alt="{{ $room->room_name }}">
                        </div>
                        <div class="card-body d-flex flex-column" style="height: calc(100% - 150px);">
                            <h5 class="card-title">{{ $room->room_name }}</h5>
                            <p class="card-text text-muted mb-2 flex-grow-1"
                                style="overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                {{ $room->room_deskription }}
                            </p>
                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                <p class="text-dark fw-bold mb-0">Rp {{ number_format($room->harga, 0, ',', '.') }}</p>
                                <button class="btn btn-outline-primary rounded-pill px-3" data-bs-toggle="modal"
                                    data-bs-target="#bookingModal" data-room_name="{{ $room->room_name }}"
                                    data-harga="{{ $room->harga }}"
                                    data-harga_formatted="Rp {{ number_format($room->harga, 0, ',', '.') }}"
                                    data-properti_detail="{{ $room->properti->nama ?? '-' }}"
                                    data-properti_id="{{ $room->properti->id ?? '' }}"
                                    data-fasilitas_detail="{{ $room->fasilitas->pluck('nama')->join(', ') }}"
                                    data-image="{{ asset('storage/' . $room->foto) }}"
                                    data-metode_pembayaran='@json($room->properti->metode_pembayaran ?? [])'
                                    data-room_id="{{ $room->id }}">
                                    <i class="fa fa-book me-2"></i> Book Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Booking -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="bookingModalLabel">Detail Booking</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modalRoomImage" src="" class="img-fluid rounded mt-3" alt="Gambar Kamar" width="80px">
                <p><strong>Nama Kamar:</strong> <span id="modalRoomName"></span></p>
                <p><strong>Harga:</strong> <span id="modalHarga"></span></p>
                <input type="hidden" id="modalFullDataRoomName" name="room_name">
                <input type="hidden" id="modalFullDataHarga" name="harga">
                <input type="hidden" id="modalFullDataRoomId" name="room_id">
                <input type="hidden" id="modalFullDataPropertiId" name="properti_id">
                <p><strong>Properti:</strong> <span id="modalProperti"></span></p>
                <p><strong>Fasilitas:</strong> <span id="modalFasilitas"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
                @if (Auth::check())
                <button type="button" class="btn btn-primary rounded-pill" id="lanjutBookingBtn">Lanjut Booking</button>
                @else
                <h6>Anda Harus Login Dulu</h6>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header sticky-top bg-white">
                <h1 class="modal-title fs-5" id="paymentModalLabel">Form Pembayaran</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="overflow-y: auto; max-height: 70vh;">
                <form id="paymentForm" method="POST" action="{{ route('booking_proses') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <!-- Detail Kamar -->
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Detail Kamar</h6>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <img id="paymentModalRoomImage" src="" class="img-fluid rounded" alt="Gambar Kamar"
                                        style="max-height: 100px;">
                                </div>
                                <div class="col-md-9">
                                    <p class="mb-1"><strong>Nama Kamar:</strong> <span id="paymentModalRoomName"></span>
                                    </p>
                                    <p class="mb-1"><strong>Harga:</strong> <span id="paymentModalHarga"></span></p>
                                    <p class="mb-0"><strong>Properti:</strong> <span id="paymentModalProperti"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Form Input -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start_date" class="form-label">Tanggal Mulai Sewa</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" required>
                                <small class="text-muted">Pilih tanggal mulai sewa</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end_date" class="form-label">Tanggal Berakhir Sewa</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" required>
                                <small class="text-muted">Tanggal berakhir sewa (otomatis dihitung)</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah" class="form-label">Jumlah Bulan</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" required>
                                <small class="text-muted">Minimal 1 bulan</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="totalHarga" class="form-label">Total Harga</label>
                                <input type="text" class="form-control" id="totalHarga" readonly>
                                <input type="hidden" id="totalHargaValue" name="total_harga">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="jumlah_yang_bayar" class="form-label">Jumlah Yang Dibayar</label>
                                <input type="text" class="form-control" name="jumlah_yang_bayar">
                            </div>
                        </div>
                    </div>
                    <!-- Metode Pembayaran -->
                    <div class="form-group mt-3">
                        <label for="metode_pembayaran_id" class="form-label">Pilih Metode Pembayaran</label>
                        <select class="form-select" id="metode_pembayaran_id" name="metode_pembayaran_id" required>
                            <option value="">-- Pilih Metode Pembayaran --</option>
                        </select>
                    </div>
                    <!-- Detail Rekening -->
                    <div class="card mt-3" id="rekeningDetail" style="display: none;">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">Detail Rekening</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Bank:</strong> <span id="detailBank"></span></p>
                            <p class="mb-1"><strong>No. Rekening:</strong> <span id="detailNoRek"></span></p>
                            <p class="mb-0"><strong>Atas Nama:</strong> <span id="detailAtasNama"></span></p>
                        </div>
                    </div>
                    <!-- Upload Bukti Pembayaran -->
                    <div class="form-group mt-3">
                        <label for="foto" class="form-label">Bukti Pembayaran</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*" required>
                        <small class="text-muted">Upload foto bukti transfer (format: JPG/PNG, maks 2MB)</small>
                    </div>
                    <!-- Hidden Fields -->
                    <input type="hidden" id="paymentRoomId" name="room_id">
                    <input type="hidden" id="paymentPropertiId" name="properti_id">
                    <input type="hidden" id="paymentHargaSatuan" name="harga_satuan">
                </form>
            </div>
            <div class="modal-footer sticky-bottom bg-white">
                <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                <button type="submit" form="paymentForm" class="btn btn-success rounded-pill">Konfirmasi
                    Pembayaran</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Booking Status -->
<div class="modal fade" id="bookingStatusModal" tabindex="-1" aria-labelledby="bookingStatusModalLabel"
    aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <!-- Success Icon -->
                <div id="successIcon" class="mb-3" style="display: none;">
                    <div class="mx-auto"
                        style="width: 80px; height: 80px; background: linear-gradient(135deg, #28a745, #20c997); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-check text-white" style="font-size: 40px;"></i>
                    </div>
                </div>
                <!-- Error Icon -->
                <div id="errorIcon" class="mb-3" style="display: none;">
                    <div class="mx-auto"
                        style="width: 80px; height: 80px; background: linear-gradient(135deg, #dc3545, #e74c3c); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-times text-white" style="font-size: 40px;"></i>
                    </div>
                </div>
                <!-- Loading Icon -->
                <div id="loadingIcon" class="mb-3" style="display: none;">
                    <div class="mx-auto"
                        style="width: 80px; height: 80px; background: linear-gradient(135deg, #007bff, #0056b3); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <div class="spinner-border text-white" role="status" style="width: 40px; height: 40px;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <!-- Status Title -->
                <h4 id="statusTitle" class="mb-3"></h4>
                <!-- Status Message -->
                <p id="statusMessage" class="text-muted mb-4"></p>
                <!-- Booking Details -->
                <div id="bookingDetails" style="display: none;">
                    <div class="card border-light bg-light">
                        <div class="card-body">
                            <div class="row text-start">
                                <div class="col-6">
                                    <small class="text-muted">Booking ID:</small>
                                    <p class="mb-2 fw-bold" id="bookingId">-</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Status:</small>
                                    <p class="mb-2"><span class="badge bg-warning text-dark" id="bookingStatus">Pending
                                            Review</span></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Tanggal Booking:</small>
                                    <p class="mb-2" id="bookingDate">-</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Total Pembayaran:</small>
                                    <p class="mb-2 fw-bold text-success" id="totalPayment">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-dismiss="modal"
                    id="closeModalBtn">
                    <span id="closeButtonText">Tutup</span>
                </button>
                <button type="button" class="btn btn-outline-primary rounded-pill px-4" id="viewBookingBtn"
                    style="display: none;">
                    <i class="fas fa-eye me-2"></i>Lihat Detail Booking
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="toast-container position-fixed top-0 end-0 p-3">
    <div id="bookingToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <div id="toastIcon" class="rounded me-2" style="width: 20px; height: 20px;"></div>
            <strong class="me-auto" id="toastTitle">Booking Status</strong>
            <small id="toastTime">now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage"></div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Global variables
    let selectedMetodePembayaran = [];
    let hargaSatuan = 0;
    const toastContainer = document.querySelector('.toast-container');
    const bookingToast = document.getElementById('bookingToast');

    // Initialize Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Modal Detail Booking - Show event
    const bookingModal = document.getElementById('bookingModal');
    bookingModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const roomName = button.getAttribute('data-room_name');
        const harga = button.getAttribute('data-harga');
        const hargaFormatted = button.getAttribute('data-harga_formatted');
        const properti = button.getAttribute('data-properti_detail');
        const propertiId = button.getAttribute('data-properti_id');
        const fasilitas = button.getAttribute('data-fasilitas_detail');
        const imageSrc = button.getAttribute('data-image');
        const metodePembayaran = JSON.parse(button.getAttribute('data-metode_pembayaran'));
        const roomId = button.getAttribute('data-room_id');

        // Set data to modal
        document.getElementById('modalProperti').textContent = properti;
        document.getElementById('modalFasilitas').textContent = fasilitas;
        document.getElementById('modalRoomName').textContent = roomName;
        document.getElementById('modalHarga').textContent = hargaFormatted;
        document.getElementById('modalRoomImage').src = imageSrc;
        document.getElementById('modalFullDataRoomName').value = roomName;
        document.getElementById('modalFullDataHarga').value = harga;
        document.getElementById('modalFullDataRoomId').value = roomId;
        document.getElementById('modalFullDataPropertiId').value = propertiId;

        // Store data for payment modal
        selectedMetodePembayaran = metodePembayaran;
        hargaSatuan = parseInt(harga);
    });

    // Lanjut Booking Button - Click event
    document.getElementById('lanjutBookingBtn').addEventListener('click', function () {
        // Hide booking modal
        const bookingModalInstance = bootstrap.Modal.getInstance(bookingModal);
        bookingModalInstance.hide();

        // Set data to payment modal
        const roomName = document.getElementById('modalRoomName').textContent;
        const hargaFormatted = document.getElementById('modalHarga').textContent;
        const properti = document.getElementById('modalProperti').textContent;
        const imageSrc = document.getElementById('modalRoomImage').src;
        const roomId = document.getElementById('modalFullDataRoomId').value;
        const propertiId = document.getElementById('modalFullDataPropertiId').value;

        document.getElementById('paymentModalRoomName').textContent = roomName;
        document.getElementById('paymentModalHarga').textContent = hargaFormatted;
        document.getElementById('paymentModalProperti').textContent = properti;
        document.getElementById('paymentModalRoomImage').src = imageSrc;
        document.getElementById('paymentRoomId').value = roomId;
        document.getElementById('paymentPropertiId').value = propertiId;
        document.getElementById('paymentHargaSatuan').value = hargaSatuan;

        // Populate metode pembayaran options
        const metodePembayaranSelect = document.getElementById('metode_pembayaran_id');
        metodePembayaranSelect.innerHTML = '<option value="">-- Pilih Metode Pembayaran --</option>';

        selectedMetodePembayaran.forEach(function (metode) {
            const option = document.createElement('option');
            option.value = metode.id;
            option.textContent = `${metode.nama_bank} - ${metode.no_rek}`;
            option.dataset.bank = metode.nama_bank;
            option.dataset.noRek = metode.no_rek;
            option.dataset.atasNama = metode.atas_nama;
            metodePembayaranSelect.appendChild(option);
        });

        // Reset form
        document.getElementById('paymentForm').reset();
        document.getElementById('rekeningDetail').style.display = 'none';

        // Set minimum date to today for start date
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('start_date').setAttribute('min', today);

        // Show payment modal
        const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
        paymentModal.show();
    });

    // Handle metode pembayaran selection
    document.getElementById('metode_pembayaran_id').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const rekeningDetail = document.getElementById('rekeningDetail');

        if (selectedOption.value) {
            document.getElementById('detailBank').textContent = selectedOption.dataset.bank;
            document.getElementById('detailNoRek').textContent = selectedOption.dataset.noRek;
            document.getElementById('detailAtasNama').textContent = selectedOption.dataset.atasNama;
            rekeningDetail.style.display = 'block';
        } else {
            rekeningDetail.style.display = 'none';
        }
    });

    // Handle start date change
    document.getElementById('start_date').addEventListener('change', function () {
        const startDate = new Date(this.value);
        const jumlahBulan = parseInt(document.getElementById('jumlah').value) || 0;

        if (jumlahBulan > 0) {
            calculateEndDate(startDate, jumlahBulan);
        }
    });

    // Handle jumlah bulan calculation
    document.getElementById('jumlah').addEventListener('input', function () {
        const jumlah = parseInt(this.value) || 0;
        const total = hargaSatuan * jumlah;
        const startDateInput = document.getElementById('start_date');

        document.getElementById('totalHarga').value = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('totalHargaValue').value = total;

        // Calculate end date if start date is selected
        if (startDateInput.value && jumlah > 0) {
            const startDate = new Date(startDateInput.value);
            calculateEndDate(startDate, jumlah);
        }

        // Set minimum payment (50% of total)
        const minPayment = Math.ceil(total * 0.5);
        const jumlahYangBayarInput = document.querySelector('input[name="jumlah_yang_bayar"]');
        if (jumlahYangBayarInput) {
            jumlahYangBayarInput.setAttribute('min', minPayment);
            jumlahYangBayarInput.setAttribute('max', total);
            jumlahYangBayarInput.setAttribute('placeholder', `Min: Rp ${minPayment.toLocaleString('id-ID')}`);

            // Validate current payment value
            if (jumlahYangBayarInput.value) {
                const currentPayment = parseInt(jumlahYangBayarInput.value);
                if (currentPayment < minPayment || currentPayment > total) {
                    jumlahYangBayarInput.value = '';
                }
            }
        }
    });

    // Handle jumlah yang dibayar validation
    const jumlahYangBayarInput = document.querySelector('input[name="jumlah_yang_bayar"]');
    if (jumlahYangBayarInput) {
        jumlahYangBayarInput.addEventListener('input', function () {
            const totalHarga = parseInt(document.getElementById('totalHargaValue').value) || 0;
            const jumlahYangDibayar = parseInt(this.value) || 0;
            const minPayment = Math.ceil(totalHarga * 0.5);

            if (totalHarga > 0) {
                if (jumlahYangDibayar < minPayment) {
                    this.setCustomValidity(
                        `Pembayaran minimal 50% dari total harga (Rp ${minPayment.toLocaleString('id-ID')})`);
                } else if (jumlahYangDibayar > totalHarga) {
                    this.setCustomValidity('Pembayaran tidak boleh melebihi total harga');
                } else {
                    this.setCustomValidity('');
                }
            }
        });
    }

    // Function to calculate end date
    function calculateEndDate(startDate, jumlahBulan) {
        const endDate = new Date(startDate);
        endDate.setMonth(endDate.getMonth() + jumlahBulan);
        const formattedEndDate = endDate.toISOString().split('T')[0];
        document.getElementById('end_date').value = formattedEndDate;
    }

    // File upload validation
    document.getElementById('foto').addEventListener('change', function (e) {
        const file = e.target.files[0];
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];

        if (file) {
            if (!allowedTypes.includes(file.type)) {
                showToast('error', 'Format File Tidak Valid', 'Hanya file JPG, PNG, atau GIF yang diperbolehkan.');
                this.value = '';
                return;
            }

            if (file.size > maxSize) {
                showToast('error', 'Ukuran File Terlalu Besar', 'Ukuran file maksimal 2MB.');
                this.value = '';
                return;
            }
        }
    });

    // Handle form submission
    document.getElementById('paymentForm').addEventListener('submit', function (e) {
        e.preventDefault();

        console.log('Form submitted');

        if (!this.checkValidity()) {
            this.classList.add('was-validated');
            showToast('error', 'Validasi Gagal', 'Mohon lengkapi semua field yang diperlukan.');
            return;
        }

        showLoadingModal();

        const submitBtn = document.querySelector('button[type="submit"][form="paymentForm"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...';
        }

        const formData = new FormData(this);

        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.text().then(text => {
                console.log('Response text:', text);
                try {
                    const data = JSON.parse(text);
                    return { data, status: response.status, ok: response.ok };
                } catch (e) {
                    console.error('JSON parse error:', e);
                    throw new Error('Invalid JSON response: ' + text.substring(0, 100));
                }
            });
        })
        .then(({ data, status, ok }) => {
            console.log('Parsed data:', data);
            if (ok && data.success) {
                const paymentModal = bootstrap.Modal.getInstance(document.getElementById('paymentModal'));
                if (paymentModal) paymentModal.hide();
                showSuccessModal(data.data);
                this.reset();
                this.classList.remove('was-validated');
                document.getElementById('rekeningDetail').style.display = 'none';
            } else {
                console.log('Error response:', data);
                showErrorModal(data.message || 'Terjadi kesalahan saat memproses booking.');
                if (data.errors) {
                    let errorMessages = '';
                    for (const field in data.errors) {
                        errorMessages += data.errors[field].join('<br>') + '<br>';
                    }
                    showToast('error', 'Validasi Error', errorMessages);
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            showErrorModal('Terjadi kesalahan saat memproses booking: ' + error.message);
        })
        .finally(() => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Konfirmasi Pembayaran';
            }
        });
    });

    // View Booking Button
    document.getElementById('viewBookingBtn').addEventListener('click', function () {
        const statusModal = bootstrap.Modal.getInstance(document.getElementById('bookingStatusModal'));
        if (statusModal) statusModal.hide();
    });

    // Close Modal Button
    document.getElementById('closeModalBtn').addEventListener('click', function () {
        const statusModal = bootstrap.Modal.getInstance(document.getElementById('bookingStatusModal'));
        if (statusModal) statusModal.hide();
    });

    // Show loading modal
    function showLoadingModal() {
        document.getElementById('loadingIcon').style.display = 'block';
        document.getElementById('successIcon').style.display = 'none';
        document.getElementById('errorIcon').style.display = 'none';
        document.getElementById('bookingDetails').style.display = 'none';
        document.getElementById('viewBookingBtn').style.display = 'none';
        document.getElementById('statusTitle').textContent = 'Memproses Booking...';
        document.getElementById('statusMessage').textContent = 'Mohon tunggu, booking Anda sedang diproses.';
        document.getElementById('closeButtonText').textContent = 'Tunggu...';
        document.getElementById('closeModalBtn').disabled = true;

        const modal = new bootstrap.Modal(document.getElementById('bookingStatusModal'));
        modal.show();
    }

    // Show success modal
    function showSuccessModal(data = {}) {
        document.getElementById('loadingIcon').style.display = 'none';
        document.getElementById('successIcon').style.display = 'block';
        document.getElementById('errorIcon').style.display = 'none';
        document.getElementById('bookingDetails').style.display = 'block';
        document.getElementById('viewBookingBtn').style.display = 'inline-block';
        document.getElementById('statusTitle').textContent = 'Booking Berhasil!';
        document.getElementById('statusMessage').textContent =
            'Booking Anda telah berhasil dibuat dan sedang dalam proses review oleh admin.';
        document.getElementById('bookingId').textContent = data.bookingId || '#BK' + Date.now();
        document.getElementById('bookingDate').textContent = data.bookingDate || new Date().toLocaleDateString('id-ID');
        document.getElementById('totalPayment').textContent = data.totalPayment || 'Rp 0';
        document.getElementById('bookingStatus').textContent = data.status || 'Pending Review';
        document.getElementById('closeButtonText').textContent = 'Selesai';
        document.getElementById('closeModalBtn').disabled = false;

        showToast('success', 'Booking Berhasil!', 'Booking Anda telah berhasil dibuat.');
    }

    // Show error modal
    function showErrorModal(errorMessage = 'Terjadi kesalahan saat memproses booking.') {
        document.getElementById('loadingIcon').style.display = 'none';
        document.getElementById('successIcon').style.display = 'none';
        document.getElementById('errorIcon').style.display = 'block';
        document.getElementById('bookingDetails').style.display = 'none';
        document.getElementById('viewBookingBtn').style.display = 'none';
        document.getElementById('statusTitle').textContent = 'Booking Gagal';
        document.getElementById('statusMessage').textContent = errorMessage;
        document.getElementById('closeButtonText').textContent = 'Tutup';
        document.getElementById('closeModalBtn').disabled = false;

        showToast('error', 'Booking Gagal', errorMessage);
    }

    // Show toast notification
    function showToast(type, title, message) {
        if (!bookingToast) return;

        const toast = new bootstrap.Toast(bookingToast, {
            autohide: true,
            delay: 5000
        });

        const toastIcon = document.getElementById('toastIcon');
        if (toastIcon) {
            toastIcon.className = 'rounded me-2';
            if (type === 'success') {
                toastIcon.innerHTML = '<i class="fas fa-check-circle text-success"></i>';
            } else if (type === 'error') {
                toastIcon.innerHTML = '<i class="fas fa-times-circle text-danger"></i>';
            } else {
                toastIcon.innerHTML = '<i class="fas fa-info-circle text-primary"></i>';
            }
        }

        document.getElementById('toastTitle').textContent = title;
        document.getElementById('toastMessage').innerHTML = message;
        document.getElementById('toastTime').textContent = 'Baru saja';

        toast.show();
    }

    // Initialize date pickers
    function initializeDatePickers() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        if (startDateInput) {
            const today = new Date().toISOString().split('T')[0];
            startDateInput.setAttribute('min', today);
        }

        if (endDateInput) {
            endDateInput.setAttribute('readonly', 'true');
        }
    }

    // Debug hamburger click
    document.querySelector('.navbar-toggler').addEventListener('click', function () {
        console.log('Hamburger menu clicked!');
    });

    // Check for Laravel session messages
    const successMessage = document.querySelector('meta[name="success-message"]');
    const errorMessage = document.querySelector('meta[name="error-message"]');

    if (successMessage) {
        showToast('success', 'Berhasil!', successMessage.getAttribute('content'));
    }

    if (errorMessage) {
        showToast('error', 'Error!', errorMessage.getAttribute('content'));
    }

    initializeDatePickers();
});
</script>
@endpush

<style>
    /* Custom animations */
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #bookingStatusModal .modal-content {
        animation: modalSlideIn 0.3s ease-out;
    }

    /* Toast custom styles */
    .toast {
        min-width: 300px;
    }

    .toast-container {
        z-index: 1060;
        top: 70px !important; /* Geser ke bawah agar tidak menutupi navbar */
    }

    /* Loading spinner */
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .spinner-border {
        animation: spin 1s linear infinite;
    }

    /* Success/Error icons animation */
    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            opacity: 1;
            transform: scale(1.1);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }

    #successIcon,
    #errorIcon {
        animation: bounceIn 0.6s ease-out;
    }
</style>