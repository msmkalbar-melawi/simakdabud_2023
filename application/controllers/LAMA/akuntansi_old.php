<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Controller master data kegiatan
 */

class Akuntansi extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();

    }

    function rekal()
    {
        $data['page_title'] = 'REKAL TRANSAKSI';
        $this->template->set('title', 'REKAL TRANSAKSI');
        $this->template->load('template', 'akuntansi/rekal', $data);
    }
	
	function spj_terima()
	{
		$data['page_title']= 'SPJ PENERIMAAN';
        $this->template->set('title', 'SPJ Penerimaan');   
        $this->template->load('template','akuntansi/spj_terima',$data) ; 
	}
    
    function neraca_saldo()
    {
        $data['page_title']= 'Neraca Saldo';
        $this->template->set('title', 'Neraca Saldo');   
        $this->template->load('template','akuntansi/neraca_saldo',$data) ; 
    }

    function proses_rekal()
    {

        $this->db->query(" delete from trhrekal ");
        $this->db->query(" delete from trdrekal ");
        //sp2d
		$sql = "INSERT  into trhrekal(no_kas,tgl_kas,uraian,kd_skpd,nm_skpd,jns_trans)
				SELECT d.no_kas, d.tgl_kas, b.keperluan, a.kd_skpd, b.nm_skpd, b.jns_spp FROM trdspp a
				LEFT JOIN trhspp b ON a.no_spp=b.no_spp
				LEFT JOIN trhspm c ON c.no_spp=b.no_spp
				LEFT JOIN trhsp2d d ON d.no_spm=c.no_spm
				WHERE d.status = '1' LIMIT 50";
		$query1 = $this->db->query($sql);
		
/*	   $sql = " SELECT a.kd_skpd,b.nm_skpd,a.kd_kegiatan,a.nm_kegiatan,a.kd_rek5,a.nm_rek5,a.nilai,b.keperluan,d.status,d.tgl_kas,d.no_kas,b.jns_spp,d.no_sp2d FROM trdspp a
				 LEFT JOIN trhspp b ON a.no_spp=b.no_spp
				 LEFT JOIN trhspm c ON c.no_spp=b.no_spp
				 LEFT JOIN trhsp2d d ON d.no_spm=c.no_spm
				 WHERE d.status='1' ";
        $query1 = $this->db->query($sql);
        $tnosp2d = '';
        foreach ($query1->result_array() as $res)
        {
            $nosp2d = trim($res['no_sp2d']);
            $nokas = $res['no_kas'];
            $tglkas = $res['tgl_kas'];
            $uraian = $res['keperluan'];
            $kdskpd = $res['kd_skpd'];
            $nmskpd = $res['nm_skpd'];
            $jns = $res['jns_spp'];
            $kdgiat = $res['kd_kegiatan'];
            $nmgiat = $res['nm_kegiatan'];
            $nilai = $res['nilai'];
            $kdrek5 = $res['kd_rek5'];
            $nmrek5 = $res['nm_rek5'];

            if ($tnosp2d != $nosp2d)
            {
                $this->db->query(" insert into trhrekal(no_kas,tgl_kas,uraian,kd_skpd,nm_skpd,jns_trans) 
				  values('$nokas','$tglkas','$uraian','$kdskpd','$nmskpd','1') ");
            }

            $this->db->query(" insert into trdrekal(no_kas,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,terima,keluar,jns_trans) 
			  values('$nokas','$kdgiat','$nmgiat','$kdrek5','$nmrek5',$nilai,0,'1') ");

            $tnosp2d = $nosp2d;
        }

        //transout
        $sql = " SELECT a.no_bukti,b.kd_skpd,b.nm_skpd,b.ket,a.kd_kegiatan,a.nm_kegiatan,a.kd_rek5,a.nm_rek5,a.nilai,b.tgl_bukti  
				 FROM trdtransout a LEFT JOIN trhtransout b ON a.no_bukti=b.no_bukti; ";
        $query1 = $this->db->query($sql);
        $tnokas = '';
        foreach ($query1->result_array() as $res)
        {
            $nokas = trim($res['no_bukti']);
            $tglkas = trim($res['tgl_bukti']);
            $uraian = trim($res['ket']);
            $kdskpd = trim($res['kd_skpd']);
            $nmskpd = trim($res['nm_skpd']);

            $kdgiat = trim($res['kd_kegiatan']);
            $nmgiat = trim($res['nm_kegiatan']);
            $kdrek5 = trim($res['kd_rek5']);
            $nmrek5 = trim($res['nm_rek5']);
            $nilai = trim($res['nilai']);

            if ($tnokas != $nokas)
            {
                $this->db->query(" insert into trhrekal(no_kas,tgl_kas,uraian,kd_skpd,nm_skpd,jns_trans) 
				                     values('$nokas','$tglkas','$uraian','$kdskpd','$nmskpd','2') ");
            }

            $this->db->query(" insert into trdrekal(no_kas,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,terima,keluar,jns_trans) 
								 values('$nokas','$kdgiat','$nmgiat','$kdrek5','$nmrek5',0,$nilai,'2') ");
            $tnokas = $nokas;
        }


        //potongan trm pot
        $sql = " SELECT a.no_bukti,a.kd_rek5,a.nm_rek5,a.nilai,b.kd_skpd,b.nm_skpd,b.ket,b.tgl_bukti FROM trdtrmpot a 
				 LEFT JOIN trhtrmpot b ON a.no_bukti=b.no_bukti ";
        $query1 = $this->db->query($sql);
        $tnosp2d = '';
        foreach ($query1->result_array() as $res)
        {
            $nosp2d = trim($res['no_bukti']);
            $nokas = $res['no_bukti'];
            $tglkas = $res['tgl_bukti'];
            $uraian = $res['ket'];
            $kdskpd = $res['kd_skpd'];
            $nmskpd = $res['nm_skpd'];
            $kdgiat = '';
            $nmgiat = '';
            $nilai = $res['nilai'];
            $kdrek5 = $res['kd_rek5'];
            $nmrek5 = $res['nm_rek5'];

            if ($tnosp2d != $nosp2d)
            {
                $this->db->query(" insert into trhrekal(no_kas,tgl_kas,uraian,kd_skpd,nm_skpd,jns_trans) 
				  values('$nokas','$tglkas','$uraian','$kdskpd','$nmskpd','3') ");
            }

            $this->db->query(" insert into trdrekal(no_kas,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,terima,keluar,jns_trans) 
			  values('$nokas','$kdgiat','$nmgiat','$kdrek5','$nmrek5',$nilai,0,'3') ");

            $tnosp2d = $nosp2d;
        }

        //potongan out pot
        $sql = " SELECT a.no_bukti,a.kd_rek5,a.nm_rek5,a.nilai,b.kd_skpd,b.nm_skpd,b.ket,b.tgl_bukti FROM trdstrpot a 
				 LEFT JOIN trhstrpot b ON a.no_bukti=b.no_bukti ";
        $query1 = $this->db->query($sql);
        $tnosp2d = '';
        foreach ($query1->result_array() as $res)
        {
            $nosp2d = trim($res['no_bukti']);
            $nokas = $res['no_bukti'];
            $tglkas = $res['tgl_bukti'];
            $uraian = $res['ket'];
            $kdskpd = $res['kd_skpd'];
            $nmskpd = $res['nm_skpd'];
            $kdgiat = '';
            $nmgiat = '';
            $nilai = $res['nilai'];
            $kdrek5 = $res['kd_rek5'];
            $nmrek5 = $res['nm_rek5'];

            if ($tnosp2d != $nosp2d)
            {
                $this->db->query(" insert into trhrekal(no_kas,tgl_kas,uraian,kd_skpd,nm_skpd,jns_trans) 
				  values('$nokas','$tglkas','$uraian','$kdskpd','$nmskpd','4') ");
            }

            $this->db->query(" insert into trdrekal(no_kas,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,terima,keluar,jns_trans) 
			  values('$nokas','$kdgiat','$nmgiat','$kdrek5','$nmrek5',0,$nilai,'4') ");

            $tnosp2d = $nosp2d;
        }
*/
        echo '1';
    }

    function mapping()
    {
        $data['page_title'] = 'MAPPING REALISASI';
        $this->template->set('title', 'MAPPING REALISASI');
        $this->template->load('template', 'akuntansi/mapping', $data);
    }
    
    

    function proses_mapping()
    {
        $this->db->query(" delete from realisasi_anggaran  where left(kd_rek5,1) in('4','5','6')  ");

        $sql = " select * from trdrka order by kd_skpd,kd_kegiatan,kd_rek5 ";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $res)
        {

            $in = 0;
            $out = 0;
            $cp = 0;
            $real_in = 0;
            $real_out = 0;

            $kd_skpd = $res['kd_skpd'];
            $kd_kegiatan = $res['kd_kegiatan'];
            $kd_rek5 = $res['kd_rek5'];
            $anggaran = $res['nilai_ubah'];

            $gt = substr($kd_kegiatan, 5, strlen($kd_kegiatan) - 5);

            //get_nama($kode,$hasil,$tabel,$field)
            $nm_skpd = $this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
            //$nm_kegiatan=$this->tukd_model->get_nama($gt,'nm_kegiatan','m_giat','kd_kegiatan');
            $nm_rek5 = $this->tukd_model->get_nama($kd_rek5, 'nm_rek5', 'ms_rek5', 'kd_rek5');
            //$nm_skpd="";
            $nm_kegiatan = "";
            //$nm_rek5	="";


           

            //hitung pengeluaran / kas out
            $query2 = $this->db->query(" SELECT SUM(a.debet) AS debet FROM trdju a INNER JOIN trhju b ON a.no_voucher = b.no_voucher WHERE b.kd_skpd='$kd_skpd' AND a.kd_kegiatan='$kd_kegiatan' AND a.kd_rek5='$kd_rek5' ");
            foreach ($query2->result_array() as $res2)
            {
                $debet = $res2['debet'];

            }
            $query3 = $this->db->query(" SELECT SUM(a.kredit) AS kredit FROM trdju a INNER JOIN trhju b ON a.no_voucher = b.no_voucher WHERE b.kd_skpd='$kd_skpd' AND a.kd_kegiatan='$kd_kegiatan' AND a.kd_rek5='$kd_rek5' ");
            foreach ($query3->result_array() as $res3)
            {
                $kredit = $res3['kredit'];

            }
          
            $real_in = $kredit  - $debet;
            $real_out = $debet - $kredit ;
          

            if (((substr($kd_rek5, 0, 1) == '4') or (substr($kd_rek5, 0, 2) == '61')))
            {
                $this->db->query(" insert into realisasi_anggaran(kd_skpd,kd_kegiatan,kd_rek5,anggaran,real_spj,nm_skpd,nm_kegiatan,nm_rek5) values('$kd_skpd','$kd_kegiatan','$kd_rek5',$anggaran,$real_in,'$nm_skpd','$nm_kegiatan','$nm_rek5') ");
            } else
            {
                $this->db->query(" insert into realisasi_anggaran(kd_skpd,kd_kegiatan,kd_rek5,anggaran,real_spj,nm_skpd,nm_kegiatan,nm_rek5) values('$kd_skpd','$kd_kegiatan','$kd_rek5',$anggaran,$real_out,'$nm_skpd','$nm_kegiatan','$nm_rek5') ");
            }
        }

        $this->db->query(" delete from realisasi_anggaran where left(kd_rek5,1) in('7','8','9') ");

        $sql3 = " SELECT  a.kd_skpd AS kd_skpd,b.kd_rek5 AS kd_rek5 FROM trdju b INNER JOIN trhju a ON a.no_voucher=b.no_voucher WHERE LEFT(b.kd_rek5,1) IN('7','8','9') 
                  GROUP BY a.kd_skpd,b.kd_rek5  ORDER BY a.kd_skpd";
        $query5 = $this->db->query($sql3);
        //$ii = 0;
        foreach ($query5->result_array() as $res1)
        {
            //echo 'sdsdsd';
            //hitung pengeluaran / kas out
            $kd_skpd = $res1['kd_skpd'];
            $kd_rek5 = $res1['kd_rek5'];
            $query6 = $this->db->query(" SELECT SUM(a.debet) AS debet FROM trdju a INNER JOIN trhju b ON a.no_voucher = b.no_voucher WHERE b.kd_skpd='$kd_skpd' AND a.kd_rek5='$kd_rek5' ");
            foreach ($query6->result_array() as $res6)
            {
                $debet = $res6['debet'];

            }
            $query7 = $this->db->query(" SELECT SUM(a.kredit) AS kredit FROM trdju a INNER JOIN trhju b ON a.no_voucher = b.no_voucher WHERE b.kd_skpd='$kd_skpd'  AND a.kd_rek5='$kd_rek5' ");

            foreach ($query7->result_array() as $res7)
            {
                //echo 'dsdsd';
                $kredit = $res7['kredit'];

            }

            
            if (substr($kd_rek5, 0, 2) == '71' || substr($kd_rek5, 0, 1) == '8')
            {
                $real = $kredit  - $debet ;
            } else
            {
                $real = $debet - $kredit;
                //$real_ppkd=$debet_ppkd- $kredit_ppkd;
            }
            $this->db->query(" insert into realisasi_anggaran(kd_skpd,kd_rek5,real_spj) values('$kd_skpd','$kd_rek5','$real') ");
            //$this->db->query(" insert into realisasi_ppkd(kd_skpd,kd_rek5,real_spj) values('$kd_skpd','$kd_rek5','$real_ppkd') ");
        }


    
        ///realisasi_laporan
        $this->db->query(" delete from realisasi  where left(kd_rek5,1) in('4','5','6')  ");

        $sql = " select * from trdrka order by kd_skpd,kd_kegiatan,kd_rek5 ";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $res)
        {

            $in = 0;
            $out = 0;
            $cp = 0;
            $real_in = 0;
            $real_out = 0;

            $kd_skpd = $res['kd_skpd'];
            $kd_kegiatan = $res['kd_kegiatan'];
            $kd_rek5 = $res['kd_rek5'];
            $rek64= $this->tukd_model->get_nama($kd_rek5,'kd_rek64','ms_rek5','kd_rek5');
            $anggaran = $res['nilai_ubah'];

            $gt = substr($kd_kegiatan, 5, strlen($kd_kegiatan) - 5);

            //get_nama($kode,$hasil,$tabel,$field)
            $nm_skpd = $this->tukd_model->get_nama($kd_skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd');
            //$nm_kegiatan=$this->tukd_model->get_nama($gt,'nm_kegiatan','m_giat','kd_kegiatan');
            $nm_rek5 = $this->tukd_model->get_nama($rek64, 'nm_rek64', 'ms_rek5', 'kd_rek64');
            //$nm_skpd="";
            $nm_kegiatan = "";
            //$nm_rek5	="";


           

            //hitung pengeluaran / kas out
            $query2 = $this->db->query(" SELECT SUM(a.debet) AS debet FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher WHERE b.kd_skpd='$kd_skpd' AND a.kd_kegiatan='$kd_kegiatan' AND a.kd_rek5='$rek64' ");
            foreach ($query2->result_array() as $res2)
            {
                $debet = $res2['debet'];

            }
            $query3 = $this->db->query(" SELECT SUM(a.kredit) AS kredit FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher WHERE b.kd_skpd='$kd_skpd' AND a.kd_kegiatan='$kd_kegiatan' AND a.kd_rek5='$rek64' ");
            foreach ($query3->result_array() as $res3)
            {
                $kredit = $res3['kredit'];

            }
          
            $real_in = $kredit  - $debet;
            $real_out = $debet - $kredit ;
          

            if (((substr($kd_rek5, 0, 1) == '4') or (substr($kd_rek5, 0, 2) == '61')))
            {
                $this->db->query(" insert into realisasi(kd_skpd,kd_kegiatan,kd_rek5,anggaran,real_spj,nm_skpd,nm_kegiatan,nm_rek5) values('$kd_skpd','$kd_kegiatan','$rek64',$anggaran,$real_in,'$nm_skpd','$nm_kegiatan','$nm_rek5') ");
            } else
            {
                $this->db->query(" insert into realisasi(kd_skpd,kd_kegiatan,kd_rek5,anggaran,real_spj,nm_skpd,nm_kegiatan,nm_rek5) values('$kd_skpd','$kd_kegiatan','$rek64',$anggaran,$real_out,'$nm_skpd','$nm_kegiatan','$nm_rek5') ");
            }
        }

        $this->db->query(" delete from realisasi where left(kd_rek5,1) in('7','8','9') ");

        $sql3 = " SELECT  a.kd_skpd AS kd_skpd,b.kd_rek5 AS kd_rek5 FROM trdju_pkd b INNER JOIN trhju_pkd a ON a.no_voucher=b.no_voucher WHERE LEFT(b.kd_rek5,1) IN('7','8','9') 
                  GROUP BY a.kd_skpd,b.kd_rek5  ORDER BY a.kd_skpd";
        $query5 = $this->db->query($sql3);
        //$ii = 0;
        foreach ($query5->result_array() as $res1)
        {
            //echo 'sdsdsd';
            //hitung pengeluaran / kas out
            $kd_skpd = $res1['kd_skpd'];
            $kd_rek5 = $res1['kd_rek5'];
            $query6 = $this->db->query(" SELECT SUM(a.debet) AS debet FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher WHERE b.kd_skpd='$kd_skpd' AND a.kd_rek5='$kd_rek5' ");
            foreach ($query6->result_array() as $res6)
            {
                $debet = $res6['debet'];

            }
            $query7 = $this->db->query(" SELECT SUM(a.kredit) AS kredit FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher = b.no_voucher WHERE b.kd_skpd='$kd_skpd'  AND a.kd_rek5='$kd_rek5' ");

            foreach ($query7->result_array() as $res7)
            {
                //echo 'dsdsd';
                $kredit = $res7['kredit'];

            }

            
            if (substr($kd_rek5, 0, 2) == '71' || substr($kd_rek5, 0, 1) == '8')
            {
                $real = $kredit  - $debet ;
            } else
            {
                $real = $debet - $kredit;
                //$real_ppkd=$debet_ppkd- $kredit_ppkd;
            }
            $this->db->query(" insert into realisasi(kd_skpd,kd_rek5,real_spj) values('$kd_skpd','$kd_rek5','$real') ");
            //$this->db->query(" insert into realisasi_ppkd(kd_skpd,kd_rek5,real_spj) values('$kd_skpd','$kd_rek5','$real_ppkd') ");
        }

        echo '1';
    }


    function bukubesar()
    {

        $data['page_title'] = 'CETAK BUKU BESAR';
        $this->template->set('title', 'BUKU BESAR');
        $this->template->load('template', 'akuntansi/bukubesar', $data);

    }
    
    

    function skpd()
    {
        $lccr = $this->input->post('q');
        $sql = "SELECT kd_skpd,nm_skpd FROM ms_skpd where upper(kd_skpd) like upper('%$lccr%') or upper(nm_skpd) like upper('%$lccr%') ";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array('id' => $ii, 'kd_skpd' => $resulte['kd_skpd'], 'nm_skpd' => $resulte['nm_skpd'], );
            $ii++;
        }

        echo json_encode($result);
    }

    function rekening()
    {
        $lccr = $this->input->post('q');
        $sql = " SELECT kd_rek5,nm_rek5 FROM ms_rek5 where kd_rek5 like '$lccr%' limit 20";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array('id' => $ii, 'kd_rek5' => $resulte['kd_rek5'], 'nm_rek5' => $resulte['nm_rek5'], );
            $ii++;
        }

        echo json_encode($result);
    }

    function cetakbb($dcetak='',$ttd='',$skpd='',$rek5='',$dcetak2=''){ //wahyu

			$cRet ='<TABLE width="100%">
					<TR>
						<TD align="center" >BUKU BESAR </TD>
					</TR>
					</TABLE>';

			$cRet .='<TABLE width="100%">
					 <TR>
						<TD align="left" width="100" >SKPD</TD>
						<TD align="left" width="100" >: '.$skpd.' '.$this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd').'</TD>
					 </TR>
					 <TR>
						<TD align="left" width="100" >Rekening</TD>
						<TD align="left" width="100" >: '.$rek5.' '.$this->tukd_model->get_nama($rek5,'nm_rek5','ms_rek5','kd_rek5').'</TD>
					 </TR>
					 <TR>
						<TD align="left" width="100" >Periode</TD>
						<TD align="left" width="100" >: '.$this->tukd_model->tanggal_format_indonesia($dcetak).' s/d '.$this->tukd_model->tanggal_format_indonesia($dcetak2).'</TD>
					 </TR>
					 </TABLE>';

			$cRet .='<TABLE border="1" cellspacing="0" cellpadding="0" >
					 <TR>
						<TD width="90" align="center" >TANGGAL</TD>
						<TD width="350" align="center" >URAIAN</TD>
						<TD width="80" align="center" >REF</TD>
						<TD width="100" align="center" >DEBET</TD>
						<TD width="100" align="center" >KREDIT</TD>
						<TD width="120" align="center" >SALDO</TD>
					 </TR>';
			
         $csql3 = "SELECT sum(a.debet) as debet,sum(a.kredit) as kredit FROM trdju_ppkd a LEFT JOIN trhju_ppkd b ON a.no_voucher=b.no_voucher WHERE a.kd_rek5='$rek5' AND b.kd_skpd='$skpd' and b.tgl_voucher < '$dcetak'  and a.pos='1'";

         
         $hasil = $this->db->query($csql3);
         $trh4 = $hasil->row(); 
         $awaldebet = $trh4->debet;
         $awalkredit = $trh4->kredit;
                    if ((substr($rek5,0,1)=='9') or (substr($rek5,0,1)=='5') or (substr($rek5,0,2)=='62') or (substr($rek5,0,2)=='72') or (substr($rek5,0,1)=='1')){					
						$saldo=$awaldebet-$awalkredit;
					}else{
						$saldo=$awalkredit-$awaldebet;
					} 
                    
                    $cRet .='<TR>
								<TD width="90" align="left" ></TD>
								<TD width="350" align="left" >saldo awal</TD>
								<TD width="80" align="left" ></TD>
								<TD width="100" align="right" ></TD>
								<TD width="100" align="right" ></TD>
								<TD width="120" align="right" >'.number_format($saldo).'</TD>
							 </TR>';	      
                
				$idx=1;
				$query = $this->db->query("SELECT a.kd_rek5,a.debet,a.kredit,b.tgl_voucher,b.ket,b.no_voucher FROM trdju_ppkd a LEFT JOIN trhju_ppkd b ON a.no_voucher=b.no_voucher WHERE a.kd_rek5='$rek5' AND b.kd_skpd='$skpd' and b.tgl_voucher>='$dcetak' and b.tgl_voucher<='$dcetak2' and a.pos='1' ");  
				foreach($query->result_array() as $res){
										
					$cetak[$idx][1]=$res['tgl_voucher'];
					$cetak[$idx][2]=$res['ket'];
					$cetak[$idx][3]=$res['no_voucher'];
					$cetak[$idx][4]=$res['debet'];
					$cetak[$idx][5]=$res['kredit'];
					$idx++;

				}

				asort($cetak);
				$i=0;
				$saldo=$saldo;
				$jdebet=0;
				$jkredit=0;
				foreach ($cetak as $key => $val) {
					$i++;
					if ((substr($rek5,0,1)=='9') or (substr($rek5,0,1)=='5') or (substr($rek5,0,2)=='62') or (substr($rek5,0,2)=='72') or (substr($rek5,0,1)=='1')){					
						$saldo=$saldo+$cetak[$key][4]-$cetak[$key][5];
					}else{
						$saldo=$saldo+$cetak[$key][5]-$cetak[$key][4];
					}

					$cRet .='<TR>
								<TD width="90" align="left" >'.$this->tukd_model->rev_date($cetak[$key][1]).'</TD>
								<TD width="350" align="left" >'.$cetak[$key][2].'</TD>
								<TD width="80" align="left" >'.$cetak[$key][3].'</TD>
								<TD width="100" align="right" >'.number_format($cetak[$key][4]).'</TD>
								<TD width="100" align="right" >'.number_format($cetak[$key][5]).'</TD>
								<TD width="120" align="right" >'.number_format($saldo).'</TD>
							 </TR>';					
					$jdebet=$jdebet+$cetak[$key][4];
					$jkredit=$jkredit+$cetak[$key][5];
                    $jsaldo=$jdebet-$jkredit;
				}

				$cRet .='<TR>
					<TD width="90" align="left" ></TD>
					<TD width="350" align="left" >JUMLAH</TD>
					<TD width="80" align="left" ></TD>
					<TD width="100" align="right" >'.number_format($jdebet).'</TD>
					<TD width="100" align="right" >'.number_format($jkredit).'</TD>
					<TD width="120" align="right" >'.number_format($saldo).'</TD>
				 </TR>';					

			$cRet .='</TABLE>';

			$data['prev']= 'BUKU BESAR';
            $this->tukd_model->_mpdf('',$cRet,10,5,10,'0');	
	
	}

    /*
    function cetakbb($dcetak='',$ttd='',$skpd='',$rek5='',$dcetak2=''){

    $cRet ='<TABLE width="100%">
    <TR>
    <TD align="center" >BUKU BESAR </TD>
    </TR>
    </TABLE>';

    $cRet .='<TABLE width="100%">
    <TR>
    <TD align="left" width="100" >SKPD</TD>
    <TD align="left" width="100" >: '.$skpd.' '.$this->tukd_model->get_nama($skpd,'nm_skpd','ms_skpd','kd_skpd').'</TD>
    </TR>
    <TR>
    <TD align="left" width="100" >Rekening</TD>
    <TD align="left" width="100" >: '.$rek5.' '.$this->tukd_model->get_nama($rek5,'nm_rek5','ms_rek5','kd_rek5').'</TD>
    </TR>
    <TR>
    <TD align="left" width="100" >Periode</TD>
    <TD align="left" width="100" >: '.$this->tukd_model->tanggal_format_indonesia($dcetak).' s/d '.$this->tukd_model->tanggal_format_indonesia($dcetak2).'</TD>
    </TR>
    </TABLE>';

    $cRet .='<TABLE border="1" cellspacing="0" cellpadding="0" >
    <TR>
    <TD width="90" align="center" >TANGGAL</TD>
    <TD width="350" align="center" >URAIAN</TD>
    <TD width="80" align="center" >REF</TD>
    <TD width="100" align="center" >DEBET</TD>
    <TD width="100" align="center" >KREDIT</TD>
    <TD width="120" align="center" >SALDO</TD>
    </TR>';
    

    $idx=1;
    $query = $this->db->query("SELECT a.kd_rek5,a.nilai,b.tgl_kas,b.ket,b.no_kas FROM trdkasout_pkd a LEFT JOIN trhkasout_pkd b ON a.no_kas=b.no_kas WHERE a.kd_rek5='$rek5' AND b.kd_skpd='$skpd' and b.tgl_kas>='$dcetak' and b.tgl_kas<='$dcetak2' ");  
    foreach($query->result_array() as $res){
    
    $cetak[$idx][1]=$res['tgl_kas'];
    $cetak[$idx][2]=$res['ket'];
    $cetak[$idx][3]=$res['no_kas'];
    $cetak[$idx][4]=$res['nilai'];
    $cetak[$idx][5]=0;
    $idx++;

    }

    $query = $this->db->query(" SELECT b.tgl_sts,b.keterangan,a.no_sts,a.rupiah AS nilai FROM trdkasin_pkd a LEFT JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE a.kd_rek5='$rek5' AND b.kd_skpd='$skpd' AND b.tgl_sts>='$dcetak' and b.tgl_sts<='$dcetak2' ");  
    foreach($query->result_array() as $res){

    $cetak[$idx][1]=$res['tgl_sts'];
    $cetak[$idx][2]=$res['keterangan'];
    $cetak[$idx][3]=$res['no_sts'];
    $cetak[$idx][4]=0;
    $cetak[$idx][5]=$res['nilai'];
    $idx++;

    }

    asort($cetak);
    $i=0;
    $saldo=0;
    $jdebet=0;
    $jkredit=0;
    foreach ($cetak as $key => $val) {
    $i++;
    if ((substr($rek5,0,1)=='5') or (substr($rek5,0,2)=='62')){					
    $saldo=$saldo+$cetak[$key][4]-$cetak[$key][5];
    }else{
    $saldo=$saldo+$cetak[$key][5]-$cetak[$key][4];
    }

    $cRet .='<TR>
    <TD width="90" align="left" >'.$this->tukd_model->rev_date($cetak[$key][1]).'</TD>
    <TD width="350" align="left" >'.$cetak[$key][2].'</TD>
    <TD width="80" align="left" >'.$cetak[$key][3].'</TD>
    <TD width="100" align="right" >'.number_format($cetak[$key][4]).'</TD>
    <TD width="100" align="right" >'.number_format($cetak[$key][5]).'</TD>
    <TD width="120" align="right" >'.number_format($saldo).'</TD>
    </TR>';					
    $jdebet=$jdebet+$cetak[$key][4];
    $jkredit=$jkredit+$cetak[$key][5];
    }

    $cRet .='<TR>
    <TD width="90" align="left" ></TD>
    <TD width="350" align="left" >JUMLAH</TD>
    <TD width="80" align="left" ></TD>
    <TD width="100" align="right" >'.number_format($jdebet).'</TD>
    <TD width="100" align="right" >'.number_format($jkredit).'</TD>
    <TD width="120" align="right" ></TD>
    </TR>';					

    $cRet .='</TABLE>';

    $data['prev']= 'BUKU BESAR';
    $this->tukd_model->_mpdf('',$cRet,10,5,10,'0');	
    
    }
    */

    function jukeluar()
    {
        $data['page_title'] = 'JURNAL PENGELUARAN';
        $this->template->set('title', 'JURNAL PENGELUARAN');
        $this->template->load('template', 'akuntansi/jukeluar', $data);
    }

    function jumasuk()
    {
        $data['page_title'] = 'JURNAL PENERIMAAN';
        $this->template->set('title', 'JURNAL PENERIMAAN');
        $this->template->load('template', 'akuntansi/jumasuk', $data);
    }


    function ctk_jukeluar($dcetak = '', $ttd = '', $skpd = '', $dcetak2 = '')
    {

        $cRet = '<TABLE width="100%">
					<TR>
						<TD align="center" >JURNAL PENGELUARAN</TD>
					</TR>
					</TABLE>';

        $cRet .= '<TABLE width="100%">
					 <TR>
						<TD align="left" width="100" >SKPD</TD>
						<TD align="left" width="100" >: ' . $skpd . ' ' . $this->tukd_model->
            get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . '</TD>
					 </TR>
					 <TR>
						<TD  align="left" width="100" >PERIODE</TD>
						<TD align="left" width="100" >: ' . $this->tukd_model->
            tanggal_format_indonesia($dcetak) . ' s/d ' . $this->tukd_model->
            tanggal_format_indonesia($dcetak2) . '</TD>
					 </TR>
					 </TABLE>';

        $cRet .= '<TABLE border="1" cellspacing="0" cellpadding="0" >
					 <TR>
						<TD width="90" align="center" rowspan="2" >TANGGAL</TD>
						<TD width="200" align="center" colspan="2" >NOMOR</TD>
						<TD width="100" align="center" rowspan="2" >KODE REKENING</TD>
						<TD width="200" align="center" rowspan="2" >URAIAN</TD>
						<TD width="80" align="center"  rowspan="2" >REF</TD>
						<TD width="100" align="center" rowspan="2" >JUMLAH</TD>
						<TD width="100" align="center" rowspan="2" >AKUMULASI</TD>
					 </TR>';

        $cRet .= '<TR>
						<TD width="100" align="center" >No.Bukti</TD>
						<TD width="100" align="center" >Bukti Lain</TD>
					 </TR>';

        $idx = 1;
        $saldo = 0;
        $tbukti = '';
        $tgl = '';
        $query = $this->db->query(" SELECT a.kd_rek5,a.nilai,a.nm_rek5,b.tgl_kas,b.ket,b.no_kas FROM trdkasout_pkd a LEFT JOIN trhkasout_pkd b ON a.no_kas=b.no_kas WHERE b.kd_skpd='$skpd' and b.tgl_kas>='$dcetak' and b.tgl_kas<='$dcetak2' order by b.tgl_kas ");
        foreach ($query->result_array() as $res)
        {
            if ($tbukti != $res['no_kas'])
            {
                $nokas = $res['no_kas'];
            } else
            {
                $nokas = '';
            }
            if ($tgl != $res['tgl_kas'])
            {
                $tanggal = $res['tgl_kas'];
            } else
            {
                $tanggal = '';
            }
            $saldo = $saldo + $res['nilai'];
            $cRet .= '<TR>
								<TD  align="left" >' . $this->tukd_model->rev_date($res['tgl_kas']) .
                '</TD>
								<TD  align="left" >' . $nokas . '</TD>
								<TD  align="left" ></TD>
								<TD  align="center" >' . $res['kd_rek5'] . '</TD>
								<TD  align="left" >' . $res['nm_rek5'] . '</TD>
								<TD  align="left" ></TD>
								<TD  align="right" >' . number_format($res['nilai']) . '</TD>
								<TD  align="right" >' . number_format($saldo) . '</TD>
							 </TR>';
            $idx++;
            $tbukti = $res['no_kas'];
            $tgl = $res['tgl_kas'];
        }


        $cRet .= '</TABLE>';

        $data['prev'] = 'JURNAL PENGELUARAN';
        $this->tukd_model->_mpdf('', $cRet, 5, 5, 10, '0');

    }


    function ctk_jumasuk($dcetak = '', $ttd = '', $skpd = '', $dcetak2 = '')
    {

        $cRet = '<TABLE width="100%">
					<TR>
						<TD align="center" >JURNAL PENERIMAAN</TD>
					</TR>
					</TABLE>';

        $cRet .= '<TABLE width="100%">
					 <TR>
						<TD align="left" width="100" >SKPD</TD>
						<TD align="left" width="100" >: ' . $skpd . ' ' . $this->tukd_model->
            get_nama($skpd, 'nm_skpd', 'ms_skpd', 'kd_skpd') . '</TD>
					 </TR>
					 <TR>
						<TD  align="left" width="100" >PERIODE</TD>
						<TD align="left" width="100" >: ' . $this->tukd_model->
            tanggal_format_indonesia($dcetak) . ' s/d ' . $this->tukd_model->
            tanggal_format_indonesia($dcetak2) . '</TD>
					 </TR>
					 </TABLE>';

        $cRet .= '<TABLE border="1" cellspacing="0" cellpadding="0" >
					 <TR>
						<TD width="90" align="center" rowspan="2" >TANGGAL</TD>
						<TD width="200" align="center" colspan="2" >NOMOR</TD>
						<TD width="100" align="center" rowspan="2" >KODE REKENING</TD>
						<TD width="200" align="center" rowspan="2" >URAIAN</TD>
						<TD width="80" align="center"  rowspan="2" >REF</TD>
						<TD width="100" align="center" rowspan="2" >JUMLAH</TD>
						<TD width="100" align="center" rowspan="2" >AKUMULASI</TD>
					 </TR>';

        $cRet .= '<TR>
						<TD width="100" align="center" >No.Bukti</TD>
						<TD width="100" align="center" >Bukti Lain</TD>
					 </TR>';

        $idx = 1;
        $saldo = 0;
        $tbukti = '';
        $query = $this->db->query(" SELECT a.kd_rek5,a.rupiah AS nilai,c.nm_rek5,b.tgl_sts,b.no_sts as no_kas FROM trdkasin_pkd a LEFT JOIN trhkasin_pkd b ON a.no_sts=b.no_sts LEFT JOIN ms_rek5 c ON a.kd_rek5=c.kd_rek5 WHERE  b.kd_skpd='$skpd' and b.tgl_sts>='$dcetak' and b.tgl_sts<='$dcetak2' ");
        foreach ($query->result_array() as $res)
        {
            if ($tbukti != $res['no_kas'])
            {
                $nokas = $res['no_kas'];
            } else
            {
                $nokas = '';
            }
            $saldo = $saldo + $res['nilai'];
            $cRet .= '<TR>
								<TD  align="left" >' . $this->tukd_model->rev_date($res['tgl_sts']) .
                '</TD>
								<TD  align="left" >' . $nokas . '</TD>
								<TD  align="left" ></TD>
								<TD  align="center" >' . $res['kd_rek5'] . '</TD>
								<TD  align="left" >' . $res['nm_rek5'] . '</TD>
								<TD  align="left" ></TD>
								<TD  align="right" >' . number_format($res['nilai']) . '</TD>
								<TD  align="right" >' . number_format($saldo) . '</TD>
							 </TR>';
            $idx++;
            $tbukti = $res['no_kas'];
        }


        $cRet .= '</TABLE>';

        $data['prev'] = 'JURNAL PENERIMAAN';
        $this->tukd_model->_mpdf('', $cRet, 5, 5, 10, '0');

    }

    function jumum()
    {
        $data['page_title'] = 'INPUT JURNAL UMUM';
        $this->template->set('title', 'INPUT JURNAL UMUM');
        $this->template->load('template', 'akuntansi/jumum', $data);
    }

    function jumum_ppkd()
    {
        $data['page_title'] = 'INPUT JURNAL UMUM';
        $this->template->set('title', 'INPUT JURNAL UMUM');
        $this->template->load('template', 'akuntansi/jumum_ppkd', $data);
    }

    function load_ju()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where = "where tabel='1'";
        if ($kriteria <> '')
        {
            $where = "where tabel='1' and (upper(no_voucher) like upper('%$kriteria%') or tgl_voucher like '%$kriteria%' or upper(ket) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from trhju_pkd $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = " select * from trhju_pkd $where order by tgl_voucher,no_voucher,kd_skpd limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $row[] = array('no_voucher' => $resulte['no_voucher'], 'tgl_voucher' => $resulte['tgl_voucher'],
                'kd_skpd' => $resulte['kd_skpd'], 'nm_skpd' => $resulte['nm_skpd'], 'ket' =>
                trim($resulte['ket']), 'total_d' => $resulte['total_d'], 'total_k' => $resulte['total_k']);
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_ju_ppkd()
    {
        $result = array();
        $row = array();
        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
        $offset = ($page - 1) * $rows;
        $kriteria = $this->input->post('cari');
        $where = "where tabel='1'";
        if ($kriteria <> '')
        {
            $where = "where tabel='1' and (upper(no_voucher) like upper('%$kriteria%') or tgl_voucher like '%$kriteria%' or upper(ket) like upper('%$kriteria%')) ";
        }

        $sql = "SELECT count(*) as total from trhju_ppkd $where";
        $query1 = $this->db->query($sql);
        $total = $query1->row();
        $result["total"] = $total->total;
        $query1->free_result();

        $sql = " select * from trhju_ppkd $where order by tgl_voucher,no_voucher,kd_skpd limit $offset,$rows";
        $query1 = $this->db->query($sql);
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $row[] = array('no_voucher' => $resulte['no_voucher'], 'tgl_voucher' => $resulte['tgl_voucher'],
                'kd_skpd' => $resulte['kd_skpd'], 'nm_skpd' => $resulte['nm_skpd'], 'ket' =>
                trim($resulte['ket']), 'total_d' => $resulte['total_d'], 'total_k' => $resulte['total_k']);
            $ii++;
        }
        $result["rows"] = $row;
        echo json_encode($result);
        $query1->free_result();
    }

    function load_dju()
    {
        $nomor = $this->input->post('no');
        $sql = "SELECT a.no_voucher,b.kd_kegiatan,b.nm_kegiatan,b.kd_rek5,IF(rk='D',b.nm_rek5,CONCAT(SPACE(4),b.nm_rek5)) AS nm_rek5,b.debet,b.kredit,b.rk,b.jns,b.pos FROM trhju_pkd a INNER JOIN trdju_pkd b ON a.no_voucher=b.no_voucher WHERE a.no_voucher='$nomor'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $result[] = array('no_voucher' => $resulte['no_voucher'], 'kd_kegiatan' => $resulte['kd_kegiatan'],
                'nm_kegiatan' => $resulte['nm_kegiatan'], 'kd_rek5' => $resulte['kd_rek5'],
                'nm_rek5' => $resulte['nm_rek5'], 'debet' => $resulte['debet'], 'kredit' => $resulte['kredit'],
                'rk' => $resulte['rk'], 'jns' => $resulte['jns'], 'post' => $resulte['pos']);
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_dju_ppkd()
    {
        $nomor = $this->input->post('no');
        $sql = "SELECT a.no_voucher,b.kd_kegiatan,b.nm_kegiatan,b.kd_rek5,IF(rk='D',b.nm_rek5,CONCAT(SPACE(4),b.nm_rek5)) AS nm_rek5,b.debet,b.kredit,b.rk,b.jns,b.pos FROM trhju_ppkd a INNER JOIN trdju_ppkd b ON a.no_voucher=b.no_voucher WHERE a.no_voucher='$nomor'";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $result[] = array('no_voucher' => $resulte['no_voucher'], 'kd_kegiatan' => $resulte['kd_kegiatan'],
                'nm_kegiatan' => $resulte['nm_kegiatan'], 'kd_rek5' => $resulte['kd_rek5'],
                'nm_rek5' => $resulte['nm_rek5'], 'debet' => $resulte['debet'], 'kredit' => $resulte['kredit'],
                'rk' => $resulte['rk'], 'jns' => $resulte['jns'], 'post' => $resulte['pos']);
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function load_ju_trskpd()
    {
        $jenis = $this->input->post('jenis');
        $len = strlen($jenis);
        $giat = $this->input->post('giat');
        $cskpd = $this->input->post('kd');

        $jns_beban = '';
        $cgiat = '';
        if ($jenis != '')
        {
            $jns_beban = "and left(a.jns_kegiatan,$len)='$jenis'";
        }
        if ($giat != '')
        {
            $cgiat = " and a.kd_kegiatan not in ($giat) ";
        }
        $lccr = $this->input->post('q');
        $sql = "SELECT a.kd_kegiatan,b.nm_kegiatan,a.kd_program,(select nm_program from m_prog where kd_program=a.kd_program) as nm_program,a.total FROM trskpd a INNER JOIN m_giat b ON a.kd_kegiatan1=b.kd_kegiatan
                WHERE LEFT(a.kd_kegiatan,4)= LEFT('$cskpd',4) $jns_beban $cgiat AND (UPPER(a.kd_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(b.nm_kegiatan) LIKE UPPER('%$lccr%'))";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {

            $result[] = array('id' => $ii, 'kd_kegiatan' => $resulte['kd_kegiatan'],
                'nm_kegiatan' => $resulte['nm_kegiatan'], 'kd_program' => $resulte['kd_program'],
                'nm_program' => $resulte['nm_program'], 'total' => $resulte['total']);
            $ii++;
        }

        echo json_encode($result);
        $query1->free_result();
    }

    function load_ju_rek()
    {
        //$jenis = $this->uri->segment(3);
        $jenis = $this->input->post('jenis');
        $len = strlen($jenis);
        $giat = $this->input->post('giat');
        $kode = $this->input->post('kd');
        $rek = $this->input->post('rek');
        $lccr = $this->input->post('q');


        if ($rek != '')
        {
            //if($jenis == '7' || $jenis == '8'  ||  $jenis == '9'){
            //$notIn = " and kd_rek4 not in ($rek) " ;
            //}else{
            $notIn = " and kd_rek5 not in ($rek) ";
            //}
        } else
        {
            $notIn = "";
        }
        //echo $jenis;

        if ($jenis == '4' || $jenis == '5' || $jenis == '6')
        {
            $sql = "SELECT kd_rek5,nm_rek5 FROM trdrka  WHERE kd_kegiatan= '$giat' AND kd_skpd = '$kode' $notIn ";
        } else
        {
            //if ($jenis == '7' || $jenis == '8'  ||  $jenis == '9'){

            //$sql = "SELECT  kd_rek5,nm_rek4 as nm_rek5 FROM ms_rek4 where left(kd_rek4,$len)='$jenis' $notIn";
            //}else{

            $sql = "SELECT kd_rek5,nm_rek5 FROM ms_rek5 where left(kd_rek5,$len)='$jenis' $notIn";
            //}
        }
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte)
        {
            $result[] = array('kd_rek5' => $resulte['kd_rek5'], 'nm_rek5' => $resulte['nm_rek5']);
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }

    function simpan_ju()
    {
        $tabel = $this->input->post('tabel');
        $nomor = $this->input->post('no');
        $tgl = $this->input->post('tgl');
        $skpd = $this->input->post('skpd');
        $nmskpd = $this->input->post('nmskpd');
        $ket = $this->input->post('ket');
        $total_d = $this->input->post('total_d');
        $total_k = $this->input->post('total_k');
        $csql = $this->input->post('sql');

        $usernm = $this->session->userdata('pcNama');
        $update = date('y-m-d H:i:s');
        $msg = array();

        if ($tabel == 'trhju_pkd')
        {
            $sql = "delete from trhju_pkd where kd_skpd='$skpd' and no_voucher='$nomor'";
            $asg = $this->db->query($sql);
            if ($asg)
            {
                $sql = "insert into trhju_pkd(no_voucher,tgl_voucher,ket,username,tgl_update,kd_skpd,nm_skpd,total_d,total_k,tabel) 
                        values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total_d','$total_k','1')";
                $asg = $this->db->query($sql);
                if (!($asg))
                {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else
                {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            } else
            {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }

        } else
            if ($tabel == 'trdju_pkd')
            {

                // Simpan Detail //
                $sql = "delete from trdju_pkd where no_voucher='$nomor'";
                $asg = $this->db->query($sql);
                if (!($asg))
                {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else
                {
                    $sql = "insert into trdju_pkd(no_voucher,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,pos,urut)";

                    $asg = $this->db->query($sql . $csql);
                    if (!($asg))
                    {
                        $msg = array('pesan' => '0');
                        echo json_encode($msg);
                        exit();
                    } else
                    {
                        $msg = array('pesan' => '1');
                        echo json_encode($msg);
                    }
                }
            }
    }

    function simpan_ju_ppkd()
    {
        $tabel = $this->input->post('tabel');
        $nomor = $this->input->post('no');
        $tgl = $this->input->post('tgl');
        $skpd = $this->input->post('skpd');
        $nmskpd = $this->input->post('nmskpd');
        $ket = $this->input->post('ket');
        $total_d = $this->input->post('total_d');
        $total_k = $this->input->post('total_k');
        $csql = $this->input->post('sql');

        $usernm = $this->session->userdata('pcNama');
        $update = date('y-m-d H:i:s');
        $msg = array();

        if ($tabel == 'trhju_ppkd')
        {
            $sql = "delete from trhju_ppkd where kd_skpd='$skpd' and no_voucher='$nomor'";
            $asg = $this->db->query($sql);
            if ($asg)
            {
                $sql = "insert into trhju_ppkd(no_voucher,tgl_voucher,ket,username,tgl_update,kd_skpd,nm_skpd,total_d,total_k,tabel) 
                        values('$nomor','$tgl','$ket','$usernm','$update','$skpd','$nmskpd','$total_d','$total_k','1')";
                $asg = $this->db->query($sql);
                if (!($asg))
                {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else
                {
                    $msg = array('pesan' => '1');
                    echo json_encode($msg);
                }
            } else
            {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }

        } else
            if ($tabel == 'trdju_ppkd')
            {

                // Simpan Detail //
                $sql = "delete from trdju_ppkd where no_voucher='$nomor'";
                $asg = $this->db->query($sql);
                if (!($asg))
                {
                    $msg = array('pesan' => '0');
                    echo json_encode($msg);
                    exit();
                } else
                {
                    $sql = "insert into trdju_ppkd(no_voucher,kd_kegiatan,nm_kegiatan,kd_rek5,nm_rek5,debet,kredit,rk,jns,pos,urut)";

                    $asg = $this->db->query($sql . $csql);
                    if (!($asg))
                    {
                        $msg = array('pesan' => '0');
                        echo json_encode($msg);
                        exit();
                    } else
                    {
                        $msg = array('pesan' => '1');
                        echo json_encode($msg);
                    }
                }
            }
    }

    function hapus_ju()
    {
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdju_pkd where no_voucher='$nomor'";
        $asg = $this->db->query($sql);
        if ($asg)
        {
            $sql = "delete from trhju_pkd where no_voucher='$nomor'";
            $asg = $this->db->query($sql);
            if (!($asg))
            {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }
        } else
        {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        }
        $msg = array('pesan' => '1');
        echo json_encode($msg);
    }

    function hapus_ju_ppkd()
    {
        $nomor = $this->input->post('no');
        $msg = array();
        $sql = "delete from trdju_ppkd where no_voucher='$nomor'";
        $asg = $this->db->query($sql);
        if ($asg)
        {
            $sql = "delete from trhju_ppkd where no_voucher='$nomor'";
            $asg = $this->db->query($sql);
            if (!($asg))
            {
                $msg = array('pesan' => '0');
                echo json_encode($msg);
                exit();
            }
        } else
        {
            $msg = array('pesan' => '0');
            echo json_encode($msg);
            exit();
        }
        $msg = array('pesan' => '1');
        echo json_encode($msg);
    }


    function penjabaran()
    {
        $data['page_title'] = 'REALISASI';
        $this->template->set('title', 'REALISASI');
        $this->template->load('template', 'akuntansi/penjabaran', $data);
    }
    function cetak_perkada_real($ctk = '', $skpd = '', $awal = '', $akhir = '')
    {

        $cetak = $ctk;
        $id = $skpd;
        $date_awal = $awal;
        $date_akhir = $akhir;
        if ($cetak == 2)
        {
            $tgl_awal = $this->tukd_model->tanggal_format_indonesia($date_awal);
            $tgl_akhir = $this->tukd_model->tanggal_format_indonesia($date_akhir);
        }
        $sqldns = "SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$id'";
        $sqlskpd = $this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns)
        {

            $kd_urusan = $rowdns->kd_u;
            $nm_urusan = $rowdns->nm_u;
            $kd_skpd = $rowdns->kd_sk;
            $nm_skpd = $rowdns->nm_sk;
        }

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc)
        {

            $tgl = $rowsc->tgl_rka;
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
            $kab = $rowsc->kab_kota;
            $daerah = $rowsc->daerah;
            $thn = $rowsc->thn_ang;
        }


        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>$kab</strong></td>
                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>RINCIAN LAPORAN REALISASI ANGGARAN MENURUT URUSAN PEMERINTAHAN DAERAH,ORGANISASI<br/>PENDAPATAN,BELANJA DAN PEMBIAYAAN </strong></td>
                    </tr>
                    
                    <tr>
                         <td align=\"center\"><strong>TAHUN ANGARAN $thn</strong></td>
                    </tr>

                  </table>";
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"0\">
                    <tr>
                        <td width=\"20%\">Urusan Organisasi </td>
                        <td width=\"80%\">:$kd_urusan - $nm_urusan</td>
                    </tr>
                    <tr>
                        <td>Organisasi</td>
                        <td>:$kd_skpd - $nm_skpd</td>
                    </tr>";
        if ($cetak == 2)
        {
            $cRet .= "<TR>                        
						<TD  align=\"left\" width=\"20%\" >PERIODE</TD>
						<TD align=\"left\" width=\"80%\" >:$tgl_awal s/d $tgl_akhir </TD>                      
					 </TR>";
        }
        $cRet .= "</table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>Kode Rekening</b></td>                            
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"26%\" align=\"center\"><b>Uraian</b></td>                            
                            <td colspan=\"2\" bgcolor=\"#CCCCCC\" width=\"28%\" align=\"center\"><b>Jumlah (Rp.)</b></td>
                            <td colspan=\"2\" bgcolor=\"#CCCCCC\" width=\"16%\" align=\"center\"><b>Bertambah/ (berkurang)</b></td>
                            <td  rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"13%\" align=\"center\"><b>Dasar Hukum</b></td>                            
                            </tr>
                        <tr>
 		                     <td width=\"13%\" bgcolor=\"#CCCCCC\" align=\"center\">Anggaran </td>
                             <td width=\"13%\" bgcolor=\"#CCCCCC\" align=\"center\">Realisasi</td>
                             <td width=\"13%\" bgcolor=\"#CCCCCC\" align=\"center\">Jumlah</td>
                             <td width=\"3%\" bgcolor=\"#CCCCCC\" align=\"center\">%</td>
                             
                        </tr>    
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>  
                            <td style=\"border-top: none;\"></td>
                                                                                   
                         </tr>
                     </tfoot>
                    
                     
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"26%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"3%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"14%\">&nbsp;</td>
                            </tr>
                        ";
        if ($cetak == 1)
        {
            $sqltp = "SELECT SUM(a.nilai) AS totp,SUM(a.nilai_ubah) AS totp_u, 
                        (SELECT SUM(rupiah) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,1)='4' AND a.kd_skpd='$id') AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,1)='4' AND d.kd_skpd='$id') AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,1)='4' AND f.kd_skpd='$id') AS kredit FROM trdrka a
                         WHERE LEFT(kd_rek5,1)='4' AND kd_skpd='$id'";
        }
        if ($cetak == 2)
        {
            $sqltp = "SELECT SUM(a.nilai) AS totp,SUM(a.nilai_ubah) AS totp_u, 
                        (SELECT SUM(rupiah) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,1)='4' AND a.kd_skpd='$id' AND b.tgl_sts>='$date_awal' AND b.tgl_sts<='$date_akhir') AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,1)='4' AND d.kd_skpd='$id' AND  d.tgl_voucher>='$date_awal' AND d.tgl_voucher<='$date_akhir') AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,1)='4' AND f.kd_skpd='$id' AND  f.tgl_voucher>='$date_awal' AND f.tgl_voucher<='$date_akhir') AS kredit FROM trdrka a
                         WHERE LEFT(kd_rek5,1)='4' AND kd_skpd='$id'";
        }

        $sqlp = $this->db->query($sqltp);
        foreach ($sqlp->result() as $rowp)
        {
            $angp = number_format($rowp->totp_u, "2", ".", ",");
            $angaranp = $rowp->totp_u;
            $penp = $rowp->cp;
            $debetp = $rowp->debet;
            $kreditp = $rowp->kredit;
            $realp = ($penp + $kreditp) - $debetp;
            $nrealp = number_format($realp, "2", ".", ",");
            $real_sp = $realp - $angaranp;
            if ($real_sp < 0)
            {
                $x1 = "(";
                $real_sp = $real_sp * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisihp = number_format($real_sp, "2", ".", ",");
            $per1p = ($real_sp != 0) ? ($real_sp / $rowp->totp_u) * 100 : 0;
            $persen1p = number_format($per1p);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Pendapatan Daerah</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$angp</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nrealp</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$pend_selisihp$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen1p</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";
        }
        if ($cetak == 1)
        {
            $sql_p = "SELECT * FROM (SELECT SUBSTR(a.no_trdrka,12,24) AS kd_rek,b.nm_rek2 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE  g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE  h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'3' AS nu FROM trdrka a INNER JOIN ms_rek2 b 
                        ON LEFT(a.kd_rek5,2)=b.kd_rek2 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,1)='4' GROUP BY SUBSTR(a.no_trdrka,12,24)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                        ON LEFT(a.kd_rek5,3)=b.kd_rek3 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,1)='4' GROUP BY SUBSTR(a.no_trdrka,12,25)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,27) AS kd_rek,b.nm_rek4 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,5)= LEFT(a.kd_rek5,5) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,5)= LEFT(a.kd_rek5,5) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'5' AS nu FROM trdrka a INNER JOIN ms_rek4 b 
                        ON LEFT(a.kd_rek5,5)=b.kd_rek4 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,1)='4' GROUP BY SUBSTR(a.no_trdrka,12,27)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,29) AS kd_rek,a.nm_rek5 ,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND c.kd_rek5= a.kd_rek5 AND d.kd_kegiatan = a.kd_kegiatan)AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE  g.kd_skpd='$id' AND e.kd_rek5= a.kd_rek5 AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND f.kd_rek5= a.kd_rek5 AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'6' AS nu FROM trdrka a                     
                        WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,1)='4' GROUP BY SUBSTR(a.no_trdrka,12,29),a.nm_rek5 ) a ORDER BY kd_rek,nu ";
        }
        if ($cetak == 2)
        {
            $sql_p = "SELECT * FROM (SELECT SUBSTR(a.no_trdrka,12,24) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir' )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE  g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir' )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE  h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir' )AS kredit,'3' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                        ON LEFT(a.kd_rek5,3)=b.kd_rek3 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,1)='4' GROUP BY SUBSTR(a.no_trdrka,12,24)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir' )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir' )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir' )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                        ON LEFT(a.kd_rek5,3)=b.kd_rek3 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,1)='4' GROUP BY SUBSTR(a.no_trdrka,12,25)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,27) AS kd_rek,b.nm_rek4 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir' )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,5)= LEFT(a.kd_rek5,5) AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir')AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,5)= LEFT(a.kd_rek5,5) AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir' )AS kredit,'5' AS nu FROM trdrka a INNER JOIN ms_rek4 b 
                        ON LEFT(a.kd_rek5,5)=b.kd_rek4 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,1)='4' GROUP BY SUBSTR(a.no_trdrka,12,27)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,29) AS kd_rek,a.nm_rek5 ,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND c.kd_rek5= a.kd_rek5 AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir')AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE  g.kd_skpd='$id' AND e.kd_rek5= a.kd_rek5 AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir' )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND f.kd_rek5= a.kd_rek5 AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir')AS kredit,'6' AS nu FROM trdrka a                     
                        WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,1)='4' GROUP BY SUBSTR(a.no_trdrka,12,29),a.nm_rek5 ) a ORDER BY kd_rek,nu ";
        }
        $pend = $this->db->query($sql_p);
        //$query = $this->skpd_model->getAllc();

        foreach ($pend->result() as $row)
        {
            $kode = $row->kd_rek;
            $nama = $row->nm_rek;
            $ang = number_format($row->nilai_u, "2", ".", ",");
            $angaran = $row->nilai_u;
            $pen = $row->cp;
            $debet = $row->debet;
            $kredit = $row->kredit;
            $real = ($pen + $kredit) - $debet;
            $nreal = number_format($real, "2", ".", ",");
            $real_s = $real - $angaran;
            if ($real_s < 0)
            {
                $x1 = "(";
                $real_s = $real_s * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisih = number_format($real_s, "2", ".", ",");
            $per1 = ($real_s != 0) ? ($real_s / $row->nilai_u) * 100 : 0;
            $persen1 = number_format($per1);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">$kode</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$pend_selisih$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen1</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"left\"></td>
                                     </tr>
                                     ";


        }

        $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";


        if ($cetak == 1)
        {
            $sqltb = "SELECT SUM(a.nilai) AS totb,SUM(a.nilai_ubah) AS totb_u, 
            (SELECT SUM(rupiah) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,1)='5' AND a.kd_skpd='$id' ) AS cp,
            (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,1)='5' AND d.kd_skpd='$id' ) AS debet,
            (SELECT SUM(kredit) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,1)='5' AND f.kd_skpd='$id' ) AS kredit FROM trdrka a
            WHERE LEFT(kd_rek5,1)='5' AND kd_skpd='$id'";
        }
        if ($cetak == 2)
        {
            $sqltb = "SELECT SUM(a.nilai) AS totb,SUM(a.nilai_ubah) AS totb_u, 
            (SELECT SUM(rupiah) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,1)='5' AND a.kd_skpd='$id' AND b.tgl_sts>='$date_awal' AND b.tgl_sts<='$date_akhir') AS cp,
            (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,1)='5' AND d.kd_skpd='$id' AND  d.tgl_voucher>='$date_awal' AND d.tgl_voucher<='$date_akhir') AS debet,
            (SELECT SUM(kredit) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,1)='5' AND f.kd_skpd='$id' AND  f.tgl_voucher>='$date_awal' AND f.tgl_voucher<='$date_akhir') AS kredit FROM trdrka a
            WHERE LEFT(kd_rek5,1)='5' AND kd_skpd='$id'";
        }
        $sqlb = $this->db->query($sqltb);
        foreach ($sqlb->result() as $rowb_5)
        {
            $ang_5 = number_format($rowb_5->totb_u, "2", ".", ",");
            $angaran_5 = $rowb_5->totb_u;
            $in_5 = $rowb_5->cp;
            $debet_5 = $rowb_5->debet;
            $kredit_5 = $rowb_5->kredit;
            $real_5 = $debet_5 - ($in_5 + $kredit_5);
            $nreal_5 = number_format($real_5, "2", ".", ",");
            $real_s_5 = $real_5 - $angaran_5;
            if ($real_s_5 < 0)
            {
                $x1 = "(";
                $real_s_5 = $real_s_5 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih_5 = number_format($real_s_5, "2", ".", ",");
            $per_5 = ($real_s_5 != 0) ? ($real_s_5 / $rowb_5->totb_u) * 100 : 0;
            $persen_5 = number_format($per_5);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Belanja Daerah</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_5$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_5</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";


        }
        if ($cetak == 1)
        {
            $sqltbtl = "SELECT SUM(a.nilai) AS totb,SUM(a.nilai_ubah) AS totb_u, 
                            (SELECT SUM(rupiah) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,2)='51' AND a.kd_skpd='$id' ) AS cp,
                            (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,2)='51' AND d.kd_skpd='$id' ) AS debet,
                            (SELECT SUM(kredit) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,2)='51' AND f.kd_skpd='$id' ) AS kredit FROM trdrka a
                            WHERE LEFT(kd_rek5,2)='51' AND kd_skpd='$id'";
        }
        if ($cetak == 2)
        {
            $sqltbtl = "SELECT SUM(a.nilai) AS totb,SUM(a.nilai_ubah) AS totb_u, 
                            (SELECT SUM(rupiah) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,2)='51' AND a.kd_skpd='$id' AND b.tgl_sts>='$date_awal' AND b.tgl_sts<='$date_akhir') AS cp,
                            (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,2)='51' AND d.kd_skpd='$id' AND  d.tgl_voucher>='$date_awal' AND d.tgl_voucher<='$date_akhir') AS debet,
                            (SELECT SUM(kredit) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,2)='51' AND f.kd_skpd='$id' AND  f.tgl_voucher>='$date_awal' AND f.tgl_voucher<='$date_akhir') AS kredit FROM trdrka a
                            WHERE LEFT(kd_rek5,2)='51' AND kd_skpd='$id'";
        }
        $sqlbtl = $this->db->query($sqltbtl);
        foreach ($sqlbtl->result() as $rowbtl)
        {
            $ang_51 = number_format($rowbtl->totb_u, "2", ".", ",");
            $angaran_51 = $rowbtl->totb_u;
            $in_51 = $rowbtl->cp;
            $debet_51 = $rowbtl->debet;
            $kredit_51 = $rowbtl->kredit;
            $real_51 = $debet_51 - ($in_51 + $kredit_51);
            $nreal_51 = number_format($real_51, "2", ".", ",");
            $real_s_51 = $real_51 - $angaran_51;
            if ($real_s_51 < 0)
            {
                $x1 = "(";
                $real_s_51 = $real_s_51 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih_51 = number_format($real_s_51, "2", ".", ",");
            $per_51 = ($real_s_51 != 0) ? ($real_s_51 / $rowbtl->totb_u) * 100 : 0;
            $persen_51 = number_format($per_51);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Belanja Tidak Langsung</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_51</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_51</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_51$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_51</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";
        }


        if ($cetak == 1)
        {
            $sql_btl = "SELECT * FROM (SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                        ON LEFT(a.kd_rek5,3)=b.kd_rek3 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='51' GROUP BY SUBSTR(a.no_trdrka,12,25)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,27) AS kd_rek,b.nm_rek4 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,5)= LEFT(a.kd_rek5,5) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,5)= LEFT(a.kd_rek5,5) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'5' AS nu FROM trdrka a INNER JOIN ms_rek4 b 
                        ON LEFT(a.kd_rek5,5)=b.kd_rek4 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='51' GROUP BY SUBSTR(a.no_trdrka,12,27)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,29) AS kd_rek,a.nm_rek5 ,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND c.kd_rek5= a.kd_rek5 AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE  g.kd_skpd='$id' AND e.kd_rek5= a.kd_rek5 AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND f.kd_rek5= a.kd_rek5 AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'6' AS nu FROM trdrka a                     
                        WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='51' GROUP BY SUBSTR(a.no_trdrka,12,29),a.nm_rek5 ) a ORDER BY kd_rek,nu";
        }
        if ($cetak == 2)
        {
            $sql_btl = "SELECT * FROM (SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir' )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir' )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir' )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                        ON LEFT(a.kd_rek5,3)=b.kd_rek3 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='51' GROUP BY SUBSTR(a.no_trdrka,12,25)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,27) AS kd_rek,b.nm_rek4 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir' )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,5)= LEFT(a.kd_rek5,5) AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir')AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,5)= LEFT(a.kd_rek5,5) AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir' )AS kredit,'5' AS nu FROM trdrka a INNER JOIN ms_rek4 b 
                        ON LEFT(a.kd_rek5,5)=b.kd_rek4 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='51' GROUP BY SUBSTR(a.no_trdrka,12,27)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,29) AS kd_rek,a.nm_rek5 ,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND c.kd_rek5= a.kd_rek5 AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir')AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE  g.kd_skpd='$id' AND e.kd_rek5= a.kd_rek5 AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir' )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND f.kd_rek5= a.kd_rek5 AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir')AS kredit,'6' AS nu FROM trdrka a                     
                        WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='51' GROUP BY SUBSTR(a.no_trdrka,12,29),a.nm_rek5 ) a ORDER BY kd_rek,nu";
        }
        $btl = $this->db->query($sql_btl);

        foreach ($btl->result() as $row_btl)
        {
            $kode = $row_btl->kd_rek;
            $nama = $row_btl->nm_rek;
            $ang_511 = number_format($row_btl->nilai_u, "2", ".", ",");
            $angaran_511 = $row_btl->nilai_u;
            $in_511 = $row_btl->cp;
            $debet_511 = $row_btl->debet;
            $kredit_511 = $row_btl->kredit;
            $real_511 = $debet_511 - ($in_511 + $kredit_511);
            $nreal_511 = number_format($real_511, "2", ".", ",");
            $real_s_511 = $real_511 - $angaran_511;
            if ($real_s_511 < 0)
            {
                $x1 = "(";
                $real_s_511 = $real_s_511 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih_511 = number_format($real_s_511, "2", ".", ",");
            $per_511 = ($real_s_5 != 0) ? ($real_s_511 / $row_btl->nilai_u) * 100 : 0;
            $persen_511 = number_format($per_511);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">$kode</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_511</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_511</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_511$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_511</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"left\"></td>
                                     </tr>
                                     ";


        }
        $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";

        if ($cetak == 1)
        {
            $sqltbl = "SELECT SUM(a.nilai) AS totbl,SUM(a.nilai_ubah) AS totbl_u, 
                        (SELECT SUM(rupiah) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,2)='52' AND a.kd_skpd='$id') AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,2)='52' AND d.kd_skpd='$id') AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,2)='52' AND f.kd_skpd='$id') AS kredit FROM trdrka a
                        WHERE LEFT(kd_rek5,2)='52' AND kd_skpd='$id'";
        }
        if ($cetak == 2)
        {
            $sqltbl = "SELECT SUM(a.nilai) AS totbl,SUM(a.nilai_ubah) AS totbl_u, 
                        (SELECT SUM(rupiah) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,2)='52' AND a.kd_skpd='$id' AND b.tgl_sts>='$date_awal' AND b.tgl_sts<='$date_akhir') AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,2)='52' AND d.kd_skpd='$id' AND  d.tgl_voucher>='$date_awal' AND d.tgl_voucher<='$date_akhir') AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,2)='52' AND f.kd_skpd='$id' AND  f.tgl_voucher>='$date_awal' AND f.tgl_voucher<='$date_akhir') AS kredit FROM trdrka a
                        WHERE LEFT(kd_rek5,2)='52' AND kd_skpd='$id'";
        }
        $sqlbl = $this->db->query($sqltbl);
        foreach ($sqlbl->result() as $rowbl)
        {
            $ang_52 = number_format($rowbl->totbl_u, "2", ".", ",");
            $angaran_52 = $rowbl->totbl_u;
            $in_52 = $rowbl->cp;
            $debet_52 = $rowbl->debet;
            $kredit_52 = $rowbl->kredit;
            $real_52 = $debet_52 - ($in_52 + $kredit_52);
            $nreal_52 = number_format($real_52, "2", ".", ",");
            $real_s_52 = $real_52 - $angaran_52;
            if ($real_s_52 < 0)
            {
                $x1 = "(";
                $real_s_52 = $real_s_52 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih_52 = number_format($real_s_51, "2", ".", ",");
            $per_52 = ($real_s_5 != 0) ? ($real_s_51 / $rowbl->totbl_u) * 100 : 0;
            $persen_52 = number_format($per_52);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Belanja Langsung</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_52</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_52</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_52$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_52</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";
        }
        $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";


        if ($cetak == 1)
        {
            $sql_bl = "SELECT * FROM (SELECT a.kd_program AS kd_rek, c.nm_program AS nm_rek,(SELECT SUM(nilai) FROM trdrka WHERE LEFT(kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program) AS nilai,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program) AS nilai_u,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd g INNER JOIN trhkasin_pkd h ON g.no_sts = h.no_sts WHERE h.kd_skpd='$id' AND LEFT(h.kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program ) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd i  INNER JOIN trhju_pkd j ON i.no_voucher = j.no_voucher  WHERE j.kd_skpd='$id' AND LEFT(i.kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program ) AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd k  INNER JOIN trhju_pkd l ON k.no_voucher = l.no_voucher WHERE l.kd_skpd='$id' AND LEFT(k.kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program ) AS kredit,'1' AS nu FROM trskpd a INNER JOIN m_prog c ON a.kd_program1=c.kd_program
                        LEFT JOIN trdrka b ON a.kd_program=LEFT(b.kd_kegiatan,(LENGTH(a.kd_program))) WHERE a.kd_skpd='$id' AND RIGHT(a.kd_program,2) <>'00' GROUP BY a.kd_program,c.nm_program 
                        UNION ALL
                        SELECT a.kd_kegiatan AS kd_rek,c.nm_kegiatan AS nm_rek,SUM(b.nilai)AS nilai,SUM(b.nilai_ubah)AS nilai_u,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd g INNER JOIN trhkasin_pkd h ON g.no_sts = h.no_sts WHERE h.kd_skpd='$id' AND h.kd_kegiatan = a.kd_kegiatan ) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd i  INNER JOIN trhju_pkd j ON i.no_voucher = j.no_voucher  WHERE j.kd_skpd='$id' AND kd_kegiatan = a.kd_kegiatan ) AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd k  INNER JOIN trhju_pkd l ON k.no_voucher = l.no_voucher WHERE l.kd_skpd='$id' AND kd_kegiatan = a.kd_kegiatan ) AS kredit,'2' AS nu FROM trskpd a INNER JOIN m_giat c ON a.kd_kegiatan1=c.kd_kegiatan
                        LEFT JOIN trdrka b ON a.kd_kegiatan=b.kd_kegiatan WHERE a.kd_skpd='$id' AND RIGHT(a.kd_program,2) <>'00' GROUP BY a.kd_kegiatan,c.nm_kegiatan
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,24) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'3' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                        ON LEFT(a.kd_rek5,3)=b.kd_rek3   
                        WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='52' GROUP BY SUBSTR(a.no_trdrka,12,24)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                        ON LEFT(a.kd_rek5,3)=b.kd_rek3                   
                        WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='52' GROUP BY SUBSTR(a.no_trdrka,12,25)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,27) AS kd_rek,b.nm_rek4 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,5)= LEFT(a.kd_rek5,5) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,5)= LEFT(a.kd_rek5,5) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'5' AS nu FROM trdrka a INNER JOIN ms_rek4 b 
                        ON LEFT(a.kd_rek5,5)=b.kd_rek4                      
                        WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='52' GROUP BY SUBSTR(a.no_trdrka,12,27)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,29) AS kd_rek,a.nm_rek5 ,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE d.kd_skpd='$id' AND c.kd_rek5= a.kd_rek5 AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND e.kd_rek5= a.kd_rek5 AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND f.kd_rek5= a.kd_rek5 AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'6' AS nu FROM trdrka a                     
                        WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='52' GROUP BY SUBSTR(a.no_trdrka,12,29),a.nm_rek5 ) a ORDER BY kd_rek,nu";
        }
        if ($cetak == 2)
        {
            $sql_bl = "SELECT * FROM (SELECT a.kd_program AS kd_rek, c.nm_program AS nm_rek,(SELECT SUM(nilai) FROM trdrka WHERE LEFT(kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program) AS nilai,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program) AS nilai_u,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd g INNER JOIN trhkasin_pkd h ON g.no_sts = h.no_sts WHERE h.kd_skpd='$id' AND LEFT(h.kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program AND h.tgl_sts>='$date_awal' AND h.tgl_sts<='$date_akhir') AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd i  INNER JOIN trhju_pkd j ON i.no_voucher = j.no_voucher  WHERE j.kd_skpd='$id' AND LEFT(i.kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program AND j.tgl_voucher>='$date_awal' AND j.tgl_voucher<='$date_akhir') AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd k  INNER JOIN trhju_pkd l ON k.no_voucher = l.no_voucher WHERE l.kd_skpd='$id' AND LEFT(k.kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program AND l.tgl_voucher>='$date_awal' AND l.tgl_voucher<='2$date_akhir') AS kredit,'1' AS nu FROM trskpd a INNER JOIN m_prog c ON a.kd_program1=c.kd_program
                        LEFT JOIN trdrka b ON a.kd_program=LEFT(b.kd_kegiatan,(LENGTH(a.kd_program))) WHERE a.kd_skpd='$id' AND RIGHT(a.kd_program,2) <>'00' GROUP BY a.kd_program,c.nm_program 
                        UNION ALL
                        SELECT a.kd_kegiatan AS kd_rek,c.nm_kegiatan AS nm_rek,SUM(b.nilai)AS nilai,SUM(b.nilai_ubah)AS nilai_u,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd g INNER JOIN trhkasin_pkd h ON g.no_sts = h.no_sts WHERE h.kd_skpd='$id' AND h.kd_kegiatan = a.kd_kegiatan AND h.tgl_sts>='2012-11-01' AND h.tgl_sts <='$date_akhir') AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd i  INNER JOIN trhju_pkd j ON i.no_voucher = j.no_voucher  WHERE j.kd_skpd='$id' AND kd_kegiatan = a.kd_kegiatan AND j.tgl_voucher>='$date_awal' AND j.tgl_voucher<='$date_akhir') AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd k  INNER JOIN trhju_pkd l ON k.no_voucher = l.no_voucher WHERE l.kd_skpd='$id' AND kd_kegiatan = a.kd_kegiatan AND l.tgl_voucher>='$date_awal' AND l.tgl_voucher<='$date_akhir') AS kredit,'2' AS nu FROM trskpd a INNER JOIN m_giat c ON a.kd_kegiatan1=c.kd_kegiatan
                        LEFT JOIN trdrka b ON a.kd_kegiatan=b.kd_kegiatan WHERE a.kd_skpd='$id' AND RIGHT(a.kd_program,2) <>'00' GROUP BY a.kd_kegiatan,c.nm_kegiatan
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,24) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir' )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir' )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir' )AS kredit,'3' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                        ON LEFT(a.kd_rek5,3)=b.kd_rek3   
                        WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='52' GROUP BY SUBSTR(a.no_trdrka,12,24)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='2012-11-01' AND d.tgl_sts<='$date_akhir' )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir' )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir' )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                        ON LEFT(a.kd_rek5,3)=b.kd_rek3                   
                        WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='52' GROUP BY SUBSTR(a.no_trdrka,12,25)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,27) AS kd_rek,b.nm_rek4 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir' )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,5)= LEFT(a.kd_rek5,5) AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir')AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,5)= LEFT(a.kd_rek5,5) AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir' )AS kredit,'5' AS nu FROM trdrka a INNER JOIN ms_rek4 b 
                        ON LEFT(a.kd_rek5,5)=b.kd_rek4                      
                        WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='52' GROUP BY SUBSTR(a.no_trdrka,12,27)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,29) AS kd_rek,a.nm_rek5 ,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE d.kd_skpd='$id' AND c.kd_rek5= a.kd_rek5 AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir')AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND e.kd_rek5= a.kd_rek5 AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir' )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND f.kd_rek5= a.kd_rek5 AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir')AS kredit,'6' AS nu FROM trdrka a                     
                        WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='52' GROUP BY SUBSTR(a.no_trdrka,12,29),a.nm_rek5 ) a ORDER BY kd_rek,nu";
        }
        $bl = $this->db->query($sql_bl);
        foreach ($bl->result() as $row_bl)
        {
            $kode = $row_bl->kd_rek;
            $nama = $row_bl->nm_rek;
            $ang_522 = number_format($row_bl->nilai_u, "2", ".", ",");
            $angaran_522 = $row_bl->nilai_u;
            $in_522 = $row_bl->cp;
            $debet_522 = $row_bl->debet;
            $kredit_522 = $row_bl->kredit;
            $real_522 = $debet_522 - ($in_522 + $kredit_522);
            $nreal_522 = number_format($real_522, "2", ".", ",");
            $real_s_522 = $real_522 - $angaran_522;
            if ($real_s_522 < 0)
            {
                $x1 = "(";
                $real_s_522 = $real_s_522 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih_522 = number_format($real_s_522, "2", ".", ",");
            $per_522 = ($real_s_522 != 0) ? ($real_s_522 / $row_bl->nilai_u) * 100 : 0;
            $persen_522 = number_format($per_522);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">$kode</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_522</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_522</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_522$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_522</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"left\"></td>
                                     </tr>
                                     ";


        }
        $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";


        $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Belanja Daerah</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_5$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_5</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";
        $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";
        if($id=='1.20.05.01')
        {
                if ($cetak == 1)
                {
                    $sqltpm = "SELECT SUM(IFNULL(a.nilai,0)) AS totbl,SUM(a.nilai_ubah) AS totbl_u, 
                                (SELECT SUM(IFNULL(rupiah,0)) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,2)='61' AND a.kd_skpd='$id' ) AS cp,
                                (SELECT SUM(IFNULL(debet,0)) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,2)='61' AND d.kd_skpd='$id' ) AS debet,
                                (SELECT SUM(IFNULL(kredit,0)) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,2)='61' AND f.kd_skpd='$id' ) AS kredit FROM trdrka a
                                WHERE LEFT(kd_rek5,2)='61' AND kd_skpd='$id'";
                }
                if ($cetak == 2)
                {
                    $sqltpm = "SELECT SUM(IFNULL(a.nilai,0)) AS totbl,SUM(a.nilai_ubah) AS totbl_u, 
                                (SELECT SUM(IFNULL(rupiah,0)) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,2)='61' AND a.kd_skpd='$id' AND b.tgl_sts>='$date_awal' AND b.tgl_sts<='$date_akhir') AS cp,
                                (SELECT SUM(IFNULL(debet,0)) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,2)='61' AND d.kd_skpd='$id' AND  d.tgl_voucher>='$date_awal' AND d.tgl_voucher<='$date_akhir') AS debet,
                                (SELECT SUM(IFNULL(kredit,0)) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,2)='61' AND f.kd_skpd='$id' AND  f.tgl_voucher>='$date_awal' AND f.tgl_voucher<='$date_akhir') AS kredit FROM trdrka a
                                WHERE LEFT(kd_rek5,2)='61' AND kd_skpd='$id'";
                }
                $sqlpm = $this->db->query($sqltpm);
                foreach ($sqlpm->result() as $rowpm)
                {
                    $ang_61 = number_format($rowpm->totbl_u, "2", ".", ",");
                    $angaran_61 = $rowpm->totbl_u;
                    $in_61 = $rowpm->cp;
                    $debet_61 = $rowpm->debet;
                    $kredit_61 = $rowpm->kredit;
                    $real_61 = $debet_61 - ($in_61 + $kredit_61);
                    $nreal_61 = number_format($real_61, "2", ".", ",");
                    $real_s_61 = $real_61 - $angaran_61;
                    if ($real_s_61 < 0)
                    {
                        $x1 = "(";
                        $real_s_61 = $real_s_61 * -1;
                        $y1 = ")";
                    } else
                    {
                        $x1 = "";
                        $y1 = "";
                    }
                    $selisih_61 = number_format($real_s_61, "2", ".", ",");
                    $per_61 = ($real_s_61 != 0) ? ($real_s_61 / $rowpm->totbl_u) * 100 : 0;
                    $persen_61 = number_format($per_61);
                    //
                    //                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>
                    //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Pembiayaan Masuk</td>
                    //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_61</td>
                    //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_61</td>
                    //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_61$y1</td>
                    //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_61</td>
                    //                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                    //                                     </tr>";
                }
        
        
                if ($cetak == 1)
                {
                    $sqlpm = "SELECT * FROM (SELECT SUBSTR(a.no_trdrka,12,24) AS kd_rek,b.nm_rek2 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek2 b 
                                ON LEFT(a.kd_rek5,2)=b.kd_rek2 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='61' GROUP BY SUBSTR(a.no_trdrka,12,24)
                                UNION ALL
                                SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                                ON LEFT(a.kd_rek5,3)=b.kd_rek3 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='61' GROUP BY SUBSTR(a.no_trdrka,12,25)
                                UNION ALL
                                SELECT SUBSTR(a.no_trdrka,12,27) AS kd_rek,b.nm_rek4 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,5)= LEFT(a.kd_rek5,5) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,5)= LEFT(a.kd_rek5,5) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'5' AS nu FROM trdrka a INNER JOIN ms_rek4 b 
                                ON LEFT(a.kd_rek5,5)=b.kd_rek4 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='61' GROUP BY SUBSTR(a.no_trdrka,12,27)
                                UNION ALL
                                SELECT SUBSTR(a.no_trdrka,12,29) AS kd_rek,a.nm_rek5 ,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND c.kd_rek5= a.kd_rek5 AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE  g.kd_skpd='$id' AND e.kd_rek5= a.kd_rek5 AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND f.kd_rek5= a.kd_rek5 AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'6' AS nu FROM trdrka a                     
                                WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='61' GROUP BY SUBSTR(a.no_trdrka,12,29),a.nm_rek5 ) a ORDER BY kd_rek,nu";
                }
                if ($cetak == 2)
                {
                    $sqlpm = "SELECT * FROM (SELECT SUBSTR(a.no_trdrka,12,24) AS kd_rek,b.nm_rek2 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir' )AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='<strong>$date_awal</strong>' AND g.tgl_voucher<='$date_akhir' )AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir' )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek2 b 
                                ON LEFT(a.kd_rek5,2)=b.kd_rek2 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='61' GROUP BY SUBSTR(a.no_trdrka,12,24)
                                UNION ALL
                                SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='2012-10-01' AND d.tgl_sts<='$date_akhir' )AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir' )AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir' )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                                ON LEFT(a.kd_rek5,3)=b.kd_rek3 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='61' GROUP BY SUBSTR(a.no_trdrka,12,25)
                                UNION ALL
                                SELECT SUBSTR(a.no_trdrka,12,27) AS kd_rek,b.nm_rek4 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir' )AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,5)= LEFT(a.kd_rek5,5) AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir')AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,5)= LEFT(a.kd_rek5,5) AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir' )AS kredit,'5' AS nu FROM trdrka a INNER JOIN ms_rek4 b 
                                ON LEFT(a.kd_rek5,5)=b.kd_rek4 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='61' GROUP BY SUBSTR(a.no_trdrka,12,27)
                                UNION ALL
                                SELECT SUBSTR(a.no_trdrka,12,29) AS kd_rek,a.nm_rek5 ,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND c.kd_rek5= a.kd_rek5 AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir')AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE  g.kd_skpd='$id' AND e.kd_rek5= a.kd_rek5 AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir' )AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND f.kd_rek5= a.kd_rek5 AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir')AS kredit,'6' AS nu FROM trdrka a                     
                                WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='61' GROUP BY SUBSTR(a.no_trdrka,12,29),a.nm_rek5 ) a ORDER BY kd_rek,nu";
                }
                $sql611 = $this->db->query($sqlpm);
                foreach ($sql611->result() as $row_61)
                {
                    $kd = $row_61->kd_rek;
                    $nm = $row_61->nm_rek;
                    $ang_611 = number_format($row_61->nilai_u, "2", ".", ",");
                    $angaran_611 = $row_61->nilai_u;
                    $in_611 = $row_61->cp;
                    $debet_611 = $row_61->debet;
                    $kredit_611 = $row_61->kredit;
                    $real_611 = $debet_611 - ($in_611 + $kredit_611);
                    $nreal_611 = number_format($real_611, "2", ".", ",");
                    $real_s_611 = $real_611 - $angaran_611;
                    if ($real_s_611 < 0)
                    {
                        $x1 = "(";
                        $real_s_611 = $real_s_611 * -1;
                        $y1 = ")";
                    } else
                    {
                        $x1 = "";
                        $y1 = "";
                    }
                    $selisih_611 = number_format($real_s_611, "2", ".", ",");
                    $per_611 = ($real_s_611 != 0) ? ($real_s_611 / $row_61->nilai_u) * 100 : 0;
                    $persen_611 = number_format($per_611);
        
                    $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"15%\" align=\"left\">$kd</td>                                     
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"26%\">$nm</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"13%\" align=\"right\">$ang_611</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"13%\" align=\"right\">$nreal_611</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"13%\" align=\"right\">$x1$selisih_611$y1</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"3%\" align=\"right\">$persen_611</td>
                                             <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                             </tr>";
                }
                $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                             <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                             </tr>";
                if ($cetak == 1)
                {
                    $sqltpk = "SELECT SUM(IFNULL(a.nilai,0)) AS totbl,SUM(a.nilai_ubah) AS totbl_u, 
                                (SELECT SUM(IFNULL(rupiah,0)) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,2)='62' AND a.kd_skpd='$id' ) AS cp,
                                (SELECT SUM(IFNULL(debet,0)) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,2)='62' AND d.kd_skpd='$id' ) AS debet,
                                (SELECT SUM(IFNULL(kredit,0)) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,2)='62' AND f.kd_skpd='$id' ) AS kredit FROM trdrka a
                                WHERE LEFT(kd_rek5,2)='62' AND kd_skpd='$id' ";
                }
                if ($cetak == 2)
                {
                    $sqltpk = "SELECT SUM(IFNULL(a.nilai,0)) AS totbl,SUM(a.nilai_ubah) AS totbl_u, 
                                (SELECT SUM(IFNULL(rupiah,0)) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,2)='62' AND a.kd_skpd='$id' AND b.tgl_sts>='$date_awal' AND b.tgl_sts<='$date_akhir') AS cp,
                                (SELECT SUM(IFNULL(debet,0)) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,2)='62' AND d.kd_skpd='$id' AND  d.tgl_voucher>='$date_awal' AND d.tgl_voucher<='$date_akhir') AS debet,
                                (SELECT SUM(IFNULL(kredit,0)) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,2)='62' AND f.kd_skpd='$id' AND  f.tgl_voucher>='$date_awal' AND f.tgl_voucher<='$date_akhir') AS kredit FROM trdrka a
                                WHERE LEFT(kd_rek5,2)='62' AND kd_skpd='$id' ";
                }
                $sqlpk = $this->db->query($sqltpk);
                foreach ($sqlpk->result() as $rowpk)
                {
                    $ang_62 = number_format($rowpk->totbl_u, "2", ".", ",");
                    $angaran_62 = $rowpk->totbl_u;
                    //echo($angaran_62);
                    $in_62 = $rowpk->cp;
                    $debet_62 = $rowpk->debet;
                    $kredit_62 = $rowpk->kredit;
                    $real_62 = $debet_62 - ($in_62 + $kredit_62);
                    $nreal_62 = number_format($real_62, "2", ".", ",");
                    $real_s_62 = $real_62 - $angaran_62;
                    if ($real_s_62 < 0)
                    {
                        $x1 = "(";
                        $real_s_62 = $real_s_62 * -1;
                        $y1 = ")";
                    } else
                    {
                        $x1 = "";
                        $y1 = "";
                    }
                    $selisih_62 = number_format($real_s_62, "2", ".", ",");
                    $per_62 = ($real_s_62 != 0) ? ($real_s_62 / $rowpk->totbl_u) * 100 : 0;
                    $persen_62 = number_format($per_62);
        
                    //                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>
                    //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Pembiayaan Keluar</td>
                    //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_62</td>
                    //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_62</td>
                    //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_62$y1</td>
                    //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_62</td>
                    //                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                    //                                     </tr>";
                }
                if ($cetak == 1)
                {
                    $sqlpk = "SELECT * FROM (SELECT SUBSTR(a.no_trdrka,12,24) AS kd_rek,b.nm_rek2 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek2 b 
                                ON LEFT(a.kd_rek5,2)=b.kd_rek2 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='62' GROUP BY SUBSTR(a.no_trdrka,12,24)
                                UNION ALL
                                SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                                ON LEFT(a.kd_rek5,3)=b.kd_rek3 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='62' GROUP BY SUBSTR(a.no_trdrka,12,25)
                                UNION ALL
                                SELECT SUBSTR(a.no_trdrka,12,27) AS kd_rek,b.nm_rek4 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,5)= LEFT(a.kd_rek5,5) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,5)= LEFT(a.kd_rek5,5) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'5' AS nu FROM trdrka a INNER JOIN ms_rek4 b 
                                ON LEFT(a.kd_rek5,5)=b.kd_rek4 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='62' GROUP BY SUBSTR(a.no_trdrka,12,27)
                                UNION ALL
                                SELECT SUBSTR(a.no_trdrka,12,29) AS kd_rek,a.nm_rek5 ,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND c.kd_rek5= a.kd_rek5 AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE  g.kd_skpd='$id' AND e.kd_rek5= a.kd_rek5 AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND f.kd_rek5= a.kd_rek5 AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'6' AS nu FROM trdrka a                     
                                WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='62' GROUP BY SUBSTR(a.no_trdrka,12,29),a.nm_rek5 ) a ORDER BY kd_rek,nu";
                }
                if ($cetak == 2)
                {
                    $sqlpk = "SELECT * FROM (SELECT SUBSTR(a.no_trdrka,12,24) AS kd_rek,b.nm_rek2 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir' )AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='<strong>$date_awal</strong>' AND g.tgl_voucher<='$date_akhir' )AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir' )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek2 b 
                                ON LEFT(a.kd_rek5,2)=b.kd_rek2 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='62' GROUP BY SUBSTR(a.no_trdrka,12,24)
                                UNION ALL
                                SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='2012-10-01' AND d.tgl_sts<='$date_akhir' )AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir' )AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir' )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                                ON LEFT(a.kd_rek5,3)=b.kd_rek3 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='62' GROUP BY SUBSTR(a.no_trdrka,12,25)
                                UNION ALL
                                SELECT SUBSTR(a.no_trdrka,12,27) AS kd_rek,b.nm_rek4 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir' )AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,5)= LEFT(a.kd_rek5,5) AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir')AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,5)= LEFT(a.kd_rek5,5) AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir' )AS kredit,'5' AS nu FROM trdrka a INNER JOIN ms_rek4 b 
                                ON LEFT(a.kd_rek5,5)=b.kd_rek4 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='62' GROUP BY SUBSTR(a.no_trdrka,12,27)
                                UNION ALL
                                SELECT SUBSTR(a.no_trdrka,12,29) AS kd_rek,a.nm_rek5 ,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                                (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND c.kd_rek5= a.kd_rek5 AND d.kd_kegiatan = a.kd_kegiatan AND d.tgl_sts>='$date_awal' AND d.tgl_sts<='$date_akhir')AS cp,
                                (SELECT SUM(e.debet) FROM trdju_pkd e INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE  g.kd_skpd='$id' AND e.kd_rek5= a.kd_rek5 AND e.kd_kegiatan = a.kd_kegiatan AND g.tgl_voucher>='$date_awal' AND g.tgl_voucher<='$date_akhir' )AS debet,
                                (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND f.kd_rek5= a.kd_rek5 AND f.kd_kegiatan = a.kd_kegiatan AND h.tgl_voucher>='$date_awal' AND h.tgl_voucher<='$date_akhir')AS kredit,'6' AS nu FROM trdrka a                     
                                WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='62' GROUP BY SUBSTR(a.no_trdrka,12,29),a.nm_rek5 ) a ORDER BY kd_rek,nu";
                }
                $sql62 = $this->db->query($sqlpk);
                foreach ($sql62->result() as $row_62)
                {
                    $kd = $row_62->kd_rek;
                    $nm = $row_62->nm_rek;
                    $ang_622 = number_format($row_62->nilai_u, "2", ".", ",");
                    $angaran_622 = $row_62->nilai_u;
                    $in_622 = $row_62->cp;
                    $debet_622 = $row_62->debet;
                    $kredit_622 = $row_62->kredit;
                    $real_622 = $debet_622 - ($in_622 + $kredit_622);
                    $nreal_622 = number_format($real_622, "2", ".", ",");
                    $real_s_622 = $real_622 - $angaran_622;
                    if ($real_s_622 < 0)
                    {
                        $x1 = "(";
                        $real_s_622 = $real_s_622 * -1;
                        $y1 = ")";
                    } else
                    {
                        $x1 = "";
                        $y1 = "";
                    }
                    $selisih_622 = number_format($real_s_622, "2", ".", ",");
                    $per_622 = ($real_s_611 != 0) ? ($real_s_622 / $row_62->nilai_u) * 100 : 0;
                    $persen_622 = number_format($per_622);
        
                    $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"15%\" align=\"left\">$kd</td>                                     
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"26%\">$nm</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"13%\" align=\"right\">$ang_622</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"13%\" align=\"right\">$nreal_622</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"13%\" align=\"right\">$x1$selisih_622$y1</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"3%\" align=\"right\">$persen_622</td>
                                             <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                             </tr>";
                }
                $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                             <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                             </tr>";
        
                $netto = $angaran_61 - $angaran_62;
                $net = number_format($netto, "2", ",", ".");
                $netto_r = $real_61 - $real_62;
                $net_r = number_format($netto_r, "2", ",", ".");
                $netto_s = $netto_r - $netto;
                if ($netto_s < 0)
                {
                    $x1 = "(";
                    $netto_s = $netto_s * -1;
                    $y1 = ")";
                } else
                {
                    $x1 = "";
                    $y1 = "";
                }
                $netto_selisih = number_format($netto_s, "2", ",", ".");
                $perpnet = ($netto_s != 0) ? ($netto_s / $netto) * 100 : 0;
                $persennet = number_format($perpnet);
        
        
                $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Pembiayaan Netto</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$net</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$net_r</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$netto_selisih$y1</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persennet</td>
                                             <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                             </tr>";
        
                $silpa = ($angaranp + $angaran_61) - ($angaran_5 + $angaran_62);
                if ($silpa < 0)
                {
                    $a = "(";
                    $silpa = $silpa * -1;
                    $b = ")";
                } else
                {
                    $a = "";
                    $b = "";
                }
                $silp = number_format($silpa, "2", ",", ".");
                $silpa_real = ($realp + $real_61) - ($real_5 + $real_62);
                if ($silpa_real < 0)
                {
                    $c = "(";
                    $silpa_real = $silpa_real * -1;
                    $d = ")";
                } else
                {
                    $c = "";
                    $d = "";
                }
                $silp_real = number_format($silpa_real, "2", ",", ".");
                $silpa_s = $silpa_real - $silpa;
                if ($silpa_s < 0)
                {
                    $x = "(";
                    $silpa_s = $silpa_s * -1;
                    $y = ")";
                } else
                {
                    $x = "";
                    $y = "";
                }
                $silpa_selisih = number_format($silpa_s, "2", ",", ".");
                $persil = ($silpa_s != 0) ? ($silpa_s / $silpa) * 100 : 0;
                $persensilpa = number_format($persil);
            
                $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>
                                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">SILPA</td>
                                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$a$silp$b</td>
                                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$c$silp_real$d</td>
                                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x$silpa_selisih$y</td>
                                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persensilpa</td>
                                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                  
                                                   </tr>";
        }else{
            $silpa = $angaranp  - $angaran_5 ;
        if ($silpa < 0)
        {
            $a = "(";
            $silpa = $silpa * -1;
            $b = ")";
        } else
        {
            $a = "";
            $b = "";
        }
        $silp = number_format($silpa, "2", ",", ".");
        $silpa_real = $realp  - $real_5;
        if ($silpa_real < 0)
        {
            $c = "(";
            $silpa_real = $silpa_real * -1;
            $d = ")";
        } else
        {
            $c = "";
            $d = "";
        }
        $silp_real = number_format($silpa_real, "2", ",", ".");
        $silpa_s = $silpa_real - $silpa;
        if ($silpa_s < 0)
        {
            $x = "(";
            $silpa_s = $silpa_s * -1;
            $y = ")";
        } else
        {
            $x = "";
            $y = "";
        }
        $silpa_selisih = number_format($silpa_s, "2", ",", ".");
        $persil = ($silpa_s != 0) ? ($silpa_s / $silpa) * 100 : 0;
        $persensilpa = number_format($persil);
    
        $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>
                                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">SILPA</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$a$silp$b</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$c$silp_real$d</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x$silpa_selisih$y</td>
                                             <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persensilpa</td>
                                             <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
          
                                           </tr>";
            
        }                                   
        $cRet .= " </table>";
        $data['prev'] = $cRet;
        echo ("$cRet");
        // $this->template->set('title', 'CETAK RINCIAN REALISASI');
        //        $this->tukd_model->_mpdf('',$cRet,10,10,10,1);


    }

    function lampiran_I()
    {
        $data['page_title'] = 'REALISASI';
        $this->template->set('title', 'REALISASI');
        $this->template->load('template', 'akuntansi/perda_real_1', $data);
    }
    function lampiran_I_smstr()
    {
        $data['page_title'] = 'REALISASI SEMESTER I';
        $this->template->set('title', 'SEMESTER I');
        $this->template->load('template', 'akuntansi/perda_real_smstr', $data);
    }

    function cetak_perda1_real($lap = '', $ctk = '', $skpd = '')
    {
        $id = $skpd;
        $cetak = $ctk;
        $laporan = $lap;

        if ($laporan <> '2')
        {
            $sts = '';
            $jurnal = '';
        } else
        {
            $sts = "AND MONTH(d.tgl_sts)<='12'";
            $jurnal = "AND MONTH(d.tgl_voucher)<='12'";

        }
        $sqldns = "SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$id'";
        $sqlskpd = $this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns)
        {
            $kd_urusan = $rowdns->kd_u;
            $nm_urusan = $rowdns->nm_u;
            $kd_skpd = $rowdns->kd_sk;
            $nm_skpd = $rowdns->nm_sk;
        }

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kd_skpd='$id' and kode='PA'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd)
        {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan = $rowttd->jab;
        }

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc)
        {

            $tgl = $rowsc->tgl_rka;
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
            $kab = $rowsc->kab_kota;
            $daerah = $rowsc->daerah;
            $thn = $rowsc->thn_ang;
        }

        $jk = $this->rka_model->combo_skpd();

        $cRet = '';

        if ($cetak == '2')
        {
            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td rowspan=\"3\" align=\"left\" width=\"55%\"><strong></strong></td>
                         <td rowspan=\"3\" valign =\"top\" align=\"left\" width=\"15%\"><strong><h5>LAMPIRAN I  :</h6></strong></td>
                         <td colspan=\"2\" align=\"left\" width=\"30%\"><strong><h5>PERATURAN DAERAH</strong></h5></td>
                    </tr>
                    <tr> 
                         <td align=\"left\" width=\"10%\"><h5>NOMOR</h5></td><td width=\"20%\" align=\"left\" ><h5>:</h5></td>
                    </tr>
                    <tr>
                        <td style=\"border-bottom: solid 1px black;\" align=\"left\" width=\"10%\"><h5>TANGGAL</h5></td><td style=\"border-bottom: solid 1px black;\" align=\"left\" width=\"20%\"><h5>:</h5></td>
                    </tr>                                     
                   
                  </table>";
        }

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>$kab</strong></td>                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>RINGKASAN REALISASI APBD </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>TAHUN ANGARAN $thn</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";
        if ($cetak == '2')
        {
            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"0\">
                        <tr>
                            <td width=\"20%\">Urusan Organisasi </td>
                            <td width=\"80%\">: $kd_urusan - $nm_urusan</td>
                        </tr>
                        <tr>
                            <td>Organisasi</td>
                            <td>: $kd_skpd - $nm_skpd </td>
                        </tr>
                    
                    </table>";
        }

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NOMOR URUT</b></td>                            
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\" colspan=\"2\" ><b>JUMLAH (Rp.)</b></td>
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>LEBIH</br>(KURANG)</b></td>
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        <tr >
                             <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Anggaran</b></td>
                             <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Realisasi</b></td>                             
                        </tr>  
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                           
                         </tr>
                     </tfoot>
                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>";


        if ($cetak == '1')
        {
            $sql4 = "SELECT * FROM(SELECT a.kd_rek1 AS kd_rek,a.lra AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,1)= a.kd_rek1 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS kredit FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek5,(LENGTH(a.kd_rek1))) WHERE LEFT(a.kd_rek1,1)='4'  GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,2)= a.kd_rek2 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,1)='4'  GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,3)= a.kd_rek3 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,1)='4'  GROUP BY a.kd_rek3,a.lra, a.nm_rek3)a ORDER BY kd_rek";
        }
        if ($cetak == '2')
        {
            $sql4 = "SELECT * FROM(SELECT a.kd_rek1 AS kd_rek,a.lra AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(kd_rek5,1)= a.kd_rek1 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS kredit FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek5,(LENGTH(a.kd_rek1))) WHERE LEFT(a.kd_rek1,1)='4' AND b.kd_skpd='$id' GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM  trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,1)='4' AND b.kd_skpd='$id' GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,1)='4' AND b.kd_skpd='$id' GROUP BY a.kd_rek3,a.lra, a.nm_rek3)a ORDER BY kd_rek";
        }

        $query4 = $this->db->query($sql4);
        //$query = $this->skpd_model->getAllc();

        foreach ($query4->result() as $row4)
        {
            $kode = $row4->rek;
            $nama = $row4->nm_rek;
            $ang = number_format($row4->nilai, "2", ".", ",");
            $angaran = $row4->nilai;
            $pen = $row4->cp;
            $debet = $row4->debet;
            $kredit = $row4->kredit;
            $real = ($pen + $kredit) - $debet;
            $nreal = number_format($real, "2", ".", ",");
            $real_s = $real - $angaran;
            if ($real_s < 0)
            {
                $x1 = "(";
                $real_s = $real_s * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisih = number_format($real_s, "2", ".", ",");
            $per1 = ($real != 0) ? ($real / $row4->nilai) * 100 : 0;
            $persen1 = number_format($per1, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kode</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$ang</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nreal</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$x1$pend_selisih$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1</td>
                                 </tr>";
        }
        if ($cetak == '1')
        {
            $sqltp = "SELECT SUM(nilai) AS totp,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE LEFT(c.kd_rek5,1)='4' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,1)='4' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,1)='4' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,1)='4' ";
        }
        if ($cetak == '2')
        {
            $sqltp = "SELECT SUM(nilai) AS totp,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)='4' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)='4' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)='4' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,1)='4' AND kd_skpd='$id'";
        }
        $sqlp = $this->db->query($sqltp);
        foreach ($sqlp->result() as $rowp)
        {
            $angp = number_format($rowp->totp, "2", ".", ",");
            $angaranp = $rowp->totp;
            $penp = $rowp->cp;
            $debetp = $rowp->debet;
            $kreditp = $rowp->kredit;
            $realp = ($penp + $kreditp) - $debetp;
            $nrealp = number_format($realp, "2", ".", ",");
            $real_sp = $realp - $angaranp;
            if ($real_sp < 0)
            {
                $x1 = "(";
                $real_sp = $real_sp * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisihp = number_format($real_sp, "2", ".", ",");
            $per1p = ($realp != 0) ? ($realp / $rowp->totp) * 100 : 0;
            $persen1p = number_format($per1p, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Jumlah Pendapatan</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angp</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nrealp</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$x1$pend_selisihp$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1p</td>
                                 </tr>";
        }

        if ($cetak == '1')
        {
            $sql5 = "SELECT * FROM(SELECT a.kd_rek1 AS kd_rek,a.lra AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,1)= a.kd_rek1 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS kredit FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek5,(LENGTH(a.kd_rek1))) WHERE LEFT(a.kd_rek1,1)='5'  GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,2)= a.kd_rek2 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,1)='5'  GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,3)= a.kd_rek3 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,1)='5'  GROUP BY a.kd_rek3,a.lra, a.nm_rek3)a ORDER BY kd_rek";
        }
        if ($cetak == '2')
        {
            $sql5 = "SELECT * FROM(SELECT a.kd_rek1 AS kd_rek,a.lra AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(kd_rek5,1)= a.kd_rek1 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS kredit FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek5,(LENGTH(a.kd_rek1))) WHERE LEFT(a.kd_rek1,1)='5' AND b.kd_skpd='$id' GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM  trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,1)='5' AND b.kd_skpd='$id' GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,1)='5' AND b.kd_skpd='$id' GROUP BY a.kd_rek3,a.lra, a.nm_rek3)a ORDER BY kd_rek";
        }


        $query5 = $this->db->query($sql5);
        //$query = $this->skpd_model->getAllc();

        foreach ($query5->result() as $row5)
        {
            $kode5 = $row5->rek;
            $nama5 = $row5->nm_rek;
            $ang5 = number_format($row5->nilai, "2", ".", ",");
            $angaran5 = $row5->nilai;
            $pen5 = $row5->cp;
            $debet5 = $row5->debet;
            $kredit5 = $row5->kredit;
            $real5 = $debet5 - ($pen5 + $kredit5);
            $nreal5 = number_format($real5, "2", ".", ",");
            $real_s5 = $real5 - $angaran5;
            if ($real_s5 < 0)
            {
                $x1 = "(";
                $real_s5 = $real_s5 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisih5 = number_format($real_s5, "2", ".", ",");
            $per15 = ($real5 != 0) ? ($real5 / $row5->nilai) * 100 : 0;
            $persen15 = number_format($per15, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kode5</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$ang5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nreal5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$x1$pend_selisih5$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen15</td>
                                     
                                 </tr>";
        }

        if ($cetak == '1')
        {
            $sqltb = "SELECT SUM(nilai) AS totb,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE LEFT(c.kd_rek5,1)='5' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,1)='5' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,1)='5' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,1)='5' ";
        }
        if ($cetak == '2')
        {
            $sqltb = "SELECT SUM(nilai) AS totb,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)='5' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)='5' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)='5' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,1)='5' AND kd_skpd='$id'";
        }
        $sqlb = $this->db->query($sqltb);
        foreach ($sqlb->result() as $rowb)
        {
            $angb = number_format($rowb->totb, "2", ".", ",");
            $angaranb = $rowb->totb;
            $penb = $rowb->cp;
            $debetb = $rowb->debet;
            $kreditb = $rowb->kredit;
            $realb = $debetb - ($penb + $kreditb);
            $nrealb = number_format($realb, "2", ".", ",");
            $real_sb = $realb - $angaranb;
            if ($real_sb < 0)
            {
                $x1 = "(";
                $real_sb = $real_sb * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisihb = number_format($real_sb, "2", ".", ",");
            $per1b = ($realb != 0) ? ($realb / $rowb->totb) * 100 : 0;
            $persen1b = number_format($per1b, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Jumlah Belanja</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angb</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nrealb</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$x1$pend_selisihb$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1b</td>
                                 </tr>";
        }

        $suplus = $angaranp - $angaranb;

        if ($suplus < 0)
        {
            $s = "(";
            $suplus = $suplus * -1;
            $d = ")";
        } else
        {
            $s = "";
            $d = "";
        }
        $surp = number_format($suplus, 2, ',', '.');
        $suplusr = $realp - $realb;

        if ($suplusr < 0)
        {
            $j = "(";
            $suplusr = $suplusr * -1;
            $k = ")";
        } else
        {
            $j = "";
            $k = "";
        }
        $surpr = number_format($suplusr, 2, ',', '.');

        $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Surplus/Defisit</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$s$surp$d</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$j$surpr$k</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                </tr>";
        if ($cetak == '1')
        {
            $sqlpm = "SELECT * FROM(SELECT a.kd_rek1 AS kd_rek,a.lra AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,1)= a.kd_rek1 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS kredit FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek5,(LENGTH(a.kd_rek1))) WHERE LEFT(a.kd_rek1,1)='6'  GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,2)= a.kd_rek2 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,2)='61'  GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,3)= a.kd_rek3 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,2)='61'  GROUP BY a.kd_rek3,a.lra, a.nm_rek3)a ORDER BY kd_rek";
        }
        if ($cetak == '2')
        {
            $sqlpm = "SELECT * FROM(SELECT a.kd_rek1 AS kd_rek,a.lra AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(kd_rek5,1)= a.kd_rek1 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS kredit FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek5,(LENGTH(a.kd_rek1))) WHERE LEFT(a.kd_rek1,1)='6' AND b.kd_skpd='$id' GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM  trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,2)='61' AND b.kd_skpd='$id' GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,2)='61' AND b.kd_skpd='$id' GROUP BY a.kd_rek3,a.lra, a.nm_rek3)a ORDER BY kd_rek";
        }
        $query61 = $this->db->query($sqlpm);

        foreach ($query61->result() as $row61)
        {
            $kode61 = $row61->rek;
            $nama61 = $row61->nm_rek;
            $ang61 = number_format($row61->nilai, "2", ".", ",");
            $angaran61 = $row61->nilai;
            //echo($angaran61);
            $pen61 = $row61->cp;
            $debet61 = $row61->debet;
            $kredit61 = $row61->kredit;
            $real61 = ($pen61 + $kredit61) - $debet61;
            $nreal61 = number_format($real61, "2", ".", ",");
            $real_s61 = $real61 - $angaran61;
            if ($real_s61 < 0)
            {
                $x1 = "(";
                $real_s61 = $real_s61 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisih61 = number_format($real_s61, "2", ".", ",");
            $per161 = ($real61 != 0) ? ($real61 / $row61->nilai) * 100 : 0;
            $persen161 = number_format($per161, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kode61</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama61</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$ang61</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nreal61</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$x1$pend_selisih61$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen161</td>
                                 </tr>";
        }

        if ($cetak == '1')
        {
            $sqltpm = "SELECT SUM(nilai) AS totpm,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE LEFT(c.kd_rek5,2)='61' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,2)='61' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,2)='61' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,2)='61' ";
        }
        if ($cetak == '2')
        {
            $sqltpm = "SELECT SUM(nilai) AS totpm,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)='61' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)='61' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)='61' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,2)='61' AND kd_skpd='$id'";
        }
        $sqlpmasuk = $this->db->query($sqltpm);
        foreach ($sqlpmasuk->result() as $rowpm)
        {
            $angpm = number_format($rowpm->totpm, "2", ".", ",");
            $angaranpm = $rowpm->totpm;
            $penpm = $rowpm->cp;
            $debetpm = $rowpm->debet;
            $kreditpm = $rowpm->kredit;
            $realpm = ($penpm + $kreditpm) - $debetpm;
            $nrealpm = number_format($realpm, "2", ".", ",");
            $real_spm = $realpm - $angaranpm;
            if ($real_spm < 0)
            {
                $x1 = "(";
                $real_spm = $real_spm * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisihpm = number_format($real_spm, "2", ".", ",");
            $per1pm = ($realpm != 0) ? ($realpm / $rowpm->totpm) * 100 : 0;
            $persen1pm = number_format($per1pm, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Jumlah Pembiayaan masuk</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angpm</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nrealpm</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$x1$pend_selisihpm$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1pm</td>
                                 </tr>";
        }
        if ($cetak == '1')
        {
            $sqlpk = "SELECT * FROM(SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,2)= a.kd_rek2 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,2)='62'  GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,3)= a.kd_rek3 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,2)='62'  GROUP BY a.kd_rek3,a.lra, a.nm_rek3) a ORDER BY kd_rek";
        }
        if ($cetak == '2')
        {
            $sqlpk = "SELECT * FROM(SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM  trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,2)='62' AND b.kd_skpd='$id' GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,2)='62' AND b.kd_skpd='$id' GROUP BY a.kd_rek3,a.lra, a.nm_rek3) a ORDER BY kd_rek";
        }
        $query62 = $this->db->query($sqlpk);

        foreach ($query62->result() as $row62)
        {
            $kode62 = $row62->rek;
            $nama62 = $row62->nm_rek;
            $ang62 = number_format($row62->nilai, "2", ".", ",");
            $angaran62 = $row62->nilai;
            $pen62 = $row62->cp;
            $debet62 = $row62->debet;
            $kredit62 = $row62->kredit;
            $real62 = $debet62 - ($pen62 + $kredit62);
            $nreal62 = number_format($real62, "2", ".", ",");
            $real_s62 = $real62 - $angaran62;
            if ($real_s62 < 0)
            {
                $x1 = "(";
                $real_s62 = $real_s62 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisih62 = number_format($real_s62, "2", ".", ",");
            $per162 = ($real62 != 0) ? ($real62 / $row62->nilai) * 100 : 0;
            $persen162 = number_format($per162, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kode62</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama62</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$ang62</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nreal62</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$x1$pend_selisih62$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen162</td>
                                 </tr>";
        }

        if ($cetak == '1')
        {
            $sqltpk = "SELECT SUM(nilai) AS totpk,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE LEFT(c.kd_rek5,2)='62' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,2)='62' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,2)='62' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,2)='62' ";
        }
        if ($cetak == '2')
        {
            $sqltpk = "SELECT SUM(nilai) AS totpk,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)='62' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)='62' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)='62' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,2)='62' AND kd_skpd='$id'";
        }
        $sqlpkeluar = $this->db->query($sqltpk);
        foreach ($sqlpkeluar->result() as $rowpk)
        {
            $angpk = number_format($rowpk->totpk, "2", ".", ",");
            $angaranpk = $rowpk->totpk;
            $penpk = $rowpk->cp;
            $debetpk = $rowpk->debet;
            $kreditpk = $rowpk->kredit;
            $realpk = ($penpk + $kreditpk) - $debetpk;
            $nrealpk = number_format($realpm, "2", ".", ",");
            $real_spk = $realpk - $angaranpk;
            if ($real_spk < 0)
            {
                $x1 = "(";
                $real_spk = $real_spk * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisihpk = number_format($real_spk, "2", ".", ",");
            $per1pk = ($realpk != 0) ? ($realpk / $rowpk->totpk) * 100 : 0;
            $persen1pk = number_format($per1pk, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Jumlah Pembiayaan Keluar</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angpk</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nrealpk</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$x1$pend_selisihpk$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1pk</td>
                                 </tr>";
        }
        //echo($angaran62);
        $netto = $angaranpm - $angaranpk;
        $net = number_format($netto, 2, ',', '.');
        if ($netto < 0)
        {
            $x4 = "(";
            $netto = $netto * -1;
            $y4 = ")";
        } else
        {
            $x4 = "";
            $y4 = "";
        }

        $nettor = $realpm - $realpk;
        $netr = number_format($nettor, 2, ',', '.');
        if ($nettor < 0)
        {
            $x5 = "(";
            $nettor = $nettor * -1;
            $y5 = ")";
        } else
        {
            $x5 = "";
            $y5 = "";
        }

        $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Pembiayaan Netto</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$x4$net$y4</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$x5$netr$y5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                </tr>";
        $silpa = ($angaranp + $angaranpm) - ($angaranpk + $angaranb);
        if ($silpa < 0)
        {
            $c = "(";
            $silpa = $silpa * -1;
            $d = ")";
        } else
        {
            $c = "";
            $d = "";
        }
        $silp = number_format($silpa, 2, ',', '.');
        $silpar = ($realp + $realpm) - ($realb + $realpk);
        if ($silpar < 0)
        {
            $c1 = "(";
            $silpar = $silpar * -1;
            $d1 = ")";
        } else
        {
            $c1 = "";
            $d1 = "";
        }
        $silpr = number_format($silpar, 2, ',', '.');

        $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">SILPA</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$c$silp$d</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$c1$silpr$d1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                </tr>";

        $cRet .= "</table>";
        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';


        $this->template->set('title', 'CETAK PERDA REALISASI SEMESTER LAMPIRAN I');
        $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
    }

    function cetak_perda1_semester($lap = '', $ctk = '', $skpd = '')
    {
        $id = $skpd;
        $cetak = $ctk;
        $laporan = $lap;

        if ($laporan <> '2')
        {
            $sts = '';
            $jurnal = '';
        } else
        {
            $sts = "AND MONTH(d.tgl_sts)<='6'";
            $jurnal = "AND MONTH(d.tgl_voucher)<='6'";

        }
        $sqldns = "SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$id'";
        $sqlskpd = $this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns)
        {
            $kd_urusan = $rowdns->kd_u;
            $nm_urusan = $rowdns->nm_u;
            $kd_skpd = $rowdns->kd_sk;
            $nm_skpd = $rowdns->nm_sk;
        }

        $sqlttd1 = "SELECT nama as nm,nip as nip,jabatan as jab FROM ms_ttd where kd_skpd='$id' and kode='PA'";
        $sqlttd = $this->db->query($sqlttd1);
        foreach ($sqlttd->result() as $rowttd)
        {
            $nip = $rowttd->nip;
            $nama = $rowttd->nm;
            $jabatan = $rowttd->jab;
        }

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc)
        {

            $tgl = $rowsc->tgl_rka;
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
            $kab = $rowsc->kab_kota;
            $daerah = $rowsc->daerah;
            $thn = $rowsc->thn_ang;
        }

        $jk = $this->rka_model->combo_skpd();

        $cRet = '';

        if ($cetak == '2')
        {
            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td rowspan=\"3\" align=\"left\" width=\"55%\"><strong></strong></td>
                         <td rowspan=\"3\" valign =\"top\" align=\"left\" width=\"15%\"><strong><h5>LAMPIRAN I  :</h6></strong></td>
                         <td colspan=\"2\" align=\"left\" width=\"30%\"><strong><h5>PERATURAN DAERAH</strong></h5></td>
                    </tr>
                    <tr> 
                         <td align=\"left\" width=\"10%\"><h5>NOMOR</h5></td><td width=\"20%\" align=\"left\" ><h5>:</h5></td>
                    </tr>
                    <tr>
                        <td style=\"border-bottom: solid 1px black;\" align=\"left\" width=\"10%\"><h5>TANGGAL</h5></td><td style=\"border-bottom: solid 1px black;\" align=\"left\" width=\"20%\"><h5>:</h5></td>
                    </tr>                                     
                   
                  </table>";
        }

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>$kab</strong></td>                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>RINGKASAN REALISASI APBD SEMESTER I </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>TAHUN ANGARAN $thn</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";
        if ($cetak == '2')
        {
            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"0\">
                        <tr>
                            <td width=\"20%\">Urusan Organisasi </td>
                            <td width=\"80%\">: $kd_urusan - $nm_urusan</td>
                        </tr>
                        <tr>
                            <td>Organisasi</td>
                            <td>: $kd_skpd - $nm_skpd </td>
                        </tr>
                    
                    </table>";
        }

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NOMOR URUT</b></td>                            
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\" colspan=\"2\" ><b>JUMLAH (Rp.)</b></td>
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>PROGNOSIS</b></td>
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        <tr >
                             <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Anggaran</b></td>
                             <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Realisasi</b></td>                             
                        </tr>  
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                           
                         </tr>
                     </tfoot>
                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>";


        if ($cetak == '1')
        {
            $sql4 = "SELECT * FROM(SELECT a.kd_rek1 AS kd_rek,a.lra AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,1)= a.kd_rek1 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS kredit FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek5,(LENGTH(a.kd_rek1))) WHERE LEFT(a.kd_rek1,1)='4'  GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,2)= a.kd_rek2 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,1)='4'  GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,3)= a.kd_rek3 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,1)='4'  GROUP BY a.kd_rek3,a.lra, a.nm_rek3)a ORDER BY kd_rek";
        }
        if ($cetak == '2')
        {
            $sql4 = "SELECT * FROM(SELECT a.kd_rek1 AS kd_rek,a.lra AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(kd_rek5,1)= a.kd_rek1 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS kredit FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek5,(LENGTH(a.kd_rek1))) WHERE LEFT(a.kd_rek1,1)='4' AND b.kd_skpd='$id' GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM  trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,1)='4' AND b.kd_skpd='$id' GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,1)='4' AND b.kd_skpd='$id' GROUP BY a.kd_rek3,a.lra, a.nm_rek3)a ORDER BY kd_rek";
        }

        $query4 = $this->db->query($sql4);
        //$query = $this->skpd_model->getAllc();

        foreach ($query4->result() as $row4)
        {
            $kode = $row4->rek;
            $nama = $row4->nm_rek;
            $ang = number_format($row4->nilai, "2", ".", ",");
            $angaran = $row4->nilai;
            $pen = $row4->cp;
            $debet = $row4->debet;
            $kredit = $row4->kredit;
            $real = ($pen + $kredit) - $debet;
            $nreal = number_format($real, "2", ".", ",");
            $real_s = $real - $angaran;
            if ($real_s < 0)
            {
                $x1 = "(";
                $real_s = $real_s * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisih = number_format($real_s, "2", ".", ",");
            $per1 = ($real != 0) ? ($real / $row4->nilai) * 100 : 0;
            $persen1 = number_format($per1, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kode</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$ang</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nreal</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$pend_selisih</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1</td>
                                 </tr>";
        }
        if ($cetak == '1')
        {
            $sqltp = "SELECT SUM(nilai) AS totp,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE LEFT(c.kd_rek5,1)='4' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,1)='4' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,1)='4' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,1)='4' ";
        }
        if ($cetak == '2')
        {
            $sqltp = "SELECT SUM(nilai) AS totp,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)='4' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)='4' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)='4' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,1)='4' AND kd_skpd='$id'";
        }
        $sqlp = $this->db->query($sqltp);
        foreach ($sqlp->result() as $rowp)
        {
            $angp = number_format($rowp->totp, "2", ".", ",");
            $angaranp = $rowp->totp;
            $penp = $rowp->cp;
            $debetp = $rowp->debet;
            $kreditp = $rowp->kredit;
            $realp = ($penp + $kreditp) - $debetp;
            $nrealp = number_format($realp, "2", ".", ",");
            $real_sp = $realp - $angaranp;
            if ($real_sp < 0)
            {
                $x1 = "(";
                $real_sp = $real_sp * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisihp = number_format($real_sp, "2", ".", ",");
            $per1p = ($realp != 0) ? ($realp / $rowp->totp) * 100 : 0;
            $persen1p = number_format($per1p, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Jumlah Pendapatan</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angp</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nrealp</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$pend_selisihp</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1p</td>
                                 </tr>";
        }

        if ($cetak == '1')
        {
            $sql5 = "SELECT * FROM(SELECT a.kd_rek1 AS kd_rek,a.lra AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,1)= a.kd_rek1 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS kredit FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek5,(LENGTH(a.kd_rek1))) WHERE LEFT(a.kd_rek1,1)='5'  GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,2)= a.kd_rek2 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,1)='5'  GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,3)= a.kd_rek3 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,1)='5'  GROUP BY a.kd_rek3,a.lra, a.nm_rek3)a ORDER BY kd_rek";
        }
        if ($cetak == '2')
        {
            $sql5 = "SELECT * FROM(SELECT a.kd_rek1 AS kd_rek,a.lra AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(kd_rek5,1)= a.kd_rek1 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS kredit FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek5,(LENGTH(a.kd_rek1))) WHERE LEFT(a.kd_rek1,1)='5' AND b.kd_skpd='$id' GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM  trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,1)='5' AND b.kd_skpd='$id' GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,1)='5' AND b.kd_skpd='$id' GROUP BY a.kd_rek3,a.lra, a.nm_rek3)a ORDER BY kd_rek";
        }


        $query5 = $this->db->query($sql5);
        //$query = $this->skpd_model->getAllc();

        foreach ($query5->result() as $row5)
        {
            $kode5 = $row5->rek;
            $nama5 = $row5->nm_rek;
            $ang5 = number_format($row5->nilai, "2", ".", ",");
            $angaran5 = $row5->nilai;
            $pen5 = $row5->cp;
            $debet5 = $row5->debet;
            $kredit5 = $row5->kredit;
            $real5 = $debet5 - ($pen5 + $kredit5);
            $nreal5 = number_format($real5, "2", ".", ",");
            $real_s5 = $real5 - $angaran5;
            if ($real_s5 < 0)
            {
                $x1 = "(";
                $real_s5 = $real_s5 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisih5 = number_format($real_s5, "2", ".", ",");
            $per15 = ($real5 != 0) ? ($real5 / $row5->nilai) * 100 : 0;
            $persen15 = number_format($per15, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kode5</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$ang5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nreal5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$pend_selisih5$</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen15</td>
                                     
                                 </tr>";
        }

        if ($cetak == '1')
        {
            $sqltb = "SELECT SUM(nilai) AS totb,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE LEFT(c.kd_rek5,1)='5' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,1)='5' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,1)='5' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,1)='5' ";
        }
        if ($cetak == '2')
        {
            $sqltb = "SELECT SUM(nilai) AS totb,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)='5' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)='5' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)='5' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,1)='5' AND kd_skpd='$id'";
        }
        $sqlb = $this->db->query($sqltb);
        foreach ($sqlb->result() as $rowb)
        {
            $angb = number_format($rowb->totb, "2", ".", ",");
            $angaranb = $rowb->totb;
            $penb = $rowb->cp;
            $debetb = $rowb->debet;
            $kreditb = $rowb->kredit;
            $realb = $debetb - ($penb + $kreditb);
            $nrealb = number_format($realb, "2", ".", ",");
            $real_sb = $realb - $angaranb;
            if ($real_sb < 0)
            {
                $x1 = "(";
                $real_sb = $real_sb * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisihb = number_format($real_sb, "2", ".", ",");
            $per1b = ($realb != 0) ? ($realb / $rowb->totb) * 100 : 0;
            $persen1b = number_format($per1b, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Jumlah Belanja</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angb</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nrealb</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$pend_selisihb</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1b</td>
                                 </tr>";
        }

        $suplus = $angaranp - $angaranb;

        if ($suplus < 0)
        {
            $s = "(";
            $suplus = $suplus * -1;
            $d = ")";
        } else
        {
            $s = "";
            $d = "";
        }
        $surp = number_format($suplus, 2, ',', '.');
        $suplusr = $realp - $realb;

        if ($suplusr < 0)
        {
            $j = "(";
            $suplusr = $suplusr * -1;
            $k = ")";
        } else
        {
            $j = "";
            $k = "";
        }
        $surpr = number_format($suplusr, 2, ',', '.');

        $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Surplus/Defisit</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$s$surp$d</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$j$surpr$k</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                </tr>";
        if ($cetak == '1')
        {
            $sqlpm = "SELECT * FROM(SELECT a.kd_rek1 AS kd_rek,a.lra AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,1)= a.kd_rek1 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS kredit FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek5,(LENGTH(a.kd_rek1))) WHERE LEFT(a.kd_rek1,1)='6'  GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,2)= a.kd_rek2 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,2)='61'  GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,3)= a.kd_rek3 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,2)='61'  GROUP BY a.kd_rek3,a.lra, a.nm_rek3)a ORDER BY kd_rek";
        }
        if ($cetak == '2')
        {
            $sqlpm = "SELECT * FROM(SELECT a.kd_rek1 AS kd_rek,a.lra AS rek, a.nm_rek1 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(kd_rek5,1)= a.kd_rek1 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,1)=a.kd_rek1 $jurnal)AS kredit FROM ms_rek1 a INNER JOIN trdrka b
                        ON a.kd_rek1=LEFT(b.kd_rek5,(LENGTH(a.kd_rek1))) WHERE LEFT(a.kd_rek1,1)='6' AND b.kd_skpd='$id' GROUP BY a.kd_rek1,a.lra, a.nm_rek1
                        UNION ALL 
                        SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM  trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,2)='61' AND b.kd_skpd='$id' GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,2)='61' AND b.kd_skpd='$id' GROUP BY a.kd_rek3,a.lra, a.nm_rek3)a ORDER BY kd_rek";
        }
        $query61 = $this->db->query($sqlpm);

        foreach ($query61->result() as $row61)
        {
            $kode61 = $row61->rek;
            $nama61 = $row61->nm_rek;
            $ang61 = number_format($row61->nilai, "2", ".", ",");
            $angaran61 = $row61->nilai;
            //echo($angaran61);
            $pen61 = $row61->cp;
            $debet61 = $row61->debet;
            $kredit61 = $row61->kredit;
            $real61 = ($pen61 + $kredit61) - $debet61;
            $nreal61 = number_format($real61, "2", ".", ",");
            $real_s61 = $real61 - $angaran61;
            if ($real_s61 < 0)
            {
                $x1 = "(";
                $real_s61 = $real_s61 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisih61 = number_format($real_s61, "2", ".", ",");
            $per161 = ($real61 != 0) ? ($real61 / $row61->nilai) * 100 : 0;
            $persen161 = number_format($per161, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kode61</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama61</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$ang61</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nreal61</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$pend_selisih61</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen161</td>
                                 </tr>";
        }

        if ($cetak == '1')
        {
            $sqltpm = "SELECT SUM(nilai) AS totpm,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE LEFT(c.kd_rek5,2)='61' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,2)='61' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,2)='61' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,2)='61' ";
        }
        if ($cetak == '2')
        {
            $sqltpm = "SELECT SUM(nilai) AS totpm,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)='61' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)='61' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)='61' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,2)='61' AND kd_skpd='$id'";
        }
        $sqlpmasuk = $this->db->query($sqltpm);
        foreach ($sqlpmasuk->result() as $rowpm)
        {
            $angpm = number_format($rowpm->totpm, "2", ".", ",");
            $angaranpm = $rowpm->totpm;
            $penpm = $rowpm->cp;
            $debetpm = $rowpm->debet;
            $kreditpm = $rowpm->kredit;
            $realpm = ($penpm + $kreditpm) - $debetpm;
            $nrealpm = number_format($realpm, "2", ".", ",");
            $real_spm = $realpm - $angaranpm;
            if ($real_spm < 0)
            {
                $x1 = "(";
                $real_spm = $real_spm * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisihpm = number_format($real_spm, "2", ".", ",");
            $per1pm = ($realpm != 0) ? ($realpm / $rowpm->totpm) * 100 : 0;
            $persen1pm = number_format($per1pm, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Jumlah Pembiayaan masuk</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angpm</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nrealpm</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$pend_selisihpm</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1pm</td>
                                 </tr>";
        }
        if ($cetak == '1')
        {
            $sqlpk = "SELECT * FROM(SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,2)= a.kd_rek2 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,2)='62'  GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE  LEFT(c.kd_rek5,3)= a.kd_rek3 $sts) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE  LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,2)='62'  GROUP BY a.kd_rek3,a.lra, a.nm_rek3) a ORDER BY kd_rek";
        }
        if ($cetak == '2')
        {
            $sqlpk = "SELECT * FROM(SELECT a.kd_rek2 AS kd_rek,a.lra AS rek,a.nm_rek2 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM  trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)=a.kd_rek2 $jurnal)AS kredit FROM ms_rek2 a INNER JOIN trdrka b
                        ON a.kd_rek2=LEFT(b.kd_rek5,(LENGTH(a.kd_rek2))) WHERE LEFT(a.kd_rek2,2)='62' AND b.kd_skpd='$id' GROUP BY a.kd_rek2,a.lra,a.nm_rek2 
                        UNION ALL 
                        SELECT a.kd_rek3 AS kd_rek,a.lra AS rek,a.nm_rek3 AS nm_rek ,SUM(b.nilai) AS nilai,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $sts)AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher  WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,3)=a.kd_rek3 $jurnal)AS kredit FROM ms_rek3 a INNER JOIN trdrka b
                        ON a.kd_rek3=LEFT(b.kd_rek5,(LENGTH(a.kd_rek3))) WHERE LEFT(a.kd_rek3,2)='62' AND b.kd_skpd='$id' GROUP BY a.kd_rek3,a.lra, a.nm_rek3) a ORDER BY kd_rek";
        }
        $query62 = $this->db->query($sqlpk);

        foreach ($query62->result() as $row62)
        {
            $kode62 = $row62->rek;
            $nama62 = $row62->nm_rek;
            $ang62 = number_format($row62->nilai, "2", ".", ",");
            $angaran62 = $row62->nilai;
            $pen62 = $row62->cp;
            $debet62 = $row62->debet;
            $kredit62 = $row62->kredit;
            $real62 = $debet62 - ($pen62 + $kredit62);
            $nreal62 = number_format($real62, "2", ".", ",");
            $real_s62 = $real62 - $angaran62;
            if ($real_s62 < 0)
            {
                $x1 = "(";
                $real_s62 = $real_s62 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisih62 = number_format($real_s62, "2", ".", ",");
            $per162 = ($real62 != 0) ? ($real62 / $row62->nilai) * 100 : 0;
            $persen162 = number_format($per162, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kode62</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama62</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$ang62</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nreal62</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$pend_selisih62</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen162</td>
                                 </tr>";
        }

        if ($cetak == '1')
        {
            $sqltpk = "SELECT SUM(nilai) AS totpk,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE LEFT(c.kd_rek5,2)='62' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,2)='62' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE  LEFT(c.kd_rek5,2)='62' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,2)='62' ";
        }
        if ($cetak == '2')
        {
            $sqltpk = "SELECT SUM(nilai) AS totpk,(SELECT SUM(rupiah)FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts=d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)='62' $sts)AS cp,
                        (SELECT SUM(debet)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)='62' $jurnal)AS debet,
                        (SELECT SUM(kredit)FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,2)='62' $jurnal)AS kredit  
                        FROM trdrka WHERE LEFT(kd_rek5,2)='62' AND kd_skpd='$id'";
        }
        $sqlpkeluar = $this->db->query($sqltpk);
        foreach ($sqlpkeluar->result() as $rowpk)
        {
            $angpk = number_format($rowpk->totpk, "2", ".", ",");
            $angaranpk = $rowpk->totpk;
            $penpk = $rowpk->cp;
            $debetpk = $rowpk->debet;
            $kreditpk = $rowpk->kredit;
            $realpk = ($penpk + $kreditpk) - $debetpk;
            $nrealpk = number_format($realpm, "2", ".", ",");
            $real_spk = $realpk - $angaranpk;
            if ($real_spk < 0)
            {
                $x1 = "(";
                $real_spk = $real_spk * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisihpk = number_format($real_spk, "2", ".", ",");
            $per1pk = ($realpk != 0) ? ($realpk / $rowpk->totpk) * 100 : 0;
            $persen1pk = number_format($per1pk, "2", ".", ",");

            $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Jumlah Pembiayaan Keluar</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angpk</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nrealpk</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$pend_selisihpk</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1pk</td>
                                 </tr>";
        }
        //echo($angaran62);
        $netto = $angaranpm - $angaranpk;
        $net = number_format($netto, 2, ',', '.');
        if ($netto < 0)
        {
            $x4 = "(";
            $netto = $netto * -1;
            $y4 = ")";
        } else
        {
            $x4 = "";
            $y4 = "";
        }

        $nettor = $realpm - $realpk;
        $netr = number_format($nettor, 2, ',', '.');
        if ($nettor < 0)
        {
            $x5 = "(";
            $nettor = $nettor * -1;
            $y5 = ")";
        } else
        {
            $x5 = "";
            $y5 = "";
        }

        $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">Pembiayaan Netto</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$x4$net$y4</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$x5$netr$y5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                </tr>";
        $silpa = ($angaranp + $angaranpm) - ($angaranpk + $angaranb);
        if ($silpa < 0)
        {
            $c = "(";
            $silpa = $silpa * -1;
            $d = ")";
        } else
        {
            $c = "";
            $d = "";
        }
        $silp = number_format($silpa, 2, ',', '.');
        $silpar = ($realp + $realpm) - ($realb + $realpk);
        if ($silpar < 0)
        {
            $c1 = "(";
            $silpar = $silpar * -1;
            $d1 = ")";
        } else
        {
            $c1 = "";
            $d1 = "";
        }
        $silpr = number_format($silpar, 2, ',', '.');

        $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">SILPA</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$c$silp$d</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$c1$silpr$d1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                </tr>";

        $cRet .= "</table>";
        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';


        $this->template->set('title', 'CETAK PERDA REALISASI SEMESTER LAMPIRAN I');
        $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
        //$this->template->load('template','anggaran/rka/perkadaII',$data);
    }

    function cetak_lra($cetak = 1, $lap = 0)
    {
        if ($lap == 1)
        {
            $tabel = 'realisasi_ppkd';
        } else
        {
            $tabel = 'realisasi';
        }
        //function cetak_lra(){
        $sql41 = "SELECT SUM(anggaran) as anggaran,SUM(real_spj) as nilai FROM $tabel WHERE left(kd_rek5,1)='4' ";
        $query41 = $this->db->query($sql41);
        $jmlp = $query41->row();
        $jmlpendapatan = $jmlp->nilai;
        $jmlangpendapatan = $jmlp->anggaran;
        $jmlangpendapatan1 = number_format($jmlp->anggaran, "2", ".", ",");
        $jmlpendapatan1 = number_format($jmlp->nilai, "2", ".", ",");

        $sql51 = "SELECT SUM(anggaran) as angaran,SUM(real_spj)as nilai FROM $tabel WHERE left(kd_rek5,1)='5' ";
        $query51 = $this->db->query($sql51);
        $jmlb = $query51->row();
        $jmlangbelanja = $jmlb->angaran;
        $jmlbelanja = $jmlb->nilai;
        $jmlbelanja1 = number_format($jmlb->nilai, "2", ".", ",");
        $jmlangbelanja1 = number_format($jmlb->angaran, "2", ".", ",");
        $sql523 = "SELECT SUM(anggaran) as anggaran,SUM(real_spj) as nilai FROM $tabel WHERE left(kd_rek5,3)='523' ";
        $query523 = $this->db->query($sql523);
        $jmlbm = $query523->row();
        $jmlbmbelanja = $jmlbm->nilai;
        $jmlangbmbelanja = $jmlbm->anggaran;
        $jmlbmbelanja1 = number_format($jmlbmbelanja, "2", ".", ",");
        $jmlangbmbelanja1 = number_format($jmlangbmbelanja, "2", ".", ",");
        $sql61 = "SELECT SUM(anggaran) as anggaran,SUM(real_spj) as nilai FROM $tabel WHERE left(kd_rek5,2)='61' ";
        $query61 = $this->db->query($sql61);
        $jmlpm = $query61->row();
        $jmlpmasuk = $jmlpm->nilai;
        $jmlangpmasuk = $jmlpm->anggaran;
        $sql62 = "SELECT SUM(anggaran) as anggaran,SUM(real_spj) as nilai FROM $tabel WHERE left(kd_rek5,2)='62' ";
        $query62 = $this->db->query($sql62);
        $jmlpk = $query62->row();
        $jmlpkeluar = $jmlpk->nilai;
        $jmlangpkeluar = $jmlpk->anggaran;
        $surplus = $jmlpendapatan - $jmlbelanja;
        $angsurplus = $jmlangpendapatan - $jmlangbelanja;
        if ($surplus < 0)
        {
            $x = "(";
            $surplus = $surplus * -1;
            $y = ")";
        } else
        {
            $x = "";
            $y = "";
        }
        if ($angsurplus < 0)
        {
            $e = "(";
            $angsurplus = $angsurplus * -1;
            $f = ")";
        } else
        {
            $e = "";
            $f = "";
        }
        $surplus1 = number_format($surplus, "2", ".", ",");
        $angsurplus1 = number_format($angsurplus, "2", ".", ",");
        $biaya_net = $jmlpmasuk - $jmlpkeluar;
        $angbiaya_net = $jmlangpmasuk - $jmlangpkeluar;
        if ($biaya_net < 0)
        {
            $a = "(";
            $biaya_net = $biaya_net * -1;
            $b = ")";
        } else
        {
            $a = "";
            $b = "";
        }
        if ($angbiaya_net < 0)
        {
            $g = "(";
            $angbiaya_net = $angbiaya_net * -1;
            $h = ")";
        } else
        {
            $g = "";
            $h = "";
        }
        $biaya_net1 = number_format($biaya_net, "2", ".", ",");
        $angbiaya_net1 = number_format($angbiaya_net, "2", ".", ",");
        $silpa = ($jmlpendapatan + $jmlpmasuk) - ($jmlbelanja + $jmlpkeluar);
        $angsilpa = ($jmlangpendapatan + $jmlangpmasuk) - ($jmlangbelanja + $jmlangpkeluar);
        if ($silpa < 0)
        {
            $c = "(";
            $silpa = $silpa * -1;
            $d = ")";
        } else
        {
            $c = "";
            $d = "";
        }
        if ($angsilpa < 0)
        {
            $i = "(";
            $angsilpa = $angsilpa * -1;
            $j = ")";
        } else
        {
            $i = "";
            $j = "";
        }
        $silpa1 = number_format($silpa, "2", ".", ",");
        $angsilpa1 = number_format($angsilpa, "2", ".", ",");
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc)
        {

            $tgl = $rowsc->tgl_rka;
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
            $kab = $rowsc->kab_kota;
            $daerah = $rowsc->daerah;
            $thn = $rowsc->thn_ang;
        }

        $jk = $this->rka_model->combo_skpd();

        $cRet = '';


        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>$kab</strong></td>                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>LAPORAN REALISASI ANGGARAN </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>TAHUN ANGGARAN $thn</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";


        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NO</b></td>                            
                            <td  bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>ANGGARAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>REALISASI</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>LEBIH</br>(KURANG)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                           
                         </tr>
                     </tfoot>
                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>";


        $sql4 = "SELECT IFNULL(a.kode,\" \") as kode,a.seq,a.nor,a.uraian,ifnull(a.kode_1,\"''\") as kode_1,thn_m1 AS lalu FROM map_lra_ppkd_kab a GROUP BY a.nor,a.uraian,a.kode_1 ORDER BY a.seq";


        $query4 = $this->db->query($sql4);
        //$query = $this->skpd_model->getAllc();
        $no = 0;
        foreach ($query4->result() as $row4)
        {
            //$kode=$row4->rek;
            $nama = $row4->uraian;

            $real_lalu = number_format($row4->lalu, "2", ".", ",");
            $kod = $row4->kode;
            $n = $row4->kode_1;
            //echo $n;
            $sql5 = "SELECT SUM(b.anggaran) as anggaran,SUM(b.real_spj) as nilai FROM $tabel b LEFT JOIN ms_rek5 c ON b.kd_rek5=c.kd_rek5 WHERE c.map_lra1 IN (" .
                $n . ") ";
            //echo $sql5;
            $query5 = $this->db->query($sql5);
            $trh = $query5->row();
            $nil = $trh->nilai;
            $angnil = $trh->anggaran;

            //$nilai=0;
            //$real_s = $trh->nilai - $row4->lalu ;
            $real_s = $trh->nilai - $trh->anggaran;
            if ($real_s < 0)
            {
                $x1 = "(";
                $real_s = $real_s * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih = number_format($real_s, "2", ".", ",");
            if ($trh->nilai == 0)
            {
                $tmp = 1;
            } else
            {
                $tmp = $trh->nilai;
            }
            $nilai = number_format($trh->nilai, "2", ".", ",");
            $angnilai = number_format($trh->anggaran, "2", ".", ",");
            $per1 = ($real_s != 0) ? ($real_s / $tmp) * 100 : 0;
            $persen1 = number_format($per1);
            $no = $no + 1;
            switch ($row4->nor)
            {
                case 1:
                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kod</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlangpendapatan1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlpendapatan1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
               // case 165:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kod</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlangpendapatan1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlpendapatan1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//                case 175:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kod</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlangbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//                case 325:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kod</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlangbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//                case 335:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kod</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$e$angsurplus1$f</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$x$surplus1$y</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//                case 490:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kod</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$g$angbiaya_net1$h</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$a$biaya_net1$b</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//                case 495:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kod</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$i$angsilpa1$j</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$c$silpa1$d</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//                case 35:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kod</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlbmbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlbmbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//                case 52:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kod</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlangbmbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlbmbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
                default:
                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$kod</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$angnilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$x1$selisih$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1</td>
                                 </tr>";

            }


        }


        // $cRet         .= "</table>";
        //$data['prev']  = $cRet;
        //$data['sikap'] = 'preview';


        //$this->template->set('title', 'LAPORAN OPERASIONAL');
        //$this->tukd_model->_mpdf('',$cRet,10,10,10,'0');


        $cRet .= " </table>";
        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul = 'LAPORAN REALISASI ANGGARAN';
        $this->template->set('title', 'CETAK LAPORAN REALISASI ANGGARAN');
        switch ($cetak)
        {
            case 1;
                $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }


        //$this->template->load('template','anggaran/rka/perkadaII',$data);
    }
    
    function ctk_lra_lo_pemda($pilih = 1, $ctk = '', $lap = 0, $skpd = '')
    {   
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1= $thn_ang-1;  
        
        $id = $skpd;
        $cetak = $ctk;
        if ($lap == 1)
        {
            $tabel = 'realisasi';
        } else
        {
            $tabel = 'realisasi';
        }
        //echo($tabel);
        if ($cetak == '1')
        {
            $skpd = '';
            $skpd1 = '';
        } else
        {
            $skpd = "AND kd_skpd='$id'";
            $skpd1 = "AND b.kd_skpd='$id'";
        }

        $sqldns = "SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$id'";
        $sqlskpd = $this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns)
        {
            $kd_urusan = $rowdns->kd_u;
            $nm_urusan = $rowdns->nm_u;
            $kd_skpd = $rowdns->kd_sk;
            $nm_skpd = $rowdns->nm_sk;
        }

        $sql41 = "SELECT SUM(real_spj) as nilai FROM $tabel WHERE left(kd_rek5,1)='8' $skpd";
        $query41 = $this->db->query($sql41);
        $jmlp = $query41->row();
        $jmlpendapatan = $jmlp->nilai;
        $jmlpendapatan1 = number_format($jmlp->nilai, "2", ".", ",");


        $sql51 = "SELECT SUM(real_spj) as nilai FROM $tabel WHERE left(kd_rek5,1)='9' $skpd";
        $query51 = $this->db->query($sql51);
        $jmlb = $query51->row();
        $jmlbelanja = $jmlb->nilai;
        $jmlbelanja1 = number_format($jmlb->nilai, "2", ".", ",");
        $sql523 = "SELECT SUM(real_spj) as nilai FROM $tabel WHERE left(kd_rek5,3)='923' $skpd";
        $query523 = $this->db->query($sql523);
        $jmlbm = $query523->row();
        $jmlbmbelanja = $jmlbm->nilai;
        $jmlbmbelanja1 = number_format($jmlbmbelanja, "2", ".", ",");
        $sql61 = "SELECT SUM(real_spj) as nilai FROM $tabel WHERE left(kd_rek5,2)='71' $skpd";
        $query61 = $this->db->query($sql61);
        $jmlpm = $query61->row();
        $jmlpmasuk = $jmlpm->nilai;
        $sql62 = "SELECT SUM(real_spj) as nilai FROM $tabel WHERE left(kd_rek5,2)='72' $skpd";
        $query62 = $this->db->query($sql62);
        $jmlpk = $query62->row();
        $jmlpkeluar = $jmlpk->nilai;
        $surplus = $jmlpendapatan - $jmlbelanja;

        if ($surplus < 0)
        {
            $x = "(";
            $surplus = $surplus * -1;
            $y = ")";
        } else
        {
            $x = "";
            $y = "";
        }
        $surplus1 = number_format($surplus, "2", ".", ",");
        $biaya_net = $jmlpmasuk - $jmlpkeluar;

        if ($biaya_net < 0)
        {
            $a = "(";
            $biaya_net = $biaya_net * -1;
            $b = ")";
        } else
        {
            $a = "";
            $b = "";
        }
        $biaya_net1 = number_format($biaya_net, "2", ".", ",");
        $silpa = ($jmlpendapatan + $jmlpmasuk) - ($jmlbelanja + $jmlpkeluar);

        if ($silpa < 0)
        {
            $c = "(";
            $silpa = $silpa * -1;
            $d = ")";
        } else
        {
            $c = "";
            $d = "";
        }
        $silpa1 = number_format($silpa, "2", ".", ",");
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc)
        {

            $tgl = $rowsc->tgl_rka;
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
            $kab = $rowsc->kab_kota;
            $daerah = $rowsc->daerah;
            $thn = $rowsc->thn_ang;
        }

        $jk = $this->rka_model->combo_skpd();

        $cRet = '';


        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>$kab</strong></td>                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>LAPORAN OPERASIONAL </strong></td>
                    </tr>                    
                    <tr>
                         <td align=\"center\"><strong>UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN 31 DESEMBER $thn_ang</strong></td>
                    </tr>
                    <tr>
                         <td align=\"center\">&nbsp;</td>
                    </tr>
                  </table>";
        if ($cetak == '2')
        {
            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"0\">
                        <tr>
                            <td width=\"20%\">Urusan Organisasi </td>
                            <td width=\"80%\">: $kd_urusan - $nm_urusan</td>
                        </tr>
                        <tr>
                            <td>Organisasi</td>
                            <td>: $kd_skpd - $nm_skpd </td>
                        </tr>
                    
                    </table>";
        }

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>NO</b></td>                            
                            <td  bgcolor=\"#CCCCCC\" width=\"40%\" align=\"center\"><b>URAIAN</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Saldo $thn_ang</b></td>
                            <td bgcolor=\"#CCCCCC\" width=\"20%\" align=\"center\"><b>Saldo $thn_ang_1</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\" ><b>Kenaikan</br>(Penurunan)</b></td>
                            <td  bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" ><b>%</b></td>   
                        </tr>
                        
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                           
                         </tr>
                     </tfoot>
                   
                     <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"40%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"20%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>";


        $sql4 = "SELECT a.seq,a.nor,a.uraian as uraian ,ifnull(a.kode_1,\"''\") as kode_1,thn_m1 AS lalu FROM map_lo_kab a GROUP BY a.nor,a.uraian,a.kode_1 ORDER BY a.nor";


        $query4 = $this->db->query($sql4);
        //$query = $this->skpd_model->getAllc();
        $no = 0;
        foreach ($query4->result() as $row4)
        {
            //$kode=$row4->rek;
            $nama = $row4->uraian;

            $real_lalu = number_format($row4->lalu, "2", ".", ",");

            $n = $row4->kode_1;
            //echo $n;
            $sql5 = "SELECT SUM(b.real_spj) as nilai FROM $tabel b LEFT JOIN ms_rek5 c ON b.kd_rek5=c.kd_rek5 WHERE c.map_lra1 IN (" .
                $n . ") $skpd1";
            //echo $sql5;
            $query5 = $this->db->query($sql5);
            $trh = $query5->row();
            $nil = $trh->nilai;
            $nilai = number_format($trh->nilai, "2", ".", ",");
            //$nilai=0;
            //$real_s = $trh->nilai - $row4->lalu ;
            $real_s = $trh->nilai - $nil;
            if ($real_s < 0)
            {
                $x1 = "(";
                $real_s = $real_s * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih = number_format($real_s, "2", ".", ",");
            if ($trh->nilai == 0)
            {
                $tmp = 1;
            } else
            {
                $tmp = $trh->nilai;
            }
            $per1 = ($real_s != 0) ? ($real_s / $tmp) * 100 : 0;
            $persen1 = number_format($per1);
            $no = $no + 1;
            switch ($row4->seq)
            {
                case 5:
                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlpendapatan1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
                                 </tr>";
                    break;
                //case 165:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$no</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlpendapatan1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlpendapatan1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//                case 175:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$no</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//                case 325:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$no</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//                case 335:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$no</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$x$surplus1$y</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$x$surplus1$y</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//                case 490:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$no</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$a$biaya_net$b</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$a$biaya_net$b</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//                case 495:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$no</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$c$silpa1$d</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$c$silpa1$d</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//                case 225:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$no</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlbmbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlbmbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//                case 260:
//                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$no</td>                                     
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlbmbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$jmlbmbelanja1</td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\"></td>
//                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\"></td>
//                                 </tr>";
//                    break;
//
                default:
                    $cRet .= "<tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"10%\" align=\"left\">$no</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"40%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\">$nilai</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"right\">$x1$selisih$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"5%\" align=\"right\">$persen1</td>
                                 </tr>";

            }


        }


        // $cRet         .= "</table>";
        //        $data['prev']  = $cRet;
        //        $data['sikap'] = 'preview';
        //
        //
        //
        //        $this->template->set('title', 'LAPORAN OPERASIONAL');
        //        $this->tukd_model->_mpdf('',$cRet,10,10,10,'0');
        //$this->template->load('template','anggaran/rka/perkadaII',$data);

        $cRet .= " </table>";
        $data['prev'] = $cRet;
        $data['sikap'] = 'preview';
        $judul = 'LAPORAN REALISASI ANGGARAN LAPORAN OPRASIONAL';
        $this->template->set('title',
            'CETAK LAPORAN REALISASI ANGGARAN LAPORAN OPRASIONAL');
        switch ($pilih)
        {
            case 1;
                $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }


    }

    function lampiran_II()
    {
        $data['page_title'] = 'REALISASI';
        $this->template->set('title', 'REALISASI');
        $this->template->load('template', 'akuntansi/perda_real_ii', $data);
    }

    function cetak_perdaII_real($cetak = 0)
    {
        //$cetak =   $this->uri->segment(3);
        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc)
        {
            $tgl = $rowsc->tgl_rka;
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
            $kab = $rowsc->kab_kota;
            $daerah = $rowsc->daerah;
            $thn = $rowsc->thn_ang;
        }

        $cRet = '';
        if ($cetak <> 0)
        {
            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td rowspan=\"3\" align=\"left\" width=\"60%\"><strong></strong></td>
                         <td rowspan=\"3\" valign =\"top\" align=\"left\" width=\"10%\"><strong><h5>LAMPIRAN II  :</h6></strong></td>
                         <td colspan=\"2\" align=\"left\" width=\"30%\"><strong><h5>PERATURAN DAERAH</strong></h5></td>
                    </tr>
                    <tr> 
                         <td align=\"left\" width=\"10%\"><h5>NOMOR</h5></td><td width=\"20%\" align=\"left\" ><h5>:</h5></td>
                    </tr>
                    <tr>
                        <td style=\"border-bottom: solid 1px black;\" align=\"left\" width=\"10%\"><h5>TANGGAL</h5></td><td style=\"border-bottom: solid 1px black;\" align=\"left\" width=\"20%\"><h5>:</h5></td>
                    </tr>
                                         
                   
                  </table>";
        }
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\" width=\"100%\"><strong>$kab</strong></td>
                         
                    </tr>
                    <tr>
                         <td align=\"center\" width=\"100%\"><strong>REALISASI APBD </strong></td>
                    </tr>
                    
                    <tr>
                         <td align=\"center\" width=\"100%\"><strong>TAHUN ANGARAN $thn</strong></td>
                    </tr>

                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td rowspan=\"3\" bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\"><b>KODE</b></td>                            
                            <td rowspan=\"3\" bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>URUSAN PEMERINTAH DAERAH</b></td>
                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" bgcolor=\"#CCCCCC\" width=\"26%\" align=\"center\" colspan=\"4\"><b>PENDAPATAN</b></td>
                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" bgcolor=\"#CCCCCC\" width=\"54%\" align=\"center\" colspan=\"8\"><b>BELANJA</b></td>
                        </tr>
                        <tr>
 		                    <td rowspan=\"2\" width=\"7%\" bgcolor=\"#CCCCCC\" align=\"center\">SETELAH<br>PERUBAHAN</td>
                            <td rowspan=\"2\" width=\"7%\" bgcolor=\"#CCCCCC\" align=\"center\">REALISASI</td>
                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" colspan=\"2\" width=\"12%\" bgcolor=\"#CCCCCC\" align=\"center\">LEBIH(KURANG)</td>
                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\"colspan=\"3\" width=\"21%\" bgcolor=\"#CCCCCC\" align=\"center\">SETELAH PERUBAHAN</td>
                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\"colspan=\"3\" width=\"21%\" bgcolor=\"#CCCCCC\" align=\"center\">REALISASI</td>
                            <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\"colspan=\"2\" width=\"12%\" bgcolor=\"#CCCCCC\" align=\"center\">LEBIH</br>(KURANG)</td>
                        </tr>
                        <tr>
                             <td bgcolor=\"#CCCCCC\" width=\"7%\" align=\"center\" >(RP.)</td>
                             <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" >%</td>
                             <td bgcolor=\"#CCCCCC\" width=\"7%\" align=\"center\" >Tidak Langsung</td>
                             <td bgcolor=\"#CCCCCC\" width=\"7%\" align=\"center\" >Langsung</td>
                             <td bgcolor=\"#CCCCCC\" width=\"7%\" align=\"center\" >Jumlah</td>
                             <td bgcolor=\"#CCCCCC\" width=\"7%\" align=\"center\" >Tidak Langsung</td>
                             <td bgcolor=\"#CCCCCC\" width=\"7%\" align=\"center\" >Langsung</td>
                             <td bgcolor=\"#CCCCCC\" width=\"7%\" align=\"center\" >Jumlah</td>
                             <td bgcolor=\"#CCCCCC\" width=\"7%\" align=\"center\" >(RP.)</td>
                             <td bgcolor=\"#CCCCCC\" width=\"5%\" align=\"center\" >%</td>
                        </tr>    
                     </thead>
                    
                     
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\" >&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>
                        ";
        $sql1 = "SELECT * FROM (SELECT a.kd_urusan1 AS kode, a.nm_urusan1 AS nama,a.kd_urusan1 AS urut,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,1)='4' AND LEFT(kd_skpd,1)=a.kd_urusan1) AS nilai_4,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd WHERE LEFT(kd_rek5,1)='4' AND LEFT(kd_skpd,1)=a.kd_urusan1) AS cp_4,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,1)='4' AND LEFT(d.kd_skpd,1)=a.kd_urusan1) AS debet_4,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,1)='4' AND LEFT(d.kd_skpd,1)=a.kd_urusan1) AS kredit_4,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,2)='51' AND LEFT(kd_skpd,1)=a.kd_urusan1) AS btl,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,2)='52' AND LEFT(kd_skpd,1)=a.kd_urusan1) AS bl,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,1)='5' AND LEFT(kd_skpd,1)=a.kd_urusan1) AS jumlah,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,2)='51' AND LEFT(d.kd_skpd,1)=a.kd_urusan1) AS debet_btl,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,2)='51' AND LEFT(d.kd_skpd,1)=a.kd_urusan1) AS kredit_btl,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd WHERE LEFT(kd_rek5,2)='51' AND LEFT(kd_skpd,1)=a.kd_urusan1) AS cp_btl,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,2)='52' AND LEFT(d.kd_skpd,1)=a.kd_urusan1) AS debet_bl,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,2)='52' AND LEFT(d.kd_skpd,1)=a.kd_urusan1) AS kredit_bl,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd WHERE LEFT(kd_rek5,2)='52' AND LEFT(kd_skpd,1)=a.kd_urusan1) AS cp_bl FROM trdrka b
                        RIGHT JOIN ms_urusan1 a ON LEFT(b.kd_skpd,1)=a.kd_urusan1 GROUP BY  a.kd_urusan1 ,a.nm_urusan1
                        UNION
                        SELECT a.kd_urusan AS kode, a.nm_urusan AS nama,a.kd_urusan AS urut,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,1)='4' AND LEFT(kd_skpd,4)=a.kd_urusan) AS nilai_4,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd WHERE LEFT(kd_rek5,1)='4' AND LEFT(kd_skpd,4)=a.kd_urusan) AS cp_4,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,1)='4' AND LEFT(d.kd_skpd,4)=a.kd_urusan) AS debet_4,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,1)='4' AND LEFT(d.kd_skpd,4)=a.kd_urusan) AS kredit_4,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,2)='51' AND LEFT(kd_skpd,4)=a.kd_urusan) AS btl,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,2)='52' AND LEFT(kd_skpd,4)=a.kd_urusan) AS bl,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,1)='5' AND LEFT(kd_skpd,4)=a.kd_urusan) AS jumlah,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,2)='51' AND LEFT(d.kd_skpd,4)=a.kd_urusan) AS debet_btl,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,2)='51' AND LEFT(d.kd_skpd,4)=a.kd_urusan) AS kredit_btl,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd WHERE LEFT(kd_rek5,2)='51' AND LEFT(kd_skpd,4)=a.kd_urusan) AS cp_btl,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,2)='52' AND LEFT(d.kd_skpd,4)=a.kd_urusan) AS debet_bl,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,2)='52' AND LEFT(d.kd_skpd,4)=a.kd_urusan) AS kredit_bl,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd WHERE LEFT(kd_rek5,2)='52' AND LEFT(kd_skpd,4)=a.kd_urusan) AS cp_bl FROM trdrka b
                        RIGHT JOIN ms_urusan a ON LEFT(b.kd_skpd,4)=a.kd_urusan WHERE LENGTH(kd_urusan) <> '1' GROUP BY  a.kd_urusan ,a.nm_urusan
                        UNION
                        SELECT a.kd_skpd AS kode, a.nm_skpd AS nama,a.kd_skpd AS urut,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,1)='4' AND kd_skpd=a.kd_skpd) AS nilai_4,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd WHERE LEFT(kd_rek5,1)='4' AND kd_skpd=a.kd_skpd) AS cp_4,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,1)='4' AND d.kd_skpd=a.kd_skpd) AS debet_4,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,1)='4' AND d.kd_skpd=a.kd_skpd) AS kredit_4,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,2)='51' AND kd_skpd=a.kd_skpd) AS btl,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,2)='52' AND kd_skpd=a.kd_skpd) AS bl,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,1)='5' AND  kd_skpd=a.kd_skpd) AS jumlah,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,2)='51' AND d.kd_skpd=a.kd_skpd) AS debet_btl,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,2)='51' AND d.kd_skpd=a.kd_skpd) AS kredit_btl,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd WHERE LEFT(kd_rek5,2)='51' AND kd_skpd=a.kd_skpd) AS cp_btl,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,2)='52' AND d.kd_skpd=a.kd_skpd) AS debet_bl,
                        (SELECT SUM(kredit) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher=d.no_voucher WHERE LEFT(c.kd_rek5,2)='52' AND d.kd_skpd=a.kd_skpd) AS kredit_bl,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd WHERE LEFT(kd_rek5,2)='52' AND kd_skpd=a.kd_skpd) AS cp_bl FROM trdrka b
                        RIGHT JOIN ms_skpd a ON a.kd_skpd=b.kd_skpd GROUP BY a.kd_skpd,a.nm_skpd
                        UNION
                        SELECT '' AS kode, 'JUMLAH' AS nama,'JUMLAH' AS urut,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,1)='4') AS nilai_4,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd WHERE LEFT(kd_rek5,1)='4') AS cp_4,
                        (SELECT SUM(debet) FROM trdju_pkd  WHERE LEFT(kd_rek5,1)='4' ) AS debet_4,
                        (SELECT SUM(kredit) FROM trdju_pkd WHERE LEFT(kd_rek5,1)='4' ) AS kredit_4,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,2)='51' ) AS btl,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,2)='52' ) AS bl,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_rek5,1)='5' ) AS jumlah,
                        (SELECT SUM(debet) FROM trdju_pkd WHERE LEFT(kd_rek5,2)='51' ) AS debet_btl,
                        (SELECT SUM(kredit) FROM trdju_pkd WHERE LEFT(kd_rek5,2)='51') AS kredit_btl,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd WHERE LEFT(kd_rek5,2)='51' ) AS cp_btl,
                        (SELECT SUM(debet) FROM trdju_pkd WHERE LEFT(kd_rek5,2)='52' ) AS debet_bl,
                        (SELECT SUM(kredit) FROM trdju_pkd WHERE LEFT(kd_rek5,2)='52' ) AS kredit_bl,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd WHERE LEFT(kd_rek5,2)='52' ) AS cp_bl FROM trdrka b
                        RIGHT JOIN ms_urusan1 a ON LEFT(b.kd_skpd,1)=a.kd_urusan1 GROUP BY  a.kd_urusan1 ,a.nm_urusan1) a ORDER BY a.urut
";

        $query = $this->db->query($sql1);
        foreach ($query->result() as $row)
        {
            $kode = $row->kode;
            $nama = $row->nama;
            $ang_4 = $row->nilai_4;
            $in = $row->cp_4;
            $indebet = $row->debet_4;
            $inkredit = $row->kredit_4;
            $real_4 = ($in + $inkredit) - $indebet;
            $pend = number_format($ang_4, "2", ",", ".");
            $pend_real = number_format($real_4, "2", ",", ".");
            $selisih_4 = $real_4 - $ang_4;
            if ($selisih_4 < 0)
            {
                $x = "(";
                $selisih_4 = $selisih_4 * -1;
                $y = ")";
            } else
            {
                $x = "";
                $y = "";
            }
            $sel_4 = number_format($selisih_4, "2", ",", ".");
            $per_4 = ($selisih_4 != 0) ? ($selisih_4 / $ang_4) * 100 : 0;
            $persen_4 = number_format($per_4);

            $ang_51 = $row->btl;
            $btlcp = $row->cp_btl;
            $debet_btl = $row->debet_btl;
            $kredit_btl = $row->kredit_btl;
            $real_btl = $debet_btl - ($btlcp + $kredit_btl);
            $ang_52 = $row->bl;
            $blcp = $row->cp_bl;
            $debet_bl = $row->debet_bl;
            $kredit_bl = $row->kredit_bl;
            $real_bl = $debet_bl - ($blcp + $kredit_bl);
            $jum_5 = $ang_51 + $ang_52;
            $jum_5_real = $real_btl + $real_bl;
            $btl = number_format($ang_51, "2", ",", ".");
            $bl = number_format($ang_52, "2", ",", ".");
            $jum = number_format($jum_5, "2", ",", ".");
            $btl_real = number_format($real_btl, "2", ",", ".");
            $bl_real = number_format($real_bl, "2", ",", ".");
            $jum_real = number_format($jum_5_real, "2", ",", ".");
            $selisih_5 = $jum_5_real - $jum_5;
            if ($selisih_5 < 0)
            {
                $x1 = "(";
                $selisih_5 = $selisih_5 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $sel_5 = number_format($selisih_5, "2", ",", ".");
            $per_5 = ($selisih_5 != 0) ? ($selisih_5 / $jum_5) * 100 : 0;
            $persen_5 = number_format($per_5);

            $cRet .= "<tr>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"left\" width=\"5%\">$kode</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"left\" width=\"15%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\" width=\"7%\">$pend</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\" width=\"7%\">$pend_real</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\" width=\"7%\">$x$sel_4$y</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\" width=\"5%\">$persen_4</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\" width=\"7%\">$btl</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\" width=\"7%\">$bl</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\" width=\"7%\">$jum</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\" width=\"7%\">$btl_real</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\" width=\"7%\">$bl_real</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\" width=\"7%\">$jum_real</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\" width=\"7%\">$x1$sel_5$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" align=\"right\" width=\"5%\">$persen_5</td>
                                </tr>
                                     ";


        }

        $cRet .= " </table>";
        $judul = 'Laporan Realisasi APBD  Lamp II';
        $data['prev'] = $cRet;
        $this->template->set('title', 'CETAK PERDA REALISASI LAMPIRAN II');
        switch ($cetak)
        {
            case 1;
                $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }

    function lampiran_III()
    {
        $data['page_title'] = 'REALISASI';
        $this->template->set('title', 'REALISASI');
        $this->template->load('template', 'akuntansi/perda_real_iii', $data);
    }

    function cetak_perdaIII_real($skpd = '', $cetak = '')
    {
        $cetak = $cetak;
        $id = $skpd;
        $sqldns = "SELECT a.kd_urusan as kd_u,b.nm_urusan as nm_u,a.kd_skpd as kd_sk,a.nm_skpd as nm_sk FROM ms_skpd a INNER JOIN ms_urusan b ON a.kd_urusan=b.kd_urusan WHERE kd_skpd='$id'";
        $sqlskpd = $this->db->query($sqldns);
        foreach ($sqlskpd->result() as $rowdns)
        {

            $kd_urusan = $rowdns->kd_u;
            $nm_urusan = $rowdns->nm_u;
            $kd_skpd = $rowdns->kd_sk;
            $nm_skpd = $rowdns->nm_sk;
        }

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc)
        {

            $tgl = $rowsc->tgl_rka;
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
            $kab = $rowsc->kab_kota;
            $daerah = $rowsc->daerah;
            $thn = $rowsc->thn_ang;
        }


        $cRet = '';
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>$kab</strong></td>
                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>RINGKASAN REALISASI APBD </strong></td>
                    </tr>
                    
                    <tr>
                         <td align=\"center\"><strong>TAHUN ANGARAN $thn</strong></td>
                    </tr>

                  </table>";
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"left\" border=\"0\">
                    <tr>
                        <td width=\"20%\">Urusan Organisasi </td>
                        <td width=\"80%\">:$kd_urusan - $nm_urusan</td>
                    </tr>
                    <tr>
                        <td>Organisasi</td>
                        <td>:$kd_skpd - $nm_skpd</td>
                    </tr>";

        $cRet .= "</table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"15%\" align=\"center\"><b>Kode Rekening</b></td>                            
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"26%\" align=\"center\"><b>Uraian</b></td>                            
                            <td colspan=\"2\" bgcolor=\"#CCCCCC\" width=\"28%\" align=\"center\"><b>Jumlah (Rp.)</b></td>
                            <td colspan=\"2\" bgcolor=\"#CCCCCC\" width=\"16%\" align=\"center\"><b>Bertambah/ (berkurang)</b></td>
                            <td  rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"13%\" align=\"center\"><b>Dasar Hukum</b></td>                            
                            </tr>
                        <tr>
 		                     <td width=\"13%\" bgcolor=\"#CCCCCC\" align=\"center\">Anggaran </td>
                             <td width=\"13%\" bgcolor=\"#CCCCCC\" align=\"center\">Realisasi</td>
                             <td width=\"13%\" bgcolor=\"#CCCCCC\" align=\"center\">Jumlah</td>
                             <td width=\"3%\" bgcolor=\"#CCCCCC\" align=\"center\">%</td>
                             
                        </tr>    
                     </thead>
                     <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>  
                            <td style=\"border-top: none;\"></td>
                                                                                   
                         </tr>
                     </tfoot>
                    
                     
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"26%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"3%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"14%\">&nbsp;</td>
                            </tr>
                        ";


        $sql_p = "SELECT * FROM (SELECT a.kd_kegiatan AS kd_rek,c.nm_kegiatan AS nm_rek,SUM(b.nilai)AS nilai,SUM(b.nilai_ubah)AS nilai_u,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd g INNER JOIN trhkasin_pkd h ON g.no_sts = h.no_sts WHERE h.kd_skpd='$id' AND h.kd_kegiatan = a.kd_kegiatan ) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd i  INNER JOIN trhju_pkd j ON i.no_voucher = j.no_voucher  WHERE j.kd_skpd='$id' AND kd_kegiatan = a.kd_kegiatan ) AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd k  INNER JOIN trhju_pkd l ON k.no_voucher = l.no_voucher WHERE l.kd_skpd='$id' AND kd_kegiatan = a.kd_kegiatan ) AS kredit,'2' AS nu FROM trskpd a INNER JOIN m_giat c ON a.kd_kegiatan1=c.kd_kegiatan
                        LEFT JOIN trdrka b ON a.kd_kegiatan=b.kd_kegiatan WHERE a.kd_skpd='$id' AND RIGHT(a.kd_kegiatan,5) ='00.01' GROUP BY a.kd_kegiatan,c.nm_kegiatan
                        UNION ALL 
                        SELECT SUBSTR(a.no_trdrka,12,24) AS kd_rek,b.nm_rek2 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE  g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE  h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'3' AS nu FROM trdrka a INNER JOIN ms_rek2 b 
                        ON LEFT(a.kd_rek5,2)=b.kd_rek2 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,1)='4' GROUP BY SUBSTR(a.no_trdrka,12,24)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                        ON LEFT(a.kd_rek5,3)=b.kd_rek3 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,1)='4' GROUP BY SUBSTR(a.no_trdrka,12,25)) a ORDER BY kd_rek,nu ";

        $pend = $this->db->query($sql_p);
        //$query = $this->skpd_model->getAllc();

        foreach ($pend->result() as $row)
        {
            $kode = $row->kd_rek;
            $nama = $row->nm_rek;
            $ang = number_format($row->nilai_u, "2", ".", ",");
            $angaran = $row->nilai_u;
            $pen = $row->cp;
            $debet = $row->debet;
            $kredit = $row->kredit;
            $real = ($pen + $kredit) - $debet;
            $nreal = number_format($real, "2", ".", ",");
            $real_s = $real - $angaran;
            if ($real_s < 0)
            {
                $x1 = "(";
                $real_s = $real_s * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisih = number_format($real_s, "2", ".", ",");
            $per1 = ($real_s != 0) ? ($real_s / $row->nilai_u) * 100 : 0;
            $persen1 = number_format($per1);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">$kode</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$pend_selisih$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen1</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"left\"></td>
                                     </tr>
                                     ";


        }

        $sqltp = "SELECT SUM(a.nilai) AS totp,SUM(a.nilai_ubah) AS totp_u, 
                        (SELECT SUM(rupiah) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,1)='4' AND a.kd_skpd='$id') AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,1)='4' AND d.kd_skpd='$id') AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,1)='4' AND f.kd_skpd='$id') AS kredit FROM trdrka a
                         WHERE LEFT(kd_rek5,1)='4' AND kd_skpd='$id'";


        $sqlp = $this->db->query($sqltp);
        foreach ($sqlp->result() as $rowp)
        {
            $angp = number_format($rowp->totp_u, "2", ".", ",");
            $angaranp = $rowp->totp_u;
            $penp = $rowp->cp;
            $debetp = $rowp->debet;
            $kreditp = $rowp->kredit;
            $realp = ($penp + $kreditp) - $debetp;
            $nrealp = number_format($realp, "2", ".", ",");
            $real_sp = $realp - $angaranp;
            if ($real_sp < 0)
            {
                $x1 = "(";
                $real_sp = $real_sp * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $pend_selisihp = number_format($real_sp, "2", ".", ",");
            $per1p = ($real_sp != 0) ? ($real_sp / $rowp->totp_u) * 100 : 0;
            $persen1p = number_format($per1p);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Jumlah Pendapatan Daerah</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$angp</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nrealp</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$pend_selisihp$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen1p</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";
        }


        $sqltb = "SELECT SUM(a.nilai) AS totb,SUM(a.nilai_ubah) AS totb_u, 
            (SELECT SUM(rupiah) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,1)='5' AND a.kd_skpd='$id' ) AS cp,
            (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,1)='5' AND d.kd_skpd='$id' ) AS debet,
            (SELECT SUM(kredit) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,1)='5' AND f.kd_skpd='$id' ) AS kredit FROM trdrka a
            WHERE LEFT(kd_rek5,1)='5' AND kd_skpd='$id'";

        $sqlb = $this->db->query($sqltb);
        foreach ($sqlb->result() as $rowb_5)
        {
            $ang_5 = number_format($rowb_5->totb_u, "2", ".", ",");
            $angaran_5 = $rowb_5->totb_u;
            $in_5 = $rowb_5->cp;
            $debet_5 = $rowb_5->debet;
            $kredit_5 = $rowb_5->kredit;
            $real_5 = $debet_5 - ($in_5 + $kredit_5);
            $nreal_5 = number_format($real_5, "2", ".", ",");
            $real_s_5 = $real_5 - $angaran_5;
            if ($real_s_5 < 0)
            {
                $x1 = "(";
                $real_s_5 = $real_s_5 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih_5 = number_format($real_s_5, "2", ".", ",");
            $per_5 = ($real_s_5 != 0) ? ($real_s_5 / $rowb_5->totb_u) * 100 : 0;
            $persen_5 = number_format($per_5);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Belanja Daerah</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_5$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_5</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";


        }

        $sqltbtl = "SELECT SUM(a.nilai) AS totb,SUM(a.nilai_ubah) AS totb_u, 
                            (SELECT SUM(rupiah) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,2)='51' AND a.kd_skpd='$id' ) AS cp,
                            (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,2)='51' AND d.kd_skpd='$id' ) AS debet,
                            (SELECT SUM(kredit) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,2)='51' AND f.kd_skpd='$id' ) AS kredit FROM trdrka a
                            WHERE LEFT(kd_rek5,2)='51' AND kd_skpd='$id'";


        $sqlbtl = $this->db->query($sqltbtl);
        foreach ($sqlbtl->result() as $rowbtl)
        {
            $ang_51 = number_format($rowbtl->totb_u, "2", ".", ",");
            $angaran_51 = $rowbtl->totb_u;
            $in_51 = $rowbtl->cp;
            $debet_51 = $rowbtl->debet;
            $kredit_51 = $rowbtl->kredit;
            $real_51 = $debet_51 - ($in_51 + $kredit_51);
            $nreal_51 = number_format($real_51, "2", ".", ",");
            $real_s_51 = $real_51 - $angaran_51;
            if ($real_s_51 < 0)
            {
                $x1 = "(";
                $real_s_51 = $real_s_51 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih_51 = number_format($real_s_51, "2", ".", ",");
            $per_51 = ($real_s_51 != 0) ? ($real_s_51 / $rowbtl->totb_u) * 100 : 0;
            $persen_51 = number_format($per_51);


        }


        $sql_btl = "SELECT * FROM (SELECT a.kd_kegiatan AS kd_rek,c.nm_kegiatan AS nm_rek,SUM(b.nilai)AS nilai,SUM(b.nilai_ubah)AS nilai_u,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd g INNER JOIN trhkasin_pkd h ON g.no_sts = h.no_sts WHERE h.kd_skpd='$id' AND h.kd_kegiatan = a.kd_kegiatan ) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd i  INNER JOIN trhju_pkd j ON i.no_voucher = j.no_voucher  WHERE j.kd_skpd='$id' AND kd_kegiatan = a.kd_kegiatan ) AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd k  INNER JOIN trhju_pkd l ON k.no_voucher = l.no_voucher WHERE l.kd_skpd='$id' AND kd_kegiatan = a.kd_kegiatan ) AS kredit,'2' AS nu FROM trskpd a INNER JOIN m_giat c ON a.kd_kegiatan1=c.kd_kegiatan
                        LEFT JOIN trdrka b ON a.kd_kegiatan=b.kd_kegiatan WHERE a.kd_skpd='$id' AND RIGHT(a.kd_kegiatan,5) ='00.02' GROUP BY a.kd_kegiatan,c.nm_kegiatan
                        UNION ALL 
                        SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                        ON LEFT(a.kd_rek5,3)=b.kd_rek3 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='51' GROUP BY SUBSTR(a.no_trdrka,12,25)) a ORDER BY kd_rek,nu";

        $btl = $this->db->query($sql_btl);

        foreach ($btl->result() as $row_btl)
        {
            $kode = $row_btl->kd_rek;
            $nama = $row_btl->nm_rek;
            $ang_511 = number_format($row_btl->nilai_u, "2", ".", ",");
            $angaran_511 = $row_btl->nilai_u;
            $in_511 = $row_btl->cp;
            $debet_511 = $row_btl->debet;
            $kredit_511 = $row_btl->kredit;
            $real_511 = $debet_511 - ($in_511 + $kredit_511);
            $nreal_511 = number_format($real_511, "2", ".", ",");
            $real_s_511 = $real_511 - $angaran_511;
            if ($real_s_511 < 0)
            {
                $x1 = "(";
                $real_s_511 = $real_s_511 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih_511 = number_format($real_s_511, "2", ".", ",");
            $per_511 = ($real_s_511 != 0) ? ($real_s_511 / $row_btl->nilai_u) * 100 : 0;
            $persen_511 = number_format($per_511);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">$kode</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_511</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_511</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_511$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_511</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"left\"></td>
                                     </tr>
                                     ";


        }
        $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";


        $sqltbl = "SELECT SUM(a.nilai) AS totbl,SUM(a.nilai_ubah) AS totbl_u, 
                        (SELECT SUM(rupiah) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,2)='52' AND a.kd_skpd='$id') AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,2)='52' AND d.kd_skpd='$id') AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,2)='52' AND f.kd_skpd='$id') AS kredit FROM trdrka a
                        WHERE LEFT(kd_rek5,2)='52' AND kd_skpd='$id'";

        $sqlbl = $this->db->query($sqltbl);
        foreach ($sqlbl->result() as $rowbl)
        {
            $ang_52 = number_format($rowbl->totbl_u, "2", ".", ",");
            $angaran_52 = $rowbl->totbl_u;
            $in_52 = $rowbl->cp;
            $debet_52 = $rowbl->debet;
            $kredit_52 = $rowbl->kredit;
            $real_52 = $debet_52 - ($in_52 + $kredit_52);
            $nreal_52 = number_format($real_52, "2", ".", ",");
            $real_s_52 = $real_52 - $angaran_52;
            if ($real_s_52 < 0)
            {
                $x1 = "(";
                $real_s_52 = $real_s_52 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih_52 = number_format($real_s_51, "2", ".", ",");
            $per_52 = ($real_s_5 != 0) ? ($real_s_51 / $rowbl->totbl_u) * 100 : 0;
            $persen_52 = number_format($per_52);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Belanja Langsung</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_52</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_52</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_52$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_52</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";
        }
        $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";


        $sql_bl = "SELECT * FROM (SELECT a.kd_program AS kd_rek, c.nm_program AS nm_rek,(SELECT SUM(nilai) FROM trdrka WHERE LEFT(kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program) AS nilai,
                        (SELECT SUM(nilai_ubah) FROM trdrka WHERE LEFT(kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program) AS nilai_u,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd g INNER JOIN trhkasin_pkd h ON g.no_sts = h.no_sts WHERE h.kd_skpd='$id' AND LEFT(h.kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program ) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd i  INNER JOIN trhju_pkd j ON i.no_voucher = j.no_voucher  WHERE j.kd_skpd='$id' AND LEFT(i.kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program ) AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd k  INNER JOIN trhju_pkd l ON k.no_voucher = l.no_voucher WHERE l.kd_skpd='$id' AND LEFT(k.kd_kegiatan,(LENGTH(a.kd_program))) = a.kd_program ) AS kredit,'1' AS nu FROM trskpd a INNER JOIN m_prog c ON a.kd_program1=c.kd_program
                        LEFT JOIN trdrka b ON a.kd_program=LEFT(b.kd_kegiatan,(LENGTH(a.kd_program))) WHERE a.kd_skpd='$id' AND RIGHT(a.kd_program,2) <>'00' GROUP BY a.kd_program,c.nm_program 
                        UNION ALL
                        SELECT a.kd_kegiatan AS kd_rek,c.nm_kegiatan AS nm_rek,SUM(b.nilai)AS nilai,SUM(b.nilai_ubah)AS nilai_u,
                        (SELECT SUM(rupiah) FROM trdkasin_pkd g INNER JOIN trhkasin_pkd h ON g.no_sts = h.no_sts WHERE h.kd_skpd='$id' AND h.kd_kegiatan = a.kd_kegiatan ) AS cp,
                        (SELECT SUM(debet) FROM trdju_pkd i  INNER JOIN trhju_pkd j ON i.no_voucher = j.no_voucher  WHERE j.kd_skpd='$id' AND kd_kegiatan = a.kd_kegiatan ) AS debet,
                        (SELECT SUM(kredit) FROM trdju_pkd k  INNER JOIN trhju_pkd l ON k.no_voucher = l.no_voucher WHERE l.kd_skpd='$id' AND kd_kegiatan = a.kd_kegiatan ) AS kredit,'2' AS nu FROM trskpd a INNER JOIN m_giat c ON a.kd_kegiatan1=c.kd_kegiatan
                        LEFT JOIN trdrka b ON a.kd_kegiatan=b.kd_kegiatan WHERE a.kd_skpd='$id' AND RIGHT(a.kd_program,2) <>'00' GROUP BY a.kd_kegiatan,c.nm_kegiatan
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,24) AS kd_rek,b.nm_rek2 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'3' AS nu FROM trdrka a INNER JOIN ms_rek2 b 
                        ON LEFT(a.kd_rek5,2)=b.kd_rek2   
                        WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='52' GROUP BY SUBSTR(a.no_trdrka,12,24)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE d.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                        ON LEFT(a.kd_rek5,3)=b.kd_rek3                   
                        WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='52' GROUP BY SUBSTR(a.no_trdrka,12,25)) a ORDER BY kd_rek,nu";


        $bl = $this->db->query($sql_bl);
        foreach ($bl->result() as $row_bl)
        {
            $kode = $row_bl->kd_rek;
            $nama = $row_bl->nm_rek;
            $ang_522 = number_format($row_bl->nilai_u, "2", ".", ",");
            $angaran_522 = $row_bl->nilai_u;
            $in_522 = $row_bl->cp;
            $debet_522 = $row_bl->debet;
            $kredit_522 = $row_bl->kredit;
            $real_522 = $debet_522 - ($in_522 + $kredit_522);
            $nreal_522 = number_format($real_522, "2", ".", ",");
            $real_s_522 = $real_522 - $angaran_522;
            if ($real_s_522 < 0)
            {
                $x1 = "(";
                $real_s_522 = $real_s_522 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih_522 = number_format($real_s_522, "2", ".", ",");
            $per_522 = ($real_s_522 != 0) ? ($real_s_522 / $row_bl->nilai_u) * 100 : 0;
            $persen_522 = number_format($per_522);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">$kode</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_522</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_522</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_522$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_522</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"left\"></td>
                                     </tr>
                                     ";


        }
        $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";


        $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Belanja Daerah</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_5</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_5$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_5</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";
        $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";


        $sqltpm = "SELECT SUM(IFNULL(a.nilai,0)) AS totbl,SUM(a.nilai_ubah) AS totbl_u, 
                        (SELECT SUM(IFNULL(rupiah,0)) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,2)='61' AND a.kd_skpd='$id' ) AS cp,
                        (SELECT SUM(IFNULL(debet,0)) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,2)='61' AND d.kd_skpd='$id' ) AS debet,
                        (SELECT SUM(IFNULL(kredit,0)) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,2)='61' AND f.kd_skpd='$id' ) AS kredit FROM trdrka a
                        WHERE LEFT(kd_rek5,2)='61' AND kd_skpd='$id'";

        $sqlpm = $this->db->query($sqltpm);
        foreach ($sqlpm->result() as $rowpm)
        {
            $ang_61 = number_format($rowpm->totbl_u, "2", ".", ",");
            $angaran_61 = $rowpm->totbl_u;
            $in_61 = $rowpm->cp;
            $debet_61 = $rowpm->debet;
            $kredit_61 = $rowpm->kredit;
            $real_61 = $debet_61 - ($in_61 + $kredit_61);
            $nreal_61 = number_format($real_61, "2", ".", ",");
            $real_s_61 = $real_61 - $angaran_61;
            if ($real_s_61 < 0)
            {
                $x1 = "(";
                $real_s_61 = $real_s_61 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih_61 = number_format($real_s_61, "2", ".", ",");
            $per_61 = ($real_s_61 != 0) ? ($real_s_61 / $rowpm->totbl_u) * 100 : 0;
            $persen_61 = number_format($per_61);
            //
            //                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>
            //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Pembiayaan Masuk</td>
            //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_61</td>
            //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_61</td>
            //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_61$y1</td>
            //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_61</td>
            //                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
            //                                     </tr>";
        }


        $sqlpm = "SELECT * FROM (SELECT SUBSTR(a.no_trdrka,12,24) AS kd_rek,b.nm_rek2 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek2 b 
                        ON LEFT(a.kd_rek5,2)=b.kd_rek2 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='61' GROUP BY SUBSTR(a.no_trdrka,12,24)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                        ON LEFT(a.kd_rek5,3)=b.kd_rek3 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='61' GROUP BY SUBSTR(a.no_trdrka,12,25) ) a ORDER BY kd_rek,nu";


        $sql611 = $this->db->query($sqlpm);
        foreach ($sql611->result() as $row_61)
        {
            $kd = $row_61->kd_rek;
            $nm = $row_61->nm_rek;
            $ang_611 = number_format($row_61->nilai_u, "2", ".", ",");
            $angaran_611 = $row_61->nilai_u;
            $in_611 = $row_61->cp;
            $debet_611 = $row_61->debet;
            $kredit_611 = $row_61->kredit;
            $real_611 = $debet_611 - ($in_611 + $kredit_611);
            $nreal_611 = number_format($real_611, "2", ".", ",");
            $real_s_611 = $real_611 - $angaran_611;
            if ($real_s_611 < 0)
            {
                $x1 = "(";
                $real_s_611 = $real_s_611 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih_611 = number_format($real_s_611, "2", ".", ",");
            $per_611 = ($real_s_611 != 0) ? ($real_s_611 / $row_61->nilai_u) * 100 : 0;
            $persen_611 = number_format($per_611);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"15%\" align=\"left\">$kd</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"26%\">$nm</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"13%\" align=\"right\">$ang_611</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"13%\" align=\"right\">$nreal_611</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"13%\" align=\"right\">$x1$selisih_611$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"3%\" align=\"right\">$persen_611</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";
        }
        $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";

        $sqltpk = "SELECT SUM(IFNULL(a.nilai,0)) AS totbl,SUM(a.nilai_ubah) AS totbl_u, 
                        (SELECT SUM(IFNULL(rupiah,0)) FROM trdkasin_pkd a INNER JOIN trhkasin_pkd b ON a.no_sts=b.no_sts WHERE LEFT(a.kd_rek5,2)='62' AND a.kd_skpd='$id' ) AS cp,
                        (SELECT SUM(IFNULL(debet,0)) FROM trdju_pkd c INNER JOIN trhju_pkd d ON c.no_voucher = d.no_voucher WHERE LEFT(c.kd_rek5,2)='62' AND d.kd_skpd='$id' ) AS debet,
                        (SELECT SUM(IFNULL(kredit,0)) FROM trdju_pkd e INNER JOIN trhju_pkd f ON e.no_voucher = f.no_voucher WHERE LEFT(e.kd_rek5,2)='62' AND f.kd_skpd='$id' ) AS kredit FROM trdrka a
                        WHERE LEFT(kd_rek5,2)='62' AND kd_skpd='$id' ";

        $sqlpk = $this->db->query($sqltpk);
        foreach ($sqlpk->result() as $rowpk)
        {
            $ang_62 = number_format($rowpk->totbl_u, "2", ".", ",");
            $angaran_62 = $rowpk->totbl_u;
            //echo($angaran_62);
            $in_62 = $rowpk->cp;
            $debet_62 = $rowpk->debet;
            $kredit_62 = $rowpk->kredit;
            $real_62 = $debet_62 - ($in_62 + $kredit_62);
            $nreal_62 = number_format($real_62, "2", ".", ",");
            $real_s_62 = $real_62 - $angaran_62;
            if ($real_s_62 < 0)
            {
                $x1 = "(";
                $real_s_62 = $real_s_62 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih_62 = number_format($real_s_62, "2", ".", ",");
            $per_62 = ($real_s_62 != 0) ? ($real_s_62 / $rowpk->totbl_u) * 100 : 0;
            $persen_62 = number_format($per_62);

            //                    $cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>
            //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Pembiayaan Keluar</td>
            //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$ang_62</td>
            //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$nreal_62</td>
            //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$selisih_62$y1</td>
            //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persen_62</td>
            //                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
            //                                     </tr>";
        }

        $sqlpk = "SELECT * FROM (SELECT SUBSTR(a.no_trdrka,12,24) AS kd_rek,b.nm_rek2 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek2 b 
                        ON LEFT(a.kd_rek5,2)=b.kd_rek2 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='62' GROUP BY SUBSTR(a.no_trdrka,12,24)
                        UNION ALL
                        SELECT SUBSTR(a.no_trdrka,12,25) AS kd_rek,b.nm_rek3 AS nm_rek,SUM(a.nilai)AS nilai,SUM(a.nilai_ubah)AS nilai_u,
                        (SELECT SUM(c.rupiah) FROM trdkasin_pkd c INNER JOIN trhkasin_pkd d ON c.no_sts = d.no_sts WHERE c.kd_skpd='$id' AND LEFT(c.kd_rek5,5)= LEFT(a.kd_rek5,5) AND d.kd_kegiatan = a.kd_kegiatan )AS cp,
                        (SELECT SUM(e.debet) FROM trdju_pkd e  INNER JOIN trhju_pkd g ON e.no_voucher = g.no_voucher WHERE g.kd_skpd='$id' AND LEFT(e.kd_rek5,3)= LEFT(a.kd_rek5,3) AND e.kd_kegiatan = a.kd_kegiatan )AS debet,
                        (SELECT SUM(f.kredit) FROM trdju_pkd f INNER JOIN trhju_pkd h ON f.no_voucher = h.no_voucher WHERE h.kd_skpd='$id' AND LEFT(f.kd_rek5,3)= LEFT(a.kd_rek5,3) AND f.kd_kegiatan = a.kd_kegiatan )AS kredit,'4' AS nu FROM trdrka a INNER JOIN ms_rek3 b 
                        ON LEFT(a.kd_rek5,3)=b.kd_rek3 WHERE a.kd_skpd='$id' AND LEFT(a.kd_rek5,2)='62' GROUP BY SUBSTR(a.no_trdrka,12,25) ) a ORDER BY kd_rek,nu";

        $sql62 = $this->db->query($sqlpk);
        foreach ($sql62->result() as $row_62)
        {
            $kd = $row_62->kd_rek;
            $nm = $row_62->nm_rek;
            $ang_622 = number_format($row_62->nilai_u, "2", ".", ",");
            $angaran_622 = $row_62->nilai_u;
            $in_622 = $row_62->cp;
            $debet_622 = $row_62->debet;
            $kredit_622 = $row_62->kredit;
            $real_622 = $debet_622 - ($in_622 + $kredit_622);
            $nreal_622 = number_format($real_622, "2", ".", ",");
            $real_s_622 = $real_622 - $angaran_622;
            if ($real_s_622 < 0)
            {
                $x1 = "(";
                $real_s_622 = $real_s_622 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $selisih_622 = number_format($real_s_622, "2", ".", ",");
            $per_622 = ($real_s_611 != 0) ? ($real_s_622 / $row_62->nilai_u) * 100 : 0;
            $persen_622 = number_format($per_622);

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"15%\" align=\"left\">$kd</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"26%\">$nm</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"13%\" align=\"right\">$ang_622</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"13%\" align=\"right\">$nreal_622</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"13%\" align=\"right\">$x1$selisih_622$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"3%\" align=\"right\">$persen_622</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";
        }
        $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\">&nbsp;</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"26%\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\"></td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";

        $netto = $angaran_61 - $angaran_62;
        $net = number_format($netto, "2", ",", ".");
        $netto_r = $real_61 - $real_62;
        $net_r = number_format($netto_r, "2", ",", ".");
        $netto_s = $netto_r - $netto;
        if ($netto_s < 0)
        {
            $x1 = "(";
            $netto_s = $netto_s * -1;
            $y1 = ")";
        } else
        {
            $x1 = "";
            $y1 = "";
        }
        $netto_selisih = number_format($netto_s, "2", ",", ".");
        $perpnet = ($netto_s != 0) ? ($netto_s / $netto) * 100 : 0;
        $persennet = number_format($perpnet);


        $cRet .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>
                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">Pembiayaan Netto</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$net</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$net_r</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x1$netto_selisih$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persennet</td>
                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
                                     </tr>";

        $silpa = ($angaranp + $angaran_61) - ($angaran_5 + $angaran_62);
        if ($silpa < 0)
        {
            $a = "(";
            $silpa = $silpa * -1;
            $b = ")";
        } else
        {
            $a = "";
            $b = "";
        }
        $silp = number_format($silpa, "2", ",", ".");
        $silpa_real = ($realp + $real_61) - ($real_5 + $real_62);
        if ($silpa_real < 0)
        {
            $c = "(";
            $silpa_real = $silpa_real * -1;
            $d = ")";
        } else
        {
            $c = "";
            $d = "";
        }
        $silp_real = number_format($silpa_real, "2", ",", ".");
        $silpa_s = $silpa_real - $silpa;
        if ($silpa_s < 0)
        {
            $x = "(";
            $silpa_s = $silpa_s * -1;
            $y = ")";
        } else
        {
            $x = "";
            $y = "";
        }
        $silpa_selisih = number_format($silpa_s, "2", ",", ".");
        $persil = ($silpa_s != 0) ? ($silpa_s / $silpa) * 100 : 0;
        $persensilpa = number_format($persil);

        //$cRet    .= " <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"15%\" align=\"left\"></td>
        //                                    <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"16%\">SILPA</td>
        //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$a$silp$b</td>
        //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$c$silp_real$d</td>
        //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"13%\" align=\"right\">$x$silpa_selisih$y</td>
        //                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"3%\" align=\"right\">$persensilpa</td>
        //                                     <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"13%\" align=\"right\"></td>
        //                                     </tr>";
        $cRet .= " </table>";
        $data['prev'] = $cRet;
        $this->template->set('title', 'CETAK REALISASI LAMPIRAN III');


        switch ($cetak)
        {
            case 1;
                $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }

    }

    function lampiran_IV()
    {
        $data['page_title'] = 'REALISASI';
        $this->template->set('title', 'REALISASI');
        $this->template->load('template', 'akuntansi/perda_real_iv', $data);
    }
    function cetak_lra2()
    {
        $data['page_title'] = 'REALISASI';
        $this->template->set('title', 'REALISASI');
        $this->template->load('template', 'akuntansi/cetak_lra', $data);
    }

    function cetak_lra_ppkd()
    {
        $data['page_title'] = 'REALISASI';
        $this->template->set('title', 'REALISASI');
        $this->template->load('template', 'akuntansi/cetak_lra_ppkd', $data);
    }

    function cetak_lra_lo_v()
    {
        $data['page_title'] = 'REALISASI';
        $this->template->set('title', 'REALISASI');
        $this->template->load('template', 'akuntansi/cetak_lra_lo', $data);
    }

    function cetak_lra_lo_ppkd()
    {
        $data['page_title'] = 'REALISASI';
        $this->template->set('title', 'REALISASI');
        $this->template->load('template', 'akuntansi/cetak_lra_lo_ppkd', $data);
    }
    
    function cetak_lra_lo_pemda()
    {
        $data['page_title'] = 'REALISASI';
        $this->template->set('title', 'REALISASI');
        $this->template->load('template', 'akuntansi/cetak_lra_lo_pemda', $data);
    }
    
    function cetak_lpe()
    {
        $data['page_title'] = 'LPE';
        $this->template->set('title', 'LPE');
        $this->template->load('template', 'akuntansi/cetak_lpe', $data);
    }
    
    function cetak_lpsal()
    {
        $data['page_title'] = 'LPSAL';
        $this->template->set('title', 'LPSAL');
        $this->template->load('template', 'akuntansi/cetak_lpsal', $data);
    }

    function cetak_perdaIV_real($cetak = 1)
    {

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc)
        {

            $tgl = $rowsc->tgl_rka;
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
            $kab = $rowsc->kab_kota;
            $daerah = $rowsc->daerah;
            $thn = $rowsc->thn_ang;
        }


        $cRet = '';
        if ($cetak <> 0)
        {
            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td rowspan=\"3\" align=\"left\" width=\"60%\"><strong></strong></td>
                         <td rowspan=\"3\" valign =\"top\" align=\"left\" width=\"10%\"><strong><h5>LAMPIRAN IV  :</h6></strong></td>
                         <td colspan=\"2\" align=\"left\" width=\"30%\"><strong><h5>PERATURAN DAERAH</strong></h5></td>
                    </tr>
                    <tr> 
                         <td align=\"left\" width=\"10%\"><h5>NOMOR</h5></td><td width=\"20%\" align=\"left\" ><h5>:</h5></td>
                    </tr>
                    <tr>
                        <td style=\"border-bottom: solid 1px black;\" align=\"left\" width=\"10%\"><h5>TANGGAL</h5></td><td style=\"border-bottom: solid 1px black;\" align=\"left\" width=\"20%\"><h5>:</h5></td>
                    </tr>
                                         
                   
                  </table>";
        }
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>$kab</strong></td>
                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>LAPORAN REALISASI BELANJA MENURUT URUSAN PEMERINTAHAN DAERAH, ORGANISASI, PROGRAM DAN KEGIATAN</strong></td>
                    </tr>
                    
                    <tr>
                         <td align=\"center\"><strong>TAHUN ANGARAN $thn</strong></td>
                    </tr>

                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td rowspan=\"3\" bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>KODE</b></td>                            
                            <td rowspan=\"3\" bgcolor=\"#CCCCCC\" width=\"22%\" align=\"center\"><b>URAIAN URUSAN, ORGANISASI, PROGRAM DAN KEGIATAN PEMERINTAH DAERAH</b></td>                            
                            <td style=\"border-bottom: solid 1px black;\" colspan=\"4\" bgcolor=\"#CCCCCC\" width=\"49%\" align=\"center\"><b>SETELAH PERUBAHAN</b></td>
                            <td style=\"border-bottom: solid 1px black;\" colspan=\"4\" bgcolor=\"#CCCCCC\" width=\"49%\" align=\"center\"><b>REALISASI</b></td>
                            <td style=\"border-bottom: solid 1px black;\" colspan=\"2\" bgcolor=\"#CCCCCC\" width=\"12%\" align=\"center\"><b>LEBIH/(KURANG)</b></td>
                        </tr>
                        <tr>
                            <td style=\"border-bottom: solid 1px black;\" colspan=\"3\" width=\"21%\" bgcolor=\"#CCCCCC\" align=\"center\">JENIS BELANJA</td>
                            <td rowspan=\"2\" width=\"7%\" bgcolor=\"#CCCCCC\" align=\"center\">JUMLAH</td>
                            <td style=\"border-bottom: solid 1px black;\" colspan=\"3\" width=\"21%\" bgcolor=\"#CCCCCC\" align=\"center\">JENIS BELANJA</td>
                            <td rowspan=\"2\" width=\"7%\" bgcolor=\"#CCCCCC\" align=\"center\">JUMLAH</td>
                            <td rowspan=\"2\" width=\"7%\" bgcolor=\"#CCCCCC\" align=\"center\">(Rp)</td>
                            <td rowspan=\"2\" width=\"5%\" bgcolor=\"#CCCCCC\" align=\"center\">%</td>
                        </tr>
                        <tr>
 		                    <td width=\"7%\" bgcolor=\"#CCCCCC\" align=\"center\">PEGAWAI</td>
                            <td width=\"7%\" bgcolor=\"#CCCCCC\" align=\"center\">BARANG DAN JASA</td>
                            <td width=\"7%\" bgcolor=\"#CCCCCC\" align=\"center\">MODAL</td>
                            <td width=\"7%\" bgcolor=\"#CCCCCC\" align=\"center\">PEGAWAI</td>
                            <td width=\"7%\" bgcolor=\"#CCCCCC\" align=\"center\">BARANG DAN JASA</td>
                            <td width=\"7%\" bgcolor=\"#CCCCCC\" align=\"center\">MODAL</td>
                        </tr>    
                     </thead>
                    
                     
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"22%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"7%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"5%\">&nbsp;</td>
                        </tr>
                        ";
        $sql1 = "SELECT * FROM (SELECT a.kd_urusan1 AS kode,a.kd_urusan1 AS kode1, a.nm_urusan1 AS nama,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='521' AND LEFT(kd_skpd,1)=a.kd_urusan1) AS ang_521,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='522' AND LEFT(kd_skpd,1)=a.kd_urusan1)AS ang_522,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='523' AND LEFT(kd_skpd,1)=a.kd_urusan1) AS ang_523,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,2)='52' AND LEFT(kd_skpd,1)=a.kd_urusan1) AS ang_jumlah,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='521' AND LEFT(kd_skpd,1)=a.kd_urusan1) AS real_521,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='522' AND LEFT(kd_skpd,1)=a.kd_urusan1)AS real_522,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='523' AND LEFT(kd_skpd,1)=a.kd_urusan1) AS real_523,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,2)='52' AND LEFT(kd_skpd,1)=a.kd_urusan1) AS real_jumlah,'1' AS urut FROM trdrka b 
                        RIGHT JOIN ms_urusan1 a ON LEFT(b.kd_skpd,1)=a.kd_urusan1 GROUP BY  a.kd_urusan1 ,a.nm_urusan1   
                        UNION
                        SELECT a.kd_urusan AS kode,a.kd_urusan AS kode1, a.nm_urusan AS nama,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='521' AND LEFT(kd_skpd,4)=a.kd_urusan) AS ang_521,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='522' AND LEFT(kd_skpd,4)=a.kd_urusan)AS ang_522,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT (kd_rek5,3)='523' AND LEFT(kd_skpd,4)=a.kd_urusan) AS ang_523,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,2)='52' AND LEFT(kd_skpd,4)=a.kd_urusan)AS ang_jumlah,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='521' AND LEFT(kd_skpd,4)=a.kd_urusan) AS real_521,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='522' AND LEFT(kd_skpd,4)=a.kd_urusan)AS real_522,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT (kd_rek5,3)='523' AND LEFT(kd_skpd,4)=a.kd_urusan) AS real_523,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,2)='52' AND LEFT(kd_skpd,4)=a.kd_urusan)AS real_jumlah,'2' AS urut FROM trdrka b 
                        RIGHT JOIN ms_urusan a ON LEFT(b.kd_skpd,4)=a.kd_urusan GROUP BY  a.kd_urusan ,a.nm_urusan  
                        UNION
                        SELECT b.kd_skpd AS kode,b.kd_skpd AS kode1, b.nm_skpd AS nama,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='521' AND kd_skpd=a.kd_skpd) AS ang_521,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='522' AND kd_skpd=a.kd_skpd) AS ang_522, 
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='523' AND kd_skpd=a.kd_skpd) AS ang_523,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,2)='52' AND kd_skpd=a.kd_skpd)AS ang_jumlah,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='521' AND kd_skpd=a.kd_skpd) AS real_521,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='522' AND kd_skpd=a.kd_skpd) AS real_522, 
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='523' AND kd_skpd=a.kd_skpd) AS real_523,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,2)='52' AND kd_skpd=a.kd_skpd)AS real_jumlah,'3' AS urut FROM trdrka a 
                        RIGHT JOIN ms_skpd b ON a.kd_skpd=b.kd_skpd GROUP BY a.kd_skpd,b.nm_skpd 
                        UNION
                        SELECT b.kd_program AS kode,b.kd_program AS kode1,c.nm_program AS nama,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='521' AND kd_skpd=a.kd_skpd AND LEFT(kd_kegiatan,(LENGTH(b.kd_program)))=b.kd_program ) AS ang_521,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='522' AND kd_skpd=a.kd_skpd AND LEFT(kd_kegiatan,(LENGTH(b.kd_program)))=b.kd_program ) AS ang_522,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='523' AND kd_skpd=a.kd_skpd AND LEFT(kd_kegiatan,(LENGTH(b.kd_program)))=b.kd_program ) AS ang_523,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,2)='52' AND kd_skpd=a.kd_skpd AND LEFT(kd_kegiatan,(LENGTH(b.kd_program)))=b.kd_program ) AS ang_jumlah,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='521' AND kd_skpd=a.kd_skpd AND LEFT(kd_kegiatan,(LENGTH(b.kd_program)))=b.kd_program ) AS real_521,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='522' AND kd_skpd=a.kd_skpd AND LEFT(kd_kegiatan,(LENGTH(b.kd_program)))=b.kd_program ) AS real_522,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='523' AND kd_skpd=a.kd_skpd AND LEFT(kd_kegiatan,(LENGTH(b.kd_program)))=b.kd_program ) AS real_523,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,2)='52' AND kd_skpd=a.kd_skpd AND LEFT(kd_kegiatan,(LENGTH(b.kd_program)))=b.kd_program ) AS real_jumlah,
                        '4' AS urut
                        FROM trdrka a INNER JOIN trskpd b ON a.kd_kegiatan=b.kd_kegiatan
                        INNER JOIN m_prog c ON b.kd_program1 = c.kd_program WHERE RIGHT(b.kd_program,2) <>'00'GROUP BY  b.kd_program,c.nm_program 
                        UNION
                        SELECT b.kd_kegiatan AS kode,b.kd_kegiatan AS kode1,c.nm_kegiatan AS nama,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='521' AND kd_skpd=a.kd_skpd AND kd_kegiatan=b.kd_kegiatan ) AS ang_521,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='522' AND kd_skpd=a.kd_skpd AND kd_kegiatan=b.kd_kegiatan ) AS ang_522,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='523' AND kd_skpd=a.kd_skpd AND kd_kegiatan=b.kd_kegiatan ) AS ang_523,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,2)='52' AND kd_skpd=a.kd_skpd AND kd_kegiatan=b.kd_kegiatan ) AS ang_jumlah,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='521' AND kd_skpd=a.kd_skpd AND kd_kegiatan=b.kd_kegiatan ) AS real_521,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='522' AND kd_skpd=a.kd_skpd AND kd_kegiatan=b.kd_kegiatan ) AS real_522,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='523' AND kd_skpd=a.kd_skpd AND kd_kegiatan=b.kd_kegiatan ) AS real_523,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,2)='52' AND kd_skpd=a.kd_skpd AND kd_kegiatan=b.kd_kegiatan ) AS real_jumlah,
                        '5' AS urut
                        FROM trdrka a INNER JOIN trskpd b ON a.kd_kegiatan=b.kd_kegiatan
                        INNER JOIN m_giat c ON b.kd_kegiatan1 = c.kd_kegiatan WHERE RIGHT(b.kd_program,2) <>'00'GROUP BY  b.kd_kegiatan,c.nm_kegiatan 
                        UNION	
                        SELECT DISTINCT '' AS kode,'99' AS kode, 'JUMLAH' AS nama,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='521') AS ang_521,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='522' )AS ang_522,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,3)='523' ) AS ang_523,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,2)='52' ) AS ang_jumlah,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='521' ) AS real_521,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='522' )AS real_522,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,3)='523' ) AS real_523,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,2)='52' ) AS real_jumlah,'6' AS urut FROM trdrka) a ORDER BY kode1,urut";

        $query = $this->db->query($sql1);
        //$query = $this->skpd_model->getAllc();

        foreach ($query->result() as $row)
        {
            $kode = $row->kode;
            $nama = $row->nama;
            $ang521 = number_format($row->ang_521, "2", ".", ",");
            $ang522 = number_format($row->ang_522, "2", ".", ",");
            $ang523 = number_format($row->ang_523, "2", ".", ",");
            $jum_ang = number_format($row->ang_jumlah, "2", ".", ",");
            $real521 = number_format($row->real_521, "2", ".", ",");
            $real522 = number_format($row->real_522, "2", ".", ",");
            $real523 = number_format($row->real_523, "2", ".", ",");
            $jum_real = number_format($row->real_jumlah, "2", ".", ",");
            $rumus1 = $row->real_jumlah - $row->ang_jumlah;
            if ($rumus1 < 0)
            {
                $x1 = "(";
                $rumus1 = $rumus1 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $total1 = number_format($rumus1, "2", ".", ",");
            $per1 = ($rumus1 != 0) ? ($rumus1 / $row->ang_jumlah) * 100 : 0;
            $persen1 = number_format($per1, "2", ".", ",");

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"10%\" align=\"left\">$kode</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"22%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$ang521</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$ang522</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$ang523</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$jum_ang</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$real521</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$real522</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$real523</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$jum_real</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"7%\" align=\"right\">$x1$total1$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: solid 1px black;\" width=\"5%\" align=\"right\">$persen1</td>
                                </tr> ";


        }

        $cRet .= " </table>";
        $data['prev'] = $cRet;
        $judul = 'Laporan Realisasi APBD  Lamp IV ';
        $this->template->set('title', 'CETAK PERDA REALISASI LAMPIRAN IV');
        switch ($cetak)
        {
            case 1;
                $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }

    function lampiran_V()
    {
        $data['page_title'] = 'REALISASI';
        $this->template->set('title', 'REALISASI');
        $this->template->load('template', 'akuntansi/perda_real_v', $data);
    }

    function cetak_perdaV_real($cetak = 1)
    {

        $sqlsc = "SELECT tgl_rka,provinsi,kab_kota,daerah,thn_ang FROM sclient";
        $sqlsclient = $this->db->query($sqlsc);
        foreach ($sqlsclient->result() as $rowsc)
        {

            $tgl = $rowsc->tgl_rka;
            $tanggal = $this->tukd_model->tanggal_format_indonesia($tgl);
            $kab = $rowsc->kab_kota;
            $daerah = $rowsc->daerah;
            $thn = $rowsc->thn_ang;
        }


        $cRet = '';
        if ($cetak <> 0)
        {
            $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td rowspan=\"3\" align=\"left\" width=\"60%\"><strong></strong></td>
                         <td rowspan=\"3\" valign =\"top\" align=\"left\" width=\"15%\"><strong><h5>LAMPIRAN V  </h6></strong></td>
                         <td colspan=\"2\" align=\"left\" width=\"30%\"><strong><h5></strong></h5></td>
                    </tr>
                    <tr> 
                         <td align=\"left\" width=\"15%\"><h5>NOMOR</h5></td><td width=\"20%\" align=\"left\" ><h5>:</h5></td>
                    </tr>
                    <tr>
                        <td style=\"border-bottom: solid 1px black;\" align=\"left\" width=\"15%\"><h5>TANGGAL</h5></td><td style=\"border-bottom: solid 1px black;\" align=\"left\" width=\"20%\"><h5>:</h5></td>
                    </tr>
                                         
                   
                  </table>";
        }
        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"0\" cellspacing=\"0\" cellpadding=\"4\">
                    <tr>
                         <td align=\"center\"><strong>$kab</strong></td>
                         
                    </tr>
                    <tr>
                         <td align=\"center\"><strong>REALISASI BELANJA DAERAH UNTUK KESELARASAN DAN KETERPADUAN URUSAN PEMERINTAHAN DAERAH DAN FUNGSI DALAM KERANGKA PENGELOLAAN KEUANGAN NEGARA </strong></td>
                    </tr>
                    
                    <tr>
                         <td align=\"center\"><strong>TAHUN ANGARAN $thn</strong></td>
                    </tr>

                  </table>";

        $cRet .= "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
                     <thead>                       
                        <tr><td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"10%\" align=\"center\"><b>KODE</b></td>                            
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"39%\" align=\"center\"><b>URAIAN</b></td>                            
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"16%\" align=\"center\"><b>JUMLAH SETELAH PERUBAHAN</b></td>
                            <td rowspan=\"2\" bgcolor=\"#CCCCCC\" width=\"16%\" align=\"center\"><b>REALISASI</b></td>
                            <td colspan=\"2\" bgcolor=\"#CCCCCC\" width=\"19%\" align=\"center\"><b>LEBIH/(KURANG)</b></td>
                            </tr>
                        <tr>
 		                     <td width=\"16%\" bgcolor=\"#CCCCCC\" align=\"center\">JUMLAH</td>
                             <td width=\"3%\" bgcolor=\"#CCCCCC\" align=\"center\">%</td>
                        </tr>    
                     </thead>
                    <tfoot>
                        <tr>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>
                            <td style=\"border-top: none;\"></td>                           
                         </tr>
                     </tfoot>
                     
                        <tr><td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"10%\" align=\"center\">&nbsp;</td>                            
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"39%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"16%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"16%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"16%\">&nbsp;</td>
                            <td style=\"vertical-align:top;border-top: none;border-bottom: none;\" width=\"3%\">&nbsp;</td>
                        </tr>
                        ";
        $sql1 = "SELECT * FROM (SELECT b.kd_fungsi AS kode, c.nm_fungsi AS nama,b.kd_fungsi AS kode1,'1' AS kode2,' 'AS skpd,
                        (SELECT SUM(a.anggaran) FROM realisasi a LEFT JOIN ms_skpd b ON a.kd_skpd=b.kd_skpd WHERE LEFT(a.kd_rek5,1)='5' AND b.kd_fungsi=c.kd_fungsi)AS anggaran,
                        (SELECT SUM(a.real_spj) FROM realisasi a LEFT JOIN ms_skpd b ON a.kd_skpd=b.kd_skpd WHERE LEFT(a.kd_rek5,1)='5' AND b.kd_fungsi=c.kd_fungsi)AS realisasi 
                        FROM trdrka a  INNER JOIN ms_skpd b ON a.kd_skpd=b.kd_skpd LEFT JOIN ms_fungsi c ON b.kd_fungsi=c.kd_fungsi GROUP BY b.kd_fungsi,c.nm_fungsi,b.kd_fungsi
                        UNION
                        SELECT a.kd_skpd AS kode, b.nm_skpd AS nama,b.kd_fungsi AS kode1,'2' AS kode2,a.kd_skpd AS skpd,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,1)='5' AND kd_skpd=a.kd_skpd)AS anggaran,
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,1)='5' AND kd_skpd=a.kd_skpd)AS realisasi FROM trdrka a 
                        INNER JOIN ms_skpd b ON a.kd_skpd=b.kd_skpd GROUP BY a.kd_skpd,b.nm_skpd,b.kd_fungsi
                        UNION	
                        SELECT DISTINCT '' AS kode, 'JUMLAH' AS nama,'99' AS kode1,'3' AS kode2,'' AS skpd,
                        (SELECT SUM(anggaran) FROM realisasi WHERE LEFT(kd_rek5,1)='5') AS anggaran,                                          
                        (SELECT SUM(real_spj) FROM realisasi WHERE LEFT(kd_rek5,1)='5' ) AS realisasi FROM trdrka) a ORDER BY kode1,a.kode2,skpd";

        $query = $this->db->query($sql1);
        //$query = $this->skpd_model->getAllc();

        foreach ($query->result() as $row)
        {
            $kode = $row->kode;
            $nama = $row->nama;
            $ang = number_format($row->anggaran, "2", ".", ",");
            $real = number_format($row->realisasi, "2", ".", ",");
            $rumus1 = $row->realisasi - $row->anggaran;
            if ($rumus1 < 0)
            {
                $x1 = "(";
                $rumus1 = $rumus1 * -1;
                $y1 = ")";
            } else
            {
                $x1 = "";
                $y1 = "";
            }
            $total1 = number_format($rumus1, "2", ".", ",");
            $per1 = ($rumus1 != 0) ? ($rumus1 / $row->anggaran) * 100 : 0;
            $persen1 = number_format($per1, "2", ".", ",");

            $cRet .= " <tr><td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"8%\" align=\"left\">$kode</td>                                     
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"20%\">$nama</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"12%\" align=\"right\">$ang</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"12%\" align=\"right\">$real</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"12%\" align=\"right\">$x1$total1$y1</td>
                                     <td style=\"vertical-align:top;border-top: solid 1px black;border-bottom: none;\" width=\"12%\" align=\"right\">$persen1</td>
                                    </tr>
                                     ";


        }

        $cRet .= " </table>";
        $data['prev'] = $cRet;
        $judul = 'Laporan Realisasi APBD  Lamp V ';
        $this->template->set('title', 'CETAK PERDA REALISASI LAMPIRAN V');
        switch ($cetak)
        {
            case 1;
                $this->tukd_model->_mpdf('', $cRet, 10, 10, 10, '0');
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }

    }

    function jur_umum()
    {
        $data['page_title'] = 'JURNAL UMUM';
        $this->template->set('title', 'JURNAL UMUM');
        $this->template->load('template', 'akuntansi/jur_umum', $data);
    }
    function jur_umum_ppkd()
    {
        $data['page_title'] = 'JURNAL UMUM';
        $this->template->set('title', 'JURNAL UMUM');
        $this->template->load('template', 'akuntansi/jur_umum_ppkd', $data);
    }

    function ctk_jurum($dcetak='',$dcetak2='',$skpd=''){
   	        $csql11 = " select nm_skpd from ms_skpd where kd_skpd = '$skpd'"; 
            $rs1 = $this->db->query($csql11);
            $trh1 = $rs1->row();
            $lcskpd = strtoupper ($trh1->nm_skpd);
            $tgl=$this->tukd_model->tanggal_format_indonesia($dcetak);
            $tgl2=$this->tukd_model->tanggal_format_indonesia($dcetak2);
            

			$cRet ="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
            <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;\">$lcskpd
                </td>
            </tr>
             <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;\">JURNAL UMUM
                </td>
            </tr>
            <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;border-bottom:solid 1px black;\">PERIODE $tgl S.D $tgl2
                </td>
            </tr>
            <tr>
                <td align=\"center\" rowspan=\"2\">Tanggal</td>
                <td align=\"center\" rowspan=\"2\">Nomor<br>Bukti</td>
                <td colspan=\"5\" align=\"center\" rowspan=\"2\">Kode<br>Rekening</td>
                <td align=\"center\" rowspan=\"2\">Uraian</td>
                <td align=\"center\" rowspan=\"2\">ref</td>
                <td align=\"center\" colspan=\"2\">Jumlah Rp</td>
            </tr>
            <tr>
                <td align=\"center\">Debit</td>
                <td align=\"center\">Kredit</td>
            </tr>
            <tr>
                <td align=\"center\" width=\"10%\">1</td>
                <td align=\"center\" width=\"10%\">2</td>
                <td colspan=\"5\" align=\"center\" width=\"15%\">3</td>
                <td align=\"center\" width=\"42%\">4</td>
                <td align=\"center\" width=\"3%\"></td>
                <td align=\"center\" width=\"10%\">5</td>
                <td align=\"center\" width=\"10%\">6</td>
            </tr>
           ";
         
         $csql1 = "select count(*) as tot FROM 
                 trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher 
                 where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd'"; 
         $rs = $this->db->query($csql1);
         $trh = $rs->row();
         
            
         $csql = "SELECT b.tgl_voucher,a.no_voucher,a.kd_rek5,CONCAT(a.nm_rek5,IF(pos='0','','')) AS nm_rek5,a.debet,a.kredit FROM 
                  trdju_pkd a LEFT JOIN trhju_pkd b ON a.no_voucher= b.no_voucher
                  where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd' 
                  ORDER BY b.tgl_voucher,a.no_voucher,a.urut,a.rk,a.kd_rek5";   
         $query = $this->db->query($csql);  
         $cnovoc = '';
         $lcno = 0;
         foreach($query->result_array() as $res){
                $lcno = $lcno + 1;
                if ($lcno==$trh->tot){
                    $cRet .="<tr>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],0,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],1,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],2,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],3,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],5,2)."</td>
                                <td style=\"border-bottom:none;\">".$res['nm_rek5']."</td>
                                <td style=\"border-bottom:none;\"></td>";
                                if($res['debet']==0){
                                    $cRet .=" <td style=\"border-bottom:none;\"></td>
                                            <td style=\"border-bottom:none;\" align=\"right\">".number_format($res['kredit'])."</td>";
                                }else{$cRet .="<td style=\"border-bottom:none;\" align=\"right\">".number_format($res['debet'])."</td>
                                               <td style=\"border-bottom:none;\"></td>";                                    
                                }
                       
                       $cRet .="</tr>"; 
                }else{
                        if($cnovoc==$res['no_voucher']){
                            $cRet .="<tr>
                                <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
                                <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],0,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],1,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],2,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],3,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],5,2)."</td>
                                <td style=\"border-bottom:none;\">".$res['nm_rek5']."</td>
                                <td style=\"border-bottom:none;\"></td>";
                                if($res['debet']==0){
                                    $cRet .=" <td style=\"border-bottom:none;\"></td>
                                            <td style=\"border-bottom:none;\" align=\"right\">".number_format($res['kredit'])."</td>";
                                }else{$cRet .="<td style=\"border-bottom:none;\" align=\"right\">".number_format($res['debet'])."</td>
                                               <td style=\"border-bottom:none;\"></td>";                                    
                                }
                       
                       $cRet .="</tr>";                    
                        }else{
                        $cRet .="<tr>
                                <td style=\"border-bottom:none\">".$this->tukd_model->tanggal_ind($res['tgl_voucher'])."</td>
                                <td style=\"border-bottom:none\">".$res['no_voucher']."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],0,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],1,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],2,1)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],3,2)."</td>
                                <td style=\"border-bottom:none;\">".substr($res['kd_rek5'],5,2)."</td>
                                <td style=\"border-bottom:none;\">".$res['nm_rek5']."</td>
                                <td style=\"border-bottom:none;\"></td>";
                                if($res['debet']==0){
                                    $cRet .=" <td style=\"border-bottom:none;\"></td>
                                            <td style=\"border-bottom:none;\" align=\"right\">".number_format($res['kredit'])."</td>";
                                }else{$cRet .="<td style=\"border-bottom:none;\" align=\"right\">".number_format($res['debet'])."</td>
                                               <td style=\"border-bottom:none;\"></td>";                                    
                                }
                       
                       $cRet .="</tr>";
                        }
                        $cnovoc=$res['no_voucher'];
                }
                
            }
            
         $cRet .=" <tr>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                    </tr>  
         </table>
         ";

			$data['prev']= 'JURNAL UMUM';
            $this->tukd_model->_mpdf('',$cRet,5,5,10,'0');	
	
	} 


    function ctk_jurum_ppkd($dcetak = '', $dcetak2 = '', $skpd = '')
    {
        $csql11 = " select nm_skpd from ms_skpd where kd_skpd = '$skpd'";
        $rs1 = $this->db->query($csql11);
        $trh1 = $rs1->row();
        $lcskpd = strtoupper($trh1->nm_skpd);
        $tgl_cetak = $this->tukd_model->rev_date($dcetak);
        $tgl_cetak2 = $this->tukd_model->rev_date($dcetak2);


        $cRet = "<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\">
            <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;\">$lcskpd
                </td>
            </tr>
             <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;\">JURNAL UMUM
                </td>
            </tr>
            <tr>
                <td colspan=\"11\" align=\"center\" style=\"border: solid 1px white;border-bottom:solid 1px black;\">PERIODE $tgl_cetak S.D $tgl_cetak2
                </td>
            </tr>
            <tr>
                <td align=\"center\" rowspan=\"2\">Tanggal</td>
                <td align=\"center\" rowspan=\"2\">Nomor<br>Bukti</td>
                <td colspan=\"5\" align=\"center\" rowspan=\"2\">Kode<br>Rekening</td>
                <td align=\"center\" rowspan=\"2\">Uraian</td>
                <td align=\"center\" rowspan=\"2\">ref</td>
                <td align=\"center\" colspan=\"2\">Jumlah Rp</td>
            </tr>
            <tr>
                <td align=\"center\">Debit</td>
                <td align=\"center\">Kredit</td>
            </tr>
            <tr>
                <td align=\"center\" width=\"10%\">1</td>
                <td align=\"center\" width=\"10%\">2</td>
                <td colspan=\"5\" align=\"center\" width=\"15%\">3</td>
                <td align=\"center\" width=\"42%\">4</td>
                <td align=\"center\" width=\"3%\"></td>
                <td align=\"center\" width=\"10%\">5</td>
                <td align=\"center\" width=\"10%\">6</td>
            </tr>
           ";

        $csql1 = "select count(*) as tot FROM 
                 trdju_ppkd a LEFT JOIN trhju_ppkd b ON a.no_voucher= b.no_voucher 
                 where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd'";
        $rs = $this->db->query($csql1);
        $trh = $rs->row();


        $csql = "SELECT b.tgl_voucher,a.no_voucher,a.kd_rek5,CONCAT(a.nm_rek5,IF(pos='0',' *','')) AS nm_rek5,a.debet,a.kredit FROM 
                  trdju_ppkd a LEFT JOIN trhju_ppkd b ON a.no_voucher= b.no_voucher
                  where b.tgl_voucher >= '$dcetak' and b.tgl_voucher <= '$dcetak2' and b.kd_skpd = '$skpd' 
                  ORDER BY b.tgl_voucher,a.no_voucher,a.urut,a.rk,a.kd_rek5";
        $query = $this->db->query($csql);
        $cnovoc = '';
        $lcno = 0;
        foreach ($query->result_array() as $res)
        {
            $lcno = $lcno + 1;
            if ($lcno == $trh->tot)
            {
                $cRet .= "<tr>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;border-top:none;\"></td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                    0, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                    1, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                    2, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                    3, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                    5, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . $res['nm_rek5'] .
                    "</td>
                                <td style=\"border-bottom:none;\"></td>";
                if ($res['debet'] == 0)
                {
                    $cRet .= " <td style=\"border-bottom:none;\"></td>
                                            <td style=\"border-bottom:none;\" align=\"right\">" .
                        number_format($res['kredit']) . "</td>";
                } else
                {
                    $cRet .= "<td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['debet']) .
                        "</td>
                                               <td style=\"border-bottom:none;\"></td>";
                }

                $cRet .= "</tr>";
            } else
            {
                if ($cnovoc == $res['no_voucher'])
                {
                    $cRet .= "<tr>
                                <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
                                <td style=\"border-bottom:none;border-top:none;\">&nbsp;</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                        0, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                        1, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                        2, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                        3, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                        5, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . $res['nm_rek5'] .
                        "</td>
                                <td style=\"border-bottom:none;\"></td>";
                    if ($res['debet'] == 0)
                    {
                        $cRet .= " <td style=\"border-bottom:none;\"></td>
                                            <td style=\"border-bottom:none;\" align=\"right\">" .
                            number_format($res['kredit']) . "</td>";
                    } else
                    {
                        $cRet .= "<td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['debet']) .
                            "</td>
                                               <td style=\"border-bottom:none;\"></td>";
                    }

                    $cRet .= "</tr>";
                } else
                {
                    $cRet .= "<tr>
                                <td style=\"border-bottom:none\">" . $this->
                        tukd_model->rev_date($res['tgl_voucher']) . "</td>
                                <td style=\"border-bottom:none\">" . $res['no_voucher'] .
                        "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                        0, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                        1, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                        2, 1) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                        3, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . substr($res['kd_rek5'],
                        5, 2) . "</td>
                                <td style=\"border-bottom:none;\">" . $res['nm_rek5'] .
                        "</td>
                                <td style=\"border-bottom:none;\"></td>";
                    if ($res['debet'] == 0)
                    {
                        $cRet .= " <td style=\"border-bottom:none;\"></td>
                                            <td style=\"border-bottom:none;\" align=\"right\">" .
                            number_format($res['kredit']) . "</td>";
                    } else
                    {
                        $cRet .= "<td style=\"border-bottom:none;\" align=\"right\">" . number_format($res['debet']) .
                            "</td>
                                               <td style=\"border-bottom:none;\"></td>";
                    }

                    $cRet .= "</tr>";
                }
                $cnovoc = $res['no_voucher'];
            }

        }

        $cRet .= " <tr>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                        <td style=\"border-top:none\"></td>
                    </tr>  
         </table>
         ";

        $data['prev'] = 'JURNAL UMUM PPKD';
        $this->tukd_model->_mpdf('', $cRet, 5, 5, 10, '0');

    }


    //wahyu========================================================================
    function cetak_lak()
    {
        $data['page_title'] = 'LAPORAN ARUS KAS';
        $this->template->set('title', 'LAPORAN ARUS KAS');
        $this->template->load('template', 'akuntansi/cetak_lak', $data);
    }

    function rpt_lak($cetak = 1)
    {
        $thi = $this->session->userdata('pcThang');
        $thl = $thi - 1;
        $cRet = '<TABLE width="100%">
					<TR>
						<TD align="center" >LAPORAN ARUS KAS ( LAK )</TD>
					</TR>
					<TR>
						<TD align="center" >TAHUN ANGGARAN ' . $thi . '</TD>
					</TR>
					</TABLE><br>';


        $cRet .= '<TABLE border="1" cellspacing="0" cellpadding="0" width="100%" >
					 <TR>
						<TD width="70%" align="center" >URAIAN</TD>
						<TD width="15%" align="center" >' . $thi . '</TD>
						<TD width="15%" align="center" >' . $thl . '</TD>
					 </TR>';

        //level 1
        $idx = 0;

        $parent = '0';
        $no = '0';
        $query = $this->db->query(" SELECT * FROM rg_lak ORDER BY seq ");
        foreach ($query->result_array() as $res)
        {

            $idx++;
            $no = $res['nor'];
            $uraian = $res['uraian'];
            $seq = $res['seq'];
            $parent = $res['parent'];

            $rek1 = trim($res['rek1']);
            $rek2 = trim($res['rek2']);
            $rek3 = trim($res['rek3']);
            $rek4 = trim($res['rek4']);
            $rek5 = trim($res['rek5']);
            $rek6 = trim($res['rek6']);
            $rek7 = trim($res['rek7']);
            $rek8 = trim($res['rek8']);
            $rek9 = trim($res['rek9']);
            $rek10 = trim($res['rek10']);
            $rek11 = trim($res['rek11']);
            $rek12 = trim($res['rek12']);
            $rek13 = trim($res['rek13']);
            $rek14 = trim($res['rek14']);
            $rek15 = trim($res['rek15']);
            $rek16 = trim($res['rek16']);
            $rek17 = trim($res['rek17']);
            $rek18 = trim($res['rek18']);
            $rek19 = trim($res['rek19']);
            $rek20 = trim($res['rek20']);
            $rek21 = trim($res['rek21']);
            $rek22 = trim($res['rek22']);
            $rek23 = trim($res['rek23']);
            $rek24 = trim($res['rek24']);
            $rek25 = trim($res['rek25']);

            if ($rek1 == '')
                $rek1 = 'xxx';
            if ($rek2 == '')
                $rek2 = 'xxx';
            if ($rek3 == '')
                $rek3 = 'xxx';
            if ($rek4 == '')
                $rek4 = 'xxx';
            if ($rek5 == '')
                $rek5 = 'xxx';
            if ($rek6 == '')
                $rek6 = 'xxx';
            if ($rek7 == '')
                $rek7 = 'xxx';
            if ($rek8 == '')
                $rek8 = 'xxx';
            if ($rek9 == '')
                $rek9 = 'xxx';
            if ($rek10 == '')
                $rek10 = 'xxx';
            if ($rek11 == '')
                $rek11 = 'xxx';
            if ($rek12 == '')
                $rek12 = 'xxx';
            if ($rek13 == '')
                $rek13 = 'xxx';
            if ($rek14 == '')
                $rek14 = 'xxx';
            if ($rek15 == '')
                $rek15 = 'xxx';
            if ($rek16 == '')
                $rek16 = 'xxx';
            if ($rek17 == '')
                $rek17 = 'xxx';
            if ($rek18 == '')
                $rek18 = 'xxx';
            if ($rek19 == '')
                $rek19 = 'xxx';
            if ($rek20 == '')
                $rek20 = 'xxx';
            if ($rek21 == '')
                $rek21 = 'xxx';
            if ($rek22 == '')
                $rek22 = 'xxx';
            if ($rek23 == '')
                $rek23 = 'xxx';
            if ($rek24 == '')
                $rek24 = 'xxx';
            if ($rek25 == '')
                $rek25 = 'xxx';


            $q = $this->db->query(" select sum(real_spj) as nilai from realisasi where 
										kd_rek5 like '$rek1%' or kd_rek5 like '$rek2%'  or 
										kd_rek5 like '$rek3%' or kd_rek5 like '$rek4%'  or 
										kd_rek5 like '$rek5%' or kd_rek5 like '$rek6%'  or 
										kd_rek5 like '$rek7%' or kd_rek5 like '$rek8%'  or 
										kd_rek5 like '$rek9%' or kd_rek5 like '$rek10%' or 
										kd_rek5 like '$rek11%' or kd_rek5 like '$rek12%' or 
										kd_rek5 like '$rek13%' or kd_rek5 like '$rek14%' or 
										kd_rek5 like '$rek15%' or kd_rek5 like '$rek16%' or 
										kd_rek5 like '$rek17%' or kd_rek5 like '$rek18%' or 
										kd_rek5 like '$rek19%' or kd_rek5 like '$rek20%' or 
										kd_rek5 like '$rek21%' or kd_rek5 like '$rek22%' or 
										kd_rek5 like '$rek23%' or kd_rek5 like '$rek24%' or 
										kd_rek5 like '$rek25%'
										");

            foreach ($q->result_array() as $r)
            {
                $nilai = $r['nilai'];
            }


            if ($nilai == '')
                $nilai = 0;
            $q = $this->db->query(" update rg_lak set  nilai=$nilai where nor='$no' ");

        }


        $query = $this->db->query(" SELECT * FROM rg_lak where bold='2' ORDER BY seq ");
        foreach ($query->result_array() as $res)
        {

            $no = $res['nor'];
            $q = $this->db->query(" select sum(nilai) as nilai from rg_lak where bold='1' and parent='$no' ");
            foreach ($q->result_array() as $r)
            {
                $nilai = $r['nilai'];
            }
            if ($nilai == '')
                $nilai = 0;
            $q = $this->db->query(" update rg_lak set  nilai=$nilai where nor='$no' ");

        }

        $query = $this->db->query(" SELECT * FROM rg_lak where bold='5' ORDER BY seq ");
        foreach ($query->result_array() as $res)
        {

            $no = $res['nor'];
            $t = $res['tambah'];
            $k = $res['kurang'];
            $tambah = 0;
            $kurang = 0;
            $q = $this->db->query(" select  nilai from rg_lak where nor='$t' ");
            foreach ($q->result_array() as $r)
            {
                $tambah = $r['nilai'];
            }
            $q = $this->db->query(" select  nilai from rg_lak where nor='$k' ");
            foreach ($q->result_array() as $r)
            {
                $kurang = $r['nilai'];
            }
            $nilai = $tambah - $kurang;

            $q = $this->db->query(" update rg_lak set  nilai=$nilai where nor='$no' ");

        }


        $query = $this->db->query(" SELECT * FROM rg_lak where bold='4' ORDER BY seq ");
        foreach ($query->result_array() as $res)
        {

            $no = $res['nor'];
            $p = $res['parent'];
            $nilai = 0;
            $q = $this->db->query(" select  nilai from rg_lak where nor='$p' ");
            foreach ($q->result_array() as $r)
            {
                $nilai = $r['nilai'];
            }
            $q = $this->db->query(" update rg_lak set  nilai=$nilai where nor='$no' ");

        }


        $idx = 0;
        $query = $this->db->query(" SELECT * FROM rg_lak  ORDER BY seq ");
        foreach ($query->result_array() as $res)
        {
            $idx++;
            $no = $res['nor'];
            $uraian = $res['uraian'];
            $seq = $res['seq'];
            $parent = $res['parent'];
            $bold = $res['bold'];
            $nl = $res['nilai'];
            $sblm = $res['nilai_lalu'];
            if (trim($parent) == '')
                $parent = 'xxx';

            if ($bold == '1')
            {
                $cRet .= '<TR>
							<TD width="70%" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' .
                    $uraian . '</TD>
							<TD width="15%" align="right" >' . number_format($nl) . '</TD>
							<TD width="15%" align="right" >' . number_format($sblm) . '</TD>
						 </TR>';
            } elseif ($bold == '2')
            {
                $cRet .= '<TR>
							<TD width="70%" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $uraian .
                    '</TD>
							<TD width="15%" align="right" >' . number_format($nl) . '</TD>
							<TD width="15%" align="right" >' . number_format($sblm) . '</TD>
						 </TR>';
            } elseif ($bold == '4')
            {
                $cRet .= '<TR>
							<TD width="70%" align="left" >' . $uraian . '</TD>
							<TD width="15%" align="right" >' . number_format($nl) . '</TD>
							<TD width="15%" align="right" >' . number_format($sblm) . '</TD>
						 </TR>';

            } elseif ($bold == '5')
            {
                $cRet .= '<TR>
							<TD width="70%" align="left" >' . $uraian . '</TD>
							<TD width="15%" align="right" >' . number_format($nl) . '</TD>
							<TD width="15%" align="right" >' . number_format($sblm) . '</TD>
						 </TR>';

            } elseif ($bold == '3')
            {
                $cRet .= '<TR>
							<TD width="70%" align="left" >' . $uraian . '</TD>
							<TD width="15%" align="right" >' . number_format($nl) . '</TD>
							<TD width="15%" align="right" >' . number_format($sblm) . '</TD>
						 </TR>';

            } elseif ($bold == '')
            {
                $cRet .= '<TR>
							<TD width="70%" align="left" >&nbsp;</TD>
							<TD width="15%" align="right" >&nbsp;</TD>
							<TD width="15%" align="center" >&nbsp;</TD>
						 </TR>';

            }
        }


        $cRet .= '</TABLE>';
        $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
    }

    function cetak_neraca()
    {
        $data['page_title'] = 'LAPORAN NERACA';
        $this->template->set('title', 'LAPORAN NERACA');
        $this->template->load('template', 'akuntansi/cetak_neraca', $data);
    }
    
    function cetak_neraca_ppkd()
    {
        $data['page_title'] = 'LAPORAN NERACA';
        $this->template->set('title', 'LAPORAN NERACA');
        $this->template->load('template', 'akuntansi/cetak_neraca_ppkd', $data);
    }

    function rpt_neraca_ppkd($cetak=1){
	        $skpd     = '1.20.05.01';//$this->session->userdata('kdskpd');
			$thi=$this->session->userdata('pcThang');
			$thl=$thi-1;
			$cRet ='<TABLE width="100%">
					<TR>
						<TD align="center" >LAPORAN NERACA</TD>
					</TR>
					<TR>
						<TD align="center" >TAHUN ANGGARAN '.$thi.'</TD>
					</TR>
					</TABLE><br>';


			$cRet .='<TABLE border="1" cellspacing="0" cellpadding="0" width="100%" >
					 <TR>
						<TD width="70%" align="center" >URAIAN</TD>
						<TD width="15%" align="center" >'.$thi.'</TD>
						<TD width="15%" align="center" >'.$thl.'</TD>
					 </TR>';
			
			//level 1
			$idx=0;
			
			
			
			
			$parent='0';
			$no='0';
			$query = $this->db->query(" SELECT * FROM map_neraca_ppkd  ORDER BY kode ");  
			foreach($query->result_array() as $res){

				$idx++;				
				$no=$res['kode'];
				$uraian=$res['uraian'];
				$seq=$res['seq'];
				$parent=$res['parent'];
				$normal=$res['normal'];

				$kode_1=trim($res['kode_1']);
				$kode_2=trim($res['kode_2']);
				$kode_3=trim($res['kode_3']);
				$kode_4=trim($res['kode_4']);
				$kode_5=trim($res['kode_5']);
				$kode_6=trim($res['kode_6']);
				$kode_7=trim($res['kode_7']);
				$kode_8=trim($res['kode_8']);
				$kode_9=trim($res['kode_9']);
				$kode_10=trim($res['kode_10']);
				$kode_11=trim($res['kode_11']);
				$kode_12=trim($res['kode_12']);
				$kode_13=trim($res['kode_13']);
				$kode_14=trim($res['kode_14']);
				$kode_15=trim($res['kode_15']);

				if ($kode_1=='') $kode_1='xxx';												
				if ($kode_2=='') $kode_2='xxx';												
				if ($kode_3=='') $kode_3='xxx';												
				if ($kode_4=='') $kode_4='xxx';												
				if ($kode_5=='') $kode_5='xxx';												
				if ($kode_6=='') $kode_6='xxx';												
				if ($kode_7=='') $kode_7='xxx';												
				if ($kode_8=='') $kode_8='xxx';												
				if ($kode_9=='') $kode_9='xxx';												
				if ($kode_10=='') $kode_10='xxx';												
				if ($kode_11=='') $kode_11='xxx';												
				if ($kode_12=='') $kode_12='xxx';												
				if ($kode_13=='') $kode_13='xxx';												
				if ($kode_14=='') $kode_14='xxx';												
				if ($kode_15=='') $kode_15='xxx';												


				$q = $this->db->query(" SELECT SUM(a.debet) AS debet,SUM(a.kredit) AS kredit FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher WHERE b.kd_skpd='$skpd' and
										(kd_rek5 like '$kode_1%' or kd_rek5 like '$kode_2%'  or 
										kd_rek5 like '$kode_3%' or kd_rek5 like '$kode_4%'  or 
										kd_rek5 like '$kode_5%' or kd_rek5 like '$kode_6%'  or 
										kd_rek5 like '$kode_7%' or kd_rek5 like '$kode_8%'  or 
										kd_rek5 like '$kode_9%' or kd_rek5 like '$kode_10%' or 
										kd_rek5 like '$kode_11%' or kd_rek5 like '$kode_12%' or 
										kd_rek5 like '$kode_13%' or kd_rek5 like '$kode_14%' or 
										kd_rek5 like '$kode_15%') 	");  

				 foreach($q->result_array() as $r){
					$debet=$r['debet'];
					$kredit=$r['kredit'];
				 }
				
				if ($debet=='') $debet=0;
				if ($kredit=='') $kredit=0;

				if ($normal=='D'){
					$nilai=$debet-$kredit;
				}else{
					$nilai=$kredit-$debet;				
				}
				if ($nilai=='') $nilai=0;

				$q = $this->db->query(" update map_neraca_ppkd set  nilai=$nilai where kode='$no' ");  
				
			}




			$idx=0;			
			$query = $this->db->query(" SELECT * FROM map_neraca_ppkd  ORDER BY kode ");  
			foreach($query->result_array() as $res){
				$idx++;
				$no=$res['kode'];
				$uraian=$res['uraian'];
				$seq=$res['seq'];
				$parent=$res['parent'];
				$bold=$res['bold'];
				$nl=$res['nilai'];
				$sblm=$res['nilai_lalu'];

				if (trim($parent)=='') $parent='xxx';

				if ($bold=='1'){
				$cRet .='<TR>
							<TD width="70%" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$uraian.'</TD>
							<TD width="15%" align="right" >'.number_format($nl).'</TD>
							<TD width="15%" align="right" >'.number_format($sblm).'</TD>
						 </TR>';
				}elseif($bold=='2'){ 
				$cRet .='<TR>
							<TD width="70%" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$uraian.'</TD>
							<TD width="15%" align="right" >'.number_format($nl).'</TD>
							<TD width="15%" align="right" >'.number_format($sblm).'</TD>
						 </TR>';
				}elseif($bold=='3'){ 
				$cRet .='<TR>
							<TD width="70%" align="left" >'.$uraian.'</TD>
							<TD width="15%" align="right" >'.number_format($nl).'</TD>
							<TD width="15%" align="right" >'.number_format($sblm).'</TD>
						 </TR>';

				}elseif($bold=='4'){ 
				$cRet .='<TR>
							<TD width="70%" align="left" >'.$uraian.'</TD>
							<TD width="15%" align="right" >'.number_format($nl).'</TD>
							<TD width="15%" align="right" >'.number_format($sblm).'</TD>
						 </TR>';

				}elseif($bold=='5'){ 
				$cRet .='<TR>
							<TD width="70%" align="left" >'.$uraian.'</TD>
							<TD width="15%" align="right" >'.number_format($nl).'</TD>
							<TD width="15%" align="right" >'.number_format($sblm).'</TD>
						 </TR>';

				}elseif($bold=='6'){ 
				$cRet .='<TR>
							<TD width="70%" align="left" >'.$uraian.'</TD>
							<TD width="15%" align="right" >'.number_format($nl).'</TD>
							<TD width="15%" align="right" >'.number_format($sblm).'</TD>
						 </TR>';

				}elseif($bold==''){ 
				$cRet .='<TR>
							<TD width="70%" align="left" >'.$uraian.'</TD>
							<TD width="15%" align="right" >'.number_format($nl).'</TD>
							<TD width="15%" align="right" >'.number_format($sblm).'</TD>
						 </TR>';

				}
			}

			
			$cRet .='</TABLE>';		 
		    $this->tukd_model->_mpdf('',$cRet,10,5,10,'0');	
	}
    
    function rpt_neraca($cetak=1){
	        
			$thi=$this->session->userdata('pcThang');
			$thl=$thi-1;
			$cRet ='<TABLE width="100%">
					<TR>
						<TD align="center" >LAPORAN NERACA</TD>
					</TR>
					<TR>
						<TD align="center" >TAHUN ANGGARAN '.$thi.'</TD>
					</TR>
					</TABLE><br>';


			$cRet .='<TABLE border="1" cellspacing="0" cellpadding="0" width="100%" >
					 <TR>
						<TD width="70%" align="center" >URAIAN</TD>
						<TD width="15%" align="center" >'.$thi.'</TD>
						<TD width="15%" align="center" >'.$thl.'</TD>
					 </TR>';
			
			//level 1
			$idx=0;
			
			
			
			
			$parent='0';
			$no='0';
			$query = $this->db->query(" SELECT * FROM map_neraca  ORDER BY kode ");  
			foreach($query->result_array() as $res){

				$idx++;				
				$no=$res['kode'];
				$uraian=$res['uraian'];
				$seq=$res['seq'];
				$parent=$res['parent'];
				$normal=$res['normal'];

				$kode_1=trim($res['kode_1']);
				$kode_2=trim($res['kode_2']);
				$kode_3=trim($res['kode_3']);
				$kode_4=trim($res['kode_4']);
				$kode_5=trim($res['kode_5']);
				$kode_6=trim($res['kode_6']);
				$kode_7=trim($res['kode_7']);
				$kode_8=trim($res['kode_8']);
				$kode_9=trim($res['kode_9']);
				$kode_10=trim($res['kode_10']);
				$kode_11=trim($res['kode_11']);
				$kode_12=trim($res['kode_12']);
				$kode_13=trim($res['kode_13']);
				$kode_14=trim($res['kode_14']);
				$kode_15=trim($res['kode_15']);

				if ($kode_1=='') $kode_1='xxx';												
				if ($kode_2=='') $kode_2='xxx';												
				if ($kode_3=='') $kode_3='xxx';												
				if ($kode_4=='') $kode_4='xxx';												
				if ($kode_5=='') $kode_5='xxx';												
				if ($kode_6=='') $kode_6='xxx';												
				if ($kode_7=='') $kode_7='xxx';												
				if ($kode_8=='') $kode_8='xxx';												
				if ($kode_9=='') $kode_9='xxx';												
				if ($kode_10=='') $kode_10='xxx';												
				if ($kode_11=='') $kode_11='xxx';												
				if ($kode_12=='') $kode_12='xxx';												
				if ($kode_13=='') $kode_13='xxx';												
				if ($kode_14=='') $kode_14='xxx';												
				if ($kode_15=='') $kode_15='xxx';												


				$q = $this->db->query(" SELECT SUM(a.debet) AS debet,SUM(a.kredit) AS kredit FROM trdju_pkd a INNER JOIN trhju_pkd b ON a.no_voucher=b.no_voucher WHERE 
										kd_rek5 like '$kode_1%' or kd_rek5 like '$kode_2%'  or 
										kd_rek5 like '$kode_3%' or kd_rek5 like '$kode_4%'  or 
										kd_rek5 like '$kode_5%' or kd_rek5 like '$kode_6%'  or 
										kd_rek5 like '$kode_7%' or kd_rek5 like '$kode_8%'  or 
										kd_rek5 like '$kode_9%' or kd_rek5 like '$kode_10%' or 
										kd_rek5 like '$kode_11%' or kd_rek5 like '$kode_12%' or 
										kd_rek5 like '$kode_13%' or kd_rek5 like '$kode_14%' or 
										kd_rek5 like '$kode_15%' 	");  

				 foreach($q->result_array() as $r){
					$debet=$r['debet'];
					$kredit=$r['kredit'];
				 }
				
				if ($debet=='') $debet=0;
				if ($kredit=='') $kredit=0;

				if ($normal=='D'){
					$nilai=$debet-$kredit;
				}else{
					$nilai=$kredit-$debet;				
				}
				if ($nilai=='') $nilai=0;

				$q = $this->db->query(" update map_neraca set  nilai=$nilai where kode='$no' ");  
				
			}




			$idx=0;			
			$query = $this->db->query(" SELECT * FROM map_neraca  ORDER BY kode ");  
			foreach($query->result_array() as $res){
				$idx++;
				$no=$res['kode'];
				$uraian=$res['uraian'];
				$seq=$res['seq'];
				$parent=$res['parent'];
				$bold=$res['bold'];
				$nl=$res['nilai'];
				$sblm=$res['nilai_lalu'];

				if (trim($parent)=='') $parent='xxx';

				if ($bold=='1'){
				$cRet .='<TR>
							<TD width="70%" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$uraian.'</TD>
							<TD width="15%" align="right" >'.number_format($nl).'</TD>
							<TD width="15%" align="right" >'.number_format($sblm).'</TD>
						 </TR>';
				}elseif($bold=='2'){ 
				$cRet .='<TR>
							<TD width="70%" align="left" >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$uraian.'</TD>
							<TD width="15%" align="right" >'.number_format($nl).'</TD>
							<TD width="15%" align="right" >'.number_format($sblm).'</TD>
						 </TR>';
				}elseif($bold=='3'){ 
				$cRet .='<TR>
							<TD width="70%" align="left" >'.$uraian.'</TD>
							<TD width="15%" align="right" >'.number_format($nl).'</TD>
							<TD width="15%" align="right" >'.number_format($sblm).'</TD>
						 </TR>';

				}elseif($bold=='4'){ 
				$cRet .='<TR>
							<TD width="70%" align="left" >'.$uraian.'</TD>
							<TD width="15%" align="right" >'.number_format($nl).'</TD>
							<TD width="15%" align="right" >'.number_format($sblm).'</TD>
						 </TR>';

				}elseif($bold=='5'){ 
				$cRet .='<TR>
							<TD width="70%" align="left" >'.$uraian.'</TD>
							<TD width="15%" align="right" >'.number_format($nl).'</TD>
							<TD width="15%" align="right" >'.number_format($sblm).'</TD>
						 </TR>';

				}elseif($bold=='6'){ 
				$cRet .='<TR>
							<TD width="70%" align="left" >'.$uraian.'</TD>
							<TD width="15%" align="right" >'.number_format($nl).'</TD>
							<TD width="15%" align="right" >'.number_format($sblm).'</TD>
						 </TR>';

				}elseif($bold==''){ 
				$cRet .='<TR>
							<TD width="70%" align="left" >'.$uraian.'</TD>
							<TD width="15%" align="right" >'.number_format($nl).'</TD>
							<TD width="15%" align="right" >'.number_format($sblm).'</TD>
						 </TR>';

				}
			}

			
			$cRet .='</TABLE>';		 
		    $this->tukd_model->_mpdf('',$cRet,10,5,10,'0');	
	}
    
    function ctk_neraca_saldo()
    {
        $thn_ang = $this->session->userdata('pcThang');
            
           
         $tgl= $_REQUEST['tgl1'];
         
         $tgl_ttd= $_REQUEST['tgl_ttd'];
                  
       
         
         $csql="SELECT a.nama, a.nip FROM ms_ttd a WHERE kode = 'BP' AND a.kd_skpd = '1.20.05.00'";
         $hasil = $this->db->query($csql);
         $trh2 = $hasil->row();          
         $lcNmBP = $trh2->nama;
         $lcNipBP = $trh2->nip;                  

       
            
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
         
            <tr>
                <td align=\"center\" colspan=\"4\" style=\"font-size:14px;border: solid 1px white;\"><b>KABUPATEN MAHARDIKA<br>LAPORAN NERACA SALDO</b></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\">&nbsp;</td>
            </tr>
            <tr>
                <td align=\"center\" colspan=\"4\" style=\"font-size:12px;border: solid 1px white;\"> TANGGAL : ".$this->tanggal_format_indonesia($tgl)."</td>
            </tr>
            
            <tr>
                <td align=\"left\"  style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:12px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
            </tr></table>";
       $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
            <thead> 
            <tr>
                <td align=\"center\" rowspan=\"2\" width=\"10%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\" >Kode Rekening</td>
                <td align=\"center\" rowspan=\"2\" width=\"50%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Nama Rekening</td>
                <td align=\"center\" colspan=\"2\" width=\"40%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Jumlah</td>             
            </tr> 
            <tr>
                
                <td align=\"center\" width=\"20%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Debet</td>
                <td align=\"center\" width=\"20%\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black;\">Kredit</td>             
            </tr> 
            </thead>         
            <tr>
                <td align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">1</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">2</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">3</td>
                <td align=\"center\"  style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">4</td>              
            </tr>";
           
           


                                                       
                
                     $sql = "SELECT * FROM ms_rek5 WHERE LEFT(kd_rek5,1) IN ('1','2','3','4','5','6') ORDER BY kd_rek5";

           
           
                                       
                    $hasil = $this->db->query($sql); 
                    foreach ($hasil->result() as $row)
                    {
                      
                       $kd_rek   =$row->kd_rek5 ;
                       $nama     =$row->nm_rek5;
                       $sn       =$row->sal_n;
                       
                             $sql1 = "SELECT a.kd_rek5,SUM(a.debet)AS debet,SUM(a.kredit) AS kredit FROM trdju_pkd a inner join trhju_pkd b on a.no_voucher=b.no_voucher WHERE a.kd_rek5='$kd_rek' and b.tgl_voucher <='$tgl' GROUP BY a.kd_rek5 ";
                             $hasil1 = $this->db->query($sql1); 
                            foreach ($hasil1->result() as $row1)
                            {
                             $lcdebet      =$row1->debet ;
                             $lckredit     =$row1->kredit;
                                if($sn=='0'  ) {
                                    $nilai=$lcdebet-$lckredit;
                                } else{
                                    $nilai=$lckredit-$lcdebet;
                                }
                                 if($sn=='0'  ) {
                                     $cRet .="<tr>
                                          <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$kd_rek</td>
                                          <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$nama</td>
                                          <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\"> ".number_format($nilai)."</td>
                                          <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">&nbsp;</td>
                                          </tr>";
                                } else{
                                    $cRet .="<tr>
                                          <td valign=\"top\" align=\"center\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$kd_rek</td>
                                          <td valign=\"top\" align=\"left\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\">$nama</td>
                                          <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\"> &nbsp;</td>
                                          <td valign=\"top\" align=\"right\" style=\"font-size:12px;border-bottom:solid 1px black;border-top:solid 1px black\"> ".number_format($nilai)."</td>
                                          </tr>";
                                }  
                               
                            }                     
                     
                    }
                 


  
                                    
       
        
                
        $cRet .='</table>';
                 
         $data['prev']= $cRet;    
         //$this->tukd_model->_mpdf('',$cRet,'10','10',5,'0');
         //$this->tukd_model->_mpdf('', $cRet, 10, 10, 10, 'L');
         $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
    }
    
    function ctk_lpe($cetak = 1)
    {
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1= $thn_ang-1;   
        
      
        
        $sqlsawal = "SELECT *  FROM map_lpe  where nor='7'";
        $queryawal = $this->db->query($sqlsawal);
        $jmlsaldo = $queryawal->row();
        $jmlsal = $jmlsaldo->thn_m1;
        
        $sql41 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,1)='8' ";
        $query41 = $this->db->query($sql41);
        $jmlp = $query41->row();
        $jmlpendapatan = $jmlp->nilai;
        $jmlpendapatan1 = number_format($jmlp->nilai, "2", ".", ",");


        $sql51 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,1)='9' ";
        $query51 = $this->db->query($sql51);
        $jmlb = $query51->row();
        $jmlbelanja = $jmlb->nilai;
        $jmlbelanja1 = number_format($jmlb->nilai, "2", ".", ",");
        $sql523 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,3)='923' ";
        $query523 = $this->db->query($sql523);
        $jmlbm = $query523->row();
        $jmlbmbelanja = $jmlbm->nilai;
        $jmlbmbelanja1 = number_format($jmlbmbelanja, "2", ".", ",");
        $sql61 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,2)='71' ";
        $query61 = $this->db->query($sql61);
        $jmlpm = $query61->row();
        $jmlpmasuk = $jmlpm->nilai;
        $sql62 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,2)='72' ";
        $query62 = $this->db->query($sql62);
        $jmlpk = $query62->row();
        $jmlpkeluar = $jmlpk->nilai;
        $surplus = $jmlpendapatan - $jmlbelanja;

        
        $biaya_net = $jmlpmasuk - $jmlpkeluar;

        
        $silpa = ($jmlpendapatan + $jmlpmasuk) - ($jmlbelanja + $jmlpkeluar);
        if ($silpa < 0)
        {
            $a = "(";
            $silpa1 = $silpa * -1;
            $b = ")";
        } else
        {
            $a = "";
            $silpa1 = $silpa;
            $b = "";
        }
        $sal_akhir=$jmlsal+$silpa;
        
        if ($sal_akhir < 0)
        {
            $c = "(";
            $sal_akhir = $sal_akhir * -1;
            $d = ")";
        } else
        {
            $c = "";
            $d = "";
        }

       
    
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
         
            <tr>
                <td align=\"center\" colspan=\"4\" style=\"font-size:14px;border: solid 1px white;\"><b>KABUPATEN MAHARDIKA<br>LAPORAN PERUBAHAN EKUITAS<br>
                31 DESEMBER $thn_ang_1 DAN $thn_ang  </b></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"4\" style=\"font-size:14px;border: solid 1px white;\">&nbsp;</td>
            </tr>
           
            
            <tr>
                <td align=\"left\"  style=\"font-size:14px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:14px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
            </tr>
      
            <tr>
                <td align=\"center\" width=\"5%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:solid 1px black;\" >NO</td>
                <td align=\"center\" width=\"65%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:solid 1px black;\">URAIAN</td>
                <td align=\"center\" width=\"15%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:solid 1px black;\">$thn_ang_1</td> 
                <td align=\"center\" width=\"15%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:solid 1px black;\">$thn_ang</td>            
            </tr>";
           
           


                                                       
                
                     $sql = "SELECT * FROM map_lpe  ORDER BY seq";

           
           
                                       
                    $hasil = $this->db->query($sql); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row)
                    {
                      
                       $kd_rek   =$row->nor ;
                       $parent   =$row->parent;
                       $nama     =$row->uraian;
                       $nilai_1    =$row->thn_m1;
                       
                               switch ($kd_rek)
                                {
                                    case 1:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> ".number_format($nilai_1)."</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">".number_format($jmlsal)."</td>
                                                     </tr>";
                                            
                                    break; 
                                    case 2:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> ".number_format($nilai_1)."</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$a".number_format($silpa1)."$b</td>
                                                     </tr>";
                                            
                                    break; 
                                     case 3:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> ".number_format($nilai_1)."</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;</td>
                                                     </tr>";
                                            
                                    break; 
                                    case 7:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> ".number_format($nilai_1)."</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$c".number_format($sal_akhir)."$d</td>
                                                     </tr>";
                                            
                                    break; 
                                    default:  
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp; &nbsp; &nbsp; &nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> ".number_format($nilai_1)."</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;</td>
                                                     </tr>";                      
                                } 
                       
                    }
                 


  
           $cRet .="<tr>
                    <td align=\"center\" width=\"5%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:none\" >&nbsp;</td>
                    <td align=\"center\" width=\"65%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:none\">&nbsp;</td>
                    <td align=\"center\" width=\"15%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:none\">&nbsp;</td> 
                    <td align=\"center\" width=\"15%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:none\">&nbsp;</td>            
                    </tr>";                            
           
        
                
        $cRet .='</table>';
                 
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = 'LAPORAN PERUBAHAN EKUITAS';
        $this->template->set('title', 'LAPORAN PERUBAHAN EKUITAS');  
         switch ($cetak)
        {
            case 1;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }

    
    }
    
    function ctk_lpsal($cetak = 1)
    {
        $thn_ang = $this->session->userdata('pcThang');
        $thn_ang_1= $thn_ang-1;   
        
        $sqlsawal = "SELECT *  FROM map_lpsal  where nor='8'";
        $queryawal = $this->db->query($sqlsawal);
        $jmlsaldo = $queryawal->row();
        $jmlsal = $jmlsaldo->thn_m1;
        
        $sql41 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,1)='8' ";
        $query41 = $this->db->query($sql41);
        $jmlp = $query41->row();
        $jmlpendapatan = $jmlp->nilai;
        $jmlpendapatan1 = number_format($jmlp->nilai, "2", ".", ",");


        $sql51 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,1)='9' ";
        $query51 = $this->db->query($sql51);
        $jmlb = $query51->row();
        $jmlbelanja = $jmlb->nilai;
        $jmlbelanja1 = number_format($jmlb->nilai, "2", ".", ",");
        $sql523 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,3)='923' ";
        $query523 = $this->db->query($sql523);
        $jmlbm = $query523->row();
        $jmlbmbelanja = $jmlbm->nilai;
        $jmlbmbelanja1 = number_format($jmlbmbelanja, "2", ".", ",");
        $sql61 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,2)='71' ";
        $query61 = $this->db->query($sql61);
        $jmlpm = $query61->row();
        $jmlpmasuk = $jmlpm->nilai;
        $sql62 = "SELECT SUM(real_spj) as nilai FROM realisasi WHERE left(kd_rek5,2)='72' ";
        $query62 = $this->db->query($sql62);
        $jmlpk = $query62->row();
        $jmlpkeluar = $jmlpk->nilai;
        $surplus = $jmlpendapatan - $jmlbelanja;
        
        $jum1=$jmlsal+$jmlpmasuk;
        
        $biaya_net = $jmlpmasuk - $jmlpkeluar;
        $silpa = ($jmlpendapatan + $jmlpmasuk) - ($jmlbelanja + $jmlpkeluar);
        if ($silpa < 0)
        {
            $a = "(";
            $silpa1 = $silpa * -1;
            $b = ")";
        } else
        {
            $a = "";
            $silpa1 = $silpa;
            $b = "";
        }
        $jum2=$jum1+$silpa;
        if ($jum2 < 0)
        {
            $c = "(";
            $jum2 = $jum2 * -1;
            $d = ")";
        } else
        {
            $c = "";
            $d = "";
        }
        $sal_akhir=$jum1+$silpa;
        if ($sal_akhir < 0)
        {
            $c = "(";
            $sal_akhir = $sal_akhir * -1;
            $d = ")";
        } else
        {
            $c = "";
            $d = "";
        }
                  
    
         $cRet = '';
         $cRet .="<table style=\"border-collapse:collapse;\" width=\"100%\" align=\"center\" border=\"1\" cellspacing=\"1\" cellpadding=\"1\">
         
            <tr>
                <td align=\"center\" colspan=\"4\" style=\"font-size:14px;border: solid 1px white;\"><b>KABUPATEN MAHARDIKA<br>LAPORAN PERUBAHAN SALDO ANGGARAN LEBIH<br>31 DESEMBER $thn_ang_1 DAN $thn_ang</b></td>
            </tr>
              <tr>
                <td align=\"left\" colspan=\"4\" style=\"font-size:14px;border: solid 1px white;\">&nbsp;</td>
            </tr>
            
            
            <tr>
                <td align=\"left\"  style=\"font-size:14px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
                <td align=\"left\" colspan=\"3\" style=\"font-size:14px;border: solid 1px white;border-bottom:solid 2px black;\">&nbsp;</td>
            </tr>
      
            <tr>
                <td align=\"center\" width=\"5%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:solid 1px black;\" >NO</td>
                <td align=\"center\" width=\"65%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:solid 1px black;\">URAIAN</td>
                <td align=\"center\" width=\"15%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:solid 1px black;\">$thn_ang_1</td> 
                <td align=\"center\" width=\"15%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:solid 1px black;\">$thn_ang</td>            
            </tr>";
                                             
                
                    $sql = "SELECT * FROM map_lpsal  ORDER BY seq";
                    $hasil = $this->db->query($sql); 
                    $nawal = 0 ;
                    foreach ($hasil->result() as $row)
                    {
                      
                       $kd_rek   =$row->nor ;
                       $nama     =$row->uraian;
                       $nilai_1    =$row->thn_m1;
                       
                               switch ($kd_rek)
                                {
                                    case 1:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> ".number_format($nilai_1)."</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">".number_format($jmlsal)."</td>
                                                     </tr>";
                                            
                                    break; 
                                    case 2:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> ".number_format($nilai_1)."</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">".number_format($jmlpmasuk)."</td>
                                                     </tr>";
                                            
                                    break; 
                                     case 3:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> ".number_format($nilai_1)."</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">".number_format($jum1)."</td>
                                                     </tr>";
                                            
                                    break;
                                    case 4:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> ".number_format($nilai_1)."</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$a".number_format($silpa1)."$b</td>
                                                     </tr>";
                                            
                                    break; 
                                    case 5:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> ".number_format($nilai_1)."</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$c".number_format($jum2)."$d</td>
                                                     </tr>";
                                            
                                    break;
                                    case 8:
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> ".number_format($nilai_1)."</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">$c".number_format($sal_akhir)."$d</td>
                                                     </tr>";
                                            
                                    break;  
                                    default:  
                                            $cRet .="<tr>
                                                      <td valign=\"top\"  width=\"5%\" align=\"center\" style=\"font-size:14px;border-bottom:none;border-top:none\">$kd_rek</td>
                                                      <td valign=\"top\"  width=\"65%\"  align=\"left\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp; &nbsp; &nbsp; &nbsp;$nama</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\"> ".number_format($nilai_1)."</td>
                                                      <td valign=\"top\"  width=\"15%\" align=\"right\" style=\"font-size:14px;border-bottom:none;border-top:none\">&nbsp;</td>
                                                     </tr>";                      
                                } 
                       
                    }
                 


  
           $cRet .="<tr>
                    <td align=\"center\" width=\"5%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:none\" >&nbsp;</td>
                    <td align=\"center\" width=\"65%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:none\">&nbsp;</td>
                    <td align=\"center\" width=\"15%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:none\">&nbsp;</td> 
                    <td align=\"center\" width=\"15%\" style=\"font-size:14px;border-bottom:solid 1px black;border-top:none\">&nbsp;</td>            
                    </tr>";                            
           
        
                
        $cRet .='</table>';
                 
         $data['prev']= $cRet;  
         $data['sikap'] = 'preview';
         $judul = 'LAPORAN PERUBAHAN SALDO ANGGARAN LEBIH';
        $this->template->set('title', 'LAPORAN PERUBAHAN SALDO ANGGARAN LEBIH');  
         switch ($cetak)
        {
            case 1;
                $this->tukd_model->_mpdf('', $cRet, 10, 5, 10, '0');
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= $judul.xls");

                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
            case 3;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-word");
                header("Content-Disposition: attachment; filename= $judul.doc");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }

    }
    
    function  tanggal_format_indonesia($tgl){
        $tanggal  = explode('-',$tgl); 
        $bulan  = $this-> getBulan($tanggal[1]);
        $tahun  =  $tanggal[0];
        return  $tanggal[2].' '.$bulan.' '.$tahun;

        }
         
        function  tanggal_indonesia($tgl){
        $tanggal  =  substr($tgl,8,2);
        $bulan  = substr($tgl,5,2);
        $tahun  =  substr($tgl,0,4);
        return  $tanggal.'-'.$bulan.'-'.$tahun;

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
        return  "Maret";
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

	function proses_transfer(){
        $user     = $this->session->userdata('pcNama');
		$skpd     = $this->session->userdata('kdskpd');
        $dir = $this->input->post('dir');
		if ($dir==1){
 		$this->db->query("exec transfer_skpd '$skpd'");
		} else if ($dir==2){
 		$this->db->query("exec transfer_program '$skpd'");
		} else if ($dir==3){
 		$this->db->query("exec transfer_kegiatan '$skpd'");
		} else if ($dir==4){
 		$this->db->query("exec transfer_kegiatan_terpilih '$skpd'");
		} else if ($dir==5){
 		$this->db->query("exec transfer_rekening '$skpd'");
		}
		
 		//$this->db->query("exec [backup]");
		echo '1';	
	}

}
