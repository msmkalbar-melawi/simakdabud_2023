<?php

/**
 * Mencetak periode tanggal / tahun pada kop laporan
 * @param int $tahun
 * @param int $bulan
 * @param ?int $tahun2 opsioanl jika ingin memasukan tahun sebelumnya
 * @return string
 * @author Emon Krismon
 * @link https://github.com/krismonsemanas
 */
function kopTahun($tahun, $bulan, $tahun2 = '')
{
    if ($tahun % 4 == 0) {
        $nilaibulan = ".31 JANUARI.29 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
    } else {
        $nilaibulan = ".31 JANUARI.28 FEBRUARI.31 MARET.30 APRIL.31 MEI.30 JUNI.31 JULI.31 AGUSTUS.30 SEPTEMBER.31 OKTOBER.30 NOVEMBER.31 DESEMBER";
    }

    $arraybulan = explode(".", $nilaibulan);

    $result = "UNTUK TAHUN YANG BERAKHIR SAMPAI DENGAN $arraybulan[$bulan] $tahun";
    if ($tahun2) {
        $result .= " DAN $tahun2";
    }

    return $result;
}

/**
 * Format mata uang indonesia
 * @param float $nilai
 * @return string nilai 
 * @author Emon Krismon
 * @link https://github.com/krismonsemanas 
 */
function idr($nilai)
{
    return number_format($nilai, 2, ',', '.');
}

/**
 * Fungsi ini untuk mereplace spasi pada nip yang di kirim pada url
 * @param string $nip
 * @return string
 */
function getNipUrl($nip)
{
    return str_replace(' ', '', urldecode($nip));
}

/**
 * fungsi ini digunakan untuk debug array yang di cetak secara rapi
 * @param Array $array
 * @return print array
 * @author Emon Krismon
 * @link https://github.com/krismonsemanas 
 */
function dd($array)
{
    print("<pre>" . print_r($array, true) . "</pre>");
    die;
}


function formatPositif($nilai)
{
    if ($nilai < 0) {
        return "(".idr(abs($nilai)).")";
    }
    return idr($nilai);
}