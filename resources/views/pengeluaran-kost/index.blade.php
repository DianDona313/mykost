@extends('layouts.admin.app')

@section('title', 'Data Pengeluaran Kost')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Pengeluaran Kost</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary btn-sm"
                                onclick="window.location.href='{{ route('pengeluaran-kost.create') }}'">
                                <i class="fas fa-plus"></i> Tambah Data
                            </button>
                            <a href="{{ route('pengeluaran-kost.export', ['format' => 'excel']) }}"
                                class="btn btn-sm btn-success">
                                <i class="fas fa-file-excel"></i> Export Excel
                            </a>

                            <!-- Tombol Export PDF -->
                            <a href="{{ route('pengeluaran-kost.export.pdf', ['format' => 'pdf']) }}"
                                class="btn btn-sm btn-danger">
                                <i class="fas fa-file-pdf"></i> Export PDF
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" id="bulkDeleteBtn" style="display: none;">
                                <i class="fas fa-trash"></i> Hapus Terpilih
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="pengeluaranTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">
                                            <input type="checkbox" id="selectAll">
                                        </th>
                                        <th width="5%">No</th>
                                        <th>Properti</th>
                                        <th>Kategori</th>
                                        <th>Keperluan</th>
                                        <th>Jumlah</th>
                                        <th>Tanggal</th>
                                        <th>Status</th>
                                        <th>Dibuat Oleh</th>
                                        <th width="15%">Aksi</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    <div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Pengeluaran</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Properti:</strong></td>
                                    <td id="detail_property"></td>
                                </tr>
                                <tr>
                                    <td><strong>Kategori:</strong></td>
                                    <td id="detail_kategori"></td>
                                </tr>
                                <tr>
                                    <td><strong>Keperluan:</strong></td>
                                    <td id="detail_keperluan"></td>
                                </tr>
                                <tr>
                                    <td><strong>Jumlah:</strong></td>
                                    <td id="detail_jumlah"></td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal:</strong></td>
                                    <td id="detail_tanggal"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td id="detail_status"></td>
                                </tr>
                                <tr>
                                    <td><strong>Dibuat Oleh:</strong></td>
                                    <td id="detail_created_by"></td>
                                </tr>
                                <tr>
                                    <td><strong>Diubah Oleh:</strong></td>
                                    <td id="detail_updated_by"></td>
                                </tr>
                                <tr>
                                    <td><strong>Bukti:</strong></td>
                                    <td id="detail_bukti"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <strong>Keterangan:</strong>
                            <p id="detail_keterangan" class="mt-2"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#pengeluaranTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pengeluaran-kost.index') }}",
                    data: function(d) {
                        d.property_id = $('#filterProperty').val();
                        d.kategori_id = $('#filterKategori').val();
                        d.status = $('#filterStatus').val();
                    },
                    error: function(xhr, error, thrown) {
                        console.log('DataTables Ajax Error:', xhr.responseText);
                        Swal.fire('Error', 'Gagal memuat data tabel', 'error');
                    }
                },
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return '<input type="checkbox" class="select-item" value="' + row.id +
                                '">';
                        }
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'property_nama',
                        name: 'property.nama',
                        defaultContent: '-'
                    },
                    {
                        data: 'kategori_nama',
                        name: 'kategoriPengeluaran.nama',
                        defaultContent: '-'
                    },
                    {
                        data: 'keperluan',
                        name: 'keperluan',
                        defaultContent: '-'
                    },
                    {
                        data: 'jumlah_format',
                        name: 'jumlah',
                        defaultContent: '-'
                    },
                    {
                        data: 'tanggal_format',
                        name: 'tanggal_pengeluaran',
                        defaultContent: '-'
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        defaultContent: '-'
                    },
                    {
                        data: 'created_by_name',
                        name: 'creator.name',
                        defaultContent: '-'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        defaultContent: '-'
                    }
                ],
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                language: {
                    url: "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json",
                    emptyTable: "Tidak ada data pengeluaran yang tersedia",
                    zeroRecords: "Tidak ditemukan data yang sesuai dengan pencarian",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    loadingRecords: "Memuat data...",
                    processing: "Sedang memproses...",
                    search: "Cari:",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });

            // Load filter options
            loadFilterOptions();

            // Filter events
            $('#filterProperty, #filterKategori, #filterStatus').change(function() {
                table.draw();
            });

            // Reset filter
            $('#resetFilter').click(function() {
                $('#filterProperty, #filterKategori, #filterStatus').val('');
                table.draw();
            });

            // Select all checkbox
            $('#selectAll').change(function() {
                $('.select-item').prop('checked', this.checked);
                toggleBulkDeleteBtn();
            });

            // Individual checkbox
            $(document).on('change', '.select-item', function() {
                toggleBulkDeleteBtn();
            });

            // Bulk delete
            $('#bulkDeleteBtn').click(function() {
                var selectedIds = [];
                $('.select-item:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length === 0) {
                    Swal.fire('Peringatan', 'Pilih data yang akan dihapus', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Anda yakin ingin menghapus ' + selectedIds.length + ' data terpilih?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('pengeluaran-kost.bulk-delete') }}",
                            type: 'POST',
                            data: {
                                ids: selectedIds,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire('Berhasil', response.message, 'success');
                                    table.draw();
                                    $('#selectAll').prop('checked', false);
                                    toggleBulkDeleteBtn();
                                } else {
                                    Swal.fire('Error', response.message, 'error');
                                }
                            },
                            error: function(xhr) {
                                console.log('Bulk Delete Error:', xhr.responseText);
                                Swal.fire('Error',
                                    'Terjadi kesalahan saat menghapus data', 'error'
                                );
                            }
                        });
                    }
                });
            });
        });

        function loadFilterOptions() {
            // Load properties with error handling
            $.get("{{ route('pengeluaran-kost.select-data') }}?type=kategori")
                .done(function(data) {
                    var options = '<option value="">Semua Properti</option>';
                    if (data && data.length > 0) {
                        $.each(data, function(index, item) {
                            options += '<option value="' + (item.id || '') + '">' + (item.text || item.name ||
                                'Unnamed') + '</option>';
                        });
                    }
                    $('#filterProperty').html(options);
                })
                .fail(function(xhr) {
                    console.log('Error loading properties:', xhr.responseText);
                    $('#filterProperty').html(
                        '<option value="">Semua Properti</option><option disabled>Gagal memuat data</option>');
                });

            // Load categories with error handling
            $.get("{{ route('pengeluaran-kost.select-data') }}?type=kategori")
                .done(function(data) {
                    var options = '<option value="">Semua Kategori</option>';
                    if (data && data.length > 0) {
                        $.each(data, function(index, item) {
                            options += '<option value="' + (item.id || '') + '">' + (item.text || item.name ||
                                'Unnamed') + '</option>';
                        });
                    }
                    $('#filterKategori').html(options);
                })
                .fail(function(xhr) {
                    console.log('Error loading categories:', xhr.responseText);
                    $('#filterKategori').html(
                        '<option value="">Semua Kategori</option><option disabled>Gagal memuat data</option>');
                });
        }

        function toggleBulkDeleteBtn() {
            var checkedCount = $('.select-item:checked').length;
            if (checkedCount > 0) {
                $('#bulkDeleteBtn').show();
            } else {
                $('#bulkDeleteBtn').hide();
            }
        }

        function showDetail(id) {
            if (!id) {
                Swal.fire('Error', 'ID tidak valid', 'error');
                return;
            }

            // Using placeholder technique to fix route parameter issue
            var detailUrl = "{{ route('pengeluaran-kost.show', 'PLACEHOLDER') }}".replace('PLACEHOLDER', id);

            $.get(detailUrl)
                .done(function(response) {
                    if (response && response.data) {
                        $('#detail_property').text(response.property_nama || '-');
                        $('#detail_kategori').text(response.kategori_nama || '-');
                        $('#detail_keperluan').text(response.data.keperluan || '-');
                        $('#detail_jumlah').text(response.jumlah_format || '-');
                        $('#detail_tanggal').text(response.tanggal_format || '-');
                        $('#detail_status').html(response.status_badge || '-');
                        $('#detail_created_by').text(response.created_by_name || '-');
                        $('#detail_updated_by').text(response.updated_by_name || '-');
                        $('#detail_keterangan').text(response.data.keterangan || '-');

                        if (response.data.bukti_pengeluaran) {
                            $('#detail_bukti').html('<a href="/storage/' + response.data.bukti_pengeluaran +
                                '" target="_blank" class="btn btn-sm btn-info">Lihat Bukti</a>');
                        } else {
                            $('#detail_bukti').text('-');
                        }

                        $('#detailModal').modal('show');
                    } else {
                        Swal.fire('Error', 'Data tidak ditemukan', 'error');
                    }
                })
                .fail(function(xhr) {
                    console.log('Detail Error:', xhr.responseText);
                    var errorMessage = 'Gagal memuat detail data';
                    if (xhr.status === 404) {
                        errorMessage = 'Data tidak ditemukan';
                    } else if (xhr.status === 403) {
                        errorMessage = 'Tidak memiliki akses untuk melihat data';
                    }
                    Swal.fire('Error', errorMessage, 'error');
                });
        }

        function editData(id) {
            if (!id) {
                Swal.fire('Error', 'ID tidak valid', 'error');
                return;
            }

            // Using placeholder technique to fix route parameter issue
            var editUrl = "{{ route('pengeluaran-kost.edit', 'PLACEHOLDER') }}".replace('PLACEHOLDER', id);
            window.location.href = editUrl;
        }

        function deleteData(id) {
            if (!id) {
                Swal.fire('Error', 'ID tidak valid', 'error');
                return;
            }

            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Anda yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Using placeholder technique to fix route parameter issue
                    var deleteUrl = "{{ route('pengeluaran-kost.destroy', 'PLACEHOLDER') }}".replace('PLACEHOLDER',
                        id);

                    $.ajax({
                        url: deleteUrl,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function(response) {
                            if (response && response.success) {
                                Swal.fire('Berhasil', response.message || 'Data berhasil dihapus',
                                    'success');
                                $('#pengeluaranTable').DataTable().draw();
                            } else {
                                Swal.fire('Error', response.message || 'Gagal menghapus data', 'error');
                            }
                        },
                        error: function(xhr) {
                            console.log('Delete Error:', xhr.responseText);
                            var errorMessage = 'Terjadi kesalahan saat menghapus data';
                            if (xhr.status === 404) {
                                errorMessage = 'Data tidak ditemukan';
                            } else if (xhr.status === 403) {
                                errorMessage = 'Tidak memiliki akses untuk menghapus data';
                            }
                            Swal.fire('Error', errorMessage, 'error');
                        }
                    });
                }
            });
        }
    </script>
@endpush
