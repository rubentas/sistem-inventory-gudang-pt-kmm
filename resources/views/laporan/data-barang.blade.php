<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Data Barang</title>
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
      padding: 25px 35px;
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
      margin: 12px 0;
      letter-spacing: 1px;
      text-transform: uppercase;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 14px;
    }

    th {
      background: #111827;
      color: white;
      padding: 7px 6px;
      font-size: 9px;
      font-weight: 700;
      text-transform: uppercase;
      text-align: center;
    }

    td {
      padding: 5px 6px;
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

    .signature {
      margin-top: 30px;
      text-align: right;
    }

    .signature .line {
      border-top: 1px solid #374151;
      width: 150px;
      display: inline-block;
      margin-top: 40px;
    }

    .signature .name {
      font-size: 10px;
      font-weight: 700;
      color: #111827;
      margin-top: 5px;
    }

    .footer {
      margin-top: 25px;
      font-size: 9px;
      text-align: center;
      color: #9ca3af;
      border-top: 1px solid #e5e7eb;
      padding-top: 10px;
    }
  </style>
</head>

<body>

  <div class="header">
    <div class="company">PT. KUDA MAS MANDIRI</div>
    <div class="address">Jl. A. Yani RT 01, Laburan, Padang Panjang, Kec. Tanta, Kab. Tabalong, Kalsel 71561
      Ruko Putih Hijau | Seberang Kantor SBM / Samping BMC</div>
    <div class="contact">Telp: 0511-123456 | Email: kmm@kmm.com</div>
  </div>

  <div class="title">DATA BARANG</div>
  <div class="subtitle">Tanggal Cetak: {{ $tanggal_cetak }}</div>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th class="text-left">Kode Barang</th>
        <th class="text-left">Nama Barang</th>
        <th>Kategori</th>
        <th>Satuan</th>
        <th class="text-right">Harga Jual (Rp)</th>
        <th class="text-right">Stok Minimum</th>
        <th class="text-right">Stok Saat Ini</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($data as $index => $barang)
        @php $stokSekarang = $barang->stok ? $barang->stok->jumlah_stok : 0; @endphp
        <tr>
          <td>{{ $index + 1 }}</td>
          <td class="text-left">{{ $barang->kode_barang }}</td>
          <td class="text-left">{{ $barang->nama_barang }}</td>
          <td>{{ $barang->kategori ?? '-' }}</td>
          <td>{{ $barang->satuan }}</td>
          <td class="text-right">{{ number_format($barang->harga_jual_default ?? 0, 0, ',', '.') }}</td>
          <td class="text-right">{{ number_format($barang->stok_minimum) }}</td>
          <td class="text-right text-bold">{{ number_format($stokSekarang) }}</td>
          <td>
            @if ($stokSekarang <= $barang->stok_minimum)
              <span style="color: #dc2626; font-weight: 700;">⚠ Menipis</span>
            @else
              <span style="color: #059669;">Aman</span>
            @endif
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="9">Tidak ada data</td>
        </tr>
      @endforelse
    </tbody>
  </table>

  <div class="signature">
    <div>Mengetahui,</div>
    <div class="line"></div>
    <div class="name">{{ $dicetak_oleh }}</div>
  </div>

  <div class="footer">
    Dicetak oleh: {{ $dicetak_oleh }} | {{ $tanggal_cetak }}
  </div>

</body>

</html>
