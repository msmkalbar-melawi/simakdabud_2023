<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class CLak extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('custom');
    }

    function index()
    {
        $data['page_title'] = 'LAPORAN ARUS KAS';
        $this->template->set('title', 'LAPORAN ARUS KAS');
        $this->template->load('template', 'akuntansi/cetak_lak', $data);
    }

   // KOTA CLAK
   function rpt_lak_kota_apbd_rinci($tglttd = "", $ttd = "")
   {
       $lntahunang = $this->session->userdata('pcThang');
       $lntahunang_l = $lntahunang - 1;
       $bulan = $this->uri->segment(3);
       $cttd = $this->uri->segment(4);
       $tglttd = $this->uri->segment(5);
       $tanggal = $this->tukd_model->tanggal_format_indonesia($tglttd);
       $jnss = $this->uri->segment(7);
       $ctk =$this->uri->segment(6);
       $cttd = str_replace('%20',' ', $cttd);
       
       
       switch ($bulan) {
           case  1:
               $judul = "JANUARI";
               break;
           case  2:
               $judul = "FEBRUARI";
               break;
           case  3:
               $judul = "MARET";
               break;
           case  4:
               $judul = "APRIL";
               break;
           case  5:
               $judul = "MEI";
               break;
           case  6:
               $judul = "JUNI";
               break;
           case  7:
               $judul = "JULI";
               break;
           case  8:
               $judul = "AGUSTUS";
               break;
           case  9:
               $judul = "SEPTEMBER";
               break;
           case  10:
               $judul = "OKTOBER";
               break;
           case  11:
               $judul = "NOVEMBER";
               break;
           case  12:
               $judul = "DESEMBER";
               break;
       }

       $sclient = $this->akuntansi_model->get_sclient();
       $cRet = '';
       $cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
               <tr>
                   <td rowspan=\"3\" align=\"left\" width=\"2%\">
                   <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"60\" height=\"70\" />
                    <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
               </tr>
               <tr>
                    <td align=\"center\"><strong>LAPORAN ARUS KAS</strong></td>
               </tr>                    
               <tr>
                    <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN 31 $judul $lntahunang</strong></td>
               </tr>
               <tr>
                    <td align=\"center\"><strong>&nbsp;</strong></td>
               </tr>
             </table>";

       if ($jnss == '1') {
           $cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
           <thead>
           <tr>
               <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>No</b></td>
               <td width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Uraian</b></td>
               <td width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>TOTAL</b></td>
               <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Pemkot $lntahunang</b></td>
               <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Blud $lntahunang</b></td>
               <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Bos $lntahunang</b></td>
               <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>$lntahunang_l</b></td>
           </tr>
           <tr>
              <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
              <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
              <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td>
              <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
              <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
              <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td>                 
              <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
           </tr>
           </thead>";
       } else {
           $cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
           <thead>
           <tr>
               <td width=\"2%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>No</b></td>
               <td width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Uraian</b></td>
               <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>$lntahunang</b></td>
               <td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>$lntahunang_l</b></td>
           </tr>
           <tr>
              <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
              <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
              <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td>
              <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td>  
           </tr>
           </thead>";
       }


       $arus1 = 0;
       $arus2 = 0;
       $arus3 = 0;
       $arus4 = 0;
       $total_arus = 0;

       $sql = "SELECT a.uraian,a.nor,a.bold,a.seq,a.kode_1,a.kode_2,a.kode_3,a.kode_4,a.thn_m1
               FROM map_lak_bpk_sanggau a 
               where a.seq<'420'
               order BY a.seq";
       $no = 0;
       $tot_nor = 0;
       $tot_peg = 0;
       $tot_brg = 0;
       $tot_mod = 0;
       $tot_bansos = 0;
       $hasil = $this->db->query($sql);
       foreach ($hasil->result() as $res) {
           $no = $no + 1;
           $nor = $res->nor;
           $bold = $res->bold;
           $nama = $res->uraian;
           $seq2 = $res->seq;
           $tahun_lalu1 = $res->thn_m1;

           $kode_1 = trim($res->kode_1);
           $kode_2 = trim($res->kode_2);
           $kode_3 = trim($res->kode_3);
           $kode_4 = trim($res->kode_4);

           if ($kode_1 == "") $kode_1 = "'xxx'";
           if ($kode_2 == "") $kode_2 = "'xxx'";
           if ($kode_3 == "") $kode_3 = "'xxx'";
           if ($kode_4 == "") $kode_4 = "'xxx'";

           $sql = "SELECT sum(case when jns='1' then real_spj end) as nilai, 
                   sum(case when jns='2' then real_spj end) as nilai_blud,
                   sum(case when jns='3' then real_spj end) as nilai_bos,
                   sum(case when jns='4' then real_spj end) as nilai_jkn
                   FROM data_lak WHERE (LEFT(kd_rek6,4) IN ($kode_1) 
                   or LEFT(kd_rek6,6) IN ($kode_2) or LEFT(kd_rek6,8) IN ($kode_3) or left (kd_rek6,12) in ($kode_4))
                   ";

           $hasil = $this->db->query($sql);
           foreach ($hasil->result() as $row) {
               $nilai = $row->nilai;
               $nilai_blud = $row->nilai_blud;
               $nilai_bos = $row->nilai_bos;
               $nilai_jkn = $row->nilai_jkn;
               $n_tot   = $nilai + $nilai_blud + $nilai_bos + $nilai_jkn;
           }
           if ($tahun_lalu1 < 0) {
               $tahun_lalu = $tahun_lalu1 * -1;
               $y = '(';
               $z = ')';	
           }  else {
               $tahun_lalu = $tahun_lalu1;
               $y = '';
               $z = '';
           }

           if ($jnss == '1') {
               switch ($bold) {
                   case 0:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $no . '</b></td> 
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td>
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  </tr>';
                       break;
                   case 1:

                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $no . '</b></td> 
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
                                  <td align="right" valign="top">' . number_format($n_tot, "2", ",", ".") . '</td> 
                                  <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td>
                                   <td align="right" valign="top">' . number_format($nilai_blud, "2", ",", ".") . '</td>   
                                  <td align="right" valign="top">' . number_format($nilai_bos, "2", ",", ".") . '</td> 
                                  <td align="right" valign="top">' . number_format($tahun_lalu, "2", ",", ".") . '</td> 
                               </tr>';
                       break;

                   case 2:

                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($n_tot, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($nilai_blud, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($nilai_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu,  "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
                   case 3:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td>
                                  <td align="right" valign="top">&nbsp; </td>
                                  </tr>';
                       break;
                   case 4:
                       $kas_terima_operasi = $nilai;
                       $kas_terima_operasi_blud = $nilai_blud;
                       $kas_terima_operasi_bos = $nilai_bos;
                       $kas_terima_operasi_jkn = $nilai_jkn;
                       $kas_terima_operasi_tot = $kas_terima_operasi + $kas_terima_operasi_blud + $kas_terima_operasi_bos + $kas_terima_operasi_jkn;

                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($n_tot, "2", ",", ".") . '</b></td>  
                                  <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($nilai_blud, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($nilai_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
                   case 5:

                       $kas_keluar_operasi = $nilai;
                       $kas_keluar_operasi_blud = $nilai_blud;
                       $kas_keluar_operasi_bos = $nilai_bos;
                       $kas_keluar_operasi_jkn = $nilai_jkn;
                       $kas_keluar_operasi_tot = $kas_keluar_operasi + $kas_keluar_operasi_blud + $kas_keluar_operasi_bos + $kas_keluar_operasi_jkn;

                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_keluar_operasi_tot,  "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_keluar_operasi,  "2", ",", ".") . '</b></td>    
                                  <td align="right" valign="top"><b>' . number_format($kas_keluar_operasi_blud,  "2", ",", ".") . '</b></td>    
                                  <td align="right" valign="top"><b>' . number_format($kas_keluar_operasi_bos,  "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu,  "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
                   case 6:
                       $kas_operasi = $kas_terima_operasi - $kas_keluar_operasi;
                       $kas_operasi_blud = $kas_terima_operasi_blud - $kas_keluar_operasi_blud;
                       $kas_operasi_bos = $kas_terima_operasi_bos - $kas_keluar_operasi_bos;
                       $kas_operasi_jkn = $kas_terima_operasi_jkn - $kas_keluar_operasi_jkn;
                       $kas_operasi_tot = $kas_terima_operasi_tot - $kas_keluar_operasi_tot;
                       $arus1 = $kas_operasi;
                       $arus1_blud = $kas_operasi_blud;
                       $arus1_bos = $kas_operasi_bos;
                       $arus1_jkn = $kas_operasi_jkn;
                       $arus1_tot = $arus1 + $arus1_blud + $arus1_bos + $arus1_jkn;

                       $cRet .= '
                              <tr>
                                  <td align="left"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_operasi_tot,  "2", ",", ".") . '</b></td>  
                                  <td align="right" valign="top"><b>' . number_format($kas_operasi,  "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_operasi_blud,  "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($kas_operasi_bos,  "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu,  "2", ",", ".") . '</b></td> 
                               </tr>
                               <tr>
                                  <td align="left" bgcolor="#555555" valign="top"></td>
                                  <td align="left" bgcolor="#555555"  valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td>
                                   <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                               </tr>
                               ';
                       break;
                   case 7:
                       $kas_terima_non = $nilai;
                       $kas_terima_non_blud = $nilai_blud;
                       $kas_terima_non_bos = $nilai_bos;
                       $kas_terima_non_jkn = $nilai_jkn;
                       $kas_terima_non_tot = $kas_terima_non + $kas_terima_non_blud + $kas_terima_non_bos + $kas_terima_non_jkn;

                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($n_tot,  "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($nilai,  "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($nilai_blud,  "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($nilai_bos,  "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu,  "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
                   case 8:

                       $kas_keluar_non = $nilai;
                       $kas_keluar_non_blud = $nilai_blud;
                       $kas_keluar_non_bos = $nilai_bos;
                       $kas_keluar_non_jkn = $nilai_jkn;
                       $kas_keluar_non_tot = $kas_keluar_non + $kas_keluar_non_blud + $kas_keluar_non_bos + $kas_keluar_non_jkn;

                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_keluar_non_tot,  "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_keluar_non,  "2", ",", ".") . '</b></td>
                                   <td align="right" valign="top"><b>' . number_format($kas_keluar_non_blud,  "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($kas_keluar_non_bos,  "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu,  "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
                   case 9:
                       $kas_operasi_non = $kas_terima_non - $kas_keluar_non;
                       $kas_operasi_non_blud = $kas_terima_non_blud - $kas_keluar_non_blud;
                       $kas_operasi_non_bos = $kas_terima_non_bos - $kas_keluar_non_bos;
                       $kas_operasi_non_jkn = $kas_terima_non_jkn - $kas_keluar_non_jkn;
                       $kas_operasi_non_tot = $kas_terima_non_tot - $kas_keluar_non_tot;
                       $arus2 = $kas_operasi_non;
                       $arus2_blud = $kas_operasi_non_blud;
                       $arus2_bos = $kas_operasi_non_bos;
                       $arus2_jkn = $kas_operasi_non_jkn;
                       $arus2_tot = $arus2 + $arus2_blud + $arus2_bos + $arus2_jkn;

                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_operasi_non_tot,  "2", ",", ".") . '</b></td>  
                                  <td align="right" valign="top"><b>' . number_format($kas_operasi_non,  "2", ",", ".") . '</b></td>
                                   <td align="right" valign="top"><b>' . number_format($kas_operasi_non_blud,  "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($kas_operasi_non_bos,  "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu,  "2", ",", ".") . '</b></td> 
                               </tr>
                               <tr>
                                  <td align="left" bgcolor="#555555" valign="top"></td>
                                  <td align="left" bgcolor="#555555"  valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td>
                                   <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                               </tr>

                               ';

                       break;

                   case 15:
                       $kas_terima_biaya = $nilai;
                       $kas_terima_biaya_blud = $nilai_blud;
                       $kas_terima_biaya_bos = $nilai_bos;
                       $kas_terima_biaya_jkn = $nilai_jkn;
                       $kas_terima_biaya_tot = $kas_terima_biaya + $kas_terima_biaya_blud + $kas_terima_biaya_bos + $kas_terima_biaya_jkn;

                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($n_tot,  "2", ",", ".") . '</b></td>  
                                  <td align="right" valign="top"><b>' . number_format($nilai,  "2", ",", ".") . '</b></td>
                                   <td align="right" valign="top"><b>' . number_format($nilai_blud,  "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($nilai_bos,  "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu,  "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
                   case 16:
                       $kas_keluar_biaya = $nilai;
                       $kas_keluar_biaya_blud = $nilai_blud;
                       $kas_keluar_biaya_bos = $nilai_bos;
                       $kas_keluar_biaya_jkn = $nilai_jkn;
                       $kas_keluar_biaya_tot = $kas_keluar_biaya + $kas_keluar_biaya_blud + $kas_keluar_biaya_bos + $kas_keluar_biaya_jkn;

                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_keluar_biaya_tot, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($nilai_blud, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($nilai_bos, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
                   case 17:
                       $kas_operasi_biaya = $kas_terima_biaya - $kas_keluar_biaya;
                       $kas_operasi_biaya_blud = $kas_terima_biaya_blud - $kas_keluar_biaya_blud;
                       $kas_operasi_biaya_bos = $kas_terima_biaya_bos - $kas_keluar_biaya_bos;
                       $kas_operasi_biaya_jkn = $kas_terima_biaya_jkn - $kas_keluar_biaya_jkn;
                       $kas_operasi_biaya_tot = $kas_terima_biaya_tot - $kas_keluar_biaya_tot;
                       $arus3 = $kas_operasi_biaya;
                       $arus3_blud = $kas_operasi_biaya_blud;
                       $arus3_bos = $kas_operasi_biaya_bos;
                       $arus3_jkn = $kas_operasi_biaya_jkn;
                       $arus3_tot = $arus3 + $arus3_blud + $arus3_bos + $arus3_jkn;

                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_operasi_biaya_tot, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_operasi_biaya, "2", ",", ".") . '</b></td>
                                   <td align="right" valign="top"><b>' . number_format($kas_operasi_biaya_blud, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($kas_operasi_biaya_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>
                               <tr>
                                  <td align="left" bgcolor="#555555" valign="top"></td>
                                  <td align="left" bgcolor="#555555"  valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td>
                                   <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                               </tr>
                               ';

                       break;
               }
           } else {

               switch ($bold) {
                   case 0:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $no . '</b></td> 
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  </tr>';
                       break;
                   case 1:

                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $no . '</b></td> 
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
                                  <td align="right" valign="top">' . number_format($n_tot, "2", ",", ".") . '</td> 
                                  <td align="right" valign="top">' . number_format($tahun_lalu, "2", ",", ".") . '</td> 
                               </tr>';
                       break;

                   case 2:

                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($n_tot, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu,  "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
                   case 3:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  </tr>';
                       break;
                   case 4:
                       $kas_terima_operasi = $nilai;
                       $kas_terima_operasi_blud = $nilai_blud;
                       $kas_terima_operasi_bos = $nilai_bos;
                       $kas_terima_operasi_jkn = $nilai_jkn;
                       $kas_terima_operasi_tot = $kas_terima_operasi + $kas_terima_operasi_blud + $kas_terima_operasi_bos + $kas_terima_operasi_jkn;

                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($n_tot, "2", ",", ".") . '</b></td>  
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
                   case 5:

                       $kas_keluar_operasi = $nilai;
                       $kas_keluar_operasi_blud = $nilai_blud;
                       $kas_keluar_operasi_bos = $nilai_bos;
                       $kas_keluar_operasi_jkn = $nilai_jkn;
                       $kas_keluar_operasi_tot = $kas_keluar_operasi + $kas_keluar_operasi_blud + $kas_keluar_operasi_bos + $kas_keluar_operasi_jkn;

                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_keluar_operasi_tot,  "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu,  "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
                   case 6:
                       $kas_operasi = $kas_terima_operasi - $kas_keluar_operasi;
                       $kas_operasi_blud = $kas_terima_operasi_blud - $kas_keluar_operasi_blud;
                       $kas_operasi_bos = $kas_terima_operasi_bos - $kas_keluar_operasi_bos;
                       $kas_operasi_jkn = $kas_terima_operasi_jkn - $kas_keluar_operasi_jkn;
                       $kas_operasi_tot = $kas_terima_operasi_tot - $kas_keluar_operasi_tot;
                       $arus1 = $kas_operasi;
                       $arus1_blud = $kas_operasi_blud;
                       $arus1_bos = $kas_operasi_bos;
                       $arus1_jkn = $kas_operasi_jkn;
                       $arus1_tot = $arus1 + $arus1_blud + $arus1_bos + $arus1_jkn;

                       $cRet .= '
                              <tr>
                                  <td align="center"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_operasi_tot,  "2", ",", ".") . '</b></td>  
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu,  "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 7:
                       $kas_terima_non = $nilai;
                       $kas_terima_non_blud = $nilai_blud;
                       $kas_terima_non_bos = $nilai_bos;
                       $kas_terima_non_jkn = $nilai_jkn;
                       $kas_terima_non_tot = $kas_terima_non + $kas_terima_non_blud + $kas_terima_non_bos + $kas_terima_non_jkn;

                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($n_tot,  "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu,  "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
                   case 8:

                       $kas_keluar_non = $nilai;
                       $kas_keluar_non_blud = $nilai_blud;
                       $kas_keluar_non_bos = $nilai_bos;
                       $kas_keluar_non_jkn = $nilai_jkn;
                       $kas_keluar_non_tot = $kas_keluar_non + $kas_keluar_non_blud + $kas_keluar_non_bos + $kas_keluar_non_jkn;

                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_keluar_non_tot,  "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu,  "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
                   case 9:
                       $kas_operasi_non = $kas_terima_non - $kas_keluar_non;
                       $kas_operasi_non_blud = $kas_terima_non_blud - $kas_keluar_non_blud;
                       $kas_operasi_non_bos = $kas_terima_non_bos - $kas_keluar_non_bos;
                       $kas_operasi_non_jkn = $kas_terima_non_jkn - $kas_keluar_non_jkn;
                       $kas_operasi_non_tot = $kas_terima_non_tot - $kas_keluar_non_tot;
                       $arus2 = $kas_operasi_non;
                       $arus2_blud = $kas_operasi_non_blud;
                       $arus2_bos = $kas_operasi_non_bos;
                       $arus2_jkn = $kas_operasi_non_jkn;
                       $arus2_tot = $arus2 + $arus2_blud + $arus2_bos + $arus2_jkn;

                       if ($kas_operasi_non_tot < 0) {
                           $kas_operasi_non_tot1 = $kas_operasi_non_tot * -1;
                           $t = '(';
                           $u = ')';	
                       }  else {
                           $kas_operasi_non_tot1 = $kas_operasi_non_tot;
                           $t = '';
                           $u = '';
                       }
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>'.$t.'' . number_format($kas_operasi_non_tot1,  "2", ",", ".") . ''.$u.'</b></td>  
                                  <td align="right" valign="top"><b>'.$y.'' . number_format($tahun_lalu,  "2", ",", ".") . ''.$z.'</b></td> 
                               </tr>';

                       break;

                   case 15:
                       $kas_terima_biaya = $nilai;
                       $kas_terima_biaya_blud = $nilai_blud;
                       $kas_terima_biaya_bos = $nilai_bos;
                       $kas_terima_biaya_jkn = $nilai_jkn;
                       $kas_terima_biaya_tot = $kas_terima_biaya + $kas_terima_biaya_blud + $kas_terima_biaya_bos + $kas_terima_biaya_jkn;

                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($n_tot,  "2", ",", ".") . '</b></td>  
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu,  "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
                   case 16:
                       $kas_keluar_biaya = $nilai;
                       $kas_keluar_biaya_blud = $nilai_blud;
                       $kas_keluar_biaya_bos = $nilai_bos;
                       $kas_keluar_biaya_jkn = $nilai_jkn;
                       $kas_keluar_biaya_tot = $kas_keluar_biaya + $kas_keluar_biaya_blud + $kas_keluar_biaya_bos + $kas_keluar_biaya_jkn;

                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($n_tot, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
                   case 17:
                       $kas_operasi_biaya = $kas_terima_biaya - $kas_keluar_biaya;
                       $kas_operasi_biaya_blud = $kas_terima_biaya_blud - $kas_keluar_biaya_blud;
                       $kas_operasi_biaya_bos = $kas_terima_biaya_bos - $kas_keluar_biaya_bos;
                       $kas_operasi_biaya_jkn = $kas_terima_biaya_jkn - $kas_keluar_biaya_jkn;
                       $kas_operasi_biaya_tot = $kas_terima_biaya_tot - $kas_keluar_biaya_tot;
                       $arus3 = $kas_operasi_biaya;
                       $arus3_blud = $kas_operasi_biaya_blud;
                       $arus3_bos = $kas_operasi_biaya_bos;
                       $arus3_jkn = $kas_operasi_biaya_jkn;
                       $arus3_tot = $arus3 + $arus3_blud + $arus3_bos + $arus3_jkn;

                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $no . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_operasi_biaya_tot, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
               }
           }
           //
       }

       //PAJAK
       $sql = $this->db->query("SELECT SUM(terima) as terima, SUM(setor) as setor FROM (
                       SELECT SUM(b.nilai) as terima, 0 setor FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                       WHERE LEFT(b.kd_rek6,3) IN ('210') and month(a.tgl_bukti)<='$bulan'
                       UNION ALL
                       SELECT 0 terima, SUM(b.nilai) as setor FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                       WHERE LEFT(b.kd_rek6,3) IN ('210') and month(a.tgl_bukti)<='$bulan'
                       UNION ALL
                       SELECT 0 terima,-40814996 as setor
                       )a")->row();
       $terima_j = $sql->terima;
       $setor_pajak_ini = $sql->setor;
       /*240000*/
       /*              
                       $sql = $this->db->query("select sum(b.kredit) as ju_inputan FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd=b.kd_unit 
                       AND a.no_voucher=b.no_voucher WHERE left(b.kd_rek5,3) IN ('211') and a.tabel='1' and  YEAR(a.tgl_voucher)='$lntahunang' 
                       and a.no_voucher='01/utang ppn/1.20.10.00'")->row();
                       $inputan_jurnal = $sql->ju_inputan;
       */
       $sql21 = $this->db->query("select 0 as utang_pajak")->row();
       $setor_pajak_lalux = $sql21->utang_pajak;

       // $setor_pajakx = $setor_pajak_ini+$setor_pajak_lalux;
       $setor_pajakx = $setor_pajak_ini;

       $terima_pajak = $terima_j;


       //CP inputan
       $sql = $this->db->query("SELECT 0 as nilai")->row();
       $cp_out = $sql->nilai;


       //CP jurnal
       $sql = $this->db->query("SELECT 0 as nilai")->row();
       $cp2 = $sql->nilai;
       /*13483917+89500+1132000*/

       //CP baru
       /*                   $sql = $this->db->query("select sum(b.debet) as pengem_kapuas FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher 
                       WHERE b.kd_rek5 IN ('1110101') and a.tabel='1' and YEAR(a.tgl_voucher)='$lntahunang' 
                       and a.no_voucher in ('BUD/21/penerimaan sisa kas PD.Kapuas Indah/1.20.00.00')")->row();
                       $cp_kapuas = $sql->pengem_kapuas;

                       $sql = $this->db->query("select sum(b.kredit) as pengem_b FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher 
                       WHERE b.kd_rek5 IN ('1110501') and YEAR(a.tgl_voucher)='$lntahunang' 
                       and a.no_voucher in ('002/koreksi kas/BPBD/2016','04/saldo kas ta.2015/2016')")->row();
                        $cp_skpd_baru = $sql->pengem_b;
       */
       $cp = $cp2;
       $arus4 = ($terima_pajak + $cp) - ($setor_pajakx + $cp_out);



       //PAJAK-LAIN_blud
       $sql = $this->db->query("SELECT SUM(terima) as terima, SUM(setor) as setor FROM (
                       SELECT SUM(b.nilai) as terima, 0 setor FROM trhtrmpot_blud a INNER JOIN trdtrmpot_blud b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                       WHERE LEFT(b.kd_rek5,3) IN ('210') and month(a.tgl_bukti)<='$bulan' and a.kd_skpd<>''
                       UNION ALL
                       SELECT 0 terima, SUM(b.nilai) as setor FROM trhstrpot_blud a INNER JOIN trdstrpot_blud b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                       WHERE LEFT(b.kd_rek5,3) IN ('210') and month(a.tgl_bukti)<='$bulan' and a.kd_skpd<>''
                       )a")->row();
       $terima_tax_blud = $sql->terima;
       $setor_tax_blud = $sql->setor;


       //PAJAK-LAIN_bos
       $sql = $this->db->query("SELECT SUM(terima) as terima, SUM(setor) as setor FROM (
                       SELECT SUM(b.nilai) as terima, 0 setor FROM trhtrmpot_bos a INNER JOIN trdtrmpot_bos b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                       WHERE LEFT(b.kd_rek5,3) IN ('210') and month(a.tgl_bukti)<='$bulan' and a.kd_skpd<>''
                       UNION ALL
                       SELECT 0 terima, SUM(b.nilai) as setor FROM trhstrpot_bos a INNER JOIN trdstrpot_bos b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                       WHERE LEFT(b.kd_rek5,3) IN ('210') and month(a.tgl_bukti)<='$bulan' and a.kd_skpd<>''
                       union all
                       select 0.00 terima, 0.00 setor
                       )a")->row();
       $terima_tax_bos = $sql->terima;
       $setor_tax_bos = $sql->setor;
       /*17934294*/

       //PAJAK-LAIN_jkn
       $sql = $this->db->query("SELECT SUM(terima) as terima, SUM(setor) as setor FROM (
                       SELECT SUM(b.nilai) as terima, 0 setor FROM trhtrmpot_pusk a INNER JOIN trdtrmpot_pusk b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                       WHERE LEFT(b.kd_rek5,3) IN ('210') and month(a.tgl_bukti)<='$bulan' and a.kd_skpd<>''
                       UNION ALL
                       SELECT 0 terima, SUM(b.nilai) as setor FROM trhstrpot_pusk a INNER JOIN trdstrpot_pusk b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
                       WHERE LEFT(b.kd_rek5,3) IN ('210') and month(a.tgl_bukti)<='$bulan' and a.kd_skpd<>'' and b.kd_rek5<>''
                       )a")->row();
       $terima_tax_jkn = $sql->terima;
       $setor_tax_jkn = $sql->setor;


       //KAS di KASDA
       /*
                       $sql = $this->db->query("select 
                       SUM(CASE WHEN YEAR(a.tgl_voucher)<'$lntahunang'  AND b.kd_rek5 IN ('1110101','1110301') THEN (debet-kredit) ELSE 0 END) as lalu,
                       SUM(CASE WHEN YEAR(a.tgl_voucher)<='$lntahunang' AND b.kd_rek5 IN ('1110101','1110301') THEN (debet-kredit) ELSE 0 END) as ini,
                       SUM(CASE WHEN YEAR(a.tgl_voucher)<='$lntahunang' AND b.kd_rek5 IN ('1110201') THEN (debet-kredit) ELSE 0 END) as ter_ini,
                       SUM(CASE WHEN YEAR(a.tgl_voucher)<='$lntahunang' AND b.kd_rek5 IN ('1110401') THEN (debet-kredit) ELSE 0 END) as blud,
                       SUM(CASE WHEN YEAR(a.tgl_voucher)<='$lntahunang' AND b.kd_rek5 IN ('1110501') THEN (debet-kredit) ELSE 0 END) as lain
                       FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher 
                       WHERE b.kd_rek5 IN ('1110101','1110301','1110201','1110401','1110501')
                       ")->row();
       */
       $masuk_bos = 0; //Transfer masuk bos
       //$sql39 = $this->db->query("select 26797.79+2885956.61 as nilai")->row();
       $sql39 = $this->db->query("select 0 as nilai")->row();
       $masuk_bos = $sql39->nilai;
       /*750000*/

       $masuk_blud = 0;
       //Transfer masuk blud
       //$sql32 = $this->db->query("select 2286900618.25+1.22 as nilai")->row();
       $sql32 = $this->db->query("select 0 as nilai")->row();
       $masuk_blud = 0; //$sql32->nilai;


       $out_blud = 0;
       //Transfer keluar blud
       $sql33 = $this->db->query("select 0.00 as nilai")->row();
       $out_blud = 0; //$sql33->nilai;


       //Transfer keluar bos
       //$sql34 = $this->db->query("select 2286910618.25+229468103.00-10000.00+11599200 as nilai")->row();
       $sql34 = $this->db->query("select 0 as nilai")->row();
       $out_bos = $sql34->nilai;
       /*2240000+3014227.05*/

       $arus4_blud = $terima_tax_blud + $masuk_blud - ($setor_tax_blud + $out_blud);
       $arus4_bos = $terima_tax_bos + $masuk_bos - ($setor_tax_bos + $out_bos);
       //$arus4_jkn=$terima_tax_jkn+$masuk_jkn-($setor_tax_jkn+$out_jkn);

       //$kas_lalu_blud=4500923677.57;
       //untuk pemanggilan RINCIAN(DETAIL) nilai saldo di neraca, pada LAK, tidak mempengaruh cetakan LK lainnya 
       $kas_lalu_kasda = 179180205429.80;
       $kas_lalu_pengeluaran = 208905340.00;
       $kas_lalu_blud = 20278995309.54;
       $kas_lalu_bos = 677804780.82;
       $kas_lalu_jkn = 1129901858.05;
       $kas_lainnya = 951167755.83;

       $kas_ini_kasda = 293775356505.76;
       $kas_ini_pengeluaran = 517726360.89;
       $kas_ini_blud = 11366263034.61;
       $kas_ini_bos = 510851069.40;
       $kas_ini_jkn = 13622553.55;

       $sql = $this->db->query("select sum(z.lalu) as lalu,sum(z.ini) as ini,sum(z.ter_ini) as ter_ini,sum(z.blud) as blud,sum(z.lain) as lain from (
               select 
               SUM(CASE WHEN YEAR(a.tgl_voucher)<'$lntahunang'  AND c.kd_rek5 IN ('11010101','11010301') THEN (debet-kredit) ELSE 0 END) as lalu,
               SUM(CASE WHEN YEAR(a.tgl_voucher)<'$lntahunang'  AND c.kd_rek5 IN ('11010101','11010301') THEN (debet-kredit) ELSE 0 END) as ini,
               SUM(CASE WHEN YEAR(a.tgl_voucher)<'$lntahunang' AND c.kd_rek5 IN ('11010201') THEN (debet-kredit) ELSE 0 END) as ter_ini,
               SUM(CASE WHEN YEAR(a.tgl_voucher)<'$lntahunang' AND c.kd_rek5 IN ('11010401','11010501','11010601','11010701') THEN (debet-kredit) ELSE 0 END) as blud,
               SUM(CASE WHEN YEAR(a.tgl_voucher)<'$lntahunang' AND c.kd_rek5 IN ('11010701') THEN (debet-kredit) ELSE 0 END) as lain
               FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher 
               join ms_rek6 c on b.kd_rek6=c.kd_rek6
               WHERE c.kd_rek5 IN ('11010101','11010201','11010301','11010401','11010501','11010601','11010701')
               union all
               select 
               0 as lalu,
               SUM(CASE WHEN YEAR(a.tgl_voucher)='$lntahunang' AND c.kd_rek5 IN ('11010101','11010301') THEN (debet-kredit) ELSE 0 END) as ini,
               SUM(CASE WHEN YEAR(a.tgl_voucher)='$lntahunang' AND c.kd_rek5 IN ('11010201') THEN (debet-kredit) ELSE 0 END) as ter_ini,
               SUM(CASE WHEN YEAR(a.tgl_voucher)='$lntahunang' AND c.kd_rek5 IN ('11010401','11010501','11010601','11010701') THEN (debet-kredit) ELSE 0 END) as blud,
               SUM(CASE WHEN YEAR(a.tgl_voucher)='$lntahunang' AND c.kd_rek5 IN ('11010701') THEN (debet-kredit) ELSE 0 END) as lain
               FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher 
               join ms_rek6 c on b.kd_rek6=c.kd_rek6
               WHERE c.kd_rek5 IN ('11010101','11010201','11010301','11010401','11010501','11010601','11010701') and month(a.tgl_voucher)<='$bulan'
           ) z ")->row();

       $kas_lalu = $sql->lalu;
       $kas_ini = $sql->ini;
       $kas_terima = $sql->ter_ini;
       $blud = $sql->blud;
       $lain = $sql->lain;
       $total_kas = 0;
       //$total_kas=$kas_ini+$kas_terima+$blud+$lain ;                  
       $sql = "SELECT a.nor,a.uraian,a.bold,a.seq,a.thn_m1 from map_lak_bpk_sanggau a where a.seq>'419' order by a.seq";
       $hasil = $this->db->query($sql);
       foreach ($hasil->result() as $res) {
           $boldnor = $res->nor;
           $bold = $res->bold;
           $nama = $res->uraian;
           $tahun_lalu = $res->thn_m1;
           $seq = $res->seq;

           if ($jnss == '1') {
               switch ($bold) {
                   case 0:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td>
                                  <td align="right" valign="top">&nbsp; </td>
                                  </tr>';
                       break;
                   case 1:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
                                  <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td>  
                                  <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
                                  <td align="right" valign="top"></td>
                                  <td align="right" valign="top"></td>             
                                  <td align="right" valign="top">' . number_format($tahun_lalu, "2", ",", ".") . '</td> 
                               </tr>';
                       break;

                   case 2:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($cp_out + $out_blud + $out_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($cp_out, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($out_blud, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($out_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 3:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td>
                                  <td align="right" valign="top">&nbsp; </td>      
                                  <td align="right" valign="top">&nbsp; </td> 
                                  </tr>';
                       break;
                   case 4:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b></b></td>
                                   <td align="right" valign="top"><b></b></td>             
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 5:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($terima_pajak + $terima_tax_bos + $terima_tax_blud, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($terima_pajak, "2", ",", ".") . '</b></td>
                                   <td align="right" valign="top"><b>' . number_format($terima_tax_blud, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($terima_tax_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 14:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($terima_pajak + $cp + $masuk_bos + $terima_tax_bos + $terima_tax_blud, "2", ",", ".") . '</b></td>  
                                  <td align="right" valign="top"><b>' . number_format($terima_pajak + $cp, "2", ",", ".") . '</b></td>
                                   <td align="right" valign="top"><b>' . number_format($terima_tax_blud, "2", ",", ".") . '</b></td>                              
                                  <td align="right" valign="top"><b>' . number_format($terima_tax_bos + $masuk_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;

                   case 20:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($setor_pajakx + $setor_tax_bos + $setor_tax_blud, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($setor_pajakx, "2", ",", ".") . '</b></td>
                                   <td align="right" valign="top"><b>' . number_format($setor_tax_blud, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($setor_tax_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 15:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($setor_pajakx + $setor_tax_bos + $out_bos + $setor_tax_blud + $cp_out, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($setor_pajakx + $cp_out, "2", ",", ".") . '</b></td>
                                   <td align="right" valign="top"><b>' . number_format($setor_tax_blud, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($setor_tax_bos + $out_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;


                   case 7:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_lalu + $kas_lalu_bos + $kas_lalu_blud, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_lalu, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_lalu_blud, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($kas_lalu_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 8:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($total_arus_bos + $kas_lalu_bos + $kas_ini + $total_arus_blud + $kas_lalu_blud, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_ini, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($total_arus_blud + $kas_lalu_blud, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($total_arus_bos + $kas_lalu_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 9:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_terima, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_terima, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b></b></td>
                                   <td align="right" valign="top"><b></b></td>
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                       /*
                           --BLUD--
                           case 10:
                           $cRet .='<tr>
                                  <td align="left"  valign="top"><b>'.$boldnor.'</b></td>
                                  <td align="left"  valign="top"><b>'.$nama.'</b></td> 
                                  <td align="right" valign="top"><b>'.number_format($total_arus_blud+$kas_lalu_blud, "2", ",", ".").'</b></td> 
                                  <td align="right" valign="top"><b></b></td> 
                                  <td align="right" valign="top"><b>'.number_format($total_arus_blud+$kas_lalu_blud, "2", ",", ".").'</b></td> 
                                  <td align="right" valign="top"><b></b></td>
                                  <td align="right" valign="top"><b>'.number_format($tahun_lalu, "2", ",", ".").'</b></td> 
                               </tr>'; 
                           break;      
                           --BOS--                                        
                           case 11:
                           $cRet .='<tr>
                                  <td align="left"  valign="top"><b>'.$boldnor.'</b></td>
                                  <td align="left"  valign="top"><b>'.$nama.'</b></td> 
                                  <td align="right" valign="top"><b>'.number_format($lain, "2", ",", ".").'</b></td> 
                                  <td align="right" valign="top"><b>'.number_format($lain, "2", ",", ".").'</b></td> 
                                  <td align="right" valign="top"><b></b></td> 
                                  <td align="right" valign="top"><b>'.number_format($tahun_lalu, "2", ",", ".").'</b></td> 
                               </tr>'; 
                           break;  
                           */
                   case 12:
                       $boldnor = $boldnor - 2;
                       $cRet .= '
                               <tr>
                                  <td align="left" bgcolor="#555555" valign="top"></td>
                                  <td align="left" bgcolor="#555555"  valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td>
                                   <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                               </tr>
                               <tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format(($total_arus_bos + $kas_lalu_bos + $kas_ini + $total_arus_blud + $kas_lalu_blud + $kas_terima), "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b></b></td> 
                                  <td align="right" valign="top"><b></b></td>
                                   <td align="right" valign="top"><b></b></td>
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>
                               <tr>
                                  <td align="left" bgcolor="#555555" valign="top"></td>
                                  <td align="left" bgcolor="#555555"  valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td>
                                   <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                               </tr>
                               ';
                       break;
                   case 13:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td>  
                                  <td align="right" valign="top"><b>' . number_format($arus4 + $arus4_blud + $arus4_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($arus4, "2", ",", ".") . '</b></td>
                                   <td align="right" valign="top"><b>' . number_format($arus4_blud, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($arus4_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 16:
                       $cRet .= '<tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($cp + $masuk_blud + $masuk_bos, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($cp, "2", ",", ".") . '</b></td>
                                   <td align="right" valign="top"><b>' . number_format($masuk_blud, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($masuk_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 99:

                       $total_arus = $arus1 + $arus2 + $arus3 + $arus4;
                       $total_arus_blud = $arus1_blud + $arus2_blud + $arus3_blud + $arus4_blud;
                       $total_arus_bos = $arus1_bos + $arus2_bos + $arus3_bos + $arus4_bos;
                       //$total_arus_jkn= $arus1_jkn+$arus2_jkn+$arus3_jkn+$arus4_jkn;     
                       $total_arus_tot = $total_arus + $total_arus_blud + $total_arus_bos;

                       $cRet .= '
                               <tr>
                                  <td align="left" bgcolor="#555555" valign="top"></td>
                                  <td align="left" bgcolor="#555555"  valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td>
                                   <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                               </tr>

                               <tr>
                                  <td align="left"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($total_arus_tot, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($total_arus, "2", ",", ".") . '</b></td>
                                   <td align="right" valign="top"><b>' . number_format($total_arus_blud, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($total_arus_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>

                               <tr>
                                  <td align="left" bgcolor="#555555" valign="top"></td>
                                  <td align="left" bgcolor="#555555"  valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td>
                                   <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                                  <td align="right" bgcolor="#555555" valign="top"></td> 
                               </tr>   
                               ';
                       break;
               }
           } else {
               switch ($bold) {
                   case 0:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  </tr>';
                       break;
                   case 1:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
                                  <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td>  
                                  <td align="right" valign="top">' . number_format($tahun_lalu, "2", ",", ".") . '</td> 
                               </tr>';
                       break;

                   case 2:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($cp_out + $out_blud + $out_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 3:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;<b>' . $nama . '</b></td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  <td align="right" valign="top">&nbsp; </td> 
                                  </tr>';
                       break;
                   case 4:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 5:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($terima_pajak + $terima_tax_blud + $terima_tax_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 14:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($terima_pajak + $cp + $masuk_bos + $terima_tax_bos + $terima_tax_blud + $masuk_blud, "2", ",", ".") . '</b></td>  
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;

                   case 20:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($setor_pajakx + $setor_tax_blud + $setor_tax_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 15:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($setor_pajakx + $setor_tax_blud + $out_blud + $setor_tax_bos + $out_bos + $cp_out, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;


                   case 7:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_lalu + $kas_lalu_blud + $kas_lalu_bos + $kas_lalu_jkn + $kas_lainnya, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 8:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($cp, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 10:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_terima, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 9:
                       $cRet .='<tr>
                                  <td align="center"  valign="top"><b>'.$boldnor.'</b></td>
                                  <td align="left"  valign="top"><b>'.$nama.'</b></td> 
                                  <td align="right" valign="top"><b>'.number_format($kas_operasi_tot + $kas_operasi_non_tot + $kas_lalu + $kas_lalu_blud + $kas_lalu_bos + $kas_lalu_jkn + $kas_lainnya + $arus4 + $arus4_blud + $arus4_bos, "2", ",", ".").'</b></td> 
                                  <td align="right" valign="top"><b>'.number_format($tahun_lalu, "2", ",", ".").'</b></td> 
                               </tr>'; 
                       break;            
                   case 11:
                       $cRet .='<tr>
                                  <td align="center"  valign="top"><b>'.$boldnor.'</b></td>
                                  <td align="left"  valign="top"><b>'.$nama.'</b></td> 
                                  <td align="right" valign="top"><b>'.number_format($kas_operasi_tot + $kas_operasi_non_tot + $kas_lalu + $kas_lalu_blud + $kas_lalu_bos + $kas_lalu_jkn + $kas_lainnya + $arus4 + $arus4_blud + $arus4_bos, "2", ",", ".").'</b></td> 
                                  <td align="right" valign="top"><b>'.number_format($tahun_lalu, "2", ",", ".").'</b></td> 
                               </tr>'; 
                       break;
                       /*
                           --BLUD--
                           case 10:
                           $cRet .='<tr>
                                  <td align="left"  valign="top"><b>'.$boldnor.'</b></td>
                                  <td align="left"  valign="top"><b>'.$nama.'</b></td> 
                                  <td align="right" valign="top"><b>'.number_format($blud, "2", ",", ".").'</b></td> 
                                  <td align="right" valign="top"><b>'.number_format($tahun_lalu, "2", ",", ".").'</b></td> 
                               </tr>'; 
                           break;                                              
                           --BOS--
                           case 11:
                           $cRet .='<tr>
                                  <td align="left"  valign="top"><b>'.$boldnor.'</b></td>
                                  <td align="left"  valign="top"><b>'.$nama.'</b></td> 
                                  <td align="right" valign="top"><b>'.number_format($lain, "2", ",", ".").'</b></td> 
                                  <td align="right" valign="top"><b>'.number_format($tahun_lalu, "2", ",", ".").'</b></td> 
                               </tr>'; 
                           break;                                              
                           */
                          
                   case 12:
                       $boldnor = $boldnor - 2;
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_ini + $total_arus_blud + $kas_lalu_blud + $total_arus_bos + $kas_lalu_bos + $kas_terima, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 13:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td>  
                                  <td align="right" valign="top"><b>' . number_format($arus4 + $arus4_blud + $arus4_bos, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 16:
                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($cp + $masuk_blud + $masuk_bos, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 19:
                       
                       $kas_operasi_tot = $kas_terima_operasi_tot - $kas_keluar_operasi_tot;
                       
                       $kas_operasi_non_tot = $kas_terima_non_tot - $kas_keluar_non_tot;

                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($kas_operasi_tot + $kas_operasi_non_tot+$arus4 + $arus4_blud + $arus4_bos, "2", ",", ".") . '</b></td>
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';
                       break;
                   case 99:
                       $kas_keluar_biaya_blud = $nilai_blud;
                       $kas_operasi_biaya_blud = $kas_terima_biaya_blud - $kas_keluar_biaya_blud;
                       $arus3_blud = $kas_operasi_biaya_blud;
                       
                       $kas_keluar_biaya_bos = $nilai_bos;
                       $kas_operasi_biaya_bos = $kas_terima_biaya_bos - $kas_keluar_biaya_bos;
                       $arus3_bos = $kas_operasi_biaya_bos;

                       $arus1 = $kas_operasi;
                       
                       $total_arus = $arus1 + $arus2 + $arus3 + $arus4;
                       $total_arus_blud = $arus1_blud + $arus2_blud + $arus3_blud + $arus4_blud;
                       $total_arus_bos = $arus1_bos + $arus2_bos + $arus3_bos + $arus4_bos;
                       //$total_arus_jkn= $arus1_jkn+$arus2_jkn+$arus3_jkn+$arus4_jkn;     
                       $total_arus_tot = $total_arus + $total_arus_blud + $total_arus_bos;

                       $cRet .= '<tr>
                                  <td align="center"  valign="top"><b>' . $boldnor . '</b></td>
                                  <td align="left"  valign="top"><b>' . $nama . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($total_arus_tot, "2", ",", ".") . '</b></td> 
                                  <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
                               </tr>';

                       break;
               }
           }
       }
       //sinivin
       $cRet .= '<tr>
                   <td align="center"  valign="top"><b>&nbsp;</b></td>
                   <td align="left"  valign="top"><b>Saldo Akhir Kas Terdiri dari :</b></td> 
                   <td align="right" valign="top"><b>&nbsp;</b></td> 
                   <td align="right" valign="top"><b>&nbsp;</b></td> 
                </tr>';
       $cRet .= '<tr>
                   <td align="center"  valign="top"><b>&nbsp;</td>
                   <td align="left"  valign="top">Kas di Kas Daerah</td> 
                   <td align="right" valign="top">' . number_format($kas_ini_kasda, "2", ",", ".") . ';</td> 
                   <td align="right" valign="top">' . number_format($kas_lalu_kasda, "2", ",", ".") . '</td> 
                </tr>';
       $cRet .= '<tr>
                   <td align="center"  valign="top">&nbsp;</td>
                   <td align="left"  valign="top">Kas di Bendahara Pengaluaran</td> 
                   <td align="right" valign="top">' . number_format($kas_ini_pengeluaran, "2", ",", ".") . '</td> 
                   <td align="right" valign="top">' . number_format($kas_lalu_pengeluaran, "2", ",", ".") . '</td> 
                </tr>';
                $cRet .= '<tr>
                <td align="center"  valign="top">&nbsp;</td>
                <td align="left"  valign="top">Kas di BLUD</td> 
                <td align="right" valign="top">' . number_format($kas_ini_blud, "2", ",", ".") . '</td> 
                <td align="right" valign="top">' . number_format($kas_lalu_blud, "2", ",", ".") . '</td> 
             </tr>';
    $cRet .= '<tr>
                <td align="center"  valign="top">&nbsp;</td>
                <td align="left"  valign="top">Kas di Dana BOS</td> 
                <td align="right" valign="top">' . number_format($kas_ini_bos, "2", ",", ".") . '</td> 
                <td align="right" valign="top">' . number_format($kas_lalu_bos, "2", ",", ".") . '</td> 
             </tr>'; 
           $cRet .= '<tr>
             <td align="center"  valign="top">&nbsp;</td>
             <td align="left"  valign="top">Kas dana Kapitasi pada FKTP</td> 
             <td align="right" valign="top">' . number_format($kas_ini_jkn, "2", ",", ".") . '</td> 
             <td align="right" valign="top">' . number_format($kas_lalu_jkn, "2", ",", ".") . '</td> 
          </tr>';
           $cRet .= '<tr>
             <td align="center"  valign="top">&nbsp;</td>
             <td align="left"  valign="top">Kas lainnya - PER </td> 
             <td align="right" valign="top">' . number_format($kas_lainnya, "2", ",", ".") . '</td> 
             <td align="right" valign="top">' . number_format($kas_lainnya, "2", ",", ".") . '</td> 
          </tr>'; 
          $cRet .= '<tr>
          <td align="center"  valign="top">&nbsp;</b></td>
          <td align="left"  valign="top"><b>Total</b></td> 
          <td align="right" valign="top"><b>' . number_format($kas_lainnya + $kas_ini_jkn + $kas_ini_bos + $kas_ini_blud + $kas_ini_pengeluaran + $kas_ini_kasda, "2", ",", ".") . '</b></td> 
          <td align="right" valign="top"><b><b>' . number_format($kas_lainnya + $kas_lalu_jkn + $kas_lalu_bos + $kas_lalu_blud + $kas_lalu_pengeluaran + $kas_lalu_kasda, "2", ",", ".") . '</b></b></td> 
       </tr>';

    $cRet .= "</table>";



                       $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$cttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
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
                                               <TD align="center" > Sanggau , ' . $tanggal . '</TD>
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
                                               <TD align="center" > Sanggau , ' . $tanggal . '</TD>
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
       
       $data['prev'] = $cRet;
       $judul = 'LAPORAN LAK';
       switch ($ctk) {
           case 0;
               echo ("<title>$judul</title>");
               echo $cRet;
               break;
           case 1;
               $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'P');
               break;
           case 2;
               header("Cache-Control: no-cache, no-store, must-revalidate");
               header("Content-Type: application/vnd.ms-excel");
               header("Content-Disposition: attachment; filename= $judul.xls");
               $this->load->view('anggaran/rka/perkadaII', $data);
               break;
       }
   }
   // END



}
