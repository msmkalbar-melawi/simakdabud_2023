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

    <style>

    </style>
    <script type="text/javascript">
        var no_kas = '';
        var kd_skpd = '';
        var cstatus = '';

        $(document).ready(function() {

            $("#dialog-modal").dialog({
                height: 450,
                width: 700,
                modal: true,
                autoOpen: false
            });
            $("#accordion").accordion();
            // get_skpd();
            get_tahun();
        });
        $(document).ready(function() {
            // Sub Rekening
            $('#rek').combogrid({
                panelWidth: 700,
                idField: 'kd_rek6',
                textField: 'kd_rek6',
                columns: [
                    [{
                            field: 'kd_sub_kegiatan',
                            title: 'Sub Kegiatan',
                            width: 140
                        },
                        {
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 100
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 100
                        },
                        {
                            field: 'nilai',
                            title: 'nilai',
                            width: 100
                        }
                    ]
                ],
            });
            $('#skpdjkn').combogrid({
                panelWidth: 700,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                mode: 'remote',
                columns: [
                    [{
                            field: 'kd_skpd',
                            title: 'Kode Puskesmas',
                            width: 100
                        },
                        {
                            field: 'nm_skpd',
                            title: 'Nama Puskesmas',
                            width: 700
                        }
                    ]
                ],
            });
            // Sub Kegiatan
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
                            width: 700
                        }
                    ]
                ],
            });
            // SP2D
            $('#sp2d').combogrid({
                panelWidth: 700,
                idField: 'no_sp2d',
                textField: 'no_sp2d',
                mode: 'remote',
                columns: [
                    [{
                            field: 'no_sp2d',
                            title: 'No SP2D',
                            width: 140
                        },
                        {
                            field: 'tgl_sp2d',
                            title: 'Tanggal',
                            width: 100
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai',
                            width: 100
                        }
                    ]
                ],
            });
            // tanggal
            $('#tanggal').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                },
                onSelect: function(date) {}
            });
            $('#tgl_cetak').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                },
                onSelect: function(date) {}
            });
            $('#tgl_sp3b').datebox({
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });
            $('#dg').edatagrid({
                columns: [
                    [{
                            field: 'no_sp2b',
                            title: 'SP2B',
                            width: 250,
                            align: "center"
                        },
                        {
                            field: 'tgl_sp2b',
                            title: 'Tanggal SP2B',
                            width: 100,
                            align: "center"
                        },
                        {
                            field: 'no_sp3b',
                            title: 'SP3B',
                            width: 250,
                            align: "center"
                        },
                        {
                            field: 'nm_skpd',
                            title: 'Skpd',
                            width: 200,
                            align: "center"
                        }
                    ]
                ]
            });
        });

        $(document).ready(function() {
            $('#sp3b').on('change', function() {
                let sp3b = ($(this).find(":selected").val());
                if (sp3b == '' || sp3b == undefined) {
                    alert('Pilih Jenis SP3B');
                    return;
                }
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>index.php/Sp2bController/loaddata',
                    idField: 'id',
                    rownumbers: "true",
                    fitColumns: "true",
                    singleSelect: "true",
                    autoRowHeight: "false",
                    loadMsg: "Tunggu Sebentar....!!",
                    pagination: "true",
                    nowrap: "true",
                    queryParams: ({
                        sp3b: sp3b
                    }),
                    columns: [
                        [{
                                field: 'no_sp2b',
                                title: 'SP2B',
                                width: 250,
                                align: "center"
                            },
                            {
                                field: 'tgl_sp2b',
                                title: 'Tanggal SP2B',
                                width: 100,
                                align: "center"
                            },
                            {
                                field: 'no_sp3b',
                                title: 'SP3B',
                                width: 250,
                                align: "center"
                            },
                            {
                                field: 'nm_skpd',
                                title: 'Skpd',
                                width: 200,
                                align: "center"
                            }
                        ]
                    ],
                    rowStyler: function(rowIndex, rowData) {
                        if (rowData.status == '1') {
                            return 'background-color:#4bbe68;color:white';
                        }
                    },
                    onSelect: function(rowIndex, rowData) {
                        //         'id' => $ii,
                        //     'kd_skpd'    => $resulte['kd_skpd'],
                        //     'nm_skpd'    => $resulte['nm_skpd'],
                        //     'ket'   => $resulte['keterangan'],
                        //     'no_lpj'   => $resulte['no_lpj'],
                        //     'tgl_lpj'      => $resulte['tgl_lpj'],
                        //     'tgl_sp2b'   => $resulte['tgl_sp2b'],
                        //     'no_sp2b'      => $resulte['no_sp2b'],
                        //     'status'      => $resulte['status'],
                        //     'tgl_awal'      => $resulte['tgl_awal'],
                        //     'tgl_akhir'      => $resulte['tgl_akhir'],
                        // );
                        kd_skpd = rowData.kd_skpd;
                        nm_skpd = rowData.nm_skpd;
                        no_lpj = rowData.no_lpj;
                        no_sp3b = rowData.no_sp3b;
                        tgl_lpj = rowData.tgl_lpj;
                        tgl_sp2b = rowData.tgl_sp2b;
                        no_sp2b = rowData.no_sp2b;
                        keterangansp2b = rowData.keterangansp2b;
                        edit_data(kd_skpd, nm_skpd, no_lpj, tgl_lpj, tgl_sp2b, no_sp2b, keterangansp2b, sp3b,no_sp3b);
                    }
                });
            });
        });


        function edit_data(kd_skpd, nm_skpd, no_lpj, tgl_lpj, tgl_sp2b, no_sp2b, keterangansp2b, sp3b,no_sp3b) {
            $.ajax({
                type: "POST",
                url: '<?php echo base_url(); ?>index.php/Sp2bController/cek_data',
                data: ({
                    no_sp2b: no_sp2b,
                    sp3b: sp3b
                }),
                dataType: "json",
                success: function(data) {
                    if (data == '1') {
                        cstatus = 'edit';
                        $('#save').html('Update');
                    } else {
                        cstatus = 'tambah';
                        $('#save').html('Simpan');
                    }
                }
            });
            $('#skpd').val(kd_skpd);
            $('#nmskpd').val(nm_skpd);
            $('#no_sp3b').val(no_sp3b);
            $('#keterangan').val(keterangansp2b);
            // $('#tot_belanja').val(0);
            $('#no_sp2b').val(no_sp2b);
            $('#no_sp2bhidden').val(no_sp2b);
            $('#tanggal').datebox("setValue", tgl_sp2b);
            $('#tgl_sp3b').datebox("setValue", tgl_lpj);
            // $('#tgl_sp3b').attr('readonly', true);
            $('#section2').click();
            $(document).ready(function() {
                $('#dg1').edatagrid({
                    url: '<?php echo base_url(); ?>index.php/Sp2bController/loadingdata',
                    queryParams: ({
                        no_lpj: no_lpj,
                        kd: kd_skpd,
                        sp3b: sp3b
                    }),
                    columns: [
                        [{
                                field: 'kd_sub_kegiatan',
                                title: 'Sub Kegiatan',
                                width: 200
                            },
                            {
                                field: 'kd_rek6',
                                title: 'Kode Rekening',
                                width: 200
                            },
                            {
                                field: 'nm_rek6',
                                title: 'Nama Rekening',
                                width: 250,
                                align: "left"
                            },
                            {
                                field: 'nilai',
                                title: 'Nilai',
                                width: 150,
                                align: "left"
                            }
                        ]
                    ]
                });

                $.ajax({
                    type: 'POST',
                    url: "<?php echo base_url(); ?>index.php/Sp2bController/loadingdata",
                    dataType: "json",
                    data: {
                        no_lpj: no_lpj,
                        kd: kd_skpd,
                        sp3b: sp3b
                    },
                    success: function(result) {
                        $.each(result, function(i, n) {
                            $('#tot_pendapatan').val(number_format(n['totpendapatan'], 2, '.', ','));
                            $('#tot_belanja').val(number_format(n['totbelanja'], 2, '.', ','));
                            // alert(n['totpendapatan']);
                            // alert(n['totbelanja']);
                        });

                    }
                });
            });



        }





        function numberFormat(n) {
            let nilai = number_format(n, 2, '.', ',');
            return nilai;
        }



        function get_tahun() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/tukd/config_tahun',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    tahun_anggaran = data;
                }
            });

        }

        function section1() {
            $(document).ready(function() {
                $('#section1').click();
                $('#dg').edatagrid('unselectAll');
            });
        }


        function cari() {
            var kriteria = document.getElementById("txtcari").value;
            var sp3b = $('#sp3b').val();
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>index.php/Sp2bController/loaddata',
                    queryParams: ({
                        cari: kriteria,
                        sp3b: sp3b
                    })
                });
            });
        }


        // Hakam
        $(document).ready(function() {
            $('#save').click(function() {
                var sp2b = $('#no_sp2b').val();
                var no_sp2bhidden = $('#no_sp2bhidden').val();
                var skpd = $('#skpd').val();
                var sp3b = $('#sp3b').val();
                var no_sp3b = $('#no_sp3b').val();
                var keterangan = $('#keterangan').val();
                var tgl_sp2b = $('#tanggal').datebox('getValue');
                var tgl_sp3b = $('#tgl_sp3b').datebox('getValue');
                if (sp2b == '') {
                    alert('Isi No SP2B Terlebih Dahulu...!!!');
                    return;
                }
                if (tgl_sp2b == '') {
                    alert('Isi Tgl SP2B Terlebih Dahulu...!!!');
                    return;
                }
                if (keterangan == '') {
                    alert('Isi Keterangan Terlebih Dahulu...!!!');
                    return;
                }
                if (cstatus == 'tambah') {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>index.php/Sp2bController/simpan_data',
                        data: ({
                            sp2b: sp2b,
                            skpd: skpd,
                            no_sp3b: no_sp3b,
                            keterangan: keterangan,
                            tgl_sp2b: tgl_sp2b,
                            sp3b: sp3b
                        }),
                        beforeSend: function() {
                            $("#save").attr("disabled", "disabled");
                        },
                        dataType: "json",
                        success: function(data) {
                            status = data;
                            if (status == '1') {
                                alert('Data Tersimpan..!!');
                                $('#dg').edatagrid('unselectAll');
                                $('#dg').edatagrid('reload');
                                $('#section1').click();
                            } else if (status == '2') {
                                alert('Data gagal disimpan.!!');
                            }
                        },
                        complete: function(response) {
                            $("#save").removeAttr('disabled');
                        }
                    });
                } else if (cstatus == 'edit') {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>index.php/Sp2bController/edit_data',
                        data: ({
                            sp2b: sp2b,
                            no_sp2bhidden: no_sp2bhidden,
                            skpd: skpd,
                            no_sp3b: no_sp3b,
                            keterangan: keterangan,
                            tgl_sp2b: tgl_sp2b,
                            sp3b: sp3b
                        }),
                        beforeSend: function() {
                            $("#save").attr("disabled", "disabled");
                        },
                        dataType: "json",
                        success: function(data) {
                            status = data;
                            if (status == '1') {
                                alert('Data diedit..!!');
                                $('#dg').edatagrid('unselectAll');
                                $('#dg').edatagrid('reload');
                                $('#section1').click();
                            } else if (status == '2') {
                                alert('Data gagal diedit.!!');
                            }
                        },
                        complete: function(response) {
                            $("#save").removeAttr('disabled');
                        }
                    });

                }

            });


            $('#delete').click(function() {
                var sp3b = $('#sp3b').val();
                var sp2b = $('#no_sp2b').val();
                var skpd = $('#skpd').val();
                var no_sp3b = $('#no_sp3b').val();
                var keterangan = $('#keterangan').val();
                var tgl_sp2b = $('#tanggal').datebox('getValue');
                var tgl_sp3b = $('#tgl_sp3b').datebox('getValue');
                if (sp2b == '') {
                    alert('Isi No SP2B Terlebih Dahulu...!!!');
                    return;
                }
                if (tgl_sp2b == '') {
                    alert('Isi Tgl SP2B Terlebih Dahulu...!!!');
                    return;
                }
                if (keterangan == '') {
                    alert('Isi Keterangan Terlebih Dahulu...!!!');
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: '<?php echo base_url(); ?>index.php/Sp2bController/delete_data',
                    data: ({
                        sp2b: sp2b,
                        skpd: skpd,
                        no_sp3b: no_sp3b,
                        sp3b: sp3b,
                        keterangan: keterangan,
                        tgl_sp2b: tgl_sp2b
                    }),
                    beforeSend: function() {
                        $("#save").attr("disabled", "disabled");
                    },
                    dataType: "json",
                    success: function(data) {
                        status = data;
                        if (status == '1') {
                            alert('Data Berhasil dihapus..!!');
                            $('#dg').edatagrid('unselectAll');
                            $('#dg').edatagrid('reload');
                            $('#section1').click();
                        } else if (status == '2') {
                            alert('Data gagal dihapus.!!');
                        }
                    },
                    complete: function(response) {
                        $("#save").removeAttr('disabled');
                    }
                });
            });

            // $(document).ready(function() {
            $('#cetak').click(function() {
                var sp2b = $('#no_sp2b').val();
                var no_sp2bhidden = $('#no_sp2bhidden').val();
                var skpd = $('#skpd').val();
                var no_sp3b = $('#no_sp3b').val();
                var keterangan = $('#keterangan').val();
                var tgl_sp2b = $('#tanggal').datebox('getValue');
                var tgl_sp3b = $('#tgl_sp3b').datebox('getValue');
                $('#ttdcetakan').combogrid({
                    panelWidth: 500,
                    url: '<?php echo base_url(); ?>/index.php/sp2d/pilih_ttd_bud',
                    idField: 'nip',
                    textField: 'nama',
                    mode: 'remote',
                    fitColumns: true,
                    columns: [
                        [{
                                field: 'nip',
                                title: 'NIP',
                                width: 60
                            },
                            {
                                field: 'nama',
                                title: 'NAMA',
                                align: 'left',
                                width: 100
                            }


                        ]
                    ],
                    onSelect: function(rowIndex, rowData) {
                        nip = rowData.nip;

                    }
                });
                var no_sp2b = $('#no_sp2b').val();
                $('#cspp').val(no_sp2b);
                $("#dialog-modal").dialog('open');
            });
            // });



            $('#cetakpdf').click(function() {
                var sp3b = $('#sp3b').val();
                var kdskpd = $('#skpd').val();
                var cspp = $('#cspp').val();
                var baris = $('#baris').val();
                var tglcetakan = $('#tgl_cetak').datebox('getValue');
                var ttdcetakan = $("#ttdcetakan").combogrid("getValue");
                if (tglcetakan == '' || tglcetakan == undefined) {
                    alert('Tanggal tidak boleh kosong');
                    return;
                }
                if (ttdcetakan == '') {
                    alert('Tanda tangan tidak boleh kosong');
                    return;
                }
                url = "<?php echo site_url(); ?>SP2BController/cetakansp2b";
                link = "?kdskpd=" + kdskpd + "&no_sp2b=" + cspp + "&tgl=" + tglcetakan + "&ttd=" + ttdcetakan + '&baris=' + baris + '&jenissp3b=' + sp3b;
                window.open(url + link, '_blank');
                window.focus();
            });
        });
    </script>

</head>

<body>
    <div id="content">
        <div id="accordion">
            <h3><a href="#" id="section1">List SP2B JKN/BOK</a></h3>
            <div>
                <p align="right">
                    <select name="sp3b" id="sp3b">
                        <option value="">-- Pilih --</option>
                        <option value="JKN">SP2B JKN</option>
                        <option value="BOK">SP2B BOK</option>
                    </select>
                    <input type="text" value="" id="txtcari" />
                    <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>

                <table id="dg" title="List SP2B JKN/BOK" style="width:870px;height:590px;">
                </table>
                </p>
            </div>

            <h3><a href="#" id="section2">SP2B JKN</a></h3>
            <div style="height: 350px;">
                <p>
                <div id="demo"></div>
                <table align="center" style="width:100%;">
                    <tr>
                        <td>Nomor SP2B</td>
                        <td><input type="text" class="input" id="no_sp2b" style="width: 200px;" />
                            <input type="hidden" class="input" id="no_sp2bhidden" style="width: 200px;" h />
                        </td>
                        <td>Tanggal </td>
                        <td><input type="text" id="tanggal" style="width: 200px;" /></td>
                    </tr>
                    <tr>
                        <td>S K P D</td>
                        <td><input id="skpd" class="input" name="skpd" style="width: 200px;" /><input type="hidden" id="nmbidang" style="border:0;width: 400px;  " readonly="true" /><input type="hidden" id="kdbidang" class="input" name="kdbidang" style="width: 200px;" /></td>
                        <td>Nama :</td>
                        <td><input type="text" id="nmskpd" style="border:0;width: 400px;" readonly="true" /></td>
                    </tr>

                    <tr>
                        <td>No SP3B</td>
                        <td><input type="text" class="input" id="no_sp3b" style="width: 200px;" readonly />
                        </td>
                        <td>Tanggal SP3B</td>
                        <td><input type="text" id="tgl_sp3b" style="width: 200px;" readonly /></td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td colspan="4"><textarea id="keterangan" class="textarea" style="width: 650px; height: 40px;"></textarea></td>
                    </tr>
                    <td colspan="3" align="right">
                        <button id="save" class="button-biru"><i class="fa fa-save"></i> Simpan</button>
                        <button id="delete" class="button-merah"><i class="fa fa-hapus"></i> Hapus</button>
                        <button id="cetak" class="button-biru"></i> Cetak</button>
                        <button class="button-abu" onclick="javascript:section1();"><i class="fa fa-kiri"></i> Kembali</button>

                    </td>
                </table>
                <table id="dg1" title="List data JKN" style="width:870px;height:200px;">
                </table>
                <table align="center" style="width:100%;" border="0">
                    <tr>
                        <td width="left">Total Pendapatan : <input type="text" id="tot_pendapatan" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true" /></td>
                        <td align="right">Total Belanja : <input type="text" id="tot_belanja" style="text-align: right;border:0;width: 200px;font-size: large;" readonly="true" /></td>
                    </tr>
                </table>
                </p>
            </div>
        </div>
    </div>

    <div id="dialog-modal" title="CETAK SP2B">
        <fieldset>
            <table>
                <tr>
                    <td width="200px">NO SP2B :</td>
                    <td><input id="cspp" disabled name="cspp" style="width: 200px; " /></td>
                </tr>
                <tr>
                    <td>Tanda Tangan:</td>
                    <td><input type="text" id="ttdcetakan" style="width: 200px;" /></td> &nbsp;&nbsp;
                    <td><input type="text" id="nm_ttdcetakan" readonly="true" style="width: 150px;border:0" /> </td>
                </tr>
                <tr style="border-bottom-style:hidden;border-spacing:0px;padding:3px 3px 3px 3px;border-collapse:collapse;">
                    <td width='20%'>Tanggal :</td>
                    <td>&nbsp;<input id="tgl_cetak" name="tgl_cetak" type="text" style="width:95px" /></td>
                </tr>
                <tr>
                    <td width="110px">Baris:</td>
                    <td><input type="number" id="baris" name="baris" style="width: 40px;" value="22" /></td>
                </tr>

                <tr style="height: 30px;">
                    <td colspan="4">
                        <div align="center">
                            <button id="cetakpdf" class="button-orange"><i class="fa fa-print" style="font-size:15px"></i> PDF</button>
                            <button id="kem" class="button-kuning" onclick="javascript:section4();"><i class="fa fa-arrow-left" style="font-size:15px"></i> Kembali</button>

                        </div>
                    </td>
                </tr>

            </table>
    </div>

</body>

</html>