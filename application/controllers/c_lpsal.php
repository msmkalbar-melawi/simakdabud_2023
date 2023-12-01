<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller LPSAL
 */

class C_lpsal extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library('custom');
    }

    function index()
    {
        $data['page_title'] = 'LPSAL';
        $this->template->set('title', 'LPSAL');
        $this->template->load('template', 'akuntansi/cetak_lpsal', $data);
    }

    function hitung_lpsal()
    {
        $thn_ang = $this->session->userdata('pcThang');
        $bulan = $this->uri->segment(3);
        $sqlsawal = "SELECT * FROM map_lpsal_$bulan where nor='8'";
        $queryawal = $this->db->query($sqlsawal);
        $jmlsaldo = $queryawal->row();
        $jmlsal = $jmlsaldo->thn_m1;
        //$jmlsal_minus = 0-$jmlsaldo->thn_m1;

        //720101010001 = SILPA
        $sqlsawal1 = "select sum(real_spj) as nilai_x from data_realisasi_pemkot
                      where bulan=$bulan and kd_rek6='720101010001'";
        $queryawal = $this->db->query($sqlsawal1);
        $jmlsaldo = $queryawal->row();
        $jmlsal_minus = $jmlsaldo->nilai_x;

        $xc_jmlsal_minus = number_format($jmlsal_minus, 2);
        $bnb_jmlsal_minus = str_replace(',', '', $xc_jmlsal_minus);
        //3
        $sub_jmlsal = $jmlsal + $bnb_jmlsal_minus;

        $this->db->query("update map_lpsal_$bulan set thn_nilai ='$jmlsal' where nor='1' ");
        $this->db->query("update map_lpsal_$bulan set thn_nilai =(select sum(real_spj)*-1 as nilai_x from data_realisasi_pemkot
                      where bulan=$bulan and kd_rek6='720101010001') where nor='2' ");

        $total_1 = $this->db->query("SELECT ISNULL(SUM(thn_nilai),0) as nilai FROM map_lpsal_$bulan WHERE nor='1'")->row();
        $subt_1 = $total_1->nilai;

        $total_2 = $this->db->query("SELECT ISNULL(SUM(thn_nilai),0) as nilai FROM map_lpsal_$bulan WHERE nor='2'")->row();
        $subt_2 = $total_2->nilai;

        $substotal_3 = $subt_1 + $subt_2;

        $this->db->query("update map_lpsal_$bulan set thn_nilai ='$substotal_3' where nor='3' ");
        $sub_jmlsal_lain_lain = $substotal_3 * -1;
        $this->db->query("update map_lpsal_$bulan set thn_nilai ='$sub_jmlsal_lain_lain' where nor='7' ");

        $query = $this->db->query(" select nilai from data_realisasi_pemkot_silpa ");
        foreach ($query->result() as $res) {
            $nilai_silpa = $res->nilai;
        }

        $xc_nilai_silpa = number_format($nilai_silpa, 2);
        $bnb_nilai_silpa = str_replace(',', '', $xc_nilai_silpa);

        // 4 = Pendapatan Daerah
        // 5 = BELANJA DAERAH
        // 6 = PEMBIAYAAN DAERAH
        // 61 = PENERIMAAN PEMBIAYAAN
        // 62 = PENGELUARAN PEMBIAYAAN
        $this->db->query("update map_lpsal_$bulan set thn_nilai =(select sum(x.nilai) from (
                        select sum(real_spj) nilai from data_realisasi_pemkot
                        where bulan=12 and left(kd_rek6,1)
                        in ('4')
                        union all
                        select sum(real_spj)*-1 nilai from data_realisasi_pemkot
                        where bulan=12 and left(kd_rek6,1)
                        in ('5','6')
                        union all
                        select sum(real_spj) nilai from data_realisasi_pemkot
                        where bulan=12 and left(kd_rek6,2)
                        in ('61')
                        union all
                        select sum(real_spj)*-1 nilai from data_realisasi_pemkot
                        where bulan=12 and left(kd_rek6,2)
                        in ('62')
                        ) x
                        ) where nor='4'");

        $total1 = $this->db->query("SELECT ISNULL(SUM(thn_nilai),0) as nilai FROM map_lpsal_$bulan WHERE nor='3'")->row();
        $subt1 = $total1->nilai;

        $subt2 = $bnb_nilai_silpa;
        $subtotal5 = $subt2 + $subt1;


        $sqlsawal2 = "select sum(x.nilai) as nilai_y from (
                        select sum(real_spj) nilai from data_realisasi_pemkot
                        where bulan=12 and left(kd_rek6,1)
                        in ('4')
                        union all
                        select sum(real_spj)*-1 nilai from data_realisasi_pemkot
                        where bulan=12 and left(kd_rek6,1)
                        in ('5','6')
                        union all
                        select sum(real_spj) nilai from data_realisasi_pemkot
                        where bulan=12 and left(kd_rek6,2)
                        in ('61')
                        union all
                        select sum(real_spj)*-1 nilai from data_realisasi_pemkot
                        where bulan=12 and left(kd_rek6,2)
                        in ('62')
                        ) x";
        $queryawal = $this->db->query($sqlsawal2);
        $jmlsaldo = $queryawal->row();
        $jmlsal_x = $jmlsaldo->nilai_y;

        $bnb_nilai   =   $jmlsal_x + $substotal_3;
        $bnb_nilai1 = number_format($bnb_nilai, 2);
        $bnb_nilai2 = str_replace(',', '', $bnb_nilai1);

        $t5 = $this->db->query("update map_lpsal_$bulan set thn_nilai ='$bnb_nilai2' where nor='5' ");
        if ($t5) {
            $total5 = $this->db->query("SELECT ISNULL(SUM(thn_nilai),0) as nilai FROM map_lpsal_$bulan WHERE nor='5'")->row();
            $subt5 = $total5->nilai;
            $subt5 = number_format($subt5, 2);
            $subt5 = str_replace(',', '', $subt5);

            $total6 = $this->db->query("SELECT ISNULL(SUM(thn_nilai),0) as nilai FROM map_lpsal_$bulan WHERE nor='6'")->row();
            $subt6 = $total6->nilai;
            $subt6 = number_format($subt6, 2);
            $subt6 = str_replace(',', '', $subt6);

            $total7 = $this->db->query("SELECT ISNULL(SUM(thn_nilai),0) as nilai FROM map_lpsal_$bulan WHERE nor='7'")->row();
            $subt7 = $total7->nilai;
            $subt7 = number_format($subt7, 2);
            $subt7 = str_replace(',', '', $subt7);

            $subtotal8 = ($subt5 + $subt6) + $subt7;
            $subtotal8 = number_format($subtotal8, 2);
            $subtotal8 = str_replace(',', '', $subtotal8);

            $t8 = $this->db->query("update map_lpsal_$bulan set thn_nilai ='$subtotal8' where nor='8' ");
            if ($t8) {
                echo '1';
            }
        }
    }

    function ctk_lpsal($cetak = "", $tanggal_ttd = '', $bulan = '', $cttd='')
    {
        $cetak = $this->uri->segment(3);
       
        $bulan = $this->uri->segment(5);
        $cttd = $this->uri->segment(7);
        $label = $this->uri->segment(6);
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1 = $thn_ang - 1;
        $cttd = str_replace('%20',' ', $cttd);
        $tanggal = $this->tukd_model->tanggal_format_indonesia($tanggal_ttd);
       if ($label == '1') {
            $label = 'UNAUDITED';
        } else {
            $label = 'AUDITED';
        }

        $sql = $this->db->query("SELECT top 1 kab_kota,daerah from sclient")->row();
        $kab_kota = $sql->kab_kota;
        $daerah = $sql->daerah;

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
         
            <tr>
            <td><img src=\"" . base_url() . "/image/logoHP.png\"  width=\"60\" height=\"70\" /></td>
                <td align=\"center\" colspan=\"4\" style=\"font-size:12px;vertical-align:top;border: solid 1px white;\"><b>$kab_kota<br>LAPORAN PERUBAHAN SALDO ANGGARAN LEBIH<br>31 DESEMBER $thn_ang DAN $thn_ang_1 <br/>$label </b></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;vertical-align:top;border: solid 1px white;\">&nbsp;</td>
            </tr>            
            
            <tr>
                <td align=\"left\"  style=\"font-size:12px;border: solid 1px white;vertical-align:top;border-bottom:solid 2px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
            </tr>
      
            <tr>
                <td align=\"center\" width=\"5%\" style=\"vertical-align:top;font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:solid 1px black;border-top:solid 1px black;\" bgcolor=\"#CCCCCC\"><b>NO</b></td>
                <td align=\"center\" width=\"65%\" style=\"vertical-align:top;font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:solid 1px black;border-top:solid 1px black;\" bgcolor=\"#CCCCCC\"><b>URAIAN</b></td>
                <td align=\"center\" width=\"15%\" style=\"vertical-align:top;font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:solid 1px black;border-top:solid 1px black;\" bgcolor=\"#CCCCCC\"><b>$thn_ang</b></td> 
                <td align=\"center\" width=\"15%\" style=\"vertical-align:top;font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:solid 1px black;border-top:solid 1px black;\" bgcolor=\"#CCCCCC\"><b>$thn_ang_1</b></td>            
            </tr>";

        $sql = "SELECT * FROM map_lpsal_$bulan ORDER BY seq";
        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row) {
            $kd_rek   = $row->nor;
            $nama     = $row->uraian;
            $nilai_1    = $row->thn_m1;
            $nilai_2    = $row->thn_nilai;

            if ($nilai_1 < 0) {
                $a = "(";
                $nilai_1 = $nilai_1 * -1;
                $b = ")";
            } else {
                $a = "";
                $nilai_1 = $nilai_1;
                $b = "";
            }

            if ($nilai_2 < 0) {
                $c = "(";
                $nilai_2 = $nilai_2 * -1;
                $d = ")";
            } else {
                $c = "";
                $nilai_2 = $nilai_2;
                $d = "";
            }

            switch ($kd_rek) {
                case 1:
                    $cRet .= "<tr>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>" . $c . "" . number_format($nilai_2, 2, ',', '.') . "" . $d . "</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>" . $a . "" . number_format($nilai_1, 2, ',', '.') . "" . $b . "</b></td>
                            </tr>";
                    break;
                case 2:
                    $cRet .= "<tr>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>" . $c . "" . number_format($nilai_2, 2, ',', '.') . "" . $d . "</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>" . $a . "" . number_format($nilai_1, 2, ',', '.') . "" . $b . "</b></td>
                            </tr>";
                    break;
                case 3:
                    $cRet .= "<tr>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>" . $c . "" . number_format($nilai_2, 2, ',', '.') . "" . $d . "</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>" . $a . "" . number_format($nilai_1, 2, ',', '.') . "" . $b . " </b></td>
                            </tr>";
                    break;
                case 4:
                    $cRet .= "<tr>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>" . $c . "" . number_format($nilai_2, 2, ',', '.') . "" . $d . "</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>" . $a . "" . number_format($nilai_1, 2, ',', '.') . "" . $b . "</b></td>
                            </tr>";
                    break;
                case 5:
                    $cRet .= "<tr>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>" . $c . "" . number_format($nilai_2, 2, ',', '.') . "" . $d . "</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>" . $a . "" . number_format($nilai_1, 2, ',', '.') . "" . $b . "</b></td>
                            </tr>";
                    break;
                case 8:
                    $cRet .= "<tr>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>" . $c . "" . number_format($nilai_2, 2, ',', '.') . "" . $d . "</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>" . $a . "" . number_format($nilai_1, 2, ',', '.') . "" . $b . "</b></td>
                            </tr>";
                    break;
                default:
                    $cRet .= "<tr>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:12px;border-right:solid 1px black;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:12px;border-right:solid 1px black;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\">&nbsp; &nbsp; &nbsp; &nbsp;$nama</td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;border-right:solid 1px black;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\">" . $c . "" . number_format($nilai_2, 2, ',', '.') . "" . $d . "</td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:12px;border-right:solid 1px black;border-right:solid 1px black;border-left:solid 1px black;border-bottom:none;border-top:none\">" . $a . "" . number_format($nilai_1, 2, ',', '.') . "" . $b . " </td>
                            </tr>";
            }
        }

        $cRet .= "<tr>
                    <td align=\"center\" width=\"5%\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:solid 1px black;border-top:none\" >&nbsp;</td>
                    <td align=\"center\" width=\"65%\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:solid 1px black;border-top:none\">&nbsp;</td>
                    <td align=\"center\" width=\"15%\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:solid 1px black;border-top:none\">&nbsp;</td> 
                    <td align=\"center\" width=\"15%\" style=\"font-size:12px;border-right:solid 1px black;border-left:solid 1px black;border-bottom:solid 1px black;border-top:none\">&nbsp;</td>            
                </tr>					
					";
        $cRet .= '</table><br/>';

      
                    $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat as pangkat FROM ms_ttd where nip='$cttd' and (kode ='agr' or kode='wk' or kode='pa' or kode='ppkd' or kode='SETDA' or kode ='BUPATI')";
                    $sqlttd = $this->db->query($sqlttd1);
                    foreach ($sqlttd->result() as $rowttd) {
                        $nip = $rowttd->nip;
                        $namax = $rowttd->nm;
                        $jabatan  = $rowttd->jab;
                        $pangkat  = $rowttd->pangkat;
                    }
            
                    
                    
                    if ($nip == '00000000 000000 0 000'){
                        $cRet .= '<br><br>
                            <TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
                                        
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD align="center" > Sanggau , ' . $tanggal . '</TD>
                                        </TR>
                                        
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD align="center" ><b>' . $jabatan . '</b></TD>
                                        </TR>
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                        </TR>
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                        </TR>
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                        </TR>
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                        </TR>   
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                        </TR>                       
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD align="center" ><b>' . $namax . '</b></TD>
                                        </TR>
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD align="center" >' . $pangkat . '</TD>
                                        </TR>
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD align="center" > </TD>
                                        </TR>
                                        </TABLE><br/>';
                
                        } else {
                        $cRet .= '<br><br>
                            <TABLE style="border-collapse:collapse; font-size:12px; font-family: Bookman Old Style;"  width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
                                        
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD align="center" > Sanggau , ' . $tanggal . '</TD>
                                        </TR>
                                        
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD align="center" ><b>' . $jabatan . '</b></TD>
                                        </TR>
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                        </TR>
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                        </TR>
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                        </TR>
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                        </TR>   
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                        </TR>                       
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD align="center" ><u><b>' . $namax . '</b></u></TD>
                                        </TR>
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD align="center" >' . $pangkat . '</TD>
                                        </TR>
                                        <TR>
                                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                                            <TD align="center" >'.$nip.'</TD>
                                        </TR>
                                        </TABLE><br/>';
                        }

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul = 'LAPORAN PERUBAHAN SALDO ANGGARAN LEBIH';
        $this->template->set('title', 'LAPORAN PERUBAHAN SALDO ANGGARAN LEBIH');
        switch ($cetak) {
            case 0;
                echo ("<title>$judul</title>");
                echo $cRet;
                break;
            case 1;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                //echo $cRet;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }

    function ctk_lpsal_calk($cetak = "", $ttd = '', $tanggal_ttd = '', $bulan = '')
    {
        $cetak = $this->uri->segment(3);
        $ttd = $this->uri->segment(4);
        $bulan = $this->uri->segment(6);
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1 = $thn_ang - 1;

        $label = $this->uri->segment(7);
        if ($label == '1') {
            $label = 'UNAUDITED';
        } else {
            $label = 'AUDITED';
        }

        $sql = $this->db->query("SELECT Top 1 kab_kota, daerah from sclient")->row();
        $kab_kota = $sql->kab_kota;
        $daerah = $sql->daerah;

        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
         
            <tr>
                <td align=\"center\" colspan=\"4\" style=\"font-size:14px;border: solid 1px white;\"><b>$kab_kota<br>LAPORAN PERUBAHAN SALDO ANGGARAN LEBIH<br>31 DESEMBER $thn_ang DAN $thn_ang_1 <br>$label</b></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"4\" style=\"font-size:14px;border: solid 1px white;\">&nbsp;</td>
            </tr>            
            
            <tr>
                <td align=\"left\"  style=\"font-size:14px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:14px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
            </tr>
      
            <tr>
                <td align=\"center\" width=\"5%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:solid 1px black;\" bgcolor=\"#CCCCCC\"><b>NO</b></td>
                <td align=\"center\" width=\"65%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:solid 1px black;\" bgcolor=\"#CCCCCC\"><b>URAIAN</b></td>
                <td align=\"center\" width=\"5%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:solid 1px black;\" bgcolor=\"#CCCCCC\"><b>CATATAN</b></td>
                <td align=\"center\" width=\"15%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:solid 1px black;\" bgcolor=\"#CCCCCC\"><b>$thn_ang</b></td> 
                <td align=\"center\" width=\"15%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:solid 1px black;\" bgcolor=\"#CCCCCC\"><b>$thn_ang_1</b></td>            
            </tr>";

        $sql = "SELECT * FROM map_lpsal_$bulan ORDER BY seq";
        $hasil = $this->db->query($sql);
        $nawal = 0;
        foreach ($hasil->result() as $row) {
            $urut_calk   = $row->urut_calk;
            $map_calk   = $row->map_calk;
            $kd_rek   = $row->nor;
            $nama     = $row->uraian;
            $nilai_1    = $row->thn_m1;
            $nilai_2    = $row->thn_nilai;

            if ($nilai_1 < 0) {
                $a = "(";
                $nilai_1 = $nilai_1 * -1;
                $b = ")";
            } else {
                $a = "";
                $nilai_1 = $nilai_1;
                $b = "";
            }

            if ($nilai_2 < 0) {
                $c = "(";
                $nilai_2 = $nilai_2 * -1;
                $d = ")";
            } else {
                $c = "";
                $nilai_2 = $nilai_2;
                $d = "";
            }

            switch ($kd_rek) {
                case 1:
                    $cRet .= "<tr>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$map_calk</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>" . $c . "" . number_format($nilai_2, 2, ',', '.') . "" . $d . "</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>" . $a . "" . number_format($nilai_1, 2, ',', '.') . "" . $b . "</b></td>
                            </tr>";
                    break;
                case 2:
                    $cRet .= "<tr>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$map_calk</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>" . $c . "" . number_format($nilai_2, 2, ',', '.') . "" . $d . "</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>" . $a . "" . number_format($nilai_1, 2, ',', '.') . "" . $b . "</b></td>
                            </tr>";
                    break;
                case 3:
                    $cRet .= "<tr>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$map_calk</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>" . $c . "" . number_format($nilai_2, 2, ',', '.') . "" . $d . "</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>" . $a . "" . number_format($nilai_1, 2, ',', '.') . "" . $b . " </b></td>
                            </tr>";
                    break;
                case 4:
                    $cRet .= "<tr>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$map_calk</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>" . $c . "" . number_format($nilai_2, 2, ',', '.') . "" . $d . "</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>" . $a . "" . number_format($nilai_1, 2, ',', '.') . "" . $b . "</b></td>
                            </tr>";
                    break;
                case 5:
                    $cRet .= "<tr>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$map_calk</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>" . $c . "" . number_format($nilai_2, 2, ',', '.') . "" . $d . "</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>" . $a . "" . number_format($nilai_1, 2, ',', '.') . "" . $b . "</b></td>
                            </tr>";
                    break;
                case 8:
                    $cRet .= "<tr>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$nama</b></td>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$map_calk</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>" . $c . "" . number_format($nilai_2, 2, ',', '.') . "" . $d . "</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>" . $a . "" . number_format($nilai_1, 2, ',', '.') . "" . $b . "</b></td>
                            </tr>";
                    break;
                default:
                    $cRet .= "<tr>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$kd_rek</b></td>
                                <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp; &nbsp; &nbsp; &nbsp;$nama</td>
                                <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\"><b>$map_calk</b></td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">" . $c . "" . number_format($nilai_2, 2, ',', '.') . "" . $d . "</td>
                                <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">" . $a . "" . number_format($nilai_1, 2, ',', '.') . "" . $b . " </td>
                            </tr>";
            }
        }

        $cRet .= "<tr>
                    <td align=\"center\" width=\"5%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:none\" >&nbsp;</td>
                    <td align=\"center\" width=\"65%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:none\">&nbsp;</td>
                    <td align=\"center\" width=\"5%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:none\">&nbsp;</td>
                    <td align=\"center\" width=\"15%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:none\">&nbsp;</td> 
                    <td align=\"center\" width=\"15%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:none\">&nbsp;</td>            
                    </tr>					
					";
        $cRet .= '</table><br/>';

        if ($ttd == "1") {
            $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode in ('WK','BUP')";
            $sqlttd = $this->db->query($sqlttd1);
            foreach ($sqlttd->result() as $rowttd) {
                $nip = $rowttd->nip;
                $nama = $rowttd->nm;
                $jabatan  = $rowttd->jab;
                $pangkat  = $rowttd->pangkat;
            }

            $cRet .= '<TABLE style="border-collapse:collapse; font-size:16px" width="100%" border="0" cellspacing="0" cellpadding="0" align=center>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $daerah . ', ' . $this->custom->tanggal_format_indonesia($tanggal_ttd) . '</TD>
					</TR>
					
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b>' . $jabatan . '</b></TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
					<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
					</TR>                    
                    <TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" ><b><u>' . $nama . '</u></b></TD>
					</TR>
                    <!--<TR>
						<TD width="50%" align="center" ><b>&nbsp;</TD>
						<TD align="center" >' . $nip . '</TD>
					</TR>-->
					</TABLE><br/>';
        }

        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul = 'LAPORAN PERUBAHAN SALDO ANGGARAN LEBIH';
        $this->template->set('title', 'LAPORAN PERUBAHAN SALDO ANGGARAN LEBIH');
        switch ($cetak) {
            case 0;
                //$this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                echo $cRet;
                break;
            case 1;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                //echo $cRet;
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }
}
