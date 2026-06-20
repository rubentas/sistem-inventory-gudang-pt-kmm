<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Laporan Barang Terlaris</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Helvetica', 'Arial', sans-serif;
      font-size: 10px;
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
      font-size: 16px;
      font-weight: 800;
      color: #111827;
    }

    .header .address {
      font-size: 9px;
      color: #4b5563;
      margin-top: 2px;
    }

    .header .contact {
      font-size: 9px;
      color: #6b7280;
    }

    .title {
      text-align: center;
      font-size: 13px;
      font-weight: 700;
      margin: 10px 0;
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    .subtitle {
      text-align: center;
      font-size: 9px;
      color: #6b7280;
      margin-bottom: 12px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12px;
    }

    th {
      background: #111827;
      color: white;
      padding: 7px 5px;
      font-size: 9px;
      font-weight: 700;
      text-align: center;
    }

    td {
      padding: 5px 5px;
      border-bottom: 1px solid #d1d5db;
      font-size: 9px;
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

    .medal {
      font-weight: 800;
    }
  </style>
</head>

<body>

  <div class="header">
    <div class="company">PT. KUDA MAS MANDIRI</div>
    <div class="address">Jl. Tanjung Tabalong, Kalimantan Selatan</div>
    <div class="contact">Telp: 0511-123456 | Email: kmm@kmm.com</div>
  </div>

  <div class="title">LAPORAN BARANG TERLARIS</div>
  <div class="subtitle">Periode: {{ $tanggal_awal }} — {{ $tanggal_akhir }}</div>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th class="text-left">Kode</th>
        <th class="text-left">Nama Barang</th>
        <th class="text-left">Kategori</th>
        <th class="text-right">Total Keluar</th>
      </tr>
    </thead>
    <tbody>
      @forelse($data as $index => $item)
        <tr>
          <td class="medal">
            @if ($index == 0)
              1
            @elseif($index == 1)
              2
            @elseif($index == 2)
              3
            @else
              {{ $index + 1 }}
            @endif
          </td>
          <td class="text-left">{{ $item->barang->kode_barang ?? '-' }}</td>
          <td class="text-left">{{ $item->barang->nama_barang ?? '-' }}</td>
          <td class="text-left">{{ $item->barang->kategori ?? '-' }}</td>
          <td class="text-right text-bold">{{ number_format($item->total_keluar) }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="5">Tidak ada data</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="footer">
    Dicetak oleh: {{ $dicetak_oleh }} | {{ $tanggal_cetak }}
  </div>

</body>

</html>
