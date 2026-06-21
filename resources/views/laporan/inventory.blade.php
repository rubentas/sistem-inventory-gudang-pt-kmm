<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Laporan Inventory - {{ $tanggal_awal }} s/d {{ $tanggal_akhir }}</title>
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
      letter-spacing: 0.5px;
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

    .periode {
      text-align: center;
      font-size: 9px;
      color: #4b5563;
      margin-bottom: 14px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 14px;
    }

    th {
      background: #111827;
      color: white;
      padding: 7px 8px;
      font-size: 9px;
      font-weight: 700;
      text-transform: uppercase;
      text-align: center;
    }

    td {
      padding: 6px 8px;
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

    .summary {
      margin-top: 8px;
      font-size: 10px;
    }

    .summary table {
      width: 50%;
      margin-left: auto;
    }

    .summary td {
      border: none;
      padding: 3px 6px;
    }

    .footer {
      margin-top: 25px;
      font-size: 9px;
    }

    .footer .signature {
      float: right;
      text-align: center;
      width: 180px;
    }

    .footer .signature .line {
      margin-top: 50px;
      border-top: 1px solid #111827;
      padding-top: 4px;
    }

    .footer .printed {
      clear: both;
      text-align: center;
      color: #9ca3af;
      font-size: 8px;
      padding-top: 15px;
      border-top: 1px solid #e5e7eb;
      margin-top: 20px;
    }
  </style>
</head>

<body>

  {{-- HEADER --}}
  <div class="header">
    <div class="company">PT. KUDA MAS MANDIRI</div>
    <div class="address">Jl. A. Yani RT 01, Laburan, Padang Panjang, Kec. Tanta, Kab. Tabalong, Kalsel 71561
      Ruko Putih Hijau | Seberang Kantor SBM / Samping BMC</div>
    <div class="contact">Telp: 0511-123456 | Email: kmm@kmm.com</div>
  </div>

  <div class="title">LAPORAN INVENTORY BARANG</div>
  <div class="periode">Periode: {{ $tanggal_awal }} — {{ $tanggal_akhir }}</div>

  {{-- TABLE --}}
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th class="text-left">Kode Barang</th>
        <th class="text-left">Nama Barang</th>
        <th>Satuan</th>
        <th class="text-right">Stok Awal</th>
        <th class="text-right">Barang Masuk</th>
        <th class="text-right">Barang Keluar</th>
        <th class="text-right">Stok Akhir</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @php
        $totalMasuk = 0;
        $totalKeluar = 0;
        $totalAkhir = 0;
      @endphp
      @forelse($stoks as $index => $stok)
        @php
          $stokAwal = $stok->jumlah_stok - $stok->total_masuk + $stok->total_keluar;
          $totalMasuk += $stok->total_masuk;
          $totalKeluar += $stok->total_keluar;
          $totalAkhir += $stok->jumlah_stok;
        @endphp
        <tr>
          <td>{{ $index + 1 }}</td>
          <td class="text-left">{{ $stok->barang->kode_barang ?? '-' }}</td>
          <td class="text-left">{{ $stok->barang->nama_barang ?? '-' }}</td>
          <td>{{ $stok->barang->satuan ?? 'Pcs' }}</td>
          <td class="text-right">{{ number_format($stokAwal) }}</td>
          <td class="text-right">{{ number_format($stok->total_masuk) }}</td>
          <td class="text-right">{{ number_format($stok->total_keluar) }}</td>
          <td class="text-right text-bold">{{ number_format($stok->jumlah_stok) }}</td>
          <td>
            @if ($stok->status == 'Menipis')
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
    <tfoot>
      <tr style="background: #111827; color: white;">
        <td colspan="4" class="text-left text-bold">TOTAL</td>
        <td class="text-right text-bold">-</td>
        <td class="text-right text-bold">{{ number_format($totalMasuk) }}</td>
        <td class="text-right text-bold">{{ number_format($totalKeluar) }}</td>
        <td class="text-right text-bold">{{ number_format($totalAkhir) }}</td>
        <td></td>
      </tr>
    </tfoot>
  </table>

  {{-- SUMMARY --}}
  <div class="summary">
    <table>
      <tr>
        <td>Total Barang Masuk</td>
        <td class="text-right text-bold">{{ number_format($total_masuk_keseluruhan) }} unit</td>
      </tr>
      <tr>
        <td>Total Barang Keluar</td>
        <td class="text-right text-bold">{{ number_format($total_keluar_keseluruhan) }} unit</td>
      </tr>
      <tr>
        <td>Total Stok Akhir</td>
        <td class="text-right text-bold">{{ number_format($total_stok_akhir) }} unit</td>
      </tr>
    </table>
  </div>

  {{-- FOOTER --}}
  <div class="footer">
    <div class="signature">
      <div>Tanjung Tabalong, {{ $tanggal_cetak }}</div>
      <div class="line">Kepala Gudang</div>
    </div>
    <div class="printed">
      Dicetak oleh: {{ $dicetak_oleh }} | {{ $tanggal_cetak }}
    </div>
  </div>

</body>

</html>
