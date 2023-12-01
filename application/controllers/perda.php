<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


use mikehaertl\wkhtmlto\Pdf;

require_once('application/3rdparty/wkhtmltopdf/Pdf.php');

class Perda extends CI_Controller
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

	function cetak_lra_pemkot_33_permen($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $initx = '',  $ttd1 = '', $ttdperda = '', $label = '')
	{
		// echo ($ttd1);

		$lntahunang = $this->session->userdata('pcThang');
		$ttd2 = str_replace('n', ' ', $ttdperda);

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
		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		$initang = "nilai_ang";


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
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a
						";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = round($row->ang_surplus);
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
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,2) IN ('61','62') $where
						GROUP BY LEFT(kd_ang,2)) a;
						";


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = round($row->ang_netto);
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

			$sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = round($row->nil_ang);
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
								   <td align="right" valign="top"><b></b></td> 
								   <td align="right" valign="top"><b>' . ($nilai) . '</b></td> 
								   <td align="right" valign="top"><b></b></td> 
								   <td align="right" valign="top"><b></b></td> 
								</tr>';
					break;
				case 3:
					$cRet .= '<tr>
								   <td align="left" valign="top">' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",",".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2",",",".") . '</td> 
								   <td align="right" valign="top">' . number_format($sel, "2",",",".") . '</td> 
								   <td align="right" valign="top">' . number_format($persen, 2) . '</td> 
								</tr>';
					break;
				case 4:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2",",",".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2",",",".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($sel, "2",",",".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, 2) . '</b></td> 
								</tr>';
					break;
				case 5:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2",",",".") . '' . $b . '</b></td> 
								   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2",",",".") . '' . $d . '</b></td> 
								   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2",",",".") . '' . $f . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen_surplus, 2) . '</b></td> 
								</tr>';
					break;
				case 6;
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2",",",".") . '' . $h . '</b></td> 
								   <td align="right" valign="top" ><b>' . $i . '' . number_format($nil_netto1, "2",",",".") . '' . $j . '</b></td> 
								   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2",",",".") . '' . $l . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_netto, 2) . '</b></td> 
								</tr>';
					break;
				case 7;
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $m . '' . number_format(($ang_silpa1), "2",",",".") . '' . $n . '</b></td> 
								   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2",",",".") . '' . $p . '</b></td> 
								   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2", ",",".") . '' . $r . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format(($persen_silpa), "2", ",",".") . '</b></td> 
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

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$ttd2' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}


		if ($initx == '2') {
			$xx = "<u>";
			$xy = "</u>";
			$nipxx = '';
			$nipx = '';
			$jabatan1 = '';
			$namax1 = '';
			$pangkat1 = '';
		} else {
			$xx = "";
			$xy = "";
			$nipxx = $nip;
			$nipx = "NIP.";
			$jabatan1 = $jabatan;
			$namax1 = $namax;
			$pangkat1 = $pangkat;
		}
		if ($initx == '2') {
			$tempat = '';
			$tgltd = '';
		} else {
			$tempat = 'Melawi';
			$tgltd = $this->custom->tanggal_format_indonesia($ttd1);
		}
		$cRet .= '<br><br>
			<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" > ' . $tempat . ' , ' . $tgltd . '</TD>
						</TR>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" ><b>' . $jabatan1 . '</b></TD>
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
							<TD align="center" >' . $xx . '<b>' . $namax1 . '</b>' . $xy . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $pangkat1 . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
						</TR>
						</TABLE><br/>';


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


	function cetak_lra_pemkot_33_permen_periode($tgl1 = '', $tgl2 = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $initx = '',  $ttd1 = '', $ttdperda = '', $label = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$ttd2 = str_replace('n', ' ', $ttdperda);
		$tgl11 = $this->tukd_model->tanggal_format_indonesia($tgl1);
		$tgl21 = $this->tukd_model->tanggal_format_indonesia($tgl2);

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

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet = "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>PERIODE " . strtoupper($tgl11) . " S.D " . strtoupper($tgl21) . " <br> TAHUN $lntahunang</b></tr>
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

		// $sql = "SELECT 
		// 	SUM(CASE WHEN kd_rek='4' THEN (nil_ang) ELSE 0 END) - 
		// 	SUM(CASE WHEN kd_rek in ('5') THEN (nil_ang) ELSE 0 END) +
		// 	SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (nil_ang) ELSE 0 END) -
		// 	SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (nil_ang) ELSE 0 END)as ang_surplus,
		// 	SUM(CASE WHEN kd_rek='4' THEN (real_spj) ELSE 0 END) - 
		// 	SUM(CASE WHEN kd_rek in ('5') THEN (real_spj) ELSE 0 END) +
		// 	SUM(CASE WHEN left(kd_rek,2) in ('61') THEN (real_spj) ELSE 0 END) -
		// 	SUM(CASE WHEN left(kd_rek,2) in ('62') THEN (real_spj) ELSE 0 END) as nil_surplus
		// 	FROM
		// 	(SELECT LEFT(kd_ang,1) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,1) IN ('4','5','6') $where
		// 	GROUP BY LEFT(kd_ang,1)) a
		// 	";
		$sql = "SELECT 
				SUM(CASE WHEN kd_rek6='4' THEN nil_ang ELSE 0 END) - SUM(CASE WHEN kd_rek6='5' THEN nil_ang ELSE 0 END) as ang_surplus,
				SUM(CASE WHEN kd_rek6='4' THEN nilai ELSE 0 END) - SUM(CASE WHEN kd_rek6='5' THEN nilai ELSE 0 END) as nil_surplus
				 FROM (
			SELECT '4' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((kredit-debet)) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,1)='4' UNION ALL
			SELECT '5' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((debet-kredit)) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,1)='5' UNION ALL
			SELECT '61' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((debet-kredit)) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,2)='61' UNION ALL
			SELECT '62' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((debet-kredit)) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,2)='62'
			) oke";
		// return;

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

		// $sql = "SELECT 
		// 				SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
		// 				SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
		// 				FROM
		// 				(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,2) IN ('61','62') $where
		// 				GROUP BY LEFT(kd_ang,2)) a;
		// 				";


		$sql = "SELECT 
				SUM (CASE WHEN kd_rek6='61' THEN nil_ang ELSE 0 END) - SUM(CASE WHEN kd_rek6='62' THEN nil_ang ELSE 0 END)AS ang_netto,
				SUM (CASE WHEN kd_rek6='61' THEN nilai ELSE 0 END) - SUM(CASE WHEN kd_rek6='62' THEN nilai ELSE 0 END)AS nil_netto
				FROM (
			SELECT '61' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((debet-kredit)) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,2)='61' UNION ALL
			SELECT '62' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((kredit-debet)) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,2)='62'
			) oke";

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

		$sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi, jenis FROM map_lra_pemkot_periode ORDER BY urut";
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
			$jenis = $row->jenis;

			$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilai FROM data_jurnal_n_periode('$tgl1','$tgl2','$anggaran','$lntahunang') where (LEFT(kd_rek6,1) IN ($kode1) or LEFT(kd_rek6,2) IN ($kode2) or LEFT(kd_rek6,4) IN ($kode3) or LEFT(kd_rek6,6) IN ($kode4) or LEFT(kd_rek6,8) IN ($kode5)) $where";


			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilai;
				$sel = $nil_ang - $nilai;
			}
			$a = $sel < 0 ? '(' : '';
			$b = $sel < 0 ? ')' : '';
			$sel = ($sel < 0) ? $sel * -1 : $sel;

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
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2",",",".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2",",",".") . '</b></td> 
								   <td align="right" valign="top"><b>' . $a . '' . number_format($sel, "2",",",".") . '' . $b . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, 2) . '</b></td> 
								</tr>';
					break;
				case 3:
					$cRet .= '<tr>
								   <td align="left" valign="top">' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2",",",".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2",",",".") . '</td> 
								   <td align="right" valign="top">' . $a . '' . number_format($sel, "2",",",".") . '' . $b . '</td> 
								   <td align="right" valign="top">' . number_format($persen, 2) . '</td> 
								</tr>';
					break;
				case 4:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2",",",".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2",",",".") . '</b></td> 
								   <td align="right" valign="top"><b>' . $a . '' . number_format($sel, "2",",",".") . '' . $b . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, 2) . '</b></td> 
								</tr>';
					break;
				case 5:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . $a . '' . number_format($ang_surplus1, "2",",",".") . '' . $b . '</b></td> 
								   <td align="right" valign="top"><b>' . $c . '' . number_format($nil_surplus1, "2",",",".") . '' . $d . '</b></td> 
								   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2",",",".") . '' . $f . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen_surplus, 2) . '</b></td> 
								</tr>';
					break;
				case 6;
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $g . '' . number_format($ang_netto1, "2",",",".") . '' . $h . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($nil_netto1, "2",",",".") . '</b></td> 
								   <td align="right" valign="top" ><b>' . $k . '' . number_format($sisa_netto1, "2",",",".") . '' . $l . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format($persen_netto, 2) . '</b></td> 
								</tr>';
					break;
				case 7;
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="right"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top" ><b>' . $m . '' . number_format(($ang_silpa1), "2",",",".") . '' . $n . '</b></td> 
								   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_surplus1 + $nil_netto1, "2",",",".") . '' . $p . '</b></td> 
								   <td align="right" valign="top" ><b>' . $q . '' . number_format($sisa_silpa1, "2",",",".") . '' . $r . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format(($persen_silpa), 2) . '</b></td> 
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

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$ttd2' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}


		if ($initx == '2') {
			$xx = "<u>";
			$xy = "</u>";
			$nipxx = '';
			$nipx = '';
			$jabatan1 = '';
			$namax1 = '';
			$pangkat1 = '';
		} else {
			$xx = "";
			$xy = "";
			$nipxx = $nip;
			$nipx = "NIP.";
			$jabatan1 = $jabatan;
			$namax1 = $namax;
			$pangkat1 = $pangkat;
		}
		if ($initx == '2') {
			$tempat = '';
			$tgltd = '';
		} else {
			$tempat = 'Melawi';
			$tgltd = $this->custom->tanggal_format_indonesia($ttd1);
		}
		$cRet .= '<br><br>
			<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" > ' . $tempat . ' , ' . $tgltd . '</TD>
						</TR>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" ><b>' . $jabatan1 . '</b></TD>
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
							<TD align="center" >' . $xx . '<b>' . $namax1 . '</b>' . $xy . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $pangkat1 . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
						</TR>
						</TABLE><br/>';


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

	function cetak_lra_baru($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd1 = '', $tanggal_ttd = '', $ttdperda = '', $label = '')
	{

		$lntahunang = $this->session->userdata('pcThang');
		$ttd1 = str_replace('n', ' ', $ttdperda);
		// echo ($ttdperda);

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
		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		$initang = "nilai_ang";

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
						GROUP BY LEFT(kd_ang,1)) a
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
						GROUP BY LEFT(kd_ang,2))a
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
				$nil_ang = round($row->nil_ang);
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
								   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								</tr>';
					break;
				case 3:
					$cRet .= '<tr>
								   <td align="center" valign="top">' . $kode . '</b></td> 
								   <td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</td> 
								   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($sel, "2", ",", ".") . '</td> 
								   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								</tr>';
					break;
				case 4:
					$cRet .= '<tr>
								   <td align="center" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
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
								   <td align="right" valign="top" ><b>' . $m . '' . number_format(($ang_silpa1), "2", ",", ".") . '' . $n . '</b></td> 
								   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
								   <td align="right" valign="top" ><b>' . $q . '' . number_format(($sisa_silpa1), "2", ",", ".") . '' . $r . '</b></td> 
								   <td align="right" valign="top" ><b>' . number_format(($persen_silpa), "2", ",", ".") . '</b></td> 
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

	function cetak_lra90_objek($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $initx = '',  $ttd1 = '', $ttdperda = '', $label = '')
	{
		// echo ($ttd1);

		$lntahunang = $this->session->userdata('pcThang');
		$ttd2 = str_replace('n', ' ', $ttdperda);

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
		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		$initang = "nilai_ang";


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
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a;
						
						";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = round($row->ang_surplus);
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
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,2) IN ('61','62') $where
						GROUP BY LEFT(kd_ang,2)) a;
						";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = round($row->ang_netto);
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

			$sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilairealisasi FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = round($row->nil_ang);
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
								   <td align="right" valign="top"><b></b></td> 
								   <td align="right" valign="top"><b></b></td> 
								   <td align="right" valign="top"><b></b></td> 
								   <td align="right" valign="top"><b></b></td> 
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
								where cast(bulan as int)='$bulan' and jns_ang='$anggaran' and (left(kd_ang,1) in ($kode1) or left(kd_ang,2) in ($kode2) or LEFT(kd_ang,4) IN ($kode3) or left(kd_ang,6) in ($kode4) )
								group by LEFT(a.kd_rek6,6) order by LEFT(a.kd_rek6,6)";

						$hasil_3_rek6 = $this->db->query($sql_3_rek6);

						foreach ($hasil_3_rek6->result() as $row3_rek6) {
							$rek_objek = str_replace("'", ' ', $row3_rek6->rek_RO);
							$rek_objek_cetak = substr($rek_objek, 0, 1) . '.' . substr($rek_objek, 1, 1) . '.' . substr($rek_objek, 2, 2) . '.' . substr($rek_objek, 4, 2);

							$sel_3_rek6 = ($row3_rek6->nil_ang) - $row3_rek6->nilairealisasi;
							if (($row3_rek6->nil_ang == 0) || ($row3_rek6->nil_ang == '')) {
								$persen_3_rek6 = 0;
							} else {
								$persen_3_rek6 = $row3_rek6->nilairealisasi / $row3_rek6->nil_ang * 100;
							}

							if (strlen($kode) < 10) {
								$cRet .= '<tr>
										<td align="left" valign="top">' . $rek_objek_cetak . '</b></td> 
										<td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $row3_rek6->rek6_objek . '</td> 
										<td align="right" valign="top">' . number_format(($row3_rek6->nil_ang), "2", ",", ".") . '</td> 
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
								   <td align="right" valign="top"><b>' . number_format(($nil_ang), "2", ",", ".") . '</b></td> 
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

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd2' and (kode ='agr' or kode='wk' or kode='ppkd')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}


		if ($initx == '2') {
			$xx = "<u>";
			$xy = "</u>";
			$nipxx = '';
			$nipx = '';
			$jabatan1 = '';
			$namax1 = '';
			$pangkat1 = '';
		} else {
			$xx = "";
			$xy = "";
			$nipxx = $nip;
			$nipx = "NIP.";
			$jabatan1 = $jabatan;
			$namax1 = $namax;
			$pangkat1 = $pangkat;
		}
		if ($initx == '2') {
			$tempat = '';
			$tgltd = '';
		} else {
			$tempat = 'Melawi';
			$tgltd = $this->custom->tanggal_format_indonesia($ttd1);
		}

		$cRet .= '<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $tempat . ', ' . $tgltd . '</TD>
						</TR>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" ><b>' . $jabatan1 . '</b></TD>
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
							<TD align="center" >' . $xx . '<b>' . $namax1 . '</b>' . $xy . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $pangkat1 . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
						</TR>
						</TABLE><br/>';


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

	function cetak_lra90_objek_periode($tgl1 = '', $tgl2 = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $initx = '',  $ttd1 = '', $ttdperda = '', $label = '')
	{
		$tgl11 = $this->tukd_model->tanggal_format_indonesia($tgl1);
		$tgl21 = $this->tukd_model->tanggal_format_indonesia($tgl2);
		$lntahunang = $this->session->userdata('pcThang');
		$ttd2 = str_replace('n', ' ', $ttdperda);

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

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet = "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>PERIODE " . strtoupper($tgl11) . " S.D " . strtoupper($tgl21) . "TAHUN $lntahunang</b></tr>
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
					SUM(CASE WHEN kd_rek6='4' THEN nil_ang ELSE 0 END) - SUM(CASE WHEN kd_rek6='5' THEN nil_ang ELSE 0 END) as ang_surplus,
					SUM(CASE WHEN kd_rek6='4' THEN nilai ELSE 0 END) - SUM(CASE WHEN kd_rek6='5' THEN nilai ELSE 0 END) as nil_surplus
					 FROM (
				SELECT '4' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((kredit-debet)) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,1)='4' UNION ALL
				SELECT '5' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((debet-kredit)) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,1)='5' UNION ALL
				SELECT '61' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((debet-kredit)) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,2)='61' UNION ALL
				SELECT '62' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((debet-kredit)) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,2)='62'
				) oke";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = round($row->ang_surplus);
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
		SUM (CASE WHEN kd_rek6='61' THEN nil_ang ELSE 0 END) - SUM(CASE WHEN kd_rek6='62' THEN nil_ang ELSE 0 END)AS ang_netto,
		SUM (CASE WHEN kd_rek6='61' THEN nilai ELSE 0 END) - SUM(CASE WHEN kd_rek6='62' THEN nilai ELSE 0 END)AS nil_netto
		FROM (
	SELECT '61' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((kredit-debet)) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,2)='61' UNION ALL
	SELECT '62' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((debet-kredit)) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,2)='62'
	) oke";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = round($row->ang_netto);
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
		$sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi, jenis FROM map_lra_pemkot_periode ORDER BY urut
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
			$jenis = $row->jenis;

			$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilairealisasi FROM data_jurnal_n_periode('$tgl1','$tgl2','$anggaran','$lntahunang') where (LEFT(kd_rek6,1) IN ($kode1) or LEFT(kd_rek6,2) IN ($kode2) or LEFT(kd_rek6,4) IN ($kode3) or LEFT(kd_rek6,6) IN ($kode4) or LEFT(kd_rek6,8) IN ($kode5)) $where";


			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = round($row->nil_ang);
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
						$sql_3_rek6 = "SELECT LEFT(a.kd_rek6,6)rek_RO,(select nm_rek4 from ms_rek4 where kd_rek4=LEFT(a.kd_rek6,6))rek6_objek,SUM(nilai) as nil_ang, SUM($jenis) as nilairealisasi FROM data_jurnal_n_periode('$tgl1','$tgl2','$anggaran','$lntahunang') a join ms_rek6 b on a.kd_rek6=b.kd_rek6 WHERE (left(a.kd_rek6,1) in ($kode1) or left(a.kd_rek6,2) in ($kode2) or LEFT(a.kd_rek6,4) IN ($kode3) or left(a.kd_rek6,6) in ($kode4) )
								group by LEFT(a.kd_rek6,6) order by LEFT(a.kd_rek6,6)";

						$hasil_3_rek6 = $this->db->query($sql_3_rek6);

						foreach ($hasil_3_rek6->result() as $row3_rek6) {
							$rek_objek = str_replace("'", ' ', $row3_rek6->rek_RO);
							$rek_objek_cetak = substr($rek_objek, 0, 1) . '.' . substr($rek_objek, 1, 1) . '.' . substr($rek_objek, 2, 2) . '.' . substr($rek_objek, 4, 2);

							$sel_3_rek6 = ($row3_rek6->nil_ang) - $row3_rek6->nilairealisasi;
							if (($row3_rek6->nil_ang == 0) || ($row3_rek6->nil_ang == '')) {
								$persen_3_rek6 = 0;
							} else {
								$persen_3_rek6 = $row3_rek6->nilairealisasi / ($row3_rek6->nil_ang) * 100;
							}

							if (strlen($kode) < 10) {
								$cRet .= '<tr>
										<td align="left" valign="top">' . $rek_objek_cetak . '</b></td> 
										<td align="left"  valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $row3_rek6->rek6_objek . '</td> 
										<td align="right" valign="top">' . number_format(($row3_rek6->nil_ang), "2", ",", ".") . '</td> 
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

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd2' and (kode ='agr' or kode='wk' or kode='ppkd')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}


		if ($initx == '2') {
			$xx = "<u>";
			$xy = "</u>";
			$nipxx = '';
			$nipx = '';
			$jabatan1 = '';
			$namax1 = '';
			$pangkat1 = '';
		} else {
			$xx = "";
			$xy = "";
			$nipxx = $nip;
			$nipx = "NIP.";
			$jabatan1 = $jabatan;
			$namax1 = $namax;
			$pangkat1 = $pangkat;
		}
		if ($initx == '2') {
			$tempat = '';
			$tgltd = '';
		} else {
			$tempat = 'Melawi';
			$tgltd = $this->custom->tanggal_format_indonesia($ttd1);
		}

		$cRet .= '<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $tempat . ', ' . $tgltd . '</TD>
						</TR>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" ><b>' . $jabatan1 . '</b></TD>
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
							<TD align="center" >' . $xx . '<b>' . $namax1 . '</b>' . $xy . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $pangkat1 . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
						</TR>
						</TABLE><br/>';


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


	function cetak_lra_pemkot_90_ro_gabungan($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $initx = '',  $ttd1 = '', $ttdperda = '', $label = '')
	{
		// echo ($anggaran);
		$data = [];
		$lntahunang = $this->session->userdata('pcThang');
		$data['lntahunang'] = $lntahunang;
		$ttd1 = str_replace('n', '', $ttdperda);
		$data['sclient'] = $this->support->get_sclient();
		$data['label'] = ($label == 1 ? 'UNAUDITED' : 'AUDITED');
		$data['organisasis'] = $this->support->getsOrganisasi();

		$data['list_rekening4'] = $this->db->query("EXEC lraGabung @bulan='$bulan', @thnini ='$lntahunang', @status_angg ='$anggaran', @kd_rek='4'")->result();
		$data['list_rekening5'] = $this->db->query("EXEC lraGabung @bulan='$bulan', @thnini ='$lntahunang', @status_angg ='$anggaran', @kd_rek='5'")->result();
		$data['list_rekening6'] = $this->db->query("EXEC lraGabung @bulan='$bulan', @thnini ='$lntahunang', @status_angg ='$anggaran', @kd_rek='6'")->result();

		$data['lrek1'] = $this->support->getLength('ms_rek1', 'kd_rek1');
		$data['lrek2'] = $this->support->getLength('ms_rek2', 'kd_rek2');
		$data['lrek3'] = $this->support->getLength('ms_rek3', 'kd_rek3');
		$data['lrek4'] = $this->support->getLength('ms_rek4', 'kd_rek4');
		$data['lrek5'] = $this->support->getLength('ms_rek5', 'kd_rek5');
		$data['lrek6'] = $this->support->getLength('ms_rek6', 'kd_rek6');


		$cRet = 'perda/lra/cetak_lra_pemkot_90_ro_gabungan';
		$data['judul'] = 'LRA RINCIAN OBJEK GABUNGAN';
		switch ($ctk) {
			case 0;
				$this->load->view($cRet, $data);
				break;
			case 1;
				$this->tukd_model->_mpdf('', $this->load->view($cRet, $data, true), 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= " . $data['judul'] . ".xls");
				$this->load->view($cRet, $data);
				break;
		}
	}

	function cetak_lra_pemkot_90_ro_gabungan_periode($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $initx = '',  $ttd1 = '', $ttdperda = '', $label = '')
	{
		// echo ($anggaran);
		$data = [];
		$lntahunang = $this->session->userdata('pcThang');
		$data['lntahunang'] = $lntahunang;
		$ttd1 = str_replace('n', '', $ttdperda);
		$data['sclient'] = $this->support->get_sclient();
		$data['label'] = ($label == 1 ? 'UNAUDITED' : 'AUDITED');
		$data['organisasis'] = $this->support->getsOrganisasi();

		$data['list_rekening4'] = $this->db->query("EXEC lraGabung @bulan='$bulan', @thnini ='$lntahunang', @status_angg ='$anggaran', @kd_rek='4'")->result();
		$data['list_rekening5'] = $this->db->query("EXEC lraGabung @bulan='$bulan', @thnini ='$lntahunang', @status_angg ='$anggaran', @kd_rek='5'")->result();
		$data['list_rekening6'] = $this->db->query("EXEC lraGabung @bulan='$bulan', @thnini ='$lntahunang', @status_angg ='$anggaran', @kd_rek='6'")->result();

		$data['lrek1'] = $this->support->getLength('ms_rek1', 'kd_rek1');
		$data['lrek2'] = $this->support->getLength('ms_rek2', 'kd_rek2');
		$data['lrek3'] = $this->support->getLength('ms_rek3', 'kd_rek3');
		$data['lrek4'] = $this->support->getLength('ms_rek4', 'kd_rek4');
		$data['lrek5'] = $this->support->getLength('ms_rek5', 'kd_rek5');
		$data['lrek6'] = $this->support->getLength('ms_rek6', 'kd_rek6');


		$cRet = 'perda/lra/cetak_lra_pemkot_90_ro_gabungan';
		$data['judul'] = 'LRA RINCIAN OBJEK GABUNGAN';
		switch ($ctk) {
			case 0;
				$this->load->view($cRet, $data);
				break;
			case 1;
				$this->tukd_model->_mpdf('', $this->load->view($cRet, $data, true), 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= " . $data['judul'] . ".xls");
				$this->load->view($cRet, $data);
				break;
		}
	}


	function cetak_lra_pemkot_90_sub_ro($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $initx = '',  $ttd1 = '', $ttdperda = '', $label = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$ttd2 = str_replace('n', ' ', $ttdperda);

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

		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		$initang = "nilai_ang";


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
						where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,1) IN ('4','5','6') $where
						GROUP BY LEFT(kd_ang,1)) a";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_surplus = round($row->ang_surplus);
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
				where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,2) IN ('61','62') $where
				GROUP BY LEFT(kd_ang,2)) a";


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto = round($row->ang_netto);
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
					data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and (LEFT(kd_ang,1) 
					IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) 
					or LEFT(kd_ang,4) IN ($kode3) 
					or LEFT(kd_ang,6) IN ($kode4) 
					or LEFT(kd_ang,8) IN ($kode5)) $where";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = round($row->nil_ang);
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
								<td align="right" valign="top"><b></b></td> 
								<td align="right" valign="top"><b></b></td> 
								<td align="right" valign="top"><b></b></td> 
								<td align="right" valign="top"><b></b></td> 
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
								join ms_rek4 b on left(a.kd_rek6,6)=b.kd_rek4 where cast(a.bulan as int)='$bulan' and a.jns_ang='$anggaran' and 
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) 
								group by b.kd_rek4,b.nm_rek4
								union all
								SELECT '9' as spasi,b.kd_rek5 as kd_rek,b.nm_rek5 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
								join ms_rek5 b on left(a.kd_rek6,8)=b.kd_rek5 where cast(a.bulan as int)='$bulan' and a.jns_ang='$anggaran' and 
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) 
								group by b.kd_rek5,b.nm_rek5
								union all
								SELECT '10' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
								where cast(a.bulan as int)='$bulan' and a.jns_ang='$anggaran' and 
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) 
								group by a.kd_rek6,a.nm_rek6 order by kd_rek";
					} else {
						if ($is_kode4 != 'X') {
							$sql = "SELECT '8' as spasi,b.kd_rek5 as kd_rek,b.nm_rek5 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
									join ms_rek5 b on left(a.kd_rek6,8)=b.kd_rek5 where cast(a.bulan as int)='$bulan' AND a.jns_ang='$anggaran' and 
									(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) 
									group by b.kd_rek5,b.nm_rek5
									union all
									SELECT '9' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
									where cast(a.bulan as int)='$bulan' and a.jns_ang='$anggaran' and 
									(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) 
									group by a.kd_rek6,a.nm_rek6 order by kd_rek";
						} else {
							if ($is_kode5 != 'X') {
								$sql = "SELECT '8' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
										where cast(a.bulan as int)='$bulan' and a.jns_ang='$anggaran' and 
										(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) 
										group by a.kd_rek6,a.nm_rek6 order by kd_rek";
							}
						}
					}

					if ($sql) {
						$result = $this->db->query($sql);
						foreach ($result->result() as $row1) {
							$spasi3 = $row1->spasi;
							$nilai_anggaran = round($row1->nil_ang);
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

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd2' 
					and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}


		if ($initx == '2') {
			$xx = "<u>";
			$xy = "</u>";
			$nipxx = '';
			$nipx = '';
			$jabatan1 = '';
			$namax1 = '';
			$pangkat1 = '';
		} else {
			$xx = "";
			$xy = "";
			$nipxx = $nip;
			$nipx = "NIP.";
			$jabatan1 = $jabatan;
			$namax1 = $namax;
			$pangkat1 = $pangkat;
		}
		if ($initx == '2') {
			$tempat = '';
			$tgltd = '';
		} else {
			$tempat = 'Melawi';
			$tgltd = $this->custom->tanggal_format_indonesia($ttd1);
		}

		$cRet .= '	<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $tempat . ', ' . $tgltd . '</TD>
					</TR>					
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>' . $jabatan1 . '</b></TD>
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
						<TD align="center" >' . $xx . '<b>' . $namax1 . '</b>' . $xy . '</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $pangkat1 . '</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
					</TR>
					</TABLE><br/>';


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

	// Create Hakam
	function cetak_lra_pemkot_90_sub_ro_periode($tgl1 = '', $tgl2 = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $initx = '',  $ttd1 = '', $ttdperda = '', $label = '')
	{

		$lntahunang = $this->session->userdata('pcThang');
		$ttdperda = $this->uri->segment(11);
		$tanggal_ttd = $this->uri->segment(10);
		$ttd1 = str_replace('n', ' ', $ttdperda);
		$ctk = $this->uri->segment(5);
		$tgl1 = $this->uri->segment(3);
		$tgl2 = $this->uri->segment(4);
		$tglper1 = $this->tukd_model->tanggal_format_indonesia($tgl1);
		$tglper2 = $this->tukd_model->tanggal_format_indonesia($tgl2);
		$tglperiode1 = strtoupper($tglper1);
		$tglperiode2 = strtoupper($tglper2);
		$anggaran = $this->uri->segment(6);
		$kd_skpd = $this->uri->segment(7);
		$baris = $this->uri->segment(13);

		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		// if ($anggaran == 1) {
		// 	$initang = "nilai";
		// } else if ($anggaran == 2) {
		// 	$initang = "nilai_sempurna";
		// } else {
		// 	$initang = 'nilai_ubah';
		// }

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
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>UNTUK PERIODE $tglperiode1 SAMPAI DENGAN $tglperiode2 </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$label</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"$baris\">
				<thead>
				<tr>
					<td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
					<td rowspan=\"2\" colspan = \"7\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
					<td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
					<td colspan=\"2\" width=\"26%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
					</tr>
					</tr>
				<tr>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>ANGGARAN</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI</b></td>
					<td width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>(Rp)</b></td>
					<td width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>%</b></td>
					</tr>
					<tr>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >1</td> 
				   <td align=\"center\" colspan = \"7\" bgcolor=\"#CCCCCC\" >2</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >3</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >4</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >5</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >6</td>
				</tr>
				</thead> ";

		$sql = "SELECT 
				SUM (CASE WHEN kd_rek6='4' THEN nil_ang ELSE 0 END) AS ang_p,
				SUM (CASE WHEN kd_rek6='4' THEN nilai ELSE 0 END) AS nil_p,
				SUM (CASE WHEN kd_rek6='4' THEN nilaill ELSE 0 END) AS nilll_p,
				
				SUM (CASE WHEN kd_rek6='5' THEN nil_ang ELSE 0 END) AS ang_b,
				SUM (CASE WHEN kd_rek6='5' THEN nilai ELSE 0 END) AS nil_b,
				SUM (CASE WHEN kd_rek6='5' THEN nilaill ELSE 0 END) AS nilll_b,
				
				SUM (CASE WHEN kd_rek6='61' THEN nil_ang ELSE 0 END) AS ang_dby,
				SUM (CASE WHEN kd_rek6='61' THEN nilai ELSE 0 END) AS nil_dby,
				SUM (CASE WHEN kd_rek6='61' THEN nilaill ELSE 0 END) AS nilll_dby,
				
				SUM (CASE WHEN kd_rek6='62' THEN nil_ang ELSE 0 END) AS ang_lby,
				SUM (CASE WHEN kd_rek6='62' THEN nilai ELSE 0 END) AS nil_lby,
				SUM (CASE WHEN kd_rek6='62' THEN nilaill ELSE 0 END) AS nilll_lby 
				FROM (
			SELECT '4' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((kredit-debet)) AS nilai,SUM ((kredit-debet)) AS nilaill FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,1)='4' UNION ALL
			SELECT '5' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((debet-kredit)) AS nilai,SUM ((debet-kredit)) AS nilaill FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,1)='5' UNION ALL
			SELECT '61' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((debet-kredit)) AS nilai,SUM ((debet-kredit)) AS nilaill FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,2)='61' UNION ALL
			SELECT '62' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((debet-kredit)) AS nilai,SUM ((debet-kredit)) AS nilaill FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,2)='62'
			) oke";

		$totsurplusang = 0;
		$totsurplusnil = 0;
		$totsurplusnilll = 0;
		$totsurplustot = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_p = $row->ang_p;
			$nil_p = $row->nil_p;
			$nilll_p = $row->nilll_p;
			$ang_b = $row->ang_b;
			$nil_b = $row->nil_b;
			$nilll_b = $row->nilll_b;
			$ang_dby = $row->ang_dby;
			$nil_dby = $row->nil_dby;
			$nilll_dby = $row->nilll_dby;
			$ang_lby = $row->ang_lby;
			$nil_lby = $row->nil_lby;
			$nilll_lby = $row->nilll_lby;
		}
		$totsurplusang = $ang_p - $ang_b;
		$totsurplusnil = $nil_p - $nil_b;
		$totsurplusnilll = $nilll_p - $nilll_b;

		$totsurplustot = $totsurplusnil + $totsurplusnilll;

		if ($totsurplusang < 0) {
			$totsurplusang1 = $totsurplusang * -1;
			$a = '(';
			$b = ')';
		} else {
			$totsurplusang1 = $totsurplusang;
			$a = '';
			$b = '';
		}

		if ($totsurplusnil < 0) {
			$totsurplusnil1 = $totsurplusnil * -1;
			$mn = '(';
			$op = ')';
		} else {
			$totsurplusnil1 = $totsurplusnil;
			$mn = '';
			$op = '';
		}

		if ($totsurplusnilll < 0) {
			$totsurplusnilll1 = $totsurplusnilll * -1;
			$qr = '(';
			$st = ')';
		} else {
			$totsurplusnilll1 = $totsurplusnilll;
			$qr = '';
			$st = '';
		}

		if ($totsurplustot < 0) {
			$totsurplustot1 = $totsurplustot * -1;
			$uv = '(';
			$wx = ')';
		} else {
			$totsurplustot1 = $totsurplustot;
			$uv = '';
			$wx = '';
		}

		$sisa_surplus = $totsurplusang - $totsurplustot;
		if (($totsurplusang == 0) || ($totsurplusang == '')) {
			$persen_surplus = 0;
		} else {
			$persen_surplus = $totsurplustot / $totsurplusang * 100;
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
				SUM (CASE WHEN kd_rek6='61' THEN nil_ang ELSE 0 END) AS ang_netto_dby,
				SUM (CASE WHEN kd_rek6='61' THEN nilai ELSE 0 END) AS nil_netto_dby,
				SUM (CASE WHEN kd_rek6='61' THEN nilaill ELSE 0 END) AS nilll_netto_dby,
				
				SUM (CASE WHEN kd_rek6='62' THEN nil_ang ELSE 0 END) AS ang_netto_lby,
				SUM (CASE WHEN kd_rek6='62' THEN nilai ELSE 0 END) AS nil_netto_lby,
				SUM (CASE WHEN kd_rek6='62' THEN nilaill ELSE 0 END) AS nilll_netto_lby 
				FROM (
			SELECT '61' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((debet-kredit)) AS nilai,SUM ((debet-kredit)) AS nilaill FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,2)='61' UNION ALL
			SELECT '62' AS kd_rek6,SUM (nilai) AS nil_ang,SUM ((debet-kredit)) AS nilai,SUM ((debet-kredit)) AS nilaill FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') WHERE LEFT (kd_rek6,2)='62'
			) oke";

		$totangnetto = 0;
		$totnilnetto = 0;
		$totnilllnetto = 0;
		$totalnetto = 0;

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$ang_netto_dby = $row->ang_netto_dby;
			$nil_netto_dby = $row->nil_netto_dby;
			$nilll_netto_dby = $row->nilll_netto_dby;
			$ang_netto_lby = $row->ang_netto_lby;
			$nil_netto_lby = $row->nil_netto_lby;
			$nilll_netto_lby = $row->nilll_netto_lby;
		}

		$totangnetto = $ang_netto_dby - $ang_netto_lby;
		$totnilnetto = $nil_netto_dby + $nil_netto_lby;
		$totnilllnetto = $nilll_netto_dby - $nilll_netto_lby;

		$totalnetto = $totnilnetto + $totnilllnetto;

		$sisa_netto = $totangnetto - $totalnetto;
		if (($totangnetto == 0) || ($totangnetto == '')) {
			$persen_netto = 0;
		} else {
			$persen_netto = $totalnetto / $totangnetto * 100;
		}

		if ($totnilllnetto < 0){
			$totnilllnetto1 = $totnilllnetto * -1;
			$ab = '(';
			$cd = ')';
		} else {
			$totnilllnetto1 = $totnilllnetto;
			$ab = '';
			$cd = '';
		}

		if ($totnilnetto < 0){
			$totnilnetto1 = $totnilnetto * -1;
			$ef = '(';
			$gh = ')';
		} else {
			$totnilnetto1 = $totnilnetto;
			$ef = '';
			$gh = '';
		}

		if ($totalnetto < 0){
			$totalnetto1 = $totalnetto * -1;
			$ij = '(';
			$kl = ')';
		} else {
			$totalnetto1 = $totalnetto;
			$ij = '';
			$kl = '';
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

		$ang_silpa = $totsurplusang + $totangnetto;
		$nil_silpa = $totsurplusnil + $totnilnetto;
		$nilll_silpa = $totsurplusnilll + $totnilllnetto;
		$tot_silpa = $totsurplustot + $totalnetto;

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
		if ($nilll_silpa < 0) {
			$nilll_silpa1 = $nilll_silpa * -1;
			$q = '(';
			$r = ')';
		} else {
			$nilll_silpa1 = $nilll_silpa;
			$q = '';
			$r = '';
		}
		if ($tot_silpa < 0) {
			$tot_silpa1 = $tot_silpa * -1;
			$t = '(';
			$u = ')';
		} else {
			$tot_silpa1 = $tot_silpa;
			$t = '';
			$u = '';
		}

		$sisa_silpa = $ang_silpa - $tot_silpa;
		if ($ang_silpa == 0) {
			$persen_silpa = 0;
		} else {
			$persen_silpa = $tot_silpa / $ang_silpa * 100;
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


		$sql = "SELECT urut, kd_rek, uraian, kode1, kode2, kode3,kode4,kode5,spasi,jenis FROM map_lra_pemkot_periode ORDER BY urut";

		$no = 0;
		$tot_real = 0;
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
			$jenis = $row->jenis;
			//$jenis_lalu = $row->jenis_lalu;
			$spasi = $row->spasi;

			$sql = "SELECT SUM(nilai) as nil_ang, SUM($jenis) as nilaireal FROM data_jurnal_n_periode('$tgl1','$tgl2','$anggaran','$lntahunang') 
		where (
		LEFT(kd_rek6,1) IN ($kode1) or 
		LEFT(kd_rek6,2) IN ($kode2) or 
		LEFT(kd_rek6,4) IN ($kode3) or 
		LEFT(kd_rek6,6) IN ($kode4) or 
		LEFT(kd_rek6,8) IN ($kode5)) 
		$where";

			$hasil = $this->db->query($sql);
			foreach ($hasil->result() as $row) {
				$nil_ang = $row->nil_ang;
				$nilai = $row->nilaireal;
				//$nilaill = $row->nilairealll;
			}
			$tot_real = $nilai;  //$nilaill;


			$sel = $nil_ang - $tot_real;

			if (($nil_ang == 0) || ($nil_ang == '')) {
				$persen = 0;
			} else {
				$persen = $tot_real / $nil_ang * 100;
			}
			if ($sel < 0) {
				$sel1 = $sel * -1;
				$t = '(';
				$u = ')';
			} else {
				$sel1 = $sel;
				$t = '';
				$u = '';
			}

			switch ($spasi) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="left" colspan = "7" valign="top"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td>   
							</tr>';
					break;
				case 2:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kode . '</b></td> 
							   <td align="right" valign="top" style="border-right:none"><b></b></td> 
							   <td align="left"  colspan = "6" width="32%" valign="top" style="border-left:none"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td>  
							   <td align="right" valign="top"><b>' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 3:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $kode . '</b></td> 
							   <td colspan="2" align="left" width="2%" valign="top" style="border-right:none"></td> 
							   <td align="left" colspan="5" width="31%" valign="top" style="border-left:none">' . $nama . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							    
							   <td align="right" valign="top">' . number_format($nilai, "2", ",", ".") . '</td>  
							   <td align="right" valign="top">' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</b></td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';

					$is_kode3 = strtoupper(substr(str_replace("'", "", $kode3), 0, 1));
					$is_kode4 = strtoupper(substr(str_replace("'", "", $kode4), 0, 1));
					$is_kode5 = strtoupper(substr(str_replace("'", "", $kode5), 0, 1));

					if ($is_kode3 != 'X') {
						$sql = "SELECT '8' AS spasi,b.kd_rek4 AS kd_rek,b.nm_rek4 AS nm_rek,SUM (nilai) AS nil_ang,SUM ($jenis) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') a JOIN ms_rek4 b ON LEFT (a.kd_rek6,6)=b.kd_rek4 WHERE (LEFT (a.kd_rek6,4) IN ($kode3) OR LEFT (a.kd_rek6,6) IN ($kode4) or LEFT(a.kd_rek6,8) IN ($kode5)) GROUP BY b.kd_rek4,b.nm_rek4 
						UNION ALL
						SELECT '9' AS spasi,b.kd_rek5 AS kd_rek,b.nm_rek5 AS nm_rek,SUM (nilai) AS nil_ang,SUM ($jenis) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') a JOIN ms_rek5 b ON LEFT (a.kd_rek6,8)=b.kd_rek5 WHERE (LEFT (a.kd_rek6,4) IN ($kode3) OR LEFT (a.kd_rek6,6) IN ($kode4) or LEFT(a.kd_rek6,8) IN ($kode5)) GROUP BY b.kd_rek5,b.nm_rek5 
						UNION ALL
						SELECT '10' AS spasi,a.kd_rek6 AS kd_rek,b.nm_rek6 AS nm_rek,SUM (nilai) AS nil_ang,SUM ($jenis) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') a JOIN ms_rek6 b ON a.kd_rek6 =b.kd_rek6 WHERE (LEFT (a.kd_rek6,4) IN ($kode3) OR LEFT (a.kd_rek6,6) IN ($kode4) or LEFT(a.kd_rek6,8) IN ($kode5)) GROUP BY a.kd_rek6,b.nm_rek6 ORDER BY kd_rek";
					} else {
						if ($is_kode4 != 'X') {
							$sql = "SELECT '8' AS spasi,b.kd_rek5 AS kd_rek,b.nm_rek5 AS nm_rek,SUM (nilai) AS nil_ang,SUM ($jenis) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') a JOIN ms_rek5 b ON LEFT (a.kd_rek6,8)=b.kd_rek5 WHERE (LEFT (a.kd_rek6,4) IN ($kode3) OR LEFT (a.kd_rek6,6) IN ($kode4) or LEFT(a.kd_rek6,8) IN ($kode5)) GROUP BY b.kd_rek5,b.nm_rek5 
							UNION ALL
							SELECT '9' AS spasi,a.kd_rek6 AS kd_rek,b.nm_rek6 AS nm_rek,SUM (nilai) AS nil_ang,SUM ($jenis) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') a JOIN ms_rek6 b ON a.kd_rek6 =b.kd_rek6 WHERE (LEFT (a.kd_rek6,4) IN ($kode3) OR LEFT (a.kd_rek6,6) IN ($kode4) or LEFT(a.kd_rek6,8) IN ($kode5)) GROUP BY a.kd_rek6,b.nm_rek6 ORDER BY kd_rek";
						} else {
							if ($is_kode5 != 'X') {
								$sql = "SELECT '8' AS spasi,a.kd_rek6 AS kd_rek,b.nm_rek6 AS nm_rek,SUM (nilai) AS nil_ang,SUM ($jenis) AS nilai FROM data_jurnal_n_periode ('$tgl1','$tgl2','$anggaran','$lntahunang') a JOIN ms_rek6 b ON a.kd_rek6 =b.kd_rek6 WHERE (LEFT (a.kd_rek6,4) IN ($kode3) OR LEFT (a.kd_rek6,6) IN ($kode4) or LEFT(a.kd_rek6,8) IN ($kode5)) GROUP BY a.kd_rek6,b.nm_rek6 ORDER BY kd_rek";
							}
						}
					}

					if ($sql) {
						$result = $this->db->query($sql);
						foreach ($result->result() as $row1) {
							$spasi3 = $row1->spasi;
							$nilai_anggaran = $row1->nil_ang;
							$nilai_realisasi = $row1->nilai;
							//$nilai_realisasill = $row1->nilaill;

							$total = $nilai_realisasi; //$nilai_realisasill;
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
															<td colspan="2" align="left"  width="4%" valign="top" style="border-right:none">&nbsp;</td>
															<td colspan="5" align="left"  width="29%" valign="top" style="border-left:none">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $row1->nm_rek . '</td> 
															<td align="right" valign="top">' . number_format($nilai_anggaran, "2", ",", ".") . '</td> 
															
															<td align="right" valign="top">' . number_format($nilai_realisasi, "2", ",", ".") . '</td>  
															<td align="right" valign="top">' . $t . '' . number_format($selisih, "2", ",", ".") . '' . $u . '</td> 
															<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td>  
														</tr>';
									break;
								case 9:
									$cRet .= '<tr>
																<td align="left" valign="top">' . $this->custom->dotrek($row1->kd_rek) . '</b></td>
																<td colspan="4" align="left" width=\"4%\" valign="top" style="border-right:none"></td>
																<td colspan="3" align="left" valign="top" width="29%" style="border-left:none">' . $row1->nm_rek . '</td> 
																<td align="right" valign="top">' . number_format($nilai_anggaran, "2", ",", ".") . '</td> 
																 
																<td align="right" valign="top">' . number_format($nilai_realisasi, "2", ",", ".") . '</td> 
																<td align="right" valign="top">' . $t . '' . number_format($selisih, "2", ",", ".") . '' . $u . '</td> 
																<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td>   
															</tr>';
									break;
								case 10:
									$cRet .= '<tr>
																	<td align="left" valign="top">' . $this->custom->dotrek($row1->kd_rek) . '</b></td>
																	<td colspan="5" align="left"  width=\"5%\" valign="top" style="border-right:none"></td>
																	<td colspan="2" align="left" valign="top" width="28%" style="border-left:none">' . $row1->nm_rek . '</td> 
																	<td align="right" valign="top">' . number_format($nilai_anggaran, "2", ",", ".") . '</td> 
																	
																	<td align="right" valign="top">' . number_format($nilai_realisasi, "2", ",", ".") . '</td> 
																	<td align="right" valign="top">' . $t . '' . number_format($selisih, "2", ",", ".") . '' . $u . '</td> 
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

					break;
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td width="1%" align="left" valign="top" style="border-right:none"></td>
							   <td colspan="6" align="left"  valign="top" style="border-left:none"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							    
							   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $t . '' . number_format($sel1, "2", ",", ".") . '' . $u . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td>  
							</tr>';
					break;
				case 5:
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td colspan = "7" align="right"  valign="top" style="border-left:none"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . '' . number_format($totsurplusang1, "2", ",", ".") . '' . $b . '<b></td> 
							   <td align="right" valign="top"><b>' . $mn . '' . number_format($totsurplusnil1, "2", ",", ".") . '' . $op . '</b></td> 
							   <td align="right" valign="top"><b>' . $e . '' . number_format($sisa_surplus1, "2", ",", ".") . '' . $f . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_surplus, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 6;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td colspan="7" align="right"  valign="top" style="border-left:none"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . number_format($totangnetto, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" ><b>' . $ef . '' . number_format($totnilnetto1, "2", ",", ".") . '' . $gh . '</b></td> 
							   <td align="right" valign="top"><b>' . $k . '' . number_format($sisa_netto1, "2", ",", ".") . '' . $l . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_netto, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				case 7;
					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td colspan="7" align="right"  valign="top" style="border-left:none"><b>' . $nama . '</b></td> 
							   <td align="right" valign="top" ><b>' . $m . '' . number_format($ang_silpa1, "2", ",", ".") . '' . $n . '</b></td> 
							   <td align="right" valign="top" ><b>' . $o . '' . number_format($nil_silpa1, "2", ",", ".") . '' . $p . '</b></td> 
							   <td align="right" valign="top"><b>' . $q . '' . number_format($sisa_silpa1, "2", ",", ".") . '' . $r . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen_silpa, "2", ",", ".") . '</b></td> 
							</tr>';
					break;

				default:

					$cRet .= '<tr>
							   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
							   <td colspan="7" align="right"  valign="top" style="border-left:none"><b></b></td> 
							   <td align="right" valign="top" ><b></b></td>
							   <td align="right" valign="top" ><b></b></td> 
							   <td align="right" valign="top" ><b></b></td> 
							   <td align="right" valign="top" ><b></b></td> 
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

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$ttd1' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUP')";
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
			$tgltd = $this->tukd_model->tanggal_format_indonesia($tanggal_ttd);
		}

		if ($nip == '00000000 000000 0 000') {
			$cRet .= '<br><br>
			<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" > Melawi, ' . $tgltd . '</TD>
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
		} else if ($nip == '19671126 199503 2 004') {
			$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
								
								<TR>
									<TD width="35%" align="center" ><b>&nbsp;</TD>
									<TD align="center" > Melawi, ' . $tgltd . '</TD>
								</TR>
								
								<TR>
									<TD width="35%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . strtoupper($jabatan) . '</TD>
								</TR>
								<TR>
									<TD width="35%" align="center" ><b></TD>
									<TD width="65%" align="center" >selaku <br>PEJABAT PENGELOLA KEUANGAN DAERAH</TD>
								</TR>
								<TR>
									<TD width="35%" align="center" ><b>&nbsp;</TD>
									<TD width="65%" align="center" ><b>&nbsp;</TD>
								</TR>
								<TR>
									<TD width="35%" align="center" ><b>&nbsp;</TD>
									<TD width="65%" align="center" ><b>&nbsp;</TD>
								</TR>
								<TR>
									<TD width="35%" align="center" ><b>&nbsp;</TD>
									<TD width="65%" align="center" ><b>&nbsp;</TD>
								</TR>
								<TR>
									<TD width="35%" align="center" ><b>&nbsp;</TD>
									<TD width="65%" align="center" ><b>&nbsp;</TD>
								</TR>   
								<TR>
									<TD width="35%" align="center" ><b>&nbsp;</TD>
									<TD width="65%" align="center" ><b>&nbsp;</TD>
								</TR>                       
								<TR>
									<TD width="35%" align="center" ><b>&nbsp;</TD>
									<TD align="center" ><u><b>' . $xx . '<b>' . $namax . '</b>' . $xy . '</b></u></TD>
								</TR>
								<TR>
									<TD width="35%" align="center" ><b>&nbsp;</TD>
									<TD align="center" >' . $pangkat . '</TD>
								</TR>
								<TR>
									<TD width="35%" align="center" ><b>&nbsp;</TD>
									<TD align="center">NIP. ' . $nip . '</TD>
								</TR>
								</TABLE><br/>';
		} else {
			$cRet .= '<br><br>
			<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" > Melawi, ' . $tgltd . '</TD>
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
		$judul = 'LRA SUB RO PERIODE ';
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
	//END

	//End

	function cetak_lra_pemkot_90_ro($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd1 = '', $tanggal_ttd = '', $ttdperda = '', $label = '')
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

		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		$initang = "nilai_ang";


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
					and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd')";
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
					</TABLE><br/>';


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

	function cetak_lra_pemkot_64_ro($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd1 = '', $tanggal_ttd = '', $ttdperda = '', $label = '')
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

		if ($kd_skpd == '-') {
			$where = "";
		} else {
			$where = "AND kd_skpd='$kd_skpd'";
		}

		if ($anggaran == 'M') {
			$initang = "nilai_ang";
		} else if ($anggaran == 'P1') {
			$initang = "nilai_ang_p1";
		} else if ($anggaran == 'P2') {
			$initang = "nilai_ang_p2";
		} else if ($anggaran == 'P3') {
			$initang = "nilai_ang_p3";
		} else if ($anggaran == 'P4') {
			$initang = "nilai_ang_p4";
		} else if ($anggaran == 'P5') {
			$initang = "nilai_ang_p5";
		} else if ($anggaran == 'U1') {
			$initang = "nilai_ang_u1";
		} else {
			$initang = "nilai_ang_u2";
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

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='agr' or kode='wk' or kode='pa' or kode='BUP' or kode='PPKD')";
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

		$ang = "nilai_ang";

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
							<TD width="30%" align="left" >LAMPIRAN I &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN BUPATI KABUPATEN MELAWI <br/> 
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
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"75\" height=\"100\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
						<!--<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>PER $arraybulan[$bulan] $lntahunang</b></tr>-->
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA  </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$bulan] $lntahunang</b></tr>                    
						</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
					<thead>
					<tr>
						<td rowspan=\"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KD REK</b></td>
						<td rowspan=\"2\" width=\"32%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
						<td colspan=\"2\" width=\"37%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
						<td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/KURANG</b></td>
						<td colspan=\"2\" width=\"23%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>REALISASI $Intahunlalu</b></td>
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
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM($ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,1) IN ('4','5','6') $where
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
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($ang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,2) IN ('61','62') $where
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

			$sql = "SELECT SUM($ang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

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
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
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
								   <td align="right" valign="top">' . number_format($tahun_lalu, "2", ",", ".") . '</td> 
								</tr>';
					break;
				case 4:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
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

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD')";
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

			$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style;" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
							<TD align="center" >&nbsp;</TD>
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
							<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
						</TR>
						</TABLE><br/>';
		}

		$data['prev'] = $cRet;
		$judul = 'LRA_PERMEN ';
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

	function cetak_perda_lampI_permen_spj33_perda($bulan = '', $ctk = '', $anggaran = '', $jenis = '', $kd_skpd = '', $ttd = '', $tanggal_ttd = '', $ttdperda = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$ttd1 = str_replace('n', ' ', $ttdperda);
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

		$initang = "nilai_ang";


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
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"75\" height=\"100\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
						<!--<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>PER $arraybulan[$bulan] $lntahunang</b></tr>-->
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA  </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$bulan] $lntahunang</b></tr>                    
						</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
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
						(SELECT LEFT(kd_ang,1) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,1) IN ('4','5','6') $where
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
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,2) IN ('61','62') $where
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

			$sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";

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
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($tahun_lalu, "2", ",", ".") . '</b></td> 
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
								   <td align="right" valign="top">' . number_format($tahun_lalu, "2", ",", ".") . '</td>
								</tr>';
					break;
				case 4:
					$cRet .= '<tr>
								   <td align="left" valign="top" ><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $nama . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($nilai, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top"><b>' . number_format($sel, "2", ",", ".") . '</b></td> 
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

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode ='PA' or kode='PPKD')";
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

			$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px" font-family: Bookman Old Style;" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
							<TD align="center" >&nbsp;</TD>
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
							<TD align="center" >' . $xx . '<b>' . $nama . '</b>' . $xy . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $nipx . '' . $nipxx . '</TD>
						</TR>
						</TABLE><br/>';
		}

		$data['prev'] = $cRet;
		$judul = 'LRA_PERMEN ';
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

	//================================================ End Lamp Perda I

	//================================================ Lamp Perda I.1
	function perdaI_1()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. I.1';
		$this->template->set('title', 'CETAK LAMP. I.1');
		$this->template->load('template', 'perda/cetak_perda_lampI_1', $data);
	}

	function cetak_perda_lampI_1_233_perwa($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunang_b =   $lntahunang + 1;

		$denganttd = $this->uri->segment(7);

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
						<TD width="30%" align="left" >LAMPIRAN I.1 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN BUPATI KABUPATEN MELAWI <br/> 
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
						<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"100\" height=\"100\" />
						</td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"></td></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><strong>$kab <br> RINGKASAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI<br>SAMPAI DENGAN " . strtoupper($judul) . " TAHUN ANGGARAN " . $lntahunang . "</strong></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
					</TABLE><br/>";

		$cRet .= "<table style=\"font-size:10px;font-family:Bookman Old Style; border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
				<thead>
				<tr>
					<td rowspan = \"4\" width=\"3.5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">KODE</td>
					<td rowspan = \"4\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">URUSAN PEMERINTAH DAERAH</td>
					<td colspan = \"4\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">PENDAPATAN</td>
					<td colspan = \"28\" width=\"71.5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BELANJA</td>
				</tr>
				<tr>
				   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">ANGGARAN</td> 
				   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">REALISASI</td> 
				   <td rowspan = \"2\" colspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BERTAMBAH/ KURANG</td> 
				   <td colspan =\"6\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">OPERASI</td> 
				   <td colspan =\"5\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">MODAL</td> 
				   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">TIDAK TERDUGA</td>   
				   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">JUMLAH ANGGARAN</td> 
				   <td colspan =\"6\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">OPERASI</td> 
				   <td colspan =\"5\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">MODAL</td> 
				   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">TIDAK TERDUGA</td> 
				   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">JUMLAH REALISASI</td> 
				   <td colspan =\"2\" rowspan = \"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BERTAMBAH/ KURANG</td> 
				</tr>
				<tr>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">PEGAWAI</td> 
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BARANG DAN<BR/>JASA</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BUNGA</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">SUBSIDI</td> 
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">HIBAH</td>                     
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BANTUAN SOSIAL</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">TANAH</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">PERALATAN DAN <BR/>MESIN</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">GEDUNG DAN <BR/>BANGUNAN</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">JALAN, IRIGASI <BR/>DAN JARINGAN</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">ASET TETAP<BR/> LAINNYA</td>
				   
				   
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">PEGAWAI</td> 
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BARANG DAN<BR/>JASA</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BUNGA</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">SUBSIDI</td> 
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">HIBAH</td>                     
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BANTUAN SOSIAL</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">TANAH</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">PERALATAN DAN <BR/>MESIN</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">GEDUNG DAN <BR/>BANGUNAN</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">JALAN, IRIGASI <BR/>DAN JARINGAN</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">ASET TETAP<BR/> LAINNYA</td>
				   
				</tr>
				<tr>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">%</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td>                    
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td>                                       
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
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
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">11</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">12</td>                    
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">13</td>                                       
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">14</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">15</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">16</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">17</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">18</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">19</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">20</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">21</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">22</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">23</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">24</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">25</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">26</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">27</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">28</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">29</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">30</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">31</td>					   
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">32</td>	
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">33</td>					   
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">34</td>
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

		$sql = " select kd_kegiatan kode, kd_kegiatan1 kode1 ,nm_rek,ang_pend,ang_5101,ang_5102,ang_5103,ang_5104,ang_5105,ang_5106,ang_5201,ang_5202,ang_5203,ang_5204,ang_5205,ang_5301,real_pend,real_5101,real_5102,real_5103,real_5104,real_5105,real_5106,real_5201,real_5202,real_5203,real_5204,real_5205,real_5301 
					FROM [perda_lampI.3_sub_3_baru_33]($bulan,'$anggaran') ORDER BY kd_kegiatan
								";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$kode = $row->kode1;
			$nama = $row->nm_rek;
			$ang_pend = $row->ang_pend;
			$ang_5101 = $row->ang_5101;
			$ang_5102 = $row->ang_5102;
			$ang_5103 = $row->ang_5103;
			$ang_5104 = $row->ang_5104;
			$ang_5105 = $row->ang_5105;
			$ang_5106 = $row->ang_5106;
			$ang_5201 = $row->ang_5201;
			$ang_5202 = $row->ang_5202;
			$ang_5203 = $row->ang_5203;
			$ang_5204 = $row->ang_5204;
			$ang_5205 = $row->ang_5205;
			$ang_5301 = $row->ang_5301;
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
			$real_5301 = $row->real_5301;

			$tot_ang = $ang_5101 + $ang_5102 + $ang_5103 + $ang_5104 + $ang_5105 + $ang_5106 + $ang_5201 + $ang_5202 + $ang_5203 + $ang_5204 + $ang_5205 + $ang_5301;
			$tot_bel = $real_5101 + $real_5102 + $real_5103 + $real_5104 + $real_5105 + $real_5106 + $real_5201 + $real_5202 + $real_5203 + $real_5204 + $real_5205 + $real_5301;
			$per_pend  = $ang_pend == 0 || $ang_pend == '' ? 0 : $bel_pend / $ang_pend * 100;
			$per_bel  = $tot_ang == 0 || $tot_ang == '' ? 0 : $tot_bel / $tot_ang * 100;

			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:8px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:8px">' . strtoupper($nama) . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($bel_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_pend - $bel_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($per_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5101, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5102, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5103, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5104, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5105, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5106, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5201, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5202, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5203, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5204, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5205, "2", ",", ".") . '</td>	
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5301, "2", ",", ".") . '</td>  
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5101, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5102, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5103, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5104, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5105, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5106, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5201, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5202, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5203, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5204, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5205, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5301, "2", ",", ".") . '</td>	
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_bel, "2", ",", ".") . '</td>                                
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_ang - $tot_bel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($per_bel, "2", ",", ".") . '</td> 
							</tr>';
		}
		$sql = " select 'JUMLAH' nm_rek,sum(ang_pend) ang_pend,sum(ang_5101)ang_5101,sum(ang_5102)ang_5102,sum(ang_5103)ang_5103,sum(ang_5104)ang_5104,sum(ang_5105)ang_5105,sum(ang_5106)ang_5106,sum(ang_5201)ang_5201,sum(ang_5202)ang_5202,sum(ang_5203)ang_5203,sum(ang_5204)ang_5204,sum(ang_5205)ang_5205,sum(ang_5301)ang_5301,sum(real_pend)real_pend,sum(real_5101)real_5101,sum(real_5102)real_5102,sum(real_5103)real_5103,sum(real_5104)real_5104,sum(real_5105)real_5105,sum(real_5106)real_5106,sum(real_5201)real_5201,sum(real_5202)real_5202,sum(real_5203)real_5203,sum(real_5204)real_5204,sum(real_5205)real_5205,sum(real_5301)real_5301 FROM 
						 [perda_lampI.3_sub_3_baru_33]($bulan,'$anggaran') where len(kd_kegiatan)=1";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$nama = $row->nm_rek;
			$ang_pend = $row->ang_pend;
			$ang_5101 = $row->ang_5101;
			$ang_5102 = $row->ang_5102;
			$ang_5103 = $row->ang_5103;
			$ang_5104 = $row->ang_5104;
			$ang_5105 = $row->ang_5105;
			$ang_5106 = $row->ang_5106;
			$ang_5201 = $row->ang_5201;
			$ang_5202 = $row->ang_5202;
			$ang_5203 = $row->ang_5203;
			$ang_5204 = $row->ang_5204;
			$ang_5205 = $row->ang_5205;
			$ang_5301 = $row->ang_5301;
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
			$real_5301 = $row->real_5301;

			$tot_ang = $ang_5101 + $ang_5102 + $ang_5103 + $ang_5104 + $ang_5105 + $ang_5106 + $ang_5201 + $ang_5202 + $ang_5203 + $ang_5204 + $ang_5205 + $ang_5301;
			$tot_bel = $real_5101 + $real_5102 + $real_5103 + $real_5104 + $real_5105 + $real_5106 + $real_5201 + $real_5202 + $real_5203 + $real_5204 + $real_5205 + $real_5301;
			$per_pend  = $ang_pend == 0 || $ang_pend == '' ? 0 : $bel_pend / $ang_pend * 100;
			$per_bel  = $tot_ang == 0 || $tot_ang == '' ? 0 : $tot_bel / $tot_ang * 100;

			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:8px"></td> 
							   <td align="center"  valign="top" style="font-size:8px">' . strtoupper($nama) . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($bel_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_pend - $bel_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($per_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5101, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5102, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5103, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5104, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5105, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5106, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5201, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5202, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5203, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5204, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5205, "2", ",", ".") . '</td>	
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5301, "2", ",", ".") . '</td>  
							   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5101, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5102, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5103, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5104, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5105, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5106, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5201, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5202, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5203, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5204, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5205, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5301, "2", ",", ".") . '</td>	
							   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_bel, "2", ",", ".") . '</td>                                
							   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_ang - $tot_bel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($per_bel, "2", ",", ".") . '</td> 
							</tr>';
		}

		$cRet .= "</table>";

		if ($denganttd == "1") {

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='wk'";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<TD align="center" >' . $jabatan . '</TD>
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
						<TD align="center" ><b></b></TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nama . '</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ></TD>
					</TR>
					</TABLE><br/>';
		} else {


			$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<TD align="center" ></TD>
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
						<TD align="center" ><u></u></TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ></TD>
					</TR>
					</TABLE><br/>';
		}

		$data['prev'] = $cRet;
		$judul = 'Perda_LampI';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf($judul, $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function cetak_perda_lampI_1_233_perda($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunang_b =   $lntahunang + 1;

		$denganttd = $this->uri->segment(7);

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
						<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"100\" height=\"100\" />
						</td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"></td></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><strong>$kab <br> RINGKASAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI<br>SAMPAI DENGAN " . strtoupper($judul) . " TAHUN ANGGARAN " . $lntahunang . "</strong></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
					</TABLE><br/>";

		$cRet .= "<table style=\"font-size:10px;font-family:Bookman Old Style; border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
				<thead>
				<tr>
					<td rowspan = \"4\" width=\"3.5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">KODE</td>
					<td rowspan = \"4\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">URUSAN PEMERINTAH DAERAH</td>
					<td colspan = \"4\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">PENDAPATAN</td>
					<td colspan = \"28\" width=\"71.5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BELANJA</td>
				</tr>
				<tr>
				   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">ANGGARAN</td> 
				   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">REALISASI</td> 
				   <td rowspan = \"2\" colspan =\"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BERTAMBAH/ KURANG</td> 
				   <td colspan =\"6\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">OPERASI</td> 
				   <td colspan =\"5\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">MODAL</td> 
				   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">TIDAK TERDUGA</td>   
				   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">JUMLAH ANGGARAN</td> 
				   <td colspan =\"6\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">OPERASI</td> 
				   <td colspan =\"5\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">MODAL</td> 
				   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">TIDAK TERDUGA</td> 
				   <td rowspan = \"2\" align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">JUMLAH REALISASI</td> 
				   <td colspan =\"2\" rowspan = \"2\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BERTAMBAH/ KURANG</td> 
				</tr>
				<tr>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">PEGAWAI</td> 
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BARANG DAN<BR/>JASA</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BUNGA</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">SUBSIDI</td> 
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">HIBAH</td>                     
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BANTUAN SOSIAL</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">TANAH</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">PERALATAN DAN <BR/>MESIN</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">GEDUNG DAN <BR/>BANGUNAN</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">JALAN, IRIGASI <BR/>DAN JARINGAN</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">ASET TETAP<BR/> LAINNYA</td>
				   
				   
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">PEGAWAI</td> 
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BARANG DAN<BR/>JASA</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BUNGA</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">SUBSIDI</td> 
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">HIBAH</td>                     
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">BANTUAN SOSIAL</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">TANAH</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">PERALATAN DAN <BR/>MESIN</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">GEDUNG DAN <BR/>BANGUNAN</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">JALAN, IRIGASI <BR/>DAN JARINGAN</td>
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">ASET TETAP<BR/> LAINNYA</td>
				   
				</tr>
				<tr>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">%</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td>                    
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td>                                       
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">RP.</td> 
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
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">11</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">12</td>                    
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">13</td>                                       
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">14</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">15</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">16</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">17</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">18</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">19</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">20</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">21</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">22</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">23</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">24</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">25</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">26</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">27</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">28</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">29</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">30</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">31</td>					   
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">32</td>	
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">33</td>					   
				   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:10px\">34</td>
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

		$sql = " select kd_kegiatan kode, kd_kegiatan1 kode1 ,nm_rek,ang_pend,ang_5101,ang_5102,ang_5103,ang_5104,ang_5105,ang_5106,ang_5201,ang_5202,ang_5203,ang_5204,ang_5205,ang_5301,real_pend,real_5101,real_5102,real_5103,real_5104,real_5105,real_5106,real_5201,real_5202,real_5203,real_5204,real_5205,real_5301 
					FROM [perda_lampI.3_sub_3_baru_33]($bulan,'$anggaran') ORDER BY kd_kegiatan
								";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$kode = $row->kode1;
			$nama = $row->nm_rek;
			$ang_pend = $row->ang_pend;
			$ang_5101 = $row->ang_5101;
			$ang_5102 = $row->ang_5102;
			$ang_5103 = $row->ang_5103;
			$ang_5104 = $row->ang_5104;
			$ang_5105 = $row->ang_5105;
			$ang_5106 = $row->ang_5106;
			$ang_5201 = $row->ang_5201;
			$ang_5202 = $row->ang_5202;
			$ang_5203 = $row->ang_5203;
			$ang_5204 = $row->ang_5204;
			$ang_5205 = $row->ang_5205;
			$ang_5301 = $row->ang_5301;
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
			$real_5301 = $row->real_5301;

			$tot_ang = $ang_5101 + $ang_5102 + $ang_5103 + $ang_5104 + $ang_5105 + $ang_5106 + $ang_5201 + $ang_5202 + $ang_5203 + $ang_5204 + $ang_5205 + $ang_5301;
			$tot_bel = $real_5101 + $real_5102 + $real_5103 + $real_5104 + $real_5105 + $real_5106 + $real_5201 + $real_5202 + $real_5203 + $real_5204 + $real_5205 + $real_5301;
			$per_pend  = $ang_pend == 0 || $ang_pend == '' ? 0 : $bel_pend / $ang_pend * 100;
			$per_bel  = $tot_ang == 0 || $tot_ang == '' ? 0 : $tot_bel / $tot_ang * 100;

			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:8px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:8px">' . strtoupper($nama) . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($bel_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_pend - $bel_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($per_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5101, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5102, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5103, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5104, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5105, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5106, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5201, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5202, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5203, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5204, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5205, "2", ",", ".") . '</td>	
							   <td align="right" valign="top" style="font-size:7px">' . number_format($ang_5301, "2", ",", ".") . '</td>  
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5101, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5102, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5103, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5104, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5105, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5106, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5201, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5202, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5203, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5204, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5205, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:7px">' . number_format($real_5301, "2", ",", ".") . '</td>	
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_bel, "2", ",", ".") . '</td>                                
							   <td align="right" valign="top" style="font-size:7px">' . number_format($tot_ang - $tot_bel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:7px">' . number_format($per_bel, "2", ",", ".") . '</td> 
							</tr>';
		}
		$sql = " select 'JUMLAH' nm_rek,sum(ang_pend) ang_pend,sum(ang_5101)ang_5101,sum(ang_5102)ang_5102,sum(ang_5103)ang_5103,sum(ang_5104)ang_5104,sum(ang_5105)ang_5105,sum(ang_5106)ang_5106,sum(ang_5201)ang_5201,sum(ang_5202)ang_5202,sum(ang_5203)ang_5203,sum(ang_5204)ang_5204,sum(ang_5205)ang_5205,sum(ang_5301)ang_5301,sum(real_pend)real_pend,sum(real_5101)real_5101,sum(real_5102)real_5102,sum(real_5103)real_5103,sum(real_5104)real_5104,sum(real_5105)real_5105,sum(real_5106)real_5106,sum(real_5201)real_5201,sum(real_5202)real_5202,sum(real_5203)real_5203,sum(real_5204)real_5204,sum(real_5205)real_5205,sum(real_5301)real_5301 FROM 
						 [perda_lampI.3_sub_3_baru_33]($bulan,'$anggaran') where len(kd_kegiatan)=1";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
			$nama = $row->nm_rek;
			$ang_pend = $row->ang_pend;
			$ang_5101 = $row->ang_5101;
			$ang_5102 = $row->ang_5102;
			$ang_5103 = $row->ang_5103;
			$ang_5104 = $row->ang_5104;
			$ang_5105 = $row->ang_5105;
			$ang_5106 = $row->ang_5106;
			$ang_5201 = $row->ang_5201;
			$ang_5202 = $row->ang_5202;
			$ang_5203 = $row->ang_5203;
			$ang_5204 = $row->ang_5204;
			$ang_5205 = $row->ang_5205;
			$ang_5301 = $row->ang_5301;
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
			$real_5301 = $row->real_5301;

			$tot_ang = $ang_5101 + $ang_5102 + $ang_5103 + $ang_5104 + $ang_5105 + $ang_5106 + $ang_5201 + $ang_5202 + $ang_5203 + $ang_5204 + $ang_5205 + $ang_5301;
			$tot_bel = $real_5101 + $real_5102 + $real_5103 + $real_5104 + $real_5105 + $real_5106 + $real_5201 + $real_5202 + $real_5203 + $real_5204 + $real_5205 + $real_5301;
			$per_pend  = $ang_pend == 0 || $ang_pend == '' ? 0 : $bel_pend / $ang_pend * 100;
			$per_bel  = $tot_ang == 0 || $tot_ang == '' ? 0 : $tot_bel / $tot_ang * 100;

			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:8px"></td> 
							   <td align="center"  valign="top" style="font-size:8px">' . strtoupper($nama) . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($bel_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_pend - $bel_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($per_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5101, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5102, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5103, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5104, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5105, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5106, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5201, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5202, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5203, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5204, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5205, "2", ",", ".") . '</td>	
							   <td align="right" valign="top" style="font-size:6px">' . number_format($ang_5301, "2", ",", ".") . '</td>  
							   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5101, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5102, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5103, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5104, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5105, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5106, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5201, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5202, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5203, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5204, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5205, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:6px">' . number_format($real_5301, "2", ",", ".") . '</td>	
							   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_bel, "2", ",", ".") . '</td>                                
							   <td align="right" valign="top" style="font-size:6px">' . number_format($tot_ang - $tot_bel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:6px">' . number_format($per_bel, "2", ",", ".") . '</td> 
							</tr>';
		}

		$cRet .= "</table>";

		if ($denganttd == "1") {

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='wk'";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<TD align="center" >' . $jabatan . '</TD>
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
						<TD align="center" ><b></b></TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nama . '</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ></TD>
					</TR>
					</TABLE><br/>';
		} else {


			$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<TD align="center" ></TD>
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
						<TD align="center" ><u></u></TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ></TD>
					</TR>
					</TABLE><br/>';
		}

		$data['prev'] = $cRet;
		$judul = 'Perda_LampI';
		switch ($ctk) {
			case 0;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 1;
				$this->tukd_model->_mpdf($judul, $cRet, 10, 10, 10, 'L');
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function cetak_perda_lampI_1($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		// if ($anggaran=='1'){
		// 	$ang='nilai';
		// }elseif  ($anggaran=='2'){
		// 	$ang='nilai_sempurna';
		// }elseif  ($anggaran=='11'){
		// 	$ang='nilaisempurna1';
		// }elseif  ($anggaran=='12'){
		// 	$ang='nilaisempurna2';
		// } else{
		// 	$ang='nilai_ubah';
		// }
		$ang = 'nilai';


		$tanggal = $tglttd == '-' ? '' : 'Melawi, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama_ttd = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama_ttd = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}
		$nip = '';

		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		$sqlnogub = "SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$ket_lampiran      = strtoupper("Lampiran I.1");
			$ket_perda         = strtoupper($rowsc->ket_perda);
			$ket_perda_no      = strtoupper($rowsc->ket_perda_no);
			$ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
		}

		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >' . $ket_lampiran . '</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >' . $ket_perda . '</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >' . $ket_perda_no . '</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >' . $ket_perda_tentang . '</TD>
					</TR>
					</TABLE><br/>';

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/kab-MELAWI.png\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH KABUPATEN MELAWI </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>RINGKASAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI </b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
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
		$sql = "SELECT a.kd_urusan as kode ,a.nm_urusan as nama,
isnull(ang_pend,0) as ang_pend, ISNULL(ang_peg,0) as ang_peg, ISNULL(ang_brjs,0) ang_brjs, ISNULL(ang_modal,0) ang_modal, 
ISNULL(ang_bunga,0) as ang_bunga, ISNULL(ang_subsidi,0) as ang_subsidi, ISNULL(ang_hibah,0) ang_hibah, ISNULL(ang_bansos,0) as ang_bansos, 
ISNULL(ang_bghasil,0) as ang_bghasil, ISNULL(ang_bantuan,0) as ang_bantuan, ISNULL(ang_takterduga,0) as ang_takterduga,
ISNULL(bel_pend,0) as bel_pend, ISNULL(bel_peg,0) as bel_peg, ISNULL(bel_brjs,0) bel_brjs, ISNULL(bel_modal,0) as bel_modal,
ISNULL(bel_bunga,0) as bel_bunga, ISNULL(bel_subsidi,0) as bel_subsidi, ISNULL(bel_hibah,0) as bel_hibah, ISNULL(bel_bansos,0) bel_bansos,
ISNULL(bel_bghasil,0) as bel_bghasil, ISNULL(bel_bantuan,0) as bel_bantuan, ISNULL(bel_takterduga,0) as bel_takterduga

FROM ms_urusan  a 
LEFT JOIN
(
SELECT a.kode, 
ang_pend, ang_peg, ang_brjs, ang_modal, ang_bunga, ang_subsidi, ang_hibah, ang_bansos, ang_bghasil, ang_bantuan, ang_takterduga,
bel_pend, bel_peg, bel_brjs, bel_modal, bel_bunga, bel_subsidi, bel_hibah, bel_bansos, bel_bghasil, bel_bantuan, bel_takterduga
FROM (
SELECT LEFT(a.kd_skpd,1) as kode 
,SUM(CASE WHEN LEFT(a.kd_rek6,1) = '4' THEN a.$ang ELSE 0 END) AS ang_pend
,SUM(CASE WHEN LEFT(a.kd_rek6,4) IN ('5101') THEN a.$ang ELSE 0 END) AS ang_peg
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5102' THEN a.$ang ELSE 0 END) AS ang_brjs
,SUM(CASE WHEN LEFT(a.kd_rek6,2) = '52' THEN a.$ang ELSE 0 END) AS ang_modal
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5103' THEN a.$ang ELSE 0 END) AS ang_bunga
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5104' THEN a.$ang ELSE 0 END) AS ang_subsidi
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5105' THEN a.$ang ELSE 0 END) AS ang_hibah
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5106' THEN a.$ang ELSE 0 END) AS ang_bansos
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5401' THEN a.$ang ELSE 0 END) AS ang_bghasil
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5402' THEN a.$ang ELSE 0 END) AS ang_bantuan
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5301' THEN a.$ang ELSE 0 END) AS ang_takterduga
FROM trdrka a WHERE a.jns_ang='$anggaran' GROUP BY LEFT(a.kd_skpd,1) ) a
LEFT JOIN 
(SELECT a.kode as kode 
,SUM(CASE WHEN LEFT(a.kd_rek,1) = '4' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
,SUM(CASE WHEN LEFT(a.kd_rek,4) IN ('5101') THEN a.nilai ELSE 0 END) AS bel_peg
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5102' THEN a.nilai ELSE 0 END) AS bel_brjs
,SUM(CASE WHEN LEFT(a.kd_rek,2) = '52' THEN a.nilai ELSE 0 END) AS bel_modal
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5103' THEN a.nilai ELSE 0 END) AS bel_bunga
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5104' THEN a.nilai ELSE 0 END) AS bel_subsidi
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5105' THEN a.nilai ELSE 0 END) AS bel_hibah
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5106' THEN a.nilai ELSE 0 END) AS bel_bansos
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5401' THEN a.nilai ELSE 0 END) AS bel_bghasil
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5402' THEN a.nilai ELSE 0 END) AS bel_bantuan
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5301' THEN a.nilai ELSE 0 END) AS bel_takterduga
FROM
(SELECT LEFT(a.kd_skpd,1) as kode ,LEFT(kd_rek6,4) kd_rek,SUM(realisasi) as nilai FROM (
SELECT a.kd_skpd, b.map_real kd_rek6,(b.debet - b.kredit) as realisasi FROM trhju_calk a INNER JOIN trdju_calk b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
WHERE LEFT(b.map_real,1) IN ('5','4') AND MONTH(a.tgl_voucher)<='$bulan' AND YEAR(a.tgl_voucher)='$lntahunang') a GROUP BY LEFT(a.kd_skpd,1) ,LEFT(kd_rek6,4))a
GROUP BY a.kode) b
ON a.kode=b.kode
) b ON a.kd_urusan=b.kode

UNION ALL
SELECT a.kd_bidang_urusan as kode ,a.nm_bidang_urusan as nama,
isnull(ang_pend,0) as ang_pend, ISNULL(ang_peg,0) as ang_peg, ISNULL(ang_brjs,0) ang_brjs, ISNULL(ang_modal,0) ang_modal, 
ISNULL(ang_bunga,0) as ang_bunga, ISNULL(ang_subsidi,0) as ang_subsidi, ISNULL(ang_hibah,0) ang_hibah, ISNULL(ang_bansos,0) as ang_bansos, 
ISNULL(ang_bghasil,0) as ang_bghasil, ISNULL(ang_bantuan,0) as ang_bantuan, ISNULL(ang_takterduga,0) as ang_takterduga,
ISNULL(bel_pend,0) as bel_bend, ISNULL(bel_peg,0) as bel_peg, ISNULL(bel_brjs,0) bel_brjs, ISNULL(bel_modal,0) as bel_modal,
ISNULL(bel_bunga,0) as bel_bunga, ISNULL(bel_subsidi,0) as bel_subsidi, ISNULL(bel_hibah,0) as bel_hibah, ISNULL(bel_bansos,0) bel_bansos,
ISNULL(bel_bghasil,0) as bel_bghasil, ISNULL(bel_bantuan,0) as bel_bantuan, ISNULL(bel_takterduga,0) as bel_takterduga

FROM ms_bidang_urusan  a 
LEFT JOIN
(
SELECT a.kode, 
ang_pend, ang_peg, ang_brjs, ang_modal, ang_bunga, ang_subsidi, ang_hibah, ang_bansos, ang_bghasil, ang_bantuan, ang_takterduga,
bel_pend, bel_peg, bel_brjs, bel_modal, bel_bunga, bel_subsidi, bel_hibah, bel_bansos, bel_bghasil, bel_bantuan, bel_takterduga
FROM (
SELECT LEFT(a.kd_skpd,4) as kode 
,SUM(CASE WHEN LEFT(a.kd_rek6,1) = '4' THEN a.$ang ELSE 0 END) AS ang_pend
,SUM(CASE WHEN LEFT(a.kd_rek6,4) IN ('5101') THEN a.$ang ELSE 0 END) AS ang_peg
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5102' THEN a.$ang ELSE 0 END) AS ang_brjs
,SUM(CASE WHEN LEFT(a.kd_rek6,2) = '52' THEN a.$ang ELSE 0 END) AS ang_modal
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5103' THEN a.$ang ELSE 0 END) AS ang_bunga
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5104' THEN a.$ang ELSE 0 END) AS ang_subsidi
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5105' THEN a.$ang ELSE 0 END) AS ang_hibah
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5106' THEN a.$ang ELSE 0 END) AS ang_bansos
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5401' THEN a.$ang ELSE 0 END) AS ang_bghasil
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5402' THEN a.$ang ELSE 0 END) AS ang_bantuan
,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5301' THEN a.$ang ELSE 0 END) AS ang_takterduga
FROM trdrka a WHERE a.jns_ang='$anggaran' GROUP BY LEFT(a.kd_skpd,4) ) a
LEFT JOIN 
(SELECT a.kode as kode 
,SUM(CASE WHEN LEFT(a.kd_rek,1) = '4' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
,SUM(CASE WHEN LEFT(a.kd_rek,4) IN ('5101') THEN a.nilai ELSE 0 END) AS bel_peg
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5102' THEN a.nilai ELSE 0 END) AS bel_brjs
,SUM(CASE WHEN LEFT(a.kd_rek,2) = '52' THEN a.nilai ELSE 0 END) AS bel_modal
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5103' THEN a.nilai ELSE 0 END) AS bel_bunga
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5104' THEN a.nilai ELSE 0 END) AS bel_subsidi
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5105' THEN a.nilai ELSE 0 END) AS bel_hibah
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5106' THEN a.nilai ELSE 0 END) AS bel_bansos
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5401' THEN a.nilai ELSE 0 END) AS bel_bghasil
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5402' THEN a.nilai ELSE 0 END) AS bel_bantuan
,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5301' THEN a.nilai ELSE 0 END) AS bel_takterduga
FROM
(SELECT LEFT(a.kd_skpd,4) as kode ,LEFT(kd_rek6,4) kd_rek,SUM(realisasi) as nilai FROM (
SELECT a.kd_skpd, b.map_real kd_rek6,(b.debet - b.kredit) as realisasi FROM trhju a INNER JOIN trdju b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
WHERE LEFT(b.map_real,1) IN ('5','4') AND MONTH(a.tgl_voucher)<='$bulan' AND YEAR(a.tgl_voucher)='$lntahunang') a GROUP BY LEFT(a.kd_skpd,4) ,LEFT(kd_rek6,4))a
GROUP BY a.kode) b
ON a.kode=b.kode
) b ON a.kd_bidang_urusan=b.kode

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
			SELECT LEFT(a.kd_skpd,17) as kode 
			,SUM(CASE WHEN LEFT(a.kd_rek6,1) = '4' THEN a.$ang ELSE 0 END) AS ang_pend
			,SUM(CASE WHEN LEFT(a.kd_rek6,4) IN ('5101') THEN a.$ang ELSE 0 END) AS ang_peg
			,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5102' THEN a.$ang ELSE 0 END) AS ang_brjs
			,SUM(CASE WHEN LEFT(a.kd_rek6,2) = '52' THEN a.$ang ELSE 0 END) AS ang_modal
			,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5103' THEN a.$ang ELSE 0 END) AS ang_bunga
			,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5104' THEN a.$ang ELSE 0 END) AS ang_subsidi
			,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5105' THEN a.$ang ELSE 0 END) AS ang_hibah
			,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5106' THEN a.$ang ELSE 0 END) AS ang_bansos
			,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5401' THEN a.$ang ELSE 0 END) AS ang_bghasil
			,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5402' THEN a.$ang ELSE 0 END) AS ang_bantuan
			,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5301' THEN a.$ang ELSE 0 END) AS ang_takterduga
			FROM trdrka a WHERE a.jns_ang='$anggaran' GROUP BY LEFT(a.kd_skpd,17) ) a
			LEFT JOIN 
			(SELECT a.kode as kode 
			,SUM(CASE WHEN LEFT(a.kd_rek,1) = '4' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
			,SUM(CASE WHEN LEFT(a.kd_rek,4) IN ('5101') THEN a.nilai ELSE 0 END) AS bel_peg
			,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5102' THEN a.nilai ELSE 0 END) AS bel_brjs
			,SUM(CASE WHEN LEFT(a.kd_rek,2) = '52' THEN a.nilai ELSE 0 END) AS bel_modal
			,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5103' THEN a.nilai ELSE 0 END) AS bel_bunga
			,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5104' THEN a.nilai ELSE 0 END) AS bel_subsidi
			,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5105' THEN a.nilai ELSE 0 END) AS bel_hibah
			,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5106' THEN a.nilai ELSE 0 END) AS bel_bansos
			,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5401' THEN a.nilai ELSE 0 END) AS bel_bghasil
			,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5402' THEN a.nilai ELSE 0 END) AS bel_bantuan
			,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5301' THEN a.nilai ELSE 0 END) AS bel_takterduga
			FROM
			(SELECT LEFT(a.kd_skpd,17) as kode ,LEFT(kd_rek6,4) kd_rek,SUM(realisasi) as nilai FROM (
			SELECT a.kd_skpd, b.map_real kd_rek6,(b.debet - b.kredit) as realisasi FROM trhju a INNER JOIN trdju b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
			WHERE LEFT(b.map_real,1) IN ('5','4') AND MONTH(a.tgl_voucher)<='$bulan' AND YEAR(a.tgl_voucher)='$lntahunang') a GROUP BY LEFT(a.kd_skpd,17) ,LEFT(kd_rek6,4))a
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
					,SUM(CASE WHEN LEFT(a.kd_rek6,1) = '4' THEN a.$ang ELSE 0 END) AS ang_pend
					,SUM(CASE WHEN LEFT(a.kd_rek6,4) IN ('5101') THEN a.$ang ELSE 0 END) AS ang_peg
					,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5102' THEN a.$ang ELSE 0 END) AS ang_brjs
					,SUM(CASE WHEN LEFT(a.kd_rek6,2) = '52' THEN a.$ang ELSE 0 END) AS ang_modal
					,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5103' THEN a.$ang ELSE 0 END) AS ang_bunga
					,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5104' THEN a.$ang ELSE 0 END) AS ang_subsidi
					,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5105' THEN a.$ang ELSE 0 END) AS ang_hibah
					,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5106' THEN a.$ang ELSE 0 END) AS ang_bansos
					,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5401' THEN a.$ang ELSE 0 END) AS ang_bghasil
					,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5402' THEN a.$ang ELSE 0 END) AS ang_bantuan
					,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5301' THEN a.$ang ELSE 0 END) AS ang_takterduga
					FROM trdrka a WHERE a.jns_ang='$anggaran' GROUP BY a.kd_skpd) a
					LEFT JOIN 
					(SELECT a.kd_skpd
					,SUM(CASE WHEN LEFT(a.kd_rek,1) = '4' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
					,SUM(CASE WHEN LEFT(a.kd_rek,4) IN ('5101') THEN a.nilai ELSE 0 END) AS bel_peg
					,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5102' THEN a.nilai ELSE 0 END) AS bel_brjs
					,SUM(CASE WHEN LEFT(a.kd_rek,2) = '52' THEN a.nilai ELSE 0 END) AS bel_modal
					,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5103' THEN a.nilai ELSE 0 END) AS bel_bunga
					,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5104' THEN a.nilai ELSE 0 END) AS bel_subsidi
					,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5105' THEN a.nilai ELSE 0 END) AS bel_hibah
					,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5106' THEN a.nilai ELSE 0 END) AS bel_bansos
					,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5401' THEN a.nilai ELSE 0 END) AS bel_bghasil
					,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5402' THEN a.nilai ELSE 0 END) AS bel_bantuan
					,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5301' THEN a.nilai ELSE 0 END) AS bel_takterduga
					FROM
					(SELECT kd_skpd,LEFT(kd_rek6,4) kd_rek,SUM(realisasi) as nilai FROM (
					SELECT a.kd_skpd, b.map_real kd_rek6,(b.debet - b.kredit) as realisasi FROM trhju a INNER JOIN trdju b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
					WHERE LEFT(b.map_real,1) IN ('5','4') AND MONTH(a.tgl_voucher)<='$bulan' AND YEAR(a.tgl_voucher)='$lntahunang') a GROUP BY kd_skpd,LEFT(kd_rek6,4))a
					GROUP BY a.kd_skpd) b
					ON a.kd_skpd=b.kd_skpd
					) b ON a.kd_skpd=b.kd_skpd
					order by kode
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$no = $no + 1;
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

			$tot_ang = $ang_peg + $ang_brjs + $ang_modal + $ang_bunga + $ang_subsidi + $ang_hibah + $ang_bansos + $ang_bghasil + $ang_bantuan + $ang_takterduga;
			$tot_bel = $bel_peg + $bel_brjs + $bel_modal + $bel_bunga + $bel_subsidi + $bel_hibah + $bel_bansos + $bel_bghasil + $bel_bantuan + $bel_takterduga;
			$per_pend  = $ang_pend == 0 || $ang_peg == '' ? 0 : $bel_pend / $ang_pend * 100;
			$per_bel  = $tot_ang == 0 || $tot_ang == '' ? 0 : $tot_bel / $tot_ang * 100;

			$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:12px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . strtoupper($nama) . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bel_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($bel_pend - $ang_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($per_pend, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_peg, "2", ",", ".") . '<br>
																					' . number_format($ang_brjs, "2", ",", ".") . '<br>
																					' . number_format($ang_modal, "2", ",", ".") . '<br>
																					' . number_format($ang_bunga, "2", ",", ".") . '<br>
																					' . number_format($ang_subsidi, "2", ",", ".") . '<br>
																					' . number_format($ang_hibah, "2", ",", ".") . '<br>
																					' . number_format($ang_bansos, "2", ",", ".") . '<br>
																					' . number_format($ang_bghasil, "2", ",", ".") . '<br>
																					' . number_format($ang_bantuan, "2", ",", ".") . '<br>
																					' . number_format($ang_takterduga, "2", ",", ".") . '
							   </td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
								 <td align="right" valign="top" style="font-size:12px">' . number_format($bel_peg, "2", ",", ".") . '<br>
																					' . number_format($bel_brjs, "2", ",", ".") . '<br>
																					' . number_format($bel_modal, "2", ",", ".") . '<br>
																					' . number_format($bel_bunga, "2", ",", ".") . '<br>
																					' . number_format($bel_subsidi, "2", ",", ".") . '<br>
																					' . number_format($bel_hibah, "2", ",", ".") . '<br>
																					' . number_format($bel_bansos, "2", ",", ".") . '<br>
																					' . number_format($bel_bghasil, "2", ",", ".") . '<br>
																					' . number_format($bel_bantuan, "2", ",", ".") . '<br>
																					' . number_format($bel_takterduga, "2", ",", ".") . '
							   </td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_bel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_bel - $tot_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($per_bel, "2", ",", ".") . '</td> 
							</tr>';
		}

		//TOTAL		
		$sql2 = "SELECT isnull(sum(ang_pend),0) ang_pend, isnull(sum(ang_peg),0) ang_peg, isnull(sum(ang_brjs),0) ang_brjs, isnull(sum(ang_modal),0) ang_modal,
						isnull(sum(ang_bunga),0) ang_bunga, isnull(sum(ang_subsidi),0) ang_subsidi, isnull(sum(ang_hibah),0) ang_hibah, 
						isnull(sum(ang_bansos),0) ang_bansos, isnull(sum(ang_bghasil),0) ang_bghasil, isnull(sum(ang_bantuan),0) ang_bantuan,
						isnull(sum(ang_takterduga),0) ang_takterduga, isnull(sum(bel_pend),0) bel_pend, isnull(sum(bel_peg),0) bel_peg, 
						isnull(sum(bel_brjs),0) bel_brjs, isnull(sum(bel_modal),0) bel_modal, isnull(sum(bel_bunga),0) bel_bunga,
						isnull(sum(bel_subsidi),0) bel_subsidi, isnull(sum(bel_hibah),0) bel_hibah, isnull(sum(bel_bansos),0) bel_bansos,
						isnull(sum(bel_bghasil),0) bel_bghasil, isnull(sum(bel_bantuan),0) bel_bantuan, isnull(sum(bel_takterduga),0) bel_takterduga
						from ( SELECT a.kd_urusan as kode ,a.nm_urusan as nama,
								isnull(ang_pend,0) as ang_pend, ISNULL(ang_peg,0) as ang_peg, ISNULL(ang_brjs,0) ang_brjs, ISNULL(ang_modal,0) ang_modal, 
								ISNULL(ang_bunga,0) as ang_bunga, ISNULL(ang_subsidi,0) as ang_subsidi, ISNULL(ang_hibah,0) ang_hibah, ISNULL(ang_bansos,0) as ang_bansos, 
								ISNULL(ang_bghasil,0) as ang_bghasil, ISNULL(ang_bantuan,0) as ang_bantuan, ISNULL(ang_takterduga,0) as ang_takterduga,
								ISNULL(bel_pend,0) as bel_pend, ISNULL(bel_peg,0) as bel_peg, ISNULL(bel_brjs,0) bel_brjs, ISNULL(bel_modal,0) as bel_modal,
								ISNULL(bel_bunga,0) as bel_bunga, ISNULL(bel_subsidi,0) as bel_subsidi, ISNULL(bel_hibah,0) as bel_hibah, ISNULL(bel_bansos,0) bel_bansos,
								ISNULL(bel_bghasil,0) as bel_bghasil, ISNULL(bel_bantuan,0) as bel_bantuan, ISNULL(bel_takterduga,0) as bel_takterduga

							FROM ms_urusan  a 
							LEFT JOIN
							(
							SELECT a.kode, 
							ang_pend, ang_peg, ang_brjs, ang_modal, ang_bunga, ang_subsidi, ang_hibah, ang_bansos, ang_bghasil, ang_bantuan, ang_takterduga,
							bel_pend, bel_peg, bel_brjs, bel_modal, bel_bunga, bel_subsidi, bel_hibah, bel_bansos, bel_bghasil, bel_bantuan, bel_takterduga
							FROM (
							SELECT LEFT(a.kd_skpd,1) as kode 
							,SUM(CASE WHEN LEFT(a.kd_rek6,1) = '4' THEN a.$ang ELSE 0 END) AS ang_pend
							,SUM(CASE WHEN LEFT(a.kd_rek6,4) IN ('5101') THEN a.$ang ELSE 0 END) AS ang_peg
							,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5102' THEN a.$ang ELSE 0 END) AS ang_brjs
							,SUM(CASE WHEN LEFT(a.kd_rek6,2) = '52' THEN a.$ang ELSE 0 END) AS ang_modal
							,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5103' THEN a.$ang ELSE 0 END) AS ang_bunga
							,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5104' THEN a.$ang ELSE 0 END) AS ang_subsidi
							,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5105' THEN a.$ang ELSE 0 END) AS ang_hibah
							,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5106' THEN a.$ang ELSE 0 END) AS ang_bansos
							,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5401' THEN a.$ang ELSE 0 END) AS ang_bghasil
							,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5402' THEN a.$ang ELSE 0 END) AS ang_bantuan
							,SUM(CASE WHEN LEFT(a.kd_rek6,4) = '5301' THEN a.$ang ELSE 0 END) AS ang_takterduga
							FROM trdrka a WHERE a.jns_ang='$anggaran' GROUP BY LEFT(a.kd_skpd,1) ) a
							LEFT JOIN 
							(SELECT a.kode as kode 
							,SUM(CASE WHEN LEFT(a.kd_rek,1) = '4' THEN a.nilai*-1 ELSE 0 END) AS bel_pend
							,SUM(CASE WHEN LEFT(a.kd_rek,4) IN ('5101') THEN a.nilai ELSE 0 END) AS bel_peg
							,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5102' THEN a.nilai ELSE 0 END) AS bel_brjs
							,SUM(CASE WHEN LEFT(a.kd_rek,2) = '52' THEN a.nilai ELSE 0 END) AS bel_modal
							,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5103' THEN a.nilai ELSE 0 END) AS bel_bunga
							,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5104' THEN a.nilai ELSE 0 END) AS bel_subsidi
							,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5105' THEN a.nilai ELSE 0 END) AS bel_hibah
							,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5106' THEN a.nilai ELSE 0 END) AS bel_bansos
							,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5401' THEN a.nilai ELSE 0 END) AS bel_bghasil
							,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5402' THEN a.nilai ELSE 0 END) AS bel_bantuan
							,SUM(CASE WHEN LEFT(a.kd_rek,4) = '5301' THEN a.nilai ELSE 0 END) AS bel_takterduga
							FROM
							(SELECT LEFT(a.kd_skpd,1) as kode ,LEFT(kd_rek6,4) kd_rek,SUM(realisasi) as nilai FROM (
							SELECT a.kd_skpd, b.map_real kd_rek6,(b.debet - b.kredit) as realisasi FROM trhju a INNER JOIN trdju b ON a.kd_skpd = b.kd_unit AND a.no_voucher=b.no_voucher
							WHERE LEFT(b.map_real,1) IN ('5','4') AND MONTH(a.tgl_voucher)<='$bulan' AND YEAR(a.tgl_voucher)='$lntahunang') a GROUP BY LEFT(a.kd_skpd,1) ,LEFT(kd_rek6,4))a
							GROUP BY a.kode) b
							ON a.kode=b.kode
							) b ON a.kd_urusan=b.kode ) z";

		$hasil2 = $this->db->query($sql2);
		foreach ($hasil2->result() as $row2) {

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

			$tot_ang2 = $ang_peg2 + $ang_brjs2 + $ang_modal2 + $ang_bunga2 + $ang_subsidi2 + $ang_hibah2 + $ang_bansos2 + $ang_bghasil2 + $ang_bantuan2 + $ang_takterduga2;
			$tot_bel2 = $bel_peg2 + $bel_brjs2 + $bel_modal2 + $bel_bunga2 + $bel_subsidi2 + $bel_hibah2 + $bel_bansos2 + $bel_bghasil2 + $bel_bantuan2 + $bel_takterduga2;
			$per_pend2  = $ang_pend2 == 0 || $ang_peg2 == '' ? 0 : $bel_pend2 / $ang_pend2 * 100;
			$per_bel2  = $tot_ang2 == 0 || $tot_ang2 == '' ? 0 : $tot_bel2 / $tot_ang2 * 100;

			$cRet .= '<tr>
							   <td align="center" valign="top" style="font-size:12px" colspan="2"><b>TOTAL</b></td> 
							   
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_pend2, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($bel_pend2, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($bel_pend2 - $ang_pend2, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($per_pend2, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_peg2, "2", ",", ".") . '<br>
																					' . number_format($ang_brjs2, "2", ",", ".") . '<br>
																					' . number_format($ang_modal2, "2", ",", ".") . '<br>
																					' . number_format($ang_bunga2, "2", ",", ".") . '<br>
																					' . number_format($ang_subsidi2, "2", ",", ".") . '<br>
																					' . number_format($ang_hibah2, "2", ",", ".") . '<br>
																					' . number_format($ang_bansos2, "2", ",", ".") . '<br>
																					' . number_format($ang_bghasil2, "2", ",", ".") . '<br>
																					' . number_format($ang_bantuan2, "2", ",", ".") . '<br>
																					' . number_format($ang_takterduga2, "2", ",", ".") . '</b>
							   </td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($tot_ang2, "2", ",", ".") . '</b></td> 
								 <td align="right" valign="top" style="font-size:12px"><b>' . number_format($bel_peg2, "2", ",", ".") . '<br>
																					' . number_format($bel_brjs2, "2", ",", ".") . '<br>
																					' . number_format($bel_modal2, "2", ",", ".") . '<br>
																					' . number_format($bel_bunga2, "2", ",", ".") . '<br>
																					' . number_format($bel_subsidi2, "2", ",", ".") . '<br>
																					' . number_format($bel_hibah2, "2", ",", ".") . '<br>
																					' . number_format($bel_bansos2, "2", ",", ".") . '<br>
																					' . number_format($bel_bghasil2, "2", ",", ".") . '<br>
																					' . number_format($bel_bantuan2, "2", ",", ".") . '<br>
																					' . number_format($bel_takterduga2, "2", ",", ".") . '</b>
							   </td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($tot_bel2, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($tot_bel2 - $tot_ang2, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($per_bel2, "2", ",", ".") . '</b></td> 
							</tr>';
		}

		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
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


	function cetak_perda_lampI_1_2_rekap($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunang_b =   $lntahunang - 1;

		$denganttd = $this->uri->segment(7);

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
                        NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunang_b . ' <br/>TENTANG PERTANGGUNGJAWABAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
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
                        <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"100\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"> </td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><strong>$kab<br>RINGKASAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH DAN ORGANISASI <br> SAMPAI DENGAN " . $judul . " TAHUN ANGGARAN " . $lntahunang . "</strong></tr>
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
					FROM [perda_lampI.3_sub_3_rekap]($bulan,'$anggaran') group by kd_kegiatan1,nm_rek ORDER BY kd_kegiatan1
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
					FROM [perda_lampI.3_sub_3_total_rekap]($bulan,'$anggaran') 
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

		if ($denganttd == "1") {

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='wk' ";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<TD align="center" >' . $jabatan . '</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b></TD>
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
						<TD align="center" >' . $nama . '</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ></TD>
					</TR>
					</TABLE><br/>';
		} else {


			$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<TD align="center" ></TD>
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
						<TD align="center" ></TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ></TD>
					</TR>
					</TABLE><br/>';
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
		$this->template->load('template', 'perda/cetak_perda_lampI_2', $data);
	}

	function cetak_perda_lampI_2_akun64_perwa($bulan = '', $ctk = '', $anggaran = '', $kd_skpd = '', $jenis = '', $tglttd = '', $ttd = '', $ttdperda = '', $label = '')
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
			$label = '';
		} else {
			$label = 'AUDITED';
		}

		$where = "WHERE a.kd_skpd='$kd_skpd'";
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
						<TD width="30%" align="left" >LAMPIRAN I.2 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN BUPATI KABUPATEN MELAWI <br/> 
                        NOMOR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; TAHUN ' . $lntahunangn . ' <br/>TENTANG PENJABARAN PELAKSANAAN <br/> ANGGARAN PENDAPATAN DAN BELANJA DAERAH<br/>KABUPATEN MELAWI TAHUN ANGGARAN ' . $lntahunang . '
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
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td align=\"center\" style=\"border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>RINCIAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH,ORGANISASI, </b></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>PENDAPATAN, BELANJA DAN PEMBIAYAAN</b></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN " . $lntahunang . " <br/> $label</b></tr>
					</TABLE>";
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan', 'ms_urusan', 'kd_urusan') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 17) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 17), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
					<!--<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $kd_skpd . " - " . $this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>-->
                    </TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"14%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KODE REKENING</b></td>
                    <td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/(BERKURANG)</b></td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DASAR HUKUM</b></td>					
					<td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>LOKASI<br/>KEGIATAN</b></td>
					<td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SUMBER<br/>DANA</b></td>
					<td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KELUARAN</b></td>
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
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >8</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >9</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >10</td>
				</tr>
				</thead>";

		//--SELECT kd_subkegiatan as kd_kegiatan, kd_ang as kd_rek,nm_rek,nilai_ang as anggaran,real_spj as sd_bulan_ini FROM data_realisasi_keg4($bulan,$anggaran) $where and left(kd_ang,1)='4' ORDER BY kd_kegiatan,kd_rek
		$sisa1 = 0;
		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,1)='4' and bulan='$bulan'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang14 = $row->anggaran;
			$sd_bulan_ini14 = $row->sd_bulan_ini;
			$sisa1 = $sd_bulan_ini14 - $nil_ang14;
			$persen1 = empty($nil_ang14) || $nil_ang14 == 0 ? 0 : $sd_bulan_ini14 / $nil_ang14 * 100;
			$sisa11 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;
			$a = $sisa1 < 0 ? '(' : '';
			$b = $sisa1 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>PENDAPATAN DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang14, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini14, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"><b></td>
							</tr>';
		}
		$hasil->free_result();



		if ($jenis == "4") {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
					) z
					group by kd_kegiatan,kode,nama, sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == "6") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
						union all  
						----akun4
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == "8") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
						union all  
						----akun4
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
						union all
						----akun5
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
						inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == "12") {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
						union all  
						----akun4
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
						union all
						----akun5
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
						inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
						union all
						----akun6
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek6 as kode,b.nm_rek6 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,a.sumber_dana as sumber FROM data_realisasi_pemkot a
						inner join ms_rek6 b on b.kd_rek6 = a.kd_rek6                                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.sumber_dana  
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		}


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sumber = $row->sumber;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';
			$leng = strlen($kd_rek);
			$leng = ($leng > 8) ? '12' : $leng;
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 12:
					$cRet .= '<tr>
								<td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="left" valign="top">' . $sumber . '</td>
								<td align="right" valign="top"></td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
					break;
			}
		}

		$hasil->free_result();

		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,1)='4' and bulan='$bulan'";
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
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"><b></td>
							</tr>';
		}
		$hasil->free_result();


		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top">&nbsp;</td> 
							   <td align="right" valign="top">&nbsp;</td> 
							   <td align="right" valign="top">&nbsp;</td> 
							   <td align="right" valign="top">&nbsp;</td> 
							   <td align="right" valign="top">&nbsp;</td>							    
							   <td align="right" valign="top">&nbsp;</td> 
							   <td align="right" valign="top">&nbsp;</td> 
							   <td align="right" valign="top">&nbsp;</td>
							</tr>';

		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,1) in ('5','6') and bulan='$bulan'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang13 = $row->anggaran;
			$sd_bulan_ini13 = $row->sd_bulan_ini;
			$sisa13 = $sd_bulan_ini13 - $nil_ang13;
			$persen13 = empty($nil_ang13) || $nil_ang13 == 0 ? 0 : $sd_bulan_ini13 / $nil_ang13 * 100;
			$sisa13 = $sisa13 < 0 ? $sisa13 * -1 : $sisa13;
			$a = $sisa13 < 0 ? '(' : '';
			$b = $sisa13 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang13, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini13, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa13, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen13, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"><b></td>
							</tr>';
		}


		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,2)='51' and bulan='$bulan'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang1 = $row->anggaran;
			$sd_bulan_ini1 = $row->sd_bulan_ini;
			$sisa1 = $sd_bulan_ini1 - $nil_ang1;
			$persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 : $sd_bulan_ini1 / $nil_ang1 * 100;
			$sisa11 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;
			$a = $sisa1 < 0 ? '(' : '';
			$b = $sisa1 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA OPERASI</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></td>
							</tr>';
		}


		$hasil->free_result();

		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

		if ($jenis == "4") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == "6") {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='51' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == 8) {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
						union all  
						----akun4
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
						union all
						----akun5
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
						inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == 12) {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
						union all  
						----akun4
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
						union all
						----akun5
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
						inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
						union all
						----akun6
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek6 as kode,b.nm_rek6 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,a.sumber_dana as sumber FROM data_realisasi_pemkot a
						inner join ms_rek6 b on b.kd_rek6 = a.kd_rek6                                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.sumber_dana  
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		}

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sumber = $row->sumber;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			$leng = ($leng > 8) ? '12' : $leng;
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
					break;
				case 12:
					$cRet .= '<tr>
								<td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="left" valign="top">' . $sumber . '</td>
								<td align="right" valign="top"></td>
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
			}
		}

		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,2)='52' and bulan='$bulan'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang12 = $row->anggaran;
			$sd_bulan_ini12 = $row->sd_bulan_ini;
			$sisa12 = $sd_bulan_ini12 - $nil_ang12;
			$persen12 = empty($nil_ang12) || $nil_ang12 == 0 ? 0 : $sd_bulan_ini12 / $nil_ang12 * 100;
			$sisa12 = $sisa12 < 0 ? $sisa12 * -1 : $sisa12;
			$a = $sisa12 < 0 ? '(' : '';
			$b = $sisa12 < 0 ? ')' : '';

			if ($nil_ang12 != 0) {
				$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA MODAL</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa12, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
			}
		}


		$hasil->free_result();
		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

		if ($jenis == "4") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == "6") {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == "8") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
						union all  
						----akun4
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
						union all
						----akun5
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
						inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == 12) {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
				union all
				----akun5
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
				inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
				union all
				----akun6
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek6 as kode,b.nm_rek6 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,a.sumber_dana as sumber FROM data_realisasi_pemkot a
				inner join ms_rek6 b on b.kd_rek6 = a.kd_rek6                                        
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.sumber_dana  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		}

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sumber = $row->sumber;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			$leng = ($leng > 8) ? '12' : $leng;
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 12:
					$cRet .= '<tr>
								<td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="left" valign="top">' . $sumber . '</td>
								<td align="right" valign="top"></td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
			}
		}

		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,2)='53' and bulan='$bulan'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang12 = $row->anggaran;
			$sd_bulan_ini12 = $row->sd_bulan_ini;
			$sisa12 = $sd_bulan_ini12 - $nil_ang12;
			$persen12 = empty($nil_ang12) || $nil_ang12 == 0 ? 0 : $sd_bulan_ini12 / $nil_ang12 * 100;
			$sisa12 = $sisa12 < 0 ? $sisa12 * -1 : $sisa12;
			$a = $sisa12 < 0 ? '(' : '';
			$b = $sisa12 < 0 ? ')' : '';

			if ($nil_ang12 != 0) {
				$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA TAK TERDUGA</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa12, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
			}
		}


		$hasil->free_result();
		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

		if ($jenis == "4") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == "6") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == '8') {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
				union all
				----akun5
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
				inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5 
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == 12) {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
				union all
				----akun5
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
				inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
				union all
				----akun6
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek6 as kode,b.nm_rek6 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,a.sumber_dana as sumber FROM data_realisasi_pemkot a
				inner join ms_rek6 b on b.kd_rek6 = a.kd_rek6                                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.sumber_dana  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		}

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sumber = $row->sumber;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			$leng = ($leng > 8) ? '12' : $leng;
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 12:
					$cRet .= '<tr>
								<td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="right" valign="top">' . $sumber . '</td>
								<td align="right" valign="top"></td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
			}
		}

		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,2)='61' and bulan='$bulan'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang12 = $row->anggaran;
			$sd_bulan_ini12 = $row->sd_bulan_ini;
			$sisa12 = $sd_bulan_ini12 - $nil_ang12;
			$persen12 = empty($nil_ang12) || $nil_ang12 == 0 ? 0 : $sd_bulan_ini12 / $nil_ang12 * 100;
			$sisa12 = $sisa12 < 0 ? $sisa12 * -1 : $sisa12;
			$a = $sisa12 < 0 ? '(' : '';
			$b = $sisa12 < 0 ? ')' : '';

			if ($nil_ang12 != 0) {
				$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>TRANSFER BAGI HASIL PENDAPATAN</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa12, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
			}
		}


		$hasil->free_result();
		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

		if ($jenis == "4") {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == "6") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
						union all  
						----akun4
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
						$where and left(a.kd_rek6,2)='61' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == '8') {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
				union all
				----akun5
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
				inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5 
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == '12') {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
				union all
				----akun5
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
				inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
				union all
				----akun6
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek6 as kode,b.nm_rek6 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,a.sumber_dana as sumber FROM data_realisasi_pemkot a
				inner join ms_rek6 b on b.kd_rek6 = a.kd_rek6                                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.sumber_dana  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		}

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			$leng = ($leng > 8) ? '12' : $leng;
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 12:
					$cRet .= '<tr>
								<td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
			}
		}

		$sql = "SELECT SUM(a.nilai_ang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,2)='62' and bulan='$bulan'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang12 = $row->anggaran;
			$sd_bulan_ini12 = $row->sd_bulan_ini;
			$sisa12 = $sd_bulan_ini12 - $nil_ang12;
			$persen12 = empty($nil_ang12) || $nil_ang12 == 0 ? 0 : $sd_bulan_ini12 / $nil_ang12 * 100;
			$sisa12 = $sisa12 < 0 ? $sisa12 * -1 : $sisa12;
			$a = $sisa12 < 0 ? '(' : '';
			$b = $sisa12 < 0 ? ')' : '';

			if ($nil_ang12 != 0) {
				$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>TRANSFER BANTUAN KEUANGAN</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa12, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
			}
		}


		$hasil->free_result();
		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

		if ($jenis == "4") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == "6") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == 8) {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
				union all
				----akun5
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
				inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == '12') {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
				union all
				----akun5
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
				inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
				union all
				----akun6
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek6 as kode,b.nm_rek6 as nama,sum(a.nilai_ang) as anggaran,sum(a.real_spj) as reali,a.sumber_dana as sumber FROM data_realisasi_pemkot a
				inner join ms_rek6 b on b.kd_rek6 = a.kd_rek6                                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.sumber_dana  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		}

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			$leng = ($leng > 8) ? '12' : $leng;
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 12:
					$cRet .= '<tr>
								<td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
			}
		}


		$hasil->free_result();
		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang13, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini13, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa13, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen13, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';


		$sql = "select isnull(sum(z.anggaran_4),0)-isnull(sum(z.anggaran_5),0) as anggaran, 
				isnull(sum(z.sd_bulan_ini_4),0)-isnull(sum(z.sd_bulan_ini_5),0) as sd_bulan_ini from (
				SELECT SUM(a.nilai_ang) anggaran_4, SUM(a.real_spj) sd_bulan_ini_4,0 anggaran_5 , 0 as sd_bulan_ini_5
				FROM data_realisasi_pemkot a 
				$where AND left(a.kd_ang,1) in ('4') and bulan='$bulan'
				UNION
				SELECT 0 as anggaran_4, 0 as sd_bulan_ini_4, SUM(a.nilai_ang) anggaran_5 ,SUM(a.real_spj) sd_bulan_ini_5
				FROM data_realisasi_pemkot a 
				$where AND left(a.kd_ang,1) in ('5') and bulan='$bulan'
				)z";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang145 = $row->anggaran;
			$sd_bulan_ini145 = $row->sd_bulan_ini;
			$sisa1 = $sd_bulan_ini145 - $nil_ang145;
			$persen145 = empty($nil_ang145) || $nil_ang145 == 0 ? 0 : $sd_bulan_ini145 / $nil_ang145 * 100;
			$a45 = $sisa1 < 0 ? '(' : '';
			$b45 = $sisa1 < 0 ? ')' : '';
			$sisa1145 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;

			$a445 = $nil_ang145 < 0 ? '(' : '';
			$b445 = $nil_ang145 < 0 ? ')' : '';
			$nil_ang1455 = $nil_ang145 < 0 ? $nil_ang145 * -1 : $nil_ang145;

			$a455 = $sd_bulan_ini145 < 0 ? '(' : '';
			$b455 = $sd_bulan_ini145 < 0 ? ')' : '';
			$sd_bulan_ini1455 = $sd_bulan_ini145 < 0 ? $sd_bulan_ini145 * -1 : $sd_bulan_ini145;


			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="right"  valign="top"><b>SURPLUS/(DEFISIT)</b></td> 
							   <td align="right" valign="top"><b>' . $a445 . ' ' . number_format($nil_ang1455, "2", ",", ".") . ' ' . $b445 . '</b></td> 
							   <td align="right" valign="top"><b>' . $a455 . ' ' . number_format($sd_bulan_ini1455, "2", ",", ".") . ' ' . $b455 . '</b></td> 
							   <td align="right" valign="top"><b>' . $a45 . ' ' . number_format($sisa1145, "2", ",", ".") . ' ' . $b45 . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen145, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= "</table>";

		$tanggal = $tglttd == '-' ? '' : 'Pontianak, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($kd_skpd == "8.01.0.00.0.00.01.0000") {

			$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='8.01.0.00.0.00.01.0000'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowsc) {
				$kab     = $rowsc->kab_kota;
				$daerah  = $rowsc->daerah;
			}

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip ='$ttd1' and kode in ('wk','agr')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			$cRet .= '<TABLE style="border-collapse:collapse; font-family:Bookman Old Style;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<!--<TD align="center" >' . $tanggal . '</TD>-->
					</TR>
					
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $jabatan . '</TD>
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
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>                    
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nama . '</TD>
					</TR>
                    <!--<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip . '</TD>
					</TR>-->
					</TABLE><br/>';
		} else {

			$cRet .= '<TABLE style="border-collapse:collapse; font-family:Bookman Old Style;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<TD align="center" ></TD>
					</TR>
					
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b></b></TD>
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
						<TD align="center" ><b></b></TD>
					</TR>
                    <!--<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ></TD>
					</TR>-->
					</TABLE><br/>';
		}

		$data['prev'] = $cRet;
		$judul = 'PERDA_LAMP_I.2 ';
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

	function left($string, $count)
	{
		return substr($string, 0, $count);
	}

	function cetak_perda_lampI_2_akun64_perda($bulan = '', $ctk = '', $anggaran = '', $kd_skpd = '', $jenis = '', $tglttd = '', $ttd = '', $ttdperda = '', $label = '')
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

		$initang = "a.nilai_ang";

		if ($label == '1') {
			$label = '';
		} else {
			$label = 'AUDITED';
		}

		$where = "WHERE a.kd_skpd='$kd_skpd'";
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
						<TD width="30%" align="left" >LAMPIRAN I.2 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/> PERATURAN DAERAH KABUPATEN MELAWI <br/> 
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
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td align=\"center\" style=\"border-bottom:hidden\"><strong>" . $sclient->kab_kota . "</strong></td></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>RINCIAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH,ORGANISASI, </b></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>PENDAPATAN, BELANJA DAN PEMBIAYAAN</b></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>TAHUN ANGGARAN " . $lntahunang . " <br/> $label</b></tr>
					</TABLE>";
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:12px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td width=\"15%\" align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Urusan Pemerintahan </td>
					<td width=\"85%\" align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 1) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 1), 'nm_urusan', 'ms_urusan', 'kd_urusan') . " </td>
					</tr>
					<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\"> &nbsp;&nbsp; Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $this->left($kd_skpd, 17) . " - " . $this->tukd_model->get_nama($this->left($kd_skpd, 17), 'nm_org', 'ms_organisasi', 'kd_org') . "</td>
					</tr>
					<!--<tr>
					<td align=\"left\" style=\"border-right:hidden;border-bottom:hidden\">&nbsp;&nbsp; Sub Unit Organisasi </td>
					<td align=\"left\" style=\"border-left:hidden;border-bottom:hidden\"> : " . $kd_skpd . " - " . $this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . "</td>
					</tr>-->
                    </TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Bookman Old Style;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"3\" cellpadding=\"3\">
                <thead>
				<tr>
                    <td rowspan=\"2\" width=\"14%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KODE REKENING</b></td>
                    <td rowspan=\"2\" width=\"25%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>URAIAN</b></td>
                    <td colspan=\"2\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>JUMLAH (Rp.)</b></td>
                    <td colspan=\"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>BERTAMBAH/(BERKURANG)</b></td>
                    <td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>DASAR HUKUM</b></td>					
					<td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>LOKASI<br/>KEGIATAN</b></td>
					<td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>SUMBER<br/>DANA</b></td>
					<td rowspan=\"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" ><b>KELUARAN</b></td>
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
                   <td align=\"center\" bgcolor=\"#CCCCCC\" >7</td> 
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >8</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >9</td>
				   <td align=\"center\" bgcolor=\"#CCCCCC\" >10</td>
				</tr>
				</thead>";

		//--SELECT kd_subkegiatan as kd_kegiatan, kd_ang as kd_rek,nm_rek,nilai_ang as anggaran,real_spj as sd_bulan_ini FROM data_realisasi_keg4($bulan,$anggaran) $where and left(kd_ang,1)='4' ORDER BY kd_kegiatan,kd_rek
		$sisa1 = 0;
		$sql = "SELECT SUM($initang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,1)='4' and bulan='$bulan'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang14 = $row->anggaran;
			$sd_bulan_ini14 = $row->sd_bulan_ini;
			$sisa1 = $sd_bulan_ini14 - $nil_ang14;
			$persen1 = empty($nil_ang14) || $nil_ang14 == 0 ? 0 : $sd_bulan_ini14 / $nil_ang14 * 100;
			$sisa11 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;
			$a = $sisa1 < 0 ? '(' : '';
			$b = $sisa1 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>PENDAPATAN DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang14, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini14, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></b></td> 
							   <td align="right" valign="top"><b></b></td>
							   <td align="right" valign="top"><b></b></td>
							   <td align="right" valign="top"><b></b></td>
							  
							</tr>';
		}
		$hasil->free_result();



		if ($jenis == "4") {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' AND b.jns_ang='$anggaran'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
					) z
					group by kd_kegiatan,kode,nama, sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == "6") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' AND b.jns_ang='$anggaran'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
						union all  
						----akun4
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == "8") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' AND b.jns_ang='$anggaran'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
						union all  
						----akun4
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
						union all
						----akun5
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
						inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == "12") {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' AND b.jns_ang='$anggaran'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
						union all  
						----akun4
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
						union all
						----akun5
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
						inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
						union all
						----akun6
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek6 as kode,b.nm_rek6 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,a.sumber_dana as sumber FROM data_realisasi_pemkot a
						inner join ms_rek6 b on b.kd_rek6 = a.kd_rek6                                        
						$where and left(a.kd_rek6,1)='4' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.sumber_dana  
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		}


		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sumber = $row->sumber;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';
			$leng = strlen($kd_rek);
			$leng = ($leng > 8) ? '12' : $leng;
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 12:
					$cRet .= '<tr>
								<td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="left" valign="top">' . $sumber . '</td>
								<td align="right" valign="top"></td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
					break;
			}
		}

		$hasil->free_result();

		$sql = "SELECT SUM($initang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,1)='4' and bulan='$bulan'";
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
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH PENDAPATAN</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"><b></td>
							</tr>';
		}
		$hasil->free_result();


		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top">&nbsp;</td> 
							   <td align="right" valign="top">&nbsp;</td> 
							   <td align="right" valign="top">&nbsp;</td> 
							   <td align="right" valign="top">&nbsp;</td> 
							   <td align="right" valign="top">&nbsp;</td> 
							   <td align="right" valign="top">&nbsp;</td>							    
							   <td align="right" valign="top">&nbsp;</td> 
							   <td align="right" valign="top">&nbsp;</td> 
							   <td align="right" valign="top">&nbsp;</td>
							</tr>';

		$sql = "SELECT SUM($initang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,1) in ('5','6') and bulan='$bulan'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang13 = $row->anggaran;
			$sd_bulan_ini13 = $row->sd_bulan_ini;
			$sisa13 = $sd_bulan_ini13 - $nil_ang13;
			$persen13 = empty($nil_ang13) || $nil_ang13 == 0 ? 0 : $sd_bulan_ini13 / $nil_ang13 * 100;
			$sisa13 = $sisa13 < 0 ? $sisa13 * -1 : $sisa13;
			$a = $sisa13 < 0 ? '(' : '';
			$b = $sisa13 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA DAERAH</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang13, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini13, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa13, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen13, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"><b></td>
							</tr>';
		}


		$sql = "SELECT SUM($initang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,2)='51' and bulan='$bulan'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang1 = $row->anggaran;
			$sd_bulan_ini1 = $row->sd_bulan_ini;
			$sisa1 = $sd_bulan_ini1 - $nil_ang1;
			$persen1 = empty($nil_ang1) || $nil_ang1 == 0 ? 0 : $sd_bulan_ini1 / $nil_ang1 * 100;
			$sisa11 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;
			$a = $sisa1 < 0 ? '(' : '';
			$b = $sisa1 < 0 ? ')' : '';

			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA OPERASI</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa11, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen1, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></td> 
							   <td align="right" valign="top"><b></td>
							</tr>';
		}


		$hasil->free_result();

		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

		if ($jenis == "4") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == "6") {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='51' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == 8) {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
						union all  
						----akun4
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
						union all
						----akun5
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
						inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == 12) {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
						union all  
						----akun4
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
						union all
						----akun5
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
						inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
						union all
						----akun6
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek6 as kode,b.nm_rek6 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,a.sumber_dana as sumber FROM data_realisasi_pemkot a
						inner join ms_rek6 b on b.kd_rek6 = a.kd_rek6                                        
						$where and left(a.kd_rek6,2)='51' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.sumber_dana  
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		}

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sumber = $row->sumber;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			$leng = ($leng > 8) ? '12' : $leng;
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
					break;
				case 12:
					$cRet .= '<tr>
								<td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="left" valign="top">' . $sumber . '</td>
								<td align="right" valign="top"></td>
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
			}
		}

		$sql = "SELECT SUM($initang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,2)='52' and bulan='$bulan'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang12 = $row->anggaran;
			$sd_bulan_ini12 = $row->sd_bulan_ini;
			$sisa12 = $sd_bulan_ini12 - $nil_ang12;
			$persen12 = empty($nil_ang12) || $nil_ang12 == 0 ? 0 : $sd_bulan_ini12 / $nil_ang12 * 100;
			$sisa12 = $sisa12 < 0 ? $sisa12 * -1 : $sisa12;
			$a = $sisa12 < 0 ? '(' : '';
			$b = $sisa12 < 0 ? ')' : '';

			if ($nil_ang12 != 0) {
				$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA MODAL</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa12, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
			}
		}


		$hasil->free_result();
		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

		if ($jenis == "4") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == "6") {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == "8") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
						union all  
						----akun4
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
						union all
						----akun5
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
						inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
						$where and left(a.kd_rek6,2)='52' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == 12) {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
				union all
				----akun5
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
				inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
				union all
				----akun6
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek6 as kode,b.nm_rek6 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,a.sumber_dana as sumber FROM data_realisasi_pemkot a
				inner join ms_rek6 b on b.kd_rek6 = a.kd_rek6                                        
				$where and left(a.kd_rek6,2)='52' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.sumber_dana  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		}

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sumber = $row->sumber;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			$leng = ($leng > 8) ? '12' : $leng;
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 12:
					$cRet .= '<tr>
								<td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="left" valign="top">' . $sumber . '</td>
								<td align="right" valign="top"></td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
			}
		}

		$sql = "SELECT SUM($initang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,2)='53' and bulan='$bulan'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang12 = $row->anggaran;
			$sd_bulan_ini12 = $row->sd_bulan_ini;
			$sisa12 = $sd_bulan_ini12 - $nil_ang12;
			$persen12 = empty($nil_ang12) || $nil_ang12 == 0 ? 0 : $sd_bulan_ini12 / $nil_ang12 * 100;
			$sisa12 = $sisa12 < 0 ? $sisa12 * -1 : $sisa12;
			$a = $sisa12 < 0 ? '(' : '';
			$b = $sisa12 < 0 ? ')' : '';

			if ($nil_ang12 != 0) {
				$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>BELANJA TAK TERDUGA</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa12, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
			}
		}


		$hasil->free_result();
		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

		if ($jenis == "4") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == "6") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == '8') {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
				union all
				----akun5
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
				inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5 
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == 12) {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
				union all
				----akun5
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
				inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
				union all
				----akun6
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek6 as kode,b.nm_rek6 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,a.sumber_dana as sumber FROM data_realisasi_pemkot a
				inner join ms_rek6 b on b.kd_rek6 = a.kd_rek6                                        
				$where and left(a.kd_rek6,2)='53' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.sumber_dana  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		}

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sumber = $row->sumber;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			$leng = ($leng > 8) ? '12' : $leng;
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 12:
					$cRet .= '<tr>
								<td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="right" valign="top">' . $sumber . '</td>
								<td align="right" valign="top"></td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
			}
		}

		$sql = "SELECT SUM($initang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,2)='61' and bulan='$bulan'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang12 = $row->anggaran;
			$sd_bulan_ini12 = $row->sd_bulan_ini;
			$sisa12 = $sd_bulan_ini12 - $nil_ang12;
			$persen12 = empty($nil_ang12) || $nil_ang12 == 0 ? 0 : $sd_bulan_ini12 / $nil_ang12 * 100;
			$sisa12 = $sisa12 < 0 ? $sisa12 * -1 : $sisa12;
			$a = $sisa12 < 0 ? '(' : '';
			$b = $sisa12 < 0 ? ')' : '';

			if ($nil_ang12 != 0) {
				$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>TRANSFER BAGI HASIL PENDAPATAN</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa12, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
			}
		}


		$hasil->free_result();
		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

		if ($jenis == "4") {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == "6") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
						----program
						SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
						$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
						group by b.kd_program,b.nm_program
						union all
						SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
						group by b.kd_kegiatan,b.nm_kegiatan
						union all  
						----kegiatan                      
						SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
						$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
						group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
						union all  
						----akun3
						SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
						$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
						union all  
						----akun4
						SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
						inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
						$where and left(a.kd_rek6,2)='61' and bulan='$bulan' 
						group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
					) z
					group by kd_kegiatan,kode,nama,sumber
					order by kd_kegiatan,kode,nama";
		} else if ($jenis == '8') {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
				union all
				----akun5
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
				inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5 
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == '12') {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
				union all
				----akun5
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
				inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
				union all
				----akun6
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek6 as kode,b.nm_rek6 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,a.sumber_dana as sumber FROM data_realisasi_pemkot a
				inner join ms_rek6 b on b.kd_rek6 = a.kd_rek6                                        
				$where and left(a.kd_rek6,2)='61' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.sumber_dana  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		}

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			$leng = ($leng > 8) ? '12' : $leng;
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 12:
					$cRet .= '<tr>
								<td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
			}
		}

		$sql = "SELECT SUM($initang) anggaran ,SUM(a.real_spj) sd_bulan_ini FROM data_realisasi_pemkot a $where AND left(a.kd_rek6,2)='62' and bulan='$bulan'";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang12 = $row->anggaran;
			$sd_bulan_ini12 = $row->sd_bulan_ini;
			$sisa12 = $sd_bulan_ini12 - $nil_ang12;
			$persen12 = empty($nil_ang12) || $nil_ang12 == 0 ? 0 : $sd_bulan_ini12 / $nil_ang12 * 100;
			$sisa12 = $sisa12 < 0 ? $sisa12 * -1 : $sisa12;
			$a = $sisa12 < 0 ? '(' : '';
			$b = $sisa12 < 0 ? ')' : '';

			if ($nil_ang12 != 0) {
				$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>TRANSFER BANTUAN KEUANGAN</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa12, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen12, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
			}
		}


		$hasil->free_result();
		//SELECT kd_kegiatan,kd_rek,nm_rek,anggaran,sd_bulan_ini FROM realisasi_jurnal_rinci($bulan,$anggaran) $where AND LEN(kd_rek) <='$jenis' ORDER BY kd_kegiatan,kd_rek

		if ($jenis == "4") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == "6") {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == 8) {

			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
				union all
				----akun5
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
				inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		} else if ($jenis == '12') {
			$sql = "SELECT z.kd_kegiatan as kd_kegiatan,z.kode as kd_rek,z.nama as nm_rek,sum(z.anggaran) as anggaran,sum(z.reali) as sd_bulan_ini, sumber from(
				----program
				SELECT b.kd_program as kd_kegiatan,'' as kode,b.nm_program as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_program b on b.kd_program = left(a.kd_sub_kegiatan,7)                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_program,b.nm_program
				union all
				SELECT b.kd_kegiatan as kd_kegiatan,'' as kode,b.nm_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join trskpd b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by b.kd_kegiatan,b.nm_kegiatan
				union all  
				----kegiatan                      
				SELECT a.kd_sub_kegiatan as kd_kegiatan,'' as kode,b.nm_sub_kegiatan as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_sub_kegiatan b on b.kd_sub_kegiatan = a.kd_sub_kegiatan                
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,b.nm_sub_kegiatan	
				union all  
				----akun3
				SELECT a.kd_sub_kegiatan as kd_kegiatan,left(a.kd_rek6,4) as kode,b.nm_rek3 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek3 b on b.kd_rek3 = left(a.kd_rek6,4)                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan'
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,left(a.kd_rek6,4),b.nm_rek3 
				union all  
				----akun4
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek4 as kode,b.nm_rek4 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' sumber FROM data_realisasi_pemkot a
				inner join ms_rek4 b on b.kd_rek4 = left(a.kd_rek6,6)                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,kd_rek4,b.nm_rek4 
				union all
				----akun5
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek5 as kode,b.nm_rek5 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,'' as sumber FROM data_realisasi_pemkot a
				inner join ms_rek5 b on b.kd_rek5 = left(a.kd_rek6,8)                                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek5,b.nm_rek5
				union all
				----akun6
				SELECT a.kd_sub_kegiatan as kd_kegiatan,b.kd_rek6 as kode,b.nm_rek6 as nama,sum($initang) as anggaran,sum(a.real_spj) as reali,a.sumber_dana as sumber FROM data_realisasi_pemkot a
				inner join ms_rek6 b on b.kd_rek6 = a.kd_rek6                                        
				$where and left(a.kd_rek6,2)='62' and bulan='$bulan' 
				group by a.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6,a.sumber_dana  
			) z
			group by kd_kegiatan,kode,nama,sumber
			order by kd_kegiatan,kode,nama";
		}

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kd_kegiatan = $row->kd_kegiatan;
			$kd_rek = $row->kd_rek;
			$nm_rek = $row->nm_rek;
			$nil_ang = $row->anggaran;
			$sd_bulan_ini = $row->sd_bulan_ini;
			$sisa = $sd_bulan_ini - $nil_ang;
			$persen = empty($nil_ang) || $nil_ang == 0 ? 0 : $sd_bulan_ini / $nil_ang * 100;
			$sisa1 = $sisa < 0 ? $sisa * -1 : $sisa;
			$a = $sisa < 0 ? '(' : '';
			$b = $sisa < 0 ? ')' : '';

			$leng = strlen($kd_rek);
			$leng = ($leng > 8) ? '12' : $leng;
			switch ($leng) {
				case 4:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 6:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 8:
					$cRet .= '<tr>
							   <td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
							   <td align="left"  valign="top">' . $nm_rek . '</td> 
							   <td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
							   <td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
				case 12:
					$cRet .= '<tr>
								<td align="left" valign="top">' . $this->custom->dotrek($kd_rek) . '</td> 
								<td align="left"  valign="top">' . $nm_rek . '</td> 
								<td align="right" valign="top">' . number_format($nil_ang, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . number_format($sd_bulan_ini, "2", ",", ".") . '</td> 
								<td align="right" valign="top">' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</td> 
								<td align="right" valign="top">' . number_format($persen, "2", ",", ".") . '</td> 
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td>
								<td align="right" valign="top"></td> 
							</tr>';
					break;
				default:
					$cRet .= '<tr>
							   <td align="left" valign="top"><b>' . $kd_kegiatan . '</b></td> 
							   <td align="left"  valign="top"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
					break;
			}
		}


		$hasil->free_result();
		$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="left"  valign="top"><b>JUMLAH BELANJA</b></td> 
							   <td align="right" valign="top"><b>' . number_format($nil_ang13, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($sd_bulan_ini13, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b>' . $a . ' ' . number_format($sisa13, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen13, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';


		$sql = "select isnull(sum(z.anggaran_4),0)-isnull(sum(z.anggaran_5),0) as anggaran, 
				isnull(sum(z.sd_bulan_ini_4),0)-isnull(sum(z.sd_bulan_ini_5),0) as sd_bulan_ini from (
				SELECT SUM($initang) anggaran_4, SUM(a.real_spj) sd_bulan_ini_4,0 anggaran_5 , 0 as sd_bulan_ini_5
				FROM data_realisasi_pemkot a 
				$where AND left(a.kd_ang,1) in ('4') and bulan='$bulan'
				UNION
				SELECT 0 as anggaran_4, 0 as sd_bulan_ini_4, SUM($initang) anggaran_5 ,SUM(a.real_spj) sd_bulan_ini_5
				FROM data_realisasi_pemkot a 
				$where AND left(a.kd_ang,1) in ('5') and bulan='$bulan'
				)z";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$nil_ang145 = $row->anggaran;
			$sd_bulan_ini145 = $row->sd_bulan_ini;
			$sisa1 = $sd_bulan_ini145 - $nil_ang145;
			$persen145 = empty($nil_ang145) || $nil_ang145 == 0 ? 0 : $sd_bulan_ini145 / $nil_ang145 * 100;
			$a45 = $sisa1 < 0 ? '(' : '';
			$b45 = $sisa1 < 0 ? ')' : '';
			$sisa1145 = $sisa1 < 0 ? $sisa1 * -1 : $sisa1;

			$a445 = $nil_ang145 < 0 ? '(' : '';
			$b445 = $nil_ang145 < 0 ? ')' : '';
			$nil_ang1455 = $nil_ang145 < 0 ? $nil_ang145 * -1 : $nil_ang145;

			$a455 = $sd_bulan_ini145 < 0 ? '(' : '';
			$b455 = $sd_bulan_ini145 < 0 ? ')' : '';
			$sd_bulan_ini1455 = $sd_bulan_ini145 < 0 ? $sd_bulan_ini145 * -1 : $sd_bulan_ini145;


			$cRet .= '<tr>
							   <td align="left" valign="top"></td> 
							   <td align="right"  valign="top"><b>SURPLUS/(DEFISIT)</b></td> 
							   <td align="right" valign="top"><b>' . $a445 . ' ' . number_format($nil_ang1455, "2", ",", ".") . ' ' . $b445 . '</b></td> 
							   <td align="right" valign="top"><b>' . $a455 . ' ' . number_format($sd_bulan_ini1455, "2", ",", ".") . ' ' . $b455 . '</b></td> 
							   <td align="right" valign="top"><b>' . $a45 . ' ' . number_format($sisa1145, "2", ",", ".") . ' ' . $b45 . '</b></td> 
							   <td align="right" valign="top"><b>' . number_format($persen145, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top"><b></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td>
							   <td align="right" valign="top"></td> 
							</tr>';
		}
		$hasil->free_result();

		$cRet .= "</table>";

		$tanggal = $tglttd == '-' ? '' : 'Melawi, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($kd_skpd == "8.01.0.00.0.00.01.0000") {

			$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='8.01.0.00.0.00.01.0000'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowsc) {
				$kab     = $rowsc->kab_kota;
				$daerah  = $rowsc->daerah;
			}

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip ='$ttd1' and kode in ('wk','agr')";
			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			$cRet .= '<TABLE style="border-collapse:collapse; font-family:Bookman Old Style;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<!--<TD align="center" >' . $tanggal . '</TD>-->
					</TR>
					
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $jabatan . '</TD>
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
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>                    
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nama . '</TD>
					</TR>
                    <!--<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip . '</TD>
					</TR>-->
					</TABLE><br/>';
		} else {

			$cRet .= '<TABLE style="border-collapse:collapse; font-family:Bookman Old Style;font-size:12px" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<TD align="center" ></TD>
					</TR>
					
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b></b></TD>
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
						<TD align="center" ><b></b></TD>
					</TR>
                    <!--<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ></TD>
					</TR>-->
					</TABLE><br/>';
		}

		$data['prev'] = $cRet;
		$judul = 'PERDA_LAMP_I.2 ';
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

	//================================================ End Lamp Perda I.2

	//================================================ Lamp Perda I.3
	function perdaI_3()
	{
		$data['page_title'] = 'CETAK PERDA LAMP. I.3';
		$this->template->set('title', 'CETAK PERDA LAMP. I.3');
		$this->template->load('template', 'perda/cetak_perda_lampI_3', $data);
	}

	function cetak_perda_lampI_3_skpd($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama_ttd = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama_ttd = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}
		$nip = '';


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		$sqlnogub = "SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$ket_lampiran      = strtoupper("Lampiran I.3");
			$ket_perda         = strtoupper($rowsc->ket_perda);
			$ket_perda_no      = strtoupper($rowsc->ket_perda_no);
			$ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
		}

		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >' . $ket_lampiran . '</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >' . $ket_perda . '</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >' . $ket_perda_no . '</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >' . $ket_perda_tentang . '</TD>
					</TR>
					</TABLE><br/>';
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/kab-MELAWI.png\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH KABUPATEN MELAWI </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REKAPITULASI REALISASI BELANJA DAERAH MENURUT URUSAN PEMERINTAH DAERAH, <BR> ORGANISASI, PROGRAM, DAN KEGIATAN</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KODE</td>
                    <td rowspan = \"2\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">URUSAN PEMERINTAH DAERAH</td>
					<td colspan = \"8\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">ANGGARAN BELANJA</td>
                    <td rowspan = \"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
					<td colspan = \"8\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI BELANJA</td>
                    <td rowspan = \"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
				</tr>
				<tr>
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td>
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">HIBAH</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BANTUAN SOSIAL</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BAGI HASIL</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BANTUAN KEUANGAN</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BELANJA TIDAK TERDUGA</td>  
				   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td>
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">HIBAH</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BANTUAN SOSIAL</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BAGI HASIL</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BANTUAN KEUANGAN</td> 
                   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BELANJA TIDAK TERDUGA</td>  
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
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td>
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td> 
                   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">20</td>  
				</tr>
				</thead>";


		$sql = " SELECT kd_skpd,kd_sub_kegiatan kode ,nm_rek,ang_peg,ang_brng,ang_mod,ang_hibah,ang_bansos,ang_bghasil,
						ang_bankeu,ang_btt,
					real_peg,real_brng,real_mod,real_hibah,real_bansos,real_bghasil,real_bankeu,real_btt 
					FROM [perda_lampI.3_rinci2]($bulan,'$anggaran',$lntahunang)
					where len(kd_skpd)='22'
					ORDER BY kd_skpd,kd_sub_kegiatan

					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kode;
			$nm_rek = $row->nm_rek;
			$ang_peg = $row->ang_peg;
			$ang_brng = $row->ang_brng;
			$ang_mod = $row->ang_mod;
			$ang_hibah = $row->ang_hibah;
			$ang_bansos = $row->ang_bansos;
			$ang_bghasil = $row->ang_bghasil;
			$ang_bankeu = $row->ang_bankeu;
			$ang_btt = $row->ang_btt;
			$real_peg = $row->real_peg;
			$real_brng = $row->real_brng;
			$real_mod = $row->real_mod;
			$real_hibah = $row->real_hibah;
			$real_bansos = $row->real_bansos;
			$real_bghasil = $row->real_bghasil;
			$real_bankeu = $row->real_bankeu;
			$real_btt = $row->real_btt;

			$tot_ang = $ang_peg + $ang_brng + $ang_mod + $ang_hibah + $ang_bansos + $ang_bghasil + $ang_bankeu + $ang_btt;
			$tot_real = $real_peg + $real_brng + $real_mod + $real_hibah + $real_bansos + $real_bghasil + $real_bankeu + $real_btt;

			$len = strlen($kode);

			if ($len == '1') {
				$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:12px"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top" style="font-size:12px"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_peg, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_brng, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_mod, "2", ",", ".") . '</b></td>
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_hibah, "2", ",", ".") . '</b></td>
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_bansos, "2", ",", ".") . '</b></td>
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_bghasil, "2", ",", ".") . '</b></td>
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_bankeu, "2", ",", ".") . '</b></td>
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_btt, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($tot_ang, "2", ",", ".") . '</b></td> 

							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_peg, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_brng, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_mod, "2", ",", ".") . '</b></td>
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_hibah, "2", ",", ".") . '</b></td>
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_bansos, "2", ",", ".") . '</b></td>
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_bghasil, "2", ",", ".") . '</b></td>
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_bankeu, "2", ",", ".") . '</b></td>
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_btt, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($tot_real, "2", ",", ".") . '</b></td> 
							</tr>';
			} else {
				$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:12px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nm_rek . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '
							   </td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_hibah, "2", ",", ".") . '
							   </td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_bansos, "2", ",", ".") . '
							   </td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_bghasil, "2", ",", ".") . '
							   </td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_bankeu, "2", ",", ".") . '
							   </td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_btt, "2", ",", ".") . '
							   </td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang, "2", ",", ".") . '</td> 

							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_hibah, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_bansos, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_bghasil, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_bankeu, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_btt, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real, "2", ",", ".") . '</td> 
							</tr>';
			}
		}
		$sql2 = "SELECT 
isnull(sum(ang_peg),0) ang_peg, isnull(sum(ang_brng),0) ang_brng, 
isnull(sum(ang_mod),0) ang_mod, isnull(sum(ang_hibah),0) ang_hibah, 
isnull(sum(ang_bansos),0) ang_bansos, isnull(sum(ang_bghasil),0) ang_bghasil, isnull(sum(ang_bankeu),0) ang_bankeu, 


isnull(sum(ang_btt),0) ang_btt, isnull(sum(real_peg),0) real_peg, isnull(sum(real_brng),0) real_brng, isnull(sum(real_mod),0) real_mod
, isnull(sum(real_hibah),0) real_hibah, isnull(sum(real_bansos),0) real_bansos, isnull(sum(real_bghasil),0) real_bghasil
, isnull(sum(real_bankeu),0) real_bankeu, isnull(sum(real_btt),0) real_btt

			from (  
			SELECT a.kd_skpd,a.kd_kegiatan, nm_rek, ISNULL(ang_peg,0) as ang_peg,
ISNULL(ang_brng,0) as ang_brng, ISNULL(ang_mod,0) as ang_mod, ISNULL(ang_hibah,0) as ang_hibah, ISNULL(ang_bansos,0) as ang_bansos,
 ISNULL(ang_bghasil,0) as ang_bghasil, ISNULL(ang_bankeu,0) as ang_bankeu, ISNULL(ang_btt,0) as ang_btt, 
 
ISNULL(real_peg,0) as real_peg,ISNULL(real_brng,0) as real_brng, ISNULL(real_mod,0) as real_mod,ISNULL(real_hibah,0) as real_hibah,
ISNULL(real_bansos,0) as real_bansos,ISNULL(real_bghasil,0) as real_bghasil,ISNULL(real_bankeu,0) as real_bankeu,ISNULL(real_btt,0) as real_btt
			 FROM (SELECT LEFT(a.kd_skpd,1) kd_skpd,  LEFT(a.kd_skpd,1) kd_kegiatan, b.nm_urusan nm_rek
, SUM(CASE WHEN LEFT(kd_rek6,4) IN('5101') THEN nilai 
		ELSE 0 END) as ang_peg 
, SUM(CASE WHEN LEFT(kd_rek6,4) = '5102' THEN nilai 
		ELSE 0 END) as ang_brng
, SUM(CASE WHEN LEFT(kd_rek6,4) IN('5201','5202','5203','5204','5205','5206') THEN nilai 
		ELSE 0 END) as ang_mod
, SUM(CASE WHEN LEFT(kd_rek6,4) = '5105' THEN nilai 
		ELSE 0 END) as ang_hibah
, SUM(CASE WHEN LEFT(kd_rek6,4) = '5106' THEN nilai 
		ELSE 0 END) as ang_bansos
, SUM(CASE WHEN LEFT(kd_rek6,4) = '5401' THEN nilai 
		ELSE 0 END) as ang_bghasil
, SUM(CASE WHEN LEFT(kd_rek6,4) = '5402' THEN nilai 
		ELSE 0 END) as ang_bankeu
, SUM(CASE WHEN LEFT(kd_rek6,4) = '5301' THEN nilai 
		ELSE 0 END) as ang_btt			
			 FROM trdrka a 
			INNER JOIN ms_urusan b on LEFT(a.kd_skpd,1)=b.kd_urusan 
			WHERE LEFT(a.kd_rek6,4) IN ('5101','5102','5201','5202','5203','5204','5205','5206','5105','5106','5401','5402','5301') AND a.jns_ang='$anggaran'
			GROUP BY LEFT(a.kd_skpd,1),b.nm_urusan) a 
			LEFT JOIN 
			(SELECT  LEFT(a.kd_skpd,1) kd_skpd
,SUM(CASE WHEN LEFT(b.map_real,4) IN('5101') THEN (debet-kredit) ELSE 0 END) as real_peg
,SUM(CASE WHEN LEFT(b.map_real,4)='5102'  THEN (debet-kredit) ELSE 0 END) as real_brng
,SUM(CASE WHEN LEFT(b.map_real,4)IN('5201','5202','5203','5204','5205','5206' )  THEN (debet-kredit) ELSE 0 END) as real_mod
,SUM(CASE WHEN LEFT(b.map_real,4)='5105'  THEN (debet-kredit) ELSE 0 END) as real_hibah
,SUM(CASE WHEN LEFT(b.map_real,4)='5106'  THEN (debet-kredit) ELSE 0 END) as real_bansos
,SUM(CASE WHEN LEFT(b.map_real,4)='5401'  THEN (debet-kredit) ELSE 0 END) as real_bghasil
,SUM(CASE WHEN LEFT(b.map_real,4)='5402'  THEN (debet-kredit) ELSE 0 END) as real_bankeu
,SUM(CASE WHEN LEFT(b.map_real,4)='5301'  THEN (debet-kredit) ELSE 0 END) as real_btt
			FROM trhju a INNER JOIN trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
			WHERE LEFT(b.map_real,4) IN ('5101','5102','5201','5202','5203','5204','5205','5206','5105','5106','5401','5402','5301') AND MONTH(a.tgl_voucher)<=$bulan AND YEAR(a.tgl_voucher)=$lntahunang
			GROUP BY LEFT(a.kd_skpd,1))b
			ON a.kd_skpd=b.kd_skpd ) a
			";
		$hasil2 = $this->db->query($sql2);
		foreach ($hasil2->result() as $row2) {

			$ang_peg = $row2->ang_peg;
			$ang_brng = $row2->ang_brng;
			$ang_mod = $row2->ang_mod;
			$ang_hibah = $row2->ang_hibah;
			$ang_bansos = $row2->ang_bansos;
			$ang_bghasil = $row2->ang_bghasil;
			$ang_bankeu = $row2->ang_bankeu;
			$ang_btt = $row2->ang_btt;

			$real_peg = $row2->real_peg;
			$real_brng = $row2->real_brng;
			$real_mod = $row2->real_mod;
			$real_hibah = $row2->real_hibah;
			$real_bansos = $row2->real_bansos;
			$real_bghasil = $row2->real_bghasil;
			$real_bankeu = $row2->real_bankeu;
			$real_btt = $row2->real_btt;

			$tot_ang = $ang_peg + $ang_brng + $ang_mod + $ang_hibah + $ang_bansos + $ang_bghasil + $ang_bankeu + $ang_btt;
			$tot_real = $real_peg + $real_brng + $real_mod + $real_hibah + $real_bansos + $real_bghasil + $real_bankeu + $real_btt;


			$cRet .= '<tr>
							   <td align="center" valign="top" style="font-size:12px" colspan="2">TOTAL</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_hibah, "2", ",", ".") . '
							   	   </td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_bansos, "2", ",", ".") . '
							  	   </td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_bghasil, "2", ",", ".") . '
							      </td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_bankeu, "2", ",", ".") . '
							      </td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_btt, "2", ",", ".") . '
							      </td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang, "2", ",", ".") . '</td> 

							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_hibah, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_bansos, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_bghasil, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_bankeu, "2", ",", ".") . '</td>
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_btt, "2", ",", ".") . '</td>  
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real, "2", ",", ".") . '</td> 
							</tr>';
		}

		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
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


	function cetak_perda_lampI_3_rinci2($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '')
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
							<img src=\"" . FCPATH . "/image/logo-melawi.png\"  width=\"60px\" height=\"75px\" />
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
						<td colspan = \"3\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">ANGGARAN BELANJA</td>
						<td rowspan = \"2\" width=\"9%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
						<td colspan = \"3\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI BELANJA</td>
						<td rowspan = \"2\" width=\"9%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
						<td rowspan = \"2\" width=\"9%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BERTAMBAH/<BR/>BERKURANG</td>
						<td rowspan = \"2\" width=\"5%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">(%)</td>
					</tr>
					<tr>
					   <td align =\"center\" width=\"9%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
					   <td align =\"center\" width=\"9%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
					   <td align =\"center\" width=\"9%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td> 
					   <td align =\"center\" width=\"9%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
					   <td align =\"center\" width=\"9%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
					   <td align =\"center\" width=\"9%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td>
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
					</tr>
					</thead>";


		$sql = " select kd_skpd,kd_sub_kegiatan kode,nm_rek,ang_peg,ang_brng,ang_mod,real_peg,real_brng,real_mod 
						FROM [perda_lampI.3_sub_2_c]($bulan,'$anggaran') order by kd_sub_kegiatan,kd_sub_kegiatan+kd_skpd,urut
						";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kode;
			$nm_rek = $row->nm_rek;
			$ang_peg = $row->ang_peg;
			$ang_brng = $row->ang_brng;
			$ang_mod = $row->ang_mod;
			$real_peg = $row->real_peg;
			$real_brng = $row->real_brng;
			$real_mod = $row->real_mod;

			$tot_ang = $ang_peg + $ang_brng + $ang_mod;
			$tot_real = $real_peg + $real_brng + $real_mod;

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
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_peg, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_brng, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_peg, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_brng, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . $e . '' . number_format($sisa_bb_, "2", ",", ".") . '' . $f . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($persen, "2", ",", ".") . '</td>
								</tr>';
		}

		$sql = "SELECT sum(ang_peg) ang_peg,sum(ang_brng) ang_brng,sum(ang_mod) ang_mod,sum(real_peg) real_peg,sum(real_brng) real_brng,sum(real_mod) real_mod from(
				select kd_sub_kegiatan kode,nm_rek,sum(ang_peg) ang_peg,sum(ang_brng) ang_brng,sum(ang_mod) ang_mod,sum(real_peg) real_peg,sum(real_brng) real_brng,sum(real_mod) real_mod 
				FROM [perda_lampI.3]($bulan,'$anggaran') where len(kd_sub_kegiatan)=1
				group by kd_sub_kegiatan , nm_rek
				)a 
			";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$ang_peg = $row->ang_peg;
			$ang_brng = $row->ang_brng;
			$ang_mod = $row->ang_mod;
			$real_peg = $row->real_peg;
			$real_brng = $row->real_brng;
			$real_mod = $row->real_mod;
			$tot_ang = $ang_peg + $ang_brng + $ang_mod;
			$tot_real = $real_peg + $real_brng + $real_mod;

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
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_peg, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_brng, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_peg, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_brng, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
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

			if ($nip == '00000000 000000 0 000') {
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


	function cetak_perda_lampI_3_rinci2_global($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$tanggal = $tglttd == '-' ? '' : 'Pontianak, ' . $this->tukd_model->tanggal_format_indonesia($tglttd);
		if ($ttd == '-') {
			$nama_ttd = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("abc", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd'";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama_ttd = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}
		$nip = '';


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}

		$sqlnogub = "SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$ket_lampiran      = strtoupper("Lampiran I.3");
			$ket_perda         = strtoupper($rowsc->ket_perda);
			$ket_perda_no      = strtoupper($rowsc->ket_perda_no);
			$ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
		}

		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
						<TR>
							<TD width="6%" valign="top" align="left" >' . $ket_lampiran . '</TD>
							<TD width="1%"  align="center" >:</TD>
							<TD width="93%"  align="left" >&nbsp;</TD>
						</TR>
						<TR>
							<TD colspan="3" width="100%" valign="top" align="left" >' . $ket_perda . '</TD>
						</TR>
						<TR>
							<TD width="6%" valign="top" align="left" >NOMOR</TD>
							<TD width="1%"  align="center" >:</TD>
							<TD width="93%"  align="left" >' . $ket_perda_no . '</TD>
						</TR>
						<TR>
							<TD width="6%" valign="top" align="left" >TENTANG</TD>
							<TD width="1%"  align="center" >:</TD>
							<TD width="93%"  align="left" >' . $ket_perda_tentang . '</TD>
						</TR>
						</TABLE><br/>';
		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/kab-MELAWI.png\"  width=\"75\" height=\"100\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>PEMERINTAH KABUPATEN MELAWI </strong></td></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REKAPITULASI REALISASI BELANJA DAERAH MENURUT URUSAN PEMERINTAH DAERAH, <BR> ORGANISASI, PROGRAM, DAN KEGIATAN</b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
						</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
					<thead>
					<tr>
						<td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KODE</td>
						<td rowspan = \"2\" width=\"35%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">URUSAN PEMERINTAH DAERAH</td>
						<td colspan = \"8\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">ANGGARAN BELANJA</td>
						<td rowspan = \"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
						<td colspan = \"8\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI BELANJA</td>
						<td rowspan = \"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
					</tr>
					<tr>
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td>
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">HIBAH</td> 
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BANTUAN SOSIAL</td> 
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BAGI HASIL</td> 
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BANTUAN KEUANGAN</td> 
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BELANJA TIDAK TERDUGA</td>  
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td>
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">HIBAH</td> 
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BANTUAN SOSIAL</td> 
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BAGI HASIL</td> 
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BANTUAN KEUANGAN</td> 
					   <td align =\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BELANJA TIDAK TERDUGA</td>  
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
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">15</td>
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">16</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">17</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">18</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">19</td> 
					   <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">20</td>  
					</tr>
					</thead>";


		$sql = " SELECT kd_skpd,kd_sub_kegiatan kode ,nm_rek,ang_peg,ang_brng,ang_mod,ang_hibah,ang_bansos,ang_bghasil,
							ang_bankeu,ang_btt,
						real_peg,real_brng,real_mod,real_hibah,real_bansos,real_bghasil,real_bankeu,real_btt 
						FROM [perda_lampI.3_rinci2]($bulan,'$anggaran',$lntahunang)
						where len(kd_skpd)<='22'
						ORDER BY kd_skpd,kd_sub_kegiatan
	
						";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kode;
			$nm_rek = $row->nm_rek;
			$ang_peg = $row->ang_peg;
			$ang_brng = $row->ang_brng;
			$ang_mod = $row->ang_mod;
			$ang_hibah = $row->ang_hibah;
			$ang_bansos = $row->ang_bansos;
			$ang_bghasil = $row->ang_bghasil;
			$ang_bankeu = $row->ang_bankeu;
			$ang_btt = $row->ang_btt;
			$real_peg = $row->real_peg;
			$real_brng = $row->real_brng;
			$real_mod = $row->real_mod;
			$real_hibah = $row->real_hibah;
			$real_bansos = $row->real_bansos;
			$real_bghasil = $row->real_bghasil;
			$real_bankeu = $row->real_bankeu;
			$real_btt = $row->real_btt;

			$tot_ang = $ang_peg + $ang_brng + $ang_mod + $ang_hibah + $ang_bansos + $ang_bghasil + $ang_bankeu + $ang_btt;
			$tot_real = $real_peg + $real_brng + $real_mod + $real_hibah + $real_bansos + $real_bghasil + $real_bankeu + $real_btt;

			$len = strlen($kode);

			if ($len == '1') {
				$cRet .= '<tr>
								   <td align="left" valign="top" style="font-size:12px"><b>' . $kode . '</b></td> 
								   <td align="left"  valign="top" style="font-size:12px"><b>' . $nm_rek . '</b></td> 
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_peg, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_brng, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_mod, "2", ",", ".") . '</b></td>
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_hibah, "2", ",", ".") . '</b></td>
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_bansos, "2", ",", ".") . '</b></td>
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_bghasil, "2", ",", ".") . '</b></td>
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_bankeu, "2", ",", ".") . '</b></td>
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_btt, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($tot_ang, "2", ",", ".") . '</b></td> 
	
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_peg, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_brng, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_mod, "2", ",", ".") . '</b></td>
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_hibah, "2", ",", ".") . '</b></td>
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_bansos, "2", ",", ".") . '</b></td>
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_bghasil, "2", ",", ".") . '</b></td>
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_bankeu, "2", ",", ".") . '</b></td>
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_btt, "2", ",", ".") . '</b></td> 
								   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($tot_real, "2", ",", ".") . '</b></td> 
								</tr>';
			} else {
				$cRet .= '<tr>
								   <td align="left" valign="top" style="font-size:12px">' . $kode . '</td> 
								   <td align="left"  valign="top" style="font-size:12px">' . $nm_rek . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_peg, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_brng, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '
								   </td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_hibah, "2", ",", ".") . '
								   </td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_bansos, "2", ",", ".") . '
								   </td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_bghasil, "2", ",", ".") . '
								   </td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_bankeu, "2", ",", ".") . '
								   </td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_btt, "2", ",", ".") . '
								   </td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
	
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_peg, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_brng, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_hibah, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_bansos, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_bghasil, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_bankeu, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_btt, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real, "2", ",", ".") . '</td> 
								</tr>';
			}
		}
		$sql2 = "SELECT 
	isnull(sum(ang_peg),0) ang_peg, isnull(sum(ang_brng),0) ang_brng, 
	isnull(sum(ang_mod),0) ang_mod, isnull(sum(ang_hibah),0) ang_hibah, 
	isnull(sum(ang_bansos),0) ang_bansos, isnull(sum(ang_bghasil),0) ang_bghasil, isnull(sum(ang_bankeu),0) ang_bankeu, 
	
	
	isnull(sum(ang_btt),0) ang_btt, isnull(sum(real_peg),0) real_peg, isnull(sum(real_brng),0) real_brng, isnull(sum(real_mod),0) real_mod
	, isnull(sum(real_hibah),0) real_hibah, isnull(sum(real_bansos),0) real_bansos, isnull(sum(real_bghasil),0) real_bghasil
	, isnull(sum(real_bankeu),0) real_bankeu, isnull(sum(real_btt),0) real_btt
	
				from (  
				SELECT a.kd_skpd,a.kd_kegiatan, nm_rek, ISNULL(ang_peg,0) as ang_peg,
	ISNULL(ang_brng,0) as ang_brng, ISNULL(ang_mod,0) as ang_mod, ISNULL(ang_hibah,0) as ang_hibah, ISNULL(ang_bansos,0) as ang_bansos,
	 ISNULL(ang_bghasil,0) as ang_bghasil, ISNULL(ang_bankeu,0) as ang_bankeu, ISNULL(ang_btt,0) as ang_btt, 
	 
	ISNULL(real_peg,0) as real_peg,ISNULL(real_brng,0) as real_brng, ISNULL(real_mod,0) as real_mod,ISNULL(real_hibah,0) as real_hibah,
	ISNULL(real_bansos,0) as real_bansos,ISNULL(real_bghasil,0) as real_bghasil,ISNULL(real_bankeu,0) as real_bankeu,ISNULL(real_btt,0) as real_btt
				 FROM (SELECT LEFT(a.kd_skpd,1) kd_skpd,  LEFT(a.kd_skpd,1) kd_kegiatan, b.nm_urusan nm_rek
	, SUM(CASE WHEN LEFT(kd_rek6,4) IN('5101') THEN nilai 
						 ELSE 0 END) as ang_peg 
	, SUM(CASE WHEN LEFT(kd_rek6,4) = '5102' THEN nilai 
						 ELSE 0 END) as ang_brng
	, SUM(CASE WHEN LEFT(kd_rek6,4) IN('5201','5202','5203','5204','5205','5206') THEN nilai 
						 ELSE 0 END) as ang_mod
	, SUM(CASE WHEN LEFT(kd_rek6,4) = '5105' THEN nilai 
						 ELSE 0 END) as ang_hibah
	, SUM(CASE WHEN LEFT(kd_rek6,4) = '5106' THEN nilai 
						 ELSE 0 END) as ang_bansos
	, SUM(CASE WHEN LEFT(kd_rek6,4) = '5401' THEN nilai 
						 ELSE 0 END) as ang_bghasil
	, SUM(CASE WHEN LEFT(kd_rek6,4) = '5402' THEN nilai 
						 ELSE 0 END) as ang_bankeu
	, SUM(CASE WHEN LEFT(kd_rek6,4) = '5301' THEN nilai 
						 ELSE 0 END) as ang_btt			
				 FROM trdrka a 
				INNER JOIN ms_urusan b on LEFT(a.kd_skpd,1)=b.kd_urusan 
				WHERE LEFT(a.kd_rek6,4) IN ('5101','5102','5201','5202','5203','5204','5205','5206','5105','5106','5401','5402','5301') AND a.jns_ang='@anggaran'
				GROUP BY LEFT(a.kd_skpd,1),b.nm_urusan) a 
				LEFT JOIN 
				(SELECT  LEFT(a.kd_skpd,1) kd_skpd
	,SUM(CASE WHEN LEFT(b.map_real,4) IN('5101') THEN (debet-kredit) ELSE 0 END) as real_peg
	,SUM(CASE WHEN LEFT(b.map_real,4)='5102'  THEN (debet-kredit) ELSE 0 END) as real_brng
	,SUM(CASE WHEN LEFT(b.map_real,4)IN('5201','5202','5203','5204','5205','5206' )  THEN (debet-kredit) ELSE 0 END) as real_mod
	,SUM(CASE WHEN LEFT(b.map_real,4)='5105'  THEN (debet-kredit) ELSE 0 END) as real_hibah
	,SUM(CASE WHEN LEFT(b.map_real,4)='5106'  THEN (debet-kredit) ELSE 0 END) as real_bansos
	,SUM(CASE WHEN LEFT(b.map_real,4)='5401'  THEN (debet-kredit) ELSE 0 END) as real_bghasil
	,SUM(CASE WHEN LEFT(b.map_real,4)='5402'  THEN (debet-kredit) ELSE 0 END) as real_bankeu
	,SUM(CASE WHEN LEFT(b.map_real,4)='5301'  THEN (debet-kredit) ELSE 0 END) as real_btt
				FROM trhju a INNER JOIN trdju b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
				WHERE LEFT(b.map_real,4) IN ('5101','5102','5201','5202','5203','5204','5205','5206','5105','5106','5401','5402','5301') AND MONTH(a.tgl_voucher)<=$bulan AND YEAR(a.tgl_voucher)=$lntahunang
				GROUP BY LEFT(a.kd_skpd,1))b
				ON a.kd_skpd=b.kd_skpd ) a
				";
		$hasil2 = $this->db->query($sql2);
		foreach ($hasil2->result() as $row2) {

			$ang_peg = $row2->ang_peg;
			$ang_brng = $row2->ang_brng;
			$ang_mod = $row2->ang_mod;
			$ang_hibah = $row2->ang_hibah;
			$ang_bansos = $row2->ang_bansos;
			$ang_bghasil = $row2->ang_bghasil;
			$ang_bankeu = $row2->ang_bankeu;
			$ang_btt = $row2->ang_btt;

			$real_peg = $row2->real_peg;
			$real_brng = $row2->real_brng;
			$real_mod = $row2->real_mod;
			$real_hibah = $row2->real_hibah;
			$real_bansos = $row2->real_bansos;
			$real_bghasil = $row2->real_bghasil;
			$real_bankeu = $row2->real_bankeu;
			$real_btt = $row2->real_btt;

			$tot_ang = $ang_peg + $ang_brng + $ang_mod + $ang_hibah + $ang_bansos + $ang_bghasil + $ang_bankeu + $ang_btt;
			$tot_real = $real_peg + $real_brng + $real_mod + $real_hibah + $real_bansos + $real_bghasil + $real_bankeu + $real_btt;


			$cRet .= '<tr>
								   <td align="center" valign="top" style="font-size:12px" colspan="2">TOTAL</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_peg, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_brng, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_hibah, "2", ",", ".") . '
										  </td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_bansos, "2", ",", ".") . '
										 </td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_bghasil, "2", ",", ".") . '
									  </td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_bankeu, "2", ",", ".") . '
									  </td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_btt, "2", ",", ".") . '
									  </td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
	
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_peg, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_brng, "2", ",", ".") . '</td> 
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_hibah, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_bansos, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_bghasil, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_bankeu, "2", ",", ".") . '</td>
								   <td align="right" valign="top" style="font-size:12px">' . number_format($real_btt, "2", ",", ".") . '</td>  
								   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real, "2", ",", ".") . '</td> 
								</tr>';
		}

		$cRet .= "</table>";
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
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


	function cetak_perda_lampI_3($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '', $ttdperda = '')
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

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"100px\" height=\"100px\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong></strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>$kab <br>REKAPITULASI REALISASI BELANJA DAERAH MENURUT URUSAN PEMERINTAH DAERAH, <BR> ORGANISASI, PROGRAM, DAN KEGIATAN<br>TAHUN ANGGARAN " . $lntahunang . "</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b></b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">KODE</td>
                    <td rowspan = \"2\" width=\"20%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">URUSAN PEMERINTAH DAERAH</td>
                    <td colspan = \"3\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">ANGGARAN BELANJA</td>
                    <td rowspan = \"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
                    <td colspan = \"3\" width=\"45%\"align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">REALISASI BELANJA</td>
                    <td rowspan = \"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">JUMLAH</td>
                    <td rowspan = \"2\" width=\"15%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BERTAMBAH/<BR/>BERKURANG</td>
                    <td rowspan = \"2\" width=\"10%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">(%)</td>
                </tr>
				<tr>
                   <td align =\"center\" width=\"15%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
                   <td align =\"center\" width=\"15%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
                   <td align =\"center\" width=\"15%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td> 
				   <td align =\"center\" width=\"15%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">PEGAWAI</td> 
                   <td align =\"center\" width=\"15%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BARANG DAN JASA</td> 
                   <td align =\"center\" width=\"15%\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">MODAL</td>
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
				</tr>
				</thead>";


		$sql = " select kd_skpd,kd_sub_kegiatan kode,nm_rek,ang_peg,ang_brng,ang_mod,real_peg,real_brng,real_mod 
					FROM [perda_lampI.3_sub_2_c]($bulan,'$anggaran') order by kd_sub_kegiatan,kd_sub_kegiatan+kd_skpd,urut
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kode;
			$nm_rek = $row->nm_rek;
			$ang_peg = $row->ang_peg;
			$ang_brng = $row->ang_brng;
			$ang_mod = $row->ang_mod;
			$real_peg = $row->real_peg;
			$real_brng = $row->real_brng;
			$real_mod = $row->real_mod;

			$tot_ang = $ang_peg + $ang_brng + $ang_mod;
			$tot_real = $real_peg + $real_brng + $real_mod;

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
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . $e . '' . number_format($sisa_bb_, "2", ",", ".") . '' . $f . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($persen, "2", ",", ".") . '</td>
							</tr>';
		}

		$sql = " select sum(ang_peg) ang_peg,sum(ang_brng) ang_brng,sum(ang_mod) ang_mod,sum(real_peg) real_peg,sum(real_brng) real_brng,sum(real_mod) real_mod from(
						select kd_sub_kegiatan kode,nm_rek,sum(ang_peg) ang_peg,sum(ang_brng) ang_brng,sum(ang_mod) ang_mod,sum(real_peg) real_peg,sum(real_brng) real_brng,sum(real_mod) real_mod 
						FROM [perda_lampI.3]($bulan,'$anggaran') where len(kd_sub_kegiatan)=1
						group by kd_sub_kegiatan , nm_rek
						)a 
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {

			$ang_peg = $row->ang_peg;
			$ang_brng = $row->ang_brng;
			$ang_mod = $row->ang_mod;
			$real_peg = $row->real_peg;
			$real_brng = $row->real_brng;
			$real_mod = $row->real_mod;
			$tot_ang = $ang_peg + $ang_brng + $ang_mod;
			$tot_real = $real_peg + $real_brng + $real_mod;

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
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_ang, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_peg, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_brng, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_mod, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($tot_real, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . $e . '' . number_format($sisa_bb_, "2", ",", ".") . '' . $f . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($persen, "2", ",", ".") . '</td>
							</tr>';
		}
		$cRet .= "</table>";

		if ($ttd == "1") {

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and kode in ('PA','PPKD')";

			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<TD align="center" >' . $tanggal . '</TD>
					</TR>
					
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $jabatan . '</TD>
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
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>                    
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nama . '</TD>
					</TR>
                    <!--<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip . '</TD>
					</TR>-->
					</TABLE><br/>';
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
		$this->mpdf->SetFooter('|Halaman {PAGENO} / {nb}| ');
		$this->mpdf->AddPage($orientasi, '', '', '', '', $lMargin, $rMargin);

		if (!empty($judul)) $this->mpdf->writeHTML($judul);
		$this->mpdf->writeHTML($isi);
		$this->mpdf->Output($name, 'D');
	}



	function cetak_perda_lampI_3_2($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '', $ttdperda = '')
	{
		$lntahunang = $this->session->userdata('pcThang');
		$lntahunangg = $lntahunang;
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

		$cRet .= "<TABLE style=\"border-collapse:collapse; font-family:Bookman Old Style\" width=\"120%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>$kab</strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REKAPITULASI REALISASI BELANJA DAERAH MENURUT URUSAN PEMERINTAH DAERAH, <BR> ORGANISASI, PROGRAM, DAN KEGIATAN</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN " . $lntahunang . "</b></tr>
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
					FROM [perda_lampI.3_sub]($bulan,'$anggaran') ORDER BY kd_sub_kegiatan
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



		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$nama = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}

		$cRet .= '<TABLE style="border-collapse:collapse; font-size:16px" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<TD align="center" >' . $tanggal . '</TD>
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
						<TD align="center" ><b><u>' . $nama . '</u></b><br>' . $pangkat . '</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip . '</TD>
					</TR>
					</TABLE><br/>';



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
					<!--<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"75\" height=\"100\" />
                        </td>-->
					<td align=\"center\" style=\"border-bottom:hidden\"><strong>$kab</strong></td></tr>
                    <tr><td align=\"center\" style=\"border-bottom:hidden;border-top:hidden\"><b>REKAPITULASI ANGGARAN DAN REALISASI BELANJA DAERAH <BR/>MENURUT ORGANISASI</b></tr>
					<tr><td align=\"center\" style=\"border-top:hidden\" ><b>SAMPAI DENGAN " . strtoupper($this->custom->getBulan($bulan)) . " TAHUN ANGGARAN " . $lntahunang . "</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
                <thead>
				<tr>
                    <td rowspan = \"2\" width=\"7%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">NAMA PERANGKAT DAERAH</td>
					<td colspan = \"3\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BELANJA TIDAK LANGSUNG</td>                    
					<td colspan = \"6\" width=\"90%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">BELANJA LANGSUNG</td>                    
					<td colspan = \"3\" width=\"45%\" align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px\">TOTAL</td>
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

		$ang = "a.nilai_ang";

		$sql = "SELECT
	b.kd_org,
	b.nm_org,
	SUM (
	CASE
			
			WHEN LEFT ( a.kd_rek6, 4 ) IN ( '5101', '5102', '5103', '5104', '5105', '5107', '5108' ) THEN
			a.nilai_ang ELSE 0 
		END 
		) AS ang_btl,
		SUM (
		CASE
				
				WHEN LEFT ( a.kd_rek6, 4 ) IN ( '5101', '5102', '5103', '5104', '5105', '5107', '5108' ) THEN
				a.real_spj ELSE 0 
			END 
			) AS real_btl,
			SUM ( CASE WHEN LEFT ( a.kd_rek6, 4 ) IN ( '5201', '5202', '5203' ) THEN a.nilai_ang ELSE 0 END ) AS ang_bl,
			SUM ( CASE WHEN LEFT ( a.kd_rek6, 4 ) = '5101' THEN a.real_spj ELSE 0 END ) AS real_peg,
			SUM ( CASE WHEN LEFT ( a.kd_rek6, 4 ) = '5102' THEN a.real_spj ELSE 0 END ) AS real_brng,
			SUM ( CASE WHEN LEFT ( a.kd_rek6,2 ) = '52' THEN a.real_spj ELSE 0 END ) AS real_mod 
		FROM
			data_realisasi_pemkot a
			LEFT JOIN ms_organisasi b ON LEFT ( a.kd_skpd, 17 ) = b.kd_org 
		WHERE
			a.bulan=$bulan
		GROUP BY
			b.kd_org,
			b.nm_org 
		ORDER BY
		b.kd_org,
	b.nm_org            
					";
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $row) {
			$kode = $row->kd_org;
			$nm_rek = $row->nm_org;
			$totang_btl = $row->ang_btl;
			$totreal_btl = $row->real_btl;
			$per_angbtl   = ($totreal_btl != 0) ? ($totreal_btl / $totang_btl) * 100 : 0;

			$tot_angbl = $row->ang_bl;
			$real_peg = $row->real_peg;
			$real_brng = $row->real_brng;
			$real_mod = $row->real_mod;

			$tot_realbl = $real_peg + $real_brng + $real_mod;
			$per_angbl   = empty($tot_realbl) ? ($tot_realbl / $tot_angbl) * 100 : 0;

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

		$sql = " 
                    SELECT SUM
	( c.ang_btl ) ang_btl,
	SUM ( c.ang_bl ) ang_bl,
	SUM ( c.real_btl ) real_btl,
	SUM ( c.real_peg ) real_peg,
	SUM ( c.real_brng ) real_brng,
	SUM ( c.real_mod ) real_mod 
FROM
	(
	SELECT
		b.kd_org,
		b.nm_org,
		SUM ( CASE WHEN LEFT ( a.kd_rek6, 3 ) IN ( '510' ) THEN a.nilai_ang ELSE 0 END ) AS ang_btl,
		SUM ( CASE WHEN LEFT ( a.kd_rek6, 3 ) IN ( '510' ) THEN a.real_spj ELSE 0 END ) AS real_btl,
		SUM ( CASE WHEN LEFT ( a.kd_rek6, 3 ) IN ( '520' ) THEN a.nilai_ang ELSE 0 END ) AS ang_bl,
		SUM ( CASE WHEN LEFT ( a.kd_rek6, 4 ) = '5101' THEN a.real_spj ELSE 0 END ) AS real_peg,
		SUM ( CASE WHEN LEFT ( a.kd_rek6, 4 ) = '5102' THEN a.real_spj ELSE 0 END ) AS real_brng,
		SUM ( CASE WHEN LEFT ( a.kd_rek6, 2 ) = '52' THEN a.real_spj ELSE 0 END ) AS real_mod 
	FROM
		data_realisasi_pemkot a
		LEFT JOIN ms_organisasi b ON LEFT ( a.kd_skpd, 7 ) = b.kd_org 
	WHERE
		a.bulan= $bulan
	GROUP BY
		b.kd_org,
	b.nm_org 
	) c            
                    ";
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



		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1'";

		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$nama = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}

		if ($ttd1 == '1') {
			$nip = "";
		} else {
			$nip = "NIP. " . $ttd1;
		}

		$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<TD align="center" >' . $tanggal . '</TD>
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
						<TD align="center" ><b>' . $nama . '</b></TD>
					</TR>
                    <!--<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip . '</TD>
					</TR>-->
					</TABLE><br/>';


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
                        <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"75\" height=\"100\" />
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
					FROM [perda_lampI.3_sub_2]($bulan,'$anggaran') ORDER BY kd_kegiatan
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

			$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and kode in ('PA','BUD', 'PPKD')";

			$sqlttd = $this->db->query($sqlttd1);
			foreach ($sqlttd->result() as $rowttd) {
				$nip = $rowttd->nip;
				$nama = $rowttd->nm;
				$jabatan  = $rowttd->jab;
				$pangkat  = $rowttd->pangkat;
			}

			$cRet .= '<TABLE style="border-collapse:collapse; font-size:12px;font-family:Bookman Old Style" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
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
						<TD align="center" >' . $tanggal . '</TD>
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
						<TD align="center" ><b>' . $nama . '</b></TD>
					</TR>
                    <!--<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip . '</TD>
					</TR>-->
					</TABLE><br/>';
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
		$this->template->load('template', 'perda/cetak_perda_lampI_4', $data);
	}


	function cetak_perda_lampI_4($bulan = '', $anggaran = '', $ctk = '', $tglttd = '', $ttd = '')
	{
		$lntahunang = $this->session->userdata('pcThang');

		if ($ttd == '-') {
			$nama_ttd = '';
			$pangkat = '';
			$jabatan = '';
			$nip = '';
		} else {
			$ttd = str_replace("n", " ", $ttd);
			$sqlsc = "SELECT nama,jabatan,pangkat FROM ms_ttd where nip='$ttd' AND kode in ('PA','PPKD','WK')";
			$sqlsclient = $this->db->query($sqlsc);
			foreach ($sqlsclient->result() as $rowttd) {
				$nama_ttd = $rowttd->nama;
				$jabatan = $rowttd->jabatan;
				$pangkat = $rowttd->pangkat;
				$nip = 'NIP. ' . $ttd;
			}
		}
		$nip = '';


		$sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$kab     = $rowsc->kab_kota;
			$prov     = $rowsc->provinsi;
			$daerah  = $rowsc->daerah;
			$thn     = $rowsc->thn_ang;
		}
		$tanggal = $tglttd == '-' ? '' : $daerah . ', ' . $this->tukd_model->tanggal_format_indonesia($tglttd);

		$sqlnogub = "SELECT ket_perda, ket_perda_no, ket_perda_tentang FROM config_nogub";
		$sqlnogub = $this->db->query($sqlnogub);
		$test = $sqlnogub->num_rows();
		foreach ($sqlnogub->result() as $rowsc) {
			$ket_lampiran      = strtoupper("Lampiran I.4");
			$ket_perda         = strtoupper($rowsc->ket_perda);
			$ket_perda_no      = strtoupper($rowsc->ket_perda_no);
			$ket_perda_tentang = strtoupper($rowsc->ket_perda_tentang);
		}

		$cRet = '<TABLE style="border-collapse:collapse;font-size:11px;font-family:Arial" width="100%" border="0" cellspacing="0" cellpadding="1" align=center>
					<TR>
						<TD width="6%" valign="top" align="left" >' . $ket_lampiran . '</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >&nbsp;</TD>
					</TR>
					<TR>
						<TD colspan="3" width="100%" valign="top" align="left" >' . $ket_perda . '</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >NOMOR</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >' . $ket_perda_no . '</TD>
					</TR>
					<TR>
						<TD width="6%" valign="top" align="left" >TENTANG</TD>
						<TD width="1%"  align="center" >:</TD>
						<TD width="93%"  align="left" >' . $ket_perda_tentang . '</TD>
					</TR>
					</TABLE><br/>';

		$cRet .= "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
					<tr>
					<td rowspan=\"3\" align=\"center\" style=\"border-right:hidden\">
                        <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"75\" height=\"100\" />
                        </td>
					<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>$kab </strong></td></tr>
                    <tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>REKAPITULASI REALISASI BELANJA DAERAH UNTUK<BR>KESELARASAN DAN KETERPADUAN URUSAN PEMERINTAH DAERAH<BR> DAN FUNGSI DALAM KERANGKA PENGELOLAAN KEUANGAN NEGARA</b></tr>
					<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>TAHUN ANGGARAN $lntahunang</b></tr>
					</TABLE>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">
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

		$sql = " SELECT
					a.kode,
					a.nama,
					ISNULL( a.anggaran, 0 ) anggaran,
					ISNULL( b.realisasi, 0 ) realisasi,
					ISNULL( anggaran - realisasi, 0 ) selisih 
				FROM
					(
					SELECT
						RTRIM( a.kd_fungsi ) + '.' + a.kd_bidang_urusan AS kode,
						a.nm_bidang_urusan AS nama,
						SUM (
						ISNULL( anggaran, 0 )) AS anggaran 
					FROM
						ms_bidang_urusan a
						LEFT JOIN (
						SELECT LEFT
							( kd_sub_kegiatan, 4 ) kd_urusan,
							SUM (a.nilai ) AS anggaran 
						FROM
							trdrka a 
						WHERE
							LEFT ( a.kd_rek6, 1 ) IN ( '5' ) AND a.jns_ang='$anggaran'
						GROUP BY
						LEFT ( kd_sub_kegiatan, 4 )) b ON a.kd_bidang_urusan= b.kd_urusan 
					GROUP BY
						a.kd_fungsi,
						a.kd_bidang_urusan,
						a.nm_bidang_urusan 
					) a
					LEFT JOIN ( 
					SELECT
						RTRIM( a.kd_fungsi ) + '.' + a.kd_bidang_urusan AS kode,
						a.nm_bidang_urusan AS nama,
						SUM (
						ISNULL( realisasi, 0 )) AS realisasi 
					FROM
						ms_bidang_urusan a
						LEFT JOIN (
						SELECT LEFT
							( kd_sub_kegiatan, 4 ) kd_urusan,
							SUM ( real_spj ) AS realisasi 
						FROM
							data_realisasi_pemkot 
						WHERE
							bulan = '$bulan' AND jns_ang='$anggaran'
							AND LEFT ( kd_rek6, 1 ) IN ( '5' ) 
						GROUP BY
							LEFT ( kd_sub_kegiatan, 4 ) 
						) b ON a.kd_bidang_urusan= b.kd_urusan 
					GROUP BY
						a.kd_fungsi,
						a.kd_bidang_urusan,
						a.nm_bidang_urusan 
					) b ON a.kode= b.kode UNION ALL
				SELECT
					a.kode,
					a.nama,
					ISNULL( a.anggaran, 0 ) anggaran,
					ISNULL( b.realisasi, 0 ) realisasi,
					ISNULL( anggaran - realisasi, 0 ) selisih 
				FROM
					(
					SELECT
						a.kd_fungsi AS kode,
						a.nm_fungsi AS nama,
						SUM (
						ISNULL( anggaran, 0 )) AS anggaran 
					FROM
						ms_fungsi a
						LEFT JOIN (
						SELECT
							RTRIM( a.kd_fungsi ) AS kode,
							SUM (
							ISNULL( anggaran, 0 )) AS anggaran 
						FROM
							ms_bidang_urusan a
							LEFT JOIN (
							SELECT LEFT
								( kd_sub_kegiatan, 4 ) kd_urusan,
								SUM ( a.nilai ) AS anggaran 
							FROM
								trdrka a 
							WHERE
								LEFT ( a.kd_rek6, 1 ) IN ( '5' ) AND a.jns_ang='$anggaran'
							GROUP BY
							LEFT ( kd_sub_kegiatan, 4 )) b ON a.kd_bidang_urusan= b.kd_urusan 
						GROUP BY
							a.kd_fungsi 
						) b ON a.kd_fungsi= left(b.kode,1) 
					GROUP BY
						a.kd_fungsi,
						nm_fungsi 
					) a
					LEFT JOIN (
					SELECT
						a.kd_fungsi AS kode,
						a.nm_fungsi AS nama,
						SUM (
						ISNULL( b.realisasi, 0 )) AS realisasi 
					FROM
						ms_fungsi a
						LEFT JOIN (
						SELECT
							RTRIM( a.kd_fungsi ) AS kode,
							SUM (
							ISNULL( realisasi, 0 )) AS realisasi 
						FROM
							ms_bidang_urusan a
							LEFT JOIN (
							SELECT LEFT
								( kd_sub_kegiatan, 4 ) kd_urusan,
								SUM ( real_spj ) AS realisasi 
							FROM
								data_realisasi_pemkot 
							WHERE
								bulan = '$bulan' AND jns_ang='$anggaran'
								AND LEFT ( kd_rek6, 1 ) IN ( '5' ) 
							GROUP BY
								LEFT ( kd_sub_kegiatan, 4 ) 
							) b ON a.kd_bidang_urusan= b.kd_urusan 
						GROUP BY
							a.kd_fungsi 
						) b ON a.kd_fungsi= left(b.kode,1) 
					GROUP BY
						a.kd_fungsi,
						nm_fungsi 
					) b ON a.kode= b.kode 
				ORDER BY
					kode
					";

		$hasil = $this->db->query($sql);
		$total_ang = 0;
		$total_real = 0;
		$sisa_total = 0;
		$total_persen = 0;
		foreach ($hasil->result() as $row) {
			$kode = $row->kode;
			$nm_rek = $row->nama;
			$ang_bel = $row->anggaran;
			$real_bel = $row->realisasi;
			$selisih = $row->selisih;
			$persen = empty($ang_bel) || $ang_bel == 0 ? 0 : $real_bel / $ang_bel * 100;

			if ($selisih < 0) {
				$selisih1 = $selisih * -1;
				$a = "(";
				$b = ")";
			} else {
				$selisih1 = $selisih;
				$a = "";
				$b = "";
			}

			$kode_len = strlen($kode);
			if ($kode_len == 1) {
				$total_real = $total_real + $real_bel;
				$total_ang = $total_ang + $ang_bel;
				$sisa_total = $total_ang - $total_real;

				$total_persen = $total_real / $total_ang * 100;
			}



			$leng = strlen($kode);
			switch ($leng) {
				case 1:
					$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:12px"><b>' . $kode . '</b></td> 
							   <td align="left"  valign="top" style="font-size:12px"><b>' . $nm_rek . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_bel, "2", ",", ".") . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_bel, "2", ",", ".") . '</b> </td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . $a . ' ' . number_format($selisih1, "2", ",", ".") . ' ' . $b . '</b></td> 
							   <td align="right" valign="top" style="font-size:12px"><b>' . number_format($persen, "2", ",", ".") . '</b></td> 
							</tr>';
					break;
				default;
					$cRet .= '<tr>
							   <td align="left" valign="top" style="font-size:12px">' . $kode . '</td> 
							   <td align="left"  valign="top" style="font-size:12px">' . $nm_rek . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($ang_bel, "2", ",", ".") . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($real_bel, "2", ",", ".") . ' </td> 
							   <td align="right" valign="top" style="font-size:12px">' . $a . ' ' . number_format($selisih1, "2", ",", ".") . ' ' . $b . '</td> 
							   <td align="right" valign="top" style="font-size:12px">' . number_format($persen, "2", ",", ".") . '</td> 
							</tr>';
					break;
			}
		}
		$cRet .= '
		<tr>
			 <td colspan ="2" align="center" valign="top" style="font-size:12px"><b>TOTAL</b></td>
			 <td align="right" valign="top" style="font-size:12px"><b>' . number_format($total_ang, 2) . '</b></td> 
			 <td align="right" valign="top" style="font-size:12px"><b>' . number_format($total_real, 2) . '</b></td> 
			 <td align="right" valign="top" style="font-size:12px"><b>' . number_format($sisa_total, 2) . '</b></td> 
			 <td align="right" valign="top" style="font-size:12px"><b>' . number_format($total_persen, 2) . '</b></td>	 		    
		</tr>';


		//tidak terpakai
		// $sql2 = "SELECT ISNULL(SUM(anggaran),0) anggaran, ISNULL(SUM(realisasi),0) realisasi
		// FROM (
		// SELECT a.kode, a.nama, ISNULL(a.anggaran,0) anggaran, 
		// ISNULL(b.realisasi,0) realisasi, ISNULL(realisasi-anggaran,0) selisih
		// FROM (
		// SELECT a.kd_fungsi as kode, a.nm_fungsi as nama
		// ,SUM(ISNULL(anggaran,0)) as anggaran
		// FROM ms_fungsi a LEFT JOIN 
		// (
		// SELECT RTRIM(a.kd_fungsi) as kode
		// ,SUM(ISNULL(anggaran,0)) as anggaran
		// FROM ms_urusan a 
		// LEFT JOIN
		// (
		// SELECT LEFT(kd_kegiatan,4) kd_urusan 
		// ,SUM ($ang) AS anggaran
		// FROM trdrka a 
		// WHERE LEFT(a.kd_rek6,1) IN ('5')  GROUP BY LEFT(kd_kegiatan,4))b
		// ON a.kd_urusan=b.kd_urusan
		// GROUP BY a.kd_fungsi) b
		// on a.kd_fungsi=b.kode
		// GROUP BY a.kd_fungsi,nm_fungsi)a
		// LEFT JOIN
		// (
		// SELECT a.kd_fungsi as kode, a.nm_fungsi as nama
		// ,SUM(ISNULL(realisasi,0)) as realisasi
		// FROM ms_fungsi a LEFT JOIN 
		// (
		// SELECT RTRIM(a.kd_fungsi) as kode
		// ,SUM(ISNULL(realisasi,0)) as realisasi
		// FROM ms_urusan a 
		// LEFT JOIN
		// (
		// SELECT LEFT(kd_kegiatan,4) kd_urusan 
		// ,SUM (debet-kredit) AS realisasi
		// FROM trdju a INNER JOIN trhju b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd
		// WHERE LEFT(a.map_real,1) IN ('5')  AND MONTH(tgl_voucher)<='$bulan' AND YEAR(tgl_voucher)='$lntahunang' GROUP BY LEFT(kd_kegiatan,4))b
		// ON a.kd_urusan=b.kd_urusan
		// GROUP BY a.kd_fungsi) b
		// on a.kd_fungsi=b.kode
		// GROUP BY a.kd_fungsi,nm_fungsi)b
		// ON a.kode=b.kode ) x";
		// $hasil2 = $this->db->query($sql2);
		// foreach ($hasil2->result() as $row2) {

		// $ang_bel2 = $row2->anggaran;
		// $real_bel2 = $row2->realisasi;
		// $selisih2 = $real_bel - $ang_bel;
		// $persen2 = empty($ang_bel2) || $ang_bel2 == 0 ? 0 : $real_bel2 / $ang_bel2 * 100;

		// if ($selisih2 < 0) {
		// $selisih12 = $selisih2 * -1;
		// $a2 = "(";
		// $b2 = ")";
		// } else {
		// $selisih12 = $selisih2;
		// $a2 = "";
		// $b2 = "";
		// }




		// $cRet .= '<tr>
		// <td align="center" valign="top" style="font-size:12px" colspan="2"><b>TOTAL</b></td> 
		// <td align="right" valign="top" style="font-size:12px"><b>' . number_format($ang_bel2, "2", ",", ".") . '</b></td> 
		// <td align="right" valign="top" style="font-size:12px"><b>' . number_format($real_bel2, "2", ",", ".") . '</b> </td> 
		// <td align="right" valign="top" style="font-size:12px"><b>' . $a2 . ' ' . number_format($selisih12, "2", ",", ".") . ' ' . $b2 . '</b></td> 
		// <td align="right" valign="top" style="font-size:12px"><b>' . number_format($persen2, "2", ",", ".") . '</b></td> 
		// </tr>';
		// }




		$cRet .= "</table>";

		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
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
		$data['prev'] = $cRet;
		$judul = 'Perda_Lamp_I.4';
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

	//================================================ End Lamp Perda I.4

}
