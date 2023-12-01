<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Rth extends CI_Controller {


	public $ppkd1 = "4.02.02.01";
	public $ppkd2 = "4.02.02.02";
	
	function __contruct()
	{	
		parent::__construct();
  
	}
function index(){
        $data['page_title']= 'RTH';
        $this->template->set('title', 'RTH');   
        $this->template->load('template','tukd/transaksi/rth',$data) ; 
    }

function cetak_rth($nbulan='',$ctk='',$ttd='', $tgl='', $cetk=''){
        $nip = str_replace('123456789',' ',$ttd);
		$tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($tgl);
        $atas = $this->uri->segment(9);
        $bawah = $this->uri->segment(10);
        $kiri = $this->uri->segment(11);
        $kanan = $this->uri->segment(12);
		

        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE KD_SKPD='$this->ppkd1'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$nip' AND kode='BUD'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
       
	   if($cetk=='2'){
			$periode1 = $this->uri->segment(8);
			$periode2 = $this->uri->segment(9);
			
			$tgl_periode1 = substr($periode1,7,2);
			$bln_periode1 = substr($periode1,5,1);
			$thn_periode1 = substr($periode1,0,4);
			
			$tgl_periode2 = substr($periode2,8,2);
			$bln_periode2 = substr($periode2,5,2);
			
			$tanggl_prd1 = $this->tukd_model->terbilang2($tgl_periode1);
			$buln_prd1  = $this->getBulan($bln_periode1);
		
			$tanggl_prd2 = $this->tukd_model->terbilang2($tgl_periode2);
			$buln_prd2  = $this->getBulan($bln_periode2);
			
			$judul='<TR>
					<TD align="center" ><b>'.$prov.'	 <br>
					PERIODE '.$tgl_periode1.' '.$buln_prd1.' s/d '.$tgl_periode2.' '.$buln_prd2.' '.$thn.'</TD>
					</TR>';
		}else{
			$judul='<TR>
					<TD align="center"><b>'.$prov.'	 <br>
					BULAN '.strtoupper($this->tukd_model->getBulan($nbulan)).' '.$thn.'</TD>
					</TR>';
		}
		
			$cRet ='<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD align="center" ><b>REKAPITULASI TRANSAKSI HARIAN BELANJA DAERAH (RTH) </TD>
					</TR>
					<tr></tr>
                    '.$judul.'
					</TABLE><br/>';

			

			$cRet .='<TABLE style="border-collapse:collapse; font-size:12px" width="100%" border="1" cellspacing="0" cellpadding="3" align=center>
					 <thead>
					 <TR>
						<TD rowspan="2" bgcolor="#CCCCCC" align="center" >No.</TD>
                        <TD rowspan="2" bgcolor="#CCCCCC" align="center" >NAMA SKPD / KUASA BUD</TD>
						<TD colspan="2" bgcolor="#CCCCCC" align="center" >SPM / SPD </TD>
						<TD colspan="2" bgcolor="#CCCCCC" align="center" >SP2D</TD>
						<TD rowspan="2" bgcolor="#CCCCCC" align="center" >POTONGAN PAJAK</TD>
						<TD rowspan="2" bgcolor="#CCCCCC" align="center" >KET</TD>
					 </TR>
					 <TR>
                        <TD bgcolor="#CCCCCC" align="center" >JUMLAH<BR>TOTAL</TD>
						<TD bgcolor="#CCCCCC" align="center" >NILAI BELANJA<BR>TOTAL</TD>						
						<TD bgcolor="#CCCCCC" align="center" >JUMLAH<BR>TOTAL </TD>
						<TD bgcolor="#CCCCCC" align="center" >NILAI BELANJA<BR>TOTAL</TD>
					 </TR>
					 </thead>
					 ';
			if($cetk=='1'){
				$query = $this->db->query("exec cetak_rth2 $nbulan");
			}else{
				$query = $this->db->query("exec cetak_rth_periode2 '$periode1', '$periode2'");
			}
			$no=0;
			$tot_spm=0;
			$totNilspm=0;
			$tot_sp2d=0;
			$totNilsp2d=0;
			$totNilpot=0;
			   foreach ($query->result() as $row) {
				$no=$no+1;
				$kd_skpd = $row->kd_skpd; 
				$nm_skpd = $row->nm_skpd;                   
				$banyak_spm = $row->banyak_spm;                   
				$nil_spm = $row->nil_spm;                   
				$banyak_sp2d = $row->banyak_sp2d;
				$nil_sp2d =$row->nil_sp2d;
				$nil_pot=$row->nil_pot;
				$tot_spm=$tot_spm + $banyak_spm;
				$totNilspm=$totNilspm + $nil_spm;
				$tot_sp2d=$tot_sp2d + $banyak_sp2d;
				$totNilsp2d=$totNilsp2d + $nil_sp2d;
				$totNilpot=$totNilpot + $nil_pot;
				$banyak_spm1  = empty($banyak_spm) || $banyak_spm == 0 ? 0 :$banyak_spm;	
				$nil_spm1  = empty($nil_spm) || $nil_spm == 0 ? number_format(0,"2",",",".") :number_format($nil_spm,"2",",",".");	
				$banyak_sp2d1 = empty($banyak_sp2d) || $banyak_sp2d == 0 ? 0 :$banyak_sp2d;	
				$nil_sp2d1  = empty($nil_sp2d) || $nil_sp2d == 0 ? number_format(0,"2",",",".") :number_format($nil_sp2d,"2",",",".");	
				$nil_pot1  = empty($nil_pot) || $nil_pot == 0 ? number_format(0,"2",",",".") :number_format($nil_pot,"2",",",".");
			   
				$cRet .="<tr>
						<td valign=\"top\" align=\"center\"> $no </td>
						<td valign=\"top\" align=\"left\"> $nm_skpd </td>
						<td valign=\"top\" align=\"center\"> $banyak_spm1 </td>
						<td valign=\"top\" align=\"right\"> $nil_spm1 &nbsp;</td>
						<td valign=\"top\" align=\"center\"> $banyak_sp2d1 </td>
						<td valign=\"top\" align=\"right\"> $nil_sp2d1 &nbsp;</td>
						<td valign=\"top\" align=\"right\"> $nil_pot1 &nbsp;</td>
						<td valign=\"top\" align=\"right\"> &nbsp;</td>
						</tr>"  ;
				}	
				$cRet .="<tr>
						<td valign=\"top\" align=\"center\"><b> TOTAL </b></td>
						<td valign=\"top\" align=\"center\"><b> $no </b></td>
						<td valign=\"top\" align=\"center\"><b> $tot_spm</b> </td>
						<td valign=\"top\" align=\"right\"><b> ".number_format($totNilspm,"2",",",".")." </b>&nbsp;</td>
						<td valign=\"top\" align=\"center\"><b> $tot_sp2d </b></td>
						<td valign=\"top\" align=\"right\"> <b>".number_format($totNilsp2d,"2",",",".")."</b> &nbsp;</td>
						<td valign=\"top\" align=\"right\"><b> ".number_format($totNilpot,"2",",",".")."</b> &nbsp;</td>
						<td valign=\"top\" align=\"right\"> &nbsp;</td>
						</tr>"  ;
				   
			
				
			$cRet .='</TABLE>';
			
			$cRet .='<TABLE style="border-collapse:collapse; font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<TD align="center" >'.$daerah.', '.$tanggal_ttd.'</TD>
					</TR>
					
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >'.$jabatan.'</TD>
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
						<TD align="center" ><b><u>'.$nama.'</u></b><br>'.$pangkat.'</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >'.$nip.'</TD>
					</TR>
					</TABLE><br/>';

			$data['prev']= $cRet;
             switch ($ctk)
        {
            case 0;
			echo ("<title>RTH</title>");
				echo $cRet;
				break;
            case 1;
				//$this->_mpdf('',$cRet,10,10,10,'P',1,'');
                $this->_mpdf_margin('',$cRet,10,10,10,'L',0,'',$atas,$bawah,$kiri,$kanan);
               break;
			case 2;        
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=RTH_$nbulan.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
			break;   
		}
	}





}
?>