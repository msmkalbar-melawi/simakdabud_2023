<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Rka_ro extends CI_Controller
{

    public $ppkd = "";
    public $ppkd1 = "";

    function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('pcNama') == '') {
            redirect('welcome');
        }
    }
    //modul input angkas all
    function input_angkas()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO PENYEMPURNAAN 2';
        $this->template->set('title', 'INPUT ANGKAS PENYEMPURNAAN II');
        $this->template->load('template', 'anggaran/angkas_penyempurnaan/angkas_rop2', $data);
    }

    function load_jangkas($jang)
    {
        // $lccr = $this->input->post('q');  
        $result = $this->master_model->load_jangkas($jang);
        echo $result;
    }

    function load_jang()
    {
        // $lccr = $this->input->post('q');  
        $result = $this->master_model->load_jang();
        echo $result;
    }

    //
    function angkas_ro()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO MURNI';
        $this->template->set('title', 'INPUT ANGKAS MURNI');
        $this->template->load('template', 'anggaran/angkas/angkas_ro', $data);
    }

    function angkas_ro1()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO MURNI GESER 1';
        $this->template->set('title', 'INPUT ANGKAS MURNI GESER 1');
        $this->template->load('template', 'anggaran/angkas/angkas_ro1', $data);
    }

    function angkas_penyempurnaan()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO PENYEMPURNAAN';
        $this->template->set('title', 'INPUT ANGKAS PENYEMPURNAAN');
        $this->template->load('template', 'anggaran/angkas_penyempurnaan/angkas_rop', $data);
    }

    function anggaran_kas_penyempurnaan1()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO PENYEMPURNAAN 1 GESER 1';
        $this->template->set('title', 'INPUT ANGKAS PENYEMPURNAAN');
        $this->template->load('template', 'anggaran/angkas_penyempurnaan/angkas_rop11', $data);
    }

    function angkas_sempurna()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO MURNI';
        $this->template->set('title', 'INPUT ANGKAS MURNI');
        $this->template->load('template', 'anggaran/angkas/angkas_sempurna', $data);
    }

    function angkas_geser()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO PERGESERAN';
        $this->template->set('title', 'INPUT ANGKAS PERGESERAN');
        $this->template->load('template', 'anggaran/angkas/angkas_geser', $data);
    }

    function cek_anggaran_geser()
    {
        $data['page_title'] = 'Cek Anggaran Kas Pergeseran';
        $this->template->set('title', 'Cek Anggaran Kas Persgeseran');
        $this->template->load('template', 'anggaran/angkas/cek_anggaran_geser', $data);
    }

    function preview_cetakan_cek_anggaran_geser()
    {
        $id = $this->uri->segment(3);
        $cetak = $this->uri->segment(4);
        $status_ang = $this->uri->segment(5);
        echo $this->angkas_ro_model->preview_cetakan_cek_anggaran_geser($id, $cetak, $status_ang);
    }

    function cetak_angkas_giat_geser($jns_anggaran = '')
    {
        $data['jns_ang']   = $jns_anggaran;
        $data['page_title'] = 'Cetak Angkas Murni Subkegiatan';
        $this->template->set('title', 'Cetak Angkas Murni Subkegiatan');
        $this->template->load('template', 'anggaran/angkas/cetak_angkas_giat_geser', $data);
    }

    function angkas_ubah()
    {
        $data['page_title'] = 'INPUT RENCANA KEGIATAN ANGGARAN RO PERUBAHAN';
        $this->template->set('title', 'INPUT ANGKAS PERUBAHAN ');
        $this->template->load('template', 'anggaran/angkas/angkas_ubah', $data);
    }

    function skpduser()
    {
        $lccr = $this->input->post('q');
        $result = $this->master_model->skpduser($lccr);
        echo json_encode($result);
    }

    function ambil_rek_angkas_ro($kegiatan = '', $skpd = '')
    {
        $result = $this->angkas_ro_model->ambil_rek_angkas_ro($kegiatan, $skpd);
        echo json_encode($result);
    }

    // function ambil_rek_angkas_ro_geser($kegiatan = '', $skpd = '')
    // {
    //     $result = $this->angkas_ro_model->ambil_rek_angkas_ro_geser($kegiatan, $skpd);
    //     echo json_encode($result);
    // }

    function ambil_rek_angkas_ro_geser()
    {
        $kegiatan   = $this->uri->segment(3);
        $skpd      = $this->uri->segment(4);
        $rak        = $this->uri->segment(5);

        $result = $this->angkas_ro_model->ambil_rek_angkas_ro_geser($kegiatan, $skpd, $rak);
        echo json_encode($result);
    }

    function load_giat()
    {
        $cskpd = $this->uri->segment(3);
        $lccr = $this->input->post('q');
        $result = $this->angkas_ro_model->load_giat($cskpd, $lccr);
        echo json_encode($result);
    }

    function load_giat_sempurna()
    {
        $cskpd = $this->uri->segment(3);
        $jns_ang = $this->uri->segment(4);
        $lccr = $this->input->post('q');
        $result = $this->angkas_ro_model->load_giat_sempurna($cskpd, $lccr, $jns_ang);
        echo json_encode($result);
    }

    function total_triwulan($status = '', $skpd = '')
    {
        $kd_kegiatan = $this->input->post('kegiatan');
        $result = $this->angkas_ro_model->total_triwulan($status, $kd_kegiatan, $skpd);
        echo json_encode($result);
    }

    function total_triwulan_geser($status = '', $skpd = '')
    {
        $kd_kegiatan = $this->input->post('kegiatan');
        $result = $this->angkas_ro_model->total_triwulan_geser($status, $kd_kegiatan, $skpd);
        echo json_encode($result);
    }

    function total_triwulan_sempurna($status = '', $skpd = '')
    {
        $kd_kegiatan = $this->input->post('kegiatan');
        $result = $this->angkas_ro_model->total_triwulan($status, $kd_kegiatan, $skpd);
        echo json_encode($result);
    }

    function load_trdskpd($status = '', $skpd = '')
    {
        $kegiatan = $this->input->post('p');
        $rekening = $this->input->post('s');
        $result = $this->angkas_ro_model->load_trdskpd($kegiatan, $rekening, $status, $skpd);
        echo json_encode($result);
    }

    function load_trdskpd_geser($status = '', $skpd = '')
    {
        $kegiatan = $this->input->post('p');
        $rekening = $this->input->post('s');
        $result = $this->angkas_ro_model->load_trdskpd_geser($kegiatan, $rekening, $status, $skpd);
        echo json_encode($result);
    }

    function simpan_trskpd_ro()
    {
        $id  = $this->session->userdata('pcUser');
        $cskpda = $this->input->post('cskpda');
        $cskpd = $this->input->post('cskpd');
        $cgiat = $this->input->post('cgiat');
        $crek5 = $this->input->post('crek5');
        $bln1 = $this->input->post('jan');
        $bln2 = $this->input->post('feb');
        $bln3 = $this->input->post('mar');
        $bln4 = $this->input->post('apr');
        $bln5 = $this->input->post('mei');
        $bln6 = $this->input->post('jun');
        $bln7 = $this->input->post('jul');
        $bln8 = $this->input->post('ags');
        $bln9 = $this->input->post('sep');
        $bln10 = $this->input->post('okt');
        $bln11 = $this->input->post('nov');
        $bln12 = $this->input->post('des');
        $tr1 = $this->input->post('tr1');
        $tr2 = $this->input->post('tr2');
        $tr3 = $this->input->post('tr3');
        $tr4 = $this->input->post('tr4');
        $status = $this->input->post('csts');
        $tabell = 'trdskpd_ro';
        $user_name  =  $this->session->userdata('pcNama');
        $result = $this->angkas_ro_model->simpan_trskpd_ro($cskpda, $status, $cskpd, $cskpd, $cgiat, $crek5, $bln1, $bln2, $bln3, $bln4, $bln5, $bln6, $bln7, $bln8, $bln9, $bln10, $bln11, $bln12, $tr1, $tr2, $tr3, $tr4, $status, $user_name);

        echo $result;
    }

    function hapus_angkas()
    {
        $kd_skpd  = $this->input->post('skpd');
        $sub_keg = $this->input->post('kd_sub_keg');
        $rek6 = $this->input->post('kd_rek6');

        // $xx = array('skpd' => $kd_skpd,
        //             'kegiatan' => $sub_keg,
        //             'rek6' => $rek6);

        $sql = "delete trdskpd_ro WHERE kd_skpd = '$kd_skpd' AND kd_sub_kegiatan = '$sub_keg' AND kd_rek6 = '$rek6'";
        // print_r($sql);die();
        $this->db->query($sql);
    }

    function realisasi_angkas_ro($skpd = '')
    {
        $skpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');
        $rek5 = $this->input->post('rek5');
        $result = $this->angkas_ro_model->realisasi_angkas_ro($skpd, $kegiatan, $rek5);
        echo $result;
    }

    function realisasi_angkas_ro_bulan($skpd = '')
    {
        $skpd = $this->input->post('skpd');
        $kegiatan = $this->input->post('keg');
        $rek5 = $this->input->post('rek5');
        $result = $this->angkas_ro_model->realisasi_angkas_ro_bulan($skpd, $kegiatan, $rek5);
        echo $result;
    }

    function  tanggal_format_indonesia($tgl)
    {
        $tanggal  =  substr($tgl, 8, 2);
        $bulan  = $this->getBulan(substr($tgl, 5, 2));
        $tahun  =  substr($tgl, 0, 4);
        return  $tanggal . ' ' . $bulan . ' ' . $tahun;
    }

    function cetak_angkas_ro($jns_anggaran = '')
    {
        $data['jns_ang']   = $jns_anggaran;
        $data['page_title'] = 'Cetak Angkas Murni RO';
        $this->template->set('title', 'Cetak Angkas Murni RO');
        $this->template->load('template', 'anggaran/angkas/cetak_angkas_ro', $data);
    }

    function cetak_angkas_giat($jns_anggaran = '')
    {
        $data['jns_ang']   = $jns_anggaran;
        $data['page_title'] = 'Cetak Angkas Murni Subkegiatan';
        $this->template->set('title', 'Cetak Angkas Murni Subkegiatan');
        $this->template->load('template', 'anggaran/angkas/cetak_angkas_giat', $data);
    }

    function cetak_angkas_ro_preview($aa = '', $tgl = '', $ttd1 = '', $ttd2 = '', $tj_ang = '', $tj_angkas = '', $skpd = '', $giat = '', $hit = '', $cetak = '')
    {
        echo $this->angkas_ro_model->cetak_angkas_ro($tgl, $ttd1, $ttd2, $tj_ang, $tj_angkas, $skpd, $giat, $hit, $cetak);
    }


    // function cetak_angkas_ro_preview($aa = '', $tgl = '', $ttd1 = '', $ttd2 = '', $jenis = '', $skpd = '', $giat = '', $hit = '', $cetak = '')
    // {
    //     echo $this->angkas_ro_model->cetak_angkas_ro($tgl, $ttd1, $ttd2, $jenis, $skpd, $giat, $hit, $cetak);
    // }

    function cetak_angkas_giat_preview($aa = '', $tgl = '', $ttd1 = '', $ttd2 = '', $jenis = '', $skpd = '', $cetak = '', $hit = '')
    {
        echo $this->angkas_ro_model->cetak_angkas_giat($tgl, $ttd1, $ttd2, $jenis, $skpd, $cetak, $hit);
    }

    // function preview_cetakan_cek_anggaran()
    // {
    //     $id = $this->uri->segment(3);
    //     $cetak = $this->uri->segment(4);
    //     $status_ang = $this->uri->segment(5);
    //     echo $this->angkas_ro_model->preview_cetakan_cek_anggaran($id, $cetak, $status_ang);
    // }
    function cek_angkas()
    {
        $data['page_title'] = 'Cek Anggaran';
        $this->template->set('title', 'Cek Anggaran');
        $this->template->load('template', 'anggaran/angkas/cek_anggaran', $data);
    }

    function preview_cetakan_cek_anggaran()
    {
        $id = $this->uri->segment(3);
        $sts_angkas = $this->uri->segment(6);
        if ($sts_angkas == 'murni') {
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
        } else if ($sts_angkas == 'sempurna1') {
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
        } else if ($sts_angkas == 'ubah') {
            $field_angkas = 'nilai_ubah';
        } else if ($sts_angkas == 'ubah1') {
            $field_angkas = 'nilai_ubah1';
        } else if ($sts_angkas == 'ubah2') {
            $field_angkas = 'nilai_ubah2';
        } else if ($sts_angkas == 'ubah3') {
            $field_angkas = 'nilai_ubah3';
        } else if ($sts_angkas == 'ubah4') {
            $field_angkas = 'nilai_ubah4';
        } else {
            $field_angkas = 'nilai_ubah5';
        }

        $cetak = $this->uri->segment(4);
        $status_ang = $this->uri->segment(5);

        echo $this->angkas_ro_model->preview_cetakan_cek_anggaran($id, $cetak, $status_ang, $field_angkas);
    }

    function load_ttd_unit($skpd)
    {
        $lccr = $this->input->post('q');
        $result = $this->master_ttd->load_ttd_unit($skpd);
        echo $result;
    }

    function load_ttd_bud()
    {
        $lccr = $this->input->post('q');
        $result = $this->master_ttd->load_ttd_bud();
        echo $result;
    }
    function pengesahan_angkas()
    {
        $data['page_title'] = 'Pengesahan Anggaran Kas';
        $this->template->set('title', 'Pengesahan Anggaran Kas');
        $this->template->load('template', 'anggaran/angkas/pengesahan_angkas', $data);
    }

    function simpan_pengesahan()
    {
        $kdskpd = $this->input->post('s_skpd');
        $u_murni = $this->input->post('s_murni');
        $u_murni_geser1 = $this->input->post('s_murni_geser1');
        $u_murni_geser2 = $this->input->post('s_murni_geser2');
        $u_murni_geser3 = $this->input->post('s_murni_geser3');
        $u_murni_geser4 = $this->input->post('s_murni_geser4');
        $u_murni_geser5 = $this->input->post('s_murni_geser5');
        $u_sempurna1 = $this->input->post('s_sempurna1');
        $u_sempurna1_geser1 = $this->input->post('s_sempurna1_geser1');
        $u_sempurna1_geser2 = $this->input->post('s_sempurna1_geser2');
        $u_sempurna1_geser3 = $this->input->post('s_sempurna1_geser3');
        $u_sempurna1_geser4 = $this->input->post('s_sempurna1_geser4');
        $u_sempurna1_geser5 = $this->input->post('s_sempurna1_geser5');
        $u_sempurna2 = $this->input->post('s_sempurna2');
        $u_sempurna2_geser1 = $this->input->post('s_sempurna2_geser1');
        $u_sempurna2_geser2 = $this->input->post('s_sempurna2_geser2');
        $u_sempurna2_geser3 = $this->input->post('s_sempurna2_geser3');
        $u_sempurna2_geser4 = $this->input->post('s_sempurna2_geser4');
        $u_sempurna2_geser5 = $this->input->post('s_sempurna2_geser5');
        $u_sempurna3 = $this->input->post('s_sempurna3');
        $u_sempurna3_geser1 = $this->input->post('s_sempurna3_geser1');
        $u_sempurna3_geser2 = $this->input->post('s_sempurna3_geser2');
        $u_sempurna3_geser3 = $this->input->post('s_sempurna3_geser3');
        $u_sempurna3_geser4 = $this->input->post('s_sempurna3_geser4');
        $u_sempurna3_geser5 = $this->input->post('s_sempurna3_geser5');
        $u_sempurna4 = $this->input->post('s_sempurna4');
        $u_sempurna4_geser1 = $this->input->post('s_sempurna4_geser1');
        $u_sempurna4_geser2 = $this->input->post('s_sempurna4_geser2');
        $u_sempurna4_geser3 = $this->input->post('s_sempurna4_geser3');
        $u_sempurna4_geser4 = $this->input->post('s_sempurna4_geser4');
        $u_sempurna4_geser5 = $this->input->post('s_sempurna4_geser5');
        $u_sempurna5 = $this->input->post('s_sempurna5');
        $u_sempurna5_geser1 = $this->input->post('s_sempurna5_geser1');
        $u_sempurna5_geser2 = $this->input->post('s_sempurna5_geser2');
        $u_sempurna5_geser3 = $this->input->post('s_sempurna5_geser3');
        $u_sempurna5_geser4 = $this->input->post('s_sempurna5_geser4');
        $u_sempurna5_geser5 = $this->input->post('s_sempurna5_geser5');
        $u_ubah = $this->input->post('s_ubah');
        $u_ubah1 = $this->input->post('s_ubah1');
        $u_ubah2 = $this->input->post('s_ubah2');
        $u_ubah3 = $this->input->post('s_ubah3');
        $u_ubah4 = $this->input->post('s_ubah4');
        $u_ubah5     = $this->input->post('s_ubah5');
        $currentdate = date("Y-m-d H:i:s");
        $user_name  =  $this->session->userdata('pcNama');


        $sql2 = "UPDATE status_angkas  set  
    murni = '$u_murni',
    murni_geser1 = '$u_murni_geser1',
    murni_geser2 = '$u_murni_geser2',
    murni_geser3 = '$u_murni_geser3',
    murni_geser4 = '$u_murni_geser4',
    murni_geser5 = '$u_murni_geser5',
    sempurna1 = '$u_sempurna1',
    sempurna1_geser1 = '$u_sempurna1_geser1',
    sempurna1_geser2 = '$u_sempurna1_geser2',
    sempurna1_geser3 = '$u_sempurna1_geser3',
    sempurna1_geser4 = '$u_sempurna1_geser4',
    sempurna1_geser5 = '$u_sempurna1_geser5',
    sempurna2 = '$u_sempurna2',
    sempurna2_geser1 = '$u_sempurna2_geser1',
    sempurna2_geser2 = '$u_sempurna2_geser2',
    sempurna2_geser3 = '$u_sempurna2_geser3',
    sempurna2_geser4 = '$u_sempurna2_geser4',
    sempurna2_geser5 = '$u_sempurna2_geser5',
    sempurna3 = '$u_sempurna3',
    sempurna3_geser1 = '$u_sempurna3_geser1',
    sempurna3_geser2 = '$u_sempurna3_geser2',
    sempurna3_geser3 = '$u_sempurna3_geser3',
    sempurna3_geser4 = '$u_sempurna3_geser4',
    sempurna3_geser5 = '$u_sempurna3_geser5',
    sempurna4 = '$u_sempurna4',
    sempurna4_geser1 = '$u_sempurna4_geser1',
    sempurna4_geser2 = '$u_sempurna4_geser2',
    sempurna4_geser3 = '$u_sempurna4_geser3',
    sempurna4_geser4 = '$u_sempurna4_geser4',
    sempurna4_geser5 = '$u_sempurna4_geser5',
    sempurna5 = '$u_sempurna5',
    sempurna5_geser1 = '$u_sempurna5_geser1',
    sempurna5_geser2 = '$u_sempurna5_geser2',
    sempurna5_geser3 = '$u_sempurna5_geser3',
    sempurna5_geser4 = '$u_sempurna5_geser4',
    sempurna5_geser5 = '$u_sempurna5_geser5',
    ubah = '$u_ubah',
    ubah1 = '$u_ubah1',
    ubah2 = '$u_ubah2',
    ubah3 = '$u_ubah3',
    ubah4 = '$u_ubah4',
    ubah5     = '$u_ubah5',
    user_sah = '$user_name',
    last_update = '$currentdate'
    where kd_skpd='$kdskpd'";

        $asg = $this->db->query($sql2);

        if ($asg) {
            echo '1';
            exit();
        } else {
            echo '0';
            exit();
        }
    }

    function load_pengesahan_angkas()
    {
        $result = array();
        $row = array();
        $id  = $this->session->userdata('pcUser');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = "where kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id')";
        if ($kriteria <> '') {
            $where = "where (upper(kd_skpd) like upper('%$kriteria%') or nm_skpd like'%$kriteria%') and kd_skpd IN 
                        (SELECT kd_skpd FROM user_bud WHERE user_id='$id')";
        }

        $sql = "SELECT count(*) as tot from status_angkas $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT * from status_angkas $where  order by kd_skpd ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id'            => $ii,
                'kd_skpd'       => $resulte['kd_skpd'],
                'nm_skpd'       => $resulte['nm_skpd'],
                'murni'         => $resulte['murni'],
                'murni_geser1'  => $resulte['murni_geser1'],
                'murni_geser2'  => $resulte['murni_geser2'],
                'murni_geser3'  => $resulte['murni_geser3'],
                'murni_geser4'  => $resulte['murni_geser4'],
                'murni_geser5'  => $resulte['murni_geser5'],
                'sempurna1'     => $resulte['sempurna1'],
                'sempurna1_geser1' => $resulte['sempurna1_geser1'],
                'sempurna1_geser2' => $resulte['sempurna1_geser2'],
                'sempurna1_geser3' => $resulte['sempurna1_geser3'],
                'sempurna1_geser4' => $resulte['sempurna1_geser4'],
                'sempurna1_geser5' => $resulte['sempurna1_geser5'],
                'sempurna2'     => $resulte['sempurna2'],
                'sempurna2_geser1' => $resulte['sempurna2_geser1'],
                'sempurna2_geser2' => $resulte['sempurna2_geser2'],
                'sempurna2_geser3' => $resulte['sempurna2_geser3'],
                'sempurna2_geser4' => $resulte['sempurna2_geser4'],
                'sempurna2_geser5' => $resulte['sempurna2_geser5'],
                'sempurna3'     => $resulte['sempurna3'],
                'sempurna3_geser1' => $resulte['sempurna3_geser1'],
                'sempurna3_geser2' => $resulte['sempurna3_geser2'],
                'sempurna3_geser3' => $resulte['sempurna3_geser3'],
                'sempurna3_geser4' => $resulte['sempurna3_geser4'],
                'sempurna3_geser5' => $resulte['sempurna3_geser5'],
                'sempurna4'     => $resulte['sempurna4'],
                'sempurna4_geser1' => $resulte['sempurna4_geser1'],
                'sempurna4_geser2' => $resulte['sempurna4_geser2'],
                'sempurna4_geser3' => $resulte['sempurna4_geser3'],
                'sempurna4_geser4' => $resulte['sempurna4_geser4'],
                'sempurna4_geser5' => $resulte['sempurna4_geser5'],
                'sempurna5'     => $resulte['sempurna5'],
                'sempurna5_geser1' => $resulte['sempurna5_geser1'],
                'sempurna5_geser2' => $resulte['sempurna5_geser2'],
                'sempurna5_geser3' => $resulte['sempurna5_geser3'],
                'sempurna5_geser4' => $resulte['sempurna5_geser4'],
                'sempurna5_geser5' => $resulte['sempurna5_geser5'],
                'ubah' => $resulte['ubah'],
                'ubah1' => $resulte['ubah1'],
                'ubah2' => $resulte['ubah2'],
                'ubah3' => $resulte['ubah3'],
                'ubah4' => $resulte['ubah4'],
                'ubah5' => $resulte['ubah5']
            );
            $ii++;
        }

        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function ambil_rak()
    {
        $result = $this->angkas_ro_model->ambil_rak();
        echo json_encode($result);
    }

    function stts_kunci_angkas()
    {
        $kunci_rak  = $this->input->post('kunci_rak');
        $kd_skpd    = $this->session->userdata('kdskpd');
        $result     = $this->angkas_ro_model->stts_kunci_angkas($kunci_rak, $kd_skpd);
        echo json_encode($result);
    }
}
