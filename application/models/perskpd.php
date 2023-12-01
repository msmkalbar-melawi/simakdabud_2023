<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Perskpd extends CI_Model
{

    protected $query;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Fungsi ini mengambil jumlah anggaran dan jumlah realiasi anggaran secara rinci
     * @param int $rekening, merupakan kode rekening untuk mengambil table yg di pilih, hanya bisa 4,5,dan 6
     * @param int $spasi, untuk membuat jarak spasi
     * @param string $kodeSkpd
     * @param date $tanggalAwal
     * @param date $tanggalAkhir
     * @param string $jenisAnggaran
     * @param string $kode1, LEFT 4 dari kd_rek6
     * @param string $kode2, LEFT 6 dari kd_rek6
     * @param string $kode2, LEFT 8 dari kd_rek6
     * @return string $query
     * @author Emon Krismon
     * @link https://github.com/krismonsemanas
     */
    public function subRo($rekening, $spasi, $kodeSkpd, $tanggalAwal, $tanggalAkhir, $jenisAnggaran, $kode1, $kode2, $kode3)
    {
        if ($rekening === 4) {
            $select = "SELECT
                SUM(a.nilai) AS nil_ang,
                b.kd_rek4 AS kd_rek,
                b.nm_rek4 AS nm_rek,
                '$spasi' AS spasi ";

            $join = " JOIN ms_rek4 b ON LEFT(a.kd_rek6, 6) = b.kd_rek4 ";
            $groupBy = " GROUP BY b.kd_rek4,b.nm_rek4";
        } elseif ($rekening === 5) {
            $select = "SELECT 
            SUM(a.nilai) AS nil_ang,
            b.kd_rek5 AS kd_rek,
            b.nm_rek5 AS nm_rek,
            '$spasi' AS spasi ";
            $join = " JOIN ms_rek5 b ON LEFT(a.kd_rek6, 8) = b.kd_rek5 ";
            $groupBy = " GROUP BY b.kd_rek5,b.nm_rek5";
        } else {
            $select = "SELECT
            SUM(a.nilai) AS nil_ang,
            a.kd_rek6 AS kd_rek,
            a.nm_rek6 AS nm_rek,
            '$spasi' AS spasi ";
            $join = "";
            $groupBy = " GROUP BY a.kd_rek6,a.nm_rek6";
        }

        $sql = "$select,
        (
            SELECT 
                SUM(CASE WHEN LEFT(d.kd_rek6, 1) = 4 OR LEFT(d.kd_rek6, 2) = 61 THEN (d.kredit-d.debet) ELSE 0 END ) -
                SUM(CASE WHEN LEFT(d.kd_rek6, 1) = 5 OR LEFT(d.kd_rek6, 2) = 62 THEN (d.debet-d.kredit) ELSE 0 END ) 
            FROM 
                trhju_pkd c
            JOIN
                trdju_pkd d
            ON c.no_voucher = d.no_voucher AND c.kd_skpd = d.kd_unit
            WHERE
                (
                    LEFT(d.kd_rek6, 4) IN ($kode1) OR  LEFT(d.kd_rek6, 6) IN ($kode2) OR  LEFT(d.kd_rek6, 8) IN ($kode3)
                ) 
                AND c.tgl_voucher BETWEEN '$tanggalAwal' AND '$tanggalAkhir'
                AND c.kd_skpd = '$kodeSkpd'
        ) as nilai
        FROM
            trdrka a
         $join            
        WHERE
            (
                LEFT(a.kd_rek6, 4) IN ($kode1) OR  LEFT(a.kd_rek6, 6) IN ($kode2) OR  LEFT(a.kd_rek6, 8) IN ($kode3)
            ) 
            AND a.kd_skpd = '$kodeSkpd'
            AND a.jns_ang = '$jenisAnggaran' 
        $groupBy 
       ";

        $this->query .= $sql;
        return $this;
    }

    /** 
     * Menambahkan UNION ALL query builder
     * @return $this
     * @author Emon Krismon
     * @link https://github.com/krismonsemanas
     */
    public function union()
    {
        $this->query .= " UNION ALL ";
        return $this;
    }

    /** 
     * Menjalankan query
     * @return object $result
     * @author Emon Krismon
     * @link https://github.com/krismonsemanas
     */
    public function get()
    {
        $result =  $this->db->query($this->query);
        $this->resetQuery(); // reset query;
        return $result;
    }

    /** 
     * Generate query builder
     * @return string $sql
     * @author Emon Krismon
     * @link https://github.com/krismonsemanas
     */
    public function toSql()
    {
        $sql = $this->query;
        $this->resetQuery();
        return $sql;
    }

    /** 
     * Reset query setelah di jalankan
     * @return $this
     * @author Emon Krismon
     * @link https://github.com/krismonsemanas
     */
    private function resetQuery()
    {
        $this->query = "";
    }
}
