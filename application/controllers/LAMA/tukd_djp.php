<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class tukd_djp extends CI_Controller {

Public  $satker ='991947';       
Public	$kdpemda = '1400';
Public  $par_rek_pot = "('2130101','2130201','2130301','2130401','2130501')";   
Public 	$npwpbud = '000332130701000';    

	function __contruct()
	{	
		parent::__construct();
  
	}
	
    function  tanggal_format_indonesia($tgl){
        $tanggal  =  substr($tgl,8,2);
        $bulan  = $this-> getBulan(substr($tgl,5,2));
        $tahun  =  substr($tgl,0,4);
        return  $tanggal.' '.$bulan.' '.$tahun;

    }
    
    function  getBulan($bln){
        switch  ($bln){
        case  1:
        return  "Januari";
        break;
        case  2:
        return  "Februari";
        break;
        case  3:
        return  "Maret";
        break;
        case  4:
        return  "April";
        break;
        case  5:
        return  "Mei";
        break;
        case  6:
        return  "Juni";
        break;
        case  7:
        return  "Juli";
        break;
        case  8:
        return  "Agustus";
        break;
        case  9:
        return  "September";
        break;
        case  10:
        return  "Oktober";
        break;
        case  11:
        return  "November";
        break;
        case  12:
        return  "Desember";
        break;
    }
    }
    
    
    function cetak_csv1($nbulan=''){                        
        ob_start();
        $thn = $this->session->userdata('pcThang');
        
        if(strlen($nbulan)==2){
            $nbulan=$nbulan;
        }else{
            $nbulan='0'.$nbulan;
        }
        
        $cRet ='';
        $data ='';
        $satker =$this->satker;  
		$kdpemda =$this->kdpemda;
        $par_rek_pot = $this->par_rek_pot; 
		$npwpbud = $this->npwpbud;		
        $filenamee = $thn.$nbulan.$satker."01";                                 
		/*
		$sql = "select TAHUN,KDSATKER,KDPEMDA,KDURUSAN,URAIANURUSAN,KDKELURUSAN,URAIANKELURUSAN,KDORG,rtrim(URAIANORG) [URAIANORG],NOSP2D,NOSPM,JNSSP2D,
				TGLSP2D,isnull(NILAI,0) [NILAI],NPWPBUD,NPWPBENDAHARA,NPWPREKANAN,left(rtrim(KET),255) [KET],NAMAREKANAN from(        
				SELECT left(b.kd_skpd,7)+'.00' as kd_skpd,
				$thn TAHUN,
				$satker KDSATKER,
				$kdpemda KDPEMDA,
				CASE WHEN SUBSTRING(b.kd_kegiatan,1,4) is null then SUBSTRING(b.kd_kegiatan,1,4) else SUBSTRING(b.kd_kegiatan,1,4) end AS KDURUSAN,
				CASE WHEN SUBSTRING(b.kd_kegiatan,1,4) is null then 
				(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(b.kd_kegiatan,1,4)) else
				(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(b.kd_kegiatan,1,4)) end AS URAIANURUSAN,
				CASE WHEN SUBSTRING(b.kd_kegiatan,1,4) is null then SUBSTRING(b.kd_kegiatan,1,4) else SUBSTRING(b.kd_kegiatan,1,4) end AS KDKELURUSAN,
				CASE WHEN SUBSTRING(b.kd_kegiatan,1,4) is null then 
				(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(b.kd_kegiatan,1,4)) else
				(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(b.kd_kegiatan,1,4)) end AS URAIANKELURUSAN,
				CASE WHEN SUBSTRING(b.kd_skpd,6,2) is null then SUBSTRING(b.kd_skpd,6,2) else SUBSTRING(b.kd_skpd,6,2) end AS KDORG,
				(select nm_org from ms_organisasi where kd_org=left(b.kd_skpd,7)) AS URAIANORG,
				b.no_sp2d NOSP2D,
				 (SELECT no_spm FROM trhsp2d WHERE rtrim(no_sp2d) = rtrim(b.no_sp2d)) NOSPM,
				 (SELECT jns_spp FROM trhsp2d WHERE rtrim(no_sp2d) = rtrim(b.no_sp2d)) JNSSP2D,
				 (SELECT REPLACE(tgl_sp2d,'-','') as tgl_sp2d FROM trhsp2d WHERE rtrim(no_sp2d) = rtrim(b.no_sp2d)) TGLSP2D,
				 (SELECT SUM (nilai) FROM trhsp2d WHERE rtrim(no_sp2d) = rtrim(b.no_sp2d)) NILAI,
				 '000332130701000' NPWPBUD,
				 (SELECT REPLACE(REPLACE(npwp, '.', ''),'-','') FROM trhsp2d WHERE rtrim(no_sp2d) = rtrim(b.no_sp2d)) NPWPBENDAHARA,
				 (SELECT top 1 CASE WHEN nmrekan = '' THEN REPLACE(REPLACE(npwp, '.', ''),'-','') ELSE '' END AS NPWPREKANAN FROM trhsp2d WHERE rtrim(no_sp2d) = rtrim(b.no_sp2d)) NPWPREKANAN,
				 (SELECT keperluan FROM trhsp2d WHERE rtrim(no_sp2d) = rtrim(b.no_sp2d)) KET,
				 (SELECT RTRIM(nmrekan) FROM trhsp2d WHERE rtrim(no_sp2d) = rtrim(b.no_sp2d)) NAMAREKANAN
				FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
				WHERE month(b.tgl_bukti)='$nbulan'
				AND RTRIM(a.kd_rek5) IN $par_rek_pot
				group by left(b.kd_skpd,7),SUBSTRING(b.kd_kegiatan,1,4),SUBSTRING(b.kd_skpd,6,2),b.no_sp2d
				)x where x.NOSP2D <> ''";
				
				REPLACE(REPLACE(nama_kolom, CHAR(13),' '), CHAR(10),' ')
		*//*
		$sql = "select $thn [TAHUN],$satker [KDSATKER],$kdpemda [KDPEMDA],LEFT(g.kd_urusan,4) KDURUSAN,g.nm_urusan [URAIANURUSAN],g.kd_urusan [KDKELURUSAN],g.nm_urusan URAIANKELURUSAN, 
				RIGHT(i.kd_org,2) [KDORG],left(rtrim(i.nm_org),255) [URAIANORG],f.no_sp2d [NOSP2D],f.no_spm [NOSPM],f.jns_spp [JNSSP2D],REPLACE(f.tgl_sp2d,'-','')[TGLSP2D],
				f.nilai [NILAI],'000332130701000' NPWPBUD,REPLACE(REPLACE(h.npwp, '.', ''),'-','') [NPWPBENDAHARA],
				(case when f.nmrekan='' then '' else REPLACE(REPLACE(f.npwp, '.', ''),'-','') end)  NPWPREKANAN,
				REPLACE(REPLACE(REPLACE(left(rtrim(f.keperluan),255), CHAR(13),' '), CHAR(10),' '),';',' ') [KET],f.nmrekan [NAMAREKANAN]
				from(
				select * from(
					select a.no_sp2d
					from trhstrpot a join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(a.tgl_bukti)='$nbulan'
					and b.kd_rek5 in $par_rek_pot and a.no_sp2d<>''
				)as d group by no_sp2d
				)as c join trhsp2d f on c.no_sp2d=f.no_sp2d 
				join ms_urusan g on g.kd_urusan=left(f.kd_skpd,4)
				join ms_skpd h on f.kd_skpd=h.kd_skpd
				join ms_organisasi i on h.kd_org=i.kd_org
				order by i.kd_org+g.kd_urusan,TGLSP2D,NOSP2D";*/
/*		
		$sql = "select * from(
				select $thn [TAHUN],$satker [KDSATKER],$kdpemda [KDPEMDA],LEFT(g.kd_urusan,4) KDURUSAN,g.nm_urusan [URAIANURUSAN],g.kd_urusan [KDKELURUSAN],g.nm_urusan URAIANKELURUSAN, 
				RIGHT(i.kd_org,2) [KDORG],left(rtrim(i.nm_org),255) [URAIANORG],f.no_sp2d [NOSP2D],f.no_spm [NOSPM],f.jns_spp [JNSSP2D],REPLACE(f.tgl_sp2d,'-','')[TGLSP2D],
				f.nilai [NILAI],$npwpbud NPWPBUD,REPLACE(REPLACE(h.npwp, '.', ''),'-','') [NPWPBENDAHARA],
				(case when f.nmrekan='' then '' else replace(REPLACE(REPLACE(f.npwp, '.', ''),'-',''),' ','') end)  NPWPREKANAN,
				REPLACE(REPLACE(REPLACE(left(rtrim(f.keperluan),255), CHAR(13),' '), CHAR(10),' '),';',' ') [KET],f.nmrekan [NAMAREKANAN]
				from(
				select * from(
					select a.no_sp2d
					from trhstrpot a join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(a.tgl_bukti)='$nbulan'
					and b.kd_rek5 in $par_rek_pot and a.no_sp2d<>''
				)as d group by no_sp2d
				)as c join trhsp2d f on c.no_sp2d=f.no_sp2d 
				join ms_urusan g on g.kd_urusan=left(f.kd_skpd,4)
				join ms_skpd h on f.kd_skpd=h.kd_skpd
				join ms_organisasi i on h.kd_org=i.kd_org
				union all			
				select $thn [TAHUN],$satker [KDSATKER],$kdpemda [KDPEMDA],LEFT(g.kd_urusan,4) KDURUSAN,g.nm_urusan [URAIANURUSAN],g.kd_urusan [KDKELURUSAN],g.nm_urusan URAIANKELURUSAN, 
				RIGHT(i.kd_org,2) [KDORG],left(rtrim(i.nm_org),255) [URAIANORG],f.no_sp2d [NOSP2D],f.no_spm [NOSPM],f.jns_spp [JNSSP2D],REPLACE(f.tgl_sp2d,'-','')[TGLSP2D],
				f.nilai [NILAI],$npwpbud NPWPBUD,REPLACE(REPLACE(h.npwp, '.', ''),'-','') [NPWPBENDAHARA],
				(case when f.nmrekan='' then '' else replace(REPLACE(REPLACE(f.npwp, '.', ''),'-',''),' ','') end)  NPWPREKANAN,
				REPLACE(REPLACE(REPLACE(left(rtrim(f.keperluan),255), CHAR(13),' '), CHAR(10),' '),';',' ') [KET],f.nmrekan [NAMAREKANAN] from(
				select * from trhsp2d e 	
				where e.no_sp2d not in 
				(select * from(
					select a.no_sp2d
					from trhstrpot a join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where 
					b.kd_rek5 in $par_rek_pot and a.no_sp2d<>'' and a.kd_skpd=e.kd_skpd
				)as d group by no_sp2d
				) and MONTH(e.tgl_sp2d)='$nbulan'
				) as f join ms_urusan g on g.kd_urusan=left(f.kd_skpd,4)
				join ms_skpd h on f.kd_skpd=h.kd_skpd
				join ms_organisasi i on h.kd_org=i.kd_org
				)as csv1 order by KDORG+KDURUSAN,TGLSP2D,NOSP2D	";
*/		
		$sql = "SELECT x.* FROM(
SELECT z.kd_skpd as SKPD,z.TAHUN,z.KDSATKER,z.KDPEMDA,z.KDURUSAN,z.URAIANURUSAN,
z.KDKELURUSAN,z.URAIANKELURUSAN,z.KDORG,z.URAIANORG,z.NOSP2D,z.NOSPM,z.JNSSP2D,z.TGLSP2D,
z.NILAI,z.NPWPBUD,z.NPWPBENDAHARA,z.NPWPREKANAN,z.KET,z.NAMAREKANAN
FROM(
SELECT d.kd_skpd as kd_skpd,
$thn TAHUN,
$satker KDSATKER,
$kdpemda KDPEMDA,
CASE WHEN SUBSTRING(d.kd_skpd,1,4) is null then SUBSTRING(d.kd_skpd,1,4) else SUBSTRING(d.kd_skpd,1,4) end AS KDURUSAN,
CASE WHEN SUBSTRING(d.kd_skpd,1,4) is null then 
(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(d.kd_skpd,1,4)) else
(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(d.kd_skpd,1,4)) end AS URAIANURUSAN,
CASE WHEN SUBSTRING(d.kd_skpd,1,4) is null then SUBSTRING(d.kd_skpd,1,4) else SUBSTRING(d.kd_skpd,1,4) end AS KDKELURUSAN,
CASE WHEN SUBSTRING(d.kd_skpd,1,4) is null then 
(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(d.kd_skpd,1,4)) else
(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(d.kd_skpd,1,4)) end AS URAIANKELURUSAN,
CASE WHEN SUBSTRING(d.kd_skpd,6,2) is null then SUBSTRING(d.kd_skpd,6,2) else SUBSTRING(d.kd_skpd,6,2) end AS KDORG,
(select nm_org from ms_organisasi where kd_org=left(d.kd_skpd,7)) AS URAIANORG,
RTRIM(d.no_sp2d) NOSP2D,
RTRIM(d.no_spm) NOSPM,
d.jns_spp JNSSP2D,
REPLACE(d.tgl_sp2d,'-','') TGLSP2D,
d.nilai NILAI,
$npwpbud NPWPBUD,
replace(REPLACE(REPLACE(d.npwp, '.', ''),'-',''),' ','') NPWPBENDAHARA,
CASE WHEN nmrekan = '' THEN replace(REPLACE(REPLACE(d.npwp, '.', ''),'-',''),' ','') ELSE '' END AS NPWPREKANAN,
RTRIM(REPLACE(REPLACE('', CHAR(13), ''),CHAR(10),'')) KET,
RTRIM(d.nmrekan) NAMAREKANAN
FROM trdspp a 
INNER JOIN trhsp2d d on a.no_spp = d.no_spp AND a.kd_skpd=d.kd_skpd
WHERE MONTH(d.tgl_sp2d)='$nbulan' and (d.sp2d_batal is null or d.sp2d_batal<>'1') 
GROUP BY d.kd_skpd,SUBSTRING(d.kd_skpd,1,4),SUBSTRING(d.kd_skpd,6,2),
d.no_sp2d,d.no_spm,d.jns_spp,d.tgl_sp2d,d.nilai,d.npwp,d.keperluan,d.nmrekan
)z left join (
SELECT b.kd_skpd as kd_skpd, b.no_sp2d, SUM(a.nilai) nil_pot FROM trdstrpot a INNER JOIN trhstrpot b
ON a.no_bukti=b.no_bukti AND a.kd_skpd=b.kd_skpd
WHERE RTRIM(a.kd_rek5) IN $par_rek_pot 
GROUP BY b.kd_skpd,b.no_sp2d
)x on x.kd_skpd=z.kd_skpd and x.no_sp2d = z.NOSP2D
group by z.kd_skpd,z.TAHUN,z.KDSATKER,z.KDPEMDA,z.KDURUSAN,z.URAIANURUSAN,
z.KDKELURUSAN,z.URAIANKELURUSAN,z.KDORG,z.URAIANORG,z.NOSP2D,z.NOSPM,z.JNSSP2D,z.TGLSP2D,
z.NILAI,z.NPWPBUD,z.NPWPBENDAHARA,z.NPWPREKANAN,z.KET,z.NAMAREKANAN
)x where x.NOSP2D <> '' and x.NILAI IS NOT NULL ORDER BY x.NOSP2D";


                $sql_query = $this->db->query($sql);              
				foreach ($sql_query->result_array() as $row) {
				$nilai  = strval($row['NILAI']);
                $nilai  = str_replace(".00","",$nilai);
                    $data = $row['TAHUN'].';'.$row['KDSATKER'].';'.$row['KDPEMDA'].';'.$row['KDURUSAN'].';'.$row['URAIANURUSAN'].';'.$row['KDKELURUSAN'].';'.$row['URAIANKELURUSAN'].';'.$row['KDORG'].';'.$row['URAIANORG'].';'.strval($row['NOSP2D']).';'.$row['NOSPM'].';'.$row['JNSSP2D'].';'.$row['TGLSP2D'].';'.$nilai.';\''.$row['NPWPBUD'].';\''.$row['NPWPBENDAHARA'].';\''.$row['NPWPREKANAN'].';'.preg_replace('~[\n]+~', '', $row['KET']).';'.$row['NAMAREKANAN']."\n";                                                      
					//$data = $row['TAHUN'].';'.$row['KDSATKER'].';'.$row['KDPEMDA'].';'.$row['KDURUSAN'].';'.$row['URAIANURUSAN'].';'.$row['KDKELURUSAN'].';'.$row['URAIANKELURUSAN'].';'.$row['KDORG'].';'.$row['URAIANORG'].';'.strval($row['NOSP2D']).';'.$row['NOSPM'].';'.$row['JNSSP2D'].';'.$row['TGLSP2D'].';'.$row['NILAI'].';'.$row['NPWPBUD'].';'.$row['NPWPBENDAHARA'].';'.$row['NPWPREKANAN'].';'.$row['KET'].';'.$row['NAMAREKANAN']."\n";                                                      
                
                echo $data;                        
                header("Cache-Control: no-cache, no-store");                                
                header("Content-Type: application/csv");                
                header("Content-Disposition: attachement; filename=$filenamee.csv");                                 
                
                }
}


    function cetak_csv2($nbulan=''){                        
        ob_start();
        $thn = $this->session->userdata('pcThang');
        
        if(strlen($nbulan)==2){
            $nbulan=$nbulan;
        }else{
            $nbulan='0'.$nbulan;
        }
        
        $cRet ='';
        $data ='';
        $satker =$this->satker;    
		$kdpemda =$this->kdpemda;
        $par_rek_pot = $this->par_rek_pot;       
        $filenamee = $thn.$nbulan.$satker."02";      

		/*
		$sql = "SELECT x.TAHUN,x.KDSATKER,KDPEMDA,x.KDURUSAN,x.KDKELURUSAN,x.KDORG,x.NOSP2D,v.KDAKUN,isnull(v.URAIANAKUN,'') [URAIANAKUN],isnull(v.KDJNSAKUN,'') [KDJNSAKUN],isnull(v.URAIANJNSAKUN,'') [URAIANJNSAKUN]
				,v.KDKELAKUN,isnull(v.URAIANKELAKUN,'') [URAIANKELAKUN],
				v.KDOBJAKUN,v.URAIANOBJAKUN,v.KDRINCIOBJAKUN,v.URAIANRINCIOBJAKUN,isnull(v.NILAIBELANJA,0) [NILAIBELANJA] FROM(
				SELECT left(b.kd_skpd,7)+'.00' as kd_skpd,
				'$thn' TAHUN,
				$satker KDSATKER,
				$kdpemda KDPEMDA,
				CASE WHEN SUBSTRING(b.kd_kegiatan,1,4) is null then SUBSTRING(b.kd_kegiatan,1,4) else SUBSTRING(b.kd_kegiatan,1,4) end AS KDURUSAN,
				CASE WHEN SUBSTRING(b.kd_kegiatan,1,4) is null then SUBSTRING(b.kd_kegiatan,1,4) else SUBSTRING(b.kd_kegiatan,1,4) end AS KDKELURUSAN,
				CASE WHEN SUBSTRING(b.kd_skpd,6,2) is null then SUBSTRING(b.kd_skpd,6,2) else SUBSTRING(b.kd_skpd,6,2) end AS KDORG,
				b.no_sp2d NOSP2D
				 FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
				WHERE month(b.tgl_bukti)='$nbulan'
				AND RTRIM(a.kd_rek5) IN $par_rek_pot
				group by left(b.kd_skpd,7),SUBSTRING(b.kd_kegiatan,1,4),SUBSTRING(b.kd_skpd,6,2),b.no_sp2d
				)x left join(
				select z.* from(
				select 
				b.no_sp2d NOSP2D,
				SUBSTRING(a.kd_rek5,1,1) AS KDAKUN,(SELECT DISTINCT nm_rek1 FROM ms_rek1 WHERE kd_rek1=SUBSTRING(a.kd_rek5,1,1)) AS URAIANAKUN,
				SUBSTRING(a.kd_rek5,2,1) AS KDKELAKUN,(SELECT DISTINCT nm_rek2 FROM ms_rek2 WHERE kd_rek2=SUBSTRING(a.kd_rek5,1,2)) AS URAIANKELAKUN,
				SUBSTRING(a.kd_rek5,3,1) AS KDJNSAKUN,(SELECT DISTINCT nm_rek3 FROM ms_rek3 WHERE kd_rek3=SUBSTRING(a.kd_rek5,1,3)) AS URAIANJNSAKUN,
				SUBSTRING(a.kd_rek5,4,2) AS KDOBJAKUN,(SELECT DISTINCT nm_rek4 FROM ms_rek4 WHERE kd_rek4=SUBSTRING(a.kd_rek5,1,5)) AS URAIANOBJAKUN,
				SUBSTRING(a.kd_rek5,6,2) AS KDRINCIOBJAKUN,(SELECT DISTINCT nm_rek5 FROM ms_rek5 WHERE kd_rek5=a.kd_rek5) AS URAIANRINCIOBJAKUN,
				a.nilai NILAIBELANJA
				from trhsp2d b 
				left join trdspp a ON b.kd_skpd=a.kd_skpd and a.no_spp=b.no_spp
				)z)v on v.NOSP2D=x.NOSP2D";	
		*/
		/*
		$sql = "select $thn [TAHUN],$satker [KDSATKER],$kdpemda [KDPEMDA],LEFT(g.kd_urusan,4) KDURUSAN,g.nm_urusan [URAIANURUSAN],g.kd_urusan [KDKELURUSAN],
				g.nm_urusan URAIANKELURUSAN,RIGHT(m.kd_org,2) [KDORG],left(rtrim(m.nm_org),255) [URAIANORG],f.no_sp2d [NOSP2D],i.kd_rek1 [KDAKUN], 
				i.nm_rek1 [URAIANAKUN],RIGHT(j.kd_rek2,1) [KDJNSAKUN],j.nm_rek2 [URAIANJNSAKUN],RIGHT(k.kd_rek3,1) [KDKELAKUN],k.nm_rek3 [URAIANKELAKUN],
				RIGHT(l.kd_rek4,2) [KDOBJAKUN],l.nm_rek4 [URAIANOBJAKUN],RIGHT(h.kd_rek5,2) [KDRINCIOBJAKUN],left(h.nm_rek5,255) [URAIANRINCIOBJAKUN],h.nilai [NILAIBELANJA]
				from(
					select * from(
						select a.no_sp2d
						from trhstrpot a join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd where MONTH(a.tgl_bukti)='$nbulan'
						and b.kd_rek5 IN $par_rek_pot  
					)as d group by no_sp2d
				)as c join trhsp2d f on c.no_sp2d=f.no_sp2d 
				join ms_urusan g on g.kd_urusan=left(f.kd_skpd,4)	
				join trdspp h on f.no_spp=h.no_spp
				join ms_rek1 i on LEFT(h.kd_rek5,1)=i.kd_rek1
				join ms_rek2 j on LEFT(h.kd_rek5,2)=j.kd_rek2
				join ms_rek3 k on LEFT(h.kd_rek5,3)=k.kd_rek3
				join ms_rek4 l on LEFT(h.kd_rek5,5)=l.kd_rek4
				join ms_organisasi m on left(f.kd_skpd,7)=m.kd_org 
				order by m.kd_org+g.kd_urusan,NOSP2D,h.kd_rek5";*/
$sql="

select 
y.kd_skpd as SKPD,y.TAHUN,y.KDSATKER,y.KDURUSAN,y.KDKELURUSAN,y.KDORG,y.NOSP2D,
y.KDAKUN,y.URAIANAKUN,y.KDKELAKUN,y.URAIANKELAKUN,y.KDJNSAKUN,y.URAIANJNSAKUN,
y.KDOBJAKUN,y.URAIANOBJAKUN,y.KDRINCIOBJAKUN,URAIANRINCIOBJAKUN,y.NILAIBELANJA
 from (
select x.*  from (
--Kota Pontianak pakai left(d.kd_skpd,7)+'.00'
SELECT a.kd_skpd as kd_skpd, 
$thn TAHUN,
$satker KDSATKER,
$kdpemda KDPEMDA,
CASE WHEN SUBSTRING(a.kd_skpd,1,4) is null then SUBSTRING(a.kd_skpd,1,4) else SUBSTRING(a.kd_skpd,1,4) end AS KDURUSAN,
CASE WHEN SUBSTRING(a.kd_skpd,1,4) is null then SUBSTRING(a.kd_skpd,1,4) else SUBSTRING(a.kd_skpd,1,4) end AS KDKELURUSAN,
CASE WHEN SUBSTRING(a.kd_skpd,6,2) is null then SUBSTRING(a.kd_skpd,6,2) else SUBSTRING(a.kd_skpd,6,2) end AS KDORG,
a.no_sp2d NOSP2D,a.no_spp,b.kd_rek5,
SUBSTRING(b.kd_rek5,1,1) AS KDAKUN,(SELECT DISTINCT nm_rek1 FROM ms_rek1 WHERE kd_rek1=SUBSTRING(b.kd_rek5,1,1)) AS URAIANAKUN,
SUBSTRING(b.kd_rek5,2,1) AS KDKELAKUN,(SELECT DISTINCT nm_rek2 FROM ms_rek2 WHERE kd_rek2=SUBSTRING(b.kd_rek5,1,2)) AS URAIANKELAKUN,
SUBSTRING(b.kd_rek5,3,1) AS KDJNSAKUN,(SELECT DISTINCT nm_rek3 FROM ms_rek3 WHERE kd_rek3=SUBSTRING(b.kd_rek5,1,3)) AS URAIANJNSAKUN,
SUBSTRING(b.kd_rek5,4,2) AS KDOBJAKUN,(SELECT DISTINCT nm_rek4 FROM ms_rek4 WHERE kd_rek4=SUBSTRING(b.kd_rek5,1,5)) AS URAIANOBJAKUN,
SUBSTRING(b.kd_rek5,6,2) AS KDRINCIOBJAKUN,(SELECT DISTINCT nm_rek5 FROM ms_rek5 WHERE kd_rek5=b.kd_rek5) AS URAIANRINCIOBJAKUN,
sum(b.nilai) as NILAIBELANJA
from trhsp2d a 
inner join trdspp b on b.kd_skpd=a.kd_skpd and a.no_spp=b.no_spp
where MONTH(a.tgl_sp2d)='$nbulan' and (a.sp2d_batal is null or a.sp2d_batal<>'1')  
group by a.kd_skpd,SUBSTRING(a.kd_skpd,1,4),SUBSTRING(a.kd_skpd,6,2),a.no_sp2d,a.no_spp,b.kd_rek5
)x
)y order by y.NOSP2D
";				

                $sql_query = $this->db->query($sql);              
				foreach ($sql_query->result_array() as $row) {
				    $nilai  = strval($row['NILAIBELANJA']);
                    $nilai  = str_replace(".00","",$nilai);
                    $data = $row['TAHUN'].';'.$row['KDSATKER'].';'.$row['KDURUSAN'].';'.$row['KDKELURUSAN'].';'.$row['KDORG'].';'.$row['NOSP2D'].';'.$row['KDAKUN'].';'.$row['URAIANAKUN'].';'.$row['KDKELAKUN'].';'.$row['URAIANKELAKUN'].';'.$row['KDJNSAKUN'].';'.$row['URAIANJNSAKUN'].';'.$row['KDOBJAKUN'].';'.$row['URAIANOBJAKUN'].';'.$row['KDRINCIOBJAKUN'].';'.$row['URAIANRINCIOBJAKUN'].';'.$nilai."\n";                                                      
				
                    //$data = $row['TAHUN'].';'.$row['KDSATKER'].';'.$row['KDPEMDA'].';'.$row['KDURUSAN'].';'.$row['KDKELURUSAN'].';'.$row['KDORG'].';'.$row['NOSP2D'].';'.$row['KDAKUN'].';'.$row['URAIANAKUN'].';'.$row['KDKELAKUN'].';'.$row['URAIANKELAKUN'].';'.$row['KDJNSAKUN'].';'.$row['URAIANJNSAKUN'].';'.$row['KDOBJAKUN'].';'.$row['URAIANOBJAKUN'].';'.$row['KDRINCIOBJAKUN'].';'.$row['URAIANRINCIOBJAKUN'].';'.strval($row['NILAIBELANJA'])."\n";                                                      
                
                echo $data;                        
                header("Cache-Control: no-cache, no-store");                                
                header("Content-Type: application/csv");                
                header("Content-Disposition: attachement; filename=$filenamee.csv");                                 
                
                }
                            			
		
}

    function cetak_csv3($nbulan=''){                        
        ob_start();
        $thn = $this->session->userdata('pcThang');
        
        if(strlen($nbulan)==2){
            $nbulan=$nbulan;
        }else{
            $nbulan='0'.$nbulan;
        }
        
        $cRet ='';
        $data ='';
        $satker =$this->satker;    
		$kdpemda =$this->kdpemda;
        $par_rek_pot = $this->par_rek_pot;       
        $filenamee = $thn.$nbulan.$satker."03";                                 
		/*
			$sql="select TAHUN,KDSATKER,KDPEMDA,KDURUSAN,KDKELURUSAN,URAIANKELURUSAN,KDORG,rtrim(URAIANORG) [URAIANORG],NOSP2D,KDPAJAK,SUM(NILAIPAJAK) [NILAIPAJAK],NTPN from(
					SELECT 
					'$thn' TAHUN,
					$satker KDSATKER,
					$kdpemda KDPEMDA,
					CASE WHEN SUBSTRING(b.kd_kegiatan,1,4) is null then SUBSTRING(b.kd_kegiatan,1,4) else SUBSTRING(a.kd_skpd,1,4) end AS KDURUSAN,
					CASE WHEN SUBSTRING(b.kd_kegiatan,1,4) is null then 
					(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(b.kd_kegiatan,1,4)) else
					(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(b.kd_kegiatan,1,4)) end AS URAIANURUSAN,
					CASE WHEN SUBSTRING(b.kd_kegiatan,1,4) is null then SUBSTRING(b.kd_kegiatan,1,4) else SUBSTRING(b.kd_kegiatan,1,4) end AS KDKELURUSAN,
					CASE WHEN SUBSTRING(b.kd_kegiatan,1,4) is null then 
					(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(b.kd_kegiatan,1,4)) else
					(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(b.kd_kegiatan,1,4)) end AS URAIANKELURUSAN,
					CASE WHEN SUBSTRING(b.kd_skpd,6,2) is null then SUBSTRING(b.kd_skpd,6,2) else SUBSTRING(b.kd_skpd,6,2) end AS KDORG,
					(select nm_org from ms_organisasi where kd_org=left(b.kd_skpd,7)) AS URAIANORG,
					b.no_sp2d NOSP2D,
					CASE WHEN a.kd_rek5='2130101' then '411121'
						 WHEN a.kd_rek5='2130201' then '411122'
						 WHEN a.kd_rek5='2130301' then '411211'
						 WHEN a.kd_rek5='2130401' then '411124'
						 WHEN a.kd_rek5='2130501' then '411128'
					END AS KDPAJAK,
					a.nilai NILAIPAJAK,
					a.ntpn NTPN
					FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
					WHERE month(b.tgl_bukti)='$nbulan'
					AND RTRIM(a.kd_rek5) IN $par_rek_pot
					)as z 
					group by TAHUN,KDSATKER,KDPEMDA,KDURUSAN,KDKELURUSAN,URAIANKELURUSAN,KDORG,URAIANORG,NOSP2D,KDPAJAK,NTPN";
		*/
		/*	$sql="select $thn [TAHUN], $satker [KDSATKER], $kdpemda [KDPEMDA],d.kd_urusan [KDURUSAN],d.kd_urusan [KDKELURUSAN],d.nm_urusan [URAIANKELURUSAN],right(c.kd_org,2) [KDORG],c.nm_org [URAIANORG], a.no_sp2d [NOSP2D],
					CASE WHEN b.kd_rek5='2130101' then '411121'
						 WHEN b.kd_rek5='2130201' then '411122'
						 WHEN b.kd_rek5='2130301' then '411211'
						 WHEN b.kd_rek5='2130401' then '411124'
						 WHEN b.kd_rek5='2130501' then '411128'
					END AS KDPAJAK,
					sum(b.nilai) [NILAIPAJAK],isnull(b.ntpn,'') [NPTN]
					from trhstrpot a join trdstrpot b on a.no_bukti=b.no_bukti and a.kd_skpd=b.kd_skpd 
					join ms_organisasi c  on left(b.kd_skpd,7)=c.kd_org
					join ms_urusan d on left(b.kd_skpd,4)=d.kd_urusan
					where MONTH(a.tgl_bukti)='$nbulan'
					and b.kd_rek5 IN $par_rek_pot and a.no_sp2d<>''
					group by d.kd_urusan,d.nm_urusan,right(c.kd_org,2),c.nm_org,a.no_sp2d,b.kd_rek5,isnull(b.ntpn,'')	";*/
		$sql="SELECT 
b.no_bukti,
$thn TAHUN,
$satker KDSATKER,
$kdpemda KDPEMDA,
CASE WHEN SUBSTRING(b.kd_skpd,1,4) is null then SUBSTRING(b.kd_skpd,1,4) else SUBSTRING(b.kd_skpd,1,4) end AS KDURUSAN,
CASE WHEN SUBSTRING(b.kd_skpd,1,4) is null then 
(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(b.kd_skpd,1,4)) else
(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(b.kd_skpd,1,4)) end AS URAIANURUSAN,
CASE WHEN SUBSTRING(b.kd_skpd,1,4) is null then SUBSTRING(b.kd_skpd,1,4) else SUBSTRING(b.kd_skpd,1,4) end AS KDKELURUSAN,
CASE WHEN SUBSTRING(b.kd_skpd,1,4) is null then 
(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(b.kd_skpd,1,4)) else
(select nm_urusan from dbo.ms_urusan where kd_urusan=SUBSTRING(b.kd_skpd,1,4)) end AS URAIANKELURUSAN,
CASE WHEN SUBSTRING(b.kd_skpd,6,2) is null then SUBSTRING(b.kd_skpd,6,2) else SUBSTRING(b.kd_skpd,6,2) end AS KDORG,
(select nm_org from ms_organisasi where kd_org=left(b.kd_skpd,7)) AS URAIANORG,
b.no_sp2d NOSP2D,
CASE WHEN a.kd_rek5='2130101' then '411121'
     WHEN a.kd_rek5='2130201' then '411122'
     WHEN a.kd_rek5='2130301' then '411211'
     WHEN a.kd_rek5='2130401' then '411124'
     WHEN a.kd_rek5='2130501' then '411128'
END AS KDPAJAK, 
a.nilai NILAIPAJAK,
'' NTPN
FROM trdstrpot a JOIN trhstrpot b ON a.no_bukti = b.no_bukti and a.kd_skpd=b.kd_skpd
WHERE month(b.tgl_bukti)='$nbulan'
AND RTRIM(a.kd_rek5) IN $par_rek_pot
";	
                $sql_query = $this->db->query($sql);              
				foreach ($sql_query->result_array() as $row) {
				$nilai  = strval($row['NILAIPAJAK']);
                $nilai  = str_replace(".00","",$nilai);
				    
					$data = $row['TAHUN'].';'.$row['KDSATKER'].';'.$row['KDURUSAN'].';'.$row['KDKELURUSAN'].';'.$row['KDORG'].';'.$row['NOSP2D'].';'.$row['KDPAJAK'].';'.$nilai."\n"; 
                   // $data = $row['TAHUN'].';'.$row['KDSATKER'].';'.$row['KDPEMDA'].';'.$row['KDURUSAN'].';'.$row['KDKELURUSAN'].';'.$row['KDORG'].';'.$row['NOSP2D'].';'.$row['KDPAJAK'].';'.strval($row['NILAIPAJAK']).';'.$row['NTPN']."\n";                                                      
                
                echo $data;                        
                header("Cache-Control: no-cache, no-store");                                
                header("Content-Type: application/csv");                
                header("Content-Disposition: attachement; filename=$filenamee.csv");                                 
                
                }

}    
}