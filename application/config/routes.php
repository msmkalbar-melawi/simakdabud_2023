
<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "welcome";
// $route['translate_uri_dashes'] = FALSE;
//added by wasisw
//MASTER
$route['mfungsi']     = "master/mfungsi"; //OK
$route['murusan']     = "master/murusan"; //OK
$route['burusan']     = "master/burusan"; //OK
$route['mskpd']     = "master/mskpd"; //OK
$route['mprogram']     = "master/mprogram"; //OK
$route['mkegiatan']   = "master/mkegiatan"; //OK
$route['mrek1']     = "master/mrek1"; //OK
$route['mrek2']     = "master/mrek2"; //OK
$route['mrek3']     = "master/mrek3"; //OK
$route['mrek4']     = "master/mrek4"; //OK
$route['mrek5']     = "master/mrek5"; //OK
$route['mrek6']     = "master/mrek6"; //OK
$route['mbank']     = "master/mbank"; //OK
$route['mttd']       = "master/mttd"; //OK
$route['mperusahaan']   = "master/mperusahaan"; //OK
$route['tapd']       = "master/tapd"; //OK
$route['sumber_dana']   = "master/sumberaktif"; //OK
$route['kelompok_barang']   = "master/kelompok_barang"; //OK
$route['penerima']     = "sp2d_bank/mpenerima"; //OK
$route['ganti_password']   = "master/ganti_pass";



$route['user']         = "master/user"; //OK
$route['tambah_user']     = "master/tambah_user"; //OK
$route['edit_user/(:any)']   = "master/edit_user"; //OK
$route['hapus_user/(:any)'] = "master/hapus_user"; //OK

//ANGGARAN

//PENYUSUNAN

$route['tambah_rka_penyusunan']         = "rka_rancang/tambah_rka_penyusunan"; //OK
$route['rka_skpd_penyusunan']           = "rka_rancang/rka0_penyusunan"; //OK
$route['preview_rka0_penyusunan/(:any)']     = "rka_rancang/preview_rka0_penyusunan/"; //OK
$route['rka_pendapatan_penyusunan']       = "rka_rancang/rka_pendapatan_penyusunan"; //OK
$route['preview_pendapatan_penyusunan/(:any)']   = "rka_rancang/preview_pendapatan_penyusunan/"; //OK
$route['preview_rka0_penyusunan_org/(:any)']   = "rka_rancang/preview_rka0_penyusunan_org/"; //OK
$route['rka_belanja_skpd']             = "rka_rancang/rka22_penyusunan"; //OK
$route['preview_rka22_penyusunan/(:any)']     = "Rka_rancang/preview_rka22_penyusunan/"; //OK
$route['rka_rincian_belanja_skpd']         = "rka_rancang/rka221_penyusunan"; //OK
$route['preview_rka221_penyusunan/(:any)']     = "rka_rancang/preview_rka221_penyusunan/"; //OK
$route['daftar_kegiatan_penyusunan/(:any)']   = "rka_rancang/daftar_kegiatan_penyusunan/"; //OK
$route['pilih_giat_penyusunan']         = "rka_rancang/tambah_giat_penyusunan"; //OK
$route['rka_pembiayaan_penyusunan']       = "rka_rancang/rka_pembiayaan_penyusunan"; //OG
$route['preview_rka_pembiayaan_penyusunan/(:any)']     = "rka_rancang/preview_rka_pembiayaan_penyusunan/"; //OG
$route['cari221']                 = "rka_rancang/cari221/"; //OG




$route['dpa_belanja_skpd_penetapan/(:any)']      = "cetak_dpa/preview_rka_belanja_skpd_penetapan";
//PENETAPAN

$route['tambah_rka_penetapan']                 = "rka_penetapan/tambah_rka_penetapan"; //OG
$route['rka_skpd_penetapan']               = "rka_penetapan/rka0_penetapan";
$route['preview_rka_skpd_penetapan/(:any)']       = "rka_penetapan/preview_rka_skpd_penetapan/";
$route['preview_rka_skpd_penetapan_org/(:any)']     = "rka_penetapan/preview_rka_skpd_penetapan_org/";
$route['rka_pendapatan_penetapan']             = "rka_penetapan/rka_pendapatan_penetapan"; //OK
$route['preview_pendapatan_penetapan/(:any)']       = "rka_penetapan/preview_pendapatan_penetapan/"; //OK
$route['rka_belanja_skpd_penetapan']           = "rka_penetapan/rka_belanja_skpd_penetapan";
$route['preview_rka_belanja_skpd_penetapan/(:any)']   = "Rka_penetapan/preview_rka_belanja_skpd_penetapan/";
$route['rka_rincian_belanja_skpd_penetapan']      = "rka_penetapan/rka_rincian_belanja_skpd_penetapan";
$route['preview_rincian_belanja_skpd_penetapan/(:any)'] = "rka_penetapan/preview_rincian_belanja_skpd_penetapan/";
$route['daftar_kegiatan_penetapan/(:any)']         = "rka_penetapan/daftar_kegiatan_penetapan/";
$route['pilih_giat_penetapan']               = "rka_penetapan/tambah_giat_penetapan"; //OK
$route['rka_pembiayaan_penetapan']             = "rka_penetapan/rka_pembiayaan_penetapan"; //OG
$route['preview_rka_pembiayaan_penetapan/(:any)']     = "rka_penetapan/preview_rka_pembiayaan_penetapan/"; //OG
$route['anggaran_kas_penetapan']             = "rka_ro/angkas_ro"; //OG
$route['cetak_anggaran_kas_penetapan']           = "rka_penetapan/cetak_anggaran_kas"; //OG
$route['preview_anggaran_kas_penetapan/(:any)']     = "rka_penetapan/preview_cetak_anggaran_kas"; //OK
$route['cek_angkas_skpd/(:any)']             = "rka_penetapan/cek_angkas_skpd"; //OG
$route['rka_pendapatan']                 = "rka_penetapan/tambah_rka_pend"; //OG
$route['cek_anggaran']                   = "rka_ro/cek_anggaran";
$route['cek_anggaran_geser']               = "rka_ro/cek_anggaran_geser";

$route['cetak_anggaran_kas_penetapan_giat']       = "rka_ro/cetak_angkas_giat/1"; //OG
$route['cetak_anggaran_kas_penetapan']           = "rka_ro/cetak_angkas_ro/1"; //OG

$route['cetak_anggaran_kas_penetapan_giat_geser']     = "rka_ro/cetak_angkas_giat_geser/2"; //OK
$route['cetak_anggaran_kas_penetapan_geser']       = "rka_ro/cetak_angkas_ro/2"; //OK

$route['angkas_sempurna']                 = "rka_ro/angkas_sempurna"; //OG

$route['rekpot_spm']                   = "master/rekpot_spm"; //OG

$route['pengesahan_angkas']               = "rka_ro/pengesahan_angkas";

//Posting anggaran
$route['dpa_skpd']                     = "dpa_penetapan/ctk_dpa_skpd"; //OG
$route['preview_dpa_skpd/(:any)']             = "dpa_penetapan/preview_dpa_skpd"; //OG
$route['preview_cover_dpa_skpd/(:any)']         = "dpa_penetapan/preview_cover_dpa_skpd"; //OG
$route['preview_dpa_skpd_penetapan/(:any)']       = "cetak_dpa/preview_dpa_skpd_penetapan/";
$route['preview_dpa_rincian_belanja_skpd_penetapan/(:any)'] = "cetak_dpa/preview_dpa_rincian_belanja_skpd_penetapan/";
$route['preview_dpa_pendapatan_penetapan/(:any)']     = "cetak_dpa/preview_pendapatan_penetapan/";
$route['dpa_pembiayaan']                 = "cetak_dpa/dpa_pembiayaan_penetapan"; //OG
$route['preview_dpa_pembiayaan_penetapan/(:any)']     = "cetak_dpa/preview_dpa_pembiayaan_penetapan/";

// Penyetoran pendapatan
$route['penyetoran_piutang']         = "Penerimaan/penyetoran_piutang";
$route['penyetoran']             = "Penyetoran/penyetorans";


//Penyempurnaan

$route['tambah_rka_penyempurnaan']           = "rka_penyempurnaan/tambah_rka_penyempurnaan"; //OG
$route['rka_skpd_penyempurnaan']         = "rka_penyempurnaan/rka0_penyusunan";
$route['preview_rka0_penyempurnaan/(:any)']   = "rka_penyempurnaan/preview_rka0_penyusunan/";
$route['rka_belanja_skpd_penyempurnaan']     = "rka_penyempurnaan/rka22_penyusunan";
$route['preview_rka22_penyempurnaan/(:any)']   = "Rka_penyempurnaan/preview_rka22_penyusunan/";
$route['rka_rincian_belanja_skpd_penyempurnaan'] = "rka_penyempurnaan/rka221_penyusunan";
$route['preview_rka221_penyempurnaan/(:any)']   = "rka_penyempurnaan/preview_rka221_penyusunan/";
$route['daftar_kegiatan_penyempurnaan/(:any)']   = "rka_penyempurnaan/daftar_kegiatan_penyusunan/";

$route['tambah_rka_sempurna']               = "rka_penyempurnaan/tambah_rka_sempurna"; //OG
$route['rka_skpd_penyempurnaan']         = "rka_penyempurnaan/rka0_penyusunan";
$route['preview_rka0_penyempurnaan/(:any)']   = "rka_penyempurnaan/preview_rka0_penyusunan/";
$route['rka_belanja_skpd_penyempurnaan']     = "rka_penyempurnaan/rka22_penyusunan";
$route['preview_rka22_penyempurnaan/(:any)']   = "Rka_penyempurnaan/preview_rka22_penyusunan/";
$route['rka_rincian_belanja_skpd_penyempurnaan'] = "rka_penyempurnaan/rka221_penyusunan";
$route['preview_rka221_penyempurnaan/(:any)']   = "rka_penyempurnaan/preview_rka221_penyusunan/";
$route['daftar_kegiatan_penyempurnaan/(:any)']   = "rka_penyempurnaan/daftar_kegiatan_penyusunan/";

// DPA
$route['pengesahan_dpa']             = "cetak_dpa/pengesahan_dpa";
$route['dpa_skpd']                 = "cetak_dpa/dpa_skpd_penetapan";
$route['dpa_pendapatan']                 = "cetak_dpa/dpa_pendapatan_penetapan";
$route['dpa_belanja']               = "cetak_dpa/ctk_dpa22";
$route['dpa_rinci_belanja']           = "cetak_dpa/dpa221";
$route['daftar_kegiatan_penetapan1/(:any)']   = "cetak_dpa/daftar_kegiatan_penetapan1/";




//SPD
$route['spd_belanja']           = "spd/spd_belanja";
$route['spd_belanja_revisi']       = "spd/spd_belanja_revisi";
$route['spd_pembiayaan']         = "spd/spd_pembiayaan";
$route['daftar_spd']           = "spd/register_spd";
$route['cetak_lampiran_spd1/(:any)']   = "spd/cetak_lampiran_spd1";
$route['cetak_otor_spd/(:any)']         = "spd/cetak_otor_spd";
$route['cetak_spd']            = "spd/ctk_spd";
$route['preview_reg_spd/(:any)']     = "spd/preview_reg_spd";

//sp2d
$route['sp2d1']                = "sp2d/sp2d1";
$route['sp2d_up']            = "sp2d/sp2d_up";
$route['sp2d_gu']            = "sp2d/sp2d_gu";
$route['sp2d_tu']            = "sp2d/sp2d_tu";
$route['sp2d_ls']            = "sp2d/sp2d_ls";
$route['sp2d_gj']            = "sp2d/sp2d_gj";
$route['sp2d_cair']            = "sp2d/sp2d_cair";
$route['sp2dgunihil']        = "sp2d/sp2dgunihil";
$route['daftar_penguji']            = "sp2d/daftar_penguji";

//sp2b
$route['sp2bControllerr'] = 'Sp2bController/index';
//sp2b blud
$route['sp2bblud'] = 'blud/SP2BBludController/index';


//TUKD
$route['spj']               = "Cetak_spj/index";
$route['reg_koreksipenerimaan'] = "Register/penerimaan";
$route['reg_koreksipengeluaran'] = "Register/pengeluaran";

//PENDAPATAN
$route['saldo_kas']                     = "tukd/saldo_kas";
$route['pendapatan_penetapan']         = "Penetapan/penetapan_pendapatan";
$route['spj_terima']                    = "tukd/spj_terima";

//PENERIMAAN
$route['penerimaan_piutang']         = "Penerimaan/penerimaan_piutang";
$route['penerimaan']             = "Penerimaan/penerimaan_skpd";

//PENGELUARAN
$route['penagihan_prov']           = "Penagihan/penagihan_skpd";

$route['penagihan']             = "Penagihan/penagihanskpd";

$route['lamp_IIA1_kasda']             = "lamp_pmk/lamp_IIA1_kasda";

$route['bk_penerimaan']             = "LAMA/tukd/bk_penerimaan";

$route['cetak_buku_kas_pembantu']       = "LAMA/tukd/cetak_buku_kas_pembantu";

$route['penerimaan_pajak_daerah']       = "LAMA/tukd/penerimaan_pajak_daerah";

$route['rekap_gj']                    = "LAMA/tukd/rekap_gj";
$route['bku']               = "Cetak_tukd/bku";

$route['pengesahan_tu']                = "spp/pengesahan_spp_tu";
$route['pengesahan_lpj_up']            = "lpj/up";
$route['pengesahan_lpj_tu']           = "tukd/lpj_tu";

// BP RINCIAN OBJEK
$route['rincian_objek']         = "Cetak_rincian_objek/index";
//END
// AKUNTANSI
$route['reg_spj']       = "akuntansi/reg_spj";
$route['reg_spj_terima']       = "akuntansi/reg_spj_terima";
$route['bukubesar_kasda']           = "akuntansi/bukubesar_kasda";
// LRA KHUSUS JKN BOK
$route['lrajknakuntansi'] = "LRAJKNController/index";
// LRA KHUSUS BLUD
$route['lrabludakuntansi'] = "LRABLUDController/index";
// LRA KHUSUS BLUD
$route['rincian/blud'] = 'BludController';
$route['rincian/blud/cetak?(:any)'] = 'BludController/cetak';
$route['rincian/blud/cetak?(:any)'] = 'BludController/cetak2';

//MAPPING
$route['map_skpd']               = "mapping/map_skpd"; //OK
$route['map_rekening']             = "mapping/map_rekening"; //OK
$route['map_rekening_akuntansi']       = "mapping/map_rekening_akuntansi"; //OK
$route['imapping']               = "mapping/imapping"; //OK
$route['input_indikator']           = "mapping/input_indikator"; //OK
$route['validasi_indikator']         = "mapping/validasi_indikator"; //OK
$route['validasi_kegiatan']         = "mapping/validasi_kegiatan"; //OK
$route['cetak_kertas_kerja/(:any)'] = "mapping/cetak_kertas_kerja"; //OK
//KASDA
$route['bkp2_gaji']                 = "tukd/bkp2_gaji";
$route['bk_retribusi']                 = "tukd/bk_retribusi";
$route['register_cp']              = "tukd/register_cp";
$route['koreksi_penerimaan']              = "tukd/koreksi_penerimaan";
$route['penerimaan_non_sp2d']       = "tukd/penerimaan_non_sp2d";
$route['pengeluaran_non_sp2d']       = "tukd/pengeluaran_non_sp2d";
$route['restitusi_ppkd']            = "tukd/restitusi_ppkd";
$route['cetak_anggaransp2d']            = "tukd/cetak_anggaransp2d";
$route['reg_sp2d']                  = "tukd/reg_sp2d";
// UJI SP2D
$route['uji_sp2d'] = "Ujisp2d/index";

$route['index'] = "index";
$route['login'] = "login";
$route['logout'] = "logout";
$route['404_override'] = '';

// Register SPM Permintaan Perbend
$route['registerspm']                 = "RegisterSPM/index";
// Cetak TUKD
$route['buku_simpanan_bank']       = "Cetak_bukubank/index";
$route['buku_tunai']               = "Cetak_buku_tunai/index";
$route['buku_pajak']               = "Cetak_pajak/index";

$route['konsolidasi-jenis'] = "akuntansi_rekon/cetakKonsolidasiJenis";
$route['konsolidasi-sub-rinci'] = "akuntansi_rekon/cetakKonsolidasiSubRinci";

/* End of file routes.php */
/* Location: ./application/config/routes.php */