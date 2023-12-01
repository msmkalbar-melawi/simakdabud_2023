<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

use mikehaertl\wkhtmlto\Pdf;

require_once('/application/3rdparty/wkhtmltopdf/Pdf.php');

class Spd extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
        $this->load->model('cek_anggaran_model');
    }

    public $keu1 = "5.02.0.00.0.00.02.0000";

    function index($offset = 0, $lctabel, $field, $field1, $judul, $list, $lccari)
    {
        $data['page_title'] = "CETAK $judul";

        //$total_rows = $this->master_model->get_count($lctabel);
        if (empty($lccari)) {
            $total_rows = $this->master_model->get_count($lctabel);
            $lc = "/.$lccari";
        } else {
            $total_rows = $this->master_model->get_count_teang($lctabel, $field, $field1, $lccari);
            $lc = "";
        }
        // pagination        
        $config['base_url']     = site_url("rka/" . $list);
        $config['total_rows']   = $total_rows;
        $config['per_page']     = '10';
        $config['uri_segment']  = 3;
        $config['num_links']    = 5;
        $config['full_tag_open'] = '<ul class="page-navi">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="current">';
        $config['cur_tag_close'] = '</li>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $limit                  = $config['per_page'];
        $offset                 = $this->uri->segment(3);
        $offset                 = (!is_numeric($offset) || $offset < 1) ? 0 : $offset;

        if (empty($offset)) {
            $offset = 0;
        }

        //$data['list']         = $this->master_model->getAll($lctabel,$field,$limit, $offset);
        if (empty($lccari)) {
            $data['list']       = $this->master_model->getAll($lctabel, $field, $limit, $offset);
        } else {
            $data['list']       = $this->master_model->getCari($lctabel, $field, $field1, $limit, $offset, $lccari);
        }
        $data['num']        = $offset;
        $data['total_rows'] = $total_rows;

        $this->pagination->initialize($config);
        $a = $judul;
        $data['sikap'] = 'list';
        $this->template->set('title', 'CETAK ' . $judul);
        $this->template->load('template', "anggaran/spd/" . $list, $data);
    }

    function _mpdf($judul = '', $isi = '', $lMargin = '', $rMargin = '', $font = 10, $orientasi = '', $hal = '', $tab = '', $jdlsave = '', $tMargin = '')
    {


        ini_set("memory_limit", "-1");
        $this->load->library('mpdf');
        //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');


        $this->mpdf->defaultheaderfontsize = 6; /* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;   /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 1; /* in pts */
        $this->mpdf->defaultfooterfontstyle = blank;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 0;
        $sa = 1;
        $tes = 0;
        if ($hal == '') {
            $hal1 = 1;
        }
        if ($hal !== '') {
            $hal1 = $hal;
        }

        if ($tMargin == '') {
            $tMargin = 16;
        }

        if ($lMargin == '') {
            $lMargin = 15;
        }

        if ($rMargin == '') {
            $rMargin = 15;
        }


        $this->mpdf = new mPDF('utf-8', array(215, 330), $size, '', $lMargin, $rMargin, $tMargin); //folio

        $mpdf->cacheTables = true;
        $mpdf->packTableData = true;
        $mpdf->simpleTables = true;
        $this->mpdf->AddPage($orientasi, '', $hal1, '1', 'off');
        if (!empty($tab)) $this->mpdf->SetTitle($tab);
        if ($hal != 'no') {
            $this->mpdf->SetFooter("Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        //$this->mpdf->simpleTables= true;     
        $this->mpdf->writeHTML(utf8_encode($isi));
        //$this->mpdf->Output('');
        $this->mpdf->Output($judul, 'I');
    }

    function q_mpdf($judul = '', $isi = '', $lMargin = '', $rMargin = '', $font = 10, $orientasi = '', $hal = '', $tab = '', $jdlsave = '', $tMargin = '')
    {


        ini_set("memory_limit", "-1");
        $this->load->library('mpdf');
        //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');


        $this->mpdf->defaultheaderfontsize = 6; /* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;   /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 1; /* in pts */
        $this->mpdf->defaultfooterfontstyle = blank;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 0;
        $sa = 1;
        $tes = 0;
        if ($hal == '') {
            $hal1 = 1;
        }
        if ($hal !== '') {
            $hal1 = $hal;
        }

        if ($tMargin == '') {
            $tMargin = 15;
        }

        if ($lMargin == '') {
            $lMargin = 15;
        }

        if ($rMargin == '') {
            $rMargin = 15;
        }


        $this->mpdf = new mPDF('utf-8', array(215, 330), $size, '', $lMargin, $rMargin, $tMargin); //folio  
        $mpdf->cacheTables = true;
        $mpdf->packTableData = true;
        $mpdf->simpleTables = true;
        $this->mpdf->AddPage($orientasi, '', $hal1, '1', 'off');
        if (!empty($tab)) $this->mpdf->SetTitle($tab);
        if ($hal != 'no') {
            $this->mpdf->SetFooter("Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        //$this->mpdf->simpleTables= true;     
        $this->mpdf->writeHTML($isi);
        //$this->mpdf->Output('');
        $this->mpdf->Output($judul, 'I');
    }

    function ambil_jns_anggaran()
    {
        $query1 = $this->db->query("SELECT * from tb_status_anggaran where status_aktif='1'");
        $ii     = 0;
        $result = array();
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id'        => $ii,
                'nama'      => $resulte['nama'],
                'kolom'     => $resulte['kolom'],
                'kode'      => $resulte['kode'],
                'kd_ang'    => $resulte['kode'],
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function config_tahun()
    {
        $result = array();
        $tahun  = $this->session->userdata('pcThang');
        $result = $tahun;
        echo json_encode($result);
    }

    function spd_belanja()
    {
        $data['page_title'] = 'INPUT SPD BELANJA';
        $this->template->set('title', 'INPUT SPD BELANJA');
        $this->template->load('template', 'anggaran/spd/spd_belanja1', $data);
    }

    function spd_belanja_revisi()
    {
        $data['page_title'] = 'INPUT SPD BELANJA REVISI';
        $this->template->set('title', 'INPUT SPD BELANJA REVISI');
        $this->template->load('template', 'anggaran/spd/spd_belanja_revisi', $data);
    }

    function jumlah_detail_spd()
    {

        $no_spd = $this->input->post('cno_spd');
        $sql    = "SELECT SUM(nilai) as nilai FROM trdspd WHERE no_spd = '$no_spd' ";

        $query1 = $this->db->query($sql);
        $test   = $query1->num_rows();
        $ii     = 0;

        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'total' => $resulte['nilai']
            );
            $ii++;
        }

        if ($test === 0) {
            $result = array(
                'total' => ''
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function bln_spdakhir($cskpd = '')
    {
        $kdskpd = $this->input->post('skpd');
        $jns = $this->input->post('jenis');
        $query1 = $this->db->query("select top 1 bulan_akhir from trhspd where kd_skpd='$kdskpd' and jns_beban='$jns' order by tgl_spd desc ");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'cbulan_akhir' => $resulte['bulan_akhir']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function cek_simpan()
    {
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field');

        $hasil = $this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor'");
        foreach ($hasil->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }
        if ($jumlah > 0) {
            $msg = array('pesan' => '1');
            echo json_encode($msg);
        } else {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
        }
    }


    function load_bendahara_p()
    {

        $kdskpd = $this->input->post('kode');

        $query1 = $this->db->query(" select nip,nama from ms_ttd where kd_skpd='$kdskpd' AND kode='BK' ");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama']
            );
            $ii++;
        }

        //return $result;
        echo json_encode($result);
    }


    function load_kadis()
    {

        $kdskpd = $this->input->post('kode');

        $query1 = $this->db->query(" select nip,nama from ms_ttd where kd_skpd='$kdskpd' AND kode in ('PA','KPA') ");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama']
            );
            $ii++;
        }

        //return $result;
        echo json_encode($result);
    }


    function load_spd_bl()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $id  = $this->session->userdata('pcUser');
        $where = "WHERE jns_beban='5' and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ";
        if ($kriteria <> '') {
            $where = "where ((upper(a.no_spd) like upper('%$kriteria%') or a.tgl_Spd like '%$kriteria%' or upper(a.nm_skpd) like 
                    upper('%$kriteria%') or upper(a.kd_skpd) like upper('%$kriteria%')) and upper(a.jns_beban)='5' 
                    and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ) ";
        }

        $sql = "SELECT count(*) as total from trhspd a $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = "SELECT TOP $rows  a.*,
        (select TOP 1 nama from ms_ttd b where a.kd_bkeluar=b.nip ) as nama ,
        case when jns_beban='5' then 'BELANJA' else 'PEMBIAYAAN' end AS nm_beban ,
        (select nama from tb_status_angkas where a.jns_ang=status_kunci)as nm_angkas
        from trhspd a  $where  AND no_spd not in (SELECT TOP $offset  no_spd from trhspd a where a.jns_beban='5' and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') order by a.no_spd,a.tgl_Spd,a.kd_skpd) 
        group by a.no_spd, a.tgl_spd,a.kd_skpd, a.nm_skpd, a.jns_beban, a.no_dpa, a.bulan_awal, a.bulan_akhir, a.kd_bkeluar, a.triwulan,a.klain, a.username,a.tglupdate, a.st, a.[status], a.total, revisi_ke,jns_ang
        order by no_spd,tgl_Spd,kd_skpd ";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_spd' => $resulte['no_spd'],
                'tgl_spd' => $resulte['tgl_spd'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'ketentuan' => $resulte['klain'],
                'nama_bend' => $resulte['nama'],
                'nip' => $resulte['kd_bkeluar'],
                'jns_beban' => $resulte['jns_beban'],
                'nm_beban' => $resulte['nm_beban'],
                'bulan_awal' => $resulte['bulan_awal'],
                'bulan_akhir' => $resulte['bulan_akhir'],
                'total' => $resulte['total'],
                'revisi' => $resulte['revisi_ke'],
                'jns_angkas' => $resulte['jns_ang'],
                'nm_angkas' => $resulte['nm_angkas'],
                'status' => $resulte['status']
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }


    function load_spd_bl_lama()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $id  = $this->session->userdata('pcUser');
        $where = "WHERE jns_beban='5' and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ";
        if ($kriteria <> '') {
            $where = "where ((upper(a.no_spd) like upper('%$kriteria%') or a.tgl_Spd like '%$kriteria%' or upper(a.nm_skpd) like 
                        upper('%$kriteria%') or upper(a.kd_skpd) like upper('%$kriteria%')) and upper(a.jns_beban)='5' 
                        and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ) ";
        }

        $sql = "SELECT count(*) as total from trhspd a $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        // $sql = "SELECT TOP $rows a.*,nama,case when jns_beban='5' then 'BELANJA' else 'PEMBIAYAAN' end AS nm_beban from trhspd a left join ms_ttd b 
        //     on a.kd_bkeluar=b.nip  $where  AND no_spd not in (SELECT TOP $offset  no_spd from trhspd a left join ms_ttd b 
        //     on a.kd_bkeluar=b.nip where a.jns_beban='5' and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') order by a.no_spd,a.tgl_Spd,a.kd_skpd) 
        //     group by a.no_spd, a.tgl_spd,a.kd_skpd, a.nm_skpd, a.jns_beban, a.no_dpa, a.bulan_awal, a.bulan_akhir, a.kd_bkeluar, a.triwulan,a.klain, a.username,a.tglupdate, a.st, a.[status], a.total, nama
        //     order by no_spd,tgl_Spd,kd_skpd ";

        $sql = "SELECT a.*, nama, CASE WHEN jns_beban = '5' THEN 'BELANJA' ELSE 'PEMBIAYAAN' END AS nm_beban FROM trhspd a LEFT JOIN ms_ttd b on a.kd_bkeluar = b.nip $where ORDER BY bulan_awal, kd_skpd OFFSET $offset ROWS FETCH NEXT $rows ROWS ONLY";

        // print_r($sql);die();
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $bulan = $this->tgl_indo($resulte['tgl_spd']);
            $row[] = array(
                'id' => $ii,
                'no_spd' => $resulte['no_spd'],
                'tgl_spd' => $resulte['tgl_spd'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'ketentuan' => $resulte['klain'],
                'nama_bend' => $resulte['nama'],
                'nip' => $resulte['kd_bkeluar'],
                'jns_beban' => $resulte['jns_beban'],
                'nm_beban' => $resulte['nm_beban'],
                'bulan_awal' => $resulte['bulan_awal'],
                'bulan_akhir' => $resulte['bulan_akhir'],
                'total' => $resulte['total'],
                'status' => $resulte['status'],
                'bln_spd' => $bulan
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function tgl_indo($tanggal)
    {
        $bulan = array(
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);

        // variabel pecahkan 0 = tanggal
        // variabel pecahkan 1 = bulan
        // variabel pecahkan 2 = tahun

        return $bulan[(int)$pecahkan[1]];
    }


    function load_spd_pembiayaan()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        //$kd_skpd = '1.20.08.10'; 
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $id  = $this->session->userdata('pcUser');
        $where = "WHERE jns_beban='6' and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ";
        if ($kriteria <> '') {
            $where = "where ((upper(a.no_spd) like upper('%$kriteria%') or a.tgl_Spd like '%$kriteria%' or upper(a.nm_skpd) like 
                        upper('%$kriteria%') or upper(a.kd_skpd) like upper('%$kriteria%')) and upper(a.jns_beban)='62' 
                        and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ) ";
        }

        $sql = "SELECT count(*) as total from trhspd a $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        //$sql = "SELECT *,IF(jns_beban='52','Belanja Langsung','Belanja Tidak Langsung') AS nm_beban from trhspd $where order by tgl_Spd,kd_skpd limit $offset,$rows";

        $sql = "SELECT TOP $rows  a.*,nama,case when jns_beban='5' then 'BELANJA' else 'PEMBIAYAAN' end AS nm_beban from trhspd a left join ms_ttd b 
            on a.kd_bkeluar=b.nip  $where  AND no_spd not in (SELECT TOP $offset  no_spd from trhspd a left join ms_ttd b 
            on a.kd_bkeluar=b.nip where a.jns_beban='6' and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') order by a.no_spd,a.tgl_Spd,a.kd_skpd) 
            group by a.jns_ang,a.no_spd, a.tgl_spd,a.kd_skpd, a.nm_skpd, a.jns_beban, a.no_dpa, a.bulan_awal, a.bulan_akhir, a.kd_bkeluar, a.triwulan,a.klain, a.username,a.tglupdate, a.st, a.[status], a.total, nama,revisi_ke
            order by no_spd,tgl_Spd,kd_skpd ";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_spd' => $resulte['no_spd'],
                'tgl_spd' => $resulte['tgl_spd'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'ketentuan' => $resulte['klain'],
                'nama_bend' => $resulte['nama'],
                'nip' => $resulte['kd_bkeluar'],
                'jns_beban' => $resulte['jns_beban'],
                'nm_beban' => $resulte['nm_beban'],
                'bulan_awal' => $resulte['bulan_awal'],
                'bulan_akhir' => $resulte['bulan_akhir'],
                'total' => $resulte['total'],
                'total_rupiah' => number_format($resulte['total'], "2", ".", ","),
                'status' => $resulte['status']
            );
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }


    function skpduser()
    {
        $lccr = $this->input->post('q');
        $id  = $this->session->userdata('pcUser');
        // $sql = "SELECT kd_skpd,nm_skpd,jns FROM ms_skpd where (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) and 
        //         kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') and right(kd_skpd,4)='0000' order by kd_skpd ";

        $sql = "SELECT kd_skpd,nm_skpd,jns FROM ms_skpd where (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) and 
                    kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') order by kd_skpd ";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns' => $resulte['jns']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_ttd_unit($skpd = '')
    {
        $kd_skpd = $skpd;
        $kd_skpd2 = $this->left($kd_skpd, 17);
        $lccr = '';
        $lccr = $this->input->post('q');
        $sql = "SELECTS * FROM ms_ttd WHERE left(kd_skpd,22)= '$kd_skpd2' AND kode in ('PA','KPA')  AND (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%'))";



        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function load_ttd_kba()
    {
        $lccr = '';
        $lccr = $this->input->post('q');
        $sql = "SELECT * FROM ms_ttd WHERE (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%')) and kode='BUD'";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function skpduser_pembiayaan()
    {
        $lccr = $this->input->post('q');
        $id  = $this->session->userdata('pcUser');
        $sql = "SELECT kd_skpd,nm_skpd,jns FROM ms_skpd where (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) and 
                    kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id' and kd_skpd='5.02.0.00.0.00.02.0000') order by kd_skpd ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns' => $resulte['jns']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function load_bendahara_ppkd()
    {

        $query1 = $this->db->query("select nip,nama,jabatan,pangkat from ms_ttd where kode='PPKD'");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'nip_ppkd' => $resulte['nip'],
                'nama_ppkd' => $resulte['nama'],
                'jabatan_ppkd' => $resulte['jabatan'],
                'pangkat_ppkd' => $resulte['pangkat']
            );
            $ii++;
        }

        //return $result;
        echo json_encode($result);
    }
    function update_sts_spd()
    {
        $no_spd      = $this->input->post('no');
        $ckd_skpd     = $this->input->post('kd_skpd');
        $csts      = $this->input->post('status_spd');
        $usernm      = $this->session->userdata('pcNama');
        $last_update =  date('d-m-y H:i:s');

        $sql = "update trhspd set status='$csts' where no_spd='$no_spd' and kd_skpd='$ckd_skpd' ";
        $asg = $this->db->query($sql);
        if ($asg > 0) {
            echo $csts;
        } else {
            echo '5';
        }
    }

    function cek_simpan_spd()
    {
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field');

        $hasil = $this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor' and (sp2d_batal is null or sp2d_batal<>1)");
        foreach ($hasil->result_array() as $row) {
            $jumlah = $row['jumlah'];
        }
        if ($jumlah > 0) {
            $msg = array('pesan' => '1');
            echo json_encode($msg);
        } else {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
        }
    }


    function load_trskpd()
    {
        //$cskpd=$this->uri->segment(3);
        $cskpd = $this->input->post('kode');
        $jenis = $this->input->post('jenis'); //'52';
        $giat = $this->input->post('giat');
        $no = $this->input->post('no');
        if ($jenis != '') {
            $jns_beban = " and b.jns_sub_kegiatan='$jenis' ";
        } else {
            $jns_beban = '';
        }
        if ($giat != '') {
            $where = " and a.kd_sub_kegiatan not in ($giat) ";
        } else {
            $where = '';
        }
        if ($no != '') {
            $spdlalu = ",(SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_sub_kegiatan=a.kd_sub_kegiatan  AND m.no_spd <> '$no') AS lalu";
        } else {
            $spdlalu = ',0 as lalu';
        }
        $lccr = '';
        $lccr = $this->input->post('q');
        $sql = "SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan,a.kd_program,(select nm_program from m_prog where kd_program=a.kd_program) as nm_program,a.total $spdlalu FROM trskpd a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
                    WHERE LEFT(a.kd_sub_kegiatan,4)= LEFT('$cskpd',4) $jns_beban $where AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_sub_kegiatan) LIKE UPPER('%$lccr%'))";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                // 'kd_kegiatan' => $resulte['kd_kegiatan'],  
                // 'nm_kegiatan' => $resulte['nm_kegiatan'],
                'kd_program' => $resulte['kd_program'],
                'nm_program' => $resulte['nm_program'],
                'lalu'       => $resulte['lalu'],
                'total'       => $resulte['total']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function load_dspd()
    {

        $no = $this->input->post('no');
        $sql = "SELECT a.*,(SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_sub_kegiatan=a.kd_sub_kegiatan  AND m.no_spd <> '$no') AS lalu,
                    (SELECT SUM(total) FROM trskpd WHERE kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_skpd=b.kd_skpd ) AS anggaran from trdspd a inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' order by b.no_spd,a.kd_sub_kegiatan,a.kd_rek6";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_spd' => $resulte['no_spd'],
                'kd_kegiatan' => $resulte['kd_kegiatan'],
                'nm_kegiatan' => $resulte['nm_kegiatan'],
                'kd_rekening' => $resulte['kd_rek6'],
                'nm_rekening' => $resulte['nm_rek6'],
                'kd_program' => $resulte['kd_program'],
                'nm_program' => $resulte['nm_program'],
                'nilai' => $resulte['nilai'],
                'lalu' => $resulte['lalu'],
                'anggaran' => $resulte['anggaran']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_dspd_kosong()
    {
        $no = $this->input->post('no');
        $sql = "SELECT a.*,(SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_sub_kegiatan=a.kd_sub_kegiatan  AND m.no_spd <> '$no') AS lalu,
                    (SELECT SUM(total) FROM trskpd WHERE kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_skpd=b.kd_skpd ) AS anggaran from trdspd a inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' order by b.no_spd,a.kd_sub_kegiatan,a.kd_rek6";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_spd' => $resulte['no_spd'],
                'kd_kegiatan' => $resulte['kd_kegiatan'],
                'nm_kegiatan' => $resulte['nm_kegiatan'],
                'kd_rekening' => $resulte['kd_rek6'],
                'nm_rekening' => $resulte['nm_rek6'],
                'kd_program' => $resulte['kd_program'],
                'nm_program' => $resulte['nm_program'],
                'nilai' => $resulte['nilai'],
                'lalu' => $resulte['lalu'],
                'anggaran' => $resulte['anggaran']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function get_status($tgl, $skpd)
    {
        $n_status   = '';
        $tanggal    = $tgl;
        $sql        = "SELECT TOP 1 * from trhrka where kd_skpd ='$skpd' and status='1' and tgl_dpa<='$tgl' order by tgl_dpa DESC";
        $q_trhrka   = $this->db->query($sql);
        $num_rows   = $q_trhrka->num_rows();
        foreach ($q_trhrka->result() as $r_trhrka) {
            $n_status = $r_trhrka->jns_ang;
        }
        return $n_status;
    }

    function get_status2($skpd)
    {
        $n_status = '';

        $qkolom = $this->get_status_angkas($skpd);

        $sql = "SELECT kode from tb_status_angkas WHERE status_kunci='$qkolom'";

        $q_trhrka = $this->db->query($sql);
        $num_rows = $q_trhrka->num_rows();

        foreach ($q_trhrka->result() as $r_trhrka) {
            $n_status = $r_trhrka->kode;
        }
        return $n_status;
    }

    function get_status_angkas($skpd)
    {
        $n_status = '';
        $sql = "SELECT TOP 1 * from (
            select '1'as urut,'nilai_susun' as status,murni as nilai from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '2'as urut,'nilai_susun1',murni_geser1 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '3'as urut,'nilai_susun2',murni_geser2 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '4'as urut,'nilai_susun3',murni_geser3 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '5'as urut,'nilai_susun4',murni_geser4 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '6'as urut,'nilai_susun5',murni_geser5 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '7'as urut,'nilai_sempurna',sempurna1 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '8'as urut,'nilai_sempurna11',sempurna1_geser1 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '9'as urut,'nilai_sempurna12',sempurna1_geser2 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '10'as urut,'nilai_sempurna13',sempurna1_geser3 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '11'as urut,'nilai_sempurna14',sempurna1_geser4 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '12'as urut,'nilai_sempurna15',sempurna1_geser5 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '13'as urut,'nilai_sempurna2',sempurna2 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '14'as urut,'nilai_sempurna21',sempurna2_geser1 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '15'as urut,'nilai_sempurna22',sempurna2_geser2 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '16'as urut,'nilai_sempurna23',sempurna2_geser3 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '17'as urut,'nilai_sempurna24',sempurna2_geser4 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '18'as urut,'nilai_sempurna25',sempurna2_geser5 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '19'as urut,'nilai_sempurna3',sempurna3 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '20'as urut,'nilai_sempurna31',sempurna3_geser1 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '21'as urut,'nilai_sempurna32',sempurna3_geser2 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '22'as urut,'nilai_sempurna33',sempurna3_geser3 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '23'as urut,'nilai_sempurna34',sempurna3_geser4 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '24'as urut,'nilai_sempurna35',sempurna3_geser5 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '25'as urut,'nilai_sempurna4',sempurna4 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '26'as urut,'nilai_sempurna41',sempurna4_geser1 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '27'as urut,'nilai_sempurna42',sempurna4_geser2 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '28'as urut,'nilai_sempurna43',sempurna4_geser3 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '29'as urut,'nilai_sempurna44',sempurna4_geser4 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '30'as urut,'nilai_sempurna45',sempurna4_geser5 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '31'as urut,'nilai_sempurna5',sempurna5 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '32'as urut,'nilai_sempurna51',sempurna5_geser1 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '33'as urut,'nilai_sempurna52',sempurna5_geser2 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '34'as urut,'nilai_sempurna53',sempurna5_geser3 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '35'as urut,'nilai_sempurna54',sempurna5_geser4 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '36'as urut,'nilai_sempurna55',sempurna5_geser5 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '37'as urut,'nilai_ubah',ubah from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '43'as urut,'nilai_ubah1',ubah1 from status_angkas where kd_skpd ='$skpd'
            UNION ALL
            select '49'as urut,'nilai_ubah2',ubah2 from status_angkas where kd_skpd ='$skpd'
            )zz where nilai='1' ORDER BY cast(urut as int) DESC";

        $q_trhrka = $this->db->query($sql);
        $num_rows = $q_trhrka->num_rows();

        foreach ($q_trhrka->result() as $r_trhrka) {
            $n_status = $r_trhrka->status;
        }
        return $n_status;
    }


    function cek_anggaran_kas_spd()
    {

        $skpd   = $this->input->post('skpd');
        $angkas = $this->get_status_angkas($skpd);
        $result = array();

        $query1 = $this->rka_model->qcek_anggaran_kas($skpd, $angkas);
        $result = $query1->num_rows();
        echo json_encode($result);
    }

    function load_tot_dspd_bl($jenis = '', $kode = '', $bln1 = '', $bln2 = '', $nomor = '', $tgl1 = '', $revisi_ke = '')
    {
        $angkas = $this->uri->segment(9);
        $nomor = $this->uri->segment(7);
        $data1 = $this->cek_anggaran_model->cek_anggaran($kode);
        $n_trdskpd = 'trdskpd_ro';
        $cnomor = str_replace('123456789', '/', $nomor);

        if ($revisi_ke == 0 || $revisi_ke == '0' || $revisi_ke == '' || $revisi_ke == null) {
            $sql = "SELECT SUM(anggaran)as nilai
                 from (
                SELECT a.kd_unit, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, a.kd_rek6 , a.nm_rek6, a.total_ubah as anggaran, 
                b.nilai-isnull(lalu_tw,0) as nilai,c.lalu FROM(

                 SELECT a.kd_skpd as kd_unit, b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,b.kd_rek6 , b.nm_rek6 , 
                    sum(b.nilai) as total_ubah, left(a.kd_skpd,22) kd_skpd FROM trskpd a 
                 inner join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd 
                 inner join ms_sub_kegiatan c on a.kd_sub_kegiatan=c.kd_sub_kegiatan 
                 WHERE left(b.kd_skpd,22)=left('$kode',22) and c.jns_sub_kegiatan='5' AND a.jns_ang='$data1'
                group by left(a.kd_skpd,22), a.kd_skpd,a.kd_program, a.nm_program,b.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6
                 ) a LEFT JOIN (
                    SELECT kd_sub_kegiatan, b.kd_rek6, left(kd_skpd,22) kd_skpd, kd_skpd as kd_unit, SUM($angkas) as nilai FROM $n_trdskpd b 
                    WHERE b.bulan>='$bln1' AND b.bulan<='$bln2' AND left(kd_skpd,22)=left('$kode',22) 
                    GROUP BY left(kd_skpd,22),kd_skpd,kd_sub_kegiatan,kd_rek6 
                    )b ON a.kd_unit=b.kd_unit and a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_rek6=b.kd_rek6
                LEFT JOIN (

                 SELECT kd_unit as kd_skpd,kd_sub_kegiatan,kd_rek6,isnull(SUM(a.nilai),0) as lalu FROM trdspd a LEFT JOIN trhspd b 
                ON a.no_spd=b.no_spd 
                WHERE left(b.kd_skpd,22)=left('$kode',22) and a.no_spd != '$cnomor' 
                and b.tgl_spd<'$tgl1' 
                GROUP BY kd_unit,kd_sub_kegiatan,kd_rek6 
                 ) c 
                 
                 ON a.kd_unit=c.kd_skpd and a.kd_sub_kegiatan=c.kd_sub_kegiatan and a.kd_rek6=c.kd_rek6
                 
                LEFT JOIN (

                 SELECT kd_unit as kd_skpd,kd_sub_kegiatan,kd_rek6,isnull(SUM(a.nilai),0) as lalu_tw FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                WHERE left(b.kd_skpd,22)=left('$kode',22) and b.bulan_awal='$bln1' AND b.bulan_akhir='$bln2' and a.no_spd != '$cnomor' and b.tgl_spd<'$tgl1' 
                GROUP BY kd_unit,kd_sub_kegiatan,kd_rek6 
                 ) d 
                 ON a.kd_unit=d.kd_skpd and a.kd_sub_kegiatan=d.kd_sub_kegiatan and a.kd_rek6=d.kd_rek6
                )xxx 
                            ";
        } else {
            $sql = "SELECT sum(anggaran) as nilai
                         from (
                        SELECT a.kd_unit, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, a.kd_rek6 , a.nm_rek6, a.total_ubah as anggaran, 
                        b.nilai,c.lalu FROM(

                         SELECT a.kd_skpd as kd_unit, b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,b.kd_rek6 , b.nm_rek6 , 
                            sum(b.nilai) as total_ubah, left(a.kd_skpd,22) kd_skpd FROM trskpd a  
                         inner join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd 
                         inner join ms_sub_kegiatan c on a.kd_sub_kegiatan=c.kd_sub_kegiatan 
                         WHERE left(b.kd_skpd,22)=left('$kode',22) and c.jns_sub_kegiatan='5' AND a.jns_ang='$data1' AND b.jns_ang='$data1'
                        group by left(a.kd_skpd,22), a.kd_skpd,a.kd_program, a.nm_program,b.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6
                         ) a LEFT JOIN (
                            SELECT kd_sub_kegiatan, b.kd_rek6, left(kd_skpd,22) kd_skpd, kd_skpd as kd_unit, SUM($angkas) as nilai FROM trdskpd_ro b 
                            WHERE b.bulan>='$bln1' AND b.bulan<='$bln2' AND left(kd_skpd,22)=left('$kode',22) 
                            GROUP BY left(kd_skpd,22),kd_skpd,kd_sub_kegiatan,kd_rek6 
                            
                            )b ON a.kd_unit=b.kd_unit and a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_rek6=b.kd_rek6
                            
                        LEFT JOIN (

                         SELECT kd_unit as kd_skpd,kd_sub_kegiatan,kd_rek6,isnull(SUM(a.nilai),0) as lalu FROM trdspd a LEFT JOIN trhspd b 
                        ON a.no_spd=b.no_spd 
                        WHERE left(b.kd_skpd,22)=left('$kode',22) and a.no_spd != '$cnomor' 
                        and b.tgl_spd<'$tgl1' 
                        GROUP BY kd_unit,kd_sub_kegiatan,kd_rek6 
                         ) c 
                         
                         ON a.kd_unit=c.kd_skpd and a.kd_sub_kegiatan=c.kd_sub_kegiatan and a.kd_rek6=c.kd_rek6
                         

                        )xxx";
        }

        // $sql = "SELECT SUM(nilai) as nilai FROM
        //         (SELECT a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, '' as kd_rek5 , '' as nm_rek5, 
        //          kd_skpd
        //         FROM trskpd a inner join ms_sub_kegiatan x ON a.kd_sub_kegiatan=x.kd_sub_kegiatan WHERE left(a.kd_skpd,17)=left('$skpd',17) and x.jns_sub_kegiatan ='5') a
        //         LEFT JOIN
        //         (SELECT kd_sub_kegiatan, kd_skpd, SUM($n_status) as nilai FROM $n_trdskpd b WHERE b.bulan>='$awal' AND b.bulan<='$ahir'
        //         AND left(kd_skpd,17)=left('$skpd',17)
        //         GROUP BY kd_sub_kegiatan, kd_skpd)b
        //         ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd";
        $query1 = $this->db->query($sql);
        $ii     = 0;

        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'nilai' => $resulte['nilai']
            );
            $ii++;
        }


        echo json_encode($result);
        $query1->free_result();
    }

    function load_tot_dspd_bl_lama($jenis = '', $skpd = '', $awal = '', $ahir = '', $nospd = '', $tgl1 = '')
    {
        $data = $this->cek_anggaran_model->cek_anggaran($skpd);
        $n_status = $this->get_status($tgl1, $skpd);
        if ($n_status == 'susun') {
            $nilai = 'nilai';
        } else {
            $nilai = 'nilai_$n_status';
        }
        $field = 'b.' . $n_status;
        $n_trdskpd = 'trdskpd';
        $spd = str_replace('123456789', '/', $nospd);
        $sql = "SELECT SUM(nilai) as nilai FROM
                    (SELECT a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, '' as kd_rek5 , '' as nm_rek5, 
                     kd_skpd
                    FROM trskpd a inner join ms_sub_kegiatan x ON a.kd_sub_kegiatan=x.kd_sub_kegiatan WHERE a.kd_skpd='$skpd' AND a.jns_ang='$data' and x.jns_sub_kegiatan ='5') a
                    LEFT JOIN
                    (SELECT kd_sub_kegiatan, kd_skpd, SUM($nilai) as nilai FROM $n_trdskpd b WHERE b.bulan>='$awal' AND b.bulan<='$ahir'
                    AND kd_skpd='$skpd'
                    GROUP BY kd_sub_kegiatan, kd_skpd)b
                    ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd";
        $query1 = $this->db->query($sql);
        $ii     = 0;

        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'nilai' => $resulte['nilai']
            );
            $ii++;
        }


        echo json_encode($result);
        $query1->free_result();
    }

    function hapus_spd()
    {
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdspd where no_spd='$nomor'";
        $asg = $this->db->query($sql);
        if ($asg) {
            $sql = "delete from trhspd where no_spd='$nomor'";
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

    function load_tot_dspd_pembiayaan($jenis = '', $skpd = '', $awal = '', $ahir = '', $nospd = '', $tgl1 = '')
    {
        $n_status = $this->get_status_angkas($skpd);
        $jns_ang = $this->cek_anggaran_model->cek_anggaran($skpd);
        $n_trdskpd = 'trdskpd_ro';
        $spd = str_replace('123456789', '/', $nospd);
        $sql = "SELECT SUM(nilai) as nilai FROM 
        (
        SELECT a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, '' as kd_rek6 , '' as nm_rek6, a.kd_skpd FROM trskpd a inner join ms_sub_kegiatan x ON a.kd_sub_kegiatan=x.kd_sub_kegiatan inner join trdrka c on c.kd_sub_kegiatan=a.kd_sub_kegiatan AND c.kd_skpd=a.kd_skpd AND c.jns_ang=a.jns_ang WHERE a.kd_skpd='$skpd' and LEFT(c.kd_rek6,2) ='62' and a.jns_ang='$jns_ang'
        ) a 
        LEFT JOIN 
        (SELECT kd_rek6, kd_sub_kegiatan, kd_skpd, SUM($n_status) as nilai FROM trdskpd_ro b WHERE b.bulan>='$awal' AND b.bulan<='$ahir' AND kd_skpd='$skpd' AND LEFT(b.kd_rek6,2) ='62' GROUP BY kd_rek6, kd_sub_kegiatan, kd_skpd)b
        ON a.kd_skpd=b.kd_skpd AND a.kd_sub_kegiatan=b.kd_sub_kegiatan AND LEFT(b.kd_rek6,2) = LEFT(b.kd_rek6,2)";
        // $sql = "SELECT SUM(nilai) as nilai FROM
        //             (SELECT a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, '' as kd_rek6 , '' as nm_rek6, 
        //              kd_skpd
        //             FROM trskpd a inner join ms_sub_kegiatan x ON a.kd_sub_kegiatan=x.kd_sub_kegiatan WHERE a.kd_skpd='$skpd' and x.jns_sub_kegiatan ='62' and a.jns_ang='$jns_ang') a
        //             LEFT JOIN
        //             (SELECT kd_sub_kegiatan, kd_skpd, SUM($n_status) as nilai FROM $n_trdskpd b WHERE b.bulan>='$awal' AND b.bulan<='$ahir'
        //             AND kd_skpd='$skpd'
        //             GROUP BY kd_sub_kegiatan, kd_skpd)b
        //             ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd";
        $query1 = $this->db->query($sql);
        $ii     = 0;

        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'nilai' => $resulte['nilai']
            );
            $ii++;
        }


        echo json_encode($result);
        $query1->free_result();
    }

    function load_dspd_bl($jenis = '', $skpd = '', $awal = '', $ahir = '', $nospd = '', $cbln1 = '')
    {
        $skpd = substr($skpd, 0, 22);
        $data = $this->cek_anggaran_model->cek_anggaran($skpd);
        $tgl = $this->input->post('tgl');
        $cbln1 = $this->input->post('cbln1');
        $cbln2 = $this->input->post('cbln2');
        $revisi_ke = $this->input->post('revisi_ke');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                        upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot FROM trskpd a inner join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd inner join ms_sub_kegiatan c on a.kd_sub_kegiatan=c.kd_sub_kegiatan AND a.jns_ang=b.jns_ang WHERE left(b.kd_skpd,22)='$skpd' and c.jns_sub_kegiatan='5' and b.jns_ang='$data'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $n_status = $this->get_status2($skpd);

        $sts_angkas = $this->get_status_angkas($skpd);
        // echo($sts_angkas);



        // echo 'status'.$n_status;

        $spd = str_replace('123456789', '/', $nospd);
        $field = 'b.' . $n_status;
        //$field='b.nilai_sempurna';
        $n_trdskpd = 'trdskpd_ro';
        $spd = str_replace('123456789', '/', $nospd);
        // $sql = "SELECT a.kd_skpd, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, '' as kd_rek5 , '' as nm_rek5, 
        //         a.total_ubah as anggaran, nilai,lalu FROM(
        //             SELECT b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, '' as kd_rek5 , '' as nm_rek5, 
        //             sum($field) as total_ubah, left(b.kd_skpd,22) kd_skpd
        //             FROM trskpd a join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd
        //             inner join ms_sub_kegiatan c on a.kd_sub_kegiatan=c.kd_sub_kegiatan
        //             WHERE left(b.kd_skpd,22)='$skpd1' and c.jns_sub_kegiatan='$jenis' and a.kd_sub_kegiatan NOT like '%0.00%' group by b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,left(b.kd_skpd,22)
        //         ) a LEFT JOIN (
        //             SELECT kd_sub_kegiatan, left(kd_skpd,22) kd_skpd, SUM($field_angkas) as nilai FROM $n_trdskpd b WHERE b.bulan>='$awal' AND b.bulan<='$ahir'
        //             AND left(kd_skpd,22)='$skpd1' GROUP BY kd_sub_kegiatan, left(kd_skpd,22)
        //         )b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND left(a.kd_skpd,22)=left(b.kd_skpd,22)  LEFT JOIN (
        //             SELECT kd_sub_kegiatan,SUM(a.nilai) as lalu FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
        //             WHERE left(b.kd_skpd,22)='$skpd1' and a.no_spd != '$spd' and b.tgl_spd<'$tgl'
        //             GROUP BY kd_sub_kegiatan
        //         ) c ON a.kd_sub_kegiatan=c.kd_sub_kegiatan
        //         WHERE a.kd_sub_kegiatan NOT IN (SELECT TOP $offset kd_sub_kegiatan FROM  trskpd a WHERE left(a.kd_skpd,22)='$skpd1' and a.kd_sub_kegiatan NOT like '%0.00%' ORDER BY a.kd_sub_kegiatan)
        //         ORDER BY a.kd_sub_kegiatan 
        //         ";

        if ($revisi_ke == 1 || $revisi_ke == '1') {
            $sql = "SELECT a.kd_unit, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, a.kd_rek6 , a.nm_rek6, a.total_ubah as anggaran, 
nilai as nilai,lalu FROM(

 SELECT a.kd_skpd as kd_unit, b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,b.kd_rek6 , b.nm_rek6 , 
    sum(b.nilai) as total_ubah, left(a.kd_skpd,22) kd_skpd FROM trskpd a 
 inner join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd AND b.jns_ang=a.jns_ang
 inner join ms_sub_kegiatan c on a.kd_sub_kegiatan=c.kd_sub_kegiatan 
 WHERE left(b.kd_skpd,22)='$skpd' and c.jns_sub_kegiatan='5' AND b.jns_ang='$data' 
group by left(a.kd_skpd,22), a.kd_skpd,a.kd_program, a.nm_program,b.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6
 
 ) a LEFT JOIN (

    SELECT kd_sub_kegiatan, b.kd_rek6, left(kd_skpd,22) kd_skpd, kd_skpd as kd_unit, SUM($sts_angkas) as nilai FROM trdskpd_ro b 
    WHERE (b.bulan between '$cbln1' AND'$cbln2') AND left(kd_skpd,22)='$skpd' 
    GROUP BY left(kd_skpd,22),kd_skpd,kd_sub_kegiatan,kd_rek6 
    
    )b ON a.kd_unit=b.kd_unit and a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_rek6=b.kd_rek6
    
LEFT JOIN (

 SELECT kd_unit as kd_skpd,kd_sub_kegiatan,kd_rek6,isnull(SUM(a.nilai),0) as lalu FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
WHERE left(b.kd_skpd,22)='$skpd' and a.no_spd != '$spd' and b.tgl_spd<'$tgl' 
GROUP BY kd_unit,kd_sub_kegiatan,kd_rek6 
 ) c 
 
 ON a.kd_unit=c.kd_skpd and a.kd_sub_kegiatan=c.kd_sub_kegiatan and a.kd_rek6=c.kd_rek6

 
WHERE a.kd_sub_kegiatan NOT IN (SELECT TOP 0 x.kd_sub_kegiatan FROM trskpd x inner join ms_sub_kegiatan y on x.kd_sub_kegiatan=y.kd_sub_kegiatan WHERE left(x.kd_skpd,22)='$skpd' and y.jns_sub_kegiatan in ('61','62','4')) 
ORDER BY a.kd_unit,a.kd_sub_kegiatan";
        } else {
            $sql = "SELECT TOP $rows a.kd_unit, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, a.kd_rek6 , a.nm_rek6, a.total_ubah as anggaran, 
nilai-isnull(lalu_tw,0) as nilai,lalu FROM(

 SELECT a.kd_skpd as kd_unit, b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,b.kd_rek6 , b.nm_rek6 , 
    sum(b.nilai) as total_ubah, left(a.kd_skpd,22) kd_skpd FROM trskpd a 
 inner join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd AND b.jns_ang=a.jns_ang 
 inner join ms_sub_kegiatan c on a.kd_sub_kegiatan=c.kd_sub_kegiatan 
 WHERE left(b.kd_skpd,22)='$skpd' and c.jns_sub_kegiatan='5' and b.jns_ang='$data'
group by left(a.kd_skpd,22), a.kd_skpd,a.kd_program, a.nm_program,b.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6
 
 ) a LEFT JOIN (

    SELECT kd_sub_kegiatan, b.kd_rek6, left(kd_skpd,22) kd_skpd, kd_skpd as kd_unit, SUM($sts_angkas) as nilai FROM trdskpd_ro b 
    WHERE b.bulan>='$cbln1' AND b.bulan<='$cbln2' AND left(kd_skpd,22)='$skpd' 
    GROUP BY left(kd_skpd,22),kd_skpd,kd_sub_kegiatan,kd_rek6 
    
    )b ON a.kd_unit=b.kd_unit and a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_rek6=b.kd_rek6
    
LEFT JOIN (

 SELECT kd_unit as kd_skpd,kd_sub_kegiatan,kd_rek6,isnull(SUM(a.nilai),0) as lalu FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
WHERE left(b.kd_skpd,22)='$skpd' and a.no_spd != '$spd' and b.tgl_spd<'$tgl' 
GROUP BY kd_unit,kd_sub_kegiatan,kd_rek6 
 ) c 
 
 ON a.kd_unit=c.kd_skpd and a.kd_sub_kegiatan=c.kd_sub_kegiatan and a.kd_rek6=c.kd_rek6

  LEFT JOIN (

 SELECT kd_unit as kd_skpd,kd_sub_kegiatan,kd_rek6,isnull(SUM(a.nilai),0) as lalu_tw FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
WHERE left(b.kd_skpd,22)='$skpd' and b.bulan_awal='$cbln1' AND b.bulan_akhir='$cbln2' and a.no_spd != '$spd' and b.tgl_spd<'$tgl' 
GROUP BY kd_unit,kd_sub_kegiatan,kd_rek6 
 ) d 
 
 ON a.kd_unit=d.kd_skpd and a.kd_sub_kegiatan=d.kd_sub_kegiatan and a.kd_rek6=d.kd_rek6


where a.kd_unit+a.kd_sub_kegiatan+a.kd_rek6 not in (select TOP $offset  

  b.kd_skpd+b.kd_sub_kegiatan+b.kd_rek6 FROM trskpd a 
 inner join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd AND a.jns_ang=b.jns_ang
 inner join ms_sub_kegiatan c on a.kd_sub_kegiatan=c.kd_sub_kegiatan 
 WHERE left(b.kd_skpd,22)='$skpd' and c.jns_sub_kegiatan='5' AND a.jns_ang='$data'
group by b.kd_skpd,b.kd_sub_kegiatan,b.kd_rek6


) 
ORDER BY a.kd_unit,a.kd_sub_kegiatan";
        }



        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id'                => $ii,
                'no_spd'            => '',
                'kd_unit'           => $resulte['kd_unit'],

                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan'   => $resulte['nm_sub_kegiatan'],
                'kd_program'        => $resulte['kd_program'],
                'nm_program'        => $resulte['nm_program'],
                'kd_rek6'           => $resulte['kd_rek6'],
                'nm_rek6'           => $resulte['nm_rek6'],
                'nilai'             => number_format($resulte['nilai'], "2", ".", ","),
                'lalu'              => number_format($resulte['lalu'], "2", ".", ","),
                'anggaran'          => number_format($resulte['anggaran'], "2", ".", ",")
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }

    function load_dspd_bl_lama($jenis = '', $skpd = '', $awal = '', $ahir = '', $nospd = '', $cbln1 = '')
    {
        $skpd1 = $skpd;
        $tgl = $this->input->post('tgl');
        $cbln1 = $this->input->post('cbln1');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                        upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot FROM trskpd a inner join ms_sub_kegiatan b on a.kd_sub_kegiatan=b.kd_sub_kegiatan WHERE left(a.kd_skpd,22)='$skpd1' and b.kd_sub_kegiatan NOT LIKE '%0.00%'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $n_status = $this->get_status2($skpd);

        // echo 'status'.$n_status;

        $spd = str_replace('123456789', '/', $nospd);
        $field = 'b.' . $n_status;
        //$field='b.nilai_sempurna';
        $n_trdskpd = 'trdskpd_ro';
        $spd = str_replace('123456789', '/', $nospd);
        // $sql = "SELECT  a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, a.kd_rek6 , a.nm_rek6,  
        //         a.total_ubah as anggaran, nilai,lalu FROM(
        //         SELECT b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, b.kd_rek6, b.nm_rek6, 
        //         sum($field) as total_ubah, left(b.kd_skpd,22) kd_skpd
        //         FROM trskpd a join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd
        //         WHERE left(b.kd_skpd,22)='$skpd1' and a.kd_sub_kegiatan NOT like '%0.00%' group by b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,left(b.kd_skpd,22),b.kd_rek6, b.nm_rek6
        //         ) a LEFT JOIN (
        //         SELECT kd_sub_kegiatan, left(kd_skpd,22) kd_skpd, kd_rek6, SUM($n_status) as nilai FROM $n_trdskpd b WHERE b.bulan>='$awal' AND b.bulan<='$ahir'
        //         AND left(kd_skpd,22)='$skpd1' GROUP BY kd_sub_kegiatan, left(kd_skpd,22), kd_rek6
        //         )b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND left(a.kd_skpd,22)=left(b.kd_skpd,22) and a.kd_rek6=b.kd_rek6 LEFT JOIN (
        //         SELECT kd_sub_kegiatan,kd_rek6,SUM(a.nilai) as lalu FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
        //         WHERE left(b.kd_skpd,22)='$skpd1' and a.no_spd != '$spd' and b.tgl_spd<'$tgl'
        //         GROUP BY kd_sub_kegiatan,kd_rek6
        //         ) c ON a.kd_sub_kegiatan=c.kd_sub_kegiatan and a.kd_rek6=c.kd_rek6
        //         ORDER BY a.kd_sub_kegiatan,a.kd_rek6
        //         ";



        $sql = "SELECT a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program,a.nm_program,a.kd_rek6 ,a.nm_rek6,a.murni,a.geser,( a.murni - a.geser ) AS selisih, nilai, lalu 
                    FROM (
                        SELECT b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, b.kd_rek6, b.nm_rek6, SUM ( b.nilai ) AS murni, SUM ( b.nilai_sempurna ) AS geser, b.kd_skpd 
                        FROM trskpd a JOIN trdrka b ON a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_skpd= b.kd_skpd WHERE b.kd_skpd= '$skpd' AND b.nilai_sempurna != 0 AND LEFT(b.kd_rek6,1) = '5'
                        GROUP BY b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, b.kd_skpd, b.kd_rek6, b.nm_rek6 ) a
                    LEFT JOIN (SELECT kd_sub_kegiatan, kd_skpd, kd_rek6, SUM ( nilai ) AS nilai FROM trdskpd_ro b WHERE b.bulan>= '$awal' AND b.bulan <= '$ahir' AND kd_skpd = '$skpd'
                    GROUP BY kd_sub_kegiatan, kd_skpd, kd_rek6) b ON a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_skpd = b.kd_skpd AND a.kd_rek6= b.kd_rek6 
                    LEFT JOIN ( SELECT kd_sub_kegiatan, kd_rek6, SUM ( a.nilai ) AS lalu FROM trdspd a LEFT JOIN trhspd b ON a.no_spd= b.no_spd WHERE b.kd_skpd = '$skpd' 
                    AND a.no_spd != '$spd' AND b.tgl_spd< '$tgl' GROUP BY kd_sub_kegiatan, kd_rek6 ) c ON a.kd_sub_kegiatan= c.kd_sub_kegiatan 
                    AND a.kd_rek6= c.kd_rek6 ORDER BY a.kd_sub_kegiatan, a.kd_rek6";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id'                  => $ii,
                'no_spd'              => '',
                'kd_sub_kegiatan'     => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan'     => $resulte['nm_sub_kegiatan'],
                'kd_program'          => $resulte['kd_program'],
                'nm_program'          => $resulte['nm_program'],
                'kd_rekening'         => $resulte['kd_rek6'],
                'nm_rekening'         => $resulte['nm_rek6'],
                'nilai'               => number_format($resulte['nilai'], "2", ".", ","),
                'lalu'                => number_format($resulte['lalu'], "2", ".", ","),
                'anggaran'            => number_format($resulte['murni'], "2", ".", ","),
                'ang_geser'          => number_format($resulte['geser'], "2", ".", ",")
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }



    function load_dspd_pembiayaan($jenis = '', $skpd = '', $awal = '', $ahir = '', $nospd = '', $cbln1 = '')
    {
        $skpd = $this->uri->segment(4);
        // echo($skpd);
        $tgl = $this->input->post('tgl');
        $cbln1 = $this->input->post('cbln1');
        $cbln2 = $this->input->post('cbln2');
        $data1 = $this->cek_anggaran_model->cek_anggaran($skpd);
        // echo($data1);
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot FROM trskpd a inner join ms_sub_kegiatan b on a.kd_sub_kegiatan=b.kd_sub_kegiatan WHERE a.jns_ang='$data1' AND a.kd_skpd='$skpd' and a.jns_kegiatan ='5'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $n_status = $this->get_status($tgl, $skpd);
        $sts_angkas = $this->get_status_angkas($skpd);

        if ($sts_angkas == 'nilai_murni') {
            $field_angkas = 'nilai';
        } else if ($sts_angkas == 'nilai_susun') {
            $field_angkas = 'nilai_susun';
        } else if ($sts_angkas == 'murni_geser1') {
            $field_angkas = 'nilai_susun1';
        } else if ($sts_angkas == 'murni_geser2') {
            $field_angkas = 'nilai_susun2';
        } else if ($sts_angkas == 'murni_geser3') {
            $field_angkas = 'nilai_susun3';
        } else if ($sts_angkas == 'murni_geser4') {
            $field_angkas = 'nilai_susun4';
        } else if ($sts_angkas == 'murni_geser5') {
            $field_angkas = 'nilai_susun5';
        } else if ($sts_angkas == 'nilai_sempurna1') {
            $field_angkas = 'nilai_sempurna';
        } else if ($sts_angkas == 'sempurna1_geser1') {
            $field_angkas = 'nilai_sempurna11';
        } else if ($sts_angkas == 'sempurna1_geser2') {
            $field_angkas = 'nilai_sempurna12';
        } else if ($sts_angkas == 'sempurna1_geser3') {
            $field_angkas = 'nilai_sempurna13';
        } else if ($sts_angkas == 'sempurna1_geser4') {
            $field_angkas = 'nilai_sempurna14';
        } else if ($sts_angkas == 'sempurna1_geser5') {
            $field_angkas = 'nilai_sempurna15';
        } else if ($sts_angkas == 'sempurna2') {
            $field_angkas = 'nilai_sempurna2';
        } else if ($sts_angkas == 'sempurna2_geser1') {
            $field_angkas = 'nilai_sempurna21';
        } else if ($sts_angkas == 'sempurna2_geser2') {
            $field_angkas = 'nilai_sempurna22';
        } else if ($sts_angkas == 'sempurna2_geser3') {
            $field_angkas = 'nilai_sempurna23';
        } else if ($sts_angkas == 'sempurna2_geser4') {
            $field_angkas = 'nilai_sempurna24';
        } else if ($sts_angkas == 'sempurna2_geser5') {
            $field_angkas = 'nilai_sempurna25';
        } else if ($sts_angkas == 'sempurna3') {
            $field_angkas = 'nilai_sempurna3';
        } else if ($sts_angkas == 'sempurna3_geser1') {
            $field_angkas = 'nilai_sempurna31';
        } else if ($sts_angkas == 'sempurna3_geser2') {
            $field_angkas = 'nilai_sempurna32';
        } else if ($sts_angkas == 'sempurna3_geser3') {
            $field_angkas = 'nilai_sempurna33';
        } else if ($sts_angkas == 'sempurna3_geser4') {
            $field_angkas = 'nilai_sempurna34';
        } else if ($sts_angkas == 'sempurna3_geser5') {
            $field_angkas = 'nilai_sempurna35';
        } else if ($sts_angkas == 'sempurna4') {
            $field_angkas = 'nilai_sempurna4';
        } else if ($sts_angkas == 'sempurna4_geser1') {
            $field_angkas = 'nilai_sempurna41';
        } else if ($sts_angkas == 'sempurna4_geser2') {
            $field_angkas = 'nilai_sempurna42';
        } else if ($sts_angkas == 'sempurna4_geser3') {
            $field_angkas = 'nilai_sempurna43';
        } else if ($sts_angkas == 'sempurna4_geser4') {
            $field_angkas = 'nilai_sempurna44';
        } else if ($sts_angkas == 'sempurna4_geser5') {
            $field_angkas = 'nilai_sempurna45';
        } else if ($sts_angkas == 'sempurna5') {
            $field_angkas = 'nilai_sempurna5';
        } else if ($sts_angkas == 'sempurna5_geser1') {
            $field_angkas = 'nilai_sempurna51';
        } else if ($sts_angkas == 'sempurna5_geser2') {
            $field_angkas = 'nilai_sempurna52';
        } else if ($sts_angkas == 'sempurna5_geser3') {
            $field_angkas = 'nilai_sempurna53';
        } else if ($sts_angkas == 'sempurna5_geser4') {
            $field_angkas = 'nilai_sempurna1';
        } else if ($sts_angkas == 'sempurna5_geser5') {
            $field_angkas = 'nilai_sempurna55';
        } else if ($sts_angkas == 'nilai_ubah') {
            $field_angkas = 'nilai_ubah';
        } else if ($sts_angkas == 'nilai_ubah1') {
            $field_angkas = 'nilai_ubah1';
        } else if ($sts_angkas == 'nilai_ubah2') {
            $field_angkas = 'nilai_ubah2';
        } else if ($sts_angkas == 'nilai_ubah3') {
            $field_angkas = 'nilai_ubah3';
        } else if ($sts_angkas == 'nilai_ubah4') {
            $field_angkas = 'nilai_ubah4';
        } else {
            $field_angkas = 'nilai_ubah5';
        }

        $spd = str_replace('123456789', '/', $nospd);
        $field = 'b.' . $n_status;
        //$field='b.nilai_sempurna';
        $n_trdskpd = 'trdskpd_ro';
        $spd = str_replace('123456789', '/', $nospd);
        $sql = "SELECT TOP $rows a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, a.kd_rek6 , a.nm_rek6, 
                a.total_ubah as anggaran, (nilai-isnull(lalu_tw,0))as nilai,lalu FROM(
                    SELECT b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, b.kd_rek6 , b.nm_rek6, 
                    sum(nilai) as total_ubah, b.kd_skpd
                    FROM trskpd a inner join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd AND a.jns_ang=b.jns_ang
                    inner join ms_sub_kegiatan c on b.kd_sub_kegiatan=c.kd_sub_kegiatan AND a.jns_ang=b.jns_ang
                    WHERE a.jns_ang='$data1' AND b.kd_skpd='$skpd' and LEFT(b.kd_rek6,2) ='62' group by b.kd_rek6,b.nm_rek6,b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,b.kd_skpd
                ) a LEFT JOIN (
                    SELECT kd_rek6, kd_sub_kegiatan, kd_skpd, SUM($field_angkas) as nilai FROM $n_trdskpd b WHERE b.bulan>='$awal' AND b.bulan<='$ahir'
                    AND kd_skpd='$skpd' GROUP BY kd_rek6,kd_sub_kegiatan, kd_skpd
                )b ON a.kd_rek6=b.kd_rek6 and a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd LEFT JOIN (
                    SELECT a.kd_rek6,kd_sub_kegiatan,SUM(a.nilai) as lalu FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                    WHERE b.kd_skpd='$skpd' and a.no_spd != '$spd' and b.tgl_spd<'$tgl'
                    GROUP BY a.kd_rek6,kd_sub_kegiatan
                ) c ON a.kd_rek6=c.kd_rek6 and a.kd_sub_kegiatan=c.kd_sub_kegiatan
                
                LEFT JOIN (

                 SELECT kd_unit as kd_skpd,kd_sub_kegiatan,kd_rek6,isnull(SUM(a.nilai),0) as lalu_tw FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                WHERE b.kd_skpd='$skpd' and b.bulan_awal='$cbln1' AND b.bulan_akhir='$cbln2' and a.no_spd != '$spd' and b.tgl_spd<'$tgl' 
                GROUP BY kd_unit,kd_sub_kegiatan,kd_rek6 
                 ) d ON a.kd_rek6=d.kd_rek6 and a.kd_sub_kegiatan=d.kd_sub_kegiatan

                WHERE a.kd_sub_kegiatan NOT IN (SELECT TOP $offset a.kd_sub_kegiatan FROM  trskpd a inner join ms_sub_kegiatan b on a.kd_sub_kegiatan=b.kd_sub_kegiatan WHERE a.jns_ang='$data1' AND a.kd_skpd='$skpd' and b.jns_sub_kegiatan ='62' ORDER BY a.kd_sub_kegiatan)
                ORDER BY a.kd_sub_kegiatan 
                ";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id'                => $ii,
                'no_spd'            => '',
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan'   => $resulte['nm_sub_kegiatan'],
                'kd_program'        => $resulte['kd_program'],
                'nm_program'        => $resulte['nm_program'],
                'kd_rek6'           => $resulte['kd_rek6'],
                'nm_rek6'           => $resulte['nm_rek6'],
                'nilai'             => number_format($resulte['nilai'], "2", ".", ","),
                'lalu'              => number_format($resulte['lalu'], "2", ".", ","),
                'anggaran'          => number_format($resulte['anggaran'], "2", ".", ",")
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }

    function load_dspd_ag_bl()
    {
        $no = $this->input->post('no');
        $jenis = $this->input->post('jenis');
        $skpd = $this->input->post('skpd');
        $kode = $this->input->post('skpd');
        $data = $this->cek_anggaran_model->cek_anggaran($kode);
        $skpd1 = substr($skpd, 0, 17);
        $tgl = $this->input->post('tgl');
        $cbln1 = $this->input->post('cbln1');
        //$stsubah=$this->rka_model->get_nama($skpd,'status_ubah','trhrka','kd_skpd');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                        upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from trdspd WHERE no_spd='$no'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $n_status = $this->get_status($tgl, $skpd);
        $field = '$n_status';

        if ($jenis == '62') {

            //$field='nilai_sempurna';
            $sql = "SELECT a.*,kd_rek6,nm_rek6 ,isnull((SELECT SUM(n.nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd 
                    WHERE n.kd_sub_kegiatan=a.kd_sub_kegiatan  and kd_rek6=a.kd_Rek6 and m.no_spd <> '$no' and m.tgl_spd<'$tgl'),0) AS lalu,
                    (SELECT SUM(nilai) FROM trdrka WHERE kd_kegiatan = a.kd_kegiatan AND kd_skpd=b.kd_skpd and kd_rek6=a.kd_Rek6 and jns_ang='$data') AS anggaran from trdspd a
                    inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' order by b.no_spd,a.kd_kegiatan,a.kd_rek5";
        } else {
            //$field='nilai_sempurna';
            $sql = "SELECT TOP $rows a.*,(SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_sub_kegiatan=a.kd_sub_kegiatan and a.kd_rek6=n.kd_rek6 AND m.no_spd <> '$no' and m.tgl_spd<='$tgl') AS lalu,
                    (select sum(nilai) from trdrka where kd_sub_kegiatan = a.kd_sub_kegiatan and a.kd_rek6=kd_rek6 AND kd_skpd=b.kd_skpd and jns_ang='$data') AS anggaran 
                    from trdspd a inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' AND left(b.kd_skpd,22)='$skpd' 
                    AND a.kd_sub_kegiatan NOT IN (SELECT TOP $offset kd_sub_kegiatan FROM trdspd where no_spd = '$no' order by kd_sub_kegiatan)order by b.no_spd,
                    a.kd_unit,a.kd_sub_kegiatan";
        }
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id' => $ii,
                'kd_unit'           => $resulte['kd_unit'],
                'nm_unit'           => $this->tukd_model->get_nama($resulte['kd_unit'], 'nm_skpd', 'ms_skpd', 'kd_skpd'),
                'no_spd'            => $resulte['no_spd'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_program'  => $resulte['kd_program'],
                'nm_program'  => $resulte['nm_program'],
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6'],
                'nilai'       => number_format($resulte['nilai'], "2", ".", ","),
                'lalu'        => number_format($resulte['lalu'], "2", ".", ","),
                'anggaran'    => number_format($resulte['anggaran'], "2", ".", ",")
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }

    function load_dspd_ag_bl_lama()
    {
        $no = $this->input->post('no');
        $jenis = $this->input->post('jenis');
        $skpd = $this->input->post('skpd');
        $kode = $this->input->post('skpd');
        $data = $this->cek_anggaran_model->cek_anggaran($kode);
        $skpd1 = substr($skpd, 0, 17);
        $tgl = $this->input->post('tgl');
        $cbln1 = $this->input->post('cbln1');
        //$stsubah=$this->rka_model->get_nama($skpd,'status_ubah','trhrka','kd_skpd');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                        upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from trdspd WHERE no_spd='$no'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $n_status = $this->get_status2($skpd);

        $field = '$n_status';

        if ($jenis == '62') {
            echo $jenis;
            //$field='nilai_sempurna';
            $sql = "SELECT a.*,kd_rek5,nm_rek5 ,isnull((SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd 
                    WHERE n.kd_kegiatan=a.kd_kegiatan  and kd_rek5=a.kd_Rek5 and m.no_spd <> '$no' and m.tgl_spd<'$tgl'),0) AS lalu,
                    (SELECT SUM(nilai) FROM trdrka WHERE kd_rek6 = a.kd_rek6 AND kd_skpd=b.kd_skpd and kd_rek5=a.kd_Rek5 and jns_ang='$data') AS anggaran from trdspd a
                    inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' order by b.no_spd,a.kd_kegiatan,a.kd_rek5";
        } else {

            $sql = "SELECT a.*,kd_rek6,nm_rek6 ,(SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_sub_kegiatan=a.kd_sub_kegiatan  AND m.no_spd <> '$no' and m.tgl_spd<'$tgl') AS lalu,
                    (select sum(nilai) from trdrka where jns_ang='$data' and kd_rek6 = a.kd_rek6 AND kd_skpd=b.kd_skpd AND kd_sub_kegiatan = a.kd_sub_kegiatan) AS anggaran, (select sum(nilai_sempurna) from trdrka where kd_rek6 = a.kd_rek6 AND kd_skpd=b.kd_skpd AND kd_sub_kegiatan = a.kd_sub_kegiatan) AS anggaran_geser, (select sum(nilai_ubah) from trdrka where kd_rek6 = a.kd_rek6 AND kd_skpd=b.kd_skpd AND kd_sub_kegiatan = a.kd_sub_kegiatan) AS anggaran_ubah from trdspd a inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' AND left(b.kd_skpd,22)='$skpd' 
                    AND a.kd_sub_kegiatan NOT IN (SELECT TOP $offset kd_sub_kegiatan FROM trdspd where no_spd = '$no' order by kd_sub_kegiatan)order by b.no_spd,a.kd_sub_kegiatan,a.kd_rek6";
        }

        // print_r($sql);die();

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id' => $ii,
                'no_spd' => $resulte['no_spd'],
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_program'  => $resulte['kd_program'],
                'nm_program'  => $resulte['nm_program'],
                'kd_rekening' => $resulte['kd_rek6'],
                'nm_rekening' => $resulte['nm_rek6'],
                'nilai'       => number_format($resulte['nilai'], "2", ".", ","),
                'lalu'        => number_format($resulte['lalu'], "2", ".", ","),
                'anggaran'    => number_format($resulte['anggaran'], "2", ".", ","),
                'anggaran_geser'    => number_format($resulte['anggaran_geser'], "2", ".", ","),
                'anggaran_ubah'    => number_format($resulte['anggaran_ubah'], "2", ".", ","),
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }


    function load_dspd_ag_pembiayaan()
    {
        $no = $this->input->post('no');
        $jenis = $this->input->post('jenis');
        $skpd = $this->input->post('skpd');
        $data = $this->cek_anggaran_model->cek_anggaran($skpd);
        $tgl = $this->input->post('tgl');
        $cbln1 = $this->input->post('cbln1');
        //$stsubah=$this->rka_model->get_nama($skpd,'status_ubah','trhrka','kd_skpd');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                        upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from trdspd WHERE no_spd='$no'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $n_status = $this->get_status($tgl, $skpd);
        $field = '$n_status';

        if ($jenis == '61') {
            echo $jenis;
            //$field='nilai_sempurna';
            $sql = "SELECT a.*,kd_rek6,nm_rek6 ,isnull((SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd 
                    WHERE n.kd_kegiatan=a.kd_kegiatan  and kd_rek5=a.kd_Rek5 and m.no_spd <> '$no' and m.tgl_spd<'$tgl'),0) AS lalu,
                    (SELECT SUM(nilai) FROM trdrka WHERE kd_kegiatan = a.kd_kegiatan AND kd_skpd=b.kd_skpd and kd_rek6=a.kd_rek6 and jns_ang='$data' ) AS anggaran from trdspd a
                    inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' order by b.no_spd,a.kd_kegiatan,a.kd_rek5";
        } else {
            //$field='nilai_sempurna';
            $sql = "SELECT a.*,kd_rek6,nm_rek6 ,(SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_sub_kegiatan=a.kd_sub_kegiatan  AND m.no_spd <> '$no' and m.tgl_spd<'$tgl') AS lalu,
                    (select sum(nilai) from trdrka where jns_ang='$data' and kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_skpd=b.kd_skpd) AS anggaran from trdspd a inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' AND b.kd_skpd='$skpd' 
                    AND a.kd_sub_kegiatan NOT IN (SELECT TOP $offset kd_sub_kegiatan FROM trdspd where no_spd = '$no' order by kd_sub_kegiatan)order by b.no_spd,a.kd_sub_kegiatan,a.kd_rek6";
        }
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id' => $ii,
                'no_spd' => $resulte['no_spd'],
                'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan'   => $resulte['nm_sub_kegiatan'],
                'kd_rek6'           => $resulte['kd_rek6'],
                'nm_rek6'           => $resulte['nm_rek6'],
                'kd_program'        => $resulte['kd_program'],
                'nm_program'        => $resulte['nm_program'],
                'kd_rekening'       => $resulte['kd_rek6'],
                'nm_rekening'       => $resulte['nm_rek6'],
                'nilai'             => number_format($resulte['nilai'], "2", ".", ","),
                'lalu'              => number_format($resulte['lalu'], "2", ".", ","),
                'anggaran'          => number_format($resulte['anggaran'], "2", ".", ",")
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }

    function load_dspd_all_keg($jenis = '', $skpd = '', $awal = 0, $ahir = 12, $nospd = '')
    {

        $kode = $skpd;
        $data1 = $this->cek_anggaran_model->cek_anggaran($kode);

        $stsubah = $this->rka_model->get_nama($skpd, 'status_ubah', 'trhrka', 'kd_skpd');

        if ($stsubah == 0) {
            $field = "b.nilai";
            $sts = "susun";
        } else {
            $field = "b.nilai_ubah";
            $sts = "ubah";
        }

        if ($jenis == '5') {
            $dan = "k.jns_sub_kegiatan ='$jenis'";

            $sql = " SELECT a.*,('')kd_rek6,('')nm_rek6,(SELECT SUM($field) FROM trdskpd b WHERE a.kd_sub_kegiatan=b.kd_sub_kegiatan AND b.bulan>=$awal AND b.bulan<=$ahir and b.status='$sts' ) AS nilai FROM trskpd a inner join ms_sub_kegiatan k on a.kd_sub_kegiatan=k.kd_sub_kegiatan WHERE a.kd_skpd='$skpd' and $dan";
        } else {
            $dan = "b.jns_sub_kegiatan ='$jenis'";

            $sql = " SELECT b.kd_sub_kegiatan,f.nm_sub_kegiatan,f.kd_program,c.nm_program,b.kd_rek6,d.nm_rek6,$field from trdrka b inner join ms_sub_kegiatan f
     on b.kd_sub_kegiatan=f.kd_sub_kegiatan inner join ms_program c on 
    f.kd_program=c.kd_program inner join ms_rek6 d on b.kd_rek6=d.kd_rek6 where a,jns_ang='$data1' AND b.kd_sub_kegiatan in 
    (select kd_sub_kegiatan FROM trskpd a inner join ms_sub_kegiatan g on a.kd_sub_skpd=g.kd_sub_kegiatan WHERE a.kd_skpd='$skpd' and jns_ang='$data1' and $dan) order by b.kd_rek6";
        }

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            if ($stsubah == 0) {
                $angg = "nilai";
            } else {
                $angg = "nilai_ubah";
            }
            if ($jenis == '5') {
                $n_nilai = $resulte['nilai'];
                if ($stsubah == 0) {
                    $angg = "total";
                } else {
                    $angg = "total_ubah";
                }
            } else {
                $n_nilai = 0;
            }

            $giat = $resulte['kd_sub_kegiatan'];
            $rek = $resulte['kd_rek6'];
            $q_rek = "a.kd_rek6 ='$rek'";
            $s = " SELECT SUM(a.nilai) as nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd WHERE b.kd_skpd='$skpd' AND a.kd_sub_kegiatan='$giat' and $q_rek and a.no_spd<>'$nospd' ";
            $q = $this->db->query($s);
            foreach ($q->result_array() as $res) {
                $lalu = $res['nilai'];
            }


            $result[] = array(
                'id'          => $ii,
                'no_spd'      => '',
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_program'  => $resulte['kd_program'],
                'nm_program'  => $resulte['nm_program'],
                'kd_rekening' => $resulte['kd_rek6'],
                'nm_rekening' => $resulte['nm_rek6'],
                'nilai'       => number_format($n_nilai, "2", ".", ","),
                'lalu'        => number_format($lalu, "2", ".", ","),
                'anggaran'    => number_format($resulte[$angg], "2", ".", ",")
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_dspd_all_keg_lama($jenis = '', $skpd = '', $awal = 0, $ahir = 12, $nospd = '')
    {
        $data  = $this->cek_anggaran_model->cek_anggaran($skpd);

        $stsubah = $this->rka_model->get_nama($skpd, 'status_ubah', 'trhrka', 'kd_skpd');


        if ($stsubah == 0) {
            $field = "b.nilai";
            $sts = "susun";
        } else {
            $field = "b.nilai_ubah";
            $sts = "ubah";
        }

        if ($jenis == '5') {
            $dan = "a.jns_kegiatan ='$jenis'";

            $sql = " SELECT a.*,('')kd_rek6,('')nm_rek6,(SELECT SUM($field) FROM trdskpd b WHERE a.kd_sub_kegiatan=b.kd_sub_kegiatan AND b.bulan>=$awal AND b.bulan<=$ahir and b.status='$sts' ) AS nilai FROM trskpd a WHERE a.kd_skpd='$skpd' and $dan";
        } else {
            $dan = "a.jns_kegiatan ='$jenis'";

            $sql = " SELECT b.kd_sub_kegiatan,f.nm_sub_kegiatan,f.kd_program,c.nm_program,b.kd_rek6,d.nm_rek6,nilai from trdrka b inner join ms_sub_kegiatan f
     on b.kd_sub_kegiatan=f.kd_sub_kegiatan inner join ms_program c on 
    f.kd_program=c.kd_program inner join ms_rek6 d on b.kd_rek6=d.kd_rek6 where jns_ang='$data' and b.kd_sub_kegiatan in 
    (select kd_sub_kegiatan FROM trskpd a WHERE a.kd_skpd='$skpd' and $dan) order by b.kd_rek6";
        }

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            if ($stsubah == 0) {
                $angg = "nilai";
            } else {
                $angg = "nilai_ubah";
            }
            if ($jenis == '5') {
                $n_nilai = $resulte['nilai'];
                if ($stsubah == 0) {
                    $angg = "total";
                } else {
                    $angg = "total_ubah";
                }
            } else {
                $n_nilai = 0;
            }

            $giat = $resulte['kd_sub_kegiatan'];
            $rek = $resulte['kd_rek6'];
            $q_rek = "a.kd_rek6 ='$rek'";
            $s = " SELECT SUM(a.nilai) as nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd WHERE b.kd_skpd='$skpd' AND a.kd_sub_kegiatan='$giat' and $q_rek and a.no_spd<>'$nospd' ";
            $q = $this->db->query($s);
            foreach ($q->result_array() as $res) {
                $lalu = $res['nilai'];
            }


            $result[] = array(
                'id'          => $ii,
                'no_spd'      => '',
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_program'  => $resulte['kd_program'],
                'nm_program'  => $resulte['nm_program'],
                'kd_rekening' => $resulte['kd_rek6'],
                'nm_rekening' => $resulte['nm_rek6'],
                'nilai'       => number_format($n_nilai, "2", ".", ","),
                'lalu'        => number_format($lalu, "2", ".", ","),
                'anggaran'    => number_format($resulte[$angg], "2", ".", ",")
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }



    function load_dspd_all_keg_pembiayaan($jenis = '', $skpd = '', $awal = 0, $ahir = 12, $nospd = '')
    {
        $stsubah = $this->rka_model->get_nama($skpd, 'status_ubah', 'trhrka', 'kd_skpd');
        if ($stsubah == 0) {
            $field = "b.nilai";
            $sts = "susun";
        } else {
            $field = "b.nilai_ubah";
            $sts = "ubah";
        }

        if ($jenis == '62') {
            $dan = "a.jns_kegiatan ='$jenis'";

            $sql = " SELECT a.*,('')kd_rek6,('')nm_rek6,(SELECT SUM($field) FROM trdskpd b WHERE a.kd_sub_kegiatan=b.kd_sub_kegiatan AND b.bulan>=$awal AND b.bulan<=$ahir and b.status='$sts' ) AS nilai FROM trskpd a WHERE a.kd_skpd='$skpd' and $dan";
        } else {
            $dan = "a.jns_kegiatan ='$jenis'";

            $sql = " select b.kd_sub_kegiatan,f.nm_sub_kegiatan,f.kd_program,c.nm_program,b.kd_rek6,d.nm_rek6,$field from trdrka b inner join ms_sub_kegiatan f
     on b.kd_sub_kegiatan=f.kd_sub_kegiatan inner join ms_program c on 
    f.kd_program=c.kd_program inner join ms_rek6 d on b.kd_rek6=d.kd_rek6 where b.kd_sub_kegiatan in 
    (select kd_sub_kegiatan FROM trskpd a WHERE a.kd_skpd='$skpd' and $dan) order by b.kd_rek6";
        }

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            if ($stsubah == 0) {
                $angg = "nilai";
            } else {
                $angg = "nilai_ubah";
            }
            if ($jenis == '62') {
                $n_nilai = $resulte['nilai'];
                if ($stsubah == 0) {
                    $angg = "total";
                } else {
                    $angg = "total_ubah";
                }
            } else {
                $n_nilai = 0;
            }

            $giat = $resulte['kd_sub_kegiatan'];
            $rek = $resulte['kd_rek6'];
            $q_rek = "a.kd_rek6 ='$rek'";
            $s = " SELECT SUM(a.nilai) as nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd WHERE b.kd_skpd='$skpd' AND a.kd_sub_kegiatan='$giat' and $q_rek and a.no_spd<>'$nospd' ";
            $q = $this->db->query($s);
            foreach ($q->result_array() as $res) {
                $lalu = $res['nilai'];
            }


            $result[] = array(
                'id'          => $ii,
                'no_spd'      => '',
                'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_program'  => $resulte['kd_program'],
                'nm_program'  => $resulte['nm_program'],
                'kd_rekening' => $resulte['kd_rek6'],
                'nm_rekening' => $resulte['nm_rek6'],
                'nilai'       => number_format($n_nilai, "2", ".", ","),
                'lalu'        => number_format($lalu, "2", ".", ","),
                'anggaran'    => number_format($resulte[$angg], "2", ".", ",")
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }




    function get_realisasi_keg_spd($cskpd = '')
    {
        $kdskpd         = $this->input->post('skpd');
        $subkegiatan     = $this->input->post('keg');
        $cbln2             =  $this->input->post('cbln2');
        $query1         = $this->db->query("SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
                                        (
                                            select c.kd_sub_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                            where c.kd_sub_kegiatan='$subkegiatan' and c.kd_skpd='$kdskpd' and b.jns_spp not in ('1','2') and MONTH(b.tgl_spp)<'$cbln2' 
                                            and (sp2d_batal<>'1' or sp2d_batal is null ) 
                                            group by c.kd_sub_kegiatan
                                        ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan
                                        left join 
                                        (
                                            select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                            from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                            where f.kd_sub_kegiatan='$subkegiatan' and MONTH(e.tgl_bukti)<'$cbln2' and e.jns_spp ='1' group by f.kd_sub_kegiatan
                                        ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan
                                        left join 
                                        (
                                            SELECT t.kd_sub_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                            INNER JOIN trhtagih u 
                                            ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                            WHERE t.kd_sub_kegiatan = '$subkegiatan'  
                                            AND u.kd_skpd='$kdskpd'
                                            AND MONTH(u.tgl_bukti)<'$cbln2'
                                            AND u.no_bukti 
                                            NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd' )
                                            GROUP BY t.kd_sub_kegiatan
                                        ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan
                                        where a.kd_sub_kegiatan='$subkegiatan'");

        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'nrealisasi' => number_format($resulte['total'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function get_realisasi_keg_spd_pembiayaan($cskpd = '')
    {
        $kdskpd         = $this->input->post('skpd');
        $subkegiatan    = $this->input->post('keg');
        $cbln2          =  $this->input->post('cbln2');
        $query1         = $this->db->query("SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
                                        (
                                            select c.kd_sub_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                            where c.kd_sub_kegiatan='$subkegiatan' and b.jns_spp not in ('1','2') and MONTH(b.tgl_spp)<'$cbln2' 
                                            and (sp2d_batal<>'1' or sp2d_batal is null ) 
                                            group by c.kd_sub_kegiatan
                                        ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan
                                        left join 
                                        (
                                            select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                            from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                            where f.kd_sub_kegiatan='$subkegiatan' and MONTH(e.tgl_bukti)<'$cbln2' and e.jns_spp ='1' group by f.kd_sub_kegiatan
                                        ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan
                                        left join 
                                        (
                                            SELECT t.kd_sub_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                            INNER JOIN trhtagih u 
                                            ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                            WHERE t.kd_sub_kegiatan = '$subkegiatan'  
                                            AND u.kd_skpd='$kdskpd'
                                            AND MONTH(u.tgl_bukti)<'$cbln2'
                                            AND u.no_bukti 
                                            NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd' )
                                            GROUP BY t.kd_sub_kegiatan
                                        ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan
                                        where a.kd_sub_kegiatan='$subkegiatan'");

        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'nrealisasi' => number_format($resulte['total'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function get_anggkas_keg($cskpd = '')
    {
        $skpd = $this->input->post('skpd');
        $n_status = $this->get_status2($skpd);
        $kegiatan = $this->input->post('keg');
        $cbln1 =  $this->input->post('cbln1');
        $cbln2 =  $this->input->post('cbln2');
        //$tgl = $this->input->post('tgl');

        if ($n_status == 'susun') {
            $nilai = 'nilai';
        } else {
            $nilai = 'nilai_$n_status';
        }

        $query1 = $this->db->query("select isnull(sum($nilai),0) [total] from trdskpd where bulan>='$cbln1' and bulan<='$cbln2' and kd_skpd='$skpd' and kd_sub_kegiatan='$kegiatan' ");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'vanggkas' => number_format($resulte['total'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }


    function get_anggkas_keg_pembiayaan($cskpd = '')
    {
        $kdskpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');
        $cbln1 =  $this->input->post('cbln1');
        $cbln2 =  $this->input->post('cbln2');
        //$tgl = $this->input->post('tgl');
        $n_status = $this->get_status2($kdskpd);
        if ($n_status == 'susun') {
            $nilai = 'nilai';
        } else {
            $nilai = 'nilai_$n_status';
        }
        $query1 = $this->db->query("select isnull(sum($nilai),0) [total] from trdskpd where bulan>='$cbln1' and bulan<='$cbln2' and kd_skpd='$kdskpd' and kd_sub_kegiatan='$kegiatan' ");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'vanggkas' => number_format($resulte['total'], 2, '.', ',')
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function cek_status_angkas()
    {
        $skpd = $this->input->post('kd_skpd');
        $sql = "SELECT TOP 1 * from (
        select '1'as urut,'murni' as ket, 'nilai_susun' as status,murni as nilai from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '2'as urut,'murni geser 1' as ket, 'murni_susun1',murni_geser1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '3'as urut,'murni geser 2' as ket, 'murni_susun2',murni_geser2 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '4'as urut,'murni geser 3' as ket, 'murni_susun3',murni_geser3 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '5'as urut,'murni geser 4' as ket, 'murni_susun4',murni_geser4 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '6'as urut,'murni geser 5' as ket, 'murni_susun5',murni_geser5 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '7'as urut,'penyempurna 1' as ket, 'nilai_sempurna',sempurna1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '8'as urut,'penyempurna 1 geser 1' as ket, 'nilai_sempurna11',sempurna1_geser1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '9'as urut,'penyempurna 1 geser 2' as ket, 'nilai_sempurna12',sempurna1_geser2 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '10'as urut,'penyempurna 1 geser 3' as ket, 'nilai_sempurna13',sempurna1_geser3 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '11'as urut,'penyempurna 1 geser 4' as ket, 'nilai_sempurna14',sempurna1_geser4 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '12'as urut,'penyempurna 1 geser 5' as ket, 'nilai_sempurna15',sempurna1_geser5 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '13'as urut,'penyempurna 2' as ket, 'nilai_sempurna2',sempurna2 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '14'as urut,'penyempurna 2 geser 1' as ket, 'nilai_sempurna21',sempurna2_geser1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '15'as urut,'penyempurna 2 geser 2' as ket, 'nilai_sempurna22',sempurna2_geser2 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '16'as urut,'penyempurna 2 geser 3' as ket, 'nilai_sempurna23',sempurna2_geser3 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '17'as urut,'penyempurna 2 geser 4' as ket, 'nilai_sempurna24',sempurna2_geser4 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '18'as urut,'penyempurna 2 geser 5' as ket, 'nilai_sempurna25',sempurna2_geser5 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '19'as urut,'penyempurna 3' as ket, 'nilai_sempurna3',sempurna3 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '20'as urut,'penyempurna 3 geser 1' as ket, 'nilai_sempurna31',sempurna3_geser1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '21'as urut,'penyempurna 3 geser 2' as ket, 'nilai_sempurna32',sempurna3_geser2 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '22'as urut,'penyempurna 3 geser 3' as ket, 'nilai_sempurna33',sempurna3_geser3 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '23'as urut,'penyempurna 3 geser 4' as ket, 'nilai_sempurna34',sempurna3_geser4 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '24'as urut,'penyempurna 3 geser 5' as ket, 'nilai_sempurna35',sempurna3_geser5 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '25'as urut,'penyempurnaan 4' as ket, 'nilai_sempurna4',sempurna4 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '26'as urut,'penyempurnaan 4 geser 1' as ket, 'nilai_sempurna41',sempurna4_geser1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '27'as urut,'penyempurnaan 4 geser 2' as ket, 'nilai_sempurna42',sempurna4_geser2 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '28'as urut,'penyempurnaan 4 geser 3' as ket, 'nilai_sempurna43',sempurna4_geser3 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '29'as urut,'penyempurnaan 4 geser 4' as ket, 'nilai_sempurna44',sempurna4_geser4 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '30'as urut,'penyempurnaan 4 geser 5' as ket, 'nilai_sempurna45',sempurna4_geser5 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '31'as urut,'Penyempurnaan 5 ' as ket, 'nilai_sempurna5',sempurna5 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '32'as urut,'Penyempurnaan 5 geser 1' as ket, 'nilai_sempurna51',sempurna5_geser1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '33'as urut,'Penyempurnaan 5 geser 2' as ket, 'nilai_sempurna52',sempurna5_geser2 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '34'as urut,'Penyempurnaan 5 geser 3' as ket, 'nilai_sempurna53',sempurna5_geser3 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '35'as urut,'Penyempurnaan 5 geser 4' as ket, 'nilai_sempurna54',sempurna5_geser4 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '36'as urut,'Penyempurnaan 5 geser 5' as ket, 'nilai_sempurna55',sempurna5_geser5 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '37'as urut,'Perubahan' as ket, 'nilai_ubah',ubah from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '38'as urut,'Perubahan II' as ket, 'nilai_ubah1',ubah1 from status_angkas where kd_skpd ='$skpd'
        UNION ALL
        select '39'as urut,'Perubahan III' as ket, 'nilai_ubah2',ubah2 from status_angkas where kd_skpd ='$skpd'
        )zz where nilai='1' ORDER BY cast(urut as int) DESC";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'status' => $resulte['status'],
                'keterangan' => $resulte['ket']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    // simpan spd biasa dan revisi
    // simpan spd biasa dan revisi
    function simpan_spd_all()
    {
        $tabel      = $this->input->post('tabel');
        $idx        = $this->input->post('cidx');
        $nomor      = $this->input->post('no');
        $nomor2     = $this->input->post('no2');
        $mode_tox   = $this->input->post('mode_tox');
        $tgl        = $this->input->post('tgl');
        $skpd       = $this->input->post('skpd');
        $nmskpd     = $this->input->post('nmskpd');
        $bend       = $this->input->post('bend');
        $revisi     = $this->input->post('revisi');
        $bln1       = $this->input->post('bln1');
        $bln2       = $this->input->post('bln2');
        $ketentuan  = $this->input->post('ketentuan');
        $pengajuan  = $this->input->post('pengajuan');
        $jenis      = $this->input->post('jenis');
        $total      = $this->input->post('total');
        // $csql       = $this->input->post('sql');
        $s_angkas   = $this->input->post('cstatus_angkas');
        $usernm     = $this->session->userdata('pcNama');
        $update     = date('Y-m-d H:i:s');
        $msg        = array();
        // Simpan Header //
        if ($tabel == 'trhspd') {
            if ($mode_tox == 'tambah') {
                $revisi_ke = 0;
                if ($revisi == 1 || $revisi == '1') {
                    $sqlrev = "SELECT max(revisi_ke)+1 as jmrevisi from trhspd where kd_skpd='$skpd' and bulan_awal='$bln1' and bulan_akhir='$bln2'";

                    $hasilrev = $this->db->query($sqlrev);
                    $rev = $hasilrev->row();
                    $revisi_ke = $rev->jmrevisi;
                } else {
                    $revisi_ke = 0;
                }
                //    ambil kolom angkas
                $field_angkas   = $this->get_status_angkas($skpd);


                $sqltotspd = "SELECT sum($field_angkas) total_spd  from trdskpd_ro where kd_skpd='$skpd' and (bulan between '$bln1' and '$bln2') and left(kd_rek6,1)='5'";

                $hasiltotspd = $this->db->query($sqltotspd);
                $tospd = $hasiltotspd->row();
                $totalspdini = $tospd->total_spd;

                $sql = "INSERT into  $tabel (no_spd,tgl_spd,kd_skpd,nm_skpd,jns_beban,bulan_awal,bulan_akhir,total,klain,kd_bkeluar,username,tglupdate,revisi_ke,jns_ang) 
                    values('$nomor','$tgl','$skpd', rtrim('$nmskpd'),'$jenis','$bln1','$bln2','$totalspdini', rtrim('$ketentuan'),'$bend','$usernm','$update','$revisi_ke','$s_angkas')";
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
                $sql = "UPDATE $tabel set 
                no_spd='$nomor',tgl_spd='$tgl',kd_skpd='$skpd',nm_skpd=rtrim('$nmskpd'),
                jns_beban='$jenis',bulan_awal='$bln1',bulan_akhir='$bln2',total='$total',klain=rtrim('$ketentuan'),kd_bkeluar='$bend',username='$usernm',tglupdate='$update'
                where no_spd='$nomor2' ";
                $asg = $this->db->query($sql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        } else if ($tabel == 'trdspd') {

            // Simpan Detail //                       
            $sql = "delete from  $tabel where no_spd='$nomor2'";
            $asg = $this->db->query($sql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $n_status       = $this->cek_anggaran_model->cek_anggaran($skpd);
                $field_angkas   = $this->get_status_angkas($skpd);
                if ($revisi == 0 || $revisi == '0' || $revisi == '' || $revisi == null) {
                    $sql = "INSERT trdspd
                            select '$nomor' as no_spd,kd_program,RTRIM(nm_program),left(kd_sub_kegiatan,12),
                            RTRIM((select nm_kegiatan from ms_kegiatan where left(kd_sub_kegiatan,12)=kd_kegiatan))as nm_kegiatan,
                            kd_sub_kegiatan,RTRIM(nm_sub_kegiatan),kd_rek6,RTRIM(nm_rek6),ISNULL(nilai,0),kd_unit
                             from (
                            SELECT a.kd_unit, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, a.kd_rek6 , a.nm_rek6, a.total_ubah as anggaran, 
                            b.nilai-isnull(lalu_tw,0) as nilai,c.lalu FROM(

                             SELECT a.kd_skpd as kd_unit, b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,b.kd_rek6 , b.nm_rek6 , 
                                sum(b.nilai) as total_ubah, left(a.kd_skpd,17) kd_skpd FROM trskpd a 
                             inner join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd 
                             inner join ms_sub_kegiatan c on a.kd_sub_kegiatan=c.kd_sub_kegiatan 
                             WHERE b.kd_skpd='$skpd' and c.jns_sub_kegiatan='5' and b.jns_ang='$n_status'
                            group by a.kd_skpd, a.kd_skpd,a.kd_program, a.nm_program,b.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6
                             
                             ) a LEFT JOIN (

                                SELECT kd_sub_kegiatan, b.kd_rek6, left(kd_skpd,17) kd_skpd, kd_skpd as kd_unit, SUM($field_angkas) as nilai FROM trdskpd_ro b 
                                WHERE b.bulan>='$bln1' AND b.bulan<='$bln2' AND kd_skpd='$skpd' 
                                GROUP BY kd_skpd,kd_skpd,kd_sub_kegiatan,kd_rek6 
                                
                                )b ON a.kd_unit=b.kd_unit and a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_rek6=b.kd_rek6
                                
                                LEFT JOIN (

                             SELECT kd_unit as kd_skpd,kd_sub_kegiatan,kd_rek6,isnull(SUM(a.nilai),0) as lalu FROM trdspd a LEFT JOIN trhspd b 
                            ON a.no_spd=b.no_spd 
                            WHERE b.kd_skpd='$skpd' and a.no_spd != '$nomor' 
                            and b.tgl_spd<'$tgl' 
                            GROUP BY kd_unit,kd_sub_kegiatan,kd_rek6 
                             ) c 
                             
                             ON a.kd_unit=c.kd_skpd and a.kd_sub_kegiatan=c.kd_sub_kegiatan and a.kd_rek6=c.kd_rek6
                             
                            LEFT JOIN (

                             SELECT kd_unit as kd_skpd,kd_sub_kegiatan,kd_rek6,isnull(SUM(a.nilai),0) as lalu_tw FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                            WHERE b.kd_skpd='$skpd' and b.bulan_awal='$bln1' AND b.bulan_akhir='$bln2' and a.no_spd != '$nomor' and b.tgl_spd<'$tgl' 
                            GROUP BY kd_unit,kd_sub_kegiatan,kd_rek6 
                             ) d 
                             
                             ON a.kd_unit=d.kd_skpd and a.kd_sub_kegiatan=d.kd_sub_kegiatan and a.kd_rek6=d.kd_rek6

                            )xxx 
                            
                            ORDER BY kd_unit,kd_sub_kegiatan
                ";
                } else {
                    $sql = "INSERT trdspd
                    select '$nomor' as no_spd,kd_program,RTRIM(nm_program),left(kd_sub_kegiatan,12),
                    RTRIM((select nm_kegiatan from ms_kegiatan where left(kd_sub_kegiatan,12)=kd_kegiatan))as nm_kegiatan,
                    kd_sub_kegiatan,RTRIM(nm_sub_kegiatan),kd_rek6,RTRIM(nm_rek6),ISNULL(nilai,0),kd_unit
                     from (
                    SELECT a.kd_unit, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, a.kd_rek6 , a.nm_rek6, a.total_ubah as anggaran, 
                    b.nilai,c.lalu FROM(

                     SELECT a.kd_skpd as kd_unit, b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,b.kd_rek6 , b.nm_rek6 , 
                        sum(b.nilai) as total_ubah, a.kd_skpd kd_skpd FROM trskpd a 
                     inner join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd 
                     inner join ms_sub_kegiatan c on a.kd_sub_kegiatan=c.kd_sub_kegiatan 
                     WHERE b.kd_skpd='$skpd' and c.jns_sub_kegiatan='5' and b.jns_ang='$n_status'
                    group by a.kd_skpd, a.kd_skpd,a.kd_program, a.nm_program,b.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6
                     
                     ) a LEFT JOIN (

                        SELECT kd_sub_kegiatan, b.kd_rek6, kd_skpd kd_skpd, kd_skpd as kd_unit, SUM($field_angkas) as nilai FROM trdskpd_ro b 
                        WHERE b.bulan>='$bln1' AND b.bulan<='$bln2' AND kd_skpd='$skpd'
                        GROUP BY kd_skpd,kd_skpd,kd_sub_kegiatan,kd_rek6 
                        
                        )b ON a.kd_unit=b.kd_unit and a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_rek6=b.kd_rek6
                        
                    LEFT JOIN (

                     SELECT kd_unit as kd_skpd,kd_sub_kegiatan,kd_rek6,isnull(SUM(a.nilai),0) as lalu FROM trdspd a LEFT JOIN trhspd b 
                    ON a.no_spd=b.no_spd 
                    WHERE b.kd_skpd='$skpd' and a.no_spd != '$nomor' 
                    and b.tgl_spd<'$tgl' 
                    GROUP BY kd_unit,kd_sub_kegiatan,kd_rek6 
                     ) c 
                     
                     ON a.kd_unit=c.kd_skpd and a.kd_sub_kegiatan=c.kd_sub_kegiatan and a.kd_rek6=c.kd_rek6
                     

                    )xxx 
                    
                    ORDER BY kd_unit,kd_sub_kegiatan
            ";
                }
                $asg = $this->db->query($sql);
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
        //$asg->free_result();
    }

    function simpan_spd_all_melawi()
    {
        $tabel      = $this->input->post('tabel');
        $idx        = $this->input->post('cidx');
        $nomor      = $this->input->post('no');
        $nomor2     = $this->input->post('no2');
        $mode_tox   = $this->input->post('mode_tox');
        $tgl        = $this->input->post('tgl');
        $skpd       = $this->input->post('skpd');
        $nmskpd     = $this->input->post('nmskpd');
        $bend       = $this->input->post('bend');
        $revisi     = $this->input->post('revisi');
        $bln1       = $this->input->post('bln1');
        $bln2       = $this->input->post('bln2');
        $ketentuan  = $this->input->post('ketentuan');
        $pengajuan  = $this->input->post('pengajuan');
        $jenis      = $this->input->post('jenis');
        $total      = $this->input->post('total');
        // $csql       = $this->input->post('sql');
        $s_angkas   = $this->input->post('cstatus_angkass');
        $usernm     = $this->session->userdata('pcNama');
        $update     = date('Y-m-d H:i:s');
        $msg        = array();

        if ($s_angkas == 'murni') {
            $nilai = 'nilai';
        } else if ($s_angkas == 'susun') {
            $nilai = 'nilai_susun';
        } else if ($s_angkas == 'susun11') {
            $nilai = 'nilai_susun1';
        } else if ($s_angkas == 'sempurna') {
            $nilai = 'nilai_sempurna';
        } else if ($s_angkas == 'sempurna2') {
            $nilai = 'nilai_sempurna2';
        } else if (($s_angkas == 'sempurna21')) {
            $nilai = 'nilai_sempurna21';
        } else if (($s_angkas == 'sempurna22')) {
            $nilai = 'nilai_sempurna22';
        } else if ($s_angkas == 'sempurna3') {
            $nilai = 'nilai_sempurna3';
        } else {
            $nilai = 'nilai_ubah';
        }
        // Simpan Header //
        if ($tabel == 'trhspd') {
            if ($mode_tox == 'tambah') {
                $revisi_ke = 0;
                if ($revisi == 1 || $revisi == '1') {
                    $sqlrev = "SELECT max(revisi_ke)+1 as jmrevisi from trhspd where kd_skpd='$skpd' and bulan_awal='$bln1' and bulan_akhir='$bln2'";

                    $hasilrev = $this->db->query($sqlrev);
                    $rev = $hasilrev->row();
                    $revisi_ke = $rev->jmrevisi;
                } else {
                    $revisi_ke = 0;
                }

                $sqltotspd = "SELECT sum($nilai) total_spd from trdskpd_ro where left(kd_skpd,17)=left('$skpd',17) and (bulan between '$bln1' and '$bln2') and left(kd_rek6,1)='5'";

                $hasiltotspd = $this->db->query($sqltotspd);
                $tospd = $hasiltotspd->row();
                $totalspdini = $tospd->total_spd;

                $sql = "INSERT into  $tabel (no_spd,tgl_spd,kd_skpd,nm_skpd,jns_beban,bulan_awal,bulan_akhir,total,klain,kd_bkeluar,username,tglupdate,revisi_ke,jns_ang) 
                    values('$nomor','$tgl','$skpd', rtrim('$nmskpd'),'$jenis','$bln1','$bln2','$totalspdini', rtrim('$ketentuan'),'$bend','$usernm','$update','$revisi_ke','$s_angkas')";
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
                $sql = "UPDATE $tabel set 
                no_spd='$nomor',tgl_spd='$tgl',kd_skpd='$skpd',nm_skpd=rtrim('$nmskpd'),
                jns_beban='$jenis',bulan_awal='$bln1',bulan_akhir='$bln2',total='$total',klain=rtrim('$ketentuan'),kd_bkeluar='$bend',username='$usernm',tglupdate='$update'
                where no_spd='$nomor2' ";
                $asg = $this->db->query($sql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        } else if ($tabel == 'trdspd') {

            // Simpan Detail //                       
            $sql = "delete from  $tabel where no_spd='$nomor2'";
            $asg = $this->db->query($sql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $n_status       = $this->get_status_spd($skpd);
                $field_angkas   = $this->get_status_angkas($skpd);
                if ($revisi == 0 || $revisi == '0' || $revisi == '' || $revisi == null) {
                    $sql = "
                            INSERT trdspd
                                    select '$nomor' as no_spd,kd_program,RTRIM(nm_program),left(kd_sub_kegiatan,12),
                                    RTRIM((select nm_kegiatan from ms_kegiatan where left(kd_sub_kegiatan,12)=kd_kegiatan))as nm_kegiatan,
                                    kd_sub_kegiatan,RTRIM(nm_sub_kegiatan),kd_rek6,RTRIM(nm_rek6),nilai,kd_unit
                                     from (
                                    SELECT a.kd_unit, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, a.kd_rek6 , a.nm_rek6, a.total_ubah as anggaran, 
                                    b.nilai-isnull(lalu_tw,0) as nilai,c.lalu FROM(

                                     SELECT a.kd_skpd as kd_unit, b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,b.kd_rek6 , b.nm_rek6 , 
                                        sum(b.nilai) as total_ubah, left(a.kd_skpd,17) kd_skpd FROM trskpd a 
                                     inner join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd AND a.jns_ang=b.jns_ang 
                                     inner join ms_sub_kegiatan c on a.kd_sub_kegiatan=c.kd_sub_kegiatan 
                                     WHERE left(b.kd_skpd,17)=left('$skpd',17) and c.jns_sub_kegiatan='5' and b.jns_ang='$n_status'
                                    group by left(a.kd_skpd,17), a.kd_skpd,a.kd_program, a.nm_program,b.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6
                                     
                                     ) a LEFT JOIN (

                                        SELECT kd_sub_kegiatan, b.kd_rek6, left(kd_skpd,17) kd_skpd, kd_skpd as kd_unit, SUM($nilai) as nilai FROM trdskpd_ro b 
                                        WHERE b.bulan>='$bln1' AND b.bulan<='$bln2' AND left(kd_skpd,17)=left('$skpd',17) 
                                        GROUP BY left(kd_skpd,17),kd_skpd,kd_sub_kegiatan,kd_rek6 
                                        
                                        )b ON a.kd_unit=b.kd_unit and a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_rek6=b.kd_rek6
                                        
                                    LEFT JOIN (

                                     SELECT kd_unit as kd_skpd,kd_sub_kegiatan,kd_rek6,isnull(SUM(a.nilai),0) as lalu FROM trdspd a LEFT JOIN trhspd b 
                                    ON a.no_spd=b.no_spd 
                                    WHERE left(b.kd_skpd,17)=left('$skpd',17) and a.no_spd != '$nomor' 
                                    and b.tgl_spd<'$tgl' 
                                    GROUP BY kd_unit,kd_sub_kegiatan,kd_rek6 
                                     ) c 
                                     
                                     ON a.kd_unit=c.kd_skpd and a.kd_sub_kegiatan=c.kd_sub_kegiatan and a.kd_rek6=c.kd_rek6
                                     
                                    LEFT JOIN (

                                     SELECT kd_unit as kd_skpd,kd_sub_kegiatan,kd_rek6,isnull(SUM(a.nilai),0) as lalu_tw FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                                    WHERE left(b.kd_skpd,17)=left('$skpd',17) and b.bulan_awal='$bln1' AND b.bulan_akhir='$bln2' and a.no_spd != '$nomor' and b.tgl_spd<'$tgl' 
                                    GROUP BY kd_unit,kd_sub_kegiatan,kd_rek6 
                                     ) d 
                                     
                                     ON a.kd_unit=d.kd_skpd and a.kd_sub_kegiatan=d.kd_sub_kegiatan and a.kd_rek6=d.kd_rek6

                                    )xxx 
                                    
                                    ORDER BY kd_unit,kd_sub_kegiatan
                        ";
                } else {
                    $sql = "
                    INSERT trdspd
                            select '$nomor' as no_spd,kd_program,RTRIM(nm_program),left(kd_sub_kegiatan,12),
                            RTRIM((select nm_kegiatan from ms_kegiatan where left(kd_sub_kegiatan,12)=kd_kegiatan))as nm_kegiatan,
                            kd_sub_kegiatan,RTRIM(nm_sub_kegiatan),kd_rek6,RTRIM(nm_rek6),nilai,kd_unit
                             from (
                            SELECT a.kd_unit, a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, a.kd_rek6 , a.nm_rek6, a.total_ubah as anggaran, 
                            b.nilai,c.lalu FROM(

                             SELECT a.kd_skpd as kd_unit, b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,b.kd_rek6 , b.nm_rek6 , 
                                sum(b.nilai) as total_ubah, left(a.kd_skpd,17) kd_skpd FROM trskpd a 
                             inner join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd AND a.jns_ang=b.jns_ang
                             inner join ms_sub_kegiatan c on a.kd_sub_kegiatan=c.kd_sub_kegiatan 
                             WHERE left(b.kd_skpd,17)=left('$skpd',17) and c.jns_sub_kegiatan='5' and b.jns_ang='$n_status'
                            group by left(a.kd_skpd,17), a.kd_skpd,a.kd_program, a.nm_program,b.kd_sub_kegiatan,a.nm_sub_kegiatan,b.kd_rek6,b.nm_rek6
                             
                             ) a LEFT JOIN (

                                SELECT kd_sub_kegiatan, b.kd_rek6, left(kd_skpd,17) kd_skpd, kd_skpd as kd_unit, SUM(nilai_ubah5) as nilai FROM trdskpd_ro b 
                                WHERE b.bulan>='$bln1' AND b.bulan<='$bln2' AND left(kd_skpd,17)=left('$skpd',17) 
                                GROUP BY left(kd_skpd,17),kd_skpd,kd_sub_kegiatan,kd_rek6 
                                
                                )b ON a.kd_unit=b.kd_unit and a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_rek6=b.kd_rek6
                                
                            LEFT JOIN (

                             SELECT kd_unit as kd_skpd,kd_sub_kegiatan,kd_rek6,isnull(SUM(a.nilai),0) as lalu FROM trdspd a LEFT JOIN trhspd b 
                            ON a.no_spd=b.no_spd 
                            WHERE left(b.kd_skpd,17)=left('$skpd',17) and a.no_spd != '$nomor' 
                            and b.tgl_spd<'$tgl' 
                            GROUP BY kd_unit,kd_sub_kegiatan,kd_rek6 
                             ) c 
                             
                             ON a.kd_unit=c.kd_skpd and a.kd_sub_kegiatan=c.kd_sub_kegiatan and a.kd_rek6=c.kd_rek6
                             

                            )xxx 
                            
                            ORDER BY kd_unit,kd_sub_kegiatan
                    ";
                }
                $asg = $this->db->query($sql);
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
        //$asg->free_result();
    }

    // simpan lama
    function simpan_spd()
    {
        $tabel      = $this->input->post('tabel');
        $idx        = $this->input->post('cidx');
        $nomor      = $this->input->post('no');
        $nomor2      = $this->input->post('no2');
        $mode_tox    = $this->input->post('mode_tox');
        $tgl        = $this->input->post('tgl');
        $skpd       = $this->input->post('skpd');
        $nmskpd     = $this->input->post('nmskpd');
        $bend       = $this->input->post('bend');
        $bln1       = $this->input->post('bln1');
        $bln2       = $this->input->post('bln2');
        $ketentuan  = $this->input->post('ketentuan');
        $pengajuan  = $this->input->post('pengajuan');
        $jenis      = $this->input->post('jenis');
        $total      = $this->input->post('total');
        $csql       = $this->input->post('sql');
        $usernm     = $this->session->userdata('pcNama');
        $update     = date('Y-m-d H:i:s');
        $msg = array();
        // Simpan Header //
        if ($tabel == 'trhspd') {
            if ($mode_tox == 'tambah') {
                //            $sql = "delete from  $tabel where kd_skpd='$skpd' and no_spd='$nomor'";
                //           $asg = $this->db->query($sql);
                //if ($asg){
                $sql = "insert into  $tabel (no_spd,tgl_spd,kd_skpd,nm_skpd,jns_beban,bulan_awal,bulan_akhir,total,klain,kd_bkeluar,username,tglupdate) 
                            values('$nomor','$tgl','$skpd', rtrim('$nmskpd'),'$jenis','$bln1','$bln2','$total', rtrim('$ketentuan'),'$bend','$usernm','$update')";
                $asg = $this->db->query($sql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
                //} else {
                //     $msg = array('pesan'=>'0');
                //    echo json_encode($msg);
                //   exit();
                //}
            } else {
                $sql = "update $tabel set 
                        no_spd='$nomor',tgl_spd='$tgl',kd_skpd='$skpd',nm_skpd=rtrim('$nmskpd'),
                        jns_beban='$jenis',bulan_awal='$bln1',bulan_akhir='$bln2',total='$total',klain=rtrim('$ketentuan'),kd_bkeluar='$bend',username='$usernm',tglupdate='$update'
                        where no_spd='$nomor2' ";
                $asg = $this->db->query($sql);
                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        } else if ($tabel == 'trdspd') {

            // Simpan Detail //                       
            $sql = "delete from  $tabel where no_spd='$nomor2'";
            $asg = $this->db->query($sql);
            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $sql = "insert into  $tabel(no_spd,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,kd_program,nm_program,nilai)";
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

    function cetak_lampiran_spd1()
    {

        $print          = $this->uri->segment(2);
        $tnp_no         = $this->uri->segment(3);
        $cell           = $this->uri->segment(5);
        // echo($cell);
        $nos            = $this->uri->segment(6);
        $nip            = $this->uri->segment(7);
        $jang           = $this->uri->segment(8);
        $nip_ppkd       = str_replace('123456789', ' ', $nip);
        $nama_ppkd      = $this->rka_model->get_nama($nip_ppkd, 'nama', 'ms_ttd', 'nip');
        $jabatan_ppkd   = $this->rka_model->get_nama($nip_ppkd, 'jabatan', 'ms_ttd', 'nip');
        $pangkat_ppkd   = $this->rka_model->get_nama($nip_ppkd, 'pangkat', 'ms_ttd', 'nip');
        $kop            = $this->input->post('kop');
        $lntahunang     = $this->session->userdata('pcThang');
        $lcnospd        = str_replace('spd', '/', $nos);
        $lkd_skpd       = $this->rka_model->get_nama($lcnospd, 'kd_skpd', 'trhspd', 'no_spd');
        $ldtgl_spd      = $this->rka_model->get_nama($lcnospd, 'tgl_spd', 'trhspd', 'no_spd');
        $stsubah        = $this->rka_model->get_nama($lkd_skpd, 'status', 'trhrka', 'kd_skpd');
        $field = $jang;

        $csql = "SELECT 
                (SELECT no_dpa FROM trhrka WHERE kd_skpd = a.kd_skpd and jns_ang='$jang') AS no_dpa,
                (SELECT SUM(nilai) FROM trdrka WHERE 
                jns_ang='$jang' and
                kd_sub_kegiatan IN(SELECT kd_sub_kegiatan FROM trdspd WHERE no_spd=a.no_spd)
                 AND left(kd_skpd,22) = left(a.kd_skpd,22)) jm_ang,
                (SELECT SUM(total) FROM trhspd WHERE kd_skpd = a.kd_skpd AND jns_beban=a.jns_beban AND 
                tgl_spd<=a.tgl_spd AND no_spd<>a.no_spd) AS jm_spdlalu,
                (select sum(nilai) from trdspd f where f.no_spd=a.no_spd) AS jm_spdini,a.jns_beban,a.bulan_awal,a.bulan_akhir,kd_skpd
                FROM trhspd a WHERE a.no_spd = '$lcnospd '";
        //  echo($csql);

        $hasil = $this->db->query($csql);
        $data1 = $hasil->row();
        $periode1 = $this->rka_model->getBulan($data1->bulan_awal);
        $periode2 = $this->rka_model->getBulan($data1->bulan_akhir);
        $jnsspd = $data1->jns_beban;
        $jm_ang = $data1->jm_ang;
        $jm_spdlalu = $data1->jm_spdlalu;
        $jm_spdini = $data1->jm_spdini;
        $A = $jm_ang;
        $b = $jm_spdlalu + $jm_spdini;
        $lnsisa = $jm_ang - ($jm_spdlalu + $jm_spdini);

        $lkd_skpd = $data1->kd_skpd;
        $ljns_beban = $data1->jns_beban;

        if ($ljns_beban == '6') {
            $nm_beban = "PENGELUARAN PEMBIAYAAN";
            $sql2 = "select * from ms_rek1 where kd_rek1='6'";
            $hasil2 = $this->db->query($sql2);
            $tox2 = $hasil2->row();
            $nm_beban2 = $tox2->nm_rek1;
            $kd_rek1 = $tox2->kd_rek1;
        } else if ($ljns_beban == '5') {
            $nm_beban = "BELANJA";
            $sql2 = "select * from ms_rek1 where kd_rek1='5'";
            $hasil2 = $this->db->query($sql2);
            $tox2 = $hasil2->row();
            $nm_beban2 = $nm_beban; //$tox2->nm_rek1;
            $kd_rek1 = $tox2->kd_rek1;
        };

        $nm_rek2 = $this->rka_model->get_nama($ljns_beban, 'nm_rek1', 'ms_rek1', 'kd_rek1');

        $nospd_cetak = $lcnospd;
        $tahun = $this->tukd_model->get_sclient('thn_ang', 'sclient');

        if ($tnp_no == '1') {
            $con_dpn = '903/';

            //$tahun=$this->session->userdata('pcThang');
            $con_blk_btl = ' /PEMBIAYAAN/BKAD-B/' . $tahun;
            $con_blk_bl = '  /BELANJA/BKAD-B/' . $tahun;

            ($ljns_beban == '62') ?  $nospd_cetak = $con_dpn . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $con_blk_btl : $nospd_cetak = $con_dpn . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $con_blk_bl;
        }
        $kode = substr($lkd_skpd, 0, -3);
        if ($ljns_beban == '5' || $kode == '4.09.01' || $kode == '1.04.02' || $kode == '2.05.01' || $kode == '4.02.02' || $kode == '4.02.03' || $kode == '2.17.01') {
            $nospd_cetak = str_replace('BKAD', 'BKAD', $nospd_cetak);
            $elek = "KEPALA BADAN KEUANGAN DAN ASET DAERAH";
            $raimu = "PLT. ";
        } else {
            $elek = "KEPALA BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH";
            $raimu = "";
            $nospd_cetak = str_replace('BKAD', 'BPKPD', $nospd_cetak);
        }



        if ($ljns_beban == '5' || $kode == '4.09.01' || $kode == '1.04.02' || $kode == '2.05.01' || $kode == '4.02.02' || $kode == '4.02.03' || $kode == '2.17.01') {
            $nospd_cetak = str_replace('2021', '2021', $nospd_cetak);
        } else {
            $nospd_cetak = str_replace('2021', '2021', $nospd_cetak);
        }

        $cRet = '';
        $field = $jang;

        //$this->get_status_spd($lkd_skpd); 

        // echo $field;
        $sqldpa = "SELECT no_dpa from trhrka where kd_skpd='$lkd_skpd' and jns_ang='$field'";
        $hasildpa = $this->db->query($sqldpa);
        $dpa = $hasildpa->row();
        $no_dpa = $dpa->no_dpa;


        //  if ($field=='nilai_ubah'){
        //     $no_dpa=$this->rka_model->get_nama($lkd_skpd,'no_dpa_ubah','trhrka','kd_skpd');
        //  }else if($field=='nilaisempurna3'){
        //     $no_dpa=$this->rka_model->get_nama($lkd_skpd,'no_dpa_sempurna3','trhrka','kd_skpd');
        //  }else if($field=='nilaisempurna2'){
        //     $no_dpa=$this->rka_model->get_nama($lkd_skpd,'no_dpa_sempurna2','trhrka','kd_skpd');
        //  }else if($field=='nilaisempurna1'){
        //     $no_dpa=$this->rka_model->get_nama($lkd_skpd,'no_dpa_sempurna','trhrka','kd_skpd');
        //  }else{
        //     $no_dpa=$this->rka_model->get_nama($lkd_skpd,'no_dpa','trhrka','kd_skpd');
        //  }
        // echo $no_dpa;

        $ukuran           = $this->uri->segment(4);
        if ($ukuran == '1') {
            $font = 11;
            $font1 = $font - 1;
        } else {
            $font = $ukuran;
            $font1 = $font - 1;
        }

        $sql = "select kd_skpd,nm_skpd from ms_skpd where kd_skpd='$lkd_skpd'";
        $hasil = $this->db->query($sql);
        $tox = $hasil->row();
        $kd_skpd = $tox->kd_skpd;

        $raimu = "PEJABAT PENGELOLA KEUANGAN DAERAH SELAKU BENDAHARA UMUM DAERAH";
        $cRet .= "<table width='100%' style='font-size:12px' cellpadding='0px'  cellspacing='0' > 
                <tr>
                    <td width='90%'><center>
                        PEMERINTAH KABUPATEN MELAWI 
                        <br /> $raimu 
                        <br />NOMOR $nospd_cetak
                        <br /> TENTANG
                        <br /> SURAT PENYEDIAAN DANA ANGGARAN BELANJA DAERAH
                        <br /> TAHUN ANGGARAN $tahun
                        <br />
                        <br />&nbsp;
                    <center></td>

                </tr>
                                               
        </table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-family:Times New Roman; font-size:10 px;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">               
                    <tr>
                        <td width=\"18%\" colspan=\"2\" align=\"left\">LAMPIRAN SURAT PENYEDIAAN DANA </td>
                    </tr>
                    <tr>
                        <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                    </tr>
                    <tr>
                        <td width=\"18%\" align=\"left\">NOMOR SPD </td>
                        <td width=\"72%\" align=\"left\">: $nospd_cetak</td>
                    </tr>
                    <tr>
                        <td width=\"18%\" align=\"left\">TANGGAL</td>
                        <td width=\"72%\" align=\"left\">: " . $this->support->tanggal_format_indonesia($ldtgl_spd) . "</td>
                    </tr>
                    <tr>
                        <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                    </tr>
                    <tr><td align=\"left\"> SKPD </td><td align=\"left\">: " . $tox->nm_skpd . "</td></tr>
                    <tr><td align=\"left\"> PERIODE BULAN </td><td align=\"left\">: $periode1 s/d $periode2 $tahun</td></tr>
                    <tr><td align=\"left\">TAHUN ANGGARAN </td><td align=\"left\">: $lntahunang</td></tr>
                    <tr><td align=\"left\">NOMOR DPA-SKPD </td><td align=\"left\">: $no_dpa</td></tr>
                    <tr>
                        <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                    </tr>
                </table>";
        $cRet .= "
           <table style=\"border-collapse:collapse;font-family:Times New Roman; font-size:$font px;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"$cell\">               
                <tr>
                    <td width=\"3%\" align=\"center\" style=\"font-weight:bold;\">No.        
                    </td>
                    <td colspan=\"2\" width=\"50%\" align=\"center\" style=\"font-weight:bold;\">Kode, dan Nama Program, Kegiatan dan Sub Kegiatan        
                    </td>
                    </td>
                    <td width=\"16%\" align=\"center\" style=\"font-weight:bold;\">ANGGARAN</td>
                    <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">AKUMULASI SPD SEBELUMNYA</td>
                    <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">JUMLAH SPD PERIODE INI</td>
                    <td width=\"11%\" align=\"center\" style=\"font-weight:bold;\">SISA ANGGARAN</td>
                </tr>
                <tr>
                    <td width=\"3%\" align=\"center\" style=\"font-weight:bold;\">1</td>
                    <td colspan=\"2\" width=\"55%\" align=\"center\" style=\"font-weight:bold;\">2</td>
                    <td width=\"11%\" align=\"center\" style=\"font-weight:bold;\">3</td>
                    <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">4</td>
                    <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">5</td>
                    <td width=\"11%\" align=\"center\" style=\"font-weight:bold;\">6=3-4-5</td>
                </tr>
                ";
        $tgl_spds        = $this->rka_model->get_nama($lcnospd, 'tgl_spd', 'trhspd', 'no_spd');
        $bulan_akhir     = $this->rka_model->get_nama($lcnospd, 'bulan_akhir', 'trhspd', 'no_spd');
        // --------------------------------
        $sql1   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                        kd_skpd='$lkd_skpd' 
                                        and bulan_akhir='3' 
                                        -- and tgl_spd<='$tgl_spds'
                                        ";
        $q1     = $this->db->query($sql1);
        $tw1    = $q1->row();
        $rev1   = $tw1->revisi;
        // --------------------------------
        $sql2   = "SELECT isnull(max(revisi_ke),0) as revisi from trhspd where 
                                        kd_skpd='$lkd_skpd' 
                                        and bulan_akhir='6' 
                                        -- and tgl_spd<='$tgl_spds'
                                        ";
        $q2     = $this->db->query($sql2);
        $tw2    = $q2->row();
        $rev2   = $tw2->revisi;
        // --------------------------------
        $sql3   = "SELECT isnull(max(revisi_ke),0) as revisi from trhspd where 
                                        kd_skpd='$lkd_skpd' 
                                        and bulan_akhir='9' 
                                        -- and tgl_spd<='$tgl_spds'
                                        ";
        $q3     = $this->db->query($sql3);
        $tw3    = $q3->row();
        $rev3   = $tw3->revisi;
        // --------------------------------
        $sql4   = "SELECT isnull(max(revisi_ke),0) as revisi from trhspd where 
                                        kd_skpd='$lkd_skpd' 
                                        and bulan_akhir='12' 
                                        -- and tgl_spd<='$tgl_spds'
                                        ";
        $q4     = $this->db->query($sql4);
        $tw4    = $q4->row();
        $rev4   = $tw4->revisi;



        $csql2 = "SELECT nm_skpd,kd_skpd,tgl_spd,total,bulan_awal,bulan_akhir,jns_beban,kd_bkeluar,klain from trhspd where no_spd = '$lcnospd'  ";
        $hasil1 = $this->db->query($csql2);
        $trh1 = $hasil1->row();
        $ldtgl_spd = $trh1->tgl_spd;

        $twspd = $this->rka_model->get_nama($lcnospd, 'bulan_akhir', 'trhspd', 'no_spd');

        if ($twspd == '3') {
            $spdlalu = "SELECT sum(nilai)as jm_spd_l from (
                            SELECT
                            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                            FROM
                            trdspd a
                            JOIN trhspd b ON a.no_spd = b.no_spd
                            WHERE
                            left(a.kd_unit,22) = left('$lkd_skpd',22)
                            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
                            AND b.jns_beban='5'
                            and bulan_akhir<='$bulan_akhir'
                            and bulan_awal='1'
                            and revisi_ke='$rev1'
                            and tgl_spd<='$tgl_spds'
                            and a.no_spd<>'$lcnospd'
                            )zz";
            $spdlalu6 = "SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='1'
            and revisi_ke='$rev1'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            )zz";

            $spdlalu2 = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        )zz";
            $spdlalu26 = "SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
            AND b.jns_beban='5'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='1'
            and revisi_ke='$rev1'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            )zz";

            $spdlalu3 = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        )zz";
            $spdlalu36 = "SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='1'
            and revisi_ke='$rev1'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            )zz";
            $spdlalu4 = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND a.kd_rek6=z.kd_rek6
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                    )zz";
            $spdlalu46 = "SELECT sum(nilai)as jm_spd_l from (
                SELECT
                'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                FROM
                trdspd a
                JOIN trhspd b ON a.no_spd = b.no_spd
                WHERE
                left(a.kd_unit,22) = left('$lkd_skpd',22)
                AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                AND a.kd_rek6=z.kd_rek6
                AND b.jns_beban='6'
                and bulan_akhir<='$bulan_akhir'
                and bulan_awal='1'
                and revisi_ke='$rev1'
                and tgl_spd<='$tgl_spds'
                and a.no_spd<>'$lcnospd'
            )zz";
        } else if ($twspd == '6') {
            $spdlalu = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='4'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        )zz";
            $spdlalu6 = "SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='1'
            and revisi_ke='$rev1'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='4'
            and revisi_ke='$rev2'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            )zz";
            $spdlalu2 = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='4'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        )zz";
            $spdlalu26 = "SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='1'
            and revisi_ke='$rev1'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='4'
            and revisi_ke='$rev2'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            )zz";
            $spdlalu3 = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='4'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        )zz";
            $spdlalu36 = "SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,17) = left('$lkd_skpd',17)
            AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='1'
            and revisi_ke='$rev1'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='4'
            and revisi_ke='$rev2'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            )zz";
            $spdlalu4 = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND a.kd_rek6=z.kd_rek6
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND a.kd_rek6=z.kd_rek6
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='4'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                    )zz";
            $spdlalu46 = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND a.kd_rek6=z.kd_rek6
                        AND b.jns_beban='6'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND a.kd_rek6=z.kd_rek6
                        AND b.jns_beban='6'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='4'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                    )zz";
        } else if ($twspd == '9') {
            $spdlalu = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='4'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='7'
                        and revisi_ke='$rev3'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                    )zz";
            $spdlalu6 = "SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='1'
            and revisi_ke='$rev1'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='4'
            and revisi_ke='$rev2'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='7'
            and revisi_ke='$rev3'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
        )zz";
            $spdlalu2 = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='4'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='7'
                        and revisi_ke='$rev3'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                    )zz";
            $spdlalu26 = "SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='1'
            and revisi_ke='$rev1'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='4'
            and revisi_ke='$rev2'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='7'
            and revisi_ke='$rev3'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
        )zz";
            $spdlalu3 = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='4'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='7'
                        and revisi_ke='$rev3'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                    )zz";
            $spdlalu36 = "SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='1'
            and revisi_ke='$rev1'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='4'
            and revisi_ke='$rev2'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='7'
            and revisi_ke='$rev3'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
        )zz";
            $spdlalu4 = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND a.kd_rek6=z.kd_rek6
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND a.kd_rek6=z.kd_rek6
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='4'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND a.kd_rek6=z.kd_rek6
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='7'
                        and revisi_ke='$rev3'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                    )zz";
            $spdlalu46 = "SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
            AND a.kd_rek6=z.kd_rek6
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='1'
            and revisi_ke='$rev1'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
            AND a.kd_rek6=z.kd_rek6
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='4'
            and revisi_ke='$rev2'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
            AND a.kd_rek6=z.kd_rek6
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='7'
            and revisi_ke='$rev3'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
        )zz";
        } else {
            $spdlalu = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='4'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='7'
                        and revisi_ke='$rev3'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW4' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='10'
                        and revisi_ke='$rev4'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        )zz";
            $spdlalu6 = "SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='1'
            and revisi_ke='$rev1'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='4'
            and revisi_ke='$rev2'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='7'
            and revisi_ke='$rev3'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW4' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='10'
            and revisi_ke='$rev4'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            )zz";
            $spdlalu2 = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='4'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='7'
                        and revisi_ke='$rev3'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW4' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='10'
                        and revisi_ke='$rev4'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        )zz";
            $spdlalu26 = "SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='1'
            and revisi_ke='$rev1'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='4'
            and revisi_ke='$rev2'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='7'
            and revisi_ke='$rev3'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW4' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='10'
            and revisi_ke='$rev4'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            )zz";
            $spdlalu3 = "SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='4'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='7'
                        and revisi_ke='$rev3'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW4' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='10'
                        and revisi_ke='$rev4'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        )zz";
            $spdlalu36 = "SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='1'
            and revisi_ke='$rev1'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='4'
            and revisi_ke='$rev2'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='7'
            and revisi_ke='$rev3'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW4' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='10'
            and revisi_ke='$rev4'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            )zz";
            $spdlalu4 = "     SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND a.kd_rek6=z.kd_rek6
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='1'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND a.kd_rek6=z.kd_rek6
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='4'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lkd_skpd',22)
                        AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
                        AND a.kd_rek6=z.kd_rek6
                        AND b.jns_beban='5'
                        and bulan_akhir<='$bulan_akhir'
                        and bulan_awal='7'
                        and revisi_ke='$rev3'
                        and tgl_spd<='$tgl_spds'
                        and a.no_spd<>'$lcnospd'
                       
                        )zz";
            $spdlalu46 = "SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
            AND a.kd_rek6=z.kd_rek6
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='1'
            and revisi_ke='$rev1'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
            AND a.kd_rek6=z.kd_rek6
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='4'
            and revisi_ke='$rev2'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            left(a.kd_unit,22) = left('$lkd_skpd',22)
            AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
            AND a.kd_rek6=z.kd_rek6
            AND b.jns_beban='6'
            and bulan_akhir<='$bulan_akhir'
            and bulan_awal='7'
            and revisi_ke='$rev3'
            and tgl_spd<='$tgl_spds'
            and a.no_spd<>'$lcnospd'
            )zz";
        }


        // echo ($ljns_beban);

        if ($ljns_beban == '5') {
            $sql = "                     
        SELECT  
        left(kd_skpd,17)+left(kd_sub_kegiatan,7) as no_urut,
        left(kd_skpd,17) kd_skpd,
        left(kd_sub_kegiatan,7)as kode,
        left(kd_sub_kegiatan,7)as kode1,
        (select nm_program from ms_program a where a.kd_program= left(z.kd_sub_kegiatan,7))as uraian,
        isnull(sum(nilai),0)as anggaran,

        (

            $spdlalu


        )as spd_lalu,

        (select isnull(sum(e.nilai),0) from trhspd d inner join trdspd e on d.no_spd=e.no_spd where 
        d.no_spd='$lcnospd' and e.kd_program=left(z.kd_sub_kegiatan,7) 
        and left(e.kd_unit,22)=left(z.kd_skpd,22) and jns_beban='5')as nilai
        from trdrka z where z.jns_ang='$field' AND left(kd_rek6,1)='5' and  left(kd_skpd,22)=left('$lkd_skpd',22)
        group by left(kd_skpd,17),LEFT( kd_skpd,22),left(kd_sub_kegiatan,7)

        UNION ALL

        select  
        left(kd_skpd,17)+left(kd_sub_kegiatan,12) as no_urut,
        left(kd_skpd,17),
        left(kd_sub_kegiatan,12)as kd_kegiatan,
        left(kd_sub_kegiatan,12)as kode1,
        (select nm_kegiatan from ms_kegiatan a where a.kd_kegiatan= left(z.kd_sub_kegiatan,12))as uraian,
        isnull(sum(nilai),0)as anggaran,
        (

            $spdlalu2


        )as spd_lalu,
        (select isnull(sum(e.nilai),0) from trhspd d inner join trdspd e on d.no_spd=e.no_spd where 
        d.no_spd='$lcnospd' and left(e.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12) 
        and left(e.kd_unit,22)=left(z.kd_skpd,22) and jns_beban='5')as nilai
        from trdrka z where z.jns_ang='$field' AND left(kd_rek6,1)='5' and  left(kd_skpd,22)=left('$lkd_skpd',22)
        group by left(kd_skpd,17),LEFT(kd_skpd,22),left(kd_sub_kegiatan,12)


        UNION ALL

        select  
        left(kd_skpd,17)+kd_sub_kegiatan as no_urut,
        left(kd_skpd,17) kd_skpd,
        kd_sub_kegiatan as kd_kegiatan,
        kd_sub_kegiatan as kode1,
        nm_sub_kegiatan as uraian,
        isnull(sum(nilai),0)as anggaran,
        (

        $spdlalu3


        )as spd_lalu,
        (select isnull(sum(e.nilai),0) from trhspd d inner join trdspd e on d.no_spd=e.no_spd where 
        d.no_spd='$lcnospd' and e.kd_sub_kegiatan=z.kd_sub_kegiatan 
        and left(e.kd_unit,22)=left(z.kd_skpd,22) and jns_beban='5')as nilai
        from trdrka z where z.jns_ang='$field' AND left(kd_rek6,1)='5' and  left(kd_skpd,22)=left('$lkd_skpd',22)
        group by left(kd_skpd,17),LEFT(kd_skpd,22),kd_sub_kegiatan,nm_sub_kegiatan

        UNION ALL

        select  
        left(kd_skpd,17)+kd_sub_kegiatan+kd_rek6 as no_urut,
        left(kd_skpd,17),
        kd_rek6 as kd_kegiatan,
        kd_sub_kegiatan as kode ,
        nm_rek6 as uraian,
        isnull(sum(nilai),0)as anggaran,

        (
        $spdlalu4


        )as spd_lalu,

        (select isnull(sum(e.nilai),0) from trhspd d inner join trdspd e on d.no_spd=e.no_spd where 
        d.no_spd='$lcnospd' 
        and e.kd_sub_kegiatan=z.kd_sub_kegiatan and e.kd_rek6=z.kd_rek6 
        and left(e.kd_unit,22)=left(z.kd_skpd,22) and jns_beban='5')as nilai
        from trdrka z where left(kd_rek6,1)='5' and  left(kd_skpd,22)=left('$lkd_skpd',22) and z.jns_ang='$field'
        group by left(kd_skpd,17),LEFT(kd_skpd,22),kd_sub_kegiatan,kd_rek6,nm_rek6



        order by no_urut";
        } else {

            $sql = "                          
        SELECT  
        left(kd_skpd,17)+left(kd_sub_kegiatan,7) as no_urut,
        left(kd_skpd,17) kd_skpd,
        left(kd_sub_kegiatan,7)as kode,
        left(kd_sub_kegiatan,7)as kode1,
        (select nm_program from ms_program a where a.kd_program= left(z.kd_sub_kegiatan,7))as uraian,
        isnull(sum(nilai),0)as anggaran,

        (

            $spdlalu6


        )as spd_lalu,

        (select isnull(sum(e.nilai),0) from trhspd d inner join trdspd e on d.no_spd=e.no_spd where 
        d.no_spd='$lcnospd' and e.kd_program=left(z.kd_sub_kegiatan,7) 
        and left(e.kd_unit,22)=left(z.kd_skpd,22) and jns_beban='6')as nilai
        from trdrka z where z.jns_ang='$field' AND left(kd_rek6,2)='62' and  left(kd_skpd,22)=left('$lkd_skpd',22)
        group by left(kd_skpd,17),LEFT(kd_skpd,22),left(kd_sub_kegiatan,7)

        UNION ALL

        select  
        left(kd_skpd,17)+left(kd_sub_kegiatan,12) as no_urut,
        left(kd_skpd,17),
        left(kd_sub_kegiatan,12)as kd_kegiatan,
        left(kd_sub_kegiatan,12)as kode1,
        (select nm_kegiatan from ms_kegiatan a where a.kd_kegiatan= left(z.kd_sub_kegiatan,12))as uraian,
        isnull(sum(nilai),0)as anggaran,
        (

            $spdlalu26


        )as spd_lalu,
        (select isnull(sum(e.nilai),0) from trhspd d inner join trdspd e on d.no_spd=e.no_spd where 
        d.no_spd='$lcnospd' and left(e.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12) 
        and left(e.kd_unit,17)=left(z.kd_skpd,17) and jns_beban='6')as nilai
        from trdrka z where z.jns_ang='$field' AND left(kd_rek6,2)='62' and  left(kd_skpd,22)=left('$lkd_skpd',22)
        group by left(kd_skpd,17),LEFT(kd_skpd,22),left(kd_sub_kegiatan,12)


        UNION ALL

        select  
        left(kd_skpd,17)+kd_sub_kegiatan as no_urut,
        left(kd_skpd,17) kd_skpd,
        kd_sub_kegiatan as kd_kegiatan,
        kd_sub_kegiatan as kode1,
        nm_sub_kegiatan as uraian,
        isnull(sum(nilai),0)as anggaran,
        (

        $spdlalu36


        )as spd_lalu,
        (select isnull(sum(e.nilai),0) from trhspd d inner join trdspd e on d.no_spd=e.no_spd where 
        d.no_spd='$lcnospd' and e.kd_sub_kegiatan=z.kd_sub_kegiatan 
        and left(e.kd_unit,22)=left(z.kd_skpd,22) and jns_beban='6')as nilai
        from trdrka z where z.jns_ang='$field' AND left(kd_rek6,2)='62' and  left(kd_skpd,22)=left('$lkd_skpd',22)
        group by left(kd_skpd,17),LEFT(kd_skpd,22),kd_sub_kegiatan,nm_sub_kegiatan

        UNION ALL

        select  
        left(kd_skpd,17)+kd_sub_kegiatan+kd_rek6 as no_urut,
        left(kd_skpd,17),
        kd_rek6 as kd_kegiatan,
        kd_sub_kegiatan as kode ,
        nm_rek6 as uraian,
        isnull(sum(nilai),0)as anggaran,

        (
        $spdlalu46


        )as spd_lalu,

        (select isnull(sum(e.nilai),0) from trhspd d inner join trdspd e on d.no_spd=e.no_spd where 
        d.no_spd='$lcnospd' 
        and e.kd_sub_kegiatan=z.kd_sub_kegiatan and e.kd_rek6=z.kd_rek6 
        and left(e.kd_unit,22)=left(z.kd_skpd,22) and jns_beban='6')as nilai
        from trdrka z where left(kd_rek6,2)='62' and  left(kd_skpd,22)=left('$lkd_skpd',22) and z.jns_ang='$field'
        group by left(kd_skpd,17),LEFT(kd_skpd,22),kd_sub_kegiatan,kd_rek6,nm_rek6



        order by no_urut";
        }



        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lntotal = 0;
        $jtotal_spd = 0;
        $jtotalang = 0;
        $jtotalspdlalu = 0;
        $jtotalspd = 0;
        $lcsisa = 0;
        foreach ($hasil->result() as $row) {
            $lcno = $lcno + 1;
            //$lntotal = $lntotal + $row->nilai;
            $lcsisa = $row->anggaran - $row->spd_lalu - $row->nilai;
            $total_spd = $row->spd_lalu + $row->nilai;
            //echo $row->no_dpa;
            if ($row->no_urut == '0') {
                $lcno_urut = '';
            } else {
                $lcno_urut = $row->no_urut;
            };
            $kode = $row->kode;
            $lenkode = strlen($kode);

            //copy
            //copy bl
            if (strlen($row->no_urut) <= 32) {
                $bold = 'font-weight:bold;';
                $fontr = $font1;
            } else {
                $bold = '';
                $fontr = $font;
            }

            if (strlen($row->no_urut) == 44) {

                $jtotalang = $jtotalang + $row->anggaran;
                $jtotalspdlalu = $jtotalspdlalu + $row->spd_lalu;
                $jtotalspd = $jtotalspd + $row->nilai;
            }





            //copy bl
            if ($ljns_beban == '5') {
                $cRet .= "<tr>
                                            <td width=\"3%\" align=\"center\" style=\"$bold font-size:$fontr px\">$lcno.   
                                            </td>
                                            <td width=\"10%\" style=\"$bold font-size:$fontr px\">$kode 
                                            
                                            </td>
                                            <td width=\"38%\" style=\"$bold font-size:$fontr px\">$row->uraian       
                                            </td>
                                            <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->anggaran, "2", ",", ".") . "&nbsp;     
                                            </td>
                                            <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->spd_lalu, "2", ",", ".") . "&nbsp;    
                                            </td>
                                            <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;
                                            </td>
                                            
                                            <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($lcsisa, "2", ",", ".") . "&nbsp;
                                            </td>
                                        </tr>";
            } else if ($ljns_beban == '6') {
                $cRet .= "<tr>
                                            <td width=\"3%\" align=\"center\" style=\"$bold font-size:$fontr px\">$lcno.   
                                            </td>
                                            <td width=\"10%\" style=\"$bold font-size:$fontr px\">$kode 
                                            
                                            </td>
                                            <td width=\"38%\" style=\"$bold font-size:$fontr px\">$row->uraian       
                                            </td>
                                            <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->anggaran, "2", ",", ".") . "&nbsp;     
                                            </td>
                                            <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->spd_lalu, "2", ",", ".") . "&nbsp;    
                                            </td>
                                            <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;
                                            </td>
                                            
                                            <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($lcsisa, "2", ",", ".") . "&nbsp;
                                            </td>
                                        </tr>";
            }
        }
        //perbaiki $total_spd <td align=\"right\" style=\"font-weight:bold;font-size:$font1 px\">".number_format($data1->jm_spdini,"2",".",",")."&nbsp;
        $lnsisa = number_format($lnsisa, "2", ",", ".");
        $cRet .= "<tr>
                                            <td valign=\"center\" style=\"font-size:10px\" align=\"right\" colspan=\"3\"><b>JUMLAH</b> &nbsp;&nbsp;&nbsp;       
                                            </td>
                                            <td valign=\"center\" style=\"font-size:10px\" align=\"right\"><b>" . number_format($jtotalang, "2", ",", ".") . "&nbsp;           
                                            </b></td>
                                            <td valign=\"center\" style=\"font-size:10px\" align=\"right\"><b>" . number_format($jtotalspdlalu, "2", ",", ".") . "&nbsp;        
                                            </b></td>
                                            <td valign=\"center\" style=\"font-size:10px\" align=\"right\"><b>" . number_format($jtotalspd, "2", ",", ".") . "&nbsp;
                                            </b></td>

                                            <td valign=\"center\" style=\"font-size:10px\" align=\"right\"><b>" . number_format($jtotalang - ($jtotalspdlalu + $jtotalspd), "2", ",", ".") . "
                                            </b></td>
                                        </tr>";

        $cRet .= "</table>";

        $enter          = $this->uri->segment(9);

        for ($i = 0; $i < $enter; $i++) {
            $cRet .= "<br>";
        }



        $cRet .= "<table style=\"border-collapse:collapse;font-family:Times New Roman; font-size:10 px;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">  
                            <tr>
                                <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                            </tr>             
                            <tr>
                                <td width=\"18%\" colspan=\"2\" align=\"left\">Jumlah Penyediaan Dana Rp" . number_format($data1->jm_spdini, "2", ",", ".") . "<br />
                                <i>(" . $this->tukd_model->terbilang($data1->jm_spdini) . ")</i></td>
                            </tr>
                            <tr>
                                <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                            </tr>
                        </table>";
        // CETAKAN TANDA TANGAN by Tox
        $cRet .= " <table style=\"border-collapse:collapse;font-family: arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                    <tr >
                        <td width=\"60%\" align=\"right\">&nbsp;</td>
                        <td width=\"40%\"  align=\"center\" colspan=\"2\">&nbsp;&nbsp;<br>
                    <br>
                    <br>
                    <br>
                    <br>
                    <br>Ditetapkan di Melawi</td>
                        </td>
                    </tr>
                <tr >
                        <td width=\"75%\" align=\"right\" colspan=\"2\">&nbsp;
                        </td>   
                        <td width=\"25%\"  text-indent: 50px; align=\"left\">Pada tanggal " . $this->support->tanggal_format_indonesia($ldtgl_spd) . "</td>
                        </td>
                    </tr>   
                    <tr >
                        <td align=\"right\">&nbsp;</td>
                        <td  align=\"center\" colspan=\"2\">&nbsp;</td>
                        </td>
                    </tr>
                <tr >
                        <td width=\"60%\" align=\"right\">&nbsp;</td>
                        <td width=\"40%\"  align=\"center\" colspan=\"2\" style=\"text-transform: uppercase;\"> PPKD SELAKU BUD,</td>
                        </td>
                    </tr>
                    <tr >
                        <td align=\"right\">&nbsp;</td>
                        <td  align=\"center\" colspan=\"2\">&nbsp;</td>
                        </td>
                    </tr>
                    <tr >
                        <td align=\"right\">&nbsp;</td>
                        <td  align=\"center\" colspan=\"2\">&nbsp;</td>
                        </td>
                    </tr>
                    <tr >
                        <td align=\"right\">&nbsp;</td>
                        <td  align=\"center\" colspan=\"2\">&nbsp;</td>
                        </td>
                    </tr>   
                <tr >
                        <td align=\"right\">&nbsp;</td>
                        <td  align=\"center\" colspan=\"2\"><u>$nama_ppkd</u></td>
                        </td>
                    </tr>
                <tr >
                        <td  align=\"right\">&nbsp;</td>
                        <td align=\"center\" colspan=\"2\">NIP. $nip_ppkd</td>
                        </td>
                    </tr>           
                </table>";
        //echo $cRet;

        $data['prev'] = $cRet;
        //echo $data['prev'];  
        //        $this->rka_model->_mpdf('',$cRet,'10','10',5,'1');
        $hasil->free_result();
        if ($print == 1) {
            $this->_mpdf('', $cRet, 10, 10, 10, 0, '', '', '', 5);
            //$this->rka_model->_mpdf_folio('',$cRet,10,10,10,'0'); 
        } else {
            echo $cRet;
        }
    }



    function cetak_lampiran_spd_biaya()
    {

        $print          = $this->uri->segment(3);
        $tnp_no         = $this->uri->segment(5);
        $cell           = $this->uri->segment(6);
        $nos            = $this->uri->segment(7);
        $nip            = $this->uri->segment(8);
        $nip_ppkd = str_replace('123456789', ' ', $nip);
        $kode = $this->session->userdata('kdskpd');
        $data = $this->cek_anggaran_model->cek_anggaran($kode);
        // $nama_ppkd      = $this->input->post('nama_ppkd');       
        // $jabatan_ppkd   = $this->input->post('jabatan_ppkd'); 
        // $pangkat_ppkd   = $this->input->post('pangkat_ppkd'); 
        $nama_ppkd = $this->rka_model->get_nama($nip_ppkd, 'nama', 'ms_ttd', 'nip');
        $jabatan_ppkd = $this->rka_model->get_nama($nip_ppkd, 'jabatan', 'ms_ttd', 'nip');
        $pangkat_ppkd = $this->rka_model->get_nama($nip_ppkd, 'pangkat', 'ms_ttd', 'nip');
        $kop            = $this->input->post('kop');

        $lntahunang     = $this->session->userdata('pcThang');

        $lcnospd = str_replace('spd', '/', $nos);
        // $lcnospd        = $this->input->post('nomor1');

        $lkd_skpd       = $this->rka_model->get_nama($lcnospd, 'kd_skpd', 'trhspd', 'no_spd');
        $ldtgl_spd      = $this->rka_model->get_nama($lcnospd, 'tgl_spd', 'trhspd', 'no_spd');
        $stsubah        = $this->rka_model->get_nama($lkd_skpd, 'status', 'trhrka', 'kd_skpd');
        //$n_status = $this->get_status2($lckdskpd);
        $field = $this->get_status($ldtgl_spd, $lkd_skpd);
        $csql = "SELECT 
            (SELECT no_dpa FROM trhrka WHERE kd_skpd = a.kd_skpd AND jns_ang='$data') AS no_dpa,
            (SELECT SUM(nilai) FROM trdrka WHERE jns_ang='$data' and kd_sub_kegiatan IN(SELECT kd_sub_kegiatan FROM trdspd WHERE no_spd=a.no_spd)
             AND left(kd_skpd,22) = left(a.kd_skpd,22)) jm_ang,
            (SELECT SUM(total) FROM trhspd WHERE kd_skpd = a.kd_skpd AND jns_beban=a.jns_beban AND 
            tgl_spd<=a.tgl_spd AND no_spd<>a.no_spd) AS jm_spdlalu,
            (select sum(nilai) from trdspd f where f.no_spd=a.no_spd) AS jm_spdini,a.jns_beban,a.bulan_awal,a.bulan_akhir,kd_skpd
            FROM trhspd a WHERE a.no_spd = '$lcnospd '";
        // echo($csql);

        $hasil = $this->db->query($csql);
        $data1 = $hasil->row();
        $periode1 = $this->rka_model->getBulan($data1->bulan_awal);
        $periode2 = $this->rka_model->getBulan($data1->bulan_akhir);
        $jnsspd = $data1->jns_beban;
        $jm_ang = $data1->jm_ang;
        $jm_spdlalu = $data1->jm_spdlalu;
        $jm_spdini = $data1->jm_spdini;
        $A = $jm_ang;
        $b = $jm_spdlalu + $jm_spdini;
        $lnsisa = $jm_ang - ($jm_spdlalu + $jm_spdini);

        $lkd_skpd = $data1->kd_skpd;
        $ljns_beban = $data1->jns_beban;

        if ($stsubah == 0) {
            $field = "nilai";
        } else {
            $field = "nilai_sempurna";
        }



        if ($ljns_beban == '6') {
            $nm_beban = "PENGELUARAN PEMBIAYAAN";
            $sql2 = "select * from ms_rek1 where kd_rek1='6'";
            $hasil2 = $this->db->query($sql2);
            $tox2 = $hasil2->row();
            $nm_beban2 = $tox2->nm_rek1;
            $kd_rek1 = $tox2->kd_rek1;
        } else if ($ljns_beban == '5') {
            $nm_beban = "BELANJA";
            $sql2 = "select * from ms_rek1 where kd_rek1='5'";
            $hasil2 = $this->db->query($sql2);
            $tox2 = $hasil2->row();
            $nm_beban2 = $nm_beban; //$tox2->nm_rek1;
            $kd_rek1 = $tox2->kd_rek1;
        };

        $nm_rek2 = $this->rka_model->get_nama($ljns_beban, 'nm_rek1', 'ms_rek1', 'kd_rek1');

        $nospd_cetak = $lcnospd;
        $tahun = $this->tukd_model->get_sclient('thn_ang', 'sclient');

        if ($tnp_no == '1') {
            $con_dpn = '903/';

            //$tahun=$this->session->userdata('pcThang');
            $con_blk_btl = ' /PEMBIAYAAN/BKAD-B/' . $tahun;
            $con_blk_bl = '  /BELANJA/BKAD-B/' . $tahun;

            ($ljns_beban == '62') ?  $nospd_cetak = $con_dpn . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $con_blk_btl : $nospd_cetak = $con_dpn . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $con_blk_bl;
        }
        $kode = substr($lkd_skpd, 0, -3);
        if ($ljns_beban == '5' || $kode == '4.09.01' || $kode == '1.04.02' || $kode == '2.05.01' || $kode == '4.02.02' || $kode == '4.02.03' || $kode == '2.17.01') {
            $nospd_cetak = str_replace('BKAD', 'BKAD', $nospd_cetak);
            $elek = "KEPALA BADAN KEUANGAN DAN ASET DAERAH";
            $raimu = "PLT. ";
        } else {
            $elek = "KEPALA BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH";
            $raimu = "";
            $nospd_cetak = str_replace('BKAD', 'BPKPD', $nospd_cetak);
        }



        if ($ljns_beban == '5' || $kode == '4.09.01' || $kode == '1.04.02' || $kode == '2.05.01' || $kode == '4.02.02' || $kode == '4.02.03' || $kode == '2.17.01') {
            $nospd_cetak = str_replace('2021', '2021', $nospd_cetak);
        } else {
            $nospd_cetak = str_replace('2021', '2020', $nospd_cetak);
        }

        $cRet = '';
        $field = $this->get_status2($lkd_skpd);


        if ($field == 'nilai_ubah') {
            $no_dpa = $this->rka_model->get_nama($lkd_skpd, 'no_dpa_ubah', 'trhrka', 'kd_skpd');
        } else if ($field == 'nilai_sempurna') {
            $no_dpa = $this->rka_model->get_nama($lkd_skpd, 'no_dpa_sempurna', 'trhrka', 'kd_skpd');
        } else {
            $no_dpa = $this->rka_model->get_nama($lkd_skpd, 'no_dpa', 'trhrka', 'kd_skpd');
        }

        $font = 11;
        $font1 = $font - 1;

        $sql = "select kd_skpd,nm_skpd from ms_skpd where kd_skpd='$lkd_skpd'";
        $hasil = $this->db->query($sql);
        $tox = $hasil->row();
        $kd_skpd = $tox->kd_skpd;

        $raimu = "PEJABAT PENGELOLA KEUANGAN DAERAH SELAKU BENDAHARA UMUM DAERAH";
        $cRet .= "<table width='100%' style='font-size:12px' cellpadding='0px'  cellspacing='0' > 
            <tr>
                <td width='90%'><center>
                    PEMERINTAH KABUPATEN MELAWI
                    <br /> $raimu 
                    <br />NOMOR $nospd_cetak
                    <br /> TENTANG
                    <br /> SURAT PENYEDIAAN DANA ANGGARAN BELANJA DAERAH
                    <br /> TAHUN ANGGARAN $tahun
                    <br />
                    <br />&nbsp;
                <center></td>

            </tr>
                                           
    </table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-family:Times New Roman; font-size:10 px;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">               
                <tr>
                    <td width=\"18%\" colspan=\"2\" align=\"left\">LAMPIRAN SURAT PENYEDIAAN DANA </td>
                </tr>
                <tr>
                    <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                </tr>
                <tr>
                    <td width=\"18%\" align=\"left\">NOMOR SPD </td>
                    <td width=\"72%\" align=\"left\">: $nospd_cetak</td>
                </tr>
                <tr>
                    <td width=\"18%\" align=\"left\">TANGGAL</td>
                    <td width=\"72%\" align=\"left\">: " . $this->support->tanggal_format_indonesia($ldtgl_spd) . "</td>
                </tr>
                <tr>
                    <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                </tr>
                <tr><td align=\"left\"> SKPD </td><td align=\"left\">: " . $tox->nm_skpd . "</td></tr>
                <tr><td align=\"left\"> PERIODE BULAN </td><td align=\"left\">: $periode1 s/d $periode2 $tahun</td></tr>
                <tr><td align=\"left\">TAHUN ANGGARAN </td><td align=\"left\">: $lntahunang</td></tr>
                <tr><td align=\"left\">NOMOR DPA-SKPD </td><td align=\"left\">: $no_dpa</td></tr>
                <tr>
                    <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                </tr>
            </table>";
        $cRet .= "
       <table style=\"border-collapse:collapse;font-family:Times New Roman; font-size:$font px;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"2\" cellpadding=\"2\">               
            <tr>
                <td width=\"3%\" align=\"center\" style=\"font-weight:bold;\">No.        
                </td>
                <td colspan=\"2\" width=\"50%\" align=\"center\" style=\"font-weight:bold;\">Kode, dan Nama Program, Kegiatan dan Sub Kegiatan        
                </td>
                </td>
                <td width=\"16%\" align=\"center\" style=\"font-weight:bold;\">ANGGARAN</td>
                <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">AKUMULASI SPD SEBELUMNYA</td>
                <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">JUMLAH SPD PERIODE INI</td>
                <td width=\"11%\" align=\"center\" style=\"font-weight:bold;\">SISA ANGGARAN</td>
            </tr>
            <tr>
                <td width=\"3%\" align=\"center\" style=\"font-weight:bold;\">1</td>
                <td colspan=\"2\" width=\"55%\" align=\"center\" style=\"font-weight:bold;\">2</td>
                <td width=\"11%\" align=\"center\" style=\"font-weight:bold;\">3</td>
                <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">4</td>
                <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">5</td>
                <td width=\"11%\" align=\"center\" style=\"font-weight:bold;\">6=3-4-5</td>
            </tr>
            ";
        $tgl_spds        = $this->rka_model->get_nama($lcnospd, 'tgl_spd', 'trhspd', 'no_spd');
        // --------------------------------
        //spd lalu konsep baru

        $lckdskpd = $lkd_skpd;
        $sql1   = "SELECT max(revisi_ke) as revisi from trhspd where 
                        kd_skpd='$lckdskpd' 
                        and bulan_akhir='3'";
        $q1     = $this->db->query($sql1);
        $w1     = $q1->row();
        $rev1   = $w1->revisi;

        //--------
        $sql2   = "SELECT max(revisi_ke) as revisi from trhspd where 
                        kd_skpd='$lckdskpd' 
                        and bulan_akhir='6'";
        $q2     = $this->db->query($sql2);
        $w2     = $q2->row();
        $rev2   = $w2->revisi;

        //--------
        $sql3   = "SELECT max(revisi_ke) as revisi from trhspd where 
                        kd_skpd='$lckdskpd' 
                        and bulan_akhir='9'";
        $q3     = $this->db->query($sql3);
        $w3     = $q3->row();
        $rev3   = $w3->revisi;

        //--------
        $sql4   = "SELECT max(revisi_ke) as revisi from trhspd where 
                        kd_skpd='$lckdskpd' 
                        and bulan_akhir='12'";
        $q4     = $this->db->query($sql4);
        $w4     = $q4->row();
        $rev4   = $w4->revisi;


        $bulanspd = $this->rka_model->get_nama($lcnospd, 'bulan_akhir', 'trhspd', 'no_spd');


        $csql2 = "SELECT nm_skpd,kd_skpd,tgl_spd,total,bulan_awal,bulan_akhir,jns_beban,kd_bkeluar,klain from trhspd where no_spd = '$lcnospd'  ";
        $hasil1 = $this->db->query($csql2);
        $trh1 = $hasil1->row();
        $ldtgl_spd = $trh1->tgl_spd;


        if ($ljns_beban == '5') {
            $sql = "
                    
SELECT  
left(kd_skpd,22)+left(kd_sub_kegiatan,7) as no_urut,
left(kd_skpd,22) kd_skpd,
left(kd_sub_kegiatan,7)as kode,
left(kd_sub_kegiatan,7)as kode1,
(select nm_program from ms_program a where a.kd_program= left(z.kd_sub_kegiatan,7))as uraian,
isnull(sum(nilai),0)as anggaran,

(

SELECT sum(nilai)as jm_spd_l from (

           
            SELECT
            'W1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='3'
            and revisi_ke='$rev1'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            
            UNION ALL
            SELECT
            'W2' ket,isnull(SUM(a.nilai),0) as jm_spd_l
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='6'
            and revisi_ke='$rev2'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'W3' ket,isnull(SUM(a.nilai),0) as jm_spd_l
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='9'
            and revisi_ke='$rev3'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'W4' ket,isnull(SUM(a.nilai),0) as jm_spd_l
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND left(a.kd_sub_kegiatan,7)=left(z.kd_sub_kegiatan,7)
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='12'
            and revisi_ke='$rev4'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            )zz
)as spd_lalu,

(select isnull(sum(e.nilai),0) from trhspd d inner join trdspd e on d.no_spd=e.no_spd where 
d.no_spd='$lcnospd' and e.kd_program=left(z.kd_sub_kegiatan,7) 
and left(e.kd_unit,22)=left(z.kd_skpd,22) and jns_beban='5')as nilai
from trdrka z where jns_ang='$data' and left(kd_rek6,1)='5' and  left(kd_skpd,22)=left('$lkd_skpd',22)
group by left(kd_skpd,22),left(kd_sub_kegiatan,7)

UNION ALL

select  
left(kd_skpd,22)+left(kd_sub_kegiatan,12) as no_urut,
left(kd_skpd,22),
left(kd_sub_kegiatan,12)as kd_kegiatan,
left(kd_sub_kegiatan,12)as kode1,
(select nm_kegiatan from ms_kegiatan a where a.kd_kegiatan= left(z.kd_sub_kegiatan,12))as uraian,
isnull(sum(nilai),0)as anggaran,
(

SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'W1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='3'
            and revisi_ke='$rev1'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            
            UNION ALL
            SELECT
            'W2' ket,isnull(SUM(a.nilai),0) as jm_spd_l
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='6'
            and revisi_ke='$rev2'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'W3' ket,isnull(SUM(a.nilai),0) as jm_spd_l
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='9'
            and revisi_ke='$rev3'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'W4' ket,isnull(SUM(a.nilai),0) as jm_spd_l
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND left(a.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12)
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='12'
            and revisi_ke='$rev4'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            )zz



)as spd_lalu,
(select isnull(sum(e.nilai),0) from trhspd d inner join trdspd e on d.no_spd=e.no_spd where 
d.no_spd='$lcnospd' and left(e.kd_sub_kegiatan,12)=left(z.kd_sub_kegiatan,12) 
and left(e.kd_unit,22)=left(z.kd_skpd,22) and jns_beban='5')as nilai
from trdrka z where jns_ang='$data' and left(kd_rek6,1)='5' and  left(kd_skpd,22)=left('$lkd_skpd',22)
group by left(kd_skpd,22),left(kd_sub_kegiatan,12)


UNION ALL

select  
left(kd_skpd,22)+kd_sub_kegiatan as no_urut,
left(kd_skpd,22) kd_skpd,
kd_sub_kegiatan as kd_kegiatan,
kd_sub_kegiatan as kode1,
nm_sub_kegiatan as uraian,
isnull(sum(nilai),0)as anggaran,
(

SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'W1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='3'
            and revisi_ke='$rev1'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            
            UNION ALL
            SELECT
            'W2' ket,isnull(SUM(a.nilai),0) as jm_spd_l
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='6'
            and revisi_ke='$rev2'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'W3' ket,isnull(SUM(a.nilai),0) as jm_spd_l
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='9'
            and revisi_ke='$rev3'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'W4' ket,isnull(SUM(a.nilai),0) as jm_spd_l
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND left(a.kd_sub_kegiatan,15)=left(z.kd_sub_kegiatan,15)
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='12'
            and revisi_ke='$rev4'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            )zz
            )as spd_lalu,
(select isnull(sum(e.nilai),0) from trhspd d inner join trdspd e on d.no_spd=e.no_spd where 
d.no_spd='$lcnospd' and e.kd_sub_kegiatan=z.kd_sub_kegiatan 
and left(e.kd_unit,22)=left(z.kd_skpd,22) and jns_beban='5')as nilai
from trdrka z where left(kd_rek6,1)='5' z.jns_ang='$data' and left(kd_skpd,22)=left('$lkd_skpd',22)
group by left(kd_skpd,22),kd_sub_kegiatan,nm_sub_kegiatan

UNION ALL

select  
left(kd_skpd,22)+kd_sub_kegiatan+kd_rek6 as no_urut,
left(kd_skpd,22),
kd_rek6 as kd_kegiatan,
kd_sub_kegiatan as kode ,
nm_rek6 as uraian,
isnull(sum(nilai),0)as anggaran,

(
SELECT sum(nilai)as jm_spd_l from (
            SELECT
            'W1' ket,isnull(SUM(a.nilai),0) AS nilai
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
            AND a.kd_rek6=z.kd_rek6
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='3'
            and revisi_ke='$rev1'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            
            UNION ALL
            SELECT
            'W2' ket,isnull(SUM(a.nilai),0) as jm_spd_l
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
            AND a.kd_rek6=z.kd_rek6
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='6'
            and revisi_ke='$rev2'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'W3' ket,isnull(SUM(a.nilai),0) as jm_spd_l
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
            AND a.kd_rek6=z.kd_rek6
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='9'
            and revisi_ke='$rev3'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            UNION ALL
            SELECT
            'W4' ket,isnull(SUM(a.nilai),0) as jm_spd_l
            FROM
            trdspd a
            JOIN trhspd b ON a.no_spd = b.no_spd
            WHERE
            b.kd_skpd = '$lckdskpd'
            AND a.kd_sub_kegiatan=z.kd_sub_kegiatan
            AND a.kd_rek6=z.kd_rek6
            AND b.jns_beban='5'
            AND b.status = '1'
            and bulan_akhir='12'
            and revisi_ke='$rev4'
            and bulan_akhir <=$bulanspd
            and a.no_spd<>'$lcnospd'
            )zz



)as spd_lalu,

(select isnull(sum(e.nilai),0) from trhspd d inner join trdspd e on d.no_spd=e.no_spd where 
d.no_spd='$lcnospd' 
and e.kd_sub_kegiatan=z.kd_sub_kegiatan and e.kd_rek6=z.kd_rek6 
and left(e.kd_unit,22)=left(z.kd_skpd,22) and jns_beban='5')as nilai
from trdrka z where jns_ang='$data' AND left(kd_rek6,1)='5' and  left(kd_skpd,22)=left('$lkd_skpd',22)
group by left(kd_skpd,22),kd_sub_kegiatan,kd_rek6,nm_rek6



order by no_urut";
        } else {

            $sql = "
           
           SELECT
	* 
FROM
	(
		(
		SELECT
			'0.00.00' no_urut,
			ss.kd_skpd,
			'0.00.00' kode,
			rtrim( nm_program ) uraian,
			SUM ( anggaran ) anggaran,
			SUM ( spd_lalu ) spd_lalu,
			SUM ( nilai ) nilai 
		FROM
			(
			SELECT
				( '' ) no_urut,
				b.kd_skpd,
				rtrim( a.kd_sub_kegiatan ) kode,
				c.nm_kegiatan,
				isnull(
					(
					SELECT SUM
						( nilai ) 
					FROM
						trdrka 
					WHERE
						jns_ang = '$data' 
						AND kd_sub_kegiatan = a.kd_sub_kegiatan 
						AND LEFT ( kd_skpd, 17 ) = LEFT ( b.kd_skpd, 17 ) 
						AND LEFT ( kd_rek6, 2 ) = '62' 
					),
					0 
				) AS anggaran,
				isnull(
					(
					SELECT SUM
						( nilai ) 
					FROM
						trdspd c
						LEFT JOIN trhspd d ON c.no_spd= d.no_spd 
					WHERE
						c.kd_sub_kegiatan = a.kd_sub_kegiatan 
						AND d.kd_skpd= b.kd_skpd 
						AND c.no_spd <> a.no_spd 
						AND d.tgl_spd<= b.tgl_spd 
						AND d.jns_beban = b.jns_beban 
					),
					0 
				) AS spd_lalu,
				a.nilai,
				a.kd_program 
			FROM
				trdspd a
				LEFT JOIN trhspd b ON a.no_spd= b.no_spd
				INNER JOIN trskpd c ON a.kd_sub_kegiatan= c.kd_sub_kegiatan 
				AND b.kd_skpd= c.kd_skpd 
			WHERE
				a.no_spd = '$lcnospd' 
				AND c.jns_ang= '$data' 
			) ss
			INNER JOIN trskpd b ON ss.kode=b.kd_sub_kegiatan 
			AND b.kd_skpd= ss.kd_skpd WHERE b.jns_ang='$data'
		GROUP BY
			no_urut,
			ss.kd_skpd,
			ss.kd_program,
			nm_program 
		) UNION ALL
	SELECT
		( '0.00.00.0.00' ) no_urut,
		ss.kd_skpd,
		'0.00.00.0.00' kode,
		( SELECT rtrim( j.nm_kegiatan ) FROM ms_kegiatan j WHERE j.kd_kegiatan= LEFT ( b.kd_sub_kegiatan, 12 ) ) AS uraian,
		SUM ( anggaran ) anggaran,
		SUM ( spd_lalu ) spd_lalu,
		SUM ( nilai ) nilai 
	FROM
		(
		SELECT
			( '' ) no_urut,
			b.kd_skpd,
			rtrim( a.kd_sub_kegiatan ) kode,
			c.nm_kegiatan,
			isnull(
				(
				SELECT SUM
					( nilai ) 
				FROM
					trdrka 
				WHERE
					jns_ang = '$data' 
					AND kd_sub_kegiatan = a.kd_sub_kegiatan 
					AND LEFT ( kd_skpd, 17 ) = LEFT ( b.kd_skpd, 17 ) 
					AND LEFT ( kd_rek6, 2 ) = '62' 
				),
				0 
			) AS anggaran,
			isnull(
				(
				SELECT SUM
					( nilai ) 
				FROM
					trdspd c
					LEFT JOIN trhspd d ON c.no_spd= d.no_spd 
				WHERE
					c.kd_sub_kegiatan = a.kd_sub_kegiatan 
					AND d.kd_skpd= b.kd_skpd 
					AND c.no_spd <> a.no_spd 
					AND d.tgl_spd<= b.tgl_spd 
					AND d.jns_beban = b.jns_beban 
				),
				0 
			) AS spd_lalu,
			a.nilai,
			a.kd_program 
		FROM
			trdspd a
			LEFT JOIN trhspd b ON a.no_spd= b.no_spd
			INNER JOIN trskpd c ON a.kd_sub_kegiatan= c.kd_sub_kegiatan 
			AND b.kd_skpd= c.kd_skpd 
		WHERE
			a.no_spd = '$lcnospd' 
			AND c.jns_ang= '$data' 
		) ss
		INNER JOIN ms_sub_kegiatan b ON ss.kode= b.kd_sub_kegiatan 
	GROUP BY
		no_urut,
		ss.kd_skpd,
		LEFT ( b.kd_sub_kegiatan, 12 ) UNION ALL
		(
		
		SELECT
			( '0.00.00.0.00.00' ) no_urut,
			b.kd_skpd,
			'0.00.00.0.00.00' kode,
			 'Pengeluaran Pembiayaan' as m_sub_kegiatan,
			isnull(
				(
				SELECT SUM
					( nilai ) 
				FROM
					trdrka 
				WHERE
					jns_ang = '$data' 
					AND kd_sub_kegiatan = a.kd_sub_kegiatan 
					AND LEFT ( kd_skpd, 17 ) = LEFT ( b.kd_skpd, 17 ) 
					AND LEFT ( kd_rek6, 2 ) = '62' 
				),
				0 
			) AS anggaran,
			isnull(
				(
				SELECT SUM
					( nilai ) 
				FROM
					trdspd c
					LEFT JOIN trhspd d ON c.no_spd= d.no_spd 
				WHERE
					c.kd_sub_kegiatan = a.kd_sub_kegiatan 
					AND d.kd_skpd= b.kd_skpd 
					AND c.no_spd <> a.no_spd 
					AND d.tgl_spd<= b.tgl_spd 
					AND d.jns_beban = b.jns_beban 
				),
				0 
			) AS spd_lalu,
			a.nilai 
		FROM
			trdspd a
			LEFT JOIN trhspd b ON a.no_spd= b.no_spd
			INNER JOIN trskpd c ON a.kd_sub_kegiatan= c.kd_sub_kegiatan 
			AND b.kd_skpd= c.kd_skpd 
		WHERE
			a.no_spd = '$lcnospd' AND c.jns_ang='$data' AND  LEFT(a.kd_rek6,2)='62'
		) UNION ALL
	SELECT
		'0.00.00.0.00.00.' + c.kd_rek6 no_urut,
		b.kd_skpd,
		c.kd_rek6 kode,
		c.nm_rek6,
		isnull(
			(
			SELECT SUM
				( nilai ) 
			FROM
				trdrka 
			WHERE
				jns_ang = '$data' 
				AND kd_sub_kegiatan = a.kd_sub_kegiatan 
				AND LEFT ( kd_skpd, 17 ) = LEFT ( b.kd_skpd, 17 ) 
				AND LEFT ( kd_rek6, 2 ) = '62' 
			),
			0 
		) AS anggaran,
		isnull(
			(
			SELECT SUM
				( nilai ) 
			FROM
				trdspd c
				LEFT JOIN trhspd d ON c.no_spd= d.no_spd 
			WHERE
				c.kd_sub_kegiatan = a.kd_sub_kegiatan 
				AND d.kd_skpd= b.kd_skpd 
				AND c.no_spd <> a.no_spd 
				AND d.tgl_spd<= b.tgl_spd 
				AND d.jns_beban = b.jns_beban 
			),
			0 
		) AS spd_lalu,
		a.nilai 
	FROM
		trdspd a
		INNER JOIN trhspd b ON a.no_spd= b.no_spd
		INNER JOIN trdrka c ON a.kd_sub_kegiatan= c.kd_sub_kegiatan 
		AND b.kd_skpd= c.kd_skpd 
	WHERE
		a.no_spd = '$lcnospd' 
		AND c.jns_ang= '$data' 
		AND LEFT ( c.kd_rek6, 2 ) = '62' 
	) zt 
ORDER BY
	no_urut";
        }

        //echo $sql;


        $hasil = $this->db->query($sql);
        $lcno = 1;
        $lntotal = 0;
        $jtotal_spd = 0;
        $jtotalang = 0;
        $jtotalspdlalu = 0;
        $jtotalspd = 0;
        foreach ($hasil->result() as $row) {
            // $lcno = $lcno + 1;
            //$lntotal = $lntotal + $row->nilai;
            $lcsisa = $row->anggaran - $row->spd_lalu - $row->nilai;
            $total_spd = $row->spd_lalu + $row->nilai;
            //echo $row->no_dpa;
            if ($row->no_urut == '0') {
                $lcno_urut = '';
            } else {
                $lcno_urut = $row->no_urut;
            };
            $kode = $row->kode;
            $lenkode = strlen($kode);

            //copy
            //copy bl
            if ($ljns_beban == '5') {
                if (strlen($row->no_urut) <= 32) {
                    $bold = 'font-weight:bold;';
                    $fontr = $font1;
                } else {
                    $bold = '';
                    $fontr = $font;
                }

                if (strlen($row->no_urut) == 44) {

                    $jtotalang = $jtotalang + $row->anggaran;
                    $jtotalspdlalu = $jtotalspdlalu + $row->spd_lalu;
                    $jtotalspd = $jtotalspd + $row->nilai;
                }
            }

            if ($ljns_beban == '6') {
                if (strlen($row->no_urut) <= 28) {
                    $bold = 'font-weight:bold;';
                    $fontr = $font1;
                } else {
                    $bold = '';
                    $fontr = $font;
                }
                if (strlen($row->no_urut) == 28) {
                    $jtotalang = $jtotalang + $row->anggaran;
                    $jtotalspdlalu = $jtotalspdlalu + $row->spd_lalu;
                    $jtotalspd = $jtotalspd + $row->nilai;
                }
            }
            //copy bl
            $anggaran = $row->anggaran;

            if ($ljns_beban == '5' && $anggaran > '0') {
                $cRet .= "<tr>
                                <td width=\"3%\" align=\"center\" style=\"$bold font-size:$fontr px\">$lcno.   
                                </td>
                                <td width=\"10%\" style=\"$bold font-size:$fontr px\">$kode 
                                
                                </td>
                                <td width=\"38%\" style=\"$bold font-size:$fontr px\">$row->uraian       
                                </td>
                                <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->anggaran, "2", ",", ".") . "&nbsp;     
                                </td>
                                <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->spd_lalu, "2", ",", ".") . "&nbsp;    
                                </td>
                                <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;
                                </td>
                                
                                <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($lcsisa, "2", ",", ".") . "&nbsp;
                                </td>
                            </tr>";
                $lcno++;
            } else if ($ljns_beban == '6') {
                $cRet .= "<tr>
                                <td width=\"3%\" align=\"center\" style=\"$bold font-size:$fontr px\">$lcno.   
                                </td>
                                <td width=\"10%\" style=\"$bold font-size:$fontr px\">$kode 
                                
                                </td>
                                <td width=\"38%\" style=\"$bold font-size:$fontr px\">$row->uraian       
                                </td>
                                <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->anggaran, "2", ",", ".") . "&nbsp;     
                                </td>
                                <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->spd_lalu, "2", ",", ".") . "&nbsp;    
                                </td>
                                <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;
                                </td>
                                
                                <td width=\"12%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($lcsisa, "2", ",", ".") . "&nbsp;
                                </td>
                            </tr>";
                $lcno++;
            }
        }
        //perbaiki $total_spd <td align=\"right\" style=\"font-weight:bold;font-size:$font1 px\">".number_format($data1->jm_spdini,"2",".",",")."&nbsp;
        $lnsisa = number_format($lnsisa, "2", ",", ".");
        $cRet .= "<tr>
                                <td valign=\"center\" style=\"font-size:10px\" align=\"right\" colspan=\"3\"><b>JUMLAH</b> &nbsp;&nbsp;&nbsp;       
                                </td>
                                <td valign=\"center\" style=\"font-size:10px\" align=\"right\"><b>" . number_format($jtotalang, "2", ",", ".") . "&nbsp;           
                                </b></td>
                                <td valign=\"center\" style=\"font-size:10px\" align=\"right\"><b>" . number_format($jtotalspdlalu, "2", ",", ".") . "&nbsp;        
                                </b></td>
                                <td valign=\"center\" style=\"font-size:10px\" align=\"right\"><b>" . number_format($jtotalspd, "2", ",", ".") . "&nbsp;
                                </b></td>

                                <td valign=\"center\" style=\"font-size:10px\" align=\"right\"><b>" . number_format($jtotalang - ($jtotalspdlalu + $jtotalspd), "2", ",", ".") . "
                                </b></td>
                            </tr>";

        $cRet .= "</table>";


        $cRet .= "<table style=\"border-collapse:collapse;font-family:Times New Roman; font-size:10 px;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">  
                <tr>
                    <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                </tr>             
                <tr>
                    <td width=\"18%\" colspan=\"2\" align=\"left\">Jumlah Penyediaan Dana Rp" . number_format($data1->jm_spdini, "2", ",", ".") . "<br />
                    <i>(" . $this->tukd_model->terbilang($data1->jm_spdini) . ")</i></td>
                </tr>
                <tr>
                    <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                </tr>
            </table>";
        // CETAKAN TANDA TANGAN by Tox
        $cRet .= " <table style=\"border-collapse:collapse;font-family: arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
         <tr >
            <td width=\"60%\" align=\"right\">&nbsp;</td>
            <td width=\"40%\"  align=\"center\" colspan=\"2\">&nbsp;&nbsp;<br>
         <br>
         <br>
         <br>Ditetapkan di Nanga Pinoh</td>
            </td>
        </tr>
    <tr >
            <td width=\"60%\" align=\"center\" colspan=\"2\">&nbsp;
            </td>   
            <td width=\"40%\"  text-indent: 50px; align=\"center\">Pada tanggal " . $this->support->tanggal_format_indonesia($ldtgl_spd) . "</td>
            </td>
        </tr>   
         <tr >
            <td align=\"right\">&nbsp;</td>
            <td  align=\"center\" colspan=\"2\">&nbsp;</td>
            </td>
        </tr>
    <tr >
            <td width=\"60%\" align=\"right\">&nbsp;</td>
            <td width=\"40%\"  align=\"center\" colspan=\"2\" style=\"text-transform: uppercase;\"> PPKD SELAKU BUD,</td>
            </td>
        </tr>
        <tr >
            <td align=\"right\">&nbsp;</td>
            <td  align=\"center\" colspan=\"2\">&nbsp;</td>
            </td>
        </tr>
        <tr >
            <td align=\"right\">&nbsp;</td>
            <td  align=\"center\" colspan=\"2\">&nbsp;</td>
            </td>
        </tr>
        <tr >
            <td align=\"right\">&nbsp;</td>
            <td  align=\"center\" colspan=\"2\">&nbsp;</td>
            </td>
        </tr>   
    <tr >
            <td align=\"right\">&nbsp;</td>
            <td  align=\"center\" colspan=\"2\"><u>$nama_ppkd</u></td>
            </td>
        </tr>
    <tr >
            <td  align=\"right\">&nbsp;</td>
            <td align=\"center\" colspan=\"2\">NIP. $nip_ppkd</td>
            </td>
        </tr>           
    </table>";
        //echo $cRet;

        // $data['prev']= $cRet;  
        //echo $data['prev'];  
        //        $this->rka_model->_mpdf('',$cRet,'10','10',5,'1');
        $hasil->free_result();
        if ($print == 1) {
            $this->_mpdf('', $cRet, 10, 10, 10, 0, '', '', '', 5);
            //$this->rka_model->_mpdf_folio('',$cRet,10,10,10,'0'); 
        } else {
            echo $cRet;
        }
    }

    function cetak_lampiran_spd2()
    {

        $print          = $this->uri->segment(2);
        $tnp_no         = $this->uri->segment(3);
        $cell           = $this->uri->segment(5);
        $nip_ppkd       = $this->input->post('nip_ppkd');
        $nama_ppkd      = $this->input->post('nama_ppkd');
        $jabatan_ppkd   = $this->input->post('jabatan_ppkd');
        $pangkat_ppkd   = $this->input->post('pangkat_ppkd');
        $kop            = $this->input->post('kop');
        $lntahunang     = $this->session->userdata('pcThang');
        $lcnospd        = $this->input->post('nomor1');
        $lkd_skpd       = $this->rka_model->get_nama($lcnospd, 'kd_skpd', 'trhspd', 'no_spd');
        $ldtgl_spd      = $this->rka_model->get_nama($lcnospd, 'tgl_spd', 'trhspd', 'no_spd');
        $stsubah        = $this->rka_model->get_nama($lkd_skpd, 'status', 'trhrka', 'kd_skpd');
        $field = $this->get_status($ldtgl_spd, $lkd_skpd);
        $data = $this->cek_anggaran_model->cek_anggaran();

        // print_r($stsubah);die();

        //$n_status = $this->get_status2($lckdskpd);
        $csql = "SELECT (SELECT no_dpa FROM trhrka WHERE kd_skpd = a.kd_skpd) AS no_dpa,
                    (SELECT SUM(nilai) FROM trdrka WHERE  jns_ang='$data' AND kd_sub_kegiatan IN(SELECT kd_sub_kegiatan FROM trdspd WHERE no_spd=a.no_spd)
                     AND left(kd_skpd,22) = left(a.kd_skpd,22)) jm_ang,
                    (SELECT SUM(total) FROM trhspd WHERE kd_skpd = a.kd_skpd AND jns_beban=a.jns_beban AND 
                    tgl_spd<=a.tgl_spd AND no_spd<>a.no_spd) AS jm_spdlalu,
                    (select sum(nilai) from trdspd f where f.no_spd=a.no_spd) AS jm_spdini,a.jns_beban,a.bulan_awal,a.bulan_akhir,kd_skpd
                    FROM trhspd a WHERE a.no_spd = '$lcnospd '";
        //  echo($csql);

        $hasil = $this->db->query($csql);
        $data1 = $hasil->row();
        $periode_awal = $this->rka_model->getBulan(1);
        $periode1 = $this->rka_model->getBulan($data1->bulan_awal);
        $periode2 = $this->rka_model->getBulan($data1->bulan_akhir);
        $jnsspd = $data1->jns_beban;
        $jm_ang = $data1->jm_ang;
        $jm_spdlalu = $data1->jm_spdlalu;
        $jm_spdini = $data1->jm_spdini;
        $A = $jm_ang;
        $b = $jm_spdlalu + $jm_spdini;
        $lnsisa = $jm_ang - ($jm_spdlalu + $jm_spdini);

        $lkd_skpd = $data1->kd_skpd;
        $ljns_beban = $data1->jns_beban;

        if ($stsubah == 0) {
            $field = "nilai";
        } else {
            $field = "nilai_sempurna";
        }



        if ($ljns_beban == '6') {
            $nm_beban = "PENGELUARAN PEMBIAYAAN";
            $sql2 = "select * from ms_rek1 where kd_rek1='6'";
            $hasil2 = $this->db->query($sql2);
            $tox2 = $hasil2->row();
            $nm_beban2 = $tox2->nm_rek1;
            $kd_rek1 = $tox2->kd_rek1;
        } else if ($ljns_beban == '5') {
            $nm_beban = "BELANJA";
            $sql2 = "select * from ms_rek1 where kd_rek1='5'";
            $hasil2 = $this->db->query($sql2);
            $tox2 = $hasil2->row();
            $nm_beban2 = $nm_beban; //$tox2->nm_rek1;
            $kd_rek1 = $tox2->kd_rek1;
        };

        $nm_rek2 = $this->rka_model->get_nama($ljns_beban, 'nm_rek1', 'ms_rek1', 'kd_rek1');

        $nospd_cetak = $lcnospd;
        $tahun = $this->tukd_model->get_sclient('thn_ang', 'sclient');

        if ($tnp_no == '1') {
            $con_dpn = '903/';

            //$tahun=$this->session->userdata('pcThang');
            $con_blk_btl = ' /PEMBIAYAAN/BKAD-B/' . $tahun;
            $con_blk_bl = '  /BELANJA/BKAD-B/' . $tahun;

            ($ljns_beban == '62') ?  $nospd_cetak = $con_dpn . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $con_blk_btl : $nospd_cetak = $con_dpn . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $con_blk_bl;
        }
        $kode = substr($lkd_skpd, 0, -3);
        if ($ljns_beban == '5' || $kode == '4.09.01' || $kode == '1.04.02' || $kode == '2.05.01' || $kode == '4.02.02' || $kode == '4.02.03' || $kode == '2.17.01') {
            $nospd_cetak = str_replace('BKAD', 'BKAD', $nospd_cetak);
            $elek = "KEPALA BADAN KEUANGAN DAN ASET DAERAH";
            $raimu = "PLT. ";
        } else {
            $elek = "KEPALA BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH";
            $raimu = "";
            $nospd_cetak = str_replace('BKAD', 'BPKPD', $nospd_cetak);
        }



        if ($ljns_beban == '5' || $kode == '4.09.01' || $kode == '1.04.02' || $kode == '2.05.01' || $kode == '4.02.02' || $kode == '4.02.03' || $kode == '2.17.01') {
            $nospd_cetak = str_replace('2021', '2021', $nospd_cetak);
        } else {
            $nospd_cetak = str_replace('2021', '2020', $nospd_cetak);
        }

        $cRet = '';
        $field = $this->get_status2($lkd_skpd);


        if ($field == 'nilai_ubah') {
            $no_dpa = $this->rka_model->get_nama($lkd_skpd, 'no_dpa_ubah', 'trhrka', 'kd_skpd');
        } else if ($field == 'nilai_sempurna') {
            $no_dpa = $this->rka_model->get_nama($lkd_skpd, 'no_dpa_sempurna', 'trhrka', 'kd_skpd');
        } else {
            $no_dpa = $this->rka_model->get_nama($lkd_skpd, 'no_dpa', 'trhrka', 'kd_skpd');
        }

        $font = 12;
        $font1 = $font - 1;

        $sql = "select kd_skpd,nm_skpd from ms_skpd where kd_skpd='$lkd_skpd'";
        $hasil = $this->db->query($sql);
        $tox = $hasil->row();
        $kd_skpd = $tox->kd_skpd;

        $raimu = "PEJABAT PENGELOLA KEUANGAN DAERAH SELAKU BENDAHARA UMUM DAERAH";
        $cRet .= "<table width='100%' style='font-size:12px' cellpadding='0px'  cellspacing='0' > 
                    <tr>
                        <td width='90%'><center>
                            PEMERINTAH KABUPATEN MELAWI
                            <br /> $raimu 
                            <br />NOMOR $nospd_cetak
                            <br /> TENTANG
                            <br /> SURAT PENYEDIAAN DANA ANGGARAN BELANJA DAERAH
                            <br /> TAHUN ANGGARAN $tahun
                            <br />
                            <br />&nbsp;
                        <center></td>

                    </tr>
                                                   
            </table>";

        $cRet .= "<table style=\"border-collapse:collapse;font-family:Times New Roman; font-size:10 px;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">               
                        <tr>
                            <td width=\"18%\" colspan=\"2\" align=\"left\">LAMPIRAN SURAT PENYEDIAAN DANA </td>
                        </tr>
                        <tr>
                            <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                        </tr>
                        <tr>
                            <td width=\"18%\" align=\"left\">NOMOR SPD </td>
                            <td width=\"72%\" align=\"left\">: $nospd_cetak</td>
                        </tr>
                        <tr>
                            <td width=\"18%\" align=\"left\">TANGGAL</td>
                            <td width=\"72%\" align=\"left\">: " . $this->support->tanggal_format_indonesia($ldtgl_spd) . "</td>
                        </tr>
                        <tr><td align=\"left\"> SKPD </td><td align=\"left\">: " . $tox->nm_skpd . "</td></tr>
                        <tr><td align=\"left\"> PERIODE BULAN </td><td align=\"left\">: $periode_awal s/d $periode2</td></tr>
                        <tr><td align=\"left\">TAHUN ANGGARAN </td><td align=\"left\">: $lntahunang</td></tr>
                        <tr><td align=\"left\">NOMOR DPA-SKPD </td><td align=\"left\">: $no_dpa</td></tr>
                        <tr>
                            <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                        </tr>
                    </table>";
        $cRet .= "
               <table style=\"border-collapse:collapse;font-family:Times New Roman; font-size:$font px;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$cell\">               
                    <tr>
                        <td width=\"3%\" align=\"center\" style=\"font-weight:bold;\">No.        
                        </td>
                        <td colspan=\"2\" width=\"55%\" align=\"center\" style=\"font-weight:bold;\">Kode, dan Nama Program, Kegiatan dan Sub Kegiatan        
                        </td>
                        </td>
                        <td width=\"11%\" align=\"center\" style=\"font-weight:bold;\">ANGGARAN</td>
                        <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">AKUMULASI SPD SEBELUMNYA</td>
                        <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">JUMLAH SPD PERIODE INI</td>
                        <td width=\"11%\" align=\"center\" style=\"font-weight:bold;\">SISA ANGGARAN</td>
                    </tr>
                    <tr>
                        <td width=\"3%\" align=\"center\" style=\"font-weight:bold;\">1</td>
                        <td colspan=\"2\" width=\"55%\" align=\"center\" style=\"font-weight:bold;\">2</td>
                        <td width=\"11%\" align=\"center\" style=\"font-weight:bold;\">3</td>
                        <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">4</td>
                        <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">5</td>
                        <td width=\"11%\" align=\"center\" style=\"font-weight:bold;\">6=3-4-5</td>
                    </tr>
                    ";


        if ($ljns_beban == '5') {
            $sql = "SELECT * from (( 
                select (ROW_NUMBER() OVER (ORDER BY ss.kd_program))no_urut,ss.kd_skpd,(ss.kd_program)kode,rtrim(ss.nm_program)uraian,anggaran,spd_lalu, 
                nilai from ( 
                SELECT ('')no_urut,b.kd_skpd,rtrim(a.kd_program)kode,c.nm_program, isnull(( 
                SELECT SUM(nilai) FROM trdrka 
                WHERE jns_ang='$data' AND left(kd_sub_kegiatan,7) = a.kd_program AND left(kd_skpd,22)=left(b.kd_skpd,22)),0) AS anggaran, isnull(( 
                SELECT SUM(nilai) FROM trdspd c 
                LEFT JOIN trhspd d ON c.no_spd=d.no_spd 
                WHERE c.kd_program = a.kd_program AND d.kd_skpd=b.kd_skpd AND c.no_spd <> a.no_spd AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) 
                AS spd_lalu, sum(a.nilai) as nilai, a.kd_program FROM trdspd a 
                LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                inner join trskpd c on a.kd_program=c.kd_program and a.kd_sub_kegiatan=c.kd_sub_kegiatan and b.kd_skpd=c.kd_skpd 
                WHERE a.no_spd = '$lcnospd' 
                group by b.kd_skpd,a.kd_program,c.nm_program,a.no_spd,b.tgl_spd,b.jns_beban) ss 
                inner join trskpd b on ss.kode=b.kd_program and b.kd_skpd=ss.kd_skpd 
                group by no_urut,ss.kd_skpd,ss.kd_program,ss.nm_program,ss.anggaran,ss.spd_lalu,ss.nilai)

                union all 

                select ('')no_urut,ss.kd_skpd,rtrim(b.kd_kegiatan)kode, 
                (select rtrim(j.nm_kegiatan) from ms_kegiatan j where j.kd_kegiatan=b.kd_kegiatan)as uraian,
                anggaran,spd_lalu,sum(nilai)nilai from ( 
                SELECT ('')no_urut,b.kd_skpd,rtrim(left(a.kd_sub_kegiatan,12))kode,c.nm_kegiatan, isnull((
                SELECT SUM(nilai) FROM trdrka 
                WHERE jns_ang='$data' AND left(kd_sub_kegiatan,12) = left(a.kd_sub_kegiatan,12) AND left(kd_skpd,22)=left(b.kd_skpd,22)),0) AS anggaran, isnull((
                SELECT SUM(nilai) FROM trdspd c 
                LEFT JOIN trhspd d ON c.no_spd=d.no_spd 
                WHERE LEFT ( c.kd_sub_kegiatan, 12 ) = LEFT ( a.kd_sub_kegiatan, 12 ) AND d.kd_skpd=b.kd_skpd AND c.no_spd <> a.no_spd AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) 
                AS spd_lalu, a.nilai,a.kd_program FROM trdspd a 
                LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                inner join trskpd c on a.kd_sub_kegiatan=c.kd_sub_kegiatan and b.kd_skpd=c.kd_skpd
                WHERE a.no_spd = '$lcnospd' ) ss 
                inner join ms_kegiatan b on ss.kode=b.kd_kegiatan 
                group by no_urut,ss.kd_skpd,b.kd_kegiatan,ss.anggaran,ss.spd_lalu
                union all

                (SELECT ('')no_urut,b.kd_skpd,rtrim(a.kd_sub_kegiatan)kode,c.nm_sub_kegiatan, isnull((
                SELECT SUM(nilai) FROM trdrka 
                WHERE jns_ang='$data' and kd_sub_kegiatan = a.kd_sub_kegiatan AND left(kd_skpd,22)=left(b.kd_skpd,22)),0) AS anggaran, isnull((
                SELECT SUM(nilai) FROM trdspd c 
                LEFT JOIN trhspd d ON c.no_spd=d.no_spd 
                WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan AND d.kd_skpd=b.kd_skpd AND c.no_spd <> a.no_spd AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) 
                AS spd_lalu, sum(a.nilai) nilai FROM trdspd a 
                LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                inner join trskpd c on a.kd_sub_kegiatan=c.kd_sub_kegiatan and b.kd_skpd=c.kd_skpd
                WHERE a.no_spd = '$lcnospd' 
                GROUP BY b.kd_skpd,a.kd_sub_kegiatan,c.nm_sub_kegiatan,a.no_spd,b.tgl_spd,b.jns_beban) ) zt 
                order by kode";
        } else {

            $sql = "    
                            SELECT no_urut, kode, uraian, anggaran, spd_lalu,nilai,   
                            case when right(kd_sub_kegiatan,5)='00.51'
                                then LEFT(kd_sub_kegiatan,19)+'00'
                                else kd_sub_kegiatan
                            end [kd_sub_kegiatan] 
                            from ((
                            select ('')no_urut,rtrim(c.kd_rek3)kode,rtrim(nm_rek3)uraian,
                            sum(anggaran)anggaran,sum(spd_lalu)spd_lalu,sum(nilai)nilai,kd_sub_kegiatan from 
                            (
                            SELECT rtrim(a.kd_rek6)kode,c.nm_rek6,a.kd_sub_kegiatan,
                            isnull((SELECT SUM(nilai) FROM trdrka WHERE jns_ang='$data' and kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_skpd=b.kd_skpd and kd_rek6=a.kd_rek6),0) AS anggaran,
                            isnull((SELECT SUM(nilai) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan and kd_rek6=a.kd_rek6 AND d.kd_skpd=b.kd_skpd AND c.no_spd <> a.no_spd AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                            a.nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
                                    WHERE  a.no_spd  = '$lcnospd'
                            ) ss 
                            inner join ms_rek3 c on left(ss.kode,3)=c.kd_rek3 group by c.kd_rek3,nm_rek3,ss.kd_sub_kegiatan
                            ) union all
                            (
                            select (ROW_NUMBER() OVER (ORDER BY c.kd_rek4))no_urut,rtrim(c.kd_rek4)kode,rtrim(nm_rek4)uraian,
                            sum(anggaran)anggaran,sum(spd_lalu)spd_lalu,sum(nilai)nilai,kd_sub_kegiatan from 
                            (
                            SELECT rtrim(a.kd_rek6)kode,c.nm_rek6,a.kd_sub_kegiatan,
                            isnull((SELECT SUM(nilai) FROM trdrka WHERE jns_ang='$data' and kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_skpd=b.kd_skpd and kd_rek6=a.kd_rek6),0) AS anggaran,
                            isnull((SELECT SUM(nilai) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan and kd_rek6=a.kd_rek6 AND d.kd_skpd=b.kd_skpd AND c.no_spd <> a.no_spd AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                            a.nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
                                    WHERE  a.no_spd  = '$lcnospd'
                            ) ss 
                            inner join ms_rek4 c on left(ss.kode,5)=c.kd_rek4 group by c.kd_rek4,nm_rek4,ss.kd_sub_kegiatan
                            )
                            union all
                            (
                            SELECT ('')no_urut,rtrim(a.kd_rek6)kode,c.nm_rek6,
                            isnull((SELECT SUM(nilai) FROM trdrka WHERE jns_ang='$data' and kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_skpd=b.kd_skpd and kd_rek6=a.kd_rek6),0) AS anggaran,
                            isnull((SELECT SUM(nilai) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan and kd_rek6=a.kd_rek6 AND d.kd_skpd=b.kd_skpd AND c.no_spd <> a.no_spd AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                            a.nilai,a.kd_sub_kegiatan FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
                                    WHERE  a.no_spd = '$lcnospd' 
                                    )
                                    ) zt order by kode";
        }



        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lntotal = 0;
        $jtotal_spd = 0;
        foreach ($hasil->result() as $row) {
            $lcno = $lcno + 1;
            //$lntotal = $lntotal + $row->nilai;
            $lcsisa = $row->anggaran - $row->spd_lalu - $row->nilai;
            $total_spd = $row->spd_lalu + $row->nilai;
            //echo $row->no_dpa;
            if ($row->no_urut == '0') {
                $lcno_urut = '';
            } else {
                $lcno_urut = $row->no_urut;
            };
            $kode = $row->kode;
            $lenkode = strlen($kode);
            $ckode = $row->kode;

            //copy
            //copy bl
            if ($ljns_beban == '5') {
                if ($lenkode <= 15) {
                    $bold = 'font-weight:bold;';
                    $fontr = $font1;
                } else {
                    $bold = '';
                    $fontr = $font;
                }

                if ($lenkode == 18) {
                    $jtotal_spd = $jtotal_spd + $total_spd;
                }
            }

            if ($ljns_beban == '62') {
                if ($lenkode <= 5) {
                    $bold = 'font-weight:bold;';
                    $fontr = $font1;
                } else {
                    $bold = '';
                    $fontr = $font;
                }
                if ($lenkode == 3) {
                    $jtotal_spd = $jtotal_spd + $total_spd;
                }

                $kode = $row->kd_kegiatan . '.' . $this->rka_model->dotrek($kode);
            }
            $cRet .= "<tr>
                                            <td width=\"3%\" align=\"center\" style=\"$bold font-size:$fontr px\">$lcno.   
                                            </td>
                                            <td width=\"17%\" style=\"$bold font-size:$fontr px\">$kode 
                                            
                                            </td>
                                            <td width=\"38%\" style=\"$bold font-size:$fontr px\">$row->uraian       
                                            </td>
                                            <td width=\"11%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->anggaran, "2", ",", ".") . "&nbsp;     
                                            </td>
                                            <td width=\"10%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->spd_lalu, "2", ",", ".") . "&nbsp;    
                                            </td>
                                            <td width=\"10%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;
                                            </td>
                                            
                                            <td width=\"11%\" align=\"right\" style=\"$bold font-size:$fontr px\">" . number_format($lcsisa, "2", ",", ".") . "&nbsp;
                                            </td>
                                        </tr>";
            if ($lenkode == 15) {
                $sqldetail = "SELECT ('')no_urut,b.kd_skpd,rtrim(a.kd_rek6)kode,a.nm_rek6 as uraian, 
                                    isnull((
                                    SELECT sum(nilai) FROM trdrka 
                                    WHERE jns_ang='$data' and kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_rek6=a.kd_rek6 AND left(kd_skpd,22)=left(b.kd_skpd,22)),0) AS anggaran, 
                                    isnull((
                                    SELECT sum(nilai) FROM trdspd c 
                                    LEFT JOIN trhspd d ON c.no_spd=d.no_spd 
                                    WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan AND c.kd_rek6=a.kd_rek6 AND d.kd_skpd=b.kd_skpd AND c.no_spd <> a.no_spd AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) 
                                    AS spd_lalu, a.nilai FROM trdspd a 
                                    LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                                    inner join trskpd c on a.kd_sub_kegiatan=c.kd_sub_kegiatan and b.kd_skpd=c.kd_skpd
                                    WHERE a.no_spd = '$lcnospd' and a.kd_sub_kegiatan='$ckode' order by kode";
                $hasildetail = $this->db->query($sqldetail);
                foreach ($hasildetail->result() as $rowdetail) {
                    $lcno = $lcno + 1;
                    $ang = $rowdetail->anggaran;
                    $spdlalu = $rowdetail->spd_lalu;
                    $nilai = $rowdetail->nilai;
                    $lccsisa2 = $ang - $spdlalu - $nilai;
                    $xkode = $rowdetail->kode;
                    $a = substr($xkode, 0, 1);
                    $b = substr($xkode, 1, 1);
                    $c = substr($xkode, 2, 2);
                    $d = substr($xkode, 3, 2);
                    $e = substr($xkode, 5, 2);
                    $f = substr($xkode, 8, 4);
                    $kodex = $a . '.' . $b . '.' . $c . '.' . $d . '.' . $e . '.' . $f;



                    $cRet .= "<tr>
                                            <td width=\"3%\" align=\"center\" style=\"font-size:$fontr px\">$lcno.   
                                            </td>
                                            <td width=\"17%\" style=\"font-size:$fontr px\">$xkode 
                                            
                                            </td>
                                            <td width=\"38%\" style=\"font-size:$fontr px\">$rowdetail->uraian       
                                            </td>
                                            <td width=\"11%\" align=\"right\" style=\"font-size:$fontr px\">" . number_format($rowdetail->anggaran, "2", ",", ".") . "&nbsp;     
                                            </td>
                                            <td width=\"10%\" align=\"right\" style=\"font-size:$fontr px\">" . number_format($rowdetail->spd_lalu, "2", ",", ".") . "&nbsp;    
                                            </td>
                                            <td width=\"10%\" align=\"right\" style=\"font-size:$fontr px\">" . number_format($rowdetail->nilai, "2", ",", ".") . "&nbsp;
                                            </td>
                                            
                                            <td width=\"11%\" align=\"right\" style=\"font-size:$fontr px\">" . number_format($lccsisa2, "2", ",", ".") . "&nbsp;
                                            </td>
                                        </tr>";
                }
            }
        }
        //perbaiki $total_spd <td align=\"right\" style=\"font-weight:bold;font-size:$font1 px\">".number_format($data1->jm_spdini,"2",".",",")."&nbsp;
        $lnsisa = number_format($lnsisa, "2", ",", ".");
        $cRet .= "<tr>
                                        <td align=\"right\" style=\"font-weight:bold;\" colspan=3>JUMLAH &nbsp;&nbsp;&nbsp;       
                                        </td>
                                        <td align=\"right\" style=\"font-weight:bold;font-size:$font1 px\">" . number_format($data1->jm_ang, "2", ",", ".") . "&nbsp;           
                                        </td>
                                        <td align=\"right\" style=\"font-weight:bold;font-size:$font1 px\">" . number_format($data1->jm_spdlalu, "2", ",", ".") . "&nbsp;        
                                        </td>
                                        <td align=\"right\" style=\"font-weight:bold;font-size:$font1 px\">" . number_format($data1->jm_spdini, "2", ",", ".") . "&nbsp;
                                        </td>

                                        <td align=\"right\" style=\"font-weight:bold;font-size:$font1 px\">$lnsisa
                                        </td>
                                    </tr>";

        $cRet .= "</table>";


        $cRet .= "<table style=\"border-collapse:collapse;font-family:Times New Roman; font-size:10 px;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">  
                        <tr>
                            <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                        </tr>             
                        <tr>
                            <td width=\"18%\" colspan=\"2\" align=\"left\">Jumlah Penyediaan Dana Rp" . number_format($data1->jm_spdini, "2", ",", ".") . "<br />
                            <i>(" . $this->tukd_model->terbilang($data1->jm_spdini) . ")</i></td>
                        </tr>
                        <tr>
                            <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                        </tr>
                    </table>";
        // CETAKAN TANDA TANGAN by Tox
        $cRet .= " <table style=\"border-collapse:collapse;font-family: arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                 <tr >
                    <td width=\"60%\" align=\"right\">&nbsp;</td>
                    <td width=\"40%\"  align=\"center\" colspan=\"2\">&nbsp;&nbsp;Ditetapkan di Nanga Pinoh</td>
                    </td>
                </tr>
            <tr >
                    <td width=\"70%\" align=\"right\" colspan=\"2\">&nbsp;
                    </td>   
                    <td width=\"30%\"  text-indent: 50px; align=\"left\">Pada tanggal " . $this->support->tanggal_format_indonesia($ldtgl_spd) . "</td>
                    </td>
                </tr>   
                 <tr >
                    <td align=\"right\">&nbsp;</td>
                    <td  align=\"center\" colspan=\"2\">&nbsp;</td>
                    </td>
                </tr>
            <tr >
                    <td width=\"60%\" align=\"right\">&nbsp;</td>
                    <td width=\"40%\"  align=\"center\" colspan=\"2\" style=\"text-transform: uppercase;\"> PPKD SELAKU BUD,</td>
                    </td>
                </tr>
                <tr >
                    <td align=\"right\">&nbsp;</td>
                    <td  align=\"center\" colspan=\"2\">&nbsp;</td>
                    </td>
                </tr>
                <tr >
                    <td align=\"right\">&nbsp;</td>
                    <td  align=\"center\" colspan=\"2\">&nbsp;</td>
                    </td>
                </tr>
                <tr >
                    <td align=\"right\">&nbsp;</td>
                    <td  align=\"center\" colspan=\"2\">&nbsp;</td>
                    </td>
                </tr>   
            <tr >
                    <td align=\"right\">&nbsp;</td>
                    <td  align=\"center\" colspan=\"2\"><u>$nama_ppkd</u></td>
                    </td>
                </tr>
            <tr >
                    <td  align=\"right\">&nbsp;</td>
                    <td align=\"center\" colspan=\"2\">NIP. $nip_ppkd</td>
                    </td>
                </tr>           
            </table>";
        // echo $cRet;

        $data['prev'] = $cRet;

        $hasil->free_result();
        if ($print == 1) {
            $this->_mpdf('', $cRet, 10, 10, 10, 0, '', '', '', 5);
        } else {
            echo $cRet;
        }
    }

    function get_status_spd($skpd)
    {
        $n_status = '';

        $sql = "SELECT TOP 1 * from trhrka where kd_skpd ='$skpd' and status='1' order by tgl_dpa DESC";

        $q_trhrka = $this->db->query($sql);
        $num_rows = $q_trhrka->num_rows();

        foreach ($q_trhrka->result() as $r_trhrka) {
            $n_status = $r_trhrka->jns_ang;
        }
        return $n_status;
    }

    function cetak_otor_spd()
    {

        $print              = $this->uri->segment(2);
        $tnp_no             = $this->uri->segment(3);
        $kop                = "";
        $water              = $this->input->post('water');
        $water              = "";
        $nip                = $this->uri->segment(7);;
        $nip_ppkd           = str_replace('123456789', ' ', $nip);
        $nos                = $this->uri->segment(6);
        $field              = $this->uri->segment(8);
        $lcnospd            = str_replace('spd', '/', $nos);
        $tambah             = $this->uri->segment(4) == '0' ? '' : $this->uri->segment(4);
        $nama_ppkd          = $this->rka_model->get_nama($nip_ppkd, 'nama', 'ms_ttd', 'nip');
        $jabatan_ppkd       = $this->rka_model->get_nama($nip_ppkd, 'jabatan', 'ms_ttd', 'nip');
        $pangkat_ppkd       = $this->rka_model->get_nama($nip_ppkd, 'pangkat', 'ms_ttd', 'nip');
        $csql2              = "SELECT nm_skpd,kd_skpd,tgl_spd,total,bulan_awal,bulan_akhir,jns_beban,kd_bkeluar,
                (select TOP 1 nama from ms_ttd where replace(nip,' ','')=replace(kd_bkeluar,' ',''))as nama1,
                klain from trhspd where no_spd ='$lcnospd'";
        $hasil1 = $this->db->query($csql2);
        $trh1 = $hasil1->row();
        $ldtgl_spd = $trh1->tgl_spd;
        $klain = $trh1->klain;
        $jmlspdini = number_format(($trh1->total), 2, ',', '.'); //number_format(ceil($trh1->total),2,',','.');;
        $biljmlini = $this->tukd_model->terbilang(($trh1->total));
        $lckdskpd = $trh1->kd_skpd;
        $blnini = $this->rka_model->getBulan($trh1->bulan_awal);
        $blnsd = $this->rka_model->getBulan($trh1->bulan_akhir);
        $lcnmskpd = $trh1->nm_skpd;
        $skpd = $trh1->kd_skpd;
        $ljns_beban = $trh1->jns_beban;
        $lcnipbk = $trh1->kd_bkeluar;

        if ($lcnipbk <> '') {
            $sqlttd1 = "SELECT nama as nm FROM ms_ttd WHERE nip='$lcnipbk'";
            $sqlttd = $this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd) {
                $nama1 = $rowttd->nm;
            }
        }

        $kode = substr($lckdskpd, 0, -3);
        $n_status = $this->get_status_spd($lckdskpd);

        $sqldpa   = "SELECT *  from trhrka where kd_skpd='$lckdskpd' and jns_ang='$field'";
        $dpa        = $this->db->query($sqldpa);
        $fieddpa    = $dpa->row();
        $no_dpa      = $fieddpa->no_dpa;

        $nospd_cetak = $lcnospd;
        if ($tnp_no == '1') {
            $con_dpn = '903/';
            $tahun = $this->session->userdata('pcThang');
            $con_blk_btl = '    /BTL/BKAD-B/' . $tahun;
            $con_blk_bl = '     /BL/BKAD-B/' . $tahun;
            if ($ljns_beban == '51') {
                $con_blk_btl = '    /BTL/BKAD-B/' . $tahun;
                $con_blk_bl = '     /BL/BKAD-B/' . $tahun;
            }

            ($ljns_beban == '5' || $ljns_beban == '6') ?  $nospd_cetak = $con_dpn . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $con_blk_btl : $nospd_cetak = $con_dpn . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $con_blk_bl;
        }

        if ($ljns_beban == '52' || $ljns_beban == '51' || $kode == '4.09.01' || $kode == '1.04.02' || $kode == '2.05.01' || $kode == '4.02.02' || $kode == '4.02.03') {
            $nospd_cetak = str_replace('BKAD', 'BKAD', $nospd_cetak);
        } else {
            $nospd_cetak = str_replace('BKAD', 'BPKPD', $nospd_cetak);
        }

        // jumlah anggaran
        if ($ljns_beban == '52' || $ljns_beban == '51' || $kode == '4.09.01' || $kode == '1.04.02' || $kode == '2.05.01' || $kode == '4.02.02' || $kode == '4.02.03' || $kode == '2.17.01' || $kode == '1.02.01') {
            $nospd_cetak = str_replace('2020', '2020', $nospd_cetak);
        } else {
            $nospd_cetak = str_replace('2020', '2019', $nospd_cetak);
        }

        $csql1 = "SELECT SUM(nilai) AS jumlah FROM trdrka a where left(kd_rek6,1)='5' and  left(kd_skpd,22)=left('$lckdskpd',22) and jns_ang='$field'
                
                          ";




        $hasil1 = $this->db->query($csql1);
        $trh2 = $hasil1->row();
        $jmldpa = number_format(($trh2->jumlah), 2, ',', '.'); //number_format(ceil($trh2->jumlah),2,',','.');
        $bilangdpa = $this->tukd_model->terbilang($trh2->jumlah);

        //spd lalu
        // --------------------------------
        $sql1   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                    kd_skpd='$lckdskpd' 
                                    and bulan_akhir='3' 
                                    -- and tgl_spd<='$ldtgl_spd'
                                    ";
        $q1     = $this->db->query($sql1);
        $tw1    = $q1->row();
        $rev1   = $tw1->revisi;
        // --------------------------------
        $sql2   = "SELECT isnull(max(revisi_ke),0) as revisi from trhspd where 
                                    kd_skpd='$lckdskpd' 
                                    and bulan_akhir='6' 
                                    -- and tgl_spd<='$ldtgl_spd'
                                    ";
        $q2     = $this->db->query($sql2);
        $tw2    = $q2->row();
        $rev2   = $tw2->revisi;
        // --------------------------------
        $sql3   = "SELECT isnull(max(revisi_ke),0) as revisi from trhspd where 
                                    kd_skpd='$lckdskpd' 
                                    and bulan_akhir='9' 
                                    -- and tgl_spd<='$ldtgl_spd'
                                    ";
        $q3     = $this->db->query($sql3);
        $tw3    = $q3->row();
        $rev3   = $tw3->revisi;
        // --------------------------------
        $sql4   = "SELECT isnull(max(revisi_ke),0) as revisi from trhspd where 
                                    kd_skpd='$lckdskpd' 
                                    and bulan_akhir='12' 
                                    -- and tgl_spd<='$ldtgl_spd'
                                    ";
        $q4     = $this->db->query($sql4);
        $tw4    = $q4->row();
        $rev4   = $tw4->revisi;


        $twspd = $this->rka_model->get_nama($lcnospd, 'bulan_akhir', 'trhspd', 'no_spd');
        if ($twspd == '3') {
            $sql = " SELECT sum(nilai)as jm_spd_l from (
                            SELECT
                            'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                            FROM
                            trdspd a
                            JOIN trhspd b ON a.no_spd = b.no_spd
                            WHERE
                            left(a.kd_unit,22) = left('$lckdskpd',22)
                            AND b.jns_beban='$ljns_beban'
                            AND b.status = '1'
                            and bulan_akhir='3'
                            and revisi_ke='$rev1'
                            and tgl_spd<='$ldtgl_spd'
                            and a.no_spd<>'$lcnospd'
                            )zz";
        } else if ($twspd == '6') {
            $sql = " SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lckdskpd',22)
                        AND b.jns_beban='$ljns_beban'
                        AND b.status = '1'
                        and bulan_akhir='3'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$ldtgl_spd'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lckdskpd',22)
                        AND b.jns_beban='$ljns_beban'
                        AND b.status = '1'
                        and bulan_akhir='6'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$ldtgl_spd'
                        and a.no_spd<>'$lcnospd'
                        )zz
    
                        ";
        } else if ($twspd == '9') {
            $sql = " SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lckdskpd',22)
                        AND b.jns_beban='$ljns_beban'
                        AND b.status = '1'
                        and bulan_akhir='3'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$ldtgl_spd'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lckdskpd',22)
                        AND b.jns_beban='$ljns_beban'
                        AND b.status = '1'
                        and bulan_akhir='6'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$ldtgl_spd'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lckdskpd',22)
                        AND b.jns_beban='$ljns_beban'
                        AND b.status = '1'
                        and bulan_akhir='9'
                        and revisi_ke='$rev3'
                        and tgl_spd<='$ldtgl_spd'
                        and a.no_spd<>'$lcnospd'
                        )zz
    
                        ";
        } else {
            $sql = " SELECT sum(nilai)as jm_spd_l from (
                        SELECT
                        'TW1' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lckdskpd',22)
                        AND b.jns_beban='$ljns_beban'
                        AND b.status = '1'
                        and bulan_akhir='3'
                        and revisi_ke='$rev1'
                        and tgl_spd<='$ldtgl_spd'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW2' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lckdskpd',22)
                        AND b.jns_beban='$ljns_beban'
                        AND b.status = '1'
                        and bulan_akhir='6'
                        and revisi_ke='$rev2'
                        and tgl_spd<='$ldtgl_spd'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW3' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lckdskpd',22)
                        AND b.jns_beban='$ljns_beban'
                        AND b.status = '1'
                        and bulan_akhir='9'
                        and revisi_ke='$rev3'
                        and tgl_spd<='$ldtgl_spd'
                        and a.no_spd<>'$lcnospd'
                        UNION ALL
                        SELECT
                        'TW4' ket,isnull(SUM(a.nilai),0) AS nilai
                        FROM
                        trdspd a
                        JOIN trhspd b ON a.no_spd = b.no_spd
                        WHERE
                        left(a.kd_unit,22) = left('$lckdskpd',22)
                        AND b.jns_beban='$ljns_beban'
                        AND b.status = '1'
                        and bulan_akhir='12'
                        and revisi_ke='$rev4'
                        and tgl_spd<='$ldtgl_spd'
                        and a.no_spd<>'$lcnospd'
                        )zz
    
                        ";
        }



        $hasil = $this->db->query($sql);
        $trh = $hasil->row();

        $jmlspdlalu = number_format(($trh->jm_spd_l), 2, ',', '.'); //number_format(ceil($trh->jm_spd_l),2,',','.');
        $bilspdlalu = $this->tukd_model->terbilang3($trh->jm_spd_l);


        $csql = "SELECT * from sclient";
        $hasil = $this->db->query($csql);
        $trh3 = $hasil->row();
        $jmlsisa = number_format(($trh2->jumlah - ($trh->jm_spd_l + $trh1->total)), 2, ',', '.');
        $jmlsisa9 = $trh2->jumlah - ($trh->jm_spd_l + $trh1->total);
        // $jmlsisa2 = number_format(($trh2->jumlah - $trh->jm_spd_l - $trh1->total),2,',','.'); //number_format(ceil($trh2->jumlah - $trh->jm_spd_l - $trh1->total),2,',','.');
        //$jmlsisa3 = $trh2->jumlah - $trh->jm_spd_l - $trh1->total;
        // $bilsisa = $this->tukd_model->terbilang($jmlsisa3);

        $sql_sisa = "SELECT SUM(jumlah-jm_spd_l-total) as sisa
                        FROM (select 0 jumlah, 0 jm_spd_l, total total from trhspd where no_spd = '$lcnospd'
                            UNION ALL
                            SELECT SUM(nilai) AS jumlah, 0 jm_spd_l,0 total FROM trdrka WHERE jns_ang='$n_status' and kd_sub_kegiatan IN 
                          (SELECT kd_sub_kegiatan FROM trdspd WHERE no_spd = '$lcnospd') and left(kd_skpd,22) IN (SELECT left(kd_skpd,22) FROM trhspd WHERE no_spd = '$lcnospd')
                          UNION ALL
                            select 0 jumlah, sum(total) as jm_spd_l, 0 total from trhspd where no_spd<>'$lcnospd' 
                        and tgl_spd<='$ldtgl_spd' and kd_skpd='$lckdskpd' and jns_beban='$ljns_beban' ) a
                            ";
        $hasilsisa = $this->db->query($sql_sisa);
        $trhsisa = $hasilsisa->row();
        $jmlsisa3 = $trhsisa->sisa;
        $jmlsisa2 = number_format(($trhsisa->sisa), 2, ',', '.'); //number_format(ceil($trhsisa->sisa),2,',','.');
        $bilsisa = $this->tukd_model->terbilang3($jmlsisa9);

        $csql = "SELECT top 1 * from trkonfig_spd where tgl_konfig_spd<='$ldtgl_spd' order by tgl_konfig_spd desc ";
        $hasil = $this->db->query($csql);
        $trh4 = $hasil->row();

        if ($ljns_beban == '5') {
            $njns = 'Belanja';
        } else if ($ljns_beban == '6') {
            $njns = 'Pembiayaan keluar';
        }


        $xx = 'Bahwa untuk melaksanakan Anggaran ' . $njns . ' sub kegiatan Tahun Anggaran ' . $trh3->thn_ang . ' berdasarkan DPA-SKPD dan anggaran kas yang telah ditetapkan, perlu disediakan pendanaan dengan menerbitkan Surat Penyediaan Dana (SPD); ';
        if ($water == 1) {
            $waters = "style=' background-image: url(" . base_url() . "/image/spd.bmp) ;background-repeat:no-repeat; background-position: 50% 50%;'";
        } else {
            $waters = "";
        }
        $cRet = "<body $waters >";

        $raimu = "PEJABAT PENGELOLA KEUANGAN DAERAH SELAKU BENDAHARA UMUM DAERAH";
        $kepala = "<table width='100%' style='font-size:12px' cellpadding='0px'  cellspacing='0' > 
                        <tr>
                            <td width='90%'><center>
                                PEMERINTAH KABUPATEN MELAWI 
                                <br /> $raimu 
                                <br />NOMOR $nospd_cetak
                                <br /> TENTANG
                                <br /> SURAT PENYEDIAAN DANA ANGGARAN BELANJA DAERAH
                                <br /> TAHUN ANGGARAN $trh3->thn_ang
                                <br />
                                <br />
                                PPKD SELAKU BUD
                                <br />&nbsp;
                            <center></td>
    
                        </tr>
                                                       
                </table>";
        $plt = "";


        // }
        $cRet .= "$kepala";




        $sql = "EXEC otori_spd '$ldtgl_spd'";
        $hasil = $this->db->query($sql);
        $num_row = $hasil->num_rows();
        $font = 10;
        if ($num_row > 10) {
            $font = $font - 2;
        }
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman;; font-size:$font px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                        <tr>
                            <td width=\"3%\" align=\"right\" valign=\"top\">&nbsp;</td>
                            <td width=\"13%\" align=\"left\" valign=\"top\" ><strong>Menimbang</strong></td>
                            <td width=\"2%\" align=\"right\" valign=\"top\">:</td>
                            <td width=\"81%\" align=\"justify\" colspan=\"2\" rowspan=\"2\" valign=\"top\" >$xx</td>
                        </tr>
                        <tr>
                            <td align=\"right\" valign=\"top\">&nbsp;</td>
                            <td align=\"left\" valign=\"top\" >&nbsp;</td>
                            <td align=\"right\" valign=\"top\">&nbsp;</td>
                        </tr>
    
                ";

        $kolom1 = '';
        //$sql = "EXEC otori_spd '$ldtgl_spd'";
        //$hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $cno1 = $row->no1;
            $cket = $row->ket;
            $ctanda = $row->tanda;

            if ($cno1 == '1') {
                $kolom1 = 'Mengingat';
                $kolom2 = ':';
            } else {
                $kolom1 = '';
                $kolom2 = '';
            }

            if ($ctanda == 'F') {
                $ctambah = $no_dpa . ' Tahun Anggaran ' . $trh3->thn_ang . ';';
            } else {
                $ctambah = '';
            }


            $cRet .= "<tr>
                                <td align=\"right\" valign=\"top\" > &nbsp;</td>
                                <td align=\"left\" valign=\"top\"><strong>$kolom1</strong></td>
                                <td align=\"right\" valign=\"top\" >$kolom2</td>
                                <td width=\"2%\" align=\"right\" valign=\"top\" >$cno1.</td>
                                <td width=\"79%\" align=\"justify\" >$cket $ctambah</td>
                            </tr>";
        }
        $cRet .= "</table>";

        $cRet .= "        
                <table style=\"border-collapse:collapse;font-family: arial;  font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                
                    <tr>
                        <td colspan=\"6\" align=\"center\" valign=\"top\" width=\"100%\"  style=\"font-size:12px\"> 
                            <strong>M E M U T U S K A N :<strong>&nbsp;
                        </td>
                    </tr>
                    <tr>
                         
                        <td colspan=\"6\" align=\"left\" valign=\"top\" width=\"90%\"  style=\"font-size:12px\">
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$trh4->memutuskan &nbsp;
                        </td>
                    </tr>
                    <tr>
                        
                        <td width=\"3%\"   style=\"font-size:12px\">1.
                        </td>
                        <td width=\"35%\"  style=\"font-size:12px\">Dasar penyediaan dana:
                        </td>
                        <td  width=\"2%\" style=\"font-size:12px\">
                        </td>
                        <td  width=\"50%\" colspan=\"3\"   style=\"font-size:12px\">
                        </td>
                    </tr>
                    <tr>
                        
                        <td width=\"3%\"   style=\"font-size:12px\">
                        </td>
                        <td width=\"35%\"  style=\"font-size:12px\">DPA-SKPD
                        </td>
                        <td  width=\"2%\" style=\"font-size:12px\">:
                        </td>
                        <td  width=\"50%\" colspan=\"3\"   style=\"font-size:12px\">$no_dpa
                        </td>
                    </tr>
                    <tr>
                        
                        <td width=\"3%\"   style=\"font-size:12px\">2.
                        </td>
                        <td width=\"35%\"  style=\"font-size:12px\">Ditujukan kepada SKPD
                        </td>
                        <td  width=\"2%\" style=\"font-size:12px\">:
                        </td>
                        <td  width=\"50%\" colspan=\"3\"   style=\"font-size:12px\">$lcnmskpd
                        </td>
                    </tr>
                    <tr>
                        
                        <td style=\"font-size:12px\" valign=\"top\">3.
                        </td>
                        <td style=\"font-size:12px\" valign=\"top\">Kepala SKPD 
                        </td>
                        <td  style=\"font-size:12px\" valign=\"top\">:
                        </td>
                        <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">$nama1
                        </td>
                    </tr>
                    <tr>
                        
                        <td rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">4.
                        </td>
                        <td rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">Jumlah Penyediaan dana
                        </td>
                        <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                        </td>
                        <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlspdini
                        </td>
                    </tr>
                    <tr>
                        <td  colspan=\"3\" style=\"font-size:12px\"><i>($biljmlini)</i></td>
                    </tr>
                    <tr>
                        
                        <td style=\"font-size:12px\">5.
                        </td>
                        <td style=\"font-size:12px\">Untuk Kebutuhan
                        </td>
                        <td  style=\"font-size:12px\">:
                        </td>
                        <td  colspan=\"3\"   style=\"font-size:12px\">$tambah Bulan $blnini s/d $blnsd
                        </td>
                    </tr>
                    <tr>
                        
                        <td style=\"font-size:12px\">6.
                        </td>
                        <td style=\"font-size:12px\">Ikhtisar penyediaan dana :
                        </td>
                        <td  style=\"font-size:12px\">
                        </td>
                        <td  colspan=\"3\"   style=\"font-size:12px\">
                        </td>
                    </tr>
    
                    <tr>
                        
                        <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                        </td>
                        <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">a. Jumlah dana DPA-SKPD
                        </td>
                        <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                        </td>
                        <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmldpa
                        </td>
    
                    </tr>
                    <tr>
                        <td  colspan=\"3\" style=\"font-size:12px\"><i>($bilangdpa)</i></td>
                    </tr>
    
                    <tr>
                        
                        <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                        </td>
                        <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">b. Akumulasi SPD sebelumnya
                        </td>
                        <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                        </td>
                        <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlspdlalu
                        </td>
    
                    </tr>
                    <tr>
                        <td  colspan=\"3\" style=\"font-size:12px\"><i>($bilspdlalu)</i></td>
                    </tr>
    
    
                    <tr>
                        
                        <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                        </td>
                        <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">c. Jumlah dana yang di-SPD-kan saat ini
                        </td>
                        <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                        </td>
                        <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlspdini
                        </td>
    
                    </tr>
                    <tr>
                        <td  colspan=\"3\" style=\"font-size:12px\"><i>($biljmlini)</i></td>
                    </tr>
    
    
                    <tr>
                        
                        <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                        </td>
                        <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">d. Sisa jumlah dana DPA-SKPD yang belum di-SPD-kan
                        </td>
                        <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                        </td>
                        <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlsisa
                        </td>
    
                    </tr>
                    <tr>
                        <td  colspan=\"3\" style=\"font-size:12px\"><i>($bilsisa)</i></td>
                    </tr>
                    <tr> 
                        
                        <td style=\"font-size:12px\" align=\"right\" valign=\"top\">7.
                        </td>
                        <td style=\"font-size:12px\" valign=\"top\">Ketentuan-ketentuan lain
                        </td>
                        <td style=\"font-size:12px\" valign=\"top\">:
                        </td>
                        <td  colspan=\"3\" align=\"justify\" style=\"font-size:12px\"> $klain
                        </td>
                    </tr>           
                    </table>";
        // CETAKAN TANDA TANGAN by Tox
        $cRet .= "
                     <table style=\"border-collapse:collapse;font-weight:none;font-family: arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
                
                 
                    <tr >
                        <td width=\"40%\" align=\"right\">&nbsp;</td>
                        <td width=\"60%\"  align=\"center\" colspan=\"2\" >Ditetapkan di Melawi
                        </td>
                    </tr>
                <tr >
                        <td width=\"40%\" align=\"right\">&nbsp;</td>
                        <td width=\"60%\"  align=\"center\" colspan=\"2\" >Pada tanggal " . $this->support->tanggal_format_indonesia($ldtgl_spd) . "</td>
                        </td>
                    </tr> 
                    <tr >
                        <td width=\"40%\" align=\"right\">&nbsp;</td>
                        <td width=\"60%\"  align=\"center\" colspan=\"2\" style=\"text-transform: uppercase;\">PPKD SELAKU BUD,<br />&nbsp;<br>&nbsp;<br>&nbsp;
                        </td>
                    </tr>   
                <tr >
                        <td align=\"right\">&nbsp;</td>
                        <td  align=\"center\" colspan=\"2\"><u>$nama_ppkd</u></td>
                        </td>
                  
                <tr >
                        <td  align=\"right\">&nbsp;</td>
                        <td align=\"center\" colspan=\"2\">NIP. $nip_ppkd</td>
                        </td>
                    </tr> 
                    <tr >
                        <td  align=\"left\">Tembusan disampaikan kepada:</td>
                        <td align=\"center\" colspan=\"2\">&nbsp;</td>
                        </td>
                    </tr> 
                    <tr >
                        <td  align=\"left\">1. Inspektur *)<br >2. Arsip</td>
                        <td align=\"center\" colspan=\"2\">&nbsp;</td>
                        </td>
                    </tr> 
                    <tr >
                        <td  align=\"left\">*) Coret yang tidak perlu</td>
                        <td align=\"center\" colspan=\"2\">&nbsp;</td>
                        </td>
                    </tr>           
                </table>";


        $data['prev'] = $cRet;


        if ($print == 1) {
            // $this->rka_model->_mpdf_folio('',$cRet,10,10,10,'0');
            $this->rka_model->_mpdf('', $cRet, 10, 10, 10, '0', 'no', '', '', $kop);
        } else {
            echo $cRet;
        }
    }

    function cetak_otor_spd_biaya()
    {

        $print = $this->uri->segment(3);
        $tnp_no = $this->uri->segment(4);
        // $kop = $this->input->post('kop');
        $kop = "";
        $water = $this->input->post('water');
        $water = "";

        $nip            = $this->uri->segment(8);;
        $nip_ppkd = str_replace('123456789', ' ', $nip);
        // $kop            = $this->input->post('kop');
        // $lntahunang     = $this->session->userdata('pcThang');       
        $nos            = $this->uri->segment(7);
        $lcnospd = str_replace('spd', '/', $nos);
        // $lcnospd        = $this->input->post('nomor1');



        $tambah = $this->uri->segment(5) == '0' ? '' : $this->uri->segment(4);

        // $lcnospd = $this->input->post('nomor1');
        //echo $lcnospd;
        // $nip_ppkd = $this->input->post('nip_ppkd');  
        $nama_ppkd = $this->rka_model->get_nama($nip_ppkd, 'nama', 'ms_ttd', 'nip');
        $jabatan_ppkd = $this->rka_model->get_nama($nip_ppkd, 'jabatan', 'ms_ttd', 'nip');
        $pangkat_ppkd = $this->rka_model->get_nama($nip_ppkd, 'pangkat', 'ms_ttd', 'nip');
        // $csql2 = "SELECT nm_skpd,kd_skpd,tgl_spd,total,bulan_awal,bulan_akhir,jns_beban,kd_bkeluar,(select TOP 1 nama from ms_ttd where replace(nip,' ','')=replace(kd_bkeluar,' ',''))as nama1,klain from trhspd where no_spd ='$lcnospd'";
        $csql2 = "SELECT a.nm_skpd as nm_skpd, a.kd_skpd as kd_skpd,a.tgl_spd as tgl_spd,sum(nilai) as total,a.bulan_awal as bulan_awal,a.bulan_akhir as bulan_akhir,a.jns_beban as jns_beban, a.kd_bkeluar as kd_bkeluar,
            (select TOP 1 nama from ms_ttd where replace(nip,' ','')=replace(kd_bkeluar,' ',''))as nama1,a.klain as klain 
            from trhspd  a inner join trdspd b on b.no_spd=a.no_spd where a.no_spd ='$lcnospd' GROUP BY a.nm_skpd, a.kd_skpd, a.tgl_spd, a.bulan_awal, a.bulan_akhir, a.jns_beban, a.kd_bkeluar, a.klain";

        $hasil1 = $this->db->query($csql2);
        $trh1 = $hasil1->row();
        $ldtgl_spd = $trh1->tgl_spd;
        $klain = $trh1->klain;
        $jmlspdini = number_format(($trh1->total), 2, ',', '.'); //number_format(ceil($trh1->total),2,',','.');;
        $biljmlini = $this->tukd_model->terbilang(($trh1->total));
        $lckdskpd = $trh1->kd_skpd;
        $blnini = $this->rka_model->getBulan($trh1->bulan_awal);
        $blnsd = $this->rka_model->getBulan($trh1->bulan_akhir);
        $lcnmskpd = $trh1->nm_skpd;
        $skpd = $trh1->kd_skpd;
        $ljns_beban = $trh1->jns_beban;
        $lcnipbk = $trh1->kd_bkeluar;
        $data = $this->cek_anggaran_model->cek_anggaran($skpd);
        // if ($lcnipbk<>''){         
        //     $sqlttd1="SELECT nama as nm FROM ms_ttd WHERE nip='$lcnipbk'";
        //          $sqlttd=$this->db->query($sqlttd1);
        //          foreach ($sqlttd->result() as $rowttd)
        //         {
        $nama1 = $trh1->nama1;
        // }
        // }

        // }
        // else{
        //             $nama1= '';
        // }   echo $sqlttd1;

        $kode = substr($lckdskpd, 0, -3);
        $n_status = $this->get_status2($lckdskpd);

        //  if ($n_status=='nilai_ubah'){
        $no_dpa = $this->rka_model->get_nama($skpd, 'no_dpa', 'trhrka', 'kd_skpd');
        //  }else if($n_status=='nilai_sempurna'){
        //     $no_dpa=$this->rka_model->get_nama($skpd,'no_dpa_sempurna','trhrka','kd_skpd');
        //  }else{
        //     $no_dpa=$this->rka_model->get_nama($skpd,'no_dpa','trhrka','kd_skpd');
        //  }

        $nospd_cetak = $lcnospd;
        if ($tnp_no == '1') {
            $con_dpn = '903/';
            $tahun = $this->session->userdata('pcThang');
            $con_blk_btl = '    /BTL/BKAD-B/' . $tahun;
            $con_blk_bl = '     /BL/BKAD-B/' . $tahun;
            if ($ljns_beban == '51') {
                $con_blk_btl = '    /BTL/BKAD-B/' . $tahun;
                $con_blk_bl = '     /BL/BKAD-B/' . $tahun;
            }

            ($ljns_beban == '5' || $ljns_beban == '6') ?  $nospd_cetak = $con_dpn . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $con_blk_btl : $nospd_cetak = $con_dpn . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $con_blk_bl;
        }

        if ($ljns_beban == '52' || $ljns_beban == '51' || $kode == '4.09.01' || $kode == '1.04.02' || $kode == '2.05.01' || $kode == '4.02.02' || $kode == '4.02.03') {
            $nospd_cetak = str_replace('BKAD', 'BKAD', $nospd_cetak);
        } else {
            $nospd_cetak = str_replace('BKAD', 'BPKPD', $nospd_cetak);
        }

        // jumlah anggaran
        if ($ljns_beban == '52' || $ljns_beban == '51' || $kode == '4.09.01' || $kode == '1.04.02' || $kode == '2.05.01' || $kode == '4.02.02' || $kode == '4.02.03' || $kode == '2.17.01' || $kode == '1.02.01') {
            $nospd_cetak = str_replace('2020', '2020', $nospd_cetak);
        } else {
            $nospd_cetak = str_replace('2020', '2019', $nospd_cetak);
        }

        $csql1 = "SELECT SUM(nilai) AS jumlah FROM trdrka a where jns_ang='$data' and left(kd_rek6,4)='6202' and  left(kd_skpd,22)=left('$lckdskpd',22)
        
                  ";


        $hasil1 = $this->db->query($csql1);
        $trh2 = $hasil1->row();
        $jmldpa = number_format(($trh2->jumlah), 2, ',', '.'); //number_format(ceil($trh2->jumlah),2,',','.');
        $bilangdpa = $this->tukd_model->terbilang($trh2->jumlah);

        //spd lalu konsep baru

        $sql1   = "SELECT max(revisi_ke) as revisi from trhspd where 
                            kd_skpd='$lckdskpd' 
                            and bulan_akhir='1'";
        $q1     = $this->db->query($sql1);
        $w1     = $q1->row();
        $rev1   = $w1->revisi;

        //--------
        $sql2   = "SELECT max(revisi_ke) as revisi from trhspd where 
                            kd_skpd='$lckdskpd' 
                            and bulan_akhir='2'";
        $q2     = $this->db->query($sql2);
        $w2     = $q2->row();
        $rev2   = $w2->revisi;

        //--------
        $sql3   = "SELECT max(revisi_ke) as revisi from trhspd where 
                            kd_skpd='$lckdskpd' 
                            and bulan_akhir='3'";
        $q3     = $this->db->query($sql3);
        $w3     = $q3->row();
        $rev3   = $w3->revisi;

        //--------
        $sql4   = "SELECT max(revisi_ke) as revisi from trhspd where 
                            kd_skpd='$lckdskpd' 
                            and bulan_akhir='4'";
        $q4     = $this->db->query($sql4);
        $w4     = $q4->row();
        $rev4   = $w4->revisi;

        //--------
        $sql5   = "SELECT max(revisi_ke) as revisi from trhspd where 
                            kd_skpd='$lckdskpd' 
                            and bulan_akhir='5'";
        $q5     = $this->db->query($sql5);
        $w5     = $q5->row();
        $rev5   = $w5->revisi;

        //--------
        $sql6   = "SELECT max(revisi_ke) as revisi from trhspd where 
                            kd_skpd='$lckdskpd' 
                            and bulan_akhir='6'";
        $q6     = $this->db->query($sql6);
        $w6     = $q6->row();
        $rev6   = $w6->revisi;

        //--------
        $sql7   = "SELECT max(revisi_ke) as revisi from trhspd where 
                            kd_skpd='$lckdskpd' 
                            and bulan_akhir='7'";
        $q7     = $this->db->query($sql7);
        $w7     = $q7->row();
        $rev7   = $w7->revisi;

        //--------
        $sql8   = "SELECT max(revisi_ke) as revisi from trhspd where 
                            kd_skpd='$lckdskpd' 
                            and bulan_akhir='8'";
        $q8     = $this->db->query($sql8);
        $w8     = $q8->row();
        $rev8   = $w8->revisi;

        //--------
        $sql9   = "SELECT max(revisi_ke) as revisi from trhspd where 
                            kd_skpd='$lckdskpd' 
                            and bulan_akhir='9'";
        $q9     = $this->db->query($sql9);
        $w9     = $q9->row();
        $rev9   = $w9->revisi;

        //--------
        $sql10   = "SELECT max(revisi_ke) as revisi from trhspd where 
                            kd_skpd='$lckdskpd' 
                            and bulan_akhir='10'";
        $q10     = $this->db->query($sql10);
        $w10     = $q10->row();
        $rev10   = $w10->revisi;

        //--------
        $sql11   = "SELECT max(revisi_ke) as revisi from trhspd where 
                            kd_skpd='$lckdskpd' 
                            and bulan_akhir='11'";
        $q11     = $this->db->query($sql11);
        $w11     = $q11->row();
        $rev11   = $w11->revisi;

        //--------
        $sql12   = "SELECT max(revisi_ke) as revisi from trhspd where 
                            kd_skpd='$lckdskpd' 
                            and bulan_akhir='12'";
        $q12     = $this->db->query($sql12);
        $w12     = $q12->row();
        $rev12   = $w12->revisi;

        //--------
        $bulanspd = $this->rka_model->get_nama($lcnospd, 'bulan_akhir', 'trhspd', 'no_spd');


        $sql = "SELECT sum(nilai)as jm_spd_l from (
                
                SELECT
                'W1' ket,isnull(SUM(a.nilai),0) AS nilai
                FROM
                trdspd a
                JOIN trhspd b ON a.no_spd = b.no_spd
                WHERE
                b.kd_skpd = '$lckdskpd'
                AND b.jns_beban='$ljns_beban'
                AND b.status = '1'
                and bulan_akhir='1'
                and revisi_ke='$rev1'
                and bulan_akhir <=$bulanspd
                and a.no_spd<>'$lcnospd'
                
                UNION ALL
                SELECT
                'W2' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                FROM
                trdspd a
                JOIN trhspd b ON a.no_spd = b.no_spd
                WHERE
                b.kd_skpd = '$lckdskpd'
                AND b.jns_beban='$ljns_beban'
                AND b.status = '1'
                and bulan_akhir='2'
                and revisi_ke='$rev2'
                and bulan_akhir <=$bulanspd
                and a.no_spd<>'$lcnospd'
                UNION ALL
                SELECT
                'W3' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                FROM
                trdspd a
                JOIN trhspd b ON a.no_spd = b.no_spd
                WHERE
                b.kd_skpd = '$lckdskpd'
                AND b.jns_beban='$ljns_beban'
                AND b.status = '1'
                and bulan_akhir='3'
                and revisi_ke='$rev3'
                and bulan_akhir <=$bulanspd
                and a.no_spd<>'$lcnospd'
                UNION ALL
                SELECT
                'W4' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                FROM
                trdspd a
                JOIN trhspd b ON a.no_spd = b.no_spd
                WHERE
                b.kd_skpd = '$lckdskpd'
                AND b.jns_beban='$ljns_beban'
                AND b.status = '1'
                and bulan_akhir='4'
                and revisi_ke='$rev4'
                and bulan_akhir <=$bulanspd
                and a.no_spd<>'$lcnospd'
                UNION ALL
                SELECT
                'W5' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                FROM
                trdspd a
                JOIN trhspd b ON a.no_spd = b.no_spd
                WHERE
                b.kd_skpd = '$lckdskpd'
                AND b.jns_beban='$ljns_beban'
                AND b.status = '1'
                and bulan_akhir='5'
                and revisi_ke='$rev5'
                and bulan_akhir <=$bulanspd
                and a.no_spd<>'$lcnospd'
                UNION ALL
                SELECT
                'W6' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                FROM
                trdspd a
                JOIN trhspd b ON a.no_spd = b.no_spd
                WHERE
                b.kd_skpd = '$lckdskpd'
                AND b.jns_beban='$ljns_beban'
                AND b.status = '1'
                and bulan_akhir='6'
                and revisi_ke='$rev6'
                and bulan_akhir <=$bulanspd
                and a.no_spd<>'$lcnospd'
                UNION ALL
                SELECT
                'W7' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                FROM
                trdspd a
                JOIN trhspd b ON a.no_spd = b.no_spd
                WHERE
                b.kd_skpd = '$lckdskpd'
                AND b.jns_beban='$ljns_beban'
                AND b.status = '1'
                and bulan_akhir='7'
                and revisi_ke='$rev7'
                and bulan_akhir <=$bulanspd
                and a.no_spd<>'$lcnospd'
                UNION ALL
                SELECT
                'W8' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                FROM
                trdspd a
                JOIN trhspd b ON a.no_spd = b.no_spd
                WHERE
                b.kd_skpd = '$lckdskpd'
                AND b.jns_beban='$ljns_beban'
                AND b.status = '1'
                and bulan_akhir='8'
                and revisi_ke='$rev8'
                and bulan_akhir <=$bulanspd
                and a.no_spd<>'$lcnospd'
                UNION ALL
                SELECT
                'W9' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                FROM
                trdspd a
                JOIN trhspd b ON a.no_spd = b.no_spd
                WHERE
                b.kd_skpd = '$lckdskpd'
                AND b.jns_beban='$ljns_beban'
                AND b.status = '1'
                and bulan_akhir='9'
                and revisi_ke='$rev9'
                and bulan_akhir <=$bulanspd
                and a.no_spd<>'$lcnospd'
                UNION ALL
                SELECT
                'W10' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                FROM
                trdspd a
                JOIN trhspd b ON a.no_spd = b.no_spd
                WHERE
                b.kd_skpd = '$lckdskpd'
                AND b.jns_beban='$ljns_beban'
                AND b.status = '1'
                and bulan_akhir='10'
                and revisi_ke='$rev10'
                and bulan_akhir <=$bulanspd
                and a.no_spd<>'$lcnospd'
                UNION ALL
                SELECT
                'W11' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                FROM
                trdspd a
                JOIN trhspd b ON a.no_spd = b.no_spd
                WHERE
                b.kd_skpd = '$lckdskpd'
                AND b.jns_beban='$ljns_beban'
                AND b.status = '1'
                and bulan_akhir='11'
                and revisi_ke='$rev11'
                and bulan_akhir <=$bulanspd
                and a.no_spd<>'$lcnospd'
                UNION ALL
                SELECT
                'W12' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                FROM
                trdspd a
                JOIN trhspd b ON a.no_spd = b.no_spd
                WHERE
                b.kd_skpd = '$lckdskpd'
                AND b.jns_beban='$ljns_beban'
                AND b.status = '1'
                and bulan_akhir='12'
                and revisi_ke='$rev12'
                and bulan_akhir <=$bulanspd
                and a.no_spd<>'$lcnospd'
                )zz";


        $hasil = $this->db->query($sql);
        $trh = $hasil->row();

        $jmlspdlalu = number_format(($trh->jm_spd_l), 2, ',', '.'); //number_format(ceil($trh->jm_spd_l),2,',','.');
        $bilspdlalu = $this->tukd_model->terbilang($trh->jm_spd_l);


        $csql = "SELECT * from sclient";
        $hasil = $this->db->query($csql);
        $trh3 = $hasil->row();
        $jmlsisa = number_format(($trh2->jumlah - ($trh->jm_spd_l + $trh1->total)), 2, ',', '.');
        $jmlsisa9 = $trh2->jumlah - ($trh->jm_spd_l + $trh1->total);
        // $jmlsisa2 = number_format(($trh2->jumlah - $trh->jm_spd_l - $trh1->total),2,',','.'); //number_format(ceil($trh2->jumlah - $trh->jm_spd_l - $trh1->total),2,',','.');
        //$jmlsisa3 = $trh2->jumlah - $trh->jm_spd_l - $trh1->total;
        // $bilsisa = $this->tukd_model->terbilang($jmlsisa3);

        $sql_sisa = "SELECT SUM(jumlah-jm_spd_l-total) as sisa
                FROM (select 0 jumlah, 0 jm_spd_l, total total from trhspd where no_spd = '$lcnospd'
                    UNION ALL
                    SELECT SUM(nilai) AS jumlah, 0 jm_spd_l,0 total FROM trdrka WHERE jns_ang='$data' and kd_sub_kegiatan IN 
                  (SELECT kd_sub_kegiatan FROM trdspd WHERE no_spd = '$lcnospd') and left(kd_skpd,17) IN (SELECT left(kd_skpd,17) FROM trhspd WHERE no_spd = '$lcnospd')
                  UNION ALL
                    select 0 jumlah, sum(total) as jm_spd_l, 0 total from trhspd where no_spd<>'$lcnospd' 
                and tgl_spd<='$ldtgl_spd' and kd_skpd='$lckdskpd' and jns_beban='$ljns_beban' ) a
                    ";
        $hasilsisa = $this->db->query($sql_sisa);
        $trhsisa = $hasilsisa->row();
        $jmlsisa3 = $trhsisa->sisa;
        $jmlsisa2 = number_format(($trhsisa->sisa), 2, ',', '.'); //number_format(ceil($trhsisa->sisa),2,',','.');
        $bilsisa = $this->tukd_model->terbilang($jmlsisa9);

        $csql = "SELECT top 1 * from trkonfig_spd where tgl_konfig_spd<='$ldtgl_spd' order by tgl_konfig_spd desc ";
        $hasil = $this->db->query($csql);
        $trh4 = $hasil->row();

        if ($ljns_beban == '5') {
            $njns = 'Belanja';
        } else if ($ljns_beban == '6') {
            $njns = 'Pembiayaan keluar';
        }


        $xx = 'Bahwa untuk melaksanakan Anggaran ' . $njns . ' sub kegiatan Tahun Anggaran ' . $trh3->thn_ang . ' berdasarkan DPA-SKPD dan anggaran kas yang telah ditetapkan, perlu disediakan pendanaan dengan menerbitkan Surat Penyediaan Dana (SPD); ';
        if ($water == 1) {
            $waters = "style=' background-image: url(" . base_url() . "/image/spd.bmp) ;background-repeat:no-repeat; background-position: 50% 50%;'";
        } else {
            $waters = "";
        }
        $cRet = "<body $waters >";

        $raimu = "PEJABAT PENGELOLA KEUANGAN DAERAH SELAKU BENDAHARA UMUM DAERAH";
        $kepala = "<table width='100%' style='font-size:12px' cellpadding='0px'  cellspacing='0' > 
                <tr>
                    <td width='90%'><center>
                        PEMERINTAH KABUPATEN MELAWI
                        <br /> $raimu 
                        <br />NOMOR $nospd_cetak
                        <br /> TENTANG
                        <br /> SURAT PENYEDIAAN DANA ANGGARAN BELANJA DAERAH
                        <br /> TAHUN ANGGARAN $trh3->thn_ang
                        <br />
                        <br />
                        PPKD SELAKU BUD
                        <br />&nbsp;
                    <center></td>

                </tr>
                                               
        </table>";
        $plt = "";


        // }
        $cRet .= "$kepala";




        $sql = "EXEC otori_spd '$ldtgl_spd'";
        $hasil = $this->db->query($sql);
        $num_row = $hasil->num_rows();
        $font = 10;
        if ($num_row > 10) {
            $font = $font - 2;
        }
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman;; font-size:$font px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                <tr>
                    <td width=\"3%\" align=\"right\" valign=\"top\">&nbsp;</td>
                    <td width=\"13%\" align=\"left\" valign=\"top\" ><strong>Menimbang</strong></td>
                    <td width=\"2%\" align=\"right\" valign=\"top\">:</td>
                    <td width=\"81%\" align=\"justify\" colspan=\"2\" rowspan=\"2\" valign=\"top\" >$xx</td>
                </tr>
                <tr>
                    <td align=\"right\" valign=\"top\">&nbsp;</td>
                    <td align=\"left\" valign=\"top\" >&nbsp;</td>
                    <td align=\"right\" valign=\"top\">&nbsp;</td>
                </tr>

        ";

        $kolom1 = '';
        //$sql = "EXEC otori_spd '$ldtgl_spd'";
        //$hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $cno1 = $row->no1;
            $cket = $row->ket;
            $ctanda = $row->tanda;

            if ($cno1 == '1') {
                $kolom1 = 'Mengingat';
                $kolom2 = ':';
            } else {
                $kolom1 = '';
                $kolom2 = '';
            }

            if ($ctanda == 'F') {
                $ctambah = $no_dpa . ' Tahun Anggaran ' . $trh3->thn_ang . ';';
            } else {
                $ctambah = '';
            }


            $cRet .= "<tr>
                        <td align=\"right\" valign=\"top\" > &nbsp;</td>
                        <td align=\"left\" valign=\"top\"><strong>$kolom1</strong></td>
                        <td align=\"right\" valign=\"top\" >$kolom2</td>
                        <td width=\"2%\" align=\"right\" valign=\"top\" >$cno1.</td>
                        <td width=\"79%\" align=\"justify\" >$cket $ctambah</td>
                    </tr>";
        }
        $cRet .= "</table>";

        $cRet .= "        
        <table style=\"border-collapse:collapse;font-family: arial;  font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
        
            <tr>
                <td colspan=\"6\" align=\"center\" valign=\"top\" width=\"100%\"  style=\"font-size:12px\"> 
                    <strong>M E M U T U S K A N :<strong>&nbsp;
                </td>
            </tr>
            <tr>
                 
                <td colspan=\"6\" align=\"left\" valign=\"top\" width=\"90%\"  style=\"font-size:12px\">
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$trh4->memutuskan &nbsp;
                </td>
            </tr>
            <tr>
                
                <td width=\"3%\"   style=\"font-size:12px\">1.
                </td>
                <td width=\"35%\"  style=\"font-size:12px\">Dasar penyediaan dana:
                </td>
                <td  width=\"2%\" style=\"font-size:12px\">
                </td>
                <td  width=\"50%\" colspan=\"3\"   style=\"font-size:12px\">
                </td>
            </tr>
            <tr>
                
                <td width=\"3%\"   style=\"font-size:12px\">
                </td>
                <td width=\"35%\"  style=\"font-size:12px\">DPA-SKPD
                </td>
                <td  width=\"2%\" style=\"font-size:12px\">:
                </td>
                <td  width=\"50%\" colspan=\"3\"   style=\"font-size:12px\">$no_dpa
                </td>
            </tr>
            <tr>
                
                <td width=\"3%\"   style=\"font-size:12px\">2.
                </td>
                <td width=\"35%\"  style=\"font-size:12px\">Ditujukan kepada SKPD
                </td>
                <td  width=\"2%\" style=\"font-size:12px\">:
                </td>
                <td  width=\"50%\" colspan=\"3\"   style=\"font-size:12px\">$lcnmskpd
                </td>
            </tr>
            <tr>
                
                <td style=\"font-size:12px\" valign=\"top\">3.
                </td>
                <td style=\"font-size:12px\" valign=\"top\">Kepala SKPD 
                </td>
                <td  style=\"font-size:12px\" valign=\"top\">:
                </td>
                <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">$nama1
                </td>
            </tr>
            <tr>
                
                <td rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">4.
                </td>
                <td rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">Jumlah Penyediaan dana
                </td>
                <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                </td>
                <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlspdini
                </td>
            </tr>
            <tr>
                <td  colspan=\"3\" style=\"font-size:12px\"><i>($biljmlini)</i></td>
            </tr>
            <tr>
                
                <td style=\"font-size:12px\">5.
                </td>
                <td style=\"font-size:12px\">Untuk Kebutuhan
                </td>
                <td  style=\"font-size:12px\">:
                </td>
                <td  colspan=\"3\"   style=\"font-size:12px\">$tambah Bulan $blnini s/d $blnsd
                </td>
            </tr>
            <tr>
                
                <td style=\"font-size:12px\">5.
                </td>
                <td style=\"font-size:12px\">Ikhtisar penyediaan dana :
                </td>
                <td  style=\"font-size:12px\">
                </td>
                <td  colspan=\"3\"   style=\"font-size:12px\">
                </td>
            </tr>

            <tr>
                
                <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                </td>
                <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">a. Jumlah dana DPA-SKPD
                </td>
                <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                </td>
                <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmldpa
                </td>

            </tr>
            <tr>
                <td  colspan=\"3\" style=\"font-size:12px\"><i>($bilangdpa)</i></td>
            </tr>

            <tr>
                
                <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                </td>
                <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">b. Akumulasi SPD sebelumnya
                </td>
                <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                </td>
                <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlspdlalu
                </td>

            </tr>
            <tr>
                <td  colspan=\"3\" style=\"font-size:12px\"><i>($bilspdlalu)</i></td>
            </tr>


            <tr>
                
                <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                </td>
                <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">c. Jumlah dana yang di-SPD-kan saat ini
                </td>
                <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                </td>
                <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlspdini
                </td>

            </tr>
            <tr>
                <td  colspan=\"3\" style=\"font-size:12px\"><i>($biljmlini)</i></td>
            </tr>


            <tr>
                
                <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                </td>
                <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">d. Sisa jumlah dana DPA-SKPD yang belum di-SPD-kan
                </td>
                <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                </td>
                <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlsisa
                </td>

            </tr>
            <tr>
                <td  colspan=\"3\" style=\"font-size:12px\"><i>($bilsisa)</i></td>
            </tr>
            <tr> 
                
                <td style=\"font-size:12px\" align=\"right\" valign=\"top\">6.
                </td>
                <td style=\"font-size:12px\" valign=\"top\">Ketentuan-ketentuan lain
                </td>
                <td style=\"font-size:12px\" valign=\"top\">:
                </td>
                <td  colspan=\"3\" align=\"justify\" style=\"font-size:12px\"> $klain
                </td>
            </tr>           
            </table>";
        // CETAKAN TANDA TANGAN by Tox
        $cRet .= "
             <table style=\"border-collapse:collapse;font-weight:none;font-family: arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
        
         
            <tr >
                <td width=\"40%\" align=\"right\">&nbsp;</td>
                <td width=\"60%\"  align=\"center\" colspan=\"2\" >Ditetapkan di Nanga Pinoh
                </td>
            </tr>
        <tr >
                <td width=\"40%\" align=\"right\">&nbsp;</td>
                <td width=\"60%\"  align=\"center\" colspan=\"2\" >Pada tanggal " . $this->support->tanggal_format_indonesia($ldtgl_spd) . "</td>
                </td>
            </tr> 
            <tr >
                <td width=\"40%\" align=\"right\">&nbsp;</td>
                <td width=\"60%\"  align=\"center\" colspan=\"2\" style=\"text-transform: uppercase;\">PPKD SELAKU BUD,<br />&nbsp;<br>&nbsp;<br>&nbsp;
                </td>
            </tr>   
        <tr >
                <td align=\"right\">&nbsp;</td>
                <td  align=\"center\" colspan=\"2\"><u>$nama_ppkd</u></td>
                </td>
          
        <tr >
                <td  align=\"right\">&nbsp;</td>
                <td align=\"center\" colspan=\"2\">NIP. $nip_ppkd</td>
                </td>
            </tr> 
            <tr >
                <td  align=\"left\">Tembusan disampaikan kepada:</td>
                <td align=\"center\" colspan=\"2\">&nbsp;</td>
                </td>
            </tr> 
            <tr >
                <td  align=\"left\">1. Inspektur *)<br >2. Arsip</td>
                <td align=\"center\" colspan=\"2\">&nbsp;</td>
                </td>
            </tr> 
            <tr >
                <td  align=\"left\">*) Coret yang tidak perlu</td>
                <td align=\"center\" colspan=\"2\">&nbsp;</td>
                </td>
            </tr>           
        </table>";


        // $data['prev']= $cRet;


        if ($print == 1) {
            // $this->rka_model->_mpdf_folio('',$cRet,10,10,10,'0');
            $this->rka_model->_mpdf('', $cRet, 10, 10, 10, '0', 'no', '', '', $kop);
        } else {
            echo $cRet;
        }
    }

    //-----------------------
    function cetak_otor_spd_lama()
    {

        $print = $this->input->get('jenis');
        $tnp_no = $this->input->get('tanpa_nomor');
        $kop = $this->input->get('kop');
        $water = $this->input->get('watermark');
        //echo ($water);
        $tambah = $this->uri->segment(4) == '0' ? '' : $this->uri->segment(4);
        $lcnospd = $this->input->get('no_spd');
        //echo $lcnospd;
        $nip_ppkd = $this->input->get('nip_ppkd');
        $nama_ppkd = $this->input->get('nama_ppkd');
        $jabatan_ppkd = $this->input->get('jabatan_ppkd');
        $pangkat_ppkd = $this->input->get('pangkat_ppkd');
        $csql2 = "select nm_skpd,kd_skpd,tgl_spd,total,bulan_awal,bulan_akhir,jns_beban,kd_bkeluar,klain from trhspd where no_spd = '$lcnospd'  ";
        $hasil1 = $this->db->query($csql2);
        $trh1 = $hasil1->row();
        $ldtgl_spd = $trh1->tgl_spd;
        $klain = $trh1->klain;
        $jmlspdini = number_format(($trh1->total), 2, ',', '.'); //number_format(ceil($trh1->total),2,',','.');;
        $biljmlini = $this->tukd_model->terbilang(($trh1->total));
        $lckdskpd = $trh1->kd_skpd;
        $blnini = $this->rka_model->getBulan($trh1->bulan_awal);
        $blnsd = $this->rka_model->getBulan($trh1->bulan_akhir);
        $lcnmskpd = $trh1->nm_skpd;
        $skpd = $trh1->kd_skpd;
        $data = $this->cek_anggaran_model->cek_anggaran($skpd);
        $ljns_beban = $trh1->jns_beban;
        $lcnipbk = $trh1->kd_bkeluar;

        if ($lcnipbk <> '') {
            $sqlttd1 = "SELECT nama as nm FROM ms_ttd WHERE nip='$lcnipbk' ";
            $sqlttd = $this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd) {
                $nama1 = empty($rowttd->nm) ? '' : $rowttd->nm;
            }
        } else {
            $nama1 = '';
        }

        $kode = substr($lckdskpd, 0, -3);
        $n_status = $this->get_status2($lckdskpd);

        // if ($n_status == 'nilai_ubah') {
        // $no_dpa = $this->rka_model->get_nama($skpd, 'no_dpa', 'trhrka', 'kd_skpd');
        $no_dpa = $this->cek_anggaran_model->cek_anggaran($skpd);
        // } else if ($n_status == 'nilai_sempurna') {
        //     $no_dpa = $this->rka_model->get_nama($skpd, 'no_dpa_sempurna', 'trhrka', 'kd_skpd');
        // } else {
        //     $no_dpa = $this->rka_model->get_nama($skpd, 'no_dpa', 'trhrka', 'kd_skpd');
        // }

        $nospd_cetak = $lcnospd;
        if ($tnp_no == '1') {
            $con_dpn = '903/';
            $tahun = $this->session->userdata('pcThang');
            $con_blk_btl = '    /BTL/BKAD-B/' . $tahun;
            $con_blk_bl = '     /BL/BKAD-B/' . $tahun;
            if ($ljns_beban == '51') {
                $con_blk_btl = '    /BTL/BKAD-B/' . $tahun;
                $con_blk_bl = '     /BL/BKAD-B/' . $tahun;
            }

            ($ljns_beban == '5' || $ljns_beban == '6') ?  $nospd_cetak = $con_dpn . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $con_blk_btl : $nospd_cetak = $con_dpn . "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" . $con_blk_bl;
        }

        if ($ljns_beban == '52' || $ljns_beban == '51' || $kode == '4.09.01' || $kode == '1.04.02' || $kode == '2.05.01' || $kode == '4.02.02' || $kode == '4.02.03') {
            $nospd_cetak = str_replace('BKAD', 'BKAD', $nospd_cetak);
        } else {
            $nospd_cetak = str_replace('BKAD', 'BPKPD', $nospd_cetak);
        }

        // jumlah anggaran
        if ($ljns_beban == '52' || $ljns_beban == '51' || $kode == '4.09.01' || $kode == '1.04.02' || $kode == '2.05.01' || $kode == '4.02.02' || $kode == '4.02.03' || $kode == '2.17.01' || $kode == '1.02.01') {
            $nospd_cetak = str_replace('2020', '2020', $nospd_cetak);
        } else {
            $nospd_cetak = str_replace('2020', '2019', $nospd_cetak);
        }

        $csql1 = "SELECT SUM(nilai) AS jumlah FROM trdrka WHERE  jns_ang='$data' AND kd_sub_kegiatan IN 
                      (SELECT kd_sub_kegiatan FROM trdspd WHERE no_spd = '$lcnospd')and left(kd_skpd,22) IN (SELECT left(kd_skpd,22) FROM trhspd WHERE no_spd = '$lcnospd')";



        $hasil1 = $this->db->query($csql1);
        $trh2 = $hasil1->row();
        $jmldpa = number_format(($trh2->jumlah), 2, ',', '.'); //number_format(ceil($trh2->jumlah),2,',','.');
        $bilangdpa = $this->tukd_model->terbilang($trh2->jumlah);

        $bulanspd = $this->rka_model->get_nama($lcnospd, 'bulan_akhir', 'trhspd', 'no_spd');

        //spd lalu konsep baru

        $sql1   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='1'";
        $q1     = $this->db->query($sql1);
        $w1     = $q1->row();
        $rev1   = $w1->revisi;

        //--------
        $sql2   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='2'";
        $q2     = $this->db->query($sql2);
        $w2     = $q2->row();
        $rev2   = $w2->revisi;

        //--------
        $sql3   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='3'";
        $q3     = $this->db->query($sql3);
        $w3     = $q3->row();
        $rev3   = $w3->revisi;

        //--------
        $sql4   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='4'";
        $q4     = $this->db->query($sql4);
        $w4     = $q4->row();
        $rev4   = $w4->revisi;

        //--------
        $sql5   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='5'";
        $q5     = $this->db->query($sql5);
        $w5     = $q5->row();
        $rev5   = $w5->revisi;

        //--------
        $sql6   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='6'";
        $q6     = $this->db->query($sql6);
        $w6     = $q6->row();
        $rev6   = $w6->revisi;

        //--------
        $sql7   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='7'";
        $q7     = $this->db->query($sql7);
        $w7     = $q7->row();
        $rev7   = $w7->revisi;

        //--------
        $sql8   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='8'";
        $q8     = $this->db->query($sql8);
        $w8     = $q8->row();
        $rev8   = $w8->revisi;

        //--------
        $sql9   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='9'";
        $q9     = $this->db->query($sql9);
        $w9     = $q9->row();
        $rev9   = $w9->revisi;

        //--------
        $sql10   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='10'";
        $q10     = $this->db->query($sql10);
        $w10     = $q10->row();
        $rev10   = $w10->revisi;

        //--------
        $sql11   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='11'";
        $q11     = $this->db->query($sql11);
        $w11     = $q11->row();
        $rev11   = $w11->revisi;

        //--------
        $sql12   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='12'";
        $q12     = $this->db->query($sql12);
        $w12     = $q12->row();
        $rev12   = $w12->revisi;

        //--------


        $sqltot = "SELECT sum(nilai)as jm_spd_l from (
                    
                    SELECT
                    'W1' ket,isnull(SUM(a.nilai),0) AS nilai
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$lckdskpd'
                    AND b.jns_beban='$ljns_beban'
                    AND b.status = '1'
                    and bulan_akhir='1'
                    and revisi_ke='$rev1'
                    and bulan_akhir <=$bulanspd
                    and a.no_spd<>'$lcnospd'
                    
                    UNION ALL
                    SELECT
                    'W2' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$lckdskpd'
                    AND b.jns_beban='$ljns_beban'
                    AND b.status = '1'
                    and bulan_akhir='2'
                    and revisi_ke='$rev2'
                    and bulan_akhir <=$bulanspd
                    and a.no_spd<>'$lcnospd'
                    UNION ALL
                    SELECT
                    'W3' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$lckdskpd'
                    AND b.jns_beban='$ljns_beban'
                    AND b.status = '1'
                    and bulan_akhir='3'
                    and revisi_ke='$rev3'
                    and bulan_akhir <=$bulanspd
                    and a.no_spd<>'$lcnospd'
                    UNION ALL
                    SELECT
                    'W4' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$lckdskpd'
                    AND b.jns_beban='$ljns_beban'
                    AND b.status = '1'
                    and bulan_akhir='4'
                    and revisi_ke='$rev4'
                    and bulan_akhir <=$bulanspd
                    and a.no_spd<>'$lcnospd'
                    UNION ALL
                    SELECT
                    'W5' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$lckdskpd'
                    AND b.jns_beban='$ljns_beban'
                    AND b.status = '1'
                    and bulan_akhir='5'
                    and revisi_ke='$rev5'
                    and bulan_akhir <=$bulanspd
                    and a.no_spd<>'$lcnospd'
                    UNION ALL
                    SELECT
                    'W6' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$lckdskpd'
                    AND b.jns_beban='$ljns_beban'
                    AND b.status = '1'
                    and bulan_akhir='6'
                    and revisi_ke='$rev6'
                    and bulan_akhir <=$bulanspd
                    and a.no_spd<>'$lcnospd'
                    UNION ALL
                    SELECT
                    'W7' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$lckdskpd'
                    AND b.jns_beban='$ljns_beban'
                    AND b.status = '1'
                    and bulan_akhir='7'
                    and revisi_ke='$rev7'
                    and bulan_akhir <=$bulanspd
                    and a.no_spd<>'$lcnospd'
                    UNION ALL
                    SELECT
                    'W8' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$lckdskpd'
                    AND b.jns_beban='$ljns_beban'
                    AND b.status = '1'
                    and bulan_akhir='8'
                    and revisi_ke='$rev8'
                    and bulan_akhir <=$bulanspd
                    and a.no_spd<>'$lcnospd'
                    UNION ALL
                    SELECT
                    'W9' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$lckdskpd'
                    AND b.jns_beban='$ljns_beban'
                    AND b.status = '1'
                    and bulan_akhir='9'
                    and revisi_ke='$rev9'
                    and bulan_akhir <=$bulanspd
                    and a.no_spd<>'$lcnospd'
                    UNION ALL
                    SELECT
                    'W10' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$lckdskpd'
                    AND b.jns_beban='$ljns_beban'
                    AND b.status = '1'
                    and bulan_akhir='10'
                    and revisi_ke='$rev10'
                    and bulan_akhir <=$bulanspd
                    and a.no_spd<>'$lcnospd'
                    UNION ALL
                    SELECT
                    'W11' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$lckdskpd'
                    AND b.jns_beban='$ljns_beban'
                    AND b.status = '1'
                    and bulan_akhir='11'
                    and revisi_ke='$rev11'
                    and bulan_akhir <=$bulanspd
                    and a.no_spd<>'$lcnospd'
                    UNION ALL
                    SELECT
                    'W12' ket,isnull(SUM(a.nilai),0) as jm_spd_l
                    FROM
                    trdspd a
                    JOIN trhspd b ON a.no_spd = b.no_spd
                    WHERE
                    b.kd_skpd = '$lckdskpd'
                    AND b.jns_beban='$ljns_beban'
                    AND b.status = '1'
                    and bulan_akhir='12'
                    and revisi_ke='$rev12'
                    and bulan_akhir <=$bulanspd
                    and a.no_spd<>'$lcnospd'
                    )zz";
        $hasil = $this->db->query($sqltot);
        $trh = $hasil->row();

        $jmlspdlalu = number_format(($trh->jm_spd_l), 2, ',', '.'); //number_format(ceil($trh->jm_spd_l),2,',','.');
        $bilspdlalu = $this->tukd_model->terbilang($trh->jm_spd_l);


        $csql = "SELECT * from sclient";
        $hasil = $this->db->query($csql);
        $trh3 = $hasil->row();
        $jmlsisa = number_format(($trh2->jumlah - ($trh->jm_spd_l + $trh1->total)), 2, ',', '.');;
        // $jmlsisa2 = number_format(($trh2->jumlah - $trh->jm_spd_l - $trh1->total),2,',','.'); //number_format(ceil($trh2->jumlah - $trh->jm_spd_l - $trh1->total),2,',','.');
        //$jmlsisa3 = $trh2->jumlah - $trh->jm_spd_l - $trh1->total;
        // $bilsisa = $this->tukd_model->terbilang($jmlsisa3);

        $sql_sisa = "SELECT SUM(jumlah-jm_spd_l-total) as sisa
                    FROM (select 0 jumlah, 0 jm_spd_l, total total from trhspd where no_spd = '$lcnospd'
                        UNION ALL
                        SELECT SUM(nilai) AS jumlah, 0 jm_spd_l,0 total FROM trdrka WHERE jns_ang='$data' AND kd_sub_kegiatan IN 
                      (SELECT kd_sub_kegiatan FROM trdspd WHERE no_spd = '$lcnospd') and left(kd_skpd,22) IN (SELECT left(kd_skpd,22) FROM trhspd WHERE no_spd = '$lcnospd')
                      UNION ALL
                        select 0 jumlah, sum(total) as jm_spd_l, 0 total from trhspd where no_spd<>'$lcnospd' 
                    and tgl_spd<='$ldtgl_spd' and kd_skpd='$lckdskpd' and jns_beban='$ljns_beban' ) a
                        ";
        $hasilsisa = $this->db->query($sql_sisa);
        $trhsisa = $hasilsisa->row();
        $jmlsisa3 = $trhsisa->sisa;
        $jmlsisa2 = number_format(($trhsisa->sisa), 2, ',', '.'); //number_format(ceil($trhsisa->sisa),2,',','.');
        $bilsisa = $this->tukd_model->terbilang($jmlsisa3);

        $csql = "SELECT top 1 * from trkonfig_spd where tgl_konfig_spd<='$ldtgl_spd' order by tgl_konfig_spd desc ";
        $hasil = $this->db->query($csql);
        $trh4 = $hasil->row();

        if ($ljns_beban == '5') {
            $njns = 'Belanja';
        } else if ($ljns_beban == '62') {
            $njns = 'Pembiayaan keluar';
        }


        $xx = 'Bahwa untuk melaksanakan Anggaran ' . $njns . ' sub kegiatan Tahun Anggaran ' . $trh3->thn_ang . ' berdasarkan DPA-SKPD dan anggaran kas yang telah ditetapkan, perlu disediakan pendanaan dengan menerbitkan Surat Penyediaan Dana (SPD); ';
        if ($water == 1) {
            $waters = "style=' background-image: url(" . base_url() . "/image/spd.bmp) ;background-repeat:no-repeat; background-position: 50% 50%;'";
        } else {
            $waters = "";
        }
        $cRet = "<body $waters >";



        $raimu = "PEJABAT PENGELOLA KEUANGAN DAERAH SELAKU BENDAHARA UMUM DAERAH";
        $kepala = "<table width='100%' style='font-size:12px' cellpadding='0px'  cellspacing='0' > 
                    <tr>
                        <td width='90%'><center>
                            PEMERINTAH KABUPATEN MELAWI
                            <br /> $raimu 
                            <br />NOMOR $nospd_cetak
                            <br /> TENTANG
                            <br /> SURAT PENYEDIAAN DANA ANGGARAN BELANJA DAERAH
                            <br /> TAHUN ANGGARAN $trh3->thn_ang
                            <br />
                            <br />
                            PPKD SELAKU BUD
                            <br />&nbsp;
                        <center></td>

                    </tr>
                                                   
            </table>";
        $plt = "";


        // }
        $cRet .= "$kepala";


        $sql = "EXEC otori_spd '$ldtgl_spd'";
        $hasil = $this->db->query($sql);
        $num_row = $hasil->num_rows();
        $font = 10;
        if ($num_row > 10) {
            $font = $font - 2;
        }
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman;; font-size:$font px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                    <tr>
                        <td width=\"3%\" align=\"right\" valign=\"top\">&nbsp;</td>
                        <td width=\"13%\" align=\"left\" valign=\"top\" >Menimbang</td>
                        <td width=\"2%\" align=\"right\" valign=\"top\">:</td>
                        <td width=\"81%\" align=\"justify\" colspan=\"2\" rowspan=\"2\" valign=\"top\" >" . $xx . "</td>
                    </tr>
                    <tr>
                        <td align=\"right\" valign=\"top\">&nbsp;</td>
                        <td align=\"left\" valign=\"top\" >&nbsp;</td>
                        <td align=\"right\" valign=\"top\">&nbsp;</td>
                    </tr>

            ";

        $kolom1 = '';
        //$sql = "EXEC otori_spd '$ldtgl_spd'";
        //$hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $cno1 = $row->no1;
            $cket = $row->ket;
            $ctanda = $row->tanda;

            if ($cno1 == '1') {
                $kolom1 = 'Mengingat';
                $kolom2 = ':';
            } else {
                $kolom1 = '';
                $kolom2 = '';
            }

            if ($ctanda == 'F') {
                $ctambah = $no_dpa . ' Tahun ' . $trh3->thn_ang . ';';
            } else {
                $ctambah = '';
            }


            $cRet .= "<tr>
                            <td align=\"right\" valign=\"top\" > &nbsp;</td>
                            <td align=\"left\" valign=\"top\">$kolom1</td>
                            <td align=\"right\" valign=\"top\" >$kolom2</td>
                            <td width=\"2%\" align=\"right\" valign=\"top\" >$cno1.</td>
                            <td width=\"79%\" align=\"justify\" >$cket $ctambah</td>
                        </tr>";
        }
        $cRet .= "</table>";

        $cRet .= "        
            <table style=\"border-collapse:collapse;font-family: arial;  font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
            
                <tr>
                    <td colspan=\"6\" align=\"center\" valign=\"top\" width=\"100%\"  style=\"font-size:12px\"> 
                        <strong>M E M U T U S K A N :<strong>&nbsp;
                    </td>
                </tr>
                <tr>
                     
                    <td colspan=\"6\" align=\"left\" valign=\"top\" width=\"90%\"  style=\"font-size:12px\">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$trh4->memutuskan &nbsp;
                    </td>
                </tr>
                <tr>
                    
                    <td width=\"3%\"   style=\"font-size:12px\">1.
                    </td>
                    <td width=\"35%\"  style=\"font-size:12px\">Dasar penyediaan dana:
                    </td>
                    <td  width=\"2%\" style=\"font-size:12px\">
                    </td>
                    <td  width=\"50%\" colspan=\"3\"   style=\"font-size:12px\">
                    </td>
                </tr>
                <tr>
                    
                    <td width=\"3%\"   style=\"font-size:12px\">
                    </td>
                    <td width=\"35%\"  style=\"font-size:12px\">DPA-SKPD
                    </td>
                    <td  width=\"2%\" style=\"font-size:12px\">:
                    </td>
                    <td  width=\"50%\" colspan=\"3\"   style=\"font-size:12px\">$no_dpa
                    </td>
                </tr>
                <tr>
                    
                    <td width=\"3%\"   style=\"font-size:12px\">2.
                    </td>
                    <td width=\"35%\"  style=\"font-size:12px\">Ditujukan kepada SKPD
                    </td>
                    <td  width=\"2%\" style=\"font-size:12px\">:
                    </td>
                    <td  width=\"50%\" colspan=\"3\"   style=\"font-size:12px\">$lcnmskpd
                    </td>
                </tr>
                <tr>
                    
                    <td style=\"font-size:12px\" valign=\"top\">3.
                    </td>
                    <td style=\"font-size:12px\" valign=\"top\">Kepala SKPD 
                    </td>
                    <td  style=\"font-size:12px\" valign=\"top\">:
                    </td>
                    <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">$nama1
                    </td>
                </tr>
                <tr>
                    
                    <td rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">4.
                    </td>
                    <td rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">Jumlah Penyediaan dana
                    </td>
                    <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                    </td>
                    <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlspdini
                    </td>
                </tr>
                <tr>
                    <td  colspan=\"3\" style=\"font-size:12px\"><i>($biljmlini)</i></td>
                </tr>
                <tr>
                    
                    <td style=\"font-size:12px\">5.
                    </td>
                    <td style=\"font-size:12px\">Untuk Kebutuhan
                    </td>
                    <td  style=\"font-size:12px\">:
                    </td>
                    <td  colspan=\"3\"   style=\"font-size:12px\">Bulan $blnini s/d $blnsd
                    </td>
                </tr>
                <tr>
                    
                    <td style=\"font-size:12px\">6.
                    </td>
                    <td style=\"font-size:12px\">Ikhtisar penyediaan dana :
                    </td>
                    <td  style=\"font-size:12px\">
                    </td>
                    <td  colspan=\"3\"   style=\"font-size:12px\">
                    </td>
                </tr>

                <tr>
                    
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                    </td>
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">a. Jumlah dana DPA-SKPD
                    </td>
                    <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                    </td>
                    <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmldpa
                    </td>

                </tr>
                <tr>
                    <td  colspan=\"3\" style=\"font-size:12px\"><i>($bilangdpa)</i></td>
                </tr>

                <tr>
                    
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                    </td>
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">b. Akumulasi SPD sebelumnya
                    </td>
                    <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                    </td>
                    <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlspdlalu
                    </td>

                </tr>
                <tr>
                    <td  colspan=\"3\" style=\"font-size:12px\"><i>($bilspdlalu)</i></td>
                </tr>


                <tr>
                    
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                    </td>
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">c. Jumlah dana yang di-SPD-kan saat ini
                    </td>
                    <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                    </td>
                    <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlspdini
                    </td>

                </tr>
                <tr>
                    <td  colspan=\"3\" style=\"font-size:12px\"><i>($biljmlini)</i></td>
                </tr>


                <tr>
                    
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                    </td>
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">d. Sisa jumlah dana DPA-SKPD yang belum di-SPD-kan
                    </td>
                    <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                    </td>
                    <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlsisa
                    </td>

                </tr>
                <tr>
                    <td  colspan=\"3\" style=\"font-size:12px\"><i>($bilsisa)</i></td>
                </tr>
                <tr> 
                    
                    <td style=\"font-size:12px\" align=\"right\" valign=\"top\">7.
                    </td>
                    <td style=\"font-size:12px\" valign=\"top\">Ketentuan-ketentuan lain
                    </td>
                    <td style=\"font-size:12px\" valign=\"top\">:
                    </td>
                    <td  colspan=\"3\" align=\"justify\" style=\"font-size:12px\"> $klain
                    </td>
                </tr>           
                </table>";
        // CETAKAN TANDA TANGAN by Tox
        $cRet .= "
                 <table style=\"border-collapse:collapse;font-weight:none;font-family: arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
            
             
                <tr >
                    <td width=\"40%\" align=\"right\">&nbsp;</td>
                    <td width=\"60%\"  align=\"center\" colspan=\"2\" >Ditetapkan di Nanga Pinoh
                    </td>
                </tr>
                <tr >
                    <td width=\"40%\" align=\"right\">&nbsp;</td>
                    <td width=\"60%\"  align=\"center\" colspan=\"2\" >Pada tanggal " . $this->support->tanggal_format_indonesia($ldtgl_spd) . "</td>
                    </td>
                </tr>
                <tr >
                    <td width=\"40%\" align=\"right\">&nbsp;</td>
                    <td width=\"60%\"  align=\"center\" colspan=\"2\" >&nbsp;</td>
                    </td>
                </tr> 
                <tr >
                    <td width=\"40%\" align=\"right\">&nbsp;</td>
                    <td width=\"60%\"  align=\"center\" colspan=\"2\" style=\"text-transform: uppercase;\">PPKD SELAKU BUD,<br />&nbsp;<br>&nbsp;<br>&nbsp;
                    </td>
                </tr>   
            <tr >
                    <td align=\"right\">&nbsp;</td>
                    <td  align=\"center\" colspan=\"2\"><u>$nama_ppkd</u></td>
                    </td>
              
            <tr >
                    <td  align=\"right\">&nbsp;</td>
                    <td align=\"center\" colspan=\"2\">NIP. $nip_ppkd</td>
                    </td>
                </tr> 
                <tr >
                    <td  align=\"left\">Tembusan disampaikan kepada:</td>
                    <td align=\"center\" colspan=\"2\">&nbsp;</td>
                    </td>
                </tr> 
                <tr >
                    <td  align=\"left\">1. Inspektur *)<br >2. Arsip</td>
                    <td align=\"center\" colspan=\"2\">&nbsp;</td>
                    </td>
                </tr> 
                <tr >
                    <td  align=\"left\">*) Coret yang tidak perlu</td>
                    <td align=\"center\" colspan=\"2\">&nbsp;</td>
                    </td>
                </tr>           
            </table>";


        $data['prev'] = $cRet;


        if ($print == 1) {
            // $this->rka_model->_mpdf_folio('',$cRet,10,10,10,'0');
            $this->rka_model->_mpdf('', $cRet, 10, 10, 10, '0', 'no', '', '', $kop);
        } else {
            echo $cRet;
        }
    }

    function spd_pembiayaan()
    {
        $data['page_title'] = 'INPUT SPD PEMBIAYAAN';
        $this->template->set('title', 'INPUT SPD PEMBIAYAAN');
        $this->template->load('template', 'anggaran/spd/spd_pembiayaan', $data);
    }

    //Register SPD
    function ctk_spd()
    {
        $this->index('0', 'ms_organisasi', 'kd_org', 'nm_org', 'Register SPD', 'ctk_spd', '');
    }

    function preview_reg_spd()
    {
        $id = $this->uri->segment(3);

        $cetak = $this->uri->segment(4);
        $jns = $this->uri->segment(5);
        $tgl =  $_REQUEST['tgl_ttd'];
        $ttd2 =  $_REQUEST['ttd2'];
        $tgl1 =  $_REQUEST['tgl1'];
        $tgl2 =  $_REQUEST['tgl2'];
        $keu1 = $this->keu1;
        if ($jns == 'unit') {
            $jns_where = "WHERE a.kd_skpd='$id'";
            $jns_where1 = "AND b.kd_skpd='$id'";
        } else if ($jns == 'skpd') {
            $jns_where = "WHERE left(a.kd_skpd,17)='$id'";
            $jns_where1 = "AND LEFT(b.kd_skpd,17)='$id'";
        } else {
            $jns_where = "";
            $jns_where1 = "";
        }

        $sqldns = "SELECT c.kd_urusan as kd_u1,c.nm_urusan as nm_u1,a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk,d.nm_org,d.kd_org 
                    FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan 
                    INNER JOIN ms_urusan c ON left(a.kd_urusan,1)=c.kd_urusan 
                    inner join ms_organisasi d on left(rtrim(a.kd_skpd),17)=rtrim(d.kd_org)
                    $jns_where";
        $sqlskpd = $this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns) {

            $kd_urusan1 = $rowdns->kd_u1;
            $nm_urusan1 = $rowdns->nm_u1;
            $kd_urusan = $rowdns->kd_u;
            $nm_urusan = $rowdns->nm_u;
            $kd_skpd  = $rowdns->kd_sk;
            $nm_skpd  = $rowdns->nm_sk;
            $kd_org  = $rowdns->kd_org;
            $nm_org  = $rowdns->nm_org;
        }
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$keu1'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc) {

            //$tgl=$rowsc->tgl_rka;
            // $tanggal = $this->tanggal_format_indonesia($tgl);
            $tanggal = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

            $kab     = $rowsc->kab_kota;
            $daerah  = $rowsc->daerah . ',';
            $thn     = $rowsc->thn_ang;
        }


        $sqlsc = "SELECT nama,jabatan FROM ms_ttd where nip='1'";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowttd) {
            $nama_ttd = $rowttd->nama;
            $jab_ttd     = $rowttd->jabatan;
        }


        if ($ttd2 != '') {
            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE (REPLACE(nip, ' ', '')='$ttd2')  ";
            $sqlttd = $this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd) {
                $nama_ttd = $rowttd->nm;
                $jab_ttd     = $rowttd->jab . ',';
            }
        } else {

            $nama_ttd = '';
            $jab_ttd     = '';
            //$daerah = '';
        }




        $cRet = '';
        $cRet1 = '';
        $cRet2 = '';
        $font = 10;
        $font1 = $font - 1;


        $sql1 = "SELECT a.no_spd,b.tgl_spd,b.kd_skpd,b.nm_skpd,c.nama,sum(a.nilai) [nilai] from trdspd a join trhspd b on a.no_spd=b.no_spd 
        left join ms_ttd c on b.kd_bkeluar=c.nip where b.tgl_spd>='$tgl1' and b.tgl_spd<='$tgl2' $jns_where1
        group by a.no_spd,b.tgl_spd,b.kd_skpd,b.nm_skpd,c.nama 
        order by b.tgl_spd,a.no_spd,b.kd_skpd
        ";


        $ctkskpd = '';
        $ctkunit = '';
        $ctkunit1 = '';
        $ctkunit2 = '';

        if ($jns == 'unit') {
            $ctkskpd = $kd_org . ' / ' . $nm_org;
            $ctkunit = $kd_skpd . ' / ' . $nm_skpd;
            $ctkunit1 = 'UNIT';
            $ctkunit2 = ':';
        } else {
            if ($jns == 'skpd') {
                $ctkskpd = $kd_org . ' / ' . $nm_org;
            } else {
                $sql1 = "SELECT a.no_spd,b.tgl_spd,b.kd_skpd,b.nm_skpd,c.nama,sum(a.nilai) [nilai] from trdspd a join trhspd b on a.no_spd=b.no_spd 
                   left join ms_ttd c on b.kd_bkeluar=c.nip WHERE b.tgl_spd>='$tgl1' and b.tgl_spd<='$tgl2' group by a.no_spd,b.tgl_spd,b.kd_skpd,b.nm_skpd,c.nama 
                    order by b.tgl_spd,a.no_spd,b.kd_skpd
                    ";
            }
        }

        $ctgl1 = $this->rka_model->tanggal_format_indonesia($tgl1);
        $ctgl2 = $this->rka_model->tanggal_format_indonesia($tgl2);

        if ($tgl <> '') {
            $tgl = $this->rka_model->tanggal_format_indonesia($tgl);
        }
        $cRet .= "<table style=\"border-collapse:collapse;font-family: arial; font-size:16px;font-weight:bold;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">  
                    <tr><td align=\"center\" colspan=\"4\" >PEMERINTAH KABUPATEN MELAWI<td></tr>
                    <tr>
                        <td align=\"center\" colspan=\"4\" >REGISTER SPD<td>
                    <tr>
                    <tr><td align=\"center\" colspan=\"4\" >TAHUN ANGGARAN " . $this->session->userdata('pcThang') . "<td></tr>
                    <tr><td align=\"center\" colspan=\"4\" >&nbsp;<td></tr>
                    <tr><td align=\"center\" colspan=\"4\" >&nbsp;<td></tr>
                  </table>";
        if ($jns == 'all') {
            $cRet .= "<table style=\"border-collapse:collapse;font-family: arial; font-size:12px;font-weight:normal;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td width=\"10%\">PADA TANGGAL</td>
                        <td width=\"1%\">:</td>
                        <td align=\"left\">$ctgl1 s/d $ctgl2</td>
                    </tr>

                    <tr><td align=\"center\" colspan=\"4\" >&nbsp;<td></tr>
                  </table>";
        } else {

            $cRet .= "<table style=\"border-collapse:collapse;font-family: arial; font-size:12px;font-weight:normal;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">   
                    <tr>
                       
                       <td width=\"12%\">KODE / NAMA SKPD</td>
                       <td width=\"1%\">:</td>
                       <td width=\"80%\">$ctkskpd</td>
                    </tr> 
                   
                    <tr>
                        <td>$ctkunit1</td>
                        <td>$ctkunit2</td>
                        <td>$ctkunit</td>
                    </tr>
                    <tr>
                        <td>PADA TANGGAL</td>
                        <td>:</td>
                        <td>$ctgl1 s/d $ctgl2</td>
                    </tr>

                    <tr><td align=\"center\" colspan=\"4\" >&nbsp;<td></tr>
                  </table>";
        }





        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px;\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
                     <thead>                       
                        <tr>
                        <td width=\"15%\" align=\"center\"><b>No</b></td>                            
                        <td width=\"15%\" align=\"center\"><b>No SPD/ Keperluan </b></td>                            
                            <td width=\"10%\" align=\"center\"><b>Tgl SPD</b></td>
                            <td width=\"49%\" align=\"center\"><b>Kode/ Nama SKPD</b></td>
                            <td width=\"12%\" align=\"center\"><b>Nilai (Rp)</b></td>
                            <td width=\"14%\" align=\"center\"><b>Bendahara Pengeluaran</b></td>
                        </tr>
                     </thead>
                     
                       ";

        $tot = 0;
        $query = $this->db->query($sql1);
        //$query = $this->skpd_model->getAllc();
        $num_query = $query->num_rows();
        $no = 1;
        foreach ($query->result() as $row) {
            $nospd = $row->no_spd;
            $tglspd = $row->tgl_spd;
            $kd_skpd = $row->kd_skpd;
            $nm_skpd = $row->nm_skpd;
            $nm = $row->nama;
            $nilai = $row->nilai;
            $tot += $nilai;
            $tglspd = date("d-m-Y", strtotime($tglspd));
            $nilai = number_format($nilai, "2", ",", ".");
            $cRet    .= " <tr>
            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"> $no</td>
            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"left\">$nospd</td>                                     
                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" >$tglspd</td>
                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"left\">$kd_skpd - $nm_skpd</td>
                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$nilai</td>
                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$nm</td>
                 </tr>";
            $no++;
        }

        $tot = number_format($tot, "2", ",", ".");
        $cRet    .= " <tr><td colspan=\"4\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" align=\"right\">Jumlah Total</td>                                     
         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:11px;\" align=\"right\">$tot</td>
         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"></td>
         </tr>";


        $cRet .= "<tr>
                                <td width=\"100%\" align=\"center\" colspan=\"5\">
                                <table width=\"100%\" border=\"0\">
                                <tr>
                                <td width=\"70%\" align=\"left\" >&nbsp;<br>&nbsp;
                                <br>&nbsp;
                                &nbsp;<br>
                                &nbsp;<br>
                                &nbsp;<br>
                                &nbsp;<br>  
                                </td>
                                <td width=\"30%\" align=\"center\" >$daerah $tgl                    
                                <br>$jab_ttd
                                <p>&nbsp;</p>
                                <br>
                                <br>
                                <br>
                                <br><b>$nama_ttd</b>
                                </td></tr></table></td>
                             </tr>";


        $cRet    .= "</table>";
        $data['prev'] = $cRet;
        $data['kd_org'] = $id;
        $this->template->set('title', 'CETAK PERDA');
        //$this->_mpdf('',$cRet,10,10,10,0);

        switch ($cetak) {
            case 0;
                // echo ("<title>REGISTER SPD $id</title>");
                // $this->support->_mpdf('', $cRet, 10, 10, 10, 1);
                echo ($cRet);
                break;
            case 1;
                $this->support->_mpdf('', $cRet, 10, 10, 10, 1);
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename=Register_SPD.xls");
                $this->load->view('anggaran/rka/perdaIII', $data);
                break;
        }
    }

    //-----------------------



    function get_status_angkas_melawi($skpd)
    {
        $n_status = '';

        $sql = "SELECT TOP 1 * from (
select '1'as urut,'murni' as status,murni as nilai from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '2'as urut,'murni_geser1',murni_geser1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '3'as urut,'murni_geser2',murni_geser2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '4'as urut,'murni_geser3',murni_geser3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '5'as urut,'murni_geser4',murni_geser4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '6'as urut,'murni_geser5',murni_geser5 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '7'as urut,'sempurna1',sempurna1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '8'as urut,'sempurna1_geser1',sempurna1_geser1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '9'as urut,'sempurna1_geser2',sempurna1_geser2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '10'as urut,'sempurna1_geser3',sempurna1_geser3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '11'as urut,'sempurna1_geser4',sempurna1_geser4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '12'as urut,'sempurna1_geser5',sempurna1_geser5 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '13'as urut,'sempurna2',sempurna2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '14'as urut,'sempurna2_geser1',sempurna2_geser1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '15'as urut,'sempurna2_geser2',sempurna2_geser2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '16'as urut,'sempurna2_geser3',sempurna2_geser3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '17'as urut,'sempurna2_geser4',sempurna2_geser4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '18'as urut,'sempurna2_geser5',sempurna2_geser5 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '19'as urut,'sempurna3',sempurna3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '20'as urut,'sempurna3_geser1',sempurna3_geser1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '21'as urut,'sempurna3_geser2',sempurna3_geser2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '22'as urut,'sempurna3_geser3',sempurna3_geser3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '23'as urut,'sempurna3_geser4',sempurna3_geser4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '24'as urut,'sempurna3_geser5',sempurna3_geser5 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '25'as urut,'sempurna4',sempurna4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '26'as urut,'sempurna4_geser1',sempurna4_geser1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '27'as urut,'sempurna4_geser2',sempurna4_geser2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '28'as urut,'sempurna4_geser3',sempurna4_geser3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '29'as urut,'sempurna4_geser4',sempurna4_geser4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '30'as urut,'sempurna4_geser5',sempurna4_geser5 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '31'as urut,'sempurna5',sempurna5 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '32'as urut,'sempurna5_geser1',sempurna5_geser1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '33'as urut,'sempurna5_geser2',sempurna5_geser2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '34'as urut,'sempurna5_geser3',sempurna5_geser3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '35'as urut,'sempurna5_geser4',sempurna5_geser4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '36'as urut,'sempurna5_geser5',sempurna5_geser5 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '37'as urut,'ubah',ubah from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '38'as urut,'ubah1',ubah1 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '39'as urut,'ubah2',ubah2 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '40'as urut,'ubah3',ubah3 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '41'as urut,'ubah4',ubah4 from status_angkas where kd_skpd ='$skpd'
UNION ALL
select '42'as urut,'ubah5',ubah5 from status_angkas where kd_skpd ='$skpd'
)zz where nilai='1' ORDER BY urut DESC";

        $q_trhrka = $this->db->query($sql);
        $num_rows = $q_trhrka->num_rows();

        foreach ($q_trhrka->result() as $r_trhrka) {
            $n_status = $r_trhrka->status;
        }
        return $n_status;
    }

    public function get_column_angkas($skpd)
    {
        $sts_angkas = $this->get_status_angkas($skpd);

        if ($sts_angkas == 'murni') return 'nilai_susun';
        if ($sts_angkas == 'murni_geser1') return 'nilai_susun1';
        if ($sts_angkas == 'murni_geser2') return 'nilai_susun2';
        if ($sts_angkas == 'murni_geser3') return 'nilai_susun3';
        if ($sts_angkas == 'murni_geser4') return 'nilai_susun4';
        if ($sts_angkas == 'murni_geser5') return 'nilai_susun5';
        if ($sts_angkas == 'sempurna1') return 'nilai_sempurna';
        if ($sts_angkas == 'sempurna1_geser1') return 'nilai_sempurna11';
        if ($sts_angkas == 'sempurna1_geser2') return 'nilai_sempurna12';
        if ($sts_angkas == 'sempurna1_geser3') return 'nilai_sempurna13';
        if ($sts_angkas == 'sempurna1_geser4') return 'nilai_sempurna14';
        if ($sts_angkas == 'sempurna1_geser5') return 'nilai_sempurna15';
        if ($sts_angkas == 'sempurna2') return 'nilai_sempurna2';
        if ($sts_angkas == 'sempurna2_geser1') return 'nilai_sempurna21';
        if ($sts_angkas == 'sempurna2_geser2') return 'nilai_sempurna22';
        if ($sts_angkas == 'sempurna2_geser3') return 'nilai_sempurna23';
        if ($sts_angkas == 'sempurna2_geser4') return 'nilai_sempurna24';
        if ($sts_angkas == 'sempurna2_geser5') return 'nilai_sempurna25';
        if ($sts_angkas == 'sempurna3') return 'nilai_sempurna3';
        if ($sts_angkas == 'sempurna3_geser1') return 'nilai_sempurna31';
        if ($sts_angkas == 'sempurna3_geser2') return 'nilai_sempurna32';
        if ($sts_angkas == 'sempurna3_geser3') return 'nilai_sempurna33';
        if ($sts_angkas == 'sempurna3_geser4') return 'nilai_sempurna34';
        if ($sts_angkas == 'sempurna3_geser5') return 'nilai_sempurna35';
        if ($sts_angkas == 'sempurna4') return 'nilai_sempurna4';
        if ($sts_angkas == 'sempurna4_geser1') return 'nilai_sempurna41';
        if ($sts_angkas == 'sempurna4_geser2') return 'nilai_sempurna42';
        if ($sts_angkas == 'sempurna4_geser3') return 'nilai_sempurna43';
        if ($sts_angkas == 'sempurna4_geser4') return 'nilai_sempurna44';
        if ($sts_angkas == 'sempurna4_geser5') return 'nilai_sempurna45';
        if ($sts_angkas == 'sempurna5') return 'nilai_sempurna5';
        if ($sts_angkas == 'sempurna5_geser1') return 'nilai_sempurna51';
        if ($sts_angkas == 'sempurna5_geser2') return 'nilai_sempurna52';
        if ($sts_angkas == 'sempurna5_geser3') return 'nilai_sempurna53';
        if ($sts_angkas == 'sempurna5_geser4') return 'nilai_sempurna1';
        if ($sts_angkas == 'sempurna5_geser5') return 'nilai_sempurna55';
        if ($sts_angkas == 'ubah') return 'nilai_ubah';
        if ($sts_angkas == 'ubah1') return 'nilai_ubah1';
        if ($sts_angkas == 'ubah2') return 'nilai_ubah2';
        if ($sts_angkas == 'ubah3') return 'nilai_ubah3';
        if ($sts_angkas == 'ubah4') return 'nilai_ubah4';
        return 'nilai_ubah5';
    }

    public function load_angkas_belanja()
    {
        $kd_skpd = $this->input->get('kd_skpd');
        $bulan_awal = $this->input->get('bulan_awal');
        $bulan_akhir = $this->input->get('bulan_akhir');
        $page = $this->input->get('page');
        $rows = $this->input->get('rows');
        $data = $this->cek_anggaran_model->cek_anggaran($kd_skpd);

        $offset = ($page - 1) * $rows;

        $column_angkas = $this->get_column_angkas($kd_skpd);

        $row = $this->db->query("SELECT COUNT(*) AS num_of_rows, SUM(nilai) AS nilai_total FROM (
            SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, SUM($column_angkas) AS nilai FROM trdskpd_ro
            WHERE kd_skpd = '$kd_skpd' AND bulan BETWEEN $bulan_awal AND $bulan_akhir
            AND LEFT(trdskpd_ro.kd_rek6, 1) = '5'
            GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6
        ) anggaran_kas")->row();

        $num_of_rows = $row->num_of_rows;
        $nilai_total = $row->nilai_total;

        $daftar_angkas = $this->db->query("SELECT ROW_NUMBER() OVER (ORDER BY anggaran.kd_sub_kegiatan, anggaran.kd_rek6) AS id, anggaran.kd_sub_kegiatan, anggaran.nm_sub_kegiatan, anggaran.kd_rek6,
                anggaran.nm_rek6, anggaran_kas.nilai AS nilai, ISNULL(spd.nilai, 0) AS nilai_lalu, anggaran.nilai AS nilai_anggaran
            FROM (
                (SELECT kd_skpd, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nilai
                FROM trdrka WHERE jns_ang='$data' AND kd_skpd='$kd_skpd' AND LEFT(kd_rek6, 1) = '5') anggaran
                JOIN
                (SELECT kd_skpd, trdskpd_ro.kd_sub_kegiatan, nm_sub_kegiatan, trdskpd_ro.kd_rek6, nm_rek6, SUM($column_angkas) AS nilai
                FROM trdskpd_ro JOIN ms_sub_kegiatan ON trdskpd_ro.kd_sub_kegiatan = ms_sub_kegiatan.kd_sub_kegiatan
                JOIN ms_rek6 ON trdskpd_ro.kd_rek6 = ms_rek6.kd_rek6
                WHERE bulan BETWEEN $bulan_awal AND $bulan_akhir AND kd_skpd='$kd_skpd' AND LEFT(trdskpd_ro.kd_rek6, 1) = '5'
                GROUP BY kd_skpd, trdskpd_ro.kd_sub_kegiatan, ms_sub_kegiatan.nm_sub_kegiatan, trdskpd_ro.kd_rek6, ms_rek6.nm_rek6) anggaran_kas
                    ON anggaran.kd_skpd = anggaran_kas.kd_skpd
                        AND anggaran.kd_sub_kegiatan = anggaran_kas.kd_sub_kegiatan
                        AND anggaran.kd_rek6 = anggaran_kas.kd_rek6
                LEFT JOIN
                (SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, SUM(spd.nilai) AS nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal < {$bulan_awal} AND spd_final.bulan_akhir < {$bulan_akhir}
                    GROUP BY spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6
                ) spd
                    ON anggaran.kd_skpd = spd.kd_skpd
                        AND anggaran.kd_sub_kegiatan = spd.kd_sub_kegiatan
                        AND anggaran.kd_rek6 = spd.kd_rek6
            ) ORDER BY kd_sub_kegiatan, kd_rek6 OFFSET $offset ROWS FETCH NEXT $rows ROWS ONLY
        ")->result_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('rows' => $daftar_angkas, 'total' => $num_of_rows, 'nilai_total' => $nilai_total)));
    }

    public function load_angkas_belanja_revisi()
    {
        $kd_skpd = $this->input->get('kd_skpd');
        $data = $this->cek_anggaran_model->cek_anggaran($kd_skpd);
        $bulan_awal = $this->input->get('bulan_awal');
        $bulan_akhir = $this->input->get('bulan_akhir');
        $page = $this->input->get('page');
        $rows = $this->input->get('rows');

        $offset = ($page - 1) * $rows;

        $column_angkas = $this->get_column_angkas($kd_skpd);

        $row = $this->db->query("SELECT COUNT(*) AS num_of_rows, SUM(nilai) AS nilai_total FROM (
            SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, SUM($column_angkas) AS nilai FROM trdskpd_ro
            WHERE kd_skpd = '$kd_skpd'
            AND bulan BETWEEN $bulan_awal AND $bulan_akhir
            AND LEFT(trdskpd_ro.kd_rek6, 1) = '5'
            GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6
        ) anggaran_kas")->row();

        $num_of_rows = $row->num_of_rows;
        $nilai_total = $row->nilai_total;

        $daftar_angkas = $this->db->query("SELECT ROW_NUMBER() OVER (ORDER BY anggaran.kd_sub_kegiatan, anggaran.kd_rek6) AS id, anggaran.kd_sub_kegiatan, anggaran.nm_sub_kegiatan, anggaran.kd_rek6,
                anggaran.nm_rek6, anggaran_kas.nilai AS nilai, ISNULL(spd.nilai, 0) AS nilai_lalu, ISNULL(spd_pra_revisi.nilai, 0) AS nilai_pra_revisi, anggaran.nilai AS nilai_anggaran
            FROM (
                (
                    SELECT kd_skpd, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nilai
                    FROM trdrka WHERE jns_ang='$data' AND  kd_skpd='$kd_skpd' AND LEFT(kd_rek6, 1) = '5'
                ) anggaran
                JOIN
                (
                    SELECT kd_skpd, trdskpd_ro.kd_sub_kegiatan, nm_sub_kegiatan, trdskpd_ro.kd_rek6, nm_rek6, SUM($column_angkas) AS nilai
                    FROM trdskpd_ro JOIN ms_sub_kegiatan ON trdskpd_ro.kd_sub_kegiatan = ms_sub_kegiatan.kd_sub_kegiatan
                    JOIN ms_rek6 ON trdskpd_ro.kd_rek6 = ms_rek6.kd_rek6
                    WHERE bulan BETWEEN $bulan_awal AND $bulan_akhir AND kd_skpd='$kd_skpd' AND LEFT(trdskpd_ro.kd_rek6, 1) = '5' AND kd_gabungan IN (
                        SELECT kd_gabungan FROM trdskpd_ro WHERE kd_skpd = '$kd_skpd' AND bulan BETWEEN $bulan_awal AND $bulan_akhir
                    )
                    GROUP BY kd_skpd, trdskpd_ro.kd_sub_kegiatan, ms_sub_kegiatan.nm_sub_kegiatan, trdskpd_ro.kd_rek6, ms_rek6.nm_rek6
                ) anggaran_kas
                    ON anggaran.kd_skpd = anggaran_kas.kd_skpd
                        AND anggaran.kd_sub_kegiatan = anggaran_kas.kd_sub_kegiatan
                        AND anggaran.kd_rek6 = anggaran_kas.kd_rek6
                LEFT JOIN
                (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, SUM(spd.nilai) AS nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal < {$bulan_awal} AND spd_final.bulan_akhir < {$bulan_akhir}
                    GROUP BY spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6
                ) spd
                    ON anggaran.kd_skpd = spd.kd_skpd
                        AND anggaran.kd_sub_kegiatan = spd.kd_sub_kegiatan
                        AND anggaran.kd_rek6 = spd.kd_rek6
                LEFT JOIN
                (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, spd.nilai AS nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal = {$bulan_awal} AND spd_final.bulan_akhir = {$bulan_akhir}
                ) spd_pra_revisi
                    ON anggaran.kd_skpd = spd_pra_revisi.kd_skpd
                        AND anggaran.kd_sub_kegiatan = spd_pra_revisi.kd_sub_kegiatan
                        AND anggaran.kd_rek6 = spd_pra_revisi.kd_rek6
            ) ORDER BY kd_sub_kegiatan, kd_rek6 OFFSET $offset ROWS FETCH NEXT $rows ROWS ONLY
        ")->result_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('rows' => $daftar_angkas, 'total' => $num_of_rows, 'nilai_total' => $nilai_total)));
    }

    public function insert_spd_revisi()
    {
        try {
            $this->db->trans_start();
            $no_spd = $this->input->post('no_spd');
            $tgl = $this->input->post('tgl');
            $kd_skpd = $this->input->post('kd_skpd');
            $bend = $this->input->post('bend');
            $bulan_awal = $this->input->post('bulan_awal');
            $bulan_akhir = $this->input->post('bulan_akhir');
            $ketentuan = $this->input->post('ketentuan');

            $spd_query = $this->db->where('no_spd', $no_spd)->get('trhspd');
            if ($spd_query->num_rows() > 0) {
                $this->output
                    ->set_status_header(422, 'Invalid input')
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array(
                        'message' => 'Gagal menambah SPD, Nomor SPD sudah digunakan',
                    )));
                return;
            }

            $skpd = $this->db->where('kd_skpd', $kd_skpd)->get('ms_skpd')->row();

            $column_angkas = $this->get_column_angkas($kd_skpd);

            $total_angkas_spd = $this->db->query(
                "SELECT SUM($column_angkas) AS total
                FROM trdskpd_ro WHERE kd_skpd='$kd_skpd'
                AND bulan BETWEEN $bulan_awal AND $bulan_akhir AND LEFT(kd_rek6, 1) = '5'"
            )->row()->total;
            $this->db->insert('trhspd', array(
                'no_spd' => $no_spd,
                'tgl_spd' => $tgl,
                'kd_skpd' => $kd_skpd,
                'nm_skpd' => $skpd->nm_skpd,
                'jns_beban' => '5',
                'bulan_awal' => $bulan_awal,
                'bulan_akhir' => $bulan_akhir,
                'total' => $total_angkas_spd,
                'klain' => $ketentuan,
                'kd_bkeluar' => $bend,
                'username' => $this->session->userdata('pcNama'),
                'tglupdate' => date('Y-m-d H:i:s'),
            ));

            $this->db->query(
                "INSERT INTO trdspd
                (no_spd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nilai)
                SELECT '$no_spd' AS no_spd, ms_program.kd_program, nm_program, ms_kegiatan.kd_kegiatan, nm_kegiatan,
                    ms_sub_kegiatan.kd_sub_kegiatan, ms_sub_kegiatan.nm_sub_kegiatan, ms_rek6.kd_rek6, ms_rek6.nm_rek6, SUM(trdskpd_ro.$column_angkas)
                FROM trdskpd_ro JOIN ms_rek6 ON trdskpd_ro.kd_rek6 = ms_rek6.kd_rek6
                JOIN ms_sub_kegiatan ON trdskpd_ro.kd_sub_kegiatan = ms_sub_kegiatan.kd_sub_kegiatan
                JOIN ms_kegiatan ON ms_sub_kegiatan.kd_kegiatan = ms_kegiatan.kd_kegiatan
                JOIN ms_program ON ms_kegiatan.kd_program = ms_program.kd_program
                WHERE trdskpd_ro.kd_skpd = '$kd_skpd' AND bulan BETWEEN $bulan_awal AND $bulan_akhir AND LEFT(trdskpd_ro.kd_rek6, 1) = '5' AND kd_gabungan IN (
                    SELECT kd_gabungan FROM trdskpd_ro WHERE kd_skpd = '$kd_skpd' AND bulan BETWEEN $bulan_awal AND $bulan_akhir
                )
                GROUP BY ms_program.kd_program, nm_program, ms_kegiatan.kd_kegiatan, nm_kegiatan,
                ms_sub_kegiatan.kd_sub_kegiatan, ms_sub_kegiatan.nm_sub_kegiatan, ms_rek6.kd_rek6, ms_rek6.nm_rek6"
            );

            // $error_message = $this->db->_error_message();

            $this->db->trans_complete();
            if ($this->db->trans_status() === false) {
                throw new \Exception('Unknown Error');
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array(
                        'message' => 'SPD telah berhasil ditambahkan',
                    )));
            }
        } catch (\Exception $e) {
            $this->output
                ->set_status_header(500, 'Server Error')
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'message' => 'SPD gagal ditambahkan',
                )));
            log_message('error', json_encode(array(
                'error_message' => $e->getMessage(),
                'no_spd' => $no_spd,
            )));
        }
    }

    public function load_detail_spd_revisi()
    {
        $no_spd = $this->input->get('no_spd');
        $page = $this->input->get('page');
        $rows = $this->input->get('rows');

        $offset = ($page - 1) * $rows;

        $spd = $this->db->where('no_spd', $no_spd)->get('trhspd')->row();

        $row = $this->db->query("SELECT COUNT(*) AS num_of_rows, SUM(nilai) AS nilai_total FROM trdspd WHERE no_spd = '$no_spd'")->row();
        $num_of_rows = $row->num_of_rows;
        $nilai_total = $row->nilai_total;

        $detail_spd = $this->db->query("SELECT ROW_NUMBER() OVER (ORDER BY spd.kd_sub_kegiatan, spd.kd_rek6) AS id, spd.kd_sub_kegiatan, spd.nm_sub_kegiatan, spd.kd_rek6,
            spd.nm_rek6, spd.nilai AS nilai, ISNULL(spd_lalu.nilai, 0) AS nilai_lalu, ISNULL(spd_pra_revisi.nilai, 0) AS nilai_pra_revisi, anggaran.nilai AS nilai_anggaran
            FROM (
                (SELECT kd_skpd, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nilai
                FROM trdspd JOIN trhspd ON trdspd.no_spd = trhspd.no_spd WHERE trhspd.no_spd = '$no_spd') spd
                JOIN
                (SELECT kd_skpd, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nilai FROM trdrka) anggaran
                    ON spd.kd_skpd = anggaran.kd_skpd AND spd.kd_sub_kegiatan = anggaran.kd_sub_kegiatan AND spd.kd_rek6 = anggaran.kd_rek6
                LEFT JOIN
                (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, SUM(spd.nilai) AS nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$spd->kd_skpd}' 
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$spd->kd_skpd}'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal < {$spd->bulan_awal} AND spd_final.bulan_akhir < {$spd->bulan_akhir}
                    GROUP BY spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6
                ) spd_lalu
                    ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan AND spd.kd_rek6 = spd_lalu.kd_rek6
                LEFT JOIN
                (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, spd.nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$spd->kd_skpd}' AND tglupdate < '{$spd->tglupdate}'
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$spd->kd_skpd}'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal = {$spd->bulan_awal} AND spd_final.bulan_akhir = {$spd->bulan_akhir}
                ) spd_pra_revisi
                    ON spd.kd_skpd = spd_pra_revisi.kd_skpd AND spd.kd_sub_kegiatan = spd_pra_revisi.kd_sub_kegiatan AND spd.kd_rek6 = spd_pra_revisi.kd_rek6
            ) ORDER BY kd_sub_kegiatan, kd_rek6 OFFSET $offset ROWS FETCH NEXT $rows ROWS ONLY
        ")->result_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('rows' => $detail_spd, 'total' => $num_of_rows, 'nilai_total' => $nilai_total)));
    }

    public function cetak_lampiran_spd_revisi()
    {
        $no_spd = $this->input->get('no_spd');
        $this->load->library('parser');

        $spd = $this->db->where('no_spd', $no_spd)->get('trhspd')->row();
        $ms_ttd = $this->db->where('kode', 'PPKD')->get('ms_ttd')->row();
        $kd_skpd = $spd->kd_skpd;
        $column_anggaran = $this->get_column_anggaran($spd->tgl_spd, $kd_skpd);
        $no_dpa = $this->get_no_dpa($spd->tgl_spd, $kd_skpd);
        $result = $this->db->query(
            "SELECT * FROM (
                SELECT spd.kd_program AS kode, spd.nm_program AS nama, '' AS kd_rek, '' AS nm_rek, SUM(trdrka.nilai) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, SUM(ISNULL(spd_pra_revisi.nilai, 0)) AS nilai_pra_revisi, 'program' AS jenis
                FROM (
                    SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                    WHERE trhspd.no_spd = '$no_spd'
                ) spd
                LEFT JOIN (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, SUM(spd.nilai) AS nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$kd_skpd}'
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal < {$spd->bulan_awal} AND spd_final.bulan_akhir < {$spd->bulan_akhir}
                    GROUP BY spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6
                ) spd_lalu
                ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_lalu.kd_rek6
                LEFT JOIN (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, spd.nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$kd_skpd}' AND (
                            (tglupdate < '{$spd->tglupdate}' AND bulan_awal = {$spd->bulan_awal} AND bulan_akhir = {$spd->bulan_akhir})
                            OR (bulan_awal <> {$spd->bulan_awal} AND bulan_akhir <> {$spd->bulan_akhir})
                        )
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal = {$spd->bulan_awal} AND spd_final.bulan_akhir = {$spd->bulan_akhir}
                ) spd_pra_revisi
                ON spd.kd_skpd = spd_pra_revisi.kd_skpd AND spd.kd_sub_kegiatan = spd_pra_revisi.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_pra_revisi.kd_rek6
                JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6
                GROUP BY spd.kd_program, spd.nm_program, spd.kd_skpd

                UNION ALL

                SELECT spd.kd_kegiatan AS kode, spd.nm_kegiatan AS nama, '' AS kd_rek, '' AS nm_rek, SUM(trdrka.$column_anggaran) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, SUM(ISNULL(spd_pra_revisi.nilai, 0)) AS nilai_pra_revisi, 'kegiatan' AS jenis
                FROM (
                    SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                    WHERE trhspd.no_spd = '$no_spd'
                ) spd
                LEFT JOIN (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, SUM(spd.nilai) AS nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$kd_skpd}'
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal < {$spd->bulan_awal} AND spd_final.bulan_akhir < {$spd->bulan_akhir}
                    GROUP BY spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6
                ) spd_lalu
                ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_lalu.kd_rek6
                LEFT JOIN (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, spd.nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$kd_skpd}' AND (
                            (tglupdate < '{$spd->tglupdate}' AND bulan_awal = {$spd->bulan_awal} AND bulan_akhir = {$spd->bulan_akhir})
                            OR (bulan_awal <> {$spd->bulan_awal} AND bulan_akhir <> {$spd->bulan_akhir})
                        )
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal = {$spd->bulan_awal} AND spd_final.bulan_akhir = {$spd->bulan_akhir}
                ) spd_pra_revisi
                ON spd.kd_skpd = spd_pra_revisi.kd_skpd AND spd.kd_sub_kegiatan = spd_pra_revisi.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_pra_revisi.kd_rek6
                JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6
                GROUP BY spd.kd_kegiatan, spd.nm_kegiatan, spd.kd_skpd

                UNION ALL

                SELECT spd.kd_sub_kegiatan AS kode, spd.nm_sub_kegiatan AS nama, '' AS kd_rek, '' AS nm_rek, SUM(trdrka.$column_anggaran) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, SUM(ISNULL(spd_pra_revisi.nilai, 0)) AS nilai_pra_revisi, 'sub_kegiatan' AS jenis
                FROM (
                    SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                    WHERE trhspd.no_spd = '$no_spd'
                ) spd
                LEFT JOIN (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, SUM(spd.nilai) AS nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$kd_skpd}'
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal < {$spd->bulan_awal} AND spd_final.bulan_akhir < {$spd->bulan_akhir}
                    GROUP BY spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6
                ) spd_lalu
                ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_lalu.kd_rek6
                LEFT JOIN (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, spd.nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$kd_skpd}' AND (
                            (tglupdate < '{$spd->tglupdate}' AND bulan_awal = {$spd->bulan_awal} AND bulan_akhir = {$spd->bulan_akhir})
                            OR (bulan_awal <> {$spd->bulan_awal} AND bulan_akhir <> {$spd->bulan_akhir})
                        )
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal = {$spd->bulan_awal} AND spd_final.bulan_akhir = {$spd->bulan_akhir}
                ) spd_pra_revisi
                ON spd.kd_skpd = spd_pra_revisi.kd_skpd AND spd.kd_sub_kegiatan = spd_pra_revisi.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_pra_revisi.kd_rek6
                JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6
                GROUP BY spd.kd_sub_kegiatan, spd.nm_sub_kegiatan, spd.kd_skpd

                UNION ALL

                SELECT spd.kd_sub_kegiatan AS kode, spd.nm_sub_kegiatan AS nama, spd.kd_rek6 AS kd_rek, spd.nm_rek6 AS nm_rek, SUM(trdrka.$column_anggaran) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, SUM(ISNULL(spd_pra_revisi.nilai, 0)) AS nilai_pra_revisi, 'rekening' AS jenis
                FROM (
                    SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                    WHERE trhspd.no_spd = '$no_spd'
                ) spd
                LEFT JOIN (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, SUM(spd.nilai) AS nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$kd_skpd}'
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal < {$spd->bulan_awal} AND spd_final.bulan_akhir < {$spd->bulan_akhir}
                    GROUP BY spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6
                ) spd_lalu
                ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_lalu.kd_rek6
                LEFT JOIN (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, spd.nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$kd_skpd}' AND (
                            (tglupdate < '{$spd->tglupdate}' AND bulan_awal = {$spd->bulan_awal} AND bulan_akhir = {$spd->bulan_akhir})
                            OR (bulan_awal <> {$spd->bulan_awal} AND bulan_akhir <> {$spd->bulan_akhir})
                        )
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal = {$spd->bulan_awal} AND spd_final.bulan_akhir = {$spd->bulan_akhir}
                ) spd_pra_revisi
                ON spd.kd_skpd = spd_pra_revisi.kd_skpd AND spd.kd_sub_kegiatan = spd_pra_revisi.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_pra_revisi.kd_rek6
                JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6
                GROUP BY spd.kd_sub_kegiatan, spd.nm_sub_kegiatan, spd.kd_skpd, spd.kd_rek6, spd.nm_rek6
            ) spd ORDER BY spd.kode"
        )->result_array();

        setlocale(LC_ALL, 'Indonesian');
        $view = $this->parser->parse('/anggaran/spd/cetak_lampiran_spd_revisi', array(
            'rincian_spd' => $result,
            'no_dpa' => $no_dpa,
            'no_spd' => $no_spd,
            'tgl_spd' => strftime('%d %B %Y', strtotime($spd->tgl_spd)),
            'nm_skpd' => $spd->nm_skpd,
            'bulan_awal' => $this->support->getBulan($spd->bulan_awal),
            'bulan_akhir' => $this->support->getBulan($spd->bulan_akhir),
            'total_spd' => number_format($spd->total, 2, ',', '.'),
            'total_spd_terbilang' => $this->support->terbilang($spd->total),
            'nm_ppkd' => $ms_ttd->nama,
            'nip_ppkd' => $ms_ttd->nip,
        ), true);
        $pdf = new Pdf(array(
            'binary' => $this->config->item('wkhtmltopdf_path'),
            'orientation' => 'Portrait',
        ));
        $pdf->addPage($view);
        $pdf->send();
    }

    public function get_column_anggaran($tgl_spd, $kd_skpd)
    {
        $sql = "SELECT
            CASE
                WHEN '$tgl_spd' >= tgl_dpa_ubah THEN 'nilai_ubah'
                WHEN '$tgl_spd' >= tgldpa_sempurna6 THEN 'nilaisempurna6'
                WHEN '$tgl_spd' >= tgldpa_sempurna5 THEN 'nilaisempurna5'
                WHEN '$tgl_spd' >= tgldpa_sempurna4 THEN 'nilaisempurna4'
                WHEN '$tgl_spd' >= tgldpa_sempurna3 THEN 'nilaisempurna3'
                WHEN '$tgl_spd' >= tgldpa_sempurna2 THEN 'nilaisempurna2'
                WHEN '$tgl_spd' >= tgl_dpa_sempurna THEN 'nilaisempurna1'
                ELSE 'nilai'
            END AS column_anggaran FROM trhrka WHERE kd_skpd = '$kd_skpd'";
        $query = $this->db->query($sql);
        return $query->row()->column_anggaran;
    }

    public function load_detail_spd()
    {
        $no_spd = $this->input->get('no_spd');
        $page = $this->input->get('page');
        $rows = $this->input->get('rows');

        $offset = ($page - 1) * $rows;

        $spd = $this->db->where('no_spd', $no_spd)->get('trhspd')->row();

        $row = $this->db->query("SELECT COUNT(*) AS num_of_rows, SUM(nilai) AS nilai_total FROM trdspd WHERE no_spd = '$no_spd'")->row();
        $num_of_rows = $row->num_of_rows;
        $nilai_total = $row->nilai_total;

        $detail_spd = $this->db->query("SELECT ROW_NUMBER() OVER (ORDER BY spd.kd_sub_kegiatan, spd.kd_rek6) AS id, spd.kd_sub_kegiatan, spd.nm_sub_kegiatan, spd.kd_rek6,
            spd.nm_rek6, spd.nilai AS nilai, ISNULL(spd_lalu.nilai, 0) AS nilai_lalu, anggaran.nilai AS nilai_anggaran
            FROM (
                (SELECT kd_skpd, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nilai
                FROM trdspd JOIN trhspd ON trdspd.no_spd = trhspd.no_spd WHERE trhspd.no_spd = '$no_spd') spd
                JOIN
                (SELECT kd_skpd, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nilai FROM trdrka) anggaran
                    ON spd.kd_skpd = anggaran.kd_skpd AND spd.kd_sub_kegiatan = anggaran.kd_sub_kegiatan AND spd.kd_rek6 = anggaran.kd_rek6
                LEFT JOIN
                (SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, SUM(spd.nilai) AS nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$spd->kd_skpd}'
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$spd->kd_skpd}'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal < {$spd->bulan_awal} AND spd_final.bulan_akhir < {$spd->bulan_akhir}
                    GROUP BY spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6
                ) spd_lalu
                    ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan AND spd.kd_rek6 = spd_lalu.kd_rek6
            ) ORDER BY kd_sub_kegiatan, kd_rek6 OFFSET $offset ROWS FETCH NEXT $rows ROWS ONLY
        ")->result_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array('rows' => $detail_spd, 'total' => $num_of_rows, 'nilai_total' => $nilai_total)));
    }

    public function insert_spd()
    {
        try {
            $this->db->trans_start();
            $no_spd = $this->input->post('no_spd');
            $tgl = $this->input->post('tgl');
            $kd_skpd = $this->input->post('kd_skpd');
            $bend = $this->input->post('bend');
            $bulan_awal = $this->input->post('bulan_awal');
            $bulan_akhir = $this->input->post('bulan_akhir');
            $ketentuan = $this->input->post('ketentuan');

            $spd_query = $this->db->where('no_spd', $no_spd)->get('trhspd');
            if ($spd_query->num_rows() > 0) {
                $this->output
                    ->set_status_header(422, 'Invalid input')
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array(
                        'message' => 'Gagal menambah SPD, Nomor SPD sudah digunakan',
                    )));
                return;
            }

            $skpd = $this->db->where('kd_skpd', $kd_skpd)->get('ms_skpd')->row();

            $column_angkas = $this->get_column_angkas($kd_skpd);
            $total_angkas_spd = $this->db->query(
                "SELECT SUM($column_angkas) AS total
                FROM trdskpd_ro WHERE kd_skpd='$kd_skpd'
                AND bulan BETWEEN $bulan_awal AND $bulan_akhir AND LEFT(kd_rek6, 1) = '5'"
            )->row()->total;
            $this->db->insert('trhspd', array(
                'no_spd' => $no_spd,
                'tgl_spd' => $tgl,
                'kd_skpd' => $kd_skpd,
                'nm_skpd' => $skpd->nm_skpd,
                'jns_beban' => '5',
                'bulan_awal' => $bulan_awal,
                'bulan_akhir' => $bulan_akhir,
                'total' => $total_angkas_spd,
                'klain' => $ketentuan,
                'kd_bkeluar' => $bend,
                'username' => $this->session->userdata('pcNama'),
                'tglupdate' => date('Y-m-d H:i:s'),
            ));

            $this->db->query(
                "INSERT INTO trdspd
                (no_spd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, nilai)
                SELECT '$no_spd' AS no_spd, ms_program.kd_program, nm_program, ms_kegiatan.kd_kegiatan, nm_kegiatan,
                    ms_sub_kegiatan.kd_sub_kegiatan, ms_sub_kegiatan.nm_sub_kegiatan, ms_rek6.kd_rek6, ms_rek6.nm_rek6, SUM(trdskpd_ro.$column_angkas)
                FROM trdskpd_ro JOIN ms_rek6 ON trdskpd_ro.kd_rek6 = ms_rek6.kd_rek6
                JOIN ms_sub_kegiatan ON trdskpd_ro.kd_sub_kegiatan = ms_sub_kegiatan.kd_sub_kegiatan
                JOIN ms_kegiatan ON ms_sub_kegiatan.kd_kegiatan = ms_kegiatan.kd_kegiatan
                JOIN ms_program ON ms_kegiatan.kd_program = ms_program.kd_program
                WHERE trdskpd_ro.kd_skpd = '$kd_skpd' AND bulan BETWEEN $bulan_awal AND $bulan_akhir AND LEFT(trdskpd_ro.kd_rek6, 1) = '5'
                GROUP BY ms_program.kd_program, nm_program, ms_kegiatan.kd_kegiatan, nm_kegiatan,
                ms_sub_kegiatan.kd_sub_kegiatan, ms_sub_kegiatan.nm_sub_kegiatan, ms_rek6.kd_rek6, ms_rek6.nm_rek6"
            );

            // $error_message = $this->db->_error_message();
            $error_message = 'Unknown Error';

            $this->db->trans_complete();
            if ($this->db->trans_status() === false) {
                throw new \Exception($error_message);
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array(
                        'message' => 'SPD telah berhasil ditambahkan',
                    )));
            }
        } catch (\Exception $e) {
            $this->output
                ->set_status_header(500, 'Server Error')
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'message' => 'SPD gagal ditambahkan',
                )));
            log_message('error', json_encode(array(
                'error_message' => $e->getMessage(),
                'no_spd' => $no_spd,
            )));
        }
    }

    public function cetak_lampiran_spd()
    {
        $no_spd = $this->input->get('no_spd');
        $this->load->library('parser');

        $spd = $this->db->where('no_spd', $no_spd)->get('trhspd')->row();
        $ms_ttd = $this->db->where('kode', 'PPKD')->get('ms_ttd')->row();
        $kd_skpd = $spd->kd_skpd;





        $column_anggaran = $this->get_column_anggaran($spd->tgl_spd, $kd_skpd);
        $no_dpa = $this->get_no_dpa($spd->tgl_spd, $kd_skpd);
        $result = $this->db->query(
            "SELECT * FROM (
                SELECT spd.kd_program AS kode, spd.nm_program AS nama, '' AS kd_rek, '' AS nm_rek, SUM(trdrka.nilai) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, 'program' AS jenis
                FROM (
                    SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                    WHERE trhspd.no_spd = '$no_spd'
                ) spd
                LEFT JOIN (


                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, SUM(spd.nilai) AS nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$kd_skpd}'
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal < {$spd->bulan_awal} AND spd_final.bulan_akhir < {$spd->bulan_akhir}
                    GROUP BY spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6
                


                ) spd_lalu
                ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_lalu.kd_rek6
                JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6
                GROUP BY spd.kd_program, spd.nm_program, spd.kd_skpd

                UNION ALL

                SELECT spd.kd_kegiatan AS kode, spd.nm_kegiatan AS nama, '' AS kd_rek, '' AS nm_rek, SUM(trdrka.$column_anggaran) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, 'kegiatan' AS jenis
                FROM (
                    SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                    WHERE trhspd.no_spd = '$no_spd'
                ) spd
                LEFT JOIN (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, SUM(spd.nilai) AS nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$kd_skpd}'
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal < {$spd->bulan_awal} AND spd_final.bulan_akhir < {$spd->bulan_akhir}
                    GROUP BY spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6
                ) spd_lalu
                ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_lalu.kd_rek6
                JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6
                GROUP BY spd.kd_kegiatan, spd.nm_kegiatan, spd.kd_skpd

                UNION ALL

                SELECT spd.kd_sub_kegiatan AS kode, spd.nm_sub_kegiatan AS nama, '' AS kd_rek, '' AS nm_rek, SUM(trdrka.$column_anggaran) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, 'sub_kegiatan' AS jenis
                FROM (
                    SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                    WHERE trhspd.no_spd = '$no_spd'
                ) spd
                LEFT JOIN (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, SUM(spd.nilai) AS nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$kd_skpd}'
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal < {$spd->bulan_awal} AND spd_final.bulan_akhir < {$spd->bulan_akhir}
                    GROUP BY spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6
                ) spd_lalu
                ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_lalu.kd_rek6
                JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6
                GROUP BY spd.kd_sub_kegiatan, spd.nm_sub_kegiatan, spd.kd_skpd

                UNION ALL

                SELECT spd.kd_sub_kegiatan AS kode, spd.nm_sub_kegiatan AS nama, spd.kd_rek6 AS kd_rek, spd.nm_rek6 AS nm_rek, SUM(trdrka.$column_anggaran) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, 'rekening' AS jenis
                FROM (
                    SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                    WHERE trhspd.no_spd = '$no_spd'
                ) spd
                LEFT JOIN (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, SUM(spd.nilai) AS nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$kd_skpd}'
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal < {$spd->bulan_awal} AND spd_final.bulan_akhir < {$spd->bulan_akhir}
                    GROUP BY spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6
                ) spd_lalu
                ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_lalu.kd_rek6
                JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6
                GROUP BY spd.kd_sub_kegiatan, spd.nm_sub_kegiatan, spd.kd_skpd, spd.kd_rek6, spd.nm_rek6
            ) spd ORDER BY spd.kode"
        )->result_array();

        setlocale(LC_ALL, 'Indonesian');
        $view = $this->parser->parse('/anggaran/spd/cetak_lampiran_spd', array(
            'rincian_spd' => $result,
            'no_dpa' => $no_dpa,
            'no_spd' => $no_spd,
            'tgl_spd' => strftime('%d %B %Y', strtotime($spd->tgl_spd)),
            'nm_skpd' => $spd->nm_skpd,
            'bulan_awal' => $this->support->getBulan($spd->bulan_awal),
            'bulan_akhir' => $this->support->getBulan($spd->bulan_akhir),
            'total_spd' => number_format($spd->total, 2, ',', '.'),
            'total_spd_terbilang' => $this->support->terbilang($spd->total),
            'nm_ppkd' => $ms_ttd->nama,
            'nip_ppkd' => $ms_ttd->nip,
        ), true);
        $pdf = new Pdf(array(
            'binary' => $this->config->item('wkhtmltopdf_path'),
            'orientation' => 'Portrait',
        ));
        $pdf->addPage($view);
        $pdf->send();
    }

    public function cetak_lampiran_spd3()
    {
        $no_spd = $this->input->get('no_spd');
        $this->load->library('parser');

        $spd = $this->db->where('no_spd', $no_spd)->get('trhspd')->row();
        $ms_ttd = $this->db->where('kode', 'PPKD')->get('ms_ttd')->row();
        $kd_skpd = $spd->kd_skpd;
        $lckdskpd = $kd_skpd;
        $ldtgl_spd = $spd->tgl_spd;

        $sql1   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='1' and tgl_spd<='$ldtgl_spd'";
        $q1     = $this->db->query($sql1);
        $w1     = $q1->row();
        $rev1   = $w1->revisi;

        //--------
        $sql2   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='2' and tgl_spd<='$ldtgl_spd'";
        $q2     = $this->db->query($sql2);
        $w2     = $q2->row();
        $rev2   = $w2->revisi;

        //--------
        $sql3   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='3' and tgl_spd<='$ldtgl_spd'";
        $q3     = $this->db->query($sql3);
        $w3     = $q3->row();
        $rev3   = $w3->revisi;

        //--------
        $sql4   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='4' and tgl_spd<='$ldtgl_spd'";
        $q4     = $this->db->query($sql4);
        $w4     = $q4->row();
        $rev4   = $w4->revisi;

        //--------
        $sql5   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='5' and tgl_spd<='$ldtgl_spd'";
        $q5     = $this->db->query($sql5);
        $w5     = $q5->row();
        $rev5   = $w5->revisi;

        //--------
        $sql6   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='6' and tgl_spd<='$ldtgl_spd'";
        $q6     = $this->db->query($sql6);
        $w6     = $q6->row();
        $rev6   = $w6->revisi;

        //--------
        $sql7   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='7' and tgl_spd<='$ldtgl_spd'";
        $q7     = $this->db->query($sql7);
        $w7     = $q7->row();
        $rev7   = $w7->revisi;

        //--------
        $sql8   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='8' and tgl_spd<='$ldtgl_spd'";
        $q8     = $this->db->query($sql8);
        $w8     = $q8->row();
        $rev8   = $w8->revisi;

        //--------
        $sql9   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='9' and tgl_spd<='$ldtgl_spd'";
        $q9     = $this->db->query($sql9);
        $w9     = $q9->row();
        $rev9   = $w9->revisi;

        //--------
        $sql10   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='10' and tgl_spd<='$ldtgl_spd'";
        $q10     = $this->db->query($sql10);
        $w10     = $q10->row();
        $rev10   = $w10->revisi;

        //--------
        $sql11   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='11' and tgl_spd<='$ldtgl_spd'";
        $q11     = $this->db->query($sql11);
        $w11     = $q11->row();
        $rev11   = $w11->revisi;

        //--------
        $sql12   = "SELECT max(revisi_ke) as revisi from trhspd where 
                                kd_skpd='$lckdskpd' 
                                and bulan_akhir='12' and tgl_spd<='$ldtgl_spd'";
        $q12     = $this->db->query($sql12);
        $w12     = $q12->row();
        $rev12   = $w12->revisi;



        $column_anggaran = 'nilai_sempurna';
        $no_dpa = $this->get_no_dpa($spd->tgl_spd, $kd_skpd);
        $result = $this->db->query(
            "SELECT * FROM (
                SELECT spd.kd_program AS kode, spd.nm_program AS nama, '' AS kd_rek, '' AS nm_rek, SUM(trdrka.$column_anggaran) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, 'program' AS jenis
                FROM (
                    SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                    WHERE trhspd.no_spd = '$no_spd'
                ) spd
                LEFT JOIN (

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) nilai from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='1'
                    and revisi_ke='$rev1'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='2'
                    and revisi_ke='$rev2'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='3'
                    and revisi_ke='$rev3'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='4'
                    and revisi_ke='$rev4'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='5'
                    and revisi_ke='$rev5'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='6'
                    and revisi_ke='$rev6'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='7'
                    and revisi_ke='$rev7'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='8'
                    and revisi_ke='$rev8'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='9'
                    and revisi_ke='$rev9'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='10'
                    and revisi_ke='$rev10'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='11'
                    and revisi_ke='$rev11'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='12'
                    and revisi_ke='$rev12'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6


                    
                    -- SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, SUM(spd.nilai) AS nilai FROM (
                    --     SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                    --     JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                    --     WHERE kd_skpd = '{$kd_skpd}'
                    --     GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    -- ) spd_final
                    -- JOIN (
                    --     SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                    --     FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                    --     WHERE kd_skpd = '$kd_skpd'
                    -- ) spd
                    -- ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    -- AND spd_final.kd_rek6 = spd.kd_rek6
                    -- AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    -- AND spd_final.latest_spd = spd.tglupdate
                    -- WHERE spd_final.bulan_awal < {$spd->bulan_awal} AND spd_final.bulan_akhir < {$spd->bulan_akhir}
                    -- GROUP BY spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6
                


                ) spd_lalu
                ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_lalu.kd_rek6
                JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6
                GROUP BY spd.kd_program, spd.nm_program, spd.kd_skpd

                UNION ALL

                SELECT spd.kd_kegiatan AS kode, spd.nm_kegiatan AS nama, '' AS kd_rek, '' AS nm_rek, SUM(trdrka.$column_anggaran) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, 'kegiatan' AS jenis
                FROM (
                    SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                    WHERE trhspd.no_spd = '$no_spd'
                ) spd
                LEFT JOIN (


                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) nilai from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='1'
                    and revisi_ke='$rev1'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='2'
                    and revisi_ke='$rev2'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='3'
                    and revisi_ke='$rev3'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='4'
                    and revisi_ke='$rev4'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='5'
                    and revisi_ke='$rev5'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='6'
                    and revisi_ke='$rev6'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='7'
                    and revisi_ke='$rev7'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='8'
                    and revisi_ke='$rev8'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='9'
                    and revisi_ke='$rev9'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='10'
                    and revisi_ke='$rev10'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='11'
                    and revisi_ke='$rev11'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='12'
                    and revisi_ke='$rev12'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6


                ) spd_lalu
                ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_lalu.kd_rek6
                JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6
                GROUP BY spd.kd_kegiatan, spd.nm_kegiatan, spd.kd_skpd

                UNION ALL

                SELECT spd.kd_sub_kegiatan AS kode, spd.nm_sub_kegiatan AS nama, '' AS kd_rek, '' AS nm_rek, SUM(trdrka.$column_anggaran) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, 'sub_kegiatan' AS jenis
                FROM (
                    SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                    WHERE trhspd.no_spd = '$no_spd'
                ) spd
                LEFT JOIN (
                    SELECT spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6, SUM(spd.nilai) AS nilai FROM (
                        SELECT kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir, MAX(tglupdate) AS latest_spd FROM trhspd
                        JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '{$kd_skpd}'
                        GROUP BY kd_skpd, kd_sub_kegiatan, kd_rek6, bulan_awal, bulan_akhir
                    ) spd_final
                    JOIN (
                        SELECT trhspd.no_spd, kd_skpd, kd_rek6, kd_sub_kegiatan, bulan_awal, bulan_akhir, tglupdate, nilai
                        FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                        WHERE kd_skpd = '$kd_skpd'
                    ) spd
                    ON spd_final.kd_skpd = spd.kd_skpd AND spd_final.kd_sub_kegiatan = spd.kd_sub_kegiatan
                    AND spd_final.kd_rek6 = spd.kd_rek6
                    AND spd_final.bulan_awal = spd.bulan_awal AND spd_final.bulan_akhir = spd.bulan_akhir
                    AND spd_final.latest_spd = spd.tglupdate
                    WHERE spd_final.bulan_awal < {$spd->bulan_awal} AND spd_final.bulan_akhir < {$spd->bulan_akhir}
                    GROUP BY spd.kd_skpd, spd.kd_sub_kegiatan, spd.kd_rek6
                ) spd_lalu
                ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_lalu.kd_rek6
                JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6
                GROUP BY spd.kd_sub_kegiatan, spd.nm_sub_kegiatan, spd.kd_skpd

                UNION ALL

                sSELECT spd.kd_sub_kegiatan AS kode, spd.nm_sub_kegiatan AS nama, spd.kd_rek6 AS kd_rek, spd.nm_rek6 AS nm_rek, SUM(trdrka.$column_anggaran) AS anggaran, SUM(spd.nilai) nilai, SUM(ISNULL(spd_lalu.nilai, 0)) AS nilai_lalu, 'rekening' AS jenis
                FROM (
                    SELECT trhspd.no_spd, kd_skpd, kd_program, nm_program, kd_kegiatan, nm_kegiatan, kd_sub_kegiatan, nm_sub_kegiatan, kd_rek6, nm_rek6, bulan_awal, bulan_akhir, nilai FROM trhspd JOIN trdspd ON trhspd.no_spd = trdspd.no_spd
                    WHERE trhspd.no_spd = '$no_spd'
                ) spd
                LEFT JOIN (
                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) nilai from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='1'
                    and revisi_ke='$rev1'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='2'
                    and revisi_ke='$rev2'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='3'
                    and revisi_ke='$rev3'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='4'
                    and revisi_ke='$rev4'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='5'
                    and revisi_ke='$rev5'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='6'
                    and revisi_ke='$rev6'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='7'
                    and revisi_ke='$rev7'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='8'
                    and revisi_ke='$rev8'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='9'
                    and revisi_ke='$rev9'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='10'
                    and revisi_ke='$rev10'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='11'
                    and revisi_ke='$rev11'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6

                    UNION ALL

                    select kd_skpd,kd_sub_kegiatan,kd_rek6,sum(nilai) from trdspd a inner join trhspd b  
                    on a.no_spd=b.no_spd
                    where b.kd_skpd='{$kd_skpd}' 
                    and a.no_spd<>'$no_spd' and bulan_akhir<{$spd->bulan_akhir} 
                    and bulan_awal='12'
                    and revisi_ke='$rev12'
                    GROUP BY kd_skpd,kd_sub_kegiatan,kd_rek6
                ) spd_lalu
                ON spd.kd_skpd = spd_lalu.kd_skpd AND spd.kd_sub_kegiatan = spd_lalu.kd_sub_kegiatan
                AND spd.kd_rek6 = spd_lalu.kd_rek6
                JOIN trdrka ON trdrka.kd_skpd = spd.kd_skpd AND trdrka.kd_sub_kegiatan = spd.kd_sub_kegiatan AND trdrka.kd_rek6 = spd.kd_rek6
                GROUP BY spd.kd_sub_kegiatan, spd.nm_sub_kegiatan, spd.kd_skpd, spd.kd_rek6, spd.nm_rek6
            ) spd ORDER BY spd.kode"
        )->result_array();

        setlocale(LC_ALL, 'Indonesian');
        $view = $this->parser->parse('/anggaran/spd/cetak_lampiran_spd', array(
            'rincian_spd' => $result,
            'no_dpa' => $no_dpa,
            'no_spd' => $no_spd,
            'tgl_spd' => strftime('%d %B %Y', strtotime($spd->tgl_spd)),
            'nm_skpd' => $spd->nm_skpd,
            'bulan_awal' => $this->support->getBulan($spd->bulan_awal),
            'bulan_akhir' => $this->support->getBulan($spd->bulan_akhir),
            'total_spd' => number_format($spd->total, 2, ',', '.'),
            'total_spd_terbilang' => $this->support->terbilang($spd->total),
            'nm_ppkd' => $ms_ttd->nama,
            'nip_ppkd' => $ms_ttd->nip,
        ), true);
        $pdf = new Pdf(array(
            'binary' => $this->config->item('wkhtmltopdf_path'),
            'orientation' => 'Portrait',
        ));
        $pdf->addPage($view);
        $pdf->send();
    }

    public function get_no_dpa($tgl_spd, $kd_skpd)
    {
        $data = $this->cek_anggaran_model->cek_anggaran($kd_skpd);
        $sql = "SELECT no_dpa from trhrka WHERE jns_ang='$data' and kd_skpd = '$kd_skpd' and tgl_dpa in(SELECT  MAX(tgl_dpa) from trhrka where kd_skpd=a.kd_skpd)";
        $query = $this->db->query($sql);
        return $query->row()->no_dpa;
    }

    public function toggle_status_spd()
    {
        try {
            $this->db->trans_start();
            $no_spd = $this->input->post('no_spd');
            $kd_skpd = $this->input->post('kd_skpd');

            $row_count = $this->db->where('no_spd', $no_spd)
                ->where('sp2d_batal <>', '1')
                ->select('COUNT(*) AS count')->get('trhspp')->row()->count;

            $spd = $this->db->where('no_spd', $no_spd)->where('kd_skpd', $kd_skpd)->get('trhspd')->row();

            if (is_null($spd->status) || $spd->status == 0) {
                $this->db->where('no_spd', $no_spd)->where('kd_skpd', $kd_skpd)->update('trhspd', array('status' => 1));
                $status = "Diaktifkan";
                $is_aktif = true;
            } else {
                if ($row_count > 0) {
                    $this->output
                        ->set_status_header(422, 'Invalid input')
                        ->set_content_type('application/json')
                        ->set_output(json_encode(array(
                            'message' => 'SPD sudah pernah di SPP-kan. Tidak dapat menonaktifkan SPD.',
                        )));
                    return;
                }
                $this->db->where('no_spd', $no_spd)->where('kd_skpd', $kd_skpd)->update('trhspd', array('status' => 0));
                $status = "Dinon-aktifkan";
                $is_aktif = false;
            }

            $error_message = 'Unknown Error';
            $this->db->trans_complete();
            if ($this->db->trans_status() === false) {
                throw new \Exception($error_message);
            } else {
                $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode(array(
                        'message' => "SPD telah berhasil $status",
                        'is_aktif' => $is_aktif,
                    )));
            }
        } catch (\Exception $e) {
            $this->output
                ->set_status_header(500, 'Server Error')
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'message' => "SPD gagal $status",
                )));
            log_message('error', json_encode(array(
                'error_message' => $e->getMessage(),
                'no_spd' => $no_spd,
            )));
        }
    }
}
