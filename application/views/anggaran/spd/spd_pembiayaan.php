<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/autoCurrency.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/numberFormat.js"></script>
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets\font-awesome\css\font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets\font-awesome\css\font-awesome.css">
    <style>
        button[type=submit] {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin: 2px 2px;
            width: auto;
            cursor: pointer;
        }

        button[type=primary] {
            background-color: #38a2ff;
            border: none;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin: 2px 2px;
            width: auto;
            text-align: center;
            cursor: pointer;
        }

        button[type=delete] {
            background-color: #ff2003;
            border: none;
            color: white;
            text-align: center;
            padding: 10px 20px;
            text-decoration: none;
            margin: 2px 2px;
            width: auto;
            cursor: pointer;
        }

        input[type=edit] {
            background-color: #ffe83d;
            border: none;
            color: black;
            padding: 10px 20px;
            text-decoration: none;
            margin: 2px 2px;
            width: 70px;
            cursor: pointer;
        }
    </style>
    <script type="text/javascript">
        var kode = '';
        var giat = '';
        var jenis = '';
        var nomor = '';
        var cid = 0;
        var ctk = '';
        var s_tox = 'edit';
        var status_spd = 0;
        // ini untuk konfigurasi nomor spd
        //var con_dpn='903/';
        //var con_blk_btl='/BTL/BPKAD-B/'.tahun_anggaran;
        //var con_blk_bl='/BL/BPKAD-B/'.tahun_anggaran;

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#dialog-modal").dialog({
                height: 600,
                width: 700,
                modal: true,
                autoOpen: false
            });

            $("#dialog-edit_nilai").dialog({
                height: 320,
                width: 900,
                modal: true,
                autoOpen: false
            });
            $("#dialog-cetak").dialog({
                height: 450,
                width: 500,
                modal: true,
                autoOpen: false
            });
            //get_skpd();
            get_tahun();
        });

        function get_tahun() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/spd/config_tahun',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    tahun_anggaran = data;
                }
            });

        }

        function get_skpd(kd_s = '', nm = '') {


            $("#skpd").attr("value", kd_s);
            $("#nmskpd").attr("value", nm);

            //$("#nmskpd").attr("value",data.nm_skpd);
            //kode = data.kd_skpd;
            kode = kd_s;
            $('#bendahara').combogrid({
                url: '<?php echo base_url(); ?>index.php/spd/load_kadis/' + kode,
                //$('#bendahara').combogrid({url:'<?php echo base_url(); ?>index.php/tukd/load_ttd/BK',
                queryParams: ({
                    kode: kode
                })
            });

        }

        $(function() {
            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/spd/load_spd_pembiayaan',
                rowStyler: function(index, row) {
                    if (row.status == 1) {
                        return 'background-color:#4bbe68;color:white';
                    }
                },
                idField: 'id',
                rownumbers: "true",
                fitColumns: "true",
                singleSelect: "true",
                autoRowHeight: "false",
                pagination: "true",
                nowrap: "true",

                columns: [
                    [{
                            field: 'ck',
                            title: '',
                            width: 20,
                            checkbox: 'true'
                        },
                        {
                            field: 'no_spd',
                            title: 'Nomor SPD',
                            width: 70
                        },
                        {
                            field: 'tgl_spd',
                            title: 'Tanggal',
                            width: 30
                        },
                        {
                            field: 'nm_skpd',
                            title: 'Nama SKPD',
                            width: 100,
                            align: "left"
                        },
                        {
                            field: 'total_rupiah',
                            title: 'Nilai',
                            width: 50,
                            align: "center"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    nomor = rowData.no_spd;
                    tgl = rowData.tgl_spd;
                    kode = rowData.kd_skpd;
                    nama = rowData.nm_skpd;
                    ketentuan = rowData.ketentuan;
                    bulan1 = rowData.bulan_awal;
                    bulan2 = rowData.bulan_akhir;
                    jns = rowData.jns_beban;
                    nip = rowData.nip;
                    nama_bend = rowData.nama_bend;
                    status_spd = rowData.status;
                    tot = angka(rowData.total) //number_format(rowData.total,2,'.',',');

                    //alert (tot);
                    get(nomor, tgl, kode, nama, bulan1, bulan2, jns, tot, ketentuan, nip, status_spd);
                    cek_anggaran_kas();
                    load_detail();

                    //beban();
                    tombol(status_spd);
                    jumlah_detail();
                    s_tox = 'edit';

                },
                onDblClickRow: function(rowIndex, rowData) {
                    section2();
                    s_tox = 'edit';


                }
            });

            $('#tanggal').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });


            $(function() {
                $('#skpd').combogrid({
                    panelWidth: 700,
                    idField: 'kd_skpd',
                    textField: 'kd_skpd',
                    mode: 'remote',
                    url: '<?php echo base_url(); ?>index.php/spd/skpduser_pembiayaan',
                    columns: [
                        [{
                                field: 'kd_skpd',
                                title: 'Kode SKPD',
                                width: 100
                            },
                            {
                                field: 'nm_skpd',
                                title: 'Nama SKPD',
                                width: 700
                            }
                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {
                        skpd = rowData.kd_skpd;
                        nmskpd = rowData.nm_skpd;

                        get_skpd(skpd, nmskpd);
                        cek_anggaran_kas();
                        //kosong();
                        cekbln_akhir(skpd);
                    },
                    onChange: function(rowIndex, rowData) {
                        kosong();

                    }
                });


                $('#skpds').combogrid({
                    panelWidth: 830,
                    url: '<?php echo base_url(); ?>/index.php/spd/skpduser',
                    idField: 'kd_skpd',
                    textField: 'nm_skpd',
                    mode: 'remote',
                    fitColumns: true,
                    columns: [
                        [{
                                field: 'kd_skpd',
                                title: 'SKPD',
                                width: 70
                            },
                            {
                                field: 'nm_skpd',
                                title: 'NAMA',
                                align: 'left',
                                width: 600
                            }

                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {
                        kd_skpd = rowData.kd_skpd;
                        nm_skpd = rowData.nm_skpd;
                        cari_spds(kd_skpd);
                    }
                });
            });

            function cari_spds(skpd) {
                var kriteria = skpd;
                $(function() {
                    $('#dg').edatagrid({
                        url: '<?php echo base_url(); ?>/index.php/spd/load_spd_pembiayaan',
                        queryParams: ({
                            cari: kriteria
                        })
                    });
                });
            }

            $('#bendahara').combogrid({
                panelWidth: 700,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                //url:'<?php echo base_url(); ?>index.php/rka/load_bendahara_p',
                // queryParams:({kode:kode}),
                columns: [
                    [{
                            field: 'nip',
                            title: 'NIP',
                            width: 150
                        },
                        {
                            field: 'nama',
                            title: 'Nama',
                            width: 500
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    $("#nama_bend").attr("value", rowData.nama);
                }
            });



            $('#bendahara_ppkd').combogrid({
                panelWidth: 700,
                idField: 'nip_ppkd',
                textField: 'nip_ppkd',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/spd/load_bendahara_ppkd',
                //       queryParams:({kode:kode}),
                columns: [
                    [{
                            field: 'nip_ppkd',
                            title: 'NIP',
                            width: 150
                        },
                        {
                            field: 'nama_ppkd',
                            title: 'Nama',
                            width: 500
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    $("#nama_ppkd").attr("value", rowData.nama_ppkd);
                    $("#jabatan_ppkd").attr("value", rowData.jabatan_ppkd);
                    $("#pangkat_ppkd").attr("value", rowData.pangkat_ppkd);
                }
            });



            $('#giat').combogrid({
                panelWidth: 700,
                idField: 'kd_sub_kegiatan',
                textField: 'kd_sub_kegiatan',
                mode: 'remote',
                columns: [
                    [{
                            field: 'kd_sub_kegiatan',
                            title: 'Kode Kegiatan',
                            width: 140
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Kegiatan',
                            width: 400
                        },
                        {
                            field: 'lalu',
                            title: 'SPD Lalu',
                            width: 100,
                            align: 'right'
                        },
                        {
                            field: 'total',
                            title: 'Anggaran',
                            width: 100,
                            align: 'right'
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    idxGiat = rowIndex;
                    giat = rowData.kd_sub_kegiatan;
                    $("#nmgiat").attr("value", rowData.nm_sub_kegiatan);
                    $('#prog').attr("value", rowData.kd_program);
                    $('#nmprog').attr("value", rowData.nm_program);
                    $('#anggaran').attr("value", number_format(rowData.total, 2, '.', ','));
                    $("#lalu").attr("value", number_format(rowData.lalu, 2, '.', ','));
                    document.getElementById('nilai').focus();
                }
            });


            $('#dg1').edatagrid({
                idField: 'idx',
                toolbar: '#toolbar',
                rownumbers: "true",
                singleSelect: "true",
                autoRowHeight: "false",
                nowrap: "true",
                columns: [
                    [{
                            field: 'no_spd',
                            title: 'Nomor SPD',
                            hidden: "true"
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'Kode Sub Kegiatan',
                            width: 160
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Sub Kegiatan',
                            width: 280
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai Rupiah',
                            width: 130,
                            align: "right"
                        },
                        {
                            field: 'lalu',
                            title: 'Telah Di SPD kan',
                            width: 130,
                            align: "right"
                        },
                        {
                            field: 'anggaran',
                            title: 'anggaran',
                            width: 130,
                            align: "right"
                        },
                        {
                            field: 'kd_program',
                            title: 'Kode Program',
                            hidden: "true",
                            width: 0
                        },
                        {
                            field: 'nm_program',
                            title: 'Nama Program',
                            hidden: "true",
                            width: 0
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    idx = rowIndex;
                    nilx = rowData.nilai;
                },
                onDblClickRow: function(rowIndex, rowData) {
                    kdkegiatan = rowData.kd_sub_kegiatan;
                    nmkegiatan = rowData.nm_sub_kegiatan;
                    nlalu = rowData.lalu;
                    nilai1 = rowData.nilai;
                    nilai_ag = rowData.anggaran;
                    edit_rekening(kdkegiatan, nmkegiatan, nilai1, nlalu, nilai_ag);
                }
            });


            $('#dg2').edatagrid({
                toolbar: '#toolbar',
                rownumbers: "true",
                singleSelect: "true",
                autoRowHeight: "false",
                nowrap: "true",
                onSelect: function(rowIndex, rowData) {
                    idx = rowIndex;
                    nilx = rowData.nilai;
                },
                columns: [
                    [{
                            field: 'hapus',
                            title: 'Hapus',
                            width: 35,
                            align: "center",
                            formatter: function(value, rec) {
                                return '<img src="<?php echo base_url(); ?>/assets/images/icon/cross.png" onclick="javascript:hapus_detail();" />';
                            }
                        },
                        {
                            field: 'no_spd',
                            title: 'Nomor SPD',
                            hidden: "true"
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'Kode Kegiatan',
                            width: 150
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Kegiatan',
                            width: 300
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai Rupiah',
                            width: 130,
                            align: "right"
                        },
                        {
                            field: 'lalu',
                            title: 'Telah Di SPD kan',
                            width: 130,
                            align: "right"
                        },
                        {
                            field: 'anggaran',
                            title: 'anggaran',
                            width: 130,
                            align: "right"
                        },
                        {
                            field: 'kd_program',
                            title: 'Kode Program',
                            hidden: "true",
                            width: 10
                        },
                        {
                            field: 'nm_program',
                            title: 'Nama Program',
                            hidden: "true",
                            width: 10
                        }
                    ]
                ]
            });
        });



        function beban() {
            jenis = document.getElementById('jenis').value;
            $('#giat').combogrid({
                url: '<?php echo base_url(); ?>index.php/spd/load_trskpd',
                queryParams: ({
                    kode: kode,
                    jenis: jenis
                })
            });
        }


        function filter_giat() {
            var vgiat = '';
            $('#dg1').edatagrid('selectAll');
            var rows = $('#dg1').edatagrid('getSelections');
            for (var i = 0; i < rows.length; i++) {
                fgiat = "'" + rows[i].kd_sub_kegiatan + "'";
                if (i > 0) {
                    vgiat = vgiat + "," + fgiat;
                } else {
                    vgiat = fgiat;
                }

            }
            var cno = document.getElementById('nomor').value;
            $('#dg1').edatagrid('unselectAll');
            $('#giat').combogrid({
                url: '<?php echo base_url(); ?>index.php/spd/load_trskpd',
                queryParams: ({
                    kode: kode,
                    jenis: jenis,
                    giat: vgiat,
                    no: cno
                })
            });
        }




        function load_detail() {

            var kk = document.getElementById("nomor").value;
            var jenis = document.getElementById('jenis').value;
            var kode = $('#skpd').combogrid('getValue');
            var tgl1 = $('#tanggal').datebox('getValue');
            var bln1 = angka(document.getElementById('bulan1').value);
            var bln2 = angka(document.getElementById('bulan2').value);



            $('#dg1').edatagrid({
                idField: 'id',
                rownumbers: "true",
                fitColumns: "true",
                singleSelect: "true",
                autoRowHeight: "false",
                pagination: "true",
                pageList: [10, 20, 30, 40, 50, 100, 300],
                nowrap: "true",
                url: '<?php echo base_url(); ?>/index.php/spd/load_dspd_ag_pembiayaan',
                queryParams: ({
                    no: kk,
                    jenis: jenis,
                    skpd: kode,
                    tgl: tgl1,
                    cbln1: bln1,
                    cbln2: bln2
                })
            });
            //bbb set_grid();
            $('#dg1').edatagrid('reload');
            get_total();

        }



        function jumlah_detail() {
            var kk = document.getElementById("nomor").value;

            $.ajax({
                url: '<?php echo base_url(); ?>index.php/spd/jumlah_detail_spd/',
                type: "POST",
                dataType: "json",
                data: ({
                    cno_spd: kk
                }),
                success: function(data) {
                    $("#total").attr("value", number_format(data.total, 2, '.', ','));
                    $("#total1").attr("value", number_format(data.total, 2, '.', ','));
                }
            });
        }


        function load_detail_kosong() {
            var no_kos = '';
            $('#dg1').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/spd/load_dspd_kosong',
                queryParams: ({
                    no: no_kos
                })
                /*   columns:[[                
                    {field:'no_spd',
                  title:'Nomor SPD',        
                      hidden:"true"},                
                      {field:'kd_sub_kegiatan', 
                  title:'Kode Kegiatan',
                  width:160},
                      {field:'nm_sub_kegiatan',
                  title:'Nama Kegiatan',
                  width:280},
                      {field:'nilai',
                  title:'Nilai Rupiah',
                  width:130,
                      align:"right"},
                      {field:'lalu',
                  title:'Telah Di SPD kan',
                  width:130,
                      align:"right"},
                      {field:'anggaran',
                  title:'anggaran',
                  width:130,
                      align:"right"},
                    {field:'kd_program',
                  title:'Kode Program',       
                      hidden:"true",  
                      width:0},
                    {field:'nm_program',
                  title:'Nama Program',       
                      hidden:"true",
                      width:0}                
                  ]] */
            });
            var jenis = document.getElementById('jenis').value;
            set_grid();
        }

        function set_numspd(skpd) {
            var skpdss = $('#skpd').combogrid('getValue');
            $("#nomor").attr("value", '/' + skpdss + '/M/1/' + tahun_anggaran);
        }

        function load_detail2() {
            $('#dg1').edatagrid('selectAll');
            var rows = $('#dg1').edatagrid('getSelections');
            for (var p = 0; p < rows.length; p++) {
                no = rows[p].no_spd;
                giat = rows[p].kd_sub_kegiatan;
                nmgiat = rows[p].nm_sub_kegiatan;
                prog = rows[p].kd_program;
                nmprog = rows[p].nm_program;
                nil = rows[p].nilai;
                lal = rows[p].lalu;
                ang = rows[p].anggaran;
                $('#dg2').edatagrid('appendRow', {
                    no_spd: no,
                    kd_sub_kegiatan: giat,
                    nm_sub_kegiatan: nmgiat,
                    nilai: nil,
                    lalu: lal,
                    anggaran: ang,
                    kd_program: prog,
                    nm_program: nmprog
                });
            }
            $('#dg1').edatagrid('unselectAll');
        }

        function set_grid() {
            $('#dg1').edatagrid({
                columns: [
                    [{
                            field: 'id',
                            title: 'id',
                            width: 10,
                            hidden: "true"
                        },
                        {
                            field: 'no_spd',
                            title: 'Nomor SPD',
                            hidden: "true"
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'Kode Kegiatan',
                            width: 160
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Sub Kegiatan',
                            width: 280
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Rekening',
                            width: 280
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 280
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai Rupiah',
                            width: 130,
                            align: "right"
                        },
                        {
                            field: 'lalu',
                            title: 'Telah Di SPD kan',
                            width: 130,
                            align: "right"
                        },
                        {
                            field: 'anggaran',
                            title: 'anggaran',
                            width: 130,
                            align: "right"
                        },
                        {
                            field: 'kd_program',
                            title: 'Kode Program',
                            hidden: "true",
                            width: 0
                        },
                        {
                            field: 'nm_program',
                            title: 'Nama Program',
                            hidden: "true",
                            width: 0
                        },

                    ]
                ],
                onLoadSuccess: function(data) {},
                onDblClickRow: function(rowIndex, rowData) {
                    kdkegiatan = rowData.kd_sub_kegiatan;
                    nmkegiatan = rowData.nm_sub_kegiatan;
                    nlalu = rowData.lalu;
                    nilai1 = rowData.nilai;
                    nilai_ag = rowData.anggaran;
                    edit_rekening(kdkegiatan, nmkegiatan, nilai1, nlalu, nilai_ag, '', '');
                }
            });
        }

        function set_grid_rek() {
            $('#dg1').datagrid({
                columns: [
                    [

                        {
                            field: 'id',
                            title: 'id',
                            width: 10,
                            hidden: "true",
                        },
                        {
                            field: 'no_spd',
                            title: 'Nomor SPD',
                            hidden: "true"
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 160
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 280
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai Rupiah',
                            width: 130,
                            align: "right"
                        },
                        {
                            field: 'kd_sub_kegiatan',
                            title: 'Kode Kegiatan',
                            hidden: "true",
                            width: 160
                        },
                        {
                            field: 'nm_sub_kegiatan',
                            title: 'Nama Kegiatan',
                            hidden: "true",
                            width: 280
                        },
                        {
                            field: 'lalu',
                            title: 'Telah Di SPD kan',
                            width: 130,
                            align: "right"
                        },
                        {
                            field: 'anggaran',
                            title: 'anggaran',
                            width: 130,
                            align: "right"
                        },
                        {
                            field: 'kd_program',
                            title: 'Kode Program',
                            hidden: "true",
                            width: 0
                        },
                        {
                            field: 'nm_program',
                            title: 'Nama Program',
                            hidden: "true",
                            width: 0
                        },

                    ]
                ],
                onLoadSuccess: function(data) {
                    //get_total();
                },
                onDblClickRow: function(rowIndex, rowData) {
                    kdkegiatan = rowData.kd_sub_kegiatan;
                    nmkegiatan = rowData.nm_sub_kegiatan;
                    kdrek = rowData.kd_rek6;
                    nmrek = rowData.nm_rek6;
                    nlalu = rowData.lalu;
                    nilai1 = rowData.nilai;
                    nilai_ag = rowData.anggaran;
                    edit_rekening(kdkegiatan, nmkegiatan, nilai1, nlalu, nilai_ag, kdrek, nmrek);
                }
            });

        }


        function getRowIndex(target) {
            var tr = $(target).closest('tr.datagrid-row');
            return parseInt(tr.attr('datagrid-row-index'));
        }

        function editrow(target) {
            $('#dg1').datagrid('beginEdit', getRowIndex(target));
        }

        function deleterow(target) {
            $.messager.confirm('Confirm', 'Anda Yakin?', function(r) {
                if (r) {
                    $('#dg1').datagrid('deleteRow', getRowIndex(target));
                }
            });
        }

        function saverow(target) {
            var rows = $('#dg1').datagrid('getSelected');
            cnil1 = rows.nilai;
            $('#dg1').datagrid('endEdit', getRowIndex(target));

            var rows = $('#dg1').datagrid('getSelected');
            cnil = rows.nilai;

            totala = angka(document.getElementById('total').value);

            selisih_ag = (angka(rows.anggaran) - (angka(rows.nilai) + angka(rows.lalu)));
            selisih = (angka(cnil1) - angka(cnil));
            cccc = (totala - selisih);

            //$('#total1').attr('value',number_format(total,2,'.',','));    
            $('#total').attr('value', number_format(cccc, "2", ".", ","));

            if (selisih_ag < 0) {
                //$('#total').attr('value',number_format(0,"2",".",","));
                rows.nilai = 0;
                alert('Nilai Melebihi Anggaran');
                //saverow(this);
                //                exit(); 
            }

        }

        function reject(target) {
            $('#dg1').datagrid('rejectChanges');
            target = undefined;
        }


        function cancelrow(target) {
            $('#dg1').datagrid('cancelEdit', getRowIndex(target));
        }






        function section1() {
            $(document).ready(function() {
                $('#section1').click();
                $('#dg').edatagrid('reload');
                $("#dialog-cetak").dialog('close');
            });

        }


        function section2() {
            $(document).ready(function() {
                $('#section2').click();
                document.getElementById("nomor").focus();
            });

        }


        function get(nomor, tgl, kode, nama, bulan1, bulan2, jns, tot, ketentuan, nip, st_b) {
            $("#nomor").attr("value", nomor);
            $("#nomor_hide").attr("value", nomor);
            $("#tanggal").datebox("setValue", tgl);

            $("#skpd").combogrid("setValue", kode);
            $("#bendahara").combogrid("setValue", nip);
            $("#nmskpd").attr("value", nama);
            $("#ketentuan").attr("value", ketentuan);
            $("#bulan1").attr("value", bulan1);
            $("#bulan2").attr("value", bulan2);
            $("#jenis").attr("value", jns);
            //alert(tot);
            //$("#total").attr("value",tot);     
            if (st_b == '1') {
                document.getElementById("p1").innerHTML = "SPD AKTIF";
            } else {
                document.getElementById("p1").innerHTML = "SPD NON AKTIF";
            }
            //        $("#total1").attr("value",number_format(tot,2,'.',',');     
        }

        function kosong() {
            status_spd = 0;
            cdate = '<?php echo date("Y-m-d"); ?>';
            s_tox = 'tambah';
            $("#nomor").attr("value", '903//PEMBIAYAAN/BKAD-B/' + tahun_anggaran);
            set_numspd();
            $("#nomor_hide").attr("value", '');
            $("#tanggal").datebox("setValue", cdate);
            $("#skpds").combogrid("setValue", '');
            $("#bendahara").combogrid("setValue", '');
            //$("#skpds").combogrid("setValue",'');
            $("#nmskpds").attr("value", '');
            //$("#bulan1").attr("value",'');
            //$("#bulan2").attr("value",'');
            $("#bulan1").attr("value", 0);
            $("#bulan2").attr("value", 0);

            $("#jenis").attr("value", '6');
            $("#ketentuan").attr("value", '');
            $("#bendahara").attr("value", '');
            $("#ketentuan").attr("value", '');
            $("#pengajuan").attr("value", '');
            var kode = '';
            var nomor = '';
            $('#giat').combogrid('setValue', '');
            $('#nilai').attr('value', '0');
            $('#total').attr('value', '0');
            tombol(status_spd);
            load_detail_kosong();
            document.getElementById("nomor").focus();
        }




        function aktif_spd() {
            var cno = document.getElementById('nomor').value;
            var cno_hide = document.getElementById('nomor_hide').value;
            var cskpd = $('#skpd').combogrid('getValue');
            var status_spd_aktif = 0;
            var status_cek = 0;
            //$(document).ready(function(){


            $(document).ready(function() {
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    data: ({
                        tabel: 'trhspp',
                        field: 'no_spd',
                        no: cno
                    }),
                    url: '<?php echo base_url(); ?>/index.php/spd/cek_simpan_spd',
                    success: function(data) {
                        status_cek = data.pesan;
                        if (status_spd == '1') {

                            if (status_cek == 1) {
                                alert("Nomor SPD Telah Dipakai di SPP tidak bisa di Non Aktifkan! ");
                                return;
                                //abort();

                            };
                        } else {
                            status_spd_aktif = 1;
                        }

                        $.ajax({
                            type: "POST",
                            dataType: 'json',
                            data: ({
                                tabel: 'trhspd',
                                mode_tox: 'edit',
                                no: cno,
                                status_spd: status_spd_aktif,
                                kd_skpd: cskpd
                            }),
                            url: '<?php echo base_url(); ?>/index.php/spd/update_sts_spd',
                            success: function(data) {
                                status = data;

                                if (status == '5') {
                                    alert('Data Gagal Tersimpan...!!!');
                                } else {
                                    if (status == '1') {
                                        $('#dg').edatagrid('reload');
                                        document.getElementById("id_aktif").innerHTML = "NON Aktifkan SPD";
                                        document.getElementById("p1").innerHTML = "SPD AKTIF";
                                        status_spd = 1;
                                        tombol(status_spd);
                                        //  section1();
                                    } else {

                                        $('#dg').edatagrid('reload');
                                        document.getElementById("id_aktif").innerHTML = "Aktifkan SPD";
                                        document.getElementById("p1").innerHTML = "SPD NON AKTIF";
                                        status_spd = 0;
                                        tombol(status_spd);
                                        //  section1();

                                    }
                                }


                            }
                        });

                    }
                });
            });

        }


        function kosong2() {
            $('#giat').combogrid('setValue', '');
            $('#nmgiat').attr('value', '');
            $('#anggaran').attr('value', '0');
            $('#lalu').attr('value', '0');
            $('#nilai').attr('value', '0');
        }

        function cari() {
            var kriteria = document.getElementById("txtcari").value;
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/spd/load_spd_pembiayaan',
                    queryParams: ({
                        cari: kriteria
                    })
                });
            });
        }


        function validate1() {

            var jenis = document.getElementById('jenis').value;
            var bln1 = document.getElementById('bulan1').value;
            var kode = $('#skpd').combogrid('getValue');
            var cno = document.getElementById('nomor').value;
            $("#bulan2").attr("value", bln1);


            $(function() {
                $('#dg1').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/spd/load_dspd_all_keg/' + jenis + '/' + kode + '/' + bln1 + '/' + bln1 + '/' + cno,
                    idField: 'id',
                    toolbar: "#toolbar",
                    rownumbers: "true",
                    fitColumns: "true",
                    singleSelect: "true",
                    showFooter: true,
                    nowrap: false
                });
            });
            (jenis == '61') ? set_grid_rek(): set_grid();

            //      set_grid();
            $('#dg1').edatagrid('reload');
        }


        function validate2() {
            //var jenis = document.getElementById('jenis').value; 
            var bln1 = angka(document.getElementById('bulan1').value);
            var bln2 = angka(document.getElementById('bulan2').value);
            var kode = $('#skpd').combogrid('getValue');
            var cno = document.getElementById('nomor').value;
            var cnomor = cno.split("/").join("123456789");
            var tgl1 = $('#tanggal').datebox('getValue');

            if (bln2 < bln1) {
                alert('Bulan Akhir tidak bisa lebih kecil dari Bulan awal');
                $("#bulan2").attr("value", bln1);
                bln2 = bln1;
            }

            //$(function(){
            $(document).ready(function() {
                $('#dg1').edatagrid({
                    url: '<?php echo base_url(); ?>index.php/spd/load_dspd_pembiayaan/6/' + kode + '/' + bln1 + '/' + bln2 + '/' + cnomor,
                    queryParams: ({
                        tgl: tgl1,
                        cbln1: bln1,
                        cbln2: bln2
                    }),
                    idField: 'id',
                    toolbar: "#toolbar",
                    rownumbers: "true",
                    fitColumns: "true",
                    singleSelect: "true",
                    pagination: "true",
                    pageList: [10, 20, 30, 40, 50, 100, 300],
                    showFooter: true,
                    nowrap: false
                });

                //set_grid();
            });
            //set_grid();
            $('#dg1').edatagrid('reload');
            // get_total();


        }


        function get_total() {
            var bln1 = document.getElementById('bulan1').value;
            var bln2 = document.getElementById('bulan2').value;
            var kode = $('#skpd').combogrid('getValue');
            var cno = document.getElementById('nomor').value;
            var tgl1 = $('#tanggal').datebox('getValue');
            var cnomor = cno.split("/").join("123456789");
            $.ajax({
                url: '<?php echo base_url(); ?>/index.php/spd/load_tot_dspd_pembiayaan/6/' + kode + '/' + bln1 + '/' + bln2 + '/' + cnomor + '/' + tgl1,
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#total").attr("value", number_format(data.nilai, 2, '.', ','));
                    $("#total1").attr("value", number_format(data.nilai, 2, '.', ','));
                }
            });
        }


        function append_save() {

            // alert('append');  
            var nomor = document.getElementById('nomor').value;
            var nama = document.getElementById('nmgiat').value;
            var namaprog = document.getElementById('nmprog').value;
            var kdprog = document.getElementById('prog').value;
            var nil = angka(document.getElementById('nilai').value);
            var ang = angka(document.getElementById('anggaran').value);
            var lal = angka(document.getElementById('lalu').value);
            var tot1 = angka(document.getElementById('total').value);
            var giat = $('#giat').combogrid('getValue');
            var tot2 = 0;

            sisa_spd();

            if (giat != '' && nil != 0 && ang != 0) {
                tot2 = tot1 + nil;
                nil = number_format(nil, 2, '.', ',');
                lal = number_format(lal, 2, '.', ',');
                ang = number_format(ang, 2, '.', ',');
                $('#dg1').datagrid('appendRow', {
                    no_spd: nomor,
                    kd_sub_kegiatan: giat,
                    nm_sub_kegiatan: nama,
                    nilai: nil,
                    anggaran: ang,
                    lalu: lal,
                    kd_program: kdprog,
                    nm_program: namaprog
                });
                $('#dg2').datagrid('appendRow', {
                    no_spd: nomor,
                    kd_sub_kegiatan: giat,
                    nm_sub_kegiatan: nama,
                    nilai: nil,
                    anggaran: ang,
                    lalu: lal,
                    kd_program: kdprog,
                    nm_program: namaprog
                });
                $('#total').attr('value', number_format(tot2, 2, '.', ','));
                $('#total1').attr('value', number_format(tot2, 2, '.', ','));
                filter_giat();
                kosong2();
            }

        }

        function cetak() {
            var nomor = document.getElementById('nomor').value;

            $("#dialog-cetak").dialog('open');
            $('#nomor1').attr('value', nomor);
            $('#chk_spd').attr('checked', false);
            $('#chk_tambah').attr('checked', false);
            $('#cetak').attr('checked', false);
        }

        function opt(val) {
            ctks = val;
        }

        function cetak_spd(ctk) {
            var cell = document.getElementById('cell').value;
            var no_spd = document.getElementById('nomor1').value;
            var nipppkd = $('#bendahara_ppkd').combogrid('getValue');
            var nmppkd = document.getElementById('nama_ppkd').value;
            var nipppkds = nipppkd.split(" ").join("123456789");

            var nospd = no_spd.split("/").join("spd"); //,"");
            var tnp_no = 0;
            var tambah = 0;
            var cell = document.getElementById('cell').value;

            if ($('#chk_spd').is(":checked")) {
                tnp_no = 1;
            }

            if ($('#chk_tambah').is(":checked")) {
                tambah = 'Tambahan';
            }



            if (ctks == '2') {
                var url = "<?php echo base_url(); ?>spd/cetak_lampiran_spd_biaya/";
                window.open(url + '/' + ctk + '/' + tnp_no + '/' + tambah + '/' + cell + '/' + nospd + '/' + nipppkds, '_blank');
                window.focus();
            } else {
                var url = "<?php echo base_url(); ?>spd/cetak_otor_spd_biaya/";
                window.open(url + '/' + ctk + '/' + tnp_no + '/' + tambah + '/' + cell + '/' + nospd + '/' + nipppkds, '_blank');
                window.focus();
            }
        }
        // function opt(val){       

        //     ctk = val;
        // var tnp_no=0;   
        //     var tambah = 0; 
        //     var cell = document.getElementById('cell').value; 
        // if ($('#chk_spd').is(":checked")){
        //   tnp_no=1;
        // }

        // if ($('#chk_tambah').is(":checked")){
        //   tambah='Tambahan';
        // }

        //     if (ctk=='1'){
        //         urll ='<?php echo base_url(); ?>cetak_lampiran_spd1/1/'+tnp_no+'/'+tambah+'/'+cell;
        //     } else if (ctk=='2'){
        //         urll ='<?php echo base_url(); ?>cetak_lampiran_spd1/1/'+tnp_no+'/'+tambah+'/'+cell;
        //     } else if (ctk=='3'){
        //         urll ='<?php echo base_url(); ?>cetak_otor_spd/1/'+tnp_no+'/'+tambah+'/'+cell;
        //     } else if (ctk=='4'){
        //         urll ='<?php echo base_url(); ?>cetak_otor_spd/1/'+tnp_no+'/'+tambah+'/'+cell;
        //     } else {
        //         exit();
        //     }             
        //     $('#frm_ctk').attr('action',urll);                        
        // }      

        function submit(val) {

            mode_ctk = val;
            //echo mode_ctk;   
            /* if (ctk==''){
                    alert('Pilih Jenis Cektakan');
                    exit();
                } */

            var xxx = $('input[name="cetak"]:checked', '#frm_ctk').val();

            opt(xxx);
            document.getElementById("frm_ctk").submit();
        }

        function tambah() {
            var kd = $('#skpd').combogrid('getValue');
            var tgl = $('#tanggal').datebox('getValue');
            var total = document.getElementById('total').value;
            $('#dg2').edatagrid('reload');
            if (kd != '' && tgl != '') {
                filter_giat();
                kosong2();
                $("#dialog-modal").dialog('open');
                $('#total1').attr('value', total);
                load_detail2();
            } else {
                alert('Harap Isi Kode SKPD dan Tanggal SPD');
            }
        }

        function keluar() {
            $("#dialog-modal").dialog('close');
            $("#dialog-cetak").dialog('close');
            kosong2();
        }

        function hapus_giat() {
            var rows = $('#dg1').edatagrid('getSelected');
            var idx = $('#dg1').edatagrid('getRowIndex', rows);
            nilx = angka(rows.nilai);
            tot3 = 0;
            var tot = angka(document.getElementById('total').value);
            tot3 = tot - nilx;
            $('#total').attr('value', number_format(tot3), 2, '.', ',');
            $('#total1').attr('value', number_format(tot3), 2, '.', ',');
            $('#dg1').datagrid('deleteRow', idx);
        }

        function hapus_detail() {
            var rows = $('#dg2').edatagrid('getSelected');
            cgiat = rows.kd_sub_kegiatan;
            cnil = rows.nilai;
            var idx = $('#dg2').edatagrid('getRowIndex', rows);
            var tny = confirm('Yakin Ingin Menghapus Data, Kegiatan : ' + cgiat + ' ,Nilai : ' + cnil);
            if (tny == true) {
                $('#dg2').edatagrid('deleteRow', idx);
                $('#dg1').edatagrid('deleteRow', idx);
                total = angka(document.getElementById('total1').value) - angka(cnil);
                $('#total1').attr('value', number_format(total, 2, '.', ','));
                $('#total').attr('value', number_format(total, 2, '.', ','));
                kosong2();
            }

        }

        function hapus() {
            var cnomor = document.getElementById('nomor').value;
            var urll = '<?php echo base_url(); ?>index.php/spd/hapus_spd';
            var tny = confirm('Yakin Ingin Menghapus Data, Nomor SPD : ' + cnomor);
            if (tny == true) {
                $(document).ready(function() {
                    $.ajax({
                        url: urll,
                        dataType: 'json',
                        type: "POST",
                        data: ({
                            no: cnomor
                        }),
                        success: function(data) {
                            status = data.pesan;
                            if (status == '1') {
                                alert('Data Berhasil Terhapus...!!!');
                                $('#dg').edatagrid('reload');
                            } else {
                                alert('Gagal Hapus...!!!');
                            }
                        }

                    });
                });
            }
        }

        function cek_spd() {
            var cno = document.getElementById('nomor').value;
            var cno_hide = document.getElementById('nomor_hide').value;
            if (cno != cno_hide) {
                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: ({
                            tabel: 'trhspd',
                            field: 'no_spd',
                            no: cno
                        }),
                        url: '<?php echo base_url(); ?>/index.php/spd/cek_simpan',
                        success: function(data) {
                            status_cek = data.pesan;
                            if (status_cek == 1) {
                                alert("Nomor SPD Sudah Ada! Silakan ubah nomor spd sebelum disimpan");

                                document.getElementById("nomor").focus();
                                status = '0';
                                $("#id_status").attr("value", '0');
                                /*
                                if(cno_hide==''){
                                    $("#nomor").attr("value",'903//BL/BPKAD-B/2016');
                                }else{    
                                    $("#nomor").attr("value",cno_hide);
                                }*/
                                //$('#save').linkbutton('disable');
                                exit();

                            } else {
                                $("#id_status").attr("value", '1');
                            }
                        }
                    });
                });
            } else {
                $("#id_status").attr("value", '1');
            }
        }

        function simpan2() {
            var cno = document.getElementById('nomor').value;
            var cno_hide = document.getElementById('nomor_hide').value;
            var ctgl = $('#tanggal').datebox('getValue');
            var cskpd = $('#skpd').combogrid('getValue');
            var cnmskpd = document.getElementById('nmskpd').value;
            var cbend = $('#bendahara').combogrid('getValue');
            var cbln1 = document.getElementById('bulan1').value;
            var cbln2 = document.getElementById('bulan2').value;
            var cketentuan = document.getElementById('ketentuan').value;
            var cpengajuan = document.getElementById('pengajuan').value;
            var cjenis = document.getElementById('jenis').value;
            var ctotal = angka(document.getElementById('total').value);

            if (s_tox == 'tambah') {
                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: ({
                            tabel: 'trhspd',
                            field: 'no_spd',
                            no: cno
                        }),
                        url: '<?php echo base_url(); ?>/index.php/spd/cek_simpan',
                        success: function(data) {
                            status_cek = data.pesan;
                            if (status_cek == 1) {
                                alert("Nomor Telah Dipakai! _cek");
                                document.getElementById("nomor").focus();
                                exit();
                            } else {
                                //-------------------------------------------------------     
                                $(document).ready(function() {
                                    $.ajax({
                                        type: "POST",
                                        dataType: 'json',
                                        data: ({
                                            tabel: 'trhspd',
                                            mode_tox: 'tambah',
                                            no2: cno,
                                            no: cno,
                                            tgl: ctgl,
                                            skpd: cskpd,
                                            nmskpd: cnmskpd,
                                            bend: cbend,
                                            bln1: cbln1,
                                            bln2: cbln2,
                                            ketentuan: cketentuan,
                                            pengajuan: cpengajuan,
                                            jenis: cjenis,
                                            total: ctotal
                                        }),
                                        url: '<?php echo base_url(); ?>/index.php/spd/simpan_spd',
                                        success: function(data) {
                                            status = data.pesan;
                                        }
                                    });
                                });
                                s_tox = 'edit';
                            }
                        }
                    });
                });
            } else {
                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    data: ({
                        no: cno,
                        tabel: 'trhspd',
                        field: 'no_spd'
                    }),
                    url: '<?php echo base_url(); ?>/index.php/spd/cek_simpan',
                    success: function(data) {
                        status_cek = data.pesan;
                        if (status_cek == 1 && cno != cno_hide) {
                            alert("Nomor Telah Dipakai!");
                            exit();
                        }
                        if (status_cek == 0 || cno == cno_hide) {
                            //alert("Nomor Bisa dipakai _e");


                            //--------
                            $(document).ready(function() {
                                $.ajax({
                                    type: "POST",
                                    dataType: 'json',
                                    data: ({
                                        tabel: 'trhspd',
                                        mode_tox: 'edit',
                                        no2: cno_hide,
                                        no: cno,
                                        tgl: ctgl,
                                        skpd: cskpd,
                                        nmskpd: cnmskpd,
                                        bend: cbend,
                                        bln1: cbln1,
                                        bln2: cbln2,
                                        ketentuan: cketentuan,
                                        pengajuan: cpengajuan,
                                        jenis: cjenis,
                                        total: ctotal
                                    }),
                                    url: '<?php echo base_url(); ?>/index.php/spd/simpan_spd',
                                    success: function(data) {
                                        status = data.pesan;
                                    }
                                });
                            });

                        }
                    }
                });

                // end mode EDIT by tox
            }
            status = document.getElementById('id_status').value;

            if (status == '0') {
                alert('Gagal Simpan...!!');
                exit();
            }
            if (status != '0') {
                $('#dg1').datagrid('selectAll');
                var rows = $('#dg1').datagrid('getSelections');
                alert("Total Data " + rows.length);
                for (var p = 0; p < rows.length; p++) {
                    cnospd = cno;
                    ckdgiat = rows[p].kd_sub_kegiatan;
                    cnmgiat = rows[p].nm_sub_kegiatan;
                    ckdrek = rows[p].kd_rek6;
                    cnmrek = rows[p].nm_rek6;
                    ckdprog = rows[p].kd_program;
                    cnmprog = rows[p].nm_program;
                    cnilai = angka(rows[p].nilai);
                    if (p > 0) {
                        csql = csql + "," + "('" + cnospd + "','" + ckdgiat + "','" + cnmgiat + "','" + ckdrek + "','" + cnmrek + "','" + ckdprog + "','" + cnmprog + "','" + cnilai + "')";
                    } else {
                        csql = "values('" + cnospd + "','" + ckdgiat + "','" + cnmgiat + "','" + ckdrek + "','" + cnmrek + "','" + ckdprog + "','" + cnmprog + "','" + cnilai + "')";
                    }
                }

                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: ({
                            tabel: 'trdspd',
                            no: cno,
                            no2: cno_hide,
                            sql: csql,
                            skpd: cskpd,
                            bln1: cbln1,
                            bln2: cbln2,
                            tgl: ctgl
                        }),
                        url: '<?php echo base_url(); ?>/index.php/spd/simpan_spd',
                        success: function(data) {
                            status = data.pesan;
                            if (status == '1') {
                                alert('Data Berhasil Tersimpan...!!!');
                                $("#nomor_hide").attr("value", cno);
                                $('#dg').edatagrid('reload');
                                section1();
                            } else {
                                alert('Data Gagal Tersimpan...!!!');
                            }
                        }
                    });
                });
            }
        }

        function simpan_spd() {

            var cno = document.getElementById('nomor').value;
            var c_angcno = cno.length;
            if (c_angcno != 38) {
                alert('Silahkan Inputkan SPD sesuai digit yang sudah ditentukan!');
                return;
            }
            var cno_hide = document.getElementById('nomor_hide').value;
            var cek_angkas = document.getElementById('txt_p2').value;
            var ctgl = $('#tanggal').datebox('getValue');
            var cskpd = $('#skpd').combogrid('getValue');
            var cnmskpd = document.getElementById('nmskpd').value;
            var cbend = $('#bendahara').combogrid('getValue');
            var cbln1 = document.getElementById('bulan1').value;
            var cbln2 = document.getElementById('bulan2').value;
            var cketentuan = document.getElementById('ketentuan').value;
            var cpengajuan = document.getElementById('pengajuan').value;
            var cjenis = document.getElementById('jenis').value;
            var ctotal = angka(document.getElementById('total').value);
            if (cno == '') {
                alert('Nomor SPD Tidak Boleh Kosong');
                exit();
            }
            if (ctgl == '') {
                alert('Tanggal SPD Tidak Boleh Kosong');
                exit();
            }
            if (cskpd == '') {
                alert('Kode SKPD Tidak Boleh Kosong');
                exit();
            }

            if (cbend == '') {
                alert('Bendahara Tidak Boleh Kosong');
                exit();
            }

            if (cek_angkas == '1') {
                var cfm = confirm("Anggaran Kas msh selisih! Anda Yakin Simpan?");
                if (cfm == false) {
                    return;
                }
            }

            if (cno != cno_hide) {
                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: ({
                            tabel: 'trhspd',
                            field: 'no_spd',
                            no: cno
                        }),
                        url: '<?php echo base_url(); ?>/index.php/spd/cek_simpan',
                        success: function(data) {
                            status_cek = data.pesan;
                            if (status_cek == 1) {
                                alert("Nomor SPD Sudah Ada! Silakan ubah nomor spd sebelum disimpan");

                                document.getElementById("nomor").focus();
                                status = '0';
                                $("#id_status").attr("value", '0');
                                exit();
                            } else {
                                $("#id_status").attr("value", '1');
                                simpan2();
                            }
                        }
                    });
                });
            } else {
                $("#id_status").attr("value", '1');
                simpan2();
            }
        }


        function spdlalu(cgiat) {
            var dgiat = cgiat;
            var dtgl = $('#tanggal').datebox('getValue');
            $(document).ready(function() {
                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>/index.php/rka/spd_lalu',
                    data: ({
                        cgiat: dgiat,
                        ctgl: dtgl
                    }),
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            cspdLalu = number_format(n['lalu'], 2, '.', ',');
                            $("#lalu").attr("value", cspdLalu);
                        });
                    }
                });
            });

        }

        function sisa_spd() {
            var ang = angka(document.getElementById('anggaran').value);
            var lalu = angka(document.getElementById('lalu').value);
            var nil = angka(document.getElementById('nilai').value);

            sisa = ang - lalu;
            slalu = (sisa - nil);
            if (slalu < 0) {
                alert('Nilai Melebihi SPD Lalu');
                exit();
            }
        }


        function tes() {
            urrl = '<?php echo base_url(); ?>/index.php/rka/sql_tes'
            $(document).ready(function() {
                $.post(urrl, ({
                    no: '1'
                }), function(data) {
                    status = data;
                    if (status == '1') {
                        alert('ok');
                    } else {
                        alert(status);
                    }
                });
            });
        }


        function bend(c) {
            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        skpd: c
                    }),
                    url: "<?php echo base_url(); ?>index.php/rka/load_bendahara_p",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#bendahara").attr("value", n['nama']);
                        });
                    }
                });
            });
        }

        function edit_rekening(kdkegiatan, nmkegiatan, nilai1, nlalu, nilai_ag, kdrek, nmrek) {

            $("#nm_rek_kegi").attr("Value", nmkegiatan);
            $("#rek_kegi").attr("disable");
            $("#rek_kegi").attr("Value", kdkegiatan);
            $("#real_keg").attr("Value", '999,999,999,999,999,999.00');
            $("#txtanggkas").attr("Value", '0.00');
            $("#rek_rek").attr("disable");
            $("#rek_rek").attr("Value", kdrek);
            $("#nm_rek_rek").attr("Value", nmrek);
            $("#rek_nilai").attr("Value", number_format((angka(nilai1)), 2, '.', ','));
            $("#rek_nilai_lalu").attr("Value", number_format((angka(nlalu)), 2, '.', ','));

            $("#rek_nilai_anggaran").attr("Value", number_format((angka(nilai_ag)), 2, '.', ','));

            var vnilai7 = angka(nilai_ag) - angka(nlalu);
            var sisa = number_format(vnilai7, 2, '.', ',');

            $("#rek_sisa").attr("Value", sisa);
            get_realisasi_spd();
            get_anggkas_keg();

            /*       var xx=angka(nilai1);
                   xx7=number_format(xx,2,'.',',');      
                       $("#rek_nilai").attr("Value",xx7);
             */
            $("#dialog-edit_nilai").dialog('open');

        }

        function update_save() {
            // alert('update');

            // $('#dg1').datagrid('selectAll');
            // var rows  = $('#dg1').datagrid('getSelections') ;
            var nilai_awal = nilx; //rows.nilai;
            $('#dg1').datagrid('selectAll');
            var rows = $('#dg1').datagrid('getSelected');
            jgrid = rows.length;

            var jumtotal = document.getElementById('total').value;
            jumtotal = angka(jumtotal);

            var cnil = document.getElementById('rek_nilai').value;

            var cnilai = cnil;

            var cnil_lalu = angka(document.getElementById('rek_nilai_lalu').value);
            var cnil_sisa = angka(document.getElementById('rek_sisa').value);
            var cnil_input = angka(document.getElementById('rek_nilai').value);
            var realisasi_spd = angka(document.getElementById('real_keg').value);
            var anggkas = angka(document.getElementById('txtanggkas').value);
            var n_akuspd = cnil_lalu + cnil_input;


            if (cnil_input > cnil_sisa) {
                alert('Nilai Melebihi nilai SISA ANGGARAN...!!!, Cek Lagi...!!!');
                exit();
            }

            if (n_akuspd < realisasi_spd) {
                alert('Nilai spd kurang dari nilai kemungkinan Realisasi...!!!, Cek Lagi...!!!');
                return;
            }

            /*  
                  if ( cnil_input > anggkas ){
                       alert('Nilai spd melebihi Anggaran Kas yang sudah diinput...!!!, Cek Lagi...!!!') ;
                       return;
                  }
            */

            if (cnilai == '') {
                cnilai = '0.00';
                cnil = '0.00';
            }
            /*if ( cnil_input == 0 ){
                 alert('Nilai Nol.....!!!, Cek Lagi...!!!') ;
                 exit();
            }*/

            pidx = jgrid;
            pidx = pidx + 1;
            $('#dg1').datagrid('updateRow', {
                index: idx,
                row: {
                    nilai: cnilai,
                    idx: idx
                }
            });

            $("#dialog-edit_nilai").dialog('close');

            jumtotal = (jumtotal + angka(cnil)) - angka(nilai_awal);


            $("#total").attr('value', number_format(jumtotal, 2, '.', ','));
            $("#total1").attr('value', number_format(jumtotal, 2, '.', ','));
            $("#dg1").datagrid("unselectAll");

        }

        function keluar_rek() {
            $("#dialog-edit_nilai").dialog('close');
            $("#dg1").datagrid("unselectAll");
            $("#rek_nilai").attr("Value", 0);
            $("#rek_nilai_lalu").attr("Value", 0);
            $("#rek_nilai_anggaran").attr("Value", 0);
            $("#rek_sisa").attr("Value", 0);
        }


        function tombol(st) {
            if (st == '1') {
                $('#save').linkbutton('disable');
                $('#del').linkbutton('disable');
                $('#poto').linkbutton('disable');
                $("#bulan1").attr("disabled", true);
                $("#bulan2").attr("disabled", true);
                $("#nomor").attr("disabled", true);
                $("#nomor_urut").attr("disabled", true);
                document.getElementById("id_aktif").innerHTML = "NON Aktifkan SPD";
                document.getElementById("p1").innerHTML = "SPD AKTIF";
            } else {
                $('#save').linkbutton('disable');
                $('#del').linkbutton('enable');
                $('#poto').linkbutton('enable');
                $("#bulan1").attr("disabled", false);
                $("#bulan2").attr("disabled", false);
                $("#nomor").attr("disabled", false);
                $("#nomor_urut").attr("disabled", false);
                document.getElementById("id_aktif").innerHTML = "Aktifkan SPD";
                document.getElementById("p1").innerHTML = "SPD NON AKTIF";
            }
        }


        function nomor_sisip() {
            var no_spd_lengkap = '';
            var con_dpn = '903/';
            var con_blk_btl = 'XXXXXX/KODE SKPD/M/1/' + tahun_anggaran;
            var con_blk_bl = '/BELANJA/BKAD-B/' + tahun_anggaran;

            var jenis = document.getElementById('jenis').value;
            var no_urut = (document.getElementById('nomor_urut').value);

            (jenis == '61') ? no_spd_lengkap = con_dpn + no_urut + con_blk_btl: no_spd_lengkap = con_dpn + no_urut + con_blk_bl;

            $("#nomor").attr('value', no_spd_lengkap);
        }

        function tampil_semua() {
            $('#dg1').edatagrid({
                idField: 'id',
                rownumbers: "true",
                fitColumns: "true",
                singleSelect: "true",
                autoRowHeight: "false",
                pagination: "true",
                pageList: [300],
                onLoadSuccess: function(data) {
                    cek_total_grid();
                }
            });
            alert("Tunggu Sebentar.... Sampai Tabel Dibawah Berwana Putih!!");
            if (document.getElementById("id_aktif").innerHTML == "Aktifkan SPD") {
                $('#save').linkbutton('enable');
            }
            $('#save').linkbutton('enable');

            //cek_spd();
        }


        function cek_total_grid() {
            var data = $('#dg1').datagrid('getData');
            var rows = data.rows;
            var sum = 0;

            for (i = 0; i < rows.length; i++) {
                sum += angka(rows[i].nilai);
            }
            $("#total").attr("value", number_format(sum, 2, '.', ','));
        }

        function get_realisasi_spd() {
            var a = $('#skpd').combogrid('getValue');
            var b = document.getElementById('rek_kegi').value;
            var bln2 = angka(document.getElementById('bulan2').value);
            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        skpd: a,
                        keg: b,
                        cbln2: bln2
                    }),
                    url: "<?php echo base_url(); ?>index.php/spd/get_realisasi_keg_spd",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#real_keg").attr("value", n['nrealisasi']);
                        });
                    }
                });
            });
        }


        function get_anggkas_keg() {
            var a = $('#skpd').combogrid('getValue');
            var b = document.getElementById('rek_kegi').value;
            var bln1 = angka(document.getElementById('bulan1').value);
            var bln2 = angka(document.getElementById('bulan2').value);
            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        skpd: a,
                        keg: b,
                        cbln2: bln2,
                        cbln1: bln1
                    }),
                    url: "<?php echo base_url(); ?>index.php/spd/get_anggkas_keg_pembiayaan",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#txtanggkas").attr("value", n['vanggkas']);
                        });
                    }
                });
            });
        }

        function cekbln_akhir(kd_s) {
            var a = kd_s;
            var b = '5';
            //var bln1  = angka(document.getElementById('bulan1').value);
            $(function() {
                $.ajax({
                    type: 'POST',
                    data: ({
                        skpd: a,
                        jenis: b
                    }),
                    url: "<?php echo base_url(); ?>index.php/rka/bln_spdakhir",
                    dataType: "json",
                    success: function(data) {
                        $.each(data, function(i, n) {
                            $("#txtblnakhir").attr("value", n['cbulan_akhir']);
                        });
                    }
                });
            });
        }

        function cekspd2() {
            var bln1 = angka(document.getElementById('bulan1').value);
            var blnakhir = angka(document.getElementById('txtblnakhir').value);
            var nomspd = document.getElementById('nomor').value;
            // var nomorspd = nomspd.substr(1, 4);
            var lens = nomspd.length;
            var res = nomspd.substr(0, lens - 6);
            bulannom = 0;
            // alert(bln1);

            var bulannom = bln1;


            $("#nomor").attr("value", '');
            $("#nomor").attr("value", res + bulannom + '/' + tahun_anggaran);
            /*if(bln1 <= blnakhir){
              /*if(bln1 <= blnakhir){
                  alert("Periode bulan ini sudah di SPD kan!!");
                  $("#bulan1").attr("value",0);
                  return;
              }*/
        }

        function cek_anggaran_kas() {
            var cskpd = $('#skpd').combogrid('getValue');
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/spd/cek_anggaran_kas_spd/',
                type: "POST",
                dataType: "json",
                data: ({
                    skpd: cskpd
                }),
                success: function(data) {
                    jmlh = data;
                    if (jmlh > 0) {
                        document.getElementById("p2").innerHTML = "ADA " + jmlh + " KEGIATAN ANGGARAN KAS MASIH SELISIH DI SKPD INI";
                        $("#txt_p2").attr("value", '1');
                    } else {
                        document.getElementById("p2").innerHTML = "";
                        $("#txt_p2").attr("value", '0');
                    }
                }
            });
        }
    </script>

</head>

<body>



    <div id="content">
        <div id="accordion">
            <h3><a href="#" id="section1">SPD PEMBIAYAAN</a></h3>
            <div>
                <p align="right">
                    <!-- <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:kosong();section2();">Tambah</a> -->
                    <button type="submit" value="+ Tambah" onclick="javascript:kosong();section2();"><i class="fa fa-plus"></i> Tambah</button>
                    <!-- <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a> -->
                    <button type="primary" onclick="javascript:cari();"><i class="fa fa-search"></i></button>
                    <input type="text" value="" id="txtcari" />
                </p>
                <p>
                    <input id="skpds" name="skpds" style="width: 870px;" />
                </p>
                <p>
                <table id="dg" title="List SPD" style="width:870px;height:450px;">
                </table>
                </p>
            </div>

            <h3>
                <a href="#" id="section2">
                    <b id="p1" style="font-size:17px;color: red;"></b><br />
                    <b id="p2" style="font-size:17px;color: #4CAF50;"></b>
                    <input type="hidden" readonly="true" style="border:hidden" id="txt_p2" name="txt_p2" value="0" />
                </a>
            </h3>
            <div style="height: 350px;">
                <p>
                <table align="center" border='1' style="width:870px;">

                    <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
                        <td colspan="5" style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">&nbsp;</td>
                    </tr>

                    <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">S K P D</td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"><input id="skpd" name="skpd" style="width: 250px;" onchange="javascript:kosong();" /></td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Nama SKPD </td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">&nbsp;&nbsp;<input type="text" id="nmskpd" style="border:0;width: 300px;" readonly="true" /></td>
                    </tr>
                    <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">No. S P D</td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">
                            <input type="text" id="nomor" style="width: 245px;" />
                            <input type="hidden" id="nomor_hide" style="width: 20px;" onclick="javascript:select();" readonly="true" />
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Tanggal SPD</td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">&nbsp;&nbsp;<input type="text" id="tanggal" style="width: 140px;" /></td>
                    </tr>

                    <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;font-weight: bold;color: red;">XXXXXX/KODE SKPD/M/1/2023</td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;"></td>
                    </tr>


                    <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Atas Beban</td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">
                            <select name="jenis" id="jenis" disabled>
                                <option value="6">PEMBIAYAAN</option>
                        </td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;"></td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Kebutuhan Bulan
                            <input type="hidden" id="txtblnakhir" style="width: 20px;" readonly="true" />
                        </td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;"><?php echo $this->rka_model->combo_bulan('bulan1', 'onchange="javascript:cekspd2();"'); ?> s/d <?php echo $this->rka_model->combo_bulan('bulan2', 'onchange="javascript:validate2();"'); ?>
                            <!--<input type="text" id="txtblnakhir" style="width: 140px;" /></td> -->
                        </td>

                    </tr>
                    <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Kepala SKPD</td>
                        <td colspan="4" style="border-bottom-style:hidden;padding:5px;border-spacing:5px 5px 5px 5px;">
                            <input type="text" name="nip" id="bendahara" style="width:245px" /> &nbsp;
                            <input type="input" readonly="true" style="border:hidden" id="nama_bend" name="nama_bend" style="width:300px" />
                            <input type="hidden" readonly="true" style="border:hidden" id="id_status" name="id_status" value="1" />
                        </td>

                    </tr>
                    <tr style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;">
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;border-right-style:hidden;">Ketentuan Lain</td>
                        <td style="border-bottom-style:hidden;padding:3px;border-spacing:5px 5px 5px 5px;" colspan="4"><textarea type="text" id="ketentuan" cols="100" rows="2"></textarea><br /><input hidden=true type="text" id="pengajuan" /></td>
                    </tr>


                    <tr style="padding:3px;border-spacing:5px 5px 5px 5px;">
                        <td style="padding:3px;border-spacing:5px 5px 5px 5px;border-bottom-style:hidden;" colspan="5" align="right">
                            <a class="easyui-linkbutton" plain="true" onclick="javascript:aktif_spd();" align="left">
                                <font color='#4CAF50'><i class="fa fa-check-square"></i></font> <b id="id_aktif"></b>
                            </a>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


                            <a class="easyui-linkbutton" type="submit" plain="true" onclick="javascript:kosong();">
                                <font color='#4CAF50'><i class="fa fa-plus"></i></font> Tambah
                            </a>
                            <a class="easyui-linkbutton" plain="true" onclick="javascript:tampil_semua();">
                                <font color='#38a2ff'><i class="fa fa-search"></i></font> Tampil Semua
                            </a>
                            <a id="save" class="easyui-linkbutton" plain="true" onclick="javascript:simpan_spd();">
                                <font color='#38a2ff'><i class="fa fa-save"></i></font> Simpan
                            </a>
                            <a id="del" class="easyui-linkbutton" plain="true" onclick="javascript:hapus();section1();">
                                <font color='red'><i class="fa fa-window-close"></i></font> Hapus
                            </a>
                            <a id="cetak" class="easyui-linkbutton" plain="true" onclick="javascript:cetak();">
                                <font color='#38a2ff'><i class="fa fa-print"></i></font> Cetak
                            </a>
                            <a class="easyui-linkbutton" plain="true" onclick="javascript:section1();">
                                <font color='orange'><i class="fa fa-arrow-left"></i></font> Kembali
                            </a>
                        </td>
                    </tr>

                    <tr style="padding:3px;border-spacing:5px 5px 5px 5px;">
                        <td colspan="5" style="padding:3px;border-spacing:5px 5px 5px 5px;border-bottom-color:black;">&nbsp;</td>
                    </tr>


                </table>

                <table id="dg1" title="Kegiatan S P D" style="width:870px;height:600px;">
                </table>
                <div id="toolbar" align="right">

                </div>
                <table align="center" style="width:100%;">
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td align="right">Total : <input type="text" id="total" style="font-size: large;border:0;width: 200px;text-align: right;" readonly="true" /></td>
                    </tr>
                </table>

                </p>
            </div>

        </div>
    </div>


    <div id="dialog-modal" title="Input Kegiatan">
        <p class="validateTips">Semua Inputan Harus Di Isi.</p>
        <fieldset>
            <table>
                <tr>
                    <td width="15px">Kode Kegiatan</td>
                    <td>:</td>
                    <td><input id="giat" name="giat" style="width: 200px;" /></td>
                </tr>
                <tr>
                    <td width="15px">Nama Kegiatan</td>
                    <td>:</td>
                    <td><input type="text" id="nmgiat" readonly="true" style="border:0;width: 400px;" /></td>
                    <td><input type="hidden" id="prog" readonly="true" style="border:0;width: 400px;" /></td>
                    <td><input type="hidden" id="nmprog" readonly="true" style="border:0;width: 400px;" /></td>
                </tr>
                <tr>
                    <td width="15px">Anggaran</td>
                    <td>:</td>
                    <td><input type="text" id="anggaran" style="text-align: right;" readonly="true" style="border:0;width: 400px;" /></td>
                </tr>
                <tr>
                    <td width="15px">Yang Telah di SPD kan</td>
                    <!--bukan Lalu, tapi yang sudah di SPDkan untuk Kontrol biat tidak melebihi anggaran saat edit yang lalu-->
                    <td>:</td>
                    <td><input type="text" id="lalu" style="text-align: right;" readonly="true" style="border:0;width: 400px;" /></td>
                </tr>
                <tr>
                    <td width="15px">Nilai</td>
                    <td>:</td>
                    <td><input type="text" id="nilai" style="text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" onkeyup="javascript:sisa_spd();" /></td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <table align="center">
                <tr>
                    <td>
                        <!-- <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:append_save();">Simpan</a> -->
                        <!-- <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Keluar</a> -->
                        <button type="delete" value="keluar" onclick="javascript:append_save();"><i class="fa fa-save"></i> Simpan</button>
                        <button type="delete" value="keluar" onclick="javascript:keluar();"><i class="fa fa-arrow-left"></i> Keluar</button>
                    </td>
                </tr>
            </table>
        </fieldset>
        <fieldset>
            <div align="right">Total : <input type="text" id="total1" style="font-size: large;border:0;width: 200px;text-align: right;" readonly="true" /></div>
            <table id="dg2" title="Kegiatan S P D" style="width:650px;height:250px;">
            </table>
        </fieldset>
    </div>

    <div id="dialog-cetak" title="Cetak SPD">
        <p class="validateTips">Cetak</p>
        <fieldset>
            <form target="_blank" method="POST" id="frm_ctk">
                <table>
                    <tr>
                        <td>Nomor SPD</td>
                        <td>:</td>
                        <td><input type="text" id="nomor1" style="border: 0;" name="nomor1" readonly="true" /></td>
                    </tr>
                    <tr>
                        <td colspan="3"><input type="radio" name="cetak" value="2" onclick="opt(this.value)" />Lampiran SPD (Layar)</td>
                    </tr>
                    <tr>
                        <td colspan="3"><input type="radio" name="cetak" value="4" onclick="opt(this.value)" />Otorisasi SPD (Layar)</td>
                    </tr>
                    <tr>
                        <td valign=\"top\" style="horizontal-align: center;">Bendahara PPKD </td>
                        &nbsp;&nbsp;<td colspan="3" style="vertical-align: center;"><input type="text" name="nip_ppkd" id="bendahara_ppkd" style="width:200px;" /><br>
                            &nbsp;&nbsp;<input type="input" readonly="true" style="border:hidden" id="nama_ppkd" name="nama_ppkd" style="width:300px;text-indent: 50px;" />
                            <input type="hidden" readonly="true" style="border:hidden" id="jabatan_ppkd" name="jabatan_ppkd" />
                            <input type="hidden" readonly="true" style="border:hidden" id="pangkat_ppkd" name="pangkat_ppkd" />
                        </td>

                    </tr>
                    <tr>
                        <td>Cetak Tanpa Nomor SPD</td>
                        <td>:</td>
                        <td><input type="checkbox" id="chk_spd" style="border: 0;" name="chk_spd" value="1" /></td>
                    </tr>
                    <tr>
                        <td>Tambahan</td>
                        <td>:</td>
                        <td><input type="checkbox" id="chk_tambah" style="border: 0;" name="chk_tambah" value="1" /></td>
                    </tr>
                    <tr>
                        <td>Ukuran Cetakkan</td>
                        <td>:</td>
                        <td>&nbsp;<input type="number" id="cell" name="cell" style="width: 50px; border:1" value="1" /> &nbsp;&nbsp;</td>
                    </tr>
                    <tr>

                        <td colspan="3" align="center"><select id="kop" name="kop">
                                <option value="5">DENGAN KOP</option>
                                <option value="30">TANPA KOP</option>
                            </select></td>
                        <td colspan="3" align="center"><select id="water" name="water">
                                <option value="0">TANPA WATERMARK</option>
                                <option value="1">DENGAN WATERMARK</option>
                            </select></td>
                    </tr>
                </table>
        </fieldset>
        <fieldset>
            <table align="center">
                <tr>
                    <td>
                        <button type="primary" value="Print" onclick="javascript:cetak_spd(0);"><i class="fa fa-television"></i> Layar</button>
                        <button type="primary" value="Print" onclick="javascript:cetak_spd(1);"><i class="fa fa-print"></i> PDF</button>
                        <!--<button type="edit"  value="keluar" onclick="javascript:keluar();"><i class="fa fa-arrow-left"></i> Keluar</button>
        -->
                        <a class="easyui-linkbutton" plain="true" onclick="javascript:section1();">
                            <font color='orange'><i class="fa fa-arrow-left"></i></font> Keluar
                        </a>
                        <!-- <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="" >Print</a>
                    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:;">Keluar</a> -->
                    </td>
                </tr>
            </table>
            </form>
        </fieldset>

    </div>


    <div id="dialog-edit_nilai" title="Input Anggaran SPD">
        <p class="validateTips"></p>
        <fieldset>
            <table align="center" style="width:100%;" border="0">
                <tr>
                    <td>KEGIATAN</td>
                    <td>:</td>
                    <td colspan="4"><input id="rek_kegi" name="rek_kegi" style="width: 200px;" readonly="true" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_kegi" style="border:0;width: 300px;" readonly="true" /></td>
                </tr>
                <tr>
                    <td>REKENING</td>
                    <td>:</td>
                    <td colspan="4"><input id="rek_rek" name="rek_rek" style="width: 200px;" readonly="true" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" id="nm_rek_rek" style="border:0;width: 300px;" readonly="true" /></td>
                </tr>
                <tr>
                    <td>NILAI</td>
                    <td>:</td>
                    <td><input type="text" id="rek_nilai" style="background-color:#99FF99; width: 196px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" /></td>
                </tr>

                <tr>
                    <td>NILAI SPD LALU</td>
                    <td>:</td>
                    <td><input type="text" id="rek_nilai_lalu" style="width: 196px; text-align: right; " readonly="true" /></td>

                </tr>
                <tr>
                    <td>ANGGARAN</td>
                    <td>:</td>
                    <td><input type="text" id="rek_nilai_anggaran" style="width: 196px; text-align: right; " readonly="true" /></td>

                </tr>
                <tr>
                    <td>SISA Anggaran</td>
                    <td>:</td>
                    <td><input type="text" id="rek_sisa" style="width: 196px; text-align: right; " readonly="true" /></td>

                </tr>

                <tr>
                    <td>Realisasi Kegiatan</td>
                    <td>:</td>
                    <td><input type="text" id="real_keg" style="width: 196px; text-align: right; " readonly="true" value='999,999,999,999,999,999.00' /></td>

                </tr>
                <tr>
                    <td>Anggaran Kas</td>
                    <td>:</td>
                    <td><input type="text" id="txtanggkas" style="width: 196px; text-align: right; " readonly="true" value='0.00' /></td>

                </tr>


                <tr>
                    <td colspan="3" align="center">
                        <!-- <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:update_save();">Simpan</a>
                    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar_rek();">Keluar</a> -->

                        <button type="primary" value="keluar" onclick="javascript:update_save();"><i class="fa fa-save"></i> Simpan</button>
                        <button type="edit" value="keluar" onclick="javascript:keluar_rek();"><i class="fa fa-arrow-left"></i> Keluar</button>
                    </td>
                </tr>

            </table>

        </fieldset>

    </div>

</body>

</html>