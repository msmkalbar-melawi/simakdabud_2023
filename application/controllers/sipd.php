<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Sipd extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_sipd');
    }

    function subkegiatan()
    {
        $data['page_title'] = 'INTEGRASI SIPD';
        $this->template->set('title', 'INTEGRASI SIPD');
        $this->template->load('template', 'anggaran/sipd/sipd_sub', $data);
    }

    function uploadSubKegiatan()
    {
        $filename = '';
        $upload_path = 'json/';
        if (substr(sprintf('%o', fileperms($upload_path)), -4) != '0777') {
            chmod($upload_path, 0777);
        }

        if (isset($_FILES['file']['tmp_name']) and is_uploaded_file($_FILES['file']['tmp_name'])) {
            $filename               = $this->security->sanitize_filename(strtolower($_FILES['file']['name']));
            $filename               = 'file_' . $filename;
            $data['file']          = $filename;
            $destination            = $upload_path . $filename;
            move_uploaded_file($_FILES['file']['tmp_name'], $destination);
        }

        $data['id']     = '';
        $data['title']          = $this->input->post('judul');

        $result = $this->M_sipd->simpan_upload($data);

        if ($result) {
            header('Content-Type: application/json');

            $xdata = json_decode(file_get_contents('json/' . $filename));

            $a = array();
            foreach ($xdata->data as $row) {


                $id_urusan = $row->id_urusan;
                $kode_urusan = $row->kode_urusan;
                $nama_urusan = $row->nama_urusan;
                $id_bidang_urusan = $row->id_bidang_urusan;
                $kode_bidang_urusan = $row->kode_bidang_urusan;
                $nama_bidang_urusan = $row->nama_bidang_urusan;
                $id_program = $row->id_program;
                $kode_program = $row->kode_program;
                $nama_program = $row->nama_program;
                $id_giat = $row->id_giat;
                $kode_giat = $row->kode_giat;
                $nama_giat = $row->nama_giat;
                $id_sub_giat = $row->id_sub_giat;
                $kode_sub_giat = $row->kode_sub_giat;
                $nama_sub_giat = $row->nama_sub_giat;


                $a[] = array(
                    'id_urusan' => $id_urusan,
                    'kode_urusan' => $kode_urusan,
                    'nama_urusan' => $nama_urusan,
                    'id_bidang_urusan' => $id_bidang_urusan,
                    'kode_bidang_urusan' => $kode_bidang_urusan,
                    'nama_bidang_urusan' => $nama_bidang_urusan,
                    'id_program' => $id_program,
                    'kode_program' => $kode_program,
                    'nama_program' => $nama_program,
                    'id_giat' => $id_giat,
                    'kode_giat' => $kode_giat,
                    'nama_giat' => $nama_giat,
                    'id_sub_giat' => $id_sub_giat,
                    'kode_sub_giat' => $kode_sub_giat,
                    'nama_sub_giat' => $nama_sub_giat
                );
            }


            $a = $this->db->insert_batch('sipd_sub_kegiatan', $a);

            if ($a) {
                $this->session->set_flashdata('msg', '1');
                redirect('sipd/subkegiatan');
            } else {
                $this->session->set_flashdata('msg', '2');
                redirect('sipd/subkegiatan');
            }
        }
    }

    function akun()
    {
        $data['page_title'] = 'INTEGRASI SIPD';
        $this->template->set('title', 'INTEGRASI SIPD');
        $this->template->load('template', 'anggaran/sipd/sipd_akun', $data);
    }

    function uploadAkun()
    {
        $filename = '';
        $upload_path = 'json/';
        if (substr(sprintf('%o', fileperms($upload_path)), -4) != '0777') {
            chmod($upload_path, 0777);
        }

        if (isset($_FILES['file']['tmp_name']) and is_uploaded_file($_FILES['file']['tmp_name'])) {
            $filename               = $this->security->sanitize_filename(strtolower($_FILES['file']['name']));
            $filename               = 'file_' . $filename;
            $data['file']          = $filename;
            $destination            = $upload_path . $filename;
            move_uploaded_file($_FILES['file']['tmp_name'], $destination);
        }

        $data['id']     = '';
        $data['title']          = $this->input->post('judul');

        $result = $this->M_sipd->simpan_upload($data);

        if ($result) {
            header('Content-Type: application/json');

            $xdata = json_decode(file_get_contents('json/' . $filename));

            $a = array();
            foreach ($xdata->data as $row) {


                $id_akun = $row->id_akun;
                $tahun = $row->tahun;
                $id_daerah = $row->id_daerah;
                $kode_akun = $row->kode_akun;
                $nama_akun = $row->nama_akun;
                $is_pendapatan = $row->is_pendapatan;
                $is_bl = $row->is_bl;
                $is_pembiayaan = $row->is_pembiayaan;
                $id_unik = $row->id_unik;


                $a[] = array(
                    'id_akun' => $id_akun,
                    'tahun' => $tahun,
                    'id_daerah' => $id_daerah,
                    'kode_akun' => $kode_akun,
                    'nama_akun' => $nama_akun,
                    'is_pendapatan' => $is_pendapatan,
                    'is_bl' => $is_bl,
                    'is_pembiayaan' => $is_pembiayaan,
                    'id_unik' => $id_unik
                );
            }


            $a = $this->db->insert_batch('sipd_akun', $a);

            if ($a) {
                $this->session->set_flashdata('msg', '1');
                redirect('sipd/akun');
            } else {
                $this->session->set_flashdata('msg', '2');
                redirect('sipd/akun');
            }
        }
    }

    function dana()
    {
        $data['page_title'] = 'INTEGRASI SIPD';
        $this->template->set('title', 'INTEGRASI SIPD');
        $this->template->load('template', 'anggaran/sipd/sipd_sdana', $data);
    }
    function uploadDana()
    {
        // $filename = '';
        // $upload_path = 'json/';
        // if (substr(sprintf('%o', fileperms($upload_path)), -4) != '0777') {
        //     chmod($upload_path, 0777);
        // }

        // if (isset($_FILES['file']['tmp_name']) and is_uploaded_file($_FILES['file']['tmp_name'])) {
        //     $filename               = $this->security->sanitize_filename(strtolower($_FILES['file']['name']));
        //     $filename               = 'file_' . $filename;
        //     $data['file']          = $filename;
        //     $destination            = $upload_path . $filename;
        //     move_uploaded_file($_FILES['file']['tmp_name'], $destination);
        // }

        // $data['id']     = '';
        // $data['title']          = $this->input->post('judul');

        // $result = $this->M_sipd->simpan_upload($data);

        // if ($result) {
            header('Content-Type: application/json');

            $xdata = json_decode(file_get_contents('json/dana.json'));

            $a = array();
            foreach ($xdata->data as $row) {


                $id_dana = $row->id_dana;
                $tahun = $row->tahun;
                $id_daerah = $row->id_daerah;
                $kode_dana = $row->kode_dana;
                $nama_dana = $row->nama_dana;
                $id_unik = $row->id_unik;
                $set_input = $row->set_input;



                $a[] = array(
                    'id_dana' => $id_dana,
                    'tahun' => $tahun,
                    'id_daerah' => $id_daerah,
                    'kode_dana' => $kode_dana,
                    'nama_dana' => $nama_dana,
                    'id_unik' => $id_unik,
                    'set_input' => $set_input
                );

                $b[] = array(

                    'kd_sdana' => $kode_dana,
                    'nm_sdana' => $nama_dana
                );
            }

            $this->db->query('delete from sipd_dana');
            $this->db->query('delete from sipd_sdana');


            $a = $this->db->insert('sipd_dana', $a);
            $b = $this->db->insert('sipd_sdana', $b);

            if ($a) {
                $this->session->set_flashdata('msg', '1');
                redirect('sipd/dana');
            } else {
                $this->session->set_flashdata('msg', '2');
                redirect('sipd/dana');
            }
        // }
    }

    function komponen()
    {
        $data['page_title'] = 'INTEGRASI SIPD';
        $this->template->set('title', 'INTEGRASI SIPD');
        $this->template->load('template', 'anggaran/sipd/sipd_komponen', $data);
    }

    function uploadKomponen()
    {
        $filename = '';
        $upload_path = 'json/';
        if (substr(sprintf('%o', fileperms($upload_path)), -4) != '0777') {
            chmod($upload_path, 0777);
        }

        if (isset($_FILES['file']['tmp_name']) and is_uploaded_file($_FILES['file']['tmp_name'])) {
            $filename               = $this->security->sanitize_filename(strtolower($_FILES['file']['name']));
            $filename               = 'file_' . $filename;
            $data['file']          = $filename;
            $destination            = $upload_path . $filename;
            move_uploaded_file($_FILES['file']['tmp_name'], $destination);
        }

        $data['id']     = '';
        $data['title']          = $this->input->post('judul');

        $result = $this->M_sipd->simpan_upload($data);

        if ($result) {
            header('Content-Type: application/json');

            $xdata = json_decode(file_get_contents('json/' . $filename));

            $a = array();
            foreach ($xdata->data as $row) {


                $kode_kel_standar_harga = $row->kode_kel_standar_harga;
                $nama_kel_standar_harga = $row->nama_kel_standar_harga;
                $id_standar_harga = $row->id_standar_harga;
                $kode_standar_harga = $row->kode_standar_harga;
                $nama_standar_harga = $row->nama_standar_harga;
                $spek = $row->spek;
                $satuan = $row->satuan;
                $harga = $row->harga;
                $harga_2 = $row->harga_2;
                $harga_3 = $row->harga_3;



                $a[] = array(
                    'kode_kel_standar_harga' => $kode_kel_standar_harga,
                    'nama_kel_standar_harga' => $nama_kel_standar_harga,
                    'id_standar_harga' => $id_standar_harga,
                    'kode_standar_harga' => $kode_standar_harga,
                    'nama_standar_harga' => $nama_standar_harga,
                    'spek' => $spek,
                    'satuan' => $satuan,
                    'harga' => $harga,
                    'harga_3' => $harga_3,
                );
            }


            $a = $this->db->insert_batch('sipd_komponen_belanja', $a);

            if ($a) {
                $this->session->set_flashdata('msg', '1');
                redirect('sipd/komponen');
            } else {
                $this->session->set_flashdata('msg', '2');
                redirect('sipd/komponen');
            }
        }
    }
}
