<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Register extends CI_Controller {


	public $ppkd1 = "5.02.0.00.0.00.02.0000";
	public $ppkd2 = "5.02.0.00.0.00.02.0000";
	
	function __contruct()
	{	
		parent::__construct();
  
	}
	function penerimaan()
    {
        $data['page_title']= 'Register Koreksi Penerimaan';
        $this->template->set('title', 'Register Koreksi Penerimaan');   
        $this->template->load('template','register_koreksi/reg_koreksipenerimaan',$data) ; 
    }
    function load_ttd($ttd){
        $kd_skpd = $this->session->userdata('kdskpd');
		//$kode_skpd = $this->input->post('kdskpd');
		$sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '$kd_skpd' and kode in ('$ttd','PA')";
		
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

    // Penerimaan
    function ctk_koreksi_penerimaan($ctk='',$ttd='',$tgl='',$no_halaman=''){
		
		$lcttd = str_replace('123456789',' ',$this->uri->segment(4));
		$cetk = $this->uri->segment(9);
		
		
		
		if($cetk=='1'){
			$where = "where tanggal = '$tgl'";
			$where2 = "where d.tanggal <'$tgl'";
			
			$tanggala = $this->tukd_model->tanggal_format_indonesia($tgl);
			
			$z = strtotime("-1 day", strtotime($tgl));
			$n = date("Y-m-d", $z);
			$tanggalsbl = $this->tukd_model->tanggal_format_indonesia($n);
		
		}else{
			
			
			$tgl1 = $this->uri->segment(7);
			$tgl2 = $this->uri->segment(8);
		
			$where = "where tanggal between '$tgl1' AND '$tgl2'";
			$where2 = "where d.tanggal <'$tgl1'";
		
			$tanggal1 = $this->tukd_model->tanggal_format_indonesia($tgl1);
			
			$z = strtotime("-1 day", strtotime($tgl1));
			$n = date("Y-m-d", $z);
			$tanggalsbl = $this->tukd_model->tanggal_format_indonesia($n);
		
			$tanggal2 = $this->tukd_model->tanggal_format_indonesia($tgl2);
			
			$z2 = strtotime("-1 day", strtotime($tgl2));
			$n2 = date("Y-m-d", $z2);
			$tanggalsbl2 = $this->tukd_model->tanggal_format_indonesia($n2);
		}
		
		
		
		$thn_ang = $this->session->userdata('pcThang');
		$prv = $this->db->query("SELECT TOP 1 * from sclient");
		$prvn = $prv->row();          
		$prov = $prvn->provinsi;         
		$daerah = $prvn->daerah;
		
		$sqlttd1="SELECT TOP 1 nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$lcttd'";
		$sqlttd=$this->db->query($sqlttd1);
		 foreach ($sqlttd->result() as $rowttd)
			{
				$nip=$rowttd->nip;                    
				$nama= $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat=$rowttd->pangkat;
			}
		
			$cRet = '';
            $cRet .="<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                       <tr>
                           <td rowspan=\"5\" align=\"left\" width=\"7%\">
                           <img src=\"".base_url()."/image/logo-kabupaten.png\"  width=\"75\" height=\"100\" />
                           </td>
                           <td align=\"left\" style=\"font-size:14px\" width=\"93%\">&nbsp;</td></tr>
                           <tr>
                           <td align=\"left\" style=\"font-size:14px\" width=\"93%\"><strong>P$prov </strong></td></tr>
                           <tr>
                           <td align=\"left\" style=\"font-size:14px\" ><strong>SKPD BADAN KEUANGAN DAN ASET DAERAH </strong></td></tr>
                           <tr>
                           <td align=\"left\" style=\"font-size:14px\" ><strong>TAHUN ANGGARAN ".$this->session->userdata('pcThang')."</strong></td></tr>
                           <tr>
                           <td align=\"left\" style=\"font-size:14px\" ><strong>&nbsp;</strong></td></tr>
                           </table>
                    <hr  width=\"100%\"> 
                    ";
        $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
			<tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>LAPORAN KOREKSI PENERIMAAN TAHUN ANGGARAN $thn_ang</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px black;\"></td>
            </tr>
			</table>
			<table style=\"border-collapse:collapse; border-color: black;font-size:13px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\" >
            <thead> 
			<tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\" style=\"font-weight:bold;\">No. Urut</td>  
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\" style=\"font-weight:bold;\">Tanggal</td>  
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"27%\" style=\"font-weight:bold\">Uraian</td>
				<td align=\"center\" bgcolor=\"#CCCCCC\" width=\"15%\" style=\"font-weight:bold\">Kode Rekening</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"15%\" style=\"font-weight:bold\">Penerimaan (Rp)</td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"border-top:solid 1px black\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"border-top:solid 1px black\">2</td>
				<td align=\"center\" bgcolor=\"#CCCCCC\" style=\"border-top:solid 1px black\">3</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"border-top:solid 1px black\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"border-top:solid 1px black\">5</td>
            </tr>";
			
			if($cetk=='1'){
					$cRet .="<tr>
						<td align=\"center\"></td>
						<td align=\"left\" colspan=\"2\">Tanggal : $tanggala</td>
						<td align=\"center\"></td>
						<td align=\"center\"></td>
					</tr>
					</thead>";
				}else{
					$cRet .="<tr>
						<td align=\"center\"></td>
						<td align=\"left\" colspan=\"2\">Tanggal : $tanggal1 s.d $tanggal2</td>
						<td align=\"center\"></td>
						<td align=\"center\"></td>
					</tr>
					</thead>";
				}
				
		$nilai3=0; 
		$sql = "SELECT * from trkasout_ppkd $where order by tanggal, [no]";
		$hasil = $this->db->query($sql);
		
		foreach ($hasil->result() as $row){
		$nomor     = $row->no;	
		$tanggal    = $row->tanggal;	
		$keterangan  = $row->keterangan;	
		$kd_rek  = $row->kd_rek;	
		$nm_skpd  = $row->nm_skpd;	
		$nilai       = $row->nilai;
        $nilaix = empty($nilai) || $nilai == 0 ? '' :number_format($nilai,"2",",",".");                     
		$nilai3=$nilai3+$nilai;

			$cRet .= '<tr>
			<td>'.$nomor.'</td>
			<td>'.$this->tanggal_format_indonesia($tanggal).'</td>
			<td>'.$nm_skpd.'<br>'.$keterangan.'</td>
			<TD align="center">'.$kd_rek.'</TD>
			<TD align="right">'.$nilaix.'</TD>
			</tr>';
			 
		} 
		
		$sql2 = "select sum(d.nilai) as nilai from trkasout_ppkd d $where2";
				
		$hasil2 = $this->db->query($sql2);
		foreach ($hasil2->result() as $row){
		$nilai2 = $row->nilai;
		$nilai2x      = number_format($nilai2,"2",",",".");
		}		
		
		$total  = $nilai3+$nilai2;
		$totalx = number_format($total,"2",",","."); 
		$nilai3x = number_format($nilai3,"2",",",".");
		
		
		if($cetk=='1'){
			$cRet .= '
			<tr>
				<TD></TD>
				<TD align="left" colspan="3" style="border-left:hidden;">Jumlah Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$tanggala.' </TD>
				<TD align="right" >'.$nilai3x.'</TD>
			</tr>
			
			<tr>
				<TD style="border-top:hidden;"></TD>
				<TD style="border-top:hidden;border-left:hidden;" align="left" colspan="3">Jumlah Sampai Tanggal : '.$tanggalsbl.' </TD>
				<TD style="border-top:hidden;" align="right" >'.$nilai2x.'</TD>
			</tr>
			
			<tr>
				<TD style="border-top:hidden;"></TD>
				<TD style="border-top:hidden;border-left:hidden;" align="left" colspan="3">Jumlah s.d Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$tanggala.' </TD>
				<TD align="right" >'.$totalx.'</TD>
			</tr>';
		}else{
			$cRet .= '
			<tr>
				<TD></TD>
				<TD align="left" colspan="3" style="border-left:hidden;">Jumlah Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$tanggal1.' &nbsp; s.d '.$tanggal2.' &nbsp;</TD>
				<TD align="right" >'.$nilai3x.'</TD>
			</tr>
			
			<tr>
				<TD style="border-top:hidden;"></TD>
				<TD style="border-top:hidden;border-left:hidden;" align="left" colspan="3">Jumlah Sampai Tanggal : '.$tanggalsbl.' </TD>
				<TD style="border-top:hidden;" align="right" >'.$nilai2x.'</TD>
			</tr>
			
			<tr>
				<TD style="border-top:hidden;"></TD>
				<TD style="border-top:hidden;border-left:hidden;" align="left" colspan="3">Jumlah s.d Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:'.$tanggal2.' &nbsp;</TD>
				<TD align="right" >'.$totalx.'</TD>
			</tr>';
		}
		
		 $cRet .='</table>';
		
		$cRet .="<table style=\"font-size:12px;border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
					</tr>
					<tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
					    <td align=\"center\" width=\"50%\">Kuasa Bendahara Umum Daerah</td>
					</tr>
                    <tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
					</tr>
					<tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
					</tr>                              
                    <tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
					</tr>                                       
                    <tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
						<td align=\"center\" width=\"50%\"><u>$nama</u></td>
					</tr>
                    <tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
						<td align=\"center\" width=\"50%\">$pangkat</td>
					</tr>
					<tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
						<td align=\"center\" width=\"50%\">NIP. $nip</td>
					</tr>
                    
                  </table>";
		
		$print = $this->uri->segment(3);
		
		if($print==0){
			 $data['prev']= $cRet;    
			 echo ("<title>Koreksi Penerimaan</title>");
			 echo $cRet;
		}else{
			$this->_mpdf3('',$cRet,5,5,5,'0',$no_halaman,'');
		}
		
	}

    // Pengeluaran
    function pengeluaran()
    {
        $data['page_title']= 'Register Koreksi Pengeluaran';
        $this->template->set('title', 'Register Koreksi Pengeluaran');   
        $this->template->load('template','register_koreksi/reg_koreksipengeluaran',$data) ; 
    }

    function ctk_koreksi_pengeluaran($ctk='',$ttd='',$tgl='',$no_halaman=''){
		
		$lcttd = str_replace('123456789',' ',$this->uri->segment(4));
		$cetk = $this->uri->segment(9);
		
		if($cetk=='1'){
			$where = "where tanggal = '$tgl'";
			$where2 = "where d.tanggal <'$tgl'";
			
			$tanggala = $this->tanggal_format_indonesia($tgl);
			
			$z = strtotime("-1 day", strtotime($tgl));
			$n = date("Y-m-d", $z);
			$tanggalsbl = $this->tanggal_format_indonesia($n);
		
		}else{
			$tgl1 = $this->uri->segment(7);
			$tgl2 = $this->uri->segment(8);
		
			$where = "where tanggal between '$tgl1' AND '$tgl2'";
			$where2 = "where d.tanggal <'$tgl1'";
		
			$tanggal1 = $this->tanggal_format_indonesia($tgl1);
			
			$z = strtotime("-1 day", strtotime($tgl1));
			$n = date("Y-m-d", $z);
			$tanggalsbl = $this->tanggal_format_indonesia($n);
		
			$tanggal2 = $this->tanggal_format_indonesia($tgl2);
			
			$z2 = strtotime("-1 day", strtotime($tgl2));
			$n2 = date("Y-m-d", $z2);
			$tanggalsbl2 = $this->tanggal_format_indonesia($n2);
		}
		
		$thn_ang = $this->session->userdata('pcThang');
		$prv = $this->db->query("SELECT TOP 1 * from sclient");
		$prvn = $prv->row();          
		$prov = $prvn->provinsi;         
		$daerah = $prvn->daerah;
		
		$sqlttd1="SELECT TOP 1 nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$lcttd'";
		$sqlttd=$this->db->query($sqlttd1);
		 foreach ($sqlttd->result() as $rowttd)
			{
				$nip=$rowttd->nip;                    
				$nama= $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat=$rowttd->pangkat;
			}
			$cRet = '';
            $cRet .="<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                       <tr>
                           <td rowspan=\"5\" align=\"left\" width=\"7%\">
                           <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" />
                           </td>
                           <td align=\"left\" style=\"font-size:14px\" width=\"93%\">&nbsp;</td></tr>
                           <tr>
                           <td align=\"left\" style=\"font-size:14px\" width=\"93%\"><strong>P$prov </strong></td></tr>
                           <tr>
                           <td align=\"left\" style=\"font-size:14px\" ><strong>SKPD BADAN KEUANGAN DAN ASET DAERAH </strong></td></tr>
                           <tr>
                           <td align=\"left\" style=\"font-size:14px\" ><strong>TAHUN ANGGARAN ".$this->session->userdata('pcThang')."</strong></td></tr>
                           <tr>
                           <td align=\"left\" style=\"font-size:14px\" ><strong>&nbsp;</strong></td></tr>
                           </table>
                    <hr  width=\"100%\"> 
                    ";
        $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
			<tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>LAPORAN KOREKSI PENGELUARAN TAHUN ANGGARAN $thn_ang</b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 1px black;\"></td>
            </tr>
			</table>
			<table style=\"border-collapse:collapse; border-color: black;font-size:13px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\" >
            <thead> 
			<tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\" style=\"font-weight:bold;\">No. Urut</td>  
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\" style=\"font-weight:bold;\">No. SP2D</td>  
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\" style=\"font-weight:bold;\">Tanggal</td>  
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"27%\" style=\"font-weight:bold\">Uraian</td>
				<td align=\"center\" bgcolor=\"#CCCCCC\" width=\"15%\" style=\"font-weight:bold\">Kode Rekening</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"15%\" style=\"font-weight:bold\">Pengeluaran (Rp)</td>
            </tr>
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"border-top:solid 1px black\">1</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"border-top:solid 1px black\">2</td>
				<td align=\"center\" bgcolor=\"#CCCCCC\" style=\"border-top:solid 1px black\">3</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"border-top:solid 1px black\">4</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"border-top:solid 1px black\">5</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"border-top:solid 1px black\">6</td>
            </tr>";
			
			if($cetk=='1'){
					$cRet .="<tr>
						<td align=\"center\"></td>
						<td align=\"left\" colspan=\"2\">Tanggal : $tanggala</td>
						<td align=\"center\"></td>
						<td align=\"center\"></td>
						<td align=\"center\"></td>
					</tr>
					</thead>";
				}else{
					$cRet .="<tr>
						<td align=\"center\"></td>
						<td align=\"left\" colspan=\"2\">Tanggal : $tanggal1 s.d $tanggal2</td>
						<td align=\"center\"></td>
						<td align=\"center\"></td>
						<td align=\"center\"></td>
					</tr>
					</thead>";
				}
				
		$nilai3=0; 
		$sql = "SELECT * from trkoreksi_pengeluaran $where order by tanggal, [no]";
		$hasil = $this->db->query($sql);
		
		foreach ($hasil->result() as $row){
		$nomor     = $row->no;	
		$tanggal    = $row->tanggal;	
		$keterangan  = $row->keterangan;	
		$no_sp2d  = $row->no_sp2d;	
		$kd_rek  = $row->kd_rek;	
		$nm_skpd  = $row->nm_skpd;	
		$nilai       = $row->nilai;
        $nilaix = empty($nilai) || $nilai == 0 ? '' :number_format($nilai,"2",",",".");                     
		$nilai3=$nilai3+$nilai;

			$cRet .= '<tr>
			<td>'.$nomor.'</td>
			<td>'.$no_sp2d.'</td>
			<td>'.$this->tanggal_format_indonesia($tanggal).'</td>
			<td>'.$nm_skpd.'<br>'.$keterangan.'</td>
			<TD align="center">'.$kd_rek.'</TD>
			<TD align="right">'.$nilaix.'</TD>
			</tr>';
			 
		} 
		
		$sql2 = "select sum(d.nilai) as nilai from trkoreksi_pengeluaran d $where2";
				
		$hasil2 = $this->db->query($sql2);
		foreach ($hasil2->result() as $row){
		$nilai2 = $row->nilai;
		$nilai2x      = number_format($nilai2,"2",",",".");
		}		
		
		$total  = $nilai3+$nilai2;
		$totalx = number_format($total,"2",",","."); 
		$nilai3x = number_format($nilai3,"2",",",".");
		
		
		if($cetk=='1'){
			$cRet .= '
			<tr>
				<TD></TD>
				<TD align="left" colspan="3" style="border-left:hidden;">Jumlah Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$tanggala.' </TD>
				<TD align="right" >'.$nilai3x.'</TD>
			</tr>
			
			<tr>
				<TD style="border-top:hidden;"></TD>
				<TD style="border-top:hidden;border-left:hidden;" align="left" colspan="3">Jumlah Sampai Tanggal : '.$tanggalsbl.' </TD>
				<TD style="border-top:hidden;" align="right" >'.$nilai2x.'</TD>
			</tr>
			
			<tr>
				<TD style="border-top:hidden;"></TD>
				<TD style="border-top:hidden;border-left:hidden;" align="left" colspan="3">Jumlah s.d Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$tanggala.' </TD>
				<TD align="right" >'.$totalx.'</TD>
			</tr>';
		}else{
			$cRet .= '
			<tr>
				<TD></TD>
				<TD align="left" colspan="3" style="border-left:hidden;">Jumlah Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: '.$tanggal1.' &nbsp; s.d '.$tanggal2.' &nbsp;</TD>
				<TD align="right" >'.$nilai3x.'</TD>
			</tr>
			
			<tr>
				<TD style="border-top:hidden;"></TD>
				<TD style="border-top:hidden;border-left:hidden;" align="left" colspan="3">Jumlah Sampai Tanggal : '.$tanggalsbl.' </TD>
				<TD style="border-top:hidden;" align="right" >'.$nilai2x.'</TD>
			</tr>
			
			<tr>
				<TD style="border-top:hidden;"></TD>
				<TD style="border-top:hidden;border-left:hidden;" align="left" colspan="3">Jumlah s.d Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:'.$tanggal2.' &nbsp;</TD>
				<TD align="right" >'.$totalx.'</TD>
			</tr>';
		}
		
		 $cRet .='</table>';
		
		$cRet .="<table style=\"font-size:12px;border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
					</tr>
					<tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
					    <td align=\"center\" width=\"50%\">Kuasa Bendahara Umum Daerah</td>
					</tr>
                    <tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
					</tr>
					<tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
					</tr>                              
                    <tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
					</tr>                                       
                    <tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
						<td align=\"center\" width=\"50%\"><u>$nama</u></td>
					</tr>
                    <tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
						<td align=\"center\" width=\"50%\">$pangkat</td>
					</tr>
					<tr>
						<td align=\"center\" width=\"50%\">&nbsp;</td>
						<td align=\"center\" width=\"50%\">NIP. $nip</td>
					</tr>
                    
                  </table>";
		
		$print = $this->uri->segment(3);
		
		if($print==0){
			 $data['prev']= $cRet;    
			 echo ("<title>Koreksi Penerimaan</title>");
			 echo $cRet;
		}else{
			$this->_mpdf3('',$cRet,5,5,5,'0',$no_halaman,'');
		}
		
	}
		
}	
