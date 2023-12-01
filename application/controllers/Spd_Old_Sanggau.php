<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


    class Spd extends CI_Controller {

    	function __contruct(){	
    		parent::__construct();
      
    	}

        public $keu1 = "5.02.0.00.0.00.02.0000";

        function index($offset=0,$lctabel,$field,$field1,$judul,$list,$lccari){
        $data['page_title'] = "CETAK $judul";
        
        //$total_rows = $this->master_model->get_count($lctabel);
        if(empty($lccari)){
            $total_rows = $this->master_model->get_count($lctabel);
            $lc = "/.$lccari";
        }else{
            $total_rows = $this->master_model->get_count_teang($lctabel,$field,$field1,$lccari);
            $lc = "";
        }
        // pagination        
        $config['base_url']     = site_url("rka/".$list);
        $config['total_rows']   = $total_rows;
        $config['per_page']     = '10';
        $config['uri_segment']  = 3;
        $config['num_links']    = 5;
        $config['full_tag_open'] = '<ul class="page-navi">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="current">';
        $config['cur_tag_close'] = '</li>';
        $config['prev_link'] = '&lt;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '&gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $limit                  = $config['per_page'];  
        $offset                 = $this->uri->segment(3);  
        $offset                 = ( ! is_numeric($offset) || $offset < 1) ? 0 : $offset;  
          
        if(empty($offset))  
        {  
            $offset=0;  
        }
               
        //$data['list']         = $this->master_model->getAll($lctabel,$field,$limit, $offset);
         if(empty($lccari)){     
        $data['list']       = $this->master_model->getAll($lctabel,$field,$limit, $offset);
        }else {
            $data['list']       = $this->master_model->getCari($lctabel,$field,$field1,$limit, $offset,$lccari);
        }
        $data['num']        = $offset;
        $data['total_rows'] = $total_rows;
        
                $this->pagination->initialize($config);
        $a=$judul;
        $data['sikap'] = 'list';
        $this->template->set('title', 'CETAK '.$judul);
        $this->template->load('template', "anggaran/spd/".$list, $data);
    }

    function _mpdf($judul='',$isi='',$lMargin='',$rMargin='',$font=10,$orientasi='',$hal='',$tab='',$jdlsave='',$tMargin='') {
                

        ini_set("memory_limit","-1");
        $this->load->library('mpdf');
        //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');
        

        $this->mpdf->defaultheaderfontsize = 6; /* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;   /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 1; /* in pts */
        $this->mpdf->defaultfooterfontstyle = blank;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 0; 
        $sa=1;
        $tes=0;
        if ($hal==''){
        $hal1=1;
        } 
        if($hal!==''){
        $hal1=$hal;
        }
        
        if ($tMargin=='' ){
            $tMargin=16;
        }
        
        if($lMargin==''){
            $lMargin=15;
        }

        if($rMargin==''){
            $rMargin=15;
        }
        
        
        $this->mpdf = new mPDF('utf-8', array(215,330),$size,'',$lMargin,$rMargin,$tMargin); //folio
        
        $mpdf->cacheTables = true;
        $mpdf->packTableData=true;
        $mpdf->simpleTables=true;
        $this->mpdf->AddPage($orientasi,'',$hal1,'1','off');
        if (!empty($tab)) $this->mpdf->SetTitle($tab); 
        if ($hal != 'no'){
            $this->mpdf->SetFooter("Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        //$this->mpdf->simpleTables= true;     
        $this->mpdf->writeHTML(utf8_encode($isi));         
        //$this->mpdf->Output('');
        $this->mpdf->Output($judul,'I');
    }

    function q_mpdf($judul='',$isi='',$lMargin='',$rMargin='',$font=10,$orientasi='',$hal='',$tab='',$jdlsave='',$tMargin='') {
                

        ini_set("memory_limit","-1");
        $this->load->library('mpdf');
        //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');
        

        $this->mpdf->defaultheaderfontsize = 6; /* in pts */
        $this->mpdf->defaultheaderfontstyle = BI;   /* blank, B, I, or BI */
        $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */

        $this->mpdf->defaultfooterfontsize = 1; /* in pts */
        $this->mpdf->defaultfooterfontstyle = blank;    /* blank, B, I, or BI */
        $this->mpdf->defaultfooterline = 0; 
        $sa=1;
        $tes=0;
        if ($hal==''){
        $hal1=1;
        } 
        if($hal!==''){
        $hal1=$hal;
        }
        
        if ($tMargin=='' ){
            $tMargin=15;
        }
        
        if($lMargin==''){
            $lMargin=15;
        }

        if($rMargin==''){
            $rMargin=15;
        }
        
        
        $this->mpdf = new mPDF('utf-8', array(215,330),$size,'',$lMargin,$rMargin,$tMargin); //folio  
        $mpdf->cacheTables = true;
        $mpdf->packTableData = true;
        $mpdf->simpleTables = true;
        $this->mpdf->AddPage($orientasi,'',$hal1,'1','off');
        if (!empty($tab)) $this->mpdf->SetTitle($tab); 
        if ($hal != 'no'){
            $this->mpdf->SetFooter("Halaman {PAGENO}  ");
        }
        if (!empty($judul)) $this->mpdf->writeHTML($judul);
        //$this->mpdf->simpleTables= true;     
        $this->mpdf->writeHTML($isi);         
        //$this->mpdf->Output('');
        $this->mpdf->Output($judul,'I');
    }
    

    	function config_tahun() {
            $result = array();
             $tahun  = $this->session->userdata('pcThang');
    		 $result = $tahun;
             echo json_encode($result);
        	   
    	}

    	function spd_belanja(){
            $data['page_title']= 'INPUT SPD BELANJA';
            $this->template->set('title', 'INPUT SPD BELANJA');   
            $this->template->load('template','anggaran/spd/spd_belanja',$data) ; 
        }

         function jumlah_detail_spd(){
            
            $no_spd = $this->input->post('cno_spd');
            $sql    = "SELECT SUM(nilai) as nilai FROM trdspd WHERE no_spd = '$no_spd' ";
                    
            $query1 = $this->db->query($sql);  
            $test   = $query1->num_rows();
            $ii     = 0;
            
            foreach($query1->result_array() as $resulte)
            { 
                $result = array(
                            'id' => $ii,        
                            'total' => $resulte['nilai']
                            );
                            $ii++;
            }
            
            if ($test===0){
                $result = array(
                            'total' => ''
                            );
                            $ii++;
            }       
            echo json_encode($result);
            $query1->free_result();   
        }

        function bln_spdakhir($cskpd=''){
            $kdskpd = $this->input->post('skpd');
            $jns = $this->input->post('jenis');
            $query1 = $this->db->query("select top 1 bulan_akhir from trhspd where kd_skpd='$kdskpd' and jns_beban='$jns' order by tgl_spd desc ");  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $result[] = array(
                    'id' => $ii,        
                    'cbulan_akhir' => $resulte['bulan_akhir']                                              
                );
                $ii++;
            }
           
            echo json_encode($result);
            $query1->free_result(); 
        }

        function cek_simpan(){
            $nomor    = $this->input->post('no');
            $tabel   = $this->input->post('tabel');
            $field    = $this->input->post('field');

            $hasil=$this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor'");
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


        function load_bendahara_p(){

            $kdskpd = $this->input->post('kode');
        
            $query1 = $this->db->query(" select nip,nama from ms_ttd where kd_skpd='$kdskpd' AND kode='BK' ");  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'nip' => $resulte['nip'],  
                            'nama' => $resulte['nama']
                            );
                            $ii++;
            }
               
               //return $result;
               echo json_encode($result);   
        }


        function load_kadis(){

            $kdskpd = $this->input->post('kode');
        
            $query1 = $this->db->query(" select nip,nama from ms_ttd where kd_skpd='$kdskpd' AND kode in ('PA','KPA') ");  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'nip' => $resulte['nip'],  
                            'nama' => $resulte['nama']
                            );
                            $ii++;
            }
               
               //return $result;
               echo json_encode($result);   
        }

    function load_spd_bl() {
            $kd_skpd = $this->session->userdata('kdskpd'); 
            $result = array();
            $row = array();
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
            $offset = ($page-1)*$rows;        
            $kriteria = $this->input->post('cari');
            $id  = $this->session->userdata('pcUser');  
            $where ="WHERE jns_beban='5' and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ";
            if ($kriteria <> ''){                               
                $where="where ((upper(a.no_spd) like upper('%$kriteria%') or a.tgl_Spd like '%$kriteria%' or upper(a.nm_skpd) like 
                        upper('%$kriteria%') or upper(a.kd_skpd) like upper('%$kriteria%')) and upper(a.jns_beban)='5' 
                        and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ) ";            
            }
            
            $sql = "SELECT count(*) as total from trhspd a $where " ;
            $query1 = $this->db->query($sql);
            $total = $query1->row();
            $result["total"] = $total->total; 
            $query1->free_result();
            
            $sql = "SELECT TOP $rows  a.*,nama,case when jns_beban='5' then 'BELANJA' else 'PEMBIAYAAN' end AS nm_beban from trhspd a left join ms_ttd b 
            on a.kd_bkeluar=b.nip  $where  AND no_spd not in (SELECT TOP $offset  no_spd from trhspd a left join ms_ttd b 
            on a.kd_bkeluar=b.nip where a.jns_beban='5' and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') order by a.no_spd,a.tgl_Spd,a.kd_skpd) 
            group by a.no_spd, a.tgl_spd,a.kd_skpd, a.nm_skpd, a.jns_beban, a.no_dpa, a.bulan_awal, a.bulan_akhir, a.kd_bkeluar, a.triwulan,a.klain, a.username,a.tglupdate, a.st, a.[status], a.total, nama
            order by no_spd,tgl_Spd,kd_skpd ";

            // print_r($sql);die();
            $query1 = $this->db->query($sql);       
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               $bulan = $this->tgl_indo($resulte['tgl_spd']);
                $row[] = array(
                            'id' => $ii,        
                            'no_spd' => $resulte['no_spd'],
                            'tgl_spd' => $resulte['tgl_spd'],
                            'kd_skpd' => $resulte['kd_skpd'],
                            'nm_skpd' => $resulte['nm_skpd'],
                            'ketentuan' => $resulte['klain'],
                            'nama_bend' => $resulte['nama'],
                            'nip' => $resulte['kd_bkeluar'],                        
                            'jns_beban' => $resulte['jns_beban'],
                            'nm_beban' => $resulte['nm_beban'],
                            'bulan_awal' => $resulte['bulan_awal'],
                            'bulan_akhir' => $resulte['bulan_akhir'],
                            'total' => $resulte['total'],                                                                      
                            'status' => $resulte['status'],
                            'bln_spd' => $bulan                                                                      
                            );
                            $ii++;
            }
            $result["rows"] = $row;           
            echo json_encode($result);
            $query1->free_result();        
        }

        function tgl_indo($tanggal){
            $bulan = array (
                1 =>   'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            );
            $pecahkan = explode('-', $tanggal);

    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

            return $bulan[ (int)$pecahkan[1] ];
        }


        function load_spd_pembiayaan() {
            $kd_skpd = $this->session->userdata('kdskpd'); 
            //$kd_skpd = '1.20.08.10'; 
            $result = array();
            $row = array();
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
            $offset = ($page-1)*$rows;        
            $kriteria = $this->input->post('cari');
            $id  = $this->session->userdata('pcUser');  
            $where ="WHERE jns_beban='62' and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ";
            if ($kriteria <> ''){                               
                $where="where ((upper(a.no_spd) like upper('%$kriteria%') or a.tgl_Spd like '%$kriteria%' or upper(a.nm_skpd) like 
                        upper('%$kriteria%') or upper(a.kd_skpd) like upper('%$kriteria%')) and upper(a.jns_beban)='62' 
                        and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ) ";            
            }
            
            $sql = "SELECT count(*) as total from trhspd a $where " ;
            $query1 = $this->db->query($sql);
            $total = $query1->row();
            $result["total"] = $total->total; 
            $query1->free_result();
            
            //$sql = "SELECT *,IF(jns_beban='52','Belanja Langsung','Belanja Tidak Langsung') AS nm_beban from trhspd $where order by tgl_Spd,kd_skpd limit $offset,$rows";
            
            $sql = "SELECT TOP $rows  a.*,nama,case when jns_beban='5' then 'BELANJA' else 'PEMBIAYAAN' end AS nm_beban from trhspd a left join ms_ttd b 
            on a.kd_bkeluar=b.nip  $where  AND no_spd not in (SELECT TOP $offset  no_spd from trhspd a left join ms_ttd b 
            on a.kd_bkeluar=b.nip where a.jns_beban='62' and a.kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') order by a.no_spd,a.tgl_Spd,a.kd_skpd) 
            group by a.no_spd, a.tgl_spd,a.kd_skpd, a.nm_skpd, a.jns_beban, a.no_dpa, a.bulan_awal, a.bulan_akhir, a.kd_bkeluar, a.triwulan,a.klain, a.username,a.tglupdate, a.st, a.[status], a.total, nama
            order by no_spd,tgl_Spd,kd_skpd ";
            $query1 = $this->db->query($sql);       
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $row[] = array(
                            'id' => $ii,        
                            'no_spd' => $resulte['no_spd'],
                            'tgl_spd' => $resulte['tgl_spd'],
                            'kd_skpd' => $resulte['kd_skpd'],
                            'nm_skpd' => $resulte['nm_skpd'],
                            'ketentuan' => $resulte['klain'],
                            'nama_bend' => $resulte['nama'],
                            'nip' => $resulte['kd_bkeluar'],                        
                            'jns_beban' => $resulte['jns_beban'],
                            'nm_beban' => $resulte['nm_beban'],
                            'bulan_awal' => $resulte['bulan_awal'],
                            'bulan_akhir' => $resulte['bulan_akhir'],
                            'total' => $resulte['total'],                                                                      
                            'status' => $resulte['status']                                                                      
                            );
                            $ii++;
            }
            $result["rows"] = $row;           
            echo json_encode($result);
            $query1->free_result();        
        }


        function skpduser() {
            $lccr = $this->input->post('q');
            $id  = $this->session->userdata('pcUser');
            // $sql = "SELECT kd_skpd,nm_skpd,jns FROM ms_skpd where (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) and 
            //         kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') and right(kd_skpd,4)='0000' order by kd_skpd ";

            $sql = "SELECT kd_skpd,nm_skpd,jns FROM ms_skpd where (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) and 
                    kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') order by kd_skpd ";

            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_skpd' => $resulte['kd_skpd'],  
                            'nm_skpd' => $resulte['nm_skpd'],
                            'jns' => $resulte['jns']
                            );
                            $ii++;
            }
               
            echo json_encode($result);
                $query1->free_result();
        }

        function load_ttd_unit($skpd='') {
        $kd_skpd = $skpd; 
        $kd_skpd2= $this->left($kd_skpd,17);
        $lccr='';        
        $lccr = $this->input->post('q');        
        $sql = "SELECTS * FROM ms_ttd WHERE left(kd_skpd,17)= '$kd_skpd2' AND kode in ('PA','KPA')  AND (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%'))";   

         
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;        
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama']      
                        );
                        $ii++;
        }           
           
        echo json_encode($result);
        $query1->free_result();        
    }


    function load_ttd_kba() {
        $lccr='';        
        $lccr = $this->input->post('q');        
        $sql = "SELECT * FROM ms_ttd WHERE (UPPER(kode) LIKE UPPER('%$lccr%') OR UPPER(nama) LIKE UPPER('%$lccr%')) and kode='BUD'";   

        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;        
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama']      
                        );
                        $ii++;
        }           
           
        echo json_encode($result);
        $query1->free_result();
           
    }


        function skpduser_pembiayaan() {
            $lccr = $this->input->post('q');
            $id  = $this->session->userdata('pcUser');
            $sql = "SELECT kd_skpd,nm_skpd,jns FROM ms_skpd where (upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%')) and 
                    kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id' and kd_skpd='5.02.0.00.0.00.02.0000') order by kd_skpd ";
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_skpd' => $resulte['kd_skpd'],  
                            'nm_skpd' => $resulte['nm_skpd'],
                            'jns' => $resulte['jns']
                            );
                            $ii++;
            }
               
            echo json_encode($result);
                $query1->free_result();
        }


        function load_bendahara_ppkd(){
        
            $query1 = $this->db->query("select nip,nama,jabatan,pangkat from ms_ttd where kode='PPKD' and bidang='1'");  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'nip_ppkd' => $resulte['nip'],  
                            'nama_ppkd' => $resulte['nama'],                        
                            'jabatan_ppkd' => $resulte['jabatan'],
                            'pangkat_ppkd' => $resulte['pangkat']
                            );
                            $ii++;
            }
               
               //return $result;
               echo json_encode($result);   
        }
    function update_sts_spd()
        {
        $no_spd      = $this->input->post('no');
        $ckd_skpd     = $this->input->post('kd_skpd');
        $csts      = $this->input->post('status_spd');
        $usernm      = $this->session->userdata('pcNama');
        $last_update =  date('d-m-y H:i:s');
                        
           $sql = "update trhspd set status='$csts' where no_spd='$no_spd' and kd_skpd='$ckd_skpd' ";
           $asg = $this->db->query($sql);
            if ($asg > 0){      
                echo $csts;
            } else {
                echo '5';
            }
        }

        function cek_simpan_spd(){
            $nomor    = $this->input->post('no');
            $tabel   = $this->input->post('tabel');
            $field    = $this->input->post('field');

            $hasil=$this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor' and (sp2d_batal is null or sp2d_batal<>1)");
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


        function load_trskpd() {
            //$cskpd=$this->uri->segment(3);
            $cskpd = $this->input->post('kode');
            $jenis = $this->input->post('jenis');//'52';
            $giat = $this->input->post('giat');
            $no = $this->input->post('no');                        
            if ($jenis !=''){
                $jns_beban = " and b.jns_sub_kegiatan='$jenis' ";
            } else {
                $jns_beban = '';
            }
            if ($giat !=''){                                    
                $where = " and a.kd_sub_kegiatan not in ($giat) ";
            } else {
                $where = '';
            }
            if ($no!=''){
                $spdlalu = ",(SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_sub_kegiatan=a.kd_sub_kegiatan  AND m.no_spd <> '$no') AS lalu";
            } else {
                $spdlalu = ',0 as lalu';            
            }
            $lccr='';        
            $lccr = $this->input->post('q');        
            $sql = "SELECTS a.kd_sub_kegiatan,b.nm_sub_kegiatan,a.kd_program,(select nm_program from m_prog where kd_program=a.kd_program) as nm_program,a.total $spdlalu FROM trskpd a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
                    WHERE LEFT(a.kd_sub_kegiatan,4)= LEFT('$cskpd',4) $jns_beban $where AND (UPPER(a.kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_sub_kegiatan) LIKE UPPER('%$lccr%'))";                                              
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;        
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],  
                            'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                            // 'kd_kegiatan' => $resulte['kd_kegiatan'],  
                            // 'nm_kegiatan' => $resulte['nm_kegiatan'],
                            'kd_program' => $resulte['kd_program'],  
                            'nm_program' => $resulte['nm_program'],
                            'lalu'       => $resulte['lalu'],
                            'total'       => $resulte['total']        
                            );
                            $ii++;
            }           
               
            echo json_encode($result);
            $query1->free_result();
               
        }


        function load_dspd() {            
            $no = $this->input->post('no');
            $sql = "SELECT a.*,(SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_sub_kegiatan=a.kd_sub_kegiatan  AND m.no_spd <> '$no') AS lalu,
                    (SELECT SUM(total) FROM trskpd WHERE kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_skpd=b.kd_skpd ) AS anggaran from trdspd a inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' order by b.no_spd,a.kd_sub_kegiatan,a.kd_rek6";
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'no_spd' => $resulte['no_spd'],
                            'kd_kegiatan' => $resulte['kd_kegiatan'],
                            'nm_kegiatan' => $resulte['nm_kegiatan'],
                            'kd_rekening' => $resulte['kd_rek6'],
                            'nm_rekening' => $resulte['nm_rek6'],
                            'kd_program' => $resulte['kd_program'],
                            'nm_program' => $resulte['nm_program'],
                            'nilai' => $resulte['nilai'],
                            'lalu' => $resulte['lalu'],
                            'anggaran' => $resulte['anggaran']                                        
                            );
                            $ii++;
            }
               
            echo json_encode($result);
            $query1->free_result();        
        }

        function load_dspd_kosong() {            
            $no = $this->input->post('no');
            $sql = "SELECT a.*,(SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_sub_kegiatan=a.kd_sub_kegiatan  AND m.no_spd <> '$no') AS lalu,
                    (SELECT SUM(total) FROM trskpd WHERE kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_skpd=b.kd_skpd ) AS anggaran from trdspd a inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' order by b.no_spd,a.kd_sub_kegiatan,a.kd_rek6";
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'no_spd' => $resulte['no_spd'],
                            'kd_kegiatan' => $resulte['kd_kegiatan'],
                            'nm_kegiatan' => $resulte['nm_kegiatan'],
                            'kd_rekening' => $resulte['kd_rek6'],
                            'nm_rekening' => $resulte['nm_rek6'],
                            'kd_program' => $resulte['kd_program'],
                            'nm_program' => $resulte['nm_program'],
                            'nilai' => $resulte['nilai'],
                            'lalu' => $resulte['lalu'],
                            'anggaran' => $resulte['anggaran']                                        
                            );
                            $ii++;
            }
               
            echo json_encode($result);
            $query1->free_result();        
        }

        function get_status($tgl,$skpd){
            $n_status = '';
            $tanggal = $tgl;
            $sql = "SELECT case when '$tanggal'>=tgl_dpa_ubah then 'nilai_ubah' 
                        when '$tanggal'>=tgl_dpa_sempurna then 'nilai_sempurna' 
                        when '$tanggal'<=tgl_dpa 
                        then 'nilai' else 'nilai' end as anggaran from trhrka where kd_skpd ='$skpd'  ";
            
            $q_trhrka = $this->db->query($sql);
            $num_rows = $q_trhrka->num_rows();
            
            foreach ($q_trhrka->result() as $r_trhrka){
                 $n_status = $r_trhrka->anggaran;                   
            }    
            return $n_status;                         
        }

         function get_status2($skpd){
            $n_status = '';
            
            $sql = "SELECT CASE WHEN status = '1' AND status_sempurna = '1' AND status_ubah = '1' THEN 'nilai_ubah' WHEN status = '1' AND status_sempurna = '1' AND status_ubah = '0' THEN 'nilai_sempurna' 
                    WHEN status = '1' AND status_sempurna = '0' AND status_ubah = '0' THEN 'nilai' ELSE 'nilai' END AS anggaran FROM trhrka WHERE kd_skpd = '$skpd'";
            
            $q_trhrka = $this->db->query($sql);
            $num_rows = $q_trhrka->num_rows();
            
            foreach ($q_trhrka->result() as $r_trhrka){
                 $n_status = $r_trhrka->anggaran;                   
            }    
            return $n_status;                         
        }


        function cek_anggaran_kas_spd(){
            
            $skpd   = $this->input->post('skpd');
            $angg   = $this->get_status2($skpd);
            $result = array();
            $query1 = $this->rka_model->qcek_anggaran_kas($skpd,$angg);                  
            $result = $query1->num_rows();
            echo json_encode($result);
        }



        function load_tot_dspd_bl($jenis='',$skpd='',$awal='',$ahir='',$nospd='',$tgl1=''){
            $n_status = $this->get_status($tgl1,$skpd);     
            $field= 'b.'.$n_status;
            $n_trdskpd = 'trdskpd';
            $spd = str_replace('123456789','/',$nospd);
            $sql = "SELECT SUM(nilai) as nilai FROM
                    (SELECT a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, '' as kd_rek5 , '' as nm_rek5, 
                     kd_skpd
                    FROM trskpd a inner join ms_sub_kegiatan x ON a.kd_sub_kegiatan=x.kd_sub_kegiatan WHERE a.kd_skpd='$skpd' and x.jns_sub_kegiatan ='5') a
                    LEFT JOIN
                    (SELECT kd_sub_kegiatan, kd_skpd, SUM($n_status) as nilai FROM $n_trdskpd b WHERE b.bulan>='$awal' AND b.bulan<='$ahir'
                    AND kd_skpd='$skpd'
                    GROUP BY kd_sub_kegiatan, kd_skpd)b
                    ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd";
            $query1 = $this->db->query($sql);  
            $ii     = 0;

             foreach($query1->result_array() as $resulte)
            { 
                $result = array(
                            'id' => $ii,        
                            'nilai' => $resulte['nilai']
                            );
                            $ii++;
            }
            
                    
            echo json_encode($result);
            $query1->free_result();   
        }

        function hapus_spd(){
            $nomor = $this->input->post('no');
            $msg = array();         
            $sql = "delete from trdspd where no_spd='$nomor'";
            $asg = $this->db->query($sql);
            if ($asg){
                $sql = "delete from trhspd where no_spd='$nomor'";
                $asg = $this->db->query($sql);
                if (!($asg)){
                   $msg = array('pesan'=>'0');
                   echo json_encode($msg);
                   exit();
                } 
            } else {
                $msg = array('pesan'=>'0');
                echo json_encode($msg);
                exit();
            }
              $msg = array('pesan'=>'1');
              echo json_encode($msg);              
        }

        function load_tot_dspd_pembiayaan($jenis='',$skpd='',$awal='',$ahir='',$nospd='',$tgl1=''){
            $n_status = $this->get_status($tgl1,$skpd);     
            $field= 'b.'.$n_status;
            $n_trdskpd = 'trdskpd';
            $spd = str_replace('123456789','/',$nospd);
            $sql = "SELECT SUM(nilai) as nilai FROM
                    (SELECT a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, '' as kd_rek6 , '' as nm_rek6, 
                     kd_skpd
                    FROM trskpd a inner join ms_sub_kegiatan x ON a.kd_sub_kegiatan=x.kd_sub_kegiatan WHERE a.kd_skpd='$skpd' and x.jns_sub_kegiatan ='62') a
                    LEFT JOIN
                    (SELECT kd_sub_kegiatan, kd_skpd, SUM($n_status) as nilai FROM $n_trdskpd b WHERE b.bulan>='$awal' AND b.bulan<='$ahir'
                    AND kd_skpd='$skpd'
                    GROUP BY kd_sub_kegiatan, kd_skpd)b
                    ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd";
            $query1 = $this->db->query($sql);  
            $ii     = 0;

             foreach($query1->result_array() as $resulte)
            { 
                $result = array(
                            'id' => $ii,        
                            'nilai' => $resulte['nilai']
                            );
                            $ii++;
            }
            
                    
            echo json_encode($result);
            $query1->free_result();   
        }


        function load_dspd_bl($jenis='',$skpd='',$awal='',$ahir='',$nospd='',$cbln1=''){
            $skpd1=$skpd;
            $tgl = $this->input->post('tgl');
            $cbln1 = $this->input->post('cbln1');
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
            $offset = ($page-1)*$rows;
            $kriteria = '';
            $kriteria = $this->input->post('cari');
            $where ='';
            if ($kriteria <> ''){                               
                $where="AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                        upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
            }

            $sql = "SELECT count(*) as tot FROM trskpd a inner join ms_sub_kegiatan b on a.kd_sub_kegiatan=b.kd_sub_kegiatan WHERE left(a.kd_skpd,22)='$skpd1' and b.kd_sub_kegiatan NOT LIKE '%0.00%'";
            $query1 = $this->db->query($sql);
            $total = $query1->row();
         
            $n_status = $this->get_status2($skpd);

            // echo 'status'.$n_status;
            
            $spd = str_replace('123456789','/',$nospd);
            $field= 'b.'.$n_status;
            //$field='b.nilai_sempurna';
            $n_trdskpd = 'trdskpd_ro';
            $spd = str_replace('123456789','/',$nospd);
            // $sql = "SELECT  a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, a.kd_rek6 , a.nm_rek6,  
            //         a.total_ubah as anggaran, nilai,lalu FROM(
            //         SELECT b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, b.kd_rek6, b.nm_rek6, 
            //         sum($field) as total_ubah, left(b.kd_skpd,22) kd_skpd
            //         FROM trskpd a join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd
            //         WHERE left(b.kd_skpd,22)='$skpd1' and a.kd_sub_kegiatan NOT like '%0.00%' group by b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,left(b.kd_skpd,22),b.kd_rek6, b.nm_rek6
            //         ) a LEFT JOIN (
            //         SELECT kd_sub_kegiatan, left(kd_skpd,22) kd_skpd, kd_rek6, SUM($n_status) as nilai FROM $n_trdskpd b WHERE b.bulan>='$awal' AND b.bulan<='$ahir'
            //         AND left(kd_skpd,22)='$skpd1' GROUP BY kd_sub_kegiatan, left(kd_skpd,22), kd_rek6
            //         )b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND left(a.kd_skpd,22)=left(b.kd_skpd,22) and a.kd_rek6=b.kd_rek6 LEFT JOIN (
            //         SELECT kd_sub_kegiatan,kd_rek6,SUM(a.nilai) as lalu FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
            //         WHERE left(b.kd_skpd,22)='$skpd1' and a.no_spd != '$spd' and b.tgl_spd<'$tgl'
            //         GROUP BY kd_sub_kegiatan,kd_rek6
            //         ) c ON a.kd_sub_kegiatan=c.kd_sub_kegiatan and a.kd_rek6=c.kd_rek6
            //         ORDER BY a.kd_sub_kegiatan,a.kd_rek6
            //         ";


            
            $sql = "SELECT a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program,a.nm_program,a.kd_rek6 ,a.nm_rek6,a.murni,a.geser,( a.murni - a.geser ) AS selisih, nilai, lalu 
                    FROM (
                        SELECT b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, b.kd_rek6, b.nm_rek6, SUM ( b.nilai ) AS murni, SUM ( b.nilai_sempurna ) AS geser, b.kd_skpd 
                        FROM trskpd a JOIN trdrka b ON a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_skpd= b.kd_skpd WHERE b.kd_skpd= '$skpd' AND b.nilai_sempurna != 0 AND LEFT(b.kd_rek6,1) = '5'
                        GROUP BY b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, b.kd_skpd, b.kd_rek6, b.nm_rek6 ) a
                    LEFT JOIN (SELECT kd_sub_kegiatan, kd_skpd, kd_rek6, SUM ( nilai ) AS nilai FROM trdskpd_ro b WHERE b.bulan>= '$awal' AND b.bulan <= '$ahir' AND kd_skpd = '$skpd'
                    GROUP BY kd_sub_kegiatan, kd_skpd, kd_rek6) b ON a.kd_sub_kegiatan= b.kd_sub_kegiatan AND a.kd_skpd = b.kd_skpd AND a.kd_rek6= b.kd_rek6 
                    LEFT JOIN ( SELECT kd_sub_kegiatan, kd_rek6, SUM ( a.nilai ) AS lalu FROM trdspd a LEFT JOIN trhspd b ON a.no_spd= b.no_spd WHERE b.kd_skpd = '$skpd' 
                    AND a.no_spd != '$spd' AND b.tgl_spd< '$tgl' GROUP BY kd_sub_kegiatan, kd_rek6 ) c ON a.kd_sub_kegiatan= c.kd_sub_kegiatan 
                    AND a.kd_rek6= c.kd_rek6 ORDER BY a.kd_sub_kegiatan, a.kd_rek6";

            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $row[] = array(
                            'id'          		=> $ii,        
                            'no_spd'      		=> '',
                            'kd_sub_kegiatan' 	=> $resulte['kd_sub_kegiatan'],
                            'nm_sub_kegiatan' 	=> $resulte['nm_sub_kegiatan'],
                            'kd_program'  		=> $resulte['kd_program'],
                            'nm_program'  		=> $resulte['nm_program'],
                            'kd_rekening' 		=> $resulte['kd_rek6'],
                            'nm_rekening' 		=> $resulte['nm_rek6'],
                            'nilai'       		=> number_format($resulte['nilai'],"2",".",","),
                            'lalu'        		=> number_format($resulte['lalu'],"2",".",","),
                            'anggaran'    		=> number_format($resulte['murni'],"2",".",","),
                            'ang_geser'          => number_format($resulte['geser'],"2",".",",")
                            );
                            $ii++;
            }
               
            $result["total"] = $total->tot;
            $result["rows"] = $row; 
            $query1->free_result();   
            echo json_encode($result);
        }



        function load_dspd_pembiayaan($jenis='',$skpd='',$awal='',$ahir='',$nospd='',$cbln1=''){

            $tgl = $this->input->post('tgl');
            $cbln1 = $this->input->post('cbln1');
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
            $offset = ($page-1)*$rows;
            $kriteria = '';
            $kriteria = $this->input->post('cari');
            $where ='';
            if ($kriteria <> ''){                               
                $where="AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                        upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
            }

            $sql = "SELECT count(*) as tot FROM trskpd a inner join ms_sub_kegiatan b on a.kd_sub_kegiatan=b.kd_sub_kegiatan WHERE a.kd_skpd='$skpd' and b.jns_sub_kegiatan ='62'";
            $query1 = $this->db->query($sql);
            $total = $query1->row();
         
            $n_status = $this->get_status($tgl,$skpd);
            
            $spd = str_replace('123456789','/',$nospd);
            $field= 'b.'.$n_status;
            //$field='b.nilai_sempurna';
            $n_trdskpd = 'trdskpd';
            $spd = str_replace('123456789','/',$nospd);
            $sql = "SELECT TOP $rows a.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, '' as kd_rek5 , '' as nm_rek5, 
                    a.total_ubah as anggaran, nilai,lalu FROM(
                        SELECT b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program, '' as kd_rek5 , '' as nm_rek5, 
                        sum($field) as total_ubah, b.kd_skpd
                        FROM trskpd a join trdrka b on a.kd_sub_kegiatan=b.kd_sub_kegiatan
                        WHERE b.kd_skpd='$skpd' and a.jns_kegiatan ='6' group by b.kd_sub_kegiatan, a.nm_sub_kegiatan, a.kd_program, a.nm_program,b.kd_skpd
                    ) a LEFT JOIN (
                        SELECT kd_sub_kegiatan, kd_skpd, SUM($n_status) as nilai FROM $n_trdskpd b WHERE b.bulan>='$awal' AND b.bulan<='$ahir'
                        AND kd_skpd='$skpd' GROUP BY kd_sub_kegiatan, kd_skpd
                    )b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan AND a.kd_skpd=b.kd_skpd LEFT JOIN (
                        SELECT kd_sub_kegiatan,SUM(a.nilai) as lalu FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                        WHERE b.kd_skpd='$skpd' and a.no_spd != '$spd' and b.tgl_spd<'$tgl'
                        GROUP BY kd_sub_kegiatan
                    ) c ON a.kd_sub_kegiatan=c.kd_sub_kegiatan
                    WHERE a.kd_sub_kegiatan NOT IN (SELECT TOP $offset kd_sub_kegiatan FROM  trskpd a WHERE a.kd_skpd='$skpd' and a.jns_kegiatan ='6' ORDER BY a.kd_sub_kegiatan)
                    ORDER BY a.kd_sub_kegiatan 
                    ";

            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $row[] = array(
                            'id'                => $ii,        
                            'no_spd'            => '',
                            'kd_sub_kegiatan'   => $resulte['kd_sub_kegiatan'],
                            'nm_sub_kegiatan'   => $resulte['nm_sub_kegiatan'],
                            'kd_program'        => $resulte['kd_program'],
                            'nm_program'        => $resulte['nm_program'],
                            'kd_rekening'       => $resulte['kd_rek5'],
                            'nm_rekening'       => $resulte['nm_rek5'],
                            'nilai'             => number_format($resulte['nilai'],"2",".",","),
                            'lalu'              => number_format($resulte['lalu'],"2",".",","),
                            'anggaran'          => number_format($resulte['anggaran'],"2",".",",")
                            );
                            $ii++;
            }
               
            $result["total"] = $total->tot;
            $result["rows"] = $row; 
            $query1->free_result();   
            echo json_encode($result);
        }



        function load_dspd_ag_bl() {            
            $no = $this->input->post('no');
            $jenis = $this->input->post('jenis');
            $skpd = $this->input->post('skpd');
            $skpd1 = substr($skpd, 0,17);
            $tgl = $this->input->post('tgl');
            $cbln1 = $this->input->post('cbln1');
            //$stsubah=$this->rka_model->get_nama($skpd,'status_ubah','trhrka','kd_skpd');
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
            $offset = ($page-1)*$rows;
            $kriteria = '';
            $kriteria = $this->input->post('cari');
            $where ='';
            if ($kriteria <> ''){                               
                $where="AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                        upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
            }

            $sql = "SELECT count(*) as tot from trdspd WHERE no_spd='$no'";
            $query1 = $this->db->query($sql);
            $total = $query1->row();
            
            $n_status = $this->get_status2($skpd);
        
            $field='$n_status'; 
            
            if ($jenis=='62'){
                  echo $jenis;
            //$field='nilai_sempurna';
            $sql = "SELECT a.*,kd_rek5,nm_rek5 ,isnull((SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd 
                    WHERE n.kd_kegiatan=a.kd_kegiatan  and kd_rek5=a.kd_Rek5 and m.no_spd <> '$no' and m.tgl_spd<'$tgl'),0) AS lalu,
                    (SELECT SUM($field) FROM trdrka WHERE kd_rek6 = a.kd_rek6 AND kd_skpd=b.kd_skpd and kd_rek5=a.kd_Rek5 ) AS anggaran from trdspd a
                    inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' order by b.no_spd,a.kd_kegiatan,a.kd_rek5";
            }else {
            
            $sql = "SELECT a.*,kd_rek6,nm_rek6 ,(SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_sub_kegiatan=a.kd_sub_kegiatan  AND m.no_spd <> '$no' and m.tgl_spd<'$tgl') AS lalu,
                    (select sum(nilai) from trdrka where kd_rek6 = a.kd_rek6 AND kd_skpd=b.kd_skpd AND kd_sub_kegiatan = a.kd_sub_kegiatan) AS anggaran, (select sum(nilai_sempurna) from trdrka where kd_rek6 = a.kd_rek6 AND kd_skpd=b.kd_skpd AND kd_sub_kegiatan = a.kd_sub_kegiatan) AS anggaran_geser, (select sum(nilai_ubah) from trdrka where kd_rek6 = a.kd_rek6 AND kd_skpd=b.kd_skpd AND kd_sub_kegiatan = a.kd_sub_kegiatan) AS anggaran_ubah from trdspd a inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' AND left(b.kd_skpd,17)='$skpd1' 
                    AND a.kd_sub_kegiatan NOT IN (SELECT TOP $offset kd_sub_kegiatan FROM trdspd where no_spd = '$no' order by kd_sub_kegiatan)order by b.no_spd,a.kd_sub_kegiatan,a.kd_rek6";
            }

            // print_r($sql);die();

            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $row[] = array(
                            'id' => $ii,        
                            'no_spd' => $resulte['no_spd'],
                            'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                            'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                            'kd_program'  => $resulte['kd_program'],
                            'nm_program'  => $resulte['nm_program'],
                            'kd_rekening' => $resulte['kd_rek6'],
                            'nm_rekening' => $resulte['nm_rek6'],
                            'nilai'       => number_format($resulte['nilai'],"2",".",","),
                            'lalu'        => number_format($resulte['lalu'],"2",".",","),
                            'anggaran'    => number_format($resulte['anggaran'],"2",".",","),                               
                            'anggaran_geser'    => number_format($resulte['anggaran_geser'],"2",".",","),                               
                            'anggaran_ubah'    => number_format($resulte['anggaran_ubah'],"2",".",","),                               
                            );
                            $ii++;
            }
               
            $result["total"] = $total->tot;
            $result["rows"] = $row; 
            $query1->free_result();   
            echo json_encode($result);
        }


         function load_dspd_ag_pembiayaan() {            
            $no = $this->input->post('no');
            $jenis = $this->input->post('jenis');
            $skpd = $this->input->post('skpd');
            $tgl = $this->input->post('tgl');
            $cbln1 = $this->input->post('cbln1');
            //$stsubah=$this->rka_model->get_nama($skpd,'status_ubah','trhrka','kd_skpd');
            $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
            $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
            $offset = ($page-1)*$rows;
            $kriteria = '';
            $kriteria = $this->input->post('cari');
            $where ='';
            if ($kriteria <> ''){                               
                $where="AND (upper(no_sp2d) like upper('%$kriteria%') or tgl_sp2d like '%$kriteria%' or upper(kd_skpd) like 
                        upper('%$kriteria%') or upper(jns_spp) like upper('%$kriteria%')) ";            
            }

            $sql = "SELECT count(*) as tot from trdspd WHERE no_spd='$no'";
            $query1 = $this->db->query($sql);
            $total = $query1->row();
            
            $n_status = $this->get_status($tgl,$skpd);
            $field='$n_status'; 
            
            if ($jenis=='61'){
                  echo $jenis;
            //$field='nilai_sempurna';
            $sql = "SELECT a.*,kd_rek5,nm_rek5 ,isnull((SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd 
                    WHERE n.kd_kegiatan=a.kd_kegiatan  and kd_rek5=a.kd_Rek5 and m.no_spd <> '$no' and m.tgl_spd<'$tgl'),0) AS lalu,
                    (SELECT SUM($field) FROM trdrka WHERE kd_kegiatan = a.kd_kegiatan AND kd_skpd=b.kd_skpd and kd_rek5=a.kd_Rek5 ) AS anggaran from trdspd a
                    inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' order by b.no_spd,a.kd_kegiatan,a.kd_rek5";
            }else {
            //$field='nilai_sempurna';
            $sql = "SELECT a.*,kd_rek6,nm_rek6 ,(SELECT SUM(nilai) FROM trdspd n INNER JOIN trhspd m ON n.no_spd=m.no_spd WHERE n.kd_sub_kegiatan=a.kd_sub_kegiatan  AND m.no_spd <> '$no' and m.tgl_spd<'$tgl') AS lalu,
                    (select sum($n_status) from trdrka where kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_skpd=b.kd_skpd) AS anggaran from trdspd a inner join trhspd b on a.no_spd=b.no_spd where a.no_spd = '$no' AND b.kd_skpd='$skpd' 
                    AND a.kd_sub_kegiatan NOT IN (SELECT TOP $offset kd_sub_kegiatan FROM trdspd where no_spd = '$no' order by kd_sub_kegiatan)order by b.no_spd,a.kd_sub_kegiatan,a.kd_rek6";
            }
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $row[] = array(
                            'id' => $ii,        
                            'no_spd' => $resulte['no_spd'],
                            'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                            'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                            'kd_program'  => $resulte['kd_program'],
                            'nm_program'  => $resulte['nm_program'],
                            'kd_rekening' => $resulte['kd_rek6'],
                            'nm_rekening' => $resulte['nm_rek6'],
                            'nilai'       => number_format($resulte['nilai'],"2",".",","),
                            'lalu'        => number_format($resulte['lalu'],"2",".",","),
                            'anggaran'    => number_format($resulte['anggaran'],"2",".",",")                               
                            );
                            $ii++;
            }
               
            $result["total"] = $total->tot;
            $result["rows"] = $row; 
            $query1->free_result();   
            echo json_encode($result);
        }



        function load_dspd_all_keg($jenis='',$skpd='',$awal=0,$ahir=12,$nospd=''){

            
            $stsubah=$this->rka_model->get_nama($skpd,'status_ubah','trhrka','kd_skpd');
            
            if ($stsubah==0){
                $field="b.nilai";       
                $sts="susun";       
            }else{
                $field="b.nilai_ubah";              
                $sts="ubah";        
            }

            if ($jenis=='5'){
              $dan="a.jns_kegiatan ='$jenis'";
             
                $sql = " SELECT a.*,('')kd_rek6,('')nm_rek6,(SELECT SUM($field) FROM trdskpd b WHERE a.kd_sub_kegiatan=b.kd_sub_kegiatan AND b.bulan>=$awal AND b.bulan<=$ahir and b.status='$sts' ) AS nilai FROM trskpd a WHERE a.kd_skpd='$skpd' and $dan";
              }else{
              $dan="a.jns_kegiatan ='$jenis'";
             
                $sql = " SELECT b.kd_sub_kegiatan,f.nm_sub_kegiatan,f.kd_program,c.nm_program,b.kd_rek6,d.nm_rek6,$field from trdrka b inner join ms_sub_kegiatan f
     on b.kd_sub_kegiatan=f.kd_sub_kegiatan inner join ms_program c on 
    f.kd_program=c.kd_program inner join ms_rek6 d on b.kd_rek6=d.kd_rek6 where b.kd_sub_kegiatan in 
    (select kd_sub_kegiatan FROM trskpd a WHERE a.kd_skpd='$skpd' and $dan) order by b.kd_rek6";
              }

            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 

                if ($stsubah==0){
                    $angg="nilai";      
                }else{
                    $angg="nilai_ubah";             
                }
                if ($jenis=='5'){
                    $n_nilai=$resulte['nilai'];
                    if ($stsubah==0){
                        $angg="total";      
                    }else{
                        $angg="total_ubah";             
                    }   
                } else {
                    $n_nilai=0;
                    
                }
                
                $giat=$resulte['kd_sub_kegiatan'];
                $rek=$resulte['kd_rek6'];
                 $q_rek="a.kd_rek6 ='$rek'";
                $s = " SELECT SUM(a.nilai) as nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd WHERE b.kd_skpd='$skpd' AND a.kd_sub_kegiatan='$giat' and $q_rek and a.no_spd<>'$nospd' ";
                $q = $this->db->query($s);  
                foreach($q->result_array() as $res){
                    $lalu=$res['nilai'];
                }


                $result[] = array(
                            'id'          => $ii,        
                            'no_spd'      => '',
                            'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                            'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                            'kd_program'  => $resulte['kd_program'],
                            'nm_program'  => $resulte['nm_program'],
                            'kd_rekening' => $resulte['kd_rek6'],
                            'nm_rekening' => $resulte['nm_rek6'],
                            'nilai'       => number_format($n_nilai,"2",".",","),
                            'lalu'        => number_format($lalu,"2",".",","),
                            'anggaran'    => number_format($resulte[$angg],"2",".",",")
                            );
                            $ii++;
            }
               
            echo json_encode($result);
            $query1->free_result(); 
        }



        function load_dspd_all_keg_pembiayaan($jenis='',$skpd='',$awal=0,$ahir=12,$nospd=''){
            $stsubah=$this->rka_model->get_nama($skpd,'status_ubah','trhrka','kd_skpd');
            if ($stsubah==0){
                $field="b.nilai";       
                $sts="susun";       
            }else{
                $field="b.nilai_ubah";              
                $sts="ubah";        
            }

            if ($jenis=='62'){
              $dan="a.jns_kegiatan ='$jenis'";
             
                $sql = " SELECT a.*,('')kd_rek6,('')nm_rek6,(SELECT SUM($field) FROM trdskpd b WHERE a.kd_sub_kegiatan=b.kd_sub_kegiatan AND b.bulan>=$awal AND b.bulan<=$ahir and b.status='$sts' ) AS nilai FROM trskpd a WHERE a.kd_skpd='$skpd' and $dan";
              }else{
              $dan="a.jns_kegiatan ='$jenis'";
             
                $sql = " select b.kd_sub_kegiatan,f.nm_sub_kegiatan,f.kd_program,c.nm_program,b.kd_rek6,d.nm_rek6,$field from trdrka b inner join ms_sub_kegiatan f
     on b.kd_sub_kegiatan=f.kd_sub_kegiatan inner join ms_program c on 
    f.kd_program=c.kd_program inner join ms_rek6 d on b.kd_rek6=d.kd_rek6 where b.kd_sub_kegiatan in 
    (select kd_sub_kegiatan FROM trskpd a WHERE a.kd_skpd='$skpd' and $dan) order by b.kd_rek6";
              }

            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 

                if ($stsubah==0){
                    $angg="nilai";      
                }else{
                    $angg="nilai_ubah";             
                }
                if ($jenis=='62'){
                    $n_nilai=$resulte['nilai'];
                    if ($stsubah==0){
                        $angg="total";      
                    }else{
                        $angg="total_ubah";             
                    }   
                } else {
                    $n_nilai=0;
                    
                }
                
                $giat=$resulte['kd_sub_kegiatan'];
                $rek=$resulte['kd_rek6'];
                 $q_rek="a.kd_rek6 ='$rek'";
                $s = " SELECT SUM(a.nilai) as nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd WHERE b.kd_skpd='$skpd' AND a.kd_sub_kegiatan='$giat' and $q_rek and a.no_spd<>'$nospd' ";
                $q = $this->db->query($s);  
                foreach($q->result_array() as $res){
                    $lalu=$res['nilai'];
                }


                $result[] = array(
                            'id'          => $ii,        
                            'no_spd'      => '',
                            'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],
                            'nm_sub_kegiatan' => $resulte['nm_sub_kegiatan'],
                            'kd_program'  => $resulte['kd_program'],
                            'nm_program'  => $resulte['nm_program'],
                            'kd_rekening' => $resulte['kd_rek6'],
                            'nm_rekening' => $resulte['nm_rek6'],
                            'nilai'       => number_format($n_nilai,"2",".",","),
                            'lalu'        => number_format($lalu,"2",".",","),
                            'anggaran'    => number_format($resulte[$angg],"2",".",",")
                            );
                            $ii++;
            }
               
            echo json_encode($result);
            $query1->free_result(); 
        }



         function get_realisasi_keg_spd($cskpd=''){
            $kdskpd 		= $this->input->post('skpd');
            $subkegiatan 	= $this->input->post('keg');
            $cbln2 			=  $this->input->post('cbln2'); 
            $query1 		= $this->db->query("SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
                                        (
                                            select c.kd_sub_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                            where c.kd_sub_kegiatan='$subkegiatan' and c.kd_skpd='$kdskpd' and b.jns_spp not in ('1','2') and MONTH(b.tgl_spp)<'$cbln2' 
                                            and (sp2d_batal<>'1' or sp2d_batal is null ) 
                                            group by c.kd_sub_kegiatan
                                        ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan
                                        left join 
                                        (
                                            select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                            from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                            where f.kd_sub_kegiatan='$subkegiatan' and MONTH(e.tgl_bukti)<'$cbln2' and e.jns_spp ='1' group by f.kd_sub_kegiatan
                                        ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan
                                        left join 
                                        (
                                            SELECT t.kd_sub_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                            INNER JOIN trhtagih u 
                                            ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                            WHERE t.kd_sub_kegiatan = '$subkegiatan'  
                                            AND u.kd_skpd='$kdskpd'
                                            AND MONTH(u.tgl_bukti)<'$cbln2'
                                            AND u.no_bukti 
                                            NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd' )
                                            GROUP BY t.kd_sub_kegiatan
                                        ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan
                                        where a.kd_sub_kegiatan='$subkegiatan'");
             
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $result[] = array(
                                'id' => $ii,        
                                'nrealisasi' => number_format($resulte['total'],2,'.',',')                                              
                            );
                            $ii++;
            }
           
               echo json_encode($result);
               $query1->free_result();  
        }


        function get_realisasi_keg_spd_pembiayaan($cskpd=''){
            $kdskpd         = $this->input->post('skpd');
            $subkegiatan    = $this->input->post('keg');
            $cbln2          =  $this->input->post('cbln2'); 
            $query1         = $this->db->query("SELECT total=isnull(spp,0)+isnull(transaksi,0)+isnull(penagihan,0) from trskpd a left join
                                        (
                                            select c.kd_sub_kegiatan,sum(c.nilai) [spp] from trhspp b join trdspp c on b.no_spp=c.no_spp and b.kd_skpd=c.kd_skpd
                                            where c.kd_sub_kegiatan='$subkegiatan' and b.jns_spp not in ('1','2') and MONTH(b.tgl_spp)<'$cbln2' 
                                            and (sp2d_batal<>'1' or sp2d_batal is null ) 
                                            group by c.kd_sub_kegiatan
                                        ) as d on a.kd_sub_kegiatan=d.kd_sub_kegiatan
                                        left join 
                                        (
                                            select f.kd_sub_kegiatan,sum(f.nilai) [transaksi]
                                            from trhtransout e join trdtransout f on e.no_bukti=f.no_bukti and e.kd_skpd=f.kd_skpd
                                            where f.kd_sub_kegiatan='$subkegiatan' and MONTH(e.tgl_bukti)<'$cbln2' and e.jns_spp ='1' group by f.kd_sub_kegiatan
                                        ) g on a.kd_sub_kegiatan=g.kd_sub_kegiatan
                                        left join 
                                        (
                                            SELECT t.kd_sub_kegiatan, SUM(t.nilai) [penagihan] FROM trdtagih t 
                                            INNER JOIN trhtagih u 
                                            ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                            WHERE t.kd_sub_kegiatan = '$subkegiatan'  
                                            AND u.kd_skpd='$kdskpd'
                                            AND MONTH(u.tgl_bukti)<'$cbln2'
                                            AND u.no_bukti 
                                            NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd' )
                                            GROUP BY t.kd_sub_kegiatan
                                        ) z ON a.kd_sub_kegiatan=z.kd_sub_kegiatan
                                        where a.kd_sub_kegiatan='$subkegiatan'");
             
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $result[] = array(
                                'id' => $ii,        
                                'nrealisasi' => number_format($resulte['total'],2,'.',',')                                              
                            );
                            $ii++;
            }
           
               echo json_encode($result);
               $query1->free_result();  
        }


        function get_anggkas_keg($cskpd=''){
            $kdskpd = $this->input->post('skpd');
            $kegiatan = $this->input->post('keg');
            $cbln1 =  $this->input->post('cbln1');
            $cbln2 =  $this->input->post('cbln2');  
            //$tgl = $this->input->post('tgl');
            $n_status = $this->get_status2($kdskpd);
            $query1 = $this->db->query("select isnull(sum($n_status),0) [total] from trdskpd where bulan>='$cbln1' and bulan<='$cbln2' and kd_skpd='$kdskpd' and kd_sub_kegiatan='$kegiatan' ");  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $result[] = array(
                                'id' => $ii,        
                                'vanggkas' => number_format($resulte['total'],2,'.',',')                                              
                            );
                            $ii++;
            }
           
               echo json_encode($result);
               $query1->free_result();  
        }


        function get_anggkas_keg_pembiayaan($cskpd=''){
            $kdskpd = $this->input->post('skpd');
            $kegiatan = $this->input->post('keg');
            $cbln1 =  $this->input->post('cbln1');
            $cbln2 =  $this->input->post('cbln2');  
            //$tgl = $this->input->post('tgl');
            $n_status = $this->get_status2($kdskpd);
            $query1 = $this->db->query("select isnull(sum($n_status),0) [total] from trdskpd where bulan>='$cbln1' and bulan<='$cbln2' and kd_skpd='$kdskpd' and kd_sub_kegiatan='$kegiatan' ");  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $result[] = array(
                                'id' => $ii,        
                                'vanggkas' => number_format($resulte['total'],2,'.',',')                                              
                            );
                            $ii++;
            }
           
               echo json_encode($result);
               $query1->free_result();  
        }


        function simpan_spd(){
            $tabel  	= $this->input->post('tabel');
            $idx    	= $this->input->post('cidx');
            $nomor  	= $this->input->post('no');
            $nomor2  	= $this->input->post('no2');
            $mode_tox	= $this->input->post('mode_tox');
            $tgl    	= $this->input->post('tgl');
            $skpd   	= $this->input->post('skpd');
            $nmskpd 	= $this->input->post('nmskpd');
            $bend   	= $this->input->post('bend');
            $bln1   	= $this->input->post('bln1');
            $bln2   	= $this->input->post('bln2');
            $ketentuan  = $this->input->post('ketentuan');
            $pengajuan  = $this->input->post('pengajuan');
            $jenis      = $this->input->post('jenis');
            $total      = $this->input->post('total');
            $csql       = $this->input->post('sql');        
            $usernm     = $this->session->userdata('pcNama');    
            $update     = date('Y-m-d H:i:s');    
            $msg = array();                
            // Simpan Header //
            if ($tabel == 'trhspd') {
                if ($mode_tox=='tambah'){
    //            $sql = "delete from  $tabel where kd_skpd='$skpd' and no_spd='$nomor'";
    //           $asg = $this->db->query($sql);
               //if ($asg){
                    $sql = "insert into  $tabel (no_spd,tgl_spd,kd_skpd,nm_skpd,jns_beban,bulan_awal,bulan_akhir,total,klain,kd_bkeluar,username,tglupdate) 
                            values('$nomor','$tgl','$skpd', rtrim('$nmskpd'),'$jenis','$bln1','$bln2','$total', rtrim('$ketentuan'),'$bend','$usernm','$update')";
                    $asg = $this->db->query($sql);
                    if (!($asg)){
                        $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    } else {
                            $msg = array('pesan'=>'1');
                            echo json_encode($msg);
                    }          
                //} else {
               //     $msg = array('pesan'=>'0');
                //    echo json_encode($msg);
                 //   exit();
                //}
                } else {
                    $sql = "update $tabel set 
                        no_spd='$nomor',tgl_spd='$tgl',kd_skpd='$skpd',nm_skpd=rtrim('$nmskpd'),
                        jns_beban='$jenis',bulan_awal='$bln1',bulan_akhir='$bln2',total='$total',klain=rtrim('$ketentuan'),kd_bkeluar='$bend',username='$usernm',tglupdate='$update'
                        where no_spd='$nomor2' ";
                    $asg = $this->db->query($sql);
                    if (!($asg)){
                        $msg = array('pesan'=>'0');
                        echo json_encode($msg);
                        exit();
                    } else {
                            $msg = array('pesan'=>'1');
                            echo json_encode($msg);
                    }          
                    
                }
                
            } else if ($tabel == 'trdspd') {
                
                // Simpan Detail //                       
                    $sql = "delete from  $tabel where no_spd='$nomor2'";
                    $asg = $this->db->query($sql);
                    if (!($asg)){
                       $msg= array('pesan'=>'0');
                       echo json_encode($msg);
                       exit();
                    } else {
                        $sql = "insert into  $tabel(no_spd,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,kd_program,nm_program,nilai)";                        
                        $asg = $this->db->query($sql.$csql);
                        if (!($asg)){
                            $msg = array('pesan'=>'0');
                            echo json_encode($msg);
                            exit();
                        }  else {
                            $msg = array('pesan'=>'1');
                            echo json_encode($msg);
                        }
                    }                                                             
            }
        }



        //cetak spd
        function cetak_lampiran_spd1(){
            
            $print          = $this->uri->segment(2);
            $tnp_no         = $this->uri->segment(3);
            $cell           = $this->uri->segment(5);
            $nip_ppkd       = $this->input->post('nip_ppkd');  
            $nama_ppkd      = $this->input->post('nama_ppkd');       
            $jabatan_ppkd   = $this->input->post('jabatan_ppkd'); 
            $pangkat_ppkd   = $this->input->post('pangkat_ppkd'); 
            $kop            = $this->input->post('kop');   
            $lntahunang     = $this->session->userdata('pcThang');       
            $lcnospd        = $this->input->post('nomor1');
            $lkd_skpd       = $this->rka_model->get_nama($lcnospd,'kd_skpd','trhspd','no_spd');
            $ldtgl_spd      = $this->rka_model->get_nama($lcnospd,'tgl_spd','trhspd','no_spd');
            $stsubah        = $this->rka_model->get_nama($lkd_skpd,'status','trhrka','kd_skpd');
            $field = $this->get_status($ldtgl_spd,$lkd_skpd);

            // print_r($stsubah);die();

            //$n_status = $this->get_status2($lckdskpd);
            $csql = "SELECT (SELECT no_dpa FROM trhrka WHERE kd_skpd = a.kd_skpd) AS no_dpa,
                    (SELECT SUM($field) FROM trdrka WHERE kd_sub_kegiatan IN(SELECT kd_sub_kegiatan FROM trdspd WHERE no_spd=a.no_spd)
                     AND left(kd_skpd,22) = left(a.kd_skpd,22)) jm_ang,
                    (SELECT SUM(total) FROM trhspd WHERE kd_skpd = a.kd_skpd AND jns_beban=a.jns_beban AND 
                    tgl_spd<=a.tgl_spd AND no_spd<>a.no_spd) AS jm_spdlalu,
                    (select sum(nilai) from trdspd f where f.no_spd=a.no_spd) AS jm_spdini,a.jns_beban,a.bulan_awal,a.bulan_akhir,kd_skpd
                    FROM trhspd a WHERE a.no_spd = '$lcnospd '";
                  //  echo($csql);
                    
            $hasil = $this->db->query($csql);
            $data1 = $hasil->row();
            $periode_awal = $this->rka_model->getBulan(1);
            $periode1 = $this->rka_model->getBulan($data1->bulan_awal);
            $periode2 = $this->rka_model->getBulan($data1->bulan_akhir);
            $jnsspd = $data1->jns_beban;
            $jm_ang = $data1->jm_ang;
            $jm_spdlalu = $data1->jm_spdlalu;
            $jm_spdini = $data1->jm_spdini;
            $A = $jm_ang;
            $b = $jm_spdlalu + $jm_spdini;
            $lnsisa = $jm_ang - ($jm_spdlalu + $jm_spdini);
             
            $lkd_skpd =$data1->kd_skpd;
            $ljns_beban =$data1->jns_beban;
            
            if ($stsubah==0){
                $field="nilai";     
            }else{
                $field="nilai_sempurna";                
            }
            
           

             if ($ljns_beban=='6')
            {
                $nm_beban="PENGELUARAN PEMBIAYAAN";
                $sql2 = "select * from ms_rek1 where kd_rek1='6'";
                $hasil2 = $this->db->query($sql2);
                $tox2 = $hasil2->row();
                $nm_beban2 = $tox2->nm_rek1;
                $kd_rek1 = $tox2->kd_rek1;

            } else if ($ljns_beban=='5')
                {
                    $nm_beban="BELANJA";
                    $sql2 = "select * from ms_rek1 where kd_rek1='5'";
                    $hasil2 = $this->db->query($sql2);
                    $tox2 = $hasil2->row();
                    $nm_beban2 = $nm_beban;//$tox2->nm_rek1;
                    $kd_rek1 = $tox2->kd_rek1;
            };
            
            $nm_rek2 =$this->rka_model->get_nama($ljns_beban,'nm_rek1','ms_rek1','kd_rek1');
            
            $nospd_cetak= $lcnospd;
            $tahun=$this->tukd_model->get_sclient('thn_ang','sclient');
                    
            if ($tnp_no=='1'){
            $con_dpn='903/';
            
                //$tahun=$this->session->userdata('pcThang');
                    $con_blk_btl=' /PEMBIAYAAN/BKAD-B/'.$tahun;
                    $con_blk_bl='  /BELANJA/BKAD-B/'.$tahun;     
        
                ( $ljns_beban=='62') ?  $nospd_cetak=$con_dpn."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$con_blk_btl:$nospd_cetak=$con_dpn."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$con_blk_bl;
                }   
                $kode= substr($lkd_skpd,0,-3);      
                if($ljns_beban=='5' || $kode=='4.09.01' || $kode=='1.04.02' || $kode=='2.05.01' || $kode=='4.02.02' || $kode=='4.02.03' || $kode=='2.17.01'){
                    $nospd_cetak= str_replace('BKAD','BKAD',$nospd_cetak); 
                    $elek="KEPALA BADAN KEUANGAN DAN ASET DAERAH"; $raimu="PLT. ";
                } else {  $elek= "KEPALA BADAN PENGELOLAAN KEUANGAN DAN PENDAPATAN DAERAH";
                          $raimu="";
                          $nospd_cetak= str_replace('BKAD','BPKPD',$nospd_cetak);
                }

                 
                
                 if($ljns_beban=='5' || $kode=='4.09.01' || $kode=='1.04.02' || $kode=='2.05.01' || $kode=='4.02.02' || $kode=='4.02.03' || $kode=='2.17.01'  ){
                $nospd_cetak= str_replace('2021','2021',$nospd_cetak);
            }else{
                $nospd_cetak= str_replace('2021','2020',$nospd_cetak);
            }
            
            $cRet = '';
            $field = $this->get_status2($lkd_skpd); 


             if ($field=='nilai_ubah'){
                $no_dpa=$this->rka_model->get_nama($lkd_skpd,'no_dpa_ubah','trhrka','kd_skpd');
             }else if($field=='nilai_sempurna'){
                $no_dpa=$this->rka_model->get_nama($lkd_skpd,'no_dpa_sempurna','trhrka','kd_skpd');
             }else{
                $no_dpa=$this->rka_model->get_nama($lkd_skpd,'no_dpa','trhrka','kd_skpd');
             }
          
            $font = 12;
            $font1 = $font-1;

            $sql = "select kd_skpd,nm_skpd from ms_skpd where kd_skpd='$lkd_skpd'";
            $hasil = $this->db->query($sql);
            $tox = $hasil->row();
            $kd_skpd = $tox->kd_skpd;

            $raimu="PEJABAT PENGELOLA KEUANGAN DAERAH SELAKU BENDAHARA UMUM DAERAH";
            $cRet .="<table width='100%' style='font-size:12px' cellpadding='0px'  cellspacing='0' > 
                    <tr>
                        <td width='90%'><center>
                            PEMERINTAH KABUPATEN SANGGAU 
                            <br /> $raimu 
                            <br />NOMOR $nospd_cetak
                            <br /> TENTANG
                            <br /> SURAT PENYEDIAAN DANA ANGGARAN BELANJA DAERAH
                            <br /> TAHUN ANGGARAN $tahun
                            <br />
                            <br />&nbsp;
                        <center></td>

                    </tr>
                                                   
            </table>";

            $cRet .="<table style=\"border-collapse:collapse;font-family:Times New Roman; font-size:10 px;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">               
                        <tr>
                            <td width=\"18%\" colspan=\"2\" align=\"left\">LAMPIRAN SURAT PENYEDIAAN DANA </td>
                        </tr>
                        <tr>
                            <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                        </tr>
                        <tr>
                            <td width=\"18%\" align=\"left\">NOMOR SPD </td>
                            <td width=\"72%\" align=\"left\">: $nospd_cetak</td>
                        </tr>
                        <tr>
                            <td width=\"18%\" align=\"left\">TANGGAL</td>
                            <td width=\"72%\" align=\"left\">: ".$this->support->tanggal_format_indonesia($ldtgl_spd)."</td>
                        </tr>
                        <tr><td align=\"left\"> SKPD </td><td align=\"left\">: ".$tox->nm_skpd."</td></tr>
                        <tr><td align=\"left\"> PERIODE BULAN </td><td align=\"left\">: $periode_awal s/d $periode2</td></tr>
                        <tr><td align=\"left\">TAHUN ANGGARAN </td><td align=\"left\">: $lntahunang</td></tr>
                        <tr><td align=\"left\">NOMOR DPA-SKPD </td><td align=\"left\">: $no_dpa</td></tr>
                        <tr>
                            <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                        </tr>
                    </table>";
            $cRet .="
               <table style=\"border-collapse:collapse;font-family:Times New Roman; font-size:$font px;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"$cell\">               
                    <tr>
                        <td width=\"3%\" align=\"center\" style=\"font-weight:bold;\">No.        
                        </td>
                        <td colspan=\"2\" width=\"55%\" align=\"center\" style=\"font-weight:bold;\">Kode, dan Nama Program, Kegiatan dan Sub Kegiatan        
                        </td>
                        </td>
                        <td width=\"11%\" align=\"center\" style=\"font-weight:bold;\">ANGGARAN</td>
                        <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">AKUMULASI SPD SEBELUMNYA</td>
                        <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">JUMLAH SPD PERIODE INI</td>
                        <td width=\"11%\" align=\"center\" style=\"font-weight:bold;\">SISA ANGGARAN</td>
                    </tr>
                    <tr>
                        <td width=\"3%\" align=\"center\" style=\"font-weight:bold;\">1</td>
                        <td colspan=\"2\" width=\"55%\" align=\"center\" style=\"font-weight:bold;\">2</td>
                        <td width=\"11%\" align=\"center\" style=\"font-weight:bold;\">3</td>
                        <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">4</td>
                        <td width=\"10%\" align=\"center\" style=\"font-weight:bold;\">5</td>
                        <td width=\"11%\" align=\"center\" style=\"font-weight:bold;\">6=3-4-5</td>
                    </tr>
                    ";

                                                
            if ($ljns_beban=='5'){ 
                $sql = "SELECT * from (( 
                select (ROW_NUMBER() OVER (ORDER BY ss.kd_program))no_urut,ss.kd_skpd,(ss.kd_program)kode,rtrim(ss.nm_program)uraian,anggaran,spd_lalu, 
                nilai from ( 
                SELECT ('')no_urut,b.kd_skpd,rtrim(a.kd_program)kode,c.nm_program, isnull(( 
                SELECT SUM($field) FROM trdrka 
                WHERE left(kd_sub_kegiatan,7) = a.kd_program AND left(kd_skpd,22)=left(b.kd_skpd,22)),0) AS anggaran, isnull(( 
                SELECT SUM(nilai) FROM trdspd c 
                LEFT JOIN trhspd d ON c.no_spd=d.no_spd 
                WHERE c.kd_program = a.kd_program AND d.kd_skpd=b.kd_skpd AND c.no_spd <> a.no_spd AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) 
                AS spd_lalu, sum(a.nilai) as nilai, a.kd_program FROM trdspd a 
                LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                inner join trskpd c on a.kd_program=c.kd_program and a.kd_sub_kegiatan=c.kd_sub_kegiatan and b.kd_skpd=c.kd_skpd 
                WHERE a.no_spd = '$lcnospd' 
                group by b.kd_skpd,a.kd_program,c.nm_program,a.no_spd,b.tgl_spd,b.jns_beban) ss 
                inner join trskpd b on ss.kode=b.kd_program and b.kd_skpd=ss.kd_skpd 
                group by no_urut,ss.kd_skpd,ss.kd_program,ss.nm_program,ss.anggaran,ss.spd_lalu,ss.nilai)

                union all 

                select ('')no_urut,ss.kd_skpd,rtrim(b.kd_kegiatan)kode, 
                (select rtrim(j.nm_kegiatan) from ms_kegiatan j where j.kd_kegiatan=b.kd_kegiatan)as uraian,
                anggaran,spd_lalu,sum(nilai)nilai from ( 
                SELECT ('')no_urut,b.kd_skpd,rtrim(left(a.kd_sub_kegiatan,12))kode,c.nm_kegiatan, isnull((
                SELECT SUM($field) FROM trdrka 
                WHERE left(kd_sub_kegiatan,12) = left(a.kd_sub_kegiatan,12) AND left(kd_skpd,22)=left(b.kd_skpd,22)),0) AS anggaran, isnull((
                SELECT SUM(nilai) FROM trdspd c 
                LEFT JOIN trhspd d ON c.no_spd=d.no_spd 
                WHERE LEFT ( c.kd_sub_kegiatan, 12 ) = LEFT ( a.kd_sub_kegiatan, 12 ) AND d.kd_skpd=b.kd_skpd AND c.no_spd <> a.no_spd AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) 
                AS spd_lalu, a.nilai,a.kd_program FROM trdspd a 
                LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                inner join trskpd c on a.kd_sub_kegiatan=c.kd_sub_kegiatan and b.kd_skpd=c.kd_skpd
                WHERE a.no_spd = '$lcnospd' ) ss 
                inner join ms_kegiatan b on ss.kode=b.kd_kegiatan 
                group by no_urut,ss.kd_skpd,b.kd_kegiatan,ss.anggaran,ss.spd_lalu
                union all

                (SELECT ('')no_urut,b.kd_skpd,rtrim(a.kd_sub_kegiatan)kode,c.nm_sub_kegiatan, isnull((
                SELECT SUM($field) FROM trdrka 
                WHERE kd_sub_kegiatan = a.kd_sub_kegiatan AND left(kd_skpd,22)=left(b.kd_skpd,22)),0) AS anggaran, isnull((
                SELECT SUM(nilai) FROM trdspd c 
                LEFT JOIN trhspd d ON c.no_spd=d.no_spd 
                WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan AND d.kd_skpd=b.kd_skpd AND c.no_spd <> a.no_spd AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) 
                AS spd_lalu, sum(a.nilai) nilai FROM trdspd a 
                LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                inner join trskpd c on a.kd_sub_kegiatan=c.kd_sub_kegiatan and b.kd_skpd=c.kd_skpd
                WHERE a.no_spd = '$lcnospd' 
                GROUP BY b.kd_skpd,a.kd_sub_kegiatan,c.nm_sub_kegiatan,a.no_spd,b.tgl_spd,b.jns_beban) ) zt 
                order by kode";        
                    

            } else{
            
                    $sql = "    
                            SELECT no_urut, kode, uraian, anggaran, spd_lalu,nilai,   
                            case when right(kd_sub_kegiatan,5)='00.51'
                                then LEFT(kd_sub_kegiatan,19)+'00'
                                else kd_sub_kegiatan
                            end [kd_sub_kegiatan] 
                            from ((
                            select ('')no_urut,rtrim(c.kd_rek3)kode,rtrim(nm_rek3)uraian,
                            sum(anggaran)anggaran,sum(spd_lalu)spd_lalu,sum(nilai)nilai,kd_sub_kegiatan from 
                            (
                            SELECT rtrim(a.kd_rek6)kode,c.nm_rek6,a.kd_sub_kegiatan,
                            isnull((SELECT SUM($field) FROM trdrka WHERE kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_skpd=b.kd_skpd and kd_rek6=a.kd_rek6),0) AS anggaran,
                            isnull((SELECT SUM(nilai) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan and kd_rek6=a.kd_rek6 AND d.kd_skpd=b.kd_skpd AND c.no_spd <> a.no_spd AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                            a.nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
                                    WHERE  a.no_spd  = '$lcnospd'
                            ) ss 
                            inner join ms_rek3 c on left(ss.kode,3)=c.kd_rek3 group by c.kd_rek3,nm_rek3,ss.kd_sub_kegiatan
                            ) union all
                            (
                            select (ROW_NUMBER() OVER (ORDER BY c.kd_rek4))no_urut,rtrim(c.kd_rek4)kode,rtrim(nm_rek4)uraian,
                            sum(anggaran)anggaran,sum(spd_lalu)spd_lalu,sum(nilai)nilai,kd_sub_kegiatan from 
                            (
                            SELECT rtrim(a.kd_rek6)kode,c.nm_rek6,a.kd_sub_kegiatan,
                            isnull((SELECT SUM($field) FROM trdrka WHERE kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_skpd=b.kd_skpd and kd_rek6=a.kd_rek6),0) AS anggaran,
                            isnull((SELECT SUM(nilai) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan and kd_rek6=a.kd_rek6 AND d.kd_skpd=b.kd_skpd AND c.no_spd <> a.no_spd AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                            a.nilai FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
                                    WHERE  a.no_spd  = '$lcnospd'
                            ) ss 
                            inner join ms_rek4 c on left(ss.kode,5)=c.kd_rek4 group by c.kd_rek4,nm_rek4,ss.kd_sub_kegiatan
                            )
                            union all
                            (
                            SELECT ('')no_urut,rtrim(a.kd_rek6)kode,c.nm_rek6,
                            isnull((SELECT SUM($field) FROM trdrka WHERE kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_skpd=b.kd_skpd and kd_rek6=a.kd_rek6),0) AS anggaran,
                            isnull((SELECT SUM(nilai) FROM trdspd c LEFT JOIN trhspd d ON c.no_spd=d.no_spd WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan and kd_rek6=a.kd_rek6 AND d.kd_skpd=b.kd_skpd AND c.no_spd <> a.no_spd AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) AS spd_lalu,
                            a.nilai,a.kd_sub_kegiatan FROM trdspd a LEFT JOIN trhspd b ON a.no_spd=b.no_spd inner join ms_rek6 c on a.kd_rek6=c.kd_rek6
                                    WHERE  a.no_spd = '$lcnospd' 
                                    )
                                    ) zt order by kode";
                    }
            
        
                        
                        $hasil = $this->db->query($sql);
                        $lcno = 0;
                        $lntotal = 0;
                        $jtotal_spd = 0;
                        foreach ($hasil->result() as $row)
                        {
                           $lcno = $lcno + 1;
                           //$lntotal = $lntotal + $row->nilai;
                           $lcsisa = $row->anggaran - $row->spd_lalu - $row->nilai;
                           $total_spd=$row->spd_lalu + $row->nilai;
                           //echo $row->no_dpa;
                           if ($row->no_urut=='0') {
                            $lcno_urut='';
                           } else {
                               $lcno_urut=$row->no_urut;
                           };
                           $kode=$row->kode;
                           $lenkode = strlen($kode);
                           $ckode=$row->kode;

                           //copy
                           //copy bl
                           if ($ljns_beban=='5'){
                               if ($lenkode <= 15){
                                    $bold = 'font-weight:bold;';
                                    $fontr = $font1;
                               }else{
                                    $bold = '';
                                    $fontr = $font;
                               }
     
                                if($lenkode==18){
                                    $jtotal_spd = $jtotal_spd + $total_spd;
                                }
                            }
                           
                           if ( $ljns_beban=='62'){
                               if ($lenkode <= 5){
                                    $bold = 'font-weight:bold;';
                                    $fontr = $font1;
                               }else{
                                    $bold = '';
                                    $fontr = $font;
                               }
                                if($lenkode==3){
                                    $jtotal_spd = $jtotal_spd + $total_spd;
                                }

                               $kode=$row->kd_kegiatan.'.'.$this->rka_model->dotrek($kode);
                           }
                                $cRet .="<tr>
                                            <td width=\"3%\" align=\"center\" style=\"$bold font-size:$fontr px\">$lcno.   
                                            </td>
                                            <td width=\"17%\" style=\"$bold font-size:$fontr px\">$kode 
                                            
                                            </td>
                                            <td width=\"38%\" style=\"$bold font-size:$fontr px\">$row->uraian       
                                            </td>
                                            <td width=\"11%\" align=\"right\" style=\"$bold font-size:$fontr px\">".number_format($row->anggaran,"2",",",".")."&nbsp;     
                                            </td>
                                            <td width=\"10%\" align=\"right\" style=\"$bold font-size:$fontr px\">".number_format($row->spd_lalu,"2",",",".")."&nbsp;    
                                            </td>
                                            <td width=\"10%\" align=\"right\" style=\"$bold font-size:$fontr px\">".number_format($row->nilai,"2",",",".")."&nbsp;
                                            </td>
                                            
                                            <td width=\"11%\" align=\"right\" style=\"$bold font-size:$fontr px\">".number_format($lcsisa,"2",",",".")."&nbsp;
                                            </td>
                                        </tr>"; 
                           if ($lenkode == 15){
                               $sqldetail = "SELECT ('')no_urut,b.kd_skpd,rtrim(a.kd_rek6)kode,a.nm_rek6 as uraian, 
                                    isnull((
                                    SELECT sum($field) FROM trdrka 
                                    WHERE kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_rek6=a.kd_rek6 AND left(kd_skpd,22)=left(b.kd_skpd,22)),0) AS anggaran, 
                                    isnull((
                                    SELECT sum(nilai) FROM trdspd c 
                                    LEFT JOIN trhspd d ON c.no_spd=d.no_spd 
                                    WHERE c.kd_sub_kegiatan = a.kd_sub_kegiatan AND c.kd_rek6=a.kd_rek6 AND d.kd_skpd=b.kd_skpd AND c.no_spd <> a.no_spd AND d.tgl_spd<=b.tgl_spd AND d.jns_beban = b.jns_beban),0) 
                                    AS spd_lalu, a.nilai FROM trdspd a 
                                    LEFT JOIN trhspd b ON a.no_spd=b.no_spd 
                                    inner join trskpd c on a.kd_sub_kegiatan=c.kd_sub_kegiatan and b.kd_skpd=c.kd_skpd
                                    WHERE a.no_spd = '$lcnospd' and a.kd_sub_kegiatan='$ckode' order by kode";
                                $hasildetail = $this->db->query($sqldetail);
                                foreach ($hasildetail->result() as $rowdetail)
                                {
                                    $lcno = $lcno + 1;
                                    $ang=$rowdetail->anggaran;
                                    $spdlalu=$rowdetail->spd_lalu;
                                    $nilai=$rowdetail->nilai;
                                    $lccsisa2 = $ang - $spdlalu - $nilai;
                                    $xkode = $rowdetail->kode;
                                    $a= substr($xkode,0,1);
                                    $b= substr($xkode,1,1);
                                    $c= substr($xkode,2,2);
                                    $d= substr($xkode,3,2);
                                    $e= substr($xkode,5,2);
                                    $f= substr($xkode,8,4);
                                    $kodex= $a.'.'.$b.'.'.$c.'.'.$d.'.'.$e.'.'.$f;


                                
                                    $cRet .="<tr>
                                            <td width=\"3%\" align=\"center\" style=\"font-size:$fontr px\">$lcno.   
                                            </td>
                                            <td width=\"17%\" style=\"font-size:$fontr px\">$xkode 
                                            
                                            </td>
                                            <td width=\"38%\" style=\"font-size:$fontr px\">$rowdetail->uraian       
                                            </td>
                                            <td width=\"11%\" align=\"right\" style=\"font-size:$fontr px\">".number_format($rowdetail->anggaran,"2",",",".")."&nbsp;     
                                            </td>
                                            <td width=\"10%\" align=\"right\" style=\"font-size:$fontr px\">".number_format($rowdetail->spd_lalu,"2",",",".")."&nbsp;    
                                            </td>
                                            <td width=\"10%\" align=\"right\" style=\"font-size:$fontr px\">".number_format($rowdetail->nilai,"2",",",".")."&nbsp;
                                            </td>
                                            
                                            <td width=\"11%\" align=\"right\" style=\"font-size:$fontr px\">".number_format($lccsisa2,"2",",",".")."&nbsp;
                                            </td>
                                        </tr>"; 
                                }                                 
                            }
                            
                        }
                     //perbaiki $total_spd <td align=\"right\" style=\"font-weight:bold;font-size:$font1 px\">".number_format($data1->jm_spdini,"2",".",",")."&nbsp;
                     $lnsisa = number_format($lnsisa,"2",",",".");
                     $cRet .="<tr>
                                        <td align=\"right\" style=\"font-weight:bold;\" colspan=3>JUMLAH &nbsp;&nbsp;&nbsp;       
                                        </td>
                                        <td align=\"right\" style=\"font-weight:bold;font-size:$font1 px\">".number_format($data1->jm_ang,"2",",",".")."&nbsp;           
                                        </td>
                                        <td align=\"right\" style=\"font-weight:bold;font-size:$font1 px\">".number_format($data1->jm_spdlalu,"2",",",".")."&nbsp;        
                                        </td>
                                        <td align=\"right\" style=\"font-weight:bold;font-size:$font1 px\">".number_format($data1->jm_spdini,"2",",",".")."&nbsp;
                                        </td>

                                        <td align=\"right\" style=\"font-weight:bold;font-size:$font1 px\">$lnsisa
                                        </td>
                                    </tr>";         

                    $cRet .="</table>";
                    

                    $cRet .="<table style=\"border-collapse:collapse;font-family:Times New Roman; font-size:10 px;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">  
                        <tr>
                            <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                        </tr>             
                        <tr>
                            <td width=\"18%\" colspan=\"2\" align=\"left\">Jumlah Penyediaan Dana Rp".number_format($data1->jm_spdini,"2",",",".")."<br />
                            <i>(".$this->tukd_model->terbilang($data1->jm_spdini).")</i></td>
                        </tr>
                        <tr>
                            <td width=\"18%\" colspan=\"2\" align=\"left\">&nbsp; </td>
                        </tr>
                    </table>";
    // CETAKAN TANDA TANGAN by Tox
                $cRet .=" <table style=\"border-collapse:collapse;font-family: arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                 <tr >
                    <td width=\"60%\" align=\"right\">&nbsp;</td>
                    <td width=\"40%\"  align=\"center\" colspan=\"2\">&nbsp;&nbsp;Ditetapkan di Sanggau</td>
                    </td>
                </tr>
            <tr >
                    <td width=\"70%\" align=\"right\" colspan=\"2\">&nbsp;
                    </td>   
                    <td width=\"30%\"  text-indent: 50px; align=\"left\">Pada tanggal ".$this->support->tanggal_format_indonesia($ldtgl_spd)."</td>
                    </td>
                </tr>   
                 <tr >
                    <td align=\"right\">&nbsp;</td>
                    <td  align=\"center\" colspan=\"2\">&nbsp;</td>
                    </td>
                </tr>
            <tr >
                    <td width=\"60%\" align=\"right\">&nbsp;</td>
                    <td width=\"40%\"  align=\"center\" colspan=\"2\" style=\"text-transform: uppercase;\"> PPKD SELAKU BUD,</td>
                    </td>
                </tr>
                <tr >
                    <td align=\"right\">&nbsp;</td>
                    <td  align=\"center\" colspan=\"2\">&nbsp;</td>
                    </td>
                </tr>
                <tr >
                    <td align=\"right\">&nbsp;</td>
                    <td  align=\"center\" colspan=\"2\">&nbsp;</td>
                    </td>
                </tr>
                <tr >
                    <td align=\"right\">&nbsp;</td>
                    <td  align=\"center\" colspan=\"2\">&nbsp;</td>
                    </td>
                </tr>   
            <tr >
                    <td align=\"right\">&nbsp;</td>
                    <td  align=\"center\" colspan=\"2\"><u>$nama_ppkd</u></td>
                    </td>
                </tr>
            <tr >
                    <td  align=\"right\">&nbsp;</td>
                    <td align=\"center\" colspan=\"2\">NIP. $nip_ppkd</td>
                    </td>
                </tr>           
            </table>";
                // echo $cRet;

            $data['prev']= $cRet;  
            
            $hasil->free_result();
            if ($print==1){
                $this->_mpdf('',$cRet,10,10,10,0,'','','',5);
            } else{
              echo $cRet;
            }   
        }

    //-----------------------
        function cetak_otor_spd(){
            
            $print = $this->uri->segment(2);
            $tnp_no = $this->uri->segment(3);
            $kop = $this->input->post('kop');
            $water= $this->input->post('water');
            //echo ($water);
            $tambah = $this->uri->segment(4) == '0' ? '' : $this->uri->segment(4);
            $bln_awal = 'Januari';
            $lcnospd = $this->input->post('nomor1');
            //echo $lcnospd;
            $nip_ppkd = $this->input->post('nip_ppkd');  
            $nama_ppkd = $this->input->post('nama_ppkd');       
            $jabatan_ppkd = $this->input->post('jabatan_ppkd'); 
            $pangkat_ppkd = $this->input->post('pangkat_ppkd');         
            $csql2 = "select nm_skpd,kd_skpd,tgl_spd,total,bulan_awal,bulan_akhir,jns_beban,kd_bkeluar,klain from trhspd where no_spd = '$lcnospd'  ";
            $hasil1 = $this->db->query($csql2);
            $trh1 = $hasil1->row();
            $ldtgl_spd = $trh1->tgl_spd;
            $klain = $trh1->klain;
            $jmlspdini = number_format(($trh1->total),2,',','.');//number_format(ceil($trh1->total),2,',','.');;
            $biljmlini = $this->tukd_model->terbilang(($trh1->total));
            $lckdskpd = $trh1->kd_skpd;
            $blnini = $this->rka_model->getBulan($trh1->bulan_awal);
            $blnsd = $this->rka_model->getBulan($trh1->bulan_akhir);
            $lcnmskpd = $trh1->nm_skpd;
            $skpd=$trh1->kd_skpd;
            $ljns_beban =$trh1->jns_beban;
            $lcnipbk = $trh1->kd_bkeluar;
            
            if ($lcnipbk<>''){         
                $sqlttd1="SELECT nama as nm FROM ms_ttd WHERE nip='$lcnipbk' ";
                     $sqlttd=$this->db->query($sqlttd1);
                     foreach ($sqlttd->result() as $rowttd)
                    {
                        $nama1= empty($rowttd->nm) ? '' : $rowttd->nm;
                    }
            }
            else{
                        $nama1= '';
            }
     
             $kode= substr($lckdskpd,0,-3);
             $n_status = $this->get_status2($lckdskpd);

             if ($n_status=='nilai_ubah'){
                $no_dpa=$this->rka_model->get_nama($skpd,'no_dpa_ubah','trhrka','kd_skpd');
             }else if($n_status=='nilai_sempurna'){
                $no_dpa=$this->rka_model->get_nama($skpd,'no_dpa_sempurna','trhrka','kd_skpd');
             }else{
                $no_dpa=$this->rka_model->get_nama($skpd,'no_dpa','trhrka','kd_skpd');
             }

             $nospd_cetak=$lcnospd;
            if ($tnp_no=='1'){
            $con_dpn='903/';
            $tahun=$this->session->userdata('pcThang');
            $con_blk_btl='    /BTL/BKAD-B/'.$tahun;
            $con_blk_bl='     /BL/BKAD-B/'.$tahun;      
            if($ljns_beban=='51') {
                    $con_blk_btl='    /BTL/BKAD-B/'.$tahun;
                    $con_blk_bl='     /BL/BKAD-B/'.$tahun; 
                }
         
                ($ljns_beban=='5' || $ljns_beban=='6') ?  $nospd_cetak=$con_dpn."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$con_blk_btl:$nospd_cetak=$con_dpn."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$con_blk_bl;
            }   
     
            if($ljns_beban=='52' || $ljns_beban=='51' || $kode== '4.09.01' || $kode=='1.04.02' || $kode=='2.05.01' || $kode=='4.02.02' || $kode=='4.02.03'){
                $nospd_cetak= str_replace('BKAD','BKAD',$nospd_cetak);
            }else{
                $nospd_cetak= str_replace('BKAD','BPKPD',$nospd_cetak);
            }
            
            // jumlah anggaran
            if($ljns_beban=='52' || $ljns_beban=='51' || $kode=='4.09.01' || $kode=='1.04.02' || $kode=='2.05.01' || $kode=='4.02.02' || $kode=='4.02.03' || $kode=='2.17.01' || $kode=='1.02.01' ){
                $nospd_cetak= str_replace('2020','2020',$nospd_cetak);
            }else{
                $nospd_cetak= str_replace('2020','2019',$nospd_cetak);
            }

            $csql1 = "SELECT SUM($n_status) AS jumlah FROM trdrka WHERE kd_sub_kegiatan IN 
                      (SELECT kd_sub_kegiatan FROM trdspd WHERE no_spd = '$lcnospd')and left(kd_skpd,17) IN (SELECT left(kd_skpd,17) FROM trhspd WHERE no_spd = '$lcnospd')";
               

                      
                      
            $hasil1 = $this->db->query($csql1);
            $trh2 = $hasil1->row();
            $jmldpa = number_format(($trh2->jumlah),2,',','.');//number_format(ceil($trh2->jumlah),2,',','.');
            $bilangdpa=$this->tukd_model->terbilang($trh2->jumlah);
            
            //spd lalu
            $sql = "SELECT isnull(sum(total),0.00) as jm_spd_l from trhspd where no_spd<>'$lcnospd' 
                    and tgl_spd<='$ldtgl_spd' and kd_skpd='$lckdskpd' and jns_beban='$ljns_beban'";
            $hasil = $this->db->query($sql);
            $trh = $hasil->row();

            $jmlspdlalu = number_format(($trh->jm_spd_l),2,',','.');//number_format(ceil($trh->jm_spd_l),2,',','.');
             $bilspdlalu = $this->tukd_model->terbilang($trh->jm_spd_l);
            
            
            $csql = "SELECT * from sclient";
            $hasil = $this->db->query($csql);
            $trh3 = $hasil->row();
            $jmlsisa = number_format(($trh2->jumlah - $trh->jm_spd_l),2,',','.');;
           // $jmlsisa2 = number_format(($trh2->jumlah - $trh->jm_spd_l - $trh1->total),2,',','.'); //number_format(ceil($trh2->jumlah - $trh->jm_spd_l - $trh1->total),2,',','.');
            //$jmlsisa3 = $trh2->jumlah - $trh->jm_spd_l - $trh1->total;
           // $bilsisa = $this->tukd_model->terbilang($jmlsisa3);
            
            $sql_sisa="SELECT SUM(jumlah-jm_spd_l-total) as sisa
                    FROM (select 0 jumlah, 0 jm_spd_l, total total from trhspd where no_spd = '$lcnospd'
                        UNION ALL
                        SELECT SUM($n_status) AS jumlah, 0 jm_spd_l,0 total FROM trdrka WHERE kd_sub_kegiatan IN 
                      (SELECT kd_sub_kegiatan FROM trdspd WHERE no_spd = '$lcnospd') and left(kd_skpd,17) IN (SELECT left(kd_skpd,17) FROM trhspd WHERE no_spd = '$lcnospd')
                      UNION ALL
                        select 0 jumlah, sum(total) as jm_spd_l, 0 total from trhspd where no_spd<>'$lcnospd' 
                    and tgl_spd<='$ldtgl_spd' and kd_skpd='$lckdskpd' and jns_beban='$ljns_beban' ) a
                        ";
            $hasilsisa = $this->db->query($sql_sisa);
            $trhsisa = $hasilsisa->row();
              $jmlsisa3 = $trhsisa->sisa;
              $jmlsisa2 = number_format(($trhsisa->sisa),2,',','.');//number_format(ceil($trhsisa->sisa),2,',','.');
            $bilsisa = $this->tukd_model->terbilang($jmlsisa3);

            $csql = "SELECT top 1 * from trkonfig_spd where tgl_konfig_spd<='$ldtgl_spd' order by tgl_konfig_spd desc ";
            $hasil = $this->db->query($csql);
            $trh4 = $hasil->row();
            
              if($ljns_beban=='5'){
                $njns = 'Belanja';             
              }else if($ljns_beban=='62'){
                $njns = 'Pembiayaan keluar';
              }
            
            
            $xx = 'Bahwa untuk melaksanakan Anggaran '.$njns.' sub kegiatan Tahun Anggaran '.$trh3->thn_ang.' berdasarkan DPA-SKPD dan anggaran kas yang telah ditetapkan, perlu disediakan pendanaan dengan menerbitkan Surat Penyediaan Dana (SPD); ';
            if ($water==1){
                $waters="style=' background-image: url(".base_url()."/image/spd.bmp) ;background-repeat:no-repeat; background-position: 50% 50%;'";

            }else{
                $waters="";
            }
            $cRet ="<body $waters >";


     
            $raimu="PEJABAT PENGELOLA KEUANGAN DAERAH SELAKU BENDAHARA UMUM DAERAH";
            $kepala="<table width='100%' style='font-size:12px' cellpadding='0px'  cellspacing='0' > 
                    <tr>
                        <td width='90%'><center>
                            PEMERINTAH KABUPATEN SANGGAU 
                            <br /> $raimu 
                            <br />NOMOR $nospd_cetak
                            <br /> TENTANG
                            <br /> SURAT PENYEDIAAN DANA ANGGARAN BELANJA DAERAH
                            <br /> TAHUN ANGGARAN $trh3->thn_ang
                            <br />
                            <br />
                            PPKD SELAKU BUD
                            <br />&nbsp;
                        <center></td>

                    </tr>
                                                   
            </table>";
            $plt="";
            

        // }
        $cRet .="$kepala";

            
            $sql = "EXEC otori_spd '$ldtgl_spd'";
            $hasil = $this->db->query($sql);
            $num_row = $hasil->num_rows();
            $font=10;
            if($num_row>10){
                $font = $font-2;
            }
            $cRet .="<table style=\"border-collapse:collapse;font-family: Times New Roman;; font-size:$font px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\">
                    <tr>
                        <td width=\"3%\" align=\"right\" valign=\"top\">&nbsp;</td>
                        <td width=\"13%\" align=\"left\" valign=\"top\" >Menimbang</td>
                        <td width=\"2%\" align=\"right\" valign=\"top\">:</td>
                        <td width=\"81%\" align=\"justify\" colspan=\"2\" rowspan=\"2\" valign=\"top\" >".$xx."</td>
                    </tr>
                    <tr>
                        <td align=\"right\" valign=\"top\">&nbsp;</td>
                        <td align=\"left\" valign=\"top\" >&nbsp;</td>
                        <td align=\"right\" valign=\"top\">&nbsp;</td>
                    </tr>

            ";
            
            $kolom1 = '';
            //$sql = "EXEC otori_spd '$ldtgl_spd'";
            //$hasil = $this->db->query($sql);
            foreach ($hasil->result() as $row){
                $cno1 = $row->no1;
                $cket = $row->ket;
                $ctanda = $row->tanda;
                
                if($cno1=='1'){
                    $kolom1 = 'Mengingat';
                    $kolom2 = ':';
                }else{
                    $kolom1 ='';
                    $kolom2 = '';
                }
                
                if($ctanda=='F'){
                    $ctambah = $no_dpa.' Tahun '.$trh3->thn_ang.';';
                }else{
                    $ctambah = '';
                }
                
                
                $cRet .="<tr>
                            <td align=\"right\" valign=\"top\" > &nbsp;</td>
                            <td align=\"left\" valign=\"top\">$kolom1</td>
                            <td align=\"right\" valign=\"top\" >$kolom2</td>
                            <td width=\"2%\" align=\"right\" valign=\"top\" >$cno1.</td>
                            <td width=\"79%\" align=\"justify\" >$cket $ctambah</td>
                        </tr>";
            }
            $cRet .="</table>";
            
            $cRet .="        
            <table style=\"border-collapse:collapse;font-family: arial;  font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
            
                <tr>
                    <td colspan=\"6\" align=\"center\" valign=\"top\" width=\"100%\"  style=\"font-size:12px\"> 
                        <strong>M E M U T U S K A N :<strong>&nbsp;
                    </td>
                </tr>
                <tr>
                     
                    <td colspan=\"6\" align=\"left\" valign=\"top\" width=\"90%\"  style=\"font-size:12px\">
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$trh4->memutuskan &nbsp;
                    </td>
                </tr>
                <tr>
                    
                    <td width=\"3%\"   style=\"font-size:12px\">1.
                    </td>
                    <td width=\"35%\"  style=\"font-size:12px\">Dasar penyediaan dana:
                    </td>
                    <td  width=\"2%\" style=\"font-size:12px\">
                    </td>
                    <td  width=\"50%\" colspan=\"3\"   style=\"font-size:12px\">
                    </td>
                </tr>
                <tr>
                    
                    <td width=\"3%\"   style=\"font-size:12px\">
                    </td>
                    <td width=\"35%\"  style=\"font-size:12px\">DPA-SKPD
                    </td>
                    <td  width=\"2%\" style=\"font-size:12px\">:
                    </td>
                    <td  width=\"50%\" colspan=\"3\"   style=\"font-size:12px\">$no_dpa
                    </td>
                </tr>
                <tr>
                    
                    <td width=\"3%\"   style=\"font-size:12px\">2.
                    </td>
                    <td width=\"35%\"  style=\"font-size:12px\">Ditujukan kepada SKPD
                    </td>
                    <td  width=\"2%\" style=\"font-size:12px\">:
                    </td>
                    <td  width=\"50%\" colspan=\"3\"   style=\"font-size:12px\">$lcnmskpd
                    </td>
                </tr>
                <tr>
                    
                    <td style=\"font-size:12px\" valign=\"top\">3.
                    </td>
                    <td style=\"font-size:12px\" valign=\"top\">Kepala SKPD 
                    </td>
                    <td  style=\"font-size:12px\" valign=\"top\">:
                    </td>
                    <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">$nama1
                    </td>
                </tr>
                <tr>
                    
                    <td rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">4.
                    </td>
                    <td rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">Jumlah Penyediaan dana
                    </td>
                    <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                    </td>
                    <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlspdini
                    </td>
                </tr>
                <tr>
                    <td  colspan=\"3\" style=\"font-size:12px\"><i>($biljmlini)</i></td>
                </tr>
                <tr>
                    
                    <td style=\"font-size:12px\">5.
                    </td>
                    <td style=\"font-size:12px\">Untuk Kebutuhan
                    </td>
                    <td  style=\"font-size:12px\">:
                    </td>
                    <td  colspan=\"3\"   style=\"font-size:12px\">Bulan $bln_awal s/d $blnsd
                    </td>
                </tr>
                <tr>
                    
                    <td style=\"font-size:12px\">6.
                    </td>
                    <td style=\"font-size:12px\">Ikhtisar penyediaan dana :
                    </td>
                    <td  style=\"font-size:12px\">
                    </td>
                    <td  colspan=\"3\"   style=\"font-size:12px\">
                    </td>
                </tr>

                <tr>
                    
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                    </td>
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">a. Jumlah dana DPA-SKPD
                    </td>
                    <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                    </td>
                    <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmldpa
                    </td>

                </tr>
                <tr>
                    <td  colspan=\"3\" style=\"font-size:12px\"><i>($bilangdpa)</i></td>
                </tr>

                <tr>
                    
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                    </td>
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">b. Akumulasi SPD sebelumnya
                    </td>
                    <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                    </td>
                    <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlspdlalu
                    </td>

                </tr>
                <tr>
                    <td  colspan=\"3\" style=\"font-size:12px\"><i>($bilspdlalu)</i></td>
                </tr>


                <tr>
                    
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                    </td>
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">c. Jumlah dana yang di-SPD-kan saat ini
                    </td>
                    <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                    </td>
                    <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlspdini
                    </td>

                </tr>
                <tr>
                    <td  colspan=\"3\" style=\"font-size:12px\"><i>($biljmlini)</i></td>
                </tr>


                <tr>
                    
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">&nbsp;
                    </td>
                    <td rowspan=\"2\"  style=\"font-size:12px\" valign=\"top\">d. Sisa jumlah dana DPA-SKPD yang belum di-SPD-kan
                    </td>
                    <td  rowspan=\"2\" style=\"font-size:12px\" valign=\"top\">:
                    </td>
                    <td  colspan=\"3\" style=\"font-size:12px\" valign=\"top\">Rp$jmlsisa2
                    </td>

                </tr>
                <tr>
                    <td  colspan=\"3\" style=\"font-size:12px\"><i>($bilsisa)</i></td>
                </tr>
                <tr> 
                    
                    <td style=\"font-size:12px\" align=\"right\" valign=\"top\">7.
                    </td>
                    <td style=\"font-size:12px\" valign=\"top\">Ketentuan-ketentuan lain
                    </td>
                    <td style=\"font-size:12px\" valign=\"top\">:
                    </td>
                    <td  colspan=\"3\" align=\"justify\" style=\"font-size:12px\"> $klain
                    </td>
                </tr>           
                </table>";
                 // CETAKAN TANDA TANGAN by Tox
                $cRet .="
                 <table style=\"border-collapse:collapse;font-weight:none;font-family: arial; font-size:12px\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"2\">
            
             
                <tr >
                    <td width=\"40%\" align=\"right\">&nbsp;</td>
                    <td width=\"60%\"  align=\"center\" colspan=\"2\" >Ditetapkan di Sanggau
                    </td>
                </tr>
                <tr >
                    <td width=\"40%\" align=\"right\">&nbsp;</td>
                    <td width=\"60%\"  align=\"center\" colspan=\"2\" >Pada tanggal ".$this->support->tanggal_format_indonesia($ldtgl_spd)."</td>
                    </td>
                </tr>
                <tr >
                    <td width=\"40%\" align=\"right\">&nbsp;</td>
                    <td width=\"60%\"  align=\"center\" colspan=\"2\" >&nbsp;</td>
                    </td>
                </tr> 
                <tr >
                    <td width=\"40%\" align=\"right\">&nbsp;</td>
                    <td width=\"60%\"  align=\"center\" colspan=\"2\" style=\"text-transform: uppercase;\">PPKD SELAKU BUD,<br />&nbsp;<br>&nbsp;<br>&nbsp;
                    </td>
                </tr>   
            <tr >
                    <td align=\"right\">&nbsp;</td>
                    <td  align=\"center\" colspan=\"2\"><u>$nama_ppkd</u></td>
                    </td>
              
            <tr >
                    <td  align=\"right\">&nbsp;</td>
                    <td align=\"center\" colspan=\"2\">NIP. $nip_ppkd</td>
                    </td>
                </tr> 
                <tr >
                    <td  align=\"left\">Tembusan disampaikan kepada:</td>
                    <td align=\"center\" colspan=\"2\">&nbsp;</td>
                    </td>
                </tr> 
                <tr >
                    <td  align=\"left\">1. Inspektur *)<br >2. Arsip</td>
                    <td align=\"center\" colspan=\"2\">&nbsp;</td>
                    </td>
                </tr> 
                <tr >
                    <td  align=\"left\">*) Coret yang tidak perlu</td>
                    <td align=\"center\" colspan=\"2\">&nbsp;</td>
                    </td>
                </tr>           
            </table>";


            $data['prev']= $cRet;
             
            
            if ($print==1){
                // $this->rka_model->_mpdf_folio('',$cRet,10,10,10,'0');
                $this->rka_model->_mpdf('',$cRet,10,10,10,'0','no','','',$kop);

            } else{
              echo $cRet;
            }

    }

    function spd_pembiayaan(){
            $data['page_title']= 'INPUT SPD PEMBIAYAAN';
            $this->template->set('title', 'INPUT SPD PEMBIAYAAN');   
            $this->template->load('template','anggaran/spd/spd_pembiayaan',$data) ; 
        }

    //Register SPD
    function ctk_spd(){    
        $this->index('0','ms_organisasi','kd_org','nm_org','Register SPD','ctk_spd','');
    }

    function preview_reg_spd(){
        $id = $this->uri->segment(1);
        $cetak = $this->uri->segment(2);  
        $jns = $this->uri->segment(3);        
        $tgl=  $_REQUEST['tgl_ttd'];
        $ttd2 =  $_REQUEST['ttd2'];
        $tgl1 =  $_REQUEST['tgl1'];
        $tgl2 =  $_REQUEST['tgl2'];
        $keu1 = $this->keu1;
            

         $sqldns="SELECT c.kd_urusan as kd_u1,c.nm_urusan as nm_u1,a.kd_urusan as kd_u,b.nm_bidang_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk,d.nm_org,d.kd_org 
                    FROM ms_skpd a INNER JOIN ms_bidang_urusan b ON a.kd_urusan=b.kd_bidang_urusan 
                    INNER JOIN ms_urusan c ON left(a.kd_urusan,1)=c.kd_urusan 
                    inner join ms_organisasi d on left(rtrim(a.kd_skpd),17)=rtrim(d.kd_org)
                    WHERE left(kd_skpd,LEN('$id'))='$id' ";
                 $sqlskpd=$this->db->query($sqldns);
                 foreach ($sqlskpd->result() as $rowdns)
                {
                   
                    $kd_urusan1=$rowdns->kd_u1;                    
                    $nm_urusan1= $rowdns->nm_u1;
                    $kd_urusan=$rowdns->kd_u;                    
                    $nm_urusan= $rowdns->nm_u;
                    $kd_skpd  = $rowdns->kd_sk;
                    $nm_skpd  = $rowdns->nm_sk;
                    $kd_org  = $rowdns->kd_org;
                    $nm_org  = $rowdns->nm_org;      

                    }
        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient where kd_skpd='$keu1'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowsc)
                {
                   
                    //$tgl=$rowsc->tgl_rka;
                   // $tanggal = $this->tanggal_format_indonesia($tgl);
                    $tanggal = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

                    $kab     = $rowsc->kab_kota;
                    $daerah  = $rowsc->daerah.',';
                    $thn     = $rowsc->thn_ang;
                }
                
                  
        $sqlsc="SELECT nama,jabatan FROM ms_ttd where nip='1'";
                 $sqlsclient=$this->db->query($sqlsc);
                 foreach ($sqlsclient->result() as $rowttd)
                {
                    $nama_ttd=$rowttd->nama;
                    $jab_ttd     = $rowttd->jabatan;
                } 


      if ($ttd2 != '' ){         
       $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab, pangkat as pangkat FROM ms_ttd WHERE (REPLACE(nip, ' ', '')='$ttd2')  ";
                 $sqlttd=$this->db->query($sqlttd1);
                 foreach ($sqlttd->result() as $rowttd)
                {
                    $nama_ttd=$rowttd->nm;
                    $jab_ttd     = $rowttd->jab.',';

                }
        }
        else{
                    
                    $nama_ttd='';
                    $jab_ttd     = '';
                    //$daerah = '';
        }

          

       
        $cRet='';
        $cRet1 ='';
        $cRet2 ='';
        $font = 10;
        $font1 = $font-1;                   
        

        $sql1="SELECT a.no_spd,b.tgl_spd,b.kd_skpd,b.nm_skpd,c.nama,sum(a.nilai) [nilai] from trdspd a join trhspd b on a.no_spd=b.no_spd 
        left join ms_ttd c on b.kd_bkeluar=c.nip where b.tgl_spd>='$tgl1' and b.tgl_spd<='$tgl2' and left(b.kd_skpd,len('$id'))='$id' 
        group by a.no_spd,b.tgl_spd,b.kd_skpd,b.nm_skpd,c.nama 
        order by b.tgl_spd,a.no_spd,b.kd_skpd
        ";

 
        $ctkskpd = '';
        $ctkunit = '';
        $ctkunit1 = '';
        $ctkunit2 = '';
        
        if($jns=='unit'){
            $ctkskpd = $kd_org.' / '.$nm_org;
            $ctkunit = $kd_skpd.' / '.$nm_skpd;
            $ctkunit1 = 'UNIT';
            $ctkunit2 = ':';
            
        }else{
            if($jns=='skpd'){
                $ctkskpd = $kd_org.' / '.$nm_org;
               
            }else{
               $sql1="SELECT a.no_spd,b.tgl_spd,b.kd_skpd,b.nm_skpd,c.nama,sum(a.nilai) [nilai] from trdspd a join trhspd b on a.no_spd=b.no_spd 
                    join ms_ttd c on b.kd_bkeluar=c.nip group by a.no_spd,b.tgl_spd,b.kd_skpd,b.nm_skpd,c.nama 
                    order by b.tgl_spd,a.no_spd,b.kd_skpd
                    ";                
            }
        }

        $ctgl1 = $this->rka_model->tanggal_format_indonesia($tgl1);
        $ctgl2 = $this->rka_model->tanggal_format_indonesia($tgl2);
       
        if($tgl<>''){
            $tgl = $this->rka_model->tanggal_format_indonesia($tgl);
        }
        $cRet .="<table style=\"border-collapse:collapse;font-family: arial; font-size:16px;font-weight:bold;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">  
                    <tr><td align=\"center\" colspan=\"4\" >PEMERINTAH KABUPATEN KUBU RAYA<td></tr>
                    <tr>
                        <td align=\"center\" colspan=\"4\" >REGISTER SPD<td>
                    <tr>
                    <tr><td align=\"center\" colspan=\"4\" >TAHUN ANGGARAN ".$this->session->userdata('pcThang')."<td></tr>
                    <tr><td align=\"center\" colspan=\"4\" >&nbsp;<td></tr>
                    <tr><td align=\"center\" colspan=\"4\" >&nbsp;<td></tr>
                  </table>"; 
        if($jns=='all'){
             $cRet .="<table style=\"border-collapse:collapse;font-family: arial; font-size:12px;font-weight:normal;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                        <td width=\"10%\">PADA TANGGAL</td>
                        <td width=\"1%\">:</td>
                        <td align=\"left\">$ctgl1 s/d $ctgl2</td>
                    </tr>

                    <tr><td align=\"center\" colspan=\"4\" >&nbsp;<td></tr>
                  </table>";    

        }else{

             $cRet .="<table style=\"border-collapse:collapse;font-family: arial; font-size:12px;font-weight:normal;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">   
                    <tr>
                       
                       <td width=\"12%\">KODE / NAMA SKPD</td>
                       <td width=\"1%\">:</td>
                       <td width=\"80%\">$ctkskpd</td>
                    </tr> 
                   
                    <tr>
                        <td>$ctkunit1</td>
                        <td>$ctkunit2</td>
                        <td>$ctkunit</td>
                    </tr>
                    <tr>
                        <td>PADA TANGGAL</td>
                        <td>:</td>
                        <td>$ctgl1 s/d $ctgl2</td>
                    </tr>

                    <tr><td align=\"center\" colspan=\"4\" >&nbsp;<td></tr>
                  </table>";    

        }

         

                  
        
        $cRet .= "<table style=\"border-collapse:collapse;font-size:12px;\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"2\">
                     <thead>                       
                        <tr><td width=\"15%\" align=\"center\"><b>No SPD/ Keperluan </b></td>                            
                            <td width=\"10%\" align=\"center\"><b>Tgl SPD</b></td>
                            <td width=\"49%\" align=\"center\"><b>Kode/ Nama SKPD</b></td>
                            <td width=\"12%\" align=\"center\"><b>Nilai (Rp)</b></td>
                            <td width=\"14%\" align=\"center\"><b>Bendahara Pengeluaran</b></td>
                        </tr>
                     </thead>
                     
                       ";
        
        $tot = 0;
        $query = $this->db->query($sql1);
                 //$query = $this->skpd_model->getAllc();
        $num_query = $query->num_rows();   
        foreach ($query->result() as $row){
            $nospd=$row->no_spd;
            $tglspd = $row->tgl_spd;
            $kd_skpd = $row->kd_skpd;
            $nm_skpd = $row->nm_skpd;
            $nm=$row->nama;
            $nilai = $row->nilai;
            $tot = $nilai + $tot;
            $tglspd = date("d-m-Y", strtotime($tglspd));
            $nilai= number_format($nilai,"2",",",".");
             $cRet    .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"left\">$nospd</td>                                     
                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" >$tglspd</td>
                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"left\">$kd_skpd - $nm_skpd</td>
                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$nilai</td>
                 <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">$nm</td>
                 </tr>";

          
        }
        
        $tot= number_format($tot,"2",",",".");
        $cRet    .= " <tr><td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;\" align=\"right\">Jumlah Total</td>                                     
         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;font-weight:bold;font-size:11px;\" align=\"right\">$tot</td>
         <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"></td>
         </tr>";

            
                 $cRet .="<tr>
                                <td width=\"100%\" align=\"center\" colspan=\"5\">
                                <table width=\"100%\" border=\"0\">
                                <tr>
                                <td width=\"70%\" align=\"left\" >&nbsp;<br>&nbsp;
                                <br>&nbsp;
                                &nbsp;<br>
                                &nbsp;<br>
                                &nbsp;<br>
                                &nbsp;<br>  
                                </td>
                                <td width=\"30%\" align=\"center\" >$daerah $tgl                    
                                <br>$jab_ttd
                                <p>&nbsp;</p>
                                <br>
                                <br>
                                <br>
                                <br><b>$nama_ttd</b>
                                </td></tr></table></td>
                             </tr>";
        

        $cRet    .= "</table>";
        $data['prev']= $cRet;
        $data['kd_org']= $id;
        $this->template->set('title', 'CETAK PERDA');    
        //$this->_mpdf('',$cRet,10,10,10,0);
       
    switch($cetak){   
        case 0;
            // echo ("<title>REGISTER SPD $id</title>");
            $this->support->_mpdf('',$cRet,10,10,10,1);
            // echo ($cRet);
            break; 
        case 1;
                $this->support->_mpdf('',$cRet,10,10,10,1);
                break; 
        case 2;        
         header("Cache-Control: no-cache, no-store, must-revalidate");
         header("Content-Type: application/vnd.ms-excel");
         header("Content-Disposition: attachment; filename=Register_SPD.xls");
        $this->load->view('anggaran/rka/perdaIII', $data);
        break;
        }
        
                
    }    

    //-----------------------
    }
    ?>