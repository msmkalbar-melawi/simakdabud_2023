<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Penetapan extends CI_Controller {


	public $ppkd1 = "4.02.02.01";
	public $ppkd2 = "4.02.02.02";
	
	function __contruct()
	{	
		parent::__construct();
  
	}

    //START PENETAPAN
function penetapan_pendapatan(){
        $data['page_title']= 'INPUT PENETAPAN';
        $this->template->set('title', 'INPUT PENETAPAN');   
        $this->template->load('template','tukd/pendapatan/penetapan',$data) ; 
    }

function load_tetap() {
        $skpd  = $this->session->userdata('kdskpd');
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where = "";// " kd_skpd='$skpd'";
        if ($kriteria <> ''){                               
            $where=" where (upper(no_tetap) like upper('%$kriteria%') or tgl_tetap like '%$kriteria%' or kd_skpd like'%$kriteria%' or
            upper(keterangan) like upper('%$kriteria%'))";            
        }
        
        $sql = "SELECT * from tr_tetap $where order by no_tetap";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_tetap'      => $resulte['no_tetap'],
                        'tgl_tetap'     => $resulte['tgl_tetap'], //$this->tukd_model->rev_date($resulte['tgl_tetap']),
                        'kd_skpd'       => $resulte['kd_skpd'],
                        'keterangan'    => $resulte['keterangan'],    
                        'nilai'         => number_format($resulte['nilai']),
                        'kd_rek6'       => $resulte['kd_rek6'],
                        'kd_rek'        => $resulte['kd_rek_lo']                                                                                            
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }


    function skpd() {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_skpd,nm_skpd,jns FROM ms_skpd where upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%') order by kd_skpd ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd'   => $resulte['kd_skpd'],  
                        'nm_skpd'   => $resulte['nm_skpd'],
                        'jns'       => $resulte['jns']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
            $query1->free_result();
    }


    function ambil_rek_tetap() {
        $lccr = $this->input->post('q');
        $lckdskpd = $this->uri->segment(3);
               
        $sql = "SELECT a.kd_sub_kegiatan,a.kd_rek6 as kd_rek6,b.nm_rek6 AS nm_rek,b.map_lo as kd_rek FROM 
        trdrka a left join ms_rek6 b on a.kd_rek6=b.kd_rek6 where a.kd_skpd = '$lckdskpd' and left(a.kd_rek6,1)='4' and 
        (upper(a.kd_rek6) like upper('%$lccr%') or b.nm_rek6 like '%$lccr%')";
        
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id'                => $ii,
                        'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],        
                        'kd_rek6'           => $resulte['kd_rek6'],
                        'kd_rek'            => $resulte['kd_rek'],  
                        'nm_rek'            => $resulte['nm_rek']                  
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    }


    function hapus_tetap(){
        //no:cnomor,skpd:cskpd
        $nomor = $this->input->post('no');
        $skpd = $this->input->post('skpd');
        
        $sql = "delete from tr_tetap where no_tetap='$nomor' and kd_skpd = '$skpd'";
        $asg = $this->db->query($sql);
        
        // $sql1 = "delete from trhju_pkd where no_voucher='$nomor' and kd_skpd = '$skpd'";
        // $asg1 = $this->db->query($sql1);
        
        // $sql2 = "delete from trdju_pkd where no_voucher='$nomor'";
        // $asg2 = $this->db->query($sql2);
        
        // $sql3 = "delete from trhju where no_voucher='$nomor' and kd_skpd = '$skpd'";
        // $asg3 = $this->db->query($sql3);
        
        // $sql4 = "delete from trdju where no_voucher='$nomor'";
        // $asg4 = $this->db->query($sql4);

        // $sql5 = "delete from trhju_pkd where no_voucher='$nomor' and kd_skpd = '$skpd'";
        // $asg5 = $this->db->query($sql5);
        // $sql6 = "delete from trdju_pkd where no_voucher='$nomor'";
        $asg6 = $this->db->query($sql);
        if ($asg){
            echo '1'; 
        } else{
            echo '0';
        }
                       
    }


    function simpan_tetap(){
        $tabel          = $this->input->post('tabel');
        $nomor          = $this->input->post('no');
        $tgl            = $this->input->post('tgl');
        $skpd           = $this->input->post('skpd');
        $ket            = $this->input->post('ket');
        $kd_rek6        = $this->input->post('kdrek');
        $kd_rek_lo      = $this->input->post('rek');
        $lnil_rek       = $this->input->post('nilai');
        $nmskpd         = $this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd');
        $nmrek          = $this->tukd_model->get_nama($kd_rek_lo,'nm_rek6','ms_rek6','kd_rek6');
        $kd_sub_kegiatan= $this->tukd_model->get_kegiatan($skpd,'kd_sub_kegiatan','ms_sub_kegiatan','kd_sub_kegiatan');
        $usernm         = $this->session->userdata('pcNama');
        $last_update    = date('Y-m-d H:i:s');
        
        $sql = "DELETE from tr_tetap where kd_skpd='$skpd' and no_tetap='$nomor'";
        $asg = $this->db->query($sql);
        $sql1 = "DELETE from trhju_pkd where kd_skpd='$skpd' and no_voucher='$nomor'";
        $asg1 = $this->db->query($sql1);
        $sql2 = "DELETE from trdju_pkd where no_voucher='$nomor'";
        $asg2 = $this->db->query($sql2);
       
            $sql = "INSERT into tr_tetap(no_tetap,tgl_tetap,kd_skpd,kd_rek6,kd_sub_kegiatan,kd_rek_lo,nilai,keterangan,user_name) 
            values('$nomor','$tgl','$skpd','$kd_rek_lo','$kd_sub_kegiatan','$kd_rek6',$lnil_rek,'$ket','$usernm')";
            $asg = $this->db->query($sql);


            //PROSES JURNAL PINDAH KE TRIGGERS
            
            // $sql1 = "INSERT into trhju_pkd(no_voucher,tgl_voucher,kd_skpd,nm_skpd,ket,tgl_update,username,total_d,total_k,tabel) 
            // values('$nomor','$tgl','$skpd','$nmskpd','$ket','$last_update','$usernm',$lnil_rek,$lnil_rek,'3')";
            // $asg1 = $this->db->query($sql1);
            
            // $sql2 = "INSERT into trdju_pkd(no_voucher,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
            // values('$nomor','1130101','Piutang Pajak',$lnil_rek,0,'D','','1','1')";
            // $asg2 = $this->db->query($sql2);
            
            // $sql3 = "INSERT into trdju_pkd(no_voucher,kd_rek5,nm_rek5,debet,kredit,rk,jns,urut,pos) 
            // values('$nomor','$kd_rek6','$nmrek',0,$lnil_rek,'K','','2','1')";
            // $asg3 = $this->db->query($sql3);
    } 

    //END PENETAPAN



	
	
}	

?>