<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Lpj extends CI_Controller {


    public $ppkd1 = "5.02.0.00.0.00.02.0000";
    public $ppkd2 = "5.02.0.00.0.00.02.0000";
    
    function __contruct()
    {   
        parent::__construct();
  
    }

function config_tahun() {
        $result = array();
         $tahun  = $this->session->userdata('pcThang');
         $result = $tahun;
         echo json_encode($result);
           
    }
 function up()
    {
        $data['page_title']= 'PENGESAHAN LPJ UP';
        $this->template->set('title', 'PENGESAHAN LPJ UP/GU');   
        $this->template->load('template','tukd/lpj/lpj_up',$data) ; 
    }

function tu()
    {
        $data['page_title']= 'PERTANGGUNG JAWABAN TAMBAH UANG';
        $this->template->set('title', 'PERTANGGUNG JAWABAN TAMBAH UANG(TU)');   
        $this->template->load('template','tukd/transaksi/spjtu',$data) ; 
    }

function select_data1_lpj_ag($lpj='') {

    $lpj = $this->input->post('lpj');
    $kdskpd = $this->input->post('kdskpd');
   
   
        $sql = "SELECT a.kd_skpd, a.no_bukti, a.no_lpj,a.kd_sub_kegiatan,a.kd_rek6,a.nm_rek6, a.nilai,a.kd_skpd FROM trlpj a WHERE no_lpj='$lpj' AND kd_bp_skpd='$kdskpd'
        order by a.kd_skpd,no_bukti";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'idx'        => $ii,                        
                        'no_bukti' => $resulte['no_bukti'],     
                        'kdsubkegiatan' => $resulte['kd_sub_kegiatan'],     
                        'kdrek6'     => $resulte['kd_rek6'],  
                        'nmrek6'     => $resulte['nm_rek6'], 
                        'kd_skpd'     => $resulte['kd_skpd'],                        
                        'nilai1'      => number_format($resulte['nilai'])

                        );
                        $ii++;
        }
           
           echo json_encode($result);
     $query1->free_result();
    }
function setuju_lpj() {
    $lpj = $this->input->post('no_lpj');
    $kdskpd = $this->input->post('kd_skpd');
    $sql = "UPDATE trhlpj SET status='2' WHERE no_lpj='$lpj' AND kd_skpd='$kdskpd'";
    $asg = $this->db->query($sql);  
    if ($asg > 0){      
                    echo '2';
                    exit();
               } else {
                    echo '0';
                    exit();
               }
    }


    function batal_lpj() {
    $lpj = $this->input->post('no_lpj');
    $kdskpd = $this->input->post('kd_skpd');
    $sql = "UPDATE trhlpj SET status='0' WHERE no_lpj='$lpj' AND kd_skpd='$kdskpd'";
    $asg = $this->db->query($sql);  
    if ($asg > 0){      
                    echo '1';
                    exit();
               } else {
                    echo '0';
                    exit();
               }
    }

function load_sum_lpj(){
        $xlpj = $this->input->post('lpj');
        $kd_skpd = $this->input->post('kode');
        
        $query1 = $this->db->query("SELECT SUM(a.nilai)AS jml FROM trlpj a 
                  WHERE a.no_lpj='$xlpj' AND left(a.kd_bp_skpd,17)=left('$kd_skpd',17) ");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'cjumlah'  =>  $resulte['jml']                       
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result(); 
    }

 function load_sum_tran(){

        $id = $this->input->post('no_bukti');
        
        $query1 = $this->db->query("select sum(nilai) as rektotal from trdtransout where no_bukti='$id'");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
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

    function cetaklpjup_ag(){
        
        $cskpd  = $this->uri->segment(4);
        $ttd1   = str_replace('a',' ',$this->uri->segment(3));
        $ttd2   = str_replace('a',' ',$this->uri->segment(6));
        $ctk    =   $this->uri->segment(5);
        $nomor1 = str_replace('abcdefghij','/',$this->uri->segment(7));
        $nomor  = str_replace('123456789',' ',$nomor1);
        $jns    =   $this->uri->segment(8);
        $spasi    =   $this->uri->segment(9);
        $lctgl1 = $this->rka_model->get_nama2($nomor,'tgl_awal','trhlpj','no_lpj','kd_skpd',$cskpd);
        $lctgl2 = $this->rka_model->get_nama2($nomor,'tgl_akhir','trhlpj','no_lpj','kd_skpd',$cskpd);
        $lctglspp = $this->rka_model->get_nama2($nomor,'tgl_lpj','trhlpj','no_lpj','kd_skpd',$cskpd);

          
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$cskpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        /* $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$cskpd' and kode='BK' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                } */
        $cRet  =" <table style=\"border-collapse:collapse;font-size:15px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                        <tr>
                            <td align='center'> <b>$kab</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>LAPORAN PERTANGGUNG JAWABAN UANG PERSEDIAAN</b></td>
                        </tr>
                        <tr>
                            <td align='center'></td>
                        </tr>
                        <tr>
                            <td align='center'></td>
                        </tr>
                        <tr>
                            <td align='center'><b>&nbsp;</b></td>
                        </tr>
                  </table>              
                ";

        $cRet .=" <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                        <tr>
                            <td align='left' width='10%'> OPD&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='5%'>:&nbsp;&nbsp;&nbsp;</td>
                            <td align='left' > ".$cskpd." ".$this->tukd_model->get_nama($cskpd,'nm_skpd','ms_skpd','kd_skpd')." </td>
                        </tr>
                        <tr>
                            <td align='left' width='10%'>No. LPJ&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='5%'>:&nbsp;&nbsp;&nbsp;</td>
                            <td align='left' >$nomor</td>
                        </tr>
                   </table>             
                ";      

        $cRet .=" <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"$spasi\" cellpadding=\"$spasi\">
                    <THEAD>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>NO</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='30%'><b>KODE REKENING</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='50%'><b>URAIAN</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>JUMLAH</b></td>
                    </tr>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>1</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='30%'><b>2</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='50%'><b>3</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>4</b></td>
                    </tr>
                    </THEAD>
                ";      
            
                if($jns=='0'){
                $sql = "SELECT 1 as urut, LEFT(a.kd_sub_kegiatan,7) as kode, b.nm_program as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN (SELECT DISTINCT kd_program,nm_program,kd_skpd FROM trskpd GROUP BY kd_program,nm_program,kd_skpd)b 
                        ON LEFT(a.kd_sub_kegiatan,7) =b.kd_program AND a.kd_skpd=b.kd_skpd
                        WHERE a.no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY LEFT(a.kd_sub_kegiatan,7), b.nm_program
                        UNION ALL

                        SELECT 1 as urut, LEFT(a.kd_sub_kegiatan,12) as kode, b.nm_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN (SELECT DISTINCT kd_kegiatan,nm_kegiatan,kd_skpd FROM trskpd GROUP BY kd_kegiatan,nm_kegiatan,kd_skpd)b 
                        ON LEFT(a.kd_sub_kegiatan,12) =b.kd_kegiatan AND a.kd_skpd=b.kd_skpd
                        WHERE a.no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY LEFT(a.kd_sub_kegiatan,12), b.nm_kegiatan

                        UNION ALL
                        SELECT 2 as urut, a.kd_sub_kegiatan as kode, b.nm_sub_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                        WHERE no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY a.kd_sub_kegiatan, b.nm_sub_kegiatan
                        UNION ALL
                        SELECT 3 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,2) as kode, b.nm_rek2 as uraian, SUM(nilai) as nilai FROM trlpj a
                        INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2
                        WHERE no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,2), b.nm_rek2
                        UNION ALL
                        SELECT 4 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,4) as kode, b.nm_rek3 as uraian, SUM(nilai) as nilai FROM trlpj a
                        INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3
                        WHERE no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,4), b.nm_rek3
                        UNION ALL
                        
                        SELECT 5 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,6) as kode, b.nm_rek4 as uraian, SUM(nilai) as nilai FROM trlpj a
                        INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4
                        WHERE no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,6), b.nm_rek4
                        UNION ALL

                        SELECT 6 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,8) as kode, b.nm_rek5 as uraian, SUM(nilai) as nilai FROM trlpj a
                        INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5
                        WHERE no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,8), b.nm_rek5
                        UNION ALL
                        SELECT 7 as urut, kd_sub_kegiatan+'.'+kd_rek6 as kode, nm_rek6 as uraian, SUM(nilai) as nilai FROM trlpj
                        WHERE no_lpj='$nomor' AND left(kd_skpd,17)=left('$cskpd',17)
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY kd_sub_kegiatan, kd_rek6, nm_rek6
                        ORDER BY kode";     
                $query1 = $this->db->query($sql); 
                $total=0;
                $i=0;
                foreach ($query1->result() as $row) {
                    $kode=$row->kode;                    
                    $urut=$row->urut;                    
                    $uraian= $row->uraian;
                    $nilai  = $row->nilai;
                    
                    if ($urut==1){
                    $i=$i+1;    
                        $cRet .="<tr>
                                    <td valign='top' align='center' ><i><b>$i</b></i></td>
                                    <td valign='top' align='left' ><i><b>$kode</b></i></td>
                                    <td valign='top' align='left' ><i><b>$uraian</b></i></td>
                                    <td valign='top' align='right'><i><b>".number_format($nilai,"2",",",".")."</b></i></td>
                                </tr>";
                    } else if ($urut==2){
                            $cRet .="<tr>
                                    <td valign='top' align='center' ><b></b></td>
                                    <td valign='top' align='left' ><b>$kode</b></td>
                                    <td valign='top' align='left' ><b>$uraian</b></td>
                                    <td valign='top' align='right'><b>".number_format($nilai,"2",",",".")."</b></td>
                                </tr>";
                    }else if ($urut==6){
                            $total=$total+$nilai;
                            $cRet .="<tr>
                                    <td valign='top' align='center' ></td>
                                    <td valign='top' align='left' >$kode</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right'>".number_format($nilai,"2",",",".")."</td>
                                </tr>";
                    }
                    else{
                        $cRet .="<tr>
                                    <td valign='top' align='left' ></td>
                                    <td valign='top' align='left' >$kode</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right' >".number_format($nilai,"2",",",".")."</td>
                                </tr>"; 
                    }

                }
                } else{
                $sql = "SELECT 1 as urut, LEFT(a.kd_sub_kegiatan,7) as kode, b.nm_program as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN (SELECT DISTINCT kd_program,nm_program,kd_skpd FROM trskpd GROUP BY kd_program,nm_program,kd_skpd)b 
                        ON LEFT(a.kd_sub_kegiatan,7) =b.kd_program AND a.kd_skpd=b.kd_skpd
                        WHERE a.no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY LEFT(a.kd_sub_kegiatan,7), b.nm_program
                        UNION ALL
                        SELECT 2 as urut, LEFT(a.kd_sub_kegiatan,12) as kode, b.nm_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN (SELECT DISTINCT kd_kegiatan,nm_kegiatan,kd_skpd FROM trskpd GROUP BY kd_kegiatan,nm_kegiatan,kd_skpd)b 
                        ON LEFT(a.kd_sub_kegiatan,12) =b.kd_kegiatan AND a.kd_skpd=b.kd_skpd
                        WHERE a.no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY LEFT(a.kd_sub_kegiatan,12), b.nm_kegiatan
                        UNION ALL
                        SELECT 3 as urut, a.kd_sub_kegiatan as kode, b.nm_sub_kegiatan as uraian, SUM(a.nilai) as nilai
                        FROM trlpj a LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                        WHERE no_lpj='$nomor' AND left(a.kd_skpd,17)=left('$cskpd',17)
                        AND no_bukti IN (SELECT no_bukti FROM trhtransout WHERE left(kd_skpd,17)=left('$cskpd',17)
                        --AND (panjar NOT IN ('3') or panjar IS NULL) 
                        AND jns_spp IN ('1','2','3'))
                        GROUP BY a.kd_sub_kegiatan, b.nm_sub_kegiatan
                        ORDER BY kode";     
                $query1 = $this->db->query($sql); 
                $total=0;
                $i=0;
                foreach ($query1->result() as $row) {
                    $kode=$row->kode;                    
                    $urut=$row->urut;                    
                    $uraian= $row->uraian;
                    $nilai  = $row->nilai;
                    
                    if ($urut==1){
                    $i=$i+1;    
                    $total=$total+$nilai;
                        $cRet .="<tr>
                                    <td valign='top' align='center' ><b>$i</b></td>
                                    <td valign='top' align='left' ><b>$kode</b></td>
                                    <td valign='top' align='left' ><b>$uraian</b></td>
                                    <td valign='top' align='right'><b>".number_format($nilai,"2",",",".")."</b></td>
                                </tr>";
                    } else{
                        
                        $cRet .="<tr>
                                    <td valign='top' align='left' ></td>
                                    <td valign='top' align='left' >$kode</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right' >".number_format($nilai,"2",",",".")."</td>
                                </tr>"; 
                    }

                }   
                }


                $sqlp = " SELECT SUM(a.nilai) AS nilai FROM trdspp a LEFT JOIN trhsp2d b ON b.no_spp=a.no_spp  
                          WHERE b.kd_skpd='$cskpd' AND (b.jns_spp=1)";
                $queryp = $this->db->query($sqlp);      
                foreach($queryp->result_array() as $nlx){ 
                        $persediaan=$nlx["nilai"];
                }

                $cRet .="
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' >&nbsp;</td>
                        </tr>                   
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Total</b></td>
                            <td align='right' ><b>".number_format($total,"2",",",".")."</b></td>
                        </tr>                   
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Uang Persediaan Awal Periode</b></td>
                            <td align='right' ><b>".number_format($persediaan,"2",",",".")."</b></td>
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Uang Persediaan Ahir Periode</b></td>
                            <td align='right' ><b>".number_format($persediaan-$total,"2",",",".")."</b></td>
                        </tr>
                        </tr>
                        ";


                $cRet .="</table><p>";              
//.$this->tukd_model->tanggal_format_indonesia($this->uri->segment(7)).
        $cRet .=" <table width='100%' style='font-size:12px' border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                    <tr>
                        <td valign='top' align='center' width='50%'>Disetujui   
                        <br>Kuasa Bendahara Umum Daerah</td>
                        <td valign='top' align='center' width='50%'>Pontianak, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                        <br>Telah diverifikasi
                        <br>Petugas</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'><b><u>$nama</u></b><br>$pangkat</td>
                        <td align='center' width='50%'>___________________<br></td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>$ttd1</td>
                        <td align='center' width='50%'></td>
                    </tr>
                  </table>
                ";      

        $data['prev']= $cRet; 

        switch ($ctk)
        {
            case 0;
               echo ("<title> LPJ UP</title>");
                echo $cRet;     
                break;
            case 1;
                $this->support->_mpdf('',$cRet,10,10,10,'0',0,'');
               break;
        }
    }


    

function load_giat_lpj(){

        $nomor = $this->input->post('lpj');
        $query1 = $this->db->query("
        SELECT a.kd_sub_kegiatan, c.nm_sub_kegiatan
        from trlpj a 
        INNER JOIN trhlpj b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
        LEFT JOIN trskpd c ON a.kd_sub_kegiatan=c.kd_sub_kegiatan AND a.kd_skpd=c.kd_skpd
        WHERE a.no_lpj = '$nomor'
        GROUP BY a.kd_sub_kegiatan,c.nm_sub_kegiatan
        ORDER BY a.kd_sub_kegiatan");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],                       
                        'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan']                      
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);
           $query1->free_result();  
    }






// 
function cetaklpjup_ag_rinci(){
        
        $cskpd  = $this->uri->segment(4);
        $ttd1   = str_replace('a',' ',$this->uri->segment(3));
        $ttd2   = str_replace('a',' ',$this->uri->segment(6));
        $ctk =   $this->uri->segment(5);
        $nomor   = str_replace('abcdefghij','/',$this->uri->segment(7));
        $nomor   = str_replace('123456789',' ',$nomor);
        $kegiatan =   $this->uri->segment(8);
        $spasi =   $this->uri->segment(9);
        $lctgl1 = $this->tukd_model->get_nama($nomor,'tgl_awal','trhlpj','no_lpj');
        $lctgl2 = $this->tukd_model->get_nama($nomor,'tgl_akhir','trhlpj','no_lpj');
        $lctglspp = $this->tukd_model->get_nama($nomor,'tgl_lpj','trhlpj','no_lpj');

          
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$cskpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                 {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                 }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
        /* $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat FROM ms_ttd where kd_skpd='$cskpd' and kode='BK' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip1=$rowttd->nip;                    
                    $nama1= $rowttd->nm;
                    $jabatan1  = $rowttd->jab;
                    $pangkat1  = $rowttd->pangkat;
                } */
        $cRet  =" <table style=\"border-collapse:collapse;font-size:15px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                        <tr>
                            <td align='center'> <b>$kab</b></td>
                        </tr>
                        <tr>
                            <td align='center'><b>LAPORAN PERTANGGUNG JAWABAN UANG PERSEDIAAN</b></td>
                        </tr>
                        <tr>
                            <td align='center'></td>
                        </tr>
                        <tr>
                            <td align='center'><b>&nbsp;</b></td>
                        </tr>
                  </table>              
                ";

        $cRet .=" <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                        <tr>
                            <td align='left' width='10%'>OPD&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='5%'>:&nbsp;&nbsp;&nbsp;</td>
                            <td align='left' > ".$cskpd." ".$this->tukd_model->get_nama($cskpd,'nm_skpd','ms_skpd','kd_skpd')." </td>
                        </tr>
                        <tr>
                            <td align='left' width='10%'>PERIODE&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='5%'>:&nbsp;&nbsp;&nbsp;</td>
                            <td align='left' >".$this->tukd_model->tanggal_format_indonesia($lctgl1).' s/d '.$this->tukd_model->tanggal_format_indonesia($lctgl2)."</td>
                        </tr>
                        <tr>
                            <td align='left' width='10%'>Kegiatan&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='5%'>:&nbsp;&nbsp;&nbsp;</td>
                            <td align='left' >$kegiatan - ".$this->tukd_model->get_nama($kegiatan,'nm_sub_kegiatan','trskpd','kd_sub_kegiatan')."</td>
                        </tr>
                        <tr>
                            <td align='left' width='10%'>&nbsp;&nbsp;&nbsp;</td>
                            <td align='center' width='5%'>&nbsp;&nbsp;&nbsp;</td>
                            <td align='left' ></td>
                        </tr>
                   </table>             
                ";      

        $cRet .=" <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"$spasi\" cellpadding=\"$spasi\">
                    <THEAD>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>NO</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>KODE REKENING</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='60%'><b>URAIAN</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>JUMLAH</b></td>
                    </tr>
                    <tr>
                        <td bgcolor='#CCCCCC' align='center' width='5%'><b>1</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>2</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='60%'><b>3</b></td>
                        <td bgcolor='#CCCCCC' align='center' width='20%'><b>4</b></td>
                    </tr>
                    </THEAD>
                ";      
            
                
                $sql = "SELECT 1 as urut, a.kd_sub_kegiatan as kode, a.kd_sub_kegiatan as rek, b.nm_kegiatan as uraian, SUM(a.nilai) as nilai
                        ,'' [tgl_bukti],0 [no_bukti]
                        FROM trlpj a LEFT JOIN trskpd b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd
                        INNER JOIN trhtransout c ON a.no_bukti=c.no_bukti AND a.kd_skpd=c.kd_skpd
                        AND (c.panjar NOT IN('3') or c.panjar IS NULL)
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY a.kd_sub_kegiatan, b.nm_kegiatan
                        UNION ALL
                        SELECT 2 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,2) as kode, LEFT(a.kd_rek6,2) as rek,  nm_rek2 as uraian, SUM(nilai) as nilai,
                        '' [tgl_bukti],0 [no_bukti] FROM trlpj a
                        INNER JOIN ms_rek2 b ON LEFT(a.kd_rek6,2)=b.kd_rek2
                        INNER JOIN trhtransout c ON a.no_bukti=c.no_bukti AND a.kd_skpd=c.kd_skpd
                        AND (c.panjar NOT IN('3') or c.panjar IS NULL)
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,2), nm_rek2
                        UNION ALL
                        SELECT 2 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,4) as kode, LEFT(a.kd_rek6,4) as rek,  nm_rek3 as uraian, SUM(nilai) as nilai,
                        '' [tgl_bukti],0 [no_bukti] FROM trlpj a
                        INNER JOIN ms_rek3 b ON LEFT(a.kd_rek6,4)=b.kd_rek3
                        INNER JOIN trhtransout c ON a.no_bukti=c.no_bukti AND a.kd_skpd=c.kd_skpd
                        AND (c.panjar NOT IN('3') or c.panjar IS NULL)
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,4), nm_rek3
                        UNION ALL
                        SELECT 2 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,6) as kode, LEFT(a.kd_rek6,6) as rek,  nm_rek4 as uraian, SUM(nilai) as nilai 
                        ,'' [tgl_bukti],0 [no_bukti] FROM trlpj a
                        INNER JOIN ms_rek4 b ON LEFT(a.kd_rek6,6)=b.kd_rek4
                        INNER JOIN trhtransout c ON a.no_bukti=c.no_bukti AND a.kd_skpd=c.kd_skpd
                        AND (c.panjar NOT IN('3') or c.panjar IS NULL)
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,6), nm_rek4
                        UNION ALL
                        SELECT 2 as urut, kd_sub_kegiatan+'.'+LEFT(a.kd_rek6,8) as kode, LEFT(a.kd_rek6,8) as rek,  nm_rek5 as uraian, SUM(nilai) as nilai 
                        ,'' [tgl_bukti],0 [no_bukti] FROM trlpj a
                        INNER JOIN ms_rek5 b ON LEFT(a.kd_rek6,8)=b.kd_rek5
                        INNER JOIN trhtransout c ON a.no_bukti=c.no_bukti AND a.kd_skpd=c.kd_skpd
                        AND (c.panjar NOT IN('3') or c.panjar IS NULL)
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY kd_sub_kegiatan, LEFT(a.kd_rek6,8), nm_rek5
                        UNION ALL
                        SELECT 2 as urut, kd_sub_kegiatan+'.'+kd_rek6 as kode, kd_rek6 as rek,  nm_rek6 as uraian, SUM(nilai) as nilai
                        ,'' [tgl_bukti],0 [no_bukti]
                        FROM trlpj a
                        INNER JOIN trhtransout c ON a.no_bukti=c.no_bukti AND a.kd_skpd=c.kd_skpd
                        AND (c.panjar NOT IN('3') or c.panjar IS NULL)
                        WHERE no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND kd_sub_kegiatan='$kegiatan'
                        GROUP BY kd_sub_kegiatan, kd_rek6, nm_rek6
                        UNION ALL
                        SELECT 3 as urut, a.kd_sub_kegiatan+'.'+a.kd_rek6+'.1' as kode,'' as rek, c.ket+' \\ No BKU: '+a.no_bukti as uraian, sum(a.nilai) as nilai,
                        c.tgl_bukti,a.no_bukti 
                        FROM trlpj a 
                        INNER JOIN trhlpj b ON a.no_lpj=b.no_lpj AND a.kd_skpd=b.kd_skpd
                        INNER JOIN trhtransout c ON a.no_bukti=c.no_bukti AND a.kd_skpd=c.kd_skpd
                        AND (c.panjar NOT IN('3') or c.panjar IS NULL)
                        WHERE a.no_lpj='$nomor' AND a.kd_skpd='$cskpd'
                        AND a.kd_sub_kegiatan='$kegiatan'
                        GROUP BY a.kd_sub_kegiatan, a.kd_rek6,nm_rek6,a.no_bukti, ket,tgl_bukti
                        ORDER BY kode,tgl_bukti,no_bukti    ";      
                $query1 = $this->db->query($sql); 
                $total=0;
                $i=0;
                foreach ($query1->result() as $row) {
                    $kode=$row->kode;                    
                    $rek=$row->rek;                    
                    $urut=$row->urut;                    
                    $uraian= $row->uraian;
                    $nilai  = $row->nilai;
                    
                    if ($urut==1){
                    $i=$i+1;    
                        $cRet .="<tr>
                                    <td valign='top' align='center' ><i><b>$i</b></i></td>
                                    <td valign='top' align='left' ><i><b>$kode</b></i></td>
                                    <td valign='top' align='left' ><i><b>$uraian</b></i></td>
                                    <td valign='top' align='right'><i><b>".number_format($nilai,"2",",",".")."</b></i></td>
                                </tr>";
                    } else if ($urut==2){
                            $cRet .="<tr>
                                    <td valign='top' align='center' ><b></b></td>
                                    <td valign='top' align='left' ><b>$kode</b></td>
                                    <td valign='top' align='left' ><b>$uraian</b></td>
                                    <td valign='top' align='right'><b>".number_format($nilai,"2",",",".")."</b></td>
                                </tr>";
                    }else{
                        $total=$total+$nilai;
                        $cRet .="<tr>
                                    <td valign='top' align='left' ></td>
                                    <td valign='top' align='left' >$rek</td>
                                    <td valign='top' align='left' >$uraian</td>
                                    <td valign='top' align='right' >".$this->support->rp_minus($nilai)."</td>
                                </tr>"; 
                    }

                }
                

                $cRet .="
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' >&nbsp;</td>
                        </tr>                   
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' ><b>Total</b></td>
                            <td align='right' ><b>".number_format($total,"2",",",".")."</b></td>
                        </tr>                   
                        
                        ";


                $cRet .="</table><p>";              
//.$this->tukd_model->tanggal_format_indonesia($this->uri->segment(7)).
        $cRet .=" <table width='100%' style='font-size:12px' border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                    <tr>
                        <td valign='top' align='center' width='50%'>Disetujui   
                        <br>Kuasa Bendahara Umum Daerah</td>
                        <td valign='top' align='center' width='50%'>Pontianak, &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
                        <br>Telah diverifikasi
                        <br>Petugas</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>&nbsp;</td>
                        <td align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'><b><u>$nama</u></b><br>$pangkat</td>
                        <td align='center' width='50%'>___________________<br></td>
                    </tr>
                    <tr>
                        <td align='center' width='50%'>$ttd1</td>
                        <td align='center' width='50%'></td>
                    </tr>
                  </table>
                ";      

        $data['prev']= $cRet; 

        switch ($ctk)
        {
            case 0;
               echo ("<title> LPJ UP</title>");
                echo $cRet;     
                break;
            case 1;
                $this->support->_mpdf('',$cRet,10,10,10,'0',0,'');
               break;
        }
    }



function cetaklpjtu_ag(){
        
        $cskpd      =   $this->uri->segment(5);
        $cetak      =   $this->uri->segment(6);
        $no_sp2d    =   str_replace('abcdefghij','/',$this->uri->segment(3));
        $no_lpj     =   str_replace('abcdefghij','/',$this->uri->segment(8));
        $ttd1       =   str_replace('a',' ',$this->uri->segment(4));

          $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$cskpd'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah;
                   
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where kode='BUD' and nip='$ttd1'";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }
       
        /*      
                
        $sql1="SELECT LEFT(kd_kegiatan,18) as kd_program,nm_program,kd_kegiatan,nm_kegiatan
               FROM trhspp a INNER JOIN trhsp2d b ON a.no_spp = b.no_spp AND a.kd_skpd=b.kd_skpd WHERE no_sp2d = '$no_sp2d'";
        */
        
        
        
                $sql1="SELECT LEFT(c.kd_sub_kegiatan,7) as kd_program,
                (select nm_program from ms_program where LEFT(c.kd_sub_kegiatan,7)=kd_program)as nm_program,
                LEFT(c.kd_sub_kegiatan,12) as kd_kegiatan,
                (select nm_kegiatan from ms_kegiatan where LEFT(c.kd_sub_kegiatan,12)=kd_kegiatan)as nm_kegiatan,
                c.kd_sub_kegiatan,c.nm_sub_kegiatan
                FROM trhspp a INNER JOIN trhsp2d b ON a.no_spp = b.no_spp AND a.kd_skpd=b.kd_skpd 
                join trdspp c ON a.no_spp = c.no_spp AND a.kd_skpd=c.kd_skpd 
                WHERE no_sp2d = '$no_sp2d' group by nm_program,c.kd_sub_kegiatan,
                c.nm_sub_kegiatan,LEFT(c.kd_sub_kegiatan,18) ";
                
        $sql2=$this->db->query($sql1);
                 foreach ($sql2->result() as $row)
                {
                    $kd_sub_kegiatan     = $row->kd_sub_kegiatan;
                    $nm_sub_kegiatan     = $row->nm_sub_kegiatan;
                    $kd_kegiatan     = $row->kd_kegiatan;
                    $nm_kegiatan     = $row->nm_kegiatan;
                    $kd_program      = $row->kd_program;
                    $nm_program      = $row->nm_program;
                }      
               
               
        $cRet  =" <table style=\"border-collapse:collapse;font-size:14px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                    <tr>
                        <td align='center' ><b>PEMERINTAH PROVINSI KALIMANTAN BARAT</b></td>
                    </tr>
                    <tr>
                        <td align='center'><b>LAPORAN PERTANGGUNG JAWABAN TAMBAHAN UANG (TU)</b></td>
                    </tr>
                    <tr>
                        <td align='center'><b>BENDAHARA PENGELUARAN</b></td>
                    </tr>
                    <tr>
                        <td align='center'>&nbsp;</td>
                    </tr>
                    </table>                
                ";

        $cRet .=" <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                    <tr>
                        <td valign='top' align='left' width='15%'> OPD&nbsp;&nbsp;&nbsp;</td>
                        <td valign='top' align='center' width='3%'>:&nbsp;&nbsp;&nbsp;</td>
                        <td valign='top' align='left' > ".$cskpd.", ".$this->tukd_model->get_nama($cskpd,'nm_skpd','ms_skpd','kd_skpd')." </td>
                    </tr>
                    <tr>
                        <td valign='top' align='left' width='15%'>Program&nbsp;&nbsp;&nbsp;</td>
                        <td valign='top' align='center' width='3%'>:&nbsp;&nbsp;&nbsp;</td>
                        <td valign='top' align='left' >".$kd_program.", ".$nm_program."</td>
                    </tr>
                    <tr>
                        <td valign='top' align='left' width='15%'>Kegiatan&nbsp;&nbsp;&nbsp;</td>
                        <td valign='top' align='center' width='3%'>:&nbsp;&nbsp;&nbsp;</td>
                        <td valign='top' align='left' >".$kd_kegiatan.", ".$nm_kegiatan."</td>
                    </tr>

                    <tr>
                        <td valign='top' align='left' width='15%'>Sub Kegiatan&nbsp;&nbsp;&nbsp;</td>
                        <td valign='top' align='center' width='3%'>:&nbsp;&nbsp;&nbsp;</td>
                        <td valign='top' align='left' >".$kd_sub_kegiatan.", ".$nm_sub_kegiatan."</td>
                    </tr>
                    
                    <tr>
                        <td valign='top' align='left' width='15%'>No SP2D &nbsp;&nbsp;&nbsp;</td>
                        <td valign='top' align='center' width='3%'>:&nbsp;&nbsp;&nbsp;</td>
                        <td valign='top' align='left' >".$no_sp2d."</td>
                    </tr>
                    
                    </table>
                <br/>                   
                ";      

        $cRet .=" <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
                    <thead>
                    <tr>
                        <td align='center' bgcolor='#CCCCCC' width='30%'>KODE REKENING</td>
                        <td align='center' bgcolor='#CCCCCC' width='50%'>URAIAN</td>
                        <td align='center' bgcolor='#CCCCCC' width='20%'>JUMLAH</td>
                    </tr>
                    </thead>
                ";      


                $sql = "SELECT
                        kd_rek6,nm_rek6,SUM(nilai) as nilai
                        FROM
                            trlpj c
                        LEFT JOIN trhlpj d ON c.no_lpj = d.no_lpj AND c.kd_skpd=d.kd_skpd
                        WHERE
                        c.no_lpj = '$no_lpj' AND d.kd_skpd='$cskpd'
                        GROUP BY kd_rek6,nm_rek6 order by kd_rek6,nm_rek6";
                $query1 = $this->db->query($sql);   
                foreach($query1->result_array() as $resulte){ 
                        $kd_rek6=$resulte["kd_rek6"];
                        $nm_rek6=$resulte["nm_rek6"];
                        $nilai=number_format($resulte["nilai"],"2",",",".");
                        $cRet .="
                                    <tr>
                                        <td align='left' >$kd_rek6</td>
                                        <td align='left' >$nm_rek6</td>
                                        <td align='right' >$nilai</td>
                                    </tr>                   
                            ";
                        }
                        
                $sql = "SELECT
                        SUM(nilai) nilai
                        FROM
                            trlpj c
                        LEFT JOIN trhlpj d ON c.no_lpj = d.no_lpj
                        WHERE
                        c.no_lpj = '$no_lpj'";      
                $query1 = $this->db->query($sql);   
                foreach($query1->result_array() as $resulte){ 
                        $total=$resulte["nilai"];
                        }

                $sqlp = "SELECT SUM(a.nilai) AS nilai FROM trdspp a LEFT JOIN trhsp2d b ON b.no_spp=a.no_spp  
                         WHERE b.kd_skpd='$cskpd' AND b.jns_spp=3 AND  no_sp2d = '$no_sp2d' ";
                $queryp = $this->db->query($sqlp);      
                foreach($queryp->result_array() as $nlx){ 
                        $persediaan=$nlx["nilai"];
                }
                $cRet .="
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' >&nbsp;</td>
                            <td align='right' >&nbsp;</td>
                        </tr>                   
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' ><b>Total</b></td>
                            <td align='right' ><b>".number_format($total,"2",",",".")."</b></td>
                        </tr>                   
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' ><b>Tambahan Uang Persediaan Awal Periode</b></td>
                            <td align='right' ><b>".number_format($persediaan,"2",",",".")."</b></td>
                        <tr>
                            <td align='left' >&nbsp;</td>
                            <td align='left' ><b>Tambahan Uang Persediaan Ahir Periode</b></td>
                            <td align='right' ><b>".number_format($persediaan-$total,"2",",",".")."</b></td>
                        </tr>
                        </tr>
                        ";


                $cRet .="</table><p>";              

        $cRet .=" <table style=\"border-collapse:collapse;font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">
                    <tr>
                        <td valign='top' align='center' width='50%'>Mengetahui <br> $jabatan    </td>
                        <td valign='top' align='center' width='50%'>$daerah,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br> Telah diverifikasi<br>Petugas</td>
                    </tr>
                    <tr>
                        <td valign='top' align='center' width='50%'>&nbsp;</td>
                        <td valign='top' align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign='top' align='center' width='50%'>&nbsp;</td>
                        <td valign='top' align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign='top' align='center' width='50%'>&nbsp;</td>
                        <td valign='top' align='center' width='50%'>&nbsp;</td>
                    </tr>
                    <tr>
                        <td valign='top' align='center' width='50%'><b><u>$nama</u></b><br>$pangkat</td>
                        <td valign='top' align='center' width='50%'>__________________</td>
                    </tr>
                    <tr>
                        <td valign='top' align='center' width='50%'>$nip</td>
                        <td valign='top' align='center' width='50%'></td>
                    </tr>
                  </table>
                ";  

        $data['prev']= $cRet;    
      if($cetak==0){
 
         $data['prev']= $cRet;    
         echo ("<title>LPJ TU</title>");
         echo $cRet;}
         else{

        $this->support->_mpdf('',$cRet,10,10,10,'0',0,'');
}
     } 

    // 
}

?>