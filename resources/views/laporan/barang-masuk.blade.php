<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Laporan Barang Masuk</title>
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
      letter-spacing: 0.5px;
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
      margin: 10px 0 5px;
      letter-spacing: 1px;
      text-transform: uppercase;
      color: #111827;
    }

    .subtitle {
      text-align: center;
      font-size: 8px;
      color: #6b7280;
      margin-bottom: 5px;
    }

    .info-box {
      margin-bottom: 12px;
      padding: 8px 12px;
      background: #f3f4f6;
      border-radius: 4px;
      display: flex;
      justify-content: space-between;
      font-size: 8px;
    }

    .info-box .label {
      color: #6b7280;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .info-box .value {
      color: #111827;
      font-weight: 700;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 12px;
    }

    thead {
      display: table-header-group;
    }

    th {
      background: #111827;
      color: white;
      padding: 7px 5px;
      font-size: 7.5px;
      font-weight: 700;
      text-transform: uppercase;
      text-align: center;
      letter-spacing: 0.5px;
      border-bottom: 2px solid #000;
    }

    td {
      padding: 5px 5px;
      border-bottom: 0.5px solid #d1d5db;
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

    .text-xs {
      font-size: 7px;
      color: #6b7280;
    }

    tfoot tr {
      background: #1f2937 !important;
      color: white;
    }

    tfoot td {
      padding: 8px 5px;
      font-size: 9px;
      font-weight: 700;
      border-bottom: none;
    }

    .footer {
      margin-top: 20px;
      font-size: 7.5px;
      text-align: right;
      color: #9ca3af;
      border-top: 1px solid #e5e7eb;
      padding-top: 10px;
    }

    .footer .signature {
      margin-top: 30px;
      text-align: right;
    }

    .footer .signature-line {
      margin-top: 40px;
      border-top: 1px solid #374151;
      width: 150px;
      display: inline-block;
    }

    .page-number {
      text-align: center;
      font-size: 7px;
      color: #9ca3af;
      margin-top: 5px;
    }

    @media print {
      body {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
      }
    }
  </style>
</head>

<body>

  <div class="header">
    <div class="company">PT. KUDA MAS MANDIRI</div>
    <div class="address">Jl. Tanjung Tabalong, Kalimantan Selatan</div>
    <div class="contact">Telp: 0511-123456 | Email: kmm@kmm.com</div>
  </div>

  <div class="title">LAPORAN BARANG MASUK</div>

  <div class="info-box">
    <div>
      <span class="label">Periode:</span>
      <span class="value">{{ $tanggal_awal }} — {{ $tanggal_akhir }}</span>
    </div>
    <div>
      <span class="label">Total Data:</span>
      <span class="value">{{ $data->count() }} transaksi</span>
    </div>
    <div>
      <span class="label">Total Jumlah:</span>
      <span class="value">{{ number_format($total_jumlah) }} unit</span>
    </div>
    @if ($nama_supplier)
      <div>
        <span class="label">Supplier:</span>
        <span class="value">{{ $nama_supplier }}</span>
      </div>
    @endif
  </div>

  <table>
    <thead>
      <tr>
        <th style="width: 4%;">NO</th>
        <th class="text-left" style="width: 10%;">TANGGAL</th>
        <th class="text-left" style="width: 12%;">NO. NOTA</th>
        <th class="text-left" style="width: 12%;">SURAT JALAN</th>
        <th class="text-left" style="width: 20%;">NAMA BARANG</th>
        <th class="text-right" style="width: 8%;">JUMLAH</th>
        <th class="text-left" style="width: 14%;">SUPPLIER</th>
        <th class="text-left" style="width: 10%;">SUMBER</th>
        <th class="text-left" style="width: 10%;">INPUT OLEH</th>
      </tr>
    </thead>
    <tbody>
      @forelse($data as $index => $item)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td class="text-left">{{ $item->tanggal_masuk->translatedFormat('d/m/Y') }}</td>
          <td class="text-left">
            <span style="font-family: 'Courier New', monospace; font-size: 7.5px;">{{ $item->no_nota }}</span>
          </td>
          <td class="text-left">
            <span style="font-size: 7.5px;">{{ $item->no_surat_jalan ?: '-' }}</span>
          </td>
          <td class="text-left">
            <div style="font-weight: 600;">{{ $item->barang->nama_barang ?? '-' }}</div>
            <div class="text-xs">{{ $item->barang->kode_barang ?? '' }}</div>
          </td>
          <td class="text-right">
            <span style="font-weight: 700;">{{ number_format($item->jumlah) }}</span>
          </td>
          <td class="text-left">{{ $item->supplier->nama_supplier ?? '-' }}</td>
          <td class="text-left">
            @if ($item->sumber === 'Supplier')
              <span
                style="background: #dbeafe; color: #1e40af; padding: 2px 6px; border-radius: 3px; font-size: 7px; font-weight: 600;">SUPPLIER</span>
            @else
              {{ $item->sumber }}
            @endif
          </td>
          <td class="text-left">{{ $item->user->nama ?? 'System' }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="9" style="padding: 30px; text-align: center; color: #9ca3af; font-style: italic;">
            Tidak ada data barang masuk pada periode ini
          </td>
        </tr>
      @endforelse
    </tbody>
    @if ($data->count() > 0)
      <tfoot>
        <tr>
          <td colspan="5" class="text-left">TOTAL</td>
          <td class="text-right">{{ number_format($total_jumlah) }}</td>
          <td colspan="3"></td>
        </tr>
      </tfoot>
    @endif
  </table>

  <div class="footer">
    <div>Dicetak oleh: {{ $dicetak_oleh }} | {{ $tanggal_cetak }}</div>
    <div class="signature">
      <div>Mengetahui,</div>
      <div class="signature-line"></div>
      <div style="margin-top: 5px; font-weight: 600; color: #374151;">{{ $dicetak_oleh }}</div>
    </div>
  </div>

</body>

</html>
