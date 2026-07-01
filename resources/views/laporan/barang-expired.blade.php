<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Laporan Barang Expired</title>
  <style>
    * {
      margin: 0;
      padding: 0
    }

    body {
      font-family: Arial;
      font-size: 10px;
      padding: 20px
    }

    .header {
      text-align: center;
      border-bottom: 2px solid #000;
      padding-bottom: 10px;
      margin-bottom: 15px
    }

    .header h1 {
      font-size: 16px
    }

    .title {
      font-size: 13px;
      font-weight: bold;
      text-align: center;
      margin-bottom: 12px
    }

    table {
      width: 100%;
      border-collapse: collapse
    }

    th {
      background: #000;
      color: #fff;
      padding: 6px;
      font-size: 9px
    }

    td {
      padding: 5px;
      border-bottom: 1px solid #ddd;
      font-size: 9px;
      text-align: center
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
      margin-top: 20px;
      font-size: 8px;
      text-align: center;
      color: #999
    }
  </style>
</head>

<body>
  <div class="header">
    <h1>PT. KUDA MAS MANDIRI</h1>
    <p>Jl. A. Yani RT 01, Laburan, Padang Panjang, Kec. Tanta, Kab. Tabalong, Kalsel 71561
      Ruko Putih Hijau | Seberang Kantor SBM / Samping BMC</p>
  </div>
  <div class="title">LAPORAN BARANG EXPIRED</div>
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>Barang</th>
        <th>Tgl Masuk</th>
        <th>Tgl Expired</th>
        <th>Status</th>
        <th>Supplier</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data as $i => $d)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $d->barang->nama_barang ?? '-' }}</td>
          <td>{{ $d->tanggal_masuk->format('d/m/Y') }}</td>
          <td>{{ $d->tanggal_expired->format('d/m/Y') }}</td>
          <td>{{ ucwords(str_replace('_', ' ', $d->status_expired)) }}</td>
          <td>{{ $d->supplier->nama_supplier ?? '-' }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="signature">
    <div>Mengetahui,</div>
    <div class="line"></div>
    <div class="name">{{ $dicetak_oleh }}</div>
  </div>

  <div class="footer">Dicetak: {{ $dicetak_oleh }} | {{ $tanggal_cetak }}</div>
</body>

</html>
