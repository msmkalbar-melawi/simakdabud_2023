<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Lap_anggaran extends CI_Controller {


	public $ppkd1 = "5.02.0.00.0.00.02.0000";
	public $ppkd = "5.02.0.00.0.00.02.0000";
	
	function __contruct()
	{	
		parent::__construct();
  
	}


//cetak_sumberdana
     function cetak_sumberdana($skpd='',$cetak=''){
        $data['page_title']= 'Sumber Dana';
        $this->template->set('title', 'Sumber Dana');   
        $this->template->load('template','anggaran/rka/cetak_sumberdana',$data) ; 
     }

     function ambil_sdana() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kode,(select nm_sumber_dana1 from sumber_dana where kode=kd_sumber_dana1)nama from ambil_sdana order by CAST(kode as VARCHAR)";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,
                        'kd_sdana' => $resulte['kode'],
                        'nm_sdana' => $resulte['nama']                                                                                
                        );
                        $ii++;
        }
           
        // $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
           
    }
    


     function preview_sumberdana($skpd='',$cetak=0)
    {
       $jns         = $this->uri->segment(5);
       $lamp        = $this->uri->segment(6);
       $kd_sdana    = $this->uri->segment(7);
       $judul       = $this->uri->segment(8);


         $sqljnsang="SELECT * FROM tb_status_anggaran where kolom='$jns'";
     $sqljsang=$this->db->query($sqljnsang);
     foreach ($sqljsang->result() as $row_jenis)
     {                   
        $kolom          = $row_jenis->kolom;
        $sumber         = $row_jenis->k_sumber1;
        $nilai_sumber  = $row_jenis->k_nsumber1;
        $sumber2        = $row_jenis->k_sumber2;
        $nilai_sumber2  = $row_jenis->k_nsumber2;
        $sumber3        = $row_jenis->k_sumber3;
        $nilai_sumber3  = $row_jenis->k_nsumber3;
        $sumber4        = $row_jenis->k_sumber4;
        $nilai_sumber4  = $row_jenis->k_nsumber4;
        
     }


        $csumber = array();
        $cRet='<META http-equiv="X-UA-Compatible" content="IE=8"/>';
        $cRet1='';
        $cRet2='';
        
        $sqlsc = $this->rka_model->get_by_id2($this->ppkd1,'sclient','kab_kota','thn_ang','kd_skpd','kd_skpd');
        foreach ($sqlsc->result() as $rowsc){
                    $kab     = $rowsc->kab_kota;
                    $thn     = $rowsc->thn_ang;
                }
        $kddan = "$kd_sdana";
        // $nmdan = "$nm_sdana"; 
        $notin = "not in ('$kd_sdana')";  
        if($lamp=='0'){                        
            if($skpd=='0'){
                $jk = 'KODE SKPD';
                $jk1 = 'NAMA SKPD';
                $field ="kd_skpd as kode,nm_skpd as nama,";
                $where = "";
                $group = "kd_skpd,nm_skpd";
                $nmskpdj = "";
            }
            else{
                $field = "kd_sub_kegiatan as kode,nm_sub_kegiatan as nama,";
                $where = "where kd_skpd='$skpd'";
                $group = "kd_sub_kegiatan,nm_sub_kegiatan";
                $jk = 'KODE SUB KEGIATAN';
                $jk1 = 'NAMA SUB KEGIATAN';
                $nmskpdj = trim($this->rka_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd'));            
            }
        }else{
            if($skpd=='0'){
                $where = "";
                $nmskpdj = "";
            }else{
                $where = "and kd_skpd='$skpd'";
                $nmskpdj = trim($this->rka_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd'));            
            }
            $jk = 'NO URUT';
            $jk1 = 'URAIAN';
        }
        
        $cRet .="<table style=\"border-collapse:collapse;font-size:20px;font-weight:bold;\" width=\"100%\"  border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                    <tr><td align=\"center\">$kab</td></tr>
                    <tr><td align=\"center\">PEMBAGIAN SUMBER DANA $judul</td></tr>
                    <tr><td align=\"center\"><pre>$nmskpdj</pre></td></tr>
                    <tr><td align=\"center\">TAHUN ANGGARAN $thn</td></tr>
                    <tr><td>&nbsp;</td></tr>
               </table>";
        
        $font = 8;
        $font1 = $font-1;
        $fontc = $font.'px;';
        $cRet .= "<table style=\"border-collapse:collapse; font-size=$fontc \" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=\"left\">
                     <thead>                       
                        <tr>
                            <td align=\"center\"><b>$jk</b></td>
                            <td align=\"center\"><b>$jk1</b></td>
                            <td align=\"center\"><b>PAD</b></td>
                            <td align=\"center\"><b>DAK FISIK</b></td>
                            <td align=\"center\"><b>DAK NON FISIK</b></td>
                            <td align=\"center\"><b>DAU</b></td>
                            <td align=\"center\"><b>DBHP</b></td>
                            <td align=\"center\"><b>DID</b></td>
                            <td align=\"center\"><b>Lain-Lain</b></td>
                            <td align=\"center\"><b>Total</b></td>
                         </tr>
                      </thead>";

        $tpad = 0;$tdak = 0;$tdaknf = 0;$tdau = 0;$tdbhp=0;$tdid=0;$tlain2=0;
        if($lamp=='0'){    
            
            $sqlsumber = $this->rka_model
                     ->q_sumberdananew($field,$sumber,$sumber2,$sumber3,$sumber4,$nilai_sumber,$nilai_sumber2,$nilai_sumber3,
                     $nilai_sumber4,$where,$group,$notin);   
            foreach ($sqlsumber->result() as $rowsc){
                $kdskpd = $rowsc->kode;
                $nmskpd = $rowsc->nama;
                $pad    = $rowsc->pad;
                $dak    = $rowsc->dak;
                $daknf  = $rowsc->daknf;
                $dau    = $rowsc->dau;
                $dbhp   = $rowsc->dbhp;
                $did   = $rowsc->did;
                $lain2   = $rowsc->lain2;
                $totalskpd  = $pad + $dak + $daknf + $dau +$dbhp + $did + $lain2;
                $tpad = $tpad + $pad;
                $tdak = $tdak + $dak;
                $tdaknf = $tdaknf + $daknf;
                $tdau = $tdau + $dau;
                $tdbhp = $tdbhp + $dbhp;
                $tdid = $tdid + $did;
                $tlain2 = $tlain2 + $lain2;
                
                $pad=number_format($pad,"2",",",".");
                $dak=number_format($dak,"2",",",".");
                $daknf=number_format($daknf,"2",",",".");
                $dau=number_format($dau,"2",",",".");
                $dbhp=number_format($dbhp,"2",",",".");
                $did=number_format($did,"2",",",".");
                $lain2=number_format($lain2,"2",",",".");
                $totalskpd=number_format($totalskpd,"2",",",".");
                $cRet    .= "<tr>
                                <td style=\"vertical-align:top\">$kdskpd</td>
                                <td style=\"vertical-align:top\">$nmskpd</td>
                                <td style=\"vertical-align:top\" align=\"right\">$pad</td>
                                <td style=\"vertical-align:top\" align=\"right\">$dak</td>
                                <td style=\"vertical-align:top\" align=\"right\">$daknf</td>
                                <td style=\"vertical-align:top\" align=\"right\">$dau</td>
                                <td style=\"vertical-align:top\" align=\"right\">$dbhp</td>
                                <td style=\"vertical-align:top\" align=\"right\">$did</td>
                                <td style=\"vertical-align:top\" align=\"right\">$lain2</td>
                                <td style=\"vertical-align:top\" align=\"right\">$totalskpd</td>
                            </tr>";                 
                
            }

            $totalsumber = $tpad+$tdak+$tdaknf+$tdau+$tdbhp+$tdid+$tlain2;
            $tpad=number_format($tpad,"2",",",".");
            $tdak=number_format($tdak,"2",",",".");
            $tdaknf=number_format($tdaknf,"2",",",".");
            $tdau=number_format($tdau,"2",",",".");
            $tdbhp=number_format($tdbhp,"2",",",".");
            $tdid=number_format($tdid,"2",",",".");
            $tlain2=number_format($tlain2,"2",",",".");        
            $totalsumber=number_format($totalsumber,"2",",",".");
            $cRet    .= "<tr>
                            <td style=\"font-weight:bold;\" colspan=\"2\">Total</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tpad</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdak</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdaknf</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdau</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdbhp</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdid</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tlain2</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$totalsumber</td>
                        </tr>";    
            
            
        }else{
            //Pendapatan - 4
            $tpad_pend = 0;$tdak_pend = 0;$tdaknf_pend = 0;$tdau_pend = 0;$tdbhp_pend=0;$tdid_pend=0;$tlain2_pend=0;
            $sqlsumber = $this->rka_model
                         ->q_lamp1_sdana_new('4',$sumber,$sumber2,$sumber3,$sumber4,$nilai_sumber,$nilai_sumber2,$nilai_sumber3,$nilai_sumber4,$notin,$where);
            $nsqlsumber = $sqlsumber->num_rows();
            if($nsqlsumber>0){
                foreach ($sqlsumber->result() as $rowsc){
                        
                        $rek=$rowsc->rek;
                        $nmrek=$rowsc->nm_rek;
                        $pad    = $rowsc->pad;
                        $dak    = $rowsc->dak;
                        $daknf    = $rowsc->daknf;
                        $dau    = $rowsc->dau;
                        $dbhp   = $rowsc->dbhp;
                        $did   = $rowsc->did;
                        $lain2   = $rowsc->lain2;
                        $totalskpd  = $pad + $dak + $daknf + $dau +$dbhp + $did + $lain2;
                        //$= number_format($row->nilai,"2",",",".");
                        if (strlen($rek)>3) {
                            $font = '';
                            $tpad_pend = $tpad_pend + $pad;
                            $tdak_pend = $tdak_pend + $dak;
                            $tdaknf_pend = $tdaknf_pend + $daknf;
                            $tdau_pend = $tdau_pend + $dau;
                            $tdbhp_pend = $tdbhp_pend + $dbhp;
                            $tdid_pend = $tdid_pend + $did;
                            $tlain2_pend = $tlain2_pend + $lain2;
    
                        }else{
                            $font = 'font-weight:bold;';
                        }    
                             
                        $pad=number_format($pad,"2",",",".");
                        $dak=number_format($dak,"2",",",".");
                        $daknf=number_format($daknf,"2",",",".");
                        $dau=number_format($dau,"2",",",".");
                        $dbhp=number_format($dbhp,"2",",",".");
                        $did=number_format($did,"2",",",".");
                        $lain2=number_format($lain2,"2",",",".");
                        $totalskpd=number_format($totalskpd,"2",",",".");
    
                        $cRet    .= "<tr>
                                        <td style=\"vertical-align:top; $font \">$rek</td>
                                        <td style=\"vertical-align:top; $font \">$nmrek</td>
                                        <td style=\"vertical-align:top; $font \" align=\"right\">$pad</td>
                                        <td style=\"vertical-align:top; $font \" align=\"right\">$dak</td>
                                        <td style=\"vertical-align:top; $font \" align=\"right\">$daknf</td>
                                        <td style=\"vertical-align:top; $font \" align=\"right\">$dau</td>
                                        <td style=\"vertical-align:top; $font \" align=\"right\">$dbhp</td>
                                        <td style=\"vertical-align:top; $font \" align=\"right\">$did</td>
                                        <td style=\"vertical-align:top; $font \" align=\"right\">$lain2</td>
                                        <td style=\"vertical-align:top; $font \" align=\"right\">$totalskpd</td>
                                    </tr>";                 
                       
                }
            }else{
                $tpad_pend = 0;
                $tdak_pend = 0;
                $tdaknf_pend = 0;
                $tdau_pend = 0;
                $tdbhp_pend = 0;
                $tdid_pend = 0;
                $tlain2_pend = 0;

                $font = 'font-weight:bold;';

                $cRet    .= "<tr>
                                <td style=\"vertical-align:top; $font \">1.</td>
                                <td style=\"vertical-align:top; $font \">PENDAPATAN</td>
                                <td style=\"vertical-align:top; $font \" align=\"right\">0,00</td>
                                <td style=\"vertical-align:top; $font \" align=\"right\">0,00</td>
                                <td style=\"vertical-align:top; $font \" align=\"right\">0,00</td>
                                <td style=\"vertical-align:top; $font \" align=\"right\">0,00</td>
                                <td style=\"vertical-align:top; $font \" align=\"right\">0,00</td>
                                <td style=\"vertical-align:top; $font \" align=\"right\">0,00</td>
                                <td style=\"vertical-align:top; $font \" align=\"right\">0,00</td>
                                <td style=\"vertical-align:top; $font \" align=\"right\">0,00</td>
                            </tr>";                                 
            }
            $totalsumber_pend = $tpad_pend+$tdak_pend+$tdaknf_pend+$tdau_pend+$tdbhp_pend+$tdid_pend+$tlain2_pend;
            $tpad_pend1=number_format($tpad_pend,"2",",",".");
            $tdak_pend1=number_format($tdak_pend,"2",",",".");
            $tdaknf_pend1=number_format($tdaknf_pend,"2",",",".");
            $tdau_pend1=number_format($tdau_pend,"2",",",".");
            $tdbhp_pend1=number_format($tdbhp_pend,"2",",",".");
            $tdid_pend1=number_format($tdid_pend,"2",",",".");
            $tlain2_pend1=number_format($tlain2_pend,"2",",",".");        
            $totalsumber_pend1=number_format($totalsumber_pend,"2",",",".");
            $cRet    .= "<tr>
                            <td style=\"font-weight:bold;\" align=\"right\" colspan=\"2\">Jumlah Pendapatan</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tpad_pend1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdak_pend1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdaknf_pend1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdau_pend1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdbhp_pend1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdid_pend1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tlain2_pend1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$totalsumber_pend1</td>
                        </tr>
                        <tr>
                            <td style=\"font-weight:bold;\" colspan=\"2\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                        </tr>";              
          
            //Belanja - 5
            $tpad_b = 0;$tdak_b = 0;$tdaknf_b = 0;$tdau_b = 0;$tdbhp_b=0;$tdid_b=0;$tlain2_b=0;
            $sqlsumber = $this->rka_model
                         ->q_lamp1_sdana_new('5',$sumber,$sumber2,$sumber3,$sumber4,$nilai_sumber,$nilai_sumber2,$nilai_sumber3,$nilai_sumber4,$notin,$where);
            foreach ($sqlsumber->result() as $rowsc){
                    
                    $rek=$rowsc->rek;
                    $nmrek=$rowsc->nm_rek;
                    $pad    = $rowsc->pad;
                    $dak    = $rowsc->dak;
                    $daknf    = $rowsc->daknf;
                    $dau    = $rowsc->dau;
                    $dbhp   = $rowsc->dbhp;
                    $did   = $rowsc->did;
                    $lain2   = $rowsc->lain2;
                    $totalskpd  = $pad + $dak + $daknf + $dau +$dbhp + $did + $lain2;
                    //$= number_format($row->nilai,"2",",",".");
                    if (strlen($rek)>3) {
                        $font = '';
                        $tpad_b = $tpad_b + $pad;
                        $tdak_b = $tdak_b + $dak;
                        $tdaknf_b = $tdaknf_b + $daknf;
                        $tdau_b = $tdau_b + $dau;
                        $tdbhp_b = $tdbhp_b + $dbhp;
                        $tdid_b = $tdid_b + $did;
                        $tlain2_b = $tlain2_b + $lain2;

                    }else{
                        $font = 'font-weight:bold;';
                    }    
                         
                    $pad=number_format($pad,"2",",",".");
                    $dak=number_format($dak,"2",",",".");
                    $daknf=number_format($daknf,"2",",",".");
                    $dau=number_format($dau,"2",",",".");
                    $dbhp=number_format($dbhp,"2",",",".");
                    $did=number_format($did,"2",",",".");
                    $lain2=number_format($lain2,"2",",",".");
                    $totalskpd=number_format($totalskpd,"2",",",".");

                    $cRet    .= "<tr>
                                    <td style=\"vertical-align:top; $font \">$rek</td>
                                    <td style=\"vertical-align:top; $font \">$nmrek</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$pad</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$dak</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$daknf</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$dau</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$dbhp</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$did</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$lain2</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$totalskpd</td>
                                </tr>";                 
                   
            }
            $totalsumber_b = $tpad_b+$tdak_b+$tdaknf_b+$tdau_b+$tdbhp_b+$tdid_b+$tlain2_b;
            $tpad_b1=number_format($tpad_b,"2",",",".");
            $tdak_b1=number_format($tdak_b,"2",",",".");
            $tdaknf_b1=number_format($tdaknf_b,"2",",",".");
            $tdau_b1=number_format($tdau_b,"2",",",".");
            $tdbhp_b1=number_format($tdbhp_b,"2",",",".");
            $tdid_b1=number_format($tdid_b,"2",",",".");
            $tlain2_b1=number_format($tlain2_b,"2",",",".");        
            $totalsumber_b1=number_format($totalsumber_b,"2",",",".");
            $cRet    .= "<tr>
                            <td style=\"font-weight:bold;\" align=\"right\" colspan=\"2\">Jumlah Belanja</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tpad_b1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdak_b1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdaknf_b1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdau_b1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdbhp_b1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdid_b1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tlain2_b1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$totalsumber_b1</td>
                        </tr>
                        <tr>
                            <td style=\"font-weight:bold;\" colspan=\"2\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                        </tr>";              
            $totalsumber_pb = $totalsumber_pend-$totalsumber_b;
            $tpad_pb=$tpad_pend-$tpad_b;
            $tdak_pb=$tdak_pend-$tdak_b;
            $tdaknf_pb=$tdaknf_pend-$tdaknf_b;
            $tdau_pb=$tdau_pend-$tdau_b;
            $tdbhp_pb=$tdbhp_pend-$tdbhp_b;
            $tdid_pb=$tdid_pend-$tdid_b;
            $tlain2_pb=$tlain2_pend-$tlain2_b;        


            $tpad_pb1=$this->rka_model->rp_minus($tpad_pb);
            $tdak_pb1=$this->rka_model->rp_minus($tdak_pb);
            $tdaknf_pb1=$this->rka_model->rp_minus($tdaknf_pb);
            $tdau_pb1=$this->rka_model->rp_minus($tdau_pb);
            $tdbhp_pb1=$this->rka_model->rp_minus($tdbhp_pb);
            $tdid_pb1=$this->rka_model->rp_minus($tdid_pb);
            $tlain2_pb1=$this->rka_model->rp_minus($tlain2_pb);      
            $totalsumber_pb1=$this->rka_model->rp_minus($totalsumber_pb);
            $cRet    .= "<tr>
                            <td style=\"font-weight:bold;\" align=\"right\" colspan=\"2\">Surplus (Defisit)</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tpad_pb1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdak_pb1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdaknf_pb1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdau_pb1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdbhp_pb1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdid_pb1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tlain2_pb1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$totalsumber_pb1</td>
                        </tr>
                        <tr>
                            <td style=\"font-weight:bold;\" colspan=\"2\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                        </tr>
                        ";
            
            if($skpd==$this->ppkd1 || $skpd==$this->ppkd || $skpd==0){
            
            $cRet    .= "<tr>
                            <td style=\"font-weight:bold;\" >3.</td>
                            <td style=\"font-weight:bold;\" align=\"left\">PEMBIAYAAN DAERAH</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                        </tr>";    
            //Penerimaan Pembiayaan Daerah - 61
            $tpad_td = 0;$tdak_td = 0;$tdaknf_td = 0;$tdau_td = 0;$tdbhp_td=0;$tdid_td=0;$tlain2_td=0;
            $sqlsumber = $this->rka_model
                         ->q_lamp1_sdana2_new('61',$sumber,$sumber2,$sumber3,$sumber4,$nilai_sumber,$nilai_sumber2,$nilai_sumber3,$nilai_sumber4,$notin,$where);
            foreach ($sqlsumber->result() as $rowsc){
                    
                    $rek=$rowsc->rek;
                    $nmrek=$rowsc->nm_rek;
                    $pad    = $rowsc->pad;
                    $dak    = $rowsc->dak;
                    $daknf    = $rowsc->daknf;
                    $dau    = $rowsc->dau;
                    $dbhp   = $rowsc->dbhp;
                    $did   = $rowsc->did;
                    $lain2   = $rowsc->lain2;
                    $totalskpd  = $pad + $dak + $daknf + $dau +$dbhp + $did + $lain2;
                    //$= number_format($row->nilai,"2",",",".");
                    if (strlen($rek)>3) {
                        $font = '';
                        $tpad_td = $tpad_td + $pad;
                        $tdak_td = $tdak_td + $dak;
                        $tdaknf_td = $tdaknf_td + $daknf;
                        $tdau_td = $tdau_td + $dau;
                        $tdbhp_td = $tdbhp_td + $dbhp;
                        $tdid_td = $tdid_td + $did;
                        $tlain2_td = $tlain2_td + $lain2;

                    }else{
                        $font = 'font-weight:bold;';
                    }    
                         
                    $pad=number_format($pad,"2",",",".");
                    $dak=number_format($dak,"2",",",".");
                    $daknf=number_format($daknf,"2",",",".");
                    $dau=number_format($dau,"2",",",".");
                    $dbhp=number_format($dbhp,"2",",",".");
                    $did=number_format($did,"2",",",".");
                    $lain2=number_format($lain2,"2",",",".");
                    $totalskpd=number_format($totalskpd,"2",",",".");

                    $cRet    .= "<tr>
                                    <td style=\"vertical-align:top; $font \">$rek</td>
                                    <td style=\"vertical-align:top; $font \">$nmrek</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$pad</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$dak</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$daknf</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$dau</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$dbhp</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$did</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$lain2</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$totalskpd</td>
                                </tr>";                 
                   
            }
            $totalsumber_td = $tpad_td+$tdak_td+$tdaknf_td+$tdau_td+$tdbhp_td+$tdid_td+$tlain2_td;
            $tpad_td1=number_format($tpad_td,"2",",",".");
            $tdak_td1=number_format($tdak_td,"2",",",".");
            $tdaknf_td1=number_format($tdaknf_td,"2",",",".");
            $tdau_td1=number_format($tdau_td,"2",",",".");
            $tdbhp_td1=number_format($tdbhp_td,"2",",",".");
            $tdid_td1=number_format($tdid_td,"2",",",".");
            $tlain2_td1=number_format($tlain2_td,"2",",",".");        
            $totalsumber_td1=number_format($totalsumber_td,"2",",",".");
            $cRet    .= "<tr>
                            <td style=\"font-weight:bold;\" align=\"right\" colspan=\"2\">Jumlah Penerimaan Pembiayaan</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tpad_td1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdak_td1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdaknf_td1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdau_td1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdbhp_td1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdid_td1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tlain2_td1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$totalsumber_td1</td>
                        </tr>
                        <tr>
                            <td style=\"font-weight:bold;\" colspan=\"2\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                        </tr>";              
            //Pengeluaran Pembiayaan Daerah - 62
            $tpad_pd = 0;$tdak_pd = 0;$tdaknf_pd = 0;$tdau_pd = 0;$tdbhp_pd=0;$tdid_pd=0;$tlain2_pd=0;
            $sqlsumber = $this->rka_model
                         ->q_lamp1_sdana2_new('62',$sumber,$sumber2,$sumber3,$sumber4,$nilai_sumber,$nilai_sumber2,$nilai_sumber3,$nilai_sumber4,$notin,$where);
            foreach ($sqlsumber->result() as $rowsc){
                    
                    $rek=$rowsc->rek;
                    $nmrek=$rowsc->nm_rek;
                    $pad    = $rowsc->pad;
                    $dak    = $rowsc->dak;
                    $daknf    = $rowsc->daknf;
                    $dau    = $rowsc->dau;
                    $dbhp   = $rowsc->dbhp;
                    $did   = $rowsc->did;
                    $lain2   = $rowsc->lain2;
                    $totalskpd  = $pad + $dak + $daknf + $dau +$dbhp + $did + $lain2;
                    //$= number_format($row->nilai,"2",",",".");
                    if (strlen($rek)>3) {
                        $font = '';
                        $tpad_pd = $tpad_pd + $pad;
                        $tdak_pd = $tdak_pd + $dak;
                        $tdaknf_pd = $tdaknf_pd + $daknf;
                        $tdau_pd = $tdau_pd + $dau;
                        $tdbhp_pd = $tdbhp_pd + $dbhp;
                        $tdid_pd = $tdid_pd + $did;
                        $tlain2_pd = $tlain2_pd + $lain2;

                    }else{
                        $font = 'font-weight:bold;';
                    }    
                         
                    $pad=number_format($pad,"2",",",".");
                    $dak=number_format($dak,"2",",",".");
                    $daknf=number_format($daknf,"2",",",".");
                    $dau=number_format($dau,"2",",",".");
                    $dbhp=number_format($dbhp,"2",",",".");
                    $did=number_format($did,"2",",",".");
                    $lain2=number_format($lain2,"2",",",".");
                    $totalskpd=number_format($totalskpd,"2",",",".");

                    $cRet    .= "<tr>
                                    <td style=\"vertical-align:top; $font \">$rek</td>
                                    <td style=\"vertical-align:top; $font \">$nmrek</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$pad</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$dak</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$daknf</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$dau</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$dbhp</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$did</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$lain2</td>
                                    <td style=\"vertical-align:top; $font \" align=\"right\">$totalskpd</td>
                                </tr>";                 
                   
            }
            $totalsumber_pd = $tpad_pd+$tdak_pd+$tdaknf_pd+$tdau_pd+$tdbhp_pd+$tdid_pd+$tlain2_pd;
            $tpad_pd1=number_format($tpad_pd,"2",",",".");
            $tdak_pd1=number_format($tdak_pd,"2",",",".");
            $tdaknf_pd1=number_format($tdaknf_pd,"2",",",".");
            $tdau_pd1=number_format($tdau_pd,"2",",",".");
            $tdbhp_pd1=number_format($tdbhp_pd,"2",",",".");
            $tdid_pd1=number_format($tdid_pd,"2",",",".");
            $tlain2_pd1=number_format($tlain2_pd,"2",",",".");        
            $totalsumber_pd1=number_format($totalsumber_pd,"2",",",".");
            $cRet    .= "<tr>
                            <td style=\"font-weight:bold;\" align=\"right\" colspan=\"2\">Jumlah Pengeluaran Pembiayaan</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tpad_pd1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdak_pd1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdaknf_pd1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdau_pd1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdbhp_pd1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdid_pd1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tlain2_pd1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$totalsumber_pd1</td>
                        </tr>
                        <tr>
                            <td style=\"font-weight:bold;\" colspan=\"2\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                            <td style=\"font-weight:bold;\" align=\"right\">&nbsp;</td>
                        </tr>";              
            $totalsumber_nett = $totalsumber_td-$totalsumber_pd;
            $tpad_nett=$tpad_td-$tpad_pd;
            $tdak_nett=$tdak_td-$tdak_pd;
            $tdaknf_nett=$tdaknf_td-$tdaknf_pd;
            $tdau_nett=$tdau_td-$tdau_pd;
            $tdbhp_nett=$tdbhp_td-$tdbhp_pd;
            $tdid_nett=$tdid_td-$tdid_pd;
            $tlain2_nett=$tlain2_td-$tlain2_pd;        


            $tpad_nett1=$this->rka_model->rp_minus($tpad_nett);
            $tdak_nett1=$this->rka_model->rp_minus($tdak_nett);
            $tdaknf_nett1=$this->rka_model->rp_minus($tdaknf_nett);
            $tdau_nett1=$this->rka_model->rp_minus($tdau_nett);
            $tdbhp_nett1=$this->rka_model->rp_minus($tdbhp_nett);
            $tdid_nett1=$this->rka_model->rp_minus($tdid_nett);
            $tlain2_nett1=$this->rka_model->rp_minus($tlain2_nett);      
            $totalsumber_nett1=$this->rka_model->rp_minus($totalsumber_nett);
            $cRet    .= "<tr>
                            <td style=\"font-weight:bold;\" align=\"right\" colspan=\"2\">Pembiayaan Netto</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tpad_nett1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdak_nett1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdaknf_nett1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdau_nett1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdbhp_nett1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdid_nett1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tlain2_nett1</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$totalsumber_nett1</td>
                        </tr>";

            $tpad_silpa=$this->rka_model->rp_minus($tpad_pb+$tpad_nett);
            $tdak_silpa=$this->rka_model->rp_minus($tdak_pb+$tdak_nett);
            $tdaknf_silpa=$this->rka_model->rp_minus($tdaknf_pb+$tdaknf_nett);
            $tdau_silpa=$this->rka_model->rp_minus($tdau_pb+$tdau_nett);
            $tdbhp_silpa=$this->rka_model->rp_minus($tdbhp_pb+$tdbhp_nett);
            $tdid_silpa=$this->rka_model->rp_minus($tdid_pb+$tdid_nett);
            $tlain2_silpa=$this->rka_model->rp_minus($tlain2_pb+$tlain2_nett);      
            $totalsumber_silpa=$this->rka_model->rp_minus($totalsumber_pb+$totalsumber_nett); 
                        
            $cRet    .= "<tr>
                            <td style=\"font-weight:bold;\" align=\"right\" colspan=\"2\">(SILPA) TAHUN BERKENAAN</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tpad_silpa</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdak_silpa</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdaknf_silpa</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdau_silpa</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdbhp_silpa</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tdid_silpa</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$tlain2_silpa</td>
                            <td style=\"font-weight:bold;\" align=\"right\">$totalsumber_silpa</td>
                        </tr>";

                        
        }
        }             
        $cRet .= "</table>";
        $data['prev']= $cRet;
        if ($skpd==0){
            $skpd = 'Keseluruhan'; 
        }
            $judul2  = 'Sumber Dana '.$judul.' '.$skpd;//'Anggaran_KAS_'.$id;
        $this->template->set('title', 'CETAK PERDA REALISASI LAMPIRAN V');        
        switch($cetak) {
        case 0;  
                echo ("<title>$judul2</title>");
                echo($cRet);
            break;
        case 1;
            ini_set('memory_limit','10000M');
            set_time_limit(10000);

             $this->support->_mpdf('',$cRet,5,3,10,'1');
        break;
        case 2;        
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-excel");
            header("Content-Disposition: attachment; filename= $judul2.xls");
            
            $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 3;     
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd.ms-word");
            header("Content-Disposition: attachment; filename= $judul2.doc");
           $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        case 4;
            ini_set('memory_limit','10000M');
            set_time_limit(10000);
            
            $this->support->_mpdf('',$cRet,5,3,10,'0','','',$judul);        

        } 
    }


function preview_perkadaI_sempurna2(){
        $id = $this->uri->segment(3);
        $cetak = $this->uri->segment(4);
        $tgl= $_REQUEST['tgl_ttd'];
        if($tgl==''){
            $tanggal = '';
        }else{
            $tanggal = $this->support->tanggal_format_indonesia($tgl);
        }
        $ttd2= $_REQUEST['ttd2'];
        /* $uppkd = '1.20.12.02';
        $sppkd = '1.20.12'; */
        
        $uppkd = $this->ppkd1;
        $sppkd = $this->ppkd; 
        

        if ($id!='all'){
            if (strlen($id)==17){
                $a = 'left(';
                $skpd = 'kd_skpd';
                $b = ',17)';
    
                $sqldns="SELECT a.kd_bidang_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_org as kd_sk,a.nm_org as nm_sk FROM ms_organisasi a INNER JOIN ms_bidang_urusan b ON a.kd_bidang_urusan=b.kd_bidang_urusan WHERE kd_org='$id'";
                
            }else{
                $a = 'left(';;
                $skpd = 'kd_skpd';
                $b = ',22)';
                $sqldns="SELECT a.kd_bidang_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_bidang_urusan=b.kd_bidang_urusan WHERE kd_skpd='$id'";
            }
      
          
                $sqlskpd=$this->db->query($sqldns);
                foreach ($sqlskpd->result() as $rowdns)
                    {
                       
                        $kd_urusan=$rowdns->kd_u;                    
                        $nm_urusan= $rowdns->nm_u;
                        $kd_skpd  = $rowdns->kd_sk;
                        $nm_skpd  = $rowdns->nm_sk;
                    }
        
        }
        
        if ($id!='all'){
            $sqldns="SELECT * from ms_urusan WHERE kd_urusan=left('$kd_urusan',1)";
        }else{
            $sqldns="SELECT * from ms_urusan";
        }
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                   
                    $kd_urusan1=$rowdns->kd_urusan;                    
                    $nm_urusan1= $rowdns->nm_urusan;
                }


        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient ";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    $tgl=$rowsc->tgl_rka;
                 //   $tanggal = $this->tanggal_format_indonesia($tgl);
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }
      
      if ($ttd2<>''){  
      $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat,rtrim(kode) [kode] FROM ms_ttd WHERE (REPLACE(nip, ' ', '')='$ttd2')  ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    if($rowttd->kode=='GUB' || $rowttd->kode=='PJGUB'){
                        $nip2='';
                    }else{
                        $nip2=empty($rowttd->nip) ? '' : 'NIP.'. $rowttd->nip ;
                    }
                                            
                    $pangkat2=empty($rowttd->pangkat) ? '' : $rowttd->pangkat;
                    $nama2= empty($rowttd->nm) ? '' : $rowttd->nm;
                    $jabatan2  = empty($rowttd->jab) ? '': $rowttd->jab;
                }
       }else{
                     
                    $nip2='' ;
                    
                    $pangkat2='';
                    $nama2= '';
                    $jabatan2  = '';       
       } 
       
       
               $sqlsc="SELECT judul,nomor,tanggal FROM trkonfig_anggaran where jenis_anggaran='2'  and lampiran='pergub'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $row_ag)
                {
                    $ag_judul=$row_ag->judul;
                    $ag_nomor = $row_ag->nomor;
                     $ag_tanggal = $row_ag->tanggal;
                } 
          
        
        $cRet='';
        
        $cRet .="<table style=\"border-collapse:collapse;font-family: arial; font-weight:bold; font-size:11px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                    <tr>
                    <td width=\"10%\" rowspan=\"3\" align=\"left\" Valign=\"top\">Lampiran I :  </td>
                         <td  width=\"30%\" align=\"left\"> </td>
                         <td  width=\"60%\" align=\"left\"></td>
                         
                    </tr>
                    <tr>
                         <td align=\"left\">Nomor &nbsp;&nbsp;: </td>
                         <td ></td>
                    </tr>
                    
                    <tr>
                         <td align=\"left\" style=\"margin-bottom:15px;border-bottom: 2px solid;display: inline-block;width: 170px; \">Tanggal : </td>
                         <td ></td>
                    </tr>                                       
                                        
                  </table><br>";  

        
        $cRet .="<table style=\"border-collapse:collapse;font-family: arial; font-size:14px;font-weight:bold;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                    

                    <tr>
                    
                         <td  width=\"85%\" align=\"center\" style=\"font-size:23px;font-weight:bold;border-bottom: none;border-left: none; \" >$kab</td>
                         
                    </tr>
                    <tr>
                         <td align=\"center\" style=\"vertical-align:top;border-top: none; text-transform:uppercase;border-bottom: none;border-left: none;\" >
                            RINGKASAN PERUBAHAN PENJABARAN ANGGARAN PENDAPATAN DAERAH, </br> BELANJA DAERAH, DAN PEMBIAYAAN DAERAH 
                         </td>
                    </tr>
                    
                    <tr>
                         <td align=\"center\" style=\"vertical-align:top;border-top: none;border-bottom: none;border-left: none;\">TAHUN ANGGARAN $thn</td>
                    </tr>
                    <tr>
                         <td align=\"center\" style=\"vertical-align:top;border-bottom: none;border-top: none;border-left: none;\" > &nbsp;</td>
                    </tr>
                  </table>";    

        if ($id != 'all'){         
            $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"1\">
                        <tr>
                            <td width=\"25%\"><strong>Urusan Pemerintahan</stong></td>
                            <td width=\"80%\"><strong>$kd_urusan1 - $nm_urusan1</stong></td>
                        </tr>
                        <tr>
                            <td width=\"25%\"><strong>Bidang Pemerintahan</stong></td>
                            <td width=\"80%\"><strong>$kd_urusan - $nm_urusan</stong></td>
                        </tr>
                        <tr>
                            <td><strong>Unit Organisasi</stong></td>
                            <td><strong>$kd_skpd - $nm_skpd</stong></td>
                        </tr>
                    </table>";
        }
        
        $font = 11;
        $font1 = $font - 1;        
        $cRet .= "<table style=\"border-collapse:collapse; font-size:$font px;border-top: solid 1px black;\"  width=\"100%\" border=\"1\"  cellspacing=\"0\" cellpadding=\"1\">
                     <thead>                       
                        <tr>
                            <td width=\"5%\" rowspan=\"2\" align=\"center\"><b>No</b></td>                            
                            <td width=\"35%\" rowspan=\"2\" align=\"center\"><b>Uraian</b></td>
                            <td width=\"34%\" colspan=\"2\" align=\"center\"><b>Jumlah(Rp.)</b></td>
                            <td width=\"24%\" colspan=\"2\" align=\"center\"><b>Bertambah/ </br> (Berkurang)</b></td>                            
                        </tr>
                        <tr>
                            <td width=\"18%\" align=\"center\"><b>Sebelum</b></td>                            
                            <td width=\"18%\" align=\"center\"><b>Setelah</b></td>
                            <td width=\"17%\" align=\"center\"><b>(Rp.)</b></td>
                            <td width=\"7%\" align=\"center\"><b>%</b></td>                            
                        </tr>
                        <tr>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;font-weight:bold\" align=\"center\">1</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;font-weight:bold\" align=\"center\">2</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;font-weight:bold\" align=\"center\">3</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;font-weight:bold\" align=\"center\">4</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;font-weight:bold\" align=\"center\">5 = 4 - 3</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;font-weight:bold\" align=\"center\">6</td>
                        </tr>
                     </thead>
                    <tfoot>
                        <tr>
                            <td style=\"font-size:10px;border-bottom: none;border-right: none;border-left: none;\" colspan=\"6\">
                                <i>Lampiran I : PerGub Prov.Kalimantan Barat - Ringkasan Perubahan Penjabaran APBD TA $thn</i>
                            </td>
                         </tr>
                     </tfoot>";

            if ($id != 'all'){
                        $sql1="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='4'  and $a$skpd$b='$id' GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,1)='4' and $a$skpd$b='$id' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,1)='4' and $a$skpd$b='$id' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                 }else{
                       $sql1="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='4' GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,1)='4' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,1)='4'  GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";                    
                 }
                 
                 $query = $this->db->query($sql1);
                 //$query = $this->skpd_model->getAllc();
                
                $totp = 0;
                $totp2 = 0;                                  
                $num_rows = $query->num_rows();
                foreach ($query->result() as $row)
                {
                    $coba1=$row->rek;   
                    $coba2=$row->nm_rek;
                    $coba3 = $row->nilai;
                    $nilai2 = $row->nilai2;
                    $selisih = $this->support->rp_minus($nilai2 - $coba3);
                    if($coba3 != 0){
                        $persen = $this->support->rp_minus((($nilai2 - $coba3)/$coba3)*100);
                    }else{
                        $persen = $this->support->rp_minus(100);
                    } 

                    
                    if (strlen($coba1)>3) {
                        $bold = '';
                        $fontr = '';
                    }else{
                        if (strlen($coba1)=='1'){
                            $totp = $totp + $coba3;
                            $totp2 = $totp2 + $nilai2;
                        }      
                        $bold = 'font-weight:bold;';
                        $fontr = 'font-size:'.$font1.' px;';
                    }

                    $coba3= number_format($coba3,"2",",",".");
                    $nilai2 = number_format($nilai2,"2",",",".");

                    $cRet    .= "  <tr>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold \" >$coba1</td>                                     
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold \" >$coba2</td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$coba3</td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$nilai2</td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$selisih</td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$persen</td>

                                    </tr>";                 
                }

 
                 
                    $coba4=number_format($totp,"2",",",".");
                    $cob1=$totp;
                    $totp2c = number_format($totp2,"2",",",".");
                    $tselisihp =  $this->support->rp_minus($totp2 - $totp);
                    if($totp != 0){
                        $tpersenp = $this->support->rp_minus((($totp2 - $totp)/$totp)*100);
                    }else{
                        $tpersenp = $this->support->rp_minus(100);
                    } 
                if($num_rows>0){  
                    $cRet    .= "   <tr>
                                        <td></td>                                     
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" align=\"right\">JUMLAH PENDAPATAN</td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$coba4</td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$totp2c</td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$tselisihp</td>
                                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$tpersenp</td>
                                    </tr> 
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>";
                }                    



                if ($id != 'all'){                   
                        $sql2="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek1 a INNER JOIN trdrka b
                               ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='5'  and $a$skpd$b='$id' GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                               UNION ALL 
                               SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek2 a INNER JOIN trdrka b
                               ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,1)='5' and $a$skpd$b='$id' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                               UNION ALL 
                               SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek3 a INNER JOIN trdrka b
                               ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,1)='5'  and $a$skpd$b='$id' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                 }else{
                     $sql2="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek1 a INNER JOIN trdrka b
                               ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='5'   GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                               UNION ALL 
                               SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek2 a INNER JOIN trdrka b
                               ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,1)='5'  GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                               UNION ALL 
                               SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek3 a INNER JOIN trdrka b
                               ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,1)='5' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                 }
                 

                $totb = 0;
                $totb2 = 0;
                $query1 = $this->db->query($sql2);
                $num_rows = $query1->num_rows();
                foreach ($query1->result() as $row1){
                    $coba5=$row1->rek;
                    $coba6=$row1->nm_rek;
                    $coba7 = $row1->nilai;
                    $nilai2 = $row1->nilai2;
                    $selisih = $this->support->rp_minus($nilai2 - $coba7);
                    if($coba7 != 0){
                        $persen = $this->support->rp_minus((($nilai2 - $coba7)/$coba7)*100);
                    }else{
                        $persen = $this->support->rp_minus(100);
                    }                     

                    if (strlen($coba5)>3) {
                        $bold = '';
                        $fontr = '';
                    }else{
                        if (strlen($coba5)=='1'){
                            $totb = $totb + $coba7;
                            $totb2 = $totb2 + $nilai2;
                        }      
                        $bold = 'font-weight:bold;';
                        $fontr = 'font-size:'.$font1.' px;';
                    }
                    
                    $nilai2 = number_format($nilai2,"2",",",".");
                    $coba7= number_format($coba7,"2",",",".");
                    
                    $cRet    .= "<tr>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold \">$coba5</td>                                     
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold \">$coba6</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$coba7</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$nilai2</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$selisih</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$persen</td>
                                </tr>";

                }
                
                    $totb2c = number_format($totb2,"2",",",".");
                    $coba8=number_format($totb,"2",",",".");
                    $selisih = $this->support->rp_minus($totb2 - $totb);
                    if($totb != 0){
                        $persen = $this->support->rp_minus((($totb2 - $totb)/$totb)*100);
                    }else{
                        $persen = $this->support->rp_minus(100);
                    }                     
    
                    
                $cob=$totb;
                if($num_rows>0){
                    $cRet.="<tr>
                                <td></td>                                     
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" align=\"right\">JUMLAH BELANJA</td>
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$coba8</td>
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$totb2c</td>
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$selisih</td>
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$persen</td>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>";
                }

                  
                $suplus=$cob1-$cob; 
                $suplus2 = $totp2 - $totb2;
                $selisih = $this->support->rp_minus($suplus2 - $suplus);
                if($suplus != 0){
                    $persen = $this->support->rp_minus((($suplus2 - $suplus)/$suplus)*100);
                }else{
                    $persen = $this->support->rp_minus(100);
                }                     

                $surp=$this->support->rp_minus($suplus);
                $surp2 =  $this->support->rp_minus($suplus2);
                $cRet.="<tr>
                            <td></td>                                     
                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" align=\"right\">SURPLUS(DEFISIT)</td>
                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$surp</td>
                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$surp2</td>
                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$selisih</td>
                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$persen</td>                            
                        </tr>                                
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>";

                

                
                $cob3 = 0;
                $cob4 = 0;
                $totpm2 = 0;
                $totpk2 =0 ;
                if ($id == $this->ppkd or $id == $this->ppkd1 or $id == 'all' ){
                    if ($id != 'all'){                   
                        $sql3="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,0 AS nilai,0 AS nilai2 FROM ms_rek1 a INNER JOIN trdrka b
                                ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='6' and $a$skpd$b='$id' GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                                UNION ALL 
                                SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek2 a INNER JOIN trdrka b
                                ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,2)='61' and $a$skpd$b='$id' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                                UNION ALL 
                                SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek3 a INNER JOIN trdrka b
                                ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,2)='61' and $a$skpd$b='$id' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                     }else{
                        $sql3="SELECT a.kd_rek1 AS kd_rek,rtrim(a.kelompok) AS rek, a.nm_rek1 AS nm_rek ,0 AS nilai,0 as nilai2 FROM ms_rek1 a INNER JOIN trdrka b
                                ON a.kd_rek1=LEFT(b.kd_rek6,(LEN(a.kd_rek1))) where left(a.kd_rek1,1)='6' GROUP BY a.kd_rek1,a.kelompok, a.nm_rek1
                                UNION ALL 
                                SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek2 a INNER JOIN trdrka b
                                ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,2)='61' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                                UNION ALL 
                                SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek3 a INNER JOIN trdrka b
                                ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,2)='61' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                        
                     }
                     $query3 = $this->db->query($sql3);
                     //$query = $this->skpd_model->getAllc();
                       
                    $totpm = 0; 
                    $totpm2 = 0;
                    
                    $num_rows = $query3->num_rows();                                 
                    foreach ($query3->result() as $row3){
                        $coba9=$row3->rek;
                        $coba10=$row3->nm_rek;
                        $coba11= $row3->nilai;
                        $nilai2 = $row3->nilai2;
                        $selisih = $this->support->rp_minus($nilai2 - $coba11);
                        if($coba11 != 0){
                            $persen = $this->support->rp_minus((($nilai2 - $coba11)/$coba11)*100);
                        }else{
                            $persen = $this->support->rp_minus(100);
                        }                     

                        if (strlen($coba9)>3) {
                            $bold = '';
                            $fontr = '';
                        }else{
                            if (strlen($coba9)==3){
                                $totpm = $totpm + $coba11;
                                $totpm2 = $totpm2 + $nilai2;
                            }      
                            $bold = 'font-weight:bold;';
                            $fontr = 'font-size:'.$font1.' px;';
                        }
                        
                        $coba11= number_format($coba11,"2",",",".");
                        $nilai2= number_format($nilai2,"2",",",".");
                        if (strlen($coba9)==1){
                            $coba11 = '';
                            $nilai2 = '';
                            $selisih = '';
                            $persen = '';    
                        }

                        
                        $cRet.="<tr>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" >$coba9</td>                                     
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" >$coba10</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$coba11</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$nilai2</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$selisih</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$persen</td>
                                </tr>";                        
                   }
    

                    $cob3=$totpm; 
                    $selisih = $this->support->rp_minus($totpm2 - $totpm);
                    if($totpm != 0){
                        $persen = $this->support->rp_minus((($totpm2 - $totpm)/$totpm)*100);
                    }else{
                        $persen = $this->support->rp_minus(100);
                    }                     
                    
                    $coba12=number_format($totpm,"2",",",".");
                    $totpm2c = number_format($totpm2,"2",",",".");
                    
                       
                    if ($id==$uppkd or $id==$sppkd or $id='all') {
                        $cRet.="<tr>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"left\"></td>                                     
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">JUMLAH PENERIMAAN PEMBIAYAAN</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$coba12</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$totpm2c</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$selisih</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$persen</td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>";                                
                    }
                    
    
                     if ($id != 'all'){                  
                         $sql4="SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek2 a INNER JOIN trdrka b
                                ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,2)='62' and $a$skpd$b='$id' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                                UNION ALL 
                                SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nila,SUM(b.nilaisempurna2) AS nilai2i FROM ms_rek3 a INNER JOIN trdrka b
                                ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,2)='62' and $a$skpd$b='$id' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";
                     }else{
                         $sql4="SELECT a.kd_rek2 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek2 a INNER JOIN trdrka b
                                ON a.kd_rek2=LEFT(b.kd_rek6,(LEN(a.kd_rek2))) where left(a.kd_rek2,2)='62' GROUP BY a.kd_rek2,a.kelompok,a.nm_rek2 
                                UNION ALL 
                                SELECT a.kd_rek3 AS kd_rek,rtrim(a.kelompok) AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,SUM(b.nilaisempurna2) AS nilai2 FROM ms_rek3 a INNER JOIN trdrka b
                                ON a.kd_rek3=LEFT(b.kd_rek6,(LEN(a.kd_rek3))) where left(a.kd_rek3,2)='62' GROUP BY a.kd_rek3,a.kelompok, a.nm_rek3 ORDER BY kd_rek";                    
                     }
                     $query4 = $this->db->query($sql4);
                     //$query = $this->skpd_model->getAllc();
                    
                    $totpk = 0;
                    $totpk2 = 0;                                  
                    foreach ($query4->result() as $row4){
                        $coba13=$row4->rek;
                        $coba14=$row4->nm_rek;
                        $coba15= $row4->nilai;
                        $nilai2 = $row4->nilai2;
                        $selisih = $this->support->rp_minus($nilai2 - $coba15);
                        if($coba15 != 0){
                            $persen = $this->support->rp_minus((($nilai2 - $coba15)/$coba15)*100);
                        }else{
                            $persen = $this->support->rp_minus(100);
                        }                     

                        if (strlen($coba13)>3) {
                            $bold = '';
                            $fontr = '';
                        }else{
                            if (strlen($coba13)==3){
                                $totpk = $totpk + $coba15;
                                $totpk2 = $totpk2 + $nilai2;
                                $c =$coba13;
                            }else{
                                $c='';
                            }
                            $bold = 'font-weight:bold;';
                            $fontr = 'font-size:'.$font1.' px;';
                        }
                        
                        $coba15= number_format($coba15,"2",",",".");
                        $nilai2= number_format($nilai2,"2",",",".");
                        
                        $cRet.="<tr>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold \">$coba13</td>                                     
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold \" >$coba14</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$coba15</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$nilai2</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$selisih</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;$bold $fontr \" align=\"right\">$persen</td>
                                </tr>";
                                  
                                
                    }

                    $cob4=$totpk;
                    $selisih = $this->support->rp_minus($totpk2 - $totpk);
                    if($totpk != 0){
                        $persen = $this->support->rp_minus((($totpk2 - $totpk)/$totpk)*100);
                    }else{
                        $persen = $this->support->rp_minus(100);
                    }                     

                    $totpk2c = number_format($totpk2,"2",",",".");               
                    $coba16=number_format($totpk,"2",",",".");                       
                    if($id==$uppkd or $id==$sppkd or $id='all'){
                    $cRet.="<tr>
                                <td></td>                                     
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" align=\"right\">JUMLAH PENGELUARAN PEMBIAYAAN</td>
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$coba16</td>
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$totpk2c</td>
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$selisih</td>
                                <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$persen</td>
                            </tr>
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>";
                                     
                    }
                      
                    $netto=$cob3-$cob4; 
                    $netto2 =$totpm2 - $totpk2; 
                    $netto_ag=$netto;
                    $nett=$this->support->rp_minus($netto_ag);  
                    $nett2 =$this->support->rp_minus($netto2);
                    $selisih = $this->support->rp_minus($netto2 - $netto);
                    if($netto != 0){
                        $persen = $this->support->rp_minus((($netto2 - $netto)/$netto)*100);
                    }else{
                        $persen = $this->support->rp_minus(100);
                    }                     
    
                    if ($id==$uppkd or $id==$sppkd or $id='all'){      
                        $cRet.="<tr>
                                    <td></td>                                     
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" align=\"right\">PEMBIAYAAN NETTO </td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$nett</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$nett2</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$selisih</td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$persen</td>
                                </tr>";
                    }

                    
            }      
                    
            $cRet.="<tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>";
                        
                        
            $silpa=($cob1+$cob3)-($cob+$cob4);
            $silpa2 = ($totp2+$totpm2)-($totb2+$totpk2);
            $selisih = $this->support->rp_minus($silpa2 - $silpa);
            if($silpa != 0){
                $persen = $this->support->rp_minus((($silpa2 - $silpa)/$silpa)*100);
            }else{
                if(($silpa==0) & ($silpa2==0)){
                    $persen = $this->support->rp_minus(0);
                }else{
                    $persen = $this->support->rp_minus(100);
                }
            }                     

            $silp=$this->support->rp_minus($silpa);
            $silp2 = $this->support->rp_minus($silpa2);
                               
            $cRet.= "<tr>
                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"left\">3.3</td>                                     
                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" >SISA LEBIH PEMBIAYAAN ANGGARAN TAHUN BERKENAAN (SILPA)</td>
                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$silp</td>
                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$silp2</td>
                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$selisih</td>
                        <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:$font1 px;\" align=\"right\">$persen</td>

                    </tr>";

                
                
                        $cRet .="
                                    <tr>                                    
                                    <td colspan=\"6\" width=\"100\"  >                          
                                        <table border=\"0\" align=\"right\" >   
                                            <tr>                                
                                                <td width=\"40%\"></td>
                                                <td width=\"50%\" align=\"center\">&nbsp;$daerah, $tanggal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;.
                                                    <br>$jabatan2,
                                                    <p>&nbsp;</p>
                                                    <br>
                                                    <br>
                                                    <br><b>SUTARMIDJI</b>
                                                    <br>$pangkat2 
                                                    <br>$nip2 
                                                </td>
                                                <td width=\"10%\"></td>
                                            </tr>
                                        </table>  
                                    </td>
                             </tr>
                             
 
                             " ;          
  
       
        
            
        $cRet    .= "</table>";
 
 
        $data['prev']= $cRet;    
        switch($cetak) {
            case 0;  
                echo ("<title>Lampiran I - Ringkasan Penjabaran Penyempurnaan APBD</title>");
                echo($cRet);
            break;
            case 1;
                $this->support->_mpdf('',$cRet,10,10,10,'0','','','',2);
            break;
        }
        //$this->template->load('template','master/fungsi/list_preview',$data);
        
                
    }


//End Lampiran 1

}

?>