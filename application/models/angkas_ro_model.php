<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */

class angkas_ro_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function load_giat($skpd, $lccr)
    {

        /*untuk kunci gaji*/
        $tipe = $this->session->userdata('type');
        if ($tipe == 1) {
            $status = "";
        } else {
            $status = "status_sub_kegiatan='1' ";
        }
        //$sort= substr($skpd,0,4)=='1.02' || substr($skpd,0,4)=='7.01' ? "kd_skpd='$skpd'" : "left(kd_skpd,17)=left('$skpd',17)";
        $sql = "SELECT a.kd_skpd,a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_program,a.nm_program,
                (SELECT SUM(nilai)  FROM trdrka WHERE kd_sub_kegiatan=a.kd_sub_kegiatan )AS total,
                (SELECT SUM(nilai_sempurna)  FROM trdrka WHERE kd_sub_kegiatan=a.kd_sub_kegiatan )AS total_sempurna,
                (SELECT SUM(nilai_ubah)  FROM trdrka WHERE kd_sub_kegiatan=a.kd_sub_kegiatan )AS total_ubah FROM trskpd a  
                WHERE  $status and kd_skpd='$skpd' and(UPPER(kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(nm_sub_kegiatan) LIKE UPPER('%$lccr%')) order by a.kd_sub_kegiatan";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'kd_kegiatan' => $resulte['kd_sub_kegiatan'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_program' => $resulte['kd_program'],
                'nm_program' => $resulte['nm_program'],
                'total'       => number_format($resulte['total'], "2", ".", ","),
                'total_sempurna'       => number_format($resulte['total_sempurna'], "2", ".", ","),
                'total_ubah'  => number_format($resulte['total_ubah'], "2", ".", ",")
            );
            $ii++;
        }
        return $result;
    }

    // function load_giat_sempurna($skpd,$lccr) { 

    //     /*untuk kunci gaji*/
    //     $tipe = $this->session->userdata('type');
    //     if($tipe==1){
    //         $status="";
    //     }else{
    //         $status="status_sub_kegiatan='1' ";
    //     }
    //     //$sort= substr($skpd,0,4)=='1.02' || substr($skpd,0,4)=='7.01' ? "kd_skpd='$skpd'" : "left(kd_skpd,17)=left('$skpd',17)";
    //     $sql = "SELECT a.kd_skpd,a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_program,a.nm_program,
    //             (SELECT SUM(nilai)  FROM trdrka WHERE kd_sub_kegiatan=a.kd_sub_kegiatan )AS total,
    //             (SELECT SUM(nilai_sempurna)  FROM trdrka WHERE kd_sub_kegiatan=a.kd_sub_kegiatan )AS total_sempurna,
    //             (SELECT SUM(nilai_ubah)  FROM trdrka WHERE kd_sub_kegiatan=a.kd_sub_kegiatan )AS total_ubah FROM trskpd a  
    //             WHERE  $status and kd_skpd='$skpd' and(UPPER(kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(nm_sub_kegiatan) LIKE UPPER('%$lccr%')) order by a.kd_sub_kegiatan";                                              
    //     $query1 = $this->db->query($sql);  
    //     $result = array();
    //     $ii = 0;        
    //     foreach($query1->result_array() as $resulte) {     
    //         $result[] = array(
    //                     'id' => $ii,        
    //                     'kd_kegiatan' => $resulte['kd_sub_kegiatan'],  
    //                     'kd_skpd' => $resulte['kd_skpd'],
    //                     'nm_kegiatan' => $resulte['nm_sub_kegiatan'],
    //                     'kd_program' => $resulte['kd_program'],  
    //                     'nm_program' => $resulte['nm_program'],
    //                     'total'       => number_format($resulte['total'],"2",".",","),
    //                     'total_sempurna'       => number_format($resulte['total_sempurna'],"2",".",","),
    //                     'total_ubah'  => number_format($resulte['total_ubah'],"2",".",",")   
    //                     );
    //                     $ii++;
    //     }            
    //     return $result;

    // }

    function load_giat_sempurna($skpd, $lccr, $jns_ang)
    {

        /*untuk kunci gaji*/
        $tipe = $this->session->userdata('type');
        if ($tipe == 1) {
            $status = "";
        } else {
            $status = "status_sub_kegiatan='1' ";
        }
        $kondisi = "AND ( SELECT SUM (nilai) FROM trdrka WHERE kd_sub_kegiatan = a.kd_sub_kegiatan ) != ( SELECT SUM ( nilai_sempurna ) FROM trdrka WHERE kd_sub_kegiatan = a.kd_sub_kegiatan )";
        //$sort= substr($skpd,0,4)=='1.02' || substr($skpd,0,4)=='7.01' ? "kd_skpd='$skpd'" : "left(kd_skpd,17)=left('$skpd',17)";
        $sql = "SELECT a.kd_skpd,a.kd_sub_kegiatan,a.nm_sub_kegiatan,a.kd_program,a.nm_program,
                (SELECT SUM(nilai)  FROM trdrka WHERE kd_sub_kegiatan=a.kd_sub_kegiatan AND kd_skpd = a.kd_skpd AND jns_ang='$jns_ang')AS total FROM trskpd a  
                WHERE kd_skpd='$skpd' and(UPPER(kd_sub_kegiatan) LIKE UPPER('%$lccr%') OR UPPER(nm_sub_kegiatan) LIKE UPPER('%$lccr%')) order by a.kd_sub_kegiatan";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'kd_kegiatan' => $resulte['kd_sub_kegiatan'],
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_kegiatan' => $resulte['nm_sub_kegiatan'],
                'kd_program' => $resulte['kd_program'],
                'nm_program' => $resulte['nm_program'],
                'total'       => number_format($resulte['total'], "2", ".", ",")
            );
            $ii++;
        }
        return $result;
    }

    function total_triwulan($status, $kd_kegiatan, $skpd)
    { /*nilai angkas per triwulan*/

        $sqlx = "SELECT kd_sub_kegiatan, sum(tw1) tw1, sum(tw2) tw2, sum(tw3) tw3, sum(tw4) tw4 from (
                select kd_sub_kegiatan,
                case when bulan BETWEEN 1 and 3 THEN sum(nilai) end as tw1,
                case when bulan BETWEEN 4 and 6 THEN sum(nilai) end as tw2,
                case when bulan BETWEEN 7 and 9 THEN sum(nilai) end as tw3,
                case when bulan BETWEEN 10 and 12 THEN sum(nilai) end as tw4
                from (
                select kd_skpd, kd_sub_kegiatan, bulan, sum($status) nilai from trdskpd_ro 
                GROUP BY kd_skpd,kd_sub_kegiatan, bulan)xx 
                WHERE kd_sub_kegiatan='$kd_kegiatan'
                GROUP BY kd_sub_kegiatan, bulan) yy GROUP BY kd_sub_kegiatan";
        $sql = $this->db->query($sqlx);

        $result = array();
        $ii = 0;
        foreach ($sql->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'kegiatan_kd' => $resulte['kd_sub_kegiatan'],
                'tw1' => number_format($resulte['tw1'], 2, '.', ','),
                'tw2' => number_format($resulte['tw2'], 2, '.', ','),
                'tw3' => number_format($resulte['tw3'], 2, '.', ','),
                'tw4' => number_format($resulte['tw4'], 2, '.', ',')
            );
            $ii++;
        }

        return $result;
    }

    function load_trdskpd($kegiatan, $rekening, $status, $skpd)
    {
        // $sort= substr($skpd,0,4)=='1.02' || substr($skpd,0,4)=='7.01' ? "kd_skpd='$skpd'" : "left(kd_skpd,17)=left('$skpd',17)";
        $sql = "SELECT bulan, $status nilai from trdskpd_ro where kd_sub_kegiatan='$kegiatan' and kd_rek6='$rekening' and kd_skpd='$skpd' order by bulan";
        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 1;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'bulan' => $resulte['bulan'],
                'nilai' => number_format($resulte['nilai'], 2, '.', ',')
            );
            $ii++;
        }
        return $result;
    }

    //     function ambil_rek_angkas_ro($kegiatan,$skpd) {
    //         // $sort= substr($skpd,0,4)=='1.02' || substr($skpd,0,4)=='7.01' ? "kd_skpd='$skpd'" : "left(kd_skpd,17)=left('$skpd',17)";
    //         $sql = "SELECT kd_rek6,nm_rek6,
    // nilai,isnull(n_ro,0) as n_ro, (nilai-isnull(n_ro,0))as s_n_ro,
    // nilai_sempurna,isnull(ns_ro,0)as ns_ro,(nilai_sempurna-isnull(ns_ro,0)) as s_ns_ro,
    // nilai_ubah,isnull(nu_ro,0) as nu_ro,(nilai_ubah-isnull(nu_ro,0))s_nu_ro
    //  from (
    // select kd_rek6,nm_rek6,sum(z.nilai)as nilai,
    // (select sum(nilai_susun) from trdskpd_ro where kd_sub_kegiatan=z.kd_sub_kegiatan and kd_rek6=z.kd_rek6 and kd_skpd=z.kd_skpd) as n_ro,
    // sum(z.nilai_sempurna)nilai_sempurna,
    // (select sum(nilai_sempurna)  from trdskpd_ro where kd_sub_kegiatan=z.kd_sub_kegiatan and kd_rek6=z.kd_rek6 and kd_skpd=z.kd_skpd) as ns_ro,
    // sum(z.nilai_ubah) nilai_ubah,
    // (select sum(nilai_ubah)  from trdskpd_ro where kd_sub_kegiatan=z.kd_sub_kegiatan and kd_rek6=z.kd_rek6 and kd_skpd=z.kd_skpd) as nu_ro
    // from trdrka z where 
    // kd_sub_kegiatan='$kegiatan' and kd_skpd='$skpd'
    // group by z.kd_skpd,z.kd_sub_kegiatan,kd_rek6,nm_rek6
    // )xx";                   

    //         $query1 = $this->db->query($sql);  
    //         $result = array();
    //         $ii = 0;
    //         foreach($query1->result_array() as $resulte) {
    //             $result[] = array(
    //                         'id' => $ii,        
    //                         'kd_rek5' => $resulte['kd_rek6'],  
    //                         'nm_rek5' => $resulte['nm_rek6'],  
    //                         'nilai' => number_format($resulte['nilai'],"2",".",","),                    /*nilai trdrka*/
    //                         'nilai_sempurna' => number_format($resulte['nilai_sempurna'],"2",".",","),  /*nilai geser trdrka*/
    //                         'nilai_ubah' => number_format($resulte['nilai_ubah'],"2",".",","),          /*nilai ubah trdrka*/                       
    //                         'n_ro' => number_format($resulte['n_ro'],"2",".",","),       /*nilai angkas*/ 
    //                         'ns_ro' => number_format($resulte['ns_ro'],"2",".",","),     /*nilai geser angkas*/
    //                         'nu_ro' => number_format($resulte['nu_ro'],"2",".",","),     /*nilai ubah angkas*/
    //                         's_n_ro' => number_format($resulte['s_n_ro'],"2",".",","),   /*selisih nilai murni*/
    //                         's_ns_ro' => number_format($resulte['s_ns_ro'],"2",".",","), /*selisih nilai geser*/
    //                         's_nu_ro' => number_format($resulte['s_nu_ro'],"2",".",",")  /*selisih nilai ubah*/                              

    //                         );
    //                         $ii++;
    //         }  
    //         return $result;

    //     }

    function ambil_rek_angkas_ro($kegiatan, $skpd)
    {
        // $sort= substr($skpd,0,4)=='1.02' || substr($skpd,0,4)=='7.01' ? "kd_skpd='$skpd'" : "left(kd_skpd,17)=left('$skpd',17)";
        $sql = "SELECT kd_rek6,nm_rek6,
nilai,n_ro, (nilai - n_ro)as s_n_ro,
nilai_sempurna,ns_ro,(nilai - ns_ro) as s_ns_ro,
nilai_ubah,nu_ro,(nilai_ubah - nu_ro)s_nu_ro
 from (
select kd_rek6,nm_rek6,sum(z.nilai)as nilai,
(select sum(nilai) from trdskpd_ro where kd_sub_kegiatan=z.kd_sub_kegiatan and kd_rek6=z.kd_rek6 and kd_skpd=z.kd_skpd) as n_ro,
sum(z.nilai_sempurna)nilai_sempurna,
(select sum(nilai_sempurna)  from trdskpd_ro where kd_sub_kegiatan=z.kd_sub_kegiatan and kd_rek6=z.kd_rek6 and kd_skpd=z.kd_skpd) as ns_ro,
sum(z.nilai_ubah) nilai_ubah,
(select sum(nilai_ubah)  from trdskpd_ro where kd_sub_kegiatan=z.kd_sub_kegiatan and kd_rek6=z.kd_rek6 and kd_skpd=z.kd_skpd) as nu_ro
from trdrka z where 
kd_sub_kegiatan='$kegiatan' and kd_skpd='$skpd'
group by z.kd_skpd,z.kd_sub_kegiatan,kd_rek6,nm_rek6
)xx";

        $query1 = $this->db->query($sql);
        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {

            $sempurna = $resulte['nilai_sempurna'];
            $angkas = $resulte['n_ro'];

            if ($sempurna == $angkas) {
                $sts = '0';
            } else {
                $sts = '1';
            }

            $result[] = array(
                'id' => $ii,
                'kd_rek5' => $resulte['kd_rek6'],
                'nm_rek5' => $resulte['nm_rek6'],
                'nilai' => number_format($resulte['nilai'], "2", ".", ","),                    /*nilai trdrka*/
                'nilai_sempurna' => number_format($resulte['nilai_sempurna'], "2", ".", ","),  /*nilai geser trdrka*/
                'nilai_ubah' => number_format($resulte['nilai_ubah'], "2", ".", ","),          /*nilai ubah trdrka*/
                'n_ro' => number_format($resulte['n_ro'], "2", ".", ","),       /*nilai angkas*/
                'ns_ro' => number_format($resulte['ns_ro'], "2", ".", ","),     /*nilai geser angkas*/
                'nu_ro' => number_format($resulte['nu_ro'], "2", ".", ","),     /*nilai ubah angkas*/
                's_n_ro' => number_format($resulte['s_n_ro'], "2", ".", ","),   /*selisih nilai murni*/
                's_ns_ro' => number_format($angkas), /*selisih nilai geser*/
                's_nu_ro' => number_format($resulte['s_nu_ro'], "2", ".", ","),  /*selisih nilai ubah*/
                'status' => $sts
            );
            $ii++;
        }
        return $result;
    }

    function simpan_trskpd_ro($cskpda, $status, $cskpd, $cskpd, $cgiat, $crek5, $bln1, $bln2, $bln3, $bln4, $bln5, $bln6, $bln7, $bln8, $bln9, $bln10, $bln11, $bln12, $tr1, $tr2, $tr3, $tr4, $status, $user_name)
    {
        $id  = $this->session->userdata('pcUser');
        $tabell = 'trdskpd_ro';
        $sort = substr($cskpda, 0, 4) == '1.02' || substr($cskpda, 0, 4) == '7.01' ? "kd_skpd='$cskpda'" : "left(kd_skpd,17)=left('$cskpda',17)";
        $query_find = $this->db->query("SELECT * from $tabell where kd_sub_kegiatan='$cgiat' and $sort and kd_rek6='$crek5'");
        $update = $query_find->num_rows();

        if ($update > 0) {
            $kdGab = $cskpda . '.' . $cgiat;

            for ($x = 1; $x <= 12; $x++) {
                $bulan = "bln$x";
                switch ($status) {
                    case 'murni':
                        $sql1 = "UPDATE $tabell set nilai='{$$bulan}',nilai_susun='{$$bulan}',nilai_sempurna='{$$bulan}',nilai_ubah={$$bulan} where kd_gabungan='$kdGab' and kd_rek6='$crek5' and bulan=$x";
                        break;
                    case 'geser':
                        $sql1 = "UPDATE $tabell set nilai_sempurna='{$$bulan}',nilai_ubah={$$bulan} where kd_gabungan='$kdGab' and kd_rek6='$crek5' and bulan=$x";
                        break;
                    case 'ubah':
                        $sql1 = "UPDATE $tabell set nilai_ubah={$$bulan} where kd_gabungan='$kdGab' and kd_rek6='$crek5' and bulan=$x";
                        break;
                }
                $asg = $this->db->query($sql1);
            }

            $sqltrskpd = " UPDATE trskpd set triw1=
                                (select sum(nilai) from trdskpd_ro where bulan in ('1','2','3') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw1_sempurna=
                                (select sum(nilai_sempurna) from trdskpd_ro where bulan in ('1','2','3') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw1_ubah=
                                (select sum(nilai_ubah) from trdskpd_ro where bulan in ('1','2','3') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw2=
                                (select sum(nilai) from trdskpd_ro where bulan in ('4','5','6') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw2_sempurna=
                                (select sum(nilai_sempurna) from trdskpd_ro where bulan in ('4','5','6') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw2_ubah=
                                (select sum(nilai_ubah) from trdskpd_ro where bulan in ('4','5','6') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw3=
                                (select sum(nilai) from trdskpd_ro where bulan in ('7','8','9') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw3_sempurna=
                                (select sum(nilai_sempurna) from trdskpd_ro where bulan in ('7','8','9') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw3_ubah=
                                (select sum(nilai_ubah) from trdskpd_ro where bulan in ('7','8','9') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw4=
                                (select sum(nilai_ubah) from trdskpd_ro where bulan in ('10','11','12') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw4_sempurna=
                                (select sum(nilai_sempurna) from trdskpd_ro where bulan in ('10','11','12') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw4_ubah=
                                (select sum(nilai_ubah) from trdskpd_ro where bulan in ('10','11','12') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                total_ubah=(select sum(nilai_ubah) from trdskpd_ro where kd_gabungan ='$kdGab' group by kd_gabungan),last_update=getdate()
                                where kd_gabungan ='$kdGab'  ";
            $asg = $this->db->query($sqltrskpd);

            return '1';
        } else {
            $sql = "delete from $tabell where kd_sub_kegiatan='$cgiat' and $sort and kd_rek6='$crek5'";
            $asg = $this->db->query($sql);
            if ($asg) {
                $kdGab = $cskpd . '.' . $cgiat;

                for ($x = 1; $x <= 12; $x++) {
                    $bulan = "bln$x";
                    switch ($status) {
                        case 'murni':
                            $sql1 = "INSERT into $tabell values('$kdGab','$cskpd','$cgiat','$crek5','$x','{$$bulan}','{$$bulan}','{$$bulan}','{$$bulan}','{$$bulan}','{$$bulan}','{$$bulan}','{$$bulan}','{$$bulan}','{$$bulan}','1') ";                                             # code...
                            break;

                        case 'geser':
                            $sql1 = "INSERT into $tabell values('$kdGab','$cgiat','$cskpd','$crek5','$x','0','0','{$$bulan}','{$$bulan}','1','$cgiat','','') ";                                             # code...
                            break;

                        case 'ubah':
                            $sql1 = "INSERT into $tabell values('$kdGab','$cgiat','$cskpd','$crek5','$x','0','0','0','{$$bulan}','1','$cgiat','','') ";                                             # code...
                            break;
                    }
                    $asg = $this->db->query($sql1);
                }


                $sqltrskpd = " UPDATE trskpd set triw1=
                                (select sum(nilai) from trdskpd_ro where bulan in ('1','2','3') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw1_sempurna=
                                (select sum(nilai_sempurna) from trdskpd_ro where bulan in ('1','2','3') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw1_ubah=
                                (select sum(nilai_ubah) from trdskpd_ro where bulan in ('1','2','3') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw2=
                                (select sum(nilai) from trdskpd_ro where bulan in ('4','5','6') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw2_sempurna=
                                (select sum(nilai_sempurna) from trdskpd_ro where bulan in ('4','5','6') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw2_ubah=
                                (select sum(nilai_ubah) from trdskpd_ro where bulan in ('4','5','6') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw3=
                                (select sum(nilai) from trdskpd_ro where bulan in ('7','8','9') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw3_sempurna=
                                (select sum(nilai_sempurna) from trdskpd_ro where bulan in ('7','8','9') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw3_ubah=
                                (select sum(nilai_ubah) from trdskpd_ro where bulan in ('7','8','9') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw4=
                                (select sum(nilai_ubah) from trdskpd_ro where bulan in ('10','11','12') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw4_sempurna=
                                (select sum(nilai_sempurna) from trdskpd_ro where bulan in ('10','11','12') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                triw4_ubah=
                                (select sum(nilai_ubah) from trdskpd_ro where bulan in ('10','11','12') and kd_gabungan ='$kdGab' group by kd_gabungan),
                                total_ubah=(select sum(nilai_ubah) from trdskpd_ro where kd_gabungan ='$kdGab' group by kd_gabungan), last_update=getdate()
                                where kd_gabungan ='$kdGab' ";
                $asg = $this->db->query($sqltrskpd);

                return '1';
            } else {
                return '0';
                exit();
            }
        }
    }

    function realisasi_angkas_ro($skpd, $kegiatan, $rek5)
    {

        $sql = "SELECT SUM(CASE WHEN awal >='1' and akhir <= '3' then nilai ELSE 0 END) as triw1,
                    SUM(CASE WHEN awal >='4' and akhir <= '6' then nilai ELSE 0 END) as triw2,
                    SUM(CASE WHEN awal >='7' and akhir <= '9' then nilai ELSE 0 END) as triw3,
                    SUM(CASE WHEN awal >='10' and akhir <= '12' then nilai ELSE 0 END) as triw4
                     FROM 
                    (
                    select '1' as awal, '3' as akhir,left(b.kd_skpd,7)+'.00' as kd_skpd,b.kd_rek6,a.kd_sub_kegiatan,sum(b.nilai) as nilai from trdrka a 
                    left join trdtransout b on b.kd_skpd = a.kd_skpd and b.kd_rek6 = a.kd_rek6 and b.kd_sub_kegiatan = a.kd_sub_kegiatan
                    left join trhsp2d c on c.no_sp2d = b.no_sp2d
                    left join trhtransout d on d.kd_skpd = b.kd_skpd and b.no_bukti = d.no_bukti
                    where month(d.tgl_bukti) in ('1','2','3') and b.kd_sub_kegiatan ='$kegiatan' and left(a.kd_skpd,7) =left('$skpd',7) and b.kd_rek6='$rek5'
                    group by left(b.kd_skpd,7),b.kd_rek6,a.kd_sub_kegiatan
                    union
                    select '4' as awal, '6' as akhir,left(b.kd_skpd,7)+'.00' as kd_skpd,b.kd_rek6,a.kd_sub_kegiatan,sum(b.nilai) as nilai from trdrka a 
                    left join trdtransout b on b.kd_skpd = a.kd_skpd and b.kd_rek6 = a.kd_rek6 and b.kd_sub_kegiatan = a.kd_sub_kegiatan
                    left join trhsp2d c on c.no_sp2d = b.no_sp2d
                    left join trhtransout d on d.kd_skpd = b.kd_skpd and b.no_bukti = d.no_bukti
                    where month(d.tgl_bukti) in ('4','5','6') and b.kd_sub_kegiatan ='$kegiatan' and left(a.kd_skpd,7) =left('$skpd',7) and b.kd_rek6='$rek5'
                    group by left(b.kd_skpd,7),b.kd_rek6,a.kd_sub_kegiatan
                    union
                    select '7' as awal, '9' as akhir,left(b.kd_skpd,7)+'.00' as kd_skpd,b.kd_rek6,a.kd_sub_kegiatan,sum(b.nilai) as nilai from trdrka a 
                    left join trdtransout b on b.kd_skpd = a.kd_skpd and b.kd_rek6 = a.kd_rek6 and b.kd_sub_kegiatan = a.kd_sub_kegiatan
                    left join trhsp2d c on c.no_sp2d = b.no_sp2d
                    left join trhtransout d on d.kd_skpd = b.kd_skpd and b.no_bukti = d.no_bukti
                    where month(d.tgl_bukti) in ('7','8','9') and b.kd_sub_kegiatan ='$kegiatan' and left(a.kd_skpd,7) =left('$skpd',7) and b.kd_rek6='$rek5'
                    group by left(b.kd_skpd,7),b.kd_rek6,a.kd_sub_kegiatan
                    union
                    select '10' as awal, '12' as akhir,left(b.kd_skpd,7)+'.00' as kd_skpd,b.kd_rek6,a.kd_sub_kegiatan,sum(b.nilai) as nilai from trdrka a 
                    left join trdtransout b on b.kd_skpd = a.kd_skpd and b.kd_rek6 = a.kd_rek6 and b.kd_sub_kegiatan = a.kd_sub_kegiatan
                    left join trhsp2d c on c.no_sp2d = b.no_sp2d
                    left join trhtransout d on d.kd_skpd = b.kd_skpd and b.no_bukti = d.no_bukti
                    where month(d.tgl_bukti) in ('10','11','12') and b.kd_sub_kegiatan ='$kegiatan' and left(a.kd_skpd,7) =left('$skpd',7) and b.kd_rek6='$rek5'
                    group by left(b.kd_skpd,7),b.kd_rek6,a.kd_sub_kegiatan
                    )a";
        $query1 = $this->db->query($sql);

        $result = array();
        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result[] = array(
                'id' => $ii,
                'triw1' => number_format($resulte['triw1'], 2, '.', ','),
                'triw2' => number_format($resulte['triw2'], 2, '.', ','),
                'triw3' => number_format($resulte['triw3'], 2, '.', ','),
                'triw4' => number_format($resulte['triw4'], 2, '.', ',')
            );
            $ii++;
        }

        return json_encode($result);
    }


    //HKM
    function cetak_angkas_ro($tgl, $ttd1, $ttd2, $tj_ang, $tj_angkas, $skpd, $giat, $hit, $cret)
    {

        $data = $this->cek_anggaran_model->cek_anggaran($skpd);

        if ($tj_angkas == 'murni') {
            $nilai = 'nilai';
        } elseif ($tj_angkas == 'susun') {
            $nilai = 'nilai';
        } elseif ($tj_angkas == 'sempurna' || $tj_angkas == 'sempurna11' || $tj_angkas = 'sempurna12' || $tj_angkas == 'sempurna13') {
            $nilai = 'nilai_sempurna';
        } elseif ($tj_angkas == 'sempurna1' || $tj_angkas == 'sempurna21' || $tj_angkas = 'sempurna22' || $tj_angkas == 'sempurna23') {
        } elseif ($tj_angkas == 'sempurna2' || $tj_angkas == 'sempurna31' || $tj_angkas = 'sempurna32' || $tj_angkas == 'sempurna33') {
        } elseif ($tj_angkas == 'ubah' || $tj_angkas == 'ubah1' || $tj_angkas == 'ubah2') {
            $nilai = 'nilai_ubah';
        }

        if ($tj_angkas == 'murni') {
            $nama = 'PENETAPAN';
        } elseif ($tj_angkas == 'susun') {
            $nama = 'PENETAPAN';
        } elseif ($tj_angkas == 'sempurna' || $tj_angkas == 'sempurna' || $tj_angkas == 'sempurna11' || $tj_angkas = 'sempurna12' || $tj_angkas == 'sempurna13') {
            $nama = 'PENYEMPURNAAN';
        } else if ($tj_angkas == 'sempurna1' || $tj_angkas == 'sempurna21' || $tj_angkas = 'sempurna22' || $tj_angkas == 'sempurna23') {
            $nama = 'PENYEMPURNAAN I';
        } else if ($tj_angkas == 'sempurna2' || $tj_angkas == 'sempurna31' || $tj_angkas = 'sempurna32' || $tj_angkas == 'sempurna33') {
            $nama = 'PENYEMPURNAAN II';
        } elseif ($tj_angkas == 'ubah' || $tj_angkas == 'ubah1' || $tj_angkas == 'ubah2') {
            $nama = 'PERUBAHAN';
        }


        $thn = $this->session->userdata('pcThang');
        $sql = $this->db->query("SELECT nm_skpd from ms_skpd WHERE kd_skpd='$skpd'")->row();

        $sqlheader1 = "SELECT 
        left(kd_skpd,1)as urusan,
        (select nm_urusan from ms_urusan where kd_urusan=left(z.kd_skpd,1))as nmurusan,
        left(kd_skpd,4)as bidang,
        (select nm_bidang_urusan from ms_bidang_urusan where kd_bidang_urusan=left(z.kd_skpd,4))as nmbidang,
        left(kd_skpd,17)as org,
        (select nm_skpd from ms_skpd where left(kd_skpd,17)=left(z.kd_skpd,17) and right(kd_skpd,4)='0000')as nmorg,
        kd_skpd as unit,
        nm_skpd as nmunit
         from ms_skpd z where kd_skpd='$skpd' ";
        $exesqlheader1 = $this->db->query($sqlheader1);
        foreach ($exesqlheader1->result() as $a) {
            $urusan   = $a->urusan;
            $nmurusan   = $a->nmurusan;
            $bidang   = $a->bidang;
            $nmbidang   = $a->nmbidang;
            $org      = $a->org . '.0000';
            $nmorg      = $a->nmorg;
            $unit     = $a->unit;
            $nmunit     = $a->nmunit;
        }

        $sqlheader2 = "SELECT 
        kd_skpd,
        left(z.kd_sub_kegiatan,7)as program,
        (select nm_program from ms_program where kd_program=left(z.kd_sub_kegiatan,7))as nmprogram,
        left(z.kd_sub_kegiatan,12)as kegiatan,
        (select nm_kegiatan from ms_kegiatan where kd_kegiatan=left(z.kd_sub_kegiatan,12))as nmkegiatan,
        kd_sub_kegiatan as subkegiatan,
        nm_sub_kegiatan as nmsubkegiatan,
        sum(nilai) as angg
        from trdrka z where kd_skpd='$skpd' and kd_sub_kegiatan='$giat' and jns_ang='$data'
        group by kd_skpd,kd_sub_kegiatan,nm_sub_kegiatan";
        $exesqlheader2 = $this->db->query($sqlheader2);
        foreach ($exesqlheader2->result() as $b) {
            $program   = $b->program;
            $nmprogram   = strtoupper($b->nmprogram);
            $kegiatan   = $b->kegiatan;
            $nmkegiatan   = strtoupper($b->nmkegiatan);
            $subkegiatan      = $b->subkegiatan;
            $nmsubkegiatan      = strtoupper($b->nmsubkegiatan);
            $angg            = number_format($b->angg, '2', ',', '.');
            $anggs            = $b->angg;
        }

        $cetak = "<table border='0', width='100%' style='font-size: 14px'>
                    <tr style='padding:10px'>
                        <td colspan='3' align='center'><b><BR> RENCANA ANGGARAN KAS<br> SATUAN KERJA PERANGKAT DAERAH <br></td>
                    </tr>
                    <tr style='padding:10px'>
                        <td colspan='3' style='font-size: 12px' align='center'>Pemerintah Kabupaten Melawi Tahun Anggaran $thn</td>
                    </tr>
                </table><br>";
        $cetak .= "<table border='0', width='100%' style='font-size: 10px'>
                    <tr style='padding:10px'>
                        <td align='left'>Urusan</td>
                        <td align='left'>: $urusan</td>
                        <td align='left'>$nmurusan</td>
                    </tr>
                    <tr style='padding:10px'>
                        <td align='left'>Bidang</td>
                        <td align='left'>: $bidang</td>
                        <td align='left'>$nmbidang</td>
                    </tr>
                    <tr style='padding:10px'>
                        <td align='left'>Unit Organisasi</td>
                        <td align='left'>: $org</td>
                        <td align='left'>" . strtoupper($nmorg) . "</td>
                    </tr>
                    <tr style='padding:10px'>
                        <td align='left'>Sub Unit Organisasi</td>
                        <td align='left'>: $unit</td>
                        <td align='left'>" . strtoupper($nmunit) . "</td>
                    </tr>
                    <tr style='padding:10px'>
                        <td align='left'>Program</td>
                        <td align='left'>: $program</td>
                        <td align='left'>$nmprogram</td>
                    </tr>
                    <tr style='padding:10px'>
                        <td align='left'>Kegiatan</td>
                        <td align='left'>: $kegiatan</td>
                        <td align='left'>$nmkegiatan</td>
                    </tr>
                    <tr style='padding:10px'>
                        <td align='left'>Sub Kegiatan</td>
                        <td align='left'>: $subkegiatan</td>
                        <td align='left'>$nmsubkegiatan</td>
                    </tr>
                    <tr style='padding:10px'>
                        <td align='left'>Nilai Anggaran</td>
                        <td align='left'>: $angg</td>
                        <td align='left'>" . strtoupper($this->tukd_model->terbilang($anggs)) . "</td>
                    </tr>

                </table>";


        $cetak .= "<table style='border-collapse:collapse;font-size:12px' width='100%' align='center' border='1' cellspacing='0' cellpadding='7'>
                    <thead>
                    <tr>
                        <td width='15%' align='center'><b>Rekening</td>
                        <td width='8%' align='center'>Jumlah Anggaran</td>
                        <td width='8%' align='center'><b>Jan</td>
                        <td width='8%' align='center'><b>Feb</td>
                        <td width='8%' align='center'><b>Mar</td>
                        <td width='8%' align='center'><b>Triwulan I</td>
                        <td width='8%' align='center'><b>Apr</td>
                        <td width='8%' align='center'><b>Mei</td>
                        <td width='8%' align='center'><b>Jun</td>
                        <td width='8%' align='center'><b>Triwulan II</td>
                        <td width='8%' align='center'><b>Jul</td>
                        <td width='8%' align='center'><b>Ags</td>
                        <td width='8%' align='center'><b>Sep</td>
                        <td width='8%' align='center'><b>Triwulan III</td>
                        <td width='8%' align='center'><b>Okt</td>
                        <td width='8%' align='center'><b>Nov</td>
                        <td width='8%' align='center'><b>Des</td>
                        <td width='8%' align='center'><b>Triwulan IV</td>
                    </tr>
                    </thead>";
        // $sql9 = "SELECT kolom from tb_status_anggaran WHERE kode='$tj_ang'";
        //     $exe9 = $this->db->query($sql9);
        //     foreach ($exe9->result() as $a9) {
        //         $kolom    = $a9->kolom;
        //     }

        // $jenis='nilai_'.$tj_angkas;
        $sql = "
            SELECT kd_sub_kegiatan+'.' giat, kd_rek6, (select nm_rek6 from ms_rek6 WHERE kd_rek6=xxx.kd_rek6) nm_rek, 
            sum(jan) jan, sum(feb) feb, sum(mar) mar, sum(apr) apr, sum(mei) mei, sum(jun) jun,
            sum(jul) jul, sum(ags) ags, sum(sep) sep, sum(okt) okt, sum(nov) nov, sum(des) des
            from (
            select kd_sub_kegiatan, kd_rek6,
            case when bulan=1 then sum($nilai) else 0 end as jan,
            case when bulan=2 then sum($nilai) else 0 end as feb,
            case when bulan=3 then sum($nilai) else 0 end as mar,
            case when bulan=4 then sum($nilai) else 0 end as apr,
            case when bulan=5 then sum($nilai) else 0 end as mei,
            case when bulan=6 then sum($nilai) else 0 end as jun,
            case when bulan=7 then sum($nilai) else 0 end as jul,
            case when bulan=8 then sum($nilai) else 0 end as ags,
            case when bulan=9 then sum($nilai) else 0 end as sep,
            case when bulan=10 then sum($nilai) else 0 end as okt,
            case when bulan=11 then sum($nilai) else 0 end as nov,
            case when bulan=12 then sum($nilai) else 0 end as des from trdskpd_ro a inner join 
            (select kd_sub_kegiatan oke, kd_skpd from trdrka GROUP by kd_sub_kegiatan,kd_skpd) b 
            on b.oke=a.kd_sub_kegiatan and a.kd_skpd=b.kd_skpd WHERE left(a.kd_skpd,22)=left('$skpd',22) and a.kd_sub_kegiatan='$giat'
            GROUP BY kd_sub_kegiatan, kd_rek6, bulan)xxx
            GROUP BY kd_sub_kegiatan, kd_rek6
            
            ORDER BY giat";
        $aa = 0;
        $b = 0;
        $c = 0;
        $d = 0;
        $e = 0;
        $f = 0;
        $g = 0;
        $h = 0;
        $i = 0;
        $j = 0;
        $k = 0;
        $l = 0;
        $tot = 0;
        $totang = 0;
        $exe = $this->db->query($sql);
        foreach ($exe->result() as $a) {
            $giats   = $a->giat;
            $rek    = $a->kd_rek6;
            $nm_rek = $a->nm_rek;
            $jan    = $a->jan;
            $feb    = $a->feb;
            $mar    = $a->mar;
            $apr    = $a->apr;
            $mei    = $a->mei;
            $jun    = $a->jun;
            $jul    = $a->jul;
            $ags    = $a->ags;
            $sep    = $a->sep;
            $okt    = $a->okt;
            $nov    = $a->nov;
            $des    = $a->des;

            $jumlah1 = $jan + $feb + $mar + $apr + $mei + $jun + $jul + $ags + $sep + $okt + $nov + $des;


            $aa = $aa + $jan;
            $g = $g + $jul;
            $b = $b + $feb;
            $h = $h + $ags;
            $c = $c + $mar;
            $i = $i + $sep;
            $d = $d + $apr;
            $j = $j + $okt;
            $e = $e + $mei;
            $k = $k + $nov;
            $f = $f + $jun;
            $l = $l + $des;
            $jumlah = $jan + $feb + $mar + $apr + $mei + $jun + $jul + $ags + $sep + $okt + $nov + $des;
            $tot    = $tot + $jumlah;


            $sqlang = "SELECT 
        sum(nilai) as anggaran
        from trdrka z where jns_ang='$data' AND kd_skpd='$skpd' and kd_sub_kegiatan='$giat' and kd_rek6='$rek'";
            //echo $sqlang;
            $exesqlang = $this->db->query($sqlang);
            foreach ($exesqlang->result() as $ag) {

                $anggaran            = number_format($ag->anggaran, '2', ',', '.');
                $anggarans            = $ag->anggaran;
            }

            $cetak .= "
                    <tr>
                        <td>$rek <br> $nm_rek</td>
                        <td align='right'>" . number_format($anggarans, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($jan, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($feb, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($mar, '2', ',', '.') . "</td>
                        <td bgcolor='#CCCCCC' align='right'>" . number_format($jan + $feb + $mar, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($apr, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($mei, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($jun, '2', ',', '.') . "</td>
                        <td bgcolor='#CCCCCC' align='right'>" . number_format($apr + $mei + $jun, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($jul, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($ags, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($sep, '2', ',', '.') . "</td>
                        <td bgcolor='#CCCCCC' align='right'>" . number_format($jul + $ags + $sep, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($okt, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($nov, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($des, '2', ',', '.') . "</td>
                        <td bgcolor='#CCCCCC' align='right'>" . number_format($okt + $nov + $des, '2', ',', '.') . "</td>
                    </tr>";
            $totang = $totang + $anggarans;
        }
        $cetak .= "   <tr>
                        <td  align='center'><b>Total</td>
                        <td align='right'><b>" . number_format($totang, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($aa, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($b, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($c, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($aa + $b + $c, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($d, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($e, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($f, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($d + $e + $f, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($g, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($h, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($i, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($g + $h + $i, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($j, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($k, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($l, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($j + $k + $l, '2', ',', '.') . "</td>
                    </tr>";
        // $cetak.="   <tr>
        //                 <td colspan='2' align='center'><b>Total Triwulan</td>
        //                 <td align='right' ><b>".number_format($tot,'2',',','.')."</td>
        //                 <td align='center' colspan='3' ><b>".number_format($aa+$b+$c,'2',',','.')."</td>
        //                 <td align='center' colspan='3' ><b>".number_format($d+$e+$f,'2',',','.')."</td>
        //                 <td align='center' colspan='3' ><b>".number_format($g+$h+$i,'2',',','.')."</td>
        //                 <td align='center' colspan='3' ><b>".number_format($j+$k+$l,'2',',','.')."</td>
        //             </tr>";
        $cetak .= "</table>";

        if ($hit != "hidden") { /*if hidden*/
            $sql = "SELECT * from ms_ttd WHERE id_ttd='$ttd1'";
            $exe = $this->db->query($sql);
            foreach ($exe->result() as $a) {
                $nip    = $a->nip;
                $nama   = $a->nama;
                $jabatan = $a->jabatan;
                $pangkat = $a->pangkat;
            }
            $sql = "SELECT * from ms_ttd WHERE id_ttd='$ttd2'";
            $exe = $this->db->query($sql);
            foreach ($exe->result() as $a) {
                $nip2    = $a->nip;
                $nama2   = $a->nama;
                $jabatan2 = $a->jabatan;
                $pangkat2 = $a->pangkat;
            }

            $cetak .= "<table width='100%' border='0' style='font-size: 12px'>
                        <tr>
                            <td width='50%' align='center'><br>
                                
                                
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                
                            </td>
                            <td width='50%' align='center'><br>
                                Kabupaten Melawi, " . $this->support->tanggal_format_indonesia($tgl) . " <br>
                                $jabatan
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <b><u>$nama</u></b><br>
                                NIP. $nip
                            </td>
                        </tr>

                    </table>";
        } /*end if hidden*/

        switch ($cret) {
            case '1':
                echo ("<title>ANGKAS RO </title>");
                echo "$cetak";
                break;
            case '2':
                $this->support->_mpdf('', $cetak, 10, 10, 10, '1');
                break;
            case '3':
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= AngkasRO-$skpd.xls");
                echo "$cetak";
                break;
        }
    }
    //end


    function cetak_angkas_giat($tgl = '', $ttd1 = '', $ttd2 = '', $tj_ang = '', $tj_angkas = '', $skpd = '', $ctk = '', $hid = '')
    {

        $data = $this->cek_anggaran_model->cek_anggaran($skpd);
        // echo($tj_angkas);
        if ($tj_angkas == 'murni') {
            $nilai = 'nilai';
        } elseif ($tj_angkas == 'susun') {
            $nilai = 'nilai_susun';
        } elseif ($tj_angkas == 'sempurna' || $tj_angkas == 'sempurna11' || $tj_angkas = 'sempurna12' || $tj_angkas == 'sempurna13') {
            $nilai = 'nilai_sempurna';
        } elseif ($tj_angkas == 'sempurna1' || $tj_angkas == 'sempurna21' || $tj_angkas = 'sempurna22' || $tj_angkas == 'sempurna23') {
        } elseif ($tj_angkas == 'sempurna2' || $tj_angkas == 'sempurna31' || $tj_angkas = 'sempurna32' || $tj_angkas == 'sempurna33') {
        } elseif ($tj_angkas == 'ubah') {
            $nilai = 'nilai_ubah';
        }

        if ($tj_angkas == 'murni') {
            $nama = 'PENETAPAN';
        } elseif ($tj_angkas == 'susun') {
            $nama = 'PENETAPAN';
        } elseif ($tj_angkas == 'sempurna' || $tj_angkas == 'sempurna' || $tj_angkas == 'sempurna11' || $tj_angkas = 'sempurna12' || $tj_angkas == 'sempurna13') {
            $nama = 'PENYEMPURNAAN';
        } else if ($tj_angkas == 'sempurna1' || $tj_angkas == 'sempurna21' || $tj_angkas = 'sempurna22' || $tj_angkas == 'sempurna23') {
            $nama = 'PENYEMPURNAAN I';
        } else if ($tj_angkas == 'sempurna2' || $tj_angkas == 'sempurna31' || $tj_angkas = 'sempurna32' || $tj_angkas == 'sempurna33') {
            $nama = 'PENYEMPURNAAN II';
        } elseif ($tj_angkas == 'ubah' || $tj_angkas == 'ubah1' || $tj_angkas == 'ubah2') {
            $nama = 'PERUBAHAN';
        }



        $thn = $this->session->userdata('pcThang');
        $sql = $this->db->query("SELECT nm_skpd from ms_skpd WHERE kd_skpd='$skpd'")->row();
        // strtoupper{$sql->nm_skpd}."
        $namaaaaskpd = $sql->nm_skpd;
        $cetak = "<table border='0', width='100%' style='font-size: 14px'>
                    <tr style='padding:10px'>
                        <td colspan='3' align='center'><b><BR> ANGGARAN KAS KEGIATAN $nama<br> " . strtoupper($namaaaaskpd) . " <br> TAHUN $thn <br></td>
                    </tr>
                </table>";

        $cetak .= "<table style='border-collapse: collapse; font-size:12px;' width='100%', border='1', cellspacing='0' cellpadding='2'>
                    <thead>
                    <tr>
                        <td width='8%' align='center' rowspan='2' ><b>Kode</td>
                        <td width='25%' align='center' rowspan='2' ><b>Uraian</td>
                        <td width='8%' align='center' rowspan='2' ><b>Anggaran</td>
                        <td width='24%' align='center' colspan='3' ><b>Triwulan I (Rp).</td>
                        <td width='24%' align='center' colspan='3' ><b>Triwulan II (Rp).</td>
                        <td width='24%' align='center' colspan='3' ><b>Triwulan III (Rp).</td>
                        <td width='24%' align='center' colspan='3' ><b>Triwulan IV (Rp).</td>
                        <td width='8%' align='center' rowspan='2' ><b>Jumlah</td>                        
                    </tr> 
                    <tr>
                        <td width='6%' align='center'><b>Jan</td>
                        <td width='6%' align='center'><b>Feb</td>
                        <td width='6%' align='center'><b>Mar</td>
                        <td width='6%' align='center'><b>Apr</td>
                        <td width='6%' align='center'><b>Mei</td>
                        <td width='6%' align='center'><b>Jun</td>
                        <td width='6%' align='center'><b>Jul</td>
                        <td width='6%' align='center'><b>Ags</td>
                        <td width='6%' align='center'><b>Sep</td>
                        <td width='6%' align='center'><b>Okt</td>
                        <td width='6%' align='center'><b>Nov</td>
                        <td width='6%' align='center'><b>Des</td>
                    </tr>
                    </thead>";
        /*sub kegiatan*/


        $sort = $this->support->sort($skpd, 'a');

        // $sql9 = "SELECT kolom from tb_status_anggaran WHERE kode='$tj_ang'";
        //     $exe9 = $this->db->query($sql9);
        //     foreach ($exe9->result() as $a9) {
        //         $kolom    = $a9->kolom;
        //     }

        // $jenis='nilai_'.$tj_angkas;
        $sql = "
            SELECT kd_sub_kegiatan giat, (select nm_sub_kegiatan from ms_sub_kegiatan WHERE kd_sub_kegiatan=xxx.kd_sub_kegiatan) nm_giat, 
            (select sum(nilai) from trdrka h where h.kd_sub_kegiatan=xxx.kd_sub_kegiatan and h.kd_skpd='$skpd' and h.jns_ang='$tj_ang')as ang,
            isnull(sum(jan),0) jan, isnull(sum(feb),0) feb, isnull(sum(mar),0) mar, isnull(sum(apr),0) apr, isnull(sum(mei),0) mei, isnull(sum(jun),0) jun,
            isnull(sum(jul),0) jul, isnull(sum(ags),0) ags, isnull(sum(sep),0) sep, isnull(sum(okt),0) okt, isnull(sum(nov),0) nov, isnull(sum(des),0) des
            from (
            select kd_sub_kegiatan,
            case when bulan=1 then sum($nilai) else 0 end as jan,
            case when bulan=2 then sum($nilai) else 0 end as feb,
            case when bulan=3 then sum($nilai) else 0 end as mar,
            case when bulan=4 then sum($nilai) else 0 end as apr,
            case when bulan=5 then sum($nilai) else 0 end as mei,
            case when bulan=6 then sum($nilai) else 0 end as jun,
            case when bulan=7 then sum($nilai) else 0 end as jul,
            case when bulan=8 then sum($nilai) else 0 end as ags,
            case when bulan=9 then sum($nilai) else 0 end as sep,
            case when bulan=10 then sum($nilai) else 0 end as okt,
            case when bulan=11 then sum($nilai) else 0 end as nov,
            case when bulan=12 then sum($nilai) else 0 end as des from trdskpd_ro a  WHERE a.kd_skpd='$skpd'
            GROUP BY kd_sub_kegiatan, bulan)xxx
            GROUP BY kd_sub_kegiatan
            UNION all
            /*kegiatan*/
            SELECT kd_sub_kegiatan giat, (select DISTINCT nm_kegiatan from ms_kegiatan WHERE left(kd_kegiatan,12)=left(xxx.kd_sub_kegiatan,12)) nm_giat, 
            (select sum(nilai) from trdrka h where left(h.kd_sub_kegiatan,12)=left(xxx.kd_sub_kegiatan,12) and h.kd_skpd='$skpd' and h.jns_ang='$tj_ang')as ang,
            sum(jan) jan, sum(feb) feb, sum(mar) mar, sum(apr) apr, sum(mei) mei, sum(jun) jun,
            sum(jul) jul, sum(ags) ags, sum(sep) sep, sum(okt) okt, sum(nov) nov, sum(des) des
            from (
            select left(kd_sub_kegiatan,12) kd_sub_kegiatan,
            case when bulan=1 then sum($nilai) else 0 end as jan,
            case when bulan=2 then sum($nilai) else 0 end as feb,
            case when bulan=3 then sum($nilai) else 0 end as mar,
            case when bulan=4 then sum($nilai) else 0 end as apr,
            case when bulan=5 then sum($nilai) else 0 end as mei,
            case when bulan=6 then sum($nilai) else 0 end as jun,
            case when bulan=7 then sum($nilai) else 0 end as jul,
            case when bulan=8 then sum($nilai) else 0 end as ags,
            case when bulan=9 then sum($nilai) else 0 end as sep,
            case when bulan=10 then sum($nilai) else 0 end as okt,
            case when bulan=11 then sum($nilai) else 0 end as nov,
            case when bulan=12 then sum($nilai) else 0 end as des from trdskpd_ro a 
            WHERE a.kd_skpd='$skpd'
            GROUP BY left(kd_sub_kegiatan,12), bulan)xxx
            GROUP BY kd_sub_kegiatan
            ORDER BY giat";
        $aa = 0;
        $b = 0;
        $c = 0;
        $d = 0;
        $e = 0;
        $f = 0;
        $g = 0;
        $h = 0;
        $i = 0;
        $j = 0;
        $k = 0;
        $l = 0;
        $tot = 0;
        $jtot = 0;
        $jang = 0;
        $exe = $this->db->query($sql);
        foreach ($exe->result() as $a) {
            $giat   = $a->giat;
            $nm_giat = $a->nm_giat;
            $ang    = $a->ang;
            $jan    = $a->jan;
            $feb    = $a->feb;
            $mar    = $a->mar;
            $apr    = $a->apr;
            $mei    = $a->mei;
            $jun    = $a->jun;
            $jul    = $a->jul;
            $ags    = $a->ags;
            $sep    = $a->sep;
            $okt    = $a->okt;
            $nov    = $a->nov;
            $des    = $a->des;

            // $aa=$aa+$jan; $g=$g+$jul;
            //             $b=$b+$feb; $h=$h+$ags;
            //             $c=$c+$mar; $i=$i+$sep;
            //             $d=$d+$apr; $j=$j+$okt;
            //             $e=$e+$mei; $k=$k+$nov;
            //             $f=$f+$jun; $l=$l+$des;

            $jumlah = $jan + $feb + $mar + $apr + $mei + $jun + $jul + $ags + $sep + $okt + $nov + $des;
            // $tot    =$tot+$jumlah;

            $cetak .= "
                    <tr>
                        <td>" . $giat . "</td>
                        <td>$nm_giat</td>
                        <td align='right'>" . number_format($ang, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($jan, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($feb, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($mar, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($apr, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($mei, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($jun, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($jul, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($ags, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($sep, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($okt, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($nov, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($des, '2', ',', '.') . "</td>
                        <td align='right'>" . number_format($jumlah, '2', ',', '.') . "</td>
                    </tr>";

            if (strlen($giat) == 15) {
                $aa = $aa + $jan;
                $g = $g + $jul;
                $b = $b + $feb;
                $h = $h + $ags;
                $c = $c + $mar;
                $i = $i + $sep;
                $d = $d + $apr;
                $j = $j + $okt;
                $e = $e + $mei;
                $k = $k + $nov;
                $f = $f + $jun;
                $l = $l + $des;

                $jjumlah = $jan + $feb + $mar + $apr + $mei + $jun + $jul + $ags + $sep + $okt + $nov + $des;
                $jtot    = $jtot + $jjumlah;
                $jang    = $jang + $ang;
            }
        }

        $cetak .= "   <tr>
                        <td colspan='2' align='center'><b>Total</td>
                        <td align='right'><b>" . number_format($jang, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($aa, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($b, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($c, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($d, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($e, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($f, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($g, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($h, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($i, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($j, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($k, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($l, '2', ',', '.') . "</td>
                        <td align='right'><b>" . number_format($jtot, '2', ',', '.') . "</td>
                    </tr>";
        $cetak .= "   <tr>
                        <td colspan='2' align='center'><b>Total Triwulan</td>
                        <td align='right' ><b>" . number_format($tot, '2', ',', '.') . "</td>
                        <td align='center' colspan='3' ><b>" . number_format($aa + $b + $c, '2', ',', '.') . "</td>
                        <td align='center' colspan='3' ><b>" . number_format($d + $e + $f, '2', ',', '.') . "</td>
                        <td align='center' colspan='3' ><b>" . number_format($g + $h + $i, '2', ',', '.') . "</td>
                        <td align='center' colspan='3' ><b>" . number_format($j + $k + $l, '2', ',', '.') . "</td>
                    </tr>";
        $cetak .= "</table>";

        if ($hid != "hidden") {
            $sql = "SELECT * from ms_ttd WHERE id_ttd='$ttd1'";
            $exe = $this->db->query($sql);
            foreach ($exe->result() as $a) {
                $nip    = $a->nip;
                $nama   = $a->nama;
                $jabatan = $a->jabatan;
                $pangkat = $a->pangkat;
            }

            $sql = "SELECT * from ms_ttd WHERE id_ttd='$ttd2'";
            $exe = $this->db->query($sql);
            foreach ($exe->result() as $a) {
                $nip2    = $a->nip;
                $nama2   = $a->nama;
                $jabatan2 = $a->jabatan;
                $pangkat2 = $a->pangkat;
            }

            $cetak .= "<table width='100%' border='0' style='font-size:12px'>
                        <tr>
                            <td width='50%' align='center'><br>
                               
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                
                            </td>
                            <td width='50%' align='center'><br>
                                Kabupaten Melawi, " . $this->support->tanggal_format_indonesia($tgl) . " <br>
                                $jabatan
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <b><u>$nama</u></b><br>
                                NIP. $nip
                            </td>
                        </tr>

                    </table>";
        }

        switch ($ctk) {
            case '1':
                echo ("<title>ANGKAS RO </title>");
                echo "$cetak";
                break;
            case '2':
                $this->support->_mpdf('', $cetak, 10, 10, 10, '1');
                break;
            case '3':
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= AngkasRO-$skpd.xls");
                echo "$cetak";
                break;
        }
    }

    function preview_cetakan_cek_anggaran_geser($id, $cetak, $status_ang)
    {

        if ($status_ang == 'nilai') {
            $status = "PENYUSUNAN";
        } else if ($status_ang == 'nilai_sempurna') {
            $status = "PERGESERAN";
        } else {
            $status = "PERUBAHAN";
        }
        $nama = $this->db->query("SELECT nm_skpd from ms_skpd where kd_skpd='$id'")->row();
        $cRet = '';

        $cRet .= "<table style='font-size:12px;border-left:solid 0px black;border-top:solid 0px black;border-right:solid 0px black;' width='100%' border='0'>
                    <tr>
                        <td align='center' colspan='5'><b>LAPORAN PERBANDINGAN<br>NILAI ANGGARAN DAN NILAI ANGGARAN KAS $status<br>{$nama->nm_skpd}</b></td>
                        
                    </tr>
                 </table>";




        $cRet .= "<table style='border-collapse:collapse;vertical-align:top;font-size:12 px;' width='100%' align='center' border='1' cellspacing='0' cellpadding='1'>

                     <thead >                       
                        <tr>
                            <td bgcolor='#A9A9A9' width='5%' align='center '><b>NO</b></td>
                            <td bgcolor='#A9A9A9' width='10%' align='center '><b>Kode Sub Kegiatan</b></td>
                            <td bgcolor='#A9A9A9' width='30%' align='center'><b>Nama Sub Kegiatan</b></td>
                            <td bgcolor='#A9A9A9' width='15%' align='center'><b>Nilai Anggaran Murni</b></td>
                            <td bgcolor='#A9A9A9' width='15%' align='center'><b>Nilai Anggaran Pergeseran</b></td>
                            <td bgcolor='#A9A9A9' width='15%' align='center'><b>Nilai Anggaran Kas</b></td>
                            <td bgcolor='#A9A9A9' width='10%' align='center'><b>Selisih</b></td>
                         </tr>
                     </thead>
                     
                   
                        ";



        // $sql1="
        //      SELECT a.giat kd_kegiatan, a.nama nm_kegiatan, a.nilai_ang, isnull(b.nilai_kas,0) nilai_kas,
        //      CASE WHEN isnull(b.nilai_kas,0) = a.nilai_ang THEN 'SAMA' ELSE 'SELISIH' END AS hasil
        //                   from (
        //  select kd_sub_kegiatan giat, nm_sub_kegiatan nama, sum(nilai_sempurna) nilai_ang

        //   from trdrka where kd_skpd='$id' GROUP BY kd_sub_kegiatan,nm_sub_kegiatan)
        //  a left join (
        //  select kd_sub_kegiatan giat, sum(nilai) nilai_kas from trdskpd_ro where kd_skpd='$id' GROUP BY kd_sub_kegiatan) b
        //  on a.giat=b.giat where isnull(b.nilai_kas,0) <> a.nilai_ang
        //  ORDER BY
        //   hasil,a.giat

        //  ";

        $sql1 = "SELECT
                        a.kd_sub_kegiatan,
                        a.nm_sub_kegiatan,
                        a.kd_skpd,
                        a.nm_skpd, 
                        SUM ( a.nilai ) anggaran_murni,
                        SUM ( a.nilai_sempurna ) anggaran,
                        (SELECT SUM ( nilai ) FROM trdskpd_ro WHERE kd_sub_kegiatan = a.kd_sub_kegiatan AND kd_skpd = a.kd_skpd GROUP BY kd_sub_kegiatan,kd_skpd) as angkas
                        FROM
                        trdrka a
                        WHERE kd_skpd = '$id'    
                        GROUP BY
                        a.kd_sub_kegiatan,
                        a.nm_sub_kegiatan,
                        a.kd_skpd,
                        a.nm_skpd";

        $totnilai = 0;
        $total_angkas = 0;
        $tselisih = 0;
        $no = 1;
        $query = $this->db->query($sql1);


        foreach ($query->result() as $row) {
            $giat = rtrim($row->kd_sub_kegiatan);
            $nm_giat = rtrim($row->nm_sub_kegiatan);
            $nilai_ang = ($row->anggaran);
            $nilai_angx = number_format($nilai_ang, 2, ',', '.');
            $nilai_kas = ($row->angkas);
            $nilai_kasx = number_format($nilai_kas, 2, ',', '.');
            $ang_murni = ($row->anggaran_murni);
            $ang_murnix = number_format($ang_murni, 2, ',', '.');
            $sisa = $nilai_ang - $nilai_kas;


            if ($sisa == 0 || $sisa == null) {
                $warna = '#6bf75c';
                $hasil = 'Sama';
            } else {
                $warna = '#f24663';
                $hasil = 'Selisih';
            }



            $cRet    .= " <tr>                                
                                        <td bgcolor='$warna' align='center' style='vertical-align:middle; ' >$no</td>
                                        <td bgcolor='$warna' align='center' style='vertical-align:middle; ' >$giat</td>
                                        <td bgcolor='$warna' align='left' style='vertical-align:middle; ' >$nm_giat</td>
                                        <td bgcolor='$warna' align='right' style='vertical-align:middle; ' >$ang_murnix</td>
                                        <td bgcolor='$warna' align='right' style='vertical-align:middle; ' >$nilai_angx</td>
                                        <td bgcolor='$warna' align='right' style='vertical-align:middle; ' >$nilai_kasx</td>
                                        <td bgcolor='$warna' align='right' style='vertical-align:middle; ' >" . number_format($sisa, 2, ',', '.') . "</td>
                                    </tr>";

            $totnilai = $totnilai + $nilai_ang;
            $total_angkas = $total_angkas + $nilai_kas;

            $no++;
        }

        $tselisih = $totnilai - $total_angkas;

        $cRet    .= "       <tr>                                
                                        <td align='center' style='vertical-align:middle;' ></td>
                                        <td align='center' style='vertical-align:middle;' ></td>
                                        <td align='center' style='vertical-align:middle;' ><b>Total</b></td>
                                        <td align='right' style='vertical-align:middle; ' ><b>" . number_format($totnilai, 2, ',', '.') . "</b></td>
                                        <td align='right' style='vertical-align:middle; ' ><b>" . number_format($total_angkas, 2, ',', '.') . "</b></td>
                                        <td align='right' style='vertical-align:middle; ' ><b>" . number_format($tselisih, 2, ',', '.') . "<b/></td>
                                    </tr>";


        $cRet .= "</table>";

        $data['prev'] = $cRet;
        switch ($cetak) {
            case 0;
                echo ("<title>Lap Perbandingan Anggaran</title>");
                echo ($cRet);
                break;
            case 1;
                $this->support->_mpdf('', $cRet, 10, 10, 10, '1');
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= cek_anggaran.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }


    function preview_cetakan_cek_anggaran($id, $cetak, $status_ang)
    {

        if ($status_ang == 'nilai') {
            $status = "PENYUSUNAN";
        } else if ($status_ang == 'nilai_sempurna') {
            $status = "PERGESERAN";
        } else {
            $status = "PERUBAHAN";
        }
        $nama = $this->db->query("SELECT nm_skpd from ms_skpd where kd_skpd='$id'")->row();
        $cRet = '';

        $cRet .= "<table style='font-size:12px;border-left:solid 0px black;border-top:solid 0px black;border-right:solid 0px black;' width='100%' border='0'>
                    <tr>
                        <td align='center' colspan='5'><b>LAPORAN PERBANDINGAN<br>NILAI ANGGARAN DAN NILAI ANGGARAN KAS $status<br>{$nama->nm_skpd}</b></td>
                        
                    </tr>
                 </table>";




        $cRet .= "<table style='border-collapse:collapse;vertical-align:top;font-size:12 px;' width='100%' align='center' border='1' cellspacing='0' cellpadding='1'>

                     <thead >                       
                        <tr>
                            <td bgcolor='#A9A9A9' width='15%' align='center '><b>Kode Kegiatan</b></td>
                            <td bgcolor='#A9A9A9' width='50%' align='center'><b>Nama Kegiatan</b></td>
                            <td bgcolor='#A9A9A9' width='15%' align='center'><b>Nilai Anggaran</b></td>
                            <td bgcolor='#A9A9A9' width='15%' align='center'><b>Nilai Anggaran Kas</b></td>
                            <td bgcolor='#A9A9A9' width='5%' align='center'><b>Hasil</b></td>
                         </tr>
                     </thead>
                     
                   
                        ";


        // $sort=$this->support->sort($id);
        $sql1 = "
                    SELECT a.giat kd_kegiatan, a.nama nm_kegiatan, a.nilai_ang, isnull(b.nilai_kas,0) nilai_kas,
                    CASE WHEN isnull(b.nilai_kas,0) = a.nilai_ang THEN 'SAMA' ELSE 'SELISIH' END AS hasil
                                 from (
                select kd_sub_kegiatan giat, nm_sub_kegiatan nama, sum($status_ang) nilai_ang

                 from trdrka where kd_skpd='$id' GROUP BY kd_sub_kegiatan,nm_sub_kegiatan)
                a left join (
                select kd_sub_kegiatan giat, sum($status_ang) nilai_kas from trdskpd_ro where kd_skpd='$id' GROUP BY kd_sub_kegiatan) b
                on a.giat=b.giat where isnull(b.nilai_kas,0) <> a.nilai_ang
                ORDER BY
                 hasil,a.giat

                ";

        $totnilai = 0;
        $tnilai2 = 0;
        $tselisih = 0;
        $query = $this->db->query($sql1);

        foreach ($query->result() as $row) {
            $giat = rtrim($row->kd_kegiatan);
            $nm_giat = rtrim($row->nm_kegiatan);
            $hasil = rtrim($row->hasil);
            $nilai_ang = ($row->nilai_ang);
            $nilai_angx = number_format($nilai_ang, 2, ',', '.');
            $nilai_kas = ($row->nilai_kas);
            $nilai_kasx = number_format($nilai_kas, 2, ',', '.');

            if ($hasil == 'SAMA') {


                $cRet    .= " <tr>                                
                                        <td align='center' style='vertical-align:middle; ' >$giat</td>
                                        <td align='left' style='vertical-align:middle; ' >$nm_giat</td>
                                        <td align='right' style='vertical-align:middle; ' >$nilai_ang</td>
                                        <td align='right' style='vertical-align:middle; ' >$nilai_kas</td>
                                        <td align='center' style='vertical-align:middle; ' >$hasil</td>
                                    </tr> 
                                   
                                    ";
            } else {


                $cRet    .= " <tr>                                
                                        <td bgcolor='#ff5d47' align='center' style='vertical-align:middle;' >$giat</td>
                                        <td bgcolor='#ff5d47' align='left' style='vertical-align:middle;' >$nm_giat</td>
                                        <td bgcolor='#ff5d47' align='right' style='vertical-align:middle;' >$nilai_ang</td>
                                        <td bgcolor='#ff5d47' align='right' style='vertical-align:middle;'>$nilai_kas</td>
                                        <td bgcolor='#ff5d47' align='center' style='vertical-align:middle;'>$hasil</td>
                                    </tr> 
                                   
                                    ";
            }
        }


        $cRet .= "</table>";

        $data['prev'] = $cRet;
        switch ($cetak) {
            case 0;
                echo ("<title>Lap Perbandingan Anggaran</title>");
                echo ($cRet);
                break;
            case 1;
                $this->support->_mpdf('', $cRet, 10, 10, 10, '1');
                break;
            case 2;
                header("Cache-Control: no-cache, no-store, must-revalidate");
                header("Content-Type: application/vnd.ms-excel");
                header("Content-Disposition: attachment; filename= cek_anggaran.xls");
                $this->load->view('anggaran/rka/perkadaII', $data);
                break;
        }
    }
}
