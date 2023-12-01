<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Lamp_pmk extends CI_Controller
{

    function __contruct(){
        parent::__construct();
    }
	
    function  tanggal_format_indonesia($tgl){
        $tanggal  = explode('-',$tgl); 
        $bulan  = $this-> getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2].' '.$bulan.' '.$tahun;
        }
         
        function  tanggal_indonesia($tgl){
        $tanggal  =  substr($tgl,8,2);
        $bulan  = substr($tgl,5,2);
        $tahun  =  substr($tgl,0,4);
        return  $tanggal.'-'.$bulan.'-'.$tahun;
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
        return  "April";
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
    			case 3:
					$rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1);								
       			 break;
    			case 5:
					$rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1).'.'.substr($rek,3,2);								
        		break;
    			case 7:
					$rek = $this->left($rek,1).'.'.substr($rek,1,1).'.'.substr($rek,2,1).'.'.substr($rek,3,2).'.'.substr($rek,5,2);								
        		break;
    			default:
				$rek = "";	
				}
				return $rek;
    }
	function lamp_IIA1(){
        $data['page_title']= 'CETAK LAMP PMK IIA1';
        $this->template->set('title', 'CETAK LAMP PMK IIA1');   
        $this->template->load('template','lamp_pmk/cetak_lamp_IIA1',$data) ;	
	}
	function lamp_IIA1_kasda(){
        $data['page_title']= 'CETAK LAPORAN REALISASI PENDAPATAN';
        $this->template->set('title', 'CETAK LAPORAN REALISASI PENDAPATAN');   
        $this->template->load('template','lamp_pmk/cetak_lamp_IIA1_kasda',$data) ;	
	}
	
	function cetak_real_pend($bulan='',$ctk='',$anggaran='',$kd_skpd='',$jenis='',$tglttd='',$ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "MARET";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "JUNI";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "SEPTEMBER";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "DESEMBER";
        break;
    }
	$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	//echo $ttd;
	//$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='KD'";
	$sqlsc="SELECT TOP 1 nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$ttd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama = $rowttd->nm;
                    $jabatan = $rowttd->jab;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}
		$where = "LEN(kd_rek)<='$jenis'";
		/* $cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" > &nbsp;</TD>
						<TD width="40%"  align="left" >Lampiran II.A.1<br>Peraturan Menteri Keuangan No. 04/PMK.07/2011<br>Tentang Tata Cara Penyampaian Informasi<br>Keuangan Daerah </TD>
					</TR>
					<tr>
					</TABLE><br/>';*/
	$cRet ='';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAERAH </b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN $lntahunang </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>REALISASI S/D $judul $lntahunang</b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td rowspan=\"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
                    <td colspan=\"3\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Realisasi (Rp.)</b></td>
                    <td rowspan=\"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA</b></td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Persen<br>tase</b></td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DASAR HUKUM</b></td>
				</tr>
				<tr>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>s/d Bulan Lalu</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Bulan Ini</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>s/d Bulan Ini</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6=(4+5)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" ></td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" ></td> 
				</tr>
				</thead>";
				
				$tot_nil_ang=0;
				$tot_bulan_lalu=0;
				$tot_bulan_ini=0;
				$tot_sd_bulan_ini=0;
				$sql="SELECT kd_rek,nm_rek,anggaran,bulan_lalu,bulan_ini,sd_bulan_ini FROM penerimaan_kasda($bulan,$anggaran) 
				WHERE LEFT(kd_rek,1)='4' AND LEFT(kd_rek,3)!='411' AND $where 
				UNION ALL
				SELECT kd_rek,nm_rek,anggaran,bulan_lalu,bulan_ini,sd_bulan_ini FROM penerimaan_kasda($bulan,$anggaran) 
				WHERE LEFT(kd_rek,1)='4' AND LEFT(kd_rek,3)='411' AND LEN(kd_rek)<'$jenis'
				UNION ALL
				SELECT a.kd_rek+'.'+a.kd_skpd as kd_rek, b.nm_skpd as nm_rek, anggaran, bulan_lalu, bulan_ini, sd_bulan_ini
				FROM (SELECT LEFT(kd_rek,$jenis) kd_rek, kd_skpd, SUM(anggaran) anggaran, SUM(bulan_lalu) bulan_lalu, SUM(bulan_ini) bulan_ini, SUM(sd_bulan_ini) sd_bulan_ini FROM penerimaan_kasda_unit($bulan,$anggaran) WHERE LEN(kd_rek)='7' AND LEFT(kd_rek,3)!='411' GROUP BY LEFT(kd_rek,$jenis), kd_skpd) a
				LEFT JOIN ms_skpd b ON a.kd_skpd=b.kd_skpd
				ORDER BY kd_rek";
				$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $bulan_lalu = $row->bulan_lalu;
                       $bulan_ini = $row->bulan_ini;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$nil_ang-$sd_bulan_ini;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang;
					   
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';

					   $tot_nil_ang=$tot_nil_ang+$nil_ang;
					   $tot_bulan_ini=$tot_bulan_ini+$bulan_ini;
					   $tot_bulan_lalu=$tot_bulan_lalu+$bulan_lalu;
					   $tot_sd_bulan_ini=$tot_sd_bulan_ini+$sd_bulan_ini;
					   $tot_sisa=$tot_nil_ang-$tot_sd_bulan_ini;
					   $tot_persen=$tot_sd_bulan_ini/$tot_nil_ang;
					   
					   $leng=strlen($kd_rek);
					   switch ($leng) {
					  case 1:
					   
					   $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$this->dotrek($kd_rek).'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_lalu, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
						break;
						case 2:
						$cRet .='<tr>
							   <td align="left" valign="top"><b>'.$this->dotrek($kd_rek).'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_lalu, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
						break;
						case 3:
						$cRet .='<tr>
							   <td align="left" valign="top"><b>'.$this->dotrek($kd_rek).'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_lalu, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
						break;
						case 5:
						$cRet .='<tr>
							   <td align="left" valign="top"><b>'.$this->dotrek($kd_rek).'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_lalu, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
						break;
						case 7:
						$cRet .='<tr>
							   <td align="left" valign="top">'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bulan_lalu, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							</tr>';
						break;
						default:
						$cRet .='<tr>
							   <td align="left" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;'.$this->right($kd_rek,10).'</td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bulan_lalu, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							</tr>';
						break;
					   }
					}
			$cRet .='<tr>
				   <td colspan="2" align="center"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
				   <td align="right" valign="top"><b>'.number_format($tot_nil_ang, "2", ",", ".").'</b></td> 
				   <td align="right" valign="top"><b>'.number_format($tot_bulan_lalu, "2", ",", ".").'</b></td> 
				   <td align="right" valign="top"><b>'.number_format($tot_bulan_ini, "2", ",", ".").'</b></td> 
				   <td align="right" valign="top"><b>'.number_format($tot_sd_bulan_ini, "2", ",", ".").'</b></td> 
				   <td align="right" valign="top"><b>'.number_format($tot_sisa, "2", ",", ".").'</b></td> 
				   <td align="right" valign="top"><b>'.number_format($tot_persen, "2", ",", ".").'</b></td> 
				   <td align="right" valign="top"><b></td> 
				</tr>';	
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
			
			$data['prev']= $cRet;    
            $judul='REALISASI_PENDPATAN ';
			switch ($ctk){
				case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
				case 1;
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'L');
				break;
				case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;	
			}
		}
	
	
	function lamp_IIB1(){
        $data['page_title']= 'CETAK LAMP PMK IIB1';
        $this->template->set('title', 'CETAK LAMP PMK IIB1');   
        $this->template->load('template','lamp_pmk/cetak_lamp_IIB1',$data) ;	
	}
	
	function cetak_lamp_IIA($bulan='',$ctk='',$anggaran='',$kd_skpd='',$jenis='',$tglttd='',$ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "MARET";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "JUNI";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "SEPTEMBER";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "DESEMBER";
        break;
    }
	$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='KD'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}
		$where = "LEN(kd_rek)<='$jenis'";
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" > &nbsp;</TD>
						<TD width="40%"  align="left" >Lampiran II.A.1<br>Peraturan Menteri Keuangan No. 04/PMK.07/2011<br>Tentang Tata Cara Penyampaian Informasi<br>Keuangan Daerah </TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAERAH </b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN $lntahunang </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>REALISASI S/D $judul $lntahunang</b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td rowspan=\"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
                    <td colspan=\"3\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Realisasi (Rp.)</b></td>
                    <td rowspan=\"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA</b></td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Persen<br>tase</b></td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DASAR HUKUM</b></td>
				</tr>
				<tr>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>s/d Bulan Lalu</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Bulan Ini</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>s/d Bulan Ini</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6=(4+5)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" ></td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" ></td> 
				</tr>
				</thead>";
				
				$tot_nil_ang=0;
				$tot_bulan_lalu=0;
				$tot_bulan_ini=0;
				$tot_sd_bulan_ini=0;
				$sql="SELECT kd_rek,nm_rek,anggaran,bulan_lalu,bulan_ini,sd_bulan_ini FROM realisasi_jurnal($bulan,$anggaran) WHERE LEFT(kd_rek,1)='4' AND $where ORDER BY kd_rek";
				$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $bulan_lalu = $row->bulan_lalu;
                       $bulan_ini = $row->bulan_ini;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$nil_ang-$sd_bulan_ini;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang;
					   
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';

					   
					   
					   $leng=strlen($kd_rek);
					   switch ($leng) {
					  case 1:
					   $tot_nil_ang=$tot_nil_ang+$nil_ang;
					   $tot_bulan_ini=$tot_bulan_ini+$bulan_ini;
					   $tot_bulan_lalu=$tot_bulan_lalu+$bulan_lalu;
					   $tot_sd_bulan_ini=$tot_sd_bulan_ini+$sd_bulan_ini;
					   $tot_sisa=$tot_nil_ang-$tot_sd_bulan_ini;
					   $tot_persen=$tot_sd_bulan_ini/$tot_nil_ang;
					   $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$this->dotrek($kd_rek).'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_lalu, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
						break;
						case 2:
						$cRet .='<tr>
							   <td align="left" valign="top"><b>'.$this->dotrek($kd_rek).'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_lalu, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
						break;
						case 3:
						$cRet .='<tr>
							   <td align="left" valign="top"><b>'.$this->dotrek($kd_rek).'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_lalu, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
						break;
						case 5:
						$cRet .='<tr>
							   <td align="left" valign="top"><b>'.$this->dotrek($kd_rek).'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_lalu, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
						break;
						case 7:
						$cRet .='<tr>
							   <td align="left" valign="top">'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bulan_lalu, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							</tr>';
						break;
					   }
					}
			$cRet .='<tr>
				   <td colspan="2" align="center"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
				   <td align="right" valign="top"><b>'.number_format($tot_nil_ang, "2", ",", ".").'</b></td> 
				   <td align="right" valign="top"><b>'.number_format($tot_bulan_lalu, "2", ",", ".").'</b></td> 
				   <td align="right" valign="top"><b>'.number_format($tot_bulan_ini, "2", ",", ".").'</b></td> 
				   <td align="right" valign="top"><b>'.number_format($tot_sd_bulan_ini, "2", ",", ".").'</b></td> 
				   <td align="right" valign="top"><b>'.number_format($tot_sisa, "2", ",", ".").'</b></td> 
				   <td align="right" valign="top"><b>'.number_format($tot_persen, "2", ",", ".").'</b></td> 
				   <td align="right" valign="top"><b></td> 
				</tr>';	
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
			
			$data['prev']= $cRet;    
            $judul='PMK_IIA ';
			switch ($ctk){
				case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
				case 1;
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'L');
				break;
				case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;	
			}
		}
	
	function cetak_lamp_IIA_unit($bulan='',$ctk='',$anggaran='',$kd_skpd='',$jenis='',$tglttd='', $ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "MARET";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "JUNI";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "SEPTEMBER";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "DESEMBER";
        break;
    }
		$where= "WHERE kd_skpd='$kd_skpd'";	
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='KD'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" > &nbsp;</TD>
						<TD width="40%"  align="left" >Lampiran II.A.1<br>Peraturan Menteri Keuangan No. 04/PMK.07/2011<br>Tentang Tata Cara Penyampaian Informasi<br>Keuangan Daerah </TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINCIAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH,ORGANISASI, </b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>PENDAPATAN, BELANJA DAN PEMBIAYAAN</b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN $lntahunang </b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH(BERKURANG)</b></td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DASAR HUKUM</b></td>
				</tr>
				<tr>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Rp.</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5=(4-3)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				</tr>
				</thead>";
				
				$sql="SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_pend($bulan,$anggaran) $where AND LEN(kd_rek)<='$jenis' ORDER BY kd_kegiatan,kd_rek";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_kegiatan = $row->kd_kegiatan;
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$sd_bulan_ini-$nil_ang;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';
					   $leng=strlen($kd_rek);
					   switch ($leng) {
					   case 3:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   case 5:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   case 7:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   default:
					    $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
					   break;
					   }
					}
					$hasil->free_result();  
					
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend($bulan,$anggaran) $where AND LEN(kd_rek)='3'";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$sd_bulan_ini-$nil_ang;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';

					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
					}
					$hasil->free_result(); 
					
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_objek($bulan,$anggaran) $where AND LEN(kd_rek)='3'";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   
                       $nil_ang1 = $row->anggaran;
                       $sd_bulan_ini1 = $row->sd_bulan_ini;
					   $sisa1=$sd_bulan_ini1-$nil_ang1;
					   $persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 :$sd_bulan_ini1/$nil_ang1;
					   $sisa11 = $sisa1<0 ? $sisa1*-1 :$sisa1;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';

					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa11, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
					}
					$hasil->free_result(); 
					
					$sql="SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek)<='$jenis' ORDER BY kd_kegiatan,kd_rek";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_kegiatan = $row->kd_kegiatan;
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$sd_bulan_ini-$nil_ang;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';

					   $leng=strlen($kd_rek);
					   switch ($leng) {
					   case 3:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   case 5:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   case 7:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   default:
					    $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_kegiatan.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
					   break;
					   }
					}
					$hasil->free_result();  
					$cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa11, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
			$data['prev']= $cRet;    
            $judul='PMK_I.II ';
			switch ($ctk){
				case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
				case 1;
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'L');
				break;
				case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;	
			}
		}
	
	function cetak_lamp_IIB1($bulan='',$ctk='',$tglttd='',$ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "MARET";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "JUNI";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "SEPTEMBER";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "DESEMBER";
        break;
    }
		//$where= "WHERE kd_skpd='$kd_skpd'";	
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama_ttd='';
		$pangkat_ttd='';
		$jabatan_ttd='';
		$nip_ttd='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='KD'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan_ttd = $rowttd->jabatan;
                    $pangkat_ttd = $rowttd->pangkat;
                    $nip_ttd = 'NIP. '.$ttd;
                } 
	}
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" > &nbsp;</TD>
						<TD width="40%"  align="left" >Lampiran II.B.1<br>Peraturan Menteri Keuangan No. 04/PMK.07/2011<br>Tentang Tata Cara Penyampaian Informasi Keuangan Daerah </TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REALISASI APBD </b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN BELANJA PER FUNGSI, URUSAN, ORGANISASI, DAN JENIS</b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN $lntahunang </b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"6\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BELANJA TIDAK LANGSUNG</b></td>
                    <td colspan=\"3\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BELANJA LANGSUNG</b></td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH</b></td>
				</tr>
				<tr>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PEGAWAI</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BANTUAN SOSIAL</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>HIBAH</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BAGI HASIL</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BANTUAN KEUANGAN</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>TIDAK TERDUGA</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PEGAWAI</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BARANG JASA</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>MODAL</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >10</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >12</td> 
				</tr>
				</thead>";
				
				$sql="
						SELECT a.kd_urusan1 as kode
						,a.nm_urusan1 as nama
						,ISNULL(bel_peg,0) as bel_peg
						,ISNULL(bel_bansos,0) as bel_bansos
						,ISNULL(bel_hibah,0) as bel_hibah
						,ISNULL(bel_bagi,0) as bel_bagi
						,ISNULL(bel_keu,0) as bel_keu
						,ISNULL(bel_takduga,0) as bel_takduga
						,ISNULL(bel_peg2,0) as bel_peg2
						,ISNULL(bel_brg,0) as bel_brg
						,ISNULL(bel_modal,0) as bel_modal
						FROM ms_urusan1 a
						LEFT JOIN
						( SELECT LEFT(b.kd_skpd,1) kd_skpd
						,SUM(CASE WHEN LEFT(a.map_real,3)='511' THEN (debet-kredit) ELSE 0 END) AS bel_peg
						,SUM(CASE WHEN LEFT(a.map_real,3)='515' THEN (debet-kredit) ELSE 0 END) AS bel_bansos
						,SUM(CASE WHEN LEFT(a.map_real,3)='514' THEN (debet-kredit) ELSE 0 END) AS bel_hibah
						,SUM(CASE WHEN LEFT(a.map_real,3)='516' THEN (debet-kredit) ELSE 0 END) AS bel_bagi
						,SUM(CASE WHEN LEFT(a.map_real,3)='517' THEN (debet-kredit) ELSE 0 END) AS bel_keu
						,SUM(CASE WHEN LEFT(a.map_real,3)='518' THEN (debet-kredit) ELSE 0 END) AS bel_takduga
						,SUM(CASE WHEN LEFT(a.map_real,3)='521' THEN (debet-kredit) ELSE 0 END) AS bel_peg2
						,SUM(CASE WHEN LEFT(a.map_real,3)='522' THEN (debet-kredit) ELSE 0 END) AS bel_brg
						,SUM(CASE WHEN LEFT(a.map_real,3)='523' THEN (debet-kredit) ELSE 0 END) AS bel_modal
						FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
						WHERE LEFT(a.map_real,2) IN ('51','52')  AND MONTH(tgl_voucher)<=$bulan GROUP BY LEFT(b.kd_skpd,1))b
						ON a.kd_urusan1=b.kd_skpd

						UNION ALL

						SELECT a.kd_urusan as kode
						,a.nm_urusan as nama
						,ISNULL(bel_peg,0) as bel_peg
						,ISNULL(bel_bansos,0) as bel_bansos
						,ISNULL(bel_hibah,0) as bel_hibah
						,ISNULL(bel_bagi,0) as bel_bagi
						,ISNULL(bel_keu,0) as bel_keu
						,ISNULL(bel_takduga,0) as bel_takduga
						,ISNULL(bel_peg2,0) as bel_peg2
						,ISNULL(bel_brg,0) as bel_brg
						,ISNULL(bel_modal,0) as bel_modal
						FROM ms_urusan a
						LEFT JOIN
						( SELECT LEFT(b.kd_skpd,4) kd_skpd
						,SUM(CASE WHEN LEFT(a.map_real,3)='511' THEN (debet-kredit) ELSE 0 END) AS bel_peg
						,SUM(CASE WHEN LEFT(a.map_real,3)='515' THEN (debet-kredit) ELSE 0 END) AS bel_bansos
						,SUM(CASE WHEN LEFT(a.map_real,3)='514' THEN (debet-kredit) ELSE 0 END) AS bel_hibah
						,SUM(CASE WHEN LEFT(a.map_real,3)='516' THEN (debet-kredit) ELSE 0 END) AS bel_bagi
						,SUM(CASE WHEN LEFT(a.map_real,3)='517' THEN (debet-kredit) ELSE 0 END) AS bel_keu
						,SUM(CASE WHEN LEFT(a.map_real,3)='518' THEN (debet-kredit) ELSE 0 END) AS bel_takduga
						,SUM(CASE WHEN LEFT(a.map_real,3)='521' THEN (debet-kredit) ELSE 0 END) AS bel_peg2
						,SUM(CASE WHEN LEFT(a.map_real,3)='522' THEN (debet-kredit) ELSE 0 END) AS bel_brg
						,SUM(CASE WHEN LEFT(a.map_real,3)='523' THEN (debet-kredit) ELSE 0 END) AS bel_modal
						FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
						WHERE LEFT(a.map_real,2) IN ('51','52')  AND MONTH(tgl_voucher)<=$bulan GROUP BY LEFT(b.kd_skpd,4))b
						ON a.kd_urusan=b.kd_skpd

						UNION ALL

						SELECT a.kd_org as kode
						,a.nm_org as nama
						,ISNULL(bel_peg,0) as bel_peg
						,ISNULL(bel_bansos,0) as bel_bansos
						,ISNULL(bel_hibah,0) as bel_hibah
						,ISNULL(bel_bagi,0) as bel_bagi
						,ISNULL(bel_keu,0) as bel_keu
						,ISNULL(bel_takduga,0) as bel_takduga
						,ISNULL(bel_peg2,0) as bel_peg2
						,ISNULL(bel_brg,0) as bel_brg
						,ISNULL(bel_modal,0) as bel_modal
						FROM ms_organisasi a
						LEFT JOIN
						( SELECT LEFT(b.kd_skpd,7) kd_skpd
						,SUM(CASE WHEN LEFT(a.map_real,3)='511' THEN (debet-kredit) ELSE 0 END) AS bel_peg
						,SUM(CASE WHEN LEFT(a.map_real,3)='515' THEN (debet-kredit) ELSE 0 END) AS bel_bansos
						,SUM(CASE WHEN LEFT(a.map_real,3)='514' THEN (debet-kredit) ELSE 0 END) AS bel_hibah
						,SUM(CASE WHEN LEFT(a.map_real,3)='516' THEN (debet-kredit) ELSE 0 END) AS bel_bagi
						,SUM(CASE WHEN LEFT(a.map_real,3)='517' THEN (debet-kredit) ELSE 0 END) AS bel_keu
						,SUM(CASE WHEN LEFT(a.map_real,3)='518' THEN (debet-kredit) ELSE 0 END) AS bel_takduga
						,SUM(CASE WHEN LEFT(a.map_real,3)='521' THEN (debet-kredit) ELSE 0 END) AS bel_peg2
						,SUM(CASE WHEN LEFT(a.map_real,3)='522' THEN (debet-kredit) ELSE 0 END) AS bel_brg
						,SUM(CASE WHEN LEFT(a.map_real,3)='523' THEN (debet-kredit) ELSE 0 END) AS bel_modal
						FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
						WHERE LEFT(a.map_real,2) IN ('51','52')  AND MONTH(tgl_voucher)<=$bulan GROUP BY LEFT(b.kd_skpd,7))b
						ON a.kd_org=b.kd_skpd

						UNION ALL
						SELECT a.kd_skpd as kode
						,a.nm_skpd as nama
						,ISNULL(bel_peg,0) as bel_peg
						,ISNULL(bel_bansos,0) as bel_bansos
						,ISNULL(bel_hibah,0) as bel_hibah
						,ISNULL(bel_bagi,0) as bel_bagi
						,ISNULL(bel_keu,0) as bel_keu
						,ISNULL(bel_takduga,0) as bel_takduga
						,ISNULL(bel_peg2,0) as bel_peg2
						,ISNULL(bel_brg,0) as bel_brg
						,ISNULL(bel_modal,0) as bel_modal
						FROM ms_skpd a
						LEFT JOIN
						( SELECT b.kd_skpd
						,SUM(CASE WHEN LEFT(a.map_real,3)='511' THEN (debet-kredit) ELSE 0 END) AS bel_peg
						,SUM(CASE WHEN LEFT(a.map_real,3)='515' THEN (debet-kredit) ELSE 0 END) AS bel_bansos
						,SUM(CASE WHEN LEFT(a.map_real,3)='514' THEN (debet-kredit) ELSE 0 END) AS bel_hibah
						,SUM(CASE WHEN LEFT(a.map_real,3)='516' THEN (debet-kredit) ELSE 0 END) AS bel_bagi
						,SUM(CASE WHEN LEFT(a.map_real,3)='517' THEN (debet-kredit) ELSE 0 END) AS bel_keu
						,SUM(CASE WHEN LEFT(a.map_real,3)='518' THEN (debet-kredit) ELSE 0 END) AS bel_takduga
						,SUM(CASE WHEN LEFT(a.map_real,3)='521' THEN (debet-kredit) ELSE 0 END) AS bel_peg2
						,SUM(CASE WHEN LEFT(a.map_real,3)='522' THEN (debet-kredit) ELSE 0 END) AS bel_brg
						,SUM(CASE WHEN LEFT(a.map_real,3)='523' THEN (debet-kredit) ELSE 0 END) AS bel_modal
						FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
						WHERE LEFT(a.map_real,2) IN ('51','52')  AND MONTH(tgl_voucher)<=$bulan GROUP BY b.kd_skpd)b
						ON a.kd_skpd=b.kd_skpd
						ORDER BY kode";
						
						$tot_bel_peg=0;
						$tot_bel_bansos=0;
						$tot_bel_hibah=0;
						$tot_bel_bagi=0;
						$tot_bel_keu=0;
						$tot_bel_takduga=0;
						$tot_bel_peg2=0;
						$tot_bel_brg=0;
						$tot_bel_modal=0;
						$total=0;
						
						$hasil = $this->db->query($sql);
						foreach ($hasil->result() as $row)
						{
					   $kode = $row->kode;
					   $nama = $row->nama;
					   $bel_peg = $row->bel_peg;
					   $bel_bansos = $row->bel_bansos;
					   $bel_hibah = $row->bel_hibah;
					   $bel_bagi = $row->bel_bagi;
					   $bel_keu = $row->bel_keu;
					   $bel_takduga = $row->bel_takduga;
					   $bel_peg2 = $row->bel_peg2;
					   $bel_brg = $row->bel_brg;
					   $bel_modal = $row->bel_modal;
					   $jumlah=$bel_peg+$bel_bansos+$bel_hibah+$bel_bagi+$bel_keu+$bel_takduga+$bel_peg2+$bel_brg+$bel_modal;
					   
					   $tot_bel_peg=$tot_bel_peg+$bel_peg;
					   $tot_bel_bansos=$tot_bel_bansos+$bel_bansos;
					   $tot_bel_hibah=$tot_bel_hibah+$bel_hibah;
					   $tot_bel_bagi=$tot_bel_bagi+$bel_bagi;
					   $tot_bel_keu=$tot_bel_keu+$bel_keu;
					   $tot_bel_takduga=$tot_bel_takduga+$bel_takduga;
					   $tot_bel_peg2=$tot_bel_peg2+$bel_peg2;
					   $tot_bel_brg=$tot_bel_brg+$bel_brg;
					   $total=$total+$jumlah;
					   
					   
					   $leng=strlen($kode);
					   switch ($leng) {
					   case 10:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kode.'</td> 
							   <td align="left"  valign="top">'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($bel_peg, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_bansos, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_hibah, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_bagi, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_keu, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_takduga, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_peg2, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_brg, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_modal, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($jumlah, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   default:
					    $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</td> 
							   <td align="left"  valign="top"><b>'.$nama.'</td> 
							   <td align="right" valign="top"><b>'.number_format($bel_peg, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($bel_bansos, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($bel_hibah, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($bel_bagi, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($bel_keu, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($bel_takduga, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($bel_peg2, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($bel_brg, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($bel_modal, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($jumlah, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   }
					
					}
					 $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</td> 
							   <td align="left"  valign="top"><b>'.$nama.'</td> 
							   <td align="right" valign="top"><b>'.number_format($tot_bel_peg, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($tot_bel_bansos, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($tot_bel_hibah, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($tot_bel_bagi, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($tot_bel_keu, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($tot_bel_takduga, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($tot_bel_peg2, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($tot_bel_brg, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($tot_bel_modal, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($total, "2", ",", ".").'</td> 
							</tr>';
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan_ttd<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat_ttd<br>$nip_ttd
					</td>
				</tr>
				</table>";
			$data['prev']= $cRet;    
            $judul='PMK_II.B.1 ';
			switch ($ctk){
				case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
				case 1;
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'L');
				break;
				case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;	
			}
		}
	
	function cetak_lamp_IIB1A($bulan='',$ctk='',$tglttd='',$ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "MARET";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "JUNI";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "SEPTEMBER";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "DESEMBER";
        break;
    }
		//$where= "WHERE kd_skpd='$kd_skpd'";	
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama_ttd='';
		$pangkat_ttd='';
		$jabatan_ttd='';
		$nip_ttd='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='KD'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan_ttd = $rowttd->jabatan;
                    $pangkat_ttd = $rowttd->pangkat;
                    $nip_ttd = 'NIP. '.$ttd;
                } 
	}
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" > &nbsp;</TD>
						<TD width="40%"  align="left" >Lampiran II.B.1A<br>Peraturan Menteri Keuangan No. 04/PMK.07/2011<br>Tentang Tata Cara Penyampaian Informasi Keuangan Daerah </TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REALISASI APBD </b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINCIAN BELANJA MODAL</b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN $lntahunang </b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>NO</b></td>
					<td width=\"60%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
					<td width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td>
				   </tr>
				</thead>";
			
			$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi FROM map_lra_sap WHERE seq BETWEEN '130' AND '150' ORDER BY seq
					";
					$no=0;
					$total=0;
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $no=$no+1;
					   $seq = $row->seq;
					   $kode = $row->kode;
                       $nama = $row->nama;
                       $kode1 = $row->kode1;
                       $kode2 = $row->kode2;
                       $kode3 = $row->kode3;
                       $kode4 = $row->kode4;
                       $jenis = $row->jenis;
                       $spasi = $row->spasi;
					   
					   if($kode1==''){
						$kode1="'X'";
						}
						if($kode2==''){
							$kode2="'XX'";
						}
						if($kode3==''){
							$kode3="'XXX'";
						}
						if($kode4==''){
							$kode4="'XXXXX'";
						}
					$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal($bulan,1) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4))
					";
										
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $nilai = $row->nilai;
					}
					$total=$total+$nilai;
					$cRet .='<tr>
							   <td align="left" valign="top">'.$no.'</td> 
							   <td align="left"  valign="top">'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							  
							</tr>';
					}
			$cRet .='<tr>
							   <td colspan="2" align="left" valign="top"><b>JUMLAH</b></td> 
							   <td align="right" valign="top"><b>'.number_format($total, "2", ",", ".").'</b></td> 
							  
							</tr>';
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan_ttd<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat_ttd<br>$nip_ttd
					</td>
				</tr>
				</table>";
			$data['prev']= $cRet;    
            $judul='PMK_II.B.1 ';
			switch ($ctk){
				case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
				case 1;
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'P');
				break;
				case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;	
			}
		}

	function cetak_lamp_IIB1B($bulan='',$ctk='',$tglttd='',$ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "MARET";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "JUNI";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "SEPTEMBER";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "DESEMBER";
        break;
    }
		//$where= "WHERE kd_skpd='$kd_skpd'";	
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama_ttd='';
		$pangkat_ttd='';
		$jabatan_ttd='';
		$nip_ttd='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='KD'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan_ttd = $rowttd->jabatan;
                    $pangkat_ttd = $rowttd->pangkat;
                    $nip_ttd = 'NIP. '.$ttd;
                } 
	}
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" > &nbsp;</TD>
						<TD width="40%"  align="left" >Lampiran II.B.1<br>Peraturan Menteri Keuangan No. 04/PMK.07/2011<br>Tentang Tata Cara Penyampaian Informasi Keuangan Daerah </TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REALISASI APBD </b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINCIAN BELANJA PEGAWAI TIDAK LANGSUNG</b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN $lntahunang </b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>NO</b></td>
					<td width=\"60%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
					<td width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td>
				   </tr>
				</thead>";
				$total=0;
			$sql="SELECT kd_rek,nm_rek,sd_bulan_ini FROM realisasi_jurnal($bulan,1) WHERE LEFT(kd_rek,3)='511' ORDER BY kd_rek";
				$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nilai = $row->sd_bulan_ini;
						$leng=strlen($kd_rek);
					   switch ($leng) {
						case 7:
						$total=$total+$nilai;
						$cRet .='<tr>
							   <td align="left" valign="top">'.$kd_rek.'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							</tr>';
						break;
						default:
						$cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_rek.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							</tr>';
						break;
					   }
					}
			$cRet .='<tr>
					   <td colspan="2" align="center" valign="top"><b>JUMLAH</b></td> 
					   <td align="right" valign="top"><b>'.number_format($total, "2", ",", ".").'</b></td> 
					</tr>';
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan_ttd<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat_ttd<br>$nip_ttd
					</td>
				</tr>
				</table>";
			$data['prev']= $cRet;    
            $judul='PMK_II.B.1 ';
			switch ($ctk){
				case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
				case 1;
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'P');
				break;
				case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;	
			}
		}

	function cetak_lamp_IIB1C($bulan='',$ctk='',$tglttd='',$ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "MARET";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "JUNI";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "SEPTEMBER";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "DESEMBER";
        break;
    }
		//$where= "WHERE kd_skpd='$kd_skpd'";	
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama_ttd='';
		$pangkat_ttd='';
		$jabatan_ttd='';
		$nip_ttd='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='KD'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan_ttd = $rowttd->jabatan;
                    $pangkat_ttd = $rowttd->pangkat;
                    $nip_ttd = 'NIP. '.$ttd;
                } 
	}
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" > &nbsp;</TD>
						<TD width="40%"  align="left" >Lampiran II.C.1<br>Peraturan Menteri Keuangan No. 04/PMK.07/2011<br>Tentang Tata Cara Penyampaian Informasi Keuangan Daerah </TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REALISASI APBD </b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN PEMBIAYAAN</b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN $lntahunang </b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>NO</b></td>
					<td width=\"60%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
					<td width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td>
				   </tr>
				</thead>";
				$total=0;
			$sql="SELECT kd_rek,nm_rek,sd_bulan_ini FROM realisasi_jurnal($bulan,1) WHERE LEFT(kd_rek,1)='6' ORDER BY kd_rek";
				$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nilai = $row->sd_bulan_ini;
					    $leng=strlen($kd_rek);
					   switch ($leng) {
						case 7:
						$total=$total+$nilai;
						$cRet .='<tr>
							   <td align="left" valign="top">'.$kd_rek.'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							</tr>';
						break;
						default:
						$cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_rek.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							</tr>';
						break;
					   }

					}
			$cRet .='<tr>
					   <td colspan="2" align="center" valign="top"><b>JUMLAH</b></td> 
					   <td align="right" valign="top"><b>'.number_format($total, "2", ",", ".").'</b></td> 
					</tr>';
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan_ttd<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat_ttd<br>$nip_ttd
					</td>
				</tr>
				</table>";
			$data['prev']= $cRet;    
            $judul='PMK_II.C.1 ';
			switch ($ctk){
				case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
				case 1;
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'P');
				break;
				case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;	
			}
		}
		
	function cetak_lamp_IIG($bulan='',$ctk='',$tglttd='',$ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "MARET";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "JUNI";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "SEPTEMBER";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "DESEMBER";
        break;
    }
		//$where= "WHERE kd_skpd='$kd_skpd'";	
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama_ttd='';
		$pangkat_ttd='';
		$jabatan_ttd='';
		$nip_ttd='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='KD'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan_ttd = $rowttd->jabatan;
                    $pangkat_ttd = $rowttd->pangkat;
                    $nip_ttd = 'NIP. '.$ttd;
                } 
	}
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" > &nbsp;</TD>
						<TD width="40%"  align="left" >Lampiran II.G<br>Peraturan Menteri Keuangan No. 04/PMK.07/2011<br>Tentang Tata Cara Penyampaian Informasi Keuangan Daerah </TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REALISASI APBD </b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINCIAN PERHITUNGAN FIHAK KETIGA</b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN $lntahunang </b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>NO</b></td>
					<td width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
					<td width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DIPOTONG</b></td>
					<td width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DISETOR</b></td>
					<td width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SELISIH</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td>
				   </tr>
				</thead>";
					$tot_terima=0;
					$tot_setor=0;
					$tot_sel=0;			
					$sql = "SELECT no as nomor, uraian, kd_rek FROM map_pajak ORDER BY no
					";
					$no=0;
					$total=0;
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $no=$no+1;
					   $nomor = $row->nomor;
					   $uraian = $row->uraian;
					   $kd_rek = $row->kd_rek;
                      
					   
					   if($kd_rek==''){
						$kode_rek="'X'";
						}
						
					$sql = "SELECT SUM(terima) as terima, SUM(setor) as setor , SUM(selisih) as selisih FROM data_pajak($bulan) WHERE kd_rek5 IN ($kd_rek)
					";
										
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $terima = $row->terima;
					   $setor = $row->setor;
					   $selisih = $row->selisih;
					}
					$tot_terima=$tot_terima+$terima;
					$tot_setor=$tot_setor+$setor;
					$tot_sel=$tot_sel+$selisih;
					$cRet .='<tr>
							   <td align="center" valign="top">'.$no.'</td> 
							   <td align="left"  valign="top">'.$uraian.'</td> 
							   <td align="right" valign="top">'.number_format($terima, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($setor, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($selisih, "2", ",", ".").'</td> 
							  
							</tr>';
					}
			$cRet .='<tr>
					   <td colspan="2" align="center" valign="top"><b>JUMLAH</b></td> 
					   <td align="right" valign="top"><b>'.number_format($tot_terima, "2", ",", ".").'</b></td> 
					   <td align="right" valign="top"><b>'.number_format($tot_setor, "2", ",", ".").'</b></td> 
					   <td align="right" valign="top"><b>'.number_format($tot_sel, "2", ",", ".").'</b></td> 
					</tr>';
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan_ttd<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat_ttd<br>$nip_ttd
					</td>
				</tr>
				</table>";
			$data['prev']= $cRet;    
            $judul='PMK_II.G ';
			switch ($ctk){
				case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
				case 1;
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'P');
				break;
				case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;	
			}
		}


}