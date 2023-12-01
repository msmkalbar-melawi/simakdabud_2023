<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
1. tanggal_format_indonesia(a)
2. getBulan(a)
3. dotrek(a)
*/

class support extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function tanggal_format_indonesia($tgl)
    {

        $tanggal  = explode('-', $tgl);
        $bulan  = $this->getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2] . ' ' . $bulan . ' ' . $tahun;
    }


    function get_sclient()
    {
        $this->db->select('top 1 daerah, kab_kota');
        $this->db->from('sclient');
        $sql = $this->db->get();
        return $sql->row();
    }

    function getsOrganisasi()
    {
        $this->db->select('kd_org, nm_org');
        $this->db->from('ms_organisasi');
        $this->db->order_by('kd_org');
        $sql = $this->db->get();
        if ($sql->num_rows < 0) return false;
        return $sql->result();
    }

    function getLength($tabel = "", $field = "")
    {
        $this->db->select('top 1 len(' . $field . ') as lfield');
        $this->db->from($tabel);
        $sql = $this->db->get();
        if ($sql->num_rows < 0) return false;
        return $sql->row()->lfield;
    }


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
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2);
                break;
            case 8:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2);
                break;
            case 11:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2) . '.' . substr($rek, 8, 12);;
                break;
            case 12:
                $rek = $this->left($rek, 1) . '.' . substr($rek, 1, 1) . '.' . substr($rek, 2, 2) . '.' . substr($rek, 4, 2) . '.' . substr($rek, 6, 2) . '.' . substr($rek, 8, 4);;
                break;
            default:
                $rek = "";
        }
        return $rek;
    }

    function auto_cek_status($skpd)
    {
        $tgl_spp = $this->input->post('tgl_cek');
        $sql = "SELECT top 1 
                case 
                when statu=1 and status_sempurna=1 and status_ubah=1  then 'ubah'
                when statu=1 and status_sempurna=1 and status_ubah=0  then 'geser' 
                when statu=1 and status_sempurna=0 and status_ubah=0  then 'murni' 
                when statu=1 and status_sempurna=0 and status_ubah=1  then 'murni'
                else 'murni' end as anggaran from trhrka where left(kd_skpd,17) =left('$skpd',17)";
        //  echo "$sql";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result() as $resulte) {
            $status_ang = $resulte->anggaran;
        }
        return $status_ang;
    }


    function auto_cek_status_skpd($skpd)
    {
        $tgl_spp = $this->input->post('tgl_cek');
        $sql = "SELECT a.kd_skpd as kd_skpd,a.nm_skpd as nm_skpd , b.jns_ang as jns_ang,(case when b.jns_ang='M' then 'Penetapan'
        when b.jns_ang='P1' then 'Penyempurnaan'
        when b.jns_ang='U1' then 'Perubahan' end)as nm_ang FROM ms_skpd a LEFT JOIN trhrka b
                    ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd' and 
                    tgl_dpa in(SELECT  MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd)";
        //  echo "$sql";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result() as $resulte) {
            $status_ang = $resulte->nm_ang;
        }
        return $status_ang;
    }

    function sort($id = '', $tbl = '')
    {
        if ($tbl == '') {
            $tabel = '';
        } else {
            $tabel = "$tbl" . ".";
        }
        return $sort = substr($id, 0, 4) == '1.02' || substr($id, 0, 4) == '7.01' ? "{$tabel}kd_skpd='$id'" : "left({$tabel}kd_skpd,17)=left('$id',17)";
    }

    function _mpdf1($judul = '', $isi = '', $lMargin = 10, $rMargin = 10, $font = '', $orientasi = '', $hal = '', $fonsize = '')
    {


        ini_set("memory_limit", "-1M");
        ini_set("MAX_EXECUTION_TIME", "-1");
        $this->load->library('mpdf');
        //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');


        $this->mpdf->defaultheaderfontsize = 10;    /* in pts */
        $this->mpdf->defaultheaderfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 3; /* in pts */
        $this->mpdf->defaultfooterfontstyle = I;    /* blank, B, I, or BI */
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
        //$this->mpdf->useOddEven = 1;                      

        $this->mpdf->AddPage($orientasi, '', $hal, '1', 'off');
        if ($hal == '') {
            $this->mpdf->SetFooter("");
        } else {
            $this->mpdf->SetFooter("Printed on Simakda || Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);
        $this->mpdf->Output();
    }
    function depan($number)
    {
        $number = abs($number);
        $nomor_depan = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
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
        $number = abs($number);
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
        $poin = '';
        if (!is_numeric($number)) {
            return false;
        }

        if ($number < 0) {
            $hasil = "Minus " . trim($this->depan($number));
            $poin = trim($this->belakang($number));
        } else if ($number == 0) {
            $hasil = trim($this->depan($number));
            $poin = '';
        } else {
            $hasil = trim($this->depan($number));
        }

        if ($poin) {
            $hasil = $hasil . " koma " . $poin . " rupiah";
        } else {
            $hasil = $hasil . " rupiah";
        }
        return $hasil;
    }

    function _mpdf($judul = '', $isi = '', $lMargin = 10, $rMargin = 10, $font = '', $orientasi = '', $hal = '', $fonsize = '')
    {


        ini_set("memory_limit", "-1M");
        ini_set("MAX_EXECUTION_TIME", "-1");
        $this->load->library('mpdf');

        $this->mpdf->defaultheaderfontsize = 10;    /* in pts */
        $this->mpdf->defaultheaderfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 3; /* in pts */
        $this->mpdf->defaultfooterfontstyle = I;    /* blank, B, I, or BI */
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

        $this->mpdf->AddPage($orientasi, '', $hal, '1', 'off');
        if ($hal == '') {
            $this->mpdf->SetFooter("");
        } else {
            $this->mpdf->SetFooter("Printed on Simakda || Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML(mb_convert_encoding($judul, 'utf-8', 'utf-8'));
        $this->mpdf->writeHTML(utf8_encode($isi));
        $this->mpdf->Output();
    }


    function _mpdf_margin($judul = '', $isi = '', $lMargin = 10, $rMargin = 10, $font = '', $orientasi = '', $hal = '', $fonsize = '', $atas = '', $bawah = '', $kiri = '', $kanan = '')
    {

        ini_set("memory_limit", "-1M");
        ini_set("MAX_EXECUTION_TIME", "-1");
        $this->load->library('mpdf');
        $this->mpdf->defaultheaderfontsize = 10;    /* in pts */
        $this->mpdf->defaultheaderfontstyle = I;    /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 3; /* in pts */
        $this->mpdf->defaultfooterfontstyle = I;    /* blank, B, I, or BI */
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
        $this->mpdf->AddPage($orientasi, '', $hal, '1', 'off', $kiri, $kanan, $atas, $bawah);
        if ($hal == '') {
            $this->mpdf->SetFooter("");
        } else {
            $this->mpdf->SetFooter("Printed on Simakda SKPD || Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);
        $this->mpdf->Output();
    }

    /**
     * Method ini digunakan untuk mengambil semua data skpd dinas kesehatan dan rumah sakit pada table ms_skpd
     * @param ?string $select // opsional parameter  untuk  select column yang akan di pilih default '*'
     * @param ?string $return // opsioanl parameter untuk type return yang digunakan defult object
     * @return $return
     * @author Emon Krismon
     * @link https://github.com/krismonsemanas
     */
    public function skpdBlud($select = '*', $return = 'object')
    {
        $sql = "SELECT $select FROM ms_skpd WHERE left(kd_skpd,4) = '1.02'";

        $query = $this->db->query($sql);

        if ($return === 'object') {
            return $query->result();
        } else {
            return $query->result_array();
        }
    }

    /**
     * Method ini digunakan untuk mengambil skpd rumah sakit umum daerah
     * @param ?string $select // opsional parameter  untuk  select column yang akan di pilih default '*'
     * @param ?string $return // opsioanl parameter untuk type return yang digunakan defult object
     * @return $return
     * @author Emon Krismon
     * @link https://github.com/krismonsemanas
     */
    public function skpdBludFirst($select = '*', $return = 'object')
    {
        $sql = "SELECT TOP 1 $select FROM ms_skpd WHERE left(kd_skpd,4) = '1.02' AND right(kd_skpd, 4) = '0001' ";

        $query = $this->db->query($sql);

        if ($return === 'object') {
            return $query->row();
        } else {
            return $query->row_array();
        }
    }

    /** 
     * Fungsi ini bertujuan untuk mengenerate bulan
     * @return array bulan
     * @author Emon Krismon
     * @link https://github.com/krismonsemanas
     */
    public function generateBulan()
    {
        $months = [];

        for ($i = 1; $i <= 12; $i++) {
            $months[$i] = [
                'id' => $i,
                'nama' => $this->getBulan($i)
            ];
        }

        return $months;
    }
}
