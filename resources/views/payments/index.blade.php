@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Daftar Pembayaran</h2>
        @can('payment-create')
            <a href="{{ route('payments.create') }}" class="btn btn-custom-orange mb-3">
                <i class="fas fa-plus me-1"></i> Tambah Pembayaran
            </a>
        @endcan

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered text-center" id="payments-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Booking</th>
                            <th>Pengguna</th>
                            <th>Jumlah</th>
                            <th>Sisa</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Telah Di Bayar</th>
                            <th>Bukti</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentModalLabel">
                        <i class="fas fa-credit-card"></i> Pembayaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="paymentForm" action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        @method('PATCH')

                        <!-- Payment Info -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h6 class="mb-0">Informasi Pembayaran</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Properti:</strong> <span id="modal-property-name">-</span></p>
                                        <p><strong>Kamar:</strong> <span id="modal-room-name">-</span></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Penyewa:</strong> <span id="modal-penyewa-name">-</span></p>
                                        <p><strong>Sisa Pembayaran:</strong>
                                            <span class="text-danger fw-bold fs-5">Rp <span id="modal-sisa">0</span></span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Form -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="jumlah_bayar" class="form-label">Jumlah Pembayaran <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="jumlah_bayar" name="jumlah_bayar"
                                        required min="1">
                                    <div class="form-text">Masukkan jumlah yang akan dibayar</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Metode Pembayaran <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="payment_method" name="payment_method" required>
                                        <option value="">Pilih Metode Pembayaran</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bukti_pembayaran" class="form-label">Bukti Pembayaran</label>
                            <input type="file" class="form-control" id="bukti_pembayaran" name="bukti_pembayaran"
                                accept="image/*">
                            <div class="form-text">Upload foto bukti pembayaran (opsional)</div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" placeholder="Keterangan tambahan..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Proses Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#payments-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('payments.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'properti',
                        name: 'properti'
                    },
                    {
                        data: 'penyewa',
                        name: 'penyewa'
                    },
                    {
                        data: 'jumlah',
                        name: 'jumlah'
                    },
                    {
                        data: 'sisa',
                        name: 'sisa_pembayaran'
                    },
                    {
                        data: 'metode_pembayaran',
                        name: 'metode_pembayaran'
                    },
                    {
                        data: 'status',
                        name: 'payment_status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'telah_dibayar',
                        name: 'telah_dibayar',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'bukti',
                        name: 'foto',
                        orderable: false,
                        searchable: false
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

        // Modal handling - pastikan ini dijalankan setelah DataTable dibuat
        $(document).ready(function() {
            console.log('Document ready - setting up modal handlers');

            // Event delegation untuk tombol bayar yang dibuat secara dinamis oleh DataTable
            $(document).on('click', '.btn-primary[data-bs-target="#paymentModal"]', function() {
                console.log('Bayar button clicked');

                const button = $(this);
                const paymentId = button.data('payment-id');
                const sisa = button.data('sisa');
                const propertyName = button.data('property-name');
                const roomName = button.data('room-name');
                const penyewaName = button.data('penyewa-name');

                console.log('Data:', {
                    paymentId,
                    sisa,
                    propertyName,
                    roomName,
                    penyewaName
                });

                // Update modal content
                $('#modal-property-name').text(propertyName);
                $('#modal-room-name').text(roomName);
                $('#modal-penyewa-name').text(penyewaName);
                $('#modal-sisa').text(sisa);

                // Set form action
                $('#paymentForm').attr('action', '/payments/' + paymentId + '/pay');

                // Clear and load payment methods
                $('#payment_method').html('<option value="">Loading...</option>');
                loadPaymentMethods(paymentId);

                // Show modal
                $('#paymentModal').modal('show');
            });

            function loadPaymentMethods(paymentId) {
                console.log('Loading payment methods for payment ID:', paymentId);

                const paymentMethodSelect = $('#payment_method');
                paymentMethodSelect.html('<option value="">Loading...</option>');

                $.ajax({
                    url: `/payments/${paymentId}/payment-methods`,
                    type: 'GET',
                    success: function(data) {
                        console.log('Payment methods loaded:', data);
                        paymentMethodSelect.html('<option value="">Pilih Metode Pembayaran</option>');

                        if (data && data.length > 0) {
                            data.forEach(function(method) {
                                paymentMethodSelect.append(
                                    `<option value="${method.id}">${method.nama_bank}-${method.no_rek}</option>`
                                );
                            });
                        } else {
                            paymentMethodSelect.append(
                                '<option value="">Tidak ada metode pembayaran</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading payment methods:', error);
                        paymentMethodSelect.html('<option value="">Error loading methods</option>');
                    }
                });
            }

            // Reset form ketika modal ditutup
            $('#paymentModal').on('hidden.bs.modal', function() {
                $('#paymentForm')[0].reset();
                $('#payment_method').html('<option value="">Pilih Metode Pembayaran</option>');
            });
        });
    </script>
@endpush
