<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lampiran SPD</title>
  <style>
    /* Avoid repetitive header */
    /* thead { display: table-row-group; } */
    #header {
      text-align: center;
    }

    #rincian-spd,
    #rincian-spd th,
    #rincian-spd td {
      border-collapse: collapse;
      border: 1px solid black;
      padding: 4px;
    }

    #rincian-spd tr td:first-child {
      text-align: center;
    }

    #rincian-spd {
      font-size: 14px;
    }

    .text-bold {
      font-weight: bold;
    }

    .spd {
      font-size: 14px;
    }

    #info-spd {
      border-collapse: collapse;
    }

    #info-spd tr td:nth-child(2) {
      padding-left: 8px;
      padding-right: 8px;
    }

    .number {
      text-align: right;
    }

    .content-text {
      font-size: 14px;
    }

    #ttd {
      width: 100%;
    }

    #ttd td {
      text-align: center;
    }

    #ttd tr>td:first-child {
      width: 60%;
    }
  </style>
</head>

<body>
  <div id="header">
    PEMERINTAH KABUPATEN SANGGAU<br />
    PEJABAT PENGELOLA KEUANGAN DAERAH SELAKU BENDAHARA UMUM DAERAH<br />
    NOMOR {no_spd}<br />
    TENTANG<br />
    SURAT PENYEDIAAN DANA ANGGARAN BELANJA DAERAH<br />
    TAHUN ANGGARAN 2021<br />
  </div>
  <br />
  <br />
  <div class="spd">LAMPIRAN SURAT PENYEDIAAN DANA</div>
  <br />
  <table class="spd" id="info-spd">
    <tbody>
      <tr>
        <td>NOMOR SPD </td>
        <td>:</td>
        <td>{no_spd}</td>
      </tr>
      <tr>
        <td>TANGGAL</td>
        <td>:</td>
        <td>{tgl_spd}</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <td>SKPD</td>
        <td>:</td>
        <td>{nm_skpd}</td>
      </tr>
      <tr>
        <td>PERIODE BULAN</td>
        <td>:</td>
        <td>{bulan_awal} s/d {bulan_akhir}</td>
      </tr>
      <tr>
        <td>TAHUN ANGGARAN</td>
        <td>:</td>
        <td>2021</td>
      </tr>
      <tr>
        <td>NOMOR DPA-SKPD</td>
        <td>:</td>
        <td>{no_dpa}</td>
      </tr>
    </tbody>
  </table>
  <br />
  <table id="rincian-spd">
    <thead>
      <tr>
        <th>No.</th>
        <th colspan="2">Kode, dan Nama Program, Kegiatan dan Sub Kegiatan</th>
        <th>ANGGARAN</th>
        <th>AKUMULASI SPD SEBELUMNYA</th>
        <th>JUMLAH SPD PERIODE INI</th>
        <th>SISA ANGGARAN</th>
      </tr>
      <tr>
        <th>1</th>
        <th colspan="2">2</th>
        <th>3</th>
        <th>4</th>
        <th>5</th>
        <th>6 = 3 - 4 - 5</th>
      </tr>
    </thead>
    <tbody>
      <?php $total_anggaran = 0;
      $total_spd = 0;
      $total_spd_lalu = 0; ?>
      <?php foreach ($rincian_spd as $key => $value) : ?>
        <tr>
          <td><?php echo ($key + 1) . '.' ?></td>
          <?php if ($value['jenis'] == 'rekening') : ?>
            <td><?php echo $value['kd_rek'] ?></td>
            <td><?php echo $value['nm_rek'] ?></td>
            <?php $total_anggaran += $value['anggaran'] ?>
            <?php $total_spd += $value['nilai'] ?>
            <?php $total_spd_lalu += $value['nilai_lalu'] ?>
          <?php else : ?>
            <td class="text-bold"><?php echo $value['kode'] ?></td>
            <td class="text-bold"><?php echo $value['nama'] ?></td>
          <?php endif ?>
          <td class="number<?php echo $value['jenis'] == 'rekening' ? '' : ' text-bold' ?>"><?php echo number_format($value['anggaran'], 2, ',', '.') ?></td>
          <td class="number<?php echo $value['jenis'] == 'rekening' ? '' : ' text-bold' ?>"><?php echo number_format($value['nilai_lalu'], 2, ',', '.') ?></td>
          <td class="number<?php echo $value['jenis'] == 'rekening' ? '' : ' text-bold' ?>"><?php echo number_format($value['nilai'], 2, ',', '.') ?></td>
          <td class="number<?php echo $value['jenis'] == 'rekening' ? '' : ' text-bold' ?>"><?php echo number_format($value['anggaran'] - $value['nilai'] - $value['nilai_lalu'], 2, ',', '.') ?></td>
        </tr>
      <?php endforeach ?>
      <tr>
        <td class="text-bold" colspan="3">Jumlah</td>
        <td class="number text-bold"><?php echo number_format($total_anggaran, 2, ',', '.') ?></td>
        <td class="number text-bold"><?php echo number_format($total_spd_lalu, 2, ',', '.') ?></td>
        <td class="number text-bold"><?php echo number_format($total_spd, 2, ',', '.') ?></td>
        <td class="number text-bold"><?php echo number_format($total_anggaran - $total_spd - $total_spd_lalu, 2, ',', '.') ?></td>
      </tr>
    </tbody>
  </table>
  <br /><br />
  <div class="content-text">Jumlah Penyediaan Dana Rp {total_spd}</div>
  <div class="content-text"><i>({total_spd_terbilang})</i></div>
  <br /><br /><br />
  <table id="ttd">
    <tbody>
      <tr>
        <td></td>
        <td>
          <div>Ditetapkan di Sanggau</div>
          <div>Pada tanggal {tgl_spd}</div>
          <br />
          <div>PPKD SELAKU BUD</div>
          <br /><br /><br /><br />
          <div><u>{nm_ppkd}</u></div>
          <div>NIP. {nip_ppkd}</div>
        </td>
      </tr>
    </tbody>
  </table>
</body>

</html>