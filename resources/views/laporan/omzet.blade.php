<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Laporan Omzet Penjualan - {{ $periode }}</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Helvetica', 'Arial', sans-serif;
      font-size: 11px;
      color: #1f2937;
      padding: 30px 40px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 20px;
      border-bottom: 3px solid #111827;
      padding-bottom: 15px;
    }

    .header .logo {
      font-size: 18px;
      font-weight: 800;
      color: #111827;
    }

    .header .info {
      text-align: right;
      font-size: 9px;
      color: #4b5563;
      line-height: 1.6;
    }

    .title {
      text-align: center;
      font-size: 14px;
      font-weight: 700;
      margin: 20px 0 5px;
      letter-spacing: 2px;
      text-transform: uppercase;
    }

    .periode {
      text-align: center;
      font-size: 10px;
      color: #6b7280;
      margin-bottom: 20px;
    }

    .summary {
      display: flex;
      gap: 15px;
      margin-bottom: 25px;
    }

    .summary-card {
      flex: 1;
      border: 2px solid #e5e7eb;
      border-radius: 8px;
      padding: 14px;
      text-align: center;
    }

    .summary-card .label {
      font-size: 9px;
      font-weight: 700;
      color: #6b7280;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
    }

    .summary-card .value {
      font-size: 18px;
      font-weight: 800;
    }

    .summary-card.total {
      border-color: #111827;
      background: #111827;
    }

    .summary-card.total .label {
      color: #9ca3af;
    }

    .summary-card.total .value {
      color: white;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th {
      background: #111827;
      color: white;
      padding: 10px 12px;
      font-size: 9px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      text-align: left;
    }

    td {
      padding: 10px 12px;
      border-bottom: 1px solid #e5e7eb;
      font-size: 10px;
    }

    tr:last-child td {
      border-bottom: 2px solid #111827;
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
      margin-top: 30px;
      display: flex;
      justify-content: space-between;
      align-items: flex-end;
    }

    .footer .meta {
      font-size: 9px;
      color: #9ca3af;
    }
  </style>
</head>

<body>

  <div class="header">
    <div>
      <div class="logo">PT. KUDA MAS MANDIRI</div>
      <div style="font-size: 9px; color: #4b5563; margin-top: 3px;">Sistem Inventory Gudang</div>
    </div>
    <div class="info">
      Jl. A. Yani RT 01, Laburan, Padang Panjang, Kec. Tanta, Kab. Tabalong, Kalsel 71561
      Ruko Putih Hijau | Seberang Kantor SBM / Samping BMC<br>
      Telp: 0511-123456 | Email: kmm@kmm.com
    </div>
  </div>

  <div class="title">Laporan Omzet Penjualan</div>
  <div class="periode">Periode: {{ $periode }}</div>

  <div class="summary">
    <div class="summary-card total">
      <div class="label">Total Omzet</div>
      <div class="value">Rp {{ number_format($omzet, 0, ',', '.') }}</div>
    </div>
    <div class="summary-card">
      <div class="label">Total Order</div>
      <div class="value" style="color:#059669;">{{ number_format($totalOrder) }}</div>
    </div>
    <div class="summary-card">
      <div class="label">Barang Terjual</div>
      <div class="value" style="color:#ea580c;">{{ number_format($totalTerjual) }} <span
          style="font-size:10px;font-weight:500;">unit</span></div>
    </div>
  </div>

  <table>
    <thead>
      <tr>
        <th>Periode</th>
        <th class="text-right">Total Omzet</th>
        <th class="text-right">Total Order</th>
        <th class="text-right">Barang Terjual</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="text-bold">{{ $periode }}</td>
        <td class="text-right text-bold">Rp {{ number_format($omzet, 0, ',', '.') }}</td>
        <td class="text-right">{{ number_format($totalOrder) }}</td>
        <td class="text-right">{{ number_format($totalTerjual) }} unit</td>
      </tr>
    </tbody>
  </table>

  <div class="signature">
    <div>Mengetahui,</div>
    <div class="line"></div>
    <div class="name">{{ $dicetak_oleh }}</div>
  </div>

  <div class="footer">
    <div class="meta">
      Dicetak: {{ $tanggal_cetak }}<br>
      Oleh: {{ $dicetak_oleh }}
    </div>
  </div>

</body>

</html>
