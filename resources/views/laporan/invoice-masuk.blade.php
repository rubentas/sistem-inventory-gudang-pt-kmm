<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Invoice {{ $item->no_invoice }}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      font-size: 12px;
      padding: 30px;
    }

    .header {
      text-align: center;
      border-bottom: 2px solid #2563eb;
      padding-bottom: 14px;
      margin-bottom: 20px;
    }

    .header h1 {
      font-size: 20px;
      color: #111827;
    }

    .header p {
      font-size: 10px;
      color: #6b7280;
    }

    .title {
      text-align: center;
      font-size: 14px;
      font-weight: 800;
      color: #2563eb;
      margin-bottom: 18px;
      text-transform: uppercase;
    }

    .info {
      background: #f9fafb;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .info div {
      margin: 4px 0;
      font-size: 11px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th {
      background: #2563eb;
      color: white;
      padding: 8px;
      font-size: 10px;
      text-align: left;
    }

    td {
      padding: 8px;
      border-bottom: 1px solid #e5e7eb;
      font-size: 10px;
    }

    .footer {
      margin-top: 30px;
      text-align: center;
      font-size: 9px;
      color: #999;
      border-top: 1px solid #e5e7eb;
      padding-top: 10px;
    }

    .signature {
      margin-top: 50px;
      display: flex;
      justify-content: space-between;
    }

    .signature-box {
      width: 40%;
      text-align: center;
    }

    .signature-box .line {
      border-top: 1px solid #000;
      width: 70%;
      margin: 40px auto 5px;
    }
  </style>
</head>

<body>
  <div class="header">
    <h1>PT. Kuda Mas Mandiri</h1>
    <p>Jl. A. Yani RT 01, Laburan, Padang Panjang, Kec. Tanta, Kab. Tabalong, Kalsel 71561</p>
    <p>Telp: 0511-123456 | Email: kmm@kmm.com</p>
  </div>
  <div class="title">Invoice Barang Masuk</div>
  <div class="info">
    <div><strong>No. Invoice:</strong> {{ $item->no_invoice }}</div>
    <div><strong>Tanggal:</strong> {{ $item->tanggal_masuk->translatedFormat('d F Y') }}</div>
    <div><strong>Supplier:</strong> {{ $item->supplier->nama_supplier ?? '-' }}</div>
    <div><strong>Admin:</strong> {{ $item->user->nama ?? 'System' }}</div>
  </div>
  <table>
    <thead>
      <tr>
        <th>Barang</th>
        <th>Kode</th>
        <th>Jumlah</th>
        <th>Satuan</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{ $item->barang->nama_barang ?? '-' }}</td>
        <td>{{ $item->barang->kode_barang ?? '-' }}</td>
        <td>{{ number_format($item->jumlah) }}</td>
        <td>{{ $item->barang->satuan ?? 'Dos' }}</td>
      </tr>
    </tbody>
  </table>
  <div class="signature">
    <div class="signature-box">
      <div class="line"></div>
      <p>{{ $item->supplier->nama_supplier ?? 'Supplier' }}</p>
    </div>
    <div class="signature-box">
      <div class="line"></div>
      <p>{{ $item->user->nama ?? 'Admin' }}</p>
    </div>
  </div>
  <div class="footer">Terima kasih. Invoice ini dibuat otomatis oleh sistem.</div>
</body>

</html>
