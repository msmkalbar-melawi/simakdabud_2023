<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/sweetalert-master/dist/sweetalert2.css" />
    <style>
        #tagih {
            position: relative;
            width: 922px;
            height: 100px;
            padding: 0.4em;
        }

        input.right {
            text-align: right;
        }
    </style>
</head>

<body>
    <div id="content">
        <h3>CETAK LAPORAN REALISASI ANGGARAN SKPD</h3>
        <div id="">
            <p align="right">
            <table id="sp2d" title="Cetak" style="width:922px;height:200px;">
                <tr>
                    <td width="900px" colspan="2"><input type="radio" name="cetak" value="1" id="status" onclick="opt(this.value)" /><b>SKPD</b>
                        <div id="kode_skpd">
                            <table style="width:100%;" border="0">
                               
                                    <td width="900px" height="40%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input id="sskpd" name="sskpd" style="width: 200px;" />
                                    </td>
                                </tr>
                                <tr>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 500px; border:0;" /></td></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="div_periode">
                            <table style="width:100%;" border="0">
                                <td width="250px" height="40%"><B>Bulan&nbsp;&nbsp;&nbsp;&nbsp;</B></td>
                                <td width="900px"><input type="text" id="bulan" style="width: 100px;" />
                                </td>
                                <!-- <td width="20%">Periode</td>
                    <td><input type="text" id="tgl1" style="width: 200px;"/> s.d. <input type="text" id="tgl2" style="width: 200px;" />
                    </td> -->
                            </table>
                        </div>
                    </td>
                </tr>
                <tr >
			<td width="21%" height="40" ><B>JENIS ANGGARAN</B></td>
			<td width="70%"><input id="jns_ang" name="jns_ang" type="text"  style="width:150px" /></td>
		</tr>
                <tr>
                    <td colspan="2">
                        <div id="div_periode">
                            <table style="width:100%;" border="0">
                                <td width="250px" height="40%"><B>Tanggal TTD</B></td>
                                <td width="900px"><input type="text" id="tgl_ttd" style="width: 100px;" />
                                </td>
                            </table>
                        </div>
                    </td>
                </tr>
                </tr>
                <tr>
                    <td width = "200px" ><b>Ringkasan</b></td>
                    <td>
                        <a class="button" plain="true" onclick="javascript:cetak(1);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" plain="true" onclick="javascript:cetak(0);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" plain="true" onclick="javascript:cetak(2);"><i class="fa fa-excel"></i> Excel</a>
                        <a class="button" plain="true" onclick="javascript:cetak(3);"><i class="fa fa-word"></i> Word</a>
                    </td>
                </tr>
                <tr>
                    <td><b>Akun Jenis</b></td>
                    <td>
                        <a class="button" plain="true" onclick="javascript:cetak2(1);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" plain="true" onclick="javascript:cetak2(0);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" plain="true" onclick="javascript:cetak2(2);"><i class="fa fa-excel"></i> Excel</a>
                        <a class="button" plain="true" onclick="javascript:cetak2(3);"><i class="fa fa-word"></i> Word</a>
                    </td>
                </tr>
                <tr>
                    <td><b>Akun Rincian</b></td>
                    <td>
                        <a class="button" plain="true" onclick="javascript:cetak3(1);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" plain="true" onclick="javascript:cetak3(0);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" plain="true" onclick="javascript:cetak3(2);"><i class="fa fa-excel"></i> Excel</a>
                    </td>
                </tr>
                <tr>
                    <td><b>Penjabaran</b></td>
                    <td>
                        <a class="button" plain="true" onclick="javascript:cetak4(1);"><i class="fa fa-print"></i> Layar</a>
                        <a class="button" plain="true" onclick="javascript:cetak4(0);"><i class="fa fa-pdf"></i> PDF</a>
                        <a class="button" plain="true" onclick="javascript:cetak4(2);"><i class="fa fa-excel"></i> Excel</a>
                        <a class="button" plain="true" onclick="javascript:cetak4(3);"><i class="fa fa-word"></i> Word</a>
                        <br> &nbsp;
                    </td>
                </tr>
            </table>
            </p>


        </div>

    </div>

    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery-1.8.0.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>easyui/jquery.edatagrid.js"></script>
    <script src="<?php echo base_url(); ?>easyui/jquery-ui.min.js"></script>

    <script type="text/javascript">
        var nip = '';
        var kdskpd = '';
        var kdrek5 = '';
        var bulan = '';
        var status_ang = '';

        $(document).ready(function() {
            $("#accordion").accordion();
            $("#dialog-modal").dialog({
                height: 100,
                width: 922
            });

            $('#sskpd').combogrid({
                panelWidth: 630,
                idField: 'kd_skpd',
                textField: 'kd_skpd',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/akuntansi/skpd',
                columns: [
                    [{
                            field: 'kd_skpd',
                            title: 'Kode SKPD',
                            width: 150
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
                    load_ttd_skpd(kdskpd);
                }
            });

            $('#jns_ang').combogrid({  
                    panelWidth:400,  
                    idField:'kode',  
                    textField:'nama',  
                    mode:'remote',
                    url:'<?php echo base_url(); ?>index.php/rka_ro/load_jang/',  
                    columns:[[  
                        {field:'nama',title:'Nama',width:400}    
                    ]]
                });


            $('#dcetak').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
                }
            });

            $('#dcetak2').datebox({
                required: true,
                formatter: function(date) {
                    var y = date.getFullYear();
                    var m = date.getMonth() + 1;
                    var d = date.getDate();
                    return y + '-' + m + '-' + d;
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

            $("#kode_skpd").hide();
        });

        function submit() {
            if (ctk == '') {
                alert('Pilih Jenis Cetakan');
                exit();
            }
            document.getElementById("frm_ctk").submit();
        }

        function opt(val) {
            ctk = val;
            if (ctk == '1') {
                $("#kode_skpd").show();
            } else {
                exit();
            }
        }

        function opt_ttd(val) {
            status_ang = val;
        }

        function load_ttd_skpd(kdskpd) {
            var skpddd = kdskpd;
            $('#ttd').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd/load_ttd_bend/PA/' + skpddd,
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
                    $("#nmttd").attr("value", rowData.nama);
                }

            });
            $('#ttd2').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/tukd/load_ttd_bend/PPK/' + skpddd,
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
                    $("#nmttd2").attr("value", rowData.nama);
                }

            });
        }

        function cetak($pilih) {
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            //var ttdx   = $("#ttd").combogrid('getValue');
            //var ttdz   = $("#ttd2").combogrid('getValue');
            //var ttd1 =ttdx.split(" ").join("a");
            //var ttd2 =ttdz.split(" ").join("a");
            var tgl_ttd = $("#tgl_ttd").combogrid('getValue');
            var csskpd = $("#sskpd").combogrid('getValue');
            var jns_ang = $("#jns_ang").combogrid('getValue');

            if (status_ang == '') {
                status_ang = 1;
            }

            if (ctk == 1) {
                urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra/' + cbulan;
                if (cbulan == '') {
                    swal("Error", "Pilih Bulan dulu", "warning");
                    exit();
                }
                if (tgl_ttd == '') {
                    swal("Error", "Pilih Tanggal TTD dulu", "warning");
                    exit();
                }
            }

            ttd1 = '-';
            ttd2 = '-';
            tgl_ttd = '-';

            window.open(urll + '/' + pilih + '/' + ttd1 + '/' + ttd2 + '/' + tgl_ttd + '/' + csskpd + '/' + pilih + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak2($pilih) {
            //var ttdx   = $("#ttd").combogrid('getValue');
            //var ttdz   = $("#ttd2").combogrid('getValue');
            //var ttd1 =ttdx.split(" ").join("a");
            //var ttd2 =ttdz.split(" ").join("a");
            var pilih = $pilih;
            // alert(pilih);
            cbulan = $('#bulan').combogrid('getValue');
            //var ctglttd = $('#tgl_ttd').datebox('getValue');            
            var csskpd = $("#sskpd").combogrid('getValue');
            var jns_ang = $("#jns_ang").combogrid('getValue');

            if (csskpd == '4.02.01.00') {
                urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_jenis_ppkd/' + cbulan;

            } else {
                urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_jenis/' + cbulan;

            }

            if (cbulan == '') {
                    swal("Error", "Pilih Bulan dulu", "warning");
                    exit();
                }
                if (tgl_ttd == '') {
                    swal("Error", "Pilih Tanggal TTD dulu", "warning");
                    exit();
                }

            if (status_ang == '') {
                status_ang = 1;
            }

            ttd1 = '-';
            ttd2 = '-';
            ctglttd = '-';

            window.open(urll + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + csskpd + '/' + pilih + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak3($pilih) {
            //var ttdx   = $("#ttd").combogrid('getValue');
            //var ttdz   = $("#ttd2").combogrid('getValue');
            //var ttd1 =ttdx.split(" ").join("a");
            //var ttd2 =ttdz.split(" ").join("a");
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            //var ctglttd = $('#tgl_ttd').datebox('getValue');
            var csskpd = $("#sskpd").combogrid('getValue');
            var jns_ang = $("#jns_ang").combogrid('getValue');
            if (csskpd == '4.02.01.00') {
                urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_rincian_ppkd/' + cbulan;

            } else {
                urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_rincian/' + cbulan;

            }
            if (cbulan == '') {
                    swal("Error", "Pilih Bulan dulu", "warning");
                    exit();
                }
                if (tgl_ttd == '') {
                    swal("Error", "Pilih Tanggal TTD dulu", "warning");
                    exit();
                }

            if (status_ang == '') {
                status_ang = 1;
            }

            ttd1 = '-';
            ttd2 = '-';
            ctglttd = '-';

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + csskpd  + '/' + pilih + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak4($pilih) {
            //var ttdx   = $("#ttd").combogrid('getValue');
            //var ttdz   = $("#ttd2").combogrid('getValue');
            //var ttd1 =ttdx.split(" ").join("a");
            //var ttd2 =ttdz.split(" ").join("a");
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            //var ctglttd = $('#tgl_ttd').datebox('getValue');
            var csskpd = $("#sskpd").combogrid('getValue');
            var jns_ang = $("#jns_ang").combogrid('getValue');
            

            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran/' + cbulan;
            if (cbulan == '') {
                    swal("Error", "Pilih Bulan dulu", "warning");
                    exit();
                }
                if (tgl_ttd == '') {
                    swal("Error", "Pilih Tanggal TTD dulu", "warning");
                    exit();
                }

            if (status_ang == '') {
                status_ang = 1;
            }

            ttd1 = '-';
            ttd2 = '-';
            ctglttd = '-';

            window.open(urll + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + csskpd + '/' + pilih + '/' + jns_ang, '_blank');
            window.focus();

        }

        function cetak8($pilih) {
            //var ttdx   = $("#ttd").combogrid('getValue');
            //var ttdz   = $("#ttd2").combogrid('getValue');
            //var ttd1 =ttdx.split(" ").join("a");
            //var ttd2 =ttdz.split(" ").join("a");
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            //var ctglttd = $('#tgl_ttd').datebox('getValue');
            var csskpd = $("#sskpd").combogrid('getValue');

            urll = '<?php echo base_url(); ?>index.php/akuntansi_add/cetak_lra_bulan_penjabaran_ang/' + cbulan;
            if (cbulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }

            if (status_ang == '') {
                status_ang = 1;
            }

            ttd1 = '-';
            ttd2 = '-';
            ctglttd = '-';

            window.open(urll + '/' + pilih + '/' + ctglttd + '/' + ttd1 + '/' + ttd2 + '/' + csskpd + '/' + status_ang, '_blank');
            window.focus();

        }

        $(function() {
            $('#bulan').combogrid({
                panelWidth: 120,
                panelHeight: 300,
                idField: 'bln',
                textField: 'nm_bulan',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/rka/bulan',
                columns: [
                    [{
                        field: 'nm_bulan',
                        title: 'Nama Bulan',
                        width: 700
                    }]
                ],
                onSelect: function(rowIndex, rowData) {
                    bulan = rowData.nm_bulan;
                    $("#bulan").attr("value", rowData.nm_bulan);
                }
            });
        });

        function runEffect() {
            var selectedEffect = 'blind';
            var options = {};
            $("#tagih").toggle(selectedEffect, options, 500);
        };

        function pilih() {
            op = '1';
        };
    </script>
</body>

</html>