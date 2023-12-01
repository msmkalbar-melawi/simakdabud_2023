<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Perda extends CI_Controller
{

    function __contruct() {
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
      
	function perdaI_5(){
        $data['page_title']= 'CETAK PERDA LAMP. I.5';
        $this->template->set('title', 'CETAK PERDA LAMP. I.5');   
        $this->template->load('template','perda/cetak_perda_lampI_5',$data) ;	
	}
	
	function cetak_perda_lampI_5($bulan='',$anggaran='',$ctk='',$tglttd='', $ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		
	$cRet ='';
	
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>IKHTISAR PENCAPAIAN KINERJA KEUANGAN</b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN $lntahunang </b></tr>
					</TABLE>";
		
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH(BERKURANG)</b></td>
                    
				</tr>
				<tr>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Rp.</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5=(4-3)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";
				
				$sql="SELECT seq, kd_skpd, kd_kegiatan, nm_rek, anggaran, realisasi FROM BABIII($bulan,$anggaran,$lntahunang) ORDER BY kd_skpd";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $seq = $row->seq;
					   $kd_skpd = $row->kd_skpd;
					   $kd_kegiatan = $row->kd_kegiatan;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->realisasi;
					   $sisa=$sd_bulan_ini-$nil_ang;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';
					   $leng=strlen($kd_kegiatan);
					   
					   switch ($seq) {
					   case 5:
					   $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_kegiatan.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   case 10:
					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
					   break;
					   default:
					     $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_kegiatan.'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
					   }
					}
					$hasil->free_result(); 
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">
					</td>
				</tr>
				</table>";
				
			$data['prev']= $cRet;    
            $judul='PERDA_LAMP_I.5';
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

	function lra_ppkd(){
        $data['page_title']= 'CETAK LRA PPKD';
        $this->template->set('title', 'CETAK LRA PPKD');   
        $this->template->load('template','perda/cetak_lra_ppkd',$data) ;	
	}
	
	function cetak_lra_ppkd($bulan='',$ctk='',$anggaran='',$ttd='',$tglttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		 switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "TRIWULAN I";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "SEMESTER I";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "TRIWULAN III";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "TAHUN ANGGARAN $lntahunang";
        break;
    }
		
		
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
		if($ttd=='-'){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}
		
		$sqlnogub="SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $no_perda     = $rowsc->no_perda;
                    $tgl_perda     = $rowsc->tgl_perda;
                    $no_pergub     = $rowsc->no_pergub;
                    $tgl_pergub    = $rowsc->tgl_pergub;
					$rancang    = "";
                 }
		 
	$cRet ="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					</tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>PPKD</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>S/D $judul $lntahunang</b></tr>
					</TABLE>";

		
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>NO URUT</b></td>
                    <td width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/(KURANG)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>PERSEN(%)</b></td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";
				
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (nilai_real) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nilai_real) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nilai) as nil_ang, SUM(nilai_real) as nilai_real FROM data_lra_ppkd($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,1) IN ('4','5') 
					GROUP BY LEFT(kd_rek5,1)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_surplus = $row->ang_surplus;
                       $nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $ang_surplus-$nil_surplus;
						if(($ang_surplus==0) || ($ang_surplus=='')){
						$persen_surplus=0;
					} else{
					$persen_surplus = $nil_surplus/$ang_surplus*100;
					}	
										$hasil->free_result();        
					if($ang_surplus<0){
						$ang_surplus1=$ang_surplus*-1;
						$a='(';
						$b=')';
					} else{
						$ang_surplus1=$ang_surplus;
						$a='';
						$b='';
					}
					if($nil_surplus<0){
						$nil_surplus1=$nil_surplus*-1;
						$c='(';
						$d=')';
					} else{
						$nil_surplus1=$nil_surplus;
						$c='';
						$d='';
					}
					if($sisa_surplus<0){
						$sisa_surplus1=$sisa_surplus*-1;
						$e='(';
						$f=')';
					} else{
						$sisa_surplus1=$sisa_surplus;
						$e='';
						$f='';
					}
			
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (nilai_real) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nilai_real) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nilai) as nil_ang, SUM(nilai_real) as nilai_real FROM data_lra_ppkd($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,2) IN ('61','62') 
					GROUP BY LEFT(kd_rek5,2)) a;
					";
					 $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_netto = $row->ang_netto;
                       $nil_netto = $row->nil_netto;
					}
					$sisa_netto = $ang_netto-$nil_netto;
					if(($ang_netto==0) || ($ang_netto=='')){
						$persen_netto=0;
					} else{
					$persen_netto = $nil_netto/$ang_netto*100;
					}
					$hasil->free_result();  
					if($ang_netto<0){
						$ang_netto1=$ang_netto*-1;
						$g='(';
						$h=')';
					} else{
						$ang_netto1=$ang_netto;
						$g='';
						$h='';
					}
					if($nil_netto<0){
						$nil_netto1=$nil_netto*-1;
						$i='(';
						$j=')';
					} else{
						$nil_netto1=$nil_netto;
						$i='';
						$j='';
					}
					if($sisa_netto<0){
						$sisa_netto1=$sisa_netto*-1;
						$k='(';
						$l=')';
					} else{
						$sisa_netto1=$sisa_netto;
						$k='';
						$l='';
					}	
					
					$ang_silpa = $ang_surplus+$ang_netto;
					$nil_silpa = $nil_surplus+$nil_netto;
					$sisa_silpa = $ang_silpa-$nil_silpa;
					if($ang_silpa==0){
						$persen_silpa=0;
					}else{
					$persen_silpa = $nil_silpa/$ang_silpa*100;
					}
					if($ang_silpa<0){
						$ang_silpa1=$ang_silpa*-1;
						$m='(';
						$n=')';
					} else{
						$ang_silpa1=$ang_silpa;
						$m='';
						$n='';
					}
					if($nil_silpa<0){
						$nil_silpa1=$nil_silpa*-1;
						$o='(';
						$p=')';
					} else{
						$nil_silpa1=$nil_silpa;
						$o='';
						$p='';
					}
					if($sisa_silpa<0){
						$sisa_silpa1=$sisa_silpa*-1;
						$q='(';
						$r=')';
					} else{
						$sisa_silpa1=$sisa_silpa;
						$q='';
						$r='';
					}	
			$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi FROM map_lra_ppkd ORDER BY seq
					";
					$no=0;
					$tot_peg=0;
					$tot_brg=0;
					$tot_mod=0;
					$tot_bansos=0;
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
					$sql = "SELECT SUM(nilai) as nil_ang, SUM(nilai_real) as nilai FROM data_lra_ppkd($bulan,$anggaran,$lntahunang) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4))
					";
										
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
                       $nil_ang = $row->nil_ang;
					   $nilai = $row->nilai;
					}
					$sel = $nil_ang-$nilai;
					if(($nil_ang==0) || ($nil_ang=='')){
						$persen=0;
					} else{
					$persen = $nilai/$nil_ang*100;
					}
					 switch ($spasi) {
					 case 1:
                        $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$this->dotrek($kode).'</b></td> 
							   <td align="left"  valign="top"><b>'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sel, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>'; 
                        break;	
                    case 2:
                         $cRet .='<tr>
							   <td align="left" valign="top">'.$this->dotrek($kode).'</td> 
							   <td align="left"  valign="top">'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sel, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
                        break;
					 case 3:
                         $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$this->dotrek($kode).'</b></td> 
							   <td align="right"  valign="top"><b>'.$nama.'</td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($sel, "2", ",", ".").'</td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
                        break;
					case 4:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$this->dotrek($kode).'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sel, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 5:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$this->dotrek($kode).'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.''.number_format($ang_surplus1, "2", ",", ".").''.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$c.''.number_format($nil_surplus1, "2", ",", ".").''.$d.'</b></td> 
							   <td align="right" valign="top"><b>'.$e.''.number_format($sisa_surplus1, "2", ",", ".").''.$f.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen_surplus, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					
					case 7;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$this->dotrek($kode).'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$m.''.number_format($ang_silpa1, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top" ><b>'.$o.''.number_format($nil_silpa1, "2", ",", ".").''.$p.'</b></td> 
							   <td align="right" valign="top" ><b>'.$q.''.number_format($sisa_silpa1, "2", ",", ".").''.$r.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_silpa, "2", ",", ".").'</b></td> 
							</tr>';
                        break; 
					}
					}
			 
                      
		
			$cRet .="</table>";
			
			/* $cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>
					</td>
				</tr>
				</table>";  */         
		
			$cRet .="</table>";
			
			$data['prev']= $cRet;    
            $judul='LRA_PPKD';
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
	
	function cetak_sap_sinergi($bulan='',$ctk='',$anggaran='',$jenis='',$kd_skpd='',$kd_org='',$ttd='',$tglttd='',$flaporan=''){
        $lntahunang = $this->session->userdata('pcThang');       
		 switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "TRIWULAN I";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "SEMESTER I";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "TRIWULAN III";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "TAHUN ANGGARAN $lntahunang";
        break;
    }
		
		$where = "";
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
		if($ttd=='-'){
			$nama_ttd='';
			$pangkat='';
			$jabatan='';
			$nip='';
		}else{
			$ttd=str_replace("abc"," ",$ttd);
			$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
						 $sqlsclient=$this->db->query($sqlsc);
						 foreach ($sqlsclient->result() as $rowttd)
						{
							$nama_ttd = $rowttd->nama;
							$jabatan = $rowttd->jabatan;
							$pangkat = $rowttd->pangkat;
							$nip = 'NIP. '.$ttd;
						} 
		}
		
		$sqlnogub="SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $no_perda     = $rowsc->no_perda;
                    $tgl_perda     = $rowsc->tgl_perda;
                    $no_pergub     = $rowsc->no_pergub;
                    $tgl_pergub    = $rowsc->tgl_pergub;
					$rancang    = "";
                 }
		 
	$cRet ="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$judul</b></tr>
					</TABLE>";
		
		
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
				</tr>
				<tr>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";
				
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet-kredit) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal($bulan,$anggaran) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_surplus = $row->ang_surplus;
                       $nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $ang_surplus-$nil_surplus;
						if(($ang_surplus==0) || ($ang_surplus=='')){
						$persen_surplus=0;
					} else{
					$persen_surplus = $nil_surplus/$ang_surplus*100;
					}	
										$hasil->free_result();        
					if($ang_surplus<0){
						$ang_surplus1=$ang_surplus*-1;
						$a='(';
						$b=')';
					} else{
						$ang_surplus1=$ang_surplus;
						$a='';
						$b='';
					}
					if($nil_surplus<0){
						$nil_surplus1=$nil_surplus*-1;
						$c='(';
						$d=')';
					} else{
						$nil_surplus1=$nil_surplus;
						$c='';
						$d='';
					}
					if($sisa_surplus<0){
						$sisa_surplus1=$sisa_surplus*-1;
						$e='(';
						$f=')';
					} else{
						$sisa_surplus1=$sisa_surplus;
						$e='';
						$f='';
					}
			
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (debet-kredit) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal($bulan,$anggaran) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_netto = $row->ang_netto;
                       $nil_netto = $row->nil_netto;
					}
					$sisa_netto = $ang_netto-$nil_netto;
					if(($ang_netto==0) || ($ang_netto=='')){
						$persen_netto=0;
					} else{
					$persen_netto = $nil_netto/$ang_netto*100;
					}
					$hasil->free_result();  
					if($ang_netto<0){
						$ang_netto1=$ang_netto*-1;
						$g='(';
						$h=')';
					} else{
						$ang_netto1=$ang_netto;
						$g='';
						$h='';
					}
					if($nil_netto<0){
						$nil_netto1=$nil_netto*-1;
						$i='(';
						$j=')';
					} else{
						$nil_netto1=$nil_netto;
						$i='';
						$j='';
					}
					if($sisa_netto<0){
						$sisa_netto1=$sisa_netto*-1;
						$k='(';
						$l=')';
					} else{
						$sisa_netto1=$sisa_netto;
						$k='';
						$l='';
					}	
					
					$ang_silpa = $ang_surplus+$ang_netto;
					$nil_silpa = $nil_surplus+$nil_netto;
					$sisa_silpa = $ang_silpa-$nil_silpa;
					if($ang_silpa==0){
						$persen_silpa=0;
					}else{
					$persen_silpa = $nil_silpa/$ang_silpa*100;
					}
					if($ang_silpa<0){
						$ang_silpa1=$ang_silpa*-1;
						$m='(';
						$n=')';
					} else{
						$ang_silpa1=$ang_silpa;
						$m='';
						$n='';
					}
					if($nil_silpa<0){
						$nil_silpa1=$nil_silpa*-1;
						$o='(';
						$p=')';
					} else{
						$nil_silpa1=$nil_silpa;
						$o='';
						$p='';
					}
					if($sisa_silpa<0){
						$sisa_silpa1=$sisa_silpa*-1;
						$q='(';
						$r=')';
					} else{
						$sisa_silpa1=$sisa_silpa;
						$q='';
						$r='';
					}	
			$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi FROM map_lra_permen_sinergi ORDER BY seq
					";
					$no=0;
					$tot_peg=0;
					$tot_brg=0;
					$tot_mod=0;
					$tot_bansos=0;
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
					$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal($bulan,$anggaran) WHERE (LEFT(kd_rek5_64,1) IN ($kode1) or LEFT(kd_rek5_64,2) IN ($kode2) or LEFT(kd_rek5_64,3) IN ($kode3) or LEFT(kd_rek5_64,5) IN($kode4))
					";

                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
                       $nil_ang = $row->nil_ang;
					   $nilai = $row->nilai;
					}
					$sel = $nil_ang-$nilai;
					if(($nil_ang==0) || ($nil_ang=='')){
						$persen=0;
					} else{
					$persen = $nilai/$nil_ang*100;
					}
					 switch ($spasi) {
					 case 1:
                        $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sel, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>'; 
                        break;	
					 case 2:
                         $cRet .='<tr>
							   <td align="left" valign="top">'.$kode.'</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sel, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
                        break;
					}
					}
			 
                      
		
			$cRet .="</table>";
			
			$data['prev']= $cRet;    
            $judul='LRA_PERMEN ';
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
	
	
	function dotrek($rek){
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
	
	function perdaI(){
        $data['page_title']= 'CETAK PERDA LAMP. I';
        $this->template->set('title', 'CETAK PERDA LAMP. I');   
        $this->template->load('template','perda/cetak_perda_lampI',$data) ;	
	}
	
	function perdaI_1(){
        $data['page_title']= 'CETAK PERDA LAMP. I.1';
        $this->template->set('title', 'CETAK PERDA LAMP. I.1');   
        $this->template->load('template','perda/cetak_perda_lampI_1',$data) ;	
	}
		
    function perdaI_2(){
        $data['page_title']= 'CETAK PERDA LAMP. I.2';
        $this->template->set('title', 'CETAK PERDA LAMP. I.2');   
        $this->template->load('template','perda/cetak_perda_lampI_2',$data) ;	
	}
	
	function perdaI_3(){
        $data['page_title']= 'CETAK PERDA LAMP. I.3';
        $this->template->set('title', 'CETAK PERDA LAMP. I.3');   
        $this->template->load('template','perda/cetak_perda_lampI_3',$data) ;	
	}
	
	function perdaI_4(){
        $data['page_title']= 'CETAK PERDA LAMP. I.4';
        $this->template->set('title', 'CETAK PERDA LAMP. I.4');   
        $this->template->load('template','perda/cetak_perda_lampI_4',$data) ;	
	}
	
	function perkada_1(){
        $data['page_title']= 'CETAK PERGUB LAMP. I';
        $this->template->set('title', 'CETAK PERGUB LAMP. I');   
        $this->template->load('template','perda/cetak_perkada_lampI',$data) ;	
	}
	
	function perkada_2(){
        $data['page_title']= 'CETAK PERGUB LAMP. II';
        $this->template->set('title', 'CETAK PERGUB LAMP. III');   
        $this->template->load('template','perda/cetak_perkada_lampII',$data) ;	
	}
	
	function cetak_perda_lampI_1($bulan='',$anggaran='',$ctk='',$tglttd='',$ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
        if ($anggaran=='1'){
			$ang='nilai';
		} if ($anggaran=='2'){
			$ang='nilai_sempurna';
		} else{
			$ang='nilai_ubah';
		}
		
		
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}
					$nip = '';
		
		$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc){
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
		
		$sqlnogub="SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $ket_lampiran      = strtoupper("Lampiran I.1");
                    $ket_perda         = strtoupper($rowsc->ket_perda);
                    $ket_perda_no      = strtoupper($rowsc->ket_perda_no);
                    $ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
                 }
		 
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >'.$ket_lampiran.'</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >'.$ket_perda.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_perda_no.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_perda_tentang.'</TD>
					</TR>
					</TABLE><br/>';
					
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"3\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KODE</td>
                    <td rowspan = \"3\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">URUSAN PEMERINTAH DAERAH</td>
					<td colspan = \"4\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PENDAPATAN</td>
                    <td colspan = \"6\" width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BELANJA</td>
                </tr>
				<tr>
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">ANGGARAN SETELAH PERUBAHAN</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI</td> 
                   <td colspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BERTAMBAH/KURANG</td> 
                   <td rowspan =\"2\" align=\"LEFT\" bgcolor=\"#CCCCCC\" style=\"font-size:9px\"> 	 7. BLJ PEGAWAI<BR>
																									 8. BLJ BARANG JASA<BR>
																									 9. BLJ MODAL<BR>
																									 10.BLJ BUNGA<BR>
																									 11.BLJ SUBSIDI<BR>
																									 12.BLJ HIBAH<BR>
																									 13.BLJ BANSOS<BR>
																									 14.BLJ BAGI HASIL<BR>
																									 15.BLJ BANTUAN KEU.<BR>
																									 16.BLJ TDK TERDUGA
																									 </td> 
                   <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH BELANJA</td> 
				   <td rowspan =\"2\" align=\"LEFT\" bgcolor=\"#CCCCCC\" style=\"font-size:9px\">	 18.BLJ PEGAWAI<BR>
																									 19.BLJ BARANG JASA<BR>
																									 20.BLJ MODAL<BR>
																									 21.BLJ BUNGA<BR>
																									 22.BLJ SUBSIDI<BR>
																									 23.BLJ HIBAH<BR>
																									 24.BLJ BANSOS<BR>
																									 25.BLJ BAGI HASIL<BR>
																									 26.BLJ BANTUAN KEU.<BR>
																									 27.BLJ TDK TERDUGA
																									 </td> 
                   <td rowspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH BELANJA</td> 
				   <td colspan=\"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BERTAMBAH/KURANG</td> 
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">RP.</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">RP.</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">RP.</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">%</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">RP.</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">%</td> 
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5(4-3)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">RP</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17=(7+sd+16)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">RP</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">28=(18+sd+27)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">29=28-17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">30</td> 
				</tr>
				</thead>";
				
			$tot_ang_brng=0;
			$tot_ang_pelihara=0;
			$tot_ang_dinas=0;
			$tot_ang_honor=0;
			$total_ang=0;
			$tot_real_brng=0;
			$tot_real_pelihara=0;
			$tot_real_dinas=0;
			$tot_real_honor=0;
			$total_real=0;
			$no=0;
			$sql = "
SELECT a.kd_urusan1 as kode ,a.nm_urusan1 as nama,
isnull(ang_pend,0) as ang_pend, ISNULL(ang_peg,0) as ang_peg, ISNULL(ang_brjs,0) ang_brjs, ISNULL(ang_modal,0) ang_modal, 
ISNULL(ang_bunga,0) as ang_bunga, ISNULL(ang_subsidi,0) as ang_subsidi, ISNULL(ang_hibah,0) ang_hibah, ISNULL(ang_bansos,0) as ang_bansos, 
ISNULL(ang_bghasil,0) as ang_bghasil, ISNULL(ang_bantuan,0) as ang_bantuan, ISNULL(ang_takterduga,0) as ang_takterduga,
ISNULL(bel_pend,0) as bel_pend, ISNULL(bel_peg,0) as bel_peg, ISNULL(bel_brjs,0) bel_brjs, ISNULL(bel_modal,0) as bel_modal,
ISNULL(bel_bunga,0) as bel_bunga, ISNULL(bel_subsidi,0) as bel_subsidi, ISNULL(bel_hibah,0) as bel_hibah, ISNULL(bel_bansos,0) bel_bansos,
ISNULL(bel_bghasil,0) as bel_bghasil, ISNULL(bel_bantuan,0) as bel_bantuan, ISNULL(bel_takterduga,0) as bel_takterduga

FROM ms_urusan1  a 
LEFT JOIN
(
SELECT a.kode, 
ang_pend, ang_peg, ang_brjs, ang_modal, ang_bunga, ang_subsidi, ang_hibah, ang_bansos, ang_bghasil, ang_bantuan, ang_takterduga,
bel_pend, bel_peg, bel_brjs, bel_modal, bel_bunga, bel_subsidi, bel_hibah, bel_bansos, bel_bghasil, bel_bantuan, bel_takterduga
FROM (
SELECT LEFT(a.kd_skpd,1) as kode 
,SUM(CASE WHEN LEFT(a.kd_rek5,1) = '4' THEN a.$ang ELSE 0 END) AS ang_pend
,SUM(CASE WHEN LEFT(a.kd_rek5,3) IN ('511','521') THEN a.$ang ELSE 0 END) AS ang_peg
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '522' THEN a.$ang ELSE 0 END) AS ang_brjs
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '523' THEN a.$ang ELSE 0 END) AS ang_modal
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '512' THEN a.$ang ELSE 0 END) AS ang_bunga
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '513' THEN a.$ang ELSE 0 END) AS ang_subsidi
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '514' THEN a.$ang ELSE 0 END) AS ang_hibah
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '515' THEN a.$ang ELSE 0 END) AS ang_bansos
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '516' THEN a.$ang ELSE 0 END) AS ang_bghasil
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '517' THEN a.$ang ELSE 0 END) AS ang_bantuan
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '518' THEN a.$ang ELSE 0 END) AS ang_takterduga
FROM trdrka a GROUP BY LEFT(a.kd_skpd,1) ) a
LEFT JOIN 
(SELECT a.kode as kode 
,SUM(CASE WHEN LEFT(a.kd_rek,1) = '4' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
,SUM(CASE WHEN LEFT(a.kd_rek,3) IN ('511','521') THEN a.nilai ELSE 0 END) AS bel_peg
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '522' THEN a.nilai ELSE 0 END) AS bel_brjs
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '523' THEN a.nilai ELSE 0 END) AS bel_modal
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '512' THEN a.nilai ELSE 0 END) AS bel_bunga
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '513' THEN a.nilai ELSE 0 END) AS bel_subsidi
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '514' THEN a.nilai ELSE 0 END) AS bel_hibah
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '515' THEN a.nilai ELSE 0 END) AS bel_bansos
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '516' THEN a.nilai ELSE 0 END) AS bel_bghasil
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '517' THEN a.nilai ELSE 0 END) AS bel_bantuan
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '518' THEN a.nilai ELSE 0 END) AS bel_takterduga
FROM
(SELECT LEFT(a.kd_skpd,1) as kode ,LEFT(kd_rek5,3) kd_rek,SUM(realisasi) as nilai FROM (
SELECT a.kd_skpd, b.map_real kd_rek5,(b.debet - b.kredit) as realisasi FROM trhju_calk a INNER JOIN trdju_calk b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
WHERE LEFT(b.map_real,1) IN ('5','4') AND MONTH(a.tgl_voucher)<='$bulan' AND YEAR(a.tgl_voucher)='$lntahunang') a GROUP BY LEFT(a.kd_skpd,1) ,LEFT(kd_rek5,3))a
GROUP BY a.kode) b
ON a.kode=b.kode
) b ON a.kd_urusan1=b.kode
UNION ALL
SELECT a.kd_urusan as kode ,a.nm_urusan as nama,
isnull(ang_pend,0) as ang_pend, ISNULL(ang_peg,0) as ang_peg, ISNULL(ang_brjs,0) ang_brjs, ISNULL(ang_modal,0) ang_modal, 
ISNULL(ang_bunga,0) as ang_bunga, ISNULL(ang_subsidi,0) as ang_subsidi, ISNULL(ang_hibah,0) ang_hibah, ISNULL(ang_bansos,0) as ang_bansos, 
ISNULL(ang_bghasil,0) as ang_bghasil, ISNULL(ang_bantuan,0) as ang_bantuan, ISNULL(ang_takterduga,0) as ang_takterduga,
ISNULL(bel_pend,0) as bel_bend, ISNULL(bel_peg,0) as bel_peg, ISNULL(bel_brjs,0) bel_brjs, ISNULL(bel_modal,0) as bel_modal,
ISNULL(bel_bunga,0) as bel_bunga, ISNULL(bel_subsidi,0) as bel_subsidi, ISNULL(bel_hibah,0) as bel_hibah, ISNULL(bel_bansos,0) bel_bansos,
ISNULL(bel_bghasil,0) as bel_bghasil, ISNULL(bel_bantuan,0) as bel_bantuan, ISNULL(bel_takterduga,0) as bel_takterduga

FROM ms_urusan  a 
LEFT JOIN
(
SELECT a.kode, 
ang_pend, ang_peg, ang_brjs, ang_modal, ang_bunga, ang_subsidi, ang_hibah, ang_bansos, ang_bghasil, ang_bantuan, ang_takterduga,
bel_pend, bel_peg, bel_brjs, bel_modal, bel_bunga, bel_subsidi, bel_hibah, bel_bansos, bel_bghasil, bel_bantuan, bel_takterduga
FROM (
SELECT LEFT(a.kd_skpd,4) as kode 
,SUM(CASE WHEN LEFT(a.kd_rek5,1) = '4' THEN a.$ang ELSE 0 END) AS ang_pend
,SUM(CASE WHEN LEFT(a.kd_rek5,3) IN ('511','521') THEN a.$ang ELSE 0 END) AS ang_peg
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '522' THEN a.$ang ELSE 0 END) AS ang_brjs
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '523' THEN a.$ang ELSE 0 END) AS ang_modal
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '512' THEN a.$ang ELSE 0 END) AS ang_bunga
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '513' THEN a.$ang ELSE 0 END) AS ang_subsidi
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '514' THEN a.$ang ELSE 0 END) AS ang_hibah
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '515' THEN a.$ang ELSE 0 END) AS ang_bansos
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '516' THEN a.$ang ELSE 0 END) AS ang_bghasil
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '517' THEN a.$ang ELSE 0 END) AS ang_bantuan
,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '518' THEN a.$ang ELSE 0 END) AS ang_takterduga
FROM trdrka a GROUP BY LEFT(a.kd_skpd,4) ) a
LEFT JOIN 
(SELECT a.kode as kode 
,SUM(CASE WHEN LEFT(a.kd_rek,1) = '4' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
,SUM(CASE WHEN LEFT(a.kd_rek,3) IN ('511','521') THEN a.nilai ELSE 0 END) AS bel_peg
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '522' THEN a.nilai ELSE 0 END) AS bel_brjs
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '523' THEN a.nilai ELSE 0 END) AS bel_modal
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '512' THEN a.nilai ELSE 0 END) AS bel_bunga
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '513' THEN a.nilai ELSE 0 END) AS bel_subsidi
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '514' THEN a.nilai ELSE 0 END) AS bel_hibah
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '515' THEN a.nilai ELSE 0 END) AS bel_bansos
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '516' THEN a.nilai ELSE 0 END) AS bel_bghasil
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '517' THEN a.nilai ELSE 0 END) AS bel_bantuan
,SUM(CASE WHEN LEFT(a.kd_rek,3) = '518' THEN a.nilai ELSE 0 END) AS bel_takterduga
FROM
(SELECT LEFT(a.kd_skpd,4) as kode ,LEFT(kd_rek5,3) kd_rek,SUM(realisasi) as nilai FROM (
SELECT a.kd_skpd, b.map_real kd_rek5,(b.debet - b.kredit) as realisasi FROM trhju a INNER JOIN trdju b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
WHERE LEFT(b.map_real,1) IN ('5','4') AND MONTH(a.tgl_voucher)<='$bulan' AND YEAR(a.tgl_voucher)='$lntahunang') a GROUP BY LEFT(a.kd_skpd,4) ,LEFT(kd_rek5,3))a
GROUP BY a.kode) b
ON a.kode=b.kode
) b ON a.kd_urusan=b.kode
			UNION ALL
			SELECT a.kd_org as kode ,a.nm_org as nama,
			isnull(ang_pend,0) as ang_pend, ISNULL(ang_peg,0) as ang_peg, ISNULL(ang_brjs,0) ang_brjs, ISNULL(ang_modal,0) ang_modal, 
			ISNULL(ang_bunga,0) as ang_bunga, ISNULL(ang_subsidi,0) as ang_subsidi, ISNULL(ang_hibah,0) ang_hibah, ISNULL(ang_bansos,0) as ang_bansos, 
			ISNULL(ang_bghasil,0) as ang_bghasil, ISNULL(ang_bantuan,0) as ang_bantuan, ISNULL(ang_takterduga,0) as ang_takterduga,
			ISNULL(bel_pend,0) as bel_bend, ISNULL(bel_peg,0) as bel_peg, ISNULL(bel_brjs,0) bel_brjs, ISNULL(bel_modal,0) as bel_modal,
			ISNULL(bel_bunga,0) as bel_bunga, ISNULL(bel_subsidi,0) as bel_subsidi, ISNULL(bel_hibah,0) as bel_hibah, ISNULL(bel_bansos,0) bel_bansos,
			ISNULL(bel_bghasil,0) as bel_bghasil, ISNULL(bel_bantuan,0) as bel_bantuan, ISNULL(bel_takterduga,0) as bel_takterduga

			FROM ms_organisasi a 
			LEFT JOIN
			(
			SELECT a.kode, 
			ang_pend, ang_peg, ang_brjs, ang_modal, ang_bunga, ang_subsidi, ang_hibah, ang_bansos, ang_bghasil, ang_bantuan, ang_takterduga,
			bel_pend, bel_peg, bel_brjs, bel_modal, bel_bunga, bel_subsidi, bel_hibah, bel_bansos, bel_bghasil, bel_bantuan, bel_takterduga
			FROM (
			SELECT LEFT(a.kd_skpd,7) as kode 
			,SUM(CASE WHEN LEFT(a.kd_rek5,1) = '4' THEN a.$ang ELSE 0 END) AS ang_pend
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) IN ('511','521') THEN a.$ang ELSE 0 END) AS ang_peg
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '522' THEN a.$ang ELSE 0 END) AS ang_brjs
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '523' THEN a.$ang ELSE 0 END) AS ang_modal
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '512' THEN a.$ang ELSE 0 END) AS ang_bunga
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '513' THEN a.$ang ELSE 0 END) AS ang_subsidi
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '514' THEN a.$ang ELSE 0 END) AS ang_hibah
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '515' THEN a.$ang ELSE 0 END) AS ang_bansos
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '516' THEN a.$ang ELSE 0 END) AS ang_bghasil
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '517' THEN a.$ang ELSE 0 END) AS ang_bantuan
			,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '518' THEN a.$ang ELSE 0 END) AS ang_takterduga
			FROM trdrka a GROUP BY LEFT(a.kd_skpd,7) ) a
			LEFT JOIN 
			(SELECT a.kode as kode 
			,SUM(CASE WHEN LEFT(a.kd_rek,1) = '4' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
			,SUM(CASE WHEN LEFT(a.kd_rek,3) IN ('511','521') THEN a.nilai ELSE 0 END) AS bel_peg
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '522' THEN a.nilai ELSE 0 END) AS bel_brjs
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '523' THEN a.nilai ELSE 0 END) AS bel_modal
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '512' THEN a.nilai ELSE 0 END) AS bel_bunga
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '513' THEN a.nilai ELSE 0 END) AS bel_subsidi
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '514' THEN a.nilai ELSE 0 END) AS bel_hibah
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '515' THEN a.nilai ELSE 0 END) AS bel_bansos
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '516' THEN a.nilai ELSE 0 END) AS bel_bghasil
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '517' THEN a.nilai ELSE 0 END) AS bel_bantuan
			,SUM(CASE WHEN LEFT(a.kd_rek,3) = '518' THEN a.nilai ELSE 0 END) AS bel_takterduga
			FROM
			(SELECT LEFT(a.kd_skpd,7) as kode ,LEFT(kd_rek5,3) kd_rek,SUM(realisasi) as nilai FROM (
			SELECT a.kd_skpd, b.map_real kd_rek5,(b.debet - b.kredit) as realisasi FROM trhju a INNER JOIN trdju b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
			WHERE LEFT(b.map_real,1) IN ('5','4') AND MONTH(a.tgl_voucher)<='$bulan' AND YEAR(a.tgl_voucher)='$lntahunang') a GROUP BY LEFT(a.kd_skpd,7) ,LEFT(kd_rek5,3))a
			GROUP BY a.kode) b
			ON a.kode=b.kode
			) b ON a.kd_org=b.kode
			
					UNION ALL
					SELECT a.kd_skpd as kode ,a.nm_skpd as nama,
					ang_pend, ang_peg, ang_brjs, ang_modal, ang_bunga, ang_subsidi, ang_hibah, ang_bansos, ang_bghasil, ang_bantuan, ang_takterduga,
					bel_pend, bel_peg, bel_brjs, bel_modal, bel_bunga, bel_subsidi, bel_hibah, bel_bansos, bel_bghasil, bel_bantuan, bel_takterduga

					FROM 
					ms_skpd a
					LEFT JOIN
					(
					SELECT a.kd_skpd, 
					ang_pend, ang_peg, ang_brjs, ang_modal, ang_bunga, ang_subsidi, ang_hibah, ang_bansos, ang_bghasil, ang_bantuan, ang_takterduga,
					bel_pend, bel_peg, bel_brjs, bel_modal, bel_bunga, bel_subsidi, bel_hibah, bel_bansos, bel_bghasil, bel_bantuan, bel_takterduga
					FROM (
					SELECT a.kd_skpd
					,SUM(CASE WHEN LEFT(a.kd_rek5,1) = '4' THEN a.$ang ELSE 0 END) AS ang_pend
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) IN ('511','521') THEN a.$ang ELSE 0 END) AS ang_peg
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '522' THEN a.$ang ELSE 0 END) AS ang_brjs
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '523' THEN a.$ang ELSE 0 END) AS ang_modal
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '512' THEN a.$ang ELSE 0 END) AS ang_bunga
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '513' THEN a.$ang ELSE 0 END) AS ang_subsidi
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '514' THEN a.$ang ELSE 0 END) AS ang_hibah
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '515' THEN a.$ang ELSE 0 END) AS ang_bansos
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '516' THEN a.$ang ELSE 0 END) AS ang_bghasil
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '517' THEN a.$ang ELSE 0 END) AS ang_bantuan
					,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '518' THEN a.$ang ELSE 0 END) AS ang_takterduga
					FROM trdrka a GROUP BY a.kd_skpd) a
					LEFT JOIN 
					(SELECT a.kd_skpd
					,SUM(CASE WHEN LEFT(a.kd_rek,1) = '4' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
					,SUM(CASE WHEN LEFT(a.kd_rek,3) IN ('511','521') THEN a.nilai ELSE 0 END) AS bel_peg
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '522' THEN a.nilai ELSE 0 END) AS bel_brjs
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '523' THEN a.nilai ELSE 0 END) AS bel_modal
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '512' THEN a.nilai ELSE 0 END) AS bel_bunga
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '513' THEN a.nilai ELSE 0 END) AS bel_subsidi
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '514' THEN a.nilai ELSE 0 END) AS bel_hibah
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '515' THEN a.nilai ELSE 0 END) AS bel_bansos
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '516' THEN a.nilai ELSE 0 END) AS bel_bghasil
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '517' THEN a.nilai ELSE 0 END) AS bel_bantuan
					,SUM(CASE WHEN LEFT(a.kd_rek,3) = '518' THEN a.nilai ELSE 0 END) AS bel_takterduga
					FROM
					(SELECT kd_skpd,LEFT(kd_rek5,3) kd_rek,SUM(realisasi) as nilai FROM (
					SELECT a.kd_skpd, b.map_real kd_rek5,(b.debet - b.kredit) as realisasi FROM trhju a INNER JOIN trdju b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
					WHERE LEFT(b.map_real,1) IN ('5','4') AND MONTH(a.tgl_voucher)<='$bulan' AND YEAR(a.tgl_voucher)='$lntahunang') a GROUP BY kd_skpd,LEFT(kd_rek5,3))a
					GROUP BY a.kd_skpd) b
					ON a.kd_skpd=b.kd_skpd
					) b ON a.kd_skpd=b.kd_skpd
					order by kode
					";
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
                       $no=$no+1;
					   $kode = $row->kode;
					   $nama = $row->nama;
                       $ang_pend = $row->ang_pend;
                       $ang_peg = $row->ang_peg;
                       $ang_brjs = $row->ang_brjs;
                       $ang_modal = $row->ang_modal;
                       $ang_bunga = $row->ang_bunga;
                       $ang_subsidi = $row->ang_subsidi;
                       $ang_hibah = $row->ang_hibah;
                       $ang_bansos = $row->ang_bansos;
                       $ang_bghasil = $row->ang_bghasil;
                       $ang_bantuan  = $row->ang_bantuan;
                       $ang_takterduga = $row->ang_takterduga;
                       $bel_pend = $row->bel_pend;
                       $bel_peg = $row->bel_peg;
                       $bel_brjs = $row->bel_brjs;
                       $bel_modal = $row->bel_modal;
                       $bel_bunga = $row->bel_bunga;
                       $bel_subsidi = $row->bel_subsidi;
                       $bel_hibah = $row->bel_hibah;
                       $bel_bansos = $row->bel_bansos;
                       $bel_bghasil = $row->bel_bghasil;
                       $bel_bantuan = $row->bel_bantuan;
                       $bel_takterduga = $row->bel_takterduga;
					   
					   $tot_ang=$ang_peg+$ang_brjs+$ang_modal+$ang_bunga+$ang_subsidi+$ang_hibah+$ang_bansos+$ang_bghasil+$ang_bantuan+$ang_takterduga;
					   $tot_bel=$bel_peg+$bel_brjs+$bel_modal+$bel_bunga+$bel_subsidi+$bel_hibah+$bel_bansos+$bel_bghasil+$bel_bantuan+$bel_takterduga;
                       $per_pend  = $ang_pend==0 || $ang_peg == '' ? 0 :$bel_pend/$ang_pend*100;
                       $per_bel  = $tot_ang==0 || $tot_ang == '' ? 0 :$tot_bel/$tot_ang*100;

					 $cRet .='<tr>
							   <td align="left" valign="top" style="font-size:12px">'.$kode.'</td> 
							   <td align="left"  valign="top" style="font-size:12px">'.strtoupper($nama).'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_pend, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($bel_pend, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($bel_pend-$ang_pend, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($per_pend, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_peg, "2", ",", ".").'<br>
																					'.number_format($ang_brjs, "2", ",", ".").'<br>
																					'.number_format($ang_modal, "2", ",", ".").'<br>
																					'.number_format($ang_bunga, "2", ",", ".").'<br>
																					'.number_format($ang_subsidi, "2", ",", ".").'<br>
																					'.number_format($ang_hibah, "2", ",", ".").'<br>
																					'.number_format($ang_bansos, "2", ",", ".").'<br>
																					'.number_format($ang_bghasil, "2", ",", ".").'<br>
																					'.number_format($ang_bantuan, "2", ",", ".").'<br>
																					'.number_format($ang_takterduga, "2", ",", ".").'
							   </td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($tot_ang, "2", ",", ".").'</td> 
								 <td align="right" valign="top" style="font-size:12px">'.number_format($bel_peg, "2", ",", ".").'<br>
																					'.number_format($bel_brjs, "2", ",", ".").'<br>
																					'.number_format($bel_modal, "2", ",", ".").'<br>
																					'.number_format($bel_bunga, "2", ",", ".").'<br>
																					'.number_format($bel_subsidi, "2", ",", ".").'<br>
																					'.number_format($bel_hibah, "2", ",", ".").'<br>
																					'.number_format($bel_bansos, "2", ",", ".").'<br>
																					'.number_format($bel_bghasil, "2", ",", ".").'<br>
																					'.number_format($bel_bantuan, "2", ",", ".").'<br>
																					'.number_format($bel_takterduga, "2", ",", ".").'
							   </td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($tot_bel, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($tot_bel-$tot_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($per_bel, "2", ",", ".").'</td> 
							</tr>'; 
					}
			
			//TOTAL		
			$sql2 = "select isnull(sum(ang_pend),0) ang_pend, isnull(sum(ang_peg),0) ang_peg, isnull(sum(ang_brjs),0) ang_brjs, isnull(sum(ang_modal),0) ang_modal,
						isnull(sum(ang_bunga),0) ang_bunga, isnull(sum(ang_subsidi),0) ang_subsidi, isnull(sum(ang_hibah),0) ang_hibah, 
						isnull(sum(ang_bansos),0) ang_bansos, isnull(sum(ang_bghasil),0) ang_bghasil, isnull(sum(ang_bantuan),0) ang_bantuan,
						isnull(sum(ang_takterduga),0) ang_takterduga, isnull(sum(bel_pend),0) bel_pend, isnull(sum(bel_peg),0) bel_peg, 
						isnull(sum(bel_brjs),0) bel_brjs, isnull(sum(bel_modal),0) bel_modal, isnull(sum(bel_bunga),0) bel_bunga,
						isnull(sum(bel_subsidi),0) bel_subsidi, isnull(sum(bel_hibah),0) bel_hibah, isnull(sum(bel_bansos),0) bel_bansos,
						isnull(sum(bel_bghasil),0) bel_bghasil, isnull(sum(bel_bantuan),0) bel_bantuan, isnull(sum(bel_takterduga),0) bel_takterduga
						from ( SELECT a.kd_urusan1 as kode ,a.nm_urusan1 as nama,
								isnull(ang_pend,0) as ang_pend, ISNULL(ang_peg,0) as ang_peg, ISNULL(ang_brjs,0) ang_brjs, ISNULL(ang_modal,0) ang_modal, 
								ISNULL(ang_bunga,0) as ang_bunga, ISNULL(ang_subsidi,0) as ang_subsidi, ISNULL(ang_hibah,0) ang_hibah, ISNULL(ang_bansos,0) as ang_bansos, 
								ISNULL(ang_bghasil,0) as ang_bghasil, ISNULL(ang_bantuan,0) as ang_bantuan, ISNULL(ang_takterduga,0) as ang_takterduga,
								ISNULL(bel_pend,0) as bel_pend, ISNULL(bel_peg,0) as bel_peg, ISNULL(bel_brjs,0) bel_brjs, ISNULL(bel_modal,0) as bel_modal,
								ISNULL(bel_bunga,0) as bel_bunga, ISNULL(bel_subsidi,0) as bel_subsidi, ISNULL(bel_hibah,0) as bel_hibah, ISNULL(bel_bansos,0) bel_bansos,
								ISNULL(bel_bghasil,0) as bel_bghasil, ISNULL(bel_bantuan,0) as bel_bantuan, ISNULL(bel_takterduga,0) as bel_takterduga

							FROM ms_urusan1  a 
							LEFT JOIN
							(
							SELECT a.kode, 
							ang_pend, ang_peg, ang_brjs, ang_modal, ang_bunga, ang_subsidi, ang_hibah, ang_bansos, ang_bghasil, ang_bantuan, ang_takterduga,
							bel_pend, bel_peg, bel_brjs, bel_modal, bel_bunga, bel_subsidi, bel_hibah, bel_bansos, bel_bghasil, bel_bantuan, bel_takterduga
							FROM (
							SELECT LEFT(a.kd_skpd,1) as kode 
							,SUM(CASE WHEN LEFT(a.kd_rek5,1) = '4' THEN a.$ang ELSE 0 END) AS ang_pend
							,SUM(CASE WHEN LEFT(a.kd_rek5,3) IN ('511','521') THEN a.$ang ELSE 0 END) AS ang_peg
							,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '522' THEN a.$ang ELSE 0 END) AS ang_brjs
							,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '523' THEN a.$ang ELSE 0 END) AS ang_modal
							,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '512' THEN a.$ang ELSE 0 END) AS ang_bunga
							,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '513' THEN a.$ang ELSE 0 END) AS ang_subsidi
							,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '514' THEN a.$ang ELSE 0 END) AS ang_hibah
							,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '515' THEN a.$ang ELSE 0 END) AS ang_bansos
							,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '516' THEN a.$ang ELSE 0 END) AS ang_bghasil
							,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '517' THEN a.$ang ELSE 0 END) AS ang_bantuan
							,SUM(CASE WHEN LEFT(a.kd_rek5,3) = '518' THEN a.$ang ELSE 0 END) AS ang_takterduga
							FROM trdrka a GROUP BY LEFT(a.kd_skpd,1) ) a
							LEFT JOIN 
							(SELECT a.kode as kode 
							,SUM(CASE WHEN LEFT(a.kd_rek,1) = '4' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
							,SUM(CASE WHEN LEFT(a.kd_rek,3) IN ('511','521') THEN a.nilai ELSE 0 END) AS bel_peg
							,SUM(CASE WHEN LEFT(a.kd_rek,3) = '522' THEN a.nilai ELSE 0 END) AS bel_brjs
							,SUM(CASE WHEN LEFT(a.kd_rek,3) = '523' THEN a.nilai ELSE 0 END) AS bel_modal
							,SUM(CASE WHEN LEFT(a.kd_rek,3) = '512' THEN a.nilai ELSE 0 END) AS bel_bunga
							,SUM(CASE WHEN LEFT(a.kd_rek,3) = '513' THEN a.nilai ELSE 0 END) AS bel_subsidi
							,SUM(CASE WHEN LEFT(a.kd_rek,3) = '514' THEN a.nilai ELSE 0 END) AS bel_hibah
							,SUM(CASE WHEN LEFT(a.kd_rek,3) = '515' THEN a.nilai ELSE 0 END) AS bel_bansos
							,SUM(CASE WHEN LEFT(a.kd_rek,3) = '516' THEN a.nilai ELSE 0 END) AS bel_bghasil
							,SUM(CASE WHEN LEFT(a.kd_rek,3) = '517' THEN a.nilai ELSE 0 END) AS bel_bantuan
							,SUM(CASE WHEN LEFT(a.kd_rek,3) = '518' THEN a.nilai ELSE 0 END) AS bel_takterduga
							FROM
							(SELECT LEFT(a.kd_skpd,1) as kode ,LEFT(kd_rek5,3) kd_rek,SUM(realisasi) as nilai FROM (
							SELECT a.kd_skpd, b.map_real kd_rek5,(b.debet - b.kredit) as realisasi FROM trhju a INNER JOIN trdju b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
							WHERE LEFT(b.map_real,1) IN ('5','4') AND MONTH(a.tgl_voucher)<='$bulan' AND YEAR(a.tgl_voucher)='$lntahunang') a GROUP BY LEFT(a.kd_skpd,1) ,LEFT(kd_rek5,3))a
							GROUP BY a.kode) b
							ON a.kode=b.kode
							) b ON a.kd_urusan1=b.kode ) z";
				
				$hasil2 = $this->db->query($sql2);
                    foreach ($hasil2->result() as $row2)
                    {
					
					   $ang_pend2 = $row2->ang_pend;
                       $ang_peg2 = $row2->ang_peg;
                       $ang_brjs2 = $row2->ang_brjs;
                       $ang_modal2 = $row2->ang_modal;
                       $ang_bunga2 = $row2->ang_bunga;
                       $ang_subsidi2 = $row2->ang_subsidi;
                       $ang_hibah2 = $row2->ang_hibah;
                       $ang_bansos2 = $row2->ang_bansos;
                       $ang_bghasil2 = $row2->ang_bghasil;
                       $ang_bantuan2  = $row2->ang_bantuan;
                       $ang_takterduga2 = $row2->ang_takterduga;
                       $bel_pend2 = $row2->bel_pend;
                       $bel_peg2 = $row2->bel_peg;
                       $bel_brjs2 = $row2->bel_brjs;
                       $bel_modal2 = $row2->bel_modal;
                       $bel_bunga2 = $row2->bel_bunga;
                       $bel_subsidi2 = $row2->bel_subsidi;
                       $bel_hibah2 = $row2->bel_hibah;
                       $bel_bansos2 = $row2->bel_bansos;
                       $bel_bghasil2 = $row2->bel_bghasil;
                       $bel_bantuan2 = $row2->bel_bantuan;
                       $bel_takterduga2 = $row2->bel_takterduga;
					   
					   $tot_ang2=$ang_peg2+$ang_brjs2+$ang_modal2+$ang_bunga2+$ang_subsidi2+$ang_hibah2+$ang_bansos2+$ang_bghasil2+$ang_bantuan2+$ang_takterduga2;
					   $tot_bel2=$bel_peg2+$bel_brjs2+$bel_modal2+$bel_bunga2+$bel_subsidi2+$bel_hibah2+$bel_bansos2+$bel_bghasil2+$bel_bantuan2+$bel_takterduga2;
                       $per_pend2  = $ang_pend2==0 || $ang_peg2 == '' ? 0 :$bel_pend2/$ang_pend2*100;
                       $per_bel2  = $tot_ang2==0 || $tot_ang2 == '' ? 0 :$tot_bel2/$tot_ang2*100;

					 $cRet .='<tr>
							   <td align="center" valign="top" style="font-size:12px" colspan="2"><b>TOTAL</b></td> 
							   
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_pend2, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($bel_pend2, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($bel_pend2-$ang_pend2, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($per_pend2, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_peg2, "2", ",", ".").'<br>
																					'.number_format($ang_brjs2, "2", ",", ".").'<br>
																					'.number_format($ang_modal2, "2", ",", ".").'<br>
																					'.number_format($ang_bunga2, "2", ",", ".").'<br>
																					'.number_format($ang_subsidi2, "2", ",", ".").'<br>
																					'.number_format($ang_hibah2, "2", ",", ".").'<br>
																					'.number_format($ang_bansos2, "2", ",", ".").'<br>
																					'.number_format($ang_bghasil2, "2", ",", ".").'<br>
																					'.number_format($ang_bantuan2, "2", ",", ".").'<br>
																					'.number_format($ang_takterduga2, "2", ",", ".").'</b>
							   </td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_ang2, "2", ",", ".").'</b></td> 
								 <td align="right" valign="top" style="font-size:12px"><b>'.number_format($bel_peg2, "2", ",", ".").'<br>
																					'.number_format($bel_brjs2, "2", ",", ".").'<br>
																					'.number_format($bel_modal2, "2", ",", ".").'<br>
																					'.number_format($bel_bunga2, "2", ",", ".").'<br>
																					'.number_format($bel_subsidi2, "2", ",", ".").'<br>
																					'.number_format($bel_hibah2, "2", ",", ".").'<br>
																					'.number_format($bel_bansos2, "2", ",", ".").'<br>
																					'.number_format($bel_bghasil2, "2", ",", ".").'<br>
																					'.number_format($bel_bantuan2, "2", ",", ".").'<br>
																					'.number_format($bel_takterduga2, "2", ",", ".").'</b>
							   </td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_bel2, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_bel2-$tot_ang2, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($per_bel2, "2", ",", ".").'</b></td> 
							</tr>'; 
					}
			
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
			$data['prev']= $cRet;    
            $judul='Perda_LampI';
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
	
	
	function cetak_perda_lampI_2_org($bulan='',$ctk='',$anggaran='',$kd_skpd='',$jenis='',$tglttd='', $ttd='', $js=''){
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
	
	if($bulan== 12){
			$sumber_data= "_at";
		}else{
			$sumber_data= "";
		}
		
		if ($js=='1'){                               
            $where= "WHERE left(kd_skpd,7)='$kd_skpd' AND kd_skpd<>'3.13.01.17'";
        } else {                               
            $where= "WHERE left(kd_skpd,7)='$kd_skpd' AND kd_skpd<>'3.13.01.17'";
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
	}				$nip = '';
	
	$sqlnogub="SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $ket_lampiran      = strtoupper("Lampiran I.2");
                    $ket_perda         = strtoupper($rowsc->ket_perda);
                    $ket_perda_no      = strtoupper($rowsc->ket_perda_no);
                    $ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
                 }
		 
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="6%" valign="top" align="right" >'.$ket_lampiran.'</TD>
						<TD  width="1%" valign="top" align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_perda.'<br>'.$ket_perda_no.'<br>'.$ket_perda_tentang.' '. $lntahunang.'</TD>
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
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,1)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,1),'nm_urusan1','ms_urusan1','kd_urusan1')." </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,4)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,4),'nm_urusan','ms_urusan','kd_urusan')."</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,7)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,7),'nm_org','ms_organisasi','kd_org')."</td>
					</tr>
                    </TABLE>";
		
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH(BERKURANG)</b></td>
                    <td rowspan=\"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DASAR HUKUM</b></td>
				</tr>
				<tr>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Rp.</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
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
				
				$sql="SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_pend_n$sumber_data($bulan,$anggaran,$lntahunang) $where ORDER BY kd_kegiatan,kd_rek";
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
					
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_n$sumber_data($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)='3'";
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
					
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_objek_n$sumber_data($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)='3'";
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
					
					$sql="SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci_n$sumber_data($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek) <='$jenis' 
					and nm_rek <> 'NON PROGRAM' and anggaran<>0
					ORDER BY kd_kegiatan,kd_rek";
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
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan,<br><br><br><br><br>$nama<br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
			$data['prev']= $cRet;    
            $judul='PERDA_LAMP_I.2 ';
			switch ($ctk){
				case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
				case 1;
				ini_set('memory_limit', '-1');
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
	
	
	
	function cetak_perda_lampI_sap_spj($bulan='',$ctk='',$anggaran='',$jenis='',$kd_skpd='',$kd_org='',$ttd='',$tglttd='',$flaporan=''){
        $lntahunang = $this->session->userdata('pcThang');       
		switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "TRIWULAN I";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "SEMESTER I";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "TRIWULAN III";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "TAHUN ANGGARAN $lntahunang";
        break;
    }
		
		if($bulan== 12){
			$sumber_data= "_at";
		}else{
			$sumber_data= "";
		}
		
		if($kd_org<>'-'){
			$where="AND LEFT(kd_skpd,7)='$kd_org'";
		}else if($kd_skpd<>'-'){
			$where="AND kd_skpd='$kd_skpd'";
		}else{
			$where="";
		}
		
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
		if($ttd=='-'){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}
		
			$sqlnogub="SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $ket_lampiran      = strtoupper("Lampiran I");
                    $ket_perda         = strtoupper($rowsc->ket_perda);
                    $ket_perda_no      = strtoupper($rowsc->ket_perda_no);
                    $ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
                 }
		 
		if($flaporan=='1'){		 
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >'.$ket_lampiran.'</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >'.$ket_perda.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_perda_no.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_perda_tentang.'</TD>
					</TR>
					</TABLE><br/>';
		}else{
			$cRet ='';
		}
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$judul</b></tr>
					</TABLE>";
		$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">";
		
				
				if($kd_org<>'-'){
					$cRet .="<tr>
				<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_org,7)." - ".$this->tukd_model->get_nama($this->left($kd_org,7),'nm_org','ms_organisasi','kd_org')."</td>
					</tr>";
				}else if($kd_skpd<>'-'){
					$cRet .="<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,10)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,10),'nm_skpd','ms_skpd','kd_skpd')."</td>
					</tr>";
				}else{
					
				}
				
				
		$cRet .="</TABLE>";
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
				</tr>
				<tr>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";
				
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet-kredit) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_surplus = $row->ang_surplus;
                       $nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $nil_surplus-$ang_surplus;
					if(($ang_surplus==0) || ($ang_surplus=='')){
						$persen_surplus=0;
					} else{
					$persen_surplus = $nil_surplus/$ang_surplus*100;
					}
					$hasil->free_result();        
					if($ang_surplus<0){
						$ang_surplus1=$ang_surplus*-1;
						$a='(';
						$b=')';
					} else{
						$ang_surplus1=$ang_surplus;
						$a='';
						$b='';
					}
					if($nil_surplus<0){
						$nil_surplus1=$nil_surplus*-1;
						$c='(';
						$d=')';
					} else{
						$nil_surplus1=$nil_surplus;
						$c='';
						$d='';
					}
					if($sisa_surplus<0){
						$sisa_surplus1=$sisa_surplus*-1;
						$e='(';
						$f=')';
					} else{
						$sisa_surplus1=$sisa_surplus;
						$e='';
						$f='';
					}
			
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (debet-kredit) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_netto = $row->ang_netto;
                       $nil_netto = $row->nil_netto;
					}
					$sisa_netto = $nil_netto-$ang_netto;
					if(($ang_netto==0) || ($ang_netto=='')){
						$persen_netto=0;
					} else{
					$persen_netto = $nil_netto/$ang_netto*100;
					}
					$hasil->free_result();  
					if($ang_netto<0){
						$ang_netto1=$ang_netto*-1;
						$g='(';
						$h=')';
					} else{
						$ang_netto1=$ang_netto;
						$g='';
						$h='';
					}
					if($nil_netto<0){
						$nil_netto1=$nil_netto*-1;
						$i='(';
						$j=')';
					} else{
						$nil_netto1=$nil_netto;
						$i='';
						$j='';
					}
					if($sisa_netto<0){
						$sisa_netto1=$sisa_netto*-1;
						$k='(';
						$l=')';
					} else{
						$sisa_netto1=$sisa_netto;
						$k='';
						$l='';
					}	
					
					$ang_silpa = $ang_surplus+$ang_netto;
					$nil_silpa = $nil_surplus+$nil_netto;
					$sisa_silpa = $nil_silpa-$ang_silpa;
					if($ang_silpa==0){
						$persen_silpa=0;
					}else{
					$persen_silpa = $nil_silpa/$ang_silpa*100;
					}
					if($ang_silpa<0){
						$ang_silpa1=$ang_silpa*-1;
						$m='(';
						$n=')';
					} else{
						$ang_silpa1=$ang_silpa;
						$m='';
						$n='';
					}
					if($nil_silpa<0){
						$nil_silpa1=$nil_silpa*-1;
						$o='(';
						$p=')';
					} else{
						$nil_silpa1=$nil_silpa;
						$o='';
						$p='';
					}
					if($sisa_silpa<0){
						$sisa_silpa1=$sisa_silpa*-1;
						$q='(';
						$r=')';
					} else{
						$sisa_silpa1=$sisa_silpa;
						$q='';
						$r='';
					}	
			$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi, jns_rek FROM map_lra_sap ORDER BY seq
					";
					$no=0;
					$tot_peg=0;
					$tot_brg=0;
					$tot_mod=0;
					$tot_bansos=0;
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
					   $jns_rek = $row->jns_rek;
					   
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
					$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4)) $where
					";
										
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
                       $nil_ang = $row->nil_ang;
					   $nilai = $row->nilai;
					}
					//coba
					if ($jns_rek=='1'){
						$sel = $nilai-$nil_ang;
					}else{
						$sel = $nil_ang-$nilai;
					}
					
					if($sel<0){
						$x = "(";
						$y = ")";
						$sel1 = $sel*-1;
					}else{
						$x = "";
						$y = "";
						$sel1 = $sel;
					}
					
					if(($nil_ang==0) || ($nil_ang=='')){
						$persen=0;
					} else{
					$persen = $nilai/$nil_ang*100;
					}
					 switch ($spasi) {
					 case 8:
                        $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$g.''.number_format($ang_netto1, "2", ",", ".").''.$h.'</b></td> 
							   <td align="right" valign="top" ><b>'.$i.''.number_format($nil_netto1, "2", ",", ".").''.$j.'</b></td> 
							   <td align="right" valign="top" ><b>'.$k.''.number_format($sisa_netto1, "2", ",", ".").''.$l.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_netto, "2", ",", ".").'</b></td> 
							</tr>'; 
                        break;
					 case 1:
                        $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>'; 
                        break;	
                    case 2:
                         $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					 case 3:
                         $cRet .='<tr>
							   <td align="left" valign="top">'.$kode.'</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
                        break;
					case 4:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 5:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.''.number_format($ang_surplus1, "2", ",", ".").''.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$c.''.number_format($nil_surplus1, "2", ",", ".").''.$d.'</b></td> 
							   <td align="right" valign="top"><b>'.$e.''.number_format($sisa_surplus1, "2", ",", ".").''.$f.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen_surplus, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 6;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$g.''.number_format($ang_netto1, "2", ",", ".").''.$h.'</b></td> 
							   <td align="right" valign="top" ><b>'.$i.''.number_format($nil_netto1, "2", ",", ".").''.$j.'</b></td> 
							   <td align="right" valign="top" ><b>'.$k.''.number_format($sisa_netto1, "2", ",", ".").''.$l.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_netto, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 7;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$m.''.number_format($ang_silpa1, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top" ><b>'.$o.''.number_format($nil_silpa1, "2", ",", ".").''.$p.'</b></td> 
							   <td align="right" valign="top" ><b>'.$q.''.number_format($sisa_silpa1, "2", ",", ".").''.$r.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_silpa, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					}
					}
			 
                      
		
			$cRet .="</table>";
			
			 $cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>
					</td>
				</tr>
				</table>";          
		
			$cRet .="</table>";
			
			
			$data['prev']= $cRet;    
            $judul='LRA_SAP ';
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
		
	function cetak_perda_lampI_sap_sp2d($bulan='',$ctk='',$anggaran='',$jenis='',$kd_skpd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		 switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "TRIWULAN I";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "SEMESTER I";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "TRIWULAN III";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "SEMESTER II";
        break;
    }
		if ($kd_skpd=='-'){                               
            $where="";            
        } else{
			$where="AND kd_skpd='$kd_skpd'";
		}
			
		if($jenis==2){
			$tabel='data_sp2d';
		  }else if($jenis==3){
			$tabel='data_sp2d_lunas';
		  } else if($jenis==4){
			$tabel='data_sp2d_advice';
		  }

		 $sqlnogub="SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $no_perda     = $rowsc->no_perda;
                    $tgl_perda     = $rowsc->tgl_perda;
                    $no_pergub     = $rowsc->no_pergub;
                    $tgl_pergub    = $rowsc->tgl_pergub;
                 }
		 if ($test===0){
			 $no_perda="";
			 $tgl_perda="";
			 $no_pergub="";
			 $tgl_pergub="";
		 }
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" > Lampiran I : </TD>
						<TD width="40%"  align="left" >Peraturan Daerah <br>Nomor : '.$no_perda.' <br>Tanggal: '.$tgl_perda.'</TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$judul</b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
				</tr>
				<tr>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";
				
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nilai) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_surplus = $row->ang_surplus;
                       $nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $nil_surplus-$ang_surplus;
					if(($ang_surplus==0) || ($ang_surplus=='')){
						$persen_surplus=0;
					} else{
					$persen_surplus = $nil_surplus/$ang_surplus *100;
					}	
					$hasil->free_result();        
					if($ang_surplus<0){
						$ang_surplus1=$ang_surplus*-1;
						$a='(';
						$b=')';
					} else{
						$ang_surplus1=$ang_surplus;
						$a='';
						$b='';
					}
					if($nil_surplus<0){
						$nil_surplus1=$nil_surplus*-1;
						$c='(';
						$d=')';
					} else{
						$nil_surplus1=$nil_surplus;
						$c='';
						$d='';
					}
					if($sisa_surplus<0){
						$sisa_surplus1=$sisa_surplus*-1;
						$e='(';
						$f=')';
					} else{
						$sisa_surplus1=$sisa_surplus;
						$e='';
						$f='';
					}
			
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nilai) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_netto = $row->ang_netto;
                       $nil_netto = $row->nil_netto;
					}
					$sisa_netto = $nil_netto-$ang_netto;
					if(($ang_netto==0) || ($ang_netto=='')){
						$persen_netto=0;
					} else{
					$persen_netto = $nil_netto/$ang_netto *100;
					}
					$hasil->free_result();  
					if($ang_netto<0){
						$ang_netto1=$ang_netto*-1;
						$g='(';
						$h=')';
					} else{
						$ang_netto1=$ang_netto;
						$g='';
						$h='';
					}
					if($nil_netto<0){
						$nil_netto1=$nil_netto*-1;
						$i='(';
						$j=')';
					} else{
						$nil_netto1=$nil_netto;
						$i='';
						$j='';
					}
					if($sisa_netto<0){
						$sisa_netto1=$sisa_netto*-1;
						$k='(';
						$l=')';
					} else{
						$sisa_netto1=$sisa_netto;
						$k='';
						$l='';
					}	
					
					$ang_silpa = $ang_surplus+$ang_netto;
					$nil_silpa = $nil_surplus+$nil_netto;
					$sisa_silpa = $nil_silpa-$ang_silpa;
					if($ang_silpa==0){
						$persen_silpa=0;
					}else{
					$persen_silpa = $nil_silpa/$ang_silpa *100;
					}
					if($ang_silpa<0){
						$ang_silpa1=$ang_silpa*-1;
						$m='(';
						$n=')';
					} else{
						$ang_silpa1=$ang_silpa;
						$m='';
						$n='';
					}
					if($nil_silpa<0){
						$nil_silpa1=$nil_silpa*-1;
						$o='(';
						$p=')';
					} else{
						$nil_silpa1=$nil_silpa;
						$o='';
						$p='';
					}
					if($sisa_silpa<0){
						$sisa_silpa1=$sisa_silpa*-1;
						$q='(';
						$r=')';
					} else{
						$sisa_silpa1=$sisa_silpa;
						$q='';
						$r='';
					}	
			$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi, jns_rek FROM map_lra_sap ORDER BY seq
					";
					$no=0;
					$tot_peg=0;
					$tot_brg=0;
					$tot_mod=0;
					$tot_bansos=0;
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
                       //$jenis = $row->jenis;
                       $spasi = $row->spasi;
					   $jns_rek = $row->jns_rek;
					   
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
					$sql = "SELECT SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4)) $where
					";
										
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
                       $nil_ang = $row->nil_ang;
					   $nilai = $row->nilai;
					}
					//coba
					if ($jns_rek=='1'){
						$sel = $nilai-$nil_ang;
					}else{
						$sel = $nil_ang-$nilai;
					}
					
					if($sel<0){
						$x = "(";
						$y = ")";
						$sel1 = $sel*-1;
					}else{
						$x = "";
						$y = "";
						$sel1 = $sel;
					}
					
					if(($nil_ang==0) || ($nil_ang=='')){
						$persen=0;
					} else{
					$persen = $nilai/$nil_ang *100;
					}
					 switch ($spasi) {
					 case 1:
                        $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>'; 
                        break;	
                    case 2:
                         $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					 case 3:
                         $cRet .='<tr>
							   <td align="left" valign="top">'.$kode.'</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
                        break;
					case 4:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 5:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.''.number_format($ang_surplus1, "2", ",", ".").''.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$c.''.number_format($nil_surplus1, "2", ",", ".").''.$d.'</b></td> 
							   <td align="right" valign="top"><b>'.$e.''.number_format($sisa_surplus1, "2", ",", ".").''.$f.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen_surplus, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 6;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$g.''.number_format($ang_netto1, "2", ",", ".").''.$h.'</b></td> 
							   <td align="right" valign="top" ><b>'.$i.''.number_format($nil_netto1, "2", ",", ".").''.$j.'</b></td> 
							   <td align="right" valign="top" ><b>'.$k.''.number_format($sisa_netto1, "2", ",", ".").''.$l.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_netto, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 7;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$m.''.number_format($ang_silpa1, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top" ><b>'.$o.''.number_format($nil_silpa1, "2", ",", ".").''.$p.'</b></td> 
							   <td align="right" valign="top" ><b>'.$q.''.number_format($sisa_silpa1, "2", ",", ".").''.$r.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_silpa, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					}
					}
			 
                      
		
			$cRet .="</table>";
			$data['prev']= $cRet;    
            $judul='LRA_SAP ';
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
		
	function cetak_perda_lampI_permen_spj($bulan='',$ctk='',$anggaran='',$jenis='',$kd_skpd='',$kd_org='',$ttd='',$tglttd='',$flaporan=''){
        $lntahunang = $this->session->userdata('pcThang');       
		 switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "TRIWULAN I";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "SEMESTER I";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "TRIWULAN III";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "TAHUN ANGGARAN $lntahunang";
        break;
    }
		if($kd_org<>'-'){
			$where="AND LEFT(kd_skpd,7)='$kd_org'";
		}else if($kd_skpd<>'-'){
			$where="AND kd_skpd='$kd_skpd'";
		}else{
			$where="";
		}
		
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
		if($ttd=='-'){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}
		
	if($bulan== 12){
	$sumber_data= "_at";
	}else{
	$sumber_data= "";
	}	
		
		$sqlnogub="SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $ket_lampiran      = strtoupper("Lampiran I");
                    $ket_perda         = strtoupper($rowsc->ket_perda);
                    $ket_perda_no      = strtoupper($rowsc->ket_perda_no);
                    $ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
                 }
		 
		 if($flaporan==1){
			 $cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >'.$ket_lampiran.'</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >'.$ket_perda.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_perda_no.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_perda_tentang.'</TD>
					</TR>
					</TABLE><br/>';
		 }else{
			 $cRet ='';
		 }
		 
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$judul</b></tr>
					</TABLE>";
		$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">";
		
				
				if($kd_org<>'-'){
					$cRet .="<tr>
				<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_org,7)." - ".$this->tukd_model->get_nama($this->left($kd_org,7),'nm_org','ms_organisasi','kd_org')."</td>
					</tr>";
				}else if($kd_skpd<>'-'){
					$cRet .="<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,10)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,10),'nm_skpd','ms_skpd','kd_skpd')."</td>
					</tr>";
				}else{
					
				}
				
				
		$cRet .="</TABLE>";
		
		
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
				</tr>
				<tr>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";
				
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet-kredit) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_surplus = $row->ang_surplus;
                       $nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $nil_surplus-$ang_surplus;
						if(($ang_surplus==0) || ($ang_surplus=='')){
						$persen_surplus=0;
					} else{
					$persen_surplus = $nil_surplus/$ang_surplus*100;
					}	
										$hasil->free_result();        
					if($ang_surplus<0){
						$ang_surplus1=$ang_surplus*-1;
						$a='(';
						$b=')';
					} else{
						$ang_surplus1=$ang_surplus;
						$a='';
						$b='';
					}
					if($nil_surplus<0){
						$nil_surplus1=$nil_surplus*-1;
						$c='(';
						$d=')';
					} else{
						$nil_surplus1=$nil_surplus;
						$c='';
						$d='';
					}
					if($sisa_surplus<0){
						$sisa_surplus1=$sisa_surplus*-1;
						$e='(';
						$f=')';
					} else{
						$sisa_surplus1=$sisa_surplus;
						$e='';
						$f='';
					}
			
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (debet-kredit) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_netto = $row->ang_netto;
                       $nil_netto = $row->nil_netto;
					}
					$sisa_netto = $nil_netto-$ang_netto;
					if(($ang_netto==0) || ($ang_netto=='')){
						$persen_netto=0;
					} else{
					$persen_netto = $nil_netto/$ang_netto*100;
					}
					$hasil->free_result();  
					if($ang_netto<0){
						$ang_netto1=$ang_netto*-1;
						$g='(';
						$h=')';
					} else{
						$ang_netto1=$ang_netto;
						$g='';
						$h='';
					}
					if($nil_netto<0){
						$nil_netto1=$nil_netto*-1;
						$i='(';
						$j=')';
					} else{
						$nil_netto1=$nil_netto;
						$i='';
						$j='';
					}
					if($sisa_netto<0){
						$sisa_netto1=$sisa_netto*-1;
						$k='(';
						$l=')';
					} else{
						$sisa_netto1=$sisa_netto;
						$k='';
						$l='';
					}	
					
					$ang_silpa = $ang_surplus+$ang_netto;
					$nil_silpa = $nil_surplus+$nil_netto;
					$sisa_silpa = $nil_silpa-$ang_silpa;
					if($ang_silpa==0){
						$persen_silpa=0;
					}else{
					$persen_silpa = $nil_silpa/$ang_silpa*100;
					}
					if($ang_silpa<0){
						$ang_silpa1=$ang_silpa*-1;
						$m='(';
						$n=')';
					} else{
						$ang_silpa1=$ang_silpa;
						$m='';
						$n='';
					}
					if($nil_silpa<0){
						$nil_silpa1=$nil_silpa*-1;
						$o='(';
						$p=')';
					} else{
						$nil_silpa1=$nil_silpa;
						$o='';
						$p='';
					}
					if($sisa_silpa<0){
						$sisa_silpa1=$sisa_silpa*-1;
						$q='(';
						$r=')';
					} else{
						$sisa_silpa1=$sisa_silpa;
						$q='';
						$r='';
					}	
			$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi, jns_rek FROM map_lra_permen ORDER BY seq
					";
					$no=0;
					$tot_peg=0;
					$tot_brg=0;
					$tot_mod=0;
					$tot_bansos=0;
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
					   $jns_rek = $row->jns_rek;
					   
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
					$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4)) $where
					";
										
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
                       $nil_ang = $row->nil_ang;
					   $nilai = $row->nilai;
					}
					
					//coba
					if ($jns_rek=='1'){
						$sel = $nilai-$nil_ang;
					}else{
						$sel = $nil_ang-$nilai;
					}
					
					if($sel<0){
						$x = "(";
						$y = ")";
						$sel1 = $sel*-1;
					}else{
						$x = "";
						$y = "";
						$sel1 = $sel;
					}
					
					
					if(($nil_ang==0) || ($nil_ang=='')){
						$persen=0;
					} else{
					$persen = $nilai/$nil_ang*100;
					}
					 switch ($spasi) {
					 case 1:
                        $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>'; 
                        break;	
                    case 2:
                         $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					 case 3:
                         $cRet .='<tr>
							   <td align="left" valign="top">'.$kode.'</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
                        break;
					case 4:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 5:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.''.number_format($ang_surplus1, "2", ",", ".").''.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$c.''.number_format($nil_surplus1, "2", ",", ".").''.$d.'</b></td> 
							   <td align="right" valign="top"><b>'.$e.''.number_format($sisa_surplus1, "2", ",", ".").''.$f.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen_surplus, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 6;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$g.''.number_format($ang_netto1, "2", ",", ".").''.$h.'</b></td> 
							   <td align="right" valign="top" ><b>'.$i.''.number_format($nil_netto1, "2", ",", ".").''.$j.'</b></td> 
							   <td align="right" valign="top" ><b>'.$k.''.number_format($sisa_netto1, "2", ",", ".").''.$l.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_netto, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 7;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$m.''.number_format($ang_silpa1, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top" ><b>'.$o.''.number_format($nil_silpa1, "2", ",", ".").''.$p.'</b></td> 
							   <td align="right" valign="top" ><b>'.$q.''.number_format($sisa_silpa1, "2", ",", ".").''.$r.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_silpa, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					}
					}
			 
                      
		
			$cRet .="</table>";
			
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>
					</td>
				</tr>
				</table>";          
		
			$cRet .="</table>";
			
			$data['prev']= $cRet;    
            $judul='LRA_PERMEN ';
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
		
	function cetak_perda_lampI_permen_sp2d($bulan='',$ctk='',$anggaran='',$jenis='', $kd_skpd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		 switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "TRIWULAN I";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "SEMESTER I";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "TRIWULAN III";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "SEMESTER II";
        break;
    }
		
		if ($kd_skpd=='-'){                               
            $where="";            
        } else{
			$where="AND kd_skpd='$kd_skpd'";
		}
		
		if($jenis==2){
			$tabel='data_sp2d';
		  }else if($jenis==3){
			$tabel='data_sp2d_lunas';
		  } else if($jenis==4){
			$tabel='data_sp2d_advice';
		  }

		$sqlnogub="SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $no_perda     = $rowsc->no_perda;
                    $tgl_perda     = $rowsc->tgl_perda;
                    $no_pergub     = $rowsc->no_pergub;
                    $tgl_pergub    = $rowsc->tgl_pergub;
                 }
		 if ($test===0){
			 $no_perda="";
			 $tgl_perda="";
			 $no_pergub="";
			 $tgl_pergub="";
		 }
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" > Lampiran I : </TD>
						<TD width="40%"  align="left" >Peraturan Daerah <br>Nomor : '.$no_perda.'<br>Tanggal: '.$tgl_perda.'</TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$judul</b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
				</tr>
				<tr>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";
				
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nilai) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_surplus = $row->ang_surplus;
                       $nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $nil_surplus-$ang_surplus;
						if(($ang_surplus==0) || ($ang_surplus=='')){
						$persen_surplus=0;
					} else{
					$persen_surplus = $nil_surplus/$ang_surplus*100;
					}	
					$hasil->free_result();        
					if($ang_surplus<0){
						$ang_surplus1=$ang_surplus*-1;
						$a='(';
						$b=')';
					} else{
						$ang_surplus1=$ang_surplus;
						$a='';
						$b='';
					}
					if($nil_surplus<0){
						$nil_surplus1=$nil_surplus*-1;
						$c='(';
						$d=')';
					} else{
						$nil_surplus1=$nil_surplus;
						$c='';
						$d='';
					}
					if($sisa_surplus<0){
						$sisa_surplus1=$sisa_surplus*-1;
						$e='(';
						$f=')';
					} else{
						$sisa_surplus1=$sisa_surplus;
						$e='';
						$f='';
					}
			
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nilai) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran) WHERE LEFT(kd_rek5,2) IN ('61','62')  $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_netto = $row->ang_netto;
                       $nil_netto = $row->nil_netto;
					}
					$sisa_netto = $nil_netto-$ang_netto;
					if(($ang_netto==0) || ($ang_netto=='')){
						$persen_netto=0;
					} else{
					$persen_netto = $nil_netto/$ang_netto*100;
					}
					$hasil->free_result();  
					if($ang_netto<0){
						$ang_netto1=$ang_netto*-1;
						$g='(';
						$h=')';
					} else{
						$ang_netto1=$ang_netto;
						$g='';
						$h='';
					}
					if($nil_netto<0){
						$nil_netto1=$nil_netto*-1;
						$i='(';
						$j=')';
					} else{
						$nil_netto1=$nil_netto;
						$i='';
						$j='';
					}
					if($sisa_netto<0){
						$sisa_netto1=$sisa_netto*-1;
						$k='(';
						$l=')';
					} else{
						$sisa_netto1=$sisa_netto;
						$k='';
						$l='';
					}	
					
					$ang_silpa = $ang_surplus+$ang_netto;
					$nil_silpa = $nil_surplus+$nil_netto;
					$sisa_silpa = $nil_silpa-$ang_silpa;
					if($ang_silpa==0){
						$persen_silpa=0;
					}else{
					$persen_silpa = $nil_silpa/$ang_silpa*100;
					}
					if($ang_silpa<0){
						$ang_silpa1=$ang_silpa*-1;
						$m='(';
						$n=')';
					} else{
						$ang_silpa1=$ang_silpa;
						$m='';
						$n='';
					}
					if($nil_silpa<0){
						$nil_silpa1=$nil_silpa*-1;
						$o='(';
						$p=')';
					} else{
						$nil_silpa1=$nil_silpa;
						$o='';
						$p='';
					}
					if($sisa_silpa<0){
						$sisa_silpa1=$sisa_silpa*-1;
						$q='(';
						$r=')';
					} else{
						$sisa_silpa1=$sisa_silpa;
						$q='';
						$r='';
					}	
			$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi, jns_rek FROM map_lra_permen ORDER BY seq
					";
					$no=0;
					$tot_peg=0;
					$tot_brg=0;
					$tot_mod=0;
					$tot_bansos=0;
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
                       //$jenis = $row->jenis;
                       $spasi = $row->spasi;
					   $jns_rek = $row->jns_rek;
					   
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
					$sql = "SELECT SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4))  $where
					";
										
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
                       $nil_ang = $row->nil_ang;
					   $nilai = $row->nilai;
					}
					
					//coba
					if ($jns_rek=='1'){
						$sel = $nilai-$nil_ang;
					}else{
						$sel = $nil_ang-$nilai;
					}
					
					if($sel<0){
						$x = "(";
						$y = ")";
						$sel1 = $sel*-1;
					}else{
						$x = "";
						$y = "";
						$sel1 = $sel;
					}
					
					if(($nil_ang==0) || ($nil_ang=='')){
						$persen=0;
					} else{
					$persen = $nilai/$nil_ang*100;
					}
					 switch ($spasi) {
					 case 1:
                        $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>'; 
                        break;	
                    case 2:
                         $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					 case 3:
                         $cRet .='<tr>
							   <td align="left" valign="top">'.$kode.'</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
                        break;
					case 4:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 5:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.''.number_format($ang_surplus1, "2", ",", ".").''.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$c.''.number_format($nil_surplus1, "2", ",", ".").''.$d.'</b></td> 
							   <td align="right" valign="top"><b>'.$e.''.number_format($sisa_surplus1, "2", ",", ".").''.$f.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen_surplus, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 6;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$g.''.number_format($ang_netto1, "2", ",", ".").''.$h.'</b></td> 
							   <td align="right" valign="top" ><b>'.$i.''.number_format($nil_netto1, "2", ",", ".").''.$j.'</b></td> 
							   <td align="right" valign="top" ><b>'.$k.''.number_format($sisa_netto1, "2", ",", ".").''.$l.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_netto, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 7;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$m.''.number_format($ang_silpa1, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top" ><b>'.$o.''.number_format($nil_silpa1, "2", ",", ".").''.$p.'</b></td> 
							   <td align="right" valign="top" ><b>'.$q.''.number_format($sisa_silpa1, "2", ",", ".").''.$r.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_silpa, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					}
					}
			 
                      
		
			$cRet .="</table>";
			$data['prev']= $cRet;    
            $judul='LRA_PERMEN ';
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
			
	function cetak_perda_lampI_2($bulan='',$ctk='',$anggaran='',$kd_skpd='',$jenis='',$tglttd='', $ttd=''){
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
	
	$sqlnogub="SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $ket_lampiran      = strtoupper("Lampiran I.2");
                    $ket_perda         = strtoupper($rowsc->ket_perda);
                    $ket_perda_no      = strtoupper($rowsc->ket_perda_no);
                    $ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
                 }
		 
		$cRet ='<TABLE style="border-collapse:collapse;font-size:10px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="9%" valign="top" align="left" >'.$ket_lampiran.'</TD>
						<TD  width="1%" valign="top" align="center" >:</TD>
						<TD width="90%"  align="left" >'.$ket_perda.'<br>'.$ket_perda_no.'<br>'.$ket_perda_tentang.' '. $lntahunang.'</TD>
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
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,1)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,1),'nm_urusan1','ms_urusan1','kd_urusan1')." </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,4)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,4),'nm_urusan','ms_urusan','kd_urusan')."</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,7)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,7),'nm_org','ms_organisasi','kd_org')."</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,10)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,10),'nm_skpd','ms_skpd','kd_skpd')."</td>
					</tr>
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
				
				$sql="SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_pend_n($bulan,$anggaran,$lntahunang) $where ORDER BY kd_kegiatan,kd_rek";
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
					
					$sql="SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci_n($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek) <='$jenis' and anggaran<>0 ORDER BY kd_kegiatan,kd_rek";
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
			if($kd_skpd=='3.13.01.17'){
				$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_objek_biaya_n($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)='3'";
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
							   <td align="left"  valign="top"><b>PEMBIAYAAN</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa11, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
					}
					$hasil->free_result(); 
					
					$sql="SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci_biaya_n($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek) <='$jenis' and anggaran<>0 ORDER BY kd_kegiatan,kd_rek";
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
							   <td align="left"  valign="top"><b>JUMLAH PEMBIAYAAN</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa11, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
			} else {}	
				
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan,<br><br><br><br><br>$nama<br>$pangkat<br>
					</td>
				</tr>
				</table>";
			$data['prev']= $cRet;    
            $judul='PERDA_LAMP_I.2 ';
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
	
	function cetak_perda_lampI_3($bulan='',$anggaran='',$ctk='',$tglttd='',$ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}				$nip = '';
		
		
		$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc){
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
		
		$sqlnogub="SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $ket_lampiran      = strtoupper("Lampiran I.3");
                    $ket_perda         = strtoupper($rowsc->ket_perda);
                    $ket_perda_no      = strtoupper($rowsc->ket_perda_no);
                    $ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
                 }
		 
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >'.$ket_lampiran.'</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >'.$ket_perda.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_perda_no.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_perda_tentang.'</TD>
					</TR>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REKAPITULASI REALISASI BELANJA DAERAH MENURUT URUSAN PEMERINTAH DAERAH, <BR> ORGANISASI, PROGRAM, DAN KEGIATAN</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KODE</td>
                    <td rowspan = \"2\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">URUSAN PEMERINTAH DAERAH</td>
					<td colspan = \"3\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">ANGGARAN BELANJA</td>
                    <td rowspan = \"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
					<td colspan = \"3\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI BELANJA</td>
                    <td rowspan = \"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
				</tr>
				<tr>
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td> 
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
				</tr>
				</thead>";
				
			
			$sql = " select kd_kegiatan kode ,nm_rek,ang_peg,ang_brng,ang_mod,real_peg,real_brng,real_mod 
					FROM [perda_lampI.3]($bulan,$anggaran,$lntahunang) ORDER BY kd_kegiatan
					";
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kode = $row->kode;
					   $nm_rek = $row->nm_rek;
                       $ang_peg = $row->ang_peg;
                       $ang_brng = $row->ang_brng;
                       $ang_mod = $row->ang_mod;
					   $real_peg = $row->real_peg;
                       $real_brng = $row->real_brng;
                       $real_mod = $row->real_mod;
					   
					   $tot_ang=$ang_peg+$ang_brng+$ang_mod;
					   $tot_real=$real_peg+$real_brng+$real_mod;
                      
					  $len = strlen($kode); 

					if($len=='1'){
					 $cRet .='<tr>
							   <td align="left" valign="top" style="font-size:12px"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top" style="font-size:12px"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_peg, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_brng, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_mod, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_peg, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_brng, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_mod, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_real, "2", ",", ".").'</b></td> 
							</tr>'; 
					}else{
						$cRet .='<tr>
							   <td align="left" valign="top" style="font-size:12px">'.$kode.'</td> 
							   <td align="left"  valign="top" style="font-size:12px">'.$nm_rek.'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_peg, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_brng, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_mod, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($tot_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($real_peg, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($real_brng, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($real_mod, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($tot_real, "2", ",", ".").'</td> 
							</tr>'; 
					}
					}
			$sql2 = "select isnull(sum(ang_peg),0) ang_peg, isnull(sum(ang_brng),0) ang_brng, isnull(sum(ang_mod),0) ang_mod, isnull(sum(real_peg),0) real_peg, isnull(sum(real_brng),0) real_brng, isnull(sum(real_mod),0) real_mod
			from (  
			SELECT a.kd_skpd,a.kd_kegiatan, nm_rek, ISNULL(ang_peg,0) as ang_peg,
			ISNULL(ang_brng,0) as ang_brng, ISNULL(ang_mod,0) as ang_mod, ISNULL(real_peg,0) as real_peg,
			ISNULL(real_brng,0) as real_brng, ISNULL(real_mod,0) as real_mod
			 FROM (SELECT LEFT(a.kd_skpd,1) kd_skpd,  LEFT(a.kd_skpd,1) kd_kegiatan, b.nm_urusan1 nm_rek
			, SUM(CASE WHEN LEFT(kd_rek5,3) IN('511','521') AND $anggaran=1 THEN nilai 
								 WHEN LEFT(kd_rek5,3) IN('511','521') AND $anggaran=2 THEN nilai_sempurna 
								 WHEN LEFT(kd_rek5,3) IN('511','521') AND $anggaran=3 THEN nilai_ubah
								 ELSE 0 END) as ang_peg 
			, SUM(CASE WHEN LEFT(kd_rek5,3) = '522' AND $anggaran=1 THEN nilai 
								 WHEN LEFT(kd_rek5,3) = '522' AND $anggaran=2 THEN nilai_sempurna 
								 WHEN LEFT(kd_rek5,3) = '522' AND $anggaran=3 THEN nilai_ubah
								 ELSE 0 END) as ang_brng
			, SUM(CASE WHEN LEFT(kd_rek5,3) = '523' AND $anggaran=1 THEN nilai 
								 WHEN LEFT(kd_rek5,3) = '523' AND $anggaran=2 THEN nilai_sempurna 
								 WHEN LEFT(kd_rek5,3) = '523' AND $anggaran=3 THEN nilai_ubah
								 ELSE 0 END) as ang_mod
			 FROM trdrka a 
			INNER JOIN ms_urusan1 b on LEFT(a.kd_skpd,1)=b.kd_urusan1 
			WHERE LEFT(a.kd_rek5,3) IN ('511','521','522','523')
			GROUP BY LEFT(a.kd_skpd,1),b.nm_urusan1) a 
			LEFT JOIN 
			(SELECT  LEFT(a.kd_skpd,1) kd_skpd
			,SUM(CASE WHEN LEFT(b.map_real,3) IN('511','521') THEN (debet-kredit) ELSE 0 END) as real_peg
			,SUM(CASE WHEN LEFT(b.map_real,3)='522'  THEN (debet-kredit) ELSE 0 END) as real_brng
			,SUM(CASE WHEN LEFT(b.map_real,3)='523'  THEN (debet-kredit) ELSE 0 END) as real_mod
			FROM trhju a INNER JOIN trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
			WHERE LEFT(b.map_real,3) IN ('511','521','522','523') AND MONTH(a.tgl_voucher)<=$bulan AND YEAR(a.tgl_voucher)=$lntahunang
			GROUP BY LEFT(a.kd_skpd,1))b
			ON a.kd_skpd=b.kd_skpd ) a
			";
			$hasil2 = $this->db->query($sql2);
                    foreach ($hasil2->result() as $row2)
                    {
					  
                       $ang_peg = $row2->ang_peg;
                       $ang_brng = $row2->ang_brng;
                       $ang_mod = $row2->ang_mod;
					   $real_peg = $row2->real_peg;
                       $real_brng = $row2->real_brng;
                       $real_mod = $row2->real_mod;
					   
					   $tot_ang=$ang_peg+$ang_brng+$ang_mod;
					   $tot_real=$real_peg+$real_brng+$real_mod;
                      

					 $cRet .='<tr>
							   <td align="center" valign="top" style="font-size:12px" colspan="2">TOTAL</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_peg, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_brng, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_mod, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($tot_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($real_peg, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($real_brng, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($real_mod, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($tot_real, "2", ",", ".").'</td> 
							</tr>'; 
					}	
				
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
			$data['prev']= $cRet;    
            $judul='Perda_LampI';
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
	
	function cetak_perda_lampI_3_global($bulan='',$anggaran='',$ctk='',$tglttd='',$ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}				$nip = '';
		
		
		$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc){
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
		
		$sqlnogub="SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $no_perda     = $rowsc->no_perda;
                    $tgl_perda     = $rowsc->tgl_perda;
                    $no_pergub     = $rowsc->no_pergub;
                    $tgl_pergub    = $rowsc->tgl_pergub;
                    $rancang    = "";
                 }
		 if ($test==0){
			 $no_perda="";
			 $tgl_perda="";
			 $no_pergub="&nbsp;&nbsp;&nbsp;&nbsp;";
			 $tgl_pergub="";
			 $rancang="Rancangan ";
		 }
		 
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="5%" valign="top" align="right" > Lampiran I.3 :</TD>
						<TD width="95%"  align="left" >'.$rancang.' Peraturan Daerah Provinsi Kalimantan Barat<br>Nomor : <u>'.$no_pergub.'</u> Tahun 2018 <br>Tentang: Pertanggungjawaban Pelaksanaan Anggaran Pendapatan dan Belanja Daerah Tahun Anggaran '. $lntahunang.'</TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REKAPITULASI REALISASI BELANJA DAERAH MENURUT URUSAN PEMERINTAH DAERAH, <BR> ORGANISASI, PROGRAM, DAN KEGIATAN</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KODE</td>
                    <td rowspan = \"2\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">URUSAN PEMERINTAH DAERAH</td>
					<td colspan = \"3\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">ANGGARAN BELANJA</td>
                    <td rowspan = \"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
					<td colspan = \"3\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI BELANJA</td>
                    <td rowspan = \"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
				</tr>
				<tr>
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td> 
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
				</tr>
				</thead>";
				
			
			$sql = " SELECT a.kd_skpd,a.kd_kegiatan kode, nm_rek, ISNULL(ang_peg,0) as ang_peg,
						ISNULL(ang_brng,0) as ang_brng, ISNULL(ang_mod,0) as ang_mod, ISNULL(real_peg,0) as real_peg,
						ISNULL(real_brng,0) as real_brng, ISNULL(real_mod,0) as real_mod
						 FROM (SELECT LEFT(a.kd_skpd,1) kd_skpd,  LEFT(a.kd_skpd,1) kd_kegiatan, b.nm_urusan1 nm_rek
						, SUM(CASE WHEN LEFT(kd_rek5,3) IN('511','521') AND $anggaran=1 THEN nilai 
											 WHEN LEFT(kd_rek5,3) IN('511','521') AND $anggaran=2 THEN nilai_sempurna 
											 WHEN LEFT(kd_rek5,3) IN('511','521') AND $anggaran=3 THEN nilai_ubah
											 ELSE 0 END) as ang_peg 
						, SUM(CASE WHEN LEFT(kd_rek5,3) = '522' AND $anggaran=1 THEN nilai 
											 WHEN LEFT(kd_rek5,3) = '522' AND $anggaran=2 THEN nilai_sempurna 
											 WHEN LEFT(kd_rek5,3) = '522' AND $anggaran=3 THEN nilai_ubah
											 ELSE 0 END) as ang_brng
						, SUM(CASE WHEN LEFT(kd_rek5,3) = '523' AND $anggaran=1 THEN nilai 
											 WHEN LEFT(kd_rek5,3) = '523' AND $anggaran=2 THEN nilai_sempurna 
											 WHEN LEFT(kd_rek5,3) = '523' AND $anggaran=3 THEN nilai_ubah
											 ELSE 0 END) as ang_mod
						 FROM trdrka a 
						INNER JOIN ms_urusan1 b on LEFT(a.kd_skpd,1)=b.kd_urusan1 
						WHERE LEFT(a.kd_rek5,3) IN ('511','521','522','523')
						GROUP BY LEFT(a.kd_skpd,1),b.nm_urusan1) a 
						LEFT JOIN 
						(SELECT  LEFT(a.kd_skpd,1) kd_skpd
						,SUM(CASE WHEN LEFT(b.map_real,3) IN('511','521') THEN (debet-kredit) ELSE 0 END) as real_peg
						,SUM(CASE WHEN LEFT(b.map_real,3)='522'  THEN (debet-kredit) ELSE 0 END) as real_brng
						,SUM(CASE WHEN LEFT(b.map_real,3)='523'  THEN (debet-kredit) ELSE 0 END) as real_mod
						FROM trhju a INNER JOIN trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
						WHERE LEFT(b.map_real,3) IN ('511','521','522','523') AND MONTH(a.tgl_voucher)<=$bulan AND YEAR(a.tgl_voucher)=$lntahunang
						GROUP BY LEFT(a.kd_skpd,1))b
						ON a.kd_skpd=b.kd_skpd 

						UNION ALL

						SELECT a.kd_skpd,a.kd_kegiatan kode, nm_rek, ISNULL(ang_peg,0) as ang_peg,
						ISNULL(ang_brng,0) as ang_brng, ISNULL(ang_mod,0) as ang_mod, ISNULL(real_peg,0) as real_peg,
						ISNULL(real_brng,0) as real_brng, ISNULL(real_mod,0) as real_mod
						 FROM (SELECT LEFT(a.kd_skpd,4) kd_skpd, LEFT(a.kd_skpd,4) kd_kegiatan, b.nm_urusan nm_rek
						, SUM(CASE WHEN LEFT(kd_rek5,3) IN('511','521') AND $anggaran=1 THEN nilai 
											 WHEN LEFT(kd_rek5,3) IN('511','521') AND $anggaran=2 THEN nilai_sempurna 
											 WHEN LEFT(kd_rek5,3) IN('511','521') AND $anggaran=3 THEN nilai_ubah
											 ELSE 0 END) as ang_peg 
						, SUM(CASE WHEN LEFT(kd_rek5,3) = '522' AND $anggaran=1 THEN nilai 
											 WHEN LEFT(kd_rek5,3) = '522' AND $anggaran=2 THEN nilai_sempurna 
											 WHEN LEFT(kd_rek5,3) = '522' AND $anggaran=3 THEN nilai_ubah
											 ELSE 0 END) as ang_brng
						, SUM(CASE WHEN LEFT(kd_rek5,3) = '523' AND $anggaran=1 THEN nilai 
											 WHEN LEFT(kd_rek5,3) = '523' AND $anggaran=2 THEN nilai_sempurna 
											 WHEN LEFT(kd_rek5,3) = '523' AND $anggaran=3 THEN nilai_ubah
											 ELSE 0 END) as ang_mod
						 FROM trdrka a 
						INNER JOIN ms_urusan b on LEFT(a.kd_skpd,4)=b.kd_urusan 
						WHERE LEFT(a.kd_rek5,3) IN ('511','521','522','523')
						GROUP BY LEFT(a.kd_skpd,4),b.nm_urusan) a 
						LEFT JOIN 
						(SELECT  LEFT(a.kd_skpd,4) kd_skpd
						,SUM(CASE WHEN LEFT(b.map_real,3) IN('511','521') THEN (debet-kredit) ELSE 0 END) as real_peg
						,SUM(CASE WHEN LEFT(b.map_real,3)='522'  THEN (debet-kredit) ELSE 0 END) as real_brng
						,SUM(CASE WHEN LEFT(b.map_real,3)='523'  THEN (debet-kredit) ELSE 0 END) as real_mod
						FROM trhju a INNER JOIN trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
						WHERE LEFT(b.map_real,3) IN ('511','521','522','523') AND MONTH(a.tgl_voucher)<=$bulan AND YEAR(a.tgl_voucher)=$lntahunang
						GROUP BY LEFT(a.kd_skpd,4))b
						ON a.kd_skpd=b.kd_skpd 
						order by kd_skpd,kd_kegiatan";
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kode = $row->kode;
					   $nm_rek = $row->nm_rek;
                       $ang_peg = $row->ang_peg;
                       $ang_brng = $row->ang_brng;
                       $ang_mod = $row->ang_mod;
					   $real_peg = $row->real_peg;
                       $real_brng = $row->real_brng;
                       $real_mod = $row->real_mod;
					   
					   $tot_ang=$ang_peg+$ang_brng+$ang_mod;
					   $tot_real=$real_peg+$real_brng+$real_mod;
                      
					  $len = strlen($kode);
					  
						if($len=='1'){
							 $cRet .='<tr>
									   <td align="left" valign="top" style="font-size:12px"><b>'.$kode.'</b></td> 
									   <td align="left"  valign="top" style="font-size:12px"><b>'.$nm_rek.'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_peg, "2", ",", ".").'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_brng, "2", ",", ".").'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_mod, "2", ",", ".").'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_ang, "2", ",", ".").'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_peg, "2", ",", ".").'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_brng, "2", ",", ".").'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_mod, "2", ",", ".").'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_real, "2", ",", ".").'</b></td> 
									</tr>'; 
						}else{
							$cRet .='<tr>
								   <td align="left" valign="top" style="font-size:12px">'.$kode.'</td> 
								   <td align="left"  valign="top" style="font-size:12px">'.$nm_rek.'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_peg, "2", ",", ".").'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_brng, "2", ",", ".").'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_mod, "2", ",", ".").'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($tot_ang, "2", ",", ".").'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($real_peg, "2", ",", ".").'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($real_brng, "2", ",", ".").'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($real_mod, "2", ",", ".").'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($tot_real, "2", ",", ".").'</td> 
								</tr>'; 
						}
					}
			$sql2 = "select isnull(sum(ang_peg),0) ang_peg, isnull(sum(ang_brng),0) ang_brng, isnull(sum(ang_mod),0) ang_mod, isnull(sum(real_peg),0) real_peg, isnull(sum(real_brng),0) real_brng, isnull(sum(real_mod),0) real_mod
			from (  
			SELECT a.kd_skpd,a.kd_kegiatan, nm_rek, ISNULL(ang_peg,0) as ang_peg,
			ISNULL(ang_brng,0) as ang_brng, ISNULL(ang_mod,0) as ang_mod, ISNULL(real_peg,0) as real_peg,
			ISNULL(real_brng,0) as real_brng, ISNULL(real_mod,0) as real_mod
			 FROM (SELECT LEFT(a.kd_skpd,1) kd_skpd,  LEFT(a.kd_skpd,1) kd_kegiatan, b.nm_urusan1 nm_rek
			, SUM(CASE WHEN LEFT(kd_rek5,3) IN('511','521') AND $anggaran=1 THEN nilai 
								 WHEN LEFT(kd_rek5,3) IN('511','521') AND $anggaran=2 THEN nilai_sempurna 
								 WHEN LEFT(kd_rek5,3) IN('511','521') AND $anggaran=3 THEN nilai_ubah
								 ELSE 0 END) as ang_peg 
			, SUM(CASE WHEN LEFT(kd_rek5,3) = '522' AND $anggaran=1 THEN nilai 
								 WHEN LEFT(kd_rek5,3) = '522' AND $anggaran=2 THEN nilai_sempurna 
								 WHEN LEFT(kd_rek5,3) = '522' AND $anggaran=3 THEN nilai_ubah
								 ELSE 0 END) as ang_brng
			, SUM(CASE WHEN LEFT(kd_rek5,3) = '523' AND $anggaran=1 THEN nilai 
								 WHEN LEFT(kd_rek5,3) = '523' AND $anggaran=2 THEN nilai_sempurna 
								 WHEN LEFT(kd_rek5,3) = '523' AND $anggaran=3 THEN nilai_ubah
								 ELSE 0 END) as ang_mod
			 FROM trdrka a 
			INNER JOIN ms_urusan1 b on LEFT(a.kd_skpd,1)=b.kd_urusan1 
			WHERE LEFT(a.kd_rek5,3) IN ('511','521','522','523')
			GROUP BY LEFT(a.kd_skpd,1),b.nm_urusan1) a 
			LEFT JOIN 
			(SELECT  LEFT(a.kd_skpd,1) kd_skpd
			,SUM(CASE WHEN LEFT(b.map_real,3) IN('511','521') THEN (debet-kredit) ELSE 0 END) as real_peg
			,SUM(CASE WHEN LEFT(b.map_real,3)='522'  THEN (debet-kredit) ELSE 0 END) as real_brng
			,SUM(CASE WHEN LEFT(b.map_real,3)='523'  THEN (debet-kredit) ELSE 0 END) as real_mod
			FROM trhju a INNER JOIN trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
			WHERE LEFT(b.map_real,3) IN ('511','521','522','523') AND MONTH(a.tgl_voucher)<=$bulan AND YEAR(a.tgl_voucher)=$lntahunang
			GROUP BY LEFT(a.kd_skpd,1))b
			ON a.kd_skpd=b.kd_skpd ) a
			";
			$hasil2 = $this->db->query($sql2);
                    foreach ($hasil2->result() as $row2)
                    {
					  
                       $ang_peg = $row2->ang_peg;
                       $ang_brng = $row2->ang_brng;
                       $ang_mod = $row2->ang_mod;
					   $real_peg = $row2->real_peg;
                       $real_brng = $row2->real_brng;
                       $real_mod = $row2->real_mod;
					   
					   $tot_ang=$ang_peg+$ang_brng+$ang_mod;
					   $tot_real=$real_peg+$real_brng+$real_mod;
                      

					 $cRet .='<tr>
							   <td align="center" valign="top" style="font-size:12px" colspan="2"><b>TOTAL</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_peg, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_brng, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_mod, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_peg, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_brng, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_mod, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_real, "2", ",", ".").'</b></td> 
							</tr>'; 
					}	
				
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
			$data['prev']= $cRet;    
            $judul='Perda_LampI';
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
	
	function cetak_belanja_urusan($bulan='',$anggaran='',$ctk='',$tglttd='',$ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}				$nip = '';
		
		
		$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc){
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
		
		$sqlnogub="SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $ket_lampiran      = strtoupper("Lampiran I.3");
                    $ket_perda         = strtoupper($rowsc->ket_perda);
                    $ket_perda_no      = strtoupper($rowsc->ket_perda_no);
                    $ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
                 }
				 
		$cRet =''; 
		 
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >'.$ket_lampiran.'</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >'.$ket_perda.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_perda_no.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_perda_tentang.'</TD>
					</TR>
					</TABLE><br/>';
	
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN BELANJA URUSAN</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KODE</td>
                    <td rowspan = \"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">NAMA UNIT</td>
					<td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">ANGGARAN BTL</td>
                    <td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI BTL</td>
					<td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">ANGGARAN BL</td>
                    <td colspan = \"3\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI BL</td>
                    <td rowspan = \"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH ANGGARAN</td>
                    <td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH REALISASI</td>
				</tr>
				<tr>
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td> 
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td> 
				</tr>
				</thead>";
				
			
			$sql = " SELECT a.kd_skpd,a.kd_kegiatan kode, nm_rek, 
						   ISNULL(ang_btl,0) as ang_btl,
						   ISNULL(real_btl,0) as real_btl,
						   ISNULL(ang_bl,0) as ang_bl,
						   ISNULL(real_pegawai,0) as real_pegawai,
						   ISNULL(real_brgjsa,0) as real_brgjsa,
						   ISNULL(real_modal,0) as real_modal
					FROM (SELECT LEFT(a.kd_skpd,1) kd_skpd,  LEFT(a.kd_skpd,1) kd_kegiatan, b.nm_urusan1 nm_rek
								,SUM(CASE WHEN LEFT(kd_rek5,2) IN('51') AND $anggaran=1 THEN nilai 
										  WHEN LEFT(kd_rek5,2) IN('51') AND $anggaran=2 THEN nilai_sempurna 
										  WHEN LEFT(kd_rek5,2) IN('51') AND $anggaran=3 THEN nilai_ubah
										  ELSE 0 END) as ang_btl
								,SUM(CASE WHEN LEFT(kd_rek5,2) IN('52') AND $anggaran=1 THEN nilai 
										  WHEN LEFT(kd_rek5,2) IN('52') AND $anggaran=2 THEN nilai_sempurna 
										  WHEN LEFT(kd_rek5,2) IN('52') AND $anggaran=3 THEN nilai_ubah
										  ELSE 0 END) as ang_bl 
						   FROM trdrka a INNER JOIN ms_urusan1 b on LEFT(a.kd_skpd,1)=b.kd_urusan1 
						   WHERE LEFT(a.kd_rek5,1) IN ('5')
						   GROUP BY LEFT(a.kd_skpd,1),b.nm_urusan1) a 
					LEFT JOIN 
					(SELECT LEFT(a.kd_skpd,1) kd_skpd
							,SUM(CASE WHEN LEFT(b.map_real,2) IN('51') THEN (debet-kredit) ELSE 0 END) as real_btl
							,SUM(CASE WHEN LEFT(b.map_real,3) IN('521') THEN (debet-kredit) ELSE 0 END) as real_pegawai
							,SUM(CASE WHEN LEFT(b.map_real,3) IN('522') THEN (debet-kredit) ELSE 0 END) as real_brgjsa
							,SUM(CASE WHEN LEFT(b.map_real,3) IN('523') THEN (debet-kredit) ELSE 0 END) as real_modal
					 FROM trhju a INNER JOIN trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
					 WHERE LEFT(b.map_real,1) IN ('5') AND MONTH(a.tgl_voucher)<=$bulan AND YEAR(a.tgl_voucher)=$lntahunang
					 GROUP BY LEFT(a.kd_skpd,1))b ON a.kd_skpd=b.kd_skpd 

					UNION ALL

					SELECT a.kd_skpd,a.kd_kegiatan kode, nm_rek, 
						   ISNULL(ang_btl,0) as ang_btl,
						   ISNULL(real_btl,0) as real_btl,
						   ISNULL(ang_bl,0) as ang_bl,
						   ISNULL(real_pegawai,0) as real_pegawai,
						   ISNULL(real_brgjsa,0) as real_brgjsa,
						   ISNULL(real_modal,0) as real_modal
					FROM (SELECT LEFT(a.kd_skpd,4) kd_skpd, LEFT(a.kd_skpd,4) kd_kegiatan, b.nm_urusan nm_rek
								,SUM(CASE WHEN LEFT(kd_rek5,2) IN('51') AND $anggaran=1 THEN nilai 
										  WHEN LEFT(kd_rek5,2) IN('51') AND $anggaran=2 THEN nilai_sempurna 
										  WHEN LEFT(kd_rek5,2) IN('51') AND $anggaran=3 THEN nilai_ubah
									 ELSE 0 END) as ang_btl
								,SUM(CASE WHEN LEFT(kd_rek5,2) IN('52') AND $anggaran=1 THEN nilai 
										  WHEN LEFT(kd_rek5,2) IN('52') AND $anggaran=2 THEN nilai_sempurna 
										  WHEN LEFT(kd_rek5,2) IN('52') AND $anggaran=3 THEN nilai_ubah
									 ELSE 0 END) as ang_bl 
						  FROM trdrka a INNER JOIN ms_urusan b on LEFT(a.kd_skpd,4)=b.kd_urusan 
						  WHERE LEFT(a.kd_rek5,1) IN ('5')
						  GROUP BY LEFT(a.kd_skpd,4),b.nm_urusan) a 
					LEFT JOIN 
					(SELECT LEFT(a.kd_skpd,4) kd_skpd
							,SUM(CASE WHEN LEFT(b.map_real,2) IN('51') THEN (debet-kredit) ELSE 0 END) as real_btl
							,SUM(CASE WHEN LEFT(b.map_real,3) IN('521') THEN (debet-kredit) ELSE 0 END) as real_pegawai
							,SUM(CASE WHEN LEFT(b.map_real,3) IN('522') THEN (debet-kredit) ELSE 0 END) as real_brgjsa
							,SUM(CASE WHEN LEFT(b.map_real,3) IN('523') THEN (debet-kredit) ELSE 0 END) as real_modal
					 FROM trhju a INNER JOIN trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
					 WHERE LEFT(b.map_real,1) IN ('5') AND MONTH(a.tgl_voucher)<=$bulan AND YEAR(a.tgl_voucher)=$lntahunang
					 GROUP BY LEFT(a.kd_skpd,4))b ON a.kd_skpd=b.kd_skpd 

					UNION ALL

					SELECT a.kd_skpd,a.kd_kegiatan kode, nm_rek, 
						   ISNULL(ang_btl,0) as ang_btl,
						   ISNULL(real_btl,0) as real_btl,
						   ISNULL(ang_bl,0) as ang_bl,
						   ISNULL(real_pegawai,0) as real_pegawai,
						   ISNULL(real_brgjsa,0) as real_brgjsa,
						   ISNULL(real_modal,0) as real_modal
					FROM (SELECT a.kd_skpd kd_skpd, a.kd_skpd kd_kegiatan, (select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) nm_rek
								,SUM(CASE WHEN LEFT(kd_rek5,2) IN('51') AND $anggaran=1 THEN nilai 
										  WHEN LEFT(kd_rek5,2) IN('51') AND $anggaran=2 THEN nilai_sempurna 
										  WHEN LEFT(kd_rek5,2) IN('51') AND $anggaran=3 THEN nilai_ubah
									 ELSE 0 END) as ang_btl
								,SUM(CASE WHEN LEFT(kd_rek5,2) IN('52') AND $anggaran=1 THEN nilai 
										  WHEN LEFT(kd_rek5,2) IN('52') AND $anggaran=2 THEN nilai_sempurna 
										  WHEN LEFT(kd_rek5,2) IN('52') AND $anggaran=3 THEN nilai_ubah
									 ELSE 0 END) as ang_bl 
						  FROM trdrka a INNER JOIN ms_urusan b on LEFT(a.kd_skpd,4)=b.kd_urusan 
						  WHERE LEFT(a.kd_rek5,1) IN ('5')
						  GROUP BY a.kd_skpd) a 
					LEFT JOIN 
					(SELECT a.kd_skpd kd_skpd
							,SUM(CASE WHEN LEFT(b.map_real,2) IN('51') THEN (debet-kredit) ELSE 0 END) as real_btl
							,SUM(CASE WHEN LEFT(b.map_real,3) IN('521') THEN (debet-kredit) ELSE 0 END) as real_pegawai
							,SUM(CASE WHEN LEFT(b.map_real,3) IN('522') THEN (debet-kredit) ELSE 0 END) as real_brgjsa
							,SUM(CASE WHEN LEFT(b.map_real,3) IN('523') THEN (debet-kredit) ELSE 0 END) as real_modal
					 FROM trhju a INNER JOIN trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
					 WHERE LEFT(b.map_real,1) IN ('5') AND MONTH(a.tgl_voucher)<=$bulan AND YEAR(a.tgl_voucher)=$lntahunang
					 GROUP BY a.kd_skpd)b ON a.kd_skpd=b.kd_skpd 
					ORDER BY kd_skpd,kd_kegiatan";
                    $hasil = $this->db->query($sql);
					
					$tot_ang_btl = 0;
					$tot_real_btl = 0;
					$tot_ang_bl = 0;
					$tot_real_peg = 0;
					$tot_real_brng = 0;
					$tot_real_mod = 0;
					$tot_tot_ang = 0;
					$tot_tot_real = 0;
					
                    foreach ($hasil->result() as $row)
                    {
					   $kode = $row->kode;
					   $nm_rek = $row->nm_rek;
                       $ang_btl = $row->ang_btl;
                       $real_btl = $row->real_btl;
                       $ang_bl = $row->ang_bl;
					   $real_peg = $row->real_pegawai;
                       $real_brng = $row->real_brgjsa;
                       $real_mod = $row->real_modal;
					   
					   $tot_ang=$ang_btl+$ang_bl;
					   $tot_real=$real_btl+$real_peg+$real_brng+$real_mod;
                      
					   $len = strlen($kode);
					  
						if($len=='1'){
							 $cRet .='<tr>
									   <td align="left" valign="top" style="font-size:12px"><b>'.$kode.'</b></td> 
									   <td align="left"  valign="top" style="font-size:12px"><b>'.$nm_rek.'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_btl, "2", ",", ".").'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_btl, "2", ",", ".").'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_bl, "2", ",", ".").'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_peg, "2", ",", ".").'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_brng, "2", ",", ".").'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_mod, "2", ",", ".").'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_ang, "2", ",", ".").'</b></td> 
									   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_real, "2", ",", ".").'</b></td> 
									</tr>'; 
						}else{
							$cRet .='<tr>
								   <td align="left" valign="top" style="font-size:12px">'.$kode.'</td> 
								   <td align="left"  valign="top" style="font-size:12px">'.$nm_rek.'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_btl, "2", ",", ".").'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($real_btl, "2", ",", ".").'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_bl, "2", ",", ".").'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($real_peg, "2", ",", ".").'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($real_brng, "2", ",", ".").'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($real_mod, "2", ",", ".").'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($tot_ang, "2", ",", ".").'</td> 
								   <td align="right" valign="top" style="font-size:12px">'.number_format($tot_real, "2", ",", ".").'</td> 
								</tr>'; 
						}
					}

				$sql = " SELECT SUM(ang_btl) tot_ang_btl, SUM(real_btl) tot_real_btl, SUM(ang_bl) tot_ang_bl, SUM(real_pegawai) tot_real_pegawai, SUM(real_brgjsa) tot_real_brgjsa , SUM(real_modal) tot_real_modal
						FROM (
						SELECT a.kd_skpd,a.kd_kegiatan kode, nm_rek, 
						   ISNULL(ang_btl,0) as ang_btl,
						   ISNULL(real_btl,0) as real_btl,
						   ISNULL(ang_bl,0) as ang_bl,
						   ISNULL(real_pegawai,0) as real_pegawai,
						   ISNULL(real_brgjsa,0) as real_brgjsa,
						   ISNULL(real_modal,0) as real_modal
					FROM (SELECT LEFT(a.kd_skpd,1) kd_skpd,  LEFT(a.kd_skpd,1) kd_kegiatan, b.nm_urusan1 nm_rek
								,SUM(CASE WHEN LEFT(kd_rek5,2) IN('51') AND $anggaran=1 THEN nilai 
										  WHEN LEFT(kd_rek5,2) IN('51') AND $anggaran=2 THEN nilai_sempurna 
										  WHEN LEFT(kd_rek5,2) IN('51') AND $anggaran=3 THEN nilai_ubah
										  ELSE 0 END) as ang_btl
								,SUM(CASE WHEN LEFT(kd_rek5,2) IN('52') AND $anggaran=1 THEN nilai 
										  WHEN LEFT(kd_rek5,2) IN('52') AND $anggaran=2 THEN nilai_sempurna 
										  WHEN LEFT(kd_rek5,2) IN('52') AND $anggaran=3 THEN nilai_ubah
										  ELSE 0 END) as ang_bl 
						   FROM trdrka a INNER JOIN ms_urusan1 b on LEFT(a.kd_skpd,1)=b.kd_urusan1 
						   WHERE LEFT(a.kd_rek5,1) IN ('5')
						   GROUP BY LEFT(a.kd_skpd,1),b.nm_urusan1) a 
					LEFT JOIN 
					(SELECT LEFT(a.kd_skpd,1) kd_skpd
							,SUM(CASE WHEN LEFT(b.map_real,2) IN('51') THEN (debet-kredit) ELSE 0 END) as real_btl
							,SUM(CASE WHEN LEFT(b.map_real,3) IN('521') THEN (debet-kredit) ELSE 0 END) as real_pegawai
							,SUM(CASE WHEN LEFT(b.map_real,3) IN('522') THEN (debet-kredit) ELSE 0 END) as real_brgjsa
							,SUM(CASE WHEN LEFT(b.map_real,3) IN('523') THEN (debet-kredit) ELSE 0 END) as real_modal
					 FROM trhju a INNER JOIN trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
					 WHERE LEFT(b.map_real,1) IN ('5') AND MONTH(a.tgl_voucher)<=$bulan AND YEAR(a.tgl_voucher)=$lntahunang
					 GROUP BY LEFT(a.kd_skpd,1))b ON a.kd_skpd=b.kd_skpd ) x ";
					$hasil = $this->db->query($sql);
					
					foreach ($hasil->result() as $row)
                    {
					  
                       $tot_ang_btl = $row->tot_ang_btl;
                       $tot_real_btl = $row->tot_real_btl;
                       $tot_ang_bl = $row->tot_ang_bl;
					   $tot_real_peg = $row->tot_real_pegawai;
                       $tot_real_brng = $row->tot_real_brgjsa;
                       $tot_real_mod = $row->tot_real_modal;
					   
					   $tot_tot_ang = $tot_ang_btl+$tot_ang_bl;
					   $tot_tot_real = $tot_real_btl+$tot_real_peg+$tot_real_brng+$tot_real_mod;
					   
					   $cRet .='<tr>
							   <td align="center" valign="top" style="font-size:12px" colspan="2"><b>TOTAL</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_ang_btl, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_real_btl, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_ang_bl, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_real_peg, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_real_brng, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_real_mod, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_tot_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($tot_tot_real, "2", ",", ".").'</b></td> 
							</tr>';
					}
					
					 
						
				
			$cRet .="</table>";
			$data['prev']= $cRet;    
            $judul='Perda_LampI';
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
	
	
	function cetak_perda_lampI_4($bulan='',$anggaran='',$ctk='',$tglttd='',$ttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
	if($ttd=='-'){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}				$nip = '';
		
		
		$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc){
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
		
		$sqlnogub="SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $ket_lampiran      = strtoupper("Lampiran I.4");
                    $ket_perda         = strtoupper($rowsc->ket_perda);
                    $ket_perda_no      = strtoupper($rowsc->ket_perda_no);
                    $ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
                 }
		 
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >'.$ket_lampiran.'</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >'.$ket_perda.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_perda_no.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_perda_tentang.'</TD>
					</TR>
					</TABLE><br/>';
					
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REKAPITULASI REALISASI BELANJA DAERAH UNTUK<BR>KESELARASAN DAN KETERPADUAN URUSAN PEMERINTAH DAERAH<BR> DAN FUNGSI DALAM KERANGKA PENGELOLAAN KEUANGAN NEGARA</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>KODE</b></td>
                    <td rowspan = \"2\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>URAIAN</b></td>
					<td rowspan = \"2\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>ANGGARAN</b></td>
                    <td rowspan = \"2\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>REALISASI</b></td>
					<td colspan = \"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>BERTAMBAH/BERKURANG</b></td>
				</tr>
				<tr>
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>(Rp)</b></td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>(%)</b></td> 
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>1</b></td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>2</b></td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>3</b></td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>4</b></td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>5</b></td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>6</b></td> 
				</tr>
				</thead>";
				
			
			if($anggaran==1){
				$ang="nilai";
			} else if ($anggaran==2){
				$ang="nilai_sempurna";
			} else {
				$ang="nilai_ubah";
			}
			$sql = " SELECT a.kode, a.nama, ISNULL(a.anggaran,0) anggaran, 
						ISNULL(b.realisasi,0) realisasi, ISNULL(realisasi-anggaran,0) selisih FROM 
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
						,SUM(ISNULL(realisasi,0)) as realisasi
						FROM ms_urusan a 
						LEFT JOIN
						(
						SELECT LEFT(kd_kegiatan,4) kd_urusan 
						,SUM(debet-kredit) AS realisasi
						FROM trdju_calk a INNER JOIN trhju_calk b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
						WHERE LEFT(a.map_real,1) IN ('5')  AND MONTH(tgl_voucher)<='$bulan' AND YEAR(tgl_voucher)='$lntahunang'  GROUP BY LEFT(kd_kegiatan,4))b
						ON a.kd_urusan=b.kd_urusan
						GROUP BY a.kd_fungsi,a.kd_urusan,a.nm_urusan)b
						ON a.kode=b.kode  

						UNION ALL

						SELECT a.kode, a.nama, ISNULL(a.anggaran,0) anggaran, 
						ISNULL(b.realisasi,0) realisasi, ISNULL(realisasi-anggaran,0) selisih
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
						,SUM(ISNULL(realisasi,0)) as realisasi
						FROM ms_fungsi a LEFT JOIN 
						(
						SELECT RTRIM(a.kd_fungsi) as kode
						,SUM(ISNULL(realisasi,0)) as realisasi
						FROM ms_urusan a 
						LEFT JOIN
						(
						SELECT LEFT(kd_kegiatan,4) kd_urusan 
						,SUM (debet-kredit) AS realisasi
						FROM trdju a INNER JOIN trhju b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
						WHERE LEFT(a.map_real,1) IN ('5')  AND MONTH(tgl_voucher)<='$bulan' AND YEAR(tgl_voucher)='$lntahunang' GROUP BY LEFT(kd_kegiatan,4))b
						ON a.kd_urusan=b.kd_urusan
						GROUP BY a.kd_fungsi) b
						on a.kd_fungsi=b.kode
						GROUP BY a.kd_fungsi,nm_fungsi)b
						ON a.kode=b.kode
						ORDER BY kode
					";
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kode = $row->kode;
					   $nm_rek = $row->nama;
                       $ang_bel = $row->anggaran;
                       $real_bel = $row->realisasi;
                       $selisih = $row->selisih;
					   $persen = empty($ang_bel) || $ang_bel == 0 ? 0 :$real_bel/$ang_bel*100;
					   
					   if($selisih<0){
						   $selisih1 = $selisih*-1;
						   $a = "(";
						   $b = ")";
					   }else{
						   $selisih1 = $selisih;
						   $a = "";
						   $b = "";
					   }
					 
					 $leng=strlen($kode);
					   switch ($leng) {
					   case 2:
					   $cRet .='<tr>
							   <td align="left" valign="top" style="font-size:12px"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top" style="font-size:12px"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_bel, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_bel, "2", ",", ".").'</b> </td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.$a.' '.number_format($selisih1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
					   break;
					   default;
						$cRet .='<tr>
							   <td align="left" valign="top" style="font-size:12px">'.$kode.'</td> 
							   <td align="left"  valign="top" style="font-size:12px">'.$nm_rek.'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_bel, "2", ",", ".").'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($real_bel, "2", ",", ".").' </td> 
							   <td align="right" valign="top" style="font-size:12px">'.$a.' '.number_format($selisih1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
					   break;
						}
					}
					
					$sql2 = "SELECT ISNULL(SUM(anggaran),0) anggaran, ISNULL(SUM(realisasi),0) realisasi
						FROM (
						SELECT a.kode, a.nama, ISNULL(a.anggaran,0) anggaran, 
												ISNULL(b.realisasi,0) realisasi, ISNULL(realisasi-anggaran,0) selisih
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
						,SUM(ISNULL(realisasi,0)) as realisasi
						FROM ms_fungsi a LEFT JOIN 
						(
						SELECT RTRIM(a.kd_fungsi) as kode
						,SUM(ISNULL(realisasi,0)) as realisasi
						FROM ms_urusan a 
						LEFT JOIN
						(
						SELECT LEFT(kd_kegiatan,4) kd_urusan 
						,SUM (debet-kredit) AS realisasi
						FROM trdju a INNER JOIN trhju b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
						WHERE LEFT(a.map_real,1) IN ('5')  AND MONTH(tgl_voucher)<='$bulan' AND YEAR(tgl_voucher)='$lntahunang' GROUP BY LEFT(kd_kegiatan,4))b
						ON a.kd_urusan=b.kd_urusan
						GROUP BY a.kd_fungsi) b
						on a.kd_fungsi=b.kode
						GROUP BY a.kd_fungsi,nm_fungsi)b
						ON a.kode=b.kode ) x";
                    $hasil2 = $this->db->query($sql2);
                    foreach ($hasil2->result() as $row2)
                    {
					   
                       $ang_bel2 = $row2->anggaran;
                       $real_bel2 = $row2->realisasi;
                       $selisih2 = $real_bel-$ang_bel;
					   $persen2 = empty($ang_bel2) || $ang_bel2 == 0 ? 0 :$real_bel2/$ang_bel2*100;
					   
					   if($selisih2<0){
						   $selisih12 = $selisih2*-1;
						   $a2 = "(";
						   $b2 = ")";
					   }else{
						   $selisih12 = $selisih2;
						   $a2 = "";
						   $b2 = "";
					   }
					   
					  
					
					
					   $cRet .='<tr>
							   <td align="center" valign="top" style="font-size:12px" colspan="2"><b>TOTAL</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($ang_bel2, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($real_bel2, "2", ",", ".").'</b> </td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.$a2.' '.number_format($selisih12, "2", ",", ".").' '.$b2.'</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>'.number_format($persen2, "2", ",", ".").'</b></td> 
							</tr>';
					}
				
				
			$cRet .="</table>";
			$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
			$data['prev']= $cRet;    
            $judul='Perda_Lamp_I.4';
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
	
	function cetak_perkada_lampI_sap_spj($bulan='',$ctk='',$anggaran='',$jenis='',$kd_skpd='',$ttd='',$tglttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "TRIWULAN I";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "SEMESTER I";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "TRIWULAN III";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "TAHUN ANGGARAN $lntahunang";
        break;
    }
	
	if($bulan== 12){
			$sumber_data= "_at";
		}else{
			$sumber_data= "";
		}
	
	if ($kd_skpd=='-'){                               
            $where="";            
        } else{
			$where="AND kd_skpd='$kd_skpd'";
		}
		
		if($tglttd=='-' || $tglttd==''){
			$tanggal='';
		}else{
			$tanggal= 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd);
		}

		//$tanggal = $tglttd == '-' ? '' : 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd) ;
		if($ttd=='-' || $ttd==''){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}
		
		$sqlnogub="SELECT ket_pergub, ket_pergub_no, ket_pergub_tentang FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $ket_lampiran      = strtoupper("Lampiran I");
                    $ket_pergub         = strtoupper($rowsc->ket_pergub);
                    $ket_pergub_no      = strtoupper($rowsc->ket_pergub_no);
                    $ket_pergub_tentang = strtoupper($rowsc->ket_pergub_tentang);
                 }
	
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >'.$ket_lampiran.'</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >'.$ket_pergub.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_pergub_no.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_pergub_tentang.'</TD>
					</TR>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$judul</b></tr>
					</TABLE>";
		
		$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">";
		
				
				if($kd_skpd<>'-'){
					$cRet .="<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,10)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,10),'nm_skpd','ms_skpd','kd_skpd')."</td>
					</tr>";
				}else{
					
				}
				
				
		$cRet .="</TABLE>";
		
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
				</tr>
				<tr>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";
				
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet-kredit) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_surplus = $row->ang_surplus;
                       $nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $nil_surplus-$ang_surplus;
					if(($ang_surplus==0) || ($ang_surplus=='')){
						$persen_surplus=0;
					} else{
					$persen_surplus = $nil_surplus/$ang_surplus*100;
					}
					$hasil->free_result();        
					if($ang_surplus<0){
						$ang_surplus1=$ang_surplus*-1;
						$a='(';
						$b=')';
					} else{
						$ang_surplus1=$ang_surplus;
						$a='';
						$b='';
					}
					if($nil_surplus<0){
						$nil_surplus1=$nil_surplus*-1;
						$c='(';
						$d=')';
					} else{
						$nil_surplus1=$nil_surplus;
						$c='';
						$d='';
					}
					if($sisa_surplus<0){
						$sisa_surplus1=$sisa_surplus*-1;
						$e='(';
						$f=')';
					} else{
						$sisa_surplus1=$sisa_surplus;
						$e='';
						$f='';
					}
			
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (debet-kredit) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_netto = $row->ang_netto;
                       $nil_netto = $row->nil_netto;
					}
					$sisa_netto = $ang_netto-$nil_netto;
					if(($ang_netto==0) || ($ang_netto=='')){
						$persen_netto=0;
					} else{
					$persen_netto = $nil_netto/$ang_netto*100;
					}
					$hasil->free_result();  
					if($ang_netto<0){
						$ang_netto1=$ang_netto*-1;
						$g='(';
						$h=')';
					} else{
						$ang_netto1=$ang_netto;
						$g='';
						$h='';
					}
					if($nil_netto<0){
						$nil_netto1=$nil_netto*-1;
						$i='(';
						$j=')';
					} else{
						$nil_netto1=$nil_netto;
						$i='';
						$j='';
					}
					if($sisa_netto<0){
						$sisa_netto1=$sisa_netto*-1;
						$k='(';
						$l=')';
					} else{
						$sisa_netto1=$sisa_netto;
						$k='';
						$l='';
					}	
					
					$ang_silpa = $ang_surplus+$ang_netto;
					$nil_silpa = $nil_surplus+$nil_netto;
					$sisa_silpa = $nil_silpa-$ang_silpa;
					if($ang_silpa==0){
						$persen_silpa=0;
					}else{
					$persen_silpa = $nil_silpa/$ang_silpa*100;
					}
					if($ang_silpa<0){
						$ang_silpa1=$ang_silpa*-1;
						$m='(';
						$n=')';
					} else{
						$ang_silpa1=$ang_silpa;
						$m='';
						$n='';
					}
					if($nil_silpa<0){
						$nil_silpa1=$nil_silpa*-1;
						$o='(';
						$p=')';
					} else{
						$nil_silpa1=$nil_silpa;
						$o='';
						$p='';
					}
					if($sisa_silpa<0){
						$sisa_silpa1=$sisa_silpa*-1;
						$q='(';
						$r=')';
					} else{
						$sisa_silpa1=$sisa_silpa;
						$q='';
						$r='';
					}	
			$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi, nomor, jns_rek FROM map_lra_sap ORDER BY seq
					";
					$no=0;
					$tot_peg=0;
					$tot_brg=0;
					$tot_mod=0;
					$tot_bansos=0;
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
                       $nomor = $row->nomor;
                       $jns_rek = $row->jns_rek;
					   
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
					$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4)) $where
					";
										
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
                       $nil_ang = $row->nil_ang;
					   $nilai = $row->nilai;
					}
					
					//coba
					if ($jns_rek=='1'){
						$sel = $nilai-$nil_ang;
					}else{
						$sel = $nil_ang-$nilai;
					}
					
					if($sel<0){
						$m = "(";
						$n = ")";
						$sel1 = $sel*-1;
					}else{
						$m = "";
						$n = "";
						$sel1 = $sel;
					}
					
					if(($nil_ang==0) || ($nil_ang=='')){
						$persen=0;
					} else{
					$persen = $nilai/$nil_ang*100;
					}
					
					
					 switch ($spasi) {
					 case 1:
                        $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$nomor.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$m.''.number_format($sel1, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>'; 
                        break;	
                    case 2:
                         $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$nomor.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$m.''.number_format($sel1, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					 case 3:
                         $cRet .='<tr>
							   <td align="left" valign="top">'.$nomor.'</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$m.''.number_format($sel1, "2", ",", ".").''.$n.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
                        break;
					case 4:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$nomor.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$m.''.number_format($sel1, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 5:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$nomor.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.''.number_format($ang_surplus1, "2", ",", ".").''.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$c.''.number_format($nil_surplus1, "2", ",", ".").''.$d.'</b></td> 
							   <td align="right" valign="top"><b>'.$e.''.number_format($sisa_surplus1, "2", ",", ".").''.$f.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen_surplus, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 6;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$nomor.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$g.''.number_format($ang_netto1, "2", ",", ".").''.$h.'</b></td> 
							   <td align="right" valign="top" ><b>'.$i.''.number_format($nil_netto1, "2", ",", ".").''.$j.'</b></td> 
							   <td align="right" valign="top" ><b>'.$k.''.number_format($sisa_netto1, "2", ",", ".").''.$l.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_netto, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 7;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$nomor.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$m.''.number_format($ang_silpa1, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top" ><b>'.$o.''.number_format($nil_silpa1, "2", ",", ".").''.$p.'</b></td> 
							   <td align="right" valign="top" ><b>'.$q.''.number_format($sisa_silpa1, "2", ",", ".").''.$r.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_silpa, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					}
					}
			 
                      
		
			$cRet .="</table>";
			
			 $cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>
					</td>
				</tr>
				</table>";       
			
			$data['prev']= $cRet;    
            $judul='PergubI_SAP ';
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
		
	function cetak_perkada_lampI_sap_sp2d($bulan='',$ctk='',$anggaran='',$jenis='',$kd_skpd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		 switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "TRIWULAN I";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "SEMESTER I";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "TRIWULAN III";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "SEMESTER II";
        break;
    }
		if ($kd_skpd=='-'){                               
            $where="";            
        } else{
			$where="AND kd_skpd='$kd_skpd'";
		}
			
		if($jenis==2){
			$tabel='data_sp2d';
		  }else if($jenis==3){
			$tabel='data_sp2d_lunas';
		  } else if($jenis==4){
			$tabel='data_sp2d_advice';
		  }

		  $sqlnogub="SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $no_perda     = $rowsc->no_perda;
                    $tgl_perda     = $rowsc->tgl_perda;
                    $no_pergub     = $rowsc->no_pergub;
                    $tgl_pergub    = $rowsc->tgl_pergub;
                 }
		 if ($test===0){
			 $no_perda="";
			 $tgl_perda="";
			 $no_pergub="";
			 $tgl_pergub="";
		 }
		  
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" > Lampiran I : </TD>
						<TD width="40%"  align="left" >Peraturan Gubernur <br>Nomor : '.$no_pergub.'<br>Tanggal: '.$tgl_pergub.'</TD>
					</TR>
					<tr>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$judul</b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
				</tr>
				<tr>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";
				
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nilai) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_surplus = $row->ang_surplus;
                       $nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $nil_surplus-$ang_surplus;
					if(($ang_surplus==0) || ($ang_surplus=='')){
						$persen_surplus=0;
					} else{
					$persen_surplus = $nil_surplus/$ang_surplus*100;
					}	
					$hasil->free_result();        
					if($ang_surplus<0){
						$ang_surplus1=$ang_surplus*-1;
						$a='(';
						$b=')';
					} else{
						$ang_surplus1=$ang_surplus;
						$a='';
						$b='';
					}
					if($nil_surplus<0){
						$nil_surplus1=$nil_surplus*-1;
						$c='(';
						$d=')';
					} else{
						$nil_surplus1=$nil_surplus;
						$c='';
						$d='';
					}
					if($sisa_surplus<0){
						$sisa_surplus1=$sisa_surplus*-1;
						$e='(';
						$f=')';
					} else{
						$sisa_surplus1=$sisa_surplus;
						$e='';
						$f='';
					}
			
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nilai) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_netto = $row->ang_netto;
                       $nil_netto = $row->nil_netto;
					}
					$sisa_netto = $nil_netto-$ang_netto;
					if(($ang_netto==0) || ($ang_netto=='')){
						$persen_netto=0;
					} else{
					$persen_netto = $nil_netto/$ang_netto*100;
					}
					$hasil->free_result();  
					if($ang_netto<0){
						$ang_netto1=$ang_netto*-1;
						$g='(';
						$h=')';
					} else{
						$ang_netto1=$ang_netto;
						$g='';
						$h='';
					}
					if($nil_netto<0){
						$nil_netto1=$nil_netto*-1;
						$i='(';
						$j=')';
					} else{
						$nil_netto1=$nil_netto;
						$i='';
						$j='';
					}
					if($sisa_netto<0){
						$sisa_netto1=$sisa_netto*-1;
						$k='(';
						$l=')';
					} else{
						$sisa_netto1=$sisa_netto;
						$k='';
						$l='';
					}	
					
					$ang_silpa = $ang_surplus+$ang_netto;
					$nil_silpa = $nil_surplus+$nil_netto;
					$sisa_silpa = $nil_silpa-$ang_silpa;
					if($ang_silpa==0){
						$persen_silpa=0;
					}else{
					$persen_silpa = $nil_silpa/$ang_silpa*100;
					}
					if($ang_silpa<0){
						$ang_silpa1=$ang_silpa*-1;
						$m='(';
						$n=')';
					} else{
						$ang_silpa1=$ang_silpa;
						$m='';
						$n='';
					}
					if($nil_silpa<0){
						$nil_silpa1=$nil_silpa*-1;
						$o='(';
						$p=')';
					} else{
						$nil_silpa1=$nil_silpa;
						$o='';
						$p='';
					}
					if($sisa_silpa<0){
						$sisa_silpa1=$sisa_silpa*-1;
						$q='(';
						$r=')';
					} else{
						$sisa_silpa1=$sisa_silpa;
						$q='';
						$r='';
					}	
			$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi, jns_rek FROM map_lra_sap ORDER BY seq
					";
					$no=0;
					$tot_peg=0;
					$tot_brg=0;
					$tot_mod=0;
					$tot_bansos=0;
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
                       //$jenis = $row->jenis;
                       $spasi = $row->spasi;
					   $jns_rek = $row->jns_rek;
					   
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
					$sql = "SELECT SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4)) $where
					";
										
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
                       $nil_ang = $row->nil_ang;
					   $nilai = $row->nilai;
					}
					//coba
					if ($jns_rek=='1'){
						$sel = $nilai-$nil_ang;
					}else{
						$sel = $nil_ang-$nilai;
					}
					
					if($sel<0){
						$x = "(";
						$y = ")";
						$sel1 = $sel*-1;
					}else{
						$x = "";
						$y = "";
						$sel1 = $sel;
					}
					
					if(($nil_ang==0) || ($nil_ang=='')){
						$persen=0;
					} else{
					$persen = $nilai/$nil_ang*100;
					}
					 switch ($spasi) {
					 case 1:
                        $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>'; 
                        break;	
                    case 2:
                         $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					 case 3:
                         $cRet .='<tr>
							   <td align="left" valign="top">'.$kode.'</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
                        break;
					case 4:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 5:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.''.number_format($ang_surplus1, "2", ",", ".").''.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$c.''.number_format($nil_surplus1, "2", ",", ".").''.$d.'</b></td> 
							   <td align="right" valign="top"><b>'.$e.''.number_format($sisa_surplus1, "2", ",", ".").''.$f.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen_surplus, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 6;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$g.''.number_format($ang_netto1, "2", ",", ".").''.$h.'</b></td> 
							   <td align="right" valign="top" ><b>'.$i.''.number_format($nil_netto1, "2", ",", ".").''.$j.'</b></td> 
							   <td align="right" valign="top" ><b>'.$k.''.number_format($sisa_netto1, "2", ",", ".").''.$l.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_netto, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 7;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$m.''.number_format($ang_silpa1, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top" ><b>'.$o.''.number_format($nil_silpa1, "2", ",", ".").''.$p.'</b></td> 
							   <td align="right" valign="top" ><b>'.$q.''.number_format($sisa_silpa1, "2", ",", ".").''.$r.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_silpa, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					}
					}
			 
                      
		
			$cRet .="</table>";
			$data['prev']= $cRet;    
            $judul='PerbugI ';
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
		
	function cetak_perkada_lampI_permen_spj($bulan='',$ctk='',$anggaran='',$jenis='',$kd_skpd='',$ttd='',$tglttd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		 switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "TRIWULAN I";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "SEMESTER I";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "TRIWULAN III";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "TAHUN ANGGARAN 2016";
        break;
    }
	
	if($bulan== 12){
			$sumber_data= "_at";
		}else{
			$sumber_data= "";
		}
	
		if ($kd_skpd=='-'){                               
            $where="";            
        } else{
			$where="AND kd_skpd='$kd_skpd'";
		}
		
		if($tglttd=='-' || $tglttd==''){
			$tanggal='';
		}else{
			$tanggal= 'Pontianak, '.$this->tukd_model->tanggal_format_indonesia($tglttd);
		}
		
		if($ttd=='-' || $ttd==''){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='GUB'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}
		
		$sqlnogub="SELECT ket_pergub, ket_pergub_no, ket_pergub_tentang FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $ket_lampiran      = strtoupper("Lampiran I");
                    $ket_pergub         = strtoupper($rowsc->ket_pergub);
                    $ket_pergub_no      = strtoupper($rowsc->ket_pergub_no);
                    $ket_pergub_tentang = strtoupper($rowsc->ket_pergub_tentang);
                 }
	
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >'.$ket_lampiran.'</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >'.$ket_pergub.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_pergub_no.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_pergub_tentang.'</TD>
					</TR>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$judul</b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
				</tr>
				<tr>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";
				
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (debet-kredit) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_surplus = $row->ang_surplus;
                       $nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $nil_surplus-$ang_surplus;
						if(($ang_surplus==0) || ($ang_surplus=='')){
						$persen_surplus=0;
					} else{
					$persen_surplus = $nil_surplus/$ang_surplus*100;
					}	
					
					$cek = $ang_surplus;
					
					$hasil->free_result();        
					if($ang_surplus<0){
						$ang_surplus1=$ang_surplus*-1;
						$a='(';
						$b=')';
					} else{
						$ang_surplus1=$ang_surplus;
						$a='';
						$b='';
					}
					if($nil_surplus<0){
						$nil_surplus1=$nil_surplus*-1;
						$c='(';
						$d=')';
					} else{
						$nil_surplus1=$nil_surplus;
						$c='';
						$d='';
					}
					if($sisa_surplus<0){
						$sisa_surplus1=$sisa_surplus*-1;
						$e='(';
						$f=')';
					} else{
						$sisa_surplus1=$sisa_surplus;
						$e='';
						$f='';
					}
			
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (kredit-debet) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (debet-kredit) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nilai) as nil_ang, SUM(kredit) as kredit,SUM(debet) as debet FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE LEFT(kd_rek5,2) IN ('61','62') $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_netto = $row->ang_netto;
                       $nil_netto = $row->nil_netto;
					}
					$sisa_netto = $nil_netto-$ang_netto;
					if(($ang_netto==0) || ($ang_netto=='')){
						$persen_netto=0;
					} else{
					$persen_netto = $nil_netto/$ang_netto*100;
					}
					$hasil->free_result();  
					if($ang_netto<0){
						$ang_netto1=$ang_netto*-1;
						$g='(';
						$h=')';
					} else{
						$ang_netto1=$ang_netto;
						$g='';
						$h='';
					}
					if($nil_netto<0){
						$nil_netto1=$nil_netto*-1;
						$i='(';
						$j=')';
					} else{
						$nil_netto1=$nil_netto;
						$i='';
						$j='';
					}
					if($sisa_netto<0){
						$sisa_netto1=$sisa_netto*-1;
						$k='(';
						$l=')';
					} else{
						$sisa_netto1=$sisa_netto;
						$k='';
						$l='';
					}	
					
					
					$ang_silpa = $ang_surplus+$ang_netto;
					$nil_silpa = $nil_surplus+$nil_netto;
					$sisa_silpa = $nil_silpa-$ang_silpa;
					if($ang_silpa==0){
						$persen_silpa=0;
					}else{
					$persen_silpa = $nil_silpa/$ang_silpa*100;
					}
					if($ang_silpa<0){
						$ang_silpa1=$ang_silpa*-1;
						$m='(';
						$n=')';
					} else{
						$ang_silpa1=$ang_silpa;
						$m='';
						$n='';
					}
					if($nil_silpa<0){
						$nil_silpa1=$nil_silpa*-1;
						$o='(';
						$p=')';
					} else{
						$nil_silpa1=$nil_silpa;
						$o='';
						$p='';
					}
					if($sisa_silpa<0){
						$sisa_silpa1=$sisa_silpa*-1;
						$q='(';
						$r=')';
					} else{
						$sisa_silpa1=$sisa_silpa;
						$q='';
						$r='';
					}	
			$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi FROM map_lra_permen ORDER BY seq
					";
					$no=0;
					$tot_peg=0;
					$tot_brg=0;
					$tot_mod=0;
					$tot_bansos=0;
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
					$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal_n$sumber_data($bulan,$anggaran,$lntahunang) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4)) $where
					";
										
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
                       $nil_ang = $row->nil_ang;
					   $nilai = $row->nilai;
					}
					$sel1 = $nilai-$nil_ang;
					
					if($sel1<0){
						$sel=$sel1*-1;
						$m='(';
						$n=')';
					} else{
						$sel=$sel1;
						$m='';
						$n='';
					}
					
					if(($nil_ang==0) || ($nil_ang=='')){
						$persen=0;
					} else{
					$persen = $nilai/$nil_ang*100;
					}
					 switch ($spasi) {
					 case 1:
                        $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$m.''.number_format($sel, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>'; 
                        break;	
                    case 2:
                         $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$m.''.number_format($sel, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					 case 3:
                         $cRet .='<tr>
							   <td align="left" valign="top">'.$kode.'</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$m.''.number_format($sel, "2", ",", ".").''.$n.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
                        break;
					case 4:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$m.''.number_format($sel, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 5:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.''.number_format($ang_surplus1, "2", ",", ".").''.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$c.''.number_format($nil_surplus1, "2", ",", ".").''.$d.'</b></td> 
							   <td align="right" valign="top"><b>'.$e.''.number_format($sisa_surplus1, "2", ",", ".").''.$f.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen_surplus, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 6;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$g.''.number_format($ang_netto1, "2", ",", ".").''.$h.'</b></td> 
							   <td align="right" valign="top" ><b>'.$i.''.number_format($nil_netto1, "2", ",", ".").''.$j.'</b></td> 
							   <td align="right" valign="top" ><b>'.$k.''.number_format($sisa_netto1, "2", ",", ".").''.$l.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_netto, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 7;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$m.''.number_format($ang_silpa1, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top" ><b>'.$o.''.number_format($nil_silpa1, "2", ",", ".").''.$p.'</b></td> 
							   <td align="right" valign="top" ><b>'.$q.''.number_format($sisa_silpa1, "2", ",", ".").''.$r.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_silpa, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					}
					}
			 
                      
		
			$cRet .="</table>";
			
			 $cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>
					</td>
				</tr>
				</table>";       
			
			
			$data['prev']= $cRet;    
            $judul='PergubI_PERMEN ';
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
		
	function cetak_perkada_lampI_permen_sp2d($bulan='',$ctk='',$anggaran='',$jenis='', $kd_skpd=''){
        $lntahunang = $this->session->userdata('pcThang');       
		 switch  ($bulan){
        case  1:
        $judul="JANUARI";
        break;
        case  2:
        $judul="FEBRUARI";
        break;
        case  3:
        $judul= "TRIWULAN I";
        break;
        case  4:
        $judul="APRIL";
        break;
        case  5:
        $judul= "MEI";
        break;
        case  6:
        $judul= "SEMESTER I";
        break;
        case  7:
        $judul= "JULI";
        break;
        case  8:
        $judul= "AGUSTUS";
        break;
        case  9:
        $judul= "TRIWULAN III";
        break;
        case  10:
        $judul= "OKTOBER";
        break;
        case  11:
        $judul= "NOVEMBER";
        break;
        case  12:
        $judul= "SEMESTER II";
        break;
    }
		
		if ($kd_skpd=='-'){                               
            $where="";            
        } else{
			$where="AND kd_skpd='$kd_skpd'";
		}
		
		if($jenis==2){
			$tabel='data_sp2d';
		  }else if($jenis==3){
			$tabel='data_sp2d_lunas';
		  } else if($jenis==4){
			$tabel='data_sp2d_advice';
		  }

		$sqlnogub="SELECT no_perda, tgl_perda,no_pergub, tgl_pergub FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $no_perda     = $rowsc->no_perda;
                    $tgl_perda     = $rowsc->tgl_perda;
                    $no_pergub     = $rowsc->no_pergub;
                    $tgl_pergub    = $rowsc->tgl_pergub;
                 }
		 if ($test===0){
			 $no_perda="";
			 $tgl_perda="";
			 $no_pergub="";
			 $tgl_pergub="";
		 }
		 
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD  width="60%" valign="top" align="right" > Lampiran I : </TD>
						<TD width="40%"  align="left" >Peraturan Gubernur <br>Nomor : '.$no_pergub.'<br>Tanggal: '.$tgl_pergub.' </TD>
					</TR>
					</TABLE><br/>';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$judul</b></tr>
					</TABLE>";
			
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
				</tr>
				<tr>
					<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
				</tr>
				</thead>";
				
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nil_ang) ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='5' THEN (nilai) ELSE 0 END) as nil_surplus
					FROM
					(SELECT LEFT(kd_rek5,1) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran) WHERE LEFT(kd_rek5,1) IN ('4','5') $where
					GROUP BY LEFT(kd_rek5,1)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_surplus = $row->ang_surplus;
                       $nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $nil_surplus-$ang_surplus;
						if(($ang_surplus==0) || ($ang_surplus=='')){
						$persen_surplus=0;
					} else{
					$persen_surplus = $nil_surplus/$ang_surplus*100;
					}	
					$hasil->free_result();        
					if($ang_surplus<0){
						$ang_surplus1=$ang_surplus*-1;
						$a='(';
						$b=')';
					} else{
						$ang_surplus1=$ang_surplus;
						$a='';
						$b='';
					}
					if($nil_surplus<0){
						$nil_surplus1=$nil_surplus*-1;
						$c='(';
						$d=')';
					} else{
						$nil_surplus1=$nil_surplus;
						$c='';
						$d='';
					}
					if($sisa_surplus<0){
						$sisa_surplus1=$sisa_surplus*-1;
						$e='(';
						$f=')';
					} else{
						$sisa_surplus1=$sisa_surplus;
						$e='';
						$f='';
					}
			
			$sql = "SELECT 
					SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
					SUM(CASE WHEN kd_rek='61' THEN (nilai) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nilai) ELSE 0 END) as nil_netto
					FROM
					(SELECT LEFT(kd_rek5,2) as kd_rek, SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran) WHERE LEFT(kd_rek5,2) IN ('61','62')  $where
					GROUP BY LEFT(kd_rek5,2)) a;
					";
					  $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $ang_netto = $row->ang_netto;
                       $nil_netto = $row->nil_netto;
					}
					$sisa_netto = $nil_netto-$ang_netto;
					if(($ang_netto==0) || ($ang_netto=='')){
						$persen_netto=0;
					} else{
					$persen_netto = $nil_netto/$ang_netto*100;
					}
					$hasil->free_result();  
					if($ang_netto<0){
						$ang_netto1=$ang_netto*-1;
						$g='(';
						$h=')';
					} else{
						$ang_netto1=$ang_netto;
						$g='';
						$h='';
					}
					if($nil_netto<0){
						$nil_netto1=$nil_netto*-1;
						$i='(';
						$j=')';
					} else{
						$nil_netto1=$nil_netto;
						$i='';
						$j='';
					}
					if($sisa_netto<0){
						$sisa_netto1=$sisa_netto*-1;
						$k='(';
						$l=')';
					} else{
						$sisa_netto1=$sisa_netto;
						$k='';
						$l='';
					}	
					
					$ang_silpa = $ang_surplus+$ang_netto;
					$nil_silpa = $nil_surplus+$nil_netto;
					$sisa_silpa = $nil_silpa-$ang_silpa;
					if($ang_silpa==0){
						$persen_silpa=0;
					}else{
					$persen_silpa = $nil_silpa/$ang_silpa*100;
					}
					if($ang_silpa<0){
						$ang_silpa1=$ang_silpa*-1;
						$m='(';
						$n=')';
					} else{
						$ang_silpa1=$ang_silpa;
						$m='';
						$n='';
					}
					if($nil_silpa<0){
						$nil_silpa1=$nil_silpa*-1;
						$o='(';
						$p=')';
					} else{
						$nil_silpa1=$nil_silpa;
						$o='';
						$p='';
					}
					if($sisa_silpa<0){
						$sisa_silpa1=$sisa_silpa*-1;
						$q='(';
						$r=')';
					} else{
						$sisa_silpa1=$sisa_silpa;
						$q='';
						$r='';
					}	
			$sql = "SELECT seq, kode, nama, kode1, kode2, kode3,kode4, jenis, spasi, jns_rek FROM map_lra_permen ORDER BY seq
					";
					$no=0;
					$tot_peg=0;
					$tot_brg=0;
					$tot_mod=0;
					$tot_bansos=0;
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
                       //$jenis = $row->jenis;
                       $spasi = $row->spasi;
					   $jns_rek = $row->jns_rek;
					   
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
					$sql = "SELECT SUM(nil_ang) as nil_ang, SUM(nilai) as nilai FROM $tabel($bulan,$anggaran) WHERE (LEFT(kd_rek5,1) IN ($kode1) or LEFT(kd_rek5,2) IN ($kode2) or LEFT(kd_rek5,3) IN ($kode3) or LEFT(kd_rek5,5) IN($kode4))  $where
					";
										
					
                    $hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
                       $nil_ang = $row->nil_ang;
					   $nilai = $row->nilai;
					}
					
					//coba
					if ($jns_rek=='1'){
						$sel = $nilai-$nil_ang;
					}else{
						$sel = $nil_ang-$nilai;
					}
					
					if($sel<0){
						$x = "(";
						$y = ")";
						$sel1 = $sel*-1;
					}else{
						$x = "";
						$y = "";
						$sel1 = $sel;
					}
					
					if(($nil_ang==0) || ($nil_ang=='')){
						$persen=0;
					} else{
					$persen = $nilai/$nil_ang*100;
					}
					 switch ($spasi) {
					 case 1:
                        $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>'; 
                        break;	
                    case 2:
                         $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					 case 3:
                         $cRet .='<tr>
							   <td align="left" valign="top">'.$kode.'</b></td> 
							   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($nilai, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							</tr>';
                        break;
					case 4:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nilai, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$x.''.number_format($sel1, "2", ",", ".").''.$y.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 5:
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top"><b>'.$a.''.number_format($ang_surplus1, "2", ",", ".").''.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.$c.''.number_format($nil_surplus1, "2", ",", ".").''.$d.'</b></td> 
							   <td align="right" valign="top"><b>'.$e.''.number_format($sisa_surplus1, "2", ",", ".").''.$f.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen_surplus, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 6;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$g.''.number_format($ang_netto1, "2", ",", ".").''.$h.'</b></td> 
							   <td align="right" valign="top" ><b>'.$i.''.number_format($nil_netto1, "2", ",", ".").''.$j.'</b></td> 
							   <td align="right" valign="top" ><b>'.$k.''.number_format($sisa_netto1, "2", ",", ".").''.$l.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_netto, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					case 7;
                       $cRet .='<tr>
							   <td align="left" valign="top" ><b>'.$kode.'</b></td> 
							   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$nama.'</b></td> 
							   <td align="right" valign="top" ><b>'.$m.''.number_format($ang_silpa1, "2", ",", ".").''.$n.'</b></td> 
							   <td align="right" valign="top" ><b>'.$o.''.number_format($nil_silpa1, "2", ",", ".").''.$p.'</b></td> 
							   <td align="right" valign="top" ><b>'.$q.''.number_format($sisa_silpa1, "2", ",", ".").''.$r.'</b></td> 
							   <td align="right" valign="top" ><b>'.number_format($persen_silpa, "2", ",", ".").'</b></td> 
							</tr>';
                        break;
					}
					}
			 
                      
		
			$cRet .="</table>";
			$data['prev']= $cRet;    
            $judul='PergubI_PERMEN ';
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
	
function cetak_perkada_lampII($bulan='',$ctk='',$anggaran='',$kd_skpd='',$jenis='',$tglttd='', $ttd=''){
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
	
	if($bulan== 12){
			$sumber_data= "_at";
		}else{
			$sumber_data= "";
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
	}				$nip = '';
	$sqlnogub="SELECT ket_pergub, ket_pergub_no, ket_pergub_tentang FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $ket_lampiran      = strtoupper("Lampiran II");
                    $ket_pergub         = strtoupper($rowsc->ket_pergub);
                    $ket_pergub_no      = strtoupper($rowsc->ket_pergub_no);
                    $ket_pergub_tentang = strtoupper($rowsc->ket_pergub_tentang);
                 }
	
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >'.$ket_lampiran.'</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >'.$ket_pergub.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_pergub_no.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_pergub_tentang.'</TD>
					</TR>
					</TABLE><br/>';
	//$cRet ='';
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>PENJABARAN LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA DAERAH</b></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN $lntahunang </b></tr>
					</TABLE>";
		$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,1)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,1),'nm_urusan1','ms_urusan1','kd_urusan1')." </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,4)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,4),'nm_urusan','ms_urusan','kd_urusan')."</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,7)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,7),'nm_org','ms_organisasi','kd_org')."</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,10)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,10),'nm_skpd','ms_skpd','kd_skpd')."</td>
					</tr>
                    </TABLE>";	
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH(BERKURANG)</b></td>
                    <td rowspan=\"2\" width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>LOKASI</b></td>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SUMBER DANA</b></td>
				</tr>
				<tr>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Rp.</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5=(4-3)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >8</td> 
				</tr>
				</thead>";
				
				$sql="SELECT RIGHT(kd_kegiatan,5) kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_pend_n$sumber_data($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)<='$jenis' ORDER BY kd_kegiatan,kd_rek";
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
							   <td align="right" valign="top"><b></td> 
							</tr>';
					   break;
					   }
					}
					$hasil->free_result();  
					
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_n$sumber_data($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)='3'";
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
							   <td align="right" valign="top"><b></td> 
							</tr>';
					}
					$hasil->free_result(); 
					
					$sqlsp2d="select SUM(d.nilai) as nilai_sp2d FROM trhsp2d a 
								INNER JOIN trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm
								INNER JOIN trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp
								INNER JOIN trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp
								WHERE a.kd_skpd='$kd_skpd' AND status_terima='1' 
								AND MONTH(tgl_terima)<='$bulan' AND LEFT(kd_rek5,1)  in ('5','1')
								AND (c.sp2d_batal IS NULL  OR c.sp2d_batal !=1) 
								";
					$hasilsp2d = $this->db->query($sqlsp2d);
					foreach ($hasilsp2d->result() as $row)
                    {
						$nil_sp2d = $row->nilai_sp2d;
					}
					$sqlcp="SELECT SUM(nilai_cp) nilai_cp FROM 
							(select SUM(rupiah) as nilai_cp from 
							trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd 
							where d.kd_skpd ='$kd_skpd' AND jns_trans='5' AND LEFT(kd_rek5,1)<>4 AND pot_khusus IN ('2','1') AND MONTH(tgl_sts)<='$bulan'
							UNION ALL
							select SUM(rupiah) as nilai_cp from 
							trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd 
							where d.kd_skpd ='$kd_skpd' AND LEFT(kd_rek5,1)<>4 AND ((jns_trans='5' AND pot_khusus='0') OR jns_trans='1')AND MONTH(tgl_sts)<='$bulan') a
						";
					
					
					$hasilcp = $this->db->query($sqlcp);
					foreach ($hasilcp->result() as $row)
                    {
						$nil_cp = $row->nilai_cp;
					}
					
					
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_objek_n$sumber_data($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)='3'";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   
                       $nil_ang1 = $row->anggaran;
                       $sd_bulan_ini1 = $row->sd_bulan_ini;
					   $sisa1=$sd_bulan_ini1-$nil_ang1;
					   $persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 :$sd_bulan_ini1/$nil_ang1*100;
					   $sisa11 = $sisa1<0 ? $sisa1*-1 :$sisa1;
					   $xy = $sisa1<0 ? '(' :'';
					   $yz = $sisa1<0 ? ')' :'';

					   $sisa_kas = $nil_sp2d-$sd_bulan_ini1-$nil_cp;
					   
					   if($sisa_kas<0){
						   $sisakas = $sisa_kas*-1;
					   }else{
						   $sisakas = $sisa_kas;
					   }
					   
					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$xy.' '.number_format($sisa11, "2", ",", ".").' '.$yz.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							</tr>';
					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="left" valign="top"><b>SP2D</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_sp2d, "2", ",", ".").'</td> 
							</tr>';
					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="left" valign="top"><b>CP</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_cp, "2", ",", ".").'</td> 
							</tr>';
					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="left" valign="top"><b>SISA KAS = SP2D - ( SPJ + CP )</b></td> 
							   <td align="right" valign="top"><b>'	.number_format($sisakas, "2", ",", ".").'</td> 
							</tr>';
					}
					$hasil->free_result(); 
					
					
					$sql="SELECT urut,RIGHT(kd_kegiatan,5) as campur, kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini,case when urut=1 then kd_kegiatan 
					                                                                                                       when urut=2 then kd_kegiatan
                                                                                                                      ELSE kd_kegiatan+kd_rek END as coba, sumber, lokasi 
					FROM realisasi_jurnal_rinci_n$sumber_data($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)<='$jenis' and nm_rek <> 'NON PROGRAM' AND anggaran<>0 ORDER BY coba";
					$hasil = $this->db->query($sql);
					
                    foreach ($hasil->result() as $row)
                    {
					   $urut = $row->urut;
					   $campur = $row->campur;
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
                       
					   $sumber = $row->sumber;
					   $lokasi = $row->lokasi;
					   
					   $leng=strlen($kd_rek);
					   $leng_urut=$urut;
					   
					   switch ($leng_urut) {
					   case 1:
					   $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_rek.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   case 2:
					   $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_rek.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="left" valign="top">'.$lokasi.'</td> 
							   <td align="left" valign="top">'.$sumber.'</td> 
							</tr>';
					   break;
					   case 3:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$campur.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   case 4:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$campur.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   case 5:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$campur.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   default:
					     $cRet .='<tr>
							   <td align="left" valign="top">'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
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
							   <td align="right" valign="top"><b>'.$xy.' '.number_format($sisa11, "2", ",", ".").' '.$yz.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
			
			if($kd_skpd=='3.13.01.17'){
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_objek_biaya_n$sumber_data($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)='3'";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   
                       $nil_ang1 = $row->anggaran;
                       $sd_bulan_ini1 = $row->sd_bulan_ini;
					   $sisa1=$sd_bulan_ini1-$nil_ang1;
					   $persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 :$sd_bulan_ini1/$nil_ang1*100;
					   $sisa11 = $sisa1<0 ? $sisa1*-1 :$sisa1;
					   $xy = $sisa1<0 ? '(' :'';
					   $yz = $sisa1<0 ? ')' :'';

					   $sisa_kas = $nil_sp2d-$sd_bulan_ini1-$nil_cp;
					   
					   if($sisa_kas<0){
						   $sisakas = $sisa_kas*-1;
					   }else{
						   $sisakas = $sisa_kas;
					   }
					   
					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>PEMBIAYAAN</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$xy.' '.number_format($sisa11, "2", ",", ".").' '.$yz.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							</tr>';
					}
					$hasil->free_result(); 
					
					
					$sql="SELECT urut,RIGHT(kd_kegiatan,5) as campur, kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini,case when urut=1 then kd_kegiatan 
					                                                                                                       when urut=2 then kd_kegiatan
                                                                                                                      ELSE kd_kegiatan+kd_rek END as coba, sumber, lokasi 
					FROM realisasi_jurnal_rinci_biaya_n$sumber_data($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)<='$jenis' and nm_rek <> 'NON PROGRAM' AND anggaran<>0 ORDER BY coba";
					$hasil = $this->db->query($sql);
					
                    foreach ($hasil->result() as $row)
                    {
					   $urut = $row->urut;
					   $campur = $row->campur;
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
                       
					   $sumber = $row->sumber;
					   $lokasi = $row->lokasi;
					   
					   $leng=strlen($kd_rek);
					   $leng_urut=$urut;
					   
					   switch ($leng_urut) {
					   case 1:
					   $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_rek.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   case 2:
					   $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$kd_rek.'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="left" valign="top">'.$lokasi.'</td> 
							   <td align="left" valign="top">'.$sumber.'</td> 
							</tr>';
					   break;
					   case 3:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$campur.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   case 4:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$campur.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   case 5:
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$campur.'.'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   default:
					     $cRet .='<tr>
							   <td align="left" valign="top">'.$this->dotrek($kd_rek).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							</tr>';
					   break;
					   }
					}
					$hasil->free_result();  
					$cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH PEMBIAYAAN</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$xy.' '.number_format($sisa11, "2", ",", ".").' '.$yz.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></td> 
							</tr>';
			}
			
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
            $judul='PergubII ';
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

function cetak_perkada_lampII_org($bulan='',$ctk='',$anggaran='',$kd_skpd='',$jenis='',$tglttd='', $ttd='', $js=''){
        $lntahunang = $this->session->userdata('pcThang');  
		$cthn = "2018";
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
	
		if($bulan== 12){
			$sumber_data= "_at";
		}else{
			$sumber_data= "";
		}
	
		/* if ($js=='1'){                               
            $where= "WHERE left(kd_skpd,7)='$kd_skpd'";
        } else {                               
            $where= "WHERE kd_org='$kd_skpd'";
        } */
		
		$where= "WHERE left(kd_skpd,7)='$kd_skpd' AND kd_skpd<>'3.13.01.17'";
		
		
		if ($anggaran=='1'){                               
            $sumber1= "sumber";
            $sumber2= "sumber2";
            $sumber3= "sumber3";
            $sumber4= "sumber4";
        } 
		else if ($anggaran=='2'){                               
            $sumber1= "sumber1_su";
            $sumber2= "sumber2_su";
            $sumber3= "sumber3_su";
            $sumber4= "sumber4_su";
        } else {                               
            $sumber1= "sumber1_ubah";
            $sumber2= "sumber2_ubah";
            $sumber3= "sumber3_ubah";
            $sumber4= "sumber4_ubah";        
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
	}				$nip='';
	

	$sqlnogub="SELECT ket_pergub, ket_pergub_no, ket_pergub_tentang FROM config_nogub";
                 $sqlnogub=$this->db->query($sqlnogub);
				 $test = $sqlnogub->num_rows();
                 foreach ($sqlnogub->result() as $rowsc){
                    $ket_lampiran      = strtoupper("Lampiran II");
                    $ket_pergub         = strtoupper($rowsc->ket_pergub);
                    $ket_pergub_no      = strtoupper($rowsc->ket_pergub_no);
                    $ket_pergub_tentang = strtoupper($rowsc->ket_pergub_tentang);
                 }
	
		$cRet ='<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >'.$ket_lampiran.'</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >'.$ket_pergub.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_pergub_no.'</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >'.$ket_pergub_tentang.'</TD>
					</TR>
					</TABLE><br/>';
					
	$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>PENJABARAN LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA DAERAH</b></tr>
                    
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN $lntahunang </b></tr>
					</TABLE>";
		$cRet .="<TABLE style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,1)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,1),'nm_urusan1','ms_urusan1','kd_urusan1')." </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Bidang Pemerintahan </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,4)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,4),'nm_urusan','ms_urusan','kd_urusan')."</td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : ".$this->left($kd_skpd,7)." - ".$this->tukd_model->get_nama($this->left($kd_skpd,7),'nm_org','ms_organisasi','kd_org')."</td>
					</tr>
                    </TABLE>";	
		$cRet .="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
                    <td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH(BERKURANG)</b></td>
                    <td rowspan=\"2\" width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>LOKASI</b></td>
                    <td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SUMBER DANA</b></td>
				</tr>
				<tr>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Rp.</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >5=(4-3)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >6)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >8</td> 
				</tr>
				</thead>";
				
				$sql="SELECT RIGHT(kd_kegiatan,5) kd_kegiatan ,kd_rek,nm_rek, SUM(anggaran) anggaran, SUM(sd_bulan_ini) sd_bulan_ini  FROM realisasi_jurnal_pend_n$sumber_data($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)<='$jenis' 
				GROUP BY RIGHT(kd_kegiatan,5),kd_rek,nm_rek
				ORDER BY kd_kegiatan,kd_rek";
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
							   <td align="right" valign="top"><b></td> 
							</tr>';
					   break;
					   }
					}
					$hasil->free_result();  
					
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_pend_n$sumber_data($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)='3'";
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
							   <td align="right" valign="top"><b></td> 
							</tr>';
					}
					$hasil->free_result(); 
					
					$sqlsp2d="select SUM(d.nilai) as nilai_sp2d FROM trhsp2d a 
								INNER JOIN trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm
								INNER JOIN trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp
								INNER JOIN trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp
								WHERE LEFT(a.kd_skpd,7)='$kd_skpd' AND a.kd_skpd<>'3.13.01.17' AND status_terima='1' 
								AND MONTH(tgl_terima)<='$bulan' AND YEAR(tgl_terima)=$lntahunang AND LEFT(kd_rek5,1)  in ('5','1')
								AND (c.sp2d_batal IS NULL  OR c.sp2d_batal !=1)  
								";
					$hasilsp2d = $this->db->query($sqlsp2d);
					foreach ($hasilsp2d->result() as $row)
                    {
						$nil_sp2d = $row->nilai_sp2d;
					}
					$sqlcp="SELECT SUM(nilai_cp) nilai_cp FROM 
							(select SUM(rupiah) as nilai_cp from 
							trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd 
							where LEFT(d.kd_skpd,7) ='$kd_skpd' AND d.kd_skpd<>'3.13.01.17' AND jns_trans='5' AND pot_khusus IN ('2','1') AND MONTH(tgl_sts)<='$bulan'
							UNION ALL
							select SUM(rupiah) as nilai_cp from 
							trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd 
							where LEFT(d.kd_skpd,7) ='$kd_skpd' AND d.kd_skpd<>'3.13.01.17' AND ((jns_trans='5' AND pot_khusus='0') OR jns_trans='1')AND MONTH(tgl_sts)<='$bulan') a
						";
					
					
					$hasilcp = $this->db->query($sqlcp);
					foreach ($hasilcp->result() as $row)
                    {
						$nil_cp = $row->nilai_cp;
					}
					
					
					$sql="SELECT SUM(anggaran) anggaran ,SUM(sd_bulan_ini) sd_bulan_ini FROM realisasi_jurnal_objek_n$sumber_data($bulan,$anggaran,$lntahunang) $where AND LEN(kd_rek)='3'";
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   
                       $nil_ang1 = $row->anggaran;
                       $sd_bulan_ini1 = $row->sd_bulan_ini;
					   $sisa1=$sd_bulan_ini1-$nil_ang1;
					   $persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 :$sd_bulan_ini1/$nil_ang1*100;
					   $sisa11 = $sisa1<0 ? $sisa1*-1 :$sisa1;
					   $xy = $sisa<0 ? '(' :'';
					   $yz = $sisa<0 ? ')' :'';

					   $sisa_kas = $nil_sp2d-$sd_bulan_ini1-$nil_cp;
					   
					   if($sisa_kas<0){
						   $sisakas = $sisa_kas*-1;
					   }else{
						   $sisakas = $sisa_kas;
					   }
					   
					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$xy.' '.number_format($sisa11, "2", ",", ".").' '.$yz.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen1, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							</tr>';
					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="left" valign="top"><b>SP2D</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_sp2d, "2", ",", ".").'</td> 
							</tr>';
					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="left" valign="top"><b>CP</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_cp, "2", ",", ".").'</td> 
							</tr>';
					   $cRet .='<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="left" valign="top"><b>SISA KAS = SP2D - ( SPJ + CP )</b></td> 
							   <td align="right" valign="top"><b>'	.number_format($sisakas, "2", ",", ".").'</td> 
							</tr>';
					}
					$hasil->free_result(); 					
					  $sql="SELECT kd_rek, LEFT(kd_rek,5) as cek, REPLACE(kd_rek, left(kd_rek,6), '') as coba,  nm_rek,SUM(anggaran) anggaran,
							SUM(sd_bulan_ini) sd_bulan_ini, 
							(select top 1 case when $sumber4='' and $sumber3='' and $sumber2='' then $sumber1 
							when $sumber4='' and $sumber3='' and $sumber2<>'' and $sumber1<>'' then $sumber1+', '+$sumber2
							when $sumber4='' and $sumber3<>'' and $sumber2<>'' and $sumber1<>'' then $sumber1+', '+$sumber2+', '+ $sumber3 
							else $sumber1+', '+$sumber2+', '+$sumber3+', '+$sumber4 end as sumber FROM trdrka where LEFT(kd_skpd,7)='$kd_skpd' AND kd_skpd<>'3.13.01.17' and right(kd_kegiatan,5)=kd_rek order by kd_skpd, kd_rek) sumber, 
							(select top 1 lokasi from trskpd where LEFT(kd_skpd,7)='$kd_skpd' and right(kd_kegiatan,5)=kd_rek order by kd_skpd, kd_rek) lokasi  
							FROM realisasi_jurnal_rinci_skpd($bulan,$anggaran,$lntahunang) a WHERE LEFT(kd_skpd,7)='$kd_skpd' AND kd_skpd<>'3.13.01.17'
							and nm_rek <> 'NON PROGRAM' and anggaran<>0 and sd_bulan_ini<>0
							GROUP BY kd_rek, nm_rek
							ORDER BY kd_rek";
					$hasil = $this->db->query($sql);
					
                    foreach ($hasil->result() as $row)
                    {
					   $kd_rek = $row->kd_rek;
					   $cek = $row->cek;
					   $coba = $row->coba;
                       $nm_rek = $row->nm_rek;
                       $nil_ang = $row->anggaran;
                       $sd_bulan_ini = $row->sd_bulan_ini;
					   $sisa=$sd_bulan_ini-$nil_ang;
					   $persen = empty($nil_ang) || $nil_ang == 0 ? 0 :$sd_bulan_ini/$nil_ang*100;
					   $sisa1 = $sisa<0 ? $sisa*-1 :$sisa;
					   $a = $sisa<0 ? '(' :'';
					   $b = $sisa<0 ? ')' :'';

					   $sumber = $row->sumber;
					   $lokasi = $row->lokasi;
					   
					   $leng=strlen($kd_rek);
					   if ($leng<=5){
						   $cRet .='<tr>
							   <td align="left" valign="top"><b>'.$cek.''.$this->dotrek($coba).'</b></td> 
							   <td align="left"  valign="top"><b>'.$nm_rek.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($nil_ang, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($sd_bulan_ini, "2", ",", ".").'</b></td> 
							   <td align="right" valign="top"><b>'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</b></td> 
							   <td align="right" valign="top"><b>'.number_format($persen, "2", ",", ".").'</b></td> 
							   <td align="left" valign="top">'.$lokasi.'</td> 
							   <td align="left" valign="top">'.$sumber.'</td> 
							</tr>';
					   }else{
						   $cRet .='<tr>
							   <td align="left" valign="top">'.$cek.'.'.$this->dotrek($coba).'</td> 
							   <td align="left"  valign="top">'.$nm_rek.'</td> 
							   <td align="right" valign="top">'.number_format($nil_ang, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($sd_bulan_ini, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.$a.' '.number_format($sisa1, "2", ",", ".").' '.$b.'</td> 
							   <td align="right" valign="top">'.number_format($persen, "2", ",", ".").'</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td> 
							</tr>';
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
                    <td width=\"50%\" align=\"center\">$tanggal<br>$jabatan<br><br><br><br><br><br><b>$nama</b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";
			$data['prev']= $cRet;    
            $judul='PergubII ';
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
		
		
	function cetak_rekap($bulan='',$ctk='',$anggaran=''){
        $lntahunang = $this->session->userdata('pcThang');       
				
		$cRet ="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">kd_urusan2</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">nm_urusan2</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">kd_urusan1</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">nm_urusan1</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">kodeAkunUtama</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">namaAkunUtama</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">kodeAkunKelompok</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">namaAkunKelompok</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">kodeAkunJenis</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">namaAkunJenis</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">kodeAkunObjek</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">namaAkunObjek</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">kodeAkunRincian</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">namaAkunRincian</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">kd_organisasi</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">nama_organisasi</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">kd_unit_organisasi</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">kd_unit_organisasi</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">nama_unit_organisasi</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">kd_program</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">nama_program</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">kd_kegiatan</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">nama_kegiatan</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">kd_rek5</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">nilai_anggaran</td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\">realisasi</td>
				</tr>
				</thead>";
				
				/*$sql="SELECT 
						SUBSTRING(a.kd_kegiatan,1,4) AS kd_urusan2,
						(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(a.kd_kegiatan,1,4)) AS nm_urusan2,
						SUBSTRING(a.kd_skpd,1,1) AS kd_urusan1,
						(select nm_urusan1 from dbo.ms_urusan1 where kd_urusan1=SUBSTRING(a.kd_skpd,1,1)) AS nm_urusan1,
						SUBSTRING(a.kd_rek5,1,1) AS kodeAkunUtama,
						(SELECT DISTINCT nm_rek1 FROM ms_rek1 WHERE kd_rek1=SUBSTRING(a.kd_rek5,1,1)) AS namaAkunUtama,
						SUBSTRING(a.kd_rek5,2,1) AS kodeAkunKelompok,
						(SELECT DISTINCT nm_rek2 FROM ms_rek2 WHERE kd_rek2=SUBSTRING(a.kd_rek5,1,2)) AS namaAkunKelompok,
						SUBSTRING(a.kd_rek5,3,1) AS kodeAkunJenis,
						(SELECT DISTINCT nm_rek3 FROM ms_rek3 WHERE kd_rek3=SUBSTRING(a.kd_rek5,1,3)) AS namaAkunJenis,
						SUBSTRING(a.kd_rek5,4,2) AS kodeAkunObjek,
						(SELECT DISTINCT nm_rek4 FROM ms_rek4 WHERE kd_rek4=SUBSTRING(a.kd_rek5,1,5)) AS namaAkunObjek,
						SUBSTRING(a.kd_rek5,6,2) AS kodeAkunRincian,
						(SELECT DISTINCT nm_rek5 FROM ms_rek5 WHERE kd_rek5=a.kd_rek5) AS namaAkunRincian,
						SUBSTRING(a.kd_skpd,1,7) AS kd_organisasi,
						(SELECT DISTINCT nm_org FROM ms_organisasi WHERE kd_org=SUBSTRING(a.kd_skpd,1,7)) AS nama_organisasi,
						a.kd_skpd as kd_unit_organisasi,
						(SELECT DISTINCT nm_skpd FROM ms_skpd WHERE kd_skpd=a.kd_skpd) AS nama_unit_organisasi,
						SUBSTRING(a.kd_kegiatan,1,18) AS kd_program,
						(SELECT DISTINCT nm_program FROM trskpd WHERE kd_program=SUBSTRING(a.kd_kegiatan,1,18) ) AS nama_program,
						a.kd_kegiatan,
						a.nm_kegiatan as nama_kegiatan,
						a.kd_rek5,
						anggaran as nilai_anggaran,
						realisasi

						FROM
						(
						SELECT a.kd_skpd,a.kd_kegiatan,a.nm_kegiatan,a.kd_rek5,a.nilai_ubah as anggaran, ISNULL(b.nilaiRealisasi,0) realisasi
						FROM trdrka a LEFT JOIN (
						select kd_skpd, kd_kegiatan,a.map_real,
						CASE WHEN SUBSTRING(a.map_real,1,1) = '5' THEN
								SUM(a.debet)-SUM(a.kredit)
						WHEN SUBSTRING(a.map_real,1,1) = '4' THEN
								SUM(a.kredit)-SUM(a.debet)
						WHEN SUBSTRING(a.map_real,1,1) = '6' AND SUBSTRING(a.map_real,2,1)='2' THEN 
								SUM(a.debet)-SUM(a.kredit)
						WHEN SUBSTRING(a.map_real,1,1) = '6' AND SUBSTRING(a.map_real,2,1)='1' THEN 
								SUM(a.kredit)-SUM(a.debet)
						END AS nilaiRealisasi
						 from trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd 
						WHERE LEFT(a.map_real,1) IN('4','5','6') 
						AND MONTH(b.tgl_voucher)<='$bulan'
						GROUP BY kd_skpd, kd_kegiatan,a.map_real) b
						ON a.kd_skpd=b.kd_skpd 
						AND a.kd_kegiatan=b.kd_kegiatan 
						AND a.kd_rek5=b.map_real
						) a
						
						order by a.kd_skpd, a.kd_kegiatan, a.kd_rek5
						";*/
						
					/* $sql="SELECT SUBSTRING(c.kd_kegiatan,1,4) AS kd_urusan2,
						(select DISTINCT nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(c.kd_kegiatan,1,4)) AS nm_urusan2,
						SUBSTRING(c.kd_skpd,1,1) AS kd_urusan1,
						(select nm_urusan1 from dbo.ms_urusan1 where kd_urusan1=SUBSTRING(c.kd_skpd,1,1)) AS nm_urusan1,
						SUBSTRING(c.kd_rek5,1,1) AS kodeAkunUtama,
						(SELECT DISTINCT nm_rek1 FROM ms_rek1 WHERE kd_rek1=SUBSTRING(c.kd_rek5,1,1)) AS namaAkunUtama,
						SUBSTRING(c.kd_rek5,2,1) AS kodeAkunKelompok,
						(SELECT DISTINCT nm_rek2 FROM ms_rek2 WHERE kd_rek2=SUBSTRING(c.kd_rek5,1,2)) AS namaAkunKelompok,
						SUBSTRING(c.kd_rek5,3,1) AS kodeAkunJenis,
						(SELECT DISTINCT nm_rek3 FROM ms_rek3 WHERE kd_rek3=SUBSTRING(c.kd_rek5,1,3)) AS namaAkunJenis,
						SUBSTRING(c.kd_rek5,4,2) AS kodeAkunObjek,
						(SELECT DISTINCT nm_rek4 FROM ms_rek4 WHERE kd_rek4=SUBSTRING(c.kd_rek5,1,5)) AS namaAkunObjek,
						SUBSTRING(c.kd_rek5,6,2) AS kodeAkunRincian,
						(SELECT DISTINCT nm_rek5 FROM ms_rek5 WHERE kd_rek5=c.kd_rek5) AS namaAkunRincian,
						SUBSTRING(c.kd_skpd,1,7) AS kd_organisasi,
						(SELECT DISTINCT nm_org FROM ms_organisasi WHERE kd_org=SUBSTRING(c.kd_skpd,1,7)) AS nama_organisasi,
						c.kd_skpd as kd_unit_organisasi,
						(SELECT DISTINCT nm_skpd FROM ms_skpd WHERE kd_skpd=c.kd_skpd) AS nama_unit_organisasi,
						SUBSTRING(c.kd_kegiatan,1,18) AS kd_program,
						(SELECT DISTINCT nm_program FROM trskpd WHERE kd_program=SUBSTRING(c.kd_kegiatan,1,18) ) AS nama_program,
						c.kd_kegiatan,
						c.nm_kegiatan as nama_kegiatan,
						c.kd_rek5,
						SUM(c.nilai_ubah) as nilai_anggaran,
						(SELECT SUM(CASE WHEN LEFT(a.map_real,1)='4' THEN a.kredit-a.debet ELSE a.debet-a.kredit END)
						FROM trdju_calk a WHERE map_real=c.kd_rek5 AND LEFT(a.no_voucher,4)='$lntahunang') as realisasi
						FROM trdrka c
						GROUP BY SUBSTRING(c.kd_kegiatan,1,4), SUBSTRING(c.kd_skpd,1,1),SUBSTRING(c.kd_rek5,1,1),
						SUBSTRING(c.kd_rek5,2,1),SUBSTRING(c.kd_rek5,3,1),SUBSTRING(c.kd_rek5,4,2),SUBSTRING(c.kd_rek5,6,2),
						SUBSTRING(c.kd_skpd,1,7),c.kd_skpd,SUBSTRING(c.kd_kegiatan,1,18),c.kd_kegiatan,c.nm_kegiatan,c.kd_rek5
						order by c.kd_skpd, c.kd_kegiatan, c.kd_rek5"; */
						
					$sql = "SELECT 
						SUBSTRING(a.kd_kegiatan,1,4) AS kd_urusan2,
						(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(a.kd_kegiatan,1,4)) AS nm_urusan2,
						SUBSTRING(a.kd_skpd,1,1) AS kd_urusan1,
						(select nm_urusan1 from dbo.ms_urusan1 where kd_urusan1=SUBSTRING(a.kd_skpd,1,1)) AS nm_urusan1,
						SUBSTRING(a.kd_rek5,1,1) AS kodeAkunUtama,
						(SELECT DISTINCT nm_rek1 FROM ms_rek1 WHERE kd_rek1=SUBSTRING(a.kd_rek5,1,1)) AS namaAkunUtama,
						SUBSTRING(a.kd_rek5,2,1) AS kodeAkunKelompok,
						(SELECT DISTINCT nm_rek2 FROM ms_rek2 WHERE kd_rek2=SUBSTRING(a.kd_rek5,1,2)) AS namaAkunKelompok,
						SUBSTRING(a.kd_rek5,3,1) AS kodeAkunJenis,
						(SELECT DISTINCT nm_rek3 FROM ms_rek3 WHERE kd_rek3=SUBSTRING(a.kd_rek5,1,3)) AS namaAkunJenis,
						SUBSTRING(a.kd_rek5,4,2) AS kodeAkunObjek,
						(SELECT DISTINCT nm_rek4 FROM ms_rek4 WHERE kd_rek4=SUBSTRING(a.kd_rek5,1,5)) AS namaAkunObjek,
						SUBSTRING(a.kd_rek5,6,2) AS kodeAkunRincian,
						(SELECT DISTINCT nm_rek5 FROM ms_rek5 WHERE kd_rek5=a.kd_rek5) AS namaAkunRincian,
						SUBSTRING(a.kd_skpd,1,7) AS kd_organisasi,
						(SELECT DISTINCT nm_org FROM ms_organisasi WHERE kd_org=SUBSTRING(a.kd_skpd,1,7)) AS nama_organisasi,
						a.kd_skpd as kd_unit_organisasi,
						(SELECT DISTINCT nm_skpd FROM ms_skpd WHERE kd_skpd=a.kd_skpd) AS nama_unit_organisasi,
						SUBSTRING(a.kd_kegiatan,1,18) AS kd_program,
						(SELECT DISTINCT nm_program FROM trskpd WHERE kd_program=SUBSTRING(a.kd_kegiatan,1,18) ) AS nama_program,
						a.kd_kegiatan,
						a.nm_kegiatan as nama_kegiatan,
						a.kd_rek5,
						anggaran as nilai_anggaran,
						realisasi

						FROM
						(
						SELECT a.kd_skpd,a.kd_kegiatan,a.nm_kegiatan,a.kd_rek5,a.nilai_sempurna as anggaran, ISNULL(b.nilaiRealisasi,0) realisasi
						FROM (select a.kd_skpd,a.kd_kegiatan,a.nm_kegiatan,a.kd_rek5,a.nilai_sempurna from trdrka a where LEFT(kd_rek5,1)<>'4'
						UNION ALL
						select a.kd_skpd,a.kd_kegiatan,a.nm_kegiatan,a.kd_rek5,a.nilai_sempurna from trdrka_pend a) a LEFT JOIN (
						select kd_skpd, kd_kegiatan,a.map_real,
						CASE WHEN SUBSTRING(a.map_real,1,1) = '5' THEN
								SUM(a.debet)-SUM(a.kredit)
						WHEN SUBSTRING(a.map_real,1,1) = '4' THEN
								SUM(a.kredit)-SUM(a.debet)
						WHEN SUBSTRING(a.map_real,1,1) = '6' AND SUBSTRING(a.map_real,2,1)='2' THEN 
								SUM(a.debet)-SUM(a.kredit)
						WHEN SUBSTRING(a.map_real,1,1) = '6' AND SUBSTRING(a.map_real,2,1)='1' THEN 
								SUM(a.kredit)-SUM(a.debet)
						END AS nilaiRealisasi
						 from trdju a INNER JOIN trhju b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd 
						WHERE LEFT(a.map_real,1) IN('4','5','6') 
						AND MONTH(b.tgl_voucher)<='$bulan' AND YEAR(b.tgl_voucher)='$lntahunang'
						GROUP BY kd_skpd, kd_kegiatan,a.map_real) b
						ON a.kd_skpd=b.kd_skpd 
						AND a.kd_kegiatan=b.kd_kegiatan 
						AND a.kd_rek5=b.map_real
						) a
						
						order by a.kd_skpd, a.kd_kegiatan, a.kd_rek5";
						
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_urusan2 = $row->kd_urusan2;
					   $nm_urusan2 = $row->nm_urusan2;
                       $kd_urusan1 = $row->kd_urusan1;
                       $nm_urusan1 = $row->nm_urusan1;
                       $kodeAkunUtama = $row->kodeAkunUtama;
                       $namaAkunUtama = $row->namaAkunUtama;
                       $kodeAkunKelompok = $row->kodeAkunKelompok;
                       $namaAkunKelompok = $row->namaAkunKelompok;
                       $kodeAkunJenis = $row->kodeAkunJenis;
                       $namaAkunJenis = $row->namaAkunJenis;
                       $kodeAkunObjek = $row->kodeAkunObjek;
                       $namaAkunObjek = $row->namaAkunObjek;
                       $kodeAkunRincian = $row->kodeAkunRincian;
                       $namaAkunRincian = $row->namaAkunRincian;
                       $kd_organisasi = $row->kd_organisasi;
                       $nama_organisasi = $row->nama_organisasi;
                       $kd_unit_organisasi = $row->kd_unit_organisasi;
                       $nama_unit_organisasi = $row->nama_unit_organisasi;
                       $kd_program = $row->kd_program;
                       $nama_program = $row->nama_program;
                       $kd_kegiatan = $row->kd_kegiatan;
                       $nama_kegiatan = $row->nama_kegiatan;
                       $kd_rek5 = $row->kd_rek5;
                       $nilai_anggaran = $row->nilai_anggaran;
                       $realisasi = $row->realisasi;
					   
					   $cRet .='<tr>
							   <td align="left" valign="top">'.$kd_urusan2.'</td> 
							   <td align="left"  valign="top">'.$nm_urusan2.'</td> 
							   <td align="left"  valign="top">'.$nm_urusan1.'</td> 
							   <td align="left"  valign="top">'.$nm_urusan1.'</td> 
							   <td align="left"  valign="top">'.$kodeAkunUtama.'</td> 
							   <td align="left"  valign="top">'.$namaAkunUtama.'</td> 
							   <td align="left"  valign="top">'.$kodeAkunKelompok.'</td> 
							   <td align="left"  valign="top">'.$namaAkunKelompok.'</td> 
							   <td align="left"  valign="top">'.$kodeAkunJenis.'</td> 
							   <td align="left"  valign="top">'.$namaAkunJenis.'</td> 
							   <td align="left"  valign="top">'.$kodeAkunObjek.'</td> 
							   <td align="left"  valign="top">'.$namaAkunObjek.'</td> 
							   <td align="left"  valign="top">'.$kodeAkunRincian.'</td> 
							   <td align="left"  valign="top">'.$namaAkunRincian.'</td> 
							   <td align="left"  valign="top">'.$kd_organisasi.'</td> 
							   <td align="left"  valign="top">'.$nama_organisasi.'</td> 
							   <td align="left"  valign="top">'.$nama_organisasi.'</td> 
							   <td align="left"  valign="top">'.$kd_unit_organisasi.'</td> 
							   <td align="left"  valign="top">'.$nama_unit_organisasi.'</td> 
							   <td align="left"  valign="top">'.$kd_program.'</td> 
							   <td align="left"  valign="top">'.$nama_program.'</td> 
							   <td align="left"  valign="top">'.$kd_kegiatan.'</td> 
							   <td align="left"  valign="top">'.$nama_kegiatan.'</td> 
							   <td align="left"  valign="top">'.$kd_rek5.'</td> 
							   <td align="right" valign="top">'.number_format($nilai_anggaran, "2", ",", ".").'</td> 
							   <td align="right" valign="top">'.number_format($realisasi, "2", ",", ".").'</td> 
							</tr>';
					   
					}
					$hasil->free_result();  
					
					
			$cRet .="</table>";
			
			$data['prev']= $cRet;    
            $judul='Rincian ';
			switch ($ctk){
				case 0;
				ini_set('memory_limit','10000M');
				set_time_limit(10000);
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
	
	
}