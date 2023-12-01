<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Ujisp2d extends CI_Controller {
	
	function __contruct()
	{	
		parent::__construct();
	}

	function index()
    {
        // echo 'COBA';
       
        $data['page_title']= 'UJI SP2D';
        $this->template->set('title','UJI SP2D');   
       return $this->template->load('template','tukd/register/uji_sp2d', $data) ; 
    }

    function cetakujisp2d($periode1='', $periode2=''){
            $periode1 = $_GET['periode1'];
            $periode2 = $_GET['periode2'];
            $print = $_GET['ctk'];

        // $sql = $this->db->query("SELECT x.kd_skpd,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd=x.kd_skpd) as nm_skpd, SUM(x.sp2d_fungsional) as sp2d_fungsional, SUM(x.spj) as spj,SUM(x.sp2d_fungsional-x.spj) selisih FROM(
        //     SELECT b.tgl_sp2d as tgl,a.kd_skpd, '' as nm_skpd, a.nilai as sp2d_fungsional, 0 as spj FROM trdspp a INNER JOIN trhsp2d b ON b.kd_skpd=a.kd_skpd AND a.no_spp=b.no_spp WHERE b.jns_spp IN ('1','2','3','4','5','6')
        //     UNION ALL
        //     SELECT a.tgl_bukti as tgl,a.kd_skpd, '' as nm_skpd,  0 as sp2d_fungsional, b.nilai as spj FROM trhtransout a INNER JOIN trdtransout b ON b.kd_skpd=a.kd_skpd AND a.no_bukti=b.no_bukti WHERE a.jns_spp IN ('4','5','6') 
        //     UNION ALL
        //     SELECT '' as tgl, b.kd_skpd, '' as nm_skpd, 0 as sp2d_fungsional, c.nilai as spj FROM trhlpj b INNER JOIN trlpj c ON c.kd_skpd=b.kd_skpd AND c.no_lpj=b.no_lpj  
        //     )x WHERE x.tgl BETWEEN '$periode1' AND '$periode2' GROUP BY x.kd_skpd ORDER BY x.kd_skpd ASC");

        $sql = $this->db->query("SELECT x.kd_skpd,(SELECT nm_skpd FROM ms_skpd WHERE kd_skpd=x.kd_skpd) as nm_skpd, SUM(x.spj_fungsional) as spj_fungsional, SUM(x.sp2d) as sp2d,SUM(x.spj_fungsional-x.sp2d) selisih FROM(
            SELECT b.tgl_sp2d as tgl,a.kd_skpd, '' as nm_skpd, a.nilai as spj_fungsional, 0 as sp2d FROM trdspp a INNER JOIN trhsp2d b ON b.kd_skpd=a.kd_skpd AND a.no_spp=b.no_spp WHERE b.jns_spp IN ('1','2','3','4','5','6')
            UNION ALL 
            SELECT b.tgl_sp2d as tgl,a.kd_skpd, '' as nm_skpd, 0 as spj_fungsional, a.nilai as sp2d FROM trdspp a INNER JOIN trhsp2d b ON b.kd_skpd=a.kd_skpd AND a.no_spp=b.no_spp WHERE b.jns_spp IN ('1','2','3','4','5','6')
            )x WHERE x.tgl BETWEEN '$periode1' AND '$periode2' GROUP BY x.kd_skpd ORDER BY x.kd_skpd ASC");

    $cRet = '';
   
    $cRet .= "<table style=\"border-collapse:collapse;\" width=\"90%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>UJI NILAI SP2D</b></td>
            </tr>
            <tr>
                <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>PERIODE ".strtoupper($this->tukd_model->tanggal_format_indonesia($periode1)) ." S.D ".strtoupper($this->tukd_model->tanggal_format_indonesia($periode2)) ." </b></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            <tr>
                <td align=\"left\" colspan=\"12\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"></td>
            </tr>
            </table>

            <table style=\"border-collapse:collapse; border-color: black;\" width=\"90%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\" >
            <thead> 
            <tr>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"3%\" style=\"font-size:12px;font-weight:bold;\">No</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"15%\" style=\"font-size:12px;font-weight:bold\">OPD</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\"  width=\"8%\" style=\"font-size:12px;font-weight:bold\">SP2D SPJ FUNGSIONAL</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"8%\" style=\"font-size:12px;font-weight:bold\">REGISTER SP2D</td>
                <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"8%\" style=\"font-size:12px;font-weight:bold\">SELISIH</td>
            </tr>";

            $totalsp2d = 0;
            $totalbelanja = 0;
            $totalselisih = 0;
            $lcno = 0;
            foreach ($sql->result_array() as $resulte) {
                
                $lcno = $lcno + 1;
                $hasil = $resulte['kd_skpd'] . ' - ' . $resulte['nm_skpd'];
                $sp2dfungsional = $resulte['spj_fungsional'];
                $spj = $resulte['sp2d'];
                $selisih = $resulte['selisih'];
                $totalsp2d += $resulte['spj_fungsional'];
                $totalbelanja += $resulte['sp2d'];
                $totalselisih = $totalsp2d - $totalbelanja;
                
                $cRet .="
                <tr>
                <td align=\"center\" style=\"font-size:13px;border-top:solid 1px black\">$lcno</td>
                <td align=\"center\" style=\"font-size:13px;border-top:solid 1px black\">$hasil</td>
                <td align=\"center\" style=\"font-size:13px;border-top:solid 1px black\">" . number_format($sp2dfungsional, 2, ",", ".") . "</td>
                <td align=\"center\" style=\"font-size:13px;border-top:solid 1px black\">" . number_format($spj, 2, ",", ".") . "</td>
                <td align=\"center\" style=\"font-size:13px;border-top:solid 1px black\">" . number_format($selisih, 2, ",", ".") . "</td>
            </tr>";
            }
        
            $cRet .="
            <tr>
                <td colspan=\"2\" align=\"center\" style=\"font-size:13px;border-top:solid 1px black\"><b>Total</b></td>
                <td align=\"center\" style=\"font-size:13px;border-top:solid 1px black\">" . number_format($totalsp2d, 2, ",", ".") . "</td>
                <td align=\"center\" style=\"font-size:13px;border-top:solid 1px black\">" . number_format($totalbelanja, 2, ",", ".") . "</td>
                <td align=\"center\" style=\"font-size:13px;border-top:solid 1px black\">" . number_format($totalselisih, 2, ",", ".") . "</td>
            </tr>
            </thead>";

    $cRet .= "</table>";

    $print = $this->uri->segment(3);
        if ($print == 0) {
            $data['prev'] = $cRet;
            echo ("<title>UJI SP2D</title>");
            echo $cRet;
        } else if($print == '1'){
            $this->support->_mpdf_margin('', $cRet, 10, 10, 10, '0', 1, '');
            //$this->_mpdf('',$cRet,10,10,10,'0',1,'');
        }else{
            echo "perbaikan";
        }
    }
        

}