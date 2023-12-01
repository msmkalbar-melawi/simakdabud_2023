<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


    class RegisterSPM extends CI_Controller {

    public $ppkd = "4.02.02";
    public $ppkd1 = "4.02.02.02";
    public $keu1 = "4.02.02.01";
    public $kdbkad="5.02.0.00.0.00.02.0000";

    public $ppkd_lama = "4.02.02";
    public $ppkd1_lama = "4.02.02.02";

        function __contruct()
        {   
            parent::__construct();
        }

        function index()
        {
            $data['page_title']= 'Register SPM';
            $this->template->set('title', 'Register SPM');   
            $this->template->load('template','registerspm/index',$data) ; 
         
        }
        
        function cetakregister()
        {
            $thn_ang = $this->session->userdata('pcThang');
            $ckdskpd = $this->uri->segment(3);
            $ctgl1 = $this->uri->segment(4);
            $ctgl2 = $this->uri->segment(5);
            $ctk = $this->uri->segment(6);
            $sqlskpd ="SELECT nm_skpd FROM ms_skpd WHERE kd_skpd='$ckdskpd'";
            $hasil1 = $this->db->query($sqlskpd);
            $hasil11 = $hasil1->row();

             $query="SELECT * FROM(
                SELECT '1'as jns, a.kd_skpd ,a.no_spm, a.tgl_spm,'' as kd_rekening, '' as nm_rekening ,0 as nil_rekening, a.nilai as nilai,'' as sumber, ''as nm_sumber FROM trhspm a
                UNION ALL
                SELECT '2'as jns, a.kd_skpd, a.no_spm, a.tgl_spm as tgl_spm,b.kd_rek6 as kd_rekening, b.nm_rek6 as nm_rekening ,b.nilai as nil_rekening, 0 as nilai,b.sumber as sumber, (SELECT nm_sumber_dana1 FROM sumber_dana WHERE kd_sumber_dana1=b.sumber) as nm_sumber FROM trhspm a INNER JOIN trdspp b ON b.no_spp=a.no_spp AND a.kd_skpd=b.kd_skpd) x WHERE x.kd_skpd='$ckdskpd' AND x.tgl_spm BETWEEN '$ctgl1' AND '$ctgl2' ORDER BY x.no_spm, x.kd_rekening";
                $hasil = $this->db->query($query);

                $cRet = '';
                $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\"     cellpadding=\"1\">
                <tr>
                            <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>KABUPATEN MELAWI TAHUN ANGGARAN $thn_ang</b></td>
                        </tr>
                        <tr>
                            <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>REGISTER SPM</b></td>
                        </tr>
                        <tr>
                        <td align=\"center\" colspan=\"16\" height=\"20\" style=\"font-size:14px;border: solid 1px white;\"></td>
                        </tr>
                        <tr>
                            <td align=\"left\" style=\"font-size:12;border: solid 1px white;\">Nama SKPD : $hasil11->nm_skpd</td>
                        </tr>
                       
                        </table>";
                $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
                <thead> 
                <tr>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"7%\" style=\"font-size:12px;font-weight:bold\">No</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\" style=\"font-size:12px;font-weight:bold\">No. SPM</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"6%\" style=\"font-size:12px;font-weight:bold\">Tgl SPM</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\" style=\"font-size:12px;font-weight:bold\">Total</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\"  width=\"8%\" style=\"font-size:12px;font-weight:bold\">Kode Rekening</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"22%\" style=\"font-size:12px;font-weight:bold\">Nama Rekening</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\" style=\"font-size:12px;font-weight:bold\">Nilai</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"8%\" style=\"font-size:12px;font-weight:bold\">Kode Sumber</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"13%\" style=\"font-size:12px;font-weight:bold\">Nama Sumber</td>
                </tr>
                <tr>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">1</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">2</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">3</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">4</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">5</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">6</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">7</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">8</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">9</td>
                </tr>
                </thead>";
                $no=0;
                foreach ($hasil->result() as $row) {
                    
                    if($row->jns=='1'){
                        $no = $no + 1;
                        $cRet .="<tr>
                        <td align=\"center\" style=\"font-size:12px;border-top:solid 1px black\">$no</td>
                        <td align=\"center\" style=\"font-size:12px;border-top:solid 1px black\">$row->no_spm</td>
                        <td align=\"center\" style=\"font-size:12px;border-top:solid 1px black\">$row->tgl_spm</td>
                        <td align=\"left\" style=\"font-size:12px;border-top:solid 1px black\">Rp " . number_format($row->nilai, "2", ",", ".") . "</td>
                        <td colspan=\"5\" align=\"center\" style=\"font-size:12px;border-top:solid 1px black\"></td>

                    </tr>
                    ";
                    }else{
                        $cRet .="<tr>
                        <td align=\"center\" style=\"font-size:12px;border-top:solid 1px black\"></td>
                        <td align=\"center\" style=\"font-size:12px;border-top:solid 1px black\"></td>
                        <td align=\"center\" style=\"font-size:12px;border-top:solid 1px black\"></td>
                        <td align=\"center\" style=\"font-size:12px;border-top:solid 1px black\"></td>
                        <td align=\"left\" style=\"font-size:12px;border-top:solid 1px black\">$row->kd_rekening</td>
                        <td align=\"left\" style=\"font-size:12px;border-top:solid 1px black\">$row->nm_rekening</td>
                        <td align=\"left\" style=\"font-size:12px;border-top:solid 1px black\">Rp " . number_format($row->nil_rekening, "2", ",", ".") . "</td>
                        <td align=\"center\" style=\"font-size:12px;border-top:solid 1px black\">$row->sumber</td>
                        <td align=\"left\" style=\"font-size:12px;border-top:solid 1px black\">$row->nm_sumber</td>
                    </tr>
                    ";
                    }
                    
                }
                $cRet .="</table>";

            
            if ($ctk == 0) {
                echo ("<title>Regiter SPM</title>");
                echo $cRet;
            } else {
                $this->support->_mpdf('',$cRet,10,10,10,1,1,'');
                break;
            }
        }

        function cetakregisterspmbatal()
        {
            $thn_ang = $this->session->userdata('pcThang');
            $ckdskpd = $this->uri->segment(3);
            $ctgl1 = $this->uri->segment(4);
            $ctgl2 = $this->uri->segment(5);
            $ctk = $this->uri->segment(6);
            $sqlskpd ="SELECT nm_skpd FROM ms_skpd WHERE kd_skpd='$ckdskpd'";
            $hasil1 = $this->db->query($sqlskpd);
            $hasil11 = $hasil1->row();

             $query="SELECT a.kd_skpd, a.no_spm, a.tgl_spm, b.ket_batal, b.tgl_batal FROM trhspm a INNER JOIN trhspp b ON b.no_spp=a.no_spp AND b.kd_skpd=a.kd_skpd WHERE b.sp2d_batal<>'' AND b.kd_skpd='$ckdskpd' AND a.tgl_spm BETWEEN '$ctgl1' AND '$ctgl2' ORDER BY a.tgl_spm";
                $hasil = $this->db->query($query);

                $cRet = '';
                $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\"     cellpadding=\"1\">
                <tr>
                            <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>KABUPATEN MELAWI TAHUN ANGGARAN $thn_ang</b></td>
                        </tr>
                        <tr>
                            <td align=\"center\" colspan=\"16\" style=\"font-size:14px;border: solid 1px white;\"><b>REGISTER SPM BATAL</b></td>
                        </tr>
                        <tr>
                        <td align=\"center\" colspan=\"16\" height=\"20\" style=\"font-size:14px;border: solid 1px white;\"></td>
                        </tr>
                        <tr>
                            <td align=\"left\" style=\"font-size:12;border: solid 1px white;\">Nama SKPD : $hasil11->nm_skpd</td>
                        </tr>
                       
                        </table>";
                $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
                <thead> 
                <tr>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\" style=\"font-size:12px;font-weight:bold\">No</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"30%\" style=\"font-size:12px;font-weight:bold\">No. SPM</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"10%\" style=\"font-size:12px;font-weight:bold\">Tgl SPM</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"30%\" style=\"font-size:12px;font-weight:bold\">Keterangan</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" width=\"20%\" style=\"font-size:12px;font-weight:bold\">Tgl Batal</td>
                </tr>
                <tr>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">1</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">2</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">3</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">4</td>
                    <td align=\"center\" bgcolor=\"#CCCCCC\" style=\"font-size:12px;border-top:solid 1px black\">5</td>
                </tr>
                </thead>";
                $no=0;
                foreach ($hasil->result() as $row) {
                    $no = $no + 1;
                    $cRet .="<tr>
                    <td align=\"center\" style=\"font-size:12px;border-top:solid 1px black\">$no</td>
                    <td align=\"center\" style=\"font-size:12px;border-top:solid 1px black\">$row->no_spm</td>
                    <td align=\"center\" style=\"font-size:12px;border-top:solid 1px black\">$row->tgl_spm</td>
                    <td align=\"left\" style=\"font-size:12px;border-top:solid 1px black\">$row->ket_batal</td>
                    <td colspan=\"5\" align=\"center\" style=\"font-size:12px;border-top:solid 1px black\">$row->tgl_batal</td>

                </tr>
                ";
                    
                }
                $cRet .="</table>";

            
            if ($ctk == 0) {
                echo ("<title>Regiter SPM</title>");
                echo $cRet;
            } else {
                $this->support->_mpdf('',$cRet,10,10,10,1,1,'');
                break;
            }
        }

        ///batas akhir

    }
    ?>