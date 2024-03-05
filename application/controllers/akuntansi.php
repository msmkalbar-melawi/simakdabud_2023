<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');


class Akuntansi extends CI_Controller
{

	function __contruct()
	{
		parent::__construct();
	}


	// Neraca Saldo
	function neracasaldo()
	{
		$data['page_title'] = 'CETAK NERACA SALDO';
		$this->template->set('title', 'NERACA SALDO');
		$this->template->load('template', 'akuntansi/neracasaldo', $data);
	}

	// Cetakan Neraca Saldo
	function cetakneraca_saldo($dcetak = '', $skpd = '', $dcetak2 = '', $jenis = '', $tglttd)
	{
		$tgltanda = $this->tukd_model->tanggal_format_indonesia($tglttd);
		$thn_ang = $this->session->userdata('pcThang');
		$thn_ang_1 = $thn_ang - 1;
		$cRet = '<TABLE width="100%" >
					<TR>
						<TD align="center" ><B>NERACA SALDO </B></TD>
					</TR>
					</TABLE>';

		$cRet .= '<TABLE width="100%">
					<TR>
						<TD align="left" width="20%" >SKPD</TD>
						<TD align="left" width="80%" >: ' . $skpd . ' ' . $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . '</TD>
					</TR>					 
					<TR>
						<TD align="left" width="20%" >Periode</TD>
						<TD align="left" width="80%" >: ' . $this->tukd_model->tanggal_format_indonesia($dcetak) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($dcetak2) . '</TD>
					</TR>
					</TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
					<THEAD>
					<TR>
						<TD width="10%"  bgcolor="#CCCCCC" align="center" >KODE</TD>
						<TD width="50%" bgcolor="#CCCCCC" align="center" >NAMA REKENING</TD>						
						<TD width="15%" bgcolor="#CCCCCC" align="center" >DEBET</TD>
						<TD width="15%" bgcolor="#CCCCCC" align="center" >KREDIT</TD>						
					</TR>
					</THEAD>';

		$idx = 1;


		$saldo_debet1 = 0;
		$saldo_debet = 0;
		$saldo_kredit1 = 0;
		$saldo_kredit = 0;
		$query = $this->db->query("SELECT DISTINCT isnull(kd_rek6,'') kd_rek6 , isnull(nm_rek6,'') nm_rek6,sum(debet) as debet,sum(kredit) as kredit FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
				where left(kd_skpd,22)= left('$skpd',22) and
				b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2' 
				group by kd_rek6,nm_rek6 order by kd_rek6");

		if ($query->num_rows() > 0) {
			$jdebet = 0;
			$jkredit = 0;
			foreach ($query->result_array() as $res) {

				$kd_rek6 = $res['kd_rek6'];
				$nm_rek6 = $res['nm_rek6'];
				$debet = $res['debet'];
				$kredit = $res['kredit'];
				$idx++;

				if ((substr($kd_rek6, 0, 1) == '9') or (substr($kd_rek6, 0, 1) == '8') or (substr($kd_rek6, 0, 1) == '5') or (substr($kd_rek6, 0, 2) == '62') or (substr($kd_rek6, 0, 2) == '72') or (substr($kd_rek6, 0, 1) == '1')) {
					$saldo_debet = $debet - $kredit;
				} else {
					$saldo_kredit = $kredit - $debet;
				}

				if ($saldo_debet < 0) {
					$saldo_debet1 = $saldo_debet * -1;
					$c = '(';
					$d = ')';
				} else {
					$saldo_debet1 = $saldo_debet;
					$c = '';
					$d = '';
				}

				if ($saldo_kredit < 0) {
					$saldo_kredit1 = $saldo_kredit * -1;
					$e = '(';
					$f = ')';
				} else {
					$saldo_kredit1 = $saldo_kredit;
					$e = '';
					$f = '';
				}

				if ((substr($kd_rek6, 0, 1) == '8') or (substr($kd_rek6, 0, 1) == '5') or (substr($kd_rek6, 0, 2) == '62') or (substr($kd_rek6, 0, 1) == '1')) {
					$cRet .= '<TR>
								<TD width="10%" align="left" >' . $kd_rek6 . '</TD>
								<TD width="50%" align="left" >' . $nm_rek6 . '</TD>
								<TD width="15%" align="right" >' . $c . '' . number_format($saldo_debet1, "2", ",", ".") . '' . $d . '</TD>
								<TD width="15%" align="right" ></TD>						
							</TR>';
					//$jdebet=$jdebet+$saldo_debet1;

				} else {
					$cRet .= '<TR>
								<TD width="10%" align="left" >' . $kd_rek6 . '</TD>
								<TD width="50%" align="left" >' . $nm_rek6 . '</TD>
								<TD width="15%" align="right" ></TD>
								<TD width="15%" align="right" >' . $e . '' . number_format($saldo_kredit1, "2", ",", ".") . '' . $f . '</TD>						
							</TR>';
					//$jkredit = $jkredit + $saldo_kredit1;

				}
			}
		}

		$query = $this->db->query("SELECT isnull(sum(z.jml_debet),0) as nil_debet,isnull(sum(z.jml_kredit),0) as nil_kredit from (				 
				SELECT isnull(sum(debet),0)-isnull(sum(kredit),0) as jml_debet,0 as jml_kredit FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
								where left(kd_skpd,22)= left('$skpd',22) and
				b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2' 
								and LEFT(a.kd_rek6,1) in ('1','8')
								union all
				SELECT 0 as jml_debet,isnull(sum(kredit),0)-isnull(sum(debet),0) as jml_kredit FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
								where left(kd_skpd,22)= left('$skpd',22) and
				b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2' 
								and LEFT(a.kd_rek6,1) in ('2','3','7')				 
				) z
				");
		foreach ($query->result_array() as $res) {

			$jmldebet = $res['nil_debet'];
			$jmlkredit = $res['nil_kredit'];
			$idx++;
			if ($jmldebet < 0) {
				$jmldebet = $jmldebet * -1;
				$k = '(';
				$l = ')';
			} else {
				$jmldebet = $jmldebet;
				$k = '';
				$l = '';
			}
			if ($jmlkredit < 0) {
				$jmlkredit = $jmlkredit * -1;
				$m = '(';
				$n = ')';
			} else {
				$jmlkredit = $jmlkredit;
				$m = '';
				$n = '';
			}

			$cRet .= '<TR>
					<TD width="10%" align="left" ></TD>
					<TD width="50%" align="center" >JUMLAH</TD>				
					<TD width="15%" align="right" >' . $k . '' . number_format($jmldebet, "2", ",", ".") . '' . $l . '</TD>
					<TD width="15%" align="right" >' . $m . '' . number_format($jmlkredit, "2", ",", ".") . '' . $n . '</TD>					
				</TR>';
			$cRet .= '</TABLE>';
		}

		//$ttd=str_replace("a"," ",$ttd);
		$sqlsc = "SELECT top 1 nama,jabatan,pangkat,nip FROM ms_ttd where kd_skpd='$skpd' AND kode='PA'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowttd) {
			$nama_ttd = $rowttd->nama;
			$jabatan = $rowttd->jabatan;
			$pangkat = $rowttd->pangkat;
			$nip = 'NIP. ' . $rowttd->nip;
		}

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet .= "<table style=\"border-collapse:collapse;font-family:Arial;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
				<tr>
					<td width=\"50%\" align=\"center\">&nbsp;</td>
					<td width=\"50%\" align=\"center\"></td>
				</tr>
				<tr>
					<td width=\"50%\" align=\"center\">&nbsp;</td>
					<td width=\"50%\" align=\"center\">" . $sclient->daerah . ", " . $tgltanda . "<br>$jabatan<br><br><br><br><br><b><u>$nama_ttd</u></b><br>$pangkat<br>$nip
					</td>
				</tr>
				</table>";

		if ($jenis == 1) {
			echo '<title> Neraca Saldo </title>';
			echo $cRet;
		}
		if ($jenis == 2) {
			$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
		}
	}
	// End

	function bukubesar()
	{

		$data['page_title'] = 'CETAK BUKU BESAR';
		$this->template->set('title', 'BUKU BESAR');
		$this->template->load('template', 'akuntansi/bukubesar', $data);
	}


	function bukubesar_kasda()
	{

		$data['page_title'] = 'CETAK BUKU BESAR';
		$this->template->set('title', 'BUKU BESAR');
		$this->template->load('template', 'akuntansi/bukubesar_kasda', $data);
	}

	function skpd()
	{
		$id  = $this->session->userdata('pcUser');
		$usernm      = $this->session->userdata('pcNama');
		$lccr = $this->input->post('q');

		if ($usernm == 'pajak') {
			$sql = "SELECT a.kd_skpd,a.nm_skpd,b.jns_ang FROM ms_skpd a join trhrka b on a.kd_skpd=b.kd_skpd where a.kd_skpd IN  
					(SELECT kd_skpd FROM user_akt WHERE user_id='$id') AND (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) order by kd_skpd";
		} else {
			$sql = "SELECT kd_skpd,nm_skpd, '' as jns_ang FROM ms_skpd where upper(kd_skpd) 
					like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%') ";
		}
		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {

			$result[] = array(
				'id' => $ii, 'kd_skpd' => $resulte['kd_skpd'],
				'nm_skpd' => $resulte['nm_skpd'],
				'jns_ang' => $resulte['jns_ang']
			);
			$ii++;
		}

		echo json_encode($result);
	}

	function rekening_kasda()
	{
		$lccr    = $this->input->post('q');
		$kd_skpd = $this->input->post('kd_skpd');

		$sql = " SELECT DISTINCT kd_rek6,nm_rek6 FROM trdrka_pend where kd_skpd='$kd_skpd' AND kd_rek6 like '$lccr%' group by kd_skpd,kd_rek6,nm_rek6";

		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {

			$result[] = array(
				'id' => $ii,
				'kd_rek6' => $resulte['kd_rek6'],
				'nm_rek6' => $resulte['nm_rek6'],
			);
			$ii++;
		}

		echo json_encode($result);
	}

	function bp_ro_penerimaan()
	{

		$data['page_title'] = 'CETAK BUKU PEMBANTU PER RINCIAN OBYEK PENERIMAAN';
		$this->template->set('title', 'CETAK BUKU PEMBANTU PER RINCIAN OBYEK PENERIMAAN');
		$this->template->load('template', 'akuntansi/bp_ro_penerimaan', $data);
	}

	function get_status($tgl, $skpd)
	{
		$n_status = '';
		$tanggal = $tgl;
		$sql = "select case when '$tanggal'>=tgl_dpa_ubah then 'nilai_ubah' 
                    when '$tanggal'>=tgl_dpa_sempurna then 'nilai_sempurna' 
                    when '$tanggal'<=tgl_dpa 
                    then 'nilai' else 'nilai' end as anggaran from trhrka where kd_skpd ='$skpd' ";

		$q_trhrka = $this->db->query($sql);
		$num_rows = $q_trhrka->num_rows();

		foreach ($q_trhrka->result() as $r_trhrka) {
			$n_status = $r_trhrka->anggaran;
		}
		return $n_status;
	}

	function cetak_bp_ro_penerimaan($dcetak = '', $ttd = '', $skpd = '', $rek5 = '', $dcetak2 = '', $jenis = '', $ttd2 = '', $tgl_ctk = '')
	{
		$thn_ang = $this->session->userdata('pcThang');
		$ang = $this->get_status($dcetak2, $skpd);

		$lcttd = str_replace('a', ' ', $ttd);
		$sqlttd1 = "SELECT TOP 1 nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$lcttd'";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$nama = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat = $rowttd->pangkat;
		}

		$lcttd2 = str_replace('a', ' ', $ttd2);
		$sqlttd2 = "SELECT TOP 1 nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$lcttd2'";
		$sqlttd_2 = $this->db->query($sqlttd2);
		foreach ($sqlttd_2->result() as $rowttd) {
			$nip2 = $rowttd->nip;
			$nama2 = $rowttd->nm;
			$jabatan2  = $rowttd->jab;
			$pangkat2 = $rowttd->pangkat;
		}

		$prv = $this->db->query("select SUM($ang) anggaran from trdrka_pend where kd_skpd='$skpd' and kd_rek6='$rek5'");
		$prvn = $prv->row();
		$anggaran = $prvn->anggaran;




		$sqldns = "SELECT a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan WHERE kd_skpd='$skpd'";
		$sqlskpd = $this->db->query($sqldns);
		foreach ($sqlskpd->result() as $rowdns) {

			$kd_urusan = $rowdns->kd_u;
			$nm_urusan = $rowdns->nm_u;
			$kd_skpd  = $rowdns->kd_sk;
			$nm_skpd  = $rowdns->nm_sk;
		}

		$sqlorg = "SELECT * FROM ms_organisasi WHERE kd_org=left('$skpd',17)";
		$sqlorg1 = $this->db->query($sqlorg);
		foreach ($sqlorg1->result() as $rowdns) {

			$kd_org = $rowdns->kd_org;
			$nm_org = $rowdns->nm_org;
		}
		$sqlurusan1 = "SELECT * FROM ms_urusan WHERE kd_urusan=left('$kd_urusan',1)";
		$sqlskpd = $this->db->query($sqlurusan1);
		foreach ($sqlskpd->result() as $rowdns) {

			$kd_urusan1 = $rowdns->kd_urusan;
			$nm_urusan1 = $rowdns->nm_urusan;
		}

		$cRet = "<TABLE style=\"border-collapse:collapse;font-family:Arial\" width=\"100%\" border=\"0\">
					<TR>
						<td width=\"5%\" rowspan=\"2\" align=\"center\"><img src=\"" . base_url() . "/image/logoHP.bmp\"  width=\"100\" height=\"100\" /></td>
						<TD width=\"95%\" align=\"center\" ><B>PEMERINTAH KABUPATEN MELAWI<br>
											   BUKU PEMBANTU<br>
											   PER RINCIAN OBYEK PENERIMAAN</B>
						</TD>
					</TR>
					<TR>
						<TD align=\"center\" colspan=\"2\"><i>Periode " . $this->tukd_model->tanggal_format_indonesia($dcetak) . " s/d " . $this->tukd_model->tanggal_format_indonesia($dcetak2) . "</i></TD>
					</TR>
					</TABLE><br>";

		$cRet .= '<TABLE style="border-collapse:collapse;font-family:Arial;font-size:12px;"  width="100%">
					 <TR>
						<TD align="left" width="20%" >Urusan Pemerintahan</TD>
						<TD align="left" width="80%" >: ' . $kd_urusan1 . ' - ' . $nm_urusan1 . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="20%" >Bidang Pemerintahan</TD>
						<TD align="left" width="80%" >: ' . $kd_urusan . ' - ' . $nm_urusan . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="20%" >Unit Organisasi</TD>
						<TD align="left" width="80%" >: ' . $kd_org . ' - ' . $nm_org . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="20%" >Sub Unit Organisasi</TD>
						<TD align="left" width="80%" >: ' . $kd_skpd . ' - ' . $nm_skpd . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="20%" >Kode Rekening</TD>
						<TD align="left" width="80%" >: ' . $rek5 . '</TD>
					 </TR>
					  <TR>
						<TD align="left" width="20%" >Nama Rekening</TD>
						<TD align="left" width="80%" >: ' . $this->tukd_model->get_nama($rek5, 'nm_rek6', 'ms_rek6', 'kd_rek6') . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="20%" >Jumlah Anggaran</TD>
						<TD align="left" width="80%" >: Rp. ' . number_format($anggaran, "2", ",", ".") . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="20%" >Tahun Anggaran</TD>
						<TD align="left" width="80%" >: ' . $thn_ang . '</TD>
					 </TR>
					 </TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse;font-family:Arial;font-size:12px" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
					 <THEAD>
					 <TR>
						<TD width="5%"  bgcolor="#CCCCCC" align="center" ><b>NO</b></TD>
						<TD width="15%" bgcolor="#CCCCCC" align="center" ><b>NO BKU</b></TD>
						<TD width="25%" bgcolor="#CCCCCC" align="center" ><b>TGL SETOR</b></TD>
						<TD width="35%" bgcolor="#CCCCCC" align="center" ><b>NO. STS & BUKTI PENERIMAAN LAINNYA</b></TD>
						<TD width="20%" bgcolor="#CCCCCC" align="center" ><b>JUMLAH</b></TD>
					 </TR>
					 </THEAD>';

		//isi
		$sql = "select b.no_sts, a.tgl_sts, a.keterangan, b.rupiah from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts where b.kd_skpd='$skpd' and b.kd_rek6='$rek5' AND a.jns_trans=4 AND (a.tgl_sts BETWEEN '$dcetak' AND '$dcetak2') order by tgl_sts, no_sts, rupiah";
		$no = 1;
		$tot = 0;
		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $rows) {
			$no_sts     = $rows->no_sts;
			$tgl_sts    = $rows->tgl_sts;
			$keterangan = $rows->keterangan;
			$rupiah     = $rows->rupiah;

			$cRet .= '<TR>
								<TD align="center" >' . $no++ . '</TD>
								<TD align="center" >' . $no_sts . '</TD>
								<TD align="center" >' . $this->tukd_model->tanggal_format_indonesia($tgl_sts) . '</TD>
								<TD align="left" >' . $keterangan . '</TD>
								<TD  align="right" >' . number_format($rupiah, "2", ",", ".") . '</TD>
							 </TR>';

			$tot = $tot + $rupiah;
		}

		$lalu = $this->db->query("select SUM(b.rupiah) tot from trhkasin_pkd a inner join trdkasin_pkd b on a.kd_skpd=b.kd_skpd and a.no_sts=b.no_sts where b.kd_skpd='$skpd' and b.kd_rek6='$rek5 ' AND a.jns_trans=4 AND a.tgl_sts<'$dcetak'");
		$nlalu = $lalu->row();
		$tot_lalu = $nlalu->tot;

		$cRet .= '<TR>
						<TD style="border-top:none" align="left" colspan="3" >Jumlah Periode Ini</TD>
						<TD style="border-top:none" colspan="2" align="center" >Rp. ' . number_format($tot, "2", ",", ".") . '</TD>
					 </TR>
					 <TR>
						<TD style="border-top:none" align="left" colspan="3" >Jumlah Periode lalu</TD>
						<TD style="border-top:none" colspan="2" align="center" >Rp. ' . number_format($tot_lalu, "2", ",", ".") . '</TD>
					 </TR>
					  <TR>
						<TD style="border-top:none" align="left" colspan="3" >Jumlah Sampai Dengan Periode Ini</TD>
						<TD style="border-top:none" colspan="2" align="center" >Rp. ' . number_format($tot_lalu + $tot, "2", ",", ".") . '</TD>
					 </TR>';

		$cRet .= '</TABLE><br>';

		$cRet .= "<table style=\"font-size:12px;border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
						<td align=\"center\" width=\"50%\">Mengetahui,</td>
						<td align=\"center\" width=\"50%\">Pontianak, " . $this->tukd_model->tanggal_format_indonesia($tgl_ctk) . "</td>
					</tr>
					<tr>
						<td align=\"center\" width=\"50%\"><b>$jabatan</b></td>
					    <td align=\"center\" width=\"50%\"><b>$jabatan2</b></td>
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
						<td align=\"center\" width=\"50%\"><u>$nama</u></td>
						<td align=\"center\" width=\"50%\"><u>$nama2</u></td>
					</tr>
                    <tr>
						<td align=\"center\" width=\"50%\">$pangkat</td>
						<td align=\"center\" width=\"50%\">$pangkat2</td>
					</tr>
					<tr>
						<td align=\"center\" width=\"50%\">NIP. $nip</td>
						<td align=\"center\" width=\"50%\">NIP. $nip2</td>
					</tr>
                    
                  </table>";

		if ($jenis == 1) {
			echo '<title>CETAK BUKU PEMBANTU PER RINCIAN OBYEK PENERIMAAN</title>';
			echo $cRet;
		}
		if ($jenis == 2) {
			$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
		}
	}


	function rpt_neraca_pemda_unit_obyek($cbulan = "", $kd_skpd = "", $cetak = 1)
	{
		//$bulan   = $_REQUEST['tgl1'];
		$id     = $this->session->userdata('kdskpd');
		$thn_ang  = $this->session->userdata('pcThang');
		$thn_ang_1  = $thn_ang - 1;
		$bulan   = $cbulan;
		$cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;

		$sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd' ";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {

			$nmskpd  = $rowsc->nm_skpd;
		}

		$nm_skpd  = strtoupper($nmskpd);

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
		$modtahun = $thn_ang % 4;

		if ($modtahun = 0) {
			$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		} else {
			$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		$cRet = '';

		$cRet = "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
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

		$sqllo10 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7')and kd_skpd='$kd_skpd'";
		$querylo10 = $this->db->query($sqllo10);
		$pen8 = $querylo10->row();
		$pen_lalu8 = $pen8->nilai;
		$pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8')and kd_skpd='$kd_skpd'";
		$querylo12 = $this->db->query($sqllo12);
		$bel10 = $querylo12->row();
		$bel_lalu10 = $bel10->nilai;
		$bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

		$sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_ll1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_ll2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_ll3  = $row003->thn_m1;
		}


		$query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM trdju_pkd a INNER JOIN trhju_pkd b 
      ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='310101010001' AND YEAR(b.tgl_voucher)<'$thn_ang'
      and b.tabel=1 and reev=0 and kd_skpd='$kd_skpd'");
		foreach ($query3->result_array() as $res2) {
			$debet3 = $res2['debet'];
			$kredit3 = $res2['kredit'];
		}

		$real = $kredit3 - $debet3 + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;

		//    created by henri_tb
		$sqllo9 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
		$querylo9 = $this->db->query($sqllo9);
		$penlo7 = $querylo9->row();
		$pen_lo7 = $penlo7->nilai;
		$pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

		$sqllo10 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
		$querylo10 = $this->db->query($sqllo10);
		$penlo8 = $querylo10->row();
		$pen_lo_lalu8 = $penlo8->nilai;
		$pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

		$sqllo11 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
		$querylo11 = $this->db->query($sqllo11);
		$bello9 = $querylo11->row();
		$bel_lo9 = $bello9->nilai;
		$bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
		$querylo12 = $this->db->query($sqllo12);
		$bello10 = $querylo12->row();
		$bel_lo_lalu10 = $bello10->nilai;
		$bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

		$surplus_lo3 = $pen_lo7 - $bel_lo9;

		$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

		$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;

		$sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_lalu1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_lalu2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_lalu3  = $row003->thn_m1;
		}

		$sal_awal	= $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;

		$sql = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$nilaiDR  = $row001->thn_m1;
		}

		$sqllpe1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$nilailpe1  = $row002->thn_m1;
		}

		$sqllpe2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$nilailpe2  = $row003->thn_m1;
		}

		$sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2;

		$sqlutang_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlutang_lalu);
		foreach ($hasil->result() as $row) {
			$nilaiutang_lalu  = $row->thn_m1;
		}


		$sqlkas_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlkas_lalu);
		foreach ($hasil->result() as $row) {
			$rk_ppkd_lalu  = $row->thn_m1;
		}

		$sqlskpd_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlskpd_lalu);
		foreach ($hasil->result() as $row) {
			$rk_skpd_lalu  = $row->thn_m1;
		}


		$sqllcr_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllcr_lalu);
		foreach ($hasil->result() as $row) {
			$lcrx_lalu  = $row->thn_m1;
		}

		$sqlast_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlast_lalu);
		foreach ($hasil->result() as $row) {
			$astx_lalu  = $row->thn_m1;
		}

		$lcr_lalu		= $lcrx_lalu - $rk_skpd_lalu;
		$ast_lalu		= $astx_lalu - $rk_skpd_lalu;
		$eku_lalu 		= $sal_awal + $rk_ppkd_lalu; //-$rk_skpd_lalu;					
		$eku_tang_lalu 	= $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu - $rk_skpd_lalu;

		$sqlutang = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher
      and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlutang);
		foreach ($hasil->result() as $row) {
			$nilaiutang  = $row->thn_m1;
		}

		$sqlkas = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlkas);
		foreach ($hasil->result() as $row) {
			$rk_ppkd  = $row->thn_m1;
		}

		$sqlskpd = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlskpd);
		foreach ($hasil->result() as $row) {
			$rk_skpd  = $row->thn_m1;
		}

		$sqllcr = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllcr);
		foreach ($hasil->result() as $row) {
			$lcrx = $row->thn_m1;
		}

		$sqlast = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlast);
		foreach ($hasil->result() as $row) {
			$astx  = $row->thn_m1;
		}

		/*$lcr			= $lcrx-$rk_skpd;
			$ast			= $astx-$rk_skpd;
			$eku 			= $sal_akhir - $rk_ppkd + $rk_skpd;					
			$eku_tang 		= $sal_akhir + $nilaiutang - $rk_ppkd +$rk_skpd;*/
		/*$rk_skpd		= $rk_skpd-$rk_ppkd;
			$rk_skpd_lalu	= $rk_skpd_lalu-$rk_ppkd_lalu;
			$rk_ppkd		= $rk_skpd;
			$rk_ppkd_lalu	= $rk_skpd_lalu;*/


		$lcr    = $lcrx - $rk_skpd;
		$ast    = $astx - $rk_skpd;
		$eku    = $sal_akhir + $rk_ppkd;
		$eku_tang   = $sal_akhir + $nilaiutang + $rk_ppkd;


		if ($sal_akhir < 0) {
			$c = "(";
			$sal_akhir = $sal_akhir * -1;
			$d = ")";
		} else {
			$c = "";
			$sal_akhir;
			$d = "";
		}

		$sal_akhir1 = number_format($sal_akhir, "2", ",", ".");

		if ($sal_awal < 0) {
			$c1 = "(";
			$sal_awal = $sal_awal * -1;
			$d1 = ")";
		} else {
			$c1 = "";
			$sal_awal;
			$d1 = "";
		}

		$sal_awal1 = number_format($sal_awal, "2", ",", ".");


		if ($eku_lalu < 0) {
			$min001 = "(";
			$eku_lalu = $eku_lalu * -1;
			$min002 = ")";
		} else {
			$min001 = "";
			$eku_lalu;
			$min002 = "";
		}

		$eku_lalu1 = number_format($eku_lalu, "2", ",", ".");

		if ($eku < 0) {
			$min003 = "(";
			$eku = $eku * -1;
			$min004 = ")";
		} else {
			$min003 = "";
			$eku;
			$min004 = "";
		}

		$eku1 = number_format($eku, "2", ",", ".");

		if ($eku_tang_lalu < 0) {
			$min005 = "(";
			$eku_tang_lalu = $eku_tang_lalu * -1;
			$min006 = ")";
		} else {
			$min005 = "";
			$eku_tang_lalu;
			$min006 = "";
		}

		$eku_tang_lalu1 = number_format($eku_tang_lalu, "2", ",", ".");

		if ($eku_tang < 0) {
			$min007 = "(";
			$eku_tang = $eku_tang * -1;
			$min008 = ")";
		} else {
			$min007 = "";
			$eku_tang;
			$min008 = "";
		}

		$eku_tang1 = number_format($eku_tang, "2", ",", ".");

		if ($rk_ppkd_lalu < 0) {
			$min009 = "(";
			$rk_ppkd_lalu = $rk_ppkd_lalu * -1;
			$min010 = ")";
		} else {
			$min009 = "";
			$rk_ppkd_lalu;
			$min010 = "";
		}

		$rk_ppkd_lalu1 = number_format($rk_ppkd_lalu, "2", ",", ".");

		if ($rk_ppkd < 0) {
			$min013 = "(";
			$rk_ppkd = $rk_ppkd * -1;
			$min014 = ")";
		} else {
			$min013 = "";
			$rk_ppkd;
			$min014 = "";
		}

		$rk_ppkd1 = number_format($rk_ppkd, "2", ",", ".");

		if ($lcr < 0) {
			$min015 = "(";
			$lcr = $lcr * -1;
			$min016 = ")";
		} else {
			$min015 = "";
			$lcr;
			$min016 = "";
		}

		$lcr1 = number_format($lcr, "2", ",", ".");

		if ($lcr_lalu < 0) {
			$min017 = "(";
			$lcr_lalu = $lcr_lalu * -1;
			$min018 = ")";
		} else {
			$min017 = "";
			$lcr_lalu;
			$min018 = "";
		}

		$lcr_lalu1 = number_format($lcr_lalu, "2", ",", ".");

		if ($ast < 0) {
			$min019 = "(";
			$ast = $ast * -1;
			$min020 = ")";
		} else {
			$min019 = "";
			$ast;
			$min020 = "";
		}

		$ast1 = number_format($ast, "2", ",", ".");

		if ($ast_lalu < 0) {
			$min021 = "(";
			$ast_lalu = $ast_lalu * -1;
			$min022 = ")";
		} else {
			$min021 = "";
			$ast_lalu;
			$min022 = "";
		}

		$ast_lalu1 = number_format($ast_lalu, "2", ",", ".");

		if ($rk_skpd_lalu < 0) {
			$min031 = "(";
			$rk_skpd_lalu = $rk_skpd_lalu * -1;
			$min032 = ")";
		} else {
			$min031 = "";
			$rk_skpd_lalu;
			$min032 = "";
		}

		$rk_skpd_lalu1 = number_format($rk_skpd_lalu, "2", ",", ".");

		if ($rk_skpd < 0) {
			$min033 = "(";
			$rk_skpd = $rk_skpd * -1;
			$min034 = ")";
		} else {
			$min033 = "";
			$rk_skpd;
			$min034 = "";
		}

		$rk_skpd1 = number_format($rk_skpd, "2", ",", ".");

		$queryneraca = " SELECT kode, uraian, bold, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
										isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
										isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
										isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
										FROM map_neraca_permen_77_obyek ORDER BY seq ";

		$query10 = $this->db->query($queryneraca);

		$no     = 0;

		foreach ($query10->result_array() as $res) {
			$uraian = $res['uraian'];
			$normal = $res['normal'];
			$bold = $res['bold'];
			$kode_1 = trim($res['kode_1']);
			$kode_2 = trim($res['kode_2']);
			$kode_3 = trim($res['kode_3']);
			$kode_4 = trim($res['kode_4']);
			$kode_5 = trim($res['kode_5']);
			$kode_6 = trim($res['kode_6']);
			$kode_7 = trim($res['kode_7']);
			$kode_8 = trim($res['kode_8']);
			$kode_9 = trim($res['kode_9']);
			$kode_10 = trim($res['kode_10']);
			$kode_11 = trim($res['kode_11']);
			$kode_12 = trim($res['kode_12']);
			$kode_13 = trim($res['kode_13']);
			$kode_14 = trim($res['kode_14']);
			$kode_15 = trim($res['kode_15']);


			$q = $this->db->query("  SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
                  and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd' and
                    (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                    kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                    kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                    kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                    kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                    kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                    kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                    kd_rek6 like '$kode_15%') ");

			foreach ($q->result_array() as $r) {
				$debet = $r['debet'];
				$kredit = $r['kredit'];
			}

			if ($debet == '') $debet = 0;
			if ($kredit == '') $kredit = 0;

			if ($normal == 1) {
				$nl = $debet - $kredit;
			} else {
				$nl = $kredit - $debet;
			}
			if ($nl == '') $nl = 0;

			// Jurnal Tahun lalu
			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
                  and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1  and kd_skpd='$kd_skpd' and
                    (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                    kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                    kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                    kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                    kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                    kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                    kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                    kd_rek6 like '$kode_15%') ");

			foreach ($q->result_array() as $rx) {
				$debet_lalu = $rx['debet'];
				$kredit_lalu = $rx['kredit'];
			}

			if ($debet_lalu == '') $debet_lalu = 0;
			if ($kredit_lalu == '') $kredit_lalu = 0;

			if ($normal == 1) {
				$sblm = $debet_lalu - $kredit_lalu;
			} else {
				$sblm = $kredit_lalu - $debet_lalu;
			}
			if ($sblm == '') $sblm = 0;

			if ($nl < 0) {
				$nl001 = "(";
				$nl = $nl * -1;
				$ln001 = ")";
			} else {
				$nl001 = "";
				$ln001 = "";
			}
			if ($sblm < 0) {
				$sblm001 = "(";
				$sblm = $sblm * -1;
				$mlbs001 = ")";
			} else {
				$sblm001 = "";
				$mlbs001 = "";
			}
			$nl1 = number_format($nl, "2", ",", ".");
			$sblm1 = number_format($sblm, "2", ",", ".");

			$no       = $no + 1;

			switch ($res['bold']) {
				case 1:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td colspan =\"7\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                   					<td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 2:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                   					<td colspan =\"6\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                   					<td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 3:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                   					<td colspan =\"5\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
                  	 				<td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
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
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min015$lcr1$min016</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min017$lcr_lalu1$min018</td>
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
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min019$ast1$min020</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min021$ast_lalu1$min022</td>
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


		$cRet .= '</table>';

		$data['prev'] = $cRet;
		$data['sikap'] = 'preview';
		$judul = ("NERACA KONSOL OBYEK UNIT $cbulan");
		$this->template->set('title', 'NERACA KONSOL OBYEK SKPD $cbulan');
		switch ($cetak) {
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


	function rpt_neraca_pemda_obyek($cbulan = "", $cetak = 1)
	{
		//$bulan	 = $_REQUEST['tgl1'];
		$kd_skpd   	= $this->session->userdata('kdskpd');
		$thn_ang	= $this->session->userdata('pcThang');
		$thn_ang_1	= $thn_ang - 1;
		$bulan	 = $cbulan;
		$cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;

		$sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd' ";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {

			$nmskpd  = $rowsc->nm_skpd;
		}

		$nm_skpd	= strtoupper($nmskpd);

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
		$modtahun = $thn_ang % 4;

		if ($modtahun = 0) {
			$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		} else {
			$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		$cRet = '';

		$cRet = "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
					<tr>
                         <td align=\"center\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
                    </tr>
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

		$sqllo10 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7')";
		$querylo10 = $this->db->query($sqllo10);
		$pen8 = $querylo10->row();
		$pen_lalu8 = $pen8->nilai;
		$pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8')";
		$querylo12 = $this->db->query($sqllo12);
		$bel10 = $querylo12->row();
		$bel_lalu10 = $bel10->nilai;
		$bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

		$sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_ll1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_ll2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_ll3  = $row003->thn_m1;
		}


		$query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM trdju_pkd a INNER JOIN trhju_pkd b 
      ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='310101010001' AND YEAR(b.tgl_voucher)<'$thn_ang'
      and b.tabel=1 and reev=0");
		foreach ($query3->result_array() as $res2) {
			$debet3 = $res2['debet'];
			$kredit3 = $res2['kredit'];
		}

		$real = $kredit3 - $debet3 + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;

		//    created by henri_tb
		$sqllo9 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7')";
		$querylo9 = $this->db->query($sqllo9);
		$penlo7 = $querylo9->row();
		$pen_lo7 = $penlo7->nilai;
		$pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

		$sqllo10 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7')";
		$querylo10 = $this->db->query($sqllo10);
		$penlo8 = $querylo10->row();
		$pen_lo_lalu8 = $penlo8->nilai;
		$pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

		$sqllo11 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8')";
		$querylo11 = $this->db->query($sqllo11);
		$bello9 = $querylo11->row();
		$bel_lo9 = $bello9->nilai;
		$bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8')";
		$querylo12 = $this->db->query($sqllo12);
		$bello10 = $querylo12->row();
		$bel_lo_lalu10 = $bello10->nilai;
		$bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

		$surplus_lo3 = $pen_lo7 - $bel_lo9;

		$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

		$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;

		$sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_lalu1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_lalu2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_lalu3  = $row003->thn_m1;
		}

		$sal_awal	= $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;

		$sql = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //aba

		$hasil = $this->db->query($sql);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$nilaiDR  = $row001->thn_m1;
		}

		$sqllpe1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //Henri_TB

		$hasil = $this->db->query($sqllpe1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$nilailpe1  = $row002->thn_m1;
		}

		$sqllpe2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
          inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //Henri_TB

		$hasil = $this->db->query($sqllpe2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$nilailpe2  = $row003->thn_m1;
		}

		$sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2;

		$sqlutang_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqlutang_lalu);
		foreach ($hasil->result() as $row) {
			$nilaiutang_lalu  = $row->thn_m1;
		}


		$sqlkas_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqlkas_lalu);
		foreach ($hasil->result() as $row) {
			$rk_ppkd_lalu  = $row->thn_m1;
		}

		$sqlskpd_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqlskpd_lalu);
		foreach ($hasil->result() as $row) {
			$rk_skpd_lalu  = $row->thn_m1;
		}


		$sqllcr_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllcr_lalu);
		foreach ($hasil->result() as $row) {
			$lcrx_lalu  = $row->thn_m1;
		}

		$sqlast_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqlast_lalu);
		foreach ($hasil->result() as $row) {
			$astx_lalu  = $row->thn_m1;
		}

		$lcr_lalu		= $lcrx_lalu - $rk_skpd_lalu;
		$ast_lalu		= $astx_lalu - $rk_skpd_lalu;
		$eku_lalu 		= $sal_awal + $rk_ppkd_lalu - $rk_skpd_lalu;
		$eku_tang_lalu 	= $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu - $rk_skpd_lalu;

		$sqlutang = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher
      and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqlutang);
		foreach ($hasil->result() as $row) {
			$nilaiutang  = $row->thn_m1;
		}

		$sqlkas = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqlkas);
		foreach ($hasil->result() as $row) {
			$rk_ppkd  = $row->thn_m1;
		}

		$sqlskpd = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqlskpd);
		foreach ($hasil->result() as $row) {
			$rk_skpd  = $row->thn_m1;
		}

		$sqllcr = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqllcr);
		foreach ($hasil->result() as $row) {
			$lcrx = $row->thn_m1;
		}

		$sqlast = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
      and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqlast);
		foreach ($hasil->result() as $row) {
			$astx  = $row->thn_m1;
		}

		$lcr			= $lcrx - $rk_skpd;
		$ast			= $astx - $rk_skpd;
		$eku 			= $sal_akhir - $rk_ppkd + $rk_skpd;
		$eku_tang 		= $sal_akhir + $nilaiutang - $rk_ppkd + $rk_skpd;
		$rk_skpd		= $rk_skpd - $rk_ppkd;
		$rk_skpd_lalu	= $rk_skpd_lalu - $rk_ppkd_lalu;
		$rk_ppkd		= $rk_skpd;
		$rk_ppkd_lalu	= $rk_skpd_lalu;


		/*$lcr    = $lcrx-$rk_skpd;
      $ast    = $astx-$rk_skpd;
      $eku    = $sal_akhir + $rk_ppkd;          
      $eku_tang   = $sal_akhir + $nilaiutang + $rk_ppkd;*/


		if ($sal_akhir < 0) {
			$c = "(";
			$sal_akhir = $sal_akhir * -1;
			$d = ")";
		} else {
			$c = "";
			$sal_akhir;
			$d = "";
		}

		$sal_akhir1 = number_format($sal_akhir, "2", ",", ".");

		if ($sal_awal < 0) {
			$c1 = "(";
			$sal_awal = $sal_awal * -1;
			$d1 = ")";
		} else {
			$c1 = "";
			$sal_awal;
			$d1 = "";
		}

		$sal_awal1 = number_format($sal_awal, "2", ",", ".");


		if ($eku_lalu < 0) {
			$min001 = "(";
			$eku_lalu = $eku_lalu * -1;
			$min002 = ")";
		} else {
			$min001 = "";
			$eku_lalu;
			$min002 = "";
		}

		$eku_lalu1 = number_format($eku_lalu, "2", ",", ".");

		if ($eku < 0) {
			$min003 = "(";
			$eku = $eku * -1;
			$min004 = ")";
		} else {
			$min003 = "";
			$eku;
			$min004 = "";
		}

		$eku1 = number_format($eku, "2", ",", ".");

		if ($eku_tang_lalu < 0) {
			$min005 = "(";
			$eku_tang_lalu = $eku_tang_lalu * -1;
			$min006 = ")";
		} else {
			$min005 = "";
			$eku_tang_lalu;
			$min006 = "";
		}

		$eku_tang_lalu1 = number_format($eku_tang_lalu, "2", ",", ".");

		if ($eku_tang < 0) {
			$min007 = "(";
			$eku_tang = $eku_tang * -1;
			$min008 = ")";
		} else {
			$min007 = "";
			$eku_tang;
			$min008 = "";
		}

		$eku_tang1 = number_format($eku_tang, "2", ",", ".");

		if ($rk_ppkd_lalu < 0) {
			$min009 = "(";
			$rk_ppkd_lalu = $rk_ppkd_lalu * -1;
			$min010 = ")";
		} else {
			$min009 = "";
			$rk_ppkd_lalu;
			$min010 = "";
		}

		$rk_ppkd_lalu1 = number_format($rk_ppkd_lalu, "2", ",", ".");

		if ($rk_ppkd < 0) {
			$min013 = "(";
			$rk_ppkd = $rk_ppkd * -1;
			$min014 = ")";
		} else {
			$min013 = "";
			$rk_ppkd;
			$min014 = "";
		}

		$rk_ppkd1 = number_format($rk_ppkd, "2", ",", ".");

		if ($lcr < 0) {
			$min015 = "(";
			$lcr = $lcr * -1;
			$min016 = ")";
		} else {
			$min015 = "";
			$lcr;
			$min016 = "";
		}

		$lcr1 = number_format($lcr, "2", ",", ".");

		if ($lcr_lalu < 0) {
			$min017 = "(";
			$lcr_lalu = $lcr_lalu * -1;
			$min018 = ")";
		} else {
			$min017 = "";
			$lcr_lalu;
			$min018 = "";
		}

		$lcr_lalu1 = number_format($lcr_lalu, "2", ",", ".");

		if ($ast < 0) {
			$min019 = "(";
			$ast = $ast * -1;
			$min020 = ")";
		} else {
			$min019 = "";
			$ast;
			$min020 = "";
		}

		$ast1 = number_format($ast, "2", ",", ".");

		if ($ast_lalu < 0) {
			$min021 = "(";
			$ast_lalu = $ast_lalu * -1;
			$min022 = ")";
		} else {
			$min021 = "";
			$ast_lalu;
			$min022 = "";
		}

		$ast_lalu1 = number_format($ast_lalu, "2", ",", ".");

		if ($rk_skpd_lalu < 0) {
			$min031 = "(";
			$rk_skpd_lalu = $rk_skpd_lalu * -1;
			$min032 = ")";
		} else {
			$min031 = "";
			$rk_skpd_lalu;
			$min032 = "";
		}

		$rk_skpd_lalu1 = number_format($rk_skpd_lalu, "2", ",", ".");

		if ($rk_skpd < 0) {
			$min033 = "(";
			$rk_skpd = $rk_skpd * -1;
			$min034 = ")";
		} else {
			$min033 = "";
			$rk_skpd;
			$min034 = "";
		}

		$rk_skpd1 = number_format($rk_skpd, "2", ",", ".");

		$queryneraca = " SELECT kode, uraian, bold, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
										isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
										isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
										isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
										FROM map_neraca_permen_77_obyek ORDER BY seq ";

		$query10 = $this->db->query($queryneraca);

		$no     = 0;

		foreach ($query10->result_array() as $res) {
			$uraian = $res['uraian'];
			$normal = $res['normal'];
			$bold = $res['bold'];
			$kode_1 = trim($res['kode_1']);
			$kode_2 = trim($res['kode_2']);
			$kode_3 = trim($res['kode_3']);
			$kode_4 = trim($res['kode_4']);
			$kode_5 = trim($res['kode_5']);
			$kode_6 = trim($res['kode_6']);
			$kode_7 = trim($res['kode_7']);
			$kode_8 = trim($res['kode_8']);
			$kode_9 = trim($res['kode_9']);
			$kode_10 = trim($res['kode_10']);
			$kode_11 = trim($res['kode_11']);
			$kode_12 = trim($res['kode_12']);
			$kode_13 = trim($res['kode_13']);
			$kode_14 = trim($res['kode_14']);
			$kode_15 = trim($res['kode_15']);


			$q = $this->db->query("  SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
                  and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and
                    (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                    kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                    kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                    kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                    kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                    kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                    kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                    kd_rek6 like '$kode_15%') ");

			foreach ($q->result_array() as $r) {
				$debet = $r['debet'];
				$kredit = $r['kredit'];
			}

			if ($debet == '') $debet = 0;
			if ($kredit == '') $kredit = 0;

			if ($normal == 1) {
				$nl = $debet - $kredit;
			} else {
				$nl = $kredit - $debet;
			}
			if ($nl == '') $nl = 0;

			// Jurnal Tahun lalu
			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
                  and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and
                    (kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
                    kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
                    kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
                    kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
                    kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
                    kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
                    kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
                    kd_rek6 like '$kode_15%') ");

			foreach ($q->result_array() as $rx) {
				$debet_lalu = $rx['debet'];
				$kredit_lalu = $rx['kredit'];
			}

			if ($debet_lalu == '') $debet_lalu = 0;
			if ($kredit_lalu == '') $kredit_lalu = 0;

			if ($normal == 1) {
				$sblm = $debet_lalu - $kredit_lalu;
			} else {
				$sblm = $kredit_lalu - $debet_lalu;
			}
			if ($sblm == '') $sblm = 0;

			if ($nl < 0) {
				$nl001 = "(";
				$nl = $nl * -1;
				$ln001 = ")";
			} else {
				$nl001 = "";
				$ln001 = "";
			}
			if ($sblm < 0) {
				$sblm001 = "(";
				$sblm = $sblm * -1;
				$mlbs001 = ")";
			} else {
				$sblm001 = "";
				$mlbs001 = "";
			}
			$nl1 = number_format($nl, "2", ",", ".");
			$sblm1 = number_format($sblm, "2", ",", ".");

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
				case 34:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td colspan =\"4\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min013$rk_ppkd1$min014</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min009$rk_ppkd_lalu1$min010</td>
                                 </tr>";
					break;
				case 14:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td colspan =\"4\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"55%\">$uraian</td>
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min033$rk_skpd1$min034</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min031$rk_skpd_lalu1$min032</td>
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
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min033$rk_skpd1$min034</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min031$rk_skpd_lalu1$min032</td>
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
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min015$lcr1$min016</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min017$lcr_lalu1$min018</td>
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
									 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min019$ast1$min020</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min021$ast_lalu1$min022</td>
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


		$cRet .= '</table>';

		$data['prev'] = $cRet;
		$data['sikap'] = 'preview';
		$judul = ("NERACA KONSOL OBYEK $cbulan");
		$this->template->set('title', 'NERACA KONSOL OBYEK $cbulan');
		switch ($cetak) {
			case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
			case 1;
				echo "<title>NERACA KONSOL OBYEK $cbulan</title>";
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



	function rpt_neraca_pemda_obyek_aset_tetap_unit($cbulan = "", $kd_skpd = "", $cetak = 1)
	{
		//$bulan   = $_REQUEST['tgl1'];
		$id     = $this->session->userdata('kdskpd');
		$thn_ang  = $this->session->userdata('pcThang');
		$thn_ang_1  = $thn_ang - 1;
		$bulan   = $cbulan;
		$cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;

		$sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd' ";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {

			$nmskpd  = $rowsc->nm_skpd;
		}

		$nm_skpd  = strtoupper($nmskpd);

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
		$modtahun = $thn_ang % 4;

		if ($modtahun = 0) {
			$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		} else {
			$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		$cRet = '';

		$cRet = "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
							
				  <TR>
					<td align=\"center\"><strong>REKAP DAFTAR BARANG</strong></td>
				  </TR>
				  <TR>
					<td align=\"center\"><strong>$nm_skpd</strong></td>
				</TR>
				  <tr>
								 <td align=\"center\"><strong>PEMERINTAH PROVINSI KALIMANTAN BARAT</strong></td>                         
							</tr>
							
							<TR>
								<td align=\"center\"><strong>PER $arraybulan[$bulan] $thn_ang DAN $thn_ang_1 </strong></td>
							</TR>
							</TABLE><br>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
							 <thead>                       
								<tr>
					  <td bgcolor=\"#CCCCCC\" width=\"3%\" align=\"center\" rowspan=\"2\"><b>NO</b></td>
					  <td bgcolor=\"#CCCCCC\" width=\"3%\" align=\"center\" rowspan=\"2\"><b>REKENING</b></td>
									<td colspan =\"7\"bgcolor=\"#CCCCCC\" width=\"28%\" align=\"center\" rowspan=\"2\"><b>URAIAN</b></td>
									<td style=\"border-top: none;\" colspan=\"2\" bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\">Harga Perolehan</td>
									<td style=\"border-top: none;\"bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"rowspan=\"2\">Akumulasi Penyusutan Sebelumnya</td>
								  <td style=\"border-top: none;\"bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"rowspan=\"2\">Beban Penyusutan</td>
								  <td style=\"border-top: none;\"bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"rowspan=\"2\">Akumulasi Penyusutan</td>
								  <td style=\"border-top: none;\"bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"rowspan=\"2\">Nilai Buku</td>                        
								</tr>
		
								<tr>
								  
								  <td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>$thn_ang</b></td>
									<td bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>$thn_ang_1</b></td> 
								</tr>
								
							 </thead>
							 <tfoot>
								<tr>
					  <td style=\"border-top: none;\"></td>
					  <td style=\"border-top: none;\"></td>
									<td colspan =\"7\"style=\"border-top: none;\"></td>
									<td style=\"border-top: none;\"></td>
									<td style=\"border-top: none;\"></td> 
									<td style=\"border-top: none;\"></td>
									<td style=\"border-top: none;\"></td> 
									<td style=\"border-top: none;\"></td>
									<td style=\"border-top: none;\"></td>                                             
								 </tr>
							 </tfoot>
						   
							 <tr> 
							 <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"3%\" align=\"center\">&nbsp;</td>
							 <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"3%\" align=\"center\">&nbsp;</td>
					  <td colspan =\"7\"style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\" align=\"center\">&nbsp;</td>                            
									<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"center\">&nbsp;</td>
									<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>
									<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>
									<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>
									<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>
								   
								</tr>";




		$queryneraca = " SELECT kode, uraian, bold, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
							isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
							isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
							isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
							FROM map_neraca_aset_tetap ORDER BY seq  ";

		$query10 = $this->db->query($queryneraca);

		$no     = 0;

		foreach ($query10->result_array() as $res) {
			$uraian = $res['uraian'];
			$normal = $res['normal'];
			$bold = $res['bold'];
			$kode_1 = trim($res['kode_1']);
			$kode_2 = trim($res['kode_2']);
			$kode_3 = trim($res['kode_3']);
			$kode_4 = trim($res['kode_4']);
			$kode_5 = trim($res['kode_5']);
			$kode_6 = trim($res['kode_6']);
			$kode_7 = trim($res['kode_7']);
			$kode_8 = trim($res['kode_8']);
			$kode_9 = trim($res['kode_9']);
			$kode_10 = trim($res['kode_10']);
			$kode_11 = trim($res['kode_11']);
			$kode_12 = trim($res['kode_12']);
			$kode_13 = trim($res['kode_13']);
			$kode_14 = trim($res['kode_14']);
			$kode_15 = trim($res['kode_15']);


			//nilai 2021 
			$q = $this->db->query("  SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
						  and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd' and 
							(kd_rek6 like '$kode_1%' 
							--or kd_rek6 like '$kode_2%'  or kd_rek6 like '$kode_3%' or 
							--kd_rek6 like '$kode_4%'  or 
							--kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
							--kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
						   -- kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
							--kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
							--kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
							--kd_rek6 like '$kode_15%'
						) ");

			foreach ($q->result_array() as $r) {
				$debet = $r['debet'];
				$kredit = $r['kredit'];
			}

			if ($debet == '') $debet = 0;
			if ($kredit == '') $kredit = 0;

			if ($normal == 1) {
				$nl = $debet - $kredit;
			} else {
				$nl = $kredit - $debet;
			}
			if ($nl == '') $nl = 0;

			// nilai 2020
			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
						  and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd' and
							(kd_rek6 like '$kode_1%' 
							--or kd_rek6 like '$kode_2%'  or 
							--kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
							--kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
							--kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
							--kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
							--kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
							--kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
							--kd_rek6 like '$kode_15%'
						) ");

			foreach ($q->result_array() as $rx) {
				$debet_lalu = $rx['debet'];
				$kredit_lalu = $rx['kredit'];
			}

			if ($debet_lalu == '') $debet_lalu = 0;
			if ($kredit_lalu == '') $kredit_lalu = 0;

			if ($normal == 1) {
				$sblm = $debet_lalu - $kredit_lalu;
			} else {
				$sblm = $kredit_lalu - $debet_lalu;
			}
			if ($sblm == '') $sblm = 0;


			//nilai akum sebelunya
			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
						  and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd' and 
							(
							kd_rek6 like '$kode_2%'   
						) ");

			foreach ($q->result_array() as $rx) {
				$debetakum_lalu = $rx['debet'];
				$kreditakum_lalu = $rx['kredit'];
			}

			if ($debetakum_lalu == '') $debetakum_lalu = 0;
			if ($kreditakum_lalu == '') $kreditakum_lalu = 0;

			if ($normal == 1) {
				$akumsblm = $debetakum_lalu - $kreditakum_lalu;
			} else {
				$akumsblm = $kreditakum_lalu - $debetakum_lalu;
			}
			if ($akumsblm == '') $akumsblm = 0;


			//nilai beban penyusutan  
			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
						  and b.kd_unit=a.kd_skpd where year(tgl_voucher)=$thn_ang and kd_skpd='$kd_skpd' and
							(
							kd_rek6 like '$kode_3%'   
						) ");

			foreach ($q->result_array() as $r) {
				$debet = $r['debet'];
				$kredit = $r['kredit'];
			}

			if ($debet == '') $debet = 0;
			if ($kredit == '') $kredit = 0;

			if ($normal == 1) {
				$bebanpenyu = $debet - $kredit;
			} else {
				$bebanpenyu = $kredit - $debet;
			}
			if ($bebanpenyu == '') $bebanpenyu = 0;



			//nilai akum 
			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
						  and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd' and
							(
							kd_rek6 like '$kode_2%'   
						) ");

			foreach ($q->result_array() as $rx) {
				$debetakum = $rx['debet'];
				$kreditakum = $rx['kredit'];
			}

			if ($debetakum == '') $debetakum = 0;
			if ($kreditakum == '') $kreditakum = 0;

			if ($normal == 1) {
				$akum = $debetakum - $kreditakum;
			} else {
				$akum = $kreditakum - $debetakum;
			}
			if ($akum == '') $akum = 0;



			//untuk memberikan tanda kurung di nilai minus
			if ($nl < 0) {
				$nl001 = "(";
				$nl = $nl * -1;
				$ln001 = ")";
			} else {
				$nl001 = "";
				$ln001 = "";
			}
			if ($sblm < 0) {
				$sblm001 = "(";
				$sblm = $sblm * -1;
				$mlbs001 = ")";
			} else {
				$sblm001 = "";
				$mlbs001 = "";
			}
			if ($akumsblm < 0) {
				$akumsblm001 = "(";
				$akumsblm = $akumsblm * -1;
				$mukamlbs001 = ")";
			} else {
				$akumsblm001 = "";
				$mukamlbs001 = "";
			}
			if ($bebanpenyu < 0) {
				$bebanpenyu001 = "(";
				$bebanpenyu = $bebanpenyu * -1;
				$unyepnabeb001 = ")";
			} else {
				$bebanpenyu001 = "";
				$unyepnabeb001 = "";
			}
			if ($akum < 0) {
				$akum001 = "(";
				$akum = $akum * -1;
				$muka001 = ")";
			} else {
				$akum001 = "";
				$muka001 = "";
			}



			$nl1 = number_format($nl, "2", ",", ".");
			$sblm1 = number_format($sblm, "2", ",", ".");
			$akumsblm1 = number_format($akumsblm, "2", ",", ".");
			$bebanpenyu1 = number_format($bebanpenyu, "2", ",", ".");
			$akum1 = number_format($akum, "2", ",", ".");

			$no       = $no + 1;

			switch ($res['bold']) {
				case 1:
					$cRet    .= "<tr>
								 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td> 
								 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"left\">" . $this->support->dotrek($kode_1) . "&nbsp;</td>                                     
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"1%\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td colspan =\"4\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"28%\">$uraian</td>
											   <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$akumsblm001$akumsblm1$mukamlbs001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$bebanpenyu001$bebanpenyu1$unyepnabeb001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$akum001$akum1$muka001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
		
										 </tr>";
					break;
				case 2:
					$cRet    .= "<tr>
								  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>
								  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"left\">" . $this->support->dotrek($kode_1) . "&nbsp;</td>                                     
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td colspan =\"4\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"28%\">&nbsp;&nbsp;&nbsp;&nbsp$uraian</td>
						   <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$akumsblm001$akumsblm1$mukamlbs001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$bebanpenyu001$bebanpenyu1$unyepnabeb001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$akum001$akum1$muka001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
										 </tr>";
					break;
				case 3:
					$cRet    .= "<tr>
								  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td> 
								  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"left\">" . $this->support->dotrek($kode_1) . "&nbsp;</td>                                     
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td colspan =\"4\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"28%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
						   <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$akumsblm001$akumsblm1$mukamlbs001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$bebanpenyu001$bebanpenyu1$unyepnabeb001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$akum001$akum1$muka001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
										 </tr>";
					break;
				case 4:
					$cRet    .= "<tr>
								 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td> 
								 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"left\">" . $this->support->dotrek($kode_1) . "&nbsp;</td>                                     
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"4%\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td colspan =\"4\"style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"28%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											  &nbsp;$uraian</td>
						   <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$akumsblm001$akumsblm1$mukamlbs001 </td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$bebanpenyu001$bebanpenyu1$unyepnabeb001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$akum001$akum1$muka001</td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
										 </tr>";
					break;

				case 10:
					$cRet    .= "<tr>
								 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>
								 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\"></td>                                     
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"2%\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"28%\">$uraian</td>
						   <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
											 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\"></td>
										 </tr>";
					break;
			}
		}


		$cRet .= '</table>';

		$data['prev'] = $cRet;
		$data['sikap'] = 'preview';
		$judul = ("NERACA ASET TETAP UNIT $cbulan");
		$this->template->set('title', 'NERACA ASET TETA UNIT $cbulan');
		switch ($cetak) {
			case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
			case 1;
				echo "<title>NERACA ASET TETAP UNIT $cbulan</title>";
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


	function cetakbb_kasda($dcetak = '', $ttd = '', $skpd = '', $rek5 = '', $dcetak2 = '', $print = '')
	{ //ANgoez
		$thn_ang = $this->session->userdata('pcThang');
		$ppkd = "5.02.0.00.0.00.02.0000";

		$cRet = '<TABLE style="border-collapse:collapse;font-family:Arial" width="100%">
					<TR>
						<TD align="center" ><B>BUKU BESAR </B></TD>
					</TR>
					</TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse;font-family:Arial"  width="100%">
					 <TR>
						<TD align="left" width="20%" >SKPD</TD>
						<TD align="left" width="80%" >: ' . $skpd . ' ' . $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="20%" >Rekening</TD>
						<TD align="left" width="80%" >: ' . $rek5 . ' ' . $this->tukd_model->get_nama($rek5, 'nm_rek6', 'ms_rek6', 'kd_rek6') . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="20%" >Periode</TD>
						<TD align="left" width="80%" >: ' . $this->tukd_model->tanggal_format_indonesia($dcetak) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($dcetak2) . '</TD>
					 </TR>
					 </TABLE>';

		$cRet .= '<TABLE style="border-collapse:collapse;font-family:Arial;font-size:12px" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
					 <THEAD>
					 <TR>
						<TD width="15%"  bgcolor="#CCCCCC" align="center" >TANGGAL</TD>
						<TD width="10%" bgcolor="#CCCCCC" align="center" >NO BUKTI</TD>
						<TD width="25%" bgcolor="#CCCCCC" align="center" >URAIAN</TD>
						<TD width="5%" bgcolor="#CCCCCC" align="center" >REF</TD>
						<TD width="15%" bgcolor="#CCCCCC" align="center" >DEBET</TD>
						<TD width="15%" bgcolor="#CCCCCC" align="center" >KREDIT</TD>
						<TD width="15%" bgcolor="#CCCCCC" align="center" >SALDO</TD>
					 </TR>
					 </THEAD>';
		$saldo_awal = 0;
		$cRet .= '<TR>
					<TD width="15%" align="left" ></TD>
					<TD width="10%" align="right" ></TD>
					<TD width="25%" align="left" >SALDO AWAL</TD>
					<TD width="5%" align="left" ></TD>
					<TD width="15%" align="right" ></TD>
					<TD width="15%" align="right" ></TD>
					<TD width="15%" align="right" >' . number_format($saldo_awal, "2", ",", ".") . '</TD>
					
				 </TR>';
		$jumlah = 0;
		//isi
		/* $sql = "select tgl_kas, no_kas,keterangan,0 as debet, kredit from (
					select a.kd_skpd, a.tgl_kas, a.no_kas, b.kd_rek5, keterangan+', '+(select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) keterangan,0 as debet, rupiah as kredit 
					from trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts AND a.no_kas=b.no_kas
					where pot_khusus<>3 AND b.kd_rek5 = '$rek5'
					union all 
					select '3.13.01.17' kd_skpd, a.tgl_kas, a.no_kas, '4141003' kd_rek5, keterangan+', '+(select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) keterangan,0 as debet, rupiah as kredit 
					from trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts and a.no_kas=b.no_kas
					where jns_trans IN ('5') AND jns_cp='1' AND pot_khusus='3' ) a 
					where kd_skpd='$skpd' and kd_rek5='$rek5' and tgl_kas between '$dcetak' AND '$dcetak2'
					UNION ALL
					select a.tgl_kas, a.no_kas, keterangan+', '+(select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) keterangan,0 as debet, rupiah as kredit 
					from trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts AND a.no_kas=b.no_kas
					where jns_trans='2' and b.kd_skpd='$skpd' and tgl_kas between '$dcetak' AND '$dcetak2'
					order by tgl_kas, no_kas"; */

		$sql = "select tgl_kas, no_kas,keterangan,0 as debet, kredit from (
					select a.kd_skpd, a.tgl_kas, a.no_kas, b.kd_rek6, keterangan+', '+(select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) keterangan,0 as debet, rupiah as kredit 
					from trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts AND a.no_kas=b.no_kas
					where pot_khusus<>3 AND jns_trans NOT IN ('2') AND b.kd_rek6 = '$rek5'
					union all 
					select '5.02.0.00.0.00.02.0000' kd_skpd, a.tgl_kas, a.no_kas, '410415030001' kd_rek6, keterangan+', '+(select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) keterangan,0 as debet, rupiah as kredit 
					from trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts and a.no_kas=b.no_kas
					where jns_trans IN ('5') AND jns_cp='1' AND pot_khusus='3' 
					union all 
					select '5.02.0.00.0.00.02.0000' kd_skpd, a.tgl_kas, a.no_kas, '4141009' kd_rek6, keterangan+', '+(select nm_skpd from ms_skpd where kd_skpd=a.kd_skpd) keterangan,0 as debet, rupiah as kredit 
					from trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.kd_skpd=b.kd_skpd AND a.no_sts=b.no_sts and a.no_kas=b.no_kas
					where jns_trans IN ('2')
					) a 
					where kd_skpd='$skpd' and kd_rek6='$rek5' and tgl_kas between '$dcetak' AND '$dcetak2'
					order by tgl_kas, no_kas";

		$hasil = $this->db->query($sql);
		foreach ($hasil->result() as $rows) {
			$tgl_kas    = $rows->tgl_kas;
			$no_kas     = $rows->no_kas;
			$keterangan = $rows->keterangan;
			$debet      = $rows->debet;
			$kredit     = $rows->kredit;
			$jumlah		= $jumlah + $kredit;
			$saldo_awal = $saldo_awal + $kredit;


			$cRet .= '<TR>
						<TD align="left" >' . $this->tukd_model->tanggal_format_indonesia($tgl_kas) . '</TD>
						<TD align="left" >' . $no_kas . '</TD>
						<TD align="left" >' . $keterangan . '</TD>
						<TD align="center" ></TD>
						<TD  align="right" >' . number_format($debet, "2", ",", ".") . '</TD>
						<TD  align="right" >' . number_format($kredit, "2", ",", ".") . '</TD>
						<TD  align="right" >' . number_format($saldo_awal, "2", ",", ".") . '</TD>
					 </TR>';
		}
		$cRet .= '<TR>
					<TD width="15%" align="left" ></TD>
					<TD width="10%" align="right" ></TD>
					<TD width="25%" align="left" >JUMLAH</TD>
					<TD width="5%" align="left" ></TD>
					<TD  align="right" >' . number_format($jumlah, "2", ",", ".") . '</TD>
					<TD  align="right" >' . number_format(0, "2", ",", ".") . '</TD>
					<TD  align="right" >' . number_format(0, "2", ",", ".") . '</TD>
					
				 </TR>';
		$cRet .= '</TABLE>';

		$jenis = '1';

		//$data['prev']= 'BUKU BESAR';
		if ($jenis == 1) {
			echo '<title> Buku Besar </title>';
			echo $cRet;
		}
		if ($jenis == 2) {
			$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
		}
	}




	function reg_spj()
	{
		$data['page_title'] = 'Register SPJ Pengeluaran';
		$this->template->set('title', 'Register SPJ Pengeluaran');
		$this->template->load('template', 'akuntansi/reg_spj', $data);
	}

	function reg_spj_terima()
	{
		$data['page_title'] = 'Register SPJ Penerimaan';
		$this->template->set('title', 'Register SPJ Penerimaan');
		$this->template->load('template', 'akuntansi/reg_spj_terima', $data);
	}

	function load_reg_spj($bulan = '')
	{
		$id  = $this->session->userdata('pcUser');
		$result = array();
		$row = array();
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page - 1) * $rows;
		$kriteria = '';
		$kriteria = $this->input->post('cari');
		$where = '';
		if ($kriteria <> '') {
			$where = "AND (upper(kd_skpd) like upper('%$kriteria%'))";
		}

		$sql = "SELECT count(*) as tot from trhspj_ppkd WHERE bulan='$bulan' $where";
		$query1 = $this->db->query($sql);
		$total = $query1->row();

		$sql = "SELECT * from trhspj_ppkd WHERE bulan='$bulan' AND kd_skpd IN (SELECT kd_skpd FROM user_akt WHERE user_id='$id') $where order by kd_skpd ";
		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {
			$row[] = array(
				'id' => $ii,
				'kd_skpd' => $resulte['kd_skpd'],
				'real_up' =>  number_format($resulte['real_up'], "2", ".", ","),
				'real_gj' =>  number_format($resulte['real_gj'], "2", ".", ","),
				'real_brg' => number_format($resulte['real_brg'], "2", ".", ","),
				'tgl_terima' => $resulte['tgl_terima'],
				'spj' => $resulte['spj'],
				'bku' => $resulte['bku'],
				'koran' => $resulte['koran'],
				//'status_sempurna' => $resulte['status_sempurna'],
				'pajak' => $resulte['pajak'],
				'sts' => $resulte['sts'],
				'ket' => $resulte['ket'],
				'cek' => $resulte['cek']
			);
			$ii++;
		}

		$result["total"] = $total->tot;
		$result["rows"] = $row;
		echo json_encode($result);
	}

	function load_reg_spj_terima($bulan = '')
	{
		$id  = $this->session->userdata('pcUser');
		$result = array();
		$row = array();
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page - 1) * $rows;
		$kriteria = '';
		$kriteria = $this->input->post('cari');
		$where = '';
		if ($kriteria <> '') {
			$where = "AND (upper(kd_skpd) like upper('%$kriteria%'))";
		}

		$sql = "SELECT count(*) as tot from trhspj_terima_ppkd WHERE bulan='$bulan' $where";
		$query1 = $this->db->query($sql);
		$total = $query1->row();

		$sql = "SELECT * from trhspj_terima_ppkd WHERE bulan='$bulan' AND kd_skpd IN (SELECT kd_skpd FROM user_akt WHERE user_id='$id') $where order by kd_skpd ";
		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {
			$row[] = array(
				'id' => $ii,
				'kd_skpd' => $resulte['kd_skpd'],
				'real_terima' =>  number_format($resulte['real_terima'], "2", ".", ","),
				'real_setor' =>  number_format($resulte['real_setor'], "2", ".", ","),
				'sisa' => number_format($resulte['sisa'], "2", ".", ","),
				'tgl_terima' => $resulte['tgl_terima'],
				'spj' => $resulte['spj'],
				'bku' => $resulte['bku'],
				'koran' => $resulte['koran'],
				//'status_sempurna' => $resulte['status_sempurna'],

				'sts' => $resulte['sts'],
				'ket' => $resulte['ket'],
				'cek' => $resulte['cek']
			);
			$ii++;
		}

		$result["total"] = $total->tot;
		$result["rows"] = $row;
		echo json_encode($result);
	}


	function simpan_pengesahan()
	{
		$kdskpd = $this->input->post('kdskpd');
		$tgl_terima = $this->input->post('tgl_terima');
		$real_gj = $this->input->post('real_gj');
		$real_up = $this->input->post('real_up');
		$real_brg = $this->input->post('real_brg');

		$spj = $this->input->post('spj');
		$bku = $this->input->post('bku');
		$koran = $this->input->post('koran');
		$pajak = $this->input->post('pajak');
		$sts = $this->input->post('sts');
		$ket = $this->input->post('ket');
		$cek = $this->input->post('cek');
		$bulan = $this->input->post('bulan');
		$usernm = $this->session->userdata('pcNama');
		$update = date('Y-m-d');
		$sql2 = "UPDATE trhspj_ppkd SET tgl_terima='$tgl_terima',real_up='$real_up',
		real_gj='$real_gj',real_brg='$real_brg',spj='$spj',bku='$bku',koran='$koran',pajak='$pajak',
		sts='$sts',ket='$ket',cek='$cek',username='$usernm',tgl_update='$update' WHERE kd_skpd='$kdskpd' AND bulan='$bulan'";
		$asg = $this->db->query($sql2);
	}

	function simpan_pengesahan_terima()
	{
		$kdskpd = $this->input->post('kdskpd');
		$tgl_terima = $this->input->post('tgl_terima');
		$real_terima = $this->input->post('real_terima');
		$real_setor = $this->input->post('real_setor');
		$sisa = $this->input->post('sisa');

		$spj = $this->input->post('spj');
		$bku = $this->input->post('bku');
		$koran = $this->input->post('koran');

		$sts = $this->input->post('sts');
		$ket = $this->input->post('ket');
		$cek = $this->input->post('cek');
		$bulan = $this->input->post('bulan');
		$usernm = $this->session->userdata('pcNama');
		$update = date('Y-m-d');
		$sql2 = "UPDATE trhspj_terima_ppkd SET tgl_terima='$tgl_terima',real_setor='$real_setor',
		real_terima='$real_terima',sisa='$sisa',spj='$spj',bku='$bku',koran='$koran',
		sts='$sts',ket='$ket',cek='$cek',username='$usernm',tgl_update='$update' WHERE kd_skpd='$kdskpd' AND bulan='$bulan'";
		$asg = $this->db->query($sql2);
	}

	function pilih_ttd_kd()
	{
		$lccr = $this->input->post('q');
		$sql = "SELECT nip,nama,jabatan,kd_skpd FROM ms_ttd where kode ='PPKD' ";
		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {

			$result[] = array(
				'id' => $ii,
				'nip' => $resulte['nip'],
				'nama' => $resulte['nama'],
				'jabatan' => $resulte['jabatan'],
				'kd_skpd' => $resulte['kd_skpd']
			);
			$ii++;
		}

		echo json_encode($result);
		$query1->free_result();
	}


	function ctk_register_spj($bulan = '', $ctk = '')
	{
		$cRet = "";
		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
           <tr>
                <td colspan=\"13\" align=\"center\" style=\"border: solid 1px white;\"><b>REGISTER SPJ<br>" . strtoupper($this->getBulan($bulan)) . " </b>
                </td>
            </tr>
			</table>";

		$cRet .= "<table style=\"border-collapse:collapse; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
            <thead>
			<tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"2%\" >No</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"20%\"  >Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >Tanggal Terima</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >UP/GU/TU</td>
				<td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >Gaji</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >LS</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\"  >SPJ</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\"  >BKU</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\"  >Rek. Koran</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\"  >BP. Pajak</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\"  >STS</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >Keterangan</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\"  >Check</td>
            </tr>
			
			</thead>
           ";


		$csql = "SELECT a.nm_skpd, b.kd_skpd
				,ISNULL(real_up,0) real_up
				,ISNULL(real_gj,0) real_gj
				,ISNULL(real_brg,0) real_brg
				,ISNULL(tgl_terima,'') tgl_terima
				,spj,bku,koran,pajak,sts,ket,cek
				 FROM ms_skpd a 
				LEFT JOIN trhspj_ppkd b ON a.kd_skpd=b.kd_skpd
				WHERE bulan='$bulan' ORDER BY kd_skpd
				";
		$no = 0;
		$query = $this->db->query($csql);
		foreach ($query->result_array() as $res) {
			$no = $no + 1;
			$kd_skpd = $res['kd_skpd'];
			$nm_skpd = $res['nm_skpd'];
			$real_up = $res['real_up'];
			$real_gj = $res['real_gj'];
			$real_brg = $res['real_brg'];
			$tgl_terima = $res['tgl_terima'];
			$ket = $res['ket'];
			/*$spj = $res['spj'];
			 $bku = $res['bku'];
			 $koran = $res['koran'];
			 $pajak = $res['pajak'];
			 $sts = $res['sts'];
			 $cek = $res['cek'];*/
			$tanggal = empty($tgl_terima) || $tgl_terima == '1900-01-01' ? '-' : $this->tukd_model->tanggal_ind($tgl_terima);
			$spj = $res['spj'] == '1' ? '&#10003;' : '';
			$bku = $res['bku'] == '1' ? '&#10003;' : '';
			$koran = $res['koran'] == '1' ? '&#10003;' : '';
			$pajak = $res['pajak'] == '1' ? '&#10003;' : '';
			$sts = $res['sts'] == '1' ? '&#10003;' : '';
			$cek = $res['cek'] == '1' ? '&#10003;' : '';

			$cRet .= "<tr>
							<td align='center' >$no</td>
							<td>$nm_skpd</td>
							<td align='center' >$tanggal</td>
							<td align='right' >" . number_format($real_up, "2", ",", ".") . "</td>
							<td align='right' >" . number_format($real_gj, "2", ",", ".") . "</td>
							<td align='right' >" . number_format($real_brg, "2", ",", ".") . "</td>
							<td align='center' >$spj</td>
							<td align='center' >$bku</td>
							<td align='center' >$koran</td>
							<td align='center' >$pajak</td>
							<td align='center' >$sts</td>
							<td>$ket</td>
							<td align='center' >$cek</td>
						</tr>";
		}

		$cRet .= " 
         </table>
         ";

		$data['prev'] = $cRet; //'JURNAL UMUM';
		$judul = 'Reg_SPJ';
		switch ($ctk) {
			case 1;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 2;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 3;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}


	function ctk_register_spj_terima($bulan = '', $ctk = '')
	{
		$cRet = "";
		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
           <tr>
                <td colspan=\"13\" align=\"center\" style=\"border: solid 1px white;\"><b>REGISTER SPJ<br>" . strtoupper($this->getBulan($bulan)) . " </b>
                </td>
            </tr>
			</table>";

		$cRet .= "<table style=\"border-collapse:collapse; font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
            <thead>
			<tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"2%\" >No</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"25%\"  >Uraian</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >Tanggal Terima</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >Realisasi Terima</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >Realisasi Setor</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >Sisa</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\"  >SPJ</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\"  >BKU</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\"  >Rek. Koran</td>
               
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"5%\"  >STS</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\"  >Keterangan</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\"  >Check</td>
            </tr>
			
			</thead>
           ";


		$csql = "SELECT a.nm_skpd, b.kd_skpd
				,ISNULL(real_terima,0) real_terima
				,ISNULL(real_setor,0) real_setor
				,ISNULL(sisa,0) sisa
				,ISNULL(tgl_terima,'') tgl_terima
				,spj,bku,koran,sts,ket,cek
				 FROM ms_skpd a 
				LEFT JOIN trhspj_terima_ppkd b ON a.kd_skpd=b.kd_skpd
				WHERE bulan='$bulan' ORDER BY kd_skpd
				";
		$no = 0;
		$query = $this->db->query($csql);
		foreach ($query->result_array() as $res) {
			$no = $no + 1;
			$kd_skpd = $res['kd_skpd'];
			$nm_skpd = $res['nm_skpd'];
			$real_terima = $res['real_terima'];
			$real_setor = $res['real_setor'];
			$sisa = $res['sisa'];
			$tgl_terima = $res['tgl_terima'];
			$ket = $res['ket'];
			/*$spj = $res['spj'];
			 $bku = $res['bku'];
			 $koran = $res['koran'];
			 $pajak = $res['pajak'];
			 $sts = $res['sts'];
			 $cek = $res['cek'];*/
			$tanggal = empty($tgl_terima) || $tgl_terima == '1900-01-01' ? '-' : $this->tukd_model->tanggal_ind($tgl_terima);
			$spj = $res['spj'] == '1' ? '&#10003;' : '';
			$bku = $res['bku'] == '1' ? '&#10003;' : '';
			$koran = $res['koran'] == '1' ? '&#10003;' : '';
			//$pajak =$res['pajak']=='1' ? '&#10003;' : '';
			$sts = $res['sts'] == '1' ? '&#10003;' : '';
			$cek = $res['cek'] == '1' ? '&#10003;' : '';

			$cRet .= "<tr>
							<td align='center' >$no</td>
							<td>$nm_skpd</td>
							<td align='center' >$tanggal</td>
							<td align='right' >" . number_format($real_terima, "2", ",", ".") . "</td>
							<td align='right' >" . number_format($real_setor, "2", ",", ".") . "</td>
							<td align='right' >" . number_format($sisa, "2", ",", ".") . "</td>
							<td align='center' >$spj</td>
							<td align='center' >$bku</td>
							<td align='center' >$koran</td>
							
							<td align='center' >$sts</td>
							<td>$ket</td>
							<td align='center' >$cek</td>
						</tr>";
		}

		$cRet .= " 
         </table>
         ";

		$data['prev'] = $cRet; //'JURNAL UMUM';
		$judul = 'Reg_SPJ_terima';
		switch ($ctk) {
			case 1;
				echo ("<title>$judul</title>");
				echo $cRet;
				break;
			case 2;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
				break;
			case 3;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename= $judul.xls");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
		}
	}

	function list_org()
	{
		$lccr = $this->input->post('q');
		$sql = "SELECT kd_org,nm_org FROM ms_organisasi where upper(kd_org) like upper('%$lccr%') or upper(nm_org) like upper('%$lccr%') order by kd_org ";
		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {

			$result[] = array(
				'id' => $ii,
				'kd_org' => $resulte['kd_org'],
				'nm_org' => $resulte['nm_org']
			);
			$ii++;
		}

		echo json_encode($result);
		$query1->free_result();
	}

	function list_skpd()
	{
		$lccr = $this->input->post('q');
		$sql = "select kd_skpd,nm_skpd from ms_skpd where upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%') order by kd_skpd";
		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {

			$result[] = array(
				'id' => $ii,
				'kd_skpd' => $resulte['kd_skpd'],
				'nm_skpd' => $resulte['nm_skpd']
			);
			$ii++;
		}

		echo json_encode($result);
		$query1->free_result();
	}


	function rekening()
	{
		$lccr = $this->input->post('q');
		$cskpd = $this->input->post('kd');
		if($cskpd!=''){
		$data ="kd_skpd='$cskpd' and ";
		}else{
			$data ="";
		}
		
		//        $sql = " SELECT kd_rek5,nm_rek5 FROM ms_rek5 where kd_rek5 like '$lccr%' limit 20";
		$sql = " SELECT DISTINCT isnull(kd_rek6,'') kd_rek6 , isnull(nm_rek6,'') nm_rek6 FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd 
				 where (upper(kd_rek6) like upper('%$lccr%') or upper(nm_rek6) like upper('%$lccr%')) group by kd_rek6,nm_rek6 order by kd_rek6";

		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {

			$result[] = array(
				'id' => $ii,
				'kd_rek6' => $resulte['kd_rek6'],
				'nm_rek6' => $resulte['nm_rek6'],
			);
			$ii++;
		}

		echo json_encode($result);
	}

	function cetakbb($dcetak = '', $skpd = '', $rek6 = '', $dcetak2 = '', $cetak_bb='')
	{ //ANgoez
		// echo ($skpd);
		// return;
		$print = $this->uri->segment(7);
		if ($skpd == '-') {
			$where1 = '';
			$where2 = '';
		} else if ($skpd != '') {
			$where1 = "AND b.kd_skpd='$skpd'";
			$where2 = "AND kd_skpd='$skpd'";
		}
		$thn_ang = $this->session->userdata('pcThang');
		$cRet = '<TABLE width="100%">
					<TR>
						<TD align="center" ><B>BUKU BESAR </B></TD>
					</TR>
					</TABLE>';
		if ($skpd == '-') {
			$cRet .= '<TABLE width="100%">
					 <TR>
						<TD align="left" width="20%" >SKPD</TD>
						<TD align="left" width="80%" >: SELURUH SKPD</TD>
					 </TR>
					 <TR>
						<TD align="left" width="20%" >Rekening</TD>
						<TD align="left" width="80%" >: ' . $rek6 . ' ' . $this->tukd_model->get_nama($rek6, 'nm_rek6', 'ms_rek6', 'kd_rek6') . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="20%" >Periode</TD>
						<TD align="left" width="80%" >: ' . $this->tukd_model->tanggal_format_indonesia($dcetak) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($dcetak2) . '</TD>
					 </TR>
					 </TABLE>';

					 $cRet .= '<TABLE style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
								<THEAD>
									<TR>
									<TD width="5%"  bgcolor="#CCCCCC" align="center" >TANGGAL</TD>
									<TD width="20%" bgcolor="#CCCCCC" align="center" >URAIAN</TD>
									<TD width="20%" bgcolor="#CCCCCC" align="center" >SKPD</TD>
									<TD width="5%" bgcolor="#CCCCCC" align="center" >REF</TD>
									<TD width="15%" bgcolor="#CCCCCC" align="center" >DEBET</TD>
									<TD width="15%" bgcolor="#CCCCCC" align="center" >KREDIT</TD>
									<TD width="25%" bgcolor="#CCCCCC" align="center" >SALDO</TD>
									</TR>
								</THEAD>';

								if ((substr($rek6, 0, 1) == '9') or (substr($rek6, 0, 1) == '8') or (substr($rek6, 0, 1) == '4') or (substr($rek6, 0, 1) == '5') or (substr($rek6, 0, 1) == '7')) {
									$csql3 = "SELECT sum(a.debet) as debet,sum(a.kredit) as kredit, a.kd_unit, b.nm_skpd FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' AND b.tgl_voucher < '$dcetak' AND YEAR(b.tgl_voucher)='$thn_ang' $where1 GROUP BY a.kd_unit, b.nm_skpd";
								} else if ($rek6 == '310101010001') {
									$csql3 = "SELECT sum(a.debet) as debet,sum(a.kredit) as kredit, a.kd_unit,a.nm_skpd from (
											
											select sum(debet) debet, sum(kredit) kredit,a.kd_unit, b.nm_skpd 
											from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
											where kd_rek6='310101010001' and reev='0' and tgl_voucher < '$dcetak'
											GROUP BY a.kd_unit, b.nm_skpd 
											union all
											select sum(debet) debet, sum(kredit) kredit,a.kd_unit, b.nm_skpd  
											from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
											where kd_rek6='310101010001' and reev not in ('0') and tgl_voucher < '$dcetak' $where2
											GROUP BY a.kd_unit, b.nm_skpd
											) a GROUP BY a.kd_unit,a.nm_skpd";
								} else if ($rek6 == '310102010001') {
									$csql3 = "SELECT sum(a.debet) as debet,sum(a.kredit) as kredit, a.kd_unit,a.nm_skpd from (
											select sum(debet) debet, sum(kredit) kredit ,a.kd_unit,a.nm_skpd
											from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
											where left(kd_rek6,1) in ('7','8') and tgl_voucher < '$dcetak' $where2
											GROUP BY a.kd_unit, b.nm_skpd
											) a 
											GROUP BY a.kd_unit, a.nm_skpd";
								} else {
									$csql3 = "SELECT sum(a.debet) as debet,sum(a.kredit) as kredit, a.kd_unit,b.nm_skpd FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' and b.tgl_voucher < '$dcetak' $where1
									GROUP BY a.kd_unit, b.nm_skpd";
								}
						
								$hasil = $this->db->query($csql3);
								$trh4 = $hasil->row();
								$awaldebet = $trh4->debet;
								$awalkredit = $trh4->kredit;
								if ((substr($rek6, 0, 1) == '8') or (substr($rek6, 0, 1) == '5') or (substr($rek6, 0, 2) == '62') or (substr($rek6, 0, 2) == '62') or (substr($rek6, 0, 1) == '1')) {
									$saldo = $awaldebet - $awalkredit;
								} else {
									$saldo = $awalkredit - $awaldebet;
								}
								if ($saldo < 0) {
									$a = '(';
									$saldox = $saldo * -1;
									$b = ')';
								} else {
									$a = '';
									$saldox = $saldo;
									$b = '';
								}
								

					$cRet .= '<TR>
								<TD width="15%" align="left" ></TD>
								<TD width="25%" align="left" >saldo awal</TD>
								<TD width="5%" align="left" ></TD>
								<TD width="5%" align="right" ></TD>
								<TD width="10%" align="right" ></TD>
								<TD width="15%" align="right" ></TD>
								<TD width="15%" align="right" >' . $a . '' . number_format($saldox, "2", ",", ".") . '' . $b . '</TD>
							 </TR>';
							 
							$idx = 1;
							if ($rek6 == '310101010001') {
								$query = $this->db->query("SELECT kd_rek6, debet, kredit, tgl_voucher, ket, no_voucher,a.kd_unit,a.nm_skpd FROM (
															   SELECT a.kd_rek6,a.debet,a.kredit,b.tgl_voucher,b.ket,b.no_voucher,a.kd_unit,b.nm_skpd FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='310101010001' AND b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2' $where1 
															   ) a
															   ORDER BY tgl_voucher, debet-kredit");
							} else if ($rek6 == '310102010001') {
								$query = $this->db->query("SELECT kd_rek6, debet, kredit, tgl_voucher, ket, no_voucher FROM (
															   
															   SELECT '310102010001' kd_rek6, SUM(a.debet) debet, SUM(a.kredit) kredit, b.tgl_voucher, 'SURPLUS/DEFISIT LO ('+b.ket+' )' ket, 'SURPLUS/DEFISIT LO - '+b.no_voucher as no_voucher,a.kd_unit,b.nm_skpd FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE LEFT(a.kd_rek6,1) IN ('7','8') AND b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2' $where1
															   GROUP BY b.tgl_voucher, b.no_voucher, b.ket,a.kd_unit,b.nm_skpd) a
															   ORDER BY tgl_voucher, debet-kredit");
							} else {
								$query = $this->db->query("SELECT a.kd_rek6,a.debet,a.kredit,b.tgl_voucher,b.ket,b.no_voucher,a.kd_unit,b.nm_skpd FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' AND b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2' $where1  ORDER BY b.tgl_voucher, 
															   case when left('$rek6',1) in (1,5,6,9) then kredit-debet else debet-kredit end");
								//$query = $this->db->query("SELECT a.kd_rek6,a.debet,a.kredit,b.tgl_voucher,b.ket,b.no_voucher FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher WHERE a.kd_rek6='$rek6' AND b.kd_skpd='$skpd' and b.tgl_voucher>='$dcetak' and b.tgl_voucher<='$dcetak2' and a.pos='1' ORDER by b.tgl_voucher, convert(b.no_voucher,unsigned)");  
							}
					
							if ($query->num_rows() > 0) {
								$jdebet = 0;
								$jkredit = 0;
								foreach ($query->result_array() as $res) {
					
									$tgl_voucher = $res['tgl_voucher'];
									$ket = $res['ket'];
									$ref = $res['no_voucher'];
									$sskpd = $res['kd_unit'];
									$snmskpd = $res['nm_skpd'];
									$debet = $res['debet'];
									$kredit = $res['kredit'];
									$idx++;
					
									
									if ($debet < 0) {
										$debet1 = $debet * -1;
										$c = '(';
										$d = ')';
									} else {
										$c = '';
										$d = '';
										$debet1 = $debet;
									}
									if ($kredit < 0) {
										$kredit1 = $kredit * -1;
										$e = '(';
										$f = ')';
									} else {
										$e = '';
										$f = '';
										$kredit1 = $kredit;
									}
									$saldo = $saldo;
									if ((substr($rek6, 0, 1) == '8') or (substr($rek6, 0, 1) == '5') or (substr($rek6, 0, 2) == '62') or (substr($rek6, 0, 2) == '62') or (substr($rek6, 0, 1) == '1')) {
										$saldo = $saldo + $debet - $kredit;
									} else {
										$saldo = $saldo + $kredit - $debet;
									}
									if ($saldo < 0) {
										$saldo1 = $saldo * -1;
										$i = '(';
										$j = ')';
									} else {
										$saldo1 = $saldo;
										$i = '';
										$j = '';
									}
									$cRet .= '<TR>
													<TD width="15%" align="left" >' . $this->tukd_model->tanggal_format_indonesia($tgl_voucher) . '</TD>
													<TD width="45%" align="left" >' . $ket . '</TD>
													<TD width="35%" align="left" >'. $sskpd .'-'.$snmskpd.' </TD>
													<TD width="5%" align="left" >' . $ref . '</TD>
													<TD width="15%" align="right" >' . $c . '' . number_format($debet1, "2", ",", ".") . '' . $d . '</TD>
													<TD width="15%" align="right" >' . $e . '' . number_format($kredit1, "2", ",", ".") . '' . $f . '</TD>
													<TD width="15%" align="right" >' . $i . '' . number_format($saldo1, "2", ",", ".") . '' . $j . '</TD>
												 </TR>';
					
									$jdebet = $jdebet + $debet;
									$jkredit = $jkredit + $kredit;
								}
								if ($jdebet < 0) {
									$jdebet1 = $jdebet * -1;
									$k = '(';
									$l = ')';
								} else {
									$jdebet1 = $jdebet;
									$k = '';
									$l = '';
								}
								if ($jkredit < 0) {
									$jkredit1 = $jkredit * -1;
									$m = '(';
									$n = ')';
								} else {
									$jkredit1 = $jkredit;
									$m = '';
									$n = '';
								}
					
								$cRet .= '<TR>
										<TD width="5%" align="left" ></TD>
										<TD width="15%" align="left" >JUMLAH</TD>
										<TD width="5%" align="left" ></TD>
										<TD width="5%" align="left" ></TD>
										<TD width="20%" align="right" >' . $k . '' . number_format($jdebet1, "2", ",", ".") . '' . $l . '</TD>
										<TD width="20%" align="right" >' . $m . '' . number_format($jkredit1, "2", ",", ".") . '' . $n . '</TD>
										<TD width="20%" align="right" >' . $i . '' . number_format($saldo1, "2", ",", ".") . '' . $j . '</TD>
									 </TR>';
								$cRet .= '</TABLE>';
							} else {
					
								$cRet .= '</TABLE>';
							}
							
							$data['prev'] = $cRet;
							$judul = 'Buku Besar';
							if($print==0) {
								echo ("<title>$judul</title>");
								echo $cRet;
							}else if($print==1){
								$this->support->_mpdf('', $cRet, 10, 5, 10, '0');
							}else{
									header("Cache-Control: no-cache, no-store, must-revalidate");
									header("Content-Type: application/vnd.ms-excel");
									header("Content-Disposition: attachment; filename=$judul.xls");
									//$this->load->view('akuntansi/bukubesar', $data);
									echo $cRet;

							}

		} else if ($skpd != '') {
			$cRet .= '<TABLE width="100%">
					 <TR>
						<TD align="left" width="20%" >SKPD</TD>
						<TD align="left" width="80%" >: ' . $skpd . ' ' . $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="20%" >Rekening</TD>
						<TD align="left" width="80%" >: ' . $rek6 . ' ' . $this->tukd_model->get_nama($rek6, 'nm_rek6', 'ms_rek6', 'kd_rek6') . '</TD>
					 </TR>
					 <TR>
						<TD align="left" width="20%" >Periode</TD>
						<TD align="left" width="80%" >: ' . $this->tukd_model->tanggal_format_indonesia($dcetak) . ' s/d ' . $this->tukd_model->tanggal_format_indonesia($dcetak2) . '</TD>
					 </TR>
					 </TABLE>';

					 $cRet .= '<TABLE style="border-collapse:collapse;" width="100%" align="center" border="1" cellspacing="0" cellpadding="4">
					 <THEAD>
					 <TR>
						<TD width="10%"  bgcolor="#CCCCCC" align="center" >TANGGAL</TD>
						<TD width="20%" bgcolor="#CCCCCC" align="center" >URAIAN</TD>
						<TD width="10%" bgcolor="#CCCCCC" align="center" >REF</TD>
						<TD width="15%" bgcolor="#CCCCCC" align="center" >DEBET</TD>
						<TD width="15%" bgcolor="#CCCCCC" align="center" >KREDIT</TD>
						<TD width="35%" bgcolor="#CCCCCC" align="center" >SALDO</TD>
					 </TR>
					 </THEAD>';

		if ((substr($rek6, 0, 1) == '9') or (substr($rek6, 0, 1) == '8') or (substr($rek6, 0, 1) == '4') or (substr($rek6, 0, 1) == '5') or (substr($rek6, 0, 1) == '7')) {
			$csql3 = "SELECT sum(a.debet) as debet,sum(a.kredit) as kredit FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' AND b.tgl_voucher < '$dcetak' AND YEAR(b.tgl_voucher)='$thn_ang' $where1";
		} else if ($rek6 == '310101010001') {
			$csql3 = "SELECT sum(a.debet) as debet,sum(a.kredit) as kredit from (
					
					select sum(debet) debet, sum(kredit) kredit 
					from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
					where kd_rek6='310101010001' and reev='0' and tgl_voucher < '$dcetak' 
					union all
					select sum(debet) debet, sum(kredit) kredit 
					from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
					where kd_rek6='310101010001' and reev not in ('0') and tgl_voucher < '$dcetak' $where2
					) a ";
		} else if ($rek6 == '310102010001') {
			$csql3 = "SELECT sum(a.debet) as debet,sum(a.kredit) as kredit from (
					select sum(debet) debet, sum(kredit) kredit 
					from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
					where left(kd_rek6,1) in ('7','8') and tgl_voucher < '$dcetak' $where2
					
					) a ";
		} else {
			$csql3 = "SELECT sum(a.debet) as debet,sum(a.kredit) as kredit FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' and b.tgl_voucher < '$dcetak' $where1";
		}

		$hasil = $this->db->query($csql3);
		$trh4 = $hasil->row();
		$awaldebet = $trh4->debet;
		$awalkredit = $trh4->kredit;
		if ((substr($rek6, 0, 1) == '8') or (substr($rek6, 0, 1) == '5') or (substr($rek6, 0, 2) == '62') or (substr($rek6, 0, 2) == '62') or (substr($rek6, 0, 1) == '1')) {
			$saldo = $awaldebet - $awalkredit;
		} else {
			$saldo = $awalkredit - $awaldebet;
		}
		if ($saldo < 0) {
			$a = '(';
			$saldox = $saldo * -1;
			$b = ')';
		} else {
			$a = '';
			$saldox = $saldo;
			$b = '';
		}
		$cRet .= '<TR>
								<TD width="15%" align="left" ></TD>
								<TD width="25%" align="left" >saldo awal</TD>
								<TD width="5%" align="left" ></TD>
								<TD width="5%" align="right" ></TD>
								<TD width="10%" align="right" ></TD>
								<TD width="15%" align="right" >' . $a . '' . number_format($saldox, "2", ",", ".") . '' . $b . '</TD>
							 </TR>';

		$idx = 1;
		if ($rek6 == '310101010001') {
			$query = $this->db->query("SELECT kd_rek6, debet, kredit, tgl_voucher, ket, no_voucher FROM (
										   SELECT a.kd_rek6,a.debet,a.kredit,b.tgl_voucher,b.ket,b.no_voucher FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='310101010001' AND b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2' $where1 
										   ) a
										   ORDER BY tgl_voucher, debet-kredit");
		} else if ($rek6 == '310102010001') {
			$query = $this->db->query("SELECT kd_rek6, debet, kredit, tgl_voucher, ket, no_voucher FROM (
										   
										   SELECT '310102010001' kd_rek6, SUM(a.debet) debet, SUM(a.kredit) kredit, b.tgl_voucher, 'SURPLUS/DEFISIT LO ('+b.ket+' )' ket, 'SURPLUS/DEFISIT LO - '+b.no_voucher as no_voucher FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE LEFT(a.kd_rek6,1) IN ('7','8') AND b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2' $where1
										   GROUP BY b.tgl_voucher, b.no_voucher, b.ket) a
										   ORDER BY tgl_voucher, debet-kredit");
		} else {
			$query = $this->db->query("SELECT a.kd_rek6,a.debet,a.kredit,b.tgl_voucher,b.ket,b.no_voucher FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$rek6' AND b.tgl_voucher>='$dcetak' AND b.tgl_voucher<='$dcetak2' $where1  ORDER BY b.tgl_voucher, 
										   case when left('$rek6',1) in (1,5,6,9) then kredit-debet else debet-kredit end");
			//$query = $this->db->query("SELECT a.kd_rek6,a.debet,a.kredit,b.tgl_voucher,b.ket,b.no_voucher FROM trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher=b.no_voucher WHERE a.kd_rek6='$rek6' AND b.kd_skpd='$skpd' and b.tgl_voucher>='$dcetak' and b.tgl_voucher<='$dcetak2' and a.pos='1' ORDER by b.tgl_voucher, convert(b.no_voucher,unsigned)");  
		}

		if ($query->num_rows() > 0) {
			$jdebet = 0;
			$jkredit = 0;
			foreach ($query->result_array() as $res) {

				$tgl_voucher = $res['tgl_voucher'];
				$ket = $res['ket'];
				$ref = $res['no_voucher'];
				$debet = $res['debet'];
				$kredit = $res['kredit'];
				$idx++;

				
				if ($debet < 0) {
					$debet1 = $debet * -1;
					$c = '(';
					$d = ')';
				} else {
					$c = '';
					$d = '';
					$debet1 = $debet;
				}
				if ($kredit < 0) {
					$kredit1 = $kredit * -1;
					$e = '(';
					$f = ')';
				} else {
					$e = '';
					$f = '';
					$kredit1 = $kredit;
				}
				$saldo = $saldo;
				if ((substr($rek6, 0, 1) == '8') or (substr($rek6, 0, 1) == '5') or (substr($rek6, 0, 2) == '62') or (substr($rek6, 0, 2) == '62') or (substr($rek6, 0, 1) == '1')) {
					$saldo = $saldo + $debet - $kredit;
				} else {
					$saldo = $saldo + $kredit - $debet;
				}
				if ($saldo < 0) {
					$saldo1 = $saldo * -1;
					$i = '(';
					$j = ')';
				} else {
					$saldo1 = $saldo;
					$i = '';
					$j = '';
				}
				$cRet .= '<TR>
								<TD width="15%" align="left" >' . $this->tukd_model->tanggal_format_indonesia($tgl_voucher) . '</TD>
								<TD width="35%" align="left" >' . $ket . '</TD>
								<TD width="5%" align="left" >' . $ref . '</TD>
								<TD width="15%" align="right" >' . $c . '' . number_format($debet1, "2", ",", ".") . '' . $d . '</TD>
								<TD width="15%" align="right" >' . $e . '' . number_format($kredit1, "2", ",", ".") . '' . $f . '</TD>
								<TD width="15%" align="right" >' . $i . '' . number_format($saldo1, "2", ",", ".") . '' . $j . '</TD>
							 </TR>';

				$jdebet = $jdebet + $debet;
				$jkredit = $jkredit + $kredit;
			}
			if ($jdebet < 0) {
				$jdebet1 = $jdebet * -1;
				$k = '(';
				$l = ')';
			} else {
				$jdebet1 = $jdebet;
				$k = '';
				$l = '';
			}
			if ($jkredit < 0) {
				$jkredit1 = $jkredit * -1;
				$m = '(';
				$n = ')';
			} else {
				$jkredit1 = $jkredit;
				$m = '';
				$n = '';
			}

			$cRet .= '<TR>
					<TD width="10%" align="left" ></TD>
					<TD width="30%" align="left" >JUMLAH</TD>
					<TD width="10%" align="left" ></TD>
					<TD width="15%" align="right" >' . $k . '' . number_format($jdebet1, "2", ",", ".") . '' . $l . '</TD>
					<TD width="15%" align="right" >' . $m . '' . number_format($jkredit1, "2", ",", ".") . '' . $n . '</TD>
					<TD width="35%" align="right" >' . $i . '' . number_format($saldo1, "2", ",", ".") . '' . $j . '</TD>
				 </TR>';
			$cRet .= '</TABLE>';
		} else {

			$cRet .= '</TABLE>';
		}

	
		$data['prev'] = $cRet;
		$judul = 'Buku Besar';
		// echo $print;
		// return;
		if($print==0) {
			echo ("<title>$judul</title>");
			echo $cRet;
		}else if($print==1){
			$this->support->_mpdf('', $cRet, 10, 5, 10, '0');
		}else{
			    header("Cache-Control: no-cache, no-store, must-revalidate");
			    header("Content-Type: application/vnd.ms-excel");
			    header("Content-Disposition: attachment; filename=$judul.xls");
			    //$this->load->view('akuntansi/bukubesar', $data);
				echo $cRet;
			}

		}		
		
	}

	//===================================================== Input Jurnal Umum
	function jumum()
	{
		$data['page_title'] = 'INPUT JURNAL UMUM';
		$this->template->set('title', 'INPUT JURNAL UMUM');
		$this->template->load('template', 'akuntansi/jumum', $data);
	}

	function load_dju()
	{
		$skpd     = $this->input->post('kdskpd');
		$nomor = $this->input->post('no');
		$sql = "SELECT a.no_voucher,b.kd_sub_kegiatan,b.nm_sub_kegiatan,b.kd_rek6,b.map_real,case when rk='D' then b.nm_rek6 else SPACE(4)+b.nm_rek6 end AS nm_rek6,b.debet,b.kredit,b.rk,b.jns,b.pos FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.no_voucher=b.no_voucher AND a.kd_skpd=b.kd_unit
		WHERE a.no_voucher='$nomor' AND a.kd_skpd = '$skpd'";
		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {
			$result[] = array(
				'no_voucher'  => $resulte['no_voucher'],
				'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
				'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
				'kd_rek6'     => $resulte['kd_rek6'],
				'kd_rek_ang'     => $resulte['map_real'],
				'nm_rek6'     => $resulte['nm_rek6'],
				'debet'       => $resulte['debet'],
				'kredit'      => $resulte['kredit'],
				'rk'          => $resulte['rk'],
				'jns'         => $resulte['jns'],
				'post'         => $resulte['pos']
			);
			$ii++;
		}
		echo json_encode($result);
		$query1->free_result();
	}

	function load_ju()
	{
		// $skpd     = $this->session->userdata('kdskpd');
		$result = array();
		$row = array();
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page - 1) * $rows;
		$kriteria = '';
		$kriteria = $this->input->post('cari');
		$where = "tabel='1'";
		if ($kriteria <> '') {
			$where = "tabel='1' and (upper(no_voucher) like upper('%$kriteria%') or tgl_voucher like '%$kriteria%' or upper(ket) like upper('%$kriteria%')) ";
		}

		$sql = "SELECT count(*) as total from trhju_pkd WHERE $where";
		$query1 = $this->db->query($sql);
		$total = $query1->row();
		$result = array();
		$query1->free_result();

		$sql = " SELECT top $rows * from trhju_pkd  WHERE  no_voucher not in (select top $offset no_voucher from trhju_pkd  WHERE $where order by tgl_voucher,no_voucher,kd_skpd) and $where order by tgl_voucher,no_voucher,kd_skpd "; //limit $offset,$rows";
		$query1 = $this->db->query($sql);
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {
			$row[] = array(
				'no_voucher'      => $resulte['no_voucher'],
				'tgl_voucher'     => $resulte['tgl_voucher'],
				'kd_skpd'         => $resulte['kd_skpd'],
				'nm_skpd'         => $resulte['nm_skpd'],
				'ket'             => trim($resulte['ket']),
				'reev'            => trim($resulte['reev']),
				'tgl_real'        => trim($resulte['tgl_real']),
				'total_d'         => $resulte['total_d'],
				'total_k'         => $resulte['total_k'],
				'kd_skpd_mutasi'  => $resulte['kd_skpd_mutasi'],
				'nm_skpd_mutasi'  => $resulte['nm_skpd_mutasi']
			);
			$ii++;
		}
		$result["total"] = $total->total;
		$result["rows"] = $row;
		echo json_encode($result);
		$query1->free_result();
	}

	function load_ju_trskpd()
	{
		$jenis = $this->input->post('jenis');
		$len = strlen($jenis);
		$giat = $this->input->post('giat');
		$cskpd = $this->input->post('kd');
		$jns_ang = $this->cek_anggaran_model->cek_anggaran($cskpd);
		$jns_beban = '';
		$cgiat = '';
		if ($jenis != '') {
			$jns_beban = "and left(a.kd_rek6,$len)='$jenis'";
		}
		if ($giat != '') {
			$cgiat = " and a.kd_sub_kegiatan not in ($giat) ";
		}
		$lccr = $this->input->post('q');
		$sql = "SELECT DISTINCT a.kd_sub_kegiatan,a.nm_sub_kegiatan,'' kd_program, '' as nm_program, 0 total FROM trdrka a
                WHERE a.jns_ang='$jns_ang' AND a.kd_skpd='$cskpd' $jns_beban $cgiat AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(a.nm_sub_kegiatan) LIKE UPPER('%$lccr%'))";
		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {

			$result[] = array(
				'id' => $ii,
				'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
				'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
				'kd_program' => $resulte['kd_program'],
				'nm_program' => $resulte['nm_program'],
				'total'       => $resulte['total']
			);
			$ii++;
		}

		echo json_encode($result);
		$query1->free_result();
	}

	function load_ju_rek()
	{
		//$jenis = $this->uri->segment(3);
		$jenis  = $this->input->post('jenis');
		$len    = strlen($jenis);
		$giat   = $this->input->post('giat');
		$kode   = $this->input->post('kd');
		$rek    = $this->input->post('rek');
		$lccr   = $this->input->post('q');
		$jns_ang = $this->cek_anggaran_model->cek_anggaran($kode);

		if ($rek != '') {
			//if($jenis == '7' || $jenis == '8'  ||  $jenis == '9'){
			//$notIn = " and kd_rek4 not in ($rek) " ;
			//}else{
			$notIn = " and a.kd_rek6 not in ('$rek') ";
			//}
		} else {
			$notIn  = "";
		}
		//echo $jenis;

		if ($jenis == '5'  ||  $jenis == '6') {
			$sql = "SELECT a.kd_rek6,a.nm_rek6 FROM trdrka a 
			INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6
			WHERE a.kd_sub_kegiatan= '$giat' and a.jns_ang='$jns_ang'
			AND a.kd_skpd = '$kode' $notIn AND ( upper(a.kd_rek6) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%')) order by kd_rek6";
		} else if ($jenis == '4') {
			$sql = "SELECT a.kd_rek6,a.nm_rek6 FROM trdrka a 
			INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6
			WHERE a.kd_sub_kegiatan= '$giat' and a.jns_ang='$jns_ang'
			AND a.kd_skpd = '$kode' $notIn AND ( upper(a.kd_rek6) like upper('%$lccr%') or upper(a.nm_rek6) like upper('%$lccr%')) order by kd_rek6";
		} else if ($jenis == '0') {
			$sql = "SELECT top 1 '000000000000' as kd_rek6,'Perubahan SAL' as nm_rek6 FROM ms_rek6 a 
					where  ( upper(kd_rek6) like upper('%%') or upper(nm_rek6) like upper('%%')) order by kd_rek6";
		} else {
			//if ($jenis == '7' || $jenis == '8'  ||  $jenis == '9'){

			//$sql = "SELECT  kd_rek6,nm_rek4 as nm_rek6 FROM ms_rek4 where left(kd_rek4,$len)='$jenis' $notIn";  
			//}else{

			$sql = "SELECT kd_rek6,nm_rek6,kd_rek2020,nm_rek2020 FROM ms_rek6 a
                LEFT JOIN map_rek ON kd_rek6 = kd_rek2021
                where left(kd_rek6,$len)='$jenis' $notIn
                AND (
                    upper(kd_rek6) like upper('%$lccr%') or upper(nm_rek6) like upper('%$lccr%')
                    OR upper(kd_rek2020) like upper('%$lccr%') or upper(nm_rek2020) like upper('%$lccr%')
                )
                order by kd_rek6
                OFFSET 0 ROWS FETCH NEXT 200 ROWS ONLY";
			//}
		}
		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {
			$result[] = array(
				'kd_rek6' => $resulte['kd_rek6'],
				'kd_rek_ang' => $resulte['kd_rek6'],
				'nm_rek6' => $resulte['nm_rek6'],
				'kd_rek2020' => isset($resulte['kd_rek2020']) ? $resulte['kd_rek2020'] : '',
				'nm_rek2020' => isset($resulte['nm_rek2020']) ? $resulte['nm_rek2020'] : '',
			);
			$ii++;
		}
		echo json_encode($result);
		$query1->free_result();
	}


	function hapus_ju()
	{
		$skpd     = $this->input->post('kdskpd');
		$nomor = $this->input->post('no');
		$msg = array();
		$sql = "delete from trdju_pkd where no_voucher='$nomor' AND kd_unit='$skpd'";
		$asg = $this->db->query($sql);
		if ($asg) {
			$sql = "delete from trhju_pkd where no_voucher='$nomor' AND kd_skpd='$skpd'";
			$asg = $this->db->query($sql);
			if (!($asg)) {
				$msg = array('pesan' => '0');
				echo json_encode($msg);
				exit();
			}
		} else {
			$msg = array('pesan' => '0');
			echo json_encode($msg);
			exit();
		}
		$msg = array('pesan' => '1');
		echo json_encode($msg);
	}

	function simpan_ju()
	{
		$tabel      = $this->input->post('tabel');
		$nomor      = $this->input->post('no');
		$tgl        = $this->input->post('tgl');
		$skpd       = $this->input->post('skpd');
		$nmskpd     = $this->input->post('nmskpd');
		$ket        = $this->input->post('ket');
		$reev       = $this->input->post('reev');
		$tgl_real   = $this->input->post('tgl_real');
		$total_d     = $this->input->post('total_d');
		$total_k     = $this->input->post('total_k');
		$csql        = $this->input->post('sql');
		$ket_mutasi1 = $this->input->post('ket_mutasi1');
		$ket_mutasi2 = $this->input->post('ket_mutasi2');
		$skpd_mutasi  = $this->input->post('skpd_mutasi');
		$nmskpd_mutasi = $this->input->post('nmskpd_mutasi');

		$usernm     = $this->session->userdata('pcNama');
		$update     = date('Y-m-d H:i:s');
		$msg        = array();
		if ($tabel == 'trhju_pkd') {
			$sql = "delete from trhju_pkd where kd_skpd='$skpd' and no_voucher='$nomor'";
			$asg = $this->db->query($sql);
			$sql = "delete from trdju_pkd where no_voucher='$nomor' and kd_unit='$skpd'";
			$asg = $this->db->query($sql);
			if ($asg) {
				$sql = "INSERT into trhju_pkd(no_voucher,tgl_voucher,ket,username,tgl_update,kd_skpd,nm_skpd,total_d,total_k,tabel,reev,tgl_real,kd_skpd_mutasi,nm_skpd_mutasi) 
									values('$nomor','$tgl','$ket_mutasi1'+'$nmskpd_mutasi'+'$ket_mutasi2'+'$ket','$usernm','$update','$skpd','$nmskpd','$total_d','$total_k','1','$reev','$tgl_real','$skpd_mutasi',
											'$nmskpd_mutasi')";
				$asg = $this->db->query($sql);
				if (!($asg)) {
					$msg = array('pesan' => '0');
					echo json_encode($msg);
					exit();
				} else {
					$msg = array('pesan' => '1');
					echo json_encode($msg);
				}
			} else {
				$msg = array('pesan' => '0');
				echo json_encode($msg);
				exit();
			}
		} else if ($tabel == 'trdju_pkd') {

			// Simpan Detail //                       
			$sql = "delete from trdju_pkd where no_voucher='$nomor' and kd_unit='$skpd'";
			$asg = $this->db->query($sql);
			if (!($asg)) {
				$msg = array('pesan' => '0');
				echo json_encode($msg);
				exit();
			} else {
				$sql = "insert into trdju_pkd(no_voucher,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,debet,kredit,rk,jns,kd_unit,pos,urut,map_real)";

				$asg = $this->db->query($sql . $csql);
				if (!($asg)) {
					$msg = array('pesan' => '0');
					echo json_encode($msg);
					exit();
				} else {
					$msg = array('pesan' => '1');
					echo json_encode($msg);
				}
			}
		}
	}

	function simpan_ju_edit()
	{
		$tabel  = $this->input->post('tabel');
		$nomor  = $this->input->post('no');
		$no_bku  = $this->input->post('no_bku');
		$tgl    = $this->input->post('tgl');
		$skpd   = $this->input->post('skpd');
		$nmskpd = $this->input->post('nmskpd');
		$ket    = $this->input->post('ket');
		$reev   = $this->input->post('reev');
		$tgl_real   = $this->input->post('tgl_real');
		$total_d = $this->input->post('total_d');
		$total_k = $this->input->post('total_k');
		$csql    = $this->input->post('sql');
		$skpd_mutasi  = $this->input->post('skpd_mutasi');
		$nmskpd_mutasi = $this->input->post('nmskpd_mutasi');

		$usernm     = $this->session->userdata('pcNama');
		$update     = date('Y-m-d H:i:s');
		$msg        = array();

		if ($tabel == 'trhju_pkd') {
			$sql = "delete from trhju_pkd where kd_skpd='$skpd' and no_voucher='$no_bku'";
			$asg = $this->db->query($sql);
			$sql = "delete from trdju_pkd where no_voucher='$no_bku' and kd_unit='$skpd'";
			$asg = $this->db->query($sql);
			if ($asg) {
				$sql = "INSERT into trhju_pkd(no_voucher,tgl_voucher,ket,username,tgl_update,kd_skpd,nm_skpd,total_d,total_k,tabel,reev,tgl_real,kd_skpd_mutasi,nm_skpd_mutasi) 
									values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total_d','$total_k','1','$reev','$tgl_real','$skpd_mutasi',
											'$nmskpd_mutasi')";
				$asg = $this->db->query($sql);
				if (!($asg)) {
					$msg = array('pesan' => '0');
					echo json_encode($msg);
					exit();
				} else {
					$msg = array('pesan' => '1');
					echo json_encode($msg);
				}
			} else {
				$msg = array('pesan' => '0');
				echo json_encode($msg);
				exit();
			}
		} else if ($tabel == 'trdju_pkd') {

			// Simpan Detail //                       
			$sql = "delete from trdju_pkd where no_voucher='$no_bku' and kd_unit='$skpd'";
			$asg = $this->db->query($sql);
			if (!($asg)) {
				$msg = array('pesan' => '0');
				echo json_encode($msg);
				exit();
			} else {
				$sql = "insert into trdju_pkd(no_voucher,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,debet,kredit,rk,jns,kd_unit,pos,urut,map_real)";

				$asg = $this->db->query($sql . $csql);
				if (!($asg)) {
					$msg = array('pesan' => '0');
					echo json_encode($msg);
					exit();
				} else {
					$msg = array('pesan' => '1');
					echo json_encode($msg);
				}
			}
		}
	}
	//===================================================== End Input Jurnal Umum

	//============================================================= Cetak Jurnal Umum
	function jur_umum()
	{
		$data['page_title'] = 'JURNAL UMUM';
		$this->template->set('title', 'JURNAL UMUM');
		$this->template->load('template', 'akuntansi/jur_umum', $data);
	}

	function ctk_jurum($dcetak = '', $dcetak2 = '', $skpd = '')
	{
		$csql11 = " SELECT nm_skpd from ms_skpd where kd_skpd = '$skpd'";
		$rs1 = $this->db->query($csql11);
		$trh1 = $rs1->row();
		$lcskpd = strtoupper($trh1->nm_skpd);
		$tgl = $this->tukd_model->tanggal_format_indonesia($dcetak);
		$tgl2 = $this->tukd_model->tanggal_format_indonesia($dcetak2);

		$cRet = "";
		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"60%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
						<tr>
							<td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;\">
								<b>$lcskpd</b>
							</td>
						</tr>
						<tr>
							<td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;\">
								<b>JURNAL UMUM</b>
							</td>
						</tr>
						<tr>
							<td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;border-bottom:solid 1px white;\">PERIODE $tgl S.D $tgl2
							</td>
						</tr>
						</table>";

		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"90%\" align=\"center\" border=\"2\" cellspacing=\"0\" cellpadding=\"4\">
						<thead>
						<tr>
							<td align=\"center\"bgcolor=\"#CCCCCC\" rowspan=\"2\">Tanggal</td>
							<td align=\"center\" bgcolor=\"#CCCCCC\"rowspan=\"2\">Nomor<br>Bukti</td>
							<td colspan=\"5\"bgcolor=\"#CCCCCC\" align=\"center\" rowspan=\"2\">Kode<br>Rekening</td>
							<td align=\"center\"bgcolor=\"#CCCCCC\" rowspan=\"2\">Uraian</td>
							<td align=\"center\"bgcolor=\"#CCCCCC\" rowspan=\"2\">ref</td>
							<td align=\"center\"bgcolor=\"#CCCCCC\" colspan=\"2\">Jumlah Rp</td>
						</tr>
						<tr>
							<td align=\"center\" bgcolor=\"#CCCCCC\">Debit</td>
							<td align=\"center\"bgcolor=\"#CCCCCC\">Kredit</td>
						</tr>
						<tr>
							<td align=\"center\" width=\"15%\";border-bottom:solid 1px red;\">1</td>
							<td align=\"center\" width=\"10%\";border-bottom:solid 1px blue;\">2</td>
							<td colspan=\"5\" align=\"center\" width=\"15%\">3</td>
							<td align=\"center\" width=\"42%\">4</td>
							<td align=\"center\" width=\"3%\"></td>
							<td align=\"center\" width=\"10%\">5</td>
							<td align=\"center\" width=\"10%\">6</td>
						</tr>
						</thead>";

		$csql1 = "SELECT count(*) as tot FROM 
						trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher and a.kd_unit=b.kd_skpd 
						where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd'";
		$rs = $this->db->query($csql1);
		$trh = $rs->row();

		$csql = "SELECT b.tgl_voucher,a.no_voucher,a.kd_rek6, nm_rek6,a.debet,a.kredit,a.rk from trdju_pkd a join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
						where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd' 
						ORDER BY b.tgl_voucher,a.no_voucher,a.urut,a.rk,a.kd_rek6";
		$query = $this->db->query($csql);
		$cnovoc = '';
		$lcno = 0;
		foreach ($query->result_array() as $res) {
			$lcno = $lcno + 1;
			if ($lcno == $trh->tot) {
				$cRet .= "<tr>
							 <td style=\"border-bottom:none;border-top:none;\"></td>
							 <td style=\"border-bottom:none;border-top:none;\"></td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 0, 1) . "</td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 1, 1) . "</td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 2, 1) . "</td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 3, 2) . "</td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 5, 2) . "</td>
							 <td style=\"border-bottom:none;\">" . $res['nm_rek6'] . "</td>
							 <td style=\"border-bottom:none;\"></td>";
				if ($res['rk'] == 'K') {
					$cRet .= " <td style=\"border-bottom:none;\"></td>
									<td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['kredit']) . "</td>";
				} else {
					$cRet .= "  <td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['debet']) . "</td>
									<td style=\"border-bottom:none;\"></td>";
				}

				$cRet .= "</tr>";
			} else {
				if ($cnovoc == $res['no_voucher']) {
					$cRet .= "<tr>
							 <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
							 <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 0, 1) . "</td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 1, 1) . "</td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 2, 1) . "</td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 3, 2) . "</td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 5, 2) . "</td>
							 <td style=\"border-bottom:none;\">" . $res['nm_rek6'] . "</td>
							 <td style=\"border-bottom:none;\"></td>";
					if ($res['rk'] == 'K') {
						$cRet .= "  <td style=\"border-bottom:none;\"></td>
										<td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['kredit']) . "</td>";
					} else {
						$cRet .= "  <td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['debet']) . "</td>
										<td style=\"border-bottom:none;\"></td>";
					}

					$cRet .= "</tr>";
				} else {
					$cRet .= "<tr>
							 <td style=\"border-bottom:none\">" . $this->tukd_model->tanggal_ind($res['tgl_voucher']) . "</td>
							 <td style=\"border-bottom:none\">" . $res['no_voucher'] . "</td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 0, 1) . "</td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 1, 1) . "</td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 2, 1) . "</td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 3, 2) . "</td>
							 <td style=\"border-bottom:none;\">" . substr($res['kd_rek6'], 5, 2) . "</td>
							 <td style=\"border-bottom:none;\">" . $res['nm_rek6'] . "</td>
							 <td style=\"border-bottom:none;\"></td>";
					if ($res['rk'] == 'K') {
						$cRet .= "  <td style=\"border-bottom:none;\"></td>
										<td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['kredit']) . "</td>";
					} else {
						$cRet .= "  <td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['debet']) . "</td>
										<td style=\"border-bottom:none;\"></td>";
					}

					$cRet .= "</tr>";
				}
				$cnovoc = $res['no_voucher'];
			}
		}

		$cRet .= "  <tr>
							<td style=\"border-top:none\"></td>
							<td style=\"border-top:none\"></td>
							<td style=\"border-top:none\"></td>
							<td style=\"border-top:none\"></td>
							<td style=\"border-top:none\"></td>
							<td style=\"border-top:none\"></td>
							<td style=\"border-top:none\"></td>
							<td style=\"border-top:none\"></td>
							<td style=\"border-top:none\"></td>
							<td style=\"border-top:none\"></td>
							<td style=\"border-top:none\"></td>
						</tr>  
						</table>";

		$data['prev'] = $cRet; //'JURNAL UMUM';
		echo $cRet;
		//$this->tukd_model->_mpdf('',$cRet,5,5,10,'0');	
	}
	//============================================================= End Cetak Jurnal Umum


	//============================================================= Mapping
	function mapping()
	{
		$data['page_title'] = 'MAPPING REALISASI';
		$this->template->set('title', 'MAPPING REALISASI');
		$this->template->load('template', 'akuntansi/mapping', $data);
	}

	function proses_mapping_all()
	{
		$user     = $this->session->userdata('pcNama');
		$skpd     = $this->session->userdata('kdskpd');
		$thn      = $this->session->userdata('pcThang');

		$this->db->query("jurnal_brewok_all '$skpd', $thn");

		echo '1';
	}


	function proses_mapping_rekap()
	{
		$user     = $this->session->userdata('pcNama');
		$skpd     = $this->session->userdata('kdskpd');
		$thn	  = $this->session->userdata('pcThang');
		$this->db->query("jurnal_rekap_pemkot '$thn'");
		echo '1';
	}
	//============================================================= End Mapping

	function skpd_new()
	{
		$lccr = $this->input->post('q');
		$sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd where right(kd_skpd,2)='00'";
		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {

			$result[] = array('id' => $ii, 'kd_skpd' => $resulte['kd_skpd'], 'nm_skpd' => $resulte['nm_skpd'],);
			$ii++;
		}

		echo json_encode($result);
	}

	function cetak_neraca_skpd()
	{
		$data['page_title'] = 'NERACA SKPD';
		$this->template->set('title', 'NERACA SKPD');
		$this->template->load('template', 'akuntansi/skpd/cetak_neraca', $data);
	}

	function skpd_2()
	{
		$lccr = $this->input->post('q');
		$sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd where right(kd_skpd,2)='00' and upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%') ";
		$query1 = $this->db->query($sql);
		$result = array();
		$ii = 0;
		foreach ($query1->result_array() as $resulte) {

			$result[] = array('id' => $ii, 'kd_skpd' => $resulte['kd_skpd'], 'nm_skpd' => $resulte['nm_skpd'],);
			$ii++;
		}

		echo json_encode($result);
	}



	function ctk_lra_lo_pemda_objek($cbulan = "", $pilih = "", $tglttd = "", $ttd = "")
	{
		//$id = $skpd;
		$this->load->library('custom');
		$cetak = '2'; //$ctk; 
		$tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
		$tglttd = str_replace('n', ' ', $tglttd);
		$id     = $this->session->userdata('kdskpd');
		$thn_ang = $this->session->userdata('pcThang');
		$thn_ang_1 = $thn_ang - 1;
		//$laporan=$lap; 

		// if ($cetak == '1') {
		//           $skpd = '';
		//           $skpd1 = '';           
		//       } else {             
		$skpd = "AND kd_skpd='$id'";
		$skpd1 = "AND b.kd_skpd='$id'";
		//}  

		$y123 = ")";
		$x123 = "(";
		$sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$id'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$nmskpd     = $rowsc->nm_skpd;
		}
		$nm_skpd = strtoupper($nmskpd);
		// INSERT DATA

		$sqldns = "SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$id'";
		$sqlskpd = $this->db->query($sqldns);
		foreach ($sqlskpd->result() as $rowdns) {
			$kd_urusan = $rowdns->kd_u;
			$nm_urusan = $rowdns->nm_u;
			$kd_skpd  = $rowdns->kd_sk;
			$nm_skpd  = $rowdns->nm_sk;
		}

		// created by henri_tb

		$sqllo1 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73')";
		$querylo1 = $this->db->query($sqllo1);
		$penlo = $querylo1->row();
		$pen_lo = $penlo->nilai;
		$pen_lo1 = number_format($penlo->nilai, "2", ",", ".");

		$sqllo2 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73')";
		$querylo2 = $this->db->query($sqllo2);
		$penlo2 = $querylo2->row();
		$pen_lo_lalu = $penlo2->nilai;
		$pen_lo_lalu1 = number_format($penlo2->nilai, "2", ",", ".");

		$sqllo3 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83','84')";
		$querylo3 = $this->db->query($sqllo3);
		$bello = $querylo3->row();
		$bel_lo = $bello->nilai;
		$bel_lo1 = number_format($bello->nilai, "2", ",", ".");

		$sqllo4 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','83','84')";
		$querylo4 = $this->db->query($sqllo4);
		$bello2 = $querylo4->row();
		$bel_lo_lalu = $bello2->nilai;
		$bel_lo_lalu1 = number_format($bello2->nilai, "2", ",", ".");

		// calvin 11 february 2023

		$sqllo5 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74','85')";
		$querylo5 = $this->db->query($sqllo5);
		$def5 = $querylo5->row();
		$def_lo = $def5->nilai;
		$def_lo1 = number_format($def5->nilai, "2", ",", ".");

		$sqllo6 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74','85')";
		$querylo6 = $this->db->query($sqllo6);
		$def6 = $querylo6->row();
		$del_lo_lalu = $def6->nilai;
		$del_lo_lalu1 = number_format($def6->nilai, "2", ",", ".");

		// end
		$surplus_lo = $pen_lo - $bel_lo;
		if ($surplus_lo < 0) {
			$lo1 = "(";
			$surplus_lox = $surplus_lo * -1;
			$lo2 = ")";
		} else {
			$lo1 = "";
			$surplus_lox = $surplus_lo;
			$lo2 = "";
		}
		$surplus_lo1 = number_format($surplus_lox, "2", ",", ".");

		$surplus_lo_lalu = $pen_lo_lalu - $bel_lo_lalu;
		if ($surplus_lo_lalu < 0) {
			$lo3 = "(";
			$surplus_lo_lalux = $surplus_lo_lalu * -1;
			$lo4 = ")";
		} else {
			$lo3 = "";
			$surplus_lo_lalux = $surplus_lo_lalu;
			$lo4 = "";
		}
		$surplus_lo_lalu1 = number_format($surplus_lo_lalux, "2", ",", ".");

		// calvin 
		$surplus_def_lo = $surplus_lo - $def_lo;
		if ($surplus_def_lo < 0) {
			$lo96 = "(";
			$surplus_def = $surplus_def_lo * -1;
			$lo97 = ")";
		} else {
			$lo96 = "";
			$surplus_def = $surplus_def_lo;
			$lo97 = "";
		}
		$surplus_def_lo1 = number_format($surplus_def, "2", ",", ".");

		$surplus_def_lo_lalu = $surplus_lo_lalu - $del_lo_lalu;
		if ($surplus_def_lo_lalu < 0) {
			$lo99 = "(";
			$surplus_def_lalux = $surplus_def_lo_lalu * -1;
			$lo98 = ")";
		} else {
			$lo99 = "";
			$surplus_def_lalux = $surplus_def_lo_lalu;
			$lo98 = "";
		}
		$surplus_def_lo_lalu1 = number_format($surplus_def_lalux, "2", ",", ".");


		$selisih_surplus_def = $surplus_def_lo - $surplus_def_lo_lalu;
		if ($selisih_surplus_def < 0) {
			$lo100 = "(";
			$selisih_surplus_defx = $selisih_surplus_def * -1;
			$lo101 = ")";
		} else {
			$lo100 = "";
			$selisih_surplus_defx = $selisih_surplus_def;
			$lo101 = "";
		}
		$selisih_surplus_def1 = number_format($selisih_surplus_defx, "2", ",", ".");

		if ($surplus_def_lo_lalu == '' or $surplus_def_lo_lalu == 0) {
			$persen100 = '0,00';
		} else {
			$persen100 = ($surplus_def_lo / $surplus_def_lo_lalu) * 100;
			$persen100 = number_format($persen100, "2", ",", ".");
		}

		//end

		$selisih_surplus_lo = $surplus_lo - $surplus_lo_lalu;
		if ($selisih_surplus_lo < 0) {
			$lo5 = "(";
			$selisih_surplus_lox = $selisih_surplus_lo * -1;
			$lo6 = ")";
		} else {
			$lo5 = "";
			$selisih_surplus_lox = $selisih_surplus_lo;
			$lo6 = "";
		}
		$selisih_surplus_lo1 = number_format($selisih_surplus_lox, "2", ",", ".");

		if ($surplus_lo_lalu == '' or $surplus_lo_lalu == 0) {
			$persen2 = '0,00';
		} else {
			$persen2 = ($surplus_lo / $surplus_lo_lalu) * 100;
			$persen2 = number_format($persen2, "2", ",", ".");
		}

		$sqllo5 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73','74')";
		$querylo5 = $this->db->query($sqllo5);
		$penlo3 = $querylo5->row();
		$pen_lo3 = $penlo3->nilai;
		$pen_lo31 = number_format($penlo3->nilai, "2", ",", ".");

		$sqllo6 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73','74')";
		$querylo6 = $this->db->query($sqllo6);
		$penlo4 = $querylo6->row();
		$pen_lo_lalu4 = $penlo4->nilai;
		$pen_lo_lalu41 = number_format($penlo4->nilai, "2", ",", ".");

		$sqllo7 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83')";
		$querylo7 = $this->db->query($sqllo7);
		$bello5 = $querylo7->row();
		$bel_lo5 = $bello5->nilai;
		$bel_lo51 = number_format($bello5->nilai, "2", ",", ".");

		$sqllo8 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','83')";
		$querylo8 = $this->db->query($sqllo8);
		$bello6 = $querylo8->row();
		$bel_lo_lalu6 = $bello6->nilai;
		$bel_lo_lalu61 = number_format($bello6->nilai, "2", ",", ".");

		$surplus_lo2 = $pen_lo3 - $bel_lo5;
		if ($surplus_lo2 < 0) {
			$lo7 = "(";
			$surplus_lo2x = $surplus_lo2 * -1;
			$lo8 = ")";
		} else {
			$lo7 = "";
			$surplus_lo2x = $surplus_lo2;
			$lo8 = "";
		}
		$surplus_lo21 = number_format($surplus_lo2x, "2", ",", ".");

		$surplus_lo_lalu2 = $pen_lo_lalu4 - $bel_lo_lalu6;
		if ($surplus_lo_lalu2 < 0) {
			$lo9 = "(";
			$surplus_lo_lalu2x = $surplus_lo_lalu2 * -1;
			$lo10 = ")";
		} else {
			$lo9 = "";
			$surplus_lo_lalu2x = $surplus_lo_lalu2;
			$lo10 = "";
		}
		$surplus_lo_lalu21 = number_format($surplus_lo_lalu2x, "2", ",", ".");

		$selisih_surplus_lo2 = $surplus_lo2 - $surplus_lo_lalu2;
		if ($selisih_surplus_lo2 < 0) {
			$lo11 = "(";
			$selisih_surplus_lo2x = $selisih_surplus_lo2 * -1;
			$lo12 = ")";
		} else {
			$lo11 = "";
			$selisih_surplus_lo2x = $selisih_surplus_lo2;
			$lo12 = "";
		}
		$selisih_surplus_lo21 = number_format($selisih_surplus_lo2x, "2", ",", ".");

		if ($surplus_lo_lalu2 == '' or $surplus_lo_lalu2 == 0) {
			$persen3 = '0,00';
		} else {
			$persen3 = ($surplus_lo2 / $surplus_lo_lalu2) * 100;
			$persen3 = number_format($persen3, "2", ",", ".");
		}

		$sqllo9 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('7')";
		$querylo9 = $this->db->query($sqllo9);
		$penlo7 = $querylo9->row();
		$pen_lo7 = $penlo7->nilai;
		$pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

		$sqllo10 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7')";
		$querylo10 = $this->db->query($sqllo10);
		$penlo8 = $querylo10->row();
		$pen_lo_lalu8 = $penlo8->nilai;
		$pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

		$sqllo11 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('8')";
		$querylo11 = $this->db->query($sqllo11);
		$bello9 = $querylo11->row();
		$bel_lo9 = $bello9->nilai;
		$bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8')";
		$querylo12 = $this->db->query($sqllo12);
		$bello10 = $querylo12->row();
		$bel_lo_lalu10 = $bello10->nilai;
		$bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

		$surplus_lo3 = $pen_lo7 - $bel_lo9;
		if ($surplus_lo3 < 0) {
			$lo13 = "(";
			$surplus_lo3x = $surplus_lo3 * -1;
			$lo14 = ")";
		} else {
			$lo13 = "";
			$surplus_lo3x = $surplus_lo3;
			$lo14 = "";
		}
		$surplus_lo31 = number_format($surplus_lo3x, "2", ",", ".");

		$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
		if ($surplus_lo_lalu3 < 0) {
			$lo15 = "(";
			$surplus_lo_lalu3x = $surplus_lo_lalu3 * -1;
			$lo16 = ")";
		} else {
			$lo15 = "";
			$surplus_lo_lalu3x = $surplus_lo_lalu3;
			$lo16 = "";
		}
		$surplus_lo_lalu31 = number_format($surplus_lo_lalu3x, "2", ",", ".");

		$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
		if ($selisih_surplus_lo3 < 0) {
			$lo17 = "(";
			$selisih_surplus_lo3x = $selisih_surplus_lo3 * -1;
			$lo18 = ")";
		} else {
			$lo17 = "";
			$selisih_surplus_lo3x = $selisih_surplus_lo3;
			$lo18 = "";
		}
		$selisih_surplus_lo31 = number_format($selisih_surplus_lo3x, "2", ",", ".");

		if ($surplus_lo_lalu3 == '' or $surplus_lo_lalu3 == 0) {
			$persen4 = '0,00';
		} else {
			$persen4 = ($surplus_lo3 / $surplus_lo_lalu3) * 100;
			$persen4 = number_format($persen4, "2", ",", ".");
		}


		$sqllo13 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74')";
		$querylo13 = $this->db->query($sqllo13);
		$penlo11 = $querylo13->row();
		$pen_lo11 = $penlo11->nilai;
		$pen_lo111 = number_format($penlo11->nilai, "2", ",", ".");

		$sqllo14 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74')";
		$querylo14 = $this->db->query($sqllo14);
		$penlo12 = $querylo14->row();
		$pen_lo_lalu12 = $penlo12->nilai;
		$pen_lo_lalu121 = number_format($penlo12->nilai, "2", ",", ".");

		$sqllo15 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('85')";
		$querylo15 = $this->db->query($sqllo15);
		$bello13 = $querylo15->row();
		$bel_lo13 = $bello13->nilai;
		$bel_lo131 = number_format($bello13->nilai, "2", ",", ".");

		$sqllo16 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('83')";
		$querylo16 = $this->db->query($sqllo16);
		$bello14 = $querylo16->row();
		$bel_lo_lalu14 = $bello14->nilai;
		$bel_lo_lalu141 = number_format($bello14->nilai, "2", ",", ".");

		$surplus_lo4 = $pen_lo11 - $bel_lo13;
		if ($surplus_lo4 < 0) {
			$lo19 = "(";
			$surplus_lo4x = $surplus_lo4 * -1;
			$lo20 = ")";
		} else {
			$lo19 = "";
			$surplus_lo4x = $surplus_lo4;
			$lo20 = "";
		}
		$surplus_lo41 = number_format($surplus_lo4x, "2", ",", ".");

		$surplus_lo_lalu4 = $pen_lo_lalu12 - $bel_lo_lalu14;
		if ($surplus_lo_lalu4 < 0) {
			$lo21 = "(";
			$surplus_lo_lalu4x = $surplus_lo_lalu4 * -1;
			$lo22 = ")";
		} else {
			$lo21 = "";
			$surplus_lo_lalu4x = $surplus_lo_lalu4;
			$lo22 = "";
		}
		$surplus_lo_lalu41 = number_format($surplus_lo_lalu4x, "2", ",", ".");

		$selisih_surplus_lo4 = $surplus_lo4 - $surplus_lo_lalu4;
		if ($selisih_surplus_lo4 < 0) {
			$lo23 = "(";
			$selisih_surplus_lo4x = $selisih_surplus_lo4 * -1;
			$lo24 = ")";
		} else {
			$lo23 = "";
			$selisih_surplus_lo4x = $selisih_surplus_lo4;
			$lo24 = "";
		}
		$selisih_surplus_lo41 = number_format($selisih_surplus_lo4x, "2", ",", ".");

		if ($surplus_lo_lalu4 == '' or $surplus_lo_lalu4 == 0) {
			$persen5 = '0,00';
		} else {
			$persen5 = ($surplus_lo4 / $surplus_lo_lalu4) * 100;
			$persen5 = number_format($persen5, "2", ",", ".");
		}


		$sqllo17 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('75')";
		$querylo17 = $this->db->query($sqllo17);
		$penlo15 = $querylo17->row();
		$pen_lo15 = $penlo15->nilai;
		$pen_lo151 = number_format($penlo15->nilai, "2", ",", ".");

		$sqllo18 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('75')";
		$querylo18 = $this->db->query($sqllo18);
		$penlo16 = $querylo18->row();
		$pen_lo_lalu16 = $penlo16->nilai;
		$pen_lo_lalu161 = number_format($penlo16->nilai, "2", ",", ".");

		$sqllo19 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('84')";
		$querylo19 = $this->db->query($sqllo19);
		$bello17 = $querylo19->row();
		$bel_lo17 = $bello17->nilai;
		$bel_lo171 = number_format($bello17->nilai, "2", ",", ".");

		$sqllo20 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('84')";
		$querylo20 = $this->db->query($sqllo20);
		$bello18 = $querylo20->row();
		$bel_lo_lalu18 = $bello18->nilai;
		$bel_lo_lalu181 = number_format($bello18->nilai, "2", ",", ".");

		$surplus_lo5 = $pen_lo15 - $bel_lo17;
		if ($surplus_lo5 < 0) {
			$lo25 = "(";
			$surplus_lo5x = $surplus_lo5 * -1;
			$lo26 = ")";
		} else {
			$lo25 = "";
			$surplus_lo5x = $surplus_lo5;
			$lo26 = "";
		}
		$surplus_lo51 = number_format($surplus_lo5x, "2", ",", ".");

		$surplus_lo_lalu5 = $pen_lo_lalu16 - $bel_lo_lalu18;
		if ($surplus_lo_lalu5 < 0) {
			$lo27 = "(";
			$surplus_lo_lalu5x = $surplus_lo_lalu5 * -1;
			$lo28 = ")";
		} else {
			$lo27 = "";
			$surplus_lo_lalu5x = $surplus_lo_lalu5;
			$lo28 = "";
		}
		$surplus_lo_lalu51 = number_format($surplus_lo_lalu5x, "2", ",", ".");

		$selisih_surplus_lo5 = $surplus_lo5 - $surplus_lo_lalu5;
		if ($selisih_surplus_lo5 < 0) {
			$lo29 = "(";
			$selisih_surplus_lo5x = $selisih_surplus_lo5 * -1;
			$lo30 = ")";
		} else {
			$lo29 = "";
			$selisih_surplus_lo5x = $selisih_surplus_lo5;
			$lo30 = "";
		}
		$selisih_surplus_lo51 = number_format($selisih_surplus_lo5x, "2", ",", ".");

		if ($surplus_lo_lalu5 == '' or $surplus_lo_lalu5 == 0) {
			$persen6 = '0,00';
		} else {
			$persen6 = ($surplus_lo5 / $surplus_lo_lalu5) * 100;
			$persen6 = number_format($persen6, "2", ",", ".");
		}


		$modtahun = $thn_ang % 4;

		if ($modtahun = 0) {
			$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		} else {
			$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		$cRet = '';

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
						<td rowspan=\"3\" align=\"left\" width=\"2%\">
						<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"60\" height=\"70\" />
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";

		$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NO</b></td>                            
                            <td colspan = \"5\" bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>Kenaikan /</br>(Penurunan)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>
                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td colspan = \"5\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>";

		$sqlmaplo = "SELECT seq, nor, uraian, bold,isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak ,parent,kode FROM map_lo_pemda 
				   GROUP BY seq, nor, uraian, bold,isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet'), parent ,kode ORDER BY seq";

		$querymaplo = $this->db->query($sqlmaplo);
		$no     = 0;
		$arr_parent = [];
		$surplus_lo = 0;
		$surplus_lo_thnlalu = 0;
		$selisih_surplus_lo = 0;

		foreach ($querymaplo->result() as $loquery) {
			$operator = $loquery->kode;
			$seq = $loquery->seq;
			$nama      = $loquery->uraian;
			$n         = $loquery->kode_1;
			$n		   = ($n == "-" ? "'-'" : $n);
			$n2        = $loquery->kode_2;
			$n2		   = ($n2 == "-" ? "'-'" : $n2);
			$n3        = $loquery->kode_3;
			$n3		   = ($n3 == "-" ? "'-'" : $n3);
			$normal    = $loquery->cetak;
			$bold      = $loquery->bold;

			if ($normal != "") {
				$quelo01   = "SELECT SUM($normal) as nilai FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan";
				$quelo02 = $this->db->query($quelo01);
				$quelo03 = $quelo02->row();
				$nil     = $quelo03->nilai;
				$nilai    = number_format($quelo03->nilai, "2", ",", ".");

				$quelo04   = "SELECT SUM($normal) as nilai FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang_1";
				$quelo05 = $this->db->query($quelo04);
				$quelo06 = $quelo05->row();
				$nil_lalu     = $quelo06->nilai;
				$nilai_lalu    = number_format($quelo06->nilai, "2", ",", ".");

				if ($nil < 0) {
					$lo88 = "(";
					$nilx = $nil * -1;
					$lo89 = ")";
				} else {
					$lo88 = "";
					$nilx = $nil;
					$lo89 = "";
				}
				$nil1 = number_format($nilx, "2", ",", ".");

				if ($nil_lalu < 0) {
					$lo99 = "(";
					$nil_lalux = $nil_lalu * -1;
					$lo98 = ")";
				} else {
					$lo99 = "";
					$nil_lalux = $nil_lalu;
					$lo98 = "";
				}
				$nil_lalu1 = number_format($nil_lalux, "2", ",", ".");

				$real_nilai = $nil - $nil_lalu;
				if ($real_nilai < 0) {
					$lo0 = "(";
					$real_nilaix = $real_nilai * -1;
					$lo00 = ")";
				} else {
					$lo0 = "";
					$real_nilaix = $real_nilai;
					$lo00 = "";
				}
				$real_nilai1 = number_format($real_nilaix, "2", ",", ".");

				if ($nil_lalu == '' or $nil_lalu == 0) {
					$persen1 = '0,00';
				} else {
					$persen1 = ($nil / $nil_lalu) * 100;
					$persen1 = number_format($persen1, "2", ",", ".");
				}
				if ($loquery->parent) {
					$arr_parent[] = array(
						"seq" => $seq,
						"parent" => $loquery->parent,
						"uraian" => $nama,
						"nilai" => ($operator == '-') ? $nil * -1 : $nil,
						"nilai_lalu" => ($operator == '-') ? $nil_lalu  * -1 : $nil_lalu
					);
				}
			}

			$no       = $no + 1;
			switch ($loquery->bold) {
				case 0:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td colspan =\"5\"  style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 1:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 2:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>   
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                         
                                     <td colspan =\"4\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
									 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 3:
					$cRet    .= "<tr>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td> 
										 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
										 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                  
										 <td colspan =\"3\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo88$nil1$lo89</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo99$nil_lalu1$lo98</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo0$real_nilai1$lo00</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persen1</td>
									 </tr>";
					//===================== Objek         
					$sql = "SELECT c.kd_rek4,c.nm_rek4,sum(a.$normal) as 'thn_ini',
									(
										select isnull(sum(d.$normal),0) from trdju_pkd d 
										join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
										where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,6)=c.kd_rek4 
										
									) as 'thn_lalu' 
									from trdju_pkd a 
									join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
									join ms_rek4 c on LEFT(a.kd_rek6,6)=c.kd_rek4
									where (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
									group by c.kd_rek4,c.nm_rek4";

					$query = $this->db->query($sql);
					foreach ($query->result() as $row) {
						$no = $no + 1;
						$nama = $row->nm_rek4;
						$nilai = $this->custom->rp_minus($row->thn_ini);
						$nilai_lalu = $this->custom->rp_minus($row->thn_lalu);
						$real_nilai1 = $this->custom->rp_minus($row->thn_ini - $row->thn_lalu);
						if ($row->thn_lalu == '' or $row->thn_lalu == 0) {
							$persen1 = '0,00';
						} else {
							$persen1 = ($row->thn_ini / $row->thn_lalu) * 100;
							$persen1 = number_format($persen1, "2", ",", ".");
						}

						$cRet    .= "<tr>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td colspan =\"2\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
											</tr>";
					}
					//===================== End Objek
					break;
				case 4:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>  
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                    
                                     <td colspan =\"2\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo88$nil1$lo89</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo99$nil_lalu1$lo98</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo0$real_nilai1$lo00</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen1</b></td>
                                 </tr>";
					break;
				case 5:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo1$surplus_lo1$lo2</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo3$surplus_lo_lalu1$lo4</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo5$selisih_surplus_lo1$lo6</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen2</b></td>
                                 </tr>";
					break;
				case 6:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo7$surplus_def_lo1$lo8</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo9$surplus_def_lo_lalu1$lo10</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_def1$lo12</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen3</b></td>
                                 </tr>";
					break;
				case 7:
					foreach ($arr_parent as $key => $value) {
						if ($value['parent'] == $seq) {
							$surplus_lo += $value['nilai'];
							$surplus_lo_thnlalu += $value['nilai_lalu'];
						}
					}
					$selisih_surplus_lo = $surplus_lo - $surplus_lo_thnlalu;

					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo7$surplus_def_lo1$lo8</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo9$surplus_def_lo_lalu1$lo10</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_def1$lo12</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen3</td>
                                 </tr>";
					break;
			}
		}

		$cRet         .= "</table>";
		//        $data['prev']  = $cRet;
		//        $data['sikap'] = 'preview';
		//        
		//        
		//        
		//        $this->template->set('title', 'LAPORAN OPERASIONAL'); 
		//        $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
		//$this->template->load('template','anggaran/rka/perkadaII',$data); 
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$tglttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}



		if ($nip == '00000000 000000 0 000') {
			$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
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
				<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
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
								<TD align="center" ><u><b>' . $namax . '</b></u></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $nip . '</TD>
							</TR>
							</TABLE><br/>';
		}
		$cRet .=       " </table>";
		$data['prev'] = $cRet;
		$data['sikap'] = 'preview';
		$judul  = ("LO KONSOL OBJEK $cbulan");
		$this->template->set('title', 'LO KONSOL OBJEK $cbulan');
		switch ($pilih) {
			case 1;
				// $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
				echo "<title>LO KONSOL OBJEK $cbulan</title>";
				echo $cRet;
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=$judul.xls");

				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
			case 3;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-word");
				header("Content-Disposition: attachment; filename=$judul.doc");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
			case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
				/* echo "<title>LO KONSOL $cbulan</title>";
			echo $cRet; */
				break;
		}
	}


	function ctk_lra_lo_pemda_rincian($cbulan = "", $pilih = "", $tglttd = "", $ttd = "")
	{
		//$id = $skpd;
		$this->load->library('custom');
		$cetak = '2'; //$ctk; 
		$tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
		$tglttd = str_replace('n', ' ', $tglttd);
		$id     = $this->session->userdata('kdskpd');
		$thn_ang = $this->session->userdata('pcThang');
		$thn_ang_1 = $thn_ang - 1;
		//$laporan=$lap; 

		// if ($cetak == '1') {
		//           $skpd = '';
		//           $skpd1 = '';           
		//       } else {             
		$skpd = "AND kd_skpd='$id'";
		$skpd1 = "AND b.kd_skpd='$id'";
		//}  

		$y123 = ")";
		$x123 = "(";
		$sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$id'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$nmskpd     = $rowsc->nm_skpd;
		}
		$nm_skpd = strtoupper($nmskpd);
		// INSERT DATA

		$sqldns = "SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$id'";
		$sqlskpd = $this->db->query($sqldns);
		foreach ($sqlskpd->result() as $rowdns) {
			$kd_urusan = $rowdns->kd_u;
			$nm_urusan = $rowdns->nm_u;
			$kd_skpd  = $rowdns->kd_sk;
			$nm_skpd  = $rowdns->nm_sk;
		}

		// created by henri_tb

		$sqllo1 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73')";
		$querylo1 = $this->db->query($sqllo1);
		$penlo = $querylo1->row();
		$pen_lo = $penlo->nilai;
		$pen_lo1 = number_format($penlo->nilai, "2", ",", ".");

		$sqllo2 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73')";
		$querylo2 = $this->db->query($sqllo2);
		$penlo2 = $querylo2->row();
		$pen_lo_lalu = $penlo2->nilai;
		$pen_lo_lalu1 = number_format($penlo2->nilai, "2", ",", ".");

		$sqllo3 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83','84')";
		$querylo3 = $this->db->query($sqllo3);
		$bello = $querylo3->row();
		$bel_lo = $bello->nilai;
		$bel_lo1 = number_format($bello->nilai, "2", ",", ".");

		$sqllo4 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','84')";
		$querylo4 = $this->db->query($sqllo4);
		$bello2 = $querylo4->row();
		$bel_lo_lalu = $bello2->nilai;
		$bel_lo_lalu1 = number_format($bello2->nilai, "2", ",", ".");

		// calvin 11 february 2023

		$sqllo5 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74','85')";
		$querylo5 = $this->db->query($sqllo5);
		$def5 = $querylo5->row();
		$def_lo = $def5->nilai;
		$def_lo1 = number_format($def5->nilai, "2", ",", ".");

		$sqllo6 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74','83')";
		$querylo6 = $this->db->query($sqllo6);
		$def6 = $querylo6->row();
		$del_lo_lalu = $def6->nilai;
		$del_lo_lalu1 = number_format($def6->nilai, "2", ",", ".");

		// end
		$surplus_lo = $pen_lo - $bel_lo;
		if ($surplus_lo < 0) {
			$lo1 = "(";
			$surplus_lox = $surplus_lo * -1;
			$lo2 = ")";
		} else {
			$lo1 = "";
			$surplus_lox = $surplus_lo;
			$lo2 = "";
		}
		$surplus_lo1 = number_format($surplus_lox, "2", ",", ".");

		$surplus_lo_lalu = $pen_lo_lalu - $bel_lo_lalu;
		if ($surplus_lo_lalu < 0) {
			$lo3 = "(";
			$surplus_lo_lalux = $surplus_lo_lalu * -1;
			$lo4 = ")";
		} else {
			$lo3 = "";
			$surplus_lo_lalux = $surplus_lo_lalu;
			$lo4 = "";
		}
		$surplus_lo_lalu1 = number_format($surplus_lo_lalux, "2", ",", ".");

		// calvin 
		$surplus_def_lo = $surplus_lo - $def_lo;
		if ($surplus_def_lo < 0) {
			$lo96 = "(";
			$surplus_def = $surplus_def_lo * -1;
			$lo97 = ")";
		} else {
			$lo96 = "";
			$surplus_def = $surplus_def_lo;
			$lo97 = "";
		}
		$surplus_def_lo1 = number_format($surplus_def, "2", ",", ".");

		$surplus_def_lo_lalu = $surplus_lo_lalu + $del_lo_lalu;
		if ($surplus_def_lo_lalu < 0) {
			$lo99 = "(";
			$surplus_def_lalux = $surplus_def_lo_lalu * -1;
			$lo98 = ")";
		} else {
			$lo99 = "";
			$surplus_def_lalux = $surplus_def_lo_lalu;
			$lo98 = "";
		}
		$surplus_def_lo_lalu1 = number_format($surplus_def_lalux, "2", ",", ".");


		$selisih_surplus_def = $surplus_def_lo - $surplus_def_lo_lalu;
		if ($selisih_surplus_def < 0) {
			$lo100 = "(";
			$selisih_surplus_defx = $selisih_surplus_def * -1;
			$lo101 = ")";
		} else {
			$lo100 = "";
			$selisih_surplus_defx = $selisih_surplus_def;
			$lo101 = "";
		}
		$selisih_surplus_def1 = number_format($selisih_surplus_defx, "2", ",", ".");

		if ($surplus_def_lo_lalu == '' or $surplus_def_lo_lalu == 0) {
			$persen100 = '0,00';
		} else {
			$persen100 = ($surplus_def_lo / $surplus_def_lo_lalu) * 100;
			$persen100 = number_format($persen100, "2", ",", ".");
		}

		//end

		$selisih_surplus_lo = $surplus_lo - $surplus_lo_lalu;
		if ($selisih_surplus_lo < 0) {
			$lo5 = "(";
			$selisih_surplus_lox = $selisih_surplus_lo * -1;
			$lo6 = ")";
		} else {
			$lo5 = "";
			$selisih_surplus_lox = $selisih_surplus_lo;
			$lo6 = "";
		}
		$selisih_surplus_lo1 = number_format($selisih_surplus_lox, "2", ",", ".");

		if ($surplus_lo_lalu == '' or $surplus_lo_lalu == 0) {
			$persen2 = '0,00';
		} else {
			$persen2 = ($surplus_lo / $surplus_lo_lalu) * 100;
			$persen2 = number_format($persen2, "2", ",", ".");
		}

		$sqllo5 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73','74')";
		$querylo5 = $this->db->query($sqllo5);
		$penlo3 = $querylo5->row();
		$pen_lo3 = $penlo3->nilai;
		$pen_lo31 = number_format($penlo3->nilai, "2", ",", ".");

		$sqllo6 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73','74')";
		$querylo6 = $this->db->query($sqllo6);
		$penlo4 = $querylo6->row();
		$pen_lo_lalu4 = $penlo4->nilai;
		$pen_lo_lalu41 = number_format($penlo4->nilai, "2", ",", ".");

		$sqllo7 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83')";
		$querylo7 = $this->db->query($sqllo7);
		$bello5 = $querylo7->row();
		$bel_lo5 = $bello5->nilai;
		$bel_lo51 = number_format($bello5->nilai, "2", ",", ".");

		$sqllo8 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','83')";
		$querylo8 = $this->db->query($sqllo8);
		$bello6 = $querylo8->row();
		$bel_lo_lalu6 = $bello6->nilai;
		$bel_lo_lalu61 = number_format($bello6->nilai, "2", ",", ".");

		$surplus_lo2 = $pen_lo3 - $bel_lo5;
		if ($surplus_lo2 < 0) {
			$lo7 = "(";
			$surplus_lo2x = $surplus_lo2 * -1;
			$lo8 = ")";
		} else {
			$lo7 = "";
			$surplus_lo2x = $surplus_lo2;
			$lo8 = "";
		}
		$surplus_lo21 = number_format($surplus_lo2x, "2", ",", ".");

		$surplus_lo_lalu2 = $pen_lo_lalu4 - $bel_lo_lalu6;
		if ($surplus_lo_lalu2 < 0) {
			$lo9 = "(";
			$surplus_lo_lalu2x = $surplus_lo_lalu2 * -1;
			$lo10 = ")";
		} else {
			$lo9 = "";
			$surplus_lo_lalu2x = $surplus_lo_lalu2;
			$lo10 = "";
		}
		$surplus_lo_lalu21 = number_format($surplus_lo_lalu2x, "2", ",", ".");

		$selisih_surplus_lo2 = $surplus_lo2 - $surplus_lo_lalu2;
		if ($selisih_surplus_lo2 < 0) {
			$lo11 = "(";
			$selisih_surplus_lo2x = $selisih_surplus_lo2 * -1;
			$lo12 = ")";
		} else {
			$lo11 = "";
			$selisih_surplus_lo2x = $selisih_surplus_lo2;
			$lo12 = "";
		}
		$selisih_surplus_lo21 = number_format($selisih_surplus_lo2x, "2", ",", ".");

		if ($surplus_lo_lalu2 == '' or $surplus_lo_lalu2 == 0) {
			$persen3 = '0,00';
		} else {
			$persen3 = ($surplus_lo2 / $surplus_lo_lalu2) * 100;
			$persen3 = number_format($persen3, "2", ",", ".");
		}

		$sqllo9 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('7')";
		$querylo9 = $this->db->query($sqllo9);
		$penlo7 = $querylo9->row();
		$pen_lo7 = $penlo7->nilai;
		$pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

		$sqllo10 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7')";
		$querylo10 = $this->db->query($sqllo10);
		$penlo8 = $querylo10->row();
		$pen_lo_lalu8 = $penlo8->nilai;
		$pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

		$sqllo11 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('8')";
		$querylo11 = $this->db->query($sqllo11);
		$bello9 = $querylo11->row();
		$bel_lo9 = $bello9->nilai;
		$bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8')";
		$querylo12 = $this->db->query($sqllo12);
		$bello10 = $querylo12->row();
		$bel_lo_lalu10 = $bello10->nilai;
		$bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

		$surplus_lo3 = $pen_lo7 - $bel_lo9;
		if ($surplus_lo3 < 0) {
			$lo13 = "(";
			$surplus_lo3x = $surplus_lo3 * -1;
			$lo14 = ")";
		} else {
			$lo13 = "";
			$surplus_lo3x = $surplus_lo3;
			$lo14 = "";
		}
		$surplus_lo31 = number_format($surplus_lo3x, "2", ",", ".");

		$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
		if ($surplus_lo_lalu3 < 0) {
			$lo15 = "(";
			$surplus_lo_lalu3x = $surplus_lo_lalu3 * -1;
			$lo16 = ")";
		} else {
			$lo15 = "";
			$surplus_lo_lalu3x = $surplus_lo_lalu3;
			$lo16 = "";
		}
		$surplus_lo_lalu31 = number_format($surplus_lo_lalu3x, "2", ",", ".");

		$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
		if ($selisih_surplus_lo3 < 0) {
			$lo17 = "(";
			$selisih_surplus_lo3x = $selisih_surplus_lo3 * -1;
			$lo18 = ")";
		} else {
			$lo17 = "";
			$selisih_surplus_lo3x = $selisih_surplus_lo3;
			$lo18 = "";
		}
		$selisih_surplus_lo31 = number_format($selisih_surplus_lo3x, "2", ",", ".");

		if ($surplus_lo_lalu3 == '' or $surplus_lo_lalu3 == 0) {
			$persen4 = '0,00';
		} else {
			$persen4 = ($surplus_lo3 / $surplus_lo_lalu3) * 100;
			$persen4 = number_format($persen4, "2", ",", ".");
		}


		$sqllo13 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74')";
		$querylo13 = $this->db->query($sqllo13);
		$penlo11 = $querylo13->row();
		$pen_lo11 = $penlo11->nilai;
		$pen_lo111 = number_format($penlo11->nilai, "2", ",", ".");

		$sqllo14 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74')";
		$querylo14 = $this->db->query($sqllo14);
		$penlo12 = $querylo14->row();
		$pen_lo_lalu12 = $penlo12->nilai;
		$pen_lo_lalu121 = number_format($penlo12->nilai, "2", ",", ".");

		$sqllo15 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('85')";
		$querylo15 = $this->db->query($sqllo15);
		$bello13 = $querylo15->row();
		$bel_lo13 = $bello13->nilai;
		$bel_lo131 = number_format($bello13->nilai, "2", ",", ".");

		$sqllo16 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('83')";
		$querylo16 = $this->db->query($sqllo16);
		$bello14 = $querylo16->row();
		$bel_lo_lalu14 = $bello14->nilai;
		$bel_lo_lalu141 = number_format($bello14->nilai, "2", ",", ".");

		$surplus_lo4 = $pen_lo11 - $bel_lo13;
		if ($surplus_lo4 < 0) {
			$lo19 = "(";
			$surplus_lo4x = $surplus_lo4 * -1;
			$lo20 = ")";
		} else {
			$lo19 = "";
			$surplus_lo4x = $surplus_lo4;
			$lo20 = "";
		}
		$surplus_lo41 = number_format($surplus_lo4x, "2", ",", ".");

		$surplus_lo_lalu4 = $pen_lo_lalu12 - $bel_lo_lalu14;
		if ($surplus_lo_lalu4 < 0) {
			$lo21 = "(";
			$surplus_lo_lalu4x = $surplus_lo_lalu4 * -1;
			$lo22 = ")";
		} else {
			$lo21 = "";
			$surplus_lo_lalu4x = $surplus_lo_lalu4;
			$lo22 = "";
		}
		$surplus_lo_lalu41 = number_format($surplus_lo_lalu4x, "2", ",", ".");

		$selisih_surplus_lo4 = $surplus_lo4 - $surplus_lo_lalu4;
		if ($selisih_surplus_lo4 < 0) {
			$lo23 = "(";
			$selisih_surplus_lo4x = $selisih_surplus_lo4 * -1;
			$lo24 = ")";
		} else {
			$lo23 = "";
			$selisih_surplus_lo4x = $selisih_surplus_lo4;
			$lo24 = "";
		}
		$selisih_surplus_lo41 = number_format($selisih_surplus_lo4x, "2", ",", ".");

		if ($surplus_lo_lalu4 == '' or $surplus_lo_lalu4 == 0) {
			$persen5 = '0,00';
		} else {
			$persen5 = ($surplus_lo4 / $surplus_lo_lalu4) * 100;
			$persen5 = number_format($persen5, "2", ",", ".");
		}


		$sqllo17 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('75')";
		$querylo17 = $this->db->query($sqllo17);
		$penlo15 = $querylo17->row();
		$pen_lo15 = $penlo15->nilai;
		$pen_lo151 = number_format($penlo15->nilai, "2", ",", ".");

		$sqllo18 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('75')";
		$querylo18 = $this->db->query($sqllo18);
		$penlo16 = $querylo18->row();
		$pen_lo_lalu16 = $penlo16->nilai;
		$pen_lo_lalu161 = number_format($penlo16->nilai, "2", ",", ".");

		$sqllo19 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('84')";
		$querylo19 = $this->db->query($sqllo19);
		$bello17 = $querylo19->row();
		$bel_lo17 = $bello17->nilai;
		$bel_lo171 = number_format($bello17->nilai, "2", ",", ".");

		$sqllo20 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('84')";
		$querylo20 = $this->db->query($sqllo20);
		$bello18 = $querylo20->row();
		$bel_lo_lalu18 = $bello18->nilai;
		$bel_lo_lalu181 = number_format($bello18->nilai, "2", ",", ".");

		$surplus_lo5 = $pen_lo15 - $bel_lo17;
		if ($surplus_lo5 < 0) {
			$lo25 = "(";
			$surplus_lo5x = $surplus_lo5 * -1;
			$lo26 = ")";
		} else {
			$lo25 = "";
			$surplus_lo5x = $surplus_lo5;
			$lo26 = "";
		}
		$surplus_lo51 = number_format($surplus_lo5x, "2", ",", ".");

		$surplus_lo_lalu5 = $pen_lo_lalu16 - $bel_lo_lalu18;
		if ($surplus_lo_lalu5 < 0) {
			$lo27 = "(";
			$surplus_lo_lalu5x = $surplus_lo_lalu5 * -1;
			$lo28 = ")";
		} else {
			$lo27 = "";
			$surplus_lo_lalu5x = $surplus_lo_lalu5;
			$lo28 = "";
		}
		$surplus_lo_lalu51 = number_format($surplus_lo_lalu5x, "2", ",", ".");

		$selisih_surplus_lo5 = $surplus_lo5 - $surplus_lo_lalu5;
		if ($selisih_surplus_lo5 < 0) {
			$lo29 = "(";
			$selisih_surplus_lo5x = $selisih_surplus_lo5 * -1;
			$lo30 = ")";
		} else {
			$lo29 = "";
			$selisih_surplus_lo5x = $selisih_surplus_lo5;
			$lo30 = "";
		}
		$selisih_surplus_lo51 = number_format($selisih_surplus_lo5x, "2", ",", ".");

		if ($surplus_lo_lalu5 == '' or $surplus_lo_lalu5 == 0) {
			$persen6 = '0,00';
		} else {
			$persen6 = ($surplus_lo5 / $surplus_lo_lalu5) * 100;
			$persen6 = number_format($persen6, "2", ",", ".");
		}


		$modtahun = $thn_ang % 4;

		if ($modtahun = 0) {
			$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		} else {
			$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		$cRet = '';

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
						<td rowspan=\"3\" align=\"left\" width=\"2%\">
						<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"60\" height=\"70\" />
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";

		$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NO</b></td>                            
                            <td colspan = \"5\" bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>Kenaikan /</br>(Penurunan)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>
                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td colspan = \"5\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>";

		$sqlmaplo = "SELECT seq, nor, uraian, bold,isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak ,parent,kode FROM map_lo_pemda 
				   GROUP BY seq, nor, uraian, bold,isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet'), parent ,kode ORDER BY seq";

		$querymaplo = $this->db->query($sqlmaplo);
		$no     = 0;
		$arr_parent = [];
		$surplus_lo = 0;
		$surplus_lo_thnlalu = 0;
		$selisih_surplus_lo = 0;

		foreach ($querymaplo->result() as $loquery) {
			$operator = $loquery->kode;
			$seq = $loquery->seq;
			$nama      = $loquery->uraian;
			$n         = $loquery->kode_1;
			$n		   = ($n == "-" ? "'-'" : $n);
			$n2        = $loquery->kode_2;
			$n2		   = ($n2 == "-" ? "'-'" : $n2);
			$n3        = $loquery->kode_3;
			$n3		   = ($n3 == "-" ? "'-'" : $n3);
			$normal    = $loquery->cetak;
			$bold      = $loquery->bold;

			if ($normal != "") {
				$quelo01   = "SELECT SUM($normal) as nilai FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan";
				$quelo02 = $this->db->query($quelo01);
				$quelo03 = $quelo02->row();
				$nil     = $quelo03->nilai;
				$nilai    = number_format($quelo03->nilai, "2", ",", ".");

				$quelo04   = "SELECT SUM($normal) as nilai FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang_1";
				$quelo05 = $this->db->query($quelo04);
				$quelo06 = $quelo05->row();
				$nil_lalu     = $quelo06->nilai;
				$nilai_lalu    = number_format($quelo06->nilai, "2", ",", ".");

				if ($nil < 0) {
					$lo88 = "(";
					$nilx = $nil * -1;
					$lo89 = ")";
				} else {
					$lo88 = "";
					$nilx = $nil;
					$lo89 = "";
				}
				$nil1 = number_format($nilx, "2", ",", ".");

				if ($nil_lalu < 0) {
					$lo99 = "(";
					$nil_lalux = $nil_lalu * -1;
					$lo98 = ")";
				} else {
					$lo99 = "";
					$nil_lalux = $nil_lalu;
					$lo98 = "";
				}
				$nil_lalu1 = number_format($nil_lalux, "2", ",", ".");

				$real_nilai = $nil - $nil_lalu;
				if ($real_nilai < 0) {
					$lo0 = "(";
					$real_nilaix = $real_nilai * -1;
					$lo00 = ")";
				} else {
					$lo0 = "";
					$real_nilaix = $real_nilai;
					$lo00 = "";
				}
				$real_nilai1 = number_format($real_nilaix, "2", ",", ".");

				if ($nil_lalu == '' or $nil_lalu == 0) {
					$persen1 = '0,00';
				} else {
					$persen1 = ($nil / $nil_lalu) * 100;
					$persen1 = number_format($persen1, "2", ",", ".");
				}
				if ($loquery->parent) {
					$arr_parent[] = array(
						"seq" => $seq,
						"parent" => $loquery->parent,
						"uraian" => $nama,
						"nilai" => ($operator == '-') ? $nil * -1 : $nil,
						"nilai_lalu" => ($operator == '-') ? $nil_lalu  * -1 : $nil_lalu
					);
				}
			}

			$no       = $no + 1;
			switch ($loquery->bold) {
				case 0:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td colspan =\"5\"  style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 1:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 2:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>   
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                         
                                     <td colspan =\"4\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
									 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 3:
					$cRet    .= "<tr>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td> 
										 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
										 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                  
										 <td colspan =\"3\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo88$nil1$lo89</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo99$nil_lalu1$lo98</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo0$real_nilai1$lo00</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persen1</td>
									 </tr>";
					//===================== Objek  / rincian       
					$sql = "SELECT c.kd_rek4 as kd_rek,c.nm_rek4 as nm_rek,sum(a.$normal) as 'thn_ini',
											(
												select isnull(sum(d.$normal),0) from trdju_pkd d 
												join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
												where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,6)=c.kd_rek4 
												
											) as 'thn_lalu' 
											from trdju_pkd a 
											join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
											join ms_rek4 c on LEFT(a.kd_rek6,6)=c.kd_rek4
											where (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
											group by c.kd_rek4,c.nm_rek4
											union all
											SELECT c.kd_rek5 as kd_rek,c.nm_rek5 as nm_rek,sum(a.$normal) as 'thn_ini',
											(
												select isnull(sum(d.$normal),0) from trdju_pkd d 
												join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
												where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,8)=c.kd_rek5 
												
											) as 'thn_lalu' 
											from trdju_pkd a 
											join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
											join ms_rek5 c on LEFT(a.kd_rek6,8)=c.kd_rek5
											where (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
											group by c.kd_rek5,c.nm_rek5
											order by kd_rek";

					$query = $this->db->query($sql);
					foreach ($query->result() as $row) {
						$no = $no + 1;
						$nama = $row->nm_rek;
						$nilai = $this->custom->rp_minus($row->thn_ini);
						$nilai_lalu = $this->custom->rp_minus($row->thn_lalu);
						$real_nilai1 = $this->custom->rp_minus($row->thn_ini - $row->thn_lalu);
						if ($row->thn_lalu == '' or $row->thn_lalu == 0) {
							$persen1 = '0,00';
						} else {
							$persen1 = ($row->thn_ini / $row->thn_lalu) * 100;
							$persen1 = number_format($persen1, "2", ",", ".");
						}

						if (strlen($row->kd_rek) == 6) { //objek
							$cRet    .= "<tr>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
													<td colspan =\"2\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
												</tr>";
						} else {
							$cRet    .= "<tr>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
													<td colspan =\"1\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
													<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
												</tr>";
						}
					}
					//===================== End Objek
					break;
				case 4:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>  
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                    
                                     <td colspan =\"2\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo88$nil1$lo89</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo99$nil_lalu1$lo98</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo0$real_nilai1$lo00</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen1</b></td>
                                 </tr>";
					break;
				case 5:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo1$surplus_lo1$lo2</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo3$surplus_lo_lalu1$lo4</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo5$selisih_surplus_lo1$lo6</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen2</b></td>
                                 </tr>";
					break;
				case 6:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo7$surplus_def_lo1$lo8</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo9$surplus_def_lo_lalu1$lo10</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_def1$lo12</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen3</b></td>
                                 </tr>";
					break;
				case 7:
					foreach ($arr_parent as $key => $value) {
						if ($value['parent'] == $seq) {
							$surplus_lo += $value['nilai'];
							$surplus_lo_thnlalu += $value['nilai_lalu'];
						}
					}
					$selisih_surplus_lo = $surplus_lo - $surplus_lo_thnlalu;

					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo7$surplus_def_lo1$lo8</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo9$surplus_def_lo_lalu1$lo10</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_def1$lo12</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen3</td>
                                 </tr>";
					break;
			}
		}

		$cRet         .= "</table>";
		//        $data['prev']  = $cRet;
		//        $data['sikap'] = 'preview';
		//        
		//        
		//        
		//        $this->template->set('title', 'LAPORAN OPERASIONAL'); 
		//        $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
		//$this->template->load('template','anggaran/rka/perkadaII',$data); 
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$tglttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}



		if ($nip == '00000000 000000 0 000') {
			$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
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
				<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
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
								<TD align="center" ><u><b>' . $namax . '</b></u></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $nip . '</TD>
							</TR>
							</TABLE><br/>';
		}
		$cRet .=       " </table>";
		$data['prev'] = $cRet;
		$data['sikap'] = 'preview';
		$judul  = ("LO KONSOL RINCIAN $cbulan");
		$this->template->set('title', 'LO KONSOL RINCIAN $cbulan');
		switch ($pilih) {
			case 1;
				// $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
				echo "<title>LO KONSOL RINCIAN $cbulan</title>";
				echo $cRet;
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=$judul.xls");

				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
			case 3;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-word");
				header("Content-Disposition: attachment; filename=$judul.doc");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
			case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
				/* echo "<title>LO KONSOL $cbulan</title>";
			echo $cRet; */
				break;
		}
	}

	// andika (penambahan lo persub rincian)
	
function ctk_lra_lo_pemda_subrincian($cbulan = "", $pilih = "",$tglttd = "", $ttd = "")
{
	//$id = $skpd;
	$this->load->library('custom');
	$cetak = '2'; //$ctk; 
	$tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
	$tglttd = str_replace('n', ' ', $tglttd);
	$id     = $this->session->userdata('kdskpd');
	$thn_ang = $this->session->userdata('pcThang');
	$thn_ang_1 = $thn_ang - 1;
	//$laporan=$lap; 

	// if ($cetak == '1') {
	//           $skpd = '';
	//           $skpd1 = '';           
	//       } else {             
	$skpd = "AND kd_skpd='$id'";
	$skpd1 = "AND b.kd_skpd='$id'";
	//}  

	$y123 = ")";
	$x123 = "(";
	$sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$id'";
	$sqlsclient = $this->db->query($sqlsc);
	foreach ($sqlsclient->result() as $rowsc) {
		$nmskpd     = $rowsc->nm_skpd;
	}
	$nm_skpd = strtoupper($nmskpd);
	// INSERT DATA

	$sqldns = "SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$id'";
	$sqlskpd = $this->db->query($sqldns);
	foreach ($sqlskpd->result() as $rowdns) {
		$kd_urusan = $rowdns->kd_u;
		$nm_urusan = $rowdns->nm_u;
		$kd_skpd  = $rowdns->kd_sk;
		$nm_skpd  = $rowdns->nm_sk;
	}

	// created by henri_tb

	$sqllo1 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73')";
	$querylo1 = $this->db->query($sqllo1);
	$penlo = $querylo1->row();
	$pen_lo = $penlo->nilai;
	$pen_lo1 = number_format($penlo->nilai, "2", ",", ".");

	$sqllo2 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73')";
	$querylo2 = $this->db->query($sqllo2);
	$penlo2 = $querylo2->row();
	$pen_lo_lalu = $penlo2->nilai;
	$pen_lo_lalu1 = number_format($penlo2->nilai, "2", ",", ".");

	$sqllo3 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','84')";
	$querylo3 = $this->db->query($sqllo3);
	$bello = $querylo3->row();
	$bel_lo = $bello->nilai;
	$bel_lo1 = number_format($bello->nilai, "2", ",", ".");

	$sqllo4 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','84')";
	$querylo4 = $this->db->query($sqllo4);
	$bello2 = $querylo4->row();
	$bel_lo_lalu = $bello2->nilai;
	$bel_lo_lalu1 = number_format($bello2->nilai, "2", ",", ".");
	
	// calvin 11 february 2022
				
	$sqllo5 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74','83')";
	$querylo5 = $this->db->query($sqllo5);
	$def5 = $querylo5->row();
	$def_lo = $def5->nilai;
	$def_lo1 = number_format($def5->nilai, "2", ",", ".");

	$sqllo6 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74','83')";
	$querylo6 = $this->db->query($sqllo6);
	$def6 = $querylo6->row();
	$del_lo_lalu = $def6->nilai;
	$del_lo_lalu1 = number_format($def6->nilai, "2", ",", ".");

	// end
	$surplus_lo = $pen_lo - $bel_lo;
	if ($surplus_lo < 0) {
		$lo1 = "(";
		$surplus_lox = $surplus_lo * -1;
		$lo2 = ")";
	} else {
		$lo1 = "";
		$surplus_lox = $surplus_lo;
		$lo2 = "";
	}
	$surplus_lo1 = number_format($surplus_lox, "2", ",", ".");

	$surplus_lo_lalu = $pen_lo_lalu - $bel_lo_lalu;
	if ($surplus_lo_lalu < 0) {
		$lo3 = "(";
		$surplus_lo_lalux = $surplus_lo_lalu * -1;
		$lo4 = ")";
	} else {
		$lo3 = "";
		$surplus_lo_lalux = $surplus_lo_lalu;
		$lo4 = "";
	}
	$surplus_lo_lalu1 = number_format($surplus_lo_lalux, "2", ",", ".");

	// calvin 
	$surplus_def_lo = $surplus_lo - $def_lo;
	if ($surplus_def_lo < 0) {
		$lo96 = "(";
		$surplus_def= $surplus_def_lo * -1;
		$lo97 = ")";
	} else {
		$lo96 = "";
		$surplus_def = $surplus_def_lo;
		$lo97 = "";
	}
	$surplus_def_lo1 = number_format($surplus_def, "2", ",", ".");
	
	$surplus_def_lo_lalu = $surplus_lo_lalu - $del_lo_lalu;
	if ($surplus_def_lo_lalu < 0) {
		$lo99 = "(";
		$surplus_def_lalux = $surplus_def_lo_lalu * -1;
		$lo98 = ")";
	} else {
		$lo99 = "";
		$surplus_def_lalux = $surplus_def_lo_lalu;
		$lo98 = "";
	}
	$surplus_def_lo_lalu1 = number_format($surplus_def_lalux, "2", ",", ".");

	
	$selisih_surplus_def = $surplus_def_lo - $surplus_def_lo_lalu;
	if ($selisih_surplus_def < 0){
		$lo100 = "(";
		$selisih_surplus_defx = $selisih_surplus_def * -1;
		$lo101 = ")";
	} else {
		$lo100 = "";
		$selisih_surplus_defx = $selisih_surplus_def;
		$lo101 = "";
	}
	$selisih_surplus_def1 = number_format($selisih_surplus_defx, "2", ",",".");
	
	if ($surplus_def_lo_lalu == '' or $surplus_def_lo_lalu == 0) {
		$persen100 = '0,00';
	} else {
		$persen100 = ($surplus_def_lo / $surplus_def_lo_lalu) * 100;
		$persen100 = number_format($persen100, "2", ",", ".");
	}
	
	//end

	$selisih_surplus_lo = $surplus_lo - $surplus_lo_lalu;
	if ($selisih_surplus_lo < 0) {
		$lo5 = "(";
		$selisih_surplus_lox = $selisih_surplus_lo * -1;
		$lo6 = ")";
	} else {
		$lo5 = "";
		$selisih_surplus_lox = $selisih_surplus_lo;
		$lo6 = "";
	}
	$selisih_surplus_lo1 = number_format($selisih_surplus_lox, "2", ",", ".");

	if ($surplus_lo_lalu == '' or $surplus_lo_lalu == 0) {
		$persen2 = '0,00';
	} else {
		$persen2 = ($surplus_lo / $surplus_lo_lalu) * 100;
		$persen2 = number_format($persen2, "2", ",", ".");
	}

	$sqllo5 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73','74')";
	$querylo5 = $this->db->query($sqllo5);
	$penlo3 = $querylo5->row();
	$pen_lo3 = $penlo3->nilai;
	$pen_lo31 = number_format($penlo3->nilai, "2", ",", ".");

	$sqllo6 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73','74')";
	$querylo6 = $this->db->query($sqllo6);
	$penlo4 = $querylo6->row();
	$pen_lo_lalu4 = $penlo4->nilai;
	$pen_lo_lalu41 = number_format($penlo4->nilai, "2", ",", ".");

	$sqllo7 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83')";
	$querylo7 = $this->db->query($sqllo7);
	$bello5 = $querylo7->row();
	$bel_lo5 = $bello5->nilai;
	$bel_lo51 = number_format($bello5->nilai, "2", ",", ".");

	$sqllo8 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','83')";
	$querylo8 = $this->db->query($sqllo8);
	$bello6 = $querylo8->row();
	$bel_lo_lalu6 = $bello6->nilai;
	$bel_lo_lalu61 = number_format($bello6->nilai, "2", ",", ".");

	$surplus_lo2 = $pen_lo3 - $bel_lo5;
	if ($surplus_lo2 < 0) {
		$lo7 = "(";
		$surplus_lo2x = $surplus_lo2 * -1;
		$lo8 = ")";
	} else {
		$lo7 = "";
		$surplus_lo2x = $surplus_lo2;
		$lo8 = "";
	}
	$surplus_lo21 = number_format($surplus_lo2x, "2", ",", ".");

	$surplus_lo_lalu2 = $pen_lo_lalu4 - $bel_lo_lalu6;
	if ($surplus_lo_lalu2 < 0) {
		$lo9 = "(";
		$surplus_lo_lalu2x = $surplus_lo_lalu2 * -1;
		$lo10 = ")";
	} else {
		$lo9 = "";
		$surplus_lo_lalu2x = $surplus_lo_lalu2;
		$lo10 = "";
	}
	$surplus_lo_lalu21 = number_format($surplus_lo_lalu2x, "2", ",", ".");

	$selisih_surplus_lo2 = $surplus_lo2 - $surplus_lo_lalu2;
	if ($selisih_surplus_lo2 < 0) {
		$lo11 = "(";
		$selisih_surplus_lo2x = $selisih_surplus_lo2 * -1;
		$lo12 = ")";
	} else {
		$lo11 = "";
		$selisih_surplus_lo2x = $selisih_surplus_lo2;
		$lo12 = "";
	}
	$selisih_surplus_lo21 = number_format($selisih_surplus_lo2x, "2", ",", ".");

	if ($surplus_lo_lalu2 == '' or $surplus_lo_lalu2 == 0) {
		$persen3 = '0,00';
	} else {
		$persen3 = ($surplus_lo2 / $surplus_lo_lalu2) * 100;
		$persen3 = number_format($persen3, "2", ",", ".");
	}

	$sqllo9 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('7')";
	$querylo9 = $this->db->query($sqllo9);
	$penlo7 = $querylo9->row();
	$pen_lo7 = $penlo7->nilai;
	$pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

	$sqllo10 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7')";
	$querylo10 = $this->db->query($sqllo10);
	$penlo8 = $querylo10->row();
	$pen_lo_lalu8 = $penlo8->nilai;
	$pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

	$sqllo11 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('8')";
	$querylo11 = $this->db->query($sqllo11);
	$bello9 = $querylo11->row();
	$bel_lo9 = $bello9->nilai;
	$bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

	$sqllo12 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8')";
	$querylo12 = $this->db->query($sqllo12);
	$bello10 = $querylo12->row();
	$bel_lo_lalu10 = $bello10->nilai;
	$bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

	$surplus_lo3 = $pen_lo7 - $bel_lo9;
	if ($surplus_lo3 < 0) {
		$lo13 = "(";
		$surplus_lo3x = $surplus_lo3 * -1;
		$lo14 = ")";
	} else {
		$lo13 = "";
		$surplus_lo3x = $surplus_lo3;
		$lo14 = "";
	}
	$surplus_lo31 = number_format($surplus_lo3x, "2", ",", ".");

	$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
	if ($surplus_lo_lalu3 < 0) {
		$lo15 = "(";
		$surplus_lo_lalu3x = $surplus_lo_lalu3 * -1;
		$lo16 = ")";
	} else {
		$lo15 = "";
		$surplus_lo_lalu3x = $surplus_lo_lalu3;
		$lo16 = "";
	}
	$surplus_lo_lalu31 = number_format($surplus_lo_lalu3x, "2", ",", ".");

	$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
	if ($selisih_surplus_lo3 < 0) {
		$lo17 = "(";
		$selisih_surplus_lo3x = $selisih_surplus_lo3 * -1;
		$lo18 = ")";
	} else {
		$lo17 = "";
		$selisih_surplus_lo3x = $selisih_surplus_lo3;
		$lo18 = "";
	}
	$selisih_surplus_lo31 = number_format($selisih_surplus_lo3x, "2", ",", ".");

	if ($surplus_lo_lalu3 == '' or $surplus_lo_lalu3 == 0) {
		$persen4 = '0,00';
	} else {
		$persen4 = ($surplus_lo3 / $surplus_lo_lalu3) * 100;
		$persen4 = number_format($persen4, "2", ",", ".");
	}


	$sqllo13 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74')";
	$querylo13 = $this->db->query($sqllo13);
	$penlo11 = $querylo13->row();
	$pen_lo11 = $penlo11->nilai;
	$pen_lo111 = number_format($penlo11->nilai, "2", ",", ".");

	$sqllo14 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74')";
	$querylo14 = $this->db->query($sqllo14);
	$penlo12 = $querylo14->row();
	$pen_lo_lalu12 = $penlo12->nilai;
	$pen_lo_lalu121 = number_format($penlo12->nilai, "2", ",", ".");

	$sqllo15 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('85')";
	$querylo15 = $this->db->query($sqllo15);
	$bello13 = $querylo15->row();
	$bel_lo13 = $bello13->nilai;
	$bel_lo131 = number_format($bello13->nilai, "2", ",", ".");

	$sqllo16 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('83')";
	$querylo16 = $this->db->query($sqllo16);
	$bello14 = $querylo16->row();
	$bel_lo_lalu14 = $bello14->nilai;
	$bel_lo_lalu141 = number_format($bello14->nilai, "2", ",", ".");

	$surplus_lo4 = $pen_lo11 - $bel_lo13;
	if ($surplus_lo4 < 0) {
		$lo19 = "(";
		$surplus_lo4x = $surplus_lo4 * -1;
		$lo20 = ")";
	} else {
		$lo19 = "";
		$surplus_lo4x = $surplus_lo4;
		$lo20 = "";
	}
	$surplus_lo41 = number_format($surplus_lo4x, "2", ",", ".");

	$surplus_lo_lalu4 = $pen_lo_lalu12 - $bel_lo_lalu14;
	if ($surplus_lo_lalu4 < 0) {
		$lo21 = "(";
		$surplus_lo_lalu4x = $surplus_lo_lalu4 * -1;
		$lo22 = ")";
	} else {
		$lo21 = "";
		$surplus_lo_lalu4x = $surplus_lo_lalu4;
		$lo22 = "";
	}
	$surplus_lo_lalu41 = number_format($surplus_lo_lalu4x, "2", ",", ".");

	$selisih_surplus_lo4 = $surplus_lo4 - $surplus_lo_lalu4;
	if ($selisih_surplus_lo4 < 0) {
		$lo23 = "(";
		$selisih_surplus_lo4x = $selisih_surplus_lo4 * -1;
		$lo24 = ")";
	} else {
		$lo23 = "";
		$selisih_surplus_lo4x = $selisih_surplus_lo4;
		$lo24 = "";
	}
	$selisih_surplus_lo41 = number_format($selisih_surplus_lo4x, "2", ",", ".");

	if ($surplus_lo_lalu4 == '' or $surplus_lo_lalu4 == 0) {
		$persen5 = '0,00';
	} else {
		$persen5 = ($surplus_lo4 / $surplus_lo_lalu4) * 100;
		$persen5 = number_format($persen5, "2", ",", ".");
	}


	$sqllo17 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('75')";
	$querylo17 = $this->db->query($sqllo17);
	$penlo15 = $querylo17->row();
	$pen_lo15 = $penlo15->nilai;
	$pen_lo151 = number_format($penlo15->nilai, "2", ",", ".");

	$sqllo18 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('75')";
	$querylo18 = $this->db->query($sqllo18);
	$penlo16 = $querylo18->row();
	$pen_lo_lalu16 = $penlo16->nilai;
	$pen_lo_lalu161 = number_format($penlo16->nilai, "2", ",", ".");

	$sqllo19 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('84')";
	$querylo19 = $this->db->query($sqllo19);
	$bello17 = $querylo19->row();
	$bel_lo17 = $bello17->nilai;
	$bel_lo171 = number_format($bello17->nilai, "2", ",", ".");

	$sqllo20 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('84')";
	$querylo20 = $this->db->query($sqllo20);
	$bello18 = $querylo20->row();
	$bel_lo_lalu18 = $bello18->nilai;
	$bel_lo_lalu181 = number_format($bello18->nilai, "2", ",", ".");

	$surplus_lo5 = $pen_lo15 - $bel_lo17;
	if ($surplus_lo5 < 0) {
		$lo25 = "(";
		$surplus_lo5x = $surplus_lo5 * -1;
		$lo26 = ")";
	} else {
		$lo25 = "";
		$surplus_lo5x = $surplus_lo5;
		$lo26 = "";
	}
	$surplus_lo51 = number_format($surplus_lo5x, "2", ",", ".");

	$surplus_lo_lalu5 = $pen_lo_lalu16 - $bel_lo_lalu18;
	if ($surplus_lo_lalu5 < 0) {
		$lo27 = "(";
		$surplus_lo_lalu5x = $surplus_lo_lalu5 * -1;
		$lo28 = ")";
	} else {
		$lo27 = "";
		$surplus_lo_lalu5x = $surplus_lo_lalu5;
		$lo28 = "";
	}
	$surplus_lo_lalu51 = number_format($surplus_lo_lalu5x, "2", ",", ".");

	$selisih_surplus_lo5 = $surplus_lo5 - $surplus_lo_lalu5;
	if ($selisih_surplus_lo5 < 0) {
		$lo29 = "(";
		$selisih_surplus_lo5x = $selisih_surplus_lo5 * -1;
		$lo30 = ")";
	} else {
		$lo29 = "";
		$selisih_surplus_lo5x = $selisih_surplus_lo5;
		$lo30 = "";
	}
	$selisih_surplus_lo51 = number_format($selisih_surplus_lo5x, "2", ",", ".");

	if ($surplus_lo_lalu5 == '' or $surplus_lo_lalu5 == 0) {
		$persen6 = '0,00';
	} else {
		$persen6 = ($surplus_lo5 / $surplus_lo_lalu5) * 100;
		$persen6 = number_format($persen6, "2", ",", ".");
	}


	$modtahun = $thn_ang % 4;

	if ($modtahun = 0) {
		$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
	} else {
		$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
	}

	$arraybulan = explode(".", $nilaibulan);

	$cRet = '';

	$sclient = $this->akuntansi_model->get_sclient();
	$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
				<tr>
					<td rowspan=\"3\" align=\"left\" width=\"2%\">
					<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"60\" height=\"70\" />
					 <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
				</tr>
				<tr>
					 <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
				</tr>                    
				<tr>
					 <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1</strong></td>
				</tr>
				<tr>
					 <td align=\"center\">&nbsp;</td>
				</tr>
			  </table>";

	$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
				 <thead>                       
					<tr><td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NO</b></td>                            
						<td colspan = \"6\" bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
						<td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
						<td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
						<td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>Kenaikan /</br>(Penurunan)</b></td>
						<td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
					</tr>
					
				 </thead>
			   
				 <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
						<td colspan = \"6\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
						<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
						<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
						<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
						<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
					</tr>";

	$sqlmaplo = "SELECT seq, nor, uraian, bold,isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3,isnull(kode_4,'-') as kode_4, isnull(cetak,'debet-debet') as cetak ,parent,kode FROM map_lo_pemda_subrinci 
			   GROUP BY seq, nor, uraian, bold,isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'),isnull(kode_4,'-'), isnull(cetak,'debet-debet'), parent ,kode ORDER BY seq";

	$querymaplo = $this->db->query($sqlmaplo);
	$no     = 0;
	$arr_parent = [];
	$surplus_lo = 0;
	$surplus_lo_thnlalu = 0;
	$selisih_surplus_lo = 0;

	foreach ($querymaplo->result() as $loquery) {
		$operator = $loquery->kode;
		$seq = $loquery->seq;
		$nama      = $loquery->uraian;
		$n         = $loquery->kode_1;
		$n		   = ($n == "-" ? "'-'" : $n);
		$n2        = $loquery->kode_2;
		$n2		   = ($n2 == "-" ? "'-'" : $n2);
		$n3        = $loquery->kode_3;
		$n3		   = ($n3 == "-" ? "'-'" : $n3);
		$n4        = $loquery->kode_4;
		$n4		   = ($n4 == "-" ? "'-'" : $n4);
		$normal    = $loquery->cetak;
		$bold      = $loquery->bold;

		if ($normal != "") {
		$quelo01   = "SELECT SUM($normal) as nilai FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3) or left(kd_rek6,12) in ($n4)) and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan";
		$quelo02 = $this->db->query($quelo01);
		$quelo03 = $quelo02->row();
		$nil     = $quelo03->nilai;
		$nilai    = number_format($quelo03->nilai, "2", ",", ".");

		$quelo04   = "SELECT SUM($normal) as nilai FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3) or left(kd_rek6,12) in ($n4)) and year(tgl_voucher)=$thn_ang_1";
		$quelo05 = $this->db->query($quelo04);
		$quelo06 = $quelo05->row();
		$nil_lalu     = $quelo06->nilai;
		$nilai_lalu    = number_format($quelo06->nilai, "2", ",", ".");

		if($nil < 0){
			$lo88 = "(";
			$nilx = $nil * -1;
			$lo89 = ")";
		} else {
			$lo88 = "";
			$nilx = $nil;
			$lo89 = "";
		}
		$nil1 = number_format($nilx, "2", ",", ".");

		if($nil_lalu < 0){
			$lo99 = "(";
			$nil_lalux = $nil_lalu * -1;
			$lo98 = ")";
		} else {
			$lo99 = "";
			$nil_lalux = $nil_lalu;
			$lo98 = "";
		}
		$nil_lalu1 = number_format($nil_lalux, "2", ",", ".");

		$real_nilai = $nil - $nil_lalu;
		if ($real_nilai < 0) {
			$lo0 = "(";
			$real_nilaix = $real_nilai * -1;
			$lo00 = ")";
		} else {
			$lo0 = "";
			$real_nilaix = $real_nilai;
			$lo00 = "";
		}
		$real_nilai1 = number_format($real_nilaix, "2", ",", ".");

		if ($nil_lalu == '' or $nil_lalu == 0) {
			$persen1 = '0,00';
		} else {
			$persen1 = ($nil / $nil_lalu) * 100;
			$persen1 = number_format($persen1, "2", ",", ".");
		}
		if ($loquery->parent) {
			$arr_parent[] = array(
				"seq" => $seq,
				"parent" => $loquery->parent,
				"uraian" => $nama,
				"nilai" => ($operator == '-') ? $nil * -1 : $nil,
				"nilai_lalu" => ($operator == '-') ? $nil_lalu  * -1 : $nil_lalu
			);
		}
	}

		$no       = $no + 1;
		switch ($loquery->bold) {
			case 0:
				$cRet    .= "<tr>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
								 <td colspan =\"6\"  style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"></td>
							 </tr>";
				break;
			case 1:
				$cRet    .= "<tr>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
								 <td colspan =\"6\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"></td>
							 </tr>";
				break;
			case 2:
				$cRet    .= "<tr>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>   
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                         
								 <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
							 </tr>";
				break;
				case 3:
					$cRet    .= "<tr>
									 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td> 
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                  
									 <td colspan =\"4\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
									 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo88$nil1$lo89</td>
									 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo99$nil_lalu1$lo98</td>
									 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo0$real_nilai1$lo00</td>
									 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$persen1</td>
								 </tr>";
								//===================== Objek  / rincian       
								$sql = "SELECT c.kd_rek4 as kd_rek,c.nm_rek4 as nm_rek,sum(a.$normal) as 'thn_ini',
										(
											select isnull(sum(d.$normal),0) from trdju_pkd d 
											join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
											where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,6)=c.kd_rek4 
											
										) as 'thn_lalu' 
										from trdju_pkd a 
										join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
										join ms_rek4 c on LEFT(a.kd_rek6,6)=c.kd_rek4
										where (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3) or left(kd_rek6,12) in ($n4)) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
										group by c.kd_rek4,c.nm_rek4
										union all
										SELECT c.kd_rek5 as kd_rek,c.nm_rek5 as nm_rek,sum(a.$normal) as 'thn_ini',
										(
											select isnull(sum(d.$normal),0) from trdju_pkd d 
											join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
											where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,8)=c.kd_rek5 
											
										) as 'thn_lalu' 
										from trdju_pkd a 
										join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
										join ms_rek5 c on LEFT(a.kd_rek6,8)=c.kd_rek5
										where (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3) or left(kd_rek6,12) in ($n4)) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
										group by c.kd_rek5,c.nm_rek5
										union all
										SELECT c.kd_rek6 as kd_rek,c.nm_rek6 as nm_rek,sum(a.$normal) as 'thn_ini',
										(
											select isnull(sum(d.$normal),0) from trdju_pkd d 
											join trhju_pkd e on d.no_voucher=e.no_voucher and d.kd_unit=e.kd_skpd 
											where YEAR(e.tgl_voucher)='$thn_ang_1' and left(d.kd_rek6,12)=c.kd_rek6 
											
										) as 'thn_lalu' 
										from trdju_pkd a 
										join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd
										join ms_rek6 c on LEFT(a.kd_rek6,12)=c.kd_rek6
										where (left(a.kd_rek6,4) in ($n) or left(a.kd_rek6,6) in ($n2) or left(a.kd_rek6,8) in ($n3) or left(a.kd_rek6,12) in ($n4)) and YEAR(b.tgl_voucher)='$thn_ang' and MONTH(b.tgl_voucher)<='$cbulan'
										group by c.kd_rek6,c.nm_rek6
										order by kd_rek";

								$query = $this->db->query($sql);
								foreach($query->result() as $row){
								$no = $no + 1;
								$nama = $row->nm_rek;
								$nilai = $this->custom->rp_minus($row->thn_ini);
								$nilai_lalu = $this->custom->rp_minus($row->thn_lalu);
								$real_nilai1 = $this->custom->rp_minus($row->thn_ini - $row->thn_lalu);
								if( $row->thn_lalu=='' or $row->thn_lalu==0){
								$persen1 = '0,00';
								}else{
								$persen1 = ($row->thn_ini/$row->thn_lalu)*100;
								$persen1 = number_format($persen1,"2",",",".");
								}

								if(strlen($row->kd_rek)==6){ //objek
									$cRet    .= "<tr>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td colspan =\"3\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
											</tr>";
								}else if(strlen($row->kd_rek)==12){ //objek
									$cRet    .= "<tr>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td colspan =\"1\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
											</tr>";
								}else{
									$cRet    .= "<tr>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"center\">$no</td>                                     
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"3%\"></td>
												<td colspan =\"2\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$real_nilai1</td>
												<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"right\">$persen1</td>
											</tr>";
									}
								}
								//===================== End Objek
					break;
			case 4:
				$cRet    .= "<tr>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>  
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                    
								 <td colspan =\"2\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo88$nil1$lo89</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo99$nil_lalu1$lo98</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo0$real_nilai1$lo00</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen1</b></td>
							 </tr>";
				break;
			case 5:
				$cRet    .= "<tr>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td> 
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                    
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo1$surplus_lo1$lo2</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo3$surplus_lo_lalu1$lo4</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo5$selisih_surplus_lo1$lo6</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen2</b></td>
							 </tr>";
				break;
			case 6:
				$cRet    .= "<tr>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td> 
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo7$surplus_def_lo1$lo8</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo9$surplus_def_lo_lalu1$lo10</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_def1$lo12</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen3</b></td>
							 </tr>";
				break;
			case 7:
				foreach ($arr_parent as $key => $value) {
					if ($value['parent'] == $seq) {
						$surplus_lo += $value['nilai'];
						$surplus_lo_thnlalu += $value['nilai_lalu'];
					}
				}
				$selisih_surplus_lo = $surplus_lo - $surplus_lo_thnlalu;

				$cRet    .= "<tr>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>   
								 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                   
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo7$surplus_def_lo1$lo8</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo9$surplus_def_lo_lalu1$lo10</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_def1$lo12</b></td>
								 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen3</td>
							 </tr>";
				break;
		}
	}

	 $cRet         .= "</table>";
	//        $data['prev']  = $cRet;
	//        $data['sikap'] = 'preview';
	//        
	//        
	//        
	//        $this->template->set('title', 'LAPORAN OPERASIONAL'); 
	//        $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
	//$this->template->load('template','anggaran/rka/perkadaII',$data); 
	$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$tglttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUP' or id='77')";
	$sqlttd = $this->db->query($sqlttd1);
	foreach ($sqlttd->result() as $rowttd) {
		$nip = $rowttd->nip;
		$namax = $rowttd->nm;
		$jabatan  = $rowttd->jab;
		$pangkat  = $rowttd->pangkat;
	}

	
	
	if ($nip == '00000000 000000 0 000'){
		$cRet .= '<br><br>
			<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						
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
	} else if ($nip == '19671126 199503 2 004'){
			$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Sanggau , ' . $tanggal . '</TD>
							</TR>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . strtoupper($jabatan) . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b></TD>
								<TD width="50%" align="center" >selaku <br>PEJABAT PENGELOLA KEUANGAN DAERAH</TD>
							</TR>
							<TR>
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
								<TD align="center" ><b><u>' . $namax . '</b></u></TD>
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


		} else {
		$cRet .= '<br><br>
			<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
						
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" > Sanggau , ' . $tanggal . '</TD>
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
							<TD align="center" ><u><b>' . $namax . '</b></u></TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >' . $pangkat . '</TD>
						</TR>
						<TR>
							<TD width="50%" align="center" ><b>&nbsp;</TD>
							<TD align="center" >'.$nip.'</TD>
						</TR>
						</TABLE><br/>';
		}
	$cRet .=       " </table>";
	$data['prev'] = $cRet;
	$data['sikap'] = 'preview';
	$judul  = ("LO KONSOL RINCIAN $cbulan");
	$this->template->set('title', 'LO KONSOL SUB RINCIAN $cbulan');
	switch ($pilih) {
		case 1;
			// $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
			echo "<title>LO KONSOL SUB RINCIAN $cbulan</title>";
			echo $cRet;
			break;
		case 2;
			header("Cache-Control: no-cache, no-store, must-revalidate");
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=$judul.xls");

			$this->load->view('anggaran/rka/perkadaII', $data);
			break;
		case 3;
			header("Cache-Control: no-cache, no-store, must-revalidate");
			header("Content-Type: application/vnd.ms-word");
			header("Content-Disposition: attachment; filename=$judul.doc");
			$this->load->view('anggaran/rka/perkadaII', $data);
			break;
		case 0;
			$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
			/* echo "<title>LO KONSOL $cbulan</title>";
		echo $cRet; */
			break;
	}
}

// end cetak lo sub rinci

	//================================================ Cetak LO
	function cetak_lra_lo_pemda()
	{
		$data['page_title'] = 'LO KONSOLIDASI';
		$this->template->set('title', 'LO KONSOLIDASI');
		$this->template->load('template', 'akuntansi/cetak_lra_lo_pemda', $data);
	}

	function ctk_lra_lo_pemda($cbulan = "", $pilih = "", $tglttd = "", $ttd = "")
	{
		//$id = $skpd;
		$this->load->library('custom');
		$cetak = '2'; //$ctk; 
		$tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
		$tglttd = str_replace('n', ' ', $tglttd);
		$id     = $this->session->userdata('kdskpd');
		$thn_ang = $this->session->userdata('pcThang');
		$thn_ang_1 = $thn_ang - 1;
		//$laporan=$lap; 

		// if ($cetak == '1') {
		//           $skpd = '';
		//           $skpd1 = '';           
		//       } else {             
		$skpd = "AND kd_skpd='$id'";
		$skpd1 = "AND b.kd_skpd='$id'";
		//}  

		$y123 = ")";
		$x123 = "(";
		$sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$id'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$nmskpd     = $rowsc->nm_skpd;
		}
		$nm_skpd = strtoupper($nmskpd);
		// INSERT DATA

		$sqldns = "SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON LEFT(a.kd_urusan,1)=LEFT(b.kd_urusan,1) WHERE kd_skpd='$id'";
		$sqlskpd = $this->db->query($sqldns);
		foreach ($sqlskpd->result() as $rowdns) {
			$kd_urusan = $rowdns->kd_u;
			$nm_urusan = $rowdns->nm_u;
			$kd_skpd  = $rowdns->kd_sk;
			$nm_skpd  = $rowdns->nm_sk;
		}

		// created by henri_tb

		$sqllo1 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73')";
		$querylo1 = $this->db->query($sqllo1);
		$penlo = $querylo1->row();
		$pen_lo = $penlo->nilai;
		$pen_lo1 = number_format($penlo->nilai, "2", ",", ".");

		$sqllo2 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73')";
		$querylo2 = $this->db->query($sqllo2);
		$penlo2 = $querylo2->row();
		$pen_lo_lalu = $penlo2->nilai;
		$pen_lo_lalu1 = number_format($penlo2->nilai, "2", ",", ".");

		$sqllo3 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83','84')";
		$querylo3 = $this->db->query($sqllo3);
		$bello = $querylo3->row();
		$bel_lo = $bello->nilai;
		$bel_lo1 = number_format($bello->nilai, "2", ",", ".");

		$sqllo4 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','84')";
		$querylo4 = $this->db->query($sqllo4);
		$bello2 = $querylo4->row();
		$bel_lo_lalu = $bello2->nilai;
		$bel_lo_lalu1 = number_format($bello2->nilai, "2", ",", ".");

		// calvin 11 february 2023

		$sqllo5 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74','85')";
		$querylo5 = $this->db->query($sqllo5);
		$def5 = $querylo5->row();
		$def_lo = $def5->nilai;
		$def_lo1 = number_format($def5->nilai, "2", ",", ".");

		$sqllo6 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74','83')";
		$querylo6 = $this->db->query($sqllo6);
		$def6 = $querylo6->row();
		$del_lo_lalu = $def6->nilai;
		$del_lo_lalu1 = number_format($def6->nilai, "2", ",", ".");

		// end
		$surplus_lo = $pen_lo - $bel_lo;
		if ($surplus_lo < 0) {
			$lo1 = "(";
			$surplus_lox = $surplus_lo * -1;
			$lo2 = ")";
		} else {
			$lo1 = "";
			$surplus_lox = $surplus_lo;
			$lo2 = "";
		}
		$surplus_lo1 = number_format($surplus_lox, "2", ",", ".");

		$surplus_lo_lalu = $pen_lo_lalu - $bel_lo_lalu;
		if ($surplus_lo_lalu < 0) {
			$lo3 = "(";
			$surplus_lo_lalux = $surplus_lo_lalu * -1;
			$lo4 = ")";
		} else {
			$lo3 = "";
			$surplus_lo_lalux = $surplus_lo_lalu;
			$lo4 = "";
		}
		$surplus_lo_lalu1 = number_format($surplus_lo_lalux, "2", ",", ".");

		// calvin 
		$surplus_def_lo = $surplus_lo - $def_lo;
		if ($surplus_def_lo < 0) {
			$lo96 = "(";
			$surplus_def = $surplus_def_lo * -1;
			$lo97 = ")";
		} else {
			$lo96 = "";
			$surplus_def = $surplus_def_lo;
			$lo97 = "";
		}
		$surplus_def_lo1 = number_format($surplus_def, "2", ",", ".");

		$surplus_def_lo_lalu = $surplus_lo_lalu + $del_lo_lalu;
		if ($surplus_def_lo_lalu < 0) {
			$lo99 = "(";
			$surplus_def_lalux = $surplus_def_lo_lalu * -1;
			$lo98 = ")";
		} else {
			$lo99 = "";
			$surplus_def_lalux = $surplus_def_lo_lalu;
			$lo98 = "";
		}
		$surplus_def_lo_lalu1 = number_format($surplus_def_lalux, "2", ",", ".");

		//PENAMBAHAN ANDIKA
		//$jumlahluarbiasa = number_format($surplus_lo_lalu1 + $del_lo_lalu, "2", ",", ".");

		$selisih_surplus_def = $surplus_def_lo - $surplus_def_lo_lalu;
		if ($selisih_surplus_def < 0) {
			$lo100 = "(";
			$selisih_surplus_defx = $selisih_surplus_def * -1;
			$lo101 = ")";
		} else {
			$lo100 = "";
			$selisih_surplus_defx = $selisih_surplus_def;
			$lo101 = "";
		}
		$selisih_surplus_def1 = number_format($selisih_surplus_defx, "2", ",", ".");

		if ($surplus_def_lo_lalu == '' or $surplus_def_lo_lalu == 0) {
			$persen100 = '0,00';
		} else {
			$persen100 = ($surplus_def_lo / $surplus_def_lo_lalu) * 100;
			$persen100 = number_format($persen100, "2", ",", ".");
		}

		//end

		$selisih_surplus_lo = $surplus_lo - $surplus_lo_lalu;
		if ($selisih_surplus_lo < 0) {
			$lo5 = "(";
			$selisih_surplus_lox = $selisih_surplus_lo * -1;
			$lo6 = ")";
		} else {
			$lo5 = "";
			$selisih_surplus_lox = $selisih_surplus_lo;
			$lo6 = "";
		}
		$selisih_surplus_lo1 = number_format($selisih_surplus_lox, "2", ",", ".");

		if ($surplus_lo_lalu == '' or $surplus_lo_lalu == 0) {
			$persen2 = '0,00';
		} else {
			$persen2 = ($surplus_lo / $surplus_lo_lalu) * 100;
			$persen2 = number_format($persen2, "2", ",", ".");
		}

		$sqllo5 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73','74')";
		$querylo5 = $this->db->query($sqllo5);
		$penlo3 = $querylo5->row();
		$pen_lo3 = $penlo3->nilai;
		$pen_lo31 = number_format($penlo3->nilai, "2", ",", ".");

		$sqllo6 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73','74')";
		$querylo6 = $this->db->query($sqllo6);
		$penlo4 = $querylo6->row();
		$pen_lo_lalu4 = $penlo4->nilai;
		$pen_lo_lalu41 = number_format($penlo4->nilai, "2", ",", ".");

		$sqllo7 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83')";
		$querylo7 = $this->db->query($sqllo7);
		$bello5 = $querylo7->row();
		$bel_lo5 = $bello5->nilai;
		$bel_lo51 = number_format($bello5->nilai, "2", ",", ".");

		$sqllo8 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','83')";
		$querylo8 = $this->db->query($sqllo8);
		$bello6 = $querylo8->row();
		$bel_lo_lalu6 = $bello6->nilai;
		$bel_lo_lalu61 = number_format($bello6->nilai, "2", ",", ".");

		$surplus_lo2 = $pen_lo3 - $bel_lo5;
		if ($surplus_lo2 < 0) {
			$lo7 = "(";
			$surplus_lo2x = $surplus_lo2 * -1;
			$lo8 = ")";
		} else {
			$lo7 = "";
			$surplus_lo2x = $surplus_lo2;
			$lo8 = "";
		}
		$surplus_lo21 = number_format($surplus_lo2x, "2", ",", ".");

		$surplus_lo_lalu2 = $pen_lo_lalu4 - $bel_lo_lalu6;
		if ($surplus_lo_lalu2 < 0) {
			$lo9 = "(";
			$surplus_lo_lalu2x = $surplus_lo_lalu2 * -1;
			$lo10 = ")";
		} else {
			$lo9 = "";
			$surplus_lo_lalu2x = $surplus_lo_lalu2;
			$lo10 = "";
		}
		$surplus_lo_lalu21 = number_format($surplus_lo_lalu2x, "2", ",", ".");

		$selisih_surplus_lo2 = $surplus_lo2 - $surplus_lo_lalu2;
		if ($selisih_surplus_lo2 < 0) {
			$lo11 = "(";
			$selisih_surplus_lo2x = $selisih_surplus_lo2 * -1;
			$lo12 = ")";
		} else {
			$lo11 = "";
			$selisih_surplus_lo2x = $selisih_surplus_lo2;
			$lo12 = "";
		}
		$selisih_surplus_lo21 = number_format($selisih_surplus_lo2x, "2", ",", ".");

		if ($surplus_lo_lalu2 == '' or $surplus_lo_lalu2 == 0) {
			$persen3 = '0,00';
		} else {
			$persen3 = ($surplus_lo2 / $surplus_lo_lalu2) * 100;
			$persen3 = number_format($persen3, "2", ",", ".");
		}

		$sqllo9 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('7')";
		$querylo9 = $this->db->query($sqllo9);
		$penlo7 = $querylo9->row();
		$pen_lo7 = $penlo7->nilai;
		$pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

		$sqllo10 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7')";
		$querylo10 = $this->db->query($sqllo10);
		$penlo8 = $querylo10->row();
		$pen_lo_lalu8 = $penlo8->nilai;
		$pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

		$sqllo11 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('8')";
		$querylo11 = $this->db->query($sqllo11);
		$bello9 = $querylo11->row();
		$bel_lo9 = $bello9->nilai;
		$bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8')";
		$querylo12 = $this->db->query($sqllo12);
		$bello10 = $querylo12->row();
		$bel_lo_lalu10 = $bello10->nilai;
		$bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

		$surplus_lo3 = $pen_lo7 - $bel_lo9;
		if ($surplus_lo3 < 0) {
			$lo13 = "(";
			$surplus_lo3x = $surplus_lo3 * -1;
			$lo14 = ")";
		} else {
			$lo13 = "";
			$surplus_lo3x = $surplus_lo3;
			$lo14 = "";
		}
		$surplus_lo31 = number_format($surplus_lo3x, "2", ",", ".");

		$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
		if ($surplus_lo_lalu3 < 0) {
			$lo15 = "(";
			$surplus_lo_lalu3x = $surplus_lo_lalu3 * -1;
			$lo16 = ")";
		} else {
			$lo15 = "";
			$surplus_lo_lalu3x = $surplus_lo_lalu3;
			$lo16 = "";
		}
		$surplus_lo_lalu31 = number_format($surplus_lo_lalu3x, "2", ",", ".");

		$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
		if ($selisih_surplus_lo3 < 0) {
			$lo17 = "(";
			$selisih_surplus_lo3x = $selisih_surplus_lo3 * -1;
			$lo18 = ")";
		} else {
			$lo17 = "";
			$selisih_surplus_lo3x = $selisih_surplus_lo3;
			$lo18 = "";
		}
		$selisih_surplus_lo31 = number_format($selisih_surplus_lo3x, "2", ",", ".");

		if ($surplus_lo_lalu3 == '' or $surplus_lo_lalu3 == 0) {
			$persen4 = '0,00';
		} else {
			$persen4 = ($surplus_lo3 / $surplus_lo_lalu3) * 100;
			$persen4 = number_format($persen4, "2", ",", ".");
		}


		$sqllo13 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74')";
		$querylo13 = $this->db->query($sqllo13);
		$penlo11 = $querylo13->row();
		$pen_lo11 = $penlo11->nilai;
		$pen_lo111 = number_format($penlo11->nilai, "2", ",", ".");

		$sqllo14 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74')";
		$querylo14 = $this->db->query($sqllo14);
		$penlo12 = $querylo14->row();
		$pen_lo_lalu12 = $penlo12->nilai;
		$pen_lo_lalu121 = number_format($penlo12->nilai, "2", ",", ".");

		$sqllo15 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('85')";
		$querylo15 = $this->db->query($sqllo15);
		$bello13 = $querylo15->row();
		$bel_lo13 = $bello13->nilai;
		$bel_lo131 = number_format($bello13->nilai, "2", ",", ".");

		$sqllo16 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('83')";
		$querylo16 = $this->db->query($sqllo16);
		$bello14 = $querylo16->row();
		$bel_lo_lalu14 = $bello14->nilai;
		$bel_lo_lalu141 = number_format($bello14->nilai, "2", ",", ".");

		$surplus_lo4 = $pen_lo11 - $bel_lo13;
		if ($surplus_lo4 < 0) {
			$lo19 = "(";
			$surplus_lo4x = $surplus_lo4 * -1;
			$lo20 = ")";
		} else {
			$lo19 = "";
			$surplus_lo4x = $surplus_lo4;
			$lo20 = "";
		}
		$surplus_lo41 = number_format($surplus_lo4x, "2", ",", ".");

		$surplus_lo_lalu4 = $pen_lo_lalu12 - $bel_lo_lalu14;
		if ($surplus_lo_lalu4 < 0) {
			$lo21 = "(";
			$surplus_lo_lalu4x = $surplus_lo_lalu4 * -1;
			$lo22 = ")";
		} else {
			$lo21 = "";
			$surplus_lo_lalu4x = $surplus_lo_lalu4;
			$lo22 = "";
		}
		$surplus_lo_lalu41 = number_format($surplus_lo_lalu4x, "2", ",", ".");

		$selisih_surplus_lo4 = $surplus_lo4 - $surplus_lo_lalu4;
		if ($selisih_surplus_lo4 < 0) {
			$lo23 = "(";
			$selisih_surplus_lo4x = $selisih_surplus_lo4 * -1;
			$lo24 = ")";
		} else {
			$lo23 = "";
			$selisih_surplus_lo4x = $selisih_surplus_lo4;
			$lo24 = "";
		}
		$selisih_surplus_lo41 = number_format($selisih_surplus_lo4x, "2", ",", ".");

		if ($surplus_lo_lalu4 == '' or $surplus_lo_lalu4 == 0) {
			$persen5 = '0,00';
		} else {
			$persen5 = ($surplus_lo4 / $surplus_lo_lalu4) * 100;
			$persen5 = number_format($persen5, "2", ",", ".");
		}


		$sqllo17 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('75')";
		$querylo17 = $this->db->query($sqllo17);
		$penlo15 = $querylo17->row();
		$pen_lo15 = $penlo15->nilai;
		$pen_lo151 = number_format($penlo15->nilai, "2", ",", ".");

		$sqllo18 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('75')";
		$querylo18 = $this->db->query($sqllo18);
		$penlo16 = $querylo18->row();
		$pen_lo_lalu16 = $penlo16->nilai;
		$pen_lo_lalu161 = number_format($penlo16->nilai, "2", ",", ".");

		$sqllo19 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('84')";
		$querylo19 = $this->db->query($sqllo19);
		$bello17 = $querylo19->row();
		$bel_lo17 = $bello17->nilai;
		$bel_lo171 = number_format($bello17->nilai, "2", ",", ".");

		$sqllo20 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('84')";
		$querylo20 = $this->db->query($sqllo20);
		$bello18 = $querylo20->row();
		$bel_lo_lalu18 = $bello18->nilai;
		$bel_lo_lalu181 = number_format($bello18->nilai, "2", ",", ".");

		$surplus_lo5 = $pen_lo15 - $bel_lo17;
		if ($surplus_lo5 < 0) {
			$lo25 = "(";
			$surplus_lo5x = $surplus_lo5 * -1;
			$lo26 = ")";
		} else {
			$lo25 = "";
			$surplus_lo5x = $surplus_lo5;
			$lo26 = "";
		}
		$surplus_lo51 = number_format($surplus_lo5x, "2", ",", ".");

		$surplus_lo_lalu5 = $pen_lo_lalu16 - $bel_lo_lalu18;
		if ($surplus_lo_lalu5 < 0) {
			$lo27 = "(";
			$surplus_lo_lalu5x = $surplus_lo_lalu5 * -1;
			$lo28 = ")";
		} else {
			$lo27 = "";
			$surplus_lo_lalu5x = $surplus_lo_lalu5;
			$lo28 = "";
		}
		$surplus_lo_lalu51 = number_format($surplus_lo_lalu5x, "2", ",", ".");

		$selisih_surplus_lo5 = $surplus_lo5 - $surplus_lo_lalu5;
		if ($selisih_surplus_lo5 < 0) {
			$lo29 = "(";
			$selisih_surplus_lo5x = $selisih_surplus_lo5 * -1;
			$lo30 = ")";
		} else {
			$lo29 = "";
			$selisih_surplus_lo5x = $selisih_surplus_lo5;
			$lo30 = "";
		}
		$selisih_surplus_lo51 = number_format($selisih_surplus_lo5x, "2", ",", ".");

		if ($surplus_lo_lalu5 == '' or $surplus_lo_lalu5 == 0) {
			$persen6 = '0,00';
		} else {
			$persen6 = ($surplus_lo5 / $surplus_lo_lalu5) * 100;
			$persen6 = number_format($persen6, "2", ",", ".");
		}


		$modtahun = $thn_ang % 4;

		if ($modtahun = 0) {
			$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		} else {
			$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		$cRet = '';

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
						<td rowspan=\"3\" align=\"left\" width=\"2%\">
						<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"60\" height=\"70\" />
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$cbulan] $thn_ang DAN $thn_ang_1</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";

		$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NO</b></td>                            
                            <td colspan = \"5\" bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>$thn_ang_1</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>Kenaikan /</br>(Penurunan)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>
                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td colspan = \"5\" style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>";

		$sqlmaplo = "SELECT seq, nor, uraian, bold,isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak ,parent,kode FROM map_lo_pemda 
				   GROUP BY seq, nor, uraian, bold,isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet'), parent ,kode ORDER BY seq";

		$querymaplo = $this->db->query($sqlmaplo);
		$no     = 0;
		$arr_parent = [];
		$surplus_lo = 0;
		$surplus_lo_thnlalu = 0;
		$selisih_surplus_lo = 0;

		foreach ($querymaplo->result() as $loquery) {
			$operator = $loquery->kode;
			$seq = $loquery->seq;
			$nama      = $loquery->uraian;
			$n         = $loquery->kode_1;
			$n		   = ($n == "-" ? "'-'" : $n);
			$n2        = $loquery->kode_2;
			$n2		   = ($n2 == "-" ? "'-'" : $n2);
			$n3        = $loquery->kode_3;
			$n3		   = ($n3 == "-" ? "'-'" : $n3);
			$normal    = $loquery->cetak;
			$bold      = $loquery->bold;

			if ($normal != "") {
				$quelo01   = "SELECT SUM($normal) as nilai FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan";
				$quelo02 = $this->db->query($quelo01);
				$quelo03 = $quelo02->row();
				$nil     = $quelo03->nilai;
				$nilai    = number_format($quelo03->nilai, "2", ",", ".");

				$quelo04   = "SELECT SUM($normal) as nilai FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang_1";
				$quelo05 = $this->db->query($quelo04);
				$quelo06 = $quelo05->row();
				$nil_lalu     = $quelo06->nilai;
				$nilai_lalu    = number_format($quelo06->nilai, "2", ",", ".");

				if ($nil < 0) {
					$lo88 = "(";
					$nilx = $nil * -1;
					$lo89 = ")";
				} else {
					$lo88 = "";
					$nilx = $nil;
					$lo89 = "";
				}
				$nil1 = number_format($nilx, "2", ",", ".");

				if ($nil_lalu < 0) {
					$lo99 = "(";
					$nil_lalux = $nil_lalu * -1;
					$lo98 = ")";
				} else {
					$lo99 = "";
					$nil_lalux = $nil_lalu;
					$lo98 = "";
				}
				$nil_lalu1 = number_format($nil_lalux, "2", ",", ".");

				$real_nilai = $nil - $nil_lalu;
				if ($real_nilai < 0) {
					$lo0 = "(";
					$real_nilaix = $real_nilai * -1;
					$lo00 = ")";
				} else {
					$lo0 = "";
					$real_nilaix = $real_nilai;
					$lo00 = "";
				}
				$real_nilai1 = number_format($real_nilaix, "2", ",", ".");

				if ($nil_lalu == '' or $nil_lalu == 0) {
					$persen1 = '0,00';
				} else {
					$persen1 = ($nil / $nil_lalu) * 100;
					$persen1 = number_format($persen1, "2", ",", ".");
				}
				if ($loquery->parent) {
					$arr_parent[] = array(
						"seq" => $seq,
						"parent" => $loquery->parent,
						"uraian" => $nama,
						"nilai" => ($operator == '-') ? $nil * -1 : $nil,
						"nilai_lalu" => ($operator == '-') ? $nil_lalu  * -1 : $nil_lalu
					);
				}
			}

			$no       = $no + 1;
			switch ($loquery->bold) {
				case 0:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td colspan =\"5\"  style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 1:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>                                     
                                     <td colspan =\"5\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 2:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>   
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                         
                                     <td colspan =\"4\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
									 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 3:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\">$no</td>  
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                     <td colspan =\"3\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo88$nil1$lo89</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo99$nil_lalu1$lo98</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\">$lo0$real_nilai1$lo00</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\">$persen1</td>
                                 </tr>";

					break;
				case 4:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>  
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                    
                                     <td colspan =\"2\" style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo88$nil1$lo89</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo99$nil_lalu1$lo98</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo0$real_nilai1$lo00</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen1</b></td>
                                 </tr>";
					break;
				case 5:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo1$surplus_lo1$lo2</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo3$surplus_lo_lalu1$lo4</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo5$selisih_surplus_lo1$lo6</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen2</b></td>
                                 </tr>";
					break;
				case 6:
					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo7$surplus_def_lo1$lo8</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo9$surplus_def_lo_lalu1$lo10</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_def1$lo12</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen3</b></td>
                                 </tr>";
					break;
				case 7:
					foreach ($arr_parent as $key => $value) {
						if ($value['parent'] == $seq) {
							$surplus_lo += $value['nilai'];
							$surplus_lo_thnlalu += $value['nilai_lalu'];
						}
					}
					$selisih_surplus_lo = $surplus_lo - $surplus_lo_thnlalu;

					$cRet    .= "<tr>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"center\"><b>$no</b></td>
									 <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;valign:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"3%\"></td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;border-left: none;border-right: none;\" width=\"27%\"><b>$nama</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo7$surplus_def_lo1$lo8</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo9$surplus_def_lo_lalu1$lo10</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"20%\" align=\"right\"><b>$lo11$selisih_surplus_def1$lo12</b></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"right\"><b>$persen3</td>
                                 </tr>";
					break;
			}
		}
		/*
			switch ($loquery->seq) {
				case 5:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 10:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 40:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 45:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 50:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 80:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 85:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 105:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 110:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;

				case 130:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 135:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 165:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 170:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 175:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;

				case 215:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 220:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;


				case 260:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 265:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 266:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 271:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 285:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 290:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 310:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 315:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo1$surplus_lo1$lo2</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo3$surplus_lo_lalu1$lo4</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo5$selisih_surplus_lo1$lo6</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen2</td>
                                 </tr>";
					break;
				case 316:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 320:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 325:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;


				case 350:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 355:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;

				case 380:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo19$surplus_lo41$lo20</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo21$surplus_lo_lalu41$lo22</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo23$selisih_surplus_lo41$lo24</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen5</td>
                                 </tr>";
					break;
				case 385:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 390:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo7$surplus_lo21$lo8</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo9$surplus_lo_lalu21$lo10</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo11$selisih_surplus_lo21$lo12</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen3</td>
                                 </tr>";
					break;
				case 395:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 400:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 405:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 420:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 425:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 445:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 450:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo13$surplus_lo31$lo14</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo15$surplus_lo_lalu31$lo16</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo17$selisih_surplus_lo31$lo18</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen4</td>
                                 </tr>";
					break;
				default:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai_lalu</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo0$real_nilai1$lo00</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1</td>
                                 </tr>";
			}
		}
		*/

		$cRet         .= "</table>";
		//        $data['prev']  = $cRet;
		//        $data['sikap'] = 'preview';
		//        
		//        
		//        
		//        $this->template->set('title', 'LAPORAN OPERASIONAL'); 
		//        $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
		//$this->template->load('template','anggaran/rka/perkadaII',$data); 
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$tglttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}



		if ($nip == '00000000 000000 0 000') {
			$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
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
				<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
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
								<TD align="center" ><u><b>' . $namax . '</b></u></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $nip . '</TD>
							</TR>
							</TABLE><br/>';
		}
		$cRet .=       " </table>";
		$data['prev'] = $cRet;
		$data['sikap'] = 'preview';
		$judul  = ("LO KONSOL $cbulan");
		$this->template->set('title', 'LO KONSOL $cbulan');
		switch ($pilih) {
			case 1;
				// $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
				echo "<title>LO KONSOL $cbulan</title>";
				echo $cRet;
				break;
			case 2;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-excel");
				header("Content-Disposition: attachment; filename=$judul.xls");

				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
			case 3;
				header("Cache-Control: no-cache, no-store, must-revalidate");
				header("Content-Type: application/vnd.ms-word");
				header("Content-Disposition: attachment; filename=$judul.doc");
				$this->load->view('anggaran/rka/perkadaII', $data);
				break;
			case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
				/* echo "<title>LO KONSOL $cbulan</title>";
			echo $cRet; */
				break;
		}
	}

	function ctk_lra_lo_pemda_unit($cbulan = "", $kd_skpd = "", $pilih = 1)
	{
		//$id = $skpd;
		$cetak = '2'; //$ctk; 
		$id     = $this->session->userdata('kdskpd');
		$thn_ang = $this->session->userdata('pcThang');
		$thn_ang_1 = $thn_ang - 1;
		//$laporan=$lap; 

		// if ($cetak == '1') {
		//           $skpd = '';
		//           $skpd1 = '';           
		//       } else {             
		$skpd = "AND kd_skpd='$kd_skpd'";
		$skpd1 = "AND b.kd_skpd='$kd_skpd'";
		//}  

		$y123 = ")";
		$x123 = "(";
		$sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd'";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {
			$nmskpd     = $rowsc->nm_skpd;
		}
		$nm_skpd = strtoupper($nmskpd);
		// INSERT DATA

		$sqldns = "SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$kd_skpd'";
		$sqlskpd = $this->db->query($sqldns);
		foreach ($sqlskpd->result() as $rowdns) {
			$kd_urusan = $rowdns->kd_u;
			$nm_urusan = $rowdns->nm_u;
			$kd_skpd  = $rowdns->kd_sk;
			$nm_skpd  = $rowdns->nm_sk;
		}

		// created by henri_tb

		$trhju_pkd = 'trhju_pkd';
		$trdju_pkd = 'trdju_pkd';

		$sqllo1 = "SELECT sum(kredit-debet) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73') and kd_skpd='$kd_skpd'";
		$querylo1 = $this->db->query($sqllo1);
		$penlo = $querylo1->row();
		$pen_lo = $penlo->nilai;
		$pen_lo1 = number_format($penlo->nilai, "2", ",", ".");

		$sqllo2 = "SELECT sum(debet-kredit) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73') and kd_skpd='$kd_skpd'";
		$querylo2 = $this->db->query($sqllo2);
		$penlo2 = $querylo2->row();
		$pen_lo_lalu = $penlo2->nilai;
		$pen_lo_lalu1 = number_format($penlo2->nilai, "2", ",", ".");

		$sqllo3 = "SELECT sum(debet-kredit) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82') and kd_skpd='$kd_skpd'";
		$querylo3 = $this->db->query($sqllo3);
		$bello = $querylo3->row();
		$bel_lo = $bello->nilai;
		$bel_lo1 = number_format($bello->nilai, "2", ",", ".");

		$sqllo4 = "SELECT sum(debet-kredit) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82') and kd_skpd='$kd_skpd'";
		$querylo4 = $this->db->query($sqllo4);
		$bello2 = $querylo4->row();
		$bel_lo_lalu = $bello2->nilai;
		$bel_lo_lalu1 = number_format($bello2->nilai, "2", ",", ".");

		$surplus_lo = $pen_lo - $bel_lo;
		if ($surplus_lo < 0) {
			$lo1 = "(";
			$surplus_lox = $surplus_lo * -1;
			$lo2 = ")";
		} else {
			$lo1 = "";
			$surplus_lox = $surplus_lo;
			$lo2 = "";
		}
		$surplus_lo1 = number_format($surplus_lox, "2", ",", ".");

		$surplus_lo_lalu = $pen_lo_lalu - $bel_lo_lalu;
		if ($surplus_lo_lalu < 0) {
			$lo3 = "(";
			$surplus_lo_lalux = $surplus_lo_lalu * -1;
			$lo4 = ")";
		} else {
			$lo3 = "";
			$surplus_lo_lalux = $surplus_lo_lalu;
			$lo4 = "";
		}
		$surplus_lo_lalu1 = number_format($surplus_lo_lalux, "2", ",", ".");

		$selisih_surplus_lo = $surplus_lo - $surplus_lo_lalu;
		if ($selisih_surplus_lo < 0) {
			$lo5 = "(";
			$selisih_surplus_lox = $selisih_surplus_lo * -1;
			$lo6 = ")";
		} else {
			$lo5 = "";
			$selisih_surplus_lox = $selisih_surplus_lo;
			$lo6 = "";
		}
		$selisih_surplus_lo1 = number_format($selisih_surplus_lox, "2", ",", ".");

		if ($surplus_lo_lalu == '' or $surplus_lo_lalu == 0) {
			$persen2 = '0,00';
		} else {
			$persen2 = ($surplus_lo / $surplus_lo_lalu) * 100;
			$persen2 = number_format($persen2, "2", ",", ".");
		}

		$sqllo5 = "SELECT sum(kredit-debet) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('71','72','73','74') and kd_skpd='$kd_skpd'";
		$querylo5 = $this->db->query($sqllo5);
		$penlo3 = $querylo5->row();
		$pen_lo3 = $penlo3->nilai;
		$pen_lo31 = number_format($penlo3->nilai, "2", ",", ".");

		$sqllo6 = "SELECT sum(kredit-debet) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('71','72','73','74') and kd_skpd='$kd_skpd'";
		$querylo6 = $this->db->query($sqllo6);
		$penlo4 = $querylo6->row();
		$pen_lo_lalu4 = $penlo4->nilai;
		$pen_lo_lalu41 = number_format($penlo4->nilai, "2", ",", ".");

		$sqllo7 = "SELECT sum(debet-kredit) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('81','82','83') and kd_skpd='$kd_skpd'";
		$querylo7 = $this->db->query($sqllo7);
		$bello5 = $querylo7->row();
		$bel_lo5 = $bello5->nilai;
		$bel_lo51 = number_format($bello5->nilai, "2", ",", ".");

		$sqllo8 = "SELECT sum(debet-kredit) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('81','82','83') and kd_skpd='$kd_skpd'";
		$querylo8 = $this->db->query($sqllo8);
		$bello6 = $querylo8->row();
		$bel_lo_lalu6 = $bello6->nilai;
		$bel_lo_lalu61 = number_format($bello6->nilai, "2", ",", ".");

		$surplus_lo2 = $pen_lo3 - $bel_lo5;
		if ($surplus_lo2 < 0) {
			$lo7 = "(";
			$surplus_lo2x = $surplus_lo2 * -1;
			$lo8 = ")";
		} else {
			$lo7 = "";
			$surplus_lo2x = $surplus_lo2;
			$lo8 = "";
		}
		$surplus_lo21 = number_format($surplus_lo2x, "2", ",", ".");

		$surplus_lo_lalu2 = $pen_lo_lalu4 - $bel_lo_lalu6;
		if ($surplus_lo_lalu2 < 0) {
			$lo9 = "(";
			$surplus_lo_lalu2x = $surplus_lo_lalu2 * -1;
			$lo10 = ")";
		} else {
			$lo9 = "";
			$surplus_lo_lalu2x = $surplus_lo_lalu2;
			$lo10 = "";
		}
		$surplus_lo_lalu21 = number_format($surplus_lo_lalu2x, "2", ",", ".");

		$selisih_surplus_lo2 = $surplus_lo2 - $surplus_lo_lalu2;
		if ($selisih_surplus_lo2 < 0) {
			$lo11 = "(";
			$selisih_surplus_lo2x = $selisih_surplus_lo2 * -1;
			$lo12 = ")";
		} else {
			$lo11 = "";
			$selisih_surplus_lo2x = $selisih_surplus_lo2;
			$lo12 = "";
		}
		$selisih_surplus_lo21 = number_format($selisih_surplus_lo2x, "2", ",", ".");

		if ($surplus_lo_lalu2 == '' or $surplus_lo_lalu2 == 0) {
			$persen3 = '0,00';
		} else {
			$persen3 = ($surplus_lo2 / $surplus_lo_lalu2) * 100;
			$persen3 = number_format($persen3, "2", ",", ".");
		}

		$sqllo9 = "SELECT sum(kredit-debet) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
		$querylo9 = $this->db->query($sqllo9);
		$penlo7 = $querylo9->row();
		$pen_lo7 = $penlo7->nilai;
		$pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

		$sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
		$querylo10 = $this->db->query($sqllo10);
		$penlo8 = $querylo10->row();
		$pen_lo_lalu8 = $penlo8->nilai;
		$pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

		$sqllo11 = "SELECT sum(debet-kredit) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
		$querylo11 = $this->db->query($sqllo11);
		$bello9 = $querylo11->row();
		$bel_lo9 = $bello9->nilai;
		$bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
		$querylo12 = $this->db->query($sqllo12);
		$bello10 = $querylo12->row();
		$bel_lo_lalu10 = $bello10->nilai;
		$bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

		$surplus_lo3 = $pen_lo7 - $bel_lo9;
		if ($surplus_lo3 < 0) {
			$lo13 = "(";
			$surplus_lo3x = $surplus_lo3 * -1;
			$lo14 = ")";
		} else {
			$lo13 = "";
			$surplus_lo3x = $surplus_lo3;
			$lo14 = "";
		}
		$surplus_lo31 = number_format($surplus_lo3x, "2", ",", ".");

		$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;
		if ($surplus_lo_lalu3 < 0) {
			$lo15 = "(";
			$surplus_lo_lalu3x = $surplus_lo_lalu3 * -1;
			$lo16 = ")";
		} else {
			$lo15 = "";
			$surplus_lo_lalu3x = $surplus_lo_lalu3;
			$lo16 = "";
		}
		$surplus_lo_lalu31 = number_format($surplus_lo_lalu3x, "2", ",", ".");

		$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;
		if ($selisih_surplus_lo3 < 0) {
			$lo17 = "(";
			$selisih_surplus_lo3x = $selisih_surplus_lo3 * -1;
			$lo18 = ")";
		} else {
			$lo17 = "";
			$selisih_surplus_lo3x = $selisih_surplus_lo3;
			$lo18 = "";
		}
		$selisih_surplus_lo31 = number_format($selisih_surplus_lo3x, "2", ",", ".");

		if ($surplus_lo_lalu3 == '' or $surplus_lo_lalu3 == 0) {
			$persen4 = '0,00';
		} else {
			$persen4 = ($surplus_lo3 / $surplus_lo_lalu3) * 100;
			$persen4 = number_format($persen4, "2", ",", ".");
		}


		$sqllo13 = "SELECT sum(kredit-debet) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('74') and kd_skpd='$kd_skpd'";
		$querylo13 = $this->db->query($sqllo13);
		$penlo11 = $querylo13->row();
		$pen_lo11 = $penlo11->nilai;
		$pen_lo111 = number_format($penlo11->nilai, "2", ",", ".");

		$sqllo14 = "SELECT sum(kredit-debet) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('74') and kd_skpd='$kd_skpd'";
		$querylo14 = $this->db->query($sqllo14);
		$penlo12 = $querylo14->row();
		$pen_lo_lalu12 = $penlo12->nilai;
		$pen_lo_lalu121 = number_format($penlo12->nilai, "2", ",", ".");

		$sqllo15 = "SELECT sum(debet-kredit) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('85') and kd_skpd='$kd_skpd'";
		$querylo15 = $this->db->query($sqllo15);
		$bello13 = $querylo15->row();
		$bel_lo13 = $bello13->nilai;
		$bel_lo131 = number_format($bello13->nilai, "2", ",", ".");

		$sqllo16 = "SELECT sum(debet-kredit) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('83') and kd_skpd='$kd_skpd'";
		$querylo16 = $this->db->query($sqllo16);
		$bello14 = $querylo16->row();
		$bel_lo_lalu14 = $bello14->nilai;
		$bel_lo_lalu141 = number_format($bello14->nilai, "2", ",", ".");

		$surplus_lo4 = $pen_lo11 - $bel_lo13;
		if ($surplus_lo4 < 0) {
			$lo19 = "(";
			$surplus_lo4x = $surplus_lo4 * -1;
			$lo20 = ")";
		} else {
			$lo19 = "";
			$surplus_lo4x = $surplus_lo4;
			$lo20 = "";
		}
		$surplus_lo41 = number_format($surplus_lo4x, "2", ",", ".");

		$surplus_lo_lalu4 = $pen_lo_lalu12 - $bel_lo_lalu14;
		if ($surplus_lo_lalu4 < 0) {
			$lo21 = "(";
			$surplus_lo_lalu4x = $surplus_lo_lalu4 * -1;
			$lo22 = ")";
		} else {
			$lo21 = "";
			$surplus_lo_lalu4x = $surplus_lo_lalu4;
			$lo22 = "";
		}
		$surplus_lo_lalu41 = number_format($surplus_lo_lalu4x, "2", ",", ".");

		$selisih_surplus_lo4 = $surplus_lo4 - $surplus_lo_lalu4;
		if ($selisih_surplus_lo4 < 0) {
			$lo23 = "(";
			$selisih_surplus_lo4x = $selisih_surplus_lo4 * -1;
			$lo24 = ")";
		} else {
			$lo23 = "";
			$selisih_surplus_lo4x = $selisih_surplus_lo4;
			$lo24 = "";
		}
		$selisih_surplus_lo41 = number_format($selisih_surplus_lo4x, "2", ",", ".");

		if ($surplus_lo_lalu4 == '' or $surplus_lo_lalu4 == 0) {
			$persen5 = '0,00';
		} else {
			$persen5 = ($surplus_lo4 / $surplus_lo_lalu4) * 100;
			$persen5 = number_format($persen5, "2", ",", ".");
		}


		$sqllo17 = "SELECT sum(kredit-debet) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('75') and kd_skpd='$kd_skpd'";
		$querylo17 = $this->db->query($sqllo17);
		$penlo15 = $querylo17->row();
		$pen_lo15 = $penlo15->nilai;
		$pen_lo151 = number_format($penlo15->nilai, "2", ",", ".");

		$sqllo18 = "SELECT sum(kredit-debet) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('75') and kd_skpd='$kd_skpd'";
		$querylo18 = $this->db->query($sqllo18);
		$penlo16 = $querylo18->row();
		$pen_lo_lalu16 = $penlo16->nilai;
		$pen_lo_lalu161 = number_format($penlo16->nilai, "2", ",", ".");

		$sqllo19 = "SELECT sum(debet-kredit) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and left(kd_rek6,2) in ('84') and kd_skpd='$kd_skpd'";
		$querylo19 = $this->db->query($sqllo19);
		$bello17 = $querylo19->row();
		$bel_lo17 = $bello17->nilai;
		$bel_lo171 = number_format($bello17->nilai, "2", ",", ".");

		$sqllo20 = "SELECT sum(debet-kredit) as nilai from $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,2) in ('84') and kd_skpd='$kd_skpd'";
		$querylo20 = $this->db->query($sqllo20);
		$bello18 = $querylo20->row();
		$bel_lo_lalu18 = $bello18->nilai;
		$bel_lo_lalu181 = number_format($bello18->nilai, "2", ",", ".");

		$surplus_lo5 = $pen_lo15 - $bel_lo17;
		if ($surplus_lo5 < 0) {
			$lo25 = "(";
			$surplus_lo5x = $surplus_lo5 * -1;
			$lo26 = ")";
		} else {
			$lo25 = "";
			$surplus_lo5x = $surplus_lo5;
			$lo26 = "";
		}
		$surplus_lo51 = number_format($surplus_lo5x, "2", ",", ".");

		$surplus_lo_lalu5 = $pen_lo_lalu16 - $bel_lo_lalu18;
		if ($surplus_lo_lalu5 < 0) {
			$lo27 = "(";
			$surplus_lo_lalu5x = $surplus_lo_lalu5 * -1;
			$lo28 = ")";
		} else {
			$lo27 = "";
			$surplus_lo_lalu5x = $surplus_lo_lalu5;
			$lo28 = "";
		}
		$surplus_lo_lalu51 = number_format($surplus_lo_lalu5x, "2", ",", ".");

		$selisih_surplus_lo5 = $surplus_lo5 - $surplus_lo_lalu5;
		if ($selisih_surplus_lo5 < 0) {
			$lo29 = "(";
			$selisih_surplus_lo5x = $selisih_surplus_lo5 * -1;
			$lo30 = ")";
		} else {
			$lo29 = "";
			$selisih_surplus_lo5x = $selisih_surplus_lo5;
			$lo30 = "";
		}
		$selisih_surplus_lo51 = number_format($selisih_surplus_lo5x, "2", ",", ".");

		if ($surplus_lo_lalu5 == '' or $surplus_lo_lalu5 == 0) {
			$persen6 = '0,00';
		} else {
			$persen6 = ($surplus_lo5 / $surplus_lo_lalu5) * 100;
			$persen6 = number_format($persen6, "2", ",", ".");
		}


		$modtahun = $thn_ang % 4;

		if ($modtahun = 0) {
			$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		} else {
			$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		$cRet = '';


		$sclient = $this->akuntansi_model->get_sclient();
		$cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
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

		$sqlmaplo = "SELECT seq, nor, uraian, isnull(kode_1,'-') as kode_1, isnull(kode_2,'-') as kode_2, isnull(kode_3,'-') as kode_3, isnull(cetak,'debet-debet') as cetak FROM map_lo_prov_permen_77 
                   GROUP BY seq, nor, uraian, isnull(kode_1,'-'), isnull(kode_2,'-'), isnull(kode_3,'-'), isnull(cetak,'debet-debet') ORDER BY seq";

		$querymaplo = $this->db->query($sqlmaplo);
		$no     = 0;

		foreach ($querymaplo->result() as $loquery) {

			$nama      = $loquery->uraian;
			$n         = $loquery->kode_1;
			$n		   = ($n == "-" ? "'-'" : $n);
			$n2        = $loquery->kode_2;
			$n2		   = ($n2 == "-" ? "'-'" : $n2);
			$n3        = $loquery->kode_3;
			$n3		   = ($n3 == "-" ? "'-'" : $n3);
			$normal    = $loquery->cetak;

			$quelo01   = "SELECT SUM($normal) as nilai FROM $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$cbulan and kd_skpd='$kd_skpd'";
			$quelo02 = $this->db->query($quelo01);
			$quelo03 = $quelo02->row();
			$nil     = $quelo03->nilai;
			$nilai    = number_format($quelo03->nilai, "2", ",", ".");

			$quelo04   = "SELECT SUM($normal) as nilai FROM $trdju_pkd a inner join $trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd WHERE (left(kd_rek6,4) in ($n) or left(kd_rek6,6) in ($n2) or left(kd_rek6,8) in ($n3)) and year(tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'";
			$quelo05 = $this->db->query($quelo04);
			$quelo06 = $quelo05->row();
			$nil_lalu     = $quelo06->nilai;
			$nilai_lalu    = number_format($quelo06->nilai, "2", ",", ".");

			$real_nilai = $nil - $nil_lalu;
			if ($real_nilai < 0) {
				$lo0 = "(";
				$real_nilaix = $real_nilai * -1;
				$lo00 = ")";
			} else {
				$lo0 = "";
				$real_nilaix = $real_nilai;
				$lo00 = "";
			}
			$real_nilai1 = number_format($real_nilaix, "2", ",", ".");

			if ($nil_lalu == '' or $nil_lalu == 0) {
				$persen1 = '0,00';
			} else {
				$persen1 = ($nil / $nil_lalu) * 100;
				$persen1 = number_format($persen1, "2", ",", ".");
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
				case 105:
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

				case 130:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 135:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 165:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 170:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 175:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;

				case 215:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 220:
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
				case 266:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 271:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 285:
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
				case 310:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 315:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo1$surplus_lo1$lo2</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo3$surplus_lo_lalu1$lo4</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo5$selisih_surplus_lo1$lo6</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen2</td>
                                 </tr>";
					break;
				case 316:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 320:
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

				case 380:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo19$surplus_lo41$lo20</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo21$surplus_lo_lalu41$lo22</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo23$selisih_surplus_lo41$lo24</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen5</td>
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
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo7$surplus_lo21$lo8</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$lo9$surplus_lo_lalu21$lo10</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$lo11$selisih_surplus_lo21$lo12</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen3</td>
                                 </tr>";
					break;
				case 395:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 400:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 405:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 420:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 425:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 445:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 450:
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
		$data['prev'] = $cRet;
		$data['sikap'] = 'preview';
		$judul  = ("LO KONSOL UNIT $cbulan");
		$this->template->set('title', 'LO KONSOL UNIT $cbulan');
		switch ($pilih) {
			case 1;
				// $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
				echo "<title>LO KONSOL UNIT $cbulan</title>";
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

	//================================================ End Cetak LO

	//================================================ Cetak LPE SKPD
	function cetak_lpe_pemda()
	{
		$data['page_title'] = 'LPE KONSOLIDASI';
		$this->template->set('title', 'LPE KONSOLIDASI');
		$this->template->load('template', 'akuntansi/cetak_lpe_pemda', $data);
	}

	// LPE MELAWI
	function ctk_lpe_pemda($cbulan = "", $cetak = 1, $tglttd = "", $ttd = "")
	{
		$id = $this->session->userdata('kdskpd');
		$thn_ang = $this->session->userdata('pcThang');
		$bulan   = $cbulan;
		$tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
		$tglttd = str_replace('n', ' ', $tglttd);
		$id1     = $this->session->userdata('kdskpd');
		//$nmskpd = $this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
		//$nm_skpd = strtoupper($nmskpd);
		$thn_ang_1 = $thn_ang - 1;

		/*$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
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
		$trhju = 'trhju_pkd';
		$trdju = 'trdju_pkd';
		$sqllo10 = "select sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7')";
		$querylo10 = $this->db->query($sqllo10);
		$pen8 = $querylo10->row();
		$pen_lalu8 = $pen8->nilai;
		$pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

		$sqllo12 = "select sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8')";
		$querylo12 = $this->db->query($sqllo12);
		$bel10 = $querylo12->row();
		$bel_lalu10 = $bel10->nilai;
		$bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

		$ekuitas = '310101010001';
		$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103' kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_ll1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102' kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_ll2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_ll3  = $row003->thn_m1;
		}


		$query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM $trdju a INNER JOIN $trhju b 
            ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$ekuitas' AND YEAR(b.tgl_voucher)<'$thn_ang'
            and b.tabel=1 and reev=0");
		foreach ($query3->result_array() as $res2) {
			$debet = $res2['debet'];
			$kredit = $res2['kredit'];
		}

		$real = $kredit - $debet + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;
		//        $this->db->query(" UPDATE map_lpe_skpd SET thn_m1 = '$real' WHERE nor = '1' ");
		//          }
		/*          
            
            $query3 = $this->db->query(" SELECT
                                        SUM(a.debet) AS debet, SUM(a.kredit) AS kredit
                                    FROM
                                        $trdju_pkd a
                                    INNER JOIN $trhju_pkd b ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd
                                    WHERE
                                        b.kd_skpd = '$kd_skpd'
                                    AND left(a.kd_rek6,1) = '9'
                                    AND YEAR (b.tgl_voucher) < '$thn'");  
            foreach($query3->result_array() as $res21){
                 $debet9=$res21['debet'];
                 $kredit9=$res21['kredit'];
                                 
             }
             
        $query3 = $this->db->query(" SELECT
                                        SUM(a.debet) AS debet, SUM(a.kredit) AS kredit
                                    FROM
                                        $trdju_pkd a
                                    INNER JOIN $trhju_pkd b ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd
                                    WHERE
                                        b.kd_skpd = '$kd_skpd'
                                    AND left(a.kd_rek6,1) = '8'
                                    AND YEAR (b.tgl_voucher) < '$thn'");  
            foreach($query3->result_array() as $res22){
                 $debet8=$res22['debet'];
                 $kredit8=$res22['kredit'];
                                 
             }   
             
        $surplus1_1=($kredit8-$debet8)-($debet9-$kredit9);
        $surplus1=number_format($surplus1_1, "2", ".", "");
*/
		//        $this->db->query(" UPDATE map_lpe_skpd SET thn_m1 = '$surplus1' WHERE nor = '2'");

		//      $this->db->query(" UPDATE map_lpe_skpd SET thn_m1 = '$akhir' WHERE nor = '7'");


		// end tahun lalu         

		/*        $sqlsawal = "SELECT * FROM map_lpe_skpd where nor='7'";
        $queryawal = $this->db->query($sqlsawal);
        $jmlsaldo = $queryawal->row();
        $jmlsal = $jmlsaldo->thn_m1;
        
        $sql41 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,1)='8' $skpd";
        $query41 = $this->db->query($sql41);
        $jmlp = $query41->row();
        $jmlpendapatan = $jmlp->nilai;
        $jmlpendapatan1 = number_format($jmlp->nilai, "2", ".", ",");
*/
		//      created by henri_tb
		$sqllo9 = "select sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7')";
		$querylo9 = $this->db->query($sqllo9);
		$penlo7 = $querylo9->row();
		$pen_lo7 = $penlo7->nilai;
		$pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

		$sqllo10 = "select sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7')";
		$querylo10 = $this->db->query($sqllo10);
		$penlo8 = $querylo10->row();
		$pen_lo_lalu8 = $penlo8->nilai;
		$pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

		$sqllo11 = "select sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8')";
		$querylo11 = $this->db->query($sqllo11);
		$bello9 = $querylo11->row();
		$bel_lo9 = $bello9->nilai;
		$bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

		$sqllo12 = "select sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8')";
		$querylo12 = $this->db->query($sqllo12);
		$bello10 = $querylo12->row();
		$bel_lo_lalu10 = $bello10->nilai;
		$bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

		$surplus_lo3 = $pen_lo7 - $bel_lo9;

		$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

		$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;


		$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_lalu1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_lalu2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_lalu3  = $row003->thn_m1;
		}
		$sqllpe2_lalu = "select 6 nor,'LAIN LAIN LALU' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
        inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and left(kd_rek6,4) ='3101' and year(a.tgl_voucher)=$thn_ang_1"; //Henri_TB

            $hasil = $this->db->query($sqllpe2_lalu);
            $nawal = 0;
            foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $nilailpe2_lalu  = $row003->thn_m1;
            }

        $sqlrkppkd_lalu = "select 8 nor,'RK PPKD LALU' uraian,0 parent,40 seq,'3103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
        inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where left(kd_rek6,4) ='3103' and year(a.tgl_voucher)=$thn_ang_1"; //Henri_TB

        $hasil = $this->db->query($sqlrkppkd_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row005) {
            $kd_rek   = $row005->nor;
            $parent   = $row005->parent;
            $nama     = $row005->uraian;
            $nilaiRKPPKD_lalu  = $row005->thn_m1;
        }

        // $sal_awal    = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3 + $nilailpe2_lalu + $nilaiRKPPKD_lalu;
        $sal_awal    = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $nilailpe2_lalu + $nilaiRKPPKD_lalu;
		/*      
        $sql51 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,1)='9'";
        $query51 = $this->db->query($sql51);
        $jmlb = $query51->row();
        $jmlbelanja = $jmlb->nilai;
        $jmlbelanja1 = number_format($jmlb->nilai, "2", ".", ",");
        $sql523 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,3)='923'";
        $query523 = $this->db->query($sql523);
        $jmlbm = $query523->row();
        $jmlbmbelanja = $jmlbm->nilai;
        $jmlbmbelanja1 = number_format($jmlbmbelanja, "2", ".", ",");
        $sql61 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,2)='71'";
        $query61 = $this->db->query($sql61);
        $jmlpm = $query61->row();
        $jmlpmasuk = $jmlpm->nilai;
        $sql62 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,2)='72'";
        $query62 = $this->db->query($sql62);
        $jmlpk = $query62->row();
        $jmlpkeluar = $jmlpk->nilai;
        $surplus = $jmlpendapatan - $jmlbelanja;
*/
		$sql = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //aba

		$hasil = $this->db->query($sql);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$nilaiDR  = $row001->thn_m1;
		}

		$sqllpe1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //Henri_TB

		$hasil = $this->db->query($sqllpe1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$nilailpe1  = $row002->thn_m1;
		}

		$sqllpe2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and left(kd_rek6,4) = '3101' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //Henri_TB

		$hasil = $this->db->query($sqllpe2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$nilailpe2  = $row003->thn_m1;
		}

		$sqleku = "select 7 nor,'EKUITAS' uraian,0 parent,35 seq,'3101'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
        inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where left(kd_rek6,4) ='3101' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //Henri_TB

            $hasil = $this->db->query($sqleku);
            $nawal = 0;
            foreach ($hasil->result() as $row004) {
                $kd_rek   = $row004->nor;
                $parent   = $row004->parent;
                $nama     = $row004->uraian;
                $nilaiEKU  = $row004->thn_m1;
            }

            $sqlrkppkd = "select 8 nor,'RK PPKD' uraian,0 parent,40 seq,'3103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where left(kd_rek6,4) ='3103' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //Henri_TB

        $hasil = $this->db->query($sqlrkppkd);
        $nawal = 0;
        foreach ($hasil->result() as $row005) {
            $kd_rek   = $row005->nor;
            $parent   = $row005->parent;
            $nama     = $row005->uraian;
            $nilaiRKPPKD  = $row005->thn_m1;
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
		$sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2 + $nilaiEKU + $nilaiRKPPKD;

		if ($surplus_lo_lalu3 < 0) {
			$lo15 = "(";
			$surplus_lo_lalu3 = $surplus_lo_lalu3 * -1;
			$lo16 = ")";
		} else {
			$lo15 = "";
			$lo16 = "";
		}
		$surplus_lo_lalu31 = number_format($surplus_lo_lalu3, "2", ",", ".");

		if ($selisih_surplus_lo3 < 0) {
			$lo17 = "(";
			$selisih_surplus_lo3 = $selisih_surplus_lo3 * -1;
			$lo18 = ")";
		} else {
			$lo17 = "";
			$lo18 = "";
		}
		$selisih_surplus_lo31 = number_format($selisih_surplus_lo3, "2", ",", ".");

		if ($nilaiEKU < 0) {
            $l099 = "(";
            $nilaiEKU = $nilaiEKU * -1;
            $p099 = ")";
        } else {
            $l099 = "";
            $nilaiEKU;
            $p099 = "";
        }

        if ($nilaiRKPPKD < 0) {
            $l098 = "(";
            $nilaiRKPPKD = $nilaiRKPPKD * -1;
            $p098 = ")";
        } else {
            $l098 = "";
            $nilaiRKPPKD;
            $p098 = "";
        }

		if ($nilaiRKPPKD_lalu < 0) {
            $l0981 = "(";
            $nilaiRKPPKD_lalu = $nilaiRKPPKD_lalu * -1;
            $p0981 = ")";
        } else {
            $l0981 = "";
            $nilaiRKPPKD_lalu;
            $p0981 = "";
        }

		if ($lpe_lalu1 < 0) {
			$lalu1 = "(";
			$lpe_lalu1 = $lpe_lalu1 * -1;
			$lalu2 = ")";
		} else {
			$lalu1 = "";
			$lpe_lalu1;
			$lalu2 = "";
		}

		if ($lpe_lalu2 < 0) {
			$lalu3 = "(";
			$lpe_lalu2 = $lpe_lalu2 * -1;
			$lalu4 = ")";
		} else {
			$lalu3 = "";
			$lpe_lalu2;
			$lalu4 = "";
		}

		if ($lpe_lalu3 < 0) {
			$lalu5 = "(";
			$lpe_lalu3 = $lpe_lalu3 * -1;
			$lalu6 = ")";
		} else {
			$lalu5 = "";
			$lpe_lalu3;
			$lalu6 = "";
		}

		if ($nilaiDR < 0) {
			$l000 = "(";
			$nilaiDR = $nilaiDR * -1;
			$p000 = ")";
		} else {
			$l000 = "";
			$nilaiDR;
			$p000 = "";
		}

		if ($nilailpe1 < 0) {
			$l001 = "(";
			$nilailpe1 = $nilailpe1 * -1;
			$p001 = ")";
		} else {
			$l001 = "";
			$nilailpe1;
			$p001 = "";
		}

		if ($nilailpe2 < 0) {
			$l002 = "(";
			$nilailpe2 = $nilailpe2 * -1;
			$p002 = ")";
		} else {
			$l002 = "";
			$nilailpe2;
			$p002 = "";
		}

		if ($nilailpe2_lalu < 0) {
            $l0021 = "(";
            $nilailpe2_lalu = $nilailpe2_lalu * -1;
            $p0021 = ")";
        } else {
            $l0021 = "";
            $nilailpe2_lalu;
            $p0021 = "";
        }

		if ($surplus_lo3 < 0) {
			$lo13 = "(";
			$surplus_lo3 = $surplus_lo3 * -1;
			$lo14 = ")";
		} else {
			$lo13 = "";
			$lo14 = "";
		}
		$surplus_lo31 = number_format($surplus_lo3, "2", ",", ".");

		if ($sal_akhir < 0) {
			$c = "(";
			$sal_akhir = $sal_akhir * -1;
			$d = ")";
		} else {
			$c = "";
			$sal_akhir;
			$d = "";
		}

		if ($sal_awal < 0) {
			$c1 = "(";
			$sal_awal = $sal_awal * -1;
			$d1 = ")";
		} else {
			$c1 = "";
			$sal_awal;
			$d1 = "";
		}

		if ($real < 0) {
			$cx = "(";
			$real = $real * -1;
			$dx = ")";
		} else {
			$cx = "";
			$real;
			$dx = "";
		}

		$modtahun = $thn_ang % 4;

		if ($modtahun = 0) {
			$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		} else {
			$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		$sclient = $this->akuntansi_model->get_sclient();

		$cRet = '';
		$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
					<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
							</td>
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong></strong></td>
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

		$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
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

		$sql = "SELECT * FROM map_lpe_permen77_SKPD  ORDER BY seq";

		$hasil = $this->db->query($sql);
		$nawal = 0;
		foreach ($hasil->result() as $row) {

			$kd_rek   = $row->nor;
			$parent   = $row->parent;
			$nama     = $row->uraian;
			$nilai_1    = $row->thn_m1;


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
			switch ($kd_rek) {
				case 1:
					$cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\"> $c1" . number_format($sal_awal, "2", ",", ".") . "$d1</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$cx" . number_format($real, "2", ",", ".") . "$dx</td>
                                                     </tr>";

					break;
				case 2:
					$cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\"> $lo13" . number_format($surplus_lo3, "2", ",", ".") . "$lo14</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$lo15" . number_format($surplus_lo_lalu3, "2", ",", ".") . "$lo16</td>
                                                     </tr>";

					break;
				case 3:
					$cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\"></td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\"></td>
                                                     </tr>";

					break;
				case 4:
					$cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$l001" . number_format($nilailpe1, "2", ",", ".") . "$p001</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$lalu1" . number_format($lpe_lalu2, "2", ",", ".") . "$lalu2</td>
                                                     </tr>";
					break;
				case 5:
					$cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$l000" . number_format($nilaiDR, "2", ",", ".") . "$p000</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$lalu3" . number_format($lpe_lalu1, "2", ",", ".") . "$lalu4</td>
                                                     </tr>";
					break;
				case 6:
					$cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$l002" . number_format($nilailpe2, "2", ",", ".") . "$p002</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$l0021" . number_format($nilailpe2_lalu, "2", ",", ".") . "$p0021</td>
                                                     </tr>";
					break;
					case 7:
                        $cRet .= "<tr>
                                                              <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
                                                              <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$nama</td>
                                                              <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$l099" . number_format($nilaiEKU, "2", ",", ".") . "$p099</td>
                                                              <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$lalu5" . number_format($lpe_lalu3, "2", ",", ".") . "$lalu5</td>
                                                             </tr>";
                        break;
				case 8:
					$cRet .= "<tr>
					<td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
					<td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$nama</td>
					<td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$l098" . number_format($nilaiRKPPKD, "2", ",", ".") . "$p098</td>
					<td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$l0981" . number_format($nilaiRKPPKD_lalu, "2", ",", ".") . "$p0981</td>
				   </tr>";
				break;
				case 9:
					$cRet .= "<tr>
													<td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
													<td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$nama</td>
													<td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$c" . number_format($sal_akhir, "2", ",", ".") . "$d</td>
													<td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$c1" . number_format($sal_awal, "2", ",", ".") . "$d1</td>
													</tr>";
			}
		}

		$cRet .= '</table>';

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$tglttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUP')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}

		
		
		if ($nip == '00000000 000000 0 000'){
			$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Sanggau , ' . $tanggal . '</TD>
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
	} else if ($nip == '19671126 199503 2 004'){
			$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Sanggau , ' . $tanggal . '</TD>
							</TR>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . strtoupper($jabatan) . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b></TD>
								<TD width="50%" align="center" >selaku <br>PEJABAT PENGELOLA KEUANGAN DAERAH</TD>
							</TR>
							<TR>
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
								<TD align="center" ><b><u>' . $namax . '</b></u></TD>
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
			} else {
			$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Sanggau , ' . $tanggal . '</TD>
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
								<TD align="center" ><u><b>' . $namax . '</b></u></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >'.$nip.'</TD>
							</TR>
							</TABLE><br/>';
			}

		$data['prev'] = $cRet;
		$data['sikap'] = 'preview';
		$judul = ("LPE KONSOL $cbulan");
		$this->template->set('title', 'LPE KONSOL UNIT $cbulan');
		switch ($cetak) {
			case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
			case 1;
				echo "<title>LPE KONSOL UNIT $cbulan</title>";
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


	//====== LPE SKPD
	function ctk_lpe_pemda_unit($cbulan = "", $kd_skpd = "", $cetak = 1,$tglttd = "", $ttd = "")
	{
		$id = $this->session->userdata('kdskpd');
		$thn_ang = $this->session->userdata('pcThang');
		$bulan   = $cbulan;
		$tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
		$tglttd = str_replace('n', ' ', $tglttd);
		$id1     = $this->session->userdata('kdskpd');
		$nmskpd = $this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
		$nm_skpd = strtoupper($nmskpd);
		$thn_ang_1 = $thn_ang - 1;

		/*$sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
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
		$trhju = 'trhju_pkd';
		$trdju = 'trdju_pkd';
		$sqllo10 = "select sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
		$querylo10 = $this->db->query($sqllo10);
		$pen8 = $querylo10->row();
		$pen_lalu8 = $pen8->nilai;
		$pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

		$sqllo12 = "select sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
		$querylo12 = $this->db->query($sqllo12);
		$bel10 = $querylo12->row();
		$bel_lalu10 = $bel10->nilai;
		$bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

		$ekuitas = '310101010001';
		$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103' kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_ll1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102' kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_ll2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_ll3  = $row003->thn_m1;
		}


		$query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM $trdju a INNER JOIN $trhju b 
            ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$ekuitas' AND YEAR(b.tgl_voucher)<'$thn_ang'
            and b.tabel=1 and reev=0 and kd_skpd='$kd_skpd'");
		foreach ($query3->result_array() as $res2) {
			$debet = $res2['debet'];
			$kredit = $res2['kredit'];
		}

		$real = $kredit - $debet + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;
		//        $this->db->query(" UPDATE map_lpe_skpd SET thn_m1 = '$real' WHERE nor = '1' ");
		//          }
		/*          
            
            $query3 = $this->db->query(" SELECT
                                        SUM(a.debet) AS debet, SUM(a.kredit) AS kredit
                                    FROM
                                        $trdju_pkd a
                                    INNER JOIN $trhju_pkd b ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd
                                    WHERE
                                        b.kd_skpd = '$kd_skpd'
                                    AND left(a.kd_rek6,1) = '9'
                                    AND YEAR (b.tgl_voucher) < '$thn'");  
            foreach($query3->result_array() as $res21){
                 $debet9=$res21['debet'];
                 $kredit9=$res21['kredit'];
                                 
             }
             
        $query3 = $this->db->query(" SELECT
                                        SUM(a.debet) AS debet, SUM(a.kredit) AS kredit
                                    FROM
                                        $trdju_pkd a
                                    INNER JOIN $trhju_pkd b ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd
                                    WHERE
                                        b.kd_skpd = '$kd_skpd'
                                    AND left(a.kd_rek6,1) = '8'
                                    AND YEAR (b.tgl_voucher) < '$thn'");  
            foreach($query3->result_array() as $res22){
                 $debet8=$res22['debet'];
                 $kredit8=$res22['kredit'];
                                 
             }   
             
        $surplus1_1=($kredit8-$debet8)-($debet9-$kredit9);
        $surplus1=number_format($surplus1_1, "2", ".", "");
*/
		//        $this->db->query(" UPDATE map_lpe_skpd SET thn_m1 = '$surplus1' WHERE nor = '2'");

		//      $this->db->query(" UPDATE map_lpe_skpd SET thn_m1 = '$akhir' WHERE nor = '7'");


		// end tahun lalu         

		/*        $sqlsawal = "SELECT * FROM map_lpe_skpd where nor='7'";
        $queryawal = $this->db->query($sqlsawal);
        $jmlsaldo = $queryawal->row();
        $jmlsal = $jmlsaldo->thn_m1;
        
        $sql41 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,1)='8' $skpd";
        $query41 = $this->db->query($sql41);
        $jmlp = $query41->row();
        $jmlpendapatan = $jmlp->nilai;
        $jmlpendapatan1 = number_format($jmlp->nilai, "2", ".", ",");
*/
		//      created by henri_tb
		$sqllo9 = "select sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
		$querylo9 = $this->db->query($sqllo9);
		$penlo7 = $querylo9->row();
		$pen_lo7 = $penlo7->nilai;
		$pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

		$sqllo10 = "select sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
		$querylo10 = $this->db->query($sqllo10);
		$penlo8 = $querylo10->row();
		$pen_lo_lalu8 = $penlo8->nilai;
		$pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

		$sqllo11 = "select sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
		$querylo11 = $this->db->query($sqllo11);
		$bello9 = $querylo11->row();
		$bel_lo9 = $bello9->nilai;
		$bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

		$sqllo12 = "select sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
		$querylo12 = $this->db->query($sqllo12);
		$bello10 = $querylo12->row();
		$bel_lo_lalu10 = $bello10->nilai;
		$bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

		$surplus_lo3 = $pen_lo7 - $bel_lo9;

		$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

		$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;


		$sql_lalu = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_lalu1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_lalu2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_lalu3  = $row003->thn_m1;
		}
		$sqllpe2_lalu = "select 6 nor,'LAIN LAIN LALU' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
        inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and left(kd_rek6,4) ='3101' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

            $hasil = $this->db->query($sqllpe2_lalu);
            $nawal = 0;
            foreach ($hasil->result() as $row003) {
            $kd_rek   = $row003->nor;
            $parent   = $row003->parent;
            $nama     = $row003->uraian;
            $nilailpe2_lalu  = $row003->thn_m1;
            }

        $sqlrkppkd_lalu = "select 8 nor,'RK PPKD LALU' uraian,0 parent,40 seq,'3103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
        inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where left(kd_rek6,4) ='3103' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqlrkppkd_lalu);
        $nawal = 0;
        foreach ($hasil->result() as $row005) {
            $kd_rek   = $row005->nor;
            $parent   = $row005->parent;
            $nama     = $row005->uraian;
            $nilaiRKPPKD_lalu  = $row005->thn_m1;
        }

        // $sal_awal    = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3 + $nilailpe2_lalu + $nilaiRKPPKD_lalu;
        $sal_awal    = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $nilailpe2_lalu + $nilaiRKPPKD_lalu;
		/*      
        $sql51 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,1)='9'";
        $query51 = $this->db->query($sql51);
        $jmlb = $query51->row();
        $jmlbelanja = $jmlb->nilai;
        $jmlbelanja1 = number_format($jmlb->nilai, "2", ".", ",");
        $sql523 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,3)='923'";
        $query523 = $this->db->query($sql523);
        $jmlbm = $query523->row();
        $jmlbmbelanja = $jmlbm->nilai;
        $jmlbmbelanja1 = number_format($jmlbmbelanja, "2", ".", ",");
        $sql61 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,2)='71'";
        $query61 = $this->db->query($sql61);
        $jmlpm = $query61->row();
        $jmlpmasuk = $jmlpm->nilai;
        $sql62 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek6,2)='72'";
        $query62 = $this->db->query($sql62);
        $jmlpk = $query62->row();
        $jmlpkeluar = $jmlpk->nilai;
        $surplus = $jmlpendapatan - $jmlbelanja;
*/
		$sql = "select 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$nilaiDR  = $row001->thn_m1;
		}

		$sqllpe1 = "select 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$nilailpe1  = $row002->thn_m1;
		}

		$sqllpe2 = "select 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
                    inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and left(kd_rek6,4) = '3101' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$nilailpe2  = $row003->thn_m1;
		}

		$sqleku = "select 7 nor,'EKUITAS' uraian,0 parent,35 seq,'3101'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
        inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where left(kd_rek6,4) ='3101' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

            $hasil = $this->db->query($sqleku);
            $nawal = 0;
            foreach ($hasil->result() as $row004) {
                $kd_rek   = $row004->nor;
                $parent   = $row004->parent;
                $nama     = $row004->uraian;
                $nilaiEKU  = $row004->thn_m1;
            }

            $sqlrkppkd = "select 8 nor,'RK PPKD' uraian,0 parent,40 seq,'3103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where left(kd_rek6,4) ='3103' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

        $hasil = $this->db->query($sqlrkppkd);
        $nawal = 0;
        foreach ($hasil->result() as $row005) {
            $kd_rek   = $row005->nor;
            $parent   = $row005->parent;
            $nama     = $row005->uraian;
            $nilaiRKPPKD  = $row005->thn_m1;
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
		$sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2 + $nilaiEKU + $nilaiRKPPKD;

		if ($surplus_lo_lalu3 < 0) {
			$lo15 = "(";
			$surplus_lo_lalu3 = $surplus_lo_lalu3 * -1;
			$lo16 = ")";
		} else {
			$lo15 = "";
			$lo16 = "";
		}
		$surplus_lo_lalu31 = number_format($surplus_lo_lalu3, "2", ",", ".");

		if ($selisih_surplus_lo3 < 0) {
			$lo17 = "(";
			$selisih_surplus_lo3 = $selisih_surplus_lo3 * -1;
			$lo18 = ")";
		} else {
			$lo17 = "";
			$lo18 = "";
		}
		$selisih_surplus_lo31 = number_format($selisih_surplus_lo3, "2", ",", ".");

		if ($nilaiEKU < 0) {
            $l099 = "(";
            $nilaiEKU = $nilaiEKU * -1;
            $p099 = ")";
        } else {
            $l099 = "";
            $nilaiEKU;
            $p099 = "";
        }

        if ($nilaiRKPPKD < 0) {
            $l098 = "(";
            $nilaiRKPPKD = $nilaiRKPPKD * -1;
            $p098 = ")";
        } else {
            $l098 = "";
            $nilaiRKPPKD;
            $p098 = "";
        }

		// LALU
		if ($nilaiRKPPKD_lalu < 0) {
            $l0981 = "(";
            $nilaiRKPPKD_lalu = $nilaiRKPPKD_lalu * -1;
            $p0981 = ")";
        } else {
            $l0981 = "";
            $nilaiRKPPKD_lalu;
            $p0981 = "";
        }

		if ($lpe_lalu1 < 0) {
			$lalu1 = "(";
			$lpe_lalu1 = $lpe_lalu1 * -1;
			$lalu2 = ")";
		} else {
			$lalu1 = "";
			$lpe_lalu1;
			$lalu2 = "";
		}

		if ($lpe_lalu2 < 0) {
			$lalu3 = "(";
			$lpe_lalu2 = $lpe_lalu2 * -1;
			$lalu4 = ")";
		} else {
			$lalu3 = "";
			$lpe_lalu2;
			$lalu4 = "";
		}

		if ($lpe_lalu3 < 0) {
			$lalu5 = "(";
			$lpe_lalu3 = $lpe_lalu3 * -1;
			$lalu6 = ")";
		} else {
			$lalu5 = "";
			$lpe_lalu3;
			$lalu6 = "";
		}

		if ($nilaiDR < 0) {
			$l000 = "(";
			$nilaiDR = $nilaiDR * -1;
			$p000 = ")";
		} else {
			$l000 = "";
			$nilaiDR;
			$p000 = "";
		}

		if ($nilailpe1 < 0) {
			$l001 = "(";
			$nilailpe1 = $nilailpe1 * -1;
			$p001 = ")";
		} else {
			$l001 = "";
			$nilailpe1;
			$p001 = "";
		}

		if ($nilailpe2 < 0) {
			$l002 = "(";
			$nilailpe2 = $nilailpe2 * -1;
			$p002 = ")";
		} else {
			$l002 = "";
			$nilailpe2;
			$p002 = "";
		}

		if ($nilailpe2_lalu < 0) {
            $l0021 = "(";
            $nilailpe2_lalu = $nilailpe2_lalu * -1;
            $p0021 = ")";
        } else {
            $l0021 = "";
            $nilailpe2_lalu;
            $p0021 = "";
        }

		if ($surplus_lo3 < 0) {
			$lo13 = "(";
			$surplus_lo3 = $surplus_lo3 * -1;
			$lo14 = ")";
		} else {
			$lo13 = "";
			$lo14 = "";
		}
		$surplus_lo31 = number_format($surplus_lo3, "2", ",", ".");

		if ($sal_akhir < 0) {
			$c = "(";
			$sal_akhir = $sal_akhir * -1;
			$d = ")";
		} else {
			$c = "";
			$sal_akhir;
			$d = "";
		}

		if ($sal_awal < 0) {
			$c1 = "(";
			$sal_awal = $sal_awal * -1;
			$d1 = ")";
		} else {
			$c1 = "";
			$sal_awal;
			$d1 = "";
		}

		if ($real < 0) {
			$cx = "(";
			$real = $real * -1;
			$dx = ")";
		} else {
			$cx = "";
			$real;
			$dx = "";
		}

		$modtahun = $thn_ang % 4;

		if ($modtahun = 0) {
			$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		} else {
			$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		$sclient = $this->akuntansi_model->get_sclient();

		$cRet = '';
		$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
					<td rowspan=\"4\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
							</td>
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
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

		$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
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

		$sql = "SELECT * FROM map_lpe_permen77_SKPD  ORDER BY seq";

		$hasil = $this->db->query($sql);
		$nawal = 0;
		foreach ($hasil->result() as $row) {

			$kd_rek   = $row->nor;
			$parent   = $row->parent;
			$nama     = $row->uraian;
			$nilai_1    = $row->thn_m1;


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
			switch ($kd_rek) {
				case 1:
					$cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\"> $c1" . number_format($sal_awal, "2", ",", ".") . "$d1</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$cx" . number_format($real, "2", ",", ".") . "$dx</td>
                                                     </tr>";

					break;
				case 2:
					$cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\"> $lo13" . number_format($surplus_lo3, "2", ",", ".") . "$lo14</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$lo15" . number_format($surplus_lo_lalu3, "2", ",", ".") . "$lo16</td>
                                                     </tr>";

					break;
				case 3:
					$cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\"></td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\"></td>
                                                     </tr>";

					break;
				case 4:
					$cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$l001" . number_format($nilailpe1, "2", ",", ".") . "$p001</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$lalu1" . number_format($lpe_lalu2, "2", ",", ".") . "$lalu2</td>
                                                     </tr>";
					break;
				case 5:
					$cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$l000" . number_format($nilaiDR, "2", ",", ".") . "$p000</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$lalu3" . number_format($lpe_lalu1, "2", ",", ".") . "$lalu4</td>
                                                     </tr>";
					break;
				case 6:
					$cRet .= "<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$l002" . number_format($nilailpe2, "2", ",", ".") . "$p002</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$l0021" . number_format($nilailpe2_lalu, "2", ",", ".") . "$p0021</td>
                                                     </tr>";
					break;
					case 7:
                        $cRet .= "<tr>
                                                              <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
                                                              <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$nama</td>
                                                              <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$l099" . number_format($nilaiEKU, "2", ",", ".") . "$p099</td>
                                                              <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$lalu5" . number_format($lpe_lalu3, "2", ",", ".") . "$lalu5</td>
                                                             </tr>";
                        break;
				case 8:
					$cRet .= "<tr>
					<td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
					<td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$nama</td>
					<td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$l098" . number_format($nilaiRKPPKD, "2", ",", ".") . "$p098</td>
					<td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$l0981" . number_format($nilaiRKPPKD_lalu, "2", ",", ".") . "$p0981</td>
				   </tr>";
				break;
				case 9:
					$cRet .= "<tr>
													<td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$kd_rek</td>
													<td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$nama</td>
													<td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$c" . number_format($sal_akhir, "2", ",", ".") . "$d</td>
													<td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;font-family:Arial;border-bottom:none;border-top:none\">$c1" . number_format($sal_awal, "2", ",", ".") . "$d1</td>
													</tr>";
			}
		}

		$cRet .= '</table>';

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$tglttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUP')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}

		
		
		if ($nip == '00000000 000000 0 000'){
			$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Sanggau , ' . $tanggal . '</TD>
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
	} else if ($nip == '19671126 199503 2 004'){
			$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Sanggau , ' . $tanggal . '</TD>
							</TR>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . strtoupper($jabatan) . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b></TD>
								<TD width="50%" align="center" >selaku <br>PEJABAT PENGELOLA KEUANGAN DAERAH</TD>
							</TR>
							<TR>
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
								<TD align="center" ><b><u>' . $namax . '</b></u></TD>
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
			} else {
			$cRet .= '<br><br>
				<TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Sanggau , ' . $tanggal . '</TD>
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
								<TD align="center" ><u><b>' . $namax . '</b></u></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >'.$nip.'</TD>
							</TR>
							</TABLE><br/>';
			}

		$data['prev'] = $cRet;
		$data['sikap'] = 'preview';
		$judul = ("LPE KONSOL $cbulan");
		$this->template->set('title', 'LPE KONSOL UNIT $cbulan');
		switch ($cetak) {
			case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
			case 1;
				echo "<title>LPE KONSOL UNIT $cbulan</title>";
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


	//================================================ End Cetak LPE SKPD

	//================================================ Cetak Neraca
	function cetak_neraca_pemda()
	{
		$data['page_title'] = 'LAPORAN NERACA KONSOLIDASI';
		$this->template->set('title', 'LAPORAN NERACA KONSOLIDASI');
		$this->template->load('template', 'akuntansi/cetak_neraca_pemda', $data);
	}

	function rpt_neraca_pemda($cbulan = "", $cetak = 1, $tglttd = "", $ttd = "")
	{
		//$bulan	 = $_REQUEST['tgl1'];
		$kd_skpd   	= $this->session->userdata('kdskpd');
		$thn_ang	= $this->session->userdata('pcThang');
		$tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
		$tglttd = str_replace('n', ' ', $tglttd);
		$thn_ang_1	= $thn_ang - 1;
		$bulan	 = $cbulan;
		$cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;

		$sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd' ";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {

			$nmskpd  = $rowsc->nm_skpd;
		}

		$nm_skpd	= strtoupper($nmskpd);

		$modtahun = $thn_ang % 4;

		if ($modtahun = 0) {
			$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		} else {
			$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		$cRet = '';

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet = "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
					<tr>
						<td rowspan=\"3\" align=\"left\" width=\"2%\">
						<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"60\" height=\"70\" />
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
					<TR>
						<td align=\"center\"><strong>NERACA</strong></td>
					</TR>
					<TR>
						<td align=\"center\"><strong>PER $arraybulan[$bulan] $thn_ang DAN $thn_ang_1 </strong></td>
					</TR>
					</TABLE><br>";

		$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
							<td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
							<td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>KODE REKENING</b></td>
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
                            <td style=\"border-top: none;\"></td>                                             
                         </tr>
                     </tfoot>
                   
                     <tr>	
					 		<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
					 		<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
							<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"55%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                           
                        </tr>";


		//level 1

		// Created by Henri_TB

		$trhju = 'trhju_pkd';
		$trdju = 'trdju_pkd';
		$ekuitas = '310101010001';
		$sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7')";
		$querylo10 = $this->db->query($sqllo10);
		$pen8 = $querylo10->row();
		$pen_lalu8 = $pen8->nilai;
		$pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8')";
		$querylo12 = $this->db->query($sqllo12);
		$bel10 = $querylo12->row();
		$bel_lalu10 = $bel10->nilai;
		$bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

		$sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_ll1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_ll2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_ll3  = $row003->thn_m1;
		}


		$query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM $trdju a INNER JOIN $trhju b 
			ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$ekuitas' AND YEAR(b.tgl_voucher)<'$thn_ang'
			and b.tabel=1 and reev=0");
		foreach ($query3->result_array() as $res2) {
			$debet3 = $res2['debet'];
			$kredit3 = $res2['kredit'];
		}

		$real = $kredit3 - $debet3 + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;

		//		created by henri_tb
		$sqllo9 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7')";
		$querylo9 = $this->db->query($sqllo9);
		$penlo7 = $querylo9->row();
		$pen_lo7 = $penlo7->nilai;
		$pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

		$sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7')";
		$querylo10 = $this->db->query($sqllo10);
		$penlo8 = $querylo10->row();
		$pen_lo_lalu8 = $penlo8->nilai;
		$pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

		$sqllo11 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8')";
		$querylo11 = $this->db->query($sqllo11);
		$bello9 = $querylo11->row();
		$bel_lo9 = $bello9->nilai;
		$bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8')";
		$querylo12 = $this->db->query($sqllo12);
		$bello10 = $querylo12->row();
		$bel_lo_lalu10 = $bello10->nilai;
		$bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

		$surplus_lo3 = $pen_lo7 - $bel_lo9;

		$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

		$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;

		$sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_lalu1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_lalu2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_lalu3  = $row003->thn_m1;
		}

		$sal_awal	= $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;

		$sql = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //aba

		$hasil = $this->db->query($sql);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$nilaiDR  = $row001->thn_m1;
		}

		$sqllpe1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //Henri_TB

		$hasil = $this->db->query($sqllpe1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$nilailpe1  = $row002->thn_m1;
		}

		$sqllpe2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //Henri_TB

		$hasil = $this->db->query($sqllpe2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$nilailpe2  = $row003->thn_m1;
		}

		$sqleku = "SELECT  7 nor,'EKUITAS' uraian,0 parent,35 seq,'3101'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
		inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where left(kd_rek6,4) ='3101' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //Henri_TB

		$hasil = $this->db->query($sqleku);
		$nawal = 0;
		foreach ($hasil->result() as $row004) {
			$kd_rek   = $row004->nor;
			$parent   = $row004->parent;
			$nama     = $row004->uraian;
			$nilaiEKU  = $row004->thn_m1;
		}

		$sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2 + $nilaiEKU;

		$sqlutang_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)='2' and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqlutang_lalu);
		foreach ($hasil->result() as $row) {
			$nilaiutang_lalu  = $row->thn_m1;
		}

		$sqlkas_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqlkas_lalu);
		foreach ($hasil->result() as $row) {
			$rk_ppkd_lalu  = $row->thn_m1;
		}

		$sqlskpd_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqlskpd_lalu);
		foreach ($hasil->result() as $row) {
			$rk_skpd_lalu  = $row->thn_m1;
		}

		$sqllcr_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek6,2)='11' and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllcr_lalu);
		foreach ($hasil->result() as $row) {
			$lcrx_lalu  = $row->thn_m1;
		}

		$sqlast_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek6,1)='1' and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqlast_lalu);
		foreach ($hasil->result() as $row) {
			$astx_lalu  = $row->thn_m1;
		}

		$lcr_lalu		= $lcrx_lalu - $rk_skpd_lalu;
		$ast_lalu		= $astx_lalu - $rk_skpd_lalu;
		$eku_lalu 		= $sal_awal + $rk_ppkd_lalu - $rk_skpd_lalu;
		$eku_tang_lalu 	= $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu - $rk_skpd_lalu;

		$sqlutang = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher
			and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)='2' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqlutang);
		foreach ($hasil->result() as $row) {
			$nilaiutang  = $row->thn_m1;
		}

		$sqlkas = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqlkas);
		foreach ($hasil->result() as $row) {
			$rk_ppkd  = $row->thn_m1;
		}

		$sqlskpd = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqlskpd);
		foreach ($hasil->result() as $row) {
			$rk_skpd  = $row->thn_m1;
		}

		$sqllcr = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek6,2)='11' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqllcr);
		foreach ($hasil->result() as $row) {
			$lcrx = $row->thn_m1;
		}

		$sqlast = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek6,1)='1' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqlast);
		foreach ($hasil->result() as $row) {
			$astx  = $row->thn_m1;
		}

		$lcr			= $lcrx - $rk_skpd;
		$ast			= $astx - $rk_skpd;
		$eku 			= $sal_akhir + $rk_ppkd;
		$eku_tang 		= $sal_akhir + $nilaiutang + $rk_ppkd;

		if ($sal_akhir < 0) {
			$c = "(";
			$sal_akhir = $sal_akhir * -1;
			$d = ")";
		} else {
			$c = "";
			$sal_akhir;
			$d = "";
		}

		$sal_akhir1 = number_format($sal_akhir, "2", ",", ".");

		if ($sal_awal < 0) {
			$c1 = "(";
			$sal_awal = $sal_awal * -1;
			$d1 = ")";
		} else {
			$c1 = "";
			$sal_awal;
			$d1 = "";
		}

		$sal_awal1 = number_format($sal_awal, "2", ",", ".");


		if ($eku_lalu < 0) {
			$min001 = "(";
			$eku_lalu = $eku_lalu * -1;
			$min002 = ")";
		} else {
			$min001 = "";
			$eku_lalu;
			$min002 = "";
		}

		$eku_lalu1 = number_format($eku_lalu, "2", ",", ".");

		if ($eku < 0) {
			$min003 = "(";
			$eku = $eku * -1;
			$min004 = ")";
		} else {
			$min003 = "";
			$eku;
			$min004 = "";
		}

		$eku1 = number_format($eku, "2", ",", ".");

		if ($eku_tang_lalu < 0) {
			$min005 = "(";
			$eku_tang_lalu = $eku_tang_lalu * -1;
			$min006 = ")";
		} else {
			$min005 = "";
			$eku_tang_lalu;
			$min006 = "";
		}

		$eku_tang_lalu1 = number_format($eku_tang_lalu, "2", ",", ".");

		if ($eku_tang < 0) {
			$min007 = "(";
			$eku_tang = $eku_tang * -1;
			$min008 = ")";
		} else {
			$min007 = "";
			$eku_tang;
			$min008 = "";
		}

		$eku_tang1 = number_format($eku_tang, "2", ",", ".");

		if ($rk_ppkd_lalu < 0) {
			$min009 = "(";
			$rk_ppkd_lalu = $rk_ppkd_lalu * -1;
			$min010 = ")";
		} else {
			$min009 = "";
			$rk_ppkd_lalu;
			$min010 = "";
		}

		$rk_ppkd_lalu1 = number_format($rk_ppkd_lalu, "2", ",", ".");

		if ($rk_ppkd < 0) {
			$min013 = "(";
			$rk_ppkd = $rk_ppkd * -1;
			$min014 = ")";
		} else {
			$min013 = "";
			$rk_ppkd;
			$min014 = "";
		}

		$rk_ppkd1 = number_format($rk_ppkd, "2", ",", ".");

		if ($lcr < 0) {
			$min015 = "(";
			$lcr = $lcr * -1;
			$min016 = ")";
		} else {
			$min015 = "";
			$lcr;
			$min016 = "";
		}

		$lcr1 = number_format($lcr, "2", ",", ".");

		if ($lcr_lalu < 0) {
			$min017 = "(";
			$lcr_lalu = $lcr_lalu * -1;
			$min018 = ")";
		} else {
			$min017 = "";
			$lcr_lalu;
			$min018 = "";
		}

		$lcr_lalu1 = number_format($lcr_lalu, "2", ",", ".");

		if ($ast < 0) {
			$min019 = "(";
			$ast = $ast * -1;
			$min020 = ")";
		} else {
			$min019 = "";
			$ast;
			$min020 = "";
		}

		$ast1 = number_format($ast, "2", ",", ".");

		if ($ast_lalu < 0) {
			$min021 = "(";
			$ast_lalu = $ast_lalu * -1;
			$min022 = ")";
		} else {
			$min021 = "";
			$ast_lalu;
			$min022 = "";
		}

		$ast_lalu1 = number_format($ast_lalu, "2", ",", ".");

		$queryneraca = " SELECT kode, uraian, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
										isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
										isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
										isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
										FROM map_neraca_permen_77_2023 ORDER BY seq ";

		$query10 = $this->db->query($queryneraca);

		$no     = 0;

		foreach ($query10->result_array() as $res) {
			$uraian = $res['uraian'];
			$normal = $res['normal'];

			$kode_1 = trim($res['kode_1']);
			$kode_2 = trim($res['kode_2']);
			$kode_3 = trim($res['kode_3']);
			$kode_4 = trim($res['kode_4']);
			$kode_5 = trim($res['kode_5']);
			$kode_6 = trim($res['kode_6']);
			$kode_7 = trim($res['kode_7']);
			$kode_8 = trim($res['kode_8']);
			$kode_9 = trim($res['kode_9']);
			$kode_10 = trim($res['kode_10']);
			$kode_11 = trim($res['kode_11']);
			$kode_12 = trim($res['kode_12']);
			$kode_13 = trim($res['kode_13']);
			$kode_14 = trim($res['kode_14']);
			$kode_15 = trim($res['kode_15']);

			$konversiLra = substr($kode_1,0,4);
			if (($konversiLra >= 1301 && $konversiLra < 1306)) {
				$length = strlen($kode_1);
				$lra =  "52".substr($kode_1,2);
				$query = "SELECT SUM(trd.debet) AS debet, SUM(trd.kredit) AS kredit FROM trhju_pkd AS trh
					INNER JOIN trdju_pkd AS trd ON trd.kd_unit = trh.kd_skpd AND trd.no_voucher = trh.no_voucher
					WHERE LEFT(trd.kd_rek6,$length) = ? AND YEAR(trh.tgl_voucher) = ? AND MONTH(trh.tgl_voucher)  <= ? AND trd.kd_rek6 NOT IN ('520399999999','520288888888','520299999999','520388888888')
					OR (LEFT(trd.kd_rek6, $length) = ? AND trh.no_voucher LIKE '%-LO-NERACA-Belanja%')
				";
				$q = $this->db->query($query, [$lra, $thn_ang, $xbulan, $kode_1]);
			} else {
				$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
									and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and
										(kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
										kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
										kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
										kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
										kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
										kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
										kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
										kd_rek6 like '$kode_15%') ");
			}

			foreach ($q->result_array() as $r) {
				$debet = $r['debet'];
				$kredit = $r['kredit'];
			}

			if ($debet == '') $debet = 0;
			if ($kredit == '') $kredit = 0;

			if ($normal == 1) {
				$nl = $debet - $kredit;
			} else {
				$nl = $kredit - $debet;
			}
			if ($nl == '') $nl = 0;

			// Jurnal Tahun lalu
			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
									and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and
										(kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
										kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
										kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
										kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
										kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
										kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
										kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
										kd_rek6 like '$kode_15%') ");

			foreach ($q->result_array() as $rx) {
				$debet_lalu = $rx['debet'];
				$kredit_lalu = $rx['kredit'];
			}

			if ($debet_lalu == '') $debet_lalu = 0;
			if ($kredit_lalu == '') $kredit_lalu = 0;

			if ($normal == 1) {
				$sblm = $debet_lalu - $kredit_lalu;
			} else {
				$sblm = $kredit_lalu - $debet_lalu;
			}
			if ($sblm == '') $sblm = 0;

			if ($nl < 0) {
				$nl001 = "(";
				$nl = $nl * -1;
				$ln001 = ")";
			} else {
				$nl001 = "";
				$ln001 = "";
			}
			if ($sblm < 0) {
				$sblm001 = "(";
				$sblm = $sblm * -1;
				$mlbs001 = ")";
			} else {
				$sblm001 = "";
				$mlbs001 = "";
			}
			if(($konversiLra >= 1301 && $konversiLra < 1306)) {
				$nl1 = number_format(($nl+$sblm), "2", ",", ".");
			}elseif (substr($kode_1, 0, 4) == 2106)  {
				$nl1 = number_format($sblm, "2", ",", ".");
			}else  {
				$nl1 = number_format($nl, "2", ",", ".");
			}
			$sblm1 = number_format($sblm, "2", ",", ".");

			$no       = $no + 1;

			switch ($res['seq']) {
				case 5:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min019$ast1$min020</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min021$ast_lalu1$min022</td>
                                 </tr>";
					break;
				case 10:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min015$lcr1$min016</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min017$lcr_lalu1$min018</td>
                                 </tr>";
					break;
				case 15:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 60:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 </tr>";
					break;
				case 65:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 </tr>";
					break;
				case 100:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 </tr>";
					break;
				case 105:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 </tr>";
					break;

				case 110:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                     <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 115:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 </tr>";
					break;
				case 120:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 </tr>";
					break;
				case 125:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 </tr>";
					break;

				case 155:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 170:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 175:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
					break;

				case 180:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 225:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 </tr>";
					break;
				case 240:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 </tr>";
					break;
				case 245:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 </tr>";
					break;
				case 260:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 265:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td> 
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 </tr>";
					break;
				case 270:
					$cRet    .= "<tr>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     	<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     	<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                     	<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 275:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td> 
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 280:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 281:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
					

				case 285:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
					break;

				case 286:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
                                 </tr>";
					break;

				case 290:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;

				case 295:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;


				case 335:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
// Ekuitas
				case 365:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min003$eku1$min004</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min001$eku_lalu1$min002</td>
                                 </tr>";
					break;
				case 400:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
                                 </tr>";
					break;
				default:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
			}
		}


		$cRet .= '</table>';

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$tglttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}



		if ($nip == '00000000 000000 0 000') {
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
		} else if ($nip == '19671126 199503 2 004') {
			$cRet .= '<br><br>
                    <TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
                                            
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Melawi , ' . $tanggal . '</TD>
							</TR>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . strtoupper($jabatan) . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b></TD>
								<TD width="50%" align="center" >selaku <br>PEJABAT PENGELOLA KEUANGAN DAERAH</TD>
							</TR>
							<TR>
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
								<TD align="center" ><b><u>' . $namax . '</b></u></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center">NIP. ' . $nip . '</TD>
							</TR>
							</TABLE><br/>';
		} else {
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
								<TD align="center" ><b>' . $namax . '</b></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $nip . '</TD>
							</TR>
							</TABLE><br/>';
		}

		$data['prev'] = $cRet;
		$data['sikap'] = 'preview';
		$judul = ("NERACA KONSOL $cbulan");
		$this->template->set('title', 'NERACA KONSOL $cbulan');
		switch ($cetak) {
			case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
			case 1;
				echo "<title>NERACA KONSOL $cbulan</title>";
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

	// andika 
	function rpt_neraca_pemda_rinci($cbulan = "", $cetak = 1, $tglttd = "", $ttd = "")
	{
		//$bulan	 = $_REQUEST['tgl1'];
		$kd_skpd   	= $this->session->userdata('kdskpd');
		$thn_ang	= $this->session->userdata('pcThang');
		$tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
		$tglttd = str_replace('n', ' ', $tglttd);
		$thn_ang_1	= $thn_ang - 1;
		$bulan	 = $cbulan;
		$cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;

		$sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd' ";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {

			$nmskpd  = $rowsc->nm_skpd;
		}

		$nm_skpd	= strtoupper($nmskpd);

		$modtahun = $thn_ang % 4;

		if ($modtahun = 0) {
			$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		} else {
			$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		$cRet = '';

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet = "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
					<tr>
						<td rowspan=\"3\" align=\"left\" width=\"2%\">
						<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"60\" height=\"70\" />
                         <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
                    </tr>
					<TR>
						<td align=\"center\"><strong>NERACA</strong></td>
					</TR>
					<TR>
						<td align=\"center\"><strong>PER $arraybulan[$bulan] $thn_ang DAN $thn_ang_1 </strong></td>
					</TR>
					</TABLE><br>";

		$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
							<td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
							<td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>KODE REKENING</b></td>
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
                            <td style=\"border-top: none;\"></td>                                             
                         </tr>
                     </tfoot>
                   
                     <tr>	<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
							<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
							<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"55%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
                           
                        </tr>";


		//level 1

		// Created by Henri_TB

		$trhju = 'trhju_pkd';
		$trdju = 'trdju_pkd';
		$ekuitas = '310101010001';
		$sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7')";
		$querylo10 = $this->db->query($sqllo10);
		$pen8 = $querylo10->row();
		$pen_lalu8 = $pen8->nilai;
		$pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8')";
		$querylo12 = $this->db->query($sqllo12);
		$bel10 = $querylo12->row();
		$bel_lalu10 = $bel10->nilai;
		$bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

		$sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_ll1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_ll2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_ll3  = $row003->thn_m1;
		}


		$query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM $trdju a INNER JOIN $trhju b 
			ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$ekuitas' AND YEAR(b.tgl_voucher)<'$thn_ang'
			and b.tabel=1 and reev=0");
		foreach ($query3->result_array() as $res2) {
			$debet3 = $res2['debet'];
			$kredit3 = $res2['kredit'];
		}

		$real = $kredit3 - $debet3 + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;

		//		created by henri_tb
		// andika khusus LO
		$sqllo9 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7')";
		$querylo9 = $this->db->query($sqllo9);
		$penlo7 = $querylo9->row();
		$pen_lo7 = $penlo7->nilai;
		$pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

		$sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7')";
		$querylo10 = $this->db->query($sqllo10);
		$penlo8 = $querylo10->row();
		$pen_lo_lalu8 = $penlo8->nilai;
		$pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

		$sqllo11 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8')";
		$querylo11 = $this->db->query($sqllo11);
		$bello9 = $querylo11->row();
		$bel_lo9 = $bello9->nilai;
		$bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8')";
		$querylo12 = $this->db->query($sqllo12);
		$bello10 = $querylo12->row();
		$bel_lo_lalu10 = $bello10->nilai;
		$bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

		// test
		// select sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)='2022' and left(kd_rek6,1) in ('7')

		$surplus_lo3 = $pen_lo7 - $bel_lo9;

		$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

		$surplus_lo_lalu3 = number_format($surplus_lo_lalu3, "2", ",", ".");

		$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;

		// sql surplus

		$sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_lalu1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_lalu2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_lalu3  = $row003->thn_m1;
		}

		$sal_awal	= $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;

		$sql = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'4103'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //aba

		$hasil = $this->db->query($sql);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$nilaiDR  = $row001->thn_m1;
		}

		$sqllpe1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'4102'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //Henri_TB

		$hasil = $this->db->query($sqllpe1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$nilailpe1  = $row002->thn_m1;
		}

		$sqllpe2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'4104'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
					inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //Henri_TB

		$hasil = $this->db->query($sqllpe2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$nilailpe2  = $row003->thn_m1;
		}

		$sqleku = "SELECT  7 nor,'EKUITAS' uraian,0 parent,35 seq,'3101'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
		inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where left(kd_rek6,4) ='3101' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan"; //Henri_TB

		$hasil = $this->db->query($sqleku);
		$nawal = 0;
		foreach ($hasil->result() as $row004) {
			$kd_rek   = $row004->nor;
			$parent   = $row004->parent;
			$nama     = $row004->uraian;
			$nilaiEKU  = $row004->thn_m1;
		}

		// $sqlekuandika = "SELECT  258 kode,'' uraian,0 parent,1275 seq,'3102' kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
		// inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where left(kd_rek6,4) ='3102' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan";		

		//$sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2 + $nilaiEKU;

		// ini rumus baru alternatif (ekuitas)
        $sal_akhir = $sal_awal + $surplus_lo3 + $nilaiEKU;

		$sqlutang_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)='2' and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqlutang_lalu);
		foreach ($hasil->result() as $row) {
			$nilaiutang_lalu  = $row->thn_m1;
		}

		$sqlkas_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqlkas_lalu);
		foreach ($hasil->result() as $row) {
			$rk_ppkd_lalu  = $row->thn_m1;
		}

		$sqlskpd_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqlskpd_lalu);
		foreach ($hasil->result() as $row) {
			$rk_skpd_lalu  = $row->thn_m1;
		}

		$sqllcr_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek6,2)='11' and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqllcr_lalu);
		foreach ($hasil->result() as $row) {
			$lcrx_lalu  = $row->thn_m1;
		}

		//kewajiban jangka pendek
		$sqlkpdk_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek6,2)='21' and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqlkpdk_lalu);
		foreach ($hasil->result() as $row) {
			$kpdkx_lalu  = $row->thn_m1;
		}

		//kewajiban jangka panjang
		$sqlkpjg_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek6,2)='22' and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqlkpjg_lalu);
		foreach ($hasil->result() as $row) {
			$kpjgx_lalu  = $row->thn_m1;
		}

		$sqlast_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek6,1)='1' and year(a.tgl_voucher)<=$thn_ang_1"; //Henri_TB

		$hasil = $this->db->query($sqlast_lalu);
		foreach ($hasil->result() as $row) {
			$astx_lalu  = $row->thn_m1;
		}

		$lcr_lalu		= $lcrx_lalu - $rk_skpd_lalu;
		$kpdk_lalu 		= $kpdkx_lalu;
		$kpjg_lalu 		= $kpjgx_lalu;
		$ast_lalu		= $astx_lalu - $rk_skpd_lalu;
		$eku_lalu 		= $sal_awal + $rk_ppkd_lalu - $rk_skpd_lalu;
		//$eku_lalu =   $sal_awal + $surplus_lo_lalu3;
		$eku_tang_lalu 	= $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu - $rk_skpd_lalu;

		$sqlutang = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher
			and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)='2' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqlutang);
		foreach ($hasil->result() as $row) {
			$nilaiutang  = $row->thn_m1;
		}

		$sqlkas = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqlkas);
		foreach ($hasil->result() as $row) {
			$rk_ppkd  = $row->thn_m1;
		}

		$sqlskpd = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqlskpd);
		foreach ($hasil->result() as $row) {
			$rk_skpd  = $row->thn_m1;
		}

		$sqllcr = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek6,2)='11' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqllcr);
		foreach ($hasil->result() as $row) {
			$lcrx = $row->thn_m1;
		}

		//kewajiban jangka pendek 21
		$sqlkpdk = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek6,2)='21' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqlkpdk);
		foreach ($hasil->result() as $row) {
			$kpdkx = $row->thn_m1;
		}

		//kewajiban jangka panjang 22
		$sqlkpjg = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek6,2)='22' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqlkpjg);
		foreach ($hasil->result() as $row) {
			$kpjgx = $row->thn_m1;
		}


		$sqlast = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
			and b.kd_unit=a.kd_skpd where left(kd_rek6,1)='1' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan'"; //Henri_TB

		$hasil = $this->db->query($sqlast);
		foreach ($hasil->result() as $row) {
			$astx  = $row->thn_m1;
		}

		$lcr			= $lcrx - $rk_skpd;
		$kpdk 			= $kpdkx;
		$kpjg			= $kpjgx;
		$ast			= $astx - $rk_skpd;
		$eku 			= $sal_akhir + $rk_ppkd - $rk_skpd;
		$eku_tang 		= $sal_akhir + $nilaiutang + $rk_ppkd - $rk_skpd;

		if ($sal_akhir < 0) {
			$c = "(";
			$sal_akhir = $sal_akhir * -1;
			$d = ")";
		} else {
			$c = "";
			$sal_akhir;
			$d = "";
		}

		$sal_akhir1 = number_format($sal_akhir, "2", ",", ".");

		if ($sal_awal < 0) {
			$c1 = "(";
			$sal_awal = $sal_awal * -1;
			$d1 = ")";
		} else {
			$c1 = "";
			$sal_awal;
			$d1 = "";
		}

		$sal_awal1 = number_format($sal_awal, "2", ",", ".");


		if ($eku_lalu < 0) {
			$min001 = "(";
			$eku_lalu = $eku_lalu * -1;
			$min002 = ")";
		} else {
			$min001 = "";
			$eku_lalu;
			$min002 = "";
		}

		$eku_lalu1 = number_format($eku_lalu, "2", ",", ".");

		if ($eku < 0) {
			$min003 = "(";
			$eku = $eku * -1;
			$min004 = ")";
		} else {
			$min003 = "";
			$eku;
			$min004 = "";
		}

		$eku1 = number_format($eku, "2", ",", ".");

		if ($eku_tang_lalu < 0) {
			$min005 = "(";
			$eku_tang_lalu = $eku_tang_lalu * -1;
			$min006 = ")";
		} else {
			$min005 = "";
			$eku_tang_lalu;
			$min006 = "";
		}

		$eku_tang_lalu1 = number_format($eku_tang_lalu, "2", ",", ".");

		if ($eku_tang < 0) {
			$min007 = "(";
			$eku_tang = $eku_tang * -1;
			$min008 = ")";
		} else {
			$min007 = "";
			$eku_tang;
			$min008 = "";
		}

		$eku_tang1 = number_format($eku_tang, "2", ",", ".");

		if ($rk_ppkd_lalu < 0) {
			$min009 = "(";
			$rk_ppkd_lalu = $rk_ppkd_lalu * -1;
			$min010 = ")";
		} else {
			$min009 = "";
			$rk_ppkd_lalu;
			$min010 = "";
		}

		$rk_ppkd_lalu1 = number_format($rk_ppkd_lalu, "2", ",", ".");

		if ($rk_ppkd < 0) {
			$min013 = "(";
			$rk_ppkd = $rk_ppkd * -1;
			$min014 = ")";
		} else {
			$min013 = "";
			$rk_ppkd;
			$min014 = "";
		}

		$rk_ppkd1 = number_format($rk_ppkd, "2", ",", ".");

		if ($lcr < 0) {
			$min015 = "(";
			$lcr = $lcr * -1;
			$min016 = ")";
		} else {
			$min015 = "";
			$lcr;
			$min016 = "";
		}

		$lcr1 = number_format($lcr, "2", ",", ".");

		if ($kpdk < 0) {
			$min0151 = "(";
			$kpdk = $kpdk * -1;
			$min0161 = ")";
		} else {
			$min0151 = "";
			$kpdk;
			$min0161 = "";
		}
		

		$kpdk1 = number_format($kpdk, "2", ",", ".");

		if ($kpjg < 0) {
			$min0152 = "(";
			$kpjg = $kpjg * -1;
			$min0162 = ")";
		} else {
			$min0152 = "";
			$kpjg;
			$min0162 = "";
		}

		$kpjg1 = number_format($kpjg, "2", ",", ".");

		if ($lcr_lalu < 0) {
			$min017 = "(";
			$lcr_lalu = $lcr_lalu * -1;
			$min018 = ")";
		} else {
			$min017 = "";
			$lcr_lalu;
			$min018 = "";
		}

		$lcr_lalu1 = number_format($lcr_lalu, "2", ",", ".");

		if ($kpdk_lalu < 0) {
			$min0171 = "(";
			$kpdk_lalu = $kpdk_lalu * -1;
			$min0181 = ")";
		} else {
			$min0171 = "";
			$kpdk_lalu;
			$min0181 = "";
		}

		$kpdk_lalu1 = number_format($kpdk_lalu, "2", ",", ".");

		if ($kpjg_lalu < 0) {
			$min0172 = "(";
			$kpjg_lalu = $kpjg_lalu * -1;
			$min0182 = ")";
		} else {
			$min0172 = "";
			$kpjg_lalu;
			$min0182 = "";
		}

		$kpjg_lalu1 = number_format($kpjg_lalu, "2", ",", ".");

		if ($ast < 0) {
			$min019 = "(";
			$ast = $ast * -1;
			$min020 = ")";
		} else {
			$min019 = "";
			$ast;
			$min020 = "";
		}

		$ast1 = number_format($ast, "2", ",", ".");

		if ($ast_lalu < 0) {
			$min021 = "(";
			$ast_lalu = $ast_lalu * -1;
			$min022 = ")";
		} else {
			$min021 = "";
			$ast_lalu;
			$min022 = "";
		}

		$ast_lalu1 = number_format($ast_lalu, "2", ",", ".");

		$queryneraca = "SELECT kode, uraian, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
										isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
										isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
										isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
										FROM map_neraca_permen_77_sinergi_2023 ORDER BY seq ";

		$query10 = $this->db->query($queryneraca);

		$no     = 0;

		// Hitung asset tetap
		$assetTetap = $this->db->query("EXEC assets_tetap ?,? ", [12,2023])->row();

		foreach ($query10->result_array() as $key => $res) {
			$uraian = $res['uraian'];
			$normal = $res['normal'];

			$kode_1 = trim($res['kode_1']);
			$kode_2 = trim($res['kode_2']);
			$kode_3 = trim($res['kode_3']);
			$kode_4 = trim($res['kode_4']);
			$kode_5 = trim($res['kode_5']);
			$kode_6 = trim($res['kode_6']);
			$kode_7 = trim($res['kode_7']);
			$kode_8 = trim($res['kode_8']);
			$kode_9 = trim($res['kode_9']);
			$kode_10 = trim($res['kode_10']);
			$kode_11 = trim($res['kode_11']);
			$kode_12 = trim($res['kode_12']);
			$kode_13 = trim($res['kode_13']);
			$kode_14 = trim($res['kode_14']);
			$kode_15 = trim($res['kode_15']);

			$konversiLra = substr($kode_1,0,4);
			if (($konversiLra >= 1301 && $konversiLra < 1306)) {
				$length = strlen($kode_1);
				$lra =  "52".substr($kode_1,2);
				$query = "SELECT SUM(trd.debet) AS debet, SUM(trd.kredit) AS kredit FROM trhju_pkd AS trh
					INNER JOIN trdju_pkd AS trd ON trd.kd_unit = trh.kd_skpd AND trd.no_voucher = trh.no_voucher
					WHERE LEFT(trd.kd_rek6,$length) = ? AND YEAR(trh.tgl_voucher) = ? AND MONTH(trh.tgl_voucher)  <= ? AND trd.kd_rek6 NOT IN ('520399999999','520288888888','520299999999','520388888888') 
				";
				if(strlen($kode_1) == 6 || !in_array($kode_1, [1304,1305]) ) {
					$query .= "OR (LEFT(trd.kd_rek6, $length) = $kode_1 AND trh.no_voucher LIKE '%-LO-NERACA-Belanja%')";
				}
				if($kode_1 == 1305) {
					$query .= " AND trd.kd_rek6 != '520508010005' ";
				}
				$q = $this->db->query($query, [$lra, $thn_ang, $xbulan]);

			} else {
				$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
									and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and
										(kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
										kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
										kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
										kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
										kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
										kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
										kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
										kd_rek6 like '$kode_15%') ");
			}

			foreach ($q->result_array() as $r) {
				$debet = $r['debet'];
				$kredit = $r['kredit'];
			}

			if ($debet == '') $debet = 0;
			if ($kredit == '') $kredit = 0;

			if ($normal == 1) {
				$nl = $debet - $kredit;
			} else {
				$nl = $kredit - $debet;
			}
			if ($nl == '') $nl = 0;

			// Jurnal Tahun lalu
			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
									and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and
										(kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
										kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
										kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
										kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
										kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
										kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
										kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
										kd_rek6 like '$kode_15%') ");

			foreach ($q->result_array() as $rx) {
				$debet_lalu = $rx['debet'];
				$kredit_lalu = $rx['kredit'];
			}

			if ($debet_lalu == '') $debet_lalu = 0;
			if ($kredit_lalu == '') $kredit_lalu = 0;

			if ($normal == 1) {
				$sblm = $debet_lalu - $kredit_lalu;
			} else {
				$sblm = $kredit_lalu - $debet_lalu;
			}
			if ($sblm == '') $sblm = 0;

			if ($nl < 0) {
				$nl001 = "(";
				$nl = $nl * -1;
				$ln001 = ")";
			} else {
				$nl001 = "";
				$ln001 = "";
			}
			if ($sblm < 0) {
				$sblm001 = "(";
				$sblm = $sblm * -1;
				$mlbs001 = ")";
			} else {
				$sblm001 = "";
				$mlbs001 = "";
			}
			if(($konversiLra >= 1301 && $konversiLra < 1306)) {
				$nl1 = number_format(($sblm+$nl), "2", ",", ".");
			}elseif (substr($kode_1, 0, 4) == 2106)  {
				$nl1 = number_format($sblm, "2", ",", ".");
			}else  {
				$nl1 = number_format($nl, "2", ",", ".");
			}
			$sblm1 = number_format($sblm, "2", ",", ".");
			$no       = $no + 1;
			switch ($res['seq']) {
				case 5:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\"><b>$no</b></td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\"><b>$uraian</b></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$min019$ast1$min020</b></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$min021$ast_lalu1$min022</b></td>
                                 </tr>";
					break;
				case 10:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>                                      
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min015$lcr1$min016</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min017$lcr_lalu1$min018</td>
                                 </tr>";
					break;
				case 15:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 65:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 100:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 185:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 205:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 225:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 340:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 370:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 385:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 410:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 425:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 440:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 460:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>	
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 470:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 </tr>";
					break;
				case 475:
					$cRet    .= "<tr>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                     	<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                     	<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                     	<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 480:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 510:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 525:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
								</tr>";
					break;
				case 530:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
								</tr>";
					break;
				case 535:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">". number_format($assetTetap->nilai,2,',','.') ."</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 540:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 550:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 650:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>	
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 675:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 700:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>	
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 740:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 775:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 800:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
								</tr>";
					break;
				case 805:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 810:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 820:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
								</tr>";
					break;
				case 825:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                    <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
				case 830:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 845:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 855:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 865:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 875:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 885:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 891:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;	
				case 895:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
								</tr>";
					break;
				case 900:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\"><b>$no</b></td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\"><b>$kode_1</b></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\"><b>$uraian</b></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$min019$ast1$min020</b></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$kpdk_lalu1</b></td>
								</tr>";
					break;
					// andika kewajiban
				case 905:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min0151$kpdk1$min0161</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$kpdk_lalu1</td>
								</tr>";
					break;
				case 910:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 955:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 990:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 1015:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 1045:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 1070:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 1150:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 1170:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
								</tr>";
					break;
				case 1175:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min0152$kpjg1$min0162</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min0172$kpjg_lalu1$min0182</td>
								</tr>";
					break;
				case 1180:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 1200:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 1220:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 1240:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				case 1250:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
								</tr>";
					break;
				case 1255:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\"><b>$no</b></td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\"><b>$uraian</b></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$min019$ast1$min020</b></td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"><b>$min021$ast_lalu1$min022</b></td>
								</tr>";
					break;
				case 1260:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min003$eku1$min004</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">2.359.424.911.691,04</td>
								</tr>";
									// varibel awal = $min001$eku_lalu1$min002 diganti menjadi 2.359.424.911.691,04 untuk kolom tahun 2022
					break;
				case 1265:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">2.359.424.911.691,04</td>
								</tr>";
									// varibel sebelumnya = $sblm001$sblm1$mlbs001 diubah menjadi 2.359.424.911.691,04 untuk kolom tahun 2022
					break;

					// ANDIKA SURPLUS DEFISIT LO
					case 1275:
						$cRet    .= "<tr>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">(82.007.625.956,83)</td>
									</tr>";
										// variabel sebelumnya = $surplus_lo_lalu3 diubah menjadi (82.007.625.956,83) untuk kolom tahun 2022
						break;

				case 1280:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">0,00</td>
								</tr>";
									//varibel awal = $sblm001$sblm1$mlbs001 diganti jadi 0,00
					break;

				case 1305:
						$cRet    .= "<tr>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">0,00</td>
									</tr>";
										//varibel awal = $sblm001$sblm1$mlbs001 diganti jadi 0,00
						break;

					case 1310:
							$cRet    .= "<tr>
											<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
											<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
											<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
											<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
											<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">0,00</td>
										</tr>";
											//varibel awal = $sblm001$sblm1$mlbs001 diganti jadi 0,00
							break;

				case 1315:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
								</tr>";
					break;
				default:
					$cRet    .= "<tr>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\">$kode_1</td>                                      
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
									<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                                 </tr>";
					break;
			}
		}


		$cRet .= '</table>';
		
		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$tglttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}

		
		
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
								<TD align="center" ><b>' . $namax . '</b></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >'.$nip.'</TD>
							</TR>
							</TABLE><br/>';

		$data['prev'] = $cRet;
		$data['sikap'] = 'preview';
		$judul = ("NERACA KONSOL $cbulan");
		$this->template->set('title', 'NERACA KONSOL $cbulan');
		switch ($cetak) {
			case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
			case 1;
				echo "<title>NERACA KONSOL $cbulan</title>";
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


	// andika 2
	function rpt_neraca_pemda_unit_obyek_skpd($cbulan = "", $kd_skpd = "", $cetak = 1, $tglttd = "", $ttd = "")
	{
		//$bulan   = $_REQUEST['tgl1'];
		/*$kd_skpd    = $this->session->userdata('kdskpd');*/
		$tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
		$tglttd = str_replace('n', ' ', $tglttd);
		$thn_ang  = $this->session->userdata('pcThang');
		$thn_ang_1  = $thn_ang - 1;
		$bulan   = $cbulan;
		$cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;

		$sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd' ";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {

			$nmskpd  = $rowsc->nm_skpd;
		}

		$nm_skpd  = strtoupper($nmskpd);
		$modtahun = $thn_ang % 4;

		if ($modtahun = 0) {
			$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		} else {
			$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		$cRet = '';

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet = "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
			  <tr>
			  <td rowspan=\"4\" align=\"left\" width=\"2%\">
                    <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"60\" height=\"70\" />
					</td>
							 <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
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

		$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
						 <thead>                       
							<tr>
				  <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>NO</b></td>
				  <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>Kode Rekening</b></td>
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
								<td style=\"border-top: none;\"></td>                                             
							 </tr>
						 </tfoot>
					   
						 <tr> 
						 	<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
						 	<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
				  <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"55%\" align=\"center\">&nbsp;</td>                            
								<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
								<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
							   
							</tr>";


		//level 1

		// Created by Henri_TB
		$trhju = 'trhju_pkd';
		$trdju = 'trdju_pkd';
		$ekuitas = '310101010001';
		$sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
		$querylo10 = $this->db->query($sqllo10);
		$pen8 = $querylo10->row();
		$pen_lalu8 = $pen8->nilai;
		$pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8')and kd_skpd='$kd_skpd'";
		$querylo12 = $this->db->query($sqllo12);
		$bel10 = $querylo12->row();
		$bel_lalu10 = $bel10->nilai;
		$bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

		$sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_ll1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_ll2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_ll3  = $row003->thn_m1;
		}


		$query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM $trdju a INNER JOIN $trhju b 
		  ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$ekuitas' AND YEAR(b.tgl_voucher)<'$thn_ang'
		  and b.tabel=1 and reev=0 and kd_skpd='$kd_skpd'");
		foreach ($query3->result_array() as $res2) {
			$debet3 = $res2['debet'];
			$kredit3 = $res2['kredit'];
		}

		$real = $kredit3 - $debet3 + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;

		//    created by henri_tb
		$sqllo9 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd' ";
		$querylo9 = $this->db->query($sqllo9);
		$penlo7 = $querylo9->row();
		$pen_lo7 = $penlo7->nilai;
		$pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

		$sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
		$querylo10 = $this->db->query($sqllo10);
		$penlo8 = $querylo10->row();
		$pen_lo_lalu8 = $penlo8->nilai;
		$pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

		$sqllo11 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
		$querylo11 = $this->db->query($sqllo11);
		$bello9 = $querylo11->row();
		$bel_lo9 = $bello9->nilai;
		$bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
		$querylo12 = $this->db->query($sqllo12);
		$bello10 = $querylo12->row();
		$bel_lo_lalu10 = $bello10->nilai;
		$bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

		$surplus_lo3 = $pen_lo7 - $bel_lo9;

		$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

		$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;

		$sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_lalu1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_lalu2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_lalu3  = $row003->thn_m1;
		}

		$sal_awal = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;

		$sql = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$nilaiDR  = $row001->thn_m1;
		}

		$sqllpe1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$nilailpe1  = $row002->thn_m1;
		}

		$sqllpe2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$nilailpe2  = $row003->thn_m1;
		}
		$sqleku = "SELECT  7 nor,'EKUITAS' uraian,0 parent,35 seq,'3101'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
		inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where left(kd_rek6,4) ='3101' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan  and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqleku);
		$nawal = 0;
		foreach ($hasil->result() as $row004) {
			$kd_rek   = $row004->nor;
			$parent   = $row004->parent;
			$nama     = $row004->uraian;
			$nilaiEKU  = $row004->thn_m1;
		}

		//$sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2 + $nilaiEKU;

		// ini rumus baru alternatif (ekuitas)
		$sal_akhir = $sal_awal + $surplus_lo3 + $nilaiEKU;

		$sqlutang_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlutang_lalu);
		foreach ($hasil->result() as $row) {
			$nilaiutang_lalu  = $row->thn_m1;
		}

		$sqlkas_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlkas_lalu);
		foreach ($hasil->result() as $row) {
			$rk_ppkd_lalu  = $row->thn_m1;
		}

		$sqlskpd_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlskpd_lalu);
		foreach ($hasil->result() as $row) {
			$rk_skpd_lalu  = $row->thn_m1;
		}

		$sqllcr_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllcr_lalu);
		foreach ($hasil->result() as $row) {
			$lcrx_lalu  = $row->thn_m1;
		}

		$sqlast_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlast_lalu);
		foreach ($hasil->result() as $row) {
			$astx_lalu  = $row->thn_m1;
		}

		$lcr_lalu   = $lcrx_lalu - $rk_skpd_lalu;
		$ast_lalu   = $astx_lalu - $rk_skpd_lalu;
		$eku_lalu     = $sal_awal + $rk_ppkd_lalu - $rk_skpd_lalu;
		$eku_tang_lalu  = $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu - $rk_skpd_lalu;

		$sqlutang = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher
		  and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlutang);
		foreach ($hasil->result() as $row) {
			$nilaiutang  = $row->thn_m1;
		}

		$sqlkas = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlkas);
		foreach ($hasil->result() as $row) {
			$rk_ppkd  = $row->thn_m1;
		}

		$sqlskpd = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlskpd);
		foreach ($hasil->result() as $row) {
			$rk_skpd  = $row->thn_m1;
		}

		$sqllcr = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllcr);
		foreach ($hasil->result() as $row) {
			$lcrx = $row->thn_m1;
		}

		$sqlast = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlast);
		foreach ($hasil->result() as $row) {
			$astx  = $row->thn_m1;
		}

		/*  $lcr      = $lcrx-$rk_skpd;
		  $ast      = $astx-$rk_skpd;
		  $eku      = $sal_akhir - $rk_ppkd + $rk_skpd;         
		  $eku_tang     = $sal_akhir + $nilaiutang - $rk_ppkd +$rk_skpd; */

		$lcr		= $lcrx;
		$ast		= $astx;
		$eku 		= $sal_akhir + $rk_ppkd;
		$eku_tang 	= $sal_akhir + $nilaiutang + $rk_ppkd;

		if ($sal_akhir < 0) {
			$c = "(";
			$sal_akhir = $sal_akhir * -1;
			$d = ")";
		} else {
			$c = "";
			$sal_akhir;
			$d = "";
		}

		$sal_akhir1 = number_format($sal_akhir, "2", ",", ".");

		if ($sal_awal < 0) {
			$c1 = "(";
			$sal_awal = $sal_awal * -1;
			$d1 = ")";
		} else {
			$c1 = "";
			$sal_awal;
			$d1 = "";
		}

		$sal_awal1 = number_format($sal_awal, "2", ",", ".");


		if ($eku_lalu < 0) {
			$min001 = "(";
			$eku_lalu = $eku_lalu * -1;
			$min002 = ")";
		} else {
			$min001 = "";
			$eku_lalu;
			$min002 = "";
		}

		$eku_lalu1 = number_format($eku_lalu, "2", ",", ".");

		if ($eku < 0) {
			$min003 = "(";
			$eku = $eku * -1;
			$min004 = ")";
		} else {
			$min003 = "";
			$eku;
			$min004 = "";
		}

		$eku1 = number_format($eku, "2", ",", ".");

		if ($eku_tang_lalu < 0) {
			$min005 = "(";
			$eku_tang_lalu = $eku_tang_lalu * -1;
			$min006 = ")";
		} else {
			$min005 = "";
			$eku_tang_lalu;
			$min006 = "";
		}

		$eku_tang_lalu1 = number_format($eku_tang_lalu, "2", ",", ".");

		if ($eku_tang < 0) {
			$min007 = "(";
			$eku_tang = $eku_tang * -1;
			$min008 = ")";
		} else {
			$min007 = "";
			$eku_tang;
			$min008 = "";
		}

		$eku_tang1 = number_format($eku_tang, "2", ",", ".");

		if ($rk_ppkd_lalu < 0) {
			$min009 = "(";
			$rk_ppkd_lalu = $rk_ppkd_lalu * -1;
			$min010 = ")";
		} else {
			$min009 = "";
			$rk_ppkd_lalu;
			$min010 = "";
		}

		$rk_ppkd_lalu1 = number_format($rk_ppkd_lalu, "2", ",", ".");

		if ($rk_ppkd < 0) {
			$min013 = "(";
			$rk_ppkd = $rk_ppkd * -1;
			$min014 = ")";
		} else {
			$min013 = "";
			$rk_ppkd;
			$min014 = "";
		}

		$rk_ppkd1 = number_format($rk_ppkd, "2", ",", ".");

		if ($lcr < 0) {
			$min015 = "(";
			$lcr = $lcr * -1;
			$min016 = ")";
		} else {
			$min015 = "";
			$lcr;
			$min016 = "";
		}

		$lcr1 = number_format($lcr, "2", ",", ".");

		if ($lcr_lalu < 0) {
			$min017 = "(";
			$lcr_lalu = $lcr_lalu * -1;
			$min018 = ")";
		} else {
			$min017 = "";
			$lcr_lalu;
			$min018 = "";
		}

		$lcr_lalu1 = number_format($lcr_lalu, "2", ",", ".");

		if ($ast < 0) {
			$min019 = "(";
			$ast = $ast * -1;
			$min020 = ")";
		} else {
			$min019 = "";
			$ast;
			$min020 = "";
		}

		$ast1 = number_format($ast, "2", ",", ".");

		if ($ast_lalu < 0) {
			$min021 = "(";
			$ast_lalu = $ast_lalu * -1;
			$min022 = ")";
		} else {
			$min021 = "";
			$ast_lalu;
			$min022 = "";
		}

		$ast_lalu1 = number_format($ast_lalu, "2", ",", ".");

		$queryneraca = " SELECT kode, uraian, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
						isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
						isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
						isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
						FROM map_neraca_permen_77_skpd ORDER BY seq ";

		$query10 = $this->db->query($queryneraca);

		$no     = 0;

		foreach ($query10->result_array() as $res) {
			$uraian = $res['uraian'];
			$normal = $res['normal'];

			$kode_1 = trim($res['kode_1']);
			$kode_2 = trim($res['kode_2']);
			$kode_3 = trim($res['kode_3']);
			$kode_4 = trim($res['kode_4']);
			$kode_5 = trim($res['kode_5']);
			$kode_6 = trim($res['kode_6']);
			$kode_7 = trim($res['kode_7']);
			$kode_8 = trim($res['kode_8']);
			$kode_9 = trim($res['kode_9']);
			$kode_10 = trim($res['kode_10']);
			$kode_11 = trim($res['kode_11']);
			$kode_12 = trim($res['kode_12']);
			$kode_13 = trim($res['kode_13']);
			$kode_14 = trim($res['kode_14']);
			$kode_15 = trim($res['kode_15']);

			$konversiLra = substr($kode_1,0,4);
			if (($konversiLra >= 1301 && $konversiLra < 1306)) {
				$length = strlen($kode_1);
				$lra =  "52".substr($kode_1,2);
				$query = "SELECT SUM(trd.debet) AS debet, SUM(trd.kredit) AS kredit FROM trhju_pkd AS trh
					INNER JOIN trdju_pkd AS trd ON trd.kd_unit = trh.kd_skpd AND trd.no_voucher = trh.no_voucher
					WHERE LEFT(trd.kd_rek6,$length) = ? AND YEAR(trh.tgl_voucher) = ? AND MONTH(trh.tgl_voucher)  <= ? AND trd.kd_rek6 NOT IN ('520399999999','520288888888','520299999999','520388888888') AND trh.kd_skpd = ? 
					OR ( LEFT(trd.kd_rek6,$length) = ? AND trh.no_voucher LIKE '%-LO-NERACA-Belanja%' AND trh.kd_skpd  = ? )
				";
				$q = $this->db->query($query, [$lra, $thn_ang, $xbulan, $kd_skpd, $kode_1, $kd_skpd]);

			} else {
				$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
					  and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd' and
						(kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
						kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
						kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
						kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
						kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
						kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
						kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
						kd_rek6 like '$kode_15%') ");
			}

			foreach ($q->result_array() as $r) {
				$debet = $r['debet'];
				$kredit = $r['kredit'];
			}

			if ($debet == '') $debet = 0;
			if ($kredit == '') $kredit = 0;

			if ($normal == 1) {
				$nl = $debet - $kredit;
			} else {
				$nl = $kredit - $debet;
			}
			if ($nl == '') $nl = 0;

			// Jurnal Tahun lalu
			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
					  and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd' and
						(kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
						kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
						kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
						kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
						kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
						kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
						kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
						kd_rek6 like '$kode_15%') ");

			foreach ($q->result_array() as $rx) {
				$debet_lalu = $rx['debet'];
				$kredit_lalu = $rx['kredit'];
			}

			if ($debet_lalu == '') $debet_lalu = 0;
			if ($kredit_lalu == '') $kredit_lalu = 0;

			if ($normal == 1) {
				$sblm = $debet_lalu - $kredit_lalu;
			} else {
				$sblm = $kredit_lalu - $debet_lalu;
			}
			if ($sblm == '') $sblm = 0;

			if ($nl < 0) {
				$nl001 = "(";
				$nl = $nl * -1;
				$ln001 = ")";
			} else {
				$nl001 = "";
				$ln001 = "";
			}
			if ($sblm < 0) {
				$sblm001 = "(";
				$sblm = $sblm * -1;
				$mlbs001 = ")";
			} else {
				$sblm001 = "";
				$mlbs001 = "";
			}
			if(($konversiLra >= 1301 && $konversiLra < 1306)) {
				$nl1 = number_format(($nl+$sblm), "2", ",", ".");
			}elseif (substr($kode_1, 0, 4) == 2106)  {
				$nl1 = number_format($sblm, "2", ",", ".");
			}else  {
				$nl1 = number_format($nl, "2", ",", ".");
			}
			$sblm1 = number_format($sblm, "2", ",", ".");

			$no       = $no + 1;

			switch ($res['seq']) {
				case 5:
					$cRet    .= "<tr>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min019$ast1$min020</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min021$ast_lalu1$min022</td>
									 </tr>";
					break;
				case 10:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min015$lcr1$min016</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min017$lcr_lalu1$min018</td>
									 </tr>";
					break;
				case 15:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 60:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 65:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 100:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 105:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;

				case 110:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 115:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 120:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 125:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                             </tr>";
					break;
				case 130:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
                                 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
                             </tr>";
					break;
				case 135:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;

				case 165:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 180:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									 </tr>";
					break;
				case 185:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									 </tr>";
					break;

				case 190:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 235:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 250:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 255:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;

				case 270:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 275:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 280:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 285:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 290:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;

				case 295:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									 </tr>";
					break;

				case 296:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										  <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
									 </tr>";
					break;

				case 300:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;



				case 305:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;


				case 345:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;

				case 375:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min003$eku1$min004</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min001$eku_lalu1$min002</td>
									 </tr>";
					break;
				case 410:
					$cRet    .= "<tr><td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
										 <td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
									 </tr>";
					break;
				default:
					$cRet    .= "<tr>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\">$kode_1</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
			}
		}


		$cRet .= '</table>';

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$tglttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}



		if ($nip == '00000000 000000 0 000') {
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
		} else if ($nip == '19671126 199503 2 004') {
			$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
											
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Melawi , ' . $tanggal . '</TD>
							</TR>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . strtoupper($jabatan) . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b></TD>
								<TD width="50%" align="center" >selaku <br>PEJABAT PENGELOLA KEUANGAN DAERAH</TD>
							</TR>
							<TR>
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
								<TD align="center" ><b><u>' . $namax . '</b></u></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center">NIP. ' . $nip . '</TD>
							</TR>
							</TABLE><br/>';
		} else {
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
								<TD align="center" ><b>' . $namax . '</b></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $nip . '</TD>
							</TR>
							</TABLE><br/>';
		}

		$data['prev'] = $cRet;
		$data['sikap'] = 'preview';
		$judul = ("NERACA KONSOL $cbulan");
		$this->template->set('title', 'NERACA KONSOL $cbulan');
		switch ($cetak) {
			case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
			case 1;
				echo "<title>NERACA KONSOL $cbulan</title>";
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

	// andika 3
	function rpt_neraca_pemda_unit_obyek_skpd_rinci($cbulan = "", $kd_skpd = "", $cetak = 1, $tglttd = "", $ttd = "")
	{
		//$bulan   = $_REQUEST['tgl1'];
		/*$kd_skpd    = $this->session->userdata('kdskpd');*/
		$tanggal = $this->tukd_model->tanggal_format_indonesia($ttd);
		$tglttd = str_replace('n', ' ', $tglttd);
		$thn_ang  = $this->session->userdata('pcThang');
		$thn_ang_1  = $thn_ang - 1;
		$bulan   = $cbulan;
		$cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;

		$sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd' ";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {

			$nmskpd  = $rowsc->nm_skpd;
		}

		$nm_skpd  = strtoupper($nmskpd);
		$modtahun = $thn_ang % 4;

		if ($modtahun = 0) {
			$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		} else {
			$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		$cRet = '';

		$sclient = $this->akuntansi_model->get_sclient();
		$cRet = "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
			  <tr>
			  <td rowspan=\"4\" align=\"left\" width=\"2%\">
                    <img src=\"" . base_url() . "/image/logoHP.png\"  width=\"60\" height=\"70\" />
					</td>
							 <td align=\"center\"><strong>" . $sclient->kab_kota . "</strong></td>                         
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

		$cRet .= "<table style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
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
					   
						 <tr> <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
				  <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"55%\" align=\"center\">&nbsp;</td>                            
								<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
								<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
							   
							</tr>";


		//level 1

		// Created by Henri_TB
		$trhju = 'trhju_pkd';
		$trdju = 'trdju_pkd';
		$ekuitas = '310101010001';
		$sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
		$querylo10 = $this->db->query($sqllo10);
		$pen8 = $querylo10->row();
		$pen_lalu8 = $pen8->nilai;
		$pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8')and kd_skpd='$kd_skpd'";
		$querylo12 = $this->db->query($sqllo12);
		$bel10 = $querylo12->row();
		$bel_lalu10 = $bel10->nilai;
		$bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

		$sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_ll1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_ll2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_ll3  = $row003->thn_m1;
		}


		$query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM $trdju a INNER JOIN $trhju b 
		  ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='$ekuitas' AND YEAR(b.tgl_voucher)<'$thn_ang'
		  and b.tabel=1 and reev=0 and kd_skpd='$kd_skpd'");
		foreach ($query3->result_array() as $res2) {
			$debet3 = $res2['debet'];
			$kredit3 = $res2['kredit'];
		}

		$real = $kredit3 - $debet3 + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;

		//    created by henri_tb
		$sqllo9 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd' ";
		$querylo9 = $this->db->query($sqllo9);
		$penlo7 = $querylo9->row();
		$pen_lo7 = $penlo7->nilai;
		$pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

		$sqllo10 = "SELECT sum(kredit-debet) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
		$querylo10 = $this->db->query($sqllo10);
		$penlo8 = $querylo10->row();
		$pen_lo_lalu8 = $penlo8->nilai;
		$pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

		$sqllo11 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
		$querylo11 = $this->db->query($sqllo11);
		$bello9 = $querylo11->row();
		$bel_lo9 = $bello9->nilai;
		$bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from $trdju a inner join $trhju b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
		$querylo12 = $this->db->query($sqllo12);
		$bello10 = $querylo12->row();
		$bel_lo_lalu10 = $bello10->nilai;
		$bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

		$surplus_lo3 = $pen_lo7 - $bel_lo9;

		$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

		$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;

		$sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_lalu1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_lalu2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_lalu3  = $row003->thn_m1;
		}

		$sal_awal = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;

		$sql = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$nilaiDR  = $row001->thn_m1;
		}

		$sqllpe1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$nilailpe1  = $row002->thn_m1;
		}

		$sqllpe2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
			  inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='$ekuitas' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$nilailpe2  = $row003->thn_m1;
		}
		$sqleku = "SELECT  7 nor,'EKUITAS' uraian,0 parent,35 seq,'3101'kode_1,isnull(sum(kredit-debet),0) thn_m1 from $trhju a
		inner join $trdju b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where left(kd_rek6,4) ='3101' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan  and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqleku);
		$nawal = 0;
		foreach ($hasil->result() as $row004) {
			$kd_rek   = $row004->nor;
			$parent   = $row004->parent;
			$nama     = $row004->uraian;
			$nilaiEKU  = $row004->thn_m1;
		}

		//$sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2 + $nilaiEKU;

		// ini rumus baru alternatif (ekuitas)
		$sal_akhir = $sal_awal + $surplus_lo3 + $nilaiEKU;

		$sqlutang_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlutang_lalu);
		foreach ($hasil->result() as $row) {
			$nilaiutang_lalu  = $row->thn_m1;
		}

		$sqlkas_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlkas_lalu);
		foreach ($hasil->result() as $row) {
			$rk_ppkd_lalu  = $row->thn_m1;
		}

		$sqlskpd_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlskpd_lalu);
		foreach ($hasil->result() as $row) {
			$rk_skpd_lalu  = $row->thn_m1;
		}

		$sqllcr_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllcr_lalu);
		foreach ($hasil->result() as $row) {
			$lcrx_lalu  = $row->thn_m1;
		}

		$sqlast_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlast_lalu);
		foreach ($hasil->result() as $row) {
			$astx_lalu  = $row->thn_m1;
		}

		$lcr_lalu   = $lcrx_lalu - $rk_skpd_lalu;
		$ast_lalu   = $astx_lalu - $rk_skpd_lalu;
		$eku_lalu     = $sal_awal + $rk_ppkd_lalu - $rk_skpd_lalu;
		$eku_tang_lalu  = $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu - $rk_skpd_lalu;

		$sqlutang = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher
		  and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlutang);
		foreach ($hasil->result() as $row) {
			$nilaiutang  = $row->thn_m1;
		}

		$sqlkas = "SELECT isnull(sum(kredit-debet),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlkas);
		foreach ($hasil->result() as $row) {
			$rk_ppkd  = $row->thn_m1;
		}

		$sqlskpd = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlskpd);
		foreach ($hasil->result() as $row) {
			$rk_skpd  = $row->thn_m1;
		}

		$sqllcr = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllcr);
		foreach ($hasil->result() as $row) {
			$lcrx = $row->thn_m1;
		}

		$sqlast = "SELECT isnull(sum(debet-kredit),0) thn_m1 from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlast);
		foreach ($hasil->result() as $row) {
			$astx  = $row->thn_m1;
		}

		/*  $lcr      = $lcrx-$rk_skpd;
		  $ast      = $astx-$rk_skpd;
		  $eku      = $sal_akhir - $rk_ppkd + $rk_skpd;         
		  $eku_tang     = $sal_akhir + $nilaiutang - $rk_ppkd +$rk_skpd; */

		$lcr		= $lcrx;
		$ast		= $astx;
		$eku 		= $sal_akhir + $rk_ppkd;
		$eku_tang 	= $sal_akhir + $nilaiutang + $rk_ppkd;

		if ($sal_akhir < 0) {
			$c = "(";
			$sal_akhir = $sal_akhir * -1;
			$d = ")";
		} else {
			$c = "";
			$sal_akhir;
			$d = "";
		}

		$sal_akhir1 = number_format($sal_akhir, "2", ",", ".");

		if ($sal_awal < 0) {
			$c1 = "(";
			$sal_awal = $sal_awal * -1;
			$d1 = ")";
		} else {
			$c1 = "";
			$sal_awal;
			$d1 = "";
		}

		$sal_awal1 = number_format($sal_awal, "2", ",", ".");


		if ($eku_lalu < 0) {
			$min001 = "(";
			$eku_lalu = $eku_lalu * -1;
			$min002 = ")";
		} else {
			$min001 = "";
			$eku_lalu;
			$min002 = "";
		}

		$eku_lalu1 = number_format($eku_lalu, "2", ",", ".");

		if ($eku < 0) {
			$min003 = "(";
			$eku = $eku * -1;
			$min004 = ")";
		} else {
			$min003 = "";
			$eku;
			$min004 = "";
		}

		$eku1 = number_format($eku, "2", ",", ".");

		if ($eku_tang_lalu < 0) {
			$min005 = "(";
			$eku_tang_lalu = $eku_tang_lalu * -1;
			$min006 = ")";
		} else {
			$min005 = "";
			$eku_tang_lalu;
			$min006 = "";
		}

		$eku_tang_lalu1 = number_format($eku_tang_lalu, "2", ",", ".");

		if ($eku_tang < 0) {
			$min007 = "(";
			$eku_tang = $eku_tang * -1;
			$min008 = ")";
		} else {
			$min007 = "";
			$eku_tang;
			$min008 = "";
		}

		$eku_tang1 = number_format($eku_tang, "2", ",", ".");

		if ($rk_ppkd_lalu < 0) {
			$min009 = "(";
			$rk_ppkd_lalu = $rk_ppkd_lalu * -1;
			$min010 = ")";
		} else {
			$min009 = "";
			$rk_ppkd_lalu;
			$min010 = "";
		}

		$rk_ppkd_lalu1 = number_format($rk_ppkd_lalu, "2", ",", ".");

		if ($rk_ppkd < 0) {
			$min013 = "(";
			$rk_ppkd = $rk_ppkd * -1;
			$min014 = ")";
		} else {
			$min013 = "";
			$rk_ppkd;
			$min014 = "";
		}

		$rk_ppkd1 = number_format($rk_ppkd, "2", ",", ".");

		if ($lcr < 0) {
			$min015 = "(";
			$lcr = $lcr * -1;
			$min016 = ")";
		} else {
			$min015 = "";
			$lcr;
			$min016 = "";
		}

		$lcr1 = number_format($lcr, "2", ",", ".");

		if ($lcr_lalu < 0) {
			$min017 = "(";
			$lcr_lalu = $lcr_lalu * -1;
			$min018 = ")";
		} else {
			$min017 = "";
			$lcr_lalu;
			$min018 = "";
		}

		$lcr_lalu1 = number_format($lcr_lalu, "2", ",", ".");

		if ($ast < 0) {
			$min019 = "(";
			$ast = $ast * -1;
			$min020 = ")";
		} else {
			$min019 = "";
			$ast;
			$min020 = "";
		}

		$ast1 = number_format($ast, "2", ",", ".");

		if ($ast_lalu < 0) {
			$min021 = "(";
			$ast_lalu = $ast_lalu * -1;
			$min022 = ")";
		} else {
			$min021 = "";
			$ast_lalu;
			$min022 = "";
		}

		$ast_lalu1 = number_format($ast_lalu, "2", ",", ".");

		$queryneraca = " SELECT kode, uraian, seq, parent, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
						isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
						isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
						isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
						FROM map_neraca_permen_77_sinergi_2023 ORDER BY seq ";

		$query10 = $this->db->query($queryneraca);
		$no     = 0;

		foreach ($query10->result_array() as $res) {
			$uraian = $res['uraian'];
			$normal = $res['normal'];
			$parent = $res['parent'];

			$kode_1 = trim($res['kode_1']);
			$kode_2 = trim($res['kode_2']);
			$kode_3 = trim($res['kode_3']);
			$kode_4 = trim($res['kode_4']);
			$kode_5 = trim($res['kode_5']);
			$kode_6 = trim($res['kode_6']);
			$kode_7 = trim($res['kode_7']);
			$kode_8 = trim($res['kode_8']);
			$kode_9 = trim($res['kode_9']);
			$kode_10 = trim($res['kode_10']);
			$kode_11 = trim($res['kode_11']);
			$kode_12 = trim($res['kode_12']);
			$kode_13 = trim($res['kode_13']);
			$kode_14 = trim($res['kode_14']);
			$kode_15 = trim($res['kode_15']);


			$konversiLra = substr($kode_1,0,4);
			if (($konversiLra >= 1301 && $konversiLra < 1306)) {
				$length = strlen($kode_1);
				$lra =  "52".substr($kode_1,2);
				$query = "SELECT SUM(trd.debet) AS debet, SUM(trd.kredit) AS kredit FROM trhju_pkd AS trh
					INNER JOIN trdju_pkd AS trd ON trd.kd_unit = trh.kd_skpd AND trd.no_voucher = trh.no_voucher
					WHERE LEFT(trd.kd_rek6,$length) = ? AND YEAR(trh.tgl_voucher) = ? AND MONTH(trh.tgl_voucher)  <= ? AND trd.kd_rek6 NOT IN ('520399999999','520288888888','520299999999','520388888888') AND trh.kd_skpd = ?
				";
				$q = $this->db->query($query, [$lra, $thn_ang, $xbulan, $kd_skpd]);

			} else {
				$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
					  and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd' and
						(kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
						kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
						kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
						kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
						kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
						kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
						kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
						kd_rek6 like '$kode_15%') ");
			}


			foreach ($q->result_array() as $r) {
				$debet = $r['debet'];
				$kredit = $r['kredit'];
			}

			if ($debet == '') $debet = 0;
			if ($kredit == '') $kredit = 0;

			if ($normal == 1) {
				$nl = $debet - $kredit;
			} else {
				$nl = $kredit - $debet;
			}
			if ($nl == '') $nl = 0;

			// Jurnal Tahun lalu
			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from $trhju a inner join $trdju b on a.no_voucher=b.no_voucher 
					  and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd' and
						(kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
						kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
						kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
						kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
						kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
						kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
						kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
						kd_rek6 like '$kode_15%') ");

			foreach ($q->result_array() as $rx) {
				$debet_lalu = $rx['debet'];
				$kredit_lalu = $rx['kredit'];
			}

			if ($debet_lalu == '') $debet_lalu = 0;
			if ($kredit_lalu == '') $kredit_lalu = 0;

			if ($normal == 1) {
				$sblm = $debet_lalu - $kredit_lalu;
			} else {
				$sblm = $kredit_lalu - $debet_lalu;
			}
			if ($sblm == '') $sblm = 0;

			if ($nl < 0) {
				$nl001 = "(";
				$nl = $nl * -1;
				$ln001 = ")";
			} else {
				$nl001 = "";
				$ln001 = "";
			}
			if ($sblm < 0) {
				$sblm001 = "(";
				$sblm = $sblm * -1;
				$mlbs001 = ")";
			} else {
				$sblm001 = "";
				$mlbs001 = "";
			}
			if(($konversiLra >= 1301 && $konversiLra < 1306)) {
				$nl1 = number_format(($nl+$sblm), "2", ",", ".");
			}elseif (substr($kode_1, 0, 4) == 2106)  {
				$nl1 = number_format($sblm, "2", ",", ".");
			}else  {
				$nl1 = number_format($nl, "2", ",", ".");
			}
			$sblm1 = number_format($sblm, "2", ",", ".");

			$no       = $no + 1;

			$styles = [
				1 => 'padding-left: 25px;',
				2 => 'padding-left: 50px;',
				3 => 'padding-left: 75px;',
				4 => 'padding-left: 100px;',
				0 => ''
			];

			$isBold = $parent == 1 ? 'style="font-weight: bold;"': '';
			if ($parent == 0) {
				$nl1 = '';
				$sblm1 = '';
			}

			$cRet .= "<tr $isBold>
						<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
						<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none; ".$styles[$parent]."\" width=\"60%\">$uraian</td>
						<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl1</td>
						<td style=\"font-size:12px;font-family:Arial;vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm1</td>
					</tr>
			";
		}


		$cRet .= '</table>';

		$sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$tglttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
		$sqlttd = $this->db->query($sqlttd1);
		foreach ($sqlttd->result() as $rowttd) {
			$nip = $rowttd->nip;
			$namax = $rowttd->nm;
			$jabatan  = $rowttd->jab;
			$pangkat  = $rowttd->pangkat;
		}



		if ($nip == '00000000 000000 0 000') {
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
		} else if ($nip == '19671126 199503 2 004') {
			$cRet .= '<br><br>
					<TABLE style="border-collapse:collapse; font-size:13px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
											
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" > Melawi , ' . $tanggal . '</TD>
							</TR>
							
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . strtoupper($jabatan) . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b></TD>
								<TD width="50%" align="center" >selaku <br>PEJABAT PENGELOLA KEUANGAN DAERAH</TD>
							</TR>
							<TR>
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
								<TD align="center" ><b><u>' . $namax . '</b></u></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center">NIP. ' . $nip . '</TD>
							</TR>
							</TABLE><br/>';
		} else {
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
								<TD align="center" ><b>' . $namax . '</b></TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $pangkat . '</TD>
							</TR>
							<TR>
								<TD width="50%" align="center" ><b>&nbsp;</TD>
								<TD align="center" >' . $nip . '</TD>
							</TR>
							</TABLE><br/>';
		}

		$data['prev'] = $cRet;
		$data['sikap'] = 'preview';
		$judul = ("NERACA KONSOL $cbulan");
		$this->template->set('title', 'NERACA KONSOL $cbulan');
		switch ($cetak) {
			case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
			case 1;
				echo "<title>NERACA KONSOL $cbulan</title>";
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



	// NERACA SKPD TEST
	function rpt_neraca_pemda_unit_obyek_test($cbulan = "", $kd_skpd = "", $cetak = 1)
	{
		//$bulan   = $_REQUEST['tgl1'];
		/*$kd_skpd    = $this->session->userdata('kdskpd');*/
		$thn_ang  = $this->session->userdata('pcThang');
		$thn_ang_1  = $thn_ang - 1;
		$bulan   = $cbulan;
		$cbulan < 10 ? $xbulan = "0$cbulan" : $xbulan = $cbulan;

		$sqlsc = "SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd' ";
		$sqlsclient = $this->db->query($sqlsc);
		foreach ($sqlsclient->result() as $rowsc) {

			$nmskpd  = $rowsc->nm_skpd;
		}

		$nm_skpd  = strtoupper($nmskpd);

		/*           $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
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
		$modtahun = $thn_ang % 4;

		if ($modtahun = 0) {
			$nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		} else {
			$nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
		}

		$arraybulan = explode(".", $nilaibulan);

		$cRet = '';

		$cRet = "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
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
					   
						 <tr> <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" align=\"center\">&nbsp;</td>
				  <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"55%\" align=\"center\">&nbsp;</td>                            
								<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
								<td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\" align=\"center\">&nbsp;</td>
							   
							</tr>";


		//level 1

		// Created by Henri_TB

		$sqllo10 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
		$querylo10 = $this->db->query($sqllo10);
		$pen8 = $querylo10->row();
		$pen_lalu8 = $pen8->nilai;
		$pen_lalu81 = number_format($pen8->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)<$thn_ang_1 and left(kd_rek6,1) in ('8')and kd_skpd='$kd_skpd'";
		$querylo12 = $this->db->query($sqllo12);
		$bel10 = $querylo12->row();
		$bel_lalu10 = $bel10->nilai;
		$bel_lalu101 = number_format($bel10->nilai, "2", ",", ".");

		$sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
			  inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_ll1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
			  inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_ll2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
			  inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)<$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_ll3  = $row003->thn_m1;
		}


		$query3 = $this->db->query(" SELECT SUM(a.debet) AS debet, SUM(a.kredit) AS kredit FROM trdju_pkd a INNER JOIN trhju_pkd b 
		  ON a.no_voucher = b.no_voucher and a.kd_unit=b.kd_skpd WHERE a.kd_rek6='310101010001' AND YEAR(b.tgl_voucher)<'$thn_ang'
		  and b.tabel=1 and reev=0 and kd_skpd='$kd_skpd'");
		foreach ($query3->result_array() as $res2) {
			$debet3 = $res2['debet'];
			$kredit3 = $res2['kredit'];
		}

		$real = $kredit3 - $debet3 + $pen_lalu8 - $bel_lalu10 + $lpe_ll1 + $lpe_ll2 + $lpe_ll3;

		//    created by henri_tb
		$sqllo9 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd' ";
		$querylo9 = $this->db->query($sqllo9);
		$penlo7 = $querylo9->row();
		$pen_lo7 = $penlo7->nilai;
		$pen_lo71 = number_format($penlo7->nilai, "2", ",", ".");

		$sqllo10 = "SELECT sum(kredit-debet) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('7') and kd_skpd='$kd_skpd'";
		$querylo10 = $this->db->query($sqllo10);
		$penlo8 = $querylo10->row();
		$pen_lo_lalu8 = $penlo8->nilai;
		$pen_lo_lalu81 = number_format($penlo8->nilai, "2", ",", ".");

		$sqllo11 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
		$querylo11 = $this->db->query($sqllo11);
		$bello9 = $querylo11->row();
		$bel_lo9 = $bello9->nilai;
		$bel_lo91 = number_format($bello9->nilai, "2", ",", ".");

		$sqllo12 = "SELECT sum(debet-kredit) as nilai from trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher and a.kd_unit=b.kd_skpd where year(tgl_voucher)=$thn_ang_1 and left(kd_rek6,1) in ('8') and kd_skpd='$kd_skpd'";
		$querylo12 = $this->db->query($sqllo12);
		$bello10 = $querylo12->row();
		$bel_lo_lalu10 = $bello10->nilai;
		$bel_lo_lalu101 = number_format($bello10->nilai, "2", ",", ".");

		$surplus_lo3 = $pen_lo7 - $bel_lo9;

		$surplus_lo_lalu3 = $pen_lo_lalu8 - $bel_lo_lalu10;

		$selisih_surplus_lo3 = $surplus_lo3 - $surplus_lo_lalu3;

		$sql_lalu = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
			  inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql_lalu);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$lpe_lalu1  = $row001->thn_m1;
		}

		$sqllpe_lalu1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
			  inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$lpe_lalu2  = $row002->thn_m1;
		}

		$sqllpe_lalu2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
			  inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe_lalu2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$lpe_lalu3  = $row003->thn_m1;
		}

		$sal_awal = $real + $surplus_lo_lalu3 + $lpe_lalu1 + $lpe_lalu2 + $lpe_lalu3;

		$sql = "SELECT 5 nor,'SELISIH REVALUASI ASET TETAP' uraian,3 parent,25 seq,'413'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
			  inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='1' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //aba

		$hasil = $this->db->query($sql);
		$nawal = 0;
		foreach ($hasil->result() as $row001) {
			$kd_rek   = $row001->nor;
			$parent   = $row001->parent;
			$nama     = $row001->uraian;
			$nilaiDR  = $row001->thn_m1;
		}

		$sqllpe1 = "SELECT 4 nor,'KOREKSI NILAI PERSEDIAAN' uraian,3 parent,20 seq,'412'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
			  inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='2' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe1);
		$nawal = 0;
		foreach ($hasil->result() as $row002) {
			$kd_rek   = $row002->nor;
			$parent   = $row002->parent;
			$nama     = $row002->uraian;
			$nilailpe1  = $row002->thn_m1;
		}

		$sqllpe2 = "SELECT 6 nor,'LAIN LAIN' uraian,3 parent,30 seq,'414'kode_1,isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a
			  inner join trdju_pkd b on a.no_voucher=b.no_voucher and a.kd_skpd=b.kd_unit where  reev='3' and kd_rek6='310101010001' and year(a.tgl_voucher)=$thn_ang and month(tgl_voucher)<=$bulan and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllpe2);
		$nawal = 0;
		foreach ($hasil->result() as $row003) {
			$kd_rek   = $row003->nor;
			$parent   = $row003->parent;
			$nama     = $row003->uraian;
			$nilailpe2  = $row003->thn_m1;
		}

		$sal_akhir = $sal_awal + $surplus_lo3 + $nilaiDR + $nilailpe1 + $nilailpe2;


		$sqlutang_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlutang_lalu);
		foreach ($hasil->result() as $row) {
			$nilaiutang_lalu  = $row->thn_m1;
		}

		$sqlkas_lalu = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlkas_lalu);
		foreach ($hasil->result() as $row) {
			$rk_ppkd_lalu  = $row->thn_m1;
		}

		$sqlskpd_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlskpd_lalu);
		foreach ($hasil->result() as $row) {
			$rk_skpd_lalu  = $row->thn_m1;
		}

		$sqllcr_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllcr_lalu);
		foreach ($hasil->result() as $row) {
			$lcrx_lalu  = $row->thn_m1;
		}

		$sqlast_lalu = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and year(a.tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlast_lalu);
		foreach ($hasil->result() as $row) {
			$astx_lalu  = $row->thn_m1;
		}

		$lcr_lalu   = $lcrx_lalu - $rk_skpd_lalu;
		$ast_lalu   = $astx_lalu - $rk_skpd_lalu;
		$eku_lalu     = $sal_awal + $rk_ppkd_lalu - $rk_skpd_lalu;
		$eku_tang_lalu  = $sal_awal + $nilaiutang_lalu + $rk_ppkd_lalu - $rk_skpd_lalu;

		$sqlutang = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher
		  and b.kd_unit=a.kd_skpd where left(b.kd_rek6,1)=2 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlutang);
		foreach ($hasil->result() as $row) {
			$nilaiutang  = $row->thn_m1;
		}

		$sqlkas = "SELECT isnull(sum(kredit-debet),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where kd_rek6='310301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlkas);
		foreach ($hasil->result() as $row) {
			$rk_ppkd  = $row->thn_m1;
		}

		$sqlskpd = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where kd_rek6='111301010001' and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlskpd);
		foreach ($hasil->result() as $row) {
			$rk_skpd  = $row->thn_m1;
		}

		$sqllcr = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(kd_rek6,2)=11 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqllcr);
		foreach ($hasil->result() as $row) {
			$lcrx = $row->thn_m1;
		}

		$sqlast = "SELECT isnull(sum(debet-kredit),0) thn_m1 from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
		  and b.kd_unit=a.kd_skpd where left(kd_rek6,1)=1 and left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd'"; //Henri_TB

		$hasil = $this->db->query($sqlast);
		foreach ($hasil->result() as $row) {
			$astx  = $row->thn_m1;
		}

		/*  $lcr      = $lcrx-$rk_skpd;
		  $ast      = $astx-$rk_skpd;
		  $eku      = $sal_akhir - $rk_ppkd + $rk_skpd;         
		  $eku_tang     = $sal_akhir + $nilaiutang - $rk_ppkd +$rk_skpd; */

		$lcr		= $lcrx - $rk_skpd;
		$ast		= $astx - $rk_skpd;
		$eku 		= $sal_akhir + $rk_ppkd;
		$eku_tang 	= $sal_akhir + $nilaiutang + $rk_ppkd;

		if ($sal_akhir < 0) {
			$c = "(";
			$sal_akhir = $sal_akhir * -1;
			$d = ")";
		} else {
			$c = "";
			$sal_akhir;
			$d = "";
		}

		$sal_akhir1 = number_format($sal_akhir, "2", ",", ".");

		if ($sal_awal < 0) {
			$c1 = "(";
			$sal_awal = $sal_awal * -1;
			$d1 = ")";
		} else {
			$c1 = "";
			$sal_awal;
			$d1 = "";
		}

		$sal_awal1 = number_format($sal_awal, "2", ",", ".");


		if ($eku_lalu < 0) {
			$min001 = "(";
			$eku_lalu = $eku_lalu * -1;
			$min002 = ")";
		} else {
			$min001 = "";
			$eku_lalu;
			$min002 = "";
		}

		$eku_lalu1 = number_format($eku_lalu, "2", ",", ".");

		if ($eku < 0) {
			$min003 = "(";
			$eku = $eku * -1;
			$min004 = ")";
		} else {
			$min003 = "";
			$eku;
			$min004 = "";
		}

		$eku1 = number_format($eku, "2", ",", ".");

		if ($eku_tang_lalu < 0) {
			$min005 = "(";
			$eku_tang_lalu = $eku_tang_lalu * -1;
			$min006 = ")";
		} else {
			$min005 = "";
			$eku_tang_lalu;
			$min006 = "";
		}

		$eku_tang_lalu1 = number_format($eku_tang_lalu, "2", ",", ".");

		if ($eku_tang < 0) {
			$min007 = "(";
			$eku_tang = $eku_tang * -1;
			$min008 = ")";
		} else {
			$min007 = "";
			$eku_tang;
			$min008 = "";
		}

		$eku_tang1 = number_format($eku_tang, "2", ",", ".");

		if ($rk_ppkd_lalu < 0) {
			$min009 = "(";
			$rk_ppkd_lalu = $rk_ppkd_lalu * -1;
			$min010 = ")";
		} else {
			$min009 = "";
			$rk_ppkd_lalu;
			$min010 = "";
		}

		$rk_ppkd_lalu1 = number_format($rk_ppkd_lalu, "2", ",", ".");

		if ($rk_ppkd < 0) {
			$min013 = "(";
			$rk_ppkd = $rk_ppkd * -1;
			$min014 = ")";
		} else {
			$min013 = "";
			$rk_ppkd;
			$min014 = "";
		}

		$rk_ppkd1 = number_format($rk_ppkd, "2", ",", ".");

		if ($lcr < 0) {
			$min015 = "(";
			$lcr = $lcr * -1;
			$min016 = ")";
		} else {
			$min015 = "";
			$lcr;
			$min016 = "";
		}

		$lcr1 = number_format($lcr, "2", ",", ".");

		if ($lcr_lalu < 0) {
			$min017 = "(";
			$lcr_lalu = $lcr_lalu * -1;
			$min018 = ")";
		} else {
			$min017 = "";
			$lcr_lalu;
			$min018 = "";
		}

		$lcr_lalu1 = number_format($lcr_lalu, "2", ",", ".");

		if ($ast < 0) {
			$min019 = "(";
			$ast = $ast * -1;
			$min020 = ")";
		} else {
			$min019 = "";
			$ast;
			$min020 = "";
		}

		$ast1 = number_format($ast, "2", ",", ".");

		if ($ast_lalu < 0) {
			$min021 = "(";
			$ast_lalu = $ast_lalu * -1;
			$min022 = ")";
		} else {
			$min021 = "";
			$ast_lalu;
			$min022 = "";
		}

		$ast_lalu1 = number_format($ast_lalu, "2", ",", ".");

		$queryneraca = " SELECT kode, uraian, seq, isnull(normal,'') as normal, isnull(kode_1,'xxx') as kode_1, isnull(kode_2,'xxx')  as kode_2, isnull(kode_3,'xxx') as kode_3, 
						isnull(kode_4,'xxx') as kode_4, isnull(kode_5,'xxx') as kode_5, isnull(kode_6,'xxx') as kode_6, isnull(kode_7,'xxx') as kode_7, 
						isnull(kode_8,'xxx') as kode_8, isnull(kode_9,'xxx') as kode_9, isnull(kode_10,'xxx') as kode_10, isnull(kode_11,'xxx') as kode_11,
						isnull(kode_12,'xxx') as kode_12, isnull(kode_13,'xxx') as kode_13, isnull(kode_14,'xxx') as kode_14, isnull(kode_15,'xxx') as kode_15 
						FROM map_neraca_permen_77_2023 ORDER BY seq ";

		$query10 = $this->db->query($queryneraca);

		$no     = 0;

		foreach ($query10->result_array() as $res) {
			$uraian = $res['uraian'];
			$normal = $res['normal'];

			$kode_1 = trim($res['kode_1']);
			$kode_2 = trim($res['kode_2']);
			$kode_3 = trim($res['kode_3']);
			$kode_4 = trim($res['kode_4']);
			$kode_5 = trim($res['kode_5']);
			$kode_6 = trim($res['kode_6']);
			$kode_7 = trim($res['kode_7']);
			$kode_8 = trim($res['kode_8']);
			$kode_9 = trim($res['kode_9']);
			$kode_10 = trim($res['kode_10']);
			$kode_11 = trim($res['kode_11']);
			$kode_12 = trim($res['kode_12']);
			$kode_13 = trim($res['kode_13']);
			$kode_14 = trim($res['kode_14']);
			$kode_15 = trim($res['kode_15']);


			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
					  and b.kd_unit=a.kd_skpd where left(CONVERT(char(15),tgl_voucher, 112),6)<='$thn_ang$xbulan' and kd_skpd='$kd_skpd' and
						(kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
						kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
						kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
						kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
						kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
						kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
						kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
						kd_rek6 like '$kode_15%') ");

			foreach ($q->result_array() as $r) {
				$debet = $r['debet'];
				$kredit = $r['kredit'];
			}

			if ($debet == '') $debet = 0;
			if ($kredit == '') $kredit = 0;

			if ($normal == 1) {
				$nl = $debet - $kredit;
			} else {
				$nl = $kredit - $debet;
			}
			if ($nl == '') $nl = 0;

			// Jurnal Tahun lalu
			$q = $this->db->query(" SELECT SUM(b.debet) AS debet,SUM(b.kredit) AS kredit from trhju_pkd a inner join trdju_pkd b on a.no_voucher=b.no_voucher 
					  and b.kd_unit=a.kd_skpd where year(tgl_voucher)<=$thn_ang_1 and kd_skpd='$kd_skpd' and
						(kd_rek6 like '$kode_1%' or kd_rek6 like '$kode_2%'  or 
						kd_rek6 like '$kode_3%' or kd_rek6 like '$kode_4%'  or 
						kd_rek6 like '$kode_5%' or kd_rek6 like '$kode_6%'  or 
						kd_rek6 like '$kode_7%' or kd_rek6 like '$kode_8%'  or 
						kd_rek6 like '$kode_9%' or kd_rek6 like '$kode_10%' or 
						kd_rek6 like '$kode_11%' or kd_rek6 like '$kode_12%' or 
						kd_rek6 like '$kode_13%' or kd_rek6 like '$kode_14%' or 
						kd_rek6 like '$kode_15%') ");

			foreach ($q->result_array() as $rx) {
				$debet_lalu = $rx['debet'];
				$kredit_lalu = $rx['kredit'];
			}

			if ($debet_lalu == '') $debet_lalu = 0;
			if ($kredit_lalu == '') $kredit_lalu = 0;

			if ($normal == 1) {
				$sblm = $debet_lalu - $kredit_lalu;
			} else {
				$sblm = $kredit_lalu - $debet_lalu;
			}
			if ($sblm == '') $sblm = 0;

			if ($nl < 0) {
				$nl001 = "(";
				$nl = $nl * -1;
				$ln001 = ")";
			} else {
				$nl001 = "";
				$ln001 = "";
			}
			if ($sblm < 0) {
				$sblm001 = "(";
				$sblm = $sblm * -1;
				$mlbs001 = ")";
			} else {
				$sblm001 = "";
				$mlbs001 = "";
			}
			$nl1 = number_format($nl, "2", ",", ".");
			$sblm1 = number_format($sblm, "2", ",", ".");

			$no       = $no + 1;

			switch ($res['seq']) {
				case 5:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min019$ast1$min020</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min021$ast_lalu1$min022</td>
									 </tr>";
					break;
				case 10:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min015$lcr1$min016</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$min017$lcr_lalu1$min018</td>
									 </tr>";
					break;
				case 15:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										<td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 60:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 65:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 100:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 105:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
					/* case 90:
							  $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>"; 
									  break;        
			  case 100:
							 $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										<td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
							break;*/

				case 110:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 115:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 120:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 125:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;

				case 155:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 170:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									 </tr>";
					break;
				case 175:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									 </tr>";
					break;

				case 180:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 225:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 240:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 245:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;

					/*case 250: //di 2020 , ekuitas dan kewajiban
	
							 $cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
									 </tr>";
							break;*/



				case 260:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 265:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 270:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 275:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
				case 280:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;

				case 285:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
									 </tr>";
					break;

				case 286:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										  <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
									 </tr>";
					break;

				case 290:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;



				case 295:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;


				case 335:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;

				case 365:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min003$eku1$min004</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min001$eku_lalu1$min002</td>
									 </tr>";
					break;
				case 400:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min007$eku_tang1$min008</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$min005$eku_tang_lalu1$min006</td>
									 </tr>";
					break;
				default:
					$cRet    .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"center\">$no</td>                                     
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"60%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$uraian</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$nl001$nl1$ln001</td>
										 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$sblm001$sblm1$mlbs001</td>
									 </tr>";
					break;
			}
		}


		$cRet .= '</table>';

		$data['prev'] = $cRet;
		$data['sikap'] = 'preview';
		$judul = ("NERACA KONSOL $cbulan");
		$this->template->set('title', 'NERACA KONSOL $cbulan');
		switch ($cetak) {
			case 0;
				$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
				echo $cRet;
				break;
			case 1;
				echo "<title>NERACA KONSOL $cbulan</title>";
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
	// END
}
