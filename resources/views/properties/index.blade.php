@extends('layouts.admin.app')

@section('title', 'Daftar Properti')

@section('content')
    <div class="container">
        <h2 class="mb-4" style="color: #FDE5AF;">Daftar Properti</h2>
        @can('properti-create')
            <a href="{{ route('properties.create') }}" class="btn btn-custom-orange mb-3">
                <i class="fas fa-plus me-1"></i> Tambah Properti
            </a>
        @endcan
        {{-- @can('booking-create')
            <a href="{{ route('bookings.create') }}" class="btn btn-custom-orange mb-3">
                <i class="fas fa-plus me-1"></i> Tambah Booking
            </a>
        @endcan --}}
        <div class="container-fluid">
            {{-- <div class="row"> --}}
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="properties-table" class="table table-bordered table-striped ">
                                <thead>
                                    <tr>
                                        <th class="text-center" width="5%">No</th>
                                        <th class="text-center" width="20%">Nama Properti</th>
                                        <th class="text-center" width="25%">Alamat</th>
                                        <th class="text-center" width="12%">Kota</th>
                                        <th class="text-center" width="15%">Dibuat Oleh</th>
                                        <th class="text-center" width="13%">Pengelola</th>
                                        <th class="text-center" width="10%">Foto</th>
                                        <th class="text-center" width="15%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            {{-- </div> --}}
        </div>

        <!-- Modal Konfirmasi Hapus -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus properti <strong id="propertyName"></strong>?</p>
                        <p class="text-danger"><small>Data terkait juga akan dihapus.</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form id="deleteForm" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" id="deleteBtn">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css">
    <style>
        .object-cover {
            object-fit: cover;
        }

        .badge {
            font-size: 0.75rem;
        }

        /* Style untuk kolom alamat agar word wrap dan lebar tetap */
        #properties-table td:nth-child(3) {
            max-width: 200px;
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
            vertical-align: top;
        }

        /* Style untuk semua kolom teks agar vertical align top */
        #properties-table td {
            vertical-align: top;
        }

        /* Alternatif: jika ingin lebih spesifik dengan class */
        .address-column {
            max-width: 200px !important;
            word-wrap: break-word !important;
            word-break: break-word !important;
            white-space: normal !important;
            vertical-align: top !important;
            line-height: 1.4;
        }

        /* Style untuk nama properti juga agar tidak terlalu lebar */
        #properties-table td:nth-child(2) {
            max-width: 180px;
            word-wrap: break-word;
            word-break: break-word;
            white-space: normal;
            vertical-align: top;
        }

        /* Pastikan header juga align dengan body */
        #properties-table th {
            vertical-align: middle;
        }

        /* Style untuk kolom yang tidak perlu word wrap */
        #properties-table td:nth-child(1),
        /* No */
        #properties-table td:nth-child(4),
        /* Kota */
        #properties-table td:nth-child(5),
        /* Dibuat Oleh */
        #properties-table td:nth-child(6),
        /* Pengelola */
        #properties-table td:nth-child(7),
        /* Foto */
        #properties-table td:nth-child(8)

        /* Aksi */
            {
            white-space: nowrap;
        }
    </style>
@endpush

@push('scripts')
    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <script>

        // Fungsi untuk menampilkan modal konfirmasi hapus
        function deleteProperty(id, name = '') {
            // Set nama properti di modal
            $('#propertyName').text(name || 'ini');

            // Set action form
            $('#deleteForm').attr('action', '{{ url('properties') }}/' + id);

            // Tampilkan modal
            $('#deleteModal').modal('show');
        }

        $(document).ready(function() {
            // Handle form submit untuk delete
            $('#deleteForm').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let submitBtn = $('#deleteBtn');

                // Disable button dan ubah text
                submitBtn.prop('disabled', true).text('Menghapus...');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    dataType: 'json',
                    success: function(response) {
                        // Tutup modal
                        $('#deleteModal').modal('hide');

                        // Tampilkan pesan sukses
                        if (typeof toastr !== 'undefined') {
                            toastr.success(response.message || 'Properti berhasil dihapus');
                        } else {
                            alert(response.message || 'Properti berhasil dihapus');
                        }

                        // Reload DataTable
                        $('#properties-table').DataTable().ajax.reload(null, false);
                    },
                    error: function(xhr) {
                        console.error('Delete Error:', xhr);

                        let errorMessage = 'Terjadi kesalahan saat menghapus properti';

                        if (xhr.status === 419) {
                            errorMessage = 'Sesi telah habis, silakan refresh halaman';
                            // Refresh halaman setelah 2 detik
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        } else if (xhr.responseJSON) {
                            errorMessage = xhr.responseJSON.message || errorMessage;
                        } else if (xhr.status === 404) {
                            errorMessage = 'Properti tidak ditemukan';
                        } else if (xhr.status === 403) {
                            errorMessage =
                                'Anda tidak memiliki izin untuk menghapus properti ini';
                        }

                        if (typeof toastr !== 'undefined') {
                            toastr.error(errorMessage);
                        } else {
                            alert(errorMessage);
                        }
                    },
                    complete: function() {
                        // Reset button
                        submitBtn.prop('disabled', false).text('Hapus');
                    }
                });
            });

            // Reset form ketika modal ditutup
            $('#deleteModal').on('hidden.bs.modal', function() {
                $('#deleteForm').attr('action', '');
                $('#propertyName').text('');
                $('#deleteBtn').prop('disabled', false).text('Hapus');
            });

            // Inisialisasi DataTable
            $('#properties-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('properties.index') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'alamat',
                        name: 'alamat',
                        className: 'address-column' // Tambahkan class untuk styling
                    },
                    {
                        data: 'kota',
                        name: 'kota'
                    },
                    {
                        data: 'created_by',
                        name: 'users.name'
                    },
                    {
                        data: 'pengelola',
                        name: 'pengelola.nama',
                        orderable: false
                    },
                    {
                        data: 'foto',
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
                ],
                order: [
                    [1, 'asc']
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
                },
                // Tambahkan opsi untuk mengatur kolom width
                columnDefs: [{
                        width: "5%",
                        targets: 0
                    }, // No
                    {
                        width: "20%",
                        targets: 1
                    }, // Nama Properti
                    {
                        width: "25%",
                        targets: 2
                    }, // Alamat
                    {
                        width: "12%",
                        targets: 3
                    }, // Kota
                    {
                        width: "15%",
                        targets: 4
                    }, // Dibuat Oleh
                    {
                        width: "13%",
                        targets: 5
                    }, // Pengelola
                    {
                        width: "10%",
                        targets: 6
                    }, // Foto
                    {
                        width: "15%",
                        targets: 7
                    } // Aksi
                ],
                autoWidth: false // Nonaktifkan auto width agar menggunakan width yang kita set
            });
        });

        // Show success/error messages dari session
        @if (session('success'))
            $(document).ready(function() {
                if (typeof toastr !== 'undefined') {
                    toastr.success("{{ session('success') }}");
                }
            });
        @endif

        @if (session('error'))
            $(document).ready(function() {
                if (typeof toastr !== 'undefined') {
                    toastr.error("{{ session('error') }}");
                }
            });
        @endif
    </script>
@endpush
