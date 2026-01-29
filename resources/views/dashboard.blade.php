@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap');

    :root {
        --primary: #0891b2;
        --bg-body: #f8fafc;
        --text-dark: #1e293b;
        --text-muted: #64748b;
        --white: #ffffff;
    }

    * {
        box-sizing: border-box;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    body {
        margin: 0;
        background: var(--bg-body);
        color: var(--text-dark);
    }

    .content {
        padding: 40px 30px;
        margin-top: 100px
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .welcome-section { margin-bottom: 30px; }
    .welcome-section h2 { font-weight: 700; font-size: 1.75rem; margin: 0; letter-spacing: -0.025em; }
    .welcome-section p { color: var(--text-muted); margin-top: 5px; }

    .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: var(--white);
        padding: 24px;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 20px -5px rgba(0, 0, 0, 0.05);
        border-color: var(--primary);
    }

    .card-header-flex { display: flex; justify-content: space-between; align-items: flex-start; }
    .stat-card h3 { margin: 0; font-size: 0.85rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; }

    .icon-box {
        width: 45px; height: 45px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center; font-size: 1.25rem;
    }

    .bg-light-blue { background: #e0f2fe; color: #0369a1; }
    .bg-light-green { background: #dcfce7; color: #15803d; }
    .bg-light-purple { background: #f3e8ff; color: #7e22ce; }

    .stat-card .value { font-size: 2.25rem; font-weight: 700; margin: 10px 0 4px 0; color: var(--text-dark); }
    .stat-card .desc { font-size: 0.85rem; color: var(--text-muted); font-weight: 500; }
    .stat-card .trend-up { color: #10b981; font-weight: 600; }

    /* Table Styling */
    .table-container {
        background: var(--white);
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-top: 20px;
    }

    .table-header { padding: 20px 24px; border-bottom: 1px solid #e2e8f0; }
    .table-header h4 { margin: 0; font-weight: 700; font-size: 1.1rem; }

    .badge-stok { padding: 5px 12px; border-radius: 8px; font-weight: 600; font-size: 12px; }
    .bg-low { background: #fee2e2; color: #dc2626; }
    .bg-normal { background: #dcfce7; color: #16a34a; }
</style>

<div class="content">
    <div class="welcome-section">
        <h2>Selamat Datang, {{ Auth::user()->username }}! </h2>
    </div>

    <div class="cards-grid">
        <div class="stat-card">
            <div class="card-header-flex">
                <h3>Total Stok</h3>
                <div class="icon-box bg-light-blue">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <div class="value">{{ $totalStok }}</div>
            <div class="desc">Produk tersedia di gudang</div>
        </div>

        <div class="stat-card">
            <div class="card-header-flex">
                <h3>Barang Masuk</h3>
                <div class="icon-box bg-light-green">
                    <i class="fa-solid fa-arrow-down-long"></i>
                </div>
            </div>

            @if($barangMasuk > 0)
                <div class="value">{{ number_format($barangMasuk, 0, ',', '.') }} unit</div>
                <div class="desc">Total barang masuk minggu ini</div>
            @else
                <p>Belum ada barang masuk.</p>
            @endif
        </div>

        <div class="stat-card">
            <div class="card-header-flex">
                <h3>Barang Keluar</h3>
                <div class="icon-box bg-light-purple">
                    <i class="fa-solid fa-arrow-up-long"></i>
                </div>
            </div>
            <div class="value">85</div>
            <div class="desc">Transaksi hari ini</div>
        </div>
    </div>

    <div class="table-container">
        <div class="table-header">
            <h4>Daftar Barang Terbaru</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-hover text-nowrap mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="px-4 py-3 border-0">Nama Barang</th>
                        <th class="py-3 border-0">Kategori</th>
                        <th class="py-3 border-0 text-center">Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @if(@isset($barang) && $barang->count() > 0)
                        @foreach($barang->take(5) as $b)
                            <tr>
                                <td class="px-4 py-3 align-middlefw-bold text-dark">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar bg-light-blue text-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width: 35px; height: 35px; font-size: 14px; font-weight: 700;">
                                            {{ strtoupper(substr($b->NamaProduk, 0, 1)) }}
                                        </div>
                                        <span class="text-caoitalive">{{ $b->NamaProduk }}</span>
                                    </div>
                                </td>
                                <td class="py-3 align-middle text-muted">
                                    {{ $b->Kategori ?? 'Tanpa Kategori' }}
                                </td>
                                <td class="py-3 align-middle text-center">
                                    <span class="badge-stok {{ $b->Stok <= 10 ? 'bg-low' : 'bg-normal' }}">
                                        {{ $b->Stok }} Pcs
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4 text-muted">
                                <i class="fa-solid fa-box-oopen d-block mb-2" style="font-size: 24px;"></i>
                                Belum ada data barang tersedia.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection