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
	
	function lamp_real_pend_lo(){
        $data['page_title']= 'CETAK LAPORAN REALISASI PENDAPATAN LO';
        $this->template->set('title', 'CETAK LAPORAN REALISASI PENDAPATAN LO');   
        $this->template->load('template','lamp_pmk/cetak_real_pend_lo',$data) ;	
	}
	
	function cetak_real_pend($bulan='',$ctk='',$anggaran='',$kd_skpd='',$jenis='',$tglttd='',$ttd=''){
        $cell = $this->uri->segment(10);
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
	$ttd=str_replace("123456789"," ",$ttd);
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
	
	if($jenis==7){
		$jenis2=5;
	}else{
		$jenis2=$jenis;
	}		
		
		
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
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$cell\">
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
						FROM (SELECT LEFT(kd_rek,$jenis) kd_rek, kd_skpd, SUM(anggaran) anggaran, SUM(bulan_lalu) bulan_lalu, SUM(bulan_ini) bulan_ini, 
						SUM(sd_bulan_ini) sd_bulan_ini FROM penerimaan_kasda_unit($bulan,$anggaran) WHERE LEN(kd_rek)='7' AND LEFT(kd_rek,3)!='411' AND LEFT(kd_rek,5)!='41410' 
						GROUP BY LEFT(kd_rek,$jenis), kd_skpd) a
						LEFT JOIN ms_skpd b ON a.kd_skpd=b.kd_skpd
						UNION ALL
						SELECT a.kd_rek+'.'+a.kd_skpd as kd_rek, b.nm_skpd as nm_rek, anggaran, bulan_lalu, bulan_ini, sd_bulan_ini
						FROM (SELECT LEFT(kd_rek,$jenis2) kd_rek, kd_skpd, SUM(anggaran) anggaran, SUM(bulan_lalu) bulan_lalu, SUM(bulan_ini) bulan_ini, 
						SUM(sd_bulan_ini) sd_bulan_ini FROM penerimaan_kasda_unit($bulan,$anggaran) WHERE LEN(kd_rek)='7' AND LEFT(kd_rek,3)='411' 
						GROUP BY LEFT(kd_rek,$jenis2), kd_skpd) a
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
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   
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
					   $tot_persen=$tot_sd_bulan_ini/$tot_nil_ang*100;
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
				$this->tukd_model->_mpdf('',$cRet,10,10,10,'1');
				break;
				case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;	
			}
		}
	
	function cetak_real_pend_lo($bulan='',$ctk='',$anggaran='',$kd_skpd='',$jenis='',$tglttd='',$ttd=''){
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
	$ttd=str_replace("123456789"," ",$ttd);
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
	
	if($jenis==7){
		$jenis2=5;
	}else{
		$jenis2=$jenis;
	}		
		
		
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
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN OPERASIONAL PENDAPATAN DAERAH </b></tr>
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
						FROM (SELECT LEFT(kd_rek,$jenis) kd_rek, kd_skpd, SUM(anggaran) anggaran, SUM(bulan_lalu) bulan_lalu, SUM(bulan_ini) bulan_ini, 
						SUM(sd_bulan_ini) sd_bulan_ini FROM penerimaan_kasda_unit($bulan,$anggaran) WHERE LEN(kd_rek)='7' AND LEFT(kd_rek,3)!='411' AND LEFT(kd_rek,5)!='41410' 
						GROUP BY LEFT(kd_rek,$jenis), kd_skpd) a
						LEFT JOIN ms_skpd b ON a.kd_skpd=b.kd_skpd
						UNION ALL
						SELECT a.kd_rek+'.'+a.kd_skpd as kd_rek, b.nm_skpd as nm_rek, anggaran, bulan_lalu, bulan_ini, sd_bulan_ini
						FROM (SELECT LEFT(kd_rek,$jenis2) kd_rek, kd_skpd, SUM(anggaran) anggaran, SUM(bulan_lalu) bulan_lalu, SUM(bulan_ini) bulan_ini, 
						SUM(sd_bulan_ini) sd_bulan_ini FROM penerimaan_kasda_unit($bulan,$anggaran) WHERE LEN(kd_rek)='7' AND LEFT(kd_rek,3)='411' 
						GROUP BY LEFT(kd_rek,$jenis2), kd_skpd) a
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
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   
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
	
	
	function realisasi_belanja_sumberdana(){
        $data['page_title']= 'CETAK LRA BERSUMBERDANA';
        $this->template->set('title', 'CETAK LRA BERSUMBERDANA');   
        $this->template->load('template','lamp_pmk/realisasi_belanja_sumberdana',$data) ;	
	}
	
	function cetak_realisasi_belanja_sumberdana($bulan='',$ctk='',$anggaran='',$jns=''){
        $lntahunang = $this->session->userdata('pcThang');       
		
		switch  ($anggaran){
        case  0:
        $anggaran="sumber1_su";
		$nilai_anggaran="nsumber1_su";
		$anggaran2="sumber2_su";
		$nilai_anggaran2="nsumber2_su";
		$anggaran3="sumber3_su";
		$nilai_anggaran3="nsumber3_su";
		$anggaran4="sumber4_su";
		$nilai_anggaran4="nsumber4_su";
        break;
        case  1:
        $anggaran="sumber1_su";	
		$nilai_anggaran="nsumber1_su";
		$anggaran2="sumber2_su";
		$nilai_anggaran2="nsumber2_su";
		$anggaran3="sumber3_su";
		$nilai_anggaran3="nsumber3_su";
		$anggaran4="sumber4_su";
		$nilai_anggaran4="nsumber4_su";
        break;
        case  2:
        $anggaran="sumber1_ubah";
		$nilai_anggaran="nsumber1_ubah";
		$anggaran2="sumber2_ubah";
		$nilai_anggaran2="nsumber2_ubah";
		$anggaran3="sumber3_ubah";
		$nilai_anggaran3="nsumber3_ubah";
		$anggaran4="sumber4_ubah";
		$nilai_anggaran4="nsumber4_ubah";
        break;
		}
		
		switch  ($bulan){
        case  3:
        $judul="TRIWULAN I";
		$bulan1 = 1;
		$bulan2 = 3;
        break;
        case  6:
        $judul="TRIWULAN II";
			if($jns=='1'){
				$bulan1 = 4;
				$bulan2 = 6;
			}else{
				$bulan1 = 1;
				$bulan2 = 6;
			}
        break;
        case  9:
        $judul= "TRIWULAN III";
			if($jns=='1'){
				$bulan1 = 7;
				$bulan2 = 9;
			}else{
				$bulan1 = 1;
				$bulan2 = 9;
			}
        break;
		case  12:
        $judul= "TRIWULAN IV";
			if($jns=='1'){
				$bulan1 = 10;
				$bulan2 = 12;
			}else{
				$bulan1 = 1;
				$bulan2 = 12;
			}
        break;
		}

	$cRet ="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
					<tr>
					<td align=\"center\" colspan=\"34\"><strong>LAPORAN REALISASI PELAKSANAAN (BELANJA) APBD YANG BERSUMBER DARI DANA TRANSFER</strong></td></tr>
                    <tr><td align=\"center\" colspan=\"34\"><b>TAHUN ANGGARAN $lntahunang </b></tr>
                    <tr><td align=\"center\" colspan=\"34\"><b>$judul</b></tr>
					</TABLE>";
					
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
					<tr>
					<td align=\"left\" width=\"20%\"><strong>Provinsi/Kabupaten/Kota</strong></td>
					<td align=\"left\"><strong>: KALIMANTAN BARAT</strong></td>
					</tr>
                    <tr>
					<td align=\"left\"><b>Tahun Anggaran</b>
					<td align=\"left\"><b>: $lntahunang</b>
					</tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"5\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KR SKPD</b></td>
                    <td rowspan=\"5\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>NAMA UNIT KERJA</b></td>
                    <td rowspan=\"5\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KR BELANJA</b></td>
                    <td rowspan=\"5\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN/PROGRAM/KEGIATAN</b></td>
					<td colspan=\"30\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI PELAKSANAAN APBD YANG BERSUMBER DARI DANA TRANSFER</b></td>
				</tr>
				<tr>
					<td colspan=\"10\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DANA TRANSFER UMUM</b></td>	
					<td colspan=\"10\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DANA TRANSFER KHUSUS (DAK)</b></td>	
					<td colspan=\"5\" rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DANA INSENTIF DAERAH</b></td>	
					<td colspan=\"5\" rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DANA DESA</b></td>	
				</tr>
				
				<tr>
					<td colspan=\"5\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DBH</b></td>	
					<td colspan=\"5\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DAU</b></td>	
					<td colspan=\"5\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DAK FISIK</b></td>	
					<td colspan=\"5\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DAK NON FISIK</b></td>	
				</tr>
				
				<tr>
					<td colspan=\"3\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>	
					<td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>	
					<td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>	
					
					<td colspan=\"3\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>	
					<td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>	
					<td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>	
					
					<td colspan=\"3\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>	
					<td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>	
					<td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>	
					
					<td colspan=\"3\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>	
					<td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>	
					<td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>	
					
					<td colspan=\"3\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>	
					<td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>	
					<td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>	
					
					<td colspan=\"3\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>	
					<td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SISA ANGGARAN</b></td>	
					<td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>	
				</tr>
				
				<tr>
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PENYALURAN</b></td>	
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PENGGUNAAN</b></td>	
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>	
					
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PENYALURAN</b></td>	
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PENGGUNAAN</b></td>	
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>	
					
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PENYALURAN</b></td>	
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PENGGUNAAN</b></td>	
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>	
					
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PENYALURAN</b></td>	
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PENGGUNAAN</b></td>	
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>	
					
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PENYALURAN</b></td>	
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PENGGUNAAN</b></td>	
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>	
					
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PENYALURAN</b></td>	
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PENGGUNAAN</b></td>	
					<td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>	
				</tr>
				
				</thead>";
				
				$totang_dak_bo = 0;
				$totreal_dak_bo = 0;
				
					//Belanja Operasi	
					$sql = "--Kegiatan
							SELECT 1 as kode, a.kd_skpd, a.nm_skpd, a.kd_kegiatan, a.nm_kegiatan, dbhp,nil_dbhp,dau,nil_dau, dak, nil_dak, daknf, nil_daknf,did, nil_did FROM 
							(SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, ISNULL(sum(pad),0) [pad],ISNULL(sum(DAK),0) [dak],ISNULL(sum(DAKNF),0) [daknf],ISNULL(sum(DAU),0) [dau],ISNULL(sum(DBHP),0) [dbhp],ISNULL(sum(did),0) [did],ISNULL(sum(lain2),0) [lain2] from(
							SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, 
							SUM (CASE WHEN $anggaran = 'PAD' THEN $nilai_anggaran ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran = 'DAK FISIK' THEN $nilai_anggaran ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran = 'DAK NON FISIK' THEN $nilai_anggaran ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran = 'DAU' THEN $nilai_anggaran ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran = 'DBHP' THEN $nilai_anggaran ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran = 'DID' THEN $nilai_anggaran ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 
							WHERE LEFT(x.kd_rek64,2) in ('51') group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan 
							UNION ALL
							SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, 
							SUM (CASE WHEN $anggaran2 = 'PAD' THEN $nilai_anggaran2 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran2 = 'DAK FISIK' THEN $nilai_anggaran2 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran2 = 'DAK NON FISIK' THEN $nilai_anggaran2 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran2 = 'DAU' THEN $nilai_anggaran2 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran2 = 'DBHP' THEN $nilai_anggaran2 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran2 = 'DID' THEN $nilai_anggaran2 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran2 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran2 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 
							WHERE LEFT(x.kd_rek64,2) in ('51') group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan 
							UNION ALL
							SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, 
							SUM (CASE WHEN $anggaran3 = 'PAD' THEN $nilai_anggaran3 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran3 = 'DAK FISIK' THEN $nilai_anggaran3 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran3 = 'DAK NON FISIK' THEN $nilai_anggaran3 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran3 = 'DAU' THEN $nilai_anggaran3 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran3 = 'DBHP' THEN $nilai_anggaran3 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran3 = 'DID' THEN $nilai_anggaran3 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran3 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran3 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 
							WHERE LEFT(x.kd_rek64,2) in ('51') group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan 
							UNION ALL
							SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, 
							SUM (CASE WHEN $anggaran4 = 'PAD' THEN $nilai_anggaran4 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran4 = 'DAK FISIK' THEN $nilai_anggaran4 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran4 = 'DAK NON FISIK' THEN $nilai_anggaran4 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran4 = 'DAU' THEN $nilai_anggaran4 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran4 = 'DBHP' THEN $nilai_anggaran4 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran4 = 'DID' THEN $nilai_anggaran4 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran4 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran4 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 
							WHERE LEFT(x.kd_rek64,2) in ('51') group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan 
							) as a group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan
							)a LEFT JOIN
							(SELECT kd_skpd, kd_kegiatan, SUM(nil_pad) nil_pad, 
                             SUM(nil_dak) nil_dak,
                             SUM(nil_dau) nil_dau,
                             SUM(nil_dbhp) nil_dbhp,
                             SUM(nil_daknf) nil_daknf,
                             SUM(nil_did) nil_did
							FROM (
							SELECT a.kd_skpd, a.kd_kegiatan,
								   ISNULL(SUM(nil_pad),0) nil_pad, 
								   ISNULL(SUM(nil_dak),0) nil_dak,
								   ISNULL(SUM(nil_dau),0) nil_dau, 
								   ISNULL(SUM(nil_dbhp),0) nil_dbhp,
								   ISNULL(SUM(nil_daknf),0) nil_daknf, 
								   ISNULL(SUM(nil_did),0) nil_did
							FROM trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
											   LEFT JOIN ms_rek5 c ON a.kd_rek5=c.kd_rek5 
							WHERE jns_spp in (1,2,3,4,5,6) AND LEFT(c.kd_rek64,2)='51' AND MONTH(b.tgl_bukti) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, a.kd_kegiatan
							UNION ALL
							SELECT a.kd_skpd, a.kd_kegiatan ,
								   SUM(CASE WHEN c.sumber1_ubah='PAD' THEN (a.rupiah*-1) ELSE 0 END) nil_pad,
								   SUM(CASE WHEN c.sumber1_ubah='DAK FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_dak,
								   SUM(CASE WHEN c.sumber1_ubah='DAU' THEN (a.rupiah*-1) ELSE 0 END) nil_dau,
								   SUM(CASE WHEN c.sumber1_ubah='DBHP' THEN (a.rupiah*-1) ELSE 0 END) nil_dbhp,
								   SUM(CASE WHEN c.sumber1_ubah='DAK NON FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_daknf,
								   SUM(CASE WHEN c.sumber1_ubah='DID' THEN (a.rupiah*-1) ELSE 0 END) nil_did
							FROM trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
												LEFT JOIN trdrka c ON c.kd_skpd=a.kd_skpd AND c.kd_rek5=a.kd_rek5 and c.kd_kegiatan=a.kd_kegiatan
							WHERE b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus NOT IN ('0','3') AND LEFT(a.kd_rek5,2)='51' AND MONTH(b.tgl_sts) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, a.kd_kegiatan
							UNION ALL
							SELECT a.kd_skpd, a.kd_kegiatan ,
								   SUM(CASE WHEN c.sumber1_ubah='PAD' THEN (a.rupiah*-1) ELSE 0 END) nil_pad,
								   SUM(CASE WHEN c.sumber1_ubah='DAK FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_dak,
								   SUM(CASE WHEN c.sumber1_ubah='DAU' THEN (a.rupiah*-1) ELSE 0 END) nil_dau,
								   SUM(CASE WHEN c.sumber1_ubah='DBHP' THEN (a.rupiah*-1) ELSE 0 END) nil_dbhp,
								   SUM(CASE WHEN c.sumber1_ubah='DAK NON FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_daknf,
								   SUM(CASE WHEN c.sumber1_ubah='DID' THEN (a.rupiah*-1) ELSE 0 END) nil_did
							FROM trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
												LEFT JOIN trdrka c ON c.kd_skpd=a.kd_skpd AND c.kd_rek5=a.kd_rek5 and c.kd_kegiatan=a.kd_kegiatan
							WHERE b.jns_trans=5 and b.jns_cp in (2) AND LEFT(a.kd_rek5,2)='51' AND MONTH(b.tgl_sts) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, a.kd_kegiatan
							UNION ALL
							SELECT a.kd_skpd, a.kd_kegiatan,
								   ISNULL(SUM(nilai),0) nil_pad, 
								   0 nil_dak,
								   0 nil_dau, 
								   0 nil_dbhp,
								   0 nil_daknf, 
								   0 nil_did
							FROM trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
							LEFT JOIN ms_rek5 c ON a.kd_rek5=c.kd_rek5 
							WHERE jns_spp in (1,2,3,4,5,6,7) AND LEFT(c.kd_rek64,2)='51' AND MONTH(b.tgl_bukti) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, a.kd_kegiatan) z
							WHERE nil_pad<>0 OR nil_dak<>0 OR nil_dau<>0 OR nil_dbhp<>0 OR nil_daknf<>0 OR nil_did<>0
							GROUP BY kd_skpd, kd_kegiatan
							)b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
							where RIGHT(a.kd_kegiatan,2)<>'00' AND (dbhp<>'0' OR dau<>'0' OR dak<>'0' OR daknf<>'0' OR did<>'0')

							UNION ALL
							--Program

							SELECT 2 as kode, a.kd_skpd,a.nm_skpd, a.kd_kegiatan, nm_kegiatan, 0 dbhp,0 nil_dbhp,0 dau,0 nil_dau,0 dak,0 nil_dak, 0 daknf,0 nil_daknf,0 did,0 nil_did
							FROM (
							SELECT kd_skpd, nm_skpd, kd_kegiatan , nm_kegiatan, ISNULL(sum(pad),0) [pad],ISNULL(sum(DAK),0) [dak],ISNULL(sum(DAKNF),0) [daknf],ISNULL(sum(DAU),0) [dau],ISNULL(sum(DBHP),0) [dbhp],ISNULL(sum(did),0) [did],ISNULL(sum(lain2),0) [lain2] from(
							SELECT z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18) kd_kegiatan, y.nm_program as nm_kegiatan,
							SUM (CASE WHEN $anggaran = 'PAD' THEN $nilai_anggaran ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran = 'DAK FISIK' THEN $nilai_anggaran ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran = 'DAK NON FISIK' THEN $nilai_anggaran ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran = 'DAU' THEN $nilai_anggaran ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran = 'DBHP' THEN $nilai_anggaran ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran = 'DID' THEN $nilai_anggaran ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 LEFT JOIN trskpd y on LEFT(z.kd_kegiatan,18)=y.kd_program 
							WHERE LEFT(x.kd_rek64,2) in ('51') group by z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18), y.nm_program
							UNION ALL
							SELECT z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18) kd_kegiatan, y.nm_program as nm_kegiatan,
							SUM (CASE WHEN $anggaran2 = 'PAD' THEN $nilai_anggaran2 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran2 = 'DAK FISIK' THEN $nilai_anggaran2 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran2 = 'DAK NON FISIK' THEN $nilai_anggaran2 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran2 = 'DAU' THEN $nilai_anggaran2 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran2 = 'DBHP' THEN $nilai_anggaran2 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran2 = 'DID' THEN $nilai_anggaran2 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran2 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran2 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 LEFT JOIN trskpd y on LEFT(z.kd_kegiatan,18)=y.kd_program 
							WHERE LEFT(x.kd_rek64,2) in ('51') group by z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18), y.nm_program
							UNION ALL
							SELECT z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18) kd_kegiatan, y.nm_program as nm_kegiatan,
							SUM (CASE WHEN $anggaran3 = 'PAD' THEN $nilai_anggaran3 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran3 = 'DAK FISIK' THEN $nilai_anggaran3 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran3 = 'DAK NON FISIK' THEN $nilai_anggaran3 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran3 = 'DAU' THEN $nilai_anggaran3 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran3 = 'DBHP' THEN $nilai_anggaran3 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran3 = 'DID' THEN $nilai_anggaran3 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran3 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran3 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 LEFT JOIN trskpd y on LEFT(z.kd_kegiatan,18)=y.kd_program 
							WHERE LEFT(x.kd_rek64,2) in ('51') group by z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18), y.nm_program
							UNION ALL
							SELECT z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18) kd_kegiatan, y.nm_program as nm_kegiatan,
							SUM (CASE WHEN $anggaran4 = 'PAD' THEN $nilai_anggaran4 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran4 = 'DAK FISIK' THEN $nilai_anggaran4 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran4 = 'DAK NON FISIK' THEN $nilai_anggaran4 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran4 = 'DAU' THEN $nilai_anggaran4 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran4 = 'DBHP' THEN $nilai_anggaran4 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran4 = 'DID' THEN $nilai_anggaran4 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran4 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran4 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 LEFT JOIN trskpd y on LEFT(z.kd_kegiatan,18)=y.kd_program 
							WHERE LEFT(x.kd_rek64,2) in ('51') group by z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18), y.nm_program
							) as a group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan) a
							LEFT JOIN 
							(SELECT kd_skpd, kd_kegiatan, SUM(nil_pad) nil_pad, 
                             SUM(nil_dak) nil_dak,
                             SUM(nil_dau) nil_dau,
                             SUM(nil_dbhp) nil_dbhp,
                             SUM(nil_daknf) nil_daknf,
                             SUM(nil_did) nil_did
							FROM (
							SELECT kd_skpd, kd_kegiatan, SUM(nil_pad) nil_pad, 
                             SUM(nil_dak) nil_dak,
                             SUM(nil_dau) nil_dau,
                             SUM(nil_dbhp) nil_dbhp,
                             SUM(nil_daknf) nil_daknf,
                             SUM(nil_did) nil_did
							FROM (
							SELECT a.kd_skpd, LEFT(a.kd_kegiatan,18) kd_kegiatan,
								   ISNULL(SUM(nil_pad),0) nil_pad, 
								   ISNULL(SUM(nil_dak),0) nil_dak,
								   ISNULL(SUM(nil_dau),0) nil_dau, 
								   ISNULL(SUM(nil_dbhp),0) nil_dbhp,
								   ISNULL(SUM(nil_daknf),0) nil_daknf, 
								   ISNULL(SUM(nil_did),0) nil_did
							FROM trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
											   LEFT JOIN ms_rek5 c ON a.kd_rek5=c.kd_rek5 
							WHERE jns_spp in (1,2,3,4,5,6) AND LEFT(c.kd_rek64,2)='51' AND MONTH(b.tgl_bukti) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, LEFT(a.kd_kegiatan,18)
							UNION ALL
							SELECT a.kd_skpd, LEFT(a.kd_kegiatan,18) kd_kegiatan ,
								   SUM(CASE WHEN c.sumber1_ubah='PAD' THEN (a.rupiah*-1) ELSE 0 END) nil_pad,
								   SUM(CASE WHEN c.sumber1_ubah='DAK FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_dak,
								   SUM(CASE WHEN c.sumber1_ubah='DAU' THEN (a.rupiah*-1) ELSE 0 END) nil_dau,
								   SUM(CASE WHEN c.sumber1_ubah='DBHP' THEN (a.rupiah*-1) ELSE 0 END) nil_dbhp,
								   SUM(CASE WHEN c.sumber1_ubah='DAK NON FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_daknf,
								   SUM(CASE WHEN c.sumber1_ubah='DID' THEN (a.rupiah*-1) ELSE 0 END) nil_did
							FROM trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
												LEFT JOIN trdrka c ON c.kd_skpd=a.kd_skpd AND c.kd_rek5=a.kd_rek5 and c.kd_kegiatan=a.kd_kegiatan
							WHERE b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus NOT IN ('0','3') AND LEFT(a.kd_rek5,2)='51' AND MONTH(b.tgl_sts) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, LEFT(a.kd_kegiatan,18)
							UNION ALL
							SELECT a.kd_skpd, LEFT(a.kd_kegiatan,18) kd_kegiatan,
								   SUM(CASE WHEN c.sumber1_ubah='PAD' THEN (a.rupiah*-1) ELSE 0 END) nil_pad,
								   SUM(CASE WHEN c.sumber1_ubah='DAK FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_dak,
								   SUM(CASE WHEN c.sumber1_ubah='DAU' THEN (a.rupiah*-1) ELSE 0 END) nil_dau,
								   SUM(CASE WHEN c.sumber1_ubah='DBHP' THEN (a.rupiah*-1) ELSE 0 END) nil_dbhp,
								   SUM(CASE WHEN c.sumber1_ubah='DAK NON FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_daknf,
								   SUM(CASE WHEN c.sumber1_ubah='DID' THEN (a.rupiah*-1) ELSE 0 END) nil_did
							FROM trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
												LEFT JOIN trdrka c ON c.kd_skpd=a.kd_skpd AND c.kd_rek5=a.kd_rek5 and c.kd_kegiatan=a.kd_kegiatan
							WHERE b.jns_trans=5 and b.jns_cp in (2) AND LEFT(a.kd_rek5,2)='51' AND MONTH(b.tgl_sts) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, LEFT(a.kd_kegiatan,18)
							UNION ALL
							SELECT a.kd_skpd, LEFT(a.kd_kegiatan,18) kd_kegiatan,
								   ISNULL(SUM(nilai),0) nil_pad, 
								   0 nil_dak,
								   0 nil_dau, 
								   0 nil_dbhp,
								   0 nil_daknf, 
								   0 nil_did
							FROM trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
							LEFT JOIN ms_rek5 c ON a.kd_rek5=c.kd_rek5 
							WHERE jns_spp in (1,2,3,4,5,6,7) AND LEFT(c.kd_rek64,2)='51' AND MONTH(b.tgl_bukti) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, LEFT(a.kd_kegiatan,18)) z
							WHERE nil_pad<>0 OR nil_dak<>0 OR nil_dau<>0 OR nil_dbhp<>0 OR nil_daknf<>0 OR nil_did<>0
							GROUP BY kd_skpd, kd_kegiatan) z
							WHERE nil_pad<>0 OR nil_dak<>0 OR nil_dau<>0 OR nil_dbhp<>0 OR nil_daknf<>0 OR nil_did<>0
							GROUP BY kd_skpd, kd_kegiatan)b
							ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
							where RIGHT(a.kd_kegiatan,2)<>'00' AND (dbhp<>'0' OR dau<>'0' OR dak<>'0' OR daknf<>'0' OR did<>'0')
							ORDER BY a.kd_skpd,a.kd_kegiatan";
					
					$cRet .='<thead>
				<tr>
                    <td coslpan=\"34\" width=\"100%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Belanja Operasi</b></td>
					</tr>
					</thead>';
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					  
					   $kode = $row->kode;
					   $kd_skpd = $row->kd_skpd;
					   $nm_skpd = $row->nm_skpd;
                       $kd_kegiatan = $row->kd_kegiatan;
                       $nm_kegiatan = $row->nm_kegiatan;
                       $dbhp = $row->dbhp;
                       $nil_dbhp = $row->nil_dbhp;
                       $dau = $row->dau;
                       $nil_dau = $row->nil_dau;
                       $dak = $row->dak;
                       $nil_dak = $row->nil_dak;
					   $daknf = $row->daknf;
                       $nil_daknf = $row->nil_daknf;
					   $did = $row->did;
                       $nil_did = $row->nil_did;
					   
					   if($dbhp==0){
					   $persen_dbhp=0;
					   $sisa_dbhp=0;
					   $persen_sisa_dbhp=0;
					   }else{
						   $persen_dbhp=$nil_dbhp/$dbhp *100;
						   $sisa_dbhp=$dbhp-$nil_dbhp;
						   $persen_sisa_dbhp=$sisa_dbhp/$dbhp *100; 
					   }
					   
					   if($dau==0){
						   $persen_dau=0;
						   $sisa_dau=0;
						   $persen_sisa_dau=0;
					   }else{
						   $persen_dau=$nil_dau/$dau *100;
						   $sisa_dau=$dau-$nil_dau;
						   $persen_sisa_dau=$sisa_dau/$dau *100;
					   }
					   
					   if($dak==0){
						   $persen_dak=0;
						   $sisa_dak=0;
						   $persen_sisa_dak=0;
					   }else{
						   $persen_dak=$nil_dak/$dak *100;
						   $sisa_dak=$dak-$nil_dak;
						   $persen_sisa_dak=$sisa_dak/$dak *100;
					   }
					   
					   $totang_dak_bo=$totang_dak_bo+$dak;
					   $totreal_dak_bo=$totreal_dak_bo+$nil_dak;
					   
					   if($daknf==0){
						   $persen_daknf=0;
						   $sisa_daknf=0;
						   $persen_sisa_daknf=0;
					   }else{
						   $persen_daknf=$nil_daknf/$daknf *100;
						   $sisa_daknf=$daknf-$nil_daknf;
						   $persen_sisa_daknf=$sisa_daknf/$daknf *100;
					   }
					   
					   if($did==0){
						   $persen_did=0;
						   $sisa_did=0;
						   $persen_sisa_did=0;
					   }else{
						   $persen_did=$nil_did/$did *100;
						   $sisa_did=$did-$nil_did;
						   $persen_sisa_did=$sisa_did/$did *100;
					   }
					   
					if($kode==2){
						$cRet .='<tr>
							   <td align="left" valign="top">'.$kd_skpd.'</td> 
							   <td align="left"  valign="top">'.$nm_skpd.'</td> 
							   <td align="left" valign="top">'.$kd_kegiatan.'</td> 
							   <td align="left" valign="top">'.$nm_kegiatan.'</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td>  
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td>  
							   <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							   
							  <td align="right" valign="top"></td> 
							 <td align="right" valign="top"></td> 
							 <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							</tr>';
					}else{		
					$cRet .='<tr>
							   <td align="left" valign="top">'.$kd_skpd.'</td> 
							   <td align="left"  valign="top">'.$nm_skpd.'</td> 
							   <td align="left" valign="top">'.$kd_kegiatan.'</td> 
							   <td align="left" valign="top">'.$nm_kegiatan.'</td> 
							   <td align="right" valign="top">'.number_format($dbhp, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nil_dbhp, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($persen_dbhp, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($sisa_dbhp, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($persen_sisa_dbhp, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($dau, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nil_dau, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($persen_dau, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($sisa_dau, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($persen_sisa_dau, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($dak, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nil_dak, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($persen_dak, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($sisa_dak, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($persen_sisa_dak, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($daknf, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nil_daknf, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($persen_daknf, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($sisa_daknf, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($persen_sisa_daknf, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($did, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nil_did, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($persen_did, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($sisa_did, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($persen_sisa_did, "2", ",", ".").'</td>
							   
							  <td align="right" valign="top">'.number_format(0, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format(0, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format(0, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format(0, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format(0, "2", ",", ".").'</td>
							</tr>';
					}
				}
				
					$totang_dak_bm=0;
					$totreal_dak_bm=0;
				
					//Belanja Modal	
					$sql = "--Kegiatan
							SELECT 1 as kode, a.kd_skpd, a.nm_skpd, a.kd_kegiatan, a.nm_kegiatan, dbhp,nil_dbhp,dau,nil_dau, dak, nil_dak, daknf, nil_daknf,did, nil_did FROM 
							(SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, ISNULL(sum(pad),0) [pad],ISNULL(sum(DAK),0) [dak],ISNULL(sum(DAKNF),0) [daknf],ISNULL(sum(DAU),0) [dau],ISNULL(sum(DBHP),0) [dbhp],ISNULL(sum(did),0) [did],ISNULL(sum(lain2),0) [lain2] from(
							SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, 
							SUM (CASE WHEN $anggaran = 'PAD' THEN $nilai_anggaran ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran = 'DAK FISIK' THEN $nilai_anggaran ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran = 'DAK NON FISIK' THEN $nilai_anggaran ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran = 'DAU' THEN $nilai_anggaran ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran = 'DBHP' THEN $nilai_anggaran ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran = 'DID' THEN $nilai_anggaran ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 
							WHERE LEFT(x.kd_rek64,2) in ('52') group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan
							UNION ALL
							SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, 
							SUM (CASE WHEN $anggaran2 = 'PAD' THEN $nilai_anggaran2 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran2 = 'DAK FISIK' THEN $nilai_anggaran2 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran2 = 'DAK NON FISIK' THEN $nilai_anggaran2 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran2 = 'DAU' THEN $nilai_anggaran2 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran2 = 'DBHP' THEN $nilai_anggaran2 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran2 = 'DID' THEN $nilai_anggaran2 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran2 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran2 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 
							WHERE LEFT(x.kd_rek64,2) in ('52') group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan
							UNION ALL
							SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, 
							SUM (CASE WHEN $anggaran3 = 'PAD' THEN $nilai_anggaran3 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran3 = 'DAK FISIK' THEN $nilai_anggaran3 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran3 = 'DAK NON FISIK' THEN $nilai_anggaran3 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran3 = 'DAU' THEN $nilai_anggaran3 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran3 = 'DBHP' THEN $nilai_anggaran3 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran3 = 'DID' THEN $nilai_anggaran3 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran3 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran3 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 
							WHERE LEFT(x.kd_rek64,2) in ('52') group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan
							UNION ALL
							SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, 
							SUM (CASE WHEN $anggaran4 = 'PAD' THEN $nilai_anggaran4 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran4 = 'DAK FISIK' THEN $nilai_anggaran4 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran4 = 'DAK NON FISIK' THEN $nilai_anggaran4 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran4 = 'DAU' THEN $nilai_anggaran4 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran4 = 'DBHP' THEN $nilai_anggaran4 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran4 = 'DID' THEN $nilai_anggaran4 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran4 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran4 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 
							WHERE LEFT(x.kd_rek64,2) in ('52') group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan
							) as a group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan
							)a LEFT JOIN
							(SELECT kd_skpd, kd_kegiatan, SUM(nil_pad) nil_pad, 
                             SUM(nil_dak) nil_dak,
                             SUM(nil_dau) nil_dau,
                             SUM(nil_dbhp) nil_dbhp,
                             SUM(nil_daknf) nil_daknf,
                             SUM(nil_did) nil_did
							FROM (
							SELECT a.kd_skpd, a.kd_kegiatan,
								   ISNULL(SUM(nil_pad),0) nil_pad, 
								   ISNULL(SUM(nil_dak),0) nil_dak,
								   ISNULL(SUM(nil_dau),0) nil_dau, 
								   ISNULL(SUM(nil_dbhp),0) nil_dbhp,
								   ISNULL(SUM(nil_daknf),0) nil_daknf, 
								   ISNULL(SUM(nil_did),0) nil_did
							FROM trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
											   LEFT JOIN ms_rek5 c ON a.kd_rek5=c.kd_rek5 
							WHERE jns_spp in (1,2,3,4,5,6) AND LEFT(c.kd_rek64,2)='52' AND MONTH(b.tgl_bukti) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, a.kd_kegiatan
							UNION ALL
							SELECT a.kd_skpd, a.kd_kegiatan ,
								   SUM(CASE WHEN c.sumber1_ubah='PAD' THEN (a.rupiah*-1) ELSE 0 END) nil_pad,
								   SUM(CASE WHEN c.sumber1_ubah='DAK FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_dak,
								   SUM(CASE WHEN c.sumber1_ubah='DAU' THEN (a.rupiah*-1) ELSE 0 END) nil_dau,
								   SUM(CASE WHEN c.sumber1_ubah='DBHP' THEN (a.rupiah*-1) ELSE 0 END) nil_dbhp,
								   SUM(CASE WHEN c.sumber1_ubah='DAK NON FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_daknf,
								   SUM(CASE WHEN c.sumber1_ubah='DID' THEN (a.rupiah*-1) ELSE 0 END) nil_did
							FROM trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
												LEFT JOIN trdrka c ON c.kd_skpd=a.kd_skpd AND c.kd_rek5=a.kd_rek5 and c.kd_kegiatan=a.kd_kegiatan
							WHERE b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus NOT IN ('0','3') AND LEFT(a.kd_rek5,2)='52' AND MONTH(b.tgl_sts) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, a.kd_kegiatan
							UNION ALL
							SELECT a.kd_skpd, a.kd_kegiatan ,
								   SUM(CASE WHEN c.sumber1_ubah='PAD' THEN (a.rupiah*-1) ELSE 0 END) nil_pad,
								   SUM(CASE WHEN c.sumber1_ubah='DAK FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_dak,
								   SUM(CASE WHEN c.sumber1_ubah='DAU' THEN (a.rupiah*-1) ELSE 0 END) nil_dau,
								   SUM(CASE WHEN c.sumber1_ubah='DBHP' THEN (a.rupiah*-1) ELSE 0 END) nil_dbhp,
								   SUM(CASE WHEN c.sumber1_ubah='DAK NON FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_daknf,
								   SUM(CASE WHEN c.sumber1_ubah='DID' THEN (a.rupiah*-1) ELSE 0 END) nil_did
							FROM trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
												LEFT JOIN trdrka c ON c.kd_skpd=a.kd_skpd AND c.kd_rek5=a.kd_rek5 and c.kd_kegiatan=a.kd_kegiatan
							WHERE b.jns_trans=5 and b.jns_cp in (2) AND LEFT(a.kd_rek5,2)='52' AND MONTH(b.tgl_sts) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, a.kd_kegiatan
							UNION ALL
							SELECT a.kd_skpd, a.kd_kegiatan,
								   ISNULL(SUM(nilai),0) nil_pad, 
								   0 nil_dak,
								   0 nil_dau, 
								   0 nil_dbhp,
								   0 nil_daknf, 
								   0 nil_did
							FROM trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
							LEFT JOIN ms_rek5 c ON a.kd_rek5=c.kd_rek5 
							WHERE jns_spp in (1,2,3,4,5,6,7) AND LEFT(c.kd_rek64,2)='52' AND MONTH(b.tgl_bukti) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, a.kd_kegiatan) z
							WHERE nil_pad<>0 OR nil_dak<>0 OR nil_dau<>0 OR nil_dbhp<>0 OR nil_daknf<>0 OR nil_did<>0
							GROUP BY kd_skpd, kd_kegiatan
							)b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
							where RIGHT(a.kd_kegiatan,2)<>'00' AND (dbhp<>'0' OR dau<>'0' OR dak<>'0' OR daknf<>'0' OR did<>'0')

							UNION ALL
							--Program

							SELECT 2 as kode, a.kd_skpd,a.nm_skpd, a.kd_kegiatan, nm_kegiatan, 0 dbhp,0 nil_dbhp,0 dau,0 nil_dau, 0 dak, 0 nil_dak, 0 daknf, 0 nil_daknf,0 did,0 nil_did
							FROM (
							SELECT kd_skpd, nm_skpd, kd_kegiatan , nm_kegiatan, ISNULL(sum(pad),0) [pad],ISNULL(sum(DAK),0) [dak],ISNULL(sum(DAKNF),0) [daknf],ISNULL(sum(DAU),0) [dau],ISNULL(sum(DBHP),0) [dbhp],ISNULL(sum(did),0) [did],ISNULL(sum(lain2),0) [lain2] from(
							SELECT z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18) kd_kegiatan, y.nm_program as nm_kegiatan,
							SUM (CASE WHEN $anggaran = 'PAD' THEN $nilai_anggaran ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran = 'DAK FISIK' THEN $nilai_anggaran ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran = 'DAK NON FISIK' THEN $nilai_anggaran ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran = 'DAU' THEN $nilai_anggaran ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran = 'DBHP' THEN $nilai_anggaran ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran = 'DID' THEN $nilai_anggaran ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 LEFT JOIN trskpd y on LEFT(z.kd_kegiatan,18)=y.kd_program 
							WHERE LEFT(x.kd_rek64,2) in ('52') group by z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18), y.nm_program
							UNION ALL
							SELECT z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18) kd_kegiatan, y.nm_program as nm_kegiatan,
							SUM (CASE WHEN $anggaran2 = 'PAD' THEN $nilai_anggaran2 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran2 = 'DAK FISIK' THEN $nilai_anggaran2 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran2 = 'DAK NON FISIK' THEN $nilai_anggaran2 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran2 = 'DAU' THEN $nilai_anggaran2 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran2 = 'DBHP' THEN $nilai_anggaran2 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran2 = 'DID' THEN $nilai_anggaran2 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran2 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran2 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 LEFT JOIN trskpd y on LEFT(z.kd_kegiatan,18)=y.kd_program 
							WHERE LEFT(x.kd_rek64,2) in ('52') group by z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18), y.nm_program
							UNION ALL
							SELECT z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18) kd_kegiatan, y.nm_program as nm_kegiatan,
							SUM (CASE WHEN $anggaran3 = 'PAD' THEN $nilai_anggaran3 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran3 = 'DAK FISIK' THEN $nilai_anggaran3 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran3 = 'DAK NON FISIK' THEN $nilai_anggaran3 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran3 = 'DAU' THEN $nilai_anggaran3 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran3 = 'DBHP' THEN $nilai_anggaran3 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran3 = 'DID' THEN $nilai_anggaran3 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran3 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran3 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 LEFT JOIN trskpd y on LEFT(z.kd_kegiatan,18)=y.kd_program 
							WHERE LEFT(x.kd_rek64,2) in ('52') group by z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18), y.nm_program
							UNION ALL
							SELECT z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18) kd_kegiatan, y.nm_program as nm_kegiatan,
							SUM (CASE WHEN $anggaran4 = 'PAD' THEN $nilai_anggaran4 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran4 = 'DAK FISIK' THEN $nilai_anggaran4 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran4 = 'DAK NON FISIK' THEN $nilai_anggaran4 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran4 = 'DAU' THEN $nilai_anggaran4 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran4 = 'DBHP' THEN $nilai_anggaran4 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran4 = 'DID' THEN $nilai_anggaran4 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran4 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran4 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 LEFT JOIN trskpd y on LEFT(z.kd_kegiatan,18)=y.kd_program 
							WHERE LEFT(x.kd_rek64,2) in ('52') group by z.kd_skpd, z.nm_skpd, LEFT(z.kd_kegiatan,18), y.nm_program
							) as a group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan) a
							LEFT JOIN 
							(SELECT kd_skpd, kd_kegiatan, SUM(nil_pad) nil_pad, 
                             SUM(nil_dak) nil_dak,
                             SUM(nil_dau) nil_dau,
                             SUM(nil_dbhp) nil_dbhp,
                             SUM(nil_daknf) nil_daknf,
                             SUM(nil_did) nil_did
							FROM (
							SELECT kd_skpd, kd_kegiatan, SUM(nil_pad) nil_pad, 
                             SUM(nil_dak) nil_dak,
                             SUM(nil_dau) nil_dau,
                             SUM(nil_dbhp) nil_dbhp,
                             SUM(nil_daknf) nil_daknf,
                             SUM(nil_did) nil_did
							FROM (
							SELECT a.kd_skpd, LEFT(a.kd_kegiatan,18) kd_kegiatan,
								   ISNULL(SUM(nil_pad),0) nil_pad, 
								   ISNULL(SUM(nil_dak),0) nil_dak,
								   ISNULL(SUM(nil_dau),0) nil_dau, 
								   ISNULL(SUM(nil_dbhp),0) nil_dbhp,
								   ISNULL(SUM(nil_daknf),0) nil_daknf, 
								   ISNULL(SUM(nil_did),0) nil_did
							FROM trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
											   LEFT JOIN ms_rek5 c ON a.kd_rek5=c.kd_rek5 
							WHERE jns_spp in (1,2,3,4,5,6) AND LEFT(c.kd_rek64,2)='52' AND MONTH(b.tgl_bukti) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, LEFT(a.kd_kegiatan,18)
							UNION ALL
							SELECT a.kd_skpd, LEFT(a.kd_kegiatan,18) kd_kegiatan ,
								   SUM(CASE WHEN c.sumber1_ubah='PAD' THEN (a.rupiah*-1) ELSE 0 END) nil_pad,
								   SUM(CASE WHEN c.sumber1_ubah='DAK FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_dak,
								   SUM(CASE WHEN c.sumber1_ubah='DAU' THEN (a.rupiah*-1) ELSE 0 END) nil_dau,
								   SUM(CASE WHEN c.sumber1_ubah='DBHP' THEN (a.rupiah*-1) ELSE 0 END) nil_dbhp,
								   SUM(CASE WHEN c.sumber1_ubah='DAK NON FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_daknf,
								   SUM(CASE WHEN c.sumber1_ubah='DID' THEN (a.rupiah*-1) ELSE 0 END) nil_did
							FROM trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
												LEFT JOIN trdrka c ON c.kd_skpd=a.kd_skpd AND c.kd_rek5=a.kd_rek5 and c.kd_kegiatan=a.kd_kegiatan
							WHERE b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus NOT IN ('0','3') AND LEFT(a.kd_rek5,2)='52' AND MONTH(b.tgl_sts) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, LEFT(a.kd_kegiatan,18)
							UNION ALL
							SELECT a.kd_skpd, LEFT(a.kd_kegiatan,18) kd_kegiatan,
								   SUM(CASE WHEN c.sumber1_ubah='PAD' THEN (a.rupiah*-1) ELSE 0 END) nil_pad,
								   SUM(CASE WHEN c.sumber1_ubah='DAK FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_dak,
								   SUM(CASE WHEN c.sumber1_ubah='DAU' THEN (a.rupiah*-1) ELSE 0 END) nil_dau,
								   SUM(CASE WHEN c.sumber1_ubah='DBHP' THEN (a.rupiah*-1) ELSE 0 END) nil_dbhp,
								   SUM(CASE WHEN c.sumber1_ubah='DAK NON FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_daknf,
								   SUM(CASE WHEN c.sumber1_ubah='DID' THEN (a.rupiah*-1) ELSE 0 END) nil_did
							FROM trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
												LEFT JOIN trdrka c ON c.kd_skpd=a.kd_skpd AND c.kd_rek5=a.kd_rek5 and c.kd_kegiatan=a.kd_kegiatan
							WHERE b.jns_trans=5 and b.jns_cp in (2) AND LEFT(a.kd_rek5,2)='52' AND MONTH(b.tgl_sts) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, LEFT(a.kd_kegiatan,18)
							UNION ALL
							SELECT a.kd_skpd, LEFT(a.kd_kegiatan,18) kd_kegiatan,
								   ISNULL(SUM(nilai),0) nil_pad, 
								   0 nil_dak,
								   0 nil_dau, 
								   0 nil_dbhp,
								   0 nil_daknf, 
								   0 nil_did
							FROM trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
							LEFT JOIN ms_rek5 c ON a.kd_rek5=c.kd_rek5 
							WHERE jns_spp in (1,2,3,4,5,6,7) AND LEFT(c.kd_rek64,2)='52' AND MONTH(b.tgl_bukti) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, LEFT(a.kd_kegiatan,18)) z
							WHERE nil_pad<>0 OR nil_dak<>0 OR nil_dau<>0 OR nil_dbhp<>0 OR nil_daknf<>0 OR nil_did<>0
							GROUP BY kd_skpd, kd_kegiatan) z
							WHERE nil_pad<>0 OR nil_dak<>0 OR nil_dau<>0 OR nil_dbhp<>0 OR nil_daknf<>0 OR nil_did<>0
							GROUP BY kd_skpd, kd_kegiatan)b
							ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
							where RIGHT(a.kd_kegiatan,2)<>'00' AND (dbhp<>'0' OR dau<>'0' OR dak<>'0' OR daknf<>'0' OR did<>'0')
							ORDER BY a.kd_skpd,a.kd_kegiatan";
					
					$cRet .='<thead>
				<tr>
                    <td coslpan=\"34\" width=\"100%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Belanja Modal</b></td>
					</tr>
					</thead>';
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					  
					   $kode2 = $row->kode;
					   $kd_skpd2 = $row->kd_skpd;
					   $nm_skpd2 = $row->nm_skpd;
                       $kd_kegiatan2 = $row->kd_kegiatan;
                       $nm_kegiatan2 = $row->nm_kegiatan;
                       $dbhp2 = $row->dbhp;
                       $nil_dbhp2 = $row->nil_dbhp;
                       $dau2 = $row->dau;
                       $nil_dau2 = $row->nil_dau;
                       $dak2 = $row->dak;
                       $nil_dak2 = $row->nil_dak;
					   $daknf2 = $row->daknf;
                       $nil_daknf2 = $row->nil_daknf;
					   $did2 = $row->did;
                       $nil_did2 = $row->nil_did;
					   
					   if($dbhp2==0){
					   $persen_dbhp2=0;
					   $sisa_dbhp2=0;
					   $persen_sisa_dbhp2=0;
					   }else{
						   $persen_dbhp2=$nil_dbhp2/$dbhp2 *100;
						   $sisa_dbhp2=$dbhp2-$nil_dbhp2;
						   $persen_sisa_dbhp2=$sisa_dbhp2/$dbhp2 *100; 
					   }
					   
					   if($dau2==0){
						   $persen_dau2=0;
						   $sisa_dau2=0;
						   $persen_sisa_dau2=0;
					   }else{
						   $persen_dau2=$nil_dau2/$dau2 *100;
						   $sisa_dau2=$dau2-$nil_dau2;
						   $persen_sisa_dau2=$sisa_dau2/$dau2 *100;
					   }
					   
					   if($dak2==0){
						   $persen_dak2=0;
						   $sisa_dak2=0;
						   $persen_sisa_dak2=0;
					   }else{
						   $persen_dak2=$nil_dak2/$dak2 *100;
						   $sisa_dak2=$dak2-$nil_dak2;
						   $persen_sisa_dak2=$sisa_dak2/$dak2 *100;
					   }
					   
					   $totang_dak_bm=$totang_dak_bm+$dak2;
					   $totreal_dak_bm=$totreal_dak_bm+$nil_dak2;
					   
					   if($daknf2==0){
						   $persen_daknf2=0;
						   $sisa_daknf2=0;
						   $persen_sisa_daknf2=0;
					   }else{
						   $persen_daknf2=$nil_daknf2/$daknf2 *100;
						   $sisa_daknf2=$daknf2-$nil_daknf2;
						   $persen_sisa_daknf2=$sisa_daknf2/$daknf2 *100;
					   }
					   
					   if($did2==0){
						   $persen_did2=0;
						   $sisa_did2=0;
						   $persen_sisa_did2=0;
					   }else{
						   $persen_did2=$nil_did2/$did2 *100;
						   $sisa_did2=$did2-$nil_did2;
						   $persen_sisa_did2=$sisa_did2/$did2 *100;
					   }
					   
					if($kode2==2){
						$cRet .='<tr>
							   <td align="left" valign="top">'.$kd_skpd2.'</td> 
							   <td align="left"  valign="top">'.$nm_skpd2.'</td> 
							   <td align="left" valign="top">'.$kd_kegiatan2.'</td> 
							   <td align="left" valign="top">'.$nm_kegiatan2.'</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td>  
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td>  
							   <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							   
							  <td align="right" valign="top"></td> 
							 <td align="right" valign="top"></td> 
							 <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							  <td align="right" valign="top"></td> 
							</tr>';
					}else{		
					$cRet .='<tr>
							   <td align="left" valign="top">'.$kd_skpd2.'</td> 
							   <td align="left"  valign="top">'.$nm_skpd2.'</td> 
							   <td align="left" valign="top">'.$kd_kegiatan2.'</td> 
							   <td align="left" valign="top">'.$nm_kegiatan2.'</td> 
							   <td align="right" valign="top">'.number_format($dbhp2, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nil_dbhp2, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($persen_dbhp2, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($sisa_dbhp2, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($persen_sisa_dbhp2, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($dau2, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nil_dau2, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($persen_dau2, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($sisa_dau2, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($persen_sisa_dau, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($dak2, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nil_dak2, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($persen_dak2, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($sisa_dak2, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($persen_sisa_dak2, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($daknf2, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nil_daknf2, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($persen_daknf2, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($sisa_daknf2, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($persen_sisa_daknf2, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($did2, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nil_did2, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($persen_did2, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($sisa_did2, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($persen_sisa_did2, "2", ",", ".").'</td>
							   
							  <td align="right" valign="top">'.number_format(0, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format(0, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format(0, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format(0, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format(0, "2", ",", ".").'</td>
							</tr>';
					}
					  
				}
				
				//Total Belanja Operasi dan MODAL
				
				$sql = "SELECT SUM(dbhp) tot_dbhp,SUM(nil_dbhp) tot_nil_dbhp,SUM(dau) tot_dau,SUM(nil_dau) tot_nil_dau, SUM(dak) tot_dak, SUM(nil_dak) tot_nil_dak, SUM(daknf) tot_daknf, SUM(nil_daknf) tot_nil_daknf, SUM(did) tot_did, SUM(nil_did) tot_nil_did FROM(
						SELECT 1 as kode, a.kd_skpd, a.nm_skpd, a.kd_kegiatan, a.nm_kegiatan, dbhp,nil_dbhp,dau,nil_dau, dak, nil_dak, daknf, nil_daknf,did, nil_did FROM 
							(SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, ISNULL(sum(pad),0) [pad],ISNULL(sum(DAK),0) [dak],ISNULL(sum(DAKNF),0) [daknf],ISNULL(sum(DAU),0) [dau],ISNULL(sum(DBHP),0) [dbhp],ISNULL(sum(did),0) [did],ISNULL(sum(lain2),0) [lain2] from(
							SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, 
							SUM (CASE WHEN $anggaran = 'PAD' THEN $nilai_anggaran ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran = 'DAK FISIK' THEN $nilai_anggaran ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran = 'DAK NON FISIK' THEN $nilai_anggaran ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran = 'DAU' THEN $nilai_anggaran ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran = 'DBHP' THEN $nilai_anggaran ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran = 'DID' THEN $nilai_anggaran ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 
							WHERE LEFT(x.kd_rek64,2) in ('51','52') group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan 
							UNION ALL
							SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, 
							SUM (CASE WHEN $anggaran2 = 'PAD' THEN $nilai_anggaran2 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran2 = 'DAK FISIK' THEN $nilai_anggaran2 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran2 = 'DAK NON FISIK' THEN $nilai_anggaran2 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran2 = 'DAU' THEN $nilai_anggaran2 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran2 = 'DBHP' THEN $nilai_anggaran2 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran2 = 'DID' THEN $nilai_anggaran2 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran2 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran2 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 
							WHERE LEFT(x.kd_rek64,2) in ('51','52') group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan
							UNION ALL
							SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, 
							SUM (CASE WHEN $anggaran3 = 'PAD' THEN $nilai_anggaran3 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran3 = 'DAK FISIK' THEN $nilai_anggaran3 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran3 = 'DAK NON FISIK' THEN $nilai_anggaran3 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran3 = 'DAU' THEN $nilai_anggaran3 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran3 = 'DBHP' THEN $nilai_anggaran3 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran3 = 'DID' THEN $nilai_anggaran3 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran3 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran3 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 
							WHERE LEFT(x.kd_rek64,2) in ('51','52') group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan 
							UNION ALL
							SELECT kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan, 
							SUM (CASE WHEN $anggaran4 = 'PAD' THEN $nilai_anggaran4 ELSE 0 END) AS PAD,
							SUM (CASE WHEN $anggaran4 = 'DAK FISIK' THEN $nilai_anggaran4 ELSE 0 END) AS DAK,
							SUM (CASE WHEN $anggaran4 = 'DAK NON FISIK' THEN $nilai_anggaran4 ELSE 0 END) AS DAKNF,
							SUM (CASE WHEN $anggaran4 = 'DAU' THEN $nilai_anggaran4 ELSE 0 END) AS DAU,
							SUM (CASE WHEN $anggaran4 = 'DBHP' THEN $nilai_anggaran4 ELSE 0 END) AS DBHP,
							SUM (CASE WHEN $anggaran4 = 'DID' THEN $nilai_anggaran4 ELSE 0 END) AS DID,
							SUM (CASE WHEN $anggaran4 not in ('PAD','DAK FISIK','DAK NON FISIK','DAU','DBHP','DID') THEN $nilai_anggaran4 ELSE 0 END) AS Lain2
							from trdrka z LEFT JOIN ms_rek5 x on z.kd_rek5=x.kd_rek5 
							WHERE LEFT(x.kd_rek64,2) in ('51','52') group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan 
							) as a group by kd_skpd, nm_skpd, kd_kegiatan, nm_kegiatan
							)a LEFT JOIN
							(SELECT kd_skpd, kd_kegiatan, SUM(nil_pad) nil_pad, 
                             SUM(nil_dak) nil_dak,
                             SUM(nil_dau) nil_dau,
                             SUM(nil_dbhp) nil_dbhp,
                             SUM(nil_daknf) nil_daknf,
                             SUM(nil_did) nil_did
							FROM (
							SELECT a.kd_skpd, a.kd_kegiatan,
								   ISNULL(SUM(nil_pad),0) nil_pad, 
								   ISNULL(SUM(nil_dak),0) nil_dak,
								   ISNULL(SUM(nil_dau),0) nil_dau, 
								   ISNULL(SUM(nil_dbhp),0) nil_dbhp,
								   ISNULL(SUM(nil_daknf),0) nil_daknf, 
								   ISNULL(SUM(nil_did),0) nil_did
							FROM trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
											   LEFT JOIN ms_rek5 c ON a.kd_rek5=c.kd_rek5 
							WHERE jns_spp in (1,2,3,4,5,6) AND LEFT(c.kd_rek64,2) IN ('51','52') AND MONTH(b.tgl_bukti) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, a.kd_kegiatan
							UNION ALL
							SELECT a.kd_skpd, a.kd_kegiatan ,
								   SUM(CASE WHEN c.sumber1_ubah='PAD' THEN (a.rupiah*-1) ELSE 0 END) nil_pad,
								   SUM(CASE WHEN c.sumber1_ubah='DAK FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_dak,
								   SUM(CASE WHEN c.sumber1_ubah='DAU' THEN (a.rupiah*-1) ELSE 0 END) nil_dau,
								   SUM(CASE WHEN c.sumber1_ubah='DBHP' THEN (a.rupiah*-1) ELSE 0 END) nil_dbhp,
								   SUM(CASE WHEN c.sumber1_ubah='DAK NON FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_daknf,
								   SUM(CASE WHEN c.sumber1_ubah='DID' THEN (a.rupiah*-1) ELSE 0 END) nil_did
							FROM trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
												LEFT JOIN trdrka c ON c.kd_skpd=a.kd_skpd AND c.kd_rek5=a.kd_rek5 and c.kd_kegiatan=a.kd_kegiatan
							WHERE b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus NOT IN ('0','3') AND LEFT(a.kd_rek5,2) IN ('51','52') AND MONTH(b.tgl_sts) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, a.kd_kegiatan
							UNION ALL
							SELECT a.kd_skpd, a.kd_kegiatan ,
								   SUM(CASE WHEN c.sumber1_ubah='PAD' THEN (a.rupiah*-1) ELSE 0 END) nil_pad,
								   SUM(CASE WHEN c.sumber1_ubah='DAK FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_dak,
								   SUM(CASE WHEN c.sumber1_ubah='DAU' THEN (a.rupiah*-1) ELSE 0 END) nil_dau,
								   SUM(CASE WHEN c.sumber1_ubah='DBHP' THEN (a.rupiah*-1) ELSE 0 END) nil_dbhp,
								   SUM(CASE WHEN c.sumber1_ubah='DAK NON FISIK' THEN (a.rupiah*-1) ELSE 0 END) nil_daknf,
								   SUM(CASE WHEN c.sumber1_ubah='DID' THEN (a.rupiah*-1) ELSE 0 END) nil_did
							FROM trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
												LEFT JOIN trdrka c ON c.kd_skpd=a.kd_skpd AND c.kd_rek5=a.kd_rek5 and c.kd_kegiatan=a.kd_kegiatan
							WHERE b.jns_trans=5 and b.jns_cp in (2) AND LEFT(a.kd_rek5,2) IN ('51','52') AND MONTH(b.tgl_sts) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, a.kd_kegiatan
							UNION ALL
							SELECT a.kd_skpd, a.kd_kegiatan,
								   ISNULL(SUM(nilai),0) nil_pad, 
								   0 nil_dak,
								   0 nil_dau, 
								   0 nil_dbhp,
								   0 nil_daknf, 
								   0 nil_did
							FROM trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
							LEFT JOIN ms_rek5 c ON a.kd_rek5=c.kd_rek5 
							WHERE jns_spp in (1,2,3,4,5,6,7) AND LEFT(c.kd_rek64,2) IN ('51','52') AND MONTH(b.tgl_bukti) BETWEEN '$bulan1' AND '$bulan2'
							GROUP BY a.kd_skpd, a.kd_kegiatan) z
							WHERE nil_pad<>0 OR nil_dak<>0 OR nil_dau<>0 OR nil_dbhp<>0 OR nil_daknf<>0 OR nil_did<>0
							GROUP BY kd_skpd, kd_kegiatan
							)b ON a.kd_skpd=b.kd_skpd AND a.kd_kegiatan=b.kd_kegiatan
							where RIGHT(a.kd_kegiatan,2)<>'00' AND (dbhp<>'0' OR dau<>'0' OR dak<>'0' OR daknf<>'0' OR did<>'0') ) z";
					
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $tot_dbhp = $row->tot_dbhp;
                       $tot_nil_dbhp = $row->tot_nil_dbhp;
                       $tot_dau = $row->tot_dau;
                       $tot_nil_dau = $row->tot_nil_dau;
                       $tot_dak = $row->tot_dak;
                       $tot_nil_dak = $row->tot_nil_dak;
					   $tot_daknf = $row->tot_daknf;
                       $tot_nil_daknf = $row->tot_nil_daknf;
					   $tot_did = $row->tot_did;
                       $tot_nil_did = $row->tot_nil_did;
					
				
				
				$cRet .='
						<tr>
							<td align=\"center\" bgcolor=\"#CCCCCC\"><b>Total</b></td>
							<td align=\"center\" bgcolor=\"#CCCCCC\"><b></b></td>
							<td align=\"center\" bgcolor=\"#CCCCCC\"><b></b></td>
							<td align=\"center\" bgcolor=\"#CCCCCC\"><b></b></td>
							<td align=\"center\" ><b>'.number_format($tot_dbhp, "2", ",", ".").'</b></td>
							<td align=\"center\" ><b>'.number_format($tot_nil_dbhp, "2", ",", ".").'</b></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ><b>'.number_format($tot_dau, "2", ",", ".").'</b></td>
							<td align=\"center\" ><b>'.number_format($tot_nil_dau, "2", ",", ".").'</b></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ><b>'.number_format($tot_dak, "2", ",", ".").'</b></td>
							<td align=\"center\" ><b>'.number_format($tot_nil_dak, "2", ",", ".").'</b></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ><b>'.number_format($tot_daknf, "2", ",", ".").'</b></td>
							<td align=\"center\" ><b>'.number_format($tot_nil_daknf, "2", ",", ".").'</b></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ><b>'.number_format($tot_did, "2", ",", ".").'</b></td>
							<td align=\"center\" ><b>'.number_format($tot_nil_did, "2", ",", ".").'</b></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ><b>'.number_format(0, "2", ",", ".").'</b></td>
							<td align=\"center\" ><b>'.number_format(0, "2", ",", ".").'</b></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ></td>
							<td align=\"center\" ></td>
							
						 </tr>
						';
			}
			
			$cRet .="</table>";
			
			$data['prev']= $cRet;    
            $judul='CETAK_LRA_BERSUMBERDANA';
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
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
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
				$sql="SELECT kd_rek,nm_rek,anggaran,bulan_lalu,bulan_ini,sd_bulan_ini FROM realisasi_jurnal_n($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek,1)='4' AND $where ORDER BY kd_rek";
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
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   
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
					   $tot_persen=$tot_sd_bulan_ini/$tot_nil_ang*100;
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
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
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
				
				$sql="SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_pend_n($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)<='$jenis' ORDER BY kd_kegiatan,kd_rek";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_kegiatan = $row->kd_kegiatan;
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$sd_bulan_ini-$nil_ang;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
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
					
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_n($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)='3'";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$sd_bulan_ini-$nil_ang;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
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
					
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_objek_n($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)='3'";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   
                       $nil_ang1 = $row->anggaran;
                       $sd_bulan_ini1 = $row->sd_bulan_ini;
					   $sisa1=$sd_bulan_ini1-$nil_ang1;
					   $persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 :$sd_bulan_ini1/$nil_ang1*100;
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
					
					$sql="SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci_n($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)<='$jenis' ORDER BY kd_kegiatan,kd_rek";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_kegiatan = $row->kd_kegiatan;
					   $kd_rek = $row->kd_rek;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$sd_bulan_ini-$nil_ang;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
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
	
	function cetak_real_pend_lo_unit($bulan='',$ctk='',$anggaran='',$kd_skpd='',$jenis='',$tglttd='', $ttd=''){
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
		$anggaran = $this->uri->segment(8);
		
		if($anggaran==1){
			$ang = "nilai";
		}else if($anggaran==2){
			$ang = "nilai_sempurna";
		}else{
			$ang = "nilai_ubah";
		}
		
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
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
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
                    <td rowspan=\"2\" width=\"6%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
					<td colspan=\"6\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARA BELANJA TIDAK LANGSUNG</b></td>
					<td rowspan=\"2\" width=\"9%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH</b></td>
                    <td colspan=\"6\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BELANJA TIDAK LANGSUNG</b></td>
                    <td rowspan=\"2\" width=\"9%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH</b></td>
                    <td colspan=\"3\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN BELANJA LANGSUNG</b></td>
                    <td rowspan=\"2\" width=\"9%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH</b></td>
					<td colspan=\"3\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BELANJA LANGSUNG</b></td>
                    <td rowspan=\"2\" width=\"9%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH</b></td>
				</tr>
				<tr>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>PEGAWAI</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>BANTUAN SOSIAL</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>HIBAH</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>BAGI HASIL</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>BANTUAN KEUANGAN</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>TIDAK TERDUGA</b></td>
					
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>PEGAWAI</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>BANTUAN SOSIAL</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>HIBAH</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>BAGI HASIL</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>BANTUAN KEUANGAN</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>TIDAK TERDUGA</b></td>
					
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>PEGAWAI</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>BARANG JASA</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>MODAL</b></td>
					
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>PEGAWAI</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>BARANG JASA</b></td>
					<td align=\"center\" bgcolor=\"#CCCCCC\" ><b>MODAL</b></td>
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
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >14</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >15</td> 
				   
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >16</td> 
				   
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >19</td> 
                   
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >20</td> 
				   
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >21</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >22</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >23</td> 
                   
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >24</td> 
				</tr>
				</thead>";
				
				$sql = "SELECT r.kode, r.nama, r.ang_bel_peg, r.ang_bel_bansos, r.ang_bel_hibah, r.ang_bel_bagi, r.ang_bel_keu, r.ang_bel_takduga, r.ang_bel_peg2, r.ang_bel_brg, r.ang_bel_modal, s.bel_peg, s.bel_bansos, s.bel_hibah, s.bel_bagi, s.bel_keu, s.bel_takduga, s.bel_peg2, s.bel_brg, s.bel_modal
						FROM (
						SELECT a.kd_fungsi as kode, '' kd_skpd, a.nm_fungsi as nama
						,SUM(ISNULL(ang_bel_peg,0)) as ang_bel_peg
						,SUM(ISNULL(ang_bel_bansos,0)) as ang_bel_bansos
						,SUM(ISNULL(ang_bel_hibah,0)) as ang_bel_hibah
						,SUM(ISNULL(ang_bel_bagi,0)) as ang_bel_bagi
						,SUM(ISNULL(ang_bel_keu,0)) as ang_bel_keu
						,SUM(ISNULL(ang_bel_takduga,0)) as ang_bel_takduga
						,SUM(ISNULL(ang_bel_peg2,0)) as ang_bel_peg2
						,SUM(ISNULL(ang_bel_brg,0)) as ang_bel_brg
						,SUM(ISNULL(ang_bel_modal,0)) as ang_bel_modal
						FROM ms_fungsi a LEFT JOIN 
						(
						SELECT RTRIM(a.kd_fungsi) as kode
						,SUM(ISNULL(ang_bel_peg,0)) as ang_bel_peg
						,SUM(ISNULL(ang_bel_bansos,0)) as ang_bel_bansos
						,SUM(ISNULL(ang_bel_hibah,0)) as ang_bel_hibah
						,SUM(ISNULL(ang_bel_bagi,0)) as ang_bel_bagi
						,SUM(ISNULL(ang_bel_keu,0)) as ang_bel_keu
						,SUM(ISNULL(ang_bel_takduga,0)) as ang_bel_takduga
						,SUM(ISNULL(ang_bel_peg2,0)) as ang_bel_peg2
						,SUM(ISNULL(ang_bel_brg,0)) as ang_bel_brg
						,SUM(ISNULL(ang_bel_modal,0)) as ang_bel_modal
						FROM ms_urusan a 
						LEFT JOIN
						(
						SELECT LEFT(kd_kegiatan,4) kd_urusan,SUM(CASE WHEN LEFT(kd_rek5,3)='511' then $ang END) AS ang_bel_peg
															,SUM(CASE WHEN LEFT(kd_rek5,3)='515' then $ang END) AS ang_bel_bansos
															,SUM(CASE WHEN LEFT(kd_rek5,3)='514' then $ang END) AS ang_bel_hibah
															,SUM(CASE WHEN LEFT(kd_rek5,3)='516' then $ang END) AS ang_bel_bagi
															,SUM(CASE WHEN LEFT(kd_rek5,3)='517' then $ang END) AS ang_bel_keu
															,SUM(CASE WHEN LEFT(kd_rek5,3)='518' then $ang END) AS ang_bel_takduga
															,SUM(CASE WHEN LEFT(kd_rek5,3)='521' then $ang END) AS ang_bel_peg2
															,SUM(CASE WHEN LEFT(kd_rek5,3)='522' then $ang END) AS ang_bel_brg
															,SUM(CASE WHEN LEFT(kd_rek5,3)='523' then $ang END) AS ang_bel_modal
						FROM trdrka a 
						WHERE LEFT(a.kd_rek5,1) IN ('5') 
						GROUP BY LEFT(kd_kegiatan,4))b
						ON a.kd_urusan=b.kd_urusan
						GROUP BY a.kd_fungsi) b
						on a.kd_fungsi=b.kode
						GROUP BY a.kd_fungsi,nm_fungsi

						UNION ALL

						SELECT RTRIM(a.kd_fungsi)+'.'+a.kd_urusan  as kode, '' kd_skpd, a.nm_urusan as nama
						,SUM(ISNULL(ang_bel_peg,0)) as ang_bel_peg
						,SUM(ISNULL(ang_bel_bansos,0)) as ang_bel_bansos
						,SUM(ISNULL(ang_bel_hibah,0)) as ang_bel_hibah
						,SUM(ISNULL(ang_bel_bagi,0)) as ang_bel_bagi
						,SUM(ISNULL(ang_bel_keu,0)) as ang_bel_keu
						,SUM(ISNULL(ang_bel_takduga,0)) as ang_bel_takduga
						,SUM(ISNULL(ang_bel_peg2,0)) as ang_bel_peg2
						,SUM(ISNULL(ang_bel_brg,0)) as ang_bel_brg
						,SUM(ISNULL(ang_bel_modal,0)) as ang_bel_modal
						FROM ms_urusan a 
						LEFT JOIN
						(
						SELECT LEFT(kd_kegiatan,4) kd_urusan 
						,SUM(CASE WHEN LEFT(kd_rek5,3)='511' then $ang END) AS ang_bel_peg
						,SUM(CASE WHEN LEFT(kd_rek5,3)='515' then $ang END) AS ang_bel_bansos
						,SUM(CASE WHEN LEFT(kd_rek5,3)='514' then $ang END) AS ang_bel_hibah
						,SUM(CASE WHEN LEFT(kd_rek5,3)='516' then $ang END) AS ang_bel_bagi
						,SUM(CASE WHEN LEFT(kd_rek5,3)='517' then $ang END) AS ang_bel_keu
						,SUM(CASE WHEN LEFT(kd_rek5,3)='518' then $ang END) AS ang_bel_takduga
						,SUM(CASE WHEN LEFT(kd_rek5,3)='521' then $ang END) AS ang_bel_peg2
						,SUM(CASE WHEN LEFT(kd_rek5,3)='522' then $ang END) AS ang_bel_brg
						,SUM(CASE WHEN LEFT(kd_rek5,3)='523' then $ang END) AS ang_bel_modal
						FROM trdrka a 
						WHERE LEFT(a.kd_rek5,1) IN ('5')  GROUP BY LEFT(kd_kegiatan,4))b
						ON a.kd_urusan=b.kd_urusan
						GROUP BY a.kd_fungsi,a.kd_urusan,a.nm_urusan

						UNION ALL

						SELECT kode, kd_skpd, nm_skpd, ang_bel_peg, ang_bel_bansos, ang_bel_hibah, ang_bel_bagi, ang_bel_keu, ang_bel_takduga, ang_bel_peg2,ang_bel_brg,ang_bel_modal FROM (
						SELECT b.kd_skpd, (select nm_skpd from trdrka where kd_skpd=b.kd_skpd group by nm_skpd) nm_skpd, RTRIM(a.kd_fungsi)+'.'+a.kd_urusan+'.00'  as kode, a.nm_urusan as nama
						,SUM(ISNULL(ang_bel_peg,0)) as ang_bel_peg
						,SUM(ISNULL(ang_bel_bansos,0)) as ang_bel_bansos
						,SUM(ISNULL(ang_bel_hibah,0)) as ang_bel_hibah
						,SUM(ISNULL(ang_bel_bagi,0)) as ang_bel_bagi
						,SUM(ISNULL(ang_bel_keu,0)) as ang_bel_keu
						,SUM(ISNULL(ang_bel_takduga,0)) as ang_bel_takduga
						,SUM(ISNULL(ang_bel_peg2,0)) as ang_bel_peg2
						,SUM(ISNULL(ang_bel_brg,0)) as ang_bel_brg
						,SUM(ISNULL(ang_bel_modal,0)) as ang_bel_modal
						FROM ms_urusan a 
						LEFT JOIN
						(
						SELECT kd_skpd, LEFT(kd_kegiatan,4) kd_urusan 
						,SUM(CASE WHEN LEFT(kd_rek5,3)='511' then $ang END) AS ang_bel_peg
						,SUM(CASE WHEN LEFT(kd_rek5,3)='515' then $ang END) AS ang_bel_bansos
						,SUM(CASE WHEN LEFT(kd_rek5,3)='514' then $ang END) AS ang_bel_hibah
						,SUM(CASE WHEN LEFT(kd_rek5,3)='516' then $ang END) AS ang_bel_bagi
						,SUM(CASE WHEN LEFT(kd_rek5,3)='517' then $ang END) AS ang_bel_keu
						,SUM(CASE WHEN LEFT(kd_rek5,3)='518' then $ang END) AS ang_bel_takduga
						,SUM(CASE WHEN LEFT(kd_rek5,3)='521' then $ang END) AS ang_bel_peg2
						,SUM(CASE WHEN LEFT(kd_rek5,3)='522' then $ang END) AS ang_bel_brg
						,SUM(CASE WHEN LEFT(kd_rek5,3)='523' then $ang END) AS ang_bel_modal
						FROM trdrka a 
						WHERE LEFT(a.kd_rek5,1) IN ('5')  GROUP BY LEFT(kd_kegiatan,4),kd_skpd)b
						ON a.kd_urusan=b.kd_urusan
						GROUP BY a.kd_fungsi,a.kd_urusan,a.nm_urusan, b.kd_skpd ) x 
						) r
						LEFT JOIN
						(
						SELECT a.kode, a.nama, ISNULL(a.anggaran,0) anggaran, 
						ISNULL(b.bel_peg,0) bel_peg,
						ISNULL(b.bel_bansos,0) bel_bansos,
						ISNULL(b.bel_hibah,0) bel_hibah,
						ISNULL(b.bel_bagi,0) bel_bagi,
						ISNULL(b.bel_keu,0) bel_keu,
						ISNULL(b.bel_takduga,0) bel_takduga,
						ISNULL(b.bel_peg2,0) bel_peg2,
						ISNULL(b.bel_brg,0) bel_brg,
						ISNULL(b.bel_modal,0) bel_modal
						 FROM (
						SELECT a.kd_fungsi as kode, a.nm_fungsi as nama
						,SUM(ISNULL(anggaran,0)) as anggaran
						FROM ms_fungsi a LEFT JOIN 
						(
						SELECT RTRIM(a.kd_fungsi) as kode
						,SUM(ISNULL(anggaran,0)) as anggaran
						FROM ms_urusan a 
						LEFT JOIN
						(
						SELECT LEFT(kd_kegiatan,4) kd_urusan 
						,SUM ($ang) AS anggaran
						FROM trdrka a 
						WHERE LEFT(a.kd_rek5,1) IN ('5') GROUP BY LEFT(kd_kegiatan,4))b
						ON a.kd_urusan=b.kd_urusan
						GROUP BY a.kd_fungsi) b
						on a.kd_fungsi=b.kode
						GROUP BY a.kd_fungsi,nm_fungsi)a
						LEFT JOIN
						(
						SELECT a.kd_fungsi as kode, a.nm_fungsi as nama
						,SUM(ISNULL(bel_peg,0)) as bel_peg
						,SUM(ISNULL(bel_bansos,0)) as bel_bansos
						,SUM(ISNULL(bel_hibah,0)) as bel_hibah
						,SUM(ISNULL(bel_bagi,0)) as bel_bagi
						,SUM(ISNULL(bel_keu,0)) as bel_keu
						,SUM(ISNULL(bel_takduga,0)) as bel_takduga
						,SUM(ISNULL(bel_peg2,0)) as bel_peg2
						,SUM(ISNULL(bel_brg,0)) as bel_brg
						,SUM(ISNULL(bel_modal,0)) as bel_modal
						FROM ms_fungsi a LEFT JOIN 
						(
						SELECT RTRIM(a.kd_fungsi) as kode
						,SUM(ISNULL(bel_peg,0)) as bel_peg
						,SUM(ISNULL(bel_bansos,0)) as bel_bansos
						,SUM(ISNULL(bel_hibah,0)) as bel_hibah
						,SUM(ISNULL(bel_bagi,0)) as bel_bagi
						,SUM(ISNULL(bel_keu,0)) as bel_keu
						,SUM(ISNULL(bel_takduga,0)) as bel_takduga
						,SUM(ISNULL(bel_peg2,0)) as bel_peg2
						,SUM(ISNULL(bel_brg,0)) as bel_brg
						,SUM(ISNULL(bel_modal,0)) as bel_modal
						FROM ms_urusan a 
						LEFT JOIN
						(
						SELECT LEFT(kd_kegiatan,4) kd_urusan
						,SUM(CASE WHEN LEFT(a.map_real,3)='511' THEN (debet-kredit) ELSE 0 END) AS bel_peg
						,SUM(CASE WHEN LEFT(a.map_real,3)='515' THEN (debet-kredit) ELSE 0 END) AS bel_bansos
						,SUM(CASE WHEN LEFT(a.map_real,3)='514' THEN (debet-kredit) ELSE 0 END) AS bel_hibah
						,SUM(CASE WHEN LEFT(a.map_real,3)='516' THEN (debet-kredit) ELSE 0 END) AS bel_bagi
						,SUM(CASE WHEN LEFT(a.map_real,3)='517' THEN (debet-kredit) ELSE 0 END) AS bel_keu
						,SUM(CASE WHEN LEFT(a.map_real,3)='518' THEN (debet-kredit) ELSE 0 END) AS bel_takduga
						,SUM(CASE WHEN LEFT(a.map_real,3)='521' THEN (debet-kredit) ELSE 0 END) AS bel_peg2
						,SUM(CASE WHEN LEFT(a.map_real,3)='522' THEN (debet-kredit) ELSE 0 END) AS bel_brg
						,SUM(CASE WHEN LEFT(a.map_real,3)='523' THEN (debet-kredit) ELSE 0 END) AS bel_modal
						FROM trdju a INNER JOIN trhju b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
						WHERE LEFT(a.map_real,2) IN ('51','52') AND MONTH(tgl_voucher)<=$bulan AND YEAR(tgl_voucher)=$lntahunang GROUP BY LEFT(kd_kegiatan,4))b
						ON a.kd_urusan=b.kd_urusan
						GROUP BY a.kd_fungsi) b
						on a.kd_fungsi=b.kode
						GROUP BY a.kd_fungsi,nm_fungsi)b
						ON a.kode=b.kode

						UNION ALL

						SELECT a.kode, a.nama, ISNULL(a.anggaran,0) anggaran, 
						ISNULL(b.bel_peg,0) bel_peg,
						ISNULL(b.bel_bansos,0) bel_bansos,
						ISNULL(b.bel_hibah,0) bel_hibah,
						ISNULL(b.bel_bagi,0) bel_bagi,
						ISNULL(b.bel_keu,0) bel_keu,
						ISNULL(b.bel_takduga,0) bel_takduga,
						ISNULL(b.bel_peg2,0) bel_peg2,
						ISNULL(b.bel_brg,0) bel_brg,
						ISNULL(b.bel_modal,0) bel_modal FROM 
						(SELECT RTRIM(a.kd_fungsi)+'.'+a.kd_urusan  as kode, a.nm_urusan as nama
						,SUM(ISNULL(anggaran,0)) as anggaran
						FROM ms_urusan a 
						LEFT JOIN
						(
						SELECT LEFT(kd_kegiatan,4) kd_urusan 
						,SUM($ang) AS anggaran
						FROM trdrka a 
						WHERE LEFT(a.kd_rek5,1) IN ('5')  GROUP BY LEFT(kd_kegiatan,4))b
						ON a.kd_urusan=b.kd_urusan
						GROUP BY a.kd_fungsi,a.kd_urusan,a.nm_urusan) a
						LEFT JOIN 
						(
						SELECT RTRIM(a.kd_fungsi)+'.'+a.kd_urusan  as kode, a.nm_urusan as nama
						,SUM(ISNULL(bel_peg,0)) as bel_peg
						,SUM(ISNULL(bel_bansos,0)) as bel_bansos
						,SUM(ISNULL(bel_hibah,0)) as bel_hibah
						,SUM(ISNULL(bel_bagi,0)) as bel_bagi
						,SUM(ISNULL(bel_keu,0)) as bel_keu
						,SUM(ISNULL(bel_takduga,0)) as bel_takduga
						,SUM(ISNULL(bel_peg2,0)) as bel_peg2
						,SUM(ISNULL(bel_brg,0)) as bel_brg
						,SUM(ISNULL(bel_modal,0)) as bel_modal
						FROM ms_urusan a 
						LEFT JOIN
						(
						SELECT LEFT(kd_kegiatan,4) kd_urusan
						,SUM(CASE WHEN LEFT(a.map_real,3)='511' THEN (debet-kredit) ELSE 0 END) AS bel_peg
						,SUM(CASE WHEN LEFT(a.map_real,3)='515' THEN (debet-kredit) ELSE 0 END) AS bel_bansos
						,SUM(CASE WHEN LEFT(a.map_real,3)='514' THEN (debet-kredit) ELSE 0 END) AS bel_hibah
						,SUM(CASE WHEN LEFT(a.map_real,3)='516' THEN (debet-kredit) ELSE 0 END) AS bel_bagi
						,SUM(CASE WHEN LEFT(a.map_real,3)='517' THEN (debet-kredit) ELSE 0 END) AS bel_keu
						,SUM(CASE WHEN LEFT(a.map_real,3)='518' THEN (debet-kredit) ELSE 0 END) AS bel_takduga
						,SUM(CASE WHEN LEFT(a.map_real,3)='521' THEN (debet-kredit) ELSE 0 END) AS bel_peg2
						,SUM(CASE WHEN LEFT(a.map_real,3)='522' THEN (debet-kredit) ELSE 0 END) AS bel_brg
						,SUM(CASE WHEN LEFT(a.map_real,3)='523' THEN (debet-kredit) ELSE 0 END) AS bel_modal
						FROM trdju a INNER JOIN trhju b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
						WHERE LEFT(a.map_real,2) IN ('51','52')  AND MONTH(tgl_voucher)<=$bulan AND YEAR(tgl_voucher)=$lntahunang GROUP BY LEFT(kd_kegiatan,4))b
						ON a.kd_urusan=b.kd_urusan
						GROUP BY a.kd_fungsi,a.kd_urusan,a.nm_urusan)b
						ON a.kode=b.kode
						UNION ALL
						--detail
						SELECT a.kode+'.00', c.nm_skpd as nama, a.anggaran,  
						ISNULL(b.bel_peg,0) bel_peg,
						ISNULL(b.bel_bansos,0) bel_bansos,
						ISNULL(b.bel_hibah,0) bel_hibah,
						ISNULL(b.bel_bagi,0) bel_bagi,
						ISNULL(b.bel_keu,0) bel_keu,
						ISNULL(b.bel_takduga,0) bel_takduga,
						ISNULL(b.bel_peg2,0) bel_peg2,
						ISNULL(b.bel_brg,0) bel_brg,
						ISNULL(b.bel_modal,0) bel_modal FROM 
						(SELECT RTRIM(a.kd_fungsi)+'.'+a.kd_urusan  as kode, a.nm_urusan as nama
						,SUM(ISNULL(anggaran,0)) as anggaran
						FROM ms_urusan a 
						LEFT JOIN
						(
						SELECT LEFT(kd_kegiatan,4) kd_urusan 
						,SUM($ang) AS anggaran
						FROM trdrka a 
						WHERE LEFT(a.kd_rek5,1) IN ('5')  GROUP BY LEFT(kd_kegiatan,4))b
						ON a.kd_urusan=b.kd_urusan
						GROUP BY a.kd_fungsi,a.kd_urusan,a.nm_urusan) a
						LEFT JOIN 
						(
						SELECT b.kd_skpd, RTRIM(a.kd_fungsi)+'.'+a.kd_urusan  as kode, a.nm_urusan as nama
						,SUM(ISNULL(bel_peg,0)) as bel_peg
						,SUM(ISNULL(bel_bansos,0)) as bel_bansos
						,SUM(ISNULL(bel_hibah,0)) as bel_hibah
						,SUM(ISNULL(bel_bagi,0)) as bel_bagi
						,SUM(ISNULL(bel_keu,0)) as bel_keu
						,SUM(ISNULL(bel_takduga,0)) as bel_takduga
						,SUM(ISNULL(bel_peg2,0)) as bel_peg2
						,SUM(ISNULL(bel_brg,0)) as bel_brg
						,SUM(ISNULL(bel_modal,0)) as bel_modal
						FROM ms_urusan a 
						LEFT JOIN
						(
						SELECT kd_skpd, LEFT(kd_kegiatan,4) kd_urusan
						,SUM(CASE WHEN LEFT(a.map_real,3)='511' THEN (debet-kredit) ELSE 0 END) AS bel_peg
						,SUM(CASE WHEN LEFT(a.map_real,3)='515' THEN (debet-kredit) ELSE 0 END) AS bel_bansos
						,SUM(CASE WHEN LEFT(a.map_real,3)='514' THEN (debet-kredit) ELSE 0 END) AS bel_hibah
						,SUM(CASE WHEN LEFT(a.map_real,3)='516' THEN (debet-kredit) ELSE 0 END) AS bel_bagi
						,SUM(CASE WHEN LEFT(a.map_real,3)='517' THEN (debet-kredit) ELSE 0 END) AS bel_keu
						,SUM(CASE WHEN LEFT(a.map_real,3)='518' THEN (debet-kredit) ELSE 0 END) AS bel_takduga
						,SUM(CASE WHEN LEFT(a.map_real,3)='521' THEN (debet-kredit) ELSE 0 END) AS bel_peg2
						,SUM(CASE WHEN LEFT(a.map_real,3)='522' THEN (debet-kredit) ELSE 0 END) AS bel_brg
						,SUM(CASE WHEN LEFT(a.map_real,3)='523' THEN (debet-kredit) ELSE 0 END) AS bel_modal
						FROM trdju a INNER JOIN trhju b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
						WHERE LEFT(a.map_real,2) IN ('51','52')  AND MONTH(tgl_voucher)<=$bulan AND YEAR(tgl_voucher)=$lntahunang GROUP BY kd_skpd, LEFT(kd_kegiatan,4))b
						ON a.kd_urusan=b.kd_urusan
						GROUP BY a.kd_fungsi,a.kd_urusan,a.nm_urusan, b.kd_skpd)b
						ON a.kode=b.kode 
						left join 
						(select kd_skpd, nm_skpd from ms_skpd) c
						on b.kd_skpd=c.kd_skpd
						where b.kd_skpd<>'') s
						on r.kode=s.kode
                        order by kode";	
						 
						 
						$ang_tot_bel_peg=0;
						$ang_tot_bel_bansos=0;
						$ang_tot_bel_hibah=0;
						$ang_tot_bel_bagi=0;
						$ang_tot_bel_keu=0;
						$ang_tot_bel_takduga=0;
						$ang_tot_bel_peg2=0;
						$ang_tot_bel_brg=0;
						$ang_tot_bel_modal=0; 
						$tot_bel_peg=0;
						$tot_bel_bansos=0;
						$tot_bel_hibah=0;
						$tot_bel_bagi=0;
						$tot_bel_keu=0;
						$tot_bel_takduga=0;
						$tot_bel_peg2=0;
						$tot_bel_brg=0;
						$tot_bel_modal=0;
						
						
						$hasil = $this->db->query($sql);
						foreach ($hasil->result() as $row)
					{
					   $kode = $row->kode;
					   $nama = $row->nama;
					   $ang_bel_peg = $row->ang_bel_peg;
					   $ang_bel_bansos = $row->ang_bel_bansos;
					   $ang_bel_hibah = $row->ang_bel_hibah;
					   $ang_bel_bagi = $row->ang_bel_bagi;
					   $ang_bel_keu = $row->ang_bel_keu;
					   $ang_bel_takduga = $row->ang_bel_takduga;
					   $ang_bel_peg2 = $row->ang_bel_peg2;
					   $ang_bel_brg = $row->ang_bel_brg;
					   $ang_bel_modal = $row->ang_bel_modal;
					   $bel_peg = $row->bel_peg;
					   $bel_bansos = $row->bel_bansos;
					   $bel_hibah = $row->bel_hibah;
					   $bel_bagi = $row->bel_bagi;
					   $bel_keu = $row->bel_keu;
					   $bel_takduga = $row->bel_takduga;
					   $bel_peg2 = $row->bel_peg2;
					   $bel_brg = $row->bel_brg;
					   $bel_modal = $row->bel_modal;
					   
					   $ang_jumlah_btl=$ang_bel_peg+$ang_bel_bansos+$ang_bel_hibah+$ang_bel_bagi+$ang_bel_keu+$ang_bel_takduga;
					   $jumlah_btl=$bel_peg+$bel_bansos+$bel_hibah+$bel_bagi+$bel_keu+$bel_takduga;
					   
					   $ang_jumlah_bl=$ang_bel_peg2+$ang_bel_brg+$ang_bel_modal;
					   $jumlah_bl=$bel_peg2+$bel_brg+$bel_modal;
					   
					   $leng=strlen($kode);
					   switch ($leng) {
					   case 10:
					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top">'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($ang_bel_peg, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($ang_bel_bansos, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($ang_bel_hibah, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($ang_bel_bagi, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($ang_bel_keu, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($ang_bel_takduga, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($ang_jumlah_btl, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_peg, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_bansos, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_hibah, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_bagi, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_keu, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_takduga, "2", ",", ".").'</td>
							   <td align="right" valign="top">'.number_format($jumlah_btl, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($ang_bel_peg2, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($ang_bel_brg, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($ang_bel_modal, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($ang_jumlah_bl, "2", ",", ".").'</td> 
							    <td align="right" valign="top">'.number_format($bel_peg2, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_brg, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($bel_modal, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($jumlah_bl, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   default:
					    $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_peg, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_bansos, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_hibah, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_bagi, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_keu, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_takduga, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_jumlah_btl, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_peg, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_bansos, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_hibah, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_bagi, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_keu, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_takduga, "2", ",", ".").'</b></td>
							   <td align="right" valign="top"><b>'.number_format($jumlah_btl, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_peg2, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_brg, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_modal, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_jumlah_bl, "2", ",", ".").'</b></td> 
							    <td align="right" valign="top"><b>'.number_format($bel_peg2, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_brg, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_modal, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($jumlah_bl, "2", ",", ".").'</b></td> 
							</tr>';
					   break;
					   }
					}
				
				$sql_tot = $this->db->query("SELECT ang_bel_peg, ang_bel_bansos, ang_bel_hibah, ang_bel_bagi,ang_bel_keu,
       ang_bel_takduga, ang_bel_peg2, ang_bel_brg, ang_bel_modal,
       bel_peg, bel_bansos, bel_hibah, bel_bagi, bel_keu, bel_takduga,
       bel_peg2,bel_brg, bel_modal FROM (
					SELECT '1' kode, SUM(ang_bel_peg) ang_bel_peg, SUM(ang_bel_bansos) ang_bel_bansos, 
					 SUM(ang_bel_hibah) ang_bel_hibah, SUM(ang_bel_bagi) ang_bel_bagi,
					 SUM(ang_bel_keu) ang_bel_keu, SUM(ang_bel_takduga) ang_bel_takduga,
					 SUM(ang_bel_peg2) ang_bel_peg2, SUM(ang_bel_brg) ang_bel_brg,
					 SUM(ang_bel_modal) ang_bel_modal
					FROM(
					SELECT SUM(ISNULL(ang_bel_peg,0)) as ang_bel_peg
					,SUM(ISNULL(ang_bel_bansos,0)) as ang_bel_bansos
					,SUM(ISNULL(ang_bel_hibah,0)) as ang_bel_hibah
					,SUM(ISNULL(ang_bel_bagi,0)) as ang_bel_bagi
					,SUM(ISNULL(ang_bel_keu,0)) as ang_bel_keu
					,SUM(ISNULL(ang_bel_takduga,0)) as ang_bel_takduga
					,SUM(ISNULL(ang_bel_peg2,0)) as ang_bel_peg2
					,SUM(ISNULL(ang_bel_brg,0)) as ang_bel_brg
					,SUM(ISNULL(ang_bel_modal,0)) as ang_bel_modal
					FROM ms_fungsi a LEFT JOIN 
					(
					SELECT RTRIM(a.kd_fungsi) as kode
					,SUM(ISNULL(ang_bel_peg,0)) as ang_bel_peg
					,SUM(ISNULL(ang_bel_bansos,0)) as ang_bel_bansos
					,SUM(ISNULL(ang_bel_hibah,0)) as ang_bel_hibah
					,SUM(ISNULL(ang_bel_bagi,0)) as ang_bel_bagi
					,SUM(ISNULL(ang_bel_keu,0)) as ang_bel_keu
					,SUM(ISNULL(ang_bel_takduga,0)) as ang_bel_takduga
					,SUM(ISNULL(ang_bel_peg2,0)) as ang_bel_peg2
					,SUM(ISNULL(ang_bel_brg,0)) as ang_bel_brg
					,SUM(ISNULL(ang_bel_modal,0)) as ang_bel_modal
					FROM ms_urusan a 
					LEFT JOIN
					(
					SELECT LEFT(kd_kegiatan,4) kd_urusan,SUM(CASE WHEN LEFT(kd_rek5,3)='511' then $ang END) AS ang_bel_peg
														,SUM(CASE WHEN LEFT(kd_rek5,3)='515' then $ang END) AS ang_bel_bansos
														,SUM(CASE WHEN LEFT(kd_rek5,3)='514' then $ang END) AS ang_bel_hibah
														,SUM(CASE WHEN LEFT(kd_rek5,3)='516' then $ang END) AS ang_bel_bagi
														,SUM(CASE WHEN LEFT(kd_rek5,3)='517' then $ang END) AS ang_bel_keu
														,SUM(CASE WHEN LEFT(kd_rek5,3)='518' then $ang END) AS ang_bel_takduga
														,SUM(CASE WHEN LEFT(kd_rek5,3)='521' then $ang END) AS ang_bel_peg2
														,SUM(CASE WHEN LEFT(kd_rek5,3)='522' then $ang END) AS ang_bel_brg
														,SUM(CASE WHEN LEFT(kd_rek5,3)='523' then $ang END) AS ang_bel_modal
					FROM trdrka a 
					WHERE LEFT(a.kd_rek5,1) IN ('5') 
					GROUP BY LEFT(kd_kegiatan,4))b
					ON a.kd_urusan=b.kd_urusan
					GROUP BY a.kd_fungsi) b
					on a.kd_fungsi=b.kode
					GROUP BY a.kd_fungsi,nm_fungsi) z ) r
					LEFT JOIN (

					SELECT '1' kode,  SUM(bel_peg) bel_peg, sum(bel_bansos) bel_bansos, sum(bel_hibah) bel_hibah, 
					sum(bel_bagi) bel_bagi, sum(bel_keu) bel_keu, sum(bel_takduga) bel_takduga, 
					sum(bel_peg2) bel_peg2, sum(bel_brg) bel_brg, sum(bel_modal) bel_modal
					FROM (
					SELECT ISNULL(b.bel_peg,0) bel_peg,
						ISNULL(b.bel_bansos,0) bel_bansos,
						ISNULL(b.bel_hibah,0) bel_hibah,
						ISNULL(b.bel_bagi,0) bel_bagi,
						ISNULL(b.bel_keu,0) bel_keu,
						ISNULL(b.bel_takduga,0) bel_takduga,
						ISNULL(b.bel_peg2,0) bel_peg2,
						ISNULL(b.bel_brg,0) bel_brg,
						ISNULL(b.bel_modal,0) bel_modal
						 FROM (
						SELECT a.kd_fungsi as kode, a.nm_fungsi as nama
						,SUM(ISNULL(anggaran,0)) as anggaran
						FROM ms_fungsi a LEFT JOIN 
						(
						SELECT RTRIM(a.kd_fungsi) as kode
						,SUM(ISNULL(anggaran,0)) as anggaran
						FROM ms_urusan a 
						LEFT JOIN
						(
						SELECT LEFT(kd_kegiatan,4) kd_urusan 
						,SUM ($ang) AS anggaran
						FROM trdrka a 
						WHERE LEFT(a.kd_rek5,1) IN ('5')  GROUP BY LEFT(kd_kegiatan,4))b
						ON a.kd_urusan=b.kd_urusan
						GROUP BY a.kd_fungsi) b
						on a.kd_fungsi=b.kode
						GROUP BY a.kd_fungsi,nm_fungsi)a
						LEFT JOIN
						(
						SELECT a.kd_fungsi as kode, a.nm_fungsi as nama
						,SUM(ISNULL(bel_peg,0)) as bel_peg
						,SUM(ISNULL(bel_bansos,0)) as bel_bansos
						,SUM(ISNULL(bel_hibah,0)) as bel_hibah
						,SUM(ISNULL(bel_bagi,0)) as bel_bagi
						,SUM(ISNULL(bel_keu,0)) as bel_keu
						,SUM(ISNULL(bel_takduga,0)) as bel_takduga
						,SUM(ISNULL(bel_peg2,0)) as bel_peg2
						,SUM(ISNULL(bel_brg,0)) as bel_brg
						,SUM(ISNULL(bel_modal,0)) as bel_modal
						FROM ms_fungsi a LEFT JOIN 
						(
						SELECT RTRIM(a.kd_fungsi) as kode
						,SUM(ISNULL(bel_peg,0)) as bel_peg
						,SUM(ISNULL(bel_bansos,0)) as bel_bansos
						,SUM(ISNULL(bel_hibah,0)) as bel_hibah
						,SUM(ISNULL(bel_bagi,0)) as bel_bagi
						,SUM(ISNULL(bel_keu,0)) as bel_keu
						,SUM(ISNULL(bel_takduga,0)) as bel_takduga
						,SUM(ISNULL(bel_peg2,0)) as bel_peg2
						,SUM(ISNULL(bel_brg,0)) as bel_brg
						,SUM(ISNULL(bel_modal,0)) as bel_modal
						FROM ms_urusan a 
						LEFT JOIN
						(
						SELECT LEFT(kd_kegiatan,4) kd_urusan
						,SUM(CASE WHEN LEFT(a.map_real,3)='511' THEN (debet-kredit) ELSE 0 END) AS bel_peg
						,SUM(CASE WHEN LEFT(a.map_real,3)='515' THEN (debet-kredit) ELSE 0 END) AS bel_bansos
						,SUM(CASE WHEN LEFT(a.map_real,3)='514' THEN (debet-kredit) ELSE 0 END) AS bel_hibah
						,SUM(CASE WHEN LEFT(a.map_real,3)='516' THEN (debet-kredit) ELSE 0 END) AS bel_bagi
						,SUM(CASE WHEN LEFT(a.map_real,3)='517' THEN (debet-kredit) ELSE 0 END) AS bel_keu
						,SUM(CASE WHEN LEFT(a.map_real,3)='518' THEN (debet-kredit) ELSE 0 END) AS bel_takduga
						,SUM(CASE WHEN LEFT(a.map_real,3)='521' THEN (debet-kredit) ELSE 0 END) AS bel_peg2
						,SUM(CASE WHEN LEFT(a.map_real,3)='522' THEN (debet-kredit) ELSE 0 END) AS bel_brg
						,SUM(CASE WHEN LEFT(a.map_real,3)='523' THEN (debet-kredit) ELSE 0 END) AS bel_modal
						FROM trdju a INNER JOIN trhju b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
						WHERE LEFT(a.map_real,2) IN ('51','52')  AND MONTH(tgl_voucher)<=$bulan AND YEAR(tgl_voucher)=$lntahunang GROUP BY LEFT(kd_kegiatan,4))b
						ON a.kd_urusan=b.kd_urusan
						GROUP BY a.kd_fungsi) b
						on a.kd_fungsi=b.kode
						GROUP BY a.kd_fungsi,nm_fungsi)b
						ON a.kode=b.kode ) x ) s
						on r.kode=s.kode");
				$sql_tot2 = $sql_tot->row();          
				$ang_bel_pegx = $sql_tot2->ang_bel_peg;
					   $ang_bel_bansosx = $sql_tot2->ang_bel_bansos;
					   $ang_bel_hibahx = $sql_tot2->ang_bel_hibah;
					   $ang_bel_bagix = $sql_tot2->ang_bel_bagi;
					   $ang_bel_keux = $sql_tot2->ang_bel_keu;
					   $ang_bel_takdugax = $sql_tot2->ang_bel_takduga;
					   $ang_bel_peg2x = $sql_tot2->ang_bel_peg2;
					   $ang_bel_brgx = $sql_tot2->ang_bel_brg;
					   $ang_bel_modalx = $sql_tot2->ang_bel_modal;
					   $bel_pegx = $sql_tot2->bel_peg;
					   $bel_bansosx = $sql_tot2->bel_bansos;
					   $bel_hibahx = $sql_tot2->bel_hibah;
					   $bel_bagix = $sql_tot2->bel_bagi;
					   $bel_keux = $sql_tot2->bel_keu;
					   $bel_takdugax = $sql_tot2->bel_takduga;
					   $bel_peg2x = $sql_tot2->bel_peg2;
					   $bel_brgx = $sql_tot2->bel_brg;
					   $bel_modalx = $sql_tot2->bel_modal;
				
					   $ang_jumlah_btlx=$ang_bel_pegx+$ang_bel_bansosx+$ang_bel_hibahx+$ang_bel_bagix+$ang_bel_keux+$ang_bel_takdugax;
					   $jumlah_btlx=$bel_pegx+$bel_bansosx+$bel_hibahx+$bel_bagix+$bel_keux+$bel_takdugax;
					   
					   $ang_jumlah_blx=$ang_bel_peg2x+$ang_bel_brgx+$ang_bel_modalx;
					   $jumlah_blx=$bel_peg2x+$bel_brgx+$bel_modalx;
					   
					   $cRet .='<tr>
							   <td colspan="2" align="left" valign="top"><b>JUMLAH</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_pegx, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_bansosx, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_hibahx, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_bagix, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_keux, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_takdugax, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_jumlah_btlx, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_pegx, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_bansosx, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_hibahx, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_bagix, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_keux, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_takdugax, "2", ",", ".").'</b></td>
							   <td align="right" valign="top"><b>'.number_format($jumlah_btlx, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_peg2x, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_brgx, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_bel_modalx, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($ang_jumlah_blx, "2", ",", ".").'</b></td> 
							    <td align="right" valign="top"><b>'.number_format($bel_peg2x, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_brgx, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($bel_modalx, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($jumlah_blx, "2", ",", ".").'</b></td> 
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
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
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
			
			$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi FROM map_lra_sap WHERE seq BETWEEN '210' AND '235' ORDER BY seq
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
					$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal_n($bulan,1,$lntahunang) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4))
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
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
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
						<TD width="40%"  align="left" >Lampiran II.B.2<br>Peraturan Menteri Keuangan No. 04/PMK.07/2011<br>Tentang Tata Cara Penyampaian Informasi Keuangan Daerah </TD>
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
			$sql="SELECT kd_rek,nm_rek,sd_bulan_ini FROM realisasi_jurnal_n($bulan,1,$lntahunang) WHERE LEFT(kd_rek,3)='511' ORDER BY kd_rek";
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
            $judul='PMK_II.B.2 ';
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
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
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
			$sql="SELECT kd_rek,nm_rek,sd_bulan_ini FROM realisasi_jurnal_n($bulan,1,$lntahunang) WHERE LEFT(kd_rek,1)='6' ORDER BY kd_rek";
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
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
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
						
					$sql = "SELECT SUM(terima) as terima, SUM(setor) as setor , SUM(selisih) as selisih FROM data_pajak($bulan,$lntahunang) WHERE kd_rek5 IN ($kd_rek)
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

	function ctk_real_sp2d(){
        $data['page_title']= 'CETAK REALISASI SP2D';
        $this->template->set('title', 'CETAK REALISASI SP2D');   
        $this->template->load('template','lamp_pmk/cetak_real_sp2d',$data) ;	
	}
	
	function cetak_real_sp2d($tgl1='',$tgl2='',$anggaran='',$ctk=''){
    if($anggaran==1){
		$judul="APBD";
	} else if($anggaran==2){
		$judul="APBD PENYEMPURNAAN";
	} else{
		$judul="APBD PERUBAHAN";
	}
	$cRet ='';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr><td colspan =\"8\" align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td colspan =\"8\" align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REALISASI SP2D </b></tr>
                    <tr><td colspan =\"8\"  align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>".strtoupper($this->tukd_model->tanggal_format_indonesia($tgl1))." - ".strtoupper($this->tukd_model->tanggal_format_indonesia($tgl2))."</b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
				<thead>
				<tr>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>NO</b></td>
					<td width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD SKPD</b></td>
					<td width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>NM SKPD</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>$judul</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI SP2D</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SPJ</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
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
				   </tr>
				</thead>";
				
							
					$sql = "exec realisasi_sp2d_spj '$tgl1','$tgl2','$anggaran'
					";
					$no=0;
					$jum_ang=0;
					$jum_sp2d=0;
					$jum_persen1=0;
					$jum_spj=0;
					$jum_persen2=0;
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
				   $no=$no+1;
				   $nomor = $row->kd_skpd;
				   $nm_skpd = $row->nm_skpd;
				   $anggaran = $row->anggaran;
				   $sp2d = $row->sp2d;
				   $spj = $row->spj;
				   if($anggaran==0){
					   $persen1=0;
				   }else{
					   $persen1=$sp2d/$anggaran *100;
				   }
				   if($sp2d==0){
					   $persen2=0;
				   }else{
					   $persen2=$spj/$sp2d *100;
				   }
				   $jum_ang=$jum_ang+$anggaran;
				   $jum_sp2d=$jum_sp2d+$sp2d;
				   $jum_spj=$jum_spj+$spj;
					$cRet .='<tr>
							   <td align="center" valign="top">'.$no.'</td> 
							   <td align="left"  valign="top">'.$nomor.'</td> 
							   <td align="left"  valign="top">'.$nm_skpd.'</td> 
							   <td align="right" valign="top">'.number_format($anggaran, "2", ".", ",").'</td> 
							   <td align="right" valign="top">'.number_format($sp2d, "2", ".", ",").'</td> 
							   <td align="right" valign="top">'.number_format($persen1, "2", ".", ",").' %</td> 
							   <td align="right" valign="top">'.number_format($spj, "2", ".", ",").'</td> 
							   <td align="right" valign="top">'.number_format($persen2, "2", ".", ",").' %</td> 
							  
							</tr>';
					}
				if($jum_ang==0){
					   $jum_persen1=0;
				   }else{
					   $jum_persen1=$jum_sp2d/$jum_ang *100;
				   }
				if($jum_sp2d==0){
					   $jum_persen2=0;
				   }else{
					   $jum_persen2=$jum_spj/$jum_sp2d *100;
				   }		
			$cRet .='<tr>
							   <td colspan="3" align="center" valign="top">JUMLAH</td> 
							   <td align="right" valign="top">'.number_format($jum_ang, "2", ".", ",").'</td> 
							   <td align="right" valign="top">'.number_format($jum_sp2d, "2", ".", ",").'</td> 
							   <td align="right" valign="top">'.number_format($jum_persen1, "2", ".", ",").' %</td> 
							   <td align="right" valign="top">'.number_format($jum_spj,  "2", ".", ",").'</td> 
							   <td align="right" valign="top">'.number_format($jum_persen2, "2", ".", ",").' %</td> 
							  
							</tr>';
			$cRet .="</table>";
			
			$data['prev']= $cRet;    
            $judul='REALISASI_SP2D ';
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