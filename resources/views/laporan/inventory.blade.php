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
      font-size: 8px;
      color: #1f2937;
      padding: 20px 30px;
    }

    .header {
      text-align: center;
      margin-bottom: 8px;
      border-bottom: 2px solid #111827;
      padding-bottom: 10px;
    }

    .company {
      font-size: 14px;
      font-weight: 800;
    }

    .address {
      font-size: 7px;
      color: #4b5563;
      margin-top: 2px;
    }

    .contact {
      font-size: 7px;
      color: #6b7280;
    }

    .title {
      text-align: center;
      font-size: 11px;
      font-weight: 700;
      margin: 8px 0;
      letter-spacing: 1px;
    }

    .periode {
      text-align: center;
      font-size: 7px;
      color: #6b7280;
      margin-bottom: 8px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 10px;
    }

    th {
      background: #111827;
      color: white;
      padding: 5px 4px;
      font-size: 7px;
      font-weight: 700;
      text-align: center;
    }

    td {
      padding: 4px;
      border-bottom: 1px solid #d1d5db;
      font-size: 7px;
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

    .bold {
      font-weight: 700;
    }

    .negative {
      font-weight: 700;
    }

    .positive {
      font-weight: 700;
    }

    tfoot tr {
      background: #1f2937 !important;
      color: white;
    }

    tfoot td {
      padding: 6px 4px;
      font-size: 8px;
      font-weight: 700;
    }

    .signature {
      margin-top: 25px;
      text-align: right;
    }

    .signature .line {
      border-top: 1px solid #374151;
      width: 130px;
      display: inline-block;
      margin-top: 35px;
    }

    .signature .name {
      font-size: 9px;
      font-weight: 700;
      margin-top: 4px;
    }

    .footer {
      margin-top: 15px;
      font-size: 7px;
      text-align: center;
      color: #9ca3af;
      border-top: 1px solid #e5e7eb;
      padding-top: 8px;
    }
  </style>
</head>

<body>

  <div class="header">
    <div class="company">PT. KUDA MAS MANDIRI</div>

    <div class="address">
      Jl. A. Yani RT 01, Laburan, Padang Panjang,
      Kec. Tanta, Kab. Tabalong, Kalsel 71561
    </div>

    <div class="address">
      Ruko Putih Hijau | Seberang Kantor SBM / Samping BMC
    </div>

    <div class="contact">
      Telp: 0511-123456 | Email: kmm@kmm.com
    </div>
  </div>

  <div class="title">
    LAPORAN INVENTORY
  </div>

  <div class="periode">
    Periode: {{ $tanggal_awal }} — {{ $tanggal_akhir }}
  </div>

  <table>

    <thead>
      <tr>
        <th>No</th>
        <th class="text-left">Tanggal</th>
        <th class="text-left">Barang</th>
        <th>Awal</th>
        <th>Masuk</th>
        <th>Keluar</th>
        <th>Sistem</th>
        <th>Fisik</th>
        <th>Selisih</th>
        <th>Input Oleh</th>
      </tr>
    </thead>

    <tbody>

      @forelse($stoks as $index => $stok)

        <tr>

          <td>
            {{ $index + 1 }}
          </td>

          <td class="text-left">
            {{ \Carbon\Carbon::parse($stok->tanggal)->format('d/m/Y') }}
          </td>

          <td class="text-left">
            {{ $stok->barang->nama_barang ?? '-' }}
          </td>

          <td class="text-right">
            {{ number_format($stok->stok_awal ?? 0) }}
          </td>

          <td class="text-right">
            +{{ number_format($stok->barang_masuk ?? 0) }}
          </td>

          <td class="text-right">
            -{{ number_format($stok->barang_keluar ?? 0) }}
          </td>

          <td class="text-right bold">
            {{ number_format($stok->stok_sistem ?? 0) }}
          </td>

          <td class="text-right">
            {{ number_format($stok->stok_fisik ?? 0) }}
          </td>

          <td class="text-right bold">
            {{ $stok->selisih > 0 ? '+' : '' }}{{ number_format($stok->selisih ?? 0) }}
          </td>

          <td>
            {{ $stok->user->nama ?? '-' }}
          </td>

        </tr>

      @empty

        <tr>
          <td colspan="10">
            Tidak ada data inventory
          </td>
        </tr>

      @endforelse

    </tbody>

    <tfoot>

      <tr>

        <td colspan="3" class="text-left">
          TOTAL
        </td>

        <td class="text-right">
          -
        </td>

        <td class="text-right">
          +{{ number_format($total_masuk_keseluruhan ?? 0) }}
        </td>

        <td class="text-right">
          -{{ number_format($total_keluar_keseluruhan ?? 0) }}
        </td>

        <td class="text-right">
          {{ number_format($total_stok_akhir ?? 0) }}
        </td>

        <td class="text-right">
          -
        </td>

        <td class="text-right">
          -
        </td>

        <td></td>

      </tr>

    </tfoot>

  </table>

  <div class="signature">
    <div>Mengetahui,</div>
    <div class="line"></div>

    <div class="name">
      {{ $dicetak_oleh }}
    </div>
  </div>

  <div class="footer">
    Dicetak oleh: {{ $dicetak_oleh }} |
    {{ $tanggal_cetak }}
  </div>

</body>

</html>