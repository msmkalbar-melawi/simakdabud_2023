<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Tukd_pusk extends CI_Controller {

    function __contruct()
    {   
        parent::__construct();
    }
    

    function sp2b() 
    {
        $data['page_title']= 'SP2B';
        $this->template->set('title', 'SP2B');   
        $this->template->load('template','tukd/pusk/sp2b',$data) ; 
    }    
        
    function laporan_sp3b_blud()
    {
        $data['page_title']= 'CETAK SP3B (BLUD)';
        $this->template->set('title', 'CETAK SP3B (BLUD)');   
        $this->template->load('template','/tukd/transaksi_pusk/cetak_sp3b_blud',$data) ; 
    }

    function load_dtrsp3b_blud() 
    {           
        $kriteria = $this->input->post('no');        
        $skpd = $this->input->post('skpd');
        
        $sql = "SELECT a.*, a.kd_rek6 as rek, '' as nm_rek
        from trsp3b_blud a where a.no_sp3b = '$kriteria' and left(a.kd_skpd,22)=left('$skpd',22) order by a.no_sp3b";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'no_sp3b' => $resulte['no_sp3b'],
                        'no_lpj' => $resulte['no_lpj'],
                        'no_bukti' => $resulte['no_bukti'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'kd_rek5' => $resulte['rek'],
                        'kd_rek7' => $resulte['kd_rek6'],
                        'nm_rek7' => $resulte['nm_rek6'],
                        'nm_rek' => $resulte['nm_rek'],
                        'nilai' => $resulte['nilai'],
                        'kd_kegiatan' => $resulte['kd_sub_kegiatan']
                        );
                        $ii++;
        }
           
        echo json_encode($result);
           
    } 

    function skpd_3(){
        $kd_skpd = $this->session->userdata('kdskpd');
        $kd_skpdd = substr($kd_skpd,0,7);
        ECHO $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd_blud where left(kd_skpd,4) = left('$kd_skpdd',4) and kd_skpd <> ('$kd_skpd')";
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

    function cetak_sp3b_blud($lcskpd='',$ctgl_1='',$ctgl_2='',$ctk=''){
        $pusk = $this->uri->segment(6);
        $nip2 = str_replace('123456789',' ',$this->uri->segment(7));
        $ketsaldo = ''; 
        $tanggal_ttd = $this->tukd_model->tanggal_format_indonesia($this->uri->segment(8));
        $atas = $this->uri->segment(10);
        $bawah = $this->uri->segment(11);
        $kiri = $this->uri->segment(12);
        $kanan = $this->uri->segment(13);   
        $nosp3b = str_replace('hhh','/',$this->uri->segment(14));
        $sp3b = $this->uri->segment(15);
        $nilai_saldo = 0;        
        //echo $nosp3b;
        $nilai_saldo = 0;        
        $saldo=0;
        $n_saldo=0;

            $n = $this->db->query("SELECT sum(sld_awal) sld_awal from (
                SELECT ISNULL(saldo_lalu,0) as sld_awal from ms_skpd_blud where kd_skpd='$lcskpd'
                union all
                select 0 ) okei
                ")->row();            
            $saldo = $n->sld_awal;
        
            $sql1=$this->db->query(" SELECT sum(isnull(c.terima,0))-sum(isnull(c.keluar,0)) nilai from(
                SELECT
                case when left(a.kd_rek6,1)=4 then SUM (isnull(a.nilai,0)) end as terima,
                case when left(a.kd_rek6,1)=5 then SUM (isnull(a.nilai,0)) end as keluar
                FROM
                trsp3b_blud a 
                WHERE a.kd_skpd = '$lcskpd' and a.tgl_sp3b <= '$ctgl_1'
                group by left(a.kd_rek6,1))c
                    ")->row();
                
            $nilai_saldo = $sql1->nilai;
            $n_saldo = $sql1->nilai;    
                
        
        if($ctgl_1<='2023-1-31'){
                $nilai_saldo = $saldo; 
                $ketsaldo = "Awal";
        }else{
                $nilai_saldo = $saldo+$n_saldo; 
                $ketsaldo = "Lalu";    
        }
            
        
        $skpd = $this->tukd_model->get_nama($lcskpd,'nm_skpd','ms_skpd','kd_skpd');
        //$nmpusk = $this->tukd_model->get_nama($nmskpd,'nm_skpd','ms_skpd','kd_skpd');
        // $cekno_lpjj = $this->db->query("select * from trhsp3b_blud where no_sp3b='$nosp3b'")->row();
        // $no_lpjj = $cekno_lpjj->no_lpj;

        $cek = "SELECT
            a.no_sp2b as no_sp2b, 
            a.tgl_sp2b as tgl_sp2b,
            b.nm_skpd as nm_skpd
        FROM
            trhsp3b_blud a inner join 
            ms_skpd_blud b on a.kd_skpd = b.kd_skpd 
        WHERE
            a.kd_skpd = '$lcskpd' 
            AND a.tgl_sp3b BETWEEN '$ctgl_1' and '$ctgl_2'" ;

        $sqlcek=$this->db->query($cek);
        foreach ($sqlcek->result() as $rowcek)
        {
            $nosp3b     = $rowcek->no_sp2b;
            $tglsp3b    = $rowcek->tgl_sp3b;
            $nmskpd     = $rowcek->nm_skpd ;
        }


        $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$lcskpd'";
                $sqlsclient=$this->db->query($sqlsc);
                foreach ($sqlsclient->result() as $rowsc)
                {
                    $kab     = $rowsc->kab_kota;
                    $prov     = $rowsc->provinsi;
                    $daerah  = $rowsc->daerah;
                    $thn     = $rowsc->thn_ang;
                }
        $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$nip2'";
                $sqlttd=$this->db->query($sqlttd1);
                foreach ($sqlttd->result() as $rowttd)
                {
                    $nip=$rowttd->nip;                    
                    $nama= $rowttd->nm;
                    $jabatan  = $rowttd->jab;
                    $pangkat  = $rowttd->pangkat;
                }       
        
            $cRet ='<TABLE style="border-collapse:collapse; font-size:14px" width="100%" border="1" cellspacing="0" cellpadding="1" align=center>
                    <TR>
                        <TD align="center" ><b>'.$prov.' <br>
                                            <b>DINAS KESEHATAN</b> <br>
                                                SURAT PERMINTAAN PENGESAHAN PENDAPATAN DAN BELANJA (SP3B) BLUD</b><br>
                                            Tanggal : '.$this->tukd_model->tanggal_format_indonesia($tglsp3b).' &nbsp;&nbsp; Nomor : '.$nosp3b.'     
                                                  
                        </TD>
                    </TR>                   
                    </TABLE>';          
            $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                    <TR>
                        <TD align="left" >Kepala SKPD Dinas Kesehatan Kabupaten Sanggau memohon kepada : </TD>                     
                    </TR>                   
                    </TABLE>';
            $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                    <TR>
                        <TD align="left" >Bendahara Umum Daerah Selaku PPKD </TD>                       
                    </TR>                   
                    </TABLE>';                    
    /*            untuk saldo awal*/
    $sql="SELECT sum(isnull(c.terima,0)) terima,sum(isnull(c.keluar,0)) keluar from(
                SELECT
                case when left(a.kd_rek6,1)=4 then SUM (isnull(a.nilai,0)) end as terima,
                case when left(a.kd_rek6,1)=5 then SUM (isnull(a.nilai,0)) end as keluar
                FROM
                trsp3b_blud a inner join trhsp3b_blud b on a.kd_skpd = b.kd_skpd and a.no_sp3b = b.no_sp3b AND a.tgl_sp3b = b.tgl_sp3b
                WHERE a.kd_skpd = '$lcskpd'
                AND b.tgl_sp3b BETWEEN '$ctgl_1' and '$ctgl_2' 
                AND a.tgl_sp3b BETWEEN '$ctgl_1' and '$ctgl_2' 
                /*AND no_sp3b = '$nosp3b'*/ 
                group by left(a.kd_rek6,1))c";
            $exe=$this->db->query($sql)->row();
            $pendapatan=$exe->terima;
            $belanja=$exe->keluar;
            //echo $nilai_saldo;
            $saldox=$nilai_saldo+$pendapatan-$belanja;

            /*untuk header*/
    $sqlxx="SELECT sum(isnull(c.terima,0)) terima,sum(isnull(c.keluar,0)) keluar from(
                SELECT
                case when left(a.kd_rek6,1)=4 then SUM (isnull(a.nilai,0)) end as terima,
                case when left(a.kd_rek6,1)=5 then SUM (isnull(a.nilai,0)) end as keluar
                FROM
                trsp3b_blud a inner join trhsp3b_blud b on a.kd_skpd = b.kd_skpd and a.no_sp3b = b.no_sp3b AND a.tgl_sp3b = b.tgl_sp3b
                WHERE a.kd_skpd = '$lcskpd'
                AND b.tgl_sp3b BETWEEN '$ctgl_1' and '$ctgl_2' 
                AND a.tgl_sp3b BETWEEN '$ctgl_1' and '$ctgl_2' 
                /*AND no_sp3b = '$nosp3b'*/
                group by left(a.kd_rek6,1))c";
            $exex=$this->db->query($sqlxx)->row();
            $pendapatan2=$exex->terima;
            $belanja2=$exex->keluar;
            
                            
            $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                    <TR>
                        <TD align="left" width="100%" colspan="3">Agar mengesahkan dan membukukan pendapatan dan belanja dana BLUD sejumlah :</TD>                      
                    </TR>   
                    <TR>
                        <TD align="left" width="10%"></TD>                      
                        <TD align="left" width="25%">1. &nbsp; Saldo '.$ketsaldo.'</TD>                     
                        <TD align="left" width="65%">Rp. '.number_format($nilai_saldo,'2',',','.').'</TD>                       
                    </TR>
                    <TR>
                        <TD align="left" width="10%"></TD>                      
                        <TD align="left" width="25%">2. &nbsp; Pendapatan</TD>                      
                        <TD align="left" width="65%">Rp. '.number_format($pendapatan2,'2',',','.').'</TD>                     
                    </TR>   
                    <TR>
                        <TD align="left" width="10%"></TD>                      
                        <TD align="left" width="25%">3. &nbsp; Belanja</TD>                     
                        <TD align="left" width="65%">Rp. '.number_format($belanja2,'2',',','.').'</TD>                     
                    </TR>   
                    <TR>
                        <TD align="left" width="10%"></TD>                      
                        <TD align="left" width="25%">4. &nbsp; Saldo Akhir</TD>                     
                        <TD align="left" width="65%">Rp. '.number_format($saldox,'2',',','.').'</TD>                      
                    </TR>   
                    </TABLE>';  
            $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                    <TR>
                        <TD align="left" >Dari tanggal <b>'.($this->tukd_model->tanggal_format_indonesia($ctgl_1)).'</b> sampai dengan <b>'.($this->tukd_model->tanggal_format_indonesia($ctgl_2)).'</b></TD>                        
                        <TD align="left" >Tahun Anggaran : <b>'.$thn.'</b></TD>                     
                    </TR>                   
                    </TABLE>';  
                    
            $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                    <TR>
                        <TD align="left" width="18%">Dasar Pengesahan</TD>                      
                        <TD align="left" width="17%">Urusan</TD>                    
                        <TD align="left" width="28%">Organisasi</TD>                        
                        <TD align="center" width="37%">Nama</TD>                           
                    </TR>
                    <TR>
                        <TD align="left" ></TD>                     
                        <TD align="left" >1.02 Kesehatan</TD>                   
                        <TD align="left" >1.02.1.02.01 Dinas Kesehatan</TD>                     
                        <TD align="center" rowspan="2"><b>'.$nmskpd.'</b></TD>                          
                    </TR>   
                    <TR>
                        <TD align="left" ></TD>                     
                        <TD align="left" >Upaya Kesehatan Masyarakat</TD>                 
                        <TD align="left" >Penyediaan Biaya Operasional dan Pemeliharaan</TD>                                                                       
                    </TR>   
                    </TABLE>';
            
            $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                    <TR>
                        <TD align="center" colspan="2" width="50%" style="border-collapse:collapse; border-right:solid 1px black;"><b>PENDAPATAN</b></TD>                       
                        <TD align="center" colspan="3" width="50%"><b>BELANJA</b></TD>                                          
                    </TR>
                    <TR>
                        <TD align="center" colspan="2" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="30%">
                        <b>Kode Rekening</b>                        
                        </TD>                       
                        <TD align="center" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="20%">
                        <b>Jumlah</b>
                        </TD>
                        <TD align="center" colspan="2" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="30%">
                        <b>Kode Rekening</b>
                        </TD>                       
                        <TD align="center" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="20%">
                        <b>Jumlah</b>
                        </TD>
                    </TR>';
                    
            
            
            $sql2="SELECT * from ( 
    SELECT 
    case when left(c.kd_rek6,1)=4 then kd_rek6 end as kd_pen,
    case when left(c.kd_rek6,1)=4 then nm_rek6 end as nm_pen,
    case when left(c.kd_rek6,1)=4 then sum(nilai) end as real_pen,

    case when left(c.kd_rek6,1)=5 then kd_rek6 end as kd_bel,
    case when left(c.kd_rek6,1)=5 then nm_rek6 end as nm_bel,
    case when left(c.kd_rek6,1)=5 then sum(nilai) end as real_bel
    from(


    select kd_rek6, nilai, nm_rek6 from trsp3b_blud a INNER JOIN trhsp3b_blud b ON a.no_sp3b=b.no_sp3b and a.kd_skpd=b.kd_skpd AND a.tgl_sp3b = b.tgl_sp3b
    WHERE b.tgl_sp3b BETWEEN '$ctgl_1' and '$ctgl_2'  AND a.kd_skpd='$lcskpd' 


    )c Group by kd_rek6, nm_rek6 ) xxx WHERE real_pen <> 0 or real_bel <> 0
    order by kd_pen desc

                    ";  //echo "$sql2";
                $jum_bel4=0; $jum_bel5=0;
                $sql2=$this->db->query($sql2);
                foreach ($sql2->result() as $row)
                {
                    $kd_rek4  = $row->kd_pen;
                    $nm_rek4  = $row->nm_pen;                    
                    $nilai4  = $row->real_pen; 
                    $kd_rek5  = $row->kd_bel;
                    $nm_rek5  = $row->nm_bel;                    
                    $nilai5  = $row->real_bel;                  
                    $jum_bel4=$jum_bel4+$nilai4;
                    $jum_bel5=$jum_bel5+$nilai5;


            
            $cRet .='
            <TR>
                        <TD align="center" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="10%">
                            '.$kd_rek4.'
                        </TD>                       
                        <TD align="right" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="25%"> 
                            '.$nm_rek4.'                     
                        </TD>
                        <TD align="right" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="15%">                      
                        '.number_format($nilai4,'2',',','.').'
                        </TD>
                        <TD align="center" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="10%">                 
                        '.$kd_rek5.'
                        </TD>   
                        <TD align="left" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="25%">                       
                        '.$nm_rek5.'
                        </TD>                   
                        <TD align="right" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="15%">                      
                        '.number_format($nilai5,'2',',','.').'
                        </TD>
                    </TR>';               
                }                          
            $cRet .='<TR>
                        <TD align="center" colspan="2" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="35%">
                        <b>Jumlah Pendapatan</b>
                        </TD>                       
                        <TD align="right" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="15%">
                        '.number_format($jum_bel4 ,'2',',','.').'
                        </TD>
                        <TD align="center" colspan="2" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="35%">
                        <b>Jumlah Belanja</b>
                        </TD>                       
                        <TD align="right" style="border-collapse:collapse; border-top:solid 1px black; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;" width="15%">
                        '.number_format($jum_bel5,'2',',','.').'
                        </TD>
                    </TR></TABLE>';         
                                                
            $cRet .='<TABLE style="font-size:13px;" width="100%" align="center">
                    <TR>
                        <TD width="50%" align="center" ></TD>
                        <TD width="50%" align="center" >Sanggau, '.$tanggal_ttd.'</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ></TD>
                        <TD width="50%" align="center" >Direktur<br>RSUD M.Th Djaman</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                        <TD width="50%" align="center" ><b>&nbsp;</TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ></TD>
                        <TD width="50%" align="center" ><b><u>'.$nama.'</u></b></TD>
                    </TR>
                    <TR>
                        <TD width="50%" align="center" ></TD>
                        <TD width="50%" align="center" >'.$nip.'</TD>
                    </TR>
                    </TABLE><br/>';

            $data['prev']= 'SP3B';
            switch ($ctk)
        {
            case 0;
            echo ("<title>SURAT SP3B</title>");
                echo $cRet;
                break;
            case 1;
                //$this->_mpdf('',$cRet,10,10,10,10,1,'');
                $this->support->_mpdf_margin('',$cRet,10,10,10,'L',0,'',$atas,$bawah,$kiri,$kanan);
            //$this->_mpdf_margin('',$cRet,10,10,10,'L',0,'',$atas,$bawah,$kiri,$kanan);
            break;
        }
    }

    function select_data1_lpj_ag($nosp2b='', $tglawl='', $tglakr='') {
        $nosp2b = $this->input->post('nosp2b');
        $kode = $this->input->post('kode');
        $tglawl = $this->input->post('tglawl');
        $tglakr = $this->input->post('tglakr');
        $sql = "SELECT a.no_sp2b,b.no_sp3b,b.no_bukti,b.tgl_sp3b,b.keterangan,b.kd_rek6,b.nm_rek6,SUM (b.nilai) AS nilai,a.kd_skpd,b.kd_sub_kegiatan,b.no_lpj FROM trhsp3b_blud a INNER JOIN trsp3b_blud b ON a.kd_skpd =b.kd_skpd AND a.no_sp3b =b.no_sp3b WHERE a.kd_skpd ='$kode' AND a.tgl_sp2b >='$tglawl' AND a.tgl_sp2b <='$tglakr' GROUP BY a.no_sp2b,b.no_sp3b,b.no_bukti,b.tgl_sp3b,b.keterangan,b.no_lpj,a.kd_skpd,b.kd_sub_kegiatan,b.kd_rek6,b.nm_rek6 ORDER BY kd_rek6";
        
        //  $sql = "SELECT b.no_sp3b,b.kd_rek6,b.nm_rek6,b.nilai,a.kd_skpd,b.kd_sub_kegiatan FROM trhsp3b_blud a INNER JOIN trsp3b_blud b ON a.kd_skpd =b.kd_skpd AND a.no_sp3b =b.no_sp3b AND a.tgl_sp3b =b.tgl_sp3b WHERE a.no_sp2b = '$nosp2b' ORDER BY kd_rek6";
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'idx' => $ii,
                        'no_sp3b'         => $resulte['no_sp3b'],
                        'kd_skpd'         => $resulte['kd_skpd'],
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],                                 
                        'kd_rek6'         => $resulte['kd_rek6'],  
                        'nm_rek6'         => $resulte['nm_rek6'],  
                        'nilai'           => number_format($resulte['nilai'])
                        );
                        $ii++;
        }
           echo json_encode($result);
        }
        
        
        function load_sum_lpj($lpj='',$pusk='') {
    
        $lpj = $this->input->post('lpj');//"900/45.A/UPTD Pusk.Kec.Ptk Tenggara/I/2017";
        $pusk = $this->input->post('kode');//"1.02.01.45";
        $result = array();
        $cek_skpd_jkn = $this->db->query("select b.kd_skpd,b.bulan from trhsp3b a left join trhlpj_pusk b on b.kd_skpd = a.kd_skpd and a.no_lpj = b.no_lpj
            where a.no_lpj='$lpj' and a.kd_skpd='$pusk'")->row();    
        $hasil_skpd_jkn = $cek_skpd_jkn->kd_skpd;
        $hasil_bulan_jkn = $cek_skpd_jkn->bulan;
        //if($hasil_skpd_jkn!=""){
        //}    
             if($hasil_bulan_jkn==1){
                
                $n = $this->db->query("select ISNULL(sld_awal,0) as sld_awal from ms_skpd_jkn where kd_skpd='$pusk'")->row();            
                $nilai_saldo = $n->sld_awal;
            }else{
                  $sql1=$this->db->query(" SELECT ISNULL(SUM(terima-keluar),0) as nilai FROM(
                        select SUM(nilai) as terima,0 keluar from TRHINLAIN_PUSK WHERE KD_SKPD='$pusk' AND jns_beban not in ('10') AND MONTH(TGL_BUKTI)<'$hasil_bulan_jkn'
                        UNION ALL
                        select ISNULL(SUM(sld_awal),0) as terima,0 keluar from ms_skpd_jkn WHERE KD_SKPD='$pusk'
                        UNION ALL                   
                        select 0 terima,SUM(nilai) keluar from TRHOUTLAIN_PUSK WHERE KD_SKPD='$pusk' AND MONTH(TGL_BUKTI)<'$hasil_bulan_jkn'
                        UNION ALL                   
                        select 0 terima,SUM(nilai) keluar from trhtransout_pusk a join
                        trdtransout_pusk b on b.no_bukti = a.no_bukti and a.kd_skpd = b.kd_skpd
                        where a.kd_skpd='$pusk' and month(a.tgl_bukti)<'$hasil_bulan_jkn'    
                        
                        ) a
                        ")->row();    
                $nilai_saldo = $sql1->nilai;          
                         
            }       
                    
            $sql = " SELECT * FROM (
                    SELECT nilai terima, 0 keluar from TRHINLAIN_PUSK WHERE kd_skpd='$pusk' AND jns_beban not in ('10') AND MONTH(TGL_BUKTI)='$hasil_bulan_jkn'
                    UNION ALL
                    SELECT 0 terima, nilai keluar from TRHOUTLAIN_PUSK WHERE kd_skpd='$pusk' AND MONTH(TGL_BUKTI)='$hasil_bulan_jkn'
                    UNION ALL
                    SELECT 0 terima, b.nilai keluar FROM trhtransout_pusk a INNER JOIN trdtransout_pusk b 
                    ON a.kd_skpd = b.kd_skpd and a.no_bukti=b.no_bukti 
                    WHERE a.kd_skpd='$pusk' AND MONTH(a.tgl_kas)='$hasil_bulan_jkn'                 
                    ) a ";        
                    
            $hasil = $this->db->query($sql);
            $lcno=0;
            $sisa=$nilai_saldo;        
            $jumlah_terima=0;
            $jumlah_keluar=0;
            $row = array();
           foreach ($hasil->result_array() as $rows){
             $terima = $rows['terima'];
             $keluar = $rows['keluar'];
             
             $jumlah_terima=$jumlah_terima+$terima;
             $jumlah_keluar=$jumlah_keluar+$keluar;
             $sisa=$sisa+$terima-$keluar;                               
            }   
                $row[] = array(                                                 
                            'idx' => '01',
                            'csaldo_awal' => $nilai_saldo,
                            'cterima' => $terima,
                            'ckeluar' => $keluar,
                            'cjumlah' => $sisa                                               
                            );                                
               
               echo json_encode($row);     
        }
        
        function setuju_sp3b() {
        $sp3b = $this->input->post('no_sp3b');
        $sp2b = $this->input->post('no_sp2b');
        $number_sp2b = $this->input->post('number_sp2b');
        $tgl_sah = $this->input->post('tgl_sah');
        $kdskpd = $this->input->post('kd_skpd');
        $sql = "UPDATE trhsp3b_blud SET number_sp2b='$number_sp2b',status_bud='1',tgl_sp2b='$tgl_sah',no_sp2b='$sp2b' WHERE no_sp3b='$sp3b' AND kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql);  
        if ($asg > 0){      
                        echo '2';
                        exit();
                   } else {
                        echo '0';
                        exit();
                   }
        }
        
        function batalsetuju_sp3b() {
        $sp3b = $this->input->post('no_sp3b');
        $sp2b = $this->input->post('no_sp2b');
        $tgl_sah = $this->input->post('tgl_sah');
        $kdskpd = $this->input->post('kd_skpd');
        $number_sp2b = $this->input->post('number_sp2b');
        $sql = "UPDATE trhsp3b_blud SET number_sp2b='0',status_bud='0',tgl_sp2b='',no_sp2b='' WHERE no_sp3b='$sp3b' AND kd_skpd='$kdskpd'";
        $asg = $this->db->query($sql);  
        if ($asg > 0){      
                        echo '2';
                        exit();
                   } else {
                        echo '0';
                        exit();

                   }
        }
        
        function cetak_sp2b_fktp(){        
            $nomor1 = str_replace('123456789','/',$this->uri->segment(6));
            $nomor2 = str_replace('123456789','/',$this->uri->segment(8));
            // $no_sp2b = $this->uri->segment(8);
            $no_sp2b  = str_replace('123456789',' ',$nomor2);
            $nomorsp3b  = str_replace('123456789',' ',$nomor1);
            $pusk  = $this->uri->segment(4);
            $lcskpd = substr($pusk,0,7).".00";
            $ttd1   = str_replace('a',' ',$this->uri->segment(3));        
            $ctk    =   $this->uri->segment(5);
            $sclient = $this->akuntansi_model->get_sclient();
            $ketsaldo = ''; 
            $nilai_saldo = 0;                                       
            $skpd = $this->tukd_model->get_nama($pusk,'nm_skpd','ms_skpd','kd_skpd');
            //$nmpusk = $this->tukd_model->get_nama($pusk,'nm_bidang','ms_bidang','kd_bidskpd');        
            $sqlsc="SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient WHERE kd_skpd='$pusk'";
                     $sqlsclient=$this->db->query($sqlsc);
                     foreach ($sqlsclient->result() as $rowsc)
                     {
                        $kab     = $rowsc->kab_kota;
                        $prov     = $rowsc->provinsi;
                        $daerah  = $rowsc->daerah;
                        $thn     = $rowsc->thn_ang;
                     }
            $sqlttd1="SELECT nama as nm,nip as nip,jabatan as jab,pangkat FROM ms_ttd where nip='$ttd1' and (kode='BUD' or kode='PA')";
                     $sqlttd=$this->db->query($sqlttd1);
                     foreach ($sqlttd->result() as $rowttd)
                    {
                        $nip="NIP. ".$rowttd->nip;                    
                        $nama= $rowttd->nm;
                        $jabatan  = $rowttd->jab;
                        $pangkat  = $rowttd->pangkat;
                    }       
                    
            $tgll = $this->db->query("select tgl_sp2b,no_lpj,no_sp3b,no_sp2b,tgl_sp3b,tgl_awal,tgl_akhir,bulan from trhsp2b_blud where kd_skpd='$pusk' and no_sp2b = '$nomorsp3b'")->row();            
                $tgl_sp2b = $tgll->tgl_sp2b;
                $tgl_sp2b = $this->tukd_model->tanggal_format_indonesia($tgl_sp2b);
                $no_lpjj =  $tgll->no_lpj;
                $no_sp3bb = $tgll->no_sp3b;
                $tgl_sp3b = $tgll->tgl_sp3b;
                $tgl_sp3b1 = $this->tukd_model->tanggal_format_indonesia($tgl_sp3b);
                $nbulan   = $tgll->bulan;
                $no_sp2b    = $tgll->no_sp2b;
                $tgl_awl  = $tgll->tgl_awal;
                $tgl_awl1 = $this->tukd_model->tanggal_format_indonesia($tgl_awl);  

                    $n = $this->db->query("SELECT sum(sld_awal) sld_awal from (
                        SELECT ISNULL(saldo_lalu,0) as sld_awal from ms_skpd_blud where kd_skpd='$pusk' 
                        union all
                        select 0 ) okei")->row();            
                $saldo = $n->sld_awal;
                    $sql1 = $this->db->query("SELECT sum(isnull(c.terima,0))-sum(isnull(c.keluar,0)) nilai from(
                        SELECT
                        case when left(a.kd_rek6,1)=4 then SUM (isnull(a.nilai,0)) end as terima,
                        case when left(a.kd_rek6,1)=5 then SUM (isnull(a.nilai,0)) end as keluar
                        FROM
                        trsp3b_blud a 
                        WHERE a.kd_skpd = '$pusk' and a.tgl_sp3b <= '$tgl_awl' 
                        group by left(a.kd_rek6,1))c")->row(); 

                    $nilai_saldo = $sql1->nilai;
                    $n_saldo = $sql1->nilai;
                              

                if($tgl_awl  == '2023-01-01'){
                        $nilai_saldo = $saldo; 
                        $ketsaldo = "Awal";
                }else{
                        $nilai_saldo = $saldo+$n_saldo; 
                        $ketsaldo = "Lalu";    
                }

                if ($ctk == 0) {
                    //$url_logo = base_url();
                    $cRet ="<TABLE style=\"border-collapse:collapse; font-size:14px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=center>
                    <TR>
                    <td align=\"center\" style=\"border-right:hidden\">
                               <img src=\"".base_url()."/image/logo-kabupaten.png\"  width=\"45\" height=\"65\" />
                              </td>
  
                        <TD align=\"center\" ><b>PEMERINTAH KABUPATEN MELAWI<br>
                                                SURAT PENGESAHAN PENDAPATAN DAN BELANJA FKTP</b>
                        </TD>
                    </TR>                   
                    </TABLE>";        
                }else{
                    $cRet ="<TABLE style=\"border-collapse:collapse; font-size:14px\" width=\"100%\" border=\"1\" cellspacing=\"0\" cellpadding=\"1\" align=center>
                    <TR>
                    <td align=\"center\" style=\"border-right:hidden\">
                         <img src=\"" . FCPATH . "/image/logo-kabupaten.png\"  width=\"40\" height=\"60\" />
                              </td>
  
                        <TD align=\"center\" ><b>PEMERINTAH KABUPATEN MELAWI<br>
                                                SURAT PENGESAHAN PENDAPATAN DAN BELANJA FKTP</b>
                        </TD>
                    </TR>                   
                    </TABLE>";        
                };     
                $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-top:solid 0px black;  border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                        <TR>
                            <TD colspan="3" style="border-right:solid 1px black;"><br></TD>              
                        </TR>
                        <TR>
                            <TD align="left" width="10%">No. SP3B </TD>                        
                            <TD align="left" width="1%">: </TD>
                            <TD align="left" width="38%" style="border-right:solid 1px black;">'.$no_sp3bb.'</TD>
                            <TD align="left" width="15%">&nbsp;&nbsp;Nama BUD / Kuasa</TD>
                            <TD align="left">: '.$nama.'</TD>
                            </TR>
                        <TR>
                            <TD align="left" width="10%">Tanggal</TD>                        
                            <TD align="left" width="1%">: </TD>
                            <TD align="left" width="38%" style="border-right:solid 1px black;">'.$tgl_sp3b1.'</TD> 
                            <TD align="left" width="15%">&nbsp;&nbsp;Nomor</TD>
                            <TD align="left">: '.$no_sp2b.'</TD>                       
                        </TR>
                        <TR>
                            <TD align="left" width="10%">SKPD</TD>                       
                            <TD align="left" width="1%">: </TD>
                            <TD align="left" width="38%" style="border-right:solid 1px black;">RSUD Melawi</TD>
                            <TD align="left" width="15%">&nbsp;&nbsp;Tanggal</TD>
                            <TD align="left">: '.$tgl_sp2b.'</TD>
                        </TR>   
                        <TR>
                            <TD align="left" width="10%">FKTP</TD>                       
                            <TD align="left" width="1%">: </TD>
                            <TD align="left" width="38%" style="border-right:solid 1px black;">RSUD Melawi</TD>
                            <TD align="left" width="15%">&nbsp;&nbsp;Tahun Anggaran</TD>
                            <TD align="left">: '.$thn.'</TD>
                        </TR>
                        <TR>
                            <TD colspan="3" style="border-right:solid 1px black;"><br></TD>
                            <TD ><br></TD>
                        </TR>
                                                                                                    
                        </TABLE>';          
                        
                    //  $sql = " SELECT * FROM (
                   	// SELECT
                    // case when left(b.kd_rek6,1)=4 then SUM (isnull(b.nilai,0)) end as terima,
                    // case when left(b.kd_rek6,1)=5 then SUM (isnull(b.nilai,0)) end as keluar
                    // FROM trhsp3b_blud a	JOIN trsp3b_blud b ON b.no_sp3b = a.no_sp3b AND a.kd_skpd = b.kd_skpd WHERE	a.kd_skpd= '$pusk' AND 
                    // MONTH ( a.tgl_sp3b ) <= '$nbulan' group by left(b.kd_rek6,1)             
                    // ) a ";
                     $sql = "SELECT * FROM(
                        SELECT
                        case when left(a.kd_rek6,1)=4 then SUM (isnull(a.nilai,0)) end as terima,
                        case when left(a.kd_rek6,1)=5 then SUM (isnull(a.nilai,0)) end as keluar
                        FROM
                        trsp2b_blud a 
                        WHERE a.kd_skpd = '$pusk' and  a.nosp2b ='$no_sp2b'
                        group by left(a.kd_rek6,1))c";   
   
            $hasil = $this->db->query($sql);
            $lcno=0;
            $sisa=$nilai_saldo;        
            $jumlah_terima=0;
            $jumlah_keluar=0;
           foreach ($hasil->result() as $row){
             $terima = $row->terima;
             $keluar = $row->keluar;
             $jumlah_terima=$jumlah_terima+$terima;
             $jumlah_keluar=$jumlah_keluar+$keluar;
             $sisa=$sisa+$terima-$keluar;         
             $terima1 = empty($terima) || $terima == 0 ? '' :number_format($terima,'2','.',',');
             $keluar1 = empty($keluar) || $keluar == 0 ? '' :number_format($keluar,'2','.',',');
            }
            
            
            //$sql_rtgs = $this->db->query("SELECT nilai from TRHOUTLAIN_PUSK WHERE kd_skpd='$pusk' AND MONTH(TGL_BUKTI)='$nbulan'")->row();
            //$nilai_rtgs = $sql_rtgs->nilai;                     
                                
                    // $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                    //     <TR>
                    //         <TD align="left" >Untuk bulan : <b>'.strtoupper($this->tukd_model->getBulan($nbulan)).'</b></TD>                        
                    //         <TD align="left" >Tahun Anggaran : <b>'.$thn.'</b></TD>                     
                    //     </TR>                   
                    //     </TABLE>';  
                            
                $cRet .='<TABLE style="border-collapse:collapse; border-top:none; border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black; font-size:13px;" width="100%">
                        <TR>
                            <TD align="left" width="100%" colspan="3"><br></TD>                     
                        </TR>
                        <TR>
                            <TD align="left" width="100%" colspan="3">Telah disahkan Pendapatan dan Belanja sejumlah :</TD>                       
                        </TR>   
                        <TR>
                            <TD align="left" width="20%"></TD>                      
                            <TD align="left" width="25%">1. &nbsp; Saldo '.$ketsaldo.'</TD>                     
                            <TD align="left" width="65%">Rp. '.number_format($nilai_saldo,'2','.',',').'</TD>                       
                        </TR>
                        <TR>
                            <TD align="left" width="20%"></TD>                      
                            <TD align="left" width="25%">2. &nbsp; Pendapatan</TD>                      
                            <TD align="left" width="65%">Rp. '.number_format($jumlah_terima,'2','.',',').'</TD>                     
                        </TR>   
                        <TR>
                            <TD align="left" width="20%"></TD>                      
                            <TD align="left" width="25%">3. &nbsp; Belanja</TD>                     
                            <TD align="left" width="65%">Rp. '.number_format($jumlah_keluar,'2','.',',').'</TD>                     
                        </TR>   
                        <TR>
                            <TD align="left" width="20%"></TD>                      
                            <TD align="left" width="25%">4. &nbsp; Saldo Akhir</TD>                     
                            <TD align="left" width="65%">Rp. '.number_format($sisa,'2','.',',').'</TD>                      
                        </TR>   
                        <TR>
                            <TD align="left" width="100%" colspan="3"><br></TD>                                                                     
                        </TR>
                        </TABLE>';  
            
                                                    
                $cRet .='<TABLE style="border-collapse:collapse;border-left:solid 1px black; border-right:solid 1px black; border-bottom:solid 1px black;font-size:13px;" width="100%" align="center">
                        <TR>
                            <TD align="left" width="100%" colspan="3"><br></TD>                     
                        </TR>
                        <TR>
                            <TD width="50%" align="center" ></TD>
                            <TD width="50%" align="center" >Melawi, '.$tgl_sp2b.'</TD>
                        </TR>
                        <TR>
                            <TD width="50%" align="center" ></TD>
                            <TD width="50%" align="center" >BENDAHARA UMUM DAERAH</TD>
                        </TR>
                        <TR>
                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                        </TR>
                         <TR>
                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                        </TR>
                         <TR>
                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                            <TD width="50%" align="center" ><b>&nbsp;</TD>
                        </TR>
                        <TR>
                            <TD width="50%" align="center" ></TD>
                            <TD width="50%" align="center" ><b><u>'.$nama.'</u></b></TD>
                        </TR>
                        <TR>
                            <TD width="50%" align="center" ></TD>
                            <TD width="50%" align="center" >'.$nip.'</TD>
                        </TR>
                        <TR>
                            <TD align="left" width="100%" colspan="3"><br></TD>                     
                        </TR>
                        </TABLE>';
    
            $atas='5';
            $bawah='5';
             $kiri='5';   
             $kanan='5';
    
                $data['prev']= 'SP2B';
                 switch ($ctk)
            {
                case 0;
                echo ("<title>SURAT SP2B</title>");
                    echo $cRet;
                    break;
                case 1;
                    //$this->_mpdf('',$cRet,10,10,10,10,1,'');
                    $this->_mpdf_sp2d2('',$cRet,10,5,5,'0');            
                   break;
            }
        }
        
        function _mpdf_sp2d2($judul='',$isi='',$lMargin='',$rMargin='',$font=0,$orientasi='') {
        
            ini_set("memory_limit","-1");
            ini_set("MAX_EXECUTION_TIME","-1");
            $this->load->library('mpdf');
            
            
            $this->mpdf->defaultheaderfontsize = 6; /* in pts */
            $this->mpdf->defaultheaderfontstyle = BI;   /* blank, B, I, or BI */
            $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */
    
            $this->mpdf->defaultfooterfontsize = 6; /* in pts */
            $this->mpdf->defaultfooterfontstyle = BI;   /* blank, B, I, or BI */
            $this->mpdf->defaultfooterline = 1; 
            $this->mpdf->SetLeftMargin = $lMargin;
            $this->mpdf->SetRightMargin = $rMargin;
            $jam = date("H:i:s");
            $this->mpdf = new mPDF('utf-8', array(215.9,330.2),$size); //folio
            $this->mpdf->AddPage($orientasi,'','',1,1,$lMargin,$rMargin,15,5);
            //$this->mpdf->SetFooter("Printed on Simakda || Halaman {PAGENO} ");
            if (!empty($judul)) $this->mpdf->writeHTML($judul);
            $this->mpdf->writeHTML($isi);      
            $this->mpdf->Output();
        }
    
    
    
    
        function _mpdf_margin($judul='',$isi='',$lMargin=10,$rMargin=10,$font='',$orientasi='',$hal='', $fonsize='',$atas='', $bawah='', $kiri='', $kanan='') {
                    
    
            ini_set("memory_limit","-1M");
            ini_set("MAX_EXECUTION_TIME","-1");
            $this->load->library('mpdf');
            //$this->mpdf->SetHeader('||Halaman {PAGENO} /{nb}');
            
            
            $this->mpdf->defaultheaderfontsize = 10;    /* in pts */
            $this->mpdf->defaultheaderfontstyle = I;    /* blank, B, I, or BI */
            $this->mpdf->defaultheaderline = 1;     /* 1 to include line below header/above footer */
    
            $this->mpdf->defaultfooterfontsize = 3; /* in pts */
            $this->mpdf->defaultfooterfontstyle = I;    /* blank, B, I, or BI */
            $this->mpdf->defaultfooterline = 1; 
            $sa=1;
            $tes=0;
            if ($hal==''){
            $hal1=1;
            } 
            if($hal!==''){
            $hal1=$hal;
            }
            if ($fonsize==''){
            $size=12;
            }else{
            $size=$fonsize;
            } 
            
            $this->mpdf = new mPDF('utf-8', array(215,330),$size); //folio
            $this->mpdf->AddPage($orientasi,'',$hal,'1','off',$kiri,$kanan,$atas,$bawah);
            if ($hal==''){
                $this->mpdf->SetFooter("");
            }
            else{
                $this->mpdf->SetFooter("Printed on Simakda SKPD || Halaman {PAGENO}  ");
            }
            if (!empty($judul)) $this->mpdf->writeHTML($judul);
            $this->mpdf->writeHTML($isi);         
            $this->mpdf->Output();
                   
        }
    
        function jumlah_belanjasp3b() {           
            $kriteria = $this->input->post('no');        
            $skpd = $this->input->post('skpd');//$this->session->userdata('kdskpd');
            
            $sql = "SELECT a.*, left(a.kd_rek6,7) as rek, (select nm_rek6 from ms_rek6 where kd_rek6 = left(a.kd_rek6,7)) as nm_rek, a.nm_rek6
            from trsp3b_blud a where a.no_sp3b = '$kriteria' and left(a.kd_skpd,17)=left('$skpd',17)  order by a.no_sp3b";
            
            $query1 = $this->db->query($sql);  
            $result = array();
            $ii = 0;
            $nilai=0;
            foreach($query1->result_array() as $resulte)
            { 
                
                $nilai=$nilai+$resulte['nilai'];
            }
                $result[] = array(
                            'nilai' => $nilai
                            );           
            echo json_encode($result);
            $query1->free_result();
               
        }    

    function load_sp2b(){
        $result = array();
       $row = array();
       $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
       $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
       $offset = ($page-1)*$rows;
      $kriteria = '';
       $kriteria = $this->input->post('cari');
       $and= '';
       $where= '';
       //$id  = $this->session->userdata('pcUser');        
       //$where =" AND kd_skpd IN (SELECT kd_skpd FROM user_bud WHERE user_id='$id') ";
       if ($kriteria <> ''){                               
           $and=" AND (upper(no_sp3b) like upper('%$kriteria%') or upper(number_sp2b) like '%$kriteria%' or upper(no_sp2b) like '%$kriteria%' or upper(kd_skpd) like '%$kriteria%')";            
           $where=" where (upper(no_sp3b) like upper('%$kriteria%') or upper(number_sp2b) like '%$kriteria%' or upper(no_sp2b) like '%$kriteria%' or upper(kd_skpd) like '%$kriteria%')";            
       }
       
       $sql = "SELECT count(*) as tot from trhsp2b_blud $where";
       $query1 = $this->db->query($sql);
       $total = $query1->row();
       
       
       $sql = " SELECT TOP $rows *,(SELECT a.nm_skpd FROM ms_skpd a where a.kd_skpd=b.kd_skpd) as nm_skpd FROM trhsp2b_blud b WHERE  
       no_sp3b NOT IN (SELECT TOP $offset no_sp3b FROM trhsp2b_blud $where order by tgl_sp3b, no_sp3b) $and
       order by tgl_sp3b, no_sp3b";
       $query1 = $this->db->query($sql);  
       $result = array(); 
       $ii = 0;
       foreach($query1->result_array() as $resulte) { 
       $row[] = array(
           'id' => $ii,
           'kd_skpd'    => $resulte['kd_skpd'],      
           'nm_skpd'    => $resulte['nm_skpd'],                          
           'ket'   => $resulte['keterangan'],
           'no_lpj'   => $resulte['no_lpj'],
        //    'no_sp3b'   => $resulte['no_sp3b'],
        //    'tgl_sp3b'      => $resulte['tgl_sp3b'],
           'no_sp2b'   => $resulte['no_sp2b'],
           'tgl_sp2b'      => $resulte['tgl_sp2b'],
           'revisi'      => $resulte['revisi_ke'],
           'tglawl'       => $resulte['tgl_awal'],
           'tglakr'      => $resulte['tgl_akhir'],
           'status'      => $resulte['status'],
           'bulan' =>intval($resulte['bulan']),                                                
           'status_bud'      => $resulte['status_bud'],
           'skpd'    => $resulte['skpd'],         
           'total'           => number_format($resulte['total'])                                     
           );
           $ii++;
       }
        $result["total"] = $total->tot;
       $result["rows"] = $row; 
       echo json_encode($result);
      $query1->free_result();
       
   }
   
   function no_urut_sp2b(){
   $query1 = $this->db->query("select COUNT(number_sp2b) as nomor from trhsp3b_blud where number_sp2b not in ('0')");
       $ii = 0;
       foreach($query1->result_array() as $resulte)
       { 
           $urut = $resulte['nomor'];
           $result = array(
                       'id' => $ii,        
                       'no_urut' => $urut+1
                       );
                       $ii++;
       }
       
       echo json_encode($result);
       $query1->free_result();   
   }

    function config_skpd(){
        $skpd     = $this->session->userdata('kdskpd');
        $jnsang = $this->cek_anggaran_model->cek_anggaran($skpd);
        $sql = "SELECT a.kd_skpd,a.nm_skpd,b.jns_ang FROM ms_skpd a LEFT JOIN trhrka b ON a.kd_skpd=b.kd_skpd WHERE a.kd_skpd ='1.02.0.00.0.00.01.0002' AND b.jns_ang ='$jnsang'";
        $query1 = $this->db->query($sql);  
        
        $test = $query1->num_rows();
        
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'jns_ang' => $resulte['jns_ang']
                        // 'status_ubah' => $resulte['status_ubah'],
                        // 'status_rancang' => $resulte['status_rancang'],
                        // 'status_sempurna' => $resulte['status_sempurna']
                        );
                        $ii++;
        }
        

        
        
        echo json_encode($result);
        $query1->free_result();   
    }

    function load_ttd($ttd){
        $kd_skpd = $this->session->userdata('kdskpd');
		//$kode_skpd = $this->input->post('kdskpd');
		$sql = "SELECT * FROM ms_ttd WHERE kd_skpd= '1.02.0.00.0.00.01.0002' and kode in ('$ttd','PA')";
		
        $mas = $this->db->query($sql);
        $result = array();
        $ii = 0;        
        foreach($mas->result_array() as $resulte)
        { 
           
            $result[] = array(
                        'id' => $ii,        
                        'nip' => $resulte['nip'],  
                        'nama' => $resulte['nama'],
                        'jabatan' => $resulte['jabatan']
                        );
                        $ii++;
        }           
           
        echo json_encode($result);
        $mas->free_result();
    	   
	}

    function load_sp3b_blud() {
        $kd_skpd     = $this->session->userdata('kdskpd');         
        $par = "a.skpd='$kd_skpd'";
        $par2 = "skpd='$kd_skpd'";        
        
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page-1)*$rows;        
        $kriteria = $this->input->post('cari');
        $where ='';
        if ($kriteria <> ''){                               
            $where=" and (upper(a.no_sp3b) like upper('%$kriteria%') or a.tgl_sp3b like '%$kriteria%' or a.kd_skpd like'%$kriteria%' or
            upper(a.keterangan) like upper('%$kriteria%')) ";            
        }
       
        $sql = "SELECT COUNT(*) as total FROM trhsp3b_blud a where $par $where " ;
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total; 
        $query1->free_result();
        
        
        //$sql = "SELECT  * from tr_panjar where kd_skpd='$kd_skpd'";
        
        
       echo $sql = "
        SELECT top $rows a.*,(SELECT nm_skpd FROM ms_skpd_blud WHERE kd_skpd = a.kd_skpd) AS nm_skpd,(SELECT nm_skpd FROM ms_skpd_blud WHERE kd_skpd = a.kd_skpd) AS nm_skpd2 from trhsp3b_blud a where $par 
        $where  AND a.no_sp3b NOT IN (SELECT top $offset no_sp3b FROM trhsp3b_blud where $par2 ORDER BY tgl_sp3b, no_sp3b)order by a.tgl_sp3b, a.no_sp3b
        ";
        
        $query1 = $this->db->query($sql); 
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
                                
            $row[] = array( 
                        'id' => $ii,        
                        'no_sp3b' => $resulte['no_sp3b'],
                        'tgl_sp3b' => $resulte['tgl_sp3b'],
                        'kd_skpd' => $resulte['kd_skpd'],
                        'keterangan' => $resulte['keterangan'],    
                        'total' =>  number_format($resulte['total']),                        
                        'status' => $resulte['status'],                     
                        'no_lpj' => $resulte['no_lpj'],
                        'nm_skpd' => $resulte['nm_skpd'],
                        'nm_skpd2' => $resulte['nm_skpd2'],
                        'bulan' => intval($resulte['bulan']),
                        'status_bud' => $resulte['status_bud']
                        );
                        $ii++;
                }
       $result["rows"] = $row; 
        echo json_encode($result);
        $query1->free_result(); 
           
    } 

    function skpd_rsud() {
		//$kd_skpd = $this->session->userdata('kdskpd');
        $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd where kd_skpd = '1.02.0.00.0.00.01.0002'";
        $query1 = $this->db->query($sql);  
        $test = $query1->num_rows();
        $ii = 0;
        foreach($query1->result_array() as $resulte)
        { 
           
            $result = array(
                        'id' => $ii,        
                        'kd_skpd' => $resulte['kd_skpd'],  
                        'nm_skpd' => $resulte['nm_skpd'],  
                       
                        );
                        $ii++;
        }
           
        echo json_encode($result);
     $query1->free_result(); 	  
	}

	function load_data_transaksi_sp3b($dtgl1='',$dtgl2='',$kode='') {
        $dtgl1  = $this->input->post('tgl1');
        $dtgl2  = $this->input->post('tgl2');
		$kode = $this->input->post('kdskpd');
				   
     	$sql = "SELECT a.no_sp2b,b.no_sp3b,b.no_bukti,b.tgl_sp3b,b.keterangan,b.kd_rek6,b.nm_rek6,SUM (b.nilai) AS nilai,a.kd_skpd,b.kd_sub_kegiatan,b.no_lpj FROM trhsp3b_blud a INNER JOIN trsp3b_blud b ON a.kd_skpd =b.kd_skpd AND a.no_sp3b =b.no_sp3b AND a.tgl_sp3b =b.tgl_sp3b WHERE a.kd_skpd ='$kode' AND a.tgl_sp2b >='$dtgl1' AND a.tgl_sp2b <='$dtgl2' GROUP BY a.no_sp2b,b.no_sp3b,b.no_bukti,b.tgl_sp3b,b.keterangan,b.no_lpj,a.kd_skpd,b.kd_sub_kegiatan,b.kd_rek6,b.nm_rek6 ORDER BY kd_rek6";	   
        
        $query1 = $this->db->query($sql);  
        $result = array();
        $ii     = 0;
        foreach($query1->result_array() as $resulte)
        { 
            $result[] = array(
                        'idx' => $ii,
                        'no_sp2b'         => $resulte['no_sp2b'],
                        // 'no_sp3b'         => $resulte['no_sp3b'],
                        // 'tgl'             => $resulte['tgl_sp3b'],
                        'ket'             => $resulte['keterangan'],
                        'kd_skpd'         => $resulte['kd_skpd'],
                        'kd_sub_kegiatan' => $resulte['kd_sub_kegiatan'],                                 
                        'kd_rek6'         => $resulte['kd_rek6'],  
                        'nm_rek6'         => $resulte['nm_rek6'],  
                        'nilai'           => $resulte['nilai']
                        );
                        $ii++;
        }
           echo json_encode($result);
    }


    function cek_simpan(){
	    $nomor    = $this->input->post('nosp2b');
	    $tabel   = $this->input->post('tabel');
	    $field    = $this->input->post('field');
	    $kd_skpd  = $this->input->post('kode');
	    
		$hasil=$this->db->query(" select count(*) as jumlah FROM $tabel where $field='$nomor' and kd_skpd = '$kd_skpd'");
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

    function simpan_hsp2b(){
		
        $nosp3b      = $this->input->post('nosp3b');
        $nosp2b      = $this->input->post('nosp2b');
        // $tglsp3b     = $this->input->post('tglsp3b');
        $tglsp2b     = $this->input->post('tglsp2b');
        $bulan       = (int) explode('-', $tglsp2b)[1];
        $kdskpd      = $this->input->post('kd');
        $nmskpd      = $this->input->post('nm');
        $revisi      = $this->input->post('revisi');
        $tgl1        = $this->input->post('tgl1');
        $tgl2        = $this->input->post('tgl2');
        $cket        = $this->input->post('ket');
        $tot         = $this->input->post('total1');
        $usernm      = $this->session->userdata('pcNama');
        $last_update =  date('d-m-y H:i:s');

    	 $sql = "INSERT INTO trhsp2b_blud (no_sp3b,kd_skpd,keterangan,tgl_sp3b,status,tgl_awal,tgl_akhir,no_lpj,total,skpd,bulan,username,tgl_update,status_bud,no_sp2b,tgl_sp2b,number_sp2b, revisi_ke) values ('$nosp3b','$kdskpd','$cket','$tglsp2b','1','$tgl1','$tgl2','-','$tot','$kdskpd','$bulan','$usernm','$last_update','1','$nosp2b','$tglsp2b','5', $revisi)";
    	$query1 = $this->db->query($sql);
    					
                if($query1){
                    echo '2';
                }else{
                    echo '0';
                }
            }

     function dsimpan_lpj(){
	  
        $csql     = $this->input->post('sql');
		$cwaktu = date("Y-m-d H:i:s");		
		$user =  $this->session->userdata('pcNama'); 
        // $nosp2b      = $this->input->post('nosp2b'); 		
    
        $sql = "INSERT INTO trsp2b_blud (nosp2b,kd_rek6,nm_rek6,nilai,kd_skpd,kd_sub_kegiatan)";

        $asg = $this->db->query($sql . $csql);

                if($asg){
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }else{
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                }
            }

    function hapus_sp2b() {    
        $kd_skpd  = $this->input->post('kode');
        $nomor    = $this->input->post('nosp2b');

        $query = $this->db->query("DELETE from trhsp2b_blud where no_sp2b='$nomor'");
        $query = $this->db->query("DELETE from trsp2b_blud where nosp2b='$nomor'");
        if ($query) {
            echo '1';
        } else {
            echo '0';
        }
    }
}