<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Cetak_b9 extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
    }

    function config_tahun()
    {
        $result = array();
        $tahun  = $this->session->userdata('pcThang');
        $result = $tahun;
        echo json_encode($result);
    }

    function  tanggal_format_indonesia($tgl)
    {
        $tanggal  = explode('-', $tgl);
        $bulan  = $this->getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2] . ' ' . $bulan . ' ' . $tahun;
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


    function cetakb9($ctk = '', $ttd = '', $tgl = '', $number = '', $no_halaman = '')
    {

        $sebelumharini = '';

        $klr_sbl1 = 0;
        $trm_sbl1 = 0;
        //$lcttd = str_replace('abc',' ',$this->uri->segment(4));
        $lcttd = str_replace('123456789', ' ', $this->uri->segment(4));
        $tgl = $this->uri->segment(5);
        $cetk = $this->uri->segment(10);
        $tgl1       = $this->uri->segment(8);
        $tgl2       = $this->uri->segment(9);
        $st_renk    = $this->uri->segment(11);

        $z1 = date("m", strtotime($tgl1));



        $skpd = '5.02.0.00.0.00.02.0000';
        if ($cetk == '1') {
            $tanggal = $this->tanggal_format_indonesia($tgl);

            $z = strtotime("-1 day", strtotime($tgl));
            $n = date("Y-m-d", $z);
            $tanggalsbl = $this->tanggal_format_indonesia($n);
            // $tglA = date('Y-m-d', strtotime('-1 day', strtotime($tgl)));
            // echo($tglA);

            $where = "a.tgl_kas = '$tgl'";
            $where2 = "AND a.tgl_kas_bud = '$tgl'";
            $where3 = "x.tanggal = '$tgl'";
            $where4 = "w.tanggal = '$tgl'";
            $where5 = "AND a.tgl_kas<='$tgl'";
            $where6 = "AND a.tgl_kas_bud<='$tgl'";
            $where7 = "x.tanggal <= '$tgl'";
            $where8 = "w.tanggal <= '$tgl'";
            $where9 = "AND a.tgl_kas <= '$tgl'";
            $whereA = "AND a.tgl_kas_bud <='$tgl'";
            $whereB = "AND a.tgl_kas <= '$tgl'";
        } else {
            $tanggal1 = $this->tanggal_format_indonesia($tgl1);

            $z = strtotime("-1 day", strtotime($tgl1));
            $n = date("Y-m-d", $z);
            $tanggalsbl = $this->tanggal_format_indonesia($n);

            $tanggal2 = $this->tanggal_format_indonesia($tgl2);

            $tglB = date('Y-m-d', strtotime('-1 day', strtotime($tgl1)));
            $tglC = date('Y-m-d', strtotime('-1 day', strtotime($tgl2)));

            $z2 = strtotime("-1 day", strtotime($tgl2));
            $n2 = date("Y-m-d", $z2);
            $tanggalsbl2 = $this->tanggal_format_indonesia($n2);

            $where = "a.tgl_kas between '$tgl1' AND '$tgl2'";
            $where2 = "AND a.tgl_kas_bud between '$tgl1' AND '$tgl2'";
            $where3 = "x.tanggal between '$tgl1' AND '$tgl2'";
            $where4 = "w.tanggal between '$tgl1' AND '$tgl2'";
            $where5 = "AND a.tgl_kas<='$tgl2'";
            $where6 = "AND a.tgl_kas_bud<='$tgl2'";
            $where7 = "x.tanggal <= '$tgl2'";
            $where8 = "w.tanggal <= '$tgl2'";
            $where9 = "AND a.tgl_kas <= '$tgl2'";
            $whereA = "AND a.tgl_kas_bud <= '$tgl2'";
            $whereB = "AND a.tgl_kas <= '$tgl2'";
        }


        $thn_ang = $this->session->userdata('pcThang');
        $prv = $this->db->query("SELECT top 1 * from sclient");
        $prvn = $prv->row();
        $prov = $prvn->provinsi;
        $daerah = $prvn->daerah;
        $thn     = $prvn->thn_ang;

        $sqlttd1 = "SELECT TOP 1 nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$lcttd'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd) {
            $nip = $rowttd->nip;
            $nama1 = $rowttd->nm;
            $jabatan  = $rowttd->jab;
            $pangkat = $rowttd->pangkat;
        }

        $sqlx = "SELECT * from buku_kas";
        $sqlx1 = $this->db->query($sqlx);
        foreach ($sqlx1->result() as $rowttd) {
            $nomor = $rowttd->nomor;
            $uraian = $rowttd->uraian;
            $nilaiu = $rowttd->nilai;
            $nilaiu_x = number_format($nilaiu, "2", ",", ".");
        }

        $nm_skpd = $this->tukd_model->get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');

        $cRet = "";
        $cRet .= "<table style=\"border-collapse:collapse;font-family: Times New Roman; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">

                        <tr>
                        <td align=\"center\" style=\"font-size:14px\" width=\"93%\"><strong>PEMERINTAH DAERAH KABUPATEN MELAWI </strong></td>
                        </tr>
                        <tr>
                        <td align=\"center\" style=\"font-size:14px\" ><strong>BUKU KAS PENERIMAAN DAN PENGELUARAN</strong></td>
                        </tr>
                        <tr>
                        <td align=\"center\" style=\"font-size:14px\" ><strong>TAHUN ANGGARAN 2022</strong></td>
                        </tr>
                        <tr>
                        <td align=\"left\" style=\"font-size:14px\" ><strong>&nbsp;</strong></td>
                        </tr>
                        </table>";
        if ($st_renk != '1') {
            $where10 = 'Jenis Rekening : ' . $st_renk . '';
        } else {
            $where10 = '';
        }

        if ($cetk == '1') {
            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\">Per tanggal " . $this->tukd_model->tanggal_format_indonesia($tgl) . " </td>
            </tr>
            
            </table>
            <br><br>
            ";
        } else {
            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\">" . $this->tukd_model->tanggal_format_indonesia($tgl1) . " SD " . $this->tukd_model->tanggal_format_indonesia($tgl2) . " </td>
            </tr>
            
            </table>
            <br><br>
            ";
        }

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
                <thead>";
        if ($st_renk != '1') {
            $cRet .= "<tr>
                    <td colspan='6' width=\"23%\" align=\"left\" style=\"font-size:14px;border-top:none;\"><b>$where10</b></td>
                    </tr>";
        }
        $cRet .= "<tr>
                    <td width=\"5%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">No. Kas</TD>
                    <td width=\"23%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Uraian <br>Penerimaan Dan Pengeluaran</TD>
                    <td width=\"15%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Kode Rekening</TD>
                    <td width=\"25%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Sub Rincian Objek <br>Penerimaan Dan Pengeluaran</TD>
                    <td width=\"10%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Jumlah Rincian</TD>
                    <td width=\"15%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Penerimaan</TD>
                    <td width=\"15%\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Pengeluaran</TD>
                    
                    
                </tr>
                </thead>
                <tfoot>
                    <tr>
                        <td colspan=\"6\" style=\"border:solid 1px white;border-top:solid 1px black;\"></td>
                    </tr>
                </tfoot>
               ";

        // if($cetk=='1'){
        //  $cRet .="<tr>
        //      <td align=\"center\"></td>
        //      <td align=\"left\">Tanggal : $tanggal</td>
        //      <td align=\"center\"></td>
        //      <td align=\"center\"></td>
        //  </tr>
        //  </thead>";
        // }else{
        //  $cRet .="<tr>
        //      <td align=\"center\"></td>
        //      <td align=\"left\">Tanggal : $tanggal1 s.d $tanggal2</td>
        //      <td align=\"center\"></td>
        //      <td align=\"center\"></td>
        //  </tr>
        //  </thead>";
        // }



        if ($tgl = $thn_ang + '01-01') {
            $saldo = "select '4' kd_rek, 'SALDO AWAL' nama, nilai , 1 jenis
            from buku_kas
            UNION ALL";
        } else {

            $saldo = "";
        }

        $sql        = "SELECT count(*) as tot from pengeluaran_non_sp2d x WHERE  $where3";

        $query1     = $this->db->query($sql);
        $total      = $query1->row();
        $totalrow   = $total->tot;

        if ($totalrow > 0) {
            $keluarnonsp2d = "UNION ALL 
            SELECT CAST(nomor as VARCHAR) as nokas,CAST(nomor as VARCHAR) as urut, keterangan+'. Rp. ' as ket,'' kode, 'PENGELUARAN NON SP2D' as nmrek,0 as terima, isnull(SUM(x.nilai), 0) AS keluar, 2 jenis, isnull(SUM(x.nilai), 0) as netto, '' as sp, '' as rek_bank FROM pengeluaran_non_sp2d x WHERE $where3 group by nomor,keterangan";
        } else {
            $keluarnonsp2d = "";
        }


        $sql2       = "SELECT count(*) as tot from penerimaan_non_sp2d w WHERE $where4 and jenis='1'";
        $query2     = $this->db->query($sql2);
        $total2         = $query2->row();
        $totalrow2  = $total2->tot;
        // echo $totalrow2;
        if ($totalrow2 != 0) {
            $masuknonsp2d = "UNION ALL
                            SELECT
                                CAST(nomor as VARCHAR) as nokas,CAST(nomor as VARCHAR) as urut,keterangan as ket,''kode,'Deposito'nmrek,
                                isnull(SUM(w.nilai),0) AS terima,0 as keluar,
                                1 jenis, 0 as netto, '' as sp, '' as rek_bank
                            FROM
                                penerimaan_non_sp2d w
                            WHERE
                            $where4  
                            AND w.jenis='1'
                            group by nomor,keterangan";
        } else {
            $masuknonsp2d = "";
        }


        $sql3       = "SELECT count(*) as tot from penerimaan_non_sp2d w WHERE $where4 and jenis='2'";
        $query3     = $this->db->query($sql3);
        $total3         = $query3->row();
        $totalrow3  = $total3->tot;
        if ($totalrow3 > 0) {
            $masuknonsp2d2 = "UNION ALL
                            SELECT
                                CAST(nomor as VARCHAR) as nokas,CAST(nomor as VARCHAR) as urut,keterangan as ket,'-'kode,'Penerimaan NON SP2D' as nmrek,
                                isnull(SUM(w.nilai), 0) AS terima,0 as keluar,
                                1 jenis, 0 netto, ''sp,'' as rek_bank
                            FROM
                                penerimaan_non_sp2d w
                            WHERE $where4
                            AND w.jenis='2'
                            group by nomor,keterangan";
        } else {
            $masuknonsp2d2 = "";
        }


        // if ($st_renk=='3001006966'){
        //     $rekkk="300100";
        //     $st_="AND left(no_rek,6)='$rekkk'";
        // }else if($st_renk=='3001000016'){
        //     $rekkk="30010000";
        //     $st_="AND left(no_rek,8)='$rekkk'";
        // }else{
        //     $st_="";
        // }

        if ($st_renk == '1') {
         $sql = "SELECT * from(
                    SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6
                        ,SUM(b.rupiah) terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp, a.rek_bank as rek_bank
                        FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
                        WHERE LEFT(b.kd_rek6,1) IN ('4') AND $where 
                        GROUP BY a.no_kas,keterangan,rek_bank
                        UNION ALL
                        SELECT '',CAST(a.no_kas as VARCHAR) as urut,keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, c.nm_rek6 as nm_rek6
                        ,b.rupiah as terima,0 as keluar, 1 jenis, 0 netto, ''as sp, a.rek_bank as rek_bank
                        FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
                        LEFT JOIN ms_rek6 c ON b.kd_rek6=c.kd_rek6
                        WHERE LEFT(b.kd_rek6,1) IN ('4') AND $where
                        UNION ALL
                        -- LAIN-LAIN PENDAPATAN ASLI DAERAH YANG SAH
                        SELECT  CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,a.keterangan+'. Rp. ' as uraian,'' as kode, ''as nm_rek6
                        ,0 as terima,0 as keluar, 1 jenis,SUM(rupiah) netto, '' as sp, a.rek_bank as rek_bank
                        FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
                        WHERE LEFT(b.kd_rek6,1) IN ('5','1') and pot_khusus=3 AND $where  
                        GROUP BY a.no_kas,rek_bank,keterangan,b.kd_sub_kegiatan,b.kd_rek6
                        UNION ALL
                        SELECT  '' as nokas,CAST(a.no_kas as VARCHAR) as urut,a.keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, 'Lain-lain PAD yang sah'as nm_rek6
                        ,b.rupiah as terima,0 as keluar, 1 jenis,0 netto, '' as sp, a.rek_bank as rek_bank
                        FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
                        WHERE LEFT(b.kd_rek6,1) IN ('5','1') and pot_khusus=3 AND $where 
                        UNION ALL
                        -- CONTRA POST
                        SELECT  CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,a.keterangan+'. Rp. ' as uraian,'' as kode, '' as nama
                        ,SUM(b.rupiah) as terima,0 as keluar, 1 jenis, SUM(rupiah) netto, '' as sp, a.rek_bank as rek_bank
                        FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
                        WHERE LEFT(b.kd_rek6,1) IN ('5','1') and a.jns_trans!='1' and pot_khusus<>3 AND $where 
                        group by a.no_kas,rek_bank,keterangan,b.kd_sub_kegiatan
                        UNION ALL
                        SELECT  '' as nokas,CAST(a.no_kas as VARCHAR) as urut,a.keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, 'CONTRA POST' as nama
                        ,b.rupiah as terima,0 as keluar, 1 jenis, 0 netto, '' as sp, a.rek_bank as rek_bank
                        FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
                        WHERE LEFT(b.kd_rek6,1) IN ('5','1') and a.jns_trans!='1' and pot_khusus<>3 AND $where
                        UNION ALL
                        SELECT CAST(a.no_kas_bud as VARCHAR) as no_kas,CAST(a.no_kas_bud as VARCHAR) as urut,'No.SP2D :'+' '+a.no_sp2d+'<br> '+a.keperluan+'Netto Rp. ' AS ket,
                        '' AS kode,'' AS nmrek,0 AS terima,SUM(b.nilai) AS keluar,2 AS jenis,
                        (SUM(b.nilai))-(SELECT ISNULL(SUM(nilai),0) FROM trspmpot WHERE no_spm=a.no_spm) AS netto,
                        '' as sp, '' as rek_bank
                        FROM trhsp2d a 
                        INNER JOIN trdspp b ON a.no_spp=b.no_spp
                        WHERE a.status_bud = '1' $where2
                        AND (a.sp2d_batal=0 OR a.sp2d_batal is NULL)
                        GROUP BY a.no_sp2d,no_kas_bud,a.keperluan,a.no_spm
                        UNION ALL
                        SELECT '' AS nokas,CAST(a.no_kas_bud as VARCHAR) AS urut,'' AS ket, case when b.kd_sub_kegiatan is null then a.kd_skpd+'.'+b.kd_rek6 else ( b.kd_sub_kegiatan+'.'+b.kd_rek6)
                        END  AS kode,b.nm_rek6 AS nmrek,0 AS terima,b.nilai AS keluar,2 AS jenis,0 as netto,''as sp, '' as rek_bank
                        FROM trdspp b INNER JOIN trhsp2d a ON a.no_spp=b.no_spp WHERE a.status_bud ='1' $where2
                        AND (a.sp2d_batal=0 OR a.sp2d_batal is NULL)
                
                        $keluarnonsp2d
                        UNION ALL
                        SELECT
                        CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,'RESTITUSI<br>'+keterangan+'. Rp. ','' as kode, '' as nm_rek6,
                        0 AS terima,0 keluar, 2 jenis,isnull(SUM(b.rupiah), 0) as netto,''sp, '' as rek_bank
                        FROM
                        trdrestitusi b inner join trhrestitusi a on a.kd_skpd=b.kd_skpd and a.no_kas=b.no_kas and a.no_sts=b.no_sts
                        WHERE a.jns_trans=3 and $where
                        group by a.no_kas,keterangan   
                        UNION ALL
                        SELECT
                        '' as nokas,CAST(a.no_kas as VARCHAR) as urut,''as ket,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, c.nm_rek6,0 terima,
                        isnull(b.rupiah, 0) AS keluar, 2 jenis,0 netto, ''sp, '' as rek_bank
                        FROM
                        trdrestitusi b inner join trhrestitusi a on a.kd_skpd=b.kd_skpd and a.no_kas=b.no_kas and a.no_sts=b.no_sts
                        left join ms_rek6 c on b.kd_rek6=c.kd_rek6 
                        WHERE a.jns_trans=3
                        and $where
                        UNION ALL 
                        SELECT CAST(w.no as VARCHAR) as no_kas, CAST(w.no as VARCHAR) as urut,'KOREKSI PENERIMAAN<br>'+keterangan as ket,kd_sub_kegiatan+'.'+kd_rek kode,nm_rek,
                        isnull(SUM(w.nilai),0) as terima,0 as keluar,
                        1 jenis,isnull(SUM(w.nilai),0) as netto,''sp, '' as rek_bank
                        FROM trkasout_ppkd w
                        WHERE
                        $where4
                        group by no,keterangan,kd_sub_kegiatan,kd_rek,nm_rek
                        
                        $masuknonsp2d
                        
                        $masuknonsp2d2
                        UNION ALL
                    -- UYHD
                    SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6 ,SUM(b.rupiah) terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE $where AND a.jns_trans='1' GROUP BY a.no_kas,keterangan,rek_bank 
                    UNION ALL 
                    SELECT '' no_kas,a.no_kas as urut,keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, 'UYHD' as nm_rek6 ,b.rupiah as terima,0 as keluar, 1 jenis, 0 netto, ''as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE $where AND a.jns_trans='1'
                    UNION ALL
                    -- PENGELUARAN LAIN LAIN
                    SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6 ,SUM(b.rupiah) terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE $where AND a.jns_trans='6' AND a.pot_khusus='0' AND a.rek_bank='$st_renk' GROUP BY a.no_kas,keterangan,rek_bank 
                    UNION ALL 
                    SELECT '' no_kas,a.no_kas as urut,keterangan as uraian,'01' as kode, 'Pengembaliantahunlalu' as nm_rek6 ,b.rupiah as terima,0 as keluar, 1 jenis, 0 netto, ''as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE $where AND a.jns_trans='6' AND a.pot_khusus='0' AND a.rek_bank='$st_renk'
                    ) a order by urut,kode,jenis";
        } else if ($st_renk == '4501002886') {
            $sql = "SELECT * from(
                SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6
                    ,SUM(b.rupiah) terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp, a.rek_bank as rek_bank
                    FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
                    WHERE LEFT(b.kd_rek6,1) IN ('4') AND $where AND a.rek_bank='$st_renk' 
                    GROUP BY a.no_kas,keterangan,rek_bank
                    UNION ALL
                    SELECT '',a.no_kas as urut,keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, c.nm_rek6 as nm_rek6
                    ,b.rupiah as terima,0 as keluar, 1 jenis, 0 netto, ''as sp, a.rek_bank as rek_bank
                    FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
                    LEFT JOIN ms_rek6 c ON b.kd_rek6=c.kd_rek6
                    WHERE LEFT(b.kd_rek6,1) IN ('4') AND $where AND a.rek_bank='$st_renk' 
                    UNION ALL
                    -- LAIN-LAIN PENDAPATAN ASLI DAERAH YANG SAH
                    SELECT  CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,a.keterangan+'. Rp. ' as uraian,'' as kode, ''as nm_rek6
                    ,0 as terima,0 as keluar, 1 jenis,SUM(rupiah) netto, '' as sp, a.rek_bank as rek_bank
                    FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
                    WHERE LEFT(b.kd_rek6,1) IN ('5','1') and pot_khusus=3 AND $where AND a.rek_bank='$st_renk'
                    GROUP BY a.no_kas,rek_bank,keterangan,b.kd_sub_kegiatan,b.kd_rek6
                    UNION ALL
                    SELECT  '' as nokas,CAST(a.no_kas as VARCHAR) as urut,a.keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, 'Lain-lain PAD yang sah'as nm_rek6
                    ,b.rupiah as terima,0 as keluar, 1 jenis,0 netto, '' as sp, a.rek_bank as rek_bank
                    FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
                    WHERE LEFT(b.kd_rek6,1) IN ('5','1') and pot_khusus=3 AND $where AND a.rek_bank='$st_renk'
                    UNION ALL
                    -- CONTRA POST
                    SELECT  CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,a.keterangan+'. Rp. ' as uraian,'' as kode, '' as nama
                    ,SUM(b.rupiah) as terima,0 as keluar, 1 jenis, SUM(rupiah) netto, '' as sp, a.rek_bank as rek_bank
                    FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
                    WHERE LEFT(b.kd_rek6,1) IN ('5','1') and pot_khusus<>3 AND $where AND a.rek_bank='$st_renk'
                    group by a.no_kas,rek_bank,keterangan,b.kd_sub_kegiatan
                    UNION ALL
                    SELECT  '' as nokas,CAST(a.no_kas as VARCHAR) as urut,a.keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, 'CONTRA POST' as nama
                    ,b.rupiah as terima,0 as keluar, 1 jenis, 0 netto, '' as sp, a.rek_bank as rek_bank
                    FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd
                    WHERE LEFT(b.kd_rek6,1) IN ('5','1') AND pot_khusus<>3 AND $where AND a.rek_bank='$st_renk'
                    $masuknonsp2d
                        
                    $masuknonsp2d2
                    UNION ALL
                    -- UYHD
                    SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6 ,SUM(b.rupiah) terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE $where AND a.jns_trans='1' AND a.rek_bank='$st_renk' GROUP BY a.no_kas,keterangan,rek_bank 
                    UNION ALL 
                    SELECT '' no_kas,a.no_kas as urut,keterangan as uraian,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, 'UYHD' as nm_rek6 ,b.rupiah as terima,0 as keluar, 1 jenis, 0 netto, ''as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE $where AND a.jns_trans='1' AND a.rek_bank='$st_renk'
                    UNION ALL
                    -- PENGELUARAN LAIN LAIN
                    SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6 ,SUM(b.rupiah) terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE $where AND a.jns_trans='6' AND a.pot_khusus='0' AND a.rek_bank='$st_renk' GROUP BY a.no_kas,keterangan,rek_bank 
                    UNION ALL 
                    SELECT '' no_kas,a.no_kas as urut,keterangan as uraian,'01' as kode, 'Pengembalian tahun lalu' as nm_rek6 ,b.rupiah as terima,0 as keluar, 1 jenis, 0 netto, ''as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE $where AND a.jns_trans='6' AND a.pot_khusus='0' AND a.rek_bank='$st_renk'
                    ) a order by urut,kode,jenis";
        }

        $no = $number - 1;
        $net1 = 0;
        $totalnet = 0;
        $totalnet_luar = 0;

        $hasil = $this->db->query($sql);
        foreach ($hasil->result() as $row) {
            $kode   = $row->kode;
            $uraian = $row->uraian;
            $no_kas = $row->no_kas;
            $netto  = $row->netto;
            $sp     = $row->sp;
            $nama   = $row->nm_rek6;
            $nilaiterima  = $row->terima;
            $nilaikeluar  = $row->keluar;
            $jenis  = $row->jenis;

            /* if(($nomor==1) or ($nomor==2) or ($nomor==3) or ($nomor==4) or ($nomor==0) or ($nomor==5) or ($nomor==6) or ($nomor==7) or ($nomor==8) or ($nomor==9)){
            $terima=$nilai;
            $keluar=0;
            
        } else {
            $terima=0;
            $keluar=$nilai;
        } */

            // if($jenis=='1'){
            //  $terima=$nilai;
            //  if($terima<0){
            //      $a="(";
            //      $b=")";
            //      $nilaiterima = number_format($terima*-1,"2",",",".");
            //  }else{
            //      $a="";
            //      $b="";
            //      $nilaiterima = number_format($terima,"2",",",".");
            //  }

            //  $keluar=0;
            //  $nilaikeluar = number_format($keluar,"2",",",".");


            // }else{
            //  $terima=0;
            //  $nilaiterima = number_format($terima,"2",",",".");
            //  $keluar=$nilai;
            //  $nilaikeluar = number_format($keluar,"2",",",".");
            //  $a="";
            //  $b="";  
            // }



            $t_terima = 0;
            $t_luar = 0;
            $t_terima = $t_terima + $nilaiterima;
            $t_luar = $t_luar + $nilaikeluar;

            // $tot_trm=0; 
            // $tot_klr=0; 
            // $tot_trm=$tot_trm+$t_terima;
            // $tot_klr=$tot_klr+$t_luar;

            if ($netto == 0) {
                $net = '';
            } else {
                $net = number_format($netto, 2, ',', '.');
                $net1 = $netto;
            }
            $st = '';



            $no = $no + 1;

            $cRet .= "
                        <tr>
                            <td width=\"5%\" align=\"center\" style=\"font-size:12px;border-bottom:none;border-top:none\">$no_kas</TD>";
            if ($no_kas != '') {
                $totalnet = $totalnet + $row->terima;
                $totalnet_luar = $totalnet_luar + $row->keluar;
                $cRet .= "<td width=\"23%\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:solid 1 px black\">$uraian$net</TD>";
                $cRet .= "<td width=\"15%\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\">$kode</TD>
                            <td width=\"25%\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\">$nama</TD>
                            <td width=\"10%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\"></TD>
                            <td width=\"15%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\">" . number_format($t_terima, 2, ',', '.') . "</TD>
                            <td width=\"15%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\">" . number_format($t_luar, 2, ',', '.') . " </TD>
                        </tr>
                        ";
            } else {

                if ($jenis == '1') {
                    $cRet .= "<td width=\"23%\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></TD>";
                    $cRet .= "<td width=\"15%\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\">$kode</TD>
                            <td width=\"25%\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\">$nama</TD>
                            <td width=\"10%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\">" . number_format($nilaiterima, 2, ',', '.') . "</TD>
                                <td width=\"15%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\"></TD>
                                <td width=\"15%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\"></TD>
                                </tr>";
                } else {
                    $cRet .= "<td width=\"23%\" align=\"left\" style=\"font-size:12px;border-bottom:none;border-top:none\"></TD>";
                    $cRet .= "<td width=\"15%\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\">$kode</TD>
                                <td width=\"25%\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\">$nama</TD>
                                <td width=\"10%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\">" . number_format($nilaikeluar, 2, ',', '.') . "</TD>
                                    <td width=\"15%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\"></TD>
                                    <td width=\"15%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\"></TD>
                                    </tr>";
                }
            }


            //     <td width=\"15%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\">".number_format($nilaiterima,2,',','.')."</TD>
            //     <td width=\"15%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\">".number_format($nilaikeluar,2,',','.')."</TD>
            //     <td width=\"15%\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1 px black;border-top:none\"></TD>
            //     </tr>
            // ";
            // }




            // $cRet .= '<tr>
            // <TD align="center" >'.$no_kas.'</TD>
            // <td valign="top">'.$uraian.$net.'</td>
            // <td valign="top">'.$kode.'</td>
            // <td valign="top">'.$nama.'</td>
            // <td valign="top" align="right">'.number_format($nilaiterima,"2",",",".").'</td>
            // <td valign="top" align="right">'.number_format($nilaikeluar,"2",",",".").'</td> 
            // </tr>';

        }
        // $nilai_ax1=0;
        //----------------------------
        $sql3 = "SELECT SUM(nilai) as nilai from buku_kas";
        $hasil3 = $this->db->query($sql3);

        foreach ($hasil3->result() as $row) {
            if ($tgl == '2020-12-30') {
                $nilai_ax = 0;
            } else {
                $nilai_ax   = $row->nilai;
            }

            $nilai_x = number_format($nilai_ax, "2", ",", ".");
        }


        if ($st_renk == '1') {
             $sebelumharini = "SELECT SUM(CASE WHEN jenis IN('1') THEN terima ELSE 0 END) as trm_sbl,
             SUM(CASE WHEN jenis IN('2') THEN keluar ELSE 0 END) as klr_sbl
             FROM(
             SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6 ,SUM(b.rupiah) terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE LEFT(b.kd_rek6,1) IN ('4') $whereB GROUP BY a.no_kas,keterangan,rek_bank 
             UNION ALL -- LAIN-LAIN PENDAPATAN ASLI DAERAH YANG SAH 
             SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,a.keterangan+'. Rp. ' as uraian,'' as kode, ''as nm_rek6 ,SUM(b.rupiah) as terima,0 as keluar, 1 jenis,SUM(rupiah) netto, '' as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE LEFT(b.kd_rek6,1) IN ('5','1') and pot_khusus=3 $whereB GROUP BY a.no_kas,rek_bank,keterangan,b.kd_sub_kegiatan,b.kd_rek6 
             UNION ALL -- CONTRA POST 
             SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,a.keterangan+'. Rp. ' as uraian,'' as kode, '' as nama ,SUM(b.rupiah) as terima,0 as keluar, 1 jenis, SUM(rupiah) netto, '' as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE LEFT(b.kd_rek6,1) IN ('5','1') and a.jns_trans!='1' and pot_khusus<>3 $whereB group by a.no_kas,rek_bank,keterangan,b.kd_sub_kegiatan 
             UNION ALL 
             SELECT CAST(a.no_kas_bud as VARCHAR) as no_kas,CAST(a.no_kas_bud as VARCHAR) as urut,'No.SP2D :'+' '+a.no_sp2d+'
             '+a.keperluan+'Netto Rp. ' AS ket, '' AS kode,'' AS nmrek,0 AS terima,SUM(b.nilai) AS keluar,2 AS jenis, (SUM(b.nilai))-(SELECT ISNULL(SUM(nilai),0) FROM trspmpot WHERE no_spm=a.no_spm) AS netto, '' as sp, '' as rek_bank FROM trhsp2d a INNER JOIN trdspp b ON a.no_spp=b.no_spp WHERE a.status_bud = '1' AND (a.sp2d_batal=0 OR a.sp2d_batal is NULL) $whereA GROUP BY a.no_sp2d,no_kas_bud,a.keperluan,a.no_spm 
             UNION ALL 
             SELECT '' as nokas,CAST(a.no_kas as VARCHAR) as urut,''as ket,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, c.nm_rek6,0 terima, isnull(b.rupiah, 0) AS keluar, 2 jenis,0 netto, ''sp, '' as rek_bank FROM trdrestitusi b inner join trhrestitusi a on a.kd_skpd=b.kd_skpd and a.no_kas=b.no_kas and a.no_sts=b.no_sts left join ms_rek6 c on b.kd_rek6=c.kd_rek6 WHERE a.jns_trans=3 $whereB  
             UNION ALL -- UYHD 
             SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6 ,SUM(b.rupiah) terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE a.jns_trans='1' $whereB  GROUP BY a.no_kas,keterangan,rek_bank
             UNION ALL
             -- Pengeluaran lain lain
             SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6 ,SUM(b.rupiah) terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE a.pot_khusus='0' AND a.jns_trans='6' $whereB  GROUP BY a.no_kas,keterangan,rek_bank ) a ";
        } else if ($st_renk == '4501002886') {
            $sebelumharini = "SELECT SUM(CASE WHEN jenis IN('1') THEN terima ELSE 0 END) as trm_sbl,
            SUM(CASE WHEN jenis IN('2') THEN keluar ELSE 0 END) as klr_sbl
            FROM(
            SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6 ,SUM(b.rupiah) terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE LEFT(b.kd_rek6,1) IN ('4') $whereB GROUP BY a.no_kas,keterangan,rek_bank 
            UNION ALL -- LAIN-LAIN PENDAPATAN ASLI DAERAH YANG SAH 
            SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,a.keterangan+'. Rp. ' as uraian,'' as kode, ''as nm_rek6 ,SUM(b.rupiah) as terima,0 as keluar, 1 jenis,SUM(rupiah) netto, '' as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE LEFT(b.kd_rek6,1) IN ('5','1') and pot_khusus=3 $whereB GROUP BY a.no_kas,rek_bank,keterangan,b.kd_sub_kegiatan,b.kd_rek6 
            UNION ALL -- CONTRA POST 
            SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,a.keterangan+'. Rp. ' as uraian,'' as kode, '' as nama ,SUM(b.rupiah) as terima,0 as keluar, 1 jenis, SUM(rupiah) netto, '' as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE LEFT(b.kd_rek6,1) IN ('5','1') and a.jns_trans!='1' and pot_khusus<>3 $whereB group by a.no_kas,rek_bank,keterangan,b.kd_sub_kegiatan 
            UNION ALL 
            SELECT CAST(a.no_kas_bud as VARCHAR) as no_kas,CAST(a.no_kas_bud as VARCHAR) as urut,'No.SP2D :'+' '+a.no_sp2d+'
            '+a.keperluan+'Netto Rp. ' AS ket, '' AS kode,'' AS nmrek,0 AS terima,SUM(b.nilai) AS keluar,2 AS jenis, (SUM(b.nilai))-(SELECT ISNULL(SUM(nilai),0) FROM trspmpot WHERE no_spm=a.no_spm) AS netto, '' as sp, '' as rek_bank FROM trhsp2d a INNER JOIN trdspp b ON a.no_spp=b.no_spp WHERE a.status_bud = '1' AND (a.sp2d_batal=0 OR a.sp2d_batal is NULL) $whereA GROUP BY a.no_sp2d,no_kas_bud,a.keperluan,a.no_spm 
            UNION ALL 
            SELECT '' as nokas,CAST(a.no_kas as VARCHAR) as urut,''as ket,b.kd_sub_kegiatan+'.'+b.kd_rek6 as kode, c.nm_rek6,0 terima, isnull(b.rupiah, 0) AS keluar, 2 jenis,0 netto, ''sp, '' as rek_bank FROM trdrestitusi b inner join trhrestitusi a on a.kd_skpd=b.kd_skpd and a.no_kas=b.no_kas and a.no_sts=b.no_sts left join ms_rek6 c on b.kd_rek6=c.kd_rek6 WHERE a.jns_trans=3 $whereB  
            UNION ALL -- UYHD 
            SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6 ,SUM(b.rupiah) terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE a.jns_trans='1' $whereB  GROUP BY a.no_kas,keterangan,rek_bank 
            UNION ALL
            -- Pengeluaran lain lain
            SELECT CAST(a.no_kas as VARCHAR) as no_kas,CAST(a.no_kas as VARCHAR) as urut,keterangan+'. Rp. ' as uraian,'' as kode, '' as nm_rek6 ,SUM(b.rupiah) terima,0 as keluar, 1 jenis, SUM(b.rupiah) netto, ''as sp, a.rek_bank as rek_bank FROM trhkasin_ppkd a INNER JOIN trdkasin_ppkd b ON a.no_kas=b.no_kas AND a.kd_skpd=b.kd_skpd WHERE a.pot_khusus='0' AND a.jns_trans='1' $whereB  GROUP BY a.no_kas,keterangan,rek_bank) a ";
        }

        // Penjabaran
        $hasil3 = $this->db->query($sebelumharini);
        foreach ($hasil3->result() as $row) {
            $trm_sbl1 = $row->trm_sbl;
            $klr_sbl1 = $row->klr_sbl;
        }
        // End

        // Kondisi
        if ($st_renk == '1') {
            $smp_dgntrm     = $trm_sbl1;
            $smp_dgnklr     = $klr_sbl1;
            $saldo_awal     = $nilai_ax;
            $tot_saldo      = $nilai_ax + $smp_dgntrm - $smp_dgnklr;
        } else if ($st_renk == '4501002886') {
            $smp_dgntrm     = $trm_sbl1;
            $smp_dgnklr     = 0;
            $saldo_awal     = $nilai_ax;
            $tot_saldo      = $nilai_ax + $smp_dgntrm;
        }
        // End
        //--------------------------------------------------------------------
        $totsaldo = number_format($tot_saldo, "2", ",", ".");
        //--------------------------------------------------------------------


        if ($cetk == '1') {
            $cRet .= '
            <tr>
           
                <TD align="right" colspan="5">Jumlah Tanggal &nbsp; ' . $tanggal . '   </TD>
                
                <td align="right">' . number_format($totalnet, "2", ",", ".") . '</td>
                <TD align="right">' . number_format($totalnet_luar, "2", ",", ".") . '</TD>
            </tr>
            
            <tr>
                <TD  align="right" colspan="5">Jumlah Sampai Dengan Tanggal &nbsp; ' . $tanggalsbl . '</TD>
                <td  align="right">' . number_format($saldo_awal, "2", ",", ".") . '</td>
                <TD  align="right" >' . number_format($saldo_awal, "2", ",", ".") . '</TD>
            </tr>
            
            <tr>
                <TD  align="right" colspan="5">Jumlah Sampai Dengan Tanggal &nbsp; ' . $tanggal . '</TD>
                <td  align="right">' . number_format($smp_dgntrm, "2", ",", ".") . '</td>
                <TD  align="right">' . number_format($smp_dgnklr, "2", ",", ".") . '</TD>
            </tr>
            <tr>
                <TD  align="right" colspan="5">Sisa Kas</TD>
                <td  align="right" colspan="2">'.number_format($tot_saldo,"2",",",".").'</td>
            </tr>';
        } else {
            $cRet .= '
            <tr>
                
                <TD align="left" colspan="5">Jumlah Tanggal &nbsp; ' . $tanggal1 . ' &nbsp; s.d ' . $tanggal2 . ' &nbsp;</TD>
                
                <td align="right">' . number_format($totalnet, "2", ",", ".") . '</td>
                <TD align="right">' . number_format($totalnet_luar, "2", ",", ".") . '</TD>
            </tr>
            
            <tr>
                <TD style="border-top:hidden;" align="left" colspan="5">Jumlah Sampai Dengan Tanggal &nbsp; ' . $tanggalsbl . '</TD>
                <td style="border-top:hidden;" align="right">' . number_format($saldo_awal , "2", ",", ".") . '</td>
                <TD style="border-top:hidden;" align="right" >' . number_format($saldo_awal , "2", ",", ".") . '</TD>
            </tr>
            
            <tr>
                <TD style="border-top:hidden;" align="left" colspan="5">Jumlah Sampai Dengan Tanggal &nbsp; ' . $tanggal2 . '</TD>
                <td style="border-top:hidden;" align="right">' . number_format($smp_dgntrm, "2", ",", ".") . '</td>
                <TD style="border-top:hidden;" align="right">' . number_format($smp_dgnklr, "2", ",", ".") . '</TD>
            </tr>
                <tr>
                <TD  align="right" colspan="5">Sisa Kas</TD>
                <td  align="right" colspan="2">'.number_format($tot_saldo ,"2",",",".").'</td>
            </tr>';

        }

        $cRet .= '</table>';

        $cRet .= "<table style=\"font-size:12px;border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td width=\"50%\"></td>
                        <td width=\"50%\">&nbsp;</td>
                    </tr>";
        if ($cetk == '1') {
            $cRet .= "<tr>
                            <td width=\"50%\">Pada hari ini, tanggal $tanggal</td>
                            <td width=\"50%\"></td>
                        </tr>";
        } else {
            $cRet .= "<tr>
                            <td width=\"50%\">Pada hari ini, s.d  tanggal $tanggal2</td>
                            <td width=\"50%\"></td>
                        </tr>";
        }
        $cRet .= "<tr>
                        <td width=\"50%\">Oleh kami didapat dalam kas Rp. $totsaldo</td>
                        <td width=\"50%\"></td>
                    </tr>
                    <tr>
                        <td><i>(" . $this->tukd_model->terbilang($tot_saldo) . ")</i></td>
                        <td width=\"50%\"></td>
                    </tr>
                    
                  </table>";

        $cRet .= "<table style=\"font-size:12px;border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td align=\"center\" width=\"50%\">&nbsp;</td>
                        <td align=\"center\" width=\"50%\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align=\"center\" width=\"50%\">&nbsp;</td>
                        <td align=\"center\" width=\"50%\">Kuasa Bendahara Umum Daerah</td>
                    </tr>
                    <tr>
                        <td align=\"center\" width=\"50%\">&nbsp;</td>
                        <td align=\"center\" width=\"50%\">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align=\"center\" width=\"50%\">&nbsp;</td>
                        <td align=\"center\" width=\"50%\">&nbsp;</td>
                    </tr>                              
                    <tr>
                        <td align=\"center\" width=\"50%\">&nbsp;</td>
                        <td align=\"center\" width=\"50%\">&nbsp;</td>
                    </tr>                                       
                    <tr>
                        <td align=\"center\" width=\"50%\">&nbsp;</td>
                        <td align=\"center\" width=\"50%\"><u>$nama1</u></td>
                    </tr>
                    <tr>
                        <td align=\"center\" width=\"50%\">&nbsp;</td>
                        <td align=\"center\" width=\"50%\">$pangkat</td>
                    </tr>
                    <tr>
                        <td align=\"center\" width=\"50%\">&nbsp;</td>
                        <td align=\"center\" width=\"50%\">NIP. $nip</td>
                    </tr>
                    
                  </table>";

        $print = $this->uri->segment(3);
        $data['prev'] = $cRet;
        if ($print == 0) {
            $data['prev'] = $cRet;
            echo ("<title>Buku Kas Penerimaan dan Pengeluaran</title>");
            echo $cRet;
        } else if ($print == 1) {
            //$this->_mpdf2('',$cRet,10,10,10,'0',$no_halaman,'');
            // $this->_mpdf3('',$cRet,0,5,5,'0',$no_halaman,'');
            $this->tukd_model->_mpdf('', $cRet, '10', '10', 5, '0');
        } else {
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename=b9.xls");

            $this->load->view('anggaran/rka/perkadaII', $data);
        }
    }
}
