<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Akuntansi_rekon extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();

    }
	
	
	function rpt_neraca_pemda_org($cbulan="", $kd_skpd="", $cetak=1){
		//$bulan	 = $_REQUEST['tgl1'];
			$id   	= $this->session->userdata('kdskpd');
			$thn_ang	= $this->session->userdata('pcThang');
			$thn_ang_1	= $thn_ang-1;
			$bulan	 = $cbulan;
			$cbulan<10 ? $xbulan = "0$cbulan" : $xbulan=$cbulan;

			$sqlsc="SELECT nm_org FROM ms_organisasi where kd_org='$kd_skpd' ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $nmskpd  = $rowsc->nm_org;
                } 
        
			$nm_skpd	= strtoupper ($nmskpd);
			
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
						<td align=\"center\"><strong>PER $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1 </strong></td>
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

			$sqllo10="select isnull(sum(kredit-debet),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek5,1) in ('8') and kd_org=left('$kd_skpd',7)";
                    $querylo10= $this->db->query($sqllo10);
                    $pen8 = $querylo10->row();
                    $pen_lalu8 = $pen8->nilai;
                    $pen_lalu81= number_format($pen8->nilai,"2",",",".");
		
			$sqllo12="select isnull(sum(debet-kredit),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek5,1) in ('9') and kd_org=left('$kd_skpd',7)";
                    $querylo12= $this->db->query($sqllo12);
                    $bel10 = $querylo12->row();
                    $bel_lalu10 = $bel10->nilai;
                    $bel_lalu101= number_format($bel10->nilai,"2",",",".");

			$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org=left('$kd_skpd',7)";//aba
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org=left('$kd_skpd',7)";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org=left('$kd_skpd',7)";//Henri_TB
                            
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
			ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE kd_org=left('$kd_skpd',7) AND a.kd_rek5='3110101' AND YEAR(b.tgl_voucher)<'$thn_ang'
			and b.tabel=1 and reev=0");  
	        foreach($query3->result_array() as $res2){
				 $debet3=$res2['debet'];
				 $kredit3=$res2['kredit'];
                 				 
			 }
		
		$sqlrkpp="select isnull(sum(kredit-debet),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<'$thn_ang_1' and left(kd_rek5,3) in ('313') and kd_org=left('$kd_skpd',7)";
                    $rkpp= $this->db->query($sqlrkpp);
                    $rkpp1 = $rkpp->row();
                    $rkpp_lalu = $rkpp1->nilai;
                    $rkpp_lalu1= number_format($rkpp1->nilai,"2",",",".");
			
		$real=$kredit3-$debet3+$pen_lalu8+$bel_lalu10+$lpe_ll1+$lpe_ll2+$lpe_ll3;

//		created by henri_tb
		$sqllo9="select isnull(sum(kredit-debet),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_rek5,1) in ('8') and kd_org=left('$kd_skpd',7)";
                    $querylo9= $this->db->query($sqllo9);
                    $penlo7 = $querylo9->row();
                    $pen_lo7 = $penlo7->nilai;
                    $pen_lo71= number_format($penlo7->nilai,"2",",",".");
        
		$sqllo10="select isnull(sum(kredit-debet),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('8') and kd_org=left('$kd_skpd',7)";
                    $querylo10= $this->db->query($sqllo10);
                    $penlo8 = $querylo10->row();
                    $pen_lo_lalu8 = $penlo8->nilai;
                    $pen_lo_lalu81= number_format($penlo8->nilai,"2",",",".");
		
		$sqllo11="select isnull(sum(debet-kredit),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and left(kd_rek5,1) in ('9') and kd_org=left('$kd_skpd',7)";
                    $querylo11= $this->db->query($sqllo11);
                    $bello9 = $querylo11->row();
                    $bel_lo9 = $bello9->nilai;
                    $bel_lo91= number_format($bello9->nilai,"2",",",".");
		
		$sqllo12="select isnull(sum(debet-kredit),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('9') and kd_org=left('$kd_skpd',7)";
                    $querylo12= $this->db->query($sqllo12);
                    $bello10 = $querylo12->row();
                    $bel_lo_lalu10 = $bello10->nilai;
                    $bel_lo_lalu101= number_format($bello10->nilai,"2",",",".");		

					$surplus_lo3 = $pen_lo7 - $bel_lo9;
                    
					$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
                    
					$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
					
			$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_org=left('$kd_skpd',7)";//aba
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_org=left('$kd_skpd',7)";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_org=left('$kd_skpd',7)";//Henri_TB
                            
                    $hasil = $this->db->query($sqllpe_lalu2); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row003)
                    {
                       $kd_rek   =$row003->nor ;
                       $parent   =$row003->parent;
                       $nama     =$row003->uraian;
                       $lpe_lalu3  =$row003->thn_m1;
					}
			
			$sqlrkpp2="select isnull(sum(kredit-debet),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)='$thn_ang_1' and left(kd_rek5,3) in ('313') and kd_org=left('$kd_skpd',7)";
                    $rkpp2= $this->db->query($sqlrkpp2);
                    $rkpp12 = $rkpp2->row();
                    $rkpp_lalu2 = $rkpp12->nilai;
                    	
			
		$sal_awal	= $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;		

			$sql = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org=left('$kd_skpd',7)";//aba
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org=left('$kd_skpd',7)";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org=left('$kd_skpd',7)";//Henri_TB
                            
                    $hasil = $this->db->query($sqllpe2); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row003)
                    {
                       $kd_rek   =$row003->nor ;
                       $parent   =$row003->parent;
                       $nama     =$row003->uraian;
                       $nilailpe2  =$row003->thn_m1;
					}
		
			$sqlrkpp3="select isnull(sum(kredit-debet),0) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)='$thn_ang' and left(kd_rek5,3) in ('313') and kd_org=left('$kd_skpd',7)";
                    $rkpp3= $this->db->query($sqlrkpp3);
                    $rkpp13 = $rkpp3->row();
                    $rkpp = $rkpp13->nilai;
                    
		
        $sal_akhir=$sal_awal+$surplus_lo3+$nilaiDR+$nilailpe1+$nilailpe2;

			$sqlutang_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(b.kd_rek5,1)=2 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlutang_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $nilaiutang_lalu  =$row->thn_m1;
					}					

			$sqlkas_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=3130101 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org=left('$kd_skpd',7)";//Henri_TB
                            
                    $hasil = $this->db->query($sqlkas_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_ppkd_lalu  =$row->thn_m1;
					}

			$eku_lalu 		= $sal_awal + $rk_ppkd_lalu;					
			$eku_tang_lalu 	= $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu;
		
			$sqlutang = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher
			and b.kd_unit=a.kd_skpd where left(b.kd_rek5,1)=2 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org=left('$kd_skpd',7)";//Henri_TB
                            
                    $hasil = $this->db->query($sqlutang); 
                    foreach ($hasil->result() as $row)
                    {
                       $nilaiutang  =$row->thn_m1;
					}					
			
			$sqlkas = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=3130101 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org=left('$kd_skpd',7)";//Henri_TB
                            
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
									and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org=left('$kd_skpd',7) and
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
									and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and kd_org=left('$kd_skpd',7) and
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
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
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
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
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
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
                        break;
					case 375:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
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
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
                        break;	
					case 415:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
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
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$c1$sal_awal1$d1</td>

								 </tr>";
                        break;
					case 470:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
						break;		 
					case 475:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min003$eku1$min004</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min001$eku_lalu1$min002</td>
								 </tr>";
                        break;
					case 485:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"55%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
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

			
        $cRet .='</table>';
                 
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = ("NERACA KONSOL SKPD $cbulan");
        $this->template->set('title', 'NERACA KONSOL SKPD $cbulan');  
         switch ($cetak)
        {
            case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
            case 1;
				echo "<title>NERACA KONSOL SKPD $cbulan</title>";
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
		//$bulan	 = $_REQUEST['tgl1'];
			$id   	= $this->session->userdata('kdskpd');
			$thn_ang	= $this->session->userdata('pcThang');
			$thn_ang_1	= $thn_ang-1;
			$bulan	 = $cbulan;
			$cbulan<10 ? $xbulan = "0$cbulan" : $xbulan=$cbulan;

			$sqlsc="SELECT nm_org FROM ms_organisasi where kd_org='$kd_skpd' ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $nmskpd  = $rowsc->nm_org;
                } 
        
			$nm_skpd	= strtoupper ($nmskpd);
			
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
					<TR>
						<td align=\"center\"><strong>$nm_skpd</strong></td>
					</TR>
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
                   
                     <tr>	<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
							<td colspan =\"7\"style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"55%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                           
                        </tr>";
			
			
			//level 1

// Created by Henri_TB

			$sqllo10="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek5,1) in ('8') and kd_org='$kd_skpd'";
                    $querylo10= $this->db->query($sqllo10);
                    $pen8 = $querylo10->row();
                    $pen_lalu8 = $pen8->nilai;
                    $pen_lalu81= number_format($pen8->nilai,"2",",",".");
		
			$sqllo12="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek5,1) in ('9') and kd_org='$kd_skpd'";
                    $querylo12= $this->db->query($sqllo12);
                    $bel10 = $querylo12->row();
                    $bel_lalu10 = $bel10->nilai;
                    $bel_lalu101= number_format($bel10->nilai,"2",",",".");

			$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org='$kd_skpd'";//aba
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
			and b.tabel=1 and reev=0 and kd_org='$kd_skpd'");  
	        foreach($query3->result_array() as $res2){
				 $debet3=$res2['debet'];
				 $kredit3=$res2['kredit'];
                 				 
			 }
		
		$real=$kredit3-$debet3+$pen_lalu8-$bel_lalu10+$lpe_ll1+$lpe_ll2+$lpe_ll3;

//		created by henri_tb
		$sqllo9="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek5,1) in ('8') and kd_org='$kd_skpd'";
                    $querylo9= $this->db->query($sqllo9);
                    $penlo7 = $querylo9->row();
                    $pen_lo7 = $penlo7->nilai;
                    $pen_lo71= number_format($penlo7->nilai,"2",",",".");
        
		$sqllo10="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('8') and kd_org='$kd_skpd'";
                    $querylo10= $this->db->query($sqllo10);
                    $penlo8 = $querylo10->row();
                    $pen_lo_lalu8 = $penlo8->nilai;
                    $pen_lo_lalu81= number_format($penlo8->nilai,"2",",",".");
		
		$sqllo11="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek5,1) in ('9') and kd_org='$kd_skpd'";
                    $querylo11= $this->db->query($sqllo11);
                    $bello9 = $querylo11->row();
                    $bel_lo9 = $bello9->nilai;
                    $bel_lo91= number_format($bello9->nilai,"2",",",".");
		
		$sqllo12="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('9') and kd_org='$kd_skpd'";
                    $querylo12= $this->db->query($sqllo12);
                    $bello10 = $querylo12->row();
                    $bel_lo_lalu10 = $bello10->nilai;
                    $bel_lo_lalu101= number_format($bello10->nilai,"2",",",".");		

					$surplus_lo3 = $pen_lo7 - $bel_lo9;
                    
					$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
                    
					$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
					
			$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_org='$kd_skpd'";//aba
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org='$kd_skpd'";//aba
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org='$kd_skpd'";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org='$kd_skpd'";//Henri_TB
                            
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

			$sqlutang_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(b.kd_rek5,1)=2 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlutang_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $nilaiutang_lalu  =$row->thn_m1;
					}					

			$sqlkas_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=3130101 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlkas_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_ppkd_lalu  =$row->thn_m1;
					}

			$sqlskpd_lalu = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=1180101 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlskpd_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_skpd_lalu  =$row->thn_m1;
					}
			
			$sqllcr_lalu = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek5,2)=11 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllcr_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $lcrx_lalu  =$row->thn_m1;
					}
					
			$sqlast_lalu = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek5,1)=1 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlast_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $astx_lalu  =$row->thn_m1;
					}
			
			$lcr_lalu		= $lcrx_lalu-$rk_skpd_lalu;
			$ast_lalu		= $astx_lalu-$rk_skpd_lalu;			
			$eku_lalu 		= $sal_awal + $rk_ppkd_lalu;					
			$eku_tang_lalu 	= $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu;
		
			$sqlutang = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher
			and b.kd_unit=a.kd_skpd where left(b.kd_rek5,1)=2 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlutang); 
                    foreach ($hasil->result() as $row)
                    {
                       $nilaiutang  =$row->thn_m1;
					}					
			
			$sqlkas = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=3130101 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlkas); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_ppkd  =$row->thn_m1;
					}
			
			$sqlskpd = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=1180101 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlskpd); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_skpd  =$row->thn_m1;
					}

			$sqllcr = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek5,2)=11 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllcr); 
                    foreach ($hasil->result() as $row)
                    {
                       $lcrx =$row->thn_m1;
					}
					
			$sqlast = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek5,1)=1 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlast); 
                    foreach ($hasil->result() as $row)
                    {
                       $astx  =$row->thn_m1;
					}		
			
			$lcr		= $lcrx-$rk_skpd;
			$ast		= $astx-$rk_skpd;
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
		
			$queryneraca = " SELECT kode, uraian, bold, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
										isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
										isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
										isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
										FROM map_neraca_obyek ORDER BY seq ";  
		
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
											
				
			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
									and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd' and
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

				if ($normal==1){
					$nl=$debet-$kredit;
				}else{
					$nl=$kredit-$debet;				
				}
				if ($nl=='') $nl=0;
	
				// Jurnal Tahun lalu
				$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
									and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd' and
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
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 2:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
									 <td colspan =\"6\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 3:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
									 <td colspan =\"5\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
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
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
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
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
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
		
	function rpt_neraca_awal_pemda_org($cbulan="", $kd_skpd="", $cetak=1){
		//$bulan	 = $_REQUEST['tgl1'];
			$id   	= $this->session->userdata('kdskpd');
			$thn_ang	= $this->session->userdata('pcThang');
			$thn_ang_1	= $thn_ang-1;
			$bulan	 = $cbulan;
			$cbulan<10 ? $xbulan = "0$cbulan" : $xbulan=$cbulan;

			$sqlsc="SELECT nm_org FROM ms_organisasi where kd_org='$kd_skpd' ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $nmskpd  = $rowsc->nm_org;
                } 
        
			$nm_skpd	= strtoupper ($nmskpd);
			
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
					<TR>
						<td align=\"center\"><strong>$nm_skpd</strong></td>
					</TR>
					<TR>
						<td align=\"center\"><strong>NERACA AWAL</strong></td>
					</TR>
					<TR>
						<td align=\"center\"><strong>PER 1 JANUARI $thn_ang</strong></td>
					</TR>
					</TABLE><br>";

					  $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
							<td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"55%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>JUMLAH</b></td>                            
                        </tr>
                        
                     </thead>
                     <tfoot>
                        <tr>
							<td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                                             
                         </tr>
                     </tfoot>
                   
                     <tr>	<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
							<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"55%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                           
                        </tr>";
			
			
			//level 1

// Created by Henri_TB

			$sqllo10="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek5,1) in ('8') and kd_org='$kd_skpd'";
                    $querylo10= $this->db->query($sqllo10);
                    $pen8 = $querylo10->row();
                    $pen_lalu8 = $pen8->nilai;
                    $pen_lalu81= number_format($pen8->nilai,"2",",",".");
		
			$sqllo12="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek5,1) in ('9') and kd_org='$kd_skpd'";
                    $querylo12= $this->db->query($sqllo12);
                    $bel10 = $querylo12->row();
                    $bel_lalu10 = $bel10->nilai;
                    $bel_lalu101= number_format($bel10->nilai,"2",",",".");

			$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org='$kd_skpd'";//aba
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
			and b.tabel=1 and reev=0 and kd_org='$kd_skpd'");  
	        foreach($query3->result_array() as $res2){
				 $debet3=$res2['debet'];
				 $kredit3=$res2['kredit'];
                 				 
			 }
		
		$real=$kredit3-$debet3+$pen_lalu8+$bel_lalu10+$lpe_ll1+$lpe_ll2+$lpe_ll3;

//		created by henri_tb
		$sqllo9="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek5,1) in ('8') and kd_org='$kd_skpd'";
                    $querylo9= $this->db->query($sqllo9);
                    $penlo7 = $querylo9->row();
                    $pen_lo7 = $penlo7->nilai;
                    $pen_lo71= number_format($penlo7->nilai,"2",",",".");
        
		$sqllo10="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('8') and kd_org='$kd_skpd'";
                    $querylo10= $this->db->query($sqllo10);
                    $penlo8 = $querylo10->row();
                    $pen_lo_lalu8 = $penlo8->nilai;
                    $pen_lo_lalu81= number_format($penlo8->nilai,"2",",",".");
		
		$sqllo11="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek5,1) in ('9') and kd_org='$kd_skpd'";
                    $querylo11= $this->db->query($sqllo11);
                    $bello9 = $querylo11->row();
                    $bel_lo9 = $bello9->nilai;
                    $bel_lo91= number_format($bello9->nilai,"2",",",".");
		
		$sqllo12="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('9') and kd_org='$kd_skpd'";
                    $querylo12= $this->db->query($sqllo12);
                    $bello10 = $querylo12->row();
                    $bel_lo_lalu10 = $bello10->nilai;
                    $bel_lo_lalu101= number_format($bello10->nilai,"2",",",".");		

					$surplus_lo3 = $pen_lo7 - $bel_lo9;
                    
					$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
                    
					$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
					
			$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_org='$kd_skpd'";//aba
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org='$kd_skpd'";//aba
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org='$kd_skpd'";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org='$kd_skpd'";//Henri_TB
                            
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

			$sqlutang_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(b.kd_rek5,1)=2 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlutang_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $nilaiutang_lalu  =$row->thn_m1;
					}					

			$sqlkas_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=3130101 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlkas_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_ppkd_lalu  =$row->thn_m1;
					}

			$sqlskpd_lalu = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=1180101 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlskpd_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_skpd_lalu  =$row->thn_m1;
					}
			
			$sqllcr_lalu = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek5,2)=11 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllcr_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $lcrx_lalu  =$row->thn_m1;
					}
					
			$sqlast_lalu = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek5,1)=1 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlast_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $astx_lalu  =$row->thn_m1;
					}
			
			$lcr_lalu		= $lcrx_lalu-$rk_skpd_lalu;
			$ast_lalu		= $astx_lalu-$rk_skpd_lalu;			
			$eku_lalu 		= $sal_awal + $rk_ppkd_lalu-$rk_skpd_lalu;					
			$eku_tang_lalu 	= $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu-$rk_skpd_lalu;
		
			$sqlutang = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher
			and b.kd_unit=a.kd_skpd where left(b.kd_rek5,1)=2 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlutang); 
                    foreach ($hasil->result() as $row)
                    {
                       $nilaiutang  =$row->thn_m1;
					}					
			
			$sqlkas = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=3130101 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlkas); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_ppkd  =$row->thn_m1;
					}
			
			$sqlskpd = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=1180101 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlskpd); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_skpd  =$row->thn_m1;
					}

			$sqllcr = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek5,2)=11 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllcr); 
                    foreach ($hasil->result() as $row)
                    {
                       $lcrx =$row->thn_m1;
					}
					
			$sqlast = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek5,1)=1 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlast); 
                    foreach ($hasil->result() as $row)
                    {
                       $astx  =$row->thn_m1;
					}		
			
			$lcr		= $lcrx-$rk_skpd;
			$ast		= $astx-$rk_skpd;
			$eku 		= $sal_akhir + $rk_ppkd-$rk_skpd;					
			$eku_tang 	= $sal_akhir + $nilaiutang + $rk_ppkd-$rk_skpd;					

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
		
			$queryneraca = " SELECT kode, uraian, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
										isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
										isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
										isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
										FROM map_neraca ORDER BY seq ";  
		
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
											
				
			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
									and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd' and
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

				if ($normal==1){
					$nl=$debet-$kredit;
				}else{
					$nl=$kredit-$debet;				
				}
				if ($nl=='') $nl=0;
	
				// Jurnal Tahun lalu
				$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
									and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd' and
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

				switch ($res['seq']) {
                    case 5:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 10:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;	
					case 15:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 20:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
                        break;						
					case 100:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min017$lcr_lalu1$min018</td>
                                 </tr>";
                        break;
					case 105:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 110:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 115:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 145:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 170:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 175:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 220:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 225:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 240:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 245:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 280:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 285:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min021$ast_lalu1$min022</td>
                                 </tr>";
                        break;		
					case 290:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 295:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 300:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 305:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 345:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 350:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 385:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 390:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 395:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min001$eku_lalu1$min002</td>
                                 </tr>";
                        break;		
					case 400:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
                                 </tr>";
                        break;
						default:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
                        break;	
					}
 
			}

			
        $cRet .='</table>';
                 
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = ("NERACA AWAL KONSOL SKPD");
        $this->template->set('title', 'NERACA AWAL KONSOL SKPD');  
         switch ($cetak)
        {
            case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
            case 1;
				echo "<title>NERACA KONSOL SKPD</title>";
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
	
	function rpt_neraca_awal_pemda_org_obyek($cbulan="", $kd_skpd="", $cetak=1){
		//$bulan	 = $_REQUEST['tgl1'];
			$id   	= $this->session->userdata('kdskpd');
			$thn_ang	= $this->session->userdata('pcThang');
			$thn_ang_1	= $thn_ang-1;
			$bulan	 = $cbulan;
			$cbulan<10 ? $xbulan = "0$cbulan" : $xbulan=$cbulan;

			$sqlsc="SELECT nm_org FROM ms_organisasi where kd_org='$kd_skpd' ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $nmskpd  = $rowsc->nm_org;
                } 
        
			$nm_skpd	= strtoupper ($nmskpd);
			
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
					<TR>
						<td align=\"center\"><strong>$nm_skpd</strong></td>
					</TR>
					<TR>
						<td align=\"center\"><strong>NERACA AWAL</strong></td>
					</TR>
					<TR>
						<td align=\"center\"><strong>PER 1 JANUARI $thn_ang</strong></td>
					</TR>
					</TABLE><br>";

					  $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
							<td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
                            <td colspan =\"7\"bgcolor=\"#CCCCCC\" width=\"55%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>JUMLAH</b></td>                            
                        </tr>
                        
                     </thead>
                     <tfoot>
                        <tr>
							<td style=\"border-top: none;\"></td>
                            <td colspan =\"7\"style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                                             
                         </tr>
                     </tfoot>
                   
                     <tr>	<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
							<td colspan =\"7\"style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"55%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                           
                        </tr>";
			
			
			//level 1

// Created by Henri_TB

			$sqllo10="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek5,1) in ('8') and kd_org ='$kd_skpd'";
                    $querylo10= $this->db->query($sqllo10);
                    $pen8 = $querylo10->row();
                    $pen_lalu8 = $pen8->nilai;
                    $pen_lalu81= number_format($pen8->nilai,"2",",",".");
		
			$sqllo12="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek5,1) in ('9') and kd_org='$kd_skpd'";
                    $querylo12= $this->db->query($sqllo12);
                    $bel10 = $querylo12->row();
                    $bel_lalu10 = $bel10->nilai;
                    $bel_lalu101= number_format($bel10->nilai,"2",",",".");

			$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org='$kd_skpd'";//aba
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
			and b.tabel=1 and reev=0 and kd_org='$kd_skpd'");  
	        foreach($query3->result_array() as $res2){
				 $debet3=$res2['debet'];
				 $kredit3=$res2['kredit'];
                 				 
			 }
		
		$real=$kredit3-$debet3+$pen_lalu8+$bel_lalu10+$lpe_ll1+$lpe_ll2+$lpe_ll3;

//		created by henri_tb
		$sqllo9="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek5,1) in ('8') and kd_org='$kd_skpd'";
                    $querylo9= $this->db->query($sqllo9);
                    $penlo7 = $querylo9->row();
                    $pen_lo7 = $penlo7->nilai;
                    $pen_lo71= number_format($penlo7->nilai,"2",",",".");
        
		$sqllo10="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('8') and kd_org='$kd_skpd'";
                    $querylo10= $this->db->query($sqllo10);
                    $penlo8 = $querylo10->row();
                    $pen_lo_lalu8 = $penlo8->nilai;
                    $pen_lo_lalu81= number_format($penlo8->nilai,"2",",",".");
		
		$sqllo11="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek5,1) in ('9') and kd_org='$kd_skpd'";
                    $querylo11= $this->db->query($sqllo11);
                    $bello9 = $querylo11->row();
                    $bel_lo9 = $bello9->nilai;
                    $bel_lo91= number_format($bello9->nilai,"2",",",".");
		
		$sqllo12="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('9') and kd_org='$kd_skpd'";
                    $querylo12= $this->db->query($sqllo12);
                    $bello10 = $querylo12->row();
                    $bel_lo_lalu10 = $bello10->nilai;
                    $bel_lo_lalu101= number_format($bello10->nilai,"2",",",".");		

					$surplus_lo3 = $pen_lo7 - $bel_lo9;
                    
					$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
                    
					$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
					
			$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_org='$kd_skpd'";//aba
                            
                    $hasil = $this->db->query($sql_lalu); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row001)
                    {
                       $kd_rek   =$row001->nor ;
                       $parent   =$row001->parent;
                       $nama     =$row001->uraian;
                       $lpe_lalu1  =$row001->thn_m1;
					}
				        
			$sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1, isnull(sum(kredit-debet),0) thn_m1 from trhju a
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org='$kd_skpd'";//aba
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org='$kd_skpd'";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org='$kd_skpd'";//Henri_TB
                            
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

			$sqlutang_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(b.kd_rek5,1)=2 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlutang_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $nilaiutang_lalu  =$row->thn_m1;
					}					

			$sqlkas_lalu = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=3130101 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlkas_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_ppkd_lalu  =$row->thn_m1;
					}

			$sqlskpd_lalu = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=1180101 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlskpd_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_skpd_lalu  =$row->thn_m1;
					}
			
			$sqllcr_lalu = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek5,2)=11 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllcr_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $lcrx_lalu  =$row->thn_m1;
					}
					
			$sqlast_lalu = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek5,1)=1 and year(a.tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlast_lalu); 
                    foreach ($hasil->result() as $row)
                    {
                       $astx_lalu  =$row->thn_m1;
					}
			
			$lcr_lalu		= $lcrx_lalu-$rk_skpd_lalu;
			$ast_lalu		= $astx_lalu-$rk_skpd_lalu;			
			$eku_lalu 		= $sal_awal + $rk_ppkd_lalu;					
			$eku_tang_lalu 	= $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu;
		
			$sqlutang = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher
			and b.kd_unit=a.kd_skpd where left(b.kd_rek5,1)=2 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlutang); 
                    foreach ($hasil->result() as $row)
                    {
                       $nilaiutang  =$row->thn_m1;
					}					
			
			$sqlkas = "select isnull(sum(kredit-debet),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=3130101 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlkas); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_ppkd  =$row->thn_m1;
					}
			
			$sqlskpd = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek5=1180101 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlskpd); 
                    foreach ($hasil->result() as $row)
                    {
                       $rk_skpd  =$row->thn_m1;
					}

			$sqllcr = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek5,2)=11 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqllcr); 
                    foreach ($hasil->result() as $row)
                    {
                       $lcrx =$row->thn_m1;
					}
					
			$sqlast = "select isnull(sum(debet-kredit),0) thn_m1 from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek5,1)=1 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd'";//Henri_TB
                            
                    $hasil = $this->db->query($sqlast); 
                    foreach ($hasil->result() as $row)
                    {
                       $astx  =$row->thn_m1;
					}		
			
			$lcr		= $lcrx-$rk_skpd;
			$ast		= $astx-$rk_skpd;
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
		
			$queryneraca = " SELECT kode, uraian, bold, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
										isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
										isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
										isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
										FROM map_neraca_obyek ORDER BY seq ";  
		
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
											
				
			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
									and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_org='$kd_skpd' and
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

				if ($normal==1){
					$nl=$debet-$kredit;
				}else{
					$nl=$kredit-$debet;				
				}
				if ($nl=='') $nl=0;
	
				// Jurnal Tahun lalu
				$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju a inner join trdju b on a.no_voucher=b.no_voucher 
									and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and kd_org='$kd_skpd' and
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
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 2:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
									 <td colspan =\"6\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 3:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
									 <td colspan =\"5\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
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
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
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
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
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
         $judul = ("NERACA AWAL KONSOL OBYEK SKPD $cbulan");
        $this->template->set('title', 'NERACA AWAL KONSOL OBYEK SKPD $cbulan');  
         switch ($cetak)
        {
            case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
            case 1;
				echo "<title>NERACA AWAL KONSOL OBYEK SKPD $cbulan</title>";
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
	
	function cetak_lra_pemda_org($cbulan="", $kd_skpd="", $cetak="", $js=""){ 
	$bulan=$cbulan;
	$kd_skpd1=$kd_skpd.".01";
	$id  = $this->session->userdata('kdskpd');
    $thn = $this->session->userdata('pcThang');
	$sqlanggaran1="select case when statu=1 and status_sempurna=1 and status_ubah=1 and $cbulan>=month(tgl_dpa_ubah) then 2 
					   when statu=1 and status_sempurna=1 and status_ubah=1 and $cbulan>=month(tgl_dpa_sempurna) and $cbulan<month(tgl_dpa_ubah) then 1
					   when statu=1 and status_sempurna=1 and status_ubah=1 and $cbulan<month(tgl_dpa_sempurna) then 1
					   when statu=1 and status_sempurna=1 and status_ubah=0 and $cbulan>=month(tgl_dpa_sempurna) then 1 
					   when statu=1 and status_sempurna=1 and status_ubah=0 and $cbulan<month(tgl_dpa_sempurna) then 1
					   when statu=1 and status_sempurna=0 and status_ubah=0 and $cbulan>=month(tgl_dpa) then 1
					   else 1 end as anggaran from trhrka where kd_skpd='$kd_skpd1'";
    $sqlanggaran=$this->db->query($sqlanggaran1);
	foreach ($sqlanggaran->result() as $rowttd)
        {
            $anggaran=$rowttd->anggaran;
        }

	$ag_tox=$anggaran;
	
	if($cbulan== 12){
		$sumber_data= "_at";
	}else{
		$sumber_data= "";
	}
	
	if ($js=='1'){                               
            $where= "AND left(kd_skpd,7)='$kd_skpd' AND kd_skpd<>'3.13.01.17'";
        } else {                               
            $where= "AND kd_org='$kd_skpd'";
        }
	
	
    //function cetak_lra(){
        $sql41="SELECT SUM(anggaran) as anggaran,SUM(real_spj) as nilai FROM data_realisasi_org$sumber_data($bulan,$ag_tox,$thn) WHERE left(kd_rek5,1)='4' $where";
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
					
        $sql51="SELECT SUM(anggaran) as angaran,SUM(real_spj)as nilai FROM data_realisasi_org$sumber_data($bulan,$ag_tox,$thn) WHERE left(kd_rek5,1) in ('5','6') $where";
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
					
        $sql523="SELECT SUM(anggaran) as anggaran,SUM(real_spj) as nilai FROM data_realisasi_org$sumber_data($bulan,$ag_tox,$thn) WHERE left(kd_rek5,2)='52' $where";
                    $query523 = $this->db->query($sql523);
                    $jmlbm = $query523->row();
                    $jmlbmbelanja = $jmlbm->nilai;
                    $jmlangbmbelanja = $jmlbm->anggaran;
                    $jmlbmbelanja1= number_format($jmlbmbelanja,"2",",",".");
                    $jmlangbmbelanja1= number_format($jmlangbmbelanja,"2",",",".");
        $sql61="SELECT SUM(anggaran) as anggaran,SUM(real_spj) as nilai FROM data_realisasi_org$sumber_data($bulan,$ag_tox,$thn) WHERE left(kd_rek5,2)='71' $where";
                    $query61 = $this->db->query($sql61);
                    $jmlpm = $query61->row();
                    $jmlpmasuk = $jmlpm->nilai;
                    $jmlangpmasuk = $jmlpm->anggaran;
        $sql62="SELECT SUM(anggaran) as anggaran,SUM(real_spj) as nilai FROM data_realisasi_org$sumber_data($bulan,$ag_tox,$thn) WHERE left(kd_rek5,2)='72' $where";
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
		
        $biaya_net = $jmlpmasuk- $jmlpkeluar;
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

		$selisih_biaya = $angbiaya_net- $biaya_net;		
		if ($selisih_biaya < 0){
                    	$xy="("; $selisih_biayax=$selisih_biaya*-1; $yx=")";
                        }else {
                    	$xy=""; $selisih_biayax=$selisih_biaya; $yx="";
                        }
		$selisih_biaya1 = number_format($selisih_biayax,"2",",",".");
                    
                    $per004     = ($angbiaya_net!=0)?($biaya_net / $angbiaya_net ) * 100:0; 
                    $persen004  = number_format($per004,"2",",",".");
		
		$selisih_silpa = $angsilpa - $silpa;		
		if ($selisih_silpa < 0){
                    	$az="("; $selisih_silpax=$selisih_silpa*-1; $za=")";
                        }else {
                    	$az=""; $selisih_silpax=$selisih_silpa; $za="";
                        }
						
		$selisih_silpa1 = number_format($selisih_silpax,"2",",",".");
                    $per005     = ($angsilpa!=0)?($silpa / $angsilpa) * 100:0; 
                    $persen005  = number_format($per005,"2",",",".");
		
		$sqlsc="SELECT nm_org FROM ms_organisasi where kd_org='$kd_skpd' ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $nmskpd  = $rowsc->nm_org;
                } 
		
		$nm_skpd	= strtoupper ($nmskpd);
        $jk=$this->rka_model->combo_skpd();
       
        $cRet='';
        
	 $modtahun= $thn%4;
	 
	 if ($modtahun = 0){
        $nilaibulan=".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";}
            else {
        $nilaibulan=".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";}
	 
	 $arraybulan=explode(".",$nilaibulan);
	   
        $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA DAERAH</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>$nm_skpd</strong></td>
                    </tr>
					<tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN</strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong> $arraybulan[$bulan] $thn</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";        
        
        
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NO</b></td>                            
                            <td  bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>ANGGARAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>REALISASI</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>LEBIH</br>(KURANG)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                           
                         </tr>
                     </tfoot>
                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>";
               
                   $sql4="SELECT a.seq,a.nor,a.uraian,isnull(a.kode_1,'-') as kode_1,isnull(a.kode_2,'-') as kode_2,isnull(a.kode_3,'-') as kode_3,thn_m1 AS lalu FROM map_lra_prov a 
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
                    $sql5   = "SELECT SUM(b.anggaran) as anggaran,SUM(b.real_spj) as nilai FROM data_realisasi_org$sumber_data($bulan,$ag_tox,$thn) b WHERE (left(b.kd_rek5,3) in ($n) or left(b.kd_rek5,5) in ($n2) or left(b.kd_rek5,7) in ($n3)) $where";
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
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 10:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 40:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 45:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 50:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 80:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 85:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 115:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 120:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 145:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$x1$selisih$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1</td>
                                 </tr>";
                        break;
					case 150:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 155:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 195:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 200:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 240:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 245:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 265:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 270:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 275:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 300:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 305:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 335:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 340:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$e$angsurplus1$f</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$x$surplus1$y</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisihsurplus</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen003</td>
                                 </tr>";
                        break;
                    case 345:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 350:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 355:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 360:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 430:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 435:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 500:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$g$angbiaya_net1$h</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$a$biaya_net1$b</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$xy$selisih_biaya1$yx</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen004</td>
                                 </tr>";
                        break;
                    case 505:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 510:
                        $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$i$angsilpa1$j</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$c$silpa1$d</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$az$selisih_silpa1$za</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen005</td>
                                 </tr>";
                        break;				
                    default:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$x1$selisih$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1</td>
                                 </tr>";
                }
                }
        $cRet .=       " </table>";
        $data['prev']= $cRet;
        $data['sikap'] = 'preview';
        $judul  = ("LRA KONSOL / $kd_skpd / $cbulan");
        $this->template->set('title', 'LRA KONSOL / $kd_skpd / $cbulan');        
        switch($cetak) {       
        case 1;
			echo ("<title>LRA KONSOL SKPD $cbulan</title>");
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

	
	function ctk_lra_lo_pemda_org($cbulan="", $kd_skpd="", $pilih=1){
        //$id = $skpd;
        $cetak='2';//$ctk; 
        $id     = $this->session->userdata('kdskpd');
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1= $thn_ang-1;  
        //$laporan=$lap; 
       
       // if ($cetak == '1') {
//           $skpd = '';
//           $skpd1 = '';           
//       } else {             
           $skpd = "AND kd_skpd='$kd_skpd'";
           $skpd1 = "AND b.kd_skpd='$kd_skpd'"; 
       //}  

       $y123=")";
	   $x123="(";       
	    $sqlsc="SELECT nm_org nm_skpd FROM ms_organisasi where kd_org='$kd_skpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
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
			
		$sqllo1="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('81','82','83') and kd_org='$kd_skpd'";
                    $querylo1= $this->db->query($sqllo1);
                    $penlo = $querylo1->row();
                    $pen_lo = $penlo->nilai;
                    $pen_lo1= number_format($penlo->nilai,"2",",",".");
        
		$sqllo2="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('81','82','83') and kd_org='$kd_skpd'";
                    $querylo2= $this->db->query($sqllo2);
                    $penlo2 = $querylo2->row();
                    $pen_lo_lalu = $penlo2->nilai;
                    $pen_lo_lalu1= number_format($penlo2->nilai,"2",",",".");
		
		$sqllo3="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('91','92') and kd_org='$kd_skpd'";
                    $querylo3= $this->db->query($sqllo3);
                    $bello = $querylo3->row();
                    $bel_lo = $bello->nilai;
                    $bel_lo1= number_format($bello->nilai,"2",",",".");
        
		$sqllo4="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('91','92') and kd_org='$kd_skpd'";
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
					
		$sqllo5="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('81','82','83','84') and kd_org='$kd_skpd'";
                    $querylo5= $this->db->query($sqllo5);
                    $penlo3 = $querylo5->row();
                    $pen_lo3 = $penlo3->nilai;
                    $pen_lo31= number_format($penlo3->nilai,"2",",",".");
        
		$sqllo6="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('81','82','83','84') and kd_org='$kd_skpd'";
                    $querylo6= $this->db->query($sqllo6);
                    $penlo4 = $querylo6->row();
                    $pen_lo_lalu4 = $penlo4->nilai;
                    $pen_lo_lalu41= number_format($penlo4->nilai,"2",",",".");
		
		$sqllo7="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('91','92','93') and kd_org='$kd_skpd'";
                    $querylo7= $this->db->query($sqllo7);
                    $bello5 = $querylo7->row();
                    $bel_lo5 = $bello5->nilai;
                    $bel_lo51= number_format($bello5->nilai,"2",",",".");
		
		$sqllo8="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('91','92','93') and kd_org='$kd_skpd'";
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
		
		$sqllo9="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,1) in ('8') and kd_org='$kd_skpd'";
                    $querylo9= $this->db->query($sqllo9);
                    $penlo7 = $querylo9->row();
                    $pen_lo7 = $penlo7->nilai;
                    $pen_lo71= number_format($penlo7->nilai,"2",",",".");
        
		$sqllo10="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('8') and kd_org='$kd_skpd'";
                    $querylo10= $this->db->query($sqllo10);
                    $penlo8 = $querylo10->row();
                    $pen_lo_lalu8 = $penlo8->nilai;
                    $pen_lo_lalu81= number_format($penlo8->nilai,"2",",",".");
		
		$sqllo11="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,1) in ('9') and kd_org='$kd_skpd'";
                    $querylo11= $this->db->query($sqllo11);
                    $bello9 = $querylo11->row();
                    $bel_lo9 = $bello9->nilai;
                    $bel_lo91= number_format($bello9->nilai,"2",",",".");
		
		$sqllo12="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('9') and kd_org='$kd_skpd'";
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

					
		$sqllo13="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('84') and kd_org='$kd_skpd'";
                    $querylo13= $this->db->query($sqllo13);
                    $penlo11 = $querylo13->row();
                    $pen_lo11 = $penlo11->nilai;
                    $pen_lo111= number_format($penlo11->nilai,"2",",",".");
        
		$sqllo14="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('84') and kd_org='$kd_skpd'";
                    $querylo14= $this->db->query($sqllo14);
                    $penlo12 = $querylo14->row();
                    $pen_lo_lalu12 = $penlo12->nilai;
                    $pen_lo_lalu121= number_format($penlo12->nilai,"2",",",".");
		
		$sqllo15="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('93') and kd_org='$kd_skpd'";
                    $querylo15= $this->db->query($sqllo15);
                    $bello13 = $querylo15->row();
                    $bel_lo13 = $bello13->nilai;
                    $bel_lo131= number_format($bello13->nilai,"2",",",".");
		
		$sqllo16="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('93') and kd_org='$kd_skpd'";
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
					
					
		$sqllo17="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('85') and kd_org='$kd_skpd'";
                    $querylo17= $this->db->query($sqllo17);
                    $penlo15 = $querylo17->row();
                    $pen_lo15 = $penlo15->nilai;
                    $pen_lo151= number_format($penlo15->nilai,"2",",",".");
        
		$sqllo18="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('85') and kd_org='$kd_skpd'";
                    $querylo18= $this->db->query($sqllo18);
                    $penlo16 = $querylo18->row();
                    $pen_lo_lalu16 = $penlo16->nilai;
                    $pen_lo_lalu161= number_format($penlo16->nilai,"2",",",".");
		
		$sqllo19="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek5,2) in ('94') and kd_org='$kd_skpd'";
                    $querylo19= $this->db->query($sqllo19);
                    $bello17 = $querylo19->row();
                    $bel_lo17 = $bello17->nilai;
                    $bel_lo171= number_format($bello17->nilai,"2",",",".");
		
		$sqllo20="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,2) in ('94') and kd_org='$kd_skpd'";
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
					
		
        $modtahun= $thn_ang%4;
	 
	 if ($modtahun = 0){
        $nilaibulan=".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";}
            else {
        $nilaibulan=".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";}
	 
	 $arraybulan=explode(".",$nilaibulan);

        $cRet='';
        
       
        $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
                    </tr>
					<tr>
						<td align=\"center\"><strong>$nm_skpd</strong></td>
					</tr>	
                    <tr>
                         <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";        
        
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NO</b></td>                            
                            <td  bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>Kenaikan</br>(Penurunan)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                           
                         </tr>
                     </tfoot>
                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
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
					
		$quelo01   = "SELECT SUM($normal) as nilai FROM trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek5,3) in ($n) or left(kd_rek5,5) in ($n2) or left(kd_rek5,7) in ($n3)) and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and kd_org='$kd_skpd'";
                    $quelo02 = $this->db->query($quelo01);
                    $quelo03 = $quelo02->row();
                    $nil     = $quelo03->nilai;
                    $nilai    = number_format($quelo03->nilai,"2",",",".");
					
		$quelo04   = "SELECT SUM($normal) as nilai FROM trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek5,3) in ($n) or left(kd_rek5,5) in ($n2) or left(kd_rek5,7) in ($n3)) and year(tgl_voucher)=$thn_ang_1 and kd_org='$kd_skpd'";
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
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 10:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 40:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 45:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;	
                    case 50:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 80:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 85:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 110:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 115:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 140:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 145:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 150:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 200:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 205:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 245:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 250:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo1$surplus_lo1$lo2</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo3$surplus_lo_lalu1$lo4</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo5$selisih_surplus_lo1$lo6</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen2</td>
                                 </tr>";
                        break;
					case 255:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 260:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 265:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 290:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 295:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 325:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 330:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo7$surplus_lo21$lo8</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo9$surplus_lo_lalu21$lo10</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo11$selisih_surplus_lo21$lo12</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen3</td>
                                 </tr>";
                        break;
					case 335:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 340:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 345:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 360:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 365:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 385:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                        break;
					case 390:
                         $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo13$surplus_lo31$lo14</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo15$surplus_lo_lalu31$lo16</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo17$selisih_surplus_lo31$lo18</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen4</td>
                                 </tr>";
                        break;
					default:
                    $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo0$real_nilai1$lo00</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1</td>
                                 </tr>";

                }
                              
                    
                }
               
              
       // $cRet         .= "</table>";
//        $data['prev']  = $cRet;
//        $data['sikap'] = 'preview';
//        
//        
//        
//        $this->template->set('title', 'LAPORAN OPERASIONAL'); 
//        $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
        //$this->template->load('template','anggaran/rka/perkadaII',$data); 
        
        $cRet .=       " </table>";
        $data['prev']= $cRet;
        $data['sikap'] = 'preview';
        $judul  = ("LO KONSOL SKPD $cbulan");
        $this->template->set('title', 'LO KONSOL UNIT $cbulan');        
        switch($pilih) {       
        case 1;
            // $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
			echo "<title>LO KONSOL SKPD $cbulan</title>";
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
    
	
	
	function ctk_lpe_pemda_org($cbulan="", $kd_skpd="", $cetak = 1){
		$id = $this->session->userdata('kdskpd');
        $thn_ang = $this->session->userdata('pcThang');
		$bulan	 = $cbulan;
        $id1     = $this->session->userdata('kdskpd');
        $nmskpd = $this->tukd_model->get_nama($kd_skpd,'nm_org','ms_organisasi','kd_org');
        $nm_skpd =strtoupper($nmskpd);
        $thn_ang_1= $thn_ang-1;   
        
	/*	$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                    $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }
		*/		
        $skpd = "AND kd_skpd='$id1'";
        $skpd1 = "AND b.kd_skpd='$id1'"; 

// UPDATE LPE TAHUN LALU
    		        
			$sqllo10="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek5,1) in ('8') and kd_org='$kd_skpd'";
                    $querylo10= $this->db->query($sqllo10);
                    $pen8 = $querylo10->row();
                    $pen_lalu8 = $pen8->nilai;
                    $pen_lalu81= number_format($pen8->nilai,"2",",",".");
		
			$sqllo12="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek5,1) in ('9') and kd_org='$kd_skpd'";
                    $querylo12= $this->db->query($sqllo12);
                    $bel10 = $querylo12->row();
                    $bel_lalu10 = $bel10->nilai;
                    $bel_lalu101= number_format($bel10->nilai,"2",",",".");

			$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org='$kd_skpd'";//aba
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)<$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
			and b.tabel=1 and reev=0 and kd_org='$kd_skpd'");  
	        foreach($query3->result_array() as $res2){
				 $debet=$res2['debet'];
				 $kredit=$res2['kredit'];
                 				 
			 }
			 
		$real=$kredit-$debet+$pen_lalu8-$bel_lalu10+$lpe_ll1+$lpe_ll2+$lpe_ll3;
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
		$sqllo9="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek5,1) in ('8') and kd_org='$kd_skpd'";
                    $querylo9= $this->db->query($sqllo9);
                    $penlo7 = $querylo9->row();
                    $pen_lo7 = $penlo7->nilai;
                    $pen_lo71= number_format($penlo7->nilai,"2",",",".");
        
		$sqllo10="select sum(kredit-debet) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('8') and kd_org='$kd_skpd'";
                    $querylo10= $this->db->query($sqllo10);
                    $penlo8 = $querylo10->row();
                    $pen_lo_lalu8 = $penlo8->nilai;
                    $pen_lo_lalu81= number_format($penlo8->nilai,"2",",",".");
		
		$sqllo11="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek5,1) in ('9') and kd_org='$kd_skpd'";
                    $querylo11= $this->db->query($sqllo11);
                    $bello9 = $querylo11->row();
                    $bel_lo9 = $bello9->nilai;
                    $bel_lo91= number_format($bello9->nilai,"2",",",".");
		
		$sqllo12="select sum(debet-kredit) as nilai from trdju a inner join trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek5,1) in ('9') and kd_org='$kd_skpd'";
                    $querylo12= $this->db->query($sqllo12);
                    $bello10 = $querylo12->row();
                    $bel_lo_lalu10 = $bello10->nilai;
                    $bel_lo_lalu101= number_format($bello10->nilai,"2",",",".");		

					$surplus_lo3 = $pen_lo7 - $bel_lo9;
                    
					$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

					$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
                    

			$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_org='$kd_skpd'";//aba
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang_1 and kd_org='$kd_skpd'";//Henri_TB
                            
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
            $sql = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju a
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org='$kd_skpd'";//aba
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org='$kd_skpd'";//Henri_TB
                            
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
					inner join trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek5=3110101 and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_org='$kd_skpd'";//Henri_TB
                            
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
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong> </td>                         
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
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$cx".number_format($real,"2",",",".")."$dx</td>
                                                     </tr>";
                                            
                                    break; 
                                    case 2:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> $lo13".number_format($surplus_lo3,"2",",",".")."$lo14</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lo15".number_format($surplus_lo_lalu3,"2",",",".")."$lo16</td>
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
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lalu1".number_format($lpe_lalu2,"2",",",".")."$lalu2</td>
                                                     </tr>";
									break;
                                    case 5:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$l000".number_format($nilaiDR,"2",",",".")."$p000</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lalu3".number_format($lpe_lalu1,"2",",",".")."$lalu4</td>
                                                     </tr>";
									break;
                                    case 6:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$l002".number_format($nilailpe2,"2",",",".")."$p002</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$lalu5".number_format($lpe_lalu3,"2",",",".")."$lalu6</td>
                                                     </tr>";
									break;
                                    case 7:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$c".number_format($sal_akhir,"2",",",".")."$d</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$c1".number_format($sal_awal,"2",",",".")."$d1</td>
                                                     </tr>";
                                } 
                       
                    }                           

        $cRet .='</table>';
                 
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = ("LPE KONSOL SKPD $cbulan");
        $this->template->set('title', 'LPE KONSOL SKPD $cbulan');  
         switch ($cetak)
        {
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
    
	
	

}
