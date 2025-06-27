@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Daftar Penyewa</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center" id="penyewa-table">
                        <thead>
                            <tr>
                                <th width="5%">No</th> 
                                <th width="18%">Pengguna</th>
                                <th width="15%">Properti</th>
                                <th width="15%">Kamar</th>
                                <th width="10%">No Handphone</th>
                                <th width="15%">Email</th>
                                <th width="10%">Sisa Pembayaran</th>
                                <th width="12%">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            $('#penyewa-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('penyewas.index') }}",
                columns: [
                    {
                        data: 'DT_RowIndex', 
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'pengguna',
                        name: 'pengguna'
                    },
                    {
                        data: 'property',
                        name: 'property'
                    },
                    {
                        data: 'kamar',
                        name: 'kamar',
                        className: 'text-center'
                    },
                    {
                        data: 'no_hp',
                        name: 'no_hp',
                        className: 'text-center'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        className: 'text-center'
                    },
                    {
                        data: 'sisa_pembayaran',
                        name: 'sisa_pembayaran',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                // Konfigurasi kolom width
                // columnDefs: [
                //     { width: "5%", targets: 0 },   
                //     { width: "18%", targets: 1 },  
                //     { width: "12%", targets: 2 },  
                //     { width: "10%", targets: 3 },  
                //     { width: "10%", targets: 4 },  
                //     { width: "10%", targets: 5 },  
                //     { width: "8%", targets: 6 },   
                //     { width: "10%", targets: 7 },  
                // ],
                autoWidth: false,
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                }
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