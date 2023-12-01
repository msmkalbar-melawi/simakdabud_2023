<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 
 */

class master_ttd extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
    
    function load_ttd_unit($skpd='',$lccr='') {          
        $sql = "SELECT * FROM ms_ttd WHERE kd_skpd='$skpd' AND (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%'))";   
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;        
        foreach($query1->result_array() as $resulte)
        { 
            
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],
                        'urut' => $resulte['id_ttd'],   
                        'nama' => $resulte['nama']      
                        );
                        $ii++;
        }                     
        return json_encode($result);      
    }

    function load_ttd_bud($skpd='',$lccr=''){


        $sql = "SELECT * FROM ms_ttd WHERE kode='bud' AND (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%'))";

        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;        
        foreach($mas->result_array() as $resulte){     
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'urut' => $resulte['id_ttd'],                           
                        'jabatan' => $resulte['jabatan']
                        );
                        $ii++;
        }           
           
        return json_encode($result);
           
    }


    function load_skpd_bp(){    
        $sql = "SELECT kd_skpd,nm_skpd from ms_skpd order by kd_skpd";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte){ 
            $result[] = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd'],  
                       
                        );
                        $ii++;
        }
        return json_encode($result);
    }

    function load_tanda_tangan($skpd,$lccr) {       
        
        if($skpd==''){
            $skpd=$this->session->userdata('kdskpd');
        }

        $sql = "SELECT * FROM ms_ttd WHERE (left(kd_skpd,22)= left('$skpd',22) AND kode in ('PA','KPA'))  AND (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%'))";   
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;        
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'jabatan' => $resulte['jabatan'],
                        'id_ttd' => $resulte['id_ttd']       
                        );
                        $ii++;
        }           
           
        return json_encode($result);
           
    }

    function load_bendahara_p($kdskpd,$lccr){
    
        $query1 = $this->db->query("SELECT nip,nama,id_ttd from ms_ttd where kd_skpd='$kdskpd' AND kode='BK' AND (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%'))");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'id_ttd' => $resulte['id_ttd']
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);   
    }

    function load_ppk_pptk($kdskpd,$lccr){
    
        $query1 = $this->db->query("SELECT nip,nama,id_ttd from ms_ttd where kd_skpd='$kdskpd' AND kode in ('PPTK','PPK') AND (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%'))");  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'id_ttd' => $resulte['id_ttd']
                        );
                        $ii++;
        }
           
           //return $result;
           echo json_encode($result);   
    }


}