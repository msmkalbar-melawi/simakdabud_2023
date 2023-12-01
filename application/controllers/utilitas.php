<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Controller master data fungsi
 */

class Utilitas extends CI_Controller {
    
	function genSql($nmtabel){

		$tipef=array();
		$namaf=array();
		$nullf=array();

		$txtqry="delete from $nmtabel ;".chr(13);
		$txtqry=$txtqry."insert into $nmtabel(";
        $query1 = $this->db->query(" SHOW FIELDS FROM $nmtabel ");  
		$jum_field=$query1->num_rows();		
		$x=0;
		foreach($query1->result_array() as $row){
			if ($x<=$jum_field-1){
				$tipef[$x]=trim($row['Type']);
				$namaf[$x]=trim($row['Field']);
				$nullf[$x]=trim($row['Null']);

				if ($x==0){
					$nmfield=trim($row['Field']);	    
					$txtqry=$txtqry.$nmfield;
				}else{
					$nmfield=",".trim($row['Field']);	    
					$txtqry=$txtqry.$nmfield;	
				}
			}
			$x++;
		}



		$txtqry1=$txtqry.') values(';
		$txtqry2='';
			
        $query2 = $this->db->query(" select * from $nmtabel ");  
		$jum_field=$query2->num_fields();	
		$jum_rows=$query2->num_rows();	
		$x=0;
		foreach($query2->result_array() as $row){
			$x++;

			if ((substr(trim($tipef[0]),0,8)=='longtext') or (substr($tipef[0],0,7)=='varchar') or (substr($tipef[0],0,4)=='date') or (substr($tipef[0],0,4)=='char')) {		
				if ((trim($row[$namaf[0]])=="") or (trim($row[$namaf[0]])=="<NULL>")){$is="null"; }else{ $is=trim($row[$namaf[0]]); }
				$is=str_replace("'","",$is);
				if($is=="null"){
					$isi=$is;				
					if($nullf[0]=='NO') $isi="''";	
				}else{
					$isi="'".$is."'";				
				}
				
				$txtqry2=$txtqry2.$isi;
			}else{
				if (trim($row[$namaf[0]])==""){$is="null"; }else{ $is=trim($row[$namaf[0]]); }
				$is=str_replace("'","",$is);
				if($is=="null"){
					$isi=$is;				
					if($nullf[0]=='NO') $isi="''";	
				}else{
					$isi="'".$is."'";				
				}
				$txtqry2=$txtqry2.$isi;
			}

			for ($i=1;$i<=$jum_field-1;$i++){
				if ((substr(trim($tipef[$i]),0,8)=='longtext') or (substr($tipef[$i],0,7)=='varchar') or (substr($tipef[$i],0,4)=='char') ) {
					
					if ((trim($row[$namaf[$i]])=="")or(trim($row[$namaf[$i]])=="<NULL>")){$is="null"; }else{ $is=trim($row[$namaf[$i]]); }
					$is=str_replace("'","",$is);
					if($is=="null"){
						$isi=",".$is;				
						if($nullf[$i]=='NO') $isi=",''";	
					}else{
						$isi=",'".$is."'";
					}
					$txtqry2=$txtqry2.$isi;
				}elseif(substr($tipef[$i],0,4)=='date'){
					if (trim($row[$namaf[$i]])==""){$is="0000-00-00"; }else{ $is=trim($row[$namaf[$i]]); }
					$is=str_replace("'","",$is);
					if($is=="null"){
						$isi=",".$is;				
						if($nullf[$i]=='NO') $isi=",''";	
					}else{
						$isi=",'".$is."'";
					}
					$txtqry2=$txtqry2.$isi;
				}elseif(substr($tipef[$i],0,3)=='bit'){
					if (trim($row[$namaf[$i]])==""){$is="null"; }else{ $is="null"; }
					$is=str_replace("'","",$is);
					if($is=="null"){
						$isi=",".$is;				
						if($nullf[$i]=='NO') $isi=",''";	
					}else{
						$isi=",'".$is."'";
					}
					$txtqry2=$txtqry2.$isi;
				}else{
					if (trim($row[$namaf[$i]])==""){$is="null"; }else{ $is=trim($row[$namaf[$i]]); }
					$is=str_replace("'","",$is);
					$isi=",".$is;
					$txtqry2=$txtqry2.$isi;		
				}
			}

	
			$txtqry2=$txtqry2.")";
			if($jum_rows!=$x){
				$txtqry2=$txtqry2.', (';
			}

			$txt=$txtqry1.$txtqry2;
		}


		if($x==0){
			return "";					
		}else{
			return $txt.";".chr(13);					
		}

	}
	

	function backup2(){

		$isi=$this->genSql('tr_panjar');
		echo $isi;
	}


	function backup(){


		$nmfile=FCPATH.'/backup/backup_'.date('Ymd').'.txt';
		$fl=fopen($nmfile,'w+');
		//$isi=$this->genSql('d_hukum_ubah');


        $query = $this->db->query(" SHOW TABLES ");  
		foreach($query->result_array() as $row){
			$nm=$row['Tables_in_simakda_siadinda'];
			
			if(($nm!='user') and ($nm!='dyn_menu') and ($nm!='otori') and ($nm!='map_lpe')
				and ($nm!='sclient') ){
			$isi=$this->genSql($nm);
			fwrite($fl,$isi);		
			}
		}
		fclose($fl);

		
		$hasil=base_url().'backup/backup_'.date('Ymd').'.txt';
		$coba=array('hasil'=>$hasil);
        echo json_encode($coba);
    }
    
	function do_upload(){
		//DATANYA DI UPLOAD DULU
		$fl= $this->input->post('datasql');
		$upload_path_url = base_url().'upload/';
        $config['upload_path'] = './upload/';
        $config['allowed_types'] = 'jpg|jpeg|txt|dat|sql|txt|abc';
        $config['max_size'] = '100000';
        $config['overwrite'] = TRUE; //overwrite user avatar

        $this->load->library('upload', $config);
	    $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('datasql')) {
			$data['status_upload']= $this->upload->display_errors();
        } else {
	
			
			//PROSES RESTORE DATA
			$dat =  $this->upload->data();
			$lfile=$dat['full_path'];


			$fl=fopen($lfile,'r');
			$jum=0;
			while (!feof($fl)) {
				$contents = fread($fl, filesize($lfile));

				//$this->_randomsleep();
			    //$query = $this->db->query($contents);  
				
				$baris=explode(";".chr(13),$contents);
				for($i=0;$i<=count($baris)-1;$i++){
					$q=$baris[$i];			        
					$jum++;	
					//$this->_randomsleep();
					//$query = $this->db->query($q);  

					$nm=FCPATH.'/restore/temp'.($i).'.txt';
					$fltemp=fopen($nm,'w+');
					fwrite($fltemp,$q);		
					fclose($fltemp);


				}			
			}

			$nm=FCPATH.'/restore/jumquery.txt';
			$fltemp=fopen($nm,'w+');
			fwrite($fltemp,$jum);		
			fclose($fltemp);


			fclose($fl);

			$data['page_title']= 'Backup';
			$data['status_upload']= 'Upload Berhasil'; //'Restore Berhasil';
			$this->template->set('title', 'Backup');   
			$this->template->load('template','backup/backup',$data) ;
		}

	}
      
	function _randomsleep(){
	 $sleep = 1;
	 sleep($sleep);
	 $this->db->reconnect();
	}

	function get_jumquery(){
		$data=array();
		$hasil='';
		$lfile=FCPATH.'/restore/jumquery.txt';			
		$fl=fopen($lfile,'r');
		$hasil = trim(fread($fl, filesize($lfile)));
		fclose($fl);
		$data=array('hasil'=>$hasil);
		echo json_encode($data);
	}

	function proses_query(){	
		$no = $this->input->post('nomor');

		$lfile=FCPATH.'/restore/temp'.$no.'.txt';
		$fl=fopen($lfile,'r');
		$hasil = trim(fread($fl, filesize($lfile)));
		fclose($fl);
        
		$query = $this->db->query($hasil);  
		
		$data=array('hasil'=>($no+1));
		echo json_encode($data);

	}

	function ahirtahun(){
		$data['page_title']= 'Proses Ahir Tahun';
		$data['status_upload']= 'PROSES AHIR TAHUN'; //'Restore Berhasil';
		$this->template->set('title', 'Proses Ahir Tahun');   
		$this->template->load('template','backup/ahirtahun',$data) ;	
	}



	function proses_ahirtahun(){	
		$nama = $this->input->post('nama');
        
		$query = $this->db->query(" CREATE DATABASE /*!32312 IF NOT EXISTS*/`$nama` /*!40100 DEFAULT CHARACTER SET latin1 */; ");  

		

        $query = $this->db->query(" SHOW TABLES ");  
		foreach($query->result_array() as $row){
			$nm=$row['Tables_in_simakda_siadinda'];
			$query = $this->db->query(" CREATE TABLE $nama.$nm LIKE simakda_siadinda.$nm ");  			

			$copytabel=array('d_hukum','d_hukum_ubah','otori','rek_lo','rg_lak','rg_neraca','sclient','sumber_dana','tapd','user');
			if((strtoupper(substr($nm,0,1))=='M') or (in_array($nm,$copytabel) )){
				$query = $this->db->query(" insert into $nama.$nm select * from simakda_siadinda.$nm ");  					
			}
		}



		$data=array('hasil'=>'Berhasil');
		echo json_encode($data);

	}


}
