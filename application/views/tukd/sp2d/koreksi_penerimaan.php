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
                height: 360,
                width: 900,
                modal: true,
                autoOpen: false,
            });

            get_nourut();
            //seting_tombol();

        });

        $(function() {
            $('#dg').edatagrid({
                url: '<?php echo base_url(); ?>/index.php/tukd/load_koreksi_penerimaan',
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
                            field: 'no',
                            title: 'NOMOR KAS',
                            width: 10,
                            align: "center"
                        },
                        {
                            field: 'tanggal',
                            title: 'TANGGAL',
                            width: 20,
                            align: "left"
                        },
                        {
                            field: 'nilai',
                            title: 'Nilai',
                            width: 20,
                            align: "right"
                        },
                        {
                            field: 'keterangan',
                            title: 'KETERANGAN',
                            width: 40,
                            align: "center"
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    no = rowData.no;
                    tanggal = rowData.tanggal;
                    keterangan = rowData.keterangan;
                    lcnilai = rowData.nilai;
                    lcjenis = rowData.jenis;
                    lckd_skpd = rowData.kd_skpd;
                    lcnm_skpd = rowData.nm_skpd;
                    status = rowData.status;
                    lcidx = rowIndex;
                    get(no, tanggal, keterangan, lcnilai, lcjenis, lckd_skpd, lcnm_skpd, status);


                },
                onDblClickRow: function(rowIndex, rowData) {
                    lcidx = rowIndex;
                    judul = 'Edit Data Buku Kas Penerimaan';
                    edit_data();
                }

            });


            $('#tgl_ttd').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });

            $('#tgl_ttd1').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });

            $('#skpd').combogrid({
                panelWidth: 700,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/rka/skpd',
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
                    kode = rowData.kd_skpd;
                    //tgl_awal = $('#tgl_kas').datebox('getValue');               
                    $("#nmskpd").attr("value", rowData.nm_skpd.toUpperCase());
                    $('#jenis').combogrid({
                        url: '<?php echo base_url(); ?>index.php/tukd/load_rekening_ppkd/' + kode
                    });
                }
            });

            $('#jenis').combogrid({
                panelWidth: 700,
                idField: 'kd_rek6',
                textField: 'kd_rek6',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd/load_rekening_ppkd/' + kode,
                columns: [
                    [{
                            field: 'kd_rek6',
                            title: 'Kode Rekening',
                            width: 140
                        },
                        {
                            field: 'nm_rek6',
                            title: 'Nama Rekening',
                            width: 700
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    kd_rek5 = rowData.kd_rek6;
                    $("#nmjenis").attr("value", rowData.nm_rek6);
                }

            });

            $('#nilai').keypress(function(e) {
                if (e.which == 13) {
                    simpan();
                }
            });

        });


        function get(no, tanggal, keterangan, lcnilai, lcjenis, lckd_skpd, lcnm_skpd, status) {
            $("#no_kas").attr("value", no);
            $("#tgl_ttd1").datebox("setValue", tanggal);
            $("#uraian").attr("value", keterangan);
            $("#nilai").attr("value", lcnilai);
            $("#skpd").combogrid("setValue", lckd_skpd);
            $("#nmskpd").attr("value", lcnm_skpd);
            $("#jenis").combogrid("setValue", lcjenis);

            var nil = angka(document.getElementById('nilai').value);

            if (nil < 0) {
                document.getElementById("minus").checked = true;
            } else {
                document.getElementById("minus").checked = false;
            }


            if (status == '1') {
                document.getElementById("ngaruh").checked = true;
            } else {
                document.getElementById("ngaruh").checked = false;
            }

        }

        function get_nourut() {
            $.ajax({
                url: '<?php echo base_url(); ?>index.php/tukd/no_urut_ppkd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#no_kas").attr("value", data.no_urut);
                }
            });
        }

        function kosong() {
            $("#jns_beban").attr("value", '1');
            $("#uraian").attr("value", '');
            $("#nilai").attr("value", '');
            $("#nmjenis").attr("value", '');

        }

        function seting_tombol() {
            $('#tambah').linkbutton('disable');
            $('#simpan').linkbutton('disable');
            $('#hapus').linkbutton('disable');
        }

        function simpan() {
            var no_kas = document.getElementById('no_kas').value;
            var kd_skpd = $('#skpd').combogrid('getValue');
            var nm_skpd = document.getElementById('nmskpd').value;
            var jenis = $('#jenis').combogrid('getValue');
            var nmjenis = document.getElementById('nmjenis').value;
            var uraian = document.getElementById('uraian').value;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            var lntotal = angka(document.getElementById('nilai').value);

            if (document.getElementById('minus').checked) {
                var minus = lntotal * -1;
            } else {
                var minus = lntotal;
            }

            if (document.getElementById('ngaruh').checked) {
                var ngaruh = '1';
            } else {
                var ngaruh = '0';
            }


            lctotal = number_format(lntotal, 0, '.', ',');

            if (no_kas == '') {
                alert('Pilih no dulu')
                exit()
            }
            if (ctglttd == '') {
                alert('Pilih Tanggal dulu')
                exit()
            }


            if (lcstatus == 'tambah') {
                $(document).ready(function() {
                    // alert(csql);
                    $.ajax({
                        type: "POST",
                        dataType: 'json',
                        data: ({
                            no: no_kas,
                            tabel: 'trkasout_ppkd',
                            field: 'no'
                        }),
                        url: '<?php echo base_url(); ?>/index.php/tukd/cek_simpan',
                        success: function(data) {
                            status_cek = data.pesan;
                            if (status_cek == 1) {
                                alert("Nomor Telah Dipakai!");
                                document.getElementById("nomor").focus();
                                exit();
                            }
                            if (status_cek == 0) {
                                alert("Nomor Bisa dipakai");

                                $(document).ready(function() {
                                    $.ajax({
                                        type: "POST",
                                        url: '<?php echo base_url(); ?>/index.php/tukd/simpan_koreksi_penerimaan',
                                        data: ({
                                            tabel: 'trkasout_ppkd',
                                            nokas: no_kas,
                                            kd_skpd: kd_skpd,
                                            nm_skpd: nm_skpd,
                                            jns: jenis,
                                            nmjenis: nmjenis,
                                            ket: uraian,
                                            ttd: ctglttd,
                                            nilai: minus,
                                            ngaruh: ngaruh
                                        }),
                                        dataType: "json",
                                        success: function(data) {
                                            status = data;
                                            if (status == '0') {
                                                alert('Gagal Simpan..!!');
                                                exit();
                                            } else {
                                                alert('Data Tersimpan..!!');
                                                $('#dg').edatagrid('reload');
                                                $("#dialog-modal").dialog('close');
                                                $("#nilai").attr("value", '');
                                                $("#no_kas").attr("value", '');
                                                $("#uraian").attr("value", '');
                                                $("#skpd").combogrid("setValue", '');
                                                $("#nmskpd").attr("value", '');
                                            }
                                        }
                                    });
                                    //akhir
                                });
                            }
                        }
                    });
                });
            } else {

                /*  lcquery = "UPDATE penerimaan_lain_ppkd SET uraian='"+uraian+"', jenis='"+jenis+"', nilai='"+lntotal+"', tgl_kas='"+ctglttd+"' where no_kas='"+no_kas+"'"; */

                $(document).ready(function() {
                    $.ajax({
                        type: "POST",
                        url: '<?php echo base_url(); ?>/index.php/tukd/update_koreksi_penerimaan',
                        data: ({
                            nokas: no_kas,
                            kd_skpd: kd_skpd,
                            nm_skpd: nm_skpd,
                            jns: jenis,
                            nmjenis: nmjenis,
                            ket: uraian,
                            ttd: ctglttd,
                            nilai: minus,
                            ngaruh: ngaruh
                        }),
                        dataType: "json",
                        success: function(data) {
                            status = data;
                            if (status == '0') {
                                alert('Gagal Simpan..!!');
                                exit();
                            } else {
                                alert('Data Tersimpan..!!');
                                keluar();
                                $('#dg').edatagrid('reload');
                            }
                        }
                    });
                });


            }


        }



        function edit_data() {
            lcstatus = 'edit';
            judul = 'Edit Data Koreksi Penerimaan';
            $("#dialog-modal").dialog({
                title: judul
            });
            $("#dialog-modal").dialog('open');
            $('#del').linkbutton('enable');
            //document.getElementById("no_kas").disabled=true;
            var a = angka(document.getElementById('nilai').value);
            if (a < 0) {
                tos = a * -1;
            } else {
                tos = a;
            }

            $("#nilai").attr("value", tos);
        }


        function tambah() {
            lcstatus = 'tambah';
            judul = 'Input Data Koreksi Penerimaan';
            $("#dialog-modal").dialog({
                title: judul
            });
            kosong();
            $("#dialog-modal").dialog('open');
            $('#del').linkbutton('disable');


        }

        function keluar() {
            $("#dialog-modal").dialog('close');
        }

        function cari() {
            var kriteria = document.getElementById("txtcari").value;
            $(function() {
                $('#dg').edatagrid({
                    url: '<?php echo base_url(); ?>/index.php/tukd/load_koreksi_penerimaan',
                    queryParams: ({
                        cari: kriteria
                    })
                });
            });
        }

        function cetak(ctk) {
            alert("Construction");

            /* var  ttd = $('#tgl_ttd').combogrid('getValue');
		    ttd = ttd.split(" ").join("123456789");
			var url    = "<?php echo site_url(); ?>/tukd/buku_kas_penerimaan_pengeluaran";  
			
			if(ttd==''){
			alert('Pilih Tanggal dulu')
			exit()
			}
			
			window.open(url+'/'+ctk+'/-/'+ttd, '_blank');
			window.focus(); */

        }

        function hapus() {
            var no_kas = document.getElementById('no_kas').value;
            var ctglttd = $('#tgl_ttd1').datebox('getValue');
            //var lntotal = angka(document.getElementById('nilai').value);
            var urll = '<?php echo base_url(); ?>/index.php/tukd/hapus_koreksi_penerimaan';
            if (no_kas != '') {
                var del = confirm('Anda yakin akan menghapus no ' + no_kas + '  ?');
                if (del == true) {
                    $(document).ready(function() {
                        $.post(urll, ({
                            no_kas: no_kas,
                            tgl_kas: ctglttd
                        }), function(data) {
                            status = data;
                            if (status == '0') {
                                alert('Gagal Simpan..!!');
                                exit();
                            } else {
                                alert('Data Terhapus..!!');
                                keluar();
                                $('#dg').edatagrid('reload');
                            }
                        });
                    });
                }
            }
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
        <div id="accordion">
            <h3 align="center"><u><b><a href="#" id="section1">KOREKSI PENERIMAAN</a></b></u></h3>
            <div>
                <p align="right">
                    <a id="tambah" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="javascript:tambah()">Tambah</a>
                    <a class="easyui-linkbutton" iconCls="icon-search" plain="true" onclick="javascript:cari();">Cari</a>
                    <input type="text" value="" id="txtcari" />
                <table id="dg" title="Listing KOREKSI PENERIMAAN" style="width:870px;height:480px;">
                </table>
                </p>
            </div>

        </div>

    </div>

    <div id="dialog-modal" title="">
        <p class="validateTips">Semua Inputan Harus Di Isi.</p>
        <fieldset>
            <table align="center" style="width:100%;" border="0">
                <tr>
                    <td>No Kas</td>
                    <td></td>
                    <td><input type="text" id="no_kas" style="width: 60px;" readonly="true" disabled /></td>
                </tr>
                <tr>
                    <td>S K P D</td>
                    <td></td>
                    <td><input id="skpd" name="skpd" style="width: 140px;" /></td>

                </tr>
                <tr>
                    <td>Nama SKPD</td>
                    <td></td>
                    <td colspan="2" align="left"><input type="text" id="nmskpd" style="border:0;width: 450px;" readonly="true" /></td>
                </tr>
                <tr>
                    <td>Jenis</td>
                    <td></td>
                    <td><input id="jenis" name="jenis" style="width: 100px;" /></td>

                </tr>
                <tr>
                    <td>Nama Jenis</td>
                    <td></td>
                    <td><input type="text" id="nmjenis" readonly="true" style="border:0;width: 600px;" /></td>

                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td></td>
                    <td><input type="text" id="tgl_ttd1" style="width: 100px;" /></td>

                </tr>

                <tr>
                    <td>Uraian</td>
                    <td></td>
                    <td><textarea name="uraian" id="uraian" cols="60" rows="1"></textarea></td>

                </tr>

                <tr>
                    <td>Nilai</td>
                    <td></td>
                    <td><input type="text" id="nilai" style="width: 140px; text-align: right;" onkeypress="return(currencyFormat(this,',','.',event))" />
                        <input type="checkbox" name="minus" id="minus" value="minus"><b>Minus</b>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="ngaruh" id="ngaruh" value="real"><i><b>(Mempengaruhi Realisasi Pendapatan)</b></i>
                    </td>
                    </td>

                </tr>
                <tr>
                    <td colspan="3" align="center"><a id="simpan" class="easyui-linkbutton" iconCls="icon-save" plain="true" onclick="javascript:simpan();">Simpan</a>[Enter]
                        <a id="hapus" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="javascript:hapus();">Hapus</a>
                        <a class="easyui-linkbutton" iconCls="icon-undo" plain="true" onclick="javascript:keluar();">Kembali</a>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
</body>

</html>