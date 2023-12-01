<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class akuntansi_perskpd extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('custom');
        $this->load->model('perskpd');
    }
    // Hkm 17 Februari 2022

    function cetak_lra_perskpd()
    {
        $data['page_title'] = 'LRA PER SKPD';
        $this->template->set('title', 'LRA PER SKPD');
        $this->template->load('template', 'akuntansiskpd/cetak_lra_skpd', $data);
    }

    function anggaran()
    {
        $sql = "SELECT * from tb_status_anggaran where status_aktif='1' order by id ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kode' => $resulte['kode'],
                'nama' => $resulte['nama'],
            );
            $ii++;
        }
        echo json_encode($result);
    }

    function load_ttd($ttd)
    {
        // $kd_skpd = $this->session->userdata('kdskpd');
        // $kode_skpd = $this->input->post('skpd');
        $kriteria = $this->input->post('q');
        $sql = "SELECT * FROM ms_ttd WHERE kode in ('$ttd','PA') AND UPPER(nama) LIKE UPPER('%$kriteria%')";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($mas->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama'],
                'jabatan' => $resulte['jabatan']
            );
            $ii++;
        }

        echo json_encode($result);
        $mas->free_result();
    }

    function cetak_lra_baru()
    {
        $rangeData = $this->input->get('rangeData');
        $kodeSkpd = $this->input->get('skpd');
        $tipeCetak = $this->input->get('tipeCetak');
        $tahun = $this->session->userdata('pcThang');
        $anggaran = $this->input->get('jenisAnggaran');
        $tanggal_ttd = $this->input->get('tanggalTTD');
        $bulan = $this->input->get('bulan');
        $namaTTD = urldecode($this->input->get('namaTTD'));
        $tanggalAwal = '';
        $tanggalAkhir = '';


        if ($rangeData === 'bulan') {
            $kop = kopTahun($tahun, $bulan);
        } else {
            $tanggalAwal = $this->input->get('tanggalAwal');
            $tanggalAkhir = $this->input->get('tanggalAkhir');
            $kop = "PERIODE " . strtoupper($this->support->tanggal_format_indonesia($tanggalAwal)) . " S.D " . strtoupper($this->support->tanggal_format_indonesia($tanggalAkhir));
        }

        if ($kodeSkpd == '-') {
            $where = "";
        } else {
            $where = "AND kd_skpd='$kodeSkpd' ";
        }

        $initang = "nilai_ang";

        $tandaTanganSql = "SELECT nama ,nip as nip,jabatan, pangkat FROM ms_ttd where nip= ? ";
        $tandaTangan = $this->db->query($tandaTanganSql, [$namaTTD])->row();
        $nmskpd = strtoupper($this->db->query("SELECT nm_skpd FROM ms_skpd where kd_skpd=?", [$kodeSkpd])->row()->nm_skpd);
        $sclient = $this->akuntansi_model->get_sclient();

        $cRet = "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td rowspan=\"5\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
                        <tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>" . $nmskpd . "</b></td></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$kop</b></td></tr>
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
					</thead> ";

        if ($rangeData === 'bulan') {
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
        } else {
            $sql = "SELECT 
                SUM(CASE WHEN LEFT( b.kd_rek6, 1 ) = 4 THEN (kredit-debet) ELSE 0 END ) - 
                SUM(CASE WHEN LEFT( b.kd_rek6, 1 ) = 5 THEN (debet-kredit) ELSE 0 END ) AS nil_surplus,
                (
                    SELECT
                        SUM(CASE WHEN LEFT( c.kd_rek6, 1 ) = 4 THEN c.nilai ELSE 0 END ) - 
                        SUM(CASE WHEN LEFT( c.kd_rek6, 1 ) = 5 THEN c.nilai ELSE 0 END ) 
                    FROM
                        trdrka c
                    WHERE
                        c.jns_ang = '$anggaran' 
                        AND c.kd_skpd = '$kodeSkpd' 
                ) AS ang_surplus
                FROM
                    trhju_pkd a
                    JOIN trdju_pkd b ON a.kd_skpd = b.kd_unit AND a.no_voucher = b.no_voucher
                WHERE
                    a.tgl_voucher BETWEEN '$tanggalAwal' 
                    AND '$tanggalAkhir' 
                    AND a.kd_skpd = '$kodeSkpd'
            ";
        }
        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $ang_surplus = $row->ang_surplus;
            $nil_surplus = $row->nil_surplus;
        }
        // var_dump($ang_surplus);
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

        if ($rangeData === 'bulan') {
            $sql = "SELECT 
						SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
						SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
						FROM
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,2) IN ('61','62') $where
						GROUP BY LEFT(kd_ang,2)) a;
						";
        } else {
            $sql = "SELECT ISNULL( SUM ( anggaran ), 0 ) AS ang_netto, ISNULL( SUM ( realisasi ), 0 ) AS nil_netto FROM
                    ( SELECT SUM( nilai ) AS anggaran,( SELECT ISNULL( SUM ( debet - kredit ), 0 )  FROM trhju_pkd AS trh INNER JOIN trdju_pkd AS trd ON trh.no_voucher = trd.no_voucher 
                            AND trh.kd_skpd = trd.kd_unit WHERE
                            trd.kd_sub_kegiatan = a.kd_sub_kegiatan 
                            AND a.kd_rek6 = trd.kd_rek6 
                            AND trh.tgl_voucher BETWEEN '$tanggalAwal' 
                            AND '$tanggalAkhir' 
                            AND LEFT ( trd.kd_rek6 , 2 ) IN ( 61, 62 ) 
                        ) AS realisasi 
                    FROM
                        trdrka a 
                    WHERE
                        a.kd_skpd = '$kodeSkpd'
                        AND a.jns_ang = '$anggaran'
                        AND LEFT ( a.kd_rek6, 2 ) IN ( 61, 62 ) 
                    GROUP BY
                        a.kd_sub_kegiatan,
                        a.kd_rek6,
                    a.kd_skpd 
                ) AS data";
        }


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
        // var_dump([
        //     '1' => $ang_surplus,
        //     '2' => $ang_netto
        // ]);
        // die;
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
        $sql = "SELECT kode, nama, kode1, kode2, kode3,kode4,kode5,spasi, jenis FROM map_lra_sap_baru";
        $no = 0;
        $tot_peg = 0;
        $tot_brg = 0;
        $tot_mod = 0;
        $tot_bansos = 0;
        $hasil = $this->db->query($sql);
        // var_dump($this->db->last_query());
        // die;
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
            $jenis = $row->jenis;

            if ($rangeData === 'bulan') {
                $sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and (LEFT(kd_ang,1) IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) or LEFT(kd_ang,4) IN ($kode3) or LEFT(kd_ang,6) IN ($kode4) or LEFT(kd_ang,8) IN ($kode5)) $where";
            } else {
                $sql = "SELECT ISNULL(SUM ( a.nilai ),0) AS nil_ang, (
                    SELECT ISNULL(SUM($jenis),0) FROM trhju_pkd b JOIN trdju_pkd c ON 
                    b.kd_skpd = c.kd_unit AND b.no_voucher = c.no_voucher WHERE b.tgl_voucher BETWEEN '$tanggalAwal' AND '$tanggalAkhir' AND b.kd_skpd = '$kodeSkpd' AND (
                        LEFT ( c.kd_rek6, 1 ) IN ( $kode1 ) 
                        OR LEFT ( c.kd_rek6, 2 ) IN ( $kode2 ) 
                        OR LEFT ( c.kd_rek6, 4 ) IN ( $kode3) 
                        OR LEFT ( c.kd_rek6, 6 ) IN ( $kode4) 
                        OR LEFT ( c.kd_rek6, 8 ) IN ( $kode5) 
                    )
	            ) AS nilai 
                FROM
                    trdrka a 
                WHERE
                    a.kd_skpd = '$kodeSkpd' 
                    AND a.jns_ang = '$anggaran' 
                    AND (
                        LEFT ( a.kd_rek6, 1 ) IN ( $kode1 )
                        OR LEFT ( a.kd_rek6, 2 ) IN ( $kode2 )  
                        OR LEFT ( a.kd_rek6, 4 ) IN ( $kode3) 
                        OR LEFT ( a.kd_rek6, 6 ) IN ( $kode4) 
                        OR LEFT ( a.kd_rek6, 8 ) IN ( $kode5) 
                    ) ";
            }

            $hasil = $this->db->query($sql);
            foreach ($hasil->result() as $row) {
                $nil_ang = $row->nil_ang;
                $nilai = abs($row->nilai);
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

        // $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$ttd1' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
        // $sqlttd = $this->db->query($sqlttd1);
        // foreach ($sqlttd->result() as $rowttd) {
        //     $nip = $rowttd->nip;
        //     $namax = $rowttd->nm;
        //     $jabatan  = $rowttd->jab;
        //     $pangkat  = $rowttd->pangkat;
        // }


        // if ($ttd1 != '1') {
        //     $xx = "<u>";
        //     $xy = "</u>";
        //     $nipxx = $nip;
        //     $nipx = "NIP.";
        // } else {
        //     $xx = "";
        //     $xy = "";
        //     $nipxx = "";
        //     $nipx = "";
        // }
        if ($tanggal_ttd == 1) {
            $tgltd = '';
        } else {
            $tgltd = $this->support->tanggal_format_indonesia($tanggal_ttd);
        }

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"></td>
            <td align=\"center\" width=\"50%\"> " . $sclient->daerah . ", $tgltd </td>
            </tr>		
            <tr>
            <td align=\"center\" width=\"50%\"></td>
            <td align=\"center\" width=\"50%\"> $tandaTangan->jabatan </td>
            </tr>	
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"></td>
            <td align=\"center\" width=\"50%\"> $tandaTangan->nama </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"></td>
            <td align=\"center\" width=\"50%\"> NIP :$tandaTangan->nip </td>
            </tr>
            </table>
            ";

        $data['prev'] = $cRet;
        $judul = 'LRA';
        switch ($tipeCetak) {
            case 0;
                $this->support->_mpdf('', $cRet, 10, 10, 10, '1');
                break;
            case 1;
                echo ("<title>$judul</title>");
                echo $cRet;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }

    function cetak_lra_77()
    {
        $tahun = $this->session->userdata('pcThang');
        $rangeData = $this->input->get('rangeData');
        $kd_skpd  = $this->input->get('skpd');
        $ttd2 = urldecode($this->input->get('namaTTD'));
        $tanggal_ttd =    $this->input->get('tanggalTTD');
        $ctgl_ttd = $this->tukd_model->tanggal_format_indonesia($tanggal_ttd);
        $agg =    $this->input->get('jenisAnggaran');
        $pilih = $this->input->get('tipeCetak');
        $bulan = $this->input->get('bulan');

        if ($rangeData === 'bulan') {
            $kop = kopTahun($tahun, $bulan);
            $sql = "SELECT
                SUM(b.nilai_ang) as anggaran,
                SUM(b.real_spj) as nilai
                FROM 
                    Data_realisasi_keg4( ?, ?) b 
                WHERE
                    b.jns_ang= ? 
                    AND kd_skpd = ? 
            ";
            $sqlDetail = $sql;
        } else {
            $tanggalAwal = $this->input->get('tanggalAwal');
            $tanggalAkhir = $this->input->get('tanggalAkhir');
            $kop = "PERIODE " . strtoupper($this->support->tanggal_format_indonesia($tanggalAwal)) . " S.D " . strtoupper($this->support->tanggal_format_indonesia($tanggalAkhir));
            $sql = "SELECT
                    SUM(CASE WHEN LEFT(b.kd_rek6, 1) = 4 THEN (b.kredit-b.debet) ELSE (b.debet-b.kredit) END) AS nilai,
                    (
                        SELECT 
                            SUM(nilai) as anggaran
                        FROM
                            trdrka c
                        WHERE 
                        c.kd_skpd = ?
                        AND c.jns_ang = ? 
                        AND LEFT (c.kd_rek6, 1) = '?'
                    ) AS anggaran
                FROM
                	trhju_pkd a
	            JOIN 
                    trdju_pkd b ON a.kd_skpd = b.kd_unit AND a.no_voucher = b.no_voucher 
                WHERE
	                a.kd_skpd = ? 
	                AND 
                        a.tgl_voucher BETWEEN ? AND ? 
                    AND LEFT ( b.kd_rek6, 1 ) = '?' 
            ";
        }



        $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$ttd2'";
        $sqlttd2 = $this->db->query($sqlttd2);
        foreach ($sqlttd2->result() as $rowttd2) {
            //$jdl2 = 'MENGETAHUI :';
            $nip2 = $rowttd2->nip;
            $nama2 = $rowttd2->nm2;
            $jabatan2  = $rowttd2->jab;
            $pangkat2  = $rowttd2->pangkat;
        }


        $nmskpd = strtoupper($this->db->query("SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd'")->row()->nm_skpd);
        $sclient = $this->akuntansi_model->get_sclient();
        $cRet = "<TABLE style=\"border-collapse:collapse;font-size:15px; Style\" width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\">
                        <strong>" . $sclient->kab_kota . " </strong>
                        </td>
                        </tr>
                        
                        <tr>
                        <td align=\"center\" style=\"border-left:hidden;border-top:hidden\" >
                        <strong><b>" . $nmskpd . "</b></strong>
                        </td>
                        </tr>

						<tr>
                        <td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\">
                        <strong><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b></strong>
                        </td>
                        </tr>

						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><strong><b>$kop</strong></b></td></tr>
                        <tr>
             <td align=\"center\">&nbsp;</td>
        </tr>
						</TABLE>";

        $cRet .= "<table style=\"font-size:12px; border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                        <thead>                       
                           <tr>
                               <td width=\"8%\" align=\"center\" style=\"border:none;\"></td>                             
                               <td bgcolor=\"#CCCCCC\" width=\"4%\" align=\"center\"><b>NO</b></td>                            
                               <td  bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                               <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>ANGGARAN</b></td>
                               <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>REALISASI</b></td>
                               <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>LEBIH</br>(KURANG)</b></td>
                               <td  bgcolor=\"#CCCCCC\" width=\"6%\" align=\"center\" ><b>%</b></td>   
                           </tr>
                           
                        </thead>
                        <tfoot>
                           <tr>
                               <td style=\"border: none;\"></td>
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>
                               <td style=\"border-top: none;\"></td>                           
                            </tr>
                        </tfoot>
                      
                        <tr>   
                               <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"4%\" align=\"center\">&nbsp;</td>                            
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"37%\">&nbsp;</td>
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                               <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"6%\">&nbsp;</td>
                           </tr>";

        if ($rangeData === 'bulan') {
            $sql .= " AND LEFT ( b.kd_rek6, 1 ) IN ( ? )";
            $total_belanja = $this->db->query($sql, [$bulan, $tahun, $agg, $kd_skpd, 4])->row();
            $total_pendapatan   = $this->db->query($sql, [$bulan, $tahun, $agg, $kd_skpd, 5])->row();
        } else {
            /**
             * Binding parameter sebelum query, lihat tanda tanya pada variable $sql
             * @param string $kd_skpd
             * @param sting $agg, Jenis Anggaran
             * @param string $kd_rek6
             * @param string $kd_skpd
             * @param date $tanggalAwal
             * @param date $tanggaAkhir
             * @param int $kd_rek6, UNTUK DI TABLE trdju_pkd
             */
            $total_belanja = $this->db->query($sql, [$kd_skpd, $agg, 5, $kd_skpd, $tanggalAwal, $tanggalAkhir, 5])->row();
            $total_pendapatan = $this->db->query($sql, [$kd_skpd, $agg, 4, $kd_skpd, $tanggalAwal, $tanggalAkhir, 4])->row();
        }

        $surplus_anggaran = $total_pendapatan->anggaran - $total_belanja->anggaran;
        $surplus_realisasi = $total_pendapatan->nilai - $total_belanja->nilai;
        $selisih_surplus = $surplus_anggaran - $surplus_realisasi;
        // dd(['belanja' => $total_belanja, 'pendapatan' => $total_pendapatan, 'surplus_ang' => $surplus_anggaran, 'surplus_real' => $surplus_realisasi]);
        if ($surplus_realisasi == 0) {
            $persen_surplus = $this->support->rp_minus(0);
        } else {
            $persen_surplus = $this->support->rp_minus($surplus_realisasi / $selisih_surplus * 100);
        }

        $sql4 = "SELECT a.bold,a.nor,a.uraian,isnull(a.kode_1,'-') as kode_1,isnull(a.kode_2,'-') as kode_2,isnull(a.kode_3,'-') as kode_3,thn_m1 AS lalu FROM map_lra_skpd a 
				   GROUP BY a.bold,a.nor,a.uraian,isnull(a.kode_1,'-'),isnull(a.kode_2,'-'),isnull(a.kode_3,'-'),thn_m1 ORDER BY nor";

        $query4 = $this->db->query($sql4);
        $no     = 0;

        foreach ($query4->result() as $key => $row4) {

            $nama      = $row4->uraian;
            $real_lalu = number_format($row4->lalu, "2", ",", ".");
            $n         = ($row4->kode_1 === "''" || $row4->kode_1 === '-' || is_null($row4->kode_1)) ? "'0000'" : $row4->kode_1;
            $n2         = $row4->kode_2;
            $n2           = ($n2 == "-" ? "'-'" : $n2);
            $n3         = $row4->kode_3;
            $n3           = ($n3 == "-" ? "'-'" : $n3);



            if ($rangeData === 'bulan') {
                $detail = $this->db->query($sqlDetail . " AND LEFT(b.kd_rek6,4) IN ($n)", [$bulan, $tahun, $agg, $kd_skpd]);
            } else {
                // Surplus
                if ($row4->nor == 28) {
                    $sqlRealisasi = "SUM(CASE WHEN LEFT(b.kd_rek6,1) = 4 THEN (b.kredit-b.debet) ELSE 0 END ) - 
                        SUM(CASE WHEN LEFT(b.kd_rek6,1) = 5 THEN (b.debet-b.kredit) ELSE 0 END )";
                } else {
                    $sqlRealisasi = "SUM(CASE WHEN LEFT(b.kd_rek6,1) = 4 THEN (b.kredit-b.debet) ELSE 0 END ) + 
                        SUM(CASE WHEN LEFT(b.kd_rek6,1) = 5 THEN (b.debet-b.kredit) ELSE 0 END )";
                }
                $sqlDetail = "SELECT
                    ISNULL($sqlRealisasi,0) AS nilai,
                    (
                        SELECT 
                            ISNULL(
                                SUM ( CASE WHEN LEFT ( c.kd_rek6, 1 ) = 4 THEN nilai ELSE 0 END ) +
				                SUM ( CASE WHEN LEFT ( c.kd_rek6, 1 ) = 5 THEN nilai ELSE 0 END )    
                            ,0) as totalAnggaran
                        FROM
                        	trdrka c
                        WHERE
                            c.kd_skpd = ?
                            AND c.jns_ang = ?
                            AND LEFT(c.kd_rek6,4) IN ($n)
                    ) AS anggaran
                    FROM
                        trhju_pkd a
                    JOIN
                        trdju_pkd b ON a.kd_skpd = b.kd_unit AND a.no_voucher = b.no_voucher 
                    WHERE
                        a.kd_skpd =	?
                        AND 
                        a.tgl_voucher BETWEEN ? AND ?
                        AND LEFT ( b.kd_rek6, 4 ) IN ($n)
                ";
                $detail = $this->db->query($sqlDetail, [$kd_skpd, $agg, $kd_skpd, $tanggalAwal, $tanggalAkhir]);
            }
            $trh    = $detail->row();
            $nil    = $trh->nilai;
            $angnil = $trh->anggaran;

            $selisih = $this->support->rp_minus($trh->anggaran - $trh->nilai);


            if ($trh->nilai == 0 || $trh->anggaran == 0) {
                $persen = $this->support->rp_minus(0);
            } else {
                $persen = $this->support->rp_minus($trh->nilai / $trh->anggaran * 100);
            }


            $nilai    = number_format($trh->nilai, "2", ",", ".");
            $angnilai = number_format($trh->anggaran, "2", ",", ".");
            $no       = $no + 1;

            switch ($row4->bold) {
                case 0:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"37%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">&nbsp;</td>
                                 </tr>";
                    break;
                case 1:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"37%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                 </tr>";
                    break;
                case 2:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"37%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                 </tr>";
                    break;
                case 3:
                    /*SURPLUS DEFISIT*/
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"37%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">" . $this->support->rp_minus($surplus_anggaran) . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">" . $this->support->rp_minus($surplus_realisasi) . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">" . $this->support->rp_minus($selisih_surplus) . "</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen_surplus</td>
                                 </tr>";
                    break;
                case 4:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"37%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                 </tr>";
                    break;
                default:
                    $cRet    .= "<tr>
                                     <td width=\"8%\" align=\"center\" style=\"border:none;\"></td> 
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"4%\" align=\"center\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"37%\">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$selisih</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"6%\" align=\"right\">$persen</td>
                                 </tr>";
                    break;
            }
        }

        $cRet .=       " </table>";
        $data['prev'] = $cRet;
        $cRet         .= "</table>";


        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"></td>
            <td align=\"center\" width=\"50%\"> " . $sclient->daerah . ", $ctgl_ttd </td>
            </tr>		
            <tr>
            <td align=\"center\" width=\"50%\"> </td>
            <td align=\"center\" width=\"50%\"> $jabatan2 </td>
            </tr>	
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> </td>
            <td align=\"center\" width=\"50%\"> $nama2 </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> </td>
            <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
            </tr>
            </table>
            ";

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul  = ("LRA SKPD $kd_skpd / $bulan");
        $this->template->set('title', 'LRA SKPD $id / $cbulan');
        switch ($pilih) {
            case 1;
                echo ("<title>LRA SKPD $bulan</title>");
                echo $cRet;
                break;
            case 0;
                $this->support->_mpdf('', $cRet, 10, 10, 10, '1');
                break;
            case 2;
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                echo $cRet;
                break;
        }
    }

    function cetak_lra()
    {
        $input = $this->input;
        $lntahunang = $this->session->userdata('pcThang');
        $ttd1 = str_replace('a', ' ', $this->uri->segment(5));

        $kd_skpd  = $input->get('skpd');
        $bulan = $input->get('bulan');
        $ctk = $input->get('tipeCetak');
        $ttd2 = urldecode($input->get('namaTTD'));
        $tanggal_ttd =    $input->get('tanggalTTD');
        $anggaran =  $input->get('jenisAnggaran');
        $rangeData =  $input->get('rangeData');

        if ($kd_skpd == '-') {
            $where = "";
        } else {
            $where = "AND kd_skpd='$kd_skpd'";
        }
        $initang = "nilai_ang";

        if ($rangeData === 'bulan') {
            $kop = kopTahun($lntahunang, $bulan);
            $sqlSurplusDefisit = "SELECT 
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
						GROUP BY LEFT(kd_ang,1)) a;"; // hitung surplus anggaran dan realisasi
        } else {
            $tanggalAwal = $this->input->get('tanggalAwal');
            $tanggalAkhir = $this->input->get('tanggalAkhir');
            $kop = "PERIODE " . strtoupper($this->support->tanggal_format_indonesia($tanggalAwal)) . " S.D " . strtoupper($this->support->tanggal_format_indonesia($tanggalAkhir));

            $sqlSurplusDefisit = "SELECT
                ISNULL(
                    -- kalkulasi anggaran pendapatan LEFT(a.kd_rek6,1) = 4 --
                    SUM(CASE WHEN LEFT(a.kd_rek6,1) = 4 THEN a.nilai ELSE 0 END) - 
                    -- kalkulias anggaran belanja LEFT (a.kd_rek6,1) = 5 --
                    SUM(CASE WHEN LEFT(a.kd_rek6,1) = 5 THEN a.nilai ELSE 0 END) +
                    -- kalkulasi pembiayan (penerimaan - pengeluaran)
                    SUM(CASE WHEN LEFT(a.kd_rek6, 2) = 61 THEN a.nilai ELSE 0 END) - 
                    SUM(CASE WHEN LEFT(a.kd_rek6, 2) = 62 THEN a.nilai ELSE 0 END)
                , 0) as ang_surplus,
	            -- kalkulasi realisasi anggaran LEFT(a.kd_rek6,1) = 4 (kredit-debet)
                (
                    SELECT (
                        ISNULL(
                            SUM(CASE WHEN LEFT(c.kd_rek6, 1) = 4 THEN c.kredit - c.debet ELSE 0 END) -
                            SUM(CASE WHEN LEFT(c.kd_rek6, 1) = 5 THEN c.debet - c.kredit ELSE 0 END) +
                            SUM(CASE WHEN LEFT(c.kd_rek6, 2) = 61 THEN c.kredit - c.debet ELSE 0 END) -
                            SUM(CASE WHEN LEFT(c.kd_rek6, 2) = 62 THEN c.debet - c.kredit ELSE 0 END) 
                        ,0)
                    ) FROM trhju_pkd b JOIN trdju_pkd c ON c.kd_unit = b.kd_skpd AND b.no_voucher = c.no_voucher
                    WHERE 
                    b.kd_skpd = ?
                    AND b.tgl_voucher BETWEEN ? AND ?
                ) AS nil_surplus
                FROM trdrka a WHERE a.kd_skpd = ? AND a.jns_ang = ?";
        }

        $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$ttd2'";
        $sqlttd2 = $this->db->query($sqlttd2);
        foreach ($sqlttd2->result() as $rowttd2) {
            //$jdl2 = 'MENGETAHUI :';
            $nip2 = $rowttd2->nip;
            $nama2 = $rowttd2->nm2;
            $jabatan2  = $rowttd2->jab;
            $pangkat2  = $rowttd2->pangkat;
        }


        $nmskpd = strtoupper($this->db->query("SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd'")->row()->nm_skpd);
        $sclient = $this->akuntansi_model->get_sclient();
        $cRet = "<TABLE style=\"border-collapse:collapse;font-size:12px;font-family:Bookman Old Style\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"center\">
						<tr>
						<td rowspan=\"5\" align=\"center\" style=\"border-right:hidden\">
							<img src=\"" . base_url() . "/image/logoHP.png\"  width=\"50\" height=\"50\" />
							</td>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden\"><strong>" . $sclient->kab_kota . " </strong></td></tr>
                        <tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>" . $nmskpd . "</b></td></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\"><b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b></tr>
						<tr><td align=\"center\" style=\"border-left:hidden;border-top:hidden\" ><b>$kop</b></td></tr>
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
					</thead> ";

        if ($rangeData === 'bulan') {
            $hasil = $this->db->query($sqlSurplusDefisit);
        } else {
            $hasil = $this->db->query($sqlSurplusDefisit, [$kd_skpd, $tanggalAwal, $tanggalAkhir, $kd_skpd, $anggaran]);
        }
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

        if ($rangeData === 'bulan') {
            $sql = "SELECT 
						SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
						SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
						FROM
						(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,2) IN ('61','62') $where
						GROUP BY LEFT(kd_ang,2)) a;
						";


            $hasil = $this->db->query($sql);
        } else {
            $sql = "SELECT
                ISNULL(
                    -- kalkulasi pembiayan (penerimaan - pengeluaran)
                    SUM(CASE WHEN LEFT(a.kd_rek6, 2) = 61 THEN a.nilai ELSE 0 END) - 
                    SUM(CASE WHEN LEFT(a.kd_rek6, 2) = 62 THEN a.nilai ELSE 0 END)
                , 0) as ang_netto,
	            -- kalkulasi realisasi anggaran LEFT(a.kd_rek6,1) = 4 (kredit-debet)
                (
                    SELECT (
                        ISNULL(
                            SUM(CASE WHEN LEFT(c.kd_rek6, 2) = 61 THEN c.kredit - c.debet ELSE 0 END) -
                            SUM(CASE WHEN LEFT(c.kd_rek6, 2) = 62 THEN c.debet - c.kredit ELSE 0 END) 
                        ,0)
                    ) FROM trhju_pkd b JOIN trdju_pkd c ON c.kd_unit = b.kd_skpd AND b.no_voucher = c.no_voucher
                    WHERE 
                    b.kd_skpd = ?
                    AND b.tgl_voucher BETWEEN ? AND ?
                ) AS nil_netto
                FROM trdrka a WHERE a.kd_skpd = ? AND a.jns_ang = ?
            ";
            $hasil = $this->db->query($sql, [$kd_skpd, $tanggalAwal, $tanggalAkhir, $kd_skpd, $anggaran]);
        }
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

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$ttd1' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $namax = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat  = $rowttd->pangkat;
        }

        if ($tanggal_ttd == 1) {
            $tgltd = '';
        } else {
            $tgltd = $this->support->tanggal_format_indonesia($tanggal_ttd);
        }

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"></td>
            <td align=\"center\" width=\"50%\"> " . $sclient->daerah . ", $tgltd </td>
            </tr>		
            <tr>
            <td align=\"center\" width=\"50%\"></td>
            <td align=\"center\" width=\"50%\"> $jabatan2 </td>
            </tr>	
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"></td>
            <td align=\"center\" width=\"50%\"> $nama2 </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"></td>
            <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
            </tr>
            </table>
            ";

        $data['prev'] = $cRet;
        $judul = 'LRA SKPD 6 ';
        switch ($ctk) {
            case 0;
                $this->support->_mpdf('', $cRet, 10, 10, 10, '1');
                break;
            case 1;
                echo ("<title>$judul</title>");
                echo $cRet;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }

    function cetak_lra_sub_ro()
    {
        $input = $this->input;
        $lntahunang = $this->session->userdata('pcThang');
        $kd_skpd  = $input->get('skpd');
        $ttd2 = urldecode($input->get('namaTTD'));
        $tanggal_ttd =    $input->get('tanggalTTD');
        $anggaran =    $input->get('jenisAnggaran');
        $ctk = $input->get('tipeCetak');
        $rangeData = $input->get('rangeData');

        if ($kd_skpd == '-') {
            $where = "";
        } else {
            $where = "AND kd_skpd='$kd_skpd'";
        }
        $initang = "nilai_ang";

        if ($rangeData === 'bulan') {
            $bulan = $input->get('bulan');
            $kop = kopTahun($lntahunang, $bulan);
        } else {
            $tanggalAwal = $input->get('tanggalAwal');
            $tanggalAkhir = $input->get('tanggalAkhir');
            $kop = "PERIODE " . strtoupper($this->support->tanggal_format_indonesia($tanggalAwal)) . " S.D " . strtoupper($this->support->tanggal_format_indonesia($tanggalAkhir));
        }

        $sqlttd2 = "SELECT nama as nm2,nip as nip,jabatan as jab , pangkat FROM ms_ttd where nip='$ttd2'";
        $sqlttd2 = $this->db->query($sqlttd2);
        foreach ($sqlttd2->result() as $rowttd2) {
            //$jdl2 = 'MENGETAHUI :';
            $nip2 = $rowttd2->nip;
            $nama2 = $rowttd2->nm2;
            $jabatan2  = $rowttd2->jab;
            $pangkat2  = $rowttd2->pangkat;
        }

        $nmskpd = strtoupper($this->db->query("SELECT nm_skpd FROM ms_skpd where kd_skpd='$kd_skpd'")->row()->nm_skpd);

        $initang = "nilai_ang";

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
                        <td align=\"center\" style=\"border-left:hidden;border-top:hidden\" >
                            <b>$nmskpd</b>
                        </td>
                    </tr>
					<tr>
						<td align=\"center\" style=\"border-left:hidden;border-bottom:hidden;border-top:hidden\">
							<b>LAPORAN REALISASI ANGGARAN PENDAPATAN DAN BELANJA </b>
						</tr>
					<tr>
						<td align=\"center\" style=\"border-left:hidden;border-top:hidden\" >
							<b>$kop</b>
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
        // QUERY UNTUK MENGISI ANGGARAN SURPLUS/DEFISIT
        if ($rangeData === 'bulan') {
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
        } else {
            $sql = "SELECT
                    ISNULL(
                        -- hitung selisih anggaran pendapatan dan belanja (pendapatan-belanja)-- 	
                        SUM(CASE WHEN LEFT(a.kd_rek6, 1) = 4 THEN a.nilai ELSE 0 END ) -
                        SUM(CASE WHEN LEFT(a.kd_rek6, 1) = 5 THEN a.nilai ELSE 0 END ) 
                    ,0) AS ang_surplus,
                    (
                        SELECT
                            -- hitung selisih realisasi pendapatan dan belanja (pendapatan-belanja)--
                            ISNULL(
                                SUM(CASE WHEN LEFT(c.kd_rek6, 1) = 4 THEN (c.kredit-c.debet) ELSE 0 END ) -
					            SUM(CASE WHEN LEFT(c.kd_rek6, 1) = 5 THEN (c.debet-c.kredit) ELSE 0 END ) 
                            ,0)
                        FROM
                            trhju_pkd b
                        JOIN
                            trdju_pkd c 
                        ON
                            b.no_voucher = c.no_voucher AND b.kd_skpd = c.kd_unit
                        WHERE
                            b.kd_skpd = ? AND
                            b.tgl_voucher BETWEEN ? AND ?
                    ) AS nil_surplus
                FROM
                    trdrka a
                WHERE 
                   a.jns_ang = ? AND a.kd_skpd = ?
            ";
            $hasil = $this->db->query($sql, [$kd_skpd, $tanggalAwal, $tanggalAkhir, $anggaran, $kd_skpd]);
        }
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

        // QUERY UNTUK MENGISI PEMBIAYAAN NETTO
        if ($rangeData === 'bulan') {
            $sql = "SELECT 
				SUM(CASE WHEN kd_rek='61' THEN (nil_ang) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (nil_ang) ELSE 0 END) as ang_netto,
				SUM(CASE WHEN kd_rek='61' THEN (real_spj) ELSE 0 END) - SUM(CASE WHEN kd_rek='62' THEN (real_spj) ELSE 0 END) as nil_netto
				FROM
				(SELECT LEFT(kd_ang,2) as kd_rek, SUM($initang) as nil_ang, SUM(real_spj) as real_spj FROM data_realisasi_pemkot 
				where bulan='$bulan' and jns_ang='$anggaran' and LEFT(kd_ang,2) IN ('61','62') $where
				GROUP BY LEFT(kd_ang,2)) a";
            $hasil = $this->db->query($sql);
        } else {
            $sql = "SELECT
                    ISNULL(
                        -- hitung selisih anggaran pendapatan dan belanja (pendapatan-belanja)-- 	
                        SUM(CASE WHEN LEFT(a.kd_rek6, 2) = 61 THEN a.nilai ELSE 0 END ) -
                        SUM(CASE WHEN LEFT(a.kd_rek6, 2) = 62 THEN a.nilai ELSE 0 END ) 
                    ,0) AS ang_netto,
                    (
                        SELECT
                            -- hitung selisih realisasi pendapatan dan belanja (pendapatan-belanja)--
                            ISNULL(
                                SUM(CASE WHEN LEFT(c.kd_rek6, 2) = 61 THEN (c.kredit-c.debet) ELSE 0 END ) -
					            SUM(CASE WHEN LEFT(c.kd_rek6, 2) = 62 THEN (c.debet-c.kredit) ELSE 0 END ) 
                            ,0)
                        FROM
                            trhju_pkd b
                        JOIN
                            trdju_pkd c 
                        ON
                            b.no_voucher = c.no_voucher AND b.kd_skpd = c.kd_unit
                        WHERE
                            b.kd_skpd = ? AND
                            b.tgl_voucher BETWEEN ? AND ?
                    ) AS nil_netto
                FROM
                    trdrka a
                WHERE 
                   a.jns_ang = ? AND a.kd_skpd = ?
            ";
            $hasil = $this->db->query($sql, [$kd_skpd, $tanggalAwal, $tanggalAkhir, $anggaran, $kd_skpd]);
        }


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
            // if ($urut == 57){
            //     $sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM 
            //     data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and (LEFT(kd_ang,1) 
            //     IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) 
            //     or LEFT(kd_ang,4) IN ($kode3) 
            //     or LEFT(kd_ang,6) IN ($kode4) 
            //     or LEFT(kd_ang,8) IN ($kode5)) $where zz";
            // }else{
            if ($rangeData === 'bulan') {
                $sql = "SELECT SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM 
                data_realisasi_pemkot where bulan='$bulan' and jns_ang='$anggaran' and (LEFT(kd_ang,1) 
                IN ($kode1) or LEFT(kd_ang,2) IN ($kode2) 
                or LEFT(kd_ang,4) IN ($kode3) 
                or LEFT(kd_ang,6) IN ($kode4) 
                or LEFT(kd_ang,8) IN ($kode5)) $where";
            } else {
                $sql = "SELECT ISNULL(SUM ( a.nilai ),0) AS nil_ang, (
                    SELECT ISNULL(SUM(CASE WHEN LEFT(c.kd_rek6, 1) = 4 OR LEFT(c.kd_rek6,2) = 61 THEN (c.kredit-c.debet) ELSE (c.debet-c.kredit) END ),0) FROM trhju_pkd b JOIN trdju_pkd c ON 
                    b.kd_skpd = c.kd_unit AND b.no_voucher = c.no_voucher WHERE b.tgl_voucher BETWEEN '$tanggalAwal' AND '$tanggalAkhir' AND b.kd_skpd = '$kd_skpd' AND 
                    (
                        LEFT ( c.kd_rek6, 1 ) IN ( $kode1 ) 
                        OR LEFT ( c.kd_rek6, 2 ) IN ( $kode2 ) 
                        OR LEFT ( c.kd_rek6, 4 ) IN ( $kode3) 
                        OR LEFT ( c.kd_rek6, 6 ) IN ( $kode4) 
                        OR LEFT ( c.kd_rek6, 8 ) IN ( $kode5) 
                    )
	            ) AS nilai 
                FROM
                    trdrka a 
                WHERE
                    a.kd_skpd = '$kd_skpd' 
                    AND a.jns_ang = '$anggaran' 
                    AND (
                        LEFT ( a.kd_rek6, 1 ) IN ( $kode1 )
                        OR LEFT ( a.kd_rek6, 2 ) IN ( $kode2 )  
                        OR LEFT ( a.kd_rek6, 4 ) IN ( $kode3) 
                        OR LEFT ( a.kd_rek6, 6 ) IN ( $kode4) 
                        OR LEFT ( a.kd_rek6, 8 ) IN ( $kode5) 
                    )";
            }
            // } 

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
                        if ($rangeData === 'bulan') {
                            $sql = "SELECT '8' as spasi, b.kd_rek4 as kd_rek,b.nm_rek4 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
								join ms_rek4 b on left(a.kd_rek6,6)=b.kd_rek4 where a.bulan='$bulan' and a.jns_ang='$anggaran' and
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) $where
								group by b.kd_rek4,b.nm_rek4
								union all
								SELECT '9' as spasi,b.kd_rek5 as kd_rek,b.nm_rek5 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
								join ms_rek5 b on left(a.kd_rek6,8)=b.kd_rek5 where a.bulan='$bulan' and a.jns_ang='$anggaran' and
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) $where
								group by b.kd_rek5,b.nm_rek5
								union all
								SELECT '10' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
								where a.bulan='$bulan' and a.jns_ang='$anggaran' and 
								(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) $where
								group by a.kd_rek6,a.nm_rek6 order by kd_rek";
                        } else {
                            $periode = $this->perskpd
                                ->subRo(4, 8, $kd_skpd, $tanggalAwal, $tanggalAkhir, $anggaran, $kode3, $kode4, $kode5)
                                ->union()
                                ->subRo(5, 9, $kd_skpd, $tanggalAwal, $tanggalAkhir, $anggaran, $kode3, $kode4, $kode5)
                                ->union()
                                ->subRo(6, 10, $kd_skpd, $tanggalAwal, $tanggalAkhir, $anggaran, $kode3, $kode4, $kode5)
                                ->get();
                        }
                    } else {
                        if ($is_kode4 != 'X') {
                            if ($rangeData === 'bulan') {
                                $sql = "SELECT '8' as spasi,b.kd_rek5 as kd_rek,b.nm_rek5 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
									join ms_rek5 b on left(a.kd_rek6,8)=b.kd_rek5 where a.bulan='$bulan' and a.jns_ang='$anggaran' and
									(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) $where
									group by b.kd_rek5,b.nm_rek5
									union all
									SELECT '9' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
									where a.bulan='$bulan' and a.jns_ang='$anggaran' and
									(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) $where
									group by a.kd_rek6,a.nm_rek6 order by kd_rek";
                            } else {
                                $periode = $this->perskpd
                                    ->subRo(5, 8, $kd_skpd, $tanggalAwal, $tanggalAkhir, $anggaran, $kode3, $kode4, $kode5)
                                    ->union()
                                    ->subRo(6, 9, $kd_skpd, $tanggalAwal, $tanggalAkhir, $anggaran, $kode3, $kode4, $kode5)
                                    ->get();
                            }
                        } else {
                            if ($is_kode5 != 'X') {
                                if ($rangeData === 'bulan') {
                                    $sql = "SELECT '8' as spasi,a.kd_rek6 as kd_rek,a.nm_rek6 as nm_rek,SUM($initang) as nil_ang, SUM(real_spj) as nilai FROM data_realisasi_pemkot a 
										where a.bulan='$bulan' and a.jns_ang='$anggaran' and
										(LEFT(a.kd_ang,4) IN ($kode3) or LEFT(a.kd_ang,6) IN ($kode4) or LEFT(a.kd_ang,8) IN ($kode5)) $where
										group by a.kd_rek6,a.nm_rek6 order by kd_rek";
                                } else {
                                    $periode = $this->perskpd
                                        ->subRo(6, 8, $kd_skpd, $tanggalAwal, $tanggalAkhir, $anggaran, $kode3, $kode4, $kode5)
                                        ->get();
                                }
                                // dd(['sql' => $periode]);
                            }
                        }
                    }
                    if ($sql) {
                        if ($rangeData === 'bulan') {
                            $result = $this->db->query($sql);
                        } else {
                            $result = $periode;
                        }
                        foreach ($result->result() as $row1) {
                            if ($row1) {
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
												<td align="left" valign="top">' . $this->support->dotrek($row1->kd_rek) . '</b></td> 
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
													<td align="left" valign="top">' . $this->support->dotrek($row1->kd_rek) . '</b></td> 
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
														<td align="left" valign="top">' . $this->support->dotrek($row1->kd_rek) . '</b></td> 
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

        if ($tanggal_ttd == 1) {
            $tgltd = '';
        } else {
            $tgltd = $this->support->tanggal_format_indonesia($tanggal_ttd);
        }

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"></td>
            <td align=\"center\" width=\"50%\"> " . $daerah . ", $tgltd </td>
            </tr>		
            <tr>
            <td align=\"center\" width=\"50%\"></td>
            <td align=\"center\" width=\"50%\"> $jabatan2 </td>
            </tr>	
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            <td align=\"center\" width=\"50%\"> &nbsp; </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"></td>
            <td align=\"center\" width=\"50%\"> $nama2 </td>
            </tr>
            <tr>
            <td align=\"center\" width=\"50%\"></td>
            <td align=\"center\" width=\"50%\"> NIP :$nip2 </td>
            </tr>
            </table>
            ";


        $data['prev'] = $cRet;
        $judul = 'LRA PERMEN 90 SUB RO';
        switch ($ctk) {

            case 1;
                echo ("<title>LRA SKPD $rangeData === 'bulan' ? 'bulan' : 'periode' </title>");
                echo $cRet;
                break;
            case 0;
                // $pdf = new Pdf(array(
                //     'binary' => $this->config->item('wkhtmltopdf_path'),
                //     'orientation' => 'Portrait',
                //     'title' => $judul,
                //     'footer-center' => 'Halaman [page] / [topage]',
                //     'footer-left' => 'Printed on @ [date] [time]',
                //     'footer-font-size' => 6,
                // ));
                //$pdf->addPage($cRet);
                //$pdf->send();
                $this->support->_mpdf('', $cRet, 10, 10, 10, '1');
                break;
            case 2;
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                echo $cRet;
                break;
            case 3;
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                echo $cRet;
                break;
        }
    }
    //END
}
