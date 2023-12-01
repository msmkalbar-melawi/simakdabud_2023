<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class perda_penetapan extends CI_Controller {

public $ppkd = "4.02.02";
public $ppkd1 = "4.02.02.02";
public $keu1 = "4.02.02.01";


public $ppkd_lama = "4.02.02";
public $ppkd1_lama = "4.02.02.02";

    function __contruct()
    {   
        parent::__construct();
    }
    
    function rp_minus($nilai){
        if($nilai<0){
            $nilai = $nilai * (-1);
            $nilai = '('.number_format($nilai,"2",",",".").')';    
        }else{
            $nilai = number_format($nilai,"2",",","."); 
        }
        
        return $nilai;
    }   

    function kode_ttd($kode){
        $kode1='';
        switch ($kode) {
            case 'PA':
                $kode1 = 'Pengguna Anggaran';
            break;
            case 'KPA':
                $kode1 = 'Kuasa Pengguna Anggaran';
            break;
        }
        return $kode1;
    }   
    
    function nvl($val, $replace){
        if( is_null($val) || $val === '' )  
            return $replace;
        else                                
            return $val;
    }   
 
    function persen($nilai,$nilai2){
            if($nilai != 0){
                $persen = $this->rp_minus((($nilai2 - $nilai)/$nilai)*100);
            }else{
                if($nilai2 == 0){
                    $persen = $this->rp_minus(0);
                }else{
                    $persen = $this->rp_minus(100);
                }
            } 
          return $persen;  
     }
 
    function get_status($tgl,$skpd){
        $n_status = '';
        $tanggal = $tgl;
        $sql = "SELECT case when '$tanggal'>=tgl_dpa_ubah then 'nilai_ubah' 
                    when '$tanggal'>=tgl_dpa_sempurna then 'nilai_sempurna' 
                    when '$tanggal'<=tgl_dpa 
                    then 'nilai' else 'nilai' end as anggaran from trhrka where kd_skpd ='$skpd'  ";
        
        $q_trhrka = $this->db->query($sql);
        $num_rows = $q_trhrka->num_rows();
        
        foreach ($q_trhrka->result() as $r_trhrka){
             $n_status = $r_trhrka->anggaran;                   
        }    
        return $n_status;                         
    }

    function  tanggal_format_indonesia($tgl){
            
        $tanggal  = explode('-',$tgl); 
        $bulan  = $this-> getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2].' '.$bulan.' '.$tahun;

    }
    

 
 
    function  getBulan($bln){
        switch  ($bln){
        case  1:
        return  "Januari";
        break;
        case  2:
        return  "Februari";
        break;
        case  3:
        return  "Maret";
        break;
        case  4:
        return  "Maret";
        break;
        case  5:
        return  "Mei";
        break;
        case  6:
        return  "Juni";
        break;
        case  7:
        return  "Juli";
        break;
        case  8:
        return  "Agustus";
        break;
        case  9:
        return  "September";
        break;
        case  10:
        return  "Oktober";
        break;
        case  11:
        return  "November";
        break;
        case  12:
        return  "Desember";
        break;
    }
    }
    function right($value, $count){
    return substr($value, ($count*-1));
    }

    function left($string, $count){
    return substr($string, 0, $count);
    }

    function  dotrek($rek){
                $nrek=strlen($rek);
                switch ($nrek) {
                case 1:
                $rek = $this->left($rek,1);                             
                 break;
                case 2:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1);                                
                 break;
                case 4:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2);                               
                 break;
                case 6:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,2).'.'.substr($rek,4,2);                              
                break;
                case 8:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1).'.'.substr($rek,4,2).'.'.substr($rek,6,2);                             
                break;
                case 12:
                    $rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1).'.'.substr($rek,4,2).'.'.substr($rek,6,2).'.'.substr($rek,8,4);                             
                break;
                default:
                $rek = "";  
                }
                return $rek;
    }

    function preview_perda1(){
      $id = $this->uri->segment(3);
      $cetak = $this->uri->segment(3);
      $tgl =  strtotime($_REQUEST['tgl_ttd']);
    
      $ttd =  $_REQUEST['ttd2'];
      $keu1 = $this->keu1;  
        $chkrancang = $this->uri->segment(4);  
        if ($chkrancang==1){
            $rancang = 'Rancangan';
        }else{
            $rancang = '';
        }
 
      $n_trdrka = 'trdrka';

     $thn = '2021';
     $kab='PEMERINTAH PROVINSI KALIMANTAN BARAT';
     $daerah='Pontianak';
     

         //echo $thn;
      
      $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$keu1'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                    $tanggal = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nsp;b';
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                } 
                
        if($ttd!=''){
            $sqlsc="SELECT nama,jabatan,nip,rtrim(kode) [kode],pangkat FROM ms_ttd where (REPLACE(nip, ' ', 'a')='$ttd')";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd){
                    $nama_ttd=$rowttd->nama;
                    $jab_ttd     = $rowttd->jabatan;
                    $nip = $rowttd->nip;
                    $kode = $rowttd->kode;
                    $pangkat = $rowttd->pangkat;
                 } 
            
        }else{
                    $nama_ttd='';
                    $jab_ttd     = '';
                    $nip = ''; 
                    $kode = '';   
                    $pangkat = '';        
        }
          
      $sqlsc="SELECT judul,nomor,tanggal FROM trkonfig_anggaran where jenis_anggaran='1'  and lampiran='perda'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $row_ag)
                {
                    $ag_judul=$row_ag->judul;
                    $ag_nomor = $row_ag->nomor;
                     $ag_tanggal = $row_ag->tanggal;
                } 

        $cRet='';
        
         $cRet .="<table style=\"border-collapse:collapse;font-family: arial; font-weight:bold; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                    <td width=\"10%\" rowspan=\"3\" align=\"left\" Valign=\"top\">Lampiran I : </td>
                         <td  width=\"30%\" align=\"left\">$ag_judul</td>
                         <td  width=\"60%\" ></td>
                         
                    </tr>
                    <tr>
                         <td align=\"left\">Nomor &nbsp;&nbsp;: $ag_nomor</td>
                         <td></td>
                    </tr>
                    
                    <tr>
                         <td align=\"left\" style=\"margin-bottom:15px;border-bottom: 2px solid;display: inline-block;width: 170px; \">Tanggal : $ag_tanggal</td>
                         <td></td>
                    </tr>                                       
                                        
                  </table><br>";  
                  
        
        $cRet .="<table style=\"border-collapse:collapse;font-family: arial; font-size:16px;font-weight:bold;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                    <td width=\"15%\" rowspan=\"4\" align=\"right\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-right: none;\" ><img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" /></td>
                         <td  width=\"85%\" align=\"center\" style=\"font-size:23px;font-weight:bold;border-bottom: none;border-left: none; \" >$kab</td>
                         
                    </tr>
                    <tr>
                         <td align=\"center\" style=\"vertical-align:top;border-top: none;text-transform:uppercase;border-bottom: none;border-left: none;\" >$rancang RINGKASAN APBD</td>
                    </tr>
                    
                    <tr>
                         <td align=\"center\" style=\"vertical-align:top;border-top: none;border-bottom: none;border-left: none;\">TAHUN ANGGARAN $thn</td>
                    </tr>
                    <tr>
                         <td align=\"center\" style=\"vertical-align:top;border-bottom: none;border-top: none;border-left: none;\" > &nbsp;</td>
                    </tr>
                  </table>";        
        $cRet .= "<table style=\"border-collapse:collapse;font-family: arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
                     <thead >                       
                        <tr><td  width=\"15%\" align=\"center\"><b>NOMOR URUT</b></td>                            
                            <td width=\"65%\" align=\"center\"><b>URAIAN</b></td>
                            <td width=\"20%\" align=\"center\"><b>JUMLAH(Rp.)</b></td></tr>
                     
                     
                        <tr><td style=\"font-weight:bold;\" width=\"10%\" align=\"center\">1</td>                            
                            <td style=\"font-weight:bold;\" width=\"70%\"  align=\"center\">2</td>
                            <td style=\"font-weight:bold;\" width=\"20%\"  align=\"center\">3</td></tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <td style=\"font-size:10px;border-bottom: none;border-right: none;border-left: none;\" colspan=\"3\"><i>Lampiran I : Perda Prov.Kalimantan Barat - Ringkasan Penyusunan APBD TA $thn</i></td>

                         </tr>
                     </tfoot>
                        ";
                 $sql1="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a INNER JOIN $n_trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='4'  GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN $n_trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,1)='4' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN $n_trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,1)='4'  GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                 
                 $query = $this->db->query($sql1);
                 //$query = $this->skpd_model->getAllc();
                                                  
                foreach ($query->result() as $row)
                {
                    $coba1=$this->dotrek($row->kd_rek);
                    $coba2=$row->nm_rek;
                    $coba3= number_format($row->nilai,"2",",",".");
                   if (strlen($coba1)>3) {
                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba1 </td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba2</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba3</td></tr>";
                   } else {
                        $cRet    .= " <tr><td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba1</td>                                     
                                     <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba2</td>
                                     <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba3</td></tr>";
                   }
                }
                $sqltp="SELECT SUM(nilai) AS totp FROM $n_trdrka WHERE LEFT(kd_rek6,1)='4'";
                 $sqlp=$this->db->query($sqltp);
                 foreach ($sqlp->result() as $rowp)
                {
                   $coba4=number_format($rowp->totp,"2",",",".");
                    $cob1=$rowp->totp;
                   
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\" align=\"right\">JUMLAH PENDAPATAN</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">$coba4</td></tr>";
                    //$cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">&nbsp;</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
                 }     
                                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
                                     
                                     
                $sql2="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a INNER JOIN $n_trdrka b
                       ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='5'  GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                       UNION ALL 
                       SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN $n_trdrka b
                       ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,1)='5' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                       UNION ALL 
                       SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN $n_trdrka b
                       ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,1)='5'  GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                 
                 $query1 = $this->db->query($sql2);
                 foreach ($query1->result() as $row1)
                {
                    $coba5=$this->dotrek($row1->kd_rek);
                    $coba6=$row1->nm_rek;
                    $coba7= number_format($row1->nilai,"2",",",".");
                    if (strlen($coba5)>3) {
                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba5</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba6</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba7</td></tr>";
                    } else {
                    $cRet    .= " <tr><td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba5</td>                                     
                                     <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba6</td>
                                     <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba7</td></tr>";

                    }
                }
                
                    $sqltb="SELECT SUM(nilai) AS totb FROM $n_trdrka WHERE LEFT(kd_rek6,1)='5'";
                    $sqlb=$this->db->query($sqltb);
                 foreach ($sqlb->result() as $rowb)
                {
                   $coba8=number_format($rowb->totb,"2",",",".");
                    $cob=$rowb->totb;
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\" align=\"right\">JUMLAH BELANJA</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">$coba8</td></tr>";
                 } 
                 
                                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
                  
                  $suplus=$cob1-$cob; 
                  if ($suplus < 0){
                    $x1="(";
                    $suplus=$suplus*-1;
                    $y1=")";}
                  else {
                    $x1="";
                    $y1="";}
                 $surp=number_format($suplus,2,',','.');  
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\" align=\"right\">SURPLUS(DEFISIT)</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">$x1$surp$y1</td></tr>";
                    
                                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
                                     
                    $sql3="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a INNER JOIN $n_trdrka b
                            ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='6'  GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                            UNION ALL 
                            SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN $n_trdrka b
                            ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,2)='61' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                            UNION ALL 
                            SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN $n_trdrka b
                            ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,2)='61'  GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                 
                 $query3 = $this->db->query($sql3);
                 //$query = $this->skpd_model->getAllc();
                                                  
                foreach ($query3->result() as $row3)
                {
                    $coba9=$this->dotrek($row3->kd_rek);
                    $coba10=$row3->nm_rek;
                    $coba11= number_format($row3->nilai,"2",",",".");
                    if($coba9=='3'){
                        $coba11='';
                    }
                    if (strlen($coba9)>3) {
                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba9</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba10</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba11</td></tr>";
                    } else {
                         $cRet    .= " <tr><td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba9</td>                                     
                                     <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba10</td>
                                     <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba11</td></tr>";
                    }
                }
                $sqltpm="SELECT SUM(nilai) AS totpm FROM $n_trdrka WHERE LEFT(kd_rek6,2)='61'";
                 $sqlpm=$this->db->query($sqltpm);
                 foreach ($sqlpm->result() as $rowpm)
                {
                   $coba12=number_format($rowpm->totpm,"2",",",".");
                    $cob3=$rowpm->totpm;
                   
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\" align=\"right\">JUMLAH PENERIMAAN PEMBIAYAAN</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">$coba12</td></tr>";
                 }     
                                     
                $sql4="SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN $n_trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,2)='62' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN $n_trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,2)='62'  GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                 
                 $query4 = $this->db->query($sql4);
                 //$query = $this->skpd_model->getAllc();
                                                  
                foreach ($query4->result() as $row4)
                {
                    $coba13=$this->dotrek($row4->kd_rek);
                    $coba14=$row4->nm_rek;
                    $coba15= number_format($row4->nilai,"2",",",".");
                   if (strlen($coba13)>3) {
                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba13</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba14</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba15</td></tr>";
                   } else {
                       $cRet    .= " <tr><td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba13</td>                                     
                                     <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba14</td>
                                     <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba15</td></tr>"; 
                   }
                }
                                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
                                     
                $sqltpk="SELECT SUM(nilai) AS totpk FROM $n_trdrka WHERE LEFT(kd_rek6,2)='62'";
                 $sqlpk=$this->db->query($sqltpk);
                 foreach ($sqlpk->result() as $rowpk)
                {
                   $coba16=number_format($rowpk->totpk,"2",",",".");
                    $cob4=$rowpk->totpk;
                   
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\" align=\"right\">JUMLAH PENGELUARAN PEMBIAYAAN</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">$coba16</td></tr>";
                 }  
                                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
                  
                  $netto=$cob3-$cob4; 
                    $netto_ag=$netto;
                  if ($netto < 0){
                    $a1="(";
                    $suplus=$netto*-1;
                    $b1=")";}
                  else {
                    $a1="";
                    $b1="";}
                 //$nett=number_format($netto_ag,2,',','.');
                 $nett=$this->rp_minus($netto_ag);          
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\" align=\"right\">PEMBIAYAAN NETTO</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">$a1$nett$b1</td></tr>";

                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
                     $silpa=($cob1+$cob3)-($cob+$cob4);
                     if ($silpa < 0){
                    $c1="(";
                    $silpa=$silpa*-1;
                    $d1=")";}
                    else {
                    $c1="";
                    $d1="";}
                 $silp=number_format($silpa,2,',','.');                  
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\">SISA LEBIH PEMBIAYAAN ANGGARAN TAHUN BERKENAAN (SILPA)</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">$c1$silp$d1</td></tr>";

                if($kode=='GUB'){                    
                     $cRet .="<tr>
                                <td width=\"100%\" align=\"right\" colspan=\"3\">
                                <table width=\"100%\" border=\"0\">
                                <tr>
                                <td width=\"70%\" align=\"left\" >&nbsp;<br>&nbsp;
                                <br>&nbsp;
                                &nbsp;<br>
                                &nbsp;<br>
                                &nbsp;<br>
                                &nbsp;<br>  
                                </td>
                                <td width=\"30%\" align=\"center\" >$daerah,                    
                                <br>$jab_ttd,
                                <p>&nbsp;</p>
                                <br>
                                <br>
                                <br>
                                <br><b>$nama_ttd</b>
                                
                                </td></tr></table></td>
                             </tr>";
                 }else{
                     $cRet .="<tr>
                                <td width=\"100%\" align=\"right\" colspan=\"3\">
                                <table width=\"100%\" border=\"0\">
                                <tr>
                                <td width=\"60%\" align=\"left\" >&nbsp;<br>&nbsp;
                                <br>&nbsp;
                                &nbsp;<br>
                                &nbsp;<br>
                                &nbsp;<br>
                                &nbsp;<br>  
                                </td>
                                <td width=\"40%\" align=\"center\" >$daerah ,$tanggal                   
                                <br>$jab_ttd,
                                <p>&nbsp;</p>
                                <br>
                                <br>
                                <br>
                                <br><u><b>$nama_ttd</b></u>
                                <br>$pangkat
                                <br>Nip. $nip
                                </td></tr></table></td>
                             </tr>";                     
                 }
        $cRet    .= "</table>";
        $data['prev']= $cRet;
        $this->template->set('title', 'CETAK PERDA');    
        //$this->_mpdf('',$cRet,10,10,10,0);
       // $this->template->load('template','anggaran/rka/perda1',$data);
          

        switch($cetak){   
        case 1;
            $this->tukd_model->_mpdf('',$cRet,10,5,10,'0','','Lampiran 1','',2);
        //echo $cRet;//$this->_mpdf('',$cRet,10,10,10,0);
        break;
        case 0;
                echo ("<title>Perda Lampiran 1</title>");
                echo($cRet);
        //$this->template->load('template','anggaran/rka/perda1',$data);
        break;
        case 2;        
         header("Cache-Control: no-cache, no-store, must-revalidate");
         header("Content-Type: application/vnd.ms-excel");
         header("Content-Disposition: attachment; filename=PERDA.xls");
        $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        }
                
    }

    function preview_perkadaI(){
        $id = $this->uri->segment(3);
        $cetak = $this->uri->segment(4);
        $tgl= $_REQUEST['tgl_ttd'];
        if($tgl==''){
            $tanggal='';
        }else{
            $tanggal = $this->tanggal_format_indonesia($tgl);
        }
        $ttd2= $_REQUEST['ttd2'];
        $chkrancang = $this->uri->segment(5);
        $uppkd = $this->ppkd1;
        $sppkd = $this->ppkd;
        //$keu1 = $this->keu1;
        if ($chkrancang==1){
            $rancang = 'Rancangan';
        }else{
            $rancang = '';
        }
        
        if ($id!='all'){
            if (strlen($id)==7){
                $a = 'left(';
                $skpd = 'kd_skpd';
                $b = ',7)';
    
                $sqldns="SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_org as kd_sk,a.nm_org as nm_sk FROM ms_organisasi a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_org='$id'";
                
            }else{
                $a = 'left(';;
                $skpd = 'kd_skpd';
                $b = ',10)';
                $sqldns="SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$id'";
            }
      
          
                $sqlskpd=$this->db->query($sqldns);
                foreach ($sqlskpd->result() as $rowdns)
                    {
                       
                        $kd_urusan=$rowdns->kd_u;                    
                        $nm_urusan= $rowdns->nm_u;
                        $kd_skpd  = $rowdns->kd_sk;
                        $nm_skpd  = $rowdns->nm_sk;
                    }
        
        }
        
        if ($id!='all'){
            $sqldns="SELECT * from ms_urusan WHERE kd_urusan=left('$kd_urusan',1)";
        }else{
            $sqldns="SELECT * from ms_urusan";
        }
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                   
                    $kd_urusan=$rowdns->kd_urusan;                    
                    $nm_urusan= $rowdns->nm_urusan;
                }

        $thn = '2021';
     $kab='PEMERINTAH PROVINSI KALIMANTAN BARAT';
     $daerah='Pontianak';
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$id'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                 //   $tanggal = $this->tanggal_format_indonesia($tgl);
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }
      
      if ($ttd2!=''){  
              $sqlttd1="SELECT nama,jabatan,nip,rtrim(kode) [kode],pangkat FROM ms_ttd where (REPLACE(nip, ' ', 'a')='$ttd2')";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    
                    $nip2=empty($rowttd->nip) ? '' : 'NIP. '. $rowttd->nip ;
                    $pangkat2=empty($rowttd->pangkat) ? '' : $rowttd->pangkat;
                    $nama2= empty($rowttd->nama) ? '' : $rowttd->nama;
                    $jabatan2  = empty($rowttd->jabatan) ? '': $rowttd->jabatan;
                    $kode_ttd = $rowttd->kode;
                }
       }else{
                     
                    $nip2='' ;
                    
                    $pangkat2='';
                    $nama2= '';
                    $jabatan2  = '';   
                    $kode_ttd = '';    
       } 
    
       
               $sqlsc="SELECT judul,nomor,tanggal FROM trkonfig_anggaran where jenis_anggaran='1'  and lampiran='pergub'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $row_ag)
                {
                    $ag_judul=$row_ag->judul;
                    $ag_nomor = $row_ag->nomor;
                     $ag_tanggal = $row_ag->tanggal;
                } 
          
        
        $cRet='';
        
                 $cRet .="<table style=\"border-collapse:collapse;font-family: arial; font-weight:bold; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                    <tr>
                    <td width=\"10%\" rowspan=\"3\" align=\"left\" Valign=\"top\">Lampiran I : </td>
                         <td  width=\"30%\" align=\"left\">$ag_judul</td>
                         <td  width=\"60%\" ></td>
                         
                    </tr>
                    <tr>
                         <td align=\"left\">Nomor &nbsp;&nbsp;: $ag_nomor</td>
                         <td></td>
                    </tr>
                    
                    <tr>
                         <td align=\"left\" style=\"margin-bottom:15px;border-bottom: 2px solid;display: inline-block;width: 170px; \">Tanggal : $ag_tanggal</td>
                         <td></td>
                    </tr>                                       
                                        
                  </table><br>";  
                  
        
        $cRet .="<table style=\"border-collapse:collapse;font-family: arial; font-size:20px;font-weight:bold;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                    <td width=\"15%\" rowspan=\"4\" align=\"right\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-right: none;\" >
                    <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" /></td>
                         <td  width=\"85%\" align=\"center\" style=\"font-size:23px;font-weight:bold;border-bottom: none;border-left: none; \" >$kab</td>
                         
                    </tr>
                    <tr>
                         <td align=\"center\" style=\"vertical-align:top;border-top: none; text-transform:uppercase;border-bottom: none;border-left: none;\" >RINGKASAN $rancang PENJABARAN APBD</td>
                    </tr>
                    
                    <tr>
                         <td align=\"center\" style=\"vertical-align:top;border-top: none;border-bottom: none;border-left: none;\">TAHUN ANGGARAN $thn</td>
                    </tr>
                    <tr>
                         <td align=\"center\" style=\"vertical-align:top;border-bottom: none;border-top: none;border-left: none;\" > &nbsp;</td>
                    </tr>
                  </table>";    

        if ($id != 'all'){         
            $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"1\">
                        <tr>
                            <td width=\"25%\"><strong>Urusan Pemerintahan</stong></td>
                            <td width=\"80%\"><strong>$kd_urusan1 - $nm_urusan1</stong></td>
                        </tr>
                        <tr>
                            <td width=\"25%\"><strong>Bidang Pemerintahan</stong></td>
                            <td width=\"80%\"><strong>$kd_urusan - $nm_urusan</stong></td>
                        </tr>
                        <tr>
                            <td><strong>Unit Organisasi</stong></td>
                            <td><strong>$kd_skpd - $nm_skpd</stong></td>
                        </tr>
                    </table>";
        }
        
        $cRet .= "<table style=\"border-collapse:collapse; border-top: solid 1px black;font-size:12px;\"  width=\"100%\" align=\"center\" border=\"1\"  cellspacing=\"0\" cellpadding=\"1\">
                     <thead>                       
                        <tr><td rowspan=\"1\" bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>Nomor Urut</b></td>                            
                            <td rowspan=\"1\" bgcolor=\"#CCCCCC\" width=\"70%\" align=\"center\"><b>Uraian</b></td>
                            <td rowspan=\"1\" bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Jumlah(Rp.)</b></td>
                     </thead>
                    
                     
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">1</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"70%\" align=\"center\">2</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">3</td>
                        </tr>
                       
                        <tfoot>
                        <tr>
                            <td style=\"font-size:10px;border-bottom: none;border-right: none;border-left: none;\" colspan=\"3\"><i>Lampiran I : PerGub Prov.Kalimantan Barat - Ringkasan $rancang Penyusunan APBD TA $thn</i></td>

                         </tr>
                     </tfoot>   
                       
                        ";

            if ($id != 'all'){
                        $sql1="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='4'  and $a$skpd$b='$id' GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,1)='4' and $a$skpd$b='$id' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,1)='4' and $a$skpd$b='$id' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                 }else{
                       $sql1="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='4' GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,1)='4' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,1)='4'  GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";                    
                 }
                 
                 $query = $this->db->query($sql1);
                 //$query = $this->skpd_model->getAllc();
                
                $totp = 0;                                  
                foreach ($query->result() as $row)
                {
                    $coba1=$row->rek;   
                    $coba2=$row->nm_rek;
                    $coba3= number_format($row->nilai,"2",",",".");
                   if (strlen($coba1)>3) {
                        
                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba1</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba2</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba3</td></tr>";
                   } else {
                        if (strlen($coba1)=='1'){
                            $totp = $totp + $row->nilai;
                        }
                        $cRet    .= " <tr><td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba1</td>                                     
                                     <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba2</td>
                                     <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba3</td></tr>";
                   }
                }

                   $coba4=number_format($totp,"2",",",".");
                    $cob1=$totp;
                   
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\" align=\"right\">JUMLAH PENDAPATAN</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">$coba4</td></tr>";
                 
                 
                 $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\">&nbsp;</td>
                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
                                     
                if ($id != 'all'){                   
                        $sql2="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a INNER JOIN trdrka b
                               ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='5'  and $a$skpd$b='$id' GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                               UNION ALL 
                               SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b
                               ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,1)='5' and $a$skpd$b='$id' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                               UNION ALL 
                               SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b
                               ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,1)='5'  and $a$skpd$b='$id' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                 }else{
                     $sql2="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek1 a INNER JOIN trdrka b
                               ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='5'   GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                               UNION ALL 
                               SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b
                               ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,1)='5'  GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                               UNION ALL 
                               SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b
                               ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,1)='5' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                 }
                 
                $totb = 0;
                 $query1 = $this->db->query($sql2);
                 foreach ($query1->result() as $row1)
                {
                    $coba5=$row1->rek;
                    $coba6=$row1->nm_rek;
                    $coba7= number_format($row1->nilai,"2",",",".");
                    if (strlen($coba5)==1){
                        $totb = $totb + $row1->nilai;
                    }
                    
                    if (strlen($coba5)>3) {
                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba5</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba6</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba7</td></tr>";
                    } else {
                    $cRet    .= " <tr><td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba5</td>                                     
                                     <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba6</td>
                                     <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba7</td></tr>";

                    }
                }
                
                   $coba8=number_format($totb,"2",",",".");
                    $cob=$totb;
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\" align=\"right\">JUMLAH BELANJA</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">$coba8</td></tr>";
                 
                                     $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
                  
                  $suplus=$cob1-$cob; 
                  if ($suplus < 0){
                    $x1="(";
                    $suplus=$suplus*-1;
                    $y1=")";}
                  else {
                    $x1="";
                    $y1="";}
                 $surp=number_format($suplus,"2",",",".");  
                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\" align=\"right\">SURPLUS(DEFISIT)</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">$x1$surp$y1</td></tr>";
                    
                                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\">&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
 
                
                $cob3 = 0;
                $cob4 = 0;
                if ($id == $this->ppkd_lama or $id == $this->ppkd1_lama or $id == 'all' ){
                    if ($id != 'all'){                   
                        $sql3="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,0 AS nilai FROM ms_rek1 a INNER JOIN trdrka b
                                ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='6' and $a$skpd$b='$id' GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                                UNION ALL 
                                SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b
                                ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,2)='61' and $a$skpd$b='$id' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                                UNION ALL 
                                SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b
                                ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,2)='61' and $a$skpd$b='$id' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                     }else{
                        $sql3="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,0 AS nilai FROM ms_rek1 a INNER JOIN trdrka b
                                ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='6' GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                                UNION ALL 
                                SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b
                                ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,2)='61' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                                UNION ALL 
                                SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b
                                ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,2)='61' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                        
                     }
                     $query3 = $this->db->query($sql3);
                     //$query = $this->skpd_model->getAllc();
     
    
                  
                    $totpm = 0;                                  
                    foreach ($query3->result() as $row3)
                    {
                        $coba9=$row3->rek;
                        $coba10=$row3->nm_rek;
                        $coba11= number_format($row3->nilai,"2",",",".");
                        
                        if (strlen($coba9)==3){
                            $totpm = $totpm + $row3->nilai;
                        }
                        
                        if (strlen($coba9)==1){
                            $coba11 = '';    
                        }
                        
                        if (strlen($coba9)>3) {
                         $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba9</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba10</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba11</td></tr>";
                        } else {
                             $cRet    .= " <tr><td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba9</td>                                     
                                         <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba10</td>
                                         <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba11</td></tr>";
                        }
                    }
    
                       $coba12=number_format($totpm,"2",",",".");
                        $cob3=$totpm;
                       
                       if ($id==$uppkd or $id==$sppkd or $id='all') {
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\" align=\"right\">JUMLAH PENERIMAAN PEMBIAYAAN</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">$coba12</td></tr>";
                        }
                        
                                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\">&nbsp;</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
    
                     if ($id != 'all'){                  
                         $sql4="SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b
                                ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,2)='62' and $a$skpd$b='$id' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                                UNION ALL 
                                SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b
                                ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,2)='62' and $a$skpd$b='$id' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                     }else{
                         $sql4="SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek2 a INNER JOIN trdrka b
                                ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,2)='62' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                                UNION ALL 
                                SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai FROM ms_rek3 a INNER JOIN trdrka b
                                ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,2)='62' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";                    
                     }
                     $query4 = $this->db->query($sql4);
                     //$query = $this->skpd_model->getAllc();
                    
                    $totpk = 0;
                                                      
                    foreach ($query4->result() as $row4)
                    {
                        $coba13=$row4->rek;
                        $coba14=$row4->nm_rek;
                        $coba15= number_format($row4->nilai,"2",",",".");
                       if (strlen($coba13)==3) {
                            $totpk = $totpk + $row4->nilai;
                            $c =$coba13;
                        }else{$c='';}
                       
                       if (strlen($coba13)>3) {
                         $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba13</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba14</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba15</td></tr>";
                       } else {
                           $cRet    .= " <tr><td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$coba13</td>                                     
                                         <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"70%\">$coba14</td>
                                         <td style=\"font-weight:bold;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$coba15</td></tr>"; 
                       }
                    }
                                         
                       $coba16=number_format($totpk,"2",",",".");
                        $cob4=$totpk;
                       
                       if($id==$uppkd or $id==$sppkd or $id='all'){
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\" align=\"right\">JUMLAH PENGELUARAN PEMBIAYAAN </td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">$coba16</td></tr>";
                                         $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\">&nbsp;</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
                      }
                      
                      $netto=$cob3-$cob4; 
                        $netto_ag=$netto;
                      if ($netto < 0){
                        $a1="(";
                        $suplus=$netto*-1;
                        $b1=")";}
                      else {
                        $a1="";
                        $b1="";}
                     $nett=number_format($netto_ag,2,",",".");  
                     
    
                     if ($id==$uppkd or $id==$sppkd or $id='all'){      
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\" align=\"right\">PEMBIAYAAN NETTO </td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">$a1$nett$b1</td></tr>";
                    }
                    
              }      
                    
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">&nbsp;</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\">&nbsp;</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">&nbsp;</td></tr>";
                         $silpa=($cob1+$cob3)-($cob+$cob4);
                         if ($silpa < 0){
                        $c1="(";
                        $silpa=$silpa*-1;
                        $d1=")";}
                        else {
                        $c1="";
                        $d1="";}
                     $silp=number_format($silpa,2,",",".");                  
                        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">3.3</td>                                     
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"70%\">SISA LEBIH PEMBIAYAAN ANGGARAN TAHUN BERKENAAN (SILPA)</td>
                                         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" width=\"20%\" align=\"right\">$c1$silp$d1</td></tr>";

                
           if($kode_ttd=='GUB'){     
                $cRet .="
                            <tr>                                    
                            <td colspan=\"3\" width=\"100\"  >                          
                                <table border=\"0\" align=\"right\" >   
                                    <tr>                                
                                        <td width=\"40%\"></td>
                                        <td width=\"50%\" align=\"center\">&nbsp;$daerah, $tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.
                                            <br>$jabatan2,
                                            <p>&nbsp;</p><br><br>
                                            <br><b>$nama2</b>
                                        </td>
                                        <td width=\"10%\"></td>
                                    </tr>
                                </table>  
                            </td>
                     </tr>
                     

                     " ;          
            }else{
                $cRet .="
                            <tr>                                    
                            <td colspan=\"3\" width=\"100\"  >                          
                                <table border=\"0\" align=\"right\" >   
                                    <tr>                                
                                        <td width=\"40%\"></td>
                                        <td width=\"50%\" align=\"center\">&nbsp;$daerah, $tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                            <br>$jabatan2,
                                            <p>&nbsp;</p><br><br>
                                            <br><b>$nama2</b>
                                            <br>$pangkat2 
                                            <br>$nip2 
                                        </td>
                                        <td width=\"10%\"></td>
                                    </tr>
                                </table>  
                            </td>
                     </tr>
                     " ;                          
            }
       
        
            
        $cRet    .= "</table>";
 
 
        $data['prev']= $cRet;    
        switch($cetak) {
            case 0;  
                echo ("<title>RINGKASAN PENJABARAN APBD</title>");
                echo($cRet);
            break;
            case 1;
                $this->_mpdf('',$cRet,10,10,10,'0','','','',5);
                //$judul='',$isi='',$lMargin='',$rMargin='',$font=10,$orientasi='',$hal='',$tab='',$jdlsave='',$tMargin=''
            break;
        }
        //$this->template->load('template','master/fungsi/list_preview',$data);
        
                
    }

    function preview_perdaII($tgl,$doc,$pdf){
        $tgl= $_REQUEST['tgl_ttd'];
        if($tgl==''){
            $tanggal='';
        }else{
            $tanggal = $this->tanggal_format_indonesia($tgl);
        }
        $thn=$this->session->userdata('pcThang');

        
        
            $lampiran="Rancangan Peraturan Daerah";
            $judul="RINGKASAN APBD YANG DIKLASIFIKASI MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI";
            $lam="perda";
        
        $tbl='';
        $nomor="";
        $tgl_lam="";
        $exc=$this->db->query("SELECT * from trkonfig_anggaran where jenis_anggaran='1' and lampiran='$lam'");
        foreach($exc->result() as $abc ){
            $nomor =$abc->nomor;
            
            $tgl_lam=$abc->tanggal;
        }

        $tbl .="<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='0' cellspacing='0' cellpadding='0'>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'> LAMPIRAN II:</td>
                        <td width='40%' colspan='2' align='left' style='border-left:none'>&nbsp; $lampiran</td>
                    </tr>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'> </td>
                        <td width='10%' align='left' style='border-left:none'>&nbsp; Nomor </td>
                        <td width='30%' align='left' style='border-left:none'>: $nomor </td>
                    </tr>
                    <tr>
                        <td width='40%' style='border-right:none'></td>
                        <td width='20%' align='right' style='border:none'></td>
                        <td width='10%' align='left' style='border-left:none'>&nbsp; Tanggal </td>
                        <td width='30%' align='left' style='border-left:none'>: $tgl_lam </td>
                    </tr>
                </table>";

        $tbl .="<table style='border-collapse:collapse;font-size:14px' width='100%' align='left' border='0' cellpadding='20px'>
                    <tr>
                        <td colspan='2' align='center'><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT <br>
                            $judul <br>
                            TAHUN ANGGARAN $thn
                            </strong></td>
                    </tr>
                </table>";

        $tbl.="<table style='border-collapse:collapse;font-size:10px' width='100%' border='1' cellspacing='0' cellpadding='5'>";
        $tbl.="<thead>
                <tr>
                    <td rowspan='2' colspan='3' align='center' width='20%'><b>Kode</td>
                    <td rowspan='2' align='center' width='20%'><b>Urusan Pemerintah Daerah</td>
                    <td rowspan='2' align='center' width='10%'><b>Pendapatan</td>
                    <td colspan='5' align='center' width='40%'><b>Belanja</td>
               </tr>
               <tr>
                    <td align='center'><b>Operasi</td>
                    <td align='center'><b>Modal</td>
                    <td align='center'><b>Tak Terduga</td>
                    <td align='center'><b>Transfer</td>
                    <td align='center' ><b>Jumlah Belanja</td>
               </tr>
                </thead>
               <tr>
                    <td align='center' colspan='3'><b>1</td>
                    <td align='center'><b>2</td>
                    <td align='center'><b>3</td>
                    <td align='center'><b>4</td>
                    <td align='center'><b>5</td>
                    <td align='center'><b>6</td>
                    <td align='center'><b>7</td>
                    <td align='center'><b>8</td>                    
               </tr>

               <tr>
                    <td align='center' colspan='3'><b>&nbsp;</td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>
                    <td align='center'><b></td>                    
               </tr>

               ";
        $tot4=0; $tot51=0; $tot52=0; $tot53=0; $tot54=0; $tottot=0;
        $sql="SELECT left(kd,1) kd1, SUBSTRING(kd,3,2) kd2,* from lampiran_2_penetapan ORDER BY kd , urut";
        $exe=$this->db->query($sql);
        foreach($exe->result() as $ab){
            $kode1=$ab->kd1;
            $kode2=$ab->kd2;
            $kode =$ab->kd;
            $urai =$ab->bidurusan;
            $skpd =$ab->kd_skpd;
            $pend =$ab->pen;
            $b51  =$ab->b51;
            $b52  =$ab->b52;
            $b53  =$ab->b53;
            $b54  =$ab->b54;
            $tot  =$ab->tot;

            if($skpd!=''){
                $tot4=$tot4+$pend;
                $tot51=$tot51+$b51;
                $tot52=$tot52+$b52;
                $tot53=$tot53+$b53;
                $tot54=$tot54+$b54;
                $tottot=$tottot+$tot;
            }

            $tbl.="<tr>
                        <td align='center' width='5%'>$kode1</td>
                        <td align='center' width='5%'>$kode2</td>
                        <td align='center' width='10%'>$skpd</td>
                        <td align='left' width='20%'>$urai</td>
                        <td align='right' width='10%'>".number_format($pend,2,',','.')."</td>
                        <td align='right' width='10%'>".number_format($b51,2,',','.')."</td>
                        <td align='right' width='10%'>".number_format($b52,2,',','.')."</td>
                        <td align='right' width='10%'>".number_format($b53,2,',','.')."</td>
                        <td align='right' width='10%'>".number_format($b54,2,',','.')."</td>
                        <td align='right' width='10%'>".number_format($tot,2,',','.')."</td>                    
                   </tr>";
        }
            $tbl.="<tr>
                        <td align='center' colspan='4'>Jumlah</td>
                        <td align='right'>".number_format($tot4,2,',','.')."</td>
                        <td align='right'>".number_format($tot51,2,',','.')."</td>
                        <td align='right'>".number_format($tot52,2,',','.')."</td>
                        <td align='right'>".number_format($tot53,2,',','.')."</td>
                        <td align='right'>".number_format($tot54,2,',','.')."</td>
                        <td align='right'>".number_format($tottot,2,',','.')."</td>                    
                   </tr>";

        $tbl.="</table>";
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE  kode='GUB' ";
            $sqlttd=$this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd){
                        $nip=$rowttd->nip;  
                        $pangkat=$rowttd->pangkat;  
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
            }
            $tbl.="<table width='100%' style='border-collapse:collapse;font-size:12px'>
                        <tr>
                            <td width='50%' align='center'>

                            </td>
                            <td width='50%' align='center'>
                                <br>Pontianak, $tgl <br>
                                $jabatan 
                                <br><br>
                                <br><br>
                                <br><br>
                                <b>$nama</b><br>
                            </td>

                        </tr>
                    </table>";    
        
        if($pdf==0){
            echo $tbl;
        }else{
            $this->master_pdf->_mpdf('',$tbl,10,10,10,'1');
        }
    }
    
    function get_status2($skpd){
        $n_status = '';
        
        $sql = "select case when status_ubah='1' then 'nilai_ubah' 
                    when status_sempurna='1' then 'nilai_sempurna' 
                    when statu='1' 
                    then 'nilai' else 'nilai' end as anggaran from trhrka where kd_skpd ='$skpd'";
        
        $q_trhrka = $this->db->query($sql);
        $num_rows = $q_trhrka->num_rows();
        
        foreach ($q_trhrka->result() as $r_trhrka){
             $n_status = $r_trhrka->anggaran;                   
        }    
        return $n_status;                         
    }
   
 

    function cek_realisasi(){
        $skpd     = $this->input->post('skpd');

        $hasil=$this->db->query("   select isnull(sum(nilai),0) [nilai] from(
                                    select top 1 isnull(nilai,0) [nilai] from trhspp where kd_skpd='$skpd' and (sp2d_batal is null or sp2d_batal<>'1') union all
                                    select top 1 isnull(nilai,0) [nilai] from trdtransout where kd_skpd='$skpd' union
                                    select top 1 isnull(nilai,0) [nilai] from trdtagih where kd_skpd='$skpd'
                                    ) as a ");
        foreach ($hasil->result_array() as $row){
        $jumlah=$row['nilai']; 
        }
        if($jumlah>0){
            $msg = array('pesan'=>'1');
            echo json_encode($msg);
        } else{
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
        }
        
    }


}


