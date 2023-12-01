<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Akuntansi_skpd extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function load_ttd($ttd = '', $skpd = '')
    {
        //$kd_skpd = $this->session->userdata('kdskpd'); 		
        $sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '$skpd' and kode='$ttd'";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($mas->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama'],
                'jabatan' => $resulte['jabatan']
            );
            $ii++;
        }

        echo json_encode($result);
        $mas->free_result();
    }


    //==================================================================== Neraca SKPD

    function rpt_neraca($cbulan = "", $pilih = 1)
    {
        $tanggalttd = $this->uri->segment(5);
        $ttd1 = str_replace('a', ' ', $this->uri->segment(6));
        $ttd2 = str_replace('a', ' ', $this->uri->segment(7));
        $id = $this->uri->segment(8);
        $jns_ttd = $this->uri->segment(9);
        $label = $this->uri->segment(10);
        if ($label == '1') {
            $label = 'UNAUDITED';
        } else {
            $label = 'AUDITED';
        }
        $ctgl_ttd = $this->tukd_model->tanggal_format_indonesia($tanggalttd);
        //$id   	= $this->session->userdata('kdskpd');
        $thn_ang    = $this->session->userdata('pcThang');
        $thn_ang_1    = $thn_ang - 1;

        $cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;

        $sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$id' ";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {

            $nmskpd  = $rowsc->nm_skpd;
        }

        $nm_skpd    = strtoupper($nmskpd);

        /*		       $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
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
        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);
        $cRet = '';

        $sclient = $this->akuntansi_model->get_sclient();
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
                    <td align=\"center\"><strong>PER $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1 </strong></td>
                </TR>
                <TR>
                    <td align=\"center\"><strong>$label</strong></td>
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
               
                 <tr>	<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
                        <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"55%\" align=\"center\">&nbsp;</td>                            
                        <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                        <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                       
                    </tr>";


        //level 1

        // Created by Henri_TB

        $ekuitas = "310101010001";
        $sqllo10 = "select isnull(sum(kredit-debet),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo10 = $this->db->query($sqllo10);
        $pen8 = $querylo10->row();
        $pen_lalu8 = $pen8->nilai;
        $pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

        $sqllo12 = "select isnull(sum(debet-kredit),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo12 = $this->db->query($sqllo12);
        $bel10 = $querylo12->row();
        $bel_lalu10 = $bel10->nilai;
        $bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

        $sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('1','37') and left(b.kd_rek6,1)='1' and year(a.tgl_voucher)<$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_ll1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('2','35') and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_ll2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('3','31','32','33','34','36','38','39','40','41','42','43','44','45') and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_ll3  = $row003->thn_m1;
        }


        $query3 = $this->db->query(" SELECT isnull(SUM(a.debet),0) AS debet, isnull(SUM(a.kredit),0) AS kredit FROM trdju_pkd a INNER JOIN trhju_pkd b 
        ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ") AND a.kd_rek6='$ekuitas' AND YEAR(b.tgl_voucher)<'$thn_ang'
        and b.tabel=1 and reev=0");
        foreach ($query3->result_array() as $res2) {
            $debet3 = $res2['debet'];
            $kredit3 = $res2['kredit'];
        }

        $real = $kredit3 - $debet3 + $pen_lalu8 + $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;

        //		created by henri_tb
        $sqllo9 = "select isnull(sum(kredit-debet),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_rek6,1) in ('7') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "select isnull(sum(kredit-debet),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "select isnull(sum(debet-kredit),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_rek6,1) in ('8') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "select isnull(sum(debet-kredit),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo12 = $this->db->query($sqllo12);
        $bello10 = $querylo12->row();
        $bel_lo_lalu10 = $bello10->nilai;
        $bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

        $surplus_lo3 = $pen_lo7 - $bel_lo9;

        $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

        $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;

        $sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('1','37') and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_lalu1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('2','35') and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_lalu2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('3','31','32','33','34','36','38','39','40','41','42','43','44','45') and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_lalu3  = $row003->thn_m1;
        }

        $sal_awal    = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;

        $sql = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('1','37') and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //aba

        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $nilaiDR  = $row001->thn_m1;
        }

        $sqllpe1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('2','35') and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $nilailpe1  = $row002->thn_m1;
        }

        $sqllpe2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('3','31','32','33','34','36','38','39','40','41','42','43','44','45') and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $nilailpe2  = $row003->thn_m1;
        }

        $sqlrkppkd_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqlrkppkd_lalu);
        foreach ($hasil->result() as $row) {
            $rk_ppkd_lalu  = $row->thn_m1;
        }

        $sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2 + $rk_ppkd_lalu;

        $sqlutang_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)='2' and year(a.tgl_voucher)<=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqlutang_lalu);
        foreach ($hasil->result() as $row) {
            $nilaiutang_lalu  = $row->thn_m1;
        }

        $sqlkas_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where kd_rek6='$ekuitas' and year(a.tgl_voucher)<=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqlkas_lalu);
        foreach ($hasil->result() as $row) {
            $rk_ppkd_lalu  = $row->thn_m1;
        }

        $eku_lalu         = $sal_awal + $rk_ppkd_lalu;
        $eku_tang_lalu     = $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu;

        $sqlutang = "select isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher
        and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)='2' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqlutang);
        foreach ($hasil->result() as $row) {
            $nilaiutang  = $row->thn_m1;
        }

        $sqlkas = "select isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where kd_rek6='$ekuitas' and year(tgl_voucher)='$thn_ang' and month(tgl_voucher)<='$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqlkas);
        foreach ($hasil->result() as $row) {
            $rk_ppkd  = $row->thn_m1;
        }

        $eku         = $sal_akhir + $rk_ppkd;
        $eku_tang     = $sal_akhir + $nilaiutang + $rk_ppkd;

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
            $min0131 = "(";
            $rk_ppkd = $rk_ppkd * -1;
            $min0141 = ")";
        } else {
            $min0131 = "";
            $rk_ppkd;
            $min0141 = "";
        }

        $rk_ppkdx1 = number_format($rk_ppkd, "2", ",", ".");

        $queryneraca = " SELECT kode, uraian, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
                                    isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
                                    isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
                                    isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
                                    FROM map_neraca_skpd ORDER BY seq ";

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


            $q = $this->db->query(" SELECT isnull(SUM(b.debet),0) AS debet,isnull(SUM(b.kredit),0) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
                                and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ") and
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

            if ($normal == 'D') {
                $nl = $debet - $kredit;
            } else {
                $nl = $kredit - $debet;
            }
            if ($nl == '') $nl = 0;

            // Jurnal Tahun lalu
            $q = $this->db->query(" SELECT isnull(SUM(b.debet),0) AS debet,isnull(SUM(b.kredit),0) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
                                and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ") and
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

            if ($normal == 'D') {
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
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\"><b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 10:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;<b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 65:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nl001$nl1$ln001</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$sblm001$sblm1$mlbs001</b></td>
                             </tr>";
                    break;
                case 70:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 170:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;<b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 300:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nl001$nl1$ln001</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$sblm001$sblm1$mlbs001</b></td>
                             </tr>";
                    break;
                case 320:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 340:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;<b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 370:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nl001$nl1$ln001</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$sblm001$sblm1$mlbs001</b></td>
                             </tr>";
                    break;
                case 375:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nl001$nl1$ln001</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$sblm001$sblm1$mlbs001</b></td>
                             </tr>";
                    break;
                case 378:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 380:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\"><b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 385:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;<b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 410:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nl001$nl1$ln001</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$sblm001$sblm1$mlbs001</b></td>
                             </tr>";
                    break;
                case 415:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no<b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nl001$nl1$ln001</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$sblm001$sblm1$mlbs001</b></td>
                             </tr>";
                    break;
                case 420:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 460:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\"><b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 465:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$c$sal_akhir1$d</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$c1$sal_awal1$d1</td>
                             </tr>";
                    break;
                case 470:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min0131$rk_ppkdx1$min0141</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min009$rk_ppkd_lalu1$min010</td>
                             </tr>";
                    break;
                case 475:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$min003$eku1$min004</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$min001$eku_lalu1$min002</b></td>
                             </tr>";
                    break;
                case 485:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$min007$eku_tang1$min008</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$min005$eku_tang_lalu1$min006</b></td>
                             </tr>";
                    break;
                default:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
                    break;
            }
        }

        $cRet .=       " </table>";

        if ($jns_ttd == 1) {

            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kd_skpd='$id' and kode='PA' and nip='$ttd1'";
            $sqlttd = $this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd) {
                $nip = $rowttd->nip;
                $oioi = $rowttd->nm;
                $jabatan = $rowttd->jab;
            }

            $sqlttd12 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kd_skpd='$id' and kode='PPK' and nip='$ttd2'";
            $sqlttd2 = $this->db->query($sqlttd12);
            foreach ($sqlttd2->result() as $rowttd) {
                $nip2 = $rowttd->nip;
                $oioi2 = $rowttd->nm;
                $jabatan2 = $rowttd->jab;
            }


            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
     <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
     <tr>
     <td align=\"center\" width=\"50%\"> Mengetahui,</td>
     <td align=\"center\" width=\"50%\"> " . $sclient->daerah . ", $ctgl_ttd</td>
     </tr>		
     <tr>
     <td align=\"center\" width=\"50%\"> $jabatan </td>
     <td align=\"center\" width=\"50%\"> $jabatan2 </td>
     </tr>	
     <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
     <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
      <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
     <tr>
     <td align=\"center\" width=\"50%\"> $oioi</td>
     <td align=\"center\" width=\"50%\"> $oioi2 </td>
     </tr>
     <tr>
     <td align=\"center\" width=\"50%\"> NIP :$nip</td>
     <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
     </tr>
     </table>
     ";
        } else if ($jns_ttd == 3) {

            $sqlttd12 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kode in ('WK','BUP') and nip='1'";
            $sqlttd2 = $this->db->query($sqlttd12);
            foreach ($sqlttd2->result() as $rowttd) {
                $oioi2 = $rowttd->nm;
                $jabatan2 = $rowttd->jab;
            }


            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
     <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
     <tr>
     <td align=\"center\" width=\"50%\"> Mengetahui,</td>
     <td align=\"center\" width=\"50%\"> " . $sclient->daerah . ", $ctgl_ttd</td>
     </tr>		
     <tr>
     <td align=\"center\" width=\"50%\">  </td>
     <td align=\"center\" width=\"50%\"> $jabatan2 </td>
     </tr>	
     <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
     <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
      <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
     <tr>
     <td align=\"center\" width=\"50%\"> </td>
     <td align=\"center\" width=\"50%\"> $oioi2 </td>
     </tr>
     </table>
     ";
        }

        $cRet .= '</table>';

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul = ("NERACA SKPD $id / $cbulan");
        $this->template->set('title', ("NERACA SKPD $id / $cbulan"));
        switch ($pilih) {
            case 1;
                echo ("<title>NERACA SKPD $cbulan</title>");
                echo $cRet;
                break;
            case 3;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                echo $cRet;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 0;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }


    function rpt_neraca_obyek($cbulan = "", $pilih = 1)
    {
        $tanggalttd = $this->uri->segment(5);
        $denganttd = $this->uri->segment(8);
        $ttd1 = str_replace('a', ' ', $this->uri->segment(6));
        $ttd2 = str_replace('a', ' ', $this->uri->segment(7));
        $ctgl_ttd = $this->tukd_model->tanggal_format_indonesia($tanggalttd);
        $id = $this->uri->segment(8);
        $jns_ttd = $this->uri->segment(9);
        $label = $this->uri->segment(10);
        if ($label == '1') {
            $label = 'UNAUDITED';
        } else {
            $label = 'AUDITED';
        }
        $thn_ang    = $this->session->userdata('pcThang');
        $thn_ang_1    = $thn_ang - 1;

        $cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;

        $sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$id' ";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {

            $nmskpd  = $rowsc->nm_skpd;
        }

        $nm_skpd    = strtoupper($nmskpd);

        /*		       $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
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
        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);
        $cRet = '';

        $sclient = $this->akuntansi_model->get_sclient();
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
                    <td align=\"center\"><strong>PER $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1 </strong></td>
                </TR>
                <TR>
                    <td align=\"center\"><strong>$label</strong></td>
                </TR>
                </TABLE><br>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                 <thead>                       
                    <tr>
                        <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
                        <td colspan =\"7\" bgcolor=\"#CCCCCC\" width=\"55%\" align=\"center\"><b>URAIAN</b></td>
                        <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                        <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>                            
                    </tr>
                    
                 </thead>
                 <tfoot>
                    <tr>
                        <td style=\"border-top: none;\"></td>
                        <td colspan =\"7\" style=\"border-top: none;\"></td>
                        <td style=\"border-top: none;\"></td>
                        <td style=\"border-top: none;\"></td>                                             
                     </tr>
                 </tfoot>
               
                 <tr>	<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
                        <td colspan =\"7\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"55%\" align=\"center\">&nbsp;</td>                            
                        <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                        <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                       
                    </tr>";


        //level 1

        // Created by Henri_TB

        $ekuitas = "310101010001";
        $sqllo10 = "select isnull(sum(kredit-debet),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo10 = $this->db->query($sqllo10);
        $pen8 = $querylo10->row();
        $pen_lalu8 = $pen8->nilai;
        $pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

        $sqllo12 = "select isnull(sum(debet-kredit),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo12 = $this->db->query($sqllo12);
        $bel10 = $querylo12->row();
        $bel_lalu10 = $bel10->nilai;
        $bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

        $sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('1','37') and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_ll1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('2','35') and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_ll2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('3','31','32','33','34','36','38','39','40','41','42','43','44','45') and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_ll3  = $row003->thn_m1;
        }


        $query3 = $this->db->query(" SELECT isnull(SUM(a.debet),0) AS debet, isnull(SUM(a.kredit),0) AS kredit FROM trdju_pkd a INNER JOIN trhju_pkd b 
        ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ") AND a.kd_rek6='$ekuitas' AND YEAR(b.tgl_voucher)<'$thn_ang'
        and b.tabel=1 and reev=0");
        foreach ($query3->result_array() as $res2) {
            $debet3 = $res2['debet'];
            $kredit3 = $res2['kredit'];
        }

        $real = $kredit3 - $debet3 + $pen_lalu8 + $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;

        //		created by henri_tb
        $sqllo9 = "select isnull(sum(kredit-debet),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_rek6,1) in ('7') and left(b.kd_skpd,7)=left('$id',7)";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "select isnull(sum(kredit-debet),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and left(b.kd_skpd,7)=left('$id',7)";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "select isnull(sum(debet-kredit),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_rek6,1) in ('8') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "select isnull(sum(debet-kredit),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo12 = $this->db->query($sqllo12);
        $bello10 = $querylo12->row();
        $bel_lo_lalu10 = $bello10->nilai;
        $bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

        $surplus_lo3 = $pen_lo7 - $bel_lo9;

        $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

        $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;

        $sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('1','37') and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_lalu1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('2','35') and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_lalu2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('3','31','32','33','34','36','38','39','40','41','42','43','44','45') and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_lalu3  = $row003->thn_m1;
        }

        $sal_awal    = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;

        $sql = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('1','37') and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //aba

        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $nilaiDR  = $row001->thn_m1;
        }

        $sqllpe1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('2','35') and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $nilailpe1  = $row002->thn_m1;
        }

        $sqllpe2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
                inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('3','31','32','33','34','36','38','39','40','41','42','43','44','45') and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $nilailpe2  = $row003->thn_m1;
        }

        $sqlrkppkd_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqlrkppkd_lalu);
        foreach ($hasil->result() as $row) {
            $rk_ppkd_lalu  = $row->thn_m1;
        }

        $sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2 + $rk_ppkd_lalu;

        $sqlutang_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)='2' and year(a.tgl_voucher)<=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqlutang_lalu);
        foreach ($hasil->result() as $row) {
            $nilaiutang_lalu  = $row->thn_m1;
        }

        $sqlkas_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where kd_rek6='$ekuitas' and year(a.tgl_voucher)<=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqlkas_lalu);
        foreach ($hasil->result() as $row) {
            $rk_ppkd_lalu  = $row->thn_m1;
        }

        $eku_lalu         = $sal_awal + $rk_ppkd_lalu;
        $eku_tang_lalu     = $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu;

        $sqlutang = "select isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher
        and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)='2' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqlutang);
        foreach ($hasil->result() as $row) {
            $nilaiutang  = $row->thn_m1;
        }

        $sqlkas = "select isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
        and b.kd_unit=a.kd_skpd where kd_rek6='$ekuitas' and year(tgl_voucher)='$thn_ang' and month(tgl_voucher)<='$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqlkas);
        foreach ($hasil->result() as $row) {
            $rk_ppkd  = $row->thn_m1;
        }

        $eku         = $sal_akhir + $rk_ppkd;
        $eku_tang     = $sal_akhir + $nilaiutang + $rk_ppkd;

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
            $min0131 = "(";
            $rk_ppkd = $rk_ppkd * -1;
            $min0141 = ")";
        } else {
            $min0131 = "";
            $rk_ppkd;
            $min0141 = "";
        }

        $rk_ppkdx1 = number_format($rk_ppkd, "2", ",", ".");

        $queryneraca = " SELECT kode, uraian, seq, bold, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
                                    isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
                                    isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
                                    isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
                                    FROM map_neraca_skpd_obyek ORDER BY seq ";

        $query10 = $this->db->query($queryneraca);

        $no     = 0;

        foreach ($query10->result_array() as $res) {
            $uraian = $res['uraian'];
            $normal = $res['normal'];
            $bold = $res['bold'];
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


            $q = $this->db->query(" SELECT isnull(SUM(b.debet),0) AS debet,isnull(SUM(b.kredit),0) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
                                and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ") and
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

            if ($normal == 'D') {
                $nl = $debet - $kredit;
            } else {
                $nl = $kredit - $debet;
            }
            if ($nl == '') $nl = 0;

            // Jurnal Tahun lalu
            $q = $this->db->query(" SELECT isnull(SUM(b.debet),0) AS debet,isnull(SUM(b.kredit),0) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
                                and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ") and
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

            if ($normal == 'D') {
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

            switch ($res['bold']) {
                case 1:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td colspan =\"7\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\"><b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 2:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td colspan =\"6\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\"><b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                             </tr>";
                    break;
                case 3:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td colspan =\"5\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\"><b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
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
                case 5:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td colspan =\"3\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\"><b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nl001$nl1$ln001</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$sblm001$sblm1$mlbs001</b></td>
                             </tr>";
                    break;
                case 6:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td colspan =\"2\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\"><b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nl001$nl1$ln001</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$sblm001$sblm1$mlbs001</b></td>
                             </tr>";
                    break;
                case 7:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\"><b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nl001$nl1$ln001</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$sblm001$sblm1$mlbs001</b></td>
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
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\"><b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$min003$eku1$min004</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$min001$eku_lalu1$min002</b></td>
                             </tr>";
                    break;
                case 13:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\"><b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$min007$eku_tang1$min008</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$min005$eku_tang_lalu1$min006</b></td>
                             </tr>";
                    break;
                case 14:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td colspan =\"4\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min0131$rk_ppkdx1$min0141</td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min009$rk_ppkd_lalu1$min010</td>
                             </tr>";
                    break;
                case 16:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\"><b>$uraian</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nl001$nl1$ln001</b></td>
                                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$sblm001$sblm1$mlbs001</b></td>
                             </tr>";
                    break;
            }
        }

        $cRet .=       " </table>";

        if ($jns_ttd == 1) {

            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kd_skpd='$id' and kode='PA' and nip='$ttd1'";
            $sqlttd = $this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd) {
                $nip = $rowttd->nip;
                $oioi = $rowttd->nm;
                $jabatan = $rowttd->jab;
            }

            $sqlttd12 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kd_skpd='$id' and kode='PPK' and nip='$ttd2'";
            $sqlttd2 = $this->db->query($sqlttd12);
            foreach ($sqlttd2->result() as $rowttd) {
                $nip2 = $rowttd->nip;
                $oioi2 = $rowttd->nm;
                $jabatan2 = $rowttd->jab;
            }


            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
     <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
     <tr>
     <td align=\"center\" width=\"50%\"> Mengetahui,</td>
     <td align=\"center\" width=\"50%\"> " . $sclient->daerah . ", $ctgl_ttd</td>
     </tr>		
     <tr>
     <td align=\"center\" width=\"50%\"> $jabatan </td>
     <td align=\"center\" width=\"50%\"> $jabatan2 </td>
     </tr>	
     <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
     <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
      <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
     <tr>
     <td align=\"center\" width=\"50%\"> $oioi</td>
     <td align=\"center\" width=\"50%\"> $oioi2 </td>
     </tr>
     <tr>
     <td align=\"center\" width=\"50%\"> NIP :$nip</td>
     <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
     </tr>
     </table>
     ";
        } else if ($jns_ttd == 3) {

            $sqlttd12 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kode in ('WK','BUP') and nip='1'";
            $sqlttd2 = $this->db->query($sqlttd12);
            foreach ($sqlttd2->result() as $rowttd) {
                $oioi2 = $rowttd->nm;
                $jabatan2 = $rowttd->jab;
            }


            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
     <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
     <tr>
     <td align=\"center\" width=\"50%\"> Mengetahui,</td>
     <td align=\"center\" width=\"50%\"> " . $sclient->daerah . ", $ctgl_ttd</td>
     </tr>		
     <tr>
     <td align=\"center\" width=\"50%\">  </td>
     <td align=\"center\" width=\"50%\"> $jabatan2 </td>
     </tr>	
     <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
     <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
      <tr>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     <td align=\"center\" width=\"50%\"> &nbsp; </td>
     </tr>
     <tr>
     <td align=\"center\" width=\"50%\"> </td>
     <td align=\"center\" width=\"50%\"> $oioi2 </td>
     </tr>
     </table>
     ";
        }

        $cRet .= '</table>';

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul = ("NERACA SKPD OBYEK $id / $cbulan");
        $this->template->set('title', ("NERACA SKPD OBYEK $id / $cbulan"));
        switch ($pilih) {
            case 1;
                echo ("<title>NERACA SKPD OBYEK $cbulan</title>");
                echo $cRet;
                break;
            case 3;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                echo $cRet;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 0;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }


    //==================================================================== End Neraca SKPD

    //================================================ Cetak LO
    function cetak_lra_lo($cbulan = "", $pilih = 1)
    {
        //$id = $skpd;
        $tanggalttd = $this->uri->segment(5);
        $ttd1 = str_replace('a', ' ', $this->uri->segment(6));
        $ttd2 = str_replace('a', ' ', $this->uri->segment(7));
        $ctgl_ttd = $this->tukd_model->tanggal_format_indonesia($tanggalttd);
        $cetak = '2'; //$ctk;
        $id     = $this->uri->segment(8);
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1 = $thn_ang - 1;

        $skpd = "AND kd_skpd='$id'";
        $skpd1 = "AND b.kd_skpd='$id'";

        $y123 = ")";
        $x123 = "(";
        $sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$id'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $nmskpd     = $rowsc->nm_skpd;
        }
        $nm_skpd = strtoupper($nmskpd);

        $sqldns = "SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$id'";
        $sqlskpd = $this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns) {
            $kd_urusan = $rowdns->kd_u;
            $nm_urusan = $rowdns->nm_u;
            $kd_skpd  = $rowdns->kd_sk;
            $nm_skpd  = $rowdns->nm_sk;
        }

        // created by henri_tb      
        $sqllo1 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,2) in ('71','72','73') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";

        $querylo1 = $this->db->query($sqllo1);
        $penlo = $querylo1->row();
        $pen_lo = $penlo->nilai;
        $pen_lo1 = number_format($penlo->nilai, "2", ",", ".");

        $sqllo2 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73') and left(b.kd_skpd,17)=left('$id',17)";

        $querylo2 = $this->db->query($sqllo2);
        $penlo2 = $querylo2->row();
        $pen_lo_lalu = $penlo2->nilai;
        $pen_lo_lalu1 = number_format($penlo2->nilai, "2", ",", ".");

        $sqllo3 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,2) in ('81') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";

        $querylo3 = $this->db->query($sqllo3);
        $bello = $querylo3->row();
        $bel_lo = $bello->nilai;
        $bel_lo1 = number_format($bello->nilai, "2", ",", ".");

        $sqllo4 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81') and left(b.kd_skpd,17)=left('$id',17)";
        $querylo4 = $this->db->query($sqllo4);
        $bello2 = $querylo4->row();
        $bel_lo_lalu = $bello2->nilai;
        $bel_lo_lalu1 = number_format($bello2->nilai, "2", ",", ".");

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

        $sqllo5 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,2) in ('84') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
        $querylo5 = $this->db->query($sqllo5);
        $penlo3 = $querylo5->row();
        $pen_lo3 = $penlo3->nilai;
        $pen_lo31 = number_format($penlo3->nilai, "2", ",", ".");

        $sqllo6 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('84') and left(b.kd_skpd,17)=left('$id',17)";
        $querylo6 = $this->db->query($sqllo6);
        $penlo4 = $querylo6->row();
        $pen_lo_lalu4 = $penlo4->nilai;
        $pen_lo_lalu41 = number_format($penlo4->nilai, "2", ",", ".");

        $sqllo7 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,2) in ('93') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
        $querylo7 = $this->db->query($sqllo7);
        $bello5 = $querylo7->row();
        $bel_lo5 = $bello5->nilai;
        $bel_lo51 = number_format($bello5->nilai, "2", ",", ".");

        $sqllo8 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('93') and left(b.kd_skpd,7)=left('$id',17)";
        $querylo8 = $this->db->query($sqllo8);
        $bello6 = $querylo8->row();
        $bel_lo_lalu6 = $bello6->nilai;
        $bel_lo_lalu61 = number_format($bello6->nilai, "2", ",", ".");

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

        if ($surplus_lo_lalu2 == '' or $surplus_lo_lalu2 == 0) {
            $persen3 = '0,00';
        } else {
            $persen3 = ($surplus_lo2 / $surplus_lo_lalu2) * 100;
            $persen3 = number_format($persen3, "2", ",", ".");
        }

        $sqllo9 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,1) in ('7') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and left(b.kd_skpd,17)=left('$id',17)";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,1) in ('8') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and left(b.kd_skpd,17)=left('$id',17)";
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

        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);
        $cRet = '';


        $sclient = $this->akuntansi_model->get_sclient();
        $daerah = $sclient->daerah;

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong> $nm_skpd</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>&nbsp;</strong></td>
                    </tr>
                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
                            <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                            <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>                            
                            <td colspan =\"5\" bgcolor=\"#CCCCCC\" width=\"27%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\" ><b>Kenaikan<br/>(Penurunan)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border:none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td colspan =\"5\" style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                           
                         </tr>
                     </tfoot>
                   
                     <tr>
                            <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>                            
                            <td colspan =\"5\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"27%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\">&nbsp;</td>
                        </tr>";

        $sqlmaplo = "SELECT seq, nor, uraian, bold, isnull(kode_1,'-') as kode_1, isnull(cetak,'debet-debet') as cetak FROM map_lo_skpd 
                   GROUP BY seq, nor, uraian, bold, isnull(kode_1,'-'), isnull(cetak,'debet-debet') ORDER BY seq";

        $querymaplo = $this->db->query($sqlmaplo);
        $no     = 0;

        foreach ($querymaplo->result() as $loquery) {

            $nama      = $loquery->uraian;
            $n         = $loquery->kode_1;
            $n         = ($n == "-" ? "'-'" : $n);
            $normal    = $loquery->cetak;
            $bold      = $loquery->bold;
            if ($normal != "") {
                $quelo01   = "SELECT SUM($normal) as nilai FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE left(kd_rek6,4) in ($n) and year(tgl_voucher)=$thn_ang and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
                $quelo02 = $this->db->query($quelo01);
                $quelo03 = $quelo02->row();
                $nil     = $quelo03->nilai;
                $nilai    = number_format($quelo03->nilai, "2", ",", ".");

                $quelo04   = "SELECT SUM($normal) as nilai FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE left(kd_rek6,4) in ($n) and year(tgl_voucher)=$thn_ang_1 and left(b.kd_skpd,17)=left('$id',17)";
                $quelo05 = $this->db->query($quelo04);
                $quelo06 = $quelo05->row();
                $nil_lalu     = $quelo06->nilai;
                $nilai_lalu    = number_format($quelo06->nilai, "2", ",", ".");

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
            }
            $no       = $no + 1;
            switch ($loquery->bold) {
                case 0:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td colspan =\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 1:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td colspan =\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 2:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td colspan =\"4\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 3:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td colspan =\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo0$real_nilai1$lo00</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
                                 </tr>";
                    break;
                case 4:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td colspan =\"2\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nilai</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nilai_lalu</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo0$real_nilai1$lo00</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"><b>$persen1</b></td>
                                 </tr>";
                    break;
                case 5:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo1$surplus_lo1$lo2</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo3$surplus_lo_lalu1$lo4</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo5$selisih_surplus_lo1$lo6</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"><b>$persen2</b></td>
                                 </tr>";
                    break;
                case 6:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo7$surplus_lo21$lo8</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo9$surplus_lo_lalu21$lo10</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_lo21$lo12</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"><b>$persen3</b></td>
                                 </tr>";
                    break;
                case 7:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo13$surplus_lo31$lo14</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo15$surplus_lo_lalu31$lo16</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo17$selisih_surplus_lo31$lo18</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"><b>$persen4</td>
                                 </tr>";
                    break;
            }
        }


        $cRet .=       " </table>";

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kd_skpd='$id' and kode='PA' and nip='$ttd1'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $oioi = $rowttd->nm;
            $jabatan = $rowttd->jab;
        }

        $sqlttd12 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kd_skpd='$id' and kode='PPK' and nip='$ttd2'";
        $sqlttd2 = $this->db->query($sqlttd12);
        foreach ($sqlttd2->result() as $rowttd) {
            $nip2 = $rowttd->nip;
            $oioi2 = $rowttd->nm;
            $jabatan2 = $rowttd->jab;
        }


        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
         <tr>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         </tr>
         <tr>
         <td align=\"center\" width=\"50%\"> Mengetahui,</td>
         <td align=\"center\" width=\"50%\"> $daerah, $ctgl_ttd</td>
         </tr>      
         <tr>
         <td align=\"center\" width=\"50%\"> $jabatan </td>
         <td align=\"center\" width=\"50%\"> $jabatan2 </td>
         </tr>  
         <tr>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         </tr>
         <tr>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         </tr>
          <tr>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         </tr>
         <tr>
         <td align=\"center\" width=\"50%\"> $oioi</td>
         <td align=\"center\" width=\"50%\"> $oioi2 </td>
         </tr>
         <tr>
         <td align=\"center\" width=\"50%\"> NIP :$nip</td>
         <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
         </tr>
         </table>
         ";


        $cRet .=       " </table>";
        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul  = ("LO SKPD $id / $cbulan");
        $this->template->set('title', 'LO SKPD $id / $cbulan');
        switch ($pilih) {
            case 1;
                echo ("<title>LO SKPD $cbulan</title>");
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
            case 0;
                $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
                break;
        }
    }

    function cetak_lra_lo_obyek($cbulan = "", $pilih = 1)
    {
        $this->load->library('custom');
        $tanggalttd = $this->uri->segment(5);
        $ttd1 = str_replace('a', ' ', $this->uri->segment(6));
        $ttd2 = str_replace('a', ' ', $this->uri->segment(7));
        $ctgl_ttd = $this->tukd_model->tanggal_format_indonesia($tanggalttd);
        $cetak = '2'; //$ctk;
        $id     = $this->uri->segment(8);
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1 = $thn_ang - 1;
        //$laporan=$lap; 

        // if ($cetak == '1') {
        //           $skpd = '';
        //           $skpd1 = '';           
        //       } else {             
        $skpd = "AND kd_skpd='$id'";
        $skpd1 = "AND b.kd_skpd='$id'";
        //}  

        $y123 = ")";
        $x123 = "(";
        $sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$id'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $nmskpd     = $rowsc->nm_skpd;
        }
        $nm_skpd = strtoupper($nmskpd);
        // INSERT DATA

        $sqldns = "SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$id'";
        $sqlskpd = $this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns) {
            $kd_urusan = $rowdns->kd_u;
            $nm_urusan = $rowdns->nm_u;
            $kd_skpd  = $rowdns->kd_sk;
            $nm_skpd  = $rowdns->nm_sk;
        }

        // created by henri_tb

        $sqllo1 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,2) in ('71','72','73') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";

        $querylo1 = $this->db->query($sqllo1);
        $penlo = $querylo1->row();
        $pen_lo = $penlo->nilai;
        $pen_lo1 = number_format($penlo->nilai, "2", ",", ".");

        $sqllo2 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73') and left(b.kd_skpd,17)=left('$id',17)";

        $querylo2 = $this->db->query($sqllo2);
        $penlo2 = $querylo2->row();
        $pen_lo_lalu = $penlo2->nilai;
        $pen_lo_lalu1 = number_format($penlo2->nilai, "2", ",", ".");

        $sqllo3 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,2) in ('81') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";

        $querylo3 = $this->db->query($sqllo3);
        $bello = $querylo3->row();
        $bel_lo = $bello->nilai;
        $bel_lo1 = number_format($bello->nilai, "2", ",", ".");

        $sqllo4 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81') and left(b.kd_skpd,17)=left('$id',17)";
        $querylo4 = $this->db->query($sqllo4);
        $bello2 = $querylo4->row();
        $bel_lo_lalu = $bello2->nilai;
        $bel_lo_lalu1 = number_format($bello2->nilai, "2", ",", ".");

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

        $sqllo5 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,2) in ('84') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
        $querylo5 = $this->db->query($sqllo5);
        $penlo3 = $querylo5->row();
        $pen_lo3 = $penlo3->nilai;
        $pen_lo31 = number_format($penlo3->nilai, "2", ",", ".");

        $sqllo6 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('84') and left(b.kd_skpd,17)=left('$id',17)";
        $querylo6 = $this->db->query($sqllo6);
        $penlo4 = $querylo6->row();
        $pen_lo_lalu4 = $penlo4->nilai;
        $pen_lo_lalu41 = number_format($penlo4->nilai, "2", ",", ".");

        $sqllo7 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,2) in ('93') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
        $querylo7 = $this->db->query($sqllo7);
        $bello5 = $querylo7->row();
        $bel_lo5 = $bello5->nilai;
        $bel_lo51 = number_format($bello5->nilai, "2", ",", ".");

        $sqllo8 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('93') and left(b.kd_skpd,7)=left('$id',17)";
        $querylo8 = $this->db->query($sqllo8);
        $bello6 = $querylo8->row();
        $bel_lo_lalu6 = $bello6->nilai;
        $bel_lo_lalu61 = number_format($bello6->nilai, "2", ",", ".");

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

        if ($surplus_lo_lalu2 == '' or $surplus_lo_lalu2 == 0) {
            $persen3 = '0,00';
        } else {
            $persen3 = ($surplus_lo2 / $surplus_lo_lalu2) * 100;
            $persen3 = number_format($persen3, "2", ",", ".");
        }

        $sqllo9 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,1) in ('7') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and left(b.kd_skpd,17)=left('$id',17)";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,1) in ('8') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and left(b.kd_skpd,17)=left('$id',17)";
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

        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);
        $cRet = '';


        $sclient = $this->akuntansi_model->get_sclient();
        $daerah = $sclient->daerah;

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong> $nm_skpd</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>&nbsp;</strong></td>
                    </tr>
                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
                            <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                            <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>                            
                            <td colspan =\"6\" bgcolor=\"#CCCCCC\" width=\"27%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\" ><b>Kenaikan<br/>(Penurunan)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border:none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td colspan =\"6\" style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                           
                         </tr>
                     </tfoot>
                   
                     <tr>
                            <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>                            
                            <td colspan =\"6\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"27%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\">&nbsp;</td>
                        </tr>";

        $sqlmaplo = "SELECT seq, nor, uraian, bold, isnull(kode_1,'-') as kode_1, isnull(cetak,'debet-debet') as cetak FROM map_lo_skpd 
                   GROUP BY seq, nor, uraian, bold, isnull(kode_1,'-'), isnull(cetak,'debet-debet') ORDER BY seq";

        $querymaplo = $this->db->query($sqlmaplo);
        $no     = 0;

        foreach ($querymaplo->result() as $loquery) {

            $nama      = $loquery->uraian;
            $n         = $loquery->kode_1;
            $n         = ($n == "-" ? "'-'" : $n);
            $normal    = $loquery->cetak;
            $bold      = $loquery->bold;

            if ($normal != "") {
                $quelo01   = "SELECT SUM($normal) as nilai FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE left(kd_rek6,4) in ($n) and year(tgl_voucher)=$thn_ang and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
                $quelo02 = $this->db->query($quelo01);
                $quelo03 = $quelo02->row();
                $nil     = $quelo03->nilai;
                $nilai    = number_format($quelo03->nilai, "2", ",", ".");

                $quelo04   = "SELECT SUM($normal) as nilai FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE left(kd_rek6,4) in ($n) and year(tgl_voucher)=$thn_ang_1 and left(b.kd_skpd,17)=left('$id',17)";
                $quelo05 = $this->db->query($quelo04);
                $quelo06 = $quelo05->row();
                $nil_lalu     = $quelo06->nilai;
                $nilai_lalu    = number_format($quelo06->nilai, "2", ",", ".");

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
                $no       = $no + 1;
            }
            switch ($loquery->bold) {
                case 0:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td colspan =\"6\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 1:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td colspan =\"6\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 2:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td colspan =\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 3:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td colspan =\"4\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo0$real_nilai1$lo00</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
                                 </tr>";
                    //===================== Objek         
                    $sql = "SELECT c.kd_rek4,c.nm_rek4,sum(a.$normal) as 'thn_ini',
                                (
                                    select isnull(sum(d.$normal),0) from trdju_pkd d 
                                    join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
                                    where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,6)=c.kd_rek4 
                                    and left(e.kd_skpd,17)=left(b.kd_skpd,17)
                                ) as 'thn_lalu' 
                                from trdju_pkd a 
                                join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                join ms_rek4 c on LEFT(a.kd_rek6,6)=c.kd_rek4
                                where left(b.kd_skpd,17)=left('$id',17) and left(kd_rek6,4) in ($n) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
                                group by c.kd_rek4,c.nm_rek4,left(b.kd_skpd,17)";
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
                                            <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                            <td colspan =\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
                                        </tr>";
                    }
                    //===================== End Objek
                    break;
                case 4:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td colspan =\"2\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"25%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nilai</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nilai_lalu</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo0$real_nilai1$lo00</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"><b>$persen1</b></td>
                                 </tr>";
                    break;
                case 5:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"24%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo1$surplus_lo1$lo2</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo3$surplus_lo_lalu1$lo4</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo5$selisih_surplus_lo1$lo6</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"><b>$persen2</b></td>
                                 </tr>";
                    break;
                case 6:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"24%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo7$surplus_lo21$lo8</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo9$surplus_lo_lalu21$lo10</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_lo21$lo12</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"><b>$persen3</b></td>
                                 </tr>";
                    break;
                case 7:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"24%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo13$surplus_lo31$lo14</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo15$surplus_lo_lalu31$lo16</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo17$selisih_surplus_lo31$lo18</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"><b>$persen4</td>
                                 </tr>";


                    break;
            }
        }


        $cRet .=       " </table>";

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kd_skpd='$id' and kode='PA' and nip='$ttd1'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $oioi = $rowttd->nm;
            $jabatan = $rowttd->jab;
        }

        $sqlttd12 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kd_skpd='$id' and kode='PPK' and nip='$ttd2'";
        $sqlttd2 = $this->db->query($sqlttd12);
        foreach ($sqlttd2->result() as $rowttd) {
            $nip2 = $rowttd->nip;
            $oioi2 = $rowttd->nm;
            $jabatan2 = $rowttd->jab;
        }


        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
         <tr>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         </tr>
         <tr>
         <td align=\"center\" width=\"50%\"> Mengetahui,</td>
         <td align=\"center\" width=\"50%\"> $daerah, $ctgl_ttd</td>
         </tr>      
         <tr>
         <td align=\"center\" width=\"50%\"> $jabatan </td>
         <td align=\"center\" width=\"50%\"> $jabatan2 </td>
         </tr>  
         <tr>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         </tr>
         <tr>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         </tr>
          <tr>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         </tr>
         <tr>
         <td align=\"center\" width=\"50%\"> $oioi</td>
         <td align=\"center\" width=\"50%\"> $oioi2 </td>
         </tr>
         <tr>
         <td align=\"center\" width=\"50%\"> NIP :$nip</td>
         <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
         </tr>
         </table>
         ";


        $cRet .=       " </table>";
        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul  = ("LO SKPD $id / $cbulan");
        $this->template->set('title', 'LO SKPD $id / $cbulan');
        switch ($pilih) {
            case 1;
                echo ("<title>LO SKPD $cbulan</title>");
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
            case 0;
                $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
                break;
        }
    }


    function cetak_lra_lo_rincian($cbulan = "", $pilih = 1)
    {
        //$id = $skpd;

        $this->load->library('custom');
        $tanggalttd = $this->uri->segment(5);
        $ttd1 = str_replace('a', ' ', $this->uri->segment(6));
        $ttd2 = str_replace('a', ' ', $this->uri->segment(7));
        $ctgl_ttd = $this->tukd_model->tanggal_format_indonesia($tanggalttd);
        $cetak = '2'; //$ctk;
        $id     = $this->uri->segment(8);
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1 = $thn_ang - 1;
        //$laporan=$lap; 

        // if ($cetak == '1') {
        //           $skpd = '';
        //           $skpd1 = '';           
        //       } else {             
        $skpd = "AND kd_skpd='$id'";
        $skpd1 = "AND b.kd_skpd='$id'";
        //}  

        $y123 = ")";
        $x123 = "(";
        $sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$id'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {
            $nmskpd     = $rowsc->nm_skpd;
        }
        $nm_skpd = strtoupper($nmskpd);
        // INSERT DATA

        $sqldns = "SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$id'";
        $sqlskpd = $this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns) {
            $kd_urusan = $rowdns->kd_u;
            $nm_urusan = $rowdns->nm_u;
            $kd_skpd  = $rowdns->kd_sk;
            $nm_skpd  = $rowdns->nm_sk;
        }

        // created by henri_tb

        $sqllo1 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,2) in ('71','72','73') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";

        $querylo1 = $this->db->query($sqllo1);
        $penlo = $querylo1->row();
        $pen_lo = $penlo->nilai;
        $pen_lo1 = number_format($penlo->nilai, "2", ",", ".");

        $sqllo2 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73') and left(b.kd_skpd,17)=left('$id',17)";

        $querylo2 = $this->db->query($sqllo2);
        $penlo2 = $querylo2->row();
        $pen_lo_lalu = $penlo2->nilai;
        $pen_lo_lalu1 = number_format($penlo2->nilai, "2", ",", ".");

        $sqllo3 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,2) in ('81') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";

        $querylo3 = $this->db->query($sqllo3);
        $bello = $querylo3->row();
        $bel_lo = $bello->nilai;
        $bel_lo1 = number_format($bello->nilai, "2", ",", ".");

        $sqllo4 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81') and left(b.kd_skpd,17)=left('$id',17)";
        $querylo4 = $this->db->query($sqllo4);
        $bello2 = $querylo4->row();
        $bel_lo_lalu = $bello2->nilai;
        $bel_lo_lalu1 = number_format($bello2->nilai, "2", ",", ".");

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

        $sqllo5 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,2) in ('84') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
        $querylo5 = $this->db->query($sqllo5);
        $penlo3 = $querylo5->row();
        $pen_lo3 = $penlo3->nilai;
        $pen_lo31 = number_format($penlo3->nilai, "2", ",", ".");

        $sqllo6 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('84') and left(b.kd_skpd,17)=left('$id',17)";
        $querylo6 = $this->db->query($sqllo6);
        $penlo4 = $querylo6->row();
        $pen_lo_lalu4 = $penlo4->nilai;
        $pen_lo_lalu41 = number_format($penlo4->nilai, "2", ",", ".");

        $sqllo7 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,2) in ('93') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
        $querylo7 = $this->db->query($sqllo7);
        $bello5 = $querylo7->row();
        $bel_lo5 = $bello5->nilai;
        $bel_lo51 = number_format($bello5->nilai, "2", ",", ".");

        $sqllo8 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('93') and left(b.kd_skpd,7)=left('$id',17)";
        $querylo8 = $this->db->query($sqllo8);
        $bello6 = $querylo8->row();
        $bel_lo_lalu6 = $bello6->nilai;
        $bel_lo_lalu61 = number_format($bello6->nilai, "2", ",", ".");

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

        if ($surplus_lo_lalu2 == '' or $surplus_lo_lalu2 == 0) {
            $persen3 = '0,00';
        } else {
            $persen3 = ($surplus_lo2 / $surplus_lo_lalu2) * 100;
            $persen3 = number_format($persen3, "2", ",", ".");
        }

        $sqllo9 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,1) in ('7') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and left(b.kd_skpd,17)=left('$id',17)";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(kd_rek6,1) in ('8') and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "select sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and left(b.kd_skpd,17)=left('$id',17)";
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

        $modtahun = $thn_ang % 4;

        if ($modtahun = 0) {
            $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        } else {
            $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
        }

        $arraybulan = explode(".", $nilaibulan);
        $cRet = '';


        $sclient = $this->akuntansi_model->get_sclient();
        $daerah = $sclient->daerah;

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong> $nm_skpd</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>&nbsp;</strong></td>
                    </tr>
                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
                            <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                            <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>                            
                            <td colspan =\"7\" bgcolor=\"#CCCCCC\" width=\"27%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\" ><b>Kenaikan<br/>(Penurunan)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border:none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td colspan =\"7\" style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                           
                         </tr>
                     </tfoot>
                   
                     <tr>
                            <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>                            
                            <td colspan =\"7\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"27%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\">&nbsp;</td>
                        </tr>";

        $sqlmaplo = "SELECT seq, nor, uraian, bold, isnull(kode_1,'-') as kode_1, isnull(cetak,'debet-debet') as cetak FROM map_lo_skpd 
                   GROUP BY seq, nor, uraian, bold, isnull(kode_1,'-'), isnull(cetak,'debet-debet') ORDER BY seq";

        $querymaplo = $this->db->query($sqlmaplo);
        $no     = 0;

        foreach ($querymaplo->result() as $loquery) {

            $nama      = $loquery->uraian;
            $n         = $loquery->kode_1;
            $n         = ($n == "-" ? "'-'" : $n);
            $normal    = $loquery->cetak;
            $bold      = $loquery->bold;

            if ($normal != "") {
                $quelo01   = "SELECT SUM($normal) as nilai FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE left(kd_rek6,4) in ($n) and year(tgl_voucher)=$thn_ang and month(b.tgl_voucher)<=$cbulan and left(b.kd_skpd,17)=left('$id',17)";
                $quelo02 = $this->db->query($quelo01);
                $quelo03 = $quelo02->row();
                $nil     = $quelo03->nilai;
                $nilai    = number_format($quelo03->nilai, "2", ",", ".");

                $quelo04   = "SELECT SUM($normal) as nilai FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE left(kd_rek6,4) in ($n) and year(tgl_voucher)=$thn_ang_1 and left(b.kd_skpd,17)=left('$id',17)";
                $quelo05 = $this->db->query($quelo04);
                $quelo06 = $quelo05->row();
                $nil_lalu     = $quelo06->nilai;
                $nilai_lalu    = number_format($quelo06->nilai, "2", ",", ".");

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
                $no       = $no + 1;
            }
            switch ($loquery->bold) {
                case 0:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td colspan =\"7\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 1:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td colspan =\"7\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 2:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td colspan =\"6\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
                    break;
                case 3:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td colspan =\"5\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo0$real_nilai1$lo00</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
                                 </tr>";
                    //===================== Objek / Rincian Objek       
                    $sql = "SELECT c.kd_rek4 as kd_rek,c.nm_rek4 as nm_rek,sum(a.$normal) as 'thn_ini',
                                (
                                    select isnull(sum(d.$normal),0) from trdju_pkd d 
                                    join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
                                    where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,6)=c.kd_rek4 
                                    and left(e.kd_skpd,17)=left(b.kd_skpd,17)
                                ) as 'thn_lalu' 
                                from trdju_pkd a 
                                join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                join ms_rek4 c on LEFT(a.kd_rek6,6)=c.kd_rek4
                                where left(b.kd_skpd,17)=left('$id',17) and left(kd_rek6,4) in ($n) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
                                group by c.kd_rek4,c.nm_rek4,left(b.kd_skpd,17)
                                union all
                                SELECT c.kd_rek5 as kd_rek,c.nm_rek5 as nm_rek,sum(a.$normal) as 'thn_ini',
                                (
                                    select isnull(sum(d.$normal),0) from trdju_pkd d 
                                    join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
                                    where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,8)=c.kd_rek5 
                                    and left(e.kd_skpd,17)=left(b.kd_skpd,17)
                                ) as 'thn_lalu' 
                                from trdju_pkd a 
                                join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
                                join ms_rek5 c on LEFT(a.kd_rek6,8)=c.kd_rek5
                                where left(b.kd_skpd,17)=left('$id',17) and left(kd_rek6,4) in ($n) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
                                group by c.kd_rek5,c.nm_rek5,left(b.kd_skpd,17)
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
                                                <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                <td colspan =\"4\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
                                            </tr>";
                        } else { //rincian obyek
                            $cRet    .= "<tr>
                                                <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                                <td colspan =\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
                                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
                                            </tr>";
                        }
                    }
                    //===================== End Objek
                    break;
                case 4:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td colspan =\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"25%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nilai</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$nilai_lalu</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo0$real_nilai1$lo00</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"><b>$persen1</b></td>
                                 </tr>";
                    break;
                case 5:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"21%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo1$surplus_lo1$lo2</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo3$surplus_lo_lalu1$lo4</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo5$selisih_surplus_lo1$lo6</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"><b>$persen2</b></td>
                                 </tr>";
                    break;
                case 6:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"21%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo7$surplus_lo21$lo8</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo9$surplus_lo_lalu21$lo10</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_lo21$lo12</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"><b>$persen3</b></td>
                                 </tr>";
                    break;
                case 7:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"21%\"><b>$nama</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo13$surplus_lo31$lo14</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo15$surplus_lo_lalu31$lo16</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$lo17$selisih_surplus_lo31$lo18</b></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"><b>$persen4</td>
                                 </tr>";


                    break;
            }
        }


        $cRet .=       " </table>";

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kd_skpd='$id' and kode='PA' and nip='$ttd1'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $oioi = $rowttd->nm;
            $jabatan = $rowttd->jab;
        }

        $sqlttd12 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kd_skpd='$id' and kode='PPK' and nip='$ttd2'";
        $sqlttd2 = $this->db->query($sqlttd12);
        foreach ($sqlttd2->result() as $rowttd) {
            $nip2 = $rowttd->nip;
            $oioi2 = $rowttd->nm;
            $jabatan2 = $rowttd->jab;
        }


        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
         <tr>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         </tr>
         <tr>
         <td align=\"center\" width=\"50%\"> Mengetahui,</td>
         <td align=\"center\" width=\"50%\"> $daerah, $ctgl_ttd</td>
         </tr>      
         <tr>
         <td align=\"center\" width=\"50%\"> $jabatan </td>
         <td align=\"center\" width=\"50%\"> $jabatan2 </td>
         </tr>  
         <tr>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         </tr>
         <tr>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         </tr>
          <tr>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         <td align=\"center\" width=\"50%\"> &nbsp; </td>
         </tr>
         <tr>
         <td align=\"center\" width=\"50%\"> $oioi</td>
         <td align=\"center\" width=\"50%\"> $oioi2 </td>
         </tr>
         <tr>
         <td align=\"center\" width=\"50%\"> NIP :$nip</td>
         <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
         </tr>
         </table>
         ";


        $cRet .=       " </table>";
        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul  = ("LO SKPD $id / $cbulan");
        $this->template->set('title', 'LO SKPD $id / $cbulan');
        switch ($pilih) {
            case 1;
                echo ("<title>LO SKPD $cbulan</title>");
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
            case 0;
                $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
                break;
        }
    }

    //================================================ End Cetak LO


    //================================================ Cetak LPE SKPD
    function ctk_lpe($cbulan = "", $pilih = 1)
    {
        $tanggalttd = $this->uri->segment(5);
        $ttd1 = str_replace('a', ' ', $this->uri->segment(6));
        $ttd2 = str_replace('a', ' ', $this->uri->segment(7));
        $ctgl_ttd = $this->tukd_model->tanggal_format_indonesia($tanggalttd);
        $id = $this->uri->segment(8);
        $jns_ttd = $this->uri->segment(9);
        $label = $this->uri->segment(10);
        if ($label == '1') {
            $label = 'UNAUDITED';
        } else {
            $label = 'AUDITED';
        }
        $thn_ang = $this->session->userdata('pcThang');
        $id1     = $this->uri->segment(8);

        $nmskpd = $this->tukd_model->get_nama($id1, 'nm_skpd', 'ms_skpd', 'kd_skpd');
        $nmskpd = strtoupper($nmskpd);
        $thn_ang_1 = $thn_ang - 1;
        $cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
        $sqlsclient = $this->db->query($sqlsc);



        foreach ($sqlsclient->result() as $rowsc) {

            $tgl = $rowsc->tgl_rka;
            $tanggal = isset($tgl) ? $this->tukd_model->tanggal_format_indonesia($tgl) : '';
            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah;
            $thn     = $rowsc->thn_ang;
        }

        $skpd = "AND kd_skpd='$id1'";
        $skpd1 = "AND b.kd_skpd='$id1'";

        // UPDATE LPE TAHUN LALU

        $ekuitas = '310101010001';
        $sqllo10 = "select isnull(sum(kredit-debet),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo10 = $this->db->query($sqllo10);
        $pen8 = $querylo10->row();
        $pen_lalu8 = $pen8->nilai;
        $pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

        $sqllo12 = "select isnull(sum(debet-kredit),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo12 = $this->db->query($sqllo12);
        $bel10 = $querylo12->row();
        $bel_lalu10 = $bel10->nilai;
        $bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

        $sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
					inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('1','37') and kd_rek6 = '$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_ll1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
					inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('2','35') and kd_rek6 = '$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_ll2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
					inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('3','31','32','33','34','36','38','39','40','41','42','43','44','45') and kd_rek6 = '$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_ll3  = $row003->thn_m1;
        }


        $query3 = $this->db->query(" SELECT isnull(SUM(a.debet),0) AS debet, isnull(SUM(a.kredit),0) AS kredit FROM trdju_pkd a INNER JOIN trhju_pkd b 
			ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ") AND a.kd_rek6 = '$ekuitas' AND YEAR(b.tgl_voucher)<'$thn_ang'
			and b.tabel=1 and reev=0");
        foreach ($query3->result_array() as $res2) {
            $debet = $res2['debet'];
            $kredit = $res2['kredit'];
        }

        $real = $kredit - $debet + $pen_lalu8 + $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;
        //        $this->db->query(" UPDATE map_lpe_skpd SET thn_m1 = '$real' WHERE nor = '1' ");
        //          }
        /*		    
			
			$query3 = $this->db->query(" SELECT
										SUM(a.debet) AS debet, SUM(a.kredit) AS kredit
									FROM
										trdju_pkd a
									INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd
									WHERE
										b.kd_skpd = '$kd_skpd'
									AND left(a.kd_rek5,1) = '9'
									AND YEAR (b.tgl_voucher) < '$thn'");  
	        foreach($query3->result_array() as $res21){
				 $debet9=$res21['debet'];
				 $kredit9=$res21['kredit'];
                 				 
			 }
			 
		$query3 = $this->db->query(" SELECT
										SUM(a.debet) AS debet, SUM(a.kredit) AS kredit
									FROM
										trdju_pkd a
									INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd
									WHERE
										b.kd_skpd = '$kd_skpd'
									AND left(a.kd_rek5,1) = '8'
									AND YEAR (b.tgl_voucher) < '$thn'");  
	        foreach($query3->result_array() as $res22){
				 $debet8=$res22['debet'];
				 $kredit8=$res22['kredit'];
                 				 
			 }	 
			 
		$surplus1_1=($kredit8-$debet8)-($debet9-$kredit9);
		$surplus1=number_format($surplus1_1, "2", ".", "");
*/
        //        $this->db->query(" UPDATE map_lpe_skpd SET thn_m1 = '$surplus1' WHERE nor = '2'");

        //		$this->db->query(" UPDATE map_lpe_skpd SET thn_m1 = '$akhir' WHERE nor = '7'");


        // end tahun lalu		  

        /*        $sqlsawal = "SELECT * FROM map_lpe_skpd where nor='7'";
        $queryawal = $this->db->query($sqlsawal);
        $jmlsaldo = $queryawal->row();
        $jmlsal = $jmlsaldo->thn_m1;
        
        $sql41 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,1)='8' $skpd";
        $query41 = $this->db->query($sql41);
        $jmlp = $query41->row();
        $jmlpendapatan = $jmlp->nilai;
        $jmlpendapatan1 = number_format($jmlp->nilai, "2", ".", ",");
*/
        //		created by henri_tb
        $sqllo9 = "select isnull(sum(kredit-debet),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_rek6,1) in ('7') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo9 = $this->db->query($sqllo9);
        $penlo7 = $querylo9->row();
        $pen_lo7 = $penlo7->nilai;
        $pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

        $sqllo10 = "select isnull(sum(kredit-debet),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo10 = $this->db->query($sqllo10);
        $penlo8 = $querylo10->row();
        $pen_lo_lalu8 = $penlo8->nilai;
        $pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

        $sqllo11 = "select isnull(sum(debet-kredit),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_rek6,1) in ('8') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo11 = $this->db->query($sqllo11);
        $bello9 = $querylo11->row();
        $bel_lo9 = $bello9->nilai;
        $bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

        $sqllo12 = "select isnull(sum(debet-kredit),0) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and left(b.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")";
        $querylo12 = $this->db->query($sqllo12);
        $bello10 = $querylo12->row();
        $bel_lo_lalu10 = $bello10->nilai;
        $bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

        $surplus_lo3 = $pen_lo7 - $bel_lo9;

        $surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

        $selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;

        $sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
					inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('1','37') and kd_rek6 = '$ekuitas'  and year(a.tgl_voucher)=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //aba

        $hasil = $this->db->query($sql_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $lpe_lalu1  = $row001->thn_m1;
        }

        $sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
					inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('2','35') and kd_rek6 = '$ekuitas'  and year(a.tgl_voucher)=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $lpe_lalu2  = $row002->thn_m1;
        }

        $sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
					inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('3','31','32','33','34','36','38','39','40','41','42','43','44','45') and kd_rek6 = '$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe_lalu2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $lpe_lalu3  = $row003->thn_m1;
        }

        $sqlrkpp2 = "select isnull(sum(b.kredit)-sum(b.debet),0) as nilai from trhju_pkd a
					inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where left(b.kd_rek6,4) in ('3103') and year(a.tgl_voucher)=$thn_ang_1 and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $rkpp2 = $this->db->query($sqlrkpp2);
        $rkpp12 = $rkpp2->row();
        $rkpp_lalu2 = $rkpp12->nilai;
        $rkpp_lalu12 = number_format($rkpp12->nilai, "2", ",", ".");

        $sal_awal    = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3 + $rkpp_lalu2;
        /*		
        $sql51 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,1)='9'";
        $query51 = $this->db->query($sql51);
        $jmlb = $query51->row();
        $jmlbelanja = $jmlb->nilai;
        $jmlbelanja1 = number_format($jmlb->nilai, "2", ".", ",");
        $sql523 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,3)='923'";
        $query523 = $this->db->query($sql523);
        $jmlbm = $query523->row();
        $jmlbmbelanja = $jmlbm->nilai;
        $jmlbmbelanja1 = number_format($jmlbmbelanja, "2", ".", ",");
        $sql61 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,2)='71'";
        $query61 = $this->db->query($sql61);
        $jmlpm = $query61->row();
        $jmlpmasuk = $jmlpm->nilai;
        $sql62 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,2)='72'";
        $query62 = $this->db->query($sql62);
        $jmlpk = $query62->row();
        $jmlpkeluar = $jmlpk->nilai;
        $surplus = $jmlpendapatan - $jmlbelanja;
*/
        $sql = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
					inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('1','37') and kd_rek6 = '$ekuitas' and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //aba

        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row001) {
            $kd_rek   = $row001->nor;
            $parent   = $row001->parent;
            $nama     = $row001->uraian;
            $nilaiDR  = $row001->thn_m1;
        }

        $sqllpe1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
					inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('2','35') and kd_rek6 = '$ekuitas' and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe1);
        $nawal = 0;
        foreach ($hasil->result() as $row002) {
            $kd_rek   = $row002->nor;
            $parent   = $row002->parent;
            $nama     = $row002->uraian;
            $nilailpe1  = $row002->thn_m1;
        }

        $sqllpe2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
					inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev in ('3','31','32','33','34','36','38','39','40','41','42','43','44','45') and kd_rek6 = '$ekuitas' and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $hasil = $this->db->query($sqllpe2);
        $nawal = 0;
        foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $nilailpe2  = $row003->thn_m1;
        }

        $sqlrkpp3 = "select isnull(sum(b.kredit)-sum(b.debet),0) as nilai from trhju_pkd a
					inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where left(b.kd_rek6,4) in ('3103') and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(a.kd_skpd," . LENGTH_KDSKPD . ")=left('$id'," . LENGTH_KDSKPD . ")"; //Henri_TB

        $rkpp3 = $this->db->query($sqlrkpp3);
        $rkpp13 = $rkpp3->row();
        $rkpp = $rkpp13->nilai;
        $rkpp1 = number_format($rkpp13->nilai, "2", ",", ".");

        /*        $biaya_net = $jmlpmasuk - $jmlpkeluar;        
        $silpa = ($jmlpendapatan + $jmlpmasuk) - ($jmlbelanja + $jmlpkeluar);
        if ($silpa < 0)
        {
            $a = "(";
            $silpa1 = $silpa * -1;
            $b = ")";
        } else
        {
            $a = "";
            $silpa1 = $silpa;
            $b = "";
        }
*/
        $sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2 + $rkpp;
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

        if ($rkpp_lalu2 < 0) {
            $pp1 = "(";
            $rkpp_lalu2 = $rkpp_lalu2 * -1;
            $pp2 = ")";
        } else {
            $pp1 = "";
            $rkpp_lalu2;
            $pp2 = "";
        }

        if ($rkpp < 0) {
            $pp3 = "(";
            $rkpp = $rkpp * -1;
            $pp4 = ")";
        } else {
            $pp3 = "";
            $rkpp;
            $pp4 = "";
        }

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

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
         
            <tr>
                <td align=\"center\"><strong>$kab</strong></td>
            </tr>
	        <tr>
                <td align=\"center\"><strong>$nmskpd</strong></td>
            </tr>
			<tr>
                <td align=\"center\"><strong>LAPORAN PERUBAHAN EKUITAS</strong></td>
            </tr>
			<tr>
                <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1</strong></td>
            </tr>
              <tr>
                <td align=\"center\"><strong>$label</strong></td>
            </tr>   
        </table>";


        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">    
					<thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>                            
                            <td  bgcolor=\"#CCCCCC\" width=\"65%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>$thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>$thn_ang_1</b></td> 
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
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"65%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                        </tr>";


        $sql = "SELECT * FROM map_lpe_skpd  ORDER BY seq";

        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row) {

            $kd_rek   = $row->nor;
            $parent   = $row->parent;
            $nama     = $row->uraian;
            $nilai_1    = $row->thn_m1;


            /*        if ($nilai_1 < 0)
        {
            $tx = "(";
            $nilai_1 = $nilai_1 * -1;
            $ty = ")";
        } else
        {
            $tx = "";
            $ty = "";
        }
*/
            switch ($kd_rek) {
                case 1:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$c1" . number_format($sal_awal, "2", ",", ".") . "$d1</b></td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$cx" . number_format($real, "2", ",", ".") . "$dx</b></td>
                                                     </tr>";

                    break;
                case 2:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$lo13" . number_format($surplus_lo3, "2", ",", ".") . "$lo14</b></td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$lo15" . number_format($surplus_lo_lalu3, "2", ",", ".") . "$lo16</b></td>
                                                     </tr>";

                    break;
                case 3:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$nama</b></td>
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
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$pp3" . number_format($rkpp, "2", ",", ".") . "$d</b></td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$pp1" . number_format($rkpp_lalu2, "2", ",", ".") . "$d1</b></td>
                                                     </tr>";
                    break;
                case 8:
                    $cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$c" . number_format($sal_akhir, "2", ",", ".") . "$d</b></td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$c1" . number_format($sal_awal, "2", ",", ".") . "$d1</b></td>
                                                     </tr>";
            }
        }

        $cRet .=       " </table>";

        if ($jns_ttd == 1) {

            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kd_skpd='$id' and kode='PA' and nip='$ttd1'";
            $sqlttd = $this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd) {
                $nip = $rowttd->nip;
                $oioi = $rowttd->nm;
                $jabatan = $rowttd->jab;
            }

            $sqlttd12 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kd_skpd='$id' and kode='PPK' and nip='$ttd2'";
            $sqlttd2 = $this->db->query($sqlttd12);
            foreach ($sqlttd2->result() as $rowttd) {
                $nip2 = $rowttd->nip;
                $oioi2 = $rowttd->nm;
                $jabatan2 = $rowttd->jab;
            }


            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		 <tr>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 </tr>
		 <tr>
		 <td align=\"center\" width=\"50%\"> Mengetahui,</td>
		 <td align=\"center\" width=\"50%\"> $daerah, $ctgl_ttd</td>
		 </tr>		
		 <tr>
		 <td align=\"center\" width=\"50%\"> $jabatan </td>
		 <td align=\"center\" width=\"50%\"> $jabatan2 </td>
		 </tr>	
		 <tr>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 </tr>
		 <tr>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 </tr>
		  <tr>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 </tr>
		 <tr>
		 <td align=\"center\" width=\"50%\"> $oioi</td>
		 <td align=\"center\" width=\"50%\"> $oioi2 </td>
		 </tr>
		 <tr>
		 <td align=\"center\" width=\"50%\"> NIP :$nip</td>
		 <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
		 </tr>
         </table>
		 ";
        } else if ($jns_ttd == 3) {

            $sqlttd12 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kode='WK' and nip='1'";
            $sqlttd2 = $this->db->query($sqlttd12);
            foreach ($sqlttd2->result() as $rowttd) {
                $oioi2 = $rowttd->nm;
                $jabatan2 = $rowttd->jab;
            }


            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		 <tr>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 </tr>
		 <tr>
		 <td align=\"center\" width=\"50%\"> </td>
		 <td align=\"center\" width=\"50%\"> Pontianak, $ctgl_ttd</td>
		 </tr>		
		 <tr>
		 <td align=\"center\" width=\"50%\">  </td>
		 <td align=\"center\" width=\"50%\"> $jabatan2 </td>
		 </tr>	
		 <tr>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 </tr>
		 <tr>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 </tr>
		  <tr>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 <td align=\"center\" width=\"50%\"> &nbsp; </td>
		 </tr>
		 <tr>
		 <td align=\"center\" width=\"50%\"> </td>
		 <td align=\"center\" width=\"50%\"> $oioi2 </td>
		 </tr>
		 </table>
		 ";
        }

        $cRet .= '</table>';

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul = ("LPE SKPD $id / $cbulan");
        $this->template->set('title', 'LPE SKPD $id / $cbulan');
        switch ($pilih) {
            case 1;
                echo ("<title>LPE SKPD $cbulan</title>");
                echo $cRet;
                break;
            case 3;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                echo $cRet;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 0;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }

    //================================================ End Cetak LPE SKPD
}
