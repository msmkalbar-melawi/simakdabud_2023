<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Penerimaan extends CI_Controller {


	public $ppkd1 = "4.02.02.01";
	public $ppkd2 = "4.02.02.02";
	
	function __contruct()
	{	
		parent::__construct();
  
	}

    //START PENERIMAAN
function config_tahun() 
    {
        $result = array();
         $tahun  = $this->session->userdata('pcThang');
         $result = $tahun;
         echo json_encode($result);
           
    }

function penerimaan_piutang()
    {
        $data['page_title']= 'INPUT PENERIMAAN PIUTANG';
        $this->template->set('title', 'INPUT PENERIMAAN PUTANG');   
        $this->template->load('template','tukd/pendapatan/penerimaan_piutang',$data) ; 
    }

function penerimaan_skpd()
    {
        $data['page_title']= 'INPUT PENERIMAAN';
        $this->template->set('title', 'INPUT PENERIMAAN');   
        $this->template->load('template','tukd/pendapatan/penerimaan',$data) ; 
    }

function load_terima_tl()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" AND no_terima LIKE '%$kriteria%' OR tgl_terima LIKE '%$kriteria%'";            
        }
       
        $sql = "SELECT count(*) as total from tr_terima WHERE kd_skpd = '$skpd' $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        
        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        
        $sql = "
        SELECT top $rows no_terima,no_tetap,tgl_terima,tgl_tetap,kd_skpd,keterangan as ket,nilai, kd_rek6,kd_rek_lo,kd_sub_kegiatan,sts_tetap from tr_terima WHERE kd_skpd='$skpd' AND jenis='2' 
        $where AND no_terima NOT IN (SELECT TOP $offset no_terima FROM tr_terima WHERE kd_skpd='$skpd' $where ORDER BY tgl_terima,no_terima ) ORDER BY tgl_terima,no_terima ";

        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(  
                        'id' => $ii,        
                        'no_terima' => $resulte['no_terima'],
                        'no_tetap' => $resulte['no_tetap'],
                        'tgl_terima' => $resulte['tgl_terima'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['ket'],    
                        'nilai' => number_format($resulte['nilai']),
                        'kd_rek6' => $resulte['kd_rek6'],
                        'kd_rek' => $resulte['kd_rek_lo'],
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                        'tgl_tetap' => $resulte['tgl_tetap'],
                        'sts_tetap' =>$resulte['sts_tetap']                                                                                            
                        );
                        $ii++;
        }
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
    }


function config_skpd()
    {
        $skpd     = $this->session->userdata('kdskpd');
        $sql = "SELECT a.kd_skpd,a.nm_skpd,b.status_rancang,b.status,b.status_sempurna,b.status_ubah FROM  ms_skpd a LEFT JOIN trhrka b ON a.kd_skpd=b.kd_skpd 
                WHERE a.kd_skpd = '$skpd'";
        $query1 = $this->db->query($sql);  
        $test = $query1->num_rows();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'statu' => $resulte['status'],
                        'status_ubah' => $resulte['status_ubah'],
                        'status_rancang' => $resulte['status_rancang'],
                        'status_sempurna' => $resulte['status_sempurna']
                        );
                        $ii++;
        }
        
        echo json_encode($result);
        $query1->free_result();   
    }


function ambil_rek_tetap() 
    {
        $lccr = $this->input->post('q');
        $lckdskpd = $this->uri->segment(3);
        $sql = "SELECT distinct a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek, c.nm_rek5, a.kd_sub_kegiatan FROM 
        trdrka a left join ms_rek6 b on a.kd_rek6=b.kd_rek6 left join ms_rek5 c on left(a.kd_rek6,8)=c.kd_rek5 
        where a.kd_skpd = '$lckdskpd' and left(a.kd_rek6,1)='4' and 
        (upper(a.kd_rek6) like upper('%$lccr%') or b.nm_rek6 like '%$lccr%') order by kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
            { 
            $result[] = array(
                        'id' => $ii,        
                        'kd_rek6' => $resulte['kd_rek6'],
                        'kd_rek' => $resulte['kd_rek'],  
                        'nm_rek' => $resulte['nm_rek'],
                        'nm_rek5' => $resulte['nm_rek5'],
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan']                  
                        );
                        $ii++;
            }
           
        echo json_encode($result);
           
    }


function load_no_tetap() 
    {
        $kd_skpd  = $this->session->userdata('kdskpd');  
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ='';
        $where2 ='';

        if ($kriteria <> ''){                               
            $where="AND (upper(no_tetap) like upper('%$kriteria%') or tgl_tetap like '%$kriteria%' or kd_skpd like'%$kriteria%' or
            upper(keterangan) like upper('%$kriteria%')) and ";
            // $where2 =' AND no_tetap not in(select no_tetap from tr_terima)';            
        }

        //$sql = "SELECT * from tr_tetap $where order by no_tetap";
       $sql = "SELECT no_tetap, tgl_tetap, kd_skpd, keterangan, nilai, kd_rek6, kd_rek_lo,
                (SELECT a.nm_rek6 FROM ms_rek6 a WHERE a.kd_rek6=tr_tetap.kd_rek6) as nm_rek FROM tr_tetap WHERE no_tetap not in(select ISNULL(no_tetap,'') from tr_terima) $where2
                UNION ALL
                SELECT no_tetap,tgl_tetap,kd_skpd,keterangan,ISNULL(nilai,0)-ISNULL(nilai_terima,0) as nilai,kd_rek6,kd_rek_lo,a.nm_rek 
                FROM 
                (SELECT *,(SELECT a.nm_rek6 FROM ms_rek6 a WHERE a.kd_rek6=tr_tetap.kd_rek6) as nm_rek FROM tr_tetap $where )a
                LEFT JOIN
                (SELECT no_tetap as tetap,ISNULL(SUM(nilai),0) as nilai_terima from tr_terima $where GROUP BY no_tetap)b
                ON a.no_tetap=b.tetap
                WHERE nilai !=nilai_terima 
                order by no_tetap";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id'            => $ii,        
                        'no_tetap'      => $resulte['no_tetap'],
                        'tgl_tetap'     => $resulte['tgl_tetap'],
                        'kd_skpd'       => $resulte['kd_skpd'],
                        'keterangan'    => $resulte['keterangan'],    
                        'nilai'         => $resulte['nilai'],
                        'kd_rek6'       => $resulte['kd_rek6'],
                        'nm_rek6'       => $resulte['nm_rek'],
                        'kd_rek_lo'     => $resulte['kd_rek_lo']                                                                                           
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }

function cek_simpan()
    {
        $nomor      = $this->input->post('no');
        $tabel      = $this->input->post('tabel');
        $field      = $this->input->post('field');
        $field2     = $this->input->post('field2');
        $tabel2     = $this->input->post('tabel2');
        $kd_skpd    = $this->session->userdata('kdskpd');        
        if ($field2==''){
        $hasil=$this->db->query("SELECT count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd' ");
        } else{
        $hasil=$this->db->query("SELECT count(*) as jumlah FROM (select $field as nomor FROM $tabel WHERE kd_skpd = '$kd_skpd' UNION ALL
        SELECT $field2 as nomor FROM $tabel2 WHERE kd_skpd = '$kd_skpd')a WHERE a.nomor = '$nomor' ");      
        }
        foreach ($hasil->result_array() as $row){
        $jumlah=$row['jumlah']; 
        }
        if($jumlah>0){
        $msg = array('pesan'=>'1');
        echo json_encode($msg);
        } else{
        $msg = array('pesan'=>'0');
        echo json_encode($msg);
        }
        
    }
function simpan_terima_ag(){
            $tabel          = $this->input->post('tabel');
            $lckolom        = $this->input->post('kolom');
            $lcnilai        = $this->input->post('nilai');
            $cid            = $this->input->post('cid');
            $lcid           = $this->input->post('lcid');
            
            $sql            = "insert into $tabel $lckolom values $lcnilai";
            $asg            = $this->db->query($sql);
            if ( $asg > 0 ) {
                echo '2';
            } else {
                echo '0';
                exit();
            }
        
    }

function update_terima_ag()
    {
            $tabel          = $this->input->post('tabel');
            $lckolom        = $this->input->post('kolom');
            $lcnilai        = $this->input->post('nilai');
            $cid            = $this->input->post('cid');
            $lcid           = $this->input->post('lcid');
            $nohide         = $this->input->post('no_hide');
            $skpd           = $this->session->userdata('kdskpd');
            
            
            $sql = "delete from tr_terima where kd_skpd='$skpd' and no_terima='$nohide'";
            $asg = $this->db->query($sql);
            if ($asg){
                $sql            = "insert into $tabel $lckolom values $lcnilai";
                $asg            = $this->db->query($sql);
                if ( $asg > 0 ) {
                    echo '2';
                } else {
                    echo '0';
                    exit();
                }
            }
    }




function load_terima() 
    {
        $skpd     = $this->session->userdata('kdskpd');
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $kode_skpd = $this->input->post('kdskpd');
        $tanggal_cari = $this->input->post('tgl_cari');
        $where ='';
        $where2 ='';
        $where3 ='';

        if ($kriteria <> ''){                               
            $where="AND no_terima LIKE '%$kriteria%' OR tgl_terima LIKE '%$kriteria%' OR keterangan LIKE '%$kriteria%' ";
        }

        if ($kode_skpd <> '') {
            $where2 = "AND kd_skpd = '$kode_skpd'";
        }
        if ($tanggal_cari <> '') {
            $where3 = "AND tgl_terima = '$tanggal_cari'";
        }        

         // print_r($where.$kriteria);die();

        $sql = "SELECT count(*) as total from tr_terima WHERE kd_skpd = '$skpd' $where" ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();

        
        $spjbulan = $this->tukd_model->cek_status_spj_pend($skpd);
       
        
        $sql1 = "SELECT top $rows no_terima,no_tetap,tgl_terima,tgl_tetap,kd_skpd,keterangan as ket, sumber,
        nilai, kd_rek6,kd_rek_lo,kd_sub_kegiatan,sts_tetap,(CASE WHEN month(tgl_terima)<='$spjbulan' THEN 1 ELSE 0 END) ketspj,user_name,kunci FROM tr_terima
        WHERE (jenis <> '2' or jenis is null)       
        AND no_terima NOT IN (SELECT TOP $offset no_terima 
        FROM tr_terima ORDER BY tgl_terima,no_terima) $where2 $where3 $where
        ORDER BY tgl_terima,no_terima";


         // print_r($sql1);die();


        $query1 = $this->db->query($sql1); 
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
           if ($resulte['ketspj']=='1'){
                $s='&#10004';
            }else{
                $s='&#10008';           
            }
            
            
            $row[] = array(  
                        'id'                => $ii,        
                        'no_terima'         => $resulte['no_terima'],
                        'no_tetap'          => $resulte['no_tetap'],
                        'tgl_terima'        => $resulte['tgl_terima'],
                        'kd_skpd'           => $resulte['kd_skpd'],
                        'keterangan'        => $resulte['ket'],    
                        'nilai'             => number_format($resulte['nilai'],2,'.',','),
                        'kd_rek6'           => $resulte['kd_rek6'],
                        'kd_rek'            => $resulte['kd_rek_lo'],
                        'sumber'            => $resulte['sumber'],
                        'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                        'tgl_tetap'         => $resulte['tgl_tetap'],
                        'sts_tetap'         =>$resulte['sts_tetap'],                                                                                            
                        'spj'               =>$resulte['ketspj'],
                        'user_nm'           =>$resulte['user_name'],          
                        'kunci'             =>$resulte['kunci'],        
                        'simbol'            =>$s                                                                                            
                        );
                        $ii++;
        }
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
    }



function load_pengirim() 
    {        
        $skpd = $this->session->userdata('kdskpd');               
        $lccr = $this->input->post('q');
        
        // if(substr($skpd,0,7)=='3.13.01'){
        //     $where = "kd_skpd='$skpd'";
        // }else{
            $where = "LEFT(kd_skpd,5)=LEFT('$skpd',5)";
        // }
        
        $sql = "SELECT * from ms_pengirim WHERE $where 
                AND (UPPER(kd_pengirim) LIKE UPPER('%$lccr%') OR UPPER(nm_pengirim) LIKE UPPER('%$lccr%')) 
                order by cast(kd_pengirim as int)";                                              
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
            { 
                $result[] = array(
                        'id'            => $ii,        
                        'kd_pengirim'   => $resulte['kd_pengirim'],  
                        'nm_pengirim'   => $resulte['nm_pengirim'],
                        'kd_skpd'       => $resulte['kd_skpd']
                        );
                        $ii++;
            }
           
        echo json_encode($result);
        $query1->free_result();        
    }

    function load_skpd() 
    {                    
        $lccr = $this->input->post('q');
        
        $sql = "SELECT * from ms_skpd WHERE (UPPER(kd_skpd) LIKE UPPER('%$lccr%') OR UPPER(nm_skpd) LIKE UPPER('%$lccr%')) 
        order by kd_skpd";                                              
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                'id'            => $ii,        
                'kd_skpd'   => $resulte['kd_skpd'],  
                'nm_skpd'   => $resulte['nm_skpd']
            );
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();        
    }


function hapus_terima(){
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        
        $sql    = "delete from tr_terima where no_terima='$nomor' and kd_skpd = '$skpd'";
        $asg    = $this->db->query($sql);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    }


    //END PENERIMAAN



	
	
}	

?>