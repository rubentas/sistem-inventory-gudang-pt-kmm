<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Invoice {{ $order->no_invoice }}</title>
  <style>
    body {
      font-family: 'Helvetica', sans-serif;
      font-size: 12px;
      line-height: 1.5;
    }

    .header {
      text-align: center;
      margin-bottom: 20px;
      border-bottom: 2px solid #333;
      padding-bottom: 10px;
    }

    .title {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .subtitle {
      font-size: 14px;
      color: #666;
    }

    .info-table {
      width: 100%;
      margin-bottom: 20px;
    }

    .info-table td {
      padding: 5px;
    }

    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .items-table th,
    .items-table td {
      border: 1px solid #ddd;
      padding: 8px;
      text-align: left;
    }

    .items-table th {
      background: #f5f5f5;
      font-weight: bold;
    }

    .total {
      text-align: right;
      font-size: 14px;
      font-weight: bold;
      margin-top: 20px;
    }

    .footer {
      margin-top: 30px;
      text-align: center;
      font-size: 10px;
      color: #999;
      border-top: 1px solid #ddd;
      padding-top: 10px;
    }
  </style>
</head>

<body>
  <div class="header">
    <div class="title">PT. KUDA MAS MANDIRI</div>
    <div class="subtitle">Tanjung Tabalong, Kalimantan Selatan</div>
    <div class="subtitle">Telp: 0511-123456 | Email: kmm@kmm.com</div>
  </div>

  <h3 style="text-align: center;">INVOICE</h3>

  <table class="info-table">
    <tr>
      <td width="50%"><strong>No. Invoice:</strong> {{ $order->no_invoice }}</td>
      <td width="50%"><strong>Tanggal:</strong> {{ $order->tanggal_order->format('d/m/Y') }}</td>
    </tr>
    <tr>
      <td><strong>Nama Toko:</strong> {{ $order->nama_toko ?? '-' }}</td>
      <td><strong>Wilayah:</strong> {{ $order->wilayah->nama_wilayah ?? '-' }}</td>
    </tr>
    <tr>
      <td><strong>Sales:</strong> {{ $order->user->nama ?? '-' }}</td>
      <td><strong>Status:</strong> {{ ucfirst($order->status) }}</td>
    </tr>
  </table>

  <table class="items-table">
    <thead>
      <tr>
        <th>Nama Barang</th>
        <th>Kode</th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th>Harga Satuan</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{ $order->barang->nama_barang ?? '-' }}</td>
        <td>{{ $order->barang->kode_barang ?? '-' }}</td>
        <td>{{ number_format($order->jumlah) }}</td>
        <td>{{ $order->barang->satuan ?? 'pcs' }}</td>
        <td>Rp {{ number_format($order->harga_satuan, 0, ',', '.') }}</td>
        <td>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
      </tr>
    </tbody>
  </table>

  @if ($order->potongan > 0)
    <div class="total">Potongan: Rp {{ number_format($order->potongan, 0, ',', '.') }}</div>
  @endif
  <div class="total">TOTAL: Rp {{ number_format($order->total_harga, 0, ',', '.') }}</div>

  <div class="footer">
    Terima kasih atas kepercayaan Anda.<br>
    Invoice ini dibuat secara otomatis oleh sistem.
  </div>
</body>

</html>
