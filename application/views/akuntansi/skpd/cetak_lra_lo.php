<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/default/easyui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/themes/icon.css">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>easyui/demo/demo.css">
    <link href="<?php echo base_url(); ?>easyui/jquery-ui.css" rel="stylesheet" type="text/css" />

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
        <h3>CETAK LAPORAN OPERASIONAL SKPD</h3>
        <div id="accordion">

            <p align="right">
            <table id="sp2d" title="Cetak" style="width:100%;height:200px;">
                <tr>
                    <td width="922px" colspan="2"><input type="radio" name="cetak" value="2" id="status" onclick="opt(this.value)" /><b>Per Unit</b>
                        <div id="kode_skpd">
                            <table style="width:100%;" border="0">
                                <tr>
                                    <td width="22px" height="40%"><B>Unit&nbsp;&nbsp;</B></td>
                                    <td width="900px"><input id="sskpd" name="sskpd" style="width: 165px;" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input id="nmskpd" name="nmskpd" style="width: 670px; border:0;" /></td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="div_periode">
                            <table style="width:100%;" border="0">
                                <td width="22px" height="40%"><B>Bulan&nbsp;&nbsp;&nbsp;&nbsp;</B></td>
                                <td width="900px"><input type="text" id="bulan" style="width: 100px;" />
                                </td>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="div_tgl">
                            <table style="width:100%;" border="0">
                                <td width="22px" height="40%"><B>Tanggal TTD</B></td>
                                <td width="900px"><input type="text" id="tgl_ttd" style="width: 100px;" />
                                </td>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>

                <tr>
                    <td colspan="2" style="border-bottom:hidden;border-spacing: 3px;padding:3px 3px 3px 3px;"><B>Label Audited<select name="label" id="label">
                                <option value="1">Unaudited</option>
                                <option value="2">Audited</option>
                            </select></td>
                </tr>

                <tr>
                    <td colspan="2">
                        <input id="qttd2" name="qttd2" type="checkbox" value="2" onclick="javascript:runEffect2();" /><B> Tanpa TTD</b>
                        <input id="qttd" name="qttd" type="checkbox" value="1" onclick="javascript:runEffect1();" /><B> Dengan TTD SKPD &nbsp;&nbsp; </b>
                        <input id="qttd3" name="qttd3" type="checkbox" value="3" onclick="javascript:runEffect3();" /><B> Dengan TTD WALIKOTA &nbsp;&nbsp; </b>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div id="div_ttd">
                            <table style="width:100%;" border="0">
                                <td width="22px" height="40%"><B>PA&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong></strong></B></td>
                                <td width="900px"><input id="ttd" name="ttd" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd" name="nmttd" style="width: 170px;border:0" />
                                </td>
                            </table>
                            <table style="width:100%;" border="0">
                                <td width="22px" height="40%"><B>PPK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</B></td>
                                <td width="900px"><input id="ttd2" name="ttd" style="width: 170px;" /> &nbsp; &nbsp; &nbsp; <input id="nmttd2" name="nmttd" style="width: 170px;border:0" />
                                </td>
                            </table>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><b> Cetak Per Jenis </b></td>
                    <td width="80%">
                        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak(1);">Layar</a>
                        <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak(0);">Pdf</a>
                        <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak(2);">Excel</a>
                        <a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak(3);">Word</a>
                    </td>
                    </td>
                </tr>
                <tr>
                    <td><b> Cetak Per Obyek </b></td>
                    <td width="80%">
                        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_obyek(1);">Layar</a>
                        <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_obyek(0);">Pdf</a>
                        <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak_obyek(2);">Excel</a>
                        <a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak_obyek(3);">Word</a>
                    </td>
                    </td>
                </tr>
                <tr>
                    <td><b> Cetak Per Rincian </b></td>
                    <td width="80%">
                        <a class="easyui-linkbutton" iconCls="icon-print" plain="true" onclick="javascript:cetak_rincian(1);">Layar</a>
                        <a class="easyui-linkbutton" iconCls="icon-pdf" plain="true" onclick="javascript:cetak_rincian(0);">Pdf</a>
                        <a class="easyui-linkbutton" iconCls="icon-excel" plain="true" onclick="javascript:cetak_rincian(2);">Excel</a>
                        <a class="easyui-linkbutton" iconCls="icon-word" plain="true" onclick="javascript:cetak_rincian(3);">Word</a>
                    </td>
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
        var pilihanttd = '';

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
                url: '<?php echo base_url(); ?>index.php/akuntansi/skpd_2',
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
                    ttd_skpd(kdskpd);
                }
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


        function submit() {
            if (ctk == '') {
                alert('Pilih Jenis Cetakan');
                exit();
            }
            document.getElementById("frm_ctk").submit();
        }

        // function opt(val) {
        //     ctk = val;
        //     if (ctk == '1') {
        //         $("#tagih").hide();
        //         $("#dcetak").datebox("setValue", '');
        //         $("#dcetak2").datebox("setValue", '');
        //     } else if (ctk == '2') {
        //         $("#tagih").show();
        //     } else {
        //         exit();
        //     }
        // }


        function ttd_skpd(kdskpd) {
            var xskpd = kdskpd;

            $('#ttd').combogrid({
                panelWidth: 600,
                idField: 'nip',
                textField: 'nip',
                mode: 'remote',
                url: '<?php echo base_url(); ?>index.php/akuntansi_skpd/load_ttd/PA/' + xskpd,
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
                url: '<?php echo base_url(); ?>index.php/akuntansi_skpd/load_ttd/PPK/' + xskpd,
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

        function opt(val) {
            ctk = val;
            if (ctk == '1') {
                // urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo';
            } else if (ctk == '2') {
                $("#kode_skpd").show();
                // urll ='<?php echo base_url(); ?>index.php/akuntansi/cetak_lra_lo_unit/'+kdskpd+'/'+ctk;
            } else {
                exit();
            }
            // $('#frm_ctk').attr('action',urll);                        
        }

        function runEffect1() {
            $('#qttd2')._propAttr('checked', false);
            $('#qttd3')._propAttr('checked', false);
            pilihanttd = "1";
        };

        function runEffect2() {
            $('#qttd')._propAttr('checked', false);
            $('#qttd3')._propAttr('checked', false);
            pilihanttd = "2";
        };

        function runEffect3() {
            $('#qttd')._propAttr('checked', false);
            $('#qttd2')._propAttr('checked', false);
            pilihanttd = "3";
        };

        function cetak($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var tgl_ttd = $("#tgl_ttd").combogrid('getValue');
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var label = document.getElementById('label').value;

            urll = '<?php echo base_url(); ?>index.php/akuntansi_skpd/cetak_lra_lo/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }
            if (kdskpd == '') {
                alert("Pilih Unit dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + tgl_ttd + '/' + ttd1 + '/' + ttd2 + '/' + kdskpd + '/' + pilihanttd + '/' + label + '/LO Jenis', '_blank');
            window.focus();

        }

        function cetak_obyek($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var tgl_ttd = $("#tgl_ttd").combogrid('getValue');
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var label = document.getElementById('label').value;

            urll = '<?php echo base_url(); ?>index.php/akuntansi_skpd/cetak_lra_lo_obyek/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }
            if (kdskpd == '') {
                alert("Pilih Unit dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + tgl_ttd + '/' + ttd1 + '/' + ttd2 + '/' + kdskpd + '/' + pilihanttd + '/' + label + '/LO Obyek', '_blank');
            window.focus();

        }

        function cetak_rincian($pilih) {
            var ttdx = $("#ttd").combogrid('getValue');
            var ttdz = $("#ttd2").combogrid('getValue');
            var ttd1 = ttdx.split(" ").join("a");
            var ttd2 = ttdz.split(" ").join("a");
            var tgl_ttd = $("#tgl_ttd").combogrid('getValue');
            var pilih = $pilih;
            cbulan = $('#bulan').combogrid('getValue');
            var label = document.getElementById('label').value;

            urll = '<?php echo base_url(); ?>index.php/akuntansi_skpd/cetak_lra_lo_rincian/' + cbulan;
            if (bulan == '') {
                alert("Pilih Bulan dulu");
                exit();
            }
            if (kdskpd == '') {
                alert("Pilih Unit dulu");
                exit();
            }

            window.open(urll + '/' + pilih + '/' + tgl_ttd + '/' + ttd1 + '/' + ttd2 + '/' + kdskpd + '/' + pilihanttd + '/' + label + '/LO Rincian', '_blank');
            window.focus();
        }

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