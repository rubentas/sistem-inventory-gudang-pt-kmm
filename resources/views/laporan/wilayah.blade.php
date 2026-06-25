<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Laporan Wilayah</title>
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
      text-align: center;
    }

    td {
      padding: 5px 6px;
      border-bottom: 1px solid #d1d5db;
      font-size: 9px;
      text-align: center;
    }

    .signature-section {
      margin-top: 30px;
      display: flex;
      justify-content: space-between;
    }

    .signature-box {
      width: 35%;
      text-align: center;
    }

    .signature-box .label {
      font-size: 10px;
      color: #4b5563;
      margin-bottom: 40px;
    }

    .signature-box .name {
      font-size: 11px;
      font-weight: 700;
      color: #111827;
      margin-top: 3px;
    }

    .signature-box .line {
      border-top: 1px solid #374151;
      width: 70%;
      margin: 0 auto;
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
    <div class="address">Jl. A. Yani RT 01, Laburan, Padang Panjang, Kec. Tanta, Kab. Tabalong, Kalsel 71561</div>
    <div class="address">Ruko Putih Hijau | Seberang Kantor SBM / Samping BMC</div>
    <div class="contact">Telp: 0511-123456 | Email: kmm@kmm.com</div>
  </div>
  <div class="title">LAPORAN WILAYAH</div>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Nama Wilayah</th>
        <th>Jumlah Toko</th>
        <th>Total Barang Keluar</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $i => $d)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $d->nama_wilayah }}</td>
          <td>{{ $d->jumlah_toko }}</td>
          <td>{{ number_format($d->total_keluar ?? 0) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="footer">Dicetak: {{ $dicetak_oleh }} | {{ $tanggal_cetak }}</div>
</body>

</html>
