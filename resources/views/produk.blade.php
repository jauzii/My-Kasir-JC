@extends('layouts.app')

@section('title', 'Barang Keluar | Konveksi Cloteh')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold m-0">Barang Keluar</h2>
            <p class="text-muted">Kelola riwayat pengeluaran stok barang konveksi.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('barang.keluar.history') }}" class="btn btn-outline-secondary shadow-sm">
                <i class="fa-solid fa-clock-rotate-left me-2"></i> Riwayat Keluar
            </a>
            <button class="btn btn-success shadow-sm" data-bs-toggle="modal" data-bs-target="#modalMasuk">
                <i class="fa-solid fa-plus me-2"></i> Catat Barang Masuk
            </button>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="fa-solid fa-plus me-2"></i> Catat Barang Keluar
            </button>
        </div>
    </div>

    {{-- Alert Success/Error --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm" style="border-radius: 10px;">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm" style="border-radius: 10px;">
            {{ session('error') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Nama Barang</th>
                            <th class="py-3">Kategori</th>
                            <th class="py-3 text-center">Sisa Stok</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barang as $b)
                        <tr>
                            <td class="px-4 fw-semibold">{{ $b->NamaProduk }}</td>
                            <td>{{ $b->Kategori }}</td>
                            <td class="text-center">
                                <span class="badge {{ $b->Stok > 10 ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }}">
                                    {{ number_format($b->Stok, 0, ',', '.') }} Pcs
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group" aria-label="Aksi">
                                    <button class="btn btn-sm btn-success" title="Catat Masuk" data-id="{{ $b->id }}" data-nama="{{ $b->NamaProduk }}" data-stok="{{ $b->Stok }}" onclick="openMasukModal(this)">
                                        <i class="fa-solid fa-truck-arrow-down"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" title="Catat Keluar" data-id="{{ $b->id }}" data-nama="{{ $b->NamaProduk }}" data-stok="{{ $b->Stok }}" onclick="openKeluarModal(this)">
                                        <i class="fa-solid fa-truck-arrow-right"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light text-primary" title="Lihat Detail" disabled>
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-bold">Catat Barang Keluar</h5>
                <button type="button" class="btn-close" data-bs-close="modal"></button>
            </div>
            {{-- Form diarahkan ke rute simpan yang kita buat di controller --}}
            <form id="formKeluar" action="{{ route('barang.keluar.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">PILIH BARANG</label>
                        <select id="keluar_barang_select" class="form-select border-0 bg-light py-2" style="border-radius: 10px;" name="barang_id" required>
                            <option value="">-- Pilih Barang dari Stok --</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->id }}" data-stok="{{ $b->Stok }}">{{ $b->NamaProduk }} (Stok: {{ $b->Stok }})</option>
                            @endforeach
                        </select>
                        <div id="keluar_barang_info" class="mt-2 small text-muted">Pilih barang untuk melihat stok.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">JUMLAH KELUAR</label>
                        <input type="number" class="form-control border-0 bg-light py-2" style="border-radius: 10px;" name="jumlah" required min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">TUJUAN / PELANGGAN</label>
                        <input id="keluar_tujuan" type="text" class="form-control border-0 bg-light py-2" style="border-radius: 10px;" name="tujuan" required placeholder="Contoh: Pelanggan Jauzi / Toko Orange">
                    </div>
                    <div class="mb-1 text-muted small" id="keluar_error" style="display:none"></div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light" style="border-radius: 10px;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" style="border-radius: 10px; background-color: #3b82f6;">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk Catat Barang Masuk -->
<div class="modal fade" id="modalMasuk" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 20px;">
            <div class="modal-header border-0 px-4 pt-4">
                <h5 class="fw-bold">Catat Barang Masuk</h5>
                <button type="button" class="btn-close" data-bs-close="modal"></button>
            </div>
            <form id="formMasuk" action="{{ route('barang.masuk.store') }}" method="POST">
                @csrf
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">PILIH BARANG</label>
                        <select id="masuk_barang_select" class="form-select border-0 bg-light py-2" style="border-radius: 10px;" name="barang_id" required>
                            <option value="">-- Pilih Barang dari Stok --</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->id }}" data-stok="{{ $b->Stok }}">{{ $b->NamaProduk }} (Stok: {{ $b->Stok }})</option>
                            @endforeach
                        </select>
                        <div id="masuk_barang_info" class="mt-2 small text-muted">Pilih barang untuk menambahkan stok.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">JUMLAH MASUK</label>
                        <input type="number" class="form-control border-0 bg-light py-2" style="border-radius: 10px;" name="jumlah" required min="1" value="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-bold">KETERANGAN (opsional)</label>
                        <input type="text" class="form-control border-0 bg-light py-2" style="border-radius: 10px;" name="keterangan" placeholder="Contoh: Supplier Jauzi / Terima dari ...">
                    </div>
                    <div class="mb-1 text-muted small" id="masuk_error" style="display:none"></div>
                </div>
                <div class="modal-footer border-0 px-4 pb-4">
                    <button type="button" class="btn btn-light" style="border-radius: 10px;" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4" style="border-radius: 10px; background-color: #10b981;">Simpan Transaksi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .table thead th {
        font-size: 0.85rem;
        letter-spacing: 0.05em;
        color: #64748b;
        border: none;
    }
    .table tbody td {
        border-color: #f1f5f9;
        font-size: 0.95rem;
    }
    .bg-danger-subtle {
        background-color: #fee2e2 !important;
        color: #dc2626 !important;
    }
    .bg-success-subtle {
        background-color: #dcfce7 !important;
        color: #16a34a !important;
    }
</style>

<script>
    // Prefill modal when clicking Catat Keluar button
    function openKeluarModal(btn) {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-nama');
        const stok = parseInt(btn.getAttribute('data-stok') || 0, 10);

        const select = document.getElementById('keluar_barang_select');
        select.value = id;

        const info = document.getElementById('keluar_barang_info');
        info.textContent = 'Memilih: ' + name + ' — Sisa Stok: ' + stok + ' Pcs';

        // set jumlah max attribute
        const jumlahInput = document.querySelector('input[name="jumlah"]');
        if (jumlahInput) {
            jumlahInput.max = stok;
            jumlahInput.value = stok > 0 ? 1 : 0;
        }

        // Show modal
        var myModal = new bootstrap.Modal(document.getElementById('modalTambah'));
        myModal.show();
    }

    // Update info when selecting from select box
    document.getElementById('keluar_barang_select').addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        const stok = opt ? opt.getAttribute('data-stok') : null;
        const info = document.getElementById('keluar_barang_info');
        if (stok !== null) {
            info.textContent = 'Sisa Stok: ' + stok + ' Pcs';
            document.querySelector('input[name="jumlah"]').max = stok;
        } else {
            info.textContent = 'Pilih barang untuk melihat stok.';
            document.querySelector('input[name="jumlah"]').removeAttribute('max');
        }
    });

    // Basic client-side validation for jumlah
    document.getElementById('formKeluar').addEventListener('submit', function(e) {
        const jumlah = parseInt(document.querySelector('input[name="jumlah"]').value || 0, 10);
        const select = document.getElementById('keluar_barang_select');
        const stok = parseInt(select.options[select.selectedIndex].getAttribute('data-stok') || 0, 10);
        const error = document.getElementById('keluar_error');

        if (!select.value) {
            e.preventDefault();
            error.style.display = 'block';
            error.textContent = 'Pilih barang terlebih dahulu.';
            return false;
        }

        if (jumlah <= 0 || jumlah > stok) {
            e.preventDefault();
            error.style.display = 'block';
            error.textContent = 'Jumlah tidak valid. Pastikan antara 1 dan ' + stok + '.';
            return false;
        }

        return true;
    });

    // Fungsi untuk membuka modal Masuk (prefill)
    function openMasukModal(btn) {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-nama');
        const stok = parseInt(btn.getAttribute('data-stok') || 0, 10);

        const select = document.getElementById('masuk_barang_select');
        if (select) select.value = id;

        const info = document.getElementById('masuk_barang_info');
        if (info) info.textContent = 'Memilih: ' + name + ' — Sisa Stok: ' + stok + ' Pcs';

        const jumlahInput = document.querySelector('#formMasuk input[name="jumlah"]');
        if (jumlahInput) jumlahInput.value = 1;

        var myModal = new bootstrap.Modal(document.getElementById('modalMasuk'));
        myModal.show();
    }

    // Update info when selecting from select box (masuk)
    var masukSelect = document.getElementById('masuk_barang_select');
    if (masukSelect) {
        masukSelect.addEventListener('change', function() {
            const opt = this.options[this.selectedIndex];
            const stok = opt ? opt.getAttribute('data-stok') : null;
            const info = document.getElementById('masuk_barang_info');
            if (stok !== null) {
                info.textContent = 'Sisa Stok: ' + stok + ' Pcs';
            } else {
                info.textContent = 'Pilih barang untuk menambahkan stok.';
            }
        });
    }

    // Basic validation for masuk form
    var formMasuk = document.getElementById('formMasuk');
    if (formMasuk) {
        formMasuk.addEventListener('submit', function(e) {
            const jumlah = parseInt(document.querySelector('#formMasuk input[name="jumlah"]').value || 0, 10);
            const select = document.getElementById('masuk_barang_select');
            const error = document.getElementById('masuk_error');

            if (!select || !select.value) {
                e.preventDefault();
                error.style.display = 'block';
                error.textContent = 'Pilih barang terlebih dahulu.';
                return false;
            }

            if (jumlah <= 0) {
                e.preventDefault();
                error.style.display = 'block';
                error.textContent = 'Jumlah tidak valid. Masukkan nilai minimal 1.';
                return false;
            }

            return true;
        });
    }
</script>

@endsection