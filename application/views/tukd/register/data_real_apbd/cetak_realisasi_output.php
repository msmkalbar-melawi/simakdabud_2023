<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>
</head>

<body>
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="1" cellpadding="1">
        <tr>
            <td align="center" style="font-size:14px;border: solid 1px white;border-bottom:solid 1px white;" colspan="9">
                <b>LAPORAN REALISASI KELUARAN/OUTPUT PENGGUNAAN APBD</b>
            </td>
        </tr>
        <tr>
            <td align="center" style="font-size:14px;border: solid 1px white;border-bottom:solid 1px white;" colspan="9">
                <b> <?= $kab_kota ?> </b>
            </td>
        </tr>
        <tr>
            <td align="center" style="font-size:14px;border: solid 1px white;border-bottom:solid 1px white;" colspan="9">
                <b>Triwulan <?= $triwulan ?> Tahun <?= $tahun ?></b><br>&nbsp;
            </td>
        </tr>
        <tr>
            <td align="center" height="40" style="border-bottom: solid 1px white;border-top: solid 1px white;border-left: solid 1px white;border-right: solid 1px white;text-align: justify;padding: 5px 30px;">
                Yang bertanda tangan di bawah ini <?= $nama ?> menyatakan bahwa saya bertanggung jawab penuh&nbsp;
                atas kebenaran Laporan Realisasi Keluaran/ Output Penggunaan APBD ini dengan rincian sebagai berikut:
            </td>
        </tr>
    </table>
    <table style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="1" cellpadding="1">
        <thead>
            <tr>
                <td style="font-size:10px" bgcolor="#CCCCCC" align="center" width="6%" rowspan="1">
                    <b>Sumber Dana</b>
                </td>
                <td style="font-size:10px" bgcolor="#CCCCCC" align="center" width="10%" rowspan="1">
                    <b>Program dan Sub Kegiatan</b>
                </td>
                <td style="font-size:10px" bgcolor="#CCCCCC" align="center" width="10%" rowspan="1">
                    <b>Indikator</b>
                </td>
                <td style="font-size:10px" bgcolor="#CCCCCC" align="center" width="10%" rowspan="1">
                    <b>Target Indikator</b>
                </td>
                <td style="font-size:10px" bgcolor="#CCCCCC" align="center" width="10%" rowspan="1">
                    <b>Satuan indikator</b>
                </td>
                <td colspan='5' style="font-size:10px" bgcolor="#CCCCCC" align="center" width="5%" rowspan="1">
                    <b>Realisasi Keluaran/Output</b>
                </td>
            </tr>
            <tr>
                <td style="font-size:10px" align="center"><b>1</b></td>
                <td style="font-size:10px" align="center"><b>2</b></td>
                <td style="font-size:10px" align="center"><b>3</b></td>
                <td style="font-size:10px" align="center"><b>4</b></td>
                <td style="font-size:10px" align="center"><b>5</b></td>
                <td style="font-size:10px" align="center"><b>TW I</b></td>
                <td style="font-size:10px" align="center"><b>TW II</b></td>
                <td style="font-size:10px" align="center"><b>TW III</b></td>
                <td style="font-size:10px" align="center"><b>TW IV</b></td>
            </tr>
        </thead>
        <?php
        $lcno = 0;
        foreach ($hasil->result() as $row) {
            $sumber = $row->sumber;
            $urut = $row->urut;
            $kegiatan = $row->kd;
            $kd_skpd = $row->no_trdrka;
            $bidurusan = $row->nama;
            $indikator = $row->indikator;
            $tw1 = $row->tw1;
            $tw2 = $row->tw2;
            $tw3 = $row->tw3;
            $tw4 = $row->tw4;
            $target_indikator = $row->target_indikator;
            if ($target_indikator == '') {
                $satuan2 = '';
                $satuan3 = '';
            } else {
                $satuan = explode(" ", $target_indikator);
                $satuan2 = $satuan[1];
                $satuan3 = $satuan[0];
            }
            $lcno = $lcno + 1;
        ?>
            <tr>
                <td align="center" style="font-size:12px"><?= $sumber ?></td>
                <?php if ($urut == 3) { ?>
                    <td align="left" style="font-size:12px;font-weight: bold;"><?= $kegiatan ?> <br>-<br><?= $bidurusan ?></td>
                <?php } else {
                ?>
                    <td align="left" style="font-size:12px;">
                        <?= $kegiatan ?> <br>-<br><?= $bidurusan ?>
                        <input type="text" value="<?= $kegiatan ?>" class="kdsubkegiatan" hidden>
                        <input type="text" value="<?= $kd_skpd ?>" class="kdorg" hidden>
                    </td>
                <?php
                }
                ?>
                <td align="left" style="font-size:12px"><?= $indikator ?></td>
                <td align="right" style="font-size:12px"><?= $satuan3 ?></td>
                <td align="right" style="font-size:12px"><?= $satuan2 ?></td>
                <?php if ($urut == 7) { ?>
                    <td align="right" style="font-size:12px"><?= number_format($tw1, "0", ".", ",")  ?></td>
                    <td align="right" style="font-size:12px"><?= number_format($tw2, "0", ".", ",")  ?></td>
                    <td align="right" style="font-size:12px"><?= number_format($tw3, "0", ".", ",")  ?></td>
                    <td align="right" style="font-size:12px"><?= number_format($tw4, "0", ".", ",")  ?></td>
                <?php
                } else {
                ?>
                    <td align="right" style="font-size:12px"></td>
                    <td align="right" style="font-size:12px"></td>
                    <td align="right" style="font-size:12px"></td>
                    <td align="right" style="font-size:12px"></td>
                <?php
                }
                ?>

            </tr>
        <?php
        }
        ?>
    </table>
    <br /><br />
    <div style="margin-left: 30px;">Demikian laporan ini dibuat dengan sebenarnya.</div>
    <table style="border-collapse:collapse; font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
        <TR>
            <TD width="50%" align="center"><b>&nbsp;</TD>
            <TD width="50%" align="center"><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD width="50%" align="center"><b>&nbsp;</TD>
            <TD width="50%" align="center"><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD width="50%" align="center"><b>&nbsp;</TD>
            <TD align="center"><?= $daerah ?>, <?= $tanggal_ttd ?></TD>
        </TR>
        <TR>
            <TD width="50%" align="center"><b>&nbsp;</TD>
            <TD align="center"><?= $jabatan ?></TD>
        </TR>
        <TR>
            <TD width="50%" align="center"><b>&nbsp;</TD>
            <TD width="50%" align="center"><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD width="50%" align="center"><b>&nbsp;</TD>
            <TD width="50%" align="center"><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD width="50%" align="center"><b>&nbsp;</TD>
            <TD width="50%" align="center"><b>&nbsp;</TD>
        </TR>
        <TR>
            <TD width="50%" align="center"><b>&nbsp;</TD>
            <TD align="center"><b><u><?= $nama ?></u></b><br><?= $pangkat ?></TD>
        </TR>
        <TR>
            <TD width="50%" align="center"><b>&nbsp;</TD>
            <TD align="center"><?= $nip ?></TD>
        </TR>
    </table>

</body>

</html>