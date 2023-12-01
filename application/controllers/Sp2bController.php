<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Sp2bController extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('custom');
    }

    public function index()
    {
        $data['page_title'] = 'SP2B JKN';
        $this->template->set('title', 'SP2B JKN');
        $this->template->load('template', 'tukd/jkn/index', $data);
    }

    public function loaddata()
    {
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $sp3b = $this->input->post('sp3b');
        $where    = '';
        if ($kriteria <> '') {
            $where = "AND (upper(no_sp3b) like upper('%$kriteria%') or no_sp2b like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%')) ";
        }
        if ($sp3b == 'JKN') {
            $sql = "SELECT TOP $rows *,(SELECT a.nm_skpd FROM ms_skpd_jkn a where a.kd_skpd=jkn_trhlpj.kd_skpd) as nm_skpd FROM jkn_trhlpj WHERE jenis = '1' $where 
            AND no_lpj NOT IN (SELECT TOP $offset no_lpj FROM jkn_trhlpj WHERE jenis = '1' $where ORDER BY tgl_lpj,no_lpj) ORDER BY tgl_lpj,no_lpj";
            $sql1 = "SELECT count(*) as tot from jkn_trhlpj WHERE jenis = '1' $where";
        } else if ($sp3b == 'BOK') {
            $sql = "SELECT TOP $rows *,(SELECT a.nm_skpd FROM ms_skpd_jkn a where a.kd_skpd=bok_trhlpj.kd_skpd) as nm_skpd FROM bok_trhlpj WHERE jenis = '1' $where 
            AND no_lpj NOT IN (SELECT TOP $offset no_lpj FROM bok_trhlpj WHERE jenis = '1' $where ORDER BY tgl_lpj,no_lpj) ORDER BY tgl_lpj,no_lpj";
            $sql1 = "SELECT count(*) as tot from bok_trhlpj WHERE jenis = '1' $where";
        }

        $query11 = $this->db->query($sql1);
        $total = $query11->row();
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $row[] = array(
                'id' => $ii,
                'kd_skpd'    => $resulte['kd_skpd'],
                'nm_skpd'    => $resulte['nm_skpd'],
                'ket'   => $resulte['keterangan'],
                'no_lpj'   => $resulte['no_lpj'],
                'tgl_lpj'      => $resulte['tgl_lpj'],
                'tgl_sp2b'   => $resulte['tgl_sp2b'],
                'no_sp2b'      => $resulte['no_sp2b'],
                'no_sp3b'      => $resulte['no_sp3b'],
                'status'      => $resulte['status'],
                'tgl_awal'      => $resulte['tgl_awal'],
                'tgl_akhir'      => $resulte['tgl_akhir'],
                'keterangansp2b' => $resulte['ket_sp2b'],
            );
            $ii++;
        }

        $result["total"] = $total->tot;
        $result["rows"] = $row;
        $query1->free_result();
        echo json_encode($result);
    }

    public function loadingdata()
    {
        $no_lpj  = $this->input->post('no_lpj');
        $sp3b  = $this->input->post('sp3b');
        $kd  = $this->input->post('kd');
        if ($sp3b == 'JKN') {
            $sql = "SELECT kd_sub_kegiatan, kd_rek6, nm_rek6, nilai FROM jkn_trlpj WHERE kd_skpd='$kd' AND no_lpj='$no_lpj'";
        } else if ($sp3b == 'BOK') {
            $sql = "SELECT kd_sub_kegiatan, kd_rek6, nm_rek6, nilai FROM bok_trlpj WHERE kd_skpd='$kd' AND no_lpj='$no_lpj'";
        }

        $query1 = $this->db->query($sql);
        $result = array();
        $topendapatan = 0;
        $tolbelanja = 0;
        foreach ($query1->result_array() as $resulte) {
            if (substr($resulte['kd_rek6'], 0, 1) == '4') {
                $topendapatan += $resulte['nilai'];
            } else if (substr($resulte['kd_rek6'], 0, 1) == '5') {
                $tolbelanja += $resulte['nilai'];
            }
            $result[] = array(
                'kd_sub_kegiatan'    => $resulte['kd_sub_kegiatan'],
                'kd_rek6'    => $resulte['kd_rek6'],
                'nm_rek6'    => $resulte['nm_rek6'],
                'nilai'    => $resulte['nilai'],
                'totpendapatan' => $topendapatan,
                'totbelanja' => $tolbelanja
            );
        }
        echo json_encode($result);
        $query1->free_result();
    }

    public function simpan_data()
    {
        $sp3b = $this->input->post('sp3b');
        $sp2b = $this->input->post('sp2b');
        $skpd = $this->input->post('skpd');
        $no_sp3b = $this->input->post('no_sp3b');
        $tgl_sp2b = $this->input->post('tgl_sp2b');
        $keterangan = $this->input->post('keterangan');
        $this->db->trans_start();
        if ($sp3b == 'JKN') {
            $sql2 = "UPDATE a SET a.no_sp2b='$sp2b', a.tgl_sp2b='$tgl_sp2b', a.status='1', a.ket_sp2b='$keterangan' FROM jkn_trhlpj a WHERE a.no_sp3b='$no_sp3b' and a.kd_skpd='$skpd'";
        } else if ($sp3b == 'BOK') {
            $sql2 = "UPDATE a SET a.no_sp2b='$sp2b', a.tgl_sp2b='$tgl_sp2b', a.status='1', a.ket_sp2b='$keterangan' FROM bok_trhlpj a WHERE a.no_sp3b='$no_sp3b' and a.kd_skpd='$skpd'";
        }

        $asg2 = $this->db->query($sql2);
        $this->db->trans_complete();
        if ($asg2) {
            echo '1';
        } else {
            echo '2';
        }
    }

    public function cek_data()
    {
        $no_sp2b = $this->input->post('no_sp2b');
        $sp3b = $this->input->post('sp3b');
        // echo ($sp3b);
        // return;
        $data = false;
        if ($sp3b == 'JKN') {
            $data = $this->db->query("SELECT COUNT(*) as no_sp2b FROM jkn_trhlpj WHERE no_sp2b='$no_sp2b'");
        } else if ($sp3b == 'BOK') {
            $data = $this->db->query("SELECT COUNT(*) as no_sp2b FROM bok_trhlpj WHERE no_sp2b='$no_sp2b'");
        }
        $hasil = $data->row();
        $no_sp2b1 = $hasil->no_sp2b;
        if ($no_sp2b1 > 0) {
            echo ('1');
        } else {
            echo ('0');
        }
    }


    public function edit_data()
    {
        $sp3b = $this->input->post('sp3b');
        $sp2b = $this->input->post('sp2b');
        $no_sp2bhidden = $this->input->post('no_sp2bhidden');
        $skpd = $this->input->post('skpd');
        $no_sp3b = $this->input->post('no_sp3b');
        $tgl_sp2b = $this->input->post('tgl_sp2b');
        $keterangan = $this->input->post('keterangan');
        $this->db->trans_start();
        if ($sp3b == 'JKN') {
            $sql2 = "UPDATE a SET a.no_sp2b='$sp2b', a.tgl_sp2b='$tgl_sp2b', a.status='1', a.ket_sp2b='$keterangan' FROM jkn_trhlpj a WHERE a.no_sp2b='$no_sp2bhidden' and a.kd_skpd='$skpd'";
        } else if ($sp3b == 'BOK') {
            $sql2 = "UPDATE a SET a.no_sp2b='$sp2b', a.tgl_sp2b='$tgl_sp2b', a.status='1', a.ket_sp2b='$keterangan' FROM bok_trhlpj a WHERE a.no_sp2b='$no_sp2bhidden' and a.kd_skpd='$skpd'";
        }

        $asg2 = $this->db->query($sql2);
        $this->db->trans_complete();
        if ($asg2) {
            echo '1';
        } else {
            echo '2';
        }
    }

    public function delete_data()
    {
        $sp3b = $this->input->post('sp3b');
        $sp2b = $this->input->post('sp2b');
        $skpd = $this->input->post('skpd');
        $no_sp3b = $this->input->post('no_sp3b');
        $tgl_sp2b = $this->input->post('tgl_sp2b');
        $this->db->trans_start();
        if ($sp3b == 'JKN') {
            $sql2 = "UPDATE a SET a.no_sp2b= NULL, a.tgl_sp2b= NULL, a.status='0', a.ket_sp2b= NULL FROM jkn_trhlpj a WHERE a.no_sp3b='$no_sp3b' and a.kd_skpd='$skpd'";
        } else if ($sp3b == 'BOK') {
            $sql2 = "UPDATE a SET a.no_sp2b= NULL, a.tgl_sp2b= NULL, a.status='0', a.ket_sp2b= NULL FROM bok_trhlpj a WHERE a.no_sp3b='$no_sp3b' and a.kd_skpd='$skpd'";
        }

        $asg2 = $this->db->query($sql2);
        $this->db->trans_complete();
        if ($asg2) {
            echo '1';
        } else {
            echo '2';
        }
    }

    function  tanggal_format_indonesia($tgl)
    {
        $tanggal  = explode('-', $tgl);
        $bulan  = $this->getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2] . ' ' . $bulan . ' ' . $tahun;
    }

    public function cetakansp2b()
    {
       
        $jenissp3b = $_GET['jenissp3b'];
        $no_sp2b = $_GET['no_sp2b'];
        $tgl = $_GET['tgl'];
        $ttd = $_GET['ttd'];
        //$no_lpj = $_REQUEST['no_lpj'];
        // $ttd = $_REQUEST['ttd'];
        $lcskpd = $_GET['kdskpd'];
        //$lcsp2b = $_REQUEST['cspp'];
        $baris = $_GET['baris'];
        $tahun = $this->session->userdata('pcThang');
        //echo($no_sp3b);
        //echo('hakam');
        // Jenis

        // nama BUD
        $namabud = "SELECT nama, nip,jabatan, pangkat FROM ms_ttd WHERE nip='$ttd'";
        $nmbud = $this->db->query($namabud);
        $g = 0;
        foreach ($nmbud->result_array() as $row) {
            $g++;
            $nmbud = $row['nama'];
            $nipbud = $row['nip'];
            $jabatanbud = $row['jabatan'];
            $pangkatbud = $row['pangkat'];
        }

        // // No LPJ
        // $nolpj = "SELECT no_sp2b, tgl_Sp2b, kd_skpd FROM jkn_trhlpj WHERE no_sp2b='$no_sp2b' AND kd_skpd='$lcskpd' ";
        // $no_lpj = $this->db->query($nolpj);
        // $g=0;
        // foreach ($no_lpj->result_array() as $row){
        //     $g++;
        //     $no_sp2b = $row['no_sp2b'];
        //     $tgl_Sp2b = $row['tgl_Sp2b'];
        //     $kd_skpd = $row['kd_skpd'];
        // }

        // Saldo Awal
        // $saldoawal = "SELECT SUM(sld_awal + sld_awal_bank) as saldo FROM ms_skpd_jkn WHERE kd_skpd='$lcskpd'";
        // $sldawal = $this->db->query($saldoawal);

        // $ha2 = 0;
        // $tox_awal = "SELECT SUM(ISNULL(nilai,0)) AS jumlah FROM jkn_saldo_awal_sp2b where kd_skpd='$lcskpd'";
        // $ha2 = $this->db->query($tox_awal);
        // $ha2 = $ha2->row('jumlah');

        // $ha2 = $this->db->query($nilaisebelumnya);
        // $trh4 = $ha2->row();

        // $ha3 = $trh4->pengeluaran;
        //$aa = $trh4->sel;
        //$saldoawal = $saldoawal + $tox;

        

        // $h = 0;
        // foreach ($sldawal->result_array() as $row) {
        //     $h++;
        //     $slaw = $row['saldo'];
        //     // $namaa = $row['nm_skpd'];
        // }
        //$ha3 = $ha2 + $h;

        if ($jenissp3b == 'JKN') {
            $judul = 'JKN FKTP';

            $nomor = $this->db->query("SELECT no_sp2b, tgl_sp2b,kd_skpd, no_sp3b, no_lpj, tgl_lpj, tgl_awal,tgl_akhir, (SELECT nm_skpd FROM ms_skpd_jkn WHERE kd_skpd=jkn_trhlpj.kd_skpd)as nm_skpd FROM jkn_trhlpj WHERE kd_skpd='$lcskpd' AND no_sp2b='$no_sp2b'")->row();
            $data1 = date('m', strtotime($nomor->tgl_awal));
            $dataaa =  $this->db->query("SELECT SUM(x.terima - keluar) as sel, SUM(terima) as terima, SUM(keluar) as keluar FROM(
                SELECT ISNULL(SUM(nilai),0) as terima, 0 as keluar FROM jkn_tr_terima WHERE kd_skpd='$lcskpd' AND tgl_terima<'$nomor->tgl_awal' GROUP BY tgl_terima, kd_skpd 
                UNION ALL
                SELECT 0 as terima, SUM(a.nilai) as keluar FROM jkn_trdtransout a INNER JOIN jkn_trhtransout b ON b.kd_skpd=a.kd_skpd AND a.no_bukti=b.no_bukti WHERE a.kd_skpd='$lcskpd' AND b.tgl_bukti<'$nomor->tgl_awal'
                UNION ALL
                SELECT SUM(a.nilai) as terima, 0 as keluar FROM jkn_saldo_awal a WHERE a.kd_skpd='$lcskpd'
                -- UNION ALL
                -- SELECT SUM(a.nilai) as terima, 0 as keluar FROM jkn_trdtrmpot a INNER JOIN jkn_trhtrmpot b ON b.kd_skpd=a.kd_skpd AND a.no_bukti=b.no_bukti WHERE a.kd_skpd='$lcskpd' AND b.tgl_bukti<'$nomor->tgl_awal'
                -- UNION ALL
                -- SELECT 0 as terima, SUM(a.nilai) as keluar FROM jkn_trdstrpot a INNER JOIN jkn_trhstrpot b ON b.kd_skpd=a.kd_skpd AND a.no_bukti=b.no_bukti WHERE a.kd_skpd='$lcskpd' AND b.tgl_bukti<'$nomor->tgl_awal'
                )x")->row();
            $nilpendapatan = 0;
            $nilbelanja = 0;
            $hasil = 0;
            $data = $this->db->query("SELECT SUM(a.nilai) as nilai, a.kd_rek6 FROM jkn_trlpj a INNER JOIN jkn_trhlpj b ON b.no_lpj=a.no_lpj AND a.kd_skpd=b.kd_skpd WHERE b.no_sp2b='$no_sp2b' AND a.kd_skpd='$lcskpd'
            GROUP BY a.kd_rek6");
            foreach ($data->result_array() as $row) {
                if (substr($row['kd_rek6'], 0, 1) == '4') {
                    $nilpendapatan += $row['nilai'];
                } else if (substr($row['kd_rek6'], 0, 1) == '5') {
                    $nilbelanja += $row['nilai'];
                }
            }
            $hasil = $dataaa->sel;
        } else if ($jenissp3b == 'BOK') {
            $judul = 'BOK';
            $nomor = $this->db->query("SELECT no_sp2b, tgl_sp2b,kd_skpd, no_sp3b, no_lpj, tgl_lpj, tgl_awal,tgl_akhir, (SELECT nm_skpd FROM ms_skpd_jkn WHERE kd_skpd=bok_trhlpj.kd_skpd)as nm_skpd FROM bok_trhlpj WHERE kd_skpd='$lcskpd' AND no_sp2b='$no_sp2b'")->row();
            $data1 = date('m', strtotime($nomor->tgl_awal));
            $dataaa =  $this->db->query("SELECT SUM(x.terima - keluar) as sel, SUM(terima) as terima, SUM(keluar) as keluar FROM(
                SELECT ISNULL(SUM(nilai),0) as terima, 0 as keluar FROM bok_tr_terima WHERE kd_skpd='$lcskpd' AND tgl_terima<'$nomor->tgl_awal' GROUP BY tgl_terima, kd_skpd 
                UNION ALL
                SELECT 0 as terima, SUM(a.nilai) as keluar FROM bok_trdtransout a INNER JOIN bok_trhtransout b ON b.kd_skpd=a.kd_skpd AND a.no_bukti=b.no_bukti WHERE a.kd_skpd='$lcskpd' AND b.tgl_bukti<'$nomor->tgl_awal'
                UNION ALL
                SELECT SUM(a.nilai) as terima, 0 as keluar FROM bok_saldo_awal a WHERE a.kd_skpd='$lcskpd'
                -- UNION ALL
                -- SELECT SUM(a.nilai) as terima, 0 as keluar FROM bok_trdtrmpot a INNER JOIN bok_trhtrmpot b ON b.kd_skpd=a.kd_skpd AND a.no_bukti=b.no_bukti WHERE a.kd_skpd='$lcskpd' AND b.tgl_bukti<'$nomor->tgl_awal'
                -- UNION ALL
                -- SELECT 0 as terima, SUM(a.nilai) as keluar FROM bok_trdstrpot a INNER JOIN bok_trhstrpot b ON b.kd_skpd=a.kd_skpd AND a.no_bukti=b.no_bukti WHERE a.kd_skpd='$lcskpd' AND b.tgl_bukti<'$nomor->tgl_awal'
                )x");
            foreach ($dataaa->result_array() as $row) {
                $sel = $row['sel'];
                $terima = $row['terima'];
                $keluar = $row['keluar'];
            }
            //data
            $nilpendapatan = 0;
            $nilbelanja = 0;
            $hasil = 0;
            $data = $this->db->query("SELECT SUM(a.nilai) as nilai, a.kd_rek6 FROM bok_trlpj a INNER JOIN bok_trhlpj b ON b.no_lpj=a.no_lpj AND a.kd_skpd=b.kd_skpd WHERE b.no_sp2b='$no_sp2b' AND a.kd_skpd='$lcskpd'
                GROUP BY a.kd_rek6");
            foreach ($data->result_array() as $row) {
                if (substr($row['kd_rek6'], 0, 1) == '4') {
                    $nilpendapatan += $row['nilai'];
                } else if (substr($row['kd_rek6'], 0, 1) == '5') {
                    $nilbelanja += $row['nilai'];
                }
            }
            $hasil = $sel;
        }




        $cRet = '';

        $cRet = '<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="1" cellspacing="2" cellpadding="1" align=center>
 
 <TR>
     <TD align="left" style="border-right:none;">
         <img src="' . base_url() . '/image/logo_melawi.png"   width="75" height="80" align="left" style="margin:5px"/>
     </TD>
     <TD style="border-left:none;">
            <align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>PEMERINTAH KABUPATEN MELAWI</b><br>
            <align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>BADAN PENGELOLA KEUANGAN</b><br>
            <align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>DAN ASET DAERAH</b>
            <align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<p><font size="3">Jl. Provinsi Nanga Pinoh-Kotabaru Km. 7, Nanga Pinoh</font></p><br>
            </align>                                             
     </TD>

     <TD rowspan="2" style="border-left:none;" >
     <align="center">&nbsp;&nbsp;&nbsp;&nbsp;<b><font size="3">SURAT PENGESAHAN PENDAPATAN DAN</font></b>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b><font size="3"> BELANJA (SP2B) ' . $judul . '</font></b><br>
     Nama BUD       : ' . $nmbud . ' <BR>
     Tanggal        :  ' . $this->support->tanggal_format_indonesia($nomor->tgl_sp2b) . '  <BR>
     Nomor          :   ' . $nomor->no_sp2b . '<BR>
     Tahun Anggaran :  ' . $tahun . ' <BR>
     </align>                                             
     </TD>
 </TR>     
 ';

        //  $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
        //  <TR>
        //      <TD align="left" >No SP3BP :   </TD> 
        // <TR> 
        //      <TD align="left" >Tanggal :   </TD>  
        // </TR>
        // <TR>             
        //      <TD align="left" >Kode SKPD :   </TD>                  
        //  </TR>  


        //  </TABLE>';

        $cRet .= '
 <TR>
     <TD align="left" colspan="2">
     No SP3BP  :  ' . $nomor->no_sp3b . '<BR>
     Tanggal   :   ' . $this->support->tanggal_format_indonesia($nomor->tgl_lpj) . ' <BR>
     Kode SKPD :  ' . $nomor->kd_skpd . ' <BR>
     Nama SKPD : ' . $nomor->nm_skpd . '  <BR>

     </TD>                  
 </TR></TABLE>';

        $cRet .= '<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">

 
 <TR>
        <td><br><p>Telah disahkan pendapatan dan belanja periode ' . $this->support->getBulan($data1) . ' sejumlah : </p></td> 
    </tr>
     <tr>
     <TD align="left">&nbsp;&nbsp;&nbsp;Saldo Awal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Rp. ' . number_format($hasil, 2, ",", ".") . '  </TD>                     
 </TR>  
 <tr>
     <TD align="left">&nbsp;&nbsp;&nbsp;Pendapatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp. ' . number_format($nilpendapatan, 2, ",", ".") . ' </TD>                     
 </TR> 
 <tr>
     <TD align="left">&nbsp;&nbsp;&nbsp;Belanja &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: Rp. ' . number_format($nilbelanja, 2, ",", ".") . '  </TD>                     
 </TR> 
 <tr>
     <TD align="left" >&nbsp;&nbsp;&nbsp;Saldo Akhir &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : Rp. ' . number_format($hasil + $nilpendapatan - $nilbelanja, 2, ",", ".") . ' </TD>                     
 </TR> 
 
 </TABLE>';
        $cRet .= '<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
 <TR>
     <TD align="left" >  </TD>   
                         
 </TR>   
 <tr>
 <td align="center" width="25%">&nbsp;</td>                    
 <td align="center" width="25%">&nbsp;</td>
</tr>

<tr>
 <td align="center" width="25%"></td>                    
 <td align="center" width="25%">Melawi, ' . $this->support->tanggal_format_indonesia($tgl) . '</td>
</tr>

<tr>
 <td align="center" width="25%"></td>                    
 <td align="center" width="25%">' . $jabatanbud . '</td>
</tr>
<tr>
 <td align="center" width="25%">&nbsp;</td>                    
 <td align="center" width="25%"></td>
</tr>                              
<tr>
 <td align="center" width="50%">&nbsp;</td>                    
 <td align="center" width="50%">&nbsp;</td>
</tr>
<tr>
 <td align="center" width="50%">&nbsp;</td>                    
 <td align="center" width="50%">&nbsp;</td>
</tr>
<tr>
 <td align="center" width="50%">&nbsp;</td>                    
 <td align="center" width="50%">&nbsp;</td>
</tr>
<tr>
 <td align="center" width="25%"> </td>                    
 <td align="center" width="25%"><b><u>' . $nmbud . '</u></b><br>
 ' . $pangkatbud . ' <br>
 NIP. ' . $nipbud . ' </td>
</tr>                              
<tr>
 <td align="center" width="25%">&nbsp;</td>                    
 <td align="center" width="25%">&nbsp;</td>
</tr>
   
 </TABLE>';
        // echo $cRet;
        $this->support->_mpdf('', $cRet, 10, 5, 5, '0');
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
}
