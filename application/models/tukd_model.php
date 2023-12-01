<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */

class Tukd_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // Tampilkan semua master data fungsi
    //function getAll($limit, $offset)
    function getAll($tabel, $field1, $limit, $offset)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->order_by($field1, 'asc');
        $this->db->limit($limit, $offset);
        return $this->db->get();
    }
    function getcari($tabel, $field, $field1, $limit, $offset, $lccari)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->or_like($field, $lccari);
        $this->db->or_like($field1, $lccari);
        $this->db->order_by($field, 'asc');
        $this->db->limit($limit, $offset);
        return $this->db->get();
    }

    function get_status($tgl, $skpd)
    {
        $n_status = '';
        $tanggal = $tgl;
        $sql = "select case when '$tanggal'>=tgl_dpa_ubah and status_ubah='1' then 'nilai_ubah' 
                    when '$tanggal'>=tgl_dpa_sempurna and status_sempurna='1' then 'nilai_sempurna' 
                    when '$tanggal'<=tgl_dpa and status='1' 
                    then 'nilai' else 'nilai' end as anggaran from trhrka where kd_skpd ='$skpd' ";

        $q_trhrka = $this->db->query($sql);
        $num_rows = $q_trhrka->num_rows();

        foreach ($q_trhrka->result() as $r_trhrka) {
            $n_status = $r_trhrka->anggaran;
        }
        return $n_status;
    }

    function cek_status_spj_pend($kd_skpd)
    {
        $hasil = '0';
        $sql = "select top 1 Cast([bulan] as INT) [bulan] from trhspj_terima_ppkd where kd_skpd='$kd_skpd' and cek='1' order by Cast([bulan] as INT) desc";
        $query1 = $this->db->query($sql);
        foreach ($query1->result_array() as $res) {
            $hasil = $res['bulan'];
        }
        return $hasil;
    }


    function getAllc($tabel, $field1)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->order_by($field1, 'asc');
        //$this->db->limit($limit,$offset);
        return $this->db->get();
    }

    // Total jumlah data
    function get_count($tabel)
    {
        return $this->db->get($tabel)->num_rows();
    }

    function get_count_cari($tabel, $field1, $field2, $data)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->or_like($field1, $data);
        $this->db->or_like($field2, $data);
        $this->db->order_by($field1, 'asc');
        return $this->db->get()->num_rows();
        //return $this->db->get('ms_fungsi')->num_rows();
    }
    function get_count_teang($tabel, $field, $field1, $lccari)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->or_like($field, $lccari);
        $this->db->or_like($field1, $lccari);
        $this->db->order_by($field, 'asc');
        return $this->db->get()->num_rows();
        //return $this->db->get('ms_fungsi')->num_rows();
    }
    // Ambil by ID
    function get_by_id($tabel, $field1, $id)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->where($field1, $id);
        return $this->db->get();
    }
    //cari
    function cari($tabel, $field1, $field2, $limit, $offset, $data)
    {
        $this->db->select('*');
        $this->db->from($tabel);
        $this->db->or_like($field2, $data);
        $this->db->or_like($field1, $data);
        $this->db->order_by($field1, 'asc');
        return $this->db->get();
    }
    // Simpan data
    function save($tabel, $data)
    {
        $this->db->insert($tabel, $data);
    }

    // Update data
    function update($tabel, $field1, $id, $data)
    {
        $this->db->where($field1, $id);
        $this->db->update($tabel, $data);
    }

    // Hapus data
    function delete($tabel, $field1, $id)
    {
        $this->db->where($field1, $id);
        $this->db->delete($tabel);
    }

    function terbilang2($number)
    {
        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            $hasil = "Minus " . trim($this->depan($number));
            $poin = trim($this->belakang($number));
        } else {
            $poin = trim($this->belakang($number));
            $hasil = trim($this->depan($number));
        }
        return $hasil;
    }


    function depan($number)
    {
        $number = abs($number);
        $nomor_depan = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
        $depans = "";

        if ($number < 12) {
            $depans = " " . $nomor_depan[$number];
        } else if ($number < 20) {
            $depans = $this->depan($number - 10) . " belas";
        } else if ($number < 100) {
            $depans = $this->depan($number / 10) . " puluh " . $this->depan(fmod($number, 10));
        } else if ($number < 200) {
            $depans = "seratus " . $this->depan($number - 100);
        } else if ($number < 1000) {
            $depans = $this->depan($number / 100) . " ratus " . $this->depan(fmod($number, 100));
            //$depans = $this->depan($number/100)." Ratus ".$this->depan($number%100);
        } else if ($number < 2000) {
            $depans = "seribu " . $this->depan($number - 1000);
        } else if ($number < 1000000) {
            $depans = $this->depan($number / 1000) . " ribu " . $this->depan(fmod($number, 1000));
        } else if ($number < 1000000000) {
            $depans = $this->depan($number / 1000000) . " juta " . $this->depan(fmod($number, 1000000));
        } else if ($number < 1000000000000) {
            $depans = $this->depan($number / 1000000000) . " milyar " . $this->depan(fmod($number, 1000000000));
            //$depans = ($number/1000000000)." Milyar ".(fmod($number,1000000000))."------".$number;

        } else if ($number < 1000000000000000) {
            $depans = $this->depan($number / 1000000000000) . " triliun " . $this->depan(fmod($number, 1000000000000));
            //$depans = ($number/1000000000)." Milyar ".(fmod($number,1000000000))."------".$number;

        } else {
            $depans = "Undefined";
        }
        return $depans;
    }

    function belakang($number)
    {
        $number = number_format(abs($number), 2, '.', ',');
        $number = stristr($number, ".");
        $nomor_belakang = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan");

        $belakangs = "";
        $length = strlen($number);
        $i = 1;
        while ($i < $length) {
            $get = substr($number, $i, 1);
            $i++;
            $belakangs .= " " . $nomor_belakang[$get];
        }
        return $belakangs;
    }

    function terbilang($number)
    {
        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            $hasil = "Minus " . trim($this->depan($number));
            $poin = trim($this->belakang($number));
        } else {
            $poin = trim($this->belakang($number));
            $hasil = trim($this->depan($number));
        }

        if ($poin) {
            $hasil = $hasil . " koma " . $poin . " Rupiah";
        } else {
            $hasil = $hasil . " Rupiah";
        }
        return $hasil;
    }


    function terbilang_angka($number)
    {
        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            $hasil = "Minus " . trim($this->depan($number));
            $poin = trim($this->belakang($number));
        } else {
            $poin = trim($this->belakang($number));
            $hasil = trim($this->depan($number));
        }

        if ($poin) {
            $hasil = $hasil . " koma " . $poin;
        } else {
            $hasil = $hasil;
        }
        return $hasil;
    }


    function _mpdf($judul = '', $isi = '', $lMargin = '', $rMargin = '', $font = 0, $orientasi = '')
    {

        ini_set("memory_limit", "512M");
        $this->load->library('mpdf');

        /*
        $this->mpdf->progbar_altHTML = '<html><body>
	                                    <div style="margin-top: 5em; text-align: center; font-family: Verdana; font-size: 12px;"><img style="vertical-align: middle" src="'.base_url().'images/loading.gif" /> Creating PDF file. Please wait...</div>';        
        $this->mpdf->StartProgressBarOutput();
        */

        $this->mpdf->defaultheaderfontsize = 6;    /* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;    /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 6;    /* in pts */
        $this->mpdf->defaultfooterfontstyle = BI;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1;
        $this->mpdf->SetLeftMargin = $lMargin;
        $this->mpdf->SetRightMargin = $rMargin;
        //$this->mpdf->SetHeader('SIMAKDA||');
        $jam = date("H:i:s");
        //$this->mpdf->SetFooter('Printed on @ {DATE j-m-Y H:i:s} |Simakda| Page {PAGENO} of {nb}');
        $this->mpdf->SetFooter('Printed on @ {DATE j-m-Y H:i:s} |Halaman {PAGENO} / {nb}| ');

        $this->mpdf->AddPage($orientasi, '', '', '', '', $lMargin, $rMargin);

        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);
        $this->mpdf->Output();
    }

    //function  tanggal_format_indonesia($tgl){
    //        $tanggal  =  substr($tgl,8,2);
    //        $bulan  = $this-> getBulan(substr($tgl,5,2));
    //        $tahun  =  substr($tgl,0,4);
    //        return  $tanggal.' '.$bulan.' '.$tahun;
    //
    //   }
    //        }

    function  tanggal_format_indonesia($tgl){
            
        $tanggal  = explode('-',$tgl); 
        $bulan  = $this-> getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2].' '.$bulan.' '.$tahun;
    }
    
    function  tanggal_format_indonesia_sebelum($tgl)
    {
        $tanggal  = explode('-', $tgl);
        $tanggal1 = $tanggal[2] - 1;
        $bulan  = $this->getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal1 . ' ' . $bulan . ' ' . $tahun;
    }

    function  tanggal_ind($tgl)
    {

        $tanggal  = explode('-', $tgl);
        $bulan  = $tanggal[1];
        $tahun  =  $tanggal[0];
        return  $tanggal[2] . '-' . $bulan . '-' . $tahun;
    }

    function  getBulan($bln)
    {
        switch ($bln) {
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

    function right($value, $count)
    {
        return substr($value, ($count * -1));
    }

    function left($string, $count)
    {
        return substr($string, 0, $count);
    }

    function  dotrek($rek)
    {
        $nrek = strlen($rek);
        switch ($nrek) {
            case 1:
                $rek = $this->left($rek, 1);
                break;
            case 2:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1);
                break;
            case 4:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2);
                break;
            case 6:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 3);
                break;
            case 8:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2);
                break;
            case 29:
                $rek = $this->left($rek, 21) . '.' . substr($rek, 23, 1) . '.' . substr($rek, 24, 1) . '.' . substr($rek, 25, 1) . '.' . substr($rek, 26, 2) . '.' . substr($rek, 28, 2);
                break;
            case 12:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2) . '.' . substr($rek, 8, 4);
                break;
            default:
                $rek = "";
        }
        return $rek;
    }


    //wahyu tambah ----------------------------------------	
    function  rev_date($tgl)
    {
        $t = explode("-", $tgl);
        $tanggal  =  $t[2];
        $bulan    =  $t[1];
        $tahun    =  $t[0];
        return  $tanggal . '-' . $bulan . '-' . $tahun;
    }

    function  rev_date1($tgl)
    {
        $t = explode("-", $tgl);
        $tanggal  =  $t[0];
        $bulan    =  $t[1];
        $tahun    =  $t[2];
        return  $tahun . '-' . $bulan . '-' . $tanggal;
    }



    function get_sclient($hasil, $tabel)
    {
        $this->db->select($hasil);
        $q = $this->db->get($tabel);
        $data  = $q->result_array();
        $baris = $q->num_rows();
        return $data[0][$hasil];
    }

    function terbilang3($number)
	{
		if (!is_numeric($number))
		{
			return false;
		}
		
		if($number<0)
		{
			$hasil = "Minus ".trim($this->depan($number));
			$poin = trim($this->belakang($number));

		}else if ($number==0){
			$poin = '';
			$hasil = 'Nol';
		}
		else{
			$poin = trim($this->belakang($number));
			$hasil = trim($this->depan($number));
		}
   
		if($poin)
		{
			$hasil = $hasil." koma ".$poin." Rupiah";
		}
		else{
			$hasil = $hasil." Rupiah";
		}
		return $hasil;  
	}

    function get_nama($kode, $hasil, $tabel, $field)
    {
        $this->db->select($hasil);
        $this->db->where($field, $kode);
        $q = $this->db->get($tabel);
        $data  = $q->result_array();
        $baris = $q->num_rows();
        return $data[0][$hasil];
    }


    function get_kegiatan($kode, $hasil, $tabel, $field)
    {
        $this->db->select($hasil);
        $this->db->where(" left(" . $field . ",4)= replace(left('" . $kode . "',4),'-','.')");
        $this->db->where("jns_sub_kegiatan", "4");
        $q = $this->db->get($tabel);
        $data  = $q->result_array();
        $baris = $q->num_rows();
        return $data[0][$hasil];
    }
    // -----------------------------------------------------
    function rp_minus($nilai)
    {
        if ($nilai < 0) {
            $nilai = $nilai * (-1);
            $nilai = '(' . number_format($nilai, "2", ",", ".") . ')';
        } else {
            $nilai = number_format($nilai, "2", ",", ".");
        }

        return $nilai;
    }

    function persen($nilai, $nilai2)
    {
        if ($nilai != 0) {
            $persen = $this->rp_minus((($nilai2 - $nilai) / $nilai) * 100);
        } else {
            if ($nilai2 == 0) {
                $persen = $this->rp_minus(0);
            } else {
                $persen = $this->rp_minus(100);
            }
        }
        return $persen;
    }

    function persen_real($ang, $real)
    {
        if ($ang != 0) {
            $persen = $this->rp_minus(($real * 100) / $ang);
        } else {
            if ($real == 0) {
                $persen = $this->rp_minus(0);
            } else {
                $persen = '~';
            }
        }
        return $persen;
    }

    function combo_beban($id = '', $script = '')
    {
        $cRet    = '';
        $cRet    = "<select name=\"$id\" id=\"$id\" $script >";
        $cRet   .= "<option value=''>Pilih Beban</option>";
        $cRet   .= "<option value='1'>UP/GU</option>";
        $cRet   .= "<option value='3'>TU</option>";
        $cRet   .= "</select>";
        return $cRet;
    }
    // -----------------------------------------------------	
    function spj_trmpajak_rek($lcskpd = '', $lcrek = '', $nbulan, $fieldas)
    {
        $hasil = '';
        $fieldas_up_ini = $fieldas . '_up_ini';
        $fieldas_up_ll = $fieldas . '_up_ll';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ll = $fieldas . '_gaji_ll';
        $fieldas_brjs_ini = $fieldas . '_brjs_ini';
        $fieldas_brjs_ll = $fieldas . '_brjs_ll';
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ll ";
        $hasil = $this->db->query($csql);
        return $hasil;
    }

    function spj_trmpajak_rek2($lcskpd = '', $lcrek = '', $lcrek2 = '', $nbulan, $fieldas)
    {
        $hasil = '';
        $fieldas_up_ini = $fieldas . '_up_ini';
        $fieldas_up_ll = $fieldas . '_up_ll';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ll = $fieldas . '_gaji_ll';
        $fieldas_brjs_ini = $fieldas . '_brjs_ini';
        $fieldas_brjs_ll = $fieldas . '_brjs_ll';
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhtrmpot a INNER JOIN trdtrmpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
    			WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ll ";
        $hasil = $this->db->query($csql);
        return $hasil;
    }

    function spj_strpajak_rek($lcskpd = '', $lcrek = '', $nbulan, $fieldas)
    {
        $hasil = '';
        $fieldas_up_ini = $fieldas . '_up_ini';
        $fieldas_up_ll = $fieldas . '_up_ll';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ll = $fieldas . '_gaji_ll';
        $fieldas_brjs_ini = $fieldas . '_brjs_ini';
        $fieldas_brjs_ll = $fieldas . '_brjs_ll';
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 = '$lcrek' AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 = '$lcrek' AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ll";
        $hasil = $this->db->query($csql);
        return $hasil;
    }

    function spj_strpajak_rek2($lcskpd = '', $lcrek = '', $lcrek2 = '', $nbulan, $fieldas)
    {
        $hasil = '';
        $fieldas_up_ini = $fieldas . '_up_ini';
        $fieldas_up_ll = $fieldas . '_up_ll';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ini = $fieldas . '_gaji_ini';
        $fieldas_gaji_ll = $fieldas . '_gaji_ll';
        $fieldas_brjs_ini = $fieldas . '_brjs_ini';
        $fieldas_brjs_ll = $fieldas . '_brjs_ll';
        $csql = "SELECT (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp IN('1','2','3')) AS $fieldas_up_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='4') AS $fieldas_gaji_ll,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)='$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ini,
                (SELECT SUM(b.nilai) FROM trhstrpot a INNER JOIN trdstrpot b
                ON a.no_bukti = b.no_bukti AND a.kd_skpd=b.kd_skpd
				WHERE a.kd_skpd = '$lcskpd' AND 
                b.kd_rek5 in ('$lcrek','$lcrek2') AND MONTH(a.tgl_bukti)<'$nbulan' AND 
                a.jns_spp ='6') AS $fieldas_brjs_ll";
        $hasil = $this->db->query($csql);
        return $hasil;
    }

    function spj_tahunlalu($lcskpd = '', $nbulan)
    {
        $hasil = '';
        $csql = "SELECT SUM(ISNULL(jlain_up_ll,0)) jlain_up_ll, SUM(ISNULL(jlain_up_ini,0)) jlain_up_ini, 
                 SUM(ISNULL(jlain_up_pjkll,0)) jlain_up_pjkll, SUM(ISNULL(jlain_up_pjkini,0)) jlain_up_pjkini FROM(   
                    SELECT 
				    SUM(CASE WHEN a.jns_beban ='1' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ll,
					SUM(CASE WHEN a.jns_beban ='1' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_ini,
				    SUM(CASE WHEN a.jns_beban ='7' AND MONTH(a.tgl_bukti)<'$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_pjkll,
					SUM(CASE WHEN a.jns_beban ='7' AND MONTH(a.tgl_bukti)='$nbulan' THEN  a.nilai ELSE 0 END) AS jlain_up_pjkini
					FROM TRHOUTLAIN a 
					WHERE a.kd_skpd='$lcskpd' and thnlalu=1
				) a ";
        $hasil = $this->db->query($csql);
        return $hasil;
    }


    function qangg_sdana($tgl, $skpd, $giat, $kdrek5)
    {
        $status = $this->get_status($tgl, $skpd);
        $sumber1 = 'sumber';
        $sumber2 = 'sumber2';
        $sumber3 = 'sumber3';
        $sumber4 = 'sumber4';

        $nilai_sumber   = 'nilai_sumber';
        $nilai_sumber2  = 'nilai_sumber2';
        $nilai_sumber3  = 'nilai_sumber3';
        $nilai_sumber4  = 'nilai_sumber4';


        if ($status == 'nilai_ubah') {
            $nilai_sumber   = 'nsumber1_ubah';
            $nilai_sumber2  = 'nsumber2_ubah';
            $nilai_sumber3  = 'nsumber3_ubah';
            $nilai_sumber4  = 'nsumber4_ubah';
            $sumber1 = 'sumber1_ubah';
            $sumber2 = 'sumber2_ubah';
            $sumber3 = 'sumber3_ubah';
            $sumber4 = 'sumber4_ubah';
        } else {
            if ($status == 'nilai_sempurna') {
                $nilai_sumber   = 'nsumber1_su';
                $nilai_sumber2  = 'nsumber2_su';
                $nilai_sumber3  = 'nsumber3_su';
                $nilai_sumber4  = 'nsumber4_su';
                $sumber1 = 'sumber1_su';
                $sumber2 = 'sumber2_su';
                $sumber3 = 'sumber3_su';
                $sumber4 = 'sumber4_su';
            }
        }

        $hasil = '';
        $csql = "select rtrim(ltrim($sumber1)) [sumber],nilai_sumber [nilai_sumber],nsumber1_su [nilai_sumber_semp],nsumber1_ubah [nilai_sumber_ubah]  
                from trdrka  where kd_kegiatan='$giat' and kd_rek5='$kdrek5' and rtrim(ltrim($sumber1))<>''
                union all
                select rtrim(ltrim($sumber2)) [sumber],nilai_sumber2 [nilai_sumber],nsumber2_su [nilai_sumber_semp],nsumber2_ubah [nilai_sumber_ubah] 
                from trdrka  where kd_kegiatan='$giat' and kd_rek5='$kdrek5' and rtrim(ltrim($sumber2))<>''
                union all
                select ltrim($sumber3) [sumber],nilai_sumber3 [nilai_sumber],nsumber3_su [nilai_sumber_semp],nsumber3_ubah [nilai_sumber_ubah] 
                from trdrka  where kd_kegiatan='$giat' and kd_rek5='$kdrek5' and rtrim(ltrim($sumber3))<>''
                union all
                select rtrim(ltrim($sumber4)) [sumber],nilai_sumber4 [nilai_sumber],nsumber4_su [nilai_sumber_semp],nsumber4_ubah [nilai_sumber_ubah] 
                from trdrka  where kd_kegiatan='$giat' and kd_rek5='$kdrek5' and ltrim(ltrim($sumber4))<>'' ";
        $hasil = $this->db->query($csql);
        return $hasil;
    }


    function qtrans_sdana_ppkd($sumber, $giat, $rek, $skpd)
    {
        $sumber = $this->check_sdana($sumber);
        $nilai  = 'nil_' . $sumber;
        $hasil  = 0;
        $csql = "select sum(nilai) [total] from (
                    select 'spp' [jdl],sum(isnull(b.$nilai,0)) [nilai] from trhspp a join trdspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd
                    where b.kd_kegiatan='$giat' and b.kd_rek5='$rek' 
                    union all
                    select 'tagih' [jdl],isnull(sum(isnull(b.$nilai,0)),0) [nilai] from trhtagih a join trdtagih b on a.no_bukti=b.no_bukti 
                    and a.kd_skpd=b.kd_skpd
                    where b.kd_kegiatan='$giat' and b.kd_rek='$rek' and b.no_bukti not in (select no_tagih from trhspp where kd_skpd='$skpd')
                ) as gabung ";
        $hasil = $this->db->query($csql);
        return $hasil;
    }

    function check_sdana($sumber)
    {
        $sumber1 = $sumber;
        if ($sumber == 'DAK FISIK') {
            $sumber1 = 'dak';
        } else if ($sumber == 'DAK NON FISIK') {
            $sumber1 = 'daknf';
        }
        return $sumber1;
    }
}

/* End of file fungsi_model.php */
/* Location: ./application/models/fungsi_model.php */