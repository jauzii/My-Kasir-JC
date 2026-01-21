@extends('layouts.header')

<!-- Link CSS External -->
<link rel="stylesheet" href="{{ asset('css/barang.css') }}">

<div class="container main-container">
    <h2 class="card-title">📦 Kelola Produk Kami</h2>

    <!-- Alert Success -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="custom-card">
        <h5 class="mb-4" style="color: #17a2b8;" id="form-title">Tambah Barang Baru</h5>
        <form action="{{ route('barang.store') }}" method="POST" id="barangForm" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nama Produk</label>
                    <input type="text" name="NamaProduk" id="NamaProduk" class="form-control" placeholder="Contoh: Susu UHT" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Kategori</label>
                    <select name="Kategori" id="Kategori" class="form-select" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                        <option value="sembako">Sembako</option>
                        <option value="frozen food">Frozen Food</option>
                        <option value="susu dan olahan">Produk Susu dan Olahan</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Harga Beli (Rp)</label>
                    <input type="number" name="HargaBeli" id="HargaBeli" class="form-control" placeholder="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Harga Jual (Rp)</label>
                    <input type="number" name="HargaJual" id="HargaJual" class="form-control" placeholder="0" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stok</label>
                    <input type="number" name="Stok" id="Stok" class="form-control" placeholder="0" required>
                </div>
                <div class="col-12 mt-4 text-end">
                    <button type="button" class="btn btn-secondary me-2" id="btnBatal" style="display:none;" onclick="batalEdit()">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-custom-primary" id="btnSubmit">
                        Simpan Produk
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="custom-card">
        <div class="table-responsive table-container">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th class="text-center">No</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                        <th class="text-center">Stok</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $brg)
                        <tr>
                            <td class="text-center fw-bold">{{ $loop->iteration }}</td>
                            <td class="fw-medium text-dark">{{ $brg->NamaProduk }}</td>
                            <td><span class="badge-category">{{ strtoupper($brg->Kategori) }}</span></td>
                            <td>Rp {{ number_format($brg->HargaBeli, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($brg->HargaJual, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <span class="fw-bold {{ $brg->Stok < 10 ? 'text-danger' : 'text-success' }}">
                                    {{ $brg->Stok }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-success me-1" style="border-radius: 6px;" data-id="{{ $brg->id }}" data-nama="{{ $brg->NamaProduk }}" data-kategori="{{ $brg->Kategori }}" data-harga-beli="{{ $brg->HargaBeli }}" data-harga-jual="{{ $brg->HargaJual }}" data-stok="{{ $brg->Stok }}" onclick="editBarang(this)">
                                    Edit
                                </button>
                                
                                <button type="button" class="btn btn-sm btn-outline-danger" style="border-radius: 6px;" data-id="{{ $brg->id }}" onclick="hapusBarang(this)">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">Belum ada data barang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Variable untuk JavaScript -->
<script>
    const barangStoreRoute = "{{ route('barang.store') }}";
</script>

<!-- Link JavaScript External -->
<script src="{{ asset('js/barang.js') }}"></script>
```

---

## 📂 **Struktur Folder Akhir:**
```
public/
├── css/
│   └── barang.css    ← File CSS baru
└── js/
    └── barang.js     ← File JavaScript baru

resources/
└── views/
    └── barang.blade.php ← Sudah diperbaharui (tanpa inline CSS/JS)