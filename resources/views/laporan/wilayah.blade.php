<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Data Wilayah</title>
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

    .subtitle {
      text-align: center;
      font-size: 9px;
      color: #6b7280;
      margin-bottom: 12px;
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
    <div class="address">Jl. Tanjung Tabalong, Kalimantan Selatan</div>
    <div class="contact">Telp: 0511-123456 | Email: kmm@kmm.com</div>
  </div>

  <div class="title">DATA WILAYAH DISTRIBUSI</div>
  <div class="subtitle">Total Wilayah: {{ $data->count() }} | Total Toko: {{ $total_toko }} | Tanggal Cetak:
    {{ $tanggal_cetak }}</div>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th class="text-left">Nama Wilayah</th>
        <th class="text-right">Jumlah Toko</th>
        <th class="text-left">Sales Penanggung Jawab</th>
        <th class="text-left">Keterangan</th>
      </tr>
    </thead>
    <tbody>
      @forelse($data as $index => $wilayah)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td class="text-left">{{ $wilayah->nama_wilayah }}</td>
          <td class="text-right">{{ number_format($wilayah->jumlah_toko) }}</td>
          <td class="text-left">{{ $wilayah->sales->nama ?? '-' }}</td>
          <td class="text-left">{{ $wilayah->keterangan ?? '-' }}</td>
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
