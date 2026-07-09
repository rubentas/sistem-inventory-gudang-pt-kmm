<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Laporan Retur Penjualan</title>
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

  <div class="title">LAPORAN RETUR PENJUALAN</div>
  <div class="subtitle">Total Retur: {{ $total_retur }} | Tanggal Cetak: {{ $tanggal_cetak }}</div>

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th class="text-left">No Retur</th>
        <th class="text-left">Order</th>
        <th>Tanggal</th>
        <th>Barang</th>
        <th>Jumlah</th>
        <th>Kondisi</th>
        <th>Tujuan</th>
        <th>Alasan</th>
        <th>Diinput Oleh</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($data as $index => $r)
        @php $detail = $r->detailRetur->first(); @endphp
        <tr>
          <td>{{ $index + 1 }}</td>
          <td class="text-left">{{ $r->no_retur }}</td>
          <td class="text-left">{{ $r->order->no_invoice ?? $r->id_order }}</td>
          <td>{{ $r->tanggal_retur->format('d/m/Y') }}</td>
          <td>{{ $detail?->barang?->nama_barang ?? '-' }}</td>
          <td>{{ $detail?->jumlah_retur ?? 0 }}</td>
          <td>{{ $detail?->kondisi_barang ?? '-' }}</td>
          <td>{{ $detail?->tujuan ?? '-' }}</td>
          <td>{{ $detail?->alasan ?? '-' }}</td>
          <td>{{ $r->user->nama ?? 'Admin' }}</td>
          <td>{{ $r->status }}</td>
        </tr>
      @empty
        <tr>
          <td colspan="11">Tidak ada data</td>
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
