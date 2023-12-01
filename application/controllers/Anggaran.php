<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Anggaran extends CI_Controller {

public $ppkd = "5.02.0.00.0.00.02.0000";
public $ppkd1 = "5.02.0.00.0.00.02.0000";
public $keu1 = "5.02.0.00.0.00.02.0000";


public $ppkd_lama = "4.02.02";
public $ppkd1_lama = "4.02.02.02";

    function __contruct()
    {   
        parent::__construct();
    }

function tambah_rka()
        {
              $jk   = $this->rka_model->combo_skpd();
            $ry   =  $this->rka_model->combo_giat();
            $cRet = '';
            
            $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" >
                       <tr style='border-bottom-style:hidden;'>                       
                            <td>$jk</td>
                            <td>$ry</td>
                            </tr>
                      ";
             
            $cRet .="</table>";
            $data['prev']= $cRet;
            $data['page_title']= 'Input RKA/DPA ';
            $this->template->set('title', 'Input RKA/DPA ');   
             $sql = "SELECT a.kd_rek6,b.nm_rek6,a.nilai,a.nilai as total from trdrka_new a inner join ms_rek6 b on a.kd_rek6=b.kd_rek6";                   
            
            $query1 = $this->db->query($sql);  
            $results = array();
            $i = 1;
            foreach($query1->result_array() as $resulte)
            { 
                $results[] = array(
                           'id' => $i,
                            'kd_rek5' => $resulte['kd_rek6'],  
                            'nm_rek5' => $resulte['nm_rek6'],  
                            'nilai' => $resulte['nilai'] ,
                            'total' => $resulte['total']                            
                            );
                            $i++;
            }
            $this->template->load('template','anggaran/rka/input_rka',$data) ; 
            $query1->free_result();
       }

function ambil_jns_anggaran(){
            $query1 = $this->db->query("SELECT * from tb_status_anggaran where status_aktif='1'") ;
            $ii     = 0;
            $result = array();
            foreach ($query1->result_array() as $resulte) {
                
                $result[] = array(
                        'id'        => '$ii',
                        'nama' => $resulte['nama'],
                        'kolom' => $resulte['kolom'],
                        'kode' => $resulte['kode']
                        //'kd_ang' => $resulte['kd_ang']
                    );
                    $ii++;    
            }
            
            echo json_encode($result) ;
        }

        function pengesahan_pendapatan()
        {
            $data['page_title']= 'Pengesahan Pendapatan';
            $this->template->set('title', 'Pengesahan Pendapatan');   
            $this->template->load('template','anggaran/dpa/pengesahan_pendapatan',$data) ; 
        }

function status_anggaran(){
            $skpd           =  $this->input->post('kdskpd');
            $jns_anggaran   =  $this->input->post('j_anggaran');
            
            $kolom="";
            $sqljnsang="SELECT * FROM tb_status_anggaran_new where kd_ang='$jns_anggaran'";
             $sqljsang=$this->db->query($sqljnsang);
             foreach ($sqljsang->result() as $row_jenis)
            {                   
                $kode      = $row_jenis->kode;
            }

            if($kode=='susun' || $kode=='murni'){
                $status_ang = 'status';
            }else{
                $status_ang = 'status_'.$kode;
            }

            $sql = "SELECT a.kd_skpd,a.nm_skpd,$status_ang as status FROM  ms_skpd a LEFT JOIN trhrka b ON 
                    a.kd_skpd=b.kd_skpd WHERE a.kd_skpd = '$skpd' ";
            $query1 = $this->db->query($sql);  
            
            $test = $query1->num_rows();
            
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $result = array(
                            'id' => $ii,        
                            'kd_skpd'   => $resulte['kd_skpd'],
                            'nm_skpd'   => $resulte['nm_skpd'],
                            'status'    => $resulte['status']
                            );
                            $ii++;
            }
            

            
            
            echo json_encode($result);
            $query1->free_result();   
        }
function ambil_pagu($cskpd='',$kd_ang=''){

    $sqljnsang="SELECT * FROM tb_status_anggaran_new where kd_ang='$kd_ang'";
    $sqljsang=$this->db->query($sqljnsang);
     foreach ($sqljsang->result() as $row_jenis)
        {                   
            $kua    = $row_jenis->kua;
        }
        

    $query1 = $this->db->query("SELECT a.$kua as nilai_kua, 
    (SELECT SUM(nilai) FROM trdrka_gabungan WHERE LEFT(kd_rek6,1)='5' AND kd_skpd = a.kd_skpd and jns_ang='$kd_ang') as nilai_ang FROM ms_skpd a where a.kd_skpd='$cskpd' ");  
    $result = array();
    $ii = 0;
    foreach($query1->result_array() as $resulte)
    { 
    $result[] = array(
        'id' => $ii,        
        'nilai_kua'     => number_format($resulte['nilai_kua'],2,'.',','),                      
        'nilai_anggaran' => number_format($resulte['nilai_ang'],2,'.',',')
    );
    $ii++;
    }

    echo json_encode($result);
    $query1->free_result();  
}

function get_status_spd($skpd){
    $n_status = '';
    
    $sql = "SELECT TOP 1 * from trhrka where kd_skpd ='$skpd' and status='1' order by tgl_dpa DESC";

    $q_trhrka = $this->db->query($sql);
    $num_rows = $q_trhrka->num_rows();
    
    foreach ($q_trhrka->result() as $r_trhrka){
         $n_status = $r_trhrka->jns_ang;                   
    }    
    return $n_status;                         
}

function get_nsumber($vvsdana=''){

    $query1 = $this->db->query("SELECT a.$kua as nilai_kua, 
    (SELECT SUM(nilai) FROM trdrka_gabungan WHERE LEFT(kd_rek6,1)='5' AND kd_skpd = a.kd_skpd and jns_ang='$kd_ang') as nilai_ang FROM ms_skpd a where a.kd_skpd='$cskpd' ");  
    $result = array();
    $ii = 0;
    foreach($query1->result_array() as $resulte)
    { 
    $result[] = array(
        'id' => $ii,        
        'nilai_kua'     => number_format($resulte['nilai_kua'],2,'.',','),                      
        'nilai_anggaran' => number_format($resulte['nilai_ang'],2,'.',',')
    );
    $ii++;
    }

    echo json_encode($result);
    $query1->free_result();  
}


function standarhargasipd(){
        $cari = $this->input->post('q');
        $kdrek = $this->input->post('kdrek');
        $sql=$this->db->query("
                            SELECT top 50 * from (
                            SELECT '1' urut, * from ms_standar_harga where kd_barang in 
                            (select kd_barang from trdpo  where kd_rek6='$kdrek') 
                            UNION all
                            select  '2' urut, * from ms_standar_harga
                            ) okei
                            WHERE  
                            kd_barang+uraian_barang+uraian_kelompok_barang+spesifikasi like '%$cari%'
                            ORDER BY urut, id_standar_harga
                            ");
        $ii = 0;
        $result = array();
        foreach($sql->result() as $ok){
                 $result[] = array(
                            'id' => $ii,        
                            'id_standar_harga'          => $ok->id_standar_harga,
                            'kode_standar_harga'        => $ok->kd_barang,
                            'nama_standar_harga'        => $ok->uraian_barang,
                            'uraian'                    => $ok->uraian_barang,
                            'kode_kel_standar_harga'    => $ok->kd_kelompok_barang,
                            'nama_kel_standar_harga'    => $ok->uraian_kelompok_barang,
                            'harga'                     => number_format($ok->harga_satuan,'2','.',','),
                            'harga_preview'             => number_format($ok->harga_satuan,'2',',','.'),
                            'satuan'                    => $ok->satuan,
                            'spek'                      => $ok->spesifikasi                                             
                        );
                        $ii++;
        }
        echo json_encode($result);
    }


function rka_rinci_lama($skpd='',$kegiatan='',$rekening='',$jns_anggaran='') 
{
        $norka  = $skpd.'.'.$kegiatan.'.'.$rekening;        
        $result = $this->rka_model->rka_rinci($skpd,$kegiatan,$rekening,$norka,$jns_anggaran);
        echo json_encode($result);
}

function rka_rinci_($skpd='',$kegiatan='',$rekening='', $jns_anggaran='')
{       $norka  = $skpd.'.'.$kegiatan.'.'.$rekening;
        $sqljnsang="SELECT * FROM tb_status_anggaran where kolom='$jns_anggaran'";
         $sqljsang=$this->db->query($sqljnsang);
         foreach ($sqljsang->result() as $row_jenis)
        {                   
            $volume     = $row_jenis->volume;
            $volume1    = $row_jenis->volume1;
            $volume2    = $row_jenis->volume2;
            $volume3    = $row_jenis->volume3;
            $volume4    = $row_jenis->volume4;
            
            $satuan     = $row_jenis->satuan;
            $satuan1    = $row_jenis->satuan1;
            $satuan2    = $row_jenis->satuan2;
            $satuan3    = $row_jenis->satuan3;
            $satuan4    = $row_jenis->satuan4;

            $koefisien  = $row_jenis->koefisien;

            $harga      = $row_jenis->harga;
            $total      = $row_jenis->total;
            $spesifikasi= $row_jenis->spesifikasi;
            $pajak      = $row_jenis->pajak;
            
        }

        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');

        $sql = "SELECT count(*) as totals from trdpo_new  where no_trdrka = '$norka'" ;
        $query1 = $this->db->query($sql);
        $totals = $query1->row();
        $result["total"] = $totals->totals; 
        $query1->free_result();  
        
        $sql    = "SELECT TOP $rows id,header,sub_header,kd_barang,uraian,
                   $spesifikasi as spesifikasi,
                   $koefisien as koefisien,
                   $volume as volume,
                   $volume1 as volume1,
                   $volume2 as volume2,
                   $volume3 as volume3,
                   $volume4 as volume4,
                   $satuan as satuan,
                   $satuan1 as satuan1,
                   $satuan2 as satuan2,
                   $satuan3 as satuan3,
                   $satuan4 as satuan4,

                   $harga as harga,
                   $total as total,
                   $pajak as pajak,
                   id_standar_harga

                   from trdpo_new where no_trdrka='$norka' 
                   and id not in (SELECT TOP $offset id
                                   from trdpo_new where no_trdrka='$norka' order by header,sub_header
                                   )
                   order by header,sub_header";                   
        
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id'                => $resulte['id'],   
                        'header'            => $resulte['header'],  
                        'sub_header'        => $resulte['sub_header'],
                        'id_standar_harga'  => $resulte['id_standar_harga'],  
                        'kd_barang'         => $resulte['kd_barang'],  
                        'uraian'            => $resulte['uraian'],
                        'spesifikasi'       => $resulte['spesifikasi'], 
                        'koefisien'         => $resulte['koefisien'],

                        'volume'            => doubleval($resulte['volume']),
                        'volume1'           => doubleval($resulte['volume1']),  
                        'volume2'           => doubleval($resulte['volume2']),  
                        'volume3'           => doubleval($resulte['volume3']),
                        'volume4'           => doubleval($resulte['volume4']),

                        'satuan'            => $resulte['satuan'],  
                        'satuan1'           => $resulte['satuan1'],  
                        'satuan2'           => $resulte['satuan2'],  
                        'satuan3'           => $resulte['satuan3'],
                        'satuan4'           => $resulte['satuan4'],

                        'pajak'             => $resulte['pajak'],  
                        
                        'harga'             => number_format($resulte['harga'],"2",".",","),  
                        'total'             => number_format($resulte['total'],"2",".",",")
                        );
                        $ii++;
        }

        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
}


function rka_rinci($skpd='',$kegiatan='',$rekening='',$jns_anggaran=''){ 

      $norka  = $skpd.'.'.$kegiatan.'.'.$rekening;
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        
        $sql = "SELECT count(*) as totals from trdpo_gabungan  where no_trdrka = '$norka' and jns_ang='$jns_anggaran'" ;
        $query1 = $this->db->query($sql);
        $totals = $query1->row();
        $result["total"] = $totals->totals; 
        $query1->free_result();        
        
        $sql = "SELECT TOP $rows id,no_po,header,sub_header,kd_barang,uraian,
                   spesifikasi as spesifikasi,
                   koefisien as koefisien,
                   volume as volume,
                   volume1 as volume1,
                   volume2 as volume2,
                   volume3 as volume3,
                   volume4 as volume4,
                   satuan as satuan,
                   satuan1 as satuan1,
                   satuan2 as satuan2,
                   satuan3 as satuan3,
                   satuan4 as satuan4,

                   harga as harga,
                   total as total,
                   pajak as pajak,
                   id_standar_harga

                   from trdpo_gabungan where no_trdrka='$norka'  and jns_ang='$jns_anggaran'
                   and id not in (SELECT TOP $offset id
                                   from trdpo_new where no_trdrka='$norka' and jns_ang='$jns_anggaran' order by header,sub_header,no_po
                                   )
                   order by header,sub_header,no_po";
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $row[] = array(
                        'id'                => $resulte['id'],
                        'no_po'             => $resulte['no_po'],   
                        'header'            => $resulte['header'],  
                        'sub_header'        => $resulte['sub_header'],
                        'id_standar_harga'  => $resulte['id_standar_harga'],  
                        'kd_barang'         => $resulte['kd_barang'],  
                        'uraian'            => $resulte['uraian'],
                        'spesifikasi'       => $resulte['spesifikasi'], 
                        'koefisien'         => $resulte['koefisien'],

                        'volume'            => doubleval($resulte['volume']),
                        'volume1'           => doubleval($resulte['volume1']),  
                        'volume2'           => doubleval($resulte['volume2']),  
                        'volume3'           => doubleval($resulte['volume3']),
                        'volume4'           => doubleval($resulte['volume4']),

                        'satuan'            => $resulte['satuan'],  
                        'satuan1'           => $resulte['satuan1'],  
                        'satuan2'           => $resulte['satuan2'],  
                        'satuan3'           => $resulte['satuan3'],
                        'satuan4'           => $resulte['satuan4'],

                        'pajak'             => $resulte['pajak'],  
                        
                        'harga'             => number_format($resulte['harga'],"2",".",","),  
                        'total'             => number_format($resulte['total'],"2",".",",")                                                                                                                  
                        );
                        $ii++;
        }
        $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result();
}


function simpan_po(){
    $this->db->trans_start();
        $username           = $this->session->userdata('pcNama');
        $jang               = $this->input->post('jang');
        $header             = $this->input->post('header');
        $sub_header         = $this->input->post('sub_header');
        $skpd               = $this->input->post('skpd');
        $kd_sub_kegiatan    = $this->input->post('kd_sub_kegiatan');      
        $kd_rek6            = $this->input->post('kd_rek');        
        $no_trdrka          = $this->input->post('no_trdrka');
        $no_po1              = $this->input->post('no_po_sisip');

        $idstandarget       = $this->input->post('idstandar');
        $kd_barang          = $this->input->post('kd_barang');      
        $uraian             = $this->input->post('uraian');      
        
        $volume             = $this->input->post('volume');      
        $volume1            = $this->input->post('volume1');      
        $volume2            = $this->input->post('volume2');      
        $volume3            = $this->input->post('volume3');      
        $volume4            = $this->input->post('volume4');      

        $satuan             = $this->input->post('satuan');
        $satuan1            = $this->input->post('satuan1');
        $satuan2            = $this->input->post('satuan2');
        $satuan3            = $this->input->post('satuan3');      
        $satuan4            = $this->input->post('satuan4');      

        $pajak              = $this->input->post('pajak');
        $harga              = $this->input->post('harga');
        $total              = $this->input->post('total');      
        
        $koefisien          = $this->input->post('koefisien');
        $spesifikasi        = $this->input->post('spesifikasi');
        $last_update        =  date('Y-m-d H:i:s');

        if ($no_po1==''){
            $sql9 = "SELECT max(no_po)+2 as no_po from trdpo_gabungan WHERE no_trdrka='$no_trdrka'";
            $exe9 = $this->db->query($sql9);
            foreach ($exe9->result() as $a9) {
                $no_po      = $a9->no_po;
            }
        }else{
            $no_po=$no_po1;
        }
        

        
        if ($idstandarget=="0" || $idstandarget ==0){
              $idstandar="null";
            }else{
            $idstandar="'$idstandarget'";
            }

        if ($volume=="--"){
              $volume="null";
            }else{
            $volume="$volume";
            }
        if ($volume1=="--"){
              $volume1="null";
            }else{
            $volume1="'$volume1'";
            }
        if ($volume2=="--"){
              $volume2="null";
            }else{
            $volume2="'$volume2'";
            }
        if ($volume3=="--"){
              $volume3="null";
            }else{
            $volume3="'$volume3'";
            }
        if ($volume4=="--"){
              $volume4="null";
            }else{
            $volume4="'$volume4'";
            }
        
            $sql ="INSERT into trdpo_gabungan(jns_ang,no_trdrka,no_po,kd_skpd,kd_sub_kegiatan,kd_rek6,header,sub_header,id_standar_harga,kd_barang,uraian,
            spesifikasi,
            satuan,
            volume,
            koefisien,
            volume1,volume2,volume3,volume4,
            satuan1,satuan2,satuan3,satuan4,
            harga,
            pajak,
            total,
            username,last_update)
                
                values ('$jang','$no_trdrka','$no_po','$skpd','$kd_sub_kegiatan','$kd_rek6','$header','$sub_header',$idstandar,'$kd_barang','$uraian',
                '$spesifikasi',
                '$satuan',
                '$volume',
                '$koefisien',
                 $volume1,$volume2,$volume3,$volume4,
                '$satuan1','$satuan2','$satuan3','$satuan4',
                '$harga',
                '$pajak',
                '$total',
                '$username','$last_update')";


        $hasil=$this->db->query("SELECT count(*) as jumlah FROM trdpo_gabungan where no_trdrka='$no_trdrka' and kd_barang='$kd_barang' and header='$header' and sub_header='$sub_header' and jns_ang='$jang'");
            foreach ($hasil->result_array() as $row){
                $jumlahtrskpd=$row['jumlah']; 
            }

        if ($jumlahtrskpd>0){
            $response='1';
            
        }else{
            
            $query_trdpo = $this->db->query($sql);
            if ($query_trdpo>0){
                $this->db->query("UPDATE a SET
                            a.no_po=b.urutan
                            from trdpo_gabungan a inner join (
                            SELECT 
                               ROW_NUMBER() OVER (
                                ORDER BY no_po
                               )*2 urutan, no_po,
                                    no_trdrka
                            FROM 
                               trdpo_gabungan WHERE no_trdrka='$no_trdrka' and jns_ang='$jang'
                            ) b on a.no_po=b.no_po and a.no_trdrka=b.no_trdrka
                            WHERE a.no_trdrka='$no_trdrka' and jns_ang='$jang'");

                $query = $this->db->query(" UPDATE trdrka_gabungan set 
                        nilai=( select sum(total) as jum from trdpo_gabungan where no_trdrka='$no_trdrka' and jns_ang='$jang') where no_trdrka='$no_trdrka' and jns_ang='$jang'"); 

                $response='2';
            }else{
                $response='0';
            }
        }

        $this->db->trans_complete();
        if($response=='1'){
            echo "1";
        }else if($response=='2'){
            echo "2";
        }else{
            echo "0";
        }
        

    }



    function update_po(){
        $username           = $this->session->userdata('pcNama');
        $jang               = $this->input->post('jang');
        $idpo               = $this->input->post('idpo');
        $header             = $this->input->post('header');
        $sub_header         = $this->input->post('sub_header');
        $skpd               = $this->input->post('skpd');
        $kd_sub_kegiatan    = $this->input->post('kd_sub_kegiatan');      
        $kd_rek6            = $this->input->post('kd_rek');        
        $no_trdrka          = $this->input->post('no_trdrka');

        $idstandar          = $this->input->post('idstandar');
        $kd_barang          = $this->input->post('kd_barang');      
        $uraian             = $this->input->post('uraian');      
        
        $volume             = $this->input->post('volume');      
        $volume1            = $this->input->post('volume1');      
        $volume2            = $this->input->post('volume2');      
        $volume3            = $this->input->post('volume3');      
        $volume4            = $this->input->post('volume4');      

        $satuan             = $this->input->post('satuan');
        $satuan1            = $this->input->post('satuan1');
        $satuan2            = $this->input->post('satuan2');
        $satuan3            = $this->input->post('satuan3');      
        $satuan4            = $this->input->post('satuan4');      

        $pajak              = $this->input->post('pajak');
        $harga              = $this->input->post('harga');
        $total              = $this->input->post('total');      
        
        $koefisien          = $this->input->post('koefisien');
        $spesifikasi        = $this->input->post('spesifikasi');
        $last_update        =  date('Y-m-d H:i:s');


    $sqljnsang="SELECT * FROM tb_status_anggaran where kolom='$jang'";
    $sqljsang=$this->db->query($sqljnsang);
     foreach ($sqljsang->result() as $row_jenis)
        {                   
            $kspesifikasi    = $row_jenis->spesifikasi;
            $kvolume         = $row_jenis->volume;
            $kvolume1        = $row_jenis->volume1;
            $kvolume2        = $row_jenis->volume2;
            $kvolume3        = $row_jenis->volume3;
            $kvolume4        = $row_jenis->volume4;
            $ksatuan         = $row_jenis->satuan;
            $ksatuan1        = $row_jenis->satuan1;
            $ksatuan2        = $row_jenis->satuan2;
            $ksatuan3        = $row_jenis->satuan3;
            $ksatuan4        = $row_jenis->satuan4;
            $kharga          = $row_jenis->harga;
            $ktotal          = $row_jenis->total;
            $kkoefisien      = $row_jenis->koefisien;
            $kpajak          = $row_jenis->pajak;
            $knilai          = $row_jenis->kolom;
        
        }
        
       
            $sql="UPDATE trdpo_new set 
            uraian          ='$uraian',
            $kspesifikasi     ='$spesifikasi',

            $kvolume     = $volume,
            $kvolume1    ='$volume1',
            $kvolume2    ='$volume2',
            $kvolume3    ='$volume3',
            $kvolume4    ='$volume4',

            $ksatuan     ='$satuan',
            $ksatuan1    ='$satuan1',
            $ksatuan2    ='$satuan2',
            $ksatuan3    ='$satuan3',
            $ksatuan4    ='$satuan4',
            
            $kharga      ='$harga',                        
        
            $ktotal      = $total,
            $kkoefisien  ='$koefisien',
            $kpajak      ='$pajak',
            
            username    = '$username',
            last_update ='$last_update'
            where id='$idpo' and no_trdrka='$no_trdrka'  ";
            $query_trdpo = $this->db->query($sql);
            if ($query_trdpo>0){

                $query = $this->db->query(" UPDATE trdrka_new set 
                        $knilai=( select sum($ktotal) as jum from trdpo_new where kd_rek6='$kd_rek6' and kd_sub_kegiatan='$kd_sub_kegiatan' and kd_skpd='$skpd' ) where kd_rek6='$kd_rek6' and kd_sub_kegiatan='$kegiatan' and kd_skpd='$skpd' "); 
                echo "2";
            }else{
                echo "0";
            }   

    }



function ambil_sumber_dana(){

            // $skpd     = $this->session->userdata('kdskpd');
            $kd_skpd    = $this->input->post('kdskpd');
            $jang       = $this->input->post('jang');
            $lccr       = '';
            $query1     = $this->db->query("SELECT a.kd_sumberdana,a.nm_sumberdana,(nilai+nilaisilpa) -
                                        (select 
                                        (select isnull(sum(nsumber1),0)as k from trdrka_gabungan 
                                        where sumber1=a.kd_sumberdana and left(kd_rek6,1)= '5' and jns_ang='$jang')+
                                        (select isnull(sum(nsumber2),0)as m from trdrka_gabungan 
                                        where sumber2=a.kd_sumberdana and left(kd_rek6,1) = '5' and jns_ang='$jang')+
                                        (select isnull(sum(nsumber3),0)as n from trdrka_gabungan 
                                        where sumber3=a.kd_sumberdana and left(kd_rek6,1) = '5' and jns_ang='$jang')+
                                        (select isnull(sum(nsumber4),0)as v from trdrka_gabungan 
                                        where sumber4=a.kd_sumberdana and left(kd_rek6,1) = '5' and jns_ang='$jang') )as sisa
                                        from hsumber_dana a inner join dsumber_dana b on a.kd_sumberdana =b.kd_sumberdana where kd_skpd in ('$kd_skpd','all')") ;
            
            $ii     = 0;
            $result = array();
            foreach ($query1->result_array() as $resulte) {
                
                $result[] = array(
                    'id'        => '$ii',
                    'kd_sdana'  => $resulte['kd_sumberdana'],
                    'nm_sdana'  => $resulte['nm_sumberdana'],
                    'nilai'     => $resulte['sisa']
                    );
                    $ii++;    
            }
            
            echo json_encode($result) ;
        }
function ambil_sumber_dana2(){

            // $skpd     = $this->session->userdata('kdskpd');
            $kd_skpd    = $this->input->post('kdskpd');
            $jang       = $this->input->post('jang');
            $vvsdana    = $this->input->post('vvsdana');
            $lccr       = '';
            $query1     = $this->db->query("SELECT a.kd_sumberdana,a.nm_sumberdana,(nilai+nilaisilpa) -
                                        (select 
                                        (select isnull(sum(nsumber1),0)as k from trdrka_gabungan 
                                        where sumber1=a.kd_sumberdana and left(kd_rek6,1)= '5' and jns_ang='$jang')+
                                        (select isnull(sum(nsumber2),0)as m from trdrka_gabungan 
                                        where sumber2=a.kd_sumberdana and left(kd_rek6,1) = '5' and jns_ang='$jang')+
                                        (select isnull(sum(nsumber3),0)as n from trdrka_gabungan 
                                        where sumber3=a.kd_sumberdana and left(kd_rek6,1) = '5' and jns_ang='$jang')+
                                        (select isnull(sum(nsumber4),0)as v from trdrka_gabungan 
                                        where sumber4=a.kd_sumberdana and left(kd_rek6,1) = '5' and jns_ang='$jang') )as sisa
                                        from hsumber_dana a inner join dsumber_dana b on a.kd_sumberdana =b.kd_sumberdana where kd_skpd in ('$kd_skpd','all') and a.kd_sumberdana='$vvsdana'") ;
            
            $ii     = 0;
            $result = array();
            foreach ($query1->result_array() as $resulte) {
                
                $result[] = array(
                    'id'        => '$ii',
                    'kd_sdana'  => $resulte['kd_sumberdana'],
                    'nm_sdana'  => $resulte['nm_sumberdana'],
                    'nilai'     => $resulte['sisa']
                    );
                    $ii++;    
            }
            
            echo json_encode($result) ;
        }

function load_total_trans(){ /*untuk spp dan penagihan konsep kota*/ 
       $kdskpd      = $this->input->post('kode');
       $kegiatan    = $this->input->post('giat');
       $rek       = $this->input->post('kdrek6');
    
        $sql = "SELECT SUM(nilai) total FROM 
                                    (
                                    -- transaksi UP/GU
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout c
                                    LEFT JOIN trhtransout d ON c.no_bukti = d.no_bukti
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan = '$kegiatan'
                                    AND d.kd_skpd = '$kdskpd'
                                    AND c.kd_rek6 = '$rek'
                                    AND d.jns_spp in ('1') 
                                    
                                    UNION ALL
                                    -- transaksi UP/GU CMS BANK Belum Validasi
                                    SELECT SUM (isnull(c.nilai,0)) as nilai
                                    FROM trdtransout_cmsbank c
                                    LEFT JOIN trhtransout_cmsbank d ON c.no_voucher = d.no_voucher
                                    AND c.kd_skpd = d.kd_skpd
                                    WHERE c.kd_sub_kegiatan ='$kegiatan'
                                    AND d.kd_skpd = '$kdskpd'
                                    AND c.kd_rek6='$rek'
                                    AND d.jns_spp in ('1') 
                                    AND (d.status_validasi='0' OR d.status_validasi is null)
                                    
                                    UNION ALL
                                    -- transaksi SPP SELAIN UP/GU
                                    SELECT SUM(isnull(x.nilai,0)) as nilai FROM trdspp x
                                    INNER JOIN trhspp y 
                                    ON x.no_spp=y.no_spp AND x.kd_skpd=y.kd_skpd
                                    WHERE x.kd_sub_kegiatan = '$kegiatan'
                                    AND x.kd_skpd = '$kdskpd'
                                    AND x.kd_rek6 = '$rek'
                                    AND y.jns_spp IN ('3','4','5','6')
                                    AND (sp2d_batal IS NULL or sp2d_batal ='' or sp2d_batal='0') 
                                    
                                    UNION ALL
                                    -- Penagihan yang belum jadi SPP
                                    SELECT SUM(isnull(nilai,0)) as nilai FROM trdtagih t 
                                    INNER JOIN trhtagih u 
                                    ON t.no_bukti=u.no_bukti AND t.kd_skpd=u.kd_skpd
                                    WHERE t.kd_sub_kegiatan ='$kegiatan' 
                                    AND t.kd_rek ='$rek' 
                                    AND u.kd_skpd = '$kdskpd' 
                                    AND u.no_bukti 
                                    NOT IN (select no_tagih FROM trhspp WHERE kd_skpd='$kdskpd')
                                    )r";    
        
        
        
        $query1 = $this->db->query($sql);                  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {                               
            $result[] = array(
                        'id' => $ii,        
                        'total' => number_format($resulte['total'],2,'.',','),
                        );
                        $ii++;
        }
           
        echo json_encode($result);
        $query1->free_result();
    }

function select_rka($kegiatan='',$skpd='', $jns_anggaran='') {
    // $skpd = $this->session->userdata('kdskpd');
    
    $kolom="";
    $sqljnsang="SELECT * FROM tb_status_anggaran_new where kd_ang='$jns_anggaran'";
     $sqljsang=$this->db->query($sqljnsang);
     foreach ($sqljsang->result() as $row_jenis)
    {                   
        $k_lalu     = $row_jenis->kd_ang_lalu;
        
    }

// if ($kolom==''){
    $sql = "SELECT a.kd_rek6,b.nm_rek6,
                    a.nilai as kol1,
                    (select nilai from trdrka_gabungan where  kd_sub_kegiatan=a.kd_sub_kegiatan and kd_skpd=a.kd_skpd and kd_rek6=a.kd_rek6 and jns_ang='$k_lalu') as kol1_lalu,
                    a.sumber1 as kol2,
                    (select nm_sumber_dana1 from sumber_dana d where a.sumber1=d.kd_sumber_dana1) as kol25,
                    a.nsumber1 as kol3,
                    a.sumber2 as kol4,
                    (select nm_sumber_dana1 from sumber_dana d where a.sumber2=d.kd_sumber_dana1) as kol45,
                    a.nsumber2 as kol5,
                    a.sumber3 as kol6,
                    (select nm_sumber_dana1 from sumber_dana d where a.sumber3=d.kd_sumber_dana1) as kol65,
                    a.nsumber3 as kol7,
                    a.sumber4 as kol8,
                    (select nm_sumber_dana1 from sumber_dana d where a.sumber4=d.kd_sumber_dana1) as kol85,
                    a.nsumber4 as kol9 
                    from trdrka_gabungan a 
                    inner join ms_rek6 b on a.kd_rek6=b.kd_rek6 
                    inner join trskpd c on a.kd_sub_kegiatan=c.kd_sub_kegiatan and a.kd_skpd=c.kd_skpd
                    where a.kd_sub_kegiatan='$kegiatan' and a.kd_skpd='$skpd' and jns_ang='$jns_anggaran'
                    ";
// }else{
//     $sql = "SELECT a.kd_rek6,b.nm_rek6,
//                     a.$kolom as kol1,
//                     a.$k_lalu as kol1_lalu,
//                     a.$k_sumber1 as kol2,
//                     (select nm_sumber_dana1 from sumber_dana d where a.$k_sumber1=d.kd_sumber_dana1) as kol25,
//                     a.$k_nsumber1 as kol3,
//                     a.$k_sumber2 as kol4,
//                     (select nm_sumber_dana1 from sumber_dana d where a.$k_sumber2=d.kd_sumber_dana1) as kol45,
//                     a.$k_nsumber2 as kol5,
//                     a.$k_sumber3 as kol6,
//                     (select nm_sumber_dana1 from sumber_dana d where a.$k_sumber3=d.kd_sumber_dana1) as kol65,
//                     a.$k_nsumber3 as kol7,
//                     a.$k_sumber4 as kol8,
//                     (select nm_sumber_dana1 from sumber_dana d where a.$k_sumber4=d.kd_sumber_dana1) as kol85,
//                     a.$k_nsumber4 as kol9 
//                     from trdrka_new a 
//                     inner join ms_rek6 b on a.kd_rek6=b.kd_rek6 
//                     inner join trskpd c on a.kd_sub_kegiatan=c.kd_sub_kegiatan and a.kd_skpd=c.kd_skpd
//                     where a.kd_sub_kegiatan='$kegiatan' and a.kd_skpd='$skpd'
//                     GROUP BY 
//                     a.kd_rek6,b.nm_rek6,
//                     a.$kolom,
//                     a.$k_lalu,
//                     a.$k_sumber1,a.$k_nsumber1,
//                     a.$k_sumber2,a.$k_nsumber2,
//                     a.$k_sumber3,a.$k_nsumber3,
//                     a.$k_sumber4,a.$k_nsumber4
//                     order by a.kd_rek6 ";
// }
                       
            
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id'        => $ii,        
                            'kd_rek5'   => $resulte['kd_rek6'],  
                            'nm_rek5'   => $resulte['nm_rek6'],  
                            'nilai'     => number_format($resulte['kol1'],"2",".",","),
                            'nilai_lalu'=> number_format($resulte['kol1_lalu'],"2",".",","),
                            
                            'sumber'    => $resulte['kol2'],
                            'sumber2'   => $resulte['kol4'],
                            'sumber3'   => $resulte['kol6'],
                            'sumber4'   => $resulte['kol8'],

                            'nmsumber1' => $resulte['kol25'],
                            'nmsumber2' => $resulte['kol45'],
                            'nmsumber3' => $resulte['kol65'],
                            'nmsumber4' => $resulte['kol85'], 

                            'nilai_sumber'  => number_format($resulte['kol3'],"2",".",","), 
                            'nilai_sumber2' => number_format($resulte['kol5'],"2",".",","), 
                            'nilai_sumber3' => number_format($resulte['kol7'],"2",".",","),
                            'nilai_sumber4' => number_format($resulte['kol9'],"2",".",",")                                

                            );
                            $ii++;
            }
               
               echo json_encode($result);
                $query1->free_result();
        }




  // ANGPEN
  function tambah_rka_pendapatan()
  {
      $data['page_title']= 'Input RKA Target Pendapatan';
      $this->template->set('title', 'Input RKA Target Pendapatan');   
      $this->template->load('template','anggaran/rka/pendapatan/tambah_rka_pend_rinci',$data) ;
  }



function pgiat_pend($skpd='') {
        
        $lccr = $this->input->post('q');
        $sql  = "SELECT a.kd_sub_kegiatan,b.nm_sub_kegiatan FROM trdrka a INNER JOIN ms_sub_kegiatan b ON a.kd_sub_kegiatan=b.kd_sub_kegiatan
        inner join trskpd c on a.kd_skpd=c.kd_skpd and a.kd_sub_kegiatan=c.kd_sub_kegiatan
                 where b.jns_sub_kegiatan='4' and a.kd_skpd='$skpd' and ( upper(a.kd_sub_kegiatan) like upper('%$lccr%') or upper(a.nm_sub_kegiatan) like upper('%$lccr%') )
                 group by a.kd_sub_kegiatan,b.nm_sub_kegiatan
                 ";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'id' => $ii,        
                        'kd_sub_kegiatan'  => $resulte['kd_sub_kegiatan'],  
                        'nm_sub_kegiatan'  => $resulte['nm_sub_kegiatan'],
                        'jns_sub_kegiatan' => '4'
                        );
                        $ii++;
        }
        echo json_encode($result);
           
    }

function skpd_pendapatan($kd_jang)
    {
        $id  = $this->session->userdata('pcUser'); 
        $usernm      = $this->session->userdata('pcNama');
        $lccr = $this->input->post('q');

        $sql = "SELECT a.kd_skpd,a.nm_skpd, 
                    (select status from trhrka where kd_skpd=a.kd_skpd and jns_ang='$kd_jang') as status
                    FROM ms_skpd a join trhrka b on a.kd_skpd=b.kd_skpd where right(a.kd_skpd,4)='0000' and (upper(a.kd_skpd) 
                    like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%') )";
        
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id'                => $ii, 
                'kd_skpd'           => $resulte['kd_skpd'], 
                'nm_skpd'           => $resulte['nm_skpd'],
                'statu'             => $resulte['status']
            );
            $ii++;
        }

        echo json_encode($result);
    }

function skpd_pendapatan2($kdskpd="",$kd_jang="")
    {
        $id         = $this->session->userdata('pcUser'); 
        $usernm     = $this->session->userdata('pcNama');
        $lccr       = $this->input->post('q');

            $sql = "SELECT a.kd_skpd,a.nm_skpd,b.status,
            (select status from trhrka_pend where kd_skpd=a.kd_skpd and jns_ang='$kd_jang') as status
             FROM ms_skpd a join trhrka_pend b on a.kd_skpd=b.kd_skpd where left(a.kd_skpd,17)=left('$kdskpd',17) and (upper(a.kd_skpd) 
                    like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%') )";
        
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array(
                'id' => $ii, 
                'kd_skpd' => $resulte['kd_skpd'], 
                'nm_skpd' => $resulte['nm_skpd'],
                'statu' => $resulte['status'] );
            $ii++;
        }

        echo json_encode($result);
    }



function ambil_rekening_pendapatan($kdskpd='',$kegiatan='',$kd_jang='') {
            
            $lccr    = $this->input->post('q');
            $sql = "SELECT kd_rek6,nm_rek6,nilai as nilai,sumber1 as k_sumber1 from trdrka where kd_skpd='$kdskpd' and kd_sub_kegiatan='$kegiatan' and jns_ang='$kd_jang'";
            $query1 = $this->db->query($sql); 
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
                $result[] = array(
                            'id'        => $ii,        
                            'kd_rek6'   => $resulte['kd_rek6'],  
                            'nm_rek6'   => $resulte['nm_rek6'],
                            'nilai'     => $resulte['nilai'],
                            'k_sumber1' => $resulte['k_sumber1']
                            );
                            $ii++;
            }
            echo json_encode($result);
        }

function ambil_sdana($sumber1=''){

            $skpd     = $this->session->userdata('kdskpd');
            $kd_skpd  = $this->input->post('kdskpd');
            $lccr  = '';//$this->input->post('q');
            // $query1 = $this->db->query("select * from ms_dana where upper(kd_sdana) like upper('%$lccr%') or upper(nm_sdana) like upper('%$lccr%') ") ;

            if ($sumber1==''){
                $query1 = $this->db->query("SELECT kd_sumber_dana1,nm_sumber_dana1
                                        from sumber_dana where (upper(kd_sumber_dana1) like upper('%%') or upper(nm_sumber_dana1) like upper('%%'))") ;
            }else{
                $query1 = $this->db->query("SELECT kd_sumber_dana1,nm_sumber_dana1
                                        from sumber_dana where kd_sumber_dana1='$sumber1' and (upper(kd_sumber_dana1) like upper('%%') or upper(nm_sumber_dana1) like upper('%%'))") ;    
            }
            
            //$query1 = $this->db->query("select kd_sdana, nm_sdana from ms_dana") ;
            
            
            $ii     = 0;
            $result = array();
            foreach ($query1->result_array() as $resulte) {
                
                $result[] = array(
                    'id'        => '$ii',
                    'kd_sdana'  => $resulte['kd_sumber_dana1'],
                    'nm_sdana'  => $resulte['nm_sumber_dana1']
                    );
                    $ii++;    
            }
            
            echo json_encode($result) ;
        }

function select_rka_pend_akt_pend($kegiatan='',$skpd='',$kd_rek6='',$kd_jang='') {

 

        $sql = "SELECT a.kd_rek6,b.nm_rek6,a.nilai,a.sumber,a.sumber2,a.sumber3,a.sumber4 from 
                trdrka_pend a inner join ms_rek6 b on a.kd_rek6=b.kd_rek6 where kd_sub_kegiatan='$kegiatan' 
                AND a.kd_skpd='$skpd' and a.kd_rek6='$kd_rek6' and jns_ang='$kd_jang' order by a.kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id'        => $ii,        
                        'kd_rek6'   => $resulte['kd_rek6'],  
                        'nm_rek6'   => $resulte['nm_rek6'],  
                        'nilai'     => number_format($resulte['nilai'],"2",".",","),
                        'sumber'    => $resulte['sumber'],
                        'sumber2'   => $resulte['sumber2'],
                        'sumber3'   => $resulte['sumber3'],
                        'sumber4'   => $resulte['sumber4']                                
                        );
                        $ii++;
        }
           
           echo json_encode($result);
            $query1->free_result();
    }


    function select_pengesahan($kd_jang='') {
        
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        
        $sql = "SELECT count(*) as total from trhrka_pend  where jns_ang = '$kd_jang' " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        $sql = "Select TOP $rows *,(select nm_skpd from ms_skpd where kd_skpd=trhrka_pend.kd_skpd)as nm_skpd from trhrka_pend where jns_ang='$kd_jang' and kd_skpd+jns_ang not in
        (Select TOP $offset kd_skpd+jns_ang from trhrka_pend where jns_ang='$kd_jang' order by kd_skpd) order by kd_skpd ";
        $query1 = $this->db->query($sql);       
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {   

            if ($resulte['status']=='1'){
                $statusdpa = '<span class="badge badge-success">Sudah disahkan</span>';
            }else{
                $statusdpa = '<span class="badge badge-danger">Belum disahkan</span>';
            }
           
            $row[] = array(
                        'id' => $ii,        
                        'kd_skpd'   => $resulte['kd_skpd'],  
                        'nm_skpd'   => $resulte['nm_skpd'],  
                        'status'    => $resulte['status'],
                        'ket'       => $statusdpa,
                        'tgl_dpa'   => $resulte['tgl_dpa'],
                        'no_dpa'    => $resulte['no_dpa']                                                                     
                        );
                        $ii++;
        }
        $result["rows"] = $row;           
        echo json_encode($result);
        $query1->free_result();        
    }

   

    

function hitung_pagu($kdskpd='',$kdskpd2='',$giat='',$kdrek6='',$kd_jang=''){
        
        $sql = "SELECT nilai as pagu, 
                (select sum(nilai) from trdrka_pend where left(kd_skpd,17)=left('$kdskpd2',17) and kd_sub_kegiatan='$giat' and kd_rek6='$kdrek6' and jns_ang='$kd_jang') as p_pend 
                from trdrka where kd_skpd='$kdskpd' and kd_sub_kegiatan='$giat' and kd_rek6='$kdrek6'";
        $query1 = $this->db->query($sql);  
        
        $test = $query1->num_rows();
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'pagu'      => $resulte['pagu'],
                        'p_pend'    => $resulte['p_pend'],
                        'sisa'      => $resulte['pagu']-$resulte['p_pend']
                        );
                        $ii++;
        }
        

        
        
        echo json_encode($result);
        $query1->free_result();   
    }

function simpan_anggaran_pendapatan(){
        $usernm      = $this->session->userdata('pcNama');
        $kdskpd = $this->input->post('kd_skpd');
        $kdskpd_asal = $this->input->post('kd_skpd_asal');
        $kdkegi = $this->input->post('kd_kegiatan');
        $kdrek  = $this->input->post('kd_rek6');
        $nilai  = $this->input->post('nilai');
        $sdana1 = $this->input->post('dana1');
        $kd_jang= $this->input->post('kd_jang');
        $kdgab = $kdskpd.'.'.$kdkegi;
        $kd_giat = substr($kdkegi,0,12);
        $kd_prog = substr($kdkegi,0,7);
        $kd_bdg = substr($kdskpd,0,4);

        $sql9 = "SELECT * from tb_status_anggaran WHERE kode='$kd_jang'";
            $exe9 = $this->db->query($sql9);
            foreach ($exe9->result() as $a9) {
                $kd_ang      = $a9->kd_ang;
            }
                
        $nmskpd = $this->rka_model->get_nama($kdskpd,'nm_skpd','ms_skpd','kd_skpd');
        $nmkegi = "Pendapatan";
        $nmrek  = $this->rka_model->get_nama($kdrek,'nm_rek6','ms_rek6','kd_rek6');
        $last_update =  date('Y-m-d H:i:s');

        $hasil=$this->db->query("SELECT count(*) as jumlah FROM trskpd_pend where kd_skpd='$kdskpd' and kd_sub_kegiatan='$kdkegi'");
            foreach ($hasil->result_array() as $row){
            $jumlahtrskpd=$row['jumlah']; 
            }
        
        $notrdrka  = $kdskpd.'.'.$kdkegi.'.'.$kdrek ;
        $query_del = $this->db->query("delete from trdrka_pend where kd_skpd='$kdskpd' and kd_sub_kegiatan='$kdkegi' and kd_rek6='$kdrek' and jns_ang='$kd_jang'");
        $query_ins = $this->db->query("insert into trdrka_pend(no_trdrka,kd_skpd_asal,kd_skpd,nm_skpd,kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6,nilai,sumber,nsumber,username,last_update,jns_ang) values('$notrdrka','$kdskpd_asal','$kdskpd','$nmskpd','$kdkegi','$nmkegi','$kdrek','$nmrek','$nilai','$sdana1','$nilai','$usernm','$last_update','$kd_jang')");        
        
        if ( $query_ins > 0 and $query_del > 0 ) {

            if($jumlahtrskpd<1){
                $query_trskpd = $this->db->query("INSERT into trskpd_pend(kd_gabungan,kd_kegiatan,kd_program,kd_bidang_urusan,kd_skpd,nm_skpd,nm_kegiatan,jns_sub_kegiatan,nm_program,username,last_update,kd_sub_kegiatan,nm_sub_kegiatan) values('$kdgab','$kd_giat','$kd_prog','$kd_bdg','$kdskpd','$nmskpd','Pendapatan','4','NON PROGRAM','$usernm','$last_update','$kdkegi','$nmkegi')"); 
                if($query_trskpd>0){
                    echo "1" ;
                }else{
                    echo "0" ;
                }

            }else{
                echo "1" ;
            }          
            
        } else {
            echo "0" ;
        }
        
    }


    function simpan_pengesahan_angpen(){
        $usernm         = $this->session->userdata('pcNama');
        $kdskpd         = $this->input->post('skpd');
        $status         = $this->input->post('status');
        $nodpa          = $this->input->post('nodpa');
        $tgldpa         = $this->input->post('tgldpa');
        $kd_jang        = $this->input->post('kd_jang');
        $last_update    =  date('Y-m-d H:i:s');

      
        $query = $this->db->query("UPDATE trhrka_pend SET status='$status',
                                    no_dpa='$nodpa',
                                    tgl_dpa='$tgldpa',
                                    username='$usernm',
                                    last_update='$last_update'
                                    where kd_skpd='$kdskpd' and jns_ang='$kd_jang'");
        
        if($query>0){
            echo "1" ;
        }else{
            echo "0" ;
        }
        
    }


function thapus_pend_akt($skpd='',$kegiatan='',$rek='',$kd_jang='') {
        $notrdrka=$skpd.'.'.$kegiatan.'.'.$rek;

        $query = $this->db->query(" DELETE from trdrka_pend where kd_skpd='$skpd' and kd_sub_kegiatan='$kegiatan' and kd_rek6='$rek' and jns_ang='$kd_jang' ");
        
        $query = $this->db->query(" UPDATE trskpd_pend set total=( select sum(nilai) as jum from trdrka_pend where kd_sub_kegiatan='$kegiatan' and jns_ang='$kd_jang' and kd_skpd='$skpd' ) where kd_sub_kegiatan='$kegiatan' and kd_skpd='$skpd' "); 
        // $this->select_rka_pend_akt($kegiatan,$skpd);

         if ( $query > 0 ) {
            echo "1" ;
        } else {
            echo "0" ;
        }
    }

   // -----------------

  //Pengesahan DPA
        function pengesahan_dpa()
    {
        $data['page_title']= 'Pengesahan DPA & DPPA';
        $this->template->set('title', 'Pengesahan DPA & DPPA');   
        $this->template->load('template','anggaran/dpa/pengesahan_dpa',$data) ; 
    }

    function load_pengesahan_dpa(){
        $result = array();
        $row = array();
        $id  = $this->session->userdata('pcUser');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ="WHERE kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id')";
        if ($kriteria <> ''){                               
            $where="where (upper(kd_skpd) like upper('%$kriteria%') or no_dpa like'%$kriteria%') and kd_skpd IN 
                    (SELECT kd_skpd FROM user_bud WHERE user_id='$id')";            
        }
        
        $sql = "SELECT count(*) as tot from trhrka $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT * from trhrka $where order by kd_skpd ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' => $ii,
                        'kd_skpd' => $resulte['kd_skpd'],        
                        
                        'status' => $resulte['status'],
                        'no_dpa' => $resulte['no_dpa'],
                        'tgl_dpa' => $resulte['tgl_dpa'],
                        
                        'status_sempurna' => $resulte['status_sempurna'],
                        'status_sempurna' => $resulte['status_sempurna'],
                        'no_dpa_sempurna' => $resulte['no_dpa_sempurna'],
                        'tgl_dpa_sempurna' => $resulte['tgl_dpa_sempurna'],

                        'status_sempurna2' => $resulte['status_sempurna2'],
                        'no_dpa_sempurna2' => $resulte['no_dpa_sempurna2'],
                        'tgl_dpa_sempurna2' => $resulte['tgl_dpa_sempurna2'],

                        'status_sempurna3' => $resulte['status_sempurna3'],
                        'no_dpa_sempurna3' => $resulte['no_dpa_sempurna3'],
                        'tgl_dpa_sempurna3' => $resulte['tgl_dpa_sempurna3'],
                        

                        'status_sempurna4' => $resulte['status_sempurna4'],
                        'no_dpa_sempurna4' => $resulte['no_dpa_sempurna4'],
                        'tgl_dpa_sempurna4' => $resulte['tgl_dpa_sempurna4'],
                        

                        'status_sempurna5' => $resulte['status_sempurna5'],
                        'no_dpa_sempurna5' => $resulte['no_dpa_sempurna5'],
                        'tgl_dpa_sempurna5' => $resulte['tgl_dpa_sempurna5'],
                        

                        
                        'status_ubah' => $resulte['status_ubah'],
                        'no_dpa_ubah' => $resulte['no_dpa_ubah'],
                        'tgl_dpa_ubah' => $resulte['tgl_dpa_ubah'],

                        'status_ubah2' => $resulte['status_ubah2'],
                        'no_dpa_ubah2' => $resulte['no_dpa_ubah2'],
                        'tgl_dpa_ubah2' => $resulte['tgl_dpa_ubah2'],
                        
                        
                        'status_rancang' => $resulte['status_rancang'],
                        'tgl_dpa_rancang' => $resulte['tgl_dpa_rancang']
                        
                        
                        
                        
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);     
    }

function load_nilai_kua($cskpd=''){
    $query1 = $this->db->query("SELECT a.nilai_kua, 
        (SELECT SUM(nilai) FROM trdrka WHERE LEFT(kd_rek6,1)='5' /*and left(kd_rek6,4)<>'5101'*/ AND kd_skpd = a.kd_skpd) as nilai_ang,
        (SELECT SUM(nilai_sempurna) FROM trdrka WHERE LEFT(kd_rek6,1)='5' /*and left(kd_rek6,4)<>'5101'*/ AND kd_skpd = a.kd_skpd) as nilai_angg_sempurna,
        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek6,1)='5' /*and left(kd_rek6,4)<>'5101'*/ AND kd_skpd = a.kd_skpd) as nilai_angg_ubah
        FROM ms_skpd a where a.kd_skpd='$cskpd'");  
           $result = array();
           $ii = 0;
           foreach($query1->result_array() as $resulte)
           { 
               $result[] = array(
                           'id' => $ii,        
                           //'rekspm' => number_format($resulte['rekspm'],2,'.',','),
                           'nilai' => number_format($resulte['nilai_kua'],2,'.',','),                      
                           'kua_terpakai' => number_format($resulte['nilai_ang'],2,'.',','),
                           'kua_terpakai_sempurna' => number_format($resulte['nilai_angg_sempurna'],2,'.',','),                       
                           'kua_terpakai_ubah' => number_format($resulte['nilai_angg_ubah'],2,'.',',')  
                           );
                           $ii++;
           }
          
              echo json_encode($result);
              $query1->free_result();  
   }

function simpan_pengesahan(){
        $kdskpd         = $this->input->post('kdskpd');
        $sdpa           = $this->input->post('stdpa');
        $sdppa          = $this->input->post('stdppa');
        $sdpasempurna   = $this->input->post('stsempurna');
        $sdpasempurna2  = $this->input->post('stsempurna2');
        $sdpasempurna3  = $this->input->post('stsempurna3');
        $sdpasempurna4  = $this->input->post('stsempurna4');
        $sdpasempurna5  = $this->input->post('stsempurna5');
        $sdppa2         = $this->input->post('stdppa');

        $nodpa          = $this->input->post('no');
        $nodppa         = $this->input->post('no2');
        $nosempurna     = $this->input->post('no3');
        $nosempurna2    = $this->input->post('no4');
        $nosempurna3    = $this->input->post('no5');
        $nosempurna4    = $this->input->post('no6');
        $nosempurna5    = $this->input->post('no7');
        $nodppa2         = $this->input->post('no8');

        $tanggal1 = $this->input->post('tgl');
        $tanggal2 = $this->input->post('tgl2');
        $tanggal3 = $this->input->post('tgl3');
        $tanggal4 = $this->input->post('tgl4');
        $tanggal5 = $this->input->post('tgl5');
        $tanggal6 = $this->input->post('tgl6');
        $tanggal7 = $this->input->post('tgl7');
        $tanggal8 = $this->input->post('tgl8');

        
        
        $sql2 = "UPDATE trhrka  set 
            status='$sdpa',
            status_sempurna ='$sdpasempurna',
            status_sempurna2='$sdpasempurna2',
            status_sempurna3='$sdpasempurna3',
            status_sempurna4='$sdpasempurna4',
            status_sempurna5='$sdpasempurna5',
            status_ubah     ='$sdppa',
            status_ubah2    ='$sdppa2',
            
            no_dpa          ='$nodpa',
            no_dpa_ubah     ='$nodppa',
            no_dpa_sempurna ='$nosempurna',
            no_dpa_sempurna2='$nosempurna2',
            no_dpa_sempurna3='$nosempurna3',
            no_dpa_sempurna4='$nosempurna4',
            no_dpa_sempurna5='$nosempurna5',
            no_dpa_ubah2    ='$nodppa2',
            
            tgl_dpa          ='$tanggal1',
            tgl_dpa_ubah     ='$tanggal2',
            tgl_dpa_sempurna ='$tanggal3',
            tgl_dpa_sempurna2='$tanggal4',
            tgl_dpa_sempurna3='$tanggal5',
            tgl_dpa_sempurna4='$tanggal6',
            tgl_dpa_sempurna5='$tanggal7',
            tgl_dpa_ubah2     ='$tanggal8'

             where kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql2);             

        $sql2 = "update [user] set kunci='1' where bidang='4' and kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql2);

        if (!($asg)){
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
        }else{
            $msg = array('pesan'=>'1');
            echo json_encode($msg);
        }             
     }

     function load_pengesahan_pendapatan(){
        $result = array();
        $row = array();
        $id  = $this->session->userdata('pcUser');
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;
        $kriteria = '';
        $kriteria = $this->input->post('cari');
        $where ="WHERE kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id')";
        if ($kriteria <> ''){                               
            $where="where (upper(kd_skpd) like upper('%$kriteria%') or no_dpa like'%$kriteria%') and kd_skpd IN 
                    (SELECT kd_skpd FROM user_bud WHERE user_id='$id')";            
        }
        
        $sql = "SELECT count(*) as tot from trhrka_pend $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        
        $sql = "SELECT * from trhrka_pend $where order by kd_skpd ";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $row[] = array(
                        'id' => $ii,
                        'kd_skpd' => $resulte['kd_skpd'],        
                        
                        'status' => $resulte['status'],
                        'no_dpa' => $resulte['no_dpa'],
                        'tgl_dpa' => $resulte['tgl_dpa'],
                        
                        'status_sempurna' => $resulte['status_sempurna'],
                        'status_sempurna' => $resulte['status_sempurna'],
                        'no_dpa_sempurna' => $resulte['no_dpa_sempurna'],
                        'tgl_dpa_sempurna' => $resulte['tgl_dpa_sempurna'],

                        'status_sempurna2' => $resulte['status_sempurna2'],
                        'no_dpa_sempurna2' => $resulte['no_dpa_sempurna2'],
                        'tgl_dpa_sempurna2' => $resulte['tgl_dpa_sempurna2'],

                        'status_sempurna3' => $resulte['status_sempurna3'],
                        'no_dpa_sempurna3' => $resulte['no_dpa_sempurna3'],
                        'tgl_dpa_sempurna3' => $resulte['tgl_dpa_sempurna3'],
                        

                        'status_sempurna4' => $resulte['status_sempurna4'],
                        'no_dpa_sempurna4' => $resulte['no_dpa_sempurna4'],
                        'tgl_dpa_sempurna4' => $resulte['tgl_dpa_sempurna4'],
                        

                        'status_sempurna5' => $resulte['status_sempurna5'],
                        'no_dpa_sempurna5' => $resulte['no_dpa_sempurna5'],
                        'tgl_dpa_sempurna5' => $resulte['tgl_dpa_sempurna5'],
                        

                        
                        'status_ubah' => $resulte['status_ubah'],
                        'no_dpa_ubah' => $resulte['no_dpa_ubah'],
                        'tgl_dpa_ubah' => $resulte['tgl_dpa_ubah'],

                        'status_ubah2' => $resulte['status_ubah2'],
                        'no_dpa_ubah2' => $resulte['no_dpa_ubah2'],
                        'tgl_dpa_ubah2' => $resulte['tgl_dpa_ubah2'],
                        
                        
                        'status_rancang' => $resulte['status_rancang'],
                        'tgl_dpa_rancang' => $resulte['tgl_dpa_rancang']
                        
                        
                        
                        
                        
                        );
                        $ii++;
        }
           
        $result["total"] = $total->tot;
        $result["rows"] = $row; 
        echo json_encode($result);     
    }
    function simpan_pengesahan_pendapatan(){
        $kdskpd         = $this->input->post('kdskpd');
        $sdpa           = $this->input->post('stdpa');
        $sdppa          = $this->input->post('stdppa');
        $sdpasempurna   = $this->input->post('stsempurna');
        $sdpasempurna2  = $this->input->post('stsempurna2');
        $sdpasempurna3  = $this->input->post('stsempurna3');
        $sdpasempurna4  = $this->input->post('stsempurna4');
        $sdpasempurna5  = $this->input->post('stsempurna5');
        $sdppa2         = $this->input->post('stdppa');

        $nodpa          = $this->input->post('no');
        $nodppa         = $this->input->post('no2');
        $nosempurna     = $this->input->post('no3');
        $nosempurna2    = $this->input->post('no4');
        $nosempurna3    = $this->input->post('no5');
        $nosempurna4    = $this->input->post('no6');
        $nosempurna5    = $this->input->post('no7');
        $nodppa2         = $this->input->post('no8');

        $tanggal1 = $this->input->post('tgl');
        $tanggal2 = $this->input->post('tgl2');
        $tanggal3 = $this->input->post('tgl3');
        $tanggal4 = $this->input->post('tgl4');
        $tanggal5 = $this->input->post('tgl5');
        $tanggal6 = $this->input->post('tgl6');
        $tanggal7 = $this->input->post('tgl7');
        $tanggal8 = $this->input->post('tgl8');

        
        
        $sql2 = "UPDATE trhrka_pend  set 
            status='$sdpa',
            status_sempurna ='$sdpasempurna',
            status_sempurna2='$sdpasempurna2',
            status_sempurna3='$sdpasempurna3',
            status_sempurna4='$sdpasempurna4',
            status_sempurna5='$sdpasempurna5',
            status_ubah     ='$sdppa',
            status_ubah2    ='$sdppa2',
            
            no_dpa          ='$nodpa',
            no_dpa_ubah     ='$nodppa',
            no_dpa_sempurna ='$nosempurna',
            no_dpa_sempurna2='$nosempurna2',
            no_dpa_sempurna3='$nosempurna3',
            no_dpa_sempurna4='$nosempurna4',
            no_dpa_sempurna5='$nosempurna5',
            no_dpa_ubah2    ='$nodppa2',
            
            tgl_dpa          ='$tanggal1',
            tgl_dpa_ubah     ='$tanggal2',
            tgl_dpa_sempurna ='$tanggal3',
            tgl_dpa_sempurna2='$tanggal4',
            tgl_dpa_sempurna3='$tanggal5',
            tgl_dpa_sempurna4='$tanggal6',
            tgl_dpa_sempurna5='$tanggal7',
            tgl_dpa_ubah2     ='$tanggal8'

             where kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql2);             

        $sql2 = "update [user] set kunci='1' where bidang='4' and kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql2);

        if (!($asg)){
            $msg = array('pesan'=>'0');
            echo json_encode($msg);
        }else{
            $msg = array('pesan'=>'1');
            echo json_encode($msg);
        }             
     }

     // Kontrol Rekening

function ld_giat_keg_renja_aktif($skpd='') { 
        $lccr   = $this->input->post('q');
        $sql    = "SELECT a.kd_sub_kegiatan,a.nm_sub_kegiatan FROM trskpd a where a.kd_skpd='$skpd'
                   and a.status_sub_kegiatan='1' group by a.kd_sub_kegiatan,a.nm_sub_kegiatan order by a.kd_sub_kegiatan";  
    
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan'],  
                        'nm_kegiatan' => $resulte['nm_sub_kegiatan']
                        );
                        $ii++;
        }
        echo json_encode($result);
    }

function ld_rek_renja_nonaktif($skpd='',$subgiat='') { 
        $lccr   = $this->input->post('q');
        
        $sql    = "SELECT kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6 FROM trdrka a where kd_skpd='$skpd' and kd_sub_kegiatan='$subgiat' and status_aktif='1' group by kd_sub_kegiatan,nm_sub_kegiatan,kd_rek6,nm_rek6 order by kd_sub_kegiatan,kd_rek6";  
    
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'kd_kegiatan'   => $resulte['kd_sub_kegiatan'],  
                        'nm_kegiatan'   => $resulte['nm_sub_kegiatan'],
                        'kd_rek6'       => $resulte['kd_rek6'],  
                        'nm_rek6'       => $resulte['nm_rek6']
                        );
                        $ii++;
        }
        echo json_encode($result);
    }

function select_rek_aktif_renja($skpd='',$kode='') {    
        $sql = "SELECT a.kd_rek6 as kd_rek6,a.nm_rek6 from trdrka a 
                where a.kd_skpd='$skpd' and a.kd_sub_kegiatan='$kode' and a.status_aktif<>'1' group by a.kd_rek6,a.nm_rek6 order by a.kd_rek6";
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        {            
            $result[] = array(
                        'id' => $ii,
                        'kd_rek6' => $resulte['kd_rek6'],                         
                        'nm_rek6' => $resulte['nm_rek6']              
                        );
                        $ii++;
        }
        echo json_encode($result);
    }
function psimpan_rek_aktif($skpd='',$keg='',$rek='') {          
       $query = $this->db->query("update trdrka set status_aktif='1' where kd_skpd='$skpd' and kd_sub_kegiatan='$keg' and kd_rek6='$rek'");
       
       $this->select_rek_aktif_renja($skpd,$keg);
    }

function f_rek_nonaktif($skpd='',$kegiatan='',$rek='') {
        $query = $this->db->query("update trdrka set status_aktif='1' where kd_skpd='$skpd' and kd_sub_kegiatan='$kegiatan' and kd_rek6='$rek'");
        $this->select_rek_aktif_renja($skpd,$kegiatan);
    }

function rek_renja_aktif_semua($skpd='',$kegiatan='') {          
       $query = $this->db->query("update trdrka set status_aktif='0' where kd_skpd='$skpd' and kd_sub_kegiatan='$kegiatan'");
       $this->select_rek_aktif_renja($skpd,$kegiatan);
    }

   function f_rek_nonaktif_semua($skpd='',$kegiatan='') {
        $query = $this->db->query("update trdrka set status_aktif='1' where kd_skpd='$skpd' and kd_sub_kegiatan='$kegiatan'");
        $this->select_rek_aktif_renja($skpd,$kegiatan);
    }

    function rek_renja_aktif_semua_all($skpd='') {          
       $query = $this->db->query("update trdrka set status_aktif='0' where kd_skpd='$skpd'");
       
    }

   function f_rek_nonaktif_semua_all($skpd='') {
        $query = $this->db->query("update trdrka set status_aktif='1' where kd_skpd='$skpd'");
       
    }

function load_sum_rek_rinci(){
            $kdskpd         = $this->input->post('skpd');
            $subkegiatan       = $this->input->post('keg');
            $rek            = $this->input->post('rek');
            $jns_anggaran   = $this->input->post('jns_ang');
            $norka          = $kdskpd.'.'.$subkegiatan.'.'.$rek;

            $query1 = $this->db->query("SELECT sum(total) as rektotal_rinci from trdpo_gabungan where no_trdrka='$norka' and jns_ang='$jns_anggaran'");  
            $result = array();
            $ii = 0;
            foreach($query1->result_array() as $resulte)
            { 
               
                $result[] = array(
                            'id' => $ii,        
                            'rektotal_rinci' => number_format($resulte['rektotal_rinci'],"2",".",",")
                            );
                            $ii++;
            }
               
               //return $result;
               echo json_encode($result);   
        }

// CEK KUA
function cek_kua_all()
    {
        $data['page_title']= 'CETAK KUA';
        $this->template->set('title', 'Cetak KUA');   
        $this->template->load('template','anggaran/rka/cek_kua_all',$data) ; 
    }

function preview_cek_kua_semua(){
        
      $cetak = $this->uri->segment(3);
      $jns = $this->uri->segment(4);
      $tgl_cetak=date('d-m-Y H:i:s');
      $tanggal = $this->support->tanggal_format_indonesia($tgl_cetak);
      $thn = $this->session->userdata('pcThang');

      $sqljnsang="SELECT * FROM tb_status_anggaran where kd_ang='$jns'";
             $sqljsang=$this->db->query($sqljnsang);
             foreach ($sqljsang->result() as $row_jenis)
            {                   
                $kua      = $row_jenis->kua;
                $kolom      = $row_jenis->kolom;
                $judul      = $row_jenis->nama;
            }

    
    
         
     $thn = '2021';
     $kab='PEMERINTAH PROVINSI KALIMANTAN BARAT';
     $daerah='Pontianak';
     
        $cRet='';
        
    
                  
        $cRet .="<table style=\"border-collapse:collapse;font-family: arial; font-size:20px;font-weight:bold;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"0\">
                    <tr>
                    <td width=\"15%\" rowspan=\"4\" align=\"right\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;border-right: none;\" >
                    <img src=\"".base_url()."/image/logoHP.bmp\"  width=\"75\" height=\"100\" /></td>
                         <td  width=\"85%\" align=\"center\" style=\"font-size:23px;font-weight:bold;border-bottom: none;border-left: none; \" >$kab</td>
                         
                    </tr>
                    <tr>
                         <td align=\"center\" style=\"vertical-align:top;border-top: none;border-bottom: none;border-left: none;\" >ANGGARAN BELANJA LANGSUNG BERDASARKAN KUA PPAS </td>
                    </tr>
                    
                    <tr>
                         <td align=\"center\" style=\"vertical-align:top;border-top: none;border-bottom: none;border-left: none;\">TAHUN ANGGARAN $thn</td>
                    </tr>
                    <tr>
                         <td align=\"center\" style=\"vertical-align:top;border-bottom: none;border-top: none;border-left: none;\" > &nbsp;</td>
                    </tr>
                  </table>";    
                  
        
        
        $cRet .= "<table style=\"border-collapse:collapse;font-family: arial; font-size:14px;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr>
                            <td  width=\"5%\" align=\"center\"><b>NO</b></td>
                            <td  width=\"11%\" align=\"center\"><b>KODE SKPD</b></td>                            
                            <td  width=\"27%\" align=\"center\"><b>NAMA</b></td>
                            <td  width=\"20%\" align=\"center\"><b>KUA</b></td>
                            <td  width=\"20%\" align=\"center\"><b>ANGGARAN $judul</b></td>
                            <td  width=\"17%\" align=\"center\"><b>SISA</b></td>
                        </tr>
                        <tr>
                            <td align=\"center\">1</td>
                            <td align=\"center\">2</td>
                            <td align=\"center\">3</td>
                            <td align=\"center\">4</td>
                            <td align=\"center\">5</td> 
                            <td align=\"center\">6</td>                                                     
                         </tr>
                     </thead>
                      <tfoot>
                        <tr>
                            <td style=\"font-size:10px;border-bottom: none;border-right: none;border-left: none;\" colspan=\"10\"><i>DATA Dicetak tanggal $tgl_cetak</i></td>

                         </tr>
                     </tfoot>";
                     
                $sql1="SELECT *, (SELECT $kua FROM ms_skpd WHERE kd_skpd=z.kode) nilai_kua
                            FROM (
                            SELECT ROW_NUMBER() OVER (ORDER BY y.kd_skpd) AS no,
                                   RTRIM(y.kd_skpd)kode, 
                                   RTRIM(y.nm_skpd)nama, 
                                   SUM(CASE WHEN left(kd_rek6,1)='5' THEN $kolom ELSE 0 END) AS ang_sudah
                            FROM trdrka x RIGHT JOIN ms_skpd y ON x.kd_skpd=y.kd_skpd 
                            WHERE y.kd_skpd IN (SELECT kd_skpd FROM trskpd) AND y.kd_skpd<>''
                            GROUP BY y.kd_skpd, y.nm_skpd ) z
                            ORDER BY kode";
                            
                $tot_kua=0;
                $tot_ang_sudah=0;
                $query = $this->db->query($sql1);
                 
                foreach ($query->result() as $row)
                {
                    $nomor=$row->no;
                    $kode=$row->kode;
                    $nama=$row->nama;
                    $nilai_kua=$row->nilai_kua;
                    $ang_sudah=$row->ang_sudah;
                    $sisa=$nilai_kua-$ang_sudah;
                    
                    $tot_kua=$tot_kua+$nilai_kua;
                    $tot_ang_sudah=$tot_ang_sudah+$ang_sudah;
                    

                     $cRet    .= " <tr>
                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"center\">$nomor</td>  
                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"left\">$kode</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" >$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">".number_format($nilai_kua,"2",".",",")."</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"right\">".number_format($ang_sudah,"2",".",",")."</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"right\">".number_format($sisa,"2",".",",")."</td>";
                
                }

                 $cRet    .= " <tr>                                
                                     <td colspan=\"3\" style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" align=\"center\" >TOTAL</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"right\">".number_format($tot_kua,"2",".",",")."</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"right\">".number_format($tot_ang_sudah,"2",".",",")."</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\"  align=\"right\">".number_format($tot_kua-$tot_ang_sudah,"2",".",",")."</td>
                                  </tr>";

                   


        $cRet .=       " </table>";
        $data['prev']= $cRet;
        $this->template->set('title', 'CETAK KUA');        

        switch($cetak){
        case 0;  
                echo ("<title>CETAK KUA</title>");
                echo($cRet);
            break;   
        case 1;
            $this->_mpdf('',$cRet,10,10,10,0);
        break;
        case 2;        
         header("Cache-Control: no-cache, no-store, must-revalidate");
         header("Content-Type: application/vnd.ms-excel");
         header("Content-Disposition: attachment; filename=cetak_kua_$judul.xls");
        $this->load->view('anggaran/rka/perkadaII', $data);
        break;
        }   

        
        
    }

    // --------------

}
?>