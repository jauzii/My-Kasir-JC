@forelse($laporan as $index => $item)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            @php $date = $item->tanggal ?? null; @endphp
            {{ $date ? \Carbon\Carbon::parse($date)->format('d-m-Y') : '-' }}
        </td>
        <td>{{ optional($item->barang)->NamaProduk ?? optional($item->barang)->nama_barang ?? 'Barang Dihapus' }}</td>
        <td>{{ optional($item->barang)->Kategori ?? optional($item->barang)->kategori ?? '-' }}</td>
        <td>{{ $item->keterangan ?? '-' }}</td>
        <td>
            @if(($item->jenis_transaksi ?? '') == 'Masuk')
                <span class="badge bg-success" style="color: white; padding: 5px 10px;">Barang Masuk</span>
            @else
                <span class="badge bg-danger" style="color: white; padding: 5px 10px;">Barang Keluar</span>
            @endif
        </td>
        <td class="font-weight-bold">
            {{ $item->jumlah ?? (optional($item->barang)->Stok ?? '-') }} Pcs
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-3">Tidak ada transaksi pada periode tanggal ini.</td>
    </tr>
@endforelse