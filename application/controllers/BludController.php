<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class BludController extends CI_Controller
{
    public function __contruct()
    {
        parent::__construct();
        $this->load->model(['support', 'akuntansi_model']);
    }

    public function index()
    {

        $skpd = $this->support->skpdBludFirst();
        $months = $this->support->generateBulan();
        $data = [
            'page_title' => 'Cetak Laporan BLUD',
            'skpd' => $skpd,
            'months' => $months,
            'statusAnggaran' => $this->statusAnggaran()
        ];
        $this->template->set('title', 'Laporan Realisasi Anggaran BLUD');
        return $this->template->load('template', 'akuntansi/realisasi-anggaran/bludView', $data);
    }

    /**
     * Method ini bertujuan untuk mencetak laporan operasional blud perbulan atar perperiode tanggal tertentu
     * Method ini dikususkan untu Rumah Sakit Umum Daerah
     * @param string $jenis jenis data yang akan di ambil perbulan atau periode
     * @param string $tipe tipe cetak yang dikirim via url, tipe tersesia preview, pdf dan excel
     * @param ?string $bulan opsional parameter bulan yang di pilih, wajib di isi jika jenis cetak perbulan
     * @param ?string $tanggalAwal opsional parameter tanggal awal peridode, wajib jika jenis cetak perpriode
     * @param ?string $tanggalAkhir opsional parameter tanggal akhir periode, waji jika jenis cetak perproiode
     */
    public function cetak()
    {
        $data = array();
        $skpd = $this->support->skpdBludFirst();
        $klien = $this->akuntansi_model->get_sclient();
        $tipeCetak = $this->input->get('tipeCetak');
        $jenisAnggaran = $this->input->get('jenisAnggaran');
        $jenis = $this->input->get('jenis');
        $bulan = '';
        $tanggalAwal = '';
        $tanggalAkhir = '';

        // get tahun anggaran
        $tahun  = $this->session->userdata('pcThang');

        if ($jenis === 'bulan') {
            $bulan = $this->input->get('bulan');
            $data['bulan'] = kopTahun($tahun, $bulan);
            $tanggalAwal = "$tahun-01-01";
            if ($bulan > 10) {
                $tanggalAkhir = date('Y-m-t', strtotime($tahun . "-" . $bulan));
            } else {
                $tanggalAkhir =
                    date('Y-m-t', strtotime($tahun . "-0" . $bulan));
            }
        } else {
            $tanggalAwal = $this->input->get('tanggalAwal');
            $tanggalAkhir = $this->input->get('tanggalAkhir');
            $data['tanggalAwal'] = $this->tukd_model->tanggal_format_indonesia($tanggalAwal);
            $data['tanggalAkhir'] = $this->tukd_model->tanggal_format_indonesia($tanggalAkhir);
        }



        //sql pendapatan blud
        $sqlPendapatanBlud = "SELECT nm_rek6, kd_rek6,kd_sub_kegiatan,nm_sub_kegiatan, SUM(nilai) AS anggaran,
         (SELECT ISNULL(SUM(kredit-debet), 0) FROM trhju_pkd AS trh INNER JOIN trdju_pkd AS trd
          ON trh.no_voucher = trd.no_voucher AND trh.kd_skpd = trd.kd_unit
           WHERE trd.kd_sub_kegiatan = rka.kd_sub_kegiatan
           AND rka.kd_rek6 = trd.kd_rek6 AND trh.tgl_voucher BETWEEN ? AND ? 
         ) AS realisasi
         FROM trdrka AS rka WHERE rka.jns_ang = ? AND rka.kd_skpd = ? AND rka.kd_rek6 IN (?)
         GROUP BY rka.nm_rek6, rka.kd_rek6,rka.kd_sub_kegiatan,rka.nm_sub_kegiatan
        ";

        // sql belanja blud
        $sqlBelanjaBlud = "SELECT rka.kd_rek6,rka.nm_rek6,rka.kd_sub_kegiatan,rka.nm_sub_kegiatan,
         SUM(rka.nilai) AS anggaran,
         (SELECT ISNULL(SUM(debet-kredit), 0) FROM trhju_pkd AS trh INNER JOIN trdju_pkd AS trd
          ON trh.no_voucher = trd.no_voucher AND trh.kd_skpd = trd.kd_unit
           WHERE trd.kd_sub_kegiatan = rka.kd_sub_kegiatan
           AND rka.kd_rek6 = trd.kd_rek6 AND trh.tgl_voucher BETWEEN ? AND ?
         ) AS realisasi
         FROM trdrka AS rka WHERE jns_ang = ? AND kd_skpd = ? AND RIGHT(kd_rek6, '4') = 9999
         GROUP BY rka.kd_sub_kegiatan, rka.nm_sub_kegiatan, rka.kd_rek6,rka.nm_rek6 ";

        $dataPendapatanBlud = $this->db->query($sqlPendapatanBlud, [
            $tanggalAwal,
            $tanggalAkhir,
            $jenisAnggaran,
            $skpd->kd_skpd,
            '410416010001'
        ])
            ->result();
        $dataBelanjaBlud = $this->db->query($sqlBelanjaBlud, [
            $tanggalAwal,
            $tanggalAkhir,
            $jenisAnggaran,
            $skpd->kd_skpd,
            '410416010001'
        ])
            ->result();



        $viewData = array_merge($data, [
            'klien' => $klien,
            'skpd' => $skpd,
            'jenis' => $jenis,
            'tahun' => $tahun,
            'pendapatanBlud' => $dataPendapatanBlud,
            'belanjaBlud' => $dataBelanjaBlud,
        ]);

        $html = $this->load->view('akuntansi/realisasi-anggaran/bludPrint', $viewData, true);

        /**
         * Kondisi sesuai tipe cetak preview, pdf, atau excel
         */
        if ($tipeCetak === 'pdf') {
            return $this->support->_mpdf_margin('', $html, 10, 10, 10, '0');
        } elseif ($tipeCetak === 'excel') {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= rincian-blud.xlsx");

            return $this->load->view('akuntansi/realisasi-anggaran/bludPrint', $viewData);
        } else {
            return $this->load->view('akuntansi/realisasi-anggaran/bludPrint', $viewData);
        }
    }

    /**
     * Method ini mengabil data status anggaran pada table tb_status_anggaran
     * Method ini bersifat private
     * @return Object
     * @author Emon Krismon
     * @link https://gtihub.com/krismonsemanas
     */
    private function statusAnggaran()
    {
        $sql = "SELECT kode, nama FROM tb_status_anggaran WHERE status_aktif = ?";
        $query = $this->db->query($sql, [true]);

        return $query->result();
    }
}
