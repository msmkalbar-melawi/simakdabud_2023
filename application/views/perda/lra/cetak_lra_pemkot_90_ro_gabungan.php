<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $judul ?></title>

    <style>
        .bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <table style="border-collapse:collapse;font-size:12px;font-family:Bookman Old Style" width="570%" border="1" cellspacing="0" cellpadding="1" align="center">
        <tr>
            <td rowspan="4" align="center" style="border-right:hidden">
                <img src="<?= base_url() ?>/image/logoHP.png" width="50" height="50" />
            </td>
            <td align="center" style="border-left:hidden;border-bottom:hidden">
                <strong><?= $sclient->kab_kota ?></strong>
            </td>
        </tr>
        <tr>
            <td align="center" style="border-left:hidden;border-bottom:hidden;border-top:hidden">
                <b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA</b>
            </td>
        </tr>
        <tr>
            <td align="center" style="border-left:hidden;border-top:hidden">
                <b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN LRA RINCIAN OBJEK TAHUN <?= $lntahunang ?></b>
            </td>
        </tr>
        <tr>
            <td align="center" style="border-left:hidden;border-top:hidden">
                <b><?= $label ?></b>
            </td>
        </tr>
    </table>
    <table style="border-collapse:collapse;font-family:Arial;font-size:11px" width="570%" align="center" border="1" cellspacing="3" cellpadding="3">
        <thead>
            <tr>
                <td rowspan="2" width="7%" align="center" bgcolor="#CCCCCC"><b>KD REK</b></td>
                <td rowspan="2" width="32%" align="center" bgcolor="#CCCCCC"><b>URAIAN</b></td>
                <td colspan="2" width="37%" align="center" bgcolor="#CCCCCC"><b>PEMKOT</b></td>
                <?php
                if ($organisasis) {
                    foreach ($organisasis as $key => $value) {
                ?>
                        <td colspan="2" width="37%" align="center" bgcolor="#CCCCCC"><?= $value->nm_org ?></td>
                <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <td width="19%" align="center" bgcolor="#CCCCCC"><b>ANGGARAN</b></td>
                <td width="18%" align="center" bgcolor="#CCCCCC"><b>REALISASI</b></td>
                <?php
                if ($organisasis) {
                    foreach ($organisasis as $key => $value) {
                ?>
                        <td width="19%" align="center" bgcolor="#CCCCCC"><b>ANGGARAN</b></td>
                        <td width="18%" align="center" bgcolor="#CCCCCC"><b>REALISASI</b></td>
                <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <?php
                $no = 4;
                for ($i = 1; $i <= $no; $i++) {
                ?>
                    <td align="center" bgcolor="#CCCCCC"><?= $i ?></td>
                    <?php
                }
                if ($organisasis) {
                    foreach ($organisasis as $key1 => $value1) {
                    ?>
                        <td align="center" bgcolor="#CCCCCC"><?= $i++ ?></td>
                        <td align="center" bgcolor="#CCCCCC"><?= $i++ ?></td>
                <?php
                    }
                }
                ?>
            </tr>
        </thead>
        <!-- ======================================================================= Rekening Pendapatan -->
        <section class="pendapatan">
            <?php
            $no_urut = '';
            if ($list_rekening4) {
                $angg_rek4 = 0;
                $real_rek4 = 0;
                $sum_nm_rek = "";
                foreach ($list_rekening4 as $key => $value) {
                    // $no_urut = $kd_rek;
                    $class = "";
                    $kd_rek = $value->kd_rek;
                    $lrek = strlen($kd_rek);
                    if ($lrek == $lrek1) {
                        $angg_rek4 = $value->angg;
                        $real_rek4 = $value->real;
                        $sum_nm_rek = $value->nm_rek;
                        $class = "bold";
                    }

                    if ($lrek == $lrek2 || $lrek == $lrek3 || $lrek == $lrek4) {
                        $class = "bold";
                    }
            ?>
                    <tr>
                        <td class="<?= $class ?>"><?= $kd_rek ?></td>
                        <td class="<?= $class ?>"><?= $value->nm_rek ?></td>
                        <td class="<?= $class ?>" align="right"><?= number_format($value->angg, '2', ',', '.') ?></td>
                        <td class="<?= $class ?>" align="right"><?= number_format($value->real, '2', ',', '.') ?></td>
                        <?php
                        if ($organisasis) {
                            foreach ($organisasis as $key1 => $value1) {
                                $column_angg = $value1->kd_org;
                                $column_real = 'real_' . $value1->kd_org;
                                if (strlen($kd_rek) == 1) {
                                    ${"sum_angg4_" . $value1->kd_org} = $value->$column_angg;
                                    ${"sum_real4_" . $value1->kd_org}  = $value->$column_real;
                                }
                        ?>
                                <td class="<?= $class ?>" align="right"><?= number_format($value->$column_angg, '2', ',', '.');  ?></td>
                                <td class="<?= $class ?>" align="right"><?= number_format($value->$column_real, '2', ',', '.') ?></td>
                        <?php
                            }
                        }
                        ?>
                    </tr>
            <?php
                }
            }
            ?>
            <tr>
                <?php
                for ($i = 1; $i <= $no; $i++) {
                ?>
                    <td>&nbsp;</td>
                    <?php
                }
                if ($organisasis) {
                    foreach ($organisasis as $key1 => $value1) {
                    ?>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <td>
                    <?php
                    // $no_urut = $no_urut + 1;
                    // echo $no_urut;
                    ?>
                </td>
                <td class="bold">JUMLAH <?= $sum_nm_rek; ?></td>
                <td align="right" class="bold"><?= number_format($angg_rek4, '2', ',', '.');  ?></td>
                <td align="right" class="bold"><?= number_format($real_rek4, '2', ',', '.');  ?></td>
                <?php
                if ($organisasis) {
                    foreach ($organisasis as $key1 => $value1) {
                ?>
                        <td align="right" class="bold"><?= number_format(${"sum_angg4_" . $value1->kd_org}, '2', ',', '.'); ?></td>
                        <td align="right" class="bold"><?= number_format(${"sum_real4_" . $value1->kd_org}, '2', ',', '.'); ?></td>
                <?php
                    }
                }
                ?>
            </tr>
        </section>
        <!-- ======================================================================= End Rekening Pendapatan -->
        <tr>
            <?php
            for ($i = 1; $i <= $no; $i++) {
            ?>
                <td>&nbsp;</td>
                <?php
            }
            if ($organisasis) {
                foreach ($organisasis as $key1 => $value1) {
                ?>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
            <?php
                }
            }
            ?>
        </tr>
        <!-- ======================================================================= Rekening Belanja -->
        <section class="belanja">
            <?php
            if ($list_rekening5) {
                $angg_rek5 = 0;
                $real_rek5 = 0;
                $sum_nm_rek = "";
                foreach ($list_rekening5 as $key => $value) {
                    $no_urut = $kd_rek;
                    $class = "";
                    $kd_rek = $value->kd_rek;
                    $lrek = strlen($kd_rek);
                    if ($lrek == $lrek1) {
                        $angg_rek5 = $value->angg;
                        $real_rek5 = $value->real;
                        $sum_nm_rek = $value->nm_rek;
                        $class = "bold";
                    }

                    if ($lrek == $lrek2 || $lrek == $lrek3 || $lrek == $lrek4) {
                        $class = "bold";
                    }
            ?>
                    <tr>
                        <td class="<?= $class ?>"><?= $kd_rek ?></td>
                        <td class="<?= $class ?>"><?= $value->nm_rek ?></td>
                        <td class="<?= $class ?>" align="right"><?= number_format($value->angg, '2', ',', '.') ?></td>
                        <td class="<?= $class ?>" align="right"><?= number_format($value->real, '2', ',', '.') ?></td>
                        <?php
                        if ($organisasis) {
                            foreach ($organisasis as $key1 => $value1) {
                                $column_angg = $value1->kd_org;
                                $column_real = 'real_' . $value1->kd_org;
                                if (strlen($kd_rek) == 1) {
                                    ${"sum_angg5_" . $value1->kd_org} = $value->$column_angg;
                                    ${"sum_real5_" . $value1->kd_org}  = $value->$column_real;
                                }
                        ?>
                                <td class="<?= $class ?>" align="right"><?= number_format($value->$column_angg, '2', ',', '.');  ?></td>
                                <td class="<?= $class ?>" align="right"><?= number_format($value->$column_real, '2', ',', '.') ?></td>
                        <?php
                            }
                        }
                        ?>
                    </tr>
            <?php
                }
            }
            ?>
            <tr>
                <?php
                for ($i = 1; $i <= $no; $i++) {
                ?>
                    <td>&nbsp;</td>
                    <?php
                }
                if ($organisasis) {
                    foreach ($organisasis as $key1 => $value1) {
                    ?>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <td>
                    <?php
                    // $no_urut = $no_urut + 1;
                    // echo $no_urut;
                    ?>
                </td>
                <td class="bold">JUMLAH <?= $sum_nm_rek; ?></td>
                <td align="right" class="bold"><?= number_format($angg_rek5, '2', ',', '.');  ?></td>
                <td align="right" class="bold"><?= number_format($real_rek5, '2', ',', '.');  ?></td>
                <?php
                if ($organisasis) {
                    foreach ($organisasis as $key1 => $value1) {
                ?>
                        <td align="right" class="bold"><?= number_format(${"sum_angg5_" . $value1->kd_org}, '2', ',', '.'); ?></td>
                        <td align="right" class="bold"><?= number_format(${"sum_real5_" . $value1->kd_org}, '2', ',', '.'); ?></td>
                <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <?php
                for ($i = 1; $i <= $no; $i++) {
                ?>
                    <td>&nbsp;</td>
                    <?php
                }
                if ($organisasis) {
                    foreach ($organisasis as $key1 => $value1) {
                    ?>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <td>
                    <?php
                    // $no_urut = $no_urut + 1;
                    // echo $no_urut;
                    ?>
                </td>
                <td class="bold">SURPLUS/(DEFISIT)</td>
                <td align="right" class="bold">
                    <?php
                    $surplus_angg = $angg_rek4 - $angg_rek5;
                    echo $this->custom->rp_minus($surplus_angg);
                    ?>
                </td>
                <td align="right" class="bold">
                    <?php
                    $surplus_real = $real_rek4 - $real_rek5;
                    echo $this->custom->rp_minus($surplus_real);
                    ?>
                </td>
                <?php
                if ($organisasis) {
                    foreach ($organisasis as $key1 => $value1) {
                ?>
                        <td align="right" class="bold">
                            <?php
                            ${"surplus_angg_" . $value1->kd_org} = ${"sum_angg4_" . $value1->kd_org} - ${"sum_angg5_" . $value1->kd_org};
                            echo $this->custom->rp_minus(${"surplus_angg_" . $value1->kd_org});
                            ?>
                        </td>
                        <td align="right" class="bold">
                            <?php
                            ${"surplus_real_" . $value1->kd_org} = ${"sum_real4_" . $value1->kd_org} - ${"sum_real5_" . $value1->kd_org};
                            echo $this->custom->rp_minus(${"surplus_real_" . $value1->kd_org});
                            ?>
                        </td>
                <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <?php
                for ($i = 1; $i <= $no; $i++) {
                ?>
                    <td>&nbsp;</td>
                    <?php
                }
                if ($organisasis) {
                    foreach ($organisasis as $key1 => $value1) {
                    ?>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                <?php
                    }
                }
                ?>
            </tr>
        </section>
        <!-- ======================================================================= End Rekening Belanja -->
        <!-- ======================================================================= Rekening Pembiayaan -->
        <section class="pembiayaan">
            <?php
            if ($list_rekening6) {
                $angg_rek6 = 0;
                $real_rek6 = 0;
                $angg_rek6_old = 0;
                $real_rek6_old = 0;
                $old_rek = "";
                $sum_nm_rek = "";
                $old_sum_nm_rek = "";
                $j = 1;
                foreach ($list_rekening6 as $key => $value) {
                    $class = "";
                    $kd_rek = $value->kd_rek;
                    $lrek = strlen($kd_rek);
                    if ($lrek == $lrek2) {
                        $angg_rek6 = $value->angg;
                        $real_rek6 = $value->real;
                        $sum_nm_rek = $value->nm_rek;
                        if ($key == 1) {
                            $old_rek = $kd_rek;
                            $angg_rek6_old = $value->angg;
                            $real_rek6_old = $value->real;
                            $old_sum_nm_rek = $value->nm_rek;
                        }
                    }

                    if ($lrek == $lrek1 || $lrek == $lrek2 || $lrek == $lrek3 || $lrek == $lrek4) {
                        $class = "bold";
                    }

                    if ($lrek == $lrek2) {
                        if ($old_rek != $kd_rek) {
            ?>
                            <tr>
                                <?php
                                for ($i = 1; $i <= $no; $i++) {
                                ?>
                                    <td>&nbsp;</td>
                                    <?php
                                }
                                if ($organisasis) {
                                    foreach ($organisasis as $key1 => $value1) {
                                    ?>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                <?php
                                    }
                                }
                                ?>
                            </tr>
                            <tr>
                                <td>
                                    <?php
                                    // $no_urut = $kd_rek;
                                    // echo $no_urut;
                                    ?>
                                </td>
                                <td class="bold">JUMLAH <?= $old_sum_nm_rek ?></td>
                                <td align="right" class="bold"><?= number_format($angg_rek6_old, '2', ',', '.');  ?></td>
                                <td align="right" class="bold"><?= number_format($real_rek6_old, '2', ',', '.');  ?></td>
                                <?php
                                if ($organisasis) {
                                    foreach ($organisasis as $key1 => $value1) {
                                ?>
                                        <td align="right" class="bold"><?= number_format(${"old_sum_angg_pend_biaya" . $value1->kd_org}, '2', ',', '.');  ?></td>
                                        <td align="right" class="bold"><?= number_format(${"old_sum_real_pend_biaya" . $value1->kd_org}, '2', ',', '.');  ?></td>
                                <?php
                                    }
                                }
                                ?>
                            </tr>
                            <tr>
                                <?php
                                for ($i = 1; $i <= $no; $i++) {
                                ?>
                                    <td>&nbsp;</td>
                                    <?php
                                }
                                if ($organisasis) {
                                    foreach ($organisasis as $key1 => $value1) {
                                    ?>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                <?php
                                    }
                                }
                                ?>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                    <tr>
                        <td class="<?= $class ?>">
                            <?php
                            $no_urut = $kd_rek;
                            echo $no_urut;
                            ?>
                        </td>
                        <td class="<?= $class ?>"><?= $value->nm_rek ?></td>
                        <td class="<?= $class ?>" align="right"><?= number_format($value->angg, '2', ',', '.') ?></td>
                        <td class="<?= $class ?>" align="right"><?= number_format($value->real, '2', ',', '.') ?></td>
                        <?php
                        if ($organisasis) {
                            foreach ($organisasis as $key1 => $value1) {
                                $column_angg = $value1->kd_org;
                                $column_real = 'real_' . $value1->kd_org;
                                if (strlen($kd_rek) == $lrek2) {
                                    ${"sum_angg_pend_biaya" . $value1->kd_org} = $value->$column_angg;
                                    ${"sum_real_pend_biaya" . $value1->kd_org}  = $value->$column_real;
                                    if ($key == 1) {
                                        ${"old_sum_angg_pend_biaya" . $value1->kd_org} = $value->$column_angg;
                                        ${"old_sum_real_pend_biaya" . $value1->kd_org}  = $value->$column_real;
                                    }
                                }
                        ?>
                                <td class="<?= $class ?>" align="right"><?= number_format($value->$column_angg, '2', ',', '.');  ?></td>
                                <td class="<?= $class ?>" align="right"><?= number_format($value->$column_real, '2', ',', '.') ?></td>
                        <?php
                            }
                        }
                        ?>
                    </tr>
            <?php
                }
            }
            ?>
            <tr>
                <?php
                for ($i = 1; $i <= $no; $i++) {
                ?>
                    <td>&nbsp;</td>
                    <?php
                }
                if ($organisasis) {
                    foreach ($organisasis as $key1 => $value1) {
                    ?>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <td>
                    <?php
                    // $no_urut = $no_urut + 1;
                    // echo $no_urut;
                    ?>
                </td>
                <td class="bold">JUMLAH <?= $sum_nm_rek ?></td>
                <td align="right" class="bold"><?= number_format($angg_rek6, '2', ',', '.');  ?></td>
                <td align="right" class="bold"><?= number_format($real_rek6, '2', ',', '.');  ?></td>
                <?php
                if ($organisasis) {
                    foreach ($organisasis as $key1 => $value1) {
                ?>
                        <td align="right" class="bold"><?= number_format(${"sum_angg_pend_biaya" . $value1->kd_org}, '2', ',', '.'); ?></td>
                        <td align="right" class="bold"><?= number_format(${"sum_real_pend_biaya" . $value1->kd_org}, '2', ',', '.'); ?></td>
                <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <?php
                for ($i = 1; $i <= $no; $i++) {
                ?>
                    <td>&nbsp;</td>
                    <?php
                }
                if ($organisasis) {
                    foreach ($organisasis as $key1 => $value1) {
                    ?>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <td>
                    <?php
                    // $no_urut = $no_urut + 1;
                    // echo $no_urut;
                    ?>
                </td>
                <td class="bold">PEMBIAYAAN NETTO</td>
                <td align="right" class="bold">
                    <?php
                    $netto_angg = $angg_rek6_old - $angg_rek6;
                    echo $this->custom->rp_minus($netto_angg);
                    ?>
                </td>
                <td align="right" class="bold">
                    <?php
                    $netto_real = $real_rek6_old - $real_rek6;
                    echo $this->custom->rp_minus($netto_real);
                    ?>
                </td>
                <?php
                if ($organisasis) {
                    foreach ($organisasis as $key1 => $value1) {
                ?>
                        <td align="right" class="bold">
                            <?php
                            ${"netto_angg_" . $value1->kd_org} = ${"old_sum_angg_pend_biaya" . $value1->kd_org} - ${"sum_angg_pend_biaya" . $value1->kd_org};
                            echo $this->custom->rp_minus(${"netto_angg_" . $value1->kd_org});
                            ?>
                        </td>
                        <td align="right" class="bold">
                            <?php
                            ${"netto_real_" . $value1->kd_org} = ${"old_sum_real_pend_biaya" . $value1->kd_org} - ${"sum_real_pend_biaya" . $value1->kd_org};
                            echo $this->custom->rp_minus(${"netto_real_" . $value1->kd_org});
                            ?>
                        </td>
                <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <?php
                for ($i = 1; $i <= $no; $i++) {
                ?>
                    <td>&nbsp;</td>
                    <?php
                }
                if ($organisasis) {
                    foreach ($organisasis as $key1 => $value1) {
                    ?>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                <?php
                    }
                }
                ?>
            </tr>
            <tr>
                <td>
                    <?php
                    // $no_urut = $no_urut + 1;
                    // echo $no_urut;
                    ?>
                </td>
                <td class="bold">SISA LEBIH PEMBIYAAN ANGGARAN TAHUN BERKENAAN (SILPA)</td>
                <td align="right" class="bold"><?= $this->custom->rp_minus($surplus_angg + $netto_angg); ?></td>
                <td align="right" class="bold"><?= $this->custom->rp_minus($surplus_real + $netto_real); ?>
                </td>
                <?php
                if ($organisasis) {
                    foreach ($organisasis as $key1 => $value1) {
                ?>
                        <td align="right" class="bold"><?= $this->custom->rp_minus(${"surplus_angg_" . $value1->kd_org} + ${"netto_angg_" . $value1->kd_org}); ?>
                        </td>
                        <td align="right" class="bold"><?= $this->custom->rp_minus(${"surplus_real_" . $value1->kd_org} + ${"netto_real_" . $value1->kd_org}); ?>
                        </td>
                <?php
                    }
                }
                ?>
            </tr>
        </section>
        <!-- ======================================================================= End Rekening Pembiayaan -->
    </table>
</body>

</html>