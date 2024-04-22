<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Akuntansi_rekon extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    /**
    * Buat kop
    *
    * @param int $kodeSkpd
    * @param int|string $bulan
    * @return string
    * @author Emon Krismon 
    * @link https://github.com/krismonsemanas
    */
    private function _client($kodeSkpd = "",$bulan)
    {
        $tahunAnggaran = $this->session->userdata('pcThang');
        if ($kodeSkpd) {
            $skpd =  $this->db->query("SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = ?",[$kodeSkpd])->row();
        }
        $tahun = kopTahun($tahunAnggaran, $bulan, $tahunAnggaran-1);
        $client = $this->akuntansi_model->get_sclient();

        $kop = "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                    <td rowspan=\"4\" align=\"left\" width=\"2%\">
                    <img src=\"" . base_url() . "/image/kab-sanggau.png\"  width=\"60\" height=\"70\" />
					</td>
                         <td align=\"center\"><strong>" . $client->kab_kota . "</strong></td>
                    </tr>";
        if (isset($skpd)) {
            $kop .= "<tr>
                        <td align=\"center\"><strong>$skpd->nm_skpd</strong></td>
                    </tr>";
        }
        $kop .=  "<tr>
                         <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>$tahun</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";
        return $kop;
    }

    /**
    * Buat header tabel
    *
    * @return string
    * @author Emon Krismon
    * @link https://github.com/krismonsemanas
    */
    private function _headerTableLo()
    {
        $tahunAnggaran = $this->session->userdata('pcThang');
        $tahunAnggaranLalu  =$tahunAnggaran-1;
        return "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
                            <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NO</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>Kode Rekening</b></td>
                            <td  colspan =\"5\" bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$tahunAnggaran</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$tahunAnggaranLalu</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>Kenaikan /</br>(Penurunan)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>                   
                        <tr>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td colspan =\"5\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>";
    }

    /**
    * Buat cetakkan nip
    *
    * @param stirng $nip
    * @param date $tanggal
    * @return string
    * @author Emon Krismon
    * @link https://github.com/krismonsemanas
    */
    private function _sign($nip, $tanggal)
    {
        $sign = $this->db->query("SELECT * FROM ms_ttd where nip= ? and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI') ",[$nip])->row();

        $ttdNip = $sign->nip ==  "00000000 000000 0 000" ? "":  $sign->nip;

        $result = '
            <br/> </br>
            <table  style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
                <tr>
                    <td width="50%" align="center"> </td>
                    <td align="center">Melawi, '.$tanggal.'</td>
                </tr>
                <tr>
                    <td width="50%" align="center"> </td>
                    <td align="center"><strong>'.$sign->jabatan.'</strong></td>
                </tr>
                <tr>
                    <td width="50%" align="center">&nbsp;</td>
                    <td align="center"> </td>
                </tr>
                <tr>
                    <td width="50%" align="center">&nbsp;</td>
                    <td align="center"> </td>
                </tr>
                <tr>
                    <td width="50%" align="center">&nbsp;</td>
                    <td align="center"> </td>
                </tr>
                <tr>
                    <td width="50%" align="center">&nbsp;</td>
                    <td align="center"> </td>
                </tr>
                <tr>
                    <td width="50%" align="center">&nbsp;</td>
                    <td align="center"> </td>
                </tr>
                <tr>
                    <td width="50%" align="center"> </td>
                    <td align="center"><u><strong>'. $sign->nama .'</strong></u></td>
                </tr>
                <tr>
                    <td width="50%" align="center"> </td>
                    <td align="center">'. $sign->pangkat .'</td>
                </tr>
                <tr>
                    <td width="50%" align="center"> </td>
                    <td align="center">'. $ttdNip .'</td>
                </tr>
            </table>
        ';

       return $result;
    }

    function ctk_lra_lo_pemda_org($cbulan = "", $kd_skpd = "", $pilih = 1, $tglttd = "", $ttd = "")
    {
        $tahunAnggaran = $this->session->userdata('pcThang');
        $tahunAnggaranLalu = $tahunAnggaran-1;
        $nip = str_replace('n', ' ', $tglttd);
        $tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
        $cetakLo = "";
        $cetakLo .= $this->_client($kd_skpd, $cbulan);
        $cetakLo .= $this->_headerTableLo();
        // start mapping
        $mapping  = $this->db->query("SELECT seq, nor, uraian, bold,isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak,parent,kode FROM map_lo_pemda GROUP BY seq, nor, uraian, bold, isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet'),parent, kode ORDER BY seq")
            ->result();

        $styles = [
            0 => "",
            1 => "padding-left: 25px;",
            2 => "padding-left: 50px;",
            3 => "padding-left: 75px;",
            4 => "padding-left: 100px;",
            5 => "padding-left: 125px;",
            6 => "padding-left: 125px;",
            7 => "padding-left: 125px;",
        ];
        
        // surpulus operasi
        $queryOperasi = "SELECT ISNULL(ABS(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE 0 END )),0) - ISNULL(ABS(SUM(CASE WHEN LEFT(kode_rekening,1) = 8 THEN debet-kredit ELSE 0 END )),0) AS nilai FROM transaksi_lo WHERE kode_skpd  = ? AND MONTH(tanggal) <= ? AND YEAR(tanggal) = ?";
        $resultOperasiTahunIni = $this->db->query($queryOperasi,[$kd_skpd, $cbulan, $tahunAnggaran])->row();
        $resultOperasiTahunLalu =  $this->db->query($queryOperasi,[$kd_skpd, 12, $tahunAnggaranLalu])->row();
        $surplusTahunIni = $resultOperasiTahunIni ? $resultOperasiTahunIni->nilai : 0;
        $surplusTahunLalu = $resultOperasiTahunLalu ? $resultOperasiTahunLalu->nilai : 0;
        $kenaikanSurplus = $surplusTahunIni-$surplusTahunLalu;
        $persenSurplus = ($surplusTahunIni == 0 || $surplusTahunLalu ==0 ) ? 0 : (abs($surplusTahunIni) / abs($surplusTahunLalu)) * 100;

        // non operasional
        $queryNonOperasi = "SELECT 
                ISNULL(
                    -- surplus non operasional
                    CASE WHEN LEFT(kode_rekening,1) = 7 THEN ABS(SUM(kredit-debet)) ELSE 0 END -
                    -- defisit non operasional
                    CASE WHEN LEFT(kode_rekening,1) = 8 THEN ABS(SUM(debet-kredit)) ELSE 0 END
                ,0) AS nilai
            FROM transaksi_lo WHERE kode_skpd = ? AND MONTH(tanggal) <= ? AND YEAR(tanggal) = ? AND LEFT(kode_rekening,2) IN (74,83)
            GROUP BY LEFT(kode_rekening,1)
        ";
        $resultNonOperasiTahunIni = $this->db->query($queryNonOperasi,[$kd_skpd, $cbulan, $tahunAnggaran])->row();
        $resultNonOperasiTahunLalu = $this->db->query($queryNonOperasi,[$kd_skpd, 12, $tahunAnggaranLalu])->row();
        $nonOperasiTahunIni = $resultNonOperasiTahunIni ? $resultNonOperasiTahunIni->nilai : 0;
        $nonOperasiTahunLalu = $resultNonOperasiTahunLalu ? $resultNonOperasiTahunLalu->nilai : 0;

        // selisih surplus non operasional dan defisit operasion
        $sebelumPosTahunIni = $surplusTahunIni + $nonOperasiTahunIni;
        $sebelumPosTahunLalu = $surplusTahunLalu + $nonOperasiTahunLalu;
        $kenaikanSebelumPos =abs( $sebelumPosTahunIni)-abs($sebelumPosTahunLalu);
        $persenSebelumPos = abs( $sebelumPosTahunIni)/abs($sebelumPosTahunLalu) * 100;

        foreach ($mapping as $key => $map) {
            $tahunIni = "";
            $tahunLalu ="";
            $kenaikan = "";
            $persen= "";

            if ($map->bold == 5) {
                $tahunIni = $surplusTahunIni < 0 ? "(". $this->support->currencyFormat(abs($surplusTahunIni)) .")" : $this->support->currencyFormat($surplusTahunIni);
                $tahunLalu = $surplusTahunLalu < 0 ? "(". $this->support->currencyFormat(abs($surplusTahunLalu)) .")" : $this->support->currencyFormat($surplusTahunLalu);
                $kenaikan = $this->support->rp_minus($kenaikanSurplus);
                $persen = $this->support->currencyFormat($persenSurplus);
            }

            if(in_array($map->bold,[6,7])) {
                $tahunIni = $this->support->rp_minus($sebelumPosTahunIni);
                $tahunLalu = $this->support->rp_minus($sebelumPosTahunLalu);
                $kenaikan = $this->support->rp_minus($kenaikanSebelumPos);
                $persen = $this->support->currencyFormat(abs($persenSebelumPos));
            }

            if($map->cetak != "" && in_array($map->bold,[3,4])) {
                $rekening3 = $map->kode_1 === '-' ? "0" : $map->kode_1;
                $rekening4 = $map->kode_2 === '-' ? "0" : $map->kode_2;
                $rekening5 = $map->kode_3 === '-' ? "0" : $map->kode_3;
                $query = "SELECT ISNULL(ABS(SUM(debet-kredit)),0) AS nilai FROM transaksi_lo 
                    WHERE kode_skpd = ? AND (LEFT(kode_rekening,4) IN ($rekening3)  OR LEFT(kode_rekening,6) 
                    IN ($rekening4)  OR LEFT(kode_rekening,8) IN ($rekening5) ) AND MONTH(tanggal) <= ?  AND YEAR(tanggal) = ? ";
                $realisasiTahunIni = $this->db->query($query,[$kd_skpd, $cbulan, $tahunAnggaran])->row();
                $realisasiTahunLalu = $this->db->query($query,[$kd_skpd, 12, $tahunAnggaranLalu])->row();
                if($map->seq == 410) {
                    $nilaiTahunIni = $nonOperasiTahunIni;
                    $nilaiTahunLalu = $nonOperasiTahunLalu;
                } else {
                    $nilaiTahunIni = $realisasiTahunIni ? $realisasiTahunIni->nilai : 0; 
                    $nilaiTahunLalu = $realisasiTahunLalu ? $realisasiTahunLalu->nilai : 0;
                }
                $tahunIni = $this->support->rp_minus($nilaiTahunIni);
                $tahunLalu = $this->support->rp_minus($nilaiTahunLalu);
                $kenaikan = $this->support->rp_minus($nilaiTahunIni - $nilaiTahunLalu);
                if($nilaiTahunIni == 0 || $nilaiTahunLalu == 0) {
                    $persen = "0,00";
                } else {
                    $persen = $this->support->currencyFormat($nilaiTahunIni / $nilaiTahunLalu *100);
                }
            }

            if($map->bold != 3) {
                $no ="<strong>".($key+1)."</strong>";
                $nama = "<strong>$map->uraian</strong>";
                $tahunLalu = "<strong>$tahunLalu</strong>";
                $tahunIni = "<strong>$tahunIni</strong>";
                $kenaikan = "<strong>$kenaikan</strong>";
                $persen = "<strong>$persen</strong>";
            } else {
                $no = $key+1;
                $nama = $map->uraian;
            }

            $style = $styles[$map->bold];
            $cetakLo .= "<tr>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td>
                            <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none; $style\" width=\"27%\">$nama</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$tahunIni</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$tahunLalu</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$kenaikan</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persen</td>
                        </tr>";
        }
        // end mapping
        $cetakLo .= "</table>";
        $cetakLo .= $this->_sign($nip, $tanggal);
        $data['prev'] = $cetakLo;
        $data['sikap'] = 'preview';
        $judul  = ("LO KONSOL SKPD $cbulan");
        $this->template->set('title', 'LO KONSOL UNIT $cbulan');
        switch ($pilih) {
            case 0;
                $this->tukd_model->_mpdf('', $cetakLo, 10, 5, 10, '0');
                echo $cetakLo;
                break;
            case 1;
                // $this->tukd_model->_mpdf('',$cetakLo,10,10,10,'0');
                echo "<title>LO KONSOL SKPD $cbulan</title>";
                echo $cetakLo;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }


    function ctk_lra_lo_pemda_org_objek($cbulan = "", $kd_skpd = "", $pilih = 1, $tglttd = "", $ttd = "")
    {
        $tahunAnggaran = $this->session->userdata('pcThang');
        $tahunAnggaranLalu = $tahunAnggaran-1;
        $nip = str_replace('n', ' ', $tglttd);
        $tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
        $cetakLo = "";
        $cetakLo .= $this->_client($kd_skpd, $cbulan);
        $cetakLo .= $this->_headerTableLo();
        // start mapping
        $mapping  = $this->db->query("SELECT seq, nor, uraian, bold,isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak,parent,kode FROM map_lo_pemda GROUP BY seq, nor, uraian, bold, isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet'),parent, kode ORDER BY seq")
            ->result();

        $styles = [
            0 => "",
            1 => "padding-left: 25px;",
            2 => "padding-left: 50px;",
            3 => "padding-left: 75px;",
            4 => "padding-left: 100px;",
            5 => "padding-left: 125px;",
            6 => "padding-left: 125px;",
            7 => "padding-left: 125px;",
        ];
        
        // surpulus operasi
        $queryOperasi = "SELECT ISNULL(ABS(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE 0 END )),0) - ISNULL(ABS(SUM(CASE WHEN LEFT(kode_rekening,1) = 8 THEN debet-kredit ELSE 0 END )),0) AS nilai FROM transaksi_lo WHERE kode_skpd  = ? AND MONTH(tanggal) <= ? AND YEAR(tanggal) = ?";
        $resultOperasiTahunIni = $this->db->query($queryOperasi,[$kd_skpd, $cbulan, $tahunAnggaran])->row();
        $resultOperasiTahunLalu =  $this->db->query($queryOperasi,[$kd_skpd, 12, $tahunAnggaranLalu])->row();
        $surplusTahunIni = $resultOperasiTahunIni ? $resultOperasiTahunIni->nilai : 0;
        $surplusTahunLalu = $resultOperasiTahunLalu ? $resultOperasiTahunLalu->nilai : 0;
        $kenaikanSurplus = $surplusTahunIni-$surplusTahunLalu;
        $persenSurplus = ($surplusTahunIni == 0 || $surplusTahunLalu ==0 ) ? 0 : (abs($surplusTahunIni) / abs($surplusTahunLalu)) * 100;

        // non operasional
        $queryNonOperasi = "SELECT 
                ISNULL(
                    -- surplus non operasional
                    CASE WHEN LEFT(kode_rekening,1) = 7 THEN ABS(SUM(kredit-debet)) ELSE 0 END -
                    -- defisit non operasional
                    CASE WHEN LEFT(kode_rekening,1) = 8 THEN ABS(SUM(debet-kredit)) ELSE 0 END
                ,0) AS nilai
            FROM transaksi_lo WHERE kode_skpd = ? AND MONTH(tanggal) <= ? AND YEAR(tanggal) = ? AND LEFT(kode_rekening,2) IN (74,83)
            GROUP BY LEFT(kode_rekening,1)
        ";
        $resultNonOperasiTahunIni = $this->db->query($queryNonOperasi,[$kd_skpd, $cbulan, $tahunAnggaran])->row();
        $resultNonOperasiTahunLalu = $this->db->query($queryNonOperasi,[$kd_skpd, 12, $tahunAnggaranLalu])->row();
        $nonOperasiTahunIni = $resultNonOperasiTahunIni ? $resultNonOperasiTahunIni->nilai : 0;
        $nonOperasiTahunLalu = $resultNonOperasiTahunLalu ? $resultNonOperasiTahunLalu->nilai : 0;

        // selisih surplus non operasional dan defisit operasion
        $sebelumPosTahunIni = $surplusTahunIni + $nonOperasiTahunIni;
        $sebelumPosTahunLalu = $surplusTahunLalu + $nonOperasiTahunLalu;
        $kenaikanSebelumPos =abs( $sebelumPosTahunIni)-abs($sebelumPosTahunLalu);
        $persenSebelumPos = abs( $sebelumPosTahunIni)/abs($sebelumPosTahunLalu) * 100;

        $index = 0;
        foreach ($mapping as $key => $map) {
            $index += 1;
            $tahunIni = "";
            $tahunLalu ="";
            $kenaikan = "";
            $persen= "";

            if ($map->bold == 5) {
                $tahunIni = $surplusTahunIni < 0 ? "(". $this->support->currencyFormat(abs($surplusTahunIni)) .")" : $this->support->currencyFormat($surplusTahunIni);
                $tahunLalu = $surplusTahunLalu < 0 ? "(". $this->support->currencyFormat(abs($surplusTahunLalu)) .")" : $this->support->currencyFormat($surplusTahunLalu);
                $kenaikan = $this->support->rp_minus($kenaikanSurplus);
                $persen = $this->support->currencyFormat($persenSurplus);
            }

            if(in_array($map->bold,[6,7])) {
                $tahunIni = $this->support->rp_minus($sebelumPosTahunIni);
                $tahunLalu = $this->support->rp_minus($sebelumPosTahunLalu);
                $kenaikan = $this->support->rp_minus($kenaikanSebelumPos);
                $persen = $this->support->currencyFormat(abs($persenSebelumPos));
            }

            if($map->cetak != "" && in_array($map->bold,[3,4])) {
                $rekening3 = $map->kode_1 === '-' ? "0" : $map->kode_1;
                $rekening4 = $map->kode_2 === '-' ? "0" : $map->kode_2;
                $rekening5 = $map->kode_3 === '-' ? "0" : $map->kode_3;
                $query = "SELECT ISNULL(ABS(SUM(debet-kredit)),0) AS nilai FROM transaksi_lo 
                    WHERE kode_skpd = ? AND (LEFT(kode_rekening,4) IN ($rekening3)  OR LEFT(kode_rekening,6) 
                    IN ($rekening4)  OR LEFT(kode_rekening,8) IN ($rekening5) ) AND MONTH(tanggal) <= ?  AND YEAR(tanggal) = ? ";
                $realisasiTahunIni = $this->db->query($query,[$kd_skpd, $cbulan, $tahunAnggaran])->row();
                $realisasiTahunLalu = $this->db->query($query,[$kd_skpd, 12, $tahunAnggaranLalu])->row();
                if($map->seq == 410) {
                    $nilaiTahunIni = $nonOperasiTahunIni;
                    $nilaiTahunLalu = $nonOperasiTahunLalu;
                } else {
                    $nilaiTahunIni = $realisasiTahunIni ? $realisasiTahunIni->nilai : 0; 
                    $nilaiTahunLalu = $realisasiTahunLalu ? $realisasiTahunLalu->nilai : 0;
                }
                $tahunIni = $this->support->rp_minus($nilaiTahunIni);
                $tahunLalu = $this->support->rp_minus($nilaiTahunLalu);
                $kenaikan = $this->support->rp_minus($nilaiTahunIni - $nilaiTahunLalu);
                if($nilaiTahunIni == 0 || $nilaiTahunLalu == 0) {
                    $persen = "0,00";
                } else {
                    $persen = $this->support->currencyFormat($nilaiTahunIni / $nilaiTahunLalu *100);
                }
            }

            if($map->bold != 3) {
                $no ="<strong>".($index)."</strong>";
                $nama = "<strong>$map->uraian</strong>";
                $tahunLalu = "<strong>$tahunLalu</strong>";
                $tahunIni = "<strong>$tahunIni</strong>";
                $kenaikan = "<strong>$kenaikan</strong>";
                $persen = "<strong>$persen</strong>";
            } else {
                $no = $index;
                $nama = $map->uraian;
            }

            $style = $styles[$map->bold];
            $cetakLo .= "<tr>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td>
                            <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none; $style\" width=\"27%\">$nama</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$tahunIni</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$tahunLalu</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$kenaikan</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persen</td>
                        </tr>";
            if ($map->bold == 3) {
                // objek
                $queryObject = "SELECT 
                        rek.kd_rek4 AS kode_rekening,
                        rek.nm_rek4 as nama_rekening,
                        ISNULL(ABS(SUM(debet-kredit)),0) AS nilai, 
                        (
                            SELECT ISNULL(ABS(SUM(debet-kredit)),0) FROM transaksi_lo AS loLalu WHERE LEFT(loLalu.kode_rekening,6) = rek.kd_rek4 AND kode_skpd =  lo.kode_skpd AND YEAR(tanggal) = ?
                        ) AS nilaiLalu
                    FROM transaksi_lo AS lo INNER JOIN ms_rek4 AS rek ON LEFT(kode_rekening,6) = rek.kd_rek4
                    WHERE kode_skpd = ? AND MONTH(tanggal) <= ? 
                    AND YEAR(tanggal) = ? AND rek.kd_rek3 = $rekening3
                    GROUP BY rek.kd_rek4, rek.nm_rek4,kode_skpd ";

                $result =  $this->db->query($queryObject, [$tahunAnggaranLalu, $kd_skpd, $cbulan, $tahunAnggaran])->result();
                
                foreach ($result as $objek) {
                    $index += 1;
                    $objekIni = $this->support->rp_minus($objek->nilai);
                    $objekLalu = $this->support->rp_minus($objek->nilaiLalu);
                    $selisihObjek = $this->support->rp_minus($objek->nilai-$objek->nilaiLalu);
                    if(in_array(0,[$objek->nilai, $objek->nilaiLalu])) {
                        $persenObjek = "0.00";
                    } else {
                        $persenObjek = $this->support->currencyFormat($objek->nilai/$objek->nilaiLalu * 100);
                    }

                    $cetakLo .= "<tr>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$index</td>
                                <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none; padding-left: 125px\" width=\"27%\">$objek->nama_rekening</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$objekIni</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$objekLalu</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$selisihObjek</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persenObjek</td>
                            </tr>";
                }
            }
        }
        // end mapping
        $cetakLo .= "</table>";
        $cetakLo .= $this->_sign($nip, $tanggal);
        $data['prev'] = $cetakLo;
        $data['sikap'] = 'preview';
        $judul  = ("LO KONSOL SKPD $cbulan");
        $this->template->set('title', 'LO KONSOL UNIT $cbulan');
        switch ($pilih) {
            case 0;
                $this->tukd_model->_mpdf('', $cetakLo, 10, 5, 10, '0');
                echo $cetakLo;
                break;
            case 1;
                // $this->tukd_model->_mpdf('',$cetakLo,10,10,10,'0');
                echo "<title>LO KONSOL SKPD $cbulan</title>";
                echo $cetakLo;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    } 

    function ctk_lra_lo_pemda_org_rincian($cbulan = "", $kd_skpd = "", $pilih = 1, $tglttd = "", $ttd = "")
    {
        $tahunAnggaran = $this->session->userdata('pcThang');
        $tahunAnggaranLalu = $tahunAnggaran-1;
        $nip = str_replace('n', ' ', $tglttd);
        $tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
        $cetakLo = "";
        $cetakLo .= $this->_client($kd_skpd, $cbulan);
        $cetakLo .= $this->_headerTableLo();
        // start mapping
        $mapping  = $this->db->query("SELECT seq, nor, uraian, bold,isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak,parent,kode FROM map_lo_pemda GROUP BY seq, nor, uraian, bold, isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet'),parent, kode ORDER BY seq")
            ->result();

        $styles = [
            0 => "",
            1 => "padding-left: 25px;",
            2 => "padding-left: 50px;",
            3 => "padding-left: 75px;",
            4 => "padding-left: 100px;",
            5 => "padding-left: 125px;",
            6 => "padding-left: 125px;",
            7 => "padding-left: 125px;",
        ];
        
        // surpulus operasi
        $queryOperasi = "SELECT ISNULL(ABS(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE 0 END )),0) - ISNULL(ABS(SUM(CASE WHEN LEFT(kode_rekening,1) = 8 THEN debet-kredit ELSE 0 END )),0) AS nilai FROM transaksi_lo WHERE kode_skpd  = ? AND MONTH(tanggal) <= ? AND YEAR(tanggal) = ?";
        $resultOperasiTahunIni = $this->db->query($queryOperasi,[$kd_skpd, $cbulan, $tahunAnggaran])->row();
        $resultOperasiTahunLalu =  $this->db->query($queryOperasi,[$kd_skpd, 12, $tahunAnggaranLalu])->row();
        $surplusTahunIni = $resultOperasiTahunIni ? $resultOperasiTahunIni->nilai : 0;
        $surplusTahunLalu = $resultOperasiTahunLalu ? $resultOperasiTahunLalu->nilai : 0;
        $kenaikanSurplus = $surplusTahunIni-$surplusTahunLalu;
        $persenSurplus = ($surplusTahunIni == 0 || $surplusTahunLalu ==0 ) ? 0 : (abs($surplusTahunIni) / abs($surplusTahunLalu)) * 100;

        // non operasional
        $queryNonOperasi = "SELECT 
                ISNULL(
                    -- surplus non operasional
                    CASE WHEN LEFT(kode_rekening,1) = 7 THEN ABS(SUM(kredit-debet)) ELSE 0 END -
                    -- defisit non operasional
                    CASE WHEN LEFT(kode_rekening,1) = 8 THEN ABS(SUM(debet-kredit)) ELSE 0 END
                ,0) AS nilai
            FROM transaksi_lo WHERE kode_skpd = ? AND MONTH(tanggal) <= ? AND YEAR(tanggal) = ? AND LEFT(kode_rekening,2) IN (74,83)
            GROUP BY LEFT(kode_rekening,1)
        ";
        $resultNonOperasiTahunIni = $this->db->query($queryNonOperasi,[$kd_skpd, $cbulan, $tahunAnggaran])->row();
        $resultNonOperasiTahunLalu = $this->db->query($queryNonOperasi,[$kd_skpd, 12, $tahunAnggaranLalu])->row();
        $nonOperasiTahunIni = $resultNonOperasiTahunIni ? $resultNonOperasiTahunIni->nilai : 0;
        $nonOperasiTahunLalu = $resultNonOperasiTahunLalu ? $resultNonOperasiTahunLalu->nilai : 0;

        // selisih surplus non operasional dan defisit operasion
        $sebelumPosTahunIni = $surplusTahunIni + $nonOperasiTahunIni;
        $sebelumPosTahunLalu = $surplusTahunLalu + $nonOperasiTahunLalu;
        $kenaikanSebelumPos =abs( $sebelumPosTahunIni)-abs($sebelumPosTahunLalu);
        $persenSebelumPos = abs( $sebelumPosTahunIni)/abs($sebelumPosTahunLalu) * 100;

        $index = 0;
        foreach ($mapping as $key => $map) {
            $index += 1;
            $tahunIni = "";
            $tahunLalu ="";
            $kenaikan = "";
            $persen= "";

            if ($map->bold == 5) {
                $tahunIni = $surplusTahunIni < 0 ? "(". $this->support->currencyFormat(abs($surplusTahunIni)) .")" : $this->support->currencyFormat($surplusTahunIni);
                $tahunLalu = $surplusTahunLalu < 0 ? "(". $this->support->currencyFormat(abs($surplusTahunLalu)) .")" : $this->support->currencyFormat($surplusTahunLalu);
                $kenaikan = $this->support->rp_minus($kenaikanSurplus);
                $persen = $this->support->currencyFormat($persenSurplus);
            }

            if(in_array($map->bold,[6,7])) {
                $tahunIni = $this->support->rp_minus($sebelumPosTahunIni);
                $tahunLalu = $this->support->rp_minus($sebelumPosTahunLalu);
                $kenaikan = $this->support->rp_minus($kenaikanSebelumPos);
                $persen = $this->support->currencyFormat(abs($persenSebelumPos));
            }

            if($map->cetak != "" && in_array($map->bold,[3,4])) {
                $rekening3 = $map->kode_1 === '-' ? "0" : $map->kode_1;
                $rekening4 = $map->kode_2 === '-' ? "0" : $map->kode_2;
                $rekening5 = $map->kode_3 === '-' ? "0" : $map->kode_3;
                $query = "SELECT ISNULL(ABS(SUM(debet-kredit)),0) AS nilai FROM transaksi_lo 
                    WHERE kode_skpd = ? AND (LEFT(kode_rekening,4) IN ($rekening3)  OR LEFT(kode_rekening,6) 
                    IN ($rekening4)  OR LEFT(kode_rekening,8) IN ($rekening5) ) AND MONTH(tanggal) <= ?  AND YEAR(tanggal) = ? ";
                $realisasiTahunIni = $this->db->query($query,[$kd_skpd, $cbulan, $tahunAnggaran])->row();
                $realisasiTahunLalu = $this->db->query($query,[$kd_skpd, 12, $tahunAnggaranLalu])->row();
                if($map->seq == 410) {
                    $nilaiTahunIni = $nonOperasiTahunIni;
                    $nilaiTahunLalu = $nonOperasiTahunLalu;
                } else {
                    $nilaiTahunIni = $realisasiTahunIni ? $realisasiTahunIni->nilai : 0; 
                    $nilaiTahunLalu = $realisasiTahunLalu ? $realisasiTahunLalu->nilai : 0;
                }
                $tahunIni = $this->support->rp_minus($nilaiTahunIni);
                $tahunLalu = $this->support->rp_minus($nilaiTahunLalu);
                $kenaikan = $this->support->rp_minus($nilaiTahunIni - $nilaiTahunLalu);
                if($nilaiTahunIni == 0 || $nilaiTahunLalu == 0) {
                    $persen = "0,00";
                } else {
                    $persen = $this->support->currencyFormat($nilaiTahunIni / $nilaiTahunLalu *100);
                }
            }

            if($map->bold != 3) {
                $no ="<strong>".($index)."</strong>";
                $nama = "<strong>$map->uraian</strong>";
                $tahunLalu = "<strong>$tahunLalu</strong>";
                $tahunIni = "<strong>$tahunIni</strong>";
                $kenaikan = "<strong>$kenaikan</strong>";
                $persen = "<strong>$persen</strong>";
            } else {
                $no = $index;
                $nama = $map->uraian;
            }

            $style = $styles[$map->bold];
            $cetakLo .= "<tr>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td>
                            <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none; $style\" width=\"27%\">$nama</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$tahunIni</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$tahunLalu</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$kenaikan</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persen</td>
                        </tr>";
            if ($map->bold == 3) {
                // objek
                $queryObject = "SELECT * FROM (
                    SELECT
                        rek.kd_rek4 AS kode_rekening,
                        rek.nm_rek4 as nama_rekening,
                        ISNULL(ABS(SUM(debet-kredit)),0) AS nilai,
                        (
                            SELECT ISNULL(ABS(SUM(debet-kredit)),0) FROM transaksi_lo AS loLalu WHERE LEFT(loLalu.kode_rekening,6) = rek.kd_rek4 AND kode_skpd =  lo.kode_skpd AND YEAR(tanggal) = ?
                        ) AS nilaiLalu
                    FROM transaksi_lo AS lo INNER JOIN ms_rek4 AS rek ON LEFT(kode_rekening,6) = rek.kd_rek4
                    WHERE kode_skpd = ? AND MONTH(tanggal) <= ?
                    AND YEAR(tanggal) = ? AND rek.kd_rek3 = $rekening3
                    GROUP BY rek.kd_rek4, rek.nm_rek4,kode_skpd UNION ALL
                    SELECT
                        rek.kd_rek5 AS kode_rekening,
                        rek.nm_rek5 as nama_rekening,
                        ISNULL(ABS(SUM(debet-kredit)),0) AS nilai,
                        (
                            SELECT ISNULL(ABS(SUM(debet-kredit)),0) FROM transaksi_lo AS loLalu WHERE LEFT(loLalu.kode_rekening,8) = rek.kd_rek5 AND kode_skpd =  lo.kode_skpd AND YEAR(tanggal) = ?
                        ) AS nilaiLalu
                    FROM transaksi_lo AS lo INNER JOIN ms_rek5 AS rek ON LEFT(kode_rekening,8) = rek.kd_rek5
                    WHERE kode_skpd = ? AND MONTH(tanggal) <= ?
                    AND YEAR(tanggal) = ? AND LEFT(kode_rekening,4) = $rekening3
                    GROUP BY rek.kd_rek5, rek.nm_rek5,kode_skpd
                ) AS source ORDER BY kode_rekening";

                $result =  $this->db->query($queryObject, [$tahunAnggaranLalu, $kd_skpd, $cbulan, $tahunAnggaran, $tahunAnggaranLalu, $kd_skpd, $cbulan, $tahunAnggaran])->result();

                foreach ($result as $objek) {
                    $index += 1;
                    $objekIni = $this->support->rp_minus($objek->nilai);
                    $objekLalu = $this->support->rp_minus($objek->nilaiLalu);
                    $selisihObjek = $this->support->rp_minus($objek->nilai-$objek->nilaiLalu);
                    if(in_array(0,[$objek->nilai, $objek->nilaiLalu])) {
                        $persenObjek = "0.00";
                    } else {
                        $persenObjek = $this->support->currencyFormat($objek->nilai/$objek->nilaiLalu * 100);
                    }

                    $style  = strlen($objek->kode_rekening) == 8 ? "150px;" : "125px;";

                    $cetakLo .= "<tr>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$index</td>
                                <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none; padding-left: $style \" width=\"27%\">$objek->nama_rekening</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$objekIni</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$objekLalu</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$selisihObjek</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persenObjek</td>
                            </tr>";
                }
            }
        }
        // end mapping
        $cetakLo .= "</table>";
        $cetakLo .= $this->_sign($nip, $tanggal);
        $data['prev'] = $cetakLo;
        $data['sikap'] = 'preview';
        $judul  = ("LO KONSOL SKPD $cbulan");
        $this->template->set('title', 'LO KONSOL UNIT $cbulan');
        switch ($pilih) {
            case 0;
                $this->tukd_model->_mpdf('', $cetakLo, 10, 5, 10, '0');
                echo $cetakLo;
                break;
            case 1;
                // $this->tukd_model->_mpdf('',$cetakLo,10,10,10,'0');
                echo "<title>LO KONSOL SKPD $cbulan</title>";
                echo $cetakLo;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }

    // andika penambahan per sub rincian (skpd)
    function ctk_lra_lo_pemda_org_subrincian($cbulan = "", $kd_skpd = "", $pilih = 1,$tglttd = "", $ttd = "")
    {
        $tahunAnggaran = $this->session->userdata('pcThang');
        $tahunAnggaranLalu = $tahunAnggaran-1;
        $nip = str_replace('n', ' ', $tglttd);
        $tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
        $cetakLo = "";
        $cetakLo .= $this->_client($kd_skpd, $cbulan);
        $cetakLo .= $this->_headerTableLo();
        // start mapping
        $mapping  = $this->db->query("SELECT seq, nor, uraian, bold,isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak,parent,kode FROM map_lo_pemda GROUP BY seq, nor, uraian, bold, isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet'),parent, kode ORDER BY seq")
            ->result();

        $styles = [
            0 => "",
            1 => "padding-left: 25px;",
            2 => "padding-left: 50px;",
            3 => "padding-left: 75px;",
            4 => "padding-left: 100px;",
            5 => "padding-left: 125px;",
            6 => "padding-left: 125px;",
            7 => "padding-left: 125px;",
        ];
        
        // surpulus operasi
        $queryOperasi = "SELECT ISNULL(ABS(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE 0 END )),0) - ISNULL(ABS(SUM(CASE WHEN LEFT(kode_rekening,1) = 8 THEN debet-kredit ELSE 0 END )),0) AS nilai FROM transaksi_lo WHERE kode_skpd  = ? AND MONTH(tanggal) <= ? AND YEAR(tanggal) = ? AND LEFT(kode_rekening,2) != 83";
        $resultOperasiTahunIni = $this->db->query($queryOperasi,[$kd_skpd, $cbulan, $tahunAnggaran])->row();
        $resultOperasiTahunLalu =  $this->db->query($queryOperasi,[$kd_skpd, 12, $tahunAnggaranLalu])->row();
        $surplusTahunIni = $resultOperasiTahunIni ? $resultOperasiTahunIni->nilai : 0;
        $surplusTahunLalu = $resultOperasiTahunLalu ? $resultOperasiTahunLalu->nilai : 0;
        $kenaikanSurplus = $surplusTahunIni-$surplusTahunLalu;
        $persenSurplus = ($surplusTahunIni == 0 || $surplusTahunLalu ==0 ) ? 0 : (abs($surplusTahunIni) / abs($surplusTahunLalu)) * 100;

        // non operasional
        $queryNonOperasi = "SELECT 
                ISNULL(
                    -- surplus non operasional
                    CASE WHEN LEFT(kode_rekening,1) = 7 THEN ABS(SUM(kredit-debet)) ELSE 0 END -
                    -- defisit non operasional
                    CASE WHEN LEFT(kode_rekening,1) = 8 THEN ABS(SUM(debet-kredit)) ELSE 0 END
                ,0) AS nilai
            FROM transaksi_lo WHERE kode_skpd = ? AND MONTH(tanggal) <= ? AND YEAR(tanggal) = ? AND LEFT(kode_rekening,2) IN (74,83)
            GROUP BY LEFT(kode_rekening,1)
        ";
        $resultNonOperasiTahunIni = $this->db->query($queryNonOperasi,[$kd_skpd, $cbulan, $tahunAnggaran])->row();
        $resultNonOperasiTahunLalu = $this->db->query($queryNonOperasi,[$kd_skpd, 12, $tahunAnggaranLalu])->row();
        $nonOperasiTahunIni = $resultNonOperasiTahunIni ? $resultNonOperasiTahunIni->nilai : 0;
        $nonOperasiTahunLalu = $resultNonOperasiTahunLalu ? $resultNonOperasiTahunLalu->nilai : 0;

        // selisih surplus non operasional dan defisit operasion
        $sebelumPosTahunIni = $surplusTahunIni + $nonOperasiTahunIni;
        $sebelumPosTahunLalu = $surplusTahunLalu + $nonOperasiTahunLalu;
        $kenaikanSebelumPos =abs( $sebelumPosTahunIni)-abs($sebelumPosTahunLalu);
        $persenSebelumPos = abs( $sebelumPosTahunIni)/abs($sebelumPosTahunLalu) * 100;

        $index = 0;
        foreach ($mapping as $key => $map) {
            $index += 1;
            $tahunIni = "";
            $tahunLalu ="";
            $kenaikan = "";
            $persen= "";
            $kodeRekening = "";

            if ($map->bold == 5) {
                $tahunIni = $surplusTahunIni < 0 ? "(". $this->support->currencyFormat(abs($surplusTahunIni)) .")" : $this->support->currencyFormat($surplusTahunIni);
                $tahunLalu = $surplusTahunLalu < 0 ? "(". $this->support->currencyFormat(abs($surplusTahunLalu)) .")" : $this->support->currencyFormat($surplusTahunLalu);
                $kenaikan = $this->support->rp_minus($kenaikanSurplus);
                $persen = $this->support->currencyFormat($persenSurplus);
            }

            if(in_array($map->bold,[6,7])) {
                $tahunIni = $this->support->rp_minus($sebelumPosTahunIni);
                $tahunLalu = $this->support->rp_minus($sebelumPosTahunLalu);
                $kenaikan = $this->support->rp_minus($kenaikanSebelumPos);
                $persen = $this->support->currencyFormat(abs($persenSebelumPos));
            }

            if($map->cetak != "" && in_array($map->bold,[3,4])) {
                $rekening3 = $map->kode_1 === '-' ? "0" : $map->kode_1;
                $rekening4 = $map->kode_2 === '-' ? "0" : $map->kode_2;
                $rekening5 = $map->kode_3 === '-' ? "0" : $map->kode_3;
                $query = "SELECT ISNULL(ABS(SUM(debet-kredit)),0) AS nilai FROM transaksi_lo 
                    WHERE kode_skpd = ? AND (LEFT(kode_rekening,4) IN ($rekening3)  OR LEFT(kode_rekening,6) 
                    IN ($rekening4)  OR LEFT(kode_rekening,8) IN ($rekening5) ) AND MONTH(tanggal) <= ?  AND YEAR(tanggal) = ? ";
                $realisasiTahunIni = $this->db->query($query,[$kd_skpd, $cbulan, $tahunAnggaran])->row();
                $realisasiTahunLalu = $this->db->query($query,[$kd_skpd, 12, $tahunAnggaranLalu])->row();
                if($map->seq == 410) {
                    $nilaiTahunIni = $nonOperasiTahunIni;
                    $nilaiTahunLalu = $nonOperasiTahunLalu;
                } else {
                    $nilaiTahunIni = $realisasiTahunIni ? $realisasiTahunIni->nilai : 0; 
                    $nilaiTahunLalu = $realisasiTahunLalu ? $realisasiTahunLalu->nilai : 0;
                }
                $tahunIni = $this->support->rp_minus($nilaiTahunIni);
                $tahunLalu = $this->support->rp_minus($nilaiTahunLalu);
                $kenaikan = $this->support->rp_minus($nilaiTahunIni - $nilaiTahunLalu);
                if($nilaiTahunIni == 0 || $nilaiTahunLalu == 0) {
                    $persen = "0,00";
                } else {
                    $persen = $this->support->currencyFormat($nilaiTahunIni / $nilaiTahunLalu *100);
                }

               
            }

            if($map->bold != 3) {
                $no ="<strong>".($index)."</strong>";
                $nama = "<strong>$map->uraian</strong>";
                $tahunLalu = "<strong>$tahunLalu</strong>";
                $tahunIni = "<strong>$tahunIni</strong>";
                $kenaikan = "<strong>$kenaikan</strong>";
                $persen = "<strong>$persen</strong>";
            } else {
                $no = $index;
                $nama = $map->uraian;
            }
            if ($map->bold == 3) {
                $kodeRekening = substr($rekening3 ? $rekening3 : $rekening4, 1,4) ? substr($rekening3 ? $rekening3 : $rekening4, 1,4) : substr($rekening5, 1,4) ;
            } else {
                // $kodeRekening = 0;
            }
            $style = $styles[$map->bold];
            $cetakLo .= "<tr>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black; padding-left: 10px; padding-left: 10px\" width=\"5%\">$kodeRekening</td>
                            <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none; $style\" width=\"27%\">$nama</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$tahunIni</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$tahunLalu</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$kenaikan</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persen</td>
                        </tr>";
            if ($map->bold == 3) {
                // objek
                $queryObject = "SELECT * FROM (
                    SELECT
                        rek.kd_rek4 AS kode_rekening,
                        rek.nm_rek4 as nama_rekening,
                        ISNULL(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE debet-kredit END ),0) AS nilai,
                        (
                            SELECT ISNULL(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE debet-kredit END ),0) FROM transaksi_lo AS loLalu WHERE LEFT(loLalu.kode_rekening,6) = rek.kd_rek4 AND kode_skpd =  lo.kode_skpd AND YEAR(tanggal) = ?
                        ) AS nilaiLalu
                    FROM transaksi_lo AS lo INNER JOIN ms_rek4 AS rek ON LEFT(kode_rekening,6) = rek.kd_rek4
                    WHERE kode_skpd = ? AND MONTH(tanggal) <= ?
                    AND YEAR(tanggal) = ? AND rek.kd_rek3 = $rekening3
                    GROUP BY rek.kd_rek4, rek.nm_rek4,kode_skpd UNION ALL
                    SELECT
                        rek.kd_rek5 AS kode_rekening,
                        rek.nm_rek5 as nama_rekening,
                        ISNULL(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE debet-kredit END ),0) AS nilai,
                        (
                            SELECT ISNULL(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE debet-kredit END ),0) FROM transaksi_lo AS loLalu WHERE LEFT(loLalu.kode_rekening,8) = rek.kd_rek5 AND kode_skpd =  lo.kode_skpd AND YEAR(tanggal) = ?
                        ) AS nilaiLalu
                    FROM transaksi_lo AS lo INNER JOIN ms_rek5 AS rek ON LEFT(kode_rekening,8) = rek.kd_rek5
                    WHERE kode_skpd = ? AND MONTH(tanggal) <= ?
                    AND YEAR(tanggal) = ? AND LEFT(kode_rekening,4) = $rekening3
                    GROUP BY rek.kd_rek5, rek.nm_rek5,kode_skpd
                    UNION ALL SELECT
                        rek.kd_rek6 AS kode_rekening,
                        rek.nm_rek6,
                        ISNULL(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE debet-kredit END ),0) AS nilai,
                        (
                            SELECT ISNULL(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE debet-kredit END ),0) FROM transaksi_lo AS loLalu WHERE loLalu.kode_rekening = rek.kd_rek6  AND kode_skpd =  lo.kode_skpd AND YEAR(tanggal) = ?
                        ) AS nilaiLalu
                    FROM transaksi_lo AS lo
                        INNER JOIN ms_rek6 AS rek ON rek.kd_rek6 = lo.kode_rekening
                    WHERE kode_skpd = ? AND MONTH(tanggal) <= ?
                    AND YEAR(tanggal) = ? AND LEFT(kode_rekening,4) = $rekening3
                    GROUP BY rek.kd_rek6, rek.nm_rek6,kode_skpd 
                ) AS source ORDER BY kode_rekening";

                $result =  $this->db->query($queryObject, [$tahunAnggaranLalu, $kd_skpd, $cbulan, $tahunAnggaran, $tahunAnggaranLalu, $kd_skpd, $cbulan, $tahunAnggaran, $tahunAnggaranLalu, $kd_skpd, $cbulan, $tahunAnggaran])->result();

                foreach ($result as $objek) {
                    $index += 1;
                    $objekIni = $this->support->rp_minus($objek->nilai);
                    $objekLalu = $this->support->rp_minus($objek->nilaiLalu);
                    $selisihObjek = $this->support->rp_minus($objek->nilai-$objek->nilaiLalu);
                    $kodeRekening = $objek->kode_rekening;
                    if(in_array(0,[$objek->nilai, $objek->nilaiLalu])) {
                        $persenObjek = "0.00";
                    } else {
                        $persenObjek = $this->support->currencyFormat($objek->nilai/$objek->nilaiLalu * 100);
                    }

                    $length = strlen($objek->kode_rekening);
                    $style = [
                        12 => '175px;',
                        8 => '150px;',
                        6 => '125px;'
                    ];

                    $cetakLo .= "<tr>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$index</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black; padding-left:10px\" width=\"5%\">$kodeRekening</td>
                                <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none; padding-left: $style[$length] \" width=\"27%\">$objek->nama_rekening</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$objekIni</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$objekLalu</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$selisihObjek</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persenObjek</td>
                            </tr>";
                }
            }
        }
        // end mapping
        $cetakLo .= "</table>";
        $cetakLo .= $this->_sign($nip, $tanggal);
        $data['prev'] = $cetakLo;
        $data['sikap'] = 'preview';
        $judul  = ("LO KONSOL SKPD $cbulan");
        $this->template->set('title', 'LO KONSOL UNIT $cbulan');
        switch ($pilih) {
            case 0;
                $this->tukd_model->_mpdf('', $cetakLo, 10, 5, 10, '0');
                echo $cetakLo;
                break;
            case 1;
                // $this->tukd_model->_mpdf('',$cetakLo,10,10,10,'0');
                echo "<title>LO KONSOL SKPD $cbulan</title>";
                echo $cetakLo;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }
     // end cetak

    function ctk_lpe_pemda_org($cbulan = "", $kd_skpd = "", $cetak = 1)
    {
        $id = $this->session->userdata('kdskpd');
        $thn_ang = $this->session->userdata('pcThang');
        $bulan   = $cbulan;
        $id1     = $this->session->userdata('kdskpd');
        $nmskpd = $this->tukd_model->get_nama($kd_skpd, 'nm_org', 'ms_organisasi', 'kd_org');
        $nm_skpd = strtoupper($nmskpd);
        $thn_ang_1 = $thn_ang - 1;

        $skpd = "AND kd_skpd='$id1'";
        $skpd1 = "AND b.kd_skpd='$id1'";

        // UPDATE LPE TAHUN LALU

        $trhju = 'trhju_pkd';
        $trdju = 'trdju_pkd';
        $sqllo10 = "select sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7') and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'";
        $querylo10 = $this->db->query($sqllo10);
        $pen8 = $querylo10->row();
        $pen_lalu8 = $pen8->nilai;
        $pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

        $sqllo12 = "select sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8') and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'";
        $querylo12 = $this->db->query($sqllo12);
        $bel10 = $querylo12->row();
        $bel_lalu10 = $bel10->nilai;
        $bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");
        $ekuitas = '310101010001';
        $sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_ll1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_ll2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_ll3  = $row003->thn_m1;
        }


        $query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM $trdju a INNER JOIN $trhju b 
		  ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$ekuitas' AND YEAR(b.tgl_voucher)<'$thn_ang'
		  and b.tabel=1 and reev=0 and left(kd_skpd," . LENGTH_KDSKPD . ") ='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'");
        foreach ($query3->result_array() as $res2) {
            $debet = $res2['debet'];
            $kredit = $res2['kredit'];
        }

        $real = $kredit - $debet + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;
        //    created by henri_tb
        $sqllo9 = "select sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7') and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "select sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "select sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8') and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "select sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'";
        $querylo12 = $this->db->query($sqllo12);
        $bello10 = $querylo12->row();
        $bel_lo_lalu10 = $bello10->nilai;
        $bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

        $surplus_lo3 = $pen_lo7 - $bel_lo9;

        $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

        $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;


        $sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_lalu1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_lalu2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_lalu3  = $row003->thn_m1;
        }

        $sal_awal = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;

        $sql = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'"; //aba

        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $nilaiDR  = $row001->thn_m1;
        }

        $sqllpe1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'"; //Henri_TB

        $hasil = $this->db->query($sqllpe1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $nilailpe1  = $row002->thn_m1;
        }

        $sqllpe2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_skpd," . LENGTH_KDSKPD . ")='$kd_skpd' AND kd_skpd<>'" . SKPD_BKD . "'"; //Henri_TB

        $hasil = $this->db->query($sqllpe2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $nilailpe2  = $row003->thn_m1;
        }

        $sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2;

        if ($surplus_lo_lalu3 < 0) {
            $lo15 = "(";
            $surplus_lo_lalu3 = $surplus_lo_lalu3 * -1;
            $lo16 = ")";
        } else {
            $lo15 = "";
            $lo16 = "";
        }
        $surplus_lo_lalu31 = number_format($surplus_lo_lalu3, "2", ",", ".");

        if ($selisih_surplus_lo3 < 0) {
            $lo17 = "(";
            $selisih_surplus_lo3 = $selisih_surplus_lo3 * -1;
            $lo18 = ")";
        } else {
            $lo17 = "";
            $lo18 = "";
        }
        $selisih_surplus_lo31 = number_format($selisih_surplus_lo3, "2", ",", ".");

        if ($lpe_lalu1 < 0) {
            $lalu1 = "(";
            $lpe_lalu1 = $lpe_lalu1 * -1;
            $lalu2 = ")";
        } else {
            $lalu1 = "";
            $lpe_lalu1;
            $lalu2 = "";
        }

        if ($lpe_lalu2 < 0) {
            $lalu3 = "(";
            $lpe_lalu2 = $lpe_lalu2 * -1;
            $lalu4 = ")";
        } else {
            $lalu3 = "";
            $lpe_lalu2;
            $lalu4 = "";
        }

        if ($lpe_lalu3 < 0) {
            $lalu5 = "(";
            $lpe_lalu3 = $lpe_lalu3 * -1;
            $lalu6 = ")";
        } else {
            $lalu5 = "";
            $lpe_lalu3;
            $lalu6 = "";
        }

        if ($nilaiDR < 0) {
            $l000 = "(";
            $nilaiDR = $nilaiDR * -1;
            $p000 = ")";
        } else {
            $l000 = "";
            $nilaiDR;
            $p000 = "";
        }

        if ($nilailpe1 < 0) {
            $l001 = "(";
            $nilailpe1 = $nilailpe1 * -1;
            $p001 = ")";
        } else {
            $l001 = "";
            $nilailpe1;
            $p001 = "";
        }

        if ($nilailpe2 < 0) {
            $l002 = "(";
            $nilailpe2 = $nilailpe2 * -1;
            $p002 = ")";
        } else {
            $l002 = "";
            $nilailpe2;
            $p002 = "";
        }

        if ($surplus_lo3 < 0) {
            $lo13 = "(";
            $surplus_lo3 = $surplus_lo3 * -1;
            $lo14 = ")";
        } else {
            $lo13 = "";
            $lo14 = "";
        }
        $surplus_lo31 = number_format($surplus_lo3, "2", ",", ".");

        if ($sal_akhir < 0) {
            $c = "(";
            $sal_akhir = $sal_akhir * -1;
            $d = ")";
        } else {
            $c = "";
            $sal_akhir;
            $d = "";
        }

        if ($sal_awal < 0) {
            $c1 = "(";
            $sal_awal = $sal_awal * -1;
            $d1 = ")";
        } else {
            $c1 = "";
            $sal_awal;
            $d1 = "";
        }

        if ($real < 0) {
            $cx = "(";
            $real = $real * -1;
            $dx = ")";
        } else {
            $cx = "";
            $real;
            $dx = "";
        }

        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);

        $sclient = $this->akuntansi_model->get_sclient();

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
						<tr>
							 <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong> </td>                         
						</tr>
			  <tr>
							 <td align=\"center\"><strong>$nm_skpd</strong></td>
						</tr>
						<tr>
							 <td align=\"center\"><strong>LAPORAN PERUBAHAN EKUITAS</strong></td>
						</tr>                    
						<tr>
							 <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1</strong></td>
						</tr>
			  <tr>
							 <td align=\"center\"><strong>&nbsp</strong></td>
						</tr>
					  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
						 <thead>                       
							<tr><td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>                            
								<td  bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
								<td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
								<td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
							</tr>
							
						 </thead>
						 <tfoot>
							<tr>
								<td style=\"border-top: none;\"></td>
								<td style=\"border-top: none;\"></td>
								<td style=\"border-top: none;\"></td>
								<td style=\"border-top: none;\"></td>                        
							 </tr>
						 </tfoot>
					   
						 <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>                            
								<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
								<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
								<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
							</tr>";

        $sql = "SELECT * FROM map_lpe_permen77_SKPD  ORDER BY seq";

        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row) {

            $kd_rek   = $row->nor;
            $parent   = $row->parent;
            $nama     = $row->uraian;
            $nilai_1    = $row->thn_m1;


            switch ($kd_rek) {
                case 1:
                    $cRet .= "<tr>
														  <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
														  <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
														  <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> $c1" . number_format($sal_awal, "2", ",", ".") . "$d1</td>
														  <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$cx" . number_format($real, "2", ",", ".") . "$dx</td>
														 </tr>";

                    break;
                case 2:
                    $cRet .= "<tr>
														  <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
														  <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
														  <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> $lo13" . number_format($surplus_lo3, "2", ",", ".") . "$lo14</td>
														  <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lo15" . number_format($surplus_lo_lalu3, "2", ",", ".") . "$lo16</td>
														 </tr>";

                    break;
                case 3:
                    $cRet .= "<tr>
														  <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
														  <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
														  <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"></td>
														  <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"></td>
														 </tr>";

                    break;
                case 4:
                    $cRet .= "<tr>
														  <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
														  <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
														  <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$l001" . number_format($nilailpe1, "2", ",", ".") . "$p001</td>
														  <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lalu1" . number_format($lpe_lalu2, "2", ",", ".") . "$lalu2</td>
														 </tr>";
                    break;
                case 5:
                    $cRet .= "<tr>
														  <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
														  <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
														  <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$l000" . number_format($nilaiDR, "2", ",", ".") . "$p000</td>
														  <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lalu3" . number_format($lpe_lalu1, "2", ",", ".") . "$lalu4</td>
														 </tr>";
                    break;
                case 6:
                    $cRet .= "<tr>
														  <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
														  <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
														  <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$l002" . number_format($nilailpe2, "2", ",", ".") . "$p002</td>
														  <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lalu5" . number_format($lpe_lalu3, "2", ",", ".") . "$lalu6</td>
														 </tr>";
                    break;
                case 7:
                    $cRet .= "<tr>
														  <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
														  <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
														  <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$c" . number_format($sal_akhir, "2", ",", ".") . "$d</td>
														  <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$c1" . number_format($sal_awal, "2", ",", ".") . "$d1</td>
														 </tr>";
            }
        }

        $cRet .= '</table>';

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul = ("LPE KONSOL SKPD $cbulan");
        $this->template->set('title', 'LPE KONSOL SKPD $cbulan');
        switch ($cetak) {
            case 0;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                echo $cRet;
                break;
            case 1;
                echo "<title>LPE KONSOL $cbulan</title>";
                echo $cRet;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }

    // KONSOL
    function cetakKonsolidasiJenis()
    {
        $bulan = $this->input->get('bulan');
        $nip = str_replace('n', ' ', $this->input->get('ttd'));
        $tanggalTtd = $this->input->get('tanggal');
        $tanggal = $this->tukd_model->tanggal_format_indonesia($tanggalTtd);
        $cetakLo = "";
        $cetakLo .= $this->_client("", $bulan);
        $cetakLo .= $this->_headerTableLo();
        $tahunAnggaran = $this->session->userdata('pcThang');
        $tahunAnggaranLalu = $tahunAnggaran-1;
        $pilih = $this->input->get('pilih');

         // start mapping
        $mapping  = $this->db->query("SELECT seq, nor, uraian, bold,isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak,parent,kode FROM map_lo_pemda GROUP BY seq, nor, uraian, bold, isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet'),parent, kode ORDER BY seq")
            ->result();

        $styles = [
            0 => "",
            1 => "padding-left: 25px;",
            2 => "padding-left: 50px;",
            3 => "padding-left: 75px;",
            4 => "padding-left: 100px;",
            5 => "padding-left: 125px;",
            6 => "padding-left: 125px;",
            7 => "padding-left: 125px;",
        ];
        
        // surpulus operasi
        $queryOperasi = "SELECT ISNULL(ABS(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE 0 END )),0) - ISNULL(ABS(SUM(CASE WHEN LEFT(kode_rekening,1) = 8 THEN debet-kredit ELSE 0 END )),0) AS nilai FROM transaksi_lo WHERE MONTH(tanggal) <= ? AND YEAR(tanggal) = ?";
        $resultOperasiTahunIni = $this->db->query($queryOperasi,[$bulan,$tahunAnggaran])->row();
        $resultOperasiTahunLalu =  $this->db->query($queryOperasi,[12, $tahunAnggaranLalu])->row();
        $surplusTahunIni = $resultOperasiTahunIni ? $resultOperasiTahunIni->nilai : 0;
        $surplusTahunLalu = $resultOperasiTahunLalu ? $resultOperasiTahunLalu->nilai : 0;
        $kenaikanSurplus = $surplusTahunIni-$surplusTahunLalu;
        $persenSurplus = ($surplusTahunIni == 0 || $surplusTahunLalu ==0 ) ? 0 : (abs($surplusTahunIni) / abs($surplusTahunLalu)) * 100;

         // non operasional
        $queryNonOperasi = "SELECT 
                ISNULL(
                    -- surplus non operasional
                    CASE WHEN LEFT(kode_rekening,1) = 7 THEN ABS(SUM(kredit-debet)) ELSE 0 END -
                    -- defisit non operasional
                    CASE WHEN LEFT(kode_rekening,1) = 8 THEN ABS(SUM(debet-kredit)) ELSE 0 END
                ,0) AS nilai
            FROM transaksi_lo WHERE MONTH(tanggal) <= ? AND YEAR(tanggal) = ? AND LEFT(kode_rekening,2) IN (74,83)
            GROUP BY LEFT(kode_rekening,1)
        ";
        $resultNonOperasiTahunIni = $this->db->query($queryNonOperasi,[$bulan, $tahunAnggaran])->row();
        $resultNonOperasiTahunLalu = $this->db->query($queryNonOperasi,[12, $tahunAnggaranLalu])->row();
        $nonOperasiTahunIni = $resultNonOperasiTahunIni ? $resultNonOperasiTahunIni->nilai : 0;
        $nonOperasiTahunLalu = $resultNonOperasiTahunLalu ? $resultNonOperasiTahunLalu->nilai : 0;

        // selisih surplus non operasional dan defisit operasion
        $sebelumPosTahunIni = $surplusTahunIni + $nonOperasiTahunIni;
        $sebelumPosTahunLalu = $surplusTahunLalu + $nonOperasiTahunLalu;
        $kenaikanSebelumPos =abs( $sebelumPosTahunIni)-abs($sebelumPosTahunLalu);
        $persenSebelumPos = abs( $sebelumPosTahunIni)/abs($sebelumPosTahunLalu) * 100;

        foreach ($mapping as $key => $map) {
            $tahunIni = "";
            $tahunLalu ="";
            $kenaikan = "";
            $persen= "";
            $kodeRekening = "";

            if ($map->bold == 5) {
                $tahunIni = $surplusTahunIni < 0 ? "(". $this->support->currencyFormat(abs($surplusTahunIni)) .")" : $this->support->currencyFormat($surplusTahunIni);
                $tahunLalu = $surplusTahunLalu < 0 ? "(". $this->support->currencyFormat(abs($surplusTahunLalu)) .")" : $this->support->currencyFormat($surplusTahunLalu);
                $kenaikan = $this->support->rp_minus($kenaikanSurplus);
                $persen = $this->support->currencyFormat($persenSurplus);
            }

            if(in_array($map->bold,[6,7])) {
                $tahunIni = $this->support->rp_minus($sebelumPosTahunIni);
                $tahunLalu = $this->support->rp_minus($sebelumPosTahunLalu);
                $kenaikan = $this->support->rp_minus($kenaikanSebelumPos);
                $persen = $this->support->currencyFormat(abs($persenSebelumPos));
            }

            if($map->cetak != "" && in_array($map->bold,[3,4])) {
                $rekening3 = $map->kode_1 === '-' ? "0" : $map->kode_1;
                $rekening4 = $map->kode_2 === '-' ? "0" : $map->kode_2;
                $rekening5 = $map->kode_3 === '-' ? "0" : $map->kode_3;
                $query = "SELECT ISNULL(ABS(SUM(debet-kredit)),0) AS nilai FROM transaksi_lo 
                    WHERE (LEFT(kode_rekening,4) IN ($rekening3)  OR LEFT(kode_rekening,6) 
                    IN ($rekening4)  OR LEFT(kode_rekening,8) IN ($rekening5) ) AND MONTH(tanggal) <= ?  AND YEAR(tanggal) = ? ";
                $realisasiTahunIni = $this->db->query($query,[$bulan, $tahunAnggaran])->row();
                $realisasiTahunLalu = $this->db->query($query,[12, $tahunAnggaranLalu])->row();
                if($map->seq == 410) {
                    $nilaiTahunIni = $nonOperasiTahunIni;
                    $nilaiTahunLalu = $nonOperasiTahunLalu;
                } else {
                    $nilaiTahunIni = $realisasiTahunIni ? $realisasiTahunIni->nilai : 0; 
                    $nilaiTahunLalu = $realisasiTahunLalu ? $realisasiTahunLalu->nilai : 0;
                }
                $tahunIni = $this->support->rp_minus($nilaiTahunIni);
                $tahunLalu = $this->support->rp_minus($nilaiTahunLalu);
                $kenaikan = $this->support->rp_minus($nilaiTahunIni - $nilaiTahunLalu);
                if($nilaiTahunIni == 0 || $nilaiTahunLalu == 0) {
                    $persen = "0,00";
                } else {
                    $persen = $this->support->currencyFormat($nilaiTahunIni / $nilaiTahunLalu *100);
                }
                $kodeRekening = $map->bold == 3 || $rekening3 != 0 ? substr($rekening3, 1,4) : "";
            }

            if($map->bold != 3) {
                $no ="<strong>".($key+1)."</strong>";
                $nama = "<strong>$map->uraian</strong>";
                $tahunLalu = "<strong>$tahunLalu</strong>";
                $tahunIni = "<strong>$tahunIni</strong>";
                $kenaikan = "<strong>$kenaikan</strong>";
                $persen = "<strong>$persen</strong>";
            } else {
                $no = $key+1;
                $nama = $map->uraian;
            }

            $style = $styles[$map->bold];
            $cetakLo .= "<tr>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$kodeRekening</td>
                            <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none; $style\" width=\"27%\">$nama</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$tahunIni</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$tahunLalu</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$kenaikan</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persen</td>
                        </tr>";
        }

        // end mapping

        $cetakLo .= "</table>";
        $cetakLo .= $this->_sign($nip, $tanggal);
        $data['prev'] = $cetakLo;
        $data['sikap'] = 'preview';
        $judul  = ("LO KONSOL SKPD $cbulan");
        $this->template->set('title', 'LO KONSOL UNIT $cbulan');
        switch ($pilih) {
            case 0;
                $this->tukd_model->_mpdf('', $cetakLo, 10, 5, 10, '0');
                echo $cetakLo;
                break;
            case 1;
                // $this->tukd_model->_mpdf('',$cetakLo,10,10,10,'0');
                echo "<title>LO KONSOL SKPD $cbulan</title>";
                echo $cetakLo;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }

    // KONSOL PER SUB RINCI
    function cetakKonsolidasiSubRinci()
    {
        $bulan = $this->input->get('bulan');
        $nip = str_replace('n', ' ', $this->input->get('ttd'));
        $tanggalTtd = $this->input->get('tanggal');
        $tanggal = $this->tukd_model->tanggal_format_indonesia($tanggalTtd);
        $cetakLo = "";
        $cetakLo .= $this->_client("", $bulan);
        $cetakLo .= $this->_headerTableLo();
        $tahunAnggaran = $this->session->userdata('pcThang');
        $tahunAnggaranLalu = $tahunAnggaran-1;
        $pilih = $this->input->get('pilih');
        $cetakLo = "";
        $cetakLo .= $this->_client("", $bulan);
        $cetakLo .= $this->_headerTableLo();
        // start mapping
        $mapping  = $this->db->query("SELECT seq, nor, uraian, bold,isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak,parent,kode FROM map_lo_pemda GROUP BY seq, nor, uraian, bold, isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet'),parent, kode ORDER BY seq")
            ->result();

        $styles = [
            0 => "",
            1 => "padding-left: 25px;",
            2 => "padding-left: 50px;",
            3 => "padding-left: 75px;",
            4 => "padding-left: 100px;",
            5 => "padding-left: 125px;",
            6 => "padding-left: 125px;",
            7 => "padding-left: 125px;",
        ];
        
        // surpulus operasi
        $queryOperasi = "SELECT ISNULL(ABS(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE 0 END )),0) - ISNULL(ABS(SUM(CASE WHEN LEFT(kode_rekening,1) = 8 THEN debet-kredit ELSE 0 END )),0) AS nilai FROM transaksi_lo WHERE MONTH(tanggal) <= ? AND YEAR(tanggal) = ? AND LEFT(kode_rekening,2) != 83";
        $resultOperasiTahunIni = $this->db->query($queryOperasi,[$bulan, $tahunAnggaran])->row();
        $resultOperasiTahunLalu =  $this->db->query($queryOperasi,[12, $tahunAnggaranLalu])->row();
        $surplusTahunIni = $resultOperasiTahunIni ? $resultOperasiTahunIni->nilai : 0;
        $surplusTahunLalu = $resultOperasiTahunLalu ? $resultOperasiTahunLalu->nilai : 0;
        $kenaikanSurplus = $surplusTahunIni-$surplusTahunLalu;
        $persenSurplus = ($surplusTahunIni == 0 || $surplusTahunLalu ==0 ) ? 0 : (abs($surplusTahunIni) / abs($surplusTahunLalu)) * 100;

        // non operasional
        $queryNonOperasi = "SELECT 
                ISNULL(
                    -- surplus non operasional
                    CASE WHEN LEFT(kode_rekening,1) = 7 THEN ABS(SUM(kredit-debet)) ELSE 0 END -
                    -- defisit non operasional
                    CASE WHEN LEFT(kode_rekening,1) = 8 THEN ABS(SUM(debet-kredit)) ELSE 0 END
                ,0) AS nilai
            FROM transaksi_lo WHERE MONTH(tanggal) <= ? AND YEAR(tanggal) = ? AND LEFT(kode_rekening,2) IN (74,83)
            GROUP BY LEFT(kode_rekening,1)
        ";
        $resultNonOperasiTahunIni = $this->db->query($queryNonOperasi,[$bulan, $tahunAnggaran])->row();
        $resultNonOperasiTahunLalu = $this->db->query($queryNonOperasi,[12, $tahunAnggaranLalu])->row();
        $nonOperasiTahunIni = $resultNonOperasiTahunIni ? $resultNonOperasiTahunIni->nilai : 0;
        $nonOperasiTahunLalu = $resultNonOperasiTahunLalu ? $resultNonOperasiTahunLalu->nilai : 0;

        // selisih surplus non operasional dan defisit operasion
        $sebelumPosTahunIni = $surplusTahunIni + $nonOperasiTahunIni;
        $sebelumPosTahunLalu = $surplusTahunLalu + $nonOperasiTahunLalu;
        $kenaikanSebelumPos =abs( $sebelumPosTahunIni)-abs($sebelumPosTahunLalu);
        $persenSebelumPos = abs( $sebelumPosTahunIni)/abs($sebelumPosTahunLalu) * 100;

        $index = 0;
        foreach ($mapping as $key => $map) {
            $index += 1;
            $tahunIni = "";
            $tahunLalu ="";
            $kenaikan = "";
            $persen= "";
            $kodeRekening = "";

            if ($map->bold == 5) {
                $tahunIni = $surplusTahunIni < 0 ? "(". $this->support->currencyFormat(abs($surplusTahunIni)) .")" : $this->support->currencyFormat($surplusTahunIni);
                $tahunLalu = $surplusTahunLalu < 0 ? "(". $this->support->currencyFormat(abs($surplusTahunLalu)) .")" : $this->support->currencyFormat($surplusTahunLalu);
                $kenaikan = $this->support->rp_minus($kenaikanSurplus);
                $persen = $this->support->currencyFormat($persenSurplus);
            }

            if(in_array($map->bold,[6,7])) {
                $tahunIni = $this->support->rp_minus($sebelumPosTahunIni);
                $tahunLalu = $this->support->rp_minus($sebelumPosTahunLalu);
                $kenaikan = $this->support->rp_minus($kenaikanSebelumPos);
                $persen = $this->support->currencyFormat(abs($persenSebelumPos));
            }

            if($map->cetak != "" && in_array($map->bold,[3,4])) {
                $rekening3 = $map->kode_1 === '-' ? "0" : $map->kode_1;
                $rekening4 = $map->kode_2 === '-' ? "0" : $map->kode_2;
                $rekening5 = $map->kode_3 === '-' ? "0" : $map->kode_3;
                $query = "SELECT ISNULL(ABS(SUM(debet-kredit)),0) AS nilai FROM transaksi_lo 
                    WHERE (LEFT(kode_rekening,4) IN ($rekening3)  OR LEFT(kode_rekening,6) 
                    IN ($rekening4)  OR LEFT(kode_rekening,8) IN ($rekening5) ) AND MONTH(tanggal) <= ?  AND YEAR(tanggal) = ? ";
                $realisasiTahunIni = $this->db->query($query,[$bulan, $tahunAnggaran])->row();
                $realisasiTahunLalu = $this->db->query($query,[12, $tahunAnggaranLalu])->row();
                if($map->seq == 410) {
                    $nilaiTahunIni = $nonOperasiTahunIni;
                    $nilaiTahunLalu = $nonOperasiTahunLalu;
                } else {
                    $nilaiTahunIni = $realisasiTahunIni ? $realisasiTahunIni->nilai : 0; 
                    $nilaiTahunLalu = $realisasiTahunLalu ? $realisasiTahunLalu->nilai : 0;
                }
                $tahunIni = $this->support->rp_minus($nilaiTahunIni);
                $tahunLalu = $this->support->rp_minus($nilaiTahunLalu);
                $kenaikan = $this->support->rp_minus($nilaiTahunIni - $nilaiTahunLalu);
                if($nilaiTahunIni == 0 || $nilaiTahunLalu == 0) {
                    $persen = "0,00";
                } else {
                    $persen = $this->support->currencyFormat($nilaiTahunIni / $nilaiTahunLalu *100);
                }
            }

            if($map->bold != 3) {
                $no ="<strong>".($index)."</strong>";
                $nama = "<strong>$map->uraian</strong>";
                $tahunLalu = "<strong>$tahunLalu</strong>";
                $tahunIni = "<strong>$tahunIni</strong>";
                $kenaikan = "<strong>$kenaikan</strong>";
                $persen = "<strong>$persen</strong>";
            } else {
                $no = $index;
                $nama = $map->uraian;
            }
            if ($map->bold == 3) {
                $kodeRekening = substr($rekening3 ? $rekening3 : $rekening4, 1,4) ? substr($rekening3 ? $rekening3 : $rekening4, 1,4) : substr($rekening5, 1,4) ;
            } else {
                // $kodeRekening = 0;
            }
            $style = $styles[$map->bold];
            $cetakLo .= "<tr>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black; padding-left: 10px; padding-left: 10px\" width=\"5%\">$kodeRekening</td>
                            <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none; $style\" width=\"27%\">$nama</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$tahunIni</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$tahunLalu</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$kenaikan</td>
                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persen</td>
                        </tr>";
            if ($map->bold == 3) {
                // objek
                $queryObject = "SELECT * FROM (
                    SELECT
                        rek.kd_rek4 AS kode_rekening,
                        rek.nm_rek4 as nama_rekening,
                        ISNULL(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE debet-kredit END ),0) AS nilai,
                        (
                            SELECT ISNULL(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE debet-kredit END ),0) FROM transaksi_lo AS loLalu WHERE LEFT(loLalu.kode_rekening,6) = rek.kd_rek4 AND YEAR(tanggal) = ?
                        ) AS nilaiLalu
                    FROM transaksi_lo AS lo INNER JOIN ms_rek4 AS rek ON LEFT(kode_rekening,6) = rek.kd_rek4
                    WHERE MONTH(tanggal) <= ?
                    AND YEAR(tanggal) = ? AND rek.kd_rek3 = $rekening3
                    GROUP BY rek.kd_rek4, rek.nm_rek4 UNION ALL
                    SELECT
                        rek.kd_rek5 AS kode_rekening,
                        rek.nm_rek5 as nama_rekening,
                        ISNULL(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE debet-kredit END ),0) AS nilai,
                        (
                            SELECT ISNULL(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE debet-kredit END ),0) FROM transaksi_lo AS loLalu WHERE LEFT(loLalu.kode_rekening,8) = rek.kd_rek5 AND YEAR(tanggal) = ?
                        ) AS nilaiLalu
                    FROM transaksi_lo AS lo INNER JOIN ms_rek5 AS rek ON LEFT(kode_rekening,8) = rek.kd_rek5
                    WHERE MONTH(tanggal) <= ?
                    AND YEAR(tanggal) = ? AND LEFT(kode_rekening,4) = $rekening3
                    GROUP BY rek.kd_rek5, rek.nm_rek5
                    UNION ALL SELECT
                        rek.kd_rek6 AS kode_rekening,
                        rek.nm_rek6,
                        ISNULL(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE debet-kredit END ),0) AS nilai,
                        (
                            SELECT ISNULL(SUM(CASE WHEN LEFT(kode_rekening,1) = 7 THEN kredit-debet ELSE debet-kredit END ),0) FROM transaksi_lo AS loLalu WHERE loLalu.kode_rekening = rek.kd_rek6  AND YEAR(tanggal) = ?
                        ) AS nilaiLalu
                    FROM transaksi_lo AS lo
                        INNER JOIN ms_rek6 AS rek ON rek.kd_rek6 = lo.kode_rekening
                    WHERE  MONTH(tanggal) <= ?
                    AND YEAR(tanggal) = ? AND LEFT(kode_rekening,4) = $rekening3
                    GROUP BY rek.kd_rek6, rek.nm_rek6 
                ) AS source ORDER BY kode_rekening";

                $result =  $this->db->query($queryObject, [$tahunAnggaranLalu, $bulan, $tahunAnggaran, $tahunAnggaranLalu, $bulan, $tahunAnggaran, $tahunAnggaranLalu, $bulan, $tahunAnggaran])->result();

                foreach ($result as $objek) {
                    $index += 1;
                    $objekIni = $this->support->rp_minus($objek->nilai);
                    $objekLalu = $this->support->rp_minus($objek->nilaiLalu);
                    $selisihObjek = $this->support->rp_minus($objek->nilai-$objek->nilaiLalu);
                    $kodeRekening = $objek->kode_rekening;
                    if(in_array(0,[$objek->nilai, $objek->nilaiLalu])) {
                        $persenObjek = "0.00";
                    } else {
                        $persenObjek = $this->support->currencyFormat($objek->nilai/$objek->nilaiLalu * 100);
                    }

                    $length = strlen($objek->kode_rekening);
                    $style = [
                        12 => '175px;',
                        8 => '150px;',
                        6 => '125px;'
                    ];

                    $cetakLo .= "<tr>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$index</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black; padding-left:10px\" width=\"5%\">$kodeRekening</td>
                                <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none; padding-left: $style[$length] \" width=\"27%\">$objek->nama_rekening</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$objekIni</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$objekLalu</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$selisihObjek</td>
                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persenObjek</td>
                            </tr>";
                }
            }
        }
        // end mapping
        $cetakLo .= "</table>";
        $cetakLo .= $this->_sign($nip, $tanggal);
        $data['prev'] = $cetakLo;
        $data['sikap'] = 'preview';
        $judul  = ("LO KONSOL SKPD $bulan");
        $this->template->set('title', 'LO KONSOL UNIT $bulan');
        switch ($pilih) {
            case 0;
                $this->tukd_model->_mpdf('', $cetakLo, 10, 5, 10, '0');
                echo $cetakLo;
                break;
            case 1;
                // $this->tukd_model->_mpdf('',$cetakLo,10,10,10,'0');
                echo "<title>LO KONSOL SKPD $bulan</title>";
                echo $cetakLo;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }





    function rpt_neraca_pemda_org_obyek($cbulan="", $kd_skpd="", $cetak=1){
        //$bulan   = $_REQUEST['tgl1'];
          $id     = $this->session->userdata('kdskpd');
          $thn_ang  = $this->session->userdata('pcThang');
          $thn_ang_1  = $thn_ang-1;
          $bulan   = $cbulan;
          $cbulan<10 ? $xbulan = "0$cbulan" : $xbulan=$cbulan;
    
          $sqlsc="SELECT nm_org FROM ms_organisasi where kd_org='$kd_skpd' ";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $nmskpd  = $rowsc->nm_org;
                    } 
            
          $nm_skpd  = strtoupper ($nmskpd);
          
      /*           $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                    {
                       
                        $tgl=$rowsc->tgl_rka;
                        $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
                        $kab     = $rowsc->kab_kota;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                    } 
    
          $sqldns="SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE a.kd_skpd='$skpd'  ";
                     $sqlskpd=$this->db->query($sqldns);
                     foreach ($sqlskpd->result() as $rowdns)
                    {
                        $kd_urusan=$rowdns->kd_u;                    
                        $nm_urusan= $rowdns->nm_u;
                        $kd_skpd  = $rowdns->kd_sk;
                        $nm_skpd  = $rowdns->nm_sk;
                    } 
    */        
      $modtahun= $thn_ang%4;
       
       if ($modtahun = 0){
            $nilaibulan=".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";}
                else {
            $nilaibulan=".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";}
       
       $arraybulan=explode(".",$nilaibulan);
          
          $cRet='';
          
          $cRet ="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
              <tr>
                             <td align=\"center\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
                        </tr>
                        <tr>
                             <td align=\"center\"><strong>$nm_skpd</strong></td>                         
                        </tr>
              <TR>
                <td align=\"center\"><strong>NERACA</strong></td>
              </TR>
              <TR>
                <td align=\"center\"><strong>PER $arraybulan[$bulan] $thn_ang DAN $thn_ang_1 </strong></td>
              </TR>
              </TABLE><br>";
    
                $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                         <thead>                       
                            <tr>
                  <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
                                <td colspan =\"7\"bgcolor=\"#CCCCCC\" width=\"55%\" align=\"center\"><b>URAIAN</b></td>
                                <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                                <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>                            
                            </tr>
                            
                         </thead>
                         <tfoot>
                            <tr>
                  <td style=\"border-top: none;\"></td>
                                <td colspan =\"7\"style=\"border-top: none;\"></td>
                                <td style=\"border-top: none;\"></td>
                                <td style=\"border-top: none;\"></td>                                             
                             </tr>
                         </tfoot>
                       
                         <tr> <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
                  <td colspan =\"7\"style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"55%\" align=\"center\">&nbsp;</td>                            
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                               
                            </tr>";
          
          
          //level 1
    
    // Created by Henri_TB
    
          $sqllo10="SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7')and LEFT(b.kd_skpd,17)='$kd_skpd'";
                        $querylo10= $this->db->query($sqllo10);
                        $pen8 = $querylo10->row();
                        $pen_lalu8 = $pen8->nilai;
                        $pen_lalu81= number_format($pen8->nilai,"2",",",".");
        
          $sqllo12="SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8')and LEFT(b.kd_skpd,17)='$kd_skpd'";
                        $querylo12= $this->db->query($sqllo12);
                        $bel10 = $querylo12->row();
                        $bel_lalu10 = $bel10->nilai;
                        $bel_lalu101= number_format($bel10->nilai,"2",",",".");
    
          $sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
              inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1 and LEFT(a.kd_skpd,17)='$kd_skpd'";//aba
                                
                        $hasil = $this->db->query($sql_lalu); 
                        $nawal = 0 ;
                        foreach ($hasil->result() as $row001)
                        {
                           $kd_rek   =$row001->nor ;
                           $parent   =$row001->parent;
                           $nama     =$row001->uraian;
                           $lpe_ll1  =$row001->thn_m1;
              }
                    
          $sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
              inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1 and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqllpe_lalu1); 
                        $nawal = 0 ;
                        foreach ($hasil->result() as $row002)
                        {
                           $kd_rek   =$row002->nor ;
                           $parent   =$row002->parent;
                           $nama     =$row002->uraian;
                           $lpe_ll2  =$row002->thn_m1;
              }         
    
          $sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
              inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1 and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqllpe_lalu2); 
                        $nawal = 0 ;
                        foreach ($hasil->result() as $row003)
                        {
                           $kd_rek   =$row003->nor ;
                           $parent   =$row003->parent;
                           $nama     =$row003->uraian;
                           $lpe_ll3  =$row003->thn_m1;
              }
              
          
          $query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM trdju_pkd a INNER JOIN trhju_pkd b 
          ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='310101010001' AND YEAR(b.tgl_voucher)<'$thn_ang'
          and b.tabel=1 and reev=0 and LEFT(b.kd_skpd,17)='$kd_skpd'");  
              foreach($query3->result_array() as $res2){
             $debet3=$res2['debet'];
             $kredit3=$res2['kredit'];
                             
           }
        
        $real=$kredit3-$debet3+$pen_lalu8-$bel_lalu10+$lpe_ll1+$lpe_ll2+$lpe_ll3;
    
    //    created by henri_tb
        $sqllo9="SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7') and LEFT(b.kd_skpd,17)='$kd_skpd'";
                        $querylo9= $this->db->query($sqllo9);
                        $penlo7 = $querylo9->row();
                        $pen_lo7 = $penlo7->nilai;
                        $pen_lo71= number_format($penlo7->nilai,"2",",",".");
            
        $sqllo10="SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and LEFT(b.kd_skpd,17)='$kd_skpd'";
                        $querylo10= $this->db->query($sqllo10);
                        $penlo8 = $querylo10->row();
                        $pen_lo_lalu8 = $penlo8->nilai;
                        $pen_lo_lalu81= number_format($penlo8->nilai,"2",",",".");
        
        $sqllo11="SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8') and LEFT(b.kd_skpd,17)='$kd_skpd'";
                        $querylo11= $this->db->query($sqllo11);
                        $bello9 = $querylo11->row();
                        $bel_lo9 = $bello9->nilai;
                        $bel_lo91= number_format($bello9->nilai,"2",",",".");
        
        $sqllo12="SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and LEFT(b.kd_skpd,17)='$kd_skpd'";
                        $querylo12= $this->db->query($sqllo12);
                        $bello10 = $querylo12->row();
                        $bel_lo_lalu10 = $bello10->nilai;
                        $bel_lo_lalu101= number_format($bello10->nilai,"2",",",".");    
    
              $surplus_lo3 = $pen_lo7 - $bel_lo9;
                        
              $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
                        
              $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
              
          $sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
              inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1 and LEFT(a.kd_skpd,17)='$kd_skpd'";//aba
                                
                        $hasil = $this->db->query($sql_lalu); 
                        $nawal = 0 ;
                        foreach ($hasil->result() as $row001)
                        {
                           $kd_rek   =$row001->nor ;
                           $parent   =$row001->parent;
                           $nama     =$row001->uraian;
                           $lpe_lalu1  =$row001->thn_m1;
              }
                    
          $sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
              inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1 and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqllpe_lalu1); 
                        $nawal = 0 ;
                        foreach ($hasil->result() as $row002)
                        {
                           $kd_rek   =$row002->nor ;
                           $parent   =$row002->parent;
                           $nama     =$row002->uraian;
                           $lpe_lalu2  =$row002->thn_m1;
              }         
    
          $sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
              inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1 and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqllpe_lalu2); 
                        $nawal = 0 ;
                        foreach ($hasil->result() as $row003)
                        {
                           $kd_rek   =$row003->nor ;
                           $parent   =$row003->parent;
                           $nama     =$row003->uraian;
                           $lpe_lalu3  =$row003->thn_m1;
              }
    
        $sal_awal = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;         
    
          $sql = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
              inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and LEFT(a.kd_skpd,17)='$kd_skpd'";//aba
                                
                        $hasil = $this->db->query($sql); 
                        $nawal = 0 ;
                        foreach ($hasil->result() as $row001)
                        {
                           $kd_rek   =$row001->nor ;
                           $parent   =$row001->parent;
                           $nama     =$row001->uraian;
                           $nilaiDR  =$row001->thn_m1;
              }
                    
          $sqllpe1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
              inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqllpe1); 
                        $nawal = 0 ;
                        foreach ($hasil->result() as $row002)
                        {
                           $kd_rek   =$row002->nor ;
                           $parent   =$row002->parent;
                           $nama     =$row002->uraian;
                           $nilailpe1  =$row002->thn_m1;
              }         
    
          $sqllpe2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
              inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqllpe2); 
                        $nawal = 0 ;
                        foreach ($hasil->result() as $row003)
                        {
                           $kd_rek   =$row003->nor ;
                           $parent   =$row003->parent;
                           $nama     =$row003->uraian;
                           $nilailpe2  =$row003->thn_m1;
              }
        
            $sal_akhir=$sal_awal+$surplus_lo3+$nilaiDR+$nilailpe1+$nilailpe2;
    
           $sqlutang_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and year(a.tgl_voucher)<=$thn_ang_1 and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqlutang_lalu); 
                        foreach ($hasil->result() as $row)
                        {
                           $nilaiutang_lalu  =$row->thn_m1;
              }           
    
           
          $sqlkas_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and year(a.tgl_voucher)<=$thn_ang_1 and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqlkas_lalu); 
                        foreach ($hasil->result() as $row)
                        {
                           $rk_ppkd_lalu  =$row->thn_m1;
              }
    
          $sqlskpd_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and year(a.tgl_voucher)<=$thn_ang_1 and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqlskpd_lalu); 
                        foreach ($hasil->result() as $row)
                        {
                           $rk_skpd_lalu  =$row->thn_m1;
              }
          
          
          $sqllcr_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and year(a.tgl_voucher)<=$thn_ang_1 and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqllcr_lalu); 
                        foreach ($hasil->result() as $row)
                        {
                           $lcrx_lalu  =$row->thn_m1;
              }
              
          $sqlast_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and year(a.tgl_voucher)<=$thn_ang_1 and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqlast_lalu); 
                        foreach ($hasil->result() as $row)
                        {
                           $astx_lalu  =$row->thn_m1;
              }
          
          $lcr_lalu   = $lcrx_lalu-$rk_skpd_lalu;
          $ast_lalu   = $astx_lalu-$rk_skpd_lalu;     
          $eku_lalu     = $sal_awal + $rk_ppkd_lalu-$rk_skpd_lalu;          
          $eku_tang_lalu  = $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu-$rk_skpd_lalu;
        
           $sqlutang = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher
          and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqlutang); 
                        foreach ($hasil->result() as $row)
                        {
                           $nilaiutang  =$row->thn_m1;
              }       
          
           $sqlkas = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqlkas); 
                        foreach ($hasil->result() as $row)
                        {
                           $rk_ppkd  =$row->thn_m1;
              }
          
          $sqlskpd = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqlskpd); 
                        foreach ($hasil->result() as $row)
                        {
                           $rk_skpd  =$row->thn_m1;
              }
    
        $sqllcr = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqllcr); 
                        foreach ($hasil->result() as $row)
                        {
                           $lcrx =$row->thn_m1;
              }
              
          $sqlast = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and LEFT(a.kd_skpd,17)='$kd_skpd'";//Henri_TB
                                
                        $hasil = $this->db->query($sqlast); 
                        foreach ($hasil->result() as $row)
                        {
                           $astx  =$row->thn_m1;
              }   
          
          /*$lcr      = $lcrx-$rk_skpd;
          $ast      = $astx-$rk_skpd;
          $eku      = $sal_akhir - $rk_ppkd + $rk_skpd;         
          $eku_tang     = $sal_akhir + $nilaiutang - $rk_ppkd +$rk_skpd;*/          
          /*$rk_skpd    = $rk_skpd-$rk_ppkd;
          $rk_skpd_lalu = $rk_skpd_lalu-$rk_ppkd_lalu;
          $rk_ppkd    = $rk_skpd;
          $rk_ppkd_lalu = $rk_skpd_lalu;*/
          
    
            $lcr    = $lcrx-$rk_skpd;
          $ast    = $astx-$rk_skpd;
          $eku    = $sal_akhir + $rk_ppkd;          
          $eku_tang   = $sal_akhir + $nilaiutang + $rk_ppkd;
    
    
              if ($sal_akhir < 0)
              {
                $c = "("; $sal_akhir = $sal_akhir * -1; $d = ")";
              } else
              {
                $c = ""; $sal_akhir; $d = "";
              }
              
              $sal_akhir1= number_format($sal_akhir,"2",",",".");
            
              if ($sal_awal < 0)
              {
                $c1 = "("; $sal_awal = $sal_awal * -1; $d1 = ")";
              } else
              {
                $c1 = ""; $sal_awal; $d1 = "";
              }   
              
              $sal_awal1= number_format($sal_awal,"2",",",".");
                    
          
        if ($eku_lalu < 0){
                          $min001="("; $eku_lalu=$eku_lalu*-1; $min002=")";
                            }else {
                          $min001=""; $eku_lalu; $min002="";
                            }     
        
        $eku_lalu1=number_format($eku_lalu,"2",",",".");
    
        if ($eku < 0){
                          $min003="("; $eku=$eku*-1; $min004=")";
                            }else {
                          $min003=""; $eku; $min004="";
                            }     
        
        $eku1=number_format($eku,"2",",",".");
          
        if ($eku_tang_lalu < 0){
                          $min005="("; $eku_tang_lalu=$eku_tang_lalu*-1; $min006=")";
                            }else {
                          $min005=""; $eku_tang_lalu; $min006="";
                            }     
        
        $eku_tang_lalu1=number_format($eku_tang_lalu,"2",",",".");
    
        if ($eku_tang < 0){
                          $min007="("; $eku_tang=$eku_tang*-1; $min008=")";
                            }else {
                          $min007=""; $eku_tang; $min008="";
                            }     
        
        $eku_tang1=number_format($eku_tang,"2",",",".");    
    
        if ($rk_ppkd_lalu < 0){
                          $min009="("; $rk_ppkd_lalu=$rk_ppkd_lalu*-1; $min010=")";
                            }else {
                          $min009=""; $rk_ppkd_lalu; $min010="";
                            }     
        
        $rk_ppkd_lalu1=number_format($rk_ppkd_lalu,"2",",",".");
    
        if ($rk_ppkd < 0){
                          $min013="("; $rk_ppkd=$rk_ppkd*-1; $min014=")";
                            }else {
                          $min013=""; $rk_ppkd; $min014="";
                            }     
    
        $rk_ppkd1=number_format($rk_ppkd,"2",",",".");
        
        if ($lcr < 0){
                          $min015="("; $lcr=$lcr*-1; $min016=")";
                            }else {
                          $min015=""; $lcr; $min016="";
                            }     
    
        $lcr1=number_format($lcr,"2",",",".");
        
        if ($lcr_lalu < 0){
                          $min017="("; $lcr_lalu=$lcr_lalu*-1; $min018=")";
                            }else {
                          $min017=""; $lcr_lalu; $min018="";
                            }     
        
        $lcr_lalu1=number_format($lcr_lalu,"2",",",".");
        
        if ($ast < 0){
                          $min019="("; $ast=$ast*-1; $min020=")";
                            }else {
                          $min019=""; $ast; $min020="";
                            }     
    
        $ast1=number_format($ast,"2",",",".");
        
        if ($ast_lalu < 0){
                          $min021="("; $ast_lalu=$ast_lalu*-1; $min022=")";
                            }else {
                          $min021=""; $ast_lalu; $min022="";
                            }     
        
        $ast_lalu1=number_format($ast_lalu,"2",",",".");
        
        if ($rk_skpd_lalu < 0){
                          $min031="("; $rk_skpd_lalu=$rk_skpd_lalu*-1; $min032=")";
                            }else {
                          $min031=""; $rk_skpd_lalu; $min032="";
                            }     
        
        $rk_skpd_lalu1=number_format($rk_skpd_lalu,"2",",",".");
        
        if ($rk_skpd < 0){
                          $min033="("; $rk_skpd=$rk_skpd*-1; $min034=")";
                            }else {
                          $min033=""; $rk_skpd; $min034="";
                            }
        
        $rk_skpd1=number_format($rk_skpd,"2",",",".");
        
          $queryneraca = " SELECT kode, uraian, bold, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
                        isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
                        isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
                        isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
                        FROM map_neraca_permen_77_obyek ORDER BY seq ";  
        
          $query10 = $this->db->query($queryneraca);
          
          $no     = 0;
          
          foreach($query10->result_array() as $res){
            $uraian=$res['uraian'];
            $normal=$res['normal'];
            $bold=$res['bold'];
            $kode_1=trim($res['kode_1']);
            $kode_2=trim($res['kode_2']);
            $kode_3=trim($res['kode_3']);
            $kode_4=trim($res['kode_4']);
            $kode_5=trim($res['kode_5']);
            $kode_6=trim($res['kode_6']);
            $kode_7=trim($res['kode_7']);
            $kode_8=trim($res['kode_8']);
            $kode_9=trim($res['kode_9']);
            $kode_10=trim($res['kode_10']);
            $kode_11=trim($res['kode_11']);
            $kode_12=trim($res['kode_12']);
            $kode_13=trim($res['kode_13']);
            $kode_14=trim($res['kode_14']);
            $kode_15=trim($res['kode_15']);
                          
            
          $q = $this->db->query("  SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
                      and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and LEFT(a.kd_skpd,17)='$kd_skpd' and
                        (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                        kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                        kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                        kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                        kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                        kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                        kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                        kd_rek6 like '$kode_15%') ");  
    
             foreach($q->result_array() as $r){
              $debet=$r['debet'];
              $kredit=$r['kredit'];
             }
            
            if ($debet=='') $debet=0;
            if ($kredit=='') $kredit=0;
    
            if ($normal==1){
              $nl=$debet-$kredit;
            }else{
              $nl=$kredit-$debet;       
            }
            if ($nl=='') $nl=0;
      
            // Jurnal Tahun lalu
            $q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
                      and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1  and LEFT(a.kd_skpd,17)='$kd_skpd' and
                        (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                        kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                        kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                        kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                        kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                        kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                        kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                        kd_rek6 like '$kode_15%') ");  
    
             foreach($q->result_array() as $rx){
              $debet_lalu=$rx['debet'];
              $kredit_lalu=$rx['kredit'];
             }
            
            if ($debet_lalu=='') $debet_lalu=0;
            if ($kredit_lalu=='') $kredit_lalu=0;
    
            if ($normal==1){
              $sblm=$debet_lalu-$kredit_lalu;
            }else{
              $sblm=$kredit_lalu-$debet_lalu;       
            }
            if ($sblm=='') $sblm=0;
    
            if ($nl < 0){
                          $nl001="("; $nl=$nl*-1; $ln001=")";
                            }else {
                          $nl001=""; $ln001="";
                            }
            if ($sblm < 0){
                          $sblm001="("; $sblm=$sblm*-1; $mlbs001=")";
                            }else {
                          $sblm001=""; $mlbs001="";
                            }
            $nl1= number_format($nl,"2",",",".");
            $sblm1= number_format($sblm,"2",",",".");
            
            $no       = $no + 1;
    
            switch ($res['bold']) {
                        case 1:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td colspan =\"7\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                            break;
              case 2:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                       <td colspan =\"6\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                            break;
              case 3:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                       <td colspan =\"5\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                            break;
              case 4:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td colspan =\"4\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                            break;
              case 34:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td colspan =\"4\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                            break;
              case 14:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td colspan =\"4\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                            break;
              case 5:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td colspan =\"3\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                            break;
              case 15:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td colspan =\"3\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                            break;  
              case 6:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td colspan =\"2\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                            break;
              case 16:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td colspan =\"2\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min015$lcr1$min016</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min017$lcr_lalu1$min018</td>
                                     </tr>";
                            break;  
              case 7:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                            break;  
              case 17:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min019$ast1$min020</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min021$ast_lalu1$min022</td>
                                     </tr>";
                            break;
              case 10:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     </tr>";
                  break;
              case 11:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td colspan =\"4\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$c$sal_akhir1$d</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$c1$sal_awal1$d1</td>
                                     </tr>";
                            break;    
              case 12:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min003$eku1$min004</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min001$eku_lalu1$min002</td>
                                     </tr>";
                            break;
              case 13:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                       <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
                                     </tr>";
                            break;
              }
          }
    
          
            $cRet .='</table>';
                     
            $data['prev']= $cRet;  
             $data['sikap'] = 'preview';
             $judul = ("NERACA KONSOL OBYEK SKPD $cbulan");
            $this->template->set('title', 'NERACA KONSOL OBYEK SKPD $cbulan');  
             switch ($cetak)
            {
                case 0;
            $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
            echo $cRet;
            break;
                case 1;
            echo "<title>NERACA KONSOL OBYEK UNIT $cbulan</title>";
            echo $cRet;
                   break;
                case 2;
                    header("Cache-Control: no-cache, no-store, must-revalidate");
                    header("Content-Type: application/vnd.ms-excel");
                    header("Content-Disposition: attachment; filename= $judul.xls");
    
                    $this->load->view('anggaran/rka/perkadaII', $data);
                    break;
                case 3;
                    header("Cache-Control: no-cache, no-store, must-revalidate");
                    header("Content-Type: application/vnd.ms-word");
                    header("Content-Disposition: attachment; filename= $judul.doc");
                    $this->load->view('anggaran/rka/perkadaII', $data);
                    break;
            }
          }

    //========================================================= Cetak Neraca

    function rpt_neraca_pemda_org($cbulan = "", $kd_skpd = "", $cetak = 1)
    {
        //$bulan   = $_REQUEST['tgl1'];
        /* $kd_skpd    = $this->session->userdata('kdskpd');*/
        $thn_ang  = $this->session->userdata('pcThang');
        $thn_ang_1  = $thn_ang - 1;
        $bulan   = $cbulan;
        $cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;

        $sqlsc = "SELECT nm_org FROM ms_organisasi where kd_org=left('$kd_skpd',17) ";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {

            $nmskpd  = $rowsc->nm_org;
        }

        $nm_skpd  = strtoupper($nmskpd);

        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);

        $sclient = $this->akuntansi_model->get_sclient();

        $cRet = '';
        $cRet = "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
              <tr>
                             <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                        </tr>
              <tr>
                             <td align=\"center\"><strong>$nm_skpd</strong></td>                         
                        </tr>
              <TR>
                <td align=\"center\"><strong>NERACA</strong></td>
              </TR>
              <TR>
                <td align=\"center\"><strong>PER $arraybulan[$bulan] $thn_ang DAN $thn_ang_1 </strong></td>
              </TR>
              </TABLE><br>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                         <thead>                       
                            <tr>
                  <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
                                <td bgcolor=\"#CCCCCC\" width=\"55%\" align=\"center\"><b>URAIAN</b></td>
                                <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                                <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>                            
                            </tr>
                            
                         </thead>
                         <tfoot>
                            <tr>
                  <td style=\"border-top: none;\"></td>
                                <td style=\"border-top: none;\"></td>
                                <td style=\"border-top: none;\"></td>
                                <td style=\"border-top: none;\"></td>                                             
                             </tr>
                         </tfoot>
                       
                         <tr> <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
                  <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"55%\" align=\"center\">&nbsp;</td>                            
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                                <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                               
                            </tr>";


        //level 1

        // Created by Henri_TB
        $trhju = 'trhju_pkd';
        $trdju = 'trdju_pkd';
        $ekuitas = '310101010001';
     $sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7') and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'";
        $querylo10 = $this->db->query($sqllo10);
        $pen8 = $querylo10->row();
        $pen_lalu8 = $pen8->nilai;
        $pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

        $sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8')and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'";
        $querylo12 = $this->db->query($sqllo12);
        $bel10 = $querylo12->row();
        $bel_lalu10 = $bel10->nilai;
        $bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

        $sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
              inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_ll1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
              inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_ll2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
              inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_ll3  = $row003->thn_m1;
        }


        $query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM $trdju a INNER JOIN $trhju b 
          ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$ekuitas' AND YEAR(b.tgl_voucher)<'$thn_ang'
          and b.tabel=1 and reev=0 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'");
        foreach ($query3->result_array() as $res2) {
            $debet3 = $res2['debet'];
            $kredit3 = $res2['kredit'];
        }

        $real = $kredit3 - $debet3 + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;

        //    created by henri_tb
        $sqllo9 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7') and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02' ";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8') and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'";
        $querylo12 = $this->db->query($sqllo12);
        $bello10 = $querylo12->row();
        $bel_lo_lalu10 = $bello10->nilai;
        $bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

        $surplus_lo3 = $pen_lo7 - $bel_lo9;

        $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

        $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;

        $sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
              inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_lalu1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
              inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_lalu2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
              inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_lalu3  = $row003->thn_m1;
        }

        $sal_awal = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;

        $sql = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
              inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //aba

        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $nilaiDR  = $row001->thn_m1;
        }

        $sqllpe1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
              inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllpe1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $nilailpe1  = $row002->thn_m1;
        }

        $sqllpe2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
              inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllpe2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $nilailpe2  = $row003->thn_m1;
        }

        $sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2;

        $sqlutang_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and year(a.tgl_voucher)<=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlutang_lalu);
        foreach ($hasil->result() as $row) {
            $nilaiutang_lalu  = $row->thn_m1;
        }

        $sqlkas_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and year(a.tgl_voucher)<=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlkas_lalu);
        foreach ($hasil->result() as $row) {
            $rk_ppkd_lalu  = $row->thn_m1;
        }

        $sqlskpd_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and year(a.tgl_voucher)<=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlskpd_lalu);
        foreach ($hasil->result() as $row) {
            $rk_skpd_lalu  = $row->thn_m1;
        }

        $sqllcr_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and year(a.tgl_voucher)<=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllcr_lalu);
        foreach ($hasil->result() as $row) {
            $lcrx_lalu  = $row->thn_m1;
        }

        $sqlast_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and year(a.tgl_voucher)<=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlast_lalu);
        foreach ($hasil->result() as $row) {
            $astx_lalu  = $row->thn_m1;
        }

        $lcr_lalu   = $lcrx_lalu - $rk_skpd_lalu;
        $ast_lalu   = $astx_lalu - $rk_skpd_lalu;
        $eku_lalu     = $sal_awal + $rk_ppkd_lalu - $rk_skpd_lalu;
        $eku_tang_lalu  = $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu - $rk_skpd_lalu;

        $sqlutang = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher
          and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlutang);
        foreach ($hasil->result() as $row) {
            $nilaiutang  = $row->thn_m1;
        }

        $sqlkas = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlkas);
        foreach ($hasil->result() as $row) {
            $rk_ppkd  = $row->thn_m1;
        }

        $sqlskpd = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlskpd);
        foreach ($hasil->result() as $row) {
            $rk_skpd  = $row->thn_m1;
        }

        $sqllcr = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqllcr);
        foreach ($hasil->result() as $row) {
            $lcrx = $row->thn_m1;
        }

        $sqlast = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
          and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02'"; //Henri_TB

        $hasil = $this->db->query($sqlast);
        foreach ($hasil->result() as $row) {
            $astx  = $row->thn_m1;
        }

        /*$lcr      = $lcrx-$rk_skpd;
          $ast      = $astx-$rk_skpd;
          $eku      = $sal_akhir - $rk_ppkd + $rk_skpd;         
          $eku_tang     = $sal_akhir + $nilaiutang - $rk_ppkd +$rk_skpd;   */


        $lcr    = $lcrx - $rk_skpd;
        $ast    = $astx - $rk_skpd;
        $eku    = $sal_akhir + $rk_ppkd;
        $eku_tang   = $sal_akhir + $nilaiutang + $rk_ppkd;

        if ($sal_akhir < 0) {
            $c = "(";
            $sal_akhir = $sal_akhir * -1;
            $d = ")";
        } else {
            $c = "";
            $sal_akhir;
            $d = "";
        }

        $sal_akhir1 = number_format($sal_akhir, "2", ",", ".");

        if ($sal_awal < 0) {
            $c1 = "(";
            $sal_awal = $sal_awal * -1;
            $d1 = ")";
        } else {
            $c1 = "";
            $sal_awal;
            $d1 = "";
        }

        $sal_awal1 = number_format($sal_awal, "2", ",", ".");


        if ($eku_lalu < 0) {
            $min001 = "(";
            $eku_lalu = $eku_lalu * -1;
            $min002 = ")";
        } else {
            $min001 = "";
            $eku_lalu;
            $min002 = "";
        }

        $eku_lalu1 = number_format($eku_lalu, "2", ",", ".");

        if ($eku < 0) {
            $min003 = "(";
            $eku = $eku * -1;
            $min004 = ")";
        } else {
            $min003 = "";
            $eku;
            $min004 = "";
        }

        $eku1 = number_format($eku, "2", ",", ".");

        if ($eku_tang_lalu < 0) {
            $min005 = "(";
            $eku_tang_lalu = $eku_tang_lalu * -1;
            $min006 = ")";
        } else {
            $min005 = "";
            $eku_tang_lalu;
            $min006 = "";
        }

        $eku_tang_lalu1 = number_format($eku_tang_lalu, "2", ",", ".");

        if ($eku_tang < 0) {
            $min007 = "(";
            $eku_tang = $eku_tang * -1;
            $min008 = ")";
        } else {
            $min007 = "";
            $eku_tang;
            $min008 = "";
        }

        $eku_tang1 = number_format($eku_tang, "2", ",", ".");

        if ($rk_ppkd_lalu < 0) {
            $min009 = "(";
            $rk_ppkd_lalu = $rk_ppkd_lalu * -1;
            $min010 = ")";
        } else {
            $min009 = "";
            $rk_ppkd_lalu;
            $min010 = "";
        }

        $rk_ppkd_lalu1 = number_format($rk_ppkd_lalu, "2", ",", ".");

        if ($rk_ppkd < 0) {
            $min013 = "(";
            $rk_ppkd = $rk_ppkd * -1;
            $min014 = ")";
        } else {
            $min013 = "";
            $rk_ppkd;
            $min014 = "";
        }

        $rk_ppkd1 = number_format($rk_ppkd, "2", ",", ".");

        if ($lcr < 0) {
            $min015 = "(";
            $lcr = $lcr * -1;
            $min016 = ")";
        } else {
            $min015 = "";
            $lcr;
            $min016 = "";
        }

        $lcr1 = number_format($lcr, "2", ",", ".");

        if ($lcr_lalu < 0) {
            $min017 = "(";
            $lcr_lalu = $lcr_lalu * -1;
            $min018 = ")";
        } else {
            $min017 = "";
            $lcr_lalu;
            $min018 = "";
        }

        $lcr_lalu1 = number_format($lcr_lalu, "2", ",", ".");

        if ($ast < 0) {
            $min019 = "(";
            $ast = $ast * -1;
            $min020 = ")";
        } else {
            $min019 = "";
            $ast;
            $min020 = "";
        }

        $ast1 = number_format($ast, "2", ",", ".");

        if ($ast_lalu < 0) {
            $min021 = "(";
            $ast_lalu = $ast_lalu * -1;
            $min022 = ")";
        } else {
            $min021 = "";
            $ast_lalu;
            $min022 = "";
        }

        $ast_lalu1 = number_format($ast_lalu, "2", ",", ".");

        $queryneraca = " SELECT kode, uraian, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
                        isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
                        isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
                        isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
                        FROM map_neraca_permen_77 ORDER BY seq ";

        $query10 = $this->db->query($queryneraca);

        $no     = 0;

        foreach ($query10->result_array() as $res) {
            $uraian = $res['uraian'];
            $normal = $res['normal'];

            $kode_1 = trim($res['kode_1']);
            $kode_2 = trim($res['kode_2']);
            $kode_3 = trim($res['kode_3']);
            $kode_4 = trim($res['kode_4']);
            $kode_5 = trim($res['kode_5']);
            $kode_6 = trim($res['kode_6']);
            $kode_7 = trim($res['kode_7']);
            $kode_8 = trim($res['kode_8']);
            $kode_9 = trim($res['kode_9']);
            $kode_10 = trim($res['kode_10']);
            $kode_11 = trim($res['kode_11']);
            $kode_12 = trim($res['kode_12']);
            $kode_13 = trim($res['kode_13']);
            $kode_14 = trim($res['kode_14']);
            $kode_15 = trim($res['kode_15']);


            $q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
                      and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02' and
                        (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                        kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                        kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                        kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                        kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                        kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                        kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                        kd_rek6 like '$kode_15%') ");

            foreach ($q->result_array() as $r) {
                $debet = $r['debet'];
                $kredit = $r['kredit'];
            }

            if ($debet == '') $debet = 0;
            if ($kredit == '') $kredit = 0;

            if ($normal == 1) {
                $nl = $debet - $kredit;
            } else {
                $nl = $kredit - $debet;
            }
            if ($nl == '') $nl = 0;

            // Jurnal Tahun lalu
            $q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
                      and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and left(kd_skpd,17)=left('$kd_skpd',17) AND kd_skpd<>'4.02.02.02' and
                        (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                        kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                        kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                        kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                        kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                        kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                        kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                        kd_rek6 like '$kode_15%') ");

            foreach ($q->result_array() as $rx) {
                $debet_lalu = $rx['debet'];
                $kredit_lalu = $rx['kredit'];
            }

            if ($debet_lalu == '') $debet_lalu = 0;
            if ($kredit_lalu == '') $kredit_lalu = 0;

            if ($normal == 1) {
                $sblm = $debet_lalu - $kredit_lalu;
            } else {
                $sblm = $kredit_lalu - $debet_lalu;
            }
            if ($sblm == '') $sblm = 0;

            if ($nl < 0) {
                $nl001 = "(";
                $nl = $nl * -1;
                $ln001 = ")";
            } else {
                $nl001 = "";
                $ln001 = "";
            }
            if ($sblm < 0) {
                $sblm001 = "(";
                $sblm = $sblm * -1;
                $mlbs001 = ")";
            } else {
                $sblm001 = "";
                $mlbs001 = "";
            }
            $nl1 = number_format($nl, "2", ",", ".");
            $sblm1 = number_format($sblm, "2", ",", ".");

            $no       = $no + 1;

            switch ($res['seq']) {
                case 5:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min019$ast1$min020</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min021$ast_lalu1$min022</td>
                                     </tr>";
                    break;
                case 10:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min015$lcr1$min016</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min017$lcr_lalu1$min018</td>
                                     </tr>";
                    break;
                case 15:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 60:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 65:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 100:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 105:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                    /* case 90:
                              $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>"; 
                                      break;        
              case 100:
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                            break;*/

                case 110:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 115:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 120:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 125:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;

                case 155:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 170:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     </tr>";
                    break;
                case 175:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     </tr>";
                    break;

                case 180:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 225:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 240:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 245:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;

                    /*case 250: //di 2020 , ekuitas dan kewajiban
    
                             $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                          <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
                                     </tr>";
                            break;*/



                case 260:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 265:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 270:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 275:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
                case 280:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;

                case 285:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     </tr>";
                    break;


                case 286:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                          <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
                                     </tr>";
                    break;
                case 290:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;



                case 295:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;


                case 335:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;

                case 365:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min003$eku1$min004</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min001$eku_lalu1$min002</td>
                                     </tr>";
                    break;
                case 400:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
                                     </tr>";
                    break;
                default:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                     </tr>";
                    break;
            }
        }


        $cRet .= '</table>';

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul = ("NERACA KONSOL $cbulan");
        $this->template->set('title', 'NERACA KONSOL $cbulan');
        switch ($cetak) {
            case 0;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                echo $cRet;
                break;
            case 1;
                echo "<title>NERACA KONSOL $cbulan</title>";
                echo $cRet;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }


    //========================================================= End Cetak Neraca
}

