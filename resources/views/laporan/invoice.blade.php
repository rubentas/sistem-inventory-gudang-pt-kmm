<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Invoice {{ $order->no_invoice }}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Helvetica', 'Arial', sans-serif;
      font-size: 12px;
      color: #1f2937;
      padding: 30px 40px;
    }

    .header {
      text-align: center;
      margin-bottom: 24px;
      border-bottom: 2px solid #2563eb;
      padding-bottom: 16px;
    }

    .header .title {
      font-size: 22px;
      font-weight: 800;
      color: #111827;
      letter-spacing: 0.5px;
    }

    .header .subtitle {
      font-size: 12px;
      color: #6b7280;
      margin-top: 4px;
    }

    .invoice-title {
      text-align: center;
      font-size: 16px;
      font-weight: 800;
      color: #2563eb;
      margin-bottom: 20px;
      letter-spacing: 2px;
      text-transform: uppercase;
    }

    .info-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 8px 0;
      margin-bottom: 24px;
      background: #f9fafb;
      border-radius: 8px;
      padding: 14px 18px;
      border: 1px solid #e5e7eb;
    }

    .info-grid .info-item {
      width: 50%;
      font-size: 12px;
      padding: 4px 0;
    }

    .info-item strong {
      display: inline-block;
      width: 100px;
      color: #4b5563;
    }

    .info-item span {
      color: #111827;
      font-weight: 500;
    }

    .items-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 24px;
      border-radius: 8px;
      overflow: hidden;
      border: 1px solid #e5e7eb;
    }

    .items-table th {
      background: #2563eb;
      color: white;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      padding: 10px 12px;
      text-align: left;
    }

    .items-table td {
      padding: 10px 12px;
      border-bottom: 1px solid #e5e7eb;
      font-size: 12px;
    }

    .items-table .text-right {
      text-align: right;
    }

    .total-box {
      text-align: right;
      font-size: 15px;
      font-weight: 800;
      color: #2563eb;
      border-top: 2px solid #2563eb;
      padding-top: 12px;
      margin-top: 4px;
    }

    /* Tanda Tangan */
    .signature-section {
      margin-top: 50px;
      display: flex;
      justify-content: space-between;
    }

    .signature-box {
      width: 40%;
      text-align: center;
    }

    .signature-box .label {
      font-size: 11px;
      color: #4b5563;
      margin-bottom: 50px;
    }

    .signature-box .name {
      font-size: 12px;
      font-weight: 700;
      color: #111827;
      margin-top: 5px;
    }

    .signature-box .line {
      border-top: 1px solid #374151;
      width: 80%;
      margin: 0 auto;
    }

    .footer {
      margin-top: 30px;
      text-align: center;
      font-size: 10px;
      color: #9ca3af;
      border-top: 1px solid #e5e7eb;
      padding-top: 12px;
      line-height: 1.6;
    }
  </style>
</head>

<body>

  <div class="header">
    <div class="title">PT. Kuda Mas Mandiri</div>
    <div class="subtitle">Jl. A. Yani RT 01, Laburan, Padang Panjang, Kec. Tanta, Kab. Tabalong, Kalimantan Selatan 71561
    </div>
    <div class="subtitle">Ruko Putih Hijau | Seberang Kantor SBM / Samping BMC</div>
    <div class="subtitle">Telp: 0511-123456 &nbsp;|&nbsp; Email: kmm@kmm.com</div>
  </div>

  <div class="invoice-title">Invoice</div>

  <div class="info-grid">
    <div class="info-item"><strong>No. Invoice</strong><span>: {{ $order->no_invoice }}</span></div>
    <div class="info-item"><strong>Tanggal</strong><span>: {{ $order->tanggal_order->translatedFormat('d F Y') }}</span>
    </div>
    <div class="info-item"><strong>Nama Toko</strong><span>: {{ $order->nama_toko ?: '-' }}</span></div>
    <div class="info-item"><strong>Sales</strong><span>:
        {{ $order->sales->nama_sales ?? ($order->user->nama ?? '-') }}</span></div>
    <div class="info-item"><strong>Wilayah</strong><span>: {{ $order->wilayah->nama_wilayah ?? '-' }}</span></div>
  </div>

  <table class="items-table">
    <thead>
      <tr>
        <th>Nama Barang</th>
        <th>Kode</th>
        <th>Jumlah</th>
        <th>Satuan</th>
        <th>Harga Satuan</th>
        <th class="text-right">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{ $order->barang->nama_barang ?? '-' }}</td>
        <td>{{ $order->barang->kode_barang ?? '-' }}</td>
        <td>{{ number_format($order->jumlah) }}</td>
        <td>{{ $order->barang->satuan ?? 'pcs' }}</td>
        <td>Rp {{ number_format($order->harga_jual, 0, ',', '.') }}</td>
        <td class="text-right">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
      </tr>
    </tbody>
  </table>

  <div class="total-box">
    TOTAL: Rp {{ number_format($order->subtotal, 0, ',', '.') }}
  </div>

  {{-- TANDA TANGAN --}}
  <div class="signature-section">
    <div class="signature-box">
      <div class="label">Penerima,</div>
      <div class="line"></div>
      <div class="name">{{ $order->nama_toko ?: '............................' }}</div>
    </div>
    <div class="signature-box">
      <div class="label">Hormat Kami,</div>
      <div class="line"></div>
      <div class="name">{{ $order->sales->nama_sales ?? ($order->user->nama ?? 'Admin') }}</div>
    </div>
  </div>

  <div class="footer">
    Terima kasih atas kepercayaan Anda.<br>
    Invoice ini dibuat secara otomatis oleh sistem Inventory PT. Kuda Mas Mandiri.
  </div>

</body>

</html>
