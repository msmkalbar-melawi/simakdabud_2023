<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


use mikehaertl\wkhtmlto\Pdf;

require_once('application/3rdparty/wkhtmltopdf/Pdf.php');

class Perdase extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->library("custom");
	}

	//================================================ LRA
	function akun_krim()
	{
		$data['page_title'] = 'CETAK LRA';
		$this->template->set('title', 'CETAK LRA');
		$this->template->load('template', 'perda/cetak_akun', $data);
	}

    function left($string, $count)
	{
		return substr($string, 0, $count);
	}

	function cetak_lra_pemkot_33_permen($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd = '', $tanggal_ttd = '', $ttdperda = '', $label = '')
	{

		$lntahunang = $this->session->userdata('pcThang');
		$ttd1 = str_replace('n', ' ', $ttdperda);

		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER I";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
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
		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($anggaran == 1) {
			$initang = "nilai_ang";
		} else {
			$initang = "nilai_ang_ubah";
		}

		if ($label == '1') {
			$label = 'UNAUDITED';
		} else if ($label == '2') {
			$label = 'AUDITED';
		} else {
			$label = '&nbsp;';
		}

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet = "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $judul TAHUN $lntahunang</b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$label</b></tr>
						</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
					<thead>
					<tr>
						<td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
						<td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
						<td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
						<td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
					</tr>
					<tr>
						<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
						<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
						<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
						<td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
						</tr>
						<tr>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
					</tr>
					</thead> ";

		$sql = "SELECT 
						SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
						SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
						FROM
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a;
						";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
						SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
						SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
						FROM
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,2) IN ('61','62') $where
						GROUP BY LEFT(kd_ang,2)) a;
						";


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi FROM map_lra_pemkot ORDER BY urut
						";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$urut = $row->urut;
			$kode = $row->kd_rek;
			$nama = $row->uraian;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$kode5 = $row->kode5;
			$spasi = $row->spasi;

			$sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot where bulan='$bulan' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}
			if ($sel < 0) {
				$sel1 = $sel * -1;
				$t = '(';
				$u = ')';	
			}  else {
				$sel1 = $sel;
				$t = '';
				$u = '';
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
								   <td align="left" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;
				case 2:
					$cRet .= '<tr>
								   <td align="left" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 3:
					$cRet .= '<tr>
								   <td align="left" valign="top">' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								</tr>';
					break;
				case 4:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 5:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
								   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
								   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 6;
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
								   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
								   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 7;
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
								   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
								   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
								</tr>';
					break;

				default:

					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;
			}
		}



		$cRet .= "</table>";


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='" . SKPD_BKD . "'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$daerah  = $rowsc->daerah;
		}

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$ttd1' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}


		if ($ttd1 != '1') {
			$xx = "<u>";
			$xy = "</u>";
			$nipxx = $nip;
			$nipx = "NIP.";
		} else {
			$xx = "";
			$xy = "";
			$nipxx = "";
			$nipx = "";
		}
		if ($tanggal_ttd == 1) {
			$tgltd = '';
		} else {
			$tgltd = $this->custom->tanggal_format_indonesia($tanggal_ttd);
		}

		if ($nip == '00000000 000000 0 000'){
		$cRet .= '<br><br>
			<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
							<TD align="center" >' . $xx . '<b>' . $namax . '</b>' . $xy . '</TD>
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
			<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
							<TD align="center" >' . $xx . '<b>' . $namax . '</b>' . $xy . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $pangkat . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
						</TR>
						</TABLE><br/>';
		}

		$data['prev'] = $cRet;
		$judul = 'LRA 33 ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->support->_mpdf('', $cRet, 10, 10, 10, '1');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lra_baru($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd = '', $tanggal_ttd = '', $ttdperda = '', $label = '')
	{

		$lntahunang = $this->session->userdata('pcThang');
		$ttd1 = str_replace('n', ' ', $ttdperda);

		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER I";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
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
		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($anggaran == 1) {
			$initang = "nilai_ang";
		} else {
			$initang = "nilai_ang_ubah";
		}

		if ($label == '1') {
			$label = 'UNAUDITED';
		} else if ($label == '2') {
			$label = 'AUDITED';
		} else {
			$label = '&nbsp;';
		}

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet = "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $judul TAHUN $lntahunang</b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$label</b></tr>
						</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
					<thead>
					<tr>
						<td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>NO</b></td>
						<td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
						<td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
						<td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
					</tr>
					<tr>
						<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
						<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
						<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
						<td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
						</tr>
						<tr>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
					</tr>
					</thead> ";

		$sql = "SELECT 
						SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
						SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
						FROM
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a;
						";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
						SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
						SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
						FROM
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,2) IN ('61','62') $where
						GROUP BY LEFT(kd_ang,2)) a;
						";


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT kode, nama, kode1, kode2, kode3,kode4,kode5,spasi FROM map_lra_sap_baru";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$kode = $row->kode;
            $nama = $row->nama;
            $kode1 = $row->kode1;
            $kode2 = $row->kode2;
            $kode3 = $row->kode3;
            $kode4 = $row->kode4;
            $kode5 = $row->kode5;
            $spasi = $row->spasi;

			$sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot where bulan='$bulan' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}
			if ($sel < 0) {
				$sel1 = $sel * -1;
				$t = '(';
				$u = ')';	
			}  else {
				$sel1 = $sel;
				$t = '';
				$u = '';
			}
			
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
								   <td align="center" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;
				case 2:
					$cRet .= '<tr>
								   <td align="center" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 3:
					$cRet .= '<tr>
									   <td align="center" valign="top">' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								</tr>';
					break;
				case 4:
					$cRet .= '<tr>
								   <td align="center" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 5:
					$cRet .= '<tr>
								   <td align="center" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
								   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
								   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 6;
					$cRet .= '<tr>
								   <td align="center" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
								   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
								   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 7;
					$cRet .= '<tr>
								   <td align="center" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
								   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
								   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
								</tr>';
					break;

				default:

					$cRet .= '<tr>
								   <td align="center" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;
			}
		}



		$cRet .= "</table>";


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='" . SKPD_BKD . "'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$daerah  = $rowsc->daerah;
		}

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$ttd1' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}


		if ($ttd1 != '1') {
			$xx = "<u>";
			$xy = "</u>";
			$nipxx = $nip;
			$nipx = "NIP.";
		} else {
			$xx = "";
			$xy = "";
			$nipxx = "";
			$nipx = "";
		}
		if ($tanggal_ttd == 1) {
			$tgltd = '';
		} else {
			$tgltd = $this->custom->tanggal_format_indonesia($tanggal_ttd);
		}
		if ($nip == '00000000 000000 0 000'){
			$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
								<TD align="center" >' . $xx . '<b>' . $namax . '</b>' . $xy . '</TD>
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
				<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
								<TD align="center" >' . $xx . '<b>' . $namax . '</b>' . $xy . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
							</TR>
							</TABLE><br/>';
			}


		$data['prev'] = $cRet;
		$judul = 'LRA';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->support->_mpdf('', $cRet, 10, 10, 10, '1');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lra90_objek($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd = '', $tanggal_ttd = '', $ttdperda = '', $label = '')
	{

		$lntahunang = $this->session->userdata('pcThang');
		$ttd1 = str_replace('n', ' ', $ttdperda);

		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER I";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER II";
				break;
		}
		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($anggaran == 1) {
			$initang = "nilai_ang";
		} else {
			$initang = "nilai_ang_ubah";
		}

		if ($label == '1') {
			$label = 'UNAUDITED';
		} else if ($label == '2') {
			$label = 'AUDITED';
		} else {
			$label = '&nbsp;';
		}

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet = "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $judul TAHUN $lntahunang</b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$label</b></tr>
						</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
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
						SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
						SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
						FROM
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a;
						
						";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
						SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
						SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
						FROM
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,2) IN ('61','62') $where
						GROUP BY LEFT(kd_ang,2)) a;
						";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi FROM map_lra_pemkot ORDER BY urut
						";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$urut = $row->urut;
			$kode = $row->kd_rek;
			$nama = $row->uraian;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$kode5 = $row->kode5;
			$spasi = $row->spasi;

			$sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilairealisasi FROM data_realisasi_pemkot where bulan='$bulan' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilairealisasi;
			}
			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
								   <td align="left" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';


					break;
				case 2:
					$cRet .= '<tr>
								   <td align="left" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';

					break;
				case 3:
					$cRet .= '<tr>
								   <td align="left" valign="top">' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								</tr>';

					if (substr(str_replace("'", '', $kode4), 0, 1) != 'x') {
						$sql_3_rek6 = "SELECT LEFT(a.kd_rek6,6)rek_RO,(select nm_rek4 from ms_rek4 where kd_rek4=LEFT(a.kd_rek6,6))rek6_objek,SUM($initang) as nil_ang, SUM(real_spj) as nilairealisasi 
								FROM data_realisasi_pemkot a join ms_rek6 b on a.kd_rek6=b.kd_rek6	
								where bulan='$bulan'  and (left(kd_ang,1) in ($kode1) or left(kd_ang,2) in ($kode2) or LEFT(kd_ang,4) IN ($kode3) or left(kd_ang,6) in ($kode4) )
								group by LEFT(a.kd_rek6,6) order by LEFT(a.kd_rek6,6)";

						$hasil_3_rek6 = $this->db->query($sql_3_rek6);

						foreach ($hasil_3_rek6->result() as $row3_rek6) {
							$rek_objek = str_replace("'", ' ', $row3_rek6->rek_RO);
							$rek_objek_cetak = substr($rek_objek, 0, 1) . '.' . substr($rek_objek, 1, 1) . '.' . substr($rek_objek, 2, 2) . '.' . substr($rek_objek, 4, 2);

							$sel_3_rek6 = $row3_rek6->nil_ang - $row3_rek6->nilairealisasi;
							if (($row3_rek6->nil_ang == 0) || ($row3_rek6->nil_ang == '')) {
								$persen_3_rek6 = 0;
							} else {
								$persen_3_rek6 = $row3_rek6->nilairealisasi / $row3_rek6->nil_ang * 100;
							}

							if (strlen($kode) < 10) {
								$cRet .= '<tr>
										<td align="left" valign="top">' . $rek_objek_cetak . '</b></td> 
										<td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $row3_rek6->rek6_objek . '</td> 
										<td align="right" valign="top">' . number_format($row3_rek6->nil_ang, "2", ",", ".") . '</td> 
										<td align="right" valign="top">' . number_format($row3_rek6->nilairealisasi, "2", ",", ".") . '</td> 
										<td align="right" valign="top">' . number_format($sel_3_rek6, "2", ",", ".") . '</td> 
										<td align="right" valign="top">' . number_format($persen_3_rek6, "2", ",", ".") . '</td> 
										</tr>';
							}
						}
					}

					break;
				case 4:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';


					break;
				case 5:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
								   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
								   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 6;
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
								   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
								   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 7;
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
								   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
								   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
								</tr>';
					break;

				default:

					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;
			}
		}



		$cRet .= "</table>";


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='" . SKPD_BKD . "'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$daerah  = $rowsc->daerah;
		}

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='agr' or kode='wk' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}


		if ($ttd1 != '1') {
			$xx = "<u>";
			$xy = "</u>";
			$nipxx = $nip;
			$nipx = "NIP.";
		} else {
			$xx = "";
			$xy = "";
			$nipxx = "";
			$nipx = "";
		}
		if ($tanggal_ttd == 1) {
			$tgltd = '';
		} else {
			$tgltd = $this->custom->tanggal_format_indonesia($tanggal_ttd);
		}
		if ($nip == '00000000 000000 0 000'){
			$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
				<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
								<TD align="center" >' . $xx . '<b>' . $namax . '</b>' . $xy . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
							</TR>
							</TABLE><br/>';
			}
	


		$data['prev'] = $cRet;
		$judul = 'LRA PERMEN 90 OBJEK ';
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

	function cetak_lra_pemkot_90_sub_ro($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd = '', $tanggal_ttd = '', $ttdperda = '', $label = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$ttd1 = str_replace('n', ' ', $ttdperda);

		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER I";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER II";
				break;
		}

		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($anggaran == 1) {
			$initang = "nilai_ang";
		} else {
			$initang = "nilai_ang_ubah";
		}

		if ($label == '1') {
			$label = 'UNAUDITED';
		} else if ($label == '2') {
			$label = 'AUDITED';
		} else {
			$label = '&nbsp;';
		}

		$sql = $this->db->query("SELECT top 1 kab_kota from sclient");
		$query = $sql->row();
		$kab_kota = $query->kab_kota;

		$cRet = "	<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
						<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
						</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\">
							<strong>$kab_kota</strong>
						</td>
					</tr>
					<tr>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\">
							<b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b>
						</tr>
					<tr>
						<td align=\"center\" style=\"border-left:hidden;border-top:hidden\" >
							<b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $judul TAHUN $lntahunang</b>
						</tr>
					<tr>	
						<td align=\"center\" style=\"border-left:hidden;border-top:hidden\" >
							<b>$label</b>
						</td>
					</tr>
				</TABLE>";

		$cRet .= "	<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
					<thead>
						<tr>
							<td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
							<td rowspan=\"2\" colspan=\"6\" width=\"33%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
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
							<td colspan=\"6\" align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
							<td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
							<td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
							<td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
							<td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
						</tr>
					</thead>";

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
					SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
					SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
					SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
					SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
					SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
					SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
					FROM
					(
						SELECT LEFT(kd_ang,1) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot 
						where bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}

		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}

		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}

		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}

		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
				SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
				SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
				FROM
				(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot 
				where bulan='$bulan' and LEFT(kd_ang,2) IN ('61','62') $where
				GROUP BY LEFT(kd_ang,2)) a";


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}

		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}

		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}

		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}

		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}

		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}

		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}

		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}

		$sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi FROM map_lra_pemkot ORDER BY urut";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$urut = $row->urut;
			$kode = $row->kd_rek;
			$nama = $row->uraian;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$kode5 = $row->kode5;
			$spasi = $row->spasi;

			$sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM 
					data_realisasi_pemkot where bulan='$bulan' and (LEFT(kd_ang,1) 
					IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) 
					or LEFT(kd_ang,4) IN ($kode3) 
					or LEFT(kd_ang,6) IN ($kode4) 
					or LEFT(kd_ang,8) IN ($kode5)) $where";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}

			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
								<td align="left" valign="top"><b>' . $kode . '</b></td> 
								<td colspan="6" align="left"  valign="top"><b>' . $nama . '</b></td> 
								<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
								<td align="left"  valign="top"><b>' . $kode . '</b></td> 
								<td align="left"  width="1%" valign="top" style="border-right:none"></td> 
								<td colspan="5" width="32%" align="left"  valign="top" style="border-left:none"><b>' . $nama . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
								<td align="left" valign="top">' . $kode . '</b></td> 
								<td colspan="2" align="left"  width="2%" valign="top" style="border-right:none"></td> 
								<td colspan="4" align="left"  width="31%" valign="top" style="border-left:none">' . $nama . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';

					// if kode=X then kode is not exist		
					$is_kode3 = strtoupper(substr(str_replace("'", "", $kode3), 0, 1));
					$is_kode4 = strtoupper(substr(str_replace("'", "", $kode4), 0, 1));
					$is_kode5 = strtoupper(substr(str_replace("'", "", $kode5), 0, 1));

					if ($is_kode3 != 'X') {
						$sql = "SELECT '8' as spasi, b.kd_rek4 as kd_rek,b.nm_rek4 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
								join ms_rek4 b on left(a.kd_rek6,6)=b.kd_rek4 where a.bulan='$bulan' and 
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) 
								group by b.kd_rek4,b.nm_rek4
								union all
								SELECT '9' as spasi,b.kd_rek5 as kd_rek,b.nm_rek5 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
								join ms_rek5 b on left(a.kd_rek6,8)=b.kd_rek5 where a.bulan='$bulan' and 
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) 
								group by b.kd_rek5,b.nm_rek5
								union all
								SELECT '10' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
								where a.bulan='$bulan' and 
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) 
								group by a.kd_rek6,a.nm_rek6 order by kd_rek";
					} else {
						if ($is_kode4 != 'X') {
							$sql = "SELECT '8' as spasi,b.kd_rek5 as kd_rek,b.nm_rek5 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
									join ms_rek5 b on left(a.kd_rek6,8)=b.kd_rek5 where a.bulan='$bulan' and 
									(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) 
									group by b.kd_rek5,b.nm_rek5
									union all
									SELECT '9' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
									where a.bulan='$bulan' and 
									(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) 
									group by a.kd_rek6,a.nm_rek6 order by kd_rek";
						} else {
							if ($is_kode5 != 'X') {
								$sql = "SELECT '8' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
										where a.bulan='$bulan' and 
										(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) 
										group by a.kd_rek6,a.nm_rek6 order by kd_rek";
							}
						}
					}

					if ($sql) {
						$result = $this->db->query($sql);
						foreach ($result->result() as $row1) {
							$spasi3 = $row1->spasi;
							$nilai_anggaran = $row1->nil_ang;
							$nilai_realisasi = $row1->nilai;

							$selisih = $nilai_anggaran - $nilai_realisasi;
							if (($nilai_anggaran == 0) || ($nilai_anggaran == '')) {
								$persen = 0;
							} else {
								$persen = $nilai_realisasi / $nilai_anggaran * 100;
							}

							switch ($spasi3) {
								case 8:
									$cRet .= '<tr>
												<td align="left" valign="top">' . $this->custom->dotrek($row1->kd_rek) . '</b></td> 
												<td colspan="3" align="left"  width="3%" valign="top" style="border-right:none">&nbsp;</td>
												<td colspan="3" align="left"  width="30%" valign="top" style="border-left:none">' . $row1->nm_rek . '</td> 
												<td align="right" valign="top">' . number_format($nilai_anggaran, "2", ",", ".") . '</td> 
												<td align="right" valign="top">' . number_format($nilai_realisasi, "2", ",", ".") . '</td> 
												<td align="right" valign="top">' . number_format($selisih, "2", ",", ".") . '</td> 
												<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
											</tr>';
									break;
								case 9:
									$cRet .= '<tr>
													<td align="left" valign="top">' . $this->custom->dotrek($row1->kd_rek) . '</b></td> 
													<td colspan="4" align="left"  width="4%" valign="top" style="border-right:none"></td>
													<td colspan="2" align="left"  width="29%" valign="top" style="border-left:none">' . $row1->nm_rek . '</td> 
													<td align="right" valign="top">' . number_format($nilai_anggaran, "2", ",", ".") . '</td> 
													<td align="right" valign="top">' . number_format($nilai_realisasi, "2", ",", ".") . '</td> 
													<td align="right" valign="top">' . number_format($selisih, "2", ",", ".") . '</td> 
													<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
												</tr>';
									break;
								case 10:
									$cRet .= '<tr>
														<td align="left" valign="top">' . $this->custom->dotrek($row1->kd_rek) . '</b></td> 
														<td colspan="5" align="left"  width=\"5%\" valign="top" style="border-right:none"></td>
														<td align="left"  valign="top" width="28%" style="border-left:none">' . $row1->nm_rek . '</td> 
														<td align="right" valign="top">' . number_format($nilai_anggaran, "2", ",", ".") . '</td> 
														<td align="right" valign="top">' . number_format($nilai_realisasi, "2", ",", ".") . '</td> 
														<td align="right" valign="top">' . number_format($selisih, "2", ",", ".") . '</td> 
														<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
													</tr>';
									break;
								default:
									# code...
									break;
							}
						}
					}
					$sql = false;

					break;
				case 4:
					$cRet .= '<tr>
								<td align="left" valign="top" ><b>' . $kode . '</b></td> 
								<td width="1%" align="left" valign="top" style="border-right:none"></td>
								<td colspan="5" align="left"  valign="top" style="border-left:none"><b>' . $nama . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							<td align="left" valign="top" ><b>' . $kode . '</b></td> 
							<td colspan="6" align="right" valign="top"><b>' . $nama . '</b></td> 
							<td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
							<td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
							<td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							<td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
						</tr>';
					break;
				case 6;
					$cRet .= '<tr>
							<td align="left" valign="top" ><b>' . $kode . '</b></td> 
							<td colspan="6" align="right" valign="top"><b>' . $nama . '</b></td> 
							<td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
							<td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
							<td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							<td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
						</tr>';
					break;
				case 7;
					$cRet .= '<tr>
							<td align="left" valign="top" ><b>' . $kode . '</b></td> 
							<td colspan="6" align="right" valign="top"><b>' . $nama . '</b></td> 
							<td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							<td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							<td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							<td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
						</tr>';
					break;
				default:
					$cRet .= '<tr>
						<td align="left" valign="top" ><b>' . $kode . '</b></td> 
						<td colspan="6" align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
						<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
						<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
						<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
					</tr>';
					break;
			}
		}

		$cRet .= "</table>";


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='" . SKPD_BKD . "'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$daerah  = $rowsc->daerah;
		}

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' 
					and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}


		if ($ttd1 != '1') {
			$xx = "<u>";
			$xy = "</u>";
			$nipxx = $nip;
			$nipx = "NIP.";
		} else {
			$xx = "";
			$xy = "";
			$nipxx = "";
			$nipx = "";
		}

		if ($tanggal_ttd == 1) {
			$tgltd = '';
		} else {
			$tgltd = $this->custom->tanggal_format_indonesia($tanggal_ttd);
		}

		if ($nip == '00000000 000000 0 000'){
			$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
				<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
								<TD align="center" >' . $xx . '<b>' . $namax . '</b>' . $xy . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
							</TR>
							</TABLE><br/>';
			}


		$data['prev'] = $cRet;
		$judul = 'LRA PERMEN 90 SUB RO';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$pdf = new Pdf(array(
					'binary' => $this->config->item('wkhtmltopdf_path'),
					'orientation' => 'Portrait',
					'title' => $judul,
					'footer-center' => 'Halaman [page] / [topage]',
					'footer-left' => 'Printed on @ [date] [time]',
					'footer-font-size' => 6,
				));
				$pdf->addPage($cRet);
				$pdf->send();
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lra_pemkot_90_ro($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd = '', $tanggal_ttd = '', $ttdperda = '', $label = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$ttd1 = str_replace('n', ' ', $ttdperda);

		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER I";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER II";
				break;
		}

		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($anggaran == 1) {
			$initang = "nilai_ang";
		} else {
			$initang = "nilai_ang_ubah";
		}

		if ($label == '1') {
			$label = 'UNAUDITED';
		} else if ($label == '2') {
			$label = 'AUDITED';
		} else {
			$label = '&nbsp;';
		}

		$sql = $this->db->query("SELECT top 1 kab_kota from sclient");
		$query = $sql->row();
		$kab_kota = $query->kab_kota;

		$cRet = "	<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
						<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
						</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\">
							<strong>$kab_kota</strong>
						</td>
					</tr>
					<tr>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\">
							<b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b>
						</tr>
					<tr>
						<td align=\"center\" style=\"border-left:hidden;border-top:hidden\" >
							<b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $judul TAHUN $lntahunang</b>
						</tr>
					<tr>	
						<td align=\"center\" style=\"border-left:hidden;border-top:hidden\" >
							<b>$label</b>
						</td>
					</tr>
				</TABLE>";

		$cRet .= "	<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
					<thead>
						<tr>
							<td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
							<td rowspan=\"2\" colspan=\"6\" width=\"33%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
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
							<td colspan=\"6\" align=\"center\" bgcolor=\"#CCCCCC\" >2</td> 
							<td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
							<td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
							<td align=\"center\" bgcolor=\"#CCCCCC\" >5</td> 
							<td align=\"center\" bgcolor=\"#CCCCCC\" >6</td> 
						</tr>
					</thead>";

		$sql = "SELECT 
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
					SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
					SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
					SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
					SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
					SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
					SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
					FROM
					(
						SELECT LEFT(kd_ang,1) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot 
						where bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}

		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}

		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}

		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}

		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
				SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
				SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
				FROM
				(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot 
				where bulan='$bulan' and LEFT(kd_ang,2) IN ('61','62') $where
				GROUP BY LEFT(kd_ang,2)) a";


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}

		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}

		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}

		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}

		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}

		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}

		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}

		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}

		$sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi FROM map_lra_pemkot ORDER BY urut";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$urut = $row->urut;
			$kode = $row->kd_rek;
			$nama = $row->uraian;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$kode5 = $row->kode5;
			$spasi = $row->spasi;

			$sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM 
					data_realisasi_pemkot where bulan='$bulan' and (LEFT(kd_ang,1) 
					IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) 
					or LEFT(kd_ang,4) IN ($kode3) 
					or LEFT(kd_ang,6) IN ($kode4) 
					or LEFT(kd_ang,8) IN ($kode5)
					) $where";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}

			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
								<td align="left" valign="top"><b>' . $kode . '</b></td> 
								<td colspan="6" align="left"  valign="top"><b>' . $nama . '</b></td> 
								<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
								<td align="left"  valign="top"><b>' . $kode . '</b></td> 
								<td align="left"  width="1%" valign="top" style="border-right:none"></td> 
								<td colspan="5" width="32%" align="left"  valign="top" style="border-left:none"><b>' . $nama . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
								<td align="left" valign="top">' . $kode . '</b></td> 
								<td colspan="2" align="left"  width="2%" valign="top" style="border-right:none"></td> 
								<td colspan="4" align="left"  width="31%" valign="top" style="border-left:none">' . $nama . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';

					// if kode=X then kode is not exist		
					$is_kode3 = strtoupper(substr(str_replace("'", "", $kode3), 0, 1));
					$is_kode4 = strtoupper(substr(str_replace("'", "", $kode4), 0, 1));
					$is_kode5 = strtoupper(substr(str_replace("'", "", $kode5), 0, 1));

					if ($is_kode3 != 'X') {
						$sql = "SELECT '8' as spasi, b.kd_rek4 as kd_rek,b.nm_rek4 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
								join ms_rek4 b on left(a.kd_rek6,6)=b.kd_rek4 where a.bulan='$bulan' and 
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4)) 
								group by b.kd_rek4,b.nm_rek4
								union all
								SELECT '9' as spasi,b.kd_rek5 as kd_rek,b.nm_rek5 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
								join ms_rek5 b on left(a.kd_rek6,8)=b.kd_rek5 where a.bulan='$bulan' and 
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4)) 
								group by b.kd_rek5,b.nm_rek5
								union all
								SELECT '10' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
								where a.bulan='$bulan' and 
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4)) 
								group by a.kd_rek6,a.nm_rek6 order by kd_rek";
					} else {
						if ($is_kode4 != 'X') {
							$sql = "SELECT '8' as spasi,b.kd_rek5 as kd_rek,b.nm_rek5 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
									join ms_rek5 b on left(a.kd_rek6,8)=b.kd_rek5 where a.bulan='$bulan' and 
									(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4)) 
									group by b.kd_rek5,b.nm_rek5
									union all
									SELECT '9' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
									where a.bulan='$bulan' and 
									(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4)) 
									group by a.kd_rek6,a.nm_rek6 order by kd_rek";
						} else {
							if ($is_kode5 != 'X') {
								$sql = "SELECT '8' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
										where a.bulan='$bulan' and 
										(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4)) 
										group by a.kd_rek6,a.nm_rek6 order by kd_rek";
							}
						 }
					}

					if ($sql) {
						$result = $this->db->query($sql);
						foreach ($result->result() as $row1) {
							$spasi3 = $row1->spasi;
							$nilai_anggaran = $row1->nil_ang;
							$nilai_realisasi = $row1->nilai;

							$selisih = $nilai_anggaran - $nilai_realisasi;
							if (($nilai_anggaran == 0) || ($nilai_anggaran == '')) {
								$persen = 0;
							} else {
								$persen = $nilai_realisasi / $nilai_anggaran * 100;
							}

							switch ($spasi3) {
								case 7:
									$cRet .= '<tr>
												<td align="left" valign="top">' . $this->custom->dotrek($row1->kd_rek) . '</b></td> 
												<td colspan="3" align="left"  width="3%" valign="top" style="border-right:none">&nbsp;</td>
												<td colspan="3" align="left"  width="30%" valign="top" style="border-left:none">' . $row1->nm_rek . '</td> 
												<td align="right" valign="top">' . number_format($nilai_anggaran, "2", ",", ".") . '</td> 
												<td align="right" valign="top">' . number_format($nilai_realisasi, "2", ",", ".") . '</td> 
												<td align="right" valign="top">' . number_format($selisih, "2", ",", ".") . '</td> 
												<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
											</tr>';
									break;
								case 8:
									$cRet .= '<tr>
													<td align="left" valign="top">' . $this->custom->dotrek($row1->kd_rek) . '</b></td> 
													<td colspan="4" align="left"  width="4%" valign="top" style="border-right:none"></td>
													<td colspan="2" align="left"  width="29%" valign="top" style="border-left:none">' . $row1->nm_rek . '</td> 
													<td align="right" valign="top">' . number_format($nilai_anggaran, "2", ",", ".") . '</td> 
													<td align="right" valign="top">' . number_format($nilai_realisasi, "2", ",", ".") . '</td> 
													<td align="right" valign="top">' . number_format($selisih, "2", ",", ".") . '</td> 
													<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
												</tr>';
									break;
								case 9:
									$cRet .= '<tr>
														<td align="left" valign="top">' . $this->custom->dotrek($row1->kd_rek) . '</b></td> 
														<td colspan="5" align="left"  width=\"5%\" valign="top" style="border-right:none"></td>
														<td align="left"  valign="top" width="28%" style="border-left:none">' . $row1->nm_rek . '</td> 
														<td align="right" valign="top">' . number_format($nilai_anggaran, "2", ",", ".") . '</td> 
														<td align="right" valign="top">' . number_format($nilai_realisasi, "2", ",", ".") . '</td> 
														<td align="right" valign="top">' . number_format($selisih, "2", ",", ".") . '</td> 
														<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
													</tr>';
									break;
								default:
									# code...
									break;
							}
						}
					}
					$sql = false;

					break;
				case 4:
					$cRet .= '<tr>
								<td align="left" valign="top" ><b>' . $kode . '</b></td> 
								<td width="1%" align="left" valign="top" style="border-right:none"></td>
								<td colspan="5" align="left"  valign="top" style="border-left:none"><b>' . $nama . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								<td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							<td align="left" valign="top" ><b>' . $kode . '</b></td> 
							<td colspan="6" align="right" valign="top"><b>' . $nama . '</b></td> 
							<td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
							<td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
							<td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							<td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
						</tr>';
					break;
				case 6;
					$cRet .= '<tr>
							<td align="left" valign="top" ><b>' . $kode . '</b></td> 
							<td colspan="6" align="right" valign="top"><b>' . $nama . '</b></td> 
							<td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
							<td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
							<td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							<td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
						</tr>';
					break;
				case 7;
					$cRet .= '<tr>
							<td align="left" valign="top" ><b>' . $kode . '</b></td> 
							<td colspan="6" align="right" valign="top"><b>' . $nama . '</b></td> 
							<td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							<td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							<td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							<td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
						</tr>';
					break;
				default:
					$cRet .= '<tr>
						<td align="left" valign="top" ><b>' . $kode . '</b></td> 
						<td colspan="6" align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
						<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
						<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
						<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
						<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
					</tr>';
					break;
			}
		}

		$cRet .= "</table>";


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='" . SKPD_BKD . "'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$daerah  = $rowsc->daerah;
		}

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' 
					and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd'  or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}


		if ($ttd1 != '1') {
			$xx = "<u>";
			$xy = "</u>";
			$nipxx = $nip;
			$nipx = "NIP.";
		} else {
			$xx = "";
			$xy = "";
			$nipxx = "";
			$nipx = "";
		}

		if ($tanggal_ttd == 1) {
			$tgltd = '';
		} else {
			$tgltd = $this->custom->tanggal_format_indonesia($tanggal_ttd);
		}

		if ($nip == '00000000 000000 0 000'){
			$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
				<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
								<TD align="center" >' . $xx . '<b>' . $namax . '</b>' . $xy . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
							</TR>
							</TABLE><br/>';
			}

		$data['prev'] = $cRet;
		$judul = 'LRA PERMEN 90 RO';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$pdf = new Pdf(array(
					'binary' => $this->config->item('wkhtmltopdf_path'),
					'orientation' => 'Portrait',
					'title' => $judul,
					'footer-center' => 'Halaman [page] / [topage]',
					'footer-left' => 'Printed on @ [date] [time]',
					'footer-font-size' => 6,
				));
				$pdf->addPage($cRet);
				$pdf->send();
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_lra_pemkot_64_ro($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd = '', $tanggal_ttd = '', $ttdperda = '', $label = '')
	{

		$lntahunang = $this->session->userdata('pcThang');
		$ttd1 = str_replace('n', ' ', $ttdperda);

		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER I";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
				break;
			case  10:
				$judul = "OKTOBER";
				break;
			case  11:
				$judul = "NOVEMBER";
				break;
			case  12:
				$judul = "SEMESTER II";
				break;
		}

		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($anggaran == 1) {
			$initang = "nilai_ang";
		} else {
			$initang = "nilai_ang_ubah";
		}

		if ($label == '1') {
			$label = 'UNAUDITED';
		} else if ($label == '2') {
			$label = 'AUDITED';
		} else {
			$label = '&nbsp;';
		}

		$sql = $this->db->query("select top 1 kab_kota from sclient");
		$query = $sql->row();
		$kab_kota = $query->kab_kota;

		$cRet = "	<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
						<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
						</td>
							<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>$kab_kota</strong>
						</td>
					</tr>
					<tr>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\">
							<b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA</b>
						</td>
					</tr>
					<tr>
						<td align=\"center\" style=\"border-left:hidden;border-top:hidden\">
							<b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $judul TAHUN $lntahunang</b>
						</td>
					</tr>
					<tr>
						<td align=\"center\" style=\"border-left:hidden;border-top:hidden\" >
							<b>$label</b>
						</td>		
					</tr>
				</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
					<thead>
						<tr>
							<td rowspan=\"2\" width=\"1%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>NO</b></td>
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
					SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
					SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
					SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
					SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
					SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
					SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
					SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
					SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
					FROM
					(
						SELECT LEFT(kd_ang,1) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj 
						FROM data_realisasi_pemkot 
						where bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)
					) a";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}

		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}

		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}

		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}

		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
				SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
				SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
				FROM
				(
					SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj 
					FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,2) IN ('61','62') $where
					GROUP BY LEFT(kd_ang,2)
				) a";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;

		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}

		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}

		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}

		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;

		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}

		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}

		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}

		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}


		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;

		$sql = "SELECT nomor, kode, nama, kode1, kode2, kode3,kode4,kode5,spasi FROM map_lra_sap_baru ORDER BY seq";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$urut = $row->nomor;
			$kode = $row->kode;
			$nama = $row->nama;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$kode5 = $row->kode5;
			$spasi = $row->spasi;

			$sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot 
					where bulan='$bulan' 
					and (	LEFT(kd_ang,1) IN ($kode1) or 
							LEFT(kd_ang,2) IN ($kode2) or 
							LEFT(kd_ang,4) IN ($kode3) or 
							LEFT(kd_ang,6) IN ($kode4)
						) or 
					LEFT(kd_ang,8) IN ($kode5) $where
					";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}

			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}

			switch ($spasi) {
				case 1:
					$cRet .= '	<tr>
									<td align="left" valign="top"><b>' . $kode . '</b></td> 
									<td align="left"  valign="top"><b>' . $nama . '</b></td> 
									<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
									<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
									<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
									<td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;
				case 2:
					$cRet .= '	<tr>
									<td align="left" valign="top"><b>' . $kode . '</b></td> 
									<td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
									<td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
									<td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
									<td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
									<td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 3:
					$cRet .= '	<tr>
									<td align="left" valign="top">' . $kode . '</b></td> 
									<td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
									<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
									<td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
									<td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
									<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								</tr>';



					if (substr(str_replace("'", '', $kode3), 0, 1) != 'x') {
						$sql_3_rek6 = "SELECT a.kd_rek6,b.nm_rek6,SUM($initang) as nil_ang, SUM(real_spj) as nilai 
								FROM data_realisasi_pemkot a join ms_rek6 b on a.kd_rek6=b.kd_rek6	
								where bulan='$bulan' and (LEFT(kd_ang,4) IN ($kode3))
								group by a.kd_rek6,b.nm_rek6 order by kd_rek6";
						$hasil_3_rek6 = $this->db->query($sql_3_rek6);

						foreach ($hasil_3_rek6->result() as $row3_rek6) {
							$sel_3_rek6 = $row3_rek6->nil_ang - $row3_rek6->nilai;
							if (($row3_rek6->nil_ang == 0) || ($row3_rek6->nil_ang == '')) {
								$persen_3_rek6 = 0;
							} else {
								$persen_3_rek6 = $row3_rek6->nilai / $row3_rek6->nil_ang * 100;
							}

							$cRet .= '<tr>
										<td align="left" valign="top">' . str_replace("'", ' ', $row3_rek6->kd_rek6) . '</b></td> 
										<td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $row3_rek6->nm_rek6 . '</td> 
										<td align="right" valign="top">' . number_format($row3_rek6->nil_ang, "2", ",", ".") . '</td> 
										<td align="right" valign="top">' . number_format($row3_rek6->nilai, "2", ",", ".") . '</td> 
										<td align="right" valign="top">' . number_format($sel_3_rek6, "2", ",", ".") . '</td> 
										<td align="right" valign="top">' . number_format($persen_3_rek6, "2", ",", ".") . '</td> 
									</tr>';
						}
					}
					break;
				case 4:
					$cRet .= '	<tr>
									<td align="left" valign="top" ><b>' . $kode . '</b></td> 
									<td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
									<td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
									<td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
									<td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
									<td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 5:
					$cRet .= '	<tr>
									<td align="left" valign="top" ><b>' . $kode . '</b></td> 
									<td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
									<td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
									<td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
									<td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
									<td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 6;
					$cRet .= '	<tr>
									<td align="left" valign="top" ><b>' . $kode . '</b></td> 
									<td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
									<td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
									<td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
									<td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
									<td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 7;
					$cRet .= '	<tr>
									<td align="left" valign="top" ><b>' . $kode . '</b></td> 
									<td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
									<td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
									<td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
									<td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
									<td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				default:
					$cRet .= '<tr>
							<td align="left" valign="top" ><b>' . $kode . '</b></td> 
							<td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
							<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
							<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
							<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
							<td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
						</tr>';
					break;
			}
		}

		$cRet .= "</table>";
		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='" . SKPD_BKD . "'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$daerah  = $rowsc->daerah;
		}

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='agr' or kode='wk' or kode='pa' or kode='BUP')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}

		if ($ttd1 != '1') {
			$xx = "<u>";
			$xy = "</u>";
			$nipxx = $nip;
			$nipx = "NIP.";
		} else {
			$xx = "";
			$xy = "";
			$nipxx = "";
			$nipx = "";
		}

		if ($tanggal_ttd == 1) {
			$tgltd = '';
		} else {
			$tgltd = $this->custom->tanggal_format_indonesia($tanggal_ttd);
		}

		$cRet .= '	<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>	
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $daerah . ', ' . $tgltd . '</TD>
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
							<TD align="center" >' . $xx . '<b>' . $namax . '</b>' . $xy . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
						</TR>
					</TABLE>
					<br/>';

		$data['prev'] = $cRet;
		$judul = 'LRA 64 RINCIAN OBJEK';
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

	//================================================ End LRA

	//================================================ Lamp Perda I
	function perdaI()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. I';
		$this->template->set('title', 'CETAK PERDA LAMP. I');
		$this->template->load('template', 'perda/cetak_perda_lampI', $data);
	}


	function cetak_perda_lampI_permen_spj33_perwa($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd = '', $tanggal_ttd = '', $ttdperda = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$ttd1 = str_replace('n', ' ', $ttdperda);
		$banyak = $this->uri->segment(11);
		$modtahun = $lntahunang % 4;

		if ($modtahun = 0) {
			/*          $nilaibulan=".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";}
					else {
				$nilaibulan=".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";}
		*/
			$nilaibulan = ".JANUARI. FEBRUARI. MARET. APRIL. MEI. JUNI. JULI. AGUSTUS. SEPTEMBER. OKTOBER. NOVEMBER. DESEMBER";
		} else {
			$nilaibulan = ".JANUARI. FEBRUARI. MARET. APRIL. MEI. JUNI. JULI. AGUSTUS. SEPTEMBER. OKTOBER. NOVEMBER. DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($anggaran == '1') {
			$ang = "nilai_ang";
		} else {
			$ang = "nilai_ang_ubah";
		}

		$lntahunangg = $lntahunang + 1;
		$Intahunlalu = $lntahunang - 1;

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
						<TR>
							<!--<TD  width="60%" valign="top" align="right" > Lampiran I : </TD>
							<TD width="40%"  align="left" >Peraturan Daerah <br>Nomor : <br>Tanggal: </TD>-->
						<TD  width="60%" valign="top" align="right" ></TD>
						<TD width="40%"  align="left" ></TD>
						</TR>
						<tr>
						</TABLE><br/>';

		$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						<TR>
							<TD width="70%" align="center" ><b>&nbsp;</TD>
							<TD width="30%" align="left" >LAMPIRAN I &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN BUPATI MELAWI <br/> 
							NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangg . ' <br/>TENTANG PENJABARAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
							</TD>
						</TR>
						<TR>
							<TD width="70%" align="center" ><b>&nbsp;</TD>
							<TD width="30%" align="center" ><b>&nbsp;</TD>
						</TR>
						</TABLE><br/>';

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" .FCPATH. "/image/logoHP.png\"  width=\"50\" height=\"75\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
						<!--<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>PER $arraybulan[$bulan] $lntahunang</b></tr>-->
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN  </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>                    
						</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"$banyak\">
					<thead>
					<tr>
						<td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
						<td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
						<td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
						<td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
						<td colspan=\"1\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI $Intahunlalu</b></td>
					</tr>
					<tr>
						<td width=\"19%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
						<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
						<td width=\"18%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
						<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
						<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
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

		$sql = "SELECT 
						SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
						SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
						FROM
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM($ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a;
						";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
						SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
						SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
						FROM
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,2) IN ('61','62') $where
						GROUP BY LEFT(kd_ang,2)) a;
						";


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi,tahun_lalu FROM map_lra_pemkot ORDER BY urut
						";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$urut = $row->urut;
			$kode = $row->kd_rek;
			$nama = $row->uraian;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$kode5 = $row->kode5;
			$spasi = $row->spasi;
			$tahun_lalu = $row->tahun_lalu;

			$sql = "SELECT SUM($ang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot where bulan='$bulan' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}
			if ($sel < 0) {
				$sel1 = $sel * -1;
				$t = '(';
				$u = ')';	
			}  else {
				$sel1 = $sel;
				$t = '';
				$u = '';
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
								   <td align="left" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;
				case 2:
					$cRet .= '<tr>
								   <td align="left" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;
				case 3:
					$cRet .= '<tr>
								   <td align="left" valign="top">' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($tahun_lalu, "2", ",", ".") . '</td> 
								</tr>';
					break;
				case 4:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 5:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
								   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
								   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 6;
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
								   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
								   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td>
								</tr>';
					break;
				case 7;
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
								   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
								   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
								    <td align="right" valign="top" ><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td>
								</tr>';
					break;

				default:

					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;
			}
		}



		$cRet .= "</table>";


		if ($ttd == "1") {

			$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='4.02.01.00'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowsc) {
				$kab     = $rowsc->kab_kota;
				$daerah  = $rowsc->daerah;
			}

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tanggal_ttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tanggal_ttd);
			}

			if ($nip == '00000000 000000 0 000'){
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
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
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
				}
		}
		$data['prev'] = $cRet;
		$judul = 'LRA_PERMEN ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf_lamp('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_perda_lampI_permen_spj33_perda($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd = '', $tanggal_ttd = '', $ttdperda = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$ttd1 = str_replace('n', ' ', $ttdperda);
		$banyak = $this->uri->segment(11);
		$modtahun = $lntahunang % 4;

		if ($modtahun = 0) {
			/*          $nilaibulan=".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";}
					else {
				$nilaibulan=".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";}
		*/
			$nilaibulan = ".JANUARI. FEBRUARI. MARET. APRIL. MEI. JUNI. JULI. AGUSTUS. SEPTEMBER. OKTOBER. NOVEMBER. DESEMBER";
		} else {
			$nilaibulan = ".JANUARI. FEBRUARI. MARET. APRIL. MEI. JUNI. JULI. AGUSTUS. SEPTEMBER. OKTOBER. NOVEMBER. DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($anggaran == '1') {
			$ang = "nilai_ang";
		} else {
			$ang = "nilai_ang_ubah";
		}

		$lntahunangg = $lntahunang + 1;
		$Intahunlalu = $lntahunang - 1;
		$sclient = $this->akuntansi_model->get_sclient();
		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
						<TR>
							<!--<TD  width="60%" valign="top" align="right" > Lampiran I : </TD>
							<TD width="40%"  align="left" >Peraturan Daerah <br>Nomor : <br>Tanggal: </TD>-->
						<TD  width="60%" valign="top" align="right" ></TD>
						<TD width="40%"  align="left" ></TD>
						</TR>
						<tr>
						</TABLE><br/>';

		$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						<TR>
							<TD width="70%" align="center" ><b>&nbsp;</TD>
							<TD width="30%" align="left" >LAMPIRAN I &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN DAERAH KABUPATEN MELAWI <br/> 
							NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangg . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
							</TD>
						</TR>
						<TR>
							<TD width="70%" align="center" ><b>&nbsp;</TD>
							<TD width="30%" align="center" ><b>&nbsp;</TD>
						</TR>
						</TABLE><br/>';

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" .FCPATH. "/image/logoHP.png\"  width=\"50\" height=\"75\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
						<!--<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>PER $arraybulan[$bulan] $lntahunang</b></tr>-->
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA  </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$bulan] $lntahunang</b></tr>                    
						</TABLE>";
		
		if ($kd_skpd  != '-'){

			$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan', 'ms_urusan', 'kd_urusan') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 22) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 22), 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>
					<!--<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $kd_skpd . " - " . $this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>-->
                    </TABLE>";
		}

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"$banyak\">
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
						SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
						SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
						FROM
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM($ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a;
						";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
						SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
						SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
						FROM
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,2) IN ('61','62') $where
						GROUP BY LEFT(kd_ang,2)) a;
						";


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi,tahun_lalu FROM map_lra_pemkot ORDER BY urut
						";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$urut = $row->urut;
			$kode = $row->kd_rek;
			$nama = $row->uraian;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$kode5 = $row->kode5;
			$spasi = $row->spasi;
			$tahun_lalu = $row->tahun_lalu;

			$sql = "SELECT SUM($ang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot where bulan='$bulan' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			$sel = $nilai - $nil_ang;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}

			$sel1 = $sel < 0 ? $sel * -1 : $sel;
			$t = $sel < 0 ? '(' : '';
			$u = $sel < 0 ? ')' : '';

			// if ($sel < 0) {
			// 	$sel1 = $sel * -1;
			// 	$t = '(';
			// 	$u = ')';	
			// }  else {
			// 	$sel1 = $sel;
			// 	$t = '';
			// 	$u = '';
			// }
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
								   <td align="left" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;
				case 2:
					$cRet .= '<tr>
								   <td align="left" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;
				case 3:
					$cRet .= '<tr>
								   <td align="left" valign="top">' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td>
								</tr>';
					break;
				case 4:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 5:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
								   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
								   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 6;
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
								   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
								   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td>
								</tr>';
					break;
				case 7;
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
								   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
								   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
								</tr>';
					break;

				default:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;
			}
		}



		$cRet .= "</table>";


		if ($ttd == "1") {

			$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='4.02.01.00'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowsc) {
				$kab     = $rowsc->kab_kota;
				$daerah  = $rowsc->daerah;
			}

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD'  or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tanggal_ttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tanggal_ttd);
			}

			if ($nip == '00000000 000000 0 000'){
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
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
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
				}
		}

		$data['prev'] = $cRet;
		$judul = 'LRA_PERMEN ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf_lamp('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_perda_lampI_permen_spj33_perda_skpd($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd = '', $tanggal_ttd = '', $ttdperda = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$ttd1 = str_replace('n', ' ', $ttdperda);
		$banyak = $this->uri->segment(11);
		$modtahun = $lntahunang % 4;

		if ($modtahun = 0) {
			/*          $nilaibulan=".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";}
					else {
				$nilaibulan=".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";}
		*/
			$nilaibulan = ".JANUARI. FEBRUARI. MARET. APRIL. MEI. JUNI. JULI. AGUSTUS. SEPTEMBER. OKTOBER. NOVEMBER. DESEMBER";
		} else {
			$nilaibulan = ".JANUARI. FEBRUARI. MARET. APRIL. MEI. JUNI. JULI. AGUSTUS. SEPTEMBER. OKTOBER. NOVEMBER. DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($anggaran == '1') {
			$ang = "nilai_ang";
		} else {
			$ang = "nilai_ang_ubah";
		}

		$lntahunangg = $lntahunang + 1;
		$Intahunlalu = $lntahunang - 1;
		$sclient = $this->akuntansi_model->get_sclient();
		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
						<TR>
							<!--<TD  width="60%" valign="top" align="right" > Lampiran I : </TD>
							<TD width="40%"  align="left" >Peraturan Daerah <br>Nomor : <br>Tanggal: </TD>-->
						<TD  width="60%" valign="top" align="right" ></TD>
						<TD width="40%"  align="left" ></TD>
						</TR>
						<tr>
						</TABLE><br/>';

		$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						<TR>
							<TD width="70%" align="center" ><b>&nbsp;</TD>
							<TD width="30%" align="left" >LAMPIRAN I &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN DAERAH KABUPATEN MELAWI <br/> 
							NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangg . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
							</TD>
						</TR>
						<TR>
							<TD width="70%" align="center" ><b>&nbsp;</TD>
							<TD width="30%" align="center" ><b>&nbsp;</TD>
						</TR>
						</TABLE><br/>';

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" .FCPATH. "/image/logoHP.png\"  width=\"50\" height=\"75\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
						<!--<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>PER $arraybulan[$bulan] $lntahunang</b></tr>-->
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA  </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$bulan] $lntahunang</b></tr>                    
						</TABLE>";
		
		if ($kd_skpd  != '-'){

			$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan', 'ms_urusan', 'kd_urusan') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 22) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 22), 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>
					<!--<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $kd_skpd . " - " . $this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>-->
                    </TABLE>";
		}

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"$banyak\">
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
						SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
						SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
						FROM
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM($ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a;
						";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
						SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
						SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
						FROM
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,2) IN ('61','62') $where
						GROUP BY LEFT(kd_ang,2)) a;
						";


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi,tahun_lalu FROM map_lra_pemkot_ringkas ORDER BY urut
						";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$urut = $row->urut;
			$kode = $row->kd_rek;
			$nama = $row->uraian;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$kode5 = $row->kode5;
			$spasi = $row->spasi;
			$tahun_lalu = $row->tahun_lalu;

			$sql = "SELECT SUM($ang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot where bulan='$bulan' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			$sel = $nilai - $nil_ang;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}

			$sel1 = $sel < 0 ? $sel * -1 : $sel;
			$t = $sel < 0 ? '(' : '';
			$u = $sel < 0 ? ')' : '';

			// if ($sel < 0) {
			// 	$sel1 = $sel * -1;
			// 	$t = '(';
			// 	$u = ')';	
			// }  else {
			// 	$sel1 = $sel;
			// 	$t = '';
			// 	$u = '';
			// }
			$ang_surplus1 = $ang_surplus1;
			
			
			switch ($spasi) {
				case 1:
					if ($nil_ang > '0'){
					$cRet .= '<tr>
								   <td align="left" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
								
					}
					break;
				case 2:
					if ($nil_ang > '0'){
					$cRet .= '<tr>
								   <td align="left" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					}
					break;
				case 3:
					if ($nil_ang > '0'){
					$cRet .= '<tr>
								   <td align="left" valign="top">' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td>
								</tr>';
					}
					break;
				case 4:
					if ($nil_ang > '0'){
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
					}
					break;
				case 5:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
								   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
								   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 6;
				if ($nil_ang > '0'){
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
								   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
								   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td>
								</tr>';
					}
					break;
				case 7;
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
								   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
								   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				default:
				if ($nil_ang > '0'){
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					}
					break;
			}
		}



		$cRet .= "</table>";


		if ($ttd == "1") {

			$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='4.02.01.00'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowsc) {
				$kab     = $rowsc->kab_kota;
				$daerah  = $rowsc->daerah;
			}

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD'  or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tanggal_ttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tanggal_ttd);
			}

			if ($nip == '00000000 000000 0 000'){
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
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
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
				}
		}

		$data['prev'] = $cRet;
		$judul = 'LRA_PERMEN ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf_lamp('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	//================================================ End Lamp Perda I

	//================================================ Lamp Perda I.1
	function perdaI_1()
	{
		$data['page_title'] = 'CETAK PERDA LAMP 1.1';
		$this->template->set('title', 'CETAK LAMP. I.1');
		$this->template->load('template', 'perdase/cetak_perda_lampI_1', $data);
	}

	function lampiran_11(){
        $bulan=$_POST['bulan'];
        $anggaran=$_POST['anggaran'];
        $tgl_ttd=$_POST['tgl_ttd'];
        $ttd=$_POST['ttd'];
        //$jlampiran=$_POST['jlampiran'];
        $pdf=$_REQUEST['c'];  

		
        $dt=$this->db->query("SELECT top 1 daerah,thn_ang tahun from sclient")->row();
        $kota=$dt->daerah;
        $tahun=$dt->tahun;
        $tahun1=$tahun+1;

        $isijudul="LAMPIRAN I.1   <br>       
        PERATURAN DAERAH KABUPATEN MELAWI <br>
        NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ".$tahun1."<br>
        TENTANG PERTANGGUNGJAWABAN PELAKSANAAN ANGGARAN PENDAPATAN DAN BELANJA DAERAH <br>
        KABUPATEN MELAWI TAHUN ANGGARAN ".$tahun."</td>";
       
        $config=$this->db->query("SELECT top 1 * from trkonfig_anggaran where lampiran='perda' ORDER by jenis_anggaran desc ")->row();
        $data['judul']  ="LAMPIRAN I.1";
        $data['isijudul']  =$isijudul;
		$data['initang']   = "nilai";
        $data['nomor']  =$config->nomor;
        $data['tanggal']=$config->tanggal;
        $data['namalampiran']="RINGKASAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI";
        $data['tahunanggaran']="TAHUN ANGGARAN ".$tahun;
        $data['rek4']       = $this->db->query("SELECT left(kd,1) kd1, SUBSTRING(kd,3,2) kd2,* FROM raperda_11($bulan,4,'$anggaran') ORDER BY kd, urut");
        $data['rek5']       = $this->db->query("SELECT left(kd,1) kd1, SUBSTRING(kd,3,2) kd2,* FROM raperda_11($bulan,5,'$anggaran') ORDER BY kd, urut");
        $data['rek6']       = $this->db->query("SELECT left(kd,1) kd1, SUBSTRING(kd,3,2) kd2,* FROM raperda_11($bulan,6,'$anggaran') ORDER BY kd, urut");

        if($ttd==''){
            $ttd='99999999';
        }else
        $datattd=$this->db->query("SELECT * from ms_ttd where nip='$ttd'")->row();
        $data['ttdnama']    = $datattd->nama;
        $data['nip']        = $datattd->nip;
        $data['jabatan']    = $datattd->jabatan;
        $data['tglttd']     = $this->support->tanggal_format_indonesia($tgl_ttd);

        switch ($pdf) {
            case '0':
                $this->load->view('perdase/raperda_11',$data);
                break;
            case '1':
                $this->tukd_model->_mpdf('', $this->load->view('perdase/raperda_11',$data, true), 10, 10, 10, 'L');
                break;
            case '2':
                $this->load->view('perdase/raperda_11',$data);
                break;
        }
        
    }

	function cetak_perda_lampI_1($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '',$ttdperda = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunang_b =   $lntahunang + 1;
		$ttdperda = $this->uri->segment(7);
		$ttd1 = str_replace('n', ' ', $ttdperda);
		$denganttd = $this->uri->segment(8);


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

		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);

		//
		$cRet = '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="left" >LAMPIRAN I.1 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN BUPATI MELAWI <br/> 
						NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunang_b . ' <br/>TENTANG PENJABARAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
						</TD>
					</TR>
					<TR>
						<TD width="60%" align="center" ><b>&nbsp;</TD>
						<TD width="40%" align="center" ><b>&nbsp;</TD>
					</TR>
					</TABLE>';
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style;\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
						<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"75\" />
						</td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"></td></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><strong>$kab <br><br> RINGKASAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI<br><br>SAMPAI DENGAN " . strtoupper($judul) . " TAHUN ANGGARAN " . $lntahunang . "</strong></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
					</TABLE><br/>";

					$cRet .= "<table style=\"font-size:10px;font-family:Bookman Old Style; border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
					<thead>
					<tr>
						<td rowspan = \"3\" width=\"3.5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">KODE</td>
						<td rowspan = \"3\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">URUSAN PEMERINTAH DAERAH</td>
						<td colspan = \"3\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">PENDAPATAN</td>
						<td colspan = \"17\" width=\"71.5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BELANJA</td>
					</tr>
					<tr>
					   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">ANGGARAN</td> 
					   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">REALISASI</td> 
					   <td rowspan = \"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BERTAMBAH/ KURANG</td> 
					   <td colspan =\"5\" colspan=\"5\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">ANGGARAN</td> 
					   <td colspan =\"5\" colspan=\"5\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">REALISASI</td>   
					   <td colspan =\"2\"  align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BERTAMBAH/ KURANG</td> 
					</tr>
				
					<tr>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">OPERASI</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">MODAL</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BTT</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">TRANSFER</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">JUMLAH</td>                    
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">OPERASI</td>                                       
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">MODAL</td>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BTT</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">TRANSFER</td>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">JUMLAH</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">Rp</td>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">%</td> 
						
					</tr>
					<tr>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">1</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">2</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">3</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">4</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">5</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">6</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">7</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">8</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">9</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">10</td>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">11</td>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">12</td>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">13</td>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">14</td>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">15</td>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">16</td>					   
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">17</td>
					</tr>
					</thead>";
	
			$tot_operasi = 0;
			$tot_modal = 0;
			$tot_transfer = 0;
			$tot_ang_brng = 0;
			$tot_ang_pelihara = 0;
			$tot_ang_dinas = 0;
			$tot_ang_honor = 0;
			$total_ang = 0;
			$tot_real_operasi = 0;
			$tot_real_modal = 0;
			$tot_real_transfer = 0;
			$tot_real_brng = 0;
			$tot_real_pelihara = 0;
			$tot_real_dinas = 0;
			$tot_real_honor = 0;
			$total_real = 0;
			$no = 0;
	
			$sql = " select kdgab,kd_skpd,kd_sub_kegiatan,nm_rek,ang_pend,ang_opr,ang_mod,ang_btt,ang_trf,real_pend,real_5101,real_5102,real_5103,real_5104,real_5105,real_5106,real_5201,real_5202,real_5203,real_5204,real_5205,real_5206,real_5301,real_5401,real_5402 
						FROM [perda_lampI.3_sub_3_baru_33]($bulan,$anggaran) ORDER BY kdgab
									";
			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$no = $no + 1;
				$kode = $row->kdgab;
				$nama = $row->nm_rek;
				$ang_pend = $row->ang_pend;
				$ang_opr  = $row->ang_opr;
				$ang_mod  = $row->ang_mod;
				$ang_btt  = $row->ang_btt;
				$ang_trf  = $row->ang_trf;
				$bel_pend = $row->real_pend;
				$real_5101 = $row->real_5101;
				$real_5102 = $row->real_5102;
				$real_5103 = $row->real_5103;
				$real_5104 = $row->real_5104;
				$real_5105 = $row->real_5105;
				$real_5106 = $row->real_5106;
				$real_5201 = $row->real_5201;
				$real_5202 = $row->real_5202;
				$real_5203 = $row->real_5203;
				$real_5204 = $row->real_5204;
				$real_5205 = $row->real_5205;
				$real_5206 = $row->real_5206;
				$real_5301 = $row->real_5301;
				$real_5401 = $row->real_5401;
				$real_5402 = $row->real_5402;
	
				$tot_real_operasi = $real_5101 + $real_5102 + $real_5103 + $real_5104 + $real_5105 + $real_5106;
				$tot_real_modal = $real_5201 + $real_5202 + $real_5203 + $real_5204 + $real_5205 + $real_5206;
				$tot_real_transfer = $real_5401 + $real_5402;
				
				$tot_ang = $ang_opr + $ang_mod + $ang_btt + $ang_trf;
				
				$tot_bel = $real_5101 + $real_5102 + $real_5103 + $real_5104 + $real_5105 + $real_5106 + $real_5201 + $real_5202 + $real_5203 + $real_5204 + $real_5205 + $real_5206 + $real_5301 + $real_5401 + $real_5402;
	
				$tmbh1 = $ang_pend - $bel_pend;
				$tbmh11 = $tmbh1 < 0 ? $tmbh1 * -1 : $tmbh1;
				$ca = $tmbh1 > 0 ? '(' : '';
				$vn = $tmbh1 > 0 ? ')' : '';
				$per_pend  = $ang_pend == 0 || $ang_pend == '' ? 0 : $bel_pend / $ang_pend * 100;
				$per_bel  = $tot_ang == 0 || $tot_ang == '' ? 0 : $tot_bel / $tot_ang * 100;
	
				$cRet .= '<tr>
								   <td align="left" valign="top" style="font-size:8px">' . $kode . '</td> 
								   <td align="left"  valign="top" style="font-size:8px">' . strtoupper($nama) . '</td> 
								   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_pend, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:7px">' . number_format($bel_pend, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:7px">' . $ca . '' . number_format($tbmh11, "2", ",", ".") . '' . $vn . '</td> 
								   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_opr, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_mod, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_btt, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_trf, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_ang, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_real_operasi, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_real_modal, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5301, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_real_transfer, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_bel, "2", ",", ".") . '</td>                             
								   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_ang - $tot_bel, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:7px">' . number_format($per_bel, "2", ",", ".") . '</td> 
								</tr>';
			}
			$sql = " select 'JUMLAH' nm_rek,sum(ang_pend) ang_pend,sum(ang_opr)ang_opr,sum(ang_mod)ang_mod,sum(ang_btt)ang_btt,sum(ang_trf)ang_trf,sum(real_pend)real_pend,sum(real_5101)real_5101,sum(real_5102)real_5102,sum(real_5103)real_5103,sum(real_5104)real_5104,sum(real_5105)real_5105,sum(real_5106)real_5106,sum(real_5201)real_5201,sum(real_5202)real_5202,sum(real_5203)real_5203,sum(real_5204)real_5204,sum(real_5205)real_5205,sum(real_5206)real_5206,sum(real_5301)real_5301,sum(real_5401)real_5401,sum(real_5402)real_5402 FROM 
							 [perda_lampI.3_sub_3_baru_33]($bulan,$anggaran) where len(kd_sub_kegiatan)=1";
			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$no = $no + 1;
				$nama = $row->nm_rek;
				$ang_pend = $row->ang_pend;
				$ang_opr  = $row->ang_opr;
				$ang_mod  = $row->ang_mod;
				$ang_btt  = $row->ang_btt;
				$ang_trf  = $row->ang_trf;
				$bel_pend = $row->real_pend;
				$real_5101 = $row->real_5101;
				$real_5102 = $row->real_5102;
				$real_5103 = $row->real_5103;
				$real_5104 = $row->real_5104;
				$real_5105 = $row->real_5105;
				$real_5106 = $row->real_5106;
				$real_5201 = $row->real_5201;
				$real_5202 = $row->real_5202;
				$real_5203 = $row->real_5203;
				$real_5204 = $row->real_5204;
				$real_5205 = $row->real_5205;
				$real_5206 = $row->real_5206;
				$real_5301 = $row->real_5301;
				$real_5401 = $row->real_5401;
				$real_5402 = $row->real_5402;
				
				$tot_real_operasi = $real_5101 + $real_5102 + $real_5103 + $real_5104 + $real_5105 + $real_5106;
				$tot_real_modal = $real_5201 + $real_5202 + $real_5203 + $real_5204 + $real_5205 + $real_5206;
				$tot_real_transfer = $real_5401 + $real_5402;
				
				$tot_ang = $ang_opr + $ang_mod + $ang_btt + $ang_trf;
	
				$tot_bel = $real_5101 + $real_5102 + $real_5103 + $real_5104 + $real_5105 + $real_5106 + $real_5201 + $real_5202 + $real_5203 + $real_5204 + $real_5205 + $real_5206 + $real_5301 + $real_5401 + $real_5402;
	
				$tmbh1 = $ang_pend - $bel_pend;
				$tbmh11 = $tmbh1 < 0 ? $tmbh1 * -1 : $tmbh1;
				$ca = $tmbh1 > 0 ? '(' : '';
				$vn = $tmbh1 > 0 ? ')' : '';
				$per_pend  = $ang_pend == 0 || $ang_pend == '' ? 0 : $bel_pend / $ang_pend * 100;
				$per_bel  = $tot_ang == 0 || $tot_ang == '' ? 0 : $tot_bel / $tot_ang * 100;
	
				$cRet .= '<tr>
								   <td align="left" valign="top" style="font-size:8px"></td> 
								   <td align="center"  valign="top" style="font-size:8px">' . strtoupper($nama) . '</td> 
								   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_pend, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:6px">' . number_format($bel_pend, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:6px">' . $ca . '' . number_format($tbmh11, "2", ",", ".") . '' . $vn . '</td> 
								   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_opr, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_mod, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_btt, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_trf, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_ang, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_real_operasi, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_real_modal, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5301, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_real_transfer, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_bel, "2", ",", ".") . '</td>                              
								   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_ang - $tot_bel, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:6px">' . number_format($per_bel, "2", ",", ".") . '</td> 
								</tr>';
			}

		$cRet .= "</table>";

		
		if ($denganttd == '1'){
			$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='4.02.01.00'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowsc) {
				$kab     = $rowsc->kab_kota;
				$daerah  = $rowsc->daerah;
			}

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tglttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
			}

			if ($nip == '00000000 000000 0 000'){
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
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
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
				}
			}

		$data['prev'] = $cRet;
		$judul = 'Perbup_LampI';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_perda_lampI_1_233_perda($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '',$ttdperda = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunang_b =   $lntahunang + 1;
		$ttdperda = $this->uri->segment(7);
		$ttd1 = str_replace('n', ' ', $ttdperda);
		$denganttd = $this->uri->segment(8);

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

		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);

		//
		$cRet = '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="left" >LAMPIRAN I.1 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN DAERAH KABUPATEN MELAWI <br/> 
						NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunang_b . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
						</TD>
					</TR>
					<TR>
						<TD width="60%" align="center" ><b>&nbsp;</TD>
						<TD width="40%" align="center" ><b>&nbsp;</TD>
					</TR>
					</TABLE>';
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style;\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" .base_url(). "/image/logoHP.png\"  width=\"50\" height=\"75\" />
							</td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"></td></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><strong>$kab <br><br> RINGKASAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI<br><br>SAMPAI DENGAN " . strtoupper($judul) . " TAHUN ANGGARAN " . $lntahunang . "</strong></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
					</TABLE><br/>";

		$cRet .= "<table style=\"font-size:10px;font-family:Bookman Old Style; border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
				<thead>
				<tr>
					<td rowspan = \"3\" width=\"3.5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">KODE</td>
					<td rowspan = \"3\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">URUSAN PEMERINTAH DAERAH</td>
					<td colspan = \"3\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">PENDAPATAN</td>
					<td colspan = \"17\" width=\"71.5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BELANJA</td>
				</tr>
				<tr>
				   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">ANGGARAN</td> 
				   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">REALISASI</td> 
				   <td rowspan = \"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BERTAMBAH/ KURANG</td> 
				   <td colspan =\"5\" colspan=\"5\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">ANGGARAN</td> 
				   <td colspan =\"5\" colspan=\"5\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">REALISASI</td>   
				   <td colspan =\"2\"  align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BERTAMBAH/ KURANG</td> 
				</tr>
			
				<tr>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">OPERASI</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">MODAL</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BTT</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">TRANSFER</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">JUMLAH</td>                    
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">OPERASI</td>                                       
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">MODAL</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BTT</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">TRANSFER</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">JUMLAH</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">Rp</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">%</td> 
					
				</tr>
				<tr>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">1</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">2</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">3</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">4</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">5</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">6</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">7</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">8</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">9</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">10</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">11</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">12</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">13</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">14</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">15</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">16</td>					   
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">17</td>
				</tr>
				</thead>";

		$tot_operasi = 0;
		$tot_modal = 0;
		$tot_transfer = 0;
		$tot_ang_brng = 0;
		$tot_ang_pelihara = 0;
		$tot_ang_dinas = 0;
		$tot_ang_honor = 0;
		$total_ang = 0;
		$tot_real_operasi = 0;
		$tot_real_modal = 0;
		$tot_real_transfer = 0;
		$tot_real_brng = 0;
		$tot_real_pelihara = 0;
		$tot_real_dinas = 0;
		$tot_real_honor = 0;
		$total_real = 0;
		$no = 0;

		$sql = " select kdgab,kd_skpd,kd_sub_kegiatan,nm_rek,ang_pend,ang_opr,ang_mod,ang_btt,ang_trf,real_pend,real_5101,real_5102,real_5103,real_5104,real_5105,real_5106,real_5201,real_5202,real_5203,real_5204,real_5205,real_5206,real_5301,real_5401,real_5402 
					FROM [perda_lampI.3_sub_3_baru_33]($bulan,$anggaran) ORDER BY kdgab
								";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$kode = $row->kdgab;
			$nama = $row->nm_rek;
			$ang_pend = $row->ang_pend;
			$ang_opr  = $row->ang_opr;
			$ang_mod  = $row->ang_mod;
			$ang_btt  = $row->ang_btt;
			$ang_trf  = $row->ang_trf;
			$bel_pend = $row->real_pend;
			$real_5101 = $row->real_5101;
			$real_5102 = $row->real_5102;
			$real_5103 = $row->real_5103;
			$real_5104 = $row->real_5104;
			$real_5105 = $row->real_5105;
			$real_5106 = $row->real_5106;
			$real_5201 = $row->real_5201;
			$real_5202 = $row->real_5202;
			$real_5203 = $row->real_5203;
			$real_5204 = $row->real_5204;
			$real_5205 = $row->real_5205;
			$real_5206 = $row->real_5206;
			$real_5301 = $row->real_5301;
			$real_5401 = $row->real_5401;
			$real_5402 = $row->real_5402;

			$tot_real_operasi = $real_5101 + $real_5102 + $real_5103 + $real_5104 + $real_5105 + $real_5106;
			$tot_real_modal = $real_5201 + $real_5202 + $real_5203 + $real_5204 + $real_5205 + $real_5206;
			$tot_real_transfer = $real_5401 + $real_5402;
			
			$tot_ang = $ang_opr + $ang_mod + $ang_btt + $ang_trf;
			
			$tot_bel = $real_5101 + $real_5102 + $real_5103 + $real_5104 + $real_5105 + $real_5106 + $real_5201 + $real_5202 + $real_5203 + $real_5204 + $real_5205 + $real_5206 + $real_5301 + $real_5401 + $real_5402;

			$tmbh1 = $ang_pend - $bel_pend;
			$tbmh11 = $tmbh1 < 0 ? $tmbh1 * -1 : $tmbh1;
			$ca = $tmbh1 > 0 ? '(' : '';
			$vn = $tmbh1 > 0 ? ')' : '';
			$per_pend  = $ang_pend == 0 || $ang_pend == '' ? 0 : $bel_pend / $ang_pend * 100;
			$per_bel  = $tot_ang == 0 || $tot_ang == '' ? 0 : $tot_bel / $tot_ang * 100;

			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:8px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:8px">' . strtoupper($nama) . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($bel_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . $ca . '' . number_format($tbmh11, "2", ",", ".") . '' . $vn . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_opr, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_mod, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_btt, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_trf, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_ang, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_real_operasi, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_real_modal, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5301, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_real_transfer, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_bel, "2", ",", ".") . '</td>                             
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_ang - $tot_bel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($per_bel, "2", ",", ".") . '</td> 
							</tr>';
		}
		$sql = " select 'JUMLAH' nm_rek,sum(ang_pend) ang_pend,sum(ang_opr)ang_opr,sum(ang_mod)ang_mod,sum(ang_btt)ang_btt,sum(ang_trf)ang_trf,sum(real_pend)real_pend,sum(real_5101)real_5101,sum(real_5102)real_5102,sum(real_5103)real_5103,sum(real_5104)real_5104,sum(real_5105)real_5105,sum(real_5106)real_5106,sum(real_5201)real_5201,sum(real_5202)real_5202,sum(real_5203)real_5203,sum(real_5204)real_5204,sum(real_5205)real_5205,sum(real_5206)real_5206,sum(real_5301)real_5301,sum(real_5401)real_5401,sum(real_5402)real_5402 FROM 
						 [perda_lampI.3_sub_3_baru_33]($bulan,$anggaran) where len(kd_sub_kegiatan)=1";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$nama = $row->nm_rek;
			$ang_pend = $row->ang_pend;
			$ang_opr  = $row->ang_opr;
			$ang_mod  = $row->ang_mod;
			$ang_btt  = $row->ang_btt;
			$ang_trf  = $row->ang_trf;
			$bel_pend = $row->real_pend;
			$real_5101 = $row->real_5101;
			$real_5102 = $row->real_5102;
			$real_5103 = $row->real_5103;
			$real_5104 = $row->real_5104;
			$real_5105 = $row->real_5105;
			$real_5106 = $row->real_5106;
			$real_5201 = $row->real_5201;
			$real_5202 = $row->real_5202;
			$real_5203 = $row->real_5203;
			$real_5204 = $row->real_5204;
			$real_5205 = $row->real_5205;
			$real_5206 = $row->real_5206;
			$real_5301 = $row->real_5301;
			$real_5401 = $row->real_5401;
			$real_5402 = $row->real_5402;
			
			$tot_real_operasi = $real_5101 + $real_5102 + $real_5103 + $real_5104 + $real_5105 + $real_5106;
			$tot_real_modal = $real_5201 + $real_5202 + $real_5203 + $real_5204 + $real_5205 + $real_5206;
			$tot_real_transfer = $real_5401 + $real_5402;
			
			$tot_ang = $ang_opr + $ang_mod + $ang_btt + $ang_trf;

			$tot_bel = $real_5101 + $real_5102 + $real_5103 + $real_5104 + $real_5105 + $real_5106 + $real_5201 + $real_5202 + $real_5203 + $real_5204 + $real_5205 + $real_5206 + $real_5301 + $real_5401 + $real_5402;

			$tmbh1 = $ang_pend - $bel_pend;
			$tbmh11 = $tmbh1 < 0 ? $tmbh1 * -1 : $tmbh1;
			$ca = $tmbh1 > 0 ? '(' : '';
			$vn = $tmbh1 > 0 ? ')' : '';
			$per_pend  = $ang_pend == 0 || $ang_pend == '' ? 0 : $bel_pend / $ang_pend * 100;
			$per_bel  = $tot_ang == 0 || $tot_ang == '' ? 0 : $tot_bel / $tot_ang * 100;

			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:8px"></td> 
							   <td align="center"  valign="top" style="font-size:8px">' . strtoupper($nama) . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($bel_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . $ca . '' . number_format($tbmh11, "2", ",", ".") . '' . $vn . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_opr, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_mod, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_btt, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_trf, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_ang, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_real_operasi, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_real_modal, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5301, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_real_transfer, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_bel, "2", ",", ".") . '</td>                              
							   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_ang - $tot_bel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($per_bel, "2", ",", ".") . '</td> 
							</tr>';
		}

		$cRet .= "</table>";

		if ($denganttd == '1'){
			$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='4.02.01.00'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowsc) {
				$kab     = $rowsc->kab_kota;
				$daerah  = $rowsc->daerah;
			}

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tglttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
			}

			if ($nip == '00000000 000000 0 000'){
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
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
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
				}
			}

		$data['prev'] = $cRet;
		$judul = 'Perda_LampI';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_perda_lampI_1_ranperda($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '',$ttdperda = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunang_b =   $lntahunang + 1;
		$ttdperda = $this->uri->segment(7);
		$ttd1 = str_replace('n', ' ', $ttdperda);
		$denganttd = $this->uri->segment(8);

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

		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);

		//
		$cRet = '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="left" >LAMPIRAN I.1 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN DAERAH KABUPATEN MELAWI <br/> 
						NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunang_b . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
						</TD>
					</TR>
					<TR>
						<TD width="60%" align="center" ><b>&nbsp;</TD>
						<TD width="40%" align="center" ><b>&nbsp;</TD>
					</TR>
					</TABLE>';
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style;\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" .base_url(). "/image/logoHP.png\"  width=\"50\" height=\"75\" />
							</td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"></td></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><strong>$kab <br><br> RINGKASAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI<br><br>SAMPAI DENGAN " . strtoupper($judul) . " TAHUN ANGGARAN " . $lntahunang . "</strong></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
					</TABLE><br/>";

					$cRet .= "<table style=\"font-size:10px;font-family:Bookman Old Style; border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
					<thead>
					<tr>
						<td rowspan = \"4\" width=\"3.5%\" colspan=\"3\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">KODE</td>
						<td rowspan = \"4\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">URUSAN PEMERINTAH DAERAH</td>
						<td colspan = \"2\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">JUMLAH ( Rp )</td>
						<td colspan = \"3\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BERTAMBAH/ KURANG</td>
					</tr>
					<tr>
					   <td rowspan = \"3\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">ANGGARAN</td> 
					   <td rowspan = \"3\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">REALISASI</td>  
					</tr>
				
					<tr>
				
						
					</tr>
					<tr>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">( Rp )</td>					   
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">( % )</td>
					</tr>
					</thead>";


		echo $sql = " select *, kd_skpd+kd urutan from (
			select * from (
			select '1' urut,'' kd_skpd, (select nm_urusan from ms_urusan WHERE kd_urusan=left(kd_sub_kegiatan,1)) bidurusan, left(kd_sub_kegiatan,1) kd, 
			sum(a.nilai_ang) nilai,
			sum(a.real_spj) realisasi,
			sum(a.nilai_ang_ubah) nilai_ubah
			from data_realisasi_pemkot a WHERE bulan=$bulan and left(a.kd_rek6,1) in (4,5,6)
			GROUP BY left(kd_sub_kegiatan,1) 
			
			UNION all
			select '2' urut,'' kd_skpd, (select nm_bidang_urusan from ms_bidang_urusan WHERE kd_bidang_urusan=left(kd_sub_kegiatan,4)) bidurusan, left(kd_sub_kegiatan,4) kd, 
			sum(a.nilai_ang) tot,
			sum(a.real_spj) realisasi,
			sum(a.nilai_ang_ubah) tot_ubah
			from data_realisasi_pemkot a WHERE bulan=$bulan and left(a.kd_rek6,1) in (4,5,6)
			GROUP BY left(kd_sub_kegiatan,4) 
			
			UNION all
			
			select  '3' urut, left(kd_skpd,22) kd_skpd, 
			(select nm_skpd from ms_skpd where kd_skpd=left(a.kd_skpd,22)) kd_skpd, 
			CASE WHEN left(kd_skpd,4)='7.01' then left(kd_sub_kegiatan,4)+SUBSTRING(kd_skpd,16,2) else left(kd_sub_kegiatan,4) end as kd,
			sum(a.nilai_ang) tot,
			sum(a.real_spj) realisasi,
			sum(a.nilai_ang_ubah) tot_ubah
			from data_realisasi_pemkot a  WHERE bulan=$bulan and left(a.kd_rek6,1) in (4,5,6)
			GROUP BY left(kd_sub_kegiatan,4),left(kd_skpd,22),left(kd_skpd,4) ,SUBSTRING(kd_skpd,16,2)
			
			UNION all
			
			select  '4' urut, left(kd_skpd,22) kd_skpd, (select nm_rek2 from ms_rek2 WHERE kd_rek2=left(kd_rek6,2)) kd_skpd, 
			CASE WHEN left(kd_skpd,4)='7.01' 
			then left(kd_sub_kegiatan,4)+SUBSTRING(kd_skpd,16,2)+left(kd_rek6,2) 
			else left(kd_sub_kegiatan,4)+left(kd_rek6,2) end as kd,
			sum(a.nilai_ang) tot,
			sum(a.real_spj) realisasi,
			sum(a.nilai_ang_ubah) tot_ubah
			from data_realisasi_pemkot a  WHERE bulan=$bulan and left(a.kd_rek6,1) in (4,5,6)
			GROUP BY left(kd_sub_kegiatan,4),left(kd_skpd,22),left(kd_rek6,2),left(kd_skpd,4),SUBSTRING(kd_skpd,16,2)
			
			
			UNION all
			
			select  '5' urut, left(kd_skpd,22) kd_skpd, (SELECT nm_rek3 from ms_rek3 WHERE kd_rek3=left(kd_rek6,4)) kd_skpd, 
			CASE WHEN left(kd_skpd,4)='7.01' 
			then left(kd_sub_kegiatan,4)+SUBSTRING(kd_skpd,16,2)+left(kd_rek6,4) 
			else left(kd_sub_kegiatan,4)+left(kd_rek6,4) end as kd,
			sum(a.nilai_ang) tot,
			sum(a.real_spj) realisasi,
			sum(a.nilai_ang_ubah) tot_ubah
			from data_realisasi_pemkot a  WHERE bulan=$bulan and left(a.kd_rek6,1) in (4,5,6)
			GROUP BY left(kd_sub_kegiatan,4),left(kd_skpd,22),left(kd_rek6,4),left(kd_skpd,4),SUBSTRING(kd_skpd,16,2)
			
			) cc
			) oke
			order by kd,urut
								";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$urut 		= $row->urut;
			$kd_skpd 	= $row->kd_skpd;
			$bid_urusan	= $row->bid_urusan;
			$kd			= $row->kd;
			$nilai		= $row->nilai;
			$real		= $row->realisasi;
			$nilai_ubah	= $row->nilai_ubah;
			$urutan 	= $row->urutan;
			
			
			$leng = strlen($urut);
			switch ($leng) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:8px">'.$urut.'</td> 
							   <td align="left" valign="top" style="font-size:8px"></td> 
							   <td align="left" valign="top" style="font-size:8px"></td> 
							   <td align="left"  valign="top" style="font-size:8px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:8px"></td> 
							   <td align="left" valign="top" style="font-size:8px"></td> 
							   <td align="left" valign="top" style="font-size:8px"></td> 
							   <td align="left"  valign="top" style="font-size:8px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:8px"></td> 
							   <td align="left" valign="top" style="font-size:8px"></td> 
							   <td align="left" valign="top" style="font-size:8px"></td> 
							   <td align="left"  valign="top" style="font-size:8px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							</tr>';
					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:8px"></td> 
							   <td align="left" valign="top" style="font-size:8px"></td> 
							   <td align="left" valign="top" style="font-size:8px"></td> 
							   <td align="left"  valign="top" style="font-size:8px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							   <td align="right" valign="top" style="font-size:7px"></td> 
							</tr>';
					break;
				
					default:

					$cRet .= '<tr>
								   <td align="left" valign="top" ><b></b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" ><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;


			}
			
		}
		// $sql = " select 'JUMLAH' nm_rek,sum(ang_pend) ang_pend,sum(ang_opr)ang_opr,sum(ang_mod)ang_mod,sum(ang_btt)ang_btt,sum(ang_trf)ang_trf,sum(real_pend)real_pend,sum(real_5101)real_5101,sum(real_5102)real_5102,sum(real_5103)real_5103,sum(real_5104)real_5104,sum(real_5105)real_5105,sum(real_5106)real_5106,sum(real_5201)real_5201,sum(real_5202)real_5202,sum(real_5203)real_5203,sum(real_5204)real_5204,sum(real_5205)real_5205,sum(real_5206)real_5206,sum(real_5301)real_5301,sum(real_5401)real_5401,sum(real_5402)real_5402 FROM 
		// 				 [perda_lampI.3_sub_3_baru_33_copy2]($bulan,$anggaran) where len(kd_sub_kegiatan)=1";
		// $hasil = $this->db->query($sql);
		// foreach ($hasil->result() as $row) {
		// 	$no = $no + 1;
		// 	$nama = $row->nm_rek;
		// 	$ang_pend = $row->ang_pend;
		// 	$ang_opr  = $row->ang_opr;
		// 	$ang_mod  = $row->ang_mod;
		// 	$ang_btt  = $row->ang_btt;
		// 	$ang_trf  = $row->ang_trf;
		// 	$bel_pend = $row->real_pend;
		// 	$real_5101 = $row->real_5101;
		// 	$real_5102 = $row->real_5102;
		// 	$real_5103 = $row->real_5103;
		// 	$real_5104 = $row->real_5104;
		// 	$real_5105 = $row->real_5105;
		// 	$real_5106 = $row->real_5106;
		// 	$real_5201 = $row->real_5201;
		// 	$real_5202 = $row->real_5202;
		// 	$real_5203 = $row->real_5203;
		// 	$real_5204 = $row->real_5204;
		// 	$real_5205 = $row->real_5205;
		// 	$real_5206 = $row->real_5206;
		// 	$real_5301 = $row->real_5301;
		// 	$real_5401 = $row->real_5401;
		// 	$real_5402 = $row->real_5402;
			
		// 	$tot_real_operasi = $real_5101 + $real_5102 + $real_5103 + $real_5104 + $real_5105 + $real_5106;
		// 	$tot_real_modal = $real_5201 + $real_5202 + $real_5203 + $real_5204 + $real_5205 + $real_5206;
		// 	$tot_real_transfer = $real_5401 + $real_5402;
			
		// 	$tot_ang = $ang_opr + $ang_mod + $ang_btt + $ang_trf;

		// 	$tot_bel = $real_5101 + $real_5102 + $real_5103 + $real_5104 + $real_5105 + $real_5106 + $real_5201 + $real_5202 + $real_5203 + $real_5204 + $real_5205 + $real_5206 + $real_5301 + $real_5401 + $real_5402;

		// 	$tmbh1 = $ang_pend - $bel_pend;
		// 	$tbmh11 = $tmbh1 < 0 ? $tmbh1 * -1 : $tmbh1;
		// 	$ca = $tmbh1 > 0 ? '(' : '';
		// 	$vn = $tmbh1 > 0 ? ')' : '';
		// 	$per_pend  = $ang_pend == 0 || $ang_pend == '' ? 0 : $bel_pend / $ang_pend * 100;
		// 	$per_bel  = $tot_ang == 0 || $tot_ang == '' ? 0 : $tot_bel / $tot_ang * 100;

		// 	$cRet .= '<tr>
		// 					   <td align="left" valign="top" colspan="3" style="font-size:8px"></td> 
		// 					   <td align="center"  valign="top" style="font-size:8px"></td> 
		// 					   <td align="right" valign="top" style="font-size:6px"></td> 
		// 					   <td align="right" valign="top" style="font-size:6px"></td> 
		// 					   <td align="right" valign="top" style="font-size:6px"></td> 
		// 					   <td align="right" valign="top" style="font-size:7px"></td> 
		// 					</tr>';
		// }

		$cRet .= "</table>";

		if ($denganttd == '1'){
			$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='4.02.01.00'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowsc) {
				$kab     = $rowsc->kab_kota;
				$daerah  = $rowsc->daerah;
			}

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tglttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
			}

			if ($nip == '00000000 000000 0 000'){
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
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
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
				}
			}

		$data['prev'] = $cRet;
		$judul = 'Perda_LampI';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_perda_lampI_1_2_rekap($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '',$ttdperda = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunang_b =   $lntahunang - 1;
		$lntahunang_c =   $lntahunang + 1;
		$ttdperda = $this->uri->segment(7);
		$ttd1 = str_replace('n', ' ', $ttdperda);
		$denganttd = $this->uri->segment(8);

		if ($anggaran == '1') {
			$ang = 'nilai';
		}
		if ($anggaran == '2') {
			$ang = 'nilai_sempurna';
		} else {
			$ang = 'nilai_ubah';
		}

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


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);

		/*if($ttd=='-'){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA' and kd_skpd='1.20.00.00'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}*/

		$cRet = '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="left" >LAMPIRAN I.1 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN DAERAH KABUPATEN MELAWI <br/> 
                        NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunang_c . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
                        </TD>
					</TR>
                    <TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="center" ><b>&nbsp;</TD>
					</TR>
					</TABLE><br/>';
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style;\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"75\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"> </td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><strong>$kab<br><br>RINGKASAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI <br><br> SAMPAI DENGAN " . $judul . " TAHUN ANGGARAN " . $lntahunang . "</strong></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
					</TABLE><br/>";

		$cRet .= "<table style=\"font-size:10px;font-family:Bookman Old Style; border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"3\" width=\"4%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">KODE</td>
                    <td rowspan = \"3\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">URUSAN PEMERINTAH DAERAH</td>					
                    <td colspan = \"2\" width=\"26%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BELANJA TIDAK LANGSUNG</td>
                    <td colspan = \"2\" width=\"26%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BELANJA LANGSUNG</td>
                    <td colspan = \"4\" width=\"40%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">TOTAL BELANJA</td>
                </tr>
				<tr>                    
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">ANGGARAN</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">REALISASI</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">ANGGARAN</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">REALISASI</td>                     
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">ANGGARAN</td>                   
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">REALISASI</td> 
                   <td colspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BERTAMBAH/ KURANG</td> 
				</tr>
				<tr>
                   
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td>                    
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td>                                       
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">%</td> 
				    
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">6</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">10</td> 
				</tr>
				</thead>";

		$tot_ang_brng = 0;
		$tot_ang_pelihara = 0;
		$tot_ang_dinas = 0;
		$tot_ang_honor = 0;
		$total_ang = 0;
		$tot_real_brng = 0;
		$tot_real_pelihara = 0;
		$tot_real_dinas = 0;
		$tot_real_honor = 0;
		$total_real = 0;
		$no = 0;

		$sql = " select kd_kegiatan1 kode1 ,nm_rek,sum(ang_pend) ang_pend,sum(ang_btl) ang_btl,sum(ang_bl) ang_bl,sum(real_pend) real_pend,sum(real_btl) real_btl,sum(real_bl) real_bl 
					FROM [perda_lampI.3_sub_3_rekap]($bulan,$anggaran) group by kd_kegiatan1,nm_rek ORDER BY kd_kegiatan1
								";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$kode = $row->kode1;
			$nama = $row->nm_rek;
			$ang_pend = $row->ang_pend;
			$ang_peg = $row->ang_btl;
			$ang_modal = $row->ang_bl;
			$bel_pend = $row->real_pend;
			$bel_peg = $row->real_btl;
			$bel_modal = $row->real_bl;

			$tot_ang = $ang_peg + $ang_modal;
			$tot_bel = $bel_peg + $bel_modal;
			$per_pend  = $ang_pend == 0 || $ang_peg == '' ? 0 : $bel_pend / $ang_pend * 100;
			$per_bel  = $tot_ang == 0 || $tot_ang == '' ? 0 : $tot_bel / $tot_ang * 100;

			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:8px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:8px">' . strtoupper($nama) . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($bel_peg, "2", ",", ".") . '</td> 
					           <td align="right" valign="top" style="font-size:7px">' . number_format($ang_modal, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($bel_modal, "2", ",", ".") . '</td>                                
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
                               <td align="right" valign="top" style="font-size:7px">' . number_format($tot_bel, "2", ",", ".") . '</td>                                
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_ang - $tot_bel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($per_bel, "2", ",", ".") . '</td> 
							</tr>';
		}
		$sql = " select '' kode, '' kode1 ,'JUMLAH' nm_rek,ang_pend,ang_btl,ang_bl,real_pend,real_btl,real_bl 
					FROM [perda_lampI.3_sub_3_total_rekap]($bulan,$anggaran) 
								";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$kode = $row->kode1;
			$nama = $row->nm_rek;
			$ang_pend = $row->ang_pend;
			$ang_peg = $row->ang_btl;
			$ang_modal = $row->ang_bl;
			$bel_pend = $row->real_pend;
			$bel_peg = $row->real_btl;
			$bel_modal = $row->real_bl;

			$tot_ang = $ang_peg + $ang_modal;
			$tot_bel = $bel_peg + $bel_modal;
			$per_pend  = $ang_pend == 0 || $ang_peg == '' ? 0 : $bel_pend / $ang_pend * 100;
			$per_bel  = $tot_ang == 0 || $tot_ang == '' ? 0 : $tot_bel / $tot_ang * 100;

			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:8px">' . $kode . '</td> 
							   <td align="center"  valign="top" style="font-size:8px">' . strtoupper($nama) . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($bel_peg, "2", ",", ".") . '</td> 
					           <td align="right" valign="top" style="font-size:7px">' . number_format($ang_modal, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($bel_modal, "2", ",", ".") . '</td>                                
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
                               <td align="right" valign="top" style="font-size:7px">' . number_format($tot_bel, "2", ",", ".") . '</td>                                
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_ang - $tot_bel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($per_bel, "2", ",", ".") . '</td> 
							</tr>';
		}

		$cRet .= "</table>";

		if ($denganttd == '1'){
			$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='4.02.01.00'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowsc) {
				$kab     = $rowsc->kab_kota;
				$daerah  = $rowsc->daerah;
			}

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tglttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
			}

			if ($nip == '00000000 000000 0 000'){
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
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
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
				}
			}

		$data['prev'] = $cRet;
		$judul = 'Perda_LampI';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	//================================================ End Lamp Perda I.1

	//================================================ Lamp Perda I.2
	function perdaI_2()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. I.2';
		$this->template->set('title', 'CETAK LAMP. I.2');
		$this->template->load('template', 'perdase/cetak_perda_lampI_2', $data);
	}
    function cetak_perda_lampI_2_akunSE($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd = '', $tanggal_ttd = '', $ttdperda = '', $label = '',$anggaran = '')
	{

		$lntahunang = $this->session->userdata('pcThang');
        $lntahunangn = $lntahunang + 1;
		$ttd1 = str_replace('n', ' ', $ttdperda);

		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
				break;
			case  4:
				$judul = "APRIL";
				break;
			case  5:
				$judul = "MEI";
				break;
			case  6:
				$judul = "SEMESTER I";
				break;
			case  7:
				$judul = "JULI";
				break;
			case  8:
				$judul = "AGUSTUS";
				break;
			case  9:
				$judul = "TRIWULAN III";
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
		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($anggaran == 1) {
			$initang = "nilai_ang";
		} else {
			$initang = "nilai_ang_ubah";
		}

		if ($label == '1') {
			$label = 'UNAUDITED';
		} else if ($label == '2') {
			$label = 'AUDITED';
		} else {
			$label = '&nbsp;';
		}

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
        
        $cRet = '<TABLE style="border-collapse:collapse;font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                    <TR>
                        <TD width="70%" align="center" ><b>&nbsp;</TD>
                        <TD width="30%" align="left" >LAMPIRAN I.2 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN DAERAH KABUPATEN MELAWI <br/> 
                            NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangn . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
                        </TD>
                    </TR>
                    <TR>
                        <TD width="70%" align="center" ><b>&nbsp;</TD>
                        <TD width="30%" align="center" ><b>&nbsp;</TD>
                    </TR>
				</TABLE><br/>';

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        &nbsp;
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong></strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>$kab <br>RINGKASAN APBD YANG DIKLASIFIKASI MENURUT KELOMPOK DAN JENIS PENDAPATAN, BELANJA, DAN PEMBIAYAAN <br>TAHUN ANGGARAN " . $lntahunang . "</b> <br> <b>$label</b>
                    </tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
					<thead>
					<tr>
						<td  width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Uraian</b></td>
						<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Anggaran " . $lntahunang . "</b></td>
						<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Realisasi " . $lntahunang . "</b></td>
						<td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
						
					</tr>
					</thead> ";

		$sql = "SELECT 
						SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
						SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
						FROM
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM(nilai_ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' AND jns_ang='$anggaran' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a;
						";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
						SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
						SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
						FROM
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM(nilai_ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' AND jns_ang='$anggaran' and LEFT(kd_ang,2) IN ('61','62') $where
						GROUP BY LEFT(kd_ang,2)) a;
						";


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi FROM map_lra_perda ORDER BY urut
						";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$urut = $row->urut;
			$kode = $row->kd_rek;
			$nama = $row->uraian;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$kode5 = $row->kode5;
			$spasi = $row->spasi;

			$sql = "SELECT SUM(nilai_ang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot where bulan='$bulan' AND jns_ang='$anggaran' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}
			if ($sel < 0) {
				$sel1 = $sel * -1;
				$t = '(';
				$u = ')';	
			}  else {
				$sel1 = $sel;
				$t = '';
				$u = '';
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr style="border-bottom:solid 1px white">
								   <td align="left"  valign="top"><b><u>' . $nama . '</u></b></td> 
								   <td align="right" valign="top"><b><b></td> 
								   <td align="right" valign="top"><b><b></td> 
								   <td align="right" valign="top"><b><b></td> 
								</tr>';
					break;
				case 2:
					$cRet .= '<tr style="border-bottom:solid 1px white">
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"></td> 
								   <td align="right" valign="top"></td> 
								   <td align="right" valign="top"></td> 
								</tr>';
					break;
				case 3:  
					$cRet .= '<tr style="border-bottom:solid 1px white">
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								</tr>';
					break;
				case 4:
					$cRet .= '<tr >
								   <td align="left"  style="border-top:solid 1px black" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" style="border-top:solid 1px black" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" style="border-top:solid 1px black" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" style="border-top:solid 1px black" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 5:
					$cRet .= '<tr>
								   <td align="left"  style="border-top:solid 1px black" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" style="border-top:solid 1px black" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
								   <td align="right" style="border-top:solid 1px black" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
								   <td align="right" style="border-top:solid 1px black" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 6;
					$cRet .= '<tr>
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
								   <td align="right" valign="top"><b>' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 7;
					$cRet .= '<tr>
								   <td align="left" style="border-top:solid 1px black" valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></b></td> 
								   <td align="right" style="border-top:solid 1px black" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
								   <td align="right" style="border-top:solid 1px black" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
								   <td align="right" style="border-top:solid 1px black" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
								</tr>';
					break;

				default:

					$cRet .= '<tr style="border-bottom:solid 1px white">
								   <td align="right"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
								   <td align="right" valign="top" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;
			}
		}



		$cRet .= "</table>";


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='" . SKPD_BKD . "'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$daerah  = $rowsc->daerah;
		}

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$ttd1' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}


		if ($ttd1 != '1') {
			$xx = "<u>";
			$xy = "</u>";
			$nipxx = $nip;
			$nipx = "NIP.";
		} else {
			$xx = "";
			$xy = "";
			$nipxx = "";
			$nipx = "";
		}
		if ($tanggal_ttd == 1) {
			$tgltd = '';
		} else {
			$tgltd = $this->custom->tanggal_format_indonesia($tanggal_ttd);
		}

		$cRet .= '<br><br>
			<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" > Melawi , ' . $tgltd . '</TD>
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
							<TD align="center" >' . $xx . '<b>' . $namax . '</b>' . $xy . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $pangkat . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
						</TR>
						</TABLE><br/>';

		$data['prev'] = $cRet;
		$judul = 'Perda_LampI.2 ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->support->_mpdf('', $cRet, 10, 10, 10, '1');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}
	

	//================================================ End Lamp Perda I.2

	//================================================ Lamp Perda I.3
	function perdaI_3()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. I.3';
		$this->template->set('title', 'CETAK PERDA LAMP. I.3');
		$this->template->load('template', 'perdase/cetak_perda_lampI_3', $data);
	}

	function cetak_perda_lampI_3_akunSE($bulan = '', $ctk = '', $anggaran = '', $kd_skpd = '', $jenis = '', $tglttd = '', $ttd = '', $ttdperda = '', $label = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunangn = $lntahunang + 1;
		$banyak = $this->uri->segment(12);
		$anggaran = $this->uri->segment(13);
		$kd_skpd = $this->uri->segment(6);
		// echo($anggaran);
		// return;
		$ttd1 = str_replace('n', ' ', $ttdperda);
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

		if ($label == '1') {
			$label = 'UNAUDITED';
		} else if ($label == '2') {
			$label = 'AUDITED';
		} else {
			$label = '&nbsp;';
		}

		if($kd_skpd != '-'){
			$where = "AND a.kd_skpd='$kd_skpd'";
			$where1 = "AND b.kd_skpd='$kd_skpd'";
			$where2 = "AND kd_skpd='$kd_skpd'";
		}else{
			$where = '';
			$where1 = '';
			$where2='';
		}

		
		/*if($ttd=='-'){
		$nama='';
		$pangkat='';
		$jabatan='';
		$nip='';
	 }else{
		$ttd=str_replace("abc"," ",$ttd);
		$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA' and kd_skpd='4.02.01.00'";
					$sqlsclient=$this->db->query($sqlsc);
					foreach ($sqlsclient->result() as $rowttd)
					{
						$nama = $rowttd->nama;
						$jabatan = $rowttd->jabatan;
						$pangkat = $rowttd->pangkat;
						$nip = 'NIP. '.$ttd;
					} 
		}*/
        

		if ($kd_skpd == '1.01.2.22.0.00.02.0000') {
			$cRet = '<br/><TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="left" >LAMPIRAN I.3 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN DAERAH KABUPATEN MELAWI <br/> 
                        NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangn . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
                        </TD>
					</TR>
                    <TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="center" ><b>&nbsp;</TD>
					</TR>
					</TABLE>';
            
		} else {
			$cRet = '';
		}
		

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"3\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"5\"  style=\"border-right:hidden;border-bottom:hidden;border-right:hidden\">&nbsp;&nbsp;&nbsp;</td>
					</tr>	
					<tr>
					<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden;border-bottom:hidden\">
							&nbsp;
							</td>
					<td align=\"center\" style=\"border-bottom:hidden\"><strong>" . $sclient->kab_kota . "</strong></td></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>RINCIAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM, KEGIATAN, SUB KEGIATAN, KELOMPOK <br> </b></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>DAN JENIS PENDAPATAN, BELANJA DAN PEMBIAYAAN</b></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN " . $lntahunang . " <br/> $label</b></tr>
					</TABLE>";

					if($kd_skpd != '-'){
						$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
						<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan', 'ms_urusan', 'kd_urusan') . " </td>
						</tr>
						<tr>
						<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp;Organisasi </td>
						<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 22) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 22), 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
						</tr>
						</TABLE>";
					}else{
						$cRet = "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"3\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td rowspan=\"5\"  style=\"border-right:hidden;border-bottom:hidden;border-right:hidden\">&nbsp;&nbsp;&nbsp;</td>
						</tr>	
						<tr>
						<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden;border-bottom:hidden\">
								&nbsp;
								</td>
						<td align=\"center\" style=\"border-bottom:hidden\"><strong>" . $sclient->kab_kota . "</strong></td></tr>
						<tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>RINCIAN APBD MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM, KEGIATAN, SUB KEGIATAN, KELOMPOK <br> </b></tr>
						<tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>DAN JENIS PENDAPATAN, BELANJA DAN PEMBIAYAAN</b></tr>
						<tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN " . $lntahunang . " <br/> $label</b></tr>
						</TABLE>";
	;
					}

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:10px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"$banyak\">
                <thead>
				<tr>
                    <td rowspan=\"2\" colspan = \"9\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Kode Rekening</b></td>
                    <td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Uraian</b></td>
                    <td colspan=\"2\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Jumlah (Rp.)</b></td>
                    <td colspan=\"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Bertambah /(Berkurang)</b></td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Dasar Hukum</b></td>
				</tr>
				<tr>
					<td width=\"13%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Anggaran Setelah Perubahan</b></td>
					<td width=\"13%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Realisasi</b></td>
					<td width=\"13%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Rp.</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
				</thead>";


		  $sql = "SELECT z.kd_skpd, z.kd_kegiatan AS kd_kegiatan,z.kode AS kd_rek,left(z.kd_kegiatan,1) as kd1,SUBSTRING(z.kd_kegiatan,3,2) as kd2, left(kd_skpd,17)	as kd3,		SUBSTRING(z.kd_kegiatan,6,2) as kd4,SUBSTRING(z.kd_kegiatan,9,4) as kd5, SUBSTRING(z.kd_kegiatan,11,2) as kd6, z.kd7 as kd7, z.kd8 as kd8, z.kd9 as kd9,z.nama 		AS nm_rek,SUM (z.anggaran) AS anggaran,SUM (z.reali) AS sd_bulan_ini,sumber FROM ( 
			----kegiatan 
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan AS kd_kegiatan,LEFT (a.kd_rek6,1) AS kode,b.nm_sub_kegiatan AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber ,LEFT (a.kd_rek6,1) as kd7, '' as kd8, '' as kd9 FROM data_realisasi_pemkot a INNER JOIN ms_sub_kegiatan b ON b.kd_sub_kegiatan =a.kd_sub_kegiatan  WHERE LEFT (a.kd_rek6,1)='4' AND bulan='$bulan' AND a.jns_ang='$anggaran' $where GROUP BY a.kd_sub_kegiatan,b.nm_sub_kegiatan,LEFT (a.kd_rek6,1) ,a.kd_skpd
			---sub kegiatan 
			UNION ALL 
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,2) AS kd_kegiatan,LEFT (a.kd_rek6,2) AS kode,b.nm_rek2 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, '' as kd9 FROM data_realisasi_pemkot a INNER JOIN ms_rek2 b ON b.kd_rek2 =LEFT (a.kd_rek6,2) WHERE LEFT (a.kd_rek6,1)='4' AND bulan='$bulan' AND a.jns_ang='$anggaran' $where GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,2),b.nm_rek2 ,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1)
			UNION ALL 
			----akun3 
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,4) AS kd_kegiatan,LEFT (a.kd_rek6,4) AS kode,b.nm_rek3 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, SUBSTRING (a.kd_rek6,3,2) as kd9 FROM data_realisasi_pemkot a INNER JOIN ms_rek3 b ON b.kd_rek3 =LEFT (a.kd_rek6,4) WHERE LEFT (a.kd_rek6,1)='4' AND bulan='$bulan' AND a.jns_ang='$anggaran' $where GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,4),b.nm_rek3,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2)
			) z 
			GROUP BY kd_kegiatan,kode,nama,sumber ,kd_skpd,z.kd7,z.kd8,z.kd9
			ORDER BY kd_kegiatan,kode,nama";


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sumber = $row->sumber;
			$kd_skpd = $row->kd_skpd;
			$kd1 	= $row->kd1;
			$kd2 	= $row->kd2;
			$kd3 	= $row->kd3;
			$kd4	= $row->kd4;
			$kd5	= $row->kd5;
			$kd6 	= $row->kd6;
			$kd7 	= $row->kd7;
			$kd8 	= $row->kd8;
			$kd9 	= $row->kd9;
			
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			if ($sumber == 1210102){
				$sumber = "DAU";
			}else if($sumber == 12202){
				$sumber = "Bantuan Keuangan";
			}else if($sumber == 11416){
				$sumber = "Pendapatan dari BLUD";
			}else if($sumber == 221010418){
				$sumber = "DAK Non Fisik-Dana Yanminduk";
			}else if($sumber == 11){
				$sumber = "PAD";
			}else if($sumber == 2210104){
				$sumber = "DAK Non Fisik";
			}else if($sumber == 2210102){
				$sumber = "DAU";
			}else if($sumber == 1){
				$sumber = "DANA UMUM";
			}else{
				$sumber = "";
			}

					$cRet .= '<tr>
							   <td align="center" valign="top">' .$kd1. '</td> 
							   <td align="center" valign="top">' .$kd2. '</td>
							   <td align="center" valign="top">' .$kd3. '</td> 
							   <td align="center" valign="top">' .$kd4. '</td> 
							   <td align="center" valign="top">' .$kd5. '</td> 
							   <td align="center" valign="top">' .$kd6. '</td> 
							   <td align="center" valign="top">' .$kd7. '</td> 
							   <td align="center" valign="top">' .$kd8. '</td> 
							   <td align="center" valign="top">' .$kd9. '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							</tr>';
				
		}

		$hasil->free_result();

		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a WHERE left(a.kd_rek6,1)='4' and bulan='$bulan' AND a.jns_ang='$anggaran' $where";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

		$cRet .= '<tr>
							   <td align="right" valign="top" colspan="10">JUMLAH PENDAPATAN</td>
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td> 
							   </tr>';
		}
			$sql = "SELECT z.kd_skpd,z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,left(z.kd_kegiatan,1) as kd1,SUBSTRING(z.kd_kegiatan,3,2) as kd2, left(kd_skpd,17)as kd3,kd4,kd5,kd6,kd7,kd8,kd9,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from( 
				----program 
				SELECT a.kd_skpd,b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber, SUBSTRING(b.kd_program,6,2) as kd4, '' as kd5, '' as kd6, '' as kd7, '' as kd8, '' as kd9 FROM data_realisasi_pemkot a inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7) WHERE left(a.kd_rek6,1)='5' and bulan='$bulan' AND a.jns_ang='$anggaran' $where group by b.kd_program,b.nm_program,a.kd_skpd,SUBSTRING(b.kd_program,6,2)
				union all 
				SELECT a.kd_skpd,b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,SUBSTRING(b.kd_kegiatan,6,2) as kd4, SUBSTRING(b.kd_kegiatan,9,4) as kd5, '' as kd6, '' as kd7, '' as kd8, '' as kd9 FROM data_realisasi_pemkot a inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan and b.kd_skpd = a.kd_skpd AND a.jns_ang=b.jns_ang WHERE left(a.kd_rek6,1)='5' and bulan='$bulan' AND a.jns_ang='$anggaran' $where group by b.kd_kegiatan,b.nm_kegiatan,a.kd_skpd,SUBSTRING(b.kd_kegiatan,6,2),SUBSTRING(b.kd_kegiatan,9,4)
				union all 
				----kegiatan 
				SELECT a.kd_skpd,a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, SUBSTRING(a.kd_sub_kegiatan,9,4) as kd5, SUBSTRING (a.kd_sub_kegiatan,14,2) as kd6, '' as kd7, '' as kd8, '' as kd9 FROM data_realisasi_pemkot a inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan WHERE left(a.kd_rek6,1)='5' and bulan='$bulan' AND a.jns_ang='$anggaran' $where group by a.kd_sub_kegiatan,b.nm_sub_kegiatan ,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,9,4),SUBSTRING(a.kd_sub_kegiatan,6,2),SUBSTRING (a.kd_sub_kegiatan,14,2)
				union all 
				----akun1
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+ LEFT (a.kd_rek6,1) AS kd_kegiatan,LEFT (a.kd_rek6,1) AS kode,b.nm_rek1 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, SUBSTRING(a.kd_sub_kegiatan,9,4) as kd5, SUBSTRING (a.kd_sub_kegiatan,14,2) as kd6, LEFT (a.kd_rek6,1) as kd7, '' as kd8, '' as kd9 FROM data_realisasi_pemkot a INNER JOIN ms_rek1 b ON b.kd_rek1 =LEFT (a.kd_rek6,1) WHERE LEFT (a.kd_rek6,1)='5' AND bulan='$bulan' AND a.jns_ang='$anggaran' $where GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,1),b.nm_rek1,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2),SUBSTRING(a.kd_sub_kegiatan,9,4),SUBSTRING (a.kd_sub_kegiatan,14,2), LEFT (a.kd_rek6,1) 
				union all
				----akun2
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+ LEFT (a.kd_rek6,2) AS kd_kegiatan,LEFT (a.kd_rek6,2) AS kode,b.nm_rek2 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, SUBSTRING(a.kd_sub_kegiatan,9,4) as kd5, SUBSTRING (a.kd_sub_kegiatan,14,2) as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1)  as kd8, '' as kd9 FROM data_realisasi_pemkot a INNER JOIN ms_rek2 b ON b.kd_rek2 =LEFT (a.kd_rek6,2) WHERE LEFT (a.kd_rek6,1)='5' AND bulan='$bulan' AND a.jns_ang='$anggaran' $where GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,2),b.nm_rek2,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2),SUBSTRING(a.kd_sub_kegiatan,9,4),SUBSTRING (a.kd_sub_kegiatan,14,2), LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,2,1)
				union all
				----akun3 
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+left(a.kd_rek6,4) as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, SUBSTRING(a.kd_sub_kegiatan,9,4) as kd5, SUBSTRING (a.kd_sub_kegiatan,14,2) as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1)  as kd8, SUBSTRING(a.kd_rek6, 3,2) as kd9 FROM data_realisasi_pemkot a inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4) WHERE left(a.kd_rek6,1)='5' and bulan='$bulan' AND a.jns_ang='$anggaran' $where group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 ,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2),SUBSTRING(a.kd_sub_kegiatan,9,4),SUBSTRING (a.kd_sub_kegiatan,14,2), LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,2,1),SUBSTRING(a.kd_rek6, 3,2)
				) z 
				group by kd_kegiatan,kode,nama,sumber,kd_skpd,kd4,kd5,kd6,kd7,kd8,kd9
				order by kd_kegiatan,kode,nama";
		

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sumber = $row->sumber;
			$kd_skpd = $row->kd_skpd;
			$kd1 	= $row->kd1;
			$kd2 	= $row->kd2;
			$kd3 	= $row->kd3;
			$kd4	= $row->kd4;
			$kd5	= $row->kd5;
			$kd6 	= $row->kd6;
			$kd7 	= $row->kd7;
			$kd8 	= $row->kd8;
			$kd9 	= $row->kd9;



			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			if ($sumber == 1210102){
				$sumber = "DAU";
			}else if($sumber == 12202){
				$sumber = "Bantuan Keuangan";
			}else if($sumber == 11416){
				$sumber = "Pendapatan dari BLUD";
			}else if($sumber == 221010418){
				$sumber = "DAK Non Fisik-Dana Yanminduk";
			}else if($sumber == 11){
				$sumber = "PAD";
			}else if($sumber == 2210104){
				$sumber = "DAK Non Fisik";
			}else if($sumber == 2210102){
				$sumber = "DAU";
			}else if($sumber == 1){
				$sumber = "DANA UMUM";
			}else{
				$sumber = "";
			}

					$cRet .= '<tr>
								<td align="center" width="3%" valign="top">' .$kd1. '</td> 
								<td align="center" width="4%" valign="top">' .$kd2. '</td>
								<td align="center" width="10%" valign="top">' .$kd3. '</td> 
								<td align="center" width="4%" valign="top">' .$kd4. '</td> 
								<td align="center" width="6%" valign="top">' .$kd5. '</td> 
								<td align="center" width="4%" valign="top">' .$kd6. '</td> 
								<td align="center" width="3%" valign="top">' .$kd7. '</td> 
								<td align="center" width="3%" valign="top">' .$kd8. '</td> 
								<td align="center" width="4%" valign="top">' .$kd9. '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td> 
							</tr>';
		}
        
				$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a WHERE left(a.kd_rek6,1) in ('5') and bulan='$bulan' AND a.jns_ang='$anggaran' $where";
				$hasil = $this->db->query($sql);
				foreach ($hasil->result() as $row) {

				$nil_ang13 = $row->anggaran;
				$sd_bulan_ini13 = $row->sd_bulan_ini;
				$sisa13 = $sd_bulan_ini13 - $nil_ang13;
				$persen13 = empty($nil_ang13) || $nil_ang13 == 0 ? 0 : $sd_bulan_ini13 / $nil_ang13 * 100;
				$sisa133 = $sisa13 < 0 ? $sisa13 * -1 : $sisa13;
				$y = $sisa13 < 0 ? '(' : '';
				$z = $sisa13 < 0 ? ')' : '';

				$cRet .= '<tr>
						<td align="right" colspan="10"  valign="top">JUMLAH BELANJA</td> 
						<td align="right" valign="top">' . number_format($nil_ang13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . number_format($sd_bulan_ini13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . $y . ' ' . number_format($sisa133, "2", ",", ".") . ' ' . $z . '</td> 
						<td align="right" valign="top">' . number_format($persen13, "2", ",", ".") . '</b></td> 
						<td align="right" valign="top"></td> 
					</tr>';

				}

				$sql = "SELECT 
						SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
						SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
						FROM
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM(nilai_ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' AND jns_ang='$anggaran' and LEFT(kd_ang,1) IN ('4','5','6') $where2
						GROUP BY LEFT(kd_ang,1)) a;
						";
					$hasil = $this->db->query($sql);
					foreach ($hasil->result() as $row) {
						$ang_surplus = $row->ang_surplus;
						$nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $ang_surplus - $nil_surplus;
					if (($ang_surplus == 0) || ($ang_surplus == '')) {
						$persen_surplus = 0;
					} else {
						$persen_surplus = $nil_surplus / $ang_surplus * 100;
					}
					$hasil->free_result();
					if ($ang_surplus < 0) {
						$ang_surplus1 = $ang_surplus * -1;
						$a = '(';
						$b = ')';
					} else {
						$ang_surplus1 = $ang_surplus;
						$a = '';
						$b = '';
					}
					if ($nil_surplus < 0) {
						$nil_surplus1 = $nil_surplus * -1;
						$c = '(';
						$d = ')';
					} else {
						$nil_surplus1 = $nil_surplus;
						$c = '';
						$d = '';
					}
					if ($sisa_surplus < 0) {
						$sisa_surplus1 = $sisa_surplus * -1;
						$e = '(';
						$f = ')';
					} else {
						$sisa_surplus1 = $sisa_surplus;
						$e = '';
						$f = '';
					}

					
					$cRet .= '<tr>
						<td align="right" colspan="10" valign="top">TOTAL SURPLUS / (DEFISIT)</td> 
						<td align="right" valign="top">' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</td> 
						<td align="right" valign="top">' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</td> 
						<td align="right" valign="top">' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</td> 
						<td align="right" valign="top">' . number_format($persen_surplus, "2", ",", ".") . '</td> 
						<td align="right" valign="top"></td> 
					</tr>';
					
		$hasil->free_result();
		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

			$sql = "SELECT z.kd_skpd,z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,left(z.kd_kegiatan,1) as kd1,SUBSTRING(z.kd_kegiatan,3,2) as kd2, left(kd_skpd,17)as kd3,kd4,kd5,kd6,kd7,kd8,kd9,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from( 
				----program 
				SELECT a.kd_skpd,b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber, SUBSTRING(b.kd_program,6,2) as kd4, '' as kd5, '' as kd6, '' as kd7, '' as kd8, '' as kd9 FROM data_realisasi_pemkot a inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7) WHERE left(a.kd_rek6,2)='61' and bulan='$bulan' group by b.kd_program,b.nm_program,a.kd_skpd,SUBSTRING(b.kd_program,6,2)
				union all 
				SELECT a.kd_skpd,b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,SUBSTRING(b.kd_kegiatan,6,2) as kd4, '0.00' as kd5, '' as kd6, '' as kd7, '' as kd8, '' as kd9 FROM data_realisasi_pemkot a inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan and b.kd_skpd = a.kd_skpd AND a.jns_ang=b.jns_ang WHERE left(a.kd_rek6,2)='61' and bulan='$bulan' $where group by b.kd_kegiatan,b.nm_kegiatan,a.kd_skpd,SUBSTRING(b.kd_kegiatan,6,2)
				union all 
				----kegiatan 
				SELECT a.kd_skpd,a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, '0.00' as kd5,'00' as kd6, '' as kd7, '' as kd8, '' as kd9 FROM data_realisasi_pemkot a inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan WHERE left(a.kd_rek6,2)='61' and bulan='$bulan' AND a.jns_ang='$anggaran' $where group by a.kd_sub_kegiatan,b.nm_sub_kegiatan ,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2)
				union all 
				----akun1
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+ LEFT (a.kd_rek6,1) AS kd_kegiatan,LEFT (a.kd_rek6,1) AS kode,b.nm_rek1 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, '0.00' as kd5, '00' as kd6, LEFT (a.kd_rek6,1) as kd7, '' as kd8, '' as kd9 FROM data_realisasi_pemkot a INNER JOIN ms_rek1 b ON b.kd_rek1 =LEFT (a.kd_rek6,1) WHERE LEFT (a.kd_rek6,2)='61' AND bulan='$bulan' AND a.jns_ang='$anggaran' $where GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,1),b.nm_rek1,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2), LEFT (a.kd_rek6,1) 
				union all
				----akun2
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+ LEFT (a.kd_rek6,2) AS kd_kegiatan,LEFT (a.kd_rek6,2) AS kode,b.nm_rek2 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, '0.00' as kd5, '00' as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1)  as kd8, '' as kd9 FROM data_realisasi_pemkot a INNER JOIN ms_rek2 b ON b.kd_rek2 =LEFT (a.kd_rek6,2) WHERE LEFT (a.kd_rek6,2)='61' AND bulan='$bulan' AND a.jns_ang='$anggaran' $where GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,2),b.nm_rek2,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2), LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,2,1)
				union all
				----akun3 
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+left(a.kd_rek6,4) as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, '0.00' as kd5, '00' as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1)  as kd8, SUBSTRING(a.kd_rek6, 3,2) as kd9 FROM data_realisasi_pemkot a inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4) WHERE left(a.kd_rek6,2)='61' and bulan='$bulan' AND a.jns_ang='$anggaran' $where group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 ,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2), LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,2,1),SUBSTRING(a.kd_rek6, 3,2)
				) z 
				group by kd_kegiatan,kode,nama,sumber,kd_skpd,kd4,kd5,kd6,kd7,kd8,kd9
				order by kd_kegiatan,kode,nama";
		

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$kd_skpd = $row->kd_skpd;
			$kd1 	= $row->kd1;
			$kd2 	= $row->kd2;
			$kd3 	= $row->kd3;
			$kd4	= $row->kd4;
			$kd5	= $row->kd5;
			$kd6 	= $row->kd6;
			$kd7 	= $row->kd7;
			$kd8 	= $row->kd8;
			$kd9 	= $row->kd9;

			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

				$cRet .= '<tr>
								<td align="center" valign="top">' .$kd1. '</td> 
								<td align="center" valign="top">' .$kd2. '</td>
								<td align="center" valign="top">' .$kd3. '</td> 
								<td align="center" valign="top">' .$kd4. '</td> 
								<td align="center" valign="top">' .$kd5. '</td> 
								<td align="center" valign="top">' .$kd6. '</td> 
								<td align="center" valign="top">' .$kd7. '</td> 
								<td align="center" valign="top">' .$kd8. '</td> 
								<td align="center" valign="top">' .$kd9. '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
							</tr>';
		}

		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a WHERE left(a.kd_rek6,2) in ('61') and bulan='$bulan' AND a.jns_ang='$anggaran' $where";
				$hasil = $this->db->query($sql);
				foreach ($hasil->result() as $row) {

				$nil_ang13 = $row->anggaran;
				$sd_bulan_ini13 = $row->sd_bulan_ini;
				$sisa13 = $sd_bulan_ini13 - $nil_ang13;
				$persen13 = empty($nil_ang13) || $nil_ang13 == 0 ? 0 : $sd_bulan_ini13 / $nil_ang13 * 100;
				$sisa133 = $sisa13 < 0 ? $sisa13 * -1 : $sisa13;
				$y = $sisa13 < 0 ? '(' : '';
				$z = $sisa13 < 0 ? ')' : '';

				$cRet .= '<tr>
						<td align="right" colspan = "10"  valign="top">JUMLAH PENERIMAAN PEMBIAYAAN</td> 
						<td align="right" valign="top">' . number_format($nil_ang13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . number_format($sd_bulan_ini13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . $y . ' ' . number_format($sisa133, "2", ",", ".") . ' ' . $z . '</td> 
						<td align="right" valign="top">' . number_format($persen13, "2", ",", ".") . '</td> 
						<td align="right" valign="top"></td> 
					</tr>';
				}


		$hasil->free_result();
		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

		
			$sql = "SELECT z.kd_skpd,z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,left(z.kd_kegiatan,1) as kd1,SUBSTRING(z.kd_kegiatan,3,2) as kd2, left(kd_skpd,17)as kd3,	kd4,kd5,kd6,kd7,kd8,kd9,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from( 
				----akun2
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+ LEFT (a.kd_rek6,2) AS kd_kegiatan,LEFT (a.kd_rek6,2) AS kode,b.nm_rek2 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, '0.00' as kd5, '00' as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1)  as kd8, '' as kd9 FROM data_realisasi_pemkot a INNER JOIN ms_rek2 b ON b.kd_rek2 =LEFT (a.kd_rek6,2) WHERE LEFT (a.kd_rek6,2)='62' AND bulan='$bulan' AND a.jns_ang='$anggaran' $where GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,2),b.nm_rek2,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2), LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,2,1)
				union all
				----akun3 
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+left(a.kd_rek6,4) as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, '0.00' as kd5, '00' as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1)  as kd8, SUBSTRING(a.kd_rek6, 3,2) as kd9 FROM data_realisasi_pemkot a inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4) WHERE left(a.kd_rek6,2)='62' and bulan='$bulan' AND a.jns_ang='$anggaran' $where group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 ,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2), LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,2,1),SUBSTRING(a.kd_rek6, 3,2)
				) z 
				group by kd_kegiatan,kode,nama,sumber,kd_skpd,kd4,kd5,kd6,kd7,kd8,kd9
				order by kd_kegiatan,kode,nama";
		

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$kd_skpd = $row->kd_skpd;
			$kd1 	= $row->kd1;
			$kd2 	= $row->kd2;
			$kd3 	= $row->kd3;
			$kd4	= $row->kd4;
			$kd5	= $row->kd5;
			$kd6 	= $row->kd6;
			$kd7 	= $row->kd7;
			$kd8 	= $row->kd8;
			$kd9 	= $row->kd9;

			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			
					$cRet .= '<tr>
								<td align="center" valign="top">' .$kd1. '</td> 
								<td align="center" valign="top">' .$kd2. '</td>
								<td align="center" valign="top">' .$kd3. '</td> 
								<td align="center" valign="top">' .$kd4. '</td> 
								<td align="center" valign="top">' .$kd5. '</td> 
								<td align="center" valign="top">' .$kd6. '</td> 
								<td align="center" valign="top">' .$kd7. '</td> 
								<td align="center" valign="top">' .$kd8. '</td> 
								<td align="center" valign="top">' .$kd9. '</td>
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
							</tr>';
			
		}
		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a WHERE left(a.kd_rek6,2) in ('62') and bulan='$bulan' AND a.jns_ang='$anggaran' $where";
				$hasil = $this->db->query($sql);
				foreach ($hasil->result() as $row) {

				$nil_ang13 = $row->anggaran;
				$sd_bulan_ini13 = $row->sd_bulan_ini;
				$sisa13 = $sd_bulan_ini13 - $nil_ang13;
				$persen13 = empty($nil_ang13) || $nil_ang13 == 0 ? 0 : $sd_bulan_ini13 / $nil_ang13 * 100;
				$sisa133 = $sisa13 < 0 ? $sisa13 * -1 : $sisa13;
				$y = $sisa13 < 0 ? '(' : '';
				$z = $sisa13 < 0 ? ')' : '';

				$cRet .= '<tr>
						<td align="right" colspan = "10"  valign="top">JUMLAH PENGELUARAN PEMBIAYAAN</td> 
						<td align="right" valign="top">' . number_format($nil_ang13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . number_format($sd_bulan_ini13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . $y . ' ' . number_format($sisa133, "2", ",", ".") . ' ' . $z . '</td> 
						<td align="right" valign="top">' . number_format($persen13, "2", ",", ".") . '</td> 
						<td align="right" valign="top"></td> 
					</tr>';
				}

		//pembiayaan netto
		$sql = "SELECT isnull(SUM (z.anggaran61),0)-isnull(SUM (z.anggaran62),0) AS anggaran,isnull(SUM (z.sd_bulan_ini61),0)-isnull(SUM (z.sd_bulan_ini62),0) AS sd_bulan_ini FROM (
			SELECT SUM (a.nilai_ang) anggaran61,SUM (a.real_spj) sd_bulan_ini61,0 AS anggaran62,0 AS sd_bulan_ini62 FROM data_realisasi_pemkot a WHERE LEFT (a.kd_rek6,2)='61' AND bulan='$bulan' AND a.jns_ang='$anggaran' $where UNION
			SELECT 0 AS anggaran61,0 AS sd_bulan_ini61,SUM (b.nilai_ang) anggaran62,SUM (b.real_spj) sd_bulan_ini62 FROM data_realisasi_pemkot b WHERE LEFT (b.kd_rek6,2)='62' AND bulan='$bulan' AND b.jns_ang='$anggaran' $where1) z";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang12 = $row->anggaran;
			$sd_bulan_ini12 = $row->sd_bulan_ini;
			$sisa12 = $sd_bulan_ini12 - $nil_ang12;
			$persen12 = empty($nil_ang12) || $nil_ang12 == 0 ? 0 : $sd_bulan_ini12 / $nil_ang12 * 100;
			$sisa12 = $sisa12 < 0 ? $sisa12 * -1 : $sisa12;
			$a = $sisa12 < 0 ? '(' : '';
			$b = $sisa12 < 0 ? ')' : '';

			//if ($nil_ang12 != 0) {
				$cRet .= '<tr>
								<td align="right" colspan = "10"  valign="top">PEMBIAYAAN NETO</td> 
								<td align="right" valign="top">' . number_format($nil_ang12, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini12, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa12, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen12, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
							</tr>';
				
		}
		//end


		$sql = "SELECT (isnull(SUM (z.anggaran_4),0)-isnull(SUM (z.anggaran_5),0))+(isnull(SUM (z.anggaran_61),0)-isnull(SUM (z.anggaran_62),0)) AS anggaran,(isnull(SUM (z.sd_bulan_ini_4),0)-isnull(SUM (z.sd_bulan_ini_5),0))+(isnull(SUM (z.sd_bulan_ini_61),0)-isnull(SUM (z.sd_bulan_ini_62),0)) AS sd_bulan_ini FROM (
			SELECT SUM (a.nilai_ang) anggaran_4,SUM (a.real_spj) sd_bulan_ini_4,0 anggaran_5,0 AS sd_bulan_ini_5,0 anggaran_61,0 AS sd_bulan_ini_61,0 AS anggaran_62,0 AS sd_bulan_ini_62 FROM data_realisasi_pemkot a WHERE LEFT (a.kd_ang,1) IN ('4') AND bulan='$bulan' AND a.jns_ang='$anggaran' $where UNION
			SELECT 0 AS anggaran_4,0 AS sd_bulan_ini_4,SUM (a.nilai_ang) anggaran_5,SUM (a.real_spj) sd_bulan_ini_5,0 AS anggaran_61,0 AS sd_bulan_ini_61,0 AS anggaran_62,0 AS sd_bulan_ini_62 FROM data_realisasi_pemkot a WHERE LEFT (a.kd_ang,1) IN ('5') AND bulan='$bulan' AND a.jns_ang='$anggaran' $where UNION
			SELECT 0 AS anggaran_4,0 AS sd_bulan_ini_4,0 AS anggaran_5,0 AS sd_bulan_ini_5,SUM (a.nilai_ang) anggaran_61,SUM (a.real_spj) sd_bulan_ini_61,0 AS anggaran_62,0 AS sd_bulan_ini_62 FROM data_realisasi_pemkot a WHERE LEFT (a.kd_ang,2) IN ('61') AND bulan='$bulan' AND a.jns_ang='$anggaran' $where UNION
			SELECT 0 AS anggaran_4,0 AS sd_bulan_ini_4,0 AS anggaran_5,0 AS sd_bulan_ini_5,0 AS anggaran_61,0 AS sd_bulan_ini_61,SUM (a.nilai_ang) anggaran_62,SUM (a.real_spj) sd_bulan_ini_62 FROM data_realisasi_pemkot a WHERE LEFT (a.kd_ang,2) IN ('62') AND bulan='$bulan' AND a.jns_ang='$anggaran' $where)z";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang145 = $row->anggaran;
			$sd_bulan_ini145 = $row->sd_bulan_ini;
			$sisa1 = $sd_bulan_ini145 - $nil_ang145;
			$persen145 = empty($nil_ang145) || $nil_ang145 == 0 ? 0 : $sd_bulan_ini145 / $nil_ang145 * 100;

			// $a45 = $sisa1 < 0 ? '(' : '';
			// $b45 = $sisa1 < 0 ? ')' : '';
			// $sisa1145 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;

            if ($sisa1 < 0) {
                $sisa1145 = $sisa1 * -1;
                $a45 = '(';
                $b45 = ')';
            } else {
                $sisa1145 = $sisa1;
                $a45 = '';
                $b45 = '';
            }

			$a445 = $nil_ang145 < 0 ? '(' : '';
			$b445 = $nil_ang145 < 0 ? ')' : '';
			$nil_ang1455 = $nil_ang145 < 0 ? $nil_ang145 * -1 : $nil_ang145;

			$a455 = $sd_bulan_ini145 < 0 ? '(' : '';
			$b455 = $sd_bulan_ini145 < 0 ? ')' : '';
			$sd_bulan_ini1455 = $sd_bulan_ini145 < 0 ? $sd_bulan_ini145 * -1 : $sd_bulan_ini145;


			$cRet .= '<tr>
								<td align="right" colspan="10" valign="top">SISA LEBIH PEMBIAYAAN ANGGARAN (SILPA)</td> 
								<td align="right" valign="top">' . $a445 . ' ' . number_format($nil_ang1455, "2", ",", ".") . ' ' . $b445 . '</td> 
								<td align="right" valign="top">' . $a455 . ' ' . number_format($sd_bulan_ini1455, "2", ",", ".") . ' ' . $b455 . '</td> 
								<td align="right" valign="top">' . $a45 . ' ' . number_format($sisa1145, "2", ",", ".") . ' ' . $b45 . '</td> 
								<td align="right" valign="top">' . number_format($persen145, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
							</tr>';
		}
		$hasil->free_result();

		$cRet .= "</table>";

		$tanggal = $tglttd == '-' ? '' : 'Melawi, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		
			$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='8.01.0.00.0.00.01.0000'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowsc) {
				$kab     = $rowsc->kab_kota;
				$daerah  = $rowsc->daerah;
			}

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip ='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}
			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tglttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
			}

			if ($nip == '00000000 000000 0 000'){
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
								</TR>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" ><b>' . $jabatan . '</b></TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b></TD>
									<TD width="50%" align="center" ><b><b>TTD<b></TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" ><b>' . $nama . '</b></TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" >Salinan sesuai dengan aslinya</TD>
									<TD width="50%" align="center" >&nbsp;</TD>
								</TR>   
								<TR>
									<TD width="50%" align="center" >KEPALA BAGIAN HUKUM,</TD>
									<TD width="50%" align="center" >&nbsp;</TD>
								</TR>                       
								<TR>
									<TD width="50%" align="center" >&nbsp;</TD>
									<TD width="50%" align="center" >&nbsp;</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" >&nbsp;</TD>
									<TD width="50%" align="center" >&nbsp;</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" >&nbsp;</TD>
									<TD width="50%" align="center" >&nbsp;</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><u>Dr. MARINA RONA, S.H., M.H</u></TD>
									<TD width="50%" align="center" >&nbsp;</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" >Pembina Tk. I (IV/b)</TD>
									<TD width="50%" align="center" >&nbsp;</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" >NIP. 19770315 200502 2 002</TD>
									<TD width="50%" align="center" >&nbsp;</TD>
								</TR>
								</TABLE><br/>';
		
				} else {
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
				}
		

		$data['prev'] = $cRet;
		$judul = 'PERDA_LAMP_I.3 ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf_lamp('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function _mpdf_down_lan($judul = '', $isi = '', $lMargin = 10, $rMargin = 10, $font = '', $orientasi = '', $hal = '', $fonsize = '', $name = '')
	{
		ini_set("memory_limit", "-1M");
		ini_set("MAX_EXECUTION_TIME", "-1");
		$this->load->library('mpdf');
		//$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');


		$this->mpdf->defaultheaderfontsize = 6;	/* in pts */
		$this->mpdf->defaultheaderfontstyle = 'BI';	/* blank, B, I, or BI */
		$this->mpdf->defaultheaderline = 1; 	/* 1 to include line below header/above footer */

		$this->mpdf->defaultfooterfontsize = 6;	/* in pts */
		$this->mpdf->defaultfooterfontstyle = 'BI';	/* blank, B, I, or BI */
		$this->mpdf->defaultfooterline = 1;
		$sa = 1;
		$tes = 0;
		if ($hal == '') {
			$hal1 = 1;
		}
		if ($hal !== '') {
			$hal1 = $hal;
		}
		if ($fonsize == '') {
			$size = 12;
		} else {
			$size = $fonsize;
		}

		$this->mpdf = new mPDF('utf-8', array(215, 330), $size); //folio
		//$this->mpdf->AddPage($orientasi,'',$hal1,'1','off');
		//$this->mpdf->SetFooter("Printed on Simakda || Halaman {PAGENO}  ");
		$this->mpdf->SetFooter('&nbsp;| &nbsp; |Halaman {PAGENO} / {nb}| ');
		$this->mpdf->AddPage($orientasi, '', '', '', '', $lMargin, $rMargin);

		if (!empty($judul)) $this->mpdf->writeHTML($judul);
		$this->mpdf->writeHTML($isi);
		$this->mpdf->Output();
	}



	function cetak_perda_lampI_3_2($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '', $ttdperda = '',$tanggal_ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunangg = $lntahunang;
		$lntahunangg = $lntahunang + 1;
		$ttd1 = str_replace('n', ' ', $ttdperda);

		$lntahunangn = $lntahunang + 1;

		/*if($ttd=='-'){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA' and kd_skpd='4.02.01.00'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}*/


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		$cRet = '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="left" >LAMPIRAN I.3 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN DAERAH MELAWI <br/> 
                        NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangg . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
                        </TD>
					</TR>
                    <TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="center" ><b>&nbsp;</TD>
					</TR>
					</TABLE><br/>';

		$cRet .= "<TABLE style=\"border-collapse:collapse; font-family:Bookman Old Style;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"75\" height=\"100\" />
                        </td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong></strong></td></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>$kab <br>REKAPITULASI REALISASI BELANJA DAERAH MENURUT URUSAN PEMERINTAH DAERAH, <BR> ORGANISASI, PROGRAM, DAN KEGIATAN<br>TAHUN ANGGARAN " . $lntahunang . "</b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
						</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KODE</td>
                    <td rowspan = \"2\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">URUSAN PEMERINTAH DAERAH</td>
					<td colspan = \"5\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">ANGGARAN BELANJA</td>
                    <td rowspan = \"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
					<td colspan = \"5\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI BELANJA</td>
                    <td rowspan = \"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
				</tr>
				<tr>
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEMELIHARAAN</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">NON PEMELIHARAAN</td>                                        
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td> 
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEMELIHARAAN</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">NON PEMELIHARAAN</td>                                                           
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">(4+5 = 6)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">7</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">8</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">9</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">10</td>                    
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">(10 + 11 =12)</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 
				</tr>
				</thead>";


		$sql = " select kd_sub_kegiatan kode,nm_rek,ang_peg,ang_brngpem,ang_brngnonpem,ang_brng,ang_mod,real_peg,real_brngpem,
            real_brngnonpem,real_brng,real_mod 
					FROM [perda_lampI.3_sub]($bulan,$anggaran) ORDER BY kd_sub_kegiatan
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kode;
			$nm_rek = $row->nm_rek;
			$ang_peg = $row->ang_peg;
			$ang_brng1 = $row->ang_brngpem;
			$ang_brng2 = $row->ang_brngnonpem;
			$ang_brng = $row->ang_brng;
			$ang_mod = $row->ang_mod;
			$real_peg = $row->real_peg;
			$real_brng1 = $row->real_brngpem;
			$real_brng2 = $row->real_brngnonpem;
			$real_brng = $row->real_brng;
			$real_mod = $row->real_mod;

			$tot_ang = $ang_peg + $ang_brng + $ang_mod;
			$tot_real = $real_peg + $real_brng + $real_mod;


			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:12px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nm_rek . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_brng1, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_brng2, "2", ",", ".") . '</td> 							   
                               <td align="right" valign="top" style="font-size:12px">' . number_format($ang_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_brng1, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_brng2, "2", ",", ".") . '</td> 							   
                               <td align="right" valign="top" style="font-size:12px">' . number_format($real_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real, "2", ",", ".") . '</td> 
							</tr>';
		}

		$cRet .= "</table>";



		if ($ttd == "1") {

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tglttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
			}

			if ($nip == '00000000 000000 0 000'){
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
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
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
				}
		}


		$data['prev'] = $cRet;
		$judul = 'Perda_LampI';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_perda_lampI_3_tepra($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '', $ttdperda = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$ttd1 = str_replace('n', ' ', $ttdperda);

		/*if($ttd=='-'){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA' and kd_skpd='4.02.01.00'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}*/


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);

		$cRet = "";
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50px\" height=\"75x\" />
                        </td>
					<td align=\"center\" style=\"border-bottom:hidden\"><strong>$kab</strong></td></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>REKAPITULASI ANGGARAN DAN REALISASI BELANJA DAERAH <BR/>MENURUT ORGANISASI</b></tr>
					<tr><td align=\"center\" style=\"border-top:hidden\" ><b>SAMPAI DENGAN " . strtoupper($this->custom->getBulan($bulan)) . " TAHUN ANGGARAN " . $lntahunang . "</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">NAMA PERANGKAT DAERAH</td>
					<td colspan = \"3\" width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BELANJA TIDAK LANGSUNG</td>                    
					<td colspan = \"6\" width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BELANJA LANGSUNG</td>                    
					<td colspan = \"3\" width=\"30%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">TOTAL</td>
				</tr>
				<tr>
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PAGU</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">%</td>
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PAGU</td>                                        
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI</td>
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">%</td>
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PAGU</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">%</td>
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
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 
				</tr>
				</thead>";


		$togang_btl = 0;
		$togreal_btl = 0;
		$togang_bl = 0;
		$togreal_peg = 0;
		$togreal_brg = 0;
		$togreal_mod = 0;
		$togreal_bl = 0;
		$tog_angbl = 0;
		$tog_realbl = 0;

		if ($anggaran == 1) {
			$ang = "a.nilai_ang";
		} else {
			$ang = "a.nilai_ang_ubah";
		}

		$sql = "SELECT b.kd_skpd,b.nm_skpd,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5107','5108') THEN a.nilai_ang ELSE 0 END) AS ang_btl,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5107','5108') THEN a.real_spj ELSE 0 END) AS real_btl,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5201','5202','5203') THEN a.nilai_ang ELSE 0 END) AS ang_bl,SUM (CASE WHEN LEFT (a.kd_rek6,4)='5201' THEN a.real_spj ELSE 0 END) AS real_peg,SUM (CASE WHEN LEFT (a.kd_rek6,4)='5202' THEN a.real_spj ELSE 0 END) AS real_brng,SUM (CASE WHEN LEFT (a.kd_rek6,4)='5203' THEN a.real_spj ELSE 0 END) AS real_mod FROM data_realisasi_pemkot a LEFT JOIN ms_skpd b ON LEFT (a.kd_skpd,22)=b.kd_skpd WHERE a.bulan=$bulan GROUP BY b.kd_skpd,b.nm_skpd ORDER BY b.kd_skpd,b.nm_skpd";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kd_skpd;
			$nm_rek = $row->nm_skpd;
			$totang_btl = $row->ang_btl;
			$totreal_btl = $row->real_btl;
			$per_angbtl   = ($totreal_btl != 0) ? ($totreal_btl / $totang_btl) * 100 : 0;

			$tot_angbl = $row->ang_bl;
			$real_peg = $row->real_peg;
			$real_brng = $row->real_brng;
			$real_mod = $row->real_mod;

			$tot_realbl = $real_peg + $real_brng + $real_mod;
			$per_angbl   = ($tot_realbl != 0) ? ($tot_realbl / $tot_angbl) * 100 : 0;

			$jumlah_angbl = $totang_btl + $tot_angbl;
			$jumlah_realbl = $totreal_btl + $tot_realbl;
			$totpersen   = ($jumlah_realbl != 0) ? ($jumlah_realbl / $jumlah_angbl) * 100 : 0;

			$cRet .= '<tr>
							   <td align="left"  valign="top" style="font-size:10px">' . $nm_rek . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($totang_btl, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($totreal_btl, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($per_angbtl, "0", ",", ".") . '</td> 							   
                               <td align="right" valign="top" style="font-size:10px">' . number_format($tot_angbl, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($real_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($real_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($real_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($tot_realbl, "2", ",", ".") . '</td> 
                               <td align="right" valign="top" style="font-size:10px">' . number_format($per_angbl, "0", ",", ".") . '</td> 
                               <td align="right" valign="top" style="font-size:10px">' . number_format($jumlah_angbl, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($jumlah_realbl, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($totpersen, "0", ",", ".") . '</td> 
							</tr>';
		}

		 $sql = "SELECT SUM (c.ang_btl) ang_btl,SUM (c.ang_bl) ang_bl,SUM (c.real_btl) real_btl,SUM (c.real_peg) real_peg,SUM (c.real_brng) real_brng,SUM (c.real_mod) real_mod FROM (
			SELECT b.kd_skpd,b.nm_skpd,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5107','5108') THEN a.nilai_ang ELSE 0 END) AS ang_btl,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5107','5108') THEN a.real_spj ELSE 0 END) AS real_btl,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5201','5202','5203') THEN a.nilai_ang ELSE 0 END) AS ang_bl,SUM (CASE WHEN LEFT (a.kd_rek6,4)='5201' THEN a.real_spj ELSE 0 END) AS real_peg,SUM (CASE WHEN LEFT (a.kd_rek6,4)='5202' THEN a.real_spj ELSE 0 END) AS real_brng,SUM (CASE WHEN LEFT (a.kd_rek6,4)='5203' THEN a.real_spj ELSE 0 END) AS real_mod FROM data_realisasi_pemkot a LEFT JOIN ms_skpd b ON LEFT (a.kd_skpd,22)=b.kd_skpd WHERE a.bulan=$bulan GROUP BY b.kd_skpd,b.nm_skpd) c";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$togang_btl = $row->ang_btl;
			$togreal_btl =  $row->real_btl;

			$togper_btl   = ($togreal_btl != 0) ? ($togreal_btl / $togang_btl) * 100 : 0;

			$togang_bl = $row->ang_bl;
			$togreal_peg = $row->real_peg;
			$togreal_brg = $row->real_brng;
			$togreal_mod = $row->real_mod;
			$togreal_pbm = $togreal_peg + $togreal_brg + $togreal_mod;
			//$togreal_bl = $togreal_bl + $tot_realbl;

			$togper_bl   = ($togreal_pbm != 0) ? ($togreal_pbm / $togang_bl) * 100 : 0;

			$tog_angbl = $togang_btl + $togang_bl;
			$tog_realbl = $togreal_btl + $togreal_pbm;

			$togper_jml   = ($tog_realbl != 0) ? ($tog_realbl / $tog_angbl) * 100 : 0;

			$cRet .= '<tr>
							   <td align="center"  valign="top" style="font-size:11px">TOTAL</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($togang_btl, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($togreal_btl, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($togper_btl, "0", ",", ".") . '</td> 							   
                               <td align="right" valign="top" style="font-size:10px">' . number_format($togang_bl, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($togreal_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($togreal_brg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($togreal_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($togreal_pbm, "2", ",", ".") . '</td> 
                               <td align="right" valign="top" style="font-size:10px">' . number_format($togper_bl, "0", ",", ".") . '</td> 
                               <td align="right" valign="top" style="font-size:10px">' . number_format($tog_angbl, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($tog_realbl, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:10px">' . number_format($togper_jml, "0", ",", ".") . '</td> 
							</tr>';
		}
		$cRet .= "</table>";



		if ($ttd == "1") {

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tglttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
			}

			if ($nip == '00000000 000000 0 000'){
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
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
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
				}
		}
		//ok


		$data['prev'] = $cRet;
		$judul = 'Perda_LampI';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_perda_lampI_3_prog($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '', $ttdperda = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$ttd1 = str_replace('n', ' ', $ttdperda);


		/*if($ttd=='-'){
		$nama_ttd='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA' and kd_skpd='4.02.01.00'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}*/


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		$cRet = '';

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"75\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>$kab</strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REKAPITULASI REALISASI BELANJA DAERAH MENURUT PROGRAM PEMERINTAH DAERAH</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>SAMPAI DENGAN " . strtoupper($this->custom->getBulan($bulan)) . " TAHUN ANGGARAN " . $lntahunang . "</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">SKPD</td>
                    <td width=\"12%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KODE</td>
                    <td width=\"33%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PROGRAM PEMERINTAH DAERAH</td>
					<td width=\"16%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">ANGGARAN</td>
					<td width=\"16%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI</td>
                    <td width=\"6%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PERSEN</td>
				</tr>
				<tr>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">1</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">2</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">3</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">4</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">5</td>                     
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">6</td>  
				</tr>
				</thead>";

		$total = 0;
		$total_real = 0;
		$sql = " select kd_kegiatan kode, skpd, nm_rek,anggaran,realisasi 
					FROM [perda_lampI.3_sub_2]($bulan,$anggaran) ORDER BY kd_kegiatan
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kode;
			$skpd = $row->skpd;
			$nm_rek = $row->nm_rek;
			$ang = $row->anggaran;
			$real = $row->realisasi;
			$total = $total + $ang;
			$total_real = $total_real + $real;
			$per_   = ($real != 0) ? ($real / $ang) * 100 : 0;
			$per__   = ($total_real != 0) ? ($total_real / $total) * 100 : 0;

			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:12px">' . $skpd . '</td> 							   
                               <td align="center" valign="top" style="font-size:12px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nm_rek . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real, "2", ",", ".") . '</td>
                               <td align="right" valign="top" style="font-size:12px">' . number_format($per_, "2", ",", ".") . '</td> 
							</tr>';
		}

		$cRet .= '
                    <tr>
							   <td align="center" colspan="3" valign="top" style="font-size:12px">TOTAL</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($total, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($total_real, "2", ",", ".") . '</td>                                
							   <td align="right" valign="top" style="font-size:12px">' . number_format($per__, "2", ",", ".") . '</td> 
							</tr>
            </table>';

			if ($ttd == "1") {

				$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
				$sqlttd = $this->db->query($sqlttd1);
				foreach ($sqlttd->result() as $rowttd) {
					$nip = $rowttd->nip;
					$nama = $rowttd->nm;
					$jabatan  = $rowttd->jab;
					$pangkat  = $rowttd->pangkat;
				}
	
				if ($ttd1 != '1') {
					$xx = "<u>";
					$xy = "</u>";
					$nipxx = $nip;
					$nipx = "NIP. ";
				} else {
					$xx = "";
					$xy = "";
					$nipxx = "";
					$nipx = "";
				}
	
				if ($tglttd == 1) {
					$tgltd = '';
				} else {
					$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
				}
	
				if ($nip == '00000000 000000 0 000'){
					$cRet .= '<br><br>
						<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
									
									<TR>
										<TD width="50%" align="center" ><b>&nbsp;</TD>
										<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
										<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
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
						<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
									
									<TR>
										<TD width="50%" align="center" ><b>&nbsp;</TD>
										<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
										<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
									</TR>
									<TR>
										<TD width="50%" align="center" ><b>&nbsp;</TD>
										<TD align="center" >' . $pangkat . '</TD>
									</TR>
									<TR>
										<TD width="50%" align="center" ><b>&nbsp;</TD>
										<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
									</TR>
									</TABLE><br/>';
					}
			}

		//ok


		$data['prev'] = $cRet;
		$judul = 'Perda_LampI_Program';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	//================================================ End Lamp Perda I.3

	//================================================ Lamp Perda I.4

	function perdaI_4()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. I.4';
		$this->template->set('title', 'CETAK PERDA LAMP. I.4');
		$this->template->load('template', 'perdase/cetak_perda_lampI_4', $data);
	}


	function cetak_perda_lampI_4($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '', $ttdperda = '',$tanggal_ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunangg = $lntahunang + 1;
		$ttd1 = str_replace('n', ' ', $ttdperda);

		$lntahunangn = $lntahunang + 1;

		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		$cRet = '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="left" >LAMPIRAN I.3 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN DAERAH KABUPATEN MELAWI <br/> 
                        NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangg . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
                        </TD>
					</TR>
                    <TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="center" ><b>&nbsp;</TD>
					</TR>
					</TABLE><br/>';

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" .FCPATH. "/image/logoHP.png\"  width=\"60px\" height=\"75px\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong></strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>$kab <br>REKAPITULASI REALISASI BELANJA DAERAH MENURUT URUSAN PEMERINTAH DAERAH, <BR> ORGANISASI, PROGRAM, DAN KEGIATAN<br>TAHUN ANGGARAN " . $lntahunang . "</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" width=\"8%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KODE</td>
                    <td rowspan = \"2\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">URUSAN PEMERINTAH DAERAH</td>
                    <td colspan = \"4\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">ANGGARAN BELANJA</td>
                    <td rowspan = \"2\" width=\"9%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
                    <td colspan = \"4\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI BELANJA</td>
                    <td rowspan = \"2\" width=\"9%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
                    <td rowspan = \"2\" width=\"9%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BERTAMBAH/<BR/>BERKURANG</td>
                    <td rowspan = \"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">(%)</td>
                </tr>
				<tr>
                   <td align =\"center\" width=\"9%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">OPERASI</td> 
                   <td align =\"center\" width=\"9%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td> 
                   <td align =\"center\" width=\"9%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BTT</td> 
				   <td align =\"center\" width=\"9%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">TRANSFER</td>
				   <td align =\"center\" width=\"9%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">OPERASI</td> 
                   <td align =\"center\" width=\"9%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td> 
                   <td align =\"center\" width=\"9%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BTT</td> 
				   <td align =\"center\" width=\"9%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">TRANSFER</td>
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
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">11</td> 				   
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">12</td> 				   
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">13</td> 				   
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">14</td> 				   
				</tr>
				</thead>";


		$sql = " select kd_skpd,kd_sub_kegiatan kode,nm_rek,ang_opr,ang_btt,ang_mod,ang_trf,real_opr,real_btt,real_mod, real_trf 
					FROM [perda_lampI.3_sub_2_c]($bulan,$anggaran) order by kd_sub_kegiatan,kd_sub_kegiatan+kd_skpd,urut
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kode;
			$nm_rek = $row->nm_rek;
			$ang_opr = $row->ang_opr;
			$ang_btt = $row->ang_btt;
			$ang_mod = $row->ang_mod;
			$ang_trf = $row->ang_trf;
			$real_opr = $row->real_opr;
			$real_btt = $row->real_btt;
			$real_mod = $row->real_mod;
			$real_trf = $row->real_trf;

			$tot_ang = $ang_opr + $ang_btt + $ang_mod + $ang_trf;
			$tot_real = $real_opr + $real_btt + $real_mod + $real_trf;

			$sisa_bb = $tot_ang - $tot_real;
			if ($sisa_bb < 0) {
				$sisa_bb_ = $sisa_bb * -1;
				$e = '(';
				$f = ')';
			} else {
				$sisa_bb_ = $sisa_bb;
				$e = '';
				$f = '';
			}

			$persen = empty($tot_ang) || $tot_ang == 0 ? 0 : $tot_real / $tot_ang * 100;

			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:12px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nm_rek . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_opr, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_btt, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_trf, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_opr, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_btt, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_trf, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . $e . '' . number_format($sisa_bb_, "2", ",", ".") . '' . $f . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($persen, "2", ",", ".") . '</td>
							</tr>';
		}

		$sql = " select sum(ang_opr) ang_opr,sum(ang_btt) ang_btt,sum(ang_mod) ang_mod,sum(ang_trf) ang_trf,sum(real_opr) real_opr,sum(real_btt) real_btt,sum(real_mod) real_mod,sum(real_trf) real_trf from(
						select kd_sub_kegiatan kode,nm_rek,sum(ang_opr) ang_opr,sum(ang_btt) ang_btt,sum(ang_mod) ang_mod,sum(ang_trf) ang_trf,sum(real_opr) real_opr,sum(real_btt) real_btt,sum(real_mod) real_mod,sum(real_trf) real_trf
						FROM [perda_lampI.3_sub_2_c]($bulan,$anggaran) where len(kd_sub_kegiatan)=1
						group by kd_sub_kegiatan , nm_rek
						)a 
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$ang_opr = $row->ang_opr;
			$ang_btt = $row->ang_btt;
			$ang_mod = $row->ang_mod;
			$ang_trf = $row->ang_trf;
			$real_opr = $row->real_opr;
			$real_btt = $row->real_btt;
			$real_mod = $row->real_mod;
			$real_trf = $row->real_trf;
			$tot_ang = $ang_opr + $ang_btt + $ang_mod + $ang_trf;
			$tot_real = $real_opr + $real_btt + $real_mod + $real_trf;

			$sisa_bb = $tot_ang - $tot_real;
			if ($sisa_bb < 0) {
				$sisa_bb_ = $sisa_bb * -1;
				$e = '(';
				$f = ')';
			} else {
				$sisa_bb_ = $sisa_bb;
				$e = '';
				$f = '';
			}

			$persen = empty($tot_ang) || $tot_ang == 0 ? 0 : $tot_real / $tot_ang * 100;

			$cRet .= '<tr>
							   <td align="center" colspan="2" valign="top" style="font-size:12px">TOTAL</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_opr, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_btt, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_trf, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_opr, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_btt, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_trf, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . $e . '' . number_format($sisa_bb_, "2", ",", ".") . '' . $f . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($persen, "2", ",", ".") . '</td>
							</tr>';
		}
		$cRet .= "</table>";

		if ($ttd == "1") {

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tglttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
			}

			if ($nip == '00000000 000000 0 000'){
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
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
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
				}
		}

		//ok


		$data['prev'] = $cRet;
		$judul = 'Perda_LampI';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->_mpdf_down_lan('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	// function cetak_perda_lampI_4_SE($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '', $ttdperda = '',$tanggal_ttd = '')
	// {
	// 	$lntahunang = $this->session->userdata('pcThang');
	// 	$lntahunangg = $lntahunang + 1;
	// 	$ttd1 = str_replace('n', ' ', $ttdperda);

	// 	$lntahunangn = $lntahunang + 1;

	// 	$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
	// 	$sqlsclient = $this->db->query($sqlsc);
	// 	foreach ($sqlsclient->result() as $rowsc) {
	// 		$kab     = $rowsc->kab_kota;
	// 		$prov     = $rowsc->provinsi;
	// 		$daerah  = $rowsc->daerah;
	// 		$thn     = $rowsc->thn_ang;
	// 	}
        
	// 	$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
	// 	$cRet = '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
	// 				<TR>
	// 					<TD width="70%" align="center" ><b>&nbsp;</TD>
	// 					<TD width="30%" align="left" >LAMPIRAN I.4 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN DAERAH KABUPATEN MELAWI <br/> 
    //                     NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangg . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
    //                     </TD>
	// 				</TR>
    //                 <TR>
	// 					<TD width="70%" align="center" ><b>&nbsp;</TD>
	// 					<TD width="30%" align="center" ><b>&nbsp;</TD>
	// 				</TR>
	// 				</TABLE><br/>';
    //     // $cRet = '<TABLE style="border-collapse:collapse;font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
    //     //                 <tr>
    //     //                     <td width="63%" align="center" >&nbsp;</td>
    //     //                     <td width="10%" valign="top" align="left" >LAMPIRAN I.4</td>
    //     //                     <td>:</td>
    //     //                     <td>RANCANGAN PERATURAN DAERAH MELAWI</td>
    //     //                 </tr>
    //     //                 <tr>
    //     //                     <td width="63%" align="center" >&nbsp;</td>
    //     //                     <td>NOMOR</td>
    //     //                     <td>:</td>
    //     //                     <td> </td>
    //     //                 </tr>
    //     //                 <tr>
    //     //                     <td width="63%" align="center" >&nbsp;</td>
    //     //                     <td>TANGGAL</td>
    //     //                     <td>:</td>
    //     //                     <td></td>
    //     //                 </tr>
    //     //                 <tr>
    //     //                     <td>&nbsp;</td>
    //     //                     <td>&nbsp;</td>
    //     //                     <td>&nbsp;</td>
    //     //                     <td>&nbsp; </td>
    //     //                 </tr>
	// 	// 			</TABLE><br/>';

	// 	$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">
	// 				<tr>
	// 				<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
    //                     &nbsp;
    //                     </td>
	// 				<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong></strong></td></tr>
    //                 <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>$kab <br>REKAPITULASI REALISASI BELANJA MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM, <br>KEGIATAN, DAN SUB KEGIATAN<br>TAHUN ANGGARAN " . $lntahunang . "</b></tr>
	// 				<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
	// 				</TABLE>";

	// 	$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"8\">
    //             <thead>
	// 			<tr>
    //                 <td rowspan = \"3\" colspan = \"6\" width=\"8%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Kode</td>
    //                 <td rowspan = \"3\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Uraian Urusan, Organisasi, Program, Kegiatan dan Sub Kegiatan</td>
    //                 <td colspan = \"8\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Kelompok Belanja</td>
    //             </tr>
				
	// 			<tr>
	// 				<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Operasi</td> 
	// 				<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Modal</td> 
	// 				<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Tidak Terduga</td> 
	// 				<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Transfer</td>
	// 		 	</tr>
	// 			 <tr>
	// 				<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
	// 				<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
	// 				<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
	// 				<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
	// 				<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
	// 				<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
	// 				<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
	// 				<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
					
	// 			</tr>
	// 			</thead>";


	// 	$sql = "SELECT kd_sub_kegiatan as kode,no_urusan,SUBSTRING (no_bid,3,2) AS no_bid,no_skpd,SUBSTRING (no_program,6,2) no_program,SUBSTRING (no_kegiatan,9,4) no_kegiatan,SUBSTRING (no_sub_kegiatan,14,2) no_sub_kegiatan,nm_rek,ang_opr,ang_btt,ang_mod,ang_trf,real_opr,real_btt,real_mod,real_trf FROM [perda_lampI.3_sub_2_c_copy1]($bulan,'$anggaran') ORDER BY kode";
		
	// 	$hasil = $this->db->query($sql);
	// 	foreach ($hasil->result() as $row) {
	// 		$kode = $row->kode;
	// 		$no_urus = $row->no_urusan;
	// 		$no_bid  = $row->no_bid;
	// 		$no_skpd = $row->no_skpd;
	// 		$no_prog = $row->no_program;
	// 		$no_keg  = $row->no_kegiatan;
	// 		$no_sub  = $row->no_sub_kegiatan;
	// 		$nm_rek = $row->nm_rek;
	// 		$ang_opr = $row->ang_opr;
	// 		$ang_btt = $row->ang_btt;
	// 		$ang_mod = $row->ang_mod;
	// 		$ang_trf = $row->ang_trf;
	// 		$real_opr = $row->real_opr;
	// 		$real_btt = $row->real_btt;
	// 		$real_mod = $row->real_mod;
	// 		$real_trf = $row->real_trf;

	// 		$cRet .= '<tr>
	// 						   <td align="center" valign="top" width="3%" style="font-size:12px">' . $no_urus . '</td> 
	// 						   <td align="center" valign="top" width="3%" style="font-size:12px">' . $no_bid . '</td> 
	// 						   <td align="center" valign="top" width="12%" style="font-size:12px">' . $no_skpd . '</td> 
	// 						   <td align="center" valign="top" width="3%" style="font-size:12px">' . $no_prog . '</td> 
	// 						   <td align="center" valign="top" width="4%" style="font-size:12px">' . $no_keg . '</td> 
	// 						   <td align="center" valign="top" width="4%" style="font-size:12px">' . $no_sub . '</td> 
	// 						   <td align="left"  valign="top" style="font-size:12px">' . $nm_rek . '</td> 
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_opr, "2", ",", ".") . '</td>
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($real_opr, "2", ",", ".") . '</td> 
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td>
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_btt, "2", ",", ".") . '</td>
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($real_btt, "2", ",", ".") . '</td> 
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_trf, "2", ",", ".") . '</td>  
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($real_trf, "2", ",", ".") . '</td> 
	// 						</tr>';
	// 	}

	// 	$sql = " select sum(ang_opr) ang_opr,sum(ang_btt) ang_btt,sum(ang_mod) ang_mod,sum(ang_trf) ang_trf,sum(real_opr) real_opr,sum(real_btt) real_btt,sum(real_mod) real_mod,sum(real_trf) real_trf from(
	// 					select kd_sub_kegiatan kode,nm_rek,sum(ang_opr) ang_opr,sum(ang_btt) ang_btt,sum(ang_mod) ang_mod,sum(ang_trf) ang_trf,sum(real_opr) real_opr,sum(real_btt) real_btt,sum(real_mod) real_mod,sum(real_trf) real_trf
	// 					FROM [perda_lampI.3_sub_2_c_copy1]($bulan,'$anggaran') where len(kd_sub_kegiatan)=1
	// 					group by kd_sub_kegiatan ,nm_rek
	// 					)a 
	// 				";
	// 	$hasil = $this->db->query($sql);
	// 	foreach ($hasil->result() as $row) {

	// 		$ang_opr = $row->ang_opr;
	// 		$ang_btt = $row->ang_btt;
	// 		$ang_mod = $row->ang_mod;
	// 		$ang_trf = $row->ang_trf;
	// 		$real_opr = $row->real_opr;
	// 		$real_btt = $row->real_btt;
	// 		$real_mod = $row->real_mod;
	// 		$real_trf = $row->real_trf;
		
	// 		$cRet .= '<tr>
	// 						   <td align="center" colspan="7" valign="top" style="font-size:12px">TOTAL</td> 
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_opr, "2", ",", ".") . '</td>
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($real_opr, "2", ",", ".") . '</td> 
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td>
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_btt, "2", ",", ".") . '</td>
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($real_btt, "2", ",", ".") . '</td> 
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_trf, "2", ",", ".") . '</td>  
	// 						   <td align="right" valign="top" style="font-size:12px">' . number_format($real_trf, "2", ",", ".") . '</td> 
	// 						</tr>';
	// 	}
	// 	$cRet .= "</table>";

	// 	if ($ttd == "1") {

	// 		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
	// 		$sqlttd = $this->db->query($sqlttd1);
	// 		foreach ($sqlttd->result() as $rowttd) {
	// 			$nip = $rowttd->nip;
	// 			$nama = $rowttd->nm;
	// 			$jabatan  = $rowttd->jab;
	// 			$pangkat  = $rowttd->pangkat;
	// 		}

	// 		if ($ttd1 != '1') {
	// 			$xx = "<u>";
	// 			$xy = "</u>";
	// 			$nipxx = $nip;
	// 			$nipx = "NIP. ";
	// 		} else {
	// 			$xx = "";
	// 			$xy = "";
	// 			$nipxx = "";
	// 			$nipx = "";
	// 		}

	// 		if ($tglttd == 1) {
	// 			$tgltd = '';
	// 		} else {
	// 			$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
	// 		}

	// 		if ($nip == '00000000 000000 0 000'){
	// 			$cRet .= '<br><br>
	// 				<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD align="center" > MELAWI , ' . $tgltd . '</TD>
	// 							</TR>
								
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD align="center" ><b>' . $jabatan . '</b></TD>
	// 							</TR>
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 							</TR>
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 							</TR>
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 							</TR>
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 							</TR>   
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 							</TR>                       
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
	// 							</TR>
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD align="center" >' . $pangkat . '</TD>
	// 							</TR>
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD align="center" > </TD>
	// 							</TR>
	// 							</TABLE><br/>';
		
	// 			} else {
	// 			$cRet .= '<br><br>
	// 				<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD align="center" > MELAWI , ' . $tgltd . '</TD>
	// 							</TR>
								
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD align="center" ><b>' . $jabatan . '</b></TD>
	// 							</TR>
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 							</TR>
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 							</TR>
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 							</TR>
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 							</TR>   
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 							</TR>                       
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
	// 							</TR>
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD align="center" >' . $pangkat . '</TD>
	// 							</TR>
	// 							<TR>
	// 								<TD width="50%" align="center" ><b>&nbsp;</TD>
	// 								<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
	// 							</TR>
	// 							</TABLE><br/>';
	// 			}
	// 	}

	// 	//ok


	// 	$data['prev'] = $cRet;
	// 	$judul = 'Perda_LampI';
	// 	switch ($ctk) {
	// 		case 0;
	// 			echo ("<title>$judul</title>");
	// 			echo $cRet;
	// 			break;
	// 		case 1;
	// 			$this->_mpdf_down_lan('', $cRet, 10, 10, 10, 'L');
	// 			break;
	// 		case 2;
	// 			header("Cache-Control: no-cache, no-store, must-revalidate");
	// 			header("Content-Type: application/vnd.ms-excel");
	// 			header("Content-Disposition: attachment; filename= $judul.xls");
	// 			$this->load->view('anggaran/rka/perkadaII', $data);
	// 			break;
	// 	}
	// }
	

	function cetak_perda_lampI_4_SE($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '', $ttdperda = '',$tanggal_ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunangg = $lntahunang + 1;
		$ttd1 = str_replace('n', ' ', $ttdperda);

		$lntahunangn = $lntahunang + 1;

		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
        
		$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		$cRet = '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="left" >LAMPIRAN I.4 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN DAERAH KABUPATEN MELAWI <br/> 
                        NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangg . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
                        </TD>
					</TR>
                    <TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="center" ><b>&nbsp;</TD>
					</TR>
					</TABLE><br/>';
        // $cRet = '<TABLE style="border-collapse:collapse;font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        //                 <tr>
        //                     <td width="63%" align="center" >&nbsp;</td>
        //                     <td width="10%" valign="top" align="left" >LAMPIRAN I.4</td>
        //                     <td>:</td>
        //                     <td>RANCANGAN PERATURAN DAERAH SANGGAU</td>
        //                 </tr>
        //                 <tr>
        //                     <td width="63%" align="center" >&nbsp;</td>
        //                     <td>NOMOR</td>
        //                     <td>:</td>
        //                     <td> </td>
        //                 </tr>
        //                 <tr>
        //                     <td width="63%" align="center" >&nbsp;</td>
        //                     <td>TANGGAL</td>
        //                     <td>:</td>
        //                     <td></td>
        //                 </tr>
        //                 <tr>
        //                     <td>&nbsp;</td>
        //                     <td>&nbsp;</td>
        //                     <td>&nbsp;</td>
        //                     <td>&nbsp; </td>
        //                 </tr>
		// 			</TABLE><br/>';

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        &nbsp;
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong></strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>$kab <br>REKAPITULASI REALISASI BELANJA MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM, <br>KEGIATAN, DAN SUB KEGIATAN<br>TAHUN ANGGARAN " . $lntahunang . "</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"3\" colspan = \"6\" width=\"8%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Kode</td>
                    <td rowspan = \"3\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Uraian Urusan, Organisasi, Program, Kegiatan dan Sub Kegiatan</td>
                    <td colspan = \"8\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Kelompok Belanja</td>
                </tr>
				
				<tr>
					<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Operasi</td> 
					<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Modal</td> 
					<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Tidak Terduga</td> 
					<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Transfer</td>
			 	</tr>
				 <tr>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
					
				</tr>
				</thead>";


		$sql = "SELECT urut, kd_sub_kegiatan kode,no_urusan,SUBSTRING (no_bid,3,2) AS no_bid,no_skpd,SUBSTRING (no_program,6,2) no_program,SUBSTRING (no_kegiatan,9,4) no_kegiatan,		SUBSTRING (no_sub_kegiatan,14,2) no_sub_kegiatan,nm_rek,ang_opr,ang_btt,ang_mod,ang_trf,real_opr,real_btt,real_mod,real_trf FROM [perda_lampI.3_sub_2_c_copy1]($bulan,'$anggaran') ORDER BY no_skpd, kd_sub_kegiatan,urut";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kode;
			$no_urus = $row->no_urusan;
			$no_bid  = $row->no_bid;
			$no_skpd = $row->no_skpd;
			$no_prog = $row->no_program;
			$no_keg  = $row->no_kegiatan;
			$no_sub  = $row->no_sub_kegiatan;
			$nm_rek = $row->nm_rek;
			$ang_opr = $row->ang_opr;
			$ang_btt = $row->ang_btt;
			$ang_mod = $row->ang_mod;
			$ang_trf = $row->ang_trf;
			$real_opr = $row->real_opr;
			$real_btt = $row->real_btt;
			$real_mod = $row->real_mod;
			$real_trf = $row->real_trf;
if($row->urut =='1'){
	$cRet .= '<tr>
	<td align="center" valign="top" width="3%" style="font-size:12px">' . $no_urus . '</td> 
	<td align="center" valign="top" width="3%" style="font-size:12px">' . $no_bid . '</td> 
	<td align="center" valign="top" width="12%" style="font-size:12px"></td> 
	<td align="center" valign="top" width="3%" style="font-size:12px">' . $no_prog . '</td> 
	<td align="center" valign="top" width="4%" style="font-size:12px">' . $no_keg . '</td> 
	<td align="center" valign="top" width="4%" style="font-size:12px">' . $no_sub . '</td> 
	<td align="left"  valign="top" style="font-size:12px">' . $nm_rek . '</td> 
	<td align="right" valign="top" style="font-size:12px">' . number_format($ang_opr, "2", ",", ".") . '</td>
	<td align="right" valign="top" style="font-size:12px">' . number_format($real_opr, "2", ",", ".") . '</td> 
	<td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td>
	<td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
	<td align="right" valign="top" style="font-size:12px">' . number_format($ang_btt, "2", ",", ".") . '</td>
	<td align="right" valign="top" style="font-size:12px">' . number_format($real_btt, "2", ",", ".") . '</td> 
	<td align="right" valign="top" style="font-size:12px">' . number_format($ang_trf, "2", ",", ".") . '</td>  
	<td align="right" valign="top" style="font-size:12px">' . number_format($real_trf, "2", ",", ".") . '</td> 
 </tr>';
}else if($row->urut =='2'){
	$cRet .= '<tr>
	<td align="center" valign="top" width="3%" style="font-size:12px">' . $no_urus . '</td> 
	<td align="center" valign="top" width="3%" style="font-size:12px">' . $no_bid . '</td> 
	<td align="center" valign="top" width="12%" style="font-size:12px"></td> 
	<td align="center" valign="top" width="3%" style="font-size:12px">' . $no_prog . '</td> 
	<td align="center" valign="top" width="4%" style="font-size:12px">' . $no_keg . '</td> 
	<td align="center" valign="top" width="4%" style="font-size:12px">' . $no_sub . '</td> 
	<td align="left"  valign="top" style="font-size:12px">' . $nm_rek . '</td> 
	<td align="right" valign="top" style="font-size:12px">' . number_format($ang_opr, "2", ",", ".") . '</td>
	<td align="right" valign="top" style="font-size:12px">' . number_format($real_opr, "2", ",", ".") . '</td> 
	<td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td>
	<td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
	<td align="right" valign="top" style="font-size:12px">' . number_format($ang_btt, "2", ",", ".") . '</td>
	<td align="right" valign="top" style="font-size:12px">' . number_format($real_btt, "2", ",", ".") . '</td> 
	<td align="right" valign="top" style="font-size:12px">' . number_format($ang_trf, "2", ",", ".") . '</td>  
	<td align="right" valign="top" style="font-size:12px">' . number_format($real_trf, "2", ",", ".") . '</td> 
 </tr>';
}else{
	$cRet .= '<tr>
	<td align="center" valign="top" width="3%" style="font-size:12px">' . $no_urus . '</td> 
	<td align="center" valign="top" width="3%" style="font-size:12px">' . $no_bid . '</td> 
	<td align="center" valign="top" width="12%" style="font-size:12px">' . $no_skpd . '</td> 
	<td align="center" valign="top" width="3%" style="font-size:12px">' . $no_prog . '</td> 
	<td align="center" valign="top" width="4%" style="font-size:12px">' . $no_keg . '</td> 
	<td align="center" valign="top" width="4%" style="font-size:12px">' . $no_sub . '</td> 
	<td align="left"  valign="top" style="font-size:12px">' . $nm_rek . '</td> 
	<td align="right" valign="top" style="font-size:12px">' . number_format($ang_opr, "2", ",", ".") . '</td>
	<td align="right" valign="top" style="font-size:12px">' . number_format($real_opr, "2", ",", ".") . '</td> 
	<td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td>
	<td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
	<td align="right" valign="top" style="font-size:12px">' . number_format($ang_btt, "2", ",", ".") . '</td>
	<td align="right" valign="top" style="font-size:12px">' . number_format($real_btt, "2", ",", ".") . '</td> 
	<td align="right" valign="top" style="font-size:12px">' . number_format($ang_trf, "2", ",", ".") . '</td>  
	<td align="right" valign="top" style="font-size:12px">' . number_format($real_trf, "2", ",", ".") . '</td> 
 </tr>';
}
			
		}

		$sql = "SELECT sum(ang_opr) ang_opr,sum(ang_btt) ang_btt,sum(ang_mod) ang_mod,sum(ang_trf) ang_trf,sum(real_opr) real_opr,sum(real_btt) real_btt,sum(real_mod) real_mod,sum(real_trf) real_trf from(
						select kd_sub_kegiatan kode,nm_rek,sum(ang_opr) ang_opr,sum(ang_btt) ang_btt,sum(ang_mod) ang_mod,sum(ang_trf) ang_trf,sum(real_opr) real_opr,sum(real_btt) real_btt,sum(real_mod) real_mod,sum(real_trf) real_trf
						FROM [perda_lampI.3_sub_2_c]($bulan,'$anggaran') where len(kd_sub_kegiatan)=1
						group by kd_sub_kegiatan , nm_rek
						)a 
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$ang_opr = $row->ang_opr;
			$ang_btt = $row->ang_btt;
			$ang_mod = $row->ang_mod;
			$ang_trf = $row->ang_trf;
			$real_opr = $row->real_opr;
			$real_btt = $row->real_btt;
			$real_mod = $row->real_mod;
			$real_trf = $row->real_trf;
		
			$cRet .= '<tr>
							   <td align="center" colspan="7" valign="top" style="font-size:12px">TOTAL</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_opr, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_opr, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_btt, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_btt, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_trf, "2", ",", ".") . '</td>  
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_trf, "2", ",", ".") . '</td> 
							</tr>';
		}
		$cRet .= "</table>";

		if ($ttd == "1") {

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUP')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tglttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
			}

			if ($nip == '00000000 000000 0 000'){
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > Melawi , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
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
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > Melawi , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
				}
		}

		//ok


		$data['prev'] = $cRet;
		$judul = 'Perda_LampI';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->_mpdf_down_lan('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	//================================================ End Lamp Perda I.4 SE 2022


	function cetak_perda_lampI_4_SE_rincian($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '', $ttdperda = '',$tanggal_ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunangg = $lntahunang + 1;
		$ttd1 = str_replace('n', ' ', $ttdperda);

		$lntahunangn = $lntahunang + 1;

		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
        
		$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		$cRet = '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="left" >LAMPIRAN I.4 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN DAERAH KABUPATEN MELAWI <br/> 
                        NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangg . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
                        </TD>
					</TR>
                    <TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="center" ><b>&nbsp;</TD>
					</TR>
					</TABLE><br/>';

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        &nbsp;
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong></strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>$kab <br>REKAPITULASI REALISASI BELANJA MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM, <br>KEGIATAN, DAN SUB KEGIATAN<br>TAHUN ANGGARAN " . $lntahunang . "</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"8\">
                <thead>
				<tr>
                    <td rowspan = \"3\" colspan = \"6\" width=\"8%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Kode</td>
                    <td rowspan = \"3\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Uraian Urusan, Organisasi, Program, Kegiatan dan Sub Kegiatan</td>
                    <td colspan = \"14\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Kelompok Belanja</td>
                </tr>
				
				<tr>
					<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Pegawai</td> 
					<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Barang dan Jasa</td> 
					<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Modal</td>
					<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Hibah</td> 
					<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Bantuan Sosial</td> 
					<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Bantuan Tidak terduga</td>
					<td align=\"center\" width=\"11%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Transfer</td>
			 	</tr>
				 <tr>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td>
				</tr>
				</thead>";
				$cRet .= '<tbody>';
				$sql = "SELECT urut,kd_sub_kegiatan as kode,no_urusan,SUBSTRING (no_bid,3,2) AS no_bid,no_skpd,SUBSTRING (no_program,6,2) no_program,SUBSTRING (no_kegiatan,9,4) no_kegiatan,SUBSTRING (no_sub_kegiatan,14,2) no_sub_kegiatan,nm_rek,
				ang_peg, ang_barjas, ang_hibah,ang_bansos,ang_modal,ang_btt,ang_trf,real_peg, real_barjas, real_hibah,
				real_bansos,real_modal,real_btt,real_trf
				FROM [perda_lampI.3_sub_2_c_rincianbelanja]($bulan,'$anggaran') ORDER BY kode";
				$hasil = $this->db->query($sql);
				$ang_peg1 =0;
				foreach($hasil->result() as $row){
					$kode = $row->kode;
					$no_urusan = $row->no_urusan;
					$no_bid = $row->no_bid;
					$no_skpd = $row->no_skpd;
					$no_program = $row->no_program;
					$no_kegiatan= $row->no_kegiatan;
					$no_sub_kegiatan = $row->no_sub_kegiatan;
					$nm_rek = $row->nm_rek;
					$ang_peg = $row->ang_peg;
					$ang_barjas = $row->ang_barjas;
					$ang_hibah = $row->ang_hibah;
					$ang_bansos = $row->ang_bansos;
					$ang_modal = $row->ang_modal;
					$ang_btt = $row->ang_btt;
					$ang_trf = $row->ang_trf;
					$real_peg = $row->real_peg;
					$real_barjas = $row->real_barjas;
					$real_hibah = $row->real_hibah;
					$real_bansos = $row->real_bansos;
					$real_modal = $row->real_modal;
					$real_btt = $row->real_btt;
					$real_trf = $row->real_trf;
				$cRet .='<tr>
							<td align="center" valign="top" width="3%" style="font-size:12px">' . $no_urusan . '</td> 
							<td align="center" valign="top" width="3%" style="font-size:12px">' . $no_bid . '</td> 
							<td align="center" valign="top" width="12%" style="font-size:12px">' . $no_skpd . '</td> 
							<td align="center" valign="top" width="3%" style="font-size:12px">' . $no_program . '</td> 
							<td align="center" valign="top" width="4%" style="font-size:12px">' . $no_kegiatan . '</td> 
							<td align="center" valign="top" width="4%" style="font-size:12px">' . $no_sub_kegiatan . '</td> 
							<td align="right" valign="top" style="font-size:12px">'.$nm_rek.'</td>
							<td align="right" valign="top" style="font-size:12px">'.number_format($ang_peg,"2", ",", ".").'</td>
							<td align="right" valign="top" style="font-size:12px">'.number_format($real_peg,"2", ",", ".").'</td>
							<td align="right" valign="top" style="font-size:12px">'.number_format($ang_barjas,"2", ",", ".").'</td>
							<td align="right" valign="top" style="font-size:12px">'.number_format($real_barjas,"2", ",", ".").'</td>
							<td align="right" valign="top" style="font-size:12px">'.number_format($ang_modal,"2", ",", ".").'</td>
							<td align="right" valign="top" style="font-size:12px">'.number_format($real_modal,"2", ",", ".").'</td>
							<td align="right" valign="top" style="font-size:12px">'.number_format($ang_hibah,"2", ",", ".").'</td>
							<td align="right" valign="top" style="font-size:12px">'.number_format($real_hibah,"2", ",", ".").'</td>
							<td align="right" valign="top" style="font-size:12px">'.number_format($ang_bansos,"2", ",", ".").'</td>
							<td align="right" valign="top" style="font-size:12px">'.number_format($real_bansos,"2", ",", ".").'</td>
							<td align="right" valign="top" style="font-size:12px">'.number_format($ang_btt,"2", ",", ".").'</td>
							<td align="right" valign="top" style="font-size:12px">'.number_format($real_btt,"2", ",", ".").'</td>
							<td align="right" valign="top" style="font-size:12px">'.number_format($ang_trf,"2", ",", ".").'</td>
							<td align="right" valign="top" style="font-size:12px">'.number_format($real_trf,"2", ",", ".").'</td>
							</tr>';	
			}	

			$sql = "SELECT
				SUM(ang_peg) as ang_peg, SUM(ang_barjas) as ang_barjas, SUM(ang_hibah) as ang_hibah,SUM(ang_bansos) as ang_bansos, SUM(ang_modal) as ang_modal
				,SUM(ang_btt) as ang_btt, SUM(ang_trf) as ang_trf,SUM(real_peg) as real_peg, SUM(real_barjas) as real_barjas, SUM(real_hibah) as real_hibah,
				SUM(real_bansos) as real_bansos,SUM(real_modal)as real_modal,SUM(real_btt)as real_btt,SUM(real_trf) as real_trf
				FROM [perda_lampI.3_sub_2_c_rincianbelanja]($bulan,'$anggaran') where len(kd_sub_kegiatan)=1";
				$hasil = $this->db->query($sql);
			foreach($hasil->result() as $row){
				$ang_peg = $row->ang_peg;
				$ang_barjas = $row->ang_barjas;
				$ang_hibah = $row->ang_hibah;
				$ang_bansos = $row->ang_bansos;
				$ang_modal = $row->ang_modal;
				$ang_btt = $row->ang_btt;
				$ang_trf = $row->ang_trf;
				$real_peg = $row->real_peg;
				$real_barjas = $row->real_barjas;
				$real_hibah = $row->real_hibah;
				$real_bansos = $row->real_bansos;
				$real_modal = $row->real_modal;
				$real_btt = $row->real_btt;
				$real_trf = $row->real_trf;

				$cRet .='<tr>
							   <td align="center" colspan="7" valign="top" style="font-size:12px">TOTAL</td> 
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_peg,"2", ",", ".").'</td>
							   <td align="right" valign="top" style="font-size:12px">'.number_format($real_peg,"2", ",", ".").'</td>
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_barjas,"2", ",", ".").'</td>
							   <td align="right" valign="top" style="font-size:12px">'.number_format($real_barjas,"2", ",", ".").'</td>
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_modal,"2", ",", ".").'</td>
							   <td align="right" valign="top" style="font-size:12px">'.number_format($real_modal,"2", ",", ".").'</td>
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_hibah,"2", ",", ".").'</td>
							   <td align="right" valign="top" style="font-size:12px">'.number_format($real_hibah,"2", ",", ".").'</td>
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_bansos,"2", ",", ".").'</td>
							   <td align="right" valign="top" style="font-size:12px">'.number_format($real_bansos,"2", ",", ".").'</td>
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_btt,"2", ",", ".").'</td>
							   <td align="right" valign="top" style="font-size:12px">'.number_format($real_btt,"2", ",", ".").'</td>
							   <td align="right" valign="top" style="font-size:12px">'.number_format($ang_trf,"2", ",", ".").'</td>
							   <td align="right" valign="top" style="font-size:12px">'.number_format($real_trf,"2", ",", ".").'</td>
							</tr>';
			}
			
				
				
			
			$cRet .= '</tbody>';
		$cRet .= "</table>";

		if ($ttd == "1") {

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tglttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
			}

			$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > Melawi , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
		}

		//ok


		$data['prev'] = $cRet;
		$judul = 'Perda_Lamp I.3';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->_mpdf_down_lan('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}
	//================================================ End Lamp Perda I.4 SE 2022


	function cetak_perda_lampI_4_SE_ringkas($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '', $ttdperda = '',$tanggal_ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunangg = $lntahunang + 1;
		$ttd1 = str_replace('n', ' ', $ttdperda);

		$lntahunangn = $lntahunang + 1;

		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
        
		$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		$cRet = '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="left" >LAMPIRAN I.4 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN DAERAH KABUPATEN MELAWI <br/> 
                        NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangg . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
                        </TD>
					</TR>
                    <TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="center" ><b>&nbsp;</TD>
					</TR>
					</TABLE><br/>';
        // $cRet = '<TABLE style="border-collapse:collapse;font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
        //                 <tr>
        //                     <td width="63%" align="center" >&nbsp;</td>
        //                     <td width="10%" valign="top" align="left" >LAMPIRAN I.4</td>
        //                     <td>:</td>
        //                     <td>RANCANGAN PERATURAN DAERAH MELAWI</td>
        //                 </tr>
        //                 <tr>
        //                     <td width="63%" align="center" >&nbsp;</td>
        //                     <td>NOMOR</td>
        //                     <td>:</td>
        //                     <td> </td>
        //                 </tr>
        //                 <tr>
        //                     <td width="63%" align="center" >&nbsp;</td>
        //                     <td>TANGGAL</td>
        //                     <td>:</td>
        //                     <td></td>
        //                 </tr>
        //                 <tr>
        //                     <td>&nbsp;</td>
        //                     <td>&nbsp;</td>
        //                     <td>&nbsp;</td>
        //                     <td>&nbsp; </td>
        //                 </tr>
		// 			</TABLE><br/>';

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        &nbsp;
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong></strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>$kab <br>REKAPITULASI REALISASI BELANJA MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM, <br>KEGIATAN, DAN SUB KEGIATAN<br>TAHUN ANGGARAN " . $lntahunang . "</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" colspan = \"6\" width=\"8%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Kode</td>
                    <td rowspan = \"2\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Uraian Urusan, Organisasi, Program, Kegiatan dan Sub Kegiatan</td>
                    <td colspan = \"2\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Jumlah (Rp)</td>
					<td colspan = \"2\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Bertambah / Berkurang</td>
                </tr>
				
				<tr>
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Anggaran</td> 
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">Realisasi</td> 
					<td align=\"center\" width=\"11%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">(Rp)</td> 
					<td align=\"center\" width=\"2%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">%</td> 
			 	</tr>
				</thead>";


		$sql = "SELECT kd_sub_kegiatan kode,no_urusan,SUBSTRING (no_bid,3,2) AS no_bid,no_skpd,SUBSTRING (no_program,6,2) no_program,SUBSTRING (no_kegiatan,9,4) no_kegiatan,	SUBSTRING (no_sub_kegiatan,14,2) no_sub_kegiatan,nm_rek,ang_opr,ang_btt,ang_mod,ang_trf,real_opr,real_btt,real_mod,real_trf FROM [perda_lampI.3_sub_2_c_copy1]($bulan,'$anggaran') ORDER BY kode";
		
		$jmang 	= 0;
		$jmreal = 0;
		$totang = 0;

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kode;
			$no_urus = $row->no_urusan;
			$no_bid  = $row->no_bid;
			$no_skpd = $row->no_skpd;
			$no_prog = $row->no_program;
			$no_keg  = $row->no_kegiatan;
			$no_sub  = $row->no_sub_kegiatan;
			$nm_rek = $row->nm_rek;
			$ang_opr = $row->ang_opr;
			$ang_btt = $row->ang_btt;
			$ang_mod = $row->ang_mod;
			$ang_trf = $row->ang_trf;
			$real_opr = $row->real_opr;
			$real_btt = $row->real_btt;
			$real_mod = $row->real_mod;
			$real_trf = $row->real_trf;

			$jmang = $ang_opr + $ang_btt + $ang_mod + $ang_trf;
			$jmreal = $real_opr + $real_btt + $real_mod + $real_trf;

			$totang = $jmang - $jmreal;
			if ($totang < 0){
				$totang1 = $totang * -1;
				$ab = '(';
				$cd = ')';
			} else {
				$totang1 = $totang;
				$ab = '';
				$cd = '';
			}
			
			$sisa = $jmang - $totang;
			if( ($jmang == 0) || ($jmang == '') ){
				$persen = 0;
			} else {
				$persen = $jmreal / $jmang * 100;
			}

			$cRet .= '<tr>
							   <td align="center" valign="top" width="3%" style="font-size:12px">' . $no_urus . '</td> 
							   <td align="center" valign="top" width="3%" style="font-size:12px">' . $no_bid . '</td> 
							   <td align="center" valign="top" width="12%" style="font-size:12px">' . $no_skpd . '</td> 
							   <td align="center" valign="top" width="3%" style="font-size:12px">' . $no_prog . '</td> 
							   <td align="center" valign="top" width="4%" style="font-size:12px">' . $no_keg . '</td> 
							   <td align="center" valign="top" width="4%" style="font-size:12px">' . $no_sub . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nm_rek . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jmang, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jmreal, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">'.$ab.'' . number_format($totang1, "2", ",", ".") . ''.$cd.'</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($persen, "2", ",", ".") . '</td>
							</tr>';
		}

		$sql = " select sum(ang_opr) ang_opr,sum(ang_btt) ang_btt,sum(ang_mod) ang_mod,sum(ang_trf) ang_trf,sum(real_opr) real_opr,sum(real_btt) real_btt,sum(real_mod) real_mod,sum(real_trf) real_trf from(
						select kd_sub_kegiatan kode,nm_rek,sum(ang_opr) ang_opr,sum(ang_btt) ang_btt,sum(ang_mod) ang_mod,sum(ang_trf) ang_trf,sum(real_opr) real_opr,sum(real_btt) real_btt,sum(real_mod) real_mod,sum(real_trf) real_trf
						FROM [perda_lampI.3_sub_2_c_copy1]($bulan,'$anggaran') where len(kd_sub_kegiatan)=1
						group by kd_sub_kegiatan , nm_rek
						)a 
					";
		$jmang 	= 0;
		$jmreal = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$ang_opr = $row->ang_opr;
			$ang_btt = $row->ang_btt;
			$ang_mod = $row->ang_mod;
			$ang_trf = $row->ang_trf;
			$real_opr = $row->real_opr;
			$real_btt = $row->real_btt;
			$real_mod = $row->real_mod;
			$real_trf = $row->real_trf;
		
			$jmang = $ang_opr + $ang_btt + $ang_mod + $ang_trf;
			$jmreal = $real_opr + $real_btt + $real_mod + $real_trf;

			$totangsel = $jmang - $jmreal;
			if ($totangsel < 0){
				$totangsel1 = $totangsel * -1;
				$ab = '(';
				$cd = ')';
			} else {
				$totangsel1 = $totangsel;
				$ab = '';
				$cd = '';
			}
			
			$sisa = $jmang - $totangsel;
			if( ($jmang == 0) || ($jmang == '') ){
				$persen = 0;
			} else {
				$persen = $jmreal / $jmang * 100;
			}

			$cRet .= '<tr>
							   <td align="center" colspan="7" valign="top" style="font-size:12px">TOTAL</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jmang, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($jmreal, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($totangsel1, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($persen, "2", ",", ".") . '</td>
							</tr>';
		}
		$cRet .= "</table>";

		if ($ttd == "1") {

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tglttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
			}

			if ($nip == '00000000 000000 0 000'){
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
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
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
				}
		}

		//ok


		$data['prev'] = $cRet;
		$judul = 'Perda_LampI';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->_mpdf_down_lan('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}
	//================================================ Lamp Perda I.5

	function perdaI_5()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. I.5';
		$this->template->set('title', 'CETAK PERDA LAMP. I.5');
		$this->template->load('template', 'perdase/cetak_perda_lampI_5', $data);
	}

	function cetak_perda_lampI_5_SE_2022($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '')
	{
		// echo ($anggaran);
		// return;
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunangg = $lntahunang + 1;
		$lntahunangn = $lntahunang + 1;

		if ($ttd == '-') {
			$nama_ttd = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("n", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat,nip FROM ms_ttd where nip='$ttd' AND (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama_ttd = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
			}
		}


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		$tanggal = $tglttd == '-' ? '' : '' . $this->tukd_model->tanggal_format_indonesia($tglttd);

		$sqlnogub = "SELECT judul, no_konfig, isi FROM trkonfig_anggaran";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$ket_lampiran      = strtoupper("Lampiran I.5");
			$ket_perda         = strtoupper($rowsc->judul);
			$ket_perda_no      = strtoupper($rowsc->no_konfig);
			$ket_perda_tentang = strtoupper($rowsc->isi);
		}
		
		$cRet = '<TABLE style="border-collapse:collapse;font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="63%" align="center" ><b>&nbsp;</TD>
						<TD width="37%" valign="top" align="left" >' . $ket_lampiran . ' <br> PERATURAN DAERAH MELAWI<br>
						NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN '.$lntahunangg.'<br>
                        TENTANG PERTANGGUNGJAWABAN PELAKSANAAN ANGGARAN PENDAPATAN DAN BELANJA DAERAH KABUPATEN MELAWI TAHUN ANGGARAN '.$lntahunang.'<br>
						</TD>
					</TR>
					<TR>
					<TD width="63%" align="center" ><b>&nbsp;</TD>
					<TD width="37%" align="center" ><b>&nbsp;</TD>
					</TR>
					</TABLE><br/>';
        
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        &nbsp;
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>$kab </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REKAPITULASI BELANJA DAERAH UNTUK KESELARASAN DAN KETERPADUAN <br> URUSAN PEMERINTAH DAERAH DAN FUNGSI DALAM KERANGKA PENGELOLAAN KEUANGAN NEGARA</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" colspan = \"4\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Kode</b></td>
                    <td rowspan = \"2\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Uraian</b></td>
					<td colspan = \"8\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Kelompok Belanja</b></td>
				</tr>
				<tr>
					<td align=\"center\" width=\"7%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Operasi</b></td> 
					<td align=\"center\" width=\"7%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Modal</b></td> 
					<td align=\"center\" width=\"7%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Tidak Terduga</b></td> 
					<td align=\"center\" width=\"7%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Transfer</b></td>
			 	</tr>
				
				</thead>";


		$sql = "SELECT kode,LEFT (kode,1) AS A,SUBSTRING (kode,2,2) AS B,SUBSTRING (kode,5,1) AS C,SUBSTRING (kode,7,2) AS D,nama,anggaran_opr,anggaran_mod,anggaran_btt,anggaran_trf,real_opr,real_mod,real_btt,real_trf FROM (
			SELECT a.kode,a.nama,ISNULL(anggaran_opr,0) AS anggaran_opr,ISNULL(anggaran_mod,0) AS anggaran_mod,ISNULL(anggaran_btt,0) AS anggaran_btt,ISNULL(anggaran_trf,0) AS anggaran_trf,ISNULL(real_opr,0) AS real_opr,ISNULL(real_mod,0) AS real_mod,ISNULL(real_btt,0) AS real_btt,ISNULL(real_trf,0) AS real_trf FROM (
			SELECT RTRIM(a.kd_fungsi)+'.'+a.kd_urusan AS kode,a.nm_fungsi AS nama,SUM (ISNULL(anggaran_opr,0)) AS anggaran_opr,SUM (ISNULL(anggaran_mod,0)) AS anggaran_mod,SUM (ISNULL(anggaran_btt,0)) AS anggaran_btt,SUM (ISNULL(anggaran_trf,0)) AS anggaran_trf FROM ms_sub_fungsi a LEFT JOIN (
			SELECT LEFT (kd_sub_kegiatan,4) kd_urusan,LEFT (a.kd_rek6,4) AS kd_rek6,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5106') THEN a.nilai ELSE 0 END) AS anggaran_opr,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5201','5202','5203','5204','5205','5206') THEN a.nilai ELSE 0 END) AS anggaran_mod,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5301') THEN a.nilai ELSE 0 END) AS anggaran_btt,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5401','5402') THEN a.nilai ELSE 0 END) AS anggaran_trf FROM trdrka a WHERE LEFT (a.kd_rek6,1) IN ('5') AND a.jns_ang='$anggaran' GROUP BY LEFT (kd_sub_kegiatan,4),LEFT (a.kd_rek6,4)) b ON a.kd_urusan=b.kd_urusan GROUP BY a.kd_fungsi,a.kd_urusan,a.nm_fungsi) a LEFT JOIN (
			SELECT RTRIM(a.kd_fungsi)+'.'+a.kd_urusan AS kode,a.nm_fungsi AS nama,SUM (ISNULL(real_opr,0)) AS real_opr,SUM (ISNULL(real_mod,0)) AS real_mod,SUM (ISNULL(real_btt,0)) AS real_btt,SUM (ISNULL(real_trf,0)) AS real_trf FROM ms_sub_fungsi a LEFT JOIN (
			SELECT LEFT (kd_sub_kegiatan,4) kd_urusan,LEFT (a.kd_rek6,4) AS kd_rek6,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5106') THEN (debet-kredit) ELSE 0 END) AS real_opr,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5201','5202','5203','5204','5205','5206') THEN (debet-kredit) ELSE 0 END) AS real_mod,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5301') THEN (debet-kredit) ELSE 0 END) AS real_btt,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5401','5402') THEN (debet-kredit) ELSE 0 END) AS real_trf FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE LEFT (a.map_real,1) IN ('5') AND MONTH (tgl_voucher)<='$bulan' AND YEAR (tgl_voucher)='$lntahunang' GROUP BY LEFT (kd_sub_kegiatan,4),LEFT (a.kd_rek6,4)) b ON a.kd_urusan=b.kd_urusan GROUP BY a.kd_fungsi,a.kd_urusan,a.nm_fungsi) b ON a.kode=b.kode UNION ALL
			SELECT a.kode AS kode,a.nama,ISNULL(anggaran_opr,0) AS anggaran_opr,ISNULL(anggaran_mod,0) AS anggaran_mod,ISNULL(anggaran_btt,0) AS anggaran_btt,ISNULL(anggaran_trf,0) AS anggaran_trf,ISNULL(real_opr,0) AS real_opr,ISNULL(real_mod,0) AS real_mod,ISNULL(real_btt,0) AS real_btt,ISNULL(real_trf,0) AS real_trf FROM (
			SELECT a.kd_fungsi AS kode,a.nm_fungsi AS nama,SUM (ISNULL(anggaran_opr,0)) AS anggaran_opr,SUM (ISNULL(anggaran_mod,0)) AS anggaran_mod,SUM (ISNULL(anggaran_btt,0)) AS anggaran_btt,SUM (ISNULL(anggaran_trf,0)) AS anggaran_trf FROM ms_fungsi a LEFT JOIN (
			SELECT RTRIM(a.kd_fungsi) AS kode,b.kd_rek6 ,SUM (ISNULL(anggaran_opr,0)) AS anggaran_opr,SUM (ISNULL(anggaran_mod,0)) AS anggaran_mod,SUM (ISNULL(anggaran_btt,0)) AS anggaran_btt,SUM (ISNULL(anggaran_trf,0)) AS anggaran_trf FROM ms_sub_fungsi a LEFT JOIN (
			SELECT LEFT (kd_sub_kegiatan,4) kd_urusan,LEFT (a.kd_rek6,4) AS kd_rek6,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5106') THEN a.nilai ELSE 0 END) AS anggaran_opr,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5201','5202','5203','5204','5205','5206') THEN a.nilai ELSE 0 END) AS anggaran_mod,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5301') THEN a.nilai ELSE 0 END) AS anggaran_btt,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5401','5402') THEN a.nilai ELSE 0 END) AS anggaran_trf FROM trdrka a WHERE LEFT (a.kd_rek6,1) IN ('5') AND a.jns_ang='$anggaran' GROUP BY LEFT (kd_sub_kegiatan,4),LEFT (a.kd_rek6,4)) b ON a.kd_urusan=b.kd_urusan GROUP BY a.kd_fungsi,b.kd_rek6) b ON a.kd_fungsi=LEFT (b.kode,1) GROUP BY a.kd_fungsi,nm_fungsi) a LEFT JOIN (
			SELECT a.kd_fungsi AS kode,a.nm_fungsi AS nama,SUM (ISNULL(real_opr,0)) AS real_opr,SUM (ISNULL(real_mod,0)) AS real_mod,SUM (ISNULL(real_btt,0)) AS real_btt,SUM (ISNULL(real_trf,0)) AS real_trf FROM ms_fungsi a LEFT JOIN (
			SELECT RTRIM(a.kd_fungsi) AS kode,b.kd_rek6 ,SUM (ISNULL(real_opr,0)) AS real_opr,SUM (ISNULL(real_mod,0)) AS real_mod,SUM (ISNULL(real_btt,0)) AS real_btt,SUM (ISNULL(real_trf,0)) AS real_trf FROM ms_sub_fungsi a LEFT JOIN (
			SELECT LEFT (kd_sub_kegiatan,4) kd_urusan,LEFT (a.kd_rek6,4) AS kd_rek6,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5106') THEN (debet-kredit) ELSE 0 END) AS real_opr,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5201','5202','5203','5204','5205','5206') THEN (debet-kredit) ELSE 0 END) AS real_mod,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5301') THEN (debet-kredit) ELSE 0 END) AS real_btt,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5401','5402') THEN (debet-kredit) ELSE 0 END) AS real_trf FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE LEFT (a.map_real,1) IN ('5') AND MONTH (tgl_voucher)<='$bulan' AND YEAR (tgl_voucher)='$lntahunang' GROUP BY LEFT (kd_sub_kegiatan,4),LEFT (a.kd_rek6,4)) b ON a.kd_urusan=b.kd_urusan GROUP BY a.kd_fungsi,b.kd_rek6) b ON a.kd_fungsi=LEFT (b.kode,1) GROUP BY a.kd_fungsi,nm_fungsi) b ON a.kode=b.kode) y ORDER BY kode";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kode;
			$a 		= $row->A;
			$b		= $row->B;
			$c 		= $row->C;
			$d		= $row->D;
			$nm_rek = $row->nama;
			$ang_opr = $row->anggaran_opr;
			$ang_mod = $row->anggaran_mod;
			$ang_btt = $row->anggaran_btt;
			$ang_trf = $row->anggaran_trf;
			$real_opr = $row->real_opr;
			$real_mod = $row->real_mod;
			$real_btt = $row->real_btt;
			$real_trf = $row->real_trf;


			$leng = strlen($kode);
			switch ($leng) {
				case 1:
					$cRet .= '<tr>
							   <td align="center" valign="top" width="3%" style="font-size:12px">' . $a . '</td> 
							   <td align="center" valign="top" width="3%" style="font-size:12px"></td> 
							   <td align="center" valign="top" width="3%" style="font-size:12px"></td> 
							   <td align="center" valign="top" width="3%" style="font-size:12px"></td> 
							   <td align="left"  valign="top" width="10%" style="font-size:12px"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($ang_opr, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($real_opr, "2", ",", ".") . '</b> </td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($ang_mod, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($real_mod, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($ang_btt, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($real_btt, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($ang_trf, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($real_trf, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				default;
					$cRet .= '<tr>
							   <td align="center" valign="top" style="font-size:12px">' . $a . '</td> 
							   <td align="center" valign="top" style="font-size:12px">' . $b . '</td> 
							   <td align="center" valign="top" style="font-size:12px">' . $c . '</td> 
							   <td align="center" valign="top" style="font-size:12px">' . $d . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nm_rek . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_opr, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_opr, "2", ",", ".") . ' </td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_btt, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_btt, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_trf, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_trf, "2", ",", ".") . '</td> 
							</tr>';
					break;
			}
		}

		$sql2 = "SELECT SUM (anggaran_opr) AS janggaran_opr,SUM (anggaran_mod) AS janggaran_mod,SUM (anggaran_btt) AS janggaran_btt,SUM (anggaran_trf) AS janggaran_trf,SUM (real_opr) AS jreal_opr,SUM (real_mod) AS jreal_mod,SUM (real_trf) AS jreal_trf,SUM (real_btt) AS jreal_btt FROM (
			SELECT SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5106') THEN a.nilai ELSE 0 END) AS anggaran_opr,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5201','5202','5203','5204','5205','5206') THEN a.nilai ELSE 0 END) AS anggaran_mod,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5301') THEN a.nilai ELSE 0 END) AS anggaran_btt,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5401','5402') THEN a.nilai ELSE 0 END) AS anggaran_trf,0 AS real_opr,0 AS real_mod,0 AS real_btt,0 AS real_trf FROM trdrka a WHERE LEFT (a.kd_rek6,1) IN ('5') AND a.jns_ang='$anggaran' UNION ALL
			SELECT 0 AS anggaran_opr,0 AS anggaran_mod,0 AS anggaran_btt,0 AS anggaran_trf,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5106') THEN (debet-kredit) ELSE 0 END) AS real_opr,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5201','5202','5203','5204','5205','5206') THEN (debet-kredit) ELSE 0 END) AS real_mod,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5301') THEN (debet-kredit) ELSE 0 END) AS real_btt,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5401','5402') THEN (debet-kredit) ELSE 0 END) AS real_trf FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE LEFT (a.map_real,1) IN ('5') AND MONTH (tgl_voucher)<='$bulan' AND YEAR (tgl_voucher)='$lntahunang') oke";


		$hasil2 = $this->db->query($sql2);	
		foreach ($hasil2->result() as $row2) {

		$janggaran_opr = $row2->janggaran_opr;
		$janggaran_mod = $row2->janggaran_mod;
		$janggaran_btt = $row2->janggaran_btt;
		$janggaran_trf = $row2->janggaran_trf;
		$jreal_opr = $row2->jreal_opr;
		$jreal_mod = $row2->jreal_mod;
		$jreal_trf = $row2->jreal_trf;
		$jreal_btt = $row2->jreal_btt;
	

		$cRet .= '<tr>
		<td align="center" colspan ="5" valign="top" width="10%" style="font-size:12px"><b>TOTAL</b></td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($janggaran_opr, "2", ",", ".") . '</b></td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($jreal_opr, "2", ",", ".") . '</b> </td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($janggaran_mod, "2", ",", ".") . '</b></td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($jreal_mod, "2", ",", ".") . '</b></td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($janggaran_btt, "2", ",", ".") . '</b></td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($jreal_btt, "2", ",", ".") . '</b></td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($janggaran_trf, "2", ",", ".") . '</b></td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($jreal_trf, "2", ",", ".") . '</b></td> 
		</tr>';
		}


		$cRet .= "</table>";

		$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Melawi , ' . $tanggal . '</TD>
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
								<TD align="center" ><b>' . $nama_ttd . '</b></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center">NIP. '.$nip.'</TD>
							</TR>
							</TABLE><br/>';
		$data['prev'] = $cRet;
		$judul = 'Perda_Lamp_I.5';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
			$this->support->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_perda_lampI_5_SE_BU_2022($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunangg = $lntahunang + 1;
		$lntahunangn = $lntahunang + 1;

		if ($ttd == '-') {
			$nama_ttd = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("n", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat,nip FROM ms_ttd where nip='$ttd' AND (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama_ttd = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
			}
		}


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		$tanggal = $tglttd == '-' ? '' : '' . $this->tukd_model->tanggal_format_indonesia($tglttd);

		$sqlnogub = "SELECT judul, no_konfig, isi FROM trkonfig_anggaran";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$ket_lampiran      = strtoupper("Lampiran I.5");
			$ket_perda         = strtoupper($rowsc->judul);
			$ket_perda_no      = strtoupper($rowsc->no_konfig);
			$ket_perda_tentang = strtoupper($rowsc->isi);
		}
		
		$cRet = '<TABLE style="border-collapse:collapse;font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="63%" align="center" ><b>&nbsp;</TD>
						<TD width="37%" valign="top" align="left" >' . $ket_lampiran . ' <br> PERATURAN DAERAH MELAWI<br>
						NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN '.$lntahunangg.'<br>
                        TENTANG PERTANGGUNGJAWABAN PELAKSANAAN ANGGARAN PENDAPATAN DAN BELANJA DAERAH KABUPATEN MELAWI TAHUN ANGGARAN '.$lntahunang.'<br>
						</TD>
					</TR>
					<TR>
					<TD width="63%" align="center" ><b>&nbsp;</TD>
					<TD width="37%" align="center" ><b>&nbsp;</TD>
					</TR>
					</TABLE><br/>';
        

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        &nbsp;
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>$kab </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REKAPITULASI BELANJA DAERAH UNTUK KESELARASAN DAN KETERPADUAN <br> URUSAN PEMERINTAH DAERAH DAN FUNGSI DALAM KERANGKA PENGELOLAAN KEUANGAN NEGARA</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" colspan = \"4\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Kode</b></td>
                    <td rowspan = \"2\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Uraian</b></td>
					<td colspan = \"8\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Kelompok Belanja</b></td>
				</tr>
				<tr>
					<td align=\"center\" width=\"7%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Operasi</b></td> 
					<td align=\"center\" width=\"7%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Modal</b></td> 
					<td align=\"center\" width=\"7%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Tidak Terduga</b></td> 
					<td align=\"center\" width=\"7%\" colspan=\"2\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\"><b>Transfer</b></td>
			 	</tr>
				
				</thead>";


		$sql = "SELECT kode,LEFT (kode,1) AS A,SUBSTRING (kode,2,2) AS B,SUBSTRING (kode,5,1) AS C,SUBSTRING (kode,7,2) AS D,nama,anggaran_opr,anggaran_mod,anggaran_btt,anggaran_trf,real_opr,real_mod,real_btt,real_trf FROM ( 
			SELECT a.kode,a.nama,ISNULL(anggaran_opr,0) AS anggaran_opr,ISNULL(anggaran_mod,0) AS anggaran_mod,ISNULL(anggaran_btt,0) AS anggaran_btt,ISNULL(anggaran_trf,0) AS anggaran_trf,ISNULL(real_opr,0) AS real_opr,ISNULL(real_mod,0) AS real_mod,ISNULL(real_btt,0) AS real_btt,ISNULL(real_trf,0) AS real_trf FROM ( 
			
			SELECT RTRIM(a.kd_fungsi)+'.'+a.kd_urusan AS kode,b.nm_bidang_urusan AS nama,SUM (ISNULL(anggaran_opr,0)) AS anggaran_opr,SUM (ISNULL(anggaran_mod,0)) AS anggaran_mod,SUM (ISNULL(anggaran_btt,0)) AS anggaran_btt,SUM (ISNULL(anggaran_trf,0)) AS anggaran_trf FROM ms_sub_fungsi a INNER JOIN ms_bidang_urusan b on b.kd_bidang_urusan = a.kd_urusan LEFT JOIN 
			
			( SELECT LEFT (kd_sub_kegiatan,4) kd_urusan,LEFT (a.kd_rek6,4) AS kd_rek6,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5106') THEN a.nilai ELSE 0 END) AS anggaran_opr,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5201','5202','5203','5204','5205','5206') THEN a.nilai ELSE 0 END) AS anggaran_mod,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5301') THEN a.nilai ELSE 0 END) AS anggaran_btt,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5401','5402') THEN a.nilai ELSE 0 END) AS anggaran_trf FROM trdrka a WHERE LEFT (a.kd_rek6,1) IN ('5') AND a.jns_ang='$anggaran' GROUP BY LEFT (kd_sub_kegiatan,4),LEFT (a.kd_rek6,4)
			) c ON b.kd_bidang_urusan=c.kd_urusan GROUP BY a.kd_fungsi,a.kd_urusan,b.nm_bidang_urusan) 
			a LEFT JOIN 
			( SELECT RTRIM(b.kd_fungsi)+'.'+b.kd_urusan AS kode,a.nm_bidang_urusan AS nama,SUM (ISNULL(real_opr,0)) AS real_opr,SUM (ISNULL(real_mod,0)) AS real_mod,SUM (ISNULL(real_btt,0)) AS real_btt,SUM (ISNULL(real_trf,0)) AS real_trf FROM ms_bidang_urusan a INNER JOIN ms_sub_fungsi b ON a.kd_bidang_urusan =b.kd_urusan LEFT JOIN (
			SELECT LEFT (kd_sub_kegiatan,4) kd_urusan,LEFT (a.kd_rek6,4) AS kd_rek6,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5106') THEN (debet-kredit) ELSE 0 END) AS real_opr,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5201','5202','5203','5204','5205','5206') THEN (debet-kredit) ELSE 0 END) AS real_mod,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5301') THEN (debet-kredit) ELSE 0 END) AS real_btt,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5401','5402') THEN (debet-kredit) ELSE 0 END) AS real_trf FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE LEFT (a.map_real,1) IN ('5') AND MONTH (tgl_voucher)<='12' AND YEAR (tgl_voucher)='2022' GROUP BY LEFT (kd_sub_kegiatan,4),LEFT (a.kd_rek6,4)) c ON b.kd_urusan=c.kd_urusan GROUP BY b.kd_fungsi,b.kd_urusan,a.nm_bidang_urusan) b ON a.kode=b.kode 
			UNION ALL
			SELECT a.kode AS kode,a.nama,ISNULL(anggaran_opr,0) AS anggaran_opr,ISNULL(anggaran_mod,0) AS anggaran_mod,ISNULL(anggaran_btt,0) AS anggaran_btt,ISNULL(anggaran_trf,0) AS anggaran_trf,ISNULL(real_opr,0) AS real_opr,ISNULL(real_mod,0) AS real_mod,ISNULL(real_btt,0) AS real_btt,ISNULL(real_trf,0) AS real_trf FROM ( SELECT a.kd_fungsi AS kode,a.nm_fungsi AS nama,SUM (ISNULL(anggaran_opr,0)) AS anggaran_opr,SUM (ISNULL(anggaran_mod,0)) AS anggaran_mod,SUM (ISNULL(anggaran_btt,0)) AS anggaran_btt,SUM (ISNULL(anggaran_trf,0)) AS anggaran_trf FROM ms_fungsi a LEFT JOIN ( SELECT RTRIM(a.kd_fungsi) AS kode,b.kd_rek6 ,SUM (ISNULL(anggaran_opr,0)) AS anggaran_opr,SUM (ISNULL(anggaran_mod,0)) AS anggaran_mod,SUM (ISNULL(anggaran_btt,0)) AS anggaran_btt,SUM (ISNULL(anggaran_trf,0)) AS anggaran_trf FROM ms_sub_fungsi a LEFT JOIN ( SELECT LEFT (kd_sub_kegiatan,4) kd_urusan,LEFT (a.kd_rek6,4) AS kd_rek6,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5106') THEN a.nilai ELSE 0 END) AS anggaran_opr,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5201','5202','5203','5204','5205','5206') THEN a.nilai ELSE 0 END) AS anggaran_mod,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5301') THEN a.nilai ELSE 0 END) AS anggaran_btt,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5401','5402') THEN a.nilai ELSE 0 END) AS anggaran_trf FROM trdrka a WHERE LEFT (a.kd_rek6,1) IN ('5') AND a.jns_ang='$anggaran' GROUP BY LEFT (kd_sub_kegiatan,4),LEFT (a.kd_rek6,4)) b ON a.kd_urusan=b.kd_urusan GROUP BY a.kd_fungsi,b.kd_rek6) b ON a.kd_fungsi=LEFT (b.kode,1) GROUP BY a.kd_fungsi,nm_fungsi) a LEFT JOIN (  SELECT a.kd_fungsi AS kode,a.nm_fungsi AS nama,SUM (ISNULL(real_opr,0)) AS real_opr,SUM (ISNULL(real_mod,0)) AS real_mod,SUM (ISNULL(real_btt,0)) AS real_btt,SUM (ISNULL(real_trf,0)) AS real_trf FROM ms_fungsi a LEFT JOIN ( SELECT RTRIM(a.kd_fungsi) AS kode,b.kd_rek6 ,SUM (ISNULL(real_opr,0)) AS real_opr,SUM (ISNULL(real_mod,0)) AS real_mod,SUM (ISNULL(real_btt,0)) AS real_btt,SUM (ISNULL(real_trf,0)) AS real_trf FROM ms_sub_fungsi a LEFT JOIN ( SELECT LEFT (kd_sub_kegiatan,4) kd_urusan,LEFT (a.kd_rek6,4) AS kd_rek6,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5106') THEN (debet-kredit) ELSE 0 END) AS real_opr,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5201','5202','5203','5204','5205','5206') THEN (debet-kredit) ELSE 0 END) AS real_mod,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5301') THEN (debet-kredit) ELSE 0 END) AS real_btt,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5401','5402') THEN (debet-kredit) ELSE 0 END) AS real_trf FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE LEFT (a.map_real,1) IN ('5') AND MONTH (tgl_voucher)<='12' AND YEAR (tgl_voucher)='2022' GROUP BY LEFT (kd_sub_kegiatan,4),LEFT (a.kd_rek6,4)) b ON a.kd_urusan=b.kd_urusan GROUP BY a.kd_fungsi,b.kd_rek6) b ON a.kd_fungsi=LEFT (b.kode,1) GROUP BY a.kd_fungsi,nm_fungsi) b ON a.kode=b.kode) y ORDER BY kode";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kode;
			$a 		= $row->A;
			$b		= $row->B;
			$c 		= $row->C;
			$d		= $row->D;
			$nm_rek = $row->nama;
			$ang_opr = $row->anggaran_opr;
			$ang_mod = $row->anggaran_mod;
			$ang_btt = $row->anggaran_btt;
			$ang_trf = $row->anggaran_trf;
			$real_opr = $row->real_opr;
			$real_mod = $row->real_mod;
			$real_btt = $row->real_btt;
			$real_trf = $row->real_trf;


			$leng = strlen($kode);
			switch ($leng) {
				case 1:
					$cRet .= '<tr>
							   <td align="center" valign="top" width="3%" style="font-size:12px">' . $a . '</td> 
							   <td align="center" valign="top" width="3%" style="font-size:12px"></td> 
							   <td align="center" valign="top" width="3%" style="font-size:12px"></td> 
							   <td align="center" valign="top" width="3%" style="font-size:12px"></td> 
							   <td align="left"  valign="top" width="10%" style="font-size:12px"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($ang_opr, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($real_opr, "2", ",", ".") . '</b> </td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($ang_mod, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($real_mod, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($ang_btt, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($real_btt, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($ang_trf, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($real_trf, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				default;
					$cRet .= '<tr>
							   <td align="center" valign="top" style="font-size:12px">' . $a . '</td> 
							   <td align="center" valign="top" style="font-size:12px">' . $b . '</td> 
							   <td align="center" valign="top" style="font-size:12px">' . $c . '</td> 
							   <td align="center" valign="top" style="font-size:12px">' . $d . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nm_rek . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_opr, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_opr, "2", ",", ".") . ' </td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_btt, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_btt, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_trf, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_trf, "2", ",", ".") . '</td> 
							</tr>';
					break;
			}
		}

		$sql2 = "SELECT SUM (anggaran_opr) AS janggaran_opr,SUM (anggaran_mod) AS janggaran_mod,SUM (anggaran_btt) AS janggaran_btt,SUM (anggaran_trf) AS janggaran_trf,SUM (real_opr) AS jreal_opr,SUM (real_mod) AS jreal_mod,SUM (real_trf) AS jreal_trf,SUM (real_btt) AS jreal_btt FROM (
			SELECT SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5106') THEN a.nilai ELSE 0 END) AS anggaran_opr,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5201','5202','5203','5204','5205','5206') THEN a.nilai ELSE 0 END) AS anggaran_mod,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5301') THEN a.nilai ELSE 0 END) AS anggaran_btt,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5401','5402') THEN a.nilai ELSE 0 END) AS anggaran_trf,0 AS real_opr,0 AS real_mod,0 AS real_btt,0 AS real_trf FROM trdrka a WHERE LEFT (a.kd_rek6,1) IN ('5') AND a.jns_ang='$anggaran' 
			UNION ALL
			SELECT 0 AS anggaran_opr,0 AS anggaran_mod,0 AS anggaran_btt,0 AS anggaran_trf,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5101','5102','5103','5104','5105','5106') THEN (debet-kredit) ELSE 0 END) AS real_opr,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5201','5202','5203','5204','5205','5206') THEN (debet-kredit) ELSE 0 END) AS real_mod,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5301') THEN (debet-kredit) ELSE 0 END) AS real_btt,SUM (CASE WHEN LEFT (a.kd_rek6,4) IN ('5401','5402') THEN (debet-kredit) ELSE 0 END) AS real_trf FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE LEFT (a.map_real,1) IN ('5') AND MONTH (tgl_voucher)<='$bulan' AND YEAR (tgl_voucher)='$lntahunang') oke";


		$hasil2 = $this->db->query($sql2);	
		foreach ($hasil2->result() as $row2) {

		$janggaran_opr = $row2->janggaran_opr;
		$janggaran_mod = $row2->janggaran_mod;
		$janggaran_btt = $row2->janggaran_btt;
		$janggaran_trf = $row2->janggaran_trf;
		$jreal_opr = $row2->jreal_opr;
		$jreal_mod = $row2->jreal_mod;
		$jreal_trf = $row2->jreal_trf;
		$jreal_btt = $row2->jreal_btt;
	

		$cRet .= '<tr>
		<td align="center" colspan ="5" valign="top" width="10%" style="font-size:12px"><b>TOTAL</b></td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($janggaran_opr, "2", ",", ".") . '</b></td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($jreal_opr, "2", ",", ".") . '</b> </td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($janggaran_mod, "2", ",", ".") . '</b></td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($jreal_mod, "2", ",", ".") . '</b></td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($janggaran_btt, "2", ",", ".") . '</b></td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($jreal_btt, "2", ",", ".") . '</b></td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($janggaran_trf, "2", ",", ".") . '</b></td> 
		<td align="right" valign="top" width="10%" style="font-size:12px"><b>' . number_format($jreal_trf, "2", ",", ".") . '</b></td> 
		</tr>';
		}


		$cRet .= "</table>";
		$cRet .= '<br><br>
		<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" > Melawi , ' . $tanggal . '</TD>
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
						<TD align="center" ><b>' . $nama_ttd . '</b></TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $pangkat . '</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center">NIP. '.$nip.'</TD>
					</TR>
					</TABLE><br/>';
		$data['prev'] = $cRet;
		$judul = 'Perda_Lamp_I.5';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
			$this->support->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	// ===============================PERBUP 1.2 =========================


	function perbupi_1()
	{
		$data['page_title'] = 'CETAK PERBUP LAMP 1';
		$this->template->set('title', 'CETAK PERBUP LAMP. 1');
		$this->template->load('template', 'perdase/cetak_perbup_lampI_1', $data);
	}
    function cetak_perbup_lampI_1_akunSE($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd = '', $tanggal_ttd = '', $ttdperda = '', $label = '')
	{

		$lntahunang = $this->session->userdata('pcThang');
        $jns_ang = $this->uri->segment(12);
        // echo($jns_ang);
        $lntahunangn = $lntahunang;
		$ttd1 = str_replace('n', ' ', $ttdperda);

		switch ($bulan) {
			case  1:
				$judul = "JANUARI";
				break;
			case  2:
				$judul = "FEBRUARI";
				break;
			case  3:
				$judul = "TRIWULAN I";
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
		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($label == '1') {
			$label = 'UNAUDITED';
		} else if ($label == '2') {
			$label = 'AUDITED';
		} else {
			$label = '&nbsp;';
		}

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
        
        $cRet = '<TABLE style="border-collapse:collapse;font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
                    <TR>
                        <TD width="70%" align="center" ><b>&nbsp;</TD>
                        <TD width="30%" align="left" >LAMPIRAN 1 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN BUPATI MELAWI <br/> 
                            NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangn . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
                        </TD>
                    </TR>
                    <TR>
                        <TD width="70%" align="center" ><b>&nbsp;</TD>
                        <TD width="30%" align="center" ><b>&nbsp;</TD>
                    </TR>
				</TABLE><br/>';

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"0\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        &nbsp;
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong></strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>$kab <br>RINGKASAN LAPORAN REALISASI ANGGARAN <br>TAHUN ANGGARAN " . $lntahunang . "</b> <br> <b>$label</b>
                    </tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
					<thead>
					<tr>
						<td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Kode</b></td>
						<td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Uraian</b></td>
						<td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Jumlah (Rp.)</b></td>
						<td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Bertambah / Berkurang</b></td>
					</tr>
					<tr>
						<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Anggaran Setelah Perubahan</b></td>
						<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Realisasi</b></td>
						<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
						<td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
						</tr>
					</thead> ";

		$sql = "SELECT 
						SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
						SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
						FROM
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM(nilai_ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') $where AND jns_ang='$jns_ang'
						GROUP BY LEFT(kd_ang,1)) a;
						";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = $row->ang_surplus;
			$nil_surplus = $row->nil_surplus;
		}
		$sisa_surplus = $ang_surplus - $nil_surplus;
		if (($ang_surplus == 0) || ($ang_surplus == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $nil_surplus / $ang_surplus * 100;
		}
		$hasil->free_result();
		if ($ang_surplus < 0) {
			$ang_surplus1 = $ang_surplus * -1;
			$a = '(';
			$b = ')';
		} else {
			$ang_surplus1 = $ang_surplus;
			$a = '';
			$b = '';
		}
		if ($nil_surplus < 0) {
			$nil_surplus1 = $nil_surplus * -1;
			$c = '(';
			$d = ')';
		} else {
			$nil_surplus1 = $nil_surplus;
			$c = '';
			$d = '';
		}
		if ($sisa_surplus < 0) {
			$sisa_surplus1 = $sisa_surplus * -1;
			$e = '(';
			$f = ')';
		} else {
			$sisa_surplus1 = $sisa_surplus;
			$e = '';
			$f = '';
		}

		$sql = "SELECT 
						SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
						SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
						FROM
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM(nilai_ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and LEFT(kd_ang,2) IN ('61','62') $where AND jns_ang='$jns_ang'
						GROUP BY LEFT(kd_ang,2)) a;
						";


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = $row->ang_netto;
			$nil_netto = $row->nil_netto;
		}
		$sisa_netto = $ang_netto - $nil_netto;
		if (($ang_netto == 0) || ($ang_netto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $nil_netto / $ang_netto * 100;
		}
		$hasil->free_result();
		if ($ang_netto < 0) {
			$ang_netto1 = $ang_netto * -1;
			$g = '(';
			$h = ')';
		} else {
			$ang_netto1 = $ang_netto;
			$g = '';
			$h = '';
		}
		if ($nil_netto < 0) {
			$nil_netto1 = $nil_netto * -1;
			$i = '(';
			$j = ')';
		} else {
			$nil_netto1 = $nil_netto;
			$i = '';
			$j = '';
		}
		if ($sisa_netto < 0) {
			$sisa_netto1 = $sisa_netto * -1;
			$k = '(';
			$l = ')';
		} else {
			$sisa_netto1 = $sisa_netto;
			$k = '';
			$l = '';
		}

		$ang_silpa = $ang_surplus + $ang_netto;
		$nil_silpa = $nil_surplus + $nil_netto;
		$sisa_silpa = $ang_silpa - $nil_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $nil_silpa / $ang_silpa * 100;
		}
		if ($ang_silpa < 0) {
			$ang_silpa1 = $ang_silpa * -1;
			$m = '(';
			$n = ')';
		} else {
			$ang_silpa1 = $ang_silpa;
			$m = '';
			$n = '';
		}
		if ($nil_silpa < 0) {
			$nil_silpa1 = $nil_silpa * -1;
			$o = '(';
			$p = ')';
		} else {
			$nil_silpa1 = $nil_silpa;
			$o = '';
			$p = '';
		}
		if ($sisa_silpa < 0) {
			$sisa_silpa1 = $sisa_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$sisa_silpa1 = $sisa_silpa;
			$q = '';
			$r = '';
		}
		$sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi FROM map_lra_perda ORDER BY urut
						";
		$no = 0;
		$tot_peg = 0;
		$tot_brg = 0;
		$tot_mod = 0;
		$tot_bansos = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$urut = $row->urut;
			$kode = $row->kd_rek;
			$nama = $row->uraian;
			$kode1 = $row->kode1;
			$kode2 = $row->kode2;
			$kode3 = $row->kode3;
			$kode4 = $row->kode4;
			$kode5 = $row->kode5;
			$spasi = $row->spasi;

			$sql = "SELECT SUM(nilai_ang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot where jns_ang='$jns_ang' AND bulan='$bulan' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
			}
			$sel = $nil_ang - $nilai;
			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $nilai / $nil_ang * 100;
			}
			if ($sel < 0) {
				$sel1 = $sel * -1;
				$t = '(';
				$u = ')';	
			}  else {
				$sel1 = $sel;
				$t = '';
				$u = '';
			}
			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
								   <td align="left" valign="top"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 2:
					$cRet .= '<tr>
								   <td align="left" valign="top">' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								</tr>';
					break;
				case 3:
					$cRet .= '<tr>
								   <td align="left" valign="top">' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								</tr>';
					break;
				case 4:
					$cRet .= '<tr>
								   <td align="left" valign="top" >' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top">' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</b></td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 5:
					$cRet .= '<tr>
								   <td align="left" valign="top" >' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top">' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</b></td> 
								   <td align="right" valign="top">' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</b></td> 
								   <td align="right" valign="top">' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
								   <td align="right" valign="top">' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 6;
					$cRet .= '<tr>
								   <td align="left" valign="top" >' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" >' . $g . '' . number_format($ang_netto1, "2", ",", ".") . '' . $h . '</b></td> 
								   <td align="right" valign="top" >' . $i . '' . number_format($nil_netto1, "2", ",", ".") . '' . $j . '</b></td> 
								   <td align="right" valign="top" >' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
								   <td align="right" valign="top" >' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 7;
					$cRet .= '<tr>
								   <td align="left" valign="top" >' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
								   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
								   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
								</tr>';
					break;

				default:

					$cRet .= '<tr>
								   <td align="left" valign="top" >' . $kode . '</b></td> 
								   <td align="right"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td>
								   <td align="right" valign="top" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								   <td align="right" valign="top" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b></td> 
								</tr>';
					break;
			}
		}



		$cRet .= "</table>";


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='" . SKPD_BKD . "'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$daerah  = $rowsc->daerah;
		}

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$ttd1' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}


		if ($ttd1 != '1') {
			$xx = "<u>";
			$xy = "</u>";
			$nipxx = $nip;
			$nipx = "NIP.";
		} else {
			$xx = "";
			$xy = "";
			$nipxx = "";
			$nipx = "";
		}
		if ($tanggal_ttd == 1) {
			$tgltd = '';
		} else {
			$tgltd = $this->custom->tanggal_format_indonesia($tanggal_ttd);
		}

		if ($nip == '00000000 000000 0 000'){
		$cRet .= '<br><br>
			<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
							<TD align="center" >' . $xx . '<b>' . $namax . '</b>' . $xy . '</TD>
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
			<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" > MELAWI , ' . $tgltd . '</TD>
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
							<TD align="center" >' . $xx . '<b>' . $namax . '</b>' . $xy . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $pangkat . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
						</TR>
						</TABLE><br/>';
		}

		$data['prev'] = $cRet;
		$judul = 'Perbup_LampI.1 ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->support->_mpdf('', $cRet, 10, 10, 10, '1');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function perbupi_2()
	{
		$data['page_title'] = 'CETAK PERBUP LAMP. I.1';
		$this->template->set('title', 'CETAK PERBUP LAMP. I.1');
		$this->template->load('template', 'perdase/cetak_perbup_lampI_2', $data);
	}

    function cetak_perbup_lampI_2_akunSE($bulan = '', $ctk = '', $anggaran = '', $kd_skpd = '', $jenis = '', $tglttd = '', $ttd = '', $ttdperda = '', $label = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunangn = $lntahunang;
		$banyak = $this->uri->segment(12);
        $jns_ang = $this->uri->segment(13);
        // echo($jns_ang);
        $ttdperda =$this->uri->segment(10);
		$ttd1 = str_replace('n', ' ', $ttdperda);
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

		if ($label == '1') {
			$label = 'UNAUDITED';
		} else if ($label == '2') {
			$label = 'AUDITED';
		} else {
			$label = '&nbsp;';
		}

		$where = "WHERE a.kd_skpd='$kd_skpd'";
		$where1 = "WHERE b.kd_skpd='$kd_skpd'";
		/*if($ttd=='-'){
		$nama='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA' and kd_skpd='4.02.01.00'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}*/
        

		if ($kd_skpd == '1.01.2.22.0.00.02.0000') {
			$cRet = '<br/><TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="left" >LAMPIRAN I.1 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN BUPATI MELAWI <br/> 
                        NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangn . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
                        </TD>
					</TR>
                    <TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="center" ><b>&nbsp;</TD>
					</TR>
					</TABLE>';
            
		} else {
			$cRet = '';
		}

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"3\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"5\"  style=\"border-right:hidden;border-bottom:hidden;border-right:hidden\">&nbsp;&nbsp;&nbsp;</td>
					</tr>	
					<tr>
					<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden;border-bottom:hidden\">
							&nbsp;
							</td>
					<td align=\"center\" style=\"border-bottom:hidden\"><strong>" . $sclient->kab_kota . "</strong></td></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>PENJABARAN LAPORAN REALISASI ANGGARAN</b></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN " . $lntahunang . " <br/> $label</b></tr>
					</TABLE>";
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<!--<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan', 'ms_urusan', 'kd_urusan') . " </td>
					</tr>-->
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp;Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 22) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 22), 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>
					<!--<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $kd_skpd . " - " . $this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>-->
                    </TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:10px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"$banyak\">
                <thead>
				<tr>
                    <td colspan = \"9\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Kode Rekening</b></td>
                    <td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Uraian</b></td>
					<td width=\"13%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Anggaran Setelah Perubahan</b></td>
					<td width=\"13%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Realisasi</b></td>
					<td width=\"13%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Rp.</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Dasar Hukum</b></td>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Keterangan</b></td>
				</tr>
				<tr>
					<td colspan = \"9\" align=\"center\" bgcolor=\"#CCCCCC\">1</td>
					<td align=\"center\" bgcolor=\"#CCCCCC\">2</td>
					<td align=\"center\" bgcolor=\"#CCCCCC\">3</td>
					<td align=\"center\" bgcolor=\"#CCCCCC\">4</td>
					<td align=\"center\" bgcolor=\"#CCCCCC\">5</td>
					<td align=\"center\" bgcolor=\"#CCCCCC\">6</td>
					<td align=\"center\" bgcolor=\"#CCCCCC\">7</td>
					<td align=\"center\" bgcolor=\"#CCCCCC\">8</td>
				</tr>
				</thead>";


		 $sql = "SELECT z.kd_skpd, z.kd_kegiatan AS kd_kegiatan,z.kode AS kd_rek,left(z.kd_kegiatan,1) as kd1,SUBSTRING(z.kd_kegiatan,3,2) as kd2, left(kd_skpd,17)	as kd3, SUBSTRING(z.kd_kegiatan,6,2) as kd4,SUBSTRING(z.kd_kegiatan,9,4) as kd5, SUBSTRING(z.kd_kegiatan,11,2) as kd6, z.kd7 as kd7, z.kd8 as kd8, z.kd9 as kd9,z.nama AS nm_rek,SUM (z.anggaran) AS anggaran,SUM (z.reali) AS sd_bulan_ini,sumber, jns_ang FROM ( 
			----kegiatan 
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan AS kd_kegiatan,LEFT (a.kd_rek6,1) AS kode,b.nm_sub_kegiatan AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber ,LEFT (a.kd_rek6,1) as kd7, '' as kd8, '' as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_sub_kegiatan b ON b.kd_sub_kegiatan =a.kd_sub_kegiatan $where AND LEFT (a.kd_rek6,1)='4' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,b.nm_sub_kegiatan,LEFT (a.kd_rek6,1) ,a.kd_skpd, a.jns_ang
			---sub kegiatan 
			UNION ALL 
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,2) AS kd_kegiatan,LEFT (a.kd_rek6,2) AS kode,b.nm_rek2 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, '' as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek2 b ON b.kd_rek2 =LEFT (a.kd_rek6,2) $where AND LEFT (a.kd_rek6,1)='4' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,2),b.nm_rek2 ,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1), a.jns_ang
			UNION ALL 
			----akun3 
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,4) AS kd_kegiatan,LEFT (a.kd_rek6,4) AS kode,b.nm_rek3 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, SUBSTRING (a.kd_rek6,3,2) as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek3 b ON b.kd_rek3 =LEFT (a.kd_rek6,4) $where AND LEFT (a.kd_rek6,1)='4' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,4),b.nm_rek3,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2),a.jns_ang
			) z WHERE jns_ang='$jns_ang'
			GROUP BY kd_kegiatan,kode,nama,sumber ,kd_skpd,z.kd7,z.kd8,z.kd9, z.jns_ang
			ORDER BY kd_kegiatan,kode,nama";


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sumber = $row->sumber;
			$kd_skpd = $row->kd_skpd;
			$kd1 	= $row->kd1;
			$kd2 	= $row->kd2;
			$kd3 	= $row->kd3;
			$kd4	= $row->kd4;
			$kd5	= $row->kd5;
			$kd6 	= $row->kd6;
			$kd7 	= $row->kd7;
			$kd8 	= $row->kd8;
			$kd9 	= $row->kd9;
			
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			if ($sumber == 1210102){
				$sumber = "DAU";
			}else if($sumber == 12202){
				$sumber = "Bantuan Keuangan";
			}else if($sumber == 11416){
				$sumber = "Pendapatan dari BLUD";
			}else if($sumber == 221010418){
				$sumber = "DAK Non Fisik-Dana Yanminduk";
			}else if($sumber == 11){
				$sumber = "PAD";
			}else if($sumber == 2210104){
				$sumber = "DAK Non Fisik";
			}else if($sumber == 2210102){
				$sumber = "DAU";
			}else if($sumber == 1){
				$sumber = "DANA UMUM";
			}else{
				$sumber = "";
			}

					$cRet .= '<tr>
							   <td align="center" valign="top">' .$kd1. '</td> 
							   <td align="center" valign="top">' .$kd2. '</td>
							   <td align="center" valign="top">' .$kd3. '</td> 
							   <td align="center" valign="top">' .$kd4. '</td> 
							   <td align="center" valign="top">' .$kd5. '</td> 
							   <td align="center" valign="top">' .$kd6. '</td> 
							   <td align="center" valign="top">' .$kd7. '</td> 
							   <td align="center" valign="top">' .$kd8. '</td> 
							   <td align="center" valign="top">' .$kd9. '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
				
		}

		$hasil->free_result();

		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,1)='4' and bulan='$bulan' AND a.jns_ang='$jns_ang'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

		$cRet .= '<tr>
							   <td align="right" valign="top" colspan="10">JUMLAH PENDAPATAN</td>
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							   </tr>';
		}
			$sql = "SELECT z.kd_skpd,z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,left(z.kd_kegiatan,1) as kd1,SUBSTRING(z.kd_kegiatan,3,2) as kd2, left(kd_skpd,17)as kd3,kd4,kd5,kd6,kd7,kd8,kd9,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber, jns_ang from( 
				----program 
				SELECT a.kd_skpd,b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber, SUBSTRING(b.kd_program,6,2) as kd4, '' as kd5, '' as kd6, '' as kd7, '' as kd8, '' as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7) $where and left(a.kd_rek6,1)='5' and bulan='$bulan' group by b.kd_program,b.nm_program,a.kd_skpd,SUBSTRING(b.kd_program,6,2), a.jns_ang
				union all 
				SELECT a.kd_skpd,b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,SUBSTRING(b.kd_kegiatan,6,2) as kd4, SUBSTRING(b.kd_kegiatan,9,4) as kd5, '' as kd6, '' as kd7, '' as kd8, '' as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan and b.kd_skpd = a.kd_skpd AND a.jns_ang=b.jns_ang $where and left(a.kd_rek6,1)='5' and bulan='$bulan' group by b.kd_kegiatan,b.nm_kegiatan,a.kd_skpd,SUBSTRING(b.kd_kegiatan,6,2),SUBSTRING(b.kd_kegiatan,9,4), a.jns_ang
				union all 
				----kegiatan 
				SELECT a.kd_skpd,a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, SUBSTRING(a.kd_sub_kegiatan,9,4) as kd5, SUBSTRING (a.kd_sub_kegiatan,14,2) as kd6, '' as kd7, '' as kd8, '' as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan $where and left(a.kd_rek6,1)='5' and bulan='$bulan' group by a.kd_sub_kegiatan,b.nm_sub_kegiatan ,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,9,4),SUBSTRING(a.kd_sub_kegiatan,6,2),SUBSTRING (a.kd_sub_kegiatan,14,2), a.jns_ang
				union all 
				----akun1
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+ LEFT (a.kd_rek6,1) AS kd_kegiatan,LEFT (a.kd_rek6,1) AS kode,b.nm_rek1 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, SUBSTRING(a.kd_sub_kegiatan,9,4) as kd5, SUBSTRING (a.kd_sub_kegiatan,14,2) as kd6, LEFT (a.kd_rek6,1) as kd7, '' as kd8, '' as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek1 b ON b.kd_rek1 =LEFT (a.kd_rek6,1) $where AND LEFT (a.kd_rek6,1)='5' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,1),b.nm_rek1,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2),SUBSTRING(a.kd_sub_kegiatan,9,4),SUBSTRING (a.kd_sub_kegiatan,14,2), LEFT (a.kd_rek6,1) , a.jns_ang
				union all
				----akun2
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+ LEFT (a.kd_rek6,2) AS kd_kegiatan,LEFT (a.kd_rek6,2) AS kode,b.nm_rek2 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, SUBSTRING(a.kd_sub_kegiatan,9,4) as kd5, SUBSTRING (a.kd_sub_kegiatan,14,2) as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1)  as kd8, '' as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek2 b ON b.kd_rek2 =LEFT (a.kd_rek6,2) $where AND LEFT (a.kd_rek6,1)='5' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,2),b.nm_rek2,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2),SUBSTRING(a.kd_sub_kegiatan,9,4),SUBSTRING (a.kd_sub_kegiatan,14,2), LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,2,1), a.jns_ang
				union all
				----akun3 
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+left(a.kd_rek6,4) as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, SUBSTRING(a.kd_sub_kegiatan,9,4) as kd5, SUBSTRING (a.kd_sub_kegiatan,14,2) as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1)  as kd8, SUBSTRING(a.kd_rek6, 3,2) as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4) $where and left(a.kd_rek6,1)='5' and bulan='$bulan' group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 ,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2),SUBSTRING(a.kd_sub_kegiatan,9,4),SUBSTRING (a.kd_sub_kegiatan,14,2), LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,2,1),SUBSTRING(a.kd_rek6, 3,2), a.jns_ang
				) z WHERE jns_ang='$jns_ang'
				group by kd_kegiatan,kode,nama,sumber,kd_skpd,kd4,kd5,kd6,kd7,kd8,kd9, jns_ang
				order by kd_kegiatan,kode,nama";
		

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sumber = $row->sumber;
			$kd_skpd = $row->kd_skpd;
			$kd1 	= $row->kd1;
			$kd2 	= $row->kd2;
			$kd3 	= $row->kd3;
			$kd4	= $row->kd4;
			$kd5	= $row->kd5;
			$kd6 	= $row->kd6;
			$kd7 	= $row->kd7;
			$kd8 	= $row->kd8;
			$kd9 	= $row->kd9;



			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			if ($sumber == 1210102){
				$sumber = "DAU";
			}else if($sumber == 12202){
				$sumber = "Bantuan Keuangan";
			}else if($sumber == 11416){
				$sumber = "Pendapatan dari BLUD";
			}else if($sumber == 221010418){
				$sumber = "DAK Non Fisik-Dana Yanminduk";
			}else if($sumber == 11){
				$sumber = "PAD";
			}else if($sumber == 2210104){
				$sumber = "DAK Non Fisik";
			}else if($sumber == 2210102){
				$sumber = "DAU";
			}else if($sumber == 1){
				$sumber = "DANA UMUM";
			}else{
				$sumber = "";
			}

					$cRet .= '<tr>
								<td align="center" width="3%" valign="top">' .$kd1. '</td> 
								<td align="center" width="4%" valign="top">' .$kd2. '</td>
								<td align="center" width="10%" valign="top">' .$kd3. '</td> 
								<td align="center" width="4%" valign="top">' .$kd4. '</td> 
								<td align="center" width="6%" valign="top">' .$kd5. '</td> 
								<td align="center" width="4%" valign="top">' .$kd6. '</td> 
								<td align="center" width="3%" valign="top">' .$kd7. '</td> 
								<td align="center" width="3%" valign="top">' .$kd8. '</td> 
								<td align="center" width="4%" valign="top">' .$kd9. '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td> 
								<td align="right" valign="top"></td>
							</tr>';
		}
        
				$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,1) in ('5') and bulan='$bulan' AND a.jns_ang='$jns_ang'";
				$hasil = $this->db->query($sql);
				foreach ($hasil->result() as $row) {

				$nil_ang13 = $row->anggaran;
				$sd_bulan_ini13 = $row->sd_bulan_ini;
				$sisa13 = $sd_bulan_ini13 - $nil_ang13;
				$persen13 = empty($nil_ang13) || $nil_ang13 == 0 ? 0 : $sd_bulan_ini13 / $nil_ang13 * 100;
				$sisa133 = $sisa13 < 0 ? $sisa13 * -1 : $sisa13;
				$y = $sisa13 < 0 ? '(' : '';
				$z = $sisa13 < 0 ? ')' : '';

				$cRet .= '<tr>
						<td align="right" colspan="10"  valign="top">JUMLAH BELANJA</td> 
						<td align="right" valign="top">' . number_format($nil_ang13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . number_format($sd_bulan_ini13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . $y . ' ' . number_format($sisa133, "2", ",", ".") . ' ' . $z . '</td> 
						<td align="right" valign="top">' . number_format($persen13, "2", ",", ".") . '</b></td> 
						<td align="right" valign="top"></td> 
						<td align="right" valign="top"></td>
					</tr>';

				}

				$sql = "SELECT 
						SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
						SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
						FROM
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM(nilai_ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where kd_skpd = '$kd_skpd' and bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') AND jns_ang='$jns_ang'
						GROUP BY LEFT(kd_ang,1)) a;
						";
					$hasil = $this->db->query($sql);
					foreach ($hasil->result() as $row) {
						$ang_surplus = $row->ang_surplus;
						$nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $ang_surplus - $nil_surplus;
					if (($ang_surplus == 0) || ($ang_surplus == '')) {
						$persen_surplus = 0;
					} else {
						$persen_surplus = $nil_surplus / $ang_surplus * 100;
					}
					$hasil->free_result();
					if ($ang_surplus < 0) {
						$ang_surplus1 = $ang_surplus * -1;
						$a = '(';
						$b = ')';
					} else {
						$ang_surplus1 = $ang_surplus;
						$a = '';
						$b = '';
					}
					if ($nil_surplus < 0) {
						$nil_surplus1 = $nil_surplus * -1;
						$c = '(';
						$d = ')';
					} else {
						$nil_surplus1 = $nil_surplus;
						$c = '';
						$d = '';
					}
					if ($sisa_surplus < 0) {
						$sisa_surplus1 = $sisa_surplus * -1;
						$e = '(';
						$f = ')';
					} else {
						$sisa_surplus1 = $sisa_surplus;
						$e = '';
						$f = '';
					}

					
					$cRet .= '<tr>
						<td align="right" colspan="10" valign="top">TOTAL SURPLUS / (DEFISIT)</td> 
						<td align="right" valign="top">' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</td> 
						<td align="right" valign="top">' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</td> 
						<td align="right" valign="top">' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</td> 
						<td align="right" valign="top">' . number_format($persen_surplus, "2", ",", ".") . '</td> 
						<td align="right" valign="top"></td> 
						<td align="right" valign="top"></td>
					</tr>';
					
		$hasil->free_result();
		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

			$sql = "SELECT z.kd_skpd,z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,left(z.kd_kegiatan,1) as kd1,SUBSTRING(z.kd_kegiatan,3,2) as kd2, left(kd_skpd,17)as kd3,kd4,kd5,kd6,kd7,kd8,kd9,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber, jns_ang from( 
				----program 
				SELECT a.kd_skpd,b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber, SUBSTRING(b.kd_program,6,2) as kd4, '' as kd5, '' as kd6, '' as kd7, '' as kd8, '' as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7) $where and left(a.kd_rek6,2)='61' and bulan='$bulan' group by b.kd_program,b.nm_program,a.kd_skpd,SUBSTRING(b.kd_program,6,2), a.jns_ang
				union all 
				SELECT a.kd_skpd,b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,SUBSTRING(b.kd_kegiatan,6,2) as kd4, '0.00' as kd5, '' as kd6, '' as kd7, '' as kd8, '' as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan and b.kd_skpd = a.kd_skpd AND a.jns_ang=b.jns_ang $where and left(a.kd_rek6,2)='61' and bulan='$bulan' group by b.kd_kegiatan,b.nm_kegiatan,a.kd_skpd,SUBSTRING(b.kd_kegiatan,6,2), a.jns_ang
				union all 
				----kegiatan 
				SELECT a.kd_skpd,a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, '0.00' as kd5,'00' as kd6, '' as kd7, '' as kd8, '' as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan $where and left(a.kd_rek6,2)='61' and bulan='$bulan' group by a.kd_sub_kegiatan,b.nm_sub_kegiatan ,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2), a.jns_ang
				union all 
				----akun1
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+ LEFT (a.kd_rek6,1) AS kd_kegiatan,LEFT (a.kd_rek6,1) AS kode,b.nm_rek1 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, '0.00' as kd5, '00' as kd6, LEFT (a.kd_rek6,1) as kd7, '' as kd8, '' as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek1 b ON b.kd_rek1 =LEFT (a.kd_rek6,1) $where AND LEFT (a.kd_rek6,2)='61' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,1),b.nm_rek1,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2), LEFT (a.kd_rek6,1), a.jns_ang
				union all
				----akun2
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+ LEFT (a.kd_rek6,2) AS kd_kegiatan,LEFT (a.kd_rek6,2) AS kode,b.nm_rek2 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, '0.00' as kd5, '00' as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1)  as kd8, '' as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek2 b ON b.kd_rek2 =LEFT (a.kd_rek6,2) $where AND LEFT (a.kd_rek6,2)='61' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,2),b.nm_rek2,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2), LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,2,1), a.jns_ang
				union all
				----akun3 
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+left(a.kd_rek6,4) as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, '0.00' as kd5, '00' as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1)  as kd8, SUBSTRING(a.kd_rek6, 3,2) as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4) $where and left(a.kd_rek6,2)='61' and bulan='$bulan' group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 ,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2), LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,2,1),SUBSTRING(a.kd_rek6, 3,2), a.jns_ang
				) z WHERE z.jns_ang='$jns_ang'
				group by kd_kegiatan,kode,nama,sumber,kd_skpd,kd4,kd5,kd6,kd7,kd8,kd9, jns_ang
				order by kd_kegiatan,kode,nama";
		

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$kd_skpd = $row->kd_skpd;
			$kd1 	= $row->kd1;
			$kd2 	= $row->kd2;
			$kd3 	= $row->kd3;
			$kd4	= $row->kd4;
			$kd5	= $row->kd5;
			$kd6 	= $row->kd6;
			$kd7 	= $row->kd7;
			$kd8 	= $row->kd8;
			$kd9 	= $row->kd9;

			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

				$cRet .= '<tr>
								<td align="center" valign="top">' .$kd1. '</td> 
								<td align="center" valign="top">' .$kd2. '</td>
								<td align="center" valign="top">' .$kd3. '</td> 
								<td align="center" valign="top">' .$kd4. '</td> 
								<td align="center" valign="top">' .$kd5. '</td> 
								<td align="center" valign="top">' .$kd6. '</td> 
								<td align="center" valign="top">' .$kd7. '</td> 
								<td align="center" valign="top">' .$kd8. '</td> 
								<td align="center" valign="top">' .$kd9. '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
							</tr>';
		}

		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,2) in ('61') and bulan='$bulan' AND jns_ang='$jns_ang'";
				$hasil = $this->db->query($sql);
				foreach ($hasil->result() as $row) {

				$nil_ang13 = $row->anggaran;
				$sd_bulan_ini13 = $row->sd_bulan_ini;
				$sisa13 = $sd_bulan_ini13 - $nil_ang13;
				$persen13 = empty($nil_ang13) || $nil_ang13 == 0 ? 0 : $sd_bulan_ini13 / $nil_ang13 * 100;
				$sisa133 = $sisa13 < 0 ? $sisa13 * -1 : $sisa13;
				$y = $sisa13 < 0 ? '(' : '';
				$z = $sisa13 < 0 ? ')' : '';

				$cRet .= '<tr>
						<td align="right" colspan = "10"  valign="top">JUMLAH PENERIMAAN PEMBIAYAAN</td> 
						<td align="right" valign="top">' . number_format($nil_ang13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . number_format($sd_bulan_ini13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . $y . ' ' . number_format($sisa133, "2", ",", ".") . ' ' . $z . '</td> 
						<td align="right" valign="top">' . number_format($persen13, "2", ",", ".") . '</td> 
						<td align="right" valign="top"></td> 
						<td align="right" valign="top"></td>
					</tr>';
				}


		$hasil->free_result();
		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

		
			$sql = "SELECT z.kd_skpd,z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,left(z.kd_kegiatan,1) as kd1,SUBSTRING(z.kd_kegiatan,3,2) as kd2, left(kd_skpd,17)as kd3,	kd4,kd5,kd6,kd7,kd8,kd9,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber, jns_ang from( 
				----akun2
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+ LEFT (a.kd_rek6,2) AS kd_kegiatan,LEFT (a.kd_rek6,2) AS kode,b.nm_rek2 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, '0.00' as kd5, '00' as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1)  as kd8, '' as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek2 b ON b.kd_rek2 =LEFT (a.kd_rek6,2) $where AND LEFT (a.kd_rek6,2)='62' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,2),b.nm_rek2,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2), LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,2,1), a.jns_ang
				union all
				----akun3 
				SELECT a.kd_skpd,a.kd_sub_kegiatan+'.'+left(a.kd_rek6,4) as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,6,2) as kd4, '0.00' as kd5, '00' as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1)  as kd8, SUBSTRING(a.kd_rek6, 3,2) as kd9, a.jns_ang as jns_ang FROM data_realisasi_pemkot a inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4) $where and left(a.kd_rek6,2)='62' and bulan='$bulan' group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 ,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,6,2), LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,2,1),SUBSTRING(a.kd_rek6, 3,2), a.jns_ang
				) z WHERE z.jns_ang='$jns_ang'
				group by kd_kegiatan,kode,nama,sumber,kd_skpd,kd4,kd5,kd6,kd7,kd8,kd9, jns_ang
				order by kd_kegiatan,kode,nama";
		

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$kd_skpd = $row->kd_skpd;
			$kd1 	= $row->kd1;
			$kd2 	= $row->kd2;
			$kd3 	= $row->kd3;
			$kd4	= $row->kd4;
			$kd5	= $row->kd5;
			$kd6 	= $row->kd6;
			$kd7 	= $row->kd7;
			$kd8 	= $row->kd8;
			$kd9 	= $row->kd9;

			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			
					$cRet .= '<tr>
								<td align="center" valign="top">' .$kd1. '</td> 
								<td align="center" valign="top">' .$kd2. '</td>
								<td align="center" valign="top">' .$kd3. '</td> 
								<td align="center" valign="top">' .$kd4. '</td> 
								<td align="center" valign="top">' .$kd5. '</td> 
								<td align="center" valign="top">' .$kd6. '</td> 
								<td align="center" valign="top">' .$kd7. '</td> 
								<td align="center" valign="top">' .$kd8. '</td> 
								<td align="center" valign="top">' .$kd9. '</td>
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
							</tr>';
			
		}
		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,2) in ('62') and bulan='$bulan' AND jns_ang='$jns_ang'";
				$hasil = $this->db->query($sql);
				foreach ($hasil->result() as $row) {

				$nil_ang13 = $row->anggaran;
				$sd_bulan_ini13 = $row->sd_bulan_ini;
				$sisa13 = $sd_bulan_ini13 - $nil_ang13;
				$persen13 = empty($nil_ang13) || $nil_ang13 == 0 ? 0 : $sd_bulan_ini13 / $nil_ang13 * 100;
				$sisa133 = $sisa13 < 0 ? $sisa13 * -1 : $sisa13;
				$y = $sisa13 < 0 ? '(' : '';
				$z = $sisa13 < 0 ? ')' : '';

				$cRet .= '<tr>
						<td align="right" colspan = "10"  valign="top">JUMLAH PENGELUARAN PEMBIAYAAN</td> 
						<td align="right" valign="top">' . number_format($nil_ang13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . number_format($sd_bulan_ini13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . $y . ' ' . number_format($sisa133, "2", ",", ".") . ' ' . $z . '</td> 
						<td align="right" valign="top">' . number_format($persen13, "2", ",", ".") . '</td> 
						<td align="right" valign="top"></td> 
						<td align="right" valign="top"></td>
					</tr>';
				}

		//pembiayaan netto
		$sql = "SELECT isnull(SUM (z.anggaran61),0)-isnull(SUM (z.anggaran62),0) AS anggaran,isnull(SUM (z.sd_bulan_ini61),0)-isnull(SUM (z.sd_bulan_ini62),0) AS sd_bulan_ini, z.jns_ang FROM (
			SELECT SUM (a.nilai_ang) anggaran61,SUM (a.real_spj) sd_bulan_ini61,0 AS anggaran62,0 AS sd_bulan_ini62, a.jns_ang as jns_ang FROM data_realisasi_pemkot a $where AND LEFT (a.kd_rek6,2)='61' AND bulan='$bulan' GROUP BY a.jns_ang
             UNION
			SELECT 0 AS anggaran61,0 AS sd_bulan_ini61,SUM (b.nilai_ang) anggaran62,SUM (b.real_spj) sd_bulan_ini62, b.jns_ang as jns_ang FROM data_realisasi_pemkot b $where1 AND LEFT (b.kd_rek6,2)='62' AND bulan='$bulan' GROUP BY b.jns_ang) z WHERE z.jns_ang='$jns_ang' GROUP BY z.jns_ang";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang12 = $row->anggaran;
			$sd_bulan_ini12 = $row->sd_bulan_ini;
			$sisa12 = $sd_bulan_ini12 - $nil_ang12;
			$persen12 = empty($nil_ang12) || $nil_ang12 == 0 ? 0 : $sd_bulan_ini12 / $nil_ang12 * 100;
			$sisa12 = $sisa12 < 0 ? $sisa12 * -1 : $sisa12;
			$a = $sisa12 < 0 ? '(' : '';
			$b = $sisa12 < 0 ? ')' : '';

			//if ($nil_ang12 != 0) {
				$cRet .= '<tr>
								<td align="right" colspan = "10"  valign="top">PEMBIAYAAN NETO</td> 
								<td align="right" valign="top">' . number_format($nil_ang12, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini12, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa12, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen12, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
							</tr>';
				
		}
		//end


		$sql = "SELECT (isnull(SUM (z.anggaran_4),0)-isnull(SUM (z.anggaran_5),0))+(isnull(SUM (z.anggaran_61),0)-isnull(SUM (z.anggaran_62),0)) AS anggaran,(isnull(SUM (z.sd_bulan_ini_4),0)-isnull(SUM (z.sd_bulan_ini_5),0))+(isnull(SUM (z.sd_bulan_ini_61),0)-isnull(SUM (z.sd_bulan_ini_62),0)) AS sd_bulan_ini, jns_ang FROM (
			SELECT SUM (a.nilai_ang) anggaran_4,SUM (a.real_spj) sd_bulan_ini_4,0 anggaran_5,0 AS sd_bulan_ini_5,0 anggaran_61,0 AS sd_bulan_ini_61,0 AS anggaran_62,0 AS sd_bulan_ini_62, a.jns_ang as jns_ang FROM data_realisasi_pemkot a $where AND LEFT (a.kd_ang,1) IN ('4') AND bulan='$bulan' GROUP BY a.jns_ang 
            UNION
			SELECT 0 AS anggaran_4,0 AS sd_bulan_ini_4,SUM (a.nilai_ang) anggaran_5,SUM (a.real_spj) sd_bulan_ini_5,0 AS anggaran_61,0 AS sd_bulan_ini_61,0 AS anggaran_62,0 AS sd_bulan_ini_62, a.jns_ang as jns_ang FROM data_realisasi_pemkot a $where AND LEFT (a.kd_ang,1) IN ('5') AND bulan='$bulan' GROUP BY a.jns_ang
             UNION
			SELECT 0 AS anggaran_4,0 AS sd_bulan_ini_4,0 AS anggaran_5,0 AS sd_bulan_ini_5,SUM (a.nilai_ang) anggaran_61,SUM (a.real_spj) sd_bulan_ini_61,0 AS anggaran_62,0 AS sd_bulan_ini_62, a.jns_ang as jns_ang FROM data_realisasi_pemkot a $where AND LEFT (a.kd_ang,2) IN ('61') AND bulan='$bulan' GROUP BY a.jns_ang
             UNION
			SELECT 0 AS anggaran_4,0 AS sd_bulan_ini_4,0 AS anggaran_5,0 AS sd_bulan_ini_5,0 AS anggaran_61,0 AS sd_bulan_ini_61,SUM (a.nilai_ang) anggaran_62,SUM (a.real_spj) sd_bulan_ini_62, a.jns_ang as jns_ang FROM data_realisasi_pemkot a $where AND LEFT (a.kd_ang,2) IN ('62') AND bulan='$bulan' GROUP BY a.jns_ang )z WHERE z.jns_ang='$jns_ang' GROUP BY jns_ang";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang145 = $row->anggaran;
			$sd_bulan_ini145 = $row->sd_bulan_ini;
			$sisa1 = $sd_bulan_ini145 - $nil_ang145;
			$persen145 = empty($nil_ang145) || $nil_ang145 == 0 ? 0 : $sd_bulan_ini145 / $nil_ang145 * 100;

			// $a45 = $sisa1 < 0 ? '(' : '';
			// $b45 = $sisa1 < 0 ? ')' : '';
			// $sisa1145 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;

            if ($sisa1 < 0) {
                $sisa1145 = $sisa1 * -1;
                $a45 = '(';
                $b45 = ')';
            } else {
                $sisa1145 = $sisa1;
                $a45 = '';
                $b45 = '';
            }

			$a445 = $nil_ang145 < 0 ? '(' : '';
			$b445 = $nil_ang145 < 0 ? ')' : '';
			$nil_ang1455 = $nil_ang145 < 0 ? $nil_ang145 * -1 : $nil_ang145;

			$a455 = $sd_bulan_ini145 < 0 ? '(' : '';
			$b455 = $sd_bulan_ini145 < 0 ? ')' : '';
			$sd_bulan_ini1455 = $sd_bulan_ini145 < 0 ? $sd_bulan_ini145 * -1 : $sd_bulan_ini145;


			$cRet .= '<tr>
								<td align="right" colspan="10" valign="top">SISA LEBIH PEMBIAYAAN ANGGARAN (SILPA)</td> 
								<td align="right" valign="top">' . $a445 . ' ' . number_format($nil_ang1455, "2", ",", ".") . ' ' . $b445 . '</td> 
								<td align="right" valign="top">' . $a455 . ' ' . number_format($sd_bulan_ini1455, "2", ",", ".") . ' ' . $b455 . '</td> 
								<td align="right" valign="top">' . $a45 . ' ' . number_format($sisa1145, "2", ",", ".") . ' ' . $b45 . '</td> 
								<td align="right" valign="top">' . number_format($persen145, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
							</tr>';
		}
		$hasil->free_result();

		$cRet .= "</table>";

		$tanggal = $tglttd == '-' ? '' : 'Melawi, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		
			$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='8.01.0.00.0.00.01.0000'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowsc) {
				$kab     = $rowsc->kab_kota;
				$daerah  = $rowsc->daerah;
			}

			 $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip ='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}
			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tglttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
			}

			$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > Melawi , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';

		$data['prev'] = $cRet;
		$judul = 'PERBUP_LAMP_I.2 ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf_lamp('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}
	
	function cetak_perbup_lampI_2_akunSE_sub_ro($bulan = '', $ctk = '', $anggaran = '', $kd_skpd = '', $jenis = '', $tglttd = '', $ttd = '', $ttdperda = '', $label = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunangn = $lntahunang;
		$banyak = $this->uri->segment(12);
        $jns_ang = $this->uri->segment(13);
		$ttd1 = str_replace('n', ' ', $ttdperda);
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

		if ($label == '1') {
			$label = 'UNAUDITED';
		} else if ($label == '2') {
			$label = 'AUDITED';
		} else {
			$label = '&nbsp;';
		}

		$where = "WHERE a.kd_skpd='$kd_skpd'";
		$where1 = "WHERE b.kd_skpd='$kd_skpd'";
		/*if($ttd=='-'){
		$nama='';
		$pangkat='';
		$jabatan='';
		$nip='';
	}else{
	$ttd=str_replace("abc"," ",$ttd);
	$sqlsc="SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode='PA' and kd_skpd='4.02.01.00'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama = $rowttd->nama;
                    $jabatan = $rowttd->jabatan;
                    $pangkat = $rowttd->pangkat;
                    $nip = 'NIP. '.$ttd;
                } 
	}*/
        

		if ($kd_skpd == '1.01.2.22.0.00.02.0000') {
			$cRet = '<br/><TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style; width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="left" >LAMPIRAN I.1 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN BUPATI MELAWI <br/> 
                        NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangn . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
                        </TD>
					</TR>
                    <TR>
						<TD width="70%" align="center" ><b>&nbsp;</TD>
						<TD width="30%" align="center" ><b>&nbsp;</TD>
					</TR>
					</TABLE>';
            
		} else {
			$cRet = '';
		}

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"3\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"5\"  style=\"border-right:hidden;border-bottom:hidden;border-right:hidden\">&nbsp;&nbsp;&nbsp;</td>
					</tr>	
					<tr>
					<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden;border-bottom:hidden\">
							&nbsp;
							</td>
					<td align=\"center\" style=\"border-bottom:hidden\"><strong>" . $sclient->kab_kota . "</strong></td></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>PENJABARAN LAPORAN REALISASI ANGGARAN</b></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN " . $lntahunang . " <br/> $label</b></tr>
					</TABLE>";
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<!--<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan', 'ms_urusan', 'kd_urusan') . " </td>
					</tr>-->
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp;Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 22) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 22), 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>
					<!--<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $kd_skpd . " - " . $this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>-->
                    </TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:10px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"$banyak\">
                <thead>
				<tr>
                    <td colspan = \"12\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Kode Rekening</b></td>
                    <td width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Uraian</b></td>
					<td width=\"13%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Anggaran Setelah Perubahan</b></td>
					<td width=\"13%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Realisasi</b></td>
					<td width=\"13%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Rp.</b></td>
					<td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Dasar Hukum</b></td>
                    <td width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>Keterangan</b></td>
				</tr>
				<tr>
					<td colspan = \"12\" align=\"center\" bgcolor=\"#CCCCCC\">1</td>
					<td align=\"center\" bgcolor=\"#CCCCCC\">2</td>
					<td align=\"center\" bgcolor=\"#CCCCCC\">3</td>
					<td align=\"center\" bgcolor=\"#CCCCCC\">4</td>
					<td align=\"center\" bgcolor=\"#CCCCCC\">5</td>
					<td align=\"center\" bgcolor=\"#CCCCCC\">6</td>
					<td align=\"center\" bgcolor=\"#CCCCCC\">7</td>
					<td align=\"center\" bgcolor=\"#CCCCCC\">8</td>
				</tr>
				</thead>";


		$sql = "SELECT z.kd_skpd, z.kd_kegiatan AS kd_kegiatan,z.kode AS kd_rek,left(z.kd_kegiatan,1) as kd1,SUBSTRING(z.kd_kegiatan,3,2) as kd2, left(kd_skpd,17) as kd3, SUBSTRING(z.kd_kegiatan,6,2) as kd4,SUBSTRING(z.kd_kegiatan,9,4) as kd5, SUBSTRING(z.kd_kegiatan,11,2) as kd6, z.kd7 as kd7, z.kd8 as kd8, z.kd9 as kd9,z.kd10,z.kd11,kd12,z.nama AS nm_rek,SUM (z.anggaran) AS anggaran,SUM (z.reali) AS sd_bulan_ini,sumber, jns_ang FROM ( 
			----kegiatan 
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan AS kd_kegiatan,LEFT (a.kd_rek6,1) AS kode,b.nm_sub_kegiatan AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber ,LEFT (a.kd_rek6,1) as kd7, '' as kd8, '' as kd9 ,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_sub_kegiatan b ON b.kd_sub_kegiatan =a.kd_sub_kegiatan $where AND LEFT (a.kd_rek6,1)='4' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,b.nm_sub_kegiatan,LEFT (a.kd_rek6,1) ,a.kd_skpd, a.jns_ang 
			---sub kegiatan 
			UNION ALL 
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,2) AS kd_kegiatan,LEFT (a.kd_rek6,2) AS kode,b.nm_rek2 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, '' as kd9 ,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek2 b ON b.kd_rek2 =LEFT (a.kd_rek6,2) $where AND LEFT (a.kd_rek6,1)='4' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,2),b.nm_rek2 ,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),a.jns_ang 
			UNION ALL 
			----akun3 
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,4) AS kd_kegiatan,LEFT (a.kd_rek6,4) AS kode,b.nm_rek3 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, SUBSTRING (a.kd_rek6,3,2) as kd9,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek3 b ON b.kd_rek3 =LEFT (a.kd_rek6,4) $where AND LEFT (a.kd_rek6,1)='4' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,4),b.nm_rek3,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2), a.jns_ang 
			UNION ALL
			----akun4
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,6) AS kd_kegiatan,LEFT (a.kd_rek6,6) AS kode,b.nm_rek4 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, SUBSTRING (a.kd_rek6,3,2) as kd9 , SUBSTRING(a.kd_rek6,5,2) as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek4 b ON b.kd_rek4 =LEFT (a.kd_rek6,6) $where AND LEFT (a.kd_rek6,1)='4' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,6),b.nm_rek4,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2),SUBSTRING(a.kd_rek6,5,2), a.jns_ang 
			UNION ALL
			----akun5
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,8) AS kd_kegiatan,LEFT (a.kd_rek6,8) AS kode,b.nm_rek5 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, SUBSTRING (a.kd_rek6,3,2) as kd9, SUBSTRING(a.kd_rek6,5,2) as kd10, SUBSTRING(a.kd_rek6,7,2) as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek5 b ON b.kd_rek5 =LEFT (a.kd_rek6,8) $where AND LEFT (a.kd_rek6,1)='4' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,8),b.nm_rek5,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2) ,SUBSTRING(a.kd_rek6,5,2),SUBSTRING(a.kd_rek6,7,2), a.jns_ang
			UNION ALL
			----akun6
			SELECT a.kd_skpd AS kd_skpd,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,12) AS kd_kegiatan,a.kd_rek6 AS kode,b.nm_rek6 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,LEFT (a.kd_rek6,1) AS kd7,SUBSTRING (a.kd_rek6,2,1) AS kd8,SUBSTRING (a.kd_rek6,3,2) AS kd9, SUBSTRING(a.kd_rek6,5,2) as kd10, SUBSTRING(a.kd_rek6,7,2) as kd11, SUBSTRING(a.kd_rek6,9,4) as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek6 b ON b.kd_rek6 =a.kd_rek6 $where AND LEFT (a.kd_rek6,1)='4' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_rek6,b.nm_rek6,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2),SUBSTRING(a.kd_rek6,5,2),SUBSTRING(a.kd_rek6,7,2),SUBSTRING(a.kd_rek6,9,4), a.jns_ang
			) z 
			WHERE z.jns_ang='$jns_ang'
			GROUP BY kd_kegiatan,kode,nama,sumber ,kd_skpd,z.kd7,z.kd8,z.kd9,z.kd10,z.kd11,z.kd12, z.jns_ang
			ORDER BY kd_kegiatan,kode,nama";


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sumber = $row->sumber;
			$kd_skpd = $row->kd_skpd;
			$kd1 	= $row->kd1;
			$kd2 	= $row->kd2;
			$kd3 	= $row->kd3;
			$kd4	= $row->kd4;
			$kd5	= $row->kd5;
			$kd6 	= $row->kd6;
			$kd7 	= $row->kd7;
			$kd8 	= $row->kd8;
			$kd9 	= $row->kd9;
			$kd10 	= $row->kd10;
			$kd11 	= $row->kd11;
			$kd12	= $row->kd12;
			
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			if ($sumber == 1210102){
				$sumber = "DAU";
			}else if($sumber == 12202){
				$sumber = "Bantuan Keuangan";
			}else if($sumber == 11416){
				$sumber = "Pendapatan dari BLUD";
			}else if($sumber == 221010418){
				$sumber = "DAK Non Fisik-Dana Yanminduk";
			}else if($sumber == 11){
				$sumber = "PAD";
			}else if($sumber == 2210104){
				$sumber = "DAK Non Fisik";
			}else if($sumber == 2210102){
				$sumber = "DAU";
			}else if($sumber == 1){
				$sumber = "DANA UMUM";
			}else{
				$sumber = "";
			}

					$cRet .= '<tr>
							   <td align="center" valign="top">' .$kd1. '</td> 
							   <td align="center" valign="top">' .$kd2. '</td>
							   <td align="center" valign="top">' .$kd3. '</td> 
							   <td align="center" valign="top">' .$kd4. '</td> 
							   <td align="center" valign="top">' .$kd5. '</td> 
							   <td align="center" valign="top">' .$kd6. '</td> 
							   <td align="center" valign="top">' .$kd7. '</td> 
							   <td align="center" valign="top">' .$kd8. '</td> 
							   <td align="center" valign="top">' .$kd9. '</td>
							   <td align="center" valign="top">' .$kd10. '</td> 
							   <td align="center" valign="top">' .$kd11. '</td> 
							   <td align="center" valign="top">' .$kd12. '</td>  
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
				
		}

		$hasil->free_result();

		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,1)='4' and bulan='$bulan' AND jns_ang='$jns_ang'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

		$cRet .= '<tr>
							   <td align="right" valign="top" colspan="13">JUMLAH PENDAPATAN</td>
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							   </tr>';
		}
			$sql = "SELECT z.kd_skpd, z.kd_kegiatan AS kd_kegiatan,z.kode AS kd_rek,left(z.kd_kegiatan,1) as kd1,SUBSTRING(z.kd_kegiatan,3,2) as kd2, left(kd_skpd,17) as kd3, SUBSTRING(z.kd_kegiatan,6,2) as kd4,SUBSTRING(z.kd_kegiatan,9,4) as kd5, z.kd6, z.kd7 as kd7, z.kd8 as kd8, z.kd9 as kd9,z.kd10,z.kd11,kd12,z.nama AS nm_rek,SUM (z.anggaran) AS anggaran,SUM (z.reali) AS sd_bulan_ini,sumber, jns_ang FROM ( 

				SELECT a.kd_skpd,b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,'' as kd6, '' as kd7, '' as kd8, '' as kd9,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7) $where and left(a.kd_rek6,1)='5' and bulan='$bulan' group by b.kd_program,b.nm_program,a.kd_skpd,SUBSTRING(b.kd_program,6,2), a.jns_ang
				union all 
				SELECT a.kd_skpd,b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,'' as kd6,''as kd7, '' as kd8, '' as kd9,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang  FROM data_realisasi_pemkot a inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan and b.kd_skpd = a.kd_skpd AND a.jns_ang=b.jns_ang $where and left(a.kd_rek6,1)='5' and bulan='$bulan' group by b.kd_kegiatan,b.nm_kegiatan,a.kd_skpd,SUBSTRING(b.kd_kegiatan,6,2),SUBSTRING(b.kd_kegiatan,9,4), a.jns_ang
								
				UNION ALL
				----kegiatan 
				SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan AS kd_kegiatan,LEFT (a.kd_rek6,1) AS kode,b.nm_sub_kegiatan AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,14,2) as kd6 ,'' as kd7, '' as kd8, '' as kd9 ,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_sub_kegiatan b ON b.kd_sub_kegiatan =a.kd_sub_kegiatan $where AND LEFT (a.kd_rek6,1)='5' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,b.nm_sub_kegiatan,LEFT (a.kd_rek6,1) ,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang
				UNION ALL
				SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,1) AS kd_kegiatan,LEFT (a.kd_rek6,1) AS kode,b.nm_rek1 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,14,2) as kd6, LEFT (a.kd_rek6,1) as kd7, '' as kd8, '' as kd9 ,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek1 b ON b.kd_rek1 =LEFT (a.kd_rek6,1) $where AND LEFT (a.kd_rek6,1)='5' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,1),b.nm_rek1,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang
				---sub kegiatan 
				UNION ALL 
				SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,2) AS kd_kegiatan,LEFT (a.kd_rek6,2) AS kode,b.nm_rek2 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,14,2) as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, '' as kd9 ,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek2 b ON b.kd_rek2 =LEFT (a.kd_rek6,2) $where AND LEFT (a.kd_rek6,1)='5' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,2),b.nm_rek2 ,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1) ,SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang
				UNION ALL 
				----akun3 
				SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,4) AS kd_kegiatan,LEFT (a.kd_rek6,4) AS kode,b.nm_rek3 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,14,2) as kd6,LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, SUBSTRING (a.kd_rek6,3,2) as kd9,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek3 b ON b.kd_rek3 =LEFT (a.kd_rek6,4) $where AND LEFT (a.kd_rek6,1)='5' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,4),b.nm_rek3,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2) ,SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang
				UNION ALL 
				----akun4 
				SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,6) AS kd_kegiatan,LEFT (a.kd_rek6,6) AS kode,b.nm_rek4 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,14,2) as kd6,LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, SUBSTRING (a.kd_rek6,3,2) as kd9 , SUBSTRING(a.kd_rek6,5,2) as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek4 b ON b.kd_rek4 =LEFT (a.kd_rek6,6) $where AND LEFT (a.kd_rek6,1)='5' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,6),b.nm_rek4,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2),SUBSTRING(a.kd_rek6,5,2),SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang
                 UNION ALL 
				----akun5 
				SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,8) AS kd_kegiatan,LEFT (a.kd_rek6,8) AS kode,b.nm_rek5 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,14,2) as kd6,LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, SUBSTRING (a.kd_rek6,3,2) as kd9, SUBSTRING(a.kd_rek6,5,2) as kd10, SUBSTRING(a.kd_rek6,7,2) as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek5 b ON b.kd_rek5 =LEFT (a.kd_rek6,8) $where AND LEFT (a.kd_rek6,1)='5' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,8),b.nm_rek5,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2) ,SUBSTRING(a.kd_rek6,5,2),SUBSTRING(a.kd_rek6,7,2),SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang 
                UNION ALL 
				----akun6 
				SELECT a.kd_skpd AS kd_skpd,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,12) AS kd_kegiatan,a.kd_rek6 AS kode,b.nm_rek6 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,SUBSTRING(a.kd_sub_kegiatan,14,2) as kd6,LEFT (a.kd_rek6,1) AS kd7,SUBSTRING (a.kd_rek6,2,1) AS kd8,SUBSTRING (a.kd_rek6,3,2) AS kd9, SUBSTRING(a.kd_rek6,5,2) as kd10, SUBSTRING(a.kd_rek6,7,2) as kd11, SUBSTRING(a.kd_rek6,9,4) as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek6 b ON b.kd_rek6 =a.kd_rek6 $where AND LEFT (a.kd_rek6,1)='5' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_rek6,b.nm_rek6,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2),SUBSTRING(a.kd_rek6,5,2),SUBSTRING(a.kd_rek6,7,2),SUBSTRING(a.kd_rek6,9,4) ,SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang
				) z WHERE z.jns_ang='$jns_ang'
				GROUP BY kd_kegiatan,kode,nama,sumber ,kd_skpd,z.kd7,z.kd8,z.kd9,z.kd10,z.kd11,z.kd12 ,z.kd6, z.jns_ang
				ORDER BY kd_kegiatan,kode,nama";
		

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sumber = $row->sumber;
			$kd_skpd = $row->kd_skpd;
			$kd1 	= $row->kd1;
			$kd2 	= $row->kd2;
			$kd3 	= $row->kd3;
			$kd4	= $row->kd4;
			$kd5	= $row->kd5;
			$kd6 	= $row->kd6;
			$kd7 	= $row->kd7;
			$kd8 	= $row->kd8;
			$kd9 	= $row->kd9;
			$kd10 	= $row->kd10;
			$kd11 	= $row->kd11;
			$kd12 	= $row->kd12;



			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			if ($sumber == 1210102){
				$sumber = "DAU";
			}else if($sumber == 12202){
				$sumber = "Bantuan Keuangan";
			}else if($sumber == 11416){
				$sumber = "Pendapatan dari BLUD";
			}else if($sumber == 221010418){
				$sumber = "DAK Non Fisik-Dana Yanminduk";
			}else if($sumber == 11){
				$sumber = "PAD";
			}else if($sumber == 2210104){
				$sumber = "DAK Non Fisik";
			}else if($sumber == 2210102){
				$sumber = "DAU";
			}else if($sumber == 1){
				$sumber = "DANA UMUM";
			}else{
				$sumber = "";
			}

					$cRet .= '<tr>
								<td align="center" width="3%" valign="top">' .$kd1. '</td> 
								<td align="center" width="4%" valign="top">' .$kd2. '</td>
								<td align="center" width="10%" valign="top">' .$kd3. '</td> 
								<td align="center" width="4%" valign="top">' .$kd4. '</td> 
								<td align="center" width="5%" valign="top">' .$kd5. '</td> 
								<td align="center" width="4%" valign="top">' .$kd6. '</td> 
								<td align="center" width="3%" valign="top">' .$kd7. '</td> 
								<td align="center" width="3%" valign="top">' .$kd8. '</td> 
								<td align="center" width="4%" valign="top">' .$kd9. '</td> 
								<td align="center" width="4%" valign="top">' .$kd10. '</td> 
								<td align="center" width="4%" valign="top">' .$kd11. '</td> 
								<td align="center" width="4%" valign="top">' .$kd12. '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td> 
								<td align="right" valign="top"></td>
							</tr>';
		}
        
				$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,1) in ('5') and bulan='$bulan' AND jns_ang='$jns_ang'";
				$hasil = $this->db->query($sql);
				foreach ($hasil->result() as $row) {

				$nil_ang13 = $row->anggaran;
				$sd_bulan_ini13 = $row->sd_bulan_ini;
				$sisa13 = $sd_bulan_ini13 - $nil_ang13;
				$persen13 = empty($nil_ang13) || $nil_ang13 == 0 ? 0 : $sd_bulan_ini13 / $nil_ang13 * 100;
				$sisa133 = $sisa13 < 0 ? $sisa13 * -1 : $sisa13;
				$y = $sisa13 < 0 ? '(' : '';
				$z = $sisa13 < 0 ? ')' : '';

				$cRet .= '<tr>
						<td align="right" colspan="13"  valign="top">JUMLAH BELANJA</td> 
						<td align="right" valign="top">' . number_format($nil_ang13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . number_format($sd_bulan_ini13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . $y . ' ' . number_format($sisa133, "2", ",", ".") . ' ' . $z . '</td> 
						<td align="right" valign="top">' . number_format($persen13, "2", ",", ".") . '</b></td> 
						<td align="right" valign="top"></td> 
						<td align="right" valign="top"></td>
					</tr>';

				}

				$sql = "SELECT 
						SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
						SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
						SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
						SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
						SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
						FROM
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM(nilai_ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where kd_skpd = '$kd_skpd' and bulan='$bulan' and LEFT(kd_ang,1) IN ('4','5','6') AND jns_ang='$jns_ang'
						GROUP BY LEFT(kd_ang,1)) a;
						";
					$hasil = $this->db->query($sql);
					foreach ($hasil->result() as $row) {
						$ang_surplus = $row->ang_surplus;
						$nil_surplus = $row->nil_surplus;
					}
					$sisa_surplus = $ang_surplus - $nil_surplus;
					if (($ang_surplus == 0) || ($ang_surplus == '')) {
						$persen_surplus = 0;
					} else {
						$persen_surplus = $nil_surplus / $ang_surplus * 100;
					}
					$hasil->free_result();
					if ($ang_surplus < 0) {
						$ang_surplus1 = $ang_surplus * -1;
						$a = '(';
						$b = ')';
					} else {
						$ang_surplus1 = $ang_surplus;
						$a = '';
						$b = '';
					}
					if ($nil_surplus < 0) {
						$nil_surplus1 = $nil_surplus * -1;
						$c = '(';
						$d = ')';
					} else {
						$nil_surplus1 = $nil_surplus;
						$c = '';
						$d = '';
					}
					if ($sisa_surplus < 0) {
						$sisa_surplus1 = $sisa_surplus * -1;
						$e = '(';
						$f = ')';
					} else {
						$sisa_surplus1 = $sisa_surplus;
						$e = '';
						$f = '';
					}

					
					$cRet .= '<tr>
						<td align="right" colspan="13" valign="top">TOTAL SURPLUS / (DEFISIT)</td> 
						<td align="right" valign="top">' . $a . '' . number_format($ang_surplus1, "2", ",", ".") . '' . $b . '</td> 
						<td align="right" valign="top">' . $c . '' . number_format($nil_surplus1, "2", ",", ".") . '' . $d . '</td> 
						<td align="right" valign="top">' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</td> 
						<td align="right" valign="top">' . number_format($persen_surplus, "2", ",", ".") . '</td> 
						<td align="right" valign="top"></td> 
						<td align="right" valign="top"></td>
					</tr>';
					
		$hasil->free_result();
		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

		$sql = "SELECT z.kd_skpd, z.kd_kegiatan AS kd_kegiatan,z.kode AS kd_rek,left(z.kd_kegiatan,1) as kd1,SUBSTRING(z.kd_kegiatan,3,2) as kd2, left(kd_skpd,17) as kd3, SUBSTRING(z.kd_kegiatan,6,2) as kd4,'0.00' as kd5, z.kd6, z.kd7 as kd7, z.kd8 as kd8, z.kd9 as kd9,z.kd10,z.kd11,kd12,z.nama AS nm_rek,SUM (z.anggaran) AS anggaran,SUM (z.reali) AS sd_bulan_ini,sumber, jns_ang FROM ( 

			SELECT a.kd_skpd,b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,'' as kd6, '' as kd7, '' as kd8, '' as kd9,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7) $where and left(a.kd_rek6,2)='61' and bulan='$bulan' group by b.kd_program,b.nm_program,a.kd_skpd,SUBSTRING(b.kd_program,6,2), a.jns_ang
			union all 
			SELECT a.kd_skpd,b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber,'' as kd6,''as kd7, '' as kd8, '' as kd9,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan and b.kd_skpd = a.kd_skpd AND a.jns_ang=b.jns_ang $where and left(a.kd_rek6,2)='61' and bulan='$bulan' group by b.kd_kegiatan,b.nm_kegiatan,a.kd_skpd,SUBSTRING(b.kd_kegiatan,6,2),SUBSTRING(b.kd_kegiatan,9,4), a.jns_ang
							
			UNION ALL
			----kegiatan 
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan AS kd_kegiatan,LEFT (a.kd_rek6,1) AS kode,b.nm_sub_kegiatan AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,'0.00' as kd6 ,'' as kd7, '' as kd8, '' as kd9 ,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_sub_kegiatan b ON b.kd_sub_kegiatan =a.kd_sub_kegiatan $where AND LEFT (a.kd_rek6,2)='61' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,b.nm_sub_kegiatan,LEFT (a.kd_rek6,1) ,a.kd_skpd,SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang
			UNION ALL
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,1) AS kd_kegiatan,LEFT (a.kd_rek6,1) AS kode,b.nm_rek1 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,'0.00' as kd6, LEFT (a.kd_rek6,1) as kd7, '' as kd8, '' as kd9 ,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek1 b ON b.kd_rek1 =LEFT (a.kd_rek6,1) $where AND LEFT (a.kd_rek6,2)='61' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,1),b.nm_rek1,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang
			---sub kegiatan 
			UNION ALL 
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,2) AS kd_kegiatan,LEFT (a.kd_rek6,2) AS kode,b.nm_rek2 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,'0.00' as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, '' as kd9 ,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek2 b ON b.kd_rek2 =LEFT (a.kd_rek6,2) $where AND LEFT (a.kd_rek6,2)='61' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,2),b.nm_rek2 ,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1) ,SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang
			UNION ALL 
			----akun3 
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,4) AS kd_kegiatan,LEFT (a.kd_rek6,4) AS kode,b.nm_rek3 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,'0.00' as kd6,LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, SUBSTRING (a.kd_rek6,3,2) as kd9,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek3 b ON b.kd_rek3 =LEFT (a.kd_rek6,4) $where AND LEFT (a.kd_rek6,2)='61' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,4),b.nm_rek3,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2) ,SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang
			UNION ALL 
			----akun4 
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,6) AS kd_kegiatan,LEFT (a.kd_rek6,6) AS kode,b.nm_rek4 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,'0.00' as kd6,LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, SUBSTRING (a.kd_rek6,3,2) as kd9 , SUBSTRING(a.kd_rek6,5,2) as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek4 b ON b.kd_rek4 =LEFT (a.kd_rek6,6) $where AND LEFT (a.kd_rek6,2)='61' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,6),b.nm_rek4,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2),SUBSTRING(a.kd_rek6,5,2),SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang
             UNION ALL 
			----akun5 
			SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,8) AS kd_kegiatan,LEFT (a.kd_rek6,8) AS kode,b.nm_rek5 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,'0.00' as kd6,LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, SUBSTRING (a.kd_rek6,3,2) as kd9, SUBSTRING(a.kd_rek6,5,2) as kd10, SUBSTRING(a.kd_rek6,7,2) as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek5 b ON b.kd_rek5 =LEFT (a.kd_rek6,8) $where AND LEFT (a.kd_rek6,2)='61' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,8),b.nm_rek5,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2) ,SUBSTRING(a.kd_rek6,5,2),SUBSTRING(a.kd_rek6,7,2),SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang 
            UNION ALL 
			----akun6 
			SELECT a.kd_skpd AS kd_skpd,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,12) AS kd_kegiatan,a.kd_rek6 AS kode,b.nm_rek6 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,'0.00' as kd6,LEFT (a.kd_rek6,1) AS kd7,SUBSTRING (a.kd_rek6,2,1) AS kd8,SUBSTRING (a.kd_rek6,3,2) AS kd9, SUBSTRING(a.kd_rek6,5,2) as kd10, SUBSTRING(a.kd_rek6,7,2) as kd11, SUBSTRING(a.kd_rek6,9,4) as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek6 b ON b.kd_rek6 =a.kd_rek6 $where AND LEFT (a.kd_rek6,2)='61' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_rek6,b.nm_rek6,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2),SUBSTRING(a.kd_rek6,5,2),SUBSTRING(a.kd_rek6,7,2),SUBSTRING(a.kd_rek6,9,4) ,SUBSTRING(a.kd_sub_kegiatan,14,2),a.jns_ang
			) z WHERE z.jns_ang='$jns_ang'
			GROUP BY kd_kegiatan,kode,nama,sumber ,kd_skpd,z.kd7,z.kd8,z.kd9,z.kd10,z.kd11,z.kd12 ,z.kd6, z.jns_ang
			ORDER BY kd_kegiatan,kode,nama";
	

	$hasil = $this->db->query($sql);
	foreach ($hasil->result() as $row) {
		$kd_kegiatan = $row->kd_kegiatan;
		$kd_rek = $row->kd_rek;
		$nm_rek = $row->nm_rek;
		$nil_ang = $row->anggaran;
		$kd_skpd = $row->kd_skpd;
		$kd1 	= $row->kd1;
		$kd2 	= $row->kd2;
		$kd3 	= $row->kd3;
		$kd4	= $row->kd4;
		$kd5	= $row->kd5;
		$kd6 	= $row->kd6;
		$kd7 	= $row->kd7;
		$kd8 	= $row->kd8;
		$kd9 	= $row->kd9;
		$kd10 	= $row->kd10;
		$kd11 	= $row->kd11;
		$kd12 	= $row->kd12;

		$sd_bulan_ini = $row->sd_bulan_ini;
		$sisa = $sd_bulan_ini - $nil_ang;
		$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
		$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
		$a = $sisa < 0 ? '(' : '';
		$b = $sisa < 0 ? ')' : '';

			$cRet .= '<tr>
							<td align="center" valign="top">' .$kd1. '</td> 
							<td align="center" valign="top">' .$kd2. '</td>
							<td align="center" valign="top">' .$kd3. '</td> 
							<td align="center" valign="top">' .$kd4. '</td> 
							<td align="center" valign="top">' .$kd5. '</td> 
							<td align="center" valign="top">' .$kd6. '</td> 
							<td align="center" valign="top">' .$kd7. '</td> 
							<td align="center" valign="top">' .$kd8. '</td> 
							<td align="center" valign="top">' .$kd9. '</td>
							<td align="center" valign="top">' .$kd10. '</td> 
							<td align="center" valign="top">' .$kd11. '</td> 
							<td align="center" valign="top">' .$kd12. '</td>  
							<td align="left"  valign="top">' . $nm_rek . '</td> 
							<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							<td align="right" valign="top"></td>
							<td align="right" valign="top"></td>
						</tr>';
	}

		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,2) in ('61') and bulan='$bulan' AND jns_ang='$jns_ang'";
				$hasil = $this->db->query($sql);
				foreach ($hasil->result() as $row) {

				$nil_ang13 = $row->anggaran;
				$sd_bulan_ini13 = $row->sd_bulan_ini;
				$sisa13 = $sd_bulan_ini13 - $nil_ang13;
				$persen13 = empty($nil_ang13) || $nil_ang13 == 0 ? 0 : $sd_bulan_ini13 / $nil_ang13 * 100;
				$sisa133 = $sisa13 < 0 ? $sisa13 * -1 : $sisa13;
				$y = $sisa13 < 0 ? '(' : '';
				$z = $sisa13 < 0 ? ')' : '';

				$cRet .= '<tr>
						<td align="right" colspan = "13"  valign="top">JUMLAH PENERIMAAN PEMBIAYAAN</td> 
						<td align="right" valign="top">' . number_format($nil_ang13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . number_format($sd_bulan_ini13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . $y . ' ' . number_format($sisa133, "2", ",", ".") . ' ' . $z . '</td> 
						<td align="right" valign="top">' . number_format($persen13, "2", ",", ".") . '</td> 
						<td align="right" valign="top"></td> 
						<td align="right" valign="top"></td>
					</tr>';
				}


		$hasil->free_result();
		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

		
			$sql = "SELECT z.kd_skpd, z.kd_kegiatan AS kd_kegiatan,z.kode AS kd_rek,left(z.kd_kegiatan,1) as kd1,SUBSTRING(z.kd_kegiatan,3,2) as kd2, left(kd_skpd,17) as kd3, SUBSTRING(z.kd_kegiatan,6,2) as kd4,'0.00' as kd5,z.kd6 as kd6, z.kd7 as kd7, z.kd8 as kd8, z.kd9 as kd9,z.kd10,z.kd11,kd12,z.nama AS nm_rek,SUM (z.anggaran) AS anggaran,SUM (z.reali) AS sd_bulan_ini,sumber, jns_ang FROM ( 

				SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,2) AS kd_kegiatan,LEFT (a.kd_rek6,2) AS kode,b.nm_rek2 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,'0.00' as kd6, LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, '' as kd9 ,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek2 b ON b.kd_rek2 =LEFT (a.kd_rek6,2) $where AND LEFT (a.kd_rek6,2)='62' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,2),b.nm_rek2 ,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1) ,SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang 
				UNION ALL
				----akun3 
				SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,4) AS kd_kegiatan,LEFT (a.kd_rek6,4) AS kode,b.nm_rek3 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,'0.00' as kd6,LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, SUBSTRING (a.kd_rek6,3,2) as kd9,'' as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek3 b ON b.kd_rek3 =LEFT (a.kd_rek6,4) $where AND LEFT (a.kd_rek6,2)='62' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,4),b.nm_rek3,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2) ,SUBSTRING(a.kd_sub_kegiatan,14,2),a.jns_ang 
				UNION ALL 
				----akun4 
				SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,6) AS kd_kegiatan,LEFT (a.kd_rek6,6) AS kode,b.nm_rek4 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,'0.00' as kd6,LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, SUBSTRING (a.kd_rek6,3,2) as kd9 , SUBSTRING(a.kd_rek6,5,2) as kd10, '' as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek4 b ON b.kd_rek4 =LEFT (a.kd_rek6,6) $where AND LEFT (a.kd_rek6,2)='62' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,6),b.nm_rek4,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2),SUBSTRING(a.kd_rek6,5,2),SUBSTRING(a.kd_sub_kegiatan,14,2),a.jns_ang 
				UNION ALL 
				----akun5
				SELECT a.kd_skpd as kd_skpd ,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,8) AS kd_kegiatan,LEFT (a.kd_rek6,8) AS kode,b.nm_rek5 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,'0.00' as kd6,LEFT (a.kd_rek6,1) as kd7, SUBSTRING (a.kd_rek6,2,1) as kd8, SUBSTRING (a.kd_rek6,3,2) as kd9, SUBSTRING(a.kd_rek6,5,2) as kd10, SUBSTRING(a.kd_rek6,7,2) as kd11, '' as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek5 b ON b.kd_rek5 =LEFT (a.kd_rek6,8) $where AND LEFT (a.kd_rek6,2)='62' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,LEFT (a.kd_rek6,8),b.nm_rek5,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2) ,SUBSTRING(a.kd_rek6,5,2),SUBSTRING(a.kd_rek6,7,2),SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang
				UNION ALL 
				----akun6 
				SELECT a.kd_skpd AS kd_skpd,a.kd_sub_kegiatan+'.'+LEFT (a.kd_rek6,12) AS kd_kegiatan,a.kd_rek6 AS kode,b.nm_rek6 AS nama,SUM (a.nilai_ang) AS anggaran,SUM (a.real_spj) AS reali,'' sumber,'0.00' as kd6,LEFT (a.kd_rek6,1) AS kd7,SUBSTRING (a.kd_rek6,2,1) AS kd8,SUBSTRING (a.kd_rek6,3,2) AS kd9, SUBSTRING(a.kd_rek6,5,2) as kd10, SUBSTRING(a.kd_rek6,7,2) as kd11, SUBSTRING(a.kd_rek6,9,4) as kd12, a.jns_ang as jns_ang FROM data_realisasi_pemkot a INNER JOIN ms_rek6 b ON b.kd_rek6 =a.kd_rek6 $where AND LEFT (a.kd_rek6,2)='62' AND bulan='$bulan' GROUP BY a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_rek6,b.nm_rek6,a.kd_skpd,SUBSTRING (a.kd_rek6,2,1),LEFT (a.kd_rek6,1),SUBSTRING (a.kd_rek6,3,2),SUBSTRING(a.kd_rek6,5,2),SUBSTRING(a.kd_rek6,7,2),SUBSTRING(a.kd_rek6,9,4) ,SUBSTRING(a.kd_sub_kegiatan,14,2), a.jns_ang 
				) z WHERE z.jns_ang='$jns_ang'
				GROUP BY kd_kegiatan,kode,nama,sumber ,kd_skpd,z.kd7,z.kd8,z.kd9,z.kd10,z.kd11,z.kd12 ,z.kd6, z.jns_ang
				ORDER BY kd_kegiatan,kode,nama";
		

		$hasil = $this->db->query($sql);
	foreach ($hasil->result() as $row) {
		$kd_kegiatan = $row->kd_kegiatan;
		$kd_rek = $row->kd_rek;
		$nm_rek = $row->nm_rek;
		$nil_ang = $row->anggaran;
		$kd_skpd = $row->kd_skpd;
		$kd1 	= $row->kd1;
		$kd2 	= $row->kd2;
		$kd3 	= $row->kd3;
		$kd4	= $row->kd4;
		$kd5	= $row->kd5;
		$kd6 	= $row->kd6;
		$kd7 	= $row->kd7;
		$kd8 	= $row->kd8;
		$kd9 	= $row->kd9;
		$kd10 	= $row->kd10;
		$kd11 	= $row->kd11;
		$kd12 	= $row->kd12;

		$sd_bulan_ini = $row->sd_bulan_ini;
		$sisa = $sd_bulan_ini - $nil_ang;
		$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
		$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
		$a = $sisa < 0 ? '(' : '';
		$b = $sisa < 0 ? ')' : '';

			$cRet .= '<tr>
							<td align="center" valign="top">' .$kd1. '</td> 
							<td align="center" valign="top">' .$kd2. '</td>
							<td align="center" valign="top">' .$kd3. '</td> 
							<td align="center" valign="top">' .$kd4. '</td> 
							<td align="center" valign="top">' .$kd5. '</td> 
							<td align="center" valign="top">' .$kd6. '</td> 
							<td align="center" valign="top">' .$kd7. '</td> 
							<td align="center" valign="top">' .$kd8. '</td> 
							<td align="center" valign="top">' .$kd9. '</td>
							<td align="center" valign="top">' .$kd10. '</td> 
							<td align="center" valign="top">' .$kd11. '</td> 
							<td align="center" valign="top">' .$kd12. '</td>  
							<td align="left"  valign="top">' . $nm_rek . '</td> 
							<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							<td align="right" valign="top"></td>
							<td align="right" valign="top"></td>
						</tr>';
	}
		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,2) in ('62') and bulan='$bulan' AND jns_ang='$jns_ang'";
				$hasil = $this->db->query($sql);
				foreach ($hasil->result() as $row) {

				$nil_ang13 = $row->anggaran;
				$sd_bulan_ini13 = $row->sd_bulan_ini;
				$sisa13 = $sd_bulan_ini13 - $nil_ang13;
				$persen13 = empty($nil_ang13) || $nil_ang13 == 0 ? 0 : $sd_bulan_ini13 / $nil_ang13 * 100;
				$sisa133 = $sisa13 < 0 ? $sisa13 * -1 : $sisa13;
				$y = $sisa13 < 0 ? '(' : '';
				$z = $sisa13 < 0 ? ')' : '';

				$cRet .= '<tr>
						<td align="right" colspan = "13"  valign="top">JUMLAH PENGELUARAN PEMBIAYAAN</td> 
						<td align="right" valign="top">' . number_format($nil_ang13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . number_format($sd_bulan_ini13, "2", ",", ".") . '</td> 
						<td align="right" valign="top">' . $y . ' ' . number_format($sisa133, "2", ",", ".") . ' ' . $z . '</td> 
						<td align="right" valign="top">' . number_format($persen13, "2", ",", ".") . '</td> 
						<td align="right" valign="top"></td> 
						<td align="right" valign="top"></td>
					</tr>';
				}

		//pembiayaan netto
		$sql = "SELECT isnull(SUM (z.anggaran61),0)-isnull(SUM (z.anggaran62),0) AS anggaran,isnull(SUM (z.sd_bulan_ini61),0)-isnull(SUM (z.sd_bulan_ini62),0) AS sd_bulan_ini, jns_ang FROM (
			SELECT SUM (a.nilai_ang) anggaran61,SUM (a.real_spj) sd_bulan_ini61,0 AS anggaran62,0 AS sd_bulan_ini62, a.jns_ang as jns_ang FROM data_realisasi_pemkot a $where AND LEFT (a.kd_rek6,2)='61' AND bulan='$bulan' GROUP BY a.jns_ang UNION
			SELECT 0 AS anggaran61,0 AS sd_bulan_ini61,SUM (b.nilai_ang) anggaran62,SUM (b.real_spj) sd_bulan_ini62, b.jns_ang as jns_ang FROM data_realisasi_pemkot b $where1 AND LEFT (b.kd_rek6,2)='62' AND bulan='$bulan' GROUP BY b.jns_ang) z WHERE z.jns_Ang='$jns_ang' GROUP BY z.jns_ang";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang12 = $row->anggaran;
			$sd_bulan_ini12 = $row->sd_bulan_ini;
			$sisa12 = $sd_bulan_ini12 - $nil_ang12;
			$persen12 = empty($nil_ang12) || $nil_ang12 == 0 ? 0 : $sd_bulan_ini12 / $nil_ang12 * 100;
			$sisa12 = $sisa12 < 0 ? $sisa12 * -1 : $sisa12;
			$a = $sisa12 < 0 ? '(' : '';
			$b = $sisa12 < 0 ? ')' : '';

			//if ($nil_ang12 != 0) {
				$cRet .= '<tr>
								<td align="right" colspan = "13"  valign="top">PEMBIAYAAN NETO</td> 
								<td align="right" valign="top">' . number_format($nil_ang12, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini12, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa12, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen12, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
							</tr>';
				
		}
		//end


		$sql = "SELECT (isnull(SUM (z.anggaran_4),0)-isnull(SUM (z.anggaran_5),0))+(isnull(SUM (z.anggaran_61),0)-isnull(SUM (z.anggaran_62),0)) AS anggaran,(isnull(SUM (z.sd_bulan_ini_4),0)-isnull(SUM (z.sd_bulan_ini_5),0))+(isnull(SUM (z.sd_bulan_ini_61),0)-isnull(SUM (z.sd_bulan_ini_62),0)) AS sd_bulan_ini, jns_ang FROM (
			SELECT SUM (a.nilai_ang) anggaran_4,SUM (a.real_spj) sd_bulan_ini_4,0 anggaran_5,0 AS sd_bulan_ini_5,0 anggaran_61,0 AS sd_bulan_ini_61,0 AS anggaran_62,0 AS sd_bulan_ini_62, a.jns_ang as jns_ang FROM data_realisasi_pemkot a $where AND LEFT (a.kd_ang,1) IN ('4') AND bulan='$bulan' GROUP BY a.jns_ang
             UNION
			SELECT 0 AS anggaran_4,0 AS sd_bulan_ini_4,SUM (a.nilai_ang) anggaran_5,SUM (a.real_spj) sd_bulan_ini_5,0 AS anggaran_61,0 AS sd_bulan_ini_61,0 AS anggaran_62,0 AS sd_bulan_ini_62, a.jns_ang as jns_ang FROM data_realisasi_pemkot a $where AND LEFT (a.kd_ang,1) IN ('5') AND bulan='$bulan' GROUP BY a.jns_ang
            UNION
			SELECT 0 AS anggaran_4,0 AS sd_bulan_ini_4,0 AS anggaran_5,0 AS sd_bulan_ini_5,SUM (a.nilai_ang) anggaran_61,SUM (a.real_spj) sd_bulan_ini_61,0 AS anggaran_62,0 AS sd_bulan_ini_62, a.jns_ang as jns_ang FROM data_realisasi_pemkot a $where AND LEFT (a.kd_ang,2) IN ('61') AND bulan='$bulan' GROUP BY a.jns_ang
             UNION
			SELECT 0 AS anggaran_4,0 AS sd_bulan_ini_4,0 AS anggaran_5,0 AS sd_bulan_ini_5,0 AS anggaran_61,0 AS sd_bulan_ini_61,SUM (a.nilai_ang) anggaran_62,SUM (a.real_spj) sd_bulan_ini_62, a.jns_ang as jns_ang FROM data_realisasi_pemkot a $where AND LEFT (a.kd_ang,2) IN ('62') AND bulan='$bulan' GROUP BY a.jns_ang)z WHERE z.jns_ang='$jns_ang' GROUP BY z.jns_ang";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang145 = $row->anggaran;
			$sd_bulan_ini145 = $row->sd_bulan_ini;
			$sisa1 = $sd_bulan_ini145 - $nil_ang145;
			$persen145 = empty($nil_ang145) || $nil_ang145 == 0 ? 0 : $sd_bulan_ini145 / $nil_ang145 * 100;

			// $a45 = $sisa1 < 0 ? '(' : '';
			// $b45 = $sisa1 < 0 ? ')' : '';
			// $sisa1145 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;

            if ($sisa1 < 0) {
                $sisa1145 = $sisa1 * -1;
                $a45 = '(';
                $b45 = ')';
            } else {
                $sisa1145 = $sisa1;
                $a45 = '';
                $b45 = '';
            }

			$a445 = $nil_ang145 < 0 ? '(' : '';
			$b445 = $nil_ang145 < 0 ? ')' : '';
			$nil_ang1455 = $nil_ang145 < 0 ? $nil_ang145 * -1 : $nil_ang145;

			$a455 = $sd_bulan_ini145 < 0 ? '(' : '';
			$b455 = $sd_bulan_ini145 < 0 ? ')' : '';
			$sd_bulan_ini1455 = $sd_bulan_ini145 < 0 ? $sd_bulan_ini145 * -1 : $sd_bulan_ini145;


			$cRet .= '<tr>
								<td align="right" colspan="13" valign="top">SISA LEBIH PEMBIAYAAN ANGGARAN (SILPA)</td> 
								<td align="right" valign="top">' . $a445 . ' ' . number_format($nil_ang1455, "2", ",", ".") . ' ' . $b445 . '</td> 
								<td align="right" valign="top">' . $a455 . ' ' . number_format($sd_bulan_ini1455, "2", ",", ".") . ' ' . $b455 . '</td> 
								<td align="right" valign="top">' . $a45 . ' ' . number_format($sisa1145, "2", ",", ".") . ' ' . $b45 . '</td> 
								<td align="right" valign="top">' . number_format($persen145, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
							</tr>';
		}
		$hasil->free_result();

		$cRet .= "</table>";

		$tanggal = $tglttd == '-' ? '' : 'Pontianak, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		
			$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='8.01.0.00.0.00.01.0000'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowsc) {
				$kab     = $rowsc->kab_kota;
				$daerah  = $rowsc->daerah;
			}

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip ='$ttd1' and (kode ='PA' or kode='PPKD' or kode='SETDA' or kode ='BUPATI')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}
			if ($ttd1 != '1') {
				$xx = "<u>";
				$xy = "</u>";
				$nipxx = $nip;
				$nipx = "NIP. ";
			} else {
				$xx = "";
				$xy = "";
				$nipxx = "";
				$nipx = "";
			}

			if ($tglttd == 1) {
				$tgltd = '';
			} else {
				$tgltd = $this->custom->tanggal_format_indonesia($tglttd);
			}

			if ($nip == '00000000 000000 0 000'){
				$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > Melawi , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
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
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > Melawi , ' . $tgltd . '</TD>
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
									<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="50%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
								</TR>
								</TABLE><br/>';
				}

		$data['prev'] = $cRet;
		$judul = 'PERBUP_LAMP_I.2 ';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf_lamp('', $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	// ================================= END =============================
}
