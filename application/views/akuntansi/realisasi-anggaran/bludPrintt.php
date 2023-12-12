<table style="border-collapse:collapse;font-size:12px;font-family:Bookman Old Style" width="100%" align="center" border="1" cellspacing="0" cellpadding="0">
    <tr>
        <td style="border-bottom:hidden;"rowspan="4" align="center" width="10%" >
            <img src="<?= site_url() ?>/image/logoHP.png" width="60" height="70" />
        </td>
        <td  style="border-left:hidden;border-bottom:hidden;" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><?= $klien->kab_kota ?></strong></td>
</tr>
<tr>
        <td  style="border-left:hidden;border-bottom:hidden;" align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong>LAPORAN REALISASI BLUD <?= strtoupper($skpd->nm_skpd) ?></strong></td>
</tr>
        <td style="border-left:hidden;border-bottom:hidden;" align="center">
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <strong><?php
                    if ($jenis === 'bulan') {

                        echo $bulan;
                    } else {
                        echo "PERIODE " . strtoupper($tanggalAwal) . " S.D " . strtoupper($tanggalAkhir);
                    }

                    ?>

                    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TAHUN ANGGARAN 2023
            </strong>
        </td>
    </tr>
    <!-- </tr>
    <tr>
        
    </tr>
    <tr>
        
    </tr> -->
    <!-- <tr>
        <td align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
    </tr> -->
</table>

<table style="border-collapse:collapse; border-color: black; font-family:Arial;font-size:11px" width="100%" align="center" border="1" cellspacing="1" cellpadding="1">
<!-- <table style="border-collapse:collapse;font-family:Arial;font-size:11px" width="100%" align="center" border="1" cellspacing="3" cellpadding="3"> -->
    <thead>
    <tr>
						<td rowspan="2" width="7%" align="center" bgcolor="#CCCCCC" ><b>KD REK</b></td>
						<td rowspan="2" width="32%" align="center" bgcolor="#CCCCCC" ><b>URAIAN</b></td>
						<td colspan="2" width="37%" align="center" bgcolor="#CCCCCC" ><b>JUMLAH (Rp.)</b></td>
						<td colspan="2" width="23%" align="center" bgcolor="#CCCCCC" ><b>BERTAMBAH/KURANG</b></td>
					</tr>

                    <tr>
						<td width="19%" align="center" bgcolor="#CCCCCC" ><b>ANGGARAN</b></td>
						<td width="18%" align="center" bgcolor="#CCCCCC" ><b>REALISASI</b></td>
						<td width="18%" align="center" bgcolor="#CCCCCC" ><b>(Rp)</b></td>
						<td width="5%" align="center" bgcolor="#CCCCCC" ><b>%</b></td>
						</tr>
					<!-- <tr>
        <tr>
         
            <td align="center" width="10%" style="font-size:12px;font-weight:bold;">Kode Rekening</td>
            <td align="center" width="28%" style="font-size:12px;font-weight:bold">Nama Rekening</td>
            <td align="center" width="12%" style="font-size:12px;font-weight:bold">Jumlah Anggaran(Rp)</td>
            <td align="center" width="12%" style="font-size:12px;font-weight:bold">Jumlah Realisasi(Rp)</td>
            <td align="center" width="12%" style="font-size:12px;font-weight:bold">Lebih(Kurang)</td>
            <td align="center" width="5%" style="font-size:12px;font-weight:bold">%</td>
        </tr> -->
        <tr>
            <td align="center" bgcolor="#CCCCCC"  style="font-size:12px;border-top:solid 1px black">1</td>
            <td align="center" bgcolor="#CCCCCC" style="font-size:12px;border-top:solid 1px black">2</td>
            <td align="center" bgcolor="#CCCCCC" style="font-size:12px;border-top:solid 1px black">3</td>
            <td align="center" bgcolor="#CCCCCC" style="font-size:12px;border-top:solid 1px black">4</td>
            <td align="center" bgcolor="#CCCCCC" style="font-size:12px;border-top:solid 1px black">5</td>
            <td align="center" bgcolor="#CCCCCC" style="font-size:12px;border-top:solid 1px black">6</td>
        </tr>
    </thead>
    <tbody>
        <!-- Loop Pendapatan -->
        <?php
        $totalAnggaranPedapatan = 0;
        $totalRealisasiPendapatan = 0;
        //$kodeSubKegiatanPendapatan = '';
        foreach ($pendapatanBlud as $pendapatan) {
            $totalAnggaranPedapatan += $pendapatan->anggaran;
            $totalRealisasiPendapatan += $pendapatan->realisasi;
        ?>
            <tr>
                <td align="center" style="font-size:11px;border-top:solid 1px black">
                    <?= $pendapatan->kd_rek6 ?>
                </td>
                <td align="left" style="font-size:11px;border-top:solid 1px black; padding-left: 10px">
                    <?= $pendapatan->nm_rek6 ?>
                </td>
                <td align="center" style="font-size:11px;border-top:solid 1px black">
                    <?= idr($pendapatan->anggaran) ?>
                </td>
                <td align="center" style="font-size:11px;border-top:solid 1px black">
                    <?= idr($pendapatan->realisasi) ?>
                </td>
                <td align="center" style="font-size:11px;border-top:solid 1px black">
                    <?= idr(abs($pendapatan->realisasi - $pendapatan->anggaran))   ?>

                </td>
                <td align="center" style="font-size:11px;border-top:solid 1px black">
                    <?php
                    echo number_format(($pendapatan->realisasi / $pendapatan->anggaran), 4, '.', ',') * 100
                    ?>
                </td>
            </tr>
        <?php
        }
        ?>
        <!-- total Pendapatan -->
        <tr>
            <td align="center" style="font-size:11px;border-top:solid 1px black;" colspan="2">
                <strong>TOTAL PENDAPATAN BLUD</strong>
            </td>
            <td align="center" style="font-size:11px;border-top:solid 1px black">
                <strong><?= idr($totalAnggaranPedapatan) ?></strong>
            </td>
            <td align="center" style="font-size:11px;border-top:solid 1px black">
                <strong> <?= idr($totalRealisasiPendapatan) ?></strong>
            </td>
            <td align="center" style="font-size:11px;border-top:solid 1px black">
                <strong> <?= idr(abs($totalRealisasiPendapatan - $totalAnggaranPedapatan))   ?></strong>

            </td>
            <td align="center" style="font-size:11px;border-top:solid 1px black">
                <strong> <?php
                            echo number_format(($totalRealisasiPendapatan / $totalAnggaranPedapatan), 4, '.', ',') * 100
                            ?></strong>
            </td>
        </tr>

        <!-- Loop Belanja -->
        <?php
        $totalAnggaranBelanja = 0;
        $totalRealisasiBelanja = 0;
        //$kodeSubKegiatanBelanja = '';
        foreach ($belanjaBlud as $belanja) {
            $totalAnggaranBelanja += $belanja->anggaran;
            $totalRealisasiBelanja += $belanja->realisasi;
        ?>
            <tr>
                <td align="center" style="font-size:11px;border-top:solid 1px black">
                    <?= $belanja->kd_rek6 ?>
                </td>
                <td align="left" style="font-size:11px;border-top:solid 1px black; padding-left: 10px">
                    <?= $belanja->nm_rek6 ?>
                </td>
                <td align="center" style="font-size:11px;border-top:solid 1px black">
                    <?= idr($belanja->anggaran) ?>
                </td>
                <td align="center" style="font-size:11px;border-top:solid 1px black">
                    <?= idr($belanja->realisasi) ?>
                </td>
                <td align="center" style="font-size:11px;border-top:solid 1px black">
                    <?= idr(abs($belanja->realisasi - $belanja->anggaran))   ?>

                </td>
                <td align="center" style="font-size:11px;border-top:solid 1px black">
                    <?=
                    number_format($belanja->realisasi / $belanja->anggaran * 100, 2, '.', ',')
                    ?>
                </td>
            </tr>
        <?php
        }
        ?>

        <!-- total Belanja -->
        <tr>
            <td align="center" style="font-size:11px;border-top:solid 1px black;" colspan="2">
                <strong>TOTAL BELANJA BLUD</strong>
            </td>
            <td align="center" style="font-size:11px;border-top:solid 1px black">
                <strong><?= idr($totalAnggaranBelanja) ?></strong>
            </td>
            <td align="center" style="font-size:11px;border-top:solid 1px black">
                <strong> <?= idr($totalRealisasiBelanja) ?></strong>
            </td>
            <td align="center" style="font-size:12px;border-top:solid 1px black">
                <strong> <?= idr(abs($totalAnggaranBelanja - $totalRealisasiBelanja))   ?></strong>

            </td>
            <td align="center" style="font-size:11px;border-top:solid 1px black">
                <strong>
                    <?= number_format($totalRealisasiBelanja / $totalAnggaranBelanja * 100, 2, '.', ',') ?>
                </strong>
            </td>
        </tr>

    </tbody>
</table>
    