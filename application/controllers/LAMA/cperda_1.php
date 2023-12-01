<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class cperda_1 extends CI_Controller {

	function __contruct(){	
		parent::__construct();
	}

    function cetak_perda1(){                        
        ob_start();
        $thn = $this->session->userdata('pcThang');
    
        $cRet ='';
        $data ='';
        $satker ='991947';  
        $filenamee = $thn.$satker."murni";                                 
		$sql = "SELECT ltrim('$thn') TAHUN,b.kd_urusan AS kodeUrusanProgram,c.nm_urusan AS namaUrusanProgram,SUBSTRING(a.kd_skpd,1,4) AS kodeUrusanPelaksana,
                (SELECT TOP 1 nm_urusan FROM ms_urusan WHERE kd_urusan=SUBSTRING(a.kd_skpd,1,4))   AS namaUrusanPelaksana,
                SUBSTRING(a.kd_skpd,6,2)+SUBSTRING(a.kd_skpd,9,2) AS kodeSKPD,b.nm_skpd AS namaSKPD,
                '0'+SUBSTRING(a.kd_kegiatan,17,2) AS  kodeProgram,b.nm_program AS namaProgram,
                SUBSTRING(a.kd_kegiatan,20,3)+'000' AS kodeKegiatan,
                b.nm_kegiatan AS namaKegiatan,c.kd_fungsi AS kodeFungsi,e.nm_fungsi AS namaFungsi,
                SUBSTRING(a.kd_rek5,1,1) AS kodeAkunUtama,
                (SELECT TOP 1 nm_rek1 FROM ms_rek1 WHERE kd_rek1=SUBSTRING(a.kd_rek5,1,1) ) AS namaAkunUtama,
                SUBSTRING(a.kd_rek5,2,1) AS kodeAkunKelompok,
                (SELECT TOP 1  nm_rek2 FROM ms_rek2 WHERE kd_rek2=SUBSTRING(a.kd_rek5,1,2)) AS namaAkunKelompok,
                SUBSTRING(a.kd_rek5,3,1) AS kodeAkunJenis,
                (SELECT TOP 1  nm_rek3 FROM ms_rek3 WHERE kd_rek3=SUBSTRING(a.kd_rek5,1,3)) AS namaAkunJenis,
                SUBSTRING(a.kd_rek5,4,2) AS kodeAkunObjek,
                (SELECT TOP 1  nm_rek4 FROM ms_rek4 WHERE kd_rek4=SUBSTRING(a.kd_rek5,1,5)) AS namaAkunObjek,
                SUBSTRING(a.kd_rek5,6,2) AS kodeAkunRincian,a.nm_rek5 AS namaAkunRincian,
                '' AS kodeAkunSub,'' AS namaAkunSub,nilai AS nilaiAnggaran FROM trdrka a 
                INNER JOIN trskpd b ON a.kd_kegiatan=b.kd_kegiatan
                INNER JOIN ms_urusan c ON b.kd_urusan=c.kd_urusan
                INNER JOIN ms_fungsi e ON c.kd_fungsi=e.kd_fungsi";


        $sql_query = $this->db->query($sql);              
		foreach ($sql_query->result_array() as $row) {
            $data = $row['TAHUN'].';'.$row['kodeUrusanProgram'].';'.$row['namaUrusanProgram'].';'.$row['kodeUrusanPelaksana'].';'.
                    $row['namaUrusanPelaksana'].';'.$row['kodeSKPD'].';'.$row['namaSKPD'].';'.$row['kodeProgram'].';'.
                    $row['namaProgram'].';'.$row['kodeKegiatan'].';'.$row['namaKegiatan'].';'.$row['kodeFungsi'].';'.$row['namaFungsi'].';'.
                    $row['kodeAkunUtama'].';'.$row['namaAkunUtama'].';'.$row['kodeAkunKelompok'].';'.
                    $row['namaAkunKelompok'].';'.$row['kodeAkunJenis'].';'.$row['namaAkunJenis'].';'.$row['kodeAkunObjek'].';'.$row['namaAkunObjek'].';'.
                    $row['kodeAkunRincian'].';'.$row['namaAkunRincian'].';'.$row['kodeAkunSub'].';'.$row['namaAkunSub'].';'.$row['nilaiAnggaran']."\n";                                                      
			//$data = $row['TAHUN'].';'.$row['KDSATKER'].';'.$row['KDPEMDA'].';'.$row['KDURUSAN'].';'.$row['URAIANURUSAN'].';'.$row['KDKELURUSAN'].';'.$row['URAIANKELURUSAN'].';'.$row['KDORG'].';'.$row['URAIANORG'].';'.strval($row['NOSP2D']).';'.$row['NOSPM'].';'.$row['JNSSP2D'].';'.$row['TGLSP2D'].';'.$row['NILAI'].';'.$row['NPWPBUD'].';'.$row['NPWPBENDAHARA'].';'.$row['NPWPREKANAN'].';'.$row['KET'].';'.$row['NAMAREKANAN']."\n";                                                      
        
        echo $data;                        
        header("Cache-Control: no-cache, no-store");                                
        header("Content-Type: application/csv");                
        header("Content-Disposition: attachement; filename=$filenamee.csv");                                 
                
        }
}


}