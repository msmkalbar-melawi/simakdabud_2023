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

    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>

    <script type="text/javascript">
        var nip = '';
        var kdskpd = '';
        var kdrek5 = '';

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#dialog-modal").dialog({
                height: 400,
                width: 800
            });
            get_skpd();
            $("#perskpd").hide();
            $("#perunit").hide();
        });

        $(function() {
            $('#ttd').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd/load_ttdkasda/BUD',
                columns: [
                    [{
                            field: 'nip',
                            title: 'NIP',
                            width: 200
                        },
                        {
                            field: 'nama',
                            title: 'Nama',
                            width: 400
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    $("#nama").attr("value", rowData.nama);
                }
            });

            $('#ttd2').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd/load_ttd/PA',
                columns: [
                    [{
                            field: 'nip',
                            title: 'NIP',
                            width: 200
                        },
                        {
                            field: 'nama',
                            title: 'Nama',
                            width: 400
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    $("#nama2").attr("value", rowData.nama);
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

            $('#tgl_ttd_pajak').datebox({
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
                            width: 200
                        },
                        {
                            field: 'nm_skpd',
                            title: 'Nama SKPD',
                            width: 500
                        }
                    ]
                ],
                onSelect: function(rowIndex, rowData) {
                    kdskpd = rowData.kd_skpd;
                    $("#nmskpd").attr("value", rowData.nm_skpd);
                    $("#skpd").attr("value", rowData.kd_skpd);

                }
            });

            $('#sskpd').combogrid({
                panelWidth: 800,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd/kode_skpd',
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
                    kdskpd = rowData.kd_skpd;
                    $("#nm_skpd").attr("value", rowData.nm_skpd);
                    $("#sskpd").attr("value", rowData.kd_skpd);

                }
            });

            $("#tagih").hide();
            $("#perunit").hide();

        });

        function klik() {
            if (document.getElementById('jenis').value == "0") {
                //document.getElementById('belanja').value == "1";
            } else {
                //document.getElementById('belanja').value == "1";
            }
        }

        function opt(val) {
            ctk = val;
            if (ctk == '0') {
                $("#perskpd").hide();
                $("#perunit").hide();
            } else if (ctk == '1') {
                $("#perskpd").show();
                $("#perunit").hide();
            } else if (ctk == '2') {
                $("#perskpd").hide();
                $("#perunit").show();
            } else if (ctk == '3') {
                $("#perskpd").hide();
                $("#perunit").hide();
            } else {
                exit();
            }
        }

        function validate1() {
            var bln1 = document.getElementById('bulan1').value;

        }

        function get_skpd() {

            $.ajax({
                url: '<?php echo base_url(); ?>index.php/rka/config_skpd',
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $("#sskpd").attr("value", data.kd_skpd);
                    $("#nmskpd").attr("value", data.nm_skpd);
                    // $("#skpd").attr("value",rowData.kd_skpd);
                    kdskpd = data.kd_skpd;

                }
            });

        }

        function rekap_tpp(ctak) {
            var cetk = 1;

            var no_halaman = document.getElementById('no_halaman').value;
            var jns = 2;
            var belanja = document.getElementById('belanja').value;
            var ttd = $('#ttd').combogrid('getValue');
            var ttd = ttd.split(" ").join("abcdeefghi");
            //ttd = ttd.split(" ").join("123456789");
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            var ctglttd1 = $('#tgl_ttd1').datebox('getValue');
            var ctglttd_pajak = $('#tgl_ttd_pajak').datebox('getValue');


            if (ctglttd_pajak == '') {
                alert("Tanggal TTD harus dipilih");
                exit();
            }

            if (ttd == '') {
                alert("Kuasa BUD harus dipilih");
                exit();
            }

            var skpd = document.getElementById('sskpd').value;
            var url = "<?php echo site_url(); ?>tukd/rekap_tpp_pot";
            window.open(url + '/' + jns + '/' + belanja + '/' + ctak + '/' + no_halaman + '/' + cetk + '/' + ttd + '/' + skpd + '/' + ctglttd_pajak + '/DAFTAR-PAJAK' + ctglttd, '_blank');
            window.focus();


        }



        function cetak_rekap_pot(ctak) {
            var cetk = 1;
            /*
            	cetk=1 --> per skpd
            	cetk=2 --> keseluruhan
            	======================
            	jns=0 	-->gaji
            	jns=1   -->non gaji
            	jns=2	--> TPP
            	jns=3	-->gaji dprd
            	jns=4	-->keseluruhan
            	
            	ctak = jenis cetakkan
            	*/


            var jns = document.getElementById('jenis').value;

            var no_halaman = document.getElementById('no_halaman').value;
            var belanja = document.getElementById('belanja').value;
            var ttd = $('#ttd').combogrid('getValue');
            var ttd = ttd.split(" ").join("abcdeefghi");
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            var ctglttd1 = $('#tgl_ttd1').datebox('getValue');
            var ctglttd_pajak = $('#tgl_ttd_pajak').datebox('getValue');

            if (ctglttd == '' || ctglttd1 == '') {
                alert("Tanggal periode masih kosong");
                exit();
            }

            if (ctglttd_pajak == '') {
                alert("Tanggal TTD harus dipilih");
                exit();
            }

            if (ttd == '') {
                alert("Kuasa BUD harus dipilih");
                exit();
            }

            var url = "<?php echo site_url(); ?>tukd/rekap_pot_pajak";
            window.open(url + '/' + jns + '/' + belanja + '/' + ctak + '/' + ctglttd + '/' + ctglttd1 + '/' + no_halaman + '/' + cetk + '/' + ttd + '/REKAP/' + ctglttd_pajak + '/DAFTAR-PAJAK' + ctglttd, '_blank');
            window.focus();














        }







        function cetak(ctak) {
            var cetk = ctk;
            var no_halaman = document.getElementById('no_halaman').value;
            var jns = document.getElementById('jenis').value;
            var belanja = document.getElementById('belanja').value;
            var ttd = $('#ttd').combogrid('getValue');
            var ttd = ttd.split(" ").join("abcdeefghi");
            var ctglttd = $('#tgl_ttd').datebox('getValue');
            var ctglttd1 = $('#tgl_ttd1').datebox('getValue');
            var ctglttd_pajak = $('#tgl_ttd_pajak').datebox('getValue');

            if (ctglttd == '' || ctglttd1 == '') {
                alert("Tanggal periode masih kosong");
                exit();
            }

            if (ctglttd_pajak == '') {
                alert("Tanggal TTD harus dipilih");
                exit();
            }

            if (ttd == '') {
                alert("Kuasa BUD harus dipilih");
                exit();
            }

            if ((jns == '0' && cetk == '0') ||
                (jns == '0' && cetk == '4') ||
                (jns == '1' && cetk == '0') ||
                (jns == '1' && cetk == '4') ||
                (jns == '2' && cetk == '0') ||
                (jns == '2' && cetk == '4') ||
                (jns == '3' && cetk == '0') ||
                (jns == '3' && cetk == '4') ||
                (jns == '4' && cetk == '0') ||
                (jns == '4' && cetk == '4')) {

                var url = "<?php echo site_url(); ?>tukd/ctk_daftar_pajak";
                window.open(url + '/' + jns + '/' + belanja + '/' + ctak + '/' + ctglttd + '/' + ctglttd1 + '/' + no_halaman + '/' + cetk + '/' + ttd + '/' + 'x' + '/' + ctglttd_pajak + '/DAFTAR-PAJAK' + ctglttd, '_blank');
                window.focus();

            } else if ((jns == '0' && cetk == '1') ||
                (jns == '1' && cetk == '1') ||
                (jns == '2' && cetk == '1') ||
                (jns == '3' && cetk == '1') ||
                (jns == '4' && cetk == '1')) {

                var skpd = document.getElementById('skpd').value;
                var url = "<?php echo site_url(); ?>tukd/ctk_daftar_pajak";
                window.open(url + '/' + jns + '/' + belanja + '/' + ctak + '/' + ctglttd + '/' + ctglttd1 + '/' + no_halaman + '/' + cetk + '/' + ttd + '/' + skpd + '/' + ctglttd_pajak + '/DAFTAR-PAJAK' + ctglttd, '_blank');
                window.focus();

            } else if ((jns == '0' && cetk == '2') ||
                (jns == '1' && cetk == '2') ||
                (jns == '2' && cetk == '2') ||
                (jns == '3' && cetk == '2') ||
                (jns == '4' && cetk == '2')) {

                var skpd = document.getElementById('sskpd').value;
                var url = "<?php echo site_url(); ?>tukd/ctk_daftar_pajak";
                window.open(url + '/' + jns + '/' + belanja + '/' + ctak + '/' + ctglttd + '/' + ctglttd1 + '/' + no_halaman + '/' + cetk + '/' + ttd + '/' + skpd + '/' + ctglttd_pajak + '/DAFTAR-PAJAK' + ctglttd, '_blank');
                window.focus();
            } else if ((jns == '0' && cetk == '3') ||
                (jns == '1' && cetk == '3') ||
                (jns == '2' && cetk == '3') ||
                (jns == '3' && cetk == '3') ||
                (jns == '4' && cetk == '3')) {

                var skpd = document.getElementById('sskpd').value;
                var url = "<?php echo site_url(); ?>tukd/ctk_daftar_pajak";
                window.open(url + '/' + jns + '/' + belanja + '/' + ctak + '/' + ctglttd + '/' + ctglttd1 + '/' + no_halaman + '/' + cetk + '/' + ttd + '/' + skpd + '/' + ctglttd_pajak + '/DAFTAR-PAJAK' + ctglttd, '_blank');
                window.focus();
            } else {
                alert("Maaf Data Tidak Tersedia");
            }
        }
    </script>

    <STYLE TYPE="text/css">
        input.right {
            text-align: right;
        }
    </STYLE>

</head>

<body>

    <div id="content">




        <div id="">
            <fieldset style="border-radius: 10px">
                <!-- <legend>
                    <h2>Daftar Pemotongan Pajak</h2>
                </legend> -->

                <p align="right">
                <table border="0" id="sp2d" title="Cetak Daftar Potongan Pajak" style="width:100%;height:200px;">
                    <p> informasi : untuk cetakan rekap TPP,tidak perlu memilih JENIS SP2D dan tanggal periode, sedangkan cetakan rekap pot pajak, tidak perlu memilih JENIS SP2D</p>
                    <tr>
                        <td colspan="4">
                            <table style="width:100%;" border="0">
                                <td width="20%">Jenis SP2D</td>
                                <td>
                                    <select class="select" style="width: 200px; " name="jenis" id="jenis">
                                        <option value="0"> GAJI </option>
                                        <option value="3"> GAJI DPRD </option>
                                        <option value="1"> NON GAJI</option>
                                        <option value="2"> T P P </option>
                                        <option value="4"> KESELURUHAN </option>

                                    </select>
                                </td>
                            </table>
                        </td>
                    </tr>
                    <tr hidden>
                        <td colspan="4">
                            <table style="width:100%;" border="0">
                                <td width="20%">Jenis Belanja</td>
                                <td>
                                    <select class="select" style="width: 200px" name="belanja" id="belanja">
                                        <option value="0"> BL </option>
                                        <option value="1"> BTL </option>
                                        <option value="2"> BL dan BTL </option>
                                    </select>
                                </td>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <div id="div_tgl">
                                <table style="width:100%;" border="0">
                                    <td width="20%">Tanggal</td>
                                    <td><input type="text" id="tgl_ttd" style="width: 200px;" /> &nbsp; s/d &nbsp; <input type="text" id="tgl_ttd1" style="width: 200px;" /></td>
                                    <td width="20%"></td>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <table style="width:100%;" border="0">
                                <tr hidden>
                                    <td><input type="radio" name="cetak" value="0" onclick="opt(this.value)" /><b>Rekap per SKPD</b></td>
                                </tr>
                                <tr>
                                    <td><input type="radio" name="cetak" value="1" id="status" onclick="opt(this.value)" /><b>Per SKPD</b>
                                        <div id="perskpd">
                                            <table>
                                                <tr>
                                                    <td><B>SKPD</B></td>
                                                    <td><input id="skpd" name="skpd" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 300px; border:0;" /></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td hidden><input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" /><b>Per Unit</b>
                                        <div id="perunit">
                                            <table>
                                                <tr>
                                                    <td><B>Unit</B></td>
                                                    <td><input id="sskpd" name="sskpd" style="width: 200px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nm_skpd" name="nm_skpd" style="width: 300px; border:0;" /></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="radio" name="cetak" value="3" onclick="opt(this.value)" /><b>Keseluruhan</b></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="width:100%;" border="0">
                                <td width="20%">Tanggal TTD</td>
                                <td>
                                    <input type="text" id="tgl_ttd_pajak" style="width: 200px;" />
                                </td>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <table style="width:100%;" border="0">
                                <td width="20%">Kuasa BUD</td>
                                <td>
                                    <input type="text" id="ttd" style="width: 200px;" /> &nbsp;&nbsp;
                                    <input type="nama" id="nama" readonly="true" style="width: 200px;border:0" />
                                </td>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="width:100%;" border="0">
                                <td width="20%">No. Halaman</td>
                                <td><input type="number" id="no_halaman" style="width: 200px;" value="1" /></td>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" align="center">
                            <a class="button" onclick="javascript:cetak(0);"> <i class="fa fa-print"></i> Cetak Layar</a>
                            <a class="button" onclick="javascript:cetak(1);"> <i class="fa fa-pdf"></i> Cetak Pdf</a>
                            <a class="button" onclick="javascript:cetak(2);"> <i class="fa fa-excel"></i> Cetak Excel</a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" align="center">
                            <a class="button" onclick="javascript:cetak_rekap_pot(0);"> <i class="fa fa-print"></i> Cetak Rekap Pot Pajak </a>
                            <a class="button" onclick="javascript:cetak_rekap_pot(1);"> <i class="fa fa-pdf"></i> Cetak Rekap Pot Pajak Pdf</a>
                            <a class="button" onclick="javascript:cetak_rekap_pot(2);"> <i class="fa fa-excel"></i> Cetak Rekap Pot Pajak Excel</a>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="2" align="center">
                            <a class="button" onclick="javascript:rekap_tpp(0);"> <i class="fa fa-print"></i> Cetak Rekap TPP (PPH 21 & BPJS) </a>
                            <a class="button" onclick="javascript:rekap_tpp(1);"> <i class="fa fa-pdf"></i> Cetak Rekap TPP Pdf</a>
                            <a class="button" onclick="javascript:rekap_tpp(2);"> <i class="fa fa-excel"></i> Cetak Rekap TPP Excel</a>
                        </td>
                    </tr>

                </table>
                </p>

            </fieldset>
        </div>
    </div>


</body>

</html>