<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Rekon_ba extends CI_Controller
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
	
	function rekon(){
        $data['page_title']= 'BA REKON';
        $this->template->set('title', 'BA REKON');   
        $this->template->load('template','rekon/rekon_ba',$data) ;	
	}
	
	function rekap_sisa_kas(){
        $data['page_title']= 'REKAP SISA KAS';
        $this->template->set('title', 'REKAP SISA KAS');   
        $this->template->load('template','rekon/rekap_sisa_kas',$data) ;	
	}
	
	function load_ttd_terima($kdskpd=""){
		$sql = "SELECT * FROM ms_ttd where kd_skpd='$kdskpd' and kode='BP'";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;        
        foreach($mas->result_array() as $resulte)
        { 
           
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
	
	function load_ttd_keluar($kdskpd=""){
		$sql = "SELECT * FROM ms_ttd where kd_skpd='$kdskpd' and kode='BK'";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;        
        foreach($mas->result_array() as $resulte)
        { 
           
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
	
	function load_ttd_neraca($kdskpd=""){
		$sql = "SELECT * FROM ms_ttd where kd_skpd='$kdskpd' and kode='PPK'";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;        
        foreach($mas->result_array() as $resulte)
        { 
           
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
	
	function load_ttd(){
		$sql = "SELECT * FROM ms_ttd where kd_skpd='3.13.01.17' and kode='AKT' and npwp='1'";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;        
        foreach($mas->result_array() as $resulte)
        { 
           
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
	
	function load_ttd_ppk($kdskpd=""){
			$sql = "SELECT * FROM ms_ttd where kd_skpd='$kdskpd' and kode='PPK'";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;        
        foreach($mas->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'jabatan' => $resulte['jabatan']
                        );
                        $ii++;
        }     
	}
	
	function cetak_ringkasan_lra($kd_skpd="", $cetak = ''){
		$cbulan = 12;
		$id = $kd_skpd;
        $thn_ang = $this->session->userdata('pcThang');
		$bulan	 = $cbulan;
        $id1     = $this->session->userdata('kdskpd');
        $nmskpd = $this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd');
        $nm_skpd =strtoupper($nmskpd);
        $thn_ang_1= $thn_ang-1;   
        
		$tgl_ttd = $this->uri->segment(9);
		$periode1 = $this->uri->segment(13);
		$periode2 = $this->uri->segment(14);
		
		$arrayperiode1=explode("-",$periode1);
		$arrayperiode2=explode("-",$periode2);		
		
		if($arrayperiode2[1]<='3'){
			$tw = "I";
		}else if($arrayperiode2[1]<='6'){
			$tw = "II";
		}else if($arrayperiode2[1]<='9'){
			$tw = "III";
		}else{
			$tw = "IV";
		}
		
		$kabid    = str_replace('%20',' ',$this->uri->segment(7));
		
		$sqlkabid="select TOP 1 * from ms_ttd where nip='$kabid'";
                 $sqlkabid1=$this->db->query($sqlkabid);
                 foreach ($sqlkabid1->result() as $rowkabid)
                {
                    $nipkabid      = $rowkabid->nip;
                    $namakabid     = $rowkabid->nama;
                    $jabatankabid  = $rowkabid->jabatan;
                    $pangkatkabid  = $rowkabid->pangkat;
                   
                }
		
		$tgl   = substr($tgl_ttd,7,2);
		$bln   = substr($tgl_ttd,5,2);
		$thn   = substr($tgl_ttd,0,4);
		
		
		//$bln2 = substr($periode2,5,2);
		$bln2 = $arrayperiode2[1];
		
		$tanggl = $this->tukd_model->terbilang2($tgl);
		$buln  = $this->getBulan($bln);
		$tahun = $this->tukd_model->terbilang2($thn); 
		
		$tgl_periode1 = substr($periode1,7,2);
		$bln_periode1 = substr($periode1,5,1);
		$thn_periode1 = substr($periode1,0,4);
		
		$len = strlen($periode2);
		
		if($len<>'10'){
			$tgl_periode2 = substr($periode2,7,2);
			$bln_periode2 = substr($periode2,5,1);
		}else{
			$tgl_periode2 = substr($periode2,8,2);
			$bln_periode2 = substr($periode2,5,2);
		}
		
		$tanggl_prd1 = $this->tukd_model->terbilang2($tgl_periode1);
		$buln_prd1   = $this->getBulan($bln_periode1);
		
		$tanggl_prd2 = $this->tukd_model->terbilang2($tgl_periode2);
		$buln_prd2   = $this->getBulan($bln_periode2);
		
		

		$day = date('D', strtotime($tgl_ttd));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
	

	
	$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='3.13.01.17'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
				
	$sqlsc="SELECT nm_skpd FROM ms_skpd where kd_skpd='3.13.01.01'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $nm_ppkd     = $rowsc->nm_skpd;
                }
	
			    
					$cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td rowspan=\"4\" align=\"right\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:16px\"><strong>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH</strong></tr>
					<tr><td colspan=\"3\" align=\"center\"><strong>Jalan Ahmad Yani Telepon (0561) 736541 Fax (0561) 738428</strong></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PONTIANAK</strong></td></tr>
					<tr><td colspan=\"4\" align=\"right\">Kode Pos: 78124 &nbsp; &nbsp;</td></tr>
					</table>
					<hr  valign=\"top\" color=\"black\" size=\"3px\" width=\"100%\"> 
					";
			
			$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                        <tr>
							<td rowspan=\"2\" align=\"right\" width=\"10%\" height=\"50\">&nbsp;</td>
							<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><b><u> BERITA ACARA REKONSILIASI</u></b></td>
						</tr>
						<tr>
							<td colspan=\"5\" align=\"center\" style=\"font-size:14px\"><b>Nomor : 900/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/BPKPD-D/$thn_ang</b></td>
                        </tr>
                    </table>
					<br>
					<br>
					";
					
					$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:14px;\" width=\"100%\" align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
						<tr>
							<td colspan=\"3\">Kami yang bertandatangan di bawah ini :</td>
                        </tr>
						<tr>
							<td align=\"left\" width=\"10%\">Nama</td>
							<td align=\"center\" width=\"3%\">:</td>
							<td align=\"left\" width=\"82%\">$namakabid</td>
                        </tr>
						<tr>
							<td align=\"left\">NIP</td>
							<td align=\"center\">:</td>
							<td align=\"left\">$nipkabid</td>
                        </tr>
						<tr>
							<td align=\"left\">Jabatan</td>
							<td align=\"center\">:</td>
							<td align=\"left\">$jabatankabid</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">Dalam Hal ini bertindak selaku perwakilan Bidang Akuntansi dan Pelaporan $nm_ppkd, selanjutnya di sebut sebagai Pihak Pertama.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td>Nama</td>
							<td align=\"center\">:</td>
							<td>.........................................................</td>
                        </tr>
						<tr>
							<td>NIP</td>
							<td align=\"center\">:</td>
							<td>.........................................................</td>
                        </tr>
						<tr>
							<td>Jabatan</td>
							<td align=\"center\">:</td>
							<td>Pejabat Penatausahaan Keuangan</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">Dalam Hal ini bertindak selaku perwakilan Unit Kerja $nmskpd, selanjutnya di sebut sebagai Pihak Kedua.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pihak Pertama dan Pihak Kedua Bersama-sama melakukan rekonsilisasi data Realisasi Pelaksanaan Anggaran Periode $tgl_periode1 $buln_prd1 $thn_periode1 sampai dengan $tgl_periode2 $buln_prd2 $thn_periode1. Rekonsiliasi ini dimaksud dilakukan dengan cara membandingkan :</td>
                        </tr>
						<tr>
							<td valign=\"top\">1. </td>
							<td align=\"justify\" colspan=\"2\">Data yang dimiliki Unit Kerja berupa Laporan Realisasi Anggaran pertanggungjawaban pelaksanaan; dengan</td>
                        </tr>
						<tr>
							<td valign=\"top\">2. </td>
							<td align=\"justify\" colspan=\"2\">Data yang dimiliki Bidang Akuntansi dan Pelaporan berupa Laporan Realisasi Anggaran.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selanjutnya, hasil rekonsilisasi dituangkan dalam Lampiran Berita Acara Rekonsiliasi (BAR) yang merupakan bagian yang tidak terpisahkan dari BAR ini.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Demikian berita acara ini dibuat dengan sadar dan tanpa paksaan.</td>
                        </tr>
                    </table>
					";
					
					$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
						<tr >
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">$daerah, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">Pihak Pertama,</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">Pihak Kedua,</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\"width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">$namakabid</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">................................................</td>
                        </tr>";
     
        $cRet .='</table>';
        		  
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = "LRA";
        $this->template->set('title', 'RINGKASAN LRA $cbulan');  
         switch($cetak) {
		case 4;
			$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');	
        break;
        case 3;
			echo ("<title>LRA UNIT $cbulan</title>");
			 echo $cRet;
        break;
        case 6;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 5;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-word");
            header("Content-Disposition: attachment; filename= $judul.doc");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        } 

    }
	
	function cetak_ringkasan_penerimaan($kd_skpd="", $cetak = ''){
		$cbulan = 12;
		$id = $kd_skpd;
        $thn_ang = $this->session->userdata('pcThang');
		$bulan	 = $cbulan;
        $id1     = $this->session->userdata('kdskpd');
        $nmskpd = $this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd');
        $nm_skpd =strtoupper($nmskpd);
        $thn_ang_1= $thn_ang-1;   
        
		$tgl_ttd = $this->uri->segment(9);
		$periode1 = $this->uri->segment(13);
		$periode2 = $this->uri->segment(14);
		
		$arrayperiode1=explode("-",$periode1);
		$arrayperiode2=explode("-",$periode2);		
		
		if($arrayperiode2[1]<='3'){
			$tw = "I";
		}else if($arrayperiode2[1]<='6'){
			$tw = "II";
		}else if($arrayperiode2[1]<='9'){
			$tw = "III";
		}else{
			$tw = "IV";
		}
		
		$kabid    = str_replace('%20',' ',$this->uri->segment(7));
		
		$sqlkabid="select TOP 1 * from ms_ttd where nip='$kabid'";
                 $sqlkabid1=$this->db->query($sqlkabid);
                 foreach ($sqlkabid1->result() as $rowkabid)
                {
                    $nipkabid      = $rowkabid->nip;
                    $namakabid     = $rowkabid->nama;
                    $jabatankabid  = $rowkabid->jabatan;
                    $pangkatkabid  = $rowkabid->pangkat;
                   
                }
		
		$tgl   = substr($tgl_ttd,7,2);
		$bln   = substr($tgl_ttd,5,2);
		$thn   = substr($tgl_ttd,0,4);
		
		
		//$bln2 = substr($periode2,5,2);
		$bln2 = $arrayperiode2[1];
		
		$tanggl = $this->tukd_model->terbilang2($tgl);
		$buln  = $this->getBulan($bln);
		$tahun = $this->tukd_model->terbilang2($thn); 
		
		$tgl_periode1 = substr($periode1,7,2);
		$bln_periode1 = substr($periode1,5,1);
		$thn_periode1 = substr($periode1,0,4);
		
		$len = strlen($periode2);
		
		if($len<>'10'){
			$tgl_periode2 = substr($periode2,7,2);
			$bln_periode2 = substr($periode2,5,1);
		}else{
			$tgl_periode2 = substr($periode2,8,2);
			$bln_periode2 = substr($periode2,5,2);
		}
		
		$tanggl_prd1 = $this->tukd_model->terbilang2($tgl_periode1);
		$buln_prd1   = $this->getBulan($bln_periode1);
		
		$tanggl_prd2 = $this->tukd_model->terbilang2($tgl_periode2);
		$buln_prd2   = $this->getBulan($bln_periode2);
		
		

		$day = date('D', strtotime($tgl_ttd));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
	

	
	$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='3.13.01.17'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
				
	$sqlsc="SELECT nm_skpd FROM ms_skpd where kd_skpd='3.13.01.01'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $nm_ppkd     = $rowsc->nm_skpd;
                }
	
			    
					$cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td rowspan=\"4\" align=\"right\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:16px\"><strong>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH</strong></tr>
					<tr><td colspan=\"3\" align=\"center\"><strong>Jalan Ahmad Yani Telepon (0561) 736541 Fax (0561) 738428</strong></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PONTIANAK</strong></td></tr>
					<tr><td colspan=\"4\" align=\"right\">Kode Pos: 78124 &nbsp; &nbsp;</td></tr>
					</table>
					<hr  valign=\"top\" color=\"black\" size=\"3px\" width=\"100%\"> 
					";
			
			$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                        <tr>
							<td rowspan=\"2\" align=\"right\" width=\"10%\" height=\"50\">&nbsp;</td>
							<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><b><u> BERITA ACARA REKONSILIASI</u></b></td>
						</tr>
						<tr>
							<td colspan=\"5\" align=\"center\" style=\"font-size:14px\"><b>Nomor : 900/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/BPKPD-D/$thn_ang</b></td>
                        </tr>
                    </table>
					<br>
					<br>
					";
					
					$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:14px;\" width=\"100%\" align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
						<tr>
							<td colspan=\"3\">Kami yang bertandatangan di bawah ini :</td>
                        </tr>
						<tr>
							<td align=\"left\" width=\"10%\">Nama</td>
							<td align=\"center\" width=\"3%\">:</td>
							<td align=\"left\" width=\"82%\">$namakabid</td>
                        </tr>
						<tr>
							<td align=\"left\">NIP</td>
							<td align=\"center\">:</td>
							<td align=\"left\">$nipkabid</td>
                        </tr>
						<tr>
							<td align=\"left\">Jabatan</td>
							<td align=\"center\">:</td>
							<td align=\"left\">$jabatankabid</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">Dalam Hal ini bertindak selaku perwakilan Bidang Akuntansi dan Pelaporan $nm_ppkd, selanjutnya di sebut sebagai Pihak Pertama.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td>Nama</td>
							<td align=\"center\">:</td>
							<td>.........................................................</td>
                        </tr>
						<tr>
							<td>NIP</td>
							<td align=\"center\">:</td>
							<td>.........................................................</td>
                        </tr>
						<tr>
							<td>Jabatan</td>
							<td align=\"center\">:</td>
							<td>Bendahara Penerimaan</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">Dalam Hal ini bertindak selaku perwakilan Unit Kerja $nmskpd, selanjutnya di sebut sebagai Pihak Kedua.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pihak Pertama dan Pihak Kedua Bersama-sama melakukan rekonsilisasi data Penerimaan Periode $tgl_periode1 $buln_prd1 $thn_periode1 sampai dengan $tgl_periode2 $buln_prd2 $thn_periode1. Rekonsiliasi ini dimaksud dilakukan dengan cara membandingkan :</td>
                        </tr>
						<tr>
							<td valign=\"top\">1. </td>
							<td align=\"justify\" colspan=\"2\">Data yang dimiliki Unit Kerja berupa laporan pertanggungjawaban Penerimaan secara fungsional atas pengelolaan uang kepada PPKD selaku BUD; dengan</td>
                        </tr>
						<tr>
							<td valign=\"top\">2. </td>
							<td align=\"justify\" colspan=\"2\">Data yang dimiliki Bidang Akuntansi dan Pelaporan berupa realisasi transaksi Penerimaan kas.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selanjutnya, hasil rekonsilisasi dituangkan dalam Lampiran Berita Acara Rekonsiliasi (BAR) yang merupakan bagian yang tidak terpisahkan dari BAR ini.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Demikian berita acara ini dibuat dengan sadar dan tanpa paksaan.</td>
                        </tr>
                    </table>
					";
					
					$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
						<tr >
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">$daerah, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">Pihak Pertama,</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">Pihak Kedua,</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\"width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">$namakabid</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">................................................</td>
                        </tr>";
     
        $cRet .='</table>';
        		  
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = "Penerimaan";
        $this->template->set('title', 'RINGKASAN Penerimaan $cbulan');  
         switch($cetak) {
		case 4;
			$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');	
        break;
        case 3;
			echo ("<title>Penerimaan UNIT $cbulan</title>");
			 echo $cRet;
        break;
        case 6;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 5;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-word");
            header("Content-Disposition: attachment; filename= $judul.doc");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        } 

    }
	
	function cetak_ringkasan_pengeluaran($kd_skpd="", $cetak = ''){
		$cbulan = 12;
		$id = $kd_skpd;
        $thn_ang = $this->session->userdata('pcThang');
		$bulan	 = $cbulan;
        $id1     = $this->session->userdata('kdskpd');
        $nmskpd = $this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd');
        $nm_skpd =strtoupper($nmskpd);
        $thn_ang_1= $thn_ang-1;   
        
		$tgl_ttd = $this->uri->segment(9);
		$periode1 = $this->uri->segment(13);
		$periode2 = $this->uri->segment(14);
		
		$arrayperiode1=explode("-",$periode1);
		$arrayperiode2=explode("-",$periode2);		
		
		if($arrayperiode2[1]<='3'){
			$tw = "I";
		}else if($arrayperiode2[1]<='6'){
			$tw = "II";
		}else if($arrayperiode2[1]<='9'){
			$tw = "III";
		}else{
			$tw = "IV";
		}
		
		$kabid    = str_replace('%20',' ',$this->uri->segment(7));
		
		$sqlkabid="select TOP 1 * from ms_ttd where nip='$kabid'";
                 $sqlkabid1=$this->db->query($sqlkabid);
                 foreach ($sqlkabid1->result() as $rowkabid)
                {
                    $nipkabid      = $rowkabid->nip;
                    $namakabid     = $rowkabid->nama;
                    $jabatankabid  = $rowkabid->jabatan;
                    $pangkatkabid  = $rowkabid->pangkat;
                   
                }
		
		$tgl   = substr($tgl_ttd,7,2);
		$bln   = substr($tgl_ttd,5,2);
		$thn   = substr($tgl_ttd,0,4);
		
		
		//$bln2 = substr($periode2,5,2);
		$bln2 = $arrayperiode2[1];
		
		$tanggl = $this->tukd_model->terbilang2($tgl);
		$buln  = $this->getBulan($bln);
		$tahun = $this->tukd_model->terbilang2($thn); 
		
		$tgl_periode1 = substr($periode1,7,2);
		$bln_periode1 = substr($periode1,5,1);
		$thn_periode1 = substr($periode1,0,4);
		
		$len = strlen($periode2);
		
		if($len<>'10'){
			$tgl_periode2 = substr($periode2,7,2);
			$bln_periode2 = substr($periode2,5,1);
		}else{
			$tgl_periode2 = substr($periode2,8,2);
			$bln_periode2 = substr($periode2,5,2);
		}
		
		$tanggl_prd1 = $this->tukd_model->terbilang2($tgl_periode1);
		$buln_prd1   = $this->getBulan($bln_periode1);
		
		$tanggl_prd2 = $this->tukd_model->terbilang2($tgl_periode2);
		$buln_prd2   = $this->getBulan($bln_periode2);
		
		

		$day = date('D', strtotime($tgl_ttd));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
	

	
	$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='3.13.01.17'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
				
	$sqlsc="SELECT nm_skpd FROM ms_skpd where kd_skpd='3.13.01.01'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $nm_ppkd     = $rowsc->nm_skpd;
                }
	
			    
					$cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td rowspan=\"4\" align=\"right\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:16px\"><strong>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH</strong></tr>
					<tr><td colspan=\"3\" align=\"center\"><strong>Jalan Ahmad Yani Telepon (0561) 736541 Fax (0561) 738428</strong></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PONTIANAK</strong></td></tr>
					<tr><td colspan=\"4\" align=\"right\">Kode Pos: 78124 &nbsp; &nbsp;</td></tr>
					</table>
					<hr  valign=\"top\" color=\"black\" size=\"3px\" width=\"100%\"> 
					";
			
			$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                        <tr>
							<td rowspan=\"2\" align=\"right\" width=\"10%\" height=\"50\">&nbsp;</td>
							<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><b><u> BERITA ACARA REKONSILIASI</u></b></td>
						</tr>
						<tr>
							<td colspan=\"5\" align=\"center\" style=\"font-size:14px\"><b>Nomor : 900/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/BPKPD-D/$thn_ang</b></td>
                        </tr>
                    </table>
					<br>
					<br>
					";
					
					$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:14px;\" width=\"100%\" align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
						<tr>
							<td colspan=\"3\">Kami yang bertandatangan di bawah ini :</td>
                        </tr>
						<tr>
							<td align=\"left\" width=\"10%\">Nama</td>
							<td align=\"center\" width=\"3%\">:</td>
							<td align=\"left\" width=\"82%\">$namakabid</td>
                        </tr>
						<tr>
							<td align=\"left\">NIP</td>
							<td align=\"center\">:</td>
							<td align=\"left\">$nipkabid</td>
                        </tr>
						<tr>
							<td align=\"left\">Jabatan</td>
							<td align=\"center\">:</td>
							<td align=\"left\">$jabatankabid</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">Dalam Hal ini bertindak selaku perwakilan Bidang Akuntansi dan Pelaporan $nm_ppkd, selanjutnya di sebut sebagai Pihak Pertama.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td>Nama</td>
							<td align=\"center\">:</td>
							<td>.........................................................</td>
                        </tr>
						<tr>
							<td>NIP</td>
							<td align=\"center\">:</td>
							<td>.........................................................</td>
                        </tr>
						<tr>
							<td>Jabatan</td>
							<td align=\"center\">:</td>
							<td>Bendahara Pengeluaran</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">Dalam Hal ini bertindak selaku perwakilan Unit Kerja $nmskpd, selanjutnya di sebut sebagai Pihak Kedua.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pihak Pertama dan Pihak Kedua Bersama-sama melakukan rekonsilisasi data Pengeluaran Periode Periode $tgl_periode1 $buln_prd1 $thn_periode1 sampai dengan $tgl_periode2 $buln_prd2 $thn_periode1. Rekonsiliasi ini dimaksud dilakukan dengan cara membandingkan :</td>
                        </tr>
						<tr>
							<td valign=\"top\">1. </td>
							<td align=\"justify\" colspan=\"2\">Data yang dimiliki Unit Kerja berupa laporan pertanggungjawaban pengeluaran secara fungsional atas pengelolaan uang kepada PPKD selaku BUD; dengan</td>
                        </tr>
						<tr>
							<td valign=\"top\">2. </td>
							<td align=\"justify\" colspan=\"2\">Data yang dimiliki Bidang Akuntansi dan Pelaporan berupa realisasi transaksi pengeluaran kas.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selanjutnya, hasil rekonsilisasi dituangkan dalam Lampiran Berita Acara Rekonsiliasi (BAR) yang merupakan bagian yang tidak terpisahkan dari BAR ini.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Demikian berita acara ini dibuat dengan sadar dan tanpa paksaan.</td>
                        </tr>
                    </table>
					";
					
					$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
						<tr >
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">$daerah, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">Pihak Pertama,</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">Pihak Kedua,</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\"width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">$namakabid</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">................................................</td>
                        </tr>";
     
        $cRet .='</table>';
        		  
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = "PENGELUARAN";
        $this->template->set('title', 'RINGKASAN PENGELUARAN $cbulan');  
         switch($cetak) {
		case 4;
			$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');	
        break;
        case 3;
			echo ("<title>PENGELUARAN UNIT $cbulan</title>");
			 echo $cRet;
        break;
        case 6;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 5;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-word");
            header("Content-Disposition: attachment; filename= $judul.doc");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        } 

    }
	
	function cetak_ba_neraca( $kd_skpd="", $cetak=''){
		$pilih = $cetak;
        $id     = $kd_skpd;
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1= $thn_ang-1;  
                  
        $skpd = "AND kd_skpd='$id'";
        $skpd1 = "AND b.kd_skpd='$id'"; 
	   
       $y123=")";
	   $x123="(";

		$tgl_ttd = $this->uri->segment(9);
		$periode1 = $this->uri->segment(13);
		$periode2 = $this->uri->segment(14);
		
		$arrayperiode1=explode("-",$periode1);		
		$arrayperiode2=explode("-",$periode2);		
		$arraytgl=explode("-",$tgl_ttd);			
		
		if($arrayperiode2[1]=='3'){
			$tw = "I";
		}if($arrayperiode2[1]=='6'){
			$tw = "II";
		}if($arrayperiode2[1]=='9'){
			$tw = "III";
		}else{
			$tw = "IV";
		}
		
		$tgl   = $arraytgl[2];
		$bln   = $arraytgl[1];
		$thn   = $arraytgl[0];
		
		$tgl_periode1 = $arrayperiode1[2];
		$bln_periode1 = $arrayperiode1[1];
		$thn_periode1 = $arrayperiode1[0];
		
		$tgl_periode2 = $arrayperiode2[2];
		$bln_periode2 = $arrayperiode2[1];
		$thn_periode2 = $arrayperiode2[0];
		
		$tanggl_ttd = $this->tukd_model->terbilang2($tgl);
		$buln_ttd  = $this->getBulan($bln);
		
		$tanggl_prd1 = $this->tukd_model->terbilang2($tgl_periode1);
		$buln_prd1  = $this->getBulan($bln_periode1);
		
		$tanggl_prd2 = $this->tukd_model->terbilang2($tgl_periode2);
		$buln_prd2  = $this->getBulan($bln_periode2); 
		
		$cbulan=$bln_periode2;
		
		if($cbulan==12){
		$sumber_jurnal= "ju_calk";
		}else{
		$sumber_jurnal= "ju";
		}	

		$day = date('D', strtotime($tgl_ttd));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
		
		$cbulan<10 ? $xbulan = "0$cbulan" : $xbulan=$cbulan;
		
		
	$kabid    = str_replace('%20',' ',$this->uri->segment(5));
	$stafak   = str_replace('%20',' ',$this->uri->segment(6));
	$kasub    = str_replace('%20',' ',$this->uri->segment(7));
	$ppk      = str_replace('%20',' ',$this->uri->segment(8));
	$penyusun = str_replace('%20',' ',$this->uri->segment(10));
	$terima   = str_replace('%20',' ',$this->uri->segment(11));
	$keluar   = str_replace('%20',' ',$this->uri->segment(12));
	
	$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='3.13.01.17'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
	
	$sqlkabid="select TOP 1 * from ms_ttd where nip='$kabid'";
                 $sqlkabid1=$this->db->query($sqlkabid);
                 foreach ($sqlkabid1->result() as $rowkabid)
                {
                    $nipkabid      = $rowkabid->nip;
                    $namakabid     = $rowkabid->nama;
                    $jabatankabid  = $rowkabid->jabatan;
                    $pangkatkabid  = $rowkabid->pangkat;
                   
                }
				
	$sqlstafak="select TOP 1 * from ms_ttd where nip='$stafak'";
                 $sqlstafak1=$this->db->query($sqlstafak);
                 foreach ($sqlstafak1->result() as $rowstafak)
                {
                    $nipstafak      = $rowstafak->nip;
                    $namastafak     = $rowstafak->nama;
                    $jabatanstafak  = $rowstafak->jabatan;
                    $pangkatstafak  = $rowstafak->pangkat;
                   
                }
	
	$sqlkasub="select TOP 1 * from ms_ttd where nip='$kasub'";
                 $sqlkasub1=$this->db->query($sqlkasub);
                 foreach ($sqlkasub1->result() as $rowkasub)
                {
                    $nipkasub      = $rowkasub->nip;
                    $namakasub     = $rowkasub->nama;
                    $jabatankasub  = $rowkasub->jabatan;
                    $pangkatkasub  = $rowkasub->pangkat;
                   
                }
	
	$sqlppk="select TOP 1 * from ms_ttd where nip='$ppk'";
                 $sqlppk1=$this->db->query($sqlppk);
                 foreach ($sqlppk1->result() as $rowppk)
                {
                    $nipppk      = $rowppk->nip;
                    $namappk     = $rowppk->nama;
                    $jabatanppk  = $rowppk->jabatan;
                    $pangkatppk  = $rowppk->pangkat;
                   
                }
				
	$sqlpenyusun="select TOP 1 * from ms_ttd where nip='$penyusun'";
                 $sqlpenyusun1=$this->db->query($sqlpenyusun);
                 foreach ($sqlpenyusun1->result() as $rowpenyusun)
                {
                    $nippenyusun      = $rowpenyusun->nip;
                    $namapenyusun     = $rowpenyusun->nama;
                    $jabatanpenyusun  = $rowpenyusun->jabatan;
                    $pangkatpenyusun  = $rowpenyusun->pangkat;
                   
                }
	
	$sqlterima="select TOP 1 * from ms_ttd where nip='$terima'";
                 $sqlterima1=$this->db->query($sqlterima);
                 foreach ($sqlterima1->result() as $rowterima)
                {
                    $nipterima      = $rowterima->nip;
                    $namaterima     = $rowterima->nama;
                    $jabatanterima  = $rowterima->jabatan;
                    $pangkatterima  = $rowterima->pangkat;
                   
                }
	
	$sqlkeluar="select TOP 1 * from ms_ttd where nip='$keluar'";
                 $sqlkeluar1=$this->db->query($sqlkeluar);
                 foreach ($sqlkeluar1->result() as $rowkeluar)
                {
                    $nipkeluar      = $rowkeluar->nip;
                    $namakeluar     = $rowkeluar->nama;
                    $jabatankeluar  = $rowkeluar->jabatan;
                    $pangkatkeluar  = $rowkeluar->pangkat;
                   
                }
		
	$sqlsc="SELECT nm_skpd FROM ms_skpd where kd_skpd='$id'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $nmskpd     = $rowsc->nm_skpd;
                } 
		$nm_skpd = strtoupper($nmskpd);		
		
			$cRet='';
			$cRet .="<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td rowspan=\"4\" align=\"right\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:16px\"><strong>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH</strong></tr>
					<tr><td colspan=\"3\" align=\"center\"><strong>Jalan Ahmad Yani Telepon (0561) 736541 Fax (0561) 738428</strong></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PONTIANAK</strong></td></tr>
					<tr><td colspan=\"4\" align=\"right\">Kode Pos: 78124 &nbsp; &nbsp;</td></tr>
					</table>
					<hr  valign=\"top\" color=\"black\" size=\"3px\" width=\"100%\"> 
					";
			
			$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                        <tr>
							<td rowspan=\"2\" align=\"right\" width=\"10%\" height=\"50\">&nbsp;</td>
							<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><b>Neraca Tahun Anggaran $thn_periode2</b></td>
						</tr>
						<tr>
							<td colspan=\"5\" align=\"center\" style=\"font-size:14px\"><b>Periode $tgl_periode1 $buln_prd1 - $tgl_periode2 $buln_prd2 $thn_periode2</b></td>
                        </tr>
						<tr>
						<td colspan=\"5\" align=\"justify\" style=\"font-size:12px\">
						<br>
						Pada hari ini ".$dayList[$day]." Tanggal $tanggl_ttd Bulan $buln_ttd  Tahun $thn. $nm_skpd telah melaksanakan rekonsiliasi dengan 
						Bidang Akuntansi Badan Pengelolaan Keuangan dan Pendapatan Daerah Provinsi Kalimantan Barat 
						<br>
						<br>
						
						</td>
                        </tr>
						<tr>
						<td colspan=\"5\" align=\"justify\" style=\"font-size:12px\">
						<br>
						SKPD : $id - $nm_skpd
						<br>
						<br>
						
						</td>
                        </tr>
                    </table>
					";
					

					  $cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
							<td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
                            <td colspan=\"2\" bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>NERACA TA. $thn_ang</b></td>
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"30%\" align=\"center\"><b>KETERANGAN</b></td>
                        </tr>
						<tr>
							<td bgcolor=\"#CCCCCC\" width=\"35%\" align=\"center\"><b>Klasifikasi</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Nilai</b></td>     
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

			$sqllo10="select isnull(sum(kredit-debet),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek5,1) in ('8') and b.kd_skpd='$kd_skpd'";
                    $querylo10= $this->db->query($sqllo10);
                    $pen8 = $querylo10->row();
                    $pen_lalu8 = $pen8->nilai;
                    $pen_lalu81= number_format($pen8->nilai,"2",",",".");
		
			$sqllo12="select isnull(sum(debet-kredit),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek5,1) in ('9') and b.kd_skpd='$kd_skpd'";
                    $querylo12= $this->db->query($sqllo12);
                    $bel10 = $querylo12->row();
                    $bel_lalu10 = $bel10->nilai;
                    $bel_lalu101= number_format($bel10->nilai,"2",",",".");

			$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and a.kd_skpd='$kd_skpd'";//aba
                            
                    $hasil = $this->db->query($sql_lalu); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row001)
                    {
                       $kd_rek   =$row001->nor ;
                       $parent   =$row001->parent;
                       $nama     =$row001->uraian;
                       $lpe_ll1  =$row001->thn_m1;
					}
				        
			$sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and a.kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllpe_lalu1); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row002)
                    {
                       $kd_rek   =$row002->nor ;
                       $parent   =$row002->parent;
                       $nama     =$row002->uraian;
                       $lpe_ll2  =$row002->thn_m1;
					}					

			$sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and a.kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllpe_lalu2); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row003)
                    {
                       $kd_rek   =$row003->nor ;
                       $parent   =$row003->parent;
                       $nama     =$row003->uraian;
                       $lpe_ll3  =$row003->thn_m1;
					}
					
			
			$query3 = $this->db->query(" SELECT isnull(SUM(a.debet),0) AS debet, isnull(SUM(a.kredit),0) AS kredit FROM trdju a INNER JOIN trhju b 
			ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE b.kd_skpd='$kd_skpd' AND a.kd_rek5='3110101' AND YEAR(b.tgl_voucher)<'$thn_ang'
			and b.tabel=1 and reev=0");  
	        foreach($query3->result_array() as $res2){
				 $debet3=$res2['debet'];
				 $kredit3=$res2['kredit'];
                 				 
			 }
		
		$sqlrkpp="select isnull(sum(kredit-debet),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<'$thn_ang_1' and left(kd_rek5,3) in ('313') and b.kd_skpd='$kd_skpd'";
                    $rkpp= $this->db->query($sqlrkpp);
                    $rkpp1 = $rkpp->row();
                    $rkpp_lalu = $rkpp1->nilai;
                    $rkpp_lalu1= number_format($rkpp1->nilai,"2",",",".");
			
		$real=$kredit3-$debet3+$pen_lalu8-$bel_lalu10+$lpe_ll1+$lpe_ll2+$lpe_ll3;

//		created by henri_tb
		$sqllo9="select isnull(sum(kredit-debet),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_rek5,1) in ('8') and b.kd_skpd='$kd_skpd'";
                    $querylo9= $this->db->query($sqllo9);
                    $penlo7 = $querylo9->row();
                    $pen_lo7 = $penlo7->nilai;
                    $pen_lo71= number_format($penlo7->nilai,"2",",",".");
        
		$sqllo10="select isnull(sum(kredit-debet),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('8') and b.kd_skpd='$kd_skpd'";
                    $querylo10= $this->db->query($sqllo10);
                    $penlo8 = $querylo10->row();
                    $pen_lo_lalu8 = $penlo8->nilai;
                    $pen_lo_lalu81= number_format($penlo8->nilai,"2",",",".");
		
		$sqllo11="select isnull(sum(debet-kredit),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_rek5,1) in ('9') and b.kd_skpd='$kd_skpd'";
                    $querylo11= $this->db->query($sqllo11);
                    $bello9 = $querylo11->row();
                    $bel_lo9 = $bello9->nilai;
                    $bel_lo91= number_format($bello9->nilai,"2",",",".");
		
		$sqllo12="select isnull(sum(debet-kredit),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('9') and b.kd_skpd='$kd_skpd'";
                    $querylo12= $this->db->query($sqllo12);
                    $bello10 = $querylo12->row();
                    $bel_lo_lalu10 = $bello10->nilai;
                    $bel_lo_lalu101= number_format($bello10->nilai,"2",",",".");		

					$surplus_lo3 = $pen_lo7 - $bel_lo9;
                    
					$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
                    
					$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
					
			$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and a.kd_skpd='$kd_skpd'";//aba
                            
                    $hasil = $this->db->query($sql_lalu); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row001)
                    {
                       $kd_rek   =$row001->nor ;
                       $parent   =$row001->parent;
                       $nama     =$row001->uraian;
                       $lpe_lalu1  =$row001->thn_m1;
					}
				        
			$sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and a.kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllpe_lalu1); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row002)
                    {
                       $kd_rek   =$row002->nor ;
                       $parent   =$row002->parent;
                       $nama     =$row002->uraian;
                       $lpe_lalu2  =$row002->thn_m1;
					}					

			$sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and a.kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllpe_lalu2); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row003)
                    {
                       $kd_rek   =$row003->nor ;
                       $parent   =$row003->parent;
                       $nama     =$row003->uraian;
                       $lpe_lalu3  =$row003->thn_m1;
					}
			
			$sqlrkpp2="select isnull(sum(kredit-debet),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)='$thn_ang_1' and left(kd_rek5,3) in ('313') and b.kd_skpd='$kd_skpd'";
                    $rkpp2= $this->db->query($sqlrkpp2);
                    $rkpp12 = $rkpp2->row();
                    $rkpp_lalu2 = $rkpp12->nilai;
                    	
			
		$sal_awal	= $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;		

			$sql = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and a.kd_skpd='$kd_skpd'";//aba
                            
                    $hasil = $this->db->query($sql); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row001)
                    {
                       $kd_rek   =$row001->nor ;
                       $parent   =$row001->parent;
                       $nama     =$row001->uraian;
                       $nilaiDR  =$row001->thn_m1;
					}
				        
			$sqllpe1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and a.kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllpe1); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row002)
                    {
                       $kd_rek   =$row002->nor ;
                       $parent   =$row002->parent;
                       $nama     =$row002->uraian;
                       $nilailpe1  =$row002->thn_m1;
					}					

			$sqllpe2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and a.kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllpe2); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row003)
                    {
                       $kd_rek   =$row003->nor ;
                       $parent   =$row003->parent;
                       $nama     =$row003->uraian;
                       $nilailpe2  =$row003->thn_m1;
					}
		
			$sqlrkpp3="select isnull(sum(kredit-debet),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)='$thn_ang' and left(kd_rek5,3) in ('313') and b.kd_skpd='$kd_skpd'";
                    $rkpp3= $this->db->query($sqlrkpp3);
                    $rkpp13 = $rkpp3->row();
                    $rkpp = $rkpp13->nilai;
                    
		
        $sal_akhir=$sal_awal+$surplus_lo3+$nilaiDR+$nilailpe1+$nilailpe2;

			$sqlutang_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(b.kd_rek5,1)=2 and year(a.tgl_voucher)<=$thn_ang_1 and a.kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlutang_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $nilaiutang_lalu  =$row->thn_m1;
					}					

			$sqlkas_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=3130101 and year(a.tgl_voucher)<=$thn_ang_1 and a.kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlkas_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_ppkd_lalu  =$row->thn_m1;
					}

			$eku_lalu 		= $sal_awal + $rk_ppkd_lalu;					
			$eku_tang_lalu 	= $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu;
		
			$sqlutang = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher
			and b.kd_unit=a.kd_skpd where left(b.kd_rek5,1)=2 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and a.kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlutang); 
                    foreach ($hasil->result() as $row)
                    {
                       $nilaiutang  =$row->thn_m1;
					}					
			
			$sqlkas = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=3130101 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and a.kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlkas); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_ppkd  =$row->thn_m1;
					}
					
			$eku 		= $sal_akhir + $rk_ppkd;					
			$eku_tang 	= $sal_akhir + $nilaiutang + $rk_ppkd;			

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
		
		if ($rkpp_lalu2 < 0){
                    	$pp1="("; $rkpp_lalu2=$rkpp_lalu2*-1; $pp2=")";}
                    else {
                    	$pp1=""; $rkpp_lalu2; $pp2="";}	
						
		$rkpp_lalu12= number_format($rkpp_lalu2,"2",",",".");
		
		if ($rkpp < 0){
                    	$pp3="("; $rkpp=$rkpp*-1; $pp4=")";}
                    else {
                    	$pp3=""; $rkpp; $pp4="";}
		
		$rkpp1= number_format($rkpp,"2",",",".");
		
			$queryneraca = " SELECT kode, uraian, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
										isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
										isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
										isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
										FROM map_neraca_skpd ORDER BY seq ";  
		
			$query10 = $this->db->query($queryneraca);
			
			$no     = 0;
			
			foreach($query10->result_array() as $res){
				$uraian=$res['uraian'];
				$normal=$res['normal'];
				
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
											
				
			$q = $this->db->query(" SELECT isnull(SUM(b.debet),0) AS debet,isnull(SUM(b.kredit),0) AS kredit from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
									and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and a.kd_skpd ='$kd_skpd' and
										(kd_rek5 like '$kode_1%' or kd_rek5 like '$kode_2%'  or 
										kd_rek5 like '$kode_3%' or kd_rek5 like '$kode_4%'  or 
										kd_rek5 like '$kode_5%' or kd_rek5 like '$kode_6%'  or 
										kd_rek5 like '$kode_7%' or kd_rek5 like '$kode_8%'  or 
										kd_rek5 like '$kode_9%' or kd_rek5 like '$kode_10%' or 
										kd_rek5 like '$kode_11%' or kd_rek5 like '$kode_12%' or 
										kd_rek5 like '$kode_13%' or kd_rek5 like '$kode_14%' or 
										kd_rek5 like '$kode_15%') ");  

				 foreach($q->result_array() as $r){
					$debet=$r['debet'];
					$kredit=$r['kredit'];
				 }
				
				if ($debet=='') $debet=0;
				if ($kredit=='') $kredit=0;

				if ($normal=='D'){
					$nl=$debet-$kredit;
				}else{
					$nl=$kredit-$debet;				
				}
				if ($nl=='') $nl=0;
	
				// Jurnal Tahun lalu
				$q = $this->db->query(" SELECT isnull(SUM(b.debet),0) AS debet,isnull(SUM(b.kredit),0) AS kredit from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
									and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and a.kd_skpd ='$kd_skpd' and
										(kd_rek5 like '$kode_1%' or kd_rek5 like '$kode_2%'  or 
										kd_rek5 like '$kode_3%' or kd_rek5 like '$kode_4%'  or 
										kd_rek5 like '$kode_5%' or kd_rek5 like '$kode_6%'  or 
										kd_rek5 like '$kode_7%' or kd_rek5 like '$kode_8%'  or 
										kd_rek5 like '$kode_9%' or kd_rek5 like '$kode_10%' or 
										kd_rek5 like '$kode_11%' or kd_rek5 like '$kode_12%' or 
										kd_rek5 like '$kode_13%' or kd_rek5 like '$kode_14%' or 
										kd_rek5 like '$kode_15%') ");  

				 foreach($q->result_array() as $rx){
					$debet_lalu=$rx['debet'];
					$kredit_lalu=$rx['kredit'];
				 }
				
				if ($debet_lalu=='') $debet_lalu=0;
				if ($kredit_lalu=='') $kredit_lalu=0;

				if ($normal=='D'){
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

				switch ($res['seq']) {
                    case 5:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 10:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 65:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
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
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 300:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
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
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 370:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 375:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
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
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 385:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 410:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;	
					case 415:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
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
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 465:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$c$sal_akhir1$d</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>

								 </tr>";
                        break;
					case 470:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
						break;		 
					case 475:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min003$eku1$min004</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
								 </tr>";
                        break;
					case 485:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;	
					default:	
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;	
					}
 
			}

			
        $cRet .='</table>';
			
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         //$judul = ("NERACA  UNIT $cbulan");
         $judul = "NERACA";
        $this->template->set('title', 'NERACA  UNIT $cbulan');  
         switch ($cetak)
        {
            case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
            case 1;
				echo "<title>NERACA  UNIT $cbulan</title>";
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
	
	function cetak_ringkasan_neraca($kd_skpd="", $cetak = ''){
		$cbulan = 12;
		$id = $kd_skpd;
        $thn_ang = $this->session->userdata('pcThang');
		$bulan	 = $cbulan;
        $id1     = $this->session->userdata('kdskpd');
        $nmskpd = $this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd');
        $nm_skpd =strtoupper($nmskpd);
        $thn_ang_1= $thn_ang-1;   
        
		$tgl_ttd = $this->uri->segment(9);
		$periode1 = $this->uri->segment(13);
		$periode2 = $this->uri->segment(14);
		
		$arrayperiode1=explode("-",$periode1);
		$arrayperiode2=explode("-",$periode2);		
		
		if($arrayperiode2[1]<='3'){
			$tw = "I";
		}else if($arrayperiode2[1]<='6'){
			$tw = "II";
		}else if($arrayperiode2[1]<='9'){
			$tw = "III";
		}else{
			$tw = "IV";
		}
		
		$kabid    = str_replace('%20',' ',$this->uri->segment(7));
		
		$sqlkabid="select TOP 1 * from ms_ttd where nip='$kabid'";
                 $sqlkabid1=$this->db->query($sqlkabid);
                 foreach ($sqlkabid1->result() as $rowkabid)
                {
                    $nipkabid      = $rowkabid->nip;
                    $namakabid     = $rowkabid->nama;
                    $jabatankabid  = $rowkabid->jabatan;
                    $pangkatkabid  = $rowkabid->pangkat;
                   
                }
		
		$tgl   = substr($tgl_ttd,7,2);
		$bln   = substr($tgl_ttd,5,2);
		$thn   = substr($tgl_ttd,0,4);
		
		
		//$bln2 = substr($periode2,5,2);
		$bln2 = $arrayperiode2[1];
		
		$tanggl = $this->tukd_model->terbilang2($tgl);
		$buln  = $this->getBulan($bln);
		$tahun = $this->tukd_model->terbilang2($thn); 
		
		$tgl_periode1 = substr($periode1,7,2);
		$bln_periode1 = substr($periode1,5,1);
		$thn_periode1 = substr($periode1,0,4);
		
		$len = strlen($periode2);
		
		if($len<>'10'){
			$tgl_periode2 = substr($periode2,7,2);
			$bln_periode2 = substr($periode2,5,1);
		}else{
			$tgl_periode2 = substr($periode2,8,2);
			$bln_periode2 = substr($periode2,5,2);
		}
		
		$tanggl_prd1 = $this->tukd_model->terbilang2($tgl_periode1);
		$buln_prd1   = $this->getBulan($bln_periode1);
		
		$tanggl_prd2 = $this->tukd_model->terbilang2($tgl_periode2);
		$buln_prd2   = $this->getBulan($bln_periode2);
		
		

		$day = date('D', strtotime($tgl_ttd));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
	

	
	$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='3.13.01.17'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
				
	$sqlsc="SELECT nm_skpd FROM ms_skpd where kd_skpd='3.13.01.01'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $nm_ppkd     = $rowsc->nm_skpd;
                }
	
			    
					$cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td rowspan=\"4\" align=\"right\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:16px\"><strong>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH</strong></tr>
					<tr><td colspan=\"3\" align=\"center\"><strong>Jalan Ahmad Yani Telepon (0561) 736541 Fax (0561) 738428</strong></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PONTIANAK</strong></td></tr>
					<tr><td colspan=\"4\" align=\"right\">Kode Pos: 78124 &nbsp; &nbsp;</td></tr>
					</table>
					<hr  valign=\"top\" color=\"black\" size=\"3px\" width=\"100%\"> 
					";
			
			$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                        <tr>
							<td rowspan=\"2\" align=\"right\" width=\"10%\" height=\"50\">&nbsp;</td>
							<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><b><u> BERITA ACARA REKONSILIASI</u></b></td>
						</tr>
						<tr>
							<td colspan=\"5\" align=\"center\" style=\"font-size:14px\"><b>Nomor : 900/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/BPKPD-D/$thn_ang</b></td>
                        </tr>
                    </table>
					<br>
					<br>
					";
					
					$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:14px;\" width=\"100%\" align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
						<tr>
							<td colspan=\"3\">Kami yang bertandatangan di bawah ini :</td>
                        </tr>
						<tr>
							<td align=\"left\" width=\"10%\">Nama</td>
							<td align=\"center\" width=\"3%\">:</td>
							<td align=\"left\" width=\"82%\">$namakabid</td>
                        </tr>
						<tr>
							<td align=\"left\">NIP</td>
							<td align=\"center\">:</td>
							<td align=\"left\">$nipkabid</td>
                        </tr>
						<tr>
							<td align=\"left\">Jabatan</td>
							<td align=\"center\">:</td>
							<td align=\"left\">$jabatankabid</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">Dalam Hal ini bertindak selaku perwakilan Bidang Akuntansi dan Pelaporan $nm_ppkd, selanjutnya di sebut sebagai Pihak Pertama.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td>Nama</td>
							<td align=\"center\">:</td>
							<td>.........................................................</td>
                        </tr>
						<tr>
							<td>NIP</td>
							<td align=\"center\">:</td>
							<td>.........................................................</td>
                        </tr>
						<tr>
							<td>Jabatan</td>
							<td align=\"center\">:</td>
							<td>Pejabat Penatausahaan Keuangan</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">Dalam Hal ini bertindak selaku perwakilan Unit Kerja $nmskpd, selanjutnya di sebut sebagai Pihak Kedua.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pihak Pertama dan Pihak Kedua Bersama-sama melakukan rekonsilisasi Laporan Keuangan Periode $tgl_periode1 $buln_prd1 $thn_periode1 sampai dengan $tgl_periode2 $buln_prd2 $thn_periode1. Rekonsiliasi ini dimaksud dilakukan dengan cara membandingkan :</td>
                        </tr>
						<tr>
							<td valign=\"top\">1. </td>
							<td align=\"justify\" colspan=\"2\">Data yang dimiliki Pejabat Penatausahaan Keuangan pada Unit Kerja berupa Laporan Keuangan yang terdiri dari Laporan Operasional, Neraca, Laporan Perubahan Ekuitas dan Laporan Barang Milik Daerah; dengan</td>
                        </tr>
						<tr>
							<td valign=\"top\">2. </td>
							<td align=\"justify\" colspan=\"2\">Data yang dimiliki Bidang Akuntansi dan Pelaporan berupa Laporan Keuangan.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selanjutnya, hasil rekonsilisasi dituangkan dalam Lampiran Berita Acara Rekonsiliasi (BAR) yang merupakan bagian yang tidak terpisahkan dari BAR ini.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Demikian berita acara ini dibuat dengan sadar dan tanpa paksaan.</td>
                        </tr>
                    </table>
					";
					
					$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
						<tr >
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">$daerah, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">Pihak Pertma,</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">Pihak Kedua,</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\"width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">$namakabid</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">................................................</td>
                        </tr>";
     
        $cRet .='</table>';
        		  
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = "NERACA";
        $this->template->set('title', 'RINGKASAN NERACA $cbulan');  
         switch($cetak) {
		case 4;
			$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');	
        break;
        case 3;
			echo ("<title>Neraca UNIT $cbulan</title>");
			 echo $cRet;
        break;
        case 6;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 5;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-word");
            header("Content-Disposition: attachment; filename= $judul.doc");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        } 

    }
	
	function cetak_ba_lo($kd_skpd="", $cetak=''){
        $pilih = $cetak;
        $id     = $kd_skpd;
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1= $thn_ang-1;  
                  
        $skpd = "AND kd_skpd='$id'";
        $skpd1 = "AND b.kd_skpd='$id'"; 
	   
       $y123=")";
	   $x123="(";

		$tgl_ttd = $this->uri->segment(9);
		$periode1 = $this->uri->segment(13);
		$periode2 = $this->uri->segment(14);
		
		$arrayperiode1=explode("-",$periode1);		
		$arrayperiode2=explode("-",$periode2);		
		$arraytgl=explode("-",$tgl_ttd);			
		
		if($arrayperiode2[1]=='3'){
			$tw = "I";
		}if($arrayperiode2[1]=='6'){
			$tw = "II";
		}if($arrayperiode2[1]=='9'){
			$tw = "III";
		}else{
			$tw = "IV";
		}
		
		$tgl   = $arraytgl[2];
		$bln   = $arraytgl[1];
		$thn   = $arraytgl[0];
		
		$tgl_periode1 = $arrayperiode1[2];
		$bln_periode1 = $arrayperiode1[1];
		$thn_periode1 = $arrayperiode1[0];
		
		$tgl_periode2 = $arrayperiode2[2];
		$bln_periode2 = $arrayperiode2[1];
		$thn_periode2 = $arrayperiode2[0];
		
		$tanggl_ttd = $this->tukd_model->terbilang2($tgl);
		$buln_ttd  = $this->getBulan($bln);
		
		$tanggl_prd1 = $this->tukd_model->terbilang2($tgl_periode1);
		$buln_prd1  = $this->getBulan($bln_periode1);
		
		$tanggl_prd2 = $this->tukd_model->terbilang2($tgl_periode2);
		$buln_prd2  = $this->getBulan($bln_periode2); 
		
		$cbulan=$bln_periode2;
		
		if($cbulan==12){
		$sumber_jurnal= "ju_calk";
		}else{
		$sumber_jurnal= "ju";
		}	

		$day = date('D', strtotime($tgl_ttd));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
		
	$kabid    = str_replace('%20',' ',$this->uri->segment(5));
	$stafak   = str_replace('%20',' ',$this->uri->segment(6));
	$kasub    = str_replace('%20',' ',$this->uri->segment(7));
	$ppk      = str_replace('%20',' ',$this->uri->segment(8));
	$penyusun = str_replace('%20',' ',$this->uri->segment(10));
	$terima   = str_replace('%20',' ',$this->uri->segment(11));
	$keluar   = str_replace('%20',' ',$this->uri->segment(12));
	
	$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='3.13.01.17'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
	
	$sqlkabid="select TOP 1 * from ms_ttd where nip='$kabid'";
                 $sqlkabid1=$this->db->query($sqlkabid);
                 foreach ($sqlkabid1->result() as $rowkabid)
                {
                    $nipkabid      = $rowkabid->nip;
                    $namakabid     = $rowkabid->nama;
                    $jabatankabid  = $rowkabid->jabatan;
                    $pangkatkabid  = $rowkabid->pangkat;
                   
                }
				
	$sqlstafak="select TOP 1 * from ms_ttd where nip='$stafak'";
                 $sqlstafak1=$this->db->query($sqlstafak);
                 foreach ($sqlstafak1->result() as $rowstafak)
                {
                    $nipstafak      = $rowstafak->nip;
                    $namastafak     = $rowstafak->nama;
                    $jabatanstafak  = $rowstafak->jabatan;
                    $pangkatstafak  = $rowstafak->pangkat;
                   
                }
	
	$sqlkasub="select TOP 1 * from ms_ttd where nip='$kasub'";
                 $sqlkasub1=$this->db->query($sqlkasub);
                 foreach ($sqlkasub1->result() as $rowkasub)
                {
                    $nipkasub      = $rowkasub->nip;
                    $namakasub     = $rowkasub->nama;
                    $jabatankasub  = $rowkasub->jabatan;
                    $pangkatkasub  = $rowkasub->pangkat;
                   
                }
	
	$sqlppk="select TOP 1 * from ms_ttd where nip='$ppk'";
                 $sqlppk1=$this->db->query($sqlppk);
                 foreach ($sqlppk1->result() as $rowppk)
                {
                    $nipppk      = $rowppk->nip;
                    $namappk     = $rowppk->nama;
                    $jabatanppk  = $rowppk->jabatan;
                    $pangkatppk  = $rowppk->pangkat;
                   
                }
				
	$sqlpenyusun="select TOP 1 * from ms_ttd where nip='$penyusun'";
                 $sqlpenyusun1=$this->db->query($sqlpenyusun);
                 foreach ($sqlpenyusun1->result() as $rowpenyusun)
                {
                    $nippenyusun      = $rowpenyusun->nip;
                    $namapenyusun     = $rowpenyusun->nama;
                    $jabatanpenyusun  = $rowpenyusun->jabatan;
                    $pangkatpenyusun  = $rowpenyusun->pangkat;
                   
                }
	
	$sqlterima="select TOP 1 * from ms_ttd where nip='$terima'";
                 $sqlterima1=$this->db->query($sqlterima);
                 foreach ($sqlterima1->result() as $rowterima)
                {
                    $nipterima      = $rowterima->nip;
                    $namaterima     = $rowterima->nama;
                    $jabatanterima  = $rowterima->jabatan;
                    $pangkatterima  = $rowterima->pangkat;
                   
                }
	
	$sqlkeluar="select TOP 1 * from ms_ttd where nip='$keluar'";
                 $sqlkeluar1=$this->db->query($sqlkeluar);
                 foreach ($sqlkeluar1->result() as $rowkeluar)
                {
                    $nipkeluar      = $rowkeluar->nip;
                    $namakeluar     = $rowkeluar->nama;
                    $jabatankeluar  = $rowkeluar->jabatan;
                    $pangkatkeluar  = $rowkeluar->pangkat;
                   
                }
       
	    $sqlsc="SELECT nm_skpd FROM ms_skpd where kd_skpd='$id'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $nmskpd     = $rowsc->nm_skpd;
                } 
		$nm_skpd = strtoupper($nmskpd);		
	   // INSERT DATA
	   
       $sqldns="SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$kd_skpd'";
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                } 
     
// created by henri_tb
			
		$sqllo1="select sum(kredit-debet) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('81','82','83') and kd_skpd='$kd_skpd'";
                    $querylo1= $this->db->query($sqllo1);
                    $penlo = $querylo1->row();
                    $pen_lo = $penlo->nilai;
                    $pen_lo1= number_format($penlo->nilai,"2",",",".");
        
		$sqllo2="select sum(kredit-debet) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('81','82','83') and kd_skpd='$kd_skpd'";
                    $querylo2= $this->db->query($sqllo2);
                    $penlo2 = $querylo2->row();
                    $pen_lo_lalu = $penlo2->nilai;
                    $pen_lo_lalu1= number_format($penlo2->nilai,"2",",",".");
		
		$sqllo3="select sum(debet-kredit) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('91','92') and kd_skpd='$kd_skpd'";
                    $querylo3= $this->db->query($sqllo3);
                    $bello = $querylo3->row();
                    $bel_lo = $bello->nilai;
                    $bel_lo1= number_format($bello->nilai,"2",",",".");
        
		$sqllo4="select sum(debet-kredit) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('91','92') and kd_skpd='$kd_skpd'";
                    $querylo4= $this->db->query($sqllo4);
                    $bello2 = $querylo4->row();
                    $bel_lo_lalu = $bello2->nilai;
                    $bel_lo_lalu1= number_format($bello2->nilai,"2",",",".");		

					$surplus_lo = $pen_lo - $bel_lo;
                    if ($surplus_lo < 0){
                    	$lo1="("; $surplus_lox=$surplus_lo*-1; $lo2=")";}
                    else {
                    	$lo1=""; $surplus_lox=$surplus_lo; $lo2="";}		
                    $surplus_lo1 = number_format($surplus_lox,"2",",",".");
                    
					$surplus_lo_lalu = $pen_lo_lalu - $bel_lo_lalu;
                    if ($surplus_lo_lalu < 0){
                    	$lo3="("; $surplus_lo_lalux=$surplus_lo_lalu*-1; $lo4=")";}
                    else {
                    	$lo3=""; $surplus_lo_lalux=$surplus_lo_lalu; $lo4="";}						
                    $surplus_lo_lalu1 = number_format($surplus_lo_lalux,"2",",",".");

					$selisih_surplus_lo = $surplus_lo - $surplus_lo_lalu;
                    if ($selisih_surplus_lo < 0){
                    	$lo5="("; $selisih_surplus_lox=$selisih_surplus_lo*-1; $lo6=")";}
                    else {
                    	$lo5=""; $selisih_surplus_lox=$selisih_surplus_lo; $lo6="";}
                    $selisih_surplus_lo1 = number_format($selisih_surplus_lox,"2",",",".");
                    
					if( $surplus_lo_lalu=='' or $surplus_lo_lalu==0){
					$persen2 = '0,00';
					}else{
					$persen2 = ($surplus_lo/$surplus_lo_lalu)*100;
					$persen2 = number_format($persen2,"2",",",".");
					}
					
		$sqllo5="select sum(kredit-debet) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('81','82','83','84') and kd_skpd='$kd_skpd'";
                    $querylo5= $this->db->query($sqllo5);
                    $penlo3 = $querylo5->row();
                    $pen_lo3 = $penlo3->nilai;
                    $pen_lo31= number_format($penlo3->nilai,"2",",",".");
        
		$sqllo6="select sum(kredit-debet) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('81','82','83','84') and kd_skpd='$kd_skpd'";
                    $querylo6= $this->db->query($sqllo6);
                    $penlo4 = $querylo6->row();
                    $pen_lo_lalu4 = $penlo4->nilai;
                    $pen_lo_lalu41= number_format($penlo4->nilai,"2",",",".");
		
		$sqllo7="select sum(debet-kredit) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('91','92','93') and kd_skpd='$kd_skpd'";
                    $querylo7= $this->db->query($sqllo7);
                    $bello5 = $querylo7->row();
                    $bel_lo5 = $bello5->nilai;
                    $bel_lo51= number_format($bello5->nilai,"2",",",".");
		
		$sqllo8="select sum(debet-kredit) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('91','92','93') and kd_skpd='$kd_skpd'";
                    $querylo8= $this->db->query($sqllo8);
                    $bello6 = $querylo8->row();
                    $bel_lo_lalu6 = $bello6->nilai;
                    $bel_lo_lalu61= number_format($bello6->nilai,"2",",",".");		

					$surplus_lo2 = $pen_lo3 - $bel_lo5;
                    if ($surplus_lo2 < 0){
                    	$lo7="("; $surplus_lo2x=$surplus_lo2*-1; $lo8=")";}
                    else {
                    	$lo7=""; $surplus_lo2x=$surplus_lo2; $lo8="";}		
                    $surplus_lo21 = number_format($surplus_lo2x,"2",",",".");
                    
					$surplus_lo_lalu2 = $pen_lo_lalu4 - $bel_lo_lalu6;
                    if ($surplus_lo_lalu2 < 0){
                    	$lo9="("; $surplus_lo_lalu2x=$surplus_lo_lalu2*-1; $lo10=")";}
                    else {
                    	$lo9=""; $surplus_lo_lalu2x=$surplus_lo_lalu2; $lo10="";}						
                    $surplus_lo_lalu21 = number_format($surplus_lo_lalu2x,"2",",",".");

					$selisih_surplus_lo2 = $surplus_lo2 - $surplus_lo_lalu2;
                    if ($selisih_surplus_lo2 < 0){
                    	$lo11="("; $selisih_surplus_lo2x=$selisih_surplus_lo2*-1; $lo12=")";}
                    else {
                    	$lo11=""; $selisih_surplus_lo2x=$selisih_surplus_lo2; $lo12="";}
                    $selisih_surplus_lo21 = number_format($selisih_surplus_lo2x,"2",",",".");
                    
					if( $surplus_lo_lalu2=='' or $surplus_lo_lalu2==0){
					$persen3 = '0,00';
					}else{
					$persen3 = ($surplus_lo2/$surplus_lo_lalu2)*100;
					$persen3 = number_format($persen3,"2",",",".");
					}
		
		$sqllo9="select sum(kredit-debet) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,1) in ('8') and kd_skpd='$kd_skpd'";
                    $querylo9= $this->db->query($sqllo9);
                    $penlo7 = $querylo9->row();
                    $pen_lo7 = $penlo7->nilai;
                    $pen_lo71= number_format($penlo7->nilai,"2",",",".");
        
		$sqllo10="select sum(kredit-debet) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('8') and kd_skpd='$kd_skpd'";
                    $querylo10= $this->db->query($sqllo10);
                    $penlo8 = $querylo10->row();
                    $pen_lo_lalu8 = $penlo8->nilai;
                    $pen_lo_lalu81= number_format($penlo8->nilai,"2",",",".");
		
		$sqllo11="select sum(debet-kredit) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,1) in ('9') and kd_skpd='$kd_skpd'";
                    $querylo11= $this->db->query($sqllo11);
                    $bello9 = $querylo11->row();
                    $bel_lo9 = $bello9->nilai;
                    $bel_lo91= number_format($bello9->nilai,"2",",",".");
		
		$sqllo12="select sum(debet-kredit) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('9') and kd_skpd='$kd_skpd'";
                    $querylo12= $this->db->query($sqllo12);
                    $bello10 = $querylo12->row();
                    $bel_lo_lalu10 = $bello10->nilai;
                    $bel_lo_lalu101= number_format($bello10->nilai,"2",",",".");		

					$surplus_lo3 = $pen_lo7 - $bel_lo9;
                    if ($surplus_lo3 < 0){
                    	$lo13="("; $surplus_lo3x=$surplus_lo3*-1; $lo14=")";}
                    else {
                    	$lo13=""; $surplus_lo3x=$surplus_lo3; $lo14="";}		
                    $surplus_lo31 = number_format($surplus_lo3x,"2",",",".");
                    
					$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
                    if ($surplus_lo_lalu3 < 0){
                    	$lo15="("; $surplus_lo_lalu3x=$surplus_lo_lalu3*-1; $lo16=")";}
                    else {
                    	$lo15=""; $surplus_lo_lalu3x=$surplus_lo_lalu3; $lo16="";}						
                    $surplus_lo_lalu31 = number_format($surplus_lo_lalu3x,"2",",",".");

					$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
                    if ($selisih_surplus_lo3 < 0){
                    	$lo17="("; $selisih_surplus_lo3x=$selisih_surplus_lo3*-1; $lo18=")";}
                    else {
                    	$lo17=""; $selisih_surplus_lo3x=$selisih_surplus_lo3; $lo18="";}
                    $selisih_surplus_lo31 = number_format($selisih_surplus_lo3x,"2",",",".");
                    
					if( $surplus_lo_lalu3=='' or $surplus_lo_lalu3==0){
					$persen4 = '0,00';
					}else{
					$persen4 = ($surplus_lo3/$surplus_lo_lalu3)*100;
					$persen4 = number_format($persen4,"2",",",".");
					}

					
		$sqllo13="select sum(kredit-debet) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('84') and kd_skpd='$kd_skpd'";
                    $querylo13= $this->db->query($sqllo13);
                    $penlo11 = $querylo13->row();
                    $pen_lo11 = $penlo11->nilai;
                    $pen_lo111= number_format($penlo11->nilai,"2",",",".");
        
		$sqllo14="select sum(kredit-debet) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('84') and kd_skpd='$kd_skpd'";
                    $querylo14= $this->db->query($sqllo14);
                    $penlo12 = $querylo14->row();
                    $pen_lo_lalu12 = $penlo12->nilai;
                    $pen_lo_lalu121= number_format($penlo12->nilai,"2",",",".");
		
		$sqllo15="select sum(debet-kredit) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('93') and kd_skpd='$kd_skpd'";
                    $querylo15= $this->db->query($sqllo15);
                    $bello13 = $querylo15->row();
                    $bel_lo13 = $bello13->nilai;
                    $bel_lo131= number_format($bello13->nilai,"2",",",".");
		
		$sqllo16="select sum(debet-kredit) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('93') and kd_skpd='$kd_skpd'";
                    $querylo16= $this->db->query($sqllo16);
                    $bello14 = $querylo16->row();
                    $bel_lo_lalu14 = $bello14->nilai;
                    $bel_lo_lalu141= number_format($bello14->nilai,"2",",",".");		

					$surplus_lo4 = $pen_lo11 - $bel_lo13;
                    if ($surplus_lo4 < 0){
                    	$lo19="("; $surplus_lo4x=$surplus_lo4*-1; $lo20=")";}
                    else {
                    	$lo19=""; $surplus_lo4x=$surplus_lo4; $lo20="";}		
                    $surplus_lo41 = number_format($surplus_lo4x,"2",",",".");
                    
					$surplus_lo_lalu4 = $pen_lo_lalu12 - $bel_lo_lalu14;
                    if ($surplus_lo_lalu4 < 0){
                    	$lo21="("; $surplus_lo_lalu4x=$surplus_lo_lalu4*-1; $lo22=")";}
                    else {
                    	$lo21=""; $surplus_lo_lalu4x=$surplus_lo_lalu4; $lo22="";}						
                    $surplus_lo_lalu41 = number_format($surplus_lo_lalu4x,"2",",",".");

					$selisih_surplus_lo4 = $surplus_lo4 - $surplus_lo_lalu4;
                    if ($selisih_surplus_lo4 < 0){
                    	$lo23="("; $selisih_surplus_lo4x=$selisih_surplus_lo4*-1; $lo24=")";}
                    else {
                    	$lo23=""; $selisih_surplus_lo4x=$selisih_surplus_lo4; $lo24="";}
                    $selisih_surplus_lo41 = number_format($selisih_surplus_lo4x,"2",",",".");
                    
					if( $surplus_lo_lalu4=='' or $surplus_lo_lalu4==0){
					$persen5 = '0,00';
					}else{
					$persen5 = ($surplus_lo4/$surplus_lo_lalu4)*100;
					$persen5 = number_format($persen5,"2",",",".");
					}
					
					
		$sqllo17="select sum(kredit-debet) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('85') and kd_skpd='$kd_skpd'";
                    $querylo17= $this->db->query($sqllo17);
                    $penlo15 = $querylo17->row();
                    $pen_lo15 = $penlo15->nilai;
                    $pen_lo151= number_format($penlo15->nilai,"2",",",".");
        
		$sqllo18="select sum(kredit-debet) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('85') and kd_skpd='$kd_skpd'";
                    $querylo18= $this->db->query($sqllo18);
                    $penlo16 = $querylo18->row();
                    $pen_lo_lalu16 = $penlo16->nilai;
                    $pen_lo_lalu161= number_format($penlo16->nilai,"2",",",".");
		
		$sqllo19="select sum(debet-kredit) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('94') and kd_skpd='$kd_skpd'";
                    $querylo19= $this->db->query($sqllo19);
                    $bello17 = $querylo19->row();
                    $bel_lo17 = $bello17->nilai;
                    $bel_lo171= number_format($bello17->nilai,"2",",",".");
		
		$sqllo20="select sum(debet-kredit) as nilai from trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('94') and kd_skpd='$kd_skpd'";
                    $querylo20= $this->db->query($sqllo20);
                    $bello18 = $querylo20->row();
                    $bel_lo_lalu18 = $bello18->nilai;
                    $bel_lo_lalu181= number_format($bello18->nilai,"2",",",".");		

					$surplus_lo5 = $pen_lo15 - $bel_lo17;
                    if ($surplus_lo5 < 0){
                    	$lo25="("; $surplus_lo5x=$surplus_lo5*-1; $lo26=")";}
                    else {
                    	$lo25=""; $surplus_lo5x=$surplus_lo5; $lo26="";}		
                    $surplus_lo51 = number_format($surplus_lo5x,"2",",",".");
                    
					$surplus_lo_lalu5 = $pen_lo_lalu16 - $bel_lo_lalu18;
                    if ($surplus_lo_lalu5 < 0){
                    	$lo27="("; $surplus_lo_lalu5x=$surplus_lo_lalu5*-1; $lo28=")";}
                    else {
                    	$lo27=""; $surplus_lo_lalu5x=$surplus_lo_lalu5; $lo28="";}						
                    $surplus_lo_lalu51 = number_format($surplus_lo_lalu5x,"2",",",".");

					$selisih_surplus_lo5 = $surplus_lo5 - $surplus_lo_lalu5;
                    if ($selisih_surplus_lo5 < 0){
                    	$lo29="("; $selisih_surplus_lo5x=$selisih_surplus_lo5*-1; $lo30=")";}
                    else {
                    	$lo29=""; $selisih_surplus_lo5x=$selisih_surplus_lo5; $lo30="";}
                    $selisih_surplus_lo51 = number_format($selisih_surplus_lo5x,"2",",",".");
                    
					if( $surplus_lo_lalu5=='' or $surplus_lo_lalu5==0){
					$persen6 = '0,00';
					}else{
					$persen6 = ($surplus_lo5/$surplus_lo_lalu5)*100;
					$persen6 = number_format($persen6,"2",",",".");
					}			
	 
	 
        $cRet='';
        
       
       $cRet .="<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td rowspan=\"4\" align=\"right\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:16px\"><strong>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH</strong></tr>
					<tr><td colspan=\"3\" align=\"center\"><strong>Jalan Ahmad Yani Telepon (0561) 736541 Fax (0561) 738428</strong></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PONTIANAK</strong></td></tr>
					<tr><td colspan=\"4\" align=\"right\">Kode Pos: 78124 &nbsp; &nbsp;</td></tr>
					</table>
					<hr  valign=\"top\" color=\"black\" size=\"3px\" width=\"100%\"> 
					";
			
			$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                        <tr>
							<td rowspan=\"2\" align=\"right\" width=\"10%\" height=\"50\">&nbsp;</td>
							<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><b>Laporan Operasional Tahun Anggaran $thn_periode2</b></td>
						</tr>
						<tr>
							<td colspan=\"5\" align=\"center\" style=\"font-size:14px\"><b>Periode $tgl_periode1 $buln_prd1 - $tgl_periode2 $buln_prd2 $thn_periode2</b></td>
                        </tr>
						<tr>
						<td colspan=\"5\" align=\"justify\" style=\"font-size:12px\">
						<br>
						Pada hari ini ".$dayList[$day]." Tanggal $tanggl_ttd Bulan $buln_ttd  Tahun $thn. $nm_skpd telah melaksanakan rekonsiliasi dengan 
						Bidang Akuntansi Badan Pengelolaan Keuangan dan Pendapatan Daerah Provinsi Kalimantan Barat 
						<br>
						<br>
						
						</td>
                        </tr>
						<tr>
						<td colspan=\"5\" align=\"justify\" style=\"font-size:12px\">
						<br>
						SKPD : $id - $nm_skpd
						<br>
						<br>
						
						</td>
                        </tr>
                    </table>
					";
        
       $cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
							<td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
                            <td colspan=\"2\" bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>LAPORAN OPERASIONAL TA. $thn_ang</b></td>
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"30%\" align=\"center\"><b>KETERANGAN</b></td>
                        </tr>
						<tr>
							<td bgcolor=\"#CCCCCC\" width=\"35%\" align=\"center\"><b>Uraian</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Nilai</b></td>     
						</tr>
                        
                     </thead>
                                        
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\">&nbsp;</td>
                        </tr>";

        $sqlmaplo="SELECT seq, nor, uraian, isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak FROM map_lo_prov 
				   GROUP BY seq, nor, uraian, isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet') ORDER BY seq";
				   
                $querymaplo = $this->db->query($sqlmaplo);
                $no     = 0;                                  
               
                foreach ($querymaplo->result() as $loquery)
                {
                    
                    $nama      = $loquery->uraian;   
                    $n         = $loquery->kode_1;
					$n		   = ($n=="-"?"'-'":$n);
					$n2        = $loquery->kode_2;
					$n2		   = ($n2=="-"?"'-'":$n2);
					$n3        = $loquery->kode_3;
					$n3		   = ($n3=="-"?"'-'":$n3);
					$normal    = $loquery->cetak;
					
		$quelo01   = "SELECT SUM($normal) as nilai FROM trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek5,3) in ($n) or left(kd_rek5,5) in ($n2) or left(kd_rek5,7) in ($n3)) and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and kd_skpd='$kd_skpd'";
                    $quelo02 = $this->db->query($quelo01);
                    $quelo03 = $quelo02->row();
                    $nil     = $quelo03->nilai;
                    $nilai    = number_format($quelo03->nilai,"2",",",".");
					
		$quelo04   = "SELECT SUM($normal) as nilai FROM trd$sumber_jurnal a inner join trh$sumber_jurnal b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek5,3) in ($n) or left(kd_rek5,5) in ($n2) or left(kd_rek5,7) in ($n3)) and year(tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'";
                    $quelo05 = $this->db->query($quelo04);
                    $quelo06 = $quelo05->row();
                    $nil_lalu     = $quelo06->nilai;
                    $nilai_lalu    = number_format($quelo06->nilai,"2",",",".");
					
                    $real_nilai = $nil - $nil_lalu;
                    if ($real_nilai < 0){
                    	$lo0="("; $real_nilaix=$real_nilai*-1; $lo00=")";}
                    else {
                    	$lo0=""; $real_nilaix=$real_nilai; $lo00="";}
                    $real_nilai1 = number_format($real_nilaix,"2",",",".");
                    
					if( $nil_lalu=='' or $nil_lalu==0){
					$persen1 = '0,00';
					}else{
					$persen1 = ($nil/$nil_lalu)*100;
					$persen1 = number_format($persen1,"2",",",".");
					}
                    $no       = $no + 1;
                    switch ($loquery->seq) {
                    case 5:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;
					case 10:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;
					case 40:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;
					case 45:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;	
                    case 50:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 80:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;
					case 85:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 110:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 115:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 140:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 145:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;
					case 150:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 200:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 205:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 245:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 250:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo1$surplus_lo1$lo2</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;
					case 255:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 260:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 265:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;
					case 290:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 295:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 325:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;
					case 330:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo7$surplus_lo21$lo8</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 335:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 340:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                   
                                 </tr>";
                        break;
					case 345:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 360:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 365:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;
					case 385:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 390:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo13$surplus_lo31$lo14</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;
					default:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";

                }
                              
                    
                }
        $cRet .=       " </table>";
		
        $data['prev']= $cRet;
        $data['sikap'] = 'preview';
        $judul  = ("LO");
        $this->template->set('title', ("LO UNIT $kd_skpd / $cbulan"));        
        switch($pilih) {
		case 0;
			$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');	
        break;
        case 1;
			echo ("<title>LO UNIT $cbulan</title>");
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
    
	function cetak_ringkasan_lo($kd_skpd="", $cetak = ''){
		$cbulan = 12;
		$id = $kd_skpd;
        $thn_ang = $this->session->userdata('pcThang');
		$bulan	 = $cbulan;
        $id1     = $this->session->userdata('kdskpd');
        $nmskpd = $this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd');
        $nm_skpd =strtoupper($nmskpd);
        $thn_ang_1= $thn_ang-1;   
        
		$tgl_ttd = $this->uri->segment(9);
		$periode1 = $this->uri->segment(13);
		$periode2 = $this->uri->segment(14);
		
		$arrayperiode1=explode("-",$periode1);
		$arrayperiode2=explode("-",$periode2);		
		
		if($arrayperiode2[1]<='3'){
			$tw = "I";
		}else if($arrayperiode2[1]<='6'){
			$tw = "II";
		}else if($arrayperiode2[1]<='9'){
			$tw = "III";
		}else{
			$tw = "IV";
		}
		
		$kabid    = str_replace('%20',' ',$this->uri->segment(7));
		
		$sqlkabid="select TOP 1 * from ms_ttd where nip='$kabid'";
                 $sqlkabid1=$this->db->query($sqlkabid);
                 foreach ($sqlkabid1->result() as $rowkabid)
                {
                    $nipkabid      = $rowkabid->nip;
                    $namakabid     = $rowkabid->nama;
                    $jabatankabid  = $rowkabid->jabatan;
                    $pangkatkabid  = $rowkabid->pangkat;
                   
                }
		
		$tgl   = substr($tgl_ttd,7,2);
		$bln   = substr($tgl_ttd,5,2);
		$thn   = substr($tgl_ttd,0,4);
		
		
		//$bln2 = substr($periode2,5,2);
		$bln2 = $arrayperiode2[1];
		
		$tanggl = $this->tukd_model->terbilang2($tgl);
		$buln  = $this->getBulan($bln);
		$tahun = $this->tukd_model->terbilang2($thn); 
		
		$tgl_periode1 = substr($periode1,7,2);
		$bln_periode1 = substr($periode1,5,1);
		$thn_periode1 = substr($periode1,0,4);
		
		$len = strlen($periode2);
		
		if($len<>'10'){
			$tgl_periode2 = substr($periode2,7,2);
			$bln_periode2 = substr($periode2,5,1);
		}else{
			$tgl_periode2 = substr($periode2,8,2);
			$bln_periode2 = substr($periode2,5,2);
		}
		
		$tanggl_prd1 = $this->tukd_model->terbilang2($tgl_periode1);
		$buln_prd1   = $this->getBulan($bln_periode1);
		
		$tanggl_prd2 = $this->tukd_model->terbilang2($tgl_periode2);
		$buln_prd2   = $this->getBulan($bln_periode2);
		
		

		$day = date('D', strtotime($tgl_ttd));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
	

	
	$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='3.13.01.17'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
				
	$sqlsc="SELECT nm_skpd FROM ms_skpd where kd_skpd='3.13.01.01'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $nm_ppkd     = $rowsc->nm_skpd;
                }
	
			    
					$cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td rowspan=\"4\" align=\"right\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:16px\"><strong>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH</strong></tr>
					<tr><td colspan=\"3\" align=\"center\"><strong>Jalan Ahmad Yani Telepon (0561) 736541 Fax (0561) 738428</strong></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PONTIANAK</strong></td></tr>
					<tr><td colspan=\"4\" align=\"right\">Kode Pos: 78124 &nbsp; &nbsp;</td></tr>
					</table>
					<hr  valign=\"top\" color=\"black\" size=\"3px\" width=\"100%\"> 
					";
			
			$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                        <tr>
							<td rowspan=\"2\" align=\"right\" width=\"10%\" height=\"50\">&nbsp;</td>
							<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><b><u> BERITA ACARA REKONSILIASI</u></b></td>
						</tr>
						<tr>
							<td colspan=\"5\" align=\"center\" style=\"font-size:14px\"><b>Nomor : 900/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/BPKPD-D/$thn_ang</b></td>
                        </tr>
                    </table>
					<br>
					<br>
					";
					
					$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:14px;\" width=\"100%\" align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
						<tr>
							<td colspan=\"3\">Kami yang bertandatangan di bawah ini :</td>
                        </tr>
						<tr>
							<td align=\"left\" width=\"10%\">Nama</td>
							<td align=\"center\" width=\"3%\">:</td>
							<td align=\"left\" width=\"82%\">$namakabid</td>
                        </tr>
						<tr>
							<td align=\"left\">NIP</td>
							<td align=\"center\">:</td>
							<td align=\"left\">$nipkabid</td>
                        </tr>
						<tr>
							<td align=\"left\">Jabatan</td>
							<td align=\"center\">:</td>
							<td align=\"left\">$jabatankabid</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">Dalam Hal ini bertindak selaku perwakilan Bidang Akuntansi dan Pelaporan $nm_ppkd, selanjutnya di sebut sebagai Pihak Pertama.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td>Nama</td>
							<td align=\"center\">:</td>
							<td>.........................................................</td>
                        </tr>
						<tr>
							<td>NIP</td>
							<td align=\"center\">:</td>
							<td>.........................................................</td>
                        </tr>
						<tr>
							<td>Jabatan</td>
							<td align=\"center\">:</td>
							<td>Pejabat Penatausahaan Keuangan</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">Dalam Hal ini bertindak selaku perwakilan Unit Kerja $nmskpd, selanjutnya di sebut sebagai Pihak Kedua.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pihak Pertama dan Pihak Kedua Bersama-sama melakukan rekonsilisasi Laporan Keuangan Periode $tgl_periode1 $buln_prd1 $thn_periode1 sampai dengan $tgl_periode2 $buln_prd2 $thn_periode1. Rekonsiliasi ini dimaksud dilakukan dengan cara membandingkan :</td>
                        </tr>
						<tr>
							<td valign=\"top\">1. </td>
							<td align=\"justify\" colspan=\"2\">Data yang dimiliki Pejabat Penatausahaan Keuangan pada Unit Kerja berupa Laporan Keuangan yang terdiri dari Laporan Operasional, Neraca, Laporan Perubahan Ekuitas dan Laporan Barang Milik Daerah; dengan</td>
                        </tr>
						<tr>
							<td valign=\"top\">2. </td>
							<td align=\"justify\" colspan=\"2\">Data yang dimiliki Bidang Akuntansi dan Pelaporan berupa Laporan Keuangan.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selanjutnya, hasil rekonsilisasi dituangkan dalam Lampiran Berita Acara Rekonsiliasi (BAR) yang merupakan bagian yang tidak terpisahkan dari BAR ini.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Demikian berita acara ini dibuat dengan sadar dan tanpa paksaan.</td>
                        </tr>
                    </table>
					";
					
					$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
						<tr >
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">$daerah, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">Pihak Pertma,</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">Pihak Kedua,</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\"width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">$namakabid</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">................................................</td>
                        </tr>";
     
        $cRet .='</table>';
        		  
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = "LO";
        $this->template->set('title', 'RINGKASAN LO $cbulan');  
         switch($cetak) {
		case 4;
			$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');	
        break;
        case 3;
			echo ("<title>LO UNIT $cbulan</title>");
			 echo $cRet;
        break;
        case 6;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 5;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-word");
            header("Content-Disposition: attachment; filename= $judul.doc");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        } 

    }
	
	function cetak_ba_lra( $kd_skpd="", $pilih=''){
	
	$id  = $kd_skpd;
    $thn_ang = $this->session->userdata('pcThang');
	
	
	$tgl_ttd = $this->uri->segment(9);
	$periode1 = $this->uri->segment(13);
	$periode2 = $this->uri->segment(14);
	
	$arrayperiode1=explode("-",$periode1);		
	$arrayperiode2=explode("-",$periode2);		
	$arraytgl=explode("-",$tgl_ttd);		
		
		if($arrayperiode2[1]=='3'){
			$tw = "I";
		}if($arrayperiode2[1]=='6'){
			$tw = "II";
		}if($arrayperiode2[1]=='9'){
			$tw = "III";
		}else{
			$tw = "IV";
		}
		
	$tgl   = $arraytgl[2];
	$bln   = $arraytgl[1];
	$thn   = $arraytgl[0];
	/*
	$tgl   = substr($tgl_ttd,7,2);
	$bln   = substr($tgl_ttd,5,2);
	$thn   = substr($tgl_ttd,0,4);
	*/	
		$tanggl = $this->tukd_model->terbilang2($tgl);
		$buln  = $this->getBulan($bln);
		$tahun = $this->tukd_model->terbilang2($thn); 
		 
		/*
		$tgl_periode1 = substr($periode1,7,2);
		$bln_periode1 = substr($periode1,5,1);
		$thn_periode1 = substr($periode1,0,4);
		*/
		
		$tgl_periode1 = $arrayperiode1[2];
		$bln_periode1 = $arrayperiode1[1];
		$thn_periode1 = $arrayperiode1[0];
		
		$len = strlen($periode2);
		/*
		if($len<>'10'){
			$tgl_periode2 = substr($periode2,7,2);
		}else{
			$tgl_periode2 = substr($periode2,8,2);
		}
		
		$bln_periode2 = substr($periode2,5,1);
		*/
		
		$tgl_periode2 = $arrayperiode2[2];
		$bln_periode2 = $arrayperiode2[1];
		$thn_periode2 = $arrayperiode2[0];

		$tanggl_prd1 = $this->tukd_model->terbilang2($tgl_periode1);
		$buln_prd1  = $this->getBulan($bln_periode1);
		
		$tanggl_prd2 = $this->tukd_model->terbilang2($tgl_periode2);
		$buln_prd2  = $this->getBulan($bln_periode2); 
		 
		$day = date('D', strtotime($tgl_ttd));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
	
	$cbulan=$bln_periode2;
	
	
	$kabid    = str_replace('%20',' ',$this->uri->segment(5));
	$stafak   = str_replace('%20',' ',$this->uri->segment(6));
	$kasub    = str_replace('%20',' ',$this->uri->segment(7));
	$ppk      = str_replace('%20',' ',$this->uri->segment(8));
	$penyusun = str_replace('%20',' ',$this->uri->segment(10));
	$terima   = str_replace('%20',' ',$this->uri->segment(11));
	$keluar   = str_replace('%20',' ',$this->uri->segment(12));
	
	$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='3.13.01.17'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
	
	$sqlkabid="select TOP 1 * from ms_ttd where nip='$kabid'";
                 $sqlkabid1=$this->db->query($sqlkabid);
                 foreach ($sqlkabid1->result() as $rowkabid)
                {
                    $nipkabid      = $rowkabid->nip;
                    $namakabid     = $rowkabid->nama;
                    $jabatankabid  = $rowkabid->jabatan;
                    $pangkatkabid  = $rowkabid->pangkat;
                   
                }
				
	$sqlstafak="select TOP 1 * from ms_ttd where nip='$stafak'";
                 $sqlstafak1=$this->db->query($sqlstafak);
                 foreach ($sqlstafak1->result() as $rowstafak)
                {
                    $nipstafak      = $rowstafak->nip;
                    $namastafak     = $rowstafak->nama;
                    $jabatanstafak  = $rowstafak->jabatan;
                    $pangkatstafak  = $rowstafak->pangkat;
                   
                }
	
	$sqlkasub="select TOP 1 * from ms_ttd where nip='$kasub'";
                 $sqlkasub1=$this->db->query($sqlkasub);
                 foreach ($sqlkasub1->result() as $rowkasub)
                {
                    $nipkasub      = $rowkasub->nip;
                    $namakasub     = $rowkasub->nama;
                    $jabatankasub  = $rowkasub->jabatan;
                    $pangkatkasub  = $rowkasub->pangkat;
                   
                }
	
	$sqlppk="select TOP 1 * from ms_ttd where nip='$ppk'";
                 $sqlppk1=$this->db->query($sqlppk);
                 foreach ($sqlppk1->result() as $rowppk)
                {
                    $nipppk      = $rowppk->nip;
                    $namappk     = $rowppk->nama;
                    $jabatanppk  = $rowppk->jabatan;
                    $pangkatppk  = $rowppk->pangkat;
                   
                }
				
	$sqlpenyusun="select TOP 1 * from ms_ttd where nip='$penyusun'";
                 $sqlpenyusun1=$this->db->query($sqlpenyusun);
                 foreach ($sqlpenyusun1->result() as $rowpenyusun)
                {
                    $nippenyusun      = $rowpenyusun->nip;
                    $namapenyusun     = $rowpenyusun->nama;
                    $jabatanpenyusun  = $rowpenyusun->jabatan;
                    $pangkatpenyusun  = $rowpenyusun->pangkat;
                   
                }
	
	$sqlterima="select TOP 1 * from ms_ttd where nip='$terima'";
                 $sqlterima1=$this->db->query($sqlterima);
                 foreach ($sqlterima1->result() as $rowterima)
                {
                    $nipterima      = $rowterima->nip;
                    $namaterima     = $rowterima->nama;
                    $jabatanterima  = $rowterima->jabatan;
                    $pangkatterima  = $rowterima->pangkat;
                   
                }
	
	$sqlkeluar="select TOP 1 * from ms_ttd where nip='$keluar'";
                 $sqlkeluar1=$this->db->query($sqlkeluar);
                 foreach ($sqlkeluar1->result() as $rowkeluar)
                {
                    $nipkeluar      = $rowkeluar->nip;
                    $namakeluar     = $rowkeluar->nama;
                    $jabatankeluar  = $rowkeluar->jabatan;
                    $pangkatkeluar  = $rowkeluar->pangkat;
                   
                }
	
	if($cbulan== 12){
	$sumber_data= "_at";
	}else{
	$sumber_data= "";
	}
	
	
	$sqlanggaran1="select case when statu=1 and status_sempurna=1 and status_ubah=1 and $cbulan>=month(tgl_dpa_ubah) then 2 
					   when statu=1 and status_sempurna=1 and status_ubah=1 and $cbulan>=month(tgl_dpa_sempurna) and $cbulan<month(tgl_dpa_ubah) then 1
					   when statu=1 and status_sempurna=1 and status_ubah=1 and $cbulan<month(tgl_dpa_sempurna) then 1
					   when statu=1 and status_sempurna=1 and status_ubah=0 and $cbulan>=month(tgl_dpa_sempurna) then 1 
					   when statu=1 and status_sempurna=1 and status_ubah=0 and $cbulan<month(tgl_dpa_sempurna) then 1
					   when statu=1 and status_sempurna=0 and status_ubah=0 and $cbulan>=month(tgl_dpa) then 1
					   else 1 end as anggaran from trhrka where kd_skpd='$kd_skpd'";
    $sqlanggaran=$this->db->query($sqlanggaran1);
	foreach ($sqlanggaran->result() as $rowttd)
        {
            $anggaran=$rowttd->anggaran;
        }

	$ag_tox=$anggaran;
	 
	 

    //function cetak_lra(){
        $sql41="SELECT SUM(anggaran) as anggaran,SUM(real_spj) as nilai FROM data_realisasi_n$sumber_data($cbulan,$ag_tox,$thn_periode2) WHERE left(kd_rek5,1)='4' and kd_skpd='$kd_skpd' ";
                    $query41 = $this->db->query($sql41);
                    $jmlp = $query41->row();
                    $jmlpendapatan = $jmlp->nilai;
                    $jmlangpendapatan = $jmlp->anggaran;
                    $jmlangpendapatan1= number_format($jmlp->anggaran,"2",",",".");
                    $jmlpendapatan1= number_format($jmlp->nilai,"2",",",".");
                    
					$real_pend = $jmlangpendapatan - $jmlpendapatan;
                    if ($real_pend < 0){
                    	$x001="("; $real_pendx=$real_pend*-1; $y001=")";}
                    else {
                    	$x001=""; $real_pendx=$real_pend; $y001="";}
                    $selisihpend = number_format($real_pendx,"2",",",".");
                    if ($jmlpendapatan==0){
                        $tmp001=1;
                    }else{
                        $tmp001=$jmlpendapatan;
                    }
					
                    $per001     = ($jmlangpendapatan!=0)?($jmlpendapatan / $jmlangpendapatan ) * 100:0; 
                    $persen001  = number_format($per001,"2",",",".");
					
        $sql51="SELECT SUM(anggaran) as angaran,SUM(real_spj)as nilai FROM data_realisasi_n$sumber_data($cbulan,$ag_tox,$thn_periode2) WHERE left(kd_rek5,1)='5' and kd_skpd='$kd_skpd' ";
                    $query51 = $this->db->query($sql51);
                    $jmlb = $query51->row();
                    $jmlangbelanja = $jmlb->angaran;
                    $jmlbelanja = $jmlb->nilai;
                    $jmlbelanja1= number_format($jmlb->nilai,"2",",",".");
                    $jmlangbelanja1= number_format($jmlb->angaran,"2",",",".");
					
					$real_belanja = $jmlangbelanja - $jmlbelanja;
                    if ($real_belanja < 0){
                    	$x002="("; $real_belanjax=$real_belanja*-1; $y002=")";}
                    else {
                    	$x002=""; $real_belanjax=$real_belanja; $y002="";}
                    $selisihbelanja = number_format($real_belanjax,"2",",",".");
                    if ($jmlbelanja==0){
                        $tmp002=1;
                    }else{
                        $tmp002=$jmlbelanja;
                    }
					
                    $per002     = ($jmlangbelanja!=0)?($jmlbelanja / $jmlangbelanja ) * 100:0; 
                    $persen002  = number_format($per002,"2",",",".");
					
        $sql523="SELECT SUM(anggaran) as anggaran,SUM(real_spj) as nilai FROM data_realisasi_n$sumber_data($cbulan,$ag_tox,$thn_periode2) WHERE left(kd_rek5,3)='523' and kd_skpd='$kd_skpd' ";
                    $query523 = $this->db->query($sql523);
                    $jmlbm = $query523->row();
                    $jmlbmbelanja = $jmlbm->nilai;
                    $jmlangbmbelanja = $jmlbm->anggaran;
                    $jmlbmbelanja1= number_format($jmlbmbelanja,"2",",",".");
                    $jmlangbmbelanja1= number_format($jmlangbmbelanja,"2",",",".");
        $sql61="SELECT SUM(anggaran) as anggaran,SUM(real_spj) as nilai FROM data_realisasi_n$sumber_data($cbulan,$ag_tox,$thn_periode2) WHERE left(kd_rek5,2)='61' and kd_skpd='$kd_skpd' ";
                    $query61 = $this->db->query($sql61);
                    $jmlpm = $query61->row();
                    $jmlpmasuk = $jmlpm->nilai;
                    $jmlangpmasuk = $jmlpm->anggaran;
        $sql62="SELECT SUM(anggaran) as anggaran,SUM(real_spj) as nilai FROM data_realisasi_n$sumber_data($cbulan,$ag_tox,$thn_periode2) WHERE left(kd_rek5,2)='62' and kd_skpd='$kd_skpd' ";
                    $query62 = $this->db->query($sql62);
                    $jmlpk = $query62->row();
                    $jmlpkeluar = $jmlpk->nilai;
                    $jmlangpkeluar = $jmlpk->anggaran;
        $surplus =  $jmlpendapatan- $jmlbelanja;
        $angsurplus = $jmlangpendapatan- $jmlangbelanja;        
        if ($surplus < 0){
                    	$x="("; $surplusx=$surplus*-1; $y=")";
                        }else {
                    	$x=""; $surplusx=$surplus; $y="";
                        }
        if ($angsurplus < 0){
                    	$e="("; $angsurplusx=$angsurplus*-1; $f=")";
                        }else {
                    	$e=""; $angsurplusx=$angsurplus; $f="";
                        }
        $surplus1= number_format($surplusx,"2",",",".");
        $angsurplus1= number_format($angsurplusx,"2",",",".");
		
		$real_surplus = $angsurplus - $surplus;
                    if ($real_surplus < 0){
                    	$x003="("; $real_surplusx=$real_surplus*-1; $y003=")";}
                    else {
                    	$x003=""; $real_surplusx=$real_surplus; $y003="";}
        $selisihsurplus = number_format($real_surplusx,"2",",",".");
                    if ($surplus==0){
                        $tmp003=1;
                    }else{
                        $tmp003=$surplus;
                    }
					
                    $per003     = ($angsurplus!=0)?($surplus / $angsurplus ) * 100:0; 
                    $persen003  = number_format($per003,"2",",",".");
		
        $biaya_net =  $jmlpmasuk- $jmlpkeluar;
        $angbiaya_net =  $jmlangpmasuk- $jmlangpkeluar;        
        if ($biaya_net < 0){
                    	$a="("; $biaya_netx=$biaya_net*-1; $b=")";
                        }else {
                    	$a=""; $biaya_netx=$biaya_net; $b="";
                        }
        if ($angbiaya_net < 0){
                    	$g="("; $angbiaya_netx=$angbiaya_net*-1; $h=")";
                        }else {
                    	$g=""; $angbiaya_netx=$angbiaya_net; $h="";
                        }
        $biaya_net1 =   number_format($biaya_netx,"2",",",".");
        $angbiaya_net1 =   number_format($angbiaya_netx,"2",",",".");
        $silpa= ($jmlpendapatan+$jmlpmasuk)-($jmlbelanja+$jmlpkeluar);
        $angsilpa= ($jmlangpendapatan+$jmlangpmasuk)-($jmlangbelanja+$jmlangpkeluar);                
        if ($silpa < 0){
                    	$c="("; $silpax=$silpa*-1; $d=")";
                        }else {
                    	$c=""; $silpax=$silpa; $d="";
                        }
        if ($angsilpa < 0){
                    	$i="("; $angsilpax=$angsilpa*-1; $j=")";
                        }else {
                    	$i=""; $angsilpax=$angsilpa; $j="";
                        }
        $silpa1 =number_format($silpax,"2",",","."); 
        $angsilpa1 =number_format($angsilpax,"2",",",".");
       $sqlsc="SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd' ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $nmskpd  = $rowsc->nm_skpd;
                } 
        
		$nm_skpd	= strtoupper ($nmskpd);
        $jk=$this->rka_model->combo_skpd();
       
        $cRet='';
	
       
      $cRet .="<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td rowspan=\"4\" align=\"right\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:16px\"><strong>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH</strong></tr>
					<tr><td colspan=\"3\" align=\"center\"><strong>Jalan Ahmad Yani Telepon (0561) 736541 Fax (0561) 738428</strong></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PONTIANAK</strong></td></tr>
					<tr><td colspan=\"4\" align=\"right\">Kode Pos: 78124 &nbsp; &nbsp;</td></tr>
					</table>
					<hr  valign=\"top\" color=\"black\" size=\"3px\" width=\"100%\"> 
					";
			
			$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                        <tr>
							<td rowspan=\"2\" align=\"right\" width=\"10%\" height=\"50\">&nbsp;</td>
							<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><b>Laporan Realisasi Anggaran Tahun Anggaran $thn_periode2</b></td>
						</tr>
						<tr>
							<td colspan=\"5\" align=\"center\" style=\"font-size:14px\"><b>Periode $tgl_periode1 $buln_prd1 - $tgl_periode2 $buln_prd2 $thn_periode2</b></td>
                        </tr>
						<tr>
						<td colspan=\"5\" align=\"justify\" style=\"font-size:12px\">
						<br>
						SKPD : $id - $nm_skpd
						<br>
						<br>
						
						</td>
                        </tr>
                    </table>
					";
        
       $cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
							<td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
                            <td colspan=\"2\" bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA TA. $thn_ang</b></td>
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"30%\" align=\"center\"><b>KETERANGAN</b></td>
                        </tr>
						<tr>
							<td bgcolor=\"#CCCCCC\" width=\"35%\" align=\"center\"><b>Uraian</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Nilai</b></td>     
						</tr>
                        
                     </thead>
                                        
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\">&nbsp;</td>
                        </tr>";
               
                    $sql4="SELECT a.seq,a.nor,a.uraian,isnull(a.kode_1,'-') as kode_1,isnull(a.kode_2,'-') as kode_2,isnull(a.kode_3,'-') as kode_3,thn_m1 AS lalu FROM map_lra_skpd a 
				   GROUP BY a.seq,a.nor,a.uraian,isnull(a.kode_1,'-'),isnull(a.kode_2,'-'),isnull(a.kode_3,'-'),thn_m1 ORDER BY a.seq";
                // isnull(a.kode_1,\"'-'\")
                $query4 = $this->db->query($sql4);
                $no     = 0;                                  
               
                foreach ($query4->result() as $row4)
                {
                    
                    $nama      = $row4->uraian;   
                    $real_lalu = number_format($row4->lalu,"2",",",".");
                    $n         = $row4->kode_1;
					$n		   = ($n=="-"?"'-'":$n);
					$n2         = $row4->kode_2;
					$n2		   = ($n2=="-"?"'-'":$n2);
					$n3         = $row4->kode_3;
					$n3		   = ($n3=="-"?"'-'":$n3);

//                    $sql5   = "SELECT SUM(b.anggaran) as anggaran,SUM(b.real_spj) as nilai FROM data_realisasi( $bulan,$ag_tox) b LEFT JOIN ms_rek5 c ON b.kd_rek5=c.kd_rek5 WHERE c.map_lra1 IN (".$n.") ";
                     $sql5   = "SELECT SUM(b.anggaran) as anggaran,SUM(b.real_spj) as nilai FROM data_realisasi_n$sumber_data($cbulan,$ag_tox,$thn_periode2) b WHERE kd_skpd='$kd_skpd' and (left(b.kd_rek5,3) in ($n) or left(b.kd_rek5,5) in ($n2) or left(b.kd_rek5,7) in ($n3))";
//					                    $sql5   = "SELECT SUM(b.anggaran) as anggaran,SUM(b.real_spj) as nilai FROM realisasi b LEFT JOIN ms_rek5 c ON b.kd_rek5=c.kd_rek5 WHERE c.map_lra1 IN (\"'.$n.'\") ";
                    $query5 = $this->db->query($sql5);
                    $trh    = $query5->row();
                    $nil    = $trh->nilai;
                    $angnil = $trh->anggaran;
                    
                    $real_s = $trh->anggaran - $trh->nilai;
                    if ($real_s < 0){
                    	$x1="("; $real_sx=$real_s*-1; $y1=")";}
                    else {
                    	$x1=""; $real_sx=$real_s; $y1="";}
                    $selisih = number_format($real_sx,"2",",",".");
                    if ($trh->nilai==0){
                        $tmp=1;
                    }else{
                        $tmp=$trh->nilai;
                    }
                    $nilai    = number_format($trh->nilai,"2",",",".");
                    $angnilai = number_format($trh->anggaran,"2",",",".");
                    $per1     = ($angnil!=0)?($nil / $angnil ) * 100:0; 
                    $persen1  = number_format($per1,"2",",",".");
                    $no       = $no + 1;
                    switch ($row4->seq) {
                    case 5:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 10:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;					
					case 35:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 36:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;	
                    case 40:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlpendapatan1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 45:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
                    case 50:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td	 style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
					case 55:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;
					case 70:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;						
					case 75:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;
					case 80:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;
					case 110:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                   
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     
                                 </tr>";
                        break;						
					case 115:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                               
                                 </tr>";
                        break;	
                    case 120:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlbelanja1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 125:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;	
                    case 130:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                    
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$x$surplus1$y</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                    
                                 </tr>";
                        break;
                   
                    default:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                  
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                   
                                 </tr>";
                }
                } 
        $cRet .=       " </table>";
		
        $data['prev']= $cRet;
        $data['sikap'] = 'preview';
        //$judul  = ("LRA UNIT $kd_skpd / $cbulan");
        $judul  = "LRA";
        $this->template->set('title', 'LRA UNIT $kd_skpd / $cbulan');        
        switch($pilih) {
		case 0;
			$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');	
        break;
        case 1;
			echo ("<title>LRA UNIT $cbulan</title>");
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
    
	function cetak_ba_lpe($kd_skpd="", $cetak = ''){
		$pilih = $cetak;
        $id     = $kd_skpd;
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1= $thn_ang-1;  
                  
        $skpd = "AND kd_skpd='$id'";
        $skpd1 = "AND b.kd_skpd='$id'"; 
	   
       $y123=")";
	   $x123="(";

		$tgl_ttd = $this->uri->segment(9);
		$periode1 = $this->uri->segment(13);
		$periode2 = $this->uri->segment(14);
		
		$arrayperiode1=explode("-",$periode1);		
		$arrayperiode2=explode("-",$periode2);		
		$arraytgl=explode("-",$tgl_ttd);			
		
		if($arrayperiode2[1]=='3'){
			$tw = "I";
		}if($arrayperiode2[1]=='6'){
			$tw = "II";
		}if($arrayperiode2[1]=='9'){
			$tw = "III";
		}else{
			$tw = "IV";
		}
		
		$tgl   = $arraytgl[2];
		$bln   = $arraytgl[1];
		$thn   = $arraytgl[0];
		
		$tgl_periode1 = $arrayperiode1[2];
		$bln_periode1 = $arrayperiode1[1];
		$thn_periode1 = $arrayperiode1[0];
		
		$tgl_periode2 = $arrayperiode2[2];
		$bln_periode2 = $arrayperiode2[1];
		$thn_periode2 = $arrayperiode2[0];
		
		$tanggl_ttd = $this->tukd_model->terbilang2($tgl);
		$buln_ttd  = $this->getBulan($bln);
		
		$tanggl_prd1 = $this->tukd_model->terbilang2($tgl_periode1);
		$buln_prd1  = $this->getBulan($bln_periode1);
		
		$tanggl_prd2 = $this->tukd_model->terbilang2($tgl_periode2);
		$buln_prd2  = $this->getBulan($bln_periode2); 
		
		$cbulan=$bln_periode2;
		$bulan =$bln_periode2; 
		if($cbulan==12){
		$sumber_jurnal= "ju_calk";
		}else{
		$sumber_jurnal= "ju";
		}	

		$day = date('D', strtotime($tgl_ttd));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
		
		$nmskpd = $this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd');
        $nm_skpd =strtoupper($nmskpd);
        
	$kabid    = str_replace('%20',' ',$this->uri->segment(5));
	$stafak   = str_replace('%20',' ',$this->uri->segment(6));
	$kasub    = str_replace('%20',' ',$this->uri->segment(7));
	$ppk      = str_replace('%20',' ',$this->uri->segment(8));
	$penyusun = str_replace('%20',' ',$this->uri->segment(10));
	$terima   = str_replace('%20',' ',$this->uri->segment(11));
	$keluar   = str_replace('%20',' ',$this->uri->segment(12));
	
	$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='3.13.01.17'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
	
	$sqlkabid="select TOP 1 * from ms_ttd where nip='$kabid'";
                 $sqlkabid1=$this->db->query($sqlkabid);
                 foreach ($sqlkabid1->result() as $rowkabid)
                {
                    $nipkabid      = $rowkabid->nip;
                    $namakabid     = $rowkabid->nama;
                    $jabatankabid  = $rowkabid->jabatan;
                    $pangkatkabid  = $rowkabid->pangkat;
                   
                }
				
	$sqlstafak="select TOP 1 * from ms_ttd where nip='$stafak'";
                 $sqlstafak1=$this->db->query($sqlstafak);
                 foreach ($sqlstafak1->result() as $rowstafak)
                {
                    $nipstafak      = $rowstafak->nip;
                    $namastafak     = $rowstafak->nama;
                    $jabatanstafak  = $rowstafak->jabatan;
                    $pangkatstafak  = $rowstafak->pangkat;
                   
                }
	
	$sqlkasub="select TOP 1 * from ms_ttd where nip='$kasub'";
                 $sqlkasub1=$this->db->query($sqlkasub);
                 foreach ($sqlkasub1->result() as $rowkasub)
                {
                    $nipkasub      = $rowkasub->nip;
                    $namakasub     = $rowkasub->nama;
                    $jabatankasub  = $rowkasub->jabatan;
                    $pangkatkasub  = $rowkasub->pangkat;
                   
                }
	
	$sqlppk="select TOP 1 * from ms_ttd where nip='$ppk'";
                 $sqlppk1=$this->db->query($sqlppk);
                 foreach ($sqlppk1->result() as $rowppk)
                {
                    $nipppk      = $rowppk->nip;
                    $namappk     = $rowppk->nama;
                    $jabatanppk  = $rowppk->jabatan;
                    $pangkatppk  = $rowppk->pangkat;
                   
                }
				
	$sqlpenyusun="select TOP 1 * from ms_ttd where nip='$penyusun'";
                 $sqlpenyusun1=$this->db->query($sqlpenyusun);
                 foreach ($sqlpenyusun1->result() as $rowpenyusun)
                {
                    $nippenyusun      = $rowpenyusun->nip;
                    $namapenyusun     = $rowpenyusun->nama;
                    $jabatanpenyusun  = $rowpenyusun->jabatan;
                    $pangkatpenyusun  = $rowpenyusun->pangkat;
                   
                }
	
	$sqlterima="select TOP 1 * from ms_ttd where nip='$terima'";
                 $sqlterima1=$this->db->query($sqlterima);
                 foreach ($sqlterima1->result() as $rowterima)
                {
                    $nipterima      = $rowterima->nip;
                    $namaterima     = $rowterima->nama;
                    $jabatanterima  = $rowterima->jabatan;
                    $pangkatterima  = $rowterima->pangkat;
                   
                }
	
	$sqlkeluar="select TOP 1 * from ms_ttd where nip='$keluar'";
                 $sqlkeluar1=$this->db->query($sqlkeluar);
                 foreach ($sqlkeluar1->result() as $rowkeluar)
                {
                    $nipkeluar      = $rowkeluar->nip;
                    $namakeluar     = $rowkeluar->nama;
                    $jabatankeluar  = $rowkeluar->jabatan;
                    $pangkatkeluar  = $rowkeluar->pangkat;
                   
                }
      
			$skpd = "AND kd_skpd='$id'";
        $skpd1 = "AND b.kd_skpd='$id'"; 

// UPDATE LPE TAHUN LALU
    		        
			$sqllo10="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek5,1) in ('8') and kd_skpd='$kd_skpd'";
                    $querylo10= $this->db->query($sqllo10);
                    $pen8 = $querylo10->row();
                    $pen_lalu8 = $pen8->nilai;
                    $pen_lalu81= number_format($pen8->nilai,"2",",",".");
		
			$sqllo12="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek5,1) in ('9') and kd_skpd='$kd_skpd'";
                    $querylo12= $this->db->query($sqllo12);
                    $bel10 = $querylo12->row();
                    $bel_lalu10 = $bel10->nilai;
                    $bel_lalu101= number_format($bel10->nilai,"2",",",".");

			$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'";//aba
                            
                    $hasil = $this->db->query($sql_lalu); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row001)
                    {
                       $kd_rek   =$row001->nor ;
                       $parent   =$row001->parent;
                       $nama     =$row001->uraian;
                       $lpe_ll1  =$row001->thn_m1;
					}
				        
			$sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllpe_lalu1); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row002)
                    {
                       $kd_rek   =$row002->nor ;
                       $parent   =$row002->parent;
                       $nama     =$row002->uraian;
                       $lpe_ll2  =$row002->thn_m1;
					}					

			$sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllpe_lalu2); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row003)
                    {
                       $kd_rek   =$row003->nor ;
                       $parent   =$row003->parent;
                       $nama     =$row003->uraian;
                       $lpe_ll3  =$row003->thn_m1;
					}
					
			
			$query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM trdju a INNER JOIN trhju b 
			ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek5='3110101' AND YEAR(b.tgl_voucher)<'$thn_ang'
			and b.tabel=1 and reev=0 and kd_skpd='$kd_skpd'");  
	        foreach($query3->result_array() as $res2){
				 $debet=$res2['debet'];
				 $kredit=$res2['kredit'];
                 				 
			 }
			 
		$real=$kredit-$debet+$pen_lalu8-$bel_lalu10+$lpe_ll1+$lpe_ll2+$lpe_ll3;

//		created by henri_tb
		$sqllo9="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek5,1) in ('8') and kd_skpd='$kd_skpd'";
                    $querylo9= $this->db->query($sqllo9);
                    $penlo7 = $querylo9->row();
                    $pen_lo7 = $penlo7->nilai;
                    $pen_lo71= number_format($penlo7->nilai,"2",",",".");
        
		$sqllo10="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('8') and kd_skpd='$kd_skpd'";
                    $querylo10= $this->db->query($sqllo10);
                    $penlo8 = $querylo10->row();
                    $pen_lo_lalu8 = $penlo8->nilai;
                    $pen_lo_lalu81= number_format($penlo8->nilai,"2",",",".");
		
		$sqllo11="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek5,1) in ('9') and kd_skpd='$kd_skpd'";
                    $querylo11= $this->db->query($sqllo11);
                    $bello9 = $querylo11->row();
                    $bel_lo9 = $bello9->nilai;
                    $bel_lo91= number_format($bello9->nilai,"2",",",".");
		
		$sqllo12="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('9') and kd_skpd='$kd_skpd'";
                    $querylo12= $this->db->query($sqllo12);
                    $bello10 = $querylo12->row();
                    $bel_lo_lalu10 = $bello10->nilai;
                    $bel_lo_lalu101= number_format($bello10->nilai,"2",",",".");		

					$surplus_lo3 = $pen_lo7 - $bel_lo9;
                    
					$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

					$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
                    

			$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'";//aba
                            
                    $hasil = $this->db->query($sql_lalu); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row001)
                    {
                       $kd_rek   =$row001->nor ;
                       $parent   =$row001->parent;
                       $nama     =$row001->uraian;
                       $lpe_lalu1  =$row001->thn_m1;
					}
				        
			$sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllpe_lalu1); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row002)
                    {
                       $kd_rek   =$row002->nor ;
                       $parent   =$row002->parent;
                       $nama     =$row002->uraian;
                       $lpe_lalu2  =$row002->thn_m1;
					}					

			$sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllpe_lalu2); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row003)
                    {
                       $kd_rek   =$row003->nor ;
                       $parent   =$row003->parent;
                       $nama     =$row003->uraian;
                       $lpe_lalu3  =$row003->thn_m1;
					}

		$sal_awal	= $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;
				
            $sql = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'";//aba
                            
                    $hasil = $this->db->query($sql); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row001)
                    {
                       $kd_rek   =$row001->nor ;
                       $parent   =$row001->parent;
                       $nama     =$row001->uraian;
                       $nilaiDR  =$row001->thn_m1;
					}
				        
			$sqllpe1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllpe1); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row002)
                    {
                       $kd_rek   =$row002->nor ;
                       $parent   =$row002->parent;
                       $nama     =$row002->uraian;
                       $nilailpe1  =$row002->thn_m1;
					}					

			$sqllpe2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllpe2); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row003)
                    {
                       $kd_rek   =$row003->nor ;
                       $parent   =$row003->parent;
                       $nama     =$row003->uraian;
                       $nilailpe2  =$row003->thn_m1;
					}
		
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
        $sal_akhir=$sal_awal+$surplus_lo3+$nilaiDR+$nilailpe1+$nilailpe2;
					
					if ($surplus_lo_lalu3 < 0){
                    	$lo15="("; $surplus_lo_lalu3=$surplus_lo_lalu3*-1; $lo16=")";}
                    else {
                    	$lo15=""; $lo16="";}						
                    $surplus_lo_lalu31 = number_format($surplus_lo_lalu3,"2",",",".");
					
					if ($selisih_surplus_lo3 < 0){
                    	$lo17="("; $selisih_surplus_lo3=$selisih_surplus_lo3*-1; $lo18=")";}
                    else {
                    	$lo17=""; $lo18="";}
                    $selisih_surplus_lo31 = number_format($selisih_surplus_lo3,"2",",",".");
					
					if ($lpe_lalu1 < 0){
                    	$lalu1="("; $lpe_lalu1=$lpe_lalu1*-1; $lalu2=")";}
                    else {
                    	$lalu1=""; $lpe_lalu1; $lalu2="";}				

					if ($lpe_lalu2 < 0){
                    	$lalu3="("; $lpe_lalu2=$lpe_lalu2*-1; $lalu4=")";}
                    else {
                    	$lalu3=""; $lpe_lalu2; $lalu4="";}

					if ($lpe_lalu3 < 0){
                    	$lalu5="("; $lpe_lalu3=$lpe_lalu3*-1; $lalu6=")";}
                    else {
                    	$lalu5=""; $lpe_lalu3; $lalu6="";}
						
					if ($nilaiDR < 0){
                    	$l000="("; $nilaiDR=$nilaiDR*-1; $p000=")";}
                    else {
                    	$l000=""; $nilaiDR; $p000="";}		

					if ($nilailpe1 < 0){
                    	$l001="("; $nilailpe1=$nilailpe1*-1; $p001=")";}
                    else {
                    	$l001=""; $nilailpe1; $p001="";}

					if ($nilailpe2 < 0){
                    	$l002="("; $nilailpe2=$nilailpe2*-1; $p002=")";}
                    else {
                    	$l002=""; $nilailpe2; $p002="";}

                    if ($surplus_lo3 < 0){
                    	$lo13="("; $surplus_lo3=$surplus_lo3*-1; $lo14=")";}
                    else {
                    	$lo13=""; $lo14="";}		
                    $surplus_lo31 = number_format($surplus_lo3,"2",",",".");
					
					if ($sal_akhir < 0)
					{
						$c = "("; $sal_akhir = $sal_akhir * -1; $d = ")";
					} else
					{
						$c = ""; $sal_akhir; $d = "";
					}

					if ($sal_awal < 0)
					{
						$c1 = "("; $sal_awal = $sal_awal * -1; $d1 = ")";
					} else
					{
						$c1 = ""; $sal_awal; $d1 = "";
					}

					if ($real < 0)
					{
						$cx = "("; $real = $real * -1; $dx = ")";
					} else
					{
						$cx = ""; $real; $dx = "";
					}

	 $modtahun= $thn_ang%4;
	 
	 if ($modtahun = 0){
        $nilaibulan=".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";}
            else {
        $nilaibulan=".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";}
	 
	 $arraybulan=explode(".",$nilaibulan);

    	
    
         $cRet = '';
        $cRet .="<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td rowspan=\"4\" align=\"right\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:16px\"><strong>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH</strong></tr>
					<tr><td colspan=\"3\" align=\"center\"><strong>Jalan Ahmad Yani Telepon (0561) 736541 Fax (0561) 738428</strong></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PONTIANAK</strong></td></tr>
					<tr><td colspan=\"4\" align=\"right\">Kode Pos: 78124 &nbsp; &nbsp;</td></tr>
					</table>
					<hr  valign=\"top\" color=\"black\" size=\"3px\" width=\"100%\"> 
					";
			
			
			$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                        <tr>
							<td rowspan=\"2\" align=\"right\" width=\"10%\" height=\"50\">&nbsp;</td>
							<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><b>Laporan Perubahan Ekuitas Tahun Anggaran $thn_periode2</b></td>
						</tr>
						<tr>
							<td colspan=\"5\" align=\"center\" style=\"font-size:14px\"><b>Periode $tgl_periode1 $buln_prd1 - $tgl_periode2 $buln_prd2 $thn_periode2</b></td>
                        </tr>
						<tr>
						<td colspan=\"5\" align=\"justify\" style=\"font-size:12px\">
						<br>
						Pada hari ini ".$dayList[$day]." Tanggal $tanggl_ttd Bulan $buln_ttd  Tahun $thn. $nm_skpd telah melaksanakan rekonsiliasi dengan 
						Bidang Akuntansi Badan Pengelolaan Keuangan dan Pendapatan Daerah Provinsi Kalimantan Barat 
						<br>
						<br>
						
						</td>
                        </tr>
						<tr>
						<td colspan=\"5\" align=\"justify\" style=\"font-size:12px\">
						<br>
						SKPD : $id - $nm_skpd
						<br>
						<br>
						
						</td>
                        </tr>
                    </table>
					";
        
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
							<td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
                            <td colspan=\"2\" bgcolor=\"#CCCCCC\" width=\"60%\" align=\"center\"><b>LAPORAN PERUBAHAN EKUITAS TA. $thn_ang</b></td>
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"30%\" align=\"center\"><b>KETERANGAN</b></td>
                        </tr>
						<tr>
							<td bgcolor=\"#CCCCCC\" width=\"35%\" align=\"center\"><b>Uraian</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Nilai</b></td>     
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
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\">&nbsp;</td>
                        </tr>";
                
                     $sql = "SELECT * FROM map_lpe_skpd  ORDER BY seq";
                            
                    $hasil = $this->db->query($sql); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row)
                    {
                      
                       $kd_rek   =$row->nor ;
                       $parent   =$row->parent;
                       $nama     =$row->uraian;
                       $nilai_1    =$row->thn_m1;
                       
					   
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
                               switch ($kd_rek)
                                {
                                    case 1:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> $c1".number_format($sal_awal,"2",",",".")."$d1</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"></td>
                                                     </tr>";
                                            
                                    break; 
                                    case 2:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> $lo13".number_format($surplus_lo3,"2",",",".")."$lo14</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"></td>
                                                     </tr>";
                                            
                                    break; 
                                     case 3:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"></td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"></td>
                                                     </tr>";
                                            
                                    break; 
                                    case 4: 
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$l001".number_format($nilailpe1,"2",",",".")."$p001</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"></td>
                                                     </tr>";
									break;
                                    case 5:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$l000".number_format($nilaiDR,"2",",",".")."$p000</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"></td>
                                                     </tr>";
									break;
                                    case 6:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$l002".number_format($nilailpe2,"2",",",".")."$p002</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"></td>
                                                     </tr>";
									break;
                                    case 7:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$c".number_format($sal_akhir,"2",",",".")."$d</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"></td>
                                                     </tr>";
                                } 
                       
                    }                           

        $cRet .='</table>';
        
		 
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         //$judul = ("LPE UNIT $cbulan");
         $judul = "LPE";
        $this->template->set('title', 'LPE UNIT $cbulan');  
         switch ($cetak)
        {
            case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
            case 1;
				echo "<title>LPE UNIT $cbulan</title>";
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
   
	function cetak_ringkasan_lpe($kd_skpd="", $cetak = ''){
		$cbulan = 12;
		$id = $kd_skpd;
        $thn_ang = $this->session->userdata('pcThang');
		$bulan	 = $cbulan;
        $id1     = $this->session->userdata('kdskpd');
        $nmskpd = $this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd');
        $nm_skpd =strtoupper($nmskpd);
        $thn_ang_1= $thn_ang-1;   
        
		$tgl_ttd = $this->uri->segment(9);
		$periode1 = $this->uri->segment(13);
		$periode2 = $this->uri->segment(14);
		
		$arrayperiode1=explode("-",$periode1);
		$arrayperiode2=explode("-",$periode2);		
		
		if($arrayperiode2[1]<='3'){
			$tw = "I";
		}else if($arrayperiode2[1]<='6'){
			$tw = "II";
		}else if($arrayperiode2[1]<='9'){
			$tw = "III";
		}else{
			$tw = "IV";
		}
		
		$kabid    = str_replace('%20',' ',$this->uri->segment(7));
		
		$sqlkabid="select TOP 1 * from ms_ttd where nip='$kabid'";
                 $sqlkabid1=$this->db->query($sqlkabid);
                 foreach ($sqlkabid1->result() as $rowkabid)
                {
                    $nipkabid      = $rowkabid->nip;
                    $namakabid     = $rowkabid->nama;
                    $jabatankabid  = $rowkabid->jabatan;
                    $pangkatkabid  = $rowkabid->pangkat;
                   
                }
		
		$tgl   = substr($tgl_ttd,7,2);
		$bln   = substr($tgl_ttd,5,2);
		$thn   = substr($tgl_ttd,0,4);
		
		
		//$bln2 = substr($periode2,5,2);
		$bln2 = $arrayperiode2[1];
		
		$tanggl = $this->tukd_model->terbilang2($tgl);
		$buln  = $this->getBulan($bln);
		$tahun = $this->tukd_model->terbilang2($thn); 
		
		$tgl_periode1 = substr($periode1,7,2);
		$bln_periode1 = substr($periode1,5,1);
		$thn_periode1 = substr($periode1,0,4);
		
		$len = strlen($periode2);
		
		if($len<>'10'){
			$tgl_periode2 = substr($periode2,7,2);
			$bln_periode2 = substr($periode2,5,1);
		}else{
			$tgl_periode2 = substr($periode2,8,2);
			$bln_periode2 = substr($periode2,5,2);
		}
		
		$tanggl_prd1 = $this->tukd_model->terbilang2($tgl_periode1);
		$buln_prd1   = $this->getBulan($bln_periode1);
		
		$tanggl_prd2 = $this->tukd_model->terbilang2($tgl_periode2);
		$buln_prd2   = $this->getBulan($bln_periode2);
		
		

		$day = date('D', strtotime($tgl_ttd));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
	

	
	$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='3.13.01.17'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
				
	$sqlsc="SELECT nm_skpd FROM ms_skpd where kd_skpd='3.13.01.01'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $nm_ppkd     = $rowsc->nm_skpd;
                }
	
			    
					$cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td rowspan=\"4\" align=\"right\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:16px\"><strong>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH</strong></tr>
					<tr><td colspan=\"3\" align=\"center\"><strong>Jalan Ahmad Yani Telepon (0561) 736541 Fax (0561) 738428</strong></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PONTIANAK</strong></td></tr>
					<tr><td colspan=\"4\" align=\"right\">Kode Pos: 78124 &nbsp; &nbsp;</td></tr>
					</table>
					<hr  valign=\"top\" color=\"black\" size=\"3px\" width=\"100%\"> 
					";
			
			$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                        <tr>
							<td rowspan=\"2\" align=\"right\" width=\"10%\" height=\"50\">&nbsp;</td>
							<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><b><u> BERITA ACARA REKONSILIASI</u></b></td>
						</tr>
						<tr>
							<td colspan=\"5\" align=\"center\" style=\"font-size:14px\"><b>Nomor : 900/&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;/BPKPD-D/$thn_ang</b></td>
                        </tr>
                    </table>
					<br>
					<br>
					";
					
					$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:14px;\" width=\"100%\" align=\"left\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
						<tr>
							<td colspan=\"3\">Kami yang bertandatangan di bawah ini :</td>
                        </tr>
						<tr>
							<td align=\"left\" width=\"10%\">Nama</td>
							<td align=\"center\" width=\"3%\">:</td>
							<td align=\"left\" width=\"82%\">$namakabid</td>
                        </tr>
						<tr>
							<td align=\"left\">NIP</td>
							<td align=\"center\">:</td>
							<td align=\"left\">$nipkabid</td>
                        </tr>
						<tr>
							<td align=\"left\">Jabatan</td>
							<td align=\"center\">:</td>
							<td align=\"left\">$jabatankabid</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">Dalam Hal ini bertindak selaku perwakilan Bidang Akuntansi dan Pelaporan $nm_ppkd, selanjutnya di sebut sebagai Pihak Pertama.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td>Nama</td>
							<td align=\"center\">:</td>
							<td>.........................................................</td>
                        </tr>
						<tr>
							<td>NIP</td>
							<td align=\"center\">:</td>
							<td>.........................................................</td>
                        </tr>
						<tr>
							<td>Jabatan</td>
							<td align=\"center\">:</td>
							<td>Pejabat Penatausahaan Keuangan</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">Dalam Hal ini bertindak selaku perwakilan Unit Kerja $nmskpd, selanjutnya di sebut sebagai Pihak Kedua.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pihak Pertama dan Pihak Kedua Bersama-sama melakukan rekonsilisasi Laporan Keuangan Periode $tgl_periode1 $buln_prd1 $thn_periode1 sampai dengan $tgl_periode2 $buln_prd2 $thn_periode1. Rekonsiliasi ini dimaksud dilakukan dengan cara membandingkan :</td>
                        </tr>
						<tr>
							<td valign=\"top\">1. </td>
							<td align=\"justify\" colspan=\"2\">Data yang dimiliki Pejabat Penatausahaan Keuangan pada Unit Kerja berupa Laporan Keuangan yang terdiri dari Laporan Operasional, Neraca, Laporan Perubahan Ekuitas dan Laporan Barang Milik Daerah; dengan</td>
                        </tr>
						<tr>
							<td valign=\"top\">2. </td>
							<td align=\"justify\" colspan=\"2\">Data yang dimiliki Bidang Akuntansi dan Pelaporan berupa Laporan Keuangan.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Selanjutnya, hasil rekonsilisasi dituangkan dalam Lampiran Berita Acara Rekonsiliasi (BAR) yang merupakan bagian yang tidak terpisahkan dari BAR ini.</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;</td>
                        </tr>
						<tr>
							<td colspan=\"3\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Demikian berita acara ini dibuat dengan sadar dan tanpa paksaan.</td>
                        </tr>
                    </table>
					";
					
					$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
						<tr >
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">$daerah, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">Pihak Pertma,</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">Pihak Kedua,</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\"width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">&nbsp;</td>
                        </tr>
						<tr>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">$namakabid</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"15%\">&nbsp;</td>
							<td align=\"center\" style=\"font-size:12px\" width=\"35%\">................................................</td>
                        </tr>";
     
        $cRet .='</table>';
        		  
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = "LPE";
        $this->template->set('title', 'RINGKASAN LPE $cbulan');  
         switch($cetak) {
		case 4;
			$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');	
        break;
        case 3;
			echo ("<title>LPE UNIT $cbulan</title>");
			 echo $cRet;
        break;
        case 6;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 5;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-word");
            header("Content-Disposition: attachment; filename= $judul.doc");
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        } 

    }
	
	
	function cetak_ba_pengeluaran($kd_skpd="", $cetak = ''){
		$cbulan = 12;
		$id = $kd_skpd;
        $thn_ang = $this->session->userdata('pcThang');
		$bulan	 = $cbulan;
        $id1     = $this->session->userdata('kdskpd');
        $nmskpd = $this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd');
        $nm_skpd =strtoupper($nmskpd);
        $thn_ang_1= $thn_ang-1;   
        
		$tgl_ttd = $this->uri->segment(9);
		$periode1 = $this->uri->segment(13);
		$periode2 = $this->uri->segment(14);
		
		$arrayperiode1=explode("-",$periode1);
		$arrayperiode2=explode("-",$periode2);		
		
		if($arrayperiode2[1]<='3'){
			$tw = "I";
		}else if($arrayperiode2[1]<='6'){
			$tw = "II";
		}else if($arrayperiode2[1]<='9'){
			$tw = "III";
		}else{
			$tw = "IV";
		}
		
		$tgl   = substr($tgl_ttd,7,2);
		$bln   = substr($tgl_ttd,5,2);
		$thn   = substr($tgl_ttd,0,4);
		
		
		//$bln2 = substr($periode2,5,2);
		$bln2 = $arrayperiode2[1];
		
		$tanggl = $this->tukd_model->terbilang2($tgl);
		$buln  = $this->getBulan($bln);
		$tahun = $this->tukd_model->terbilang2($thn); 
		
		$tgl_periode1 = substr($periode1,7,2);
		$bln_periode1 = substr($periode1,5,1);
		$thn_periode1 = substr($periode1,0,4);
		
		$len = strlen($periode2);
		
		if($len<>'10'){
			$tgl_periode2 = substr($periode2,7,2);
			$bln_periode2 = substr($periode2,5,1);
		}else{
			$tgl_periode2 = substr($periode2,8,2);
			$bln_periode2 = substr($periode2,5,2);
		}
		
		$tanggl_prd1 = $this->tukd_model->terbilang2($tgl_periode1);
		$buln_prd1   = $this->getBulan($bln_periode1);
		
		$tanggl_prd2 = $this->tukd_model->terbilang2($tgl_periode2);
		$buln_prd2   = $this->getBulan($bln_periode2);
		
		

		$day = date('D', strtotime($tgl_ttd));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
	
	
	$this->db->query("exec recall '$kd_skpd'");
	
	$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='3.13.01.17'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
	
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td rowspan=\"4\" align=\"right\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:16px\"><strong>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH</strong></tr>
					<tr><td colspan=\"3\" align=\"center\"><strong>Jalan Ahmad Yani Telepon (0561) 736541 Fax (0561) 738428</strong></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PONTIANAK</strong></td></tr>
					<tr><td colspan=\"4\" align=\"right\">Kode Pos: 78124 &nbsp; &nbsp;</td></tr>
					</table>
					<hr  valign=\"top\" color=\"black\" size=\"3px\" width=\"100%\"> 
					";
			
		$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                        <tr>
							<td rowspan=\"2\" align=\"right\" width=\"10%\" height=\"50\">&nbsp;</td>
							<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><b>Realisasi Pengeluaran Tahun Anggaran $thn_ang</b></td>
						</tr>
						<tr>
							<td colspan=\"5\" align=\"center\" style=\"font-size:14px\"><b>Periode $tgl_periode1 $buln_prd1 - $tgl_periode2 $buln_prd2 $thn_periode1</b></td>
                        </tr>
						<tr>
						<td colspan=\"5\" align=\"justify\" style=\"font-size:12px\">
						<br>
						SKPD : $id - $nm_skpd
						<br>
						<br>
						
						</td>
                        </tr>
                    </table>";
        
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
							<td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
							<td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"30%\" align=\"center\"><b>URAIAN</b></td>
                            <td colspan=\"2\" bgcolor=\"#CCCCCC\" width=\"30%\" align=\"center\"><b>REALISASI TRIWULAN $tw TA $thn_ang</b></td>
							<!--<td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>SISA LEBIH/(KURANG)</b></td>-->
							<td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KETERANGAN</b></td>
                        </tr>
						<tr>
							<td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>Akuntansi</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>SKPD</b></td>     
						</tr>
                     </thead>
                                       
                     <tr>
							<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" >&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" >&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" >&nbsp;</td>
                            <!--<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" >&nbsp;</td>-->
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" >&nbsp;</td>
                     </tr>";
              
		if($kd_skpd=='3.13.01.17'){
			$kon = "AND LEFT(kd_rek5,1)='5'";
			
			  $real_spj = "sum(gj_ll)+sum(gj_ini)+sum(brg_ll)+sum(brg_ini)+sum(up_ll)+sum(up_ini) as nilai from(
							SELECT a.kd_skpd
							,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ini
							,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ll
							,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ini
							,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ll
							,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (6) THEN a.nilai ELSE 0 END) AS brg_ini
							,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (6) THEN a.nilai ELSE 0 END) AS brg_ll
							from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
							WHERE a.kd_skpd='$kd_skpd' GROUP BY a.kd_skpd
							UNION ALL
							SELECT b.kd_skpd, 0 as up_ini
							,SUM(CASE WHEN MONTH(b.TGL_BUKTI)<=$bln2 and b.pengurang_belanja=1 THEN a.nilai*-1 ELSE 0 END) AS up_ll
							, 0 as gj_ini, 0 as gj_ll, 0 as brg_ini, 0 as brg_ll
							FROM trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
							WHERE b.kd_skpd='$kd_skpd'
							GROUP BY b.kd_skpd
							UNION ALL
							SELECT a.kd_skpd, 0 up_ini, 0 up_ll
							,SUM(CASE WHEN MONTH(b.tgl_sts)=$bln2 and b.jns_trans=5 and b.jns_cp in (1) THEN a.rupiah*-1 ELSE 0 END) AS gj_ini
							,SUM(CASE WHEN MONTH(b.tgl_sts)<$bln2 and b.jns_trans=5 and b.jns_cp in (1) THEN a.rupiah*-1 ELSE 0 END) AS gj_ll
							,SUM(CASE WHEN MONTH(b.tgl_sts)=$bln2 and b.jns_trans=5 and b.jns_cp in (2) THEN a.rupiah*-1 ELSE 0 END) AS brg_ini
							,SUM(CASE WHEN MONTH(b.tgl_sts)<$bln2 and b.jns_trans=5 and b.jns_cp in (2) THEN a.rupiah*-1 ELSE 0 END) AS brg_ll
							from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
							WHERE b.kd_skpd='$kd_skpd' and right(a.kd_kegiatan,5)<>'00.04'
							GROUP BY a.kd_skpd
							UNION ALL
							SELECT a.kd_skpd
							,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ini
							,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ll
							,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ini
							,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ll
							,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (6,7) THEN a.nilai ELSE 0 END) AS brg_ini
							,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (6,7) THEN a.nilai ELSE 0 END) AS brg_ll
							from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
							WHERE a.kd_skpd='$kd_skpd' GROUP BY a.kd_skpd)b GROUP BY kd_skpd ";
							
			$real_pend_sp2d = "sum(gj_ll)+sum(gj_ini)+sum(brg_ll)+sum(brg_ini)+sum(up_ll)+sum(up_ini) as nilai from(
							   SELECT a.kd_skpd
								,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ini
								,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ll
								,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ini
								,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ll
								,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (6) THEN a.nilai ELSE 0 END) AS brg_ini
								,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (6) THEN a.nilai ELSE 0 END) AS brg_ll
								from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
								WHERE a.kd_skpd='$kd_skpd' GROUP BY a.kd_skpd
								UNION ALL
								SELECT b.kd_skpd, 0 as up_ini
								,SUM(CASE WHEN MONTH(b.TGL_BUKTI)<=$bln2 and b.pengurang_belanja=1 THEN a.nilai*-1 ELSE 0 END) AS up_ll
								, 0 as gj_ini, 0 as gj_ll, 0 as brg_ini, 0 as brg_ll
								FROM trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
								WHERE b.kd_skpd='$kd_skpd'
								GROUP BY b.kd_skpd
								UNION ALL
								SELECT a.kd_skpd, 0 up_ini, 0 up_ll
								,SUM(CASE WHEN MONTH(b.tgl_sts)=$bln2 and b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus NOT IN ('0','3') THEN a.rupiah*-1 ELSE 0 END) AS gj_ini
								,SUM(CASE WHEN MONTH(b.tgl_sts)<$bln2 and b.jns_trans=5 and b.jns_cp in (1) and b.pot_khusus NOT IN ('0','3') THEN a.rupiah*-1 ELSE 0 END) AS gj_ll
								,SUM(CASE WHEN MONTH(b.tgl_sts)=$bln2 and b.jns_trans=5 and b.jns_cp in (2) THEN a.rupiah*-1 ELSE 0 END) AS brg_ini
								,SUM(CASE WHEN MONTH(b.tgl_sts)<$bln2 and b.jns_trans=5 and b.jns_cp in (2) THEN a.rupiah*-1 ELSE 0 END) AS brg_ll
								from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
								WHERE b.kd_skpd='$kd_skpd' and right(a.kd_kegiatan,5)<>'00.04'
								GROUP BY a.kd_skpd
								UNION ALL
								SELECT a.kd_skpd
								,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ini
								,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ll
								,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ini
								,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ll
								,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (6,7) THEN a.nilai ELSE 0 END) AS brg_ini
								,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (6,7) THEN a.nilai ELSE 0 END) AS brg_ll
								from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
								WHERE a.kd_skpd='$kd_skpd' GROUP BY a.kd_skpd)b GROUP BY kd_skpd ";
		}else{
			$kon = "";
			$real_pend_sp2d = "ISNULL(SUM(d.nilai), 0) AS nilai FROM trhsp2d a 
							   INNER JOIN trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm
							   INNER JOIN trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp
							   INNER JOIN trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp
							   WHERE a.kd_skpd='$kd_skpd' AND status_terima='1' AND MONTH(tgl_terima)<='$bln2' AND (LEFT(kd_rek5,1) in ('5') OR kd_rek5 in ('1110302')) AND (c.sp2d_batal IS NULL  OR c.sp2d_batal !=1)";
				    
			$real_spj = "sum(gj_ll)+sum(gj_ini)+sum(brg_ll)+sum(brg_ini)+sum(up_ll)+sum(up_ini) as nilai from(
							SELECT a.kd_skpd
							,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ini
							,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ll
							,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ini
							,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ll
							,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (6) THEN a.nilai ELSE 0 END) AS brg_ini
							,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (6) THEN a.nilai ELSE 0 END) AS brg_ll
							from trdtransout a join trhtransout b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
							WHERE a.kd_skpd='$kd_skpd' GROUP BY a.kd_skpd
							UNION ALL
							SELECT b.kd_skpd, 0 as up_ini
							,SUM(CASE WHEN MONTH(b.TGL_BUKTI)<=$bln2 and b.pengurang_belanja=1 THEN a.nilai*-1 ELSE 0 END) AS up_ll
							, 0 as gj_ini, 0 as gj_ll, 0 as brg_ini, 0 as brg_ll
							FROM trdinlain a join TRHINLAIN b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
							WHERE b.kd_skpd='$kd_skpd'
							GROUP BY b.kd_skpd
							UNION ALL
							SELECT a.kd_skpd, 0 up_ini, 0 up_ll
							,SUM(CASE WHEN MONTH(b.tgl_sts)=$bln2 and b.jns_trans=5 and b.jns_cp in (1) THEN a.rupiah*-1 ELSE 0 END) AS gj_ini
							,SUM(CASE WHEN MONTH(b.tgl_sts)<$bln2 and b.jns_trans=5 and b.jns_cp in (1) THEN a.rupiah*-1 ELSE 0 END) AS gj_ll
							,SUM(CASE WHEN MONTH(b.tgl_sts)=$bln2 and b.jns_trans=5 and b.jns_cp in (2) THEN a.rupiah*-1 ELSE 0 END) AS brg_ini
							,SUM(CASE WHEN MONTH(b.tgl_sts)<$bln2 and b.jns_trans=5 and b.jns_cp in (2) THEN a.rupiah*-1 ELSE 0 END) AS brg_ll
							from trdkasin_pkd a join trhkasin_pkd b on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd
							WHERE b.kd_skpd='$kd_skpd' and right(a.kd_kegiatan,5)<>'00.04'
							GROUP BY a.kd_skpd
							UNION ALL
							SELECT a.kd_skpd
							,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ini
							,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (1,2,3) THEN a.nilai ELSE 0 END) AS up_ll
							,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ini
							,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (4,5) THEN a.nilai ELSE 0 END) AS gj_ll
							,SUM(CASE WHEN MONTH(b.tgl_bukti)=$bln2 AND jns_spp in (6,7) THEN a.nilai ELSE 0 END) AS brg_ini
							,SUM(CASE WHEN MONTH(b.tgl_bukti)<$bln2 AND jns_spp in (6,7) THEN a.nilai ELSE 0 END) AS brg_ll
							from trdtransout_blud a join trhtransout_blud b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd
							WHERE a.kd_skpd='$kd_skpd' GROUP BY a.kd_skpd)b GROUP BY kd_skpd ";
		}
				
			  $att="exec spj_skpd '$kd_skpd','$bln2'";
				$hasil=$this->db->query($att);
				foreach ($hasil->result() as $trh1){
				$bre				=	$trh1->kd_rek;
				$wok				=	$trh1->uraian;
				$nilai				=	$trh1->anggaran;
				$real_up_ini		=	$trh1->up_ini;
				$real_up_ll			=	$trh1->up_lalu;
				$real_gaji_ini		=	$trh1->gaji_ini;
				$real_gaji_ll		=	$trh1->gaji_lalu;
				$real_brg_js_ini	=	$trh1->brg_ini;
				$real_brg_js_ll		=	$trh1->brg_lalu;
				$total	= $real_gaji_ll+$real_gaji_ini+$real_brg_js_ll+$real_brg_js_ini+$real_up_ll+$real_up_ini;
				$sisa	= $nilai-$real_gaji_ll-$real_gaji_ini-$real_brg_js_ll-$real_brg_js_ini-$real_up_ll-$real_up_ini;
				}
			  
				$real_keluar_spj = $total;
				
				$sql_kastunai = "SELECT nilai from (
								SELECT x.terima+y.tox-x.keluar+z.terimakeluar as nilai FROM (
								--saldotunai_terimakeluar
								SELECT '1' kd,
									ISNULL(SUM(case when jns=1 then jumlah else 0 end ),0) AS terima,
									ISNULL(SUM(case when jns=2 then jumlah else 0 end),0) AS keluar
									FROM (
									SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan UNION ALL
									select f.tgl_kas as tgl,f.no_kas as bku,f.keterangan as ket, f.nilai as jumlah, '1' as jns,f.kd_skpd as kode from tr_jpanjar f join tr_panjar g on f.no_panjar_lalu=g.no_panjar and f.kd_skpd=g.kd_skpd where f.jns=2 and g.pay='TUNAI' UNION ALL
									select tgl_bukti [tgl],no_bukti [bku],ket [ket],nilai [jumlah],'1' [jns],kd_skpd [kode] from trhtrmpot a where kd_skpd='$kd_skpd' and pay='' and jns_spp in ('1','2','3') 
									UNION ALL
									select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar where pay='TUNAI' 
									UNION ALL
									select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd 
									where jns_trans NOT IN ('4','2') and pot_khusus =0  and bank='TNK'
									GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd				
									UNION ALL
									SELECT	a.tgl_bukti AS tgl,	a.no_bukti AS bku, a.ket AS ket, SUM(z.nilai) - isnull(pot, 0) AS jumlah, '2' AS jns, a.kd_skpd AS kode FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
									LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
									LEFT JOIN (SELECT no_spm, SUM (nilai) pot FROM trspmpot GROUP BY no_spm) c ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') AND MONTH(a.tgl_bukti)<'$bln2' and a.kd_skpd='$kd_skpd' AND a.no_bukti NOT IN( select no_bukti from trhtransout where no_sp2d in (SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1) AND MONTH(tgl_bukti)<'$bln2' and  no_kas not in (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' AND MONTH(tgl_bukti)<'$bln2' GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1) and jns_spp in (4,5,6) and kd_skpd='$kd_skpd')
									GROUP BY a.tgl_bukti,a.no_bukti,a.ket,a.no_sp2d,z.no_sp2d,a.total,pot,a.kd_skpd
									UNION ALL
									SELECT	tgl_bukti AS tgl,no_bukti AS bku, ket AS ket,  isnull(total, 0) AS jumlah, '2' AS jns, kd_skpd AS kode
									from trhtransout WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') and no_sp2d in (SELECT no_sp2d as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1) AND MONTH(tgl_bukti)<'$bln2' and  no_kas not in (SELECT min(z.no_kas) as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' AND MONTH(tgl_bukti)<'$bln2' GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1) and jns_spp in (4,5,6) and kd_skpd='$kd_skpd'
									UNION ALL
									SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain  WHERE pay='TUNAI' 
									UNION ALL
									SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_setorsimpanan WHERE jenis ='2' 
									UNION ALL
									SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='TUNAI' 
									UNION ALL
									select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],a.nilai [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a 
									where a.kd_skpd='$kd_skpd' and a.pay='' and jns_spp in ('1','2','3')
									) a where month(a.tgl)<'$bln2' and kode='$kd_skpd') x
								LEFT JOIN (
								--saldotunai_tox
								SELECT '1' kd, CASE WHEN kd_bayar<>1 THEN isnull(sld_awal,0)+sld_awalpajak ELSE 0 END AS tox FROM ms_skpd where kd_skpd='$kd_skpd') y
								on x.kd=y.kd
								LEFT JOIN (
								--terimakeluar_tunai
								SELECT '1' kd, ISNULL(SUM(masuk-keluar),0) terimakeluar FROM (
								SELECT tgl_kas AS tgl, kd_skpd AS kode, nilai AS masuk,0 AS keluar FROM tr_ambilsimpanan 
								UNION ALL
								select f.tgl_kas as tgl, f.kd_skpd as kode, f.nilai as masuk, 0 as keluar from tr_jpanjar f join tr_panjar g on f.no_panjar_lalu=g.no_panjar and f.kd_skpd=g.kd_skpd where f.jns=2 and g.pay='TUNAI' 
								UNION ALL
								select tgl_bukti [tgl], kd_skpd [kode], nilai AS masuk,0 AS keluar from trhtrmpot a where kd_skpd='$kd_skpd' and pay='' and jns_spp in('1','2','3') 
								UNION ALL
								select tgl_panjar as tgl, kd_skpd as kode, 0 as masuk,nilai as keluar from tr_panjar where pay='TUNAI' 
								UNION ALL
								select a.tgl_sts as tgl, a.kd_skpd as kode, 0 as masuk,SUM(b.rupiah) as keluar from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd where jns_trans NOT IN ('4','2') and pot_khusus =0  and bank='TNK' GROUP BY a.tgl_sts,a.kd_skpd		
								UNION ALL
								SELECT a.tgl_bukti AS tgl, a.kd_skpd AS kode, 0 AS masuk, SUM(z.nilai)-isnull(pot,0)  AS keluar FROM trhtransout a INNER JOIN trdtransout z ON a.no_bukti=z.no_bukti AND a.kd_skpd=z.kd_skpd
								LEFT JOIN trhsp2d b ON z.no_sp2d = b.no_sp2d
								LEFT JOIN (SELECT no_spm, SUM (nilai) pot	FROM trspmpot GROUP BY no_spm) c ON b.no_spm = c.no_spm WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') AND MONTH(a.tgl_bukti)='$bln2' and a.kd_skpd='$kd_skpd' 
								AND a.no_bukti NOT IN(select no_bukti from trhtransout where no_sp2d in (SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1) AND MONTH(tgl_bukti)='$bln2' and  no_kas not in (SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' AND MONTH(tgl_bukti)='$bln2' GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1) and jns_spp in (4,5,6) and kd_skpd='$kd_skpd')
								group by a.tgl_bukti,a.kd_skpd,pot
								UNION ALL
								select tgl_bukti AS tgl, kd_skpd AS kode, 0 AS masuk, ISNULL(total,0)  AS keluar from trhtransout WHERE pay = 'TUNAI' AND panjar NOT IN('1','3') AND no_sp2d in (SELECT ISNULL(no_sp2d,'') as no_bukti FROM trhtransout where kd_skpd='$kd_skpd' GROUP BY no_sp2d HAVING COUNT(no_sp2d)>1) AND MONTH(tgl_bukti)='$bln2' and  no_kas not in(SELECT ISNULL(min(z.no_kas),'') as no_bukti FROM trhtransout z WHERE z.jns_spp in (4,5,6) and kd_skpd='$kd_skpd' AND MONTH(tgl_bukti)='$bln2' GROUP BY z.no_sp2d HAVING COUNT(z.no_sp2d)>1) and jns_spp in (4,5,6) and kd_skpd='$kd_skpd'
								UNION ALL
								SELECT tgl_bukti AS tgl, kd_skpd AS kode, 0 as masuk,nilai AS keluar FROM trhoutlain WHERE pay='TUNAI' 
								UNION ALL
								SELECT tgl_kas AS tgl, kd_skpd AS kode, 0 as masuk,nilai AS keluar FROM tr_setorsimpanan WHERE jenis ='2' 
								UNION  ALL
								SELECT tgl_bukti AS tgl, kd_skpd AS kode, nilai as masuk,0 AS keluar FROM trhINlain WHERE pay='TUNAI' 
								union all
								select a.tgl_bukti [tgl], a.kd_skpd [kode], 0 as masuk,nilai AS keluar from trhstrpot a where a.kd_skpd='$kd_skpd' and a.pay='' and jns_spp in ('1','2','3'))a
								where month(a.tgl)='$bln2' and kode='$kd_skpd') z
								on x.kd=z.kd ) r";
            $hasil_kas = $this->db->query($sql_kastunai);
			$kastunai  = $hasil_kas->row('nilai');
			
			
			$sal_ll = $this->db->query("SELECT CASE WHEN kd_bayar=1 THEN isnull(sld_awal,0)+sld_awalpajak ELSE 0 END AS sal_lalu 
										FROM ms_skpd where kd_skpd='$kd_skpd'");
			$sal_lal = $sal_ll->row();          
			$sal_llu = $sal_lal->sal_lalu;
			$sql_hasil_bank = "SELECT ISNULL(a.terima-a.keluar,0) as nilai from( 
								select
								SUM(case when jns=1 then jumlah else 0 end) AS terima,
								SUM(case when jns=2 then jumlah else 0 end) AS keluar
								from (
								SELECT tgl_kas AS tgl,no_kas AS bku,keterangan as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM tr_setorsimpanan union ALL
								SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'1' AS jns,kd_skpd AS kode FROM trhINlain WHERE pay='BANK' union ALL
								select c.tgl_kas [tgl],c.no_kas [bku] ,c.keterangan [ket],c.nilai [jumlah],'1' [jns],c.kd_skpd [kode] from tr_jpanjar c join tr_panjar d on 
								c.no_panjar_lalu=d.no_panjar and c.kd_skpd=d.kd_skpd where c.jns='1' and c.kd_skpd='$kd_skpd' and  d.pay='BANK' union all
								select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'1' [jns],a.kd_skpd [kode] 
								from trhtrmpot a join trdtrmpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd 
								where a.kd_skpd='$kd_skpd' and pay='BANK' group by  a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd  union all         
								select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '1' as jns, a.kd_skpd as kode 
								from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
								where jns_trans NOT IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd'
								GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
								union all
								SELECT tgl_kas AS tgl,no_kas AS bku,keterangan AS ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM tr_ambilsimpanan union ALL
								SELECT tgl_bukti AS tgl,no_bukti AS bku,ket as ket,nilai AS jumlah,'2' AS jns,kd_skpd AS kode FROM trhoutlain where pay='BANK' union all
								select tgl_panjar as tgl,no_panjar as bku,keterangan as ket, nilai as jumlah, '2' as jns,kd_skpd as kode from tr_panjar WHERE jns='1' and kd_skpd='$kd_skpd' AND pay='BANK' union all
								SELECT tgl_bukti AS tgl,no_bukti AS bku,ket AS ket,total-isnull(pot,0) AS jumlah,'2' AS jns,a.kd_skpd AS kode FROM trhtransout a 
								join trhsp2d b on a.no_sp2d=b.no_sp2d left join (select no_spm, sum(nilai)pot from trspmpot group by no_spm) 
								c on b.no_spm=c.no_spm WHERE pay='BANK' union all 
								select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
								from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
								where jns_trans NOT IN ('4','2','5') and pot_khusus =0  and bank='BNK' and a.kd_skpd='$kd_skpd'
								GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
								union all
								select a.tgl_sts as tgl,a.no_sts as bku, a.keterangan as ket, SUM(b.rupiah) as jumlah, '2' as jns, a.kd_skpd as kode 
								from trhkasin_pkd a INNER JOIN trdkasin_pkd b ON a.no_sts=b.no_sts AND a.kd_skpd=b.kd_skpd  
								where jns_trans NOT IN ('5') and bank='BNK' and a.kd_skpd='$kd_skpd'
								GROUP BY a.tgl_sts,a.no_sts, a.keterangan,a.kd_skpd 
								union all			
								select a.tgl_bukti [tgl],a.no_bukti [bku],a.ket [ket],sum(b.nilai) [jumlah],'2' [jns],a.kd_skpd [kode] from trhstrpot a join 
								trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd 
								where a.kd_skpd='$kd_skpd' and pay='BANK' group by a.tgl_bukti,a.no_bukti,a.ket,a.kd_skpd) a
								where month(tgl)<='$bln2' and kode='$kd_skpd')a";
			$hasil_bank = $this->db->query($sql_hasil_bank);
			$saldobank  = $hasil_bank->row('nilai')+$sal_llu;	
			
			$sql_pjk = "SELECT ISNULL(SUM(nilai),0) nilai FROM (
						SELECT ISNULL(SUM(b.nilai),0) AS nilai
						FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
						WHERE MONTH(a.tgl_bukti)<='12' AND b.kd_skpd='$kd_skpd'
						UNION ALL
						SELECT ISNULL(SUM(b.nilai)*-1,0) AS nilai
						FROM trhstrpot a INNER JOIN trdstrpot b on a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
						WHERE MONTH(a.tgl_bukti)<='12' AND b.kd_skpd='$kd_skpd') z";
			$csql_pjk = $this->db->query($sql_pjk);
			$nil_pajak  = $csql_pjk->row('nilai');
		
		if($bln2<12){
			$uyhdtini = "ISNULL(SUM(nilai),0) nilai from (
						 select ISNULL(sld_awal,0)+ISNULL(sld_awalpajak,0) nilai from ms_skpd where KD_SKPD='$kd_skpd'
						 UNION ALL 
						 select ISNULL(sum(nilai)*-1,0) nilai from TRHOUTLAIN where KD_SKPD='$kd_skpd' and tgl_bukti<='$periode2' and thnlalu='1' and jns_beban not in ('4','6')) x";
		}else{
			$uyhdtini = "ISNULL(SUM(nilai),0) nilai from (
						 select ISNULL(sld_awal,0)+ISNULL(sld_awalpajak,0) nilai from ms_skpd where KD_SKPD='$kd_skpd'
						 UNION ALL 
						 select ISNULL(sum(nilai)*-1,0) nilai from TRHOUTLAIN where KD_SKPD='$kd_skpd' and tgl_bukti<='$periode2' and thnlalu='1' and jns_beban not in ('4','6')
						 UNION ALL
						 select $kastunai+$saldobank-$nil_pajak as nilai) x ";
		}
		
		$rek_ppn = "'2130301'";
		$sql_terima_ppn = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_ppn)";
		$hasil_terima_ppn = $this->db->query($sql_terima_ppn);
		$terima_ppn  = $hasil_terima_ppn->row('nilai');
		
		$sql_keluar_ppn = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_ppn)";
		$hasil_keluar_ppn = $this->db->query($sql_keluar_ppn);
		$keluar_ppn  = $hasil_keluar_ppn->row('nilai');
			
		$rek_pph21 = "'2130101'";
		$sql_terima_pph21 = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_pph21)";
		$hasil_terima_pph21 = $this->db->query($sql_terima_pph21);
		$terima_pph21  = $hasil_terima_pph21->row('nilai');
		
		$sql_keluar_pph21 = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_pph21)";
		$hasil_keluar_pph21 = $this->db->query($sql_keluar_pph21);
		$keluar_pph21  = $hasil_keluar_pph21->row('nilai');
		
		$rek_pph22 = "'2130201'";
		$sql_terima_pph22 = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_pph22)";
		$hasil_terima_pph22 = $this->db->query($sql_terima_pph22);
		$terima_pph22  = $hasil_terima_pph22->row('nilai');
		
		$sql_keluar_pph22 = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_pph22)";
		$hasil_keluar_pph22 = $this->db->query($sql_keluar_pph22);
		$keluar_pph22  = $hasil_keluar_pph22->row('nilai');
		
		$rek_pph23 = "'2130401'";
		$sql_terima_pph23 = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_pph23)";
		$hasil_terima_pph23 = $this->db->query($sql_terima_pph23);
		$terima_pph23  = $hasil_terima_pph23->row('nilai');
		
		$sql_keluar_pph23 = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_pph23)";
		$hasil_keluar_pph23 = $this->db->query($sql_keluar_pph23);
		$keluar_pph23  = $hasil_keluar_pph23->row('nilai');
						   
		$rek_iwp = "'2110701','2110702','2110703'";
		$sql_terima_iwp = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_iwp)";
		$hasil_terima_iwp = $this->db->query($sql_terima_iwp);
		$terima_iwp  = $hasil_terima_iwp->row('nilai');
		
		$sql_keluar_iwp = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_iwp)";
		$hasil_keluar_iwp = $this->db->query($sql_keluar_iwp);
		$keluar_iwp  = $hasil_keluar_iwp->row('nilai');
		
		$rek_taperum = "'2110501'";
		$sql_terima_taperum = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_taperum)";
		$hasil_terima_taperum = $this->db->query($sql_terima_taperum);
		$terima_taperum  = $hasil_terima_taperum->row('nilai');
		
		$sql_keluar_taperum = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_taperum)";
		$hasil_keluar_taperum = $this->db->query($sql_keluar_taperum);
		$keluar_taperum  = $hasil_keluar_taperum->row('nilai');
		
		$rek_pph4 = "'2130501'";
		$sql_terima_pph4 = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_pph4)";
		$hasil_terima_pph4 = $this->db->query($sql_terima_pph4);
		$terima_pph4  = $hasil_terima_pph4->row('nilai');
		
		$sql_keluar_pph4 = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_pph4)";
		$hasil_keluar_pph4 = $this->db->query($sql_keluar_pph4);
		$keluar_pph4  = $hasil_keluar_pph4->row('nilai');
		
		$rek_ppn2 = "'2111001'";
		$sql_terima_ppn2 = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_ppn2)";
		$hasil_terima_ppn2 = $this->db->query($sql_terima_ppn2);
		$terima_ppn2  = $hasil_terima_ppn2->row('nilai');
		
		$sql_keluar_ppn2 = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_ppn2)";
		$hasil_keluar_ppn2 = $this->db->query($sql_keluar_ppn2);
		$keluar_ppn2  = $hasil_keluar_ppn2->row('nilai');
		
		$rek_ppn3 = "'2111101'";
		$sql_terima_ppn3 = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_ppn3)";
		$hasil_terima_ppn3 = $this->db->query($sql_terima_ppn3);
		$terima_ppn3  = $hasil_terima_ppn3->row('nilai');
		
		$sql_keluar_ppn3 = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_ppn3)";
		$hasil_keluar_ppn3 = $this->db->query($sql_keluar_ppn3);
		$keluar_ppn3  = $hasil_keluar_ppn3->row('nilai');
		
		$rek_jkk = "'2111201'";
		$sql_terima_jkk = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_jkk)";
		$hasil_terima_jkk = $this->db->query($sql_terima_jkk);
		$terima_jkk  = $hasil_terima_jkk->row('nilai');
		
		$sql_keluar_jkk = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_jkk)";
		$hasil_keluar_jkk = $this->db->query($sql_keluar_jkk);
		$keluar_jkk  = $hasil_keluar_jkk->row('nilai');
		
		$rek_jkm = "'2111301'";
		$sql_terima_jkm = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_jkm)";
		$hasil_terima_jkm = $this->db->query($sql_terima_jkm);
		$terima_jkm  = $hasil_terima_jkm->row('nilai');
		
		$sql_keluar_jkm = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_jkm)";
		$hasil_keluar_jkm = $this->db->query($sql_keluar_jkm);
		$keluar_jkm  = $hasil_keluar_jkm->row('nilai');
		
		$rek_bpjs = "'2111401'";
		$sql_terima_bpjs = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_bpjs)";
		$hasil_terima_bpjs = $this->db->query($sql_terima_bpjs);
		$terima_bpjs  = $hasil_terima_bpjs->row('nilai');
		
		$sql_keluar_bpjs = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_bpjs)";
		$hasil_keluar_bpjs = $this->db->query($sql_keluar_bpjs);
		$keluar_bpjs  = $hasil_keluar_bpjs->row('nilai');
		
		$rek_denda = "'4140611'";
		$sql_terima_denda = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_denda)";
		$hasil_terima_denda = $this->db->query($sql_terima_denda);
		$terima_denda  = $hasil_terima_denda->row('nilai');
		
		$sql_keluar_denda = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 IN ($rek_denda)";
		$hasil_keluar_denda = $this->db->query($sql_keluar_denda);
		$keluar_denda  = $hasil_keluar_denda->row('nilai');
		
		$sql_terima_lain = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 NOT IN ($rek_ppn,$rek_pph21,$rek_pph22,$rek_pph23,$rek_iwp,$rek_taperum,$rek_pph4,$rek_ppn2,$rek_ppn3,$rek_jkk,$rek_jkm,$rek_bpjs,$rek_denda)";
		$hasil_terima_lain = $this->db->query($sql_terima_lain);
		$terima_lain  = $hasil_terima_lain->row('nilai');
		
		$sql_keluar_lain = "SELECT ISNULL(SUM(b.nilai), 0) nilai FROM trhstrpot a INNER JOIN trdstrpot b ON a.no_bukti = b.no_bukti AND a.kd_skpd = b.kd_skpd 
						   WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' AND b.kd_rek5 NOT IN ($rek_ppn,$rek_pph21,$rek_pph22,$rek_pph23,$rek_iwp,$rek_taperum,$rek_pph4,$rek_ppn2,$rek_ppn3,$rek_jkk,$rek_jkm,$rek_bpjs,$rek_denda)";
		$hasil_keluar_lain = $this->db->query($sql_keluar_lain);
		$keluar_lain  = $hasil_keluar_lain->row('nilai');
		
		if($kd_skpd=='1.02.02.01'){
			$setor_tlalu_blud = "UNION ALL
				  SELECT isnull(sum(kredit-debet),0)*-1 as nilai FROM trhju_calk a INNER JOIN trdju_calk b ON a.kd_skpd=b.kd_unit AND a.no_voucher=b.no_voucher
				  WHERE YEAR(a.tgl_voucher)=$thn_ang AND LEFT(b.kd_rek5,3)='211' AND b.kd_unit='$kd_skpd'";
		}else{
			$setor_tlalu_blud = "";
		}
		$sql="SELECT 1 nomor,0 jns, 'Saldo BKU' AS nama, $kastunai+$saldobank nilai 
			  UNION ALL
			  --Kas tunai
			  SELECT 1 nomor,1 jns,'- Kas Tunai' AS nama, $kastunai nilai
			  UNION ALL
			  --Saldo Bank
			  SELECT 1 nomor, 1 jns, '- Saldo Bank' AS nama, $saldobank nilai
			  UNION ALL
			  --realisasi penerimaan sp2d--
			  SELECT 2 nomor, 0 jns, 'Realisasi penerimaan SP2D' nama, $real_pend_sp2d
			  UNION ALL
			  -- Realisasi SPJ
			  SELECT 3 nomor, '0' jns, 'Realisasi Pengeluaran SPJ (LS+UP/GU/TU)' uraian, $real_spj
			  UNION ALL
			  --setro cp--
			  SELECT 4 nomor, '0' jns, 'Setoran CP' uraian, SUM (nilai_cp) nilai FROM
				  (SELECT SUM (rupiah) AS nilai_cp FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd WHERE d.kd_skpd = '$kd_skpd' AND jns_trans = '5' AND pot_khusus IN ('2', '1') AND MONTH (tgl_sts) <= '$bln2'
				  UNION ALL
				  SELECT SUM (rupiah) AS nilai_cp FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts AND c.kd_skpd = d.kd_skpd 
				  WHERE d.kd_skpd = '$kd_skpd' AND ((jns_trans = '5' AND pot_khusus = '0') OR jns_trans = '1') AND MONTH (tgl_sts) <= '$bln2') a
			  UNION ALL
			  --setor uyhd tahun lalu--
              SELECT 5 nomor, '0' jns, 'Setoran UYHD Tahun Lalu' uraian, ISNULL(sum(nilai),0) nilai 
			  from TRHOUTLAIN where KD_SKPD='$kd_skpd' and tgl_bukti<='$periode2' and jns_beban not in ('4','6','7')
			  UNION ALL
			  --lebih setor
			  SELECT 6 nomor, '0' jns, 'Lebih Setor' uraian, 0 nilai
			  UNION ALL
			  SELECT 7 nomor, '0' jns, 'Penerimaan Pajak' uraian, SUM (nilai) nilai FROM ( 
				   SELECT $terima_ppn+$terima_pph21+$terima_pph22+$terima_pph23+$terima_iwp+$terima_taperum+$terima_pph4+$terima_ppn2+$terima_ppn3+$terima_jkk+$terima_jkm+$terima_bpjs+$terima_denda+$terima_lain nilai 
				) a
				
			UNION ALL
			SELECT 7 nomor, '1' jns, '-Potongan Pajak' uraian, 0 nilai
			UNION ALL
			---penerimaan PPN--
			SELECT 7 nomor, '2' jns, 'a. PPn' uraian, $terima_ppn nilai 
			UNION ALL
			---penerimaan PPH21--
			SELECT 7 nomor, '2' jns, 'b. PPh 21' uraian, $terima_pph21 nilai
			UNION ALL
			---penerimaan PPH22--
			SELECT 7 nomor, '2' jns, 'c. PPh22' uraian, $terima_pph22 nilai
			UNION ALL
			---penerimaan PPH23--
			SELECT 7 nomor, '2' jns, 'c. PPh23' uraian, $terima_pph23 nilai
			UNION ALL
			---penerimaan IWP--
			SELECT 7 nomor, '2' jns, '- Pot. IWP' uraian, $terima_iwp nilai
			UNION ALL
			---penerimaan Taperum--
			SELECT 7 nomor, '2' jns, '- Pot. Taperum' uraian, $terima_taperum nilai
			UNION ALL
			---penerimaan pph4--
			SELECT 7 nomor, '2' jns, '- Pot. PPh Pasal 4' uraian, $terima_pph4 nilai
			UNION ALL
			---penerimaan ppnpn 2%--
			SELECT 7 nomor, '2' jns, '- Pot. Iuran Wajib PPNPN 2%' uraian, $terima_ppn2 nilai
			UNION ALL
			---penerimaan ppnpn 3%--
			SELECT 7 nomor, '2' jns, '- Pot. Iuran Wajib PPNPN 3%' uraian, $terima_ppn3 nilai
			UNION ALL
			---Iuran JKK--
			SELECT 7 nomor, '2' jns, '- Pot. Iuran Wajib JKK' uraian, $terima_jkk nilai
			UNION ALL
			---Iuran JKM--
			SELECT 7 nomor, '2' jns, '- Pot. Iuran Wajib JKM' uraian, $terima_jkm nilai
			UNION ALL
			---Pot. BPJS--
			SELECT 7 nomor, '2' jns, '- Pot. BPJS' uraian, $terima_bpjs nilai
			UNION ALL
			---Denda Keterlambatan--
			SELECT 7 nomor, '2' jns, '- Denda Keterlambatan' uraian, $terima_denda nilai
			UNION ALL
			---penerimaan lain--
			SELECT 7 nomor, '2' jns, '- Lain-lain' uraian, $terima_lain nilai
            
			UNION ALL
			
			SELECT 8 nomor, '0' jns, 'Pengeluaran Pajak' uraian, SUM (nilai) nilai FROM ( SELECT $keluar_ppn+$keluar_pph21+$keluar_pph22+$keluar_pph23+$keluar_iwp+$keluar_taperum+$keluar_pph4+$keluar_ppn2+$keluar_ppn3+$keluar_jkk+$keluar_jkm+$keluar_bpjs+$keluar_denda+$keluar_lain nilai
				) a
			
			UNION ALL
								
			SELECT 8 nomor, '1' jns, '-Potongan Pajak' uraian, 0 nilai 
			
			UNION ALL
			--pengeluaran ppn--
			SELECT 8 nomor, '2' jns, 'a. PPn' uraian, $keluar_ppn nilai
			UNION ALL
			--pengeluaran pph21--
			SELECT 8 nomor, '2' jns, 'b. PPh 21' uraian, $keluar_pph21 nilai
			UNION ALL
			--pengeluaran pph22--
			SELECT 8 nomor, '2' jns, 'c. PPh 22' uraian, $keluar_pph22 nilai
			UNION ALL
			--pengeluaran pph23--
			SELECT 8 nomor, '2' jns, 'd. PPh 23' uraian, $keluar_pph23 nilai
			UNION ALL
			--pengeluaran iwp--
			SELECT 8 nomor, '2' jns, '- Pot. IWP' uraian, $keluar_iwp nilai
			UNION ALL
			--pengeluaran taperum--
			SELECT 8 nomor, '2' jns, '- Pot. Taperum' uraian, $keluar_taperum nilai
			UNION ALL
			--pengeluaran pphpas4--
			SELECT 8 nomor, '2' jns, '- Pot. PPh Pasal 4' uraian, $keluar_pph4 nilai
			UNION ALL
			--pengeluaran ppnpn 2%--
			SELECT 8 nomor, '2' jns, '- Pot. Iuran Wajib PPNPN 2%' uraian, $keluar_ppn2 nilai
			UNION ALL
			--pengeluaran ppnpn 3%--
			SELECT 8 nomor, '2' jns, '- Pot. Iuran Wajib PPNPN 3%' uraian, $keluar_ppn3 nilai
			UNION ALL
			--pengeluaran Pot. Iuran Wajib JKK--
			SELECT 8 nomor, '2' jns, '- Pot. Iuran Wajib JKK' uraian, $keluar_jkk nilai
			UNION ALL
			--pengeluaran Pot. Iuran Wajib JKM--
			SELECT 8 nomor, '2' jns, '- Pot. Iuran Wajib JKM' uraian, $keluar_jkm nilai
			UNION ALL
			--pengeluaran Pot. BPJS--
			SELECT 8 nomor, '2' jns, '- Pot. BPJS' uraian, $keluar_bpjs nilai
			UNION ALL
			--pengeluaran Denda Keterlambatan--
			SELECT 8 nomor, '2' jns, '- Denda Keterlambatan' uraian, $keluar_denda nilai
			UNION ALL
			--pengeluaran lain--
			SELECT 8 nomor, '2' jns, '- Lain-lain' uraian, $keluar_lain nilai
			
			UNION ALL
			
			--Setoran Utang Pajak tahun lalu--
			SELECT 9 nomor, '0' jns, 'Setoran Utang Pajak Tahun Lalu' AS uraian, 
			isnull(sum(nilai),0) as nilai from 
			( SELECT isnull(sum(nilai),0) as nilai FROM TRHOUTLAIN where jns_beban = '7' AND KD_SKPD='$kd_skpd'
			  AND tgl_bukti BETWEEN '$periode1' AND '$periode2'
			  $setor_tlalu_blud ) x
  

			
			UNION ALL
			
			--Setoran Utang Belanja tahun lalu--
			SELECT 10 nomor, '0' jns, 'Setoran Utang Belanja Tahun Lalu' AS uraian, ISNULL(SUM(nilai),0) nilai FROM
				(
				SELECT ISNULL(SUM(debet - kredit), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
				AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek5, 5) IN ('21501','21502','21503') AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND b.map_real in ('15','40')
				UNION ALL
				SELECT ISNULL(SUM(debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
				AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek5, 5) IN ('21501','21502','21503') AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND (b.kd_unit='' OR b.kd_unit IS NULL) AND b.tabel='1' AND reev in ('','0')
				) a 
			
			UNION ALL
			SELECT 10 nomor, '1' jns, '- Belanja Pegawai' AS uraian, ISNULL(SUM(nilai),0) nilai FROM
				(
				SELECT ISNULL(SUM(debet - kredit), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
				AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek5, 5) = '21501' AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND b.map_real in ('15','40')
				UNION ALL
				SELECT ISNULL(SUM(debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
				AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek5, 5) = '21501' AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND (b.kd_unit='' OR b.kd_unit IS NULL) AND b.tabel='1' AND reev in ('','0')
				) a 
			UNION ALL
			SELECT 10 nomor, '1' jns, '- Belanja Barang dan Jasa' AS uraian, ISNULL(SUM(nilai),0) nilai FROM
				(
				SELECT ISNULL(SUM(debet - kredit), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
				AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek5, 5) = '21502' AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND b.map_real in ('15','40')
				UNION ALL
				SELECT ISNULL(SUM(debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
				AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek5, 5) = '21502' AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND (b.kd_unit='' OR b.kd_unit IS NULL) AND b.tabel='1' AND reev in ('','0')
				) a  
			UNION ALL
			SELECT 10 nomor, '1' jns, '- Belanja Modal' AS uraian, ISNULL(SUM(nilai),0) nilai FROM
				(
				SELECT ISNULL(SUM(debet - kredit), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
				AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek5, 5) = '21503' AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND b.map_real in ('15','40')
				UNION ALL
				SELECT ISNULL(SUM(debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
				AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek5, 5) = '21503' AND b.tgl_voucher BETWEEN '$periode1' AND '$periode2' AND (b.kd_unit='' OR b.kd_unit IS NULL) AND b.tabel='1' AND reev in ('','0')
				) a   
			
			UNION ALL
			
			--pajak yang belum di setor--
			SELECT 11 nomor, '0' jns, 'Pajak yang belum disetor' AS uraian, ISNULL( SUM (terima_pajak - setor_pajak), 0 ) AS nilai
			FROM ( SELECT a.kd_skpd, SUM (b.nilai) AS terima_pajak, 0 setor_pajak 
			       FROM trhtrmpot a INNER JOIN trdtrmpot b ON a.kd_skpd = b.kd_skpd AND a.no_bukti = b.no_bukti WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' GROUP BY a.kd_skpd
				   UNION ALL
				   SELECT a.kd_skpd, 0 terima_pajak, SUM (b.nilai) AS setor_pajak
				   FROM trhstrpot a INNER JOIN trdstrpot b ON a.kd_skpd = b.kd_skpd AND a.no_bukti = b.no_bukti WHERE a.kd_skpd = '$kd_skpd' AND a.tgl_bukti BETWEEN '$periode1' AND '$periode2' GROUP BY a.kd_skpd
				) a
			
			UNION ALL
			
			--belanja yang belum dibayar--
			SELECT 12 nomor, '0' jns, 'Belanja yang blm dibayar ' AS uraian, SUM (nilai) nilai
			FROM ( SELECT ISNULL(SUM(kredit - debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher AND a.kd_unit = b.kd_skpd
				   WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek5, 5) in ('21501','21502','21503') AND b.tgl_voucher <='$periode2'
				) a
			
			UNION ALL
			
			SELECT 12 nomor, '1' jns, '- Belanja Pegawai' AS uraian, ISNULL(SUM(kredit - debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
			AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek5, 5) = '21501' AND b.tgl_voucher <='$periode2'
			UNION ALL
			SELECT 12 nomor, '1' jns, '- Belanja Barang dan Jasa' AS uraian, ISNULL(SUM(kredit - debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
			AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek5, 5) = '21502' AND b.tgl_voucher <='$periode2' 
			UNION ALL
			SELECT 12 nomor, '1' jns, '- Belanja Modal' AS uraian, ISNULL(SUM(kredit - debet), 0) AS nilai FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher
			AND a.kd_unit = b.kd_skpd WHERE a.kd_unit = '$kd_skpd' AND LEFT (a.kd_rek5, 5) = '21503' AND b.tgl_voucher <='$periode2' ";
            	
				 $sql1=$this->db->query($sql);
                 foreach ($sql1->result() as $rowsql)
                {
                    $nomor  = $rowsql->nomor;
                    $jns    = $rowsql->jns;
                    $nama   = $rowsql->nama;
                    $nilai  = $rowsql->nilai;
                    
					if($nilai<0){
						$a = "(&nbsp;";
						$b = "&nbsp;)";
						$nilai2 = number_format($nilai*-1,"2",",",".");
					}else{
						$a = "";
						$b = "";
						$nilai2 = number_format($nilai,"2",",",".");
					}
				   
				   
				   
				   if($jns==0){
						if($nomor==1 || $nomor==2 || $nomor==3 || $nomor==4 || $nomor==5 || $nomor==6  || $nomor==7 || $nomor==8 || $nomor==9 || $nomor==10 || $nomor==11 || $nomor==12){
							$cRet .="<tr>
									<td align=\"center\"><b>$nomor</b></td>
									<td align=\"left\"><b>$nama</b></td>
									<td align=\"right\"><b>$a$nilai2$b</b></td>
									<td align=\"right\"><b>$a$nilai2$b</b></td>
									<!--<td align=\"right\">0</td>-->
									<td></td>
								</tr>";
						}else{
						   $cRet .="<tr>
										<td align=\"center\"><b>$nomor</b></td>
										<td align=\"left\"><b>$nama</b></td>
										<td align=\"right\">$a$nilai2$b</td>
										<td align=\"right\">$a$nilai2$b</td>
										<!--<td align=\"right\">0</td>-->
										<td></td>
									</tr>"; 
						}
				   }else{
				   
					$cRet .="<tr>
									<td align=\"center\"></td>
									<td align=\"left\">$nama</td>
									<td align=\"right\">$a$nilai2$b</td>
									<td align=\"right\">$a$nilai2$b</td>
									<!--<td align=\"right\">0</td>-->
									<td></td>
								</tr>"; 
				
				
				} 
                
				}
				

        $cRet .='</table>';
		  
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = "PENGELUARAN";
        $this->template->set('title', 'PENGELUARAN $cbulan');  
         switch($cetak) {
		case 0;
			$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');	
        break;
        case 1;
			echo ("<title>PENGELUARAN UNIT $cbulan</title>");
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
	
	function cetak_ba_penerimaan($kd_skpd="", $cetak = ''){
		$cbulan = 12;
		$id = $kd_skpd;
        $thn_ang = $this->session->userdata('pcThang');
		$bulan	 = $cbulan;
        $id1     = $this->session->userdata('kdskpd');
        $nmskpd = $this->tukd_model->get_nama($kd_skpd,'nm_skpd','ms_skpd','kd_skpd');
        $nm_skpd =strtoupper($nmskpd);
        $thn_ang_1= $thn_ang-1;   
        
		$tgl_ttd = $this->uri->segment(9);
		$periode1 = $this->uri->segment(13);
		$periode2 = $this->uri->segment(14);
		
		$arrayperiode1=explode("-",$periode1);
		$arrayperiode2=explode("-",$periode2);		
		$arraytgl=explode("-",$tgl_ttd);		
		
		
		if($arrayperiode2[1]<='3'){
			$tw = "I";
		}else if($arrayperiode2[1]<='6'){
			$tw = "II";
		}else if($arrayperiode2[1]<='9'){
			$tw = "III";
		}else{
			$tw = "IV";
		}
		/*
		$tgl   = substr($tgl_ttd,7,2);
		$bln   = substr($tgl_ttd,5,2);
		$thn   = substr($tgl_ttd,0,4);
		*/
		
		$tgl   = $arraytgl[2];
		$bln   = $arraytgl[1];
		$thn   = $arraytgl[0];
		
		$tanggl = $this->tukd_model->terbilang2($tgl);
		$buln  = $this->getBulan($bln);
		$tahun = $this->tukd_model->terbilang2($thn); 
		/*
		$tgl_periode1 = substr($periode1,7,2);
		$bln_periode1 = substr($periode1,5,1);
		$thn_periode1 = substr($periode1,0,4);
		*/
		
		$tgl_periode1 = $arrayperiode1[2];
		$bln_periode1 = $arrayperiode1[1];
		$thn_periode1 = $arrayperiode1[0];
		
		$len = strlen($periode2);
		/*
		if($len<>'10'){
			$tgl_periode2 = substr($periode2,7,2);
		}else{
			$tgl_periode2 = substr($periode2,8,2);
		}
		
		$bln_periode2 = substr($periode2,5,1);
		*/

		$tgl_periode2 = $arrayperiode2[2];
		$bln_periode2 = $arrayperiode2[1];
		$thn_periode2 = $arrayperiode2[0];
		
		$tanggl_prd1 = $this->tukd_model->terbilang2($tgl_periode1);
		$buln_prd1  = $this->getBulan($bln_periode1);
		
		$tanggl_prd2 = $this->tukd_model->terbilang2($tgl_periode2);
		$buln_prd2  = $this->getBulan($bln_periode2);
		
		 

		$day = date('D', strtotime($tgl_ttd));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
	
	$kabid    = str_replace('%20',' ',$this->uri->segment(5));
	$stafak   = str_replace('%20',' ',$this->uri->segment(6));
	$kasub    = str_replace('%20',' ',$this->uri->segment(7));
	$ppk      = str_replace('%20',' ',$this->uri->segment(8));
	$penyusun = str_replace('%20',' ',$this->uri->segment(10));
	$terima   = str_replace('%20',' ',$this->uri->segment(11));
	$keluar   = str_replace('%20',' ',$this->uri->segment(12));
	
	$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='3.13.01.17'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
	
	$sqlkabid="select TOP 1 * from ms_ttd where nip='$kabid'";
                 $sqlkabid1=$this->db->query($sqlkabid);
                 foreach ($sqlkabid1->result() as $rowkabid)
                {
                    $nipkabid      = $rowkabid->nip;
                    $namakabid     = $rowkabid->nama;
                    $jabatankabid  = $rowkabid->jabatan;
                    $pangkatkabid  = $rowkabid->pangkat;
                   
                }
				
	$sqlstafak="select TOP 1 * from ms_ttd where nip='$stafak'";
                 $sqlstafak1=$this->db->query($sqlstafak);
                 foreach ($sqlstafak1->result() as $rowstafak)
                {
                    $nipstafak      = $rowstafak->nip;
                    $namastafak     = $rowstafak->nama;
                    $jabatanstafak  = $rowstafak->jabatan;
                    $pangkatstafak  = $rowstafak->pangkat;
                   
                }
	
	$sqlkasub="select TOP 1 * from ms_ttd where nip='$kasub'";
                 $sqlkasub1=$this->db->query($sqlkasub);
                 foreach ($sqlkasub1->result() as $rowkasub)
                {
                    $nipkasub      = $rowkasub->nip;
                    $namakasub     = $rowkasub->nama;
                    $jabatankasub  = $rowkasub->jabatan;
                    $pangkatkasub  = $rowkasub->pangkat;
                   
                }
	
	$sqlppk="select TOP 1 * from ms_ttd where nip='$ppk'";
                 $sqlppk1=$this->db->query($sqlppk);
                 foreach ($sqlppk1->result() as $rowppk)
                {
                    $nipppk      = $rowppk->nip;
                    $namappk     = $rowppk->nama;
                    $jabatanppk  = $rowppk->jabatan;
                    $pangkatppk  = $rowppk->pangkat;
                   
                }
				
	$sqlpenyusun="select TOP 1 * from ms_ttd where nip='$penyusun'";
                 $sqlpenyusun1=$this->db->query($sqlpenyusun);
                 foreach ($sqlpenyusun1->result() as $rowpenyusun)
                {
                    $nippenyusun      = $rowpenyusun->nip;
                    $namapenyusun     = $rowpenyusun->nama;
                    $jabatanpenyusun  = $rowpenyusun->jabatan;
                    $pangkatpenyusun  = $rowpenyusun->pangkat;
                   
                }
	
	$sqlterima="select TOP 1 * from ms_ttd where nip='$terima'";
                 $sqlterima1=$this->db->query($sqlterima);
                 foreach ($sqlterima1->result() as $rowterima)
                {
                    $nipterima      = $rowterima->nip;
                    $namaterima     = $rowterima->nama;
                    $jabatanterima  = $rowterima->jabatan;
                    $pangkatterima  = $rowterima->pangkat;
                   
                }
	
	$sqlkeluar="select TOP 1 * from ms_ttd where nip='$keluar'";
                 $sqlkeluar1=$this->db->query($sqlkeluar);
                 foreach ($sqlkeluar1->result() as $rowkeluar)
                {
                    $nipkeluar      = $rowkeluar->nip;
                    $namakeluar     = $rowkeluar->nama;
                    $jabatankeluar  = $rowkeluar->jabatan;
                    $pangkatkeluar  = $rowkeluar->pangkat;
                   
                }
      
			    
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td rowspan=\"4\" align=\"right\">
                        <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                        </td>
					<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT </strong></td></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:16px\"><strong>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH</strong></tr>
					<tr><td colspan=\"3\" align=\"center\"><strong>Jalan Ahmad Yani Telepon (0561) 736541 Fax (0561) 738428</strong></tr>
                    <tr><td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><strong>PONTIANAK</strong></td></tr>
					<tr><td colspan=\"4\" align=\"right\">Kode Pos: 78124 &nbsp; &nbsp;</td></tr>
					</table>
					<hr  valign=\"top\" color=\"black\" size=\"3px\" width=\"100%\"> 
					";
			
			$cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                        <tr>
							<td rowspan=\"2\" align=\"right\" width=\"10%\" height=\"50\">&nbsp;</td>
							<td colspan=\"3\" align=\"center\" style=\"font-size:14px\"><b>Realisasi Penerimaan Tahun Anggaran $thn_ang</b></td>
						</tr>
						<tr>
							<td colspan=\"5\" align=\"center\" style=\"font-size:14px\"><b>Periode $tgl_periode1 $buln_prd1 - $tgl_periode2 $buln_prd2 $thn_periode2</b></td>
                        </tr>
						<tr>
						<td colspan=\"5\" align=\"justify\" style=\"font-size:12px\">
						<br>
						SKPD : $id - $nm_skpd
						<br>
						<br>
						
						</td>
                        </tr>
                    </table>
					";
        
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
							<td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
							<td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"30%\" align=\"center\"><b>URAIAN</b></td>
                            <td colspan=\"2\" bgcolor=\"#CCCCCC\" width=\"30%\" align=\"center\"><b>REALISASI TRIWULAN $tw TA $thn_ang</b></td>
							<!--<td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>SISA LEBIH/(KURANG)</b></td>-->
							<td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>KETERANGAN</b></td>
                        </tr>
						<tr>
							<td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>Penerimaan</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>Penyetoran</b></td>     
						</tr>
                     </thead>
                                       
                     <tr>
							<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" >&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" >&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" >&nbsp;</td>
                            <!--<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" >&nbsp;</td>-->
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" >&nbsp;</td>
                     </tr>";
    
	
	$rek_pkb = "'4110101','4110102','4110103','4110104','4110105','4110106','4110107','4110108','4110109','4110110','4110111','4110112'"; 
	$rek_tgk_pkb = "'4110114'";
	$rek_pka = "'4110113'";
	$rek_bbnkb = "'4110201','4110202','4110203','4110204','4110205','4110206','4110207','4110208','4110209','4110210','4110211','4110212'"; 
	$rek_bbnka = "'4110213'"; 
	$rek_pbbkb = "'4110301','4110302','4110303'"; 
	$rek_rokok = "'4110501'"; 
	$rek_papabt = "'4110401','4110402'"; 
	$rek_ret_umum = "'4120101','4120102','4120103','4120104','4120105','4120106','4120107','4120108','4120109'";
	$rek_ret_jasa = "'4120201','4120202','4120203','4120204','4120205','4120206','4120207','4120208','4120209','4120210'";
	$rek_ret_izin ="'4120301','4120302','4120303','4120304','4120305'"; 
	$rek_denda_pkb = "'4140701'";
	$rek_denda_pap = "'4140705'";
	$rek_denda_bbnkb = "'4140702'";
	$rek_pendidikan = "'4141201','4141202','4141203','4141204','4141205'";
	$rek_pihak3 = "'4310302'";
	$rek_penjualan = "'4140101','4140102','4140103','4140104','4140105','4140106','4140107','4140108','4140109','4140110','4140111','4140112','4140113','4140114','4140115','4140116','4140117','4140118'";
	$rek_laba = "'4130101','4130103'";
	$rek_jagir = "'4140201','4140202'";
	$rek_bunga = "'4140301'";
	$rek_denda_terlambat = "'4140611'";
	$rek_pengembalian = "'4141003','4141007','4141009','4141011'";
	$rek_bg_hasil_pjk = "'4210101','4210103','4210104','4210105','4210106'";
	$rek_bg_hasil_bknpjk = "'4210201','4210202','4210203','4210204','4210205'";
	$rek_dau = "'4220101'";
	$rek_dak = "'4230103','4230104','4230105','4230106','4230107','4230109','4230111'";
	$rek_daknf = "'4230201','4230202','4230203','4230206','4230207','4230209','4230210'";
	$rek_hibah = "'4310101'";
	$rek_penyesuaian = "'4340104'";
	$rek_ban_keu = "'4350201'";
	$rek_blud = "'4141501'";
	
	
	//TERIMA
	$sql_terima_pkb = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(nilai), 0) AS nilai 
						FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
						AND kd_rek5 IN ($rek_pkb)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_pkb) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";	
	$hasil_terima_pkb = $this->db->query($sql_terima_pkb);
	$terima_pkb  = $hasil_terima_pkb->row('nilai');
	
	$sql_terima_tgk_pkb = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(nilai), 0) AS nilai 
						FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
						AND kd_rek5 IN ($rek_tgk_pkb)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_tgk_pkb) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z ";				   
					   
	$hasil_terima_tgk_pkb = $this->db->query($sql_terima_tgk_pkb);
	$terima_tgk_pkb  = $hasil_terima_tgk_pkb->row('nilai');
	
	$sql_terima_pka = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(nilai), 0) AS nilai 
						FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
						AND kd_rek5 IN ($rek_pka)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_pka) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
					   
	$hasil_terima_pka = $this->db->query($sql_terima_pka);
	$terima_pka  = $hasil_terima_pka->row('nilai');
	
	$sql_terima_bbnkb = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(nilai), 0) AS nilai 
						FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
						AND kd_rek5 IN ($rek_bbnkb)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_bbnkb) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";				   
					  
	$hasil_terima_bbnkb = $this->db->query($sql_terima_bbnkb);
	$terima_bbnkb  = $hasil_terima_bbnkb->row('nilai');
					   
	$sql_terima_bbnka = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(nilai), 0) AS nilai 
						FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
						AND kd_rek5 IN ($rek_bbnka)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_bbnka) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
					   
	$hasil_terima_bbnka = $this->db->query($sql_terima_bbnka);
	$terima_bbnka  = $hasil_terima_bbnka->row('nilai');
	
	$sql_terima_pbbkb = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(nilai), 0) AS nilai 
						FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
						AND kd_rek5 IN ($rek_pbbkb)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_pbbkb) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_terima_pbbkb = $this->db->query($sql_terima_pbbkb);
	$terima_pbbkb  = $hasil_terima_pbbkb->row('nilai');
	
	$sql_terima_rokok = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(nilai), 0) AS nilai 
						FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
						AND kd_rek5 IN ($rek_rokok)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_rokok) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_terima_rokok = $this->db->query($sql_terima_rokok);
	$terima_rokok  = $hasil_terima_rokok->row('nilai');
	
	$sql_terima_papabt = "SELECT SUM(nilai) nilai FROM (
							SELECT ISNULL(SUM(nilai), 0) AS nilai 
							FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
							AND kd_rek5 IN ($rek_papabt)
							UNION ALL
							select ISNULL(sum(b.rupiah*-1),0) nilai 
							FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
							WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_papabt) AND a.tgl_sts <= '$periode2'
							AND a.jns_trans='3') z";
	$hasil_terima_papabt = $this->db->query($sql_terima_papabt);
	$terima_papabt  = $hasil_terima_papabt->row('nilai');
	
    $sql_terima_ret_umum = "SELECT SUM(nilai) nilai FROM (
							SELECT ISNULL(SUM(nilai), 0) AS nilai 
							FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
							AND kd_rek5 IN ($rek_ret_umum)
							UNION ALL
							select ISNULL(sum(b.rupiah*-1),0) nilai 
							FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
							WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_ret_umum) AND a.tgl_sts <= '$periode2'
							AND a.jns_trans='3') z";
	$hasil_terima_ret_umum = $this->db->query($sql_terima_ret_umum);
	$terima_ret_umum  = $hasil_terima_ret_umum->row('nilai');
		   
	$sql_terima_ret_jasa = "SELECT SUM(nilai) nilai FROM (
							SELECT ISNULL(SUM(nilai), 0) AS nilai 
							FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
							AND kd_rek5 IN ($rek_ret_jasa)
							UNION ALL
							select ISNULL(sum(b.rupiah*-1),0) nilai 
							FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
							WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_ret_jasa) AND a.tgl_sts <= '$periode2'
							AND a.jns_trans='3') z";				   
	$hasil_terima_ret_jasa = $this->db->query($sql_terima_ret_jasa);
	$terima_ret_jasa  = $hasil_terima_ret_jasa->row('nilai');
					   
	$sql_terima_ret_izin = "SELECT SUM(nilai) nilai FROM (
							SELECT ISNULL(SUM(nilai), 0) AS nilai 
							FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
							AND kd_rek5 IN ($rek_ret_izin)
							UNION ALL
							select ISNULL(sum(b.rupiah*-1),0) nilai 
							FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
							WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_ret_izin) AND a.tgl_sts <= '$periode2'
							AND a.jns_trans='3') z";				   
	$hasil_terima_ret_izin = $this->db->query($sql_terima_ret_izin);
	$terima_ret_izin  = $hasil_terima_ret_izin->row('nilai');
	
	$sql_terima_denda_pkb = "SELECT SUM(nilai) nilai FROM (
							SELECT ISNULL(SUM(nilai), 0) AS nilai 
							FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
							AND kd_rek5 IN ($rek_denda_pkb)
							UNION ALL
							select ISNULL(sum(b.rupiah*-1),0) nilai 
							FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
							WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_denda_pkb) AND a.tgl_sts <= '$periode2'
							AND a.jns_trans='3') z";
	$hasil_terima_denda_pkb = $this->db->query($sql_terima_denda_pkb);
	$terima_denda_pkb  = $hasil_terima_denda_pkb->row('nilai');

	$sql_terima_denda_pap = "SELECT SUM(nilai) nilai FROM (
							SELECT ISNULL(SUM(nilai), 0) AS nilai 
							FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
							AND kd_rek5 IN ($rek_denda_pap)
							UNION ALL
							select ISNULL(sum(b.rupiah*-1),0) nilai 
							FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
							WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_denda_pap) AND a.tgl_sts <= '$periode2'
							AND a.jns_trans='3') z";
	$hasil_terima_denda_pap = $this->db->query($sql_terima_denda_pap);
	$terima_denda_pap  = $hasil_terima_denda_pap->row('nilai');
	
    $sql_terima_denda_bbnkb = "SELECT SUM(nilai) nilai FROM (
							SELECT ISNULL(SUM(nilai), 0) AS nilai 
							FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
							AND kd_rek5 IN ($rek_denda_bbnkb)
							UNION ALL
							select ISNULL(sum(b.rupiah*-1),0) nilai 
							FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
							WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_denda_bbnkb) AND a.tgl_sts <= '$periode2'
							AND a.jns_trans='3') z";					   
	$hasil_terima_denda_bbnkb = $this->db->query($sql_terima_denda_bbnkb);
	$terima_denda_bbnkb  = $hasil_terima_denda_bbnkb->row('nilai');
	
    $sql_terima_pendidikan = "SELECT SUM(nilai) nilai FROM (
							SELECT ISNULL(SUM(nilai), 0) AS nilai 
							FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
							AND kd_rek5 IN ($rek_pendidikan)
							UNION ALL
							select ISNULL(sum(b.rupiah*-1),0) nilai 
							FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
							WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_pendidikan) AND a.tgl_sts <= '$periode2'
							AND a.jns_trans='3') z";
	$hasil_terima_pendidikan = $this->db->query($sql_terima_pendidikan);
	$terima_pendidikan  = $hasil_terima_pendidikan->row('nilai');
	   
	$sql_terima_pihak3 = "SELECT SUM(nilai) nilai FROM (
							SELECT ISNULL(SUM(nilai), 0) AS nilai 
							FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
							AND kd_rek5 IN ($rek_pihak3)
							UNION ALL
							select ISNULL(sum(b.rupiah*-1),0) nilai 
							FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
							WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_pihak3) AND a.tgl_sts <= '$periode2'
							AND a.jns_trans='3') z";
	
	$hasil_terima_pihak3 = $this->db->query($sql_terima_pihak3);
	$terima_pihak3  = $hasil_terima_pihak3->row('nilai');

	$sql_terima_penjualan = "SELECT SUM(nilai) nilai FROM (
							SELECT ISNULL(SUM(nilai), 0) AS nilai 
							FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
							AND kd_rek5 IN ($rek_penjualan)
							UNION ALL
							select ISNULL(sum(b.rupiah*-1),0) nilai 
							FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
							WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_penjualan) AND a.tgl_sts <= '$periode2'
							AND a.jns_trans='3') z";
	$hasil_terima_penjualan = $this->db->query($sql_terima_penjualan);
	$terima_penjualan  = $hasil_terima_penjualan->row('nilai');
	
	$sql_terima_blud = "SELECT SUM(nilai) nilai FROM (
							SELECT ISNULL(SUM(nilai), 0) AS nilai 
							FROM tr_terima WHERE kd_skpd = '$kd_skpd' AND tgl_terima <= '$periode2' 
							AND kd_rek5 IN ($rek_blud)
							UNION ALL
							select ISNULL(sum(b.rupiah*-1),0) nilai 
							FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
							WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_blud) AND a.tgl_sts <= '$periode2'
							AND a.jns_trans='3') z";
	$hasil_terima_blud = $this->db->query($sql_terima_blud);
	$terima_blud  = $hasil_terima_blud->row('nilai');
	
	//SETOR
	$sql_setor_pkb = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_pkb)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_pkb) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_pkb = $this->db->query($sql_setor_pkb);
	$setor_pkb  = $hasil_setor_pkb->row('nilai');
	
	$sql_setor_tgk_pkb = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_tgk_pkb)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_tgk_pkb) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_tgk_pkb = $this->db->query($sql_setor_tgk_pkb);
	$setor_tgk_pkb  = $hasil_setor_tgk_pkb->row('nilai');
	
	$sql_setor_pka = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_pka)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_pka) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_pka = $this->db->query($sql_setor_pka);
	$setor_pka  = $hasil_setor_pka->row('nilai');
	
	$sql_setor_bbnkb = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_bbnkb)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_bbnkb) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_bbnkb = $this->db->query($sql_setor_bbnkb);
	$setor_bbnkb  = $hasil_setor_bbnkb->row('nilai');
	
	$sql_setor_bbnka = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_bbnka)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_bbnka) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_bbnka = $this->db->query($sql_setor_bbnka);
	$setor_bbnka  = $hasil_setor_bbnka->row('nilai');
	
	$sql_setor_pbbkb = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_pbbkb)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_pbbkb) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_pbbkb = $this->db->query($sql_setor_pbbkb);
	$setor_pbbkb  = $hasil_setor_pbbkb->row('nilai');
	
	$sql_setor_rokok = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_rokok)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_rokok) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_rokok = $this->db->query($sql_setor_rokok);
	$setor_rokok  = $hasil_setor_rokok->row('nilai');
	
	$sql_setor_papabt = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_papabt)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_papabt) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_papabt = $this->db->query($sql_setor_papabt);
	$setor_papabt  = $hasil_setor_papabt->row('nilai');
	
	$sql_setor_ret_umum = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_ret_umum)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_ret_umum) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_ret_umum = $this->db->query($sql_setor_ret_umum);
	$setor_ret_umum  = $hasil_setor_ret_umum->row('nilai');
	
	$sql_setor_ret_jasa = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_ret_jasa)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_ret_jasa) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_ret_jasa = $this->db->query($sql_setor_ret_jasa);
	$setor_ret_jasa  = $hasil_setor_ret_jasa->row('nilai');
	
	$sql_setor_ret_izin = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_ret_izin)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_ret_izin) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_ret_izin = $this->db->query($sql_setor_ret_izin);
	$setor_ret_izin  = $hasil_setor_ret_izin->row('nilai');
	
	$sql_setor_denda_pkb = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_denda_pkb)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_denda_pkb) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_denda_pkb = $this->db->query($sql_setor_denda_pkb);
	$setor_denda_pkb  = $hasil_setor_denda_pkb->row('nilai');
	
	$sql_setor_denda_pap = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_denda_pap)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_denda_pap) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_denda_pap = $this->db->query($sql_setor_denda_pap);
	$setor_denda_pap  = $hasil_setor_denda_pap->row('nilai');
	
	$sql_setor_denda_bbnkb = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_denda_bbnkb)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_denda_bbnkb) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_denda_bbnkb = $this->db->query($sql_setor_denda_bbnkb);
	$setor_denda_bbnkb  = $hasil_setor_denda_bbnkb->row('nilai');
	
	$sql_setor_pendidikan = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_pendidikan)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_pendidikan) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	
	$hasil_setor_pendidikan = $this->db->query($sql_setor_pendidikan);
	$setor_pendidikan  = $hasil_setor_pendidikan->row('nilai');
	
	$sql_setor_pihak3 = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_pihak3)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_pihak3) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_pihak3 = $this->db->query($sql_setor_pihak3);
	$setor_pihak3  = $hasil_setor_pihak3->row('nilai');
	
	$sql_setor_penjualan = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_penjualan)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_penjualan) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_penjualan = $this->db->query($sql_setor_penjualan);
	$setor_penjualan  = $hasil_setor_penjualan->row('nilai');
	
	$sql_setor_laba = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_laba)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_laba) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_laba = $this->db->query($sql_setor_laba);
	$setor_laba  = $hasil_setor_laba->row('nilai');
	
	$sql_setor_jagir = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_jagir)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_jagir) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_jagir = $this->db->query($sql_setor_jagir);
	$setor_jagir  = $hasil_setor_jagir->row('nilai');
	
	$sql_setor_bunga = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_bunga)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_bunga) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	
	$hasil_setor_bunga = $this->db->query($sql_setor_bunga);
	$setor_bunga  = $hasil_setor_bunga->row('nilai');
	
	$sql_setor_denda_terlambat = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_denda_terlambat)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_denda_terlambat) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_denda_terlambat = $this->db->query($sql_setor_denda_terlambat);
	$setor_denda_terlambat  = $hasil_setor_denda_terlambat->row('nilai');
	
	$sql_setor_pengembalian = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_pengembalian)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_pengembalian) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_pengembalian = $this->db->query($sql_setor_pengembalian);
	$setor_pengembalian  = $hasil_setor_pengembalian->row('nilai');
	
	$sql_setor_bg_hasil_pjk = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_bg_hasil_pjk)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_bg_hasil_pjk) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_bg_hasil_pjk = $this->db->query($sql_setor_bg_hasil_pjk);
	$setor_bg_hasil_pjk  = $hasil_setor_bg_hasil_pjk->row('nilai');
	
	$sql_setor_bg_hasil_bknpjk = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_bg_hasil_bknpjk)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_bg_hasil_bknpjk) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_bg_hasil_bknpjk = $this->db->query($sql_setor_bg_hasil_bknpjk);
	$setor_bg_hasil_bknpjk  = $hasil_setor_bg_hasil_bknpjk->row('nilai');
	
	$sql_setor_dau = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_dau)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_dau) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_dau = $this->db->query($sql_setor_dau);
	$setor_dau  = $hasil_setor_dau->row('nilai');
	
	$sql_setor_dak = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_dak)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_dak) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_dak = $this->db->query($sql_setor_dak);
	$setor_dak  = $hasil_setor_dak->row('nilai');
	
	$sql_setor_daknf = "SELECT ISNULL(sum(a.rupiah),0) as nilai from trdkasin_pkd a inner join trhkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts where a.kd_skpd = '$kd_skpd' and b.jns_trans in ('4','2') and left(a.kd_rek5,1)='4' AND b.tgl_sts <= '$periode2' AND a.kd_rek5 IN ($rek_daknf) ";
	$hasil_setor_daknf = $this->db->query($sql_setor_daknf);
	
	$sql_setor_daknf = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_daknf)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_daknf) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_daknf = $this->db->query($sql_setor_daknf);
	$setor_daknf  = $hasil_setor_daknf->row('nilai');
	
	$sql_setor_hibah = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_hibah)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_hibah) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_hibah = $this->db->query($sql_setor_hibah);
	$setor_hibah  = $hasil_setor_hibah->row('nilai');
	
	$sql_setor_penyesuaian = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_penyesuaian)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_penyesuaian) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_penyesuaian = $this->db->query($sql_setor_penyesuaian);
	$setor_penyesuaian  = $hasil_setor_penyesuaian->row('nilai');
	
	$sql_setor_ban_keu = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_ban_keu)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_ban_keu) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_ban_keu = $this->db->query($sql_setor_ban_keu);
	$setor_ban_keu  = $hasil_setor_ban_keu->row('nilai');
	
	$sql_setor_blud = "SELECT SUM(nilai) nilai FROM (
						SELECT ISNULL(SUM(b.rupiah), 0) AS nilai 
						from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts 
						WHERE a.kd_skpd = '$kd_skpd' and a.jns_trans in ('4','2') and left(b.kd_rek5,1)='4' AND a.tgl_sts <= '$periode2' AND b.kd_rek5 IN ($rek_blud)
						UNION ALL
						select ISNULL(sum(b.rupiah*-1),0) nilai 
						FROM trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts
						WHERE b.kd_skpd='$kd_skpd' AND b.kd_rek5 IN ($rek_blud) AND a.tgl_sts <= '$periode2'
						AND a.jns_trans='3') z";
	$hasil_setor_blud = $this->db->query($sql_setor_blud);
	$setor_blud  = $hasil_setor_blud->row('nilai');
	
	if($kd_skpd<>'3.13.01.17'){
		
		$Realisasi_Penerimaan = "SELECT 2 nomor, 0 jns,0 urut,'- Realisasi Penerimaan' nama,ISNULL(SUM(nilai), 0) AS nilai 
						 FROM ( SELECT $terima_pkb+$terima_tgk_pkb+$terima_pka+$terima_bbnkb+$terima_bbnka+$terima_pbbkb+$terima_rokok+$terima_papabt+$terima_ret_umum+$terima_ret_jasa+$terima_ret_izin+$terima_denda_pkb+$terima_denda_pap+$terima_denda_bbnkb+$terima_pendidikan+$terima_pihak3+$terima_penjualan+$terima_blud nilai
							   ) a 
					   UNION ALL 
					   SELECT 2 nomor, 1 jns,1 urut,'- Pajak Kendaraan Bermotor' nama, $terima_pkb nilai 
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 2 urut,'- Tunggakan PKB' nama, $terima_tgk_pkb nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 3 urut,'- Pajak Kendaraan di atas Air' nama, $terima_pka nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 4 urut,'- Bea Balik Nama Kendaraan Bermotor' nama, $terima_bbnkb nilai 
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 5 urut,'- Bea Balik Nama Kendaraan diatas Air' nama, $terima_bbnka nilai 
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 6 urut,'- Pajak Bahan Bakar Kendaraan Bermotor' nama, $terima_pbbkb nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 7 urut,'- Pajak Rokok' nama, $terima_rokok nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 8 urut,'- PAP-ABT' nama, $terima_papabt nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 9 urut,'- Retribusi Jasa Umum' nama, $terima_ret_umum nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 10 urut,'- Retribusi Jasa Usaha' nama, $terima_ret_jasa nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 11 urut,'- Retribusi Perizinan Tertentu' nama, $terima_ret_izin nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 12 urut,'- Pendapatan Denda Pajak Kendaraan Bermotor' nama, $terima_denda_pkb nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 13 urut,'- Pendapatan Denda Pajak Air Permukaan' nama, $terima_denda_pap nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 14 urut,'- Pendapatan Denda Pajak Bea Balik Nama Kendaraan Bermotor' nama, $terima_denda_bbnkb nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 15 urut,'- Pendapatan dari Penyelenggaraan Pendidikan dan Pelatihan' nama, $terima_pendidikan nilai 
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 16 urut,'- Partisipasi Pihak Ketiga' nama, $terima_pihak3 nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 17 urut,'- Hasil Penjualan Aset Daerah Yang Tidak Dipisahkan' nama, $terima_penjualan nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 18 urut,'- Pendapatan BLUD' nama, $terima_blud nilai";
		
		$Saldo_Penerimaan_Yang_Belum_Di_Setor = "select 5 nomor, 0 jns, 0 urut, '- Saldo Penerimaan Yang Belum Di Setor' nama,  (nilai_terima-nilai_resti) - (nilai_setor-nilai_resti) as nilai 
						   from (select isnull(sum(nilai),0) nilai_terima, (select isnull(sum(a.rupiah),0) nilai_setor from trdkasin_pkd a INNER JOIN trhkasin_pkd b 
									on a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
									where a.kd_skpd='$kd_skpd' AND b.jns_trans='4' AND LEFT(a.kd_rek5,1)='4' AND b.tgl_sts <= '$periode2') as  nilai_setor,
									(select isnull(sum(a.rupiah),0) nilai_setor from trdkasin_pkd a INNER JOIN trhkasin_pkd b 
									on a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
									where a.kd_skpd='$kd_skpd' AND b.jns_trans='3' AND LEFT(a.kd_rek5,1)='4' AND b.tgl_sts <= '$periode2') as  nilai_resti
									from tr_terima where kd_skpd='$kd_skpd' and tgl_terima <= '$periode2' 
								) a";
						   
		$akuntansi = "SELECT 2 nomor, 0 jns,0 urut,'- Realisasi Penerimaan' nama,ISNULL(SUM(nilai), 0) AS nilai 
					FROM( SELECT $setor_pkb+$setor_tgk_pkb+$setor_pka+$setor_bbnkb+$setor_bbnka+$setor_pbbkb+$setor_rokok+$setor_papabt+$setor_ret_umum+$setor_ret_jasa+$setor_ret_izin+$setor_denda_pkb+$setor_denda_pap+$setor_denda_bbnkb+$setor_pendidikan+$setor_pihak3+$setor_penjualan+$setor_blud nilai
						) a 

					   UNION ALL 
					   SELECT 2 nomor, 1 jns,1 urut,'- Pajak Kendaraan Bermotor' nama, $setor_pkb nilai 
					   UNION ALL
					   SELECT 2 nomor, 1 jns,2 urut, '- Tunggakan PKB' nama, $setor_tgk_pkb nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,3 urut, '- Pajak Kendaraan di atas Air' nama, $setor_pka nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,4 urut, '- Bea Balik Nama Kendaraan Bermotor' nama, $setor_bbnkb nilai 
					   UNION ALL
					   SELECT 2 nomor, 1 jns,5 urut, '- Bea Balik Nama Kendaraan diatas Air' nama, $setor_bbnka nilai 
					   UNION ALL
					   SELECT 2 nomor, 1 jns,6 urut, '- Pajak Bahan Bakar Kendaraan Bermotor' nama, $setor_pbbkb nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,7 urut, '- Pajak Rokok' nama, $setor_rokok nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,8 urut, '- PAP-ABT' nama, $setor_papabt nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,9 urut, '- Retribusi Jasa Umum' nama, $setor_ret_umum nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,10 urut, '- Retribusi Jasa Usaha' nama, $setor_ret_jasa nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,11 urut, '- Retribusi Perizinan Tertentu' nama, $setor_ret_izin nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,12 urut, '- Pendapatan Denda Pajak Kendaraan Bermotor' nama, $setor_denda_pkb nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,13 urut, '- Pendapatan Denda Pajak Air Permukaan' nama, $setor_denda_pap nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,14 urut, '- Pendapatan Denda Pajak Bea Balik Nama Kendaraan Bermotor' nama, $setor_denda_bbnkb nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,15 urut, '- Pendapatan dari Penyelenggaraan Pendidikan dan Pelatihan' nama, $setor_pendidikan nilai 
					   UNION ALL
					   SELECT 2 nomor, 1 jns,16 urut, '- Partisipasi Pihak Ketiga' nama, $setor_pihak3 nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,17 urut, '- Hasil Penjualan Aset Daerah Yang Tidak Dipisahkan' nama, $setor_penjualan nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,18 urut, '- Pendapatan BLUD' nama, $setor_blud nilai";
		
	}else{
		
			$Realisasi_Penerimaan = "SELECT 2 nomor, 0 jns,0 urut,'- Realisasi Penerimaan' nama,ISNULL(SUM(nilai), 0) AS nilai 
					FROM( SELECT $setor_pkb+$setor_tgk_pkb+$setor_pka+$setor_bbnkb+$setor_bbnka+$setor_pbbkb+$setor_rokok+$setor_papabt+$setor_ret_umum+$setor_ret_jasa+$setor_ret_izin+$setor_denda_pkb+$setor_denda_pap+$setor_denda_bbnkb+$setor_pendidikan+$setor_pihak3+$setor_penjualan+$setor_laba+$setor_jagir+$setor_bunga+$setor_denda_terlambat+$setor_pengembalian+$setor_bg_hasil_pjk+$setor_bg_hasil_bknpjk+$setor_dau+$setor_dak+$setor_daknf+$setor_hibah+$setor_penyesuaian+$setor_ban_keu nilai
						) a 

				   UNION ALL 
				   SELECT 2 nomor, 1 jns,1 urut,'- Pajak Kendaraan Bermotor' nama, $setor_pkb nilai 
				   UNION ALL
				   SELECT 2 nomor, 1 jns,2 urut, '- Tunggakan PKB' nama, $setor_tgk_pkb nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns,3 urut, '- Pajak Kendaraan di atas Air' nama, $setor_pka nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns,4 urut, '- Bea Balik Nama Kendaraan Bermotor' nama, $setor_bbnkb nilai 
				   UNION ALL
				   SELECT 2 nomor, 1 jns,5 urut, '- Bea Balik Nama Kendaraan diatas Air' nama, $setor_bbnka nilai 
				   UNION ALL
				   SELECT 2 nomor, 1 jns,6 urut, '- Pajak Bahan Bakar Kendaraan Bermotor' nama, $setor_pbbkb nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns,7 urut, '- Pajak Rokok' nama, $setor_rokok nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns,8 urut, '- PAP-ABT' nama, $setor_papabt nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns,9 urut, '- Retribusi Jasa Umum' nama, $setor_ret_umum nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns,10 urut, '- Retribusi Jasa Usaha' nama, $setor_ret_jasa nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns,11 urut, '- Retribusi Perizinan Tertentu' nama, $setor_ret_izin nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns,12 urut, '- Pendapatan Denda Pajak Kendaraan Bermotor' nama, $setor_denda_pkb nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns,13 urut, '- Pendapatan Denda Pajak Air Permukaan' nama, $setor_denda_pap nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns,14 urut, '- Pendapatan Denda Pajak Bea Balik Nama Kendaraan Bermotor' nama, $setor_denda_bbnkb nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns,15 urut, '- Pendapatan dari Penyelenggaraan Pendidikan dan Pelatihan' nama, $setor_pendidikan nilai 
				   UNION ALL
				   SELECT 2 nomor, 1 jns,16 urut, '- Partisipasi Pihak Ketiga' nama, $setor_pihak3 nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns,17 urut, '- Hasil Penjualan Aset Daerah Yang Tidak Dipisahkan' nama, $setor_penjualan nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns, 18 urut,'- Bagian Laba Atas Penyertaan Modal Pada Perusahaan Milik Daerah/BUMD' nama, $setor_laba nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns, 19 urut,'- Penerimaan Jasa Giro' nama, $setor_jagir nilai
				   UNION ALL 
				   SELECT 2 nomor, 1 jns, 20 urut,'- Pendapatan Bunga' nama, $setor_bunga nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns, 21 urut,'- Pendapatan Denda Atas Keterlambatan Pelaksanaan Pekerjaan' nama, $setor_denda_terlambat nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns, 22 urut,'- Pendapatan Dari Pengembalian' nama, $setor_pengembalian nilai
				   UNION ALL
			       SELECT 2 nomor, 1 jns, 23 urut,'- Bagi Hasil Pajak' nama, $setor_bg_hasil_pjk nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns, 24 urut,'- Bagi Hasil Bukan Pajak/Sumber Daya Alam' nama, $setor_bg_hasil_bknpjk nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns, 25 urut,'- Dana Alokasi Umum' nama, $setor_dau nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns, 26 urut,'- Dana Alokasi Khusus Fisik' nama, $setor_dak nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns, 27 urut,'- Dana Alokasi Khusus Non Fisik' nama, $setor_daknf nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns, 28 urut,'- Pendapatan Hibah Dari Pemerintah' nama, $setor_hibah nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns, 29 urut,'- Dana Penyesuaian' nama, $setor_penyesuaian nilai
				   UNION ALL
				   SELECT 2 nomor, 1 jns, 30 urut,'- Bantuan Keuangan Dari Kabupaten' nama, $setor_ban_keu nilai";	
			
			$Saldo_Penerimaan_Yang_Belum_Di_Setor = "select 5 nomor, 0 jns, 0 urut, '- Saldo Penerimaan Yang Belum Di Setor' nama,  nilai_terima-nilai_setor as nilai 
						from (
						select isnull(sum(a.rupiah),0) nilai_setor,(select isnull(sum(a.rupiah),0) nilai_setor from trdkasin_pkd a INNER JOIN trhkasin_pkd b 
						on a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
						where a.kd_skpd='$kd_skpd' AND b.jns_trans='4' AND LEFT(a.kd_rek5,1)='4' AND b.tgl_sts <= '$periode2'
						) nilai_terima 
						from trdkasin_pkd a INNER JOIN trhkasin_pkd b 
						on a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts
						where a.kd_skpd='$kd_skpd' AND b.jns_trans='4' AND LEFT(a.kd_rek5,1)='4' AND b.tgl_sts <= '$periode2' ) a";
						
			$akuntansi = "SELECT 2 nomor, 0 jns,0 urut,'- Realisasi Penerimaan' nama,ISNULL(SUM(nilai), 0) AS nilai 
					FROM( SELECT $setor_pkb+$setor_tgk_pkb+$setor_pka+$setor_bbnkb+$setor_bbnka+$setor_pbbkb+$setor_rokok+$setor_papabt+$setor_ret_umum+$setor_ret_jasa+$setor_ret_izin+$setor_denda_pkb+$setor_denda_pap+$setor_denda_bbnkb+$setor_pendidikan+$setor_pihak3+$setor_penjualan+$setor_laba+$setor_jagir+$setor_bunga+$setor_denda_terlambat+$setor_pengembalian+$setor_bg_hasil_pjk+$setor_bg_hasil_bknpjk+$setor_dau+$setor_dak+$setor_daknf+$setor_hibah+$setor_penyesuaian+$setor_ban_keu nilai
						) a 

					  UNION ALL 
					   SELECT 2 nomor, 1 jns,1 urut,'- Pajak Kendaraan Bermotor' nama, $setor_pkb nilai 
					   UNION ALL
					   SELECT 2 nomor, 1 jns,2 urut, '- Tunggakan PKB' nama, $setor_tgk_pkb nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,3 urut, '- Pajak Kendaraan di atas Air' nama, $setor_pka nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,4 urut, '- Bea Balik Nama Kendaraan Bermotor' nama, $setor_bbnkb nilai 
					   UNION ALL
					   SELECT 2 nomor, 1 jns,5 urut, '- Bea Balik Nama Kendaraan diatas Air' nama, $setor_bbnka nilai 
					   UNION ALL
					   SELECT 2 nomor, 1 jns,6 urut, '- Pajak Bahan Bakar Kendaraan Bermotor' nama, $setor_pbbkb nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,7 urut, '- Pajak Rokok' nama, $setor_rokok nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,8 urut, '- PAP-ABT' nama, $setor_papabt nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,9 urut, '- Retribusi Jasa Umum' nama, $setor_ret_umum nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,10 urut, '- Retribusi Jasa Usaha' nama, $setor_ret_jasa nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,11 urut, '- Retribusi Perizinan Tertentu' nama, $setor_ret_izin nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,12 urut, '- Pendapatan Denda Pajak Kendaraan Bermotor' nama, $setor_denda_pkb nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,13 urut, '- Pendapatan Denda Pajak Air Permukaan' nama, $setor_denda_pap nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,14 urut, '- Pendapatan Denda Pajak Bea Balik Nama Kendaraan Bermotor' nama, $setor_denda_bbnkb nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,15 urut, '- Pendapatan dari Penyelenggaraan Pendidikan dan Pelatihan' nama, $setor_pendidikan nilai 
					   UNION ALL
					   SELECT 2 nomor, 1 jns,16 urut, '- Partisipasi Pihak Ketiga' nama, $setor_pihak3 nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns,17 urut, '- Hasil Penjualan Aset Daerah Yang Tidak Dipisahkan' nama, $setor_penjualan nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 18 urut,'- Bagian Laba Atas Penyertaan Modal Pada Perusahaan Milik Daerah/BUMD' nama, $setor_laba nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 19 urut,'- Penerimaan Jasa Giro' nama, $setor_jagir nilai
					   UNION ALL 
					   SELECT 2 nomor, 1 jns, 20 urut,'- Pendapatan Bunga' nama, $setor_bunga nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 21 urut,'- Pendapatan Denda Atas Keterlambatan Pelaksanaan Pekerjaan' nama, $setor_denda_terlambat nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 22 urut,'- Pendapatan Dari Pengembalian' nama, $setor_pengembalian nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 23 urut,'- Bagi Hasil Pajak' nama, $setor_bg_hasil_pjk nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 24 urut,'- Bagi Hasil Bukan Pajak/Sumber Daya Alam' nama, $setor_bg_hasil_bknpjk nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 25 urut,'- Dana Alokasi Umum' nama, $setor_dau nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 26 urut,'- Dana Alokasi Khusus Fisik' nama, $setor_dak nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 27 urut,'- Dana Alokasi Khusus Non Fisik' nama, $setor_daknf nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 28 urut,'- Pendapatan Hibah Dari Pemerintah' nama, $setor_hibah nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 29 urut,'- Dana Penyesuaian' nama, $setor_penyesuaian nilai
					   UNION ALL
					   SELECT 2 nomor, 1 jns, 30 urut,'- Bantuan Keuangan Dari Kabupaten' nama, $setor_ban_keu nilai";
	}
	
	
	$sql="SELECT n.kode, n.nomor, n.jns, n.nama, n.jns, n.nilai_unit, w.nilai_ak, (nilai_ak - nilai_unit) sisa
		  FROM (SELECT ROW_NUMBER () OVER (ORDER BY b.nomor) AS kode,b.nomor, b.urut, b.nama,b.jns,b.nilai_unit
				FROM (	SELECT 1 nomor,0 jns,0 urut,'Saldo BKU' nama,SUM (CASE WHEN jns = 1 THEN a.nilai ELSE 0 END) AS nilai_unit
						FROM( SELECT 1 nomor, 1 jns,1 urut, '- Kas Tunai' nama, 0 nilai
							  UNION ALL
							  SELECT 1 nomor, 1 jns,2 urut, '- Saldo Bank' nama, 0 nilai
							) a
							  UNION ALL
							  SELECT 1 nomor, 1 jns,1 urut, '- Kas Tunai' nama, 0 nilai
							  UNION ALL
							  SELECT 1 nomor, 1 jns,2 urut, '- Saldo Bank' nama, 0 nilai
							  UNION ALL

						 --TOTAL PENERIMAAN-- 
						 $Realisasi_Penerimaan
					   
					   UNION ALL
					   
					   -- Kas di Bendahara Penerimaan  
					   select 3 nomor,0 jns,0 urut,'- Kas di Bendahara Penerimaan' nama,sum(debet-kredit)  nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where LEFT(a.kd_rek5,5)='11102' and a.kd_unit='$kd_skpd' AND b.tgl_voucher <= '$periode2'
							 
					   UNION ALL
				 
					   -- Lebih Setor(Pendapatan dari Pengembalian Lain-lain)  
					   SELECT 4 nomor, 0 jns,0 urut, '- Lebih Setor(Pendapatan dari Pengembalian Lain-lain)' nama, 0 nilai 
					   
					   UNION ALL
					   
					   -- Saldo Penerimaan Yang Belum Di Setor
					   $Saldo_Penerimaan_Yang_Belum_Di_Setor

    				   UNION ALL
						
					  -- Saldo Awal Penerimaan Tahun Lalu
					  select 6 nomor, 0 jns,0 urut, '- Saldo Awal Penerimaan Tahun Lalu' nama , isnull(sum(0),0) nilai from trdkasin_pkd a inner join trhkasin_pkd b			  
                      on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd WHERE b.jns_trans='2' AND a.kd_skpd='$kd_skpd' AND b.tgl_sts <= '$periode2'
					  
					  UNION ALL
						
					  -- Setoran UYHD Penerimaan Tahun Lalu
					  select 7 nomor, 0 jns,0 urut, '- Setoran UYHD Penerimaan Tahun Lalu' nama , isnull(sum(0),0) nilai from trdkasin_pkd a inner join trhkasin_pkd b			  
                      on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd WHERE b.jns_trans='2' AND a.kd_skpd='$kd_skpd' AND b.tgl_sts <= '$periode2'
					)b
				)n 

			inner join 

			(select ROW_NUMBER() OVER (ORDER BY c.nomor) AS kode, urut, nilai_ak 
			  from(select 1 nomor, 0 jns, 0 urut,'Saldo BKU' nama, sum(case when jns=1 then a.nilai else 0 end) as nilai_ak 
					FROM ( select 1 nomor, 1 jns, 1 urut, '- Kas Tunai' nama, 0 nilai 
						   UNION ALL 
						   select 1 nomor, 1 jns, 2 urut,'- Saldo Bank' nama, 0 nilai 
						  ) a 
					UNION ALL 
					select 1 nomor, 1 jns, 1 urut,'- Kas Tunai' nama, 0 nilai 
					UNION ALL 
					select 1 nomor, 1 jns, 2 urut,'- Saldo Bank' nama, 0 nilai 
					UNION ALL 
					
					$akuntansi
					  
					 UNION ALL
					  
					  -- Kas di Bendahara Penerimaan  
					  select 3 nomor,0 jns,0 urut,'- Kas di Bendahara Penerimaan' nama, sum(0) nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where LEFT(a.kd_rek5,5)='11102' and a.kd_unit='$kd_skpd' AND b.tgl_voucher <= '$periode2' 
								
					  UNION ALL
					  
					  -- Lebih Setor(Pendapatan dari Pengembalian Lain-lain)  
					  select 4 nomor,0 jns,0 urut,'- Lebih Setor(Pendapatan dari Pengembalian Lain-lain)' nama, 0 nilai 
					  
					  UNION ALL
					  
					  -- Saldo Penerimaan Yang Belum Di Setor
					   select 5 nomor, 0 jns, 0 urut, '- Saldo Penerimaan Yang Belum Di Setor' nama,  0 as nilai 
							
					  UNION ALL
					  
					  -- Saldo Awal Penerimaan Tahun Lalu
					  select 6 nomor, 0 jns,0 urut, '- Saldo Awal Penerimaan Tahun Lalu' nama , isnull(sum(a.rupiah),0) nilai from trdkasin_pkd a inner join trhkasin_pkd b			  
                      on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd WHERE b.jns_trans='2' AND a.kd_skpd='$kd_skpd' AND b.tgl_sts <= '$periode2'
					  
					  UNION ALL
					  
					  -- Setoran UYHD Penerimaan Tahun Lalu
					  select 7 nomor, 0 jns,0 urut, '- Setoran UYHD Penerimaan Tahun Lalu' nama , isnull(sum(a.rupiah),0) nilai from trdkasin_pkd a inner join trhkasin_pkd b			  
                      on a.no_sts=b.no_sts and a.kd_skpd=b.kd_skpd WHERE b.jns_trans='2' AND a.kd_skpd='$kd_skpd' AND b.tgl_sts <= '$periode2'
				)c 
			)w 
			on n.kode=w.kode and n.urut=w.urut
			order by n.kode";
																			
	
				$sql1=$this->db->query($sql);
                 foreach ($sql1->result() as $rowsql)
                {
                    $kode       = $rowsql->kode;
                    $nomor      = $rowsql->nomor;
                    $jns        = $rowsql->jns;
                    $nama       = $rowsql->nama;
                    $nilai_unit = $rowsql->nilai_unit;
                    $nilai_ak   = $rowsql->nilai_ak;
                    $sisa       = $rowsql->sisa;

				if($nilai_unit<0 || $nilai_ak<0){
					$a        = "(&nbsp";
					$b        = "&nbsp)";
					$jml_unit = number_format($nilai_unit*-1,"2",",",".");
					$jml_ak   = number_format($nilai_ak*-1,"2",",",".");
					$jml_sisa = number_format($sisa*-1,"2",",",".");
					
					
				}else{
					$a        = "";
					$b        = "";
					$jml_unit = number_format($nilai_unit,"2",",",".");
					$jml_ak   = number_format($nilai_ak,"2",",",".");
					$jml_sisa = number_format($sisa,"2",",",".");
				}	
					
					if($jns==0){
							
							   $cRet .="<tr>
											<td align=\"center\"><b>$nomor</b></td>
											<td align=\"left\"><b>$nama</b></td>
											<td align=\"right\"><b>$a$jml_unit$b</b></td>
											<td align=\"right\"><b>$a$jml_ak$b</b></td>
											<!--<td align=\"right\"><b>$a$jml_sisa$b</b></td>-->
											<td></td>
										</tr>"; 
					}else{
							$cRet .="<tr>
											<td align=\"center\"></td>
											<td align=\"left\">$nama</td>
											<td align=\"right\">$a$jml_unit$b</td>
											<td align=\"right\">$a$jml_ak$b</td>
											<!--<td align=\"right\">$a$jml_sisa$b</td>-->
											<td></td>
										</tr>";
					}
                 
				}	
					
        $cRet .='</table>';
       
		 
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = "PENERIMAAN";
        $this->template->set('title', 'PENERIMAAN $cbulan');  
         switch ($cetak)
        {
            case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
            case 1;
				echo "<title>PENERIMAAN $cbulan</title>";
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
	
	function rekap_sisa_kas_pengeluaran($bulan='',$ctk='',$anggaran=''){
        $lntahunang = $this->session->userdata('pcThang');       
				
		$cRet ="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Kode SKPD</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Nama SKPD</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Anggaran</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>SP2D</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>SPJ</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Sisa Kas</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Contra Pos</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Potongan yang Belum Disetor</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Kas Bendahara</b></td>
				</tr>
				</thead>";
				
					$sql="SELECT * FROM rekap_sisa_kas_pengeluaran ($bulan,$anggaran,$lntahunang) order by kd_skpd";
						
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_skpd = $row->kd_skpd;
					   $nm_skpd = $row->nm_skpd;
                       $anggaran = $row->anggaran;
                       $sp2d = $row->sp2d;
                       $spj = $row->spj;
                       $sisa_kas = $row->sisa_kas;
                       $cp = $row->cp;
                       $pot = $row->pot;
                       $kas_ben = $row->kas_ben;
					   
					   $cRet .='<tr>
							   <td align="center" valign="center">'.$kd_skpd.'</td> 
							   <td align="left"  valign="center">'.$nm_skpd.'</td> 
							   <td align="right" valign="center">'.number_format($anggaran, "2", ",", ".").'</td> 
							   <td align="right" valign="center">'.number_format($sp2d, "2", ",", ".").'</td> 
							   <td align="right" valign="center">'.number_format($spj, "2", ",", ".").'</td> 
							   <td align="right" valign="center">'.number_format($sisa_kas, "2", ",", ".").'</td> 
							   <td align="right" valign="center">'.number_format($cp, "2", ",", ".").'</td> 
							   <td align="right" valign="center">'.number_format($pot, "2", ",", ".").'</td> 
							   <td align="right" valign="center">'.number_format($kas_ben, "2", ",", ".").'</td> 
							</tr>';
					   
					}
					$hasil->free_result();  
					
					
			$cRet .="</table>";
			
			$data['prev']= $cRet;    
            $judul='Rekap_pengeluaran';
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
	
	function rekap_sisa_kas_penerimaan($bulan='',$ctk='',$anggaran=''){
        $lntahunang = $this->session->userdata('pcThang');       
				
		$cRet ="<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Kode SKPD</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Nama SKPD</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Anggaran</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Penerimaan</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Penyetoran</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Sisa Kas</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Penyetoran Tahun Lalu</b></td>
                    <td  align=\"center\" bgcolor=\"#CCCCCC\"><b>Kas Bendahara</b></td>
				</tr>
				</thead>";
				
					$sql="SELECT * FROM rekap_sisa_kas_penerimaan ($bulan,$anggaran,$lntahunang) order by kd_skpd";
						
					$hasil = $this->db->query($sql);
                    foreach ($hasil->result() as $row)
                    {
					   $kd_skpd = $row->kd_skpd;
					   $nm_skpd = $row->nm_skpd;
                       $anggaran = $row->anggaran;
                       $terima = $row->terima;
                       $setor = $row->setor;
                       $sisa_kas = $row->sisa_kas;
                       $setor_lalu = $row->setor_lalu;
                       $kas_ben = $row->kas_ben;
					   
					   $cRet .='<tr>
							   <td align="center" valign="center">'.$kd_skpd.'</td> 
							   <td align="left"  valign="center">'.$nm_skpd.'</td> 
							   <td align="right" valign="center">'.number_format($anggaran, "2", ",", ".").'</td> 
							   <td align="right" valign="center">'.number_format($terima, "2", ",", ".").'</td> 
							   <td align="right" valign="center">'.number_format($setor, "2", ",", ".").'</td> 
							   <td align="right" valign="center">'.number_format($sisa_kas, "2", ",", ".").'</td> 
							   <td align="right" valign="center">'.number_format($setor_lalu, "2", ",", ".").'</td> 
							   <td align="right" valign="center">'.number_format($kas_ben, "2", ",", ".").'</td> 
							</tr>';
					   
					}
					$hasil->free_result();  
					
					
			$cRet .="</table>";
			
			$data['prev']= $cRet;    
            $judul='Rekap_pengeluaran';
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