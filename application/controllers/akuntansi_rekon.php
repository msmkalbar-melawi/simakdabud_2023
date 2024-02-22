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
    private function _client($kodeSkpd,$bulan)
    {
        $tahunAnggaran = $this->session->userdata('pcThang');
        $skpd =  $this->db->query("SELECT nm_skpd FROM ms_skpd WHERE kd_skpd = ?",[$kodeSkpd])->row();
        $tahun = kopTahun($tahunAnggaran, $bulan, $tahunAnggaran-1);
        $client = $this->akuntansi_model->get_sclient();

        $kop = "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                    <td rowspan=\"4\" align=\"left\" width=\"2%\">
                    <img src=\"" . base_url() . "/image/kab-sanggau.png\"  width=\"60\" height=\"70\" />
					</td>
                         <td align=\"center\"><strong>" . $client->kab_kota . "</strong></td>
                    </tr>
          <tr>
            <td align=\"center\"><strong>$skpd->nm_skpd</strong></td>
          </tr>
                    <tr>
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
                            <td  colspan =\"5\" bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$tahunAnggaran</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$tahunAnggaranLalu</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>Kenaikan /</br>(Penurunan)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
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
        $persenSebelumPos = ($nonOperasiTahunIni == 0 || $nonOperasiTahunLalu == 0 ) ? 0 : abs( $sebelumPosTahunIni)/abs($sebelumPosTahunLalu) * 100;

        foreach ($mapping as $key => $map) {
            $tahunIni = "";
            $tahunLalu ="";
            $kenaikan = "";
            $persen= "";

            if ($map->bold == 5) {
                $tahunIni = $surplusTahunIni < 0 ? "(". $this->support->currencyFormat(abs($surplusTahunIni)) .")" : $surplusTahunIni;
                $tahunLalu = $surplusTahunLalu < 0 ? "(". $this->support->currencyFormat(abs($surplusTahunLalu)) .")" : $surplusTahunLalu;
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
        $this->load->library('custom');
        //$id = $skpd;
        $cetak = '2'; //$ctk; 
        $tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
        $tglttd = str_replace('n', ' ', $tglttd);
        $id     = $this->session->userdata('kdskpd');
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1 = $thn_ang - 1;
        //$laporan=$lap; 

        // if ($cetak == '1') {
        //           $skpd = '';
        //           $skpd1 = '';           
        //       } else {             
        $skpd = "AND kd_skpd='$kd_skpd'";
        $skpd1 = "AND b.kd_skpd='$kd_skpd'";
        //}  

        $y123 = ")";
        $x123 = "(";
        $sqlsc = "SELECT kd_skpd, nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $nmskpd     = $rowsc->nm_skpd;
        }
        $nm_skpd = strtoupper($nmskpd);
        // INSERT DATA
        /*
       $sqldns="SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$kd_skpd'";
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                } 
     */
        // created by henri_tb


        $trhju = "trhju_pkd";
        $trdju = "trdju_pkd";

        $sqllo1 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo1 = $this->db->query($sqllo1);
        $penlo = $querylo1->row();
        $pen_lo = $penlo->nilai;
        $pen_lo1 = number_format($penlo->nilai, "2", ",", ".");

        $sqllo2 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo2 = $this->db->query($sqllo2);
        $penlo2 = $querylo2->row();
        $pen_lo_lalu = $penlo2->nilai;
        $pen_lo_lalu1 = number_format($penlo2->nilai, "2", ",", ".");

        $sqllo3 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83','84') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo3 = $this->db->query($sqllo3);
        $bello = $querylo3->row();
        $bel_lo = $bello->nilai;
        $bel_lo1 = number_format($bello->nilai, "2", ",", ".");

        $sqllo4 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','83','84') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo4 = $this->db->query($sqllo4);
        $bello2 = $querylo4->row();
        $bel_lo_lalu = $bello2->nilai;
        $bel_lo_lalu1 = number_format($bello2->nilai, "2", ",", ".");

        // calvin 11 february 2022

        $sqllo5 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74','85') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo5 = $this->db->query($sqllo5);
        $def5 = $querylo5->row();
        $def_lo = $def5->nilai;
        $def_lo1 = number_format($def5->nilai, "2", ",", ".");

        $sqllo6 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74','85') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo6 = $this->db->query($sqllo6);
        $def6 = $querylo6->row();
        $del_lo_lalu = $def6->nilai;
        $del_lo_lalu1 = number_format($def6->nilai, "2", ",", ".");

        // end

        $surplus_lo = $pen_lo - $bel_lo;
        if ($surplus_lo < 0) {
            $lo1 = "(";
            $surplus_lox = $surplus_lo * -1;
            $lo2 = ")";
        } else {
            $lo1 = "";
            $surplus_lox = $surplus_lo;
            $lo2 = "";
        }
        $surplus_lo1 = number_format($surplus_lox, "2", ",", ".");

        $surplus_lo_lalu = $pen_lo_lalu - $bel_lo_lalu;
        if ($surplus_lo_lalu < 0) {
            $lo3 = "(";
            $surplus_lo_lalux = $surplus_lo_lalu * -1;
            $lo4 = ")";
        } else {
            $lo3 = "";
            $surplus_lo_lalux = $surplus_lo_lalu;
            $lo4 = "";
        }
        $surplus_lo_lalu1 = number_format($surplus_lo_lalux, "2", ",", ".");

        // calvin 
        $surplus_def_lo = $surplus_lo - $def_lo;
        if ($surplus_def_lo < 0) {
            $lo96 = "(";
            $surplus_def = $surplus_def_lo * -1;
            $lo97 = ")";
        } else {
            $lo96 = "";
            $surplus_def = $surplus_def_lo;
            $lo97 = "";
        }
        $surplus_def_lo1 = number_format($surplus_def, "2", ",", ".");

        $surplus_def_lo_lalu = $surplus_lo_lalu - $del_lo_lalu;
        if ($surplus_def_lo_lalu < 0) {
            $lo99 = "(";
            $surplus_def_lalux = $surplus_def_lo_lalu * -1;
            $lo98 = ")";
        } else {
            $lo99 = "";
            $surplus_def_lalux = $surplus_def_lo_lalu;
            $lo98 = "";
        }
        $surplus_def_lo_lalu1 = number_format($surplus_def_lalux, "2", ",", ".");

        //end

        $selisih_surplus_lo = $surplus_lo - $surplus_lo_lalu;
        if ($selisih_surplus_lo < 0) {
            $lo5 = "(";
            $selisih_surplus_lox = $selisih_surplus_lo * -1;
            $lo6 = ")";
        } else {
            $lo5 = "";
            $selisih_surplus_lox = $selisih_surplus_lo;
            $lo6 = "";
        }
        $selisih_surplus_lo1 = number_format($selisih_surplus_lox, "2", ",", ".");

        if ($surplus_lo_lalu == '' or $surplus_lo_lalu == 0) {
            $persen2 = '0,00';
        } else {
            $persen2 = ($surplus_lo / $surplus_lo_lalu) * 100;
            $persen2 = number_format($persen2, "2", ",", ".");
        }
        //surpuls def
        $selisih_surplus_def = $surplus_def_lo - $surplus_def_lo_lalu;
        if ($selisih_surplus_def < 0) {
            $lo100 = "(";
            $selisih_surplus_defx = $selisih_surplus_def * -1;
            $lo101 = ")";
        } else {
            $lo100 = "";
            $selisih_surplus_defx = $selisih_surplus_def;
            $lo101 = "";
        }
        $selisih_surplus_def1 = number_format($selisih_surplus_defx, "2", ",", ".");

        if ($surplus_def_lo_lalu == '' or $surplus_def_lo_lalu == 0) {
            $persen100 = '0,00';
        } else {
            $persen100 = ($surplus_def_lo / $surplus_def_lo_lalu) * 100;
            $persen100 = number_format($persen100, "2", ",", ".");
        }

        //end
        $sqllo5 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73','74') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo5 = $this->db->query($sqllo5);
        $penlo3 = $querylo5->row();
        $pen_lo3 = $penlo3->nilai;
        $pen_lo31 = number_format($penlo3->nilai, "2", ",", ".");

        $sqllo6 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73','74') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo6 = $this->db->query($sqllo6);
        $penlo4 = $querylo6->row();
        $pen_lo_lalu4 = $penlo4->nilai;
        $pen_lo_lalu41 = number_format($penlo4->nilai, "2", ",", ".");

        $sqllo7 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo7 = $this->db->query($sqllo7);
        $bello5 = $querylo7->row();
        $bel_lo5 = $bello5->nilai;
        $bel_lo51 = number_format($bello5->nilai, "2", ",", ".");

        $sqllo8 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','83') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo8 = $this->db->query($sqllo8);
        $bello6 = $querylo8->row();
        $bel_lo_lalu6 = $bello6->nilai;
        $bel_lo_lalu61 = number_format($bello6->nilai, "2", ",", ".");

        //calvin kenaikan/penurunan | 13 feburary 2022
        $sqllo22 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74','85') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo22 = $this->db->query($sqllo22);
        $deflo22 = $querylo22->row();
        $def_lo22 = $deflo22->nilai;
        $def_lo221 = number_format($deflo22->nilai, "2", ",", ".");

        $sqllo23 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74','85') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo23 = $this->db->query($sqllo23);
        $def23 = $querylo23->row();
        $def_lo_lalu23 = $def23->nilai;
        $def_lo_lalu231 = number_format($def23->nilai, "2", ",", ".");
        //end

        $surplus_lo2 = $pen_lo3 - $bel_lo5;
        if ($surplus_lo2 < 0) {
            $lo7 = "(";
            $surplus_lo2x = $surplus_lo2 * -1;
            $lo8 = ")";
        } else {
            $lo7 = "";
            $surplus_lo2x = $surplus_lo2;
            $lo8 = "";
        }
        $surplus_lo21 = number_format($surplus_lo2x, "2", ",", ".");

        $surplus_lo_lalu2 = $pen_lo_lalu4 - $bel_lo_lalu6;
        if ($surplus_lo_lalu2 < 0) {
            $lo9 = "(";
            $surplus_lo_lalu2x = $surplus_lo_lalu2 * -1;
            $lo10 = ")";
        } else {
            $lo9 = "";
            $surplus_lo_lalu2x = $surplus_lo_lalu2;
            $lo10 = "";
        }
        $surplus_lo_lalu21 = number_format($surplus_lo_lalu2x, "2", ",", ".");

        $selisih_surplus_lo2 = $surplus_lo2 - $surplus_lo_lalu2;
        if ($selisih_surplus_lo2 < 0) {
            $lo11 = "(";
            $selisih_surplus_lo2x = $selisih_surplus_lo2 * -1;
            $lo12 = ")";
        } else {
            $lo11 = "";
            $selisih_surplus_lo2x = $selisih_surplus_lo2;
            $lo12 = "";
        }
        $selisih_surplus_lo21 = number_format($selisih_surplus_lo2x, "2", ",", ".");

        // calvin | 13 february 2022
        $selisih_def_lo22 = $def_lo22 - $def_lo_lalu23;
        //end

        if ($surplus_lo_lalu2 == '' or $surplus_lo_lalu2 == 0) {
            $persen3 = '0,00';
        } else {
            $persen3 = ($surplus_lo2 / $surplus_lo_lalu2) * 100;
            $persen3 = number_format($persen3, "2", ",", ".");
        }

        $sqllo9 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('7') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('8') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo12 = $this->db->query($sqllo12);
        $bello10 = $querylo12->row();
        $bel_lo_lalu10 = $bello10->nilai;
        $bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

        $surplus_lo3 = $pen_lo7 - $bel_lo9;
        if ($surplus_lo3 < 0) {
            $lo13 = "(";
            $surplus_lo3x = $surplus_lo3 * -1;
            $lo14 = ")";
        } else {
            $lo13 = "";
            $surplus_lo3x = $surplus_lo3;
            $lo14 = "";
        }
        $surplus_lo31 = number_format($surplus_lo3x, "2", ",", ".");

        $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
        if ($surplus_lo_lalu3 < 0) {
            $lo15 = "(";
            $surplus_lo_lalu3x = $surplus_lo_lalu3 * -1;
            $lo16 = ")";
        } else {
            $lo15 = "";
            $surplus_lo_lalu3x = $surplus_lo_lalu3;
            $lo16 = "";
        }
        $surplus_lo_lalu31 = number_format($surplus_lo_lalu3x, "2", ",", ".");

        $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
        if ($selisih_surplus_lo3 < 0) {
            $lo17 = "(";
            $selisih_surplus_lo3x = $selisih_surplus_lo3 * -1;
            $lo18 = ")";
        } else {
            $lo17 = "";
            $selisih_surplus_lo3x = $selisih_surplus_lo3;
            $lo18 = "";
        }
        $selisih_surplus_lo31 = number_format($selisih_surplus_lo3x, "2", ",", ".");

        if ($surplus_lo_lalu3 == '' or $surplus_lo_lalu3 == 0) {
            $persen4 = '0,00';
        } else {
            $persen4 = ($surplus_lo3 / $surplus_lo_lalu3) * 100;
            $persen4 = number_format($persen4, "2", ",", ".");
        }


        $sqllo13 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo13 = $this->db->query($sqllo13);
        $penlo11 = $querylo13->row();
        $pen_lo11 = $penlo11->nilai;
        $pen_lo111 = number_format($penlo11->nilai, "2", ",", ".");

        $sqllo14 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo14 = $this->db->query($sqllo14);
        $penlo12 = $querylo14->row();
        $pen_lo_lalu12 = $penlo12->nilai;
        $pen_lo_lalu121 = number_format($penlo12->nilai, "2", ",", ".");

        $sqllo15 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('83') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo15 = $this->db->query($sqllo15);
        $bello13 = $querylo15->row();
        $bel_lo13 = $bello13->nilai;
        $bel_lo131 = number_format($bello13->nilai, "2", ",", ".");

        $sqllo16 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('83') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo16 = $this->db->query($sqllo16);
        $bello14 = $querylo16->row();
        $bel_lo_lalu14 = $bello14->nilai;
        $bel_lo_lalu141 = number_format($bello14->nilai, "2", ",", ".");

        $surplus_lo4 = $pen_lo11 - $bel_lo13;
        if ($surplus_lo4 < 0) {
            $lo19 = "(";
            $surplus_lo4x = $surplus_lo4 * -1;
            $lo20 = ")";
        } else {
            $lo19 = "";
            $surplus_lo4x = $surplus_lo4;
            $lo20 = "";
        }
        $surplus_lo41 = number_format($surplus_lo4x, "2", ",", ".");

        $surplus_lo_lalu4 = $pen_lo_lalu12 - $bel_lo_lalu14;
        if ($surplus_lo_lalu4 < 0) {
            $lo21 = "(";
            $surplus_lo_lalu4x = $surplus_lo_lalu4 * -1;
            $lo22 = ")";
        } else {
            $lo21 = "";
            $surplus_lo_lalu4x = $surplus_lo_lalu4;
            $lo22 = "";
        }
        $surplus_lo_lalu41 = number_format($surplus_lo_lalu4x, "2", ",", ".");

        $selisih_surplus_lo4 = $surplus_lo4 - $surplus_lo_lalu4;
        if ($selisih_surplus_lo4 < 0) {
            $lo23 = "(";
            $selisih_surplus_lo4x = $selisih_surplus_lo4 * -1;
            $lo24 = ")";
        } else {
            $lo23 = "";
            $selisih_surplus_lo4x = $selisih_surplus_lo4;
            $lo24 = "";
        }
        $selisih_surplus_lo41 = number_format($selisih_surplus_lo4x, "2", ",", ".");

        if ($surplus_lo_lalu4 == '' or $surplus_lo_lalu4 == 0) {
            $persen5 = '0,00';
        } else {
            $persen5 = ($surplus_lo4 / $surplus_lo_lalu4) * 100;
            $persen5 = number_format($persen5, "2", ",", ".");
        }


        $sqllo17 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('75') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo17 = $this->db->query($sqllo17);
        $penlo15 = $querylo17->row();
        $pen_lo15 = $penlo15->nilai;
        $pen_lo151 = number_format($penlo15->nilai, "2", ",", ".");

        $sqllo18 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('75') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo18 = $this->db->query($sqllo18);
        $penlo16 = $querylo18->row();
        $pen_lo_lalu16 = $penlo16->nilai;
        $pen_lo_lalu161 = number_format($penlo16->nilai, "2", ",", ".");

        $sqllo19 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('84') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo19 = $this->db->query($sqllo19);
        $bello17 = $querylo19->row();
        $bel_lo17 = $bello17->nilai;
        $bel_lo171 = number_format($bello17->nilai, "2", ",", ".");

        $sqllo20 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('84') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo20 = $this->db->query($sqllo20);
        $bello18 = $querylo20->row();
        $bel_lo_lalu18 = $bello18->nilai;
        $bel_lo_lalu181 = number_format($bello18->nilai, "2", ",", ".");

        $surplus_lo5 = $pen_lo15 - $bel_lo17;
        if ($surplus_lo5 < 0) {
            $lo25 = "(";
            $surplus_lo5x = $surplus_lo5 * -1;
            $lo26 = ")";
        } else {
            $lo25 = "";
            $surplus_lo5x = $surplus_lo5;
            $lo26 = "";
        }
        $surplus_lo51 = number_format($surplus_lo5x, "2", ",", ".");

        $surplus_lo_lalu5 = $pen_lo_lalu16 - $bel_lo_lalu18;
        if ($surplus_lo_lalu5 < 0) {
            $lo27 = "(";
            $surplus_lo_lalu5x = $surplus_lo_lalu5 * -1;
            $lo28 = ")";
        } else {
            $lo27 = "";
            $surplus_lo_lalu5x = $surplus_lo_lalu5;
            $lo28 = "";
        }
        $surplus_lo_lalu51 = number_format($surplus_lo_lalu5x, "2", ",", ".");

        $selisih_surplus_lo5 = $surplus_lo5 - $surplus_lo_lalu5;
        if ($selisih_surplus_lo5 < 0) {
            $lo29 = "(";
            $selisih_surplus_lo5x = $selisih_surplus_lo5 * -1;
            $lo30 = ")";
        } else {
            $lo29 = "";
            $selisih_surplus_lo5x = $selisih_surplus_lo5;
            $lo30 = "";
        }
        $selisih_surplus_lo51 = number_format($selisih_surplus_lo5x, "2", ",", ".");

        if ($surplus_lo_lalu5 == '' or $surplus_lo_lalu5 == 0) {
            $persen6 = '0,00';
        } else {
            $persen6 = ($surplus_lo5 / $surplus_lo_lalu5) * 100;
            $persen6 = number_format($persen6, "2", ",", ".");
        }


        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);

        $cRet = '';


        $sclient = $this->akuntansi_model->get_sclient();
        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                    <td rowspan=\"4\" align=\"left\" width=\"2%\">
                    <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"60\" height=\"70\" />
					</td>
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
          <tr>
            <td align=\"center\"><strong>$nm_skpd</strong></td>
          </tr> 
                    <tr>
                         <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
                            <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NO</b></td>                            
                            <td  colspan =\"5\" bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>Kenaikan /</br>(Penurunan)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td colspan =\"5\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>";

        $sqlmaplo = "SELECT seq, nor, uraian, bold,isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak,parent,kode FROM map_lo_pemda 
                   GROUP BY seq, nor, uraian, bold, isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet'),parent, kode ORDER BY seq";

        $querymaplo = $this->db->query($sqlmaplo);
        $no     = 0;
        $arr_parent = [];
        $surplus_lo = 0;
        $surplus_lo_thnlalu = 0;
        $selisih_surplus_lo = 0;

        foreach ($querymaplo->result() as $loquery) {
            $operator = $loquery->kode;
            $seq = $loquery->seq;
            $nama      = $loquery->uraian;
            $n         = $loquery->kode_1;
            $n       = ($n == "-" ? "'-'" : $n);
            $n2        = $loquery->kode_2;
            $n2      = ($n2 == "-" ? "'-'" : $n2);
            $n3        = $loquery->kode_3;
            $n3      = ($n3 == "-" ? "'-'" : $n3);
            $normal    = $loquery->cetak;
            $bold      = $loquery->bold;

            if ($normal != "") {
                $quelo01   = "SELECT SUM($normal) as nilai FROM $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
                $quelo02 = $this->db->query($quelo01);
                $quelo03 = $quelo02->row();
                $nil     = $quelo03->nilai;
                $nilai    = number_format($quelo03->nilai, "2", ",", ".");

                $quelo04   = "SELECT SUM($normal) as nilai FROM $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang_1 and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
                $quelo05 = $this->db->query($quelo04);
                $quelo06 = $quelo05->row();
                $nil_lalu     = $quelo06->nilai;
                $nilai_lalu    = number_format($quelo06->nilai, "2", ",", ".");

                if ($nil < 0) {
                    $lo88 = "(";
                    $nilx = $nil * -1;
                    $lo89 = ")";
                } else {
                    $lo88 = "";
                    $nilx = $nil;
                    $lo89 = "";
                }
                $nil1 = number_format($nilx, "2", ",", ".");

                if ($nil_lalu < 0) {
                    $lo99 = "(";
                    $nil_lalux = $nil_lalu * -1;
                    $lo98 = ")";
                } else {
                    $lo99 = "";
                    $nil_lalux = $nil_lalu;
                    $lo98 = "";
                }
                $nil_lalu1 = number_format($nil_lalux, "2", ",", ".");

                $real_nilai = $nil - $nil_lalu;
                if ($real_nilai < 0) {
                    $lo0 = "(";
                    $real_nilaix = $real_nilai * -1;
                    $lo00 = ")";
                } else {
                    $lo0 = "";
                    $real_nilaix = $real_nilai;
                    $lo00 = "";
                }
                $real_nilai1 = number_format($real_nilaix, "2", ",", ".");

                if ($nil_lalu == '' or $nil_lalu == 0) {
                    $persen1 = '0,00';
                } else {
                    $persen1 = ($nil / $nil_lalu) * 100;
                    $persen1 = number_format($persen1, "2", ",", ".");
                }
                if ($loquery->parent) {
                    $arr_parent[] = array(
                        "seq" => $seq,
                        "parent" => $loquery->parent,
                        "uraian" => $nama,
                        "nilai" => ($operator == '-') ? $nil * -1 : $nil,
                        "nilai_lalu" => ($operator == '-') ? $nil_lalu  * -1 : $nil_lalu
                    );
                }
            }
            $no       = $no + 1;
            switch ($loquery->bold) {
                case 0:
                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 1:
                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 2:
                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                     <td colspan =\"4\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"></td>
                                     
                                 </tr>";
                    break;
                case 3:
                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td> 
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                  
                                     <td colspan =\"3\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo88$nil1$lo89</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo99$nil_lalu1$lo98</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo0$real_nilai1$lo00</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persen1</td>
                                 </tr>";
                    //===================== Objek         
                    $sql = "SELECT c.kd_rek4,c.nm_rek4,sum(a.$normal) as 'thn_ini',
                                (
                                    select isnull(sum(d.$normal),0) from trdju_pkd d 
                                    join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
                                    where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,6)=c.kd_rek4 
                                    and left(e.kd_skpd,22)=left(b.kd_skpd,22)
                                ) as 'thn_lalu' 
                                from trdju_pkd a 
                                join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                join ms_rek4 c on LEFT(a.kd_rek6,6)=c.kd_rek4
                                where left(b.kd_skpd,22)=left('$kd_skpd',22) and left(kd_rek6,4) in ($n) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
                                group by c.kd_rek4,c.nm_rek4,left(b.kd_skpd,22)";
                    $query = $this->db->query($sql);
                    foreach ($query->result() as $row) {
                        $no = $no + 1;
                        $nama = $row->nm_rek4;
                        $nilai = $this->custom->rp_minus($row->thn_ini);
                        $nilai_lalu = $this->custom->rp_minus($row->thn_lalu);
                        $real_nilai1 = $this->custom->rp_minus($row->thn_ini - $row->thn_lalu);
                        if ($row->thn_lalu == '' or $row->thn_lalu == 0) {
                            $persen1 = '0,00';
                        } else {
                            $persen1 = ($row->thn_ini / $row->thn_lalu) * 100;
                            $persen1 = number_format($persen1, "2", ",", ".");
                        }

                        $cRet    .= "<tr>
                                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                            <td colspan =\"2\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
                                            <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
                                        </tr>";
                    }
                    //===================== End Objek
                    break;
                case 4:
                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td> 
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                    
                                     <td colspan =\"2\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo88$nil1$lo89</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo99$nil_lalu1$lo98</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo0$real_nilai1$lo00</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"><b>$persen1</b></td>
                                 </tr>";
                    break;
                case 5:
                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo1$surplus_lo1$lo2</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo3$surplus_lo_lalu1$lo4</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo5$selisih_surplus_lo1$lo6</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"><b>$persen2</b></td>
                                 </tr>";
                    break;
                case 6:
                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td> 
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                   
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo96$surplus_def_lo1$lo97</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo99$surplus_def_lo_lalu1$lo98</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_def1$lo12</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"><b>$persen100</b></td>
                                 </tr>";
                    break;
                case 7:
                    foreach ($arr_parent as $key => $value) {
                        if ($value['parent'] == $seq) {
                            $surplus_lo += $value['nilai'];
                            $surplus_lo_thnlalu += $value['nilai_lalu'];
                        }
                    }
                    $selisih_surplus_lo = $surplus_lo - $surplus_lo_thnlalu;

                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td> 
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                    
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo96$surplus_def_lo1$lo97</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo99$surplus_def_lo_lalu1$lo98</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_def1$lo12</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"><b>$persen100</td>
                                 </tr>";
                    break;
            }
        }

        $cRet         .= "</table>";
        //        $data['prev']  = $cRet;
        //        $data['sikap'] = 'preview';
        //        
        //        
        //        
        //        $this->template->set('title', 'LAPORAN OPERASIONAL'); 
        //        $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
        //$this->template->load('template','anggaran/rka/perkadaII',$data); 
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$tglttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $namax = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }



        if ($nip == '00000000 000000 0 000') {
            $cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Melawi , ' . $tanggal . '</TD>
							</TR>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" ><b>' . $jabatan . '</b></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>   
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>                       
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" ><b>' . $namax . '</b></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > </TD>
							</TR>
							</TABLE><br/>';
        } else {
            $cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Melawi , ' . $tanggal . '</TD>
							</TR>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" ><b>' . $jabatan . '</b></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>   
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>                       
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" ><u><b>' . $namax . '</b></u></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $nip . '</TD>
							</TR>
							</TABLE><br/>';
        }
        $cRet .=       " </table>";
        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul  = ("LO KONSOL SKPD OBJEK $cbulan");
        $this->template->set('title', 'LO KONSOL UNIT OBJEK $cbulan');
        switch ($pilih) {
            case 0;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                echo $cRet;
                break;
            case 1;
                // $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
                echo "<title>LO KONSOL SKPD OBJEK $cbulan</title>";
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

    function ctk_lra_lo_pemda_org_rincian($cbulan = "", $kd_skpd = "", $pilih = 1, $tglttd = "", $ttd = "")
    {
        $this->load->library('custom');
        //$id = $skpd;
        $cetak = '2'; //$ctk; 
        $tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
        $tglttd = str_replace('n', ' ', $tglttd);
        $id     = $this->session->userdata('kdskpd');
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1 = $thn_ang - 1;
        //$laporan=$lap; 

        // if ($cetak == '1') {
        //           $skpd = '';
        //           $skpd1 = '';           
        //       } else {             
        $skpd = "AND kd_skpd='$kd_skpd'";
        $skpd1 = "AND b.kd_skpd='$kd_skpd'";
        //}  

        $y123 = ")";
        $x123 = "(";
        $sqlsc = "SELECT kd_skpd, nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $nmskpd     = $rowsc->nm_skpd;
        }
        $nm_skpd = strtoupper($nmskpd);
        // INSERT DATA
        /*
       $sqldns="SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$kd_skpd'";
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                } 
     */
        // created by henri_tb


        $trhju = "trhju_pkd";
        $trdju = "trdju_pkd";

        $sqllo1 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo1 = $this->db->query($sqllo1);
        $penlo = $querylo1->row();
        $pen_lo = $penlo->nilai;
        $pen_lo1 = number_format($penlo->nilai, "2", ",", ".");

        $sqllo2 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo2 = $this->db->query($sqllo2);
        $penlo2 = $querylo2->row();
        $pen_lo_lalu = $penlo2->nilai;
        $pen_lo_lalu1 = number_format($penlo2->nilai, "2", ",", ".");

        $sqllo3 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83','84') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo3 = $this->db->query($sqllo3);
        $bello = $querylo3->row();
        $bel_lo = $bello->nilai;
        $bel_lo1 = number_format($bello->nilai, "2", ",", ".");

        $sqllo4 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','83','84') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo4 = $this->db->query($sqllo4);
        $bello2 = $querylo4->row();
        $bel_lo_lalu = $bello2->nilai;
        $bel_lo_lalu1 = number_format($bello2->nilai, "2", ",", ".");

        // calvin 11 february 2022

        $sqllo5 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74','85') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo5 = $this->db->query($sqllo5);
        $def5 = $querylo5->row();
        $def_lo = $def5->nilai;
        $def_lo1 = number_format($def5->nilai, "2", ",", ".");

        $sqllo6 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74','85') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo6 = $this->db->query($sqllo6);
        $def6 = $querylo6->row();
        $del_lo_lalu = $def6->nilai;
        $del_lo_lalu1 = number_format($def6->nilai, "2", ",", ".");

        // end

        $surplus_lo = $pen_lo - $bel_lo;
        if ($surplus_lo < 0) {
            $lo1 = "(";
            $surplus_lox = $surplus_lo * -1;
            $lo2 = ")";
        } else {
            $lo1 = "";
            $surplus_lox = $surplus_lo;
            $lo2 = "";
        }
        $surplus_lo1 = number_format($surplus_lox, "2", ",", ".");

        $surplus_lo_lalu = $pen_lo_lalu - $bel_lo_lalu;
        if ($surplus_lo_lalu < 0) {
            $lo3 = "(";
            $surplus_lo_lalux = $surplus_lo_lalu * -1;
            $lo4 = ")";
        } else {
            $lo3 = "";
            $surplus_lo_lalux = $surplus_lo_lalu;
            $lo4 = "";
        }
        $surplus_lo_lalu1 = number_format($surplus_lo_lalux, "2", ",", ".");

        // calvin 
        $surplus_def_lo = $surplus_lo - $def_lo;
        if ($surplus_def_lo < 0) {
            $lo96 = "(";
            $surplus_def = $surplus_def_lo * -1;
            $lo97 = ")";
        } else {
            $lo96 = "";
            $surplus_def = $surplus_def_lo;
            $lo97 = "";
        }
        $surplus_def_lo1 = number_format($surplus_def, "2", ",", ".");

        $surplus_def_lo_lalu = $surplus_lo_lalu - $del_lo_lalu;
        if ($surplus_def_lo_lalu < 0) {
            $lo99 = "(";
            $surplus_def_lalux = $surplus_def_lo_lalu * -1;
            $lo98 = ")";
        } else {
            $lo99 = "";
            $surplus_def_lalux = $surplus_def_lo_lalu;
            $lo98 = "";
        }
        $surplus_def_lo_lalu1 = number_format($surplus_def_lalux, "2", ",", ".");

        //end

        $selisih_surplus_lo = $surplus_lo - $surplus_lo_lalu;
        if ($selisih_surplus_lo < 0) {
            $lo5 = "(";
            $selisih_surplus_lox = $selisih_surplus_lo * -1;
            $lo6 = ")";
        } else {
            $lo5 = "";
            $selisih_surplus_lox = $selisih_surplus_lo;
            $lo6 = "";
        }
        $selisih_surplus_lo1 = number_format($selisih_surplus_lox, "2", ",", ".");

        if ($surplus_lo_lalu == '' or $surplus_lo_lalu == 0) {
            $persen2 = '0,00';
        } else {
            $persen2 = ($surplus_lo / $surplus_lo_lalu) * 100;
            $persen2 = number_format($persen2, "2", ",", ".");
        }
        //surpuls def
        $selisih_surplus_def = $surplus_def_lo - $surplus_def_lo_lalu;
        if ($selisih_surplus_def < 0) {
            $lo100 = "(";
            $selisih_surplus_defx = $selisih_surplus_def * -1;
            $lo101 = ")";
        } else {
            $lo100 = "";
            $selisih_surplus_defx = $selisih_surplus_def;
            $lo101 = "";
        }
        $selisih_surplus_def1 = number_format($selisih_surplus_defx, "2", ",", ".");

        if ($surplus_def_lo_lalu == '' or $surplus_def_lo_lalu == 0) {
            $persen100 = '0,00';
        } else {
            $persen100 = ($surplus_def_lo / $surplus_def_lo_lalu) * 100;
            $persen100 = number_format($persen100, "2", ",", ".");
        }

        //end
        $sqllo5 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73','74') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo5 = $this->db->query($sqllo5);
        $penlo3 = $querylo5->row();
        $pen_lo3 = $penlo3->nilai;
        $pen_lo31 = number_format($penlo3->nilai, "2", ",", ".");

        $sqllo6 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73','74') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo6 = $this->db->query($sqllo6);
        $penlo4 = $querylo6->row();
        $pen_lo_lalu4 = $penlo4->nilai;
        $pen_lo_lalu41 = number_format($penlo4->nilai, "2", ",", ".");

        $sqllo7 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo7 = $this->db->query($sqllo7);
        $bello5 = $querylo7->row();
        $bel_lo5 = $bello5->nilai;
        $bel_lo51 = number_format($bello5->nilai, "2", ",", ".");

        $sqllo8 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','83') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo8 = $this->db->query($sqllo8);
        $bello6 = $querylo8->row();
        $bel_lo_lalu6 = $bello6->nilai;
        $bel_lo_lalu61 = number_format($bello6->nilai, "2", ",", ".");

        //calvin kenaikan/penurunan | 13 feburary 2022
        $sqllo22 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74','85') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo22 = $this->db->query($sqllo22);
        $deflo22 = $querylo22->row();
        $def_lo22 = $deflo22->nilai;
        $def_lo221 = number_format($deflo22->nilai, "2", ",", ".");

        $sqllo23 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74','85') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo23 = $this->db->query($sqllo23);
        $def23 = $querylo23->row();
        $def_lo_lalu23 = $def23->nilai;
        $def_lo_lalu231 = number_format($def23->nilai, "2", ",", ".");
        //end

        $surplus_lo2 = $pen_lo3 - $bel_lo5;
        if ($surplus_lo2 < 0) {
            $lo7 = "(";
            $surplus_lo2x = $surplus_lo2 * -1;
            $lo8 = ")";
        } else {
            $lo7 = "";
            $surplus_lo2x = $surplus_lo2;
            $lo8 = "";
        }
        $surplus_lo21 = number_format($surplus_lo2x, "2", ",", ".");

        $surplus_lo_lalu2 = $pen_lo_lalu4 - $bel_lo_lalu6;
        if ($surplus_lo_lalu2 < 0) {
            $lo9 = "(";
            $surplus_lo_lalu2x = $surplus_lo_lalu2 * -1;
            $lo10 = ")";
        } else {
            $lo9 = "";
            $surplus_lo_lalu2x = $surplus_lo_lalu2;
            $lo10 = "";
        }
        $surplus_lo_lalu21 = number_format($surplus_lo_lalu2x, "2", ",", ".");

        $selisih_surplus_lo2 = $surplus_lo2 - $surplus_lo_lalu2;
        if ($selisih_surplus_lo2 < 0) {
            $lo11 = "(";
            $selisih_surplus_lo2x = $selisih_surplus_lo2 * -1;
            $lo12 = ")";
        } else {
            $lo11 = "";
            $selisih_surplus_lo2x = $selisih_surplus_lo2;
            $lo12 = "";
        }
        $selisih_surplus_lo21 = number_format($selisih_surplus_lo2x, "2", ",", ".");

        // calvin | 13 february 2022
        $selisih_def_lo22 = $def_lo22 - $def_lo_lalu23;
        //end

        if ($surplus_lo_lalu2 == '' or $surplus_lo_lalu2 == 0) {
            $persen3 = '0,00';
        } else {
            $persen3 = ($surplus_lo2 / $surplus_lo_lalu2) * 100;
            $persen3 = number_format($persen3, "2", ",", ".");
        }

        $sqllo9 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('7') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('8') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo12 = $this->db->query($sqllo12);
        $bello10 = $querylo12->row();
        $bel_lo_lalu10 = $bello10->nilai;
        $bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

        $surplus_lo3 = $pen_lo7 - $bel_lo9;
        if ($surplus_lo3 < 0) {
            $lo13 = "(";
            $surplus_lo3x = $surplus_lo3 * -1;
            $lo14 = ")";
        } else {
            $lo13 = "";
            $surplus_lo3x = $surplus_lo3;
            $lo14 = "";
        }
        $surplus_lo31 = number_format($surplus_lo3x, "2", ",", ".");

        $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
        if ($surplus_lo_lalu3 < 0) {
            $lo15 = "(";
            $surplus_lo_lalu3x = $surplus_lo_lalu3 * -1;
            $lo16 = ")";
        } else {
            $lo15 = "";
            $surplus_lo_lalu3x = $surplus_lo_lalu3;
            $lo16 = "";
        }
        $surplus_lo_lalu31 = number_format($surplus_lo_lalu3x, "2", ",", ".");

        $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
        if ($selisih_surplus_lo3 < 0) {
            $lo17 = "(";
            $selisih_surplus_lo3x = $selisih_surplus_lo3 * -1;
            $lo18 = ")";
        } else {
            $lo17 = "";
            $selisih_surplus_lo3x = $selisih_surplus_lo3;
            $lo18 = "";
        }
        $selisih_surplus_lo31 = number_format($selisih_surplus_lo3x, "2", ",", ".");

        if ($surplus_lo_lalu3 == '' or $surplus_lo_lalu3 == 0) {
            $persen4 = '0,00';
        } else {
            $persen4 = ($surplus_lo3 / $surplus_lo_lalu3) * 100;
            $persen4 = number_format($persen4, "2", ",", ".");
        }


        $sqllo13 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo13 = $this->db->query($sqllo13);
        $penlo11 = $querylo13->row();
        $pen_lo11 = $penlo11->nilai;
        $pen_lo111 = number_format($penlo11->nilai, "2", ",", ".");

        $sqllo14 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo14 = $this->db->query($sqllo14);
        $penlo12 = $querylo14->row();
        $pen_lo_lalu12 = $penlo12->nilai;
        $pen_lo_lalu121 = number_format($penlo12->nilai, "2", ",", ".");

        $sqllo15 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('83') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo15 = $this->db->query($sqllo15);
        $bello13 = $querylo15->row();
        $bel_lo13 = $bello13->nilai;
        $bel_lo131 = number_format($bello13->nilai, "2", ",", ".");

        $sqllo16 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('83') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo16 = $this->db->query($sqllo16);
        $bello14 = $querylo16->row();
        $bel_lo_lalu14 = $bello14->nilai;
        $bel_lo_lalu141 = number_format($bello14->nilai, "2", ",", ".");

        $surplus_lo4 = $pen_lo11 - $bel_lo13;
        if ($surplus_lo4 < 0) {
            $lo19 = "(";
            $surplus_lo4x = $surplus_lo4 * -1;
            $lo20 = ")";
        } else {
            $lo19 = "";
            $surplus_lo4x = $surplus_lo4;
            $lo20 = "";
        }
        $surplus_lo41 = number_format($surplus_lo4x, "2", ",", ".");

        $surplus_lo_lalu4 = $pen_lo_lalu12 - $bel_lo_lalu14;
        if ($surplus_lo_lalu4 < 0) {
            $lo21 = "(";
            $surplus_lo_lalu4x = $surplus_lo_lalu4 * -1;
            $lo22 = ")";
        } else {
            $lo21 = "";
            $surplus_lo_lalu4x = $surplus_lo_lalu4;
            $lo22 = "";
        }
        $surplus_lo_lalu41 = number_format($surplus_lo_lalu4x, "2", ",", ".");

        $selisih_surplus_lo4 = $surplus_lo4 - $surplus_lo_lalu4;
        if ($selisih_surplus_lo4 < 0) {
            $lo23 = "(";
            $selisih_surplus_lo4x = $selisih_surplus_lo4 * -1;
            $lo24 = ")";
        } else {
            $lo23 = "";
            $selisih_surplus_lo4x = $selisih_surplus_lo4;
            $lo24 = "";
        }
        $selisih_surplus_lo41 = number_format($selisih_surplus_lo4x, "2", ",", ".");

        if ($surplus_lo_lalu4 == '' or $surplus_lo_lalu4 == 0) {
            $persen5 = '0,00';
        } else {
            $persen5 = ($surplus_lo4 / $surplus_lo_lalu4) * 100;
            $persen5 = number_format($persen5, "2", ",", ".");
        }


        $sqllo17 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('75') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo17 = $this->db->query($sqllo17);
        $penlo15 = $querylo17->row();
        $pen_lo15 = $penlo15->nilai;
        $pen_lo151 = number_format($penlo15->nilai, "2", ",", ".");

        $sqllo18 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('75') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo18 = $this->db->query($sqllo18);
        $penlo16 = $querylo18->row();
        $pen_lo_lalu16 = $penlo16->nilai;
        $pen_lo_lalu161 = number_format($penlo16->nilai, "2", ",", ".");

        $sqllo19 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('84') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo19 = $this->db->query($sqllo19);
        $bello17 = $querylo19->row();
        $bel_lo17 = $bello17->nilai;
        $bel_lo171 = number_format($bello17->nilai, "2", ",", ".");

        $sqllo20 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('84') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
        $querylo20 = $this->db->query($sqllo20);
        $bello18 = $querylo20->row();
        $bel_lo_lalu18 = $bello18->nilai;
        $bel_lo_lalu181 = number_format($bello18->nilai, "2", ",", ".");

        $surplus_lo5 = $pen_lo15 - $bel_lo17;
        if ($surplus_lo5 < 0) {
            $lo25 = "(";
            $surplus_lo5x = $surplus_lo5 * -1;
            $lo26 = ")";
        } else {
            $lo25 = "";
            $surplus_lo5x = $surplus_lo5;
            $lo26 = "";
        }
        $surplus_lo51 = number_format($surplus_lo5x, "2", ",", ".");

        $surplus_lo_lalu5 = $pen_lo_lalu16 - $bel_lo_lalu18;
        if ($surplus_lo_lalu5 < 0) {
            $lo27 = "(";
            $surplus_lo_lalu5x = $surplus_lo_lalu5 * -1;
            $lo28 = ")";
        } else {
            $lo27 = "";
            $surplus_lo_lalu5x = $surplus_lo_lalu5;
            $lo28 = "";
        }
        $surplus_lo_lalu51 = number_format($surplus_lo_lalu5x, "2", ",", ".");

        $selisih_surplus_lo5 = $surplus_lo5 - $surplus_lo_lalu5;
        if ($selisih_surplus_lo5 < 0) {
            $lo29 = "(";
            $selisih_surplus_lo5x = $selisih_surplus_lo5 * -1;
            $lo30 = ")";
        } else {
            $lo29 = "";
            $selisih_surplus_lo5x = $selisih_surplus_lo5;
            $lo30 = "";
        }
        $selisih_surplus_lo51 = number_format($selisih_surplus_lo5x, "2", ",", ".");

        if ($surplus_lo_lalu5 == '' or $surplus_lo_lalu5 == 0) {
            $persen6 = '0,00';
        } else {
            $persen6 = ($surplus_lo5 / $surplus_lo_lalu5) * 100;
            $persen6 = number_format($persen6, "2", ",", ".");
        }


        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);

        $cRet = '';


        $sclient = $this->akuntansi_model->get_sclient();
        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                    <td rowspan=\"4\" align=\"left\" width=\"2%\">
                    <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"60\" height=\"70\" />
					</td>
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
          <tr>
            <td align=\"center\"><strong>$nm_skpd</strong></td>
          </tr> 
                    <tr>
                         <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
                            <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NO</b></td>                            
                            <td  colspan =\"5\" bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>Kenaikan /</br>(Penurunan)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td colspan =\"5\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>";

        $sqlmaplo = "SELECT seq, nor, uraian, bold,isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak,parent,kode FROM map_lo_pemda 
                   GROUP BY seq, nor, uraian, bold, isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet'),parent, kode ORDER BY seq";

        $querymaplo = $this->db->query($sqlmaplo);
        $no     = 0;
        $arr_parent = [];
        $surplus_lo = 0;
        $surplus_lo_thnlalu = 0;
        $selisih_surplus_lo = 0;

        foreach ($querymaplo->result() as $loquery) {
            $operator = $loquery->kode;
            $seq = $loquery->seq;
            $nama      = $loquery->uraian;
            $n         = $loquery->kode_1;
            $n       = ($n == "-" ? "'-'" : $n);
            $n2        = $loquery->kode_2;
            $n2      = ($n2 == "-" ? "'-'" : $n2);
            $n3        = $loquery->kode_3;
            $n3      = ($n3 == "-" ? "'-'" : $n3);
            $normal    = $loquery->cetak;
            $bold      = $loquery->bold;

            if ($normal != "") {
                $quelo01   = "SELECT SUM($normal) as nilai FROM $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
                $quelo02 = $this->db->query($quelo01);
                $quelo03 = $quelo02->row();
                $nil     = $quelo03->nilai;
                $nilai    = number_format($quelo03->nilai, "2", ",", ".");

                $quelo04   = "SELECT SUM($normal) as nilai FROM $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang_1 and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
                $quelo05 = $this->db->query($quelo04);
                $quelo06 = $quelo05->row();
                $nil_lalu     = $quelo06->nilai;
                $nilai_lalu    = number_format($quelo06->nilai, "2", ",", ".");

                if ($nil < 0) {
                    $lo88 = "(";
                    $nilx = $nil * -1;
                    $lo89 = ")";
                } else {
                    $lo88 = "";
                    $nilx = $nil;
                    $lo89 = "";
                }
                $nil1 = number_format($nilx, "2", ",", ".");

                if ($nil_lalu < 0) {
                    $lo99 = "(";
                    $nil_lalux = $nil_lalu * -1;
                    $lo98 = ")";
                } else {
                    $lo99 = "";
                    $nil_lalux = $nil_lalu;
                    $lo98 = "";
                }
                $nil_lalu1 = number_format($nil_lalux, "2", ",", ".");

                $real_nilai = $nil - $nil_lalu;
                if ($real_nilai < 0) {
                    $lo0 = "(";
                    $real_nilaix = $real_nilai * -1;
                    $lo00 = ")";
                } else {
                    $lo0 = "";
                    $real_nilaix = $real_nilai;
                    $lo00 = "";
                }
                $real_nilai1 = number_format($real_nilaix, "2", ",", ".");

                if ($nil_lalu == '' or $nil_lalu == 0) {
                    $persen1 = '0,00';
                } else {
                    $persen1 = ($nil / $nil_lalu) * 100;
                    $persen1 = number_format($persen1, "2", ",", ".");
                }
                if ($loquery->parent) {
                    $arr_parent[] = array(
                        "seq" => $seq,
                        "parent" => $loquery->parent,
                        "uraian" => $nama,
                        "nilai" => ($operator == '-') ? $nil * -1 : $nil,
                        "nilai_lalu" => ($operator == '-') ? $nil_lalu  * -1 : $nil_lalu
                    );
                }
            }
            $no       = $no + 1;
            switch ($loquery->bold) {
                case 0:
                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 1:
                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 2:
                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                     <td colspan =\"4\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"></td>
                                     
                                 </tr>";
                    break;
                case 3:
                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td> 
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                  
                                     <td colspan =\"3\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo88$nil1$lo89</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo99$nil_lalu1$lo98</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo0$real_nilai1$lo00</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persen1</td>
                                 </tr>";
                    //===================== Objek         
                    $sql = "SELECT c.kd_rek4 as kd_rek,c.nm_rek4 as nm_rek,sum(a.$normal) as 'thn_ini',
                                        (
                                            select isnull(sum(d.$normal),0) from trdju_pkd d 
                                            join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
                                            where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,6)=c.kd_rek4 
                                            and left(e.kd_skpd,22)=left(b.kd_skpd,22)
                                        ) as 'thn_lalu' 
                                        from trdju_pkd a 
                                        join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                        join ms_rek4 c on LEFT(a.kd_rek6,6)=c.kd_rek4
                                        where left(b.kd_skpd,22)=left('$kd_skpd',22) and left(kd_rek6,4) in ($n) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
                                        group by c.kd_rek4,c.nm_rek4,left(b.kd_skpd,22)
                                        union all
                                        SELECT c.kd_rek5 as kd_rek,c.nm_rek5 as nm_rek,sum(a.$normal) as 'thn_ini',
                                        (
                                            select isnull(sum(d.$normal),0) from trdju_pkd d 
                                            join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
                                            where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,8)=c.kd_rek5 
                                            and left(e.kd_skpd,22)=left(b.kd_skpd,22)
                                        ) as 'thn_lalu' 
                                        from trdju_pkd a 
                                        join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                        join ms_rek5 c on LEFT(a.kd_rek6,8)=c.kd_rek5
                                        where left(b.kd_skpd,22)=left('$kd_skpd',22) and left(kd_rek6,4) in ($n) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
                                        group by c.kd_rek5,c.nm_rek5,left(b.kd_skpd,22)
                                        order by kd_rek";

                    $query = $this->db->query($sql);
                    foreach ($query->result() as $row) {
                        $no = $no + 1;
                        $nama = $row->nm_rek;
                        $nilai = $this->custom->rp_minus($row->thn_ini);
                        $nilai_lalu = $this->custom->rp_minus($row->thn_lalu);
                        $real_nilai1 = $this->custom->rp_minus($row->thn_ini - $row->thn_lalu);
                        if ($row->thn_lalu == '' or $row->thn_lalu == 0) {
                            $persen1 = '0,00';
                        } else {
                            $persen1 = ($row->thn_ini / $row->thn_lalu) * 100;
                            $persen1 = number_format($persen1, "2", ",", ".");
                        }

                        if (strlen($row->kd_rek) == 6) { //objek
                            $cRet    .= "<tr>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                <td colspan =\"2\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
                                            </tr>";
                        } else {
                            $cRet    .= "<tr>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                <td colspan =\"1\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
                                                <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
                                            </tr>";
                        }
                    }
                    //===================== End Objek
                    break;
                case 4:
                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td> 
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                    
                                     <td colspan =\"2\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo88$nil1$lo89</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo99$nil_lalu1$lo98</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo0$real_nilai1$lo00</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"><b>$persen1</b></td>
                                 </tr>";
                    break;
                case 5:
                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo1$surplus_lo1$lo2</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo3$surplus_lo_lalu1$lo4</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo5$selisih_surplus_lo1$lo6</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"><b>$persen2</b></td>
                                 </tr>";
                    break;
                case 6:
                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td> 
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                   
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo96$surplus_def_lo1$lo97</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo99$surplus_def_lo_lalu1$lo98</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_def1$lo12</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"><b>$persen100</b></td>
                                 </tr>";
                    break;
                case 7:
                    foreach ($arr_parent as $key => $value) {
                        if ($value['parent'] == $seq) {
                            $surplus_lo += $value['nilai'];
                            $surplus_lo_thnlalu += $value['nilai_lalu'];
                        }
                    }
                    $selisih_surplus_lo = $surplus_lo - $surplus_lo_thnlalu;

                    $cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td> 
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                    
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo96$surplus_def_lo1$lo97</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo99$surplus_def_lo_lalu1$lo98</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_def1$lo12</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"><b>$persen100</td>
                                 </tr>";
                    break;
            }
        }

        $cRet         .= "</table>";
        //        $data['prev']  = $cRet;
        //        $data['sikap'] = 'preview';
        //        
        //        
        //        
        //        $this->template->set('title', 'LAPORAN OPERASIONAL'); 
        //        $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
        //$this->template->load('template','anggaran/rka/perkadaII',$data); 
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$tglttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $namax = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }



        if ($nip == '00000000 000000 0 000') {
            $cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Melawi , ' . $tanggal . '</TD>
							</TR>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" ><b>' . $jabatan . '</b></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>   
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>                       
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" ><b>' . $namax . '</b></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > </TD>
							</TR>
							</TABLE><br/>';
        } else {
            $cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Melawi , ' . $tanggal . '</TD>
							</TR>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" ><b>' . $jabatan . '</b></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>   
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
							</TR>                       
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" ><u><b>' . $namax . '</b></u></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $nip . '</TD>
							</TR>
							</TABLE><br/>';
        }
        $cRet .=       " </table>";
        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul  = ("LO KONSOL SKPD RINCIAN $cbulan");
        $this->template->set('title', 'LO KONSOL UNIT RINCIAN $cbulan');
        switch ($pilih) {
            case 0;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                echo $cRet;
                break;
            case 1;
                // $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
                echo "<title>LO KONSOL SKPD RINCIAN $cbulan</title>";
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

    // andika penambahan per sub rincian (skpd)
     function ctk_lra_lo_pemda_org_subrincian($cbulan = "", $kd_skpd = "", $pilih = 1,$tglttd = "", $ttd = "")
     {
         $this->load->library('custom');
         //$id = $skpd;
         $cetak = '2'; //$ctk; 
         $tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
         $tglttd = str_replace('n', ' ', $tglttd);
         $id     = $this->session->userdata('kdskpd');
         $thn_ang = $this->session->userdata('pcThang');
         $thn_ang_1 = $thn_ang - 1;
         //$laporan=$lap; 
 
         // if ($cetak == '1') {
         //           $skpd = '';
         //           $skpd1 = '';           
         //       } else {             
         $skpd = "AND kd_skpd='$kd_skpd'";
         $skpd1 = "AND b.kd_skpd='$kd_skpd'";
         //}  
 
         $y123 = ")";
         $x123 = "(";
         $sqlsc = "SELECT kd_skpd, nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd'";
         $sqlsclient = $this->db->query($sqlsc);
         foreach ($sqlsclient->result() as $rowsc) {
             $nmskpd     = $rowsc->nm_skpd;
         }
         $nm_skpd = strtoupper($nmskpd);
         // INSERT DATA
         /*
        $sqldns="SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$kd_skpd'";
                  $sqlskpd=$this->db->query($sqldns);
                  foreach ($sqlskpd->result() as $rowdns)
                 {
                     $kd_urusan=$rowdns->kd_u;                    
                     $nm_urusan= $rowdns->nm_u;
                     $kd_skpd  = $rowdns->kd_sk;
                     $nm_skpd  = $rowdns->nm_sk;
                 } 
      */
         // created by henri_tb
 
 
         $trhju = "trhju_pkd";
         $trdju = "trdju_pkd";
 
         $sqllo1 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo1 = $this->db->query($sqllo1);
         $penlo = $querylo1->row();
         $pen_lo = $penlo->nilai;
         $pen_lo1 = number_format($penlo->nilai, "2", ",", ".");
 
         $sqllo2 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo2 = $this->db->query($sqllo2);
         $penlo2 = $querylo2->row();
         $pen_lo_lalu = $penlo2->nilai;
         $pen_lo_lalu1 = number_format($penlo2->nilai, "2", ",", ".");
 
         $sqllo3 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','84') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo3 = $this->db->query($sqllo3);
         $bello = $querylo3->row();
         $bel_lo = $bello->nilai;
         $bel_lo1 = number_format($bello->nilai, "2", ",", ".");
 
         $sqllo4 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','84') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo4 = $this->db->query($sqllo4);
         $bello2 = $querylo4->row();
         $bel_lo_lalu = $bello2->nilai;
         $bel_lo_lalu1 = number_format($bello2->nilai, "2", ",", ".");
 
         // calvin 11 february 2022
             
         $sqllo5 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74','83') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo5 = $this->db->query($sqllo5);
         $def5 = $querylo5->row();
         $def_lo = $def5->nilai;
         $def_lo1 = number_format($def5->nilai, "2", ",", ".");
 
         $sqllo6 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74','83') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo6 = $this->db->query($sqllo6);
         $def6 = $querylo6->row();
         $del_lo_lalu = $def6->nilai;
         $del_lo_lalu1 = number_format($def6->nilai, "2", ",", ".");
 
         // end
 
         $surplus_lo = $pen_lo - $bel_lo;
         if ($surplus_lo < 0) {
             $lo1 = "(";
             $surplus_lox = $surplus_lo * -1;
             $lo2 = ")";
         } else {
             $lo1 = "";
             $surplus_lox = $surplus_lo;
             $lo2 = "";
         }
         $surplus_lo1 = number_format($surplus_lox, "2", ",", ".");
 
         $surplus_lo_lalu = $pen_lo_lalu - $bel_lo_lalu;
         if ($surplus_lo_lalu < 0) {
             $lo3 = "(";
             $surplus_lo_lalux = $surplus_lo_lalu * -1;
             $lo4 = ")";
         } else {
             $lo3 = "";
             $surplus_lo_lalux = $surplus_lo_lalu;
             $lo4 = "";
         }
         $surplus_lo_lalu1 = number_format($surplus_lo_lalux, "2", ",", ".");
         
         // calvin 
         $surplus_def_lo = $surplus_lo - $def_lo;
         if ($surplus_def_lo < 0) {
             $lo96 = "(";
             $surplus_def= $surplus_def_lo * -1;
             $lo97 = ")";
         } else {
             $lo96 = "";
             $surplus_def = $surplus_def_lo;
             $lo97 = "";
         }
         $surplus_def_lo1 = number_format($surplus_def, "2", ",", ".");
         
         $surplus_def_lo_lalu = $surplus_lo_lalu - $del_lo_lalu;
         if ($surplus_def_lo_lalu < 0) {
             $lo991 = "(";
             $surplus_def_lalux = $surplus_def_lo_lalu * -1;
             $lo981 = ")";
         } else {
             $lo991 = "";
             $surplus_def_lalux = $surplus_def_lo_lalu;
             $lo981 = "";
         }
         $surplus_def_lo_lalu1 = number_format($surplus_def_lalux, "2", ",", ".");
 
         //end
 
         $selisih_surplus_lo = $surplus_lo - $surplus_lo_lalu;
         if ($selisih_surplus_lo < 0) {
             $lo5 = "(";
             $selisih_surplus_lox = $selisih_surplus_lo * -1;
             $lo6 = ")";
         } else {
             $lo5 = "";
             $selisih_surplus_lox = $selisih_surplus_lo;
             $lo6 = "";
         }
         $selisih_surplus_lo1 = number_format($selisih_surplus_lox, "2", ",", ".");
 
         if ($surplus_lo_lalu == '' or $surplus_lo_lalu == 0) {
             $persen2 = '0,00';
         } else {
             $persen2 = ($surplus_lo / $surplus_lo_lalu) * 100;
             $persen2 = number_format($persen2, "2", ",", ".");
         }
         //surpuls def
         $selisih_surplus_def = $surplus_def_lo - $surplus_def_lo_lalu;
         if ($selisih_surplus_def < 0){
             $lo100 = "(";
             $selisih_surplus_defx = $selisih_surplus_def * -1;
             $lo101 = ")";
         } else {
             $lo100 = "";
             $selisih_surplus_defx = $selisih_surplus_def;
             $lo101 = "";
         }
         $selisih_surplus_def1 = number_format($selisih_surplus_defx, "2", ",",".");
         
         if ($surplus_def_lo_lalu == '' or $surplus_def_lo_lalu == 0) {
             $persen100 = '0,00';
         } else {
             $persen100 = ($surplus_def_lo / $surplus_def_lo_lalu) * 100;
             $persen100 = number_format($persen100, "2", ",", ".");
         }
 
         //end
         $sqllo5 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73','74') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo5 = $this->db->query($sqllo5);
         $penlo3 = $querylo5->row();
         $pen_lo3 = $penlo3->nilai;
         $pen_lo31 = number_format($penlo3->nilai, "2", ",", ".");
 
         $sqllo6 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73','74') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo6 = $this->db->query($sqllo6);
         $penlo4 = $querylo6->row();
         $pen_lo_lalu4 = $penlo4->nilai;
         $pen_lo_lalu41 = number_format($penlo4->nilai, "2", ",", ".");
 
         $sqllo7 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo7 = $this->db->query($sqllo7);
         $bello5 = $querylo7->row();
         $bel_lo5 = $bello5->nilai;
         $bel_lo51 = number_format($bello5->nilai, "2", ",", ".");
 
         $sqllo8 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','83') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo8 = $this->db->query($sqllo8);
         $bello6 = $querylo8->row();
         $bel_lo_lalu6 = $bello6->nilai;
         $bel_lo_lalu61 = number_format($bello6->nilai, "2", ",", ".");
 
         //calvin kenaikan/penurunan | 13 feburary 2022
         $sqllo22 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74','85') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo22 = $this->db->query($sqllo22);
         $deflo22 = $querylo22->row();
         $def_lo22 = $deflo22->nilai;
         $def_lo221 = number_format($deflo22->nilai, "2", ",", ".");
 
         $sqllo23 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74','85') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo23 = $this->db->query($sqllo23);
         $def23 = $querylo23->row();
         $def_lo_lalu23 = $def23->nilai;
         $def_lo_lalu231 = number_format($def23->nilai, "2", ",", ".");
         //end
 
         $surplus_lo2 = $pen_lo3 - $bel_lo5;
         if ($surplus_lo2 < 0) {
             $lo7 = "(";
             $surplus_lo2x = $surplus_lo2 * -1;
             $lo8 = ")";
         } else {
             $lo7 = "";
             $surplus_lo2x = $surplus_lo2;
             $lo8 = "";
         }
         $surplus_lo21 = number_format($surplus_lo2x, "2", ",", ".");
 
         $surplus_lo_lalu2 = $pen_lo_lalu4 - $bel_lo_lalu6;
         if ($surplus_lo_lalu2 < 0) {
             $lo9 = "(";
             $surplus_lo_lalu2x = $surplus_lo_lalu2 * -1;
             $lo10 = ")";
         } else {
             $lo9 = "";
             $surplus_lo_lalu2x = $surplus_lo_lalu2;
             $lo10 = "";
         }
         $surplus_lo_lalu21 = number_format($surplus_lo_lalu2x, "2", ",", ".");
 
         $selisih_surplus_lo2 = $surplus_lo2 - $surplus_lo_lalu2;
         if ($selisih_surplus_lo2 < 0) {
             $lo11 = "(";
             $selisih_surplus_lo2x = $selisih_surplus_lo2 * -1;
             $lo12 = ")";
         } else {
             $lo11 = "";
             $selisih_surplus_lo2x = $selisih_surplus_lo2;
             $lo12 = "";
         }
         $selisih_surplus_lo21 = number_format($selisih_surplus_lo2x, "2", ",", ".");
         
         // calvin | 13 february 2022
         $selisih_def_lo22 = $def_lo22 - $def_lo_lalu23;
         //end
 
         if ($surplus_lo_lalu2 == '' or $surplus_lo_lalu2 == 0) {
             $persen3 = '0,00';
         } else {
             $persen3 = ($surplus_lo2 / $surplus_lo_lalu2) * 100;
             $persen3 = number_format($persen3, "2", ",", ".");
         }
 
         $sqllo9 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('7') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo9 = $this->db->query($sqllo9);
         $penlo7 = $querylo9->row();
         $pen_lo7 = $penlo7->nilai;
         $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");
 
         $sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo10 = $this->db->query($sqllo10);
         $penlo8 = $querylo10->row();
         $pen_lo_lalu8 = $penlo8->nilai;
         $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");
 
         $sqllo11 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('8') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo11 = $this->db->query($sqllo11);
         $bello9 = $querylo11->row();
         $bel_lo9 = $bello9->nilai;
         $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");
 
         $sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo12 = $this->db->query($sqllo12);
         $bello10 = $querylo12->row();
         $bel_lo_lalu10 = $bello10->nilai;
         $bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");
 
         $surplus_lo3 = $pen_lo7 - $bel_lo9;
         if ($surplus_lo3 < 0) {
             $lo13 = "(";
             $surplus_lo3x = $surplus_lo3 * -1;
             $lo14 = ")";
         } else {
             $lo13 = "";
             $surplus_lo3x = $surplus_lo3;
             $lo14 = "";
         }
         $surplus_lo31 = number_format($surplus_lo3x, "2", ",", ".");
 
         $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
         if ($surplus_lo_lalu3 < 0) {
             $lo15 = "(";
             $surplus_lo_lalu3x = $surplus_lo_lalu3 * -1;
             $lo16 = ")";
         } else {
             $lo15 = "";
             $surplus_lo_lalu3x = $surplus_lo_lalu3;
             $lo16 = "";
         }
         $surplus_lo_lalu31 = number_format($surplus_lo_lalu3x, "2", ",", ".");
 
         $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
         if ($selisih_surplus_lo3 < 0) {
             $lo17 = "(";
             $selisih_surplus_lo3x = $selisih_surplus_lo3 * -1;
             $lo18 = ")";
         } else {
             $lo17 = "";
             $selisih_surplus_lo3x = $selisih_surplus_lo3;
             $lo18 = "";
         }
         $selisih_surplus_lo31 = number_format($selisih_surplus_lo3x, "2", ",", ".");
 
         if ($surplus_lo_lalu3 == '' or $surplus_lo_lalu3 == 0) {
             $persen4 = '0,00';
         } else {
             $persen4 = ($surplus_lo3 / $surplus_lo_lalu3) * 100;
             $persen4 = number_format($persen4, "2", ",", ".");
         }
 
 
         $sqllo13 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo13 = $this->db->query($sqllo13);
         $penlo11 = $querylo13->row();
         $pen_lo11 = $penlo11->nilai;
         $pen_lo111 = number_format($penlo11->nilai, "2", ",", ".");
 
         $sqllo14 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo14 = $this->db->query($sqllo14);
         $penlo12 = $querylo14->row();
         $pen_lo_lalu12 = $penlo12->nilai;
         $pen_lo_lalu121 = number_format($penlo12->nilai, "2", ",", ".");
 
         $sqllo15 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('83') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo15 = $this->db->query($sqllo15);
         $bello13 = $querylo15->row();
         $bel_lo13 = $bello13->nilai;
         $bel_lo131 = number_format($bello13->nilai, "2", ",", ".");
 
         $sqllo16 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('83') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo16 = $this->db->query($sqllo16);
         $bello14 = $querylo16->row();
         $bel_lo_lalu14 = $bello14->nilai;
         $bel_lo_lalu141 = number_format($bello14->nilai, "2", ",", ".");
 
         $surplus_lo4 = $pen_lo11 - $bel_lo13;
         if ($surplus_lo4 < 0) {
             $lo19 = "(";
             $surplus_lo4x = $surplus_lo4 * -1;
             $lo20 = ")";
         } else {
             $lo19 = "";
             $surplus_lo4x = $surplus_lo4;
             $lo20 = "";
         }
         $surplus_lo41 = number_format($surplus_lo4x, "2", ",", ".");
 
         $surplus_lo_lalu4 = $pen_lo_lalu12 - $bel_lo_lalu14;
         if ($surplus_lo_lalu4 < 0) {
             $lo21 = "(";
             $surplus_lo_lalu4x = $surplus_lo_lalu4 * -1;
             $lo22 = ")";
         } else {
             $lo21 = "";
             $surplus_lo_lalu4x = $surplus_lo_lalu4;
             $lo22 = "";
         }
         $surplus_lo_lalu41 = number_format($surplus_lo_lalu4x, "2", ",", ".");
 
         $selisih_surplus_lo4 = $surplus_lo4 - $surplus_lo_lalu4;
         if ($selisih_surplus_lo4 < 0) {
             $lo23 = "(";
             $selisih_surplus_lo4x = $selisih_surplus_lo4 * -1;
             $lo24 = ")";
         } else {
             $lo23 = "";
             $selisih_surplus_lo4x = $selisih_surplus_lo4;
             $lo24 = "";
         }
         $selisih_surplus_lo41 = number_format($selisih_surplus_lo4x, "2", ",", ".");
 
         if ($surplus_lo_lalu4 == '' or $surplus_lo_lalu4 == 0) {
             $persen5 = '0,00';
         } else {
             $persen5 = ($surplus_lo4 / $surplus_lo_lalu4) * 100;
             $persen5 = number_format($persen5, "2", ",", ".");
         }
 
 
         $sqllo17 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('75') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo17 = $this->db->query($sqllo17);
         $penlo15 = $querylo17->row();
         $pen_lo15 = $penlo15->nilai;
         $pen_lo151 = number_format($penlo15->nilai, "2", ",", ".");
 
         $sqllo18 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('75') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo18 = $this->db->query($sqllo18);
         $penlo16 = $querylo18->row();
         $pen_lo_lalu16 = $penlo16->nilai;
         $pen_lo_lalu161 = number_format($penlo16->nilai, "2", ",", ".");
 
         $sqllo19 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('84') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo19 = $this->db->query($sqllo19);
         $bello17 = $querylo19->row();
         $bel_lo17 = $bello17->nilai;
         $bel_lo171 = number_format($bello17->nilai, "2", ",", ".");
 
         $sqllo20 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('84') and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
         $querylo20 = $this->db->query($sqllo20);
         $bello18 = $querylo20->row();
         $bel_lo_lalu18 = $bello18->nilai;
         $bel_lo_lalu181 = number_format($bello18->nilai, "2", ",", ".");
 
         $surplus_lo5 = $pen_lo15 - $bel_lo17;
         if ($surplus_lo5 < 0) {
             $lo25 = "(";
             $surplus_lo5x = $surplus_lo5 * -1;
             $lo26 = ")";
         } else {
             $lo25 = "";
             $surplus_lo5x = $surplus_lo5;
             $lo26 = "";
         }
         $surplus_lo51 = number_format($surplus_lo5x, "2", ",", ".");
 
         $surplus_lo_lalu5 = $pen_lo_lalu16 - $bel_lo_lalu18;
         if ($surplus_lo_lalu5 < 0) {
             $lo27 = "(";
             $surplus_lo_lalu5x = $surplus_lo_lalu5 * -1;
             $lo28 = ")";
         } else {
             $lo27 = "";
             $surplus_lo_lalu5x = $surplus_lo_lalu5;
             $lo28 = "";
         }
         $surplus_lo_lalu51 = number_format($surplus_lo_lalu5x, "2", ",", ".");
 
         $selisih_surplus_lo5 = $surplus_lo5 - $surplus_lo_lalu5;
         if ($selisih_surplus_lo5 < 0) {
             $lo29 = "(";
             $selisih_surplus_lo5x = $selisih_surplus_lo5 * -1;
             $lo30 = ")";
         } else {
             $lo29 = "";
             $selisih_surplus_lo5x = $selisih_surplus_lo5;
             $lo30 = "";
         }
         $selisih_surplus_lo51 = number_format($selisih_surplus_lo5x, "2", ",", ".");
 
         if ($surplus_lo_lalu5 == '' or $surplus_lo_lalu5 == 0) {
             $persen6 = '0,00';
         } else {
             $persen6 = ($surplus_lo5 / $surplus_lo_lalu5) * 100;
             $persen6 = number_format($persen6, "2", ",", ".");
         }
 
 
         $modtahun = $thn_ang % 4;
 
         if ($modtahun = 0) {
             $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
         } else {
             $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
         }
 
         $arraybulan = explode(".", $nilaibulan);
 
         $cRet = '';
 
 
         $sclient = $this->akuntansi_model->get_sclient();
         $cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                     <tr>
                     <td rowspan=\"4\" align=\"left\" width=\"2%\">
                     <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"60\" height=\"70\" />
                     </td>
                          <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                     </tr>
           <tr>
             <td align=\"center\"><strong>$nm_skpd</strong></td>
           </tr> 
                     <tr>
                          <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
                     </tr>                    
                     <tr>
                          <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1</strong></td>
                     </tr>
                     <tr>
                          <td align=\"center\">&nbsp;</td>
                     </tr>
                   </table>";
 
         $cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                      <thead>                       
                         <tr>
                             <td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NO</b></td>                            
                             <td  colspan =\"6\" bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                             <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                             <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
                             <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>Kenaikan /</br>(Penurunan)</b></td>
                             <td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                         </tr>
                         
                      </thead>                   
                      <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                             <td colspan =\"6\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                             <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                             <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                             <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                             <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                         </tr>";
 
         $sqlmaplo = "SELECT seq, nor, uraian, bold,isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3,isnull(kode_4,'-') as kode_4, isnull(cetak,'debet-debet') as cetak,parent,kode FROM map_lo_pemda_subrinci 
                    GROUP BY seq, nor, uraian, bold, isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'),isnull(kode_4,'-'), isnull(cetak,'debet-debet'),parent, kode ORDER BY seq";
 
         $querymaplo = $this->db->query($sqlmaplo);
         $no     = 0;
         $arr_parent = [];
         $surplus_lo = 0;
         $surplus_lo_thnlalu = 0;
         $selisih_surplus_lo = 0;
 
         foreach ($querymaplo->result() as $loquery) {
             $operator = $loquery->kode;
             $seq = $loquery->seq;
             $nama      = $loquery->uraian;
             $n         = $loquery->kode_1;
             $n       = ($n == "-" ? "'-'" : $n);
             $n2        = $loquery->kode_2;
             $n2      = ($n2 == "-" ? "'-'" : $n2);
             $n3        = $loquery->kode_3;
             $n3      = ($n3 == "-" ? "'-'" : $n3);
             $n4        = $loquery->kode_4;
             $n4		   = ($n4 == "-" ? "'-'" : $n4);
             $normal    = $loquery->cetak;
             $bold      = $loquery->bold;
 
             if ($normal != "") {
             $quelo01   = "SELECT SUM($normal) as nilai FROM $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3) or left(kd_rek6,12) in ($n4)) and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
             $quelo02 = $this->db->query($quelo01);
             $quelo03 = $quelo02->row();
             $nil     = $quelo03->nilai;
             $nilai    = number_format($quelo03->nilai, "2", ",", ".");
 
             $quelo04   = "SELECT SUM($normal) as nilai FROM $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3) or left(kd_rek6,12) in ($n4)) and year(tgl_voucher)=$thn_ang_1 and left(kd_skpd,22)='$kd_skpd' AND kd_skpd<>'4.02.02.02'";
             $quelo05 = $this->db->query($quelo04);
             $quelo06 = $quelo05->row();
             $nil_lalu     = $quelo06->nilai;
             $nilai_lalu    = number_format($quelo06->nilai, "2", ",", ".");
             
             if($nil < 0){
                 $lo88 = "(";
                 $nilx = $nil * -1;
                 $lo89 = ")";
             } else {
                 $lo88 = "";
                 $nilx = $nil;
                 $lo89 = "";
             }
             $nil1 = number_format($nilx, "2", ",", ".");
 
             if($nil_lalu < 0){
                 $lo99 = "(";
                 $nil_lalux = $nil_lalu * -1;
                 $lo98 = ")";
             } else {
                 $lo99 = "";
                 $nil_lalux = $nil_lalu;
                 $lo98 = "";
             }
             $nil_lalu1 = number_format($nil_lalux, "2", ",", ".");
 
             $real_nilai = $nil - $nil_lalu;
             if ($real_nilai < 0) {
                 $lo0 = "(";
                 $real_nilaix = $real_nilai * -1;
                 $lo00 = ")";
             } else {
                 $lo0 = "";
                 $real_nilaix = $real_nilai;
                 $lo00 = "";
             }
             $real_nilai1 = number_format($real_nilaix, "2", ",", ".");
 
             if ($nil_lalu == '' or $nil_lalu == 0) {
                 $persen1 = '0,00';
             } else {
                 $persen1 = ($nil / $nil_lalu) * 100;
                 $persen1 = number_format($persen1, "2", ",", ".");
             }
             if ($loquery->parent) {
                 $arr_parent[] = array(
                     "seq" => $seq,
                     "parent" => $loquery->parent,
                     "uraian" => $nama,
                     "nilai" => ($operator == '-') ? $nil * -1 : $nil,
                     "nilai_lalu" => ($operator == '-') ? $nil_lalu  * -1 : $nil_lalu
                 );
             }
         }
             $no       = $no + 1;
             switch ($loquery->bold) {
                 case 0:
                     $cRet    .= "<tr>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td>                                     
                                      <td colspan =\"6\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"></td>
                                  </tr>";
                     break;
                 case 1:
                    $cRet    .= "<tr>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                      <td colspan =\"6\" style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"></td>
                                  </tr>";
                     break;
                 case 2:
                     $cRet    .= "<tr>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                      <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"></td>
                                      
                                  </tr>";
                     break;
                 case 3:
                     $cRet    .= "<tr>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td> 
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                  
                                      <td colspan =\"4\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo88$nil1$lo89</td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo99$nil_lalu1$lo98</td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo0$real_nilai1$lo00</td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persen1</td>
                                  </tr>";
                                 //===================== Objek / Sub Rincian         
                                 $sql = "SELECT c.kd_rek4 as kd_rek,c.nm_rek4 as nm_rek,sum(a.$normal) as 'thn_ini',
                                         (
                                             select isnull(sum(d.$normal),0) from trdju_pkd d 
                                             join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
                                             where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,6)=c.kd_rek4 
                                             and left(e.kd_skpd,22)=left(b.kd_skpd,22)
                                         ) as 'thn_lalu' 
                                         from trdju_pkd a 
                                         join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                         join ms_rek4 c on LEFT(a.kd_rek6,6)=c.kd_rek4
                                         where left(b.kd_skpd,22)=left('$kd_skpd',22) and (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3) or left(kd_rek6,12) in ($n4)) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
                                         group by c.kd_rek4,c.nm_rek4,left(b.kd_skpd,22)
                                         union all
                                         SELECT c.kd_rek5 as kd_rek,c.nm_rek5 as nm_rek,sum(a.$normal) as 'thn_ini',
                                         (
                                             select isnull(sum(d.$normal),0) from trdju_pkd d 
                                             join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
                                             where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,8)=c.kd_rek5 
                                             and left(e.kd_skpd,22)=left(b.kd_skpd,22)
                                         ) as 'thn_lalu' 
                                         from trdju_pkd a 
                                         join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                         join ms_rek5 c on LEFT(a.kd_rek6,8)=c.kd_rek5
                                         where left(b.kd_skpd,22)=left('$kd_skpd',22) and (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3) or left(kd_rek6,12) in ($n4)) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
                                         group by c.kd_rek5,c.nm_rek5,left(b.kd_skpd,22)
                                         union all
                                         SELECT c.kd_rek6 as kd_rek,c.nm_rek6 as nm_rek,sum(a.$normal) as 'thn_ini',
                                         (
                                             select isnull(sum(d.$normal),0) from trdju_pkd d 
                                             join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
                                             where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,12)=c.kd_rek6 
                                             and left(e.kd_skpd,22)=left(b.kd_skpd,22)
                                         ) as 'thn_lalu' 
                                         from trdju_pkd a 
                                         join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                         join ms_rek6 c on LEFT(a.kd_rek6,12)=c.kd_rek6
                                         where left(b.kd_skpd,22)=left('$kd_skpd',22) and (left(a.kd_rek6,4) in ($n) or left(a.kd_rek6,6) in ($n2) or left(a.kd_rek6,8) in ($n3) or left(a.kd_rek6,12) in ($n4)) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
                                         group by c.kd_rek6,c.nm_rek6,left(b.kd_skpd,22)
                                         order by kd_rek";
 
                                 $query = $this->db->query($sql);
                                 foreach($query->result() as $row){
                                 $no = $no + 1;
                                 $nama = $row->nm_rek;
                                 $nilai = $this->custom->rp_minus($row->thn_ini);
                                 $nilai_lalu = $this->custom->rp_minus($row->thn_lalu);
                                 $real_nilai1 = $this->custom->rp_minus($row->thn_ini - $row->thn_lalu);
                                 if( $row->thn_lalu=='' or $row->thn_lalu==0){
                                 $persen1 = '0,00';
                                 }else{
                                 $persen1 = ($row->thn_ini/$row->thn_lalu)*100;
                                 $persen1 = number_format($persen1,"2",",",".");
                                 }
 
                                 if(strlen($row->kd_rek)==6){ //objek
                                     $cRet    .= "<tr>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                 <td colspan =\"3\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
                                             </tr>";
                                 }else if(strlen($row->kd_rek)==12){ //sub rincian 
                                     $cRet    .= "<tr>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                 <td colspan =\"1\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
                                             </tr>";
                                 }else{
                                     $cRet    .= "<tr>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                 <td colspan =\"2\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
                                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
                                             </tr>";
                                     }
                                 }
                                 //===================== End Objek
                     break;
                 case 4:
                     $cRet    .= "<tr>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td> 
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>  
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                   
                                      <td colspan =\"2\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo88$nil1$lo89</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo99$nil_lalu1$lo98</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo0$real_nilai1$lo00</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"><b>$persen1</b></td>
                                  </tr>";
                     break;
                 case 5:
                     $cRet    .= "<tr>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo1$surplus_lo1$lo2</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo3$surplus_lo_lalu1$lo4</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo5$selisih_surplus_lo1$lo6</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"><b>$persen2</b></td>
                                  </tr>";
                     break;
                 case 6:
                     $cRet    .= "<tr>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td> 
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>   
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo96$surplus_def_lo1$lo97</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo991$surplus_def_lo_lalu1$lo981</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_def1$lo12</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"><b>$persen100</b></td>
                                  </tr>";
                     break;
                 case 7:
                     foreach ($arr_parent as $key => $value) {
                         if ($value['parent'] == $seq) {
                             $surplus_lo += $value['nilai'];
                             $surplus_lo_thnlalu += $value['nilai_lalu'];
                         }
                     }
                     $selisih_surplus_lo = $surplus_lo - $surplus_lo_thnlalu;
 
                     $cRet    .= "<tr>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td> 
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>   
                                      <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                    
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo96$surplus_def_lo1$lo97</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo99$surplus_def_lo_lalu1$lo98</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_def1$lo12</b></td>
                                      <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\"><b>$persen100</td>
                                  </tr>";
                     break;
             }
         }
 
             $cRet         .= "</table>";
         //        $data['prev']  = $cRet;
         //        $data['sikap'] = 'preview';
         //        
         //        
         //        
         //        $this->template->set('title', 'LAPORAN OPERASIONAL'); 
         //        $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
         //$this->template->load('template','anggaran/rka/perkadaII',$data); 
         $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$tglttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUP' or id='77')";
         $sqlttd = $this->db->query($sqlttd1);
         foreach ($sqlttd->result() as $rowttd) {
             $nip = $rowttd->nip;
             $namax = $rowttd->nm;
             $jabatan  = $rowttd->jab;
             $pangkat  = $rowttd->pangkat;
         }
 
         
         
         if ($nip == '00000000 000000 0 000'){
             $cRet .= '<br><br>
                 <TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
                             
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD align="center" > Melawi , ' . $tanggal . '</TD>
                             </TR>
                             
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD align="center" ><b>' . $jabatan . '</b></TD>
                             </TR>
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                             </TR>
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                             </TR>
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                             </TR>
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                             </TR>   
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                             </TR>                       
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD align="center" ><b>' . $namax . '</b></TD>
                             </TR>
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD align="center" >' . $pangkat . '</TD>
                             </TR>
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD align="center" > </TD>
                             </TR>
                             </TABLE><br/>';
             } else if ($nip == '19671126 199503 2 004'){
                 $cRet .= '<br><br>
                     <TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
                                 
                                 <TR>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                     <TD align="center" > Melawi , ' . $tanggal . '</TD>
                                 </TR>
                                 
                                 <TR>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                     <TD align="center" >' . strtoupper($jabatan) . '</TD>
                                 </TR>
                                 <TR>
                                     <TD width="50%" align="center" ><b></TD>
                                     <TD width="50%" align="center" >selaku <br>PEJABAT PENGELOLA KEUANGAN DAERAH</TD>
                                 </TR>
                                 <TR>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 </TR>
                                 <TR>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 </TR>
                                 <TR>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 </TR>
                                 <TR>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 </TR>   
                                 <TR>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 </TR>                       
                                 <TR>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                     <TD align="center" ><b><u>' . $namax . '</b></u></TD>
                                 </TR>
                                 <TR>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                     <TD align="center" >' . $pangkat . '</TD>
                                 </TR>
                                 <TR>
                                     <TD width="50%" align="center" ><b>&nbsp;</TD>
                                     <TD align="center">NIP. '.$nip.'</TD>
                                 </TR>
                                 </TABLE><br/>';
             } else {
             $cRet .= '<br><br>
                 <TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
                             
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD align="center" > Melawi , ' . $tanggal . '</TD>
                             </TR>
                             
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD align="center" ><b>' . $jabatan . '</b></TD>
                             </TR>
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                             </TR>
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                             </TR>
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                             </TR>
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                             </TR>   
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                             </TR>                       
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD align="center" ><u><b>' . $namax . '</b></u></TD>
                             </TR>
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD align="center" >' . $pangkat . '</TD>
                             </TR>
                             <TR>
                                 <TD width="50%" align="center" ><b>&nbsp;</TD>
                                 <TD align="center" >'.$nip.'</TD>
                             </TR>
                             </TABLE><br/>';
             }
         $cRet .=       " </table>";
         $data['prev'] = $cRet;
         $data['sikap'] = 'preview';
         $judul  = ("LO KONSOL SKPD SUB RINCIAN $cbulan");
         $this->template->set('title', 'LO KONSOL UNIT SUB RINCIAN $cbulan');
         switch ($pilih) {
             case 0;
                 $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                 echo $cRet;
                 break;
             case 1;
                 // $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
                 echo "<title>LO KONSOL SKPD SUB RINCIAN $cbulan</title>";
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

