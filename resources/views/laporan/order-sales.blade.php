<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Laporan Order Sales</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Helvetica', 'Arial', sans-serif;
      font-size: 9px;
      color: #1f2937;
      padding: 20px 30px;
    }

    .header {
      text-align: center;
      margin-bottom: 8px;
      border-bottom: 2px solid #111827;
      padding-bottom: 10px;
    }

    .header .company {
      font-size: 15px;
      font-weight: 800;
      color: #111827;
    }

    .header .address {
      font-size: 8px;
      color: #4b5563;
      margin-top: 2px;
    }

    .header .contact {
      font-size: 8px;
      color: #6b7280;
    }

    .title {
      text-align: center;
      font-size: 12px;
      font-weight: 700;
      margin: 10px 0;
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    .subtitle {
      text-align: center;
      font-size: 8px;
      color: #6b7280;
      margin-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12px;
    }

    th {
      background: #111827;
      color: white;
      padding: 6px 5px;
      font-size: 8px;
      font-weight: 700;
      text-transform: uppercase;
      text-align: center;
    }

    td {
      padding: 4px 5px;
      border-bottom: 1px solid #d1d5db;
      font-size: 8px;
      text-align: center;
    }

    tr:nth-child(even) td {
      background: #f9fafb;
    }

    .text-left {
      text-align: left;
    }

    .text-right {
      text-align: right;
    }

    .text-bold {
      font-weight: 700;
    }

    .footer {
      margin-top: 20px;
      font-size: 8px;
      text-align: center;
      color: #9ca3af;
      border-top: 1px solid #e5e7eb;
      padding-top: 8px;
    }
  </style>
</head>

<body>

  <div class="header">
    <div class="company">PT. KUDA MAS MANDIRI</div>
    <div class="address">Jl. Tanjung Tabalong, Kalimantan Selatan</div>
    <div class="contact">Telp: 0511-123456 | Email: kmm@kmm.com</div>
  </div>

  <div class="title">LAPORAN ORDER SALES</div>
  <div class="subtitle">Periode: {{ $tanggal_awal }} — {{ $tanggal_akhir }}</div>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th class="text-left">Tanggal</th>
        <th class="text-left">Barang</th>
        <th class="text-left">Wilayah</th>
        <th class="text-left">Nama Toko</th>
        <th class="text-left">Sales</th>
        <th class="text-right">Jumlah</th>
        <th class="text-right">Harga</th>
        <th class="text-right">Subtotal</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($data as $index => $order)
        @php
          $harga = is_numeric($order->harga_jual) ? (int) $order->harga_jual : 0;
          $subtotal = $harga * (int) $order->jumlah;
        @endphp
        <tr>
          <td>{{ $index + 1 }}</td>
          <td class="text-left">{{ $order->tanggal_order->translatedFormat('d/m/Y') }}</td>
          <td class="text-left">{{ $order->barang->nama_barang ?? '-' }}</td>
          <td class="text-left">{{ $order->wilayah->nama_wilayah ?? '-' }}</td>
          <td class="text-left">{{ $order->nama_toko ?: '-' }}</td>
          <td class="text-left">{{ $order->user->nama ?? '-' }}</td>
          <td class="text-right">{{ number_format($order->jumlah) }}</td>
          <td class="text-right">Rp {{ number_format($harga, 0, ',', '.') }}</td>
          <td class="text-right text-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
          <td>
            @if ($order->status == 'pending')
              <span style="color:#d97706;">Pending</span>
            @elseif($order->status == 'diproses')
              <span style="color:#2563eb;">Diproses</span>
            @else
              <span style="color:#059669;">Selesai</span>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="10">Tidak ada data</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="footer">
    Dicetak oleh: {{ $dicetak_oleh }} | {{ $tanggal_cetak }}
  </div>

</body>

</html>
