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

    <script type="text/javascript">
        var kode = '';
        var giat = '';
        var nomor = '';
        var judul = '';
        var cid = 0;
        var lcidx = 0;
        var lcstatus = '';

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#dialog-modal").dialog({
                height: 350,
                width: 1000,
                modal: true,
                autoOpen: false
            });
        });



        $(function() {
            $('#kode_f').combogrid({
                panelWidth: 500,
                idField: 'kd_kelompok_barang',
                textField: 'kd_kelompok_barang',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/master/ambil_kel_barang',
                columns: [
                    [{
                            field: 'kd_kelompok_barang',
                            title: 'Kode Kelompok Barang',
                            width: 100
                        },
                        {
                            field: 'uraian_kelompok_barang',
                            title: 'Nama Kelompok Barang',
                            width: 400
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    $("#nm_f").attr("value", rowData.uraian_kelompok_barang.toUpperCase());
                }
            });

            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/master/load_daftar_harga_ar',
                idField: 'id',
                rownumbers: "true",
                fitColumns: "true",
                singleSelect: "true",
                autoRowHeight: "false",
                loadMsg: "Tunggu Sebentar....!!",
                pagination: "true",
                nowrap: "true",
                columns: [
                    [{
                            field: 'kd_barang',
                            title: 'Kode Barang',
                            width: 15,
                            align: "center"
                        },
                        {
                            field: 'uraian',
                            title: 'Uraian Barang',
                            width: 50
                        },
                        {
                            field: 'spesifikasi',
                            title: 'Spesifikasi',
                            width: 10
                        },
                        {
                            field: 'harga',
                            title: 'Harga Satuan',
                            width: 10
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    idshb = rowData.ids;
                    kd = rowData.kd_barang;
                    kd_f = rowData.kd_kelompok_barang;
                    nm = rowData.uraian;
                    spek = rowData.spesifikasi;
                    satuan = rowData.satuan;
                    harga = rowData.harga;
                    lcidx = rowIndex;
                    get(idshb, kd, nm, kd_f, spek, satuan, harga);

                },
                onDblClickRow: function(rowIndex, rowData) {
                    lcidx = rowIndex;
                    judul = 'Edit Data Barang';
                    edit_data();
                }

            });

        });



        function get(idshb, kd, nm, kd_f, spek, satuan, harga) {

            $("#idshb").attr("value", idshb);
            $("#kode").attr("value", kd);
            $("#nama").attr("value", nm);
            $("#spek").attr("value", spek);
            $("#satuan").attr("value", satuan);
            $("#harga").attr("value", number_format(harga, "2", ".", ","));
            $('#kode_f').combogrid("setValue", kd_f);
        }

        function kosong() {
            $("#kode").attr("value", '');
            $("#nama").attr("value", '');
            $("#id_shb").attr("value", '');
            $("#nm_f").attr("value", '');
            $("#kd_barang").attr("value", '');
            $("#nama").attr("value", '');
            $("#spek").attr("value", '');
            $("#satuan").attr("value", '');
            $("#harga").attr("value", '');
            $("#idshb").attr("value", '');
            $('#kode_f').combogrid("setValue", '');
            $('#kode_f').combogrid("enable");
        }


        function cari() {
            var kriteria = document.getElementById("txtcari").value;
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/master/load_daftar_harga_ar',
                    queryParams: ({
                        cari: kriteria
                    })
                });
            });
        }

        function simpan_barang() {

            var ckode = document.getElementById('kode').value;
            var cidshb = document.getElementById('idshb').value;
            var cnama = document.getElementById('nama').value;
            var cspesifikasi = document.getElementById('spek').value;
            var csatuan = document.getElementById('satuan').value;
            var charga = angka(document.getElementById('harga').value);
            var ckode_f = $('#kode_f').combogrid('getValue'); //kd_kelompok
            var cnm_f = document.getElementById('nm_f').value;

            if (ckode == '') {
                alert('Kode Barang Tidak Boleh Kosong');
                exit();
            }
            if (cnama == '') {
                alert('Uraian Barang Tidak Boleh Kosong');
                exit();
            }

            if (ckode_f == '') {
                alert('Kode Kelompok Barang Tidak Boleh Kosong');
                exit();
            }

            if (charga == '') {
                alert('Harga Satuan Tidak Boleh Kosong');
                exit();
            }


            if (lcstatus == 'tambah') {

                lcinsert = "(kd_kelompok_barang,uraian_kelompok_barang,kd_barang,uraian_barang,spesifikasi,satuan,harga_satuan)";
                lcvalues = "('" + $.trim(ckode_f) + "','" + $.trim(cnm_f) + "','" + $.trim(ckode) + "','" + $.trim(cnama) + "','" + $.trim(cspesifikasi) + "','" + $.trim(csatuan) + "','" + $.trim(charga) + "')";

                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/master/simpan_barang_baru',
                        data: ({
                            tabel: 'ms_standar_harga',
                            kolom: lcinsert,
                            nilai: lcvalues,
                            curai: cnama,
                            cspek: cspesifikasi,
                            lkode: ckode
                        }),
                        dataType: "json",
                        success: function(data) {
                            status = data;
                            if (status == '0') {
                                alert('Gagal Simpan..!!');
                                exit();
                            } else if (status == '1') {
                                alert('Data Sudah Ada..!!');
                                exit();
                            } else {
                                alert('Data Tersimpan..!!');
                                exit();
                            }
                        }
                    });
                });

            } else {

                lcquery = "UPDATE ms_standar_harga SET uraian_barang='" + $.trim(cnama) + "',spesifikasi='" + $.trim(cspesifikasi) + "',satuan='" + $.trim(csatuan) + "',harga_satuan='" + $.trim(charga) + "' where id_standar_harga='" + $.trim(cidshb) + "'";

                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/master/update_master2',
                        data: ({
                            st_query: lcquery
                        }),
                        dataType: "json",
                        success: function(data) {
                            status = data;
                            if (status == '0') {
                                alert('Gagal Simpan..!!');
                                exit();
                            } else {
                                alert('Data Tersimpan..!!');

                                exit();
                            }
                        }
                    });
                });


            }


            $("#dialog-modal").dialog('close');
            $('#dg').edatagrid('reload');
        }

        function edit_data() {
            lcstatus = 'edit';
            judul = 'Edit Data Program';
            $("#dialog-modal").dialog({
                title: judul
            });
            $("#dialog-modal").dialog('open');
            document.getElementById("kode").disabled = true;
            $('#kode_f').combogrid("disable");
        }


        function tambah() {
            lcstatus = 'tambah';
            judul = 'Input Data Program';
            $("#dialog-modal").dialog({
                title: judul
            });
            kosong();
            $("#dialog-modal").dialog('open');
            $('#kode_f').combogrid("enable");
            document.getElementById("kode").disabled = false;
            document.getElementById("kode").focus();
        }

        function keluar() {
            $("#dialog-modal").dialog('close');
        }

        function hapus() {
            var ckode = document.getElementById('kode').value;
            $.ajax({
                type: "POST",
                dataType: 'json',
                data: ({
                    no: ckode,
                    tabel: 'trdpo',
                    field: 'kd_barang'
                }),
                url: '<?php echo base_url(); ?>/index.php/rka/cek_data',
                success: function(data) {
                    status_cek = data.pesan;
                    if (status_cek == 1) {
                        alert("Kode Barang Telah Dipakai dan tidak bisa di Hapus!");
                        exit();
                    }
                    if (status_cek == 0) {
                        var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
                        $(document).ready(function() {
                            $.post(urll, ({
                                tabel: 'ms_standar_harga',
                                cnid: ckode,
                                cid: 'kd_barang'
                            }), function(data) {
                                status = data;
                                if (status == '0') {
                                    alert('Gagal Hapus..!!');
                                    exit();
                                } else {
                                    $('#dg').datagrid('deleteRow', lcidx);
                                    alert('Data Berhasil Dihapus..!!');
                                    $("#dialog-modal").dialog('close');
                                    $('#dg').edatagrid('reload');
                                    //                exit();
                                }
                            });
                        });

                    }
                }
            });


        }


        function addCommas(nStr) {
            nStr += '';
            x = nStr.split(',');
            x1 = x[0];
            x2 = x.length > 1 ? ',' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + '.' + '$2');
            }
            return x1 + x2;
        }

        function delCommas(nStr) {
            nStr += ' ';
            x2 = nStr.length;
            var x = nStr;
            var i = 0;
            while (i < x2) {
                x = x.replace(',', '');
                i++;
            }
            return x;
        }
    </script>

</head>

<body>

    <div id="content">

        <h3 align="center"><u><b><a>INPUTAN MASTER STANDAR HARGA & BIAYA</a></b></u></h3>
        <div align="center">
            <p align="center">
            <table style="width:400px;" border="0">
                <tr>
                    <td width="10%">
                        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>
                    </td>

                    <td width="5%"><a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a></td>
                    <td><input type="text" value="" id="txtcari" style="width:300px;" /></td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table id="dg" title="LISTING DATA STANDAR HARGA & BIAYA" style="width:900px;height:440px;">
                        </table>
                    </td>
                </tr>
            </table>



            </p>
        </div>
    </div>

    <div id="dialog-modal" title="">
        <p class="validateTips">Semua Inputan Harus Di Isi.</p> <input type="text" id="idshb" style="width:50;" hidden="true" />
        <fieldset>
            <table align="center" style="width:100%;" border="0">
                <tr>
                    <td width="40%">Kode Kelompok Barang</td>
                    <td width="1%">:</td>
                    <td width="59%">
                        <input type="text" id="kode_f" style="width:200px;" />&nbsp;
                        <input type="text" id="nm_f" style="border:0px;width:310px;" readonly="true" />
                    </td>
                </tr>
                <tr>
                    <td width="40%">Kode Barang</td>
                    <td width="1%">:</td>
                    <td width="59%"><input type="text" id="kode" style="width:100px;" /></td>
                </tr>
                <tr>
                    <td width="40%">Uraian Barang</td>
                    <td width="1%">:</td>
                    <td width="59%"><input type="text" id="nama" style="width:700px;" /></td>
                </tr>
                <tr>
                    <td width="40%">Spesifikasi</td>
                    <td width="1%">:</td>
                    <td width="59%"><input type="text" id="spek" style="width:700px;" /></td>
                </tr>
                <tr>
                    <td width="40%">Harga Satuan</td>
                    <td width="1%">:</td>
                    <td width="59%"><input type="text" id="harga" style="width:200px;text-align:right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" /></td>
                </tr>
                <tr>
                    <td width="40%">Satuan</td>
                    <td width="1%">:</td>
                    <td width="59%"><input type="text" id="satuan" style="width:70px;" /></td>
                </tr>
                <tr>
                    <td colspan="3">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="3" align="center">
                        <button type="primary" plain="true" onclick="javascript:simpan_barang(); $('#dg').edatagrid('reload'); "><i class="fa fa-save"></i> Simpan</button>
                        <button type="delete" plain="true" onclick="javascript:hapus();"><i class="fa fa-trash"></i> Hapus</button>
                        <button type="edit" plain="true" onclick="javascript:keluar();"><i class="fa fa-arrow-left"></i> Kembali</button>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>

</body>

</html>