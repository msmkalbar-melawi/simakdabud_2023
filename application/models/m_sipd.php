<?php
class M_sipd extends CI_Model {
 
    
    function simpan_upload($data){

    	 $sql1 ="INSERT into tb_json([file],id,title)values ('".$data['file']."','".$data['id']."','".$data['title']."')";
        $this->db->query($sql1);
        return $this->db->affected_rows();
    }
 
}