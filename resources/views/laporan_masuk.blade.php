@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Laporan Barang Masuk</h1>
        <div class="d-flex gap-2">
            <form id="exportMasukForm" action="{{ route('laporan.masuk.export') }}" method="GET" style="display:inline">
                <input type="hidden" name="start_date" value="{{ $startDate }}" />
                <input type="hidden" name="end_date" value="{{ $endDate }}" />
                <button type="submit" class="btn btn-sm btn-primary">📥 Export CSV</button>
            </form>
            <button onclick="window.print()" class="btn btn-sm btn-outline-secondary">🖨️ Cetak</button>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Periode</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('laporan.masuk') }}" method="GET">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($masuk as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') : '-' }}</td>
                            <td>{{ optional($item->barang)->NamaProduk ?? 'Barang Dihapus' }}</td>
                            <td>{{ optional($item->barang)->Kategori ?? '-' }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td class="font-weight-bold">{{ $item->jumlah ?? '-' }} Pcs</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-3">Tidak ada transaksi masuk pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection