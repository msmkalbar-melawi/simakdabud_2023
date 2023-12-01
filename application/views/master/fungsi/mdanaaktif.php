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
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
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
                height: 500,
                width: 800,
                modal: true,
                autoOpen: false
            });
        });

        $(function() {

            $('#kode_u').combogrid({
                panelWidth: 550,
                idField: 'kd_sdana',
                textField: 'kd_sdana',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/master/ambil_sdana',
                columns: [
                    [{
                            field: 'kd_sdana',
                            title: 'KODE SUMBER DANA',
                            width: 150
                        },
                        {
                            field: 'nm_sdana',
                            title: 'NAMA SUMBER DANA',
                            width: 400
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    kd_sdana = rowData.kd_sdana;

                    // $("#kode_u").attr("value",rowData.kd_sdana.toUpperCase());
                    // $("#kode_u").combogrid("setValue",kd_sdana);
                    $("#nmkode").attr("value", rowData.nm_sdana.toUpperCase());
                    //muncul();                
                }
            });

            $('#dinas').combogrid({
                panelWidth: 500,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/master/ambil_skpd',
                columns: [
                    [{
                            field: 'kd_skpd',
                            title: 'Kode SKPD',
                            width: 150
                        },
                        {
                            field: 'nm_skpd',
                            title: 'Nama SKPD',
                            width: 400
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    $("#nmskpd").attr("value", rowData.nm_skpd.toUpperCase());
                    // $("#kode").attr("value",rowData.kd_urusan.toUpperCase()+'.');                
                }
            });


            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/master/load_danaaktif',
                panelWidth: 200,
                idField: 'id',
                rownumbers: "true",
                fitColumns: "true",
                singleSelect: "true",
                autoRowHeight: "false",
                loadMsg: "Tunggu Sebentar....!!",
                pagination: true,
                nowrap: "true",
                columns: [
                    [{
                            field: 'kd_sdana',
                            title: 'KODE',
                            width: 20,
                            align: "left"
                        },
                        {
                            field: 'nm_sdana',
                            title: 'SUMBER DANA',
                            width: 80
                        },
                        {
                            field: 'nilaisilt',
                            title: 'NILAI SILPA',
                            width: 50,
                            align: "right"
                        },
                        {
                            field: 'nilait',
                            title: 'NILAI',
                            width: 50,
                            align: "right"
                        },
                        {
                            field: 'nm_skpd',
                            title: 'NM SKPD',
                            width: 20,
                            align: "right"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    id_sumber = rowData.id_sumber;
                    // alert(id_sumber);
                    kd = rowData.kd_sdana;
                    nm = rowData.nm_sdana;
                    nillsilpa = rowData.nilaisil;
                    nill = rowData.nilai;
                    kdskpd = rowData.kd_skpd;
                    get(kd, nm, nill, nillsilpa, kdskpd, id_sumber);
                    lcidx = rowIndex;


                },
                onDblClickRow: function(rowIndex, rowData) {
                    lcidx = rowIndex;
                    judul = 'Edit Data Sumber Dana';
                    load_detail();
                    edit_data();
                }

            });

            $('#dg2').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/master/load_danadetail',
                idField: 'id',
                toolbar: "#toolbar",
                rownumbers: "true",
                fitColumns: false,
                autoRowHeight: "false",
                singleSelect: "true",
                nowrap: "false",
                columns: [
                    [{
                            field: 'id',
                            title: 'id',
                            width: 70,
                            align: "left",
                            hidden: "true"
                        },
                        {
                            field: 'kd_skpd',
                            title: 'Kode SKPD',
                            width: 170,
                            align: "left"
                        },
                        {
                            field: 'nm_skpd',
                            title: 'Nama SKPD',
                            width: 350,
                            align: "left"
                        },
                        {
                            field: 'hapus',
                            title: 'Hapus',
                            width: 70,
                            align: "center",
                            formatter: function(value, rec) {
                                return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                            }
                        }
                    ]
                ]
            });


        });


        function loadd() {

        }

        function get(kd, nm, nill, nillsilpa, kdskpd, id_sumber) {
            // alert(id_sumber);
            $("#id_sumber").attr("value", id_sumber);

            $("#kode_u").combogrid("setValue", kd);
            $("#nmkode").attr("value", nm);
            $("#nil").attr("value", number_format(nill, 2, '.', ','));
            $("#nilsilpa").attr("value", number_format(nillsilpa, 2, '.', ','));
            $("#dinas").combogrid("setValue", kdskpd);

        }

        function kosong() {
            $("#kode_u").combogrid("setValue", '');
            $("#nmkode").attr("value", '');
            $("#nil").attr("value", '');
            $("#nilsilpa").attr("value", '');
            $("#dinas").combogrid("setValue", '');
            load_detail();
        }


        function kosong_detail() {
            $("#kode_u").attr("value", '');
            $("#nmkode").attr("value", '');
        }


        function cari() {
            var kriteria = document.getElementById("txtcari").value;
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/master/load_danaaktif',
                    queryParams: ({
                        cari: kriteria
                    })
                });
            });
        }



        function load_detail() {

            var kd_rek = $("#kode_u").combogrid("getValue");
            var kdskpd = $("#dinas").combogrid("getValue");
            //alert(kdskpd);

            $('#dg2').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/master/load_danadetail',
                queryParams: ({
                    rekening: kd_rek,
                    vkdskpd: kdskpd
                }),
                idField: 'id',
                toolbar: "#toolbar",
                rownumbers: "true",
                fitColumns: "false",
                autoRowHeight: "false",
                singleSelect: "true",
                nowrap: "false",
                columns: [
                    [{
                            field: 'id',
                            title: 'id',
                            width: 70,
                            align: "left",
                            hidden: "true"
                        },
                        {
                            field: 'no_urut',
                            title: 'No Urut',
                            width: 70,
                            align: "left",
                            hidden: "true"
                        },
                        {
                            field: 'kd_sumberdana',
                            title: 'Rekening',
                            width: 80,
                            align: "left",
                            hidden: "true"
                        },
                        {
                            field: 'kd_skpd',
                            title: 'Kode SKPD',
                            width: 170,
                            align: "left"
                        },
                        {
                            field: 'nm_skpd',
                            title: 'Nama SKPD',
                            width: 350,
                            align: "left"
                        },
                        {
                            field: 'hapus',
                            title: 'Hapus',
                            width: 70,
                            align: "center",
                            formatter: function(value, rec) {
                                return '<img src="<?php echo base_url(); ?>/assets/images/icon/edit_remove.png" onclick="javascript:hapus_detail();" />';
                            }
                        }
                    ]
                ]
            });
        }

        function hapus_detail() {


            var rows = $('#dg2').edatagrid('getSelected');
            var bkdrek = $('#kode_u').combogrid('getValue');

            bkd_skpd = rows.kd_skpd;
            bnm_skpd = rows.nm_skpd;

            var idx = $('#dg2').edatagrid('getRowIndex', rows);
            var tny = confirm('Yakin Ingin Menghapus Data, ' + bnm_skpd + '  ?');

            if (tny == true) {

                $('#dg2').datagrid('deleteRow', idx);
                $('#dg2').datagrid('unselectAll');

                var urll = '<?php echo base_url(); ?>index.php/master/hapus_detail_sumber';
                $(document).ready(function() {
                    $.post(urll, ({
                        ckdrek: bkdrek,
                        skpdd: bkd_skpd
                    }), function(data) {
                        status = data;
                        if (status == '0') {
                            alert('Gagal Hapus..!!');
                            exit();
                        } else {
                            alert('Data Telah Terhapus..!!');
                            load_detail();
                            exit();
                        }
                    });
                });
            }
        }



        function simpan() {

            var rekening = $('#kode_u').combogrid('getValue');
            var nm_sdana = document.getElementById('nmkode').value;
            var nilaii = angka(document.getElementById('nil').value);
            var nilaiisilpa = angka(document.getElementById('nilsilpa').value);
            var vkdskpd = $("#dinas").combogrid('getValue');
            var idsumber = document.getElementById('id_sumber').value;

            if (rekening == '') {
                swal("success", "Kode Rekening Tidak Boleh Kosong", "success");
                exit();
            }

            if (nilaii == '') {
                swal("success", "Nilai Tidak Boleh Kosong", "success");
                exit();
            }

            /*if (nilaiisilpa==''){
                swal("success", "Nilai Silpa Tidak Boleh Kosong", "success");
                exit();
            }*/


            if (lcstatus == 'tambah') {

                lcinsert = "(kd_sumberdana,nm_sumberdana,nilai,nilaisilpa)";
                lcvalues = "('" + rekening + "','" + nm_sdana + "','" + nilaii + "','" + nilaiisilpa + "')";

                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/master/simpan_mastersumber',
                        data: ({
                            tabel: 'hsumber_dana',
                            kolom: lcinsert,
                            nilai: lcvalues,
                            cid: 'kd_sumberdana',
                            lcid: rekening,
                            lcskpd: vkdskpd,
                        }),
                        dataType: "json",
                        success: function(data) {
                            status = data.status;
                            // alert(status);
                            if (status == '0') {
                                alert('Gagal Simpan..!!');
                                exit();
                            }
                            /*else if (status=='1'){
                                                        alert('data sudah ada..!!');
                                                        exit();
                                                    }*/
                            else {
                                detsimpan(data.inserted_id);
                                exit();
                            }
                        }
                    });
                });

            } else {

                lcquery = "UPDATE hsumber_dana SET nilai='" + nilaii + "',nilaisilpa='" + nilaiisilpa + "' where kd_sumberdana='" + rekening + "' AND id ='" + idsumber + "'";

                lcquery1 = "UPDATE dsumber_dana SET kd_skpd='" + vkdskpd + "' where kd_sumberdana='" + rekening + "' AND id ='" + idsumber + "'";

                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/master/update_master',
                        data: ({
                            st_query: lcquery,
                            st_query1: lcquery1
                        }),
                        dataType: "json",
                        success: function(data) {
                            status = data;
                            if (status == '0') {
                                alert('Gagal Simpan..!!');
                                return;
                            } else {
                                alert('Data Tersimpan..!!');
                                $("#dialog-modal").dialog('close');
                                $('#dg').edatagrid('reload');

                            }
                        }
                    });
                });


            }
            /*{

                       lcinsert = "(nilai,nilaisilpa)";
                       lcvalues = "("+nilaii+"','"+nilaiisilpa+"')";
                       
                       $(document).ready(function(){
                           $.ajax({
                               type      : "POST",
                               url       : '<?php echo base_url(); ?>/index.php/master/simpan_master',
                               data      : ({tabel:'hsumber_dana',kolom:lcinsert,nilai:lcvalues,cid:'kd_sumberdana',lcid:rekening}),
                               dataType  : "json",
                               success   : function(data){
                                   status = data;
                                   if (status=='0'){
                                       alert('Gagal Simpan..!!');
                                       exit();
                                   }else{
                                       detsimpan();
                                       exit();
                                   }
                               }
                           });
                       });              
                   }*/
            $('#dg').edatagrid('reload');
        }

        function detsimpan(id) {


            var crek5 = $("#kode_u").combogrid("getValue");
            var csql = '';
            var call = $("input[name=cetak]:checked").val();
            $('#dg2').datagrid('selectAll');
            var rows = $('#dg2').datagrid('getSelections');

            if (call == '1') {
                for (var i = 0; i < rows.length; i++) {
                    cidx = rows[i].id;
                    skpd = rows[i].kd_skpd;
                    sdana = rows[i].kd_sumberdana;

                    if (i > 0) {
                        csql = csql + "," + "('" + sdana + "','" + skpd + "','" + id + "')";
                    } else {
                        csql = "values('" + sdana + "','" + skpd + "','" + id + "')";
                    }
                }
            } else {
                var sdana = $("#kode_u").combogrid("getValue");
                csql = "values('" + sdana + "','all','" + id + "')";
            }
            //alert(call);


            $.ajax({
                type: "POST",
                dataType: 'json',
                data: ({
                    tabel_detail: 'dsumber_dana',
                    sql_detail: csql,
                    proses: 'detail',
                    nomor: crek5,
                    id: id
                }),
                url: '<?php echo base_url(); ?>/index.php/master/simpan_detail_sdana',
                success: function(data) {
                    status = data;
                    if (status == '0') {
                        alert('Data Detail Gagal Tersimpan');
                    } else if (status == '1') {
                        alert('Data Detail Berhasil Tersimpan');
                        $('#dg2').edatagrid('unselectAll');
                        $('#dg').edatagrid('reload');
                        keluar();
                    }
                }
            });


            $("#rek5_hide").attr("Value", crek5);
            $('#dg2').edatagrid('unselectAll');
            $('#dg2').edatagrid('reload');

        }


        function edit_data() {
            lcstatus = 'edit';
            judul = 'Edit Data Sumber Dana';
            $("#dialog-modal").dialog({
                title: judul
            });
            $("#dialog-modal").dialog('open');
            $("#kode_u").combogrid("disable");
        }


        function tambah() {
            lcstatus = 'tambah';
            judul = 'Input Data Sumber Dana';
            $("#dialog-modal").dialog({
                title: judul
            });
            kosong();
            $("#dialog-modal").dialog('open');
            $("#kode_u").combogrid("enable");
            // document.getElementById("kode").disabled=false;
            // document.getElementById("kode").focus();
        }

        function keluar() {
            $("#dialog-modal").dialog('close');
        }



        function hapus() {
            var id_sumberr = document.getElementById('id_sumber').value;
            // alert(id_sumberr);
            var ckode = $("#kode_u").combogrid('getValue');
            //var c  = $("#kode_u").combogrid('getValue') ; 

            var urll = '<?php echo base_url(); ?>index.php/master/hapus_master';
            $(document).ready(function() {
                $.post(urll, ({
                    tabel: 'hsumber_dana',
                    cnid: ckode,
                    cid: id_sumberr,
                }), function(data) {
                    status = data;
                    if (status == '0') {
                        alert('Gagal Hapus..!!');
                        exit();
                    } else {
                        $('#dg').datagrid('deleteRow', lcidx);
                        hapus_detail_all();
                        swal("success", "Data Berhasil Dihapus..!!", "success");
                        kosong();
                        $("#dialog-modal").dialog('close');
                        exit();
                    }
                });
            });
        }


        function hapus_detail_all() {
            var id_sumberr = document.getElementById('id_sumber').value;
            var ckode = $("#kode_u").combogrid('getValue');
            var urll = '<?php echo base_url(); ?>index.php/master/hapus_detail_all';
            $(document).ready(function() {
                $.post(urll, ({
                    tabel: 'dsumber_dana',
                    cnid: ckode,
                    cid: id_sumberr,
                }), function(data) {
                    status = data;
                    if (status == '0') {
                        alert("Gagal Hapus Detail...!!!")
                    }
                });
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

        function append_save() {

            $('#dg2').datagrid('selectAll');
            var rows = $('#dg2').datagrid('getSelections');
            jgrid = rows.length - 1;
            pidx = jgrid + 1;


            var vnm_skpd = document.getElementById('nmskpd').value;
            var vkdrek = $("#kode_u").combogrid('getValue');
            var vkdskpd = $("#dinas").combogrid('getValue');

            if (vkdskpd == '') {
                alert('Pilih SKPD Terlebih Dahulu...!!!');
                document.getElementById('dinas').focus;
                exit();
            }

            if (vkdrek == '') {
                alert('Pilih Rekening Terlebih Dahulu...!!!');
                document.getElementById('rek5').focus;
                exit();
            }


            $('#dg2').edatagrid('appendRow', {
                kd_skpd: vkdskpd,
                kd_sumberdana: vkdrek,
                nm_skpd: vnm_skpd,
                id: pidx
            });
            $("#dg2").datagrid("unselectAll");
            // kosong_detail();

        }


        function opt(val) {
            ctk = val;
            if (ctk == '2') {
                //$("#div_skpd").hide();
                options = {
                    percent: 0
                };
                selectedEffect = "clip";
                $("#div_skpd").hide(selectedEffect, options, 1000);
                $("#inst").hide();
            } else if (ctk == '1') {

                $("#div_skpd").show(selectedEffect, options, 1000);
                $("#inst").show();
            } else {
                exit();
            }
        }

        function callback() {
            setTimeout(function() {
                $("#div_skpd").removeAttr("style").hide().fadeIn();
                //$( "#inst" ).removeAttr( "disabled" );
                document.getElementById("#inst").disabled = true;
            }, 1000);
        };
    </script>

</head>

<body>

    <div id="content">
        <h3 align="center"><u><b><a>INPUTAN MASTER SUMBER DANA</a></b></u></h3>
        <div align="center">
            <p align="center">
            <table style="width:100%;" border="0">
                <tr style="border-style:hidden;">
                    <td width="10%" style="border-style:hidden;">
                        <a class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>
                    </td>
                    <td width="5%" style="border-style:hidden;">
                        <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
                    </td>
                    <td>
                        <input type="text" value="" id="txtcari" style="width:300px;" />
                    </td>
                </tr>
                <tr>
                    <td colspan="4">
                        <table id="dg" title="LISTING DATA SUMBER DANA" style="width:900px;height:365px;">
                        </table>
                    </td>
                </tr>
            </table>
            </p>
        </div>
    </div>

    <div id="dialog-modal" title="">
        <p class="validateTips">Semua Inputan Harus Di Isi</p>
        <fieldset>
            <table align="center" style="width:100%;" border="0">
                <tr>
                    <td width="30%">SUMBER DANA</td>
                    <td width="1%">:</td>
                    <td><input type="text" id="kode_u" style="width:90px;" />
                        <input type="text" id="nmkode" style="width:350px;" readonly="true" />
                        <input type="text" id="id_sumber" style="width:350px;" readonly="true" hidden="true" />
                    </td>
                </tr>
                <tr>
                    <td width="30%">SILPA TAHUN LALU</td>
                    <td width="1%">:</td>
                    <td>
                        <input type="text" id="nilsilpa" style="width:150px; text-align: right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" />
                    </td>
                </tr>

                <tr>
                    <td width="30%">NILAI</td>
                    <td width="1%">:</td>
                    <td>
                        <input type="text" id="nil" style="width:150px; text-align: right;" onkeypress="javascript:return(currencyFormat(this,',','.',event))" />
                    </td>
                </tr>
            </table>
            <tr>
                <td colspan="2"><input type="radio" name="cetak" value="1" onclick="opt(this.value)" />Per SKPD &ensp;
                    <input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" />Keseluruhan
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div id="div_skpd">
                        <table style="width:100%;" border="0">
                            <tr>
                                <td width="30%">SKPD</td>
                                <td width="1%">:</td>
                                <td><input type="text" id="dinas" style="width:90px;" />
                                    <input type="text" id="nmskpd" style="width:350px;" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>

        </fieldset>
        <table style="width:755px;" border='0'>
            <tr>
                <td colspan="2">
                <td align="left">
                    <a class="easyui-linkbutton" id="inst" value="1" iconCls="icon-edit" plain="true" onclick="javascript:append_save();">Insert</a>
                </td>
                </td>

                <td align="right">
                    <!-- <a class="easyui-linkbutton" iconCls="icon-add"    plain="true" onclick="javascript:tambah();">Baru</a> -->
                    <a class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>
                    <a class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                    <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                </td>
            </tr>
            <table id="dg2" title="Listing Data" style="width:755px;height:300px;">
            </table>
        </table>
    </div>

</body>

</html>