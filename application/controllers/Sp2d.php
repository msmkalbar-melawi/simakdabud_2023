<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Sp2d extends CI_Controller
{
    public $org_keu = "";
    public $skpd_keu = "";

    // public $ppkd1 = "4.02.02.01";
    // public $ppkd2 = "4.02.02.02";

    function __construct()
    {
        parent::__construct();
        $this->load->model('ClientModel');
    }
    function right($value, $count)
    {
        return substr($value, ($count * -1));
    }

    function left($string, $count)
    {
        return substr($string, 0, $count);
    }

    function config_tahun()
    {
        $result = array();
        $tahun  = $this->session->userdata('pcThang');
        $result = $tahun;
        // echo ($result);
        echo json_encode($result);
    }

    function load_jenis_beban($jenis = '')
    {
        if ($jenis == 3) {
            $result = array((array(
                    "id"   => 1,
                    "text" => " TU",
                    "selected" => true
                )
                )
            );
        } else if ($jenis == 4) {
            $result = array((array(
                    "id"   => 1,
                    "text" => " Gaji & Tunjangan"
                )
                ),
                (array(
                    "id"   => 2,
                    "text" => " Kespeg"
                )
                ),
                (array(
                    "id"   => 3,
                    "text" => " Uang Makan"
                )
                ),
                (array(
                    "id"   => 4,
                    "text" => " Upah Pungut"
                )
                ),
                (array(
                    "id"   => 5,
                    "text" => " Upah Pungut PBB"
                )
                ),
                (array(
                    "id"   => 6,
                    "text" => " Upah Pungut PBB-KB PKB & BBN-KB"
                )
                ),
                (array(
                    "id"   => 7,
                    "text" => " Tambahan/Kekurangan Gaji & Tunjangan"
                )
                ),
                (array(
                    "id"   => 8,
                    "text" => " Tunjangan Transport"
                )
                ),
                (array(
                    "id"   => 9,
                    "text" => " Tunjangan Lainnya"
                )
                )
            );
        } else if ($jenis == 5) {
            $result = array((array(
                    "id"   => 1,
                    "text" => "Hibah berupa uang"
                )
                ),
                (array(
                    "id"   => 2,
                    "text" => " Bantuan Sosial berupa uang"
                )
                ),
                (array(
                    "id"   => 3,
                    "text" => " Bantuan Keuangan"
                )
                ),
                (array(
                    "id"   => 4,
                    "text" => " Subsidi"
                )
                ),
                (array(
                    "id"   => 5,
                    "text" => " Bagi Hasil"
                )
                ),
                (array(
                    "id"   => 6,
                    "text" => " Belanja Tidak Terduga"
                )
                ),
                (array(
                    "id"   => 7,
                    "text" => " Pembayaran kewajiban pemda atas putusan pengadilan, dan
rekomendasi APIP dan/atau rekomendasi BPK"
                )
                ),
                (array(
                    "id"   => 8,
                    "text" => " Pengeluaran Pembiayaan"
                )
                ),
                (array(
                    "id" => 9,
                    "text" => "Barang yang diserahkan ke masyarakat"
                )
                )

            );
        } else if ($jenis == 6) {
            $result = array((array(
                    "id"   => 1,
                    "text" => " LS Rutin (PNS)"
                )
                ),
                (array(
                    "id"   => 2,
                    "text" => " LS Rutin (Non PNS)"
                )
                ),
                (array(
                    "id"   => 3,
                    "text" => " LS Pihak Ketiga"
                )
                )

            );
        }
        echo json_encode($result);
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

    function  tanggal_format_indonesia($tgl)
    {
        $tanggal  = explode('-', $tgl);
        $bulan  = $this->getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2] . ' ' . $bulan . ' ' . $tahun;
    }

    function _mpdf_daftar_penguji($judul = '', $isi = '', $lMargin = 10, $rMargin = 10, $font = '', $orientasi = '', $hal = '', $fonsize = '', $atas = '', $bawah = '', $kiri = '', $kanan = '')
    {

        ini_set("memory_limit", "-1");
        ini_set("MAX_EXECUTION_TIME", "-1");
        $this->load->library('mpdf');

        $header = array(
            'odd' => array(
                'L' => array(
                    'content' => '  ',
                    'font-size' => 11,
                    'font-style' => '',
                    'font-family' => 'arial',
                    'color' => '#000000'
                ),
                'C' => array(
                    'content' => ' ',
                    'font-size' => 15,
                    'font-style' => 'B',
                    'font-family' => 'arial',
                    'color' => '#000000'
                ),
                'R' => array(
                    'content' => '<br>Lembaran ke {PAGENO} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br> Terdiri dari {nb} lembar ',
                    'font-size' => 10,
                    'font-style' => '',
                    'font-family' => 'arial',
                    'color' => '#000000'
                ),
                'line' => 0,
            ),
            'even' => array()
        );

        $this->mpdf->SetLeftMargin = $lMargin;
        $this->mpdf->SetRightMargin = $rMargin;
        $jam = date("H:i:s");

        $this->mpdf = new mPDF('utf-8', array(215, 330), $size); //folio
        //$this->mpdf->SetHeader($header,'');

        $this->mpdf->AddPage($orientasi, '', 1, 1, '', $kiri, $kanan, $atas, $bawah);
        //$this->mpdf->SetHeader(" || Halaman {PAGENO} ");
        //$this->mpdf->SetHeader($header,'');
        //$this->mpdf->SetFooter("Printed on Simakda || Halaman {PAGENO} ");
        //$PageCount =  $this->mpdf->getPageCount();        
        if (!empty($judul)) $this->mpdf->writeHTML($judul);

        //$isi = str_replace('$page', $this->mpdf->getPageCount(), $isi);

        $this->mpdf->writeHTML($isi);
        //$this->mpdf->WriteHTML("ewe".$PageCount."2323");      
        $this->mpdf->Output();
    }

    function _mpdf_sp2d2($judul = '', $isi = '', $lMargin = '', $rMargin = '', $font = 0, $orientasi = '')
    {

        ini_set("memory_limit", "-1");
        ini_set("MAX_EXECUTION_TIME", "-1");
        $this->load->library('mpdf');


        $this->mpdf->defaultheaderfontsize = 6; /* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;   /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 6; /* in pts */
        $this->mpdf->defaultfooterfontstyle = BI;   /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 1;
        $this->mpdf->SetLeftMargin = $lMargin;
        $this->mpdf->SetRightMargin = $rMargin;
        $jam = date("H:i:s");
        $this->mpdf = new mPDF('utf-8', array(215.9, 330.2), $size); //folio
        $this->mpdf->AddPage($orientasi, '', '', 1, 1, $lMargin, $rMargin, 15, 5);
        //$this->mpdf->SetFooter("Printed on Simakda || Halaman {PAGENO} ");
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        $this->mpdf->writeHTML($isi);
        $this->mpdf->Output();
    }

    function pilih_ttd_bud()
    {

        $sql = "SELECT nip,nama,jabatan FROM ms_ttd where kode IN ('BUD','PA') AND kd_skpd='5.02.0.00.0.00.02.0000' group by  nip,nama,jabatan ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'nip' => $resulte['nip'],
                'nama' => $resulte['nama'],
                'jabatan' => $resulte['jabatan']

            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function daftar_penguji()
    {
        $data['page_title'] = 'INPUT DAFTAR PENGUJI';
        $this->template->set('title', 'INPUT DAFTAR PENGUJI');
        $this->template->load('template', 'tukd/sp2d/daftar_penguji', $data);
    }

    function sp2d_list_uji()
    {
        $lccr = $this->input->post('q');
        // $sql   = " select no_sp2d, tgl_sp2d,no_spm,tgl_spm,nilai from trhsp2d where no_sp2d not in 
        //             (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji) and upper(no_sp2d) like upper('%$lccr%') and (sp2d_batal is null or sp2d_batal='') ";
        $sql   = " SELECT
        no_sp2d,
        tgl_sp2d,
        no_spm,
        tgl_spm,
        nilai 
    FROM
        trhsp2d 
    WHERE
        no_sp2d NOT IN ( SELECT no_sp2d FROM trhuji a INNER JOIN trduji b ON a.no_uji= b.no_uji ) 
        AND UPPER ( no_sp2d ) LIKE UPPER ( '%$lccr%' ) 
        AND (
        sp2d_batal IS NULL 
        OR sp2d_batal = '') GROUP BY no_sp2d, tgl_sp2d,no_spm, tgl_spm,nilai 
        order by no_sp2d";
        // $sql   = " select no_spd, tgl_spd from trhspd ";        
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'tgl_sp2d' => $resulte['tgl_sp2d'],
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'nilai' => $resulte['nilai']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_d_uji()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $skriteria = $this->input->post('vkriteria');
        $where    = " ";

        if ($skriteria != '') {
            if ($kriteria <> '') {
                $where = "  and (upper(a.no_uji) like upper('%$kriteria%') or a.tgl_uji like '%$kriteria%' or b.no_sp2d like '%$kriteria%') ";
            }


            $sql = "SELECT count(*) as tot from trhuji";
            $query1 = $this->db->query($sql);
            $total = $query1->row();

            /*
            $sql = "SELECT top $rows * from trhuji  a where a.no_uji not in (SELECT TOP $offset  no_uji from  
                    trhuji order by tgl_uji,no_uji ) $where order by a.tgl_uji, a.no_uji ";//limit $offset,$rows";
            */
            $sql = "SELECT top $rows a.no_uji, a.tgl_uji from trhuji a
                    INNER JOIN TRDUJI b ON a.no_uji=b.no_uji 
                    where a.no_uji not in (SELECT TOP $offset  no_uji from  
                    trhuji order by tgl_uji,no_uji ) $where
                    GROUP BY a.no_uji, a.tgl_uji order by a.tgl_uji, a.no_uji "; //limit $offset,$rows";
        } else {
            if ($kriteria <> '') {
                $where = "  and (upper(a.no_uji) like upper('%$kriteria%') or a.tgl_uji like '%$kriteria%') ";
            }


            $sql = "SELECT count(*) as tot from trhuji";
            $query1 = $this->db->query($sql);
            $total = $query1->row();

            /*
            $sql = "SELECT top $rows * from trhuji  a where a.no_uji not in (SELECT TOP $offset  no_uji from  
                    trhuji order by tgl_uji,no_uji ) $where order by a.tgl_uji, a.no_uji ";//limit $offset,$rows";
            */
            $sql = "SELECT top $rows a.no_uji, a.tgl_uji from trhuji a 
                    where a.no_uji not in (SELECT TOP $offset  no_uji from  
                    trhuji order by tgl_uji,no_uji ) $where
                    GROUP BY a.no_uji, a.tgl_uji order by a.tgl_uji, a.no_uji "; //limit $offset,$rows";            
        }
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $row[] = array(
                'id' => $ii,
                'no_uji'    => $resulte['no_uji'],
                'tgl_uji'    => $resulte['tgl_uji']
            );
            $ii++;
        }
        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function select_detail_uji($vno_uji = '')
    {

        $luji = $this->input->post('vno_uji');


        $sql = "SELECT no_uji, tgl_uji, a.no_sp2d,b.tgl_sp2d,no_spm,tgl_spm,nilai 
                FROM TRDUJI a inner join trhsp2d b on a.no_sp2d=b.no_sp2d WHERE no_uji='$luji'";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'idx'        => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'tgl_sp2d'     => $resulte['tgl_sp2d'],
                'no_spm'     => $resulte['no_spm'],
                'tgl_spm'     => $resulte['tgl_spm'],
                'nilai1'      => number_format($resulte['nilai'])
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function hhapusuji()
    {
        $nomor = $this->input->post('no');
        $query = $this->db->query("delete from TRHUJI where no_uji='$nomor'");
        $query = $this->db->query("delete from TRDUJI where no_uji='$nomor'");
        $query->free_result();
    }

    function cek_uji($vno_uji = '')
    {

        $luji = $this->input->post('vno_uji');


        $sql = "SELECT top 1 isnull(b.no_kas_bud,'') [no_kas_bud] FROM TRDUJI a inner join trhsp2d b on a.no_sp2d=b.no_sp2d WHERE no_uji='$luji' 
                order by b.no_kas_bud desc ";

        $query1 = $this->db->query($sql);
        $result = '';
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result = $resulte['no_kas_bud'];
        }

        echo json_encode($result);
        $query1->free_result();
    }



    function load_data_sp2d_uji($dtgl1 = '', $dtgl2 = '')
    {

        $dtgl1  = $this->input->post('tgl1');
        $dtgl2  = $this->input->post('tgl2');

        $sql    = " SELECT no_sp2d, tgl_sp2d,no_spm,tgl_spm,SUM(b.nilai) as nilai from trhsp2d a
                    INNER JOIN trdspp b ON a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd
                    WHERE tgl_sp2d BETWEEN '$dtgl1' AND '$dtgl2' AND 
                    no_sp2d not in (select no_sp2d from trhuji a inner join trduji b on a.no_uji=b.no_uji) 
                    GROUP BY no_sp2d, tgl_sp2d,no_spm,tgl_spm
                    ORDER BY tgl_sp2d,no_sp2d";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                //'tgl_sp2d' => $this->tanggal_indonesia($resulte['tgl_sp2d']),
                'tgl_sp2d' => $resulte['tgl_sp2d'],
                'no_spm' => $resulte['no_spm'],
                //'tgl_spm' => $this->tanggal_indonesia($resulte['tgl_spm']),
                'tgl_spm' => $resulte['tgl_spm'],
                'nilai' => number_format($resulte['nilai'], "2", ".", ",")
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function simpan_daftar_uji()
    {
        $tabel    = $this->input->post('tabel');
        $no_uji = $this->input->post('no_uji');
        $tgl_uji = $this->input->post('tgl_uji');
        $no_blk = $this->input->post('no_blk');
        $cwaktu = date("Y-m-d H:i:s");
        $user =  $this->session->userdata('pcNama');
        $lcst = $this->input->post('lcst');
        $r_nomor = '2';

        if ($tabel == 'trhuji') {
            $sql = "Select isnull(Max((no_urut)),0) As maks From trhuji";
            $hasil = $this->db->query($sql);
            $nomor7 = $hasil->row();
            $nomor7_urut = $nomor7->maks + 1;
            $r_nomor = strval($nomor7_urut) . $no_blk;

            $db_debug = $this->db->db_debug;
            $this->db->db_debug = FALSE;
            $csql = "INSERT INTO trhuji (no_uji,tgl_uji,username,tgl_update,no_urut) values ('$r_nomor','$tgl_uji','$user','$cwaktu','$nomor7_urut')";
            $query1 = $this->db->query($csql);
            $this->db->db_debug = $db_debug;
            if ($query1) {
                echo json_encode($r_nomor);
            } else {
                echo '0';
            }
        } else if ($tabel == 'trduji') {
            $nomor_baru = $this->input->post('nomor_baru');
            $csql     = $this->input->post('sql');
            // Simpan Detail //                       
            $sql = "delete from TRDUJI where no_uji='$nomor_baru'";
            $asg = $this->db->query($sql);


            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $db_debug = $this->db->db_debug;
                $this->db->db_debug = FALSE;
                $sql = "insert into TRDUJI(no_uji,tgl_uji,no_sp2d)";
                $asg = $this->db->query($sql . $csql);
                //var_dump($asg);
                $this->db->db_debug = $db_debug;

                if (!($asg)) {
                    //$msg = array('pesan'=>'0');
                    //echo json_encode($msg);
                    //   exit();
                    echo json_encode('0');
                } else {
                    // $msg = array('pesan'=>'1');
                    // echo json_encode($msg);
                    echo json_encode('1');
                }
            }
        }
    }

    function edit_daftar_uji()
    {
        $tabel    = $this->input->post('tabel');
        $no_uji = $this->input->post('no_uji');
        $no_uji_hide = $this->input->post('no_uji_hide');
        $tgl_uji = $this->input->post('tgl_uji');
        $cwaktu = date("Y-m-d H:i:s");
        $user =  $this->session->userdata('pcNama');

        if ($tabel == 'trhuji') {
            $csql = "update trhuji set tgl_uji='$tgl_uji',username='$user',tgl_update='$cwaktu' where no_uji='$no_uji_hide'";
            $query1 = $this->db->query($csql);
            if ($query1) {
                echo json_encode($no_uji);
            } else {
                echo '0';
            }
        } else if ($tabel == 'trduji') {
            $csql     = $this->input->post('sql');
            // Simpan Detail //                       
            $sql = "delete from TRDUJI where no_uji='$no_uji_hide'";
            $asg = $this->db->query($sql);


            if (!($asg)) {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            } else {
                $sql = "insert into TRDUJI(no_uji,tgl_uji,no_sp2d)";
                $asg = $this->db->query($sql . $csql);


                if (!($asg)) {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    //   exit();
                } else {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            }
        }
    }

    function simpan_detail_d_uji()
    {

        $no_uji = $this->input->post('no_uji');
        $tgl_uji = $this->input->post('tgl_uji');
        $no_sp2d = $this->input->post('no_sp2d');

        $csql = "INSERT INTO trduji (no_uji,tgl_uji,no_sp2d) values ('$no_uji','$tgl_uji','$no_sp2d')";
        $query1 = $this->db->query($csql);

        if ($query1) {
            echo '2';
        } else {
            echo '0';
        }
    }

    function simpan_detail_d_uji_hapus()
    {
        $no_uji  = trim($this->input->post('no_uji'));

        $sql     = " delete trduji where no_uji='$no_uji' ";
        $asg     = $this->db->query($sql);
        if ($asg > 0) {
            echo '1';
            exit();
        } else {
            echo '0';
            exit();
        }
    }

    function cetak_daftar_penguji($no_uji = '', $ttd = '', $dcetak = '', $cetak = '', $atas = '', $bawah = '', $kiri = '', $kanan = '')
    {
        $print = $cetak;

        $no_uji = str_replace('123456789', '/', $this->uri->segment(3));
        $lcttd = str_replace('abcdefg', ' ', $this->uri->segment(4));

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$lcttd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat = $rowttd->pangkat;
        }
        $sqlcount = "SELECT COUNT(a.no_sp2d) as jumlah FROM trduji a INNER JOIN trhuji b ON a.no_uji=b.no_uji WHERE a.no_uji='$no_uji'";
        $sql123 = $this->db->query($sqlcount);
        foreach ($sql123->result() as $rowcount) {
            $jumlah = $rowcount->jumlah;
        }
        $PageCount = '$page';
        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;font-weight:bold;font-family:Tahoma; font-size:12px\" border=\"0\" width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">
            <tr >
                <td width=\"100%\" align=\"center\" colspan=4 style=\"font-size:18px\">DAFTAR PENGUJI / PENGANTAR<br>SURAT PERINTAH PENCAIRAN DANA</td></tr>
                     <TR >
                        <TD align=\"left\" width=\"10%\">Tanggal </TD>
                        <TD align=\"left\" width=\"70%\">: " . $this->tukd_model->tanggal_format_indonesia($dcetak) . "</TD>
                        <TD align=\"left\" width=\"10%\"></TD>
                        <TD align=\"right\"  width=\"20%\">Lembaran ke 1</TD>
                     </TR>                   
                     <TR>
                        <TD align=\"left\"> Nomor</TD>
                        <TD align=\"left\"  >: " . $no_uji . "</TD>
                        <TD align=\"left\" > </TD>
                        <TD align=\"right\" >Terdiri dari " . $jumlah . " lembar </TD>
                     </TR>
                     </TABLE>";

        $cRet .= " <table style=\"border-collapse:collapse;font-family:Tahoma; font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\">               
                <thead>
               <tr style=\"font-size:12px;font-weight:bold;\">
                    <td width=\"5%\" align=\"center\"><b>NO</b></td>
                    <td width=\"10%\" align=\"center\" ><b>TANGGAL DAN<br>NOMOR SP2D</b></td>
                     <td  width=\"28%\" align=\"center\"><b>ATAS NAMA<br>( YANG BERHAK )</b>
                     </td>
                     <td width=\"20%\" align=\"center\" ><b>OPD</b>        
                    </td>
                    <td  width=\"10%\" align=\"center\"><b>JUMLAH KOTOR<br>(Rp)</b>
                     </td>                   
                    <td width=\"10%\" align=\"center\" ><b>JUMLAH<br>POTONGAN</b>
                    </td>
                    <td width=\"10%\" align=\"center\"><b>JUMLAH<br>BERSIH</b>
                    </td>
                    <td  width=\"10%\" align=\"center\"><b>TANGGAL<br>TRANSFER</b>
                    </td>
                   
                </tr>
                <tr style=\"font-size:11px;font-weight:bold;\"> 
                    <td align=\"center\" >1
                    </td>
                    <td align=\"center\" >2
                    </td>
                    <td align=\"center\" >3
                    </td>
                    <td align=\"center\" >4
                    </td>
                    <td align=\"center\" >5
                    </td>
                    <td align=\"center\" >6
                    </td>
                    <td align=\"center\" >7
                    </td>
                    <td align=\"center\" >8
                    </td>
                </tr>
                </thead>
                ";

        // SELECT b.no_sp2d,c.tgl_sp2d,c.nmrekan,c.pimpinan,c.alamat,c.kd_skpd,c.nm_skpd
        // ,c.jns_spp,c.jenis_beban,c.kotor,c.pot FROM TRHUJI a inner join TRDUJI b on a.no_uji=b.no_uji LEFT join (
        // SELECT a.*,ISNULL(SUM(b.nilai),0)pot FROM (select no_sp2d,no_spm,tgl_sp2d,b.nmrekan,b.alamat,b.pimpinan,
        // a.kd_skpd,d.nm_skpd,a.jns_spp,a.jenis_beban, isnull(SUM(z.nilai),0)kotor
        // from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp AND a.kd_skpd=b.kd_skpd
        // INNER JOIN trdspp z ON b.no_spp=z.no_spp AND b.kd_skpd=z.kd_skpd
        // INNER JOIN ms_skpd d on a.kd_skpd=d.kd_skpd
        // GROUP BY no_sp2d,no_spm,tgl_sp2d,b.nmrekan,b.alamat,b.pimpinan,
        // a.kd_skpd,d.nm_skpd,a.jns_spp,a.jenis_beban)a 
        // LEFT JOIN 
        // trspmpot b ON a.no_spm=b.no_spm and a.kd_skpd=b.kd_skpd
        // GROUP BY no_sp2d,a.no_spm,tgl_sp2d,a.nmrekan,a.alamat,a.pimpinan,
        // a.kd_skpd,a.nm_skpd,a.jns_spp,a.jenis_beban,a.kotor) c on b.no_sp2d=c.no_sp2d WHERE a.no_uji='$no_uji'

        $sql = "SELECT
        b.no_sp2d,
        c.tgl_sp2d,
        c.nmrekan,
        c.pimpinan,
        c.alamat,
        c.kd_skpd,
        c.nm_skpd ,
        c.jns_spp,
        c.jenis_beban,
        c.kotor,
        c.pot 
    FROM
        TRHUJI a
        INNER JOIN TRDUJI b ON a.no_uji= b.no_uji
        LEFT JOIN (
        SELECT
            a.*,
            ISNULL( SUM ( b.nilai ), 0 ) pot 
        FROM
            (
            SELECT
                no_sp2d,
                no_spm,
                tgl_sp2d,
                a.nmrekan,
                b.alamat,
                b.pimpinan,
                a.kd_skpd,
                d.nm_skpd,
                a.jns_spp,
                a.jenis_beban,
                isnull( SUM ( z.nilai ), 0 ) kotor 
            FROM
                trhsp2d a
                INNER JOIN trhspp b ON a.no_spp= b.no_spp 
                AND a.kd_skpd= b.kd_skpd
                INNER JOIN trdspp z ON b.no_spp= z.no_spp 
                AND b.kd_skpd= z.kd_skpd
                INNER JOIN ms_skpd d ON a.kd_skpd= d.kd_skpd 
            GROUP BY
                no_sp2d,
                no_spm,
                tgl_sp2d,
                a.nmrekan,
                b.alamat,
                b.pimpinan,
                a.kd_skpd,
                d.nm_skpd,
                a.jns_spp,
                a.jenis_beban 
            ) a
            LEFT JOIN trspmpot b ON a.no_spm= b.no_spm 
            AND a.kd_skpd= b.kd_skpd 
        GROUP BY
            no_sp2d,
            a.no_spm,
            tgl_sp2d,
            a.nmrekan,
            a.alamat,
            a.pimpinan,
            a.kd_skpd,
            a.nm_skpd,
            a.jns_spp,
            a.jenis_beban,
            a.kotor 
        ) c ON b.no_sp2d= c.no_sp2d 
    WHERE
        a.no_uji= '$no_uji'";
        $hasil = $this->db->query($sql);
        $lcno = 0;
        $total_kotor = 0;
        $total_pot = 0;

        foreach ($hasil->result() as $row) {
            $lcno = $lcno + 1;
            $no_sp2d = empty($row->no_sp2d) || $row->no_sp2d == '' ? ' ' : $row->no_sp2d;
            //$tgl_sp2d=$row->tgl_sp2d;
            $nmrekan = empty($row->nmrekan) || $row->nmrekan == '' ? ' ' : $row->nmrekan;
            $pimpinan = empty($row->pimpinan) || $row->pimpinan == '' ? ' ' : $row->pimpinan;
            $alamat = empty($row->alamat) || $row->alamat == '' ? ' ' : $row->alamat;
            $kd_skpd = empty($row->kd_skpd) || $row->kd_skpd == '' ? ' ' : $row->kd_skpd;
            $nm_skpd = empty($row->nm_skpd) || $row->nm_skpd == '' ? ' ' : $row->nm_skpd;
            $jns = empty($row->jns_spp) || $row->jns_spp == '' ? ' ' : $row->jns_spp;
            $jns_bbn = empty($row->jenis_beban) || $row->jenis_beban == '' ? ' ' : $row->jenis_beban;
            $kotor = empty($row->kotor) || $row->kotor == '' ? 0 : $row->kotor;
            $pot = empty($row->pot) || $row->pot == '' ? 0 : $row->pot;
            $total_kotor = $kotor + $total_kotor;
            $total_pot = $pot + $total_pot;
            //$total_bersih=$total_kotor-$total_pot;
            $tgl_sp2d = empty($row->tgl_sp2d) || $row->tgl_sp2d == '' ? ' ' : $this->tukd_model->tanggal_ind($row->tgl_sp2d);

            $sqlnam = "SELECT TOP 1 * FROM ms_ttd WHERE kd_skpd = '$kd_skpd' AND kode='BK'";
            $sqlnam = $this->db->query($sqlnam);
            foreach ($sqlnam->result() as $rownam) {
                $nama_ben = $rownam->nama;
                $jabat_ben = $rownam->jabatan;
            }
            $nama_ben = empty($nama_ben) || $nama_ben == 'NULL' ? 'Belum Ada data Bendahara' : $nama_ben;
            $jabat_ben = empty($jabat_ben) || $jabat_ben == 'NULL' ? '' : $jabat_ben;


            if (($jns == 6) && ($jns_bbn == 6)) {

                $cRet .= " <tr >
                    <td valign=\"top\" align=\"center\">$lcno  
                    </td>
                    <td valign=\"top\" align=\"center\" >$no_sp2d <br> $tgl_sp2d
                    </td>                   
                    <td valign=\"top\" align=\"left\">$nmrekan, $pimpinan <br>$alamat
                    </td>                   
                    <td valign=\"top\" align=\"left\" >$kd_skpd<br>$nm_skpd
                    </td>                   
                    <td valign=\"top\" align=\"right\" >" . number_format($kotor, "2", ",", ".") . "&nbsp; 
                     </td>
                    <td valign=\"top\" align=\"right\" >" . number_format($pot, "2", ",", ".") . "&nbsp; 
                    </td>
                    <td valign=\"top\" align=\"right\" >" . number_format($kotor - $pot, "2", ",", ".") . "&nbsp; 
                     </td>
                    <td valign=\"top\" align=\"center\" >&nbsp; 
                    </td>
                                     
                </tr>
                ";
            } else if (($jns == 5)) {

                $cRet .= " <tr >
                    <td valign=\"top\" align=\"center\">$lcno  
                    </td>
                    <td valign=\"top\" align=\"center\" >$no_sp2d <br> $tgl_sp2d
                    </td>                   
                    <td valign=\"top\" align=\"left\">$nmrekan <br> $alamat
                    </td>                   
                    <td valign=\"top\" align=\"left\" >$kd_skpd<br>$nm_skpd
                    </td>                   
                    <td valign=\"top\" align=\"right\" >" . number_format($kotor, "2", ",", ".") . "&nbsp; 
                     </td>
                    <td valign=\"top\" align=\"right\" >" . number_format($pot, "2", ",", ".") . "&nbsp; 
                    </td>
                    <td valign=\"top\" align=\"right\" >" . number_format($kotor - $pot, "2", ",", ".") . "&nbsp; 
                     </td>
                    <td valign=\"top\" align=\"center\" >&nbsp; 
                    </td>
                                     
                </tr>
                ";
            } else {
                $cRet .= " <tr >
                    <td valign=\"top\" align=\"center\" >$lcno  
                    </td>
                    <td valign=\"top\" align=\"center\" >$no_sp2d <br> $tgl_sp2d
                    </td>                   
                    <td valign=\"top\" align=\"left\" >$nmrekan<br>$jabat_ben $nm_skpd
                    </td>                   
                    <td valign=\"top\" align=\"left\" >$kd_skpd<br>$nm_skpd
                    </td>                   
                    <td valign=\"top\" align=\"right\" >" . number_format($kotor, "2", ",", ".") . "&nbsp; 
                     </td>
                    <td valign=\"top\" align=\"right\" >" . number_format($pot, "2", ",", ".") . "&nbsp; 
                    </td>
                    <td valign=\"top\" align=\"right\" >" . number_format($kotor - $pot, "2", ",", ".") . "&nbsp; 
                     </td>
                    <td valign=\"top\" align=\"center\" >&nbsp; 
                    </td>
                                     
                </tr>
                ";
            }
        };

        $cRet .= " <tr style=\"font-size:11px;font-weight:bold;\">
                    <td colspan=\"4\" align=\"center\" >TOTAL
                    </td>
                    <td  align=\"right\" >" . number_format($total_kotor, "2", ",", ".") . "&nbsp; 
                    </td>
                    <td  align=\"right\" >" . number_format($total_pot, "2", ",", ".") . "&nbsp; 
                    </td>
                    <td  align=\"right\" >" . number_format($total_kotor - $total_pot, "2", ",", ".") . "&nbsp;
                    </td>
                    <td  align=\"center\" >&nbsp; 
                    </td>
                </tr>
                ";
        $cRet .= '</table>';

        $cRet .= " <table style=\"border-collapse:collapse;font-weight:bold;font-family:Tahoma; font-size:11px;\" border=\"0\" width=\"100%\" align=\"center\" cellspacing=\"0\" cellpadding=\"0\">
            
            <tr >
                <td align=\"left\" width=\"70%\" style=\"height: 30px;\" >&nbsp;&nbsp;Diterima oleh : ................................................</td>
                <td align=\"center\" width=\"30%\" >&nbsp;</td>
                
                </tr>
            <tr>
                <td>&nbsp;&nbsp;.....................................................</td>
                <td align=\"center\">Kuasa Bendahara Umum Daerah <br>$jabatan</td>
                </tr>
            <tr>
                <td colspan=\"2\" ><br>&nbsp;&nbsp;Petugas Bank / Pos</td>
                </tr>
            <tr >
                <td width=\"100%\" colspan=\"2\" style=\"height: 50px;\" >&nbsp;</td>
                </tr>
            <tr>
                <td>&nbsp;</td>
                <td align=\"center\"><u>$nama</u></td>
                </tr>
            <tr>
                <td>&nbsp;</td>
                <td align=\"center\">$pangkat</td>
                </tr>
            <tr>
                <td><align=\"left\" style=\"width: 250px;\">__________________________________</td>
                <td align=\"center\">NIP. $nip</td>
                </tr>
                </table>";

        $data['prev'] = 'Kartu Kendali';
        if ($print == 1) {
            //$cRet = str_replace('$page', $this->mpdf->getPageCount(), $cRet);
            $this->_mpdf_daftar_penguji('', $cRet, 10, 10, 10, 'L', 0, '', $atas, $bawah, $kiri, $kanan);
        } else {
            $cRet = str_replace('$page', '', $cRet);
            echo $cRet;
        }
    }



    function sp2d1()
    {
        $data['page_title'] = 'INPUT S P 2 D';
        $this->template->set('title', 'INPUT S P 2 D');
        $this->template->load('template', 'tukd/sp2d/sp2d', $data);
    }

    function sp2d_cair()
    {
        $data['page_title'] = 'PENCAIRAN S P 2 D';
        $this->template->set('title', 'PENCAIRAN S P 2 D');
        $this->template->load('template', 'tukd/sp2d_cair/sp2d_cair', $data);
    }

    function load_sp2d_cair()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;

        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = " kd_skpd <> '' ";
        if ($kriteria <> '') {
            $where = " (upper(a.no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(a.no_sp2d) as tot from trhsp2d a INNER JOIN trduji b ON a.no_sp2d=b.no_sp2d where $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows a.no_sp2d,tgl_sp2d, no_spm, tgl_spm,no_spp, tgl_spp, kd_skpd,nm_skpd,jns_spp, keperluan, bulan,
                no_spd, bank, nmrekan, no_rek, npwp, no_kas, no_kas_bud, tgl_kas, tgl_kas_bud,
                nocek, status_bud,jenis_beban,no_spd,no_uji FROM trhsp2d a INNER JOIN trduji b on a.no_sp2d=b.no_sp2d where $where and  a.no_sp2d not in 
        (SELECT TOP $offset a.no_sp2d from trhsp2d a INNER JOIN trduji b on a.no_sp2d=b.no_sp2d where $where order by a.no_sp2d,kd_skpd) order by a.no_sp2d,kd_skpd";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            if ($resulte['status_bud'] == '1') {
                $s = 'Sudah Cair';
            } else {
                $s = 'Belum Cair';
            }

            $row[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'tgl_sp2d' => $this->tukd_model->rev_date($resulte['tgl_sp2d']),
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp'],
                'nokas' => $resulte['no_kas'],
                'nokasbud' => $resulte['no_kas_bud'],
                'dkas' => $resulte['tgl_kas'],
                'dkasbud' => $resulte['tgl_kas_bud'],
                'nocek' => $resulte['nocek'],
                'jenis_beban' => $resulte['jenis_beban'],
                'no_spd' => $resulte['no_spd'],
                'no_uji' => $resulte['no_uji'],
                'status' => $s
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function simpan_cair()
    {
        $no_sp2d = $this->input->post('nsp2d');
        $nokas = $this->input->post('nkas');
        $tglkas = $this->input->post('tcair');
        $nocek = $this->input->post('ncek');
        $total = $this->input->post('tot');
        //$cskpd = $this->input->post('skpd');
        $cket = $this->input->post('ket');
        $advice = $this->input->post('advice');
        $beban = $this->input->post('beban');
        $usernm = $this->session->userdata('pcNama');
        $cskpd = $this->session->userdata('kdskpd');
        $last_update =  "";
        //$last_update=  date('Y-m-d H:i:s');


        $total = str_replace(",", "", $total);

        $nmskpd = $this->tukd_model->get_nama($cskpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');

        $sql = " update trhsp2d set status_bud='1', no_kas_bud='$nokas', tgl_kas_bud='$tglkas',no_advice='$advice' where no_sp2d='$no_sp2d' ";
        $asg = $this->db->query($sql);


        $sql3 = " insert into trhju_pkd(no_voucher,tgl_voucher,ket,username,tgl_update,kd_skpd,nm_skpd,kd_unit,total_d,total_k,tabel) 
                  values('$nokas','$tglkas','$no_sp2d','$usernm','$last_update','$cskpd','$nmskpd','$cskpd','$total','$total','0')";
        $asg3 = $this->db->query($sql3);



        $sql = " SELECT a.no_spp,a.kd_skpd,a.kd_kegiatan,a.kd_rek5,a.nilai,b.bulan,c.no_spm,d.no_sp2d,b.sts_tagih FROM trdspp a 
                 LEFT JOIN trhspp b ON a.no_spp=b.no_spp
                 LEFT JOIN trhspm c ON c.no_spp=b.no_spp
                 LEFT JOIN trhsp2d d ON d.no_spm=c.no_spm
                 WHERE d.no_sp2d='$no_sp2d' ";
        $query1 = $this->db->query($sql);
        $ii = 0;
        $jum = 0;
        foreach ($query1->result_array() as $resulte) {

            $sp2d = $no_sp2d;
            $jns = $beban;
            $skpd = $resulte['kd_skpd'];
            $giat = $resulte['kd_kegiatan'];
            $rek5 = $resulte['kd_rek5'];

            //$rek9='9'.substr($rek5,1,7);//$resulte['kd_rek5'];

            $rek9 = $this->tukd_model->get_nama($rek5, 'map_lo', 'ms_rek5', 'kd_rek5');
            $nmrek9 = $this->tukd_model->get_nama($rek9, 'nm_rek5', 'ms_rek5', 'kd_rek5');

            $rek64 = $this->tukd_model->get_nama($rek5, 'kd_rek64', 'ms_rek5', 'kd_rek5');
            $nmrek64 = $this->tukd_model->get_nama($rek64, 'nm_rek64', 'ms_rek5', 'kd_rek64');

            $nilai = $resulte['nilai'];
            $tagih = $resulte['sts_tagih'];

            if ($beban == '1') {
                $rek3 = $rek5;
            } else {
                $rek3 = substr($rek5, 0, 3);
            }

            $jum = $jum + $nilai;

            $rekutang = $this->tukd_model->get_nama($rek64, 'piutang_utang', 'ms_rek5', 'kd_rek64');

            $nmskpd = $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
            $nmgiat = $this->tukd_model->get_nama($giat, 'nm_kegiatan', 'trskpd', 'kd_kegiatan');
            $nmrek5 = $this->tukd_model->get_nama($rek5, 'nm_rek5', 'ms_rek5', 'kd_rek5');
            $nmrekutang = $this->tukd_model->get_nama($rekutang, 'nm_rek5', 'ms_rek5', 'kd_rek5');
        }


        if ($tagih == '1') {
            $this->db->query("insert trdju_pkd(no_voucher,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos,kd_unit) 
                                  values('$nokas','','','1180101','RK SKPD','$jum','0','D','','1','1','$cskpd') ");
            $this->db->query("insert trdju_pkd(no_voucher,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos,kd_unit) 
                                  values('$nokas','','','1110101','KAS DI KAS DAERAH','0','$jum','K','','2','1','$cskpd') ");
        } else {
            if (($jns == '1') or ($jns == '2') or ($jns == '3')) {
                $this->db->query("insert trdju_pkd(no_voucher,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos,kd_unit) 
                                  values('$nokas','','','1180101','RK SKPD','$jum','0','D','','1','1','$cskpd') ");
                $this->db->query("insert trdju_pkd(no_voucher,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos,kd_unit) 
                                  values('$nokas','','','1110101','KAS DI KAS DAERAH','0','$jum','K','','2','1','$cskpd') ");
            } else {
                $this->db->query("insert trdju_pkd(no_voucher,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos,kd_unit) 
                                  values('$nokas','','','1180101','RK SKPD','$jum','0','D','','1','1','$cskpd') ");
                $this->db->query("insert trdju_pkd(no_voucher,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos,kd_unit) 
                                  values('$nokas','','','1110101','KAS DI KAS DAERAH','0','$jum','K','','2','1','$cskpd') ");
            }
        }

        echo '1';
    }

    function batal_cair()
    {
        $no_sp2d = $this->input->post('nsp2d');
        $nokas = $this->input->post('nkas');
        $tglkas = $this->input->post('tcair');
        $nocek = $this->input->post('ncek');
        $total = $this->input->post('tot');
        $cskpd = $this->session->userdata('kdskpd');
        $sql = " update trhsp2d set status_bud='0',no_kas_bud='',tgl_kas_bud='',nocek='' where no_sp2d='$no_sp2d' ";
        $asg = $this->db->query($sql);
        $sql = " DELETE FROM trhju_pkd WHERE no_voucher='$nokas' AND kd_skpd='$cskpd' ";
        $asg = $this->db->query($sql);
        $sql = " DELETE FROM trdju_pkd WHERE no_voucher='$nokas' AND kd_unit='$cskpd' ";
        $asg = $this->db->query($sql);
        if (($asg > 0) and ($asg > 0)) {
            echo '1';
        }
    }

    function hapus_sp2d()
    {

        $nom = $this->input->post('no');
        $spm = $this->input->post('spm');
        $this->db->trans_start();
        $query = $this->db->query("DELETE from trhsp2d where no_sp2d='$nom'");
        $query = $this->db->query("UPDATE trhspm set [status]='0' where no_spm='$spm'");
        $this->db->trans_complete();
    }

    function cek_simpan()
    {
        $nomor    = $this->input->post('no');
        $tabel   = $this->input->post('tabel');
        $field    = $this->input->post('field');
        $kd_skpd  = $this->session->userdata('kdskpd');

        $hasil = $this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd' ");
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

    function no_urut()
    {
        $kd_skpd = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("select case when max(nomor+1) is null then 1 else max(nomor+1) end as nomor from (
    select no_kas_bud nomor,'Pencairan SP2D' ket,kd_skpd from trhsp2d where isnumeric(no_kas_bud)=1 and status_bud=1
    ) z 
    ");
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'no_urut' => $resulte['nomor']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function sp2d_up()
    {
        $data['page_title'] = 'INPUT SP2D';
        $this->template->set('title', 'INPUT SP2D UP');
        $this->template->load('template', 'tukd/sp2d/sp2d_up', $data);
    }

    function load_sp2d_up()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $id  = $this->session->userdata('pcUser');
        $kriteria = $this->input->post('cari');
        $where = " kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ";

        if ($kriteria <> '') {
            $where = " (upper(z.no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%' ) or upper(no_spp) like upper('%$kriteria%') or upper(no_spm) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from (select a.* from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where $where AND jns_spp='1'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();


        $sql = "SELECT TOP $rows *from (select a.* , (case when c.jns_beban='5' then 'Belanja' else 'Pembiayaan' end)jns_spd from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner 
                join trhspd c on a.no_spd=c.no_spd) z  where $where  AND jns_spp='1' and z.no_sp2d not in 
                (SELECT TOP $offset z.no_sp2d from (select a.* from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z 
                where $where  AND jns_spp='1' order by tgl_sp2d,z.no_sp2d,kd_skpd) order by tgl_sp2d,z.no_sp2d,kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            if ($resulte['status_terima'] == '1') {
                $s = 'Sudah Diterima';
            } else {
                $s = 'Belum Diterima';
            }

            if ($resulte['status_bud'] == '1') {
                $s_bud = 'Sudah Cair';
            } else {
                $s_bud = 'Belum Cair';
            }


            $nosp2dexplode = explode("/", $resulte['no_sp2d']);
            $nosp2d = $nosp2dexplode[0];

            $row[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'no_sp2ds' => $nosp2d,
                'tgl_sp2d' => $resulte['tgl_sp2d'],
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp'],
                'sp2d_batal' => $resulte['sp2d_batal'],
                'jenis_beban' => $resulte['jenis_beban'],
                'status' => $s,
                'status_bud' => $s_bud,
                'nokasbud' => $resulte['no_kas_bud'],
                'dkasbud' => $resulte['tgl_kas_bud'],
                'jns_spd' => $resulte['jns_spd']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function nospm_up()
    {
        $id  = $this->session->userdata('pcUser');
        $lccr = $this->input->post('q');
        $tanggal = date("d");
        $bulan = '1';
        // $bulan=date("m");
        if ($bulan < 10) {
            $bulan = str_replace("0", "", $bulan);
            $bulan = $bulan - 1;
        }
        $sql = "SELECT a.no_spm, a.tgl_spm, a.no_spp, a.tgl_spp, a.kd_skpd,a.nm_skpd,a.jns_spp,
       a.keperluan, a.bulan, a.no_spd, a.bank, a.nmrekan, a.no_rek, a.npwp,
       (case when b.jns_beban='5' then 'Belanja' else 'Pembiayaan' end)jns_spd  FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd 
       join trhspp c on a.no_spp=c.no_spp 
        where a.jns_spp='1'  and (c.sp2d_batal!='1' or c.sp2d_batal is null) OR a.status NOT IN('0') AND a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id')
        AND (upper(a.no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%')) AND a.no_spm NOT IN(SELECT no_spm FROM trhsp2d WHERE no_spm=a.no_spm)";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'jns_spd' => $resulte['jns_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function sp2d_gu()
    {
        $data['page_title'] = 'INPUT SP2D';
        $this->template->set('title', 'INPUT SP2D GU');
        $this->template->load('template', 'tukd/sp2d/sp2d_gu', $data);
    }

    function load_sp2d_gu()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $id  = $this->session->userdata('pcUser');
        $kriteria = $this->input->post('cari');
        $where = " kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ";

        if ($kriteria <> '') {
            $where = " (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%') or upper(no_spp) like upper('%$kriteria%') or upper(no_spm) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from (select a.* from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where $where AND jns_spp IN ('2','7')";
        $query1 = $this->db->query($sql);
        $total = $query1->row();


        $sql = "SELECT TOP $rows *
        from (select a.* , (case when c.jns_beban='5' then 'Belanja' else 'Pembiayaan' end)jns_spd
        from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where $where  AND jns_spp IN ('2','7') and no_sp2d not in 
        (SELECT TOP $offset no_sp2d from (select a.* from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where $where  AND jns_spp IN ('2','7') order by no_sp2d,kd_skpd) order by no_sp2d,kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            if ($resulte['status_terima'] == '1') {
                $s = 'Sudah Diterima';
            } else {
                $s = 'Belum Diterima';
            }

            if ($resulte['status_bud'] == '1') {
                $s_bud = 'Sudah Cair';
            } else {
                $s_bud = 'Belum Cair';
            }

            $row[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'tgl_sp2d' => $resulte['tgl_sp2d'],
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp'],
                'sp2d_batal' => $resulte['sp2d_batal'],
                'jenis_beban' => $resulte['jenis_beban'],
                'status' => $s,
                'status_bud' => $s_bud,
                'nokasbud' => $resulte['no_kas_bud'],
                'dkasbud' => $resulte['tgl_kas_bud'],
                'jns_spd' => $resulte['jns_spd']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function nospm_gu()
    {
        $id  = $this->session->userdata('pcUser');
        $lccr = $this->input->post('q');
        $tanggal = date("d");
        $bulan = date("m");
        if ($bulan < 10) {
            $bulan = str_replace("0", "", $bulan);
            $bulan = $bulan - 1;
        }
        //if($tanggal<11){
        $sql = "SELECT a.no_spm, a.tgl_spm, a.no_spp, a.tgl_spp, a.kd_skpd,a.nm_skpd,a.jns_spp,
       a.keperluan, a.bulan, a.no_spd, a.bank, a.nmrekan, a.no_rek, a.npwp,
       (case when b.jns_beban='5' then 'Belanja' else 'Pembiayaan' end)jns_spd  FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd 
       join trhspp c on a.no_spp=c.no_spp 
        where a.status = '0' AND a.jns_spp IN('2','7') and (c.sp2d_batal!='1' or c.sp2d_batal is null) AND a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id')
        AND (upper(a.no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%'))"; //no_spm not in (select no_spm from trhsp2d where no_spm<>'$lccr') and
        /*}else{
        $sql = "SELECT a.no_spm, a.tgl_spm, a.no_spp, a.tgl_spp, a.kd_skpd,a.nm_skpd,a.jns_spp,
       a.keperluan, a.bulan, a.no_spd, a.bank, a.nmrekan, a.no_rek, a.npwp,
       (case when jns_beban='51' then 'BTL' else 'BL' end)jns_spd  FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd 
        where a.status = '0' AND a.jns_spp='2' AND a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id')
        AND a.kd_skpd IN (select kd_skpd from trhspj_ppkd WHERE bulan='$bulan' AND cek='1')
        AND (upper(no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%'))";//no_spm not in (select no_spm from trhsp2d where no_spm<>'$lccr') and
        //}*/


        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'jns_spd' => $resulte['jns_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function sp2d_tu()
    {
        $data['page_title'] = 'INPUT SP2D';
        $this->template->set('title', 'INPUT SP2D TU');
        $this->template->load('template', 'tukd/sp2d/sp2d_tu', $data);
    }

    function pilih_sp2d()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT no_sp2d,kd_skpd,no_spm FROM trhsp2d where upper(no_sp2d) like upper('%$lccr%') or upper(kd_skpd) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'kd_skpd' => $resulte['kd_skpd'],
                'no_spm' => $resulte['no_spm']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_sp2d_tu()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $id  = $this->session->userdata('pcUser');
        $kriteria = $this->input->post('cari');
        $where = " kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ";

        if ($kriteria <> '') {
            $where = " (upper(z.no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%') or upper(no_spp) like upper('%$kriteria%') or upper(no_spm) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from (select a.* from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where $where AND jns_spp='3'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();


        $sql = "SELECT TOP $rows * from (select a.* , (case when c.jns_beban='5' then 'Belanja' else 'Pembiayaan' end)jns_spd from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner 
                join trhspd c on a.no_spd=c.no_spd) z join nomor_sp2d e on z.no_sp2d=e.no_sp2d where $where  AND jns_spp='3' and z.no_sp2d not in 
                (SELECT TOP $offset z.no_sp2d from (select a.* from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z 
                join nomor_sp2d d on z.no_sp2d=d.no_sp2d where $where  AND jns_spp='3' order by d.nomor,tgl_sp2d,z.no_sp2d,kd_skpd) order by e.nomor,tgl_sp2d,z.no_sp2d,kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            if ($resulte['status_terima'] == '1') {
                $s = 'Sudah Diterima';
            } else {
                $s = 'Belum Diterima';
            }

            if ($resulte['status_bud'] == '1') {
                $s_bud = 'Sudah Cair';
            } else {
                $s_bud = 'Belum Cair';
            }

            $row[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'tgl_sp2d' => $resulte['tgl_sp2d'],
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp'],
                'sp2d_batal' => $resulte['sp2d_batal'],
                'jenis_beban' => $resulte['jenis_beban'],
                'status' => $s,
                'status_bud' => $s_bud,
                'nokasbud' => $resulte['no_kas_bud'],
                'dkasbud' => $resulte['tgl_kas_bud'],
                'jns_spd' => $resulte['jns_spd']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function nospm_tu()
    {
        $id  = $this->session->userdata('pcUser');
        $lccr = $this->input->post('q');
        $tanggal = date("d");
        $bulan = date("m");
        if ($bulan < 10) {
            $bulan = str_replace("0", "", $bulan);
            $bulan = $bulan - 1;
        }
        //if($tanggal<11){
        $sql = "SELECT a.no_spm, a.tgl_spm, a.no_spp, a.tgl_spp, a.kd_skpd,a.nm_skpd,a.jns_spp,
       a.keperluan, a.bulan, a.no_spd, a.bank, a.nmrekan, a.no_rek, a.npwp,
       (case when b.jns_beban='5' then 'Belanja' else 'Pembiayaan' end)jns_spd  FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd 
       join trhspp c on a.no_spp=c.no_spp
        where a.status = '0' AND a.jns_spp='3' and (c.sp2d_batal!='1' or c.sp2d_batal is null)  AND a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id')
        AND (upper(a.no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%'))"; //no_spm not in (select no_spm from trhsp2d where no_spm<>'$lccr') and
        /*}else{
        $sql = "SELECT a.no_spm, a.tgl_spm, a.no_spp, a.tgl_spp, a.kd_skpd,a.nm_skpd,a.jns_spp,
       a.keperluan, a.bulan, a.no_spd, a.bank, a.nmrekan, a.no_rek, a.npwp,
       (case when jns_beban='51' then 'BTL' else 'BL' end)jns_spd  FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd 
        where a.status = '0' AND a.jns_spp='3' AND a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id')
        AND a.kd_skpd IN (select kd_skpd from trhspj_ppkd WHERE bulan='$bulan' AND cek='1')
        AND (upper(no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%'))";//no_spm not in (select no_spm from trhsp2d where no_spm<>'$lccr') and
        //}
        */

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'jns_spd' => $resulte['jns_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function sp2d_ls()
    {
        $data['page_title'] = 'INPUT SP2D';
        $this->template->set('title', 'INPUT SP2D LS');
        $this->template->load('template', 'tukd/sp2d/sp2d_ls', $data);
    }

    function pilih_sp2d_ls()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT TOP 10 no_sp2d,kd_skpd,no_spm FROM trhsp2d where jns_spp IN ('4','6') AND no_sp2d NOT LIKE '%GJ%' AND (upper(no_sp2d) like upper('%$lccr%') or upper(kd_skpd) like upper('%$lccr%')) ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'kd_skpd' => $resulte['kd_skpd'],
                'no_spm' => $resulte['no_spm']
            );
            $ii++;
        }

        echo json_encode($result);
    }

    function load_sp2d_ls()
    {

        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $id  = $this->session->userdata('pcUser');
        $kriteria = $this->input->post('cari');
        // $where =" kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ";
        $where = '';
        if ($kriteria <> '') {
            $where = " AND (upper(z.no_sp2d) like upper('%$kriteria%') or z.tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%') or upper(no_spp) like upper('%$kriteria%') or upper(no_spm) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from (select a.* from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where jns_spp IN ('5','6') $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows * from (select a.* , (case when c.jns_beban='5' then 'Belanja' else 'Pembiayaan' end) jns_spd FROM trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c 
                on a.no_spd=c.no_spd) z  where jns_spp IN ('5','6') $where AND z.no_sp2d not in 
                (SELECT TOP $offset z.no_sp2d from (select a.* from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z 
                where jns_spp IN ('5','6') $where order by tgl_sp2d,z.no_sp2d,kd_skpd) order by tgl_sp2d,z.no_sp2d,kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            if ($resulte['status_terima'] == '1') {
                $s = 'Sudah Diterima';
            } else {
                $s = 'Belum Diterima';
            }

            if ($resulte['status_bud'] == '1') {
                $s_bud = 'Sudah Cair';
            } else {
                $s_bud = 'Belum Cair';
            }

            $nosp2dexplode = explode("/", $resulte['no_sp2d']);
            $nosp2d = $nosp2dexplode[0];

            $row[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'nosp2d' => $nosp2d,
                'tgl_sp2d' => $resulte['tgl_sp2d'],
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp'],
                'sp2d_batal' => $resulte['sp2d_batal'],
                'jenis_beban' => $resulte['jenis_beban'],
                'status' => $s,
                'status_bud' => $s_bud,
                'nokasbud' => $resulte['no_kas_bud'],
                'dkasbud' => $resulte['tgl_kas_bud'],
                'jns_spd' => $resulte['jns_spd']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function nospm_ls()
    {
        //  $id  = $this->session->userdata('pcUser');        
        //  $lccr = $this->input->post('q');
        //  $tanggal=date("d");
        //  $bulan=date("m");
        //  if($bulan<10){
        //      $bulan = str_replace("0","",$bulan);
        //      $bulan = $bulan-1;
        //  }

        //  $sql = "SELECT a.no_spm, a.tgl_spm, a.no_spp, a.tgl_spp, a.kd_skpd,a.nm_skpd,a.jns_spp,
        // a.keperluan, a.bulan, a.no_spd, a.bank, a.nmrekan, a.no_rek, a.npwp,
        // (case when b.jns_beban='5' then 'Belanja' else 'Pembiayaan' end)jns_spd  FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd 
        // join trhspp c on a.no_spp=c.no_spp 
        //  where a.status = '0' AND a.jns_spp IN ('5','6') AND (c.sp2d_batal!='1' or c.sp2d_batal is null)  AND a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id')
        //  AND (upper(a.no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%'))
        //  UNION ALL
        //  SELECT a.no_spm, a.tgl_spm, a.no_spp, a.tgl_spp, a.kd_skpd,a.nm_skpd,a.jns_spp,
        // a.keperluan, a.bulan, a.no_spd, a.bank, a.nmrekan, a.no_rek, a.npwp,
        // (case when b.jns_beban='5' then 'Belanja' else 'Pembiayaan' end)jns_spd  FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd 
        // join trhspp c on a.no_spp=c.no_spp 
        //  where a.status = '0' AND a.jns_spp IN ('4') AND a.jenis_beban NOT IN ('1','7') and (c.sp2d_batal!='1' or c.sp2d_batal is null)  AND a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id')
        //  AND (upper(a.no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%'))
        //  UNION ALL
        //  SELECT a.no_spm, a.tgl_spm, a.no_spp, a.tgl_spp, a.kd_skpd,a.nm_skpd,a.jns_spp,
        // a.keperluan, a.bulan, a.no_spd, a.bank, a.nmrekan, a.no_rek, a.npwp,
        // (case when b.jns_beban='5' then 'Belanja' else 'Pembiayaan' end)jns_spd  FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd 
        // join trhspp c on a.no_spp=c.no_spp 
        //  where a.kd_skpd IN ('5.02.01.01') AND (a.keperluan LIKE '%anggota dprd%' OR a.keperluan LIKE '%BPOP%') AND a.status = '0' AND a.jns_spp IN ('4') AND a.jenis_beban IN ('1','7') and (c.sp2d_batal!='1' or c.sp2d_batal is null)  AND a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id')
        //  AND (upper(a.no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%'))
        //  UNION ALL
        //  SELECT a.no_spm, a.tgl_spm, a.no_spp, a.tgl_spp, a.kd_skpd,a.nm_skpd,a.jns_spp,
        // a.keperluan, a.bulan, a.no_spd, a.bank, a.nmrekan, a.no_rek, a.npwp,
        // (case when b.jns_beban='5' then 'Belanja' else 'Pembiayaan' end)jns_spd  FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd 
        // join trhspp c on a.no_spp=c.no_spp 
        //  where a.kd_skpd IN ('5.01.01.00') AND a.no_spm like '%BTL%' AND a.status = '0' AND a.jns_spp IN ('4') AND a.jenis_beban IN ('1','7') and (c.sp2d_batal!='1' or c.sp2d_batal is null)  AND a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id')
        //  AND (upper(a.no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%'))";

        $lccr = $this->input->post('q');



        $sql = "SELECT a.* FROM trhspm a INNER JOIN trhspd b on a.no_spd =b.no_spd INNER JOIN trhspp c on c.no_spp=a.no_spp AND c.no_spd=b.no_spd WHERE a.jns_spp IN ('5', '6') AND (c.sp2d_batal IS NULL OR c.sp2d_batal!=1) AND a.status ='0' AND (upper(a.no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%') or upper(a.nm_skpd) like upper('%$lccr%')) AND a.no_spm NOT IN(SELECT no_spm FROM trhsp2d WHERE no_spm=a.no_spm)";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'nilai' => number_format($resulte['nilai']),
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_sisa_anggaran($skpd)
    {
        $query1 = $this->db->query("SELECT SUM(nilai_ubah) as nilai FROM trdrka WHERE kd_skpd='$skpd' 
                                    AND (kd_sub_kegiatan NOT IN ('2.11.00.0.00.04','4.01.01.1.11.01') OR  right(kd_sub_kegiatan,5) not in ('00.04') OR  right(kd_sub_kegiatan,10) not in ('01.1.02.01') )");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'nilai' => number_format($resulte['nilai'], 2, '.', ','),
            );
            $ii++;
        }
        //return $result;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_sisa_sp2d($skpd)
    {
        $query1 = $this->db->query("SELECT SUM(nilai) as nilai FROM (
                                    SELECT SUM(d.nilai) as nilai FROM trhsp2d a 
                                    INNER JOIN trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm
                                    INNER JOIN trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp
                                    INNER JOIN trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp
                                    WHERE (c.sp2d_batal!=1 OR c.sp2d_batal IS NULL) AND a.kd_skpd='$skpd'
                                    AND  (right(d.kd_sub_kegiatan,10) not in ('01.1.02.01') OR d.kd_sub_kegiatan<>'4.01.01.1.11.01')
                                    UNION ALL
                                    SELECT SUM(d.nilai) as nilai FROM trhsp2d a 
                                    INNER JOIN trhspm b ON a.kd_skpd=b.kd_skpd AND a.no_spm=b.no_spm
                                    INNER JOIN trhspp c ON b.kd_skpd=c.kd_skpd AND b.no_spp=c.no_spp
                                    INNER JOIN trdspp d ON c.kd_skpd=d.kd_skpd AND c.no_spp=d.no_spp
                                    WHERE (c.sp2d_batal!=1 OR c.sp2d_batal IS NULL) AND a.kd_skpd='$skpd'
                                    AND a.jns_spp='1')a");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'nilai' => number_format($resulte['nilai'], 2, '.', ','),
            );
            $ii++;
        }
        //return $result;
        echo json_encode($result);
        $query1->free_result();
    }

    function sp2d_gj()
    {
        $data['page_title'] = 'INPUT SP2D';
        $this->template->set('title', 'INPUT SP2D Gaji');
        $this->template->load('template', 'tukd/sp2d/sp2d_gj', $data);
    }

    function load_sp2d_gj()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        //$id  = $this->session->userdata('pcUser');        
        $kriteria = $this->input->post('cari');
        // $where =" kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ";
        $where = " kd_skpd <>'' ";
        if ($kriteria <> '') {
            $where = " (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%') or upper(no_spp) like upper('%$kriteria%') or upper(no_spm) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from (select a.* from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where $where AND jns_spp = '4'";
        $query1 = $this->db->query($sql);
        $total = $query1->row();

        $sql = "SELECT TOP $rows *
        from (select a.* , (case when c.jns_beban='5' then 'Belanja' else 'Pembiayaan' end) jns_spd FROM trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where $where  AND jns_spp = '4' AND no_sp2d not in 
        (SELECT TOP $offset no_sp2d from (select a.* from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where $where AND jns_spp = '4' order by no_sp2d,kd_skpd) order by no_sp2d,kd_skpd ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            if ($resulte['status_terima'] == '1') {
                $s = 'Sudah Diterima';
            } else {
                $s = 'Belum Diterima';
            }

            if ($resulte['status_bud'] == '1') {
                $s_bud = 'Sudah Cair';
            } else {
                $s_bud = 'Belum Cair';
            }

            $nosp2dexplode = explode("/", $resulte['no_sp2d']);
            $nosp2d = $nosp2dexplode[0];

            $row[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'no_sp2ds' => $nosp2d,
                'tgl_sp2d' => $resulte['tgl_sp2d'],
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp'],
                'sp2d_batal' => $resulte['sp2d_batal'],
                'jenis_beban' => $resulte['jenis_beban'],
                'status' => $s,
                'status_bud' => $s_bud,
                'nokasbud' => $resulte['no_kas_bud'],
                'dkasbud' => $resulte['tgl_kas_bud'],
                'jns_spd' => $resulte['jns_spd']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function nomorsp2d()
    {
        $sql = "SELECT nosp2d+1 as no_sp2d FROM nomor";
        $query1 = $this->db->query($sql);

        $test = $query1->num_rows();

        foreach ($query1->result_array() as $resulte) {
            $no = $resulte['no_sp2d'];
            $urut = str_pad($no, 6, "0", STR_PAD_LEFT);
            $result = array(
                'no_sp2d' => $urut
            );
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function configNosp2D($jns_spp = "")
    {
        $tahun   = $this->session->userdata('pcThang');
        // if ($jns_spp == 'LS') {
        //     $where    = "WHERE a.jns_spp IN ('4','6','5')";
        // } else
        // if ($jns_spp == 'UP') {
        //     $where    = "WHERE a.jns_spp IN ('1')";
        // } else
        // if ($jns_spp == 'TU') {
        //     $where    = "WHERE a.jns_spp IN ('3')";
        // } else
        // if ($jns_spp == 'GU') {
        //     $where    = "WHERE a.jns_spp IN ('2')";
        // }

        // $sql = "SELECT MAX
        // (CAST ( ltrim( rtrim( nomor )) AS VARCHAR )) nilai 
        // FROM(SELECT
        //     CASE WHEN
        //     jns_spp = 1 THEN replace( nomor, '/UP', '' ) 
        //     WHEN jns_spp = 2 THEN replace( nomor, '/GU', '' ) 
        //     WHEN jns_spp = 3 THEN replace( nomor, '/TU', '' ) 
        //     WHEN jns_spp IN ( '4', '5', '6' ) THEN replace( nomor, '/LS', '' ) WHEN jns_spp IN ('4') THEN REPLACE(nomor,'/LS-GJ','') ELSE replace( nomor, '/GU NIHIL', '' ) 
        //     END AS nomor FROM
        //     (SELECT REPLACE( no_sp2d, '/'+'$tahun', '' ) nomor, jns_spp FROM trhsp2d ) okeii 
        //     )oke";  

        $sql = "SELECT MAX
            (CAST ( ltrim( rtrim( nomor )) AS VARCHAR )) nilai 
            FROM(
    SELECT CASE WHEN jns_spp=1 THEN REPLACE(nomor, '/UP','')
    WHEN jns_spp=2 THEN REPLACE(nomor, '/GU','')
       WHEN jns_spp = 3 THEN replace( nomor, '/TU', '' )
                   WHEN jns_spp = 4 THEN replace( nomor, '/LS-GJ', '' ) 
                        WHEN jns_spp = 5 THEN replace( nomor, '/LS', '' )
                            WHEN jns_spp = 6 THEN replace( nomor, '/LS', '' )
                            WHEN jns_spp = 7 THEN replace( nomor, '/GU-NIHIL', '' )
    END AS nomor FROM (SELECT REPLACE( no_sp2d, '/'+'2023', '' ) nomor, jns_spp FROM trhsp2d) na) naa";
        $query1 = $this->db->query($sql);

        foreach ($query1->result_array() as $resulte)

            $no = $resulte['nilai'] + 1;
        $urut = str_pad($no, 6, "0", STR_PAD_LEFT); {
            $result = array(
                'nomor' => $urut
            );
        }
        echo json_encode($result);
    }

    function nospm()
    {
        //  $lccr = $this->input->post('q');    
        //  $tanggal=date("d");
        //  $bulan=date("m");
        //  if($bulan<10){
        //      $bulan = str_replace("0","",$bulan);

        //  }

        //  $sql = "SELECT a.no_spm, a.tgl_spm, a.no_spp, a.tgl_spp, a.kd_skpd,a.nm_skpd,a.jns_spp,
        // a.keperluan, a.bulan, a.no_spd, a.bank, a.nmrekan, a.no_rek, a.npwp,
        // (case when b.jns_beban='5' then 'Belanja' else 'Pembiayaan' end)jns_spd  FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd
        //  join trhspp c on a.no_spp=c.no_spp and a.jns_spp=c.jns_spp
        //  where a.status = '0' and (c.sp2d_batal<>'1' or c.sp2d_batal is null) and a.jns_spp='4' AND a.jenis_beban IN ('1','7')
        //  AND (upper(no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%'))
        //  UNION ALL
        //  SELECT a.no_spm, a.tgl_spm, a.no_spp, a.tgl_spp, a.kd_skpd,a.nm_skpd,a.jns_spp,
        // a.keperluan, a.bulan, a.no_spd, a.bank, a.nmrekan, a.no_rek, a.npwp,
        // (case when b.jns_beban='5' then 'Belanja' else 'Pembiayaan' end)jns_spd  FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd
        //  join trhspp c on a.no_spp=c.no_spp and a.jns_spp=c.jns_spp
        //  where a.kd_skpd='3.10.01.01' AND a.keperluan NOT LIKE '%anggota dprd%' AND a.keperluan NOT LIKE '%BPOP%' AND a.status = '0' and (c.sp2d_batal<>'1' or c.sp2d_batal is null) and a.jns_spp='4' AND a.jenis_beban IN ('1','7') 
        //  AND (upper(no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%'))
        //  UNION ALL
        //  SELECT a.no_spm, a.tgl_spm, a.no_spp, a.tgl_spp, a.kd_skpd,a.nm_skpd,a.jns_spp,
        // a.keperluan, a.bulan, a.no_spd, a.bank, a.nmrekan, a.no_rek, a.npwp,
        // (case when b.jns_beban='5' then 'Belanja' else 'Pembiayaan' end)jns_spd  FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd
        //  join trhspp c on a.no_spp=c.no_spp and a.jns_spp=c.jns_spp
        //  where a.kd_skpd IN ('1.20.02.01') AND a.no_spm not like '%BTL%' AND a.status = '0' and (c.sp2d_batal<>'1' or c.sp2d_batal is null) and a.jns_spp='4' AND a.jenis_beban IN ('1','7')
        //  AND (upper(no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%'))";
        //  $query1 = $this->db->query($sql);  
        //  $result = array();
        //  $ii = 0;
        //  foreach($query1->result_array() as $resulte)
        //  { 

        //      $result[] = array(
        //                  'id' => $ii,
        //                  'no_spm' => $resulte['no_spm'],
        //                  'tgl_spm' => $resulte['tgl_spm'],        
        //                  'no_spp' => $resulte['no_spp'],
        //                  'tgl_spp' => $resulte['tgl_spp'],
        //                  'kd_skpd' => $resulte['kd_skpd'],
        //                  'nm_skpd' => $resulte['nm_skpd'],    
        //                  'jns_spp' => $resulte['jns_spp'],
        //                  'keperluan' => $resulte['keperluan'],
        //                  'bulan' => $resulte['bulan'],
        //                  'no_spd' => $resulte['no_spd'],
        //                  'jns_spd' => $resulte['jns_spd'],
        //                  'bank' => $resulte['bank'],
        //                  'nmrekan' => $resulte['nmrekan'],
        //                  'no_rek' => $resulte['no_rek'],
        //                  'npwp' => $resulte['npwp']
        //                  );
        //                  $ii++;
        //  }

        //  echo json_encode($result);
        //   $query1->free_result();
        $lccr = $this->input->post('q');

        $sql = "SELECT a.* FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd INNER JOIN trhspp c ON c.kd_skpd=a.kd_skpd AND c.no_spp=a.no_spp WHERE c.sp2d_batal IS NULL AND a.jns_spp = '4' AND (upper(a.no_spm) like upper('%$lccr%') or upper(a.kd_skpd) like upper('%$lccr%') or upper(a.nm_skpd) like upper('%$lccr%')) AND a.no_spm NOT IN(SELECT no_spm FROM trhsp2d WHERE no_spm=a.no_spm)";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'nilai' => number_format($resulte['nilai']),
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function nospm1()
    {
        $lccr = $this->input->post('q');
        //      $sql = "SELECT * FROM trhspm ";       
        $sql = "SELECT a.*,(case when b.jns_beban in ('5') then 'Belanja' else 'Pembiayaan' end)jns_spd  FROM trhspm a inner join trhspd b on a.no_spd =b.no_spd join trhspp c on a.no_spp=c.no_spp where 
                (c.sp2d_batal is null or sp2d_batal<>'1')";
        //
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'jns_beban' => $resulte['jenis_beban'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'jns_spd' => $resulte['jns_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function select_data1($spp = '')
    {
        //$kegiatan = $this->uri->segment(3);

        $spp = $this->input->post('spp');
        $sql = "SELECT kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,sisa FROM trdspp WHERE no_spp='$spp' ORDER BY kd_sub_kegiatan,kd_rek6";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'idx' => $ii,
                'kdkegiatan' => $resulte['kd_sub_kegiatan'],
                'nmkegiatan' => $resulte['nm_sub_kegiatan'],
                'kdrek5' => $resulte['kd_rek6'],
                'nmrek5' => $resulte['nm_rek6'],
                'nilai1' => $resulte['nilai'],
                'nilai' => number_format($resulte['nilai'], "2", ",", "."),
                'sisa' => number_format($resulte['sisa'], "2", ",", "."),
                'sis' => $resulte['sisa']

            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function pot()
    {
        $spm = $this->input->post('spm');
        $sql = "SELECT * FROM trspmpot where no_spm='$spm' order by kd_rek6";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $sts_pot = $resulte['status_potongan'];
            if ($sts_pot == '1') {
                $status = 'Memotong Langsung Pencairan SP2D';
            } else {
                $status = 'Sebagai Informasi Tidak Memotong Pencairan SP2D';
            }

            $result[] = array(
                'id' => $ii,
                'kd_rek6' => $resulte['kd_rek6'],
                'nm_rek6' => $resulte['nm_rek6'],
                'pot' => $resulte['pot'],
                'nilai' => $resulte['nilai'],
                'status' => $status
            );
            $ii++;
        }

        echo json_encode($result);
        //$query1->free_result();   
    }

    function config_bank2()
    {
        $lccr   = $this->input->post('q');
        $sql    = "SELECT kode, nama FROM ms_bank where upper(kode) like '%$lccr%' or upper(nama) like '%$lccr%' order by kode ";

        // $sql = "SELECT * from ms_bank order by kode";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'kd_bank' => $resulte['kode'],
                'nama_bank' => $resulte['nama']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function simpan_sp2d()
    {
        $nomormurni = $this->input->post('nomormurni');
        $no_spp = $this->input->post('no_spp');
        $no_spm = $this->input->post('no_spm');
        //$no_sp2d = $this->input->post('no_sp2d');
        $kd_skpd = $this->input->post('cskpd');
        $keperluan = $this->input->post('keperluan');
        $jns_spp = $this->input->post('jns_spp');
        $jns_beban = $this->input->post('jenis');
        $tgl_spp = $this->input->post('tgl_spp');
        $tgl_spm = $this->input->post('tgl_spm');
        $tgl_sp2d = $this->input->post('tgl_sp2d');
        $bulan = $this->input->post('bulan');
        $spd = $this->input->post('cspd');
        $bank = $this->input->post('bank');
        $nmrekan = $this->input->post('rekanan');
        $no_rek = $this->input->post('rekening');
        $npwp = $this->input->post('npwp');
        $nm_skpd = $this->input->post('nmskpd');
        $nilai = $this->input->post('nilai');
        $dir = $this->input->post('dir');
        $usernm = $this->session->userdata('pcNama');
        $sp2d_blk = $this->input->post('sp2d_blk');
        //date_default_timezone_set('Asia/Bangkok');
        $last_update =  "";
        $no_sementara = $this->input->post('no_sp2d');
        $lcstatus_input = $this->input->post('lcstatus_input');
        $no_sp2d_tag = $this->input->post('no_sp2d_tag');
        $nomor_urut = '';
        $real_no_sp2d = '7777';
        $lc_save = '2';

        if ($lcstatus_input == 'tambah') {
            $query2 = "insert into trhsp2d(no_sp2d,tgl_sp2d,no_spm,tgl_spm,no_spp,kd_skpd,nm_skpd,tgl_spp,bulan,no_spd,keperluan,username,last_update,status,jns_spp,bank,nmrekan,no_rek,npwp,nilai,status_terima,jenis_beban,no_urut) 
                    values('$no_sementara','$tgl_sp2d','$no_spm','$tgl_spm','$no_spp','$kd_skpd','$nm_skpd','$tgl_spp','$bulan','$spd','$keperluan','$usernm','$last_update','0','$jns_spp','$bank','$nmrekan','$no_rek','$npwp','$nilai','0','$jns_beban','$nomormurni') ";
            $sqlnomor = "update nomor set nosp2d='$nomormurni'";
        } else {

            $query2 = " UPDATE trhsp2d SET no_sp2d='$no_sp2d_tag',tgl_sp2d='$tgl_sp2d', no_spm='$no_spm', tgl_spm='$tgl_spm', bulan='$bulan', kd_skpd='$kd_skpd',
             nm_skpd='$nm_skpd', no_spp='$no_spp', tgl_spp='$tgl_spp', no_spd='$spd', username='$usernm', status='0', last_update='$last_update',
             keperluan='$keperluan', jns_spp='$jns_spp', no_rek='$no_rek', bank='$bank',nmrekan='$nmrekan',npwp='$npwp',nilai='$nilai' where no_sp2d='$no_sp2d_tag' ";
        }
        $asg2 = $this->db->query($query2);
        $asg = $this->db->query($sqlnomor);

        $query1 = "update trhspm set status='1' where no_spm='$no_spm'";
        $asg3 = $this->db->query($query1);

        $dataspm = array(
            'bank' => $bank,
            'nmrekan' => $nmrekan,
            'no_rek' => $no_rek,
            'npwp' => $npwp
        );
        $this->db->where('no_spm', $no_spm);
        $this->db->update('trhspm', $dataspm);

        $dataspp = array(
            'bank' => $bank,
            'nmrekan' => $nmrekan,
            'no_rek' => $no_rek,
            'npwp' => $npwp
        );
        $this->db->where('no_spp', $no_spp);
        $this->db->update('trhspp', $dataspp);




        if ($asg3) {
            $lc_save = '1';
        } else {
            $lc_save = '2';
        }

        if ($lc_save == '1') {
            echo json_encode($real_no_sp2d);
        } else {
            echo '2';
        }
    }



    function simpan_sp2dup()
    {
        // $nomormurni = $this->input->post('nomormurni');
        $no_spp = $this->input->post('no_spp');
        $no_spm = $this->input->post('no_spm');
        $no_sp2d = $this->input->post('no_sp2d');
        $kd_skpd = $this->input->post('cskpd');
        $keperluan = $this->input->post('keperluan');
        $jns_spp = $this->input->post('jns_spp');
        $jns_beban = $this->input->post('jenis');
        $tgl_spp = $this->input->post('tgl_spp');
        $tgl_spm = $this->input->post('tgl_spm');
        $tgl_sp2d = $this->input->post('tgl_sp2d');
        $bulan = $this->input->post('bulan');
        $spd = $this->input->post('cspd');
        $bank = $this->input->post('bank');
        $nmrekan = $this->input->post('rekanan');
        $no_rek = $this->input->post('rekening');
        $npwp = $this->input->post('npwp');
        $nm_skpd = $this->input->post('nmskpd');
        $nilai = $this->input->post('nilai');
        $dir = $this->input->post('dir');
        $usernm = $this->session->userdata('pcNama');
        $sp2d_blk = $this->input->post('sp2d_blk');
        //date_default_timezone_set('Asia/Bangkok');
        $last_update =  "";
        $no_sementara = "";
        $nomormurni = "";
        $lcstatus_input = $this->input->post('lcstatus_input');
        $no_sp2d_tag = $this->input->post('no_sp2d_tag');
        $nomor_urut = '';
        $real_no_sp2d = '7777';
        $lc_save = '2';
        $tahun   = $this->session->userdata('pcThang');

        $hasil = $this->db->query("SELECT MAX
        (
        CAST ( ltrim( rtrim( nomor )) AS INT )) as nomorsp2d 
    FROM
        (
        SELECT
        CASE
        
            WHEN
                jns_spp = 1 THEN
                    replace( nomor, '/UP', '' ) 
                    WHEN jns_spp = 2 THEN
                    replace( nomor, '/GU', '' ) 
                    WHEN jns_spp = 3 THEN
                    replace( nomor, '/TU', '' ) 
                    WHEN jns_spp IN ('5', '6' ) THEN
                    replace( nomor, '/LS', '' ) 
                    WHEN jns_spp IN ( '4') THEN
                    replace( nomor, '/LS-GJ', '' )
                    ELSE replace( nomor, '/GU NIHIL', '' ) 
                END AS nomor 
            FROM
            ( SELECT REPLACE( no_sp2d, '/'+'$tahun', '' ) nomor, jns_spp FROM trhsp2d ) okeii 
        ) oke");
        foreach ($hasil->result_array() as $row) {
            $nomormurni = $row['nomorsp2d'] + 1;
            $urut = str_pad($nomormurni, 6, "0", STR_PAD_LEFT);
            // $urut ='000201';
            $no_sementara = $urut . '/UP/2023';
        }
        //                      echo $real_no_sp2d;

        /* $sql = "delete from trhsp2d where kd_skpd='$kd_skpd' and no_sp2d='$no_sp2d'";
           $asg = $this->db->query($sql);    */
        if ($lcstatus_input == 'tambah') {
            $query2 = "insert into trhsp2d(no_sp2d,tgl_sp2d,no_spm,tgl_spm,no_spp,kd_skpd,nm_skpd,tgl_spp,bulan,no_spd,keperluan,username,last_update,status,jns_spp,bank,nmrekan,no_rek,npwp,nilai,status_terima,jenis_beban) 
                    values('$no_sementara','$tgl_sp2d','$no_spm','$tgl_spm','$no_spp','$kd_skpd','$nm_skpd','$tgl_spp','$bulan','$spd','$keperluan','$usernm','$last_update','0','$jns_spp','$bank','$nmrekan','$no_rek','$npwp','$nilai','0','$jns_beban') ";
            $sqlnomor = "update nomor set nosp2d='$urut'";
        } else {

            $query2 = " UPDATE trhsp2d SET no_sp2d='$no_sp2d',tgl_sp2d='$tgl_sp2d', no_spm='$no_spm', tgl_spm='$tgl_spm', bulan='$bulan', kd_skpd='$kd_skpd',
             nm_skpd='$nm_skpd', no_spp='$no_spp', tgl_spp='$tgl_spp', no_spd='$spd', username='$usernm', status='0', last_update='$last_update',
             keperluan='$keperluan', jns_spp='$jns_spp', no_rek='$no_rek', bank='$bank',nmrekan='$nmrekan',npwp='$npwp',nilai='$nilai' where no_sp2d='$no_sp2d_tag' ";

            //  $query2 ="insert into trhsp2d(no_sp2d,tgl_sp2d,no_spm,tgl_spm,no_spp,kd_skpd,nm_skpd,tgl_spp,bulan,no_spd,keperluan,username,last_update,status,jns_spp,bank,nmrekan,no_rek,npwp,nilai,status_terima,jenis_beban) 
            //                values('$no_sementara','$tgl_sp2d','$no_spm','$tgl_spm','$no_spp','$kd_skpd','$nm_skpd','$tgl_spp','$bulan','$spd','$keperluan','$usernm','$last_update','0','$jns_spp','$bank','$nmrekan','$no_rek','$npwp','$nilai','0','$jns_beban') ";    
        }
        $asg2 = $this->db->query($query2);
        $asg = $this->db->query($sqlnomor);

        $query1 = "update trhspm set status='1' where no_spm='$no_spm'";
        $asg3 = $this->db->query($query1);
        if ($asg3) {
            $lc_save = '1';
        } else {
            $lc_save = '2';
        }

        // untuk sementara sp2d manual        
        //get nomor sp2d by tox
        //if ($jns_spp<>'4'){
        // if ($lcstatus_input=='tambah'){
        //     // $sql = "insert into nomor_sp2d (no_sp2d) values ('$no_sementara')";
        //     // $asg = $this->db->query($sql);
        //     // if($asg){
        //     //     $lc_save='1';
        //     // }else{
        //     //     $lc_save='2';
        //     // }


        // $hasil=$this->db->query(" select nomor FROM  nomor_sp2d where no_sp2d='$no_sementara' ");
        // foreach ($hasil->result_array() as $row){
        // $nomor_urut=$row['nomor'];  
        // }
        // $real_no_sp2d =strval($nomor_urut).$sp2d_blk;

        //     $sql = "update nomor_sp2d set no_sp2d='$real_no_sp2d' where no_sp2d='$no_sementara'";
        //     $asg = $this->db->query($sql);
        //     if($asg){
        //        // echo $real_no_sp2d;
        //        $lc_save='1';
        //     }else{
        //         echo '2';                
        //     }

        // $sql = "update trhsp2d set no_sp2d='$real_no_sp2d' where no_sp2d='$no_sementara'";
        //     $asg = $this->db->query($sql);
        //     if($asg){
        $lc_save = '1';
        //     }else{
        //         $lc_save='2';
        //     }
        // }
        //  else{
        // // sp2d ini sementara manual dulu by tox

        // $hasil=$this->db->query(" select nomor FROM  nomor_sp2d where no_sp2d='$no_sp2d_tag' ");
        // foreach ($hasil->result_array() as $row){
        // $nomor_urut=$row['nomor'];  
        // }
        // $real_no_sp2d =strval($nomor_urut).$sp2d_blk;

        //     $sql = "update nomor_sp2d set no_sp2d='$real_no_sp2d' where no_sp2d='$no_sp2d_tag'";
        //     $asg = $this->db->query($sql);
        //     if($asg){
        //         $lc_save='1';
        //     }else{
        //         $lc_save='2';
        //     }     

        // $sql = "update trhsp2d set no_sp2d='$real_no_sp2d' where no_sp2d='$no_sp2d_tag'";
        //     $asg = $this->db->query($sql);
        //     if($asg){

        //     $lc_save='1';
        //     }else{
        //         $lc_save='2';
        //     }           

        //     }
        //}

        if ($lc_save == '1') {
            echo json_encode($real_no_sp2d);
        } else {
            echo '2';
        }
    }

    function simpan_sp2d_gj()
    {
        $nomormurni = $this->input->post('nomormurni');
        $no_spp = $this->input->post('no_spp');
        $no_spm = $this->input->post('no_spm');
        //$no_sp2d = $this->input->post('no_sp2d');
        $kd_skpd = $this->input->post('cskpd');
        $keperluan = $this->input->post('keperluan');
        $jns_spp = $this->input->post('jns_spp');
        $jns_beban = $this->input->post('jenis');
        $tgl_spp = $this->input->post('tgl_spp');
        $tgl_spm = $this->input->post('tgl_spm');
        $tgl_sp2d = $this->input->post('tgl_sp2d');
        $bulan = $this->input->post('bulan');
        $spd = $this->input->post('cspd');
        $bank = $this->input->post('bank');
        $nmrekan = $this->input->post('rekanan');
        $no_rek = $this->input->post('rekening');
        $npwp = $this->input->post('npwp');
        $nm_skpd = $this->input->post('nmskpd');
        $nilai = $this->input->post('nilai');
        $dir = $this->input->post('dir');
        $usernm = $this->session->userdata('pcNama');
        $sp2d_blk = $this->input->post('sp2d_blk');
        //date_default_timezone_set('Asia/Bangkok');
        $last_update =  "";
        $no_sementara = $this->input->post('no_sp2d');
        $lcstatus_input = $this->input->post('lcstatus_input');
        $no_sp2d_tag = $this->input->post('no_sp2d_tag');
        $nomor_urut = '';
        $real_no_sp2d = '7777';
        $lc_save = '2';

        $dataspm = array(
            'bank' => $bank,
            'nmrekan' => $nmrekan,
            'no_rek' => $no_rek,
            'npwp' => $npwp
        );
        $this->db->where('no_spm', $no_spm);
        $this->db->update('trhspm', $dataspm);

        $dataspp = array(
            'bank' => $bank,
            'nmrekan' => $nmrekan,
            'no_rek' => $no_rek,
            'npwp' => $npwp
        );
        $this->db->where('no_spp', $no_spp);
        $this->db->update('trhspp', $dataspp);
        //                      echo $real_no_sp2d;

        /* $sql = "delete from trhsp2d where kd_skpd='$kd_skpd' and no_sp2d='$no_sp2d'";
           $asg = $this->db->query($sql);    */
        if ($lcstatus_input == 'tambah') {
            $query2 = "insert into trhsp2d(no_sp2d,tgl_sp2d,no_spm,tgl_spm,no_spp,kd_skpd,nm_skpd,tgl_spp,bulan,no_spd,keperluan,username,last_update,status,jns_spp,bank,nmrekan,no_rek,npwp,nilai,status_terima,jenis_beban,no_urut) 
                    values('$no_sementara','$tgl_sp2d','$no_spm','$tgl_spm','$no_spp','$kd_skpd','$nm_skpd','$tgl_spp','$bulan','$spd','$keperluan','$usernm','$last_update','0','$jns_spp','$bank','$nmrekan','$no_rek','$npwp','$nilai','0','$jns_beban','$nomormurni') ";
            $sqlnomor = "update nomor set nosp2d='$nomormurni'";
        } else {

            $query2 = " UPDATE trhsp2d SET no_sp2d='$no_sp2d_tag',tgl_sp2d='$tgl_sp2d', no_spm='$no_spm', tgl_spm='$tgl_spm', bulan='$bulan', kd_skpd='$kd_skpd',
             nm_skpd='$nm_skpd', no_spp='$no_spp', tgl_spp='$tgl_spp', no_spd='$spd', username='$usernm', status='0', last_update='$last_update',
             keperluan='$keperluan', jns_spp='$jns_spp', no_rek='$no_rek', bank='$bank',nmrekan='$nmrekan',npwp='$npwp',nilai='$nilai' where no_sp2d='$no_sp2d_tag' ";
        }
        $asg2 = $this->db->query($query2);
        $asg = $this->db->query($sqlnomor);

        $query1 = "update trhspm set status='1' where no_spm='$no_spm'";
        $asg3 = $this->db->query($query1);
        if ($asg3) {
            $lc_save = '1';
        } else {
            $lc_save = '2';
        }


        // untuk sementara sp2d manual        
        //get nomor sp2d by tox
        if ($jns_spp <> '4') {
            if ($lcstatus_input == 'tambah') {
                $sql = "insert into nomor_sp2d (no_sp2d) values ('$no_sementara')";
                $asg = $this->db->query($sql);
                if ($asg) {
                    $lc_save = '1';
                } else {
                    $lc_save = '2';
                }


                $hasil = $this->db->query(" select nomor FROM  nomor_sp2d where no_sp2d='$no_sementara' ");
                foreach ($hasil->result_array() as $row) {
                    $nomor_urut = $row['nomor'];
                }
                $real_no_sp2d = strval($nomor_urut) . $sp2d_blk;

                $sql = "update nomor_sp2d set no_sp2d='$real_no_sp2d' where no_sp2d='$no_sementara'";
                $asg = $this->db->query($sql);
                if ($asg) {
                    // echo $real_no_sp2d;
                    $lc_save = '1';
                } else {
                    echo '2';
                }

                $sql = "update trhsp2d set no_sp2d='$real_no_sp2d' where no_sp2d='$no_sementara'";
                $asg = $this->db->query($sql);
                if ($asg) {
                    $lc_save = '1';
                } else {
                    $lc_save = '2';
                }
            } else {
                // sp2d ini sementara manual dulu by tox

                $hasil = $this->db->query(" select nomor FROM  nomor_sp2d where no_sp2d='$no_sp2d_tag' ");
                foreach ($hasil->result_array() as $row) {
                    $nomor_urut = $row['nomor'];
                }
                $real_no_sp2d = strval($nomor_urut) . $sp2d_blk;

                $sql = "update nomor_sp2d set no_sp2d='$real_no_sp2d' where no_sp2d='$no_sp2d_tag'";
                $asg = $this->db->query($sql);
                if ($asg) {
                    $lc_save = '1';
                } else {
                    $lc_save = '2';
                }

                $sql = "update trhsp2d set no_sp2d='$real_no_sp2d' where no_sp2d='$no_sp2d_tag'";
                $asg = $this->db->query($sql);
                if ($asg) {

                    $lc_save = '1';
                } else {
                    $lc_save = '2';
                }
            }
        }

        if ($lc_save == '1') {
            echo json_encode($real_no_sp2d);
        } else {
            echo '2';
        }
        //   echo '1';
        //$asg->free_result();
        //          $query1->free_result();  
    }

    function batal_sp2d()
    {
        $sp2d = $this->input->post('sp2d');
        $spp = $this->input->post('no');
        $ket = $this->input->post('ket');
        $usernm      = $this->session->userdata('pcNama');
        $last_update =  date('d-m-y H:i:s');

        $query = $this->db->query("update trhspp set sp2d_batal='1',ket_batal='$ket',user_batal='$usernm', tgl_batal='$last_update' where no_spp='$spp'");
        $query = $this->db->query("update trhsp2d set sp2d_batal='1' where no_sp2d='$sp2d'");
        //$query->free_result();

    }

    function load_sum_spm()
    {

        $spp = $this->input->post('spp');
        //$id=str_replace('123456789','/',$spp);
        $query1 = $this->db->query("select sum(nilai) as rekspm from trdspp where no_spp='$spp'");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            //number_format($resulte['rekspm'],"2",",","."),
            $result[] = array(
                'id' => $ii,
                'rekspm' => number_format($resulte['rekspm'], 2, '.', ','),
                'rekspm1' => $resulte['rekspm']
            );
            $ii++;
        }

        //return $result;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_sum_pot()
    {

        $spm = $this->input->post('spm');
        //$id=str_replace('123456789','/',$spp);
        $query1 = $this->db->query("select sum(nilai) as rektotal from trspmpot where no_spm='$spm'");
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $result[] = array(
                'id' => $ii,
                'rektotal' => number_format($resulte['rektotal']),
                'rektotal1' => $resulte['rektotal']
            );
            $ii++;
        }

        //return $result;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_sp2d()
    {

        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = " kd_skpd<>'' ";
        if ($kriteria <> '') {
            $where = " (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%') or upper(no_spp) like upper('%$kriteria%') or upper(no_spm) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as tot from (select a.* from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();


        $sql = "SELECT TOP $rows *
        from (select a.* , (case when c.jns_beban='5' then 'Belanja' else 'Pembiayaan' end)jns_spd
        from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where $where and no_sp2d not in 
        (SELECT TOP $offset no_sp2d from (select a.* from trhsp2d a inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd inner join trhspd c on a.no_spd=c.no_spd) z where $where) order by no_sp2d,kd_skpd";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            if ($resulte['status_terima'] == '1') {
                $s = 'Sudah Diterima';
            } else {
                $s = 'Belum Diterima';
            }

            if ($resulte['status_bud'] == '1') {
                $s_bud = 'Sudah Cair';
            } else {
                $s_bud = 'Belum Cair';
            }

            $row[] = array(
                'id' => $ii,
                'no_sp2d' => $resulte['no_sp2d'],
                'tgl_sp2d' => $resulte['tgl_sp2d'],
                'no_spm' => $resulte['no_spm'],
                'tgl_spm' => $resulte['tgl_spm'],
                'no_spp' => $resulte['no_spp'],
                'tgl_spp' => $resulte['tgl_spp'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd'],
                'jns_spp' => $resulte['jns_spp'],
                'keperluan' => $resulte['keperluan'],
                'bulan' => $resulte['bulan'],
                'no_spd' => $resulte['no_spd'],
                'bank' => $resulte['bank'],
                'nmrekan' => $resulte['nmrekan'],
                'no_rek' => $resulte['no_rek'],
                'npwp' => $resulte['npwp'],
                'sp2d_batal' => $resulte['sp2d_batal'],
                'jenis_beban' => $resulte['jenis_beban'],
                'status' => $s,
                'status_bud' => $s_bud,
                'nokasbud' => $resulte['no_kas_bud'],
                'dkasbud' => $resulte['tgl_kas_bud'],
                'jns_spd' => $resulte['jns_spd']
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function cetak_sp2d()
    {
        $client = $this->ClientModel->clientData('1');
        $lntahunang = $this->session->userdata('pcThang');
        $lcnosp2d = str_replace('123456789', '/', $this->uri->segment(3));
        $lcttd = str_replace('abc', ' ', $this->uri->segment(5));
        $banyak = $this->uri->segment(6);
        $jns_cetak = $this->uri->segment(7);
        $a = '*' . $lcnosp2d . '*';
        $csql = "SELECT a.*,
                (SELECT nmrekan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS nmrekan1,
                (SELECT pimpinan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS pimpinan,
                (SELECT alamat FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS alamat
                 FROM trhsp2d a WHERE a.no_sp2d = '$lcnosp2d'";
        $hasil = $this->db->query($csql);
        $trh = $hasil->row();
        $lckd_skpd  = $trh->kd_skpd;
        $data1 = $this->cek_anggaran_model->cek_anggaran($lckd_skpd);
        $lcnospm    = $trh->no_spm;
        $ldtglspm   = $trh->tgl_spm;
        $lcnmskpd   = $trh->nm_skpd;
        $lckdskpd   = $trh->kd_skpd;
        $alamat     = $trh->alamat;
        $lcnpwp     = $trh->npwp;
        $rekbank    = $trh->no_rek;
        $lcperlu    = $trh->keperluan;
        $lcnospp    = $trh->no_spp;
        $tgl        = $trh->tgl_sp2d;
        $pimpinan   = $trh->pimpinan;
        $nmrekan    = $trh->nmrekan;
        $jns_bbn    = $trh->jenis_beban;
        $jns        = $trh->jns_spp;
        $bank       = $trh->bank;
        $banyak_kar = strlen($lcperlu);
        $tanggal    = $this->tanggal_format_indonesia($tgl);
        //$banyak = $banyak_kar > 400 ? 14 :23;          
        // echo $jns;  
        // echo "<br>";
        // echo $jns_bbn;        

        $csqlnilai = "SELECT sum(nilai) [nilai] from  trdspp WHERE no_spp = '$lcnospp'";
        $hasiln = $this->db->query($csqlnilai);
        $trhn = $hasiln->row();
        $n          = $trhn->nilai;

        $sqlrek = "SELECT bank,rekening, npwp FROM ms_skpd WHERE kd_skpd = '$lckd_skpd' ";
        $sqlrek = $this->db->query($sqlrek);
        foreach ($sqlrek->result() as $rowrek) {
            $bank_ben = $rowrek->bank;
            $rekben = $rowrek->rekening;
            $npwp_ben = $rowrek->npwp;
        }
        $rek_ben = empty($rekben) || $rekben == 0 ? '' : $rekben;
        $npwp_ben = empty($npwp_ben) || $npwp_ben == 0 ? '' : $npwp_ben;
        $nama_bank = empty($bank) ? 'Belum Pilih Bank' : $this->rka_model->get_nama($bank, 'nama', 'ms_bank', 'kode');
        $nama_bank_ben = empty($bank_ben) ? 'Belum Pilih Bank' : $this->rka_model->get_nama($bank_ben, 'nama', 'ms_bank', 'kode');
        //$nama_bank_ben = $this->rka_model->get_nama(12,'nama','ms_bank','kode');      
        $sqlttd1 = $this->db->query("SELECT nama as nm,nip as nip,jabatan as jab,pangkat,LTRIM(RTRIM(kode)) as kode FROM ms_ttd where nip='$lcttd' AND kode in ('BUD', 'PA')")->row();
        $nip = $sqlttd1->nip;
        $nama = $sqlttd1->nm;
        $jabatan  = $sqlttd1->jab;
        $pangkat = $sqlttd1->pangkat;
        $kode = $sqlttd1->kode;
        $bidang = $sqlttd1->bidang;

        // echo $jabatan;die();

        if ($kode == 'BUD') {
            $bud = "Kuasa Bendahara Umum Daerah";
        } else if ($kode == 'PA') {
            $bud = "Bendahara Umum Daerah";
        } else {
            $bud = $jabatan;
        }
        $sqlnam = "SELECT TOP 1 * FROM ms_ttd WHERE kd_skpd = '$lckdskpd' AND kode='BK' ";
        $sqlnam = $this->db->query($sqlnam);
        foreach ($sqlnam->result() as $rownam) {
            $nama_ben = $rownam->nama;
            $jabat_ben = $rownam->jabatan;
        }
        $nama_ben = empty($nama_ben) ? 'Belum Ada data Bendahara' : $nama_ben;
        $jabat_ben = empty($jabat_ben) ? ' ' : $jabat_ben;

        if (($jns == '1') or ($jns == '2')  or ($jns == '4') or ($jns == '5')) {
            $kd_kegi = '';
            $nm_kegi = '';
            $kd_prog = '';
            $nm_prog = '';
        } else {
            $sql12 = "SELECT kd_sub_kegiatan FROM trdspp a INNER JOIN trhsp2d b ON a.no_spp = b.no_spp AND a.kd_skpd=b.kd_skpd 
                WHERE b.kd_skpd = '$lckdskpd' AND no_sp2d='$lcnosp2d' group by kd_sub_kegiatan ";
            $sqlrek12 = $this->db->query($sql12);
            foreach ($sqlrek12->result() as $rowrek) {
                $kd_kegi = $rowrek->kd_sub_kegiatan;
            }
            $nm_kegi = " - " . $this->rka_model->get_nama($kd_kegi, 'nm_sub_kegiatan', 'trskpd', 'kd_sub_kegiatan');
            $kd_prog = $this->left($kd_kegi, 7);
            $nm_prog = " - " . $this->rka_model->get_nama($kd_prog, 'nm_program', 'trskpd', 'kd_program');
        }
        if ($jns_cetak == '2') {
            $tinggi = '150px';
            //$banyak=9;
            $banyak = 10;
        } else 
        if ($jns_cetak == '1') {
            //$tinggi='80px';
            $tinggi = '10px';
            //$banyak=15;
            $banyak = $this->uri->segment(6);
        } else {
            $tinggi = '10px';
            $banyak = $banyak;
        }

        $cRet = '';

        $cRet .= "
        <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px;\"  width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
        $cRet .= "
        <tr>
            <td align=\"center\" width=\"50%\" style=\"border-collapse:collapse;font-weight:bold; font-size:12px\">" . $client->pem . " " . $client->nm_kab . "</td>
            <td align=\"center\" width=\"50%\">
                <table style=\"border-collapse:collapse;font-size:12px; font-weight: bold;\" width=\"100%\" align=\"center\" cellspacing=\"4\" cellpadding=\"0\">
                    <tr>
                        <td align=\"center\">
                            SURAT PERINTAH PENCAIRAN DANA (SP2D)
                        </td>
                    </tr>
                    <tr>
                        <td align=\"center\">
                            <b>Nomor : $lcnosp2d</b>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>   
        <tr>
            <td style=\"border-left:solid 1px black;\" >
                <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:11px\" width=\"100%\" align=\"center\" valign=\"top\" border=\"1\" cellspacing=\"4\" cellpadding=\"0\">
                    <tr>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" width=\"18%\" align=\"left\" valign=\"top\">&nbsp;Nomor SPM</td>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" width=\"2%\" valign=\"top\">:</td>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" width=\"80%\" valign=\"top\">$lcnospm</td>
                    </tr>
                    <tr>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" width=\"18%\" valign=\"top\">&nbsp;Tanggal</td>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" width=\"2%\" valign=\"top\" >:</td>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" width=\"80%\" valign=\"top\">" . $this->tanggal_format_indonesia($ldtglspm) . "</td>
                    </tr>
                    <tr>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" width=\"18%\" valign=\"top\">&nbsp;Nama SKPD</td>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" width=\"2%\" valign=\"top\">:</td>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" width=\"80%\" valign=\"top\">$lckd_skpd $lcnmskpd</td>
                    </tr>
                </table>
            </td>
            <td>
                <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:11px\" width=\"100%\"  valign=\"top\" border=\"0\" cellspacing=\"4\" cellpadding=\"0\">
                    <tr>
                        <td valign=\"top\" width=\"25%\">Dari</td>
                        <td valign=\"top\" width=\"5%\">:</td>
                        <td valign=\"top\" width=\"70%\">Kuasa BUD</td>
                    </tr>
                    <tr>
                        <td valign=\"top\" width=\"25%\">Tahun Anggaran</td>
                        <td valign=\"top\" width=\"5%\">:</td>
                        <td valign=\"top\" width=\"70%\">$lntahunang</td>
                    </tr>
                </table>
            </td>
        </tr>
            <tr>
        <td colspan=\"2\">
            <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12x\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"4\" cellpadding=\"0\">
            <tr>
                <td style=\"border-bottom: hidden; border-right: hidden;\" width=\"120px\">&nbsp;Bank Pengirim</td>
                <td style=\"border-bottom: hidden;\" >:&nbsp;Bank Kalbar Cabang Melawi</td>
            </tr>
            <tr>
                <td style=\"border-bottom: hidden;\" colspan=\"2\" >&nbsp;Hendaklah mencairkan / memindahbukukan dari bank Rekening Nomor 4501002886</td>
            </tr>
            <tr>
                <td style=\"border-bottom: hidden; border-right: hidden;\" >&nbsp;Uang sebesar Rp</td>
                <td style=\"border-bottom: hidden;\" >:&nbsp;Rp" . number_format($n, '2', ',', '.') . "  (" . $this->tukd_model->terbilang($n) . ") </td>
            </tr>
            </table>
        </td>
        </tr>   
        <tr>
            <td colspan=\"2\">";


        // $field = $this->support->auto_cek_status_skpd($lckd_skpd);

        // if ($field == 'ubah') {
        //     $kol = 'nilai_ubah';
        // } else if ($field == 'geser') {
        //     $kol = 'nilai_sempurna';
        // } else {
        //     $kol = 'nilai';
        // }

        if ($jns == 2) {
            $sql_pagu = "SELECT sum(nilai)total FROM trdrka where jns_ang='$data1' and kd_sub_kegiatan in (select a.kd_sub_kegiatan from trdspp a 
inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd  
where a.no_spp='$lcnospp' AND left(a.kd_skpd,22)=left('$lckd_skpd',22)and b.jns_spp not in ('1','2')) AND left(kd_skpd,22)=left('$lckd_skpd',22)";
        } else {
            $sql_pagu = "SELECT sum(nilai)total FROM trdrka where jns_ang='$data1' and  kd_sub_kegiatan in (select a.kd_sub_kegiatan from trdspp a 
inner join trhspp b on a.no_spp=b.no_spp and a.kd_skpd=b.kd_skpd  
where a.no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd' and b.jns_spp not in ('1','2')) AND kd_skpd='$lckd_skpd'";
        }
        $sql_pagus = $this->db->query($sql_pagu);
        foreach ($sql_pagus->result() as $row_pagu) {
            $pagu_ang = $row_pagu->total;
        }




        $cRet .= "<table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"-1\" cellpadding=\"-1\">
               <tr>
                    <td valign=\"top\" width=\"120px\">&nbsp;Kepada</td>
                    <td valign=\"top\" width=\"10px\" >:</td>
                    <td valign=\"top\" >$nmrekan</td>
                </tr>
                <tr>
                    <td valign=\"top\" >&nbsp;NPWP</td>
                    <td valign=\"top\" >:</td>
                    <td valign=\"top\" >$lcnpwp</td>
                </tr>
                <tr>
                    <td valign=\"top\" >&nbsp;No.Rekening Bank</td>
                    <td valign=\"top\" >:</td>
                    <td valign=\"top\" >$rekbank</td>
                </tr>
                <tr>
                    <td valign=\"top\">&nbsp;Bank Penerima</td>
                    <td valign=\"top\">:</td>
                    <td valign=\"top\">$nama_bank</td>
                </tr>
                <tr>
                    <td valign=\"top\" >&nbsp;Keperluan Untuk</td>
                    <td valign=\"top\" >:</td>
                    <td height=\"$tinggi\" valign=\"top\" style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" >$lcperlu 
                    </td>

                  

                </tr>
                <tr>
                    <td valign=\"top\" colspan=\"3\">&nbsp;</td>
                </tr>";
        if ($jns == '1' || $jns == '2' || $jns == '7') {
            $cRet .= "<tr>
                    <td valign=\"top\" >&nbsp;Pagu Anggaran</td>
                    <td valign=\"top\" >:</td>
                    <td height=\"$tinggi\" valign=\"top\" style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" >-
                    </td>
                </tr>
                </table> ";
        } else {
            $cRet .= "<tr>
                    <td valign=\"top\" >&nbsp;Pagu Anggaran</td>
                    <td valign=\"top\" >:</td>
                    <td height=\"$tinggi\" valign=\"top\" style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" >Rp" . number_format($pagu_ang, "2", ",", ".") . "
                    </td>
                </tr>
                </table> ";
        }


        // if(($jns==6) && ($jns_bbn==3)){

        //      $cRet .="<table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"-1\" cellpadding=\"-1\">
        //        <tr>
        //             <td valign=\"top\" width=\"120px\">&nbsp;Kepada</td>
        //             <td valign=\"top\" width=\"10px\" >:</td>
        //             <td valign=\"top\" >$nmrekan</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;NPWP</td>
        //             <td valign=\"top\" >:</td>
        //             <td valign=\"top\" >$lcnpwp</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;No.Rekening Bank</td>
        //             <td valign=\"top\" >:</td>
        //             <td valign=\"top\" >$rekbank</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\">&nbsp;Bank Penerima</td>
        //             <td valign=\"top\">:</td>
        //             <td valign=\"top\">$nama_bank</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;Keperluan Untuk</td>
        //             <td valign=\"top\" >:</td>
        //             <td height=\"$tinggi\" valign=\"top\" style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" >$lcperlu
        //             <br>".$this->right($kd_prog,2)."$nm_prog
        //             <br>".$this->right($kd_kegi,2)."$nm_kegi
        //             </td>

        //         </tr>
        //         <tr>
        //             <td valign=\"top\" colspan=\"3\">&nbsp;</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;Pagu Anggaran</td>
        //             <td valign=\"top\" >:</td>
        //             <td height=\"$tinggi\" valign=\"top\" style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" >Rp".number_format($pagu_ang,"2",",",".")."
        //             </td>
        //         </tr>
        //         </table> ";
        // }
        //  if($jns==5 ){

        //      $cRet .="<table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"-1\" cellpadding=\"-1\">
        //        <tr>
        //             <td valign=\"top\" width=\"120px\">&nbsp;Kepada</td>
        //             <td valign=\"top\" width=\"10px\" >:</td>
        //             <td valign=\"top\" >$nmrekan, $alamat</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;NPWP</td>
        //             <td valign=\"top\" >:</td>
        //             <td valign=\"top\" >$lcnpwp</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;No.Rekening Bank</td>
        //             <td valign=\"top\" >:</td>
        //             <td valign=\"top\" >$rekbank</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\">&nbsp;Bank Penerima</td>
        //             <td valign=\"top\">:</td>
        //             <td valign=\"top\">$nama_bank</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;Keperluan Untuk</td>
        //             <td valign=\"top\" >:</td>
        //             <td height=\"$tinggi\" valign=\"top\" style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" >$lcperlu
        //             <br>".$this->right($kd_prog,2)."$nm_prog
        //             <br>".$this->right($kd_kegi,2)."$nm_kegi
        //             </td>

        //         </tr>
        //         <tr>
        //             <td valign=\"top\" colspan=\"3\">&nbsp;</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;Pagu Anggaran</td>
        //             <td valign=\"top\" >:</td>
        //             <td height=\"$tinggi\" valign=\"top\" style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" >Rp".number_format($pagu_ang,"2",",",".")."
        //             </td>
        //         </tr>
        //         </table> ";
        // }else if ($jns=='1'){
        //     $cRet .="<table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"-1\" cellpadding=\"-1\">
        //        <tr>
        //             <td valign=\"top\" width=\"120px\">&nbsp;Kepada </td>
        //             <td valign=\"top\" width=\"10px\">:&nbsp;</td>
        //             <td valign=\"top\" font-family: Arial; >$nama_ben - $jabat_ben</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;NPWP</td>
        //             <td valign=\"top\" >:</td>
        //             <td valign=\"top\" >$lcnpwp</td>

        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;No.Rekening Bank</td>
        //             <td valign=\"top\" >:</td>
        //             <td valign=\"top\" >$rekbank</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;Bank Penerima</td>
        //             <td valign=\"top\" >:</td>
        //             <td valign=\"top\" >$nama_bank</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;Keperluan Untuk</td>
        //             <td valign=\"top\" >:</td>
        //             <td height=\"$tinggi\" valign=\"top\" style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" >$lcperlu
        //             <br>".$this->right($kd_prog,2)."$nm_prog
        //             <br>".$this->right($kd_kegi,2)."$nm_kegi
        //             </td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" colspan=\"3\">&nbsp;</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;Pagu Anggaran</td>
        //             <td valign=\"top\" >:</td>
        //             <td height=\"$tinggi\" valign=\"top\" style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" >
        //             </td>
        //         </tr>
        //         </table> ";

        // }else{
        //     $cRet .="<table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"-1\" cellpadding=\"-1\">
        //        <tr>
        //             <td valign=\"top\" width=\"120px\">&nbsp;Kepada </td>
        //             <td valign=\"top\" width=\"10px\">:&nbsp;</td>
        //             <td valign=\"top\" font-family: Arial; >$nmrekan</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;NPWP</td>
        //             <td valign=\"top\" >:</td>
        //             <td valign=\"top\" >$npwp_ben</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;No.Rekening Bank</td>
        //             <td valign=\"top\" >:</td>
        //             <td valign=\"top\" >$rek_ben</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;Bank Penerima</td>
        //             <td valign=\"top\" >:</td>
        //             <td valign=\"top\" >$nama_bank</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;Keperluan Untuk</td>
        //             <td valign=\"top\" >:</td>
        //             <td height=\"$tinggi\" valign=\"top\" style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" >$lcperlu
        //             <br>".$this->right($kd_prog,2)."$nm_prog
        //             <br>".$this->right($kd_kegi,2)."$nm_kegi
        //             </td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" colspan=\"3\">&nbsp;</td>
        //         </tr>
        //         <tr>
        //             <td valign=\"top\" >&nbsp;Pagu Anggaran</td>
        //             <td valign=\"top\" >:</td>
        //             <td height=\"$tinggi\" valign=\"top\" style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" >Rp".number_format($pagu_ang,"2",",",".")."
        //             </td>
        //         </tr>
        //         </table> ";

        // }
        $cRet  .= "  </td>
        </tr>
        <tr>
            <td colspan=\"2\">
                <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr >
                        <td width=\"5%\" align=\"center\"><b>NO</b></td>
                        <td width=\"15%\" align=\"center\"><b>KODE REKENING </b></td>
                        <td align=\"center\"><b>NAMA REKENING</b></td>
                        <td width=\"15%\" align=\"center\"><b>JUMLAH<br>(Rp)</b></td>
                    </tr>
                    <tr>
                        <td align=\"center\">1</td>
                        <td align=\"center\">2</td>
                        <td align=\"center\">3</td>
                        <td align=\"center\">4</td>
                    </tr>";
        $sql_total = "SELECT sum(nilai)total FROM trdspp where no_spp='$lcnospp' AND kd_skpd='$lckd_skpd'";
        $sql_x = $this->db->query($sql_total);
        foreach ($sql_x->result() as $row_x) {
            $lntotal = $row_x->total;
        }
        if (($jns == 1)) {
            $sql = "select SUM(nilai) nilai from trdspp where no_spp='$lcnospp' AND kd_skpd='$lckd_skpd'";
            $hasil = $this->db->query($sql);
            $lcno = 0;
            $lntotal = 0;
            foreach ($hasil->result() as $row) {
                $lcno = $lcno + 1;
                $lntotal = $lntotal + $row->nilai;
                $cRet .= "<tr>
                                                        <td style=\"border-bottom: hidden;\" align=\"center\">&nbsp;1</td>
                                                        <td style=\"border-bottom: hidden;font-size:11px\">&nbsp; 11010301002</td>
                                                        <td style=\"border-bottom: hidden;font-size:11px\">&nbsp; Uang Persediaan</td>
                                                        <td style=\"border-bottom: hidden;font-size:11px\" align=\"right\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;</td>
                                                    </tr>";
            }
            if ($lcno <= $banyak) {
                for ($i = $lcno; $i <= $banyak; $i++) {
                    $cRet .= "<tr>
                                                        <td style=\"border-top: hidden;\" align=\"center\">&nbsp;</td>
                                                        <td style=\"border-top: hidden;\" ></td>
                                                        <td style=\"border-top: hidden;\"></td>
                                                        <td style=\"border-top: hidden;\" align=\"right\"></td>
                                                    </tr>";
                }
            }
        } else if (($jns == 2) || ($jns == 7)) {
            $nama_gu = $jns == 2 ? 'Ganti Uang(GU)' : 'Ganti Uang(GU-NIHIL)';
            $sql = "select SUM(nilai) nilai from trdspp where no_spp='$lcnospp' AND kd_skpd='$lckd_skpd'";
            $hasil = $this->db->query($sql);
            $lcno = 0;
            $lntotal = 0;
            foreach ($hasil->result() as $row) {
                $lcno = $lcno + 1;
                $lntotal = $lntotal + $row->nilai;
                $cRet .= "<tr>
                                                        <td style=\"border-bottom: hidden;\" align=\"center\">&nbsp;1</td>
                                                        <td style=\"border-bottom: hidden;font-size:11px\">&nbsp; -</td>
                                                        <td style=\"border-bottom: hidden;font-size:11px\">&nbsp; $nama_gu</td>
                                                        <td style=\"border-bottom: hidden;font-size:11px\" align=\"right\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;</td>
                                                    </tr>";
            }
            if ($lcno <= $banyak) {
                for ($i = $lcno; $i <= $banyak; $i++) {
                    $cRet .= "<tr>
                                                        <td style=\"border-top: hidden;\" align=\"center\">&nbsp;</td>
                                                        <td style=\"border-top: hidden;\" ></td>
                                                        <td style=\"border-top: hidden;\"></td>
                                                        <td style=\"border-top: hidden;\" align=\"right\"></td>
                                                    </tr>";
                }
            }
        } else {
            $sql1 = "SELECT COUNT(*) as jumlah from 
                            (

                                select '1'as urut,left(a.kd_sub_kegiatan,12)as kd_sub_kegiatan,left(a.kd_sub_kegiatan,12) kd_rek,b.nm_kegiatan as nm_rek,sum(nilai)nilai from trdspp a inner join 
                                trskpd b on a.kd_sub_kegiatan=b.kd_sub_kegiatan where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd' AND b.jns_ang='$data1'
                                group by left(a.kd_sub_kegiatan,12),nm_kegiatan
                                union all
                                select '2'as urut,kd_sub_kegiatan,kd_sub_kegiatan kd_rek,a.nm_sub_kegiatan as nm_rek,sum(nilai)nilai from trdspp a where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                                group by kd_sub_kegiatan,nm_sub_kegiatan
                                union all
                                select '3'as urut,kd_sub_kegiatan,a.kd_rek6 kd_rek,b.nm_rek6 as nm_rek,sum(nilai)nilai from trdspp a inner join 
                                ms_rek6 b on a.kd_rek6=b.kd_rek6 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                                group by a.kd_rek6,b.nm_rek6,kd_sub_kegiatan
                                
                            ) tox";
            $hasil1 = $this->db->query($sql1);
            $row1 = $hasil1->row();
            $jumlahbaris = $row1->jumlah;
            if ($jumlahbaris <= $banyak) {
                $sql = "SELECT * from (

                                select '1'as urut,left(a.kd_sub_kegiatan,12)as kd_sub_kegiatan,left(a.kd_sub_kegiatan,12) kd_rek,b.nm_kegiatan as nm_rek,sum(nilai)nilai from trdspp a inner join 
                                trskpd b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd' AND b.jns_ang='$data1'
                                group by left(a.kd_sub_kegiatan,12),nm_kegiatan
                                union all
                                select '2'as urut,kd_sub_kegiatan,kd_sub_kegiatan kd_rek,a.nm_sub_kegiatan as nm_rek,sum(nilai)nilai from trdspp a where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                                group by kd_sub_kegiatan,nm_sub_kegiatan
                                union all
                                select '3'as urut,kd_sub_kegiatan,a.kd_rek6 kd_rek,b.nm_rek6 as nm_rek,sum(nilai)nilai from trdspp a inner join 
                                ms_rek6 b on a.kd_rek6=b.kd_rek6 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                                group by a.kd_rek6,b.nm_rek6,kd_sub_kegiatan
                                ) tox order by urut,kd_rek";
            } else {
                $sql = "SELECT * from (

                                select '1'as urut,left(a.kd_sub_kegiatan,12)as kd_sub_kegiatan,left(a.kd_sub_kegiatan,12) kd_rek,b.nm_kegiatan as nm_rek,sum(nilai)nilai from trdspp a inner join 
                                trskpd b on a.kd_sub_kegiatan=b.kd_sub_kegiatan where no_spp='$lcnospp' AND b.jns_ang='$data1' AND a.kd_skpd='$lckd_skpd' group by left(a.kd_sub_kegiatan,12),nm_kegiatan
                            union all
                            select '2' as urut, '' as kd_sub_kegiatan, '' kd_rek, '(Rincian Terlampir)' as nm_rek, 0 as nilai
                            ) tox order by urut,kd_rek";
            }
            $hasil = $this->db->query($sql);
            $lcno = 0;
            $lcno_baris = 0;
            // $lntotal = 0;
            foreach ($hasil->result() as $row) {
                $lcno_baris = $lcno_baris + 1;
                if (strlen($row->kd_rek) >= 12) {
                    //print_r($row);
                    //exit();
                    $lcno = $lcno + 1;
                    $lcno_x = $lcno;
                } else {
                    $lcno_x = '';
                }
                //                                           $lntotal = $lntotal + $row->nilai;
                // $panjang=strlen($row->kd_rek);
                if ($row->urut == '3') {

                    $cRet .= "<tr>
                                                        <td style=\"border-bottom: hidden;font-size:12px\" align=\"center\">&nbsp;$lcno_x</td>
                                                        <td style=\"border-bottom: hidden;font-size:12px\">&nbsp;" . $this->tukd_model->dotrek($row->kd_rek) . " </td>
                                                        <td style=\"border-bottom: hidden;font-size:12px\">&nbsp; $row->nm_rek</td>
                                                        <td style=\"border-bottom: hidden;font-size:12px\" align=\"right\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;</td>
                                                    </tr>";
                } else {

                    $cRet .= "<tr>
                                                        <td style=\"border-bottom: hidden;font-size:12px\" align=\"center\">&nbsp;<b>$lcno_x</b></td>
                                                        <td style=\"border-bottom: hidden;font-size:12px\">&nbsp;<b>" . $row->kd_rek . " </b></td>
                                                        <td style=\"border-bottom: hidden;font-size:12px\">&nbsp; <b>$row->nm_rek</b></td>
                                                        <td style=\"border-bottom: hidden;font-size:12px\" align=\"right\"><b>" . number_format($row->nilai, "2", ",", ".") . "&nbsp;</b></td>
                                                    </tr>";
                }
            }
            if ($lcno_baris <= $banyak) {
                for ($i = $lcno_baris; $i <= $banyak; $i++) {
                    $cRet .= "<tr>
                                                        <td style=\"border-top: hidden;\" align=\"center\">&nbsp;</td>
                                                        <td style=\"border-top: hidden;\" ></td>
                                                        <td style=\"border-top: hidden;\"></td>
                                                        <td style=\"border-top: hidden;\" align=\"right\"></td>
                                                    </tr>";
                }
            }
        }
        $cRet .= "<tr>
                        <td align=\"right\" colspan=\"3\">&nbsp;<b>JUMLAH&nbsp;</b></td>
                        <td align=\"right\"><b>" . number_format($lntotal, "2", ",", ".") . "</b>&nbsp;</td>
                    </tr>
                    </table>
                    <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td colspan=\"3\">&nbsp;Potongan-potongan</td>
                    </tr>
                    <tr>
                        <td  width=\"5%\" align=\"center\"><b>NO</b></td>
                        <td  width=\"65%\" align=\"center\"><b>Uraian (No.Rekening)</b></td>
                        <td  width=\"30%\" align=\"center\"><b>Jumlah(Rp)</b></td>
                    </tr>";

        $sql = "SELECT a.* from trspmpot a inner join ms_pot b on a.map_pot=b.map_pot where no_spm='$lcnospm' AND kd_skpd='$lckd_skpd' and kelompok='1'";
        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lntotalpot = 0;
        foreach ($hasil->result() as $row) {
            $lcno = $lcno + 1;
            $lntotalpot = $lntotalpot + $row->nilai;
            $cRet .= "<tr>
                                            <td width=\"5%\" align=\"center\">&nbsp;$lcno</td>
                                            <td width=\"55%\" style=\"font-size='12px'\" >&nbsp;  " . $this->tukd_model->dotrek($row->kd_rek6) . " $row->nm_rek6</td>
                                            <td width=\"30%\" align=\"right\" style=\"font-size='12px'\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;</td>
                                        </tr>";
        }

        $cRet .= "
                    <tr>
                        <td colspan =\"2\" align=\"right\"><b>Jumlah</b>&nbsp;</td>
                        <td align=\"right\"><b>" . number_format($lntotalpot, "2", ",", ".") . "</b>&nbsp;</td>
                    </tr>";
        $cRet .= "
                    
                    <tr>
                        <td colspan=\"3\">&nbsp;<b>Informasi :</b> <i>tidak mengurangi Jumlah Pembayaran SP2D</i></td>
                    </tr>
                    <tr>
                        <td  width=\"5%\" align=\"center\"><b>NO</b></td>
                        <td  width=\"65%\" align=\"center\"><b>Uraian (No.Rekening)</b></td>
                        <td  width=\"30%\" align=\"center\"><b>Jumlah(Rp)</b></td>
                    </tr>";

        $sql2 = "SELECT a.* from trspmpot a inner join ms_pot b on a.map_pot=b.map_pot where no_spm='$lcnospm' AND kd_skpd='$lckd_skpd' AND kelompok='2'";
        $hasil2 = $this->db->query($sql2);
        $lcno2 = 0;
        $lntotalpot2 = 0;
        foreach ($hasil2->result() as $row) {
            $lcno2 = $lcno2 + 1;
            $lntotalpot2 = $lntotalpot2 + $row->nilai;
            $cRet .= "<tr>
                                            <td style=\"font-size='12px'\" width=\"5%\" align=\"center\">&nbsp;$lcno2</td>
                                            <td style=\"font-size='12px'\" width=\"55%\">&nbsp; " . $this->tukd_model->dotrek($row->kd_rek6) . " $row->nm_rek6</td>
                                            <td style=\"font-size='12px'\" width=\"30%\" align=\"right\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;</td>
                                        </tr>";
        }

        $cRet .= "
                    <tr>
                        <td colspan =\"2\" align=\"right\"><b>Jumlah</b>&nbsp;</td>
                        <td align=\"right\"><b>" . number_format($lntotalpot2, "2", ",", ".") . "</b>&nbsp;</td>
                    </tr>";
        $n_total = ($jns == 7) ? 0 : $lntotal;
        $cRet .= "
                </table>  
            </td>
        </tr>
        <tr>
            <td colspan=\"2\">
                <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"-1\" cellpadding=\"-1\">
                   <tr>
                        <td colspan=\"4\" valign=\"bottom\" style=\"font-weight: bold;\">&nbsp;SP2D yang Dibayarkan</td>
                    </tr>
                   <tr>
                   
                        <td width=\"67%\" align=\"left\">&nbsp;Jumlah yang Diminta (Bruto)</td>
                        <td   align=\"left\" colspan=\"2\">&nbsp;Rp</td>
                        <td style=\"border-left: hidden;\"  align=\"right\">" . number_format($n_total, "2", ",", ".") . "&nbsp;</td>
                        </tr>
                    <tr > 
                        <td align=\"left\">&nbsp;Jumlah Potongan</td>
                        <td  align=\"left\" colspan=\"2\">&nbsp;Rp</td>
                        <td style=\"border-left: hidden;\" align=\"right\">" . number_format(($lntotalpot + $lntotalpot2), "2", ",", ".") . "&nbsp;</td>
                    </tr>
                    <tr style=\"font-weight: bold;\">
                        <td align=\"left\">&nbsp;<b>Jumlah yang Dibayarkan (Netto)</b></td>
                        <td  align=\"left\" colspan=\"2\"><b>&nbsp;Rp</b></td>
                        
                        <td style=\"border-left: hidden;\"align=\"right\" ><b>" . number_format($n_total - ($lntotalpot + $lntotalpot2), "2", ",", ".") . "&nbsp;</b></td>
                    </tr> 

                    <tr style=\"font-weight: bold;\">
                        <td align=\"left\" colspan=\"3\">&nbsp;<b><i>Uang Sejumlah :
                        &nbsp;" . $this->tukd_model->terbilang($n_total - ($lntotalpot + $lntotalpot2)) . "&nbsp;</b></i></td>
                        
                        <td style=\"border-left: hidden;\" >&nbsp;</td>
                    </tr>                    
                </table>  
            </td>
        </tr> 
        <tr>
            <td colspan=\"2\">
                <table style=\"border-collapse:collapse;font-weight: bold;font-family: Tahoma;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"-1\" cellpadding=\"-1\">
           
                <tr>
                        <td width=\"70%\" align=\"left\" style=\"font-size:10px\" valign=\"top\">
                        <br>&nbsp;Lembar 1 : Bank Yang Ditunjuk<br>
                        &nbsp;Lembar 2 : Pengguna Anggaran/Kuasa Pengguna Anggaran<br>
                        &nbsp;Lembar 3 : Arsip Kuasa BUD<br>
                        &nbsp;Lembar 4 : Pihak Penerima<br>
                               
                        </td>
                        <td width=\"30%\" align=\"center\">
                        <br>
                        " . $client->tetapkan . ", $tanggal<br>
                        " . $bud . "
                        <br>
                        <br>
                        <br>
                        <br>
                        <u>$nama</u><br>
                        NIP. $nip                
                        </td>
                   </tr>
                </table>  
            </td>
        </tr>
        
        
        </table>";
        $data['prev'] = $cRet;
        // echo $cRet;
        $this->_mpdf_sp2d2('', utf8_encode($cRet), 10, 5, 5, '0');
    }

    function cetak_lamp_sp2d()
    {
        //$jns = $this->uri->segment(3);
        $lntahunang = $this->session->userdata('pcThang');
        $lcnosp2d = str_replace('123456789', '/', $this->uri->segment(3));

        // $lcttd = str_replace('123456789','/',$this->uri->segment(5));
        $lcttd = str_replace('abc', ' ', $this->uri->segment(5));
        $banyak = $this->uri->segment(6);
        echo ($banyak);
        $jns_cetak = $this->uri->segment(7);
        $a = '*' . $lcnosp2d . '*';
        $csql = "SELECT a.*,
                (SELECT nmrekan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS nmrekan,
                (SELECT pimpinan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS pimpinan,
                (SELECT alamat FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS alamat
                 FROM trhsp2d a WHERE a.no_sp2d = '$lcnosp2d'";
        $hasil = $this->db->query($csql);
        $trh = $hasil->row();
        $lckd_skpd  = $trh->kd_skpd;
        $lcnospm    = $trh->no_spm;
        $ldtglspm   = $trh->tgl_spm;
        $lcnmskpd   = $trh->nm_skpd;
        $lckdskpd   = $trh->kd_skpd;
        $alamat     = $trh->alamat;
        $lcnpwp     = $trh->npwp;
        $rekbank    = $trh->no_rek;
        $lcperlu    = $trh->keperluan;
        $lcnospp    = $trh->no_spp;
        $tgl        = $trh->tgl_sp2d;
        $n          = $trh->nilai;
        $pimpinan   = $trh->pimpinan;
        $nmrekan    = $trh->nmrekan;
        $jns_bbn    = $trh->jenis_beban;
        $jns        = $trh->jns_spp;
        $bank = $trh->bank;
        $banyak_kar = strlen($lcperlu);
        $tanggal    = $this->tanggal_format_indonesia($tgl);
        //$banyak = $banyak_kar > 400 ? 14 :23;

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$lcttd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat = $rowttd->pangkat;
        }

        $cRet = "";
        $cRet  .= "
                <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:13px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr >
                        <td width=\"100%\" align=\"center\"><b>Daftar Lampiran SP2D Nomor: $lcnosp2d </b></td>
                    </tr>
                    <tr>
                        <td align=\"center\"><b>Tanggal : $tanggal</b></td>
                    </tr>
                    <tr>
                        <td align=\"center\">&nbsp;</td>
                    </tr>
                </table>";
        $cRet  .= "  
                <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$banyak\">
                    <tr >
                        <td width=\"7%\" align=\"center\"><b>NO</b></td>
                        <td width=\"22%\" align=\"center\"><b>KODE REKENING</b></td>
                        <td align=\"center\"><b>URAIAN</b></td>
                        <td width=\"18%\" align=\"center\"><b>JUMLAH (Rp)</b></td>
                    </tr>
                    <tr>
                        <td align=\"center\">1</td>
                        <td align=\"center\">2</td>
                        <td align=\"center\">3</td>
                        <td align=\"center\">4</td>
                    </tr>";

        $sql = "

            SELECT * from (
-- select left(a.kd_sub_kegiatan,12) as urut,left(a.kd_sub_kegiatan,12)as kd_sub_kegiatan,left(a.kd_sub_kegiatan,12) kd_rek,b.nm_kegiatan as nm_rek,sum(nilai)nilai from trdspp a inner join 
-- trskpd b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd where no_spp='21/SPP/GU/4.01.0.00.0.00.01.0000/2021' AND a.kd_skpd='4.01.0.00.0.00.01.0000'
-- group by left(a.kd_sub_kegiatan,12),nm_kegiatan
-- UNION ALL
select '1' as no,left(a.kd_sub_kegiatan,12) as urut,left(a.kd_sub_kegiatan,12)kd_sub_kegiatan,left(a.kd_sub_kegiatan,12) kd_rek,
(select nm_kegiatan from ms_kegiatan where left(a.kd_sub_kegiatan,12)=kd_kegiatan)
 as nm_rek,sum(nilai)nilai 
from trdspp a where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
group by left(a.kd_sub_kegiatan,12)
union all
select '2' as no,a.kd_sub_kegiatan as urut,kd_sub_kegiatan,kd_sub_kegiatan kd_rek,a.nm_sub_kegiatan as nm_rek,sum(nilai)nilai 
from trdspp a where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
group by kd_sub_kegiatan,nm_sub_kegiatan
union all
select '3' as no,a.kd_sub_kegiatan+'.'+a.kd_rek6 as urut,kd_sub_kegiatan,a.kd_rek6 kd_rek,b.nm_rek6 as nm_rek,sum(nilai)nilai from trdspp a inner join 
ms_rek6 b on a.kd_rek6=b.kd_rek6 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
group by a.kd_rek6,b.nm_rek6,kd_sub_kegiatan
) tox order by urut


            -- SELECT * from (

            --                     select '1'as urut,left(a.kd_sub_kegiatan,12)as kd_sub_kegiatan,left(a.kd_sub_kegiatan,12) kd_rek,b.nm_kegiatan as nm_rek,sum(nilai)nilai from trdspp a inner join 
            --                     trskpd b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
            --                     group by left(a.kd_sub_kegiatan,12),nm_kegiatan

            --                     union all
            --                     select '2'as urut,kd_sub_kegiatan,kd_sub_kegiatan kd_rek,a.nm_sub_kegiatan as nm_rek,sum(nilai)nilai from trdspp a where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
            --                     group by kd_sub_kegiatan,nm_sub_kegiatan
            --                     union all
            --                     select '3'as urut,kd_sub_kegiatan,a.kd_rek6 kd_rek,b.nm_rek6 as nm_rek,sum(nilai)nilai from trdspp a inner join 
            --                     ms_rek6 b on a.kd_rek6=b.kd_rek6 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
            --                     group by a.kd_rek6,b.nm_rek6,kd_sub_kegiatan
            --                     ) tox order by kd_rek


                                ";

        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lcno_baris = 0;
        $lntotal = 0;
        foreach ($hasil->result() as $row) {
            $lcno_baris = $lcno_baris + 1;
            if (strlen($row->kd_rek) >= 12) {
                $lcno = $lcno + 1;
                $lcno_x = $lcno;
            } else {
                $lcno_x = '';
            }
            //                                           $lntotal = $lntotal + $row->nilai;                                           
            // $cRet .="<tr>
            //              <td align=\"center\">&nbsp;$lcno_x</td>
            //              <td >&nbsp; $row->kd_sub_kegiatan.".$this->tukd_model->dotrek($row->kd_rek)." </td>
            //              <td >&nbsp; $row->nm_rek</td>
            //              <td align=\"right\">".number_format($row->nilai,"2",",",".")."&nbsp;</td>
            //          </tr>";


            if ($row->no == '3') {

                $cRet .= "<tr>
                                                        <td style=\"border-bottom: hidden;\" align=\"center\">&nbsp;$lcno_x</td>
                                                        <td style=\"border-bottom: hidden;\">&nbsp;" . $this->tukd_model->dotrek($row->kd_rek) . " </td>
                                                        <td style=\"border-bottom: hidden;\">&nbsp; $row->nm_rek</td>
                                                        <td style=\"border-bottom: hidden;\" align=\"right\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;</td>
                                                    </tr>";
                $lntotal = $lntotal + $row->nilai;
            } else {

                $cRet .= "<tr>
                                                        <td style=\"border-bottom: hidden;\" align=\"center\">&nbsp;<b>$lcno_x</b></td>
                                                        <td style=\"border-bottom: hidden;\">&nbsp;<b>" . $row->kd_rek . " </b></td>
                                                        <td style=\"border-bottom: hidden;\">&nbsp; <b>$row->nm_rek</b></td>
                                                        <td style=\"border-bottom: hidden;\" align=\"right\"><b>" . number_format($row->nilai, "2", ",", ".") . "&nbsp;</b></td>
                                                    </tr>";
            }
        }
        $cRet .= "<tr>
                        <td align=\"right\" colspan=\"3\">&nbsp;<b>JUMLAH&nbsp;</b></td>
                        <td align=\"right\"><b>" . number_format($lntotal, "2", ",", ".") . "</b>&nbsp;</td>
                    </tr>
        </table>";
        $cRet .= "<table style=\"border-collapse:collapse; font-family:Tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                    <td width=\"50%\" align=\"center\"></td>
                    <td width=\"50%\" align=\"center\">Sanggau, $tanggal</td>
                    </tr>
                    <tr>
                    <td width=\"50%\" align=\"center\"></td>
                    <td width=\"50%\" align=\"center\">$jabatan</td>
                    </tr>
                    <tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>                              
                    <tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>                                       
                    <tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"><b><u>$nama</u></b></td>
                    </tr>
                   

                    <tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">NIP. $nip</td>
                    </tr>
                    
                  </table>";
        $data['prev'] = $cRet;
        // echo "$cRet"  ;  
        $this->rka_model->_mpdf_folio_sp2d('', $cRet, 10, 5, 5, '0');
        //$this->_mpdf_sp2d2('',$cRet,10,5,5,'0');
    }
    function cetak_lamp_sp2d_()
    {
        //$jns = $this->uri->segment(3);
        $lntahunang = $this->session->userdata('pcThang');
        $lcnosp2d = str_replace('123456789', '/', $this->uri->segment(3));

        // $lcttd = str_replace('123456789','/',$this->uri->segment(5));
        $lcttd = str_replace('abc', ' ', $this->uri->segment(5));
        $banyak = $this->uri->segment(6);
        $jns_cetak = $this->uri->segment(7);
        $a = '*' . $lcnosp2d . '*';
        $csql = "SELECT a.*,
                (SELECT nmrekan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS nmrekan,
                (SELECT pimpinan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS pimpinan,
                (SELECT alamat FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS alamat
                 FROM trhsp2d a WHERE a.no_sp2d = '$lcnosp2d'";
        $hasil = $this->db->query($csql);
        $trh = $hasil->row();
        $lckd_skpd  = $trh->kd_skpd;
        $lcnospm    = $trh->no_spm;
        $ldtglspm   = $trh->tgl_spm;
        $lcnmskpd   = $trh->nm_skpd;
        $lckdskpd   = $trh->kd_skpd;
        $alamat     = $trh->alamat;
        $lcnpwp     = $trh->npwp;
        $rekbank    = $trh->no_rek;
        $lcperlu    = $trh->keperluan;
        $lcnospp    = $trh->no_spp;
        $tgl        = $trh->tgl_sp2d;
        $n          = $trh->nilai;
        $pimpinan   = $trh->pimpinan;
        $nmrekan    = $trh->nmrekan;
        $jns_bbn    = $trh->jenis_beban;
        $jns        = $trh->jns_spp;
        $bank = $trh->bank;
        $banyak_kar = strlen($lcperlu);
        $tanggal    = $this->tanggal_format_indonesia($tgl);
        //$banyak = $banyak_kar > 400 ? 14 :23;

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$lcttd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat = $rowttd->pangkat;
        }

        $cRet = "";
        $cRet  .= "<br><br><br><br><br><br><br>  
                <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:13px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr >
                        <td width=\"100%\" align=\"center\"><b>Daftar Lampiran SP2D Nomor: $lcnosp2d </b></td>
                    </tr>
                    <tr>
                        <td align=\"center\"><b>Tanggal : $tanggal</b></td>
                    </tr>
                    <tr>
                        <td align=\"center\">&nbsp;</td>
                    </tr>
                </table>";
        $cRet  .= "  
                <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr >
                        <td width=\"5%\" align=\"center\"><b>NO</b></td>
                        <td width=\"28%\" align=\"center\"><b>KODE REKENING</b></td>
                        <td align=\"center\"><b>URAIAN</b></td>
                        <td width=\"15%\" align=\"center\"><b>JUMLAH (Rp)</b></td>
                    </tr>
                    <tr>
                        <td align=\"center\">1</td>
                        <td align=\"center\">2</td>
                        <td align=\"center\">3</td>
                        <td align=\"center\">4</td>
                    </tr>";

        $sql = "SELECT * from (

                                select '1'as urut,left(a.kd_sub_kegiatan,12)as kd_sub_kegiatan,left(a.kd_sub_kegiatan,12) kd_rek,b.nm_kegiatan as nm_rek,sum(nilai)nilai from trdspp a inner join 
                                trskpd b on a.kd_sub_kegiatan=b.kd_sub_kegiatan where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                                group by left(a.kd_sub_kegiatan,12),nm_kegiatan
                                union all
                                select '2'as urut,kd_sub_kegiatan,kd_sub_kegiatan kd_rek,a.nm_sub_kegiatan as nm_rek,sum(nilai)nilai from trdspp a where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                                group by kd_sub_kegiatan,nm_sub_kegiatan
                                union all
                                select '3'as urut,kd_sub_kegiatan,a.kd_rek6 kd_rek,b.nm_rek6 as nm_rek,sum(nilai)nilai from trdspp a inner join 
                                ms_rek6 b on a.kd_rek6=b.kd_rek6 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                                group by a.kd_rek6,b.nm_rek6,kd_sub_kegiatan
                                ) tox order by urut,kd_rek";

        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lcno_baris = 0;
        $lntotal = 0;
        foreach ($hasil->result() as $row) {
            $lcno_baris = $lcno_baris + 1;
            if (strlen($row->kd_rek) >= 12) {
                $lcno = $lcno + 1;
                $lcno_x = $lcno;
            } else {
                $lcno_x = '';
            }
            //                                           $lntotal = $lntotal + $row->nilai;                                           
            // $cRet .="<tr>
            //              <td align=\"center\">&nbsp;$lcno_x</td>
            //              <td >&nbsp; $row->kd_sub_kegiatan.".$this->tukd_model->dotrek($row->kd_rek)." </td>
            //              <td >&nbsp; $row->nm_rek</td>
            //              <td align=\"right\">".number_format($row->nilai,"2",",",".")."&nbsp;</td>
            //          </tr>";


            if ($row->urut == '3') {

                $cRet .= "<tr>
                                                        <td style=\"border-bottom: hidden;\" align=\"center\">&nbsp;$lcno_x</td>
                                                        <td style=\"border-bottom: hidden;\">&nbsp;" . $this->tukd_model->dotrek($row->kd_rek) . " </td>
                                                        <td style=\"border-bottom: hidden;\">&nbsp; $row->nm_rek</td>
                                                        <td style=\"border-bottom: hidden;\" align=\"right\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;</td>
                                                    </tr>";
                $lntotal = $lntotal + $row->nilai;
            } else {

                $cRet .= "<tr>
                                                        <td style=\"border-bottom: hidden;\" align=\"center\">&nbsp;<b>$lcno_x</b></td>
                                                        <td style=\"border-bottom: hidden;\">&nbsp;<b>" . $row->kd_rek . " </b></td>
                                                        <td style=\"border-bottom: hidden;\">&nbsp; <b>$row->nm_rek</b></td>
                                                        <td style=\"border-bottom: hidden;\" align=\"right\"><b>" . number_format($row->nilai, "2", ",", ".") . "&nbsp;</b></td>
                                                    </tr>";
            }
        }
        $cRet .= "<tr>
                        <td align=\"right\" colspan=\"3\">&nbsp;<b>JUMLAH&nbsp;</b></td>
                        <td align=\"right\"><b>" . number_format($lntotal, "2", ",", ".") . "</b>&nbsp;</td>
                    </tr>
        </table>";
        $cRet .= "<table style=\"border-collapse:collapse; font-family:Tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>
                    <tr>
                    <td width=\"50%\" align=\"center\"></td>
                    <td width=\"50%\" align=\"center\">Pontianak, $tanggal</td>
                    </tr>
                    <tr>
                    <td width=\"50%\" align=\"center\"></td>
                    <td width=\"50%\" align=\"center\">$jabatan<br>Kuasa Bendahara Umum Daerah</td>
                    </tr>
                    <tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>                              
                    <tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    </tr>                                       
                    <tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\"><b><u>$nama</u></b></td>
                    </tr>
                    <tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">$pangkat</td>
                    </tr>

                    <tr>
                    <td width=\"50%\" align=\"center\">&nbsp;</td>
                    <td width=\"50%\" align=\"center\">NIP. $nip</td>
                    </tr>
                    
                  </table>";
        $data['prev'] = $cRet;
        // echo "$cRet"  ;  
        //$this->rka_model->_mpdf_folio('',$cRet,10,10,10,'0');
        $this->_mpdf_sp2d2('', $cRet, 10, 5, 5, '0');
    }


    function cetak_sp2d_old()
    {
        //$jns = $this->uri->segment(3);
        $lntahunang = $this->session->userdata('pcThang');
        $lcnosp2d = str_replace('123456789', '/', $this->uri->segment(3));
        // $lcttd = str_replace('123456789','/',$this->uri->segment(5));
        $lcttd = str_replace('abc', ' ', $this->uri->segment(5));
        $banyak = $this->uri->segment(6);
        $jns_cetak = $this->uri->segment(7);
        $a = '*' . $lcnosp2d . '*';
        $csql = "SELECT a.*,
                (SELECT nmrekan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS nmrekan,
                (SELECT pimpinan FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS pimpinan,
                (SELECT alamat FROM trhspp WHERE no_spp = a.no_spp AND kd_skpd=a.kd_skpd) AS alamat
                 FROM trhsp2d a WHERE a.no_sp2d = '$lcnosp2d'";
        $hasil = $this->db->query($csql);
        $trh = $hasil->row();
        $lckd_skpd  = $trh->kd_skpd;
        $lcnospm    = $trh->no_spm;
        $ldtglspm   = $trh->tgl_spm;
        $lcnmskpd   = $trh->nm_skpd;
        $lckdskpd   = $trh->kd_skpd;
        $alamat     = $trh->alamat;
        $lcnpwp     = $trh->npwp;
        $rekbank    = $trh->no_rek;
        $lcperlu    = $trh->keperluan;
        $lcnospp    = $trh->no_spp;
        $tgl        = $trh->tgl_sp2d;
        //$n          = $trh->nilai;
        $pimpinan   = $trh->pimpinan;
        $nmrekan    = $trh->nmrekan;
        $jns_bbn    = $trh->jenis_beban;
        $jns        = $trh->jns_spp;
        $bank = $trh->bank;
        $banyak_kar = strlen($lcperlu);
        $tanggal    = $this->tanggal_format_indonesia($tgl);
        //$banyak = $banyak_kar > 400 ? 14 :23;                     

        $csqlnilai = "SELECT sum(nilai) [nilai] from  trdspp WHERE no_spp = '$lcnospp'";
        $hasiln = $this->db->query($csqlnilai);
        $trhn = $hasiln->row();
        $n          = $trhn->nilai;

        $sqlrek = "SELECT bank,rekening, npwp FROM ms_skpd WHERE kd_skpd = '$lckd_skpd' ";
        $sqlrek = $this->db->query($sqlrek);
        foreach ($sqlrek->result() as $rowrek) {
            $bank_ben = $rowrek->bank;
            $rekben = $rowrek->rekening;
            $npwp_ben = $rowrek->npwp;
        }
        $rek_ben = empty($rekben) || $rekben == 0 ? '' : $rekben;
        $npwp_ben = empty($npwp_ben) || $npwp_ben == 0 ? '' : $npwp_ben;
        $nama_bank = empty($bank) ? 'Belum Pilih Bank' : $this->rka_model->get_nama($bank, 'nama', 'ms_bank', 'kode');
        $nama_bank_ben = empty($bank_ben) ? 'Belum Pilih Bank' : $this->rka_model->get_nama($bank_ben, 'nama', 'ms_bank', 'kode');
        //$nama_bank_ben = $this->rka_model->get_nama(12,'nama','ms_bank','kode');      
        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$lcttd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat = $rowttd->pangkat;
        }
        $sqlnam = "SELECT TOP 1 * FROM ms_ttd WHERE kd_skpd = '$lckdskpd' AND kode='BK' ";
        $sqlnam = $this->db->query($sqlnam);
        foreach ($sqlnam->result() as $rownam) {
            $nama_ben = $rownam->nama;
            $jabat_ben = $rownam->jabatan;
        }
        $nama_ben = empty($nama_ben) ? 'Belum Ada data Bendahara' : $nama_ben;
        $jabat_ben = empty($jabat_ben) ? ' ' : $jabat_ben;

        if (($jns == '1') or ($jns == '2')  or ($jns == '4') or ($jns == '5')) {
            $kd_kegi = '';
            $nm_kegi = '';
            $kd_prog = '';
            $nm_prog = '';
        } else {
            $sql12 = "SELECT kd_sub_kegiatan FROM trdspp a INNER JOIN trhsp2d b ON a.no_spp = b.no_spp AND a.kd_skpd=b.kd_skpd 
                WHERE b.kd_skpd = '$lckdskpd' AND no_sp2d='$lcnosp2d' group by kd_sub_kegiatan ";
            $sqlrek12 = $this->db->query($sql12);
            foreach ($sqlrek12->result() as $rowrek) {
                $kd_kegi = $rowrek->kd_sub_kegiatan;
            }
            $nm_kegi = " - " . $this->rka_model->get_nama($kd_kegi, 'nm_sub_kegiatan', 'trskpd', 'kd_sub_kegiatan');
            $kd_prog = $this->left($kd_kegi, 7);
            $nm_prog = " - " . $this->rka_model->get_nama($kd_prog, 'nm_program', 'trskpd', 'kd_program');
        }
        if ($jns_cetak == '2') {
            $tinggi = '150px';
            //$banyak=9;
            $banyak = 10;
        } else 
        if ($jns_cetak == '1') {
            //$tinggi='80px';
            $tinggi = '10px';
            //$banyak=15;
            $banyak = 16;
        } else {
            $tinggi = '10px';
            $banyak = $banyak;
        }

        $cRet = '';
        $cRet .= "<!--<table style=\"font-family: Tahoma; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td rowspan=\"4\" align=\"right\">
                        <img src=\"" . base_url() . "/image/logo.bmp\"  width=\"75\" height=\"100\" />
                        </td>
                    <td align=\"center\" style=\"font-size:14px\"><strong>PEMERINTAH KABUPATEN KUBU RAYA </strong></td></tr>
                    <tr><td align=\"center\" style=\"font-size:15px\"><strong>BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH</strong></tr>
                    <tr><td align=\"center\" style=\"font-size:12px\">Jalan Ahmad Yani Telepon (0561) 736541 Fax. (0561) 738428</tr>
                    <tr><td align=\"center\">P O N T I A N A K</td></tr>
                    <tr><td colspan=\"2\" style=\"border-bottom: hidden;\" align=\"right\" >Kode Pos: 78124 &nbsp; &nbsp;</td></tr>
                    <tr></td><td colspan=\"2\" style=\"border-top: 2px solid black;border-bottom: 1px solid black;font-size:1px;\"  align=\"right\" >&nbsp;</td></tr>
                    </table>
                    &nbsp;<br><br>-->
                    ";
        $cRet .= "<br><br><br><br><br><br><br>
        <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px;\"  width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">";
        $cRet .= "
        <tr>
            <td align=\"center\" width=\"50%\" style=\"border-collapse:collapse;font-weight:bold; font-size:12px\"> KABUPATEN KUBU RAYA
            </td>
            <td align=\"center\" width=\"50%\">
                <table style=\"border-collapse:collapse;font-size:12px; font-weight: bold;\" width=\"100%\" align=\"center\" cellspacing=\"4\" cellpadding=\"0\">
                    <tr>
                        <td align=\"right\">
                            <b>Nomor : $lcnosp2d</b>
                        </td>
                    </tr>
                    <tr>
                        <td align=\"center\">
                            SURAT PERINTAH PENCAIRAN DANA<br>(SP2D)
                        </td>
                    </tr>
                </table>
            </td>
        </tr>   
        <tr>
            <td style=\"border-left:solid 1px black;\" >
                <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" width=\"100%\" align=\"center\" valign=\"top\" border=\"1\" cellspacing=\"4\" cellpadding=\"0\">
                        <tr>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" width=\"25%\" align=\"left\" valign=\"top\">&nbsp;Nomor SPM</td>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" width=\"2%\" valign=\"top\">:</td>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" width=\"73%\" valign=\"top\">$lcnospm</td>
                    </tr>
                    <tr>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" valign=\"top\">&nbsp;Tanggal</td>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" valign=\"top\" >:</td>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" valign=\"top\">" . $this->tanggal_format_indonesia($ldtglspm) . "</td>
                    </tr>
                    <tr>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" valign=\"top\">&nbsp;OPD</td>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" valign=\"top\">:</td>
                        <td style=\"border-left:hidden;border-top: hidden;border-bottom: hidden; border-right: hidden;\" valign=\"top\" height=\"60px\">$lckd_skpd $lcnmskpd</td>
                    </tr>
                </table>
            </td>
            <td>
                <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" width=\"100%\"  valign=\"top\" border=\"0\" cellspacing=\"4\" cellpadding=\"0\">
                    <tr>
                        <td valign=\"top\">&nbsp;Dari &nbsp; : Kuasa Bendahara Umum Daerah (Kuasa BUD)</td>
                    </tr>
                     <tr>
                        <td valign=\"top\" >&nbsp;Tahun Anggaran : &nbsp;$lntahunang</td>
                    </tr>
                    <tr>
                        <td valign=\"top\" >&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign=\"top\" >&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign=\"top\" >&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign=\"top\" >&nbsp;</td>
                    </tr>
                </table>
            </td>
        </tr>
            <tr>
        <td colspan=\"2\">
            <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12x\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"4\" cellpadding=\"0\">
            <tr>
                <td style=\"border-bottom: hidden; border-right: hidden;\" width=\"120px\">&nbsp;Bank/Pos</td>
                <td style=\"border-bottom: hidden;\" >:&nbsp;PT. Bank Kalbar Cabang Utama Pontianak</td>
            </tr>
            <tr>
                <td style=\"border-bottom: hidden;\" colspan=\"2\" >&nbsp;Hendaklah mencairkan / memindahbukukan dari bank Rekening Nomor 1001002201</td>
            </tr>
            <tr>
                <td style=\"border-bottom: hidden; border-right: hidden;\" >&nbsp;Uang sebesar Rp</td>
                <td style=\"border-bottom: hidden;\" >:&nbsp;" . number_format($n, '2', ',', '.') . "  (" . $this->tukd_model->terbilang($n) . ") </td>
            </tr>
            </table>
        </td>
        </tr>   
        <tr>
            <td colspan=\"2\">";
        if (($jns == 6) && ($jns_bbn == 3)) {

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"-1\" cellpadding=\"-1\">
               <tr>
                    <td valign=\"top\" width=\"120px\">&nbsp;Kepada</td>
                    <td valign=\"top\" width=\"10px\" >:</td>
                    <td valign=\"top\" >$pimpinan, $nmrekan, $alamat</td>
                </tr>
                <tr>
                    <td valign=\"top\" >&nbsp;NPWP</td>
                    <td valign=\"top\" >:</td>
                    <td valign=\"top\" >$lcnpwp</td>
                </tr>
                <tr>
                    <td valign=\"top\" >&nbsp;No.Rekening Bank</td>
                    <td valign=\"top\" >:</td>
                    <td valign=\"top\" >$rekbank</td>
                </tr>
                <tr>
                    <td valign=\"top\">&nbsp;Bank/Pos</td>
                    <td valign=\"top\">:</td>
                    <td valign=\"top\">$nama_bank</td>
                </tr>
                <tr>
                    <td valign=\"top\" >&nbsp;Untuk Keperluan</td>
                    <td valign=\"top\" >:</td>
                    <td height=\"$tinggi\" valign=\"top\" style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" >$lcperlu
                    <br>" . $this->right($kd_prog, 2) . "$nm_prog
                    <br>" . $this->right($kd_kegi, 2) . "$nm_kegi
                    </td>

                </tr>
                </table> ";
        } else if ($jns == 5) {

            $cRet .= "<table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"-1\" cellpadding=\"-1\">
               <tr>
                    <td valign=\"top\" width=\"120px\">&nbsp;Kepada</td>
                    <td valign=\"top\" width=\"10px\" >:</td>
                    <td valign=\"top\" >$nmrekan, $alamat</td>
                </tr>
                <tr>
                    <td valign=\"top\" >&nbsp;NPWP</td>
                    <td valign=\"top\" >:</td>
                    <td valign=\"top\" >$lcnpwp</td>
                </tr>
                <tr>
                    <td valign=\"top\" >&nbsp;No.Rekening Bank</td>
                    <td valign=\"top\" >:</td>
                    <td valign=\"top\" >$rekbank</td>
                </tr>
                <tr>
                    <td valign=\"top\">&nbsp;Bank/Pos</td>
                    <td valign=\"top\">:</td>
                    <td valign=\"top\">$nama_bank</td>
                </tr>
                <tr>
                    <td valign=\"top\" >&nbsp;Untuk Keperluan</td>
                    <td valign=\"top\" >:</td>
                    <td height=\"$tinggi\" valign=\"top\" style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" >$lcperlu
                    <br>" . $this->right($kd_prog, 2) . "$nm_prog
                    <br>" . $this->right($kd_kegi, 2) . "$nm_kegi
                    </td>

                </tr>
                </table> ";
        } else {
            $cRet .= "<table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"-1\" cellpadding=\"-1\">
               <tr>
                    <td valign=\"top\" width=\"120px\">&nbsp;Kepada </td>
                    <td valign=\"top\" width=\"10px\">:&nbsp;</td>
                    <td valign=\"top\" font-family: Arial; >$nama_ben - $jabat_ben</td>
                </tr>
                <tr>
                    <td valign=\"top\" >&nbsp;NPWP</td>
                    <td valign=\"top\" >:</td>
                    <td valign=\"top\" >$npwp_ben</td>
                </tr>
                <tr>
                    <td valign=\"top\" >&nbsp;No.Rekening Bank</td>
                    <td valign=\"top\" >:</td>
                    <td valign=\"top\" >$rek_ben</td>
                </tr>
                <tr>
                    <td valign=\"top\" >&nbsp;Bank/Pos</td>
                    <td valign=\"top\" >:</td>
                    <td valign=\"top\" >$nama_bank_ben</td>
                </tr>
                <tr>
                    <td valign=\"top\" >&nbsp;Untuk Keperluan</td>
                    <td valign=\"top\" >:</td>
                    <td height=\"$tinggi\" valign=\"top\" style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" >$lcperlu
                    <br>" . $this->right($kd_prog, 2) . "$nm_prog
                    <br>" . $this->right($kd_kegi, 2) . "$nm_kegi
                    </td>
                </tr>
                </table> ";
        }
        $cRet  .= "  </td>
        </tr>
        <tr>
            <td colspan=\"2\">
                <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:11px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr >
                        <td width=\"5%\" align=\"center\"><b>NO</b></td>
                        <td width=\"28%\" align=\"center\"><b>KODE REKENING</b></td>
                        <td align=\"center\"><b>URAIAN</b></td>
                        <td width=\"15%\" align=\"center\"><b>JUMLAH (Rp)</b></td>
                    </tr>
                    <tr>
                        <td align=\"center\">1</td>
                        <td align=\"center\">2</td>
                        <td align=\"center\">3</td>
                        <td align=\"center\">4</td>
                    </tr>";
        $sql_total = "SELECT sum(nilai)total FROM trdspp where no_spp='$lcnospp' AND kd_skpd='$lckd_skpd'";
        $sql_x = $this->db->query($sql_total);
        foreach ($sql_x->result() as $row_x) {
            $lntotal = $row_x->total;
        }
        if (($jns == 1) || ($jns == 2)) {
            $sql = "select SUM(nilai) nilai from trdspp where no_spp='$lcnospp' AND kd_skpd='$lckd_skpd'";
            $hasil = $this->db->query($sql);
            $lcno = 0;
            $lntotal = 0;
            foreach ($hasil->result() as $row) {
                $lcno = $lcno + 1;
                $lntotal = $lntotal + $row->nilai;
                $cRet .= "<tr>
                                                        <td style=\"border-bottom: hidden;\" align=\"center\">&nbsp;1</td>
                                                        <td style=\"border-bottom: hidden;\">&nbsp; $lckd_skpd  </td>
                                                        <td style=\"border-bottom: hidden;\">&nbsp; $lcnmskpd</td>
                                                        <td style=\"border-bottom: hidden;\" align=\"right\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;</td>
                                                    </tr>";
            }
            if ($lcno <= $banyak) {
                for ($i = $lcno; $i <= $banyak; $i++) {
                    $cRet .= "<tr>
                                                        <td style=\"border-top: hidden;\" align=\"center\">&nbsp;</td>
                                                        <td style=\"border-top: hidden;\" ></td>
                                                        <td style=\"border-top: hidden;\"></td>
                                                        <td style=\"border-top: hidden;\" align=\"right\"></td>
                                                    </tr>";
                }
            }
        } else {



            $sql1 = "select COUNT(*) as jumlah from 
                            (select kd_sub_kegiatan,left(kd_rek6,2)kd_rek,nm_rek2 as nm_rek,sum(nilai)nilai from trdspp a inner join 
                            ms_rek2 b on left(kd_rek6,2)=kd_rek2 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd' 
                            group by left(kd_rek6,2),nm_rek2,kd_sub_kegiatan
                            union all
                            select kd_sub_kegiatan,left(kd_rek6,4)kd_rek,nm_rek3 as nm_rek,sum(nilai)nilai from trdspp a inner join 
                            ms_rek3 b on left(kd_rek6,4)=kd_rek3 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                            group by left(kd_rek6,4),nm_rek3,kd_sub_kegiatan
                            union all
                            select kd_sub_kegiatan,left(kd_rek6,6)kd_rek,nm_rek4 as nm_rek,sum(nilai)nilai from trdspp a inner join 
                            ms_rek4 b on left(kd_rek6,6)=kd_rek4 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                            group by left(kd_rek6,6),nm_rek4,kd_sub_kegiatan
                            union all 
                           select kd_sub_kegiatan,left(kd_rek6,8)kd_rek,nm_rek5 as nm_rek,sum(nilai)nilai from trdspp a 
                             inner join ms_rek5 b on left(kd_rek6,8)=kd_rek5 
                             where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd' 
                            group by left(kd_rek6,8),nm_rek5,kd_sub_kegiatan
                            union all
                            select kd_sub_kegiatan,a.kd_rek6 kd_rek,b.nm_rek6 as nm_rek,sum(nilai)nilai from trdspp a inner join 
                            ms_rek6 b on a.kd_rek6=b.kd_rek6 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                            group by a.kd_rek6,b.nm_rek6,kd_sub_kegiatan
                            ) tox";
            $hasil1 = $this->db->query($sql1);
            $row1 = $hasil1->row();
            $jumlahbaris = $row1->jumlah;
            if ($jumlahbaris <= $banyak) {
                $sql = "select * from (select kd_sub_kegiatan,left(kd_rek6,2)kd_rek,nm_rek2 as nm_rek,sum(nilai)nilai from trdspp a inner join 
                            ms_rek2 b on left(kd_rek6,2)=kd_rek2 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                            group by left(kd_rek6,2),nm_rek2,kd_sub_kegiatan
                            union all
                            select kd_sub_kegiatan,left(kd_rek6,4)kd_rek,nm_rek3 as nm_rek,sum(nilai)nilai from trdspp a inner join 
                            ms_rek3 b on left(kd_rek6,4)=kd_rek3 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                            group by left(kd_rek6,4),nm_rek3,kd_sub_kegiatan
                            union all
                            select kd_sub_kegiatan,left(kd_rek6,6)kd_rek,nm_rek4 as nm_rek,sum(nilai)nilai from trdspp a inner join 
                            ms_rek4 b on left(kd_rek6,6)=kd_rek4 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                            group by left(kd_rek6,6),nm_rek4,kd_sub_kegiatan
                            union all 
                           select kd_sub_kegiatan,left(kd_rek6,8)kd_rek,nm_rek5 as nm_rek,sum(nilai)nilai from trdspp a 
                             inner join ms_rek5 b on left(kd_rek6,8)=kd_rek5 
                             where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd' 
                            group by left(kd_rek6,8),nm_rek5,kd_sub_kegiatan
                            union all
                            select kd_sub_kegiatan,a.kd_rek6 kd_rek,b.nm_rek6 as nm_rek,sum(nilai)nilai from trdspp a inner join 
                            ms_rek6 b on a.kd_rek6=b.kd_rek6 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                            group by a.kd_rek6,b.nm_rek6,kd_sub_kegiatan
                            ) tox order by kd_rek";
            } else {
                $sql = "select * from (select '1' urut, kd_sub_kegiatan,left(kd_rek6,2)kd_rek,nm_rek2 as nm_rek,sum(nilai)nilai from trdspp a inner join 
                            ms_rek2 b on left(kd_rek6,2)=kd_rek2 where no_spp='$lcnospp' AND a.kd_skpd='$lckd_skpd'
                            group by left(kd_rek6,2),nm_rek2,kd_sub_kegiatan
                            union all
                            select '2' as urut, '' as kd_sub_kegiatan, '' kd_rek, '(Rincian Terlampir)' as nm_rek, 0 as nilai
                            ) tox order by urut,kd_rek";
            }
            $hasil = $this->db->query($sql);
            $lcno = 0;
            $lcno_baris = 0;
            // $lntotal = 0;
            foreach ($hasil->result() as $row) {
                $lcno_baris = $lcno_baris + 1;
                if (strlen($row->kd_rek) >= 12) {
                    //print_r($row);
                    //exit();
                    $lcno = $lcno + 1;
                    $lcno_x = $lcno;
                } else {
                    $lcno_x = '';
                }
                //                                           $lntotal = $lntotal + $row->nilai;                                           
                $cRet .= "<tr>
                                                        <td style=\"border-bottom: hidden;\" align=\"center\">&nbsp;$lcno_x</td>
                                                        <td style=\"border-bottom: hidden;\">&nbsp; $row->kd_sub_kegiatan." . $this->tukd_model->dotrek($row->kd_rek) . " </td>
                                                        <td style=\"border-bottom: hidden;\">&nbsp; $row->nm_rek</td>
                                                        <td style=\"border-bottom: hidden;\" align=\"right\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;</td>
                                                    </tr>";
            }
            if ($lcno_baris <= $banyak) {
                for ($i = $lcno_baris; $i <= $banyak; $i++) {
                    $cRet .= "<tr>
                                                        <td style=\"border-top: hidden;\" align=\"center\">&nbsp;</td>
                                                        <td style=\"border-top: hidden;\" ></td>
                                                        <td style=\"border-top: hidden;\"></td>
                                                        <td style=\"border-top: hidden;\" align=\"right\"></td>
                                                    </tr>";
                }
            }
        }
        $cRet .= "<tr>
                        <td align=\"right\" colspan=\"3\">&nbsp;<b>JUMLAH&nbsp;</b></td>
                        <td align=\"right\"><b>" . number_format($lntotal, "2", ",", ".") . "</b>&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan=\"4\">&nbsp;Potongan-potongan</td>
                    </tr>
                    <tr>
                        <td  align=\"center\"><b>NO</b></td>
                        <td  align=\"center\"><b>Uraian (No.Rekening)</b></td>
                        <td  align=\"center\"><b>Jumlah(Rp)</b></td>
                        <td  align=\"center\"><b>Keterangan</b></td>
                    </tr>";

        // $sql = "select * from trspmpot where no_spm='$lcnospm' AND kd_rek6 IN('2111001','4140611','2110501','2110701','2110702','2110703','2110801','2110901','4140612','2111501')";
        $sql = "select a.* from trspmpot a inner join ms_pot b on a.map_pot=b.map_pot where no_spm='$lcnospm' AND kelompok='1' AND kd_skpd='$lckd_skpd'";
        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lntotalpot = 0;
        foreach ($hasil->result() as $row) {
            $lcno = $lcno + 1;
            $lntotalpot = $lntotalpot + $row->nilai;
            $cRet .= "<tr>
                                            <td align=\"center\">&nbsp;$lcno</td>
                                            <td style=\"font-size='12px'\">&nbsp; $row->nm_rek6</td>
                                            <td style=\"font-size='12px'\" align=\"right\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>";
        }
        if ($lcno <= 4) {
            for ($i = $lcno; $i < 4; $i++) {
                $cRet .= "<tr>
                                                        <td>&nbsp;</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                     </tr>";
            }
        }

        $cRet .= "
                    <tr>
                        <td>&nbsp;</td>
                        <td align=\"right\"><b>Jumlah</b>&nbsp;</td>
                        <td align=\"right\"><b>" . number_format($lntotalpot, "2", ",", ".") . "</b>&nbsp;</td>
                        <td></td>
                    </tr>
                     <tr>
                        <td colspan=\"4\">&nbsp;Informasi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>(tidak mengurangi jumlah pembayaran SP2D)</i></td>
                    </tr>
            
                    <tr>
                        <td align=\"center\"><b>NO</b></td>
                        <td align=\"center\"><b>Uraian (No.Rekening)</b></td>
                        <td align=\"center\"><b>Jumlah(Rp)</b></td>
                        <td align=\"center\"><b>Keterangan</b></td>
                    </tr>";

        $sql = "SELECT a.* from trspmpot a inner join ms_pot b on a.map_pot=b.map_pot where no_spm='$lcnospm' AND kelompok='2' AND kd_skpd='$lckd_skpd'";

        // select 1 urut, * from trspmpot where no_spm='$lcnospm' AND kd_rek6 IN('2130301') AND kd_skpd='$lckd_skpd'
        // UNION ALL
        // select 2 urut, * from trspmpot where no_spm='$lcnospm' AND kd_rek6 NOT IN('210107010001','210108010001') AND kd_skpd='$lckd_skpd'
        // ORDER BY urut";
        // $sql = "select 1 urut, * from trspmpot where no_spm='$lcnospm' AND kd_rek6 IN('2130301')
        //         UNION ALL
        //         select 2 urut, * from trspmpot where no_spm='$lcnospm' AND kd_rek6 NOT IN('2111001','4140611','2130301','2110501','2110701','2110702','2110703','2110801','2110901','4140612','2111501')
        //         ORDER BY urut,kd_rek6";
        $hasil = $this->db->query($sql);
        $lcno = 0;
        $lntotalpott = 0;
        foreach ($hasil->result() as $row) {
            $lcno = $lcno + 1;
            $lntotalpott = $lntotalpott + $row->nilai;
            $kode_rek = $row->kd_rek6;
            // if($kode_rek=='2130101'){
            //     $nama_rek='PPh 21';
            // } else if ($kode_rek=='2130201'){
            //     $nama_rek='PPh 22';
            // } else if($kode_rek=='2130301'){
            //     $nama_rek='PPN';
            // } else if($kode_rek=='2130401'){
            //     $nama_rek='PPh 23';
            // } else if($kode_rek=='2130501'){
            //     $nama_rek='PPh Pasal 4';
            // } else{
            $nama_rek = $row->nm_rek6;
            // }
            $cRet .= "<tr>
                                            <td align=\"center\">&nbsp;$lcno</td>
                                            <td> &nbsp; $nama_rek</td>
                                            <td align=\"right\">" . number_format($row->nilai, "2", ",", ".") . "&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>";
        }
        if ($lcno <= 4) {
            for ($i = $lcno; $i < 4; $i++) {
                $cRet .= "<tr>
                                                        <td>&nbsp;</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                     </tr>";
            }
        }



        $jum_bayar = strval($lntotal - $lntotalpot);
        $bil_bayar = strval($lntotal - ($lntotalpot + $lntotalpott));
        $cRet .= "
                    <tr>
                        <td>&nbsp;</td>
                        <td align=\"right\"><b>Jumlah</b>&nbsp;</td>
                        <td align=\"right\"><b>" . number_format($lntotalpott, "2", ",", ".") . "</b>&nbsp;</td>
                        <td></td>
                    </tr>
                     
                </table>  
            </td>
        </tr>
        <tr>
            <td colspan=\"2\">
                <table style=\"border-collapse:collapse;font-family: Tahoma;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"-1\" cellpadding=\"-1\">
                   <tr>
                        <td colspan=\"4\" valign=\"bottom\" style=\"font-weight: bold;\">&nbsp;SP2D yang Dibayarkan</td>
                    </tr>
                   <tr>
                   
                        <td width=\"28%\" align=\"left\">&nbsp;Jumlah yang Diterima</td>
                        <td style=\"border-left: hidden;\" width=\"4%\" align=\"left\">&nbsp;RP</td>
                        <td style=\"border-left: hidden;\" width=\"50%\" align=\"right\">&nbsp;" . number_format($lntotal, "2", ",", ".") . "</td>
                        <td style=\"border-left: hidden;\" width=\"20%\" align=\"center\">&nbsp;</td>
                        </tr>
                    <tr > 
                        <td align=\"left\">&nbsp;Jumlah Potongan</td>
                        <td style=\"border-left: hidden;\" align=\"left\">&nbsp;RP</td>
                        <td style=\"border-left: hidden;\" align=\"right\" >&nbsp; " . number_format($lntotalpot + $lntotalpott, "2", ",", ".") . "</td>
                        <td style=\"border-left: hidden;\" >&nbsp;</td>
                    </tr>
                    <tr style=\"font-weight: bold;\">
                        <td align=\"left\">&nbsp;<b>Jumlah yang Dibayarkan</b></td>
                        <td style=\"border-left: hidden;\" align=\"left\"><b>&nbsp;RP</b></td>
                        <td style=\"border-left: hidden;font-size:11px;\" align=\"right\"><b>&nbsp; " . number_format($lntotal - ($lntotalpot + $lntotalpott), "2", ",", ".") . "</b></td>
                        <td style=\"border-left: hidden;\" >&nbsp;</td>
                    </tr>                    
                </table>  
            </td>
        </tr>
        
        <tr>
            <td colspan=\"2\">
                <table style=\"border-collapse:collapse;font-weight: bold;font-family: Tahoma;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"-1\" cellpadding=\"-1\">
            <tr>
                        <td colspan=\"2\" align=\"left\" >&nbsp;Uang Sejumlah :
                        (&nbsp;" . $this->tukd_model->terbilang($bil_bayar) . "&nbsp;)</td>
                        
    
                    </tr>
                <tr>
                        <td width=\"70%\" align=\"left\" style=\"font-size:10px\" valign=\"top\">
                        <br>&nbsp;Lembar 1 : Bank Yang Ditunjukan<br>
                        &nbsp;Lembar 2 : Pengguna Anggaran/Kuasa Pengguna Anggaran<br>
                        &nbsp;Lembar 3 : Arsip Kuasa BUD<br>
                        &nbsp;Lembar 4 : Pihak Ketiga<br>
                               
                        </td>
                        <td width=\"30%\" align=\"center\">
                        <br>
                        Pontianak, $tanggal<br>
                        $jabatan<br>Kuasa Bendahara Umum Daerah
                        <br>
                        <br>
                        <br>
                        <br>
                        <u>$nama</u><br>
                        $pangkat<br>
                        NIP. $nip                
                        </td>
                   </tr>
                </table>  
            </td>
        </tr>
        
        
        </table>";
        $data['prev'] = $cRet;
        //echo "$cRet"  ;  
        $this->_mpdf_sp2d2('', $cRet, 10, 5, 5, '0');
    }
}
