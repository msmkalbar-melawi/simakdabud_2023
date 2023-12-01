<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Spp extends CI_Controller {


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

function config_nm_user() {
        $result = array();
         $usernm  = $this->session->userdata('pcNama');
		 $result = $usernm;
         echo json_encode($result);
    	   
	}

function config_bank2(){
		
		$sql = "SELECT * from ms_bank order by kode";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
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



function pengesahan_spp_tu() {
        $data['page_title']= 'PENGESAHAN SPP TU';
        $this->template->set('title', 'PENGESAHAN SPP TU');   
        $this->template->load('template','tukd/spp/pengesahan_spp',$data) ; 
    }

function load_spp() {
		$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
		$offset = ($page-1)*$rows;
		$kd_skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where    = " AND jns_spp <> '1' AND jns_spp <> '2' ";
        if ($kriteria <> ''){                               
            $where="and (upper(no_spp) like upper('%$kriteria%') or tgl_spp like '%$kriteria%' or upper(kd_skpd) like 
                    upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
        }
        
        $sql = "SELECT count(*) as tot from trhspp WHERE kd_skpd = '$kd_skpd' $where ";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
		//alert('aaa');
		
        $sql = "SELECT TOP $rows * from trhspp WHERE kd_skpd = '$kd_skpd' $where 
		and no_spp not in (SELECT TOP $offset no_spp from trhspp WHERE kd_skpd = '$kd_skpd' $where) order by no_spp,kd_skpd";
       // $sql = "SELECT * from trhspp WHERE kd_skpd = '$kd_skpd' $where order by no_spp,kd_skpd";
		
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id' => $ii,        
                        'no_spp' => $resulte['no_spp'],
                        'tgl_spp' => $resulte['tgl_spp'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],    
                        'jns_spp' => $resulte['jns_spp'],
                        'jns_beban' => $resulte['jns_beban'],
                        'keperluan' => $resulte['keperluan'],
                        'bulan' => $resulte['bulan'],
                        'no_spd' => $resulte['no_spd'],
                        'bank' => $resulte['bank'],
                        'nmrekan' => $resulte['nmrekan'],
                        'no_rek' => $resulte['no_rek'],
                        'npwp' => $resulte['npwp'],
                        'status' =>$resulte['status'],
                        'kd_sub_kegiatan'=>$resulte['kd_sub_kegiatan'],
                        'nm_sub_kegiatan'=>$resulte['nm_sub_kegiatan'],
                        'kd_program'=>$resulte['kd_program'],
                        'nm_program'=>$resulte['nm_program'],
                        'dir'=>$resulte['pimpinan'],
                        'no_tagih'=>$resulte['no_tagih'],
                        'tgl_tagih'=>$resulte['tgl_tagih'],
                        'alamat'=>$resulte['alamat'],
                        'lanjut'=>$resulte['lanjut'],
                        'kontrak'=>$resulte['kontrak'],
                        'tgl_mulai'=>$resulte['tgl_mulai'],
                        'tgl_akhir'=>$resulte['tgl_akhir'],
                        'sts_tagih'=>$resulte['sts_tagih'],
                        'sp2d_batal'=>$resulte['sp2d_batal'],
                        'ket_batal'=>$resulte['ket_batal']     
                        );
                        $ii++;
        }
		$result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);
	}

function spd1() {

        $skpd  = $this->session->userdata('kdskpd');
        $sql   = " SELECT no_spd, tgl_spd from trhspd where kd_skpd='$skpd' and status='1' order by tgl_spd,no_spd";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'no_spd' => $resulte['no_spd'],  
                        'tgl_spd' => $resulte['tgl_spd']  
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result();	   
	}

function kegi() {
        $spd=$this->input->post('spd');
        $lccr = $this->input->post('q');
		 
		$sql  = "SELECT distinct a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_program,a.nm_program, 0 nilai 
						FROM trdspd a where a.no_spd='$spd'
						AND (upper(a.kd_sub_kegiatan) like upper('%$lccr%') or upper(a.nm_sub_kegiatan) like upper('%$lccr%')) 
						order by  a.kd_sub_kegiatan"; 
		 
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],  
                        'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],  
                        'kd_program' => $resulte['kd_program'], 
                        'nm_program' => $resulte['nm_program'], 
                        'nilai_spd' => $resulte['nilai']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();	   
	}

function select_data1($spp='') {
    $kd_skpd  = $this->session->userdata('kdskpd');
    $spp = $this->input->post('spp');
    $sql = "SELECT kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,sumber,(select nm_sumberdana from hsumber_dana where kd_sumberdana=sumber)as nmsumber,
    nilai,no_bukti FROM trdspp WHERE no_spp='$spp' ORDER BY no_bukti,kd_sub_kegiatan,kd_rek6";                   
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'idx'        => $ii,
                        'kdsubkegiatan' => $resulte['kd_sub_kegiatan'],
                        'nmsubkegiatan' => $resulte['nm_sub_kegiatan'],       
                        'kdrek6'     => $resulte['kd_rek6'],  
                        'nmrek6'     => $resulte['nm_rek6'],  
                        'nilai1'     => number_format($resulte['nilai'],"2",".",","),
                        'nilai'      => number_format($resulte['nilai']),
                        // 'sisa'       => number_format($resulte['sisa']),
                        // 'sis'        => $resulte['sisa'],
                        'sumber'     => $resulte['sumber'], 
                        'nmsumber'     => $resulte['nmsumber'], 

                        'no_bukti'   => $resulte['no_bukti']  
                        );
                        $ii++;
        }
           
           echo json_encode($result);
     $query1->free_result();
    }

function load_trskpd_ar_2() 
    { 
        $cskpd  =  '' ;
        $cskpd  =  $this->input->post('kdskpd');
        $dcskpd  = substr($cskpd,0,17);  
        $sql    = "SELECT kd_sub_kegiatan, nm_sub_kegiatan FROM trskpd where left(kd_skpd,17) = '$dcskpd' order by kd_sub_kegiatan ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],  
                        'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan']
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result();        
    }
function setuju_spp() {
    $spp = $this->input->post('no_spp');
    $kdskpd = $this->input->post('kd_skpd');
	$sql = "UPDATE trhspp SET sts_setuju='1' WHERE no_spp='$spp' AND kd_skpd='$kdskpd'";
	$asg = $this->db->query($sql);  
	if ($asg > 0){  	
                    echo '2';
                    exit();
               } else {
                    echo '0';
                    exit();
               }
	}


	function batal_spp(){
        $skpd     = $this->session->userdata('kdskpd');
        $nospp      = $this->input->post('nospp');
        $ket      = $this->input->post('ket');  
        $jns_spp      = $this->input->post('jns_spp');          
        $usernm      = $this->session->userdata('pcNama');
        $last_update=  date('d-m-y H:i:s');
        
        $sql = "UPDATE trhspp set sp2d_batal='1',ket_batal='$ket',user_batal='$usernm',tgl_batal='$last_update' where no_spp='$nospp'"; 
        $asg = $this->db->query($sql);  
        
        if($jns_spp=='6'){              
            $query1 = "SELECT ltrim(no_tagih) [no_tagih] from trhspp where no_spp='$nospp'"; 
            $hquery1 = $this->db->query($query1);
            $no_tagih = $hquery1->row('no_tagih');
            if($no_tagih!=''){
                $sql1 = "UPDATE trhspp set no_tagih='',kontrak='',sts_tagih='0',nmrekan='',pimpinan='' where no_spp='$nospp'"; 
                $asg1 = $this->db->query($sql1);  
                $sql2 = "UPDATE trhtagih set sts_tagih='0' where no_bukti='$no_tagih'"; 
                $asg2 = $this->db->query($sql2);  
            }
        }
        
        if($asg){
            echo '1';
        }else{
            echo '0';
        }
    }


   function load_sum_spp()
    {
        $xspp = $this->input->post('spp');
        $skpd = $this->session->userdata('kdskpd');
        $query1 = $this->db->query("SELECT sum(nilai) as rektotal from trdspp where no_spp = '$xspp' ");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'rektotal'  =>  $resulte['rektotal'],
                        'rektotal1' => $resulte['rektotal']                         
                        );
                        $ii++;
        }
        echo json_encode($result);
        $query1->free_result(); 
    }
    
function skpd_2() {
		$kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd where kd_skpd = '$kd_skpd' ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result(); 	  
	}
    
    function select_data2() {
    $kegi=$this->input->post('giat');
        $sql = "SELECT a.kd_sub_kegiatan,c.nm_sub_kegiatan,a.kd_rek6,b.nm_rek6,a.nilai,(SELECT SUM(nilai) FROM trdspp WHERE kd_sub_kegiatan =a.kd_sub_kegiatan AND kd_rek6=a.kd_rek6)AS total 
                FROM trdrka a INNER JOIN ms_rek6 b ON a.kd_rek6=b.kd_rek6 INNER JOIN trskpd d ON a.kd_sub_kegiatan=d.kd_sub_kegiatan INNER JOIN m_giat c ON d.kd_sub_kegiatan1=c.kd_sub_kegiatan
                WHERE a.kd_sub_kegiatan ='$kegi' ORDER BY a.kd_rek6";                   
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                         'id' => $ii,
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                        'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],       
                        'kd_rek6' => $resulte['kd_rek6'],  
                        'nm_rek6' => $resulte['nm_rek6'],  
                        'nilai' => number_format($resulte['nilai'],"2",",","."),
                        'total' => number_format($resulte['total'],"2",",","."),
                        'a' => $resulte['nilai'],
                        'b' => $resulte['total']                        
                        );
                        $ii++;
        }
           
           echo json_encode($result);
     $query1->free_result();
    }




// -----
}

?>