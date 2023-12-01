<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */

class cek_anggaran_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function cek_anggaran($kode)
	{
		$sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd , b.jns_ang as jns_ang FROM ms_skpd a LEFT JOIN trhrka b
                    ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$kode' and 
                    b.tgl_dpa in(SELECT MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd AND status='1')";
		return $query1 = $this->db->query($sql)->row()->jns_ang;
	}

	function cek_angkas()
	{

		$sql = "SELECT * from tb_status_angkas where status='1' order by id DESC";
		$hasil = $this->db->query($sql);
		return $hasil;
	}


	function cek_anggaranI($kode)
	{
		$sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd , b.jns_ang as jns_ang, no_dpa as no_dpa FROM ms_skpd a LEFT JOIN trhrka b
                    ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$kode' and 
                    tgl_dpa in(SELECT MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd AND status='1')";
		return $query1 = $this->db->query($sql)->row()->no_dpa;
	}
}

/* End of file fungsi_model.php */
/* Location: ./application/models/fungsi_model.php */